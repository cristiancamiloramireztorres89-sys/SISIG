<x-sisig::layouts.admin title="Quiz Interactivo | {{ $tituloSeccion }}">
    <div class="space-y-6 w-full max-w-4xl mx-auto pb-12">

        <!-- Barra Superior de Navegación del Quiz -->
        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('sisig.aprendiz.quiz.index') }}"
               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-bold transition-all shadow-xs">
                <i class="fas fa-arrow-left"></i>
                <span>Volver a secciones</span>
            </a>

            <!-- Temporizador Dinámico -->
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 border border-slate-800 text-white text-xs font-extrabold shadow-sm">
                <i class="fas fa-clock text-sena-green"></i>
                <span>Tiempo: <span id="timer-display">10:00</span></span>
            </div>
        </div>

        <!-- Banner de Encabezado de la Evaluación -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#001A29] via-[#002F48] to-[#004266] border border-slate-700/60 p-6 sm:p-8 text-white shadow-2xl">
            <!-- Destellos ambientales decorativos -->
            <div class="absolute -top-24 -right-24 w-80 h-80 bg-sena-green/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-sky-500/15 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex items-center justify-between gap-4">
                <div class="space-y-1">
                    <span class="text-sena-green text-[11px] font-extrabold tracking-wider uppercase flex items-center gap-2">
                        <i class="fas fa-gamepad"></i>
                        <span>Evaluación Interactiva</span>
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                        Quiz de {{ $tituloSeccion }}
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 font-normal">
                        Selecciona la respuesta correcta para cada una de las preguntas formativas.
                    </p>
                </div>

                <div class="hidden sm:flex w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md items-center justify-center text-white text-2xl shadow-sm border border-white/10">
                    <i class="fas fa-gamepad text-sena-green"></i>
                </div>
            </div>

            <!-- Barra de Progreso de Preguntas -->
            <div class="relative z-10 mt-5 space-y-1.5">
                <div class="flex justify-between text-xs text-slate-300 font-bold">
                    <span>Pregunta <span id="current-question-num">1</span> de {{ count($preguntas) }}</span>
                    <span id="progress-percent" class="text-sena-green font-extrabold">0%</span>
                </div>
                <div class="w-full bg-white/20 h-2.5 rounded-full overflow-hidden">
                    <div id="progress-bar-fill" class="h-2.5 bg-gradient-to-r from-sena-green to-emerald-400 rounded-full transition-all duration-300" style="width: {{ count($preguntas) > 0 ? round(100 / count($preguntas)) : 100 }}%;"></div>
                </div>
            </div>
        </div>

        <!-- Contenedor Principal de Preguntas -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-8 space-y-6">

            <form id="form-quiz" onsubmit="event.preventDefault(); enviarRespuestas();">
                @csrf

                @foreach($preguntas as $index => $pregunta)
                <div class="pregunta-step {{ $index === 0 ? '' : 'hidden' }}" id="step-{{ $index }}">
                    <!-- Pregunta -->
                    <div class="mb-6">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-extrabold bg-[#e8f5e9] text-[#0d5c3a] mb-3">
                            Pregunta #{{ $index + 1 }}
                        </span>
                        <h2 class="text-lg sm:text-xl font-bold text-slate-800 leading-snug">
                            {{ $pregunta->pregunta ?? $pregunta->texto }}
                        </h2>
                    </div>

                    <!-- Opciones de Respuesta -->
                    <div class="space-y-3">
                        @foreach($pregunta->opciones as $opcion)
                        <label class="opcion-card flex items-center gap-3.5 p-4 rounded-2xl border-2 border-slate-100 hover:border-[#0d5c3a]/50 bg-[#fcfdfd] hover:bg-[#f0fdf4] cursor-pointer transition-all duration-200 group">
                            <input type="radio"
                                   name="respuestas[{{ $pregunta->id_pregunta ?? $pregunta->id }}]"
                                   value="{{ $opcion->id_opcion ?? $opcion->id }}"
                                   required
                                   class="w-4 h-4 text-[#0d5c3a] focus:ring-[#0d5c3a] border-slate-300"
                                   onchange="actualizarSeleccion(this)">
                            <span class="text-sm font-medium text-slate-700 group-hover:text-slate-900 leading-relaxed">
                                {{ $opcion->opcion ?? $opcion->texto }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach

                <!-- Botones de Navegación del Paso a Paso -->
                <div class="flex items-center justify-between pt-6 border-t border-slate-100 mt-6">
                    <button type="button"
                            id="btn-prev"
                            onclick="navegarPregunta(-1)"
                            disabled
                            class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50 transition-colors disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer">
                        <i class="fas fa-chevron-left mr-1"></i> Anterior
                    </button>

                    <button type="button"
                            id="btn-next"
                            onclick="navegarPregunta(1)"
                            class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-[#001A29] via-[#002F48] to-[#004266] hover:brightness-110 text-white font-bold text-xs transition-all shadow-md cursor-pointer active:scale-95">
                        <span>Siguiente</span> <i class="fas fa-chevron-right ml-1"></i>
                    </button>

                    <button type="button"
                            id="btn-finish"
                            onclick="enviarRespuestas()"
                            class="hidden px-6 py-2.5 rounded-xl bg-gradient-to-r from-[#001A29] via-[#002F48] to-[#004266] hover:brightness-110 text-white font-bold text-xs transition-all shadow-md cursor-pointer active:scale-95">
                        <i class="fas fa-paper-plane mr-1 text-sena-green"></i> Finalizar y evaluar quiz
                    </button>
                </div>
            </form>

        </div>

    </div>

    <!-- Script de Juego Interactivo y Envío en Tiempo Real -->
    <script>
        const totalPreguntas = {{ count($preguntas) }};
        let currentStep = 0;
        let timerSeconds = 600; // 10 minutos
        let timerInterval = null;

        function iniciarTemporizador() {
            const display = document.getElementById('timer-display');
            timerInterval = setInterval(() => {
                if (timerSeconds <= 0) {
                    clearInterval(timerInterval);
                    Swal.fire({
                        title: '¡Tiempo agotado!',
                        text: 'El tiempo límite para responder este quiz ha finalizado.',
                        icon: 'warning',
                        confirmButtonColor: '#0d5c3a'
                    }).then(() => {
                        enviarRespuestas();
                    });
                    return;
                }
                timerSeconds--;
                const min = Math.floor(timerSeconds / 60);
                const sec = timerSeconds % 60;
                display.textContent = `${min.toString().padStart(2, '0')}:${sec.toString().padStart(2, '0')}`;
            }, 1000);
        }

        function navegarPregunta(dir) {
            const stepActual = document.getElementById(`step-${currentStep}`);
            
            if (dir > 0) {
                const seleccionada = stepActual.querySelector('input[type="radio"]:checked');
                if (!seleccionada) {
                    Swal.fire({
                        title: 'Selecciona una respuesta',
                        text: 'Por favor elige una opción para continuar a la siguiente pregunta.',
                        icon: 'info',
                        confirmButtonColor: '#0d5c3a'
                    });
                    return;
                }
            }

            stepActual.classList.add('hidden');
            currentStep += dir;
            if (currentStep < 0) currentStep = 0;
            if (currentStep >= totalPreguntas) currentStep = totalPreguntas - 1;

            const nuevoStep = document.getElementById(`step-${currentStep}`);
            nuevoStep.classList.remove('hidden');

            actualizarControles();
        }

        function actualizarControles() {
            document.getElementById('current-question-num').textContent = currentStep + 1;
            const porcentaje = Math.round(((currentStep + 1) / totalPreguntas) * 100);
            document.getElementById('progress-percent').textContent = `${porcentaje}%`;
            document.getElementById('progress-bar-fill').style.width = `${porcentaje}%`;

            const btnPrev = document.getElementById('btn-prev');
            const btnNext = document.getElementById('btn-next');
            const btnFinish = document.getElementById('btn-finish');

            btnPrev.disabled = (currentStep === 0);

            if (currentStep === totalPreguntas - 1) {
                btnNext.classList.add('hidden');
                btnFinish.classList.remove('hidden');
            } else {
                btnNext.classList.remove('hidden');
                btnFinish.classList.add('hidden');
            }
        }

        function actualizarSeleccion(radio) {
            const step = radio.closest('.pregunta-step');
            step.querySelectorAll('.opcion-card').forEach(card => {
                card.classList.remove('border-[#0d5c3a]', 'bg-[#f0fdf4]', 'ring-2', 'ring-[#0d5c3a]/20');
                card.classList.add('border-slate-100', 'bg-[#fcfdfd]');
            });
            const cardActiva = radio.closest('.opcion-card');
            cardActiva.classList.remove('border-slate-100', 'bg-[#fcfdfd]');
            cardActiva.classList.add('border-[#0d5c3a]', 'bg-[#f0fdf4]', 'ring-2', 'ring-[#0d5c3a]/20');
        }

        function enviarRespuestas() {
            const form = document.getElementById('form-quiz');
            const formData = new FormData(form);

            Swal.fire({
                title: 'Evaluando respuestas...',
                text: 'Calculando tu resultado en tiempo real.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch("{{ route('sisig.aprendiz.quiz.evaluar', $quiz->id_quiz ?? 0) }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                clearInterval(timerInterval);
                if (data.success) {
                    Swal.fire({
                        title: data.aprobado ? '¡Felicitaciones! Has aprobado' : 'Intento Completado',
                        html: `
                            <div class="text-center py-2">
                                <div class="text-4xl font-extrabold mb-2 ${data.aprobado ? 'text-[#0d5c3a]' : 'text-amber-600'}">
                                    ${data.puntaje} / 100
                                </div>
                                <p class="text-sm text-slate-600 font-medium mb-1">
                                    Aciertos: ${data.aciertos} de ${data.total} preguntas
                                </p>
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold ${data.aprobado ? 'bg-[#d1fae5] text-[#0d5c3a]' : 'bg-[#fef2f2] text-[#991b1b]'}">
                                    ${data.aprobado ? '● Sección Aprobada' : '● No Aprobado'}
                                </span>
                                <p class="text-xs text-slate-500 mt-3">
                                    ${data.mensaje}
                                </p>
                            </div>
                        `,
                        icon: data.aprobado ? 'success' : 'info',
                        confirmButtonText: 'Volver a Quizzes',
                        confirmButtonColor: '#0d5c3a',
                        showCancelButton: !data.aprobado,
                        cancelButtonText: 'Reintentar',
                        cancelButtonColor: '#64748b'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('sisig.aprendiz.quiz.index') }}";
                        } else {
                            window.location.reload();
                        }
                    });
                } else {
                    Swal.fire('Error', data.mensaje || 'No se pudo procesar la evaluación.', 'error');
                }
            })
            .catch(err => {
                Swal.fire('Error', 'Hubo un inconveniente al comunicarse con el servidor.', 'error');
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            iniciarTemporizador();
            actualizarControles();
        });
    </script>
</x-sisig::layouts.admin>
