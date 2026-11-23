<?php

namespace Modules\SISIG\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SISIG\Entities\Modulo;
use Modules\SISIG\Entities\Quiz;
use Modules\SISIG\Entities\Resultado;

class SISIGController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $modulos = Modulo::with('quices')
            ->where('estado', '!=', 'inactivo')
            ->orderBy('id', 'asc')
            ->get();

        $totalModulos = $modulos->count();
        $totalQuices = Quiz::where('estado', '!=', 'inactivo')->count();
        $totalEvaluaciones = Resultado::whereNotNull('fecha_presentacion')->count();
        $moduloDestacado = $modulos->first();

        return view('sisig::index', compact('modulos', 'totalModulos', 'totalQuices', 'totalEvaluaciones', 'moduloDestacado'));
    }

    /**
     * Muestra la vista del equipo de desarrolladores de SISIG.
     */
    public function desarrolladores()
    {
        return view('sisig::desarrolladores');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sisig::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('sisig::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('sisig::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
