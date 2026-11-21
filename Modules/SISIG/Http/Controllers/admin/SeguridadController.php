<?php

namespace Modules\SISIG\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class SeguridadController extends Controller
{
    /**
     * Muestra el panel de Seguridad y Accesos con métricas en tiempo real,
     * logs de auditoría, sesiones activas y gestión de acceso a usuarios.
     */
    public function index(Request $request)
    {
        // -------------------------------------------------------------
        // AUTORIZACIÓN ESTRICTA EN SERVIDOR (DEFENSA EN PROFUNDIDAD)
        // -------------------------------------------------------------
        if (!Auth::check() || (!Auth::user()->hasRole('admin_sisig') && !Auth::user()->hasRole('sisig.admin') && !Auth::user()->hasRole('superadmin'))) {
            abort(403, 'Acceso denegado: Se requieren privilegios de Administrador SISIG para acceder al panel de seguridad.');
        }

        Carbon::setLocale('es');

        // -------------------------------------------------------------
        // 1. FILTROS Y REGISTRO DE LOGS (HISTORIAL DE ACTIVIDADES)
        // -------------------------------------------------------------
        $filtroUsuario = trim($request->input('usuario', ''));
        $filtroAccion = trim($request->input('accion', ''));

        $logsQuery = DB::table('actividadreciente')
            ->leftJoin('users', 'actividadreciente.user_id', '=', 'users.id')
            ->select(
                'actividadreciente.*',
                'users.name as user_name',
                'users.email as user_email',
                'users.nickname as user_nickname'
            )
            ->where('actividadreciente.created_at', '>=', Carbon::now()->subHours(24));

        if (!empty($filtroUsuario)) {
            // Escapar comodines para prevenir comportamientos anómalos en LIKE
            $escapedUsuario = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $filtroUsuario);
            $logsQuery->where(function ($q) use ($escapedUsuario) {
                $q->where('users.name', 'like', "%{$escapedUsuario}%")
                  ->orWhere('users.email', 'like', "%{$escapedUsuario}%")
                  ->orWhere('users.nickname', 'like', "%{$escapedUsuario}%");
            });
        }

        if (!empty($filtroAccion) && $filtroAccion !== 'todas') {
            $logsQuery->where('actividadreciente.tipo_evento', $filtroAccion);
        }

        $actividadesRaw = $logsQuery->orderBy('actividadreciente.created_at', 'desc')->get();

        // Optimización N+1: Cargar usuarios y roles en un único lote indexado por ID
        $userIds = $actividadesRaw->pluck('user_id')->filter()->unique()->toArray();
        $usuariosCargados = !empty($userIds)
            ? User::withTrashed()->with('roles')->whereIn('id', $userIds)->get()->keyBy('id')
            : collect();

        $actividades = $actividadesRaw->map(function ($item) use ($usuariosCargados) {
            $fecha = Carbon::parse($item->fecha_hora ?? $item->created_at);
            
            // Detectar módulo temático a partir del evento
            $modulo = 'Sistema';
            $ip = '127.0.0.1';

            if (str_contains(strtolower($item->tipo_evento), 'sesion') || str_contains(strtolower($item->mensaje), 'sesión') || str_contains(strtolower($item->mensaje), 'login')) {
                $modulo = 'Autenticación';
            } elseif (str_contains(strtolower($item->tipo_evento), 'perfil') || str_contains(strtolower($item->mensaje), 'perfil')) {
                $modulo = 'Mi Perfil';
            } elseif (str_contains(strtolower($item->tipo_evento), 'quiz') || str_contains(strtolower($item->tipo_evento), 'examen')) {
                $modulo = 'Evaluaciones';
            } elseif (str_contains(strtolower($item->tipo_evento), 'seguridad') || str_contains(strtolower($item->tipo_evento), 'bloqueo') || str_contains(strtolower($item->tipo_evento), 'restablecimiento')) {
                $modulo = 'Seguridad';
            }

            // Mapear etiqueta legible y color para la vista
            $accionLabel = 'General';
            $accionColor = 'slate';

            switch ($item->tipo_evento) {
                case 'inicio_sesion':
                case 'login':
                    $accionLabel = 'Inicio sesión';
                    $accionColor = 'emerald';
                    break;
                case 'edicion':
                case 'actualizacion':
                    $accionLabel = 'Edición';
                    $accionColor = 'sky';
                    break;
                case 'restablecimiento':
                case 'password_reset':
                    $accionLabel = 'Restablecimiento';
                    $accionColor = 'amber';
                    break;
                case 'bloqueo':
                case 'desactivacion':
                    $accionLabel = 'Bloqueo';
                    $accionColor = 'rose';
                    break;
                default:
                    $accionLabel = ucfirst(str_replace('_', ' ', $item->tipo_evento));
                    $accionColor = 'slate';
            }

            // Obtener rol desde el lote precargado en memoria (sin consultas SQL adicionales)
            $rol = 'Usuario';
            if ($item->user_id && isset($usuariosCargados[$item->user_id])) {
                $rol = $this->extraerNombreRol($usuariosCargados[$item->user_id]);
            }

            return (object) [
                'id' => $item->id_actividad ?? ($item->id ?? null),
                'fecha_hora' => $fecha->format('d M Y - h:i a'),
                'usuario' => $item->user_name ?? ($item->user_nickname ?? 'Sistema'),
                'rol' => $rol,
                'accion' => $accionLabel,
                'accion_color' => $accionColor,
                'modulo' => $modulo,
                'ip' => $ip,
                'mensaje' => $item->mensaje,
            ];
        });

