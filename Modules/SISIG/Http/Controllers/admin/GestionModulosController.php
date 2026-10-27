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

        $imagenPath = $this->procesarImagenModulo($request);

        Modulo::create([
            'titulo' => $request->titulo,
            'norma' => $request->norma,
            'estado' => $request->estado,
            'descripcion' => $request->descripcion,
            'imagen' => $imagenPath,
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
        $modulo->fill([
            'titulo' => $request->titulo,
            'estado' => $request->estado,
            'descripcion' => $request->descripcion
        ]);

        if ($request->filled('imagen_recortada') || $request->hasFile('imagen')) {
            $nuevaImagen = $this->procesarImagenModulo($request);
            if ($nuevaImagen) {
                if ($modulo->imagen && str_contains($modulo->imagen, 'modulos/') && file_exists(public_path($modulo->imagen))) {
                    @unlink(public_path($modulo->imagen));
                }
                $modulo->imagen = $nuevaImagen;
            }
        }

        if (!$modulo->isDirty()) {
            return redirect()->back()->with('info', 'No se detectaron modificaciones en el módulo.');
        }

        $modulo->save();

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
            'contenido_html' => 'nullable|string',
            'fondo_opacidad' => 'nullable|integer|min:0|max:100'
        ]);

        $modulo = Modulo::findOrFail($id);
        $modulo->contenido_html = $request->contenido_html;

        if ($request->input('quitar_fondo') === '1') {
            if ($modulo->fondo_imagen && file_exists(public_path($modulo->fondo_imagen))) {
                @unlink(public_path($modulo->fondo_imagen));
            }
            $modulo->fondo_imagen = null;
        } elseif ($request->filled('fondo_imagen_recortada')) {
            $nuevoFondo = $this->guardarImagenBase64($request->input('fondo_imagen_recortada'), 'fondo');
            if ($nuevoFondo) {
                if ($modulo->fondo_imagen && file_exists(public_path($modulo->fondo_imagen))) {
                    @unlink(public_path($modulo->fondo_imagen));
                }
                $modulo->fondo_imagen = $nuevoFondo;
            }
        }

        if ($request->has('fondo_opacidad')) {
            $modulo->fondo_opacidad = (int) $request->input('fondo_opacidad');
        }

        $modulo->save();

        return redirect()->route('sisig.admin.modulos.index')->with('success', 'Contenido y fondo actualizados exitosamente');
    }

    public function destroy($id)
    {
        $modulo = Modulo::findOrFail($id);
        $titulo = $modulo->titulo;

        if ($modulo->imagen && file_exists(public_path($modulo->imagen))) {
            @unlink(public_path($modulo->imagen));
        }

        if ($modulo->fondo_imagen && file_exists(public_path($modulo->fondo_imagen))) {
            @unlink(public_path($modulo->fondo_imagen));
        }

        $modulo->delete();

        return redirect()->route('sisig.admin.modulos.index')->with('success', 'Módulo "' . ucfirst($titulo) . '" eliminado exitosamente');
    }

    /**
     * Procesa la imagen del módulo ya sea en Base64 (recortada) o subida como archivo directo.
     */
    private function procesarImagenModulo(Request $request)
    {
        if ($request->filled('imagen_recortada')) {
            return $this->guardarImagenBase64($request->input('imagen_recortada'), 'modulo');
        } elseif ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $filename = 'modulo_' . time() . '_' . uniqid() . '.' . $extension;
            $destinationDir = public_path('modules/sisig/images/modulos');
            if (!file_exists($destinationDir)) {
                mkdir($destinationDir, 0755, true);
            }
            $file->move($destinationDir, $filename);
            return 'modules/sisig/images/modulos/' . $filename;
        }

        return null;
    }

    /**
     * Decodifica y guarda una imagen Base64 DataURL en el directorio de módulos.
     */
    private function guardarImagenBase64($dataUrl, $prefijo = 'modulo')
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $dataUrl, $type)) {
            $dataUrl = substr($dataUrl, strpos($dataUrl, ',') + 1);
            $type = strtolower($type[1]);
            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png', 'webp'])) {
                $type = 'png';
            }
            $decoded = base64_decode($dataUrl);
            if ($decoded !== false) {
                $filename = $prefijo . '_' . time() . '_' . uniqid() . '.' . $type;
                $destinationDir = public_path('modules/sisig/images/modulos');
                if (!file_exists($destinationDir)) {
                    mkdir($destinationDir, 0755, true);
                }
                file_put_contents($destinationDir . DIRECTORY_SEPARATOR . $filename, $decoded);
                return 'modules/sisig/images/modulos/' . $filename;
            }
        }
        return null;
    }
}
