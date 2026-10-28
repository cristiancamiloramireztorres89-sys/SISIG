<x-sisig::layouts.admin title="Resultado de Evaluación">
    <div class="space-y-8 w-full max-w-[1200px] mx-auto pb-12">

        <!-- 1. Encabezado / Banner de Resultado según Estado -->
        @if($resultado->fue_anulado)
            <!-- Estado ANULADO por Infracciones -->
            <div class="p-8 sm:p-10 rounded-3xl bg-gradient-to-br from-rose-900 via-rose-800 to-slate-900 text-white shadow-xl relative overflow-hidden border border-rose-700/50">
                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-5 text-center md:text-left flex-col md:flex-row">
                        <div class="w-20 h-20 rounded-3xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-4xl text-rose-300 shadow-inner">
                            <i class="fas fa-shield-virus"></i>
                        </div>
                        <div>
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-rose-500/30 text-rose-200 border border-rose-400/30 mb-2">
                                <i class="fas fa-ban"></i>
                                <span>Evaluación Anulada</span>
                            </span>
                            <h1 class="text-2xl sm:text-3xl font-black text-white">
                                Prueba Anulada por Protocolo Anti-Trampa
                            </h1>
                            <p class="text-xs sm:text-sm text-rose-100/90 mt-1 max-w-xl">
                                {{ $resultado->motivo_anulacion ?? 'Se superó el límite de advertencias de integridad académica durante la presentación.' }}
                            </p>
                        </div>
                    </div>

                    <div class="bg-black/30 backdrop-blur-md px-6 py-4 rounded-3xl border border-white/10 text-center min-w-[150px]">
                        <span class="text-[10px] font-extrabold text-rose-300 uppercase tracking-wider block">Calificación Final</span>
                        <span class="text-4xl font-black text-rose-400 font-mono">0.00%</span>
                        <span class="text-[10px] text-rose-200/70 block mt-0.5">Nota anulada</span>
                    </div>
                </div>
            </div>
        @elseif($resultado->aprobado)
            <!-- Estado APROBADO -->
            <div class="p-8 sm:p-10 rounded-3xl bg-gradient-to-br from-emerald-800 via-sena-green to-teal-900 text-white shadow-xl relative overflow-hidden border border-emerald-500/30">
                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-5 text-center md:text-left flex-col md:flex-row">
                        <div class="w-20 h-20 rounded-3xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-4xl text-emerald-200 shadow-inner">
                            <i class="fas fa-award"></i>
                        </div>
                        <div>
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-white/20 text-emerald-100 border border-white/30 mb-2">
                                <i class="fas fa-check-circle"></i>
                                <span>¡Felicitaciones! Evaluación Aprobada</span>
                            </span>
                            <h1 class="text-2xl sm:text-3xl font-black text-white">
                                {{ $quiz->titulo }}
                            </h1>
                            <p class="text-xs sm:text-sm text-emerald-100 mt-1">
                                Has alcanzado y superado el estándar de aprobación institucional del {{ number_format($quiz->puntaje_minimo, 0) }}%.
                            </p>
                        </div>
                    </div>

                    <div class="bg-white/15 backdrop-blur-md px-6 py-4 rounded-3xl border border-white/20 text-center min-w-[150px]">
                        <span class="text-[10px] font-extrabold text-emerald-200 uppercase tracking-wider block">Calificación</span>
                        <span class="text-4xl font-black text-white font-mono">{{ number_format($resultado->puntaje, 1) }}%</span>
                        <span class="text-[10px] text-emerald-100 block mt-0.5">Aprobado</span>
                    </div>
                </div>
            </div>
        @else
            <!-- Estado NO APROBADO -->
            <div class="p-8 sm:p-10 rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-amber-950 text-white shadow-xl relative overflow-hidden border border-amber-600/30">
                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-5 text-center md:text-left flex-col md:flex-row">
                        <div class="w-20 h-20 rounded-3xl bg-amber-500/20 backdrop-blur-md border border-amber-500/30 flex items-center justify-center text-4xl text-amber-400 shadow-inner">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div>
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-400/30 mb-2">
                                <i class="fas fa-info-circle"></i>
                                <span>No Aprobado</span>
                            </span>
                            <h1 class="text-2xl sm:text-3xl font-black text-white">
                                {{ $quiz->titulo }}
                            </h1>
                            <p class="text-xs sm:text-sm text-slate-300 mt-1">
                                Obtuviste un puntaje menor al {{ number_format($quiz->puntaje_minimo, 0) }}% requerido para certificar este módulo.
                            </p>
                        </div>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md px-6 py-4 rounded-3xl border border-white/10 text-center min-w-[150px]">
                        <span class="text-[10px] font-extrabold text-amber-300 uppercase tracking-wider block">Calificación</span>
                        <span class="text-4xl font-black text-amber-400 font-mono">{{ number_format($resultado->puntaje, 1) }}%</span>
                        <span class="text-[10px] text-slate-400 block mt-0.5">Mínimo: {{ number_format($quiz->puntaje_minimo, 0) }}%</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- 2. Tarjetas de Métricas Detalladas (Estilo Unificado SISIG) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            
            <!-- Intento -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Intento Realizado</span>
                    <div class="w-8 h-8 rounded-full bg-sky-50 text-sky-600 flex items-center justify-center text-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                        <i class="fas fa-redo-alt"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl font-extrabold text-[#343A40] tracking-tight">
                        #{{ $resultado->intento }}
                    </h3>
                    <div class="mt-1 text-xs text-slate-400 font-medium">
                        De {{ $quiz->numero_intentos ?? 3 }} permitidos
                    </div>
                </div>
            </div>

            <!-- Tiempo Empleado -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Tiempo Empleado</span>
                    <div class="w-8 h-8 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center text-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                        <i class="fas fa-stopwatch"></i>
                    </div>
                </div>
                <div class="mt-3">
                    @php
                        $segundos = $resultado->tiempo_segundos_empleado ?? 0;
                        $min = floor($segundos / 60);
                        $sec = $segundos % 60;
                    @endphp
                    <h3 class="text-2xl font-extrabold text-[#343A40] tracking-tight">
                        {{ $min }}m {{ $sec }}s
                    </h3>
                    <div class="mt-1 text-xs text-slate-400 font-medium">
                        Duración de la prueba
                    </div>
                </div>
            </div>

            <!-- Infracciones de Seguridad -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Incidentes Detectados</span>
                    <div class="w-8 h-8 rounded-full {{ $infracciones->count() > 0 ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600' }} flex items-center justify-center text-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl font-extrabold {{ $infracciones->count() > 0 ? 'text-rose-600' : 'text-[#2E6F40]' }} tracking-tight">
                        {{ $infracciones->count() }} {{ $infracciones->count() === 1 ? 'falta' : 'faltas' }}
                    </h3>
                    <div class="mt-1 text-xs text-slate-400 font-medium">
                        Alertas anti-trampa
                    </div>
                </div>
            </div>

            <!-- Fecha y Hora -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Fecha Presentación</span>
                    <div class="w-8 h-8 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center text-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                        <i class="far fa-calendar-alt"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-lg font-extrabold text-[#343A40] tracking-tight truncate">
                        {{ $resultado->fecha_presentacion ? $resultado->fecha_presentacion->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}
                    </h3>
                    <div class="mt-1 text-xs text-slate-400 font-medium">
                        Registro temporal
                    </div>
                </div>
            </div>

        </div>

        <!-- 3. Registro de Auditoría de Integridad Académica (Log Anti-Trampa) -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-slate-900 text-white flex items-center justify-center text-lg">
                        <i class="fas fa-fingerprint"></i>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-extrabold text-slate-800">
                            Auditoría de Integridad y Supervisión Anti-Trampa
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Historial detallado de eventos detectados por el sistema durante la sesión de examen
                        </p>
                    </div>
                </div>

                @if($infracciones->count() === 0)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 self-start sm:self-auto">
                        <i class="fas fa-check-circle text-emerald-500"></i>
                        <span>Sesión 100% Limpia</span>
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 self-start sm:self-auto">
                        <i class="fas fa-exclamation-triangle text-rose-500"></i>
                        <span>{{ $infracciones->count() }} Advertencias Registradas</span>
                    </span>
                @endif
            </div>

            @if($infracciones->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider">
                                <th class="py-3 px-4">#</th>
                                <th class="py-3 px-4">Tipo de Incidente</th>
                                <th class="py-3 px-4">Detalle / Acción Detectada</th>
                                <th class="py-3 px-4 text-center">Hora Exacta</th>
                                <th class="py-3 px-4 text-right">Impacto</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($infracciones as $idx => $incidente)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-3.5 px-4 font-bold text-slate-400">
                                        {{ $idx + 1 }}
                                    </td>
                                    <td class="py-3.5 px-4 font-bold text-slate-800">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-exclamation-circle text-rose-500 text-sm"></i>
                                            <span>{{ $incidente->tipo_evento ?? $incidente->tipo_infraccion }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-600">
                                        {{ $incidente->detalle ?? 'Incidente auditado por monitor de ventana' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-mono text-slate-500 font-bold">
                                        {{ $incidente->marca_tiempo ? $incidente->marca_tiempo->format('H:i:s') : $incidente->created_at->format('H:i:s') }}
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black {{ $idx >= 2 ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800' }}">
                                            Advertencia {{ $idx + 1 }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-8 text-center bg-emerald-50/50 rounded-2xl border border-emerald-100 space-y-2">
                    <div class="w-12 h-12 mx-auto rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl">
                        <i class="fas fa-shield-check"></i>
                    </div>
                    <h3 class="text-sm font-bold text-emerald-900">Conducta Ejemplar en la Prueba</h3>
                    <p class="text-xs text-emerald-700 max-w-md mx-auto">
                        No se registraron cambios de ventana, salidas de pantalla completa, intentos de copiado ni uso de atajos no autorizados.
                    </p>
                </div>
            @endif
        </div>

        <!-- 4. Acciones y Navegación Final -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
            <a href="{{ route('sisig.aprendiz.dashboard') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-2xl bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold transition-all">
                <i class="fas fa-arrow-left text-[11px]"></i>
                <span>Volver al Dashboard del Aprendiz</span>
            </a>

            @if(!$resultado->aprobado && ($resultado->intento < ($quiz->numero_intentos ?? 3)))
                <a href="{{ route('sisig.aprendiz.quiz.iniciar', $quiz->id_quiz) }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-3.5 rounded-2xl bg-sena-green hover:bg-emerald-600 text-white text-xs font-black shadow-lg shadow-emerald-600/30 transition-all">
                    <i class="fas fa-redo-alt"></i>
                    <span>Presentar Siguiente Intento ({{ $resultado->intento + 1 }} de {{ $quiz->numero_intentos ?? 3 }})</span>
                </a>
            @endif
        </div>

    </div>
</x-sisig::layouts.admin>
