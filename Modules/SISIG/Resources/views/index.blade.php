<x-sisig::layouts.master>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-16">

        <!-- 1. Hero Section -->
        <section id="inicio" class="scroll-mt-28 relative overflow-hidden rounded-3xl bg-gradient-to-br from-sena-dark via-sena-navy to-sena-slate border border-slate-700/50 shadow-sena-navy text-white p-8 sm:p-12 lg:p-14">
            
            <!-- Glow background decorative elements -->
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-sena-green/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-sena-navy-light/40 rounded-full blur-2xl pointer-events-none"></div>
            
            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                <!-- Left Content -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="text-sena-green text-xs font-extrabold tracking-wider uppercase">
                        Procesos de Apoyo &bull; SENA Empresa
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight">
                        Sistema de Inducción SIG
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-sena-green to-[#6de828]">SISIG</span>
                    </h1>

                    <p class="text-slate-300 text-base sm:text-lg leading-relaxed max-w-2xl font-normal">
                        Plataforma digital para la inducción y evaluación de conocimientos en 
                        <strong class="text-white font-semibold">Seguridad y Salud en el Trabajo (SST)</strong>, 
                        <strong class="text-white font-semibold">Gestión de la Calidad</strong> y 
                        <strong class="text-white font-semibold">Gestión Ambiental</strong> para aprendices de tecnólogo que ingresan a su etapa práctica en <strong class="text-white font-semibold">SENA Empresa</strong>.
                    </p>

                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        <a href="#submodulos" class="inline-flex items-center gap-2.5 px-6 py-3.5 rounded-xl bg-sena-green hover:bg-sena-green-hover text-white text-sm font-bold shadow-sena hover:shadow-glow transition-all duration-300 transform hover:-translate-y-0.5">
                            <i class="fas fa-th-large"></i>
                            <span>Explorar Submódulos</span>
                        </a>
                        <a href="#areas" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl bg-white/10 hover:bg-white/15 text-white text-sm font-semibold border border-white/20 transition-all duration-300 backdrop-blur-sm">
                            <i class="fas fa-shield-alt text-sena-green"></i>
                            <span>Ejes Temáticos SIG</span>
                        </a>
                        <a href="{{ route('sisig.desarrolladores') }}" class="inline-flex items-center gap-2 px-5 py-3.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-sm font-semibold border border-white/20 hover:border-sena-green transition-all duration-300 backdrop-blur-sm group">
                            <i class="fas fa-code text-sena-green group-hover:scale-110 transition-transform"></i>
                            <span>Desarrolladores</span>
                        </a>
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-5 py-3.5 rounded-xl bg-white/5 hover:bg-white/10 text-slate-200 hover:text-white text-sm font-semibold border border-white/20 hover:border-white/40 transition-all duration-300 backdrop-blur-sm">
                            <i class="fas fa-arrow-left text-sena-green"></i>
                            <span>Volver al ERP</span>
                        </a>
                    </div>
                </div>

                <!-- Right Visual Element -->
                <div class="lg:col-span-5 flex justify-center lg:justify-end">
                    <div class="relative group">
                        <div class="absolute -inset-1 bg-gradient-to-r from-sena-green to-[#00A859] rounded-3xl blur opacity-30 group-hover:opacity-60 transition duration-500"></div>
                        <div class="relative bg-slate-900/90 border border-slate-700/80 rounded-3xl p-8 backdrop-blur-xl shadow-2xl flex flex-col items-center text-center max-w-xs space-y-4">
                            <div class="w-32 h-32 flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                                <img src="{{ asset('modules/sisig/images/logo.png') }}" alt="SENA Empresa Logo" class="w-full h-full object-contain filter drop-shadow-xl">
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">SENA Empresa</h3>
                                <p class="text-xs text-slate-400 mt-1">Centro de Formación Agroindustrial 'La Angostura' &bull; CEFA</p>
                            </div>
                            <div class="w-full pt-3 border-t border-slate-800 flex items-center justify-around text-xs">
                                <span class="text-slate-300"><i class="fas fa-check-circle text-sena-green mr-1"></i> SST</span>
                                <span class="text-slate-300"><i class="fas fa-check-circle text-sena-green mr-1"></i> Calidad</span>
                                <span class="text-slate-300"><i class="fas fa-check-circle text-sena-green mr-1"></i> Ambiental</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- 2. KPI / Metrics Summary Section -->
        <section id="indicadores" class="scroll-mt-28 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            
            <!-- Metric 1: Quices -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Evaluaciones SIG</span>
                    <div class="w-8 h-8 rounded-full bg-emerald-50 text-[#2E6F40] flex items-center justify-center text-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                        <i class="fas fa-file-signature"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="flex items-baseline gap-1.5">
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-[#343A40] tracking-tight">Quices</h3>
                    </div>
                    <div class="mt-1 flex items-center justify-between text-xs text-slate-400 font-medium">
                        <span>Medición de Saberes</span>
                        <span class="text-xs font-bold text-[#2E6F40]">Evaluación</span>
                    </div>
                </div>
            </div>

            <!-- Metric 2: Secciones e Inducción -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Módulos Temáticos</span>
                    <div class="w-8 h-8 rounded-full bg-blue-50 text-[#00BCD4] flex items-center justify-center text-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                        <i class="fas fa-book-open"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="flex items-baseline gap-1.5">
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-[#343A40] tracking-tight">Secciones</h3>
                    </div>
                    <div class="mt-1 flex items-center justify-between text-xs text-slate-400 font-medium">
                        <span>Guías y Videos</span>
                        <span class="text-xs font-bold text-[#00BCD4]">Formativo</span>
                    </div>
                </div>
            </div>

            <!-- Metric 3: Aprobación -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Calificaciones</span>
                    <div class="w-8 h-8 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center text-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="flex items-baseline gap-1.5">
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-[#343A40] tracking-tight">Resultados</h3>
                    </div>
                    <div class="mt-1 flex items-center justify-between text-xs text-slate-400 font-medium">
                        <span>Control de Intentos</span>
                        <span class="text-xs font-bold text-amber-600">Historial</span>
                    </div>
                </div>
            </div>

            <!-- Metric 4: Seguridad & Control -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Anti-Fraude</span>
                    <div class="w-8 h-8 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center text-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                        <i class="fas fa-user-shield"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="flex items-baseline gap-1.5">
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-[#343A40] tracking-tight">Infracciones</h3>
                    </div>
                    <div class="mt-1 flex items-center justify-between text-xs text-slate-400 font-medium">
                        <span>Monitoreo Activo</span>
                        <span class="text-xs font-bold text-[#2E6F40]">Auditoría</span>
                    </div>
                </div>
            </div>

        </section>

        <!-- 3. Submodules Interactive Cards -->
        <section id="submodulos" class="scroll-mt-28 space-y-6">
            <div class="border-b border-slate-200 pb-4">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                    <span class="w-3.5 h-8 rounded-full bg-[#2E6F40] inline-block"></span>
                    Submódulos y Operaciones de SISIG
                </h2>
                <p class="text-sm text-slate-500 mt-1">Administra los contenidos de inducción, diseña evaluaciones y realiza seguimiento al conocimiento de los aprendices</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Card 1: Secciones y Contenidos -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden group">
                    <div class="bg-gradient-to-r from-[#1A2B4C] to-[#243B66] p-5 text-white relative">
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="bg-white/10 text-white/90 border border-white/15 px-2.5 py-0.5 rounded-full text-[11px] font-bold">
                                Formación
                            </span>
                            <span class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center text-xs text-white border border-white/20">
                                <i class="fas fa-layer-group"></i>
                            </span>
                        </div>
                        <h3 class="font-extrabold text-white text-base tracking-tight leading-snug line-clamp-1" style="color: #ffffff !important;">
                            Secciones y Contenidos
                        </h3>
                        <p class="text-xs text-slate-200 mt-1 font-medium" style="color: #e2e8f0 !important;">
                            Material didáctico e inductivo
                        </p>
                    </div>
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                        <p class="text-xs text-slate-600 leading-relaxed font-normal">
                            Organización de temas por ejes temáticos, cargue de manuales PDF, videos interactivos e infografías de SST, Calidad y Gestión Ambiental.
                        </p>
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-slate-400 font-semibold">Recursos</span>
                            <span class="font-bold text-[#2E6F40]">Multimedia</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Quices y Preguntas -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden group">
                    <div class="bg-gradient-to-r from-[#1A2B4C] to-[#243B66] p-5 text-white relative">
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="bg-white/10 text-white/90 border border-white/15 px-2.5 py-0.5 rounded-full text-[11px] font-bold">
                                Evaluación
                            </span>
                            <span class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center text-xs text-white border border-white/20">
                                <i class="fas fa-tasks"></i>
                            </span>
                        </div>
                        <h3 class="font-extrabold text-white text-base tracking-tight leading-snug line-clamp-1" style="color: #ffffff !important;">
                            Quices y Preguntas
                        </h3>
                        <p class="text-xs text-slate-200 mt-1 font-medium" style="color: #e2e8f0 !important;">
                            Medición de conocimientos
                        </p>
                    </div>
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                        <p class="text-xs text-slate-600 leading-relaxed font-normal">
                            Cuestionarios por módulo, configuración de puntajes mínimos, cantidad de intentos personalizados por el instructor y preguntas interactivas.
                        </p>
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-slate-400 font-semibold">Cuestionarios</span>
                            <span class="font-bold text-[#00BCD4]">Supervisados</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Resultados e Infracciones -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden group">
                    <div class="bg-gradient-to-r from-[#1A2B4C] to-[#243B66] p-5 text-white relative">
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="bg-white/10 text-white/90 border border-white/15 px-2.5 py-0.5 rounded-full text-[11px] font-bold">
                                Trazabilidad
                            </span>
                            <span class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center text-xs text-white border border-white/20">
                                <i class="fas fa-chart-bar"></i>
                            </span>
                        </div>
                        <h3 class="font-extrabold text-white text-base tracking-tight leading-snug line-clamp-1" style="color: #ffffff !important;">
                            Resultados e Infracciones
                        </h3>
                        <p class="text-xs text-slate-200 mt-1 font-medium" style="color: #e2e8f0 !important;">
                            Control y supervisión
                        </p>
                    </div>
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                        <p class="text-xs text-slate-600 leading-relaxed font-normal">
                            Calificaciones por aprendiz, estado de aprobación para SENA Empresa y detección de eventos de alerta o cambio de pestaña durante las pruebas.
                        </p>
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-slate-400 font-semibold">Seguridad</span>
                            <span class="font-bold text-amber-600">Anti-Fraude</span>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Gestión de Usuarios y Roles -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden group">
                    <div class="bg-gradient-to-r from-[#1A2B4C] to-[#243B66] p-5 text-white relative">
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="bg-white/10 text-white/90 border border-white/15 px-2.5 py-0.5 rounded-full text-[11px] font-bold">
                                Accesos
                            </span>
                            <span class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center text-xs text-white border border-white/20">
                                <i class="fas fa-users-cog"></i>
                            </span>
                        </div>
                        <h3 class="font-extrabold text-white text-base tracking-tight leading-snug line-clamp-1" style="color: #ffffff !important;">
                            Gestión de Usuarios
                        </h3>
                        <p class="text-xs text-slate-200 mt-1 font-medium" style="color: #e2e8f0 !important;">
                            Roles y permisos
                        </p>
                    </div>
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                        <p class="text-xs text-slate-600 leading-relaxed font-normal">
                            Administración de instructores, editores y aprendices, asignación de roles y permisos en SISIG, y consulta de perfiles con foto e historial de inducción.
                        </p>
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-slate-400 font-semibold">Plataforma</span>
                            <span class="font-bold text-[#2E6F40]">SENA Empresa</span>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- 4. SECCIÓN: Ejes Temáticos del Sistema Integrado de Gestión (SIG) -->
        <section id="areas" class="scroll-mt-28 space-y-6">
            <div class="border-b border-slate-200 pb-4">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                    <span class="w-3.5 h-8 rounded-full bg-[#1A2B4C] inline-block"></span>
                    Ejes Temáticos del Sistema Integrado de Gestión (SIG)
                </h2>
                <p class="text-sm text-slate-500 mt-1">Conocimientos fundamentales que cada aprendiz debe cursar y demostrar al ingresar a su etapa en SENA Empresa</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Area 1: Seguridad y Salud en el Trabajo (SST) -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden group">
                    <div class="bg-gradient-to-r from-[#1A2B4C] to-[#243B66] p-5 text-white relative">
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="bg-white/10 text-white/90 border border-white/15 px-2.5 py-0.5 rounded-full text-[11px] font-bold">
                                ISO 45001
                            </span>
                            <span class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center text-xs text-white border border-white/20">
                                <i class="fas fa-hard-hat"></i>
                            </span>
                        </div>
                        <h3 class="font-extrabold text-white text-base tracking-tight leading-snug line-clamp-1" style="color: #ffffff !important;">
                            Seguridad y Salud (SST)
                        </h3>
                        <p class="text-xs text-slate-200 mt-1 font-medium" style="color: #e2e8f0 !important;">
                            Prevención de riesgos
                        </p>
                    </div>
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                        <p class="text-xs text-slate-600 leading-relaxed font-normal">
                            Identificación de peligros, valoración de riesgos laborales, prevención de accidentes y uso correcto de Elementos de Protección Personal (EPP).
                        </p>
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-slate-400 font-semibold">Norma</span>
                            <span class="font-bold text-[#2E6F40]">Autocuidado</span>
                        </div>
                    </div>
                </div>

                <!-- Area 2: Gestión de la Calidad -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden group">
                    <div class="bg-gradient-to-r from-[#1A2B4C] to-[#243B66] p-5 text-white relative">
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="bg-white/10 text-white/90 border border-white/15 px-2.5 py-0.5 rounded-full text-[11px] font-bold">
                                ISO 9001
                            </span>
                            <span class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center text-xs text-white border border-white/20">
                                <i class="fas fa-award"></i>
                            </span>
                        </div>
                        <h3 class="font-extrabold text-white text-base tracking-tight leading-snug line-clamp-1" style="color: #ffffff !important;">
                            Gestión de la Calidad
                        </h3>
                        <p class="text-xs text-slate-200 mt-1 font-medium" style="color: #e2e8f0 !important;">
                            Estándares y procesos
                        </p>
                    </div>
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                        <p class="text-xs text-slate-600 leading-relaxed font-normal">
                            Estandarización de procesos, Buenas Prácticas (BPM/BPA), aseguramiento del servicio, control de calidad y enfoque en mejora continua.
                        </p>
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-slate-400 font-semibold">Norma</span>
                            <span class="font-bold text-[#00BCD4]">Mejora Continua</span>
                        </div>
                    </div>
                </div>

                <!-- Area 3: Gestión Ambiental -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden group">
                    <div class="bg-gradient-to-r from-[#1A2B4C] to-[#243B66] p-5 text-white relative">
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="bg-white/10 text-white/90 border border-white/15 px-2.5 py-0.5 rounded-full text-[11px] font-bold">
                                ISO 14001
                            </span>
                            <span class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center text-xs text-white border border-white/20">
                                <i class="fas fa-leaf"></i>
                            </span>
                        </div>
                        <h3 class="font-extrabold text-white text-base tracking-tight leading-snug line-clamp-1" style="color: #ffffff !important;">
                            Gestión Ambiental
                        </h3>
                        <p class="text-xs text-slate-200 mt-1 font-medium" style="color: #e2e8f0 !important;">
                            Sostenibilidad ecológica
                        </p>
                    </div>
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                        <p class="text-xs text-slate-600 leading-relaxed font-normal">
                            Separación de residuos en la fuente por código de colores, manejo de sustancias especiales, uso eficiente de agua y energía, y conservación del entorno.
                        </p>
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-slate-400 font-semibold">Norma</span>
                            <span class="font-bold text-[#2E6F40]">Reciclaje</span>
                        </div>
                    </div>
                </div>

                <!-- Area 4: Prevención y Respuesta a Emergencias -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden group">
                    <div class="bg-gradient-to-r from-[#1A2B4C] to-[#243B66] p-5 text-white relative">
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="bg-white/10 text-white/90 border border-white/15 px-2.5 py-0.5 rounded-full text-[11px] font-bold">
                                Contingencias
                            </span>
                            <span class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center text-xs text-white border border-white/20">
                                <i class="fas fa-exclamation-triangle"></i>
                            </span>
                        </div>
                        <h3 class="font-extrabold text-white text-base tracking-tight leading-snug line-clamp-1" style="color: #ffffff !important;">
                            Emergencias y Contingencias
                        </h3>
                        <p class="text-xs text-slate-200 mt-1 font-medium" style="color: #e2e8f0 !important;">
                            Atención y evacuación
                        </p>
                    </div>
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                        <p class="text-xs text-slate-600 leading-relaxed font-normal">
                            Rutas de evacuación, puntos de encuentro institucionales, plan de contingencias, reporte de incidentes y protocolos de atención inmediata.
                        </p>
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-slate-400 font-semibold">Protocolo</span>
                            <span class="font-bold text-rose-600">Seguridad</span>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- 5. SECCIÓN: Características y Funciones del Sistema -->
        <section id="caracteristicas" class="scroll-mt-28 relative overflow-hidden bg-white rounded-3xl p-8 sm:p-12 border border-slate-200 shadow-sm space-y-8">
            <div class="max-w-3xl">
                <span class="text-xs font-bold text-sena-green uppercase tracking-wider">Innovación y Seguridad</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">¿Por qué usar el sistema SISIG?</h2>
                <p class="text-sm text-slate-500 mt-2">Tecnología diseñada para evaluar y certificar las competencias previas de los aprendices antes de iniciar en SENA Empresa.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <div class="flex gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-sena-green/10 text-sena-green flex items-center justify-center text-xl flex-shrink-0">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-base">Monitoreo Anti-Fraude</h4>
                        <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                            El sistema detecta automáticamente cuando un aprendiz cambia de pestaña, minimiza la ventana o sale del foco de la evaluación.
                        </p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-sena-navy/10 text-sena-navy flex items-center justify-center text-xl flex-shrink-0">
                        <i class="fas fa-stopwatch"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-base">Intentos Definidos por Instructores</h4>
                        <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                            Los instructores tienen el control total para configurar la cantidad de oportunidades que cada aprendiz tiene para aprobar el quiz.
                        </p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-600 flex items-center justify-center text-xl flex-shrink-0">
                        <i class="fas fa-database"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-base">Integración Centralizada SICA</h4>
                        <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                            Validación instantánea contra el módulo principal de SICA para vincular el historial directamente a la cuenta del aprendiz.
                        </p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-blue-600 flex items-center justify-center text-xl flex-shrink-0">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-base">Acceso Multi-Dispositivo</h4>
                        <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                            Diseñado con interfaz responsiva para que los aprendices estudien los materiales y presenten quices desde computadores o dispositivos móviles.
                        </p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center text-xl flex-shrink-0">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-base">Métricas para Instructores</h4>
                        <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                            Panel con estadísticas de cobertura por ficha y programa para verificar qué aprendices están habilitados para la etapa de SENA Empresa.
                        </p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-purple-500/10 text-purple-600 flex items-center justify-center text-xl flex-shrink-0">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-base">Biblioteca Multimedia</h4>
                        <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                            Soporte para incrustar guías PDF institucionales, videos interactivos y diagramas de procesos de SST, Calidad y Medio Ambiente.
                        </p>
                    </div>
                </div>

            </div>
        </section>

        <!-- 6. Flujo Operativo Paso a Paso -->
        <section id="acerca" class="scroll-mt-28 bg-gradient-to-r from-sena-navy to-sena-dark rounded-3xl p-8 sm:p-12 border border-slate-700 text-white space-y-8">
            <div class="max-w-3xl">
                <span class="text-xs font-bold text-sena-green uppercase tracking-wider">Metodología de Preparación</span>
                <h3 class="text-2xl sm:text-3xl font-black text-white mt-1">Paso a Paso del Proceso en SISIG</h3>
                <p class="text-slate-300 text-sm mt-1">Así transcurre la experiencia de un aprendiz antes de incorporarse a SENA Empresa.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-sm space-y-3 relative">
                    <div class="w-10 h-10 rounded-xl bg-sena-green/20 text-sena-green flex items-center justify-center font-black text-lg border border-sena-green/30">
                        1
                    </div>
                    <h4 class="font-bold text-white text-base">Inicio de Sesión</h4>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        El aprendiz ingresa con su usuario y contraseña institucional en la plataforma ERP.
                    </p>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-sm space-y-3 relative">
                    <div class="w-10 h-10 rounded-xl bg-sena-green/20 text-sena-green flex items-center justify-center font-black text-lg border border-sena-green/30">
                        2
                    </div>
                    <h4 class="font-bold text-white text-base">Revisión de Contenidos</h4>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Estudia las guías y material formativo de SST, Calidad y Gestión Ambiental.
                    </p>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-sm space-y-3 relative">
                    <div class="w-10 h-10 rounded-xl bg-sena-green/20 text-sena-green flex items-center justify-center font-black text-lg border border-sena-green/30">
                        3
                    </div>
                    <h4 class="font-bold text-white text-base">Presentación del Quiz</h4>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Responde la evaluación de conocimientos con monitoreo anti-fraude activo.
                    </p>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-sm space-y-3 relative">
                    <div class="w-10 h-10 rounded-xl bg-sena-green/20 text-sena-green flex items-center justify-center font-black text-lg border border-sena-green/30">
                        4
                    </div>
                    <h4 class="font-bold text-white text-base">Certificación & Habilitación</h4>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Demuestra los saberes requeridos para ingresar y desempeñarse con éxito en SENA Empresa.
                    </p>
                </div>

            </div>
        </section>

        <!-- 7. SECCIÓN: Preguntas Frecuentes (FAQ) -->
        <section id="faq" class="scroll-mt-28 space-y-6">
            <div class="border-b border-slate-200 pb-4">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                    <span class="w-3.5 h-8 rounded-full bg-sena-green inline-block"></span>
                    Preguntas Frecuentes (FAQ)
                </h2>
                <p class="text-sm text-slate-500 mt-1">Respuestas a las dudas habituales de aprendices e instructores sobre el sistema SISIG</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-2">
                    <h4 class="font-bold text-slate-900 text-base flex items-center gap-2">
                        <i class="fas fa-question-circle text-sena-green"></i>
                        ¿Quiénes deben presentar la inducción y evaluación de SISIG?
                    </h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Todos los aprendices de tecnólogo que se encuentran en sus últimos trimestres y van a entrar a la etapa de SENA Empresa, con el fin de evaluar y certificar sus conocimientos en SST, Calidad y Gestión Ambiental.
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-2">
                    <h4 class="font-bold text-slate-900 text-base flex items-center gap-2">
                        <i class="fas fa-question-circle text-sena-green"></i>
                        ¿Cuántos intentos tengo para aprobar un quiz?
                    </h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        La cantidad de intentos permitidos es definida directamente por los instructores al momento de crear y configurar cada evaluación en el sistema.
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-2">
                    <h4 class="font-bold text-slate-900 text-base flex items-center gap-2">
                        <i class="fas fa-question-circle text-sena-green"></i>
                        ¿Qué pasa si cambio de pestaña durante la evaluación?
                    </h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        El sistema registra una infracción en la base de datos indicando la hora exacta y el evento. Múltiples infracciones pueden causar la anulación automática del intento.
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-2">
                    <h4 class="font-bold text-slate-900 text-base flex items-center gap-2">
                        <i class="fas fa-question-circle text-sena-green"></i>
                        ¿Dónde consulto mi constancia de inducción?
                    </h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Una vez aprobado el quiz con el puntaje mínimo requerido, podrás visualizar y descargar tu estado de aprobación directamente desde tu perfil de usuario.
                    </p>
                </div>

            </div>
        </section>

        <!-- 8. Banner Final Call to Action -->
        <section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-sena-dark via-sena-navy to-[#002235] border border-sena-green/30 p-8 sm:p-12 text-white shadow-sena-navy flex flex-col sm:flex-row items-center justify-between gap-8">
            <!-- Glow background decorative element -->
            <div class="absolute -right-16 -bottom-16 w-80 h-80 bg-sena-green/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-16 -top-16 w-60 h-60 bg-sena-navy-light/40 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 space-y-3 text-center sm:text-left">
                <div class="inline-flex items-center gap-2 text-sena-green text-xs font-extrabold uppercase tracking-wider">
                    <i class="fas fa-shield-alt text-xs"></i>
                    <span>Inducción SENA Empresa</span>
                </div>
                <h3 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">¿Listo para evaluar tus conocimientos?</h3>
                <p class="text-slate-300 text-sm max-w-xl leading-relaxed">
                    Ingresa con tu cuenta institucional para explorar los módulos interactivos y certificar tus saberes en SST, Calidad y Gestión Ambiental.
                </p>
            </div>
            
            <div class="relative z-10 flex-shrink-0">
                <a href="{{ route('login') }}?redirect=/sisig" class="inline-flex items-center gap-2.5 px-8 py-4 rounded-xl bg-sena-green hover:bg-sena-green-hover text-white text-sm font-extrabold shadow-sena hover:shadow-glow transition-all duration-300 transform hover:-translate-y-0.5">
                    <i class="fas fa-sign-in-alt text-base"></i>
                    <span>Comenzar Ahora</span>
                </a>
            </div>
        </section>

    </div>
</x-sisig::layouts.master>