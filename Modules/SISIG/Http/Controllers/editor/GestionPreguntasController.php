<?php

namespace Modules\SISIG\Http\Controllers\Editor;

use Modules\SISIG\Entities\Quiz;
use Modules\SISIG\Http\Controllers\Admin\GestionPreguntasController as AdminGestionPreguntasController;

class GestionPreguntasController extends AdminGestionPreguntasController
{
    /**
     * Prefijo de rutas para las redirecciones del panel de editor.
     */
    protected string $routePrefix = 'sisig.editor';

    /**
     * Busca un examen asegurando que pertenezca a los módulos asignados al editor.
     */
    protected function findQuizOrFail($quiz_id)
    {
        // Obtener IDs de módulos que el editor tiene autorizados para editar
        $user = auth()->user();
        $modulosIds = $user->modulosEditables->pluck('id')->toArray();

        // Consultar el examen restringiendo el alcance a los módulos permitidos
        return Quiz::with(['modulo', 'preguntas.opciones'])
            ->whereIn('modulo_id', $modulosIds)
            ->findOrFail($quiz_id);
    }
}
