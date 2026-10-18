<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{ $quiz->titulo }} | Evaluación Segura SISIG</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sena: {
                            green: '#39A900',
                            'green-hover': '#2d8500',
                            'green-light': '#E8F5E9',
                            navy: '#00324D',
                            'navy-light': '#004B73',
                            dark: '#001A29',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
        }
        /* Bloqueo estricto de selección de texto en todo el cuerpo */
        .bloquear-seleccion {
            -webkit-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
            user-select: none !important;
        }

        /* Estilos y Animaciones 3D de Vidas */
        #contenedor-corazones-3d {
            perspective: 900px;
            perspective-origin: center;
        }

        .heart-3d-box {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            position: relative;
            transform-style: preserve-3d;
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), filter 0.4s ease;
            cursor: pointer;
            filter: drop-shadow(0 4px 6px rgba(225, 29, 72, 0.35));
            will-change: transform;
        }

        .heart-3d-box:hover {
            transform: translateZ(14px) rotateX(12deg) rotateY(-14deg) scale(1.18) !important;
            filter: drop-shadow(0 8px 14px rgba(225, 29, 72, 0.65)) !important;
        }

        .heart-3d-box.heart-alive {
            animation: heartFloat3D 3.2s ease-in-out infinite;
        }

        #heart-life-1.heart-alive {
            animation-delay: 0s;
        }

        #heart-life-2.heart-alive {
            animation-delay: 0.35s;
        }

        #heart-life-3.heart-alive {
            animation-delay: 0.7s;
        }

        .heart-3d-box.heart-critical {
            animation: heartCriticalPulse3D 0.85s ease-in-out infinite !important;
            filter: drop-shadow(0 0 14px rgba(244, 63, 94, 0.95)) !important;
        }

        .heart-3d-box.heart-lost {
            filter: grayscale(1) opacity(0.35) drop-shadow(0 2px 3px rgba(0,0,0,0.25)) !important;
            transform: scale(0.82) rotateX(15deg) rotateZ(-12deg) translateZ(-5px);
            animation: none !important;
            cursor: default;
        }

        .heart-3d-box.heart-shatter {
            animation: heartShatter3D 0.75s cubic-bezier(0.36, 0.07, 0.19, 0.97) both !important;
        }

        @keyframes heartFloat3D {
            0%, 100% {
                transform: translateY(0) rotateX(8deg) rotateY(-8deg) translateZ(0);
            }
            50% {
                transform: translateY(-4px) rotateX(-6deg) rotateY(10deg) translateZ(8px) scale(1.06);
            }
        }

        @keyframes heartCriticalPulse3D {
            0%, 100% {
                transform: scale3d(1, 1, 1) translateZ(0);
            }
            15% {
                transform: scale3d(1.26, 1.26, 1.26) translateZ(16px) rotateX(-8deg);
            }
            30% {
                transform: scale3d(1.06, 1.06, 1.06) translateZ(4px);
            }
            45% {
                transform: scale3d(1.32, 1.32, 1.32) translateZ(20px) rotateX(8deg);
            }
        }

        @keyframes heartShatter3D {
            0% {
                transform: scale(1) translateZ(0);
            }
            20% {
                transform: scale(1.35) rotateX(-25deg) rotateY(35deg) translateZ(25px);
            }
            40% {
                transform: scale(1.15) rotateX(25deg) rotateY(-30deg) translateZ(15px);
            }
            60% {
                transform: scale(0.95) rotateZ(12deg) rotateX(-15deg);
            }
            80% {
                transform: scale(0.85) rotateZ(-14deg) rotateX(18deg);
            }
            100% {
                transform: scale(0.82) rotateX(15deg) rotateZ(-12deg) translateZ(-5px);
            }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen text-slate-800 flex flex-col justify-between bloquear-seleccion">

    <!-- 1. Barra Superior de Monitoreo y Supervisión Anti-Trampa (Sticky) -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/90 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3 flex flex-wrap items-center justify-between gap-4">
            
            <!-- Título del Quiz y Módulo -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-emerald-500/10 text-sena-green border border-emerald-500/20 flex items-center justify-center text-lg font-black">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-sena-green bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-100">
                            {{ $quiz->modulo->titulo ?? 'Módulo SIG' }}
                        </span>
                        <span class="text-[10px] font-bold text-slate-400">
                            Intento #{{ $resultado->intento }} de {{ $quiz->numero_intentos ?? 3 }}
                        </span>
                    </div>
                    <h1 class="text-sm sm:text-base font-extrabold text-slate-800 truncate max-w-xs sm:max-w-md">
                        {{ $quiz->titulo }}
                    </h1>
                </div>
            </div>

            <!-- Panel de Indicadores de Seguridad y Tiempo -->
            <div class="flex items-center gap-3 sm:gap-4 flex-wrap">
                
                <!-- Temporizador Sincronizado -->
                @if($segundosRestantes !== null)
                    <div id="contenedor-temporizador" class="flex items-center gap-2 px-3.5 py-2 rounded-2xl bg-slate-900 text-white shadow-inner">
                        <i class="fas fa-stopwatch text-amber-400 text-sm" id="icono-timer"></i>
                        <div class="text-xs">
                            <span class="text-[9px] block text-slate-400 font-bold uppercase tracking-wider">Tiempo Restante</span>
                            <span id="timer-display" class="font-mono font-black text-sm text-emerald-400 tracking-wider">--:--</span>
                        </div>
                    </div>
                @endif

                <!-- Indicador de Vidas de Integridad 3D Anti-Trampa -->
                <div class="flex items-center gap-3 px-3.5 py-1.5 rounded-2xl bg-white border border-slate-200 shadow-sm" title="Sistema de Integridad Anti-Trampa: Tienes 3 vidas">
                    <div class="text-left hidden sm:block">
                        <span class="text-[9px] font-extrabold uppercase tracking-wider text-slate-400 block leading-none">Integridad</span>
                        <span id="vidas-restantes-texto" class="text-[11px] font-black text-rose-600 block leading-tight mt-0.5">
                            @php
                                $faltasIni = $infraccionesCount ?? 0;
                                $vidasIni = max(0, ($maxAdvertencias ?? 3) - $faltasIni);
                            @endphp
                            {{ $vidasIni === 1 ? '¡1 vida!' : ($vidasIni === 0 ? '0 vidas' : $vidasIni . ' vidas') }}
                        </span>
                    </div>

                    <!-- Los 3 Corazones 3D Volumétricos -->
                    <div class="flex items-center gap-1.5" id="contenedor-corazones-3d">
                        @for($v = 1; $v <= 3; $v++)
                            @php
                                $esPerdido = $v > $vidasIni;
                            @endphp
                            <div id="heart-life-{{ $v }}" 
                                 class="heart-3d-box {{ $esPerdido ? 'heart-lost' : ($vidasIni === 1 ? 'heart-alive heart-critical' : 'heart-alive') }}" 
                                 title="Vida #{{ $v }}: {{ $esPerdido ? 'Perdida' : 'Activa' }}">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7 heart-svg transition-transform duration-300" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <defs>
                                        <!-- Degradado frontal 3D rubí activo con iluminación cenital -->
                                        <radialGradient id="grad-heart-active-quiz-{{ $v }}" cx="35%" cy="22%" r="72%">
                                            <stop offset="0%" stop-color="#ff8da1"/>
                                            <stop offset="25%" stop-color="#ff2e5b"/>
                                            <stop offset="65%" stop-color="#be123c"/>
                                            <stop offset="100%" stop-color="#700925"/>
                                        </radialGradient>
                                        <!-- Degradado frontal perdido (piedra de ceniza / grafito) -->
                                        <radialGradient id="grad-heart-lost-quiz-{{ $v }}" cx="35%" cy="25%" r="75%">
                                            <stop offset="0%" stop-color="#94a3b8"/>
                                            <stop offset="35%" stop-color="#64748b"/>
                                            <stop offset="75%" stop-color="#334155"/>
                                            <stop offset="100%" stop-color="#0f172a"/>
                                        </radialGradient>
                                        <!-- Luz de rebote ambiental inferior 3D -->
                                        <linearGradient id="grad-rim-quiz-{{ $v }}" x1="0%" y1="100%" x2="0%" y2="0%">
                                            <stop offset="0%" stop-color="#f43f5e" stop-opacity="0.8"/>
                                            <stop offset="100%" stop-color="#be123c" stop-opacity="0"/>
                                        </linearGradient>
                                    </defs>

                                    <!-- Sombra proyectada en la base (suelo 3D) -->
                                    <ellipse class="heart-ground-shadow transition-opacity duration-300" 
                                             cx="16" cy="30" rx="7.5" ry="1.6" 
                                             fill="rgba(15, 23, 42, 0.25)" 
                                             opacity="{{ $esPerdido ? '0.15' : '0.6' }}"/>

                                    <!-- Bisel de profundidad 3D (Extrusión posterior del corazón) -->
                                    <path class="heart-depth" 
                                          d="M16 29.5 C9.2 23.3 2.5 17.6 2.5 11.6 C2.5 6.2 6.5 2.2 11.5 2.2 C14.2 2.2 15.3 3.6 16 4.6 C16.7 3.6 17.8 2.2 20.5 2.2 C25.5 2.2 29.5 6.2 29.5 11.6 C29.5 17.6 22.8 23.3 16 29.5 Z" 
                                          fill="{{ $esPerdido ? '#0f172a' : '#4c0519' }}"/>

                                    <!-- Cara frontal volumétrica con iluminación esférica -->
                                    <path class="heart-body" 
                                          d="M16 27.5 C9.7 21.6 3.4 16.2 3.4 10.8 C3.4 6 7.2 2.6 11.5 2.6 C14.1 2.6 15.2 3.8 16 4.8 C16.8 3.8 17.9 2.6 20.5 2.6 C24.8 2.6 28.6 6 28.6 10.8 C28.6 16.2 22.3 21.6 16 27.5 Z" 
                                          fill="{{ $esPerdido ? 'url(#grad-heart-lost-quiz-' . $v . ')' : 'url(#grad-heart-active-quiz-' . $v . ')' }}"/>

                                    <!-- Reflejo curvo de luz ambiental inferior -->
                                    <path class="heart-rim-light {{ $esPerdido ? 'hidden' : '' }}" 
                                          d="M16 27.5 C10.5 22.5 5 17.5 5 12 C5 11 6 12 8 15 C10.5 18.5 14 23 16 26 C18 23 21.5 18.5 24 15 C26 12 27 11 27 12 C27 17.5 21.5 22.5 16 27.5 Z" 
                                          fill="url(#grad-rim-quiz-{{ $v }})" opacity="0.45"/>

                                    <!-- Brillo especular principal curvado (Lóbulo izquierdo de cristal) -->
                                    <path class="heart-shine {{ $esPerdido ? 'hidden' : '' }}" 
                                          d="M8.5 5.2 C6.8 6.5 5.5 8.5 5.5 10.8 C5.5 12 6.2 12.5 7.2 12.5 C8.2 12.5 8.5 10.8 9.6 8.8 C10.6 7 12 6.2 12.8 6.2 C12.8 5.2 10.5 4.5 8.5 5.2 Z" 
                                          fill="#ffffff" opacity="0.85"/>

                                    <!-- Micro-destello especular en lóbulo derecho -->
                                    <ellipse class="heart-shine-mini {{ $esPerdido ? 'hidden' : '' }}" 
                                             cx="22.5" cy="7.2" rx="1.8" ry="1.1" 
                                             transform="rotate(-28 22.5 7.2)" 
                                             fill="#ffffff" opacity="0.6"/>

                                    <!-- Grieta 3D incrustada con relieve de luz y sombra -->
                                    <path class="heart-crack-highlight {{ $esPerdido ? '' : 'hidden' }}" 
                                          d="M16.5 4.5 L14.5 10.5 L18.5 15.5 L13.5 21.5 L16.5 27.5" 
                                          stroke="rgba(255, 255, 255, 0.45)" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path class="heart-crack {{ $esPerdido ? '' : 'hidden' }}" 
                                          d="M16 4 L14 10 L18 15 L13 21 L16 27" 
                                          stroke="#090d16" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- Botón / Estado Pantalla Completa -->
                <button type="button" id="btn-solicitar-fullscreen" class="inline-flex items-center gap-2 px-3 py-2 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all cursor-pointer" title="Activar Pantalla Completa">
                    <i class="fas fa-expand text-slate-500"></i>
                    <span class="hidden md:inline">Pantalla Completa</span>
                </button>

            </div>
        </div>

        <!-- Banner de Advertencia de Integridad -->
        <div class="bg-gradient-to-r from-slate-900 via-sena-navy to-slate-900 text-slate-200 text-[11px] py-1.5 px-4 text-center border-t border-slate-700 flex items-center justify-center gap-2">
            <i class="fas fa-eye text-emerald-400 text-xs"></i>
            <span><strong>Monitoreo Activo:</strong> Mantén la ventana en pantalla completa. Queda prohibido cambiar de pestaña, copiar o usar atajos no autorizados.</span>
        </div>
    </header>

    <!-- 2. Contenido Principal del Examen -->
    <main class="max-w-4xl w-full mx-auto px-4 sm:px-6 py-8 flex-1" id="contenedor-examen">
        
        <form id="form-examen-quiz" action="{{ route('sisig.aprendiz.quiz.finalizar', $resultado->id_resultado) }}" method="POST" class="space-y-6">
            @csrf

            <!-- Encabezado de Indicaciones para el Aprendiz -->
            <div class="p-5 rounded-3xl bg-white border border-slate-200 shadow-sm flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-sena-green flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-slate-800">Cuestionario Oficial de Conocimientos</h2>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                        Lee detenidamente cada enunciado y selecciona la opción que consideres correcta. 
                        Puntaje mínimo de aprobación institucional: <strong>{{ number_format($quiz->puntaje_minimo, 0) }}%</strong>.
                    </p>
                </div>
            </div>

            <!-- Listado de Preguntas -->
            @forelse($preguntas as $index => $pregunta)
                <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-4 transition-all hover:border-slate-300">
                    
                    <!-- Encabezado de Pregunta -->
                    <div class="flex items-start justify-between gap-3 pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-xl bg-slate-900 text-white font-extrabold text-xs flex items-center justify-center">
                                {{ $index + 1 }}
                            </span>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                                Pregunta {{ $index + 1 }} de {{ $preguntas->count() }}
                            </span>
                        </div>
                        <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-xl border border-emerald-200">
                            {{ $pregunta->puntaje ?? 1 }} Pts
                        </span>
                    </div>

                    <!-- Enunciado -->
                    <p class="text-sm font-bold text-slate-800 leading-relaxed">
                        {{ $pregunta->pregunta }}
                    </p>

                    <!-- Opciones de Respuesta -->
                    <div class="space-y-2.5 pt-2">
                        @if($pregunta->opciones->count() > 0)
                            @foreach($pregunta->opciones as $opcion)
                                <label class="flex items-center gap-3.5 p-3.5 rounded-2xl border border-slate-200 hover:bg-slate-50 hover:border-emerald-300 transition-all cursor-pointer group">
                                    @if($pregunta->tipo === 'seleccion_multiple')
                                        <input type="checkbox" 
                                               name="respuestas[{{ $pregunta->id_pregunta }}][]" 
                                               value="{{ $opcion->id_opcion }}" 
                                               class="w-4 h-4 text-sena-green rounded border-slate-300 focus:ring-emerald-500 focus:ring-2 cursor-pointer">
                                    @else
                                        <input type="radio" 
                                               name="respuestas[{{ $pregunta->id_pregunta }}]" 
                                               value="{{ $opcion->id_opcion }}" 
                                               class="w-4 h-4 text-sena-green border-slate-300 focus:ring-emerald-500 focus:ring-2 cursor-pointer"
                                               required>
                                    @endif
                                    <span class="text-xs text-slate-700 group-hover:text-slate-900 font-medium">
                                        {{ $opcion->opcion }}
                                    </span>
                                </label>
                            @endforeach
                        @else
                            <p class="text-xs text-amber-600 italic">No hay opciones cargadas para esta pregunta.</p>
                        @endif
                    </div>

                </div>
            @empty
                <div class="p-8 text-center bg-white rounded-3xl border border-slate-200">
                    <p class="text-sm text-slate-500">No hay preguntas disponibles en esta evaluación.</p>
                </div>
            @endforelse

            <!-- Botón de Envío -->
            <div class="pt-4 pb-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <span class="text-xs text-slate-500 flex items-center gap-2">
                    <i class="fas fa-lock text-slate-400"></i>
                    <span>Tus respuestas se enviarán directamente al servidor de evaluación.</span>
                </span>
                <button type="button" id="btn-finalizar-examen" class="w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-sena-green hover:bg-emerald-600 text-white font-black text-sm shadow-lg shadow-emerald-600/30 transition-all cursor-pointer flex items-center justify-center gap-2">
                    <i class="fas fa-paper-plane"></i>
                    <span>Entregar y Finalizar Evaluación</span>
                </button>
            </div>

        </form>

    </main>

    <!-- 3. Modal de Obligatoriedad de Pantalla Completa -->
    <div id="modal-requiere-fullscreen" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4 hidden">
        <div class="max-w-md w-full bg-white rounded-3xl p-6 sm:p-8 text-center space-y-5 border border-slate-200 shadow-2xl">
            <div class="w-16 h-16 mx-auto rounded-3xl bg-rose-100 text-rose-600 flex items-center justify-center text-3xl">
                <i class="fas fa-compress-arrows-alt"></i>
            </div>
            <div>
                <h3 class="text-lg font-extrabold text-slate-900">Pantalla Completa Requerida</h3>
                <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
                    Para garantizar la integridad académica del examen, es obligatorio permanecer en modo de pantalla completa. 
                    Salir de este modo genera una advertencia de seguridad.
                </p>
            </div>
            <button type="button" id="btn-modal-activar-fullscreen" class="w-full py-3 px-6 rounded-2xl bg-sena-green hover:bg-emerald-600 text-white font-extrabold text-xs shadow-lg shadow-emerald-600/20 transition-all cursor-pointer">
                Reanudar Pantalla Completa
            </button>
        </div>
    </div>

    <!-- 4. Lógica de Integridad y Temporizador Reactivo -->
    <script>
        // Cargar Script Anti-Trampa
        const configAntiTrampa = {
            intentoId: {{ $resultado->id_resultado }},
            incidenteUrl: "{{ route('sisig.aprendiz.quiz.incidente', $resultado->id_resultado) }}",
            csrfToken: "{{ csrf_token() }}",
            maxFaltas: {{ $maxAdvertencias }},
            faltasIniciales: {{ $infraccionesCount }},
            formSelector: '#form-examen-quiz',
            requierePantallaCompleta: true
        };
    </script>
    <script src="{{ asset('modules/sisig/js/anti-trampa.js') }}"></script>
    <script>
        // Fallback por si el asset no está publicado en public/
        if (typeof SisigAntiTrampa === 'undefined') {
            console.log('[Anti-Trampa] Cargando script embebido de contingencia...');
            @include('sisig::aprendiz.quiz._script_antitrampa_inline')
        }

        // Inicializar Sistema Anti-Trampa
        const monitorAntiTrampa = new SisigAntiTrampa(configAntiTrampa);

        // Control del Temporizador de Examen
        @if($segundosRestantes !== null)
            let tiempoRestante = {{ $segundosRestantes }};
            const timerDisplay = document.getElementById('timer-display');
            const contenedorTimer = document.getElementById('contenedor-temporizador');
            const iconoTimer = document.getElementById('icono-timer');

            function actualizarTimer() {
                if (tiempoRestante <= 0) {
                    clearInterval(intervaloTimer);
                    timerDisplay.textContent = '00:00';
                    if (window.Swal) {
                        Swal.fire({
                            title: '⏰ Tiempo Expirado',
                            text: 'El tiempo límite para realizar la evaluación ha concluido. El examen se enviará automáticamente.',
                            icon: 'info',
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                            allowOutsideClick: false
                        }).then(() => {
                            document.getElementById('form-examen-quiz').submit();
                        });
                    } else {
                        alert('El tiempo límite ha concluido. El examen se enviará automáticamente.');
                        document.getElementById('form-examen-quiz').submit();
                    }
                    return;
                }

                const minutos = Math.floor(tiempoRestante / 60);
                const segundos = tiempoRestante % 60;
                timerDisplay.textContent = `${String(minutos).padStart(2, '0')}:${String(segundos).padStart(2, '0')}`;

                // Alerta cuando quedan menos de 2 minutos
                if (tiempoRestante <= 120) {
                    timerDisplay.className = 'font-mono font-black text-sm text-rose-400 tracking-wider animate-pulse';
                    iconoTimer.className = 'fas fa-exclamation-triangle text-rose-400 text-sm animate-bounce';
                }

                tiempoRestante--;
            }

            actualizarTimer();
            const intervaloTimer = setInterval(actualizarTimer, 1000);
        @endif

        // Modal de pantalla completa
        document.getElementById('btn-modal-activar-fullscreen')?.addEventListener('click', () => {
            monitorAntiTrampa.solicitarPantallaCompleta();
            document.getElementById('modal-requiere-fullscreen').classList.add('hidden');
        });

        // Confirmación para finalizar el examen
        document.getElementById('btn-finalizar-examen')?.addEventListener('click', () => {
            if (window.Swal) {
                Swal.fire({
                    title: '¿Confirmas el envío de tu examen?',
                    text: 'Una vez enviado, tus respuestas serán calificadas y no podrás modificarlas.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, finalizar y enviar',
                    cancelButtonText: 'Revisar respuestas',
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#64748b',
                    customClass: {
                        popup: 'rounded-3xl border border-slate-200 shadow-2xl',
                        confirmButton: 'rounded-xl font-bold text-xs px-5 py-2.5',
                        cancelButton: 'rounded-xl font-bold text-xs px-5 py-2.5'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-examen-quiz').submit();
                    }
                });
            } else {
                if (confirm('¿Confirmas el envío de tu evaluación?')) {
                    document.getElementById('form-examen-quiz').submit();
                }
            }
        });
    </script>
</body>
</html>
