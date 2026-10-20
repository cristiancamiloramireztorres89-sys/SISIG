<?php

namespace Modules\SISIG\Http\Controllers\Aprendiz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SISIG\Entities\Modulo;
use Modules\SISIG\Entities\Quiz;

class InduccionAprendizController extends Controller
{
    /**
     * Muestra la lista de módulos y contenidos de inducción disponibles para el aprendiz.
     */
    public function index(Request $request)
    {
        // Consultar módulos en tiempo real desde la base de datos
        $query = Modulo::query();

        $hayPublicados = Modulo::where('estado', 'publicado')->exists();
        if ($hayPublicados) {
            $query->where('estado', 'publicado');
        } else {
            $query->where('estado', '!=', 'inactivo');
        }

        // Búsqueda opcional por término
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('titulo', 'LIKE', "%{$buscar}%")
                  ->orWhere('norma', 'LIKE', "%{$buscar}%")
                  ->orWhere('descripcion', 'LIKE', "%{$buscar}%");
            });
        }

        $modulos = $query->orderBy('id', 'asc')->get();

        $totalModulos = $modulos->count();
        $totalConContenido = $modulos->filter(fn($m) => !empty($m->contenido_html))->count();

        return view('sisig::aprendiz.induccion.induccion', compact(
            'modulos',
            'totalModulos',
            'totalConContenido'
        ));
    }

    /**
     * Despliega la pantalla de estudio interactiva de una sección temática de inducción.
     */
    public function show($id)
    {
        // Obtener el módulo seleccionado
        $modulo = Modulo::findOrFail($id);

        // Lista de módulos para la navegación rápida entre temas
        $todosModulos = Modulo::where('estado', '!=', 'inactivo')->orderBy('id', 'asc')->get();

        // Módulo previo y siguiente para navegación secuencial
        $moduloAnterior = Modulo::where('estado', '!=', 'inactivo')->where('id', '<', $id)->orderBy('id', 'desc')->first();
        $moduloSiguiente = Modulo::where('estado', '!=', 'inactivo')->where('id', '>', $id)->orderBy('id', 'asc')->first();

        $userId = auth()->id();

        // Consultar los quices o evaluaciones asignadas a este módulo
        $quicesAsociados = Quiz::where('modulo_id', $modulo->id)
            ->where('activo', 1)
            ->withCount('preguntas')
            ->get();

        $quicesConEstado = $quicesAsociados->map(function ($quiz) use ($userId) {
            $intentos = \Illuminate\Support\Facades\DB::table('sisig_resultados')
                ->where('user_id', $userId)
                ->where('id_quiz', $quiz->id_quiz)
                ->whereNotNull('fecha_presentacion')
                ->get();

            $aprobado = $intentos->contains('aprobado', 1);
            $mejorNota = $intentos->max('puntaje') ?? 0;
            $intentosUsados = $intentos->count();
            $intentosPermitidos = $quiz->numero_intentos ?? 3;
            $puedePresentar = !$aprobado && ($intentosUsados < $intentosPermitidos) && ($quiz->preguntas_count > 0);

            $quiz->aprobado = $aprobado;
            $quiz->mejor_nota = (float) $mejorNota;
            $quiz->intentos_usados = $intentosUsados;
            $quiz->intentos_permitidos = $intentosPermitidos;
            $quiz->puede_presentar = $puedePresentar;

            return $quiz;
        });

        $quizAsociado = $quicesConEstado->first();

        return view('sisig::aprendiz.induccion.show', compact(
            'modulo',
            'todosModulos',
            'moduloAnterior',
            'moduloSiguiente',
            'quizAsociado',
            'quicesConEstado'
        ));
    }
}
