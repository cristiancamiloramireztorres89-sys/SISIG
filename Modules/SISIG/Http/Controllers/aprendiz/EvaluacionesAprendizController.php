<?php

namespace Modules\SISIG\Http\Controllers\Aprendiz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\SISIG\Entities\Quiz;
use Modules\SISIG\Entities\Pregunta;
use Modules\SISIG\Entities\Opcion;

class EvaluacionesAprendizController extends Controller
{
    /**
     * Listado de evaluaciones disponibles para el aprendiz.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Consultar quices activos con sus módulos y preguntas
        $quices = Quiz::where('activo', 1)->with(['modulo', 'preguntas'])->get();

        $evaluaciones = $quices->map(function ($quiz) use ($userId) {
            $intentos = DB::table('sisig_resultados')
                ->where('user_id', $userId)
                ->where('id_quiz', $quiz->id_quiz)
                ->orderByDesc('created_at')
                ->get();

            $intentosRealizados = $intentos->count();
            $aprobado = $intentos->contains('aprobado', 1);
            $mejorPuntaje = $intentos->max('puntaje') ?? 0;
            $ultimoIntento = $intentos->first();

            $intentosPermitidos = $quiz->numero_intentos ?? 3;
            $intentosRestantes = max(0, $intentosPermitidos - $intentosRealizados);
            $puedePresentar = !$aprobado && ($intentosRestantes > 0) && ($quiz->preguntas->count() > 0);

            return (object) [
                'id_quiz' => $quiz->id_quiz,
                'titulo' => $quiz->titulo,
                'modulo' => $quiz->modulo,
                'puntaje_minimo' => (float) $quiz->puntaje_minimo,
                'numero_preguntas' => $quiz->preguntas->count() ?: ($quiz->numero_preguntas ?? 0),
                'tiempo_minutos' => $quiz->tiempo_minutos ?? 15,
                'intentos_permitidos' => $intentosPermitidos,
                'intentos_realizados' => $intentosRealizados,
                'intentos_restantes' => $intentosRestantes,
                'aprobado' => $aprobado,
                'mejor_puntaje' => (float) $mejorPuntaje,
                'ultimo_intento' => $ultimoIntento,
                'puede_presentar' => $puedePresentar,
            ];
        });

        // Búsqueda opcional
        if ($request->filled('buscar')) {
            $buscar = strtolower($request->buscar);
            $evaluaciones = $evaluaciones->filter(function ($ev) use ($buscar) {
                return str_contains(strtolower($ev->titulo), $buscar)
                    || ($ev->modulo && str_contains(strtolower($ev->modulo->titulo), $buscar));
            });
        }

        $totalEvaluaciones = $evaluaciones->count();
        $totalAprobadas = $evaluaciones->where('aprobado', true)->count();
        $totalPendientes = $evaluaciones->where('puede_presentar', true)->count();
        $totalAgotadas = $evaluaciones->filter(fn($ev) => !$ev->aprobado && $ev->intentos_restantes <= 0)->count();

        return view('sisig::aprendiz.evaluaciones.evaluaciones', compact(
            'evaluaciones',
            'totalEvaluaciones',
            'totalAprobadas',
            'totalPendientes',
            'totalAgotadas'
        ));
    }

    /**
     * Iniciar la presentación del Quiz Interactivo.
     * Criterio 1: Comprobación de intentos disponibles al iniciar.
     */
    public function iniciar($id_quiz)
    {
        $userId = Auth::id();
        $quiz = Quiz::with(['modulo', 'preguntas.opciones'])->findOrFail($id_quiz);

        // Validar que el quiz esté activo
        if (!$quiz->activo) {
            return redirect()->route('sisig.aprendiz.evaluaciones.index')
                ->with('error', 'Esta evaluación no se encuentra activa en este momento.');
        }

        // Validar que tenga preguntas
        if ($quiz->preguntas->isEmpty()) {
            return redirect()->route('sisig.aprendiz.evaluaciones.index')
                ->with('warning', 'Esta evaluación aún no tiene preguntas configuradas por el instructor.');
        }

        // Comprobar intentos disponibles (Criterio 1)
        $intentosRealizados = DB::table('sisig_resultados')
            ->where('user_id', $userId)
            ->where('id_quiz', $id_quiz)
            ->count();

        $intentosPermitidos = $quiz->numero_intentos ?? 3;
        $intentosRestantes = $intentosPermitidos - $intentosRealizados;

        // Comprobar si ya aprobó
        $yaAprobado = DB::table('sisig_resultados')
            ->where('user_id', $userId)
            ->where('id_quiz', $id_quiz)
            ->where('aprobado', 1)
            ->exists();

        if ($yaAprobado) {
            return redirect()->route('sisig.aprendiz.evaluaciones.index')
                ->with('info', 'Ya has aprobado esta evaluación con anterioridad.');
        }

        if ($intentosRestantes <= 0) {
            return redirect()->route('sisig.aprendiz.evaluaciones.index')
                ->with('error', 'Has agotado todos los intentos permitidos para esta evaluación.');
        }

        $intentoActual = $intentosRealizados + 1;

        return view('sisig::aprendiz.evaluaciones.presentar', compact(
            'quiz',
            'intentoActual',
            'intentosRestantes',
            'intentosPermitidos'
        ));
    }

