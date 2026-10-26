<?php

namespace Modules\SISIG\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SISIG\Entities\Quiz;
use Modules\SISIG\Entities\Pregunta;
use Modules\SISIG\Entities\Opcion;

class GestionPreguntasController extends Controller
{
    /**
     * Prefijo de rutas para redirecciones del panel de administración.
     */
    protected string $routePrefix = 'sisig.admin';

    /**
     * Busca y obtiene el examen solicitado con sus relaciones o lanza error 404.
     */
    protected function findQuizOrFail($quiz_id)
    {
        return Quiz::with(['modulo', 'preguntas.opciones'])->findOrFail($quiz_id);
    }

    /**
     * Muestra el banco de preguntas asociadas a un examen específico.
     */
    public function index($quiz_id)
    {
        // Consultar el examen junto con su módulo y preguntas
        $quiz = $this->findQuizOrFail($quiz_id);
        $routePrefix = $this->routePrefix;
        
        return view('sisig::admin.GestionPreguntas', compact('quiz', 'routePrefix'));
    }

    /**
     * Registra una nueva pregunta y sus opciones de respuesta en el examen.
     */
    public function store(Request $request, $quiz_id)
    {
        // Obtener el examen contenedor
        $quiz = $this->findQuizOrFail($quiz_id);

        // Validación de datos de la pregunta
        $request->validate([
            'tipo'               => 'required|string|in:opcion_multiple,seleccion_multiple,verdadero_falso,espacios_blanco,abierta,tabla_relleno',
            'pregunta'           => 'required|string',
            'puntaje'            => 'required|numeric|min:0',
            'retroalimentacion'  => 'nullable|string',
            'configuracion_json' => 'nullable|array'
        ]);

        // Crear la pregunta vinculada al examen
        $pregunta = $quiz->preguntas()->create([
            'pregunta'           => $request->pregunta,
            'tipo'               => $request->tipo,
            'puntaje'            => $request->puntaje,
            'retroalimentacion'  => $request->retroalimentacion,
            'configuracion_json' => $request->configuracion_json
        ]);

        // Registrar opciones de respuesta si fueron suministradas
        if ($request->has('opciones') && is_array($request->opciones)) {
            foreach ($request->opciones as $opcionData) {
                if (!empty($opcionData['texto'])) {
                    $pregunta->opciones()->create([
                        'opcion'      => $opcionData['texto'],
                        'es_correcta' => isset($opcionData['es_correcta']) && $opcionData['es_correcta'] == 1
                    ]);
                }
            }
        }

        // Actualizar el contador total de preguntas en el examen
        $quiz->numero_preguntas = $quiz->preguntas()->count();
        $quiz->save();

        return redirect()->route($this->routePrefix . '.examenes.preguntas.index', $quiz_id)->with('success', 'Pregunta agregada exitosamente.');
    }

    /**
     * Actualiza el enunciado, puntaje y opciones de respuesta de una pregunta.
     */
    public function update(Request $request, $quiz_id, $pregunta_id)
    {
        // Verificar existencia del examen y de la pregunta
        $quiz = $this->findQuizOrFail($quiz_id);
        $pregunta = Pregunta::where('id_quiz', $quiz_id)->where('id_pregunta', $pregunta_id)->firstOrFail();

        // Validación de campos editables
        $request->validate([
            'pregunta'           => 'required|string',
            'puntaje'            => 'required|numeric|min:0',
            'retroalimentacion'  => 'nullable|string',
            'configuracion_json' => 'nullable|array'
        ]);

        // Actualizar datos básicos de la pregunta
        $pregunta->update([
            'pregunta'           => $request->pregunta,
            'puntaje'            => $request->puntaje,
            'retroalimentacion'  => $request->retroalimentacion,
            'configuracion_json' => $request->configuracion_json
        ]);

        // Sincronizar opciones de respuesta (reemplazo seguro de opciones existentes)
        if ($request->has('opciones') && is_array($request->opciones)) {
            $pregunta->opciones()->delete();

            foreach ($request->opciones as $opcionData) {
                if (!empty($opcionData['texto'])) {
                    $pregunta->opciones()->create([
                        'opcion'      => $opcionData['texto'],
                        'es_correcta' => isset($opcionData['es_correcta']) && $opcionData['es_correcta'] == 1
                    ]);
                }
            }
        }

        return redirect()->route($this->routePrefix . '.examenes.preguntas.index', $quiz_id)->with('success', 'Pregunta actualizada exitosamente.');
    }

    /**
     * Elimina una pregunta y recalcula el total de preguntas del examen.
     */
    public function destroy($quiz_id, $pregunta_id)
    {
        // Obtener examen y la pregunta correspondiente
        $quiz = $this->findQuizOrFail($quiz_id);
        $pregunta = Pregunta::where('id_quiz', $quiz_id)->where('id_pregunta', $pregunta_id)->firstOrFail();
        
        // Eliminar la pregunta (sus opciones se eliminan en cascada según el modelo)
        $pregunta->delete();

        // Recalcular y sincronizar el total de preguntas activas en el examen
        $quiz->numero_preguntas = $quiz->preguntas()->count();
        $quiz->save();

        return redirect()->route($this->routePrefix . '.examenes.preguntas.index', $quiz_id)->with('success', 'Pregunta eliminada.');
    }
}
