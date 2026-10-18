<x-sisig::layouts.admin title="Reportes Generales SISIG">
    <div class="space-y-6 sm:space-y-8 animate-fade-in pb-12">

        <!-- 1. Encabezado de la Sección (Estilo Limpio y Unificado) -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-5 border-b border-slate-200">
            <div>
                <div class="flex items-center gap-2 text-xs font-bold text-slate-400 mb-1">
                    <span>Inicio</span>
                    <i class="fas fa-chevron-right text-[10px]"></i>
                    <span>Gestión</span>
                    <i class="fas fa-chevron-right text-[10px]"></i>
                    <span class="text-sena-green font-extrabold">Reportes</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                    <i class="fas fa-chart-line text-sena-green"></i>
                    <span>Reportes Generales SISIG</span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-3xl">
                    Genera y consulta reportes globales del proceso de inducción para facilitar la toma de decisiones y el seguimiento institucional.
                </p>
            </div>

            <div class="self-start lg:self-auto flex items-center gap-2.5">
                <span class="inline-flex items-center gap-2 px-3.5 py-2 rounded-2xl text-xs font-bold text-slate-600 bg-slate-100 border border-slate-200">
                    <i class="fas fa-calendar-alt text-sena-green"></i>
                    <span>{{ now()->translatedFormat('d F Y') }}</span>
                </span>
            </div>
        </div>

        <!-- 2. Tarjetas de Resumen Global (KPIs con estilo Gestión de Usuarios) -->
        @php
            $kpisReportes = [
                [
                    'title' => 'Aprendices Registrados',
                    'value' => $resumenGlobal['aprendices_registrados'],
                    'sub'   => 'en plataforma',
                    'icon'  => 'fa-users text-slate-700',
                    'bar'   => 'from-slate-600 to-slate-800',
                ],
                [
                    'title' => 'Inducción Completada',
                    'value' => $resumenGlobal['induccion_completada'],
                    'sub'   => 'al 100% de avance',
                    'icon'  => 'fa-check-circle text-emerald-600',
                    'bar'   => 'from-emerald-500 to-teal-600',
                ],
                [
                    'title' => 'Aprobaron Quizzes',
                    'value' => $resumenGlobal['aprobaron_quizzes'],
                    'sub'   => 'evaluaciones ≥ 70 pts',
                    'icon'  => 'fa-trophy text-amber-500',
                    'bar'   => 'from-amber-400 to-orange-500',
                ],
                [
                    'title' => 'No Aprobaron Quizzes',
                    'value' => $resumenGlobal['no_aprobaron_quizzes'],
                    'sub'   => 'evaluaciones < 70 pts',
                    'icon'  => 'fa-times-circle text-rose-500',
                    'bar'   => 'from-rose-500 to-red-600',
                ],
                [
                    'title' => 'Alertas',
                    'value' => $resumenGlobal['alertas_antitrampa'] ?? 0,
                    'sub'   => 'antitrampa en quizzes',
                    'icon'  => 'fa-shield-alt text-indigo-600',
                    'bar'   => 'from-indigo-500 to-purple-600',
                ],
            ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-5">
            @foreach($kpisReportes as $kpi)
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

        <!-- ========================================================================= -->
        <!-- 3. REPORTE 1: APRENDICES REGISTRADOS EN LA PLATAFORMA                     -->
        <!-- ========================================================================= -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-6">
            
            <!-- Cabecera del Reporte 1 -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-sena-green block">
                        Reporte 1 — Seguimiento Individual
                    </span>
                    <h2 class="text-lg sm:text-xl font-black text-slate-800 flex items-center gap-2.5 mt-0.5">
                        <i class="fas fa-table text-sena-green"></i>
                        <span>Listado de Aprendices Registrados</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Registro independiente por sección SIG con seguimiento de progreso y estado
                    </p>
                </div>
                
                <div class="flex items-center gap-3 self-start sm:self-auto">
                    <span class="text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1.5 rounded-xl">
                        Registros: <strong class="text-slate-800">{{ $reporte1Filas->count() }}</strong>
                    </span>
                    <button type="button" onclick="exportarTablaExcel('tabla-reporte-1', 'Reporte_Aprendices_Registrados')"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-xs font-bold text-white bg-sena-green hover:bg-emerald-600 shadow-sena hover:shadow-lg transition-all transform hover:-translate-y-0.5 cursor-pointer">
                        <i class="fas fa-file-excel text-xs"></i>
                        <span>Exportar Excel</span>
                    </button>
                </div>
            </div>

            <!-- Filtros Funcionales del Reporte 1 -->
            <form method="GET" action="{{ route('sisig.admin.reportes.index') }}" class="bg-slate-50 p-4 sm:p-5 rounded-2xl border border-slate-200 space-y-3">
                <input type="hidden" name="seccion_quiz" value="{{ $seccionQuizId }}">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-center">
                    
                    <!-- Filtro Sección SIG -->
                    <div>
                        <label for="seccion_sig" class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-wider mb-1">
                            Sección SIG
                        </label>
                        <select name="seccion_sig" id="seccion_sig" onchange="this.form.submit()"
                                class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none text-xs font-bold text-slate-700 transition-all">
                            <option value="todas" {{ $filtroSeccion === 'todas' ? 'selected' : '' }}>Todas las Secciones</option>
                            @foreach($modulosDisponibles as $mod)
                                <option value="{{ $mod['id'] }}" {{ (string)$filtroSeccion === (string)$mod['id'] ? 'selected' : '' }}>
                                    {{ $mod['nombre'] }} ({{ $mod['norma'] }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro Estado -->
                    <div>
                        <label for="estado" class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-wider mb-1">
                            Estado
                        </label>
                        <select name="estado" id="estado" onchange="this.form.submit()"
                                class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none text-xs font-bold text-slate-700 transition-all">
                            <option value="todos" {{ $filtroEstado === 'todos' ? 'selected' : '' }}>Todos los Estados</option>
                            <option value="completado" {{ $filtroEstado === 'completado' ? 'selected' : '' }}>Completado</option>
                            <option value="en_progreso" {{ $filtroEstado === 'en_progreso' ? 'selected' : '' }}>En progreso</option>
                            <option value="sin_iniciar" {{ $filtroEstado === 'sin_iniciar' ? 'selected' : '' }}>Sin iniciar</option>
                        </select>
                    </div>

                    <!-- Filtro Ficha -->
                    <div>
                        <label for="ficha" class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-wider mb-1">
                            Ficha / Curso
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                                <i class="fas fa-search text-xs"></i>
                            </span>
                            <input type="text" name="ficha" id="ficha" value="{{ $filtroFicha }}" placeholder="Ej. 2845621"
                                   class="w-full pl-8 pr-3 py-2 rounded-xl bg-white border border-slate-200 focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none text-xs font-medium text-slate-800 transition-all">
                        </div>
                    </div>

                    <!-- Botones de Acción de Filtro -->
                    <div class="flex items-center gap-2 pt-4 sm:pt-4">
                        <button type="submit" 
                                class="flex-1 px-4 py-2 rounded-xl text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 shadow-sm transition-all text-center cursor-pointer">
                            <i class="fas fa-filter text-xs mr-1"></i> Filtrar
                        </button>
                        <a href="{{ route('sisig.admin.reportes.index', ['seccion_quiz' => $seccionQuizId]) }}" 
                           title="Restablecer filtros"
                           class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-600 bg-white hover:bg-slate-200 border border-slate-200 transition-all flex items-center justify-center cursor-pointer">
                            <i class="fas fa-undo text-xs"></i>
                            <span class="ml-1 hidden md:inline">Limpiar</span>
                        </a>
                    </div>

                </div>
            </form>

            <!-- Tabla del Reporte 1 -->
            <div class="overflow-x-auto rounded-2xl border border-slate-100">
                <table id="tabla-reporte-1" class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/70 text-slate-400 font-extrabold uppercase tracking-wider text-[11px]">
                            <th class="py-3.5 px-4 w-12 text-center">#</th>
                            <th class="py-3.5 px-4">Aprendiz</th>
                            <th class="py-3.5 px-4">Documento</th>
                            <th class="py-3.5 px-4">Correo</th>
                            <th class="py-3.5 px-4">Ficha</th>
                            <th class="py-3.5 px-4">Sección</th>
                            <th class="py-3.5 px-4">Progreso</th>
                            <th class="py-3.5 px-4">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($reporte1Filas as $index => $item)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <!-- # Contador -->
                                <td class="py-3.5 px-4 text-center font-bold text-slate-400">
                                    {{ $index + 1 }}
                                </td>

                                <!-- Aprendiz con Avatar -->
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        @php
                                            $partesNombre = preg_split('/\s+/', trim($item->aprendiz));
                                            $iniciales = count($partesNombre) >= 2
                                                ? mb_strtoupper(mb_substr($partesNombre[0], 0, 1) . mb_substr($partesNombre[1], 0, 1))
                                                : mb_strtoupper(mb_substr($item->aprendiz, 0, 2));
                                        @endphp
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-slate-700 to-slate-800 text-white font-black text-xs flex items-center justify-center flex-shrink-0 shadow-sm border border-slate-200/50">
                                            {{ $iniciales }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-800 block text-xs sm:text-sm">{{ $item->aprendiz }}</span>
                                            <span class="text-[10px] text-slate-400 font-medium">Aprendiz SISIG</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Documento -->
                                <td class="py-3.5 px-4 text-slate-700 font-mono text-xs font-bold">
                                    {{ $item->documento }}
                                </td>

                                <!-- Correo Electrónico -->
                                <td class="py-3.5 px-4 text-slate-600 font-medium">
                                    {{ $item->correo }}
                                </td>

                                <!-- Ficha -->
                                <td class="py-3.5 px-4">
                                    <span class="font-mono text-xs font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded-lg border border-slate-200/80">
                                        {{ $item->ficha }}
                                    </span>
                                </td>

                                <!-- Sección -->
                                <td class="py-3.5 px-4 font-semibold text-slate-700">
                                    {{ $item->seccion }}
                                </td>

                                <!-- Progreso con Barra Visual -->
                                <td class="py-3.5 px-4">
                                    <div class="w-28 sm:w-32">
                                        <div class="flex items-center justify-between text-[11px] mb-1">
                                            <span class="font-bold text-slate-700">{{ $item->progreso }}%</span>
                                        </div>
                                        <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                            @php
                                                $barColor = $item->progreso >= 100 
                                                    ? 'bg-sena-green' 
                                                    : ($item->progreso > 0 ? 'bg-amber-500' : 'bg-slate-300');
                                            @endphp
                                            <div class="{{ $barColor }} h-1.5 rounded-full transition-all duration-500" style="width: {{ $item->progreso }}%"></div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Estado con Badge -->
                                <td class="py-3.5 px-4">
                                    @php
                                        $estadoStyle = match($item->estado) {
                                            'Completado'  => 'text-emerald-600 bg-emerald-50 border-emerald-200',
                                            'En progreso' => 'text-amber-600 bg-amber-50 border-amber-200',
                                            'Sin iniciar' => 'text-slate-500 bg-slate-100 border-slate-200',
                                            default       => 'text-slate-500 bg-slate-100 border-slate-200',
                                        };
                                        $dotColor = match($item->estado) {
                                            'Completado'  => 'bg-emerald-500',
                                            'En progreso' => 'bg-amber-500',
                                            'Sin iniciar' => 'bg-slate-400',
                                            default       => 'bg-slate-400',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $estadoStyle }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>
                                        <span>{{ $item->estado }}</span>
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-10 text-slate-400">
                                    <i class="fas fa-info-circle text-2xl text-slate-300 mb-2 block"></i>
                                    <span class="font-bold">No se encontraron registros de aprendices con los filtros seleccionados.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

        <!-- ========================================================================= -->
        <!-- 4. REPORTE 2: RESULTADOS DE QUIZZES POR SECCIÓN SIG                       -->
        <!-- ========================================================================= -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-6">
            
            <!-- Cabecera del Reporte 2 -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-sena-green block">
                        Reporte 2 — Evaluación por Sección
                    </span>
                    <h2 class="text-lg sm:text-xl font-black text-slate-800 flex items-center gap-2.5 mt-0.5">
                        <i class="fas fa-clipboard-check text-sena-green"></i>
                        <span>Aprobados y No Aprobados por Sección SIG</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Consulta y exporta el rendimiento de los aprendices en las evaluaciones de inducción
                    </p>
                </div>

                <div class="self-start sm:self-auto">
                    <button type="button" onclick="exportarReporte2Excel()"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-xs font-bold text-white bg-sena-green hover:bg-emerald-600 shadow-sena hover:shadow-lg transition-all transform hover:-translate-y-0.5 cursor-pointer">
                        <i class="fas fa-file-excel text-xs"></i>
                        <span>Exportar Excel</span>
                    </button>
                </div>
            </div>

            <!-- Selector de Pestañas Independientes por Sección (Cambio Instantáneo Sin Recarga) -->
            <div class="flex flex-wrap items-center gap-2 text-xs" id="tabs-reporte-2">
                @forelse($modulosDisponibles as $mod)
                    @php
                        $isActive = ($seccionQuizId == $mod['id']);
                    @endphp
                    <button type="button"
                            onclick="cambiarSeccionQuiz({{ $mod['id'] }})"
                            data-modulo-id="{{ $mod['id'] }}"
                            class="tab-quiz-btn px-4 py-2 rounded-2xl font-bold transition-all flex items-center gap-2 cursor-pointer {{ $isActive ? 'bg-sena-green text-white shadow-sena transform -translate-y-0.5' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 border border-slate-200' }}">
                        <i class="tab-icon {{ $isActive ? 'fas fa-check-circle text-xs' : 'fas fa-layer-group text-xs text-slate-400' }}"></i>
                        <span>{{ $mod['nombre'] }}</span>
                        <span class="text-[10px] opacity-80 font-mono">({{ $mod['norma'] }})</span>
                    </button>
                @empty
                    <span class="text-xs text-slate-400 italic">No hay módulos disponibles registrados.</span>
                @endforelse
            </div>

            <!-- Panel de Métricas de la Sección Activa con Gráfica Donut -->
            <div class="bg-slate-50/90 rounded-3xl p-6 border border-slate-200 flex flex-col md:flex-row items-center justify-between gap-6">
                
                <!-- Gráfica Donut SVG Dinámica -->
                <div class="flex items-center gap-6">
                    <div class="relative w-28 h-28 flex-shrink-0">
                        @php
                            $pct = $metricasSeccionQuiz['porcentaje_aprobados'] ?? 0;
                            $circunferencia = 238.76;
                            $strokeDashoffset = $circunferencia - ($pct / 100) * $circunferencia;
                        @endphp
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                            <!-- Fondo del Donut -->
                            <circle cx="50" cy="50" r="38" stroke="#e2e8f0" stroke-width="12" fill="transparent" />
                            <!-- Segmento Aprobados Verde SENA Dinámico -->
                            <circle id="donut-circle" cx="50" cy="50" r="38" stroke="#39A900" stroke-width="12" fill="transparent"
                                    stroke-dasharray="{{ $circunferencia }}"
                                    style="stroke-dashoffset: {{ $strokeDashoffset }}; transition: stroke-dashoffset 0.8s ease;"
                                    stroke-linecap="round" />
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                            <span id="donut-porcentaje" class="text-xl font-black text-slate-800 leading-none">{{ $pct }}%</span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter mt-0.5">Aprobados</span>
                        </div>
                    </div>

                    <!-- Leyenda de Estados de la Sección -->
                    <div class="space-y-2 text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-sena-green inline-block flex-shrink-0"></span>
                            <span class="font-bold text-slate-700">Aprobaron:</span>
                            <strong id="contador-aprobados" class="text-emerald-700 font-extrabold">{{ $metricasSeccionQuiz['aprobados'] ?? 0 }}</strong>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-amber-500 inline-block flex-shrink-0"></span>
                            <span class="font-bold text-slate-700">Pendientes:</span>
                            <strong id="contador-pendientes" class="text-amber-700 font-extrabold">{{ $metricasSeccionQuiz['pendientes'] ?? 0 }}</strong>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-rose-600 inline-block flex-shrink-0"></span>
                            <span class="font-bold text-slate-700">Trampa / Anulados:</span>
                            <strong id="contador-anulados" class="text-rose-700 font-extrabold">{{ $metricasSeccionQuiz['anulados'] ?? 0 }}</strong>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-slate-400 inline-block flex-shrink-0"></span>
                            <span class="font-bold text-slate-500">No aprobaron (Puntaje):</span>
                            <strong id="contador-no-aprobados" class="text-slate-700 font-extrabold">{{ max(0, ($metricasSeccionQuiz['no_aprobados'] ?? 0) - ($metricasSeccionQuiz['anulados'] ?? 0)) }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Detalle Contextual de la Sección -->
                <div class="text-left md:text-right border-t md:border-t-0 md:border-l border-slate-200/80 pt-4 md:pt-0 md:pl-6 w-full md:w-auto">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block">
                        Sección Activa
                    </span>
                    <h3 class="text-xl font-black text-slate-800 tracking-tight mt-0.5">
                        <span id="seccion-nombre-texto">{{ $metricasSeccionQuiz['seccion_nombre'] ?? 'Sin sección' }}</span> 
                        <span id="seccion-norma-texto" class="text-sena-green">· {{ $metricasSeccionQuiz['seccion_norma'] ?? '' }}</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-1 font-medium">
                        Total evaluados: <strong id="total-evaluados-texto" class="text-slate-800 font-bold">{{ $metricasSeccionQuiz['total_evaluados'] ?? 0 }}</strong> aprendices
                    </p>
                </div>

            </div>

            <!-- Tabla de Evaluaciones de la Sección Activa -->
            <div class="overflow-x-auto rounded-2xl border border-slate-100">
                <table id="tabla-reporte-2" class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/70 text-slate-400 font-extrabold uppercase tracking-wider text-[11px]">
                            <th class="py-3.5 px-4 w-12 text-center">#</th>
                            <th class="py-3.5 px-4">Aprendiz</th>
                            <th class="py-3.5 px-4">Ficha</th>
                            <th class="py-3.5 px-4 text-center">Intentos</th>
                            <th class="py-3.5 px-4 text-center">Puntaje</th>
                            <th class="py-3.5 px-4">Resultado</th>
                            <th class="py-3.5 px-4">Fecha</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-reporte-2-body" class="divide-y divide-slate-100">
                        @forelse($reporte2Filas as $index => $item)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <!-- # Contador -->
                                <td class="py-3.5 px-4 text-center font-bold text-slate-400">
                                    {{ $index + 1 }}
                                </td>

                                <!-- Aprendiz con Avatar -->
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        @php
                                            $nombreApp = data_get($item, 'aprendiz', 'Aprendiz');
                                            $partesNombre2 = preg_split('/\s+/', trim($nombreApp));
                                            $iniciales2 = count($partesNombre2) >= 2
                                                ? mb_strtoupper(mb_substr($partesNombre2[0], 0, 1) . mb_substr($partesNombre2[1], 0, 1))
                                                : mb_strtoupper(mb_substr($nombreApp, 0, 2));
                                            $inicFinal = data_get($item, 'iniciales', $iniciales2);
                                            $puntajeNum = data_get($item, 'puntaje_num');
                                            $puntajeTxt = data_get($item, 'puntaje', '—');
                                            $resTxt = data_get($item, 'resultado', 'Pendiente');
                                            $estadoTipo = data_get($item, 'estado_tipo', 'pendiente');
                                            $motivoAnul = data_get($item, 'motivo_anulacion', '');
                                            $infraccNum = data_get($item, 'infracciones', 0);
                                            $fichaTxt = data_get($item, 'ficha', 'Sin asignar');
                                            $intentosTxt = data_get($item, 'intentos', '—');
                                            $fechaTxt = data_get($item, 'fecha', '—');
                                        @endphp
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-slate-700 to-slate-800 text-white font-black text-xs flex items-center justify-center flex-shrink-0 shadow-sm border border-slate-200/50">
                                            {{ $inicFinal }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-800 block text-xs sm:text-sm">{{ $nombreApp }}</span>
                                            <span class="text-[10px] text-slate-400 font-medium">Evaluación <span class="modulo-row-nombre">{{ $metricasSeccionQuiz['seccion_nombre'] ?? '' }}</span></span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Ficha -->
                                <td class="py-3.5 px-4">
                                    <span class="font-mono text-xs font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded-lg border border-slate-200/80">
                                        {{ $fichaTxt }}
                                    </span>
                                </td>

                                <!-- Intentos -->
                                <td class="py-3.5 px-4 text-center font-mono font-bold text-slate-700">
                                    {{ $intentosTxt }}
                                </td>

                                <!-- Puntaje con Color -->
                                <td class="py-3.5 px-4 text-center font-mono font-black text-xs">
                                    @if($puntajeNum !== null)
                                        <span class="{{ $puntajeNum >= 70 ? 'text-sena-green font-extrabold' : 'text-rose-600 font-extrabold' }}">
                                            {{ $puntajeTxt }}
                                        </span>
                                    @else
                                        <span class="text-slate-300 font-bold">—</span>
                                    @endif
                                </td>

                                <!-- Resultado (Rojo si trampa, Verde si aprobado, Naranja si pendiente) -->
                                <td class="py-3.5 px-4 font-semibold">
                                    @if($estadoTipo === 'trampa')
                                        <div class="inline-flex flex-col items-start gap-1">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-black bg-rose-100 text-rose-700 border border-rose-200" title="{{ $motivoAnul }}">
                                                <i class="fas fa-ban text-[11px] text-rose-600"></i>
                                                <span>Anulado (Trampa)</span>
                                            </span>
                                            @if($infraccNum > 0)
                                                <span class="text-[10px] text-rose-500 font-bold ml-1">{{ $infraccNum }} {{ $infraccNum == 1 ? 'infracción' : 'infracciones' }}</span>
                                            @endif
                                        </div>
                                    @elseif($estadoTipo === 'aprobado')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            <i class="fas fa-check-circle text-[11px] text-emerald-600"></i>
                                            <span>Aprobado</span>
                                        </span>
                                    @elseif($estadoTipo === 'pendiente')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-black bg-amber-100 text-amber-800 border border-amber-200">
                                            <i class="fas fa-clock text-[11px] text-amber-600"></i>
                                            <span>Pendiente</span>
                                        </span>
                                    @else
                                        <div class="inline-flex flex-col items-start gap-0.5">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200" title="Puntaje menor al 70%">
                                                <i class="fas fa-times-circle text-[11px] text-slate-500"></i>
                                                <span>No aprobado</span>
                                            </span>
                                            <span class="text-[10px] text-slate-400 font-medium ml-1">Contestó mal</span>
                                        </div>
                                    @endif
                                </td>

                                <!-- Fecha -->
                                <td class="py-3.5 px-4 text-slate-600 font-medium">
                                    {{ $fechaTxt }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-10 text-slate-400">
                                    <i class="fas fa-info-circle text-2xl text-slate-300 mb-2 block"></i>
                                    <span class="font-bold">No hay aprendices registrados para evaluar en esta sección.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>

    <!-- Librería SheetJS para exportación nativa directa a Microsoft Excel (.xlsx) -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script>
        // Datos reactivos precargados para conmutación instantánea (sin recarga)
        const datosReporte2PorModulo = @json($datosReporte2PorModulo);
        let moduloActivoId = {{ $seccionQuizId ?? 'null' }};
        let moduloActivoNombre = "{{ $moduloActivo['nombre'] ?? 'Modulo' }}";

        // Función para cambiar de sección al instante sin recarga de página
        function cambiarSeccionQuiz(id) {
            const data = datosReporte2PorModulo[id];
            if (!data) return;

            moduloActivoId = id;
            moduloActivoNombre = data.nombre;

            // 1. Actualizar apariencia visual de las pestañas
            document.querySelectorAll('.tab-quiz-btn').forEach(btn => {
                const esActivo = (btn.dataset.moduloId == id);
                if (esActivo) {
                    btn.className = 'tab-quiz-btn px-4 py-2 rounded-2xl font-bold transition-all flex items-center gap-2 cursor-pointer bg-sena-green text-white shadow-sena transform -translate-y-0.5';
                    btn.querySelector('.tab-icon').className = 'tab-icon fas fa-check-circle text-xs';
                } else {
                    btn.className = 'tab-quiz-btn px-4 py-2 rounded-2xl font-bold transition-all flex items-center gap-2 cursor-pointer bg-slate-100 text-slate-600 hover:bg-slate-200 border border-slate-200';
                    btn.querySelector('.tab-icon').className = 'tab-icon fas fa-layer-group text-xs text-slate-400';
                }
            });

            // 2. Animar y actualizar la gráfica Donut
            const pct = data.porcentaje_aprobados || 0;
            const circunferencia = 238.76;
            const strokeDashoffset = circunferencia - (pct / 100) * circunferencia;

            const circle = document.getElementById('donut-circle');
            if (circle) {
                circle.style.strokeDashoffset = strokeDashoffset;
            }

            const pctTexto = document.getElementById('donut-porcentaje');
            if (pctTexto) {
                pctTexto.innerText = pct + '%';
            }

            // 3. Actualizar contadores y textos contextuales
            document.getElementById('contador-aprobados').innerText = data.aprobados;
            document.getElementById('contador-pendientes').innerText = data.pendientes;
            if (document.getElementById('contador-anulados')) {
                document.getElementById('contador-anulados').innerText = data.anulados || 0;
            }
            if (document.getElementById('contador-no-aprobados')) {
                document.getElementById('contador-no-aprobados').innerText = Math.max(0, (data.no_aprobados || 0) - (data.anulados || 0));
            }
            document.getElementById('seccion-nombre-texto').innerText = data.nombre;
            document.getElementById('seccion-norma-texto').innerText = '· ' + data.norma;
            document.getElementById('total-evaluados-texto').innerText = data.total_evaluados;

            // 4. Renderizar las filas de la tabla de esa sección
            const tbody = document.getElementById('tabla-reporte-2-body');
            if (!tbody) return;

            if (!data.filas || data.filas.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center py-10 text-slate-400">
                            <i class="fas fa-info-circle text-2xl text-slate-300 mb-2 block"></i>
                            <span class="font-bold">No hay aprendices registrados para evaluar en esta sección.</span>
                        </td>
                    </tr>
                `;
                return;
            }

            let html = '';
            data.filas.forEach((item, index) => {
                let puntajeHtml = '<span class="text-slate-300 font-bold">—</span>';
                if (item.puntaje_num !== null) {
                    const col = item.puntaje_num >= 70 ? 'text-sena-green font-extrabold' : 'text-rose-600 font-extrabold';
                    puntajeHtml = `<span class="${col}">${item.puntaje}</span>`;
                }

                let resultadoHtml = '';
                if (item.estado_tipo === 'trampa' || item.fue_anulado) {
                    let subFaltas = (item.infracciones && item.infracciones > 0)
                        ? `<span class="text-[10px] text-rose-500 font-bold ml-1">${item.infracciones} ${item.infracciones === 1 ? 'infracción' : 'infracciones'}</span>`
                        : '';
                    resultadoHtml = `
                        <div class="inline-flex flex-col items-start gap-1">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-black bg-rose-100 text-rose-700 border border-rose-200" title="${item.motivo_anulacion || 'Infracciones anti-trampa'}">
                                <i class="fas fa-ban text-[11px] text-rose-600"></i>
                                <span>Anulado (Trampa)</span>
                            </span>
                            ${subFaltas}
                        </div>
                    `;
                } else if (item.estado_tipo === 'aprobado') {
                    resultadoHtml = `
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-200">
                            <i class="fas fa-check-circle text-[11px] text-emerald-600"></i>
                            <span>Aprobado</span>
                        </span>
                    `;
                } else if (item.estado_tipo === 'pendiente') {
                    resultadoHtml = `
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-black bg-amber-100 text-amber-800 border border-amber-200">
                            <i class="fas fa-clock text-[11px] text-amber-600"></i>
                            <span>Pendiente</span>
                        </span>
                    `;
                } else {
                    resultadoHtml = `
                        <div class="inline-flex flex-col items-start gap-0.5">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200" title="Puntaje menor al 70%">
                                <i class="fas fa-times-circle text-[11px] text-slate-500"></i>
                                <span>No aprobado</span>
                            </span>
                            <span class="text-[10px] text-slate-400 font-medium ml-1">Contestó mal</span>
                        </div>
                    `;
                }

                html += `
                    <tr class="hover:bg-slate-50/80 transition-colors animate-fade-in">
                        <td class="py-3.5 px-4 text-center font-bold text-slate-400">${index + 1}</td>
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-slate-700 to-slate-800 text-white font-black text-xs flex items-center justify-center flex-shrink-0 shadow-sm border border-slate-200/50">
                                    ${item.iniciales}
                                </div>
                                <div>
                                    <span class="font-bold text-slate-800 block text-xs sm:text-sm">${item.aprendiz}</span>
                                    <span class="text-[10px] text-slate-400 font-medium">Evaluación ${data.nombre}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="font-mono text-xs font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded-lg border border-slate-200/80">
                                ${item.ficha}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-center font-mono font-bold text-slate-700">${item.intentos}</td>
                        <td class="py-3.5 px-4 text-center font-mono font-black text-xs">${puntajeHtml}</td>
                        <td class="py-3.5 px-4 font-semibold">${resultadoHtml}</td>
                        <td class="py-3.5 px-4 text-slate-600 font-medium">${item.fecha}</td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
        }

        // Exportar Reporte 2 adaptándose automáticamente a la sección activa
        function exportarReporte2Excel() {
            exportarTablaExcel('tabla-reporte-2', 'Resultados_Quiz_' + moduloActivoNombre);
        }

        // Función base de exportación a archivo binario .xlsx nativo
        function exportarTablaExcel(tablaId, nombreArchivo) {
            const tablaOriginal = document.getElementById(tablaId);
            if (!tablaOriginal) return;

            // Clonar la tabla para limpiar el contenido antes de pasarlo a Excel
            const tablaClon = tablaOriginal.cloneNode(true);

            // Limpiar texto de celdas para que no queden saltos de línea extraños
            const filas = tablaClon.querySelectorAll('tr');
            filas.forEach(fila => {
                const celdas = fila.querySelectorAll('th, td');
                celdas.forEach(celda => {
                    let texto = celda.innerText.replace(/(\r\n|\n|\r)/gm, ' ').replace(/\s+/g, ' ').trim();
                    celda.innerText = texto;
                });
            });

            // Generar libro de trabajo Excel nativo
            const libroExcel = XLSX.utils.table_to_book(tablaClon, { 
                sheet: "Reporte SISIG",
                raw: true 
            });

            // Autoajustar el ancho de las columnas
            const hoja = libroExcel.Sheets["Reporte SISIG"];
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

            // Descargar directamente en formato .xlsx
            const fechaHoy = new Date().toISOString().slice(0, 10);
            XLSX.writeFile(libroExcel, `${nombreArchivo}_${fechaHoy}.xlsx`);
        }
    </script>
</x-sisig::layouts.admin>
