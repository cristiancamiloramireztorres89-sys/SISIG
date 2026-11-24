<?php

namespace Modules\SISIG\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Modules\SISIG\Entities\Modulo;

class DashboardControllerEditor extends Controller
{
    /**
     * Muestra el panel principal del Editor de Contenidos en SISIG.
     * Muestra únicamente los módulos asignados al editor actual (o todos si es Administrador/Superadmin).
     */
    public function index()
    {
        if (!Auth::check()) {
            abort(403, 'Acceso denegado: Usuario no autenticado.');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasRole('editor_sisig') && !$user->hasRole('admin_sisig') && !$user->hasRole('sisig.admin') && !$user->hasRole('superadmin')) {
            abort(403, 'Acceso denegado: Se requieren privilegios de Editor de Contenidos o Administrador SISIG.');
        }

        $isAdmin = $user->hasRole('admin_sisig') || $user->hasRole('sisig.admin') || $user->hasRole('superadmin');

        // 1. Obtener módulos según permisos: todos para admin/superadmin, o únicamente los asignados para editor_sisig
        if ($isAdmin) {
            $modulosBase = Modulo::orderBy('id', 'asc')->get();
        } else {
            $modulosBase = $user->modulosEditables()->orderBy('sisig_modulos.id', 'asc')->get();
        }

        $moduloIds = $modulosBase->pluck('id')->toArray();

        // 2. Métricas editoriales acordes al ámbito del usuario autenticado
        $totalModulos = $modulosBase->count();
        $totalContenidos = $modulosBase->filter(function ($m) {
            return !empty($m->contenido_html) || !empty($m->canvas_state);
        })->count();

        $quicesQuery = DB::table('sisig_quices');
        if (!$isAdmin) {
            $quicesQuery->whereIn('modulo_id', $moduloIds);
        }
        $totalQuices = (!empty($moduloIds) || $isAdmin) ? (clone $quicesQuery)->count() : 0;
        $quicesActivos = (!empty($moduloIds) || $isAdmin) ? (clone $quicesQuery)->where('activo', 1)->count() : 0;

        $preguntasQuery = DB::table('sisig_preguntas');
        if (!$isAdmin) {
            $quizIdsPermitidos = (!empty($moduloIds)) ? (clone $quicesQuery)->pluck('id_quiz')->toArray() : [];
            $totalPreguntas = !empty($quizIdsPermitidos) 
                ? $preguntasQuery->whereIn('id_quiz', $quizIdsPermitidos)->count() 
                : 0;
        } else {
            $totalPreguntas = $preguntasQuery->count();
        }

        // 3. Mapear los módulos permitidos con su información detallada y logos reales
        $modulos = $modulosBase->map(function ($mod) {
            $quiz = DB::table('sisig_quices')
                ->where('modulo_id', $mod->id)
                ->first();

            $preguntasCount = $quiz 
                ? DB::table('sisig_preguntas')->where('id_quiz', $quiz->id_quiz)->count() 
                : 0;

            return (object) [
                'id_seccion' => $mod->id,
                'id' => $mod->id,
                'nombre' => $mod->titulo,
                'titulo' => $mod->titulo,
                'descripcion' => $mod->descripcion,
                'orden' => $mod->id,
                'imagen' => $mod->imagen ?? null,
                'fondo' => $mod->fondo ?? null,
                'contenidos_count' => (!empty($mod->contenido_html) || !empty($mod->canvas_state)) ? 1 : 0,
                'quiz' => $quiz,
                'preguntas_count' => $preguntasCount,
                'meta' => $this->getSeccionMeta($mod->titulo, $mod->id),
            ];
        });

        return view('sisig::editor.DashboardEditor', compact(
            'user',
            'isAdmin',
            'totalModulos',
            'totalContenidos',
            'totalQuices',
            'quicesActivos',
            'totalPreguntas',
            'modulos'
        ));
    }

    /**
     * Devuelve configuración visual por eje o módulo formativo.
     */
    private function getSeccionMeta(string $nombre, int $orden): array
    {
        $nombreLower = mb_strtolower($nombre);

        if (str_contains($nombreLower, 'seguridad') || str_contains($nombreLower, 'sst') || str_contains($nombreLower, 'salud')) {
            return [
                'icono' => 'fas fa-hard-hat',
                'color' => 'text-emerald-500',
                'bg' => 'bg-emerald-50',
                'border' => 'border-emerald-200',
                'badge' => 'SST • Seguridad',
            ];
        }

        if (str_contains($nombreLower, 'calidad')) {
            return [
                'icono' => 'fas fa-award',
                'color' => 'text-sky-500',
                'bg' => 'bg-sky-50',
                'border' => 'border-sky-200',
                'badge' => 'Gestión de Calidad',
            ];
        }

        if (str_contains($nombreLower, 'ambient') || str_contains($nombreLower, 'medio')) {
            return [
                'icono' => 'fas fa-leaf',
                'color' => 'text-amber-500',
                'bg' => 'bg-amber-50',
                'border' => 'border-amber-200',
                'badge' => 'Gestión Ambiental',
            ];
        }

        return [
            'icono' => 'fas fa-book-open',
            'color' => 'text-teal-500',
            'bg' => 'bg-teal-50',
            'border' => 'border-teal-200',
            'badge' => 'Módulo #' . $orden,
        ];
    }
}