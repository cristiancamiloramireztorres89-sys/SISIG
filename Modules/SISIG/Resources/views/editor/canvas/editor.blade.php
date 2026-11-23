@php
    $module = $module ?? \Modules\SISIG\Entities\Modulo::first();
    $modulos = \Modules\SISIG\Entities\Modulo::all();
@endphp

@include('sisig::admin.ContenidoModulo', [
    'routePrefix' => 'sisig.editor',
    'modulos' => $modulos,
    'moduloSeleccionado' => $module
])
