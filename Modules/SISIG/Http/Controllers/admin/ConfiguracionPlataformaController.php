<?php

namespace Modules\SISIG\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Modules\SISIG\Entities\Modulo;
use Modules\SISIG\Entities\PlataformaConfiguracion;

class ConfiguracionPlataformaController extends Controller
{
    /**
     * Muestra la vista integral de configuración general de la plataforma SISIG,
     * incluyendo la consulta dinámica de módulos y editores.
     */
    public function index(Request $request)
    {
        $this->verificarPermisoAdmin();

        // 1. Obtener la configuración global de la plataforma
        $configuracion = PlataformaConfiguracion::getActual();

        // 2. Obtener usuarios con rol de editor (editor_sisig)
        $editores = User::whereHas('roles', function ($query) {
            $query->whereIn('slug', ['editor_sisig', 'sisig.editor']);
        })
        ->with(['person', 'modulosEditables'])
        ->orderBy('name')
        ->get();

        // 3. Determinar el editor seleccionado (por parámetro URL o el primero disponible)
        $selectedEditorId = $request->input('editor_id', $editores->first()?->id);
        $editorSeleccionado = $editores->firstWhere('id', $selectedEditorId);

        // 4. CONSULTA DINÁMICA: Cargar todos los módulos registrados en la BD (Sin nombres quemados)
        $modulos = Modulo::orderBy('titulo')->get();

        // 5. IDs de módulos que ya tiene asignados el editor seleccionado
        $modulosAsignados = $editorSeleccionado
            ? $editorSeleccionado->modulosEditables->pluck('id')->toArray()
            : [];

        // 6. Mapa completo de permisos por cada editor para alternancia 100% reactiva en el cliente (0 recargas)
        $mapaPermisosEditores = $editores->mapWithKeys(function ($ed) {
            return [$ed->id => $ed->modulosEditables->pluck('id')->toArray()];
        });

        return view('sisig::admin.ConfiguracionPlataforma', compact(
            'configuracion',
            'editores',
            'editorSeleccionado',
            'modulos',
            'modulosAsignados',
            'mapaPermisosEditores'
        ));
    }

    /**
     * Procesa la actualización de identidad, textos legales, restricciones
     * y sincronización dinámica (sync) de módulos asignados al editor.
     * Soporta tanto peticiones AJAX como envío de formulario tradicional.
     */
    public function update(Request $request)
    {
        $this->verificarPermisoAdmin();

        $request->validate([
            'nombre_plataforma'                    => 'required|string|max:150',
            'logo'                                 => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'terminos_condiciones'                 => 'nullable|string',
            'politicas_privacidad'                 => 'nullable|string',
            'editor_bloquear_edicion_critica'      => 'nullable|boolean',
            'editor_limitar_creacion_modulos'      => 'nullable|boolean',
            'editor_restringir_eliminacion_quices' => 'nullable|boolean',
            'editor_requerir_aprobacion'           => 'nullable|boolean',
            'editor_id'                            => 'nullable|exists:users,id',
            'modulos_asignados'                    => 'nullable|array',
            'modulos_asignados.*'                  => 'integer|exists:sisig_modulos,id',
            'permisos_editores_json'               => 'nullable|string',
        ]);

        $config = PlataformaConfiguracion::getActual();

        // 1. Manejo del Logotipo Institucional
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $filename = 'logo_sisig_' . time() . '_' . uniqid() . '.' . $extension;
            $destinationDir = public_path('modules/sisig/images/logo');

            if (!File::isDirectory($destinationDir)) {
                File::makeDirectory($destinationDir, 0755, true);
            }

            // Eliminar logo anterior si no es el default
            if ($config->logo_path && !str_contains($config->logo_path, 'logo.png')) {
                $oldPath = public_path($config->logo_path);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $file->move($destinationDir, $filename);
            $config->logo_path = 'modules/sisig/images/logo/' . $filename;
        }

        // 2. Asignación de Identidad y Textos Legales
        $config->nombre_plataforma = $request->input('nombre_plataforma');
        $config->terminos_condiciones = $request->input('terminos_condiciones');
        $config->politicas_privacidad = $request->input('politicas_privacidad');

        // 3. Restricciones operativas para el rol editor_sisig
        $config->editor_bloquear_edicion_critica = $request->has('editor_bloquear_edicion_critica');
        $config->editor_limitar_creacion_modulos = $request->has('editor_limitar_creacion_modulos');
        $config->editor_restringir_eliminacion_quices = $request->has('editor_restringir_eliminacion_quices');
        $config->editor_requerir_aprobacion = $request->has('editor_requerir_aprobacion');

        $config->updated_by = Auth::id();
        $config->save();

        // 4. SINCRONIZACIÓN DINÁMICA DE MÓDULOS DE EDITORES
        // Si se envió el mapa completo serializado en JSON (permite guardar cambios en múltiples editores a la vez)
        if ($request->filled('permisos_editores_json')) {
            $todosPermisos = json_decode($request->input('permisos_editores_json'), true);
            if (is_array($todosPermisos)) {
                foreach ($todosPermisos as $edId => $modIds) {
                    $ed = User::find($edId);
                    if ($ed && is_array($modIds)) {
                        $ed->modulosEditables()->sync($modIds);
                    }
                }
            }
        } elseif ($request->filled('editor_id')) {
            $editor = User::find($request->input('editor_id'));
            if ($editor) {
                $modulosIds = $request->input('modulos_asignados', []);
                $editor->modulosEditables()->sync($modulosIds);
            }
        }

        // 5. Registro de Auditoría Administrativa
        try {
            DB::table('actividadreciente')->insert([
                'user_id'     => Auth::id(),
                'tipo_evento' => 'Configuración',
                'mensaje'     => 'Actualizó la configuración global de la plataforma y asignaciones de módulos a editores.',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        } catch (\Throwable $e) {
            // Ignorar en caso de indisponibilidad
        }

        // Si es una petición AJAX, responder JSON limpio para evitar recarga de página
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'  => true,
                'message'  => '¡Configuración de la plataforma y asignación de módulos actualizadas exitosamente!',
                'logo_url' => asset($config->logo_path),
            ]);
        }

        $redirectParams = $request->filled('editor_id') ? ['editor_id' => $request->input('editor_id')] : [];

        return redirect()->route('sisig.admin.configuracion.index', $redirectParams)
            ->with('success', '¡Configuración de la plataforma y asignación de módulos actualizadas exitosamente!');
    }

    /**
     * Valida de manera estricta que solo el Administrador SISIG pueda acceder o modificar.
     */
    private function verificarPermisoAdmin(): void
    {
        if (!Auth::check() || !Auth::user()->hasRole('admin_sisig')) {
            abort(403, 'Acceso Denegado: Se requiere rol de Administrador SISIG.');
        }
    }
}
