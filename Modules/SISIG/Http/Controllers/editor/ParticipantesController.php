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

        // 1. Obtener todas las secciones formativas activas dinámicamente de la BD (Solo SELECT)
        $modulos = DB::table('sisig_modulos')
            ->where('estado', '!=', 'inactivo')
            ->orderBy('id', 'asc')
            ->get();

        if ($modulos->isEmpty()) {
            $modulos = DB::table('sisig_modulos')->orderBy('id', 'asc')->get();
        }

        // Asignar visual e icono dinámico a cada módulo
        $modulos = $modulos->map(function ($mod) {
            $tit = strtolower($mod->titulo ?? '');
            if (str_contains($tit, 'calidad') || str_contains($tit, '9001')) {
                $mod->icono_base = 'fas fa-certificate';
                $mod->icon_bg = 'bg-sky-50 text-sky-600';
            } elseif (str_contains($tit, 'sst') || str_contains($tit, 'seguridad') || str_contains($tit, 'salud') || str_contains($tit, '45001')) {
                $mod->icono_base = 'fas fa-hard-hat';
                $mod->icon_bg = 'bg-amber-50 text-amber-600';
            } elseif (str_contains($tit, 'ambiental') || str_contains($tit, 'ambiente') || str_contains($tit, '14001')) {
                $mod->icono_base = 'fas fa-leaf';
                $mod->icon_bg = 'bg-emerald-50 text-emerald-600';
            } elseif (!empty($mod->icon_class)) {
                $mod->icono_base = trim(preg_replace('/text-[a-z0-9\/-]+/', '', $mod->icon_class)) ?: 'fas fa-layer-group';
                $mod->icon_bg = $mod->icon_bg ?? 'bg-sky-50 text-sky-600';
            } else {
                $mod->icono_base = 'fas fa-layer-group';
                $mod->icon_bg = 'bg-indigo-50 text-indigo-600';
            }
            return $mod;
        });

        // 2. Asociar quices a cada módulo dinámicamente
        $quices = DB::table('sisig_quices')->where('activo', 1)->get();
        $quicesPorModulo = [];
        foreach ($modulos as $mod) {
            $quicesPorModulo[$mod->id] = $quices->filter(function ($q) use ($mod) {
                if ($q->modulo_id == $mod->id) return true;
                $qTit = strtolower($q->titulo ?? '');
                $mTit = strtolower($mod->titulo ?? '');
                if (!empty($mTit) && str_contains($qTit, $mTit)) return true;
                if (!empty($mod->norma) && str_contains($qTit, strtolower($mod->norma))) return true;
                return false;
            })->pluck('id_quiz')->toArray();
        }

        // 3. Pre-cargar resultados reales para evitar consultas N+1
        $userIds = $usuarios->pluck('id')->toArray();
        $resultadosGrouped = DB::table('sisig_resultados')
            ->whereIn('user_id', $userIds)
            ->where('fue_anulado', false)
            ->get()
            ->groupBy('user_id');

        $progresosGrouped = collect();
        if (Schema::hasTable('progreso_aprendiz_seccion')) {
            $progresosGrouped = DB::table('progreso_aprendiz_seccion')
                ->whereIn('user_id', $userIds)
                ->get()
                ->groupBy('user_id');
        }

        $aprobados = 0;
        $pendientes = 0;
        $no_aprobados = 0;

        $participantesList = $usuarios->map(function ($aprendiz) use (
            $modulos, $quicesPorModulo, $resultadosGrouped, $progresosGrouped,
            &$aprobados, &$pendientes, &$no_aprobados
        ) {
            // Documento y tipo de documento real
            $documento = 'Sin asignar';
            $tipoDocumento = 'Sin asignar';
            if (isset($aprendiz->person)) {
                if (!empty($aprendiz->person->document_number)) {
                    $documento = number_format($aprendiz->person->document_number, 0, '', '.');
                }
                if (!empty($aprendiz->person->document_type)) {
                    $tipoDocumento = $aprendiz->person->document_type;
                }
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

            // Iteración dinámica por cada sección formativa real de la BD
            $seccionesAprendiz = [];
            $modulosAprobados = 0;
            $modulosIniciados = 0;

            foreach ($modulos as $mod) {
                $quizIds = $quicesPorModulo[$mod->id] ?? [];
                $porcentaje = 0;

                // Consulta real de puntaje en quices
                if (!empty($quizIds) && isset($resultadosGrouped[$aprendiz->id])) {
                    $maxPuntaje = $resultadosGrouped[$aprendiz->id]
                        ->whereIn('id_quiz', $quizIds)
                        ->max('puntaje');

                    if ($maxPuntaje !== null) {
                        $porcentaje = (int) round($maxPuntaje);
                    }
                }

                // Avance de lectura si no tiene evaluación
                if ($porcentaje === 0 && isset($progresosGrouped[$aprendiz->id])) {
                    $progresoRow = $progresosGrouped[$aprendiz->id]->first(function ($p) use ($mod) {
                        return $p->id_seccion == $mod->id;
                    });

                    if ($progresoRow && $progresoRow->porcentaje_visto > 0) {
                        $porcentaje = (int) round($progresoRow->porcentaje_visto);
                    }
                }

                if ($porcentaje >= 70) {
                    $estadoSeccion = 'Aprobado';
                    $modulosAprobados++;
                    $modulosIniciados++;
                } elseif ($porcentaje > 0) {
                    $estadoSeccion = 'En progreso';
                    $modulosIniciados++;
                } else {
                    $estadoSeccion = 'Sin iniciar';
                }

                $seccionesAprendiz[] = [
                    'id'         => $mod->id,
                    'titulo'     => $mod->titulo,
                    'norma'      => $mod->norma ?? '',
                    'icono'      => $mod->icono_base,
                    'icon_bg'    => $mod->icon_bg,
                    'porcentaje' => $porcentaje,
                    'estado'     => $estadoSeccion,
                ];
            }

            // Estado general según módulos
            $totalMods = $modulos->count();
            if ($totalMods > 0 && $modulosAprobados === $totalMods) {
                $estado = 'Aprobado';
                $aprobados++;
            } elseif ($modulosIniciados === 0) {
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

            // Foto / Avatar guardado del usuario
            $avatarUrl = null;
            if (isset($aprendiz->person) && !empty($aprendiz->person->avatar)) {
                $avatarVal = $aprendiz->person->avatar;
                $avatarUrl = (str_starts_with($avatarVal, 'http') || str_starts_with($avatarVal, '/'))
                    ? $avatarVal
                    : asset('storage/' . $avatarVal);
            } elseif (!empty($aprendiz->avatar)) {
                $avatarVal = $aprendiz->avatar;
                $avatarUrl = (str_starts_with($avatarVal, 'http') || str_starts_with($avatarVal, '/'))
                    ? $avatarVal
                    : asset('storage/' . $avatarVal);
            }

            // Compatibilidad con columnas de la tabla existente
            $calidad = 0; $sst = 0; $ambiental = 0;
            foreach ($seccionesAprendiz as $sec) {
                $t = strtolower($sec['titulo']);
                if (str_contains($t, 'calidad') || str_contains(strtolower($sec['norma']), '9001')) $calidad = $sec['porcentaje'];
                elseif (str_contains($t, 'sst') || str_contains($t, 'seguridad') || str_contains(strtolower($sec['norma']), '45001')) $sst = $sec['porcentaje'];
                elseif (str_contains($t, 'ambiental') || str_contains($t, 'ambiente') || str_contains(strtolower($sec['norma']), '14001')) $ambiental = $sec['porcentaje'];
            }

            return (object) [
                'id'             => $aprendiz->id,
                'nombre'         => $nombreLimpio,
                'avatar_url'     => $avatarUrl,
                'iniciales'      => $iniciales,
                'documento'      => $documento,
                'tipo_documento' => $tipoDocumento,
                'correo'         => $aprendiz->email ?? 'Sin asignar',
                'ficha'          => $ficha,
                'calidad'        => $calidad,
                'sst'            => $sst,
                'ambiental'      => $ambiental,
                'secciones'      => $seccionesAprendiz,
                'estado'         => $estado,
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

        return view('sisig::editor.participantes.index', compact('metrics', 'participantesList', 'buscar', 'filtroEstado', 'filtroSeccion', 'modulos'));
    }
}