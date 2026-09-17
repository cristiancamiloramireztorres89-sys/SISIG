<?php

namespace Modules\SISIG\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SISIG\Entities\Quiz;

class GestionExamenesController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $modulosIds = $user->modulosEditables->pluck('id')->toArray();

        $query = Quiz::with('modulo')->whereIn('modulo_id', $modulosIds);

        // Filtros
        if ($request->has('modulo_id') && $request->modulo_id != 'all') {
            if (in_array($request->modulo_id, $modulosIds)) {
                $query->where('modulo_id', $request->modulo_id);
            }
        }
        
        if ($request->has('estado') && $request->estado != 'all') {
            $estado = $request->estado == 'activo' ? 1 : 0;
            $query->where('activo', $estado);
        }

        if ($request->has('buscar') && $request->buscar != '') {
            $query->where('titulo', 'LIKE', '%' . $request->buscar . '%');
        }

        $quices = $query->get();
        $modulos = $user->modulosEditables;
        $routePrefix = 'sisig.editor';

        return view('sisig::admin.GestionExamenes', compact('quices', 'modulos', 'routePrefix'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $modulosIds = $user->modulosEditables->pluck('id')->toArray();

        $request->validate([
            'titulo' => 'required|string|max:100',
            'modulo_id' => 'required|in:' . implode(',', $modulosIds), // Solo módulos permitidos
            'activo' => 'required|boolean',
            'numero_intentos' => 'required|integer|min:1',
            'puntaje_minimo' => 'required|numeric|min:0|max:100',
            'tiempo_minutos' => 'required|integer|min:0',
            'fecha_limite' => 'nullable|date'
        ]);

        Quiz::create($request->all());

        return redirect()->route('sisig.editor.examenes.index')->with('success', 'Examen creado exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $modulosIds = $user->modulosEditables->pluck('id')->toArray();

        $request->validate([
            'titulo' => 'required|string|max:100',
            'modulo_id' => 'required|in:' . implode(',', $modulosIds),
            'activo' => 'required|boolean',
            'numero_intentos' => 'required|integer|min:1',
            'puntaje_minimo' => 'required|numeric|min:0|max:100',
            'tiempo_minutos' => 'required|integer|min:0',
            'fecha_limite' => 'nullable|date'
        ]);

        $quiz = Quiz::whereIn('modulo_id', $modulosIds)->findOrFail($id);
        $quiz->update($request->all());

        return redirect()->route('sisig.editor.examenes.index')->with('success', 'Examen actualizado exitosamente.');
    }

    public function toggleStatus($id)
    {
        $user = auth()->user();
        $modulosIds = $user->modulosEditables->pluck('id')->toArray();

        $quiz = Quiz::whereIn('modulo_id', $modulosIds)->findOrFail($id);
        $quiz->activo = !$quiz->activo;
        $quiz->save();

        return redirect()->route('sisig.editor.examenes.index')->with('success', 'Estado del examen actualizado.');
    }
}
