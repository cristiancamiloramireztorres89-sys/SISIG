<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{ $title ?? 'SISIG - Sistema Integrado de Gestión' }} | SENA Empresa</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS CDN with custom SENA palette -->
    <!-- Tailwind CSS CDN with custom official palette -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        // Nueva paleta oficial (Guía de Estilo)
                        'dark-navy': '#1A2B4C',
                        'forest-green': '#2E6F40',
                        'neon-green': '#33FF57',
                        'off-white': '#F4F5F7',
                        'dark-grey': '#343A40',
                        'cyan-blue': '#00BCD4',

                        // Mapeo retrocompatible sena.*
                        sena: {
                            green: '#2E6F40',
                            'green-hover': '#245833',
                            'green-neon': '#33FF57',
                            'green-light': '#EBFBF0',
                            navy: '#1A2B4C',
                            'navy-light': '#243B66',
                            dark: '#14223C',
                            slate: '#1A2B4C',
                            light: '#F4F5F7',
                            card: '#FFFFFF',
                            grey: '#343A40',
                            cyan: '#00BCD4',
                            orange: '#e65100',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    boxShadow: {
                        'sena': '0 10px 25px -5px rgba(46, 111, 64, 0.25)',
                        'sena-neon': '0 10px 25px -5px rgba(51, 255, 87, 0.35)',
                        'sena-navy': '0 12px 30px -5px rgba(26, 43, 76, 0.25)',
                        'glow': '0 0 20px rgba(51, 255, 87, 0.40)',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F4F5F7;
            color: #343A40;
        }
        .custom-glass-dark {
            background: rgba(26, 43, 76, 0.96);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        /* Nav links highlighting styles */
        .nav-spy-item {
            color: #cbd5e1;
            font-weight: 600;
            transition: all 0.25s ease-in-out;
            position: relative;
        }

        .nav-spy-item:hover {
            color: #ffffff;
        }

        .nav-spy-item.active {
            color: #33FF57 !important; /* Neon Green */
            font-weight: 800 !important;
            text-shadow: 0 0 16px rgba(51, 255, 87, 0.65);
        }

        .nav-spy-item.active i {
            color: #33FF57 !important;
            transform: scale(1.1);
        }
    </style>
</head>

<body class="bg-[#F4F5F7] text-[#343A40] flex flex-col min-h-screen antialiased selection:bg-[#33FF57] selection:text-[#1A2B4C] font-sans">

    <!-- Header / Navbar -->
    <header class="sticky top-0 z-50 custom-glass-dark border-b-2 border-[#33FF57] shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo & Brand -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('sisig.index') }}" class="flex items-center gap-3 group">
                        <div class="w-12 h-12 flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                            <img src="{{ asset('modules/sisig/images/logo.png') }}" alt="SENA Empresa Logo" class="w-full h-full object-contain filter drop-shadow-md">
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-2xl font-extrabold tracking-tight text-white">SISIG</span>
                                <span class="text-xs uppercase font-extrabold tracking-wider text-sena-green">&bull; Apoyo</span>
                            </div>
                            <span class="text-xs text-slate-300 font-medium block">SENA Empresa &bull; Cefa</span>
                        </div>
                    </a>
                </div>

                <!-- Nav Navigation Links (Sin cuadros / resaltado de palabra dinámico) -->
                <nav class="hidden lg:flex items-center gap-7">
                    <a href="{{ route('sisig.index') }}#inicio" class="nav-spy-item {{ request()->routeIs('sisig.index') ? 'active' : '' }} text-sm flex items-center gap-1.5" data-section="inicio">
                        <i class="fas fa-home text-sm"></i>
                        <span>Inicio</span>
                    </a>
                    <a href="{{ route('sisig.index') }}#submodulos" class="nav-spy-item text-sm flex items-center gap-1.5" data-section="submodulos">
                        <i class="fas fa-layer-group text-sm"></i>
                        <span>Submódulos</span>
                    </a>
                    <a href="{{ route('sisig.index') }}#areas" class="nav-spy-item text-sm flex items-center gap-1.5" data-section="areas">
                        <i class="fas fa-shield-alt text-sm"></i>
                        <span>Ejes SIG</span>
                    </a>
                    <a href="{{ route('sisig.index') }}#caracteristicas" class="nav-spy-item text-sm flex items-center gap-1.5" data-section="caracteristicas">
                        <i class="fas fa-bolt text-sm"></i>
                        <span>Funciones</span>
                    </a>
                    <a href="{{ route('sisig.desarrolladores') }}" class="nav-spy-item text-sm flex items-center gap-1.5 {{ request()->routeIs('sisig.desarrolladores') ? 'active' : '' }}">
                        <i class="fas fa-code text-sm"></i>
                        <span>Desarrolladores</span>
                    </a>
                </nav>

                <!-- Action Button: Iniciar Sesión / Volver al ERP -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" title="Volver al Portal Principal del ERP" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-slate-300 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 rounded-xl transition-all">
                        <i class="fas fa-th text-sena-green text-xs"></i>
                        <span>Portal ERP</span>
                    </a>
                    @if(auth()->check())
                        <div class="flex items-center gap-3">
                            @if(auth()->user()->hasRole('admin_sisig'))
                                <a href="{{ route('sisig.admin.dashboard') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-white bg-sena-green hover:bg-sena-green-hover rounded-xl shadow-sena transition-all">
                                    <i class="fas fa-chart-line"></i>
                                    <span>Panel Admin</span>
                                </a>
                            @elseif(auth()->user()->hasRole('editor_sisig'))
                                <a href="{{ route('sisig.editor.dashboard') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-white bg-sena-green hover:bg-sena-green-hover rounded-xl shadow-sena transition-all">
                                    <i class="fas fa-edit"></i>
                                    <span>Panel Edición</span>
                                </a>
                            @else
                                <a href="{{ route('sisig.aprendiz.dashboard') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-white bg-sena-green hover:bg-sena-green-hover rounded-xl shadow-sena transition-all">
                                    <i class="fas fa-graduation-cap"></i>
                                    <span>Mi Dashboard</span>
                                </a>
                            @endif
                            @php
                                $masterUser = auth()->user();
                                $mInitials = '';
                                if ($masterUser->person) {
                                    $first = mb_substr(trim($masterUser->person->first_name ?? ''), 0, 1);
                                    $last = mb_substr(trim($masterUser->person->first_last_name ?? ''), 0, 1);
                                    $mInitials = strtoupper($first . $last);
                                }
                                if (empty($mInitials) && !empty($masterUser->name)) {
                                    $parts = preg_split('/\s+/', trim($masterUser->name));
                                    $mInitials = strtoupper(mb_substr($parts[0] ?? '', 0, 1) . mb_substr($parts[1] ?? '', 0, 1));
                                }
                                if (empty($mInitials)) {
                                    $mInitials = strtoupper(substr($masterUser->nickname ?? $masterUser->email ?? 'US', 0, 2));
                                }
                                $mDisplayName = strtoupper($masterUser->full_name ?? ($masterUser->person ? trim($masterUser->person->first_name . ' ' . $masterUser->person->first_last_name) : $masterUser->name));
                                
                                $mRoleName = 'APRENDIZ';
                                if ($masterUser->hasRole('admin_sisig') || $masterUser->hasRole('sisig.admin')) {
                                    $mRoleName = 'ADMINISTRADOR';
                                } elseif ($masterUser->hasRole('editor_sisig') || $masterUser->hasRole('sisig.instructor')) {
                                    $mRoleName = 'EDITOR';
                                } elseif ($masterUser->roles && $masterUser->roles->isNotEmpty()) {
                                    $firstR = strtoupper($masterUser->roles->first()->name ?? '');
                                    if (str_contains($firstR, 'ADMIN')) $mRoleName = 'ADMINISTRADOR';
                                    elseif (str_contains($firstR, 'EDIT') || str_contains($firstR, 'INSTRUCT')) $mRoleName = 'EDITOR';
                                    elseif (str_contains($firstR, 'APRENDIZ')) $mRoleName = 'APRENDIZ';
                                    else $mRoleName = $firstR;
                                }
                            @endphp

                            <div class="relative" id="master-user-menu-container">
                                <button type="button" 
                                        id="master-user-menu-button" 
                                        aria-expanded="false" 
                                        aria-haspopup="true"
                                        class="bg-white hover:bg-slate-50 text-[#343A40] pl-2 pr-3.5 py-1.5 rounded-full flex items-center gap-2.5 shadow-sm hover:shadow transition-all group cursor-pointer border border-slate-100 select-none">
                                    <div class="w-8 h-8 rounded-full bg-[#39A900] text-white flex items-center justify-center font-bold text-xs tracking-wider overflow-hidden flex-shrink-0 shadow-xs">
                                        @if($masterUser->person && $masterUser->person->avatar)
                                            <img src="{{ asset('storage/' . $masterUser->person->avatar) }}" alt="{{ $mDisplayName }}" class="w-full h-full object-cover">
                                        @else
                                            <span>{{ $mInitials }}</span>
                                        @endif
                                    </div>
                                    <div class="text-left hidden sm:block">
                                        <span class="block text-xs font-black text-slate-800 tracking-tight uppercase leading-tight truncate max-w-[190px]">
                                            {{ $mDisplayName }}
                                        </span>
                                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mt-0.5">
                                            {{ $mRoleName }}
                                        </span>
                                    </div>
                                    <i class="fas fa-chevron-down text-[10px] text-slate-400 group-hover:text-slate-600 transition-transform duration-200 ml-1" id="master-user-menu-chevron"></i>
                                </button>

                                <div id="master-user-menu-dropdown" 
                                     class="hidden absolute right-0 top-full mt-2 w-48 sm:w-52 bg-white rounded-2xl shadow-xl border border-slate-100 p-2 z-50 transition-all duration-200 origin-top-right">
                                    <a href="{{ route('sisig.perfil.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-700 hover:text-slate-900 hover:bg-slate-50 transition-colors group cursor-pointer">
                                        <i class="fas fa-user text-[#39A900] text-sm w-5 text-center group-hover:scale-110 transition-transform"></i>
                                        <span class="text-xs font-bold">Mi perfil</span>
                                    </a>
                                    <div class="h-px bg-slate-100 my-1"></div>
                                    <a href="{{ route('logout') }}?redirect=/sisig" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-rose-600 hover:text-rose-700 hover:bg-rose-50 transition-colors group cursor-pointer">
                                        <i class="fas fa-arrow-right-from-bracket text-rose-500 text-sm w-5 text-center group-hover:translate-x-0.5 transition-transform"></i>
                                        <span class="text-xs font-bold">Cerrar sesión</span>
                                    </a>
                                </div>
                            </div>

                            <script>
                                (function() {
                                    function initMasterMenu() {
                                        var btn = document.getElementById('master-user-menu-button');
                                        var menu = document.getElementById('master-user-menu-dropdown');
                                        var chevron = document.getElementById('master-user-menu-chevron');
                                        if (!btn || !menu || btn.dataset.boundMasterMenu) return;
                                        btn.dataset.boundMasterMenu = 'true';

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
                                        document.addEventListener('DOMContentLoaded', initMasterMenu);
                                    } else {
                                        initMasterMenu();
                                    }
                                })();
                            </script>
                        </div>
                    @else
                        <a href="{{ route('login') }}?redirect=/sisig" class="inline-flex items-center gap-2 px-5 py-2.5 text-xs sm:text-sm font-bold text-white bg-sena-green hover:bg-sena-green-hover rounded-xl shadow-sena hover:shadow-glow transition-all duration-300 transform hover:-translate-y-0.5">
                            <i class="fas fa-sign-in-alt text-sm"></i>
                            <span>Iniciar Sesión</span>
                        </a>
                    @endif
                </div>

            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-[#1A2B4C] border-t-2 border-[#33FF57] text-slate-300 text-sm mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                
                <!-- Left -->
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center p-1.5 flex-shrink-0 shadow-sm">
                        <img src="{{ asset('modules/sisig/images/logo.png') }}" alt="SENA Empresa Logo" class="w-full h-full object-contain filter drop-shadow-xs">
                    </div>
                    <div>
                        <h4 class="text-white font-bold text-base">SISIG - SENA Empresa</h4>
                        <p class="text-xs text-slate-400">Centro de Formación Agroindustrial 'La Angostura'</p>
                    </div>
                </div>

                <!-- Center -->
                <div class="text-center text-xs text-slate-400">
                    <p>Inducciones &bull; SST &bull; Gestión de la Calidad &bull; Gestión Ambiental</p>
                    <p class="text-[#33FF57] font-bold mt-1">Servicio Nacional de Aprendizaje - SENA</p>
                </div>

                <!-- Right -->
                <div class="text-center md:text-right text-xs">
                    <p>&copy; {{ date('Y') }} Todos los derechos reservados.</p>
                    <p class="text-slate-400 mt-0.5">Sistema Integrado de Gestión Institucional</p>
                </div>

            </div>
        </div>
    </footer>

    <!-- ScrollSpy Script: detecta en qué sección vas y resalta la palabra dinámicamente -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.nav-spy-item');

            const onScroll = () => {
                const scrollPos = window.scrollY + 140; // Offset para el navbar fijo

                sections.forEach(sec => {
                    const top = sec.offsetTop;
                    const height = sec.offsetHeight;
                    const id = sec.getAttribute('id');

                    if (scrollPos >= top && scrollPos < top + height) {
                        navLinks.forEach(link => {
                            if (link.getAttribute('data-section') === id) {
                                link.classList.add('active');
                            } else {
                                link.classList.remove('active');
                            }
                        });
                    }
                });
            };

            window.addEventListener('scroll', onScroll);
            onScroll(); // Ejecutar al cargar
        });
    </script>
</body>
</html>
