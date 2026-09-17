<?php

namespace Modules\SISIG\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SISIG\Entities\Quiz;
use Modules\SISIG\Entities\Modulo;

class GestionExamenesController extends Controller
{
    public function index(Request $request)
    {
        $query = Quiz::with('modulo');

        // Filtros
        if ($request->has('modulo_id') && $request->modulo_id != 'all') {
            $query->where('modulo_id', $request->modulo_id);
        }
        
        if ($request->has('estado') && $request->estado != 'all') {
            $estado = $request->estado == 'activo' ? 1 : 0;
            $query->where('activo', $estado);
        }

        if ($request->has('buscar') && $request->buscar != '') {
            $query->where('titulo', 'LIKE', '%' . $request->buscar . '%');
        }

        $quices = $query->get();
        $modulos = Modulo::all();
        $routePrefix = 'sisig.admin';

        return view('sisig::admin.GestionExamenes', compact('quices', 'modulos', 'routePrefix'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:100',
            'modulo_id' => 'required|exists:sisig_modulos,id',
            'activo' => 'required|boolean',
            'numero_intentos' => 'required|integer|min:1',
            'puntaje_minimo' => 'required|numeric|min:0|max:100',
            'tiempo_minutos' => 'required|integer|min:0',
            'fecha_limite' => 'nullable|date'
        ]);

        Quiz::create($request->all());

        return redirect()->route('sisig.admin.examenes.index')->with('success', 'Examen creado exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|string|max:100',
            'modulo_id' => 'required|exists:sisig_modulos,id',
            'activo' => 'required|boolean',
            'numero_intentos' => 'required|integer|min:1',
            'puntaje_minimo' => 'required|numeric|min:0|max:100',
            'tiempo_minutos' => 'required|integer|min:0',
            'fecha_limite' => 'nullable|date'
        ]);

        $quiz = Quiz::findOrFail($id);
        $quiz->update($request->all());

        return redirect()->route('sisig.admin.examenes.index')->with('success', 'Examen actualizado exitosamente.');
    }

    public function toggleStatus($id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->activo = !$quiz->activo;
        $quiz->save();

        return redirect()->route('sisig.admin.examenes.index')->with('success', 'Estado del examen actualizado.');
    }
}
