<?php

namespace Modules\SISIG\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
     * Verifica la autorización del usuario actual (sobreescribible en controladores derivados).
     */
    protected function checkAuthorization(): void
    {
        if (!Auth::check() || (!Auth::user()->hasRole('admin_sisig') && !Auth::user()->hasRole('sisig.admin') && !Auth::user()->hasRole('superadmin'))) {
            abort(403, 'Acceso denegado: Se requieren privilegios de Administrador SISIG.');
        }
    }

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
        $this->checkAuthorization();

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
        $this->checkAuthorization();

        // Obtener el examen contenedor
        $quiz = $this->findQuizOrFail($quiz_id);

        // Validación de datos de la pregunta
        $validated = $request->validate([
            'tipo'               => 'required|string|in:opcion_multiple,seleccion_multiple,verdadero_falso,espacios_blanco,abierta,tabla_relleno',
            'pregunta'           => 'required|string|max:2000',
            'puntaje'            => 'required|numeric|min:0|max:100',
            'retroalimentacion'  => 'nullable|string|max:2000',
            'configuracion_json' => 'nullable|array'
        ]);

        // Crear la pregunta vinculada al examen
        $pregunta = $quiz->preguntas()->create([
            'pregunta'           => trim($validated['pregunta']),
            'tipo'               => $validated['tipo'],
            'puntaje'            => (float)$validated['puntaje'],
            'retroalimentacion'  => isset($validated['retroalimentacion']) ? trim($validated['retroalimentacion']) : null,
            'configuracion_json' => $validated['configuracion_json'] ?? null
        ]);

        // Registrar opciones de respuesta si fueron suministradas
        if ($request->has('opciones') && is_array($request->opciones)) {
            foreach ($request->opciones as $opcionData) {
                if (is_array($opcionData) && !empty(trim($opcionData['texto'] ?? ''))) {
                    $pregunta->opciones()->create([
                        'opcion'      => trim($opcionData['texto']),
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
        $this->checkAuthorization();

        // Verificar existencia del examen y de la pregunta
        $quiz = $this->findQuizOrFail($quiz_id);
        $pregunta = Pregunta::where('id_quiz', $quiz_id)->where('id_pregunta', $pregunta_id)->firstOrFail();

        // Validación de campos editables
        $validated = $request->validate([
            'pregunta'           => 'required|string|max:2000',
            'puntaje'            => 'required|numeric|min:0|max:100',
            'retroalimentacion'  => 'nullable|string|max:2000',
            'configuracion_json' => 'nullable|array'
        ]);

        // Actualizar datos básicos de la pregunta
        $pregunta->update([
            'pregunta'           => trim($validated['pregunta']),
            'puntaje'            => (float)$validated['puntaje'],
            'retroalimentacion'  => isset($validated['retroalimentacion']) ? trim($validated['retroalimentacion']) : null,
            'configuracion_json' => $validated['configuracion_json'] ?? null
        ]);

        // Sincronizar opciones de respuesta (reemplazo seguro de opciones existentes)
        if ($request->has('opciones') && is_array($request->opciones)) {
            $pregunta->opciones()->delete();

            foreach ($request->opciones as $opcionData) {
                if (is_array($opcionData) && !empty(trim($opcionData['texto'] ?? ''))) {
                    $pregunta->opciones()->create([
                        'opcion'      => trim($opcionData['texto']),
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
        $this->checkAuthorization();

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
