<?php

namespace Modules\SISIG\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class SeguridadController extends Controller
{
    /**
     * Muestra el panel de Seguridad y Accesos con métricas en tiempo real,
     * logs de auditoría, sesiones activas y gestión de acceso a usuarios.
     */
    public function index(Request $request)
    {
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
            $logsQuery->where(function ($q) use ($filtroUsuario) {
                $q->where('users.name', 'like', "%{$filtroUsuario}%")
                  ->orWhere('users.email', 'like', "%{$filtroUsuario}%")
                  ->orWhere('users.nickname', 'like', "%{$filtroUsuario}%");
            });
        }

        if (!empty($filtroAccion) && $filtroAccion !== 'todas') {
            $logsQuery->where('actividadreciente.tipo_evento', $filtroAccion);
        }

        $actividades = $logsQuery->orderBy('actividadreciente.created_at', 'desc')->get()->map(function ($item) {
            $fecha = Carbon::parse($item->fecha_hora ?? $item->created_at);
            
            // Detectar o asignar IP y Módulo desde la información del log o por defecto
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

            // Consultar rol del usuario
            $rol = 'Usuario';
            if ($item->user_id) {
                $rol = $this->obtenerRolNombre($item->user_id);
            }

            return (object) [
                'id' => $item->id_actividad,
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

            foreach ($dbSessions as $sess) {
                $user = User::withTrashed()->find($sess->user_id);
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
                    'rol' => $this->obtenerRolNombre($user->id),
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
                'rol' => $this->obtenerRolNombre($currentUser->id),
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

        $kpiAccionesHoy = DB::table('actividadreciente')
            ->whereDate('created_at', Carbon::today())
            ->count();

        $kpiPasswordsRestablecidas = DB::table('actividadreciente')
            ->where('tipo_evento', 'restablecimiento')
            ->count();

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
                            'Sesión activa cerrada por el administrador del sistema.',
                            'Seguridad'
                        );
                    }
                }
            }

            return redirect()->route('sisig.admin.seguridad.index')
                ->with('success', 'La sesión seleccionada ha sido cerrada exitosamente en tiempo real.');
        } catch (\Exception $e) {
            return redirect()->route('sisig.admin.seguridad.index')
                ->with('error', 'Error al cerrar la sesión: ' . $e->getMessage());
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
                    'tipo_evento' => $tipoEvento,
                    'mensaje' => $mensaje,
                    'fecha_hora' => Carbon::now(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        } catch (\Exception $e) {
            // Registro silencioso para no romper flujo principal
        }
    }

    /**
     * Obtiene el nombre del rol principal del usuario.
     */
    private function obtenerRolNombre($userId)
    {
        $user = User::withTrashed()->with('roles')->find($userId);
        if (!$user) {
            return 'Usuario';
        }

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

        return 'Aprendiz';
    }

    /**
     * Parsea la cadena del User-Agent para extraer navegador y sistema operativo.
     */
    private function parseUserAgent($userAgent)
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