        // -------------------------------------------------------------
        // 2. SESIONES ACTIVAS EN TIEMPO REAL
        // -------------------------------------------------------------
        $sesionesActivas = collect();

        if (Schema::hasTable('sessions')) {
            // Umbral de 120 minutos de actividad
            $limiteActividad = Carbon::now()->subMinutes(120)->timestamp;

            $dbSessions = DB::table('sessions')
                ->whereNotNull('user_id')
                ->where('last_activity', '>=', $limiteActividad)
                ->orderBy('last_activity', 'desc')
                ->get();

            $sessionUserIds = $dbSessions->pluck('user_id')->filter()->unique()->toArray();
            $sessionUsers = !empty($sessionUserIds)
                ? User::withTrashed()->with('roles')->whereIn('id', $sessionUserIds)->get()->keyBy('id')
                : collect();

            foreach ($dbSessions as $sess) {
                $user = $sessionUsers->get($sess->user_id);
                if (!$user) {
                    continue;
                }

                $dispositivo = $this->parseUserAgent($sess->user_agent ?? '');
                $ultimoAcceso = Carbon::createFromTimestamp($sess->last_activity)->diffForHumans();

                $sesionesActivas->push((object) [
                    'session_id' => $sess->id,
                    'user_id' => $user->id,
                    'nombre' => $user->name,
                    'email' => $user->email,
                    'rol' => $this->extraerNombreRol($user),
                    'ultimo_acceso' => $ultimoAcceso,
                    'ip' => $sess->ip_address ?? '127.0.0.1',
                    'dispositivo_icono' => $dispositivo['icono'],
                    'dispositivo_texto' => $dispositivo['texto'],
                    'estado' => 'En línea',
                ]);
            }
        }

        // Si la tabla sessions está vacía o el driver de sesión es archivo,
        // generar la sesión del usuario actual autenticado para reflejar la realidad
        if ($sesionesActivas->isEmpty() && Auth::check()) {
            $currentUser = Auth::user();
            $dispositivo = $this->parseUserAgent($request->userAgent() ?? '');
            
            $sesionesActivas->push((object) [
                'session_id' => session()->getId(),
                'user_id' => $currentUser->id,
                'nombre' => $currentUser->name,
                'email' => $currentUser->email,
                'rol' => $this->extraerNombreRol($currentUser),
                'ultimo_acceso' => 'En este momento',
                'ip' => $request->ip() ?? '127.0.0.1',
                'dispositivo_icono' => $dispositivo['icono'],
                'dispositivo_texto' => $dispositivo['texto'],
                'estado' => 'En línea',
            ]);
        }

        // -------------------------------------------------------------
        // 3. CÁLCULO DE KPIS EN TIEMPO REAL
        // -------------------------------------------------------------
        $kpiSesionesActivas = $sesionesActivas->count();

        $kpiAccionesHoy = Schema::hasTable('actividadreciente')
            ? DB::table('actividadreciente')->whereDate('created_at', Carbon::today())->count()
            : 0;

        $kpiPasswordsRestablecidas = Schema::hasTable('actividadreciente')
            ? DB::table('actividadreciente')->where('tipo_evento', 'restablecimiento')->count()
            : 0;

        $kpiCuentasBloqueadas = User::onlyTrashed()->count();

