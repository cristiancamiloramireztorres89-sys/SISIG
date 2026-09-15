<?php

namespace Modules\SISIG\Http\Controllers\Aprendiz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class DashboardControllerAprendiz extends Controller
{
    /**
     * Muestra el panel principal del Aprendiz en SISIG.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $userId = $user->id;

        // Consultar datos de ficha/formación en la tabla apprentices si existe
        $aprendizData = null;
        if ($user->person_id && Schema::hasTable('apprentices')) {
            $aprendizData = DB::table('apprentices')
                ->where('person_id', $user->person_id)
                ->first();
        }

        // Consultar las 3 secciones temáticas del SIG (SST, Calidad, Ambiental)
        $seccionesRaw = DB::table('sisig_secciones')
            ->orderBy('orden')
            ->get();

        $secciones = [];
        $totalProgresoPorcentaje = 0;
        $seccionesCompletadas = 0;

        foreach ($seccionesRaw as $sec) {
            // Progreso del aprendiz en esta sección
            $progreso = DB::table('progreso_aprendiz_seccion')
                ->where('user_id', $userId)
                ->where('id_seccion', $sec->id_seccion)
                ->first();

            $porcentajeVisto = $progreso ? (float) $progreso->porcentaje_visto : 0;
            $estadoSeccion = $progreso ? $progreso->estado : 'No iniciado';

            if ($estadoSeccion === 'Completado' || $porcentajeVisto >= 100) {
                $seccionesCompletadas++;
            }
            $totalProgresoPorcentaje += $porcentajeVisto;

            // Quiz asociado a la sección
            $quiz = DB::table('sisig_quices')
                ->where('id_seccion', $sec->id_seccion)
                ->where('activo', 1)
                ->first();

            $quizData = null;
            if ($quiz) {
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

                $quizData = (object) [
                    'id_quiz' => $quiz->id_quiz,
                    'titulo' => $quiz->titulo,
                    'puntaje_minimo' => (float) $quiz->puntaje_minimo,
                    'numero_preguntas' => $quiz->numero_preguntas,
                    'intentos_permitidos' => $intentosPermitidos,
                    'intentos_realizados' => $intentosRealizados,
                    'intentos_restantes' => $intentosRestantes,
                    'aprobado' => $aprobado,
                    'mejor_puntaje' => (float) $mejorPuntaje,
                    'ultimo_intento' => $ultimoIntento,
                    'puede_presentar' => !$aprobado && ($intentosRestantes > 0),
                ];
            }

            // Metadatos decorativos por eje (Iconos y colores temáticos)
            $meta = $this->getSeccionMeta($sec->nombre, $sec->orden);

            $secciones[] = (object) [
                'id_seccion' => $sec->id_seccion,
                'nombre' => $sec->nombre,
                'descripcion' => $sec->descripcion,
                'orden' => $sec->orden,
                'porcentaje_visto' => $porcentajeVisto,
                'estado' => $estadoSeccion,
                'quiz' => $quizData,
                'icono' => $meta['icono'],
                'color' => $meta['color'],
                'gradiente' => $meta['gradiente'],
                'badge' => $meta['badge'],
            ];
        }

        $totalSecciones = count($secciones);
        $progresoGeneral = $totalSecciones > 0 
            ? round($totalProgresoPorcentaje / $totalSecciones) 
            : 0;

        // Resumen de evaluaciones del aprendiz
        $todosResultados = DB::table('sisig_resultados')
            ->where('user_id', $userId)
            ->get();

        $totalEvaluacionesPresentadas = $todosResultados->count();
        $totalQuicesDisponibles = DB::table('sisig_quices')->where('activo', 1)->count();

        // Contar quices activos únicos que el aprendiz ha aprobado (soporta cualquier cantidad)
        $quicesAprobadosCount = DB::table('sisig_resultados')
            ->join('sisig_quices', 'sisig_resultados.id_quiz', '=', 'sisig_quices.id_quiz')
            ->where('sisig_resultados.user_id', $userId)
            ->where('sisig_quices.activo', 1)
            ->where('sisig_resultados.aprobado', 1)
            ->distinct()
            ->count('sisig_resultados.id_quiz');

        $promedioCalificacion = $totalEvaluacionesPresentadas > 0
            ? round((float) $todosResultados->avg('puntaje'), 1)
            : 0;

        // Estado global de inducción (dinámico según lo existente en base de datos)
        $induccionCompleta = ($totalSecciones > 0 
            && $seccionesCompletadas >= $totalSecciones 
            && ($totalQuicesDisponibles === 0 || $quicesAprobadosCount >= $totalQuicesDisponibles));

        // Historial reciente de evaluaciones presentadas por el aprendiz
        $historialEvaluaciones = DB::table('sisig_resultados')
            ->join('sisig_quices', 'sisig_resultados.id_quiz', '=', 'sisig_quices.id_quiz')
            ->leftJoin('sisig_secciones', 'sisig_quices.id_seccion', '=', 'sisig_secciones.id_seccion')
            ->where('sisig_resultados.user_id', $userId)
            ->select(
                'sisig_resultados.*',
                'sisig_quices.titulo as quiz_titulo',
                'sisig_quices.puntaje_minimo',
                'sisig_secciones.nombre as seccion_nombre'
            )
            ->orderByDesc('sisig_resultados.created_at')
            ->limit(6)
            ->get();

        return view('sisig::aprendiz.DashboardAprendiz', compact(
            'user',
            'aprendizData',
            'secciones',
            'totalSecciones',
            'seccionesCompletadas',
            'progresoGeneral',
            'quicesAprobadosCount',
            'totalQuicesDisponibles',
            'promedioCalificacion',
            'totalEvaluacionesPresentadas',
            'induccionCompleta',
            'historialEvaluaciones'
        ));
    }

    /**
     * Devuelve configuración visual por eje temático del SIG.
     */
    private function getSeccionMeta(string $nombre, int $orden): array
    {
        $nombreLower = mb_strtolower($nombre);

        if (str_contains($nombreLower, 'seguridad') || str_contains($nombreLower, 'sst') || str_contains($nombreLower, 'salud')) {
            return [
                'icono' => 'fas fa-hard-hat',
                'color' => 'text-emerald-500',
                'gradiente' => 'from-emerald-500 to-teal-700',
                'badge' => 'SST &bull; Seguridad en el Trabajo',
            ];
        }

        if (str_contains($nombreLower, 'calidad')) {
            return [
                'icono' => 'fas fa-award',
                'color' => 'text-sky-500',
                'gradiente' => 'from-sky-500 to-blue-700',
                'badge' => 'Gestión de la Calidad',
            ];
        }

        if (str_contains($nombreLower, 'ambient') || str_contains($nombreLower, 'medio')) {
            return [
                'icono' => 'fas fa-leaf',
                'color' => 'text-amber-500',
                'gradiente' => 'from-amber-500 to-emerald-700',
                'badge' => 'Gestión Ambiental Sostenible',
            ];
        }

        return [
            'icono' => 'fas fa-book-open',
            'color' => 'text-teal-500',
            'gradiente' => 'from-teal-500 to-emerald-700',
            'badge' => 'Eje Formativo SIG #' . $orden,
        ];
    }
}
