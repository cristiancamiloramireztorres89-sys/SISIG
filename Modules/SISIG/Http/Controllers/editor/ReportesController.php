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
        $seccion = strtolower($request->input('seccion', 'calidad'));
        if (!in_array($seccion, ['calidad', 'sst', 'ambiental'])) {
            $seccion = 'calidad';
        }

        $buscar = trim($request->input('buscar', ''));

        // 1. Identificar módulos y quices correspondientes a la sección activa
        $modulosQuery = DB::table('sisig_modulos');
        if ($seccion === 'calidad') {
            $modulosQuery->where(function ($q) {
                $q->where('titulo', 'like', '%calidad%')
                  ->orWhere('norma', 'like', '%9001%');
            });
        } elseif ($seccion === 'sst') {
            $modulosQuery->where(function ($q) {
                $q->where('titulo', 'like', '%sst%')
                  ->orWhere('titulo', 'like', '%seguridad%')
                  ->orWhere('titulo', 'like', '%salud%')
                  ->orWhere('norma', 'like', '%45001%');
            });
        } elseif ($seccion === 'ambiental') {
            $modulosQuery->where(function ($q) {
                $q->where('titulo', 'like', '%ambiental%')
                  ->orWhere('titulo', 'like', '%ambiente%')
                  ->orWhere('norma', 'like', '%14001%');
            });
        }
        $modulosIds = $modulosQuery->pluck('id')->toArray();

        $quizIds = DB::table('sisig_quices')
            ->where(function ($q) use ($modulosIds, $seccion) {
                if (!empty($modulosIds)) {
                    $q->whereIn('modulo_id', $modulosIds);
                }
                $q->orWhere('titulo', 'like', "%{$seccion}%");
            })
            ->pluck('id_quiz')
            ->toArray();

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
                $documento = number_format($aprendiz->person->document_number, 0, '', '.');
            } elseif (!empty($aprendiz->documento)) {
                $documento = $aprendiz->documento;
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
                    ->orderByDesc('puntaje')
                    ->first();
            }

            if ($resultado) {
                $puntaje = (int) round($resultado->puntaje ?? 0);
                $intentos = $resultado->intento ?? 1;
                $fechaRaw = $resultado->fecha_presentacion ?? $resultado->created_at ?? now();
                $fecha = Carbon::parse($fechaRaw)->translatedFormat('d M Y');

                $item = (object) [
                    'id'         => $aprendiz->id,
                    'nombre'     => $nombreLimpio,
                    'iniciales'  => $iniciales,
                    'documento'  => $documento,
                    'correo'     => $aprendiz->email ?? 'N/A',
                    'ficha'      => $ficha,
                    'puntaje'    => $puntaje,
                    'intentos'   => $intentos,
                    'fecha'      => $fecha,
                    'aprobado'   => ($puntaje >= 70 || $resultado->aprobado == 1),
                    'quiz_id'    => $resultado->id_quiz,
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
            'noAprobadosList'
        ));
    }
}