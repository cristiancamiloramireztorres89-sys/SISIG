<?php

namespace Modules\SISIG\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SISIG\Entities\Quiz;
use Modules\SISIG\Entities\Pregunta;

class GestionPreguntasController extends Controller
{
    /**
     * Muestra la interfaz de selección de tipos y la lista de preguntas actuales del quiz
     */
    public function index($quiz_id)
    {
        $user = auth()->user();
        $modulosIds = $user->modulosEditables->pluck('id')->toArray();

        // Validar que el quiz pertenece a un módulo permitido
        $quiz = Quiz::with(['modulo', 'preguntas.opciones'])->whereIn('modulo_id', $modulosIds)->findOrFail($quiz_id);
        
        $routePrefix = 'sisig.editor';
        
        return view('sisig::admin.GestionPreguntas', compact('quiz', 'routePrefix'));
    }

    /**
     * Guarda una nueva pregunta
     */
    public function store(Request $request, $quiz_id)
    {
        $user = auth()->user();
        $modulosIds = $user->modulosEditables->pluck('id')->toArray();
        $quiz = Quiz::whereIn('modulo_id', $modulosIds)->findOrFail($quiz_id);

        $request->validate([
            'tipo' => 'required|string|in:opcion_multiple,seleccion_multiple,verdadero_falso,espacios_blanco,abierta,tabla_relleno',
            'pregunta' => 'required|string',
            'puntaje' => 'required|numeric|min:0',
            'retroalimentacion' => 'nullable|string',
            'configuracion_json' => 'nullable|array'
        ]);

        $pregunta = $quiz->preguntas()->create([
            'pregunta' => $request->pregunta,
            'tipo' => $request->tipo,
            'puntaje' => $request->puntaje,
            'retroalimentacion' => $request->retroalimentacion,
            'configuracion_json' => $request->configuracion_json
        ]);

        if ($request->has('opciones') && is_array($request->opciones)) {
            foreach ($request->opciones as $opcionData) {
                if (!empty($opcionData['texto'])) {
                    $pregunta->opciones()->create([
                        'opcion' => $opcionData['texto'],
                        'es_correcta' => isset($opcionData['es_correcta']) && $opcionData['es_correcta'] == 1
                    ]);
                }
            }
        }

        $quiz->numero_preguntas = $quiz->preguntas()->count();
        $quiz->save();

        return redirect()->route('sisig.editor.examenes.preguntas.index', $quiz_id)->with('success', 'Pregunta agregada exitosamente.');
    }

    /**
     * Elimina una pregunta
     */
    public function destroy($quiz_id, $pregunta_id)
    {
        $user = auth()->user();
        $modulosIds = $user->modulosEditables->pluck('id')->toArray();
        $quiz = Quiz::whereIn('modulo_id', $modulosIds)->findOrFail($quiz_id);

        $pregunta = Pregunta::where('id_quiz', $quiz_id)->where('id_pregunta', $pregunta_id)->firstOrFail();
        
        $pregunta->delete();

        $quiz->numero_preguntas = $quiz->preguntas()->count();
        $quiz->save();

        return redirect()->route('sisig.editor.examenes.preguntas.index', $quiz_id)->with('success', 'Pregunta eliminada.');
    }
}
