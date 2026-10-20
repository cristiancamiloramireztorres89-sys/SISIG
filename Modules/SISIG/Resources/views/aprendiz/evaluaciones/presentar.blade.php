<x-sisig::layouts.admin title="Evaluación: {{ $quiz->titulo }}">
    <div class="space-y-6 animate-fade-in pb-16 w-full max-w-[1400px] mx-auto bloquear-seleccion relative">

        <!-- Modal Inicial de Bienvenida y Activación Obligatoria de Pantalla Completa -->
        <div id="modal-bienvenida-segura" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/85 backdrop-blur-md p-4 animate-fade-in">
            <div class="relative bg-white rounded-3xl max-w-lg w-full p-7 sm:p-9 shadow-2xl border border-slate-200 text-center space-y-6">
                
                <!-- Icono de Seguridad 3D con Escudo y Candado -->
                <div class="w-20 h-20 mx-auto rounded-3xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-sena-green shadow-inner">
                    <i class="fas fa-shield-alt text-3xl"></i>
                </div>

                <div class="space-y-2">
                    <span class="px-3.5 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800">
                        Protocolo de Evaluación Segura
                    </span>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight">
                        {{ $quiz->titulo }}
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 max-w-sm mx-auto leading-relaxed">
                        Intento {{ $intentoActual }} de {{ $intentosPermitidos }} • {{ $quiz->preguntas->count() }} preguntas
                    </p>
                </div>

                <!-- Reglas de Integridad Claras -->
                <div class="bg-slate-50 rounded-2xl p-4 text-left border border-slate-100 space-y-3 text-xs">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-expand text-emerald-600 mt-1 flex-shrink-0 text-sm"></i>
                        <div>
                            <strong class="text-slate-800 block font-bold">Pantalla Completa Obligatoria</strong>
                            <span class="text-slate-500 text-[11px]">La prueba se realiza exclusivamente en pantalla completa para garantizar la integridad académica.</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="fas fa-heart text-rose-500 mt-1 flex-shrink-0 text-sm"></i>
                        <div>
                            <strong class="text-slate-800 block font-bold">3 Vidas de Integridad</strong>
                            <span class="text-slate-500 text-[11px]">Salir de pantalla completa o cambiar de pestaña restará 1 vida. Si pierdes las 3 vidas, el examen se anulará de inmediato.</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="far fa-clock text-amber-500 mt-1 flex-shrink-0 text-sm"></i>
                        <div>
                            <strong class="text-slate-800 block font-bold">15 Minutos de Tiempo</strong>
                            <span class="text-slate-500 text-[11px]">El cronómetro regresivo comenzará a correr en cuanto actives la pantalla completa.</span>
                        </div>
                    </div>
                </div>

                <!-- Botón de Inicio con Pantalla Completa -->
                <button type="button" 
                        id="btn-iniciar-fullscreen-modal"
                        onclick="iniciarEvaluacionConPantallaCompleta()" 
                        class="w-full py-4 px-6 rounded-2xl bg-sena-green hover:bg-emerald-600 text-white font-black text-sm shadow-lg shadow-emerald-600/30 hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center justify-center gap-3 cursor-pointer">
                    <i class="fas fa-play text-xs"></i>
                    <span>Comenzar Evaluación en Pantalla Completa</span>
                </button>
            </div>
        </div>

        <!-- 1. Cabecera Flotante con Cronómetro Regresivo e Indicador Anti-Trampa (Criterio 2) -->
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
                        <span class="font-bold text-sena-green">Intento {{ $intentoActual }} de {{ $intentosPermitidos }}</span>
                    </div>
                </div>
            </div>

            <!-- Panel de Seguridad y Cronómetro Regresivo -->
            <div class="flex items-center gap-3 sm:gap-4 flex-wrap justify-end">
                
                <!-- Indicador de Vidas de Integridad 3D Anti-Trampa -->
                <div class="flex items-center gap-3 px-3.5 py-1.5 rounded-2xl bg-white border border-slate-200/90 shadow-sm" title="Sistema de Integridad Anti-Trampa: Tienes 3 vidas">
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
                                // El corazón 3 se pierde primero (1 falta), el 2 segundo (2 faltas), el 1 tercero (3 faltas)
                                $esPerdido = $v > $vidasIni;
                            @endphp
                            <div id="heart-life-{{ $v }}" 
                                 class="heart-3d-box {{ $esPerdido ? 'heart-lost' : ($vidasIni === 1 ? 'heart-alive heart-critical' : 'heart-alive') }}" 
                                 title="Vida {{ $v }}: {{ $esPerdido ? 'Perdida' : 'Activa' }}">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7 heart-svg transition-transform duration-300" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <defs>
                                        <!-- Degradado frontal 3D rubí activo con iluminación cenital -->
                                        <radialGradient id="grad-heart-active-{{ $v }}" cx="35%" cy="22%" r="72%">
                                            <stop offset="0%" stop-color="#ff8da1"/>
                                            <stop offset="25%" stop-color="#ff2e5b"/>
                                            <stop offset="65%" stop-color="#be123c"/>
                                            <stop offset="100%" stop-color="#700925"/>
                                        </radialGradient>
                                        <!-- Degradado frontal perdido (piedra de ceniza / grafito) -->
                                        <radialGradient id="grad-heart-lost-{{ $v }}" cx="35%" cy="25%" r="75%">
                                            <stop offset="0%" stop-color="#94a3b8"/>
                                            <stop offset="35%" stop-color="#64748b"/>
                                            <stop offset="75%" stop-color="#334155"/>
                                            <stop offset="100%" stop-color="#0f172a"/>
                                        </radialGradient>
                                        <!-- Luz de rebote ambiental inferior 3D -->
                                        <linearGradient id="grad-rim-{{ $v }}" x1="0%" y1="100%" x2="0%" y2="0%">
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
                                          fill="{{ $esPerdido ? 'url(#grad-heart-lost-' . $v . ')' : 'url(#grad-heart-active-' . $v . ')' }}"/>

                                    <!-- Reflejo curvo de luz ambiental inferior -->
                                    <path class="heart-rim-light {{ $esPerdido ? 'hidden' : '' }}" 
                                          d="M16 27.5 C10.5 22.5 5 17.5 5 12 C5 11 6 12 8 15 C10.5 18.5 14 23 16 26 C18 23 21.5 18.5 24 15 C26 12 27 11 27 12 C27 17.5 21.5 22.5 16 27.5 Z" 
                                          fill="url(#grad-rim-{{ $v }})" opacity="0.45"/>

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

                <!-- Botón de Pantalla Completa -->
                <button type="button" id="btn-toggle-fullscreen" onclick="alternarPantallaCompleta()" 
                        class="hidden sm:inline-flex items-center gap-2 px-3 py-2 rounded-2xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-600 text-xs font-bold transition-all cursor-pointer" title="Modo Pantalla Completa">
                    <i class="fas fa-expand text-xs"></i>
                    <span id="txtPantallaCompleta">Pantalla Completa</span>
                </button>

                <!-- Cronómetro Regresivo Visible -->
                <div id="contenedorCronometro" class="flex items-center gap-2.5 px-4 py-2 rounded-2xl bg-slate-50 border border-slate-200 transition-all duration-300">
                    <i id="iconoCronometro" class="far fa-clock text-base text-slate-500"></i>
                    <div class="text-right">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block leading-none">Tiempo Restante</span>
                        <span id="relojRegresivo" class="text-lg font-black text-slate-800 tracking-wider font-mono">
                            @php
                                $minutosConfig = (!empty($quiz->tiempo_minutos) && (int)$quiz->tiempo_minutos > 0) ? (int)$quiz->tiempo_minutos : 15;
                                $segundosBase = (isset($segundosRestantes) && $segundosRestantes > 0) ? $segundosRestantes : ($minutosConfig * 60);
                                $minBase = floor($segundosBase / 60);
                                $secBase = $segundosBase % 60;
                            @endphp
                            {{ sprintf('%02d:%02d', $minBase, $secBase) }}
                        </span>
                    </div>
                </div>

                <button type="button" onclick="confirmarEnvioManual()" 
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-xs font-bold text-white bg-sena-green hover:bg-emerald-600 shadow-sena hover:shadow-lg transition-all cursor-pointer">
                    <i class="fas fa-paper-plane text-xs"></i>
                    <span>Finalizar Examen</span>
                </button>
            </div>
        </div>

        <!-- 2. Contenedor Principal: Preguntas y Navegación (Criterio 3) -->
        <form id="formEvaluacionQuiz" action="{{ route('sisig.aprendiz.evaluaciones.calificar', $quiz->id_quiz) }}" method="POST">
            @csrf
            @if(isset($resultado))
                <input type="hidden" name="id_resultado" value="{{ $resultado->id_resultado }}">
            @endif

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
                                    {{ $pregunta->tipo === 'seleccion_multiple' ? 'Selección Múltiple' : 'Opción Múltiple' }}
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
                                        <input type="{{ $pregunta->tipo === 'seleccion_multiple' ? 'checkbox' : 'radio' }}" 
                                               name="respuestas[{{ $pregunta->id_pregunta }}]{{ $pregunta->tipo === 'seleccion_multiple' ? '[]' : '' }}" 
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
                                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all cursor-pointer {{ $idx === 0 ? 'invisible' : '' }}">
                                    <i class="fas fa-chevron-left text-[10px]"></i>
                                    <span>Anterior</span>
                                </button>

                                @if($idx < $quiz->preguntas->count() - 1)
                                    <button type="button" 
                                            onclick="cambiarPregunta({{ $idx + 1 }})" 
                                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl text-xs font-bold text-white bg-slate-800 hover:bg-slate-900 transition-all cursor-pointer">
                                        <span>Siguiente</span>
                                        <i class="fas fa-chevron-right text-[10px]"></i>
                                    </button>
                                @else
                                    <button type="button" 
                                            onclick="confirmarEnvioManual()" 
                                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-2xl text-xs font-bold text-white bg-sena-green hover:bg-emerald-600 shadow-sena hover:shadow-lg transition-all cursor-pointer">
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
                                        class="h-10 rounded-xl text-xs font-bold border transition-all flex items-center justify-center cursor-pointer {{ $idx === 0 ? 'border-sena-green ring-2 ring-sena-green/30 bg-emerald-50 text-sena-green' : 'border-slate-200 text-slate-600 bg-slate-50 hover:bg-slate-100' }}">
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

                    <!-- Tarjeta de Instrucciones del Examen y Protocolo de Seguridad -->
                    <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/90 shadow-sm space-y-3">
                        <h4 class="text-xs font-bold text-slate-700 flex items-center gap-2">
                            <i class="fas fa-info-circle text-sky-500"></i> Indicaciones Clave
                        </h4>
                        <ul class="text-xs text-slate-500 space-y-2 leading-relaxed">
                            <li>• Puedes navegar libremente entre preguntas usando los botones o el mapa lateral.</li>
                            <li>• Si el cronómetro llega a <strong>00:00</strong>, el examen se enviará automáticamente con tus respuestas contestadas.</li>
                            <li>• <strong>Protocolo Anti-Trampa:</strong> No cambies de pestaña ni salgas de pantalla completa; acumular <strong>{{ $maxAdvertencias ?? 3 }} advertencias</strong> anulará tu evaluación automáticamente.</li>
                        </ul>
                    </div>

                </div>

            </div>
        </form>

    </div>

    <!-- Estilos de protección contra copiado y animación 3D de Vidas -->
    <style>
        .bloquear-seleccion, .bloquear-seleccion * {
            -webkit-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
            user-select: none !important;
        }

        /* Efectos y Animaciones 3D de Corazones */
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

    <!-- Script de Temporizador Regresivo, Navegación y Monitor Anti-Trampa Integrado -->
    <script>
        let preguntaActual = 0;
        const totalPreguntas = {{ $quiz->preguntas->count() }};
        @php
            $minutosJs = (!empty($quiz->tiempo_minutos) && (int)$quiz->tiempo_minutos > 0) ? (int)$quiz->tiempo_minutos : 15;
            $segundosInit = (isset($segundosRestantes) && $segundosRestantes > 0) ? $segundosRestantes : ($minutosJs * 60);
        @endphp
        let tiempoRestante = {{ $segundosInit }};
        let temporizadorInterval;
        let envioEnProgreso = false;

        // Configuración Anti-Trampa
        const antiTrampaConfig = {
            intentoId: {{ $resultado->id_resultado ?? 0 }},
            incidenteUrl: "{{ isset($resultado) ? route('sisig.aprendiz.evaluaciones.incidente', $resultado->id_resultado) : '' }}",
            csrfToken: "{{ csrf_token() }}",
            maxFaltas: {{ $maxAdvertencias ?? 3 }},
            faltasActuales: {{ $infraccionesCount ?? 0 }},
            bloqueado: false,
            examenIniciado: false,
            modalAbierto: false,
            ultimoIncidenteTiempo: 0,
            cooldownMs: 2000
        };

        document.addEventListener('DOMContentLoaded', function () {
            actualizarContadorPreguntas();
            actualizarEstilosMapa();
            actualizarUIAntiTrampa();
        });

        // Inicio seguro de evaluación activando Pantalla Completa
        function iniciarEvaluacionConPantallaCompleta() {
            solicitarPantallaCompleta();
            const modal = document.getElementById('modal-bienvenida-segura');
            if (modal) modal.classList.add('hidden');

            antiTrampaConfig.examenIniciado = true;
            antiTrampaConfig.ultimoIncidenteTiempo = 0;

            iniciarCronometro();
            iniciarMonitorAntiTrampa();
        }

        function solicitarPantallaCompleta() {
            const elem = document.documentElement;
            try {
                if (elem.requestFullscreen) {
                    elem.requestFullscreen().catch(err => console.warn('[Anti-Trampa] Fullscreen catch:', err));
                } else if (elem.webkitRequestFullscreen) {
                    elem.webkitRequestFullscreen();
                } else if (elem.mozRequestFullScreen) {
                    elem.mozRequestFullScreen();
                } else if (elem.msRequestFullscreen) {
                    elem.msRequestFullscreen();
                }
            } catch (e) {
                console.warn('[Anti-Trampa] Error solicitando fullscreen:', e);
            }
        }

        // 1. Iniciar Cronómetro Regresivo (Criterio 2)
        function iniciarCronometro() {
            const reloj = document.getElementById('relojRegresivo');
            const contenedor = document.getElementById('contenedorCronometro');
            const icono = document.getElementById('iconoCronometro');

            function tick() {
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
            }

            tick();
            temporizadorInterval = setInterval(tick, 1000);
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
                const respondida = document.querySelector(`input[name^="respuestas[${obtenerIdPregunta(i)}]"]:checked`);

                badge.className = "h-10 rounded-xl text-xs font-bold border transition-all flex items-center justify-center cursor-pointer ";

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
            const input = stepBox.querySelector('input[name^="respuestas"]');
            if (input) {
                const match = input.name.match(/\[(\d+)\]/);
                return match ? match[1] : '';
            }
            return '';
        }

        function actualizarContadorPreguntas() {
            let respondidas = 0;
            for (let i = 0; i < totalPreguntas; i++) {
                if (document.querySelector(`input[name^="respuestas[${obtenerIdPregunta(i)}]"]:checked`)) {
                    respondidas++;
                }
            }
            document.getElementById('contadorRespondidas').textContent = `${respondidas} / ${totalPreguntas} respondidas`;
        }

        // 4. Envío Automático por Tiempo Expirado (Criterio 4)
        function enviarPorTiempoExpirado() {
            if (envioEnProgreso) return;
            envioEnProgreso = true;

            if (window.Swal) {
                Swal.fire({
                    title: '⏰ Tiempo Expirado',
                    text: 'El tiempo límite de la evaluación ha terminado. Tus respuestas serán enviadas y calificadas automáticamente.',
                    icon: 'info',
                    timer: 3000,
                    showConfirmButton: false,
                    allowOutsideClick: false
                }).then(() => {
                    document.getElementById('formEvaluacionQuiz').submit();
                });
            } else {
                alert('¡El tiempo límite de la evaluación ha terminado! Tus respuestas serán enviadas y calificadas automáticamente.');
                document.getElementById('formEvaluacionQuiz').submit();
            }
        }

        // 5. Confirmación de Envío Manual
        function confirmarEnvioManual() {
            if (envioEnProgreso) return;

            let respondidas = 0;
            for (let i = 0; i < totalPreguntas; i++) {
                if (document.querySelector(`input[name^="respuestas[${obtenerIdPregunta(i)}]"]:checked`)) {
                    respondidas++;
                }
            }
            const pendientes = totalPreguntas - respondidas;

            let mensaje = `Has respondido ${respondidas} de ${totalPreguntas} preguntas.`;
            if (pendientes > 0) {
                mensaje += `<br><br><span class="text-amber-600 font-bold">¡Atención! Aún tienes ${pendientes} pregunta(s) sin responder.</span>`;
            }
            mensaje += `<br><br>¿Estás seguro de que deseas finalizar y calificar este intento?`;

            if (window.Swal) {
                Swal.fire({
                    title: '¿Finalizar Evaluación?',
                    html: mensaje,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, finalizar y enviar',
                    cancelButtonText: 'Seguir respondiendo',
                    confirmButtonColor: '#39A900',
                    cancelButtonColor: '#64748b',
                    customClass: {
                        popup: 'rounded-3xl border border-slate-200 shadow-2xl',
                        confirmButton: 'rounded-xl font-bold text-xs px-5 py-2.5',
                        cancelButton: 'rounded-xl font-bold text-xs px-5 py-2.5'
                    }
                }).then((res) => {
                    if (res.isConfirmed) {
                        envioEnProgreso = true;
                        clearInterval(temporizadorInterval);
                        document.getElementById('formEvaluacionQuiz').submit();
                    }
                });
            } else {
                if (confirm(`Has respondido ${respondidas} de ${totalPreguntas}.\n\n¿Estás seguro de que deseas finalizar?`)) {
                    envioEnProgreso = true;
                    clearInterval(temporizadorInterval);
                    document.getElementById('formEvaluacionQuiz').submit();
                }
            }
        }

        // 6. MOTOR DE INTEGRIDAD Y MONITOR ANTI-TRAMPA FUSIONADO
        function iniciarMonitorAntiTrampa() {
            if (!antiTrampaConfig.incidenteUrl) return;

            // A. Detección de pérdida de foco y cambio de pestañas
            document.addEventListener('visibilitychange', () => {
                if (document.hidden && antiTrampaConfig.examenIniciado && !antiTrampaConfig.bloqueado && !envioEnProgreso && !antiTrampaConfig.modalAbierto) {
                    reportarIncidenteAntiTrampa('cambio_pestana', 'El aprendiz cambió de pestaña o minimizó el navegador.');
                }
            });

            window.addEventListener('blur', () => {
                if (antiTrampaConfig.examenIniciado && !antiTrampaConfig.bloqueado && !envioEnProgreso && !antiTrampaConfig.modalAbierto) {
                    reportarIncidenteAntiTrampa('desenfoque_ventana', 'El navegador perdió el foco de la pantalla.');
                }
            });

            // B. Bloqueo de portapapeles y menú contextual
            document.addEventListener('copy', (e) => {
                if (antiTrampaConfig.bloqueado || envioEnProgreso || !antiTrampaConfig.examenIniciado) return;
                e.preventDefault();
                reportarIncidenteAntiTrampa('intento_copiar', 'Intento bloqueado de copiar texto del examen.');
            });

            document.addEventListener('cut', (e) => {
                if (antiTrampaConfig.bloqueado || envioEnProgreso || !antiTrampaConfig.examenIniciado) return;
                e.preventDefault();
                reportarIncidenteAntiTrampa('intento_copiar', 'Intento bloqueado de cortar texto del examen.');
            });

            document.addEventListener('paste', (e) => {
                if (antiTrampaConfig.bloqueado || envioEnProgreso || !antiTrampaConfig.examenIniciado) return;
                e.preventDefault();
                reportarIncidenteAntiTrampa('intento_pegar', 'Intento bloqueado de pegar texto externo.');
            });

            document.addEventListener('contextmenu', (e) => {
                if (antiTrampaConfig.bloqueado || envioEnProgreso || !antiTrampaConfig.examenIniciado) return;
                e.preventDefault();
                reportarIncidenteAntiTrampa('clic_derecho', 'Intento de abrir menú contextual con clic derecho.');
            });

            // Función auxiliar para vaciar portapapeles ante intento de captura o copia
            function vaciarPortapapelesSeguro() {
                try {
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText('').catch(() => {});
                    }
                } catch (err) {}
            }

            // B. Bloqueo de portapapeles y menú contextual
            document.addEventListener('copy', (e) => {
                if (antiTrampaConfig.bloqueado || envioEnProgreso || !antiTrampaConfig.examenIniciado) return;
                e.preventDefault();
                reportarIncidenteAntiTrampa('intento_copiar', 'Intento bloqueado de copiar texto del examen.');
            });

            document.addEventListener('cut', (e) => {
                if (antiTrampaConfig.bloqueado || envioEnProgreso || !antiTrampaConfig.examenIniciado) return;
                e.preventDefault();
                reportarIncidenteAntiTrampa('intento_copiar', 'Intento bloqueado de cortar texto del examen.');
            });

            document.addEventListener('paste', (e) => {
                if (antiTrampaConfig.bloqueado || envioEnProgreso || !antiTrampaConfig.examenIniciado) return;
                e.preventDefault();
                reportarIncidenteAntiTrampa('intento_pegar', 'Intento bloqueado de pegar texto externo (Ctrl+V / Shift+Insert / Menú).');
            });

            document.addEventListener('contextmenu', (e) => {
                if (antiTrampaConfig.bloqueado || envioEnProgreso || !antiTrampaConfig.examenIniciado) return;
                e.preventDefault();
                reportarIncidenteAntiTrampa('clic_derecho', 'Intento de abrir menú contextual con clic derecho.');
            });

            document.addEventListener('selectstart', (e) => {
                if (antiTrampaConfig.examenIniciado && !antiTrampaConfig.bloqueado && !envioEnProgreso) {
                    const tag = e.target.tagName ? e.target.tagName.toLowerCase() : '';
                    if (tag !== 'input' && tag !== 'textarea') {
                        e.preventDefault();
                    }
                }
            });

            document.addEventListener('dragstart', (e) => {
                if (antiTrampaConfig.examenIniciado && !antiTrampaConfig.bloqueado && !envioEnProgreso) {
                    e.preventDefault();
                }
            });

            // C. Bloqueo de atajos de teclado peligrosos (Capturas, Pegar/Copiar, IA Brave/Copilot, DevTools)
            document.addEventListener('keydown', (e) => {
                if (antiTrampaConfig.bloqueado || envioEnProgreso || !antiTrampaConfig.examenIniciado) return;

                const tecla = e.key ? e.key.toLowerCase() : '';
                const code = e.code ? e.code.toLowerCase() : '';
                const ctrl = e.ctrlKey || e.metaKey;
                const shift = e.shiftKey;
                const alt = e.altKey;

                // 1. Captura de pantalla (PrintScreen / Snapshot)
                if (e.key === 'PrintScreen' || code === 'printscreen' || e.key === 'Snapshot' || e.keyCode === 44) {
                    e.preventDefault();
                    vaciarPortapapelesSeguro();
                    reportarIncidenteAntiTrampa('captura_pantalla', 'Intento de captura de pantalla (Tecla ImprPant / PrintScreen).');
                    return;
                }

                // 2. Herramientas de recorte y captura por atajos (Shift+S en Windows, Cmd+Shift+3/4/5 en Mac)
                if ((ctrl || alt || e.metaKey) && shift && ['s', 'keys', '3', '4', '5'].includes(tecla || code)) {
                    e.preventDefault();
                    vaciarPortapapelesSeguro();
                    reportarIncidenteAntiTrampa('captura_pantalla', 'Intento de captura o recorte de pantalla (Shift+S / Recortes).');
                    return;
                }

                // 3. Atajos para invocar IA (Brave Leo Ctrl+B / Alt+B, Edge Copilot Ctrl+Shift+., etc.)
                if ((ctrl || alt) && (tecla === 'b' || code === 'keyb')) {
                    e.preventDefault();
                    reportarIncidenteAntiTrampa('asistente_ia', 'Intento de abrir asistente de IA en navegador (Ctrl+B / Alt+B / Brave Leo).');
                    return;
                }

                if (ctrl && shift && (tecla === '.' || code === 'period')) {
                    e.preventDefault();
                    reportarIncidenteAntiTrampa('asistente_ia', 'Intento de abrir asistente de IA Copilot en Edge (Ctrl+Shift+.).');
                    return;
                }

                if (alt && (tecla === 'c' || code === 'keyc')) {
                    e.preventDefault();
                    reportarIncidenteAntiTrampa('asistente_ia', 'Intento de abrir asistente de IA / Copilot (Alt+C).');
                    return;
                }

                if ((ctrl || alt) && (code === 'space' || tecla === ' ' || tecla === 'space')) {
                    e.preventDefault();
                    reportarIncidenteAntiTrampa('asistente_ia', 'Intento de abrir lanzador de búsqueda o asistente de IA (Espacio).');
                    return;
                }

                // Extensiones comunes de IA (ChatGPT, Monica, Merlin, Sider, etc.)
                if (ctrl && shift && ['l', 'o', 'e', 'y', 'a'].includes(tecla)) {
                    e.preventDefault();
                    reportarIncidenteAntiTrampa('asistente_ia', `Intento de abrir extensión de IA o herramienta externa (Ctrl+Shift+${tecla.toUpperCase()}).`);
                    return;
                }

                // 4. Portapapeles alternativo (Shift+Insert para pegar, Ctrl+Insert para copiar, Shift+Delete para cortar)
                if (shift && (e.key === 'Insert' || code === 'insert')) {
                    e.preventDefault();
                    reportarIncidenteAntiTrampa('intento_pegar', 'Intento de pegar contenido externo (Shift+Insert).');
                    return;
                }

                if (ctrl && (e.key === 'Insert' || code === 'insert')) {
                    e.preventDefault();
                    reportarIncidenteAntiTrampa('intento_copiar', 'Intento de copiar contenido del examen (Ctrl+Insert).');
                    return;
                }

                if (shift && (e.key === 'Delete' || code === 'delete')) {
                    e.preventDefault();
                    reportarIncidenteAntiTrampa('intento_copiar', 'Intento de cortar contenido (Shift+Delete).');
                    return;
                }

                // 5. DevTools y Teclas de función de depuración / búsqueda / recarga
                if (e.key === 'F12' || code === 'f12') {
                    e.preventDefault();
                    reportarIncidenteAntiTrampa('tecla_bloqueada', 'Intento de abrir herramientas de desarrollador (F12).');
                    return;
                }

                if (e.key === 'F5' || code === 'f5') {
                    e.preventDefault();
                    reportarIncidenteAntiTrampa('tecla_bloqueada', 'Intento de recargar el examen (F5).');
                    return;
                }

                if (e.key === 'F3' || code === 'f3') {
                    e.preventDefault();
                    reportarIncidenteAntiTrampa('tecla_bloqueada', 'Intento de buscar en la página (F3).');
                    return;
                }

                // 6. Atajos con Ctrl / Cmd
                if (ctrl) {
                    // Pegar
                    if (tecla === 'v' || code === 'keyv') {
                        e.preventDefault();
                        reportarIncidenteAntiTrampa('intento_pegar', 'Atajo Ctrl+V (Pegar contenido externo) bloqueado.');
                        return;
                    }
                    // Copiar / Cortar
                    if (['c', 'x'].includes(tecla) || ['keyc', 'keyx'].includes(code)) {
                        e.preventDefault();
                        reportarIncidenteAntiTrampa('intento_copiar', `Atajo Ctrl+${tecla.toUpperCase()} (Copiar/Cortar) bloqueado.`);
                        return;
                    }
                    // Seleccionar todo
                    if (tecla === 'a' || code === 'keya') {
                        e.preventDefault();
                        reportarIncidenteAntiTrampa('intento_copiar', 'Atajo Ctrl+A (Seleccionar todo) bloqueado.');
                        return;
                    }

                    // Búsqueda en página
                    if (['f', 'g'].includes(tecla)) {
                        e.preventDefault();
                        reportarIncidenteAntiTrampa('tecla_bloqueada', `Atajo de búsqueda Ctrl+${tecla.toUpperCase()} bloqueado.`);
                        return;
                    }

                    // Código fuente, guardar, imprimir, recargar
                    if (['u', 's', 'p', 'r'].includes(tecla)) {
                        e.preventDefault();
                        if (tecla === 'p') vaciarPortapapelesSeguro();
                        reportarIncidenteAntiTrampa(tecla === 'p' ? 'captura_pantalla' : 'tecla_bloqueada', `Atajo Ctrl+${tecla.toUpperCase()} bloqueado.`);
                        return;
                    }

                    // Nuevas pestañas, ventanas, historial o descargas
                    if (['n', 't', 'w', 'h', 'j'].includes(tecla)) {
                        e.preventDefault();
                        reportarIncidenteAntiTrampa('tecla_bloqueada', `Atajo de navegación Ctrl+${tecla.toUpperCase()} bloqueado.`);
                        return;
                    }

                    // Consola / Inspector DevTools (Ctrl+Shift+I/J/C/K)
                    if (shift && ['i', 'j', 'c', 'k'].includes(tecla)) {
                        e.preventDefault();
                        reportarIncidenteAntiTrampa('tecla_bloqueada', `Atajo Ctrl+Shift+${tecla.toUpperCase()} (Inspector/Consola) bloqueado.`);
                        return;
                    }
                }
            });

            // Captura reactiva de PrintScreen en keyup (por si el SO interceptó el keydown)
            window.addEventListener('keyup', (e) => {
                if (antiTrampaConfig.bloqueado || envioEnProgreso || !antiTrampaConfig.examenIniciado) return;
                if (e.key === 'PrintScreen' || (e.code && e.code.toLowerCase() === 'printscreen') || e.key === 'Snapshot' || e.keyCode === 44) {
                    vaciarPortapapelesSeguro();
                    reportarIncidenteAntiTrampa('captura_pantalla', 'Captura de pantalla detectada por el monitor (PrintScreen).');
                }
            });

            // D. Detección de salida de Pantalla Completa (Multi-navegador estándar)
            const eventosFS = ['fullscreenchange', 'webkitfullscreenchange', 'mozfullscreenchange', 'MSFullscreenChange'];
            eventosFS.forEach(ev => {
                document.addEventListener(ev, () => {
                    const enFS = !!(document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement);
                    actualizarTextoBotonFS(enFS);

                    if (!enFS && antiTrampaConfig.examenIniciado && !antiTrampaConfig.bloqueado && !envioEnProgreso && !antiTrampaConfig.modalAbierto) {
                        reportarIncidenteAntiTrampa('salida_pantalla_completa', 'Salida del modo de pantalla completa obligatoria.');
                    }
                });
            });

            // Supervisión activa periódica de Pantalla Completa cada segundo
            setInterval(() => {
                if (antiTrampaConfig.examenIniciado && !antiTrampaConfig.bloqueado && !envioEnProgreso && !antiTrampaConfig.modalAbierto) {
                    const enFS = !!(document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement);
                    if (!enFS) {
                        reportarIncidenteAntiTrampa('salida_pantalla_completa', 'Salida del modo de pantalla completa obligatoria detectada por el monitor de integridad.');
                    }
                }
            }, 1000);
        }

        function alternarPantallaCompleta() {
            const enFS = !!(document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement);
            if (!enFS) {
                solicitarPantallaCompleta();
            } else {
                if (document.exitFullscreen) document.exitFullscreen().catch(() => {});
                else if (document.webkitExitFullscreen) document.webkitExitFullscreen();
            }
        }

        function actualizarTextoBotonFS(enFS) {
            const txt = document.getElementById('txtPantallaCompleta');
            if (txt) {
                txt.textContent = enFS ? 'Salir Pantalla Completa' : 'Pantalla Completa';
            }
        }

        // Envío asíncrono hacia el backend con protección de ráfaga y cooldown
        async function reportarIncidenteAntiTrampa(tipoEvento, detalle) {
            if (antiTrampaConfig.bloqueado || envioEnProgreso || antiTrampaConfig.modalAbierto || !antiTrampaConfig.examenIniciado) return;

            const ahora = Date.now();
            if (ahora - antiTrampaConfig.ultimoIncidenteTiempo < antiTrampaConfig.cooldownMs) {
                return;
            }
            antiTrampaConfig.ultimoIncidenteTiempo = ahora;
            antiTrampaConfig.modalAbierto = true;

            try {
                const res = await fetch(antiTrampaConfig.incidenteUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': antiTrampaConfig.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        tipo_evento: tipoEvento,
                        detalle: detalle
                    })
                });

                if (!res.ok) {
                    antiTrampaConfig.modalAbierto = false;
                    return;
                }
                const data = await res.json();

                if (data.faltas !== undefined) {
                    antiTrampaConfig.faltasActuales = data.faltas;
                    actualizarUIAntiTrampa();
                }

                if (data.anulado || data.status === 'anulado') {
                    bloquearYAnularExamen(data);
                    return;
                }

                if (data.status === 'advertencia') {
                    mostrarAlertaAdvertencia(data);
                } else {
                    antiTrampaConfig.modalAbierto = false;
                }
            } catch (err) {
                console.error('[Anti-Trampa] Error al registrar incidente:', err);
                antiTrampaConfig.modalAbierto = false;
            }
        }

        function actualizarUIAntiTrampa() {
            const faltas = antiTrampaConfig.faltasActuales;
            const vidasRestantes = Math.max(0, 3 - faltas);

            const textoVidas = document.getElementById('vidas-restantes-texto');
            if (textoVidas) {
                if (vidasRestantes === 3) {
                    textoVidas.textContent = '3 vidas';
                    textoVidas.className = 'text-[11px] font-black text-rose-600 block leading-tight mt-0.5';
                } else if (vidasRestantes === 2) {
                    textoVidas.textContent = '2 vidas';
                    textoVidas.className = 'text-[11px] font-black text-amber-600 block leading-tight mt-0.5';
                } else if (vidasRestantes === 1) {
                    textoVidas.textContent = '¡Última vida!';
                    textoVidas.className = 'text-[11px] font-black text-rose-600 block leading-tight mt-0.5 animate-pulse';
                } else {
                    textoVidas.textContent = '0 vidas';
                    textoVidas.className = 'text-[11px] font-black text-slate-400 block leading-tight mt-0.5';
                }
            }

            // Actualizar estado volumétrico y capas 3D de cada corazón
            for (let v = 1; v <= 3; v++) {
                const heartBox = document.getElementById(`heart-life-${v}`);
                if (!heartBox) continue;

                const body = heartBox.querySelector('.heart-body');
                const depth = heartBox.querySelector('.heart-depth');
                const rimLight = heartBox.querySelector('.heart-rim-light');
                const shine = heartBox.querySelector('.heart-shine');
                const shineMini = heartBox.querySelector('.heart-shine-mini');
                const crack = heartBox.querySelector('.heart-crack');
                const crackHighlight = heartBox.querySelector('.heart-crack-highlight');
                const groundShadow = heartBox.querySelector('.heart-ground-shadow');

                const esPerdido = v > vidasRestantes;

                if (esPerdido) {
                    if (!heartBox.classList.contains('heart-lost')) {
                        heartBox.classList.add('heart-shatter');
                        setTimeout(() => heartBox.classList.remove('heart-shatter'), 750);
                    }
                    heartBox.classList.remove('heart-alive', 'heart-critical');
                    heartBox.classList.add('heart-lost');
                    heartBox.setAttribute('title', `Vida #${v}: Perdida`);

                    if (body) body.setAttribute('fill', `url(#grad-heart-lost-${v})`);
                    if (depth) depth.setAttribute('fill', '#0f172a');
                    if (groundShadow) groundShadow.setAttribute('opacity', '0.15');
                    if (rimLight) rimLight.classList.add('hidden');
                    if (shine) shine.classList.add('hidden');
                    if (shineMini) shineMini.classList.add('hidden');
                    if (crack) crack.classList.remove('hidden');
                    if (crackHighlight) crackHighlight.classList.remove('hidden');
                } else {
                    heartBox.classList.remove('heart-lost');
                    heartBox.classList.add('heart-alive');
                    heartBox.setAttribute('title', `Vida #${v}: Activa`);

                    if (vidasRestantes === 1 && v === 1) {
                        heartBox.classList.add('heart-critical');
                    } else {
                        heartBox.classList.remove('heart-critical');
                    }

                    if (body) body.setAttribute('fill', `url(#grad-heart-active-${v})`);
                    if (depth) depth.setAttribute('fill', '#4c0519');
                    if (groundShadow) groundShadow.setAttribute('opacity', '0.6');
                    if (rimLight) rimLight.classList.remove('hidden');
                    if (shine) shine.classList.remove('hidden');
                    if (shineMini) shineMini.classList.remove('hidden');
                    if (crack) crack.classList.add('hidden');
                    if (crackHighlight) crackHighlight.classList.add('hidden');
                }
            }
        }

        function mostrarAlertaAdvertencia(data) {
            const vidasRestantes = Math.max(0, 3 - antiTrampaConfig.faltasActuales);
            const titulo = `💔 ¡Has perdido una vida de integridad! (${vidasRestantes} ${vidasRestantes === 1 ? 'restante' : 'restantes'})`;
            const mensaje = data.mensaje || 'Se ha detectado una acción que compromete la integridad del examen.';

            const enFS = !!(document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement);
            const requiereVolverFS = !enFS;

            if (window.Swal) {
                Swal.fire({
                    title: titulo,
                    html: `
                        <div class="space-y-3 text-left text-xs sm:text-sm text-slate-600">
                            <p class="font-semibold text-slate-800">${mensaje}</p>
                            <div class="p-3.5 bg-rose-50 rounded-2xl border border-rose-200 text-rose-800 flex items-start gap-2.5">
                                <i class="fas fa-heart-broken text-rose-600 text-lg mt-0.5 flex-shrink-0"></i>
                                <div>
                                    <span class="font-black text-sm block">Vidas restantes: ${vidasRestantes} de 3</span>
                                    <p class="text-[11px] text-rose-700 mt-1 leading-snug">
                                        Al perder las 3 vidas por faltas al protocolo o salidas de pantalla, el examen se anulará automáticamente con nota 0.0.
                                    </p>
                                </div>
                            </div>
                            ${requiereVolverFS ? '<div class="p-2.5 bg-amber-50 rounded-xl border border-amber-200 text-amber-800 text-[11px] font-bold flex items-center gap-2"><i class="fas fa-expand text-amber-600"></i> Al continuar, se reactivará la Pantalla Completa obligatoria.</div>' : ''}
                        </div>
                    `,
                    icon: 'warning',
                    confirmButtonText: requiereVolverFS ? 'Reactivar Pantalla Completa y Continuar' : 'Continuar evaluación',
                    confirmButtonColor: '#39A900',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    customClass: {
                        popup: 'rounded-3xl border border-rose-200 shadow-2xl',
                        confirmButton: 'rounded-xl font-bold text-xs px-6 py-3 shadow-md'
                    }
                }).then(() => {
                    solicitarPantallaCompleta();
                    antiTrampaConfig.ultimoIncidenteTiempo = Date.now();
                    setTimeout(() => {
                        antiTrampaConfig.modalAbierto = false;
                    }, 1200);
                });
            } else {
                alert(`${titulo}\n\n${mensaje}\n\nVidas restantes: ${vidasRestantes} de 3.`);
                solicitarPantallaCompleta();
                antiTrampaConfig.ultimoIncidenteTiempo = Date.now();
                setTimeout(() => {
                    antiTrampaConfig.modalAbierto = false;
                }, 1200);
            }
        }

        function bloquearYAnularExamen(data) {
            antiTrampaConfig.bloqueado = true;
            envioEnProgreso = true;
            clearInterval(temporizadorInterval);

            // Deshabilitar todos los inputs
            const form = document.getElementById('formEvaluacionQuiz');
            if (form) {
                form.querySelectorAll('input, button').forEach(el => el.disabled = true);
            }

            const mensaje = data.mensaje || 'Has alcanzado el límite permitido de advertencias. El examen ha sido anulado.';
            const redirectUrl = data.redirect || window.location.href;

            if (window.Swal) {
                Swal.fire({
                    title: '⛔ Examen Anulado',
                    html: `
                        <div class="space-y-3 text-center">
                            <p class="text-sm font-bold text-slate-800">${mensaje}</p>
                            <p class="text-xs text-slate-500">Tus respuestas han sido bloqueadas y la calificación es 0.0.</p>
                        </div>
                    `,
                    icon: 'error',
                    showConfirmButton: true,
                    confirmButtonText: 'Ver Resultados',
                    confirmButtonColor: '#e11d48',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    timer: 3500,
                    timerProgressBar: true
                }).then(() => {
                    window.location.href = redirectUrl;
                });
            } else {
                alert(`EXAMEN ANULADO: ${mensaje}`);
                window.location.href = redirectUrl;
            }
        }
    </script>
</x-sisig::layouts.admin>
