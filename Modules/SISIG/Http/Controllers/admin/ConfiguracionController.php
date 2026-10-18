<?php

namespace Modules\SISIG\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Modules\SISIG\Entities\Configuracion;

class ConfiguracionController extends Controller
{
    /**
     * Muestra el panel de Configuración de la Plataforma SISIG.
     */
    public function index()
    {
        // Obtener todas las configuraciones agrupadas
        $configuraciones = Configuracion::getGrouped();

        // Si la tabla estuviese vacía por alguna razón, usar colección vacía
        $identidadVisual = $configuraciones->get('identidad_visual', collect());
        $restriccionesEditor = $configuraciones->get('restricciones_editor', collect());
        $politicasSistema = $configuraciones->get('politicas_sistema', collect());

        // Métricas de estado para las tarjetas superiores
        $totalConfiguraciones = Configuracion::count();
        $restriccionesActivas = Configuracion::where('grupo', 'restricciones_editor')
            ->where('tipo', 'boolean')
            ->where('valor', '1')
            ->count();
        $mantenimientoActivo = Configuracion::get('mensaje_mantenimiento_activo', false);

        // Obtener usuarios con rol de Editor en el sistema SISIG
        $editores = \App\Models\User::whereHas('roles', function ($q) {
            $q->whereIn('slug', ['editor_sisig', 'sisig.editor']);
        })->with(['person', 'roles', 'modulosEditables'])->get();

        // Obtener módulos del sistema con conteo de editores y quices
        $modulos = \Modules\SISIG\Entities\Modulo::with(['editores', 'quices'])->get();

        return view('sisig::admin.ConfiguracionPlataforma', compact(
            'identidadVisual',
            'restriccionesEditor',
            'politicasSistema',
            'totalConfiguraciones',
            'restriccionesActivas',
            'mantenimientoActivo',
            'editores',
            'modulos'
        ));
    }

    /**
     * Actualiza las configuraciones de la plataforma.
     */
    public function update(Request $request)
    {
        $grupo = $request->input('_grupo', 'todos');

        // Validaciones básicas según el grupo
        $rules = [
            'app_name' => 'nullable|string|max:100',
            'app_slogan' => 'nullable|string|max:255',
            'app_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:3072',
            'app_favicon' => 'nullable|mimes:ico,png,svg|max:1024',
            'color_primario' => 'nullable|regex:/^#[a-fA-F0-9]{6}$/',
            'color_sidebar' => 'nullable|regex:/^#[a-fA-F0-9]{6}$/',
            'editor_limite_quizzes_por_modulo' => 'nullable|integer|min:1|max:100',
            'examen_tiempo_defecto' => 'nullable|integer|min:5|max:300',
            'examen_advertencias_maximas' => 'nullable|integer|min:1|max:10',
            'mensaje_mantenimiento_texto' => 'nullable|string|max:500',
        ];

        $request->validate($rules);

        // Obtener todas las configuraciones existentes
        $configsQuery = Configuracion::query();
        if ($grupo !== 'todos') {
            $configsQuery->where('grupo', $grupo);
        }
        $configs = $configsQuery->get();

        foreach ($configs as $config) {
            $clave = $config->clave;

            // Manejo de archivos (Logos, favicons)
            if ($config->tipo === 'file') {
                if ($request->hasFile($clave)) {
                    $file = $request->file($clave);
                    $destPath = public_path('modules/sisig/uploads/branding');

                    if (!File::exists($destPath)) {
                        File::makeDirectory($destPath, 0755, true);
                    }

                    // Eliminar archivo anterior si existía y no era el default
                    if (!empty($config->valor) && File::exists(public_path($config->valor))) {
                        File::delete(public_path($config->valor));
                    }

                    $filename = time() . '_' . $clave . '.' . $file->getClientOriginalExtension();
                    $file->move($destPath, $filename);
                    $config->valor = 'modules/sisig/uploads/branding/' . $filename;
                    $config->save();
                }
                continue;
            }

            // Manejo de booleanos (switches/checkboxes)
            if ($config->tipo === 'boolean') {
                $config->valor = $request->has($clave) ? '1' : '0';
                $config->save();
                continue;
            }

            // Manejo de valores de texto, color, número
            if ($request->has($clave)) {
                $config->valor = $request->input($clave);
                $config->save();
            }
        }

        // Sincronización de asignaciones de módulos si se envían desde la ventana modal de restricciones
        if ($request->has('modulos_editor') || $request->has('editor_id')) {
            $editorId = $request->input('editor_id');
            $user = $editorId ? \App\Models\User::find($editorId) : null;
            if (!$user) {
                $user = \App\Models\User::whereHas('roles', fn($q) => $q->whereIn('slug', ['editor_sisig', 'sisig.editor']))->first();
            }
            if ($user) {
                $user->modulosEditables()->sync($request->input('modulos_editor', []));
            }
        } elseif ($request->has('asignaciones_editores')) {
            foreach ($request->input('asignaciones_editores', []) as $userId => $modulosIds) {
                $user = \App\Models\User::find($userId);
                if ($user) {
                    $user->modulosEditables()->sync(is_array($modulosIds) ? $modulosIds : []);
                }
            }
        }

        // Limpiar caché para que los cambios tengan efecto inmediato
        Configuracion::clearCache();

        return redirect()
            ->route('sisig.admin.configuracion.index')
            ->with('success', 'Configuraciones de la plataforma actualizadas exitosamente.');
    }

