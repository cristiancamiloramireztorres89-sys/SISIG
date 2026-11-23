<x-sisig::layouts.admin title="Diseñador Visual de Contenido: {{ $moduloSeleccionado->titulo }}">
    <!-- GrapesJS CSS (Local / Offline) -->
    <link rel="stylesheet" href="{{ asset('modules/sisig/vendor/grapesjs/grapes.min.css') }}">
    <!-- Estilos personalizados del Constructor Visual SISIG -->
    <link rel="stylesheet" href="{{ asset('modules/sisig/css/grapes-builder.css') }}?v={{ @filemtime(public_path('modules/sisig/css/grapes-builder.css')) }}">

@php
    $routePrefix = $routePrefix ?? (request()->is('*editor*') ? 'sisig.editor' : 'sisig.admin');
    $isEditor = $routePrefix === 'sisig.editor';
    $tabRouteName = $isEditor ? 'sisig.editor.contenido.edit' : 'sisig.admin.modulos.contenido';
    $updateRouteName = $isEditor ? 'sisig.editor.contenido.update' : 'sisig.admin.modulos.contenido.update';
    $backRouteName = $isEditor ? 'sisig.editor.contenido.index' : 'sisig.admin.modulos.index';
    $codigoDocumento = 'SIG-MOD-' . str_pad($moduloSeleccionado->id, 2, '0', STR_PAD_LEFT);
