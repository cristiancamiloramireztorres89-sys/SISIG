<?php

namespace Modules\SISIG\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReportesController extends Controller
{
    /**
     * Muestra la vista de Seguimiento y Reportes por sección (Calidad, SST, Ambiental)
     * con listado de Aprobados y No aprobados, y detalle de respuestas.
     */
    public function index(Request $request)
    {
        // 1. Obtener todos los módulos formativos de la base de datos de manera dinámica
        $modulos = DB::table('sisig_modulos')
            ->where('estado', '!=', 'inactivo')
            ->orderBy('id', 'asc')
            ->get();

        if ($modulos->isEmpty()) {
            $modulos = DB::table('sisig_modulos')->orderBy('id', 'asc')->get();
        }

        // Asignar icono visual a cada módulo
        $modulos = $modulos->map(function ($mod) {
            $tit = strtolower($mod->titulo ?? '');
            if (str_contains($tit, 'calidad') || str_contains($tit, '9001')) {
                $mod->icono_base = 'fas fa-certificate';
                $mod->color_icono = 'text-emerald-400';
            } elseif (str_contains($tit, 'sst') || str_contains($tit, 'seguridad') || str_contains($tit, 'salud') || str_contains($tit, '45001')) {
                $mod->icono_base = 'fas fa-hard-hat';
                $mod->color_icono = 'text-amber-400';
            } elseif (str_contains($tit, 'ambiental') || str_contains($tit, 'ambiente') || str_contains($tit, '14001')) {
                $mod->icono_base = 'fas fa-leaf';
                $mod->color_icono = 'text-emerald-400';
            } elseif (!empty($mod->icon_class)) {
                $mod->icono_base = trim(preg_replace('/text-[a-z0-9\/-]+/', '', $mod->icon_class)) ?: 'fas fa-layer-group';
                $mod->color_icono = 'text-sky-400';
            } else {
                $mod->icono_base = 'fas fa-layer-group';
                $mod->color_icono = 'text-sky-400';
            }
            $mod->icono = $mod->icono_base . ' ' . $mod->color_icono;
            return $mod;
        });

        // 2. Determinar el módulo o sección activa dinámicamente
        $seccionInput = $request->input('seccion');
        $moduloSeleccionado = null;

        if ($seccionInput) {
            if (is_numeric($seccionInput)) {
                $moduloSeleccionado = $modulos->firstWhere('id', (int) $seccionInput);
            }
            if (!$moduloSeleccionado) {
                $moduloSeleccionado = $modulos->first(function ($m) use ($seccionInput) {
                    return strtolower(trim($m->titulo)) === strtolower(trim($seccionInput));
                });
            }
            if (!$moduloSeleccionado) {
                $moduloSeleccionado = $modulos->first(function ($m) use ($seccionInput) {
                    return str_contains(strtolower($m->titulo), strtolower($seccionInput));
                });
            }
        }

        if (!$moduloSeleccionado) {
            $moduloSeleccionado = $modulos->first(function ($m) {
                return str_contains(strtolower($m->titulo), 'calidad');
            }) ?? $modulos->first();
        }

        $seccion = $moduloSeleccionado ? $moduloSeleccionado->titulo : 'General';
        $buscar = trim($request->input('buscar', ''));

        // 3. Consultar quices asociados al módulo activo
        $quizIds = [];
        if ($moduloSeleccionado) {
            $quizIds = DB::table('sisig_quices')
                ->where(function ($q) use ($moduloSeleccionado) {
                    $q->where('modulo_id', $moduloSeleccionado->id)
                      ->orWhere('titulo', 'like', "%{$moduloSeleccionado->titulo}%");

                    if (!empty($moduloSeleccionado->norma)) {
                        $q->orWhere('titulo', 'like', "%{$moduloSeleccionado->norma}%");
                    }
                })
                ->pluck('id_quiz')
                ->toArray();
        }

        // 2. Consultar aprendices registrados
        $query = User::query();

        if (method_exists(User::class, 'person')) {
            $query->with('person');
        }

        // Excluir administradores y editores
        if (method_exists(User::class, 'roles')) {
            $query->whereDoesntHave('roles', function ($q) {
                $q->whereIn('slug', ['admin_sisig', 'sisig.admin', 'editor_sisig']);
            });
        }

        // Filtro de búsqueda
        if (!empty($buscar)) {
            $query->where(function ($q) use ($buscar) {
                $q->where('name', 'like', "%{$buscar}%")
                  ->orWhere('email', 'like', "%{$buscar}%")
                  ->orWhere('nickname', 'like', "%{$buscar}%");

                if (Schema::hasTable('people')) {
                    $q->orWhereHas('person', function ($qp) use ($buscar) {
                        $qp->where('first_name', 'like', "%{$buscar}%")
                           ->orWhere('first_last_name', 'like', "%{$buscar}%")
                           ->orWhere('second_last_name', 'like', "%{$buscar}%")
                           ->orWhere('document_number', 'like', "%{$buscar}%");
                    });
                }
            });
        }

        $usuarios = $query->orderBy('name')->get();

        $aprobadosList = collect();
        $noAprobadosList = collect();
        $pendientesCount = 0;

        foreach ($usuarios as $aprendiz) {
            // Documento de identidad
            $documento = 'N/A';
            if (isset($aprendiz->person) && !empty($aprendiz->person->document_number)) {
                $docNum = $aprendiz->person->document_number;
                $documento = is_numeric($docNum) ? number_format((float) $docNum, 0, '', '.') : (string) $docNum;
            } elseif (!empty($aprendiz->documento)) {
                $documento = (string) $aprendiz->documento;
            }

            // Ficha de formación
            $ficha = 'Sin asignar';
            if (!empty($aprendiz->person_id) && Schema::hasTable('apprentices')) {
                $fichaVal = DB::table('apprentices')->where('person_id', $aprendiz->person_id)->value('course_id');
                if ($fichaVal) {
                    $ficha = $fichaVal;
                }
            } elseif (!empty($aprendiz->ficha)) {
                $ficha = $aprendiz->ficha;
            }

            // Iniciales del aprendiz para el avatar
            $nombreLimpio = trim($aprendiz->name ?? 'Aprendiz');
            $partes = preg_split('/\s+/', $nombreLimpio);
            $iniciales = '';
            if (count($partes) >= 2) {
                $iniciales = mb_strtoupper(mb_substr($partes[0], 0, 1) . mb_substr($partes[1], 0, 1));
            } else {
                $iniciales = mb_strtoupper(mb_substr($nombreLimpio, 0, 2));
            }

            // Verificar si el aprendiz presentó la evaluación de esta sección
            $resultado = null;
            if (!empty($quizIds)) {
                $resultado = DB::table('sisig_resultados')
                    ->where('user_id', $aprendiz->id)
                    ->whereIn('id_quiz', $quizIds)
                    ->orderByDesc('id_resultado')
                    ->first();
            }

            if ($resultado) {
                $puntaje = (int) round($resultado->puntaje ?? 0);
                $intentos = $resultado->intento ?? 1;
                $fueAnulado = (bool) ($resultado->fue_anulado ?? false);
                $esAprobado = !$fueAnulado && ($puntaje >= 70 || $resultado->aprobado == 1);
                $fechaRaw = $resultado->fecha_presentacion ?? $resultado->created_at ?? now();
                $fecha = Carbon::parse($fechaRaw)->translatedFormat('d M Y');

                $item = (object) [
                    'id'                     => $aprendiz->id,
                    'nombre'                 => $nombreLimpio,
                    'iniciales'              => $iniciales,
                    'documento'              => $documento,
                    'correo'                 => $aprendiz->email ?? 'N/A',
                    'ficha'                  => $ficha,
                    'puntaje'                => $puntaje,
                    'intentos'               => $intentos,
                    'fecha'                  => $fecha,
                    'aprobado'               => $esAprobado,
                    'fue_anulado'            => $fueAnulado,
                    'motivo_anulacion'       => $resultado->motivo_anulacion ?? null,
                    'infracciones_detectadas'=> (int) ($resultado->infracciones_detectadas ?? 0),
                    'motivo_no_aprobado'     => $fueAnulado ? 'Anulado por Trampa (Infracciones)' : 'Respuestas incorrectas (Puntaje bajo)',
                    'quiz_id'                => $resultado->id_quiz,
                ];

                if ($item->aprobado) {
                    $aprobadosList->push($item);
                } else {
                    $noAprobadosList->push($item);
                }
            } else {
                $pendientesCount++;
            }
        }

        // Métricas
        $totalEvaluados = $aprobadosList->count() + $noAprobadosList->count();
        $metrics = [
            'total'        => $totalEvaluados > 0 ? $totalEvaluados : $usuarios->count(),
            'aprobados'    => $aprobadosList->count(),
            'pendientes'   => $pendientesCount,
            'no_aprobados' => $noAprobadosList->count(),
        ];

        return view('sisig::editor.reportes.index', compact(
            'metrics',
            'seccion',
            'buscar',
            'aprobadosList',
            'noAprobadosList',
            'modulos',
            'moduloSeleccionado'
        ));
    }
}