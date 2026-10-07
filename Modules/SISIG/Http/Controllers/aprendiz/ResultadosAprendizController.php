<?php

namespace Modules\SISIG\Http\Controllers\Aprendiz;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\SISIG\Entities\Quiz;
use Modules\SISIG\Entities\Modulo;
use Modules\SISIG\Entities\Pregunta;

class ResultadosAprendizController extends Controller
{
    /**
     * Muestra la vista de Mis Resultados del Aprendiz en tiempo real.
     * Consulta el desempeño por sección SIG y el historial de intentos realizados.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $seccionActiva = strtolower($request->input('seccion', 'calidad'));
        if (!in_array($seccionActiva, ['calidad', 'sst', 'ambiental'])) {
            $seccionActiva = 'calidad';
        }

        // Configuración de los 3 ejes temáticos del SIG
        $configEjes = [
            'calidad' => [
                'slug'          => 'calidad',
                'titulo'        => 'Calidad',
                'norma'         => 'ISO 9001:2015',
                'icono'         => 'fas fa-check-circle',
                'preguntas_def' => 3,
                'intentos_def'  => 3,
                'terminos'      => ['calidad', '9001'],
            ],
            'sst' => [
                'slug'          => 'sst',
                'titulo'        => 'SST',
                'norma'         => 'ISO 45001:2018',
                'icono'         => 'fas fa-hard-hat',
                'preguntas_def' => 2,
                'intentos_def'  => 2,
                'terminos'      => ['sst', 'seguridad', 'salud', '45001'],
            ],
            'ambiental' => [
                'slug'          => 'ambiental',
                'titulo'        => 'Ambiental',
                'norma'         => 'ISO 14001:2015',
                'icono'         => 'fas fa-tree',
                'preguntas_def' => 1,
                'intentos_def'  => 3,
                'terminos'      => ['ambiental', 'ambiente', '14001'],
            ],
        ];

        $tarjetasSecciones = [];
        $historialPorSeccion = [
            'calidad'   => collect(),
            'sst'       => collect(),
            'ambiental' => collect(),
        ];
        $infoSeccionActiva = null;

        foreach ($configEjes as $key => $eje) {
            // Consultar módulo en tiempo real
            $modulo = DB::table('sisig_modulos')
                ->where(function ($q) use ($eje) {
                    foreach ($eje['terminos'] as $t) {
                        $q->orWhere('titulo', 'like', "%{$t}%")
                          ->orWhere('norma', 'like', "%{$t}%");
                    }
                })
                ->first();

            // Consultar quiz asociado
            $quiz = null;
            if ($modulo) {
                $quiz = DB::table('sisig_quices')->where('modulo_id', $modulo->id)->first();
            }
            if (!$quiz) {
                $quiz = DB::table('sisig_quices')->where('titulo', 'like', "%{$eje['titulo']}%")->first();
            }

            $norma = ($modulo && !empty($modulo->norma)) ? $modulo->norma : $eje['norma'];
            $preguntasTotal = $quiz ? (int) ($quiz->numero_preguntas ?? $eje['preguntas_def']) : $eje['preguntas_def'];
            $intentosMax = $quiz ? (int) ($quiz->numero_intentos ?? $eje['intentos_def']) : $eje['intentos_def'];

            // Contar preguntas reales si existen
            if ($quiz) {
                $reales = DB::table('sisig_preguntas')->where('id_quiz', $quiz->id_quiz)->count();
                if ($reales > 0) {
                    $preguntasTotal = $reales;
                }
            }

            // Consultar intentos del aprendiz en tiempo real
            $intentos = collect();
            if ($quiz && Schema::hasTable('sisig_resultados')) {
                $intentos = DB::table('sisig_resultados')
                    ->where('user_id', $userId)
                    ->where('id_quiz', $quiz->id_quiz)
                    ->orderBy('intento', 'asc')
                    ->get();
            }

            $intentosCount = $intentos->count();
            $mejorPuntaje = $intentosCount > 0 ? (int) round($intentos->max('puntaje')) : null;
            $aprobado = $intentos->contains(function ($item) {
                return $item->aprobado == 1 || $item->puntaje >= 70;
            });

            // Determinar estado de la tarjeta
            if ($intentosCount === 0) {
                $estado = 'Pendiente';
                $estadoBadge = '● Pendiente';
                $badgeBg = 'bg-[#f1f5f9]';
                $badgeText = 'text-slate-500';
            } elseif ($aprobado) {
                $estado = 'Aprobado';
                $estadoBadge = '● Aprobado';
                $badgeBg = 'bg-[#d1fae5]';
                $badgeText = 'text-[#0d5c3a]';
            } else {
                $estado = 'No aprobado';
                $estadoBadge = '● No aprobado';
                $badgeBg = 'bg-[#fee2e2]';
                $badgeText = 'text-[#b91c1c]';
            }

            // Estructura para la tarjeta superior
            $tarjetasSecciones[$key] = (object) [
                'slug'              => $key,
                'titulo'            => $eje['titulo'],
                'norma'             => $norma,
                'icono'             => $eje['icono'],
                'estado'            => $estado,
                'estado_badge'      => $estadoBadge,
                'badge_bg'          => $badgeBg,
                'badge_text'        => $badgeText,
                'puntaje'           => $mejorPuntaje,
                'intentos_count'    => $intentosCount,
                'intentos_max'      => $intentosMax,
                'preguntas_total'   => $preguntasTotal,
                'quiz_id'           => $quiz->id_quiz ?? null,
            ];

            // Mapear historial detallado
            $historialItems = $intentos->map(function ($it, $index) use ($preguntasTotal, $eje) {
                $puntaje = (int) round($it->puntaje ?? 0);
                $esAprobado = $puntaje >= 70 || $it->aprobado == 1;
                $correctas = $preguntasTotal > 0 ? (int) round(($puntaje / 100) * $preguntasTotal) : 0;

                $fechaFormatted = 'N/A';
                if (!empty($it->fecha_presentacion) || !empty($it->created_at)) {
                    $f = Carbon::parse($it->fecha_presentacion ?? $it->created_at);
                    $fechaFormatted = $f->translatedFormat('d M Y') . ' · ' . $f->format('h:i a');
                }

                return (object) [
                    'numero'     => $it->intento ?? ($index + 1),
                    'fecha'      => $fechaFormatted,
                    'puntaje'    => $puntaje,
                    'correctas'  => "{$correctas} de {$preguntasTotal} correctas",
                    'aprobado'   => $esAprobado,
                    'estado_txt' => $esAprobado ? 'Aprobado' : 'No aprobado',
                    'slug'       => $eje['slug'],
                ];
            });

            $historialPorSeccion[$key] = $historialItems;

            if ($key === $seccionActiva) {
                $infoSeccionActiva = $tarjetasSecciones[$key];
            }
        }

        $historialActual = $historialPorSeccion[$seccionActiva] ?? collect();

        return view('sisig::aprendiz.resultados.index', compact(
            'tarjetasSecciones',
            'seccionActiva',
            'infoSeccionActiva',
            'historialActual'
        ));
    }
}