        return view('sisig::admin.SeguridadModulo', compact(
            'actividades',
            'sesionesActivas',
            'kpiSesionesActivas',
            'kpiAccionesHoy',
            'kpiPasswordsRestablecidas',
            'kpiCuentasBloqueadas',
            'filtroUsuario',
            'filtroAccion'
        ));
    }

    /**
     * Cierra forzosamente una sesión activa de un usuario en tiempo real.
     */
    public function cerrarSesion($sessionId)
    {
        // Autorización estricta
        if (!Auth::check() || (!Auth::user()->hasRole('admin_sisig') && !Auth::user()->hasRole('sisig.admin') && !Auth::user()->hasRole('superadmin'))) {
            abort(403, 'Acceso denegado: Se requieren privilegios de Administrador SISIG.');
        }

        // Validación del identificador de sesión
        if (!is_string($sessionId) || empty(trim($sessionId)) || strlen($sessionId) > 255) {
            return redirect()->route('sisig.admin.seguridad.index')
                ->with('error', 'Identificador de sesión inválido.');
        }

        // Prevenir auto-cierre accidental de la sesión del administrador en uso
        if ($sessionId === session()->getId()) {
            return redirect()->route('sisig.admin.seguridad.index')
                ->with('info', 'No puedes forzar el cierre de tu propia sesión activa desde esta acción. Utiliza la opción de Salir del menú.');
        }

        try {
            if (Schema::hasTable('sessions')) {
                $sessionData = DB::table('sessions')->where('id', $sessionId)->first();

                if ($sessionData) {
                    $userId = $sessionData->user_id;

                    DB::table('sessions')->where('id', $sessionId)->delete();

                    if ($userId) {
                        $this->registrarLog(
                            $userId,
                            'cierre_sesion',
                            'Sesión activa cerrada forzosamente por el Administrador SISIG (' . Auth::user()->name . ').',
                            'Seguridad'
                        );
                    }
                }
            }

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'session_id' => $sessionId,
                    'message' => 'La sesión seleccionada ha sido cerrada exitosamente en tiempo real.'
                ]);
            }

            return redirect()->route('sisig.admin.seguridad.index')
                ->with('success', 'La sesión seleccionada ha sido cerrada exitosamente en tiempo real.');
        } catch (\Exception $e) {
            Log::error('Error al cerrar sesión activa en SISIG: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al cerrar la sesión: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('sisig.admin.seguridad.index')
                ->with('error', 'Ocurrió un error inesperado al cerrar la sesión. La incidencia fue registrada.');
        }
    }

    /**
     * Registra un evento en la tabla de auditoría en tiempo real.
     */
    private function registrarLog($userId, $tipoEvento, $mensaje, $modulo = 'Seguridad')
    {
        try {
            if (Schema::hasTable('actividadreciente')) {
                DB::table('actividadreciente')->insert([
                    'user_id' => $userId,
                    'tipo_evento' => substr($tipoEvento, 0, 50),
                    'mensaje' => substr($mensaje, 0, 255),
                    'fecha_hora' => Carbon::now(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        } catch (\Exception $e) {
            Log::warning('No se pudo registrar log de auditoría en actividadreciente: ' . $e->getMessage());
        }
    }

    /**
     * Obtiene el nombre legible del rol principal de un modelo User precargado.
     */
    private function extraerNombreRol(User $user): string
    {
        if ($user->roles && $user->roles->isNotEmpty()) {
            foreach ($user->roles as $role) {
                $slug = strtolower($role->slug ?? '');
                if (str_contains($slug, 'admin')) {
                    return 'Admin';
                }
                if (str_contains($slug, 'editor')) {
                    return 'Editor';
                }
                if (str_contains($slug, 'aprendiz')) {
                    return 'Aprendiz';
                }
            }
            return $user->roles->first()->name ?? 'Usuario';
        }

        if (strtolower($user->nickname ?? '') === 'aprendiz') {
            return 'Aprendiz';
        }

        return 'Usuario';
    }

    /**
     * Parsea la cadena del User-Agent para extraer navegador y sistema operativo de forma segura.
     */
    private function parseUserAgent(?string $userAgent): array
    {
        $browser = 'Navegador Web';
        $os = 'Dispositivo';
        $icono = 'fas fa-desktop';

        if (empty($userAgent)) {
            return [
                'icono' => 'fas fa-desktop',
                'texto' => 'Navegador Web / Windows',
            ];
        }

        // Detectar SO
        if (preg_match('/windows|win32/i', $userAgent)) {
            $os = 'Windows';
            $icono = 'fas fa-desktop';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            $os = 'macOS';
            $icono = 'fas fa-laptop';
        } elseif (preg_match('/linux/i', $userAgent)) {
            $os = 'Linux';
            $icono = 'fas fa-desktop';
        } elseif (preg_match('/android/i', $userAgent)) {
            $os = 'Android';
            $icono = 'fas fa-mobile-alt';
        } elseif (preg_match('/iphone|ipad|ipod/i', $userAgent)) {
            $os = 'iOS';
            $icono = 'fas fa-mobile-alt';
        }

        // Detectar Navegador
        if (preg_match('/edg/i', $userAgent)) {
            $browser = 'Edge';
        } elseif (preg_match('/chrome|crios/i', $userAgent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/firefox|fxios/i', $userAgent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/safari/i', $userAgent)) {
            $browser = 'Safari';
        } elseif (preg_match('/opera|opr/i', $userAgent)) {
            $browser = 'Opera';
        }

        return [
            'icono' => $icono,
            'texto' => "{$browser} / {$os}",
        ];
    }
}
