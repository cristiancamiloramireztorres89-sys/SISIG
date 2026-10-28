<!-- Sidebar Lateral Completo para SISIG (Estilo Oficial: Dark Navy + Active Pill Neon Green) -->
<aside id="sidebar-menu" style="background-color: #1A2B4C;" class="w-64 border-r border-[#243B66]/60 text-slate-200 flex-shrink-0 flex flex-col justify-between h-screen sticky top-0 transition-all duration-300 z-40">
    
    <!-- 1. Encabezado Superior del Sidebar (Logo Circular Blanco + Identidad SISIG) -->
    <div class="h-20 px-4 flex-shrink-0 flex items-center justify-start sidebar-header-container transition-all border-b border-[#243B66]/50">
        <div class="flex items-center gap-3 overflow-hidden w-full select-none sidebar-header-inner">
            <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center p-1.5 shadow-sm flex-shrink-0 sidebar-logo-box">
                <img src="{{ asset('modules/sisig/images/logo.png') }}" alt="SENA Empresa Logo" class="w-full h-full object-contain filter drop-shadow-xs">
            </div>
            <div class="min-w-0 sidebar-text transition-all duration-200">
                <span class="text-base font-black tracking-tight text-white block leading-tight">SISIG</span>
                <span class="text-[10px] font-bold text-[#33FF57] tracking-wider uppercase block leading-none mt-0.5">SENA Empresa</span>
            </div>
        </div>
    </div>

    <!-- 2. Navegación Principal (Scrollable) -->
    <div class="flex-1 overflow-y-auto p-3 space-y-4" id="sidebar-nav">
        
        @if(auth()->check() && auth()->user()->hasRole('admin_sisig'))
            <!-- VISTA ADMINISTRADOR SISIG -->
            <div class="sidebar-section-block">
                <span class="sidebar-section-title px-3 text-[10px] font-black tracking-wider text-slate-400 uppercase">
                    Menú Principal
                </span>
                <div class="sidebar-divider hidden my-1.5 border-t border-[#243B66]/60"></div>
                <div class="mt-1 space-y-1">
                    <a href="{{ route('sisig.admin.dashboard') }}" 
                       title="Dashboard Administrador"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs font-bold transition-all duration-200 group {{ request()->routeIs('sisig.admin.dashboard') ? 'bg-[#33FF57] text-[#1A2B4C] font-black shadow-md shadow-emerald-500/20' : 'hover:bg-white/10 hover:text-white text-slate-200' }}">
                        <i class="fas fa-chart-line text-sm w-5 text-center flex-shrink-0 {{ request()->routeIs('sisig.admin.dashboard') ? 'text-[#1A2B4C]' : 'text-slate-200' }}"></i>
                        <span class="sidebar-text truncate">Dashboard Administrador</span>
                    </a>
                </div>
            </div>

            <div class="sidebar-section-block">
                <span class="sidebar-section-title px-3 text-[10px] font-black tracking-wider text-slate-400 uppercase">
                    Módulos de Gestión
                </span>
                <div class="sidebar-divider hidden my-1.5 border-t border-[#243B66]/60"></div>
                <div class="mt-1 space-y-1">
                    <a href="{{ route('sisig.admin.users.index') }}" 
                       title="Gestión de Usuarios"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs font-semibold transition-all group {{ request()->routeIs('sisig.admin.users.*') ? 'bg-[#33FF57] text-[#1A2B4C] font-black shadow-md shadow-emerald-500/20' : 'hover:bg-white/10 hover:text-white text-slate-200' }}">
                        <i class="fas fa-users-cog text-sm w-5 text-center flex-shrink-0 {{ request()->routeIs('sisig.admin.users.*') ? 'text-[#1A2B4C]' : 'text-slate-200' }}"></i>
                        <span class="sidebar-text truncate">Gestión de Usuarios</span>
                    </a>

                    <a href="{{ route('sisig.admin.modulos.index') }}" 
                       title="Gestión de Módulos"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs font-semibold transition-all group {{ request()->routeIs('sisig.admin.modulos.*') ? 'bg-[#33FF57] text-[#1A2B4C] font-black shadow-md shadow-emerald-500/20' : 'hover:bg-white/10 hover:text-white text-slate-200' }}">
                        <i class="fas fa-layer-group text-sm w-5 text-center flex-shrink-0 {{ request()->routeIs('sisig.admin.modulos.*') ? 'text-[#1A2B4C]' : 'text-slate-200' }}"></i>
                        <span class="sidebar-text truncate">Gestión de Módulos</span>
                    </a>

                    <a href="{{ route('sisig.admin.examenes.index') }}" 
                       title="Gestión de Exámenes"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs font-semibold transition-all group {{ request()->routeIs('sisig.admin.examenes.*') ? 'bg-[#33FF57] text-[#1A2B4C] font-black shadow-md shadow-emerald-500/20' : 'hover:bg-white/10 hover:text-white text-slate-200' }}">
                        <i class="fas fa-clipboard-check text-sm w-5 text-center flex-shrink-0 {{ request()->routeIs('sisig.admin.examenes.*') ? 'text-[#1A2B4C]' : 'text-slate-200' }}"></i>
                        <span class="sidebar-text truncate">Gestión de Exámenes</span>
                    </a>

                    <a href="{{ route('sisig.admin.reportes.index') }}" 
                       title="Reportes y Métricas"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs font-semibold transition-all group {{ request()->routeIs('sisig.admin.reportes.*') ? 'bg-[#33FF57] text-[#1A2B4C] font-black shadow-md shadow-emerald-500/20' : 'hover:bg-white/10 hover:text-white text-slate-200' }}">
                        <i class="fas fa-file-excel text-sm w-5 text-center flex-shrink-0 {{ request()->routeIs('sisig.admin.reportes.*') ? 'text-[#1A2B4C]' : 'text-slate-200' }}"></i>
                        <span class="sidebar-text truncate">Reportes y Métricas</span>
                    </a>

                    <a href="{{ route('sisig.admin.seguridad.index') }}" 
                       title="Seguridad y Accesos"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs font-semibold transition-all group {{ request()->routeIs('sisig.admin.seguridad.*') ? 'bg-[#33FF57] text-[#1A2B4C] font-black shadow-md shadow-emerald-500/20' : 'hover:bg-white/10 hover:text-white text-slate-200' }}">
                        <i class="fas fa-shield-alt text-sm w-5 text-center flex-shrink-0 {{ request()->routeIs('sisig.admin.seguridad.*') ? 'text-[#1A2B4C]' : 'text-slate-200' }}"></i>
                        <span class="sidebar-text truncate">Seguridad y Accesos</span>
                    </a>
                </div>
            </div>

        @elseif(auth()->check() && auth()->user()->hasRole('editor_sisig'))
            <!-- VISTA EDITOR DE CONTENIDOS SISIG -->
            <div class="sidebar-section-block">
                <span class="sidebar-section-title px-3 text-[10px] font-black tracking-wider text-slate-400 uppercase">
                    Menú Principal
                </span>
                <div class="sidebar-divider hidden my-1.5 border-t border-[#243B66]/60"></div>
                <div class="mt-1 space-y-1">
                    <a href="{{ route('sisig.editor.dashboard') }}" 
                       title="Dashboard Editor"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs font-bold transition-all duration-200 group {{ request()->routeIs('sisig.editor.dashboard') ? 'bg-[#33FF57] text-[#1A2B4C] font-black shadow-md shadow-emerald-500/20' : 'hover:bg-white/10 hover:text-white text-slate-200' }}">
                        <i class="fas fa-edit text-sm w-5 text-center flex-shrink-0 {{ request()->routeIs('sisig.editor.dashboard') ? 'text-[#1A2B4C]' : 'text-slate-200' }}"></i>
                        <span class="sidebar-text truncate">Dashboard Editor</span>
                    </a>
                </div>
            </div>

            <!-- SECCIÓN 2: GESTIÓN DE CONTENIDOS Y EVALUACIONES -->
            <div class="sidebar-section-block">
                <span class="sidebar-section-title px-3 text-[10px] font-black tracking-wider text-slate-400 uppercase">
                    Gestión Editorial
                </span>
                <div class="sidebar-divider hidden my-1.5 border-t border-[#243B66]/60"></div>
                <div class="mt-1 space-y-1">
                    <a href="{{ route('sisig.editor.contenido.index') }}" 
                       title="Gestión de Contenidos"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs font-semibold transition-all group {{ request()->routeIs('sisig.editor.contenido.*') ? 'bg-[#33FF57] text-[#1A2B4C] font-black shadow-md shadow-emerald-500/20' : 'hover:bg-white/10 hover:text-white text-slate-200' }}">
                        <i class="fas fa-layer-group text-sm w-5 text-center flex-shrink-0 {{ request()->routeIs('sisig.editor.contenido.*') ? 'text-[#1A2B4C]' : 'text-slate-200' }}"></i>
                        <span class="sidebar-text truncate">Gestión de Contenidos</span>
                    </a>     

                    <a href="{{ route('sisig.editor.examenes.index') }}" 
                       title="Gestión de Exámenes"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs font-semibold transition-all group {{ request()->routeIs('sisig.editor.examenes.*') ? 'bg-[#33FF57] text-[#1A2B4C] font-black shadow-md shadow-emerald-500/20' : 'hover:bg-white/10 hover:text-white text-slate-200' }}">
                        <i class="fas fa-clipboard-check text-sm w-5 text-center flex-shrink-0 {{ request()->routeIs('sisig.editor.examenes.*') ? 'text-[#1A2B4C]' : 'text-slate-200' }}"></i>
                        <span class="sidebar-text truncate">Gestión de Exámenes</span>
                    </a>

                    <a href="{{ route('sisig.editor.participantes.index') }}" 
                       title="Participantes"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs font-semibold transition-all group {{ request()->routeIs('sisig.editor.participantes.*') ? 'bg-[#33FF57] text-[#1A2B4C] font-black shadow-md shadow-emerald-500/20' : 'hover:bg-white/10 hover:text-white text-slate-200' }}">
                        <i class="fas fa-users text-sm w-5 text-center flex-shrink-0 {{ request()->routeIs('sisig.editor.participantes.*') ? 'text-[#1A2B4C]' : 'text-slate-200' }}"></i>
                        <span class="sidebar-text truncate">Participantes</span>
                    </a>

                    <a href="{{ route('sisig.editor.reportes.index') }}" 
                       title="Reportes"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs font-semibold transition-all group {{ request()->routeIs('sisig.editor.reportes.*') ? 'bg-[#33FF57] text-[#1A2B4C] font-black shadow-md shadow-emerald-500/20' : 'hover:bg-white/10 hover:text-white text-slate-200' }}">
                        <i class="fas fa-chart-line text-sm w-5 text-center flex-shrink-0 {{ request()->routeIs('sisig.editor.reportes.*') ? 'text-[#1A2B4C]' : 'text-slate-200' }}"></i>
                        <span class="sidebar-text truncate">Reportes</span>
                    </a>

                    <a href="{{ route('sisig.editor.infracciones.index') }}" 
                       title="Infracciones"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs font-semibold transition-all group {{ request()->routeIs('sisig.editor.infracciones.*') ? 'bg-[#33FF57] text-[#1A2B4C] font-black shadow-md shadow-emerald-500/20' : 'hover:bg-white/10 hover:text-white text-slate-200' }}">
                        <i class="fas fa-shield-alt text-sm w-5 text-center flex-shrink-0 {{ request()->routeIs('sisig.editor.infracciones.*') ? 'text-[#1A2B4C]' : 'text-slate-200' }}"></i>
                        <span class="sidebar-text truncate">Infracciones</span>
                    </a>
                </div>
            </div>

        @else
            <!-- VISTA DEL APRENDIZ / USUARIO ESTUDIANTE -->
            <div class="sidebar-section-block">
                <span class="sidebar-section-title px-3 text-[10px] font-black tracking-wider text-slate-400 uppercase">
                    Mi Formación
                </span>
                <div class="sidebar-divider hidden my-1.5 border-t border-[#243B66]/60"></div>
                <div class="mt-1 space-y-1">
                    <a href="{{ route('sisig.aprendiz.dashboard') }}" 
                       title="Mi Dashboard SIG"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs font-bold transition-all duration-200 group {{ request()->routeIs('sisig.aprendiz.dashboard') ? 'bg-[#33FF57] text-[#1A2B4C] font-black shadow-md shadow-emerald-500/20' : 'hover:bg-white/10 hover:text-white text-slate-200' }}">
                        <i class="fas fa-graduation-cap text-sm w-5 text-center flex-shrink-0 {{ request()->routeIs('sisig.aprendiz.dashboard') ? 'text-[#1A2B4C]' : 'text-slate-200' }}"></i>
                        <span class="sidebar-text truncate">Mi Dashboard SIG</span>
                    </a>
                </div>
            </div>

            <div class="sidebar-section-block">
                <span class="sidebar-section-title px-3 text-[10px] font-black tracking-wider text-slate-400 uppercase">
                    Inducción y Pruebas
                </span>
                <div class="sidebar-divider hidden my-1.5 border-t border-[#243B66]/60"></div>
                <div class="mt-1 space-y-1">
                    <a href="{{ route('sisig.aprendiz.induccion.index') }}" 
                       title="Módulos de Inducción"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs font-semibold transition-all group {{ request()->routeIs('sisig.aprendiz.induccion.*') ? 'bg-[#33FF57] text-[#1A2B4C] font-black shadow-md shadow-emerald-500/20' : 'hover:bg-white/10 hover:text-white text-slate-200' }}">
                        <i class="fas fa-book-reader text-sm w-5 text-center flex-shrink-0 {{ request()->routeIs('sisig.aprendiz.induccion.*') ? 'text-[#1A2B4C]' : 'text-slate-200' }}"></i>
                        <span class="sidebar-text truncate">Módulos de Inducción</span>
                    </a>

                    <a href="{{ route('sisig.aprendiz.evaluaciones.index') }}" 
                       title="Mis Evaluaciones"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs font-semibold transition-all group {{ request()->routeIs('sisig.aprendiz.evaluaciones.*') ? 'bg-[#33FF57] text-[#1A2B4C] font-black shadow-md shadow-emerald-500/20' : 'hover:bg-white/10 hover:text-white text-slate-200' }}">
                        <i class="fas fa-tasks text-sm w-5 text-center flex-shrink-0 {{ request()->routeIs('sisig.aprendiz.evaluaciones.*') ? 'text-[#1A2B4C]' : 'text-slate-200' }}"></i>
                        <span class="sidebar-text truncate">Mis Evaluaciones</span>
                    </a>

                    <a href="{{ route('sisig.aprendiz.historial.index') }}" 
                       title="Historial y Calificaciones"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs font-semibold transition-all group {{ request()->routeIs('sisig.aprendiz.historial.*') ? 'bg-[#33FF57] text-[#1A2B4C] font-black shadow-md shadow-emerald-500/20' : 'hover:bg-white/10 hover:text-white text-slate-200' }}">
                        <i class="fas fa-award text-sm w-5 text-center flex-shrink-0 {{ request()->routeIs('sisig.aprendiz.historial.*') ? 'text-[#1A2B4C]' : 'text-slate-200' }}"></i>
                        <span class="sidebar-text truncate">Historial y Calificaciones</span>
                    </a>
                </div>
            </div>
        @endif

    </div>

</aside>