<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Editor Visual Interactivo Tipo Canva | SISIG SENA</title>

    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'dark-navy': '#1A2B4C',
                        'forest-green': '#2E6F40',
                        'neon-green': '#33FF57',
                        'off-white': '#F4F5F7',
                        'dark-grey': '#343A40',
                        'cyan-blue': '#00BCD4',
                        'sena-navy': '#1A2B4C',
                        'sena-navy-dark': '#0E1726',
                        'sena-green': '#2E6F40',
                        'sena-neon': '#33FF57',
                        'sena-emerald': '#10b981',
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- Google Fonts: Plus Jakarta Sans (Tipografía Oficial SISIG) + Fuentes Canvas -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Anton&family=Bebas+Neue&family=Caveat:wght@600;700&family=Dancing+Script:wght@700&family=Fira+Code:wght@500;700&family=Inter:wght@400;500;600;700;800;900&family=Montserrat:wght@600;700;800;900&family=Oswald:wght@600;700&family=Pacifico&family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Poppins:wght@400;600;700;800&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- SweetAlert2 para notificaciones modernas -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Fabric.js v5.3.1 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            user-select: none;
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
                font-size: 0.875rem !important;
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

        /* Área de trabajo estilo Canva con fondo Dark Navy sutil */
        .workspace-canvas-bg {
            background-color: #0d1527;
            background-image: 
                radial-gradient(circle at 1px 1px, rgba(255,255,255,0.08) 1px, transparent 0);
            background-size: 24px 24px;
        }

        /* Estilo de la hoja / lienzo */
        .canvas-container {
            margin: 0 auto;
            border-radius: 12px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(255, 255, 255, 0.08);
            background-color: #ffffff !important;
            transition: transform 0.15s ease;
        }

        /* Scrollbars finos para los paneles */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.03);
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.4);
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(100, 116, 139, 0.7);
        }

        /* Pestañas de Herramientas del Sidebar (Exactas al sistema oficial SISIG) */
        .sidebar-tab-btn {
            color: #cbd5e1;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
        }
        .sidebar-tab-btn:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }
        .sidebar-tab-btn.active {
            background-color: #33FF57 !important;
            color: #1A2B4C !important;
            font-weight: 900 !important;
            box-shadow: 0 4px 14px rgba(51, 255, 87, 0.25);
        }
        .sidebar-tab-btn.active i,
        .sidebar-tab-btn.active span {
            color: #1A2B4C !important;
            font-weight: 900 !important;
        }

        /* Cuando el sidebar de edición está colapsado (Modo Mini-Sidebar 76px) */
        @media (min-width: 768px) {
            #sidebar-menu.sidebar-collapsed .sidebar-tab-btn {
                width: 2.75rem !important;
                height: 2.75rem !important;
                margin-left: auto !important;
                margin-right: auto !important;
                padding: 0 !important;
                justify-content: center !important;
                align-items: center !important;
                border-radius: 0.75rem !important;
            }
            #sidebar-menu.sidebar-collapsed .sidebar-tab-btn i {
                font-size: 0.875rem !important;
                margin: 0 !important;
                width: auto !important;
            }
            #sidebar-menu.sidebar-collapsed .sidebar-tab-btn span {
                display: none !important;
            }
        }

        /* Deslizadores personalizados */
        input[type="range"] {
            accent-color: #2E6F40;
        }
    </style>
</head>
<body class="bg-[#1A2B4C] text-[#343A40] flex h-screen w-screen overflow-hidden antialiased font-sans select-none m-0 p-0">

    <!-- Backdrop oscuro para móvil -->
    <div id="sidebar-backdrop" class="fixed inset-0 bg-[#1A2B4C]/80 backdrop-blur-xs z-40 hidden md:hidden transition-opacity duration-300"></div>

    <!-- 1. SIDEBAR LATERAL OFICIAL DE EDICIÓN SISIG (Mismo estilo, diseño y tamaño del sistema) -->
    <aside id="sidebar-menu" style="background-color: #1A2B4C;" class="w-64 border-r border-[#243B66]/60 text-slate-200 flex-shrink-0 flex flex-col justify-between h-screen sticky top-0 transition-all duration-300 z-40 select-none">
        
        <!-- Encabezado Superior del Sidebar (Logo Circular Blanco como botón para alternar el menú) -->
        <div class="h-16 px-4 flex-shrink-0 flex items-center justify-start sidebar-header-container border-b border-[#243B66]/50">
            <button type="button" 
                    id="btn-toggle-sidebar-logo" 
                    class="flex items-center gap-3 overflow-hidden w-full select-none sidebar-header-inner text-left cursor-pointer focus:outline-none" 
                    title="Haz clic para ocultar o expandir el menú lateral">
                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center p-1.5 shadow-sm flex-shrink-0 sidebar-logo-box">
                    <img src="{{ asset('modules/sisig/images/logo.png') }}" alt="SENA Empresa Logo" class="w-full h-full object-contain filter drop-shadow-xs">
                </div>
                <div class="min-w-0 sidebar-text">
                    <span class="text-base font-black tracking-tight text-white block leading-tight">SISIG</span>
                    <span class="text-[10px] font-bold text-[#33FF57] tracking-wider uppercase block leading-none mt-0.5">Editor de Contenido</span>
                </div>
            </button>
        </div>

        <!-- Navegación de Herramientas de Edición (Exacto al diseño del sidebar oficial) -->
        <div class="flex-1 overflow-y-auto p-3 space-y-4" id="sidebar-nav">
            
            <div class="sidebar-section-block">
                <span class="sidebar-section-title px-3 text-[10px] font-black tracking-wider text-slate-400 uppercase">
                    Herramientas de Edición
                </span>
                <div class="sidebar-divider hidden my-1.5 border-t border-[#243B66]/60"></div>
                
                <div class="mt-2 space-y-1.5">
                    <!-- 1. Plantillas -->
                    <button type="button" data-tab="plantillas" class="sidebar-tab-btn dock-tab-btn active w-full flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs font-semibold transition-all group cursor-pointer text-left" title="Plantillas SISIG">
                        <i class="fas fa-layer-group text-sm w-5 text-center flex-shrink-0"></i>
                        <span class="sidebar-text truncate">Plantillas SISIG</span>
                    </button>

                    <!-- 2. Texto -->
                    <button type="button" data-tab="texto" class="sidebar-tab-btn dock-tab-btn w-full flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs font-semibold transition-all group cursor-pointer text-left" title="Textos y Tipografías">
                        <i class="fas fa-font text-sm w-5 text-center flex-shrink-0"></i>
                        <span class="sidebar-text truncate">Texto & Tipografías</span>
                    </button>

                    <!-- 3. Elementos -->
                    <button type="button" data-tab="elementos" class="sidebar-tab-btn dock-tab-btn w-full flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs font-semibold transition-all group cursor-pointer text-left" title="Elementos y Formas">
                        <i class="fas fa-shapes text-sm w-5 text-center flex-shrink-0"></i>
                        <span class="sidebar-text truncate">Elementos & Formas</span>
                    </button>

                    <!-- 4. Marcos -->
                    <button type="button" data-tab="marcos" class="sidebar-tab-btn dock-tab-btn w-full flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs font-semibold transition-all group cursor-pointer text-left" title="Marcos de Fotos">
                        <i class="fas fa-crop-alt text-sm w-5 text-center flex-shrink-0"></i>
                        <span class="sidebar-text truncate">Marcos de Fotos</span>
                    </button>

                    <!-- 5. Subidas -->
                    <button type="button" data-tab="subidas" class="sidebar-tab-btn dock-tab-btn w-full flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs font-semibold transition-all group cursor-pointer text-left" title="Subir Imágenes">
                        <i class="fas fa-cloud-upload-alt text-sm w-5 text-center flex-shrink-0"></i>
                        <span class="sidebar-text truncate">Subidas / Multimedia</span>
                    </button>

                    <!-- 6. Fondo -->
                    <button type="button" data-tab="fondo" class="sidebar-tab-btn dock-tab-btn w-full flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs font-semibold transition-all group cursor-pointer text-left" title="Color de Fondo">
                        <i class="fas fa-fill-drip text-sm w-5 text-center flex-shrink-0"></i>
                        <span class="sidebar-text truncate">Fondo del Lienzo</span>
                    </button>
                </div>
            </div>

        </div>

        <!-- Pie del Sidebar: Volver a Gestión de Contenido -->
        <div class="p-3 border-t border-[#243B66]/50 sidebar-footer-container">
            <span class="sidebar-section-title px-3 text-[10px] font-black tracking-wider text-slate-400 uppercase mb-1.5 block">
                Navegación
            </span>
            <div class="sidebar-divider hidden my-1.5 border-t border-[#243B66]/60"></div>
            <a href="{{ route('sisig.editor.contenido.index') }}" 
               title="Volver a Gestión de Contenido" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs font-bold text-slate-300 hover:text-white hover:bg-rose-500/15 transition-all group">
                <i class="fas fa-arrow-left text-sm w-5 text-center flex-shrink-0 text-rose-400 group-hover:-translate-x-1 transition-transform"></i>
                <span class="sidebar-text truncate">Volver a Contenidos</span>
            </a>
        </div>

    </aside>

    <!-- 2. CONTENEDOR DERECHO: HEADER SUPERIOR + ÁREA DEL EDITOR -->
    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden bg-slate-900">

        <!-- HEADER SUPERIOR OFICIAL SISIG (Exacto a la Imagen 2, sin botón hamburguesa) -->
        @include('sisig::components.layouts.header', ['title' => 'Gestión de Contenido', 'hideToggle' => true])

        <!-- ÁREA DEL EDITOR VISUAL CANVA (DRAWER + LIENZO) -->
        <div class="flex-1 flex overflow-hidden relative">

            <!-- PANEL EXPANDIBLE DE CONTENIDO DE LA PESTAÑA (DRAWER) -->
            <aside id="canva-drawer" class="w-80 bg-white border-r border-slate-200 flex flex-col z-20 shadow-2xl flex-shrink-0 transition-all duration-300">
                
                <!-- Encabezado del Panel -->
                <div class="h-14 px-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between flex-shrink-0">
                    <h2 id="drawer-title" class="text-xs font-black text-[#1A2B4C] uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-layer-group text-[#2E6F40]"></i> Plantillas SISIG
                    </h2>
                    <button type="button" id="btn-toggle-drawer" title="Ocultar panel lateral" class="text-slate-400 hover:text-[#1A2B4C] p-2 rounded-xl hover:bg-slate-200 transition-colors cursor-pointer">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </button>
                </div>

            <!-- Contenido Dinámico por Pestaña -->
            <div class="flex-grow overflow-y-auto p-4 space-y-4">

                <!-- 1. PANEL: PLANTILLAS INSTITUCIONALES SISIG -->
                <div id="panel-plantillas" class="tab-panel space-y-3">
                    <p class="text-xs text-slate-500 font-medium">Haz clic en una plantilla prediseñada para insertarla en el lienzo:</p>

                    <!-- Filtros Rápidos de Categoría -->
                    <div class="flex items-center gap-1 pb-1 overflow-x-auto text-[10px] font-bold">
                        <button type="button" onclick="filtrarPlantillas('todas', this)" class="btn-filter-tpl px-2.5 py-1 rounded-full bg-sena-green text-white cursor-pointer transition-all flex-shrink-0" data-filter="todas">Todas (12)</button>
                        <button type="button" onclick="filtrarPlantillas('oficial', this)" class="btn-filter-tpl px-2 py-1 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 cursor-pointer transition-all flex-shrink-0" data-filter="oficial">Oficial</button>
                        <button type="button" onclick="filtrarPlantillas('sst', this)" class="btn-filter-tpl px-2 py-1 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 cursor-pointer transition-all flex-shrink-0" data-filter="sst">SST</button>
                        <button type="button" onclick="filtrarPlantillas('ambiental', this)" class="btn-filter-tpl px-2 py-1 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 cursor-pointer transition-all flex-shrink-0" data-filter="ambiental">Ambiental</button>
                        <button type="button" onclick="filtrarPlantillas('con_foto', this)" class="btn-filter-tpl px-2 py-1 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 cursor-pointer transition-all flex-shrink-0" data-filter="con_foto">Con Foto</button>
                    </div>

                    <!-- Lista de 12 Plantillas -->
                    <div id="lista-plantillas" class="space-y-2.5">
                        <!-- Template 1: Portada SISIG -->
                        <div onclick="aplicarPlantilla('portada')" data-cat="oficial" class="tpl-card p-3 bg-gradient-to-br from-slate-900 to-sena-navy text-white rounded-2xl cursor-pointer hover:scale-[1.02] transition-all border border-slate-700 shadow-sm group">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-[10px] font-black uppercase text-sena-neon">Plantilla Oficial</span>
                                <i class="fas fa-plus text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </div>
                            <h3 class="text-xs font-black tracking-tight">Portada de Inducción SISIG</h3>
                            <p class="text-[10px] text-slate-300 mt-0.5">Título corporativo, franja institucional y fecha</p>
                        </div>

                        <!-- Template 2: Tarjeta de Seguridad SST -->
                        <div onclick="aplicarPlantilla('sst')" data-cat="sst" class="tpl-card p-3 bg-amber-500/10 border border-amber-300 rounded-2xl cursor-pointer hover:scale-[1.02] transition-all group">
                            <div class="flex items-center justify-between text-amber-700 mb-1">
                                <span class="text-[10px] font-black uppercase flex items-center gap-1">
                                    <i class="fas fa-shield-alt"></i> Seguridad y Salud (SST)
                                </span>
                                <i class="fas fa-plus text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </div>
                            <h3 class="text-xs font-extrabold text-amber-900">Alerta Preventiva y EPP</h3>
                            <p class="text-[10px] text-amber-800/80 mt-0.5">Norma ISO 45001 y cuidados críticos</p>
                        </div>

                        <!-- Template 3: Gestión Ambiental -->
                        <div onclick="aplicarPlantilla('ambiental')" data-cat="ambiental" class="tpl-card p-3 bg-emerald-500/10 border border-emerald-300 rounded-2xl cursor-pointer hover:scale-[1.02] transition-all group">
                            <div class="flex items-center justify-between text-emerald-700 mb-1">
                                <span class="text-[10px] font-black uppercase flex items-center gap-1">
                                    <i class="fas fa-leaf"></i> Gestión Ambiental
                                </span>
                                <i class="fas fa-plus text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </div>
                            <h3 class="text-xs font-extrabold text-emerald-900">Buenas Prácticas ISO 14001</h3>
                            <p class="text-[10px] text-emerald-800/80 mt-0.5">Clasificación de residuos y ahorro</p>
                        </div>

                        <!-- Template 4: Tip para Examen / Evaluación -->
                        <div onclick="aplicarPlantilla('evaluacion')" data-cat="oficial" class="tpl-card p-3 bg-sky-500/10 border border-sky-300 rounded-2xl cursor-pointer hover:scale-[1.02] transition-all group">
                            <div class="flex items-center justify-between text-sky-700 mb-1">
                                <span class="text-[10px] font-black uppercase flex items-center gap-1">
                                    <i class="fas fa-question-circle"></i> Clave de Examen
                                </span>
                                <i class="fas fa-plus text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </div>
                            <h3 class="text-xs font-extrabold text-sky-900">Pregunta de Autoevaluación</h3>
                            <p class="text-[10px] text-sky-800/80 mt-0.5">Pregunta con opciones múltiples para aprendices</p>
                        </div>

                        <!-- Template 5: Ciclo PHVA en 3 Pasos -->
                        <div onclick="aplicarPlantilla('proceso_pasos')" data-cat="oficial" class="tpl-card p-3 bg-indigo-500/10 border border-indigo-200 rounded-2xl cursor-pointer hover:scale-[1.02] transition-all group">
                            <div class="flex items-center justify-between text-indigo-700 mb-1">
                                <span class="text-[10px] font-black uppercase flex items-center gap-1">
                                    <i class="fas fa-sync-alt"></i> Calidad & Procesos
                                </span>
                                <i class="fas fa-plus text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </div>
                            <h3 class="text-xs font-extrabold text-indigo-900">Infografía: Ciclo PHVA</h3>
                            <p class="text-[10px] text-indigo-800/80 mt-0.5">3 fases de mejora continua en columnas numeradas</p>
                        </div>

                        <!-- Template 6: Código de Canecas de Residuos -->
                        <div onclick="aplicarPlantilla('canecas_residuos')" data-cat="ambiental" class="tpl-card p-3 bg-teal-500/10 border border-teal-300 rounded-2xl cursor-pointer hover:scale-[1.02] transition-all group">
                            <div class="flex items-center justify-between text-teal-700 mb-1">
                                <span class="text-[10px] font-black uppercase flex items-center gap-1">
                                    <i class="fas fa-recycle"></i> Norma Ambiental
                                </span>
                                <i class="fas fa-plus text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </div>
                            <h3 class="text-xs font-extrabold text-teal-900">Código de Colores de Canecas</h3>
                            <p class="text-[10px] text-teal-800/80 mt-0.5">Blanco, Verde y Negro según Res. 2184</p>
                        </div>

                        <!-- Template 7: Ruta de Evacuación y Emergencias -->
                        <div onclick="aplicarPlantilla('emergencia')" data-cat="sst" class="tpl-card p-3 bg-rose-500/10 border border-rose-300 rounded-2xl cursor-pointer hover:scale-[1.02] transition-all group">
                            <div class="flex items-center justify-between text-rose-700 mb-1">
                                <span class="text-[10px] font-black uppercase flex items-center gap-1">
                                    <i class="fas fa-bullhorn"></i> Emergencias
                                </span>
                                <i class="fas fa-plus text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </div>
                            <h3 class="text-xs font-extrabold text-rose-900">Ruta de Evacuación y Alarma</h3>
                            <p class="text-[10px] text-rose-800/80 mt-0.5">Protocolo PON, números de socorro y punto de encuentro</p>
                        </div>

                        <!-- Template 8: Política Integrada de Calidad -->
                        <div onclick="aplicarPlantilla('politica_calidad')" data-cat="oficial" class="tpl-card p-3 bg-amber-500/10 border border-amber-400 rounded-2xl cursor-pointer hover:scale-[1.02] transition-all group">
                            <div class="flex items-center justify-between text-amber-800 mb-1">
                                <span class="text-[10px] font-black uppercase flex items-center gap-1">
                                    <i class="fas fa-award"></i> Marco Legal
                                </span>
                                <i class="fas fa-plus text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </div>
                            <h3 class="text-xs font-extrabold text-amber-950">Política Integrada de Calidad</h3>
                            <p class="text-[10px] text-amber-900/80 mt-0.5">Declaración institucional formal y 3 pilares ISO</p>
                        </div>

                        <!-- Template 9: Ficha Técnica con Marco de Foto -->
                        <div onclick="aplicarPlantilla('ficha_equipo')" data-cat="con_foto" class="tpl-card p-3 bg-blue-500/10 border border-blue-300 rounded-2xl cursor-pointer hover:scale-[1.02] transition-all group">
                            <div class="flex items-center justify-between text-blue-700 mb-1">
                                <span class="text-[10px] font-black uppercase flex items-center gap-1">
                                    <i class="fas fa-cogs"></i> Maquinaria & Equipos
                                </span>
                                <span class="text-[9px] bg-blue-600 text-white px-1.5 py-0.5 rounded font-black">FOTO</span>
                            </div>
                            <h3 class="text-xs font-extrabold text-blue-900">Ficha Técnica con Foto</h3>
                            <p class="text-[10px] text-blue-800/80 mt-0.5">Especificaciones técnicas + marco para foto del equipo</p>
                        </div>

                        <!-- Template 10: Diapositiva Multimedia Split Screen -->
                        <div onclick="aplicarPlantilla('diapositiva_split')" data-cat="con_foto" class="tpl-card p-3 bg-violet-500/10 border border-violet-300 rounded-2xl cursor-pointer hover:scale-[1.02] transition-all group">
                            <div class="flex items-center justify-between text-violet-700 mb-1">
                                <span class="text-[10px] font-black uppercase flex items-center gap-1">
                                    <i class="fas fa-columns"></i> Diapositiva
                                </span>
                                <span class="text-[9px] bg-violet-600 text-white px-1.5 py-0.5 rounded font-black">FOTO</span>
                            </div>
                            <h3 class="text-xs font-extrabold text-violet-900">Lección Educativa Multimedia</h3>
                            <p class="text-[10px] text-violet-800/80 mt-0.5">Texto y lista de verificación + marco fotográfico</p>
                        </div>

                        <!-- Template 11: Pausa Activa y Ergonomía -->
                        <div onclick="aplicarPlantilla('pausa_activa')" data-cat="sst" class="tpl-card p-3 bg-cyan-500/10 border border-cyan-300 rounded-2xl cursor-pointer hover:scale-[1.02] transition-all group">
                            <div class="flex items-center justify-between text-cyan-700 mb-1">
                                <span class="text-[10px] font-black uppercase flex items-center gap-1">
                                    <i class="fas fa-heartbeat"></i> Bienestar SST
                                </span>
                                <i class="fas fa-plus text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </div>
                            <h3 class="text-xs font-extrabold text-cyan-900">Pausas Activas y Ergonomía</h3>
                            <p class="text-[10px] text-cyan-800/80 mt-0.5">Ejercicios de estiramiento visual y muscular</p>
                        </div>

                        <!-- Template 12: Certificado / Reconocimiento -->
                        <div onclick="aplicarPlantilla('diploma')" data-cat="oficial" class="tpl-card p-3 bg-yellow-500/15 border border-yellow-400 rounded-2xl cursor-pointer hover:scale-[1.02] transition-all group">
                            <div class="flex items-center justify-between text-yellow-800 mb-1">
                                <span class="text-[10px] font-black uppercase flex items-center gap-1">
                                    <i class="fas fa-certificate"></i> Reconocimiento
                                </span>
                                <i class="fas fa-plus text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </div>
                            <h3 class="text-xs font-extrabold text-yellow-950">Certificado de Aprobación</h3>
                            <p class="text-[10px] text-yellow-900/80 mt-0.5">Diploma oficial de capacitación con firmas</p>
                        </div>
                    </div>
                </div>

                <!-- 2. PANEL: TEXTOS Y TIPOGRAFÍAS (CANVA SUITE) -->
                <div id="panel-texto" class="tab-panel hidden space-y-3">
                    <p class="text-xs text-slate-500 font-medium">Haz clic para insertar en el lienzo con edición directa:</p>

                    <!-- Jerarquías Principales -->
                    <div class="space-y-2">
                        <button type="button" onclick="agregarTexto('titulo')" class="w-full text-left p-3.5 bg-slate-50 hover:bg-slate-100/80 rounded-2xl border border-slate-200 transition-all group hover:border-slate-300 shadow-xs">
                            <span class="text-xl font-black text-slate-900 block leading-tight font-['Montserrat']">Añadir un Título</span>
                            <span class="text-[10px] text-slate-400">Jerarquía H1 para conceptos clave</span>
                        </button>

                        <button type="button" onclick="agregarTexto('subtitulo')" class="w-full text-left p-3 bg-slate-50 hover:bg-slate-100/80 rounded-2xl border border-slate-200 transition-all group hover:border-slate-300 shadow-xs">
                            <span class="text-base font-bold text-slate-800 block font-['Poppins']">Añadir un Subtítulo</span>
                            <span class="text-[10px] text-slate-400">Jerarquía H2 para secciones</span>
                        </button>

                        <button type="button" onclick="agregarTexto('parrafo')" class="w-full text-left p-3 bg-slate-50 hover:bg-slate-100/80 rounded-2xl border border-slate-200 transition-all group hover:border-slate-300 shadow-xs">
                            <span class="text-xs font-medium text-slate-700 block">Añadir texto de párrafo</span>
                            <span class="text-[10px] text-slate-400">Caja de texto descriptivo y explicativo</span>
                        </button>

                        <button type="button" onclick="agregarTexto('nota')" class="w-full text-left p-2.5 bg-slate-50 hover:bg-slate-100/80 rounded-2xl border border-slate-200 transition-all group hover:border-slate-300 shadow-xs">
                            <span class="text-[11px] font-medium text-slate-500 block italic">Añadir pie de página o nota técnica</span>
                        </button>
                    </div>

                    <!-- Combinaciones de Fuentes y Estilos Canva -->
                    <div class="pt-3 border-t border-slate-200/80 space-y-2.5">
                        <h4 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider flex items-center justify-between">
                            <span>Combinaciones Canva</span>
                            <i class="fas fa-magic text-emerald-600 text-xs"></i>
                        </h4>

                        <!-- Titular Impacto -->
                        <button type="button" onclick="agregarTexto('impacto')" class="w-full text-left p-3 bg-gradient-to-r from-slate-900 to-slate-800 hover:from-slate-800 hover:to-slate-700 rounded-2xl border border-slate-800 text-white transition-all shadow-md group">
                            <span class="text-lg font-black tracking-widest block uppercase font-['Bebas_Neue']" style="font-family: 'Bebas Neue', sans-serif; letter-spacing: 2px;">SEGURIDAD Y SALUD 2026</span>
                            <span class="text-[10px] text-slate-400">Titular imponente con fuente Bebas Neue</span>
                        </button>

                        <!-- Caligrafía / Firma -->
                        <button type="button" onclick="agregarTexto('firma')" class="w-full text-left p-3 bg-amber-50/60 hover:bg-amber-100/60 rounded-2xl border border-amber-200 transition-all group">
                            <span class="text-base text-amber-900 block" style="font-family: 'Pacifico', cursive;">Compromiso con la Calidad</span>
                            <span class="text-[10px] text-amber-700/80">Estilo manuscrito fluido tipo firma</span>
                        </button>

                        <!-- Cita Célebre SST -->
                        <button type="button" onclick="agregarTexto('cita')" class="w-full text-left p-3 bg-slate-50 hover:bg-slate-100 rounded-2xl border-l-4 border-l-emerald-600 border border-slate-200 transition-all group">
                            <span class="text-xs italic text-slate-800 font-serif block">“La seguridad no es un requisito, es un principio de vida.”</span>
                            <span class="text-[10px] text-slate-400 mt-0.5 block">— Política Institucional SIG</span>
                        </button>

                        <!-- Métrica / KPI Destacado -->
                        <button type="button" onclick="agregarTexto('kpi')" class="w-full text-left p-3 bg-emerald-50/80 hover:bg-emerald-100/80 rounded-2xl border border-emerald-200 transition-all group">
                            <div class="flex items-baseline gap-2">
                                <span class="text-2xl font-black text-emerald-800 font-['Montserrat']">100%</span>
                                <span class="text-xs font-bold text-emerald-950">CERO ACCIDENTES</span>
                            </div>
                            <span class="text-[10px] text-emerald-700">Métrica KPI en grande para estadísticas</span>
                        </button>

                        <!-- Checklist con Viñetas -->
                        <button type="button" onclick="agregarTexto('checklist')" class="w-full text-left p-3 bg-slate-50 hover:bg-slate-100 rounded-2xl border border-slate-200 transition-all group">
                            <div class="text-[11px] font-semibold text-slate-700 space-y-0.5">
                                <div>✔ Uso obligatorio de EPP</div>
                                <div>✔ Inspección de área operativa</div>
                                <div>✔ Reporte de actos inseguros</div>
                            </div>
                            <span class="text-[10px] text-slate-400 mt-1 block">Lista de chequeo con viñetas activas</span>
                        </button>

                        <!-- Insignia Norma ISO -->
                        <button type="button" onclick="agregarTexto('insignia')" class="w-full text-left p-2.5 bg-slate-900 hover:bg-slate-800 rounded-2xl border border-slate-700 transition-all">
                            <span class="inline-block px-2.5 py-1 bg-sena-green text-sena-neon text-[11px] font-black rounded-lg">ETIQUETA NORMA ISO 9001 / 45001</span>
                            <span class="text-[10px] text-slate-400 block mt-1">Insignia destacada institucional</span>
                        </button>

                        <!-- Alerta Precaución SST -->
                        <button type="button" onclick="agregarTexto('alerta')" class="w-full text-left p-2.5 bg-amber-50 hover:bg-amber-100 rounded-2xl border border-amber-300 transition-all">
                            <span class="inline-block px-2.5 py-1 bg-amber-500 text-white text-[11px] font-black rounded-lg">⚠️ NOTA IMPORTANTE SST</span>
                            <span class="text-[10px] text-amber-800 block mt-1">Caja de advertencia con fondo de seguridad</span>
                        </button>

                        <!-- Resplandor Neón -->
                        <button type="button" onclick="agregarTexto('neon_title')" class="w-full text-left p-2.5 bg-slate-950 hover:bg-slate-900 rounded-2xl border border-emerald-500/40 transition-all">
                            <span class="text-xs font-black text-emerald-400 block tracking-wider uppercase" style="text-shadow: 0 0 10px #33FF57;">INDUCCIÓN AMBIENTAL 2026</span>
                            <span class="text-[10px] text-slate-400 block">Titular con resplandor neón verde</span>
                        </button>
                    </div>
                </div>

                <!-- 3. PANEL: ELEMENTOS Y FORMAS (CANVA SUITE EXPANDIDA) -->
                <div id="panel-elementos" class="tab-panel hidden space-y-4">
                    
                    <!-- 1. Líneas y Flechas Vectoriales -->
                    <div>
                        <h4 class="text-xs font-black text-slate-700 uppercase tracking-wider mb-2 flex items-center justify-between">
                            <span>Líneas y Flechas</span>
                            <i class="fas fa-arrow-right text-emerald-600 text-xs"></i>
                        </h4>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" onclick="agregarForma('line')" class="p-2.5 bg-slate-50 hover:bg-slate-100/80 rounded-xl border border-slate-200 flex flex-col items-center justify-center gap-1.5 transition-all group">
                                <div class="w-14 h-1 bg-slate-800 rounded group-hover:scale-105 transition-transform"></div>
                                <span class="text-[10px] text-slate-600 font-bold">Línea Sólida</span>
                            </button>

                            <button type="button" onclick="agregarForma('dashed_line')" class="p-2.5 bg-slate-50 hover:bg-slate-100/80 rounded-xl border border-slate-200 flex flex-col items-center justify-center gap-1.5 transition-all group">
                                <div class="w-14 h-1 border-t-2 border-dashed border-slate-800 group-hover:scale-105 transition-transform"></div>
                                <span class="text-[10px] text-slate-600 font-bold">Línea Punteada</span>
                            </button>

                            <button type="button" onclick="agregarForma('arrow')" class="p-2.5 bg-slate-50 hover:bg-slate-100/80 rounded-xl border border-slate-200 flex flex-col items-center justify-center gap-1.5 transition-all group">
                                <div class="flex items-center text-emerald-600 group-hover:scale-105 transition-transform">
                                    <div class="w-10 h-0.5 bg-emerald-600"></div>
                                    <i class="fas fa-chevron-right text-xs -ml-1"></i>
                                </div>
                                <span class="text-[10px] text-slate-600 font-bold">Flecha Derecha</span>
                            </button>

                            <button type="button" onclick="agregarForma('double_arrow')" class="p-2.5 bg-slate-50 hover:bg-slate-100/80 rounded-xl border border-slate-200 flex flex-col items-center justify-center gap-1.5 transition-all group">
                                <div class="flex items-center text-emerald-600 group-hover:scale-105 transition-transform">
                                    <i class="fas fa-chevron-left text-xs -mr-1"></i>
                                    <div class="w-8 h-0.5 bg-emerald-600"></div>
                                    <i class="fas fa-chevron-right text-xs -ml-1"></i>
                                </div>
                                <span class="text-[10px] text-slate-600 font-bold">Bidireccional</span>
                            </button>
                        </div>
                    </div>

                    <!-- 2. Formas y Geometría -->
                    <div class="pt-2 border-t border-slate-200/80">
                        <h4 class="text-xs font-black text-slate-700 uppercase tracking-wider mb-2 flex items-center justify-between">
                            <span>Formas Geométricas</span>
                            <i class="fas fa-shapes text-emerald-600 text-xs"></i>
                        </h4>
                        <div class="grid grid-cols-3 gap-2">
                            <!-- Rectángulo -->
                            <button type="button" onclick="agregarForma('rect')" class="p-2.5 bg-slate-50 hover:bg-slate-100 rounded-xl border border-slate-200 flex flex-col items-center gap-1 group">
                                <div class="w-8 h-6 rounded bg-sena-navy group-hover:scale-105 transition-transform shadow-xs"></div>
                                <span class="text-[9px] text-slate-600 font-bold">Rectángulo</span>
                            </button>

                            <!-- Píldora / Rect Redondeado -->
                            <button type="button" onclick="agregarForma('pill')" class="p-2.5 bg-slate-50 hover:bg-slate-100 rounded-xl border border-slate-200 flex flex-col items-center gap-1 group">
                                <div class="w-10 h-5 rounded-full bg-emerald-600 group-hover:scale-105 transition-transform shadow-xs"></div>
                                <span class="text-[9px] text-slate-600 font-bold">Píldora</span>
                            </button>

                            <!-- Círculo -->
                            <button type="button" onclick="agregarForma('circle')" class="p-2.5 bg-slate-50 hover:bg-slate-100 rounded-xl border border-slate-200 flex flex-col items-center gap-1 group">
                                <div class="w-7 h-7 rounded-full bg-sena-green group-hover:scale-105 transition-transform shadow-xs"></div>
                                <span class="text-[9px] text-slate-600 font-bold">Círculo</span>
                            </button>

                            <!-- Triángulo -->
                            <button type="button" onclick="agregarForma('triangle')" class="p-2.5 bg-slate-50 hover:bg-slate-100 rounded-xl border border-slate-200 flex flex-col items-center gap-1 group">
                                <div class="w-0 h-0 border-l-[14px] border-l-transparent border-r-[14px] border-r-transparent border-b-[24px] border-b-amber-500 group-hover:scale-105 transition-transform"></div>
                                <span class="text-[9px] text-slate-600 font-bold">Triángulo</span>
                            </button>

                            <!-- Estrella -->
                            <button type="button" onclick="agregarForma('star')" class="p-2.5 bg-slate-50 hover:bg-slate-100 rounded-xl border border-slate-200 flex flex-col items-center gap-1 group">
                                <i class="fas fa-star text-amber-400 text-xl group-hover:scale-105 transition-transform"></i>
                                <span class="text-[9px] text-slate-600 font-bold">Estrella</span>
                            </button>

                            <!-- Hexágono -->
                            <button type="button" onclick="agregarForma('hexagon')" class="p-2.5 bg-slate-50 hover:bg-slate-100 rounded-xl border border-slate-200 flex flex-col items-center gap-1 group">
                                <i class="fas fa-certificate text-sena-navy text-xl group-hover:scale-105 transition-transform"></i>
                                <span class="text-[9px] text-slate-600 font-bold">Hexágono</span>
                            </button>

                            <!-- Rombo / Diamante -->
                            <button type="button" onclick="agregarForma('diamond')" class="p-2.5 bg-slate-50 hover:bg-slate-100 rounded-xl border border-slate-200 flex flex-col items-center gap-1 group">
                                <div class="w-5 h-5 bg-sky-600 transform rotate-45 group-hover:scale-105 transition-transform"></div>
                                <span class="text-[9px] text-slate-600 font-bold">Rombo</span>
                            </button>

                            <!-- Chevron / Bloque -->
                            <button type="button" onclick="agregarForma('chevron')" class="p-2.5 bg-slate-50 hover:bg-slate-100 rounded-xl border border-slate-200 flex flex-col items-center gap-1 group">
                                <i class="fas fa-chevron-right text-emerald-700 text-lg font-black group-hover:scale-105 transition-transform"></i>
                                <span class="text-[9px] text-slate-600 font-bold">Bloque Flecha</span>
                            </button>

                            <!-- Medalla / Insignia -->
                            <button type="button" onclick="agregarForma('sello_sig')" class="p-2.5 bg-slate-50 hover:bg-slate-100 rounded-xl border border-slate-200 flex flex-col items-center gap-1 group">
                                <i class="fas fa-award text-emerald-500 text-xl group-hover:scale-105 transition-transform"></i>
                                <span class="text-[9px] text-slate-600 font-bold">Sello SIG</span>
                            </button>
                        </div>
                    </div>

                    <!-- 3. Señalética de Seguridad y Salud en el Trabajo (SST) -->
                    <div class="pt-2 border-t border-slate-200/80">
                        <h4 class="text-xs font-black text-slate-700 uppercase tracking-wider mb-2 flex items-center justify-between">
                            <span>Señalética SST & SIG</span>
                            <span class="text-[9px] font-extrabold px-1.5 py-0.5 rounded bg-amber-100 text-amber-800">ISO 45001</span>
                        </h4>
                        <div class="grid grid-cols-2 gap-2">
                            <!-- Señal Peligro / Advertencia -->
                            <button type="button" onclick="agregarForma('senal_alerta')" class="p-2.5 bg-amber-50 hover:bg-amber-100 rounded-xl border border-amber-200 flex items-center gap-2 text-left group">
                                <div class="w-7 h-7 rounded-lg bg-amber-500 text-white flex items-center justify-center font-black text-sm flex-shrink-0 shadow-2xs">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div>
                                    <span class="text-[10px] font-black text-amber-950 block">Advertencia</span>
                                    <span class="text-[9px] text-amber-700">Riesgo / Precaución</span>
                                </div>
                            </button>

                            <!-- Señal Prohibición -->
                            <button type="button" onclick="agregarForma('senal_prohibicion')" class="p-2.5 bg-rose-50 hover:bg-rose-100 rounded-xl border border-rose-200 flex items-center gap-2 text-left group">
                                <div class="w-7 h-7 rounded-full border-2 border-rose-600 text-rose-600 flex items-center justify-center font-black text-xs flex-shrink-0 bg-white shadow-2xs">
                                    <i class="fas fa-ban"></i>
                                </div>
                                <div>
                                    <span class="text-[10px] font-black text-rose-950 block">Prohibición</span>
                                    <span class="text-[9px] text-rose-700">Acción No permitida</span>
                                </div>
                            </button>

                            <!-- Señal Obligación EPP -->
                            <button type="button" onclick="agregarForma('senal_obligacion')" class="p-2.5 bg-sky-50 hover:bg-sky-100 rounded-xl border border-sky-200 flex items-center gap-2 text-left group">
                                <div class="w-7 h-7 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs flex-shrink-0 shadow-2xs">
                                    <i class="fas fa-hard-hat"></i>
                                </div>
                                <div>
                                    <span class="text-[10px] font-black text-sky-950 block">Obligación EPP</span>
                                    <span class="text-[9px] text-sky-700">Protección mandatoria</span>
                                </div>
                            </button>

                            <!-- Rombo NFPA 704 -->
                            <button type="button" onclick="agregarForma('rombo_nfpa')" class="p-2.5 bg-slate-50 hover:bg-slate-100 rounded-xl border border-slate-200 flex items-center gap-2 text-left group">
                                <div class="w-7 h-7 grid grid-cols-2 gap-0.5 p-0.5 bg-slate-800 rounded transform rotate-45 flex-shrink-0 shadow-2xs">
                                    <div class="bg-blue-500"></div>
                                    <div class="bg-red-500"></div>
                                    <div class="bg-yellow-400"></div>
                                    <div class="bg-white"></div>
                                </div>
                                <div>
                                    <span class="text-[10px] font-black text-slate-800 block">Rombo NFPA</span>
                                    <span class="text-[9px] text-slate-500">Seguridad química</span>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- 4. Contenedores, Tarjetas y Banners -->
                    <div class="pt-2 border-t border-slate-200/80">
                        <h4 class="text-xs font-black text-slate-700 uppercase tracking-wider mb-2">Contenedores y Tarjetas</h4>
                        <div class="space-y-1.5">
                            <button type="button" onclick="agregarForma('card')" class="w-full p-2.5 bg-slate-50 hover:bg-slate-100 rounded-xl border border-slate-200 flex items-center justify-between text-left group">
                                <div>
                                    <span class="text-xs font-bold text-slate-800 block">Tarjeta Contenedora Blanca</span>
                                    <span class="text-[10px] text-slate-400">Borde redondeado con sombra flotante</span>
                                </div>
                                <div class="w-10 h-6 border-2 border-slate-300 rounded-lg bg-white shadow-xs"></div>
                            </button>

                            <button type="button" onclick="agregarForma('badge')" class="w-full p-2.5 bg-amber-50/70 hover:bg-amber-100/70 rounded-xl border border-amber-200 flex items-center justify-between text-left group">
                                <div>
                                    <span class="text-xs font-bold text-amber-950 block">Banner Alerta de Seguridad</span>
                                    <span class="text-[10px] text-amber-700">Resaltador ámbar con icono preventivo</span>
                                </div>
                                <div class="w-7 h-6 rounded-md bg-amber-500 text-white flex items-center justify-center text-xs">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                            </button>

                            <button type="button" onclick="agregarForma('card_ambiental')" class="w-full p-2.5 bg-emerald-50/70 hover:bg-emerald-100/70 rounded-xl border border-emerald-200 flex items-center justify-between text-left group">
                                <div>
                                    <span class="text-xs font-bold text-emerald-950 block">Tarjeta Gestión Ambiental</span>
                                    <span class="text-[10px] text-emerald-700">Contenedor verde ecológico ISO 14001</span>
                                </div>
                                <div class="w-7 h-6 rounded-md bg-emerald-600 text-white flex items-center justify-center text-xs">
                                    <i class="fas fa-leaf"></i>
                                </div>
                            </button>

                            <button type="button" onclick="agregarForma('ribbon')" class="w-full p-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl border border-slate-800 flex items-center justify-between text-left group">
                                <div>
                                    <span class="text-xs font-bold text-white block">Cinta / Listón de Encabezado</span>
                                    <span class="text-[10px] text-slate-300">Banner horizontal para títulos mayores</span>
                                </div>
                                <div class="w-8 h-4 bg-sena-neon rounded-xs"></div>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 4. PANEL: MARCOS (MÁSCARAS DE RECORTE TIPO CANVA) -->
                <div id="panel-marcos" class="tab-panel hidden space-y-4">
                    <input type="file" id="frame-direct-file-input" accept="image/png, image/jpeg, image/jpg, image/svg+xml, image/webp" class="hidden">

                    <div class="p-3 bg-gradient-to-br from-emerald-500/10 via-sky-500/5 to-transparent border border-emerald-500/20 rounded-2xl">
                        <div class="flex items-center gap-2 text-emerald-800 font-black text-xs mb-1">
                            <i class="fas fa-crop-alt text-emerald-600"></i>
                            <span>Marcos y Siluetas de Recorte</span>
                        </div>
                        <p class="text-[11px] text-slate-600 leading-relaxed">
                            Inserta un marco. Luego <strong>arrastra una foto encima</strong> o haz clic en <strong>"Cargar Foto"</strong> para enmarcarla con la silueta exacta.
                        </p>
                    </div>

                    <!-- 1. FORMAS GEOMÉTRICAS BÁSICAS -->
                    <div>
                        <div class="flex items-center justify-between mb-2 px-1">
                            <span class="text-[11px] font-bold text-slate-700 uppercase tracking-wider">Geométricos Esenciales</span>
                            <span class="text-[10px] text-emerald-600 font-semibold bg-emerald-50 px-2 py-0.5 rounded-full">6 estilos</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <!-- Marco Circular -->
                            <button type="button" onclick="agregarMarco('circle')" class="p-2.5 bg-slate-50 hover:bg-emerald-50/50 rounded-xl border border-slate-200 hover:border-emerald-400 flex flex-col items-center gap-1.5 group transition-all">
                                <div class="w-12 h-12 rounded-full border-2 border-dashed border-emerald-500 bg-white flex items-center justify-center text-emerald-600 group-hover:scale-105 shadow-xs transition-transform">
                                    <i class="fas fa-image text-xs"></i>
                                </div>
                                <span class="text-[10px] text-slate-700 font-bold group-hover:text-emerald-700">Circular</span>
                            </button>

                            <!-- Marco Cuadrado Redondeado -->
                            <button type="button" onclick="agregarMarco('rounded')" class="p-2.5 bg-slate-50 hover:bg-sky-50/50 rounded-xl border border-slate-200 hover:border-sky-400 flex flex-col items-center gap-1.5 group transition-all">
                                <div class="w-12 h-12 rounded-2xl border-2 border-dashed border-sky-500 bg-white flex items-center justify-center text-sky-600 group-hover:scale-105 shadow-xs transition-transform">
                                    <i class="fas fa-image text-xs"></i>
                                </div>
                                <span class="text-[10px] text-slate-700 font-bold group-hover:text-sky-700">Redondeado</span>
                            </button>

                            <!-- Marco Cuadrado 1:1 -->
                            <button type="button" onclick="agregarMarco('square')" class="p-2.5 bg-slate-50 hover:bg-indigo-50/50 rounded-xl border border-slate-200 hover:border-indigo-400 flex flex-col items-center gap-1.5 group transition-all">
                                <div class="w-12 h-12 rounded-none border-2 border-dashed border-indigo-500 bg-white flex items-center justify-center text-indigo-600 group-hover:scale-105 shadow-xs transition-transform">
                                    <i class="fas fa-image text-xs"></i>
                                </div>
                                <span class="text-[10px] text-slate-700 font-bold group-hover:text-indigo-700">Cuadrado 1:1</span>
                            </button>

                            <!-- Marco Panorámico 16:9 -->
                            <button type="button" onclick="agregarMarco('panoramic')" class="p-2.5 bg-slate-50 hover:bg-teal-50/50 rounded-xl border border-slate-200 hover:border-teal-400 flex flex-col items-center gap-1.5 group transition-all">
                                <div class="w-14 h-9 rounded-lg border-2 border-dashed border-teal-500 bg-white flex items-center justify-center text-teal-600 group-hover:scale-105 shadow-xs transition-transform my-1.5">
                                    <i class="fas fa-image text-xs"></i>
                                </div>
                                <span class="text-[10px] text-slate-700 font-bold group-hover:text-teal-700">Panorámico 16:9</span>
                            </button>

                            <!-- Marco Ovalado -->
                            <button type="button" onclick="agregarMarco('oval')" class="p-2.5 bg-slate-50 hover:bg-amber-50/50 rounded-xl border border-slate-200 hover:border-amber-400 flex flex-col items-center gap-1.5 group transition-all">
                                <div class="w-14 h-10 rounded-[50%] border-2 border-dashed border-amber-500 bg-white flex items-center justify-center text-amber-600 group-hover:scale-105 shadow-xs transition-transform my-1">
                                    <i class="fas fa-image text-xs"></i>
                                </div>
                                <span class="text-[10px] text-slate-700 font-bold group-hover:text-amber-700">Óvalo</span>
                            </button>

                            <!-- Marco Ventana en Arco (Arch) -->
                            <button type="button" onclick="agregarMarco('arch')" class="p-2.5 bg-slate-50 hover:bg-purple-50/50 rounded-xl border border-slate-200 hover:border-purple-400 flex flex-col items-center gap-1.5 group transition-all">
                                <div class="w-11 h-12 rounded-t-full border-2 border-dashed border-purple-500 bg-white flex items-center justify-center text-purple-600 group-hover:scale-105 shadow-xs transition-transform">
                                    <i class="fas fa-image text-xs"></i>
                                </div>
                                <span class="text-[10px] text-slate-700 font-bold group-hover:text-purple-700">Ventana Arco</span>
                            </button>
                        </div>
                    </div>

                    <!-- 2. FORMAS INSTITUCIONALES Y SST (SENA / SIG) -->
                    <div>
                        <div class="flex items-center justify-between mb-2 px-1">
                            <span class="text-[11px] font-bold text-slate-700 uppercase tracking-wider">Institucional & SST</span>
                            <span class="text-[10px] text-amber-600 font-semibold bg-amber-50 px-2 py-0.5 rounded-full">SST & ISO</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <!-- Marco Hexagonal (ISO / Procesos) -->
                            <button type="button" onclick="agregarMarco('hexagon')" class="p-2.5 bg-slate-50 hover:bg-emerald-50/50 rounded-xl border border-slate-200 hover:border-emerald-400 flex flex-col items-center gap-1.5 group transition-all">
                                <div class="w-12 h-12 border-2 border-dashed border-emerald-600 bg-white flex items-center justify-center text-emerald-600 group-hover:scale-105 shadow-xs transition-transform" style="clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);">
                                    <i class="fas fa-image text-xs"></i>
                                </div>
                                <span class="text-[10px] text-slate-700 font-bold group-hover:text-emerald-700">Hexágono ISO</span>
                            </button>

                            <!-- Marco Escudo Seguridad SST -->
                            <button type="button" onclick="agregarMarco('shield')" class="p-2.5 bg-slate-50 hover:bg-amber-50/50 rounded-xl border border-slate-200 hover:border-amber-400 flex flex-col items-center gap-1.5 group transition-all">
                                <div class="w-11 h-12 border-2 border-dashed border-amber-600 bg-white flex items-center justify-center text-amber-600 group-hover:scale-105 shadow-xs transition-transform" style="clip-path: polygon(0% 0%, 100% 0%, 100% 65%, 50% 100%, 0% 65%);">
                                    <i class="fas fa-shield-alt text-xs"></i>
                                </div>
                                <span class="text-[10px] text-slate-700 font-bold group-hover:text-amber-700">Escudo SST</span>
                            </button>

                            <!-- Marco Rombo de Advertencia -->
                            <button type="button" onclick="agregarMarco('diamond')" class="p-2.5 bg-slate-50 hover:bg-orange-50/50 rounded-xl border border-slate-200 hover:border-orange-400 flex flex-col items-center gap-1.5 group transition-all">
                                <div class="w-11 h-11 rotate-45 border-2 border-dashed border-orange-500 bg-white flex items-center justify-center text-orange-600 group-hover:scale-105 shadow-xs transition-transform my-1">
                                    <i class="fas fa-image text-xs -rotate-45"></i>
                                </div>
                                <span class="text-[10px] text-slate-700 font-bold group-hover:text-orange-700">Rombo Alerta</span>
                            </button>

                            <!-- Marco Estrella de Logros -->
                            <button type="button" onclick="agregarMarco('star')" class="p-2.5 bg-slate-50 hover:bg-yellow-50/50 rounded-xl border border-slate-200 hover:border-yellow-400 flex flex-col items-center gap-1.5 group transition-all">
                                <div class="w-12 h-12 border-2 border-dashed border-yellow-500 bg-white flex items-center justify-center text-yellow-600 group-hover:scale-105 shadow-xs transition-transform" style="clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);">
                                    <i class="fas fa-star text-xs"></i>
                                </div>
                                <span class="text-[10px] text-slate-700 font-bold group-hover:text-yellow-700">Estrella Logro</span>
                            </button>

                            <!-- Marco Cruz de Brigadista -->
                            <button type="button" onclick="agregarMarco('cross')" class="p-2.5 bg-slate-50 hover:bg-rose-50/50 rounded-xl border border-slate-200 hover:border-rose-400 flex flex-col items-center gap-1.5 group transition-all">
                                <div class="w-11 h-11 border-2 border-dashed border-rose-500 bg-white flex items-center justify-center text-rose-600 group-hover:scale-105 shadow-xs transition-transform my-0.5" style="clip-path: polygon(35% 0%, 65% 0%, 65% 35%, 100% 35%, 100% 65%, 65% 65%, 65% 100%, 35% 100%, 35% 65%, 0% 65%, 0% 35%, 35% 35%);">
                                    <i class="fas fa-plus text-xs"></i>
                                </div>
                                <span class="text-[10px] text-slate-700 font-bold group-hover:text-rose-700">Cruz Brigada</span>
                            </button>

                            <!-- Marco Burbuja de Diálogo -->
                            <button type="button" onclick="agregarMarco('bubble')" class="p-2.5 bg-slate-50 hover:bg-blue-50/50 rounded-xl border border-slate-200 hover:border-blue-400 flex flex-col items-center gap-1.5 group transition-all">
                                <div class="w-12 h-10 rounded-xl rounded-bl-none border-2 border-dashed border-blue-500 bg-white flex items-center justify-center text-blue-600 group-hover:scale-105 shadow-xs transition-transform my-1">
                                    <i class="fas fa-comment text-xs"></i>
                                </div>
                                <span class="text-[10px] text-slate-700 font-bold group-hover:text-blue-700">Burbuja Cita</span>
                            </button>
                        </div>
                    </div>

                    <!-- 3. MOCKUPS DE DISPOSITIVOS -->
                    <div>
                        <div class="flex items-center justify-between mb-2 px-1">
                            <span class="text-[11px] font-bold text-slate-700 uppercase tracking-wider">Dispositivos & Software</span>
                            <span class="text-[10px] text-sky-600 font-semibold bg-sky-50 px-2 py-0.5 rounded-full">Mockups</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <!-- Marco Monitor PC -->
                            <button type="button" onclick="agregarMarco('device_desktop')" class="p-2.5 bg-slate-50 hover:bg-slate-100 rounded-xl border border-slate-200 hover:border-slate-400 flex flex-col items-center gap-1.5 group transition-all">
                                <div class="w-13 h-10 border-2 border-slate-700 bg-slate-800 rounded-md flex flex-col items-center justify-center text-slate-300 group-hover:scale-105 shadow-xs transition-transform my-1 relative">
                                    <div class="w-10 h-6 bg-sky-100/30 rounded-xs flex items-center justify-center">
                                        <i class="fas fa-desktop text-[10px]"></i>
                                    </div>
                                    <div class="absolute -bottom-2 w-3 h-1 bg-slate-600"></div>
                                </div>
                                <span class="text-[10px] text-slate-700 font-bold group-hover:text-slate-900">Monitor PC</span>
                            </button>

                            <!-- Marco Smartphone Móvil -->
                            <button type="button" onclick="agregarMarco('device_mobile')" class="p-2.5 bg-slate-50 hover:bg-slate-100 rounded-xl border border-slate-200 hover:border-slate-400 flex flex-col items-center gap-1.5 group transition-all">
                                <div class="w-7 h-12 border-2 border-slate-700 bg-slate-800 rounded-xl flex flex-col items-center justify-center text-slate-300 group-hover:scale-105 shadow-xs transition-transform relative">
                                    <div class="w-5 h-9 bg-emerald-100/30 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-mobile-alt text-[10px]"></i>
                                    </div>
                                </div>
                                <span class="text-[10px] text-slate-700 font-bold group-hover:text-slate-900">Smartphone</span>
                            </button>
                        </div>
                    </div>

                    <!-- 4. FOTOGRÁFICOS Y CREATIVOS -->
                    <div>
                        <div class="flex items-center justify-between mb-2 px-1">
                            <span class="text-[11px] font-bold text-slate-700 uppercase tracking-wider">Fotográficos & Retro</span>
                            <span class="text-[10px] text-purple-600 font-semibold bg-purple-50 px-2 py-0.5 rounded-full">Creativos</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <!-- Marco Polaroid Vintage -->
                            <button type="button" onclick="agregarMarco('polaroid')" class="p-2.5 bg-slate-50 hover:bg-rose-50/50 rounded-xl border border-slate-200 hover:border-rose-400 flex flex-col items-center gap-1.5 group transition-all">
                                <div class="w-10 h-12 bg-white border border-slate-300 rounded-xs shadow-xs p-1 flex flex-col items-center group-hover:scale-105 transition-transform">
                                    <div class="w-full h-7 bg-slate-100 border border-dashed border-rose-400 flex items-center justify-center text-rose-500">
                                        <i class="fas fa-camera text-[9px]"></i>
                                    </div>
                                    <div class="w-4 h-0.5 bg-slate-200 mt-1.5 rounded-full"></div>
                                </div>
                                <span class="text-[10px] text-slate-700 font-bold group-hover:text-rose-700">Polaroid</span>
                            </button>

                            <!-- Marco Sello Postal -->
                            <button type="button" onclick="agregarMarco('stamp')" class="p-2.5 bg-slate-50 hover:bg-cyan-50/50 rounded-xl border border-slate-200 hover:border-cyan-400 flex flex-col items-center gap-1.5 group transition-all">
                                <div class="w-11 h-11 bg-white border-2 border-dashed border-cyan-500 rounded-sm shadow-xs flex items-center justify-center text-cyan-600 group-hover:scale-105 transition-transform">
                                    <i class="fas fa-stamp text-xs"></i>
                                </div>
                                <span class="text-[10px] text-slate-700 font-bold group-hover:text-cyan-700">Sello Postal</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 5. PANEL: SUBIDAS / MULTIMEDIA -->
                <div id="panel-subidas" class="tab-panel hidden space-y-4">
                    <input type="file" id="drawer-file-input" accept="image/png, image/jpeg, image/jpg, image/svg+xml, image/webp" class="hidden">
                    
                    <!-- Botones de Acción: Cargar Imagen y Añadir Enlace de Video -->
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" onclick="document.getElementById('drawer-file-input').click()" 
                                class="py-2.5 px-3 rounded-2xl bg-sena-navy hover:bg-slate-800 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-sm transition-all cursor-pointer border border-[#243B66]">
                            <i class="fas fa-cloud-arrow-up text-sena-neon text-xs"></i>
                            <span id="label-subir-multimedia">Subir Imagen</span>
                        </button>

                        <button type="button" onclick="abrirModalNuevoVideo()" 
                                class="py-2.5 px-3 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-sm transition-all cursor-pointer border border-rose-500">
                            <i class="fab fa-youtube text-white text-xs"></i>
                            <span>Añadir Video</span>
                        </button>
                    </div>

                    <div class="p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-[11px] text-slate-500">
                        <i class="fas fa-info-circle text-emerald-600 mr-1"></i>
                        <strong>Imágenes y Videos:</strong> Haz clic o arrastra al lienzo. Los videos se reproducen en ventana dentro de la página sin salirse.
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                                <span>Biblioteca Multimedia</span>
                            </h4>
                            <span class="text-[10px] font-black px-2 py-0.5 rounded-full bg-slate-100 text-slate-600" id="count-uploads">0</span>
                        </div>
                        
                        <div id="uploads-gallery" class="grid grid-cols-2 gap-2 max-h-84 overflow-y-auto pr-0.5">
                            <!-- Inyectadas dinámicamente -->
                        </div>
                    </div>
                </div>

                <!-- 6. PANEL: FONDO DEL LIENZO -->
                <div id="panel-fondo" class="tab-panel hidden space-y-4">
                    <div>
                        <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Colores Sólidos Institucionales</h4>
                        <div class="grid grid-cols-4 gap-2">
                            <button type="button" onclick="cambiarFondo('#ffffff')" title="Blanco" class="h-10 rounded-xl border-2 border-slate-200 bg-white shadow-xs hover:scale-105 transition-all"></button>
                            <button type="button" onclick="cambiarFondo('#1A2B4C')" title="Azul Marino SENA" class="h-10 rounded-xl bg-sena-navy shadow-xs hover:scale-105 transition-all"></button>
                            <button type="button" onclick="cambiarFondo('#2E6F40')" title="Verde SENA" class="h-10 rounded-xl bg-sena-green shadow-xs hover:scale-105 transition-all"></button>
                            <button type="button" onclick="cambiarFondo('#0f172a')" title="Slate Oscuro" class="h-10 rounded-xl bg-slate-900 shadow-xs hover:scale-105 transition-all"></button>
                            <button type="button" onclick="cambiarFondo('#f8fafc')" title="Gris Muy Claro" class="h-10 rounded-xl bg-slate-50 border border-slate-200 shadow-xs hover:scale-105 transition-all"></button>
                            <button type="button" onclick="cambiarFondo('#ecfdf5')" title="Verde Esmeralda Sutil" class="h-10 rounded-xl bg-emerald-50 border border-emerald-200 shadow-xs hover:scale-105 transition-all"></button>
                            <button type="button" onclick="cambiarFondo('#fffbeb')" title="Ámbar Sutil" class="h-10 rounded-xl bg-amber-50 border border-amber-200 shadow-xs hover:scale-105 transition-all"></button>
                            <button type="button" onclick="cambiarFondo('#f0f9ff')" title="Azul Cielo Sutil" class="h-10 rounded-xl bg-sky-50 border border-sky-200 shadow-xs hover:scale-105 transition-all"></button>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Color Personalizado</h4>
                        <div class="flex items-center gap-2">
                            <input type="color" id="input-custom-bg" value="#ffffff" class="w-10 h-10 rounded-xl border border-slate-200 cursor-pointer p-0 bg-transparent">
                            <span class="text-xs font-mono text-slate-600 font-bold" id="hex-custom-bg">#FFFFFF</span>
                        </div>
                    </div>
                </div>

            </div>
        </aside>

        <!-- C. ÁREA CENTRAL DE TRABAJO (WORKSPACE + BARRA LIENZO + LIENZO) -->
        <main class="flex-grow flex flex-col overflow-hidden relative">

            <!-- ========================================================= -->
            <!-- BARRA SUPERIOR DEL LIENZO (MODERNA, COMPACTA Y SIN DESBORDES) -->
            <!-- ========================================================= -->
            <div class="h-14 bg-[#1A2B4C] border-b border-[#243B66] px-3 sm:px-4 flex items-center justify-between text-white z-20 flex-shrink-0 shadow-sm gap-2 flex-nowrap overflow-x-hidden select-none">
                
                <!-- Izquierda: Selector Interactivo de Proporciones y Pastilla Módulo -->
                <div class="flex items-center gap-2 flex-shrink-0 min-w-0">
                    <div class="relative inline-block text-left" id="dropdown-lienzo-container">
                        <button type="button" id="btn-dimensiones-lienzo" onclick="toggleMenuDimensiones()" 
                                class="flex items-center gap-1.5 bg-[#243B66]/90 hover:bg-[#243B66] px-2.5 sm:px-3 py-1.5 rounded-xl border border-white/15 text-xs cursor-pointer transition-all hover:border-[#33FF57]/50 shadow-xs group"
                                title="Haz clic para cambiar el tamaño o la altura vertical del lienzo">
                            <span class="font-black text-[#33FF57] uppercase tracking-wider text-[10px]">LIENZO:</span>
                            <span id="label-dimensiones-lienzo" class="font-bold text-white text-[11px] whitespace-nowrap">960 × 960 px (1:1)</span>
                            <i class="fas fa-chevron-down text-[10px] text-slate-300 group-hover:text-white transition-transform duration-200" id="icon-chevron-dimensiones"></i>
                        </button>

                        <!-- Menú Desplegable Flotante -->
                        <div id="menu-dimensiones-lienzo" class="hidden absolute left-0 mt-2 w-84 rounded-2xl bg-[#1A2B4C] border border-[#243B66] shadow-2xl z-50 p-3 space-y-2 text-white text-xs">
                            <div class="flex items-center justify-between pb-2 border-b border-[#243B66]">
                                <span class="font-extrabold text-white text-xs flex items-center gap-1.5">
                                    <i class="fas fa-expand text-[#33FF57]"></i> Dimensiones del Lienzo
                                </span>
                                <span class="text-[10px] text-[#33FF57] font-bold">Espacio Vertical</span>
                            </div>

                            <!-- Opciones preestablecidas -->
                            <div class="space-y-1 max-h-72 overflow-y-auto pr-1">
                                <!-- 960 x 960 (1:1 Cuadrado Extra Alto - Recomendado) -->
                                <button type="button" onclick="cambiarTamanoLienzo(960, 960, '1:1')" 
                                        class="btn-opt-dimension w-full p-2 rounded-xl text-left hover:bg-white/10 flex items-center justify-between transition-colors cursor-pointer group" data-dims="960x960">
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-bold text-white text-xs">960 × 960 px</span>
                                            <span class="px-1.5 py-0.2 rounded text-[9px] font-black bg-[#33FF57]/20 text-[#33FF57]">1:1 • EXTRA ALTO</span>
                                        </div>
                                        <span class="text-[10px] text-slate-400 block mt-0.5">Espacio vertical amplio y cómodo (Recomendado)</span>
                                    </div>
                                    <i class="fas fa-check text-[#33FF57] text-xs check-opt hidden"></i>
                                </button>

                                <!-- 960 x 1080 (16:18 Máximo Vertical / Doble Altura) -->
                                <button type="button" onclick="cambiarTamanoLienzo(960, 1080, '16:18')" 
                                        class="btn-opt-dimension w-full p-2 rounded-xl text-left hover:bg-white/10 flex items-center justify-between transition-colors cursor-pointer group" data-dims="960x1080">
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-bold text-white text-xs">960 × 1080 px</span>
                                            <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-amber-400/20 text-amber-300">DOBLE ALTURA</span>
                                        </div>
                                        <span class="text-[10px] text-slate-400 block mt-0.5">Doble espacio vertical para módulos con mucho contenido</span>
                                    </div>
                                    <i class="fas fa-check text-[#33FF57] text-xs check-opt hidden"></i>
                                </button>

                                <!-- 960 x 1200 (Formato Extendido Documento / Dossier) -->
                                <button type="button" onclick="cambiarTamanoLienzo(960, 1200, '4:5')" 
                                        class="btn-opt-dimension w-full p-2 rounded-xl text-left hover:bg-white/10 flex items-center justify-between transition-colors cursor-pointer group" data-dims="960x1200">
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-bold text-white text-xs">960 × 1200 px</span>
                                            <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-purple-500/20 text-purple-300">EXTENDIDO</span>
                                        </div>
                                        <span class="text-[10px] text-slate-400 block mt-0.5">Formato largo vertical estilo documento institucional</span>
                                    </div>
                                    <i class="fas fa-check text-[#33FF57] text-xs check-opt hidden"></i>
                                </button>

                                <!-- 960 x 800 (6:5 Alto Extendido) -->
                                <button type="button" onclick="cambiarTamanoLienzo(960, 800, '6:5')" 
                                        class="btn-opt-dimension w-full p-2 rounded-xl text-left hover:bg-white/10 flex items-center justify-between transition-colors cursor-pointer group" data-dims="960x800">
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-bold text-white text-xs">960 × 800 px</span>
                                            <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-white/10 text-slate-300">6:5 ALTO</span>
                                        </div>
                                        <span class="text-[10px] text-slate-400 block mt-0.5">Espacio vertical intermedio balanceado</span>
                                    </div>
                                    <i class="fas fa-check text-[#33FF57] text-xs check-opt hidden"></i>
                                </button>

                                <!-- 960 x 640 (3:2 Formato Medio) -->
                                <button type="button" onclick="cambiarTamanoLienzo(960, 640, '3:2')" 
                                        class="btn-opt-dimension w-full p-2 rounded-xl text-left hover:bg-white/10 flex items-center justify-between transition-colors cursor-pointer group" data-dims="960x640">
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-bold text-white text-xs">960 × 640 px</span>
                                            <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-white/10 text-slate-300">3:2</span>
                                        </div>
                                        <span class="text-[10px] text-slate-400 block mt-0.5">Formato horizontal con altura media</span>
                                    </div>
                                    <i class="fas fa-check text-[#33FF57] text-xs check-opt hidden"></i>
                                </button>

                                <!-- 960 x 540 (16:9 Panorámico Clásico) -->
                                <button type="button" onclick="cambiarTamanoLienzo(960, 540, '16:9')" 
                                        class="btn-opt-dimension w-full p-2 rounded-xl text-left hover:bg-white/10 flex items-center justify-between transition-colors cursor-pointer group" data-dims="960x540">
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-bold text-white text-xs">960 × 540 px</span>
                                            <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-white/10 text-slate-300">16:9</span>
                                        </div>
                                        <span class="text-[10px] text-slate-400 block mt-0.5">Panorámico estándar</span>
                                    </div>
                                    <i class="fas fa-check text-[#33FF57] text-xs check-opt hidden"></i>
                                </button>

                                <!-- 640 x 960 (Formato Vertical Infografía) -->
                                <button type="button" onclick="cambiarTamanoLienzo(640, 960, 'Vertical')" 
                                        class="btn-opt-dimension w-full p-2 rounded-xl text-left hover:bg-white/10 flex items-center justify-between transition-colors cursor-pointer group" data-dims="640x960">
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-bold text-white text-xs">640 × 960 px</span>
                                            <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-sky-500/20 text-sky-300">VERTICAL</span>
                                        </div>
                                        <span class="text-[10px] text-slate-400 block mt-0.5">Estilo ficha, documento o infografía</span>
                                    </div>
                                    <i class="fas fa-check text-[#33FF57] text-xs check-opt hidden"></i>
                                </button>
                            </div>

                            <!-- Tamaño Personalizado -->
                            <div class="pt-2 border-t border-[#243B66]">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1.5">Medida Personalizada:</span>
                                <div class="grid grid-cols-2 gap-2 mb-2">
                                    <div>
                                        <label class="text-[9px] text-slate-400 block mb-0.5">Ancho (px)</label>
                                        <input type="number" id="input-custom-width" value="960" min="400" max="2400" class="w-full bg-[#243B66] border border-white/20 rounded-lg px-2 py-1 text-white text-xs font-bold text-center">
                                    </div>
                                    <div>
                                        <label class="text-[9px] text-slate-400 block mb-0.5">Alto (px)</label>
                                        <input type="number" id="input-custom-height" value="960" min="300" max="2400" class="w-full bg-[#243B66] border border-white/20 rounded-lg px-2 py-1 text-white text-xs font-bold text-center">
                                    </div>
                                </div>
                                <button type="button" onclick="aplicarTamanoPersonalizado()" class="w-full py-1.5 rounded-full bg-[#33FF57] hover:bg-[#2ae049] text-[#1A2B4C] font-black text-xs transition-colors cursor-pointer shadow-md shadow-emerald-500/20">
                                    Aplicar Medidas
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Pastilla Módulo con Punto Neón -->
                    <div class="hidden xl:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-black bg-[#243B66] text-[#33FF57] border border-[#33FF57]/40 shadow-xs flex-shrink-0 max-w-[170px] truncate">
                        <span class="w-2 h-2 rounded-full bg-[#33FF57] animate-pulse flex-shrink-0"></span>
                        <span class="truncate">Módulo #{{ $module->id ?? 1 }}: {{ $module->title ?? $module->titulo ?? 'Ambiental' }}</span>
                    </div>
                </div>

                <!-- Centro: Deshacer / Rehacer y Zoom Compacto -->
                <div class="flex items-center gap-1 bg-[#243B66]/80 px-2 py-1 rounded-full border border-white/10 shadow-xs flex-shrink-0">
                    <!-- Deshacer / Rehacer -->
                    <button type="button" id="btn-undo" title="Deshacer (Ctrl + Z)" 
                            class="w-6 h-6 rounded-full hover:bg-white/10 text-slate-200 hover:text-white flex items-center justify-center transition-all disabled:opacity-30 disabled:hover:bg-transparent cursor-pointer">
                        <i class="fas fa-undo-alt text-xs"></i>
                    </button>
                    <button type="button" id="btn-redo" title="Rehacer (Ctrl + Y)" 
                            class="w-6 h-6 rounded-full hover:bg-white/10 text-slate-200 hover:text-white flex items-center justify-center transition-all disabled:opacity-30 disabled:hover:bg-transparent cursor-pointer">
                        <i class="fas fa-redo-alt text-xs"></i>
                    </button>

                    <div class="w-px h-3.5 bg-white/20 mx-0.5"></div>

                    <!-- Zoom -->
                    <button type="button" id="btn-zoom-out" title="Alejar zoom" 
                            class="w-6 h-6 rounded-full hover:bg-white/10 text-slate-200 hover:text-white flex items-center justify-center transition-all cursor-pointer">
                        <i class="fas fa-search-minus text-xs"></i>
                    </button>
                    <span id="label-zoom" class="text-xs font-extrabold text-white min-w-[32px] text-center">100%</span>
                    <button type="button" id="btn-zoom-in" title="Acercar zoom" 
                            class="w-6 h-6 rounded-full hover:bg-white/10 text-slate-200 hover:text-white flex items-center justify-center transition-all cursor-pointer">
                        <i class="fas fa-search-plus text-xs"></i>
                    </button>
                    <button type="button" id="btn-zoom-fit" title="Ajustar al 100%" 
                            class="hidden sm:inline-block text-[10px] font-black px-2 py-0.5 rounded-full bg-white/10 hover:bg-white/20 text-[#33FF57] transition-all cursor-pointer">
                        100%
                    </button>
                </div>

                <!-- Derecha: Visualizar y Guardar Módulo -->
                <div class="flex items-center gap-1.5 sm:gap-2 flex-shrink-0">
                    <!-- Visualizar Módulo -->
                    <button type="button" id="btn-preview-canvas" 
                            class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1.5 rounded-full text-xs font-bold text-slate-200 bg-[#243B66] hover:bg-[#2d477a] hover:text-white border border-white/10 shadow-xs transition-all cursor-pointer flex-shrink-0"
                            title="Visualizar el módulo como lo verán los aprendices">
                        <i class="fas fa-eye text-[#33FF57]"></i>
                        <span class="hidden md:inline">Visualizar</span>
                    </button>

                    <!-- Guardar Módulo -->
                    <button type="button" id="btn-save-canvas" onclick="guardarLienzoEnBackend()" 
                            class="inline-flex items-center gap-1.5 px-3 sm:px-3.5 py-1.5 rounded-full text-xs font-black text-[#1A2B4C] bg-[#33FF57] hover:bg-[#2ae049] shadow-md shadow-emerald-500/30 transition-all cursor-pointer flex-shrink-0">
                        <i class="fas fa-save text-xs" id="icon-save"></i>
                        <span id="btn-save-text">Guardar</span>
                    </button>
                </div>

            </div>

            <!-- ========================================================= -->
            <!-- BARRA DE PROPIEDADES CONTEXTUALES SUPERIOR                -->
            <!-- ========================================================= -->
            <div id="context-toolbar" class="min-h-11 bg-white border-b border-slate-200 px-4 py-1.5 flex items-center justify-between text-xs font-medium z-10 shadow-2xs flex-wrap gap-2">
                
                <!-- 1. Opciones cuando NO hay nada seleccionado -->
                <div id="context-empty" class="flex items-center gap-3 text-slate-500 py-0.5">
                    <span class="text-[11px] text-slate-400 flex items-center gap-1.5">
                        <i class="fas fa-info-circle text-sky-500"></i> Selecciona o haz doble clic sobre un elemento del lienzo para editar sus propiedades
                    </span>
                </div>

                <!-- 2. Opciones cuando un TEXTO está seleccionado (Canva Toolbar) -->
                <div id="context-text" class="hidden flex items-center gap-1.5 flex-wrap">
                    <!-- Selector de Fuente (Google Fonts Canva) -->
                    <select id="text-font-family" class="h-8 px-2.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-800 text-xs font-bold cursor-pointer max-w-[140px] truncate" title="Tipo de fuente">
                        <option value="Inter" style="font-family: 'Inter', sans-serif;">Inter (Limpia)</option>
                        <option value="Montserrat" style="font-family: 'Montserrat', sans-serif;">Montserrat (Titulares)</option>
                        <option value="Poppins" style="font-family: 'Poppins', sans-serif;">Poppins (Moderna)</option>
                        <option value="Bebas Neue" style="font-family: 'Bebas Neue', sans-serif;">Bebas Neue (Impacto)</option>
                        <option value="Oswald" style="font-family: 'Oswald', sans-serif;">Oswald (Condensada)</option>
                        <option value="Anton" style="font-family: 'Anton', sans-serif;">Anton (Pesada)</option>
                        <option value="Playfair Display" style="font-family: 'Playfair Display', serif;">Playfair (Elegante)</option>
                        <option value="Pacifico" style="font-family: 'Pacifico', cursive;">Pacifico (Manuscrita)</option>
                        <option value="Caveat" style="font-family: 'Caveat', cursive;">Caveat (Pizarra)</option>
                        <option value="Dancing Script" style="font-family: 'Dancing Script', cursive;">Dancing Script (Caligráfica)</option>
                        <option value="Fira Code" style="font-family: 'Fira Code', monospace;">Fira Code (Técnica)</option>
                        <option value="Roboto" style="font-family: 'Roboto', sans-serif;">Roboto (Neutral)</option>
                        <option value="Arial" style="font-family: Arial, sans-serif;">Arial</option>
                        <option value="Times New Roman" style="font-family: 'Times New Roman', serif;">Times New Roman</option>
                    </select>

                    <!-- Control de Tamaño de Fuente -->
                    <div class="flex items-center border border-slate-200 rounded-lg overflow-hidden bg-slate-50 shadow-2xs">
                        <button type="button" id="btn-font-dec" title="Disminuir tamaño" class="w-6 h-8 hover:bg-slate-200 font-black text-slate-700 flex items-center justify-center transition-colors text-xs">-</button>
                        <input type="number" id="text-font-size" value="24" min="8" max="250" class="w-10 h-8 text-center bg-transparent border-none text-xs font-black text-slate-800 p-0 focus:outline-hidden" title="Tamaño en puntos">
                        <button type="button" id="btn-font-inc" title="Aumentar tamaño" class="w-6 h-8 hover:bg-slate-200 font-black text-slate-700 flex items-center justify-center transition-colors text-xs">+</button>
                    </div>

                    <!-- Color de Texto + Paleta Rápida -->
                    <div class="flex items-center gap-1 bg-slate-50 px-1.5 py-1 rounded-lg border border-slate-200">
                        <input type="color" id="text-color" value="#1e293b" title="Selector libre de color" class="w-6 h-6 rounded-md border border-slate-300 cursor-pointer p-0">
                        <!-- Muestras rápidas institucionales -->
                        <div class="hidden xl:flex items-center gap-0.5">
                            <button type="button" onclick="aplicarColorTextoRapido('#FFFFFF')" class="w-4 h-4 rounded-full bg-white border border-slate-300 hover:scale-110 transition-transform" title="Blanco"></button>
                            <button type="button" onclick="aplicarColorTextoRapido('#1E293B')" class="w-4 h-4 rounded-full bg-slate-800 hover:scale-110 transition-transform" title="Negro Slate"></button>
                            <button type="button" onclick="aplicarColorTextoRapido('#1A2B4C')" class="w-4 h-4 rounded-full bg-[#1A2B4C] hover:scale-110 transition-transform" title="Azul SISIG"></button>
                            <button type="button" onclick="aplicarColorTextoRapido('#2E6F40')" class="w-4 h-4 rounded-full bg-[#2E6F40] hover:scale-110 transition-transform" title="Verde SENA"></button>
                            <button type="button" onclick="aplicarColorTextoRapido('#33FF57')" class="w-4 h-4 rounded-full bg-[#33FF57] hover:scale-110 transition-transform" title="Verde Neón"></button>
                            <button type="button" onclick="aplicarColorTextoRapido('#EF4444')" class="w-4 h-4 rounded-full bg-rose-500 hover:scale-110 transition-transform" title="Rojo Peligro"></button>
                            <button type="button" onclick="aplicarColorTextoRapido('#F59E0B')" class="w-4 h-4 rounded-full bg-amber-500 hover:scale-110 transition-transform" title="Amarillo Precaución"></button>
                        </div>
                    </div>

                    <div class="h-4 w-px bg-slate-200 mx-0.5"></div>

                    <!-- Estilos B / I / U / S / aA -->
                    <div class="flex items-center gap-0.5 bg-slate-50 p-0.5 rounded-lg border border-slate-200">
                        <button type="button" id="btn-text-bold" title="Negrita (Ctrl + B)" class="w-7 h-7 rounded-md hover:bg-slate-200 text-slate-700 font-black flex items-center justify-center text-xs transition-colors">B</button>
                        <button type="button" id="btn-text-italic" title="Cursiva (Ctrl + I)" class="w-7 h-7 rounded-md hover:bg-slate-200 text-slate-700 font-serif italic flex items-center justify-center text-xs transition-colors">I</button>
                        <button type="button" id="btn-text-underline" title="Subrayado (Ctrl + U)" class="w-7 h-7 rounded-md hover:bg-slate-200 text-slate-700 underline flex items-center justify-center text-xs transition-colors">U</button>
                        <button type="button" id="btn-text-strike" title="Tachado" class="w-7 h-7 rounded-md hover:bg-slate-200 text-slate-700 line-through flex items-center justify-center text-xs transition-colors">S</button>
                        <button type="button" id="btn-text-case" title="Alternar Mayúsculas / Minúsculas (Canva aA)" class="px-1.5 h-7 rounded-md hover:bg-slate-200 text-slate-700 font-bold flex items-center justify-center text-[11px] transition-colors">aA</button>
                    </div>

                    <div class="h-4 w-px bg-slate-200 mx-0.5"></div>

                    <!-- Alineación -->
                    <div class="flex items-center gap-0.5 bg-slate-50 p-0.5 rounded-lg border border-slate-200">
                        <button type="button" onclick="setTextAlign('left')" title="Alinear a la izquierda" class="w-7 h-7 rounded-md hover:bg-slate-200 text-slate-700 flex items-center justify-center text-xs transition-colors"><i class="fas fa-align-left"></i></button>
                        <button type="button" onclick="setTextAlign('center')" title="Centrar texto" class="w-7 h-7 rounded-md hover:bg-slate-200 text-slate-700 flex items-center justify-center text-xs transition-colors"><i class="fas fa-align-center"></i></button>
                        <button type="button" onclick="setTextAlign('right')" title="Alinear a la derecha" class="w-7 h-7 rounded-md hover:bg-slate-200 text-slate-700 flex items-center justify-center text-xs transition-colors"><i class="fas fa-align-right"></i></button>
                        <button type="button" onclick="setTextAlign('justify')" title="Justificar texto" class="w-7 h-7 rounded-md hover:bg-slate-200 text-slate-700 flex items-center justify-center text-xs transition-colors"><i class="fas fa-align-justify"></i></button>
                    </div>

                    <!-- Listas y Viñetas -->
                    <div class="flex items-center gap-0.5 bg-slate-50 p-0.5 rounded-lg border border-slate-200">
                        <button type="button" id="btn-text-bullets" onclick="toggleTextBullets()" title="Lista con viñetas" class="w-7 h-7 rounded-md hover:bg-slate-200 text-slate-700 flex items-center justify-center text-xs transition-colors"><i class="fas fa-list-ul"></i></button>
                        <button type="button" id="btn-text-numbers" onclick="toggleTextNumbering()" title="Lista numerada" class="w-7 h-7 rounded-md hover:bg-slate-200 text-slate-700 flex items-center justify-center text-xs transition-colors"><i class="fas fa-list-ol"></i></button>
                    </div>

                    <div class="h-4 w-px bg-slate-200 mx-0.5"></div>

                    <!-- Espaciado (Tracking e Interlineado) -->
                    <button type="button" onclick="toggleTextSpacingMenu()" title="Interlineado y espaciado de letras" class="h-8 px-2.5 rounded-lg hover:bg-slate-100 text-slate-700 flex items-center gap-1.5 text-xs font-bold border border-slate-200 bg-white transition-colors">
                        <i class="fas fa-arrows-alt-v text-[11px] text-slate-500"></i> Espaciado
                    </button>

                    <!-- Efectos de Texto Canva -->
                    <button type="button" onclick="toggleTextEffectsMenu()" title="Efectos de texto estilo Canva" class="h-8 px-3 rounded-lg hover:bg-emerald-50 text-emerald-800 flex items-center gap-1.5 text-xs font-black border border-emerald-300 bg-emerald-50/50 shadow-2xs transition-all">
                        <i class="fas fa-magic text-[11px] text-emerald-600"></i> Efectos
                    </button>
                </div>

                <!-- 4. Opciones cuando una FORMA está seleccionada (Canva Shape Toolbar) -->
                <div id="context-shape" class="hidden flex items-center gap-2 flex-wrap">
                    <!-- Relleno + Muestras Rápidas -->
                    <div class="flex items-center gap-1.5 bg-slate-50 px-2 py-1 rounded-lg border border-slate-200" id="fill-control-group">
                        <label class="text-[11px] font-bold text-slate-500">Relleno:</label>
                        <input type="color" id="shape-fill" value="#2E6F40" class="w-6 h-6 rounded-md border border-slate-300 cursor-pointer p-0">
                        <div class="hidden lg:flex items-center gap-0.5 ml-1">
                            <button type="button" onclick="aplicarColorFormaRapido('#2E6F40')" class="w-4 h-4 rounded-full bg-[#2E6F40] hover:scale-110 transition-transform" title="Verde SENA"></button>
                            <button type="button" onclick="aplicarColorFormaRapido('#1A2B4C')" class="w-4 h-4 rounded-full bg-[#1A2B4C] hover:scale-110 transition-transform" title="Azul SISIG"></button>
                            <button type="button" onclick="aplicarColorFormaRapido('#33FF57')" class="w-4 h-4 rounded-full bg-[#33FF57] hover:scale-110 transition-transform" title="Verde Neón"></button>
                            <button type="button" onclick="aplicarColorFormaRapido('#F59E0B')" class="w-4 h-4 rounded-full bg-amber-500 hover:scale-110 transition-transform" title="Ámbar Precaución"></button>
                            <button type="button" onclick="aplicarColorFormaRapido('#EF4444')" class="w-4 h-4 rounded-full bg-rose-500 hover:scale-110 transition-transform" title="Rojo Peligro"></button>
                            <button type="button" onclick="aplicarColorFormaRapido('#FFFFFF')" class="w-4 h-4 rounded-full bg-white border border-slate-300 hover:scale-110 transition-transform" title="Blanco"></button>
                        </div>
                    </div>

                    <!-- Borde (Color, Grosor, Estilo) -->
                    <div class="flex items-center gap-1.5 bg-slate-50 px-2 py-1 rounded-lg border border-slate-200">
                        <label class="text-[11px] font-bold text-slate-500">Borde:</label>
                        <input type="color" id="shape-stroke" value="#1A2B4C" class="w-6 h-6 rounded-md border border-slate-300 cursor-pointer p-0">
                        <input type="number" id="shape-stroke-width" min="0" max="30" value="0" class="w-10 h-6 text-center bg-white border border-slate-300 rounded text-xs font-bold" title="Grosor de borde">
                        
                        <!-- Estilo de Trazo: Sólido / Discontinuo / Punteado -->
                        <div class="flex items-center gap-0.5 border-l border-slate-300 pl-1">
                            <button type="button" onclick="aplicarEstiloBordeForma('solid')" class="px-1.5 py-0.5 rounded text-[10px] font-bold hover:bg-slate-200 text-slate-700" title="Borde sólido">—</button>
                            <button type="button" onclick="aplicarEstiloBordeForma('dashed')" class="px-1.5 py-0.5 rounded text-[10px] font-bold hover:bg-slate-200 text-slate-700" title="Borde con guiones">- -</button>
                            <button type="button" onclick="aplicarEstiloBordeForma('dotted')" class="px-1.5 py-0.5 rounded text-[10px] font-bold hover:bg-slate-200 text-slate-700" title="Borde punteado">···</button>
                        </div>
                    </div>

                    <!-- Redondeo de Esquinas -->
                    <div class="flex items-center gap-1 bg-slate-50 px-2 py-1 rounded-lg border border-slate-200" id="corner-radius-group">
                        <label class="text-[11px] font-bold text-slate-500">Redondeo:</label>
                        <input type="number" id="shape-corner-radius" min="0" max="100" value="0" class="w-10 h-6 text-center bg-white border border-slate-300 rounded text-xs font-bold" title="Radio de esquinas (px)">
                    </div>

                    <!-- Degradados Preconfigurados -->
                    <div class="flex items-center gap-1">
                        <button type="button" onclick="aplicarDegradadoForma('sena')" class="px-2 py-1 bg-gradient-to-r from-[#2E6F40] to-[#33FF57] text-white rounded-lg text-[10px] font-extrabold shadow-2xs hover:opacity-90">
                            Degr. SENA
                        </button>
                        <button type="button" onclick="aplicarDegradadoForma('sisig')" class="px-2 py-1 bg-gradient-to-r from-[#1A2B4C] to-[#0ea5e9] text-white rounded-lg text-[10px] font-extrabold shadow-2xs hover:opacity-90">
                            Degr. SIG
                        </button>
                    </div>

                    <div class="h-4 w-px bg-slate-200 mx-0.5"></div>

                    <!-- Voltear Forma (Flip Horizontal / Vertical) -->
                    <div class="flex items-center gap-0.5 bg-slate-50 p-0.5 rounded-lg border border-slate-200">
                        <button type="button" onclick="voltearObjeto('H')" title="Invertir horizontalmente (Flip H)" class="w-7 h-7 rounded hover:bg-slate-200 text-slate-700 flex items-center justify-center text-xs">
                            <i class="fas fa-arrows-alt-h"></i>
                        </button>
                        <button type="button" onclick="voltearObjeto('V')" title="Invertir verticalmente (Flip V)" class="w-7 h-7 rounded hover:bg-slate-200 text-slate-700 flex items-center justify-center text-xs">
                            <i class="fas fa-arrows-alt-v"></i>
                        </button>
                    </div>
                </div>

                <!-- 5. Opciones cuando una IMAGEN está seleccionada -->
                <div id="context-image" class="hidden flex items-center gap-2">
                    <span class="text-xs font-bold text-sky-700 flex items-center gap-1">
                        <i class="fas fa-image"></i> Imagen
                    </span>

                    <button type="button" onclick="abrirAjustesImagenModal()" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold flex items-center gap-1">
                        <i class="fas fa-sliders-h text-xs"></i> Filtros y Ajustes
                    </button>

                    <button type="button" onclick="activarRecorteImagen()" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold flex items-center gap-1">
                        <i class="fas fa-crop-alt text-xs"></i> Recortar
                    </button>
                </div>

                <!-- 5.5 Opciones cuando un MARCO está seleccionado (Canva Frame Toolbar) -->
                <div id="context-frame" class="hidden flex items-center gap-2 flex-wrap">
                    <div class="flex items-center gap-1.5 px-2 py-1 bg-emerald-50 text-emerald-800 rounded-lg text-xs font-bold border border-emerald-200">
                        <i class="fas fa-crop-alt text-emerald-600"></i>
                        <span id="context-frame-name">Marco</span>
                    </div>

                    <!-- Botón Cargar / Cambiar Foto -->
                    <button type="button" onclick="cargarFotoEnMarcoSeleccionado()" class="px-2.5 py-1 bg-sena-navy hover:bg-slate-800 text-white rounded-lg text-xs font-bold flex items-center gap-1.5 shadow-xs transition-all cursor-pointer" title="Subir o cambiar imagen para este marco">
                        <i class="fas fa-cloud-arrow-up text-sena-neon text-xs"></i>
                        <span id="btn-label-frame-image">Cargar Foto</span>
                    </button>

                    <!-- Botón Colocar / Cambiar Video en Marco -->
                    <button type="button" onclick="abrirModalNuevoVideoParaMarco()" class="px-2.5 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-bold flex items-center gap-1.5 shadow-xs transition-all cursor-pointer" title="Añadir o cambiar video en este marco">
                        <i class="fab fa-youtube text-white text-xs"></i>
                        <span id="btn-label-frame-video">Colocar Video</span>
                    </button>

                    <!-- Botón Reproducir Video en Marco (Visible cuando el marco contiene un video) -->
                    <button type="button" id="btn-frame-play-video" onclick="reproducirVideoMarcoSeleccionado()" class="hidden px-2.5 py-1 bg-[#33FF57] hover:bg-[#2ae049] text-[#1A2B4C] rounded-lg text-xs font-black flex items-center gap-1.5 shadow-xs transition-all cursor-pointer" title="Reproducir el video de este marco">
                        <i class="fas fa-play text-xs"></i>
                        <span>Reproducir</span>
                    </button>

                    <!-- Botón Desvincular Foto / Video -->
                    <button type="button" id="btn-frame-detach" onclick="desvincularImagenDeMarco()" class="hidden px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-bold flex items-center gap-1 border border-rose-200 transition-all cursor-pointer" title="Extraer imagen o video del marco">
                        <i class="fas fa-unlink text-xs"></i> <span id="btn-label-frame-detach">Desvincular</span>
                    </button>

                    <!-- Control de Zoom / Encuadre de Foto dentro del marco (Estilo Canva) -->
                    <div id="frame-zoom-control" class="hidden flex items-center gap-1.5 bg-slate-50 px-2 py-1 rounded-lg border border-slate-200">
                        <i class="fas fa-search-plus text-slate-400 text-xs" title="Zoom de la foto dentro del marco"></i>
                        <label class="text-[11px] font-bold text-slate-500">Zoom:</label>
                        <input type="range" id="frame-inner-zoom" min="100" max="300" value="100" class="w-16 accent-sena-green cursor-pointer" title="Ampliar imagen dentro del marco">
                        <span id="label-frame-inner-zoom" class="text-[10px] font-bold text-slate-600 min-w-7">100%</span>
                    </div>

                    <!-- Control de Borde del Marco -->
                    <div class="flex items-center gap-1.5 bg-slate-50 px-2 py-1 rounded-lg border border-slate-200">
                        <label class="text-[11px] font-bold text-slate-500">Borde:</label>
                        <input type="color" id="frame-border-color" value="#10b981" class="w-6 h-6 rounded-md border border-slate-300 cursor-pointer p-0" title="Color del borde">
                        <input type="number" id="frame-border-width" value="0" min="0" max="24" class="w-12 h-6 px-1 text-center font-bold text-xs bg-white border border-slate-200 rounded" title="Grosor de borde">
                        <span class="text-[10px] text-slate-400">px</span>
                    </div>

                    <!-- Estilo de Contorno -->
                    <select id="frame-border-style" class="h-7 px-2 rounded-lg border border-slate-200 bg-slate-50 text-slate-700 text-xs font-medium cursor-pointer" title="Estilo de contorno">
                        <option value="solid">Sólido</option>
                        <option value="dashed">Discontinuo</option>
                        <option value="dotted">Punteado</option>
                        <option value="none">Sin Borde</option>
                    </select>

                    <!-- Filtros de Imagen (cuando tiene foto) -->
                    <button type="button" id="btn-frame-filters" onclick="abrirAjustesImagenModal()" class="hidden px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold flex items-center gap-1 cursor-pointer">
                        <i class="fas fa-sliders-h text-xs"></i> Filtros
                    </button>
                </div>

                <!-- 5. OPCIONES PARA TARJETA DE VIDEO INTERACTIVO -->
                <div id="context-video" class="hidden flex items-center gap-2">
                    <button type="button" onclick="reproducirVideoSeleccionado()" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-bold flex items-center gap-1.5 shadow-xs transition-all cursor-pointer">
                        <i class="fas fa-play text-xs"></i>
                        <span>Reproducir</span>
                    </button>
                    <button type="button" onclick="iniciarSeleccionMarcoParaVideo()" class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold flex items-center gap-1.5 shadow-xs transition-all cursor-pointer" title="Colocar este video dentro de un marco de recorte">
                        <i class="fas fa-crop-alt text-xs"></i>
                        <span>Colocar en Marco</span>
                    </button>
                    <span class="text-[11px] text-slate-500 font-bold truncate max-w-xs" id="context-video-title"></span>
                </div>

                <!-- 6. ACCIONES COMUNES A CUALQUIER OBJETO ACTIVO (DERECHA) -->
                <div id="context-actions" class="hidden flex items-center gap-1.5 flex-wrap">
                    <!-- Opacidad -->
                    <div class="flex items-center gap-1 mr-1">
                        <i class="fas fa-adjust text-slate-400 text-xs" title="Opacidad"></i>
                        <input type="range" id="obj-opacity" min="0" max="100" value="100" class="w-14 accent-sena-green cursor-pointer">
                    </div>

                    <div class="h-4 w-px bg-slate-200"></div>

                    <!-- Alineación Relativa al Lienzo -->
                    <button type="button" onclick="centrarObjeto('H')" title="Centrar horizontalmente" class="w-7 h-7 rounded-lg hover:bg-slate-100 text-slate-600 flex items-center justify-center">
                        <i class="fas fa-arrows-alt-h text-xs"></i>
                    </button>
                    <button type="button" onclick="centrarObjeto('V')" title="Centrar verticalmente" class="w-7 h-7 rounded-lg hover:bg-slate-100 text-slate-600 flex items-center justify-center">
                        <i class="fas fa-arrows-alt-v text-xs"></i>
                    </button>

                    <!-- Capas (Z-Index) -->
                    <button type="button" id="btn-bring-front" title="Traer al frente" class="w-7 h-7 rounded-lg hover:bg-slate-100 text-slate-600 flex items-center justify-center">
                        <i class="fas fa-layer-group text-xs"></i>
                    </button>
                    <button type="button" id="btn-send-back" title="Enviar al fondo" class="w-7 h-7 rounded-lg hover:bg-slate-100 text-slate-600 flex items-center justify-center">
                        <i class="fas fa-level-down-alt text-xs"></i>
                    </button>

                    <!-- Agrupar / Desagrupar -->
                    <button type="button" id="btn-group-toggle" title="Agrupar / Desagrupar elementos" class="w-7 h-7 rounded-lg hover:bg-slate-100 text-slate-600 flex items-center justify-center">
                        <i class="fas fa-object-group text-xs"></i>
                    </button>

                    <!-- Candado / Bloqueo -->
                    <button type="button" id="btn-lock-toggle" title="Bloquear / Desbloquear elemento" class="w-7 h-7 rounded-lg hover:bg-slate-100 text-slate-600 flex items-center justify-center">
                        <i class="fas fa-unlock text-xs" id="lock-icon"></i>
                    </button>

                    <!-- Duplicar -->
                    <button type="button" id="btn-duplicate-obj" title="Duplicar elemento (Ctrl + D)" class="w-7 h-7 rounded-lg hover:bg-slate-100 text-slate-600 flex items-center justify-center">
                        <i class="fas fa-clone text-xs"></i>
                    </button>

                    <!-- Eliminar -->
                    <button type="button" id="btn-delete-obj" title="Eliminar elemento (Supr)" class="w-7 h-7 rounded-lg hover:bg-rose-50 text-rose-600 flex items-center justify-center">
                        <i class="fas fa-trash-alt text-xs"></i>
                    </button>
                </div>

            </div>

            <!-- POPUP FLOTANTE: ESPACIADO DE TEXTO (CANVA SPACING) -->
            <div id="popover-text-spacing" style="display: none;" class="absolute top-14 left-72 bg-white rounded-2xl p-4 shadow-2xl border border-slate-200 z-40 w-72 space-y-3">
                <div class="flex items-center justify-between pb-1.5 border-b border-slate-100">
                    <span class="text-xs font-black text-slate-800 flex items-center gap-1.5">
                        <i class="fas fa-arrows-alt-v text-emerald-600"></i> Espaciado de Texto
                    </span>
                    <button type="button" onclick="toggleTextSpacingMenu(false)" class="text-slate-400 hover:text-slate-600 text-xs"><i class="fas fa-times"></i></button>
                </div>
                <div>
                    <div class="flex justify-between text-[11px] font-bold text-slate-600 mb-1">
                        <span>Interlineado (Alto de línea):</span>
                        <span id="label-line-height" class="text-emerald-700 font-extrabold">1.2</span>
                    </div>
                    <input type="range" id="slider-line-height" min="0.5" max="3.0" step="0.05" value="1.2" class="w-full">
                </div>
                <div>
                    <div class="flex justify-between text-[11px] font-bold text-slate-600 mb-1">
                        <span>Espaciado entre letras (Tracking):</span>
                        <span id="label-char-spacing" class="text-emerald-700 font-extrabold">0</span>
                    </div>
                    <input type="range" id="slider-char-spacing" min="-50" max="500" step="10" value="0" class="w-full">
                </div>
            </div>

            <!-- POPUP FLOTANTE: EFECTOS DE TEXTO ESTILO CANVA -->
            <div id="popover-text-effects" style="display: none;" class="absolute top-14 left-80 bg-white rounded-3xl p-5 shadow-2xl border border-slate-200 z-40 w-84 max-h-[85vh] overflow-y-auto space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs">
                            <i class="fas fa-magic"></i>
                        </div>
                        <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider">Efectos de Texto Canva</h3>
                    </div>
                    <button type="button" onclick="toggleTextEffectsMenu(false)" class="text-slate-400 hover:text-slate-600 p-1"><i class="fas fa-times text-xs"></i></button>
                </div>

                <!-- Grilla de Tarjetas de Efectos -->
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2 block">Estilo del Efecto</label>
                    <div class="grid grid-cols-3 gap-2">
                        <!-- Ninguno -->
                        <button type="button" onclick="aplicarEfectoTexto('ninguno')" class="p-2.5 rounded-xl border-2 border-slate-200 hover:border-emerald-500 bg-slate-50 flex flex-col items-center justify-center gap-1 transition-all group">
                            <span class="text-xs font-extrabold text-slate-700">Aa</span>
                            <span class="text-[9px] font-bold text-slate-500">Ninguno</span>
                        </button>
                        <!-- Sombra -->
                        <button type="button" onclick="aplicarEfectoTexto('sombra')" class="p-2.5 rounded-xl border-2 border-slate-200 hover:border-emerald-500 bg-slate-50 flex flex-col items-center justify-center gap-1 transition-all group">
                            <span class="text-xs font-extrabold text-slate-800" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.4);">Aa</span>
                            <span class="text-[9px] font-bold text-slate-600">Sombra</span>
                        </button>
                        <!-- Flotante (Lift) -->
                        <button type="button" onclick="aplicarEfectoTexto('flotante')" class="p-2.5 rounded-xl border-2 border-slate-200 hover:border-emerald-500 bg-slate-50 flex flex-col items-center justify-center gap-1 transition-all group">
                            <span class="text-xs font-extrabold text-slate-800" style="text-shadow: 0 4px 8px rgba(0,0,0,0.35);">Aa</span>
                            <span class="text-[9px] font-bold text-slate-600">Flotante</span>
                        </button>
                        <!-- Hueco (Hollow) -->
                        <button type="button" onclick="aplicarEfectoTexto('hueco')" class="p-2.5 rounded-xl border-2 border-slate-200 hover:border-emerald-500 bg-slate-50 flex flex-col items-center justify-center gap-1 transition-all group">
                            <span class="text-xs font-black text-transparent" style="-webkit-text-stroke: 1.5px #1A2B4C;">Aa</span>
                            <span class="text-[9px] font-bold text-slate-600">Hueco</span>
                        </button>
                        <!-- Empalme (Splice) -->
                        <button type="button" onclick="aplicarEfectoTexto('empalme')" class="p-2.5 rounded-xl border-2 border-slate-200 hover:border-emerald-500 bg-slate-50 flex flex-col items-center justify-center gap-1 transition-all group">
                            <span class="text-xs font-black text-emerald-600" style="-webkit-text-stroke: 1px #1A2B4C; text-shadow: 2px 2px 0 #1A2B4C;">Aa</span>
                            <span class="text-[9px] font-bold text-slate-600">Empalme</span>
                        </button>
                        <!-- Eco (Echo) -->
                        <button type="button" onclick="aplicarEfectoTexto('eco')" class="p-2.5 rounded-xl border-2 border-slate-200 hover:border-emerald-500 bg-slate-50 flex flex-col items-center justify-center gap-1 transition-all group">
                            <span class="text-xs font-black text-slate-800" style="text-shadow: 3px 3px 0 rgba(46,111,64,0.4), 6px 6px 0 rgba(46,111,64,0.2);">Aa</span>
                            <span class="text-[9px] font-bold text-slate-600">Eco</span>
                        </button>
                        <!-- Glitch -->
                        <button type="button" onclick="aplicarEfectoTexto('glitch')" class="p-2.5 rounded-xl border-2 border-slate-200 hover:border-emerald-500 bg-slate-50 flex flex-col items-center justify-center gap-1 transition-all group">
                            <span class="text-xs font-black text-slate-900" style="text-shadow: -2px 0 #06b6d4, 2px 0 #f43f5e;">Aa</span>
                            <span class="text-[9px] font-bold text-slate-600">Glitch</span>
                        </button>
                        <!-- Neón -->
                        <button type="button" onclick="aplicarEfectoTexto('neon')" class="p-2.5 rounded-xl border-2 border-emerald-300 hover:border-emerald-500 bg-emerald-50/70 flex flex-col items-center justify-center gap-1 transition-all group">
                            <span class="text-xs font-black text-emerald-600" style="text-shadow: 0 0 10px #33FF57, 0 0 20px #33FF57;">Aa</span>
                            <span class="text-[9px] font-bold text-emerald-800">Neón</span>
                        </button>
                        <!-- Fondo (Background) -->
                        <button type="button" onclick="aplicarEfectoTexto('fondo')" class="p-2.5 rounded-xl border-2 border-slate-200 hover:border-emerald-500 bg-slate-50 flex flex-col items-center justify-center gap-1 transition-all group">
                            <span class="text-xs font-black px-1.5 py-0.5 rounded-sm bg-sena-green text-sena-neon">Aa</span>
                            <span class="text-[9px] font-bold text-slate-600">Fondo</span>
                        </button>
                    </div>
                </div>

                <!-- Controles de Ajuste Fino para el Efecto Seleccionado -->
                <div class="pt-3 border-t border-slate-100 space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Ajustes del Efecto</label>

                    <!-- Desplazamiento de Sombra / Eco -->
                    <div>
                        <div class="flex justify-between text-[11px] font-bold text-slate-600 mb-1">
                            <span>Desplazamiento / Distancia:</span>
                            <span id="label-effect-offset">4px</span>
                        </div>
                        <input type="range" id="slider-effect-offset" min="0" max="30" value="4" class="w-full">
                    </div>

                    <!-- Desenfoque (Blur) -->
                    <div>
                        <div class="flex justify-between text-[11px] font-bold text-slate-600 mb-1">
                            <span>Desenfoque (Blur):</span>
                            <span id="label-effect-blur">8px</span>
                        </div>
                        <input type="range" id="slider-effect-blur" min="0" max="40" value="8" class="w-full">
                    </div>

                    <!-- Grosor de Contorno (Stroke) -->
                    <div>
                        <div class="flex justify-between text-[11px] font-bold text-slate-600 mb-1">
                            <span>Grosor de Contorno:</span>
                            <span id="label-effect-stroke">2px</span>
                        </div>
                        <input type="range" id="slider-effect-stroke" min="0" max="15" value="2" class="w-full">
                    </div>

                    <!-- Color del Efecto o Fondo -->
                    <div class="flex items-center justify-between bg-slate-50 p-2 rounded-xl border border-slate-200">
                        <span class="text-[11px] font-bold text-slate-700">Color del Efecto / Fondo:</span>
                        <div class="flex items-center gap-2">
                            <input type="color" id="effect-custom-color" value="#33FF57" class="w-7 h-7 rounded-lg border border-slate-300 cursor-pointer p-0">
                            <button type="button" onclick="removerFondoCajaTexto()" class="text-[10px] text-rose-500 hover:underline font-bold">Limpiar</button>
                        </div>
                    </div>
                </div>

                <!-- Curvatura de Texto (Canva Curved Text) -->
                <div class="pt-3 border-t border-slate-100 space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Forma y Curvatura</label>
                    <div class="flex items-center justify-between text-[11px] font-bold text-slate-600">
                        <span>Curva del Texto:</span>
                        <span id="label-text-curve" class="text-emerald-700 font-extrabold">0%</span>
                    </div>
                    <input type="range" id="slider-text-curve" min="-100" max="100" step="5" value="0" class="w-full">
                    <div class="flex justify-between">
                        <button type="button" onclick="aplicarCurvaturaTexto(0)" class="text-[10px] font-bold text-slate-400 hover:text-slate-600">Plano (0%)</button>
                        <button type="button" onclick="aplicarCurvaturaTexto(50)" class="text-[10px] font-bold text-emerald-600 hover:text-emerald-700">Arco Superior (+50%)</button>
                        <button type="button" onclick="aplicarCurvaturaTexto(-50)" class="text-[10px] font-bold text-emerald-600 hover:text-emerald-700">Arco Inferior (-50%)</button>
                    </div>
                </div>
            </div>

            <!-- MODAL: AJUSTES DE MAPA DE BITS DE IMAGEN (FILTROS) -->
            <div id="modal-image-filters" style="display: none;" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50">
                <div class="bg-white rounded-3xl p-6 shadow-2xl border border-slate-200 w-full max-w-sm space-y-4">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-sliders-h text-sena-green"></i>
                            <h3 class="text-sm font-bold text-slate-800">Filtros y Ajustes de Imagen</h3>
                        </div>
                        <button type="button" onclick="cerrarAjustesImagenModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </div>

                    <!-- Brillo -->
                    <div>
                        <div class="flex justify-between text-xs font-bold text-slate-600 mb-1">
                            <span>Brillo:</span>
                            <span id="label-filter-brightness">0%</span>
                        </div>
                        <input type="range" id="filter-brightness" min="-1" max="1" step="0.05" value="0" class="w-full">
                    </div>

                    <!-- Contraste -->
                    <div>
                        <div class="flex justify-between text-xs font-bold text-slate-600 mb-1">
                            <span>Contraste:</span>
                            <span id="label-filter-contrast">0%</span>
                        </div>
                        <input type="range" id="filter-contrast" min="-1" max="1" step="0.05" value="0" class="w-full">
                    </div>

                    <!-- Saturación -->
                    <div>
                        <div class="flex justify-between text-xs font-bold text-slate-600 mb-1">
                            <span>Saturación:</span>
                            <span id="label-filter-saturation">0%</span>
                        </div>
                        <input type="range" id="filter-saturation" min="-1" max="1" step="0.05" value="0" class="w-full">
                    </div>

                    <!-- Filtros Rápidos -->
                    <div class="pt-2 border-t border-slate-100 grid grid-cols-3 gap-2">
                        <button type="button" onclick="aplicarFiltroRapido('normal')" class="py-1.5 px-2 text-center border border-slate-200 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50">Normal</button>
                        <button type="button" onclick="aplicarFiltroRapido('bn')" class="py-1.5 px-2 text-center border border-slate-200 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50">B & N</button>
                        <button type="button" onclick="aplicarFiltroRapido('sepia')" class="py-1.5 px-2 text-center border border-slate-200 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50">Sepia</button>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="button" onclick="cerrarAjustesImagenModal()" class="px-4 py-2 bg-sena-navy hover:bg-slate-800 text-white rounded-xl text-xs font-bold">Listo</button>
                    </div>
                </div>
            </div>

            <!-- LIENZO CENTRADO EN EL WORKSPACE -->
            <div class="flex-grow workspace-canvas-bg flex items-center justify-center p-6 overflow-auto" id="workspace-container">
                <div id="canvas-wrapper" class="relative bg-white rounded-2xl shadow-2xl shadow-black/60 overflow-hidden my-auto mx-auto" style="width: 960px; height: 960px; min-width: 960px; min-height: 960px;">
                    <canvas id="sisig-canvas" width="960" height="960"></canvas>
                </div>
            </div>

            <!-- BARRA DE ESTADO INFERIOR (Estilo Institucional SISIG) -->
            <footer style="background-color: #1A2B4C;" class="h-8 text-slate-300 px-4 flex items-center justify-between text-[11px] border-t border-[#243B66]/60 z-10 flex-shrink-0">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#33FF57] animate-pulse"></span>
                    <span class="font-bold text-white">Editor listo</span>
                    <span class="text-slate-500">|</span>
                    <span class="text-slate-300">Atajos: <kbd class="px-1.5 py-0.5 bg-[#243B66] rounded text-white text-[10px]">Supr</kbd> eliminar, <kbd class="px-1.5 py-0.5 bg-[#243B66] rounded text-white text-[10px]">Ctrl+D</kbd> duplicar, <kbd class="px-1.5 py-0.5 bg-[#243B66] rounded text-white text-[10px]">Ctrl+Z</kbd> deshacer</span>
                </div>
                <div class="flex items-center gap-3">
                    <span id="canvas-objects-count" class="font-bold text-slate-200">0 objetos</span>
                    <span id="last-saved-time" class="text-[#33FF57] font-bold">
                        <i class="fas fa-check-circle"></i> Sincronizado
                    </span>
                </div>
            </footer>

        </main>

            </div> <!-- Fin Área del Editor Visual (Dock + Drawer + Lienzo) -->
        </div> <!-- Fin Contenedor Derecho (Header Oficial + Área del Editor) -->

    <!-- ============================================================== -->
    <!-- MODAL REPRODUCTOR DE VIDEO IN-PAGE (Sin salir de la página)   -->
    <!-- ============================================================== -->
    <div id="modal-video-player" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/85 backdrop-blur-md animate-fade-in">
        <div class="relative w-full max-w-4xl bg-slate-900 border border-slate-700 rounded-3xl shadow-2xl overflow-hidden flex flex-col">
            <!-- Barra superior del reproductor -->
            <div class="flex items-center justify-between px-5 py-3.5 bg-slate-800/90 border-b border-slate-700/80">
                <div class="flex items-center gap-3 min-w-0">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#33FF57] animate-pulse flex-shrink-0"></span>
                    <i class="fab fa-youtube text-red-500 text-base flex-shrink-0" id="modal-video-icon"></i>
                    <h3 id="modal-video-title" class="text-sm font-bold text-white truncate max-w-md sm:max-w-xl">Reproduciendo Video Interactivo</h3>
                </div>
                <button type="button" onclick="cerrarModalVideo()" class="w-8 h-8 rounded-full bg-slate-700/80 hover:bg-rose-600 text-slate-300 hover:text-white flex items-center justify-center transition-all cursor-pointer" title="Cerrar reproductor">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
            
            <!-- Contenedor del video responsivo 16:9 -->
            <div class="relative w-full aspect-video bg-black flex items-center justify-center">
                <iframe id="video-iframe-player" class="w-full h-full" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen style="display: none;"></iframe>
                <video id="video-html5-player" class="w-full h-full" controls autoplay style="display: none;"></video>
            </div>

            <!-- Pie del reproductor -->
            <div class="px-5 py-2.5 bg-slate-950 flex items-center justify-between text-xs text-slate-400 border-t border-slate-800">
                <span class="flex items-center gap-1.5 text-[11px]">
                    <i class="fas fa-shield-alt text-[#33FF57]"></i> Reproducción integrada en plataforma SISIG SENA Empresa
                </span>
                <button type="button" onclick="cerrarModalVideo()" class="text-xs font-bold text-slate-300 hover:text-white px-3 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 transition-colors cursor-pointer">
                    Cerrar Ventana
                </button>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL DE VISUALIZACIÓN INTERACTIVA DEL MÓDULO                 -->
    <!-- ============================================================== -->
    <div id="modal-preview-module" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 bg-slate-950/90 backdrop-blur-md animate-fade-in">
        <div class="relative w-full max-w-6xl h-[92vh] bg-slate-900 border border-slate-700 rounded-3xl shadow-2xl overflow-hidden flex flex-col">
            <!-- Barra superior del visor -->
            <div class="flex items-center justify-between px-5 py-3.5 bg-[#1A2B4C] border-b border-[#243B66] text-white flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-black bg-[#243B66] text-[#33FF57] border border-[#33FF57]/40">
                        <i class="fas fa-eye text-xs"></i>
                        <span>Vista Previa Interactiva</span>
                    </div>
                    <span class="text-sm font-extrabold text-white truncate max-w-md">Módulo #{{ $module->id ?? 1 }}: {{ $module->title ?? $module->titulo ?? 'Ambiental' }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('sisig.aprendiz.induccion.show', $module->id ?? 1) }}" target="_blank" 
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-[#33FF57] hover:bg-[#2ae049] text-[#1A2B4C] transition-all shadow-xs cursor-pointer">
                        <i class="fas fa-external-link-alt text-[10px]"></i>
                        <span>Abrir Vista de Aprendiz</span>
                    </a>
                    <button type="button" onclick="cerrarModalVisualizacion()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-rose-600 text-slate-300 hover:text-white flex items-center justify-center transition-all cursor-pointer" title="Cerrar vista previa">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            </div>

            <!-- Área Central del Visor (Lienzo Centrado y Escalado) -->
            <div class="flex-1 bg-[#0b1322] p-4 sm:p-6 flex items-center justify-center overflow-auto relative">
                <div id="preview-stage-container" class="relative rounded-2xl shadow-2xl overflow-hidden bg-white max-w-full max-h-full flex items-center justify-center border border-slate-700" style="width: 960px; height: 960px;">
                    <!-- Render del SVG del canvas -->
                    <div id="preview-svg-wrapper" class="w-full h-full relative flex items-center justify-center"></div>
                </div>
            </div>

            <!-- Barra inferior del visor -->
            <div class="px-5 py-2.5 bg-slate-950 flex items-center justify-between text-xs text-slate-400 border-t border-slate-800 flex-shrink-0">
                <span class="text-[11px] text-slate-400">
                    <i class="fas fa-info-circle text-sky-400 mr-1"></i> Si el módulo incluye videos, haz clic sobre cualquiera de ellos para probar su reproducción directa sin salir de la página.
                </span>
                <button type="button" onclick="cerrarModalVisualizacion()" class="text-xs font-bold text-slate-300 hover:text-white px-3.5 py-1.5 rounded-full bg-slate-800 hover:bg-slate-700 transition-colors cursor-pointer">
                    Volver al Editor
                </button>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL CONFIRMACIÓN DE GUARDADO (TARJETAS OFICIALES SISTEMA SISIG)  -->
    <!-- ============================================================== -->
    <div id="modal-guardado-exito" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md animate-fade-in">
        <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-200 flex flex-col">
            <!-- Encabezado Dark Navy con línea Verde Neón SISIG -->
            <div class="bg-[#1A2B4C] p-5 text-white relative border-b-2 border-[#33FF57]">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#33FF57]/20 border border-[#33FF57]/40 flex items-center justify-center text-[#33FF57] text-lg flex-shrink-0">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-white tracking-tight">¡Módulo Guardado Exitosamente!</h3>
                            <p class="text-xs text-slate-300 mt-0.5">El lienzo y todos sus recursos multimedia se guardaron correctamente en el sistema.</p>
                        </div>
                    </div>
                    <button type="button" onclick="cerrarModalGuardado()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition-colors cursor-pointer" title="Cerrar modal">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            </div>

            <!-- Cuerpo con las 2 Tarjetas Idénticas al Catálogo Oficial SISIG -->
            <div class="p-5 sm:p-6 bg-slate-50/70">
                <div class="mb-4">
                    <span class="text-xs font-black text-slate-700 uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-mouse-pointer text-[#2E6F40]"></i> Selecciona tu siguiente paso:
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Tarjeta 1: Salir a Gestión de Módulos (Diseño Oficial SISIG) -->
                    <div class="modulo-card bg-white rounded-2xl border border-slate-200/90 shadow-sm hover:shadow-md transition-all duration-300 relative group flex flex-col h-full overflow-hidden">
                        <!-- Cabecera Dark Navy con badges oficiales -->
                        <div class="bg-gradient-to-r from-[#1A2B4C] to-[#243B66] p-4 text-white relative">
                            <div class="flex items-center justify-between mb-2.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-white/10 border border-white/20 flex items-center justify-center text-white text-xs flex-shrink-0">
                                        <i class="fas fa-th-large"></i>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full bg-white/15 border border-white/20 text-[10px] font-bold text-slate-100">
                                        Catálogo SIG
                                    </span>
                                </div>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-white text-[#2E6F40] text-[10px] font-black shadow-xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#33FF57]"></span>
                                    <span>Guardado</span>
                                </span>
                            </div>
                            <h4 class="font-extrabold text-white text-sm tracking-tight leading-snug">Gestión de Módulos</h4>
                            <p class="text-[11px] text-slate-200 font-medium mt-0.5">
                                <i class="fas fa-arrow-left text-[#33FF57] mr-1"></i> Salir al panel de contenidos
                            </p>
                        </div>

                        <!-- Cuerpo blanco con acción -->
                        <div class="p-4 flex flex-col flex-grow justify-between space-y-3 bg-white">
                            <p class="text-slate-600 text-xs leading-relaxed">
                                Regresa a la lista principal de módulos para administrar el proceso de inducción.
                            </p>
                            <div class="pt-2 border-t border-slate-100 mt-auto">
                                <a href="{{ route('sisig.editor.contenido.index') }}" 
                                   class="w-full py-2.5 px-4 rounded-full text-xs font-black text-[#1A2B4C] bg-[#33FF57] hover:bg-[#2ae049] transition-all shadow-md shadow-emerald-500/20 flex items-center justify-center gap-2 cursor-pointer group-hover:scale-[1.02]">
                                    <i class="fas fa-arrow-left text-xs text-[#1A2B4C]"></i>
                                    <span>Salir a Gestión de Módulos</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Tarjeta 2: Seguir Editando (Diseño Oficial SISIG) -->
                    <div class="modulo-card bg-white rounded-2xl border border-slate-200/90 shadow-sm hover:shadow-md transition-all duration-300 relative group flex flex-col h-full overflow-hidden">
                        <!-- Cabecera Dark Navy con badges oficiales -->
                        <div class="bg-gradient-to-r from-[#1A2B4C] to-[#243B66] p-4 text-white relative">
                            <div class="flex items-center justify-between mb-2.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-white/10 border border-white/20 flex items-center justify-center text-[#33FF57] text-xs flex-shrink-0">
                                        <i class="fas fa-pencil-alt"></i>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full bg-white/15 border border-white/20 text-[10px] font-bold text-slate-100">
                                        Lienzo Activo
                                    </span>
                                </div>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-white text-sky-700 text-[10px] font-black shadow-xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                                    <span>En Edición</span>
                                </span>
                            </div>
                            <h4 class="font-extrabold text-white text-sm tracking-tight leading-snug">Seguir Editando</h4>
                            <p class="text-[11px] text-slate-200 font-medium mt-0.5">
                                <i class="fas fa-palette text-[#33FF57] mr-1"></i> Continuar ajustando el diseño
                            </p>
                        </div>

                        <!-- Cuerpo blanco con acción -->
                        <div class="p-4 flex flex-col flex-grow justify-between space-y-3 bg-white">
                            <p class="text-slate-600 text-xs leading-relaxed">
                                Permanece en el lienzo para seguir añadiendo formas, textos, marcos o recursos multimedia.
                            </p>
                            <div class="pt-2 border-t border-slate-100 mt-auto">
                                <button type="button" onclick="cerrarModalGuardado()" 
                                        class="w-full py-2.5 px-4 rounded-full text-xs font-black text-[#1A2B4C] bg-[#33FF57] hover:bg-[#2ae049] transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer group-hover:scale-[1.02]">
                                    <i class="fas fa-edit text-xs"></i>
                                    <span>Seguir Editando</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pie con opción para visualizar -->
            <div class="px-6 py-3.5 bg-white border-t border-slate-200 flex items-center justify-between text-xs">
                <button type="button" onclick="cerrarModalGuardado(); abrirModalVisualizacion();" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#2E6F40] hover:text-[#1A2B4C] transition-colors cursor-pointer">
                    <i class="fas fa-eye text-xs"></i>
                    <span>Ver cómo quedó (Visualizar)</span>
                </button>
                <button type="button" onclick="cerrarModalGuardado()" class="text-xs font-bold text-slate-400 hover:text-slate-600 transition-colors cursor-pointer">
                    Cerrar ventana
                </button>
            </div>
        </div>
    </div>

    <!-- ========================================================= -->
    <!-- 3. JAVASCRIPT / MOTOR COMPLETO FABRIC.JS / TODAS LAS FUNCIONES -->
    <!-- ========================================================= -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Control de Expansión/Colapso del Sidebar al hacer clic en el Logo
            const sidebar = document.getElementById('sidebar-menu');
            const toggleLogoBtn = document.getElementById('btn-toggle-sidebar-logo');
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
                        if (backdrop) backdrop.classList.toggle('hidden', !isOpen);
                    }
                } else {
                    if (sidebar) {
                        const collapsed = sidebar.classList.toggle('sidebar-collapsed');
                        localStorage.setItem('sisig_sidebar_collapsed', collapsed ? 'true' : 'false');
                    }
                }
            }

            if (toggleLogoBtn) {
                toggleLogoBtn.addEventListener('click', toggleSidebar);
            }
            if (toggleBtn) {
                toggleBtn.addEventListener('click', toggleSidebar);
            }

            if (backdrop) {
                backdrop.addEventListener('click', function() {
                    if (sidebar) sidebar.classList.remove('mobile-open');
                    backdrop.classList.add('hidden');
                });
            }

            const moduleId = "{{ $module->id ?? 1 }}";
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // -------------------------------------------------------------
            // 1. INICIALIZACIÓN DEL LIENZO FABRIC.JS (960x960 / 1:1 Espacio Vertical Extra Amplio)
            // -------------------------------------------------------------
            const canvas = new fabric.Canvas('sisig-canvas', {
                width: 960,
                height: 960,
                backgroundColor: '#ffffff',
                preserveObjectStacking: true,
                selection: true,
                interactive: true
            });
            window.sisigCanvas = canvas;

            // Calibración matemática exacta de puntero para soporte de zoom sin desfase ni congelamiento
            canvas.getPointer = function(e, ignoreZoom) {
                const rect = canvas.upperCanvasEl.getBoundingClientRect();
                const scaleX = (canvas.width || 960) / (rect.width || 1);
                const scaleY = (canvas.height || 960) / (rect.height || 1);
                return {
                    x: (e.clientX - rect.left) * scaleX,
                    y: (e.clientY - rect.top) * scaleY
                };
            };

            // Máxima nitidez y calidad bicúbica para todas las imágenes y recortes
            canvas.on('after:render', function() {
                if (canvas.contextContainer) {
                    canvas.contextContainer.imageSmoothingEnabled = true;
                    canvas.contextContainer.imageSmoothingQuality = 'high';
                }
            });

            // Controles visuales estilizados estilo Canva
            fabric.Object.prototype.set({
                transparentCorners: false,
                cornerColor: '#2E6F40',
                cornerStrokeColor: '#ffffff',
                borderColor: '#10b981',
                cornerSize: 10,
                cornerStyle: 'circle',
                borderDashArray: [4, 4]
            });

            // -------------------------------------------------------------
            // CONTROL DE DIMENSIONES, ZOOM Y ENCUADRE DEL LIENZO
            // -------------------------------------------------------------
            let currentZoom = 1;
            const labelZoom = document.getElementById('label-zoom');
            const canvasWrapper = document.getElementById('canvas-wrapper');

            function applyZoom(z) {
                currentZoom = Math.min(Math.max(z, 0.35), 2.0);
                if (canvasWrapper) {
                    canvasWrapper.style.transform = `scale(${currentZoom})`;
                    canvasWrapper.style.transformOrigin = 'center center';
                }
                if (labelZoom) {
                    labelZoom.textContent = Math.round(currentZoom * 100) + '%';
                }
                if (canvas) {
                    canvas.calcOffset();
                }
            }

            function autoAjustarLienzo() {
                const container = document.querySelector('.workspace-canvas-bg');
                if (!container) return;
                const availableW = container.clientWidth - 50;
                const availableH = container.clientHeight - 50;
                if (availableW <= 0 || availableH <= 0) return;
                const currentW = canvas.width || 960;
                const currentH = canvas.height || 960;
                const scaleW = availableW / currentW;
                const scaleH = availableH / currentH;
                const bestScale = Math.min(scaleW, scaleH);
                applyZoom(Math.max(Math.min(bestScale, 1.0), 0.35));
            }

            window.currentLienzoAspect = '1:1';

            window.toggleMenuDimensiones = function(forceClose = false) {
                const menu = document.getElementById('menu-dimensiones-lienzo');
                const chevron = document.getElementById('icon-chevron-dimensiones');
                if (!menu) return;
                if (forceClose || !menu.classList.contains('hidden')) {
                    menu.classList.add('hidden');
                    if (chevron) chevron.style.transform = 'rotate(0deg)';
                } else {
                    menu.classList.remove('hidden');
                    if (chevron) chevron.style.transform = 'rotate(180deg)';
                }
            };

            // Cerrar menú al hacer clic fuera
            document.addEventListener('click', function(e) {
                const container = document.getElementById('dropdown-lienzo-container');
                if (container && !container.contains(e.target)) {
                    window.toggleMenuDimensiones(true);
                }
            });

            function cambiarTamanoLienzo(width, height, aspectLabel = '', record = true) {
                width = parseInt(width, 10);
                height = parseInt(height, 10);
                if (isNaN(width) || isNaN(height) || width < 200 || height < 200) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Medidas inválidas', 'El ancho y alto deben ser números superiores a 200px.', 'warning');
                    }
                    return;
                }

                // Ajustar dimensiones del Fabric Canvas
                canvas.setWidth(width);
                canvas.setHeight(height);

                // Si existe barra lateral institucional (franja verde SENA), ajustar su altura al nuevo alto del lienzo
                canvas.getObjects().forEach(o => {
                    if (o.type === 'rect' && o.left <= 15 && o.width <= 28 && (o.fill === '#33FF57' || o.selectable === false)) {
                        o.set({ top: 0, height: height });
                        o.setCoords();
                    }
                });

                // Si existe pie de página institucional, reubicarlo exactamente a la base del nuevo lienzo
                const footerBox = canvas.getObjects().find(o => o.type === 'rect' && o.width >= 750 && o.height <= 85 && o.left >= 30 && o.left <= 100);
                if (footerBox) {
                    const oldTop = footerBox.top;
                    const newTop = height - 90;
                    const deltaY = newTop - oldTop;
                    if (Math.abs(deltaY) > 1) {
                        canvas.getObjects().forEach(o => {
                            if (o.top >= oldTop - 15 && o.top <= oldTop + 85 && (o.left >= 40 || o.type === 'i-text')) {
                                o.set('top', o.top + deltaY);
                                o.setCoords();
                            }
                        });
                    }
                }

                // Ajustar contenedor visual del canvas en el editor
                const cWrapper = document.getElementById('canvas-wrapper');
                if (cWrapper) {
                    cWrapper.style.width = width + 'px';
                    cWrapper.style.height = height + 'px';
                    cWrapper.style.minWidth = width + 'px';
                    cWrapper.style.minHeight = height + 'px';
                }

                // Ajustar contenedor de visualización previa
                const previewStage = document.getElementById('preview-stage-container');
                if (previewStage) {
                    previewStage.style.width = width + 'px';
                    previewStage.style.height = height + 'px';
                }

                // Actualizar etiqueta del botón en la barra superior
                const label = document.getElementById('label-dimensiones-lienzo');
                const labelText = aspectLabel ? `${width} × ${height} px (${aspectLabel})` : `${width} × ${height} px`;
                if (label) {
                    label.textContent = labelText;
                }
                window.currentLienzoAspect = aspectLabel || `${width}:${height}`;

                // Sincronizar inputs de tamaño personalizado
                const inputW = document.getElementById('input-custom-width');
                const inputH = document.getElementById('input-custom-height');
                if (inputW) inputW.value = width;
                if (inputH) inputH.value = height;

                // Actualizar selección activa en los botones del menú
                document.querySelectorAll('.btn-opt-dimension').forEach(btn => {
                    const dims = btn.getAttribute('data-dims');
                    const check = btn.querySelector('.check-opt');
                    if (dims === `${width}x${height}`) {
                        btn.classList.add('bg-white/15', 'border-l-4', 'border-[#33FF57]');
                        if (check) check.classList.remove('hidden');
                    } else {
                        btn.classList.remove('bg-white/15', 'border-l-4', 'border-[#33FF57]');
                        if (check) check.classList.add('hidden');
                    }
                });

                // Auto-ajustar al viewport y re-renderizar
                autoAjustarLienzo();
                canvas.renderAll();

                // Cerrar menú desplegable
                window.toggleMenuDimensiones(true);

                if (record && typeof recordState === 'function') {
                    recordState();
                }
            }
            window.cambiarTamanoLienzo = cambiarTamanoLienzo;

            window.aplicarTamanoPersonalizado = function() {
                const inputW = document.getElementById('input-custom-width');
                const inputH = document.getElementById('input-custom-height');
                if (!inputW || !inputH) return;
                const w = parseInt(inputW.value, 10);
                const h = parseInt(inputH.value, 10);
                if (isNaN(w) || isNaN(h) || w < 300 || h < 300 || w > 3000 || h > 3000) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Medidas no válidas',
                            text: 'Ingresa valores entre 300px y 3000px.',
                            confirmButtonColor: '#1A2B4C'
                        });
                    }
                    return;
                }
                cambiarTamanoLienzo(w, h, 'Personalizado', true);
            };

            // -------------------------------------------------------------
            // 2. HISTORIAL COMPLETO (UNDO / REDO) Y CONTEO
            // -------------------------------------------------------------
            let canvasHistory = [];
            let historyIndex = -1;
            let isHistoryAction = false;

            function updateObjectsCount() {
                try {
                    const count = canvas ? canvas.getObjects().length : 0;
                    const el = document.getElementById('canvas-objects-count');
                    if (el) {
                        el.textContent = count + (count === 1 ? ' objeto' : ' objetos');
                    }
                } catch(e) {}
            }
            window.updateObjectsCount = updateObjectsCount;

            function updateUndoRedoButtons() {
                try {
                    const btnUndo = document.getElementById('btn-undo');
                    const btnRedo = document.getElementById('btn-redo');
                    if (btnUndo) btnUndo.disabled = historyIndex <= 0;
                    if (btnRedo) btnRedo.disabled = historyIndex >= canvasHistory.length - 1;
                } catch(e) {}
            }

            function recordState() {
                try {
                    if (isHistoryAction) return;
                    const canvasObj = canvas.toJSON([
                        'lockMovementX', 'lockMovementY', 'lockRotation', 'lockScalingX', 'lockScalingY',
                        'hasControls', 'selectable', 'clipPath',
                        'isFrame', 'frameType', 'hasImage', 'hasVideo', 'isVideoFrame', 'innerImageSrc',
                        'innerZoom', 'hasExplicitBorder',
                        'frameBorderColor', 'frameBorderWidth', 'frameBorderStyle',
                        'isFrameBorder', 'isFrameBase', 'isFrameDecoration', 'isFramePlaceholder',
                        'isVideoCard', 'videoUrl', 'embedUrl', 'videoTitle', 'videoType'
                    ]);
                    canvasObj.canvas_width = canvas.width;
                    canvasObj.canvas_height = canvas.height;
                    canvasObj.canvas_aspect_label = window.currentLienzoAspect || '';
                    const json = JSON.stringify(canvasObj);

                    if (historyIndex < canvasHistory.length - 1) {
                        canvasHistory = canvasHistory.slice(0, historyIndex + 1);
                    }
                    canvasHistory.push(json);
                    historyIndex++;
                    updateUndoRedoButtons();
                } catch (err) {
                    console.warn('Error en recordState:', err);
                } finally {
                    updateObjectsCount();
                }
            }

            window.undo = function() {
                if (historyIndex > 0) {
                    isHistoryAction = true;
                    historyIndex--;
                    try {
                        const prevObj = JSON.parse(canvasHistory[historyIndex]);
                        if (prevObj && prevObj.canvas_width && prevObj.canvas_height && (prevObj.canvas_width !== canvas.width || prevObj.canvas_height !== canvas.height)) {
                            cambiarTamanoLienzo(prevObj.canvas_width, prevObj.canvas_height, prevObj.canvas_aspect_label || '', false);
                        }
                    } catch (e) {}

                    canvas.loadFromJSON(canvasHistory[historyIndex], function () {
                        try {
                            if (typeof rehidratarMarcosCanvas === 'function') rehidratarMarcosCanvas();
                            canvas.forEachObject(o => o.setCoords());
                            canvas.renderAll();
                        } catch(e){}
                        finally {
                            isHistoryAction = false;
                            updateUndoRedoButtons();
                            updateObjectsCount();
                        }
                    });
                }
            };

            window.redo = function() {
                if (historyIndex < canvasHistory.length - 1) {
                    isHistoryAction = true;
                    historyIndex++;
                    try {
                        const nextObj = JSON.parse(canvasHistory[historyIndex]);
                        if (nextObj && nextObj.canvas_width && nextObj.canvas_height && (nextObj.canvas_width !== canvas.width || nextObj.canvas_height !== canvas.height)) {
                            cambiarTamanoLienzo(nextObj.canvas_width, nextObj.canvas_height, nextObj.canvas_aspect_label || '', false);
                        }
                    } catch (e) {}

                    canvas.loadFromJSON(canvasHistory[historyIndex], function () {
                        try {
                            if (typeof rehidratarMarcosCanvas === 'function') rehidratarMarcosCanvas();
                            canvas.forEachObject(o => o.setCoords());
                            canvas.renderAll();
                        } catch(e){}
                        finally {
                            isHistoryAction = false;
                            updateUndoRedoButtons();
                            updateObjectsCount();
                        }
                    });
                }
            };

            const btnUndoEl = document.getElementById('btn-undo');
            if (btnUndoEl) btnUndoEl.addEventListener('click', window.undo);
            const btnRedoEl = document.getElementById('btn-redo');
            if (btnRedoEl) btnRedoEl.addEventListener('click', window.redo);

            canvas.on('object:added', function(e) {
                try {
                    if (e && e.target) e.target.setCoords();
                    updateObjectsCount();
                    recordState();
                } catch(err) {
                    console.warn('Error en object:added:', err);
                }
            });
            canvas.on('object:modified', function(e) {
                try {
                    if (e && e.target) e.target.setCoords();
                    recordState();
                } catch(err) {
                    console.warn('Error en object:modified:', err);
                }
            });
            canvas.on('object:removed', function() {
                try {
                    updateObjectsCount();
                    recordState();
                } catch(err) {
                    console.warn('Error en object:removed:', err);
                }
            });

            // -------------------------------------------------------------
            // 3. PRECARGAR ESTADO DE BASE DE DATOS
            // -------------------------------------------------------------
            isHistoryAction = true; // Pausar historial durante la carga inicial
            let loadStateFinished = false;

            function finalizarCargaLienzo() {
                if (loadStateFinished) return;
                loadStateFinished = true;
                try {
                    // Garantizar que todos los objetos sean interactivos, tengan coordenadas y manejadores
                    canvas.forEachObject(function(o) {
                        if (o.type === 'rect' && o.left <= 15 && o.width <= 28) {
                            o.selectable = false;
                            o.evented = false;
                        } else {
                            if (typeof o.selectable === 'undefined') o.selectable = true;
                            if (typeof o.evented === 'undefined') o.evented = true;
                            o.hasControls = true;
                            o.hasBorders = true;
                            o.lockMovementX = false;
                            o.lockMovementY = false;
                        }
                        o.setCoords();
                    });

                    // Asegurar que las tarjetas de video tengan etiquetas para detección magnética
                    canvas.getObjects().forEach(o => {
                        if (o.isVideoCard && o._objects) {
                            o._objects.forEach(c => { c._parentVideoCard = o; });
                        }
                    });

                    if (typeof rehidratarMarcosCanvas === 'function') {
                        try { rehidratarMarcosCanvas(); } catch(e){}
                    }

                    canvas.renderAll();
                } catch (err) {
                    console.warn('Aviso durante finalización de carga:', err);
                } finally {
                    isHistoryAction = false;
                    canvasHistory = [];
                    historyIndex = -1;
                    recordState();
                    updateObjectsCount();
                }
            }

            // Fallback de seguridad: libera el lienzo de inmediato si recursos remotos tardan en responder
            setTimeout(function() {
                if (!loadStateFinished) {
                    finalizarCargaLienzo();
                }
            }, 600);

            @if(isset($module->canvas_state) && !empty($module->canvas_state))
                try {
                    let rawState = @json($module->canvas_state);
                    if (typeof rawState === 'string') {
                        try { rawState = JSON.parse(rawState); } catch(e){}
                    }

                    if (rawState && rawState.canvas_width && rawState.canvas_height) {
                        cambiarTamanoLienzo(
                            parseInt(rawState.canvas_width, 10),
                            parseInt(rawState.canvas_height, 10),
                            rawState.canvas_aspect_label || '',
                            false
                        );
                    } else {
                        cambiarTamanoLienzo(960, 960, '1:1', false);
                    }

                    // Cargar el estado exportado nativo de Fabric preservando estrictamente el diseño guardado
                    if (rawState && Array.isArray(rawState.objects) && rawState.objects.length > 0) {
                        canvas.loadFromJSON(rawState, function() {
                            try {
                                if (!canvas.backgroundColor) {
                                    canvas.setBackgroundColor('#ffffff', canvas.renderAll.bind(canvas));
                                }
                            } catch(e){}
                            finalizarCargaLienzo();
                        });
                    } else {
                        canvas.setBackgroundColor('#ffffff', canvas.renderAll.bind(canvas));
                        canvas.renderAll();
                        finalizarCargaLienzo();
                    }
                } catch (e) {
                    console.warn('Estado previo no parseable por Fabric:', e);
                    cambiarTamanoLienzo(960, 960, '1:1', false);
                    canvas.setBackgroundColor('#ffffff', canvas.renderAll.bind(canvas));
                    canvas.renderAll();
                    finalizarCargaLienzo();
                }
            @else
                cambiarTamanoLienzo(960, 960, '1:1', false);
                canvas.setBackgroundColor('#ffffff', canvas.renderAll.bind(canvas));
                canvas.renderAll();
                finalizarCargaLienzo();
            @endif

            // -------------------------------------------------------------
            // 4. CONTROL DE PESTAÑAS (CANVA DOCK)
            // -------------------------------------------------------------
            const tabButtons = document.querySelectorAll('.dock-tab-btn');
            const tabPanels = document.querySelectorAll('.tab-panel');
            const drawer = document.getElementById('canva-drawer');
            const drawerTitle = document.getElementById('drawer-title');

            const tabTitles = {
                plantillas: '<i class="fas fa-layer-group text-sena-green"></i> Plantillas SISIG',
                texto: '<i class="fas fa-font text-sena-green"></i> Textos y Tipografías',
                elementos: '<i class="fas fa-shapes text-sena-green"></i> Elementos y Formas',
                marcos: '<i class="fas fa-crop-alt text-sena-green"></i> Marcos de Recorte',
                subidas: '<i class="fas fa-cloud-upload-alt text-sena-green"></i> Galería Multimedia',
                fondo: '<i class="fas fa-fill-drip text-sena-green"></i> Fondo del Lienzo'
            };

            tabButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const targetTab = this.getAttribute('data-tab');

                    tabButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    tabPanels.forEach(p => p.classList.add('hidden'));
                    const targetPanel = document.getElementById('panel-' + targetTab);
                    if (targetPanel) targetPanel.classList.remove('hidden');

                    if (drawerTitle) drawerTitle.innerHTML = tabTitles[targetTab] || 'Herramientas';
                    drawer.classList.remove('-ml-80');
                });
            });

            document.getElementById('btn-toggle-drawer').addEventListener('click', function () {
                drawer.classList.toggle('-ml-80');
            });

            // -------------------------------------------------------------
            // 5. BARRA CONTEXTUAL INTELIGENTE (SELECCIÓN DINÁMICA)
            // -------------------------------------------------------------
            const contextEmpty = document.getElementById('context-empty');
            const contextText = document.getElementById('context-text');
            const contextShape = document.getElementById('context-shape');
            const contextImage = document.getElementById('context-image');
            const contextFrame = document.getElementById('context-frame');
            const contextVideo = document.getElementById('context-video');
            const contextActions = document.getElementById('context-actions');
            const fillGroup = document.getElementById('fill-control-group');
            const cornerGroup = document.getElementById('corner-radius-group');
            const lockIcon = document.getElementById('lock-icon');

            function updateContextBar() {
                try {
                    const active = canvas.getActiveObject();
                    if (active && active.isFrame) {
                        window._lastSelectedFrame = active;
                    }

                    if (!active) {
                        if (contextEmpty) contextEmpty.classList.remove('hidden');
                        if (contextText) contextText.classList.add('hidden');
                        if (contextShape) contextShape.classList.add('hidden');
                        if (contextImage) contextImage.classList.add('hidden');
                        if (contextFrame) contextFrame.classList.add('hidden');
                        if (contextVideo) contextVideo.classList.add('hidden');
                        if (contextActions) contextActions.classList.add('hidden');
                        return;
                    }

                    if (contextEmpty) contextEmpty.classList.add('hidden');
                    if (contextActions) contextActions.classList.remove('hidden');

                    // Estado del candado
                    const isLocked = active.lockMovementX === true;
                    if (lockIcon) {
                        lockIcon.className = isLocked ? 'fas fa-lock text-rose-500' : 'fas fa-unlock text-slate-600';
                    }

                    // 0. Si es Tarjeta de Video Interactivo
                    if (active.isVideoCard || active._parentVideoCard) {
                        const videoCard = active.isVideoCard ? active : active._parentVideoCard;
                        if (contextText) contextText.classList.add('hidden');
                        if (contextShape) contextShape.classList.add('hidden');
                        if (contextImage) contextImage.classList.add('hidden');
                        if (contextFrame) contextFrame.classList.add('hidden');
                        if (contextVideo) {
                            contextVideo.classList.remove('hidden');
                            const titleEl = document.getElementById('context-video-title');
                            if (titleEl) titleEl.textContent = videoCard.videoTitle || 'Video Interactivo';
                        }
                    }
                    // 1. Si es Marco / Frame
                    else if (active.isFrame) {
                        if (contextVideo) contextVideo.classList.add('hidden');
                        if (contextText) contextText.classList.add('hidden');
                        if (contextShape) contextShape.classList.add('hidden');
                        if (contextImage) contextImage.classList.add('hidden');
                        if (contextFrame) {
                            contextFrame.classList.remove('hidden');
                            const nameEl = document.getElementById('context-frame-name');
                            if (nameEl) nameEl.textContent = obtenerNombreMarco(active.frameType);

                            const btnLabel = document.getElementById('btn-label-frame-image');
                            const btnLabelVideo = document.getElementById('btn-label-frame-video');
                            const btnPlayVideo = document.getElementById('btn-frame-play-video');
                            const btnDetach = document.getElementById('btn-frame-detach');
                            const btnDetachLabel = document.getElementById('btn-label-frame-detach');
                            const btnFilters = document.getElementById('btn-frame-filters');

                            const zoomControl = document.getElementById('frame-zoom-control');
                            const innerZoomInput = document.getElementById('frame-inner-zoom');
                            const labelInnerZoom = document.getElementById('label-frame-inner-zoom');

                            if (active.hasVideo) {
                                if (nameEl) nameEl.textContent = `${obtenerNombreMarco(active.frameType)} (Video)`;
                                if (btnLabel) btnLabel.textContent = 'Cambiar Foto';
                                if (btnLabelVideo) btnLabelVideo.textContent = 'Cambiar Video';
                                if (btnPlayVideo) btnPlayVideo.classList.remove('hidden');
                                if (btnDetach) btnDetach.classList.remove('hidden');
                                if (btnDetachLabel) btnDetachLabel.textContent = 'Quitar Video';
                                if (btnFilters) btnFilters.classList.add('hidden');
                                if (zoomControl) zoomControl.classList.remove('hidden');
                                const currentZoomVal = Math.round((active.innerZoom || 1.0) * 100);
                                if (innerZoomInput) innerZoomInput.value = currentZoomVal;
                                if (labelInnerZoom) labelInnerZoom.textContent = currentZoomVal + '%';
                            } else if (active.hasImage) {
                                if (nameEl) nameEl.textContent = obtenerNombreMarco(active.frameType);
                                if (btnLabel) btnLabel.textContent = 'Cambiar Foto';
                                if (btnLabelVideo) btnLabelVideo.textContent = 'Colocar Video';
                                if (btnPlayVideo) btnPlayVideo.classList.add('hidden');
                                if (btnDetach) btnDetach.classList.remove('hidden');
                                if (btnDetachLabel) btnDetachLabel.textContent = 'Desvincular';
                                if (btnFilters) btnFilters.classList.remove('hidden');
                                if (zoomControl) zoomControl.classList.remove('hidden');
                                const currentZoomVal = Math.round((active.innerZoom || 1.0) * 100);
                                if (innerZoomInput) innerZoomInput.value = currentZoomVal;
                                if (labelInnerZoom) labelInnerZoom.textContent = currentZoomVal + '%';
                            } else {
                                if (nameEl) nameEl.textContent = obtenerNombreMarco(active.frameType);
                                if (btnLabel) btnLabel.textContent = 'Cargar Foto';
                                if (btnLabelVideo) btnLabelVideo.textContent = 'Colocar Video';
                                if (btnPlayVideo) btnPlayVideo.classList.add('hidden');
                                if (btnDetach) btnDetach.classList.add('hidden');
                                if (btnFilters) btnFilters.classList.add('hidden');
                                if (zoomControl) zoomControl.classList.add('hidden');
                            }

                            const frameColorEl = document.getElementById('frame-border-color');
                            if (frameColorEl) frameColorEl.value = active.frameBorderColor || '#10b981';
                            const borderWidthEl = document.getElementById('frame-border-width');
                            if (borderWidthEl) borderWidthEl.value = typeof active.frameBorderWidth !== 'undefined' ? active.frameBorderWidth : (active.hasImage ? 0 : 2);
                            const borderStyleEl = document.getElementById('frame-border-style');
                            if (borderStyleEl) borderStyleEl.value = active.frameBorderStyle || (active.hasImage ? 'none' : 'dashed');
                        }
                    }
                    // 2. Si es texto
                    else if (active.type === 'i-text' || active.type === 'text') {
                        if (contextFrame) contextFrame.classList.add('hidden');
                        if (contextVideo) contextVideo.classList.add('hidden');
                        if (contextText) contextText.classList.remove('hidden');
                        if (contextShape) contextShape.classList.add('hidden');
                        if (contextImage) contextImage.classList.add('hidden');

                        const fontFamEl = document.getElementById('text-font-family');
                        if (fontFamEl) fontFamEl.value = active.fontFamily || 'Inter';
                        const fontSzEl = document.getElementById('text-font-size');
                        if (fontSzEl) fontSzEl.value = active.fontSize || 24;
                        const txtColEl = document.getElementById('text-color');
                        if (txtColEl) txtColEl.value = (typeof active.fill === 'string' && active.fill.startsWith('#')) ? active.fill : '#1e293b';
                        const btnBold = document.getElementById('btn-text-bold');
                        if (btnBold) btnBold.classList.toggle('bg-slate-200', active.fontWeight === 'bold' || active.fontWeight === 700);
                        const btnItalic = document.getElementById('btn-text-italic');
                        if (btnItalic) btnItalic.classList.toggle('bg-slate-200', active.fontStyle === 'italic');
                        const btnUnderline = document.getElementById('btn-text-underline');
                        if (btnUnderline) btnUnderline.classList.toggle('bg-slate-200', active.underline === true);
                        const btnStrike = document.getElementById('btn-text-strike');
                        if (btnStrike) btnStrike.classList.toggle('bg-slate-200', active.linethrough === true);
                        const lineHEl = document.getElementById('slider-line-height');
                        if (lineHEl) lineHEl.value = active.lineHeight || 1.2;
                        const lblLineHEl = document.getElementById('label-line-height');
                        if (lblLineHEl) lblLineHEl.textContent = active.lineHeight || 1.2;
                        const charSpEl = document.getElementById('slider-char-spacing');
                        if (charSpEl) charSpEl.value = active.charSpacing || 0;
                        const lblCharSpEl = document.getElementById('label-char-spacing');
                        if (lblCharSpEl) lblCharSpEl.textContent = active.charSpacing || 0;
                    }
                    // 3. Si es imagen
                    else if (active.type === 'image') {
                        if (contextFrame) contextFrame.classList.add('hidden');
                        if (contextVideo) contextVideo.classList.add('hidden');
                        if (contextText) contextText.classList.add('hidden');
                        if (contextShape) contextShape.classList.add('hidden');
                        if (contextImage) contextImage.classList.remove('hidden');
                    }
                    // 4. Si es forma u otro
                    else {
                        if (contextFrame) contextFrame.classList.add('hidden');
                        if (contextVideo) contextVideo.classList.add('hidden');
                        if (contextText) contextText.classList.add('hidden');
                        if (contextShape) contextShape.classList.remove('hidden');
                        if (contextImage) contextImage.classList.add('hidden');

                        const sFill = document.getElementById('shape-fill');
                        if (sFill) sFill.value = (typeof active.fill === 'string' && active.fill.startsWith('#')) ? active.fill : '#2E6F40';
                        const sStroke = document.getElementById('shape-stroke');
                        if (sStroke) sStroke.value = (typeof active.stroke === 'string' && active.stroke.startsWith('#')) ? active.stroke : '#1A2B4C';
                        const sStrokeW = document.getElementById('shape-stroke-width');
                        if (sStrokeW) sStrokeW.value = active.strokeWidth || 0;
                        if (cornerGroup) cornerGroup.classList.toggle('hidden', active.type !== 'rect');
                        if (active.type === 'rect') {
                            const sCorner = document.getElementById('shape-corner-radius');
                            if (sCorner) sCorner.value = active.rx || 0;
                        }
                    }

                    const objOpacityEl = document.getElementById('obj-opacity');
                    if (objOpacityEl) objOpacityEl.value = Math.round((active.opacity ?? 1) * 100);
                } catch (err) {
                    console.warn('Error en updateContextBar:', err);
                }
            }

            canvas.on('selection:created', updateContextBar);
            canvas.on('selection:updated', updateContextBar);
            canvas.on('selection:cleared', updateContextBar);
            canvas.on('mouse:down', function(e) {
                if (e.target && e.target.isFrame) {
                    window._lastSelectedFrame = e.target;
                }
            });

            // -------------------------------------------------------------
            // 6. TEXTO Y TIPOGRAFÍA: CONTROLES DIRECTOS ESTILO CANVA
            // -------------------------------------------------------------
            window.agregarTexto = function(tipo) {
                let options = {
                    left: canvas.width / 2,
                    top: canvas.height / 2,
                    originX: 'center',
                    originY: 'center',
                    fontFamily: 'Inter',
                    fill: '#1e293b'
                };

                if (tipo === 'titulo') {
                    options = { ...options, text: 'Título del Módulo', fontSize: 42, fontWeight: '800', fontFamily: 'Montserrat', fill: '#1A2B4C' };
                } else if (tipo === 'subtitulo') {
                    options = { ...options, text: 'Subtítulo del Concepto', fontSize: 24, fontWeight: '700', fontFamily: 'Poppins', fill: '#2E6F40' };
                } else if (tipo === 'parrafo') {
                    options = { ...options, text: 'Redacta aquí la descripción o contenido formativo para los aprendices.', fontSize: 15, fontWeight: 'normal', fontFamily: 'Inter', fill: '#334155', lineHeight: 1.35 };
                } else if (tipo === 'nota') {
                    options = { ...options, text: 'Nota técnica: Cumplimiento conforme a la resolución vigente.', fontSize: 11, fontStyle: 'italic', fontFamily: 'Inter', fill: '#64748b' };
                } else if (tipo === 'impacto') {
                    options = { ...options, text: 'SEGURIDAD Y SALUD 2026', fontSize: 44, fontWeight: '900', fontFamily: 'Bebas Neue', fill: '#1A2B4C', charSpacing: 120 };
                } else if (tipo === 'firma') {
                    options = { ...options, text: 'Compromiso con la Calidad', fontSize: 32, fontFamily: 'Pacifico', fill: '#78350f' };
                } else if (tipo === 'cita') {
                    options = { ...options, text: '“La seguridad no es un requisito, es un principio de vida.”\n— Política SIG SENA', fontSize: 17, fontStyle: 'italic', fontFamily: 'Playfair Display', fill: '#0f172a', textAlign: 'center', lineHeight: 1.4 };
                } else if (tipo === 'kpi') {
                    options = { ...options, text: '100% CERO ACCIDENTES', fontSize: 42, fontWeight: '900', fontFamily: 'Montserrat', fill: '#065f46', charSpacing: 40 };
                } else if (tipo === 'checklist') {
                    options = { ...options, text: '✔ Uso obligatorio de EPP\n✔ Inspección de área operativa\n✔ Reporte de actos inseguros', fontSize: 16, fontWeight: '600', fontFamily: 'Inter', fill: '#1e293b', lineHeight: 1.5 };
                } else if (tipo === 'insignia') {
                    options = { ...options, text: 'ETIQUETA NORMA ISO 9001 / 45001', fontSize: 13, fontWeight: '900', fill: '#33FF57', backgroundColor: '#1A2B4C', padding: 10 };
                } else if (tipo === 'alerta') {
                    options = { ...options, text: '⚠️ PRECAUCIÓN: USO DE EPP OBLIGATORIO', fontSize: 14, fontWeight: '800', fill: '#ffffff', backgroundColor: '#d97706', padding: 10 };
                } else if (tipo === 'neon_title') {
                    options = {
                        ...options,
                        text: 'INDUCCIÓN AMBIENTAL 2026',
                        fontSize: 34,
                        fontWeight: '900',
                        fontFamily: 'Montserrat',
                        fill: '#ffffff',
                        shadow: new fabric.Shadow({ color: '#33FF57', blur: 25, offsetX: 0, offsetY: 0 })
                    };
                }

                const textObj = new fabric.IText(options.text, options);
                canvas.add(textObj);
                canvas.setActiveObject(textObj);
                canvas.renderAll();
                recordState();
            };

            // Paleta rápida de color de texto
            window.aplicarColorTextoRapido = function(color) {
                const obj = canvas.getActiveObject();
                if (obj && (obj.type === 'i-text' || obj.type === 'text')) {
                    obj.set('fill', color);
                    document.getElementById('text-color').value = color.startsWith('#') ? color : '#1e293b';
                    canvas.renderAll();
                    recordState();
                }
            };

            document.getElementById('text-font-family').addEventListener('change', function () {
                const obj = canvas.getActiveObject();
                if (obj && (obj.type === 'i-text' || obj.type === 'text')) {
                    obj.set('fontFamily', this.value);
                    canvas.renderAll();
                    recordState();
                }
            });

            document.getElementById('text-font-size').addEventListener('input', function () {
                const obj = canvas.getActiveObject();
                if (obj && (obj.type === 'i-text' || obj.type === 'text')) {
                    obj.set('fontSize', parseInt(this.value) || 12);
                    canvas.renderAll();
                    recordState();
                }
            });

            document.getElementById('btn-font-inc').addEventListener('click', function () {
                const obj = canvas.getActiveObject();
                if (obj && (obj.type === 'i-text' || obj.type === 'text')) {
                    obj.set('fontSize', (obj.fontSize || 24) + 2);
                    document.getElementById('text-font-size').value = obj.fontSize;
                    canvas.renderAll();
                    recordState();
                }
            });

            document.getElementById('btn-font-dec').addEventListener('click', function () {
                const obj = canvas.getActiveObject();
                if (obj && (obj.type === 'i-text' || obj.type === 'text') && obj.fontSize > 8) {
                    obj.set('fontSize', obj.fontSize - 2);
                    document.getElementById('text-font-size').value = obj.fontSize;
                    canvas.renderAll();
                    recordState();
                }
            });

            document.getElementById('text-color').addEventListener('input', function () {
                const obj = canvas.getActiveObject();
                if (obj && (obj.type === 'i-text' || obj.type === 'text')) {
                    obj.set('fill', this.value);
                    canvas.renderAll();
                    recordState();
                }
            });

            document.getElementById('btn-text-bold').addEventListener('click', function () {
                const obj = canvas.getActiveObject();
                if (obj && (obj.type === 'i-text' || obj.type === 'text')) {
                    const isBold = obj.fontWeight === 'bold' || obj.fontWeight === 700;
                    obj.set('fontWeight', isBold ? 'normal' : 'bold');
                    this.classList.toggle('bg-slate-200', !isBold);
                    canvas.renderAll();
                    recordState();
                }
            });

            document.getElementById('btn-text-italic').addEventListener('click', function () {
                const obj = canvas.getActiveObject();
                if (obj && (obj.type === 'i-text' || obj.type === 'text')) {
                    const isItalic = obj.fontStyle === 'italic';
                    obj.set('fontStyle', isItalic ? 'normal' : 'italic');
                    this.classList.toggle('bg-slate-200', !isItalic);
                    canvas.renderAll();
                    recordState();
                }
            });

            document.getElementById('btn-text-underline').addEventListener('click', function () {
                const obj = canvas.getActiveObject();
                if (obj && (obj.type === 'i-text' || obj.type === 'text')) {
                    obj.set('underline', !obj.underline);
                    this.classList.toggle('bg-slate-200', obj.underline);
                    canvas.renderAll();
                    recordState();
                }
            });

            const btnStrike = document.getElementById('btn-text-strike');
            if (btnStrike) {
                btnStrike.addEventListener('click', function () {
                    const obj = canvas.getActiveObject();
                    if (obj && (obj.type === 'i-text' || obj.type === 'text')) {
                        obj.set('linethrough', !obj.linethrough);
                        this.classList.toggle('bg-slate-200', obj.linethrough);
                        canvas.renderAll();
                        recordState();
                    }
                });
            }

            const btnCase = document.getElementById('btn-text-case');
            if (btnCase) {
                btnCase.addEventListener('click', function () {
                    const obj = canvas.getActiveObject();
                    if (obj && (obj.type === 'i-text' || obj.type === 'text')) {
                        const text = obj.text || '';
                        const isAllUpper = text === text.toUpperCase() && text !== text.toLowerCase();
                        obj.set('text', isAllUpper ? text.toLowerCase() : text.toUpperCase());
                        canvas.renderAll();
                        recordState();
                    }
                });
            }

            window.setTextAlign = function(align) {
                const obj = canvas.getActiveObject();
                if (obj && (obj.type === 'i-text' || obj.type === 'text')) {
                    obj.set('textAlign', align);
                    canvas.renderAll();
                    recordState();
                }
            };

            // Viñetas y Listas
            window.toggleTextBullets = function () {
                const obj = canvas.getActiveObject();
                if (!obj || (obj.type !== 'i-text' && obj.type !== 'text')) return;
                const lines = (obj.text || '').split('\n');
                const hasBullets = lines.every(l => l.trim().startsWith('•') || l.trim() === '');
                const newLines = lines.map(line => {
                    if (hasBullets) return line.replace(/^\s*•\s*/, '');
                    return line.trim() === '' ? '' : '• ' + line;
                });
                obj.set('text', newLines.join('\n'));
                canvas.renderAll();
                recordState();
            };

            window.toggleTextNumbering = function () {
                const obj = canvas.getActiveObject();
                if (!obj || (obj.type !== 'i-text' && obj.type !== 'text')) return;
                const lines = (obj.text || '').split('\n');
                const hasNumbers = lines.every(l => /^\s*\d+\.\s*/.test(l) || l.trim() === '');
                let counter = 1;
                const newLines = lines.map(line => {
                    if (hasNumbers) return line.replace(/^\s*\d+\.\s*/, '');
                    return line.trim() === '' ? '' : (counter++) + '. ' + line;
                });
                obj.set('text', newLines.join('\n'));
                canvas.renderAll();
                recordState();
            };

            // Interlineado y Tracking
            window.toggleTextSpacingMenu = function(show = null) {
                const pop = document.getElementById('popover-text-spacing');
                pop.style.display = (show !== null) ? (show ? 'block' : 'none') : (pop.style.display === 'none' ? 'block' : 'none');
            };

            document.getElementById('slider-line-height').addEventListener('input', function () {
                const obj = canvas.getActiveObject();
                if (obj && (obj.type === 'i-text' || obj.type === 'text')) {
                    obj.set('lineHeight', parseFloat(this.value));
                    document.getElementById('label-line-height').textContent = this.value;
                    canvas.renderAll();
                    recordState();
                }
            });

            document.getElementById('slider-char-spacing').addEventListener('input', function () {
                const obj = canvas.getActiveObject();
                if (obj && (obj.type === 'i-text' || obj.type === 'text')) {
                    obj.set('charSpacing', parseInt(this.value));
                    document.getElementById('label-char-spacing').textContent = this.value;
                    canvas.renderAll();
                    recordState();
                }
            });

            // Efectos de Texto estilo Canva
            window.toggleTextEffectsMenu = function(show = null) {
                const pop = document.getElementById('popover-text-effects');
                pop.style.display = (show !== null) ? (show ? 'block' : 'none') : (pop.style.display === 'none' ? 'block' : 'none');
            };

            window.aplicarEfectoTexto = function(tipo) {
                const obj = canvas.getActiveObject();
                if (!obj || (obj.type !== 'i-text' && obj.type !== 'text')) return;

                const customColor = document.getElementById('effect-custom-color').value || '#33FF57';
                const offsetVal = parseInt(document.getElementById('slider-effect-offset').value) || 4;
                const blurVal = parseInt(document.getElementById('slider-effect-blur').value) || 8;
                const strokeVal = parseInt(document.getElementById('slider-effect-stroke').value) || 2;

                if (tipo === 'ninguno') {
                    obj.set('shadow', null);
                    obj.set('stroke', null);
                    obj.set('strokeWidth', 0);
                    obj.set('backgroundColor', null);
                    if (obj.fill === 'transparent') obj.set('fill', '#1e293b');
                } else if (tipo === 'sombra') {
                    obj.set('shadow', new fabric.Shadow({
                        color: 'rgba(0,0,0,0.35)',
                        blur: blurVal,
                        offsetX: offsetVal,
                        offsetY: offsetVal
                    }));
                    if (obj.fill === 'transparent') obj.set('fill', '#1e293b');
                    obj.set('strokeWidth', 0);
                } else if (tipo === 'flotante') {
                    obj.set('shadow', new fabric.Shadow({
                        color: 'rgba(0,0,0,0.38)',
                        blur: 24,
                        offsetX: 0,
                        offsetY: 10
                    }));
                    if (obj.fill === 'transparent') obj.set('fill', '#1e293b');
                } else if (tipo === 'hueco') {
                    obj.set('fill', 'transparent');
                    obj.set('stroke', customColor.startsWith('#') ? customColor : '#1A2B4C');
                    obj.set('strokeWidth', strokeVal);
                    obj.set('shadow', null);
                } else if (tipo === 'empalme') {
                    obj.set('stroke', '#1A2B4C');
                    obj.set('strokeWidth', 2);
                    obj.set('fill', '#ffffff');
                    obj.set('shadow', new fabric.Shadow({
                        color: customColor,
                        blur: 0,
                        offsetX: offsetVal,
                        offsetY: offsetVal
                    }));
                } else if (tipo === 'eco') {
                    obj.set('shadow', new fabric.Shadow({
                        color: 'rgba(46,111,64,0.45)',
                        blur: 0,
                        offsetX: 8,
                        offsetY: 8
                    }));
                    if (obj.fill === 'transparent') obj.set('fill', '#1e293b');
                } else if (tipo === 'glitch') {
                    obj.set('shadow', new fabric.Shadow({
                        color: '#06b6d4',
                        blur: 0,
                        offsetX: -3,
                        offsetY: 2
                    }));
                    if (obj.fill === 'transparent') obj.set('fill', '#0f172a');
                } else if (tipo === 'neon') {
                    obj.set('fill', '#ffffff');
                    obj.set('stroke', null);
                    obj.set('strokeWidth', 0);
                    obj.set('shadow', new fabric.Shadow({
                        color: customColor,
                        blur: 25,
                        offsetX: 0,
                        offsetY: 0
                    }));
                } else if (tipo === 'fondo') {
                    obj.set('backgroundColor', customColor);
                    obj.set('padding', 10);
                    if (obj.fill === '#1e293b' || obj.fill === '#000000') obj.set('fill', '#ffffff');
                }

                canvas.renderAll();
                recordState();
            };

            // Ajustes interactivos de efectos
            document.getElementById('slider-effect-offset').addEventListener('input', function () {
                document.getElementById('label-effect-offset').textContent = this.value + 'px';
                const obj = canvas.getActiveObject();
                if (obj && obj.shadow) {
                    obj.shadow.offsetX = parseInt(this.value);
                    obj.shadow.offsetY = parseInt(this.value);
                    canvas.renderAll();
                    recordState();
                }
            });

            document.getElementById('slider-effect-blur').addEventListener('input', function () {
                document.getElementById('label-effect-blur').textContent = this.value + 'px';
                const obj = canvas.getActiveObject();
                if (obj && obj.shadow) {
                    obj.shadow.blur = parseInt(this.value);
                    canvas.renderAll();
                    recordState();
                }
            });

            document.getElementById('slider-effect-stroke').addEventListener('input', function () {
                document.getElementById('label-effect-stroke').textContent = this.value + 'px';
                const obj = canvas.getActiveObject();
                if (obj && (obj.type === 'i-text' || obj.type === 'text')) {
                    obj.set('strokeWidth', parseInt(this.value));
                    canvas.renderAll();
                    recordState();
                }
            });

            document.getElementById('effect-custom-color').addEventListener('input', function () {
                const obj = canvas.getActiveObject();
                if (!obj || (obj.type !== 'i-text' && obj.type !== 'text')) return;
                if (obj.backgroundColor) {
                    obj.set('backgroundColor', this.value);
                } else if (obj.stroke && obj.strokeWidth > 0) {
                    obj.set('stroke', this.value);
                } else if (obj.shadow) {
                    obj.shadow.color = this.value;
                }
                canvas.renderAll();
                recordState();
            });

            // Curvatura de Texto (Arco circular Canva)
            window.aplicarCurvaturaTexto = function (curva) {
                const obj = canvas.getActiveObject();
                if (!obj || (obj.type !== 'i-text' && obj.type !== 'text')) return;
                document.getElementById('slider-text-curve').value = curva;
                document.getElementById('label-text-curve').textContent = curva + '%';

                if (curva === 0) {
                    obj.set('path', null);
                } else {
                    const w = Math.max(obj.width || 200, 260);
                    const controlY = (curva / 100) * 80;
                    const pathStr = `M 0 50 Q ${w / 2} ${50 - controlY} ${w} 50`;
                    try {
                        obj.set('path', new fabric.Path(pathStr, { visible: false }));
                    } catch (e) {
                        console.warn('Curved path fallback', e);
                    }
                }
                canvas.renderAll();
                recordState();
            };

            document.getElementById('slider-text-curve').addEventListener('input', function () {
                window.aplicarCurvaturaTexto(parseInt(this.value));
            });

            window.removerFondoCajaTexto = function() {
                const obj = canvas.getActiveObject();
                if (obj && (obj.type === 'i-text' || obj.type === 'text')) {
                    obj.set('backgroundColor', null);
                    canvas.renderAll();
                    recordState();
                }
            };

            // -------------------------------------------------------------
            // 7. ELEMENTOS Y FORMAS (CANVA SHAPES & SYMBOLS SUITE)
            // -------------------------------------------------------------
            window.agregarForma = function(tipo) {
                let shape;
                const centerX = canvas.width / 2;
                const centerY = canvas.height / 2;

                // 1. Líneas y Flechas
                if (tipo === 'line') {
                    shape = new fabric.Line([centerX - 140, centerY, centerX + 140, centerY], {
                        stroke: '#1A2B4C', strokeWidth: 4, strokeLineCap: 'round'
                    });
                } else if (tipo === 'dashed_line') {
                    shape = new fabric.Line([centerX - 140, centerY, centerX + 140, centerY], {
                        stroke: '#2E6F40', strokeWidth: 4, strokeDashArray: [10, 8], strokeLineCap: 'round'
                    });
                } else if (tipo === 'arrow') {
                    const line = new fabric.Line([-70, 0, 60, 0], { stroke: '#2E6F40', strokeWidth: 5, strokeLineCap: 'round' });
                    const head = new fabric.Triangle({ left: 70, top: 0, originX: 'center', originY: 'center', angle: 90, width: 22, height: 22, fill: '#2E6F40' });
                    shape = new fabric.Group([line, head], { left: centerX, top: centerY, originX: 'center', originY: 'center' });
                } else if (tipo === 'double_arrow') {
                    const line = new fabric.Line([-60, 0, 60, 0], { stroke: '#1A2B4C', strokeWidth: 5, strokeLineCap: 'round' });
                    const headR = new fabric.Triangle({ left: 70, top: 0, originX: 'center', originY: 'center', angle: 90, width: 20, height: 20, fill: '#1A2B4C' });
                    const headL = new fabric.Triangle({ left: -70, top: 0, originX: 'center', originY: 'center', angle: -90, width: 20, height: 20, fill: '#1A2B4C' });
                    shape = new fabric.Group([headL, line, headR], { left: centerX, top: centerY, originX: 'center', originY: 'center' });
                } else if (tipo === 'chevron') {
                    shape = new fabric.Path('M 0 0 L 140 0 L 175 40 L 140 80 L 0 80 L 35 40 Z', {
                        fill: '#1A2B4C', left: centerX, top: centerY, originX: 'center', originY: 'center'
                    });
                }

                // 2. Geometría
                else if (tipo === 'rect') {
                    shape = new fabric.Rect({
                        left: centerX, top: centerY, originX: 'center', originY: 'center',
                        width: 200, height: 120, fill: '#1A2B4C', rx: 8, ry: 8
                    });
                } else if (tipo === 'pill') {
                    shape = new fabric.Rect({
                        left: centerX, top: centerY, originX: 'center', originY: 'center',
                        width: 220, height: 60, fill: '#2E6F40', rx: 30, ry: 30
                    });
                } else if (tipo === 'circle') {
                    shape = new fabric.Circle({
                        left: centerX, top: centerY, originX: 'center', originY: 'center',
                        radius: 65, fill: '#2E6F40'
                    });
                } else if (tipo === 'triangle') {
                    shape = new fabric.Triangle({
                        left: centerX, top: centerY, originX: 'center', originY: 'center',
                        width: 140, height: 120, fill: '#f59e0b'
                    });
                } else if (tipo === 'star') {
                    const starPath = 'M 0 -60 L 17 -18 L 60 -18 L 25 9 L 38 52 L 0 25 L -38 52 L -25 9 L -60 -18 L -17 -18 Z';
                    shape = new fabric.Path(starPath, {
                        left: centerX, top: centerY, originX: 'center', originY: 'center', fill: '#fbbf24', stroke: '#d97706', strokeWidth: 2
                    });
                } else if (tipo === 'hexagon') {
                    const hexPath = 'M 35 0 L 105 0 L 140 60 L 105 120 L 35 120 L 0 60 Z';
                    shape = new fabric.Path(hexPath, {
                        left: centerX, top: centerY, originX: 'center', originY: 'center', fill: '#1A2B4C', stroke: '#33FF57', strokeWidth: 3
                    });
                } else if (tipo === 'diamond') {
                    shape = new fabric.Rect({
                        left: centerX, top: centerY, originX: 'center', originY: 'center',
                        width: 100, height: 100, fill: '#0284c7', angle: 45
                    });
                }

                // 3. Señalética y Badges SST / SIG
                else if (tipo === 'senal_alerta') {
                    const tri = new fabric.Triangle({ width: 140, height: 120, fill: '#f59e0b', stroke: '#1e293b', strokeWidth: 6, strokeLineJoin: 'round', originX: 'center', originY: 'center' });
                    const excl = new fabric.IText('!', { fontSize: 56, fontWeight: '900', fill: '#1e293b', originX: 'center', originY: 'center', top: 12 });
                    shape = new fabric.Group([tri, excl], { left: centerX, top: centerY, originX: 'center', originY: 'center' });
                } else if (tipo === 'senal_prohibicion') {
                    const ring = new fabric.Circle({ radius: 50, fill: 'transparent', stroke: '#ef4444', strokeWidth: 10, originX: 'center', originY: 'center' });
                    const slash = new fabric.Line([-35, -35, 35, 35], { stroke: '#ef4444', strokeWidth: 10, originX: 'center', originY: 'center' });
                    shape = new fabric.Group([ring, slash], { left: centerX, top: centerY, originX: 'center', originY: 'center' });
                } else if (tipo === 'senal_obligacion') {
                    const circ = new fabric.Circle({ radius: 55, fill: '#2563eb', stroke: '#ffffff', strokeWidth: 4, originX: 'center', originY: 'center' });
                    const hat = new fabric.IText('🦺', { fontSize: 44, originX: 'center', originY: 'center' });
                    shape = new fabric.Group([circ, hat], { left: centerX, top: centerY, originX: 'center', originY: 'center' });
                } else if (tipo === 'sello_sig') {
                    const c1 = new fabric.Circle({ radius: 65, fill: '#2E6F40', originX: 'center', originY: 'center' });
                    const c2 = new fabric.Circle({ radius: 57, fill: 'transparent', stroke: '#33FF57', strokeWidth: 2, strokeDashArray: [6, 4], originX: 'center', originY: 'center' });
                    const t = new fabric.IText('CALIDAD\nSENA SIG\nISO 9001', { fontSize: 13, fontWeight: '900', fill: '#ffffff', textAlign: 'center', originX: 'center', originY: 'center' });
                    shape = new fabric.Group([c1, c2, t], { left: centerX, top: centerY, originX: 'center', originY: 'center' });
                } else if (tipo === 'rombo_nfpa') {
                    const qBlue = new fabric.Rect({ width: 36, height: 36, fill: '#3b82f6', left: -26, top: 0, angle: 45, originX: 'center', originY: 'center' });
                    const qRed = new fabric.Rect({ width: 36, height: 36, fill: '#ef4444', left: 0, top: -26, angle: 45, originX: 'center', originY: 'center' });
                    const qYellow = new fabric.Rect({ width: 36, height: 36, fill: '#eab308', left: 26, top: 0, angle: 45, originX: 'center', originY: 'center' });
                    const qWhite = new fabric.Rect({ width: 36, height: 36, fill: '#ffffff', stroke: '#cbd5e1', left: 0, top: 26, angle: 45, originX: 'center', originY: 'center' });
                    const t1 = new fabric.IText('2', { left: -26, top: 0, fontSize: 16, fontWeight: '900', fill: '#ffffff', originX: 'center', originY: 'center' });
                    const t2 = new fabric.IText('3', { left: 0, top: -26, fontSize: 16, fontWeight: '900', fill: '#ffffff', originX: 'center', originY: 'center' });
                    const t3 = new fabric.IText('0', { left: 26, top: 0, fontSize: 16, fontWeight: '900', fill: '#1e293b', originX: 'center', originY: 'center' });
                    shape = new fabric.Group([qBlue, qRed, qYellow, qWhite, t1, t2, t3], { left: centerX, top: centerY, originX: 'center', originY: 'center' });
                }

                // 4. Contenedores y Tarjetas
                else if (tipo === 'card') {
                    shape = new fabric.Rect({
                        left: centerX, top: centerY, originX: 'center', originY: 'center',
                        width: 340, height: 200, fill: '#ffffff', stroke: '#cbd5e1', strokeWidth: 2, rx: 18, ry: 18,
                        shadow: new fabric.Shadow({ color: 'rgba(0,0,0,0.08)', blur: 20, offsetX: 0, offsetY: 8 })
                    });
                } else if (tipo === 'badge') {
                    const r = new fabric.Rect({ width: 340, height: 60, fill: '#fef3c7', stroke: '#f59e0b', strokeWidth: 2, rx: 16, ry: 16 });
                    const t = new fabric.IText('⚠️ ALERTA SST: ZONA DE PROTECCIÓN', { left: 24, top: 22, fontSize: 13, fontWeight: '900', fill: '#92400e' });
                    shape = new fabric.Group([r, t], { left: centerX, top: centerY, originX: 'center', originY: 'center' });
                } else if (tipo === 'card_ambiental') {
                    const r = new fabric.Rect({ width: 340, height: 160, fill: '#ecfdf5', stroke: '#10b981', strokeWidth: 2, rx: 18, ry: 18 });
                    const t = new fabric.IText('🌱 GESTIÓN AMBIENTAL ISO 14001\nClasificación de residuos y ahorro', { left: 24, top: 24, fontSize: 13, fontWeight: '800', fill: '#065f46', width: 290 });
                    shape = new fabric.Group([r, t], { left: centerX, top: centerY, originX: 'center', originY: 'center' });
                } else if (tipo === 'ribbon') {
                    shape = new fabric.Path('M 0 0 L 280 0 L 310 25 L 280 50 L 0 50 L 30 25 Z', {
                        fill: '#1A2B4C', stroke: '#33FF57', strokeWidth: 2, left: centerX, top: centerY, originX: 'center', originY: 'center'
                    });
                }

                if (shape) {
                    canvas.add(shape);
                    canvas.setActiveObject(shape);
                    canvas.renderAll();
                    recordState();
                }
            };

            // Paleta rápida de relleno
            window.aplicarColorFormaRapido = function(color) {
                const obj = canvas.getActiveObject();
                if (obj) {
                    obj.set('fill', color);
                    document.getElementById('shape-fill').value = color.startsWith('#') ? color : '#2E6F40';
                    canvas.renderAll();
                    recordState();
                }
            };

            // Estilos de borde: sólido, discontinuo, punteado
            window.aplicarEstiloBordeForma = function(estilo) {
                const obj = canvas.getActiveObject();
                if (!obj) return;

                if (obj.strokeWidth === 0 || !obj.strokeWidth) {
                    obj.set('strokeWidth', 2);
                    document.getElementById('shape-stroke-width').value = 2;
                }

                if (estilo === 'solid') {
                    obj.set('strokeDashArray', null);
                } else if (estilo === 'dashed') {
                    obj.set('strokeDashArray', [10, 6]);
                } else if (estilo === 'dotted') {
                    obj.set('strokeDashArray', [3, 4]);
                }

                canvas.renderAll();
                recordState();
            };

            // Voltear Forma (Flip Horizontal / Vertical)
            window.voltearObjeto = function(eje) {
                const obj = canvas.getActiveObject();
                if (!obj) return;
                if (eje === 'H') {
                    obj.set('flipX', !obj.flipX);
                } else if (eje === 'V') {
                    obj.set('flipY', !obj.flipY);
                }
                canvas.renderAll();
                recordState();
            };

            document.getElementById('shape-fill').addEventListener('input', function () {
                const obj = canvas.getActiveObject();
                if (obj) { obj.set('fill', this.value); canvas.renderAll(); recordState(); }
            });

            document.getElementById('shape-stroke').addEventListener('input', function () {
                const obj = canvas.getActiveObject();
                if (obj) { obj.set('stroke', this.value); canvas.renderAll(); recordState(); }
            });

            document.getElementById('shape-stroke-width').addEventListener('input', function () {
                const obj = canvas.getActiveObject();
                if (obj) { obj.set('strokeWidth', parseInt(this.value) || 0); canvas.renderAll(); recordState(); }
            });

            document.getElementById('shape-corner-radius').addEventListener('input', function () {
                const obj = canvas.getActiveObject();
                if (obj && obj.type === 'rect') {
                    const r = parseInt(this.value) || 0;
                    obj.set({ rx: r, ry: r });
                    canvas.renderAll();
                    recordState();
                }
            });

            window.aplicarDegradadoForma = function(tipo = 'sena') {
                const obj = canvas.getActiveObject();
                if (!obj) return;
                const stops = (tipo === 'sisig') ?
                    [{ offset: 0, color: '#1A2B4C' }, { offset: 1, color: '#0ea5e9' }] :
                    [{ offset: 0, color: '#2E6F40' }, { offset: 1, color: '#33FF57' }];

                const grad = new fabric.Gradient({
                    type: 'linear',
                    gradientUnits: 'pixels',
                    coords: { x1: 0, y1: 0, x2: obj.width || 200, y2: obj.height || 200 },
                    colorStops: stops
                });
                obj.set('fill', grad);
                canvas.renderAll();
                recordState();
            };

            // -------------------------------------------------------------
            // 8. MARCOS Y MÁSCARAS DE RECORTE (CANVA FRAMES SUITE)
            // -------------------------------------------------------------
            const NOMBRES_MARCOS = {
                circle: 'Marco Circular',
                rounded: 'Marco Redondeado',
                square: 'Marco Cuadrado 1:1',
                panoramic: 'Marco Panorámico 16:9',
                oval: 'Marco Ovalado',
                arch: 'Marco Ventana en Arco',
                hexagon: 'Marco Hexagonal ISO',
                shield: 'Marco Escudo SST',
                diamond: 'Marco Rombo Alerta',
                star: 'Marco Estrella Logro',
                cross: 'Marco Cruz Brigadista',
                bubble: 'Marco Burbuja Cita',
                device_desktop: 'Mockup Monitor PC',
                device_mobile: 'Mockup Smartphone',
                polaroid: 'Marco Polaroid',
                stamp: 'Marco Sello Postal'
            };

            function obtenerNombreMarco(tipo) {
                return NOMBRES_MARCOS[tipo] || 'Marco de Recorte';
            }

            function crearSiluetaVectorial(tipo, options = {}) {
                const opts = Object.assign({
                    originX: 'center',
                    originY: 'center',
                    left: 0,
                    top: 0
                }, options);

                switch (tipo) {
                    case 'circle':
                        return new fabric.Circle(Object.assign({ radius: 95 }, opts));

                    case 'rounded':
                        return new fabric.Rect(Object.assign({ width: 210, height: 210, rx: 32, ry: 32 }, opts));

                    case 'square':
                        return new fabric.Rect(Object.assign({ width: 210, height: 210, rx: 0, ry: 0 }, opts));

                    case 'panoramic':
                        return new fabric.Rect(Object.assign({ width: 320, height: 180, rx: 16, ry: 16 }, opts));

                    case 'oval':
                        return new fabric.Ellipse(Object.assign({ rx: 115, ry: 80 }, opts));

                    case 'arch':
                        return new fabric.Path('M -90 120 L -90 -30 A 90 90 0 0 1 90 -30 L 90 120 Z', opts);

                    case 'hexagon':
                        return new fabric.Polygon([
                            { x: 0, y: -100 },
                            { x: 86.6, y: -50 },
                            { x: 86.6, y: 50 },
                            { x: 0, y: 100 },
                            { x: -86.6, y: 50 },
                            { x: -86.6, y: -50 }
                        ], opts);

                    case 'shield':
                        return new fabric.Path('M -95 -105 L 95 -105 Q 100 35 0 120 Q -100 35 -95 -105 Z', opts);

                    case 'diamond':
                        return new fabric.Polygon([
                            { x: 0, y: -105 },
                            { x: 105, y: 0 },
                            { x: 0, y: 105 },
                            { x: -105, y: 0 }
                        ], opts);

                    case 'star':
                        const starPts = [];
                        const spikes = 5;
                        const outerR = 100;
                        const innerR = 45;
                        for (let i = 0; i < spikes * 2; i++) {
                            const r = (i % 2 === 0) ? outerR : innerR;
                            const angle = (i * Math.PI) / spikes - Math.PI / 2;
                            starPts.push({ x: Math.cos(angle) * r, y: Math.sin(angle) * r });
                        }
                        return new fabric.Polygon(starPts, opts);

                    case 'cross':
                        return new fabric.Polygon([
                            { x: -25, y: -100 }, { x: 25, y: -100 },
                            { x: 25, y: -25 }, { x: 100, y: -25 },
                            { x: 100, y: 25 }, { x: 25, y: 25 },
                            { x: 25, y: 100 }, { x: -25, y: 100 },
                            { x: -25, y: 25 }, { x: -100, y: 25 },
                            { x: -100, y: -25 }, { x: -25, y: -25 }
                        ], opts);

                    case 'bubble':
                        return new fabric.Path('M -85 -70 L 85 -70 Q 105 -70 105 -50 L 105 30 Q 105 50 85 50 L -35 50 L -65 85 L -55 50 L -85 50 Q -105 50 -105 30 L -105 -50 Q -105 -70 -85 -70 Z', opts);

                    case 'polaroid':
                        return new fabric.Rect(Object.assign({ width: 196, height: 196, rx: 0, ry: 0 }, opts));

                    case 'device_mobile':
                        return new fabric.Rect(Object.assign({ width: 150, height: 300, rx: 22, ry: 22 }, opts));

                    case 'device_desktop':
                        return new fabric.Rect(Object.assign({ width: 270, height: 165, rx: 4, ry: 4 }, opts));

                    case 'stamp':
                        return new fabric.Rect(Object.assign({ width: 195, height: 195, rx: 2, ry: 2 }, opts));

                    default:
                        return new fabric.Circle(Object.assign({ radius: 95 }, opts));
                }
            }

            function crearMarcoVacio(tipo, posX = null, posY = null) {
                const cx = posX !== null ? posX : (canvas.width / 2);
                const cy = posY !== null ? posY : (canvas.height / 2);

                const items = [];

                let defaultStroke = '#10b981';
                if (tipo === 'rounded' || tipo === 'device_desktop' || tipo === 'stamp') defaultStroke = '#0ea5e9';
                if (tipo === 'shield' || tipo === 'oval') defaultStroke = '#f59e0b';
                if (tipo === 'cross' || tipo === 'polaroid') defaultStroke = '#f43f5e';
                if (tipo === 'diamond') defaultStroke = '#f97316';
                if (tipo === 'arch') defaultStroke = '#a855f7';
                if (tipo === 'device_mobile') defaultStroke = '#475569';

                // Decoraciones previas según mockup
                if (tipo === 'polaroid') {
                    const card = new fabric.Rect({
                        width: 230, height: 280, rx: 6, ry: 6,
                        fill: '#ffffff', stroke: '#e2e8f0', strokeWidth: 1.5,
                        originX: 'center', originY: 'center', left: 0, top: 0,
                        shadow: new fabric.Shadow({ color: 'rgba(0,0,0,0.1)', blur: 10, offsetX: 0, offsetY: 4 }),
                        isFrameDecoration: true
                    });
                    const caption = new fabric.IText('Pie de foto...', {
                        left: 0, top: 105, originX: 'center', originY: 'center',
                        fontSize: 14, fontFamily: 'Caveat', fill: '#94a3b8',
                        isFrameDecoration: true
                    });
                    items.push(card, caption);
                } else if (tipo === 'device_desktop') {
                    const standBase = new fabric.Rect({
                        width: 100, height: 10, rx: 4, ry: 4, fill: '#475569',
                        originX: 'center', originY: 'center', left: 0, top: 110, isFrameDecoration: true
                    });
                    const standNeck = new fabric.Rect({
                        width: 28, height: 40, fill: '#64748b',
                        originX: 'center', originY: 'center', left: 0, top: 88, isFrameDecoration: true
                    });
                    const chassis = new fabric.Rect({
                        width: 296, height: 195, rx: 10, ry: 10, fill: '#1e293b', stroke: '#334155', strokeWidth: 2,
                        originX: 'center', originY: 'center', left: 0, top: -22, isFrameDecoration: true
                    });
                    const webCam = new fabric.Circle({
                        radius: 3, fill: '#0f172a', originX: 'center', originY: 'center', left: 0, top: -114, isFrameDecoration: true
                    });
                    items.push(standNeck, standBase, chassis, webCam);
                } else if (tipo === 'device_mobile') {
                    const body = new fabric.Rect({
                        width: 170, height: 330, rx: 32, ry: 32, fill: '#1e293b', stroke: '#334155', strokeWidth: 2,
                        originX: 'center', originY: 'center', left: 0, top: 0, isFrameDecoration: true,
                        shadow: new fabric.Shadow({ color: 'rgba(0,0,0,0.18)', blur: 14, offsetX: 0, offsetY: 6 })
                    });
                    const island = new fabric.Rect({
                        width: 44, height: 12, rx: 6, ry: 6, fill: '#090d16',
                        originX: 'center', originY: 'center', left: 0, top: -138, isFrameDecoration: true
                    });
                    items.push(body, island);
                }

                // Silueta base interior
                const baseOffsetY = (tipo === 'polaroid' ? -25 : (tipo === 'device_desktop' ? -22 : 0));
                const baseShape = crearSiluetaVectorial(tipo, {
                    fill: tipo === 'polaroid' ? '#f8fafc' : (tipo.startsWith('device_') ? '#0f172a' : '#f8fafc'),
                    stroke: defaultStroke,
                    strokeWidth: 2,
                    strokeDashArray: [6, 4],
                    top: baseOffsetY,
                    isFrameBase: true
                });
                items.push(baseShape);

                // Ícono de multimedia y etiqueta
                const iconTop = baseOffsetY - 12;
                const iconText = new fabric.IText('🖼️', {
                    left: 0, top: iconTop, originX: 'center', originY: 'center',
                    fontSize: 24, selectable: false, isFramePlaceholder: true
                });
                const labelText = new fabric.IText('Arrastra foto o video aquí', {
                    left: 0, top: iconTop + 24, originX: 'center', originY: 'center',
                    fontSize: 9.5, fontWeight: 'bold', fill: '#64748b', selectable: false, isFramePlaceholder: true
                });
                items.push(iconText, labelText);

                const group = new fabric.Group(items, {
                    left: cx,
                    top: cy,
                    originX: 'center',
                    originY: 'center',
                    isFrame: true,
                    frameType: tipo,
                    hasImage: false,
                    innerImageSrc: null,
                    frameBorderColor: defaultStroke,
                    frameBorderWidth: 2,
                    frameBorderStyle: 'dashed',
                    objectCaching: false,
                    cornerColor: '#2E6F40'
                });

                return group;
            }

            window.agregarMarco = function(tipo) {
                const frame = crearMarcoVacio(tipo);
                canvas.add(frame);
                canvas.setActiveObject(frame);
                canvas.renderAll();
                recordState();
                updateContextBar();
            };

            function roundRect(ctx, x, y, w, h, r) {
                if (ctx.roundRect) {
                    ctx.roundRect(x, y, w, h, r);
                    return;
                }
                if (w < 2 * r) r = w / 2;
                if (h < 2 * r) r = h / 2;
                ctx.moveTo(x + r, y);
                ctx.arcTo(x + w, y, x + w, y + h, r);
                ctx.arcTo(x + w, y + h, x, y + h, r);
                ctx.arcTo(x, y + h, x, y, r);
                ctx.arcTo(x, y, x + w, y, r);
                ctx.closePath();
            }

            function trazarSiluetaMarco(ctx, tipo, w, h, offsetY = 0) {
                switch (tipo) {
                    case 'circle': {
                        const r = Math.min(w, h) / 2;
                        ctx.arc(0, offsetY, r, 0, Math.PI * 2);
                        break;
                    }
                    case 'rounded': {
                        roundRect(ctx, -w / 2, offsetY - h / 2, w, h, 32);
                        break;
                    }
                    case 'square': {
                        ctx.rect(-w / 2, offsetY - h / 2, w, h);
                        break;
                    }
                    case 'panoramic': {
                        roundRect(ctx, -w / 2, offsetY - h / 2, w, h, 16);
                        break;
                    }
                    case 'oval': {
                        ctx.ellipse(0, offsetY, w / 2, h / 2, 0, 0, Math.PI * 2);
                        break;
                    }
                    case 'arch': {
                        const halfW = w / 2;
                        const topCenterY = offsetY - (h / 2) + halfW;
                        ctx.moveTo(-halfW, offsetY + h / 2);
                        ctx.lineTo(-halfW, topCenterY);
                        ctx.arc(0, topCenterY, halfW, Math.PI, 0, false);
                        ctx.lineTo(halfW, offsetY + h / 2);
                        ctx.closePath();
                        break;
                    }
                    case 'hexagon': {
                        ctx.moveTo(0, offsetY - 100);
                        ctx.lineTo(86.6, offsetY - 50);
                        ctx.lineTo(86.6, offsetY + 50);
                        ctx.lineTo(0, offsetY + 100);
                        ctx.lineTo(-86.6, offsetY + 50);
                        ctx.lineTo(-86.6, offsetY - 50);
                        ctx.closePath();
                        break;
                    }
                    case 'shield': {
                        ctx.moveTo(-95, offsetY - 105);
                        ctx.lineTo(95, offsetY - 105);
                        ctx.quadraticCurveTo(100, offsetY + 35, 0, offsetY + 120);
                        ctx.quadraticCurveTo(-100, offsetY + 35, -95, offsetY - 105);
                        ctx.closePath();
                        break;
                    }
                    case 'diamond': {
                        ctx.moveTo(0, offsetY - 105);
                        ctx.lineTo(105, offsetY);
                        ctx.lineTo(0, offsetY + 105);
                        ctx.lineTo(-105, offsetY);
                        ctx.closePath();
                        break;
                    }
                    case 'star': {
                        const spikes = 5, outerR = 100, innerR = 45;
                        for (let i = 0; i < spikes * 2; i++) {
                            const r = (i % 2 === 0) ? outerR : innerR;
                            const angle = (i * Math.PI) / spikes - Math.PI / 2;
                            const x = Math.cos(angle) * r;
                            const y = offsetY + Math.sin(angle) * r;
                            if (i === 0) ctx.moveTo(x, y);
                            else ctx.lineTo(x, y);
                        }
                        ctx.closePath();
                        break;
                    }
                    case 'cross': {
                        const pts = [
                            { x: -25, y: -100 }, { x: 25, y: -100 },
                            { x: 25, y: -25 }, { x: 100, y: -25 },
                            { x: 100, y: 25 }, { x: 25, y: 25 },
                            { x: 25, y: 100 }, { x: -25, y: 100 },
                            { x: -25, y: 25 }, { x: -100, y: 25 },
                            { x: -100, y: -25 }, { x: -25, y: -25 }
                        ];
                        ctx.moveTo(pts[0].x, offsetY + pts[0].y);
                        for (let i = 1; i < pts.length; i++) ctx.lineTo(pts[i].x, offsetY + pts[i].y);
                        ctx.closePath();
                        break;
                    }
                    case 'bubble': {
                        ctx.moveTo(-85, offsetY - 70);
                        ctx.lineTo(85, offsetY - 70);
                        ctx.quadraticCurveTo(105, offsetY - 70, 105, offsetY - 50);
                        ctx.lineTo(105, offsetY + 30);
                        ctx.quadraticCurveTo(105, offsetY + 50, 85, offsetY + 50);
                        ctx.lineTo(-35, offsetY + 50);
                        ctx.lineTo(-65, offsetY + 85);
                        ctx.lineTo(-55, offsetY + 50);
                        ctx.lineTo(-85, offsetY + 50);
                        ctx.quadraticCurveTo(-105, offsetY + 50, -105, offsetY + 30);
                        ctx.lineTo(-105, offsetY - 50);
                        ctx.quadraticCurveTo(-105, offsetY - 70, -85, offsetY - 70);
                        ctx.closePath();
                        break;
                    }
                    case 'polaroid': {
                        ctx.rect(-w / 2, offsetY - h / 2, w, h);
                        break;
                    }
                    case 'device_desktop': {
                        roundRect(ctx, -w / 2, offsetY - h / 2, w, h, 4);
                        break;
                    }
                    case 'device_mobile': {
                        roundRect(ctx, -w / 2, offsetY - h / 2, w, h, 22);
                        break;
                    }
                    case 'stamp': {
                        roundRect(ctx, -w / 2, offsetY - h / 2, w, h, 2);
                        break;
                    }
                    default: {
                        ctx.arc(0, offsetY, Math.min(w, h) / 2, 0, Math.PI * 2);
                        break;
                    }
                }
            }

            function obtenerDimensionesMarco(frameType) {
                let targetW = 210, targetH = 210, objW = 210, objH = 210, centerOffsetY = 0;
                if (frameType === 'circle') { targetW = 190; targetH = 190; objW = 190; objH = 190; }
                else if (frameType === 'rounded') { targetW = 210; targetH = 210; objW = 210; objH = 210; }
                else if (frameType === 'square') { targetW = 210; targetH = 210; objW = 210; objH = 210; }
                else if (frameType === 'panoramic') { targetW = 320; targetH = 180; objW = 320; objH = 180; }
                else if (frameType === 'oval') { targetW = 230; targetH = 160; objW = 230; objH = 160; }
                else if (frameType === 'arch') { targetW = 180; targetH = 240; objW = 180; objH = 240; }
                else if (frameType === 'hexagon') { targetW = 174; targetH = 200; objW = 174; objH = 200; }
                else if (frameType === 'shield') { targetW = 190; targetH = 225; objW = 190; objH = 225; centerOffsetY = 7.5; }
                else if (frameType === 'diamond') { targetW = 210; targetH = 210; objW = 210; objH = 210; }
                else if (frameType === 'star') { targetW = 200; targetH = 200; objW = 200; objH = 200; }
                else if (frameType === 'cross') { targetW = 200; targetH = 200; objW = 200; objH = 200; }
                else if (frameType === 'bubble') { targetW = 210; targetH = 155; objW = 210; objH = 155; centerOffsetY = 7.5; }
                else if (frameType === 'polaroid') { targetW = 196; targetH = 196; objW = 230; objH = 280; centerOffsetY = -25; }
                else if (frameType === 'device_desktop') { targetW = 270; targetH = 165; objW = 296; objH = 235; centerOffsetY = -22; }
                else if (frameType === 'device_mobile') { targetW = 150; targetH = 300; objW = 170; objH = 330; centerOffsetY = 0; }
                else if (frameType === 'stamp') { targetW = 195; targetH = 195; objW = 195; objH = 195; }
                return { targetW, targetH, objW, objH, centerOffsetY };
            }

            function configurarRenderMarco(frameObj) {
                frameObj.objectCaching = false;
                frameObj.needsItsOwnCache = function() { return false; };
                frameObj._renderConfigured = true;

                const dims = obtenerDimensionesMarco(frameObj.frameType);
                const targetW = dims.targetW;
                const targetH = dims.targetH;
                const centerOffsetY = dims.centerOffsetY;

                if (!frameObj._imageElement && frameObj.innerImageSrc) {
                    const imgEl = new Image();
                    imgEl.crossOrigin = 'anonymous';
                    imgEl.onload = function() {
                        canvas.renderAll();
                    };
                    imgEl.onerror = function() {
                        if (imgEl.crossOrigin) {
                            imgEl.crossOrigin = null;
                            imgEl.src = frameObj.innerImageSrc;
                        }
                    };
                    imgEl.src = frameObj.innerImageSrc;
                    frameObj._imageElement = imgEl;
                }

                frameObj._render = function(ctx) {
                    const fType = this.frameType;
                    const cZoom = (typeof this.innerZoom === 'number' && this.innerZoom >= 1.0) ? this.innerZoom : 1.0;

                    // 1. Fondos / chasis decorativos externos (Polaroid, Computador, Celular)
                    if (fType === 'polaroid') {
                        ctx.save();
                        ctx.shadowColor = 'rgba(0,0,0,0.12)';
                        ctx.shadowBlur = 12;
                        ctx.shadowOffsetY = 4;
                        ctx.fillStyle = '#ffffff';
                        ctx.strokeStyle = '#e2e8f0';
                        ctx.lineWidth = 1.5;
                        ctx.beginPath();
                        roundRect(ctx, -115, -140, 230, 280, 6);
                        ctx.fill();
                        ctx.shadowColor = 'transparent';
                        ctx.stroke();

                        ctx.font = '14px Caveat';
                        ctx.fillStyle = '#475569';
                        ctx.textAlign = 'center';
                        ctx.fillText('Pie de foto...', 0, 105);
                        ctx.restore();
                    } else if (fType === 'device_desktop') {
                        ctx.save();
                        ctx.fillStyle = '#475569';
                        ctx.beginPath();
                        roundRect(ctx, -50, 105, 100, 10, 4);
                        ctx.fill();
                        ctx.fillStyle = '#64748b';
                        ctx.fillRect(-14, 68, 28, 40);
                        ctx.fillStyle = '#1e293b';
                        ctx.strokeStyle = '#334155';
                        ctx.lineWidth = 2;
                        ctx.beginPath();
                        roundRect(ctx, -148, -120, 296, 195, 10);
                        ctx.fill();
                        ctx.stroke();
                        ctx.fillStyle = '#0f172a';
                        ctx.beginPath();
                        ctx.arc(0, -114, 3, 0, Math.PI * 2);
                        ctx.fill();
                        ctx.restore();
                    } else if (fType === 'device_mobile') {
                        ctx.save();
                        ctx.shadowColor = 'rgba(0,0,0,0.2)';
                        ctx.shadowBlur = 14;
                        ctx.shadowOffsetY = 6;
                        ctx.fillStyle = '#1e293b';
                        ctx.strokeStyle = '#334155';
                        ctx.lineWidth = 2;
                        ctx.beginPath();
                        roundRect(ctx, -85, -165, 170, 330, 32);
                        ctx.fill();
                        ctx.shadowColor = 'transparent';
                        ctx.stroke();
                        ctx.restore();
                    }

                    // 2. Dibujo vectorial de la FOTO O VIDEO con RECORTE NATIVO a resolución completa
                    ctx.save();
                    ctx.beginPath();
                    trazarSiluetaMarco(ctx, fType, targetW, targetH, centerOffsetY);
                    ctx.clip();

                    // Relleno que se adapta dinámicamente al fondo del lienzo para evitar bordes o separaciones discordantes
                    ctx.fillStyle = canvas.backgroundColor || '#ffffff';
                    ctx.fill();

                    const imgEl = this._imageElement;
                    if (imgEl && (imgEl.naturalWidth || imgEl.width)) {
                        const nw = imgEl.naturalWidth || imgEl.width;
                        const nh = imgEl.naturalHeight || imgEl.height;

                        const baseScale = Math.max(targetW / nw, targetH / nh);
                        const finalScale = baseScale * cZoom;
                        const drawW = nw * finalScale;
                        const drawH = nh * finalScale;

                        // Máxima calidad bicúbica del hardware sin pérdidas
                        ctx.imageSmoothingEnabled = true;
                        ctx.imageSmoothingQuality = 'high';

                        // Inyección directa de píxeles sin compresión ni buffers intermedios
                        ctx.drawImage(imgEl, -drawW / 2, centerOffsetY - drawH / 2, drawW, drawH);
                    } else if (this.hasVideo) {
                        ctx.fillStyle = '#0f172a';
                        ctx.fillRect(-targetW, centerOffsetY - targetH, targetW * 2, targetH * 2);
                    }

                    // Si el marco contiene un VIDEO interactivo, renderizar botón de reproducción y badge
                    if (this.hasVideo) {
                        // Velo sutil translúcido para asegurar contraste óptimo del botón de play
                        ctx.fillStyle = 'rgba(15, 23, 42, 0.28)';
                        ctx.fillRect(-targetW, centerOffsetY - targetH, targetW * 2, targetH * 2);

                        const btnRadius = Math.max(16, Math.min(26, Math.min(targetW, targetH) * 0.17));

                        // Sombra difuminada para el botón
                        ctx.save();
                        ctx.shadowColor = 'rgba(0, 0, 0, 0.45)';
                        ctx.shadowBlur = 10;
                        ctx.shadowOffsetY = 2;

                        // Botón circular rojo
                        ctx.fillStyle = '#e11d48';
                        ctx.strokeStyle = '#ffffff';
                        ctx.lineWidth = 2.5;
                        ctx.beginPath();
                        ctx.arc(0, centerOffsetY, btnRadius, 0, Math.PI * 2);
                        ctx.fill();
                        ctx.shadowColor = 'transparent';
                        ctx.stroke();
                        ctx.restore();

                        // Triángulo blanco de reproducción centrado
                        const triH = btnRadius * 0.55;
                        ctx.fillStyle = '#ffffff';
                        ctx.beginPath();
                        ctx.moveTo(-triH * 0.5 + 2, centerOffsetY - triH);
                        ctx.lineTo(triH * 0.8 + 2, centerOffsetY);
                        ctx.lineTo(-triH * 0.5 + 2, centerOffsetY + triH);
                        ctx.closePath();
                        ctx.fill();

                        // Badge inferior estilizado si la altura del marco lo permite
                        if (targetH >= 90) {
                            const pillW = 76;
                            const pillH = 17;
                            const pillY = centerOffsetY + (targetH * 0.36);
                            ctx.save();
                            ctx.fillStyle = 'rgba(15, 23, 42, 0.85)';
                            ctx.beginPath();
                            roundRect(ctx, -pillW / 2, pillY - pillH / 2, pillW, pillH, 8);
                            ctx.fill();
                            ctx.font = '800 8px "Plus Jakarta Sans", sans-serif';
                            ctx.fillStyle = '#33FF57';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            ctx.fillText('▶ VIDEO', 0, pillY);
                            ctx.restore();
                        }
                    }

                    ctx.restore();

                    // 3. Decoraciones frontales (isla dinámica de móvil)
                    if (fType === 'device_mobile') {
                        ctx.save();
                        ctx.fillStyle = '#090d16';
                        ctx.beginPath();
                        roundRect(ctx, -22, -144, 44, 12, 6);
                        ctx.fill();
                        ctx.restore();
                    }

                    // 4. Borde opcional si el usuario lo activa en la barra superior
                    if (this.frameBorderWidth > 0 && this.frameBorderStyle !== 'none') {
                        ctx.save();
                        ctx.lineWidth = this.frameBorderWidth;
                        ctx.strokeStyle = this.frameBorderColor || '#10b981';
                        if (this.frameBorderStyle === 'dashed') ctx.setLineDash([6, 4]);
                        else if (this.frameBorderStyle === 'dotted') ctx.setLineDash([2, 3]);

                        ctx.beginPath();
                        trazarSiluetaMarco(ctx, fType, targetW, targetH, centerOffsetY);
                        ctx.stroke();
                        ctx.restore();
                    }
                };
            }

            function rehidratarMarcosCanvas() {
                const targetColor = canvas.backgroundColor || '#ffffff';
                canvas.getObjects().forEach(function(obj) {
                    if (obj.isFrame && (obj.hasImage || obj.hasVideo) && !obj._renderConfigured) {
                        configurarRenderMarco(obj);
                    }
                    if (obj.isVideoCard && obj._objects) {
                        obj._objects.forEach(function(child) {
                            child._parentVideoCard = obj;
                            if (child.isAdaptiveVideoBg && child.fill !== targetColor) {
                                child.set('fill', targetColor);
                            }
                        });
                    }
                });
            }

            window.colocarImagenEnMarco = function(frameGroup, imageUrl) {
                if (!frameGroup || !frameGroup.isFrame) return;

                const frameType = frameGroup.frameType || 'circle';
                const left = frameGroup.left;
                const top = frameGroup.top;
                const scaleX = frameGroup.scaleX || 1;
                const scaleY = frameGroup.scaleY || 1;
                const angle = frameGroup.angle || 0;

                const hasExplicitBorder = frameGroup.hasExplicitBorder === true;
                const borderColor = frameGroup.frameBorderColor || '#10b981';
                const borderWidth = hasExplicitBorder ? (frameGroup.frameBorderWidth || 0) : 0;
                const borderStyle = hasExplicitBorder ? (frameGroup.frameBorderStyle || 'none') : 'none';

                const dims = obtenerDimensionesMarco(frameType);

                const frameObj = new fabric.Rect({
                    left: left,
                    top: top,
                    width: dims.objW,
                    height: dims.objH,
                    scaleX: scaleX,
                    scaleY: scaleY,
                    angle: angle,
                    originX: 'center',
                    originY: 'center',
                    fill: 'transparent',
                    stroke: 'transparent',
                    strokeWidth: 0,
                    isFrame: true,
                    frameType: frameType,
                    hasImage: true,
                    innerImageSrc: imageUrl,
                    innerZoom: frameGroup.innerZoom || 1.0,
                    hasExplicitBorder: hasExplicitBorder,
                    frameBorderColor: borderColor,
                    frameBorderWidth: borderWidth,
                    frameBorderStyle: borderStyle,
                    objectCaching: false,
                    cornerColor: '#2E6F40'
                });

                configurarRenderMarco(frameObj);

                const idx = canvas.getObjects().indexOf(frameGroup);
                canvas.remove(frameGroup);
                if (idx > -1) {
                    canvas.insertAt(frameObj, idx);
                } else {
                    canvas.add(frameObj);
                }

                canvas.setActiveObject(frameObj);

                if (canvas.contextContainer) {
                    canvas.contextContainer.imageSmoothingEnabled = true;
                    canvas.contextContainer.imageSmoothingQuality = 'high';
                }

                canvas.renderAll();
                recordState();
                updateContextBar();

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Foto colocada en alta resolución',
                    showConfirmButton: false,
                    timer: 1800
                });
            };

            // Colocar Video Interactivo en un Marco (Cualquier forma o mockup)
            window.colocarVideoEnMarco = function(frameGroup, videoData) {
                if (!frameGroup || !frameGroup.isFrame) return;

                const frameType = frameGroup.frameType || 'circle';
                const left = frameGroup.left;
                const top = frameGroup.top;
                const scaleX = frameGroup.scaleX || 1;
                const scaleY = frameGroup.scaleY || 1;
                const angle = frameGroup.angle || 0;

                const hasExplicitBorder = frameGroup.hasExplicitBorder === true;
                const borderColor = frameGroup.frameBorderColor || '#10b981';
                const borderWidth = hasExplicitBorder ? (frameGroup.frameBorderWidth || 0) : 0;
                const borderStyle = hasExplicitBorder ? (frameGroup.frameBorderStyle || 'none') : 'none';

                const dims = obtenerDimensionesMarco(frameType);

                let thumbUrl = videoData.thumbnail || '';
                if (thumbUrl && thumbUrl.includes('hqdefault.jpg')) {
                    thumbUrl = thumbUrl.replace('hqdefault.jpg', 'mqdefault.jpg');
                }
                if (!thumbUrl && videoData.url) {
                    const ytMatch = videoData.url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/|youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/i);
                    if (ytMatch) {
                        thumbUrl = `https://img.youtube.com/vi/${ytMatch[1]}/mqdefault.jpg`;
                    }
                }

                const embedUrl = videoData.embed_url || videoData.embedUrl || videoData.url;
                const videoTitle = videoData.title || videoData.videoTitle || 'Video Interactivo';
                const videoType = videoData.provider || videoData.videoType || 'youtube';

                const frameObj = new fabric.Rect({
                    left: left,
                    top: top,
                    width: dims.objW,
                    height: dims.objH,
                    scaleX: scaleX,
                    scaleY: scaleY,
                    angle: angle,
                    originX: 'center',
                    originY: 'center',
                    fill: 'transparent',
                    stroke: 'transparent',
                    strokeWidth: 0,
                    isFrame: true,
                    frameType: frameType,
                    hasImage: true,
                    hasVideo: true,
                    isVideoFrame: true,
                    innerImageSrc: thumbUrl,
                    innerZoom: frameGroup.innerZoom || 1.0,
                    videoUrl: videoData.url,
                    embedUrl: embedUrl,
                    videoTitle: videoTitle,
                    videoType: videoType,
                    hasExplicitBorder: hasExplicitBorder,
                    frameBorderColor: borderColor,
                    frameBorderWidth: borderWidth,
                    frameBorderStyle: borderStyle,
                    objectCaching: false,
                    cornerColor: '#2E6F40'
                });

                configurarRenderMarco(frameObj);

                const idx = canvas.getObjects().indexOf(frameGroup);
                canvas.remove(frameGroup);
                if (idx > -1) {
                    canvas.insertAt(frameObj, idx);
                } else {
                    canvas.add(frameObj);
                }

                canvas.setActiveObject(frameObj);
                window._lastSelectedFrame = frameObj;

                if (canvas.contextContainer) {
                    canvas.contextContainer.imageSmoothingEnabled = true;
                    canvas.contextContainer.imageSmoothingQuality = 'high';
                }

                canvas.renderAll();
                recordState();
                updateContextBar();

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: `Video colocado en marco (${videoTitle})`,
                    showConfirmButton: false,
                    timer: 2000
                });
            };

            // Reproducir Video del Marco Seleccionado
            window.reproducirVideoMarcoSeleccionado = function() {
                const active = canvas.getActiveObject();
                if (active && active.isFrame && active.hasVideo) {
                    reproducirVideoEnModal(active.embedUrl || active.videoUrl, active.videoTitle, active.videoType);
                }
            };

            // Abrir Modal de Video dirigido específicamente al marco seleccionado (permite elegir de la biblioteca o nuevo)
            window.abrirModalNuevoVideoParaMarco = function() {
                const active = (canvas.getActiveObject() && canvas.getActiveObject().isFrame) 
                    ? canvas.getActiveObject() 
                    : (window._lastSelectedFrame && canvas.getObjects().includes(window._lastSelectedFrame) ? window._lastSelectedFrame : null);

                if (active && active.isFrame) {
                    window._targetFrameForVideo = active;
                }
                abrirModalSeleccionarVideoParaMarco();
            };

            // Selector / Modal inteligente para colocar video en el marco
            window.abrirModalSeleccionarVideoParaMarco = function() {
                const active = (canvas.getActiveObject() && canvas.getActiveObject().isFrame) 
                    ? canvas.getActiveObject() 
                    : (window._lastSelectedFrame && canvas.getObjects().includes(window._lastSelectedFrame) ? window._lastSelectedFrame : null);

                if (!active || !active.isFrame) {
                    Swal.fire('Selecciona un Marco', 'Haz clic primero sobre el marco en el lienzo donde deseas colocar el video.', 'info');
                    return;
                }
                window._targetFrameForVideo = active;

                const existingVideos = (window._galeriaMultimedia || []).filter(m => m.type === 'video');

                if (existingVideos.length > 0) {
                    let videosHtml = `
                        <div class="text-left space-y-3 pt-1">
                            <p class="text-xs text-slate-500 font-semibold mb-2">Selecciona un video de tu biblioteca o añade un nuevo enlace:</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-60 overflow-y-auto p-1">
                    `;

                    existingVideos.forEach((v, i) => {
                        const thumb = v.thumbnail || 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=400';
                        videosHtml += `
                            <button type="button" class="btn-select-existing-video w-full p-2 bg-slate-50 hover:bg-emerald-50 rounded-xl border border-slate-200 hover:border-emerald-500 text-left transition-all flex items-center gap-2.5 cursor-pointer group" data-video-index="${i}">
                                <div class="w-16 h-11 rounded-lg overflow-hidden bg-slate-900 flex-shrink-0 relative">
                                    <img src="${thumb}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/25 flex items-center justify-center">
                                        <i class="fas fa-play text-white text-[10px]"></i>
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="text-xs font-bold text-slate-800 group-hover:text-emerald-700 truncate block">${v.title || 'Video'}</span>
                                    <span class="text-[10px] text-slate-400 uppercase font-semibold">${v.provider || 'video'}</span>
                                </div>
                            </button>
                        `;
                    });

                    videosHtml += `
                            </div>
                            <div class="pt-2 border-t border-slate-200 flex justify-between items-center">
                                <span class="text-[11px] text-slate-400">¿Deseas otro video?</span>
                                <button type="button" id="btn-swal-new-url" class="px-3 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs flex items-center gap-1.5 transition-colors cursor-pointer">
                                    <i class="fab fa-youtube"></i> Nuevo enlace URL
                                </button>
                            </div>
                        </div>
                    `;

                    Swal.fire({
                        title: '<div class="text-sm font-black text-slate-800 uppercase tracking-wide flex items-center justify-center gap-2"><i class="fab fa-youtube text-rose-600"></i> Colocar Video en Marco</div>',
                        html: videosHtml,
                        showConfirmButton: false,
                        showCancelButton: true,
                        cancelButtonText: 'Cerrar',
                        cancelButtonColor: '#64748b',
                        didOpen: () => {
                            const modal = Swal.getHtmlContainer();
                            modal.querySelectorAll('.btn-select-existing-video').forEach(btn => {
                                btn.addEventListener('click', function() {
                                    const idx = parseInt(this.dataset.videoIndex, 10);
                                    const vid = existingVideos[idx];
                                    if (vid && window._targetFrameForVideo) {
                                        colocarVideoEnMarco(window._targetFrameForVideo, vid);
                                        window._targetFrameForVideo = null;
                                        Swal.close();
                                    }
                                });
                            });
                            const btnNewUrl = modal.querySelector('#btn-swal-new-url');
                            if (btnNewUrl) {
                                btnNewUrl.addEventListener('click', () => {
                                    abrirModalNuevoVideo();
                                });
                            }
                        }
                    });
                } else {
                    abrirModalNuevoVideo();
                }
            };

            // Permitir colocar una tarjeta de video ya existente en el lienzo dentro de un marco
            window.iniciarSeleccionMarcoParaVideo = function() {
                const active = canvas.getActiveObject();
                if (!active || (!active.isVideoCard && !active._parentVideoCard)) return;
                const videoCard = active.isVideoCard ? active : active._parentVideoCard;

                const frames = canvas.getObjects().filter(o => o.isFrame);
                if (frames.length === 0) {
                    Swal.fire({
                        title: 'No hay marcos en el lienzo',
                        text: 'Abre la pestaña "Marcos" en el panel izquierdo y agrega un marco primero (círculo, computador, celular, etc.).',
                        icon: 'info',
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#2E6F40'
                    });
                    return;
                }

                const videoData = {
                    url: videoCard.videoUrl,
                    embed_url: videoCard.embedUrl || videoCard.videoUrl,
                    title: videoCard.videoTitle || 'Video',
                    provider: videoCard.videoType || 'youtube',
                    thumbnail: videoCard.thumbnail || ''
                };

                if (frames.length === 1) {
                    canvas.remove(videoCard);
                    colocarVideoEnMarco(frames[0], videoData);
                    return;
                }

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'info',
                    title: 'Haz clic sobre el marco donde deseas colocar el video',
                    showConfirmButton: false,
                    timer: 3500
                });

                frames.forEach(f => activarBrilloMarco(f));

                function onCanvasFramePick(opt) {
                    if (opt.target && opt.target.isFrame) {
                        canvas.off('mouse:down', onCanvasFramePick);
                        frames.forEach(f => desactivarBrilloMarco(f));
                        canvas.remove(videoCard);
                        colocarVideoEnMarco(opt.target, videoData);
                    }
                }
                setTimeout(() => {
                    canvas.on('mouse:down', onCanvasFramePick);
                }, 50);
            };

            window.desvincularImagenDeMarco = function() {
                const active = canvas.getActiveObject();
                if (!active || !active.isFrame || (!active.hasImage && !active.hasVideo)) return;

                const frameType = active.frameType;
                const left = active.left;
                const top = active.top;
                const scaleX = active.scaleX || 1;
                const scaleY = active.scaleY || 1;
                const imgSrc = active.innerImageSrc;
                const hadVideo = active.hasVideo;
                const videoData = hadVideo ? {
                    url: active.videoUrl,
                    embed_url: active.embedUrl,
                    title: active.videoTitle,
                    provider: active.videoType,
                    thumbnail: active.innerImageSrc
                } : null;

                const emptyFrame = crearMarcoVacio(frameType, left, top);
                emptyFrame.set({ scaleX, scaleY });

                const idx = canvas.getObjects().indexOf(active);
                canvas.remove(active);
                if (idx > -1) {
                    canvas.insertAt(emptyFrame, idx);
                } else {
                    canvas.add(emptyFrame);
                }

                if (hadVideo && videoData) {
                    insertarVideoEnLienzo(videoData, left + 40, top + 30);
                } else if (imgSrc) {
                    fabric.Image.fromURL(imgSrc, function(img) {
                        const maxW = canvas.width * 0.45;
                        const maxH = canvas.height * 0.45;
                        if (img.width > maxW || img.height > maxH) {
                            const s = Math.min(maxW / img.width, maxH / img.height);
                            img.scale(s);
                        }
                        img.set({
                            left: left + 50,
                            top: top + 40,
                            originX: 'center',
                            originY: 'center',
                            cornerColor: '#2E6F40'
                        });
                        canvas.add(img);
                        canvas.setActiveObject(img);
                        canvas.renderAll();
                        recordState();
                    }, { crossOrigin: 'anonymous' });
                }

                canvas.setActiveObject(emptyFrame);
                canvas.renderAll();
                recordState();
                updateContextBar();

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'info',
                    title: hadVideo ? 'Video desvinculado del marco' : 'Foto desvinculada del marco',
                    showConfirmButton: false,
                    timer: 1800
                });
            };

            window.actualizarBordeMarcoActivo = function() {
                const active = canvas.getActiveObject();
                if (!active || !active.isFrame) return;

                const color = document.getElementById('frame-border-color').value;
                const width = parseInt(document.getElementById('frame-border-width').value, 10) || 0;
                const style = document.getElementById('frame-border-style').value;

                active.hasExplicitBorder = (width > 0 && style !== 'none');
                active.frameBorderColor = color;
                active.frameBorderWidth = width;
                active.frameBorderStyle = style;

                if (active.forEachObject) {
                    const dash = style === 'dashed' ? [6, 4] : (style === 'dotted' ? [2, 3] : null);
                    const strokeColor = (width > 0 && style !== 'none') ? color : 'transparent';
                    active.forEachObject(function(obj) {
                        if (obj.isFrameBorder || obj.isFrameBase) {
                            obj.set({
                                stroke: strokeColor,
                                strokeWidth: width,
                                strokeDashArray: dash
                            });
                        }
                    });
                }

                canvas.renderAll();
                recordState();
            };

            // Ajuste interactivo de Zoom y Encuadre de la Foto dentro del marco
            window.actualizarZoomFotoMarco = function() {
                const active = canvas.getActiveObject();
                if (!active || !active.isFrame || !active.hasImage) return;

                const slider = document.getElementById('frame-inner-zoom');
                const label = document.getElementById('label-frame-inner-zoom');
                if (!slider) return;

                const zoomPercent = parseInt(slider.value, 10) || 100;
                if (label) label.textContent = zoomPercent + '%';

                active.innerZoom = zoomPercent / 100;
                canvas.renderAll();
            };

            const frameBorderColorInput = document.getElementById('frame-border-color');
            const frameBorderWidthInput = document.getElementById('frame-border-width');
            const frameBorderStyleInput = document.getElementById('frame-border-style');

            if (frameBorderColorInput) frameBorderColorInput.addEventListener('input', window.actualizarBordeMarcoActivo);
            if (frameBorderWidthInput) frameBorderWidthInput.addEventListener('input', window.actualizarBordeMarcoActivo);
            if (frameBorderStyleInput) frameBorderStyleInput.addEventListener('change', window.actualizarBordeMarcoActivo);

            const frameInnerZoomInput = document.getElementById('frame-inner-zoom');
            if (frameInnerZoomInput) {
                frameInnerZoomInput.addEventListener('input', window.actualizarZoomFotoMarco);
                frameInnerZoomInput.addEventListener('change', function() {
                    recordState();
                });
            }

            // Doble clic sobre el marco con foto para enfocar el control de zoom (interacción tipo Canva)
            canvas.on('mouse:dblclick', function(e) {
                const target = e.target;
                if (target && target.isFrame && target.hasImage && !target.hasVideo) {
                    const slider = document.getElementById('frame-inner-zoom');
                    if (slider) {
                        slider.focus();
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'info',
                            title: 'Ajusta el zoom de la foto en la barra superior',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                }
            });

            const frameDirectFileInput = document.getElementById('frame-direct-file-input');
            if (frameDirectFileInput) {
                frameDirectFileInput.addEventListener('change', async function(e) {
                    const file = e.target.files[0];
                    if (!file) return;

                    const active = canvas.getActiveObject();
                    if (!active || !active.isFrame) return;

                    const formData = new FormData();
                    formData.append('image', file);

                    const btnLabel = document.getElementById('btn-label-frame-image');
                    if (btnLabel) btnLabel.textContent = 'Subiendo...';

                    try {
                        const response = await fetch('/sisig/editor/modules/upload-media', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: formData
                        });

                        const data = await response.json();
                        if (response.ok && data.success && data.url) {
                            colocarImagenEnMarco(active, data.url);
                            agregarMiniaturaGaleria(data.url, file.name);
                        } else {
                            Swal.fire('Error al subir imagen', data.message || 'No se pudo subir la foto.', 'error');
                        }
                    } catch (err) {
                        console.error('Error subiendo imagen a marco:', err);
                        Swal.fire('Error', 'Fallo de conexión al subir la foto.', 'error');
                    } finally {
                        if (btnLabel) btnLabel.textContent = active.hasImage ? 'Cambiar Foto' : 'Cargar Foto';
                        frameDirectFileInput.value = '';
                    }
                });
            }

            window.cargarFotoEnMarcoSeleccionado = function() {
                const active = canvas.getActiveObject();
                if (!active || !active.isFrame) {
                    Swal.fire('Selecciona un Marco', 'Haz clic primero sobre el marco en el lienzo donde deseas colocar la foto.', 'info');
                    return;
                }
                if (frameDirectFileInput) frameDirectFileInput.click();
            };

            // -------------------------------------------------------------
            // 9. SUBIDAS MULTIMEDIA, VIDEOS Y DRAG & DROP PERSISTENTE
            // -------------------------------------------------------------
            const drawerFileInput = document.getElementById('drawer-file-input');
            const uploadsGallery = document.getElementById('uploads-gallery');
            const countUploads = document.getElementById('count-uploads');
            let uploadsCount = 0;

            // Cargar automáticamente todos los recursos multimedia guardados en el servidor
            async function cargarGaleriaMultimedia() {
                try {
                    const response = await fetch('/sisig/editor/modules/media', {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });
                    const data = await response.json();

                    if (response.ok && data.success && Array.isArray(data.media)) {
                        window._galeriaMultimedia = data.media;
                        uploadsGallery.innerHTML = '';
                        uploadsCount = 0;

                        if (data.media.length === 0) {
                            uploadsGallery.innerHTML = `
                                <div class="col-span-2 py-8 text-center text-slate-400">
                                    <i class="fas fa-photo-video text-2xl text-slate-300 mb-2 block"></i>
                                    <p class="text-[11px] font-medium">Aún no hay archivos multimedia.</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">Sube imágenes o agrega enlaces de videos.</p>
                                </div>
                            `;
                            countUploads.textContent = '0 archivos';
                            return;
                        }

                        data.media.forEach(item => {
                            agregarItemGaleria(item, false);
                        });
                    }
                } catch (err) {
                    console.error('Error al cargar multimedia:', err);
                }
            }

            // Llamada inicial al arrancar el editor
            cargarGaleriaMultimedia();

            // Subida de imagen por archivo
            drawerFileInput.addEventListener('change', async function (e) {
                const file = e.target.files[0];
                if (!file) return;

                const formData = new FormData();
                formData.append('image', file);

                const labelUpload = document.getElementById('label-subir-multimedia');
                labelUpload.textContent = 'Subiendo...';

                try {
                    const response = await fetch('/sisig/editor/modules/upload-media', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (response.ok && data.success && data.url) {
                        insertarImagenEnLienzo(data.url);
                        agregarItemGaleria({
                            url: data.url,
                            filename: data.filename || file.name,
                            title: file.name,
                            type: 'image',
                            id: data.filename || ('img_' + Date.now())
                        }, true);

                        Swal.fire({
                            toast: true, position: 'top-end', icon: 'success',
                            title: 'Imagen subida y guardada en galería', showConfirmButton: false, timer: 2000
                        });
                    } else {
                        Swal.fire('Error', data.message || 'No se pudo subir la imagen.', 'error');
                    }
                } catch (err) {
                    console.error('Error al subir imagen:', err);
                    Swal.fire('Error de conexión', 'Fallo al comunicarse con el servidor.', 'error');
                } finally {
                    labelUpload.textContent = 'Subir Imagen';
                    drawerFileInput.value = '';
                }
            });

            // Añadir enlace de video interactivo (YouTube, Vimeo, MP4)
            window.abrirModalNuevoVideo = async function() {
                const { value: formValues } = await Swal.fire({
                    title: '<div class="text-sm font-black text-slate-800 uppercase tracking-wide flex items-center justify-center gap-2"><i class="fab fa-youtube text-rose-600"></i> Añadir Video Interactivo</div>',
                    html: `
                        <div class="text-left space-y-3 pt-2 text-xs">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Enlace del Video (URL) <span class="text-rose-500">*</span></label>
                                <input type="url" id="swal-video-url" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:border-rose-500 text-xs text-slate-800" placeholder="https://www.youtube.com/watch?v=... o https://youtu.be/..." required>
                                <span class="text-[10px] text-slate-400 mt-0.5 block">Soporta enlaces de YouTube, Vimeo o URLs directas (.mp4).</span>
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Título o Tema del Video (Opcional)</label>
                                <input type="text" id="swal-video-title" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:border-rose-500 text-xs text-slate-800" placeholder="Ej: Inducción en Seguridad y Salud">
                            </div>
                        </div>
                    `,
                    focusConfirm: false,
                    showCancelButton: true,
                    confirmButtonText: '<i class="fas fa-plus-circle mr-1"></i> Guardar e Insertar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#e11d48',
                    cancelButtonColor: '#64748b',
                    preConfirm: () => {
                        const url = document.getElementById('swal-video-url').value.trim();
                        const title = document.getElementById('swal-video-title').value.trim();
                        if (!url) {
                            Swal.showValidationMessage('Por favor escribe o pega la URL del video');
                            return false;
                        }
                        return { url, title };
                    }
                });

                if (formValues) {
                    try {
                        Swal.fire({
                            title: 'Guardando video...',
                            didOpen: () => Swal.showLoading(),
                            allowOutsideClick: false
                        });

                        const response = await fetch('/sisig/editor/modules/media/video', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(formValues)
                        });

                        const result = await response.json();

                        if (response.ok && result.success && result.video) {
                            // Si se abrió desde un marco, colocar el video en ese marco
                            if (window._targetFrameForVideo && window._targetFrameForVideo.isFrame) {
                                colocarVideoEnMarco(window._targetFrameForVideo, result.video);
                                window._targetFrameForVideo = null;
                            } else {
                                const active = canvas.getActiveObject();
                                if (active && active.isFrame) {
                                    colocarVideoEnMarco(active, result.video);
                                } else {
                                    insertarVideoEnLienzo(result.video);
                                }
                            }
                            // Agregar a la galería
                            window._galeriaMultimedia = window._galeriaMultimedia || [];
                            window._galeriaMultimedia.unshift(result.video);
                            agregarItemGaleria(result.video, true);

                            Swal.fire({
                                toast: true, position: 'top-end', icon: 'success',
                                title: 'Video guardado e insertado en el lienzo', showConfirmButton: false, timer: 2500
                            });
                        } else {
                            throw new Error(result.message || 'No se pudo guardar el video');
                        }
                    } catch (e) {
                        console.error('Error al guardar video:', e);
                        Swal.fire('Error', e.message || 'Error al registrar el enlace de video.', 'error');
                    }
                }
            };

            // Renderizar un recurso (imagen o video) en la galería lateral
            function agregarItemGaleria(item, prepend = false) {
                // Limpiar mensaje vacío si existe
                if (uploadsGallery.querySelector('.col-span-2')) {
                    uploadsGallery.innerHTML = '';
                    uploadsCount = 0;
                }

                uploadsCount++;
                countUploads.textContent = uploadsCount + (uploadsCount === 1 ? ' archivo' : ' archivos');

                const isVideo = item.type === 'video';
                const card = document.createElement('div');
                card.className = 'group relative bg-white hover:bg-slate-50/90 rounded-2xl p-2 border border-slate-200 hover:border-[#2E6F40] hover:shadow-md cursor-pointer transition-all flex flex-col justify-between';
                card.setAttribute('draggable', 'true');

                if (isVideo) {
                    const thumbUrl = item.thumbnail || 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=400&auto=format&fit=crop&q=60';
                    card.innerHTML = `
                        <div class="relative aspect-video rounded-xl overflow-hidden bg-slate-900 border border-slate-200 shadow-2xs mb-1.5">
                            <img src="${thumbUrl}" alt="${item.title || 'Video'}" class="w-full h-full object-cover pointer-events-none opacity-90 group-hover:opacity-100 transition-opacity">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>
                            
                            <!-- Badges superiores -->
                            <div class="absolute top-1 left-1 right-1 flex items-center justify-between">
                                <span class="px-1.5 py-0.5 rounded-full text-[8px] font-black uppercase tracking-wider bg-rose-600 text-white flex items-center gap-1 shadow-sm">
                                    <i class="fab fa-youtube text-[9px]"></i> Video
                                </span>
                                <button type="button" class="btn-delete-media w-5 h-5 rounded-full bg-slate-900/80 hover:bg-rose-600 text-slate-300 hover:text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity text-[10px]" title="Eliminar video">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>

                            <!-- Botón central de play -->
                            <div class="absolute inset-0 flex items-center justify-center">
                                <button type="button" class="btn-preview-video-item w-7 h-7 rounded-full bg-rose-600 hover:bg-rose-500 text-white flex items-center justify-center text-xs shadow-md group-hover:scale-110 transition-transform" title="Ver video sin salir de la página">
                                    <i class="fas fa-play text-[9px] ml-0.5"></i>
                                </button>
                            </div>
                        </div>
                        <div class="px-0.5">
                            <h5 class="text-[10px] font-extrabold text-slate-800 truncate" title="${item.title || 'Video'}">${item.title || 'Video Interactivo'}</h5>
                            <span class="text-[9px] text-[#2E6F40] font-bold block mt-0.5 flex items-center gap-1">
                                <i class="fas fa-plus-circle text-[8px]"></i> Clic o arrastrar
                            </span>
                        </div>
                    `;
                } else {
                    card.innerHTML = `
                        <div class="relative aspect-video rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shadow-2xs mb-1.5">
                            <img src="${item.url}" alt="${item.filename || item.title}" class="w-full h-full object-cover pointer-events-none group-hover:scale-105 transition-transform duration-300">
                            
                            <!-- Badges superiores -->
                            <div class="absolute top-1 left-1 right-1 flex items-center justify-between">
                                <span class="px-1.5 py-0.5 rounded-full text-[8px] font-black uppercase tracking-wider bg-white/95 text-[#2E6F40] border border-emerald-200 shadow-2xs">
                                    <i class="fas fa-image text-[8px]"></i> Imagen
                                </span>
                                <button type="button" class="btn-delete-media w-5 h-5 rounded-full bg-slate-900/80 hover:bg-rose-600 text-slate-300 hover:text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity text-[10px]" title="Eliminar imagen">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>

                            <!-- Overlay de arrastre -->
                            <div class="absolute inset-0 bg-slate-900/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-[10px] font-bold">
                                <i class="fas fa-hand-rock mr-1"></i> Arrastrar
                            </div>
                        </div>
                        <div class="px-0.5">
                            <h5 class="text-[10px] font-extrabold text-slate-800 truncate" title="${item.filename || 'Imagen'}">${item.filename || 'Imagen'}</h5>
                            <span class="text-[9px] text-slate-400 font-semibold block mt-0.5">Recurso institucional</span>
                        </div>
                    `;
                }

                // Drag start
                card.addEventListener('dragstart', function (e) {
                    e.dataTransfer.setData('text/plain', item.url);
                    e.dataTransfer.setData('application/json', JSON.stringify(item));
                    e.dataTransfer.effectAllowed = 'copy';
                });

                // Clic en el botón eliminar
                const btnDelete = card.querySelector('.btn-delete-media');
                if (btnDelete) {
                    btnDelete.addEventListener('click', function(e) {
                        e.stopPropagation();
                        eliminarRecursoMultimedia(item, card);
                    });
                }

                // Clic en el botón reproducir directo (en videos)
                const btnPreviewPlay = card.querySelector('.btn-preview-video-item');
                if (btnPreviewPlay) {
                    btnPreviewPlay.addEventListener('click', function(e) {
                        e.stopPropagation();
                        reproducirVideoEnModal(item.embed_url || item.url, item.title, item.provider);
                    });
                }

                // Clic general en la tarjeta (Si hay un marco seleccionado, coloca el video o foto directamente)
                card.onclick = () => {
                    const active = (canvas.getActiveObject() && canvas.getActiveObject().isFrame) 
                        ? canvas.getActiveObject() 
                        : (window._lastSelectedFrame && canvas.getObjects().includes(window._lastSelectedFrame) ? window._lastSelectedFrame : null);

                    if (active && active.isFrame) {
                        if (isVideo) {
                            colocarVideoEnMarco(active, item);
                        } else {
                            colocarImagenEnMarco(active, item.url);
                        }
                    } else {
                        if (isVideo) {
                            insertarVideoEnLienzo(item);
                        } else {
                            insertarImagenEnLienzo(item.url);
                        }
                    }
                };

                if (prepend) {
                    uploadsGallery.prepend(card);
                } else {
                    uploadsGallery.appendChild(card);
                }
            }

            // Eliminar recurso multimedia
            async function eliminarRecursoMultimedia(item, cardEl) {
                const confirmed = await Swal.fire({
                    title: '¿Eliminar recurso?',
                    text: `Se eliminará "${item.title || item.filename}" de tu biblioteca multimedia.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#e11d48',
                    cancelButtonColor: '#64748b'
                });

                if (confirmed.isConfirmed) {
                    try {
                        const response = await fetch('/sisig/editor/modules/media', {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                type: item.type,
                                id: item.id,
                                filename: item.filename
                            })
                        });

                        const res = await response.json();
                        if (response.ok && res.success) {
                            cardEl.remove();
                            uploadsCount = Math.max(0, uploadsCount - 1);
                            countUploads.textContent = uploadsCount + (uploadsCount === 1 ? ' archivo' : ' archivos');

                            if (uploadsCount === 0) {
                                uploadsGallery.innerHTML = `
                                    <div class="col-span-2 py-8 text-center text-slate-400">
                                        <i class="fas fa-photo-video text-2xl text-slate-300 mb-2 block"></i>
                                        <p class="text-[11px] font-medium">Aún no hay archivos multimedia.</p>
                                    </div>
                                `;
                            }

                            Swal.fire({
                                toast: true, position: 'top-end', icon: 'success',
                                title: 'Recurso eliminado de la galería', showConfirmButton: false, timer: 2000
                            });
                        }
                    } catch (err) {
                        console.error('Error al eliminar recurso:', err);
                    }
                }
            }

            // Insertar imagen simple en lienzo
            function insertarImagenEnLienzo(url, x = null, y = null) {
                fabric.Image.fromURL(url, function (img) {
                    if (!img) return;
                    const maxW = canvas.width * 0.65;
                    const maxH = canvas.height * 0.65;
                    if (img.width > maxW || img.height > maxH) {
                        const scale = Math.min(maxW / img.width, maxH / img.height);
                        img.scale(scale);
                    }
                    img.set({
                        left: x ?? (canvas.width / 2),
                        top: y ?? (canvas.height / 2),
                        originX: 'center',
                        originY: 'center',
                        cornerColor: '#2E6F40',
                        selectable: true,
                        evented: true,
                        hasControls: true,
                        hasBorders: true,
                        lockMovementX: false,
                        lockMovementY: false
                    });
                    canvas.add(img);
                    canvas.bringToFront(img);
                    img.setCoords();
                    canvas.setActiveObject(img);
                    canvas.renderAll();
                    updateObjectsCount();
                    recordState();
                }, { crossOrigin: 'anonymous' });
            }

            // Insertar Tarjeta de Video Interactivo en Lienzo (Ventana In-Page, fondo adaptable sin bordes toscos)
            window.insertarVideoEnLienzo = function(videoData, x = null, y = null) {
                const cardW = 420;
                const cardH = 236; // Proporción 16:9 nítida y exacta (420 x 236)
                const posX = x ?? (canvas.width > 700 ? 705 : (canvas.width / 2));
                const posY = y ?? (canvas.height > 600 ? 195 : (canvas.height / 2));

                let thumbUrl = videoData.thumbnail || '';
                // Limpieza de miniaturas YouTube 4:3 para usar la versión 16:9 limpia (sin bandas negras)
                if (thumbUrl && thumbUrl.includes('hqdefault.jpg')) {
                    thumbUrl = thumbUrl.replace('hqdefault.jpg', 'mqdefault.jpg');
                }
                if (!thumbUrl && videoData.url) {
                    const ytMatch = videoData.url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/|youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/i);
                    if (ytMatch) {
                        thumbUrl = `https://img.youtube.com/vi/${ytMatch[1]}/mqdefault.jpg`;
                    }
                }

                function armarGrupoVideo(bgElement) {
                    const currentBgColor = canvas.backgroundColor || '#0f172a';
                    const bgCard = new fabric.Rect({
                        width: cardW,
                        height: cardH,
                        fill: currentBgColor,
                        rx: 16,
                        ry: 16,
                        stroke: '#334155',
                        strokeWidth: 1,
                        originX: 'center',
                        originY: 'center'
                    });
                    bgCard.isAdaptiveVideoBg = true;

                    // Velo translúcido sobre el video para garantizar contraste
                    const veil = new fabric.Rect({
                        width: cardW,
                        height: cardH,
                        fill: 'rgba(15, 23, 42, 0.22)',
                        originX: 'center',
                        originY: 'center'
                    });

                    // Barra inferior integrada en la tarjeta (alineada exactamente al fondo)
                    const barBottomH = 54;
                    const barBottom = new fabric.Rect({
                        width: cardW,
                        height: barBottomH,
                        fill: 'rgba(10, 15, 30, 0.90)',
                        originX: 'center',
                        originY: 'bottom',
                        top: (cardH / 2)
                    });

                    // Botón circular de reproducción
                    const playCircle = new fabric.Circle({
                        radius: 26,
                        fill: '#e11d48',
                        stroke: '#ffffff',
                        strokeWidth: 2.5,
                        originX: 'center',
                        originY: 'center',
                        top: -14,
                        shadow: new fabric.Shadow({
                            color: 'rgba(225, 29, 72, 0.6)',
                            blur: 16,
                            offsetX: 0,
                            offsetY: 0
                        })
                    });

                    // Triángulo blanco centrado
                    const playTriangle = new fabric.Triangle({
                        width: 15,
                        height: 17,
                        fill: '#ffffff',
                        angle: 90,
                        originX: 'center',
                        originY: 'center',
                        left: 2,
                        top: -14
                    });

                    const titleShort = (videoData.title || 'Video Interactivo SISIG').length > 38 
                        ? (videoData.title.substring(0, 35) + '...') 
                        : (videoData.title || 'Video Interactivo SISIG');

                    const textTitle = new fabric.Text(titleShort, {
                        fontSize: 11.5,
                        fontWeight: '700',
                        fontFamily: 'Montserrat',
                        fill: '#ffffff',
                        originX: 'center',
                        originY: 'center',
                        top: (cardH / 2) - 34
                    });

                    const providerTag = (videoData.provider || videoData.videoType || 'youtube').toUpperCase();
                    const textBadge = new fabric.Text(`▶ REPRODUCIR EN VENTANA (${providerTag})`, {
                        fontSize: 9,
                        fontWeight: '800',
                        fontFamily: 'Inter',
                        fill: '#33FF57',
                        originX: 'center',
                        originY: 'center',
                        top: (cardH / 2) - 16
                    });

                    const groupItems = [bgCard];
                    if (bgElement) groupItems.push(bgElement, veil);
                    groupItems.push(barBottom, textTitle, textBadge, playCircle, playTriangle);

                    groupItems.forEach(item => {
                        item._parentVideoCard = true;
                    });

                    const videoGroup = new fabric.Group(groupItems, {
                        left: posX,
                        top: posY,
                        width: cardW,
                        height: cardH,
                        originX: 'center',
                        originY: 'center',
                        cornerColor: '#e11d48',
                        borderColor: '#e11d48',
                        hasRotatingPoint: false,
                        subTargetCheck: true,
                        clipPath: new fabric.Rect({
                            width: cardW,
                            height: cardH,
                            rx: 16,
                            ry: 16,
                            originX: 'center',
                            originY: 'center'
                        }),
                        shadow: new fabric.Shadow({
                            color: 'rgba(0, 0, 0, 0.45)',
                            blur: 22,
                            offsetX: 0,
                            offsetY: 8
                        })
                    });

                    videoGroup.isVideoCard = true;
                    videoGroup.videoUrl = videoData.url;
                    videoGroup.embedUrl = videoData.embed_url || videoData.url;
                    videoGroup.videoTitle = videoData.title || 'Video Interactivo';
                    videoGroup.videoType = videoData.provider || videoData.videoType || 'youtube';
                    videoGroup.thumbnail = thumbUrl;

                    canvas.add(videoGroup);
                    canvas.setActiveObject(videoGroup);
                    canvas.renderAll();
                    recordState();
                }

                // Cargar imagen de miniatura si existe
                if (thumbUrl) {
                    fabric.Image.fromURL(thumbUrl, function(img) {
                        if (img) {
                            let scale = Math.max(cardW / img.width, cardH / img.height) * 1.05;
                            if ((img.width / img.height) < 1.65) {
                                scale *= 1.34;
                            }
                            img.scale(scale);
                            img.set({
                                originX: 'center',
                                originY: 'center',
                                left: 0,
                                top: 0
                            });
                            armarGrupoVideo(img);
                        } else {
                            armarGrupoVideo(null);
                        }
                    }, { crossOrigin: 'anonymous' });
                } else {
                    armarGrupoVideo(null);
                }
            };

            // Doble clic en el lienzo: si es una tarjeta de video o un marco con video, abre el reproductor in-page
            canvas.on('mouse:dblclick', function(e) {
                if (e.target) {
                    const card = e.target.isVideoCard ? e.target : (e.target.group?.isVideoCard ? e.target.group : null);
                    if (card && card.isVideoCard) {
                        reproducirVideoEnModal(card.embedUrl || card.videoUrl, card.videoTitle, card.videoType);
                        return;
                    }
                    if (e.target.isFrame && e.target.hasVideo) {
                        reproducirVideoEnModal(e.target.embedUrl || e.target.videoUrl, e.target.videoTitle, e.target.videoType);
                        return;
                    }
                }
            });

            // Sincronizar el fondo de todos los videos para que siempre coincidan exactamente con el fondo del lienzo
            window.sincronizarFondoVideos = function(nuevoColor) {
                const targetColor = nuevoColor || canvas.backgroundColor || '#ffffff';
                canvas.getObjects().forEach(function(obj) {
                    if (obj.isVideoCard && obj._objects) {
                        obj._objects.forEach(function(child) {
                            if (child.isAdaptiveVideoBg) {
                                child.set('fill', targetColor);
                            }
                        });
                    }
                    if (obj.isFrame) {
                        obj.canvasBgColor = targetColor;
                    }
                });
                canvas.renderAll();
            };

            // Reproducción de video seleccionado desde la barra contextual
            window.reproducirVideoSeleccionado = function() {
                const active = canvas.getActiveObject();
                if (active) {
                    const card = active.isVideoCard ? active : (active.group?.isVideoCard ? active.group : null);
                    if (card && card.isVideoCard) {
                        reproducirVideoEnModal(card.embedUrl || card.videoUrl, card.videoTitle, card.videoType);
                    }
                }
            };

            // =============================================================
            // REPRODUCTOR DE VIDEO EN VENTANA MODAL (Sin salirse de la página)
            // =============================================================
            window.reproducirVideoEnModal = function(embedUrl, title, type = 'youtube') {
                const modal = document.getElementById('modal-video-player');
                const titleEl = document.getElementById('modal-video-title');
                const iconEl = document.getElementById('modal-video-icon');
                const iframe = document.getElementById('video-iframe-player');
                const videoHtml5 = document.getElementById('video-html5-player');

                if (!modal) return;

                titleEl.textContent = title || 'Reproduciendo Video Interactivo';

                if (type === 'vimeo') {
                    iconEl.className = 'fab fa-vimeo text-sky-400 text-base flex-shrink-0';
                } else if (type === 'mp4') {
                    iconEl.className = 'fas fa-file-video text-emerald-400 text-base flex-shrink-0';
                } else {
                    iconEl.className = 'fab fa-youtube text-rose-500 text-base flex-shrink-0';
                }

                if (type === 'mp4') {
                    iframe.style.display = 'none';
                    iframe.src = '';
                    videoHtml5.style.display = 'block';
                    videoHtml5.src = embedUrl;
                    videoHtml5.play();
                } else {
                    videoHtml5.pause();
                    videoHtml5.src = '';
                    videoHtml5.style.display = 'none';
                    iframe.style.display = 'block';
                    iframe.src = embedUrl;
                }

                modal.style.display = 'flex';
            };

            window.cerrarModalVideo = function() {
                const modal = document.getElementById('modal-video-player');
                const iframe = document.getElementById('video-iframe-player');
                const videoHtml5 = document.getElementById('video-html5-player');

                if (iframe) iframe.src = '';
                if (videoHtml5) {
                    videoHtml5.pause();
                    videoHtml5.src = '';
                }
                if (modal) modal.style.display = 'none';
            };

            // Cerrar modal al presionar tecla Escape
            window.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    cerrarModalVideo();
                    cerrarModalVisualizacion();
                }
            });

            // =============================================================
            // MODAL DE VISUALIZACIÓN INTERACTIVA DEL MÓDULO (Vista Previa)
            // =============================================================
            window.abrirModalVisualizacion = function() {
                const modal = document.getElementById('modal-preview-module');
                const previewSvgWrapper = document.getElementById('preview-svg-wrapper');
                const previewStage = document.getElementById('preview-stage-container');
                if (!modal || !previewSvgWrapper) return;

                if (previewStage) {
                    previewStage.style.width = (canvas.width || 960) + 'px';
                    previewStage.style.height = (canvas.height || 960) + 'px';
                }

                // Deseleccionar cualquier objeto antes de exportar
                canvas.discardActiveObject();
                canvas.renderAll();

                // Exportar SVG del lienzo actual
                const svgString = canvas.toSVG();
                previewSvgWrapper.innerHTML = svgString;

                // Ajustar SVG para que ocupe el 100%
                const svgEl = previewSvgWrapper.querySelector('svg');
                if (svgEl) {
                    svgEl.style.width = '100%';
                    svgEl.style.height = '100%';
                    svgEl.style.display = 'block';
                    svgEl.style.cursor = 'default';
                }

                // Si hay objetos de video o marcos con video en el canvas, añadir overlay de clics interactivos
                const videoObjects = canvas.getObjects().filter(o => o.isVideoCard || (o.isFrame && o.hasVideo));
                videoObjects.forEach(vObj => {
                    const bound = vObj.getBoundingRect();
                    const clickArea = document.createElement('div');
                    clickArea.className = 'absolute cursor-pointer hover:ring-4 hover:ring-rose-500/80 rounded-xl transition-all';
                    clickArea.style.left = bound.left + 'px';
                    clickArea.style.top = bound.top + 'px';
                    clickArea.style.width = bound.width + 'px';
                    clickArea.style.height = bound.height + 'px';
                    clickArea.title = 'Haz clic para reproducir: ' + (vObj.videoTitle || 'Video');
                    clickArea.onclick = () => {
                        reproducirVideoEnModal(vObj.embedUrl || vObj.videoUrl, vObj.videoTitle, vObj.videoType);
                    };
                    previewSvgWrapper.appendChild(clickArea);
                });

                modal.style.display = 'flex';
            };

            window.cerrarModalVisualizacion = function() {
                const modal = document.getElementById('modal-preview-module');
                const previewSvgWrapper = document.getElementById('preview-svg-wrapper');
                if (modal) modal.style.display = 'none';
                if (previewSvgWrapper) previewSvgWrapper.innerHTML = '';
            };

            // Botón Visualizar de la barra superior
            const btnPreviewTop = document.getElementById('btn-preview-canvas');
            if (btnPreviewTop) {
                btnPreviewTop.addEventListener('click', abrirModalVisualizacion);
            }

            // -------------------------------------------------------------
            // EFECTO MAGNÉTICO ESTILO CANVA (SNAPPING AUTOMÁTICO A MARCOS)
            // -------------------------------------------------------------
            let activeHoveredFrame = null;

            function activarBrilloMarco(frame) {
                if (!frame || frame._isHoverGlow) return;
                frame._isHoverGlow = true;
                frame._origShadow = frame.shadow;

                // Halo magnético brillante estilo Canva (verde esmeralda institucional radiante)
                frame.set('shadow', new fabric.Shadow({
                    color: '#10b981',
                    blur: 28,
                    offsetX: 0,
                    offsetY: 0
                }));

                // Resaltar el contorno del marco y el ícono si es un grupo
                if (frame.forEachObject) {
                    frame.forEachObject(function(o) {
                        if (o.isFrameBase) {
                            o._origStroke = o.stroke;
                            o._origStrokeWidth = o.strokeWidth;
                            o.set({
                                stroke: '#10b981',
                                strokeWidth: Math.max((o.strokeWidth || 2), 3.5),
                                strokeDashArray: [4, 2]
                            });
                        }
                        if (o.isFramePlaceholder) {
                            o.set({ fill: '#059669' });
                        }
                    });
                }

                canvas.requestRenderAll();
            }

            function desactivarBrilloMarco(frame) {
                if (!frame || !frame._isHoverGlow) return;
                frame._isHoverGlow = false;

                frame.set('shadow', frame._origShadow || null);

                if (frame.forEachObject) {
                    frame.forEachObject(function(o) {
                        if (o.isFrameBase && typeof o._origStroke !== 'undefined') {
                            o.set({
                                stroke: o._origStroke,
                                strokeWidth: o._origStrokeWidth,
                                strokeDashArray: [6, 4]
                            });
                            delete o._origStroke;
                            delete o._origStrokeWidth;
                        }
                        if (o.isFramePlaceholder) {
                            o.set({ fill: '#64748b' });
                        }
                    });
                }

                canvas.requestRenderAll();
            }

            // Calcula con precisión matemática el punto sobre el lienzo compensando zoom y escala CSS
            function getCanvasPointer(e) {
                const rect = canvas.upperCanvasEl.getBoundingClientRect();
                const scaleX = canvas.width / rect.width;
                const scaleY = canvas.height / rect.height;
                return new fabric.Point((e.clientX - rect.left) * scaleX, (e.clientY - rect.top) * scaleY);
            }

            // Encuentra el marco bajo un punto con detección geométrica y de proximidad
            function buscarMarcoBajoPunto(point, excludeObj = null) {
                const frames = canvas.getObjects().filter(o => o.isFrame && o !== excludeObj);
                for (let i = frames.length - 1; i >= 0; i--) {
                    const f = frames[i];
                    f.setCoords();
                    if (f.containsPoint && f.containsPoint(point)) {
                        return f;
                    }
                    if (excludeObj && f.intersectsWithObject && f.intersectsWithObject(excludeObj)) {
                        return f;
                    }
                    const fCenter = f.getCenterPoint();
                    const dist = Math.hypot(point.x - fCenter.x, point.y - fCenter.y);
                    const maxDist = Math.max((f.width || 200) * (f.scaleX || 1), (f.height || 200) * (f.scaleY || 1)) * 0.65;
                    if (dist < maxDist) {
                        return f;
                    }
                }
                return null;
            }

            // Inserta imagen o video dentro del marco sobre el cual fue soltado
            function encajarElementoEnMarcoHovered(target) {
                if (!target) return false;
                let realTarget = target;
                if (realTarget.group && (realTarget.group.isVideoCard || realTarget.group._parentVideoCard)) {
                    realTarget = realTarget.group;
                }
                let frameToFill = activeHoveredFrame;
                if (!frameToFill && typeof realTarget.getCenterPoint === 'function') {
                    frameToFill = buscarMarcoBajoPunto(realTarget.getCenterPoint(), realTarget);
                }
                if (!frameToFill || !realTarget) return false;

                desactivarBrilloMarco(frameToFill);
                activeHoveredFrame = null;

                const isVideo = realTarget.isVideoCard || realTarget._parentVideoCard;
                if (isVideo) {
                    const videoCard = realTarget.isVideoCard ? realTarget : (realTarget.group?.isVideoCard ? realTarget.group : realTarget);
                    const videoData = {
                        url: videoCard.videoUrl,
                        embed_url: videoCard.embedUrl || videoCard.videoUrl,
                        title: videoCard.videoTitle || 'Video',
                        provider: videoCard.videoType || 'youtube',
                        thumbnail: videoCard.thumbnail || ''
                    };
                    canvas.remove(videoCard);
                    colocarVideoEnMarco(frameToFill, videoData);
                    return true;
                } else if (realTarget.type === 'image' && !realTarget.isFrame) {
                    const imgSrc = realTarget.getSrc ? realTarget.getSrc() : (realTarget._element ? realTarget._element.src : null);
                    if (imgSrc) {
                        canvas.remove(realTarget);
                        colocarImagenEnMarco(frameToFill, imgSrc);
                        return true;
                    }
                }
                return false;
            }

            // Detector de proximidad magnética mientras se mueve una imagen o video en el lienzo
            canvas.on('object:moving', function(e) {
                try {
                    let target = e.target;
                    if (target && target.group && (target.group.isVideoCard || target.group._parentVideoCard)) {
                        target = target.group;
                    }
                    const isVideoCard = target && (target.isVideoCard || target._parentVideoCard);
                    const isImage = target && target.type === 'image' && !target.isFrame;
                    if (!target || (!isImage && !isVideoCard)) return;

                    const center = target.getCenterPoint();
                    const point = new fabric.Point(center.x, center.y);

                    const matchedFrame = buscarMarcoBajoPunto(point, target);

                    if (matchedFrame !== activeHoveredFrame) {
                        if (activeHoveredFrame) desactivarBrilloMarco(activeHoveredFrame);
                        activeHoveredFrame = matchedFrame;
                        if (activeHoveredFrame) activarBrilloMarco(activeHoveredFrame);
                    }
                } catch (err) {
                    console.warn('Error en object:moving:', err);
                }
            });

            // Al soltar el mouse tras mover imagen o video en el lienzo
            canvas.on('object:modified', function(e) {
                try {
                    let target = e.target;
                    if (target && target.group && (target.group.isVideoCard || target.group._parentVideoCard)) {
                        target = target.group;
                    }
                    if (activeHoveredFrame) {
                        encajarElementoEnMarcoHovered(target);
                    }
                } catch (err) {
                    console.warn('Error en object:modified:', err);
                }
            });

            canvas.on('mouse:up', function(e) {
                try {
                    if (activeHoveredFrame) {
                        let activeObj = canvas.getActiveObject() || e.target;
                        if (activeObj && activeObj.group && (activeObj.group.isVideoCard || activeObj.group._parentVideoCard)) {
                            activeObj = activeObj.group;
                        }
                        if (activeObj && (activeObj.isVideoCard || activeObj._parentVideoCard || (activeObj.type === 'image' && !activeObj.isFrame))) {
                            encajarElementoEnMarcoHovered(activeObj);
                        }
                        desactivarBrilloMarco(activeHoveredFrame);
                        activeHoveredFrame = null;
                    }
                } catch (err) {
                    console.warn('Error en mouse:up:', err);
                }
            });

            // Detector de URLs de video
            function esUrlDeVideo(url) {
                if (!url || typeof url !== 'string') return false;
                return /(?:youtube\.com|youtu\.be|vimeo\.com|\.(?:mp4|webm|ogg)(?:$|\?))/i.test(url);
            }

            // Receptor de Drop y Dragover sobre el Lienzo (arrastre desde Subidas o Escritorio)
            const upperEl = canvas.upperCanvasEl;
            upperEl.addEventListener('dragover', function (e) {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'copy';

                const point = getCanvasPointer(e);
                const matched = buscarMarcoBajoPunto(point, null);

                if (matched !== activeHoveredFrame) {
                    if (activeHoveredFrame) desactivarBrilloMarco(activeHoveredFrame);
                    activeHoveredFrame = matched;
                    if (activeHoveredFrame) activarBrilloMarco(activeHoveredFrame);
                }
            });

            upperEl.addEventListener('dragleave', function () {
                if (activeHoveredFrame) {
                    desactivarBrilloMarco(activeHoveredFrame);
                    activeHoveredFrame = null;
                }
            });

            upperEl.addEventListener('drop', function (e) {
                e.preventDefault();
                let itemData = null;
                try {
                    const json = e.dataTransfer.getData('application/json');
                    if (json) itemData = JSON.parse(json);
                } catch (err) {}

                const url = e.dataTransfer.getData('text/plain');
                const point = getCanvasPointer(e);

                const target = activeHoveredFrame || buscarMarcoBajoPunto(point, null);
                if (target) {
                    desactivarBrilloMarco(target);
                    activeHoveredFrame = null;

                    if (itemData && itemData.type === 'video') {
                        colocarVideoEnMarco(target, itemData);
                        return;
                    }
                    if (url) {
                        if (esUrlDeVideo(url)) {
                            colocarVideoEnMarco(target, { url: url, title: 'Video', provider: 'youtube' });
                        } else {
                            colocarImagenEnMarco(target, url);
                        }
                        return;
                    }
                }

                if (itemData && itemData.type === 'video') {
                    insertarVideoEnLienzo(itemData, point.x, point.y);
                } else if (url) {
                    if (esUrlDeVideo(url)) {
                        insertarVideoEnLienzo({ url: url, title: 'Video' }, point.x, point.y);
                    } else {
                        insertarImagenEnLienzo(url, point.x, point.y);
                    }
                }
            });

            // -------------------------------------------------------------
            // 10. EDICIÓN DE IMAGEN INTEGRADA (FILTROS Y RECORTE)
            // -------------------------------------------------------------
            window.abrirAjustesImagenModal = function() {
                const active = canvas.getActiveObject();
                if (!active || (active.type !== 'image' && !active.isFrame)) return;
                document.getElementById('modal-image-filters').style.display = 'flex';
            };

            window.cerrarAjustesImagenModal = function() {
                document.getElementById('modal-image-filters').style.display = 'none';
            };

            function aplicarFiltrosImagen() {
                let obj = canvas.getActiveObject();
                if (!obj) return;

                if (obj.isFrame && obj.hasImage && obj._objects) {
                    obj = obj._objects.find(o => o.type === 'image') || obj;
                }
                if (obj.type !== 'image') return;

                const b = parseFloat(document.getElementById('filter-brightness').value);
                const c = parseFloat(document.getElementById('filter-contrast').value);
                const s = parseFloat(document.getElementById('filter-saturation').value);

                document.getElementById('label-filter-brightness').textContent = Math.round(b * 100) + '%';
                document.getElementById('label-filter-contrast').textContent = Math.round(c * 100) + '%';
                document.getElementById('label-filter-saturation').textContent = Math.round(s * 100) + '%';

                obj.filters = [
                    new fabric.Image.filters.Brightness({ brightness: b }),
                    new fabric.Image.filters.Contrast({ contrast: c }),
                    new fabric.Image.filters.Saturation({ saturation: s })
                ];
                obj.applyFilters();
                canvas.renderAll();
                recordState();
            }

            document.getElementById('filter-brightness').addEventListener('input', aplicarFiltrosImagen);
            document.getElementById('filter-contrast').addEventListener('input', aplicarFiltrosImagen);
            document.getElementById('filter-saturation').addEventListener('input', aplicarFiltrosImagen);

            window.aplicarFiltroRapido = function(tipo) {
                let obj = canvas.getActiveObject();
                if (!obj) return;

                if (obj.isFrame && obj.hasImage && obj._objects) {
                    obj = obj._objects.find(o => o.type === 'image') || obj;
                }
                if (obj.type !== 'image') return;

                if (tipo === 'normal') {
                    obj.filters = [];
                    document.getElementById('filter-brightness').value = 0;
                    document.getElementById('filter-contrast').value = 0;
                    document.getElementById('filter-saturation').value = 0;
                } else if (tipo === 'bn') {
                    obj.filters = [new fabric.Image.filters.Grayscale()];
                } else if (tipo === 'sepia') {
                    obj.filters = [new fabric.Image.filters.Sepia()];
                }

                obj.applyFilters();
                canvas.renderAll();
                recordState();
            };

            // Herramienta de Recorte
            window.activarRecorteImagen = function() {
                const obj = canvas.getActiveObject();
                if (!obj || obj.type !== 'image') return;

                Swal.fire({
                    title: 'Herramienta de Recorte',
                    text: 'Ajusta los bordes para recortar la imagen en el lienzo.',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#2E6F40',
                    confirmButtonText: 'Aplicar recorte 50%',
                    cancelButtonText: 'Cerrar'
                }).then((res) => {
                    if (res.isConfirmed) {
                        obj.set({
                            cropX: obj.width * 0.15,
                            cropY: obj.height * 0.15,
                            width: obj.width * 0.7,
                            height: obj.height * 0.7
                        });
                        canvas.renderAll();
                        recordState();
                    }
                });
            };

            // -------------------------------------------------------------
            // 11. GESTIÓN DEL ESPACIO: CAPAS, GRUPOS, ALINEACIÓN Y CANDADO
            // -------------------------------------------------------------
            // Opacidad
            document.getElementById('obj-opacity').addEventListener('input', function () {
                const obj = canvas.getActiveObject();
                if (obj) { obj.set('opacity', (parseInt(this.value) || 100) / 100); canvas.renderAll(); recordState(); }
            });

            // Z-Index
            document.getElementById('btn-bring-front').addEventListener('click', function () {
                const obj = canvas.getActiveObject();
                if (obj) { canvas.bringToFront(obj); canvas.renderAll(); recordState(); }
            });

            document.getElementById('btn-send-back').addEventListener('click', function () {
                const obj = canvas.getActiveObject();
                if (obj) { canvas.sendToBack(obj); canvas.renderAll(); recordState(); }
            });

            // Agrupar / Desagrupar
            document.getElementById('btn-group-toggle').addEventListener('click', function () {
                const active = canvas.getActiveObject();
                if (!active) return;

                if (active.type === 'activeSelection') {
                    active.toGroup();
                    canvas.requestRenderAll();
                    recordState();
                } else if (active.type === 'group') {
                    active.toActiveSelection();
                    canvas.requestRenderAll();
                    recordState();
                }
            });

            // Bloqueo Preventivo (Candado)
            document.getElementById('btn-lock-toggle').addEventListener('click', function () {
                const active = canvas.getActiveObject();
                if (!active) return;

                const willLock = active.lockMovementX !== true;
                active.set({
                    lockMovementX: willLock,
                    lockMovementY: willLock,
                    lockRotation: willLock,
                    lockScalingX: willLock,
                    lockScalingY: willLock,
                    hasControls: !willLock
                });
                lockIcon.className = willLock ? 'fas fa-lock text-rose-500' : 'fas fa-unlock text-slate-600';
                canvas.renderAll();
                recordState();

                Swal.fire({
                    toast: true, position: 'top-end',
                    icon: willLock ? 'warning' : 'info',
                    title: willLock ? 'Elemento bloqueado' : 'Elemento desbloqueado',
                    showConfirmButton: false, timer: 1500
                });
            });

            // Alineación Relativa
            window.centrarObjeto = function(eje) {
                const obj = canvas.getActiveObject();
                if (!obj) return;
                if (eje === 'H') obj.centerH();
                if (eje === 'V') obj.centerV();
                obj.setCoords();
                canvas.renderAll();
                recordState();
            };

            // Duplicar y Eliminar
            document.getElementById('btn-duplicate-obj').addEventListener('click', duplicarObjetoActivo);
            document.getElementById('btn-delete-obj').addEventListener('click', eliminarObjetoActivo);

            function duplicarObjetoActivo() {
                const obj = canvas.getActiveObject();
                if (!obj) return;

                obj.clone(function (cloned) {
                    cloned.set({
                        left: obj.left + 25,
                        top: obj.top + 25,
                        evented: true,
                    });
                    canvas.add(cloned);
                    canvas.setActiveObject(cloned);
                    canvas.renderAll();
                    recordState();
                });
            }

            function eliminarObjetoActivo() {
                const actives = canvas.getActiveObjects();
                if (actives && actives.length > 0) {
                    actives.forEach(o => canvas.remove(o));
                    canvas.discardActiveObject();
                    canvas.renderAll();
                    recordState();
                }
            }

            // -------------------------------------------------------------
            // 12. ATAJOS DE TECLADO GLOBALES
            // -------------------------------------------------------------
            window.addEventListener('keydown', function (e) {
                const active = canvas.getActiveObject();
                if (active && active.isEditing) return;

                if (e.key === 'Delete' || e.key === 'Backspace') {
                    eliminarObjetoActivo();
                }
                if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'z' && !e.shiftKey) {
                    e.preventDefault(); window.undo();
                }
                if ((e.ctrlKey || e.metaKey) && (e.key.toLowerCase() === 'y' || (e.shiftKey && e.key.toLowerCase() === 'z'))) {
                    e.preventDefault(); window.redo();
                }
                if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'd') {
                    e.preventDefault(); duplicarObjetoActivo();
                }
                if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 's') {
                    e.preventDefault(); guardarLienzoEnBackend();
                }
                if (['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight'].includes(e.key) && active && !active.lockMovementX) {
                    e.preventDefault();
                    const step = e.shiftKey ? 10 : 1;
                    if (e.key === 'ArrowUp') active.top -= step;
                    if (e.key === 'ArrowDown') active.top += step;
                    if (e.key === 'ArrowLeft') active.left -= step;
                    if (e.key === 'ArrowRight') active.left += step;
                    active.setCoords();
                    canvas.renderAll();
                    recordState();
                }
            });

            // -------------------------------------------------------------
            // 14. PLANTILLAS SISIG
            // -------------------------------------------------------------
            window.filtrarPlantillas = function(cat, btn) {
                document.querySelectorAll('.btn-filter-tpl').forEach(b => {
                    b.classList.remove('bg-sena-green', 'text-white');
                    b.classList.add('bg-slate-100', 'text-slate-600');
                });
                if (btn) {
                    btn.classList.remove('bg-slate-100', 'text-slate-600');
                    btn.classList.add('bg-sena-green', 'text-white');
                }

                const cards = document.querySelectorAll('#lista-plantillas .tpl-card');
                cards.forEach(card => {
                    if (cat === 'todas' || card.getAttribute('data-cat') === cat) {
                        card.classList.remove('hidden');
                    } else {
                        card.classList.add('hidden');
                    }
                });
            };

            window.aplicarPlantilla = function(tipo) {
                // Si el lienzo está en blanco, aplicar de inmediato sin confirmación
                if (canvas.getObjects().length === 0) {
                    construirElementosPlantilla(tipo);
                    return;
                }

                Swal.fire({
                    title: '¿Cargar plantilla?',
                    text: 'Se reemplazará el contenido actual por la plantilla seleccionada.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#2E6F40',
                    confirmButtonText: 'Sí, aplicar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        construirElementosPlantilla(tipo);
                    }
                });
            };

            function construirElementosPlantilla(tipo) {
                canvas.clear();

                if (tipo === 'portada') {
                    canvas.setBackgroundColor('#0f172a', canvas.renderAll.bind(canvas));
                    const currentH = canvas.height || 960;
                    const bar = new fabric.Rect({ left: 0, top: 0, width: 14, height: currentH, fill: '#33FF57', selectable: false });
                    const badgeBox = new fabric.Rect({ left: 60, top: 60, width: 330, height: 30, rx: 6, ry: 6, fill: 'rgba(51, 255, 87, 0.12)', stroke: '#33FF57', strokeWidth: 1.5 });
                    const badgeTxt = new fabric.IText('SISTEMA INTEGRADO DE GESTIÓN • SISIG', { left: 75, top: 68, fontSize: 11, fontWeight: '800', fill: '#33FF57', fontFamily: 'Montserrat' });
                    const title = new fabric.IText('INDUCCIÓN\nINSTITUCIONAL', { left: 60, top: 105, fontSize: 50, fontWeight: '900', fill: '#ffffff', lineHeight: 1.1, fontFamily: 'Montserrat' });
                    const line = new fabric.Rect({ left: 60, top: 225, width: 100, height: 5, fill: '#33FF57', rx: 2.5, ry: 2.5 });
                    const sub = new fabric.IText('Calidad, Seguridad y Salud en el Trabajo y Gestión Ambiental', { left: 60, top: 248, fontSize: 17, fontWeight: '500', fill: '#94a3b8', width: 440 });

                    const tag1Box = new fabric.Rect({ left: 60, top: 310, width: 115, height: 32, rx: 8, ry: 8, fill: '#1e293b', stroke: '#38bdf8', strokeWidth: 1 });
                    const tag1Txt = new fabric.IText('ISO 9001:2015', { left: 75, top: 319, fontSize: 11, fontWeight: '800', fill: '#38bdf8' });
                    const tag2Box = new fabric.Rect({ left: 190, top: 310, width: 120, height: 32, rx: 8, ry: 8, fill: '#1e293b', stroke: '#4ade80', strokeWidth: 1 });
                    const tag2Txt = new fabric.IText('ISO 14001:2015', { left: 203, top: 319, fontSize: 11, fontWeight: '800', fill: '#4ade80' });
                    const tag3Box = new fabric.Rect({ left: 325, top: 310, width: 120, height: 32, rx: 8, ry: 8, fill: '#1e293b', stroke: '#fbbf24', strokeWidth: 1 });
                    const tag3Txt = new fabric.IText('ISO 45001:2018', { left: 338, top: 319, fontSize: 11, fontWeight: '800', fill: '#fbbf24' });

                    const footerY = currentH - 90;
                    const footerBox = new fabric.Rect({ left: 60, top: footerY, width: 840, height: 60, rx: 12, fill: 'rgba(255,255,255,0.04)', stroke: 'rgba(255,255,255,0.1)', strokeWidth: 1 });
                    const footerT1 = new fabric.IText('SERVICIO NACIONAL DE APRENDIZAJE - SENA • REGIONAL DISTRITO CAPITAL', { left: 85, top: footerY + 13, fontSize: 11, fontWeight: '800', fill: '#e2e8f0' });
                    const footerT2 = new fabric.IText('Módulo de Inducción • Documento Oficial de Consulta • Año 2026', { left: 85, top: footerY + 33, fontSize: 11, fill: '#64748b' });
                    const footerCode = new fabric.IText('CÓDIGO: SIG-IND-01', { left: 730, top: footerY + 22, fontSize: 11, fontWeight: '800', fill: '#33FF57' });

                    const baseItems = [bar, badgeBox, badgeTxt, title, line, sub, tag1Box, tag1Txt, tag2Box, tag2Txt, tag3Box, tag3Txt];

                    // Si el lienzo es amplio verticalmente (>= 640px), añadir los 3 pilares para cubrir todo el espacio
                    if (currentH >= 640) {
                        const cardH = Math.max(220, currentH - 380 - 120);
                        const c1 = new fabric.Rect({ left: 60, top: 380, width: 260, height: cardH, rx: 14, ry: 14, fill: '#142038', stroke: 'rgba(56, 189, 248, 0.3)', strokeWidth: 1 });
                        const c1b = new fabric.Rect({ left: 80, top: 405, width: 140, height: 26, rx: 6, ry: 6, fill: 'rgba(56, 189, 248, 0.15)' });
                        const c1bt = new fabric.IText('🏅 GESTIÓN DE CALIDAD', { left: 90, top: 411, fontSize: 10, fontWeight: '800', fill: '#38bdf8', fontFamily: 'Montserrat' });
                        const c1t = new fabric.IText('Norma ISO 9001', { left: 80, top: 445, fontSize: 17, fontWeight: '800', fill: '#ffffff', fontFamily: 'Montserrat' });
                        const c1p = new fabric.IText('• Enfoque en el aprendiz\n• Eficiencia en procesos\n• Mejora continua SIG', { left: 80, top: 485, fontSize: 12.5, fill: '#cbd5e1', lineHeight: 1.6, fontFamily: 'Inter' });

                        const c2 = new fabric.Rect({ left: 350, top: 380, width: 260, height: cardH, rx: 14, ry: 14, fill: '#142038', stroke: 'rgba(74, 222, 128, 0.3)', strokeWidth: 1 });
                        const c2b = new fabric.Rect({ left: 370, top: 405, width: 140, height: 26, rx: 6, ry: 6, fill: 'rgba(74, 222, 128, 0.15)' });
                        const c2bt = new fabric.IText('🌿 MEDIO AMBIENTE', { left: 380, top: 411, fontSize: 10, fontWeight: '800', fill: '#4ade80', fontFamily: 'Montserrat' });
                        const c2t = new fabric.IText('Norma ISO 14001', { left: 370, top: 445, fontSize: 17, fontWeight: '800', fill: '#ffffff', fontFamily: 'Montserrat' });
                        const c2p = new fabric.IText('• Separación en canecas\n• Ahorro de agua y energía\n• Política cero papel', { left: 370, top: 485, fontSize: 12.5, fill: '#cbd5e1', lineHeight: 1.6, fontFamily: 'Inter' });

                        const c3 = new fabric.Rect({ left: 640, top: 380, width: 260, height: cardH, rx: 14, ry: 14, fill: '#142038', stroke: 'rgba(251, 191, 36, 0.3)', strokeWidth: 1 });
                        const c3b = new fabric.Rect({ left: 660, top: 405, width: 140, height: 26, rx: 6, ry: 6, fill: 'rgba(251, 191, 36, 0.15)' });
                        const c3bt = new fabric.IText('🛡️ SEGURIDAD SST', { left: 670, top: 411, fontSize: 10, fontWeight: '800', fill: '#fbbf24', fontFamily: 'Montserrat' });
                        const c3t = new fabric.IText('Norma ISO 45001', { left: 660, top: 445, fontSize: 17, fontWeight: '800', fill: '#ffffff', fontFamily: 'Montserrat' });
                        const c3p = new fabric.IText('• Uso obligatorio de EPP\n• Reporte de condiciones\n• Prevención de riesgos', { left: 660, top: 485, fontSize: 12.5, fill: '#cbd5e1', lineHeight: 1.6, fontFamily: 'Inter' });

                        baseItems.push(c1, c1b, c1bt, c1t, c1p, c2, c2b, c2bt, c2t, c2p, c3, c3b, c3bt, c3t, c3p);
                    }

                    baseItems.push(footerBox, footerT1, footerT2, footerCode);
                    canvas.add(...baseItems);

                    // Insertar tarjeta de video interactiva 16:9 nítida y adaptable en la esquina superior derecha
                    insertarVideoEnLienzo({
                        url: 'https://www.youtube.com/watch?v=M2cckDmNLMI',
                        embed_url: 'https://www.youtube.com/embed/M2cckDmNLMI?autoplay=1',
                        title: 'Video Inducción Institucional SISIG',
                        provider: 'youtube',
                        thumbnail: 'https://img.youtube.com/vi/M2cckDmNLMI/mqdefault.jpg'
                    }, 705, 195);

                    canvas.renderAll();
                    recordState();

                } else if (tipo === 'sst') {
                    canvas.setBackgroundColor('#f8fafc', canvas.renderAll.bind(canvas));
                    const top = new fabric.Rect({ left: 0, top: 0, width: 960, height: 80, fill: '#1A2B4C', selectable: false });
                    const topStripe = new fabric.Rect({ left: 0, top: 76, width: 960, height: 4, fill: '#f59e0b', selectable: false });
                    const topBadge = new fabric.IText('SEGURIDAD Y SALUD EN EL TRABAJO • NORMA ISO 45001', { left: 40, top: 18, fontSize: 11, fontWeight: '800', fill: '#fbbf24' });
                    const topTitle = new fabric.IText('ALERTA PREVENTIVA: ELEMENTOS DE PROTECCIÓN PERSONAL', { left: 40, top: 38, fontSize: 22, fontWeight: '900', fill: '#ffffff' });

                    const card1 = new fabric.Rect({ left: 40, top: 105, width: 425, height: 405, rx: 16, ry: 16, fill: '#ffffff', stroke: '#fed7aa', strokeWidth: 2 });
                    const c1Badge = new fabric.Rect({ left: 60, top: 125, width: 200, height: 26, rx: 6, fill: '#fef3c7' });
                    const c1BadgeT = new fabric.IText('🛡️ EPP OBLIGATORIO', { left: 75, top: 131, fontSize: 11, fontWeight: '800', fill: '#b45309' });
                    const card1T = new fabric.IText('Equipos para Ambientes y Talleres', { left: 60, top: 165, fontSize: 18, fontWeight: '800', fill: '#7c2d12' });
                    const card1P = new fabric.IText('• Casco tipo II con barbuquejo en zonas de obra\n• Gafas de seguridad con protección contra impactos\n• Botas dieléctricas con puntera de seguridad\n• Protectores auditivos de inserción o tipo copa\n• Guantes adecuados al tipo de riesgo mecánico', { left: 60, top: 205, fontSize: 13.5, fill: '#334155', lineHeight: 1.6 });
                    const alertBox = new fabric.Rect({ left: 60, top: 395, width: 385, height: 90, rx: 10, fill: '#fff7ed', stroke: '#fdba74', strokeWidth: 1 });
                    const alertTxt = new fabric.IText('⚠️ RECUERDA: El uso adecuado de los EPP salva vidas y es de estricto cumplimiento para aprendices e instructores.', { left: 75, top: 412, fontSize: 12, fontWeight: '600', fill: '#9a3412', width: 355 });

                    const card2 = new fabric.Rect({ left: 495, top: 105, width: 425, height: 405, rx: 16, ry: 16, fill: '#ffffff', stroke: '#cbd5e1', strokeWidth: 1.5 });
                    const c2Badge = new fabric.Rect({ left: 515, top: 125, width: 195, height: 26, rx: 6, fill: '#e0f2fe' });
                    const c2BadgeT = new fabric.IText('📋 REPORTE OPORTUNO', { left: 530, top: 131, fontSize: 11, fontWeight: '800', fill: '#0369a1' });
                    const card2T = new fabric.IText('Condiciones y Actos Inseguros', { left: 515, top: 165, fontSize: 18, fontWeight: '800', fill: '#0f172a' });
                    const card2P = new fabric.IText('1. Identifica cables sueltos, pisos resbalosos o fallas\n2. Detén la tarea de inmediato ante peligro inminente\n3. Informa de inmediato a tu instructor a cargo\n4. Notifica al líder del Comité Paritario (COPASST)\n5. Colabora en la implementación de la medida correctiva', { left: 515, top: 205, fontSize: 13.5, fill: '#334155', lineHeight: 1.6 });
                    const phoneBox = new fabric.Rect({ left: 515, top: 395, width: 385, height: 90, rx: 10, fill: '#f8fafc', stroke: '#cbd5e1', strokeWidth: 1 });
                    const phoneTxt = new fabric.IText('📞 LÍNEA DE EMERGENCIAS SENA:\nExtensión 1111 / 2222 • Brigada de Emergencia Activa', { left: 530, top: 420, fontSize: 13, fontWeight: '700', fill: '#1A2B4C' });

                    canvas.add(top, topStripe, topBadge, topTitle, card1, c1Badge, c1BadgeT, card1T, card1P, alertBox, alertTxt, card2, c2Badge, c2BadgeT, card2T, card2P, phoneBox, phoneTxt);

                } else if (tipo === 'ambiental') {
                    canvas.setBackgroundColor('#f0fdf4', canvas.renderAll.bind(canvas));
                    const hBadge = new fabric.Rect({ left: 40, top: 25, width: 250, height: 28, rx: 14, fill: '#dcfce7' });
                    const hBadgeT = new fabric.IText('🌿 GESTIÓN AMBIENTAL • ISO 14001', { left: 55, top: 32, fontSize: 11, fontWeight: '800', fill: '#15803d' });
                    const hTitle = new fabric.IText('Buenas Prácticas Ambientales en el SENA', { left: 40, top: 62, fontSize: 26, fontWeight: '900', fill: '#14532d' });
                    const hSub = new fabric.IText('Pequeñas acciones diarias generan un gran impacto ecológico en nuestra comunidad educativa.', { left: 40, top: 98, fontSize: 13, fill: '#4b5563' });

                    const b1 = new fabric.Rect({ left: 40, top: 135, width: 425, height: 170, rx: 14, fill: '#ffffff', stroke: '#bbf7d0', strokeWidth: 1.5 });
                    const b1T = new fabric.IText('🗑️ 01. Separación en la Fuente', { left: 60, top: 155, fontSize: 16, fontWeight: '800', fill: '#166534' });
                    const b1P = new fabric.IText('Deposita los residuos en su caneca correspondiente: Blanca (reciclables limpios), Verde (orgánicos) y Negra (no aprovechables). Enjuaga los envases antes de depositarlos.', { left: 60, top: 188, fontSize: 13, fill: '#374151', width: 385, lineHeight: 1.45 });

                    const b2 = new fabric.Rect({ left: 495, top: 135, width: 425, height: 170, rx: 14, fill: '#ffffff', stroke: '#bbf7d0', strokeWidth: 1.5 });
                    const b2T = new fabric.IText('⚡ 02. Uso Eficiente de Energía', { left: 515, top: 155, fontSize: 16, fontWeight: '800', fill: '#166534' });
                    const b2P = new fabric.IText('Apaga computadores, proyectores y luces al terminar la jornada de formación. Aprovecha la luz natural en talleres y abre ventanas para ventilación eficiente.', { left: 515, top: 188, fontSize: 13, fill: '#374151', width: 385, lineHeight: 1.45 });

                    const b3 = new fabric.Rect({ left: 40, top: 325, width: 425, height: 175, rx: 14, fill: '#ffffff', stroke: '#bbf7d0', strokeWidth: 1.5 });
                    const b3T = new fabric.IText('💧 03. Cuidado y Ahorro de Agua', { left: 60, top: 345, fontSize: 16, fontWeight: '800', fill: '#166534' });
                    const b3P = new fabric.IText('Cierra la llave mientras te enjabonas las manos. Si detectas fugas, llaves goteando o averías en sanitarios y lavamanos, repórtalas de inmediato a Servicios Generales.', { left: 60, top: 378, fontSize: 13, fill: '#374151', width: 385, lineHeight: 1.45 });

                    const b4 = new fabric.Rect({ left: 495, top: 325, width: 425, height: 175, rx: 14, fill: '#ffffff', stroke: '#bbf7d0', strokeWidth: 1.5 });
                    const b4T = new fabric.IText('📄 04. Política Cero Papel', { left: 515, top: 345, fontSize: 16, fontWeight: '800', fill: '#166534' });
                    const b4P = new fabric.IText('Prioriza el uso de plataformas virtuales (Zajuna, correo misena) y firmas digitales. Si requieres imprimir guías, utiliza hojas reutilizables impresas por ambas caras.', { left: 515, top: 378, fontSize: 13, fill: '#374151', width: 385, lineHeight: 1.45 });

                    canvas.add(hBadge, hBadgeT, hTitle, hSub, b1, b1T, b1P, b2, b2T, b2P, b3, b3T, b3P, b4, b4T, b4P);

                } else if (tipo === 'evaluacion') {
                    canvas.setBackgroundColor('#0f172a', canvas.renderAll.bind(canvas));
                    const badge = new fabric.Rect({ left: 480, top: 40, originX: 'center', width: 280, height: 32, rx: 16, fill: '#33FF57' });
                    const badgeT = new fabric.IText('AUTOEVALUACIÓN DE APRENDIZAJE', { left: 480, top: 48, originX: 'center', fontSize: 11, fontWeight: '900', fill: '#0f172a' });
                    const q = new fabric.IText('¿Cuál es el objetivo principal del Sistema Integrado de Gestión (SISIG) en el SENA?', { left: 480, top: 95, originX: 'center', fontSize: 21, fontWeight: '800', fill: '#ffffff', textAlign: 'center', width: 840 });

                    const optABox = new fabric.Rect({ left: 70, top: 175, width: 820, height: 65, rx: 12, fill: '#1e293b', stroke: '#334155', strokeWidth: 1 });
                    const optABadge = new fabric.Rect({ left: 88, top: 187, width: 40, height: 40, rx: 8, fill: '#334155' });
                    const optALetter = new fabric.IText('A', { left: 101, top: 196, fontSize: 18, fontWeight: '900', fill: '#94a3b8' });
                    const optATxt = new fabric.IText('Cumplir únicamente con trámites de auditoría sin involucrar los procesos misionales.', { left: 145, top: 197, fontSize: 14, fill: '#cbd5e1' });

                    const optBBox = new fabric.Rect({ left: 70, top: 255, width: 820, height: 80, rx: 12, fill: 'rgba(46, 111, 64, 0.4)', stroke: '#33FF57', strokeWidth: 2 });
                    const optBBadge = new fabric.Rect({ left: 88, top: 270, width: 50, height: 50, rx: 10, fill: '#33FF57' });
                    const optBLetter = new fabric.IText('B', { left: 105, top: 282, fontSize: 20, fontWeight: '900', fill: '#0f172a' });
                    const optBTxt = new fabric.IText('Articular la calidad, la gestión ambiental y la SST para fomentar la excelencia en la formación y la mejora continua.', { left: 155, top: 272, fontSize: 14, fontWeight: '700', fill: '#ffffff', width: 630 });
                    const optBCorrect = new fabric.IText('✓ CORRECTA', { left: 800, top: 286, fontSize: 11, fontWeight: '900', fill: '#33FF57' });

                    const optCBox = new fabric.Rect({ left: 70, top: 350, width: 820, height: 65, rx: 12, fill: '#1e293b', stroke: '#334155', strokeWidth: 1 });
                    const optCBadge = new fabric.Rect({ left: 88, top: 362, width: 40, height: 40, rx: 8, fill: '#334155' });
                    const optCLetter = new fabric.IText('C', { left: 101, top: 371, fontSize: 18, fontWeight: '900', fill: '#94a3b8' });
                    const optCTxt = new fabric.IText('Delegar toda la responsabilidad ambiental en el personal de aseo y mantenimiento.', { left: 145, top: 372, fontSize: 14, fill: '#cbd5e1' });

                    const hintTxt = new fabric.IText('💡 Recuerda: El SISIG integra las normas internacionales ISO 9001, ISO 14001 e ISO 45001.', { left: 480, top: 460, originX: 'center', fontSize: 13, fontStyle: 'italic', fill: '#94a3b8' });

                    canvas.add(badge, badgeT, q, optABox, optABadge, optALetter, optATxt, optBBox, optBBadge, optBLetter, optBTxt, optBCorrect, optCBox, optCBadge, optCLetter, optCTxt, hintTxt);

                } else if (tipo === 'proceso_pasos') {
                    canvas.setBackgroundColor('#f8fafc', canvas.renderAll.bind(canvas));
                    const pTag = new fabric.IText('MEJORA CONTINUA • CICLO PHVA', { left: 480, top: 30, originX: 'center', fontSize: 11, fontWeight: '900', fill: '#4f46e5', letterSpacing: 1 });
                    const pTitle = new fabric.IText('Fases del Ciclo de Calidad e Innovación', { left: 480, top: 54, originX: 'center', fontSize: 24, fontWeight: '900', fill: '#0f172a' });

                    const col1Box = new fabric.Rect({ left: 40, top: 100, width: 205, height: 400, rx: 14, fill: '#ffffff', stroke: '#c7d2fe', strokeWidth: 2 });
                    const col1Top = new fabric.Rect({ left: 40, top: 100, width: 205, height: 60, rx: 14, fill: '#4f46e5' });
                    const col1Num = new fabric.IText('01', { left: 55, top: 115, fontSize: 26, fontWeight: '900', fill: '#ffffff' });
                    const col1Lbl = new fabric.IText('PLANEAR', { left: 105, top: 122, fontSize: 14, fontWeight: '800', fill: '#ffffff' });
                    const col1Txt = new fabric.IText('• Definir metas y directrices institucionales\n\n• Identificar aspectos ambientales y peligros SST\n\n• Diseñar planes de trabajo y presupuestos', { left: 55, top: 180, fontSize: 12.5, fill: '#334155', width: 175, lineHeight: 1.5 });

                    const col2Box = new fabric.Rect({ left: 265, top: 100, width: 205, height: 400, rx: 14, fill: '#ffffff', stroke: '#a7f3d0', strokeWidth: 2 });
                    const col2Top = new fabric.Rect({ left: 265, top: 100, width: 205, height: 60, rx: 14, fill: '#059669' });
                    const col2Num = new fabric.IText('02', { left: 280, top: 115, fontSize: 26, fontWeight: '900', fill: '#ffffff' });
                    const col2Lbl = new fabric.IText('HACER', { left: 330, top: 122, fontSize: 14, fontWeight: '800', fill: '#ffffff' });
                    const col2Txt = new fabric.IText('• Ejecutar programas de formación integral\n\n• Aplicar controles operacionales en talleres\n\n• Gestionar evidencias y registros oficiales', { left: 280, top: 180, fontSize: 12.5, fill: '#334155', width: 175, lineHeight: 1.5 });

                    const col3Box = new fabric.Rect({ left: 490, top: 100, width: 205, height: 400, rx: 14, fill: '#ffffff', stroke: '#fde68a', strokeWidth: 2 });
                    const col3Top = new fabric.Rect({ left: 490, top: 100, width: 205, height: 60, rx: 14, fill: '#d97706' });
                    const col3Num = new fabric.IText('03', { left: 505, top: 115, fontSize: 26, fontWeight: '900', fill: '#ffffff' });
                    const col3Lbl = new fabric.IText('VERIFICAR', { left: 555, top: 122, fontSize: 14, fontWeight: '800', fill: '#ffffff' });
                    const col3Txt = new fabric.IText('• Ejecutar auditorías internas de calidad\n\n• Monitorear indicadores de accidentalidad\n\n• Medir satisfacción de aprendices', { left: 505, top: 180, fontSize: 12.5, fill: '#334155', width: 175, lineHeight: 1.5 });

                    const col4Box = new fabric.Rect({ left: 715, top: 100, width: 205, height: 400, rx: 14, fill: '#ffffff', stroke: '#ddd6fe', strokeWidth: 2 });
                    const col4Top = new fabric.Rect({ left: 715, top: 100, width: 205, height: 60, rx: 14, fill: '#7c3aed' });
                    const col4Num = new fabric.IText('04', { left: 730, top: 115, fontSize: 26, fontWeight: '900', fill: '#ffffff' });
                    const col4Lbl = new fabric.IText('ACTUAR', { left: 780, top: 122, fontSize: 14, fontWeight: '800', fill: '#ffffff' });
                    const col4Txt = new fabric.IText('• Formular planes de mejoramiento continuo\n\n• Corregir no conformidades detectadas\n\n• Estandarizar buenas prácticas exitosas', { left: 730, top: 180, fontSize: 12.5, fill: '#334155', width: 175, lineHeight: 1.5 });

                    canvas.add(pTag, pTitle, col1Box, col1Top, col1Num, col1Lbl, col1Txt, col2Box, col2Top, col2Num, col2Lbl, col2Txt, col3Box, col3Top, col3Num, col3Lbl, col3Txt, col4Box, col4Top, col4Num, col4Lbl, col4Txt);

                } else if (tipo === 'canecas_residuos') {
                    canvas.setBackgroundColor('#f8fafc', canvas.renderAll.bind(canvas));
                    const resBadge = new fabric.Rect({ left: 40, top: 22, width: 340, height: 26, rx: 6, fill: '#047857' });
                    const resBadgeT = new fabric.IText('RESOLUCIÓN 2184 DE 2019 • CÓDIGO UNIFICADO', { left: 50, top: 28, fontSize: 11, fontWeight: '900', fill: '#ffffff' });
                    const resTitle = new fabric.IText('Clasificación Adecuada de Residuos en el SENA', { left: 40, top: 58, fontSize: 24, fontWeight: '900', fill: '#0f172a' });

                    const cBlancaBox = new fabric.Rect({ left: 40, top: 98, width: 270, height: 415, rx: 16, fill: '#ffffff', stroke: '#94a3b8', strokeWidth: 2.5 });
                    const cBlancaTop = new fabric.Rect({ left: 40, top: 98, width: 270, height: 55, rx: 16, fill: '#f1f5f9' });
                    const cBlancaT = new fabric.IText('⚪ CANECA BLANCA', { left: 60, top: 115, fontSize: 16, fontWeight: '900', fill: '#1e293b' });
                    const cBlancaSub = new fabric.IText('RESIDUOS APROVECHABLES', { left: 60, top: 170, fontSize: 12, fontWeight: '800', fill: '#2563eb' });
                    const cBlancaTxt = new fabric.IText('• Botellas plásticas PET (limpias)\n• Cartón corrugado y cajas secas\n• Papel de oficina y archivo\n• Envases Tetra Pak limpios\n• Latas de aluminio y conservas\n• Vidrio en botellas y frascos\n\n📌 NOTA: Todo debe estar seco y sin grasa.', { left: 60, top: 200, fontSize: 13, fill: '#475569', width: 230, lineHeight: 1.45 });

                    const cVerdeBox = new fabric.Rect({ left: 345, top: 98, width: 270, height: 415, rx: 16, fill: '#ffffff', stroke: '#16a34a', strokeWidth: 2.5 });
                    const cVerdeTop = new fabric.Rect({ left: 345, top: 98, width: 270, height: 55, rx: 16, fill: '#16a34a' });
                    const cVerdeT = new fabric.IText('🟢 CANECA VERDE', { left: 365, top: 115, fontSize: 16, fontWeight: '900', fill: '#ffffff' });
                    const cVerdeSub = new fabric.IText('ORGÁNICOS APROVECHABLES', { left: 365, top: 170, fontSize: 12, fontWeight: '800', fill: '#15803d' });
                    const cVerdeTxt = new fabric.IText('• Restos de frutas y verduras\n• Cáscaras de huevo y tubérculos\n• Cuncho de café y bolsitas de té\n• Hojas secas y cortes de poda\n• Sobrantes de alimentos crudos\n\n📌 NOTA: Destinados al compostaje institucional.', { left: 365, top: 200, fontSize: 13, fill: '#475569', width: 230, lineHeight: 1.45 });

                    const cNegraBox = new fabric.Rect({ left: 650, top: 98, width: 270, height: 415, rx: 16, fill: '#ffffff', stroke: '#1e293b', strokeWidth: 2.5 });
                    const cNegraTop = new fabric.Rect({ left: 650, top: 98, width: 270, height: 55, rx: 16, fill: '#0f172a' });
                    const cNegraT = new fabric.IText('⚫ CANECA NEGRA', { left: 670, top: 115, fontSize: 16, fontWeight: '900', fill: '#ffffff' });
                    const cNegraSub = new fabric.IText('NO APROVECHABLES', { left: 670, top: 170, fontSize: 12, fontWeight: '800', fill: '#dc2626' });
                    const cNegraTxt = new fabric.IText('• Papel higiénico y pañuelos\n• Servilletas y toallas de manos\n• Papeles encerados o plastificados\n• Envolturas de snacks y golosinas\n• Cartón con grasa o comida\n• Colillas de cigarrillo\n\n📌 NOTA: Van al relleno sanitario.', { left: 670, top: 200, fontSize: 13, fill: '#475569', width: 230, lineHeight: 1.45 });

                    canvas.add(resBadge, resBadgeT, resTitle, cBlancaBox, cBlancaTop, cBlancaT, cBlancaSub, cBlancaTxt, cVerdeBox, cVerdeTop, cVerdeT, cVerdeSub, cVerdeTxt, cNegraBox, cNegraTop, cNegraT, cNegraSub, cNegraTxt);

                } else if (tipo === 'emergencia') {
                    canvas.setBackgroundColor('#fef2f2', canvas.renderAll.bind(canvas));
                    const emTop = new fabric.Rect({ left: 0, top: 0, width: 960, height: 75, fill: '#dc2626', selectable: false });
                    const emTag = new fabric.IText('PLAN DE EMERGENCIAS Y CONTINGENCIAS • SENA', { left: 40, top: 16, fontSize: 11, fontWeight: '900', fill: '#fef08a' });
                    const emTitle = new fabric.IText('PROTOCOLO DE EVACUACIÓN Y ALARMA', { left: 40, top: 36, fontSize: 22, fontWeight: '900', fill: '#ffffff' });

                    const leftBox = new fabric.Rect({ left: 40, top: 95, width: 530, height: 420, rx: 16, fill: '#ffffff', stroke: '#fca5a5', strokeWidth: 2 });
                    const s1T = new fabric.IText('1. CONSERVE LA CALMA', { left: 65, top: 120, fontSize: 15, fontWeight: '900', fill: '#b91c1c' });
                    const s1D = new fabric.IText('No corra, no grite ni cause pánico. Atienda la voz de su brigadista.', { left: 65, top: 142, fontSize: 13, fill: '#475569' });
                    const s2T = new fabric.IText('2. SUSPENDA ACTIVIDADES Y DESCONECTE', { left: 65, top: 178, fontSize: 15, fontWeight: '900', fill: '#b91c1c' });
                    const s2D = new fabric.IText('Apague máquinas y computadores. Cierre suministros de gas o energía.', { left: 65, top: 200, fontSize: 13, fill: '#475569' });
                    const s3T = new fabric.IText('3. SIGA LAS RUTAS VERDES DE EVACUACIÓN', { left: 65, top: 236, fontSize: 15, fontWeight: '900', fill: '#b91c1c' });
                    const s3D = new fabric.IText('Camine por la derecha. Utilice siempre escaleras; jamás los ascensores.', { left: 65, top: 258, fontSize: 13, fill: '#475569' });
                    const s4T = new fabric.IText('4. DIRÍJASE AL PUNTO DE ENCUENTRO', { left: 65, top: 294, fontSize: 15, fontWeight: '900', fill: '#b91c1c' });
                    const s4D = new fabric.IText('Espere el censo del brigadista. No regrese por pertenencias olvidadas.', { left: 65, top: 316, fontSize: 13, fill: '#475569' });
                    const warnBox = new fabric.Rect({ left: 65, top: 360, width: 480, height: 125, rx: 10, fill: '#fff1f2', stroke: '#f43f5e', strokeWidth: 1.5 });
                    const warnTxt = new fabric.IText('🚫 PROHIBIDO EL REINGRESO:\nNinguna persona debe retornar al edificio o taller hasta que el Coordinador de Brigada y los Bomberos declaren la zona completamente segura.', { left: 80, top: 380, fontSize: 12.5, fontWeight: '700', fill: '#9f1239', width: 450 });

                    const rightBox = new fabric.Rect({ left: 590, top: 95, width: 330, height: 420, rx: 16, fill: '#ffffff', stroke: '#cbd5e1', strokeWidth: 1.5 });
                    const puntoBox = new fabric.Rect({ left: 610, top: 115, width: 290, height: 75, rx: 12, fill: '#15803d' });
                    const puntoT = new fabric.IText('🟢 PUNTO DE ENCUENTRO', { left: 625, top: 127, fontSize: 15, fontWeight: '900', fill: '#ffffff' });
                    const puntoD = new fabric.IText('Plaza de la Cultura / Zonas Verdes', { left: 625, top: 153, fontSize: 12, fill: '#dcfce7' });
                    const phonesT = new fabric.IText('🚨 TELÉFONOS DE SOCORRO', { left: 610, top: 215, fontSize: 14, fontWeight: '900', fill: '#0f172a' });
                    const phonesD = new fabric.IText('• Brigada SENA: Ext. 9999\n• Emergencias Nacional: 123\n• Cuerpo de Bomberos: 119\n• Cruz Roja Colombiana: 132\n• Defensa Civil: 144\n• Policía Nacional: 112', { left: 610, top: 245, fontSize: 13.5, fill: '#334155', lineHeight: 1.6 });
                    const camillaT = new fabric.IText('📍 Camillas y botiquines en cada piso junto a la estación de extintores.', { left: 610, top: 440, fontSize: 11, fontStyle: 'italic', fill: '#64748b', width: 290 });

                    canvas.add(emTop, emTag, emTitle, leftBox, s1T, s1D, s2T, s2D, s3T, s3D, s4T, s4D, warnBox, warnTxt, rightBox, puntoBox, puntoT, puntoD, phonesT, phonesD, camillaT);

                } else if (tipo === 'politica_calidad') {
                    canvas.setBackgroundColor('#fffbeb', canvas.renderAll.bind(canvas));
                    const polBorderOut = new fabric.Rect({ left: 20, top: 20, width: 920, height: 500, rx: 12, fill: '#ffffff', stroke: '#1A2B4C', strokeWidth: 3 });
                    const polBorderIn = new fabric.Rect({ left: 26, top: 26, width: 908, height: 488, rx: 8, fill: 'transparent', stroke: '#d97706', strokeWidth: 1 });
                    const polInst = new fabric.IText('SERVICIO NACIONAL DE APRENDIZAJE • SENA', { left: 480, top: 48, originX: 'center', fontSize: 12, fontWeight: '900', fill: '#1A2B4C', letterSpacing: 1.5 });
                    const polTitle = new fabric.IText('POLÍTICA DEL SISTEMA INTEGRADO DE GESTIÓN', { left: 480, top: 72, originX: 'center', fontSize: 21, fontWeight: '900', fill: '#2E6F40' });
                    const polDiv = new fabric.Rect({ left: 380, top: 102, width: 200, height: 2, fill: '#d97706' });
                    const polText = new fabric.IText('El SENA se compromete con la formación profesional integral para la incorporación de las personas en actividades productivas que contribuyan al desarrollo social, económico y tecnológico del país, satisfaciendo los requisitos de clientes y partes interesadas, protegiendo el medio ambiente mediante la prevención de la contaminación y garantizando ambientes laborales seguros y saludables mediante la mejora continua.', { left: 480, top: 118, originX: 'center', fontSize: 13, fill: '#334155', textAlign: 'center', width: 830, lineHeight: 1.45 });

                    const pil1Box = new fabric.Rect({ left: 55, top: 225, width: 268, height: 185, rx: 10, fill: '#f8fafc', stroke: '#cbd5e1', strokeWidth: 1 });
                    const pil1T = new fabric.IText('🎖️ ISO 9001:2015', { left: 75, top: 242, fontSize: 15, fontWeight: '900', fill: '#1A2B4C' });
                    const pil1S = new fabric.IText('GESTIÓN DE CALIDAD', { left: 75, top: 265, fontSize: 11, fontWeight: '800', fill: '#0284c7' });
                    const pil1P = new fabric.IText('Enfocados en la satisfacción de aprendices y empresarios mediante la pertinencia y calidad de la formación profesional.', { left: 75, top: 295, fontSize: 12, fill: '#475569', width: 228, lineHeight: 1.4 });

                    const pil2Box = new fabric.Rect({ left: 346, top: 225, width: 268, height: 185, rx: 10, fill: '#f8fafc', stroke: '#cbd5e1', strokeWidth: 1 });
                    const pil2T = new fabric.IText('🌱 ISO 14001:2015', { left: 366, top: 242, fontSize: 15, fontWeight: '900', fill: '#15803d' });
                    const pil2S = new fabric.IText('GESTIÓN AMBIENTAL', { left: 366, top: 265, fontSize: 11, fontWeight: '800', fill: '#16a34a' });
                    const pil2P = new fabric.IText('Prevención de la contaminación, uso responsable del agua y energía, y correcta separación de residuos en la fuente.', { left: 366, top: 295, fontSize: 12, fill: '#475569', width: 228, lineHeight: 1.4 });

                    const pil3Box = new fabric.Rect({ left: 637, top: 225, width: 268, height: 185, rx: 10, fill: '#f8fafc', stroke: '#cbd5e1', strokeWidth: 1 });
                    const pil3T = new fabric.IText('⛑️ ISO 45001:2018', { left: 657, top: 242, fontSize: 15, fontWeight: '900', fill: '#b45309' });
                    const pil3S = new fabric.IText('SEGURIDAD Y SALUD', { left: 657, top: 265, fontSize: 11, fontWeight: '800', fill: '#d97706' });
                    const pil3P = new fabric.IText('Garantizar ambientes de formación seguros y saludables, eliminando peligros y previniendo lesiones y enfermedades laborales.', { left: 657, top: 295, fontSize: 12, fill: '#475569', width: 228, lineHeight: 1.4 });

                    const polFoot = new fabric.IText('Dirección General del SENA • Aprobado por el Comité Institucional de Gestión y Desempeño SISIG', { left: 480, top: 450, originX: 'center', fontSize: 11.5, fontWeight: '700', fill: '#1A2B4C' });

                    canvas.add(polBorderOut, polBorderIn, polInst, polTitle, polDiv, polText, pil1Box, pil1T, pil1S, pil1P, pil2Box, pil2T, pil2S, pil2P, pil3Box, pil3T, pil3S, pil3P, polFoot);

                } else if (tipo === 'ficha_equipo') {
                    canvas.setBackgroundColor('#f8fafc', canvas.renderAll.bind(canvas));
                    const fBadge = new fabric.Rect({ left: 40, top: 25, width: 200, height: 26, rx: 6, fill: '#dbeafe' });
                    const fBadgeT = new fabric.IText('FICHA TÉCNICA DE EQUIPO', { left: 52, top: 31, fontSize: 11, fontWeight: '900', fill: '#1d4ed8' });
                    const fTitle = new fabric.IText('TORNO PARALELO INDUSTRIAL', { left: 40, top: 60, fontSize: 24, fontWeight: '900', fill: '#0f172a' });
                    const fSub = new fabric.IText('Código Inventario: MAQ-TAL-042 • Ubicación: Taller Metalmecánica', { left: 40, top: 92, fontSize: 12, fill: '#64748b' });

                    const fCard = new fabric.Rect({ left: 40, top: 125, width: 480, height: 260, rx: 12, fill: '#ffffff', stroke: '#e2e8f0', strokeWidth: 1.5 });
                    const th1 = new fabric.IText('PARÁMETRO', { left: 60, top: 140, fontSize: 12, fontWeight: '900', fill: '#1e293b' });
                    const th2 = new fabric.IText('ESPECIFICACIÓN', { left: 280, top: 140, fontSize: 12, fontWeight: '900', fill: '#1e293b' });
                    const thLine = new fabric.Rect({ left: 60, top: 162, width: 440, height: 1, fill: '#e2e8f0' });
                    const fLabels = new fabric.IText('Voltaje de Operación:\nPotencia de Motor:\nVelocidad de Husillo:\nFrecuencia de Mantenimiento:\nResponsable Técnico:', { left: 60, top: 175, fontSize: 13, fontWeight: '700', fill: '#475569', lineHeight: 1.6 });
                    const fValues = new fabric.IText('220V Trifásico 60Hz\n5.5 HP (4.0 kW)\n45 a 2000 RPM\nMensual Preventivo\nInstructor Área Metalúrgica', { left: 280, top: 175, fontSize: 13, fill: '#0f172a', lineHeight: 1.6 });

                    const fSafety = new fabric.Rect({ left: 40, top: 405, width: 480, height: 100, rx: 10, fill: '#eff6ff', stroke: '#bfdbfe', strokeWidth: 1 });
                    const fSafetyT = new fabric.IText('⚠️ CONDICIÓN DE SEGURIDAD SST:\nOperar con careta facial, cabello recogido y sin guantes cerca del mandril giratorio. Verificar que el botón de parada de emergencia esté liberado y operativo.', { left: 55, top: 418, fontSize: 12, fontWeight: '600', fill: '#1e40af', width: 450 });

                    const fPhotoH = new fabric.IText('REGISTRO FOTOGRÁFICO', { left: 740, top: 35, originX: 'center', fontSize: 12, fontWeight: '900', fill: '#0284c7' });
                    const fFootNotice = new fabric.IText('Arrastra una imagen sobre el marco • Inspección SISIG SENA', { left: 740, top: 485, originX: 'center', fontSize: 11, fill: '#94a3b8' });

                    canvas.add(fBadge, fBadgeT, fTitle, fSub, fCard, th1, th2, thLine, fLabels, fValues, fSafety, fSafetyT, fPhotoH, fFootNotice);

                    // Insertar marco interactivo para foto
                    const marcoEquipo = crearMarcoVacio('rounded', 740, 260);
                    marcoEquipo.scale(1.15);
                    canvas.add(marcoEquipo);

                } else if (tipo === 'diapositiva_split') {
                    canvas.setBackgroundColor('#ffffff', canvas.renderAll.bind(canvas));
                    const dTag = new fabric.IText('MÓDULO DE FORMACIÓN • LECCIÓN 03', { left: 40, top: 35, fontSize: 11, fontWeight: '900', fill: '#6366f1' });
                    const dTitle = new fabric.IText('Jerarquía de Controles\ny Gestión de Riesgos', { left: 40, top: 62, fontSize: 28, fontWeight: '900', fill: '#0f172a', lineHeight: 1.15 });
                    const dDesc = new fabric.IText('La jerarquía de controles es la metodología preventiva adoptada por el SENA para intervenir los peligros laborales desde su origen antes de recurrir a la protección individual.', { left: 40, top: 155, fontSize: 13.5, fill: '#475569', width: 430, lineHeight: 1.45 });

                    const dCard = new fabric.Rect({ left: 40, top: 240, width: 430, height: 260, rx: 14, fill: '#f8fafc', stroke: '#e2e8f0', strokeWidth: 1.5 });
                    const dItems = new fabric.IText('1. Eliminación: Suprimir totalmente el peligro\n2. Sustitución: Cambiar por insumos menos nocivos\n3. Controles de ingeniería: Instalar guardas y extractores\n4. Controles administrativos: Señalizar y capacitar\n5. EPP: Dotación personal como última barrera de contención', { left: 60, top: 265, fontSize: 13, fontWeight: '600', fill: '#1e293b', width: 390, lineHeight: 1.7 });

                    const dPhotoH = new fabric.IText('EVIDENCIA PRÁCTICA EN TALLER', { left: 725, top: 35, originX: 'center', fontSize: 11, fontWeight: '900', fill: '#a855f7' });
                    const dPhotoFoot = new fabric.IText('📷 Fotografía de aplicación real por aprendices', { left: 725, top: 495, originX: 'center', fontSize: 11, fontWeight: '600', fill: '#64748b' });

                    canvas.add(dTag, dTitle, dDesc, dCard, dItems, dPhotoH, dPhotoFoot);

                    // Insertar marco de arco interactivo
                    const marcoDiapo = crearMarcoVacio('arch', 725, 270);
                    marcoDiapo.scale(1.2);
                    canvas.add(marcoDiapo);

                } else if (tipo === 'pausa_activa') {
                    canvas.setBackgroundColor('#f0f9ff', canvas.renderAll.bind(canvas));
                    const paBadge = new fabric.Rect({ left: 40, top: 22, width: 250, height: 28, rx: 14, fill: '#bae6fd' });
                    const paBadgeT = new fabric.IText('🧘 SALUD LABORAL • ERGONOMÍA', { left: 55, top: 29, fontSize: 11, fontWeight: '900', fill: '#0369a1' });
                    const paTitle = new fabric.IText('Programa de Pausas Activas Diarias', { left: 40, top: 58, fontSize: 26, fontWeight: '900', fill: '#0c4a6e' });
                    const paSub = new fabric.IText('Realiza estos ejercicios 2 veces durante tu jornada de estudio o trabajo para reducir la fatiga muscular y visual.', { left: 40, top: 94, fontSize: 13, fill: '#0369a1' });

                    const pa1Box = new fabric.Rect({ left: 40, top: 125, width: 270, height: 385, rx: 16, fill: '#ffffff', stroke: '#7dd3fc', strokeWidth: 2 });
                    const pa1Top = new fabric.Rect({ left: 40, top: 125, width: 270, height: 50, rx: 16, fill: '#0284c7' });
                    const pa1T = new fabric.IText('💆 01. CUELLO Y HOMBROS', { left: 60, top: 140, fontSize: 14, fontWeight: '900', fill: '#ffffff' });
                    const pa1Txt = new fabric.IText('1. Inclina la cabeza hacia tu hombro derecho suavemente durante 15 segundos.\n\n2. Repite el movimiento hacia el hombro izquierdo.\n\n3. Realiza 10 círculos suaves con los hombros hacia atrás.\n\n⏱️ Tiempo: 2 Minutos', { left: 60, top: 195, fontSize: 13, fill: '#334155', width: 230, lineHeight: 1.45 });

                    const pa2Box = new fabric.Rect({ left: 345, top: 125, width: 270, height: 385, rx: 16, fill: '#ffffff', stroke: '#7dd3fc', strokeWidth: 2 });
                    const pa2Top = new fabric.Rect({ left: 345, top: 125, width: 270, height: 50, rx: 16, fill: '#0284c7' });
                    const pa2T = new fabric.IText('🖐️ 02. MANOS Y MUÑECAS', { left: 365, top: 140, fontSize: 14, fontWeight: '900', fill: '#ffffff' });
                    const pa2Txt = new fabric.IText('1. Extiende el brazo hacia adelante y con la otra mano empuja suavemente los dedos hacia atrás 15 seg.\n\n2. Cierra las manos fuertemente y ábrelas estirando los dedos.\n\n3. Realiza giros suaves de muñeca.\n\n⏱️ Tiempo: 2 Minutos', { left: 365, top: 195, fontSize: 13, fill: '#334155', width: 230, lineHeight: 1.45 });

                    const pa3Box = new fabric.Rect({ left: 650, top: 125, width: 270, height: 385, rx: 16, fill: '#ffffff', stroke: '#7dd3fc', strokeWidth: 2 });
                    const pa3Top = new fabric.Rect({ left: 650, top: 125, width: 270, height: 50, rx: 16, fill: '#0284c7' });
                    const pa3T = new fabric.IText('👀 03. DESCANSO VISUAL', { left: 670, top: 140, fontSize: 14, fontWeight: '900', fill: '#ffffff' });
                    const pa3Txt = new fabric.IText('1. Regla 20-20-20: Mira a un punto lejano (6 metros) durante 20 segundos cada 20 minutos.\n\n2. Parpadea repetidamente para lubricar los ojos.\n\n3. Entrelaza las manos por detrás de la espalda y estira el torso.\n\n⏱️ Tiempo: 1 Minuto', { left: 670, top: 195, fontSize: 13, fill: '#334155', width: 230, lineHeight: 1.45 });

                    canvas.add(paBadge, paBadgeT, paTitle, paSub, pa1Box, pa1Top, pa1T, pa1Txt, pa2Box, pa2Top, pa2T, pa2Txt, pa3Box, pa3Top, pa3T, pa3Txt);

                } else if (tipo === 'diploma') {
                    canvas.setBackgroundColor('#fffdfa', canvas.renderAll.bind(canvas));
                    const dipBorderOut = new fabric.Rect({ left: 25, top: 20, width: 910, height: 500, rx: 8, fill: '#ffffff', stroke: '#1A2B4C', strokeWidth: 4 });
                    const dipBorderIn = new fabric.Rect({ left: 35, top: 30, width: 890, height: 480, rx: 4, fill: 'transparent', stroke: '#d97706', strokeWidth: 1.5 });
                    const dipInst = new fabric.IText('SERVICIO NACIONAL DE APRENDIZAJE • SENA', { left: 480, top: 52, originX: 'center', fontSize: 13, fontWeight: '900', fill: '#1A2B4C', letterSpacing: 2 });
                    const dipSub = new fabric.IText('SISTEMA INTEGRADO DE GESTIÓN • SISIG', { left: 480, top: 72, originX: 'center', fontSize: 11, fontWeight: '700', fill: '#2E6F40' });
                    const dipTitle = new fabric.IText('CERTIFICADO DE APROBACIÓN', { left: 480, top: 102, originX: 'center', fontSize: 30, fontWeight: '900', fill: '#1A2B4C', fontFamily: 'Montserrat' });
                    const dipOt = new fabric.IText('Otorga la presente constancia a:', { left: 480, top: 155, originX: 'center', fontSize: 13, fontStyle: 'italic', fill: '#64748b' });
                    const dipName = new fabric.IText('NOMBRE COMPLETO DEL APRENDIZ', { left: 480, top: 185, originX: 'center', fontSize: 26, fontWeight: '900', fill: '#2E6F40' });
                    const dipLine = new fabric.Rect({ left: 280, top: 220, width: 400, height: 2, fill: '#cbd5e1' });
                    const dipDesc = new fabric.IText('Por haber completado y aprobado satisfactoriamente la capacitación de inducción institucional en:\nPOLÍTICAS Y NORMAS DEL SISTEMA INTEGRADO DE GESTIÓN (ISO 9001, 14001, 45001)\ncon una intensidad horaria de 20 horas de formación integral.', { left: 480, top: 240, originX: 'center', fontSize: 13, fill: '#334155', textAlign: 'center', width: 780, lineHeight: 1.5 });
                    const dipDate = new fabric.IText('Expedido en Colombia el 05 de Noviembre de 2026', { left: 480, top: 335, originX: 'center', fontSize: 12, fill: '#64748b' });

                    const sig1Line = new fabric.Rect({ left: 140, top: 420, width: 220, height: 1.5, fill: '#1e293b' });
                    const sig1T = new fabric.IText('COORDINADOR DEL SISIG', { left: 250, top: 430, originX: 'center', fontSize: 11, fontWeight: '800', fill: '#1A2B4C' });
                    const sig1S = new fabric.IText('SENA Regional', { left: 250, top: 446, originX: 'center', fontSize: 10, fill: '#64748b' });

                    const sealC = new fabric.Circle({ left: 480, top: 420, originX: 'center', originY: 'center', radius: 26, fill: '#fef3c7', stroke: '#d97706', strokeWidth: 2 });
                    const sealT = new fabric.IText('SENA\nSISIG', { left: 480, top: 420, originX: 'center', originY: 'center', fontSize: 10, fontWeight: '900', fill: '#b45309', textAlign: 'center' });

                    const sig2Line = new fabric.Rect({ left: 600, top: 420, width: 220, height: 1.5, fill: '#1e293b' });
                    const sig2T = new fabric.IText('SUBDIRECTOR DE CENTRO', { left: 710, top: 430, originX: 'center', fontSize: 11, fontWeight: '800', fill: '#1A2B4C' });
                    const sig2S = new fabric.IText('Centro de Formación', { left: 710, top: 446, originX: 'center', fontSize: 10, fill: '#64748b' });

                    canvas.add(dipBorderOut, dipBorderIn, dipInst, dipSub, dipTitle, dipOt, dipName, dipLine, dipDesc, dipDate, sig1Line, sig1T, sig1S, sealC, sealT, sig2Line, sig2T, sig2S);
                }

                canvas.renderAll();
                recordState();
            }

            // -------------------------------------------------------------
            // 15. PERSISTENCIA EN BACKEND (POST /sisig/editor/modules/{id}/canvas)
            // -------------------------------------------------------------
            const btnSave = document.getElementById('btn-save-canvas');
            const btnSaveText = document.getElementById('btn-save-text');
            const iconSave = document.getElementById('icon-save');
            const lastSavedTime = document.getElementById('last-saved-time');

            if (btnSave) {
                btnSave.addEventListener('click', guardarLienzoEnBackend);
            }

            function escaparHtml(text) {
                if (!text) return '';
                const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
                return String(text).replace(/[&<>"']/g, m => map[m]);
            }

            // Generador de Contenido Didáctico Semántico y Fluido para la Vista del Aprendiz (NO diapositiva rígida)
            function generarContenidoHtmlDidactico(c) {
                if (!c) return '';
                const objects = c.getObjects();
                if (!objects || objects.length === 0) return '';

                let headerPill = '';
                let mainTitle = '';
                let subtitle = '';
                const badges = [];
                const pillarCards = [];
                const videos = [];
                const images = [];
                const paragraphs = [];
                let footerText = '';
                let footerCode = '';

                objects.forEach(obj => {
                    // Video cards o marcos con video
                    if (obj.isVideoCard || (obj.isFrame && obj.hasVideo)) {
                        const url = obj.videoUrl || obj.embedUrl;
                        const title = obj.videoTitle || 'Video Interactivo';
                        const embed = obj.embedUrl || url;
                        if (url) {
                            videos.push({ url, embed, title });
                        }
                        return;
                    }

                    // Marcos con imagen o imágenes sueltas
                    if (obj.isFrame && obj.hasImage && obj.innerImageSrc) {
                        images.push({ src: obj.innerImageSrc, title: obj.frameType });
                        return;
                    }
                    if (obj.type === 'image' && !obj.isFrame) {
                        const src = obj.getSrc ? obj.getSrc() : (obj._element ? obj._element.src : null);
                        if (src && !src.startsWith('blob:')) {
                            images.push({ src, title: 'Recurso gráfico' });
                        }
                        return;
                    }

                    // Textos del lienzo
                    if (obj.type === 'i-text' || obj.type === 'text') {
                        const t = (obj.text || '').trim();
                        if (!t) return;

                        if (t.includes('SISTEMA INTEGRADO DE GESTIÓN') || t.includes('SIG • SISIG') || t.includes('SIG - SISIG')) {
                            headerPill = t;
                        } else if (t.toUpperCase().includes('INDUCCIÓN') && t.length < 50) {
                            mainTitle = t;
                        } else if (t.includes('ISO 9001') && t.length < 30) {
                            if (!badges.includes(t)) badges.push(t);
                        } else if (t.includes('ISO 14001') && t.length < 30) {
                            if (!badges.includes(t)) badges.push(t);
                        } else if (t.includes('ISO 45001') && t.length < 30) {
                            if (!badges.includes(t)) badges.push(t);
                        } else if (t.includes('Calidad, Seguridad y Salud') || (obj.fontSize >= 13 && obj.fontSize <= 20 && !mainTitle && !t.includes('•'))) {
                            subtitle = t;
                        } else if (t.includes('🏅 GESTIÓN DE CALIDAD') || t.includes('🌿 MEDIO AMBIENTE') || t.includes('🛡️ SEGURIDAD SST') || (obj.fontSize >= 15 && obj.fontWeight === '800' && t.length < 35)) {
                            pillarCards.push({ title: t, items: [] });
                        } else if (t.includes('•')) {
                            const lines = t.split('\n').map(l => l.replace(/^[•\-\*]\s*/, '').trim()).filter(l => l.length > 0);
                            if (pillarCards.length > 0) {
                                pillarCards[pillarCards.length - 1].items.push(...lines);
                            } else {
                                paragraphs.push({ type: 'list', items: lines });
                            }
                        } else if (t.includes('SERVICIO NACIONAL DE APRENDIZAJE') || t.includes('SENA •') || t.includes('REGIONAL DISTRITO CAPITAL')) {
                            footerText = t;
                        } else if (t.includes('CÓDIGO:') || t.includes('SIG-IND-')) {
                            footerCode = t;
                        } else if (t.length > 25) {
                            paragraphs.push({ type: 'text', text: t });
                        }
                    }
                });

                let html = '<div class="espacio-didactico-interactivo space-y-8 max-w-5xl mx-auto py-2">';

                // Encabezado institucional
                if (headerPill || mainTitle || subtitle || badges.length > 0) {
                    html += '<div class="rounded-3xl bg-gradient-to-br from-[#1A2B4C] to-[#0f172a] text-white p-6 sm:p-8 shadow-md border border-slate-700/50 relative overflow-hidden">';
                    html += '<div class="relative z-10 space-y-3">';
                    if (headerPill) {
                        html += `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black tracking-wide uppercase bg-[#33FF57]/15 text-[#33FF57] border border-[#33FF57]/30"><i class="fas fa-shield-alt text-xs"></i> ${escaparHtml(headerPill)}</span>`;
                    }
                    if (mainTitle) {
                        html += `<h1 class="text-2xl sm:text-4xl font-black text-white tracking-tight leading-tight mt-1" style="color: #ffffff !important;">${escaparHtml(mainTitle)}</h1>`;
                    }
                    if (subtitle) {
                        html += `<p class="text-slate-300 text-sm sm:text-base font-normal max-w-3xl leading-relaxed">${escaparHtml(subtitle)}</p>`;
                    }
                    if (badges.length > 0) {
                        html += '<div class="flex flex-wrap gap-2 pt-2">';
                        badges.forEach(b => {
                            html += `<span class="px-3 py-1 rounded-xl text-xs font-bold bg-white/10 text-white border border-white/15 flex items-center gap-1.5 shadow-2xs"><i class="fas fa-check-circle text-[#33FF57]"></i> ${escaparHtml(b)}</span>`;
                        });
                        html += '</div>';
                    }
                    html += '</div></div>';
                }

                // Videos responsivos
                if (videos.length > 0) {
                    videos.forEach(v => {
                        let embedUrl = v.embed || v.url;
                        const ytMatch = v.url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/|youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/i);
                        if (ytMatch && ytMatch[1]) {
                            embedUrl = `https://www.youtube-nocookie.com/embed/${ytMatch[1]}?rel=0`;
                        }
                        html += `
                        <div class="my-6 rounded-3xl overflow-hidden bg-slate-900 border border-slate-700 shadow-xl">
                            <div class="flex items-center justify-between px-5 py-3 bg-slate-800/90 border-b border-slate-700 text-white text-xs font-bold">
                                <span class="flex items-center gap-2 truncate max-w-md"><i class="fab fa-youtube text-red-500 text-sm"></i> ${escaparHtml(v.title)}</span>
                                <span class="text-[#33FF57] font-semibold text-[11px] hidden sm:inline">Recurso Oficial SISIG</span>
                            </div>
                            <div class="relative w-full aspect-video bg-black flex items-center justify-center">
                                <iframe class="w-full h-full" src="${embedUrl}" title="${escaparHtml(v.title)}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                            </div>
                        </div>`;
                    });
                }

                // Tarjetas temáticas en columnas responsivas
                if (pillarCards.length > 0) {
                    html += `<div class="grid grid-cols-1 ${pillarCards.length === 2 ? 'md:grid-cols-2' : 'md:grid-cols-3'} gap-6 my-6">`;
                    pillarCards.forEach(card => {
                        const isCalidad = card.title.includes('CALIDAD');
                        const isAmbiental = card.title.includes('AMBIENTE');
                        const borderCol = isCalidad ? 'border-sky-200 bg-sky-50/40' : (isAmbiental ? 'border-emerald-200 bg-emerald-50/40' : 'border-amber-200 bg-amber-50/40');
                        const badgeCol = isCalidad ? 'bg-sky-100 text-sky-800' : (isAmbiental ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800');
                        const checkCol = isCalidad ? 'text-sky-600' : (isAmbiental ? 'text-[#2E6F40]' : 'text-amber-600');

                        html += `
                        <div class="rounded-3xl p-6 border ${borderCol} shadow-xs flex flex-col justify-between transition-all hover:shadow-md">
                            <div>
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider ${badgeCol} mb-3">${escaparHtml(card.title)}</span>
                                <ul class="space-y-2.5 mt-2">`;
                        card.items.forEach(it => {
                            html += `<li class="flex items-start gap-2.5 text-xs sm:text-sm text-slate-700 leading-relaxed"><i class="fas fa-check-circle ${checkCol} mt-1 text-xs flex-shrink-0"></i><span>${escaparHtml(it)}</span></li>`;
                        });
                        html += `
                                </ul>
                            </div>
                        </div>`;
                    });
                    html += '</div>';
                }

                // Imágenes didácticas con visor modal
                if (images.length > 0) {
                    images.forEach(img => {
                        html += `
                        <div class="my-6 text-center">
                            <div class="inline-block relative rounded-2xl overflow-hidden border border-slate-200 shadow-sm max-w-full group">
                                <img src="${img.src}" alt="${escaparHtml(img.title || 'Esquema didáctico')}" class="max-h-[500px] w-auto max-w-full object-contain cursor-pointer transition-transform group-hover:scale-[1.02]" onclick="ampliarImagen(this.src)" title="Haz clic para ampliar">
                                <div class="absolute bottom-2 right-2 px-2.5 py-1 rounded-lg bg-slate-900/80 text-white text-[10px] font-bold pointer-events-none flex items-center gap-1">
                                    <i class="fas fa-search-plus"></i> Clic para ampliar
                                </div>
                            </div>
                        </div>`;
                    });
                }

                // Párrafos y listas
                if (paragraphs.length > 0) {
                    paragraphs.forEach(p => {
                        if (p.type === 'list') {
                            html += '<ul class="space-y-2 my-4 pl-4 list-disc text-slate-700 text-sm leading-relaxed">';
                            p.items.forEach(it => {
                                html += `<li>${escaparHtml(it)}</li>`;
                            });
                            html += '</ul>';
                        } else {
                            html += `<p class="text-slate-700 text-sm sm:text-base leading-relaxed my-3">${escaparHtml(p.text)}</p>`;
                        }
                    });
                }

                // Pie de página institucional
                if (footerText || footerCode) {
                    html += `
                    <div class="mt-8 pt-5 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-400">
                        <span>${escaparHtml(footerText || 'SERVICIO NACIONAL DE APRENDIZAJE - SENA • REGIONAL DISTRITO CAPITAL')}</span>
                        <span class="font-bold text-[#2E6F40]">${escaparHtml(footerCode || 'CÓDIGO: SIG-IND-01')}</span>
                    </div>`;
                }

                html += '</div>';
                return html;
            }

            async function guardarLienzoEnBackend() {
                try {
                    canvas.discardActiveObject();
                    canvas.renderAll();
                } catch(e) {}

                let canvasJSON = {};
                try {
                    canvasJSON = canvas.toJSON([
                        'lockMovementX', 'lockMovementY', 'lockRotation', 'lockScalingX', 'lockScalingY',
                        'hasControls', 'selectable', 'clipPath',
                        'isFrame', 'frameType', 'hasImage', 'hasVideo', 'isVideoFrame', 'innerImageSrc', 'innerZoom',
                        'frameBorderColor', 'frameBorderWidth', 'frameBorderStyle',
                        'isFrameBorder', 'isFrameBase', 'isFrameDecoration', 'isFramePlaceholder',
                        'isVideoCard', 'videoUrl', 'embedUrl', 'videoTitle', 'videoType'
                    ]);
                    canvasJSON.canvas_width = canvas.width || 960;
                    canvasJSON.canvas_height = canvas.height || 960;
                    canvasJSON.canvas_aspect_label = window.currentLienzoAspect || '1:1';
                } catch(e) {
                    console.warn('Aviso serializando canvas:', e);
                }

                // Generar contenido HTML didáctico semántico para el aprendiz (NO una presentación tipo diapositiva)
                let contenidoHtml = '';
                try {
                    contenidoHtml = generarContenidoHtmlDidactico(canvas);
                } catch (htmlErr) {
                    console.warn('Aviso generando HTML didáctico:', htmlErr);
                }

                const bSave = document.getElementById('btn-save-canvas');
                const bSaveText = document.getElementById('btn-save-text');
                const iSave = document.getElementById('icon-save');
                const lSavedTime = document.getElementById('last-saved-time');

                if (bSave) bSave.disabled = true;
                if (bSaveText) bSaveText.textContent = 'Guardando...';
                if (iSave) iSave.className = 'fas fa-spinner fa-spin text-xs';

                const targetModuleId = moduleId || '{{ $module->id ?? 1 }}';
                const token = csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || "{{ csrf_token() }}";
                const saveEndpoints = [
                    `/sisig/editor/modules/${targetModuleId}/canvas`
                ];

                let saveSuccess = false;
                let lastError = null;

                for (const url of saveEndpoints) {
                    try {
                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ 
                                canvas_state: canvasJSON,
                                contenido_html: contenidoHtml,
                                id: targetModuleId,
                                module_id: targetModuleId
                            })
                        });

                        if (response.ok) {
                            const result = await response.json();
                            if (result && result.success !== false) {
                                saveSuccess = true;
                                break;
                            } else {
                                lastError = result?.message || 'Error del servidor.';
                            }
                        } else if (response.status === 403) {
                            const errData = await response.json().catch(() => ({}));
                            lastError = errData.message || 'Permiso denegado.';
                        } else {
                            lastError = `HTTP ${response.status}: Error al guardar.`;
                        }
                    } catch (netErr) {
                        lastError = netErr.message;
                    }
                }

                if (saveSuccess) {
                    if (bSaveText) bSaveText.textContent = '¡Guardado!';
                    if (iSave) iSave.className = 'fas fa-check text-xs';
                    if (lSavedTime) {
                        lSavedTime.innerHTML = '<i class="fas fa-check-circle"></i> Guardado: ' + new Date().toLocaleTimeString();
                    }

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Módulo guardado exitosamente',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }

                    // Abrir modal oficial con opciones (Salir a Gestión de Módulos / Seguir Editando)
                    abrirModalGuardado();

                    setTimeout(() => {
                        if (bSaveText) bSaveText.textContent = 'Guardar';
                        if (iSave) iSave.className = 'fas fa-save text-xs';
                        if (bSave) bSave.disabled = false;
                    }, 2500);
                } else {
                    console.error('Error al guardar canvas:', lastError);
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Error al guardar', lastError || 'Error de conexión con el servidor.', 'error');
                    } else {
                        alert('Error al guardar: ' + (lastError || 'Error de conexión.'));
                    }
                    if (bSaveText) bSaveText.textContent = 'Guardar';
                    if (iSave) iSave.className = 'fas fa-save text-xs';
                    if (bSave) bSave.disabled = false;
                }
            }
            window.guardarLienzoEnBackend = guardarLienzoEnBackend;

            // Controladores del Modal de Guardado con Tarjetas Oficiales SISIG
            window.abrirModalGuardado = function() {
                const modal = document.getElementById('modal-guardado-exito');
                if (modal) {
                    modal.style.display = 'flex';
                    modal.classList.remove('hidden');
                }
            };

            window.cerrarModalGuardado = function() {
                const modal = document.getElementById('modal-guardado-exito');
                if (modal) {
                    modal.style.display = 'none';
                    modal.classList.add('hidden');
                }
            };

            const modalGuardadoElement = document.getElementById('modal-guardado-exito');
            if (modalGuardadoElement) {
                modalGuardadoElement.addEventListener('click', function(e) {
                    if (e.target === modalGuardadoElement) {
                        cerrarModalGuardado();
                    }
                });
            }

            // -------------------------------------------------------------
            // 16. CONTROLES DE ZOOM Y VISTA PREVIA
            // -------------------------------------------------------------
            const btnExport = document.getElementById('btn-export-png');
            if (btnExport) {
                btnExport.addEventListener('click', function () {
                    canvas.discardActiveObject();
                    canvas.renderAll();

                    const dataURL = canvas.toDataURL({ format: 'png', quality: 1, multiplier: 2 });
                    const link = document.createElement('a');
                    link.download = `modulo-sisig-${moduleId}-${Date.now()}.png`;
                    link.href = dataURL;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    Swal.fire({
                        toast: true, position: 'top-end', icon: 'success',
                        title: 'Imagen PNG descargada', showConfirmButton: false, timer: 2000
                    });
                });
            }

            // Conectar botones de Zoom y Auto-ajuste
            document.getElementById('btn-zoom-in')?.addEventListener('click', () => applyZoom(currentZoom + 0.1));
            document.getElementById('btn-zoom-out')?.addEventListener('click', () => applyZoom(currentZoom - 0.1));
            document.getElementById('btn-zoom-fit')?.addEventListener('click', autoAjustarLienzo);

            window.addEventListener('resize', autoAjustarLienzo);
            setTimeout(autoAjustarLienzo, 100);

            // Cambio de fondo
            window.cambiarFondo = function(color) {
                canvas.setBackgroundColor(color, function () { 
                    if (typeof sincronizarFondoVideos === 'function') {
                        sincronizarFondoVideos(color);
                    }
                    canvas.renderAll(); 
                    recordState(); 
                });
            };

            const customBgInput = document.getElementById('input-custom-bg');
            customBgInput.addEventListener('input', function () {
                document.getElementById('hex-custom-bg').textContent = this.value.toUpperCase();
                window.cambiarFondo(this.value);
            });

            function updateObjectsCount() {
                try {
                    const count = canvas ? canvas.getObjects().length : 0;
                    const el = document.getElementById('canvas-objects-count');
                    if (el) {
                        el.textContent = count + (count === 1 ? ' objeto' : ' objetos');
                    }
                } catch(e) {}
            }

        });
    </script>
</body>
</html>
