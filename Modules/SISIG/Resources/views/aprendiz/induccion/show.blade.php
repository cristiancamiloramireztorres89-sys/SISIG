<x-sisig::layouts.admin title="Estudio: {{ $modulo->titulo }}">
    <div class="space-y-4 animate-fade-in pb-10 w-full max-w-[1600px] mx-auto">

        <!-- 1. Barra de Navegación Superior y Miga de Pan -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-200">
            <div class="flex items-center gap-2 text-xs sm:text-sm font-semibold text-slate-500">
                <a href="{{ route('sisig.aprendiz.dashboard') }}" class="hover:text-sena-green transition-colors">
                    <i class="fas fa-home"></i> Inicio
                </a>
                <span class="text-slate-300">/</span>
                <a href="{{ route('sisig.aprendiz.induccion.index') }}" class="hover:text-sena-green transition-colors">
                    Módulos de Inducción
                </a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-800 font-bold truncate max-w-xs sm:max-w-md">{{ $modulo->titulo }}</span>
            </div>

            <div class="flex items-center gap-2">
                @if($moduloAnterior)
                    <a href="{{ route('sisig.aprendiz.induccion.show', $moduloAnterior->id) }}" 
                       title="Módulo anterior: {{ $moduloAnterior->titulo }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition-all shadow-xs">
                        <i class="fas fa-chevron-left text-[10px]"></i>
                        <span class="hidden sm:inline">Anterior</span>
                    </a>
                @endif

                @if($moduloSiguiente)
                    <a href="{{ route('sisig.aprendiz.induccion.show', $moduloSiguiente->id) }}" 
                       title="Módulo siguiente: {{ $moduloSiguiente->titulo }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition-all shadow-xs">
                        <span class="hidden sm:inline">Siguiente</span>
                        <i class="fas fa-chevron-right text-[10px]"></i>
                    </a>
                @endif

                <a href="{{ route('sisig.aprendiz.induccion.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-black text-[#1A2B4C] bg-[#33FF57] hover:bg-[#2ae049] shadow-md shadow-emerald-500/20 transition-all cursor-pointer">
                    <i class="fas fa-th-large text-xs"></i>
                    <span>Ver Catálogo</span>
                </a>
            </div>
        </div>

        <!-- 2. Encabezado del Módulo (Estilo Banner SIG) -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#1A2B4C] via-[#1E3A5F] to-[#243B66] border border-slate-700/60 p-6 sm:p-7 text-white shadow-xl">
            <div class="absolute -top-24 -right-24 w-80 h-80 bg-[#33FF57]/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-[#00BCD4]/15 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="space-y-2 max-w-3xl">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight" style="color: #ffffff !important;">
                        {{ $modulo->titulo }}
                    </h1>

                    @if($modulo->descripcion)
                        <p class="text-slate-200 text-xs sm:text-sm font-normal leading-relaxed" style="color: #e2e8f0 !important;">
                            {{ $modulo->descripcion }}
                        </p>
                    @endif
                </div>

                <div class="flex items-center gap-3">
                    <div class="bg-white/10 backdrop-blur-md px-3.5 py-2 rounded-full border border-white/15 text-slate-200 text-xs font-semibold flex items-center gap-2">
                        <i class="far fa-clock text-[#33FF57]"></i>
                        <span>Lectura interactiva</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Selector de Temáticas de Inducción en Pestañas Horizontales -->
        <div class="space-y-2">
            <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 flex items-center gap-2">
                <i class="fas fa-layer-group text-[#2E6F40]"></i>
                <span>Temáticas de Inducción</span>
            </h3>

            <div class="flex flex-wrap items-center gap-2.5">
                @foreach($todosModulos as $mod)
                    <a href="{{ route('sisig.aprendiz.induccion.show', $mod->id) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs sm:text-sm font-bold border transition-all duration-200 {{ $mod->id == $modulo->id ? 'bg-[#1A2B4C] text-white border-[#1A2B4C] shadow-sm' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50 hover:border-slate-300' }}">
                        <i class="{{ $mod->id == $modulo->id ? 'fas fa-book-open text-[#33FF57]' : 'fas fa-file-alt text-slate-400' }}"></i>
                        <span>{{ $mod->titulo }}</span>
                        @if($mod->id == $modulo->id)
                            <span class="w-2 h-2 rounded-full bg-[#33FF57] flex-shrink-0"></span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>

        <!-- 4. Lector Didáctico HTML, Gráficos y Videos (Ancho Completo) -->
        <div class="w-full">
            <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200/90 shadow-sm relative overflow-hidden">
                
                @if(!empty($modulo->fondo_imagen))
                    <div class="absolute inset-0 pointer-events-none bg-center bg-cover bg-no-repeat transition-opacity"
                         style="background-image: url('{{ asset($modulo->fondo_imagen) }}'); opacity: {{ ($modulo->fondo_opacidad ?? 20) / 100 }}; z-index: 0;">
                    </div>
                @endif

                <div class="relative z-10">
                    @if(!empty($modulo->contenido_html))
                        <!-- Contenido Didáctico Formateado en HTML (Saneado contra XSS) -->
                        <div id="pantalla-estudio-contenido" class="contenido-didactico-sig text-slate-800 leading-relaxed space-y-4">
                            {!! \Modules\SISIG\Helpers\HtmlSecuritySanitizer::clean($modulo->contenido_html) !!}
                        </div>
                    @else
                        <!-- Mensaje en caso de que aún no se haya redactado el contenido -->
                        <div class="py-12 px-6 text-center rounded-2xl bg-white/80 backdrop-blur-xs flex flex-col items-center justify-center">
                            <div class="w-16 h-16 flex items-center justify-center text-3xl mb-4 text-amber-500">
                                <i class="fas fa-edit"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-800">Contenido en proceso de redacción</h3>
                            <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto mt-1 leading-relaxed">
                                El equipo editorial o instructor asignado está preparando el material didáctico, esquemas gráficos y videos de esta sección.
                            </p>
                        </div>
                    @endif

                </div>

                <!-- Barra de Navegación Inferior entre Módulos -->
                <div class="mt-10 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    @if($moduloAnterior)
                        <a href="{{ route('sisig.aprendiz.induccion.show', $moduloAnterior->id) }}" 
                           class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-sena-green transition-colors">
                            <i class="fas fa-arrow-left"></i>
                            <span>Anterior: {{ Str::limit($moduloAnterior->titulo, 35) }}</span>
                        </a>
                    @else
                        <div></div>
                    @endif

                    <div class="flex flex-wrap items-center justify-end gap-5 sm:gap-6">
                        @if(!empty($quicesConEstado) && $quicesConEstado->count() > 0)
                            @foreach($quicesConEstado as $quizItem)
                                <a href="{{ route('sisig.aprendiz.evaluaciones.iniciar', $quizItem->id_quiz) }}" 
                                   class="inline-flex items-center gap-2 text-xs font-bold text-sena-green hover:text-emerald-700 transition-colors">
                                    <span>Realizar Quiz: {{ Str::limit($quizItem->titulo, 35) }}</span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            @endforeach
                        @endif

                        @if($moduloSiguiente)
                            <a href="{{ route('sisig.aprendiz.induccion.show', $moduloSiguiente->id) }}" 
                               class="inline-flex items-center gap-2 text-xs font-bold text-sena-green hover:text-emerald-700 transition-colors">
                                <span>Siguiente: {{ Str::limit($moduloSiguiente->titulo, 35) }}</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        @else
                            <a href="{{ route('sisig.aprendiz.induccion.index') }}" 
                               class="inline-flex items-center gap-2 text-xs font-bold text-sena-green hover:text-emerald-700 transition-colors">
                                <span>Finalizar lectura de módulos</span>
                                <i class="fas fa-check-circle"></i>
                            </a>
                        @endif
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- Modal para Visualización Ampliada de Imágenes y Señalizaciones -->
    <div id="modalZoomImagen" class="fixed inset-0 z-[120] hidden flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm" onclick="cerrarZoomImagen()">
        <div class="relative max-w-4xl max-h-[90vh] bg-white rounded-3xl p-4 shadow-2xl overflow-hidden flex flex-col items-center" onclick="event.stopPropagation()">
            <div class="w-full flex justify-between items-center pb-3 border-b border-slate-100">
                <span class="text-xs font-bold text-slate-700 flex items-center gap-2">
                    <i class="fas fa-image text-sena-green"></i> Esquema Gráfico / Señalización SIG
                </span>
                <button type="button" onclick="cerrarZoomImagen()" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 hover:bg-slate-200 flex items-center justify-center transition-colors cursor-pointer" title="Cerrar ampliación">
                    <i class="fas fa-times text-xs leading-none"></i>
                </button>
            </div>
            <div class="mt-4 overflow-auto max-h-[75vh] w-full flex items-center justify-center">
                <img id="imagenZoomSrc" src="" alt="Esquema ampliado" class="max-w-full max-h-[70vh] rounded-2xl object-contain shadow-sm">
            </div>
        </div>
    </div>

    <!-- Modal para Reproducción de Videos In-Page (Sin salir de la página) -->
    <div id="modalVideoAprendiz" class="fixed inset-0 z-[130] hidden flex items-center justify-center p-4 bg-slate-950/85 backdrop-blur-md" onclick="cerrarVideoAprendiz()">
        <div class="relative w-full max-w-4xl bg-slate-900 border border-slate-700 rounded-3xl shadow-2xl overflow-hidden flex flex-col" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between px-5 py-3.5 bg-slate-800 border-b border-slate-700">
                <div class="flex items-center gap-2.5 text-white min-w-0">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#33FF57] animate-pulse flex-shrink-0"></span>
                    <i class="fab fa-youtube text-red-500 text-base flex-shrink-0"></i>
                    <h3 id="videoAprendizTitle" class="text-sm font-bold text-white truncate max-w-md">Video del Módulo</h3>
                </div>
                <button type="button" onclick="cerrarVideoAprendiz()" class="w-8 h-8 rounded-full bg-slate-700 hover:bg-rose-600 text-slate-300 hover:text-white flex items-center justify-center transition-colors cursor-pointer" title="Cerrar video">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
            <div class="relative w-full aspect-video bg-black flex items-center justify-center">
                <iframe id="iframeVideoAprendiz" class="w-full h-full" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen style="display: none;"></iframe>
                <video id="html5VideoAprendiz" class="w-full h-full" controls autoplay style="display: none;"></video>
            </div>
            <div class="px-5 py-2.5 bg-slate-950 flex items-center justify-between text-xs text-slate-400 border-t border-slate-800">
                <span class="flex items-center gap-1.5 text-[11px]">
                    <i class="fas fa-shield-alt text-[#33FF57]"></i> Reproducción integrada en el proceso de inducción SISIG
                </span>
                <button type="button" onclick="cerrarVideoAprendiz()" class="text-xs font-bold text-slate-300 hover:text-white px-3 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 transition-colors cursor-pointer">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <!-- Estilos específicos para el contenido didáctico (HTML, SVG, Imágenes y Videos Fluidos) -->
    <style>
        .contenido-didactico-sig {
            font-size: 0.95rem;
            line-height: 1.8;
            color: #334155;
            width: 100%;
        }
        .contenido-didactico-sig .sisig-canvas-presentation-wrapper {
            width: 100% !important;
            max-width: 100% !important;
            border: none !important;
            box-shadow: none !important;
            background: transparent !important;
            margin: 1rem 0 !important;
            overflow: visible !important;
        }
        .contenido-didactico-sig .sisig-canvas-presentation-wrapper > div {
            aspect-ratio: auto !important;
        }
        .contenido-didactico-sig svg {
            width: 100% !important;
            height: auto !important;
            max-width: 100% !important;
            display: block;
            margin: 1.5rem auto;
            border-radius: 1.25rem;
        }
        .contenido-didactico-sig h1 {
            font-size: 1.75rem;
            font-weight: 800;
            color: #0f172a;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 0.5rem;
        }
        .contenido-didactico-sig h2 {
            font-size: 1.4rem;
            font-weight: 800;
            color: #0f172a;
            margin-top: 1.25rem;
            margin-bottom: 0.5rem;
        }
        .contenido-didactico-sig h3 {
            font-size: 1.15rem;
            font-weight: 700;
            color: #1e293b;
            margin-top: 1rem;
            margin-bottom: 0.5rem;
        }
        .contenido-didactico-sig p {
            margin-bottom: 1rem;
        }
        .contenido-didactico-sig ul {
            list-style-type: disc;
            padding-left: 1.5rem;
            margin-bottom: 1rem;
        }
        .contenido-didactico-sig ol {
            list-style-type: decimal;
            padding-left: 1.5rem;
            margin-bottom: 1rem;
        }
        .contenido-didactico-sig li {
            margin-bottom: 0.35rem;
        }
        .contenido-didactico-sig blockquote {
            border-left: 4px solid #39A900;
            background-color: #f8fafc;
            padding: 0.75rem 1.25rem;
            border-radius: 0.5rem;
            margin: 1.25rem 0;
            color: #475569;
            font-style: italic;
        }
        /* Imágenes responsivas y con cursor de zoom */
        .contenido-didactico-sig img {
            max-width: 100%;
            height: auto;
            border-radius: 1rem;
            margin: 1.5rem auto;
            display: block;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            cursor: zoom-in;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .contenido-didactico-sig img:hover {
            transform: scale(1.01);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        /* Videos instructivos con relación de aspecto fluida (16:9) */
        .contenido-didactico-sig iframe,
        .contenido-didactico-sig video {
            width: 100%;
            aspect-ratio: 16 / 9;
            border-radius: 1rem;
            margin: 1.5rem 0;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
            display: block;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Habilitar visualización ampliada al hacer clic en cualquier imagen del contenido
            const imagenes = document.querySelectorAll('.contenido-didactico-sig img');
            const modal = document.getElementById('modalZoomImagen');
            const imgZoom = document.getElementById('imagenZoomSrc');

            imagenes.forEach(img => {
                img.addEventListener('click', function () {
                    if (imgZoom && modal) {
                        imgZoom.src = this.src;
                        modal.classList.remove('hidden');
                    }
                });
            });

            // Interceptar enlaces de video dentro del contenido didáctico para que abran en ventana sin salir de la página
            const enlacesVideo = document.querySelectorAll('.contenido-didactico-sig a[href*="youtube.com"], .contenido-didactico-sig a[href*="youtu.be"], .contenido-didactico-sig a[href*="vimeo.com"], .contenido-didactico-sig a[href*=".mp4"]');
            enlacesVideo.forEach(enlace => {
                enlace.addEventListener('click', function (e) {
                    e.preventDefault();
                    const url = this.getAttribute('href');
                    let embedUrl = url;
                    let type = 'youtube';

                    if (url.includes('youtube.com') || url.includes('youtu.be')) {
                        const match = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/|youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/i);
                        if (match && match[1]) {
                            embedUrl = `https://www.youtube-nocookie.com/embed/${match[1]}?autoplay=1&rel=0`;
                        }
                    } else if (url.includes('vimeo.com')) {
                        type = 'vimeo';
                        const match = url.match(/vimeo\.com\/(?:video\/)?([0-9]+)/);
                        if (match && match[1]) {
                            embedUrl = `https://player.vimeo.com/video/${match[1]}?autoplay=1`;
                        }
                    } else if (url.includes('.mp4')) {
                        type = 'mp4';
                    }

                    reproducirVideoAprendiz(embedUrl, this.textContent || 'Video de Aprendizaje', type);
                });
            });
        });

        function reproducirVideoAprendiz(embedUrl, title, type = 'youtube') {
            const modal = document.getElementById('modalVideoAprendiz');
            const titleEl = document.getElementById('videoAprendizTitle');
            const iframe = document.getElementById('iframeVideoAprendiz');
            const videoHtml5 = document.getElementById('html5VideoAprendiz');
            if (!modal) return;

            titleEl.textContent = title || 'Video del Módulo';
            if (type === 'mp4') {
                iframe.style.display = 'none';
                iframe.src = '';
                videoHtml5.style.display = 'block';
                videoHtml5.src = embedUrl;
                videoHtml5.play();
            } else {
                videoHtml5.pause();
                videoHtml5.src = '';
                videoHtml5.style.display = 'none';
                iframe.style.display = 'block';
                iframe.src = embedUrl;
            }
            modal.classList.remove('hidden');
        }

        function cerrarVideoAprendiz() {
            const modal = document.getElementById('modalVideoAprendiz');
            const iframe = document.getElementById('iframeVideoAprendiz');
            const videoHtml5 = document.getElementById('html5VideoAprendiz');
            if (iframe) iframe.src = '';
            if (videoHtml5) {
                videoHtml5.pause();
                videoHtml5.src = '';
            }
            if (modal) modal.classList.add('hidden');
        }

        function ampliarImagen(src) {
            const modal = document.getElementById('modalZoomImagen');
            const imgZoom = document.getElementById('imagenZoomSrc');
            if (imgZoom && modal && src) {
                imgZoom.src = src;
                modal.classList.remove('hidden');
            }
        }
        window.ampliarImagen = ampliarImagen;

        function cerrarZoomImagen() {
            const modal = document.getElementById('modalZoomImagen');
            if (modal) {
                modal.classList.add('hidden');
            }
        }
    </script>
</x-sisig::layouts.admin>
