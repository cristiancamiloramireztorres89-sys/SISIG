<?php

namespace Modules\SISIG\Http\Controllers\Aprendiz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\SISIG\Entities\Quiz;
use Modules\SISIG\Entities\Pregunta;
use Modules\SISIG\Entities\Opcion;
use Modules\SISIG\Entities\Resultado;
use Modules\SISIG\Entities\InfraccionQuiz;

class EvaluacionesAprendizController extends Controller
{
    /**
     * Umbral máximo de advertencias de seguridad permitidas antes de anular.
     */
    protected int $maxAdvertencias = 3;

    /**
     * Margen de gracia en segundos para tolerancia de latencia de red.
     */
    protected int $margenGraciaSegundos = 45;

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
                ->whereNotNull('fecha_presentacion')
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
     * Iniciar la presentación del Quiz Interactivo con supervisión Anti-Trampa activa.
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

        // Comprobar si ya aprobó
        $yaAprobado = Resultado::where('user_id', $userId)
            ->where('id_quiz', $id_quiz)
            ->where('aprobado', true)
            ->exists();

        if ($yaAprobado) {
            return redirect()->route('sisig.aprendiz.evaluaciones.index')
                ->with('info', 'Ya has aprobado esta evaluación con anterioridad.');
        }

        // Comprobar intentos completados previamente
        $intentosRealizados = Resultado::where('user_id', $userId)
            ->where('id_quiz', $id_quiz)
            ->whereNotNull('fecha_presentacion')
            ->count();

        $intentosPermitidos = $quiz->numero_intentos ?? 3;
        $intentosRestantes = max(0, $intentosPermitidos - $intentosRealizados);

        // Verificar si existe un intento en curso activo (sin fecha_presentacion)
        $intentoEnCurso = Resultado::where('user_id', $userId)
            ->where('id_quiz', $id_quiz)
            ->whereNull('fecha_presentacion')
            ->where('fue_anulado', false)
            ->latest()
            ->first();

        $minutosValidos = (!empty($quiz->tiempo_minutos) && (int)$quiz->tiempo_minutos > 0) ? (int)$quiz->tiempo_minutos : 15;
        $duracionSegundos = $minutosValidos * 60;

        if ($intentoEnCurso) {
            $segundosTranscurridos = abs(now()->diffInSeconds($intentoEnCurso->created_at));

            if ($segundosTranscurridos > ($duracionSegundos + $this->margenGraciaSegundos)) {
                // Anular intento vencido
                $intentoEnCurso->update([
                    'fue_anulado' => true,
                    'motivo_anulacion' => 'Tiempo límite de presentación expirado.',
                    'fecha_presentacion' => now(),
                    'tiempo_segundos_empleado' => $segundosTranscurridos,
                    'puntaje' => 0.00,
                    'aprobado' => false,
                ]);
                $intentosRealizados++;
                $intentosRestantes = max(0, $intentosPermitidos - $intentosRealizados);
                $resultado = null;
            } else {
                $resultado = $intentoEnCurso;
                $segundosRestantes = max(0, $duracionSegundos - $segundosTranscurridos);
                $intentoActual = $resultado->intento;
            }
        } else {
            $resultado = null;
        }

        if (!$resultado) {
            if ($intentosRestantes <= 0) {
                return redirect()->route('sisig.aprendiz.evaluaciones.index')
                    ->with('error', 'Has agotado todos los intentos permitidos para esta evaluación.');
            }

            $intentoActual = $intentosRealizados + 1;

            // Crear intento formal para iniciar auditoría de tiempo y faltas
            $resultado = Resultado::create([
                'user_id' => $userId,
                'id_quiz' => $id_quiz,
                'intento' => $intentoActual,
                'puntaje' => 0.00,
                'aprobado' => false,
                'fue_anulado' => false,
                'tiempo_segundos_empleado' => 0,
                'infracciones_detectadas' => 0,
            ]);

            $segundosRestantes = $duracionSegundos;
        }

        $infraccionesCount = $resultado->infracciones()->count();
        $maxAdvertencias = $this->maxAdvertencias;

