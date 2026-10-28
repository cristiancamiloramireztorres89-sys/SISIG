<x-sisig::layouts.editor title="Auditoría de Infracciones y Anti-Trampa">
    <div class="space-y-6 sm:space-y-8 animate-fade-in pb-12">

        <!-- 1. Encabezado de la Sección -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-5 border-b border-slate-200">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                    <i class="fas fa-shield-alt text-sena-green"></i>
                    <span>Auditoría de Infracciones y Anti-Trampa</span>
                </h1>
                <p class="text-xs text-slate-500 mt-1 font-medium">
                    Registro de evaluaciones anuladas por trampas e infracciones de seguridad cometidas durante la presentación de quices
                </p>
            </div>
        </div>

        <!-- 2. Tarjetas de Métricas (KPIs con estilo editorial unificado) -->
        @php
            $kpis = [
                [
                    'title' => 'Quices Anulados',
                    'value' => $metrics['anulados'] ?? 0,
                    'sub'   => 'por infracciones',
                    'icon'  => 'fa-ban text-rose-500',
                    'bar'   => 'from-rose-500 to-rose-700',
                ],
                [
                    'title' => 'Total Infracciones',
                    'value' => $metrics['total_infracciones'] ?? 0,
                    'sub'   => 'alertas detectadas',
                    'icon'  => 'fa-exclamation-triangle text-amber-500',
                    'bar'   => 'from-amber-400 to-amber-600',
                ],
                [
                    'title' => 'Faltas Pantalla Completa',
                    'value' => $metrics['pantalla'] ?? 0,
                    'sub'   => 'salidas forzadas',
                    'icon'  => 'fa-compress-arrows-alt text-indigo-500',
                    'bar'   => 'from-indigo-500 to-indigo-700',
                ],
                [
                    'title' => 'Faltas Pestaña / Ventana',
                    'value' => $metrics['pestana'] ?? 0,
                    'sub'   => 'pérdidas de foco',
                    'icon'  => 'fa-window-restore text-sky-500',
                    'bar'   => 'from-sky-400 to-sky-600',
                ],
            ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($kpis as $kpi)
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500">{{ $kpi['title'] }}</span>
                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                            <i class="fas {{ $kpi['icon'] }}"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="flex items-baseline gap-1.5">
                            <h3 class="text-2xl sm:text-3xl font-extrabold text-[#343A40] tracking-tight">{{ $kpi['value'] }}</h3>
                            <span class="text-xs font-bold text-slate-400">{{ $kpi['sub'] }}</span>
                        </div>
                        <div class="mt-1 flex items-center justify-between text-xs text-slate-400 font-medium">
                            <span>Seguridad SISIG</span>
                            <span class="text-xs font-bold text-[#2E6F40]">Anti-Trampa</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- 3. Barra de Filtros y Acciones (Estilo Unificado Admin) -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200 flex flex-col md:flex-row gap-4 items-center justify-between relative z-20">
            <form action="{{ route('sisig.editor.infracciones.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 w-full">
                <div class="relative flex-1 max-w-md">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="buscar" id="infracciones-search-input" value="{{ $buscar ?? '' }}" placeholder="Buscar por aprendiz, documento, ficha o quiz..." 
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all">
                </div>
                @php
                    $modulosInfracciones = \Illuminate\Support\Facades\DB::table('sisig_modulos')
                        ->where('estado', '!=', 'inactivo')
                        ->orderBy('id', 'asc')
                        ->get();
                @endphp
                <select name="seccion" id="infracciones-seccion-select" class="px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-sm text-slate-600 focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all cursor-pointer font-medium min-w-[180px]" onchange="this.form.submit()">
                    <option value="todas" {{ $seccion === 'todas' ? 'selected' : '' }}>Todos los módulos</option>
                    @foreach($modulosInfracciones as $modInf)
                        @php
                            $valSec = strtolower($modInf->titulo);
                            if (str_contains($valSec, 'calidad')) $valSec = 'calidad';
                            elseif (str_contains($valSec, 'sst') || str_contains($valSec, 'seguridad')) $valSec = 'sst';
                            elseif (str_contains($valSec, 'ambiental') || str_contains($valSec, 'ambiente')) $valSec = 'ambiental';
                        @endphp
                        <option value="{{ $valSec }}" {{ $seccion === $valSec ? 'selected' : '' }}>
                            {{ $modInf->titulo }} {{ !empty($modInf->norma) ? "({$modInf->norma})" : '' }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <!-- 4. TABLA: Listado de Quices Anulados e Infracciones Registradas -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-800 flex items-center gap-2.5">
                        <i class="fas fa-ban text-rose-600"></i>
                        <span>Aprendices con Quices Anulados por Trampa</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Listado detallado de intentos invalidados automáticamente por el sistema de seguridad anti-trampa</p>
                </div>
                
                <div class="flex items-center gap-2.5 self-start sm:self-auto">
                    <span id="counter-infracciones" class="text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1.5 rounded-xl">
                        Registros: <strong class="text-slate-800">{{ $infraccionesList->count() }}</strong>
                    </span>
                    <button type="button" onclick="exportarTablaExcel('tabla-infracciones', 'Auditoria_Infracciones_SISIG_{{ ucfirst($seccion) }}')"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-sena-green hover:bg-emerald-600 shadow-sena hover:shadow-lg transition-all cursor-pointer">
                        <i class="fas fa-file-excel text-xs"></i>
                        <span>Exportar Excel</span>
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table id="tabla-infracciones" class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider">
                            <th class="py-3.5 px-4 w-12 text-center">#</th>
                            <th class="py-3.5 px-4">Aprendiz</th>
                            <th class="py-3.5 px-4">Documento / Ficha</th>
                            <th class="py-3.5 px-4">Quiz / Módulo</th>
                            <th class="py-3.5 px-4 text-center">Infracciones</th>
                            <th class="py-3.5 px-4">Motivo de Anulación</th>
                            <th class="py-3.5 px-4">Fecha y Hora</th>
                            <th class="py-3.5 px-4 text-right">Detalle</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-infracciones" class="divide-y divide-slate-100">
                        @forelse($infraccionesList as $index => $item)
                            <tr class="infraccion-row hover:bg-slate-50/80 transition-colors"
                                data-name="{{ strtolower($item->aprendiz) }}"
                                data-email="{{ strtolower($item->correo) }}"
                                data-document="{{ strtolower(str_replace('.', '', $item->documento)) }}"
                                data-ficha="{{ strtolower($item->ficha) }}"
                                data-quiz="{{ strtolower($item->quiz) }}">
                                
                                <!-- # Contador -->
                                <td class="py-3.5 px-4 text-center font-bold text-slate-400">
                                    {{ $index + 1 }}
                                </td>

                                <!-- Aprendiz con Avatar -->
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl overflow-hidden bg-slate-100 flex items-center justify-center flex-shrink-0 shadow-sm border border-slate-200/80">
                                            @if(!empty($item->avatar_url))
                                                <img src="{{ $item->avatar_url }}" alt="{{ $item->aprendiz }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-slate-200 text-slate-400 flex items-center justify-center text-xs">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-800 block text-xs sm:text-sm">{{ $item->aprendiz }}</span>
                                            <span class="text-[11px] text-slate-400 font-medium">{{ $item->correo }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Documento y Ficha -->
                                <td class="py-3.5 px-4">
                                    <span class="font-mono text-xs font-bold text-slate-700 block">{{ $item->documento }}</span>
                                    <span class="font-mono text-[11px] font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200/80 inline-block mt-0.5">
                                        Ficha: {{ $item->ficha }}
                                    </span>
                                </td>

                                <!-- Quiz y Módulo -->
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-slate-800 block text-xs">{{ $item->quiz }}</span>
                                    <span class="text-[10px] text-slate-400 font-medium">{{ $item->modulo }} · {{ $item->norma }}</span>
                                </td>

                                <!-- Conteo de Infracciones -->
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-black bg-rose-100 text-rose-700 border border-rose-200">
                                        <i class="fas fa-exclamation-triangle text-[11px] text-rose-600"></i>
                                        <span>{{ $item->infracciones_count }} {{ $item->infracciones_count == 1 ? 'falta' : 'faltas' }}</span>
                                    </span>
                                </td>

                                <!-- Motivo de Anulación -->
                                <td class="py-3.5 px-4 max-w-xs">
                                    <div class="flex items-start gap-1.5">
                                        <i class="fas fa-ban text-rose-500 mt-0.5 flex-shrink-0 text-xs"></i>
                                        <span class="font-medium text-slate-700 text-xs leading-snug line-clamp-2" title="{{ $item->motivo_anulacion }}">
                                            {{ $item->motivo_anulacion }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Fecha y Hora -->
                                <td class="py-3.5 px-4 text-slate-500 font-medium text-xs whitespace-nowrap">
                                    {{ $item->fecha }}
                                </td>

                                <!-- Botón Ver Detalle -->
                                <td class="py-3.5 px-4 text-right">
                                    <button type="button"
                                            onclick='abrirModalInfraccion(@json($item))'
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-slate-600 hover:text-rose-700 hover:border-rose-200 hover:bg-rose-50 transition-all text-xs font-bold shadow-xs cursor-pointer"
                                            title="Ver bitácora de auditoría">
                                        <i class="fas fa-eye text-xs"></i>
                                        <span>Auditoría</span>
                                    </button>
                                </td>

                            </tr>
                        @empty
                            <tr class="empty-row-infracciones">
                                <td colspan="8" class="text-center py-12 text-slate-400">
                                    <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl">
                                        <i class="fas fa-shield-check"></i>
                                    </div>
                                    <span class="font-bold text-slate-700 text-sm block">Sin infracciones registradas</span>
                                    <span class="text-xs text-slate-400 mt-1 block">No se encontraron evaluaciones anuladas por trampas en esta sección.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="no-results-infracciones" class="hidden text-center py-8 text-slate-400 text-xs">
                <i class="fas fa-search text-xl mb-1.5 block text-slate-300"></i>
                No se encontraron registros de infracciones que coincidan con la búsqueda.
            </div>
        </div>

    </div>

    <!-- MODAL DETALLE DE AUDITORÍA Y BITÁCORA DE INFRACCIONES -->
    <div id="modal-infraccion-detalle" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-7 shadow-2xl border border-slate-200 animate-scale-in relative">
            
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div id="modal-inf-avatar" class="w-11 h-11 rounded-2xl overflow-hidden bg-slate-100 flex items-center justify-center flex-shrink-0 shadow-sm border border-slate-200/80">
                        <i class="fas fa-user text-slate-400 text-sm"></i>
                    </div>
                    <div>
                        <h3 id="modal-inf-nombre" class="text-base font-bold text-slate-800">Nombre Aprendiz</h3>
                        <p id="modal-inf-sub" class="text-xs text-slate-500 mt-0.5">Quiz --</p>
                    </div>
                </div>
                <button type="button" onclick="cerrarModalInfraccion()" class="text-slate-400 hover:text-slate-600 p-2 rounded-xl hover:bg-slate-100 transition-colors cursor-pointer">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <div class="mt-5 space-y-4 text-xs">
                
                <!-- Alerta Banner de Anulación -->
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200/80 text-rose-900 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fas fa-ban"></i>
                    </div>
                    <div>
                        <span class="font-extrabold text-rose-800 text-xs block">Evaluación Anulada por Trampa</span>
                        <p id="modal-inf-motivo" class="text-xs text-rose-700 mt-0.5 font-medium leading-relaxed">
                            --
                        </p>
                    </div>
                </div>

                <!-- Resumen de Datos del Intento -->
                <div class="grid grid-cols-2 gap-3 p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80">
                    <div>
                        <span class="text-slate-400 block font-medium">Documento de Identidad</span>
                        <span id="modal-inf-doc" class="text-slate-800 font-bold font-mono">--</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-medium">Ficha de Formación</span>
                        <span id="modal-inf-ficha" class="text-slate-800 font-bold font-mono">--</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-medium">Faltas Detectadas</span>
                        <span id="modal-inf-faltas" class="text-rose-600 font-extrabold">--</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-medium">Fecha y Hora</span>
                        <span id="modal-inf-fecha" class="text-slate-700 font-bold">--</span>
                    </div>
                </div>

                <!-- Bitácora Segundo a Segundo de Faltas -->
                <div>
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider block mb-2.5">
                        Bitácora Cronológica de Incidentes
                    </span>
                    <div id="modal-inf-eventos" class="max-h-56 overflow-y-auto space-y-2 pr-1 divide-y divide-slate-100">
                        <!-- Eventos inyectados por JavaScript -->
                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end mt-5">
                <button type="button" onclick="cerrarModalInfraccion()" class="px-5 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-colors cursor-pointer">
                    Cerrar
                </button>
            </div>

        </div>
    </div>

    <!-- SCRIPTS DE INTERACCIÓN REACTIVA Y EXPORTACIÓN CSV -->
    <script>
        const searchInput = document.getElementById('infracciones-search-input');
        const rowsInfracciones = document.querySelectorAll('.infraccion-row');
        const counterInfracciones = document.getElementById('counter-infracciones');
        const noResultsInfracciones = document.getElementById('no-results-infracciones');

        function applySearch() {
            const query = (searchInput?.value || '').toLowerCase().trim();
            let count = 0;

            rowsInfracciones.forEach(row => {
                const name = row.dataset.name || '';
                const email = row.dataset.email || '';
                const doc = row.dataset.document || '';
                const ficha = row.dataset.ficha || '';
                const quiz = row.dataset.quiz || '';

                const matches = !query || name.includes(query) || email.includes(query) || doc.includes(query) || ficha.includes(query) || quiz.includes(query);
                row.style.display = matches ? '' : 'none';
                if (matches) count++;
            });

            if (counterInfracciones) {
                counterInfracciones.innerHTML = `Registros: <strong class="text-slate-800">${count}</strong>`;
            }
            if (noResultsInfracciones) {
                noResultsInfracciones.classList.toggle('hidden', count > 0 || rowsInfracciones.length === 0);
            }
        }

        searchInput?.addEventListener('input', applySearch);

        // Control del Modal de Auditoría
        function abrirModalInfraccion(item) {
            const avatarContainer = document.getElementById('modal-inf-avatar');
            if (item.avatar_url) {
                avatarContainer.innerHTML = `<img src="${item.avatar_url}" alt="${item.aprendiz || ''}" class="w-full h-full object-cover">`;
            } else {
                avatarContainer.innerHTML = `<div class="w-full h-full bg-slate-200 text-slate-400 flex items-center justify-center text-sm"><i class="fas fa-user"></i></div>`;
            }
            document.getElementById('modal-inf-nombre').textContent = item.aprendiz || 'Sin nombre';
            document.getElementById('modal-inf-sub').textContent = item.quiz + ' · ' + (item.modulo || 'Módulo SIG');
            document.getElementById('modal-inf-motivo').textContent = item.motivo_anulacion || 'Infracciones anti-trampa detectadas.';
            document.getElementById('modal-inf-doc').textContent = item.documento || 'N/A';
            document.getElementById('modal-inf-ficha').textContent = item.ficha || 'Sin asignar';
            document.getElementById('modal-inf-faltas').textContent = (item.infracciones_count || 0) + ' infracciones';
            document.getElementById('modal-inf-fecha').textContent = item.fecha || 'N/A';

            const container = document.getElementById('modal-inf-eventos');
            if (item.infracciones_detalle && item.infracciones_detalle.length > 0) {
                let html = '';
                item.infracciones_detalle.forEach((det, idx) => {
                    html += `
                        <div class="pt-2 pb-1.5 flex items-start gap-2.5">
                            <span class="w-6 h-6 rounded-lg bg-rose-100 text-rose-700 font-bold text-[11px] flex items-center justify-center flex-shrink-0 mt-0.5">
                                ${idx + 1}
                            </span>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-slate-800 text-xs">${det.evento}</span>
                                    <span class="text-[10px] text-slate-400 font-mono">${det.hora}</span>
                                </div>
                                <p class="text-[11px] text-slate-500 mt-0.5">${det.detalle}</p>
                            </div>
                        </div>
                    `;
                });
                container.innerHTML = html;
            } else {
                container.innerHTML = `
                    <div class="py-4 text-center text-slate-400 text-xs">
                        <i class="fas fa-info-circle mb-1 block"></i>
                        El examen fue anulado directamente por violar los parámetros de seguridad.
                    </div>
                `;
            }

            document.getElementById('modal-infraccion-detalle').classList.remove('hidden');
            if (window.lockBodyScroll) window.lockBodyScroll(true);
        }

        function cerrarModalInfraccion() {
            document.getElementById('modal-infraccion-detalle').classList.add('hidden');
            if (window.lockBodyScroll) window.lockBodyScroll(false);
        }

        document.getElementById('modal-infraccion-detalle')?.addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarModalInfraccion();
            }
        });

    <!-- Librería SheetJS para exportación nativa a Microsoft Excel (.xlsx) -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script>
        // Función de exportación a archivo nativo .xlsx
        function exportarTablaExcel(tablaId, nombreArchivo) {
            const tablaOriginal = document.getElementById(tablaId);
            if (!tablaOriginal) return;

            // Clonar la tabla para preparar datos limpios
            const tablaClon = tablaOriginal.cloneNode(true);

            // Eliminar filas que estén ocultas por el buscador o mensaje de vacío
            const filas = tablaClon.querySelectorAll('tr');
            filas.forEach(fila => {
                if (fila.style.display === 'none' || fila.classList.contains('empty-row-infracciones')) {
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
                sheet: "Auditoría Infracciones", 
                raw: true 
            });

            // Autoajustar el ancho de las columnas
            const hoja = libroExcel.Sheets["Auditoría Infracciones"];
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
