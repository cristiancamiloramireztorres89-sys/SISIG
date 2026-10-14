<?php

namespace Modules\SISIG\Http\Controllers\Editor;

use Modules\SISIG\Entities\Quiz;
use Modules\SISIG\Http\Controllers\Admin\GestionPreguntasController as AdminGestionPreguntasController;

class GestionPreguntasController extends AdminGestionPreguntasController
{
    protected string $routePrefix = 'sisig.editor';

    protected function findQuizOrFail($quiz_id)
    {
        $user = auth()->user();
        $modulosIds = $user->modulosEditables->pluck('id')->toArray();

        return Quiz::with(['modulo', 'preguntas.opciones'])
            ->whereIn('modulo_id', $modulosIds)
            ->findOrFail($quiz_id);
    }
}
