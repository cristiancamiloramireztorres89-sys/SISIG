<x-sisig::layouts.admin title="Gestión de Contenido">
    <!-- Quill.js CSS (Local / Offline) -->
    <link href="{{ asset('modules/sisig/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <!-- Cropper.js CSS (Local / Offline) -->
    <link rel="stylesheet" href="{{ asset('modules/sisig/vendor/cropperjs/cropper.min.css') }}">
    <style>
        .cropper-view-box {
            outline: 2px solid #ffffff !important;
            outline-color: #ffffff !important;
        }
        .cropper-line {
            background-color: transparent !important;
        }
        .cropper-point {
            background-color: #ffffff !important;
        }
        .cropper-point.point-se, .cropper-point.point-sw, .cropper-point.point-nw, .cropper-point.point-ne {
            width: 14px !important;
            height: 14px !important;
            background-color: #10b981 !important;
            border-radius: 2px;
        }
        .cropper-dashed {
            border-color: rgba(255, 255, 255, 0.45) !important;
        }
        .cropper-modal {
            background-color: rgba(0, 0, 0, 0.88) !important;
        }

        .ql-toolbar.ql-snow {
            border: 1px solid #e2e8f0;
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
            background-color: #f8fafc;
            padding: 0.75rem 1rem;
        }
        
        /* Botón de Fondo en la barra de herramientas: perfectamente integrado como los demás iconos */
        .ql-snow .ql-toolbar button.ql-fondo_contenido {
            width: 28px !important;
            height: 24px !important;
            padding: 3px 5px !important;
            border: none !important;
            background: transparent !important;
            cursor: pointer !important;
            border-radius: 4px !important;
            transition: all 0.15s ease !important;
        }
        .ql-snow .ql-toolbar button.ql-fondo_contenido:hover {
            background-color: #ecfdf5 !important;
        }
        .ql-snow .ql-toolbar button.ql-fondo_contenido:hover .ql-stroke {
            stroke: #059669 !important;
        }
        .ql-snow .ql-toolbar button.ql-fondo_contenido:hover .ql-fill {
            fill: #059669 !important;
        }

        .ql-container.ql-snow {
            border: 1px solid #e2e8f0;
            border-bottom-left-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
            min-height: 440px;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            color: #334155;
            position: relative !important;
            background-color: transparent !important;
        }
        .ql-editor {
            padding: 1.5rem;
            position: relative !important;
            z-index: 1 !important;
            background-color: transparent !important;
        }
    </style>

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
                   class="w-10 h-10 rounded-2xl bg-white border border-slate-200 text-slate-500 hover:text-sena-green hover:border-emerald-200 hover:bg-emerald-50 transition-all flex items-center justify-center shadow-xs cursor-pointer flex-shrink-0 mt-1 sm:mt-0">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <div class="flex items-center gap-2 text-xs font-bold text-sena-green uppercase tracking-wider mb-1">
                        <i class="fas fa-file-alt"></i>
                        <span>{{ $isEditor ? 'Panel Editorial SISIG' : 'Administración SIG' }}</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex flex-wrap items-center gap-2">
                        <span>Edición de Contenido:</span>
                        <span class="text-sena-green">{{ $moduloSeleccionado->titulo }}</span>
                    </h1>
                    <p class="text-xs text-slate-500 mt-1 font-medium">
                        Redacta y formatea el material didáctico, guías y contenido interactivo de esta sección usando el editor enriquecido.
                    </p>
                </div>
            </div>
        </div>

        <!-- 2. Alerta de Permisos -->
        <div class="bg-emerald-50 border border-emerald-200 px-5 py-3.5 rounded-2xl text-xs sm:text-sm flex items-center gap-3 shadow-sm text-emerald-800">
            <div class="w-6 h-6 rounded-full bg-emerald-500 flex items-center justify-center text-white flex-shrink-0">
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
                   class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs sm:text-sm font-bold border transition-all 
                   {{ $moduloSeleccionado->id == $mod->id ? 'bg-emerald-50 text-sena-green border-emerald-200 shadow-sm' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
                   
                   @if($mod->estado == 'publicado')
                        <i class="fas fa-check-circle {{ $moduloSeleccionado->id == $mod->id ? 'text-sena-green' : 'text-emerald-500' }}"></i>
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
                        <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                            <i class="fas fa-edit text-sena-green"></i>
                            <span>Redacción de Contenido: <strong class="text-slate-900">{{ $moduloSeleccionado->titulo }}</strong></span>
                        </h2>
                    </div>
                    
                    <div class="flex items-center gap-2.5">
                        <!-- Botón de Fondo adicional en la cabecera para máxima comodidad -->
                        <button type="button" onclick="abrirModalConfigFondo()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-slate-700 bg-white border border-slate-200 hover:border-emerald-300 hover:text-emerald-700 hover:bg-emerald-50/50 shadow-xs transition-all cursor-pointer">
                            <i class="fas fa-image text-emerald-600"></i>
                            <span>Fondo del Contenido</span>
                        </button>

                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-white bg-sena-green hover:bg-emerald-600 shadow-sena hover:shadow-lg transition-all transform hover:-translate-y-0.5 cursor-pointer">
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
                    
                    <!-- Editor Div (limpio y nativo) -->
                    <div id="editor">
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

    <!-- Modal Interactivo de Recorte / Encuadre de Imagen de Fondo (Estilo Screenshot) -->
    <div id="modalRecortarFondo" style="display: none;" class="fixed inset-0 z-[130] flex flex-col bg-[#050811] text-white animate-fade-in select-none">
        <!-- Header superior discreto -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-800/80 bg-slate-950/60 z-10">
            <div class="flex items-center gap-2">
                <i class="fas fa-crop-alt text-emerald-400 text-sm"></i>
                <span class="text-xs sm:text-sm font-bold tracking-wide text-slate-200">Acomodar imagen de fondo</span>
            </div>
            <div class="text-xs text-slate-400 hidden sm:block">
                Arrastra para encuadrar o usa la rueda del ratón para zoom
            </div>
        </div>

        <!-- Área Central de Recorte con Cropper.js -->
        <div class="flex-grow relative flex items-center justify-center overflow-hidden p-4">
            <div class="w-full max-w-4xl h-[65vh] flex items-center justify-center">
                <img id="cropper-fondo-target" src="" alt="Ajustar fondo" class="max-w-full max-h-full block">
            </div>
        </div>

        <!-- Barra Inferior de Acciones (Fiel a la captura) -->
        <div class="px-8 py-5 border-t border-slate-800/80 bg-slate-950/90 flex items-center justify-between z-10">
            <!-- Botón Cancelar (Verde) -->
            <button type="button" onclick="cancelarRecorteFondo()" class="text-emerald-400 hover:text-emerald-300 font-bold text-sm sm:text-base px-3 py-2 transition-colors cursor-pointer">
                Cancelar
            </button>

            <!-- Herramientas centrales: Rotar y Zoom -->
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

            <!-- Botón OK (Verde) -->
            <button type="button" onclick="confirmarRecorteFondo()" class="text-emerald-400 hover:text-emerald-300 font-bold text-sm sm:text-base px-3 py-2 transition-colors cursor-pointer">
                OK
            </button>
        </div>
    </div>

    <!-- Cropper.js JS (Local / Offline) -->
    <script src="{{ asset('modules/sisig/vendor/cropperjs/cropper.min.js') }}"></script>
    <!-- Quill.js JS (Local / Offline) -->
    <script src="{{ asset('modules/sisig/vendor/quill/quill.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Registrar icono nativo de Quill para el botón de Fondo antes de inicializar
            var icons = Quill.import('ui/icons');
            icons['fondo_contenido'] = `
                <svg viewBox="0 0 18 18">
                    <rect class="ql-stroke" stroke-dasharray="2,1.5" height="13" width="14" x="2" y="2.5" rx="1.5"></rect>
                    <rect class="ql-stroke" height="7.5" width="9.5" x="4.25" y="5.25" rx="1"></rect>
                    <circle class="ql-fill" cx="6.5" cy="7.25" r="0.9"></circle>
                    <polyline class="ql-even ql-fill" points="5.5 11.5 5.5 10.5 7 9 8 10 10 8 12 10 12 11.5 5.5 11.5"></polyline>
                </svg>
            `;

            var toolbarOptions = [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['link', 'image', 'video', 'fondo_contenido'],   // Grupo multimedia con Fondo del contenido
                ['clean']                                         // remove formatting button
            ];

            var quill = new Quill('#editor', {
                modules: {
                    toolbar: {
                        container: toolbarOptions,
                        handlers: {
                            'fondo_contenido': function() {
                                window.abrirModalConfigFondo();
                            }
                        }
                    }
                },
                theme: 'snow',
                placeholder: 'Escribe aquí el contenido del módulo...'
            });

            // Inyectar capa de fondo detrás del contenido de .ql-editor dentro de .ql-container
            var qlContainer = quill.container;
            if (qlContainer) {
                qlContainer.style.position = 'relative';

                var fondoCapa = document.createElement('div');
                fondoCapa.id = 'editor-fondo-capa';
                fondoCapa.style.position = 'absolute';
                fondoCapa.style.inset = '0';
                fondoCapa.style.backgroundPosition = 'center';
                fondoCapa.style.backgroundSize = 'cover';
                fondoCapa.style.backgroundRepeat = 'no-repeat';
                fondoCapa.style.pointerEvents = 'none';
                fondoCapa.style.zIndex = '0';
                fondoCapa.style.transition = 'opacity 0.2s ease';
                fondoCapa.style.borderBottomLeftRadius = '0.75rem';
                fondoCapa.style.borderBottomRightRadius = '0.75rem';
                fondoCapa.style.opacity = '{{ ($moduloSeleccionado->fondo_opacidad ?? 20) / 100 }}';
                @if($moduloSeleccionado->fondo_imagen)
                    fondoCapa.style.backgroundImage = 'url("{{ asset($moduloSeleccionado->fondo_imagen) }}")';
                @endif

                qlContainer.insertBefore(fondoCapa, qlContainer.firstChild);
            }

            // Configurar el botón nativo en la barra de herramientas (limpio, sin texto que desborde)
            var btnFondo = document.querySelector('.ql-fondo_contenido');
            if (btnFondo) {
                btnFondo.setAttribute('type', 'button');
                btnFondo.setAttribute('title', 'Fondo del contenido (Imagen y Transparencia)');
                btnFondo.setAttribute('aria-label', 'Fondo del contenido');
                if (!btnFondo.querySelector('svg')) {
                    btnFondo.innerHTML = icons['fondo_contenido'];
                }
                btnFondo.onclick = function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    window.abrirModalConfigFondo();
                };
            }

            // Actualizar el input oculto y el contador de caracteres cuando el contenido cambia
            var hiddenInput = document.getElementById('contenido_html');
            var charCount = document.getElementById('charCount');

            // Set initial char count
            charCount.textContent = quill.getText().trim().length + ' caracteres';

            quill.on('text-change', function() {
                hiddenInput.value = quill.root.innerHTML;
                charCount.textContent = quill.getText().trim().length + ' caracteres';
            });

            // Antes de enviar el form, asegurar que el HTML esté en el input
            document.getElementById('editorForm').onsubmit = function() {
                hiddenInput.value = quill.root.innerHTML;
            };

            // Evento cambio de archivo para el fondo
            var inputArchivoFondo = document.getElementById('input-archivo-fondo');
            if (inputArchivoFondo) {
                inputArchivoFondo.addEventListener('change', function(e) {
                    var file = e.target.files[0];
                    if (file) {
                        if (!file.type.startsWith('image/')) {
                            if (window.mostrarAlertaBanner) {
                                window.mostrarAlertaBanner('Por favor selecciona un archivo de imagen válido.', 'error');
                            }
                            return;
                        }
                        var reader = new FileReader();
                        reader.onload = function(evt) {
                            currentFondoRawSrc = evt.target.result;
                            iniciarCropperFondo(currentFondoRawSrc);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Control Deslizante de Transparencia (En vivo en el editor)
            var sliderOpacidad = document.getElementById('slider-opacidad-control');
            if (sliderOpacidad) {
                sliderOpacidad.addEventListener('input', function(e) {
                    var val = e.target.value;
                    var labelDisplay = document.getElementById('label-opacidad-display');
                    if (labelDisplay) labelDisplay.textContent = val + '%';
                    
                    var inputHidden = document.getElementById('fondo_opacidad');
                    if (inputHidden) inputHidden.value = val;
                    
                    var editorFondoCapa = document.getElementById('editor-fondo-capa');
                    if (editorFondoCapa) {
                        editorFondoCapa.style.opacity = (val / 100);
                    }
                });
            }
        });

        // Click global con delegación para máxima confiabilidad
        document.addEventListener('click', function(e) {
            if (e.target.closest('.ql-fondo_contenido')) {
                e.preventDefault();
                e.stopPropagation();
                window.abrirModalConfigFondo();
            }
        });

        // ==========================================
        // FUNCIONES DE CONTROL DEL MODAL Y CROPPER
        // ==========================================
        let cropperFondoInstance = null;
        let currentFondoRawSrc = null;

        window.abrirModalConfigFondo = function() {
            var modal = document.getElementById('modalConfigFondoContenido');
            if (modal) {
                modal.style.display = 'flex';
                if (window.lockBodyScroll) window.lockBodyScroll(true);
            }
        };

        window.cerrarModalConfigFondo = function() {
            var modal = document.getElementById('modalConfigFondoContenido');
            if (modal) {
                modal.style.display = 'none';
                if (window.lockBodyScroll) window.lockBodyScroll(false);
            }
        };

        function iniciarCropperFondo(imgSrc) {
            var cropperFondoTarget = document.getElementById('cropper-fondo-target');
            var modalRecortarFondo = document.getElementById('modalRecortarFondo');
            
            if (cropperFondoTarget && modalRecortarFondo) {
                cropperFondoTarget.src = imgSrc;
                modalRecortarFondo.style.display = 'flex';
                if (window.lockBodyScroll) window.lockBodyScroll(true);

                if (cropperFondoInstance) {
                    cropperFondoInstance.destroy();
                }

                cropperFondoInstance = new Cropper(cropperFondoTarget, {
                    aspectRatio: 16 / 9,
                    viewMode: 1,
                    guides: true,
                    center: true,
                    highlight: false,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    autoCropArea: 0.9,
                    responsive: true,
                    background: false
                });
            }
        }

        function cropperFondoRotar(grados) {
            if (cropperFondoInstance) {
                cropperFondoInstance.rotate(grados);
            }
        }

        function cropperFondoZoom(ratio) {
            if (cropperFondoInstance) {
                cropperFondoInstance.zoom(ratio);
            }
        }

        function cancelarRecorteFondo() {
            var modalRecortarFondo = document.getElementById('modalRecortarFondo');
            if (modalRecortarFondo) {
                modalRecortarFondo.style.display = 'none';
                if (window.lockBodyScroll) window.lockBodyScroll(false);
            }
            if (cropperFondoInstance) {
                cropperFondoInstance.destroy();
                cropperFondoInstance = null;
            }
            var inputArchivo = document.getElementById('input-archivo-fondo');
            if (inputArchivo) inputArchivo.value = '';
        }

        function confirmarRecorteFondo() {
            if (!cropperFondoInstance) return;

            var canvas = cropperFondoInstance.getCroppedCanvas({
                width: 1600,
                height: 900,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high'
            });

            var croppedBase64 = canvas.toDataURL('image/png');

            var inputHiddenFondo = document.getElementById('fondo_imagen_recortada');
            if (inputHiddenFondo) inputHiddenFondo.value = croppedBase64;

            var inputHiddenQuitar = document.getElementById('quitar_fondo');
            if (inputHiddenQuitar) inputHiddenQuitar.value = '0';

            var previewMiniatura = document.getElementById('preview-fondo-miniatura');
            if (previewMiniatura) previewMiniatura.src = croppedBase64;

            var dropzone = document.getElementById('dropzone-fondo-modulo');
            if (dropzone) dropzone.classList.add('hidden');

            var previewContainer = document.getElementById('preview-fondo-container');
            if (previewContainer) previewContainer.classList.remove('hidden');

            var editorFondoCapa = document.getElementById('editor-fondo-capa');
            var sliderOpacidad = document.getElementById('slider-opacidad-control');
            if (editorFondoCapa) {
                editorFondoCapa.style.backgroundImage = 'url("' + croppedBase64 + '")';
                var op = sliderOpacidad ? sliderOpacidad.value : 20;
                editorFondoCapa.style.opacity = (op / 100);
            }

            var modalRecortarFondo = document.getElementById('modalRecortarFondo');
            if (modalRecortarFondo) {
                modalRecortarFondo.style.display = 'none';
                if (window.lockBodyScroll) window.lockBodyScroll(false);
            }

            cropperFondoInstance.destroy();
            cropperFondoInstance = null;
        }

        function quitarFondoContenido() {
            var inputHiddenQuitar = document.getElementById('quitar_fondo');
            if (inputHiddenQuitar) inputHiddenQuitar.value = '1';

            var inputHiddenFondo = document.getElementById('fondo_imagen_recortada');
            if (inputHiddenFondo) inputHiddenFondo.value = '';

            var inputArchivo = document.getElementById('input-archivo-fondo');
            if (inputArchivo) inputArchivo.value = '';

            currentFondoRawSrc = null;

            var previewMiniatura = document.getElementById('preview-fondo-miniatura');
            if (previewMiniatura) previewMiniatura.src = '';

            var previewContainer = document.getElementById('preview-fondo-container');
            if (previewContainer) previewContainer.classList.add('hidden');

            var dropzone = document.getElementById('dropzone-fondo-modulo');
            if (dropzone) dropzone.classList.remove('hidden');

            var editorFondoCapa = document.getElementById('editor-fondo-capa');
            if (editorFondoCapa) {
                editorFondoCapa.style.backgroundImage = 'none';
            }
        }
    </script>
</x-sisig::layouts.admin>
