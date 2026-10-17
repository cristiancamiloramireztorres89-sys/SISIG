<x-sisig::layouts.admin title="Estudio: {{ $modulo->titulo }}">
    <div class="space-y-6 animate-fade-in pb-12 w-full max-w-[1600px] mx-auto">

        <!-- 1. Barra de Navegación Superior y Miga de Pan -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
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
                       class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition-all shadow-xs">
                        <i class="fas fa-chevron-left text-[10px]"></i>
                        <span class="hidden sm:inline">Anterior</span>
                    </a>
                @endif

                @if($moduloSiguiente)
                    <a href="{{ route('sisig.aprendiz.induccion.show', $moduloSiguiente->id) }}" 
                       title="Módulo siguiente: {{ $moduloSiguiente->titulo }}"
                       class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition-all shadow-xs">
                        <span class="hidden sm:inline">Siguiente</span>
                        <i class="fas fa-chevron-right text-[10px]"></i>
                    </a>
                @endif

                <a href="{{ route('sisig.aprendiz.induccion.index') }}" 
                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-sena-green hover:bg-emerald-600 shadow-sena transition-all">
                    <i class="fas fa-th-large text-xs"></i>
                    <span>Ver Catálogo</span>
                </a>
            </div>
        </div>

        <!-- 2. Encabezado del Módulo (Estilo Banner SIG) -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#001A29] via-[#002F48] to-[#004266] border border-slate-700/60 p-6 sm:p-8 text-white shadow-xl">
            <div class="absolute -top-24 -right-24 w-80 h-80 bg-sena-green/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-sky-500/15 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-2.5 max-w-3xl">
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight">
                        {{ $modulo->titulo }}
                    </h1>

                    @if($modulo->descripcion)
                        <p class="text-slate-300 text-xs sm:text-sm font-normal leading-relaxed">
                            {{ $modulo->descripcion }}
                        </p>
                    @endif
                </div>

                <div class="flex md:flex-col items-center md:items-end justify-between gap-3 text-right">
                    <div class="bg-white/10 backdrop-blur-md px-4 py-2.5 rounded-2xl border border-white/15 text-slate-200 text-xs font-semibold flex items-center gap-2">
                        <i class="far fa-clock text-sena-green"></i>
                        <span>Lectura interactiva</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Contenedor Principal de Estudio: 2 Columnas -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-start">
            
            <!-- Columna Izquierda: Lector Didáctico HTML, Gráficos y Videos (3 Columnas) -->
            <div class="lg:col-span-3 space-y-6">
                
                <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200/90 shadow-sm relative">
                    
                    @if(!empty($modulo->contenido_html))
                        <!-- Contenido Didáctico Formateado en HTML -->
                        <div id="pantalla-estudio-contenido" class="contenido-didactico-sig text-slate-800 leading-relaxed space-y-4">
                            {!! $modulo->contenido_html !!}
                        </div>
                    @else
                        <!-- Mensaje en caso de que aún no se haya redactado el contenido -->
                        <div class="py-12 px-6 text-center rounded-2xl bg-white flex flex-col items-center justify-center">
                            <div class="w-16 h-16 flex items-center justify-center text-3xl mb-4 text-amber-500">
                                <i class="fas fa-edit"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-800">Contenido en proceso de redacción</h3>
                            <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto mt-1 leading-relaxed">
                                El equipo editorial o instructor asignado está preparando el material didáctico, esquemas gráficos y videos de esta sección.
                            </p>
                        </div>
                    @endif

                    <!-- Barra de Navegación Inferior entre Módulos -->
                    <div class="mt-10 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                        @if($moduloAnterior)
                            <a href="{{ route('sisig.aprendiz.induccion.show', $moduloAnterior->id) }}" 
                               class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-sena-green transition-colors">
                                <i class="fas fa-arrow-left"></i>
                                <span>Anterior: {{ Str::limit($moduloAnterior->titulo, 25) }}</span>
                            </a>
                        @else
                            <div></div>
                        @endif

                        @if($moduloSiguiente)
                            <a href="{{ route('sisig.aprendiz.induccion.show', $moduloSiguiente->id) }}" 
                               class="inline-flex items-center gap-2 text-xs font-bold text-sena-green hover:text-emerald-700 transition-colors">
                                <span>Siguiente: {{ Str::limit($moduloSiguiente->titulo, 25) }}</span>
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

            <!-- Columna Derecha: Menú Lateral de Temáticas y Recursos (1 Columna) -->
            <div class="space-y-6">

                <!-- 1. Temáticas del Proceso de Inducción -->
                <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/90 shadow-sm">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 mb-4 flex items-center gap-2">
                        <i class="fas fa-list-ol text-sena-green"></i>
                        <span>Temáticas de Inducción</span>
                    </h3>

                    <div class="space-y-2">
                        @foreach($todosModulos as $mod)
                            <a href="{{ route('sisig.aprendiz.induccion.show', $mod->id) }}" 
                               class="flex items-center justify-between p-3 rounded-2xl text-xs font-bold transition-all {{ $mod->id == $modulo->id ? 'bg-sena-green text-white shadow-sena' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-100' }}">
                                <div class="flex items-center gap-2.5 truncate">
                                    <i class="{{ $mod->id == $modulo->id ? 'fas fa-book-open text-white' : 'fas fa-file-alt text-slate-400' }}"></i>
                                    <span class="truncate">{{ $mod->titulo }}</span>
                                </div>
                                @if($mod->id == $modulo->id)
                                    <span class="w-2 h-2 rounded-full bg-white flex-shrink-0 animate-ping"></span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- 2. Recursos Clave de Estudio -->
                <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/90 shadow-sm space-y-4">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 flex items-center gap-2">
                        <i class="fas fa-lightbulb text-amber-500"></i>
                        <span>Recomendaciones</span>
                    </h3>

                    <ul class="text-xs text-slate-600 space-y-3 leading-relaxed">
                        <li class="flex items-start gap-2.5">
                            <i class="fas fa-check text-sena-green mt-0.5 flex-shrink-0"></i>
                            <span>Lee con atención las <strong>políticas</strong> y <strong>normas de bioseguridad</strong> descritas.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fas fa-image text-sky-500 mt-0.5 flex-shrink-0"></i>
                            <span>Haz clic sobre los <strong>esquemas gráficos y señalizaciones</strong> para verlos en detalle.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fas fa-video text-purple-500 mt-0.5 flex-shrink-0"></i>
                            <span>Reproduce los <strong>videos explicativos</strong> sin salir de la plataforma para consolidar tus aprendizajes.</span>
                        </li>
                    </ul>
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
                <button type="button" onclick="cerrarZoomImagen()" class="w-8 h-8 rounded-xl bg-slate-100 text-slate-500 hover:bg-slate-200 flex items-center justify-center transition-colors">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
            <div class="mt-4 overflow-auto max-h-[75vh] w-full flex items-center justify-center">
                <img id="imagenZoomSrc" src="" alt="Esquema ampliado" class="max-w-full max-h-[70vh] rounded-2xl object-contain shadow-sm">
            </div>
        </div>
    </div>

    <!-- Estilos específicos para el contenido didáctico (HTML, Imágenes y Videos Fluidos) -->
    <style>
        .contenido-didactico-sig {
            font-size: 0.95rem;
            line-height: 1.8;
            color: #334155;
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
        });

        function cerrarZoomImagen() {
            const modal = document.getElementById('modalZoomImagen');
            if (modal) {
                modal.classList.add('hidden');
            }
        }
    </script>
</x-sisig::layouts.admin>
