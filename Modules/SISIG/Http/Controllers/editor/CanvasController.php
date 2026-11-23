<?php

namespace Modules\SISIG\Http\Controllers\editor;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\SISIG\Entities\InductionModule;
use Modules\SISIG\Entities\Modulo;

class CanvasController extends Controller
{
    /**
     * Muestra la interfaz gráfica del editor interactivo documental.
     * Redirige al editor institucional enriquecido tipo Google Docs con plantillas SIG.
     *
     * @param int|string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function showEditor($id = 1)
    {
        $id = request()->query('id', $id);
        if (!is_numeric($id) || (int)$id <= 0) {
            $primer = Modulo::first();
            $id = $primer ? $primer->id : 1;
        }

        return redirect()->route('sisig.editor.contenido.edit', $id);
    }
    /**
     * Valida la petición, extrae el payload JSON ($request->input('canvas_state'))
     * y actualiza el registro en la base de datos de forma asíncrona.
     *
     * @param Request $request
     * @param int|string|null $id
     * @return JsonResponse
     */
    public function saveState(Request $request, $id = null): JsonResponse
    {
        $moduleId = $id ?? $request->input('id') ?? $request->input('module_id');

        if (!is_numeric($moduleId) || (int)$moduleId <= 0) {
            $primer = Modulo::first();
            $moduleId = $primer ? $primer->id : 1;
        }

        // Validación de la petición
        $request->validate([
            'canvas_state' => 'required',
            'title'        => 'nullable|string|max:255',
            'titulo'       => 'nullable|string|max:255',
        ]);

        // Buscar en Modulo existente o en InductionModule
        $modulo = Modulo::find($moduleId) ?? InductionModule::find($moduleId);
        if (!$modulo) {
            return response()->json([
                'success' => false,
                'message' => "Módulo con ID {$moduleId} no encontrado."
            ], 404);
        }

        // Control de permisos amigable y seguro para editores y administradores
        $user = Auth::user();
        if ($user && method_exists($user, 'modulosEditables')) {
            $isAdmin = $user->hasRole('admin_sisig') || $user->hasRole('Administrador SISIG') || $user->hasRole('sisig.admin');
            if (!$isAdmin && !($modulo instanceof InductionModule)) {
                $user->modulosEditables()->syncWithoutDetaching([$moduleId]);
            }
        }

        // Extraer payload ($request->input('canvas_state'))
        $canvasState = $request->input('canvas_state');
        if (is_string($canvasState)) {
            $decoded = json_decode($canvasState, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $canvasState = $decoded;
            }
        }

        // Actualizar registro en la base de datos
        $modulo->canvas_state = $canvasState;

        if ($request->has('contenido_html') && isset($modulo->contenido_html)) {
            $modulo->contenido_html = $request->input('contenido_html');
        }

        if ($request->filled('title')) {
            $modulo->title = $request->input('title');
        } elseif ($request->filled('titulo') && isset($modulo->titulo)) {
            $modulo->titulo = $request->input('titulo');
        }

        if ($request->has('fondo_opacidad') && isset($modulo->fondo_opacidad)) {
            $modulo->fondo_opacidad = (int)$request->input('fondo_opacidad');
        }

        if (($request->input('quitar_fondo') === '1' || $request->input('quitar_fondo') === 1) && isset($modulo->fondo_imagen)) {
            $modulo->fondo_imagen = null;
        } elseif ($request->filled('fondo_imagen_recortada') && isset($modulo->fondo_imagen)) {
            $base64 = $request->input('fondo_imagen_recortada');
            if (preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
                $data = substr($base64, strpos($base64, ',') + 1);
                $decoded = base64_decode($data);
                if ($decoded !== false) {
                    $ext = strtolower($type[1]);
                    $fileName = 'fondo_' . $modulo->id . '_' . time() . '.' . $ext;
                    $relPath = 'storage/canvas_resources/' . $fileName;
                    Storage::disk('public')->put('canvas_resources/' . $fileName, $decoded);
                    $modulo->fondo_imagen = $relPath;
                }
            }
        }

        $modulo->save();

        return response()->json([
            'success'     => true,
            'message'     => 'Estado del lienzo guardado exitosamente.',
            'data'        => $modulo,
            'preview_url' => route('sisig.aprendiz.induccion.show', $modulo->id),
        ], 200);
    }

