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
     * Verifica la autorización del editor de contenidos o administrador.
     */
    protected function checkAuthorization(): void
    {
        if (!auth()->check()) {
            abort(403, 'Acceso denegado: Usuario no autenticado.');
        }

        $user = auth()->user();
        if (!$user->hasRole('editor_sisig') && !$user->hasRole('admin_sisig') && !$user->hasRole('sisig.admin') && !$user->hasRole('superadmin')) {
            abort(403, 'Acceso denegado: Se requieren privilegios de Editor de Contenidos o Administrador SISIG.');
        }
    }

    /**
     * Busca un examen asegurando que pertenezca a los módulos asignados al editor (o a cualquiera si es admin).
     */
    protected function findQuizOrFail($quiz_id)
    {
        $user = auth()->user();
        if ($user->hasRole('admin_sisig') || $user->hasRole('sisig.admin') || $user->hasRole('superadmin')) {
            return Quiz::with(['modulo', 'preguntas.opciones'])->findOrFail($quiz_id);
        }

        $modulosIds = $user->modulosEditables->pluck('id')->toArray();

        return Quiz::with(['modulo', 'preguntas.opciones'])
            ->whereIn('modulo_id', $modulosIds)
            ->findOrFail($quiz_id);
    }
}
