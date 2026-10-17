<x-sisig::layouts.admin title="Módulos de Inducción SIG">
    <div class="space-y-6 sm:space-y-8 animate-fade-in pb-12 w-full max-w-[1600px] mx-auto">

        <!-- 1. Encabezado de la Sección (Estilo Limpio y Unificado) -->
        <div class="pb-5 border-b border-slate-200">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                    <i class="fas fa-layer-group text-sena-green"></i>
                    <span>Módulos de Inducción SIG</span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Espacio formativo donde podrás estudiar guías pedagógicas, visualizar infografías normativas y reproducir videos instructivos del SIG.
                </p>
            </div>
        </div>

        <!-- 2. Tarjetas de Métricas Clave (KPIs) con Estilo Premium -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            
            <!-- Total Módulos -->
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Total Creados</span>
                    <i class="fas fa-layer-group text-2xl text-blue-600 group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight">{{ $totalModulos }}</h3>
                    <div class="mt-2.5 flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Módulos en el sistema</span>
                    </div>
                </div>
            </div>

            <!-- Publicados / Disponibles -->
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-sena-green to-[#237000]"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Publicados</span>
                    <i class="fas fa-eye text-2xl text-sena-green group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight">{{ $modulos->where('estado', 'publicado')->count() }}</h3>
                    <div class="mt-2.5 flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Visibles para aprendices</span>
                    </div>
                </div>
            </div>

            <!-- Con Contenido Didáctico -->
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-amber-400 to-orange-500"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">En Revisión</span>
                    <i class="fas fa-pencil-alt text-2xl text-amber-500 group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight">{{ $modulos->where('estado', 'revision')->count() }}</h3>
                    <div class="mt-2.5 flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Borradores pendientes</span>
                    </div>
                </div>
            </div>

            <!-- Inactivos -->
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-rose-500 to-red-600"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Inactivos</span>
                    <i class="fas fa-archive text-2xl text-rose-500 group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight">{{ $modulos->where('estado', 'inactivo')->count() }}</h3>
                    <div class="mt-2.5 flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Ocultos o dados de baja</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Barra de Búsqueda y Filtros Estilo Premium -->
        <div class="bg-white rounded-3xl p-4 sm:p-5 border border-slate-200/90 shadow-sm flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="relative w-full md:w-96">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" id="filtroModulosInput" placeholder="Buscar módulo por nombre o norma..." 
                       class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-2 focus:ring-sena-green/50 transition-all outline-none text-slate-700 placeholder-slate-400 shadow-inner">
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <select id="filtroNormaSelect" class="bg-slate-50 border border-slate-200 text-slate-700 font-medium text-sm rounded-2xl px-4 py-3 focus:ring-2 focus:ring-sena-green/50 outline-none w-full sm:w-auto cursor-pointer transition-all hover:bg-slate-100">
                    <option value="">Todas las secciones</option>
                    @foreach($modulos->pluck('norma')->unique()->filter() as $norma)
                        <option value="{{ strtolower($norma) }}">{{ $norma }}</option>
                    @endforeach
                </select>

                <select id="filtroEstadoSelect" class="bg-slate-50 border border-slate-200 text-slate-700 font-medium text-sm rounded-2xl px-4 py-3 focus:ring-2 focus:ring-sena-green/50 outline-none w-full sm:w-auto cursor-pointer transition-all hover:bg-slate-100">
                    <option value="">Todos los estados</option>
                    <option value="publicado">Publicado</option>
                    <option value="revision">En revisión</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>
        </div>

        <!-- 4. Catálogo del Proceso de Inducción -->
        <div class="space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-800 flex items-center gap-2.5">
                        <i class="fas fa-layer-group text-sena-green"></i>
                        <span>Catálogo del Proceso de Inducción</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Explora las temáticas pedagógicas, guías, normas y videos instructivos del SIG</p>
                </div>
            </div>

            @if($modulos->isEmpty())
                <!-- Estado vacío ilustrado exacto a la imagen -->
                <div class="py-16 px-6 text-center rounded-3xl bg-white border border-dashed border-slate-300 shadow-sm flex flex-col items-center justify-center">
                    <div class="w-20 h-20 rounded-3xl bg-slate-50 border border-slate-200 text-slate-400 flex items-center justify-center text-3xl mb-5 shadow-inner">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-800">Aún no hay módulos creados</h4>
                    <p class="text-sm text-slate-500 max-w-md mx-auto mt-2 leading-relaxed mb-6">
                        El sistema se encuentra en desarrollo inicial. Los instructores y administradores están configurando los módulos pedagógicos (por ejemplo: SST, Calidad, o Ambiental) para estructurar el proceso de inducción.
                    </p>
                </div>
            @else
                <!-- Grid de Módulos Dinámicos -->
                <div id="gridModulos" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($modulos as $modulo)
                        <div class="card-modulo bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative group flex flex-col h-full"
                             data-titulo="{{ strtolower($modulo->titulo) }}"
                             data-norma="{{ strtolower($modulo->norma ?? '') }}"
                             data-estado="{{ strtolower($modulo->estado) }}">

                            <!-- Encabezado de la Tarjeta -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-3.5">
                                    @php
                                        // Extraer el icono base (ej: 'fas fa-layer-group') removiendo clases de color azul para aplicar el verde SENA
                                        $baseIcon = trim(str_replace(['text-blue-600', 'text-sky-600', 'text-[#00324D]', 'text-blue-500', 'text-blue-700'], '', $modulo->icon_class ?? 'fas fa-layer-group'));
                                        if (empty($baseIcon)) {
                                            $baseIcon = 'fas fa-layer-group';
                                        }
                                    @endphp
                                    <div class="w-12 h-12 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                        <i class="{{ $baseIcon }} text-sena-green text-2xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-extrabold text-slate-800 text-base leading-tight">{{ $modulo->titulo }}</h3>
                                        <div class="flex items-center gap-1.5 mt-1 text-slate-500 text-xs font-medium">
                                            <i class="fas fa-tags text-slate-400"></i>
                                            <span>{{ $modulo->norma ?: 'SIG Institucional' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Etiqueta de Estado Limpia -->
                            <div class="absolute top-6 right-6">
                                @if($modulo->estado == 'publicado')
                                    <span class="text-xs font-black tracking-wide uppercase text-emerald-600">
                                        Publicado
                                    </span>
                                @elseif($modulo->estado == 'revision')
                                    <span class="text-xs font-black tracking-wide uppercase text-amber-600">
                                        En Revisión
                                    </span>
                                @else
                                    <span class="text-xs font-black tracking-wide uppercase text-rose-600">
                                        Inactivo
                                    </span>
                                @endif
                            </div>

                            <!-- Descripción del Módulo -->
                            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed mb-5 flex-grow">
                                {{ $modulo->descripcion ?: 'Este módulo contiene guías pedagógicas, normativas de bioseguridad y material explicativo interactivo.' }}
                            </p>

                            <!-- Medios y Recursos Disponibles -->
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mb-5">
                                <span class="text-xs font-semibold text-slate-900 flex items-center gap-1.5">
                                    <i class="fas fa-file-alt text-emerald-500"></i> Guía Didáctica
                                </span>
                                <span class="text-xs font-semibold text-slate-900 flex items-center gap-1.5">
                                    <i class="fas fa-image text-sky-500"></i> Esquemas y Señales
                                </span>
                                <span class="text-xs font-semibold text-slate-900 flex items-center gap-1.5">
                                    <i class="fas fa-play-circle text-purple-500"></i> Video Instructivo
                                </span>
                            </div>

                            <!-- Barra de Progreso del Módulo -->
                            <div class="mb-5 p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                                <div class="flex justify-between text-xs mb-1.5">
                                    <span class="text-slate-600 font-bold">Avance temático</span>
                                    <span class="font-black text-sena-green">{{ $modulo->progreso ?? 0 }}%</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden shadow-inner">
                                    <div class="bg-sena-green h-1.5 rounded-full transition-all" style="width: {{ $modulo->progreso ?? 0 }}%"></div>
                                </div>
                            </div>

                            <!-- Botón de Ingreso al Estudio -->
                            <div class="pt-4 border-t border-slate-100 mt-auto">
                                <a href="{{ route('sisig.aprendiz.induccion.show', $modulo->id) }}" 
                                   class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-2xl text-xs font-bold text-white bg-sena-green hover:bg-emerald-600 shadow-sena hover:shadow-lg transition-all w-full group-hover:bg-emerald-600">
                                    <i class="fas fa-book-reader text-xs"></i>
                                    <span>Ingresar a Estudiar</span>
                                    <i class="fas fa-arrow-right text-[10px] transform group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            </div>

                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

    <!-- Script de Filtro y Búsqueda en Tiempo Real -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('filtroModulosInput');
            const normaSelect = document.getElementById('filtroNormaSelect');
            const estadoSelect = document.getElementById('filtroEstadoSelect');
            const cards = document.querySelectorAll('.card-modulo');

            function filtrar() {
                const query = (searchInput?.value || '').trim().toLowerCase();
                const norma = (normaSelect?.value || '').toLowerCase();
                const estado = (estadoSelect?.value || '').toLowerCase();

                cards.forEach(card => {
                    const cardTitulo = card.getAttribute('data-titulo') || '';
                    const cardNorma = card.getAttribute('data-norma') || '';
                    const cardEstado = card.getAttribute('data-estado') || '';

                    const coincideQuery = !query || cardTitulo.includes(query) || cardNorma.includes(query);
                    const coincideNorma = !norma || cardNorma.includes(norma);
                    const coincideEstado = !estado || cardEstado === estado;

                    if (coincideQuery && coincideNorma && coincideEstado) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }

            if (searchInput) searchInput.addEventListener('input', filtrar);
            if (normaSelect) normaSelect.addEventListener('change', filtrar);
            if (estadoSelect) estadoSelect.addEventListener('change', filtrar);
        });
    </script>
</x-sisig::layouts.admin>
