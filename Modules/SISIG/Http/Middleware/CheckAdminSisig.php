<?php

namespace Modules\SISIG\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdminSisig
{
    /**
     * Intercepta y verifica que el usuario autenticado posea privilegios de Administrador SISIG.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->guest(route('login'));
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $hasAdminRole = $user->hasRole('admin_sisig') 
            || $user->hasRole('sisig.admin') 
            || $user->hasRole('superadmin');

        if (!$hasAdminRole) {
            abort(403, 'Acceso denegado: Se requieren privilegios de Administrador SISIG para acceder a este recurso.');
        }

        return $next($request);
    }
}

