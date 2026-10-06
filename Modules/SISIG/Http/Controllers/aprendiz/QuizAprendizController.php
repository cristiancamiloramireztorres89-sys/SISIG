<?php

namespace Modules\SISIG\Http\Controllers\Aprendiz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\SISIG\Entities\Quiz;
use Modules\SISIG\Entities\Modulo;
use Modules\SISIG\Entities\Pregunta;

class QuizAprendizController extends Controller
{
    /**
     * Muestra la vista principal del Quiz Interactivo del Aprendiz.
     * Consulta estrictamente en tiempo real los quices existentes creados por el editor en la base de datos.
     * Si no existen registros en la base de datos, la lista se presenta vacía en tiempo real.
     */
    public function index()
    {
        $userId = Auth::id();

        // 1. Consulta en tiempo real sobre los quices registrados en la BD
        $quices = Quiz::with(['modulo', 'preguntas'])->get();

        $seccionesList = [];

        foreach ($quices as $quiz) {
            $modulo = $quiz->modulo;

            // Cantidad real de preguntas registradas en la base de datos
            $preguntasCount = $quiz->preguntas ? $quiz->preguntas->count() : (int) ($quiz->numero_preguntas ?? 0);
            $intentosMax = (int) ($quiz->numero_intentos ?? 3);
            $norma = ($modulo && !empty($modulo->norma)) ? $modulo->norma : 'Norma SIG';

            // 2. Consulta de resultados del aprendiz autenticado en tiempo real
            $resultados = DB::table('sisig_resultados')
                ->where('user_id', $userId)
                ->where('id_quiz', $quiz->id_quiz)
                ->get();

            $intentosRealizados = $resultados->count();
            $aprobado = $resultados->contains(function ($r) use ($quiz) {
                $minimo = (float) ($quiz->puntaje_minimo ?? 70);
                return $r->aprobado == 1 || $r->puntaje >= $minimo;
            });
            $mejorPuntaje = (float) ($resultados->max('puntaje') ?? 0);

            // 3. Estado en tiempo real del aprendiz para este quiz
            if ($aprobado) {
                $estado = 'Completado';
                $badgeBg = 'bg-[#d1fae5]';
                $badgeText = 'text-[#0d5c3a]';
            } elseif ($intentosRealizados > 0) {
                $estado = 'En progreso';
                $badgeBg = 'bg-[#fef9c3]';
                $badgeText = 'text-[#854d0e]';
            } else {
                $estado = 'Pendiente';
                $badgeBg = 'bg-[#f1f5f9]';
                $badgeText = 'text-[#475569]';
            }

            // Disponibilidad real según configuración del editor
            $disponible = (bool) $quiz->activo;
            if ($modulo && $modulo->estado === 'inactivo') {
                $disponible = false;
            }

            // Si ya no le quedan intentos y no aprobó
            if (!$aprobado && $intentosMax > 0 && $intentosRealizados >= $intentosMax) {
                $disponible = false;
            }

            // Icono temático institucional en base al título
            $tituloLower = mb_strtolower(($modulo ? $modulo->titulo : '') . ' ' . $quiz->titulo);
            $icono = 'fas fa-clipboard-check';
            if (str_contains($tituloLower, 'calidad') || str_contains($tituloLower, '9001')) {
                $icono = 'fas fa-check-circle';
            } elseif (str_contains($tituloLower, 'sst') || str_contains($tituloLower, 'seguridad') || str_contains($tituloLower, 'salud') || str_contains($tituloLower, '45001')) {
                $icono = 'fas fa-hard-hat';
            } elseif (str_contains($tituloLower, 'ambiental') || str_contains($tituloLower, 'ambiente') || str_contains($tituloLower, '14001')) {
                $icono = 'fas fa-tree';
            }

            $seccionesList[] = (object) [
                'id_quiz'          => $quiz->id_quiz,
                'titulo'           => $modulo ? $modulo->titulo : $quiz->titulo,
                'subtitulo'        => $quiz->titulo,
                'norma'            => $norma,
                'icono'            => $icono,
                'preguntas_count'  => $preguntasCount,
                'intentos_max'     => $intentosMax,
                'intentos_hechos'  => $intentosRealizados,
                'estado'           => $estado,
                'badge_bg'         => $badgeBg,
                'badge_text'       => $badgeText,
                'disponible'       => $disponible,
                'mejor_puntaje'    => $mejorPuntaje,
            ];
        }

        return view('sisig::aprendiz.quiz.index', compact('seccionesList'));
    }

