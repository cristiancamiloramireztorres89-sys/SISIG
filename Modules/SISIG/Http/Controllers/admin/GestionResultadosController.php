<?php

namespace Modules\SISIG\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\SISIG\Entities\InfraccionQuiz;
use Modules\SISIG\Entities\Modulo;
use Modules\SISIG\Entities\Quiz;
use Modules\SISIG\Entities\Resultado;

class GestionResultadosController extends Controller
{
    /**
     * Muestra el panel principal de auditoría de resultados de exámenes e intentos.
     */
    public function index(Request $request)
    {
        // 1. Métricas Globales de Rendimiento
        $totalEvaluaciones = Resultado::whereNotNull('fecha_presentacion')->count();
        $totalAprobadas = Resultado::whereNotNull('fecha_presentacion')->where('aprobado', true)->count();
        $totalReprobadas = Resultado::whereNotNull('fecha_presentacion')->where('aprobado', false)->where('fue_anulado', false)->count();
        $totalAnuladas = Resultado::where('fue_anulado', true)->count();
        $totalConInfracciones = Resultado::where('infracciones_detectadas', '>', 0)->count();
        $promedioPuntaje = round((float) (Resultado::whereNotNull('fecha_presentacion')->avg('puntaje') ?? 0), 1);

        $tasaAprobacion = $totalEvaluaciones > 0 ? round(($totalAprobadas / $totalEvaluaciones) * 100, 1) : 0;

        // 2. Filtros Dinámicos
        $moduloId = $request->input('modulo_id');
        $quizId = $request->input('quiz_id');
        $estado = $request->input('estado'); // 'aprobado', 'reprobado', 'anulado', 'infracciones'
        $buscar = trim($request->input('buscar', ''));

        $query = Resultado::with([
            'user.person',
            'quiz.modulo',
            'infracciones'
        ])
        ->whereNotNull('fecha_presentacion');

        if ($moduloId && $moduloId !== 'all') {
            $query->whereHas('quiz', function ($q) use ($moduloId) {
                $q->where('modulo_id', $moduloId);
            });
        }

        if ($quizId && $quizId !== 'all') {
            $query->where('id_quiz', $quizId);
        }

        if ($estado) {
            if ($estado === 'aprobado') {
                $query->where('aprobado', true)->where('fue_anulado', false);
            } elseif ($estado === 'reprobado') {
                $query->where('aprobado', false)->where('fue_anulado', false);
            } elseif ($estado === 'anulado') {
                $query->where('fue_anulado', true);
            } elseif ($estado === 'infracciones') {
                $query->where('infracciones_detectadas', '>', 0);
            }
        }

        if (!empty($buscar)) {
            $escaped = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $buscar);
            $query->where(function ($q) use ($escaped) {
                $q->whereHas('user', function ($qu) use ($escaped) {
                    $qu->where('nickname', 'like', "%{$escaped}%")
                       ->orWhere('email', 'like', "%{$escaped}%")
                       ->orWhereHas('person', function ($qp) use ($escaped) {
                           $qp->where('first_name', 'like', "%{$escaped}%")
                              ->orWhere('first_last_name', 'like', "%{$escaped}%")
                              ->orWhere('document_number', 'like', "%{$escaped}%");
                       });
                })
                ->orWhereHas('quiz', function ($qq) use ($escaped) {
                    $qq->where('titulo', 'like', "%{$escaped}%");
                });
            });
        }

        $resultados = $query->orderByDesc('fecha_presentacion')->get();

        // 3. Catálogo para desplegables de filtrado
        $modulos = Modulo::orderBy('titulo')->get();
        $quices = Quiz::with('modulo')->orderBy('titulo')->get();

