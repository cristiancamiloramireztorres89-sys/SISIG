<?php

namespace Modules\SISIG\Http\Controllers\editor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SISIG\Entities\Modulo;

class ContenidoController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->hasRole('admin_sisig') || $user->hasRole('sisig.admin')) {
            $modulos = Modulo::all();
        } else {
            $modulos = $user->modulosEditables;
        }

        return view('sisig::editor.contenido.index', compact('modulos'));
    }

    public function edit($id)
    {
        $user = auth()->user();
        
        if ($user->hasRole('admin_sisig') || $user->hasRole('sisig.admin')) {
            $modulos = Modulo::all();
            $moduloSeleccionado = Modulo::findOrFail($id);
        } else {
            // Verificar permiso del editor
            $modulos = $user->modulosEditables;
            $moduloSeleccionado = $user->modulosEditables()->where('sisig_modulos.id', $id)->first();
            
            if (!$moduloSeleccionado) {
                abort(403, 'No tienes permiso para editar este módulo.');
            }
        }

        return view('sisig::editor.contenido.edit', compact('modulos', 'moduloSeleccionado'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'contenido_html' => 'nullable|string'
        ]);

        $modulo = Modulo::findOrFail($id);
        $modulo->contenido_html = $request->contenido_html;
        $modulo->save();

        return redirect()->route('sisig.editor.contenido.index')
                         ->with('success', 'Contenido actualizado exitosamente.');
    }
}