<?php

namespace Modules\SISIG\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\SISIG\Entities\Modulo;

class GestionModulosController extends Controller
{
    /**
     * Muestra el catálogo de módulos temáticos y métricas de publicación.
     */
    public function index()
    {
        // Obtener todos los módulos registrados
        $modulos = Modulo::all();
        
        // Métricas de estado de publicación de los módulos
        $totalActivos = $modulos->count();
        $publicados   = $modulos->where('estado', 'publicado')->count();
        $revision     = $modulos->where('estado', 'revision')->count();
        $inactivos    = $modulos->where('estado', 'inactivo')->count();

        return view('sisig::admin.GestionModulos', compact('modulos', 'totalActivos', 'publicados', 'revision', 'inactivos'));
    }

    /**
     * Registra un nuevo módulo temático en la plataforma.
     */
    public function store(Request $request)
    {
        // Validación de datos del módulo
        $request->validate([
            'titulo'      => 'required|string|max:255',
            'norma'       => 'nullable|string|max:255',
            'estado'      => 'required|in:publicado,revision,inactivo',
            'descripcion' => 'nullable|string'
        ]);

        // Procesar imagen de portada (soporta archivo físico o Base64 recortado)
        $imagenPath = $this->procesarImagenModulo($request);

        // Guardar nuevo módulo en la base de datos con valores visuales por defecto
        Modulo::create([
            'titulo'       => $request->titulo,
            'norma'        => $request->norma,
            'estado'       => $request->estado,
            'descripcion'  => $request->descripcion,
            'imagen'       => $imagenPath,
            'icon_class'   => 'fas fa-layer-group text-sky-600',
            'icon_bg'      => 'bg-sky-100',
            'border_class' => 'border-sky-100'
        ]);

        return redirect()->back()->with('success', 'Módulo creado exitosamente');
    }

    /**
     * Actualiza la información general y portada de un módulo existente.
     */
    public function update(Request $request, $id)
    {
        // Validación de datos del formulario
        $request->validate([
            'titulo'      => 'required|string|max:255',
            'estado'      => 'required|in:publicado,revision,inactivo',
            'descripcion' => 'nullable|string'
        ]);

        $modulo = Modulo::findOrFail($id);
        $modulo->fill([
            'titulo'      => $request->titulo,
            'estado'      => $request->estado,
            'descripcion' => $request->descripcion
        ]);

        // Reemplazo de imagen de portada y eliminación del archivo anterior si existe
        if ($request->filled('imagen_recortada') || $request->hasFile('imagen')) {
            $nuevaImagen = $this->procesarImagenModulo($request);
            if ($nuevaImagen) {
                if ($modulo->imagen && str_contains($modulo->imagen, 'modulos/') && file_exists(public_path($modulo->imagen))) {
                    @unlink(public_path($modulo->imagen));
                }
                $modulo->imagen = $nuevaImagen;
            }
        }

        // Verificar si se realizaron cambios para evitar consultas innecesarias
        if (!$modulo->isDirty()) {
            return redirect()->back()->with('info', 'No se detectaron modificaciones en el módulo.');
        }

        $modulo->save();

        return redirect()->back()->with('success', 'Módulo actualizado exitosamente');
    }

    /**
     * Carga la vista del editor de contenido interactivo del módulo seleccionado.
     */
    public function editContent($id)
    {
        // Módulos para navegación por pestañas
        $modulos = Modulo::all();
        $moduloSeleccionado = Modulo::findOrFail($id);

        return view('sisig::admin.ContenidoModulo', compact('modulos', 'moduloSeleccionado'));
    }

    /**
     * Actualiza el contenido formativo HTML y fondo personalizado del módulo.
     */
    public function updateContent(Request $request, $id)
    {
        // Validación de contenido y nivel de opacidad
        $request->validate([
            'contenido_html' => 'nullable|string',
            'fondo_opacidad' => 'nullable|integer|min:0|max:100'
        ]);

        $modulo = Modulo::findOrFail($id);
        $modulo->contenido_html = $request->contenido_html;

        // Gestión del fondo del módulo: remover o actualizar por nuevo recorte
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

        // Configuración del nivel de opacidad del fondo
        if ($request->has('fondo_opacidad')) {
            $modulo->fondo_opacidad = (int) $request->input('fondo_opacidad');
        }

        $modulo->save();

        return redirect()->route('sisig.admin.modulos.index')->with('success', 'Contenido y fondo actualizados exitosamente');
    }

    /**
     * Elimina un módulo y sus archivos multimedia asociados del servidor.
     */
    public function destroy($id)
    {
        $modulo = Modulo::findOrFail($id);
        $titulo = $modulo->titulo;

        // Limpiar archivo físico de portada
        if ($modulo->imagen && file_exists(public_path($modulo->imagen))) {
            @unlink(public_path($modulo->imagen));
        }

        // Limpiar archivo físico de fondo
        if ($modulo->fondo_imagen && file_exists(public_path($modulo->fondo_imagen))) {
            @unlink(public_path($modulo->fondo_imagen));
        }

        // Eliminar registro del módulo en base de datos
        $modulo->delete();

        return redirect()->route('sisig.admin.modulos.index')->with('success', 'Módulo "' . ucfirst($titulo) . '" eliminado exitosamente');
    }

    /**
     * Procesa la imagen del módulo ya sea en Base64 (recortada) o subida como archivo directo.
     */
    private function procesarImagenModulo(Request $request)
    {
        // Imagen recortada desde canvas Base64
        if ($request->filled('imagen_recortada')) {
            return $this->guardarImagenBase64($request->input('imagen_recortada'), 'modulo');
        } 
        
        // Imagen subida como archivo físico directo
        elseif ($request->hasFile('imagen')) {
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
        // Validar formato y decodificar flujo Base64
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
