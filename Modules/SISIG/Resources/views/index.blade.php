<x-sisig::layouts.master title="Sistema de Inducción SIG | SENA Empresa">

    {{-- Fondo blanco limpio y profesional en consonancia con la identidad SENA Empresa --}}
    <style>
        body {
            background-color: #FFFFFF !important;
            color: #1E293B !important;
        }

        /* Mockup de dispositivo móvil y desktop con sombras suaves sobre fondo blanco */
        .device-browser-frame-light {
            background: #0F172A;
            border: 1px solid #CBD5E1;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.18), 0 0 0 1px rgba(15, 23, 42, 0.05);
        }

        .device-mobile-frame-light {
            background: #090F1C;
            border: 3px solid #0F172A;
            box-shadow: 0 25px 40px -10px rgba(0, 0, 0, 0.35);
        }
    </style>

    <div class="space-y-20 pb-20 overflow-x-hidden">

        <!-- ========================================================================= -->
        <!-- 1. HERO SECTION CLEAN & MODERN (Estructura RealStack sobre Fondo Blanco)   -->
        <!-- ========================================================================= -->
        <section id="inicio" class="scroll-mt-28 relative overflow-hidden bg-gradient-to-b from-white via-slate-50/60 to-white border-b border-slate-200/80">
            
            <!-- Luces ambientales sutiles de fondo (Resplandores verdes institucionales) -->
            <div class="absolute -top-32 -left-32 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute top-1/4 right-0 w-[30rem] h-[30rem] bg-emerald-600/5 rounded-full blur-[120px] pointer-events-none"></div>
            <div class="absolute -bottom-24 left-1/3 w-80 h-80 bg-blue-500/5 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Patrón de malla muy sutil -->
            <div class="absolute inset-0 bg-[radial-gradient(#0f172a0a_1px,transparent_1px)] [background-size:28px_28px] pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 sm:pt-16 pb-16 relative z-10">
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-10 items-center">
                    
                    <!-- ===================================================== -->
                    <!-- Columna Izquierda: Tipografía Monumental & Acciones  -->
                    <!-- ===================================================== -->
                    <div class="lg:col-span-7 space-y-7">
                        
                        <!-- Kicker / Tagline Tecnológico en Verde Institucional -->
                        <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-emerald-50 border border-emerald-200/80 text-xs text-emerald-800 font-extrabold uppercase tracking-widest shadow-2xs">
                            <span class="w-2 h-2 rounded-full bg-[#39A900] animate-pulse"></span>
                            <span>Inducción Institucional &amp; Evaluación Digital</span>
                        </div>

                        <!-- Titular Principal de Gran Impacto (Estilo RealStack con Contraste en Blanco) -->
                        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight text-slate-900 leading-[1.08]">
                            Tu formación <br class="hidden sm:inline" />
                            <span class="text-slate-900">ya está lista.</span> <br />
                            <span class="text-[#39A900]">Que tu acreditación</span> <br class="hidden sm:inline" />
                            <span class="text-[#39A900]">también lo esté.</span>
                        </h1>

                        <!-- Descripción Clara & Profesional -->
                        <p class="text-slate-600 text-sm sm:text-base leading-relaxed max-w-2xl font-normal">
                            @if($modulos->isNotEmpty())
                                Capacítate y certifícate en los módulos oficiales de 
                                <strong class="text-slate-900 font-bold">{{ $modulos->pluck('titulo')->join(', ', ' y ') }}</strong>. 
                            @else
                                Capacítate y certifícate en los ejes formativos oficiales del Sistema Integrado de Gestión.
                            @endif
                            Evaluaciones supervisadas con protocolo anti-trampa y calificación equitativa sobre 100% para aprendices de 
                            <span class="text-[#39A900] font-bold">SENA Empresa CEFA</span>.
                        </p>

                        <!-- Botones de Acción Estilo RealStack (Pill Button Neón + Enlace Ghost) -->
                        <div class="flex flex-wrap items-center gap-4 pt-2">
                            <a href="#catalogo-modulos" 
                               class="inline-flex items-center gap-3 px-8 py-4 rounded-full bg-[#33FF57] hover:bg-[#2ae049] text-[#1A2B4C] font-black text-sm tracking-wide shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 hover:scale-[1.02] transition-all duration-300 cursor-pointer">
                                <span>Iniciar Inducción</span>
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>

                            <a href="#ruta-aprendizaje" 
                               class="inline-flex items-center gap-2 px-6 py-4 rounded-full text-slate-700 hover:text-slate-900 font-bold text-sm hover:underline transition-all group">
                                <span>Cómo funciona</span>
                                <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                            </a>

                            @if(auth()->check())
                                @if(auth()->user()->hasRole('admin_sisig') || auth()->user()->hasRole('sisig.admin') || auth()->user()->hasRole('superadmin'))
                                    <a href="{{ route('sisig.admin.dashboard') }}" 
                                       class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold border border-slate-200 transition-all">
                                        <i class="fas fa-chart-line text-[#39A900]"></i>
                                        <span>Panel Admin</span>
                                    </a>
                                @elseif(auth()->user()->hasRole('editor_sisig'))
                                    <a href="{{ route('sisig.editor.dashboard') }}" 
                                       class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold border border-slate-200 transition-all">
                                        <i class="fas fa-edit text-[#39A900]"></i>
                                        <span>Panel Editor</span>
                                    </a>
                                @else
                                    <a href="{{ route('sisig.aprendiz.dashboard') }}" 
                                       class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold border border-slate-200 transition-all">
                                        <i class="fas fa-graduation-cap text-[#39A900]"></i>
                                        <span>Mi Dashboard</span>
                                    </a>
                                @endif
                            @endif
                        </div>

                        <!-- Microcopy institucional inferior -->
                        <div class="pt-1 text-xs text-slate-500 font-medium flex items-center gap-2">
                            <i class="fas fa-check-circle text-[#39A900]"></i>
                            <span>Acceso oficial para aprendices e instructores con matrícula activa en CEFA.</span>
                        </div>

                    </div>


                    <!-- ================================================================= -->
                    <!-- Columna Derecha: Dual Mockup Responsivo (Desktop + Móvil + SLA)   -->
                    <!-- ================================================================= -->
                    <div class="lg:col-span-5 relative flex flex-col items-center">
                        
                        <div class="relative w-full max-w-lg">
                            
                            <!-- 1. Marco Desktop (Ventana de Navegador de Alto Contraste) -->
                            <div class="device-browser-frame-light rounded-2xl overflow-hidden relative z-10 transition-transform duration-500 hover:scale-[1.01]">
                                
                                <!-- Barra Superior de Navegador con 3 botones de control -->
                                <div class="px-4 py-2.5 bg-[#0A101D] border-b border-slate-800 flex items-center justify-between">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500/90"></span>
                                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500/90"></span>
                                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/90"></span>
                                    </div>
                                    <div class="px-3 py-0.5 rounded-md bg-white/10 border border-white/10 text-[10px] text-slate-300 font-mono flex items-center gap-1.5">
                                        <i class="fas fa-lock text-[9px] text-[#33FF57]"></i>
                                        <span>sisig.senaempresa.edu.co</span>
                                    </div>
                                    <div class="w-10"></div>
                                </div>

                                <!-- Contenido de Pantalla Desktop Simulado -->
                                <div class="p-4 sm:p-5 bg-gradient-to-b from-[#0D182E] to-[#0A1224] space-y-4 text-white">
                                    
                                    <!-- Banner Superior del Módulo Destacado en el Desktop -->
                                    <div class="rounded-xl bg-gradient-to-r from-[#142342] to-[#0E1A33] border border-white/10 p-3.5 flex items-center justify-between gap-3">
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2">
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#33FF57]"></span>
                                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#33FF57]">
                                                    {{ $moduloDestacado->norma ?? 'SST · ISO 45001' }}
                                                </span>
                                            </div>
                                            <h4 class="text-sm font-black text-white leading-tight">
                                                {{ $moduloDestacado->titulo ?? 'Seguridad y Salud en el Trabajo' }}
                                            </h4>
                                            <p class="text-[11px] text-slate-400 line-clamp-1">
                                                {{ $moduloDestacado->descripcion ?? 'Protocolos de prevención, brigadas y bienestar en CEFA.' }}
                                            </p>
                                        </div>

                                        @if(!empty($moduloDestacado))
                                            <a href="{{ route('sisig.aprendiz.induccion.show', $moduloDestacado->id) }}" 
                                               class="px-3 py-1.5 rounded-lg bg-[#33FF57] text-[#0A1322] text-[11px] font-black flex-shrink-0 hover:scale-105 transition-all">
                                                <span>Ver Módulo</span>
                                            </a>
                                        @endif
                                    </div>

                                    <!-- Mini Grid de Módulos dentro del Mockup Desktop (Dinámico con los módulos reales) -->
                                    @php
                                        $modulosSecundarios = $modulos->skip(1)->take(2);
                                    @endphp
                                    @if($modulosSecundarios->isNotEmpty())
                                        <div class="grid grid-cols-2 gap-2.5">
                                            @foreach($modulosSecundarios as $idxSec => $mSec)
                                                <div class="bg-white/5 border border-white/10 rounded-xl p-2.5 space-y-1.5">
                                                    <div class="flex items-center justify-between">
                                                        <span class="text-[9px] font-bold text-slate-400 uppercase truncate max-w-[85px]">
                                                            {{ !empty($mSec->norma) ? $mSec->norma : 'Eje SIG' }}
                                                        </span>
                                                        <span class="text-[9px] text-[#33FF57] font-mono">100%</span>
                                                    </div>
                                                    <div class="text-xs font-bold text-white truncate" title="{{ $mSec->titulo }}">
                                                        {{ $mSec->titulo }}
                                                    </div>
                                                    <div class="w-full bg-white/10 h-1 rounded-full overflow-hidden">
                                                        <div class="bg-[#33FF57] h-full" style="width: {{ $idxSec === 0 ? '75%' : '60%' }}"></div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                </div>

                            </div>

                            <!-- 2. Marco Móvil Superpuesto (Smartphone Foreground Frame) -->
                            <div class="device-mobile-frame-light hidden sm:block absolute -right-4 -bottom-10 w-44 rounded-3xl overflow-hidden z-20 transition-transform duration-500 hover:scale-105">
                                
                                <!-- Isla Dinámica / Altavoz superior del móvil -->
                                <div class="h-5 bg-[#070C16] flex items-center justify-center pt-1">
                                    <div class="w-12 h-2 rounded-full bg-slate-800"></div>
                                </div>

                                <!-- Pantalla del Smartphone -->
                                <div class="p-3 bg-gradient-to-b from-[#0D182E] to-[#0A1224] space-y-2.5 text-white">
                                    
                                    <div class="flex items-center justify-between border-b border-white/10 pb-1.5">
                                        <span class="text-[9px] font-black text-[#33FF57]">SISIG Móvil</span>
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#33FF57] animate-pulse"></span>
                                    </div>

                                    <div class="space-y-1">
                                        <div class="text-[10px] font-extrabold text-white leading-tight">Evaluación SST</div>
                                        <div class="text-[8px] text-slate-400">Pregunta 1 de 5</div>
                                    </div>

                                    <!-- Pregunta Simulada en Móvil -->
                                    <div class="bg-white/5 border border-white/10 rounded-lg p-2 space-y-1">
                                        <div class="text-[8px] text-slate-300">¿Qué significa EPP?</div>
                                        <div class="px-2 py-1 rounded bg-[#33FF57]/20 border border-[#33FF57]/40 text-[8px] font-bold text-[#33FF57]">
                                            ✓ Equipos Protección
                                        </div>
                                    </div>

                                    <div class="pt-1 flex items-center justify-between text-[8px] text-slate-400">
                                        <span>3 Vidas Seguras</span>
                                        <span class="text-emerald-400 font-bold">100% Pts</span>
                                    </div>

                                </div>

                                <!-- Barra Inferior de Home del móvil -->
                                <div class="h-3 bg-[#070C16] flex items-center justify-center">
                                    <div class="w-10 h-0.5 rounded-full bg-slate-700"></div>
                                </div>
                            </div>

                        </div>

                        <!-- 3. Tarjeta Flotante Horizontal (Estilo SLA "Hasta 72 h hábiles" de RealStack) -->
                        <div class="w-full max-w-xl mt-8 sm:mt-12 relative z-30">
                            <div class="rounded-2xl bg-white border border-slate-200/90 p-4 sm:p-5 shadow-xl space-y-3">
                                
                                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-route text-[#39A900] text-xs"></i>
                                        <span class="text-xs font-black text-slate-900 tracking-wide">Ruta Oficial de Acreditación SIG</span>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                        3 Etapas Clave
                                    </span>
                                </div>

                                <!-- 3 Pasos Conectados Horizontalmente -->
                                <div class="grid grid-cols-3 gap-2 sm:gap-4 text-center items-center">
                                    
                                    <!-- Paso 1 -->
                                    <div class="space-y-1">
                                        <div class="w-8 h-8 mx-auto rounded-xl bg-emerald-50 border border-emerald-200 text-[#39A900] flex items-center justify-center text-xs">
                                            <i class="fas fa-book-open"></i>
                                        </div>
                                        <div class="text-[11px] font-bold text-slate-800 truncate">1. Contenidos</div>
                                        <div class="text-[9px] text-slate-500">Estudio interactivo</div>
                                    </div>

                                    <!-- Paso 2 -->
                                    <div class="space-y-1 relative">
                                        <div class="w-8 h-8 mx-auto rounded-xl bg-blue-50 border border-blue-200 text-blue-600 flex items-center justify-center text-xs">
                                            <i class="fas fa-shield-alt"></i>
                                        </div>
                                        <div class="text-[11px] font-bold text-slate-800 truncate">2. Evaluación</div>
                                        <div class="text-[9px] text-slate-500">Anti-trampa 3 vidas</div>
                                    </div>

                                    <!-- Paso 3 -->
                                    <div class="space-y-1">
                                        <div class="w-8 h-8 mx-auto rounded-xl bg-emerald-100 border border-emerald-300 text-emerald-800 flex items-center justify-center text-xs">
                                            <i class="fas fa-rocket"></i>
                                        </div>
                                        <div class="text-[11px] font-bold text-emerald-700 truncate">3. Acreditación</div>
                                        <div class="text-[9px] text-slate-500">Aprobación SENA</div>
                                    </div>

                                </div>

                            </div>

                            <p class="text-center text-[11px] text-slate-500 mt-2.5">
                                Vista interactiva optimizada para computador, tableta y teléfono móvil.
                            </p>
                        </div>

                    </div>

                </div>

            </div>
        </section>



        <!-- ========================================================================= -->
        <!-- 2. TRUST BAR / 4-ITEM FEATURE STRIP (Estilo RealStack con Fondo Blanco)    -->
        <!-- ========================================================================= -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 sm:-mt-10 relative z-30">
            <div class="rounded-2xl bg-white border border-slate-200/90 shadow-xl p-4 sm:p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6">
                    
                    <!-- Item 1 -->
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-[#39A900] text-base flex-shrink-0">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <div>
                            <span class="block text-sm font-bold text-slate-900">Calificación Equitativa</span>
                            <span class="block text-xs text-slate-500">Ponderación 100% automática</span>
                        </div>
                    </div>

                    <!-- Item 2 -->
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-[#39A900] text-base flex-shrink-0">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <span class="block text-sm font-bold text-slate-900">Protocolo Anti-Trampa</span>
                            <span class="block text-xs text-slate-500">3 vidas y control de pestañas</span>
                        </div>
                    </div>

                    <!-- Item 3 -->
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-[#39A900] text-base flex-shrink-0">
                            <i class="fas fa-award"></i>
                        </div>
                        <div>
                            <span class="block text-sm font-bold text-slate-900">Acreditación Oficial</span>
                            <span class="block text-xs text-slate-500">Habilitación para SENA Empresa</span>
                        </div>
                    </div>

                    <!-- Item 4 -->
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-[#39A900] text-base flex-shrink-0">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div>
                            <span class="block text-sm font-bold text-slate-900">Trazabilidad Total</span>
                            <span class="block text-xs text-slate-500">Auditoría detallada de respuestas</span>
                        </div>
                    </div>

                </div>
            </div>
        </section>



        <!-- ========================================================================= -->
        <!-- 3. CATÁLOGO DE MÓDULOS TEMÁTICOS (Diseño Limpio, Moderno y Atractivo)     -->
        <!-- ========================================================================= -->
        <section id="catalogo-modulos" class="scroll-mt-28 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Encabezado del Catálogo con Buscador Rápido -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-6 border-b border-slate-200">
                <div class="space-y-2">
                    <span class="text-xs font-black text-[#39A900] uppercase tracking-widest flex items-center gap-2">
                        <i class="fas fa-layer-group text-xs"></i>
                        <span>Catálogo Oficial de Formación</span>
                    </span>
                    <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                        Módulos Temáticos del SIG
                    </h2>
                    <p class="text-sm text-slate-500 max-w-2xl leading-relaxed">
                        Explora los contenidos multimedia y evaluaciones organizadas por ejes normativos de SENA Empresa CEFA.
                    </p>
                </div>

                <!-- Buscador Rápido en Tiempo Real -->
                <div class="relative w-full md:w-80">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" 
                           id="buscador-modulos-catalogo" 
                           oninput="filtrarModulosCatalogo()" 
                           placeholder="Buscar módulo o norma..." 
                           class="w-full pl-10 pr-4 py-3 rounded-full bg-white border border-slate-200 text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#39A900] focus:ring-2 focus:ring-emerald-500/20 shadow-xs transition-all">
                </div>
            </div>

            <!-- Filtros de Categoría (Pill Buttons) -->
            <div class="flex items-center gap-2.5 overflow-x-auto pb-2 scrollbar-none">
                <button type="button" 
                        onclick="filtrarPorCategoria('todos', this)" 
                        class="filtro-btn px-5 py-2.5 rounded-full text-xs font-black transition-all bg-[#1A2B4C] text-white shadow-sm flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-th-large text-[10px]"></i>
                    <span>Todos</span>
                    <span class="px-2 py-0.5 rounded-full bg-white/20 text-[10px]">{{ $modulos->count() }}</span>
                </button>

                <button type="button" 
                        onclick="filtrarPorCategoria('sst', this)" 
                        class="filtro-btn px-5 py-2.5 rounded-full text-xs font-bold transition-all bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 shadow-2xs flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-shield-alt text-[#39A900] text-[10px]"></i>
                    <span>SST (ISO 45001)</span>
                </button>

                <button type="button" 
                        onclick="filtrarPorCategoria('calidad', this)" 
                        class="filtro-btn px-5 py-2.5 rounded-full text-xs font-bold transition-all bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 shadow-2xs flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-award text-blue-600 text-[10px]"></i>
                    <span>Calidad (ISO 9001)</span>
                </button>

                <button type="button" 
                        onclick="filtrarPorCategoria('ambiental', this)" 
                        class="filtro-btn px-5 py-2.5 rounded-full text-xs font-bold transition-all bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 shadow-2xs flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-leaf text-emerald-600 text-[10px]"></i>
                    <span>Ambiental (ISO 14001)</span>
                </button>
            </div>

            <!-- Cuadrícula de Tarjetas de Módulos (Cards Limpias con Portada de Alto Contraste) -->
            <div id="contenedor-modulos-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
                @forelse($modulos as $mod)
                    @php
                        $normaLower = mb_strtolower($mod->norma ?? '');
                        $tituloLower = mb_strtolower($mod->titulo ?? '');
                        $categoriaTag = 'general';
                        if (str_contains($normaLower, '45001') || str_contains($tituloLower, 'sst') || str_contains($tituloLower, 'seguridad')) {
                            $categoriaTag = 'sst';
                        } elseif (str_contains($normaLower, '9001') || str_contains($tituloLower, 'calidad')) {
                            $categoriaTag = 'calidad';
                        } elseif (str_contains($normaLower, '14001') || str_contains($tituloLower, 'ambiental') || str_contains($tituloLower, 'medio')) {
                            $categoriaTag = 'ambiental';
                        }
                        $quicesCount = $mod->quices ? $mod->quices->where('activo', 1)->count() : 0;
                    @endphp

                    <div class="tarjeta-modulo-item bg-white border border-slate-200/90 hover:border-emerald-500/60 rounded-3xl overflow-hidden shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col group"
                         data-categoria="{{ $categoriaTag }}"
                         data-busqueda="{{ $tituloLower }} {{ $normaLower }} {{ mb_strtolower($mod->descripcion ?? '') }}">
                        
                        <!-- Encabezado Visual de Tarjeta / Imagen con Overlay -->
                        <div class="h-48 w-full bg-gradient-to-br from-[#1A2B4C] via-[#1E335A] to-[#14233C] flex items-center justify-center relative overflow-hidden flex-shrink-0">
                            
                            <!-- Badges Superiores Flotantes -->
                            <div class="absolute top-4 left-4 z-10">
                                <span class="bg-[#1A2B4C]/90 backdrop-blur-md text-white border border-white/20 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider">
                                    {{ !empty($mod->norma) ? $mod->norma : 'Eje SIG' }}
                                </span>
                            </div>

                            <div class="absolute top-4 right-4 z-10">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-white/10 backdrop-blur-md text-white border border-white/20">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $mod->estado === 'publicado' ? 'bg-[#33FF57]' : 'bg-amber-400' }}"></span>
                                    <span class="capitalize">{{ $mod->estado ?? 'Publicado' }}</span>
                                </span>
                            </div>

                            <!-- Contenedor Central de Imagen o Ícono -->
                            <div class="w-20 h-20 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center text-white overflow-hidden shadow-xl group-hover:scale-110 transition-transform duration-300 z-10">
                                @if(!empty($mod->imagen) && file_exists(public_path($mod->imagen)))
                                    <img src="{{ asset($mod->imagen) }}" 
                                         alt="{{ $mod->titulo }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <i class="{{ !empty($mod->icon_class) ? $mod->icon_class : 'fas fa-layer-group' }} text-3xl text-[#33FF57]"></i>
                                @endif
                            </div>

                            <!-- Resplandor sutil interior -->
                            <div class="absolute -bottom-10 -right-10 w-32 h-32 rounded-full bg-emerald-500/20 blur-xl pointer-events-none"></div>
                        </div>

                        <!-- Cuerpo de la Tarjeta -->
                        <div class="p-6 flex-1 flex flex-col justify-between space-y-5">
                            
                            <div class="space-y-2">
                                <h3 class="font-black text-slate-900 text-lg tracking-tight leading-snug group-hover:text-emerald-700 transition-colors">
                                    {{ $mod->titulo }}
                                </h3>
                                <p class="text-xs text-slate-500 leading-relaxed font-normal line-clamp-3">
                                    {{ !empty($mod->descripcion) ? $mod->descripcion : 'Contenido formativo, manuales y evaluación oficial del Sistema Integrado de Gestión para aprendices en etapa productiva.' }}
                                </p>
                            </div>

                            <!-- Fila de Chips Rápidos -->
                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                                <span class="flex items-center gap-1.5 font-bold text-slate-700">
                                    <i class="fas fa-tasks text-emerald-600"></i>
                                    <span>{{ $quicesCount }} {{ $quicesCount === 1 ? 'Examen' : 'Exámenes' }}</span>
                                </span>
                                <span class="flex items-center gap-1.5 font-bold text-slate-700">
                                    <i class="fas fa-shield-alt text-amber-500"></i>
                                    <span>Anti-Trampa</span>
                                </span>
                                <span class="flex items-center gap-1.5 font-bold text-slate-700">
                                    <i class="fas fa-calculator text-blue-500"></i>
                                    <span>Base 100%</span>
                                </span>
                            </div>

                            <!-- Botón de Entrada al Módulo -->
                            <div class="pt-2">
                                <a href="{{ route('sisig.aprendiz.induccion.show', $mod->id) }}" 
                                   class="w-full py-3.5 px-4 rounded-full bg-[#33FF57] hover:bg-[#2ae049] text-[#1A2B4C] text-xs font-black shadow-md shadow-emerald-500/20 hover:shadow-emerald-500/35 transition-all duration-300 flex items-center justify-center gap-2 cursor-pointer group/btn">
                                    <span>Iniciar Inducción</span>
                                    <i class="fas fa-arrow-right text-[11px] group-hover/btn:translate-x-1 transition-transform"></i>
                                </a>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-slate-200">
                        <i class="fas fa-layer-group text-4xl text-slate-400 mb-3 block"></i>
                        <h4 class="text-base font-bold text-slate-700">Aún no hay módulos publicados</h4>
                        <p class="text-xs text-slate-400 mt-1">Los módulos registrados en la plataforma aparecerán aquí automáticamente.</p>
                    </div>
                @endforelse
            </div>

            <!-- Estado Vacío cuando no coincida la búsqueda -->
            <div id="sin-resultados-catalogo" class="hidden bg-white rounded-3xl p-12 text-center border border-slate-200">
                <i class="fas fa-search text-3xl text-slate-400 mb-3 block"></i>
                <h4 class="text-base font-bold text-slate-700">No se encontraron módulos</h4>
                <p class="text-xs text-slate-400 mt-1">Prueba con otra palabra clave o selecciona otra categoría arriba.</p>
            </div>

        </section>



        <!-- ========================================================================= -->
        <!-- 4. RUTA DE APRENDIZAJE PASO A PASO (Diseño Limpio en Fondo Blanco)        -->
        <!-- ========================================================================= -->
        <section id="ruta-aprendizaje" class="scroll-mt-28 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <div class="text-center max-w-2xl mx-auto space-y-2">
                <span class="text-xs font-black text-[#39A900] uppercase tracking-widest">Metodología de Acreditación</span>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    ¿Cómo funciona el proceso en SISIG?
                </h2>
                <p class="text-sm text-slate-500 leading-relaxed">
                    Un recorrido estructurado y supervisado para garantizar que cada aprendiz domine los lineamientos de SENA Empresa.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Paso 1 -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-xs hover:shadow-lg transition-all space-y-4 relative group flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 font-black text-base flex items-center justify-center border border-emerald-200 group-hover:scale-105 transition-transform">
                            01
                        </div>
                        <h3 class="text-base font-bold text-slate-900">1. Explora el Catálogo</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            @if($modulos->isNotEmpty())
                                Accede a los módulos formativos de 
                                <strong class="text-slate-800 font-semibold">{{ $modulos->pluck('titulo')->join(', ', ' y ') }}</strong> 
                                asignados a tu programa o etapa de inducción.
                            @else
                                Accede a los módulos formativos institucionales asignados a tu programa o etapa de inducción.
                            @endif
                        </p>
                    </div>

                    @if($modulos->isNotEmpty())
                        <!-- Badges dinámicos de los módulos creados en la plataforma -->
                        <div class="flex flex-wrap gap-1.5 pt-3 border-t border-slate-100">
                            @foreach($modulos as $mItem)
                                <a href="#catalogo-modulos" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200/80 text-[11px] font-bold transition-colors">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#39A900]"></span>
                                    <span>{{ $mItem->titulo }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Paso 2 -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-xs hover:shadow-lg transition-all space-y-4 relative group">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 font-black text-base flex items-center justify-center border border-blue-200 group-hover:scale-105 transition-transform">
                        02
                    </div>
                    <h3 class="text-base font-bold text-slate-900">2. Estudia la Normativa</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Revisa infografías, manuales interactivos y directrices institucionales preparadas por los instructores y editores.
                    </p>
                </div>

                <!-- Paso 3 -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-xs hover:shadow-lg transition-all space-y-4 relative group">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 font-black text-base flex items-center justify-center border border-amber-200 group-hover:scale-105 transition-transform">
                        03
                    </div>
                    <h3 class="text-base font-bold text-slate-900">3. Presenta la Evaluación</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Demuestra tus conocimientos en pantalla completa bajo el protocolo seguro anti-trampa con calificación equitativa sobre 100%.
                    </p>
                </div>

                <!-- Paso 4 -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-xs hover:shadow-lg transition-all space-y-4 relative group">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-800 font-black text-base flex items-center justify-center border border-emerald-300 group-hover:scale-105 transition-transform">
                        04
                    </div>
                    <h3 class="text-base font-bold text-slate-900">4. Certificación y Etapa</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Al superar el puntaje mínimo de aprobación (70%), el sistema registra tu estado de acreditación para SENA Empresa.
                    </p>
                </div>

            </div>

        </section>



        <!-- ========================================================================= -->
        <!-- 5. SUBMÓDULOS Y OPERACIONES EN SISIG (Capacidades del Sistema)             -->
        <!-- ========================================================================= -->
        <section id="submodulos" class="scroll-mt-28 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <div class="border-b border-slate-200 pb-5 flex items-center justify-between flex-wrap gap-4">
                <div>
                    <span class="text-xs font-black text-[#39A900] uppercase tracking-widest flex items-center gap-1.5">
                        <i class="fas fa-cogs"></i>
                        <span>Ecosistema de Herramientas</span>
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-1">
                        Submódulos y Operaciones en SISIG
                    </h2>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 max-w-md text-left sm:text-right">
                    Diseñado para conectar a administradores, editores de contenidos y aprendices en un entorno digital fluido.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Card 1: Formación y Contenidos -->
                <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all flex flex-col justify-between space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-[#39A900] flex items-center justify-center text-xl border border-emerald-100">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div class="space-y-1.5">
                        <h4 class="font-extrabold text-slate-900 text-base">Formación y Lienzo</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Editor interactivo de contenidos formativos, material audiovisual y guías para los ejes temáticos del SIG.
                        </p>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-500">
                        <span>Lienzo Multimedia</span>
                        <span class="text-emerald-700">Formativo</span>
                    </div>
                </div>

                <!-- Card 2: Quices y Preguntas -->
                <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all flex flex-col justify-between space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl border border-blue-100">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="space-y-1.5">
                        <h4 class="font-extrabold text-slate-900 text-base">Evaluaciones y Quices</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            6 tipos de preguntas interactivas con calificación equitativa sobre 100% y bloqueo automático de integridad.
                        </p>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-500">
                        <span>Ponderación 100%</span>
                        <span class="text-blue-600">Reactivos</span>
                    </div>
                </div>

                <!-- Card 3: Auditoría Anti-Trampa -->
                <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all flex flex-col justify-between space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl border border-rose-100">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="space-y-1.5">
                        <h4 class="font-extrabold text-slate-900 text-base">Auditoría Anti-Trampa</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Sistema de 3 vidas, supervisión de cambio de pestañas, anulación automática y revisión detallada pregunta por pregunta.
                        </p>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-500">
                        <span>3 Vidas Seguras</span>
                        <span class="text-rose-600">Integridad</span>
                    </div>
                </div>

                <!-- Card 4: Reportes y Métricas -->
                <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all flex flex-col justify-between space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl border border-amber-100">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div class="space-y-1.5">
                        <h4 class="font-extrabold text-slate-900 text-base">Reportes y Métricas</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Gráficas Donut interactivas, seguimiento por ficha, tasa de aprobación y exportación en tiempo real.
                        </p>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-500">
                        <span>Trazabilidad</span>
                        <span class="text-amber-600">Estadísticas</span>
                    </div>
                </div>

            </div>

        </section>



        <!-- ========================================================================= -->
        <!-- 6. CALL TO ACTION FINAL (Contenedor Azul Marino Institucional de Alto Impacto) -->
        <!-- ========================================================================= -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#0E1A33] via-[#142646] to-[#0E1A33] border border-slate-800 p-8 sm:p-14 text-white shadow-2xl">
                
                <div class="absolute -top-20 -right-20 w-80 h-80 bg-[#33FF57]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8 text-center md:text-left">
                    <div class="space-y-3 max-w-xl">
                        <span class="text-xs font-black text-[#33FF57] uppercase tracking-widest block">
                            Preparación para SENA Empresa
                        </span>
                        <h3 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight leading-snug">
                            ¿Listo para iniciar tu proceso de inducción?
                        </h3>
                        <p class="text-slate-300 text-xs sm:text-sm leading-relaxed">
                            Ingresa al catálogo, estudia los ejes normativos y realiza tus evaluaciones formativas desde cualquier dispositivo.
                        </p>
                    </div>

                    <div class="flex items-center gap-4 flex-wrap justify-center">
                        <a href="#catalogo-modulos" 
                           class="px-8 py-4 rounded-full bg-[#33FF57] hover:bg-[#2adb4e] text-[#0A1322] text-sm font-black shadow-lg shadow-[#33FF57]/25 transition-all transform hover:scale-105 cursor-pointer">
                            <i class="fas fa-play text-xs mr-1.5"></i>
                            <span>Comenzar Ahora</span>
                        </a>
                        <a href="{{ route('home') }}" 
                           class="px-7 py-4 rounded-full bg-white/10 hover:bg-white/20 text-white text-sm font-bold border border-white/20 transition-all backdrop-blur-md">
                            <span>Volver al ERP</span>
                        </a>
                    </div>
                </div>

            </div>
        </section>

    </div>



    <!-- ========================================================================= -->
    <!-- 7. FLOATING BADGE (Inspirado en el botón "RealStack AI •")                  -->
    <!-- ========================================================================= -->
    <div class="fixed bottom-6 right-6 z-40">
        <a href="{{ route('sisig.desarrolladores') }}" 
           class="inline-flex items-center gap-2.5 px-4 py-2.5 rounded-full bg-white/95 border border-slate-200 text-slate-800 hover:text-slate-900 hover:border-emerald-500 shadow-xl backdrop-blur-md transition-all hover:scale-105 group"
           title="Ver equipo de desarrollo de SISIG">
            <span class="w-2.5 h-2.5 rounded-full bg-[#39A900] animate-pulse"></span>
            <span class="text-xs font-black tracking-wide text-[#39A900]">SISIG</span>
            <span class="text-xs text-slate-500 font-semibold group-hover:text-slate-800">· SENA Empresa</span>
            <i class="fas fa-chevron-right text-[10px] text-slate-400 group-hover:text-[#39A900] transition-transform"></i>
        </a>
    </div>



    <!-- ========================================================================= -->
    <!-- JAVASCRIPT DEL CATÁLOGO EN TIEMPO REAL (Filtro por Categorías y Búsqueda) -->
    <!-- ========================================================================= -->
    <script>
        let categoriaActiva = 'todos';

        function filtrarPorCategoria(categoria, btn) {
            categoriaActiva = categoria;

            // Actualizar estilo visual de los botones de filtro sobre fondo blanco
            document.querySelectorAll('.filtro-btn').forEach(b => {
                b.className = 'filtro-btn px-5 py-2.5 rounded-full text-xs font-bold transition-all bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 shadow-2xs flex items-center gap-2 cursor-pointer';
            });

            if (btn) {
                btn.className = 'filtro-btn px-5 py-2.5 rounded-full text-xs font-black transition-all bg-[#1A2B4C] text-white shadow-sm flex items-center gap-2 cursor-pointer';
            }

            aplicarFiltrosCatalogo();
        }

        function filtrarModulosCatalogo() {
            aplicarFiltrosCatalogo();
        }

        function aplicarFiltrosCatalogo() {
            const query = (document.getElementById('buscador-modulos-catalogo')?.value || '').trim().toLowerCase();
            const tarjetas = document.querySelectorAll('.tarjeta-modulo-item');
            const sinResultados = document.getElementById('sin-resultados-catalogo');
            let visibles = 0;

            tarjetas.forEach(card => {
                const cat = card.dataset.categoria || '';
                const searchData = card.dataset.busqueda || '';

                const coincideCat = (categoriaActiva === 'todos' || cat === categoriaActiva);
                const coincideQuery = (query === '' || searchData.includes(query));

                if (coincideCat && coincideQuery) {
                    card.style.display = 'flex';
                    visibles++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (sinResultados) {
                if (visibles === 0 && tarjetas.length > 0) {
                    sinResultados.classList.remove('hidden');
                } else {
                    sinResultados.classList.add('hidden');
                }
            }
        }
    </script>
</x-sisig::layouts.master>