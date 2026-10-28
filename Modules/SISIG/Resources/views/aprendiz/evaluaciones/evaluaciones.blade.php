<x-sisig::layouts.admin title="Mis Evaluaciones SIG">
    <div class="space-y-6 sm:space-y-8 animate-fade-in pb-12 w-full max-w-[1600px] mx-auto">

        <!-- 1. Encabezado de la Sección (Estilo Limpio y Unificado) -->
        <div class="pb-5 border-b border-slate-200">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                    <i class="fas fa-clipboard-check text-sena-green"></i>
                    <span>Mis Evaluaciones SIG</span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Espacio evaluativo donde responderás cuestionarios interactivos con control de tiempo regresivo, comprobación de intentos y calificación instantánea.
                </p>
            </div>
        </div>

        <!-- 2. Tarjetas de Métricas Clave (KPIs) con Estilo Premium -->
        <!-- 2. Tarjetas de Métricas Clave (KPIs) con Estilo de Referencia Image 1 -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Total Evaluaciones -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Total Creados</span>
                    <div class="w-8 h-8 rounded-full bg-blue-50 text-[#1A2B4C] flex items-center justify-center text-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#343A40] tracking-tight">{{ $totalEvaluaciones }}</h3>
                    <div class="mt-1 flex items-center justify-between text-xs text-slate-400 font-medium">
                        <span>Evaluaciones en el sistema</span>
                        <span class="text-xs font-bold text-[#2E6F40]">Quices</span>
                    </div>
                </div>
            </div>

            <!-- Aprobadas -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Aprobadas</span>
                    <div class="w-8 h-8 rounded-full bg-emerald-50 text-[#2E6F40] flex items-center justify-center text-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#343A40] tracking-tight">{{ $totalAprobadas }}</h3>
                    <div class="mt-1 flex items-center justify-between text-xs text-slate-400 font-medium">
                        <span>Evaluaciones superadas</span>
                        <span class="text-xs font-bold text-[#2E6F40]">Acreditadas</span>
                    </div>
                </div>
            </div>

            <!-- Disponibles para presentar -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Disponibles</span>
                    <div class="w-8 h-8 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center text-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                        <i class="fas fa-play"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#343A40] tracking-tight">{{ $totalPendientes }}</h3>
                    <div class="mt-1 flex items-center justify-between text-xs text-slate-400 font-medium">
                        <span>Listas para presentar</span>
                        <span class="text-xs font-bold text-amber-600">En curso</span>
                    </div>
                </div>
            </div>

            <!-- Intentos Agotados -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Agotadas</span>
                    <div class="w-8 h-8 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center text-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                        <i class="fas fa-ban"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#343A40] tracking-tight">{{ $totalAgotadas }}</h3>
                    <div class="mt-1 flex items-center justify-between text-xs text-slate-400 font-medium">
                        <span>Sin intentos restantes</span>
                        <span class="text-xs font-bold text-rose-600">Finalizadas</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Barra de Búsqueda y Filtros Estilo Premium -->
        <div class="bg-white rounded-3xl p-4 sm:p-5 border border-slate-200/90 shadow-sm flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="relative w-full md:w-96">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" id="filtroQuizInput" placeholder="Buscar evaluación por nombre o módulo..." 
                       class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-2 focus:ring-sena-green/50 transition-all outline-none text-slate-700 placeholder-slate-400 shadow-inner">
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                @php
                    $modulosFiltroAprendiz = \Illuminate\Support\Facades\DB::table('sisig_modulos')
                        ->where('estado', '!=', 'inactivo')
                        ->orderBy('id', 'asc')
                        ->get();
                    if ($modulosFiltroAprendiz->isEmpty()) {
                        $modulosFiltroAprendiz = $evaluaciones->pluck('modulo.titulo')->unique()->filter()->map(function($t, $i) {
                            return (object)['id' => $i+1, 'titulo' => $t];
                        });
                    }
                    $totalOpcionesEv = $modulosFiltroAprendiz->count() + 1;
                    $tieneMasDeTresEv = $totalOpcionesEv > 3;
                @endphp

                <style>
                    #contenedor-modulos-ev::-webkit-scrollbar {
                        display: none;
                    }
                    #contenedor-modulos-ev {
                        -ms-overflow-style: none;
                        scrollbar-width: none;
                    }
                </style>

                <!-- Filtro Dinámico de Secciones SIG Aprendiz (Sin logos, Capacidad: 3) -->
                <div class="flex items-center gap-1.5 bg-slate-50 p-1.5 rounded-2xl border border-slate-200 select-none text-xs">
                    <span class="text-slate-400 font-bold px-2 hidden sm:inline whitespace-nowrap">Sección SIG:</span>
                    
                    @if($tieneMasDeTresEv)
                        <button type="button" 
                                id="btn-ev-modulos-prev"
                                onclick="desplazarModulosEv('left')" 
                                class="w-7 h-7 rounded-xl bg-white text-slate-500 hover:text-slate-900 hover:bg-slate-100 border border-slate-200/80 shadow-xs flex items-center justify-center transition-all active:scale-90 flex-shrink-0 cursor-pointer"
                                title="Ver módulos anteriores"
                                aria-label="Ver módulos anteriores">
                            <i class="fas fa-chevron-left text-[10px]"></i>
                        </button>
                    @endif

                    <div id="contenedor-modulos-ev" 
                         class="flex items-center gap-1.5 overflow-x-auto scroll-smooth {{ $tieneMasDeTresEv ? 'w-[240px] sm:w-[270px] md:w-[300px]' : '' }} py-0.5 px-0.5"
                         style="scrollbar-width: none; -ms-overflow-style: none;">
                        
                        <!-- Opción Todas -->
                        <button type="button" 
                                onclick="seleccionarModuloEv('', this)"
                                title="Todas las secciones"
                                @if($tieneMasDeTresEv) style="flex: 0 0 calc((100% - 12px) / 3); min-width: 0;" @endif
                                class="btn-ev-modulo-pill px-2.5 py-1.5 rounded-xl font-bold transition-all flex items-center justify-center flex-shrink-0 whitespace-nowrap text-center truncate bg-slate-900 text-white shadow-sm ring-1 ring-slate-900 is-active-ev"
                                data-modulo="">
                            <span class="truncate">Todas</span>
                        </button>

                        @foreach($modulosFiltroAprendiz as $mEv)
                            <button type="button" 
                                    onclick="seleccionarModuloEv('{{ strtolower($mEv->titulo) }}', this)"
                                    title="{{ $mEv->titulo }}"
                                    @if($tieneMasDeTresEv) style="flex: 0 0 calc((100% - 12px) / 3); min-width: 0;" @endif
                                    class="btn-ev-modulo-pill px-2.5 py-1.5 rounded-xl font-bold transition-all flex items-center justify-center flex-shrink-0 whitespace-nowrap text-center truncate text-slate-600 hover:bg-slate-200/80 hover:text-slate-900"
                                    data-modulo="{{ strtolower($mEv->titulo) }}">
                                <span class="truncate">{{ $mEv->titulo }}</span>
                            </button>
                        @endforeach
                    </div>

                    @if($tieneMasDeTresEv)
                        <button type="button" 
                                id="btn-ev-modulos-next"
                                onclick="desplazarModulosEv('right')" 
                                class="w-7 h-7 rounded-xl bg-white text-slate-500 hover:text-slate-900 hover:bg-slate-100 border border-slate-200/80 shadow-xs flex items-center justify-center transition-all active:scale-90 flex-shrink-0 cursor-pointer"
                                title="Ver más módulos"
                                aria-label="Ver más módulos">
                            <i class="fas fa-chevron-right text-[10px]"></i>
                        </button>
                    @endif
                </div>

                <input type="hidden" id="filtroModuloSelect" value="">

                <select id="filtroEstadoSelect" class="bg-slate-50 border border-slate-200 text-slate-700 font-medium text-sm rounded-2xl px-4 py-3 focus:ring-2 focus:ring-sena-green/50 outline-none w-full sm:w-auto cursor-pointer transition-all hover:bg-slate-100">
                    <option value="">Todos los estados</option>
                    <option value="aprobado">Aprobado</option>
                    <option value="disponible">Disponible</option>
                    <option value="agotado">Agotado</option>
                </select>
            </div>
        </div>

        <!-- 4. Catálogo de Evaluaciones -->
        <div class="space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-800 flex items-center gap-2.5">
                        <i class="fas fa-clipboard-check text-sena-green"></i>
                        <span>Catálogo del Proceso de Evaluación</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Cuestionarios de opción múltiple con cronómetro, intentos limitados y calificación instantánea</p>
                </div>
            </div>

            @if($evaluaciones->isEmpty())
                <!-- Estado vacío ilustrado exacto al diseño institucional -->
                <div class="py-16 px-6 text-center rounded-3xl bg-white border border-dashed border-slate-300 shadow-sm flex flex-col items-center justify-center">
                    <div class="w-20 h-20 rounded-3xl bg-slate-50 border border-slate-200 text-slate-400 flex items-center justify-center text-3xl mb-5 shadow-inner">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-800">Aún no hay evaluaciones creadas</h4>
                    <p class="text-sm text-slate-500 max-w-md mx-auto mt-2 leading-relaxed mb-6">
                        El sistema se encuentra en desarrollo inicial. Los instructores y administradores están configurando los quices y preguntas para evaluar cada módulo formativo.
                    </p>
                </div>
            @else
                <!-- Grid de Evaluaciones Dinámicas -->
                <div id="gridEvaluaciones" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($evaluaciones as $ev)
                        @php
                            $estadoFiltro = $ev->aprobado ? 'aprobado' : ($ev->intentos_restantes <= 0 ? 'agotado' : 'disponible');
                        @endphp
                        <div class="card-quiz bg-white rounded-2xl border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative group flex flex-col overflow-hidden h-full"
                             data-titulo="{{ strtolower($ev->titulo) }}"
                             data-modulo="{{ strtolower($ev->modulo->titulo ?? '') }}"
                             data-estado="{{ $estadoFiltro }}">

                            <!-- Cabecera de la Tarjeta (Dark Navy Gradient) -->
                            <div class="bg-gradient-to-r from-[#1A2B4C] to-[#243B66] p-5 text-white relative rounded-t-2xl">
                                <div class="flex items-center justify-between gap-3 mb-3">
                                    <div class="flex items-center gap-2.5">
                                        @if(!empty($ev->modulo->imagen))
                                            <img src="{{ asset($ev->modulo->imagen) }}" alt="{{ $ev->modulo->titulo ?? 'Módulo' }}" class="w-7 h-7 rounded-full object-cover border border-white/30 shadow-xs flex-shrink-0">
                                        @else
                                            <div class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center text-xs text-white border border-white/20 flex-shrink-0">
                                                <i class="fas fa-tasks"></i>
                                            </div>
                                        @endif
                                        <span class="text-[11px] font-bold text-white/90 bg-white/10 px-2.5 py-0.5 rounded-full border border-white/15 truncate max-w-[170px]" style="color: #ffffff !important;">
                                            {{ $ev->modulo->titulo ?? 'Módulo General' }}
                                        </span>
                                    </div>

                                    <!-- Estado -->
                                    @if($ev->aprobado)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-[#33FF57]/20 text-[#33FF57] border border-[#33FF57]/30 shadow-xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#33FF57]"></span>
                                            Aprobado
                                        </span>
                                    @elseif($ev->intentos_restantes <= 0)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30 shadow-xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                            Agotado
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-sky-500/20 text-sky-300 border border-sky-500/30 shadow-xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-sky-400"></span>
                                            Disponible
                                        </span>
                                    @endif
                                </div>

                                <h3 class="font-extrabold text-white text-lg tracking-tight leading-tight line-clamp-1" style="color: #ffffff !important;">
                                    {{ $ev->titulo }}
                                </h3>
                                <p class="text-xs text-slate-200 mt-1 line-clamp-1 font-medium flex items-center gap-1.5" style="color: #e2e8f0 !important;">
                                    <i class="fas fa-layer-group text-[10px] text-slate-300"></i>
                                    <span>{{ $ev->modulo->titulo ?? 'Evaluación de Conocimiento' }}</span>
                                </p>
                            </div>

                            <!-- Cuerpo de la Tarjeta con datos 100% reales -->
                            <div class="p-5 flex-1 flex flex-col justify-between bg-white rounded-b-2xl space-y-4">
                                <!-- Parámetros: Tiempo, Preguntas, Min % -->
                                <div class="grid grid-cols-3 gap-2 p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Tiempo</span>
                                        <span class="text-xs font-black text-slate-800 flex items-center justify-center gap-1 mt-0.5">
                                            <i class="far fa-clock text-amber-500"></i> {{ $ev->tiempo_minutos }}m
                                        </span>
                                    </div>
                                    <div class="flex flex-col items-center justify-center border-x border-slate-200">
                                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Preguntas</span>
                                        <span class="text-xs font-black text-slate-800 flex items-center justify-center gap-1 mt-0.5">
                                            <i class="fas fa-question text-sky-500"></i> {{ $ev->numero_preguntas }}
                                        </span>
                                    </div>
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Mínimo</span>
                                        <span class="text-xs font-black text-[#2E6F40] flex items-center justify-center gap-1 mt-0.5">
                                            <i class="fas fa-award text-[#2E6F40]"></i> {{ $ev->puntaje_minimo }}%
                                        </span>
                                    </div>
                                </div>

                                <!-- Control de Intentos en Tiempo Real -->
                                <div class="space-y-1.5">
                                    <div class="flex justify-between text-xs">
                                        <span class="text-slate-600 font-bold">Intentos utilizados</span>
                                        <span class="font-black text-slate-800">{{ $ev->intentos_realizados }} / {{ $ev->intentos_permitidos }}</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden shadow-inner">
                                        @php
                                            $porcentajeIntentos = $ev->intentos_permitidos > 0 ? min(100, ($ev->intentos_realizados / $ev->intentos_permitidos) * 100) : 0;
                                        @endphp
                                        <div class="h-2 rounded-full transition-all {{ $ev->aprobado ? 'bg-gradient-to-r from-[#2E6F40] to-[#33FF57]' : ($ev->intentos_restantes <= 0 ? 'bg-rose-500' : 'bg-amber-500') }}" style="width: {{ $porcentajeIntentos }}%"></div>
                                    </div>
                                    @if($ev->intentos_realizados > 0)
                                        <div class="flex justify-between text-[11px] pt-1 text-slate-500">
                                            <span>Mejor calificación:</span>
                                            <span class="font-black {{ $ev->aprobado ? 'text-[#2E6F40]' : 'text-slate-700' }}">{{ $ev->mejor_puntaje }}%</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Botón de Acción Principal -->
                                <div class="pt-3 border-t border-slate-100 mt-auto">
                                    @if($ev->aprobado)
                                        <button disabled 
                                                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full text-xs font-bold bg-emerald-50 text-[#2E6F40] border border-emerald-200 w-full cursor-not-allowed">
                                            <i class="fas fa-check-circle"></i>
                                            <span>Evaluación Superada</span>
                                        </button>
                                    @elseif($ev->intentos_restantes <= 0)
                                        <button disabled 
                                                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full text-xs font-bold bg-slate-100 text-slate-400 border border-slate-200 w-full cursor-not-allowed">
                                            <i class="fas fa-ban"></i>
                                            <span>Intentos Agotados</span>
                                        </button>
                                    @elseif($ev->numero_preguntas == 0)
                                        <button disabled 
                                                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full text-xs font-bold bg-slate-100 text-slate-400 border border-slate-200 w-full cursor-not-allowed">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <span>Sin Preguntas Configuradas</span>
                                        </button>
                                    @else
                                        <a href="{{ route('sisig.aprendiz.evaluaciones.iniciar', $ev->id_quiz) }}" 
                                           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full text-xs font-black text-[#1A2B4C] bg-[#33FF57] hover:bg-[#2ae049] shadow-md shadow-emerald-500/20 transition-all w-full group-hover:scale-[1.02]">
                                            <i class="fas fa-play text-xs"></i>
                                            <span>Iniciar Evaluación (Intento {{ $ev->intentos_realizados + 1 }})</span>
                                            <i class="fas fa-arrow-right text-[10px]"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

    <!-- Script de Filtro y Búsqueda en Tiempo Real -->
    <script>
        function seleccionarModuloEv(modulo, btn) {
            document.querySelectorAll('.btn-ev-modulo-pill').forEach(b => {
                b.className = 'btn-ev-modulo-pill px-2.5 py-1.5 rounded-xl font-bold transition-all flex items-center justify-center flex-shrink-0 whitespace-nowrap text-center truncate text-slate-600 hover:bg-slate-200/80 hover:text-slate-900';
            });
            btn.className = 'btn-ev-modulo-pill px-2.5 py-1.5 rounded-xl font-bold transition-all flex items-center justify-center flex-shrink-0 whitespace-nowrap text-center truncate bg-slate-900 text-white shadow-sm ring-1 ring-slate-900 is-active-ev';
            
            const hiddenInput = document.getElementById('filtroModuloSelect');
            if (hiddenInput) {
                hiddenInput.value = modulo;
                hiddenInput.dispatchEvent(new Event('change'));
            }
        }

        function desplazarModulosEv(direccion) {
            const contenedor = document.getElementById('contenedor-modulos-ev');
            if (!contenedor) return;
            const paso = Math.round(contenedor.clientWidth / 3) + 2;
            contenedor.scrollBy({
                left: direccion === 'left' ? -paso : paso,
                behavior: 'smooth'
            });
            setTimeout(verificarLimitesScrollEv, 320);
        }

        function verificarLimitesScrollEv() {
            const contenedor = document.getElementById('contenedor-modulos-ev');
            const btnPrev = document.getElementById('btn-ev-modulos-prev');
            const btnNext = document.getElementById('btn-ev-modulos-next');
            if (!contenedor) return;

            if (btnPrev) {
                const alInicio = contenedor.scrollLeft <= 5;
                btnPrev.style.opacity = alInicio ? '0.35' : '1';
                btnPrev.style.pointerEvents = alInicio ? 'none' : 'auto';
            }
            if (btnNext) {
                const alFinal = (contenedor.scrollLeft + contenedor.clientWidth) >= (contenedor.scrollWidth - 5);
                btnNext.style.opacity = alFinal ? '0.35' : '1';
                btnNext.style.pointerEvents = alFinal ? 'none' : 'auto';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('filtroQuizInput');
            const moduloSelect = document.getElementById('filtroModuloSelect');
            const estadoSelect = document.getElementById('filtroEstadoSelect');
            const cards = document.querySelectorAll('.card-quiz');
            const contenedorEv = document.getElementById('contenedor-modulos-ev');

            if (contenedorEv) {
                contenedorEv.addEventListener('scroll', verificarLimitesScrollEv);
                setTimeout(verificarLimitesScrollEv, 350);
            }

            function filtrar() {
                const query = (searchInput?.value || '').trim().toLowerCase();
                const modulo = (moduloSelect?.value || '').toLowerCase();
                const estado = (estadoSelect?.value || '').toLowerCase();

                cards.forEach(card => {
                    const cardTitulo = card.getAttribute('data-titulo') || '';
                    const cardModulo = card.getAttribute('data-modulo') || '';
                    const cardEstado = card.getAttribute('data-estado') || '';

                    const coincideQuery = !query || cardTitulo.includes(query) || cardModulo.includes(query);
                    const coincideModulo = !modulo || cardModulo.includes(modulo);
                    const coincideEstado = !estado || cardEstado === estado;

                    if (coincideQuery && coincideModulo && coincideEstado) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }

            if (searchInput) searchInput.addEventListener('input', filtrar);
            if (moduloSelect) moduloSelect.addEventListener('change', filtrar);
            if (estadoSelect) estadoSelect.addEventListener('change', filtrar);
        });
    </script>
</x-sisig::layouts.admin>
