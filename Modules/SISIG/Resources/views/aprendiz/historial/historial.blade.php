<x-sisig::layouts.admin title="Historial y Calificaciones SIG">
    <div class="space-y-6 sm:space-y-8 animate-fade-in pb-16 w-full max-w-[1600px] mx-auto">

        <!-- 1. Encabezado de la Sección (Estilo Limpio y Unificado) -->
        <div class="pb-5 border-b border-slate-200">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                    <i class="fas fa-award text-sena-green"></i>
                    <span>Historial y Calificaciones SIG</span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Espacio donde consultas el consolidado de tu rendimiento académico en todos los cuestionarios, estados de aprobación e historial de intentos.
                </p>
            </div>
        </div>

        <!-- 2. Tarjetas de Métricas Clave (KPIs) con Estilo Premium y Degradados -->
        <!-- 2. Tarjetas de Métricas Clave (KPIs con Estilo de Referencia Image 1) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Total Evaluaciones -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Total Creados</span>
                    <div class="w-8 h-8 rounded-full bg-blue-50 text-[#1A2B4C] flex items-center justify-center text-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#343A40] tracking-tight">{{ $totalEvaluaciones }}</h3>
                    <div class="mt-1 flex items-center justify-between text-xs text-slate-400 font-medium">
                        <span>Evaluaciones en el sistema</span>
                        <span class="text-xs font-bold text-[#2E6F40]">Quices</span>
                    </div>
                </div>
            </div>

            <!-- Aprobadas -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Aprobadas</span>
                    <div class="w-8 h-8 rounded-full bg-emerald-50 text-[#2E6F40] flex items-center justify-center text-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#343A40] tracking-tight">{{ $totalAprobadas }}</h3>
                    <div class="mt-1 flex items-center justify-between text-xs text-slate-400 font-medium">
                        <span>Evaluaciones superadas</span>
                        <span class="text-xs font-bold text-[#2E6F40]">Acreditadas</span>
                    </div>
                </div>
            </div>

            <!-- Total Intentos Presentados -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Total Intentos</span>
                    <div class="w-8 h-8 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center text-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                        <i class="fas fa-history"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#343A40] tracking-tight">{{ $totalIntentosRealizados }}</h3>
                    <div class="mt-1 flex items-center justify-between text-xs text-slate-400 font-medium">
                        <span>Pruebas presentadas</span>
                        <span class="text-xs font-bold text-amber-600">Intentos</span>
                    </div>
                </div>
            </div>

            <!-- Promedio General -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Promedio General</span>
                    <div class="w-8 h-8 rounded-full bg-cyan-50 text-[#00BCD4] flex items-center justify-center text-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#343A40] tracking-tight">{{ number_format($promedioGeneral, 1) }}%</h3>
                    <div class="mt-1 flex items-center justify-between text-xs text-slate-400 font-medium">
                        <span>Rendimiento académico</span>
                        <span class="text-xs font-bold text-[#00BCD4]">Promedio</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Barra de Búsqueda y Filtros Estilo Premium -->
        <div class="bg-white rounded-3xl p-4 sm:p-5 border border-slate-200/90 shadow-sm flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="relative w-full md:w-96">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" id="filtroHistorialInput" placeholder="Buscar por módulo o evaluación..." 
                       class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-2 focus:ring-sena-green/50 transition-all outline-none text-slate-700 placeholder-slate-400 shadow-inner">
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                @php
                    $modulosFiltroHistorial = \Illuminate\Support\Facades\DB::table('sisig_modulos')
                        ->where('estado', '!=', 'inactivo')
                        ->orderBy('id', 'asc')
                        ->get();
                    if ($modulosFiltroHistorial->isEmpty()) {
                        $modulosFiltroHistorial = $consolidadoModulos->pluck('modulo_titulo')->unique()->filter()->map(function($t, $i) {
                            return (object)['id' => $i+1, 'titulo' => $t];
                        });
                    }
                    $totalOpcionesHistorial = $modulosFiltroHistorial->count() + 1;
                    $tieneMasDeTresHistorial = $totalOpcionesHistorial > 3;
                @endphp

                <style>
                    #contenedor-modulos-historial::-webkit-scrollbar {
                        display: none;
                    }
                    #contenedor-modulos-historial {
                        -ms-overflow-style: none;
                        scrollbar-width: none;
                    }
                </style>

                <!-- Filtro Dinámico de Secciones SIG Historial (Sin logos, Capacidad: 3) -->
                <div class="flex items-center gap-1.5 bg-slate-50 p-1.5 rounded-2xl border border-slate-200 select-none text-xs">
                    <span class="text-slate-400 font-bold px-2 hidden sm:inline whitespace-nowrap">Sección SIG:</span>
                    
                    @if($tieneMasDeTresHistorial)
                        <button type="button" 
                                id="btn-historial-modulos-prev"
                                onclick="desplazarModulosHistorial('left')" 
                                class="w-7 h-7 rounded-xl bg-white text-slate-500 hover:text-slate-900 hover:bg-slate-100 border border-slate-200/80 shadow-xs flex items-center justify-center transition-all active:scale-90 flex-shrink-0 cursor-pointer"
                                title="Ver módulos anteriores"
                                aria-label="Ver módulos anteriores">
                            <i class="fas fa-chevron-left text-[10px]"></i>
                        </button>
                    @endif

                    <div id="contenedor-modulos-historial" 
                         class="flex items-center gap-1.5 overflow-x-auto scroll-smooth {{ $tieneMasDeTresHistorial ? 'w-[240px] sm:w-[270px] md:w-[300px]' : '' }} py-0.5 px-0.5"
                         style="scrollbar-width: none; -ms-overflow-style: none;">
                        
                        <!-- Opción Todas -->
                        <button type="button" 
                                onclick="seleccionarModuloHistorial('', this)"
                                title="Todas las secciones"
                                @if($tieneMasDeTresHistorial) style="flex: 0 0 calc((100% - 12px) / 3); min-width: 0;" @endif
                                class="btn-historial-modulo-pill px-2.5 py-1.5 rounded-xl font-bold transition-all flex items-center justify-center flex-shrink-0 whitespace-nowrap text-center truncate bg-slate-900 text-white shadow-sm ring-1 ring-slate-900 is-active-historial"
                                data-modulo="">
                            <span class="truncate">Todas</span>
                        </button>

                        @foreach($modulosFiltroHistorial as $mH)
                            <button type="button" 
                                    onclick="seleccionarModuloHistorial('{{ strtolower($mH->titulo) }}', this)"
                                    title="{{ $mH->titulo }}"
                                    @if($tieneMasDeTresHistorial) style="flex: 0 0 calc((100% - 12px) / 3); min-width: 0;" @endif
                                    class="btn-historial-modulo-pill px-2.5 py-1.5 rounded-xl font-bold transition-all flex items-center justify-center flex-shrink-0 whitespace-nowrap text-center truncate text-slate-600 hover:bg-slate-200/80 hover:text-slate-900"
                                    data-modulo="{{ strtolower($mH->titulo) }}">
                                <span class="truncate">{{ $mH->titulo }}</span>
                            </button>
                        @endforeach
                    </div>

                    @if($tieneMasDeTresHistorial)
                        <button type="button" 
                                id="btn-historial-modulos-next"
                                onclick="desplazarModulosHistorial('right')" 
                                class="w-7 h-7 rounded-xl bg-white text-slate-500 hover:text-slate-900 hover:bg-slate-100 border border-slate-200/80 shadow-xs flex items-center justify-center transition-all active:scale-90 flex-shrink-0 cursor-pointer"
                                title="Ver más módulos"
                                aria-label="Ver más módulos">
                            <i class="fas fa-chevron-right text-[10px]"></i>
                        </button>
                    @endif
                </div>

                <input type="hidden" id="filtroModuloSelect" value="">

                <select id="filtroEstadoSelect" class="bg-slate-50 border border-slate-200 text-slate-700 font-medium text-sm rounded-2xl px-4 py-3 focus:ring-2 focus:ring-sena-green/50 outline-none w-full sm:w-auto cursor-pointer transition-all hover:bg-slate-100">
                    <option value="">Todos los estados</option>
                    <option value="aprobado">Aprobado</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="reprobado">No Aprobado</option>
                </select>
            </div>
        </div>

        <!-- 4. Consolidado de Notas por Módulo del SIG (Criterios 1, 2 y 4) -->
        <div class="space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-800 flex items-center gap-2.5">
                        <i class="fas fa-layer-group text-sena-green"></i>
                        <span>Consolidado de Calificaciones por Módulo</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Rendimiento en cada sección temática (SST, Calidad, Ambiental) e intentos restantes</p>
                </div>
            </div>

            @if($consolidadoModulos->isEmpty())
                <!-- Estado vacío ilustrado exacto -->
                <div class="py-16 px-6 text-center rounded-3xl bg-white border border-dashed border-slate-300 shadow-sm flex flex-col items-center justify-center">
                    <div class="w-20 h-20 rounded-3xl bg-slate-50 border border-slate-200 text-slate-400 flex items-center justify-center text-3xl mb-5 shadow-inner">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-800">Aún no hay calificaciones registradas</h4>
                    <p class="text-sm text-slate-500 max-w-md mx-auto mt-2 leading-relaxed mb-6">
                        El sistema se encuentra en desarrollo inicial. Cuando presentes tus evaluaciones de inducción, aquí se consolidarán tus resultados y aprobaciones.
                    </p>
                </div>
            @else
                <!-- Grid de Tarjetas de Consolidado por Módulo -->
                <div id="gridConsolidado" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($consolidadoModulos as $mod)
                        @php
                            $estadoModulo = $mod->aprobado ? 'aprobado' : ($mod->total_intentos > 0 ? 'reprobado' : 'pendiente');
                        @endphp
                        <div class="card-modulo-historial bg-white rounded-2xl border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative group flex flex-col overflow-hidden h-full"
                             data-titulo="{{ strtolower($mod->quiz_titulo) }}"
                             data-modulo="{{ strtolower($mod->modulo_titulo) }}"
                             data-estado="{{ $estadoModulo }}">

                            <!-- Cabecera de la Tarjeta (Dark Navy Gradient) -->
                            <div class="bg-gradient-to-r from-[#1A2B4C] to-[#243B66] p-5 text-white relative rounded-t-2xl">
                                <div class="flex items-center justify-between gap-3 mb-3">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center text-xs text-white border border-white/20 flex-shrink-0">
                                            <i class="fas fa-graduation-cap"></i>
                                        </div>
                                        <span class="text-[11px] font-bold text-white/90 bg-white/10 px-2.5 py-0.5 rounded-full border border-white/15 truncate max-w-[170px]" style="color: #ffffff !important;">
                                            {{ $mod->modulo_titulo }}
                                        </span>
                                    </div>

                                    <!-- Estado -->
                                    @if($mod->aprobado)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-[#33FF57]/20 text-[#33FF57] border border-[#33FF57]/30 shadow-xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#33FF57]"></span>
                                            Aprobado
                                        </span>
                                    @elseif($mod->total_intentos > 0)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30 shadow-xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                            No Aprobado
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-slate-500/20 text-slate-300 border border-slate-400/30 shadow-xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            Pendiente
                                        </span>
                                    @endif
                                </div>

                                <h3 class="font-extrabold text-white text-lg tracking-tight leading-tight line-clamp-1" style="color: #ffffff !important;">
                                    {{ $mod->quiz_titulo }}
                                </h3>
                                <p class="text-xs text-slate-200 mt-1 line-clamp-1 font-medium flex items-center gap-1.5" style="color: #e2e8f0 !important;">
                                    <i class="fas fa-layer-group text-[10px] text-slate-300"></i>
                                    <span>{{ $mod->modulo_titulo }}</span>
                                </p>
                            </div>

                            <!-- Cuerpo de la Tarjeta con datos 100% reales -->
                            <div class="p-5 flex-1 flex flex-col justify-between bg-white rounded-b-2xl space-y-4">
                                <!-- Métricas: Mejor Nota, Nota Mínima -->
                                <div class="grid grid-cols-2 gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Mejor Nota</span>
                                        <span class="text-base font-black {{ $mod->aprobado ? 'text-[#2E6F40]' : 'text-slate-800' }} mt-0.5 block">
                                            {{ number_format($mod->mejor_puntaje, 1) }}%
                                        </span>
                                    </div>
                                    <div class="flex flex-col items-center justify-center border-l border-slate-200">
                                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Nota Mínima</span>
                                        <span class="text-base font-black text-slate-700 mt-0.5 block">
                                            {{ $mod->puntaje_minimo }}%
                                        </span>
                                    </div>
                                </div>

                                <!-- Control de Intentos Restantes -->
                                <div class="space-y-1.5">
                                    <div class="flex justify-between text-xs">
                                        <span class="text-slate-600 font-bold">Intentos utilizados</span>
                                        <span class="font-black text-slate-800">{{ $mod->total_intentos }} / {{ $mod->intentos_permitidos }}</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden shadow-inner">
                                        @php
                                            $porcentajeBarra = $mod->intentos_permitidos > 0 ? min(100, ($mod->total_intentos / $mod->intentos_permitidos) * 100) : 0;
                                        @endphp
                                        <div class="h-2 rounded-full transition-all {{ $mod->aprobado ? 'bg-gradient-to-r from-[#2E6F40] to-[#33FF57]' : ($mod->intentos_restantes <= 0 ? 'bg-rose-500' : 'bg-amber-500') }}" style="width: {{ $porcentajeBarra }}%"></div>
                                    </div>
                                    <div class="flex justify-between text-[11px] pt-1 font-semibold">
                                        <span class="text-slate-500">Intentos disponibles:</span>
                                        <span class="{{ $mod->intentos_restantes > 0 ? 'text-[#2E6F40] font-bold' : 'text-rose-600 font-bold' }}">
                                            {{ $mod->intentos_restantes }} restantes
                                        </span>
                                    </div>
                                </div>

                                <!-- Botón de Acción -->
                                <div class="pt-3 border-t border-slate-100 mt-auto">
                                    @if($mod->aprobado)
                                        <div class="text-center py-2.5 px-4 text-xs font-bold text-[#2E6F40] bg-emerald-50 rounded-full border border-emerald-200 flex items-center justify-center gap-2">
                                            <i class="fas fa-check-double text-[#2E6F40]"></i> <span>Módulo Acreditado</span>
                                        </div>
                                    @elseif($mod->puede_reintentar)
                                        <a href="{{ route('sisig.aprendiz.evaluaciones.iniciar', $mod->id_quiz) }}" 
                                           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full text-xs font-black text-[#1A2B4C] bg-[#33FF57] hover:bg-[#2ae049] shadow-md shadow-emerald-500/20 transition-all w-full group-hover:scale-[1.02]">
                                            <i class="fas fa-play text-xs"></i>
                                            <span>Presentar Intento (Intento {{ $mod->total_intentos + 1 }})</span>
                                            <i class="fas fa-arrow-right text-[10px]"></i>
                                        </a>
                                    @else
                                        <div class="text-center py-2.5 px-4 text-xs font-bold text-slate-400 bg-slate-100 rounded-full border border-slate-200 flex items-center justify-center gap-2">
                                            <i class="fas fa-ban"></i> <span>Sin Oportunidades Restantes</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- 5. Historial Detallado de Intentos Realizados (Criterio 3) -->
        <div class="space-y-5 pt-4">
            <div class="flex items-center gap-2.5 pb-2 border-b border-slate-200">
                <i class="fas fa-history text-sena-green text-xl"></i>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-800">
                        Historial Cronológico de Intentos Realizados
                    </h2>
                    <p class="text-xs text-slate-500">
                        Registro auditable de cada prueba presentada con fecha, hora, puntaje y estado de anulación
                    </p>
                </div>
            </div>

            @if($historialIntentos->isEmpty())
                <div class="bg-white rounded-3xl p-8 border border-slate-200/90 shadow-sm text-center">
                    <p class="text-xs sm:text-sm text-slate-500">Aún no has presentado ningún intento de evaluación.</p>
                </div>
            @else
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider">
                                    <th class="py-4 px-6">Evaluación / Módulo</th>
                                    <th class="py-4 px-4 text-center">Intento</th>
                                    <th class="py-4 px-4 text-center">Calificación</th>
                                    <th class="py-4 px-4 text-center">Nota Mínima</th>
                                    <th class="py-4 px-4 text-center">Resultado</th>
                                    <th class="py-4 px-4 text-center">Validez</th>
                                    <th class="py-4 px-6 text-right">Fecha y Hora</th>
                                    <th class="py-4 px-4 text-center">Detalle</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($historialIntentos as $h)
                                    <tr class="hover:bg-slate-50/90 transition-colors">
                                        <td class="py-4 px-6">
                                            <span class="font-extrabold text-slate-800 block text-xs sm:text-sm">{{ $h->quiz_titulo }}</span>
                                            <span class="text-[11px] text-slate-500 font-medium">{{ $h->modulo_titulo ?: 'Módulo General' }}</span>
                                        </td>
                                        <td class="py-4 px-4 text-center font-bold text-slate-600">
                                            #{{ $h->intento }}
                                        </td>
                                        <td class="py-4 px-4 text-center font-black text-sm {{ $h->aprobado ? 'text-sena-green' : 'text-rose-600' }}">
                                            {{ number_format($h->puntaje, 1) }}%
                                        </td>
                                        <td class="py-4 px-4 text-center text-slate-500 font-semibold">
                                            {{ $h->puntaje_minimo }}%
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            @if($h->aprobado)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[11px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-700">
                                                    <i class="fas fa-check-circle"></i> Aprobado
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[11px] font-black uppercase tracking-wider bg-rose-100 text-rose-700">
                                                    <i class="fas fa-times-circle"></i> Reprobado
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            @if($h->fue_anulado)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-black uppercase bg-red-100 text-red-700" title="{{ $h->motivo_anulacion }}">
                                                    <i class="fas fa-ban"></i> Anulado
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-black uppercase bg-slate-100 text-slate-600">
                                                    <i class="fas fa-shield-alt text-sena-green"></i> Válido
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 text-right text-slate-500 font-medium whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($h->fecha_presentacion ?? $h->created_at)->format('d/m/Y - h:i A') }}
                                        </td>
                                        <td class="py-4 px-4 text-center whitespace-nowrap">
                                            <a href="{{ route('sisig.aprendiz.evaluaciones.resultado', $h->id_resultado) }}" 
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 font-bold transition-all text-xs" 
                                               title="Ver detalle y auditoría del intento">
                                                <i class="fas fa-eye text-xs"></i>
                                                <span>Ver</span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

    </div>

    <!-- Script de Filtro en Tiempo Real -->
    <script>
        function seleccionarModuloHistorial(modulo, btn) {
            document.querySelectorAll('.btn-historial-modulo-pill').forEach(b => {
                b.className = 'btn-historial-modulo-pill px-2.5 py-1.5 rounded-xl font-bold transition-all flex items-center justify-center flex-shrink-0 whitespace-nowrap text-center truncate text-slate-600 hover:bg-slate-200/80 hover:text-slate-900';
            });
            btn.className = 'btn-historial-modulo-pill px-2.5 py-1.5 rounded-xl font-bold transition-all flex items-center justify-center flex-shrink-0 whitespace-nowrap text-center truncate bg-slate-900 text-white shadow-sm ring-1 ring-slate-900 is-active-historial';
            
            const hiddenInput = document.getElementById('filtroModuloSelect');
            if (hiddenInput) {
                hiddenInput.value = modulo;
                hiddenInput.dispatchEvent(new Event('change'));
            }
        }

        function desplazarModulosHistorial(direccion) {
            const contenedor = document.getElementById('contenedor-modulos-historial');
            if (!contenedor) return;
            const paso = Math.round(contenedor.clientWidth / 3) + 2;
            contenedor.scrollBy({
                left: direccion === 'left' ? -paso : paso,
                behavior: 'smooth'
            });
            setTimeout(verificarLimitesScrollHistorial, 320);
        }

        function verificarLimitesScrollHistorial() {
            const contenedor = document.getElementById('contenedor-modulos-historial');
            const btnPrev = document.getElementById('btn-historial-modulos-prev');
            const btnNext = document.getElementById('btn-historial-modulos-next');
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

        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('filtroHistorialInput');
            const moduloSelect = document.getElementById('filtroModuloSelect');
            const estadoSelect = document.getElementById('filtroEstadoSelect');
            const cards = document.querySelectorAll('.card-modulo-historial');
            const contenedorHist = document.getElementById('contenedor-modulos-historial');

            if (contenedorHist) {
                contenedorHist.addEventListener('scroll', verificarLimitesScrollHistorial);
                setTimeout(verificarLimitesScrollHistorial, 350);
            }

            function filtrar() {
                const query = (searchInput?.value || '').trim().toLowerCase();
                const modulo = (moduloSelect?.value || '').toLowerCase();
                const estado = (estadoSelect?.value || '').toLowerCase();

                cards.forEach(card => {
                    const cardTitulo = card.getAttribute('data-titulo') || '';
                    const cardModulo = card.getAttribute('data-modulo') || '';
                    const cardEstado = card.getAttribute('data-estado') || '';

                    const coincideQuery = !query || cardTitulo.includes(query) || cardModulo.includes(query);
                    const coincideModulo = !modulo || cardModulo.includes(modulo);
                    const coincideEstado = !estado || cardEstado === estado;

                    if (coincideQuery && coincideModulo && coincideEstado) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }

            if (searchInput) searchInput.addEventListener('input', filtrar);
            if (moduloSelect) moduloSelect.addEventListener('change', filtrar);
            if (estadoSelect) estadoSelect.addEventListener('change', filtrar);
        });
    </script>
</x-sisig::layouts.admin>
