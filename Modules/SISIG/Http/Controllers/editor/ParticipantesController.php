<?php

namespace Modules\SISIG\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ParticipantesController extends Controller
{
    /**
     * Muestra el listado de participantes (aprendices) con métricas,
     * estado de avance por módulo (Calidad, SST, Ambiental) y filtros.
     */
    public function index(Request $request)
    {
        $buscar = trim($request->input('buscar', ''));
        $filtroEstado = $request->input('estado');
        $filtroSeccion = $request->input('seccion');

        // Consulta de usuarios aprendices
        $query = User::query();

        if (method_exists(User::class, 'person')) {
            $query->with('person');
        }

        // Excluir personal administrativo y editores de la lista de aprendices participantes
        if (method_exists(User::class, 'roles')) {
            $query->whereDoesntHave('roles', function ($q) {
                $q->whereIn('slug', ['admin_sisig', 'sisig.admin', 'editor_sisig']);
            });
        }

        // Filtro de búsqueda
        if (!empty($buscar)) {
            $query->where(function ($q) use ($buscar) {
                $q->where('name', 'like', "%{$buscar}%")
                  ->orWhere('email', 'like', "%{$buscar}%")
                  ->orWhere('nickname', 'like', "%{$buscar}%");

                if (Schema::hasTable('people')) {
                    $q->orWhereHas('person', function ($qp) use ($buscar) {
                        $qp->where('first_name', 'like', "%{$buscar}%")
                           ->orWhere('first_last_name', 'like', "%{$buscar}%")
                           ->orWhere('second_last_name', 'like', "%{$buscar}%")
                           ->orWhere('document_number', 'like', "%{$buscar}%");
                    });
                }
            });
        }

        $usuarios = $query->orderBy('name')->get();

        // Identificar IDs de módulos para Calidad, SST y Ambiental
        $modulosCalidad = DB::table('sisig_modulos')
            ->where(function ($q) {
                $q->where('titulo', 'like', '%calidad%')
                  ->orWhere('norma', 'like', '%9001%');
            })->pluck('id')->toArray();

        $modulosSST = DB::table('sisig_modulos')
            ->where(function ($q) {
                $q->where('titulo', 'like', '%sst%')
                  ->orWhere('titulo', 'like', '%seguridad%')
                  ->orWhere('titulo', 'like', '%salud%')
                  ->orWhere('norma', 'like', '%45001%');
            })->pluck('id')->toArray();

        $modulosAmbiental = DB::table('sisig_modulos')
            ->where(function ($q) {
                $q->where('titulo', 'like', '%ambiental%')
                  ->orWhere('titulo', 'like', '%ambiente%')
                  ->orWhere('norma', 'like', '%14001%');
            })->pluck('id')->toArray();

        // Identificar quices asociados a cada sección
        $quicesCalidad = DB::table('sisig_quices')->whereIn('modulo_id', $modulosCalidad)->pluck('id_quiz')->toArray();
        $quicesSST = DB::table('sisig_quices')->whereIn('modulo_id', $modulosSST)->pluck('id_quiz')->toArray();
        $quicesAmbiental = DB::table('sisig_quices')->whereIn('modulo_id', $modulosAmbiental)->pluck('id_quiz')->toArray();

        $aprobados = 0;
        $pendientes = 0;
        $no_aprobados = 0;

        $participantesList = $usuarios->map(function ($aprendiz) use (
            $quicesCalidad, $quicesSST, $quicesAmbiental,
            &$aprobados, &$pendientes, &$no_aprobados
        ) {
            // Documento de identidad
            $documento = 'N/A';
            if (isset($aprendiz->person) && !empty($aprendiz->person->document_number)) {
                $documento = number_format($aprendiz->person->document_number, 0, '', '.');
            } elseif (!empty($aprendiz->documento)) {
                $documento = $aprendiz->documento;
            }

            // Ficha o Área
            $ficha = 'Sin asignar';
            if (!empty($aprendiz->person_id) && Schema::hasTable('apprentices')) {
                $fichaVal = DB::table('apprentices')->where('person_id', $aprendiz->person_id)->value('course_id');
                if ($fichaVal) {
                    $ficha = $fichaVal;
                }
            } elseif (!empty($aprendiz->ficha)) {
                $ficha = $aprendiz->ficha;
            }

            // Calcular porcentajes por sección (Puntaje de evaluación o progreso de lectura)
            $calidad = 0;
            if (!empty($quicesCalidad)) {
                $calidadPuntaje = DB::table('sisig_resultados')
                    ->where('user_id', $aprendiz->id)
                    ->whereIn('id_quiz', $quicesCalidad)
                    ->max('puntaje');
                if ($calidadPuntaje !== null) {
                    $calidad = (int) round($calidadPuntaje);
                }
            }

            $sst = 0;
            if (!empty($quicesSST)) {
                $sstPuntaje = DB::table('sisig_resultados')
                    ->where('user_id', $aprendiz->id)
                    ->whereIn('id_quiz', $quicesSST)
                    ->max('puntaje');
                if ($sstPuntaje !== null) {
                    $sst = (int) round($sstPuntaje);
                }
            }

            $ambiental = 0;
            if (!empty($quicesAmbiental)) {
                $ambPuntaje = DB::table('sisig_resultados')
                    ->where('user_id', $aprendiz->id)
                    ->whereIn('id_quiz', $quicesAmbiental)
                    ->max('puntaje');
                if ($ambPuntaje !== null) {
                    $ambiental = (int) round($ambPuntaje);
                }
            }

            // En caso de no tener evaluación pero sí avance en lectura
            if (Schema::hasTable('progreso_aprendiz_seccion')) {
                if ($calidad === 0) {
                    $progCalidad = DB::table('progreso_aprendiz_seccion')
                        ->join('sisig_secciones', 'progreso_aprendiz_seccion.id_seccion', '=', 'sisig_secciones.id_seccion')
                        ->where('progreso_aprendiz_seccion.user_id', $aprendiz->id)
                        ->where('sisig_secciones.nombre', 'like', '%calidad%')
                        ->value('porcentaje_visto');
                    if ($progCalidad) $calidad = (int) round($progCalidad);
                }
                if ($sst === 0) {
                    $progSST = DB::table('progreso_aprendiz_seccion')
                        ->join('sisig_secciones', 'progreso_aprendiz_seccion.id_seccion', '=', 'sisig_secciones.id_seccion')
                        ->where('progreso_aprendiz_seccion.user_id', $aprendiz->id)
                        ->where(function ($q) {
                            $q->where('sisig_secciones.nombre', 'like', '%sst%')
                              ->orWhere('sisig_secciones.nombre', 'like', '%seguridad%');
                        })
                        ->value('porcentaje_visto');
                    if ($progSST) $sst = (int) round($progSST);
                }
                if ($ambiental === 0) {
                    $progAmb = DB::table('progreso_aprendiz_seccion')
                        ->join('sisig_secciones', 'progreso_aprendiz_seccion.id_seccion', '=', 'sisig_secciones.id_seccion')
                        ->where('progreso_aprendiz_seccion.user_id', $aprendiz->id)
                        ->where('sisig_secciones.nombre', 'like', '%ambiental%')
                        ->value('porcentaje_visto');
                    if ($progAmb) $ambiental = (int) round($progAmb);
                }
            }

            // Estado general según los 3 módulos
            if ($calidad >= 70 && $sst >= 70 && $ambiental >= 70) {
                $estado = 'Aprobado';
                $aprobados++;
            } elseif ($calidad == 0 && $sst == 0 && $ambiental == 0) {
                $estado = 'Pendiente';
                $pendientes++;
            } else {
                $estado = 'No aprobado';
                $no_aprobados++;
            }

            // Iniciales del aprendiz para el avatar
            $nombreLimpio = trim($aprendiz->name ?? 'Aprendiz');
            $partes = preg_split('/\s+/', $nombreLimpio);
            $iniciales = '';
            if (count($partes) >= 2) {
                $iniciales = mb_strtoupper(mb_substr($partes[0], 0, 1) . mb_substr($partes[1], 0, 1));
            } else {
                $iniciales = mb_strtoupper(mb_substr($nombreLimpio, 0, 2));
            }

            return (object) [
                'id'         => $aprendiz->id,
                'nombre'     => $nombreLimpio,
                'iniciales'  => $iniciales,
                'documento'  => $documento,
                'correo'     => $aprendiz->email ?? 'N/A',
                'ficha'      => $ficha,
                'calidad'    => $calidad,
                'sst'        => $sst,
                'ambiental'  => $ambiental,
                'estado'     => $estado,
            ];
        });

        // Aplicar filtro por estado si se seleccionó
        if (!empty($filtroEstado)) {
            $participantesList = $participantesList->filter(function ($item) use ($filtroEstado) {
                return $item->estado === $filtroEstado;
            })->values();
        }

        // Aplicar ordenamiento o filtro si se seleccionó sección
        if (!empty($filtroSeccion)) {
            $participantesList = $participantesList->sortByDesc(function ($item) use ($filtroSeccion) {
                return $item->{$filtroSeccion} ?? 0;
            })->values();
        }

        $metrics = [
            'total'        => $usuarios->count(),
            'aprobados'    => $aprobados,
            'pendientes'   => $pendientes,
            'no_aprobados' => $no_aprobados,
        ];

        return view('sisig::editor.participantes.index', compact('metrics', 'participantesList', 'buscar', 'filtroEstado', 'filtroSeccion'));
    }
}