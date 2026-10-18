<x-sisig::layouts.admin title="Resultados: {{ $quiz->titulo }}">
    <div class="space-y-6 sm:space-y-8 animate-fade-in pb-16 w-full max-w-[1400px] mx-auto">

        @php
            $esAnulado = isset($resultado) && $resultado->fue_anulado;
        @endphp

        <!-- 1. Banner Principal de Calificación (Criterio 5) -->
        <div class="relative overflow-hidden rounded-3xl p-7 sm:p-9 text-white shadow-xl border {{ $esAnulado ? 'bg-gradient-to-r from-rose-900 via-rose-800 to-slate-900 border-rose-700' : ($aprobado ? 'bg-gradient-to-r from-emerald-700 via-teal-700 to-emerald-800 border-emerald-600' : 'bg-gradient-to-r from-rose-700 via-red-600 to-rose-800 border-rose-600') }}">
            <div class="absolute -top-24 -right-24 w-80 h-80 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-2">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider {{ $esAnulado ? 'bg-rose-500/30 text-rose-200 border border-rose-400/30' : ($aprobado ? 'bg-white/20 text-emerald-100' : 'bg-white/20 text-rose-100') }}">
                        <i class="fas {{ $esAnulado ? 'fa-ban' : ($aprobado ? 'fa-check-circle' : 'fa-times-circle') }}"></i>
                        <span>{{ $esAnulado ? 'Evaluación Anulada' : ($aprobado ? 'Evaluación Aprobada' : 'Evaluación No Aprobada') }}</span>
                    </span>

                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight">
                        {{ $quiz->titulo }}
                    </h1>

                    <p class="text-white/90 text-xs sm:text-sm font-normal max-w-2xl leading-relaxed">
                        @if($esAnulado)
                            {{ $resultado->motivo_anulacion ?? 'Esta evaluación fue anulada automáticamente por superar el límite de advertencias anti-trampa o por exceder el tiempo límite permitido.' }}
                        @elseif($aprobado)
                            ¡Felicitaciones! Has superado satisfactoriamente el puntaje mínimo requerido del <strong>{{ $puntajeMinimo }}%</strong> en esta evaluación del Sistema Integrado de Gestión.
                        @else
                            No alcanzaste el puntaje mínimo requerido del <strong>{{ $puntajeMinimo }}%</strong>. Revisa la retroalimentación pedagógica detallada a continuación para preparar tu siguiente intento.
                        @endif
                    </p>
                </div>

                <!-- Puntaje en Cifra Gigante -->
                <div class="flex items-center md:flex-col md:items-end justify-between gap-3 text-right bg-white/10 backdrop-blur-md px-6 py-4 rounded-3xl border border-white/20 self-start md:self-auto min-w-[160px]">
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-white/80 block">Calificación Obtenida</span>
                    <span class="text-4xl sm:text-5xl font-black text-white tracking-tight">
                        {{ number_format($puntaje, 1) }}%
                    </span>
                    <span class="text-xs text-white/80 font-medium">{{ $esAnulado ? 'Nota anulada (0.0)' : 'Mínimo: ' . $puntajeMinimo . '%' }}</span>
                </div>
            </div>
        </div>

        <!-- 2. Tarjetas Resumen de Desempeño (KPIs con degradados superiores) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            
            <!-- Estado -->
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm">
                <div class="absolute top-0 left-0 right-0 h-1.5 {{ $esAnulado ? 'bg-gradient-to-r from-rose-800 to-slate-900' : ($aprobado ? 'bg-gradient-to-r from-sena-green to-[#237000]' : 'bg-gradient-to-r from-rose-500 to-red-600') }}"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Resultado</span>
                    <i class="fas {{ $esAnulado ? 'fa-ban text-rose-600' : ($aprobado ? 'fa-award text-sena-green' : 'fa-exclamation-triangle text-rose-500') }} text-2xl"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-black {{ $esAnulado ? 'text-rose-700' : ($aprobado ? 'text-sena-green' : 'text-rose-600') }}">
                        {{ $esAnulado ? 'Anulado' : ($aprobado ? 'Aprobado' : 'No Aprobado') }}
                    </h3>
                    <div class="mt-2 text-xs text-slate-500 font-medium">
                        {{ $esAnulado ? 'Faltas al protocolo' : 'Estado de este intento' }}
                    </div>
                </div>
            </div>

            <!-- Preguntas Acertadas -->
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Aciertos</span>
                    <i class="fas fa-check-double text-2xl text-blue-600"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl font-black text-slate-800">
                        {{ $respuestasCorrectas }} / {{ $totalPreguntas }}
                    </h3>
                    <div class="mt-2 text-xs text-slate-500 font-medium">
                        Preguntas correctas
                    </div>
                </div>
            </div>

            <!-- Intento Presentado -->
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-amber-400 to-orange-500"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Intento</span>
                    <i class="fas fa-redo text-2xl text-amber-500"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl font-black text-slate-800">
                        #{{ $intentoNumero }}
                    </h3>
                    <div class="mt-2 text-xs text-slate-500 font-medium">
                        De {{ $intentosPermitidos }} intentos permitidos
                    </div>
                </div>
            </div>

            <!-- Intentos Restantes -->
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-purple-500 to-indigo-600"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Oportunidades</span>
                    <i class="fas fa-history text-2xl text-purple-500"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl font-black text-slate-800">
                        {{ $intentosRestantes }}
                    </h3>
                    <div class="mt-2 text-xs text-slate-500 font-medium">
                        {{ $intentosRestantes > 0 ? 'Intentos aún disponibles' : 'Intentos agotados' }}
                    </div>
                </div>
            </div>

        </div>

        <!-- 3. Acciones de Navegación -->
        <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/90 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('sisig.aprendiz.evaluaciones.index') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-all">
                    <i class="fas fa-arrow-left text-xs text-slate-400"></i>
                    <span>Volver a Mis Evaluaciones</span>
                </a>

                <a href="{{ route('sisig.aprendiz.dashboard') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl text-xs font-bold text-slate-600 hover:text-slate-900 transition-all">
                    <i class="fas fa-home text-xs"></i>
                    <span>Ir al Dashboard</span>
                </a>
            </div>

            @if($puedeReintentar)
                <a href="{{ route('sisig.aprendiz.evaluaciones.iniciar', $quiz->id_quiz) }}" 
                   class="inline-flex items-center gap-2 px-6 py-2.5 rounded-2xl text-xs font-bold text-white bg-sena-green hover:bg-emerald-600 shadow-sena hover:shadow-lg transition-all">
                    <i class="fas fa-redo text-xs"></i>
                    <span>Presentar Siguiente Intento (#{{ $intentoNumero + 1 }})</span>
                </a>
            @endif
        </div>

        <!-- 4. Registro de Auditoría de Integridad y Seguridad (Si hubo infracciones) -->
        @if(isset($infracciones) && $infracciones->count() > 0)
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-sm font-bold">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-800">
                                Incidentes Detectados por el Sistema Anti-Trampa
                            </h3>
                            <p class="text-xs text-slate-500">
                                Registro de acciones registradas durante la presentación de este intento
                            </p>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-black bg-rose-100 text-rose-800">
                        {{ $infracciones->count() }} {{ $infracciones->count() === 1 ? 'advertencia' : 'advertencias' }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider">
                                <th class="py-2.5 px-3">#</th>
                                <th class="py-2.5 px-3">Incidente</th>
                                <th class="py-2.5 px-3">Detalle</th>
                                <th class="py-2.5 px-3 text-right">Hora</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($infracciones as $idx => $inf)
                                <tr class="hover:bg-slate-50/80">
                                    <td class="py-2.5 px-3 font-bold text-slate-400">{{ $idx + 1 }}</td>
                                    <td class="py-2.5 px-3 font-bold text-slate-800">{{ $inf->tipo_evento ?? $inf->tipo_infraccion }}</td>
                                    <td class="py-2.5 px-3 text-slate-600">{{ $inf->detalle }}</td>
                                    <td class="py-2.5 px-3 text-right font-mono text-slate-500 font-bold">
                                        {{ $inf->marca_tiempo ? $inf->marca_tiempo->format('H:i:s') : $inf->created_at->format('H:i:s') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- 5. Retroalimentación Pedagógica Inmediata por Pregunta (Criterio 6) -->
        @if(!empty($detallesRevision) && count($detallesRevision) > 0)
            <div class="space-y-6">
                <div class="flex items-center gap-3 pb-3 border-b border-slate-200">
                    <i class="fas fa-comment-dots text-2xl text-sena-green"></i>
                    <div>
                        <h2 class="text-lg sm:text-xl font-bold text-slate-800">
                            Retroalimentación Pedagógica Inmediata
                        </h2>
                        <p class="text-xs text-slate-500">
                            Revisión explicativa registrada en el sistema para reforzar los conocimientos evaluados
                        </p>
                    </div>
                </div>

                <div class="space-y-5">
                    @foreach($detallesRevision as $idx => $det)
                        <div class="bg-white rounded-3xl p-6 sm:p-7 border {{ $det->es_correcta ? 'border-emerald-200/90 bg-emerald-50/10' : 'border-rose-200/90 bg-rose-50/10' }} shadow-sm space-y-4">
                            
                            <!-- Encabezado de la Pregunta -->
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-xl font-black text-xs flex items-center justify-center {{ $det->es_correcta ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white' }}">
                                        {{ $idx + 1 }}
                                    </span>
                                    <h3 class="text-sm sm:text-base font-bold text-slate-800 leading-snug">
                                        {{ $det->pregunta }}
                                    </h3>
                                </div>

                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-black uppercase tracking-wider {{ $det->es_correcta ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                    <i class="fas {{ $det->es_correcta ? 'fa-check' : 'fa-times' }}"></i>
                                    <span>{{ $det->es_correcta ? 'Correcta' : 'Incorrecta' }}</span>
                                </span>
                            </div>

                            <!-- Opciones de Respuesta -->
                            <div class="space-y-2 pt-2">
                                @foreach($det->opciones as $opc)
                                    @php
                                        $esLaMarcada = $det->opcion_seleccionada && $det->opcion_seleccionada->id_opcion == $opc->id_opcion;
                                        $esLaCorrecta = $opc->es_correcta == 1;
                                    @endphp

                                    <div class="p-3.5 rounded-2xl border text-xs sm:text-sm font-medium flex items-center justify-between gap-3 {{ $esLaCorrecta ? 'bg-emerald-50 border-emerald-300 text-emerald-900 font-bold' : ($esLaMarcada ? 'bg-rose-50 border-rose-300 text-rose-900' : 'bg-slate-50 border-slate-100 text-slate-600') }}">
                                        <div class="flex items-center gap-3">
                                            <i class="fas {{ $esLaCorrecta ? 'fa-check-circle text-emerald-600' : ($esLaMarcada ? 'fa-times-circle text-rose-500' : 'fa-circle text-slate-300 text-[10px]') }}"></i>
                                            <span>{{ $opc->opcion }}</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            @if($esLaMarcada)
                                                <span class="px-2 py-0.5 rounded-lg text-[10px] font-extrabold uppercase {{ $det->es_correcta ? 'bg-emerald-200 text-emerald-800' : 'bg-rose-200 text-rose-800' }}">
                                                    Tu Respuesta
                                                </span>
                                            @endif
                                            @if($esLaCorrecta)
                                                <span class="px-2 py-0.5 rounded-lg text-[10px] font-extrabold uppercase bg-emerald-200 text-emerald-800">
                                                    Respuesta Correcta
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Caja de Retroalimentación Pedagógica (Criterio 6) -->
                            <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200/80 flex items-start gap-3 mt-3">
                                <i class="fas fa-lightbulb text-amber-500 text-base mt-0.5 flex-shrink-0"></i>
                                <div>
                                    <span class="text-xs font-black text-amber-900 uppercase tracking-wide block">
                                        Retroalimentación Pedagógica
                                    </span>
                                    <p class="text-xs text-amber-800 mt-1 leading-relaxed">
                                        {{ $det->retroalimentacion }}
                                    </p>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</x-sisig::layouts.admin>
