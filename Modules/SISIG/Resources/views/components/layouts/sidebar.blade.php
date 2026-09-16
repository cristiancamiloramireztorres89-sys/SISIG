<!-- Sidebar Lateral Completo para SISIG (Desde arriba h-screen) -->
<aside id="sidebar-menu" class="w-64 bg-[#001A29] border-r border-slate-800 text-slate-300 flex-shrink-0 flex flex-col justify-between h-screen sticky top-0 transition-all duration-300 z-40">
    
    <!-- 1. Encabezado Superior del Sidebar: Logo & Marca (Bloque continuo sin líneas) -->
    <div class="p-4 sm:p-5 flex-shrink-0">
        <a href="{{ route('sisig.index') }}" class="flex items-center gap-3 group">
            <div class="w-10 h-10 flex items-center justify-center group-hover:scale-105 transition-transform duration-300 flex-shrink-0">
                <img src="{{ asset('modules/sisig/images/logo.png') }}" alt="SENA Empresa Logo" class="w-full h-full object-contain filter drop-shadow">
            </div>
            <div class="min-w-0">
                <span class="text-xl font-extrabold tracking-tight text-white block leading-tight">SISIG</span>
            </div>
        </a>
    </div>

    <!-- 2. Navegación Principal (Scrollable) -->
    <div class="flex-1 overflow-y-auto p-4 space-y-5" id="sidebar-nav">
        
        @if(auth()->check() && auth()->user()->hasRole('admin_sisig'))
            <!-- VISTA ADMINISTRADOR SISIG -->
            <div>
                <span class="px-3 text-[10px] font-extrabold tracking-wider text-slate-400 uppercase">
                    Menú Principal
                </span>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('sisig.admin.dashboard') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('sisig.admin.dashboard') ? 'bg-sena-green text-white shadow-sena' : 'hover:bg-slate-800/80 hover:text-white text-slate-300' }}">
                        <i class="fas fa-chart-line text-sm w-5 text-center"></i>
                        <span>Dashboard Administrador</span>
                    </a>
                </div>
            </div>

            <div>
                <span class="px-3 text-[10px] font-extrabold tracking-wider text-slate-400 uppercase">
                    Módulos de Gestión
                </span>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('sisig.admin.users.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-medium transition-all group {{ request()->routeIs('sisig.admin.users.*') ? 'bg-sena-green text-white shadow-sena' : 'hover:bg-slate-800/80 hover:text-white text-slate-300' }}">
                        <i class="fas fa-users-cog text-sm w-5 text-center {{ request()->routeIs('sisig.admin.users.*') ? 'text-white' : 'text-indigo-400 group-hover:scale-110' }} transition-transform"></i>
                        <span>Gestión de Usuarios</span>
                    </a>

                    <a href="#gestion-modulos" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-medium hover:bg-slate-800/80 hover:text-white transition-all text-slate-300 group">
                        <i class="fas fa-layer-group text-sm w-5 text-center text-emerald-400 group-hover:scale-110 transition-transform"></i>
                        <span>Gestión de Módulos</span>
                    </a>

                    <a href="#seguimiento-examenes" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-medium hover:bg-slate-800/80 hover:text-white transition-all text-slate-300 group">
                        <i class="fas fa-clipboard-check text-sm w-5 text-center text-sky-400 group-hover:scale-110 transition-transform"></i>
                        <span>Seguimiento Exámenes</span>
                    </a>

                    <a href="#reportes" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-medium hover:bg-slate-800/80 hover:text-white transition-all text-slate-300 group">
                        <i class="fas fa-file-excel text-sm w-5 text-center text-teal-400 group-hover:scale-110 transition-transform"></i>
                        <span>Reportes y Métricas</span>
                    </a>

                    <a href="#seguridad" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-medium hover:bg-slate-800/80 hover:text-white transition-all text-slate-300 group">
                        <i class="fas fa-shield-alt text-sm w-5 text-center text-amber-400 group-hover:scale-110 transition-transform"></i>
                        <span>Seguridad e Infracciones</span>
                    </a>
                </div>
            </div>

        @elseif(auth()->check() && auth()->user()->hasRole('editor_sisig'))
            <!-- VISTA EDITOR DE CONTENIDOS SISIG -->
            <div>
                <span class="px-3 text-[10px] font-extrabold tracking-wider text-slate-400 uppercase">
                    Menú Principal
                </span>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('sisig.editor.dashboard') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('sisig.editor.dashboard') ? 'bg-sena-green text-white shadow-sena' : 'hover:bg-slate-800/80 hover:text-white text-slate-300' }}">
                        <i class="fas fa-edit text-sm w-5 text-center"></i>
                        <span>Dashboard Editor</span>
                    </a>
                </div>
            </div>

            <div>
                <span class="px-3 text-[10px] font-extrabold tracking-wider text-slate-400 uppercase">
                    Gestión Editorial
                </span>
                <div class="mt-2 space-y-1">
                    <a href="#" 
                       onclick="Swal.fire({ title: 'Gestión de Módulos', text: 'Módulo en desarrollo por el equipo.', icon: 'info', confirmButtonColor: '#39A900' })"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-medium hover:bg-slate-800/80 hover:text-white transition-all text-slate-300 group">
                        <i class="fas fa-layer-group text-sm w-5 text-center text-sky-400 group-hover:scale-110 transition-transform"></i>
                        <span>Módulos Temáticos</span>
                    </a>

                    <a href="#" 
                       onclick="Swal.fire({ title: 'Recursos Didácticos', text: 'Módulo en desarrollo por el equipo.', icon: 'info', confirmButtonColor: '#39A900' })"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-medium hover:bg-slate-800/80 hover:text-white transition-all text-slate-300 group">
                        <i class="fas fa-file-alt text-sm w-5 text-center text-emerald-400 group-hover:scale-110 transition-transform"></i>
                        <span>Recursos y Materiales</span>
                    </a>

                    <a href="#" 
                       onclick="Swal.fire({ title: 'Banco de Preguntas', text: 'Módulo en desarrollo por el equipo.', icon: 'info', confirmButtonColor: '#39A900' })"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-medium hover:bg-slate-800/80 hover:text-white transition-all text-slate-300 group">
                        <i class="fas fa-tasks text-sm w-5 text-center text-teal-400 group-hover:scale-110 transition-transform"></i>
                        <span>Quices y Preguntas</span>
                    </a>
                </div>
            </div>

        @else
            <!-- VISTA DEL APRENDIZ / USUARIO ESTUDIANTE -->
            <div>
                <span class="px-3 text-[10px] font-extrabold tracking-wider text-slate-400 uppercase">
                    Mi Formación
                </span>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('sisig.aprendiz.dashboard') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('sisig.aprendiz.dashboard') ? 'bg-sena-green text-white shadow-sena' : 'hover:bg-slate-800/80 hover:text-white text-slate-300' }}">
                        <i class="fas fa-graduation-cap text-sm w-5 text-center"></i>
                        <span>Mi Dashboard SIG</span>
                    </a>
                </div>
            </div>

            <div>
                <span class="px-3 text-[10px] font-extrabold tracking-wider text-slate-400 uppercase">
                    Inducción y Pruebas
                </span>
                <div class="mt-2 space-y-1">
                    <a href="#" 
                       onclick="Swal.fire({ title: 'Módulos de Inducción', text: 'Esta sección está en desarrollo por el equipo.', icon: 'info', confirmButtonColor: '#39A900' })"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-medium hover:bg-slate-800/80 hover:text-white transition-all text-slate-300 group">
                        <i class="fas fa-book-reader text-sm w-5 text-center text-emerald-400 group-hover:scale-110 transition-transform"></i>
                        <span>Módulos de Inducción</span>
                    </a>

                    <a href="#" 
                       onclick="Swal.fire({ title: 'Mis Evaluaciones', text: 'Esta sección está en desarrollo por el equipo.', icon: 'info', confirmButtonColor: '#39A900' })"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-medium hover:bg-slate-800/80 hover:text-white transition-all text-slate-300 group">
                        <i class="fas fa-tasks text-sm w-5 text-center text-sky-400 group-hover:scale-110 transition-transform"></i>
                        <span>Mis Evaluaciones</span>
                    </a>

                    <a href="#" 
                       onclick="Swal.fire({ title: 'Historial y Calificaciones', text: 'Esta sección está en desarrollo por el equipo.', icon: 'info', confirmButtonColor: '#39A900' })"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-medium hover:bg-slate-800/80 hover:text-white transition-all text-slate-300 group">
                        <i class="fas fa-award text-sm w-5 text-center text-amber-400 group-hover:scale-110 transition-transform"></i>
                        <span>Historial y Calificaciones</span>
                    </a>
                </div>
            </div>
        @endif

    </div>

    <!-- 3. Footer del Sidebar: Cerrar Sesión (Bloque continuo sin líneas) -->
    <div class="p-4 flex-shrink-0">
        @if(auth()->check())
            <a href="{{ route('logout') }}?redirect=/sisig" 
               class="flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl text-xs font-bold text-rose-300 hover:text-white bg-rose-950/40 hover:bg-rose-900/60 border border-rose-800/50 transition-all duration-200 group shadow-sm w-full">
                <i class="fas fa-sign-out-alt text-xs text-rose-400 group-hover:translate-x-0.5 transition-transform"></i>
                <span>Cerrar Sesión</span>
            </a>
        @else
            <a href="{{ route('login') }}?redirect=/sisig" 
               class="flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl text-xs font-bold text-white bg-sena-green hover:bg-sena-green-hover transition-all duration-200 group shadow-sm w-full">
                <i class="fas fa-sign-in-alt text-xs"></i>
                <span>Iniciar Sesión</span>
            </a>
        @endif
    </div>

</aside>
