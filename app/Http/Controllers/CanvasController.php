<?php

namespace App\Http\Controllers;

use App\Models\InductionModule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CanvasController extends Controller
{
    /**
     * Muestra la vista del editor visual interactivo (Canvas / Fabric.js).
     *
     * @param int|string $id
     * @return \Illuminate\View\View
     */
    public function showEditor($id = 1)
    {
        $module = InductionModule::find($id);
        if (!$module) {
            $module = InductionModule::firstOrCreate(
                ['id' => $id],
                ['title' => 'Módulo de Inducción ' . $id, 'canvas_state' => null]
            );
        }

        return view('sisig::editor.canvas.editor', compact('module'));
    }

    /**
     * Valida, extrae el payload JSON y actualiza el estado del módulo.
     *
     * @param Request $request
     * @param int|string $id
     * @return JsonResponse
     */
    public function saveState(Request $request, $id): JsonResponse
    {
        // 1. Validar la petición
        $request->validate([
            'canvas_state' => 'required',
            'title'        => 'nullable|string|max:255',
        ]);

        // 2. Buscar el registro del módulo
        $module = \Modules\SISIG\Entities\Modulo::find($id) ?? InductionModule::find($id);
        if (!$module) {
            $module = InductionModule::findOrFail($id);
        }

        // 3. Extraer el payload JSON del estado del lienzo
        $canvasState = $request->input('canvas_state');
        if (is_string($canvasState)) {
            $decoded = json_decode($canvasState, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $canvasState = $decoded;
            }
        }

        // 4. Actualizar el registro del módulo
        $module->canvas_state = $canvasState;

        if ($request->filled('title')) {
            $module->title = $request->input('title');
        }

        $module->save();

        return response()->json([
            'success' => true,
            'message' => 'Estado del lienzo guardado exitosamente.',
            'data'    => $module,
        ], 200);
    }

    /**
     * Valida y almacena un archivo de imagen en el disco público,
     * retornando la URL pública generada mediante Storage::url().
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadMedia(Request $request): JsonResponse
    {
        // 1. Validar el archivo de imagen
        $request->validate([
            'image' => 'required|image|mimes:png,jpg,jpeg,svg,webp|max:10240',
        ]);

        // 2. Almacenar usando el disco público en el directorio canvas_resources
        $path = $request->file('image')->store('canvas_resources', 'public');

        // 3. Retornar respuesta JSON con la URL pública generada mediante Storage::url()
        return response()->json([
            'success' => true,
            'url'     => Storage::url($path),
            'path'    => $path,
        ], 201);
    }

    /**
     * Retorna el estado actual del lienzo de un módulo.
     *
     * @param int|string $id
     * @return JsonResponse
     */
    public function getState($id): JsonResponse
    {
        $module = InductionModule::findOrFail($id);

        return response()->json([
            'success'      => true,
            'id'           => $module->id,
            'title'        => $module->title,
            'canvas_state' => $module->canvas_state ?? [],
            'updated_at'   => $module->updated_at?->toIso8601String(),
        ], 200);
    }
}
