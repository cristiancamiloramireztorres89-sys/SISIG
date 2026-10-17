<?php

namespace Modules\SISIG\Http\Controllers\Aprendiz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\SISIG\Entities\Quiz;
use Modules\SISIG\Entities\Modulo;

class HistorialAprendizController extends Controller
{
    /**
     * Muestra el consolidado de calificaciones e historial de intentos del aprendiz.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();

        // 1. Obtener todos los quices activos con sus módulos temáticos
        $quices = Quiz::where('activo', 1)->with('modulo')->get();

        // 2. Obtener todos los resultados registrados para el aprendiz
        $resultadosUser = DB::table('sisig_resultados')
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get();

        // 3. Consolidado por módulo/quiz (Criterios 1, 2 y 4)
        $consolidadoModulos = $quices->map(function ($quiz) use ($resultadosUser) {
            $intentosQuiz = $resultadosUser->where('id_quiz', $quiz->id_quiz);
            $totalIntentos = $intentosQuiz->count();
            $aprobado = $intentosQuiz->contains('aprobado', 1);
            $mejorPuntaje = $intentosQuiz->max('puntaje') ?? 0;
            $ultimoIntento = $intentosQuiz->first();

            $intentosPermitidos = $quiz->numero_intentos ?? 3;
            $intentosRestantes = max(0, $intentosPermitidos - $totalIntentos);

            return (object) [
                'id_quiz' => $quiz->id_quiz,
                'quiz_titulo' => $quiz->titulo,
                'modulo_titulo' => $quiz->modulo->titulo ?? 'Módulo General SIG',
                'norma' => $quiz->modulo->norma ?? 'SIG Institucional',
                'puntaje_minimo' => (float) $quiz->puntaje_minimo,
                'mejor_puntaje' => (float) $mejorPuntaje,
                'total_intentos' => $totalIntentos,
                'intentos_permitidos' => $intentosPermitidos,
                'intentos_restantes' => $intentosRestantes,
                'aprobado' => $aprobado,
                'ultimo_intento' => $ultimoIntento,
                'puede_reintentar' => !$aprobado && ($intentosRestantes > 0),
            ];
        });

        // 4. Historial cronológico detallado de todos los intentos presentados (Criterio 3)
        $historialIntentos = DB::table('sisig_resultados')
            ->join('sisig_quices', 'sisig_resultados.id_quiz', '=', 'sisig_quices.id_quiz')
            ->leftJoin('sisig_modulos', 'sisig_quices.modulo_id', '=', 'sisig_modulos.id')
            ->where('sisig_resultados.user_id', $userId)
            ->select(
                'sisig_resultados.*',
                'sisig_quices.titulo as quiz_titulo',
                'sisig_quices.puntaje_minimo',
                'sisig_quices.numero_intentos as intentos_permitidos',
                'sisig_modulos.titulo as modulo_titulo',
                'sisig_modulos.norma as modulo_norma'
            )
            ->orderByDesc('sisig_resultados.created_at')
            ->get();

        // 5. Métricas globales para las tarjetas KPI superiores
        $totalEvaluaciones = $quices->count();
        $totalAprobadas = $consolidadoModulos->where('aprobado', true)->count();
        $totalIntentosRealizados = $resultadosUser->count();
        $promedioGeneral = $totalIntentosRealizados > 0 ? round($resultadosUser->avg('puntaje'), 1) : 0;

        return view('sisig::aprendiz.historial.historial', compact(
            'consolidadoModulos',
            'historialIntentos',
            'totalEvaluaciones',
            'totalAprobadas',
            'totalIntentosRealizados',
            'promedioGeneral'
        ));
    }
}
