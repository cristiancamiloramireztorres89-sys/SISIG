<!-- Sidebar Lateral Completo para SISIG (Modo Completo y Modo Mini-Iconos) -->
<aside id="sidebar-menu" class="w-64 bg-[#001A29] border-r border-slate-800 text-slate-300 flex-shrink-0 flex flex-col justify-between h-screen sticky top-0 transition-all duration-300 z-40">
    
    <!-- 1. Encabezado Superior del Sidebar (Alineado con el Header h-16 = 64px) -->
    <div class="h-16 px-4 flex-shrink-0 flex items-center justify-start sidebar-header-container transition-all">
        <a href="{{ route('sisig.index') }}" class="flex items-center gap-3 group overflow-hidden w-full" title="Ir al Portal SISIG">
            <div class="w-9 h-9 flex items-center justify-center group-hover:scale-105 transition-transform duration-300 flex-shrink-0">
                <img src="{{ asset('modules/sisig/images/logo.png') }}" alt="SENA Empresa Logo" class="w-full h-full object-contain filter drop-shadow">
            </div>
            <div class="min-w-0 sidebar-text transition-all duration-200">
                <span class="text-lg font-extrabold tracking-tight text-white block leading-tight">SISIG</span>
            </div>
        </a>
    </div>

    <!-- 2. Navegación Principal (Scrollable) -->
    <div class="flex-1 overflow-y-auto p-3 space-y-4" id="sidebar-nav">
        
        @if(auth()->check() && auth()->user()->hasRole('admin_sisig'))
            <!-- VISTA ADMINISTRADOR SISIG -->
            <div class="sidebar-section-block">
                <span class="sidebar-section-title px-3 text-[10px] font-extrabold tracking-wider text-slate-400 uppercase">
                    Menú Principal
                </span>
                <div class="sidebar-divider hidden my-1.5 border-t border-slate-800/80"></div>
                <div class="mt-1 space-y-1">
                    <a href="{{ route('sisig.admin.dashboard') }}" 
                       title="Dashboard Administrador"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('sisig.admin.dashboard') ? 'bg-sena-green text-white shadow-sena' : 'hover:bg-slate-800/80 hover:text-white text-slate-300' }}">
                        <i class="fas fa-chart-line text-sm w-5 text-center flex-shrink-0"></i>
                        <span class="sidebar-text truncate">Dashboard Administrador</span>
                    </a>
                </div>
            </div>

            <div class="sidebar-section-block">
                <span class="sidebar-section-title px-3 text-[10px] font-extrabold tracking-wider text-slate-400 uppercase">
                    Módulos de Gestión
                </span>
                <div class="sidebar-divider hidden my-1.5 border-t border-slate-800/80"></div>
                <div class="mt-1 space-y-1">
                    <a href="{{ route('sisig.admin.users.index') }}" 
                       title="Gestión de Usuarios"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium transition-all group {{ request()->routeIs('sisig.admin.users.*') ? 'bg-sena-green text-white shadow-sena' : 'hover:bg-slate-800/80 hover:text-white text-slate-300' }}">
                        <i class="fas fa-users-cog text-sm w-5 text-center flex-shrink-0 {{ request()->routeIs('sisig.admin.users.*') ? 'text-white' : 'text-indigo-400 group-hover:scale-110' }} transition-transform"></i>
                        <span class="sidebar-text truncate">Gestión de Usuarios</span>
                    </a>

                    <a href="{{ route('sisig.admin.modulos.index') }}" 
                       title="Gestión de Módulos"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium transition-all group {{ request()->routeIs('sisig.admin.modulos.*') ? 'bg-sena-green text-white shadow-sena' : 'hover:bg-slate-800/80 hover:text-white text-slate-300' }}">
                        <i class="fas fa-layer-group text-sm w-5 text-center flex-shrink-0 transition-transform {{ request()->routeIs('sisig.admin.modulos.*') ? 'text-white' : 'text-emerald-400 group-hover:scale-110' }}"></i>
                        <span class="sidebar-text truncate">Gestión de Módulos</span>
                    </a>

                    <a href="{{ route('sisig.admin.examenes.index') }}" 
                       title="Gestión de Exámenes"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium transition-all group {{ request()->routeIs('sisig.admin.examenes.*') ? 'bg-sena-green text-white shadow-sena' : 'hover:bg-slate-800/80 hover:text-white text-slate-300' }}">
                        <i class="fas fa-clipboard-check text-sm w-5 text-center flex-shrink-0 transition-transform {{ request()->routeIs('sisig.admin.examenes.*') ? 'text-white' : 'text-sky-400 group-hover:scale-110' }}"></i>
                        <span class="sidebar-text truncate">Gestión de Exámenes</span>
                    </a>

                    <a href="#reportes" 
                       title="Reportes y Métricas"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium hover:bg-slate-800/80 hover:text-white transition-all text-slate-300 group">
                        <i class="fas fa-file-excel text-sm w-5 text-center flex-shrink-0 text-teal-400 group-hover:scale-110 transition-transform"></i>
                        <span class="sidebar-text truncate">Reportes y Métricas</span>
                    </a>

                    <a href="#seguridad" 
                       title="Seguridad e Infracciones"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium hover:bg-slate-800/80 hover:text-white transition-all text-slate-300 group">
                        <i class="fas fa-shield-alt text-sm w-5 text-center flex-shrink-0 text-amber-400 group-hover:scale-110 transition-transform"></i>
                        <span class="sidebar-text truncate">Seguridad e Infracciones</span>
                    </a>
                </div>
            </div>

        @elseif(auth()->check() && auth()->user()->hasRole('editor_sisig'))
            <!-- VISTA EDITOR DE CONTENIDOS SISIG -->
            <div class="sidebar-section-block">
                <span class="sidebar-section-title px-3 text-[10px] font-extrabold tracking-wider text-slate-400 uppercase">
                    Menú Principal
                </span>
                <div class="sidebar-divider hidden my-1.5 border-t border-slate-800/80"></div>
                <div class="mt-1 space-y-1">
                    <a href="{{ route('sisig.editor.dashboard') }}" 
                       title="Dashboard Editor"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('sisig.editor.dashboard') ? 'bg-sena-green text-white shadow-sena' : 'hover:bg-slate-800/80 hover:text-white text-slate-300' }}">
                        <i class="fas fa-edit text-sm w-5 text-center flex-shrink-0"></i>
                        <span class="sidebar-text truncate">Dashboard Editor</span>
                    </a>
                </div>
            </div>

            <!-- SECCIÓN 2: GESTIÓN DE CONTENIDOS Y EVALUACIONES -->
            <div class="sidebar-section-block">
                <span class="sidebar-section-title px-3 text-[10px] font-extrabold tracking-wider text-slate-400 uppercase">
                    Gestión Editorial
                </span>
                <div class="sidebar-divider hidden my-1.5 border-t border-slate-800/80"></div>
                <div class="mt-1 space-y-1">
                    <a href="{{ route('sisig.editor.contenido.index') }}"
                       title="Gestión de Contenidos"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium transition-all group {{ request()->routeIs('sisig.editor.contenido.*') ? 'bg-sena-green text-white shadow-sena' : 'hover:bg-slate-800/80 hover:text-white text-slate-300' }}">
                        <i class="fas fa-layer-group text-sm w-5 text-center flex-shrink-0 transition-transform {{ request()->routeIs('sisig.editor.contenido.*') ? 'text-white' : 'text-sky-400 group-hover:scale-110' }}"></i>
                        <span class="sidebar-text truncate">Gestión de Contenidos</span>
                    </a>     

                    <a href="{{ route('sisig.editor.examenes.index') }}" 
                       title="Gestión de Exámenes"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium transition-all group {{ request()->routeIs('sisig.editor.examenes.*') ? 'bg-sena-green text-white shadow-sena' : 'hover:bg-slate-800/80 hover:text-white text-slate-300' }}">
                        <i class="fas fa-clipboard-check text-sm w-5 text-center flex-shrink-0 transition-transform {{ request()->routeIs('sisig.editor.examenes.*') ? 'text-white' : 'text-sky-400 group-hover:scale-110' }}"></i>
                        <span class="sidebar-text truncate">Gestión de Exámenes</span>
                    </a>

                    <a href="{{ route('sisig.editor.participantes.index') }}" 
                       title="Participantes"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium transition-all group {{ request()->routeIs('sisig.editor.participantes.*') ? 'bg-sena-green text-white shadow-sena' : 'hover:bg-slate-800/80 hover:text-white text-slate-300' }}">
                        <i class="fas fa-users text-sm w-5 text-center flex-shrink-0 transition-transform {{ request()->routeIs('sisig.editor.participantes.*') ? 'text-white' : 'text-emerald-400 group-hover:scale-110' }}"></i>
                        <span class="sidebar-text truncate">Participantes</span>
                    </a>

                    <a href="{{ route('sisig.editor.reportes.index') }}" 
                       title="Reportes"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium transition-all group {{ request()->routeIs('sisig.editor.reportes.*') ? 'bg-sena-green text-white shadow-sena' : 'hover:bg-slate-800/80 hover:text-white text-slate-300' }}">
                        <i class="fas fa-chart-line text-sm w-5 text-center flex-shrink-0 transition-transform {{ request()->routeIs('sisig.editor.reportes.*') ? 'text-white' : 'text-emerald-400 group-hover:scale-110' }}"></i>
                        <span class="sidebar-text truncate">Reportes</span>
                    </a>
                </div>
            </div>

        @else
            <!-- VISTA DEL APRENDIZ / USUARIO ESTUDIANTE -->
            <div class="sidebar-section-block">
                <span class="sidebar-section-title px-3 text-[10px] font-extrabold tracking-wider text-slate-400 uppercase">
                    Mi Formación
                </span>
                <div class="sidebar-divider hidden my-1.5 border-t border-slate-800/80"></div>
                <div class="mt-1 space-y-1">
                    <a href="{{ route('sisig.aprendiz.dashboard') }}" 
                       title="Mi Dashboard SIG"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('sisig.aprendiz.dashboard') ? 'bg-sena-green text-white shadow-sena' : 'hover:bg-slate-800/80 hover:text-white text-slate-300' }}">
                        <i class="fas fa-graduation-cap text-sm w-5 text-center flex-shrink-0"></i>
                        <span class="sidebar-text truncate">Mi Dashboard SIG</span>
                    </a>
                </div>
            </div>

            <div class="sidebar-section-block">
                <span class="sidebar-section-title px-3 text-[10px] font-extrabold tracking-wider text-slate-400 uppercase">
                    Inducción y Pruebas
                </span>
                <div class="sidebar-divider hidden my-1.5 border-t border-slate-800/80"></div>
                <div class="mt-1 space-y-1">
                    <a href="#" 
                       title="Módulos de Inducción"
                       onclick="Swal.fire({ title: 'Módulos de Inducción', text: 'Esta sección está en desarrollo por el equipo.', icon: 'info', confirmButtonColor: '#39A900' })"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium hover:bg-slate-800/80 hover:text-white transition-all text-slate-300 group">
                        <i class="fas fa-book-reader text-sm w-5 text-center flex-shrink-0 text-emerald-400 group-hover:scale-110 transition-transform"></i>
                        <span class="sidebar-text truncate">Módulos de Inducción</span>
                    </a>

                    <a href="#" 
                       title="Mis Evaluaciones"
                       onclick="Swal.fire({ title: 'Mis Evaluaciones', text: 'Esta sección está en desarrollo por el equipo.', icon: 'info', confirmButtonColor: '#39A900' })"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium hover:bg-slate-800/80 hover:text-white transition-all text-slate-300 group">
                        <i class="fas fa-tasks text-sm w-5 text-center flex-shrink-0 text-sky-400 group-hover:scale-110 transition-transform"></i>
                        <span class="sidebar-text truncate">Mis Evaluaciones</span>
                    </a>

                    <a href="#" 
                       title="Historial y Calificaciones"
                       onclick="Swal.fire({ title: 'Historial y Calificaciones', text: 'Esta sección está en desarrollo por el equipo.', icon: 'info', confirmButtonColor: '#39A900' })"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium hover:bg-slate-800/80 hover:text-white transition-all text-slate-300 group">
                        <i class="fas fa-award text-sm w-5 text-center flex-shrink-0 text-amber-400 group-hover:scale-110 transition-transform"></i>
                        <span class="sidebar-text truncate">Historial y Calificaciones</span>
                    </a>
                </div>
            </div>
        @endif

    </div>

    <!-- 3. Footer del Sidebar: Cerrar Sesión (Bloque continuo sin líneas) -->
    <div class="p-3 flex-shrink-0 sidebar-footer-container transition-all">
        @if(auth()->check())
            <a href="{{ route('logout') }}?redirect=/sisig" 
               title="Cerrar Sesión"
               class="flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl text-xs font-bold text-rose-300 hover:text-white bg-rose-950/40 hover:bg-rose-900/60 border border-rose-800/50 transition-all duration-200 group shadow-sm w-full">
                <i class="fas fa-sign-out-alt text-xs text-rose-400 group-hover:translate-x-0.5 transition-transform flex-shrink-0"></i>
                <span class="sidebar-text truncate">Cerrar Sesión</span>
            </a>
        @else
            <a href="{{ route('login') }}?redirect=/sisig" 
               title="Iniciar Sesión"
               class="flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl text-xs font-bold text-white bg-sena-green hover:bg-sena-green-hover transition-all duration-200 group shadow-sm w-full">
                <i class="fas fa-sign-in-alt text-xs flex-shrink-0"></i>
                <span class="sidebar-text truncate">Iniciar Sesión</span>
            </a>
        @endif
    </div>

</aside>