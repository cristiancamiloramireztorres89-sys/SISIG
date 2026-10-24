<?php

namespace Modules\SISIG\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\SISIG\Entities\InfraccionQuiz;
use Modules\SISIG\Entities\Resultado;

class InfraccionesController extends Controller
{
    /**
     * Muestra el módulo de auditoría de infracciones y quices anulados por trampa.
     */
    public function index(Request $request)
    {
        $seccion = strtolower($request->input('seccion', 'todas'));
        if (!in_array($seccion, ['todas', 'calidad', 'sst', 'ambiental'])) {
            $seccion = 'todas';
        }

        $buscar = trim($request->input('buscar', ''));

        // Consulta de resultados que fueron anulados o que tuvieron infracciones anti-trampa registradas
        $query = Resultado::with([
            'user' => function ($q) {
                if (method_exists(User::class, 'person')) {
                    $q->with('person');
                }
            },
            'quiz.modulo',
            'infracciones'
        ])
        ->where(function ($q) {
            $q->where('fue_anulado', true)
              ->orWhere('infracciones_detectadas', '>', 0);
        });

        // Filtro por Sección SIG
        if ($seccion !== 'todas') {
            $query->whereHas('quiz', function ($q) use ($seccion) {
                if ($seccion === 'calidad') {
                    $q->where('titulo', 'like', '%calidad%')
                      ->orWhereHas('modulo', function ($qm) {
                          $qm->where('titulo', 'like', '%calidad%')
                             ->orWhere('norma', 'like', '%9001%');
                      });
                } elseif ($seccion === 'sst') {
                    $q->where(function ($qs) {
                        $qs->where('titulo', 'like', '%sst%')
                           ->orWhere('titulo', 'like', '%seguridad%')
                           ->orWhere('titulo', 'like', '%salud%');
                    })->orWhereHas('modulo', function ($qm) {
                        $qm->where('titulo', 'like', '%sst%')
                           ->orWhere('titulo', 'like', '%seguridad%')
                           ->orWhere('titulo', 'like', '%salud%')
                           ->orWhere('norma', 'like', '%45001%');
                    });
                } elseif ($seccion === 'ambiental') {
                    $q->where(function ($qs) {
                        $qs->where('titulo', 'like', '%ambiental%')
                           ->orWhere('titulo', 'like', '%ambiente%');
                    })->orWhereHas('modulo', function ($qm) {
                        $qm->where('titulo', 'like', '%ambiental%')
                           ->orWhere('titulo', 'like', '%ambiente%')
                           ->orWhere('norma', 'like', '%14001%');
                    });
                }
            });
        }

        // Filtro de búsqueda
        if (!empty($buscar)) {
            $query->where(function ($q) use ($buscar) {
                $q->whereHas('user', function ($qu) use ($buscar) {
                    $qu->where('name', 'like', "%{$buscar}%")
                       ->orWhere('email', 'like', "%{$buscar}%")
                       ->orWhere('nickname', 'like', "%{$buscar}%");

                    if (Schema::hasTable('people')) {
                        $qu->orWhereHas('person', function ($qp) use ($buscar) {
                            $qp->where('first_name', 'like', "%{$buscar}%")
                               ->orWhere('first_last_name', 'like', "%{$buscar}%")
                               ->orWhere('second_last_name', 'like', "%{$buscar}%")
                               ->orWhere('document_number', 'like', "%{$buscar}%");
                        });
                    }
                })->orWhereHas('quiz', function ($qq) use ($buscar) {
                    $qq->where('titulo', 'like', "%{$buscar}%");
                });
            });
        }

        $resultados = $query->orderByDesc('id_resultado')->get();

        // Estructurar items para la vista
        $infraccionesList = $resultados->map(function ($res) {
            $aprendiz = $res->user;

            // Nombre
            $nombreAprendiz = trim($aprendiz->name ?? 'Aprendiz');
            if (isset($aprendiz->person) && (!empty($aprendiz->person->first_name) || !empty($aprendiz->person->first_last_name))) {
                $nombreCompleto = trim("{$aprendiz->person->first_name} {$aprendiz->person->first_last_name} " . ($aprendiz->person->second_last_name ?? ''));
                if (!empty($nombreCompleto)) {
                    $nombreAprendiz = $nombreCompleto;
                }
            }

            // Iniciales
            $partes = preg_split('/\s+/', $nombreAprendiz);
            $iniciales = count($partes) >= 2
                ? mb_strtoupper(mb_substr($partes[0], 0, 1) . mb_substr($partes[1], 0, 1))
                : mb_strtoupper(mb_substr($nombreAprendiz, 0, 2));

            // Documento
            $documento = 'N/A';
            if (isset($aprendiz->person) && !empty($aprendiz->person->document_number)) {
                $docNum = $aprendiz->person->document_number;
                $documento = is_numeric($docNum) ? number_format((float) $docNum, 0, '', '.') : (string) $docNum;
            } elseif (!empty($aprendiz->documento)) {
                $documento = (string) $aprendiz->documento;
            }

            // Ficha
            $ficha = 'Sin asignar';
            if (!empty($aprendiz->person_id) && Schema::hasTable('apprentices')) {
                $fichaVal = DB::table('apprentices')->where('person_id', $aprendiz->person_id)->value('course_id');
                if ($fichaVal) {
                    $ficha = (string) $fichaVal;
                }
            } elseif (!empty($aprendiz->ficha)) {
                $ficha = (string) $aprendiz->ficha;
            }

            // Foto / Avatar guardado del usuario
            $avatarUrl = null;
            if (isset($aprendiz->person) && !empty($aprendiz->person->avatar)) {
                $avatarVal = $aprendiz->person->avatar;
                $avatarUrl = (str_starts_with($avatarVal, 'http') || str_starts_with($avatarVal, '/'))
                    ? $avatarVal
                    : asset('storage/' . $avatarVal);
            } elseif (!empty($aprendiz->avatar)) {
                $avatarVal = $aprendiz->avatar;
                $avatarUrl = (str_starts_with($avatarVal, 'http') || str_starts_with($avatarVal, '/'))
                    ? $avatarVal
                    : asset('storage/' . $avatarVal);
            }

            // Quiz y Módulo
            $quizTitulo = $res->quiz->titulo ?? 'Evaluación de Seguridad';
            $moduloNombre = $res->quiz->modulo->titulo ?? 'Módulo SIG';
            $norma = $res->quiz->modulo->norma ?? 'SIG';

            // Fechas
            $fechaRaw = $res->fecha_presentacion ?? $res->created_at ?? now();
            $fecha = Carbon::parse($fechaRaw)->translatedFormat('d M Y - h:i A');

            // Infracciones
            $infraccionesDetalle = $res->infracciones->map(function ($inf) {
                return [
                    'id'          => $inf->id_infraccion,
                    'tipo'        => $inf->tipo_infraccion,
                    'evento'      => $inf->tipo_evento ?? $inf->tipo_infraccion,
                    'detalle'     => $inf->detalle ?? 'Incidencia detectada por el monitor de examen',
                    'hora'        => $inf->marca_tiempo ? Carbon::parse($inf->marca_tiempo)->format('h:i:s A') : 'N/A',
                    'ip'          => $inf->ip_address ?? '127.0.0.1',
                    'navegador'   => $inf->navegador_detalles ?? 'Navegador Web',
                ];
            })->values()->toArray();

            return (object) [
                'id_resultado'           => $res->id_resultado,
                'user_id'                => $res->user_id,
                'aprendiz'               => $nombreAprendiz,
                'avatar_url'             => $avatarUrl,
                'correo'                 => $aprendiz->email ?? 'N/A',
                'iniciales'              => $iniciales,
                'documento'              => $documento,
                'ficha'                  => $ficha,
                'quiz'                   => $quizTitulo,
                'modulo'                 => $moduloNombre,
                'norma'                  => $norma,
                'intento'                => $res->intento ?? 1,
                'puntaje'                => (int) round($res->puntaje ?? 0),
                'fue_anulado'            => (bool) $res->fue_anulado,
                'motivo_anulacion'       => $res->motivo_anulacion ?? 'Anulado por acumulación de faltas anti-trampa',
                'infracciones_count'     => max($res->infracciones_detectadas ?? 0, count($infraccionesDetalle)),
                'fecha'                  => $fecha,
                'infracciones_detalle'   => $infraccionesDetalle,
            ];
        });

        // Métricas para tarjetas superiores
        $totalAnulados = Resultado::where('fue_anulado', true)->count();
        $totalInfracciones = InfraccionQuiz::count();
        $infraccionesPantalla = InfraccionQuiz::where('tipo_infraccion', 'like', '%pantalla%')
            ->orWhere('tipo_evento', 'like', '%pantalla%')
            ->count();
        $infraccionesPestana = InfraccionQuiz::where(function ($q) {
            $q->where('tipo_infraccion', 'like', '%pestana%')
              ->orWhere('tipo_infraccion', 'like', '%foco%')
              ->orWhere('tipo_evento', 'like', '%pestana%')
              ->orWhere('tipo_evento', 'like', '%foco%')
              ->orWhere('tipo_evento', 'like', '%ventana%');
        })->count();

        $metrics = [
            'anulados'            => $totalAnulados,
            'total_infracciones'  => $totalInfracciones,
            'pantalla'            => $infraccionesPantalla,
            'pestana'             => $infraccionesPestana,
        ];

        return view('sisig::editor.infracciones.index', compact(
            'infraccionesList',
            'metrics',
            'seccion',
            'buscar'
        ));
    }
}
