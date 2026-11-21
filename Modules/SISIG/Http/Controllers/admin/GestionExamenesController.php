<?php

namespace Modules\SISIG\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\SISIG\Entities\Quiz;
use Modules\SISIG\Entities\Modulo;

class GestionExamenesController extends Controller
{
    /**
     * Prefijo de rutas para las redirecciones del panel de administración.
     */
    protected string $routePrefix = 'sisig.admin';

    /**
     * Verifica la autorización del usuario actual (sobreescribible en controladores derivados).
     */
    protected function checkAuthorization(): void
    {
        if (!Auth::check() || (!Auth::user()->hasRole('admin_sisig') && !Auth::user()->hasRole('sisig.admin') && !Auth::user()->hasRole('superadmin'))) {
            abort(403, 'Acceso denegado: Se requieren privilegios de Administrador SISIG.');
        }
    }

    /**
     * Obtiene la colección de módulos a los que el rol actual tiene acceso.
     */
    protected function getModulosPermitidos()
    {
        return Modulo::all();
    }

    /**
     * Obtiene un arreglo con los IDs de los módulos permitidos.
     */
    protected function getModulosIdsPermitidos()
    {
        return $this->getModulosPermitidos()->pluck('id')->toArray();
    }

    /**
     * Construye la consulta base de exámenes con su módulo asociado.
     */
    protected function getQuizQuery()
    {
        return Quiz::with('modulo')->whereIn('modulo_id', $this->getModulosIdsPermitidos());
    }

    /**
     * Busca un examen dentro del alcance permitido o lanza error 404.
     */
    protected function findQuizOrFail($id)
    {
        return $this->getQuizQuery()->findOrFail($id);
    }

    /**
     * Muestra el catálogo de exámenes con filtros de módulo, estado y búsqueda.
     */
    public function index(Request $request)
    {
        $this->checkAuthorization();

        // Consulta base de exámenes permitidos
        $query = $this->getQuizQuery();
        $modulosIds = $this->getModulosIdsPermitidos();

        // Filtro por módulo temático
        if ($request->has('modulo_id') && $request->modulo_id != 'all') {
            if (in_array((int)$request->modulo_id, $modulosIds, true)) {
                $query->where('modulo_id', (int)$request->modulo_id);
            }
        }
        
        // Filtro por estado activo o inactivo
        if ($request->has('estado') && $request->estado != 'all') {
            $estado = $request->estado == 'activo' ? 1 : 0;
            $query->where('activo', $estado);
        }

        // Búsqueda por coincidencia en el título del examen con comodines escapados
        if ($request->filled('buscar')) {
            $buscar = trim($request->input('buscar'));
            $escapedBuscar = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $buscar);
            $query->where('titulo', 'LIKE', '%' . $escapedBuscar . '%');
        }

        $quices = $query->get();
        $modulos = $this->getModulosPermitidos();
        $routePrefix = $this->routePrefix;

        return view('sisig::admin.GestionExamenes', compact('quices', 'modulos', 'routePrefix'));
    }

    /**
     * Registra un nuevo examen en la base de datos protegiendo contra asignación masiva.
     */
    public function store(Request $request)
    {
        $this->checkAuthorization();

        // Validación de datos y parámetros del examen
        $modulosIds = $this->getModulosIdsPermitidos();

        $validated = $request->validate([
            'titulo'          => 'required|string|max:100',
            'modulo_id'       => 'required|in:' . implode(',', $modulosIds),
            'activo'          => 'required|boolean',
            'numero_intentos' => 'required|integer|min:1|max:10',
            'puntaje_minimo'  => 'required|numeric|min:0|max:100',
            'tiempo_minutos'  => 'required|integer|min:1|max:300',
            'fecha_limite'    => 'nullable|date'
        ]);

        // Crear el examen únicamente con campos explícitamente autorizados
        Quiz::create([
            'titulo'          => trim($validated['titulo']),
            'modulo_id'       => $validated['modulo_id'],
            'activo'          => (bool)$validated['activo'],
            'numero_intentos' => (int)$validated['numero_intentos'],
            'puntaje_minimo'  => (float)$validated['puntaje_minimo'],
            'tiempo_minutos'  => (int)$validated['tiempo_minutos'],
            'fecha_limite'    => $validated['fecha_limite'] ?? null,
        ]);

        return redirect()->route($this->routePrefix . '.examenes.index')->with('success', 'Examen creado exitosamente.');
    }

    /**
     * Actualiza la configuración y parámetros de un examen existente.
     */
    public function update(Request $request, $id)
    {
        $this->checkAuthorization();

        // Validación de campos del examen
        $modulosIds = $this->getModulosIdsPermitidos();

        $validated = $request->validate([
            'titulo'          => 'required|string|max:100',
            'modulo_id'       => 'required|in:' . implode(',', $modulosIds),
            'activo'          => 'required|boolean',
            'numero_intentos' => 'required|integer|min:1|max:10',
            'puntaje_minimo'  => 'required|numeric|min:0|max:100',
            'tiempo_minutos'  => 'required|integer|min:1|max:300',
            'fecha_limite'    => 'nullable|date'
        ]);

        $quiz = $this->findQuizOrFail($id);
        $quiz->fill([
            'titulo'          => trim($validated['titulo']),
            'modulo_id'       => $validated['modulo_id'],
            'activo'          => (bool)$validated['activo'],
            'numero_intentos' => (int)$validated['numero_intentos'],
            'puntaje_minimo'  => (float)$validated['puntaje_minimo'],
            'tiempo_minutos'  => (int)$validated['tiempo_minutos'],
            'fecha_limite'    => $validated['fecha_limite'] ?? null,
        ]);

        // Comprobar si hubo cambios antes de guardar
        if (!$quiz->isDirty()) {
            return redirect()->route($this->routePrefix . '.examenes.index')->with('info', 'No se detectaron modificaciones en el examen.');
        }

        $quiz->save();

        return redirect()->route($this->routePrefix . '.examenes.index')->with('success', 'Examen actualizado exitosamente.');
    }

    /**
     * Alterna rápidamente el estado del examen entre activo e inactivo.
     */
    public function toggleStatus($id)
    {
        $this->checkAuthorization();

        // Obtener examen y cambiar su estado booleano
        $quiz = $this->findQuizOrFail($id);
        $quiz->activo = !$quiz->activo;
        $quiz->save();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'id_quiz' => $quiz->id_quiz,
                'activo' => (bool)$quiz->activo,
                'message' => $quiz->activo ? 'El examen ha sido activado exitosamente.' : 'El examen ha sido desactivado exitosamente.'
            ]);
        }

        return redirect()->route($this->routePrefix . '.examenes.index')->with('success', 'Estado del examen actualizado.');
    }
}
