<x-sisig::layouts.admin title="Gestión de Contenido">
    <!-- Quill.js CSS (Local / Offline) -->
    <link href="{{ asset('modules/sisig/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <!-- Cropper.js CSS (Local / Offline) -->
    <link rel="stylesheet" href="{{ asset('modules/sisig/vendor/cropperjs/cropper.min.css') }}">
    <!-- Estilos personalizados del Editor SISIG (Separación de capas) -->
    <link rel="stylesheet" href="{{ asset('modules/sisig/css/contenido-editor.css') }}">

@php
    $routePrefix = $routePrefix ?? (request()->is('*editor*') ? 'sisig.editor' : 'sisig.admin');
    $isEditor = $routePrefix === 'sisig.editor';
    $tabRouteName = $isEditor ? 'sisig.editor.contenido.edit' : 'sisig.admin.modulos.contenido';
    $updateRouteName = $isEditor ? 'sisig.editor.contenido.update' : 'sisig.admin.modulos.contenido.update';
    $backRouteName = $isEditor ? 'sisig.editor.contenido.index' : 'sisig.admin.modulos.index';
@endphp

    <div class="space-y-6 sm:space-y-8 animate-fade-in pb-12">
        
        <!-- 1. Encabezado de la Sección (Estilo Limpio y Unificado) -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-5 border-b border-slate-200">
            <div class="flex items-start sm:items-center gap-3.5">
                <a href="{{ route($backRouteName) }}" 
                   title="Volver a Contenidos" 
                   class="w-10 h-10 rounded-2xl bg-white border border-slate-200 text-slate-500 hover:text-[#2E6F40] hover:border-emerald-200 hover:bg-emerald-50 transition-all flex items-center justify-center shadow-xs cursor-pointer flex-shrink-0 mt-1 sm:mt-0">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <div class="flex items-center gap-2 text-xs font-bold text-[#2E6F40] uppercase tracking-wider mb-1">
                        <i class="fas fa-file-alt"></i>
                        <span>{{ $isEditor ? 'Panel Editorial SISIG' : 'Administración SIG' }}</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-[#343A40] tracking-tight flex flex-wrap items-center gap-2">
                        <span>Edición de Contenido:</span>
                        <span class="text-[#2E6F40]">{{ $moduloSeleccionado->titulo }}</span>
                    </h1>
                    <p class="text-xs text-slate-500 mt-1 font-medium">
                        Redacta y formatea el material didáctico, guías y contenido interactivo de esta sección usando el editor enriquecido.
                    </p>
                </div>
            </div>
        </div>

        <!-- 2. Alerta de Permisos -->
        <div class="bg-emerald-50 border border-emerald-200 px-5 py-3.5 rounded-2xl text-xs sm:text-sm flex items-center gap-3 shadow-sm text-emerald-800">
            <div class="w-6 h-6 rounded-full bg-[#2E6F40] flex items-center justify-center text-white flex-shrink-0">
                <i class="fas fa-info text-xs"></i>
            </div>
            <p>
                <span class="font-bold">Editor interactivo activo:</span> Puedes añadir texto con formato, enlaces, listas, imágenes, videos y configurar una imagen de fondo con transparencia.
            </p>
        </div>

        <!-- 3. Título de Secciones -->
        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-4 flex items-center gap-2">
            <i class="fas fa-layer-group text-slate-400"></i> SELECCIONA LA SECCIÓN A EDITAR
        </h3>

        <!-- 4. Pestañas de Módulos (Tabs Dinámicos) -->
        <div class="flex flex-wrap gap-2.5 pb-2">
            @foreach($modulos as $mod)
                <a href="{{ route($tabRouteName, $mod->id) }}" 
                   class="flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold border transition-all 
                   {{ $moduloSeleccionado->id == $mod->id ? 'bg-[#1A2B4C] text-white border-[#1A2B4C] shadow-sm' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
                   
                   @if($mod->estado == 'publicado')
                        <i class="fas fa-check-circle {{ $moduloSeleccionado->id == $mod->id ? 'text-[#33FF57]' : 'text-emerald-500' }}"></i>
                   @elseif($mod->estado == 'inactivo')
                        <i class="fas fa-lock text-slate-400"></i>
                   @else
                        <i class="fas fa-pencil-alt text-amber-500"></i>
                   @endif
                   
                   <span>{{ $mod->titulo }}</span>
                </a>
            @endforeach
        </div>

        <!-- 5. Editor de Contenido -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <!-- Header del Editor -->
            <form action="{{ route($updateRouteName, $moduloSeleccionado->id) }}" method="POST" id="editorForm">
                @csrf
                @method('PUT')
                
                <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <h2 class="text-base font-bold text-[#343A40] flex items-center gap-2">
                            <i class="fas fa-edit text-[#2E6F40]"></i>
                            <span>Redacción de Contenido: <strong class="text-[#1A2B4C]">{{ $moduloSeleccionado->titulo }}</strong></span>
                        </h2>
                    </div>
                    
                    <div class="flex items-center gap-2.5">
                        <!-- Botón de Fondo adicional en la cabecera para máxima comodidad -->
                        <button type="button" onclick="abrirModalConfigFondo()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:border-emerald-300 hover:text-emerald-700 hover:bg-emerald-50/50 shadow-xs transition-all cursor-pointer">
                            <i class="fas fa-image text-emerald-600"></i>
                            <span>Fondo del Contenido</span>
                        </button>

                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full text-xs font-black text-[#1A2B4C] bg-[#33FF57] hover:bg-[#2ae049] shadow-md shadow-emerald-500/20 transition-all transform hover:-translate-y-0.5 cursor-pointer">
                            <i class="fas fa-save text-xs"></i> <span>Guardar Cambios</span>
                        </button>
                    </div>
                </div>

                <!-- Contenedor Quill -->
                <div class="p-6">
                    <!-- Campos ocultos para enviar el contenido y fondo -->
                    <input type="hidden" name="contenido_html" id="contenido_html" value="{{ $moduloSeleccionado->contenido_html }}">
                    <input type="hidden" name="fondo_imagen_recortada" id="fondo_imagen_recortada">
                    <input type="hidden" name="fondo_opacidad" id="fondo_opacidad" value="{{ $moduloSeleccionado->fondo_opacidad ?? 20 }}">
                    <input type="hidden" name="quitar_fondo" id="quitar_fondo" value="0">
                    
                    <!-- Editor Div con atributos data para desacoplamiento limpio de backend y frontend -->
                    <div id="editor" 
                         data-fondo-opacidad="{{ ($moduloSeleccionado->fondo_opacidad ?? 20) / 100 }}"
                         data-fondo-imagen="{{ $moduloSeleccionado->fondo_imagen ? asset($moduloSeleccionado->fondo_imagen) : '' }}">
                        {!! $moduloSeleccionado->contenido_html !!}
                    </div>
                </div>

                <!-- Footer del Editor -->
                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between text-xs font-medium text-slate-500 bg-slate-50/50 rounded-b-2xl">
                    <span id="charCount">0 caracteres</span>
                    <span class="flex items-center gap-1.5 text-emerald-600">
                        <i class="fas fa-check-circle"></i> Último guardado: {{ $moduloSeleccionado->updated_at->diffForHumans() }}
                    </span>
                </div>
            </form>
        </div>

    </div>

    <!-- Modal para Configurar Fondo y Transparencia del Contenido -->
    <div id="modalConfigFondoContenido" style="display: none;" class="fixed inset-0 z-[110] flex items-center justify-center p-4">
        <!-- Backdrop oscuro -->
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="cerrarModalConfigFondo()"></div>

        <!-- Contenedor del Modal -->
        <div class="relative w-full max-w-lg bg-white rounded-3xl p-6 sm:p-7 shadow-2xl border border-slate-200 animate-scale-in max-h-[90vh] overflow-y-auto">
            
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-sena-green flex items-center justify-center text-lg shadow-sm border border-emerald-100">
                        <i class="fas fa-image"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Fondo del Contenido</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Añade una imagen de fondo y ajusta su transparencia</p>
                    </div>
                </div>
                <button type="button" onclick="cerrarModalConfigFondo()" class="text-slate-400 hover:text-slate-600 p-2 rounded-xl hover:bg-slate-100 transition-colors cursor-pointer">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <div class="mt-5 space-y-5">
                <input type="file" id="input-archivo-fondo" accept="image/png, image/jpeg, image/jpg, image/webp" class="hidden">

                <!-- Dropzone / Selector de Imagen -->
                <div id="dropzone-fondo-modulo" onclick="document.getElementById('input-archivo-fondo').click()"
                     class="{{ $moduloSeleccionado->fondo_imagen ? 'hidden' : '' }} border-2 border-dashed border-slate-200 hover:border-emerald-400 bg-slate-50 hover:bg-emerald-50/40 rounded-2xl p-6 text-center cursor-pointer transition-all group">
                    <div class="flex flex-col items-center justify-center gap-2">
                        <div class="w-12 h-12 rounded-2xl bg-white shadow-xs border border-slate-200 flex items-center justify-center text-slate-400 group-hover:text-sena-green group-hover:scale-110 transition-all">
                            <i class="fas fa-cloud-upload-alt text-xl"></i>
                        </div>
                        <div class="text-xs sm:text-sm font-bold text-slate-700 group-hover:text-emerald-700">
                            Haz clic para seleccionar la imagen de fondo
                        </div>
                        <p class="text-[11px] text-slate-400">Podrás encuadrarla y recortarla a tu gusto (JPG, PNG, WEBP)</p>
                    </div>
                </div>

                <!-- Preview de la imagen de fondo actual/nueva -->
                <div id="preview-fondo-container" class="{{ $moduloSeleccionado->fondo_imagen ? '' : 'hidden' }} p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                    <div class="flex items-center gap-4">
                        <img id="preview-fondo-miniatura" 
                             src="{{ $moduloSeleccionado->fondo_imagen ? asset($moduloSeleccionado->fondo_imagen) : '' }}" 
                             alt="Fondo actual" 
                             class="w-20 h-16 object-cover rounded-xl border border-slate-200 shadow-sm flex-shrink-0 bg-white">
                        <div class="flex-grow min-w-0">
                            <div class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                <i class="fas fa-check-circle text-emerald-600 text-xs"></i>
                                <span>Imagen de fondo asignada</span>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-0.5">Se verá detrás del texto con la transparencia elegida.</p>
                            <div class="flex items-center gap-2 mt-2">
                                <button type="button" onclick="document.getElementById('input-archivo-fondo').click()" class="text-xs text-sena-green hover:underline font-bold flex items-center gap-1 cursor-pointer">
                                    <i class="fas fa-exchange-alt"></i> Cambiar imagen
                                </button>
                                <span class="text-slate-300">|</span>
                                <button type="button" onclick="quitarFondoContenido()" class="text-xs text-rose-500 hover:text-rose-700 font-medium flex items-center gap-1 cursor-pointer">
                                    <i class="fas fa-trash-alt"></i> Quitar fondo
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Control Deslizante de Transparencia / Opacidad -->
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl space-y-2.5">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                            <i class="fas fa-adjust text-slate-400"></i>
                            <span>Nivel de Transparencia</span>
                        </label>
                        <span id="label-opacidad-display" class="text-sm font-extrabold text-sena-green bg-emerald-50 px-2.5 py-0.5 rounded-lg border border-emerald-200">
                            {{ $moduloSeleccionado->fondo_opacidad ?? 20 }}%
                        </span>
                    </div>

                    <input type="range" id="slider-opacidad-control" min="5" max="95" step="5" 
                           value="{{ $moduloSeleccionado->fondo_opacidad ?? 20 }}" 
                           class="w-full accent-sena-green cursor-pointer h-2 bg-slate-200 rounded-lg">
                    
                    <div class="flex justify-between text-[10px] text-slate-400 font-medium">
                        <span>10% (Muy sutil)</span>
                        <span>20% (Recomendado)</span>
                        <span>50% (Marcado)</span>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="cerrarModalConfigFondo()" class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-slate-600 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 transition-all cursor-pointer">
                        Listo
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Interactivo de Recorte / Encuadre de Imagen de Fondo -->
    <div id="modalRecortarFondo" style="display: none;" class="fixed inset-0 z-[130] flex flex-col bg-[#050811] text-white animate-fade-in select-none">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-800/80 bg-slate-950/60 z-10">
            <div class="flex items-center gap-2">
                <i class="fas fa-crop-alt text-emerald-400 text-sm"></i>
                <span class="text-xs sm:text-sm font-bold tracking-wide text-slate-200">Acomodar imagen de fondo</span>
            </div>
            <div class="text-xs text-slate-400 hidden sm:block">
                Arrastra para encuadrar o usa la rueda del ratón para zoom
            </div>
        </div>

        <div class="flex-grow relative flex items-center justify-center overflow-hidden p-4">
            <div class="w-full max-w-4xl h-[65vh] flex items-center justify-center">
                <img id="cropper-fondo-target" src="" alt="Ajustar fondo" class="max-w-full max-h-full block">
            </div>
        </div>

        <div class="px-8 py-5 border-t border-slate-800/80 bg-slate-950/90 flex items-center justify-between z-10">
            <button type="button" onclick="cancelarRecorteFondo()" class="text-emerald-400 hover:text-emerald-300 font-bold text-sm sm:text-base px-3 py-2 transition-colors cursor-pointer">
                Cancelar
            </button>

            <div class="flex items-center gap-3">
                <button type="button" onclick="cropperFondoRotar(-90)" title="Girar 90° hacia la izquierda" class="w-10 h-10 rounded-full bg-slate-900 border border-slate-700 hover:border-slate-500 text-slate-300 hover:text-white flex items-center justify-center transition-all cursor-pointer">
                    <i class="fas fa-undo-alt text-xs"></i>
                </button>
                <button type="button" onclick="cropperFondoRotar(90)" title="Rotar imagen" class="w-12 h-12 rounded-full bg-slate-800 border border-slate-600 hover:border-emerald-400 text-white hover:text-emerald-400 flex items-center justify-center shadow-lg transition-all cursor-pointer text-base">
                    <i class="fas fa-sync-alt"></i>
                </button>
                <button type="button" onclick="cropperFondoZoom(0.1)" title="Acercar (Zoom +)" class="w-10 h-10 rounded-full bg-slate-900 border border-slate-700 hover:border-slate-500 text-slate-300 hover:text-white flex items-center justify-center transition-all cursor-pointer">
                    <i class="fas fa-search-plus text-xs"></i>
                </button>
                <button type="button" onclick="cropperFondoZoom(-0.1)" title="Alejar (Zoom -)" class="w-10 h-10 rounded-full bg-slate-900 border border-slate-700 hover:border-slate-500 text-slate-300 hover:text-white flex items-center justify-center transition-all cursor-pointer">
                    <i class="fas fa-search-minus text-xs"></i>
                </button>
            </div>

            <button type="button" onclick="confirmarRecorteFondo()" class="text-emerald-400 hover:text-emerald-300 font-bold text-sm sm:text-base px-3 py-2 transition-colors cursor-pointer">
                OK
            </button>
        </div>
    </div>

    <!-- Cropper.js JS (Local / Offline) -->
    <script src="{{ asset('modules/sisig/vendor/cropperjs/cropper.min.js') }}"></script>
    <!-- Quill.js JS (Local / Offline) -->
    <script src="{{ asset('modules/sisig/vendor/quill/quill.min.js') }}"></script>
    <!-- Lógica interactiva del Editor SISIG (Separación de capas) -->
    <script src="{{ asset('modules/sisig/js/contenido-editor.js') }}"></script>
</x-sisig::layouts.admin>
