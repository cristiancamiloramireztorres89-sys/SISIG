<?php

namespace Modules\SISIG\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckEditorSisig
{
    /**
     * Intercepta y verifica que el usuario autenticado posea privilegios de Editor o Administrador SISIG.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->guest(route('login'));
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $hasEditorRole = $user->hasRole('editor_sisig') 
            || $user->hasRole('admin_sisig') 
            || $user->hasRole('sisig.admin') 
            || $user->hasRole('superadmin');

        if (!$hasEditorRole) {
            abort(403, 'Acceso denegado: Se requieren privilegios de Editor de Contenidos o Administrador SISIG.');
        }

        return $next($request);
    }
}