@endphp

    <div class="sisig-builder-wrapper rounded-3xl overflow-hidden shadow-2xl border border-slate-700/60 animate-fade-in -mt-2">
        
        <!-- 1. BARRA SUPERIOR DE CONTROL (TOPBAR CANVA STYLE) -->
        <header class="sisig-builder-topbar">
            <!-- Izquierda: Volver y Título del Módulo -->
            <div class="flex items-center gap-3">
                <a href="{{ route($backRouteName) }}" 
                   title="Volver al catálogo" 
                   class="w-9 h-9 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-all cursor-pointer">
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>

                <div class="hidden sm:block">
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-400 bg-emerald-950/60 px-2 py-0.5 rounded-md border border-emerald-800/80">
                            {{ $codigoDocumento }}
                        </span>
                        <h1 class="text-sm font-bold text-white tracking-tight truncate max-w-xs md:max-w-md">
                            {{ $moduloSeleccionado->titulo }}
                        </h1>
                    </div>
                </div>
            </div>

            <!-- Centro: Selector de Dispositivos (Device Manager) -->
            <div class="flex items-center bg-slate-900/60 p-1 rounded-full border border-slate-700/80">
                <button type="button" class="sisig-builder-btn sisig-builder-btn-device active" data-device="desktop" title="Vista Escritorio">
                    <i class="fas fa-desktop text-xs"></i>
                    <span class="hidden md:inline">Escritorio</span>
                </button>
                <button type="button" class="sisig-builder-btn sisig-builder-btn-device" data-device="tablet" title="Vista Tablet">
                    <i class="fas fa-tablet-alt text-xs"></i>
                    <span class="hidden md:inline">Tablet</span>
                </button>
                <button type="button" class="sisig-builder-btn sisig-builder-btn-device" data-device="mobile" title="Vista Móvil">
                    <i class="fas fa-mobile-alt text-xs"></i>
                    <span class="hidden md:inline">Móvil</span>
                </button>
            </div>

            <!-- Derecha: Acciones Rápidas y Guardado -->
            <div class="flex items-center gap-1.5 sm:gap-2">
                <!-- Deshacer y Rehacer -->
                <button type="button" id="btn-undo" class="sisig-builder-btn sisig-builder-btn-default px-2.5" title="Deshacer (Ctrl + Z)">
                    <i class="fas fa-undo text-xs"></i>
                </button>
                <button type="button" id="btn-redo" class="sisig-builder-btn sisig-builder-btn-default px-2.5" title="Rehacer (Ctrl + Y)">
                    <i class="fas fa-redo text-xs"></i>
                </button>

                <!-- Vista Previa / Limpiar -->
                <button type="button" id="btn-preview" class="sisig-builder-btn sisig-builder-btn-default" title="Modo Vista Previa">
                    <i class="fas fa-eye text-xs"></i>
                    <span class="hidden lg:inline">Vista Previa</span>
                </button>
                <button type="button" id="btn-clear" class="sisig-builder-btn sisig-builder-btn-default px-2.5 text-rose-300 hover:text-rose-100" title="Limpiar lienzo">
                    <i class="fas fa-trash-alt text-xs"></i>
                </button>

                <!-- Botón Principal de Guardado -->
                <button type="button" id="btn-save" class="sisig-builder-btn sisig-builder-btn-save ml-1">
                    <i class="fas fa-save text-xs"></i>
                    <span id="txt-save">Guardar</span>
                </button>
            </div>
        </header>

        <!-- 2. ÁREA DE TRABAJO PRINCIPAL (LIENZO + PANEL LATERAL) -->
        <div class="flex-grow flex overflow-hidden relative">
            
            <!-- Lienzo Interactivo Central (GrapesJS Canvas) -->
            <main class="flex-grow relative h-full overflow-hidden bg-slate-900">
                <div id="gjs" 
                     data-modulo-id="{{ $moduloSeleccionado->id }}"
                     data-save-url="{{ route($updateRouteName, $moduloSeleccionado->id) }}"
                     data-upload-url="{{ route('sisig.editor.canvas.upload') }}"
                     data-csrf-token="{{ csrf_token() }}">
                </div>

                <!-- Almacenamiento seguro de estado y HTML sin colisión de comillas en atributos -->
                <script type="application/json" id="sisig-canvas-state">
                    {!! json_encode($moduloSeleccionado->canvas_state ?? null) !!}
                </script>
                <template id="sisig-initial-html">
                    {!! $moduloSeleccionado->contenido_html ?? '' !!}
                </template>
            </main>

            <!-- Barra Lateral Derecha (Elementos, Estilos y Propiedades) -->
            <aside class="sisig-builder-sidebar">
                
                <!-- Pestañas de Navegación del Panel Lateral -->
                <div class="sisig-sidebar-tabs">
                    <button type="button" class="sisig-sidebar-tab active" data-tab="blocks" title="Elementos y componentes">
                        <i class="fas fa-cubes"></i>
                        <span>Elementos</span>
                    </button>
                    <button type="button" class="sisig-sidebar-tab" data-tab="styles" title="Diseño, tamaño y tipografía">
                        <i class="fas fa-paint-brush"></i>
                        <span>Estilos</span>
                    </button>
                    <button type="button" class="sisig-sidebar-tab" data-tab="traits" title="Ajustes del elemento">
                        <i class="fas fa-sliders-h"></i>
                        <span>Ajustes</span>
                    </button>
                </div>

                <!-- Botón Rápido para Subir Imagen desde el PC -->
                <div class="p-3 bg-slate-800/80 border-b border-slate-700/80 flex items-center justify-between gap-2">
                    <input type="file" id="input-upload-imagen" accept="image/png, image/jpeg, image/webp" class="hidden">
                    <button type="button" onclick="document.getElementById('input-upload-imagen').click()" 
                            class="w-full py-2 px-3 rounded-xl text-xs font-bold text-white bg-slate-700 hover:bg-emerald-700 border border-slate-600 hover:border-emerald-500 transition-all flex items-center justify-center gap-2 cursor-pointer shadow-sm">
                        <i class="fas fa-cloud-upload-alt text-emerald-400"></i>
                        <span id="label-btn-subir-imagen">Subir Imagen de tu PC</span>
                    </button>
                </div>

                <!-- Contenedores Dinámicos de GrapesJS -->
                <div class="sisig-sidebar-content">
                    <!-- Contenedor 1: Bloques (Canva Elements) -->
                    <div id="blocks-container" style="display: block;"></div>

                    <!-- Contenedor 2: Gestor de Estilos (Style Manager) -->
                    <div id="styles-container" style="display: none;"></div>

                    <!-- Contenedor 3: Propiedades y Traits (YouTube URL, Links, etc.) -->
                    <div id="traits-container" style="display: none;"></div>
                </div>
            </aside>
        </div>

    </div>

    <!-- GrapesJS JS (Local / Offline) -->
    <script src="{{ asset('modules/sisig/vendor/grapesjs/grapes.min.js') }}"></script>
    <!-- Lógica del Constructor Visual SISIG -->
    <script src="{{ asset('modules/sisig/js/grapes-builder.js') }}?v={{ @filemtime(public_path('modules/sisig/js/grapes-builder.js')) }}"></script>
</x-sisig::layouts.admin>
