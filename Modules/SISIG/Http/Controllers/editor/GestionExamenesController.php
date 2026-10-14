<?php

namespace Modules\SISIG\Http\Controllers\Editor;

use Modules\SISIG\Http\Controllers\Admin\GestionExamenesController as AdminGestionExamenesController;

class GestionExamenesController extends AdminGestionExamenesController
{
    protected string $routePrefix = 'sisig.editor';

    protected function getModulosPermitidos()
    {
        return auth()->user()->modulosEditables;
    }
}
