<x-sisig::layouts.master title="Sistema de Inducción SIG | SENA Empresa">
    <div class="space-y-16 pb-20">

        <!-- ========================================================================= -->
        <!-- 1. HERO SECTION PREMIUM TECH (Inspirado en Referencia 2 con paleta SISIG) -->
        <!-- ========================================================================= -->
        <section id="inicio" class="scroll-mt-28 relative overflow-hidden bg-gradient-to-b from-[#0B1528] via-[#0F1E38] to-[#142646] border-b border-slate-800 shadow-2xl text-white">
            
            <!-- Luces y Resplandores Ambientales de Fondo (Ambient Neon & Deep Indigo Glow) -->
            <div class="absolute -top-32 -left-32 w-96 h-96 bg-[#33FF57]/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute top-1/4 right-0 w-[32rem] h-[32rem] bg-emerald-600/10 rounded-full blur-[120px] pointer-events-none"></div>
            <div class="absolute -bottom-24 left-1/3 w-80 h-80 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Patrón de malla sutil de fondo -->
            <div class="absolute inset-0 bg-[radial-gradient(#ffffff0a_1px,transparent_1px)] [background-size:24px_24px] pointer-events-none opacity-40"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 sm:pt-16 pb-16 relative z-10">
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                    
                    <!-- Columna Izquierda: Mensaje Central & Llamados a la Acción -->
                    <div class="lg:col-span-7 space-y-7">
                        
                        <!-- Pill Badge Institucional Estilo Tech -->
                        <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-white/10 border border-white/15 text-xs text-[#33FF57] font-extrabold uppercase tracking-wider backdrop-blur-md shadow-inner">
                            <span class="w-2 h-2 rounded-full bg-[#33FF57] animate-pulse"></span>
                            <span>Sistema Integrado de Gestión · SENA Empresa</span>
                        </div>

                        <!-- Titular Principal de Alto Impacto -->
                        <h1 class="text-3xl sm:text-5xl lg:text-5xl font-black tracking-tight text-white leading-[1.15]">
                            Tu Inducción en el <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#33FF57] via-[#52ff73] to-[#2E6F40]">SIG</span>, ágil, moderna y certificada.
                        </h1>

                        <!-- Descripción Clara y Profesional -->
                        <p class="text-slate-300 text-sm sm:text-base leading-relaxed max-w-2xl font-normal">
                            Plataforma oficial de inducción y evaluación para aprendices de tecnólogo en etapa productiva. Capacítate en 
                            <strong class="text-white font-semibold">Seguridad y Salud en el Trabajo (ISO 45001)</strong>, 
                            <strong class="text-white font-semibold">Gestión de la Calidad (ISO 9001)</strong> y 
                            <strong class="text-white font-semibold">Sostenibilidad Ambiental (ISO 14001)</strong> mediante contenidos interactivos y evaluaciones supervisadas con protocolo anti-trampa.
                        </p>

                        <!-- Botones de Acción (Combinación Neon High-Contrast & Glass) -->
                        <div class="flex flex-wrap items-center gap-4 pt-2">
                            <a href="#catalogo-modulos" 
                               class="inline-flex items-center gap-2.5 px-6 py-3.5 rounded-2xl bg-[#33FF57] hover:bg-[#2ae049] text-[#1A2B4C] text-sm font-black shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 transition-all duration-300 transform hover:-translate-y-0.5 cursor-pointer">
                                <i class="fas fa-th-large text-xs"></i>
                                <span>Explorar Módulos SIG</span>
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>

                            <a href="#ruta-aprendizaje" 
                               class="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-white/10 hover:bg-white/15 text-white text-sm font-bold border border-white/20 hover:border-white/40 transition-all duration-300 backdrop-blur-md">
                                <i class="fas fa-compass text-[#33FF57]"></i>
                                <span>Ruta de Aprendizaje</span>
                            </a>

                            @if(auth()->check())
                                @if(auth()->user()->hasRole('admin_sisig') || auth()->user()->hasRole('sisig.admin') || auth()->user()->hasRole('superadmin'))
                                    <a href="{{ route('sisig.admin.dashboard') }}" 
                                       class="inline-flex items-center gap-2 px-5 py-3.5 rounded-2xl bg-slate-800/80 hover:bg-slate-800 text-slate-200 hover:text-white text-sm font-bold border border-slate-700/80 hover:border-emerald-500/50 transition-all duration-300 backdrop-blur-md">
                                        <i class="fas fa-chart-line text-[#33FF57]"></i>
                                        <span>Panel Admin</span>
                                    </a>
                                @elseif(auth()->user()->hasRole('editor_sisig'))
                                    <a href="{{ route('sisig.editor.dashboard') }}" 
                                       class="inline-flex items-center gap-2 px-5 py-3.5 rounded-2xl bg-slate-800/80 hover:bg-slate-800 text-slate-200 hover:text-white text-sm font-bold border border-slate-700/80 hover:border-emerald-500/50 transition-all duration-300 backdrop-blur-md">
                                        <i class="fas fa-edit text-[#33FF57]"></i>
                                        <span>Panel Editor</span>
                                    </a>
                                @else
                                    <a href="{{ route('sisig.aprendiz.dashboard') }}" 
                                       class="inline-flex items-center gap-2 px-5 py-3.5 rounded-2xl bg-slate-800/80 hover:bg-slate-800 text-slate-200 hover:text-white text-sm font-bold border border-slate-700/80 hover:border-emerald-500/50 transition-all duration-300 backdrop-blur-md">
                                        <i class="fas fa-graduation-cap text-[#33FF57]"></i>
                                        <span>Mi Dashboard</span>
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" 
                                   class="inline-flex items-center gap-2 px-5 py-3.5 rounded-2xl bg-slate-800/80 hover:bg-slate-800 text-slate-200 hover:text-white text-sm font-bold border border-slate-700/80 hover:border-emerald-500/50 transition-all duration-300 backdrop-blur-md">
                                    <i class="fas fa-sign-in-alt text-[#33FF57]"></i>
                                    <span>Ingresar</span>
                                </a>
                            @endif
                        </div>

                    </div>

                    <!-- Columna Derecha: Tarjeta Flotante Glassmorphism (Inspirada en el Showcase de Referencia 2) -->
                    <div class="lg:col-span-5 flex justify-center lg:justify-end">
                        <div class="relative w-full max-w-md group">
                            
                            <!-- Resplandor exterior en hover -->
                            <div class="absolute -inset-1.5 bg-gradient-to-r from-[#33FF57]/30 to-emerald-600/30 rounded-3xl blur-xl opacity-60 group-hover:opacity-100 transition duration-700 pointer-events-none"></div>
                            
                            <!-- Contenedor Glassmorphism Principal -->
                            <div class="relative bg-[#111F3B]/90 border border-white/15 rounded-3xl p-6 sm:p-7 backdrop-blur-2xl shadow-2xl space-y-6">
                                
                                <!-- Cabecera de la Tarjeta Flotante -->
                                <div class="flex items-center justify-between pb-4 border-b border-white/10">
                                    <div class="flex items-center gap-2.5">
                                        <span class="w-2.5 h-2.5 rounded-full bg-[#33FF57] shadow-xs shadow-emerald-400"></span>
                                        <span class="text-xs font-black uppercase tracking-wider text-slate-300">
                                            {{ $moduloDestacado->norma ?? 'SST · ISO 45001' }}
                                        </span>
                                    </div>
                                    <span class="px-3 py-0.5 rounded-full bg-emerald-500/20 text-[#33FF57] border border-[#33FF57]/30 text-[10px] font-black uppercase tracking-wider">
                                        Inducción Activa
                                    </span>
                                </div>

                                <!-- Presentación Visual del Módulo Destacado -->
                                <div class="space-y-3">
                                    <h3 class="text-xl sm:text-2xl font-black text-white tracking-tight leading-snug">
                                        {{ $moduloDestacado->titulo ?? 'Seguridad y Salud en el Trabajo' }}
                                    </h3>
                                    <p class="text-xs text-slate-300 line-clamp-2 leading-relaxed">
                                        {{ $moduloDestacado->descripcion ?? 'Protocolos de prevención, uso de EPP, brigadas de emergencia y bienestar en el Centro de Formación La Angostura.' }}
                                    </p>
                                </div>

                                <!-- Preview Mockup / Portada -->
                                <div class="h-44 rounded-2xl bg-gradient-to-br from-[#1A2B4C] to-[#0A1424] border border-white/10 flex items-center justify-center relative overflow-hidden shadow-inner group/img">
                                    @if(!empty($moduloDestacado->imagen) && file_exists(public_path($moduloDestacado->imagen)))
                                        <img src="{{ asset($moduloDestacado->imagen) }}" 
                                             alt="{{ $moduloDestacado->titulo }}" 
                                             class="w-full h-full object-cover group-hover/img:scale-105 transition-transform duration-500">
                                    @else
                                        <div class="text-center p-4">
                                            <div class="w-16 h-16 mx-auto rounded-2xl bg-white/10 text-[#33FF57] flex items-center justify-center text-3xl mb-2 shadow-lg">
                                                <i class="fas fa-shield-alt"></i>
                                            </div>
                                            <span class="text-[11px] font-bold text-slate-300 uppercase tracking-widest block">CEFA · SENA Empresa</span>
                                        </div>
                                    @endif

                                    <!-- Overlay de degradado inferior -->
                                    <div class="absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-[#111F3B] to-transparent pointer-events-none"></div>
                                </div>

                                <!-- Cuadrícula de Especificaciones / Specs (Estilo EcoFlow) -->
                                <div class="grid grid-cols-3 gap-2.5 pt-1">
                                    <div class="bg-white/5 border border-white/10 rounded-2xl p-3 text-center">
                                        <span class="block text-sm sm:text-base font-black text-white font-mono">100%</span>
                                        <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Ponderación</span>
                                    </div>
                                    <div class="bg-white/5 border border-white/10 rounded-2xl p-3 text-center">
                                        <span class="block text-sm sm:text-base font-black text-[#33FF57] font-mono">3 Vidas</span>
                                        <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Anti-Trampa</span>
                                    </div>
                                    <div class="bg-white/5 border border-white/10 rounded-2xl p-3 text-center">
                                        <span class="block text-sm sm:text-base font-black text-emerald-400 font-mono">Oficial</span>
                                        <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">SENA SIG</span>
                                    </div>
                                </div>

                                <!-- Badge Flotante de Entrega Verificada + Botón -->
                                <div class="pt-2 flex items-center justify-between gap-3">
                                    <div class="inline-flex items-center gap-2 text-xs font-bold text-slate-300">
                                        <i class="fas fa-certificate text-[#33FF57]"></i>
                                        <span>Habilitación Verificada</span>
                                    </div>

                                    @if(!empty($moduloDestacado))
                                        <a href="{{ route('sisig.aprendiz.induccion.show', $moduloDestacado->id) }}" 
                                           class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-[#33FF57] hover:text-white border border-[#33FF57]/30 text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer">
                                            <span>Ver Módulo</span>
                                            <i class="fas fa-chevron-right text-[10px]"></i>
                                        </a>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                <!-- ------------------------------------------------------------- -->
                <!-- Barra de Métricas Globales Inferior (Estilo 100% / Gratis / 3000+) -->
                <!-- ------------------------------------------------------------- -->
                <div class="mt-14 pt-8 border-t border-white/10 grid grid-cols-2 md:grid-cols-4 gap-6 text-left">
                    
                    <div class="space-y-1">
                        <div class="text-2xl sm:text-3xl font-black text-white tracking-tight flex items-baseline gap-1">
                            <span>100</span><span class="text-[#33FF57]">%</span>
                        </div>
                        <div class="text-xs font-extrabold text-[#33FF57] uppercase tracking-wider">Ponderación Equitativa</div>
                        <p class="text-[11px] text-slate-400 leading-snug">Cada pregunta se evalúa automáticamente en partes iguales.</p>
                    </div>

                    <div class="space-y-1">
                        <div class="text-2xl sm:text-3xl font-black text-white tracking-tight flex items-baseline gap-1">
                            <span>{{ $totalModulos ?? 3 }}</span><span class="text-emerald-400">Ejes</span>
                        </div>
                        <div class="text-xs font-extrabold text-emerald-400 uppercase tracking-wider">Normas Institucionales</div>
                        <p class="text-[11px] text-slate-400 leading-snug">SST (45001), Calidad (9001) y Gestión Ambiental (14001).</p>
                    </div>

                    <div class="space-y-1">
                        <div class="text-2xl sm:text-3xl font-black text-white tracking-tight flex items-baseline gap-1">
                            <span>Seguro</span><span class="text-rose-400"><i class="fas fa-shield-alt text-lg ml-1"></i></span>
                        </div>
                        <div class="text-xs font-extrabold text-rose-400 uppercase tracking-wider">Protocolo Anti-Trampa</div>
                        <p class="text-[11px] text-slate-400 leading-snug">Monitoreo de cambio de pestaña y auditoría de integridad.</p>
                    </div>

                    <div class="space-y-1">
                        <div class="text-2xl sm:text-3xl font-black text-white tracking-tight flex items-baseline gap-1">
                            <span>Digital</span><span class="text-cyan-400"><i class="fas fa-award text-lg ml-1"></i></span>
                        </div>
                        <div class="text-xs font-extrabold text-cyan-400 uppercase tracking-wider">Habilitación Oficial</div>
                        <p class="text-[11px] text-slate-400 leading-snug">Trazabilidad de calificaciones para SENA Empresa CEFA.</p>
                    </div>

                </div>

            </div>
        </section>



        <!-- ========================================================================= -->
        <!-- 2. CATÁLOGO DE MÓDULOS TEMÁTICOS (Inspirado en Referencia 1: Shopify Themes) -->
        <!-- ========================================================================= -->
        <section id="catalogo-modulos" class="scroll-mt-28 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Encabezado de Sección Estilo Tienda de Temas Shopify -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-6 border-b border-slate-200">
                <div class="space-y-2">
                    <span class="text-xs font-black text-sena-green uppercase tracking-widest flex items-center gap-2">
                        <i class="fas fa-layer-group text-xs"></i>
                        <span>Catálogo Oficial de Formación</span>
                    </span>
                    <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                        Módulos Temáticos del SIG
                    </h2>
                    <p class="text-sm text-slate-500 max-w-2xl leading-relaxed">
                        Explora el material interactivo, guías y evaluaciones organizadas por ejes temáticos. Cada módulo contiene recursos multimedia y su respectivo examen evaluativo.
                    </p>
                </div>

                <!-- Buscador Rápido en Tiempo Real -->
                <div class="relative w-full md:w-80">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" 
                           id="buscador-modulos-catalogo" 
                           oninput="filtrarModulosCatalogo()" 
                           placeholder="Buscar módulo o norma..." 
                           class="w-full pl-10 pr-4 py-2.5 rounded-2xl bg-white border border-slate-200 text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-sena-green focus:ring-2 focus:ring-emerald-500/20 shadow-xs transition-all">
                </div>
            </div>

            <!-- Barra de Pestañas / Filtros de Categoría (Pills estilo Shopify) -->
            <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
                <button type="button" 
                        onclick="filtrarPorCategoria('todos', this)" 
                        class="filtro-btn px-4 py-2 rounded-2xl text-xs font-black transition-all bg-[#1A2B4C] text-white shadow-sm flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-th-large text-[10px]"></i>
                    <span>Todos</span>
                    <span class="px-1.5 py-0.2 rounded-full bg-white/20 text-[10px]">{{ $modulos->count() }}</span>
                </button>

                <button type="button" 
                        onclick="filtrarPorCategoria('sst', this)" 
                        class="filtro-btn px-4 py-2 rounded-2xl text-xs font-bold transition-all bg-white hover:bg-slate-50 text-slate-600 border border-slate-200 shadow-2xs flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-shield-alt text-emerald-600 text-[10px]"></i>
                    <span>SST (ISO 45001)</span>
                </button>

                <button type="button" 
                        onclick="filtrarPorCategoria('calidad', this)" 
                        class="filtro-btn px-4 py-2 rounded-2xl text-xs font-bold transition-all bg-white hover:bg-slate-50 text-slate-600 border border-slate-200 shadow-2xs flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-award text-blue-600 text-[10px]"></i>
                    <span>Calidad (ISO 9001)</span>
                </button>

                <button type="button" 
                        onclick="filtrarPorCategoria('ambiental', this)" 
                        class="filtro-btn px-4 py-2 rounded-2xl text-xs font-bold transition-all bg-white hover:bg-slate-50 text-slate-600 border border-slate-200 shadow-2xs flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-leaf text-emerald-500 text-[10px]"></i>
                    <span>Ambiental (ISO 14001)</span>
                </button>
            </div>

            <!-- Cuadrícula de Tarjetas de Módulos (Diseño Limpio Shopify Showcase) -->
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

                    <div class="tarjeta-modulo-item bg-white rounded-3xl border border-slate-200/90 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden group"
                         data-categoria="{{ $categoriaTag }}"
                         data-busqueda="{{ $tituloLower }} {{ $normaLower }} {{ mb_strtolower($mod->descripcion ?? '') }}">
                        
                        <!-- Encabezado Visual de Tarjeta / Mockup Frame -->
                        <div class="h-48 w-full bg-gradient-to-br from-[#1A2B4C] via-[#1E335A] to-[#14233C] flex items-center justify-center relative overflow-hidden flex-shrink-0 group-hover:scale-[1.01] transition-transform">
                            
                            <!-- Badges Superiores Flotantes -->
                            <div class="absolute top-4 left-4 z-10 flex items-center gap-2">
                                <span class="bg-[#1A2B4C]/90 backdrop-blur-md text-white border border-white/20 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider shadow-sm">
                                    {{ !empty($mod->norma) ? $mod->norma : 'Eje SIG' }}
                                </span>
                            </div>

                            <div class="absolute top-4 right-4 z-10">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-white/10 backdrop-blur-md text-white border border-white/20">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $mod->estado === 'publicado' ? 'bg-[#33FF57]' : 'bg-amber-400' }}"></span>
                                    <span class="capitalize">{{ $mod->estado ?? 'Publicado' }}</span>
                                </span>
                            </div>

                            <!-- Contenedor Central de Imagen Registrada o Ícono Oficial -->
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
                                <h3 class="font-black text-slate-800 text-lg tracking-tight leading-snug group-hover:text-emerald-700 transition-colors">
                                    {{ $mod->titulo }}
                                </h3>
                                <p class="text-xs text-slate-500 leading-relaxed font-normal line-clamp-3">
                                    {{ !empty($mod->descripcion) ? $mod->descripcion : 'Contenido formativo, manuales y evaluación oficial del Sistema Integrado de Gestión para aprendices en etapa productiva.' }}
                                </p>
                            </div>

                            <!-- Fila de Características Rápidas -->
                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                                <span class="flex items-center gap-1.5 font-bold text-slate-600">
                                    <i class="fas fa-tasks text-emerald-600"></i>
                                    <span>{{ $quicesCount }} {{ $quicesCount === 1 ? 'Examen' : 'Exámenes' }}</span>
                                </span>
                                <span class="flex items-center gap-1.5 font-bold text-slate-600">
                                    <i class="fas fa-shield-alt text-amber-500"></i>
                                    <span>Anti-Trampa</span>
                                </span>
                                <span class="flex items-center gap-1.5 font-bold text-slate-600">
                                    <i class="fas fa-calculator text-blue-500"></i>
                                    <span>Base 100%</span>
                                </span>
                            </div>

                            <!-- Botón de Entrada al Módulo -->
                            <div class="pt-2">
                                <a href="{{ route('sisig.aprendiz.induccion.show', $mod->id) }}" 
                                   class="w-full py-3 px-4 rounded-2xl bg-slate-900 hover:bg-[#33FF57] text-white hover:text-[#1A2B4C] text-xs font-black shadow-sm transition-all duration-300 flex items-center justify-center gap-2 cursor-pointer group/btn">
                                    <span>Iniciar Inducción</span>
                                    <i class="fas fa-arrow-right text-[11px] group-hover/btn:translate-x-1 transition-transform"></i>
                                </a>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-slate-200">
                        <i class="fas fa-layer-group text-4xl text-slate-300 mb-3 block"></i>
                        <h4 class="text-base font-bold text-slate-700">Aún no hay módulos publicados</h4>
                        <p class="text-xs text-slate-400 mt-1">Los módulos registrados en la plataforma aparecerán aquí automáticamente.</p>
                    </div>
                @endforelse
            </div>

            <!-- Estado Vacío cuando no coincida la búsqueda -->
            <div id="sin-resultados-catalogo" class="hidden bg-white rounded-3xl p-12 text-center border border-slate-200">
                <i class="fas fa-search text-3xl text-slate-300 mb-3 block"></i>
                <h4 class="text-base font-bold text-slate-700">No se encontraron módulos</h4>
                <p class="text-xs text-slate-400 mt-1">Prueba con otra palabra clave o selecciona otra categoría arriba.</p>
            </div>

        </section>



        <!-- ========================================================================= -->
        <!-- 3. RUTA DE APRENDIZAJE PASO A PASO (Flujo Educativo Interactivo) -->
        <!-- ========================================================================= -->
        <section id="ruta-aprendizaje" class="scroll-mt-28 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <div class="text-center max-w-2xl mx-auto space-y-2">
                <span class="text-xs font-black text-sena-green uppercase tracking-widest">Metodología de Acreditación</span>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    ¿Cómo funciona el proceso en SISIG?
                </h2>
                <p class="text-sm text-slate-500 leading-relaxed">
                    Un recorrido estructurado y supervisado para garantizar que cada aprendiz domine los lineamientos de SENA Empresa.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Paso 1 -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-xs hover:shadow-lg transition-all space-y-4 relative group">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-sena-green font-black text-base flex items-center justify-center border border-emerald-200 group-hover:scale-105 transition-transform">
                        01
                    </div>
                    <h3 class="text-base font-bold text-slate-800">1. Explora el Catálogo</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Accede a los módulos formativos de SST, Calidad y Medio Ambiente asignados a tu programa o etapa de inducción.
                    </p>
                </div>

                <!-- Paso 2 -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-xs hover:shadow-lg transition-all space-y-4 relative group">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 font-black text-base flex items-center justify-center border border-blue-200 group-hover:scale-105 transition-transform">
                        02
                    </div>
                    <h3 class="text-base font-bold text-slate-800">2. Estudia la Normativa</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Revisa infografías, manuales interactivos y directrices institucionales preparadas por los instructores y editores.
                    </p>
                </div>

                <!-- Paso 3 -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-xs hover:shadow-lg transition-all space-y-4 relative group">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 font-black text-base flex items-center justify-center border border-amber-200 group-hover:scale-105 transition-transform">
                        03
                    </div>
                    <h3 class="text-base font-bold text-slate-800">3. Presenta la Evaluación</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Demuestra tus conocimientos en pantalla completa bajo el protocolo seguro anti-trampa con calificación equitativa sobre 100%.
                    </p>
                </div>

                <!-- Paso 4 -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-xs hover:shadow-lg transition-all space-y-4 relative group">
                    <div class="w-12 h-12 rounded-2xl bg-[#33FF57]/20 text-emerald-800 font-black text-base flex items-center justify-center border border-[#33FF57]/40 group-hover:scale-105 transition-transform">
                        04
                    </div>
                    <h3 class="text-base font-bold text-slate-800">4. Certificación y Etapa</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Al superar el puntaje mínimo de aprobación (70%), el sistema registra tu estado de acreditación para SENA Empresa.
                    </p>
                </div>

            </div>

        </section>



        <!-- ========================================================================= -->
        <!-- 4. SUBMÓDULOS Y OPERACIONES DE LA PLATAFORMA (Capacidades del Sistema) -->
        <!-- ========================================================================= -->
        <section id="submodulos" class="scroll-mt-28 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <div class="border-b border-slate-200 pb-5 flex items-center justify-between flex-wrap gap-4">
                <div>
                    <span class="text-xs font-black text-sena-green uppercase tracking-widest flex items-center gap-1.5">
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
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-sena-green flex items-center justify-center text-xl">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div class="space-y-1.5">
                        <h4 class="font-extrabold text-slate-800 text-base">Formación y Lienzo</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Editor interactivo de contenidos formativos, material audiovisual y guías para los ejes temáticos del SIG.
                        </p>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-400">
                        <span>Lienzo Multimedia</span>
                        <span class="text-emerald-700">Formativo</span>
                    </div>
                </div>

                <!-- Card 2: Quices y Preguntas -->
                <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all flex flex-col justify-between space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="space-y-1.5">
                        <h4 class="font-extrabold text-slate-800 text-base">Evaluaciones y Quices</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            6 tipos de preguntas interactivas con calificación equitativa sobre 100% y bloqueo automático de integridad.
                        </p>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-400">
                        <span>Ponderación 100%</span>
                        <span class="text-blue-600">Reactivos</span>
                    </div>
                </div>

                <!-- Card 3: Auditoría Anti-Trampa -->
                <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all flex flex-col justify-between space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="space-y-1.5">
                        <h4 class="font-extrabold text-slate-800 text-base">Auditoría Anti-Trampa</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Sistema de 3 vidas, supervisión de cambio de pestañas, anulación automática y revisión detallada pregunta por pregunta.
                        </p>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-400">
                        <span>3 Vidas Seguras</span>
                        <span class="text-rose-600">Integridad</span>
                    </div>
                </div>

                <!-- Card 4: Reportes y Métricas -->
                <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all flex flex-col justify-between space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div class="space-y-1.5">
                        <h4 class="font-extrabold text-slate-800 text-base">Reportes y Métricas</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Gráficas Donut interactivas, seguimiento por ficha, tasa de aprobación y exportación en tiempo real.
                        </p>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-400">
                        <span>Trazabilidad</span>
                        <span class="text-amber-600">Estadísticas</span>
                    </div>
                </div>

            </div>

        </section>



        <!-- ========================================================================= -->
        <!-- 5. CALL TO ACTION FINAL (Diseño Oscuro con Acento Neón) -->
        <!-- ========================================================================= -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#0E1A33] via-[#142646] to-[#0E1A33] border border-white/10 p-8 sm:p-14 text-white shadow-2xl">
                
                <div class="absolute -top-20 -right-20 w-80 h-80 bg-[#33FF57]/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>

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
                           class="px-7 py-3.5 rounded-2xl bg-[#33FF57] hover:bg-[#2ae049] text-[#1A2B4C] text-sm font-black shadow-lg shadow-emerald-500/25 transition-all transform hover:-translate-y-0.5 cursor-pointer">
                            <i class="fas fa-play text-xs mr-1.5"></i>
                            <span>Comenzar Ahora</span>
                        </a>
                        <a href="{{ route('home') }}" 
                           class="px-6 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white text-sm font-bold border border-white/20 transition-all backdrop-blur-md">
                            <span>Volver al ERP</span>
                        </a>
                    </div>
                </div>

            </div>
        </section>

    </div>



    <!-- ========================================================================= -->
    <!-- JAVASCRIPT DEL CATÁLOGO EN TIEMPO REAL (Filtro por Categorías y Búsqueda) -->
    <!-- ========================================================================= -->
    <script>
        let categoriaActiva = 'todos';

        function filtrarPorCategoria(categoria, btn) {
            categoriaActiva = categoria;

            // Actualizar estilo visual de los botones de filtro
            document.querySelectorAll('.filtro-btn').forEach(b => {
                b.className = 'filtro-btn px-4 py-2 rounded-2xl text-xs font-bold transition-all bg-white hover:bg-slate-50 text-slate-600 border border-slate-200 shadow-2xs flex items-center gap-2 cursor-pointer';
            });

            if (btn) {
                btn.className = 'filtro-btn px-4 py-2 rounded-2xl text-xs font-black transition-all bg-[#1A2B4C] text-white shadow-sm flex items-center gap-2 cursor-pointer';
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