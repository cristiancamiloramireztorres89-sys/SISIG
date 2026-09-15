<!-- Sidebar Lateral Adaptativo para SISIG (Roles: Administrador, Editor y Aprendiz) -->
<aside id="sidebar-menu" class="w-64 bg-[#001A29] border-r border-slate-800 text-slate-300 flex-shrink-0 flex flex-col justify-between sticky top-16 h-[calc(100vh-4rem)] overflow-y-auto transition-all duration-300 z-30">
    <div class="p-4 space-y-5">
        
        @if(auth()->check() && auth()->user()->hasRole('admin_sisig'))
            <!-- VISTA ADMINISTRADOR SISIG -->
            <!-- SECCIÓN 1: GESTIÓN PRINCIPAL -->
            <div>
                <span class="px-3 text-[10px] font-extrabold tracking-wider text-slate-400 uppercase">
                    Menú Principal
                </span>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('sisig.admin.dashboard') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('sisig.admin.dashboard') ? 'bg-sena-green text-white shadow-sena' : 'hover:bg-slate-800/80 hover:text-white text-slate-300' }}">
                        <i class="fas fa-chart-line text-sm w-5 text-center"></i>
                        <span>Dashboard General</span>
                    </a>
                </div>
            </div>

            <!-- SECCIÓN 2: LOS MÓDULOS DE GESTIÓN ADMINISTRATIVA -->
            <div>
                <span class="px-3 text-[10px] font-extrabold tracking-wider text-slate-400 uppercase">
                    Módulos de Gestión
                </span>
                <div class="mt-2 space-y-1">
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

                    <a href="#gestion-usuarios" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-medium hover:bg-slate-800/80 hover:text-white transition-all text-slate-300 group">
                        <i class="fas fa-users-cog text-sm w-5 text-center text-indigo-400 group-hover:scale-110 transition-transform"></i>
                        <span>Gestión de Usuarios</span>
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
            <!-- SECCIÓN 1: PANEL PRINCIPAL -->
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

            <!-- SECCIÓN 2: GESTIÓN DE CONTENIDOS Y EVALUACIONES -->
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
            <!-- SECCIÓN 1: APRENDIZAJE -->
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

            <!-- SECCIÓN 2: EJES DE INDUCCIÓN Y EVALUACIONES -->
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

    <!-- Botón Cerrar Sesión en el Sidebar -->
    <div class="p-4 border-t border-slate-800/80">
        <a href="{{ route('logout') }}?redirect=/sisig" 
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold text-rose-300 hover:text-white bg-rose-950/40 hover:bg-rose-900/60 border border-rose-800/50 transition-all duration-200 group shadow-sm">
            <i class="fas fa-sign-out-alt text-sm text-rose-400 group-hover:translate-x-0.5 transition-transform"></i>
            <span>Cerrar Sesión</span>
        </a>
    </div>
</aside>
