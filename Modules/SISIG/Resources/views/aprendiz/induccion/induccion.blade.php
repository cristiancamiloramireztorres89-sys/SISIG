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

        <!-- 2. Tarjetas de Métricas Clave (KPIs) con Estilo de Referencia Image 1 -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            
            <!-- Total Módulos -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Total Creados</span>
                    <div class="w-8 h-8 rounded-full bg-blue-50 text-[#1A2B4C] flex items-center justify-center text-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                        <i class="fas fa-layer-group"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#343A40] tracking-tight">{{ $totalModulos }}</h3>
                    <div class="mt-1 text-xs text-slate-400 font-medium">
                        <span>Módulos en el sistema</span>
                    </div>
                </div>
            </div>

            <!-- Publicados / Disponibles -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Publicados</span>
                    <div class="w-8 h-8 rounded-full bg-emerald-50 text-[#2E6F40] flex items-center justify-center text-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#343A40] tracking-tight">{{ $modulos->where('estado', 'publicado')->count() }}</h3>
                    <div class="mt-1 text-xs text-slate-400 font-medium">
                        <span>Visibles para aprendices</span>
                    </div>
                </div>
            </div>

            <!-- En revisión -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">En Revisión</span>
                    <div class="w-8 h-8 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center text-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                        <i class="fas fa-pencil-alt"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#343A40] tracking-tight">{{ $modulos->where('estado', 'revision')->count() }}</h3>
                    <div class="mt-1 text-xs text-slate-400 font-medium">
                        <span>Borradores pendientes</span>
                    </div>
                </div>
            </div>

            <!-- Inactivos -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Inactivos</span>
                    <div class="w-8 h-8 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center text-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                        <i class="fas fa-archive"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#343A40] tracking-tight">{{ $modulos->where('estado', 'inactivo')->count() }}</h3>
                    <div class="mt-1 text-xs text-slate-400 font-medium">
                        <span>Ocultos o dados de baja</span>
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
                        <div class="card-modulo bg-white rounded-2xl border border-slate-200/90 shadow-sm hover:shadow-md transition-all duration-300 relative group flex flex-col h-full overflow-hidden"
                             data-titulo="{{ strtolower($modulo->titulo) }}"
                             data-norma="{{ strtolower($modulo->norma ?? '') }}"
                             data-estado="{{ strtolower($modulo->estado) }}">

                            <!-- 1. Cabecera estilo Image 1: Dark Navy con texto blanco de alto contraste -->
                            <div class="bg-gradient-to-r from-[#1A2B4C] to-[#243B66] p-5 text-white relative">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-2.5">
                                        <!-- Imagen o Ícono circular del módulo -->
                                        @if($modulo->imagen)
                                            <img src="{{ asset($modulo->imagen) }}" alt="{{ $modulo->titulo }}" class="w-9 h-9 object-cover rounded-full border border-white/30 shadow-xs flex-shrink-0">
                                        @else
                                            <div class="w-9 h-9 rounded-full bg-white/10 border border-white/20 flex items-center justify-center text-white text-xs flex-shrink-0">
                                                <i class="{{ $modulo->icon_class ?? 'fas fa-layer-group' }}"></i>
                                            </div>
                                        @endif
                                        <span class="px-2.5 py-0.5 rounded-full bg-white/15 border border-white/20 text-[10px] font-bold text-slate-100">
                                            {{ $modulo->norma ?: 'SIG Institucional' }}
                                        </span>
                                    </div>

                                    <!-- Estado original de SISIG con pill estilo Image 1 -->
                                    <div>
                                        @if($modulo->estado == 'publicado')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white text-[#2E6F40] text-xs font-black shadow-xs">
                                                <span class="w-2 h-2 rounded-full bg-[#33FF57]"></span>
                                                <span>Publicado</span>
                                            </span>
                                        @elseif($modulo->estado == 'revision')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white text-amber-700 text-xs font-black shadow-xs">
                                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                                <span>En Revisión</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white text-rose-700 text-xs font-black shadow-xs">
                                                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                                <span>Inactivo</span>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Título y Norma en texto blanco hiperlegible -->
                                <h3 class="font-extrabold text-white text-lg tracking-tight leading-snug" style="color: #ffffff !important;">{{ $modulo->titulo }}</h3>
                                <p class="text-xs text-slate-200 font-medium mt-0.5" style="color: #e2e8f0 !important;"><i class="fas fa-tags text-slate-300 mr-1"></i> {{ $modulo->norma ?: 'SIG Institucional' }}</p>
                            </div>

                            <!-- 2. Cuerpo de la Tarjeta (Blanco con recursos, avance y botón de ingreso intactos) -->
                            <div class="p-5 flex flex-col flex-grow justify-between space-y-4 bg-white">
                                <!-- Descripción del Módulo -->
                                <p class="text-slate-600 text-xs leading-relaxed line-clamp-2">
                                    {{ $modulo->descripcion ?: 'Este módulo contiene guías pedagógicas, normativas de bioseguridad y material explicativo interactivo.' }}
                                </p>

                                <!-- Medios y Recursos Disponibles -->
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-2 pt-2 border-t border-slate-100">
                                    <span class="text-xs font-semibold text-slate-700 flex items-center gap-1.5">
                                        <i class="fas fa-file-alt text-[#2E6F40]"></i> Guía Didáctica
                                    </span>
                                    <span class="text-xs font-semibold text-slate-700 flex items-center gap-1.5">
                                        <i class="fas fa-image text-sky-500"></i> Esquemas y Señales
                                    </span>
                                    <span class="text-xs font-semibold text-slate-700 flex items-center gap-1.5">
                                        <i class="fas fa-play-circle text-purple-500"></i> Video Instructivo
                                    </span>
                                </div>

                                <!-- Barra de Progreso del Módulo -->
                                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 space-y-1.5">
                                    <div class="flex justify-between text-xs">
                                        <span class="text-slate-600 font-bold text-[11px]">Avance temático</span>
                                        <span class="font-black text-[#2E6F40]">{{ $modulo->progreso ?? 0 }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden shadow-inner">
                                        <div class="bg-[#2E6F40] h-1.5 rounded-full transition-all" style="width: {{ $modulo->progreso ?? 0 }}%"></div>
                                    </div>
                                </div>

                                <!-- Botón de Ingreso al Estudio en Pill -->
                                <div class="pt-3 border-t border-slate-100 mt-auto">
                                    <a href="{{ route('sisig.aprendiz.induccion.show', $modulo->id) }}" 
                                       class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full text-xs font-bold text-white bg-sena-green hover:bg-sena-green-hover shadow-sena hover:shadow-lg transition-all w-full cursor-pointer">
                                        <i class="fas fa-book-reader text-xs"></i>
                                        <span>Ingresar a Estudiar</span>
                                        <i class="fas fa-arrow-right text-[10px] transform group-hover:translate-x-1 transition-transform"></i>
                                    </a>
                                </div>
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
