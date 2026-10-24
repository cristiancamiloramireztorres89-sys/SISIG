<?php

namespace Modules\SISIG\Http\Controllers\Editor;

use Illuminate\Http\Request;
use Modules\SISIG\Entities\Quiz;
use Modules\SISIG\Http\Controllers\Admin\GestionExamenesController as AdminGestionExamenesController;

class GestionExamenesController extends AdminGestionExamenesController
{
    protected string $routePrefix = 'sisig.editor';

    protected function getModulosPermitidos()
    {
        return auth()->user()->modulosEditables;
    }

    /**
     * Valida el límite de exámenes por módulo según las políticas del sistema.
     */
    public function store(Request $request)
    {
        $limiteMaximo = 10;
        $conteoActual = Quiz::where('modulo_id', $request->modulo_id)->count();

        if ($conteoActual >= $limiteMaximo) {
            return redirect()->back()
                ->withInput()
                ->with('error', "No se pueden crear más evaluaciones. El módulo ha alcanzado el límite máximo permitido ({$limiteMaximo}) según las políticas del sistema.");
        }

        return parent::store($request);
    }

    /**
     * Valida si se permite modificar un examen con intentos registrados según las políticas.
     */
    public function update(Request $request, $id)
    {
        $puedeEditarConRespuestas = false;
        
        if (!$puedeEditarConRespuestas) {
            $quiz = $this->findQuizOrFail($id);
            if ($quiz->resultados()->exists()) {
                return redirect()->back()
                    ->with('error', 'Por política institucional, no se permite modificar evaluaciones que ya cuentan con intentos registrados por aprendices.');
            }
        }

        return parent::update($request, $id);
    }
}