    /**
     * Restablece el logotipo o favicon por defecto.
     */
    public function removeBrandingFile(Request $request, string $clave)
    {
        $validKeys = ['app_logo', 'app_favicon'];
        if (!in_array($clave, $validKeys)) {
            return redirect()->back()->with('error', 'Clave no permitida.');
        }

        $config = Configuracion::where('clave', $clave)->first();
        if ($config && !empty($config->valor)) {
            if (File::exists(public_path($config->valor))) {
                File::delete(public_path($config->valor));
            }
            $config->valor = null;
            $config->save();
            Configuracion::clearCache();
        }

        return redirect()->back()->with('success', 'Elemento visual restablecido al valor original.');
    }

    /**
     * Restablece todas las configuraciones a sus valores originales de fábrica.
     */
    public function resetDefaults()
    {
        $defaults = [
            'app_name' => 'SISIG',
            'app_slogan' => 'Sistema Integral de Seguimiento e Inducción a la Gestión',
            'app_logo' => null,
            'app_favicon' => null,
            'color_primario' => '#39A900',
            'color_sidebar' => '#001A29',
            'editor_puede_eliminar_quizzes' => '0',
            'editor_puede_editar_quizzes_con_respuestas' => '0',
            'editor_requiere_aprobacion_publicacion' => '0',
            'editor_limite_quizzes_por_modulo' => '10',
            'examen_tiempo_defecto' => '30',
            'examen_advertencias_maximas' => '3',
            'examen_mostrar_resultados_inmediatos' => '1',
            'mensaje_mantenimiento_activo' => '0',
            'mensaje_mantenimiento_texto' => 'Atención: La plataforma entrará en mantenimiento preventivo. Por favor guarda tus actividades.',
        ];

        foreach ($defaults as $clave => $valor) {
            Configuracion::where('clave', $clave)->update(['valor' => $valor]);
        }

        // Limpiar archivos personalizados de branding si existen
        $brandingDir = public_path('modules/sisig/uploads/branding');
        if (File::exists($brandingDir)) {
            File::cleanDirectory($brandingDir);
        }

        Configuracion::clearCache();

        return redirect()
            ->route('sisig.admin.configuracion.index')
            ->with('success', 'Todas las configuraciones y colores han sido restablecidos a los valores por defecto del sistema.');
    }
}
