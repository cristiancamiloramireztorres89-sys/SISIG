<?php

namespace Modules\SISIG\Http\Controllers\Aprendiz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\SISIG\Entities\Quiz;
use Modules\SISIG\Entities\Resultado;
use Modules\SISIG\Entities\InfraccionQuiz;
use Modules\SISIG\Entities\Pregunta;
use Modules\SISIG\Entities\Opcion;

class QuizExamenController extends Controller
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
     * Inicia un intento de quiz o retoma un intento activo no finalizado.
     */
    public function iniciar($id_quiz)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $quiz = Quiz::with('preguntas.opciones')->where('activo', 1)->findOrFail($id_quiz);

        if ($quiz->preguntas->count() === 0) {
            return redirect()->route('sisig.aprendiz.dashboard')
                ->with('error', 'Esta evaluación aún no cuenta con preguntas configuradas.');
        }

        // Consultar intentos completados previamente
        $intentosCompletados = Resultado::where('user_id', $user->id)
            ->where('id_quiz', $quiz->id_quiz)
            ->whereNotNull('fecha_presentacion')
            ->count();

        $intentosPermitidos = $quiz->numero_intentos ?? 3;

        // Verificar si ya aprobó el examen
        $yaAprobado = Resultado::where('user_id', $user->id)
            ->where('id_quiz', $quiz->id_quiz)
            ->where('aprobado', true)
            ->exists();

        if ($yaAprobado) {
            return redirect()->route('sisig.aprendiz.dashboard')
                ->with('info', 'Ya has aprobado esta evaluación con anterioridad.');
        }

        // Verificar si existe un intento en curso activo (sin fecha_presentacion)
        $intentoEnCurso = Resultado::where('user_id', $user->id)
            ->where('id_quiz', $quiz->id_quiz)
            ->whereNull('fecha_presentacion')
            ->where('fue_anulado', false)
            ->latest()
            ->first();

        if ($intentoEnCurso) {
            // Verificar si el intento en curso no ha excedido el tiempo límite total
            $duracionSegundos = ($quiz->tiempo_minutos ?? 0) * 60;
            $segundosTranscurridos = now()->diffInSeconds($intentoEnCurso->created_at);

            if ($duracionSegundos > 0 && $segundosTranscurridos > ($duracionSegundos + $this->margenGraciaSegundos)) {
                // Anular intento vencido por tiempo
                $intentoEnCurso->update([
                    'fue_anulado' => true,
                    'motivo_anulacion' => 'Tiempo límite de presentación expirado.',
                    'fecha_presentacion' => now(),
                    'tiempo_segundos_empleado' => $segundosTranscurridos,
                    'puntaje' => 0.00,
                    'aprobado' => false,
                ]);
            } else {
                // Retomar el intento existente para no reiniciar tiempo ni omitir infracciones
                return redirect()->route('sisig.aprendiz.quiz.presentar', $intentoEnCurso->id_resultado);
            }
        }

        // Validar límite de intentos
        if ($intentosCompletados >= $intentosPermitidos) {
            return redirect()->route('sisig.aprendiz.dashboard')
                ->with('error', 'Has agotado el número máximo de intentos permitidos para este examen (' . $intentosPermitidos . ').');
        }

        // Crear nuevo registro de intento
        $numeroIntento = $intentosCompletados + 1;
        $resultado = Resultado::create([
            'user_id' => $user->id,
            'id_quiz' => $quiz->id_quiz,
            'intento' => $numeroIntento,
            'puntaje' => 0.00,
            'aprobado' => false,
            'fue_anulado' => false,
            'infracciones_detectadas' => 0,
            'tiempo_segundos_empleado' => 0,
        ]);

        return redirect()->route('sisig.aprendiz.quiz.presentar', $resultado->id_resultado);
    }

    /**
     * Muestra la interfaz del examen con el sistema de monitoreo anti-trampa activo.
     */
    public function presentar($id_resultado)
    {
        $user = Auth::user();
        $resultado = Resultado::with(['quiz.modulo', 'infracciones'])
            ->where('user_id', $user->id)
            ->findOrFail($id_resultado);

        // Si ya fue finalizado o anulado, redirigir a la vista de resultados
        if ($resultado->fecha_presentacion !== null || $resultado->fue_anulado) {
            return redirect()->route('sisig.aprendiz.quiz.resultado', $resultado->id_resultado);
        }

        $quiz = $resultado->quiz;

        // Cálculo estricto de tiempo transcurrido y restante en el servidor
        $duracionSegundos = ($quiz->tiempo_minutos ?? 0) * 60;
        $segundosTranscurridos = now()->diffInSeconds($resultado->created_at);
        $segundosRestantes = $duracionSegundos > 0 
            ? max(0, $duracionSegundos - $segundosTranscurridos) 
            : null;

        // Si el tiempo límite ya venció en servidor al cargar la vista
        if ($duracionSegundos > 0 && $segundosTranscurridos > ($duracionSegundos + $this->margenGraciaSegundos)) {
            $resultado->update([
                'fue_anulado' => true,
                'motivo_anulacion' => 'Tiempo límite de evaluación expirado en el servidor.',
                'fecha_presentacion' => now(),
                'tiempo_segundos_empleado' => $segundosTranscurridos,
                'puntaje' => 0.00,
                'aprobado' => false,
            ]);

            return redirect()->route('sisig.aprendiz.quiz.resultado', $resultado->id_resultado)
                ->with('error', 'El tiempo límite de tu examen ha expirado.');
        }

        // Cargar preguntas asociadas y sus opciones
        $preguntas = Pregunta::with('opciones')
            ->where('id_quiz', $quiz->id_quiz)
            ->get();

        $infraccionesCount = $resultado->infracciones()->count();

        // Validar si ya acumuló el máximo de faltas
        if ($infraccionesCount >= $this->maxAdvertencias) {
            $resultado->update([
                'fue_anulado' => true,
                'motivo_anulacion' => 'Anulado por superar el límite de ' . $this->maxAdvertencias . ' advertencias anti-trampa.',
                'fecha_presentacion' => now(),
                'tiempo_segundos_empleado' => $segundosTranscurridos,
                'puntaje' => 0.00,
                'aprobado' => false,
            ]);

            return redirect()->route('sisig.aprendiz.quiz.resultado', $resultado->id_resultado)
                ->with('error', 'El examen ha sido anulado por acumular ' . $this->maxAdvertencias . ' advertencias de seguridad.');
        }

        $maxAdvertencias = $this->maxAdvertencias;

        return view('sisig::aprendiz.quiz.presentar', compact(
            'resultado',
            'quiz',
            'preguntas',
            'segundosRestantes',
            'infraccionesCount',
            'maxAdvertencias'
        ));
    }

    /**
     * Endpoint asíncrono (AJAX/Fetch) para auditar cada incidente de seguridad detectado.
     */
    public function registrarIncidente(Request $request, $id_resultado)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        $resultado = Resultado::where('id_resultado', $id_resultado)
            ->where('user_id', $user->id)
            ->first();

        if (!$resultado) {
            return response()->json(['error' => 'Intento no encontrado'], 404);
        }

        // Si ya está anulado o finalizado
        if ($resultado->fue_anulado || $resultado->fecha_presentacion !== null) {
            return response()->json([
                'status' => 'anulado',
                'anulado' => true,
                'mensaje' => 'El examen ya se encuentra finalizado o anulado.',
                'redirect' => route('sisig.aprendiz.quiz.resultado', $resultado->id_resultado)
            ]);
        }

        $tipoEvento = $request->input('tipo_evento', 'evento_desconocido');
        $detalle = $request->input('detalle', 'Incidente detectado por el monitor de integridad.');
        $userAgent = substr($request->userAgent() ?? 'N/A', 0, 255);
        $ip = $request->ip();

        // Mapear tipos legibles de infracción
        $tiposMapeados = [
            'cambio_pestana' => 'Cambio de pestaña o ventana oculta',
            'desenfoque_ventana' => 'Pérdida de foco del navegador',
            'salida_pantalla_completa' => 'Salida del modo pantalla completa',
            'intento_copiar' => 'Intento de copiar contenido',
            'intento_pegar' => 'Intento de pegar contenido',
            'clic_derecho' => 'Apertura de menú contextual (clic derecho)',
            'tecla_bloqueada' => 'Atajo de teclado no permitido',
        ];

        $descripcionTipo = $tiposMapeados[$tipoEvento] ?? $tipoEvento;

        // Registrar incidente en la tabla infraccionesquiz
        InfraccionQuiz::create([
            'id_resultado' => $resultado->id_resultado,
            'user_id' => $user->id,
            'id_quiz' => $resultado->id_quiz,
            'tipo_infraccion' => substr($tipoEvento, 0, 50),
            'tipo_evento' => substr($descripcionTipo, 0, 100),
            'detalle' => $detalle,
            'marca_tiempo' => now(),
            'navegador_detalles' => $userAgent,
            'ip_address' => $ip,
        ]);

        // Recalcular total de infracciones en este intento
        $totalFaltas = $resultado->infracciones()->count();
        $resultado->update(['infracciones_detectadas' => $totalFaltas]);

        // Verificar si se superó el límite de advertencias
        if ($totalFaltas >= $this->maxAdvertencias) {
            $segundosTranscurridos = now()->diffInSeconds($resultado->created_at);

            $resultado->update([
                'fue_anulado' => true,
                'motivo_anulacion' => 'Anulación automática: Se superó el límite de ' . $this->maxAdvertencias . ' infracciones al protocolo (' . $descripcionTipo . ').',
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
                'redirect' => route('sisig.aprendiz.quiz.resultado', $resultado->id_resultado)
            ]);
        }

        return response()->json([
            'status' => 'advertencia',
            'anulado' => false,
            'faltas' => $totalFaltas,
            'max_faltas' => $this->maxAdvertencias,
            'faltas_restantes' => max(0, $this->maxAdvertencias - $totalFaltas),
            'tipo_evento' => $descripcionTipo,
            'mensaje' => "Advertencia de seguridad #{$totalFaltas}/{$this->maxAdvertencias}: {$descripcionTipo}. Si acumulas {$this->maxAdvertencias}, el examen será anulado."
        ]);
    }

    /**
     * Procesa las respuestas enviadas, realiza validación estricta de tiempo e infracciones, y califica.
     */
    public function finalizar(Request $request, $id_resultado)
    {
        $user = Auth::user();
        $resultado = Resultado::with('quiz.preguntas.opciones')
            ->where('user_id', $user->id)
            ->findOrFail($id_resultado);

        // Si ya fue anulado o presentado previamente
        if ($resultado->fecha_presentacion !== null || $resultado->fue_anulado) {
            return redirect()->route('sisig.aprendiz.quiz.resultado', $resultado->id_resultado);
        }

        $quiz = $resultado->quiz;
        $segundosTranscurridos = now()->diffInSeconds($resultado->created_at);
        $duracionSegundos = ($quiz->tiempo_minutos ?? 0) * 60;

        // 1. VALIDACIÓN ESTRICTA DE TIEMPO EN EL SERVIDOR
        // (No confiamos en el cliente; si duracion > 0 y transcurrido > limite + gracia, anular)
        if ($duracionSegundos > 0 && $segundosTranscurridos > ($duracionSegundos + $this->margenGraciaSegundos)) {
            $minutosEmpleados = round($segundosTranscurridos / 60, 1);
            $resultado->update([
                'fue_anulado' => true,
                'motivo_anulacion' => "Exceso del tiempo límite en el servidor ({$minutosEmpleados} min empleados de {$quiz->tiempo_minutos} min permitidos).",
                'fecha_presentacion' => now(),
                'tiempo_segundos_empleado' => $segundosTranscurridos,
                'puntaje' => 0.00,
                'aprobado' => false,
            ]);

            return redirect()->route('sisig.aprendiz.quiz.resultado', $resultado->id_resultado)
                ->with('error', 'El examen ha sido anulado por exceder el tiempo límite de entrega.');
        }

        // 2. VALIDACIÓN DE INFRACCIONES ACUMULADAS
        $totalInfracciones = $resultado->infracciones()->count();
        if ($totalInfracciones >= $this->maxAdvertencias) {
            $resultado->update([
                'fue_anulado' => true,
                'motivo_anulacion' => 'Anulación automática por acumular ' . $totalInfracciones . ' incidentes de seguridad.',
                'fecha_presentacion' => now(),
                'tiempo_segundos_empleado' => $segundosTranscurridos,
                'infracciones_detectadas' => $totalInfracciones,
                'puntaje' => 0.00,
                'aprobado' => false,
            ]);

            return redirect()->route('sisig.aprendiz.quiz.resultado', $resultado->id_resultado)
                ->with('error', 'Examen anulado por infringir los protocolos de seguridad anti-trampa.');
        }

        // 3. CALIFICACIÓN RIGUROSA DE RESPUESTAS
        $respuestasEnviadas = $request->input('respuestas', []);
        $puntosGanados = 0;
        $puntosTotales = 0;

        foreach ($quiz->preguntas as $pregunta) {
            $valorPregunta = (float) ($pregunta->puntaje ?? 1);
            $puntosTotales += $valorPregunta;

            $respuestaUsuario = $respuestasEnviadas[$pregunta->id_pregunta] ?? null;

            if ($respuestaUsuario !== null) {
                // Para tipos de preguntas con opciones
                if (in_array($pregunta->tipo, ['opcion_multiple', 'verdadero_falso'])) {
                    $opcionCorrecta = $pregunta->opciones->firstWhere('es_correcta', true);
                    if ($opcionCorrecta && (string) $opcionCorrecta->id_opcion === (string) $respuestaUsuario) {
                        $puntosGanados += $valorPregunta;
                    }
                } elseif ($pregunta->tipo === 'seleccion_multiple') {
                    // Si se recibieron múltiples respuestas en array
                    $opcionesCorrectasIds = $pregunta->opciones->where('es_correcta', true)->pluck('id_opcion')->map(fn($id) => (string)$id)->toArray();
                    $respuestasArray = is_array($respuestaUsuario) ? array_map('strval', $respuestaUsuario) : [(string)$respuestaUsuario];

                    sort($opcionesCorrectasIds);
                    sort($respuestasArray);

                    if ($opcionesCorrectasIds === $respuestasArray) {
                        $puntosGanados += $valorPregunta;
                    }
                }
            }
        }

        // Puntaje en base de 0 a 100%
        $puntajeFinal = $puntosTotales > 0 
            ? round(($puntosGanados / $puntosTotales) * 100, 2) 
            : 0.00;

        $puntajeMinimo = (float) ($quiz->puntaje_minimo ?? 70.00);
        $aprobado = $puntajeFinal >= $puntajeMinimo;

        // Si tuvo infracciones menores (1 o 2), podemos aplicar una penalización visual o advertencia registrada
        $resultado->update([
            'puntaje' => $puntajeFinal,
            'aprobado' => $aprobado,
            'fue_anulado' => false,
            'fecha_presentacion' => now(),
            'tiempo_segundos_empleado' => $segundosTranscurridos,
            'infracciones_detectadas' => $totalInfracciones,
        ]);

        return redirect()->route('sisig.aprendiz.quiz.resultado', $resultado->id_resultado);
    }

    /**
     * Muestra la vista de resultados detallada del intento con auditoría de integridad.
     */
    public function resultado($id_resultado)
    {
        $user = Auth::user();
        $resultado = Resultado::with(['quiz.modulo', 'infracciones'])
            ->where('user_id', $user->id)
            ->findOrFail($id_resultado);

        $quiz = $resultado->quiz;
        $infracciones = $resultado->infracciones()->orderBy('marca_tiempo', 'asc')->get();

        return view('sisig::aprendiz.quiz.resultado', compact('resultado', 'quiz', 'infracciones'));
    }
}
