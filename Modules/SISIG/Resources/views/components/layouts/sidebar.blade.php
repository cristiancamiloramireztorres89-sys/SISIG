<!-- Sidebar Lateral para SISIG (Roles: Editor y Administrador) -->
<aside id="sidebar-menu" class="w-64 bg-[#001A29] border-r border-slate-800 text-slate-300 flex-shrink-0 flex flex-col justify-between sticky top-16 h-[calc(100vh-4rem)] overflow-y-auto transition-all duration-300 z-30">
    <div class="p-4 space-y-5">
        
        <!-- SECCIÓN 1: GESTIÓN PRINCIPAL -->
        <div>
            <span class="px-3 text-[10px] font-extrabold tracking-wider text-slate-400 uppercase">
                Menú Principal
            </span>
            <div class="mt-2 space-y-1">
                <!-- Dashboard General -->
                <a href="{{ route('sisig.admin.dashboard') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('sisig.admin.dashboard') ? 'bg-sena-green text-white shadow-sena' : 'hover:bg-slate-800/80 hover:text-white text-slate-300' }}">
                    <i class="fas fa-chart-line text-sm w-5 text-center"></i>
                    <span>Dashboard General</span>
                </a>
            </div>
        </div>

        <!-- SECCIÓN 2: LOS 6 MÓDULOS DE GESTIÓN -->
        <div>
            <span class="px-3 text-[10px] font-extrabold tracking-wider text-slate-400 uppercase">
                Módulos de Gestión
            </span>
            <div class="mt-2 space-y-1">
                
                <!-- 1. Gestión de Módulos (Secciones, Contenidos SST, Calidad, Ambiental) -->
                <a href="#gestion-modulos" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-medium hover:bg-slate-800/80 hover:text-white transition-all text-slate-300 group">
                    <i class="fas fa-layer-group text-sm w-5 text-center text-emerald-400 group-hover:scale-110 transition-transform"></i>
                    <span>Gestión de Módulos</span>
                </a>

                <!-- 2. Seguimiento de Exámenes (Quices, Notas, Intentos) -->
                <a href="#seguimiento-examenes" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-medium hover:bg-slate-800/80 hover:text-white transition-all text-slate-300 group">
                    <i class="fas fa-clipboard-check text-sm w-5 text-center text-sky-400 group-hover:scale-110 transition-transform"></i>
                    <span>Seguimiento Exámenes</span>
                </a>

                <!-- 3. Gestión de Usuarios (Aprendices, Formación) -->
                <a href="#gestion-usuarios" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-medium hover:bg-slate-800/80 hover:text-white transition-all text-slate-300 group">
                    <i class="fas fa-users-cog text-sm w-5 text-center text-indigo-400 group-hover:scale-110 transition-transform"></i>
                    <span>Gestión de Usuarios</span>
                </a>

                <!-- 4. Reportes (Descargas Excel / PDF y Estadísticas) -->
                <a href="#reportes" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-medium hover:bg-slate-800/80 hover:text-white transition-all text-slate-300 group">
                    <i class="fas fa-file-excel text-sm w-5 text-center text-teal-400 group-hover:scale-110 transition-transform"></i>
                    <span>Reportes y Métricas</span>
                </a>

                <!-- 5. Seguridad (Control de Infracciones y Monitoreo de Pantalla) -->
                <a href="#seguridad" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-medium hover:bg-slate-800/80 hover:text-white transition-all text-slate-300 group">
                    <i class="fas fa-shield-alt text-sm w-5 text-center text-amber-400 group-hover:scale-110 transition-transform"></i>
                    <span>Seguridad e Infracciones</span>
                </a>

            </div>
        </div>

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


