<x-sisig::layouts.admin title="Dashboard Administrador">
    <div class="space-y-8 w-full max-w-[1600px] mx-auto pb-10">

        <!-- 1. Banner Principal Ejecutivo de Bienvenida -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#1A2B4C] via-[#1E3A5F] to-[#243B66] border border-[#243B66] p-7 sm:p-9 text-white shadow-2xl">
            <!-- Destellos y brillos ambientales de fondo -->
            <div class="absolute -top-24 -right-24 w-80 h-80 bg-[#33FF57]/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-[#00BCD4]/15 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <!-- Columna Izquierda: Información de bienvenida -->
                <div class="space-y-3">
                    <div>
                        <span class="text-[#33FF57] text-xs font-black uppercase tracking-widest">
                            Panel de Supervisión Estratégica SISIG
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-tight">
                        ¡Hola, <span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-slate-100 to-emerald-300">{{ auth()->user()->full_name ?? auth()->user()->name }}</span>!
                    </h1>

                    <p class="text-slate-300 text-xs sm:text-sm max-w-2xl font-normal leading-relaxed">
                        Control general de las inducciones formativas y evaluaciones en <strong class="text-white">Seguridad y Salud (SST)</strong>, <strong class="text-white">Gestión de la Calidad</strong> y <strong class="text-white">Gestión Ambiental</strong> para aprendices del Centro Agroindustrial "La Angostura".
                    </p>
                </div>

                <!-- Columna Derecha: Fecha actual limpia -->
                <div class="flex items-center gap-2.5 self-start lg:self-center bg-white/10 backdrop-blur-md px-4 py-2.5 rounded-full border border-white/15 text-white shadow-sm">
                    <i class="far fa-calendar-check text-[#33FF57] text-base"></i>
                    <span class="text-xs sm:text-sm font-bold text-slate-100 capitalize">
                        {{ now()->isoFormat('dddd, D [de] MMMM') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- 2. Tarjetas de Métricas Clave (KPIs) con Estilo de Referencia Image 1 -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            
            <!-- KPI 1: Aprendices Registrados -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Aprendices SENA</span>
                    <div class="w-8 h-8 rounded-full bg-blue-50 text-[#1A2B4C] flex items-center justify-center text-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#343A40] tracking-tight">{{ number_format($totalAprendices) }}</h3>
                    <div class="mt-1 flex items-center justify-between text-xs text-slate-400 font-medium">
                        <span>Asignados a formación</span>
                        <span class="text-xs font-bold text-[#2E6F40]">Etapa Práctica</span>
                    </div>
                </div>
            </div>

            <!-- KPI 2: Evaluaciones Activas -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Quices Activos</span>
                    <div class="w-8 h-8 rounded-full bg-emerald-50 text-[#2E6F40] flex items-center justify-center text-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="flex items-baseline gap-1.5">
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-[#343A40] tracking-tight">{{ $quicesActivos }}</h3>
                        <span class="text-xs font-semibold text-slate-400">/ {{ $totalQuices }} creados</span>
                    </div>
                    <div class="mt-1 flex items-center justify-between text-xs text-slate-400 font-medium">
                        <span>Habilitados para presentar</span>
                        <span class="text-xs font-bold text-[#2E6F40]">Disponibles</span>
                    </div>
                </div>
            </div>

            <!-- KPI 3: Tasa de Aprobación -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Tasa de Aprobación</span>
                    <div class="w-8 h-8 rounded-full bg-cyan-50 text-[#00BCD4] flex items-center justify-center text-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#343A40] tracking-tight">{{ $tasaAprobacion }}%</h3>
                    <div class="mt-1 flex items-center justify-between text-xs text-slate-400 font-medium">
                        <span>{{ $evaluacionesAprobadas }} aprobados</span>
                        <span class="text-rose-500 font-bold">{{ $evaluacionesReprobadas }} reprobados</span>
                    </div>
                </div>
            </div>

            <!-- KPI 4: Monitoreo Anti-Fraude e Infracciones -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Control Anti-Fraude</span>
                    <div class="w-8 h-8 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center text-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                        <i class="fas fa-shield-virus"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#343A40] tracking-tight">{{ $totalInfracciones }}</h3>
                    <div class="mt-1 flex items-center justify-between text-xs text-slate-400 font-medium">
                        <span>Alertas registradas</span>
                        <span class="text-xs font-bold {{ $evaluacionesAnuladas > 0 ? 'text-rose-600' : 'text-[#2E6F40]' }}">
                            {{ $evaluacionesAnuladas }} Anuladas
                        </span>
                    </div>
                </div>
            </div>

        </div>

        <!-- 3. Sección de Visualización Gráfica y Rendimiento del SIG -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Gráfica de Barras: Rendimiento por Módulo SIG (2 columnas) -->
            <div class="lg:col-span-2 bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-sm flex flex-col justify-between">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-800 flex items-center gap-2.5">
                            <i class="fas fa-layer-group text-sena-green"></i>
                            <span>Rendimiento y Evaluaciones por Módulo SIG</span>
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Estadísticas y tasa de aprobación de los módulos registrados en el sistema ({{ $modulosEstadisticas->count() }} {{ $modulosEstadisticas->count() == 1 ? 'módulo' : 'módulos' }})
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            {{ $modulosEstadisticas->where('estado', 'publicado')->count() }} Publicados
                        </span>
                        <a href="{{ route('sisig.admin.modulos.index') }}" class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold text-slate-600 hover:text-sena-green hover:bg-slate-50 rounded-lg transition" title="Ir a Gestión de Módulos">
                            <i class="fas fa-cog"></i>
                            <span class="hidden sm:inline">Gestionar</span>
                        </a>
                    </div>
                </div>

                @if($modulosEstadisticas->isEmpty())
                    <div class="py-12 h-64 sm:h-72 w-full flex flex-col items-center justify-center text-center p-6 bg-slate-50/50 rounded-2xl border border-dashed border-slate-200 my-4">
                        <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center text-sena-green mb-3">
                            <i class="fas fa-layer-group text-xl"></i>
                        </div>
                        <p class="text-slate-700 font-bold text-sm">No hay módulos registrados en el sistema</p>
                        <p class="text-xs text-slate-500 max-w-xs mt-1">Registra nuevos módulos en la sección de Gestión de Módulos para visualizar su rendimiento.</p>
                        <a href="{{ route('sisig.admin.modulos.index') }}" class="mt-3.5 inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold bg-sena-green text-white hover:bg-emerald-700 transition shadow-sm">
                            <i class="fas fa-plus"></i> Crear Primer Módulo
                        </a>
                    </div>
                @else
                    <div class="py-6 h-64 sm:h-72 w-full relative">
                        <canvas id="chartEjesSIG"></canvas>
                    </div>

                    @php
                        $paletasModulos = [
                            ['bg' => 'bg-emerald-50/70', 'border' => 'border-emerald-200', 'text' => 'text-emerald-700', 'badge' => 'bg-emerald-100 text-emerald-800'],
                            ['bg' => 'bg-sky-50/70', 'border' => 'border-sky-200', 'text' => 'text-sky-700', 'badge' => 'bg-sky-100 text-sky-800'],
                            ['bg' => 'bg-amber-50/70', 'border' => 'border-amber-200', 'text' => 'text-amber-700', 'badge' => 'bg-amber-100 text-amber-800'],
                            ['bg' => 'bg-purple-50/70', 'border' => 'border-purple-200', 'text' => 'text-purple-700', 'badge' => 'bg-purple-100 text-purple-800'],
                            ['bg' => 'bg-rose-50/70', 'border' => 'border-rose-200', 'text' => 'text-rose-700', 'badge' => 'bg-rose-100 text-rose-800'],
                            ['bg' => 'bg-indigo-50/70', 'border' => 'border-indigo-200', 'text' => 'text-indigo-700', 'badge' => 'bg-indigo-100 text-indigo-800'],
                            ['bg' => 'bg-teal-50/70', 'border' => 'border-teal-200', 'text' => 'text-teal-700', 'badge' => 'bg-teal-100 text-teal-800'],
                        ];
                    @endphp

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 {{ $modulosEstadisticas->count() >= 4 ? 'xl:grid-cols-4' : '' }} gap-3 pt-4 border-t border-slate-100">
                        @foreach($modulosEstadisticas as $idx => $modStat)
                            <div class="rounded-2xl bg-white border border-slate-200/90 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between overflow-hidden">
                                <div class="bg-gradient-to-r from-[#1A2B4C] to-[#243B66] px-3 py-2 text-white flex items-center justify-between gap-1">
                                    <span class="text-[10px] font-bold tracking-wider uppercase text-slate-200 truncate" title="{{ $modStat->norma ?? 'Módulo Registrado' }}" style="color: #e2e8f0 !important;">
                                        {{ $modStat->norma ? Str::limit($modStat->norma, 16) : 'Módulo #'.$modStat->id }}
                                    </span>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full font-extrabold {{ $modStat->tasa_aprobacion >= 70 ? 'bg-[#33FF57]/20 text-[#33FF57]' : ($modStat->total_evaluaciones > 0 ? 'bg-amber-500/20 text-amber-300' : 'bg-white/10 text-slate-300') }}">
                                        {{ $modStat->tasa_aprobacion }}%
                                    </span>
                                </div>
                                <div class="p-3 bg-white">
                                    <strong class="text-[#343A40] font-extrabold text-xs sm:text-sm block truncate" title="{{ $modStat->titulo }}">
                                        {{ ucfirst($modStat->titulo) }}
                                    </strong>
                                    <div class="flex items-center justify-between text-[11px] text-slate-400 mt-2 pt-2 border-t border-slate-100">
                                        <span><i class="fas fa-file-signature text-[10px] mr-1 text-[#2E6F40]"></i>{{ $modStat->total_quices }} {{ $modStat->total_quices == 1 ? 'quiz' : 'quices' }}</span>
                                        <span><i class="fas fa-user-check text-[10px] mr-1 text-[#00BCD4]"></i>{{ $modStat->total_evaluaciones }} {{ $modStat->total_evaluaciones == 1 ? 'eval' : 'evals' }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Gráfica Circular / Dona: Estado Global de Evaluaciones (1 columna) -->
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-sm flex flex-col justify-between">
                <div class="pb-5 border-b border-slate-100">
                    <h2 class="text-base sm:text-lg font-bold text-slate-800 flex items-center gap-2.5">
                        <i class="fas fa-donut text-emerald-600"></i>
                        <span>Estado de Evaluaciones</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Distribución porcentual de calificaciones</p>
                </div>

                <div class="py-4 h-56 w-full relative flex items-center justify-center">
                    <canvas id="chartDonaResultados"></canvas>
                </div>

                <div class="space-y-2.5 pt-4 border-t border-slate-100 text-xs">
                    <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50">
                        <span class="flex items-center gap-2 text-slate-600 font-medium">
                            <span class="w-3 h-3 rounded-full bg-sena-green"></span>
                            <span>Aprobadas</span>
                        </span>
                        <strong class="text-slate-800 font-extrabold">{{ $evaluacionesAprobadas }}</strong>
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50">
                        <span class="flex items-center gap-2 text-slate-600 font-medium">
                            <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                            <span>Reprobadas</span>
                        </span>
                        <strong class="text-slate-800 font-extrabold">{{ $evaluacionesReprobadas }}</strong>
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50">
                        <span class="flex items-center gap-2 text-slate-600 font-medium">
                            <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                            <span>Anuladas / Fraude</span>
                        </span>
                        <strong class="text-slate-800 font-extrabold">{{ $evaluacionesAnuladas }}</strong>
                    </div>
                </div>
            </div>

        </div>

        <!-- 4. Tabla de Últimas Evaluaciones Presentadas -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-800 flex items-center gap-2.5">
                        <i class="fas fa-poll text-sena-green"></i>
                        <span>Registro de Evaluaciones en Tiempo Real</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Resultados, calificaciones y seguimiento directo de intentos</p>
                </div>
                <span class="text-xs font-bold text-slate-400 bg-slate-100 px-3 py-1 rounded-xl self-start sm:self-auto">
                    Total presentados: {{ $totalEvaluaciones }}
                </span>
            </div>

            @if($ultimosResultados->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider">
                                <th class="py-3.5 px-4">Aprendiz</th>
                                <th class="py-3.5 px-4">Quiz / Eje</th>
                                <th class="py-3.5 px-4 text-center">Intento</th>
                                <th class="py-3.5 px-4 text-center">Calificación</th>
                                <th class="py-3.5 px-4 text-center">Estado</th>
                                <th class="py-3.5 px-4 text-right">Fecha</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($ultimosResultados as $res)
                                <tr class="hover:bg-slate-50/90 transition-colors">
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 font-extrabold flex items-center justify-center text-xs shadow-sm">
                                                {{ strtoupper(substr($res->user_name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <span class="font-bold text-slate-800 block text-xs sm:text-sm">{{ $res->user_name }}</span>
                                                <span class="text-slate-400 text-[11px]">Doc: {{ $res->document_number ?? $res->nickname }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 font-semibold text-slate-700">
                                        {{ $res->quiz_titulo }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-bold text-slate-500">
                                        #{{ $res->intento }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <span class="text-sm font-black {{ $res->aprobado ? 'text-emerald-600' : 'text-rose-600' }}">
                                            {{ number_format($res->puntaje, 1) }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        @if($res->fue_anulado)
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[10px] font-black bg-rose-100 text-rose-700 border border-rose-200">
                                                <i class="fas fa-ban text-[9px]"></i> Anulado
                                            </span>
                                        @elseif($res->aprobado)
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                <i class="fas fa-check text-[9px]"></i> Aprobado
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[10px] font-black bg-rose-100 text-rose-700 border border-rose-200">
                                                <i class="fas fa-times text-[9px]"></i> Reprobado
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-right text-slate-500 text-[11px] font-medium">
                                        {{ \Carbon\Carbon::parse($res->created_at)->diffForHumans() }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <!-- Estado vacío ilustrado y elegante -->
                <div class="py-14 px-6 text-center rounded-2xl bg-gradient-to-b from-slate-50/70 to-slate-50/20 border border-dashed border-slate-200">
                    <div class="mb-3 text-sena-green">
                        <i class="fas fa-clipboard-list text-4xl"></i>
                    </div>
                    <h4 class="text-base font-bold text-slate-800">Aún no hay evaluaciones presentadas</h4>
                    <p class="text-xs text-slate-500 max-w-md mx-auto mt-1.5 leading-relaxed">
                        Cuando los aprendices inicien su inducción y completen los quices de SST, Calidad y Ambiental, verás sus notas, intentos y auditorías en tiempo real en este panel.
                    </p>
                </div>
            @endif
        </div>

        <!-- 6. Bitácora de Actividad Reciente en la Plataforma (Auditoría Admin) -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-5">
            <div class="pb-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-800 flex items-center gap-2.5">
                        <i class="fas fa-history text-sena-green"></i>
                        <span>Actividad Reciente del Módulo SISIG</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Seguimiento de interacciones, ingresos y actualizaciones en la plataforma</p>
                </div>
                <span class="text-[11px] font-bold text-slate-400 bg-slate-50 px-3 py-1 rounded-full border border-slate-200 self-start sm:self-auto">
                    Auditoría Administrativa
                </span>
            </div>

            @if(isset($actividadReciente) && $actividadReciente->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider">
                                <th class="py-3.5 px-4">Usuario</th>
                                <th class="py-3.5 px-4">Evento</th>
                                <th class="py-3.5 px-4">Detalle</th>
                                <th class="py-3.5 px-4 text-right">Fecha y Hora</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($actividadReciente as $act)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-3 px-4 font-bold text-slate-800">
                                        {{ $act->user_name ?? 'Usuario del Sistema' }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                            {{ ucfirst(str_replace('_', ' ', $act->tipo_evento)) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-slate-600">
                                        {{ $act->mensaje }}
                                    </td>
                                    <td class="py-3 px-4 text-right text-slate-400 text-[11px]">
                                        {{ \Carbon\Carbon::parse($act->created_at)->format('d/m/Y H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8 text-slate-400 text-xs">
                    <i class="fas fa-clipboard-list text-2xl mb-2 block text-slate-300"></i>
                    No hay eventos recientes registrados en la bitácora del módulo.
                </div>
            @endif
        </div>

    </div>

    <!-- Script de Inicialización de Gráficas Chart.js -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // 1. Gráfica de Barras: Rendimiento por Módulos Registrados
            const ctxEjes = document.getElementById('chartEjesSIG');
            if (ctxEjes) {
                @php
                    $paletaBarras = [
                        ['bg' => 'rgba(16, 185, 129, 0.85)', 'border' => 'rgb(16, 185, 129)'],
                        ['bg' => 'rgba(14, 165, 233, 0.85)', 'border' => 'rgb(14, 165, 233)'],
                        ['bg' => 'rgba(245, 158, 11, 0.85)', 'border' => 'rgb(245, 158, 11)'],
                        ['bg' => 'rgba(168, 85, 247, 0.85)', 'border' => 'rgb(168, 85, 247)'],
                        ['bg' => 'rgba(244, 63, 94, 0.85)', 'border' => 'rgb(244, 63, 94)'],
                        ['bg' => 'rgba(99, 102, 241, 0.85)', 'border' => 'rgb(99, 102, 241)'],
                        ['bg' => 'rgba(20, 184, 166, 0.85)', 'border' => 'rgb(20, 184, 166)'],
                    ];
                    $barColors = [];
                    $borderColors = [];
                    foreach ($modulosEstadisticas as $k => $m) {
                        $p = $paletaBarras[$k % count($paletaBarras)];
                        $barColors[] = $p['bg'];
                        $borderColors[] = $p['border'];
                    }
                @endphp

                const modulosLabels = {!! json_encode($modulosEstadisticas->map(fn($m) => ucfirst($m->titulo))->values()) !!};
                const modulosTasas = {!! json_encode($modulosEstadisticas->pluck('tasa_aprobacion')->values()) !!};
                const modulosTotales = {!! json_encode($modulosEstadisticas->pluck('total_evaluaciones')->values()) !!};
                const modulosAprobadas = {!! json_encode($modulosEstadisticas->pluck('evaluaciones_aprobadas')->values()) !!};
                const modulosReprobadas = {!! json_encode($modulosEstadisticas->pluck('evaluaciones_reprobadas')->values()) !!};
                const modulosQuices = {!! json_encode($modulosEstadisticas->pluck('total_quices')->values()) !!};
                const modulosColors = {!! json_encode($barColors) !!};
                const modulosBorderColors = {!! json_encode($borderColors) !!};

                if (modulosLabels && modulosLabels.length > 0) {
                    new Chart(ctxEjes, {
                        type: 'bar',
                        data: {
                            labels: modulosLabels,
                            datasets: [
                                {
                                    label: 'Tasa de Aprobación (%)',
                                    data: modulosTasas,
                                    backgroundColor: modulosColors,
                                    borderColor: modulosBorderColors,
                                    borderWidth: 1.5,
                                    borderRadius: 10,
                                    borderSkipped: false,
                                    maxBarThickness: 65,
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: '#0f172a',
                                    titleFont: { size: 13, weight: 'bold' },
                                    bodyFont: { size: 12 },
                                    padding: 12,
                                    cornerRadius: 8,
                                    callbacks: {
                                        label: function(context) {
                                            const i = context.dataIndex;
                                            const tasa = context.parsed.y || 0;
                                            const total = modulosTotales[i] || 0;
                                            const aprob = modulosAprobadas[i] || 0;
                                            const reprob = modulosReprobadas[i] || 0;
                                            const quices = modulosQuices[i] || 0;
                                            return [
                                                ` Tasa de Aprobación: ${tasa}%`,
                                                ` Evaluaciones: ${aprob} aprobadas, ${reprob} reprobadas (${total} total)`,
                                                ` Quices asociados: ${quices}`
                                            ];
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100,
                                    ticks: { callback: v => v + '%' },
                                    grid: { color: 'rgba(226, 232, 240, 0.6)' }
                                },
                                x: {
                                    grid: { display: false },
                                    ticks: {
                                        font: { weight: '600' }
                                    }
                                }
                            }
                        }
                    });
                }
            }

            // 2. Gráfica de Dona: Estado de Evaluaciones
            const ctxDona = document.getElementById('chartDonaResultados');
            if (ctxDona) {
                const aprobados = {{ $evaluacionesAprobadas }};
                const reprobados = {{ $evaluacionesReprobadas }};
                const anulados = {{ $evaluacionesAnuladas }};

                // Si no hay datos, mostrar valores didácticos iniciales
                const dataValues = (aprobados + reprobados + anulados) > 0 
                    ? [aprobados, reprobados, anulados] 
                    : [1, 0, 0];

                new Chart(ctxDona, {
                    type: 'doughnut',
                    data: {
                        labels: ['Aprobadas', 'Reprobadas', 'Anuladas'],
                        datasets: [{
                            data: dataValues,
                            backgroundColor: [
                                '#39A900', // Verde SENA
                                '#ef4444', // Rojo Rose
                                '#f59e0b'  // Ámbar
                            ],
                            borderWidth: 0,
                            hoverOffset: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '72%',
                        plugins: {
                            legend: { display: false }
                        }
                    }
                });
            }
        });
    </script>
</x-sisig::layouts.admin>
