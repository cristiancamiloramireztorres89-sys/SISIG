<!-- Header / Navbar Superior SISIG (Blanco / Clean Light Style) -->
<header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-slate-200 text-slate-700 shadow-sm">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            
            <!-- Left: Mobile Toggle + Identificador Institucional -->
            <div class="flex items-center gap-3">
                <button type="button" id="mobile-sidebar-toggle" class="md:hidden p-2 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 focus:outline-none transition-colors">
                    <i class="fas fa-bars text-lg"></i>
                </button>

                <div class="flex items-center gap-2 text-xs">
                    <span class="font-extrabold tracking-wider text-slate-800 uppercase">SENA Empresa</span>
                    <span class="text-sena-green font-bold hidden sm:inline">Centro De Formación Agroindustrial La Angostura</span>
                </div>
            </div>

            <!-- Right: Foto de Perfil Circular Destacada -->
            <div class="flex items-center gap-4">
                @if(auth()->check())
                    <a href="{{ route('sisig.perfil.index') }}" 
                       title="{{ auth()->user()->full_name ?? auth()->user()->name }} • Ver Mi Perfil" 
                       class="relative group cursor-pointer focus:outline-none flex items-center">
                        
                        <!-- Contenedor Circular Resaltado con Anillo y Sombra SENA -->
                        <div class="w-10 h-10 rounded-full p-[2px] bg-gradient-to-tr from-sena-green via-emerald-400 to-teal-300 group-hover:from-emerald-400 group-hover:to-sena-green shadow-sm group-hover:shadow-sena group-hover:scale-105 transition-all duration-300">
                            <div class="w-full h-full rounded-full overflow-hidden bg-slate-100 flex items-center justify-center">
                                @if(auth()->user()->person && auth()->user()->person->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->person->avatar) }}" 
                                         alt="{{ auth()->user()->name }}" 
                                         class="w-full h-full object-cover rounded-full">
                                @else
                                    <span class="text-xs font-black text-white bg-gradient-to-br from-sena-green to-[#237000] w-full h-full flex items-center justify-center rounded-full">
                                        {{ auth()->user()->initials ?? strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Indicador en línea de usuario activo con borde blanco -->
                        <span class="absolute bottom-0 right-0 w-3 h-3 bg-sena-green border-2 border-white rounded-full shadow-sm"></span>
                    </a>
                @else
                    <a href="{{ route('login') }}?redirect=/sisig" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-white bg-sena-green hover:bg-sena-green-hover rounded-xl shadow-sena transition-all">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Iniciar Sesión</span>
                    </a>
                @endif
            </div>

        </div>
    </div>
</header>
