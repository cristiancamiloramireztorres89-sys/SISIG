@php
    $rolNombre = 'Usuario SISIG';
    $panelSubtitulo = 'Panel del Aprendiz';

    if (auth()->check()) {
        $u = auth()->user();
        if ($u->hasRole('admin_sisig')) {
            $rolNombre = 'Administrador SISIG';
            $panelSubtitulo = 'Panel Administrativo';
        } elseif ($u->hasRole('editor_sisig')) {
            $rolNombre = 'Editor SISIG';
            $panelSubtitulo = 'Panel de Edición';
        } elseif ($u->roles->isNotEmpty()) {
            $rolNombre = $u->roles->first()->name;
            $panelSubtitulo = 'Panel del Aprendiz';
        } elseif ($u->nickname === 'aprendiz' || ($u->person_id && \Illuminate\Support\Facades\Schema::hasTable('apprentices') && \Illuminate\Support\Facades\DB::table('apprentices')->where('person_id', $u->person_id)->exists())) {
            $rolNombre = 'Aprendiz SISIG';
            $panelSubtitulo = 'Panel del Aprendiz';
        }
    }
@endphp

<!-- Header / Navbar Superior SISIG -->
<header class="sticky top-0 z-40 bg-[#001A29]/95 backdrop-blur-md border-b border-slate-800 text-white shadow-md">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            
            <!-- Left: Logo & Brand -->
            <div class="flex items-center gap-3">
                <!-- Mobile sidebar toggle button -->
                <button type="button" id="mobile-sidebar-toggle" class="md:hidden p-2 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 focus:outline-none">
                    <i class="fas fa-bars text-lg"></i>
                </button>

                <a href="{{ route('sisig.index') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                        <img src="{{ asset('modules/sisig/images/logo.png') }}" alt="SENA Empresa Logo" class="w-full h-full object-contain filter drop-shadow">
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xl font-extrabold tracking-tight text-white">SISIG</span>
                            <span class="text-xs uppercase font-extrabold tracking-wider text-sena-green">
                                &bull; {{ $panelSubtitulo }}
                            </span>
                        </div>
                        <span class="text-[11px] text-slate-400 font-medium block">SENA Empresa &bull; Centro Agroindustrial La Angostura</span>
                    </div>
                </a>
            </div>

            <!-- Right: User Profile & Actions -->
            <div class="flex items-center gap-4">
                @if(auth()->check())
                    <!-- User Info Pill (Clickable link to Mi Perfil) -->
                    <a href="{{ route('sisig.perfil.index') }}" 
                       title="Ver Mi Perfil" 
                       class="flex items-center gap-3 bg-slate-900/80 hover:bg-slate-800 border border-slate-700/60 hover:border-sena-green/50 rounded-xl px-3 py-1.5 transition-all duration-200 group cursor-pointer shadow-sm">
                        
                        <!-- Foto de Perfil o Iniciales -->
                        <div class="w-8 h-8 rounded-lg overflow-hidden flex items-center justify-center font-bold text-xs shadow-sm group-hover:scale-105 transition-transform flex-shrink-0 {{ (auth()->user()->person && auth()->user()->person->avatar) ? 'bg-slate-800 border border-slate-600' : 'bg-gradient-to-br from-sena-green to-[#237000] text-white' }}">
                            @if(auth()->user()->person && auth()->user()->person->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->person->avatar) }}" 
                                     alt="{{ auth()->user()->name }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <span>{{ auth()->user()->initials ?? 'U' }}</span>
                            @endif
                        </div>

                        <div class="hidden sm:block text-left leading-tight">
                            <p class="text-xs font-bold text-slate-100 truncate max-w-[150px] group-hover:text-white">
                                {{ auth()->user()->full_name ?? auth()->user()->name }}
                            </p>
                            <span class="text-[10px] font-semibold text-sena-green flex items-center gap-1">
                                <span>{{ $rolNombre }}</span>
                                <i class="fas fa-chevron-right text-[9px] text-slate-500 group-hover:text-sena-green group-hover:translate-x-0.5 transition-all"></i>
                            </span>
                        </div>
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
