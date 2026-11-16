<?php

namespace Modules\SISIG\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\SISIG\Entities\Modulo;
use Modules\SISIG\Helpers\HtmlSecuritySanitizer;

class GestionModulosController extends Controller
{
    /**
     * Valida la autorización del usuario actual para operaciones administrativas sobre módulos.
     */
    protected function checkAdminAuthorization(): void
    {
        if (!Auth::check() || (!Auth::user()->hasRole('admin_sisig') && !Auth::user()->hasRole('sisig.admin') && !Auth::user()->hasRole('superadmin'))) {
            abort(403, 'Acceso denegado: Se requieren privilegios de Administrador SISIG para gestionar módulos.');
        }
    }

    /**
     * Muestra el catálogo de módulos temáticos y métricas de publicación.
     */
    public function index()
    {
        $this->checkAdminAuthorization();

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
     * Registra un nuevo módulo temático en la plataforma con validación estricta.
     */
    public function store(Request $request)
    {
        $this->checkAdminAuthorization();

        // Validación exhaustiva de datos del módulo incluyendo imágenes
        $request->validate([
            'titulo'           => 'required|string|max:255',
            'norma'            => 'nullable|string|max:255',
            'estado'           => 'required|in:publicado,revision,inactivo',
            'descripcion'      => 'nullable|string|max:2000',
            'imagen'           => 'nullable|file|image|mimes:jpeg,png,jpg,webp|max:2048',
            'imagen_recortada' => 'nullable|string',
        ]);

        // Procesar imagen de portada de forma segura
        $imagenPath = $this->procesarImagenModulo($request);

        // Guardar nuevo módulo en la base de datos con valores visuales por defecto
        Modulo::create([
            'titulo'       => trim($request->titulo),
            'norma'        => $request->filled('norma') ? trim($request->norma) : null,
            'estado'       => $request->estado,
            'descripcion'  => $request->filled('descripcion') ? trim($request->descripcion) : null,
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
        $this->checkAdminAuthorization();

        // Validación de datos del formulario incluyendo archivos
        $request->validate([
            'titulo'           => 'required|string|max:255',
            'norma'            => 'nullable|string|max:255',
            'estado'           => 'required|in:publicado,revision,inactivo',
            'descripcion'      => 'nullable|string|max:2000',
            'imagen'           => 'nullable|file|image|mimes:jpeg,png,jpg,webp|max:2048',
            'imagen_recortada' => 'nullable|string',
        ]);

        $modulo = Modulo::findOrFail($id);
        $modulo->fill([
            'titulo'      => trim($request->titulo),
            'norma'       => $request->filled('norma') ? trim($request->norma) : $modulo->norma,
            'estado'      => $request->estado,
            'descripcion' => $request->filled('descripcion') ? trim($request->descripcion) : null,
        ]);

        // Reemplazo seguro de imagen de portada y eliminación controlada del archivo anterior
        if ($request->filled('imagen_recortada') || $request->hasFile('imagen')) {
            $nuevaImagen = $this->procesarImagenModulo($request);
            if ($nuevaImagen) {
                if ($modulo->imagen && str_contains($modulo->imagen, 'modules/sisig/images/modulos/') && file_exists(public_path($modulo->imagen))) {
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
        $this->checkAdminAuthorization();

        $modulos = Modulo::all();
        $moduloSeleccionado = Modulo::findOrFail($id);

        return view('sisig::admin.ContenidoModulo', compact('modulos', 'moduloSeleccionado'));
    }

    /**
     * Actualiza el contenido formativo HTML y fondo personalizado del módulo con saneamiento XSS.
     */
    public function updateContent(Request $request, $id)
    {
        $this->checkAdminAuthorization();

        // Validación de parámetros
        $request->validate([
            'contenido_html'         => 'nullable|string',
            'fondo_opacidad'         => 'nullable|integer|min:0|max:100',
            'fondo_imagen_recortada' => 'nullable|string',
            'quitar_fondo'           => 'nullable|in:0,1',
        ]);

        $modulo = Modulo::findOrFail($id);

        // Saneamiento de seguridad de la entrada HTML enriquecida contra Stored XSS
        $modulo->contenido_html = HtmlSecuritySanitizer::clean($request->contenido_html);

        // Gestión del fondo del módulo: remover o actualizar por nuevo recorte
        if ($request->input('quitar_fondo') === '1') {
            if ($modulo->fondo_imagen && str_contains($modulo->fondo_imagen, 'modules/sisig/images/modulos/') && file_exists(public_path($modulo->fondo_imagen))) {
                @unlink(public_path($modulo->fondo_imagen));
            }
            $modulo->fondo_imagen = null;
        } elseif ($request->filled('fondo_imagen_recortada')) {
            $nuevoFondo = $this->guardarImagenBase64($request->input('fondo_imagen_recortada'), 'fondo');
            if ($nuevoFondo) {
                if ($modulo->fondo_imagen && str_contains($modulo->fondo_imagen, 'modules/sisig/images/modulos/') && file_exists(public_path($modulo->fondo_imagen))) {
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
        $this->checkAdminAuthorization();

        try {
            $modulo = Modulo::findOrFail($id);
            $titulo = $modulo->titulo;

            // Limpiar archivo físico de portada de forma controlada
            if ($modulo->imagen && str_contains($modulo->imagen, 'modules/sisig/images/modulos/') && file_exists(public_path($modulo->imagen))) {
                @unlink(public_path($modulo->imagen));
            }

            // Limpiar archivo físico de fondo de forma controlada
            if ($modulo->fondo_imagen && str_contains($modulo->fondo_imagen, 'modules/sisig/images/modulos/') && file_exists(public_path($modulo->fondo_imagen))) {
                @unlink(public_path($modulo->fondo_imagen));
            }

            // Eliminar registro del módulo en base de datos
            $modulo->delete();

            return redirect()->route('sisig.admin.modulos.index')->with('success', 'Módulo "' . ucfirst($titulo) . '" eliminado exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al eliminar módulo en SISIG: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('sisig.admin.modulos.index')->with('error', 'Ocurrió un error al intentar eliminar el módulo.');
        }
    }

    /**
     * Procesa la imagen del módulo con validación estricta de extensiones y tipos MIME.
     */
    private function procesarImagenModulo(Request $request): ?string
    {
        // Imagen recortada desde canvas Base64
        if ($request->filled('imagen_recortada')) {
            return $this->guardarImagenBase64($request->input('imagen_recortada'), 'modulo');
        } 
        
        // Imagen subida como archivo físico directo
        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');

            // Determinar extensión segura basada en MIME real comprobado
            $mime = $file->getMimeType();
            $allowedMimes = [
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/webp' => 'webp',
            ];

            if (!isset($allowedMimes[$mime])) {
                return null;
            }

            $extension = $allowedMimes[$mime];
            $filename = 'modulo_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
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
     * Decodifica y guarda una imagen Base64 DataURL validando formato, tamaño y encabezados binarios.
     */
    private function guardarImagenBase64($dataUrl, $prefijo = 'modulo'): ?string
    {
        if (!is_string($dataUrl)) {
            return null;
        }

        // Validar formato de esquema Data URL
        if (preg_match('/^data:image\/(jpeg|png|webp);base64,([A-Za-z0-9+\/=\r\n]+)$/', $dataUrl, $matches)) {
            $type = strtolower($matches[1]);
            if ($type === 'jpeg') {
                $type = 'jpg';
            }

            $decoded = base64_decode($matches[2], true);
            if ($decoded === false || strlen($decoded) === 0) {
                return null;
            }

            // Límite de tamaño estricto de 2.5 MB para prevenir agotamiento de memoria
            if (strlen($decoded) > 2621440) {
                return null;
            }

            // Comprobar que realmente contiene una cabecera de imagen binaria válida
            $imageInfo = @getimagesizefromstring($decoded);
            if ($imageInfo === false) {
                return null;
            }

            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if (!in_array($imageInfo['mime'], $allowedMimeTypes, true)) {
                return null;
            }

            $filename = $prefijo . '_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $type;
            $destinationDir = public_path('modules/sisig/images/modulos');
            
            if (!file_exists($destinationDir)) {
                mkdir($destinationDir, 0755, true);
            }
            
            file_put_contents($destinationDir . DIRECTORY_SEPARATOR . $filename, $decoded);
            return 'modules/sisig/images/modulos/' . $filename;
        }
        
        return null;
    }
}