        return view('sisig::aprendiz.evaluaciones.presentar', compact(
            'quiz',
            'intentoActual',
            'intentosRestantes',
            'intentosPermitidos',
            'resultado',
            'segundosRestantes',
            'infraccionesCount',
            'maxAdvertencias'
        ));
    }

    /**
     * Endpoint asíncrono para registrar y auditar incidentes anti-trampa.
     */
    public function registrarIncidente(Request $request, $id_resultado)
    {
        $userId = Auth::id();
        $resultado = Resultado::where('id_resultado', $id_resultado)
            ->where('user_id', $userId)
            ->first();

        if (!$resultado) {
            return response()->json(['error' => 'Intento no encontrado'], 404);
        }

        if ($resultado->fue_anulado || $resultado->fecha_presentacion !== null) {
            return response()->json([
                'status' => 'anulado',
                'anulado' => true,
                'mensaje' => 'El examen ya ha sido finalizado o anulado.',
                'redirect' => route('sisig.aprendiz.evaluaciones.resultado', $resultado->id_resultado)
            ]);
        }

        $tipoEvento = $request->input('tipo_evento', 'evento_desconocido');
        $detalle = $request->input('detalle', 'Incidente detectado por el monitor de integridad.');
        $userAgent = substr($request->userAgent() ?? 'N/A', 0, 255);
        $ip = $request->ip();

        $tiposMapeados = [
            'cambio_pestana' => 'Cambio de pestaña o ventana minimizada',
            'desenfoque_ventana' => 'Pérdida de foco del navegador',
            'salida_pantalla_completa' => 'Salida del modo de pantalla completa',
            'intento_copiar' => 'Intento de copiar contenido',
            'intento_pegar' => 'Intento de pegar contenido',
            'clic_derecho' => 'Menú contextual (clic derecho)',
            'tecla_bloqueada' => 'Atajo de teclado no permitido',
            'captura_pantalla' => 'Intento de captura de pantalla (PrintScreen / Recortes)',
            'asistente_ia' => 'Intento de abrir IA o asistente inteligente (Brave Leo, Copilot, etc.)',
        ];

        $descripcionTipo = $tiposMapeados[$tipoEvento] ?? $tipoEvento;

        // Registrar incidente en la tabla infraccionesquiz
        InfraccionQuiz::create([
            'id_resultado' => $resultado->id_resultado,
            'user_id' => $userId,
            'id_quiz' => $resultado->id_quiz,
            'tipo_infraccion' => substr($tipoEvento, 0, 50),
            'tipo_evento' => substr($descripcionTipo, 0, 100),
            'detalle' => $detalle,
            'marca_tiempo' => now(),
            'navegador_detalles' => $userAgent,
            'ip_address' => $ip,
        ]);

        $totalFaltas = $resultado->infracciones()->count();
        $resultado->update(['infracciones_detectadas' => $totalFaltas]);

        if ($totalFaltas >= $this->maxAdvertencias) {
            $segundosTranscurridos = now()->diffInSeconds($resultado->created_at);

            $resultado->update([
                'fue_anulado' => true,
                'motivo_anulacion' => 'Anulación automática por acumular ' . $this->maxAdvertencias . ' advertencias de seguridad anti-trampa (' . $descripcionTipo . ').',
                'fecha_presentacion' => now(),
                'tiempo_segundos_empleado' => $segundosTranscurridos,
                'puntaje' => 0.00,
                'aprobado' => false,
            ]);

            return response()->json([
                'status' => 'anulado',
                'anulado' => true,
                'faltas' => $totalFaltas,
                'max_faltas' => $this->maxAdvertencias,
                'tipo_evento' => $descripcionTipo,
                'mensaje' => 'Has alcanzado el límite de ' . $this->maxAdvertencias . ' advertencias. Tu examen ha sido anulado automáticamente.',
                'redirect' => route('sisig.aprendiz.evaluaciones.resultado', $resultado->id_resultado)
            ]);
        }

        return response()->json([
            'status' => 'advertencia',
            'anulado' => false,
            'faltas' => $totalFaltas,
            'max_faltas' => $this->maxAdvertencias,
            'faltas_restantes' => max(0, $this->maxAdvertencias - $totalFaltas),
            'tipo_evento' => $descripcionTipo,
            'mensaje' => "Advertencia de seguridad #{$totalFaltas}/{$this->maxAdvertencias}: {$descripcionTipo}."
        ]);
    }

    /**
     * Procesar y calificar las respuestas del aprendiz con validación estricta en servidor.
     */
    public function calificar(Request $request, $id_quiz)
    {
        $userId = Auth::id();
        $quiz = Quiz::with(['modulo', 'preguntas.opciones'])->findOrFail($id_quiz);

        // Buscar el intento activo correspondiente
        $idResultado = $request->input('id_resultado');
        $resultado = null;

        if ($idResultado) {
            $resultado = Resultado::where('id_resultado', $idResultado)
                ->where('user_id', $userId)
                ->first();
        }

        if (!$resultado) {
            $resultado = Resultado::where('user_id', $userId)
                ->where('id_quiz', $id_quiz)
                ->whereNull('fecha_presentacion')
                ->latest()
                ->first();
        }

        // Si no hay intento activo pero ya se había calificado
        if (!$resultado) {
            $resultado = Resultado::where('user_id', $userId)
                ->where('id_quiz', $id_quiz)
                ->latest()
                ->firstOrFail();
            return redirect()->route('sisig.aprendiz.evaluaciones.resultado', $resultado->id_resultado);
        }

        $intentoNumero = $resultado->intento;
        $segundosTranscurridos = abs(now()->diffInSeconds($resultado->created_at));
        $minutosValidos = (!empty($quiz->tiempo_minutos) && (int)$quiz->tiempo_minutos > 0) ? (int)$quiz->tiempo_minutos : 15;
        $duracionSegundos = $minutosValidos * 60;
        $totalInfracciones = $resultado->infracciones()->count();

        // 1. VALIDACIÓN ESTRICTA DE TIEMPO EN SERVIDOR
        $excedioTiempo = $duracionSegundos > 0 && $segundosTranscurridos > ($duracionSegundos + $this->margenGraciaSegundos);
        
        // 2. VALIDACIÓN DE INFRACCIONES
        $excedioFaltas = $totalInfracciones >= $this->maxAdvertencias;

        $fueAnulado = $resultado->fue_anulado || $excedioTiempo || $excedioFaltas;
        $motivoAnulacion = $resultado->motivo_anulacion;

        if (!$motivoAnulacion) {
            if ($excedioFaltas) {
                $motivoAnulacion = 'Anulado por superar el límite de advertencias anti-trampa.';
            } elseif ($excedioTiempo) {
                $minutos = round($segundosTranscurridos / 60, 1);
                $motivoAnulacion = "Exceso del tiempo límite en el servidor ({$minutos} min empleados de {$quiz->tiempo_minutos} min permitidos).";
            }
        }

        // Respuestas recibidas
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

        $puntaje = $fueAnulado ? 0.00 : ($totalPreguntas > 0 ? round(($respuestasCorrectas / $totalPreguntas) * 100, 2) : 0.00);
        $puntajeMinimo = (float) $quiz->puntaje_minimo;
        $aprobado = !$fueAnulado && ($puntaje >= $puntajeMinimo);

        // Actualizar intento en sisig_resultados
        $resultado->update([
            'puntaje' => $puntaje,
            'aprobado' => $aprobado,
            'fue_anulado' => $fueAnulado,
            'motivo_anulacion' => $motivoAnulacion,
            'tiempo_segundos_empleado' => $segundosTranscurridos,
            'infracciones_detectadas' => $totalInfracciones,
            'fecha_presentacion' => now(),
        ]);

        $intentosPermitidos = $quiz->numero_intentos ?? 3;
        $intentosRestantes = max(0, $intentosPermitidos - $intentoNumero);
        $puedeReintentar = !$aprobado && ($intentosRestantes > 0);
        $infracciones = $resultado->infracciones()->orderBy('marca_tiempo', 'asc')->get();

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
            'detallesRevision',
            'resultado',
            'infracciones'
        ));
    }

    /**
     * Ver resultados de una evaluación finalizada.
     */
    public function verResultado($id_resultado)
    {
        $userId = Auth::id();
        $resultado = Resultado::with('quiz.modulo')->where('id_resultado', $id_resultado)
            ->where('user_id', $userId)
            ->firstOrFail();

        $quiz = $resultado->quiz;
        $intentosPermitidos = $quiz->numero_intentos ?? 3;
        $intentosRestantes = max(0, $intentosPermitidos - $resultado->intento);
        $puedeReintentar = !$resultado->aprobado && ($intentosRestantes > 0);

        $puntaje = (float) $resultado->puntaje;
        $puntajeMinimo = (float) $quiz->puntaje_minimo;
        $aprobado = $resultado->aprobado;
        $intentoNumero = $resultado->intento;
        $totalPreguntas = $quiz->preguntas()->count();
        $respuestasCorrectas = round(($puntaje / 100) * $totalPreguntas);
        $detallesRevision = [];
        $infracciones = $resultado->infracciones()->orderBy('marca_tiempo', 'asc')->get();

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
            'detallesRevision',
            'resultado',
            'infracciones'
        ));
    }
}
