<?php

namespace Modules\SISIG\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Muestra el panel de control principal del Administrador SISIG.
     */
    public function index()
    {
        // Verificar que el usuario tenga el rol de admin_sisig o superadministrador
        if (!Auth::user()->hasRole('admin_sisig')) {
            abort(403, 'No cuentas con permisos de Administrador SISIG para acceder a esta sección.');
        }

        // Métricas de aprendices
        $totalAprendices = DB::table('apprentices')->count();

        // Métricas de evaluaciones y quices
        $totalQuices = DB::table('sisig_quices')->count();
        $quicesActivos = DB::table('sisig_quices')->where('activo', 1)->count();

        // Métricas de resultados
        $totalEvaluaciones = DB::table('sisig_resultados')->count();
        $evaluacionesAprobadas = DB::table('sisig_resultados')->where('aprobado', 1)->count();
        $evaluacionesReprobadas = DB::table('sisig_resultados')->where('aprobado', 0)->count();
        $promedioPuntaje = round((float) (DB::table('sisig_resultados')->avg('puntaje') ?? 0), 1);
        
        $tasaAprobacion = $totalEvaluaciones > 0
            ? round(($evaluacionesAprobadas / $totalEvaluaciones) * 100, 1)
            : 0;

        // Métricas de seguridad e infracciones
        $totalInfracciones = DB::table('infraccionesquiz')->count();
        $evaluacionesAnuladas = DB::table('sisig_resultados')->where('fue_anulado', 1)->count();

        // Secciones temáticas del SIG (SST, Calidad, Ambiental)
        $secciones = DB::table('sisig_secciones')
            ->orderBy('orden')
            ->get();

        // Últimos exámenes presentados por aprendices
        $ultimosResultados = DB::table('sisig_resultados')
            ->join('users', 'sisig_resultados.user_id', '=', 'users.id')
            ->leftJoin('people', 'users.person_id', '=', 'people.id')
            ->join('sisig_quices', 'sisig_resultados.id_quiz', '=', 'sisig_quices.id_quiz')
            ->select(
                'sisig_resultados.*',
                'users.name as user_name',
                'users.nickname',
                'people.document_number',
                'sisig_quices.titulo as quiz_titulo'
            )
            ->orderByDesc('sisig_resultados.created_at')
            ->limit(7)
            ->get();

        // Registro de actividad reciente en el módulo
        $actividadReciente = DB::table('actividadreciente')
            ->join('users', 'actividadreciente.user_id', '=', 'users.id')
            ->select('actividadreciente.*', 'users.name as user_name')
            ->orderByDesc('actividadreciente.created_at')
            ->limit(6)
            ->get();

        return view('sisig::admin.dashboard', compact(
            'totalAprendices',
            'totalQuices',
            'quicesActivos',
            'totalEvaluaciones',
            'evaluacionesAprobadas',
            'evaluacionesReprobadas',
            'promedioPuntaje',
            'tasaAprobacion',
            'totalInfracciones',
            'evaluacionesAnuladas',
            'secciones',
            'ultimosResultados',
            'actividadReciente'
        ));
    }
}