        return view('sisig::admin.GestionResultados', compact(
            'resultados',
            'totalEvaluaciones',
            'totalAprobadas',
            'totalReprobadas',
            'totalAnuladas',
            'totalConInfracciones',
            'promedioPuntaje',
            'tasaAprobacion',
            'modulos',
            'quices',
            'moduloId',
            'quizId',
            'estado',
            'buscar'
        ));
    }

    /**
     * Retorna la auditoría completa (respuestas detalladas e infracciones) de un intento específico.
     */
    public function show($id)
    {
        $resultado = Resultado::with([
            'user.person',
            'quiz.modulo',
            'quiz.preguntas.opciones',
            'infracciones'
        ])->findOrFail($id);

        $user = $resultado->user;
        $person = $user ? $user->person : null;

        $nombreCompleto = $person 
            ? trim("{$person->first_name} {$person->first_last_name} " . ($person->second_last_name ?? '')) 
            : ($user->nickname ?? $user->email ?? 'Usuario Aprendiz');

        $documento = $person->document_number ?? 'Sin documento';
        $email = $user->email ?? ($person->misena_email ?? 'N/A');

        // Formato amigable de duración
        $segundos = (int) $resultado->tiempo_segundos_empleado;
        $minutos = floor($segundos / 60);
        $segundosRestantes = $segundos % 60;
        $duracionTexto = "{$minutos}m " . str_pad($segundosRestantes, 2, '0', STR_PAD_LEFT) . "s";

        // Bitácora de infracciones formateada
        $infraccionesLog = $resultado->infracciones->map(function ($inf) {
            return [
                'id_infraccion' => $inf->id_infraccion,
                'tipo_evento' => $inf->tipo_evento ?? $inf->tipo_infraccion ?? 'Evento Sospechoso',
                'detalle' => $inf->detalle ?? 'Sin detalle adicional',
                'hora' => $inf->marca_tiempo ? $inf->marca_tiempo->format('d/m/Y h:i:s A') : ($inf->created_at ? $inf->created_at->format('d/m/Y h:i:s A') : 'N/A'),
                'ip' => $inf->ip_address ?? 'N/A',
                'navegador' => $inf->navegador_detalles ?? 'N/A'
            ];
        });

        // Detalle de respuestas pregunta por pregunta
        $detallesRevision = $resultado->detalles_revision;
        $tieneDetallePreguntas = !empty($detallesRevision) && is_array($detallesRevision) && count($detallesRevision) > 0;

        return response()->json([
            'success' => true,
            'id_resultado' => $resultado->id_resultado,
            'puntaje' => (float) $resultado->puntaje,
            'aprobado' => (bool) $resultado->aprobado,
            'fue_anulado' => (bool) $resultado->fue_anulado,
            'motivo_anulacion' => $resultado->motivo_anulacion,
            'intento' => $resultado->intento,
            'intentos_maximos' => $resultado->quiz->numero_intentos ?? 3,
            'puntaje_minimo' => (float) ($resultado->quiz->puntaje_minimo ?? 70),
            'duracion_texto' => $duracionTexto,
            'fecha_presentacion' => $resultado->fecha_presentacion ? $resultado->fecha_presentacion->format('d/m/Y h:i A') : 'N/A',
            'infracciones_count' => $resultado->infracciones_detectadas,
            'aprendiz' => [
                'nombre' => $nombreCompleto,
                'documento' => $documento,
                'email' => $email,
                'avatar' => $person && $person->avatar ? asset('storage/' . $person->avatar) : null
            ],
            'quiz' => [
                'id_quiz' => $resultado->quiz->id_quiz,
                'titulo' => $resultado->quiz->titulo,
                'modulo' => $resultado->quiz->modulo ? $resultado->quiz->modulo->titulo : 'SIG',
                'tiempo_minutos' => $resultado->quiz->tiempo_minutos
            ],
            'tiene_detalle_preguntas' => $tieneDetallePreguntas,
            'detalles_preguntas' => $detallesRevision ?? [],
            'infracciones' => $infraccionesLog
        ]);
    }

    /**
     * Anula formalmente un intento por fraude o trampa académica.
     */
    public function anular(Request $request, $id)
    {
        $request->validate([
            'motivo_anulacion' => 'required|string|min:5|max:255'
        ]);

        $resultado = Resultado::findOrFail($id);

        $resultado->update([
            'fue_anulado' => true,
            'aprobado' => false,
            'puntaje' => 0.00,
            'motivo_anulacion' => $request->motivo_anulacion
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'La evaluación ha sido anulada con éxito y su calificación ha sido establecida en 0.00%.'
            ]);
        }

        return redirect()->back()->with('success', 'La evaluación ha sido anulada exitosamente.');
    }
}
