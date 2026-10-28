<!-- Header / Navbar Superior SISIG (Estilo Oficial: Dark Navy + Neon Green Line) -->
<header class="sticky top-0 z-30 bg-[#1A2B4C] border-b-2 border-[#33FF57] text-white shadow-md">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            
            <!-- Left: Toggle Sidebar + Título del Panel de Inicio -->
            <div class="flex items-center gap-3">
                <button type="button" id="sidebar-toggle-btn" class="p-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 focus:outline-none transition-all duration-200 flex items-center justify-center cursor-pointer border border-transparent hover:border-white/10" title="Mostrar / Ocultar menú lateral (Sidebar)">
                    <i class="fas fa-bars-staggered text-base"></i>
                </button>

                <div class="flex items-center gap-2">
                    <h2 class="text-sm sm:text-base font-extrabold text-white tracking-wide">
                        {{ $title ?? 'Panel de Inicio - SISIG' }}
                    </h2>
                </div>
            </div>

            <!-- Right: Tarjeta Píldora de Usuario con Menú Desplegable (Estilo Imagen Oficial) -->
            <div class="flex items-center gap-3">
                @if(auth()->check())
                    @php
                        $user = auth()->user();
                        
                        // 1. Iniciales de usuario o avatar
                        $initials = '';
                        if ($user->person) {
                            $first = mb_substr(trim($user->person->first_name ?? ''), 0, 1);
                            $last = mb_substr(trim($user->person->first_last_name ?? ''), 0, 1);
                            $initials = strtoupper($first . $last);
                        }
                        if (empty($initials) && !empty($user->name)) {
                            $parts = preg_split('/\s+/', trim($user->name));
                            $initials = strtoupper(mb_substr($parts[0] ?? '', 0, 1) . mb_substr($parts[1] ?? '', 0, 1));
                        }
                        if (empty($initials)) {
                            $initials = strtoupper(substr($user->nickname ?? $user->email ?? 'US', 0, 2));
                        }

                        // 2. Nombre completo a mostrar en mayúsculas
                        $displayName = strtoupper($user->full_name ?? ($user->person ? trim($user->person->first_name . ' ' . $user->person->first_last_name) : $user->name));

                        // 3. Etiqueta del rol según los 3 roles del sistema
                        $roleName = 'APRENDIZ';
                        if ($user->hasRole('admin_sisig') || $user->hasRole('sisig.admin')) {
                            $roleName = 'ADMINISTRADOR';
                        } elseif ($user->hasRole('editor_sisig') || $user->hasRole('sisig.instructor')) {
                            $roleName = 'EDITOR';
                        } elseif ($user->roles && $user->roles->isNotEmpty()) {
                            $firstRoleName = strtoupper($user->roles->first()->name ?? '');
                            if (str_contains($firstRoleName, 'ADMIN')) {
                                $roleName = 'ADMINISTRADOR';
                            } elseif (str_contains($firstRoleName, 'EDIT') || str_contains($firstRoleName, 'INSTRUCT')) {
                                $roleName = 'EDITOR';
                            } elseif (str_contains($firstRoleName, 'APRENDIZ')) {
                                $roleName = 'APRENDIZ';
                            } else {
                                $roleName = $firstRoleName;
                            }
                        }
                    @endphp

                    <div class="relative" id="user-menu-container">
                        <!-- Botón Píldora de Usuario -->
                        <button type="button" 
                                id="user-menu-button" 
                                aria-expanded="false" 
                                aria-haspopup="true"
                                class="bg-white hover:bg-slate-50 text-[#343A40] pl-2 pr-3.5 py-1.5 rounded-full flex items-center gap-2.5 shadow-sm hover:shadow transition-all group cursor-pointer border border-slate-100 select-none">
                            
                            <!-- Avatar circular verde con iniciales en blanco o foto -->
                            <div class="w-8 h-8 rounded-full bg-[#39A900] text-white flex items-center justify-center font-bold text-xs tracking-wider overflow-hidden flex-shrink-0 shadow-xs">
                                @if($user->person && $user->person->avatar)
                                    <img src="{{ asset('storage/' . $user->person->avatar) }}" 
                                         alt="{{ $displayName }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <span>{{ $initials }}</span>
                                @endif
                            </div>

                            <!-- Nombre y Rol del Usuario -->
                            <div class="text-left hidden sm:block">
                                <span class="block text-xs font-black text-slate-800 tracking-tight uppercase leading-tight truncate max-w-[190px]">
                                    {{ $displayName }}
                                </span>
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mt-0.5">
                                    {{ $roleName }}
                                </span>
                            </div>

                            <!-- Chevron Icon -->
                            <i class="fas fa-chevron-down text-[10px] text-slate-400 group-hover:text-slate-600 transition-transform duration-200 ml-1" id="user-menu-chevron"></i>
                        </button>

                        <!-- Menú Desplegable Flotante (Exacto al Screenshot) -->
                        <div id="user-menu-dropdown" 
                             class="hidden absolute right-0 top-full mt-2 w-48 sm:w-52 bg-white rounded-2xl shadow-xl border border-slate-100 p-2 z-50 transition-all duration-200 origin-top-right">
                            
                            <!-- Opción 1: Mi perfil -->
                            <a href="{{ route('sisig.perfil.index') }}" 
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-700 hover:text-slate-900 hover:bg-slate-50 transition-colors group cursor-pointer">
                                <i class="fas fa-user text-[#39A900] text-sm w-5 text-center group-hover:scale-110 transition-transform"></i>
                                <span class="text-xs font-bold">Mi perfil</span>
                            </a>

                            <!-- Divisor Sutil -->
                            <div class="h-px bg-slate-100 my-1"></div>

                            <!-- Opción 2: Cerrar sesión -->
                            <a href="{{ route('logout') }}?redirect=/sisig" 
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-rose-600 hover:text-rose-700 hover:bg-rose-50 transition-colors group cursor-pointer">
                                <i class="fas fa-arrow-right-from-bracket text-rose-500 text-sm w-5 text-center group-hover:translate-x-0.5 transition-transform"></i>
                                <span class="text-xs font-bold">Cerrar sesión</span>
                            </a>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}?redirect=/sisig" class="inline-flex items-center gap-2 px-5 py-2 text-xs font-black text-[#1A2B4C] bg-[#33FF57] hover:bg-[#2ae049] rounded-full shadow-md transition-all">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Iniciar Sesión</span>
                    </a>
                @endif
            </div>

            <script>
                (function() {
                    function initUserMenu() {
                        var btn = document.getElementById('user-menu-button');
                        var menu = document.getElementById('user-menu-dropdown');
                        var chevron = document.getElementById('user-menu-chevron');

                        if (!btn || !menu || btn.dataset.boundUserMenu) return;
                        btn.dataset.boundUserMenu = 'true';

                        btn.addEventListener('click', function(e) {
                            e.stopPropagation();
                            var isClosed = menu.classList.contains('hidden');
                            if (isClosed) {
                                menu.classList.remove('hidden');
                                btn.setAttribute('aria-expanded', 'true');
                                if (chevron) chevron.classList.add('rotate-180');
                            } else {
                                menu.classList.add('hidden');
                                btn.setAttribute('aria-expanded', 'false');
                                if (chevron) chevron.classList.remove('rotate-180');
                            }
                        });

                        document.addEventListener('click', function(e) {
                            if (!menu.classList.contains('hidden') && !btn.contains(e.target) && !menu.contains(e.target)) {
                                menu.classList.add('hidden');
                                btn.setAttribute('aria-expanded', 'false');
                                if (chevron) chevron.classList.remove('rotate-180');
                            }
                        });

                        document.addEventListener('keydown', function(e) {
                            if (e.key === 'Escape' && !menu.classList.contains('hidden')) {
                                menu.classList.add('hidden');
                                btn.setAttribute('aria-expanded', 'false');
                                if (chevron) chevron.classList.remove('rotate-180');
                            }
                        });
                    }

                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', initUserMenu);
                    } else {
                        initUserMenu();
                    }
                })();
            </script>

        </div>
    </div>
</header>
