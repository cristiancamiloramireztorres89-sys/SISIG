<x-sisig::layouts.admin title="Evaluación: {{ $quiz->titulo }}">
    <div class="space-y-6 animate-fade-in pb-16 w-full max-w-[1400px] mx-auto">

        <!-- 1. Cabecera Flotante con Cronómetro Regresivo (Criterio 2) -->
        <div class="sticky top-4 z-40 bg-white/95 backdrop-blur-md rounded-3xl p-4 sm:p-5 border border-slate-200/90 shadow-lg flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-sena-green flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-clipboard-check text-xl"></i>
                </div>
                <div>
                    <h1 class="text-base sm:text-lg font-black text-slate-900 leading-tight">{{ $quiz->titulo }}</h1>
                    <div class="flex items-center gap-2 mt-0.5 text-xs text-slate-500 font-medium">
                        <span>{{ $quiz->modulo->titulo ?? 'SIG La Angostura' }}</span>
                        <span>•</span>
                        <span class="font-bold text-sena-green">Intento #{{ $intentoActual }} de {{ $intentosPermitidos }}</span>
                    </div>
                </div>
            </div>

            <!-- Cronómetro Regresivo Visible -->
            <div class="flex items-center gap-4">
                <div id="contenedorCronometro" class="flex items-center gap-2.5 px-4 py-2 rounded-2xl bg-slate-50 border border-slate-200 transition-all duration-300">
                    <i id="iconoCronometro" class="far fa-clock text-base text-slate-500"></i>
                    <div class="text-right">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block leading-none">Tiempo Restante</span>
                        <span id="relojRegresivo" class="text-lg font-black text-slate-800 tracking-wider font-mono">
                            {{ sprintf('%02d:00', $quiz->tiempo_minutos ?? 15) }}
                        </span>
                    </div>
                </div>

                <button type="button" onclick="confirmarEnvioManual()" 
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-xs font-bold text-white bg-sena-green hover:bg-emerald-600 shadow-sena hover:shadow-lg transition-all">
                    <i class="fas fa-paper-plane text-xs"></i>
                    <span>Finalizar Examen</span>
                </button>
            </div>
        </div>

        <!-- 2. Contenedor Principal: Preguntas y Navegación (Criterio 3) -->
        <form id="formEvaluacionQuiz" action="{{ route('sisig.aprendiz.evaluaciones.calificar', $quiz->id_quiz) }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-start">
                
                <!-- Columna Izquierda: Espacio de la Pregunta Activa (3 Columnas) -->
                <div class="lg:col-span-3 space-y-6">
                    
                    @foreach($quiz->preguntas as $idx => $pregunta)
                        <div id="pregunta-step-{{ $idx }}" 
                             class="pregunta-step-box bg-white rounded-3xl p-6 sm:p-9 border border-slate-200/90 shadow-sm space-y-6 {{ $idx === 0 ? '' : 'hidden' }}">
                            
                            <!-- Indicador de Pregunta -->
                            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                                <span class="px-3.5 py-1.5 rounded-xl text-xs font-black text-slate-700 bg-slate-100 uppercase tracking-wide">
                                    Pregunta {{ $idx + 1 }} de {{ $quiz->preguntas->count() }}
                                </span>
                                <span class="text-xs font-semibold text-slate-400">
                                    Selección Múltiple
                                </span>
                            </div>

                            <!-- Enunciado de la Pregunta -->
                            <div class="space-y-2">
                                <h2 class="text-base sm:text-lg font-bold text-slate-800 leading-relaxed">
                                    {{ $pregunta->pregunta }}
                                </h2>
                            </div>

                            <!-- Opciones de Respuesta -->
                            <div class="space-y-3 pt-2">
                                @foreach($pregunta->opciones as $opc)
                                    <label class="group relative flex items-center p-4 rounded-2xl border-2 border-slate-200 hover:border-emerald-300 bg-slate-50/50 hover:bg-emerald-50/30 cursor-pointer transition-all duration-200 has-[:checked]:border-sena-green has-[:checked]:bg-emerald-50/60 has-[:checked]:shadow-xs">
                                        <input type="radio" 
                                               name="respuestas[{{ $pregunta->id_pregunta }}]" 
                                               value="{{ $opc->id_opcion }}" 
                                               class="w-4 h-4 text-sena-green border-slate-300 focus:ring-sena-green cursor-pointer"
                                               onchange="marcarPreguntaRespondida({{ $idx }})">
                                        <span class="ml-3.5 text-xs sm:text-sm font-semibold text-slate-700 group-hover:text-slate-900 leading-snug">
                                            {{ $opc->opcion }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>

                            <!-- Botones de Navegación entre Preguntas -->
                            <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
                                <button type="button" 
                                        onclick="cambiarPregunta({{ $idx - 1 }})" 
                                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all {{ $idx === 0 ? 'invisible' : '' }}">
                                    <i class="fas fa-chevron-left text-[10px]"></i>
                                    <span>Anterior</span>
                                </button>

                                @if($idx < $quiz->preguntas->count() - 1)
                                    <button type="button" 
                                            onclick="cambiarPregunta({{ $idx + 1 }})" 
                                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl text-xs font-bold text-white bg-slate-800 hover:bg-slate-900 transition-all">
                                        <span>Siguiente</span>
                                        <i class="fas fa-chevron-right text-[10px]"></i>
                                    </button>
                                @else
                                    <button type="button" 
                                            onclick="confirmarEnvioManual()" 
                                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-2xl text-xs font-bold text-white bg-sena-green hover:bg-emerald-600 shadow-sena hover:shadow-lg transition-all">
                                        <i class="fas fa-check-circle text-xs"></i>
                                        <span>Finalizar y Enviar</span>
                                    </button>
                                @endif
                            </div>

                        </div>
                    @endforeach

                </div>

                <!-- Columna Derecha: Mapa de Preguntas y Navegación Directa (Criterio 3) -->
                <div class="space-y-6">
                    
                    <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/90 shadow-sm space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400">
                                Mapa de Preguntas
                            </h3>
                            <span id="contadorRespondidas" class="text-xs font-bold text-sena-green">
                                0 / {{ $quiz->preguntas->count() }} respondidas
                            </span>
                        </div>

                        <!-- Grid de Pastillas Numéricas de Preguntas -->
                        <div class="grid grid-cols-5 gap-2">
                            @foreach($quiz->preguntas as $idx => $p)
                                <button type="button" 
                                        id="badge-pregunta-{{ $idx }}" 
                                        onclick="cambiarPregunta({{ $idx }})"
                                        class="h-10 rounded-xl text-xs font-bold border transition-all flex items-center justify-center {{ $idx === 0 ? 'border-sena-green ring-2 ring-sena-green/30 bg-emerald-50 text-sena-green' : 'border-slate-200 text-slate-600 bg-slate-50 hover:bg-slate-100' }}">
                                    {{ $idx + 1 }}
                                </button>
                            @endforeach
                        </div>

                        <!-- Leyenda -->
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400 font-medium">
                            <span class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-sena-green"></span> Respondida
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-slate-200"></span> Pendiente
                            </span>
                        </div>
                    </div>

                    <!-- Tarjeta de Instrucciones del Examen -->
                    <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/90 shadow-sm space-y-3">
                        <h4 class="text-xs font-bold text-slate-700 flex items-center gap-2">
                            <i class="fas fa-info-circle text-sky-500"></i> Indicaciones Clave
                        </h4>
                        <ul class="text-xs text-slate-500 space-y-2 leading-relaxed">
                            <li>• Puedes navegar libremente entre preguntas usando los botones o el mapa lateral.</li>
                            <li>• Si el cronómetro llega a <strong>00:00</strong>, el examen se enviará automáticamente con tus respuestas contestadas.</li>
                            <li>• Al finalizar recibirás tu calificación inmediata y retroalimentación pedagógica.</li>
                        </ul>
                    </div>

                </div>

            </div>
        </form>

    </div>

    <!-- Script de Temporizador Regresivo, Navegación y Envío Automático (Criterios 2, 3 y 4) -->
    <script>
        let preguntaActual = 0;
        const totalPreguntas = {{ $quiz->preguntas->count() }};
        let tiempoRestante = {{ ($quiz->tiempo_minutos ?? 15) * 60 }}; // En segundos
        let temporizadorInterval;
        let envioEnProgreso = false;

        document.addEventListener('DOMContentLoaded', function () {
            iniciarCronometro();
            actualizarContadorPreguntas();
        });

        // 1. Iniciar Cronómetro Regresivo (Criterio 2)
        function iniciarCronometro() {
            const reloj = document.getElementById('relojRegresivo');
            const contenedor = document.getElementById('contenedorCronometro');
            const icono = document.getElementById('iconoCronometro');

            temporizadorInterval = setInterval(function () {
                if (tiempoRestante <= 0) {
                    clearInterval(temporizadorInterval);
                    reloj.textContent = "00:00";
                    enviarPorTiempoExpirado();
                    return;
                }

                tiempoRestante--;

                const minutos = Math.floor(tiempoRestante / 60);
                const segundos = tiempoRestante % 60;
                reloj.textContent = String(minutos).padStart(2, '0') + ':' + String(segundos).padStart(2, '0');

                // Alerta visual cuando resten 2 minutos o menos
                if (tiempoRestante <= 120) {
                    contenedor.classList.remove('bg-slate-50', 'border-slate-200');
                    contenedor.classList.add('bg-rose-50', 'border-rose-300', 'animate-pulse');
                    reloj.classList.remove('text-slate-800');
                    reloj.classList.add('text-rose-600');
                    icono.classList.remove('text-slate-500');
                    icono.classList.add('text-rose-600');
                }
            }, 1000);
        }

        // 2. Navegación entre Preguntas (Criterio 3)
        function cambiarPregunta(nuevoIndice) {
            if (nuevoIndice < 0 || nuevoIndice >= totalPreguntas) return;

            document.getElementById(`pregunta-step-${preguntaActual}`).classList.add('hidden');
            document.getElementById(`pregunta-step-${nuevoIndice}`).classList.remove('hidden');

            preguntaActual = nuevoIndice;
            actualizarEstilosMapa();
        }

        // 3. Marcar Pregunta como Respondida
        function marcarPreguntaRespondida(idx) {
            actualizarEstilosMapa();
            actualizarContadorPreguntas();
        }

        function actualizarEstilosMapa() {
            for (let i = 0; i < totalPreguntas; i++) {
                const badge = document.getElementById(`badge-pregunta-${i}`);
                const respondida = document.querySelector(`input[name="respuestas[${obtenerIdPregunta(i)}]"]:checked`);

                badge.className = "h-10 rounded-xl text-xs font-bold border transition-all flex items-center justify-center ";

                if (i === preguntaActual) {
                    badge.className += "border-sena-green ring-2 ring-sena-green/30 font-black ";
                }

                if (respondida) {
                    badge.className += "bg-emerald-500 text-white border-emerald-600 shadow-xs ";
                } else {
                    badge.className += i === preguntaActual ? "bg-emerald-50 text-sena-green " : "bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100 ";
                }
            }
        }

        function obtenerIdPregunta(idx) {
            const stepBox = document.getElementById(`pregunta-step-${idx}`);
            const input = stepBox.querySelector('input[type="radio"]');
            if (input) {
                const match = input.name.match(/\[(\d+)\]/);
                return match ? match[1] : '';
            }
            return '';
        }

        function actualizarContadorPreguntas() {
            let respondidas = 0;
            for (let i = 0; i < totalPreguntas; i++) {
                if (document.querySelector(`input[name="respuestas[${obtenerIdPregunta(i)}]"]:checked`)) {
                    respondidas++;
                }
            }
            document.getElementById('contadorRespondidas').textContent = `${respondidas} / ${totalPreguntas} respondidas`;
        }

        // 4. Envío Automático por Tiempo Expirado (Criterio 4)
        function enviarPorTiempoExpirado() {
            if (envioEnProgreso) return;
            envioEnProgreso = true;

            alert('¡El tiempo límite de la evaluación ha terminado! Tus respuestas serán enviadas y calificadas automáticamente.');
            document.getElementById('formEvaluacionQuiz').submit();
        }

        // 5. Confirmación de Envío Manual
        function confirmarEnvioManual() {
            if (envioEnProgreso) return;

            let respondidas = 0;
            for (let i = 0; i < totalPreguntas; i++) {
                if (document.querySelector(`input[name="respuestas[${obtenerIdPregunta(i)}]"]:checked`)) {
                    respondidas++;
                }
            }
            const pendientes = totalPreguntas - respondidas;

            let mensaje = `Has respondido ${respondidas} de ${totalPreguntas} preguntas.`;
            if (pendientes > 0) {
                mensaje += `\n\n¡Atención! Aún tienes ${pendientes} pregunta(s) sin responder.`;
            }
            mensaje += `\n\n¿Estás seguro de que deseas finalizar y calificar este intento?`;

            if (confirm(mensaje)) {
                envioEnProgreso = true;
                clearInterval(temporizadorInterval);
                document.getElementById('formEvaluacionQuiz').submit();
            }
        }
    </script>
</x-sisig::layouts.admin>
