<x-sisig::layouts.master title="Sistema de Inducción SIG | SENA Empresa">

    {{-- Forzar fondo oscuro inmersivo en todo el body para eliminar cualquier fondo blanco genérico --}}
    <style>
        body {
            background-color: #070C16 !important;
            color: #F1F5F9 !important;
        }

        /* Suavizado de bordes y resplandores tecnológicos */
        .tech-glow-green {
            box-shadow: 0 0 50px -10px rgba(51, 255, 87, 0.25);
        }

        .tech-glow-navy {
            box-shadow: 0 0 60px -15px rgba(26, 43, 76, 0.6);
        }

        /* Mockup de dispositivo móvil y desktop */
        .device-browser-frame {
            background: #0B1426;
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .device-mobile-frame {
            background: #090F1C;
            border: 2px solid rgba(255, 255, 255, 0.16);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.8), 0 0 30px -5px rgba(51, 255, 87, 0.15);
        }
    </style>

    <div class="space-y-20 pb-20 overflow-x-hidden">

        <!-- ========================================================================= -->
        <!-- 1. HERO SECTION DARK TECH (Inspirado fielmente en RealStack)              -->
        <!-- ========================================================================= -->
        <section id="inicio" class="scroll-mt-28 relative overflow-hidden bg-gradient-to-b from-[#070C16] via-[#091122] to-[#070C16] border-b border-white/10 text-white">
            
            <!-- Resplandores ambientales neon / navy (Ambient Backdrops) -->
            <div class="absolute -top-40 -left-40 w-[30rem] h-[30rem] bg-[#33FF57]/15 rounded-full blur-[140px] pointer-events-none"></div>
            <div class="absolute top-1/3 right-0 w-[36rem] h-[36rem] bg-emerald-600/10 rounded-full blur-[150px] pointer-events-none"></div>
            <div class="absolute -bottom-32 left-1/3 w-96 h-96 bg-blue-600/10 rounded-full blur-[120px] pointer-events-none"></div>

            <!-- Patrón de rejilla sutil Tech -->
            <div class="absolute inset-0 bg-[radial-gradient(#ffffff0d_1px,transparent_1px)] [background-size:28px_28px] pointer-events-none opacity-50"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 sm:pt-16 pb-16 relative z-10">
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-10 items-center">
                    
                    <!-- ===================================================== -->
                    <!-- Columna Izquierda: Tipografía Monumental & Acciones  -->
                    <!-- ===================================================== -->
                    <div class="lg:col-span-7 space-y-7">
                        
                        <!-- Kicker / Tagline tecnológico -->
                        <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-[#0D192E] border border-[#33FF57]/40 text-xs text-[#33FF57] font-black uppercase tracking-widest backdrop-blur-md shadow-xs">
                            <span class="w-2 h-2 rounded-full bg-[#33FF57] animate-pulse"></span>
                            <span>Inducción Institucional &amp; Evaluación Digital</span>
                        </div>

                        <!-- Titular Principal de Gran Impacto (Estilo RealStack) -->
                        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight text-white leading-[1.08]">
                            Tu formación <br class="hidden sm:inline" />
                            <span class="text-white">ya está lista.</span> <br />
                            <span class="text-[#33FF57]">Que tu acreditación</span> <br class="hidden sm:inline" />
                            <span class="text-[#33FF57]">también lo esté.</span>
                        </h1>

                        <!-- Descripción Clara & Profesional -->
                        <p class="text-slate-300 text-sm sm:text-base leading-relaxed max-w-2xl font-normal">
                            Capacítate y certifícate en 
                            <strong class="text-white font-semibold">Seguridad y Salud (ISO 45001)</strong>, 
                            <strong class="text-white font-semibold">Gestión de la Calidad (ISO 9001)</strong> y 
                            <strong class="text-white font-semibold">Gestión Ambiental (ISO 14001)</strong>. 
                            Evaluaciones supervisadas con protocolo anti-trampa y calificación equitativa sobre 100% para aprendices de 
                            <span class="text-[#33FF57] font-semibold">SENA Empresa CEFA</span>.
                        </p>

                        <!-- Botones de Acción Estilo RealStack (Pill Button Neón + Enlace Ghost) -->
                        <div class="flex flex-wrap items-center gap-4 pt-2">
                            <a href="#catalogo-modulos" 
                               class="inline-flex items-center gap-3 px-8 py-4 rounded-full bg-[#33FF57] hover:bg-[#2adb4e] text-[#0A1322] font-black text-sm tracking-wide shadow-lg shadow-[#33FF57]/25 hover:shadow-[#33FF57]/40 hover:scale-[1.02] transition-all duration-300 cursor-pointer">
                                <span>Iniciar Inducción</span>
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>

                            <a href="#ruta-aprendizaje" 
                               class="inline-flex items-center gap-2 px-6 py-4 rounded-full text-slate-200 hover:text-white font-bold text-sm hover:underline transition-all group">
                                <span>Cómo funciona</span>
                                <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                            </a>

                            @if(auth()->check())
                                @if(auth()->user()->hasRole('admin_sisig') || auth()->user()->hasRole('sisig.admin') || auth()->user()->hasRole('superadmin'))
                                    <a href="{{ route('sisig.admin.dashboard') }}" 
                                       class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-white/10 hover:bg-white/15 text-white text-xs font-bold border border-white/20 transition-all backdrop-blur-md">
                                        <i class="fas fa-chart-line text-[#33FF57]"></i>
                                        <span>Panel Admin</span>
                                    </a>
                                @elseif(auth()->user()->hasRole('editor_sisig'))
                                    <a href="{{ route('sisig.editor.dashboard') }}" 
                                       class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-white/10 hover:bg-white/15 text-white text-xs font-bold border border-white/20 transition-all backdrop-blur-md">
                                        <i class="fas fa-edit text-[#33FF57]"></i>
                                        <span>Panel Editor</span>
                                    </a>
                                @else
                                    <a href="{{ route('sisig.aprendiz.dashboard') }}" 
                                       class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-white/10 hover:bg-white/15 text-white text-xs font-bold border border-white/20 transition-all backdrop-blur-md">
                                        <i class="fas fa-graduation-cap text-[#33FF57]"></i>
                                        <span>Mi Dashboard</span>
                                    </a>
                                @endif
                            @endif
                        </div>

                        <!-- Microcopy institucional inferior -->
                        <div class="pt-1 text-xs text-slate-400 font-medium flex items-center gap-2">
                            <i class="fas fa-check-circle text-[#33FF57]"></i>
                            <span>Acceso oficial para aprendices e instructores con matrícula activa en CEFA.</span>
                        </div>

                    </div>


                    <!-- ================================================================= -->
                    <!-- Columna Derecha: Dual Mockup Responsivo (Desktop + Móvil + SLA)   -->
                    <!-- ================================================================= -->
                    <div class="lg:col-span-5 relative flex flex-col items-center">
                        
                        <div class="relative w-full max-w-lg">
                            
                            <!-- 1. Marco Desktop (Ventana de Navegador) -->
                            <div class="device-browser-frame rounded-2xl overflow-hidden shadow-2xl relative z-10 transition-transform duration-500 hover:scale-[1.01]">
                                
                                <!-- Barra Superior de Navegador con 3 botones de control -->
                                <div class="px-4 py-2.5 bg-[#080E1C] border-b border-white/10 flex items-center justify-between">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500/80"></span>
                                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500/80"></span>
                                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/80"></span>
                                    </div>
                                    <div class="px-3 py-0.5 rounded-md bg-white/5 border border-white/10 text-[10px] text-slate-400 font-mono flex items-center gap-1.5">
                                        <i class="fas fa-lock text-[9px] text-[#33FF57]"></i>
                                        <span>sisig.senaempresa.edu.co</span>
                                    </div>
                                    <div class="w-10"></div>
                                </div>

                                <!-- Contenido de Pantalla Desktop Simulado -->
                                <div class="p-4 sm:p-5 bg-gradient-to-b from-[#0D182E] to-[#0A1224] space-y-4">
                                    
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

                                    <!-- Mini Grid de Módulos dentro del Mockup Desktop -->
                                    <div class="grid grid-cols-2 gap-2.5">
                                        <div class="bg-white/5 border border-white/10 rounded-xl p-2.5 space-y-1.5">
                                            <div class="flex items-center justify-between">
                                                <span class="text-[9px] font-bold text-slate-400 uppercase">ISO 9001</span>
                                                <span class="text-[9px] text-[#33FF57] font-mono">100%</span>
                                            </div>
                                            <div class="text-xs font-bold text-white truncate">Gestión Calidad</div>
                                            <div class="w-full bg-white/10 h-1 rounded-full overflow-hidden">
                                                <div class="bg-[#33FF57] h-full w-3/4"></div>
                                            </div>
                                        </div>

                                        <div class="bg-white/5 border border-white/10 rounded-xl p-2.5 space-y-1.5">
                                            <div class="flex items-center justify-between">
                                                <span class="text-[9px] font-bold text-slate-400 uppercase">ISO 14001</span>
                                                <span class="text-[9px] text-emerald-400 font-mono">Activo</span>
                                            </div>
                                            <div class="text-xs font-bold text-white truncate">Medio Ambiente</div>
                                            <div class="w-full bg-white/10 h-1 rounded-full overflow-hidden">
                                                <div class="bg-emerald-500 h-full w-2/3"></div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <!-- 2. Marco Móvil Superpuesto (Smartphone Foreground Frame) -->
                            <div class="device-mobile-frame hidden sm:block absolute -right-4 -bottom-10 w-44 rounded-3xl overflow-hidden z-20 transition-transform duration-500 hover:scale-105">
                                
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
                            <div class="rounded-2xl bg-[#0D182E]/95 border border-[#33FF57]/30 p-4 sm:p-5 shadow-2xl backdrop-blur-xl space-y-3 tech-glow-navy">
                                
                                <div class="flex items-center justify-between pb-2 border-b border-white/10">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-route text-[#33FF57] text-xs"></i>
                                        <span class="text-xs font-black text-white tracking-wide">Ruta Oficial de Acreditación SIG</span>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        3 Etapas Clave
                                    </span>
                                </div>

                                <!-- 3 Pasos Conectados Horizontalmente -->
                                <div class="grid grid-cols-3 gap-2 sm:gap-4 text-center items-center">
                                    
                                    <!-- Paso 1 -->
                                    <div class="space-y-1">
                                        <div class="w-8 h-8 mx-auto rounded-xl bg-white/5 border border-white/10 text-[#33FF57] flex items-center justify-center text-xs">
                                            <i class="fas fa-book-open"></i>
                                        </div>
                                        <div class="text-[11px] font-bold text-white truncate">1. Contenidos</div>
                                        <div class="text-[9px] text-slate-400">Estudio interactivo</div>
                                    </div>

                                    <!-- Paso 2 -->
                                    <div class="space-y-1 relative">
                                        <div class="w-8 h-8 mx-auto rounded-xl bg-white/5 border border-white/10 text-emerald-400 flex items-center justify-center text-xs">
                                            <i class="fas fa-shield-alt"></i>
                                        </div>
                                        <div class="text-[11px] font-bold text-white truncate">2. Evaluación</div>
                                        <div class="text-[9px] text-slate-400">Anti-trampa 3 vidas</div>
                                    </div>

                                    <!-- Paso 3 -->
                                    <div class="space-y-1">
                                        <div class="w-8 h-8 mx-auto rounded-xl bg-[#33FF57]/20 border border-[#33FF57]/40 text-[#33FF57] flex items-center justify-center text-xs">
                                            <i class="fas fa-rocket"></i>
                                        </div>
                                        <div class="text-[11px] font-bold text-[#33FF57] truncate">3. Acreditación</div>
                                        <div class="text-[9px] text-slate-400">Aprobación SENA</div>
                                    </div>

                                </div>

                            </div>

                            <p class="text-center text-[11px] text-slate-400 mt-2.5">
                                Vista interactiva optimizada para computador, tableta y teléfono móvil.
                            </p>
                        </div>

                    </div>

                </div>

            </div>
        </section>



        <!-- ========================================================================= -->
        <!-- 2. TRUST BAR / 4-ITEM FEATURE STRIP (Idéntico a RealStack)                 -->
        <!-- ========================================================================= -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 sm:-mt-10 relative z-30">
            <div class="rounded-2xl bg-[#0B1426] border border-white/10 shadow-2xl p-4 sm:p-6 backdrop-blur-xl">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6">
                    
                    <!-- Item 1 -->
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-[#33FF57]/10 border border-[#33FF57]/20 flex items-center justify-center text-[#33FF57] text-base flex-shrink-0">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <div>
                            <span class="block text-sm font-bold text-white">Calificación Equitativa</span>
                            <span class="block text-xs text-slate-400">Ponderación 100% automática</span>
                        </div>
                    </div>

                    <!-- Item 2 -->
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-[#33FF57]/10 border border-[#33FF57]/20 flex items-center justify-center text-[#33FF57] text-base flex-shrink-0">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <span class="block text-sm font-bold text-white">Protocolo Anti-Trampa</span>
                            <span class="block text-xs text-slate-400">3 vidas y control de pestañas</span>
                        </div>
                    </div>

                    <!-- Item 3 -->
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-[#33FF57]/10 border border-[#33FF57]/20 flex items-center justify-center text-[#33FF57] text-base flex-shrink-0">
                            <i class="fas fa-award"></i>
                        </div>
                        <div>
                            <span class="block text-sm font-bold text-white">Acreditación Oficial</span>
                            <span class="block text-xs text-slate-400">Habilitación para SENA Empresa</span>
                        </div>
                    </div>

                    <!-- Item 4 -->
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-[#33FF57]/10 border border-[#33FF57]/20 flex items-center justify-center text-[#33FF57] text-base flex-shrink-0">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div>
                            <span class="block text-sm font-bold text-white">Trazabilidad Total</span>
                            <span class="block text-xs text-slate-400">Auditoría detallada de respuestas</span>
                        </div>
                    </div>

                </div>
            </div>
        </section>



        <!-- ========================================================================= -->
        <!-- 3. CATÁLOGO DE MÓDULOS TEMÁTICOS (Dark Tech Showcase)                     -->
        <!-- ========================================================================= -->
        <section id="catalogo-modulos" class="scroll-mt-28 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Encabezado del Catálogo con Buscador Rápido -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-6 border-b border-white/10">
                <div class="space-y-2">
                    <span class="text-xs font-black text-[#33FF57] uppercase tracking-widest flex items-center gap-2">
                        <i class="fas fa-layer-group text-xs"></i>
                        <span>Catálogo Oficial de Formación</span>
                    </span>
                    <h2 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight">
                        Módulos Temáticos del SIG
                    </h2>
                    <p class="text-sm text-slate-400 max-w-2xl leading-relaxed">
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
                           class="w-full pl-10 pr-4 py-3 rounded-full bg-[#0D182E] border border-white/15 text-xs font-medium text-white placeholder-slate-400 focus:outline-none focus:border-[#33FF57] focus:ring-2 focus:ring-[#33FF57]/20 shadow-inner transition-all">
                </div>
            </div>

            <!-- Filtros de Categoría (Pill Buttons Estilo Dark Tech) -->
            <div class="flex items-center gap-2.5 overflow-x-auto pb-2 scrollbar-none">
                <button type="button" 
                        onclick="filtrarPorCategoria('todos', this)" 
                        class="filtro-btn px-5 py-2.5 rounded-full text-xs font-black transition-all bg-[#33FF57] text-[#070C16] shadow-sm flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-th-large text-[10px]"></i>
                    <span>Todos</span>
                    <span class="px-2 py-0.5 rounded-full bg-black/20 text-[10px]">{{ $modulos->count() }}</span>
                </button>

                <button type="button" 
                        onclick="filtrarPorCategoria('sst', this)" 
                        class="filtro-btn px-5 py-2.5 rounded-full text-xs font-bold transition-all bg-[#0D182E] hover:bg-[#132240] text-slate-300 border border-white/10 flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-shield-alt text-[#33FF57] text-[10px]"></i>
                    <span>SST (ISO 45001)</span>
                </button>

                <button type="button" 
                        onclick="filtrarPorCategoria('calidad', this)" 
                        class="filtro-btn px-5 py-2.5 rounded-full text-xs font-bold transition-all bg-[#0D182E] hover:bg-[#132240] text-slate-300 border border-white/10 flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-award text-blue-400 text-[10px]"></i>
                    <span>Calidad (ISO 9001)</span>
                </button>

                <button type="button" 
                        onclick="filtrarPorCategoria('ambiental', this)" 
                        class="filtro-btn px-5 py-2.5 rounded-full text-xs font-bold transition-all bg-[#0D182E] hover:bg-[#132240] text-slate-300 border border-white/10 flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-leaf text-emerald-400 text-[10px]"></i>
                    <span>Ambiental (ISO 14001)</span>
                </button>
            </div>

            <!-- Cuadrícula de Tarjetas de Módulos (Dark Cards con Acento Neón) -->
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

                    <div class="tarjeta-modulo-item bg-[#0D182E]/90 border border-white/10 hover:border-[#33FF57]/60 rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl hover:shadow-[#33FF57]/10 transition-all duration-300 flex flex-col group"
                         data-categoria="{{ $categoriaTag }}"
                         data-busqueda="{{ $tituloLower }} {{ $normaLower }} {{ mb_strtolower($mod->descripcion ?? '') }}">
                        
                        <!-- Encabezado Visual de Tarjeta / Imagen con Overlay -->
                        <div class="h-48 w-full bg-gradient-to-br from-[#142342] via-[#0E1A33] to-[#080E1C] flex items-center justify-center relative overflow-hidden flex-shrink-0">
                            
                            <!-- Badges Superiores Flotantes -->
                            <div class="absolute top-4 left-4 z-10">
                                <span class="bg-[#070C16]/80 backdrop-blur-md text-white border border-white/20 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider">
                                    {{ !empty($mod->norma) ? $mod->norma : 'Eje SIG' }}
                                </span>
                            </div>

                            <div class="absolute top-4 right-4 z-10">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-[#070C16]/80 backdrop-blur-md text-slate-200 border border-white/20">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $mod->estado === 'publicado' ? 'bg-[#33FF57]' : 'bg-amber-400' }}"></span>
                                    <span class="capitalize">{{ $mod->estado ?? 'Publicado' }}</span>
                                </span>
                            </div>

                            <!-- Contenedor Central de Imagen o Ícono -->
                            <div class="w-20 h-20 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-white overflow-hidden shadow-xl group-hover:scale-110 transition-transform duration-300 z-10">
                                @if(!empty($mod->imagen) && file_exists(public_path($mod->imagen)))
                                    <img src="{{ asset($mod->imagen) }}" 
                                         alt="{{ $mod->titulo }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <i class="{{ !empty($mod->icon_class) ? $mod->icon_class : 'fas fa-layer-group' }} text-3xl text-[#33FF57]"></i>
                                @endif
                            </div>

                            <!-- Resplandor sutil interior -->
                            <div class="absolute -bottom-10 -right-10 w-32 h-32 rounded-full bg-[#33FF57]/15 blur-xl pointer-events-none"></div>
                        </div>

                        <!-- Cuerpo de la Tarjeta -->
                        <div class="p-6 flex-1 flex flex-col justify-between space-y-5">
                            
                            <div class="space-y-2">
                                <h3 class="font-black text-white text-lg tracking-tight leading-snug group-hover:text-[#33FF57] transition-colors">
                                    {{ $mod->titulo }}
                                </h3>
                                <p class="text-xs text-slate-400 leading-relaxed font-normal line-clamp-3">
                                    {{ !empty($mod->descripcion) ? $mod->descripcion : 'Contenido formativo, manuales y evaluación oficial del Sistema Integrado de Gestión para aprendices en etapa productiva.' }}
                                </p>
                            </div>

                            <!-- Fila de Chips Rápidos -->
                            <div class="pt-3 border-t border-white/10 flex items-center justify-between text-xs text-slate-400">
                                <span class="flex items-center gap-1.5 font-bold text-slate-300">
                                    <i class="fas fa-tasks text-[#33FF57]"></i>
                                    <span>{{ $quicesCount }} {{ $quicesCount === 1 ? 'Examen' : 'Exámenes' }}</span>
                                </span>
                                <span class="flex items-center gap-1.5 font-bold text-slate-300">
                                    <i class="fas fa-shield-alt text-rose-400"></i>
                                    <span>Anti-Trampa</span>
                                </span>
                                <span class="flex items-center gap-1.5 font-bold text-slate-300">
                                    <i class="fas fa-calculator text-cyan-400"></i>
                                    <span>Base 100%</span>
                                </span>
                            </div>

                            <!-- Botón de Entrada al Módulo -->
                            <div class="pt-2">
                                <a href="{{ route('sisig.aprendiz.induccion.show', $mod->id) }}" 
                                   class="w-full py-3.5 px-4 rounded-full bg-white/10 hover:bg-[#33FF57] text-white hover:text-[#070C16] border border-white/15 hover:border-transparent text-xs font-black shadow-sm transition-all duration-300 flex items-center justify-center gap-2 cursor-pointer group/btn">
                                    <span>Iniciar Inducción</span>
                                    <i class="fas fa-arrow-right text-[11px] group-hover/btn:translate-x-1 transition-transform"></i>
                                </a>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-[#0D182E] rounded-3xl p-12 text-center border border-white/10 text-white">
                        <i class="fas fa-layer-group text-4xl text-slate-500 mb-3 block"></i>
                        <h4 class="text-base font-bold text-slate-200">Aún no hay módulos publicados</h4>
                        <p class="text-xs text-slate-400 mt-1">Los módulos registrados en la plataforma aparecerán aquí automáticamente.</p>
                    </div>
                @endforelse
            </div>

            <!-- Estado Vacío cuando no coincida la búsqueda -->
            <div id="sin-resultados-catalogo" class="hidden bg-[#0D182E] rounded-3xl p-12 text-center border border-white/10 text-white">
                <i class="fas fa-search text-3xl text-slate-500 mb-3 block"></i>
                <h4 class="text-base font-bold text-slate-200">No se encontraron módulos</h4>
                <p class="text-xs text-slate-400 mt-1">Prueba con otra palabra clave o selecciona otra categoría arriba.</p>
            </div>

        </section>



        <!-- ========================================================================= -->
        <!-- 4. RUTA DE APRENDIZAJE PASO A PASO (Dark Tech Flow)                       -->
        <!-- ========================================================================= -->
        <section id="ruta-aprendizaje" class="scroll-mt-28 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <div class="text-center max-w-2xl mx-auto space-y-2">
                <span class="text-xs font-black text-[#33FF57] uppercase tracking-widest">Metodología de Acreditación</span>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight">
                    ¿Cómo funciona el proceso en SISIG?
                </h2>
                <p class="text-sm text-slate-400 leading-relaxed">
                    Un recorrido estructurado y supervisado para garantizar que cada aprendiz domine los lineamientos de SENA Empresa.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Paso 1 -->
                <div class="bg-[#0D182E]/90 rounded-3xl p-6 border border-white/10 hover:border-[#33FF57]/40 shadow-xl transition-all space-y-4 relative group">
                    <div class="w-12 h-12 rounded-2xl bg-white/5 text-[#33FF57] font-black text-base flex items-center justify-center border border-white/10 group-hover:scale-105 transition-transform">
                        01
                    </div>
                    <h3 class="text-base font-bold text-white">1. Explora el Catálogo</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Accede a los módulos formativos de SST, Calidad y Medio Ambiente asignados a tu programa o etapa de inducción.
                    </p>
                </div>

                <!-- Paso 2 -->
                <div class="bg-[#0D182E]/90 rounded-3xl p-6 border border-white/10 hover:border-[#33FF57]/40 shadow-xl transition-all space-y-4 relative group">
                    <div class="w-12 h-12 rounded-2xl bg-white/5 text-blue-400 font-black text-base flex items-center justify-center border border-white/10 group-hover:scale-105 transition-transform">
                        02
                    </div>
                    <h3 class="text-base font-bold text-white">2. Estudia la Normativa</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Revisa infografías, manuales interactivos y directrices institucionales preparadas por los instructores y editores.
                    </p>
                </div>

                <!-- Paso 3 -->
                <div class="bg-[#0D182E]/90 rounded-3xl p-6 border border-white/10 hover:border-[#33FF57]/40 shadow-xl transition-all space-y-4 relative group">
                    <div class="w-12 h-12 rounded-2xl bg-white/5 text-amber-400 font-black text-base flex items-center justify-center border border-white/10 group-hover:scale-105 transition-transform">
                        03
                    </div>
                    <h3 class="text-base font-bold text-white">3. Presenta la Evaluación</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Demuestra tus conocimientos en pantalla completa bajo el protocolo seguro anti-trampa con calificación equitativa sobre 100%.
                    </p>
                </div>

                <!-- Paso 4 -->
                <div class="bg-[#0D182E]/90 rounded-3xl p-6 border border-white/10 hover:border-[#33FF57]/40 shadow-xl transition-all space-y-4 relative group">
                    <div class="w-12 h-12 rounded-2xl bg-[#33FF57]/20 text-[#33FF57] font-black text-base flex items-center justify-center border border-[#33FF57]/30 group-hover:scale-105 transition-transform">
                        04
                    </div>
                    <h3 class="text-base font-bold text-white">4. Certificación y Etapa</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Al superar el puntaje mínimo de aprobación (70%), el sistema registra tu estado de acreditación para SENA Empresa.
                    </p>
                </div>

            </div>

        </section>



        <!-- ========================================================================= -->
        <!-- 5. SUBMÓDULOS Y OPERACIONES EN SISIG (Capacidades del Sistema)             -->
        <!-- ========================================================================= -->
        <section id="submodulos" class="scroll-mt-28 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <div class="border-b border-white/10 pb-5 flex items-center justify-between flex-wrap gap-4">
                <div>
                    <span class="text-xs font-black text-[#33FF57] uppercase tracking-widest flex items-center gap-1.5">
                        <i class="fas fa-cogs"></i>
                        <span>Ecosistema de Herramientas</span>
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight mt-1">
                        Submódulos y Operaciones en SISIG
                    </h2>
                </div>
                <p class="text-xs sm:text-sm text-slate-400 max-w-md text-left sm:text-right">
                    Diseñado para conectar a administradores, editores de contenidos y aprendices en un entorno digital fluido.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Card 1: Formación y Contenidos -->
                <div class="bg-[#0D182E]/90 rounded-3xl border border-white/10 p-6 shadow-xl hover:border-[#33FF57]/50 hover:-translate-y-1 transition-all flex flex-col justify-between space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-white/5 text-[#33FF57] flex items-center justify-center text-xl border border-white/10">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div class="space-y-1.5">
                        <h4 class="font-extrabold text-white text-base">Formación y Lienzo</h4>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            Editor interactivo de contenidos formativos, material audiovisual y guías para los ejes temáticos del SIG.
                        </p>
                    </div>
                    <div class="pt-3 border-t border-white/10 flex items-center justify-between text-xs font-bold text-slate-400">
                        <span>Lienzo Multimedia</span>
                        <span class="text-[#33FF57]">Formativo</span>
                    </div>
                </div>

                <!-- Card 2: Quices y Preguntas -->
                <div class="bg-[#0D182E]/90 rounded-3xl border border-white/10 p-6 shadow-xl hover:border-[#33FF57]/50 hover:-translate-y-1 transition-all flex flex-col justify-between space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-white/5 text-blue-400 flex items-center justify-center text-xl border border-white/10">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="space-y-1.5">
                        <h4 class="font-extrabold text-white text-base">Evaluaciones y Quices</h4>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            6 tipos de preguntas interactivas con calificación equitativa sobre 100% y bloqueo automático de integridad.
                        </p>
                    </div>
                    <div class="pt-3 border-t border-white/10 flex items-center justify-between text-xs font-bold text-slate-400">
                        <span>Ponderación 100%</span>
                        <span class="text-blue-400">Reactivos</span>
                    </div>
                </div>

                <!-- Card 3: Auditoría Anti-Trampa -->
                <div class="bg-[#0D182E]/90 rounded-3xl border border-white/10 p-6 shadow-xl hover:border-[#33FF57]/50 hover:-translate-y-1 transition-all flex flex-col justify-between space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-white/5 text-rose-400 flex items-center justify-center text-xl border border-white/10">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="space-y-1.5">
                        <h4 class="font-extrabold text-white text-base">Auditoría Anti-Trampa</h4>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            Sistema de 3 vidas, supervisión de cambio de pestañas, anulación automática y revisión detallada pregunta por pregunta.
                        </p>
                    </div>
                    <div class="pt-3 border-t border-white/10 flex items-center justify-between text-xs font-bold text-slate-400">
                        <span>3 Vidas Seguras</span>
                        <span class="text-rose-400">Integridad</span>
                    </div>
                </div>

                <!-- Card 4: Reportes y Métricas -->
                <div class="bg-[#0D182E]/90 rounded-3xl border border-white/10 p-6 shadow-xl hover:border-[#33FF57]/50 hover:-translate-y-1 transition-all flex flex-col justify-between space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-white/5 text-amber-400 flex items-center justify-center text-xl border border-white/10">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div class="space-y-1.5">
                        <h4 class="font-extrabold text-white text-base">Reportes y Métricas</h4>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            Gráficas Donut interactivas, seguimiento por ficha, tasa de aprobación y exportación en tiempo real.
                        </p>
                    </div>
                    <div class="pt-3 border-t border-white/10 flex items-center justify-between text-xs font-bold text-slate-400">
                        <span>Trazabilidad</span>
                        <span class="text-amber-400">Estadísticas</span>
                    </div>
                </div>

            </div>

        </section>



        <!-- ========================================================================= -->
        <!-- 6. CALL TO ACTION FINAL (Diseño Oscuro con Resplandor Neón)                -->
        <!-- ========================================================================= -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#091122] via-[#0E1A33] to-[#091122] border border-[#33FF57]/30 p-8 sm:p-14 text-white shadow-2xl tech-glow-green">
                
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
           class="inline-flex items-center gap-2.5 px-4 py-2.5 rounded-full bg-[#0D182E]/95 border border-[#33FF57]/40 text-slate-200 hover:text-white hover:border-[#33FF57] shadow-2xl backdrop-blur-md transition-all hover:scale-105 group"
           title="Ver equipo de desarrollo de SISIG">
            <span class="w-2.5 h-2.5 rounded-full bg-[#33FF57] animate-pulse"></span>
            <span class="text-xs font-black tracking-wide text-[#33FF57]">SISIG</span>
            <span class="text-xs text-slate-400 font-semibold group-hover:text-slate-200">· SENA Empresa</span>
            <i class="fas fa-chevron-right text-[10px] text-slate-400 group-hover:text-[#33FF57] transition-transform"></i>
        </a>
    </div>



    <!-- ========================================================================= -->
    <!-- JAVASCRIPT DEL CATÁLOGO EN TIEMPO REAL (Filtro por Categorías y Búsqueda) -->
    <!-- ========================================================================= -->
    <script>
        let categoriaActiva = 'todos';

        function filtrarPorCategoria(categoria, btn) {
            categoriaActiva = categoria;

            // Actualizar estilo visual de los botones de filtro con la paleta Dark Tech
            document.querySelectorAll('.filtro-btn').forEach(b => {
                b.className = 'filtro-btn px-5 py-2.5 rounded-full text-xs font-bold transition-all bg-[#0D182E] hover:bg-[#132240] text-slate-300 border border-white/10 flex items-center gap-2 cursor-pointer';
            });

            if (btn) {
                btn.className = 'filtro-btn px-5 py-2.5 rounded-full text-xs font-black transition-all bg-[#33FF57] text-[#070C16] shadow-sm flex items-center gap-2 cursor-pointer';
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
