<?php

namespace Modules\SISIG\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\SISIG\Entities\Modulo;

class GestionModulosController extends Controller
{
    public function index()
    {
        $modulos = Modulo::all();
        
        $totalActivos = $modulos->count();
        $publicados = $modulos->where('estado', 'publicado')->count();
        $revision = $modulos->where('estado', 'revision')->count();
        $inactivos = $modulos->where('estado', 'inactivo')->count();

        return view('sisig::admin.GestionModulos', compact('modulos', 'totalActivos', 'publicados', 'revision', 'inactivos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'norma' => 'nullable|string|max:255',
            'estado' => 'required|in:publicado,revision,inactivo',
            'descripcion' => 'nullable|string'
        ]);

        Modulo::create([
            'titulo' => $request->titulo,
            'norma' => $request->norma,
            'estado' => $request->estado,
            'descripcion' => $request->descripcion,
            // Valores por defecto visuales (luego podrían ser dinámicos)
            'icon_class' => 'fas fa-layer-group text-sky-600',
            'icon_bg' => 'bg-sky-100',
            'border_class' => 'border-sky-100'
        ]);

        return redirect()->back()->with('success', 'Módulo creado exitosamente');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'estado' => 'required|in:publicado,revision,inactivo',
            'descripcion' => 'nullable|string'
        ]);

        $modulo = Modulo::findOrFail($id);
        $modulo->update([
            'titulo' => $request->titulo,
            'estado' => $request->estado,
            'descripcion' => $request->descripcion
        ]);

        return redirect()->back()->with('success', 'Módulo actualizado exitosamente');
    }

    public function editContent($id)
    {
        $modulos = Modulo::all(); // Para las pestañas
        $moduloSeleccionado = Modulo::findOrFail($id);

        return view('sisig::admin.ContenidoModulo', compact('modulos', 'moduloSeleccionado'));
    }

    public function updateContent(Request $request, $id)
    {
        $request->validate([
            'contenido_html' => 'nullable|string'
        ]);

        $modulo = Modulo::findOrFail($id);
        $modulo->contenido_html = $request->contenido_html;
        $modulo->save();

        return redirect()->route('sisig.admin.modulos.index')->with('success', 'Contenido actualizado exitosamente');
    }
}
