<?php

namespace Modules\SISIG\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\SISIG\Entities\Modulo;
use Modules\SISIG\Helpers\HtmlSecuritySanitizer;

class ContenidoController extends Controller
{
    /**
     * Valida la autorización del usuario actual para el entorno de edición.
     */
    protected function checkEditorAuthorization(): void
    {
        if (!Auth::check()) {
            abort(403, 'Acceso denegado: Usuario no autenticado.');
        }

        $user = Auth::user();
        if (!$user->hasRole('editor_sisig') && !$user->hasRole('admin_sisig') && !$user->hasRole('sisig.admin') && !$user->hasRole('superadmin')) {
            abort(403, 'Acceso denegado: Se requieren privilegios de Editor de Contenidos o Administrador SISIG.');
        }
    }

    /**
     * Muestra la lista de módulos que el editor tiene asignados para gestionar contenido.
     */
    public function index()
    {
        $this->checkEditorAuthorization();

        $user = auth()->user();
        if ($user->hasRole('admin_sisig') || $user->hasRole('sisig.admin') || $user->hasRole('superadmin')) {
            $modulos = Modulo::all();
        } else {
            $modulos = $user->modulosEditables;
        }

        // Métricas de estado de los módulos disponibles
        $totalActivos = $modulos->count();
        $publicados   = $modulos->where('estado', 'publicado')->count();
        $revision     = $modulos->where('estado', 'revision')->count();
        $inactivos    = $modulos->where('estado', 'inactivo')->count();

        return view('sisig::editor.contenido.index', compact('modulos', 'totalActivos', 'publicados', 'revision', 'inactivos'));
    }

    /**
     * Carga el editor de contenido interactivo (Redirige al nuevo Editor Visual Canvas tipo Canva).
     */
    public function edit($id)
    {
        $this->checkEditorAuthorization();

        $user = auth()->user();
        
        // Carga y validación estricta de permisos sobre el módulo solicitado
        if ($user->hasRole('admin_sisig') || $user->hasRole('sisig.admin') || $user->hasRole('superadmin')) {
            $modulos = Modulo::all();
            $moduloSeleccionado = Modulo::findOrFail($id);
        } else {
            $modulos = $user->modulosEditables;
            $moduloSeleccionado = $user->modulosEditables()->where('sisig_modulos.id', $id)->first();
            
            // Denegar acceso si el módulo no está asignado al editor
            if (!$moduloSeleccionado) {
                abort(403, 'Acceso denegado: No tienes autorización asignada para editar el contenido de este módulo.');
            }
        }

        // Redirigir siempre de forma directa al nuevo Editor Canvas tipo Canva
        return redirect()->route('sisig.editor.canvas.editor', $id);
    }

    /**
     * Permite al editor crear un nuevo módulo temático y auto-asignárselo con validación estricta.
     */
    public function store(Request $request)
    {
        $this->checkEditorAuthorization();

        // Validación de datos del formulario incluyendo control de imágenes
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

        // Crear el módulo en la base de datos con estilos visuales iniciales
        $modulo = Modulo::create([
            'titulo'       => trim($request->titulo),
            'norma'        => $request->filled('norma') ? trim($request->norma) : null,
            'estado'       => $request->estado,
            'descripcion'  => $request->filled('descripcion') ? trim($request->descripcion) : null,
            'imagen'       => $imagenPath,
            'icon_class'   => 'fas fa-layer-group text-sky-600',
            'icon_bg'      => 'bg-sky-100',
            'border_class' => 'border-sky-100'
        ]);

        // Asignar automáticamente el módulo creado al editor actual
        $user = auth()->user();
        if ($user && method_exists($user, 'modulosEditables')) {
            $user->modulosEditables()->syncWithoutDetaching([$modulo->id]);
        }

        return redirect()->route('sisig.editor.contenido.index')
                         ->with('success', 'Módulo "' . ucfirst($modulo->titulo) . '" creado y asignado exitosamente.');
    }

    /**
     * Actualiza el contenido formativo HTML y fondo personalizado del módulo con prevención IDOR/BOLA y XSS.
     */
    public function update(Request $request, $id)
    {
        $this->checkEditorAuthorization();

        $user = auth()->user();

        // Mitigación de IDOR / BOLA: Validar titularidad antes de procesar cualquier cambio
        if (!$user->hasRole('admin_sisig') && !$user->hasRole('sisig.admin') && !$user->hasRole('superadmin')) {
            $moduloAutorizado = $user->modulosEditables()->where('sisig_modulos.id', $id)->first();
            if (!$moduloAutorizado) {
                abort(403, 'Acceso denegado: No tienes autorización para modificar el contenido de este módulo.');
            }
        }

        // Validación de contenido y nivel de opacidad
        $request->validate([
            'contenido_html'         => 'nullable|string',
            'fondo_opacidad'         => 'nullable|integer|min:0|max:100',
            'fondo_imagen_recortada' => 'nullable|string',
            'quitar_fondo'           => 'nullable|in:0,1',
        ]);

        $modulo = Modulo::findOrFail($id);

        // Saneamiento de seguridad de la entrada HTML enriquecida contra Stored XSS
        $modulo->contenido_html = HtmlSecuritySanitizer::clean($request->contenido_html);

        // Gestión del fondo del módulo: remover o actualizar por nuevo recorte Base64
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

        // Persistencia del estado JSON del lienzo/editor
        if ($request->filled('canvas_state')) {
            $canvasState = $request->input('canvas_state');
            if (is_string($canvasState)) {
                $decoded = json_decode($canvasState, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $canvasState = $decoded;
                }
            }
            $modulo->canvas_state = $canvasState;
        }

        $modulo->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Contenido y estado del lienzo actualizados exitosamente.',
                'data' => [
                    'id'             => $modulo->id,
                    'title'          => $modulo->titulo,
                    'canvas_state'   => $modulo->canvas_state,
                    'contenido_html' => $modulo->contenido_html,
                    'updated_at'     => $modulo->updated_at?->toIso8601String(),
                    'updated_human'  => $modulo->updated_at?->diffForHumans(),
                ]
            ], 200);
        }

        return redirect()->route('sisig.editor.contenido.index')
                         ->with('success', 'Contenido y fondo actualizados exitosamente.');
    }

    /**
     * Procesa la imagen del módulo con validación estricta de extensiones y tipos MIME reales.
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
