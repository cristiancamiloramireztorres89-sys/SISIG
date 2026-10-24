<x-sisig::layouts.editor title="Gestión de Participantes SISIG">
    <div class="space-y-6 sm:space-y-8 animate-fade-in pb-12">

        <!-- 1. Encabezado de la Sección -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-5 border-b border-slate-200">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                    <i class="fas fa-users text-sena-green"></i>
                    <span>Gestión de Participantes SISIG</span>
                </h1>
            </div>
        </div>

        <!-- 2. Tarjetas de Métricas (KPIs con estilo idéntico a Gestión de Usuarios) -->
        @php
            $kpis = [
                [
                    'title' => 'Total Aprendices',
                    'value' => $metrics['total'] ?? 0,
                    'sub'   => 'registrados',
                    'icon'  => 'fa-users text-slate-700',
                    'bar'   => 'from-slate-600 to-slate-800',
                ],
                [
                    'title' => 'Aprobados',
                    'value' => $metrics['aprobados'] ?? 0,
                    'sub'   => 'competentes',
                    'icon'  => 'fa-user-check text-emerald-600',
                    'bar'   => 'from-emerald-500 to-teal-600',
                ],
                [
                    'title' => 'Pendientes',
                    'value' => $metrics['pendientes'] ?? 0,
                    'sub'   => 'en proceso',
                    'icon'  => 'fa-hourglass-half text-amber-500',
                    'bar'   => 'from-amber-400 to-amber-600',
                ],
                [
                    'title' => 'No Aprobados',
                    'value' => $metrics['no_aprobados'] ?? 0,
                    'sub'   => 'por reforzar',
                    'icon'  => 'fa-times-circle text-rose-500',
                    'bar'   => 'from-rose-500 to-rose-700',
                ],
            ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($kpis as $kpi)
                <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r {{ $kpi['bar'] }}"></div>
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">{{ $kpi['title'] }}</span>
                        <i class="fas {{ $kpi['icon'] }} text-2xl group-hover:scale-110 transition-transform"></i>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-baseline gap-1.5">
                            <h3 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight">{{ $kpi['value'] }}</h3>
                            <span class="text-sm font-bold text-slate-400">{{ $kpi['sub'] }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- 3. Barra de Búsqueda y Filtros Rápidos -->
        <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/90 shadow-sm space-y-4">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                
                <div class="relative flex-1 max-w-md">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" id="participante-search-input" value="{{ $buscar ?? '' }}" placeholder="Buscar por nombre, documento, correo o ficha..." 
                           class="w-full pl-10 pr-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none text-xs transition-all text-slate-800">
                </div>

                <!-- Filtros combinados en pastillas -->
                <div class="flex flex-wrap items-center gap-3 text-xs">
                    <div class="flex items-center gap-1.5 bg-slate-50 p-1.5 rounded-2xl border border-slate-200">
                        <span class="text-slate-400 font-bold px-2 hidden sm:inline">Estado:</span>
                        <button type="button" onclick="filterByStatus('todos')" class="status-filter-btn px-3 py-1 rounded-xl font-bold bg-slate-900 text-white shadow-sm" data-status="todos">Todos</button>
                        <button type="button" onclick="filterByStatus('Aprobado')" class="status-filter-btn px-3 py-1 rounded-xl font-bold text-slate-600 hover:bg-slate-200" data-status="Aprobado">Aprobados</button>
                        <button type="button" onclick="filterByStatus('Pendiente')" class="status-filter-btn px-3 py-1 rounded-xl font-bold text-slate-600 hover:bg-slate-200" data-status="Pendiente">Pendientes</button>
                        <button type="button" onclick="filterByStatus('No aprobado')" class="status-filter-btn px-3 py-1 rounded-xl font-bold text-slate-600 hover:bg-slate-200" data-status="No aprobado">No aprobados</button>
                    </div>

                    @php
                        $modulosFiltroPart = $modulos ?? collect();
                        $totalOpcionesPart = $modulosFiltroPart->count() + 1;
                        $tieneMasDeTresPart = $totalOpcionesPart > 3;
                    @endphp

                    <style>
                        #contenedor-modulos-part::-webkit-scrollbar {
                            display: none;
                        }
                        #contenedor-modulos-part {
                            -ms-overflow-style: none;
                            scrollbar-width: none;
                        }
                    </style>

                    <!-- Filtro Dinámico de Secciones SIG Participantes (Sin logos, Capacidad: 3) -->
                    <div class="flex items-center gap-1.5 bg-slate-50 p-1.5 rounded-2xl border border-slate-200 select-none text-xs">
                        <span class="text-slate-400 font-bold px-2 hidden sm:inline whitespace-nowrap">Sección:</span>
                        
                        @if($tieneMasDeTresPart)
                            <button type="button" 
                                    id="btn-part-modulos-prev"
                                    onclick="desplazarModulosPart('left')" 
                                    class="w-7 h-7 rounded-xl bg-white text-slate-500 hover:text-slate-900 hover:bg-slate-100 border border-slate-200/80 shadow-xs flex items-center justify-center transition-all active:scale-90 flex-shrink-0 cursor-pointer"
                                    title="Ver módulos anteriores"
                                    aria-label="Ver módulos anteriores">
                                <i class="fas fa-chevron-left text-[10px]"></i>
                            </button>
                        @endif

                        <div id="contenedor-modulos-part" 
                             class="flex items-center gap-1.5 overflow-x-auto scroll-smooth {{ $tieneMasDeTresPart ? 'w-[240px] sm:w-[270px] md:w-[300px]' : '' }} py-0.5 px-0.5"
                             style="scrollbar-width: none; -ms-overflow-style: none;">
                            
                            <!-- Opción Todas -->
                            <button type="button" 
                                    onclick="filterBySeccion('todas')"
                                    title="Todas las secciones"
                                    @if($tieneMasDeTresPart) style="flex: 0 0 calc((100% - 12px) / 3); min-width: 0;" @endif
                                    class="seccion-filter-btn px-2.5 py-1.5 rounded-xl font-bold transition-all flex items-center justify-center flex-shrink-0 whitespace-nowrap text-center truncate bg-slate-900 text-white shadow-sm ring-1 ring-slate-900 is-active-part"
                                    data-seccion="todas">
                                <span class="truncate">Todas</span>
                            </button>

                            @foreach($modulosFiltroPart as $mP)
                                @php
                                    $secKey = strtolower($mP->titulo);
                                    if (str_contains($secKey, 'calidad')) $secKey = 'calidad';
                                    elseif (str_contains($secKey, 'sst') || str_contains($secKey, 'seguridad')) $secKey = 'sst';
                                    elseif (str_contains($secKey, 'ambiental') || str_contains($secKey, 'ambiente')) $secKey = 'ambiental';
                                @endphp
                                <button type="button" 
                                        onclick="filterBySeccion('{{ $secKey }}')"
                                        title="{{ $mP->titulo }}"
                                        @if($tieneMasDeTresPart) style="flex: 0 0 calc((100% - 12px) / 3); min-width: 0;" @endif
                                        class="seccion-filter-btn px-2.5 py-1.5 rounded-xl font-bold transition-all flex items-center justify-center flex-shrink-0 whitespace-nowrap text-center truncate text-slate-600 hover:bg-slate-200/80 hover:text-slate-900"
                                        data-seccion="{{ $secKey }}">
                                    <span class="truncate">{{ $mP->titulo }}</span>
                                </button>
                            @endforeach
                        </div>

                        @if($tieneMasDeTresPart)
                            <button type="button" 
                                    id="btn-part-modulos-next"
                                    onclick="desplazarModulosPart('right')" 
                                    class="w-7 h-7 rounded-xl bg-white text-slate-500 hover:text-slate-900 hover:bg-slate-100 border border-slate-200/80 shadow-xs flex items-center justify-center transition-all active:scale-90 flex-shrink-0 cursor-pointer"
                                    title="Ver más módulos"
                                    aria-label="Ver más módulos">
                                <i class="fas fa-chevron-right text-[10px]"></i>
                            </button>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        <!-- 4. Tabla Principal de Participantes -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-800 flex items-center gap-2.5">
                        <i class="fas fa-address-book text-sena-green"></i>
                        <span>Directorio de Participantes de SISIG</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Listado institucional con roles, estado de cuenta y avance formativo</p>
                </div>
                <span id="filtered-counter" class="text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1 rounded-xl self-start sm:self-auto">
                    Participantes: {{ count($participantesList) }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider">
                            <th class="py-3.5 px-4">Aprendiz / Persona</th>
                            <th class="py-3.5 px-4">Documento</th>
                            <th class="py-3.5 px-4">Correo Electrónico</th>
                            @foreach($modulos as $mod)
                                @php
                                    $modKey = strtolower($mod->titulo);
                                    if (str_contains($modKey, 'calidad')) $modKey = 'calidad';
                                    elseif (str_contains($modKey, 'sst') || str_contains($modKey, 'seguridad')) $modKey = 'sst';
                                    elseif (str_contains($modKey, 'ambiental') || str_contains($modKey, 'ambiente')) $modKey = 'ambiental';
                                    else $modKey = \Illuminate\Support\Str::slug($modKey, '_');
                                @endphp
                                <th class="py-3.5 px-4 text-center col-seccion col-seccion-{{ $modKey }}" data-seccion-col="{{ $modKey }}">
                                    {{ $mod->titulo }}
                                </th>
                            @endforeach
                            <th class="py-3.5 px-4">Estado</th>
                            <th class="py-3.5 px-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="participantes-table-body" class="divide-y divide-slate-100">
                        @forelse($participantesList as $participante)
                            <tr class="participante-row hover:bg-slate-50/80 transition-colors"
                                data-name="{{ strtolower($participante->nombre) }}"
                                data-email="{{ strtolower($participante->correo) }}"
                                data-document="{{ strtolower(str_replace('.', '', $participante->documento)) }}"
                                data-ficha="{{ strtolower($participante->ficha) }}"
                                data-status="{{ $participante->estado }}"
                                data-calidad="{{ $participante->calidad }}"
                                data-sst="{{ $participante->sst }}"
                                data-ambiental="{{ $participante->ambiental }}"
                                @foreach($participante->secciones as $sec)
                                    data-{{ \Illuminate\Support\Str::slug($sec['titulo'], '_') }}="{{ $sec['porcentaje'] }}"
                                @endforeach>
                                
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl overflow-hidden bg-slate-100 flex items-center justify-center flex-shrink-0 shadow-sm border border-slate-200/80">
                                            @if(!empty($participante->avatar_url))
                                                <img src="{{ $participante->avatar_url }}" alt="{{ $participante->nombre }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-slate-200 text-slate-400 flex items-center justify-center text-xs">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-800 block text-xs sm:text-sm">{{ $participante->nombre }}</span>
                                            <span class="text-[11px] text-slate-400 font-medium">Ficha: {{ $participante->ficha }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-3.5 px-4 text-slate-600 font-mono text-xs">
                                    <span class="font-bold text-slate-700">{{ $participante->documento }}</span>
                                    <span class="text-[10px] text-slate-400 block">{{ $participante->tipo_documento ?? 'Sin asignar' }}</span>
                                </td>

                                <td class="py-3.5 px-4 text-slate-600">
                                    <span class="font-medium text-slate-700">{{ $participante->correo }}</span>
                                    @if($participante->ficha && $participante->ficha !== 'Sin asignar')
                                        <span class="text-[10px] text-sena-green font-bold block">Ficha: {{ $participante->ficha }}</span>
                                    @endif
                                </td>

                                @foreach($modulos as $mod)
                                    @php
                                        $modKey = strtolower($mod->titulo);
                                        if (str_contains($modKey, 'calidad')) $modKey = 'calidad';
                                        elseif (str_contains($modKey, 'sst') || str_contains($modKey, 'seguridad')) $modKey = 'sst';
                                        elseif (str_contains($modKey, 'ambiental') || str_contains($modKey, 'ambiente')) $modKey = 'ambiental';
                                        else $modKey = \Illuminate\Support\Str::slug($modKey, '_');

                                        $secInfo = collect($participante->secciones)->firstWhere('id', $mod->id);
                                        $secPct = $secInfo['porcentaje'] ?? 0;
                                        $secEst = $secInfo['estado'] ?? 'Sin iniciar';
                                    @endphp
                                    <td class="py-3.5 px-4 col-seccion col-seccion-{{ $modKey }}" data-seccion-col="{{ $modKey }}">
                                        <div class="w-24 mx-auto">
                                            <div class="flex items-center justify-between text-[11px] mb-1">
                                                <span class="font-bold text-slate-700">{{ $secPct }}%</span>
                                                <span class="text-[10px] {{ $secPct >= 70 ? 'text-emerald-600 font-bold' : ($secPct > 0 ? 'text-amber-500 font-semibold' : 'text-slate-400') }}">
                                                    {{ $secEst }}
                                                </span>
                                            </div>
                                            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                                <div class="h-1.5 rounded-full {{ $secPct >= 70 ? 'bg-sena-green' : ($secPct > 0 ? 'bg-amber-400' : 'bg-slate-200') }}" style="width: {{ $secPct }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                @endforeach

                                <td class="py-3.5 px-4">
                                    @php
                                        $estadoColors = match($participante->estado) {
                                            'Aprobado' => ['text-emerald-600', 'bg-emerald-500'],
                                            'Pendiente' => ['text-amber-600', 'bg-amber-500'],
                                            default => ['text-rose-600', 'bg-rose-500'],
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold {{ $estadoColors[0] }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $estadoColors[1] }}"></span>
                                        <span>{{ $participante->estado }}</span>
                                    </span>
                                </td>

                                <td class="py-3.5 px-4 text-right">
                                    <div class="inline-flex items-center gap-1.5">
                                        <button type="button" onclick="abrirModalDetalle({{ json_encode($participante) }})"
                                                class="w-8 h-8 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-sena-green hover:border-emerald-200 hover:bg-emerald-50 transition-all flex items-center justify-center cursor-pointer shadow-xs"
                                                title="Ver detalle del participante">
                                            <i class="fas fa-eye text-xs"></i>
                                        </button>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ 5 + count($modulos) }}" class="text-center py-12 text-slate-400">
                                    <i class="fas fa-users-slash text-3xl mb-2 block text-slate-300"></i>
                                    No hay participantes registrados en el sistema.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="no-results-msg" class="hidden text-center py-8 text-slate-400 text-xs">
                <i class="fas fa-search text-2xl mb-2 block text-slate-300"></i>
                No se encontraron participantes que coincidan con la búsqueda o filtros aplicados.
            </div>
        </div>

    </div>

    <!-- MODAL DETALLE DEL PARTICIPANTE (ESTILO UNIFICADO SISIG) -->
    <div id="modal-detalle-aprendiz" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-200 animate-scale-in relative">
            
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div id="modal-avatar" class="w-10 h-10 rounded-xl overflow-hidden bg-slate-100 flex items-center justify-center flex-shrink-0 shadow-sm border border-slate-200/80">
                        <i class="fas fa-user text-slate-400 text-sm"></i>
                    </div>
                    <div>
                        <h3 id="modal-nombre" class="text-base font-bold text-slate-800">Nombre Aprendiz</h3>
                        <p id="modal-documento" class="text-xs text-slate-500 mt-0.5">Sin asignar</p>
                    </div>
                </div>
                <button type="button" onclick="cerrarModalDetalle()" class="text-slate-400 hover:text-slate-600 p-2 rounded-xl hover:bg-slate-100 transition-colors">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <div class="mt-5 space-y-4 text-xs">
                <!-- Información General -->
                <div class="grid grid-cols-2 gap-3 p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80">
                    <div>
                        <span class="text-slate-400 block font-medium">Correo Electrónico</span>
                        <span id="modal-correo" class="text-slate-700 font-bold break-all">Sin asignar</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-medium">Ficha / Programa</span>
                        <span id="modal-ficha" class="text-slate-700 font-bold">Sin asignar</span>
                    </div>
                </div>

                <!-- Rendimiento por sección formativa dinámico -->
                <div class="space-y-2.5">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider block">Rendimiento por Sección</span>

                    <!-- Contenedor dinámico de secciones -->
                    <div id="modal-secciones-container" class="space-y-2.5 max-h-64 overflow-y-auto pr-1">
                        <!-- Se insertan dinámicamente con JavaScript desde los datos reales del aprendiz -->
                    </div>
                </div>

                <!-- Estado Consolidado Final -->
                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                    <span class="text-xs font-bold text-slate-500">Estado Consolidado</span>
                    <span id="modal-estado-pill" class="inline-flex items-center gap-1.5 text-xs font-bold">--</span>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end mt-5">
                <button type="button" onclick="cerrarModalDetalle()" class="px-5 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-colors cursor-pointer">
                    Cerrar
                </button>
            </div>

        </div>
    </div>

    <!-- SCRIPTS DE INTERACCIÓN REACTIVA Y FILTROS -->
    <script>
        let currentStatusFilter = '{{ $filtroEstado ?: "todos" }}';
        let currentSeccionFilter = '{{ $filtroSeccion ?: "todas" }}';
        const searchInput = document.getElementById('participante-search-input');
        const tableBody = document.getElementById('participantes-table-body');
        const counter = document.getElementById('filtered-counter');
        const noResults = document.getElementById('no-results-msg');

        function getRows() {
            return Array.from(document.querySelectorAll('.participante-row'));
        }

        // 1. Filtrado y Búsqueda Reactiva en Vivo
        function applyFilters() {
            const query = searchInput.value.toLowerCase().trim();
            const rows = getRows();
            let visibleCount = 0;

            rows.forEach(row => {
                const name = row.dataset.name || '';
                const email = row.dataset.email || '';
                const doc = row.dataset.document || '';
                const ficha = row.dataset.ficha || '';
                const status = row.dataset.status || '';

                const matchesSearch = !query || name.includes(query) || email.includes(query) || doc.includes(query) || ficha.includes(query);
                const matchesStatus = currentStatusFilter === 'todos' || (status === currentStatusFilter);

                const isVisible = matchesSearch && matchesStatus;
                row.style.display = isVisible ? '' : 'none';
                if (isVisible) visibleCount++;
            });

            counter.textContent = `Participantes: ${visibleCount}`;
            noResults.classList.toggle('hidden', visibleCount > 0);
        }

        searchInput.addEventListener('input', applyFilters);

        // 2. Filtro por Estado
        function filterByStatus(status) {
            currentStatusFilter = status;
            document.querySelectorAll('.status-filter-btn').forEach(btn => {
                btn.className = btn.dataset.status === status 
                    ? 'status-filter-btn px-3 py-1 rounded-xl font-bold bg-slate-900 text-white shadow-sm'
                    : 'status-filter-btn px-3 py-1 rounded-xl font-bold text-slate-600 hover:bg-slate-200';
            });
            applyFilters();
        }

        function desplazarModulosPart(direccion) {
            const contenedor = document.getElementById('contenedor-modulos-part');
            if (!contenedor) return;
            const paso = Math.round(contenedor.clientWidth / 3) + 2;
            contenedor.scrollBy({
                left: direccion === 'left' ? -paso : paso,
                behavior: 'smooth'
            });
            setTimeout(verificarLimitesScrollPart, 320);
        }

        function verificarLimitesScrollPart() {
            const contenedor = document.getElementById('contenedor-modulos-part');
            const btnPrev = document.getElementById('btn-part-modulos-prev');
            const btnNext = document.getElementById('btn-part-modulos-next');
            if (!contenedor) return;

            if (btnPrev) {
                const alInicio = contenedor.scrollLeft <= 5;
                btnPrev.style.opacity = alInicio ? '0.35' : '1';
                btnPrev.style.pointerEvents = alInicio ? 'none' : 'auto';
            }
            if (btnNext) {
                const alFinal = (contenedor.scrollLeft + contenedor.clientWidth) >= (contenedor.scrollWidth - 5);
                btnNext.style.opacity = alFinal ? '0.35' : '1';
                btnNext.style.pointerEvents = alFinal ? 'none' : 'auto';
            }
        }

        // 3. Filtro, Visibilidad y Ordenación por Sección
        function filterBySeccion(seccion) {
            currentSeccionFilter = seccion;

            // 1. Actualizar visualmente la clase de botón activo
            document.querySelectorAll('.seccion-filter-btn').forEach(btn => {
                const isActive = btn.dataset.seccion === seccion;
                btn.className = isActive 
                    ? 'seccion-filter-btn px-2.5 py-1.5 rounded-xl font-bold bg-slate-900 text-white shadow-sm ring-1 ring-slate-900 is-active-part flex items-center justify-center flex-shrink-0 whitespace-nowrap text-center truncate'
                    : 'seccion-filter-btn px-2.5 py-1.5 rounded-xl font-bold text-slate-600 hover:bg-slate-200/80 hover:text-slate-900 flex items-center justify-center flex-shrink-0 whitespace-nowrap text-center truncate';
            });

            // 2. Controlar la visibilidad de las columnas (th y td)
            const todasLasColumnas = document.querySelectorAll('.col-seccion');
            if (seccion === 'todas') {
                todasLasColumnas.forEach(col => col.style.display = '');
            } else {
                todasLasColumnas.forEach(col => {
                    col.style.display = (col.dataset.seccionCol === seccion) ? '' : 'none';
                });
            }

            // 3. Ajustar colspan del mensaje de tabla vacía si existe
            const emptyTd = document.querySelector('#participantes-table-body td[colspan]');
            if (emptyTd) {
                emptyTd.colSpan = seccion === 'todas' ? (5 + {{ count($modulos) }}) : 6;
            }

            // 4. Ordenar: si es sección específica de mayor a menor; si es 'todas' alfabéticamente
            const rows = getRows();
            if (seccion !== 'todas') {
                rows.sort((a, b) => {
                    const valA = parseFloat(a.dataset[seccion] || 0);
                    const valB = parseFloat(b.dataset[seccion] || 0);
                    return valB - valA;
                });
            } else {
                rows.sort((a, b) => (a.dataset.name || '').localeCompare(b.dataset.name || ''));
            }
            rows.forEach(row => tableBody.appendChild(row));

            applyFilters();
        }

        // 4. Modal Detalle del Participante
        function abrirModalDetalle(aprendiz) {
            const avatarContainer = document.getElementById('modal-avatar');
            if (aprendiz.avatar_url) {
                avatarContainer.innerHTML = `<img src="${aprendiz.avatar_url}" alt="${aprendiz.nombre || ''}" class="w-full h-full object-cover">`;
            } else {
                avatarContainer.innerHTML = `<div class="w-full h-full bg-slate-200 text-slate-400 flex items-center justify-center text-sm"><i class="fas fa-user"></i></div>`;
            }
            const docTipo = (aprendiz.tipo_documento && aprendiz.tipo_documento !== 'Sin asignar') ? (aprendiz.tipo_documento + ' - ') : '';
            document.getElementById('modal-documento').textContent = docTipo + (aprendiz.documento || 'Sin asignar');
            document.getElementById('modal-correo').textContent = aprendiz.correo || 'Sin asignar';
            document.getElementById('modal-ficha').textContent = aprendiz.ficha || 'Sin asignar';

            // Rendimiento por sección dinámico (Cero código quemado)
            const contSecciones = document.getElementById('modal-secciones-container');
            if (contSecciones) {
                contSecciones.innerHTML = '';
                const secciones = Array.isArray(aprendiz.secciones) ? aprendiz.secciones : [];

                if (secciones.length > 0) {
                    secciones.forEach(sec => {
                        const pct = parseInt(sec.porcentaje) || 0;
                        let badgeColor = 'text-slate-400';
                        let badgeText = 'Sin iniciar';
                        let pctColor = 'text-slate-800';

                        if (pct >= 70) {
                            badgeColor = 'text-emerald-600';
                            badgeText = 'Aprobado';
                            pctColor = 'text-emerald-600';
                        } else if (pct > 0) {
                            badgeColor = 'text-amber-600';
                            badgeText = 'En progreso';
                            pctColor = 'text-amber-600';
                        }

                        const normaText = sec.norma 
                            ? (sec.norma.toLowerCase().startsWith('iso') ? 'Norma ' + sec.norma : sec.norma) 
                            : 'Módulo Formativo';
                        const icono = sec.icono || 'fas fa-layer-group';
                        const iconBg = sec.icon_bg || 'bg-slate-100 text-slate-600';

                        const item = document.createElement('div');
                        item.className = 'p-3.5 rounded-2xl border border-slate-200/80 bg-white shadow-xs flex items-center justify-between gap-4 transition-all hover:border-slate-300';
                        item.innerHTML = `
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-xl ${iconBg} flex items-center justify-center text-xs font-bold flex-shrink-0">
                                    <i class="${icono}"></i>
                                </div>
                                <div class="min-w-0">
                                    <span class="font-bold text-slate-800 text-xs block truncate">${sec.titulo || 'Módulo'}</span>
                                    <span class="text-[10px] text-slate-400 block truncate">${normaText}</span>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span class="text-xs font-extrabold ${pctColor} block">${pct}%</span>
                                <span class="text-[10px] font-semibold ${badgeColor}">${badgeText}</span>
                            </div>
                        `;
                        contSecciones.appendChild(item);
                    });
                } else {
                    contSecciones.innerHTML = `
                        <div class="p-3 text-center text-slate-400 text-xs bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                            No hay secciones formativas registradas en el sistema.
                        </div>
                    `;
                }
            }

            // Estado general pill
            const pill = document.getElementById('modal-estado-pill');
            if (aprendiz.estado === 'Aprobado') {
                pill.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span><span class="text-emerald-600">Aprobado</span>';
            } else if (aprendiz.estado === 'Pendiente') {
                pill.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span><span class="text-amber-600">Pendiente</span>';
            } else {
                pill.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span><span class="text-rose-600">No aprobado</span>';
            }

            document.getElementById('modal-detalle-aprendiz').classList.remove('hidden');
            if (window.lockBodyScroll) window.lockBodyScroll(true);
        }

        function cerrarModalDetalle() {
            document.getElementById('modal-detalle-aprendiz').classList.add('hidden');
            if (window.lockBodyScroll) window.lockBodyScroll(false);
        }

        // Cerrar modal al hacer click en el backdrop
        document.getElementById('modal-detalle-aprendiz')?.addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarModalDetalle();
            }
        });

        // Inicializar filtros activos si venían de query params
        @if($filtroEstado)
            filterByStatus('{{ $filtroEstado }}');
        @endif
        @if($filtroSeccion)
            filterBySeccion('{{ $filtroSeccion }}');
        @endif
    </script>
</x-sisig::layouts.editor>