    /**
     * Valida el archivo de imagen, lo almacena en el disco público y devuelve
     * una respuesta JSON con la URL pública generada mediante Storage::url().
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadMedia(Request $request): JsonResponse
    {
        // 1. Validar archivo de imagen
        $request->validate([
            'image' => 'required|image|mimes:png,jpg,jpeg,svg,webp|max:10240',
        ]);

        // 2. Almacenar usando $request->file('image')->store('canvas_resources', 'public')
        $path = $request->file('image')->store('canvas_resources', 'public');

        // 3. Retornar respuesta JSON con la URL pública generada mediante Storage::url()
        return response()->json([
            'success'  => true,
            'url'      => Storage::url($path),
            'path'     => $path,
            'filename' => basename($path),
        ], 201);
    }

    /**
     * Retorna la lista de medios multimedia existentes (imágenes subidas y enlaces de videos).
     *
     * @return JsonResponse
     */
    public function getMedia(): JsonResponse
    {
        $media = [];

        // 1. Obtener imágenes almacenadas en public/canvas_resources
        try {
            $files = Storage::disk('public')->files('canvas_resources');
            foreach ($files as $file) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($ext, ['png', 'jpg', 'jpeg', 'svg', 'webp', 'gif'])) {
                    $media[] = [
                        'id'        => md5($file),
                        'url'       => Storage::url($file),
                        'filename'  => basename($file),
                        'title'     => basename($file),
                        'type'      => 'image',
                        'time'      => Storage::disk('public')->lastModified($file) ?: time(),
                    ];
                }
            }
        } catch (\Throwable $e) {
            // Continúa con videos si el disco falla
        }

        // 2. Obtener videos registrados en videos.json
        try {
            if (Storage::disk('local')->exists('canvas_resources/videos.json')) {
                $rawVideos = Storage::disk('local')->get('canvas_resources/videos.json');
                $videos = json_decode($rawVideos, true);
                if (is_array($videos)) {
                    foreach ($videos as $v) {
                        $media[] = [
                            'id'         => $v['id'] ?? uniqid('vid_'),
                            'url'        => $v['url'] ?? '',
                            'filename'   => $v['title'] ?? 'Video',
                            'title'      => $v['title'] ?? 'Video Multimedia',
                            'type'       => 'video',
                            'provider'   => $v['provider'] ?? 'youtube',
                            'thumbnail'  => $v['thumbnail'] ?? '',
                            'embed_url'  => $v['embed_url'] ?? ($v['url'] ?? ''),
                            'time'       => $v['time'] ?? ($v['created_at'] ?? time()),
                        ];
                    }
                }
            }
        } catch (\Throwable $e) {
            // Continúa
        }

        // 3. Ordenar cronológicamente descendente (más recientes primero)
        usort($media, function ($a, $b) {
            return ($b['time'] ?? 0) <=> ($a['time'] ?? 0);
        });

        return response()->json([
            'success' => true,
            'media'   => array_values($media),
            'count'   => count($media)
        ], 200);
    }

    /**
     * Registra un enlace de video externo (YouTube, Vimeo, MP4 directo) en el repositorio multimedia.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function saveVideoLink(Request $request): JsonResponse
    {
        $request->validate([
            'url'   => 'required|string',
            'title' => 'nullable|string|max:255',
        ]);

        $url = trim($request->input('url'));
        $title = trim($request->input('title') ?? '');
        $provider = 'generic';
        $embedUrl = $url;
        $thumbnail = '';

        // Detección de YouTube (Watch, Share youtu.be, Embed, Shorts)
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/|youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/i', $url, $matches)) {
            $provider = 'youtube';
            $ytId = $matches[1];
            $thumbnail = "https://img.youtube.com/vi/{$ytId}/mqdefault.jpg";
            $embedUrl = "https://www.youtube-nocookie.com/embed/{$ytId}?autoplay=1&rel=0";
            if (empty($title)) {
                $title = 'Video YouTube';
            }
        }
        // Detección de Vimeo
        elseif (preg_match('/(?:vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+))/i', $url, $matches)) {
            $provider = 'vimeo';
            $vimeoId = end($matches);
            $thumbnail = "https://vumbnail.com/{$vimeoId}.jpg";
            $embedUrl = "https://player.vimeo.com/video/{$vimeoId}?autoplay=1";
            if (empty($title)) {
                $title = 'Video Vimeo';
            }
        }
        // Archivo directo de video MP4 / WebM / OGG
        elseif (preg_match('/\.(mp4|webm|ogg)($|\?)/i', $url)) {
            $provider = 'mp4';
            $thumbnail = '';
            $embedUrl = $url;
            if (empty($title)) {
                $title = 'Video MP4';
            }
        } else {
            $provider = 'generic';
            if (empty($title)) {
                $title = 'Video Multimedia';
            }
        }

        $videoItem = [
            'id'         => 'vid_' . time() . '_' . substr(md5($url), 0, 6),
            'url'        => $url,
            'title'      => $title,
            'provider'   => $provider,
            'thumbnail'  => $thumbnail,
            'embed_url'  => $embedUrl,
            'type'       => 'video',
            'time'       => time(),
            'created_at' => time(),
        ];

        // Guardar en canvas_resources/videos.json
        $existingVideos = [];
        if (Storage::disk('local')->exists('canvas_resources/videos.json')) {
            $raw = Storage::disk('local')->get('canvas_resources/videos.json');
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                $existingVideos = $decoded;
            }
        }

        // Agregar al inicio
        array_unshift($existingVideos, $videoItem);

        Storage::disk('local')->put('canvas_resources/videos.json', json_encode($existingVideos, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return response()->json([
            'success' => true,
            'message' => 'Video agregado exitosamente a la biblioteca.',
            'video'   => $videoItem,
        ], 201);
    }

    /**
     * Elimina un recurso multimedia (imagen o enlace de video).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteMedia(Request $request): JsonResponse
    {
        $type = $request->input('type');
        $id = $request->input('id');
        $filename = $request->input('filename');

        if ($type === 'video') {
            if (Storage::disk('local')->exists('canvas_resources/videos.json')) {
                $raw = Storage::disk('local')->get('canvas_resources/videos.json');
                $videos = json_decode($raw, true) ?: [];
                $videos = array_values(array_filter($videos, fn($v) => ($v['id'] ?? '') !== $id));
                Storage::disk('local')->put('canvas_resources/videos.json', json_encode($videos, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            }
            return response()->json(['success' => true, 'message' => 'Video eliminado de la galería.']);
        }

        if ($filename) {
            $cleanName = basename($filename);
            Storage::disk('public')->delete('canvas_resources/' . $cleanName);
            return response()->json(['success' => true, 'message' => 'Imagen eliminada de la galería.']);
        }

        return response()->json(['success' => false, 'message' => 'Parámetros insuficientes.'], 400);
    }

    /**
     * Retorna el estado actual del lienzo de un módulo.
     *
     * @param int|string $id
     * @return JsonResponse
     */
    public function getState($id): JsonResponse
    {
        $modulo = Modulo::find($id) ?? InductionModule::find($id);
        if (!$modulo) {
            return response()->json([
                'success' => false,
                'message' => "Módulo con ID {$id} no encontrado."
            ], 404);
        }

        return response()->json([
            'success'      => true,
            'id'           => $modulo->id,
            'title'        => $modulo->title ?? $modulo->titulo,
            'canvas_state' => $modulo->canvas_state ?? [],
            'updated_at'   => $modulo->updated_at?->toIso8601String(),
        ], 200);
    }
}
