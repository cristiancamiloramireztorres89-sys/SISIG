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
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            
            <!-- Total Evaluaciones -->
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Total Creados</span>
                    <i class="fas fa-clipboard-list text-2xl text-blue-600 group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight">{{ $totalEvaluaciones }}</h3>
                    <div class="mt-2.5 flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Evaluaciones en el sistema</span>
                    </div>
                </div>
            </div>

            <!-- Aprobadas -->
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-sena-green to-[#237000]"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Aprobadas</span>
                    <i class="fas fa-check-circle text-2xl text-sena-green group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight">{{ $totalAprobadas }}</h3>
                    <div class="mt-2.5 flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Evaluaciones superadas</span>
                    </div>
                </div>
            </div>

            <!-- Total Intentos Presentados -->
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-amber-400 to-orange-500"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Total Intentos</span>
                    <i class="fas fa-history text-2xl text-amber-500 group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight">{{ $totalIntentosRealizados }}</h3>
                    <div class="mt-2.5 flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Pruebas presentadas</span>
                    </div>
                </div>
            </div>

            <!-- Promedio General -->
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-teal-500 to-emerald-600"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Promedio General</span>
                    <i class="fas fa-chart-line text-2xl text-teal-600 group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight">{{ number_format($promedioGeneral, 1) }}%</h3>
                    <div class="mt-2.5 flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Rendimiento académico</span>
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
                <select id="filtroModuloSelect" class="bg-slate-50 border border-slate-200 text-slate-700 font-medium text-sm rounded-2xl px-4 py-3 focus:ring-2 focus:ring-sena-green/50 outline-none w-full sm:w-auto cursor-pointer transition-all hover:bg-slate-100">
                    <option value="">Todas las secciones</option>
                    @foreach($consolidadoModulos->pluck('modulo_titulo')->unique()->filter() as $modTitulo)
                        <option value="{{ strtolower($modTitulo) }}">{{ $modTitulo }}</option>
                    @endforeach
                </select>

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
                        <div class="card-modulo-historial bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative group flex flex-col h-full"
                             data-titulo="{{ strtolower($mod->quiz_titulo) }}"
                             data-modulo="{{ strtolower($mod->modulo_titulo) }}"
                             data-estado="{{ $estadoModulo }}">

                            <!-- Encabezado de la Tarjeta -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-sena-green flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-graduation-cap text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-extrabold text-slate-800 text-base leading-tight">{{ $mod->quiz_titulo }}</h3>
                                        <div class="flex items-center gap-1.5 mt-1 text-slate-500 text-xs font-medium">
                                            <i class="fas fa-layer-group text-slate-400"></i>
                                            <span>{{ $mod->modulo_titulo }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Distintivo de Estado Limpio (Criterio 2) -->
                            <div class="absolute top-6 right-6">
                                @if($mod->aprobado)
                                    <span class="text-xs font-black tracking-wide uppercase text-emerald-600 flex items-center gap-1">
                                        <i class="fas fa-check-circle"></i> Aprobado
                                    </span>
                                @elseif($mod->total_intentos > 0)
                                    <span class="text-xs font-black tracking-wide uppercase text-rose-600 flex items-center gap-1">
                                        <i class="fas fa-times-circle"></i> No Aprobado
                                    </span>
                                @else
                                    <span class="text-xs font-black tracking-wide uppercase text-slate-400 flex items-center gap-1">
                                        <i class="fas fa-clock"></i> Pendiente
                                    </span>
                                @endif
                            </div>

                            <!-- Métricas de Desempeño del Módulo -->
                            <div class="grid grid-cols-2 gap-3 my-4 p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                                <div>
                                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Mejor Nota</span>
                                    <span class="text-base font-black {{ $mod->aprobado ? 'text-sena-green' : 'text-slate-800' }} mt-0.5 block">
                                        {{ number_format($mod->mejor_puntaje, 1) }}%
                                    </span>
                                </div>
                                <div class="border-l border-slate-200">
                                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Nota Mínima</span>
                                    <span class="text-base font-black text-slate-700 mt-0.5 block">
                                        {{ $mod->puntaje_minimo }}%
                                    </span>
                                </div>
                            </div>

                            <!-- Control de Intentos Restantes Disponibles (Criterio 4) -->
                            <div class="mb-5 p-3.5 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
                                <div class="flex justify-between text-xs">
                                    <span class="text-slate-600 font-bold">Intentos utilizados</span>
                                    <span class="font-black text-slate-800">{{ $mod->total_intentos }} / {{ $mod->intentos_permitidos }}</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden shadow-inner">
                                    @php
                                        $porcentajeBarra = $mod->intentos_permitidos > 0 ? min(100, ($mod->total_intentos / $mod->intentos_permitidos) * 100) : 0;
                                    @endphp
                                    <div class="h-1.5 rounded-full transition-all {{ $mod->aprobado ? 'bg-sena-green' : ($mod->intentos_restantes <= 0 ? 'bg-rose-500' : 'bg-amber-500') }}" style="width: {{ $porcentajeBarra }}%"></div>
                                </div>
                                <div class="flex justify-between text-[11px] pt-1 font-semibold">
                                    <span class="text-slate-500">Intentos disponibles:</span>
                                    <span class="{{ $mod->intentos_restantes > 0 ? 'text-sena-green font-bold' : 'text-rose-600 font-bold' }}">
                                        {{ $mod->intentos_restantes }} restantes
                                    </span>
                                </div>
                            </div>

                            <!-- Botón de Acción -->
                            <div class="pt-4 border-t border-slate-100 mt-auto">
                                @if($mod->aprobado)
                                    <div class="text-center py-2 text-xs font-bold text-emerald-700 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center justify-center gap-2">
                                        <i class="fas fa-check-double text-sena-green"></i> Módulo Acreditado
                                    </div>
                                @elseif($mod->puede_reintentar)
                                    <a href="{{ route('sisig.aprendiz.evaluaciones.iniciar', $mod->id_quiz) }}" 
                                       class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-2xl text-xs font-bold text-white bg-sena-green hover:bg-emerald-600 shadow-sena hover:shadow-lg transition-all w-full">
                                        <i class="fas fa-play text-xs"></i>
                                        <span>Presentar Intento (#{{ $mod->total_intentos + 1 }})</span>
                                    </a>
                                @else
                                    <div class="text-center py-2 text-xs font-bold text-slate-400 bg-slate-100 rounded-2xl border border-slate-200">
                                        <i class="fas fa-ban"></i> Sin Oportunidades Restantes
                                    </div>
                                @endif
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
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('filtroHistorialInput');
            const moduloSelect = document.getElementById('filtroModuloSelect');
            const estadoSelect = document.getElementById('filtroEstadoSelect');
            const cards = document.querySelectorAll('.card-modulo-historial');

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
