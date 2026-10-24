<x-sisig::layouts.editor title="Seguimiento y Reportes SISIG">
    <div class="space-y-6 sm:space-y-8 animate-fade-in pb-12">

        <!-- 1. Encabezado de la Sección -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-5 border-b border-slate-200">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                    <i class="fas fa-chart-line text-sena-green"></i>
                    <span>Seguimiento y Reportes SISIG</span>
                </h1>
            </div>
        </div>

        <!-- 2. Tarjetas de Métricas (KPIs con estilo idéntico a Gestión de Usuarios) -->
        @php
            $kpis = [
                [
                    'title' => 'Total Evaluados',
                    'value' => $metrics['total'] ?? 0,
                    'sub'   => 'evaluaciones',
                    'icon'  => 'fa-users text-slate-700',
                    'bar'   => 'from-slate-600 to-slate-800',
                ],
                [
                    'title' => 'Aprobaron',
                    'value' => $metrics['aprobados'] ?? 0,
                    'sub'   => 'competentes (≥70%)',
                    'icon'  => 'fa-check-circle text-emerald-600',
                    'bar'   => 'from-emerald-500 to-teal-600',
                ],
                [
                    'title' => 'Pendientes',
                    'value' => $metrics['pendientes'] ?? 0,
                    'sub'   => 'sin presentar',
                    'icon'  => 'fa-hourglass-half text-amber-500',
                    'bar'   => 'from-amber-400 to-amber-600',
                ],
                [
                    'title' => 'No Aprobaron',
                    'value' => $metrics['no_aprobados'] ?? 0,
                    'sub'   => 'por reforzar (<70%)',
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

        <!-- 3. Barra de Búsqueda y Selector de Secciones SIG -->
        <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/90 shadow-sm space-y-4">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                
                <!-- Buscador reactivo -->
                <div class="relative flex-1 max-w-md">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" id="reportes-search-input" value="{{ $buscar ?? '' }}" placeholder="Buscar aprendiz por nombre, documento o ficha..." 
                           class="w-full pl-10 pr-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none text-xs transition-all text-slate-800">
                </div>

                <!-- Pastillas de Selección de Sección SIG Dinámicas -->
                @php
                    $listaModulos = (!empty($modulos) && $modulos->isNotEmpty()) ? $modulos : collect([
                        (object)['id' => 1, 'titulo' => 'Calidad', 'icono_base' => 'fas fa-certificate', 'color_icono' => 'text-emerald-400'],
                        (object)['id' => 2, 'titulo' => 'SST', 'icono_base' => 'fas fa-hard-hat', 'color_icono' => 'text-amber-400'],
                        (object)['id' => 3, 'titulo' => 'Ambiental', 'icono_base' => 'fas fa-leaf', 'color_icono' => 'text-emerald-400'],
                    ]);
                    $tieneMasDeTres = $listaModulos->count() > 3;
                @endphp

                <style>
                    #contenedor-secciones-sig::-webkit-scrollbar {
                        display: none;
                    }
                </style>

                <div class="flex items-center gap-1.5 bg-slate-50 p-1.5 rounded-2xl border border-slate-200 select-none text-xs">
                    <span class="text-slate-400 font-bold px-2 hidden sm:inline whitespace-nowrap">Sección SIG:</span>
                    
                    @if($tieneMasDeTres)
                        <!-- Flecha Desplazamiento Izquierda -->
                        <button type="button" 
                                id="btn-nav-secciones-prev"
                                onclick="desplazarSecciones('left')" 
                                class="w-7 h-7 rounded-xl bg-white text-slate-500 hover:text-slate-900 hover:bg-slate-100 border border-slate-200/80 shadow-xs flex items-center justify-center transition-all active:scale-90 flex-shrink-0 cursor-pointer"
                                title="Ver módulos anteriores"
                                aria-label="Ver módulos anteriores">
                            <i class="fas fa-chevron-left text-[10px]"></i>
                        </button>
                    @endif

                    <!-- Contenedor Deslizable de Módulos Dinámicos (Capacidad: 3 módulos) -->
                    <div id="contenedor-secciones-sig" 
                         class="flex items-center gap-1.5 overflow-x-auto scroll-smooth {{ $tieneMasDeTres ? 'w-[240px] sm:w-[270px] md:w-[300px]' : '' }} py-0.5 px-0.5"
                         style="scrollbar-width: none; -ms-overflow-style: none;">
                        @foreach($listaModulos as $mod)
                            @php
                                $esActivo = ($moduloSeleccionado && $moduloSeleccionado->id == $mod->id) 
                                            || (strtolower(trim($seccion)) === strtolower(trim($mod->titulo)));
                            @endphp
                            <a href="{{ route('sisig.editor.reportes.index', ['seccion' => $mod->id, 'buscar' => request('buscar')]) }}"
                               id="seccion-pill-{{ $mod->id }}"
                               title="{{ $mod->titulo }}"
                               @if($tieneMasDeTres) style="flex: 0 0 calc((100% - 12px) / 3); min-width: 0;" @endif
                               class="px-2.5 py-1.5 rounded-xl font-bold transition-all flex items-center justify-center flex-shrink-0 whitespace-nowrap text-center truncate {{ $esActivo ? 'bg-slate-900 text-white shadow-sm ring-1 ring-slate-900 is-active-seccion' : 'text-slate-600 hover:bg-slate-200/80 hover:text-slate-900' }}">
                                <span class="truncate">{{ $mod->titulo }}</span>
                            </a>
                        @endforeach
                    </div>

                    @if($tieneMasDeTres)
                        <!-- Flecha Desplazamiento Derecha -->
                        <button type="button" 
                                id="btn-nav-secciones-next"
                                onclick="desplazarSecciones('right')" 
                                class="w-7 h-7 rounded-xl bg-white text-slate-500 hover:text-slate-900 hover:bg-slate-100 border border-slate-200/80 shadow-xs flex items-center justify-center transition-all active:scale-90 flex-shrink-0 cursor-pointer"
                                title="Ver más módulos"
                                aria-label="Ver más módulos">
                            <i class="fas fa-chevron-right text-[10px]"></i>
                        </button>
                    @endif
                </div>

            </div>
        </div>

        <!-- 4. TABLA 1: Aprendices Aprobados -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-800 flex items-center gap-2.5">
                        <i class="fas fa-check-circle text-emerald-600"></i>
                        <span>Aprobados — Quiz {{ ucfirst($seccion) }}</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Aprendices que alcanzaron o superaron el umbral mínimo del 70% en la evaluación</p>
                </div>
                
                <div class="flex items-center gap-2.5 self-start sm:self-auto">
                    <span id="counter-aprobados" class="text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200/60 px-3 py-1 rounded-xl">
                        Aprobados: {{ $aprobadosList->count() }}
                    </span>
                    <button type="button" onclick="exportarTablaExcel('tabla-aprobados', 'Aprobados_Quiz_{{ ucfirst($seccion) }}')"
                            class="inline-flex items-center gap-2 px-3.5 py-1 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-xs font-bold text-slate-700 hover:text-emerald-700 hover:border-emerald-200 transition-all shadow-xs cursor-pointer">
                        <i class="fas fa-file-excel text-emerald-600 text-xs"></i>
                        <span>Exportar Excel</span>
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table id="tabla-aprobados" class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider">
                            <th class="py-3.5 px-4">Aprendiz / Persona</th>
                            <th class="py-3.5 px-4">Documento</th>
                            <th class="py-3.5 px-4">Ficha</th>
                            <th class="py-3.5 px-4">Puntaje</th>
                            <th class="py-3.5 px-4 text-center">Intentos</th>
                            <th class="py-3.5 px-4">Fecha</th>
                            <th class="py-3.5 px-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-aprobados" class="divide-y divide-slate-100">
                        @forelse($aprobadosList as $item)
                            <tr class="aprobado-row hover:bg-slate-50/80 transition-colors"
                                data-name="{{ strtolower($item->nombre) }}"
                                data-email="{{ strtolower($item->correo) }}"
                                data-document="{{ strtolower(str_replace('.', '', $item->documento)) }}"
                                data-ficha="{{ strtolower($item->ficha) }}">
                                
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl overflow-hidden bg-slate-100 flex items-center justify-center flex-shrink-0 shadow-sm border border-slate-200/80">
                                            @if(!empty($item->avatar_url))
                                                <img src="{{ $item->avatar_url }}" alt="{{ $item->nombre }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-slate-200 text-slate-400 flex items-center justify-center text-xs">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-800 block text-xs sm:text-sm">{{ $item->nombre }}</span>
                                            <span class="text-[11px] text-slate-400 font-medium">{{ $item->correo }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-3.5 px-4 text-slate-600 font-mono text-xs">
                                    <span class="font-bold text-slate-700">{{ $item->documento }}</span>
                                    <span class="text-[10px] text-slate-400 block">Cédula de ciudadanía</span>
                                </td>

                                <td class="py-3.5 px-4 text-slate-600">
                                    <span class="font-medium text-slate-700">{{ $item->ficha }}</span>
                                </td>

                                <td class="py-3.5 px-4">
                                    <div class="w-24">
                                        <div class="flex items-center justify-between text-[11px] mb-1">
                                            <span class="font-bold text-emerald-600">{{ $item->puntaje }}%</span>
                                            <span class="text-[10px] text-emerald-600 font-bold">Aprobado</span>
                                        </div>
                                        <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                            <div class="bg-sena-green h-1.5 rounded-full" style="width: {{ $item->puntaje }}%"></div>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-700">
                                        {{ $item->intentos }} {{ $item->intentos == 1 ? 'intento' : 'intentos' }}
                                    </span>
                                </td>

                                <td class="py-3.5 px-4 text-slate-500 font-medium text-xs">
                                    {{ $item->fecha }}
                                </td>

                                <td class="py-3.5 px-4 text-right">
                                    <div class="inline-flex items-center gap-1.5">
                                        <button type="button"
                                                onclick="abrirModalRespuestas({{ json_encode($item) }}, '{{ ucfirst($seccion) }}')"
                                                class="w-8 h-8 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-sena-green hover:border-emerald-200 hover:bg-emerald-50 transition-all flex items-center justify-center cursor-pointer shadow-xs"
                                                title="Ver detalle del intento">
                                            <i class="fas fa-eye text-xs"></i>
                                        </button>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr class="empty-row-aprobados">
                                <td colspan="7" class="text-center py-10 text-slate-400">
                                    <i class="fas fa-info-circle text-2xl mb-2 block text-slate-300"></i>
                                    No hay aprendices aprobados registrados para el Quiz de {{ ucfirst($seccion) }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="no-results-aprobados" class="hidden text-center py-6 text-slate-400 text-xs">
                <i class="fas fa-search text-xl mb-1.5 block text-slate-300"></i>
                No se encontraron aprendices aprobados que coincidan con la búsqueda.
            </div>
        </div>

        <!-- 5. TABLA 2: Aprendices No Aprobados -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-800 flex items-center gap-2.5">
                        <i class="fas fa-times-circle text-rose-500"></i>
                        <span>No aprobados — Quiz {{ ucfirst($seccion) }}</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Aprendices con puntaje menor al 70% que requieren refuerzo formativo</p>
                </div>
                
                <div class="flex items-center gap-2.5 self-start sm:self-auto">
                    <span id="counter-no-aprobados" class="text-xs font-bold text-rose-700 bg-rose-50 border border-rose-200/60 px-3 py-1 rounded-xl">
                        No aprobados: {{ $noAprobadosList->count() }}
                    </span>
                    <button type="button" onclick="exportarTablaExcel('tabla-no-aprobados', 'No_Aprobados_Quiz_{{ ucfirst($seccion) }}')"
                            class="inline-flex items-center gap-2 px-3.5 py-1 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-xs font-bold text-slate-700 hover:text-rose-700 hover:border-rose-200 transition-all shadow-xs cursor-pointer">
                        <i class="fas fa-file-excel text-rose-500 text-xs"></i>
                        <span>Exportar Excel</span>
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table id="tabla-no-aprobados" class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider">
                            <th class="py-3.5 px-4">Aprendiz / Persona</th>
                            <th class="py-3.5 px-4">Documento</th>
                            <th class="py-3.5 px-4">Ficha</th>
                            <th class="py-3.5 px-4">Puntaje</th>
                            <th class="py-3.5 px-4 text-center">Intentos</th>
                            <th class="py-3.5 px-4">Fecha</th>
                            <th class="py-3.5 px-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-no-aprobados" class="divide-y divide-slate-100">
                        @forelse($noAprobadosList as $item)
                            <tr class="no-aprobado-row hover:bg-slate-50/80 transition-colors"
                                data-name="{{ strtolower($item->nombre) }}"
                                data-email="{{ strtolower($item->correo) }}"
                                data-document="{{ strtolower(str_replace('.', '', $item->documento)) }}"
                                data-ficha="{{ strtolower($item->ficha) }}">
                                
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl overflow-hidden bg-slate-100 flex items-center justify-center flex-shrink-0 shadow-sm border border-slate-200/80">
                                            @if(!empty($item->avatar_url))
                                                <img src="{{ $item->avatar_url }}" alt="{{ $item->nombre }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-slate-200 text-slate-400 flex items-center justify-center text-xs">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-800 block text-xs sm:text-sm">{{ $item->nombre }}</span>
                                            <span class="text-[11px] text-slate-400 font-medium">{{ $item->correo }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-3.5 px-4 text-slate-600 font-mono text-xs">
                                    <span class="font-bold text-slate-700">{{ $item->documento }}</span>
                                    <span class="text-[10px] text-slate-400 block">Cédula de ciudadanía</span>
                                </td>

                                <td class="py-3.5 px-4 text-slate-600">
                                    <span class="font-medium text-slate-700">{{ $item->ficha }}</span>
                                </td>

                                <td class="py-3.5 px-4">
                                    @if($item->fue_anulado)
                                        <div class="inline-flex flex-col items-start gap-1">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg text-[10px] font-black bg-rose-100 text-rose-700 border border-rose-200" title="{{ $item->motivo_anulacion }}">
                                                <i class="fas fa-ban text-[9px] text-rose-600"></i>
                                                <span>Anulado (Trampa)</span>
                                            </span>
                                            <span class="text-[10px] text-rose-500 font-bold ml-0.5">
                                                {{ $item->infracciones_detectadas > 0 ? $item->infracciones_detectadas . ' infracciones' : 'Faltas de seguridad' }}
                                            </span>
                                        </div>
                                    @else
                                        <div class="w-24">
                                            <div class="flex items-center justify-between text-[11px] mb-1">
                                                <span class="font-bold text-rose-600">{{ $item->puntaje }}%</span>
                                                <span class="text-[10px] text-slate-500 font-medium" title="Contestó mal">Puntaje bajo</span>
                                            </div>
                                            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                                <div class="bg-rose-500 h-1.5 rounded-full" style="width: {{ $item->puntaje }}%"></div>
                                            </div>
                                        </div>
                                    @endif
                                </td>

                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-700">
                                        {{ $item->intentos }} {{ $item->intentos == 1 ? 'intento' : 'intentos' }}
                                    </span>
                                </td>

                                <td class="py-3.5 px-4 text-slate-500 font-medium text-xs">
                                    {{ $item->fecha }}
                                </td>

                                <td class="py-3.5 px-4 text-right">
                                    <div class="inline-flex items-center gap-1.5">
                                        <button type="button"
                                                onclick="abrirModalRespuestas({{ json_encode($item) }}, '{{ ucfirst($seccion) }}')"
                                                class="w-8 h-8 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-sena-green hover:border-emerald-200 hover:bg-emerald-50 transition-all flex items-center justify-center cursor-pointer shadow-xs"
                                                title="Ver detalle del intento">
                                            <i class="fas fa-eye text-xs"></i>
                                        </button>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr class="empty-row-no-aprobados">
                                <td colspan="7" class="text-center py-10 text-slate-400">
                                    <i class="fas fa-info-circle text-2xl mb-2 block text-slate-300"></i>
                                    No hay aprendices reprobados registrados para el Quiz de {{ ucfirst($seccion) }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="no-results-no-aprobados" class="hidden text-center py-6 text-slate-400 text-xs">
                <i class="fas fa-search text-xl mb-1.5 block text-slate-300"></i>
                No se encontraron aprendices reprobados que coincidan con la búsqueda.
            </div>
        </div>

    </div>

    <!-- MODAL DETALLE DE RESPUESTAS (ESTILO UNIFICADO SISIG) -->
    <div id="modal-respuestas-aprendiz" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-200 animate-scale-in relative">
            
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div id="modal-avatar" class="w-10 h-10 rounded-xl bg-gradient-to-tr from-slate-700 to-slate-800 text-white font-black text-xs flex items-center justify-center flex-shrink-0 shadow-sm border border-slate-200/50">
                        --
                    </div>
                    <div>
                        <h3 id="modal-nombre" class="text-base font-bold text-slate-800">Nombre Aprendiz</h3>
                        <p id="modal-info-sub" class="text-xs text-slate-500 mt-0.5">Quiz --</p>
                    </div>
                </div>
                <button type="button" onclick="cerrarModalRespuestas()" class="text-slate-400 hover:text-slate-600 p-2 rounded-xl hover:bg-slate-100 transition-colors">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <div class="mt-5 space-y-4 text-xs">
                <!-- Información del Intento -->
                <div class="grid grid-cols-2 gap-3 p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80">
                    <div>
                        <span class="text-slate-400 block font-medium">Puntaje Obtenido</span>
                        <span id="modal-puntaje" class="text-base font-extrabold text-slate-800">--</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-medium">Resultado</span>
                        <span id="modal-estado-badge" class="inline-flex items-center gap-1.5 font-bold text-xs mt-1">--</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-medium">Número de Intentos</span>
                        <span id="modal-intentos" class="text-slate-700 font-bold">--</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-medium">Fecha de Presentación</span>
                        <span id="modal-fecha" class="text-slate-700 font-bold">--</span>
                    </div>
                </div>

                <!-- Resumen Evaluativo / Diagnóstico Institucional -->
                <div class="space-y-2">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider block">Diagnóstico de Evaluación</span>
                    <div id="modal-diagnostico-box" class="p-3.5 rounded-2xl bg-emerald-50/50 border border-emerald-200/60 text-emerald-900 flex items-start gap-2.5">
                        <i id="modal-diagnostico-icon" class="fas fa-check-double text-emerald-600 mt-0.5 flex-shrink-0"></i>
                        <span id="modal-diagnostico-texto" class="leading-relaxed">Las respuestas de la evaluación fueron calificadas de manera automatizada conforme al banco de preguntas del módulo bajo el umbral mínimo institucional del 70%.</span>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end mt-5">
                <button type="button" onclick="cerrarModalRespuestas()" class="px-5 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-colors cursor-pointer">
                    Cerrar
                </button>
            </div>

        </div>
    </div>

    <!-- SCRIPTS DE INTERACCIÓN REACTIVA Y EXPORTACIÓN CSV -->
    <script>
        const searchInput = document.getElementById('reportes-search-input');
        const rowsAprobados = document.querySelectorAll('.aprobado-row');
        const rowsNoAprobados = document.querySelectorAll('.no-aprobado-row');
        const counterAprobados = document.getElementById('counter-aprobados');
        const counterNoAprobados = document.getElementById('counter-no-aprobados');
        const noResultsAprobados = document.getElementById('no-results-aprobados');
        const noResultsNoAprobados = document.getElementById('no-results-no-aprobados');

        // Filtrado en vivo sobre ambas tablas
        function applySearch() {
            const query = searchInput.value.toLowerCase().trim();
            
            // 1. Filtrar Aprobados
            let countAprobados = 0;
            rowsAprobados.forEach(row => {
                const name = row.dataset.name || '';
                const email = row.dataset.email || '';
                const doc = row.dataset.document || '';
                const ficha = row.dataset.ficha || '';

                const matches = !query || name.includes(query) || email.includes(query) || doc.includes(query) || ficha.includes(query);
                row.style.display = matches ? '' : 'none';
                if (matches) countAprobados++;
            });
            if (counterAprobados) {
                counterAprobados.textContent = `Aprobados: ${countAprobados}`;
            }
            if (noResultsAprobados) {
                noResultsAprobados.classList.toggle('hidden', countAprobados > 0 || rowsAprobados.length === 0);
            }

            // 2. Filtrar No Aprobados
            let countNoAprobados = 0;
            rowsNoAprobados.forEach(row => {
                const name = row.dataset.name || '';
                const email = row.dataset.email || '';
                const doc = row.dataset.document || '';
                const ficha = row.dataset.ficha || '';

                const matches = !query || name.includes(query) || email.includes(query) || doc.includes(query) || ficha.includes(query);
                row.style.display = matches ? '' : 'none';
                if (matches) countNoAprobados++;
            });
            if (counterNoAprobados) {
                counterNoAprobados.textContent = `No aprobados: ${countNoAprobados}`;
            }
            if (noResultsNoAprobados) {
                noResultsNoAprobados.classList.toggle('hidden', countNoAprobados > 0 || rowsNoAprobados.length === 0);
            }
        }

        searchInput.addEventListener('input', applySearch);

        // Control del Modal de Respuestas
        function abrirModalRespuestas(item, seccion) {
            const modalAvatar = document.getElementById('modal-avatar');
            if (modalAvatar) {
                if (item.avatar_url) {
                    modalAvatar.innerHTML = `<img src="${item.avatar_url}" alt="${item.nombre}" class="w-full h-full object-cover">`;
                } else {
                    modalAvatar.innerHTML = `<i class="fas fa-user text-slate-400"></i>`;
                }
            }
            document.getElementById('modal-nombre').textContent = item.nombre || 'Sin nombre';
            document.getElementById('modal-info-sub').textContent = 'Quiz ' + seccion + ' · Ficha ' + (item.ficha || 'Sin asignar');
            document.getElementById('modal-puntaje').textContent = item.puntaje + '%';
            document.getElementById('modal-intentos').textContent = (item.intentos || 1) + ((item.intentos == 1) ? ' intento' : ' intentos');
            document.getElementById('modal-fecha').textContent = item.fecha || 'N/A';

            const badge = document.getElementById('modal-estado-badge');
            const diagBox = document.getElementById('modal-diagnostico-box');
            const diagIcon = document.getElementById('modal-diagnostico-icon');
            const diagTexto = document.getElementById('modal-diagnostico-texto');

            if (item.fue_anulado) {
                badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span><span class="text-rose-600 font-black">Anulado por Trampa</span>';
                if (diagBox) {
                    diagBox.className = 'p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 flex items-start gap-2.5';
                    diagIcon.className = 'fas fa-ban text-rose-600 mt-0.5 flex-shrink-0';
                    diagTexto.innerHTML = `<strong>Examen Anulado por Trampa / Infracciones de Seguridad:</strong> ${item.motivo_anulacion || 'El aprendiz perdió sus vidas de seguridad o cometió faltas anti-trampa'}. Faltas detectadas: ${item.infracciones_detectadas || 0}.`;
                }
            } else if (item.aprobado) {
                badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span><span class="text-emerald-600 font-black">Aprobado</span>';
                if (diagBox) {
                    diagBox.className = 'p-3.5 rounded-2xl bg-emerald-50/50 border border-emerald-200/60 text-emerald-900 flex items-start gap-2.5';
                    diagIcon.className = 'fas fa-check-double text-emerald-600 mt-0.5 flex-shrink-0';
                    diagTexto.innerHTML = '<strong>Aprobado con éxito:</strong> El aprendiz contestó correctamente y superó el umbral mínimo institucional del 70%.';
                }
            } else {
                badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span><span class="text-amber-700 font-black">No aprobado</span>';
                if (diagBox) {
                    diagBox.className = 'p-3.5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 flex items-start gap-2.5';
                    diagIcon.className = 'fas fa-times-circle text-amber-600 mt-0.5 flex-shrink-0';
                    diagTexto.innerHTML = '<strong>No Aprobado por Respuestas:</strong> El aprendiz completó la prueba normalmente sin faltas de seguridad, pero contestó mal preguntas y obtuvo un puntaje menor al 70%.';
                }
            }

            document.getElementById('modal-respuestas-aprendiz').classList.remove('hidden');
            if (window.lockBodyScroll) window.lockBodyScroll(true);
        }

        function cerrarModalRespuestas() {
            document.getElementById('modal-respuestas-aprendiz').classList.add('hidden');
            if (window.lockBodyScroll) window.lockBodyScroll(false);
        }

        document.getElementById('modal-respuestas-aprendiz')?.addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarModalRespuestas();
            }
        });

        // Navegación con flechas para módulos de Sección SIG
        function desplazarSecciones(direccion) {
            const contenedor = document.getElementById('contenedor-secciones-sig');
            if (!contenedor) return;
            const paso = Math.round(contenedor.clientWidth / 3) + 2; // Desplazamiento exacto de 1 módulo (1/3 visible)
            contenedor.scrollBy({
                left: direccion === 'left' ? -paso : paso,
                behavior: 'smooth'
            });
            setTimeout(verificarLimitesScrollSecciones, 320);
        }

        function verificarLimitesScrollSecciones() {
            const contenedor = document.getElementById('contenedor-secciones-sig');
            const btnPrev = document.getElementById('btn-nav-secciones-prev');
            const btnNext = document.getElementById('btn-nav-secciones-next');
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

        document.addEventListener('DOMContentLoaded', function() {
            const contenedor = document.getElementById('contenedor-secciones-sig');
            if (contenedor) {
                contenedor.addEventListener('scroll', verificarLimitesScrollSecciones);
                
                // Centrar automáticamente la pastilla del módulo activo si no está visible
                const activa = contenedor.querySelector('.is-active-seccion');
                if (activa) {
                    activa.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                }
                setTimeout(verificarLimitesScrollSecciones, 350);
            }
        });
    </script>

    <!-- Librería SheetJS para exportación nativa a Microsoft Excel (.xlsx) -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script>
        // Función de exportación a archivo nativo .xlsx
        function exportarTablaExcel(tablaId, nombreArchivo) {
            const tablaOriginal = document.getElementById(tablaId);
            if (!tablaOriginal) return;

            // Clonar la tabla para preparar datos limpios
            const tablaClon = tablaOriginal.cloneNode(true);

            // Eliminar filas que estén ocultas por el buscador
            const filas = tablaClon.querySelectorAll('tr');
            filas.forEach(fila => {
                if (fila.style.display === 'none' || fila.classList.contains('empty-row-aprobados') || fila.classList.contains('empty-row-no-aprobados')) {
                    fila.remove();
                    return;
                }

                // Omitir última columna de acciones
                const celdas = fila.querySelectorAll('th, td');
                if (celdas.length > 0) {
                    celdas[celdas.length - 1].remove();
                }

                // Limpiar saltos de línea de las celdas restantes
                fila.querySelectorAll('th, td').forEach(celda => {
                    let texto = celda.innerText.replace(/(\r\n|\n|\r)/gm, ' ').replace(/\s+/g, ' ').trim();
                    celda.innerText = texto;
                });
            });

            // Convertir tabla a libro Excel nativo
            const libroExcel = XLSX.utils.table_to_book(tablaClon, { 
                sheet: "Reporte Editor", 
                raw: true 
            });

            // Autoajustar el ancho de las columnas
            const hoja = libroExcel.Sheets["Reporte Editor"];
            if (hoja && hoja['!ref']) {
                const rango = XLSX.utils.decode_range(hoja['!ref']);
                let anchos = [];
                for (let C = rango.s.c; C <= rango.e.c; ++C) {
                    let maxLong = 12;
                    for (let R = rango.s.r; R <= rango.e.r; ++R) {
                        const cellAddress = XLSX.utils.encode_cell({ r: R, c: C });
                        const celda = hoja[cellAddress];
                        if (celda && celda.v) {
                            maxLong = Math.max(maxLong, String(celda.v).length + 3);
                        }
                    }
                    anchos.push({ wch: Math.min(maxLong, 40) });
                }
                hoja['!cols'] = anchos;
            }

            // Descargar el archivo con extensión .xlsx
            XLSX.writeFile(libroExcel, nombreArchivo + '.xlsx');
        }
    </script>
</x-sisig::layouts.editor>