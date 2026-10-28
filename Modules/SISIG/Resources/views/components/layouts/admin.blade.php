<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{ $title ?? 'Panel de Control' }} | SISIG - SENA Empresa</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Chart.js para visualizaciones y graficas de rendimiento -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- SweetAlert2 para modales y alertas estilizadas -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Sistema Global Unificado de Alertas Banner SISIG (Intercepción Temprana)
        window._alertasPendientes = [];

        window.mostrarAlertaBanner = function(mensaje, tipo = 'success') {
            const contenedor = document.getElementById('contenedor-alertas-globales');
            if (!contenedor) {
                window._alertasPendientes.push({ mensaje, tipo });
                return;
            }

            // Limpiar alertas previas
            contenedor.innerHTML = '';

            const bgClass = tipo === 'success' 
                ? 'bg-emerald-50 border-emerald-200 text-emerald-800' 
                : (tipo === 'error' ? 'bg-rose-50 border-rose-200 text-rose-800' : 'bg-amber-50 border-amber-200 text-amber-800');
            const iconClass = tipo === 'success' 
                ? 'fa-check-circle text-emerald-600' 
                : (tipo === 'error' ? 'fa-exclamation-triangle text-rose-600' : 'fa-exclamation-circle text-amber-600');

            const alertEl = document.createElement('div');
            alertEl.id = 'session-alert';
            alertEl.className = `p-4 rounded-2xl ${bgClass} border flex items-center justify-between gap-3 shadow-sm transition-all duration-500 ease-out animate-fade-in`;
            alertEl.innerHTML = `
                <div class="flex items-center gap-3">
                    <i class="fas ${iconClass} text-base flex-shrink-0"></i>
                    <p class="text-xs sm:text-sm font-bold">${mensaje}</p>
                </div>
                <button type="button" onclick="cerrarAlertaGlobal(this.closest('#session-alert'))" class="p-1.5 rounded-lg opacity-70 hover:opacity-100 transition-opacity cursor-pointer">
                    <i class="fas fa-times text-xs"></i>
                </button>
            `;

            contenedor.appendChild(alertEl);
            window.iniciarTemporizadorAlerta(alertEl);
        };

        window.cerrarAlertaGlobal = function(el) {
            if (!el) return;
            el.style.opacity = '0';
            el.style.transform = 'translateY(-8px)';
            setTimeout(() => {
                if (el && el.parentNode) el.remove();
            }, 400);
        };

        window.iniciarTemporizadorAlerta = function(el) {
            if (!el) return;
            setTimeout(() => {
                window.cerrarAlertaGlobal(el);
            }, 3000);
        };

        // Procesar alertas pendientes al cargar el DOM
        document.addEventListener('DOMContentLoaded', () => {
            const initialAlert = document.getElementById('session-alert');
            if (initialAlert) {
                window.iniciarTemporizadorAlerta(initialAlert);
            }
            if (window._alertasPendientes && window._alertasPendientes.length > 0) {
                window._alertasPendientes.forEach(item => window.mostrarAlertaBanner(item.mensaje, item.tipo));
                window._alertasPendientes = [];
            }
        });

        // Interceptar INMEDIATAMENTE SweetAlert2 para redirigir toda notificación al banner superior
        if (window.Swal) {
            const _swalFireOriginal = window.Swal.fire;
            window.Swal.fire = function(...args) {
                let config = {};
                if (typeof args[0] === 'object' && args[0] !== null) {
                    config = args[0];
                } else {
                    config = {
                        title: args[0] || '',
                        text: args[1] || '',
                        icon: args[2] || 'info'
                    };
                }

                // Si es un diálogo de confirmación interactivo que tiene botón cancelar o inputs
                if (config.showCancelButton || config.input) {
                    return _swalFireOriginal.apply(this, args);
                }

                // Para cualquier mensaje o alerta de acción:
                let rawMsg = config.text || (config.title && config.title !== '¡Éxito!' && config.title !== 'Éxito' ? config.title : '') || config.html || config.title || '';
                
                // Limpiar entidades HTML (ej. &quot;)
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = rawMsg;
                const cleanMsg = tempDiv.textContent || tempDiv.innerText || rawMsg;

                let tipo = 'success';
                if (config.icon === 'error') tipo = 'error';
                else if (config.icon === 'warning') tipo = 'warning';
                else if (config.icon === 'info') tipo = 'info';

                window.mostrarAlertaBanner(cleanMsg, tipo);
                return Promise.resolve({ isConfirmed: true, isDenied: false, isDismissed: false });
            };
        }
    </script>

    <!-- Tailwind CSS CDN with custom official palette -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
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
        :root {
            --color-dark-navy: #1A2B4C;
            --color-forest-green: #2E6F40;
            --color-neon-green: #33FF57;
            --color-off-white: #F4F5F7;
            --color-dark-grey: #343A40;
            --color-cyan-blue: #00BCD4;
            --color-primario: #2E6F40;
            --color-sidebar: #1A2B4C;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F4F5F7;
            color: #343A40;
        }
        #contenedor-alertas-globales:empty {
            display: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        /* Bloqueo estricto de scroll para la página de fondo cuando hay un modal abierto */
        html.modal-open,
        body.modal-open {
            overflow: hidden !important;
            height: 100% !important;
        }

        /* Prevenir que el scroll dentro del modal rebote hacia el fondo de la página */
        .modal-open [class*="overflow-y-auto"],
        .modal-open [class*="overflow-auto"] {
            overscroll-behavior: contain !important;
        }
        /* Scrollbar elegante y oscuro para el sidebar */
        #sidebar-menu::-webkit-scrollbar {
            width: 4px;
        }
        #sidebar-menu::-webkit-scrollbar-track {
            background: #1A2B4C;
        }
        #sidebar-menu::-webkit-scrollbar-thumb {
            background: #243B66;
            border-radius: 4px;
        }
        #sidebar-menu::-webkit-scrollbar-thumb:hover {
            background: #334e80;
        }

        /* Transiciones fluidas del Sidebar */
        #sidebar-menu {
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), padding 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* En Desktop (md y superior), Modo Mini-Sidebar con Logos/Iconos */
        @media (min-width: 768px) {
            #sidebar-menu.sidebar-collapsed {
                width: 4.75rem !important; /* 76px */
            }
            #sidebar-menu.sidebar-collapsed .sidebar-text {
                display: none !important;
            }
            #sidebar-menu.sidebar-collapsed .sidebar-section-title {
                display: none !important;
            }
            #sidebar-menu.sidebar-collapsed .sidebar-divider {
                display: block !important;
            }
            #sidebar-menu.sidebar-collapsed .sidebar-header-container {
                justify-content: center !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
            #sidebar-menu.sidebar-collapsed .sidebar-header-inner {
                justify-content: center !important;
                gap: 0 !important;
                width: 100% !important;
            }
            #sidebar-menu.sidebar-collapsed .sidebar-logo-box {
                margin: 0 auto !important;
            }
            #sidebar-menu.sidebar-collapsed #sidebar-nav {
                padding: 0.5rem 0.25rem !important;
                gap: 0.25rem !important;
            }
            #sidebar-menu.sidebar-collapsed .sidebar-section-block {
                margin-top: 0.25rem !important;
            }
            #sidebar-menu.sidebar-collapsed .sidebar-section-block:first-child {
                margin-top: 0 !important;
            }
            #sidebar-menu.sidebar-collapsed a {
                width: 2.75rem !important; /* 44px */
                height: 2.75rem !important; /* 44px */
                margin-left: auto !important;
                margin-right: auto !important;
                padding: 0 !important;
                justify-content: center !important;
                align-items: center !important;
                border-radius: 0.75rem !important;
            }
            #sidebar-menu.sidebar-collapsed a i {
                font-size: 0.875rem !important; /* Exactamente el mismo tamaño natural sin agrandarse */
                margin: 0 !important;
                width: auto !important;
            }
            #sidebar-menu.sidebar-collapsed .sidebar-footer-container {
                padding: 0.5rem 0.25rem !important;
                display: flex !important;
                justify-content: center !important;
            }
        }

        /* En Móvil (menor a 768px), sidebar flotante con overlay */
        @media (max-width: 767px) {
            #sidebar-menu {
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                z-index: 50;
                transform: translateX(-100%);
                width: 16rem !important;
            }
            #sidebar-menu.mobile-open {
                transform: translateX(0);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
            }
        }
    </style>
