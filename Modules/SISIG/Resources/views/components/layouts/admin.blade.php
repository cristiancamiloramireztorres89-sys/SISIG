<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{ $title ?? 'Panel Administrativo' }} | SISIG - SENA Empresa</title>

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

    <!-- Tailwind CSS CDN with custom SENA palette -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
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
                        'sena': '0 10px 25px -5px rgba(57, 169, 0, 0.25)',
                        'sena-navy': '0 12px 30px -5px rgba(0, 50, 77, 0.20)',
                        'glow': '0 0 20px rgba(57, 169, 0, 0.35)',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        /* Scrollbar elegante y oscuro para el sidebar fijo */
        #sidebar-menu::-webkit-scrollbar {
            width: 4px;
        }
        #sidebar-menu::-webkit-scrollbar-track {
            background: #001A29;
        }
        #sidebar-menu::-webkit-scrollbar-thumb {
            background: #1e293b;
            border-radius: 4px;
        }
        #sidebar-menu::-webkit-scrollbar-thumb:hover {
            background: #334155;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen antialiased selection:bg-sena-green selection:text-white">

    <!-- 1. Navbar Superior -->
    @include('sisig::components.layouts.header')

    <!-- 2. Contenedor con Sidebar y Contenido Principal -->
    <div class="flex flex-1 min-h-[calc(100vh-4rem)]">
        
        <!-- Sidebar de navegación (Fijo en columna izquierda) -->
        @include('sisig::components.layouts.sidebar')

        <!-- Columna Derecha: Contenido Dinámico + Footer -->
        <div class="flex-1 flex flex-col min-w-0 bg-[#F8FAFC]">
            <main class="flex-1 p-5 sm:p-8">
                {{ $slot }}
            </main>

            <!-- 3. Pie de página exclusivo de la columna de contenido -->
            @include('sisig::components.layouts.footer')
        </div>

    </div>

    <!-- Toggle script para el sidebar en móviles -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleBtn = document.getElementById('mobile-sidebar-toggle');
            const sidebar = document.getElementById('sidebar-menu');

            if (toggleBtn && sidebar) {
                toggleBtn.addEventListener('click', () => {
                    sidebar.classList.toggle('hidden');
                });
            }
        });
    </script>
</body>
</html>
