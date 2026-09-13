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
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        sena: {
                            green: '#39A900',
                            'green-hover': '#2d8500',
                            'green-light': '#E8F5E9',
                            navy: '#00324D',
                            'navy-light': '#004B73',
                            dark: '#001A29',
                            slate: '#0F2B3E',
                            light: '#F8FAFC',
                            card: '#FFFFFF',
                            orange: '#e65100',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    boxShadow: {
                        'sena': '0 10px 30px -5px rgba(57, 169, 0, 0.3)',
                        'sena-navy': '0 12px 35px -5px rgba(0, 50, 77, 0.25)',
                        'glow': '0 0 25px rgba(57, 169, 0, 0.35)',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
        }
        .custom-glass-dark {
            background: rgba(0, 26, 41, 0.94);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        /* Nav links highlighting styles */
        .nav-spy-item {
            color: #94a3b8; /* slate-400 */
            font-weight: 500;
            transition: all 0.25s ease-in-out;
            position: relative;
        }

        .nav-spy-item:hover {
            color: #ffffff;
        }

        .nav-spy-item.active {
            color: #39A900 !important; /* SENA Green */
            font-weight: 800 !important;
            text-shadow: 0 0 16px rgba(57, 169, 0, 0.65);
        }

        .nav-spy-item.active i {
            color: #39A900 !important;
            transform: scale(1.1);
        }
    </style>
</head>

<body class="bg-sena-light text-slate-800 flex flex-col min-h-screen antialiased selection:bg-sena-green selection:text-white">

    <!-- Header / Navbar -->
    <header class="sticky top-0 z-50 custom-glass-dark border-b-2 border-sena-green shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo & Brand -->
                <div class="flex items-center gap-3">
                    <a href="#inicio" class="flex items-center gap-3 group">
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
                    <a href="#inicio" class="nav-spy-item active text-sm flex items-center gap-1.5" data-section="inicio">
                        <i class="fas fa-home text-sm"></i>
                        <span>Inicio</span>
                    </a>
                    <a href="#submodulos" class="nav-spy-item text-sm flex items-center gap-1.5" data-section="submodulos">
                        <i class="fas fa-layer-group text-sm"></i>
                        <span>Submódulos</span>
                    </a>
                    <a href="#areas" class="nav-spy-item text-sm flex items-center gap-1.5" data-section="areas">
                        <i class="fas fa-shield-alt text-sm"></i>
                        <span>Ejes SIG</span>
                    </a>
                    <a href="#caracteristicas" class="nav-spy-item text-sm flex items-center gap-1.5" data-section="caracteristicas">
                        <i class="fas fa-bolt text-sm"></i>
                        <span>Funciones</span>
                    </a>
                    <a href="#faq" class="nav-spy-item text-sm flex items-center gap-1.5" data-section="faq">
                        <i class="fas fa-question-circle text-sm"></i>
                        <span>Preguntas</span>
                    </a>
                </nav>

                <!-- Action Button: Iniciar Sesión -->
                <div class="flex items-center gap-3">
                    @if(auth()->check())
                        <div class="flex items-center gap-3">
                            <span class="text-xs sm:text-sm font-semibold text-slate-200 flex items-center gap-2">
                                <i class="fas fa-user-circle text-sena-green text-lg"></i>
                                <span>{{ auth()->user()->nickname ?? auth()->user()->email }}</span>
                            </span>
                            <a href="{{ route('logout') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-rose-300 hover:text-white bg-rose-950/40 hover:bg-rose-900/60 border border-rose-800/50 rounded-xl transition-all">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Cerrar Sesión</span>
                            </a>
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
    <footer class="bg-sena-dark border-t border-slate-800 text-slate-400 text-sm mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                
                <!-- Left -->
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 flex items-center justify-center flex-shrink-0">
                        <img src="{{ asset('modules/sisig/images/logo.png') }}" alt="SENA Empresa Logo" class="w-full h-full object-contain filter drop-shadow-sm">
                    </div>
                    <div>
                        <h4 class="text-white font-bold text-base">SISIG - SENA Empresa</h4>
                        <p class="text-xs text-slate-400">Centro de Formación Agroindustrial 'La Angostura'</p>
                    </div>
                </div>

                <!-- Center -->
                <div class="text-center text-xs text-slate-400">
                    <p>Inducciones &bull; SST &bull; Gestión de la Calidad &bull; Gestión Ambiental</p>
                    <p class="text-sena-green font-semibold mt-1">Servicio Nacional de Aprendizaje - SENA</p>
                </div>

                <!-- Right -->
                <div class="text-center md:text-right text-xs">
                    <p>&copy; {{ date('Y') }} Todos los derechos reservados.</p>
                    <p class="text-slate-500 mt-0.5">Sistema Integrado de Gestión Institucional</p>
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