</head>

<body class="bg-[#F4F5F7] text-[#343A40] flex min-h-screen antialiased selection:bg-[#33FF57] selection:text-[#1A2B4C] relative m-0 p-0 font-sans">

    <!-- Backdrop oscuro para móvil -->
    <div id="sidebar-backdrop" class="fixed inset-0 bg-[#1A2B4C]/80 backdrop-blur-xs z-40 hidden md:hidden transition-opacity duration-300"></div>

    <!-- 1. Sidebar Lateral Completo (Desde arriba h-screen) -->
    @include('sisig::components.layouts.sidebar')

    <!-- 2. Columna Derecha: Header + Contenido Principal + Footer -->
    <div class="flex-1 flex flex-col min-w-0 bg-[#F4F5F7] min-h-screen transition-all duration-300">
        
        <!-- Header Superior -->
        @include('sisig::components.layouts.header')

        <main class="flex-1 p-4 sm:p-6 lg:p-7 relative">
            <!-- Contenedor Universal de Alertas de Sesión / Acciones -->
            <div id="contenedor-alertas-globales" class="space-y-3">
                @if(session('success') || session('error') || session('info') || session('warning'))
                    @php 
                        $tipo = session('success') ? 'success' : (session('error') ? 'error' : (session('warning') ? 'warning' : 'info'));
                        $mensaje = session('success') ?? session('error') ?? session('warning') ?? session('info');
                    @endphp
                    <div id="session-alert" class="p-4 mb-4 rounded-2xl {{ $tipo === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : ($tipo === 'error' ? 'bg-rose-50 border-rose-200 text-rose-800' : 'bg-amber-50 border-amber-200 text-amber-800') }} border flex items-center justify-between gap-3 shadow-sm transition-all duration-500 ease-out animate-fade-in">
                        <div class="flex items-center gap-3">
                            <i class="fas {{ $tipo === 'success' ? 'fa-check-circle text-emerald-600' : ($tipo === 'error' ? 'fa-exclamation-triangle text-rose-600' : 'fa-exclamation-circle text-amber-600') }} text-base flex-shrink-0"></i>
                            <p class="text-xs sm:text-sm font-bold">{{ $mensaje }}</p>
                        </div>
                        <button type="button" onclick="cerrarAlertaGlobal(this.closest('#session-alert'))" class="p-1.5 rounded-lg opacity-70 hover:opacity-100 transition-opacity">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </div>
                @endif
            </div>

            {{ $slot }}

            <!-- Modal Universal Reutilizable de Confirmación -->
            @include('sisig::components.modals.confirmacion')
        </main>

        <!-- 3. Pie de página institucional -->
        @include('sisig::components.layouts.footer')
    </div>

    <!-- Script universal para sidebar y navegación -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('sidebar-menu');
            const toggleBtn = document.getElementById('sidebar-toggle-btn');
            const backdrop = document.getElementById('sidebar-backdrop');

            // Leer estado guardado en Desktop
            const isCollapsed = localStorage.getItem('sisig_sidebar_collapsed') === 'true';
            if (isCollapsed && window.innerWidth >= 768 && sidebar) {
                sidebar.classList.add('sidebar-collapsed');
            }

            function toggleSidebar() {
                if (window.innerWidth < 768) {
                    if (sidebar) {
                        const isOpen = sidebar.classList.toggle('mobile-open');
                        if (backdrop) {
                            backdrop.classList.toggle('hidden', !isOpen);
                        }
                    }
                } else {
                    if (sidebar) {
                        const collapsed = sidebar.classList.toggle('sidebar-collapsed');
                        localStorage.setItem('sisig_sidebar_collapsed', collapsed ? 'true' : 'false');
                    }
                }
            }

            function closeSidebarMobile() {
                if (sidebar) sidebar.classList.remove('mobile-open');
                if (backdrop) backdrop.classList.add('hidden');
            }

            if (toggleBtn) toggleBtn.addEventListener('click', toggleSidebar);
            if (backdrop) backdrop.addEventListener('click', closeSidebarMobile);
        });

        // ========================================================
        // CONTROL GLOBAL DE BLOQUEO DE SCROLL AL ABRIR CUALQUIER MODAL
        // ========================================================
        window.lockBodyScroll = function(lock) {
            if (lock) {
                document.documentElement.classList.add('modal-open');
                document.body.classList.add('modal-open');
            } else {
                setTimeout(() => {
                    const modales = document.querySelectorAll('.fixed.inset-0:not(#sidebar-backdrop)');
                    let hayModalAbierto = false;
                    for (const m of modales) {
                        if (!m.classList.contains('hidden') && m.style.display !== 'none' && (m.offsetWidth > 0 || m.offsetHeight > 0)) {
                            hayModalAbierto = true;
                            break;
                        }
                    }
                    if (!hayModalAbierto) {
                        document.documentElement.classList.remove('modal-open');
                        document.body.classList.remove('modal-open');
                    }
                }, 20);
            }
        };
    </script>
</body>
</html>