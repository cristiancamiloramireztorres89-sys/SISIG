<?php

namespace Modules\SISIG\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SISIG\Entities\Quiz;
use Modules\SISIG\Entities\Modulo;

class GestionExamenesController extends Controller
{
    protected string $routePrefix = 'sisig.admin';

    protected function getModulosPermitidos()
    {
        return Modulo::all();
    }

    protected function getModulosIdsPermitidos()
    {
        return $this->getModulosPermitidos()->pluck('id')->toArray();
    }

    protected function getQuizQuery()
    {
        return Quiz::with('modulo')->whereIn('modulo_id', $this->getModulosIdsPermitidos());
    }

    protected function findQuizOrFail($id)
    {
        return $this->getQuizQuery()->findOrFail($id);
    }

    public function index(Request $request)
    {
        $query = $this->getQuizQuery();
        $modulosIds = $this->getModulosIdsPermitidos();

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
        $modulos = $this->getModulosPermitidos();
        $routePrefix = $this->routePrefix;

        return view('sisig::admin.GestionExamenes', compact('quices', 'modulos', 'routePrefix'));
    }

    public function store(Request $request)
    {
        $modulosIds = $this->getModulosIdsPermitidos();

        $request->validate([
            'titulo' => 'required|string|max:100',
            'modulo_id' => 'required|in:' . implode(',', $modulosIds),
            'activo' => 'required|boolean',
            'numero_intentos' => 'required|integer|min:1',
            'puntaje_minimo' => 'required|numeric|min:0|max:100',
            'tiempo_minutos' => 'required|integer|min:0',
            'fecha_limite' => 'nullable|date'
        ]);

        Quiz::create($request->all());

        return redirect()->route($this->routePrefix . '.examenes.index')->with('success', 'Examen creado exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $modulosIds = $this->getModulosIdsPermitidos();

        $request->validate([
            'titulo' => 'required|string|max:100',
            'modulo_id' => 'required|in:' . implode(',', $modulosIds),
            'activo' => 'required|boolean',
            'numero_intentos' => 'required|integer|min:1',
            'puntaje_minimo' => 'required|numeric|min:0|max:100',
            'tiempo_minutos' => 'required|integer|min:0',
            'fecha_limite' => 'nullable|date'
        ]);

        $quiz = $this->findQuizOrFail($id);
        $quiz->fill($request->all());

        if (!$quiz->isDirty()) {
            return redirect()->route($this->routePrefix . '.examenes.index')->with('info', 'No se detectaron modificaciones en el examen.');
        }

        $quiz->save();

        return redirect()->route($this->routePrefix . '.examenes.index')->with('success', 'Examen actualizado exitosamente.');
    }

    public function toggleStatus($id)
    {
        $quiz = $this->findQuizOrFail($id);
        $quiz->activo = !$quiz->activo;
        $quiz->save();

        return redirect()->route($this->routePrefix . '.examenes.index')->with('success', 'Estado del examen actualizado.');
    }
}
