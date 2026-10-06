<x-sisig::layouts.editor title="Participantes">
    <div class="space-y-6 w-full max-w-[1600px] mx-auto pb-10">

        <!-- Banner de cabecera verde institucional -->
        <div class="relative overflow-hidden rounded-2xl p-6 sm:p-8 text-white shadow-sm" style="background: linear-gradient(135deg, #0d5c3a 0%, #116842 50%, #157347 100%);">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="space-y-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-white text-xl shadow-sm">
                            <i class="fas fa-users"></i>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                            Participantes
                        </h1>
                    </div>
                    <p class="text-emerald-100/90 text-xs sm:text-sm max-w-2xl font-normal leading-relaxed">
                        Consulta el listado de aprendices, su estado de avance por sección y filtra por resultado de evaluación.
                    </p>
                    <nav class="flex items-center gap-2 text-xs text-emerald-200/80 pt-1">
                        <a href="{{ route('sisig.editor.dashboard') }}" class="hover:text-white transition-colors">Inicio</a>
                        <span class="text-emerald-300/60">›</span>
                        <span class="text-emerald-200">Contenido</span>
                        <span class="text-emerald-300/60">›</span>
                        <span class="text-white font-semibold">Participantes</span>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Tarjetas de métricas superiores (4 KPIs) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
            <!-- Total Aprendices -->
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center gap-4 transition-all hover:shadow-md">
                <div class="w-14 h-14 rounded-2xl bg-[#e8f7ee] text-[#198754] flex items-center justify-center text-xl shrink-0">
                    <i class="fas fa-user-friends"></i>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight leading-none">
                        {{ $metrics['total'] ?? 0 }}
                    </div>
                    <div class="text-xs sm:text-sm font-medium text-slate-400 mt-1">
                        Total aprendices
                    </div>
                </div>
            </div>

            <!-- Aprobados -->
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center gap-4 transition-all hover:shadow-md">
                <div class="w-14 h-14 rounded-2xl bg-[#e8f7ee] text-[#198754] flex items-center justify-center text-xl shrink-0">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight leading-none">
                        {{ $metrics['aprobados'] ?? 0 }}
                    </div>
                    <div class="text-xs sm:text-sm font-medium text-slate-400 mt-1">
                        Aprobados
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

            <!-- No Aprobados -->
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center gap-4 transition-all hover:shadow-md">
                <div class="w-14 h-14 rounded-2xl bg-[#fdeeed] text-[#dc3545] flex items-center justify-center text-xl shrink-0">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight leading-none">
                        {{ $metrics['no_aprobados'] ?? 0 }}
                    </div>
                    <div class="text-xs sm:text-sm font-medium text-slate-400 mt-1">
                        No aprobados
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenedor Principal: Filtros y Tabla -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 sm:p-7 space-y-6">

            <!-- Subtítulo de sección -->
            <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-wider">
                <i class="fas fa-table-list text-emerald-600"></i>
                <span>Listado de aprendices registrados</span>
            </div>

            <!-- Barra de Filtros -->
            <form method="GET" action="{{ route('sisig.editor.participantes.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                <!-- Buscador -->
                <div class="md:col-span-6 relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por nombre o correo..."
                           class="w-full pl-11 pr-4 py-2.5 bg-[#fcfdfd] border border-slate-200 rounded-xl text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                </div>

                <!-- Filtro de Estados -->
                <div class="md:col-span-3">
                    <select name="estado" onchange="this.form.submit()"
                            class="w-full px-4 py-2.5 bg-[#fcfdfd] border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all cursor-pointer">
                        <option value="">Todos los estados</option>
                        <option value="Aprobado" {{ request('estado') === 'Aprobado' ? 'selected' : '' }}>Aprobado</option>
                        <option value="Pendiente" {{ request('estado') === 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="No aprobado" {{ request('estado') === 'No aprobado' ? 'selected' : '' }}>No aprobado</option>
                    </select>
                </div>

                <!-- Filtro de Secciones -->
                <div class="md:col-span-3">
                    <select name="seccion" onchange="this.form.submit()"
                            class="w-full px-4 py-2.5 bg-[#fcfdfd] border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all cursor-pointer">
                        <option value="">Todas las secciones</option>
                        <option value="calidad" {{ request('seccion') === 'calidad' ? 'selected' : '' }}>Calidad</option>
                        <option value="sst" {{ request('seccion') === 'sst' ? 'selected' : '' }}>SST</option>
                        <option value="ambiental" {{ request('seccion') === 'ambiental' ? 'selected' : '' }}>Ambiental</option>
                    </select>
                </div>
            </form>

            <!-- Encabezado secundario de la tabla -->
            <div class="flex items-center justify-between pt-2 border-t border-slate-50">
                <div class="flex items-center gap-2">
                    <i class="fas fa-list text-emerald-600"></i>
                    <h2 class="text-sm font-bold text-slate-800">Aprendices registrados</h2>
                </div>
                <span class="text-xs text-slate-400 font-medium">
                    {{ count($participantesList) }} {{ count($participantesList) === 1 ? 'aprendiz' : 'aprendices' }}
                </span>
            </div>

            <!-- Tabla de Aprendices -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="pb-3.5 pr-4">Aprendiz</th>
                            <th class="pb-3.5 px-4">Correo</th>
                            <th class="pb-3.5 px-4">Ficha / Área</th>
                            <th class="pb-3.5 px-4 text-center">Calidad</th>
                            <th class="pb-3.5 px-4 text-center">SST</th>
                            <th class="pb-3.5 px-4 text-center">Ambiental</th>
                            <th class="pb-3.5 px-4 text-center">Estado General</th>
                            <th class="pb-3.5 pl-4 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/80 text-sm">
                        @forelse($participantesList as $participante)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <!-- Aprendiz -->
                            <td class="py-4 pr-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-[#0d5c3a] text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-sm">
                                        {{ $participante->iniciales }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800 leading-snug group-hover:text-emerald-700 transition-colors">
                                            {{ $participante->nombre }}
                                        </div>
                                        <div class="text-xs text-slate-400 mt-0.5">
                                            CC - {{ $participante->documento }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Correo -->
                            <td class="py-4 px-4 text-xs text-slate-500 font-normal">
                                {{ $participante->correo }}
                            </td>

                            <!-- Ficha / Área -->
                            <td class="py-4 px-4 text-xs text-slate-500 font-normal">
                                {{ $participante->ficha }}
                            </td>

                            <!-- Calidad -->
                            <td class="py-4 px-4 text-center">
                                <div class="flex flex-col items-center gap-1.5 w-24 mx-auto">
                                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                        <div class="h-1.5 rounded-full {{ $participante->calidad >= 70 ? 'bg-[#10b981]' : ($participante->calidad > 0 ? 'bg-amber-400' : 'bg-slate-200') }}"
                                             style="width: {{ $participante->calidad }}%;"></div>
                                    </div>
                                    <span class="text-[11px] font-bold text-slate-700">{{ $participante->calidad }}%</span>
                                </div>
                            </td>

                            <!-- SST -->
                            <td class="py-4 px-4 text-center">
                                <div class="flex flex-col items-center gap-1.5 w-24 mx-auto">
                                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                        <div class="h-1.5 rounded-full {{ $participante->sst >= 70 ? 'bg-[#10b981]' : ($participante->sst > 0 ? 'bg-amber-400' : 'bg-slate-200') }}"
                                             style="width: {{ $participante->sst }}%;"></div>
                                    </div>
                                    <span class="text-[11px] font-bold text-slate-700">{{ $participante->sst }}%</span>
                                </div>
                            </td>

                            <!-- Ambiental -->
                            <td class="py-4 px-4 text-center">
                                <div class="flex flex-col items-center gap-1.5 w-24 mx-auto">
                                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                        <div class="h-1.5 rounded-full {{ $participante->ambiental >= 70 ? 'bg-[#10b981]' : ($participante->ambiental > 0 ? 'bg-amber-400' : 'bg-slate-200') }}"
                                             style="width: {{ $participante->ambiental }}%;"></div>
                                    </div>
                                    <span class="text-[11px] font-bold text-slate-700">{{ $participante->ambiental }}%</span>
                                </div>
                            </td>

                            <!-- Estado General -->
                            <td class="py-4 px-4 text-center">
                                @if($participante->estado === 'Aprobado')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#e8f7ee] text-[#198754]">
                                        ● Aprobado
                                    </span>
                                @elseif($participante->estado === 'Pendiente')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#fef9e7] text-[#d97706]">
                                        ● Pendiente
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#fdeeed] text-[#dc3545]">
                                        ● No aprobado
                                    </span>
                                @endif
                            </td>

                            <!-- Acciones -->
                            <td class="py-4 pl-4 text-center">
                                <button type="button"
                                        onclick="abrirModalDetalle({{ json_encode($participante) }})"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-semibold bg-[#e8f7ee] text-[#198754] hover:bg-[#d1e7dd] transition-all cursor-pointer border border-[#c3e6cb] shadow-xs hover:shadow-sm">
                                    <i class="fas fa-eye text-xs"></i>
                                    <span>Ver</span>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 px-4">
                                <div class="max-w-sm mx-auto flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-2xl mb-3">
                                        <i class="fas fa-users-slash"></i>
                                    </div>
                                    <h3 class="text-base font-bold text-slate-700">No hay aprendices disponibles</h3>
                                    <p class="text-xs text-slate-400 mt-1 leading-relaxed">
                                        No se encontraron participantes registrados que coincidan con los filtros de búsqueda aplicados.
                                    </p>
                                    @if(request('buscar') || request('estado') || request('seccion'))
                                    <a href="{{ route('sisig.editor.participantes.index') }}"
                                       class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold bg-emerald-600 text-white hover:bg-emerald-700 transition-colors shadow-sm">
                                        <i class="fas fa-sync-alt"></i> Limpiar filtros
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>

    <!-- Modal Detalle del Aprendiz -->
    <div id="modal-detalle-aprendiz" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full shadow-2xl border border-slate-100 overflow-hidden transform transition-all animate-fade-in">
            <!-- Modal Header -->
            <div class="p-6 pb-4 border-b border-slate-100 flex items-center justify-between" style="background: linear-gradient(135deg, #0d5c3a 0%, #157347 100%); color: white;">
                <div class="flex items-center gap-3">
                    <div id="modal-avatar" class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md text-white font-extrabold text-sm flex items-center justify-center shadow-inner">
                        --
                    </div>
                    <div>
                        <h3 id="modal-nombre" class="font-extrabold text-base leading-snug text-white">Nombre Aprendiz</h3>
                        <p id="modal-documento" class="text-xs text-emerald-100/90 mt-0.5">CC - --</p>
                    </div>
                </div>
                <button type="button" onclick="cerrarModalDetalle()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-5 text-sm">
                <!-- Info general -->
                <div class="grid grid-cols-2 gap-3 p-3.5 bg-slate-50 rounded-2xl border border-slate-100 text-xs">
                    <div>
                        <span class="text-slate-400 block font-medium">Correo Electrónico</span>
                        <span id="modal-correo" class="text-slate-700 font-bold break-all">--</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-medium">Ficha / Programa</span>
                        <span id="modal-ficha" class="text-slate-700 font-bold">--</span>
                    </div>
                </div>

                <!-- Detalle por módulo formativo -->
                <div class="space-y-3">
                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Rendimiento por Sección</h4>

                    <!-- Calidad -->
                    <div class="p-3.5 rounded-2xl border border-slate-100 bg-[#fbfdfc] flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold">
                                <i class="fas fa-certificate"></i>
                            </div>
                            <div>
                                <span class="font-bold text-slate-800 text-xs block">Gestión de la Calidad</span>
                                <span class="text-[10px] text-slate-400">Norma ISO 9001</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span id="modal-calidad" class="text-xs font-extrabold text-slate-800 block">0%</span>
                            <span id="modal-calidad-badge" class="text-[10px] font-semibold text-slate-400">Pendiente</span>
                        </div>
                    </div>

                    <!-- SST -->
                    <div class="p-3.5 rounded-2xl border border-slate-100 bg-[#fbfdfc] flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xs font-bold">
                                <i class="fas fa-hard-hat"></i>
                            </div>
                            <div>
                                <span class="font-bold text-slate-800 text-xs block">Seguridad y Salud en el Trabajo</span>
                                <span class="text-[10px] text-slate-400">Norma ISO 45001</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span id="modal-sst" class="text-xs font-extrabold text-slate-800 block">0%</span>
                            <span id="modal-sst-badge" class="text-[10px] font-semibold text-slate-400">Pendiente</span>
                        </div>
                    </div>

                    <!-- Ambiental -->
                    <div class="p-3.5 rounded-2xl border border-slate-100 bg-[#fbfdfc] flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold">
                                <i class="fas fa-leaf"></i>
                            </div>
                            <div>
                                <span class="font-bold text-slate-800 text-xs block">Gestión Ambiental</span>
                                <span class="text-[10px] text-slate-400">Norma ISO 14001</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span id="modal-ambiental" class="text-xs font-extrabold text-slate-800 block">0%</span>
                            <span id="modal-ambiental-badge" class="text-[10px] font-semibold text-slate-400">Pendiente</span>
                        </div>
                    </div>
                </div>

                <!-- Estado General Final -->
                <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                    <span class="text-xs font-bold text-slate-500">Estado Consolidado</span>
                    <span id="modal-estado-pill" class="text-xs font-bold px-3 py-1 rounded-full bg-slate-100 text-slate-600">--</span>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="p-4 bg-slate-50 border-t border-slate-100 flex justify-end">
                <button type="button" onclick="cerrarModalDetalle()" class="px-5 py-2 rounded-xl text-xs font-bold bg-slate-200 text-slate-700 hover:bg-slate-300 transition-colors cursor-pointer">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <!-- Script de control del Modal -->
    <script>
        function abrirModalDetalle(aprendiz) {
            document.getElementById('modal-avatar').textContent = aprendiz.iniciales || 'AP';
            document.getElementById('modal-nombre').textContent = aprendiz.nombre || 'Sin nombre';
            document.getElementById('modal-documento').textContent = 'CC - ' + (aprendiz.documento || 'N/A');
            document.getElementById('modal-correo').textContent = aprendiz.correo || 'N/A';
            document.getElementById('modal-ficha').textContent = aprendiz.ficha || 'Sin asignar';

            // Calidad
            const cal = aprendiz.calidad || 0;
            document.getElementById('modal-calidad').textContent = cal + '%';
            document.getElementById('modal-calidad-badge').textContent = cal >= 70 ? 'Aprobado' : (cal > 0 ? 'En progreso' : 'Sin iniciar');
            document.getElementById('modal-calidad-badge').className = cal >= 70 ? 'text-[10px] font-semibold text-emerald-600' : (cal > 0 ? 'text-[10px] font-semibold text-amber-600' : 'text-[10px] font-semibold text-slate-400');

            // SST
            const sst = aprendiz.sst || 0;
            document.getElementById('modal-sst').textContent = sst + '%';
            document.getElementById('modal-sst-badge').textContent = sst >= 70 ? 'Aprobado' : (sst > 0 ? 'En progreso' : 'Sin iniciar');
            document.getElementById('modal-sst-badge').className = sst >= 70 ? 'text-[10px] font-semibold text-emerald-600' : (sst > 0 ? 'text-[10px] font-semibold text-amber-600' : 'text-[10px] font-semibold text-slate-400');

            // Ambiental
            const amb = aprendiz.ambiental || 0;
            document.getElementById('modal-ambiental').textContent = amb + '%';
            document.getElementById('modal-ambiental-badge').textContent = amb >= 70 ? 'Aprobado' : (amb > 0 ? 'En progreso' : 'Sin iniciar');
            document.getElementById('modal-ambiental-badge').className = amb >= 70 ? 'text-[10px] font-semibold text-emerald-600' : (amb > 0 ? 'text-[10px] font-semibold text-amber-600' : 'text-[10px] font-semibold text-slate-400');

            // Estado general pill
            const pill = document.getElementById('modal-estado-pill');
            if (aprendiz.estado === 'Aprobado') {
                pill.textContent = '● Aprobado';
                pill.className = 'text-xs font-bold px-3 py-1 rounded-full bg-[#e8f7ee] text-[#198754]';
            } else if (aprendiz.estado === 'Pendiente') {
                pill.textContent = '● Pendiente';
                pill.className = 'text-xs font-bold px-3 py-1 rounded-full bg-[#fef9e7] text-[#d97706]';
            } else {
                pill.textContent = '● No aprobado';
                pill.className = 'text-xs font-bold px-3 py-1 rounded-full bg-[#fdeeed] text-[#dc3545]';
            }

            document.getElementById('modal-detalle-aprendiz').classList.remove('hidden');
        }

        function cerrarModalDetalle() {
            document.getElementById('modal-detalle-aprendiz').classList.add('hidden');
        }

        // Cerrar modal al hacer click fuera del contenedor
        document.getElementById('modal-detalle-aprendiz')?.addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarModalDetalle();
            }
        });
    </script>
</x-sisig::layouts.editor>