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
        $totalModulos = DB::table('sisig_modulos')->count();
        $totalQuices = DB::table('sisig_quices')->count();
        $quicesActivos = DB::table('sisig_quices')->where('activo', 1)->count();
        $totalPreguntas = DB::table('sisig_preguntas')->count();
        $totalContenidos = $totalModulos;

        // 2. Módulos asignados o existentes con detalle de quices
        $modulos = DB::table('sisig_modulos')
            ->get()
            ->map(function ($mod) {
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
                    'contenidos_count' => !empty($mod->contenido_html) ? 1 : 0,
                    'quiz' => $quiz,
                    'preguntas_count' => $preguntasCount,
                    'meta' => $this->getSeccionMeta($mod->titulo, $mod->id),
                ];
            });

        return view('sisig::editor.DashboardEditor', compact(
            'user',
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