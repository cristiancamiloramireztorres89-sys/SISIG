<?php

namespace Modules\SISIG\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class DashboardControllerEditor extends Controller
{
    /**
     * Muestra el panel principal del Editor de Contenidos en SISIG.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Métricas generales de contenidos y evaluaciones
        $totalModulos = DB::table('sisig_secciones')->count();
        $totalContenidos = DB::table('sisig_contenidos')->count();
        $totalQuices = DB::table('sisig_quices')->count();
        $quicesActivos = DB::table('sisig_quices')->where('activo', 1)->count();
        $totalPreguntas = DB::table('sisig_preguntas')->count();

        // 2. Módulos con detalle de sus contenidos y quices
        $modulos = DB::table('sisig_secciones')
            ->orderBy('orden')
            ->get()
            ->map(function ($sec) {
                $contenidosCount = DB::table('sisig_contenidos')
                    ->where('id_seccion', $sec->id_seccion)
                    ->count();

                $quiz = DB::table('sisig_quices')
                    ->where('id_seccion', $sec->id_seccion)
                    ->first();

                $preguntasCount = $quiz 
                    ? DB::table('sisig_preguntas')->where('id_quiz', $quiz->id_quiz)->count() 
                    : 0;

                return (object) [
                    'id_seccion' => $sec->id_seccion,
                    'nombre' => $sec->nombre,
                    'descripcion' => $sec->descripcion,
                    'orden' => $sec->orden,
                    'contenidos_count' => $contenidosCount,
                    'quiz' => $quiz,
                    'preguntas_count' => $preguntasCount,
                    'meta' => $this->getSeccionMeta($sec->nombre, $sec->orden),
                ];
            });

        // 3. Actividad reciente registrada en el módulo
        $actividadReciente = DB::table('actividadreciente')
            ->join('users', 'actividadreciente.user_id', '=', 'users.id')
            ->select('actividadreciente.*', 'users.name as user_name')
            ->orderByDesc('actividadreciente.created_at')
            ->limit(5)
            ->get();

        return view('sisig::editor.DashboardEditor', compact(
            'user',
            'totalModulos',
            'totalContenidos',
            'totalQuices',
            'quicesActivos',
            'totalPreguntas',
            'modulos',
            'actividadReciente'
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
