<?php

namespace Modules\SISIG\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SISIG\Entities\Quiz;
use Modules\SISIG\Entities\Pregunta;
use Modules\SISIG\Entities\Opcion;

class GestionPreguntasController extends Controller
{
    protected string $routePrefix = 'sisig.admin';

    protected function findQuizOrFail($quiz_id)
    {
        return Quiz::with(['modulo', 'preguntas.opciones'])->findOrFail($quiz_id);
    }

    public function index($quiz_id)
    {
        $quiz = $this->findQuizOrFail($quiz_id);
        $routePrefix = $this->routePrefix;
        
        return view('sisig::admin.GestionPreguntas', compact('quiz', 'routePrefix'));
    }

    public function store(Request $request, $quiz_id)
    {
        $quiz = $this->findQuizOrFail($quiz_id);

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

        return redirect()->route($this->routePrefix . '.examenes.preguntas.index', $quiz_id)->with('success', 'Pregunta agregada exitosamente.');
    }

    public function update(Request $request, $quiz_id, $pregunta_id)
    {
        $quiz = $this->findQuizOrFail($quiz_id);
        $pregunta = Pregunta::where('id_quiz', $quiz_id)->where('id_pregunta', $pregunta_id)->firstOrFail();

        $request->validate([
            'pregunta' => 'required|string',
            'puntaje' => 'required|numeric|min:0',
            'retroalimentacion' => 'nullable|string',
            'configuracion_json' => 'nullable|array'
        ]);

        $pregunta->update([
            'pregunta' => $request->pregunta,
            'puntaje' => $request->puntaje,
            'retroalimentacion' => $request->retroalimentacion,
            'configuracion_json' => $request->configuracion_json
        ]);

        if ($request->has('opciones') && is_array($request->opciones)) {
            $pregunta->opciones()->delete();

            foreach ($request->opciones as $opcionData) {
                if (!empty($opcionData['texto'])) {
                    $pregunta->opciones()->create([
                        'opcion' => $opcionData['texto'],
                        'es_correcta' => isset($opcionData['es_correcta']) && $opcionData['es_correcta'] == 1
                    ]);
                }
            }
        }

        return redirect()->route($this->routePrefix . '.examenes.preguntas.index', $quiz_id)->with('success', 'Pregunta actualizada exitosamente.');
    }

    public function destroy($quiz_id, $pregunta_id)
    {
        $quiz = $this->findQuizOrFail($quiz_id);
        $pregunta = Pregunta::where('id_quiz', $quiz_id)->where('id_pregunta', $pregunta_id)->firstOrFail();
        
        $pregunta->delete();

        $quiz->numero_preguntas = $quiz->preguntas()->count();
        $quiz->save();

        return redirect()->route($this->routePrefix . '.examenes.preguntas.index', $quiz_id)->with('success', 'Pregunta eliminada.');
    }
}
