<!-- Footer Modular SISIG (Estilo Oficial: Dark Navy + Columnas según Image 1) -->
<footer class="bg-[#1A2B4C] border-t-2 border-[#33FF57] text-slate-300 text-xs py-8 px-6 sm:px-8 mt-auto">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <!-- Columna 1: Identidad SISIG -->
        <div class="space-y-3">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center p-1 shadow-sm flex-shrink-0">
                    <img src="{{ asset('modules/sisig/images/logo.png') }}" alt="SENA Logo" class="w-full h-full object-contain">
                </div>
                <div>
                    <span class="text-sm font-extrabold text-white block leading-tight">SISIG</span>
                    <span class="text-[10px] font-bold text-[#33FF57] uppercase tracking-wider">SENA Empresa</span>
                </div>
            </div>
            <p class="text-slate-400 text-xs leading-relaxed max-w-sm">
                Sistema de Inducción y Evaluación en SST, Gestión de la Calidad y Ambiental del Centro de Formación Agroindustrial "La Angostura".
            </p>
        </div>

        <!-- Columna 2: Enlaces -->
        <div class="space-y-2">
            <h4 class="text-xs font-black text-[#33FF57] uppercase tracking-wider">Enlaces</h4>
            <ul class="space-y-1.5 text-xs text-slate-300">
                <li>
                    <a href="{{ route('sisig.index') }}" class="hover:text-[#33FF57] transition-colors flex items-center gap-1.5">
                        <i class="fas fa-angle-right text-[10px] text-[#33FF57]"></i>
                        <span>Portal Principal</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('sisig.desarrolladores') }}" class="hover:text-[#33FF57] transition-colors flex items-center gap-1.5">
                        <i class="fas fa-angle-right text-[10px] text-[#33FF57]"></i>
                        <span>Equipo de Desarrolladores</span>
                    </a>
                </li>
                <li>
                    <span class="text-slate-400 flex items-center gap-1.5">
                        <i class="fas fa-angle-right text-[10px] text-[#33FF57]"></i>
                        <span>Manual de Usuario</span>
                    </span>
                </li>
            </ul>
        </div>

        <!-- Columna 3: Contacto -->
        <div class="space-y-2">
            <h4 class="text-xs font-black text-[#33FF57] uppercase tracking-wider">Contacto</h4>
            <div class="space-y-2 text-xs text-slate-300">
                <div class="flex items-center gap-2">
                    <i class="fas fa-envelope text-[#33FF57] text-sm w-4 text-center"></i>
                    <span>ecp.angostura@sena.edu.co</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-phone-alt text-[#33FF57] text-sm w-4 text-center"></i>
                    <span>(608) 870 0000</span>
                </div>
                <div class="text-[11px] text-slate-400 pt-1">
                    &copy; {{ date('Y') }} SENA &bull; Todos los derechos reservados.
                </div>
            </div>
        </div>

    </div>
</footer>
