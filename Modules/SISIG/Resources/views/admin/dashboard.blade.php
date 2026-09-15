<x-sisig::layouts.admin title="Dashboard Administrador">
    <div class="space-y-8 w-full max-w-[1600px] mx-auto pb-10">

        <!-- 1. Banner Principal Ejecutivo de Bienvenida -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#001A29] via-[#002F48] to-[#004266] border border-slate-700/60 p-7 sm:p-9 text-white shadow-2xl">
            <!-- Destellos y brillos ambientales de fondo -->
            <div class="absolute -top-24 -right-24 w-80 h-80 bg-sena-green/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-sky-500/15 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <!-- Columna Izquierda: Información de bienvenida -->
                <div class="space-y-3">
                    <div>
                        <span class="text-sena-green text-xs font-black uppercase tracking-widest">
                            Panel de Supervisión Estratégica &bull; SISIG
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-tight">
                        ¡Hola, <span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-slate-100 to-emerald-300">{{ auth()->user()->full_name ?? auth()->user()->name }}</span>!
                    </h1>

                    <p class="text-slate-300 text-xs sm:text-sm max-w-2xl font-normal leading-relaxed">
                        Control general de las inducciones formativas y evaluaciones en <strong class="text-white">Seguridad y Salud (SST)</strong>, <strong class="text-white">Gestión de la Calidad</strong> y <strong class="text-white">Gestión Ambiental</strong> para aprendices del Centro Agroindustrial "La Angostura".
                    </p>
                </div>

                <!-- Columna Derecha: Fecha actual limpia sin cuadros -->
                <div class="flex items-center gap-2.5 self-start lg:self-center">
                    <i class="far fa-calendar-check text-sena-green text-xl"></i>
                    <span class="text-xs sm:text-sm font-bold text-slate-100 capitalize">
                        {{ now()->isoFormat('dddd, D [de] MMMM') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- 2. Tarjetas de Métricas Clave (KPIs) con Estilo Premium -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            
            <!-- KPI 1: Aprendices Registrados -->
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Aprendices SENA</span>
                    <i class="fas fa-user-graduate text-2xl text-blue-600 group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight">{{ number_format($totalAprendices) }}</h3>
                    <div class="mt-3 flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Asignados a formación</span>
                        <span class="text-xs font-extrabold text-blue-600">Etapa Práctica</span>
                    </div>
                    <!-- Barra de progreso decorativa -->
                    <div class="w-full bg-slate-100 rounded-full h-1.5 mt-2.5 overflow-hidden">
                        <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $totalAprendices > 0 ? '100%' : '15%' }}"></div>
                    </div>
                </div>
            </div>

            <!-- KPI 2: Evaluaciones Activas -->
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-sena-green to-[#237000]"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Quices Activos</span>
                    <i class="fas fa-clipboard-check text-2xl text-sena-green group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="mt-4">
                    <div class="flex items-baseline gap-1.5">
                        <h3 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight">{{ $quicesActivos }}</h3>
                        <span class="text-sm font-bold text-slate-400">/ {{ $totalQuices }} creados</span>
                    </div>
                    <div class="mt-3 flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Habilitados para presentar</span>
                        <span class="text-xs font-black text-sena-green">Disponibles</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 mt-2.5 overflow-hidden">
                        <div class="bg-sena-green h-1.5 rounded-full" style="width: {{ $totalQuices > 0 ? round(($quicesActivos / max($totalQuices, 1)) * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>

            <!-- KPI 3: Tasa de Aprobación -->
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-teal-400 to-emerald-600"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Tasa de Aprobación</span>
                    <i class="fas fa-chart-pie text-2xl text-teal-600 group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight">{{ $tasaAprobacion }}%</h3>
                    <div class="mt-3 flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">{{ $evaluacionesAprobadas }} aprobados</span>
                        <span class="text-rose-500 font-bold">{{ $evaluacionesReprobadas }} reprobados</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 mt-2.5 overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-500 to-teal-500 h-1.5 rounded-full" style="width: {{ $tasaAprobacion }}%"></div>
                    </div>
                </div>
            </div>

            <!-- KPI 4: Monitoreo Anti-Fraude e Infracciones -->
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-amber-400 to-rose-600"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Control Anti-Fraude</span>
                    <i class="fas fa-shield-virus text-2xl text-amber-600 group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight">{{ $totalInfracciones }}</h3>
                    <div class="mt-3 flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Alertas registradas</span>
                        <span class="text-xs font-bold {{ $evaluacionesAnuladas > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                            {{ $evaluacionesAnuladas }} Anuladas
                        </span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 mt-2.5 overflow-hidden">
                        <div class="bg-amber-500 h-1.5 rounded-full" style="width: {{ min($totalInfracciones * 10, 100) }}%"></div>
                    </div>
                </div>
            </div>

        </div>

        <!-- 3. Sección de Visualización Gráfica y Rendimiento del SIG -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Gráfica de Barras: Rendimiento por Eje SIG (2 columnas) -->
            <div class="lg:col-span-2 bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-sm flex flex-col justify-between">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-800 flex items-center gap-2.5">
                            <i class="fas fa-chart-bar text-sena-green"></i>
                            <span>Rendimiento y Evaluaciones por Eje SIG</span>
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">Comparativa de participación y aprobación en cada área temática</p>
                    </div>
                </div>

                <div class="py-6 h-64 sm:h-72 w-full relative">
                    <canvas id="chartEjesSIG"></canvas>
                </div>

                <div class="grid grid-cols-3 gap-3 pt-4 border-t border-slate-100 text-center text-xs">
                    <div class="p-2 rounded-xl bg-emerald-50/70 border border-emerald-100">
                        <span class="text-slate-500 block text-[11px]">SST</span>
                        <strong class="text-emerald-700 font-extrabold text-sm sm:text-base">Seguridad</strong>
                    </div>
                    <div class="p-2 rounded-xl bg-sky-50/70 border border-sky-100">
                        <span class="text-slate-500 block text-[11px]">Calidad</span>
                        <strong class="text-sky-700 font-extrabold text-sm sm:text-base">Procesos</strong>
                    </div>
                    <div class="p-2 rounded-xl bg-amber-50/70 border border-amber-100">
                        <span class="text-slate-500 block text-[11px]">Ambiental</span>
                        <strong class="text-amber-700 font-extrabold text-sm sm:text-base">Sostenible</strong>
                    </div>
                </div>
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
                    <div class="w-16 h-16 mx-auto rounded-3xl bg-emerald-50 border border-emerald-200/60 text-sena-green flex items-center justify-center text-2xl mb-4 shadow-sm">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <h4 class="text-base font-bold text-slate-800">Aún no hay evaluaciones presentadas</h4>
                    <p class="text-xs text-slate-500 max-w-md mx-auto mt-1.5 leading-relaxed">
                        Cuando los aprendices inicien su inducción y completen los quices de SST, Calidad y Ambiental, verás sus notas, intentos y auditorías en tiempo real en este panel.
                    </p>
                </div>
            @endif
        </div>

    </div>

    <!-- Script de Inicialización de Gráficas Chart.js -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // 1. Gráfica de Barras: Rendimiento por Eje
            const ctxEjes = document.getElementById('chartEjesSIG');
            if (ctxEjes) {
                new Chart(ctxEjes, {
                    type: 'bar',
                    data: {
                        labels: ['SST (Seguridad)', 'Gestión de Calidad', 'Gestión Ambiental'],
                        datasets: [
                            {
                                label: 'Tasa de Aprobación Esperada (%)',
                                data: [85, 90, 80],
                                backgroundColor: ['rgba(57, 169, 0, 0.85)', 'rgba(14, 165, 233, 0.85)', 'rgba(245, 158, 11, 0.85)'],
                                borderRadius: 10,
                                borderSkipped: false,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                ticks: { callback: v => v + '%' },
                                grid: { color: 'rgba(226, 232, 240, 0.6)' }
                            },
                            x: {
                                grid: { display: false }
                            }
                        }
                    }
                });
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
