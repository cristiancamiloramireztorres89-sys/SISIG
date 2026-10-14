<x-sisig::layouts.editor title="Seguimiento y Reportes">
    <div class="space-y-6 w-full max-w-[1600px] mx-auto pb-12">

        <!-- Banner Principal Editorial -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#001A29] via-[#002F48] to-[#004266] border border-slate-700/60 p-7 sm:p-9 text-white shadow-2xl">
            <div class="absolute -top-24 -right-24 w-80 h-80 bg-sena-green/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-sena-green/15 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="space-y-3">
                    <div>
                        <span class="text-sena-green text-xs font-black uppercase tracking-widest">
                            Panel Editorial
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-tight flex items-center gap-3">
                        <i class="fas fa-chart-line text-sena-green"></i>
                        <span>Seguimiento y Reportes</span>
                    </h1>

                    <p class="text-slate-300 text-xs sm:text-sm max-w-2xl font-normal leading-relaxed">
                        Visualiza en tiempo real el desempeño de los aprendices en los quizzes. Consulta aprobados, no aprobados y detalle de respuestas.
                    </p>

                    <nav class="flex items-center gap-2 text-xs text-slate-400 pt-1">
                        <a href="{{ route('sisig.editor.dashboard') }}" class="hover:text-white transition-colors">Inicio</a>
                        <span class="text-slate-500">›</span>
                        <span class="text-slate-300">Reportes</span>
                        <span class="text-slate-500">›</span>
                        <span class="text-sena-green font-semibold">Seguimiento y Reportes</span>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Tarjetas de métricas superiores (4 KPIs) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
            <!-- Total Evaluados -->
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center gap-4 transition-all hover:shadow-md">
                <div class="w-14 h-14 rounded-2xl bg-[#e8f7ee] text-[#198754] flex items-center justify-center text-xl shrink-0">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight leading-none">
                        {{ $metrics['total'] ?? 0 }}
                    </div>
                    <div class="text-xs sm:text-sm font-medium text-slate-400 mt-1">
                        Total evaluados
                    </div>
                </div>
            </div>

            <!-- Aprobaron -->
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center gap-4 transition-all hover:shadow-md">
                <div class="w-14 h-14 rounded-2xl bg-[#e8f7ee] text-[#198754] flex items-center justify-center text-xl shrink-0">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight leading-none">
                        {{ $metrics['aprobados'] ?? 0 }}
                    </div>
                    <div class="text-xs sm:text-sm font-medium text-slate-400 mt-1">
                        Aprobaron
                    </div>
                </div>
            </div>

            <!-- Pendientes -->
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center gap-4 transition-all hover:shadow-md">
                <div class="w-14 h-14 rounded-2xl bg-[#fef9e7] text-[#d97706] flex items-center justify-center text-xl shrink-0">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight leading-none">
                        {{ $metrics['pendientes'] ?? 0 }}
                    </div>
                    <div class="text-xs sm:text-sm font-medium text-slate-400 mt-1">
                        Pendientes
                    </div>
                </div>
            </div>

            <!-- No Aprobaron -->
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center gap-4 transition-all hover:shadow-md">
                <div class="w-14 h-14 rounded-2xl bg-[#fdeeed] text-[#dc3545] flex items-center justify-center text-xl shrink-0">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight leading-none">
                        {{ $metrics['no_aprobados'] ?? 0 }}
                    </div>
                    <div class="text-xs sm:text-sm font-medium text-slate-400 mt-1">
                        No aprobaron
                    </div>
                </div>
            </div>
        </div>

        <!-- Pestañas de Selección de Sección SIG -->
        <div class="space-y-2.5">
            <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-wider">
                <i class="fas fa-th-large text-sena-green"></i>
                <span>Resultados por sección SIG</span>
            </div>

            <div class="flex items-center gap-2.5 flex-wrap">
                <!-- Calidad -->
                <a href="{{ route('sisig.editor.reportes.index', ['seccion' => 'calidad', 'buscar' => request('buscar')]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $seccion === 'calidad' ? 'bg-[#e8f7ee] text-[#198754] border border-[#c3e6cb] shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                    <i class="fas fa-check-circle {{ $seccion === 'calidad' ? 'text-[#198754]' : 'text-slate-400' }}"></i>
                    <span>Calidad</span>
                </a>

                <!-- SST -->
                <a href="{{ route('sisig.editor.reportes.index', ['seccion' => 'sst', 'buscar' => request('buscar')]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $seccion === 'sst' ? 'bg-[#e8f7ee] text-[#198754] border border-[#c3e6cb] shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                    <i class="fas fa-hard-hat {{ $seccion === 'sst' ? 'text-[#198754]' : 'text-slate-400' }}"></i>
                    <span>SST</span>
                </a>

                <!-- Ambiental -->
                <a href="{{ route('sisig.editor.reportes.index', ['seccion' => 'ambiental', 'buscar' => request('buscar')]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $seccion === 'ambiental' ? 'bg-[#e8f7ee] text-[#198754] border border-[#c3e6cb] shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                    <i class="fas fa-tree {{ $seccion === 'ambiental' ? 'text-[#198754]' : 'text-slate-400' }}"></i>
                    <span>Ambiental</span>
                </a>
            </div>
        </div>

        <!-- TABLA 1: Aprobados — Quiz {{ ucfirst($seccion) }} -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 sm:p-7 space-y-5">
            <!-- Header de Aprobados -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#198754] inline-block shadow-xs"></span>
                    <h2 class="text-base font-bold text-slate-800">
                        Aprobados — Quiz {{ ucfirst($seccion) }}
                    </h2>
                </div>

                <button type="button" onclick="exportarTablaCSV('tabla-aprobados', 'Aprobados_Quiz_{{ ucfirst($seccion) }}')"
                        class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl border border-emerald-300 text-emerald-700 bg-white hover:bg-emerald-50 text-xs font-semibold shadow-xs transition-colors cursor-pointer self-start sm:self-auto">
                    <i class="fas fa-file-excel text-emerald-600"></i>
                    <span>Exportar</span>
                </button>
            </div>

            <!-- Buscador para Aprobados -->
            <form method="GET" action="{{ route('sisig.editor.reportes.index') }}" class="relative">
                <input type="hidden" name="seccion" value="{{ $seccion }}">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" name="buscar" value="{{ $buscar }}" placeholder="Buscar aprendiz..."
                       class="w-full pl-11 pr-4 py-2.5 bg-[#fcfdfd] border border-slate-200 rounded-xl text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sena-green/20 focus:border-sena-green transition-all">
            </form>

            <!-- Tabla de Aprobados -->
            <div class="overflow-x-auto">
                <table id="tabla-aprobados" class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="pb-3.5 pr-4">Aprendiz</th>
                            <th class="pb-3.5 px-4">Ficha</th>
                            <th class="pb-3.5 px-4">Puntaje</th>
                            <th class="pb-3.5 px-4 text-center">Intentos</th>
                            <th class="pb-3.5 px-4">Fecha</th>
                            <th class="pb-3.5 pl-4 text-center">Req 3: Respuestas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/80 text-sm">
                        @forelse($aprobadosList as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <!-- Aprendiz -->
                            <td class="py-4 pr-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#001A29] to-[#003B5C] text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-sm border border-slate-700/20">
                                        {{ $item->iniciales }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800 leading-snug group-hover:text-sena-green transition-colors">
                                            {{ $item->nombre }}
                                        </div>
                                        <div class="text-xs text-slate-400 mt-0.5">
                                            {{ $item->correo }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Ficha -->
                            <td class="py-4 px-4 text-xs text-slate-500 font-medium">
                                {{ $item->ficha }}
                            </td>

                            <!-- Puntaje -->
                            <td class="py-4 px-4 text-xs font-bold text-[#198754]">
                                {{ $item->puntaje }}/100
                            </td>

                            <!-- Intentos -->
                            <td class="py-4 px-4 text-center text-xs text-slate-700 font-medium">
                                {{ $item->intentos }}
                            </td>

                            <!-- Fecha -->
                            <td class="py-4 px-4 text-xs text-slate-500 font-normal">
                                {{ $item->fecha }}
                            </td>

                            <!-- REQ 3: Respuestas -->
                            <td class="py-4 pl-4 text-center">
                                <button type="button"
                                        onclick="abrirModalRespuestas({{ json_encode($item) }}, '{{ ucfirst($seccion) }}')"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-[#e8f7ee] text-[#198754] hover:bg-[#d1e7dd] transition-all cursor-pointer border border-[#c3e6cb] shadow-xs hover:shadow-sm">
                                    <i class="fas fa-bars-staggered text-xs"></i>
                                    <span>Ver respuestas</span>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-slate-400 text-xs">
                                <i class="fas fa-info-circle text-sena-green mr-1.5"></i>
                                No hay aprendices aprobados registrados para el Quiz de {{ ucfirst($seccion) }}.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TABLA 2: No aprobados — Quiz {{ ucfirst($seccion) }} (Ubicada exactamente debajo de Aprobados) -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 sm:p-7 space-y-5">
            <!-- Header de No Aprobados -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <i class="fas fa-times-circle text-[#dc3545] text-base"></i>
                    <h2 class="text-base font-bold text-slate-800">
                        No aprobados — Quiz {{ ucfirst($seccion) }}
                    </h2>
                </div>

                <button type="button" onclick="exportarTablaCSV('tabla-no-aprobados', 'No_Aprobados_Quiz_{{ ucfirst($seccion) }}')"
                        class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl border border-emerald-300 text-emerald-700 bg-white hover:bg-emerald-50 text-xs font-semibold shadow-xs transition-colors cursor-pointer self-start sm:self-auto">
                    <i class="fas fa-file-excel text-emerald-600"></i>
                    <span>Exportar</span>
                </button>
            </div>

            <!-- Tabla de No Aprobados -->
            <div class="overflow-x-auto">
                <table id="tabla-no-aprobados" class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="pb-3.5 pr-4">Aprendiz</th>
                            <th class="pb-3.5 px-4">Ficha</th>
                            <th class="pb-3.5 px-4">Puntaje</th>
                            <th class="pb-3.5 px-4 text-center">Intentos</th>
                            <th class="pb-3.5 px-4">Fecha</th>
                            <th class="pb-3.5 pl-4 text-center">Respuestas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/80 text-sm">
                        @forelse($noAprobadosList as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <!-- Aprendiz -->
                            <td class="py-4 pr-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#001A29] to-[#003B5C] text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-sm border border-slate-700/20">
                                        {{ $item->iniciales }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800 leading-snug group-hover:text-sena-green transition-colors">
                                            {{ $item->nombre }}
                                        </div>
                                        <div class="text-xs text-slate-400 mt-0.5">
                                            {{ $item->correo }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Ficha -->
                            <td class="py-4 px-4 text-xs text-slate-500 font-medium">
                                {{ $item->ficha }}
                            </td>

                            <!-- Puntaje (Rojo para no aprobados) -->
                            <td class="py-4 px-4 text-xs font-bold text-[#dc3545]">
                                {{ $item->puntaje }}/100
                            </td>

                            <!-- Intentos -->
                            <td class="py-4 px-4 text-center text-xs text-slate-700 font-medium">
                                {{ $item->intentos }}
                            </td>

                            <!-- Fecha -->
                            <td class="py-4 px-4 text-xs text-slate-500 font-normal">
                                {{ $item->fecha }}
                            </td>

                            <!-- Respuestas -->
                            <td class="py-4 pl-4 text-center">
                                <button type="button"
                                        onclick="abrirModalRespuestas({{ json_encode($item) }}, '{{ ucfirst($seccion) }}')"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-[#e8f7ee] text-[#198754] hover:bg-[#d1e7dd] transition-all cursor-pointer border border-[#c3e6cb] shadow-xs hover:shadow-sm">
                                    <i class="fas fa-bars-staggered text-xs"></i>
                                    <span>Ver respuestas</span>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-slate-400 text-xs">
                                <i class="fas fa-info-circle text-sena-green mr-1.5"></i>
                                No hay aprendices reprobados registrados para el Quiz de {{ ucfirst($seccion) }}.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Modal Detalle de Respuestas -->
    <div id="modal-respuestas-aprendiz" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full shadow-2xl border border-slate-100 overflow-hidden transform transition-all animate-fade-in">
            <!-- Modal Header -->
            <div class="relative overflow-hidden p-6 pb-4 border-b border-slate-700/50 flex items-center justify-between bg-gradient-to-r from-[#001A29] via-[#002F48] to-[#004266] text-white">
                <div class="relative z-10 flex items-center gap-3">
                    <div id="modal-avatar" class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 text-sena-green font-extrabold text-sm flex items-center justify-center shadow-inner">
                        --
                    </div>
                    <div>
                        <h3 id="modal-nombre" class="font-extrabold text-base leading-snug text-white">Nombre Aprendiz</h3>
                        <p id="modal-info-sub" class="text-xs text-slate-300 mt-0.5">Quiz --</p>
                    </div>
                </div>
                <button type="button" onclick="cerrarModalRespuestas()" class="relative z-10 w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-5 text-sm">
                <!-- Info del Intento -->
                <div class="grid grid-cols-2 gap-3 p-3.5 bg-slate-50 rounded-2xl border border-slate-100 text-xs">
                    <div>
                        <span class="text-slate-400 block font-medium">Puntaje Final</span>
                        <span id="modal-puntaje" class="text-base font-extrabold text-slate-800">--</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-medium">Resultado</span>
                        <span id="modal-estado-badge" class="font-bold text-xs inline-block mt-0.5">--</span>
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

                <!-- Detalle de Respuestas -->
                <div class="space-y-3">
                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Resumen de Evaluación</h4>
                    <div class="p-4 rounded-2xl bg-emerald-50/50 border border-emerald-100 text-xs text-emerald-800 leading-relaxed">
                        <i class="fas fa-check-double text-emerald-600 mr-1.5"></i>
                        Las respuestas del aprendiz fueron evaluadas automáticamente por la plataforma SISIG bajo el umbral mínimo del 70%.
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="p-4 bg-slate-50 border-t border-slate-100 flex justify-end">
                <button type="button" onclick="cerrarModalRespuestas()" class="px-5 py-2 rounded-xl text-xs font-bold bg-slate-200 text-slate-700 hover:bg-slate-300 transition-colors cursor-pointer">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <!-- Scripts de Interacción y Exportación CSV -->
    <script>
        function abrirModalRespuestas(item, seccion) {
            document.getElementById('modal-avatar').textContent = item.iniciales || 'AP';
            document.getElementById('modal-nombre').textContent = item.nombre || 'Sin nombre';
            document.getElementById('modal-info-sub').textContent = 'Quiz ' + seccion + ' · Ficha ' + (item.ficha || 'N/A');
            document.getElementById('modal-puntaje').textContent = item.puntaje + '/100';
            document.getElementById('modal-intentos').textContent = item.intentos || 1;
            document.getElementById('modal-fecha').textContent = item.fecha || 'N/A';

            const badge = document.getElementById('modal-estado-badge');
            if (item.aprobado) {
                badge.textContent = '● Aprobado';
                badge.className = 'text-xs font-bold px-3 py-1 rounded-full bg-[#e8f7ee] text-[#198754] inline-block';
            } else {
                badge.textContent = '● No aprobado';
                badge.className = 'text-xs font-bold px-3 py-1 rounded-full bg-[#fdeeed] text-[#dc3545] inline-block';
            }

            document.getElementById('modal-respuestas-aprendiz').classList.remove('hidden');
        }

        function cerrarModalRespuestas() {
            document.getElementById('modal-respuestas-aprendiz').classList.add('hidden');
        }

        document.getElementById('modal-respuestas-aprendiz')?.addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarModalRespuestas();
            }
        });

        // Función para exportar tabla HTML a CSV compatible con Microsoft Excel
        function exportarTablaCSV(tablaId, nombreArchivo) {
            const tabla = document.getElementById(tablaId);
            if (!tabla) return;

            let csv = [];
            const filas = tabla.querySelectorAll('tr');

            filas.forEach(fila => {
                const columnas = fila.querySelectorAll('th, td');
                let filaDatos = [];

                columnas.forEach((columna, index) => {
                    // Omitir la última columna de acciones
                    if (index === columnas.length - 1) return;

                    let texto = columna.innerText.replace(/(\r\n|\n|\r)/gm, ' ').trim();
                    texto = texto.replace(/"/g, '""');
                    filaDatos.push('"' + texto + '"');
                });

                if (filaDatos.length > 0) {
                    csv.push(filaDatos.join(';'));
                }
            });

            const csvString = '\uFEFF' + csv.join('\r\n');
            const blob = new Blob([csvString], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);

            link.setAttribute('href', url);
            link.setAttribute('download', nombreArchivo + '.csv');
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
</x-sisig::layouts.editor>