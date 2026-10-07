<x-sisig::layouts.admin title="Mis Resultados | Aprendiz">
    <div class="space-y-7 w-full max-w-[1600px] mx-auto pb-12">

        <!-- Banner Principal de Mis Resultados (Color y estilo de la primera imagen / Dashboard) -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#001A29] via-[#002F48] to-[#004266] border border-slate-700/60 p-7 sm:p-9 text-white shadow-2xl">
            <!-- Destellos ambientales decorativos -->
            <div class="absolute -top-24 -right-24 w-80 h-80 bg-sena-green/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-sky-500/15 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-white text-xl shadow-sm border border-white/10">
                            <i class="fas fa-chart-line text-sena-green"></i>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                            Mis Resultados
                        </h1>
                    </div>
                    <p class="text-slate-300 text-xs sm:text-sm max-w-2xl font-normal leading-relaxed">
                        Consulta tu desempeño en los quizzes, tu estado de aprobación por sección y el historial de intentos.
                    </p>
                    <nav class="flex items-center gap-2 text-xs text-slate-300/80 pt-1">
                        <a href="{{ route('sisig.aprendiz.dashboard') }}" class="hover:text-white transition-colors">Inicio</a>
                        <span class="text-slate-500">›</span>
                        <span class="text-slate-300">Evaluaciones</span>
                        <span class="text-slate-500">›</span>
                        <span class="text-white font-semibold">Mis Resultados</span>
                    </nav>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 1: Puntaje y Estado por Sección -->
        <div class="space-y-4">
            <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-wider">
                <span class="w-2.5 h-2.5 rounded-full bg-[#198754] inline-block shadow-xs"></span>
                <span>Puntaje y estado por sección</span>
            </div>

            <!-- Rejilla de las 3 Tarjetas de Resumen SIG -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($tarjetasSecciones as $key => $card)
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all p-6 sm:p-7 flex flex-col justify-between">
                    <!-- Cabecera de la tarjeta: Icono temático + Título y Norma -->
                    <div class="flex items-center gap-3.5 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-[#e8f5e9] text-[#0d5c3a] flex items-center justify-center text-xl shrink-0 shadow-xs">
                            @if($card->slug === 'sst')
                                <svg class="w-6 h-6 fill-current text-[#0d5c3a]" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 2L6 17h12L12 2zm0 4.2l3.4 8.8H8.6L12 6.2zM4 19h16v3H4v-3z"/>
                                </svg>
                            @else
                                <i class="{{ $card->icono }}"></i>
                            @endif
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-800 leading-snug">
                                {{ $card->titulo }}
                            </h2>
                            <p class="text-xs text-slate-400 font-medium">
                                {{ $card->norma }}
                            </p>
                        </div>
                    </div>

                    <!-- Gráfico Circular Central de Calificación -->
                    <div class="my-4 text-center">
                        @if($card->intentos_count > 0 && $card->puntaje !== null)
                            @php
                                $esAprobado = $card->estado === 'Aprobado';
                                $colorAnillo = $esAprobado ? 'border-[#10b981]' : 'border-[#ef4444]';
                            @endphp
                            <div class="w-28 h-28 rounded-full border-4 {{ $colorAnillo }} mx-auto flex flex-col items-center justify-center shadow-xs">
                                <span class="text-3xl font-extrabold text-slate-800 leading-none">
                                    {{ $card->puntaje }}
                                </span>
                                <span class="text-[11px] font-bold text-slate-400 mt-1">
                                    /100
                                </span>
                            </div>
                        @else
                            <div class="w-28 h-28 rounded-full border-4 border-slate-200 mx-auto flex items-center justify-center text-slate-300 text-3xl shadow-xs">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                        @endif

                        <!-- Insignia de Estado -->
                        <div class="mt-4">
                            <span class="inline-block px-3.5 py-1 rounded-full text-xs font-bold {{ $card->badge_bg }} {{ $card->badge_text }} shadow-xs">
                                {{ $card->estado_badge }}
                            </span>
                        </div>

                        <!-- Texto de Regla / Umbral Aprobatorio -->
                        <div class="mt-2.5 text-xs text-slate-500 font-medium">
                            @if($card->intentos_count > 0)
                                Nota mínima requerida: <span class="font-bold text-slate-700">70/100</span>
                            @else
                                <span class="text-slate-400 italic">Aún no has realizado este quiz</span>
                            @endif
                        </div>

                        <!-- Barra Horizontal de Calificación -->
                        <div class="w-full bg-slate-100 rounded-full h-1.5 mt-3 overflow-hidden">
                            @if($card->intentos_count > 0 && $card->puntaje !== null)
                                <div class="h-1.5 rounded-full {{ $card->estado === 'Aprobado' ? 'bg-[#10b981]' : 'bg-[#ef4444]' }}"
                                     style="width: {{ min(100, $card->puntaje) }}%;"></div>
                            @else
                                <div class="h-1.5 rounded-full bg-slate-200" style="width: 0%;"></div>
                            @endif
                        </div>
                    </div>

                    <!-- Pie de la Tarjeta: Historial Breve -->
                    <div class="pt-3 border-t border-slate-50 text-center text-[11px] text-slate-400">
                        @if($card->intentos_count > 0)
                            Mejor puntaje: <strong class="text-slate-600">{{ $card->puntaje }}</strong> · {{ $card->intentos_count }} {{ $card->intentos_count == 1 ? 'intento realizado' : 'intentos realizados' }}
                        @else
                            Sin intentos registrados
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- SECCIÓN 2: Historial de Intentos Realizados -->
        <div class="space-y-4 pt-2">
            <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-wider">
                <i class="fas fa-clock text-[#0d5c3a]"></i>
                <span>Historial de intentos realizados</span>
            </div>

            <!-- Tarjeta Contenedora Principal -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-7 space-y-6">

                <!-- Encabezado con Título y Botón "Ir al Quiz" -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-2.5">
                        <i class="fas fa-table-cells text-[#002F48] text-lg"></i>
                        <h2 class="text-base font-bold text-slate-800">
                            Registro de intentos con fecha y puntaje
                        </h2>
                    </div>

                    <a href="{{ route('sisig.aprendiz.quiz.show', $seccionActiva) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-[#001A29] via-[#002F48] to-[#004266] hover:brightness-110 text-white transition-all text-xs font-bold shadow-md self-start sm:self-auto cursor-pointer active:scale-95">
                        <i class="fas fa-gamepad text-sena-green"></i>
                        <span>Ir al Quiz</span>
                    </a>
                </div>

                <!-- Pestañas de Selección de Sección SIG -->
                <div class="flex items-center gap-2.5 flex-wrap">
                    <!-- Calidad -->
                    <a href="{{ route('sisig.aprendiz.resultados.index', ['seccion' => 'calidad']) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $seccionActiva === 'calidad' ? 'bg-[#d1fae5] text-[#0d5c3a] border border-[#a7f3d0] shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                        <i class="fas fa-check-circle {{ $seccionActiva === 'calidad' ? 'text-[#0d5c3a]' : 'text-slate-400' }}"></i>
                        <span>Calidad</span>
                    </a>

                    <!-- SST -->
                    <a href="{{ route('sisig.aprendiz.resultados.index', ['seccion' => 'sst']) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $seccionActiva === 'sst' ? 'bg-[#d1fae5] text-[#0d5c3a] border border-[#a7f3d0] shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                        <i class="fas fa-hard-hat {{ $seccionActiva === 'sst' ? 'text-[#0d5c3a]' : 'text-slate-400' }}"></i>
                        <span>SST</span>
                    </a>

                    <!-- Ambiental -->
                    <a href="{{ route('sisig.aprendiz.resultados.index', ['seccion' => 'ambiental']) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $seccionActiva === 'ambiental' ? 'bg-[#d1fae5] text-[#0d5c3a] border border-[#a7f3d0] shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                        <i class="fas fa-tree {{ $seccionActiva === 'ambiental' ? 'text-[#0d5c3a]' : 'text-slate-400' }}"></i>
                        <span>Ambiental</span>
                    </a>
                </div>

                <!-- Tabla de Historial de Intentos -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                <th class="pb-3.5 pr-4">#</th>
                                <th class="pb-3.5 px-4">Fecha</th>
                                <th class="pb-3.5 px-4">Puntaje</th>
                                <th class="pb-3.5 px-4">Correctas</th>
                                <th class="pb-3.5 px-4">Estado</th>
                                <th class="pb-3.5 pl-4">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/80 text-sm">
                            @forelse($historialActual as $item)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <!-- Número de Intento -->
                                <td class="py-4 pr-4 text-xs font-semibold text-slate-400">
                                    Intento {{ $item->numero }}
                                </td>

                                <!-- Fecha de Presentación -->
                                <td class="py-4 px-4 text-xs text-slate-600 font-medium">
                                    {{ $item->fecha }}
                                </td>

                                <!-- Puntaje Obtenido -->
                                <td class="py-4 px-4 text-xs font-extrabold {{ $item->aprobado ? 'text-[#059669]' : 'text-[#dc2626]' }}">
                                    {{ $item->puntaje }}/100
                                </td>

                                <!-- Cantidad de Preguntas Correctas -->
                                <td class="py-4 px-4 text-xs text-slate-500 font-normal">
                                    {{ $item->correctas }}
                                </td>

                                <!-- Estado de Aprobación -->
                                <td class="py-4 px-4">
                                    @if($item->aprobado)
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-[#d1fae5] text-[#0d5c3a] shadow-xs">
                                            Aprobado
                                        </span>
                                    @else
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-[#fee2e2] text-[#b91c1c] shadow-xs">
                                            No aprobado
                                        </span>
                                    @endif
                                </td>

                                <!-- Acción -->
                                <td class="py-4 pl-4">
                                    @if($item->aprobado)
                                        <span class="text-xs font-semibold text-slate-400">
                                            Completado
                                        </span>
                                    @else
                                        <a href="{{ route('sisig.aprendiz.quiz.show', $seccionActiva) }}"
                                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-[#e8f7ee] text-[#0d5c3a] hover:bg-[#d1fae5] text-xs font-bold border border-[#c3e6cb] transition-colors cursor-pointer shadow-xs">
                                            <i class="fas fa-rotate-right text-xs"></i>
                                            <span>Reintentar</span>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-9 text-slate-400 text-xs">
                                    <i class="fas fa-info-circle text-[#0d5c3a] mr-1.5"></i>
                                    Aún no has realizado ningún intento para el Quiz de {{ ucfirst($seccionActiva) }}. Haz clic en <strong>"Ir al Quiz"</strong> para comenzar.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Nota Informativa al Pie de la Tabla -->
                <div class="pt-3 border-t border-slate-50 flex items-center gap-2 text-xs text-slate-400">
                    <i class="fas fa-info-circle text-slate-400"></i>
                    <span>
                        Máximo {{ $infoSeccionActiva->intentos_max ?? 3 }} intentos para esta sección. Has usado {{ count($historialActual) }}.
                    </span>
                </div>

            </div>
        </div>

    </div>
</x-sisig::layouts.admin>