    /**
     * Muestra la interfaz para resolver el quiz en tiempo real.
     */
    public function show($id)
    {
        $userId = Auth::id();

        // Buscar el quiz por su ID numérico o por coincidencia de título
        $quiz = is_numeric($id) 
            ? Quiz::with(['modulo', 'preguntas.opciones'])->find($id)
            : Quiz::with(['modulo', 'preguntas.opciones'])->where('titulo', 'like', "%{$id}%")->first();

        if (!$quiz) {
            return redirect()->route('sisig.aprendiz.quiz.index')->with('error', 'El quiz solicitado no existe en la base de datos.');
        }

        if (!$quiz->activo) {
            return redirect()->route('sisig.aprendiz.quiz.index')->with('error', 'Este quiz no está habilitado por el editor actualmente.');
        }

        $tituloSeccion = $quiz->modulo ? $quiz->modulo->titulo : $quiz->titulo;
        $preguntas = $quiz->preguntas;

        if ($preguntas->isEmpty()) {
            return redirect()->route('sisig.aprendiz.quiz.index')->with('error', 'El editor aún no ha agregado preguntas a este quiz.');
        }

        return view('sisig::aprendiz.quiz.juego', compact('quiz', 'tituloSeccion', 'preguntas'));
    }

    /**
     * Evalúa las respuestas del aprendiz en tiempo real y guarda el intento en sisig_resultados.
     */
    public function evaluar(Request $request, $id)
    {
        $userId = Auth::id();

        $quiz = is_numeric($id) 
            ? Quiz::with(['preguntas.opciones'])->find($id)
            : Quiz::with(['preguntas.opciones'])->where('titulo', 'like', "%{$id}%")->first();

        if (!$quiz) {
            return response()->json(['success' => false, 'mensaje' => 'Quiz no encontrado.'], 404);
        }

        $respuestas = $request->input('respuestas', []);
        $preguntas = $quiz->preguntas;
        $totalPreguntas = $preguntas->count();
        $aciertos = 0;

        foreach ($preguntas as $pregunta) {
            $opcionElegidaId = $respuestas[$pregunta->id_pregunta] ?? null;
            $opcionCorrecta = $pregunta->opciones->firstWhere('es_correcta', 1);

            if ($opcionCorrecta && $opcionElegidaId == $opcionCorrecta->id_opcion) {
                $aciertos++;
            }
        }

        $puntaje = $totalPreguntas > 0 ? (int) round(($aciertos / $totalPreguntas) * 100) : 0;
        $puntajeMinimo = (float) ($quiz->puntaje_minimo ?? 70);
        $aprobado = $puntaje >= $puntajeMinimo;

        // Registrar intento en tiempo real en la tabla sisig_resultados
        $intentoActual = DB::table('sisig_resultados')
            ->where('user_id', $userId)
            ->where('id_quiz', $quiz->id_quiz)
            ->count() + 1;

        DB::table('sisig_resultados')->insert([
            'user_id'            => $userId,
            'id_quiz'            => $quiz->id_quiz,
            'intento'            => $intentoActual,
            'puntaje'            => $puntaje,
            'aprobado'           => $aprobado ? 1 : 0,
            'fue_anulado'        => 0,
            'fecha_presentacion' => now(),
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        // Registrar en actividadreciente si existe la tabla
        if (Schema::hasTable('actividadreciente')) {
            DB::table('actividadreciente')->insert([
                'user_id'     => $userId,
                'tipo_evento' => 'quiz_presentado',
                'mensaje'     => "Presentó el Quiz \"{$quiz->titulo}\" obteniendo {$puntaje}/100 (" . ($aprobado ? 'Aprobado' : 'No aprobado') . ")",
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        return response()->json([
            'success'   => true,
            'puntaje'   => $puntaje,
            'aprobado'  => $aprobado,
            'aciertos'  => $aciertos,
            'total'     => $totalPreguntas,
            'mensaje'   => $aprobado ? '¡Felicitaciones! Has aprobado este quiz con éxito.' : 'Intento registrado. Te recomendamos repasar los contenidos y volver a intentarlo.',
        ]);
    }
}
