<?php

namespace Modules\SISIG\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReportesController extends Controller
{
    /**
     * Muestra la vista principal de Reportes Generales del Administrador SISIG.
     * Consulta 100% en tiempo real los módulos, quices y aprendices registrados en la BD.
     */
    public function index(Request $request)
    {
        // 1. Verificación de permisos de Administrador SISIG
        if (!Auth::check() || !Auth::user()->hasRole('admin_sisig')) {
            abort(403, 'No cuentas con permisos de Administrador SISIG para acceder al módulo de reportes.');
        }

        // 2. Consultar dinámicamente los módulos existentes en la base de datos
        $modulosDB = DB::table('sisig_modulos')
            ->where('estado', '!=', 'inactivo')
            ->orderBy('id')
            ->get();

        // Mapear cada módulo dinámicamente con su norma institucional y estilo visual
        $modulosDisponibles = collect();
        $quicesPorModulo = [];

        foreach ($modulosDB as $modulo) {
            $tituloLower = mb_strtolower(trim($modulo->titulo));
            
            // Determinar norma institucional si no viene en el campo
            $norma = !empty($modulo->norma) ? $modulo->norma : null;
            if (empty($norma)) {
                if (str_contains($tituloLower, 'calidad') || str_contains($tituloLower, '9001')) {
                    $norma = 'ISO 9001';
                } elseif (str_contains($tituloLower, 'sst') || str_contains($tituloLower, 'seguridad') || str_contains($tituloLower, 'salud') || str_contains($tituloLower, '45001')) {
                    $norma = 'ISO 45001';
                } elseif (str_contains($tituloLower, 'ambiental') || str_contains($tituloLower, 'ambiente') || str_contains($tituloLower, '14001')) {
                    $norma = 'ISO 14001';
                } else {
                    $norma = 'SIG SENA';
                }
            }

            // Colores por convención del módulo
            if (str_contains($tituloLower, 'calidad')) {
                $color = 'text-emerald-700 bg-emerald-50 border-emerald-200';
                $dot = 'bg-emerald-500';
            } elseif (str_contains($tituloLower, 'sst') || str_contains($tituloLower, 'seguridad') || str_contains($tituloLower, 'salud')) {
                $color = 'text-amber-800 bg-amber-50 border-amber-200';
                $dot = 'bg-amber-500';
            } elseif (str_contains($tituloLower, 'ambiental') || str_contains($tituloLower, 'ambiente')) {
                $color = 'text-teal-700 bg-teal-50 border-teal-200';
                $dot = 'bg-teal-500';
            } else {
                $color = 'text-sky-700 bg-sky-50 border-sky-200';
                $dot = 'bg-sky-500';
            }

            $modulosDisponibles->push([
                'id'     => $modulo->id,
                'nombre' => $modulo->titulo,
                'norma'  => $norma,
                'color'  => $color,
                'dot'    => $dot,
            ]);

            // Quices vinculados a este módulo específico en la BD
            $quicesIds = DB::table('sisig_quices')
                ->where(function ($q) use ($modulo, $tituloLower) {
                    $q->where('modulo_id', $modulo->id)
                      ->orWhere('titulo', 'like', "%{$tituloLower}%");
                })
                ->pluck('id_quiz')
                ->toArray();

            $quicesPorModulo[$modulo->id] = $quicesIds;
        }

        // 3. Parámetros de filtros recibidos por la solicitud
        $filtroSeccion = $request->input('seccion_sig', 'todas');
        $filtroEstado = strtolower($request->input('estado', 'todos'));
        $filtroFicha = trim($request->input('ficha', ''));

        // Pestaña de sección activa para el Reporte 2 (Quizzes)
        // Por defecto toma el primer módulo disponible de la BD
        $seccionQuizParam = $request->input('seccion_quiz');
        $moduloActivo = null;

        if ($modulosDisponibles->isNotEmpty()) {
            if (!empty($seccionQuizParam)) {
                $moduloActivo = $modulosDisponibles->firstWhere('id', (int) $seccionQuizParam)
                    ?? $modulosDisponibles->first(fn($m) => mb_strtolower($m['nombre']) === mb_strtolower($seccionQuizParam))
                    ?? $modulosDisponibles->first();
            } else {
                $moduloActivo = $modulosDisponibles->first();
            }
        }

        $seccionQuizId = $moduloActivo['id'] ?? null;

        // 4. Consultar los aprendices registrados en la plataforma
        $queryAprendices = User::query();
        if (method_exists(User::class, 'person')) {
            $queryAprendices->with('person');
        }

        // Excluir administradores y editores para mantener exclusivamente los aprendices
        if (method_exists(User::class, 'roles')) {
            $queryAprendices->whereDoesntHave('roles', function ($q) {
                $q->whereIn('slug', ['admin_sisig', 'sisig.admin', 'editor_sisig']);
            });
        }

        $aprendices = $queryAprendices->orderBy('name')->get();

        // 5. Construcción de datos del REPORTE 1: Listado de Aprendices Registrados
        $reporte1Filas = collect();
        $totalInduccionCompletada = 0;
        $totalAprobaronQuizzes = 0;
        $totalNoAprobaronQuizzes = 0;

        foreach ($aprendices as $aprendiz) {
            // Documento de identidad formateado con puntos (ej. 1.023.456.789)
            $documento = 'N/A';
            if (isset($aprendiz->person) && !empty($aprendiz->person->document_number)) {
                $docNum = $aprendiz->person->document_number;
                $documento = is_numeric($docNum) ? number_format((float) $docNum, 0, '', '.') : (string) $docNum;
            } elseif (!empty($aprendiz->documento)) {
                $documento = (string) $aprendiz->documento;
            }

            // Ficha o Programa formativo
            $ficha = 'Sin asignar';
            if (!empty($aprendiz->person_id) && Schema::hasTable('apprentices')) {
                $fichaVal = DB::table('apprentices')->where('person_id', $aprendiz->person_id)->value('course_id');
                if ($fichaVal) {
                    $ficha = (string) $fichaVal;
                }
            } elseif (!empty($aprendiz->ficha)) {
                $ficha = (string) $aprendiz->ficha;
            }

            // Nombre completo limpio
            $nombreAprendiz = trim($aprendiz->name ?? 'Aprendiz');
            if (isset($aprendiz->person) && (!empty($aprendiz->person->first_name) || !empty($aprendiz->person->first_last_name))) {
                $nombreCompleto = trim("{$aprendiz->person->first_name} {$aprendiz->person->first_last_name} " . ($aprendiz->person->second_last_name ?? ''));
                if (!empty($nombreCompleto)) {
                    $nombreAprendiz = $nombreCompleto;
                }
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

            // Evaluar el progreso del aprendiz en CADA MÓDULO existente en la BD
            $modulosCompletadosCount = 0;

            foreach ($modulosDisponibles as $mod) {
                $progresoSec = 0;
                $quizIdsSec = $quicesPorModulo[$mod['id']] ?? [];

                // 1. Verificar si tiene resultado en el quiz del módulo
                $resultadoSec = null;
                if (!empty($quizIdsSec)) {
                    $resultadoSec = DB::table('sisig_resultados')
                        ->where('user_id', $aprendiz->id)
                        ->whereIn('id_quiz', $quizIdsSec)
                        ->orderByDesc('puntaje')
                        ->first();
                }

                if ($resultadoSec) {
                    if ($resultadoSec->aprobado == 1 || $resultadoSec->puntaje >= 70) {
                        $progresoSec = 100;
                    } else {
                        $progresoSec = (int) round($resultadoSec->puntaje);
                    }
                } elseif (Schema::hasTable('progreso_aprendiz_seccion')) {
                    // 2. Si no tiene quiz presentado, verificar lectura en progreso_aprendiz_seccion
                    $porcentajeLectura = DB::table('progreso_aprendiz_seccion')
                        ->join('sisig_secciones', 'progreso_aprendiz_seccion.id_seccion', '=', 'sisig_secciones.id_seccion')
                        ->where('progreso_aprendiz_seccion.user_id', $aprendiz->id)
                        ->where('sisig_secciones.nombre', 'like', "%{$mod['nombre']}%")
                        ->value('porcentaje_visto');

                    if ($porcentajeLectura) {
                        $progresoSec = (int) round($porcentajeLectura);
                    }
                }

                if ($progresoSec >= 100) {
                    $estadoSec = 'Completado';
                    $modulosCompletadosCount++;
                } elseif ($progresoSec > 0) {
                    $estadoSec = 'En progreso';
                } else {
                    $estadoSec = 'Sin iniciar';
                }

                // Generar registro para la tabla del Reporte 1
                $reporte1Filas->push((object) [
                    'id'          => $aprendiz->id,
                    'aprendiz'    => $nombreAprendiz,
                    'avatar_url'  => $avatarUrl,
                    'documento'   => $documento,
                    'correo'      => $aprendiz->email ?? 'N/A',
                    'ficha'       => $ficha,
                    'modulo_id'   => $mod['id'],
                    'seccion'     => $mod['nombre'],
                    'seccion_key' => mb_strtolower($mod['nombre']),
                    'color_badge' => $mod['color'],
                    'progreso'    => $progresoSec,
                    'estado'      => $estadoSec,
                ]);
            }

            // Evaluación de métricas globales del aprendiz
            if ($modulosDisponibles->isNotEmpty() && $modulosCompletadosCount >= $modulosDisponibles->count()) {
                // Aprendiz que ha completado todos los módulos creados en el sistema
                $totalInduccionCompletada++;
            }

            // Verificar si el aprendiz aprobó o reprobó quizzes en la plataforma
            $resultadosGenerales = DB::table('sisig_resultados')
                ->where('user_id', $aprendiz->id)
                ->get();

            if ($resultadosGenerales->isNotEmpty()) {
                $tieneAprobado = $resultadosGenerales->contains(fn($r) => $r->aprobado == 1 || $r->puntaje >= 70);
                if ($tieneAprobado) {
                    $totalAprobaronQuizzes++;
                } else {
                    $totalNoAprobaronQuizzes++;
                }
            }
        }

        // Aplicar filtros al REPORTE 1
        // Filtro por Sección / Módulo
        if ($filtroSeccion !== 'todas' && !empty($filtroSeccion)) {
            $reporte1Filas = $reporte1Filas->filter(function ($item) use ($filtroSeccion) {
                return (string) $item->modulo_id === (string) $filtroSeccion 
                    || mb_strtolower($item->seccion) === mb_strtolower($filtroSeccion);
            });
        }

        // Filtro por Estado
        if ($filtroEstado !== 'todos' && in_array($filtroEstado, ['completado', 'en_progreso', 'sin_iniciar'])) {
            $estadoEsperado = match ($filtroEstado) {
                'completado'   => 'Completado',
                'en_progreso'  => 'En progreso',
                'sin_iniciar'  => 'Sin iniciar',
                default        => null,
            };
            if ($estadoEsperado) {
                $reporte1Filas = $reporte1Filas->filter(fn($item) => $item->estado === $estadoEsperado);
            }
        }

        // Filtro por Ficha
        if (!empty($filtroFicha)) {
            $reporte1Filas = $reporte1Filas->filter(function ($item) use ($filtroFicha) {
                return stripos($item->ficha, $filtroFicha) !== false;
            });
        }

        $reporte1Filas = $reporte1Filas->values();

        // 6. Construcción de datos del REPORTE 2: Precalcular datos de todos los módulos para cambio instantáneo (sin recarga)
        $datosReporte2PorModulo = [];

        foreach ($modulosDisponibles as $modItem) {
            $mId = $modItem['id'];
            $quicesModulo = $quicesPorModulo[$mId] ?? [];
            $filasModulo = [];
            $secAprobados = 0;
            $secNoAprobados = 0;
            $secPendientes = 0;
            $secAnulados = 0;

            foreach ($aprendices as $aprendiz) {
                $nombreAprendiz = trim($aprendiz->name ?? 'Aprendiz');
                if (isset($aprendiz->person) && (!empty($aprendiz->person->first_name) || !empty($aprendiz->person->first_last_name))) {
                    $nombreCompleto = trim("{$aprendiz->person->first_name} {$aprendiz->person->first_last_name} " . ($aprendiz->person->second_last_name ?? ''));
                    if (!empty($nombreCompleto)) {
                        $nombreAprendiz = $nombreCompleto;
                    }
                }

                $avatarUrl2 = null;
                if (isset($aprendiz->person) && !empty($aprendiz->person->avatar)) {
                    $avatarVal = $aprendiz->person->avatar;
                    $avatarUrl2 = (str_starts_with($avatarVal, 'http') || str_starts_with($avatarVal, '/'))
                        ? $avatarVal
                        : asset('storage/' . $avatarVal);
                } elseif (!empty($aprendiz->avatar)) {
                    $avatarVal = $aprendiz->avatar;
                    $avatarUrl2 = (str_starts_with($avatarVal, 'http') || str_starts_with($avatarVal, '/'))
                        ? $avatarVal
                        : asset('storage/' . $avatarVal);
                }

                $partesNombre = preg_split('/\s+/', $nombreAprendiz);
                $iniciales = count($partesNombre) >= 2
                    ? mb_strtoupper(mb_substr($partesNombre[0], 0, 1) . mb_substr($partesNombre[1], 0, 1))
                    : mb_strtoupper(mb_substr($nombreAprendiz, 0, 2));

                $ficha = 'Sin asignar';
                if (!empty($aprendiz->person_id) && Schema::hasTable('apprentices')) {
                    $fichaVal = DB::table('apprentices')->where('person_id', $aprendiz->person_id)->value('course_id');
                    if ($fichaVal) {
                        $ficha = (string) $fichaVal;
                    }
                } elseif (!empty($aprendiz->ficha)) {
                    $ficha = (string) $aprendiz->ficha;
                }

                // Consultar resultado del quiz (el más reciente por id_resultado)
                $resultado = null;
                if (!empty($quicesModulo)) {
                    $resultado = DB::table('sisig_resultados')
                        ->where('user_id', $aprendiz->id)
                        ->whereIn('id_quiz', $quicesModulo)
                        ->orderByDesc('id_resultado')
                        ->first();
                }

                if ($resultado) {
                    $puntaje = (int) round($resultado->puntaje ?? 0);
                    $intentos = $resultado->intento ?? 1;
                    $fueAnulado = (bool) ($resultado->fue_anulado ?? false);
                    $esAprobado = !$fueAnulado && ($puntaje >= 70 || $resultado->aprobado == 1);
                    $fechaRaw = $resultado->fecha_presentacion ?? $resultado->created_at ?? now();
                    $fecha = Carbon::parse($fechaRaw)->translatedFormat('d M Y');
                    $motivoAnulacion = $resultado->motivo_anulacion ?? 'Infracciones anti-trampa detectadas';
                    $infraccionesCount = (int) ($resultado->infracciones_detectadas ?? 0);

                    if ($fueAnulado) {
                        $secAnulados++;
                        $secNoAprobados++;
                        $resultadoTexto = 'Anulado por Trampa';
                        $estadoTipo = 'trampa';
                    } elseif ($esAprobado) {
                        $secAprobados++;
                        $resultadoTexto = 'Aprobado';
                        $estadoTipo = 'aprobado';
                    } else {
                        $secNoAprobados++;
                        $resultadoTexto = 'No aprobado (Puntaje bajo)';
                        $estadoTipo = 'no_aprobado';
                    }

                    $filasModulo[] = (object) [
                        'id'               => $aprendiz->id,
                        'aprendiz'         => $nombreAprendiz,
                        'avatar_url'       => $avatarUrl2,
                        'iniciales'        => $iniciales,
                        'ficha'            => $ficha,
                        'intentos'         => $intentos,
                        'puntaje'          => "{$puntaje}/100",
                        'puntaje_num'      => $puntaje,
                        'resultado'        => $resultadoTexto,
                        'estado_tipo'      => $estadoTipo,
                        'fue_anulado'      => $fueAnulado,
                        'motivo_anulacion' => $motivoAnulacion,
                        'infracciones'     => $infraccionesCount,
                        'fecha'            => $fecha,
                    ];
                } else {
                    $secPendientes++;

                    $filasModulo[] = (object) [
                        'id'               => $aprendiz->id,
                        'aprendiz'         => $nombreAprendiz,
                        'avatar_url'       => $avatarUrl2,
                        'iniciales'        => $iniciales,
                        'ficha'            => $ficha,
                        'intentos'         => '—',
                        'puntaje'          => '—',
                        'puntaje_num'      => null,
                        'resultado'        => 'Pendiente',
                        'estado_tipo'      => 'pendiente',
                        'fue_anulado'      => false,
                        'motivo_anulacion' => null,
                        'infracciones'     => 0,
                        'fecha'            => '—',
                    ];
                }
            }

            $totalEvaluados = $secAprobados + $secNoAprobados;
            $pctAprobados = $totalEvaluados > 0 ? round(($secAprobados / $totalEvaluados) * 100) : 0;

            $datosReporte2PorModulo[$mId] = [
                'modulo_id'            => $mId,
                'nombre'               => $modItem['nombre'],
                'norma'                => $modItem['norma'],
                'aprobados'            => $secAprobados,
                'no_aprobados'         => $secNoAprobados,
                'pendientes'           => $secPendientes,
                'anulados'             => $secAnulados,
                'total_evaluados'      => $totalEvaluados,
                'porcentaje_aprobados' => $pctAprobados,
                'filas'                => $filasModulo,
            ];
        }

        // Datos iniciales para el módulo activo
        $seccionActivaData = $datosReporte2PorModulo[$seccionQuizId] ?? reset($datosReporte2PorModulo) ?? null;
        $metricasSeccionQuiz = $seccionActivaData ? [
            'modulo_id'           => $seccionActivaData['modulo_id'],
            'seccion_nombre'      => $seccionActivaData['nombre'],
            'seccion_norma'       => $seccionActivaData['norma'],
            'aprobados'           => $seccionActivaData['aprobados'],
            'no_aprobados'        => $seccionActivaData['no_aprobados'],
            'pendientes'          => $seccionActivaData['pendientes'],
            'anulados'            => $seccionActivaData['anulados'] ?? 0,
            'total_evaluados'     => $seccionActivaData['total_evaluados'],
            'porcentaje_aprobados'=> $seccionActivaData['porcentaje_aprobados'],
        ] : [];
        $reporte2Filas = collect($seccionActivaData['filas'] ?? []);

        // Conteo de alertas antitrampa registradas
        $totalAlertasAntitrampa = Schema::hasTable('infraccionesquiz')
            ? DB::table('infraccionesquiz')->count()
            : 0;

        // 7. Resumen Global (Tarjetas KPI superiores)
        $resumenGlobal = [
            'aprendices_registrados' => $aprendices->count(),
            'induccion_completada'   => $totalInduccionCompletada,
            'aprobaron_quizzes'      => $totalAprobaronQuizzes,
            'no_aprobaron_quizzes'   => $totalNoAprobaronQuizzes,
            'alertas_antitrampa'     => $totalAlertasAntitrampa,
        ];

        $seccionQuiz = $seccionQuizId;

        return view('sisig::admin.ReportesAdmin', compact(
            'resumenGlobal',
            'reporte1Filas',
            'filtroSeccion',
            'filtroEstado',
            'filtroFicha',
            'seccionQuizId',
            'seccionQuiz',
            'moduloActivo',
            'metricasSeccionQuiz',
            'reporte2Filas',
            'modulosDisponibles',
            'datosReporte2PorModulo'
        ));
    }
}