    /**
     * Procesar y calificar las respuestas del aprendiz.
     * Cumple con:
     * - Criterio 4: Envío y calificación automática (manual o por tiempo expirado)
     * - Criterio 5: Visualización de puntaje y distinción de aprobado/reprobado
     * - Criterio 6: Retroalimentación pedagógica inmediata (Feedback)
     */
    public function calificar(Request $request, $id_quiz)
    {
        $userId = Auth::id();
        $quiz = Quiz::with(['modulo', 'preguntas.opciones'])->findOrFail($id_quiz);

        $intentosRealizados = DB::table('sisig_resultados')
            ->where('user_id', $userId)
            ->where('id_quiz', $id_quiz)
            ->count();

        $intentoNumero = $intentosRealizados + 1;

        // Respuestas recibidas: array asociativo [id_pregunta => id_opcion]
        $respuestasUsuario = $request->input('respuestas', []);

        $totalPreguntas = $quiz->preguntas->count();
        $respuestasCorrectas = 0;
        $detallesRevision = [];

        foreach ($quiz->preguntas as $pregunta) {
            $opcionSeleccionadaId = $respuestasUsuario[$pregunta->id_pregunta] ?? null;
            $opcionCorrecta = $pregunta->opciones->firstWhere('es_correcta', 1);
            $opcionSeleccionada = $pregunta->opciones->firstWhere('id_opcion', $opcionSeleccionadaId);

            $esCorrecta = $opcionSeleccionada && $opcionSeleccionada->es_correcta == 1;
            if ($esCorrecta) {
                $respuestasCorrectas++;
            }

            $detallesRevision[] = (object) [
                'pregunta' => $pregunta->pregunta,
                'opciones' => $pregunta->opciones,
                'opcion_seleccionada' => $opcionSeleccionada,
                'opcion_correcta' => $opcionCorrecta,
                'es_correcta' => $esCorrecta,
                'retroalimentacion' => $pregunta->retroalimentacion ?? 'Revisa nuevamente la guía didáctica del módulo para reforzar este concepto.'
            ];
        }

        // Cálculo de calificación en escala de 0 a 100
        $puntaje = $totalPreguntas > 0 ? round(($respuestasCorrectas / $totalPreguntas) * 100, 2) : 0;
        $puntajeMinimo = (float) $quiz->puntaje_minimo;
        $aprobado = $puntaje >= $puntajeMinimo;

        // Registrar resultado del intento en sisig_resultados
        DB::table('sisig_resultados')->insert([
            'user_id' => $userId,
            'id_quiz' => $id_quiz,
            'intento' => $intentoNumero,
            'puntaje' => $puntaje,
            'aprobado' => $aprobado ? 1 : 0,
            'fue_anulado' => 0,
            'motivo_anulacion' => null,
            'fecha_presentacion' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $intentosPermitidos = $quiz->numero_intentos ?? 3;
        $intentosRestantes = max(0, $intentosPermitidos - $intentoNumero);
        $puedeReintentar = !$aprobado && ($intentosRestantes > 0);

        return view('sisig::aprendiz.evaluaciones.resultado', compact(
            'quiz',
            'puntaje',
            'puntajeMinimo',
            'aprobado',
            'respuestasCorrectas',
            'totalPreguntas',
            'intentoNumero',
            'intentosPermitidos',
            'intentosRestantes',
            'puedeReintentar',
            'detallesRevision'
        ));
    }
}
