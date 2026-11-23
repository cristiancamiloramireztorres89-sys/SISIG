<x-sisig::layouts.admin title="Auditoría de Resultados y Exámenes - SISIG">
    <div class="space-y-6 sm:space-y-8 animate-fade-in pb-12">

        <!-- 1. Encabezado de la Sección -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-5 border-b border-slate-200">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                    <i class="fas fa-poll-h text-sena-green"></i>
                    <span>Auditoría de Resultados y Exámenes</span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-3xl">
                    Supervisa el rendimiento de los aprendices, audita las respuestas marcadas pregunta por pregunta y revisa incidentes de seguridad anti-trampa.
                </p>
            </div>

            <div class="self-start lg:self-auto flex items-center gap-2.5">
                <span class="inline-flex items-center gap-2 px-3.5 py-2 rounded-2xl text-xs font-bold text-slate-600 bg-slate-100 border border-slate-200">
                    <i class="fas fa-calendar-alt text-sena-green"></i>
                    <span>{{ now()->translatedFormat('d F Y') }}</span>
                </span>
            </div>
        </div>

        <!-- 2. Tarjetas de Resumen Global (KPIs) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-5">
            <!-- Total Evaluaciones -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Total Evaluaciones</span>
                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs flex-shrink-0 text-slate-700">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="flex items-baseline gap-1.5">
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-[#343A40] tracking-tight">{{ $totalEvaluaciones }}</h3>
                        <span class="text-xs font-bold text-slate-400">presentadas</span>
                    </div>
                    <div class="mt-1 text-xs text-slate-400 font-medium">Promedio: {{ $promedioPuntaje }}%</div>
                </div>
            </div>

            <!-- Aprobadas -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Aprobadas</span>
                    <div class="w-8 h-8 rounded-full bg-emerald-50 flex items-center justify-center text-xs flex-shrink-0 text-emerald-600">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="flex items-baseline gap-1.5">
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-emerald-600 tracking-tight">{{ $totalAprobadas }}</h3>
                        <span class="text-xs font-bold text-emerald-600/80">({{ $tasaAprobacion }}%)</span>
                    </div>
                    <div class="mt-1 text-xs text-slate-400 font-medium">Nota ≥ Puntaje mínimo</div>
                </div>
            </div>

            <!-- Reprobadas -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Reprobadas</span>
                    <div class="w-8 h-8 rounded-full bg-rose-50 flex items-center justify-center text-xs flex-shrink-0 text-rose-500">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="flex items-baseline gap-1.5">
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-rose-600 tracking-tight">{{ $totalReprobadas }}</h3>
                        <span class="text-xs font-bold text-slate-400">intentos</span>
                    </div>
                    <div class="mt-1 text-xs text-slate-400 font-medium">Requieren refuerzo</div>
                </div>
            </div>

            <!-- Anuladas por Fraude -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Anuladas / Fraude</span>
                    <div class="w-8 h-8 rounded-full bg-amber-50 flex items-center justify-center text-xs flex-shrink-0 text-amber-600">
                        <i class="fas fa-ban"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="flex items-baseline gap-1.5">
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-amber-600 tracking-tight">{{ $totalAnuladas }}</h3>
                        <span class="text-xs font-bold text-slate-400">anulaciones</span>
                    </div>
                    <div class="mt-1 text-xs text-slate-400 font-medium">Por trampa o tiempo</div>
                </div>
            </div>

            <!-- Infracciones Detectadas -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Alertas Anti-Trampa</span>
                    <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-xs flex-shrink-0 text-indigo-600">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="flex items-baseline gap-1.5">
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-indigo-600 tracking-tight">{{ $totalConInfracciones }}</h3>
                        <span class="text-xs font-bold text-slate-400">pruebas con falta</span>
                    </div>
                    <div class="mt-1 text-xs text-slate-400 font-medium">Pestañas / Foco</div>
                </div>
            </div>
        </div>

        <!-- 3. Barra de Búsqueda y Filtros Rápidos en Tiempo Real -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200/90 shadow-sm space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
                <!-- Buscador de texto -->
                <div class="relative">
                    <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" id="filtro-buscar-resultado" 
                           placeholder="Buscar aprendiz, documento o examen..." 
                           class="w-full pl-9 pr-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all">
                </div>

                <!-- Filtro Módulo -->
                <div>
                    <select id="filtro-modulo-select" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all cursor-pointer">
                        <option value="all">Todos los módulos</option>
                        @foreach($modulos as $mod)
                            <option value="{{ $mod->id }}">{{ $mod->titulo }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro Quiz / Examen -->
                <div>
                    <select id="filtro-quiz-select" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all cursor-pointer">
                        <option value="all">Todos los exámenes</option>
                        @foreach($quices as $qz)
                            <option value="{{ $qz->id_quiz }}" data-modulo="{{ $qz->modulo_id }}">{{ $qz->titulo }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro Estado -->
                <div>
                    <select id="filtro-estado-select" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all cursor-pointer">
                        <option value="all">Todos los estados</option>
                        <option value="aprobado">Aprobados (≥ Mínimo)</option>
                        <option value="reprobado">Reprobados (< Mínimo)</option>
                        <option value="anulado">Anulados por Fraude</option>
                        <option value="infracciones">Con Alertas Anti-Trampa</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 4. Listado Principal de Resultados para Auditoría -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#33FF57]"></span>
                    <h2 class="text-base font-extrabold text-slate-800 tracking-tight">Registro de Intentos de Exámenes</h2>
                </div>
                <span id="contador-resultados-visibles" class="text-xs font-bold text-slate-400 bg-slate-100 px-3 py-1 rounded-full">
                    {{ $resultados->count() }} resultados
                </span>
            </div>

            @if($resultados->isEmpty())
                <div class="py-16 px-4 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 text-2xl">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-700">Aún no hay intentos registrados</h3>
                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                        Cuando los aprendices presenten sus evaluaciones evaluativas en el sistema, aquí podrás auditar sus respuestas y comportamiento en tiempo real.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse" id="tabla-resultados-auditoria">
                        <thead>
                            <tr class="bg-slate-50/80 text-[11px] font-black uppercase tracking-wider text-slate-400 border-b border-slate-200/80 select-none">
                                <th class="py-3.5 px-4">Aprendiz</th>
                                <th class="py-3.5 px-4">Módulo / Examen</th>
                                <th class="py-3.5 px-4 text-center">Intento</th>
                                <th class="py-3.5 px-4 text-center">Calificación</th>
                                <th class="py-3.5 px-4 text-center">Duración</th>
                                <th class="py-3.5 px-4 text-center">Seguridad</th>
                                <th class="py-3.5 px-4 text-center">Fecha Presentación</th>
                                <th class="py-3.5 px-4 text-right">Auditoría</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @foreach($resultados as $res)
                                @php
                                    $user = $res->user;
                                    $person = $user ? $user->person : null;
                                    $nombreAprendiz = $person 
                                        ? trim("{$person->first_name} {$person->first_last_name} " . ($person->second_last_name ?? '')) 
                                        : ($user->nickname ?? $user->email ?? 'Usuario Aprendiz');
                                    $docAprendiz = $person->document_number ?? 'Sin documento';
                                    $emailAprendiz = $user->email ?? ($person->misena_email ?? '');
                                    
                                    $segundos = (int) $res->tiempo_segundos_empleado;
                                    $duracionFmt = floor($segundos / 60) . 'm ' . str_pad($segundos % 60, 2, '0', STR_PAD_LEFT) . 's';

                                    $estadoFiltro = $res->fue_anulado ? 'anulado' : ($res->aprobado ? 'aprobado' : 'reprobado');
                                    $tieneInfraccionFiltro = $res->infracciones_detectadas > 0 ? '1' : '0';
                                @endphp
                                <tr class="resultado-row hover:bg-slate-50/70 transition-colors"
                                    data-nombre="{{ strtolower($nombreAprendiz) }}"
                                    data-documento="{{ strtolower($docAprendiz) }}"
                                    data-email="{{ strtolower($emailAprendiz) }}"
                                    data-examen="{{ strtolower($res->quiz->titulo ?? '') }}"
                                    data-modulo-id="{{ $res->quiz->modulo_id ?? '' }}"
                                    data-quiz-id="{{ $res->id_quiz }}"
                                    data-estado="{{ $estadoFiltro }}"
                                    data-infracciones="{{ $tieneInfraccionFiltro }}">
                                    
                                    <!-- Aprendiz -->
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center gap-3">
                                            @if($person && $person->avatar)
                                                <img src="{{ asset('storage/' . $person->avatar) }}" alt="Avatar" class="w-9 h-9 rounded-full object-cover border border-slate-200 flex-shrink-0">
                                            @else
                                                <div class="w-9 h-9 rounded-full bg-slate-900 text-white font-bold text-xs flex items-center justify-center flex-shrink-0">
                                                    {{ strtoupper(substr($nombreAprendiz, 0, 2)) }}
                                                </div>
                                            @endif
                                            <div class="min-w-0">
                                                <span class="font-extrabold text-slate-800 block truncate leading-tight">{{ $nombreAprendiz }}</span>
                                                <span class="text-[11px] text-slate-400 block mt-0.5 truncate">C.C. {{ $docAprendiz }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Módulo y Examen -->
                                    <td class="py-3.5 px-4">
                                        <div class="min-w-0">
                                            <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-600 mb-1">
                                                {{ $res->quiz->modulo->titulo ?? 'SIG' }}
                                            </span>
                                            <p class="font-bold text-slate-700 truncate text-xs">{{ $res->quiz->titulo ?? 'Examen' }}</p>
                                        </div>
                                    </td>

                                    <!-- Intento -->
                                    <td class="py-3.5 px-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-black bg-slate-100 text-slate-700">
                                            #{{ $res->intento }} / {{ $res->quiz->numero_intentos ?? 3 }}
                                        </span>
                                    </td>

                                    <!-- Calificación -->
                                    <td class="py-3.5 px-4 text-center">
                                        @if($res->fue_anulado)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-xs font-black bg-rose-100 text-rose-700 border border-rose-200">
                                                <i class="fas fa-ban"></i> 0.00% (Anulado)
                                            </span>
                                        @elseif($res->aprobado)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                <i class="fas fa-check-circle"></i> {{ number_format($res->puntaje, 1) }}%
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-xs font-black bg-rose-50 text-rose-600 border border-rose-200">
                                                <i class="fas fa-times-circle"></i> {{ number_format($res->puntaje, 1) }}%
                                            </span>
                                        @endif
                                        <div class="text-[10px] text-slate-400 mt-0.5">Mín: {{ $res->quiz->puntaje_minimo ?? 70 }}%</div>
                                    </td>

                                    <!-- Duración -->
                                    <td class="py-3.5 px-4 text-center text-slate-600 font-bold">
                                        <i class="fas fa-clock text-slate-400 mr-1 text-[11px]"></i>
                                        {{ $duracionFmt }}
                                    </td>

                                    <!-- Seguridad Anti-trampa -->
                                    <td class="py-3.5 px-4 text-center">
                                        @if($res->infracciones_detectadas > 0)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-xs font-black bg-amber-100 text-amber-800 border border-amber-200">
                                                <i class="fas fa-exclamation-triangle"></i> {{ $res->infracciones_detectadas }} faltas
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-xs font-bold bg-slate-50 text-slate-500 border border-slate-200">
                                                <i class="fas fa-shield-alt text-emerald-500"></i> Limpio
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Fecha -->
                                    <td class="py-3.5 px-4 text-center text-slate-500">
                                        <span class="font-bold text-slate-700 block">{{ $res->fecha_presentacion ? $res->fecha_presentacion->format('d/m/Y') : 'N/A' }}</span>
                                        <span class="text-[11px] text-slate-400">{{ $res->fecha_presentacion ? $res->fecha_presentacion->format('h:i A') : '' }}</span>
                                    </td>

                                    <!-- Acciones -->
                                    <td class="py-3.5 px-4 text-right">
                                        <button type="button" 
                                                onclick="abrirModalAuditoriaResultado({{ $res->id_resultado }})"
                                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-black text-[#1A2B4C] bg-[#33FF57] hover:bg-[#2ae049] shadow-sm shadow-emerald-500/20 transition-all cursor-pointer">
                                            <i class="fas fa-search-plus text-xs"></i>
                                            <span>Auditar Examen</span>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- No resultados en filtros -->
                <div id="no-filter-results" class="hidden py-12 px-4 text-center">
                    <i class="fas fa-search text-3xl text-slate-300 mb-2 block"></i>
                    <p class="text-sm font-bold text-slate-700">No se encontraron resultados coincidentes con los filtros aplicados.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL DETALLADO DE AUDITORÍA (RESPUESTAS E INFRACCIONES) -->
    <!-- ======================================================== -->
    <div id="modal-auditoria-resultado" class="fixed inset-0 z-[120] hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-3xl w-full p-6 sm:p-7 shadow-2xl border border-slate-200 animate-scale-in max-h-[90vh] flex flex-col relative">
            
            <!-- Botón cerrar X -->
            <button type="button" onclick="cerrarModalAuditoriaResultado()" class="absolute top-5 right-5 w-8 h-8 rounded-xl flex items-center justify-center text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all cursor-pointer" title="Cerrar modal">
                <i class="fas fa-times text-xs leading-none"></i>
            </button>

            <!-- Cabecera del Modal con Detalles del Aprendiz y Examen -->
            <div class="pb-4 border-b border-slate-100 pr-10">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div id="audit-aprendiz-avatar-wrap" class="w-12 h-12 rounded-2xl bg-[#1A2B4C] text-[#33FF57] font-black text-sm flex items-center justify-center flex-shrink-0 shadow-sm overflow-hidden">
                            <span id="audit-aprendiz-initials">AP</span>
                        </div>
                        <div>
                            <h3 id="audit-aprendiz-nombre" class="text-base font-black text-slate-800 leading-tight">Nombre Aprendiz</h3>
                            <p id="audit-aprendiz-meta" class="text-xs text-slate-500 mt-0.5">C.C. 1.000.000.000 · aprendiz@soy.sena.edu.co</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <span id="audit-badge-calificacion" class="px-3 py-1.5 rounded-xl text-xs font-black">
                            100% Aprobado
                        </span>
                    </div>
                </div>

                <!-- Chips resumen -->
                <div class="mt-3 flex flex-wrap items-center gap-2 text-xs">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 font-bold">
                        <i class="fas fa-layer-group text-slate-400"></i>
                        <span id="audit-modulo-titulo">Módulo</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 font-bold">
                        <i class="fas fa-clipboard-check text-slate-400"></i>
                        <span id="audit-quiz-titulo">Examen</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 font-bold">
                        <i class="fas fa-redo text-slate-400"></i>
                        <span id="audit-intento-texto">Intento 1 de 3</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 font-bold">
                        <i class="fas fa-clock text-slate-400"></i>
                        <span id="audit-duracion-texto">12m 30s</span>
                    </span>
                </div>
            </div>

            <!-- Selector de Pestañas (Tabs) -->
            <div class="flex items-center gap-2 pt-3 pb-2 border-b border-slate-100">
                <button type="button" id="tab-btn-preguntas" onclick="cambiarPestanaAuditoria('preguntas')" class="px-4 py-2 rounded-xl text-xs font-black transition-all cursor-pointer flex items-center gap-2 bg-[#1A2B4C] text-[#33FF57]">
                    <i class="fas fa-list-ol"></i>
                    <span>Respuestas Pregunta por Pregunta</span>
                </button>
                <button type="button" id="tab-btn-infracciones" onclick="cambiarPestanaAuditoria('infracciones')" class="px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center gap-2 text-slate-500 hover:bg-slate-100">
                    <i class="fas fa-shield-alt"></i>
                    <span>Bitácora de Incidentes</span>
                    <span id="audit-tab-infracciones-count" class="px-1.5 py-0.2 rounded-full text-[10px] font-black bg-rose-500 text-white">0</span>
                </button>
            </div>

            <!-- Contenido Scrollable -->
            <div class="flex-1 overflow-y-auto py-4 space-y-4 pr-1" id="audit-scroll-container">
                
                <!-- Loading State -->
                <div id="audit-loading-state" class="py-16 text-center text-slate-400">
                    <i class="fas fa-circle-notch fa-spin text-3xl mb-3 text-sena-green block"></i>
                    <p class="text-xs font-bold text-slate-600">Cargando desglose de la evaluación...</p>
                </div>

                <!-- PESTAÑA 1: Preguntas y Respuestas Detalladas -->
                <div id="audit-tab-content-preguntas" class="space-y-4 hidden">
                    <!-- Contenedor dinámico de reactivos -->
                    <div id="audit-preguntas-lista" class="space-y-4"></div>
                </div>

                <!-- PESTAÑA 2: Infracciones Anti-trampa -->
                <div id="audit-tab-content-infracciones" class="space-y-3 hidden">
                    <div id="audit-infracciones-lista" class="space-y-2.5"></div>
                </div>
            </div>

            <!-- Footer del Modal -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
                <div id="audit-anulacion-motivo-box" class="hidden text-xs text-rose-600 font-bold flex items-center gap-1.5">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span id="audit-anulacion-motivo-texto"></span>
                </div>

                <div class="flex items-center gap-2 ml-auto">
                    <button type="button" id="btn-anular-examen-modal" onclick="solicitarAnulacionExamen()" class="px-4 py-2.5 rounded-xl text-xs font-bold text-rose-600 hover:text-rose-800 hover:bg-rose-50 border border-rose-200 transition-all cursor-pointer flex items-center gap-1.5">
                        <i class="fas fa-ban text-xs"></i>
                        <span>Anular Examen por Fraude</span>
                    </button>
                    <button type="button" onclick="cerrarModalAuditoriaResultado()" class="px-5 py-2.5 rounded-xl font-bold text-xs text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all cursor-pointer">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL DE CONFIRMACIÓN DE ANULACIÓN POR FRAUDE            -->
    <!-- ======================================================== -->
    <div id="modal-confirmar-anulacion" class="fixed inset-0 z-[130] hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl border border-slate-200 animate-scale-in relative text-center">
            
            <div class="w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center text-2xl bg-rose-50 text-rose-600 border border-rose-100 ring-8 ring-rose-50/60 shadow-inner">
                <i class="fas fa-ban"></i>
            </div>

            <h3 class="text-xl font-black text-slate-800 tracking-tight">¿Anular examen del aprendiz?</h3>
            <p class="text-xs text-slate-500 mt-2 max-w-sm mx-auto leading-relaxed">
                Esta acción establecerá la calificación de este intento en 0.00% y marcará la prueba formalmente como anulada por deshonestidad académica.
            </p>

            <div class="mt-4 text-left">
                <label class="text-xs font-bold text-slate-700 block mb-1">Motivo o Justificación de la Anulación *</label>
                <textarea id="input-motivo-anulacion" rows="3" required placeholder="Ej: Se evidencia cambio reiterado de pestaña y abandono de pantalla completa..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm focus:bg-white focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 outline-none transition-all resize-none"></textarea>
            </div>

            <div class="mt-6 flex items-center justify-center gap-3">
                <button type="button" onclick="cerrarModalAnulacion()" class="flex-1 py-3 px-4 rounded-xl font-bold text-xs sm:text-sm text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all cursor-pointer">
                    Cancelar
                </button>
                <button type="button" id="btn-confirmar-anular-accion" onclick="ejecutarAnulacionConfirmada()" class="flex-1 py-3 px-4 rounded-xl font-bold text-xs sm:text-sm text-white bg-rose-600 hover:bg-rose-700 shadow-md shadow-rose-600/20 transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-95">
                    <i class="fas fa-ban text-xs"></i>
                    <span>Confirmar Anulación</span>
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentResultadoId = null;

        // ==========================================
        // 1. FILTRADO EN VIVO EN CLIENTE
        // ==========================================
        const inputBuscar = document.getElementById('filtro-buscar-resultado');
        const selectModulo = document.getElementById('filtro-modulo-select');
        const selectQuiz = document.getElementById('filtro-quiz-select');
        const selectEstado = document.getElementById('filtro-estado-select');
        const contadorVisibles = document.getElementById('contador-resultados-visibles');
        const noFilterResults = document.getElementById('no-filter-results');

        function aplicarFiltrosEnVivo() {
            const txt = (inputBuscar?.value || '').toLowerCase().trim();
            const modId = selectModulo?.value || 'all';
            const qzId = selectQuiz?.value || 'all';
            const est = selectEstado?.value || 'all';

            const filas = document.querySelectorAll('.resultado-row');
            let visibles = 0;

            filas.forEach(fila => {
                const nombre = fila.getAttribute('data-nombre') || '';
                const doc = fila.getAttribute('data-documento') || '';
                const email = fila.getAttribute('data-email') || '';
                const examen = fila.getAttribute('data-examen') || '';
                const rowModId = fila.getAttribute('data-modulo-id') || '';
                const rowQzId = fila.getAttribute('data-quiz-id') || '';
                const rowEstado = fila.getAttribute('data-estado') || '';
                const rowInfracciones = fila.getAttribute('data-infracciones') || '0';

                const coincideTexto = !txt || nombre.includes(txt) || doc.includes(txt) || email.includes(txt) || examen.includes(txt);
                const coincideModulo = (modId === 'all') || (rowModId === modId);
                const coincideQuiz = (qzId === 'all') || (rowQzId === qzId);
                
                let coincideEstado = true;
                if (est === 'aprobado') coincideEstado = (rowEstado === 'aprobado');
                else if (est === 'reprobado') coincideEstado = (rowEstado === 'reprobado');
                else if (est === 'anulado') coincideEstado = (rowEstado === 'anulado');
                else if (est === 'infracciones') coincideEstado = (rowInfracciones === '1');

                if (coincideTexto && coincideModulo && coincideQuiz && coincideEstado) {
                    fila.style.display = '';
                    visibles++;
                } else {
                    fila.style.display = 'none';
                }
            });

            if (contadorVisibles) contadorVisibles.textContent = `${visibles} resultados`;
            if (noFilterResults) {
                noFilterResults.classList.toggle('hidden', visibles > 0);
            }
        }

        if (inputBuscar) inputBuscar.addEventListener('input', aplicarFiltrosEnVivo);
        if (selectModulo) {
            selectModulo.addEventListener('change', () => {
                // Filtrar opciones de quiz dependientes del módulo
                const modVal = selectModulo.value;
                if (selectQuiz) {
                    Array.from(selectQuiz.options).forEach(opt => {
                        if (opt.value === 'all') return;
                        const optMod = opt.getAttribute('data-modulo');
                        opt.style.display = (modVal === 'all' || optMod === modVal) ? '' : 'none';
                    });
                    selectQuiz.value = 'all';
                }
                aplicarFiltrosEnVivo();
            });
        }
        if (selectQuiz) selectQuiz.addEventListener('change', aplicarFiltrosEnVivo);
        if (selectEstado) selectEstado.addEventListener('change', aplicarFiltrosEnVivo);

        // ==========================================
        // 2. MODAL DE AUDITORÍA
        // ==========================================
        async function abrirModalAuditoriaResultado(idResultado) {
            currentResultadoId = idResultado;
            const modal = document.getElementById('modal-auditoria-resultado');
            const loading = document.getElementById('audit-loading-state');
            const contentPreguntas = document.getElementById('audit-tab-content-preguntas');
            const contentInfracciones = document.getElementById('audit-tab-content-infracciones');

            if (!modal) return;

            // Reset vistas
            loading.classList.remove('hidden');
            contentPreguntas.classList.add('hidden');
            contentInfracciones.classList.add('hidden');
            modal.classList.remove('hidden');
            if (window.lockBodyScroll) window.lockBodyScroll(true);

            // Activar por defecto la pestaña de preguntas
            cambiarPestanaAuditoria('preguntas');

            try {
                const response = await fetch(`{{ url('sisig/admin/resultados') }}/${idResultado}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();
                if (!data.success) {
                    alert('No se pudo cargar la información de la prueba.');
                    cerrarModalAuditoriaResultado();
                    return;
                }

                // Llenar encabezado
                document.getElementById('audit-aprendiz-nombre').textContent = data.aprendiz.nombre;
                document.getElementById('audit-aprendiz-meta').textContent = `C.C. ${data.aprendiz.documento} · ${data.aprendiz.email}`;
                document.getElementById('audit-modulo-titulo').textContent = data.quiz.modulo;
                document.getElementById('audit-quiz-titulo').textContent = data.quiz.titulo;
                document.getElementById('audit-intento-texto').textContent = `Intento ${data.intento} de ${data.intentos_maximos}`;
                document.getElementById('audit-duracion-texto').textContent = data.duracion_texto;

                // Calificación badge
                const badgeCal = document.getElementById('audit-badge-calificacion');
                if (data.fue_anulado) {
                    badgeCal.className = 'px-3 py-1.5 rounded-xl text-xs font-black bg-rose-100 text-rose-700 border border-rose-200';
                    badgeCal.innerHTML = `<i class="fas fa-ban mr-1"></i> Anulado (0.00%)`;
                } else if (data.aprobado) {
                    badgeCal.className = 'px-3 py-1.5 rounded-xl text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-200';
                    badgeCal.innerHTML = `<i class="fas fa-check-circle mr-1"></i> ${data.puntaje.toFixed(1)}% Aprobado`;
                } else {
                    badgeCal.className = 'px-3 py-1.5 rounded-xl text-xs font-black bg-rose-50 text-rose-600 border border-rose-200';
                    badgeCal.innerHTML = `<i class="fas fa-times-circle mr-1"></i> ${data.puntaje.toFixed(1)}% Reprobado`;
                }

                // Infracciones badge en tab
                const infCountEl = document.getElementById('audit-tab-infracciones-count');
                const infCount = (data.infracciones || []).length;
                infCountEl.textContent = infCount;
                infCountEl.className = infCount > 0 
                    ? 'px-1.5 py-0.2 rounded-full text-[10px] font-black bg-rose-500 text-white' 
                    : 'px-1.5 py-0.2 rounded-full text-[10px] font-black bg-slate-200 text-slate-600';

                // Botón anular visibilidad
                const btnAnular = document.getElementById('btn-anular-examen-modal');
                const motivoBox = document.getElementById('audit-anulacion-motivo-box');
                if (data.fue_anulado) {
                    btnAnular.classList.add('hidden');
                    motivoBox.classList.remove('hidden');
                    document.getElementById('audit-anulacion-motivo-texto').textContent = `Anulado: ${data.motivo_anulacion || 'Deshonestidad académica'}`;
                } else {
                    btnAnular.classList.remove('hidden');
                    motivoBox.classList.add('hidden');
                }

                // Renderizar preguntas
                renderizarPreguntasAuditoria(data);

                // Renderizar infracciones
                renderizarInfraccionesAuditoria(data.infracciones || []);

                loading.classList.add('hidden');
                contentPreguntas.classList.remove('hidden');

            } catch (err) {
                console.error(err);
                alert('Ocurrió un error de red al consultar la auditoría.');
                cerrarModalAuditoriaResultado();
            }
        }

        function renderizarPreguntasAuditoria(data) {
            const container = document.getElementById('audit-preguntas-lista');
            container.innerHTML = '';

            if (!data.tiene_detalle_preguntas || !data.detalles_preguntas || data.detalles_preguntas.length === 0) {
                container.innerHTML = `
                    <div class="p-6 rounded-2xl bg-amber-50 border border-amber-200/80 text-amber-900 text-center">
                        <i class="fas fa-info-circle text-2xl text-amber-600 mb-2 block"></i>
                        <h4 class="font-extrabold text-xs sm:text-sm">Registro histórico sin almacenamiento de reactivos individuales</h4>
                        <p class="text-xs text-amber-800/80 mt-1 max-w-md mx-auto">
                            Este intento se presentó con anterioridad a la activación del desglose de opciones seleccionadas. El sistema cuenta con la nota global oficial (${data.puntaje.toFixed(1)}%), la duración y la bitácora de seguridad.
                        </p>
                    </div>
                `;
                return;
            }

            data.detalles_preguntas.forEach((item, index) => {
                const esCorrecta = item.es_correcta;
                const card = document.createElement('div');
                card.className = `p-4 sm:p-5 rounded-2xl border ${esCorrecta ? 'border-emerald-200/90 bg-emerald-50/20' : 'border-rose-200/90 bg-rose-50/20'} space-y-3`;

                // Header de la pregunta
                let html = `
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start gap-2.5">
                            <span class="w-6 h-6 rounded-lg text-xs font-black flex items-center justify-center flex-shrink-0 mt-0.5 ${esCorrecta ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white'}">
                                ${index + 1}
                            </span>
                            <h4 class="text-xs sm:text-sm font-bold text-slate-800 leading-snug">${escapeHtml(item.pregunta)}</h4>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-wider flex-shrink-0 ${esCorrecta ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'}">
                            <i class="fas ${esCorrecta ? 'fa-check' : 'fa-times'} mr-1"></i> ${esCorrecta ? 'Correcta' : 'Incorrecta'}
                        </span>
                    </div>
                `;

                // Opciones
                html += `<div class="space-y-1.5 pt-1">`;
                const opciones = item.opciones || [];
                opciones.forEach(op => {
                    const selId = item.opcion_seleccionada ? item.opcion_seleccionada.id_opcion : null;
                    const esLaMarcada = (selId == op.id_opcion);
                    const esLaCorrecta = op.es_correcta;

                    let bgClass = 'bg-white border-slate-200 text-slate-700';
                    let iconClass = 'fa-circle text-slate-300 text-[10px]';
                    let tagHtml = '';

                    if (esLaCorrecta) {
                        bgClass = 'bg-emerald-50 border-emerald-300 text-emerald-950 font-bold';
                        iconClass = 'fa-check-circle text-emerald-600 text-sm';
                        tagHtml += `<span class="px-2 py-0.5 rounded-md text-[9px] font-extrabold uppercase bg-emerald-200 text-emerald-800">Opción Correcta</span>`;
                    }

                    if (esLaMarcada) {
                        if (!esLaCorrecta) {
                            bgClass = 'bg-rose-50 border-rose-300 text-rose-950 font-semibold';
                            iconClass = 'fa-times-circle text-rose-500 text-sm';
                        }
                        tagHtml += `<span class="px-2 py-0.5 rounded-md text-[9px] font-extrabold uppercase ${esLaCorrecta ? 'bg-emerald-600 text-white' : 'bg-rose-600 text-white'}">Marcada por el aprendiz</span>`;
                    }

                    html += `
                        <div class="p-2.5 rounded-xl border text-xs flex items-center justify-between gap-2.5 ${bgClass}">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <i class="fas ${iconClass} flex-shrink-0"></i>
                                <span class="break-words">${escapeHtml(op.opcion)}</span>
                            </div>
                            <div class="flex items-center gap-1.5 flex-shrink-0">
                                ${tagHtml}
                            </div>
                        </div>
                    `;
                });
                html += `</div>`;

                // Retroalimentación pedagógica
                if (item.retroalimentacion) {
                    html += `
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 text-[11px] text-slate-600 flex items-start gap-2">
                            <i class="fas fa-lightbulb text-amber-500 mt-0.5 flex-shrink-0"></i>
                            <div>
                                <span class="font-extrabold text-slate-700 block">Explicación institucional:</span>
                                <span class="leading-relaxed">${escapeHtml(item.retroalimentacion)}</span>
                            </div>
                        </div>
                    `;
                }

                card.innerHTML = html;
                container.appendChild(card);
            });
        }

        function renderizarInfraccionesAuditoria(infracciones) {
            const container = document.getElementById('audit-infracciones-lista');
            container.innerHTML = '';

            if (infracciones.length === 0) {
                container.innerHTML = `
                    <div class="py-10 px-4 text-center rounded-2xl bg-emerald-50/60 border border-emerald-200">
                        <i class="fas fa-shield-alt text-3xl text-emerald-500 mb-2 block"></i>
                        <h4 class="font-bold text-xs sm:text-sm text-emerald-900">Sin incidentes de seguridad registrados</h4>
                        <p class="text-xs text-emerald-700 mt-0.5">El aprendiz mantuvo el foco continuo dentro de la plataforma y respetó la pantalla completa durante toda la prueba.</p>
                    </div>
                `;
                return;
            }

            infracciones.forEach((inf, idx) => {
                const item = document.createElement('div');
                item.className = 'p-3 rounded-xl border border-rose-200 bg-rose-50/40 flex items-start justify-between gap-3 text-xs';
                item.innerHTML = `
                    <div class="flex items-start gap-2.5 min-w-0">
                        <div class="w-6 h-6 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center flex-shrink-0 mt-0.5 text-xs font-black">
                            ${idx + 1}
                        </div>
                        <div class="min-w-0">
                            <span class="font-extrabold text-rose-900 block truncate">${escapeHtml(inf.tipo_evento)}</span>
                            <p class="text-slate-600 text-[11px] mt-0.5 break-words">${escapeHtml(inf.detalle)}</p>
                            <span class="text-[10px] text-slate-400 font-mono mt-1 block">IP: ${escapeHtml(inf.ip)}</span>
                        </div>
                    </div>
                    <span class="text-[11px] font-bold text-slate-500 flex-shrink-0 bg-white px-2 py-1 rounded-md border border-slate-200">
                        ${escapeHtml(inf.hora)}
                    </span>
                `;
                container.appendChild(item);
            });
        }

        function cambiarPestanaAuditoria(tab) {
            const btnPreguntas = document.getElementById('tab-btn-preguntas');
            const btnInfracciones = document.getElementById('tab-btn-infracciones');
            const contentPreguntas = document.getElementById('audit-tab-content-preguntas');
            const contentInfracciones = document.getElementById('audit-tab-content-infracciones');

            if (tab === 'preguntas') {
                btnPreguntas.className = 'px-4 py-2 rounded-xl text-xs font-black transition-all cursor-pointer flex items-center gap-2 bg-[#1A2B4C] text-[#33FF57]';
                btnInfracciones.className = 'px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center gap-2 text-slate-500 hover:bg-slate-100';
                contentPreguntas.classList.remove('hidden');
                contentInfracciones.classList.add('hidden');
            } else {
                btnInfracciones.className = 'px-4 py-2 rounded-xl text-xs font-black transition-all cursor-pointer flex items-center gap-2 bg-[#1A2B4C] text-[#33FF57]';
                btnPreguntas.className = 'px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center gap-2 text-slate-500 hover:bg-slate-100';
                contentInfracciones.classList.remove('hidden');
                contentPreguntas.classList.add('hidden');
            }
        }

        function cerrarModalAuditoriaResultado() {
            const modal = document.getElementById('modal-auditoria-resultado');
            if (modal) modal.classList.add('hidden');
            currentResultadoId = null;
            if (window.lockBodyScroll) window.lockBodyScroll(false);
        }

        // ==========================================
        // 3. ANULACIÓN DE EXAMEN
        // ==========================================
        function solicitarAnulacionExamen() {
            const modalAnulacion = document.getElementById('modal-confirmar-anulacion');
            if (modalAnulacion) {
                document.getElementById('input-motivo-anulacion').value = '';
                modalAnulacion.classList.remove('hidden');
            }
        }

        function cerrarModalAnulacion() {
            const modalAnulacion = document.getElementById('modal-confirmar-anulacion');
            if (modalAnulacion) modalAnulacion.classList.add('hidden');
        }

        async function ejecutarAnulacionConfirmada() {
            const motivo = (document.getElementById('input-motivo-anulacion')?.value || '').trim();
            if (motivo.length < 5) {
                alert('Por favor ingresa una justificación válida para anular la evaluación (mínimo 5 caracteres).');
                return;
            }

            const btn = document.getElementById('btn-confirmar-anular-accion');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin text-xs"></i> Procesando...';

            try {
                const response = await fetch(`{{ url('sisig/admin/resultados') }}/${currentResultadoId}/anular`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ motivo_anulacion: motivo })
                });

                const data = await response.json();
                if (data.success) {
                    cerrarModalAnulacion();
                    cerrarModalAuditoriaResultado();
                    if (window.mostrarAlertaBanner) {
                        window.mostrarAlertaBanner(data.message, 'success');
                    } else {
                        alert(data.message);
                    }
                    setTimeout(() => location.reload(), 800);
                } else {
                    alert(data.message || 'No se pudo anular la evaluación.');
                }
            } catch (err) {
                console.error(err);
                alert('Error al intentar anular la evaluación.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-ban text-xs"></i><span>Confirmar Anulación</span>';
            }
        }

        function escapeHtml(text) {
            if (!text) return '';
            const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
            return String(text).replace(/[&<>"']/g, m => map[m]);
        }

        // Escape para cerrar modal
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const modalAnulacion = document.getElementById('modal-confirmar-anulacion');
                if (modalAnulacion && !modalAnulacion.classList.contains('hidden')) {
                    cerrarModalAnulacion();
                    return;
                }
                cerrarModalAuditoriaResultado();
            }
        });
    </script>
</x-sisig::layouts.admin>
