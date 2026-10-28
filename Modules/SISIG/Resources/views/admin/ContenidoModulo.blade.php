<x-sisig::layouts.admin title="Gestión de Contenido">
    <!-- Quill.js CSS (Local / Offline) -->
    <link href="{{ asset('modules/sisig/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <!-- Cropper.js CSS (Local / Offline) -->
    <link rel="stylesheet" href="{{ asset('modules/sisig/vendor/cropperjs/cropper.min.css') }}">
    <style>
        .cropper-view-box {
            outline: 2px solid #ffffff !important;
            outline-color: #ffffff !important;
        }
        .cropper-line {
            background-color: transparent !important;
        }
        .cropper-point {
            background-color: #ffffff !important;
        }
        .cropper-point.point-se, .cropper-point.point-sw, .cropper-point.point-nw, .cropper-point.point-ne {
            width: 14px !important;
            height: 14px !important;
            background-color: #10b981 !important;
            border-radius: 2px;
        }
        .cropper-dashed {
            border-color: rgba(255, 255, 255, 0.45) !important;
        }
        .cropper-modal {
            background-color: rgba(0, 0, 0, 0.88) !important;
        }

        /* =========================================================
           BARRA DE HERRAMIENTAS QUILL (Fondo Azul Institucional Sidebar)
           ========================================================= */
        .ql-toolbar.ql-snow {
            border: 1px solid #1A2B4C !important;
            border-bottom: none !important;
            border-top-left-radius: 1rem !important;
            border-top-right-radius: 1rem !important;
            background-color: #1A2B4C !important;
            background: linear-gradient(135deg, #1A2B4C 0%, #20335A 100%) !important;
            padding: 0.75rem 1rem !important;
            display: flex !important;
            flex-wrap: wrap !important;
            align-items: center !important;
            gap: 4px !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1) !important;
            position: relative !important;
            z-index: 20 !important;
        }

        .ql-snow .ql-formats {
            display: inline-flex !important;
            align-items: center !important;
            gap: 2px !important;
            margin-right: 8px !important;
            padding-right: 8px !important;
            border-right: 1px solid rgba(255, 255, 255, 0.15) !important;
            position: relative !important;
        }
        .ql-snow .ql-formats:last-child {
            border-right: none !important;
            padding-right: 0 !important;
            margin-right: 0 !important;
        }

        /* Botones de acción estándar (B, I, U, S, listas, etc.) */
        .ql-snow.ql-toolbar button,
        .ql-snow .ql-toolbar button {
            width: 32px !important;
            height: 32px !important;
            padding: 6px !important;
            border: 1px solid transparent !important;
            border-radius: 8px !important;
            background: transparent !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            cursor: pointer !important;
            transition: all 0.15s ease !important;
        }

        .ql-snow.ql-toolbar button svg,
        .ql-snow .ql-toolbar button svg {
            width: 18px !important;
            height: 18px !important;
        }

        /* Íconos en blanco puro (#FFFFFF) sobre la barra oscura del sidebar */
        .ql-toolbar.ql-snow button .ql-stroke,
        .ql-toolbar.ql-snow .ql-picker-label .ql-stroke {
            stroke: #FFFFFF !important;
            stroke-width: 2 !important;
            transition: stroke 0.15s ease, stroke-width 0.15s ease !important;
        }
        .ql-toolbar.ql-snow button .ql-fill,
        .ql-toolbar.ql-snow .ql-picker-label .ql-fill {
            fill: #FFFFFF !important;
            transition: fill 0.15s ease !important;
        }

        /* Íconos dentro de los MENÚS DESPLEGABLES FLOTANTES (Fondo Blanco -> Trazos Azul Oscuro #1A2B4C) */
        .ql-snow .ql-picker-options .ql-stroke {
            stroke: #1A2B4C !important;
            stroke-width: 2 !important;
            transition: stroke 0.15s ease !important;
        }
        .ql-snow .ql-picker-options .ql-fill {
            fill: #1A2B4C !important;
            transition: fill 0.15s ease !important;
        }

        /* Hover de botones: Fondo translúcido blanco + Ícono Verde Neón (#33FF57) */
        .ql-snow.ql-toolbar button:hover,
        .ql-snow .ql-toolbar button:hover {
            background-color: rgba(255, 255, 255, 0.15) !important;
            border-color: rgba(255, 255, 255, 0.25) !important;
        }
        .ql-snow.ql-toolbar button:hover .ql-stroke,
        .ql-snow .ql-toolbar button:hover .ql-stroke {
            stroke: #33FF57 !important;
        }
        .ql-snow.ql-toolbar button:hover .ql-fill,
        .ql-snow .ql-toolbar button:hover .ql-fill {
            fill: #33FF57 !important;
        }

        /* Estado Activo (.ql-active): Estilo Pill Verde Neón institucional del Sidebar */
        .ql-snow.ql-toolbar button.ql-active,
        .ql-snow .ql-toolbar button.ql-active {
            background-color: #33FF57 !important;
            border-color: #33FF57 !important;
            box-shadow: 0 2px 8px rgba(51, 255, 87, 0.35) !important;
        }
        .ql-snow.ql-toolbar button.ql-active .ql-stroke,
        .ql-snow .ql-toolbar button.ql-active .ql-stroke {
            stroke: #1A2B4C !important;
            stroke-width: 2.3 !important;
        }
        .ql-snow.ql-toolbar button.ql-active .ql-fill,
        .ql-snow .ql-toolbar button.ql-active .ql-fill {
            fill: #1A2B4C !important;
        }

        /* Botón personalizado de Fondo del Contenido */
        .ql-snow .ql-toolbar button.ql-fondo_contenido {
            width: 32px !important;
            height: 32px !important;
            padding: 6px !important;
            border: 1px solid transparent !important;
            background: transparent !important;
            cursor: pointer !important;
            border-radius: 8px !important;
            transition: all 0.15s ease !important;
        }
        .ql-snow .ql-toolbar button.ql-fondo_contenido:hover {
            background-color: rgba(255, 255, 255, 0.15) !important;
            border-color: rgba(255, 255, 255, 0.25) !important;
        }
        .ql-snow .ql-toolbar button.ql-fondo_contenido:hover .ql-stroke {
            stroke: #33FF57 !important;
        }
        .ql-snow .ql-toolbar button.ql-fondo_contenido:hover .ql-fill {
            fill: #33FF57 !important;
        }

        /* =========================================================
           1. SELECTORES DE TEXTO (TIPOGRAFÍA Y ENCABEZADOS)
           ========================================================= */
        .ql-snow .ql-picker:not(.ql-icon-picker) {
            height: 32px !important;
            display: inline-flex !important;
            align-items: center !important;
            position: relative !important;
        }

        /* Botón / Selector cerrado de texto */
        .ql-snow .ql-picker:not(.ql-icon-picker) .ql-picker-label {
            border: 1px solid rgba(255, 255, 255, 0.22) !important;
            border-radius: 8px !important;
            background-color: rgba(255, 255, 255, 0.1) !important;
            padding: 0 26px 0 10px !important;
            height: 32px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: flex-start !important;
            font-size: 13px !important;
            font-weight: 700 !important;
            color: #FFFFFF !important;
            cursor: pointer !important;
            transition: all 0.15s ease !important;
        }

        .ql-snow .ql-picker:not(.ql-icon-picker) .ql-picker-label:hover,
        .ql-snow .ql-picker:not(.ql-icon-picker).ql-expanded .ql-picker-label {
            border-color: #33FF57 !important;
            background-color: rgba(255, 255, 255, 0.18) !important;
            color: #33FF57 !important;
        }

        /* Flecha indicadora SOLO para text pickers */
        .ql-snow .ql-picker:not(.ql-icon-picker) .ql-picker-label svg {
            position: absolute !important;
            right: 8px !important;
            top: 50% !important;
            margin-top: -6px !important;
            width: 12px !important;
            height: 12px !important;
            stroke: #FFFFFF !important;
            transition: stroke 0.15s ease !important;
        }

        .ql-snow .ql-picker:not(.ql-icon-picker) .ql-picker-label:hover svg,
        .ql-snow .ql-picker:not(.ql-icon-picker).ql-expanded .ql-picker-label svg {
            stroke: #33FF57 !important;
        }

        /* Contenedor flotante de opciones de texto */
        .ql-snow .ql-picker:not(.ql-icon-picker).ql-expanded .ql-picker-options {
            background-color: #FFFFFF !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.18), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
            padding: 6px !important;
            margin-top: 6px !important;
            z-index: 100 !important;
            display: block !important;
            top: 100% !important;
            position: absolute !important;
        }

        /* Opciones dentro del menú desplegable de texto */
        .ql-snow .ql-picker:not(.ql-icon-picker) .ql-picker-options .ql-picker-item {
            padding: 8px 12px !important;
            margin: 2px 0 !important;
            border-radius: 8px !important;
            line-height: 1.35 !important;
            height: auto !important;
            min-height: 32px !important;
            display: flex !important;
            align-items: center !important;
            cursor: pointer !important;
            transition: all 0.15s ease !important;
            color: #1A2B4C !important;
        }

        .ql-snow .ql-picker:not(.ql-icon-picker) .ql-picker-options .ql-picker-item:hover,
        .ql-snow .ql-picker:not(.ql-icon-picker) .ql-picker-options .ql-picker-item.ql-selected {
            background-color: rgba(26, 43, 76, 0.08) !important;
            color: #2E6F40 !important;
        }

        /* =========================================================
           2. SELECTOR DE ICONOS (ALINEACIÓN: QL-ALIGN)
           ========================================================= */
        .ql-snow .ql-picker.ql-icon-picker {
            width: 32px !important;
            height: 32px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            position: relative !important;
        }

        /* Botón cerrado de alineación */
        .ql-snow .ql-picker.ql-icon-picker .ql-picker-label {
            width: 32px !important;
            height: 32px !important;
            padding: 6px !important;
            border-radius: 8px !important;
            border: 1px solid transparent !important;
            background-color: transparent !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            cursor: pointer !important;
            transition: all 0.15s ease !important;
        }

        .ql-snow .ql-picker.ql-icon-picker .ql-picker-label:hover,
        .ql-snow .ql-picker.ql-icon-picker.ql-expanded .ql-picker-label {
            background-color: rgba(255, 255, 255, 0.15) !important;
            border-color: rgba(255, 255, 255, 0.25) !important;
        }

        /* Icono SVG dentro del botón de alineación */
        .ql-snow .ql-picker.ql-icon-picker .ql-picker-label svg {
            position: static !important;
            margin: 0 !important;
            width: 18px !important;
            height: 18px !important;
            display: block !important;
        }
        .ql-snow .ql-picker.ql-icon-picker .ql-picker-label svg .ql-stroke {
            stroke: #FFFFFF !important;
        }

        .ql-snow .ql-picker.ql-icon-picker .ql-picker-label:hover svg .ql-stroke,
        .ql-snow .ql-picker.ql-icon-picker.ql-expanded .ql-picker-label svg .ql-stroke {
            stroke: #33FF57 !important;
        }

        /* Desplegable flotante de alineación (Fondo Blanco) */
        .ql-snow .ql-picker.ql-icon-picker.ql-expanded .ql-picker-options {
            background-color: #FFFFFF !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
            padding: 5px !important;
            margin-top: 6px !important;
            min-width: 44px !important;
            width: 44px !important;
            z-index: 100 !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            gap: 4px !important;
            top: 100% !important;
            position: absolute !important;
        }

        /* Opciones individuales de alineación dentro del menú */
        .ql-snow .ql-picker.ql-icon-picker .ql-picker-options .ql-picker-item {
            width: 32px !important;
            height: 32px !important;
            min-height: 32px !important;
            padding: 6px !important;
            margin: 0 !important;
            border-radius: 8px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            cursor: pointer !important;
            transition: all 0.15s ease !important;
        }

        .ql-snow .ql-picker.ql-icon-picker .ql-picker-options .ql-picker-item svg {
            width: 18px !important;
            height: 18px !important;
            display: block !important;
        }

        .ql-snow .ql-picker.ql-icon-picker .ql-picker-options .ql-picker-item svg .ql-stroke {
            stroke: #1A2B4C !important;
            stroke-width: 2 !important;
            transition: stroke 0.15s ease !important;
        }

        .ql-snow .ql-picker.ql-icon-picker .ql-picker-options .ql-picker-item:hover,
        .ql-snow .ql-picker.ql-icon-picker .ql-picker-options .ql-picker-item.ql-selected {
            background-color: rgba(26, 43, 76, 0.08) !important;
        }

        .ql-snow .ql-picker.ql-icon-picker .ql-picker-options .ql-picker-item:hover svg .ql-stroke,
        .ql-snow .ql-picker.ql-icon-picker .ql-picker-options .ql-picker-item.ql-selected svg .ql-stroke {
            stroke: #2E6F40 !important;
        }

        /* ---------------------------------------------------------
           1. SELECTOR DE ENCABEZADOS (Normal, Heading 1, 2, 3)
           --------------------------------------------------------- */
        .ql-snow .ql-picker.ql-header {
            width: 135px !important;
        }

        .ql-snow .ql-picker.ql-header .ql-picker-options {
            min-width: 155px !important;
        }

        /* Etiquetas para Encabezados */
        .ql-snow .ql-picker.ql-header .ql-picker-label::before {
            content: 'Normal' !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            line-height: 1.35 !important;
            color: #FFFFFF !important;
        }
        .ql-snow .ql-picker.ql-header .ql-picker-label:hover::before,
        .ql-snow .ql-picker.ql-header.ql-expanded .ql-picker-label::before {
            color: #33FF57 !important;
        }

        .ql-snow .ql-picker.ql-header .ql-picker-label[data-value="1"]::before {
            content: 'Heading 1' !important;
            color: #FFFFFF !important;
        }
        .ql-snow .ql-picker.ql-header .ql-picker-label[data-value="2"]::before {
            content: 'Heading 2' !important;
            color: #FFFFFF !important;
        }
        .ql-snow .ql-picker.ql-header .ql-picker-label[data-value="3"]::before {
            content: 'Heading 3' !important;
            color: #FFFFFF !important;
        }

        /* Opciones dentro del dropdown flotante */
        .ql-snow .ql-picker.ql-header .ql-picker-item::before {
            content: 'Normal' !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            line-height: 1.35 !important;
            color: #1A2B4C !important;
        }

        .ql-snow .ql-picker.ql-header .ql-picker-item[data-value="1"]::before {
            content: 'Heading 1' !important;
            font-size: 16px !important;
            font-weight: 800 !important;
            line-height: 1.35 !important;
            color: #1A2B4C !important;
        }

        .ql-snow .ql-picker.ql-header .ql-picker-item[data-value="2"]::before {
            content: 'Heading 2' !important;
            font-size: 14.5px !important;
            font-weight: 700 !important;
            line-height: 1.35 !important;
            color: #1A2B4C !important;
        }

        .ql-snow .ql-picker.ql-header .ql-picker-item[data-value="3"]::before {
            content: 'Heading 3' !important;
            font-size: 13.5px !important;
            font-weight: 600 !important;
            line-height: 1.35 !important;
            color: #1A2B4C !important;
        }

        /* Hover de Encabezados dentro del menú */
        .ql-snow .ql-picker.ql-header .ql-picker-item:hover::before,
        .ql-snow .ql-picker.ql-header .ql-picker-item.ql-selected::before {
            color: #2E6F40 !important;
        }

        /* ---------------------------------------------------------
           2. SELECTOR DE TIPOGRAFÍA (Arial, Calibri, Times New Roman)
           --------------------------------------------------------- */
        .ql-snow .ql-picker.ql-font {
            width: 145px !important;
        }

        .ql-snow .ql-picker.ql-font .ql-picker-options {
            min-width: 170px !important;
        }

        /* Etiqueta cerrada del selector de fuente */
        .ql-snow .ql-picker.ql-font .ql-picker-label::before {
            content: 'Tipografía' !important;
            font-size: 13px !important;
            font-weight: 700 !important;
            color: #FFFFFF !important;
        }
        .ql-snow .ql-picker.ql-font .ql-picker-label:hover::before,
        .ql-snow .ql-picker.ql-font.ql-expanded .ql-picker-label::before {
            color: #33FF57 !important;
        }

        .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="Arial"]::before {
            content: 'Arial' !important;
            font-family: Arial, sans-serif !important;
            color: #FFFFFF !important;
        }
        .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="Calibri"]::before {
            content: 'Calibri' !important;
            font-family: Calibri, 'Segoe UI', sans-serif !important;
            color: #FFFFFF !important;
        }
        .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="Times New Roman"]::before {
            content: 'Times New Roman' !important;
            font-family: 'Times New Roman', Times, serif !important;
            color: #FFFFFF !important;
        }

        /* Elementos dentro del desplegable de fuentes */
        .ql-snow .ql-picker.ql-font .ql-picker-item::before {
            content: 'Predeterminada' !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            font-family: inherit !important;
            color: #1A2B4C !important;
        }

        .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="Arial"]::before {
            content: 'Arial' !important;
            font-family: Arial, sans-serif !important;
            font-size: 13.5px !important;
            color: #1A2B4C !important;
        }

        .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="Calibri"]::before {
            content: 'Calibri' !important;
            font-family: Calibri, 'Segoe UI', sans-serif !important;
            font-size: 13.5px !important;
            color: #1A2B4C !important;
        }

        .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="Times New Roman"]::before {
            content: 'Times New Roman' !important;
            font-family: 'Times New Roman', Times, serif !important;
            font-size: 14px !important;
            color: #1A2B4C !important;
        }

        .ql-snow .ql-picker.ql-font .ql-picker-item:hover::before,
        .ql-snow .ql-picker.ql-font .ql-picker-item.ql-selected::before {
            color: #2E6F40 !important;
            font-weight: 700 !important;
        }

        /* =========================================================
           ÁREA DE TEXTO / CONTENEDOR PRINCIPAL DEL EDITOR
           ========================================================= */
        .ql-container.ql-snow {
            border: 1px solid #e2e8f0 !important;
            border-top: none !important;
            border-bottom-left-radius: 1rem !important;
            border-bottom-right-radius: 1rem !important;
            min-height: 480px !important;
            font-size: 1rem !important;
            font-family: 'Inter', system-ui, -apple-system, sans-serif !important;
            color: #1e293b !important;
            position: relative !important;
            background-color: #ffffff !important;
        }

        .ql-editor {
            padding: 1.75rem 2rem !important;
            min-height: 480px !important;
            line-height: 1.7 !important;
            position: relative !important;
            z-index: 1 !important;
            background-color: transparent !important;
        }

        .ql-editor.ql-blank::before {
            left: 2rem !important;
            color: #94a3b8 !important;
            font-style: normal !important;
        }

        /* Estilos de encabezados dentro del editor */
        .ql-editor h1 {
            font-size: 1.875rem !important;
            font-weight: 800 !important;
            color: #0f172a !important;
            margin-top: 1.5rem !important;
            margin-bottom: 0.75rem !important;
            line-height: 1.25 !important;
        }
        .ql-editor h2 {
            font-size: 1.5rem !important;
            font-weight: 700 !important;
            color: #1e293b !important;
            margin-top: 1.25rem !important;
            margin-bottom: 0.5rem !important;
            line-height: 1.3 !important;
        }
        .ql-editor h3 {
            font-size: 1.25rem !important;
            font-weight: 600 !important;
            color: #334155 !important;
            margin-top: 1rem !important;
            margin-bottom: 0.5rem !important;
            line-height: 1.35 !important;
        }
        .ql-editor p {
            margin-bottom: 0.85rem !important;
        }
    </style>

@php
    $routePrefix = $routePrefix ?? (request()->is('*editor*') ? 'sisig.editor' : 'sisig.admin');
    $isEditor = $routePrefix === 'sisig.editor';
    $tabRouteName = $isEditor ? 'sisig.editor.contenido.edit' : 'sisig.admin.modulos.contenido';
    $updateRouteName = $isEditor ? 'sisig.editor.contenido.update' : 'sisig.admin.modulos.contenido.update';
    $backRouteName = $isEditor ? 'sisig.editor.contenido.index' : 'sisig.admin.modulos.index';
@endphp

    <div class="space-y-6 sm:space-y-8 animate-fade-in pb-12">
        
        <!-- 1. Encabezado de la Sección (Estilo Limpio y Unificado) -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-5 border-b border-slate-200">
            <div class="flex items-start sm:items-center gap-3.5">
                <a href="{{ route($backRouteName) }}" 
                   title="Volver a Contenidos" 
                   class="w-10 h-10 rounded-2xl bg-white border border-slate-200 text-slate-500 hover:text-[#2E6F40] hover:border-emerald-200 hover:bg-emerald-50 transition-all flex items-center justify-center shadow-xs cursor-pointer flex-shrink-0 mt-1 sm:mt-0">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <div class="flex items-center gap-2 text-xs font-bold text-[#2E6F40] uppercase tracking-wider mb-1">
                        <i class="fas fa-file-alt"></i>
                        <span>{{ $isEditor ? 'Panel Editorial SISIG' : 'Administración SIG' }}</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-[#343A40] tracking-tight flex flex-wrap items-center gap-2">
                        <span>Edición de Contenido:</span>
                        <span class="text-[#2E6F40]">{{ $moduloSeleccionado->titulo }}</span>
                    </h1>
                    <p class="text-xs text-slate-500 mt-1 font-medium">
                        Redacta y formatea el material didáctico, guías y contenido interactivo de esta sección usando el editor enriquecido.
                    </p>
                </div>
            </div>
        </div>

        <!-- 2. Alerta de Permisos -->
        <div class="bg-emerald-50 border border-emerald-200 px-5 py-3.5 rounded-2xl text-xs sm:text-sm flex items-center gap-3 shadow-sm text-emerald-800">
            <div class="w-6 h-6 rounded-full bg-[#2E6F40] flex items-center justify-center text-white flex-shrink-0">
                <i class="fas fa-info text-xs"></i>
            </div>
            <p>
                <span class="font-bold">Editor interactivo activo:</span> Puedes añadir texto con formato, enlaces, listas, imágenes, videos y configurar una imagen de fondo con transparencia.
            </p>
        </div>

        <!-- 3. Título de Secciones -->
        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-4 flex items-center gap-2">
            <i class="fas fa-layer-group text-slate-400"></i> SELECCIONA LA SECCIÓN A EDITAR
        </h3>

        <!-- 4. Pestañas de Módulos (Tabs Dinámicos) -->
        <div class="flex flex-wrap gap-2.5 pb-2">
            @foreach($modulos as $mod)
                <a href="{{ route($tabRouteName, $mod->id) }}" 
                   class="flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold border transition-all 
                   {{ $moduloSeleccionado->id == $mod->id ? 'bg-[#1A2B4C] text-white border-[#1A2B4C] shadow-sm' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
                   
                   @if($mod->estado == 'publicado')
                        <i class="fas fa-check-circle {{ $moduloSeleccionado->id == $mod->id ? 'text-[#33FF57]' : 'text-emerald-500' }}"></i>
                   @elseif($mod->estado == 'inactivo')
                        <i class="fas fa-lock text-slate-400"></i>
                   @else
                        <i class="fas fa-pencil-alt text-amber-500"></i>
                   @endif
                   
                   <span>{{ $mod->titulo }}</span>
                </a>
            @endforeach
        </div>

        <!-- 5. Editor de Contenido -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <!-- Header del Editor -->
            <form action="{{ route($updateRouteName, $moduloSeleccionado->id) }}" method="POST" id="editorForm">
                @csrf
                @method('PUT')
                
                <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <h2 class="text-base font-bold text-[#343A40] flex items-center gap-2">
                            <i class="fas fa-edit text-[#2E6F40]"></i>
                            <span>Redacción de Contenido: <strong class="text-[#1A2B4C]">{{ $moduloSeleccionado->titulo }}</strong></span>
                        </h2>
                    </div>
                    
                    <div class="flex items-center gap-2.5">
                        <!-- Botón de Fondo adicional en la cabecera para máxima comodidad -->
                        <button type="button" onclick="abrirModalConfigFondo()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:border-emerald-300 hover:text-emerald-700 hover:bg-emerald-50/50 shadow-xs transition-all cursor-pointer">
                            <i class="fas fa-image text-emerald-600"></i>
                            <span>Fondo del Contenido</span>
                        </button>

                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full text-xs font-black text-[#1A2B4C] bg-[#33FF57] hover:bg-[#2ae049] shadow-md shadow-emerald-500/20 transition-all transform hover:-translate-y-0.5 cursor-pointer">
                            <i class="fas fa-save text-xs"></i> <span>Guardar Cambios</span>
                        </button>
                    </div>
                </div>

                <!-- Contenedor Quill -->
                <div class="p-6">
                    <!-- Campos ocultos para enviar el contenido y fondo -->
                    <input type="hidden" name="contenido_html" id="contenido_html" value="{{ $moduloSeleccionado->contenido_html }}">
                    <input type="hidden" name="fondo_imagen_recortada" id="fondo_imagen_recortada">
                    <input type="hidden" name="fondo_opacidad" id="fondo_opacidad" value="{{ $moduloSeleccionado->fondo_opacidad ?? 20 }}">
                    <input type="hidden" name="quitar_fondo" id="quitar_fondo" value="0">
                    
                    <!-- Editor Div (limpio y nativo) -->
                    <div id="editor">
                        {!! $moduloSeleccionado->contenido_html !!}
                    </div>
                </div>

                <!-- Footer del Editor -->
                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between text-xs font-medium text-slate-500 bg-slate-50/50 rounded-b-2xl">
                    <span id="charCount">0 caracteres</span>
                    <span class="flex items-center gap-1.5 text-emerald-600">
                        <i class="fas fa-check-circle"></i> Último guardado: {{ $moduloSeleccionado->updated_at->diffForHumans() }}
                    </span>
                </div>
            </form>
        </div>

    </div>

    <!-- Modal para Configurar Fondo y Transparencia del Contenido -->
    <div id="modalConfigFondoContenido" style="display: none;" class="fixed inset-0 z-[110] flex items-center justify-center p-4">
        <!-- Backdrop oscuro -->
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="cerrarModalConfigFondo()"></div>

        <!-- Contenedor del Modal -->
        <div class="relative w-full max-w-lg bg-white rounded-3xl p-6 sm:p-7 shadow-2xl border border-slate-200 animate-scale-in max-h-[90vh] overflow-y-auto">
            
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-sena-green flex items-center justify-center text-lg shadow-sm border border-emerald-100">
                        <i class="fas fa-image"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Fondo del Contenido</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Añade una imagen de fondo y ajusta su transparencia</p>
                    </div>
                </div>
                <button type="button" onclick="cerrarModalConfigFondo()" class="text-slate-400 hover:text-slate-600 p-2 rounded-xl hover:bg-slate-100 transition-colors cursor-pointer">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <div class="mt-5 space-y-5">
                <input type="file" id="input-archivo-fondo" accept="image/png, image/jpeg, image/jpg, image/webp" class="hidden">

                <!-- Dropzone / Selector de Imagen -->
                <div id="dropzone-fondo-modulo" onclick="document.getElementById('input-archivo-fondo').click()"
                     class="{{ $moduloSeleccionado->fondo_imagen ? 'hidden' : '' }} border-2 border-dashed border-slate-200 hover:border-emerald-400 bg-slate-50 hover:bg-emerald-50/40 rounded-2xl p-6 text-center cursor-pointer transition-all group">
                    <div class="flex flex-col items-center justify-center gap-2">
                        <div class="w-12 h-12 rounded-2xl bg-white shadow-xs border border-slate-200 flex items-center justify-center text-slate-400 group-hover:text-sena-green group-hover:scale-110 transition-all">
                            <i class="fas fa-cloud-upload-alt text-xl"></i>
                        </div>
                        <div class="text-xs sm:text-sm font-bold text-slate-700 group-hover:text-emerald-700">
                            Haz clic para seleccionar la imagen de fondo
                        </div>
                        <p class="text-[11px] text-slate-400">Podrás encuadrarla y recortarla a tu gusto (JPG, PNG, WEBP)</p>
                    </div>
                </div>

                <!-- Preview de la imagen de fondo actual/nueva -->
                <div id="preview-fondo-container" class="{{ $moduloSeleccionado->fondo_imagen ? '' : 'hidden' }} p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                    <div class="flex items-center gap-4">
                        <img id="preview-fondo-miniatura" 
                             src="{{ $moduloSeleccionado->fondo_imagen ? asset($moduloSeleccionado->fondo_imagen) : '' }}" 
                             alt="Fondo actual" 
                             class="w-20 h-16 object-cover rounded-xl border border-slate-200 shadow-sm flex-shrink-0 bg-white">
                        <div class="flex-grow min-w-0">
                            <div class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                <i class="fas fa-check-circle text-emerald-600 text-xs"></i>
                                <span>Imagen de fondo asignada</span>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-0.5">Se verá detrás del texto con la transparencia elegida.</p>
                            <div class="flex items-center gap-2 mt-2">
                                <button type="button" onclick="document.getElementById('input-archivo-fondo').click()" class="text-xs text-sena-green hover:underline font-bold flex items-center gap-1 cursor-pointer">
                                    <i class="fas fa-exchange-alt"></i> Cambiar imagen
                                </button>
                                <span class="text-slate-300">|</span>
                                <button type="button" onclick="quitarFondoContenido()" class="text-xs text-rose-500 hover:text-rose-700 font-medium flex items-center gap-1 cursor-pointer">
                                    <i class="fas fa-trash-alt"></i> Quitar fondo
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Control Deslizante de Transparencia / Opacidad -->
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl space-y-2.5">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                            <i class="fas fa-adjust text-slate-400"></i>
                            <span>Nivel de Transparencia</span>
                        </label>
                        <span id="label-opacidad-display" class="text-sm font-extrabold text-sena-green bg-emerald-50 px-2.5 py-0.5 rounded-lg border border-emerald-200">
                            {{ $moduloSeleccionado->fondo_opacidad ?? 20 }}%
                        </span>
                    </div>

                    <input type="range" id="slider-opacidad-control" min="5" max="95" step="5" 
                           value="{{ $moduloSeleccionado->fondo_opacidad ?? 20 }}" 
                           class="w-full accent-sena-green cursor-pointer h-2 bg-slate-200 rounded-lg">
                    
                    <div class="flex justify-between text-[10px] text-slate-400 font-medium">
                        <span>10% (Muy sutil)</span>
                        <span>20% (Recomendado)</span>
                        <span>50% (Marcado)</span>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="cerrarModalConfigFondo()" class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-slate-600 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 transition-all cursor-pointer">
                        Listo
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Interactivo de Recorte / Encuadre de Imagen de Fondo (Estilo Screenshot) -->
    <div id="modalRecortarFondo" style="display: none;" class="fixed inset-0 z-[130] flex flex-col bg-[#050811] text-white animate-fade-in select-none">
        <!-- Header superior discreto -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-800/80 bg-slate-950/60 z-10">
            <div class="flex items-center gap-2">
                <i class="fas fa-crop-alt text-emerald-400 text-sm"></i>
                <span class="text-xs sm:text-sm font-bold tracking-wide text-slate-200">Acomodar imagen de fondo</span>
            </div>
            <div class="text-xs text-slate-400 hidden sm:block">
                Arrastra para encuadrar o usa la rueda del ratón para zoom
            </div>
        </div>

        <!-- Área Central de Recorte con Cropper.js -->
        <div class="flex-grow relative flex items-center justify-center overflow-hidden p-4">
            <div class="w-full max-w-4xl h-[65vh] flex items-center justify-center">
                <img id="cropper-fondo-target" src="" alt="Ajustar fondo" class="max-w-full max-h-full block">
            </div>
        </div>

        <!-- Barra Inferior de Acciones (Fiel a la captura) -->
        <div class="px-8 py-5 border-t border-slate-800/80 bg-slate-950/90 flex items-center justify-between z-10">
            <!-- Botón Cancelar (Verde) -->
            <button type="button" onclick="cancelarRecorteFondo()" class="text-emerald-400 hover:text-emerald-300 font-bold text-sm sm:text-base px-3 py-2 transition-colors cursor-pointer">
                Cancelar
            </button>

            <!-- Herramientas centrales: Rotar y Zoom -->
            <div class="flex items-center gap-3">
                <button type="button" onclick="cropperFondoRotar(-90)" title="Girar 90° hacia la izquierda" class="w-10 h-10 rounded-full bg-slate-900 border border-slate-700 hover:border-slate-500 text-slate-300 hover:text-white flex items-center justify-center transition-all cursor-pointer">
                    <i class="fas fa-undo-alt text-xs"></i>
                </button>
                <button type="button" onclick="cropperFondoRotar(90)" title="Rotar imagen" class="w-12 h-12 rounded-full bg-slate-800 border border-slate-600 hover:border-emerald-400 text-white hover:text-emerald-400 flex items-center justify-center shadow-lg transition-all cursor-pointer text-base">
                    <i class="fas fa-sync-alt"></i>
                </button>
                <button type="button" onclick="cropperFondoZoom(0.1)" title="Acercar (Zoom +)" class="w-10 h-10 rounded-full bg-slate-900 border border-slate-700 hover:border-slate-500 text-slate-300 hover:text-white flex items-center justify-center transition-all cursor-pointer">
                    <i class="fas fa-search-plus text-xs"></i>
                </button>
                <button type="button" onclick="cropperFondoZoom(-0.1)" title="Alejar (Zoom -)" class="w-10 h-10 rounded-full bg-slate-900 border border-slate-700 hover:border-slate-500 text-slate-300 hover:text-white flex items-center justify-center transition-all cursor-pointer">
                    <i class="fas fa-search-minus text-xs"></i>
                </button>
            </div>

            <!-- Botón OK (Verde) -->
            <button type="button" onclick="confirmarRecorteFondo()" class="text-emerald-400 hover:text-emerald-300 font-bold text-sm sm:text-base px-3 py-2 transition-colors cursor-pointer">
                OK
            </button>
        </div>
    </div>

    <!-- Cropper.js JS (Local / Offline) -->
    <script src="{{ asset('modules/sisig/vendor/cropperjs/cropper.min.js') }}"></script>
    <!-- Quill.js JS (Local / Offline) -->
    <script src="{{ asset('modules/sisig/vendor/quill/quill.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Configurar Attributor de Estilo en línea para Tipografía (font-family)
            // Esto asegura que al aplicar una fuente se genere style="font-family: ..." directamente en el HTML guardado en la base de datos
            var FontStyle = Quill.import('attributors/style/font');
            FontStyle.whitelist = ['Arial', 'Calibri', 'Times New Roman'];
            Quill.register(FontStyle, true);

            // 2. Configurar Attributor de Estilo en línea para Alineación de texto (text-align)
            var AlignStyle = Quill.import('attributors/style/align');
            Quill.register(AlignStyle, true);

            // 3. Registrar icono nativo de Quill para el botón de Fondo antes de inicializar
            var icons = Quill.import('ui/icons');
            icons['fondo_contenido'] = `
                <svg viewBox="0 0 18 18">
                    <rect class="ql-stroke" stroke-dasharray="2,1.5" height="13" width="14" x="2" y="2.5" rx="1.5"></rect>
                    <rect class="ql-stroke" height="7.5" width="9.5" x="4.25" y="5.25" rx="1"></rect>
                    <circle class="ql-fill" cx="6.5" cy="7.25" r="0.9"></circle>
                    <polyline class="ql-even ql-fill" points="5.5 11.5 5.5 10.5 7 9 8 10 10 8 12 10 12 11.5 5.5 11.5"></polyline>
                </svg>
            `;

            var toolbarOptions = [
                [{ 'font': [false, 'Arial', 'Calibri', 'Times New Roman'] }],
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['link', 'image', 'video', 'fondo_contenido'],   // Grupo multimedia con Fondo del contenido
                ['clean']                                         // remove formatting button
            ];

            var quill = new Quill('#editor', {
                modules: {
                    toolbar: {
                        container: toolbarOptions,
                        handlers: {
                            'fondo_contenido': function() {
                                window.abrirModalConfigFondo();
                            }
                        }
                    }
                },
                theme: 'snow',
                placeholder: 'Escribe aquí el contenido del módulo...'
            });

            // Inyectar capa de fondo detrás del contenido de .ql-editor dentro de .ql-container
            var qlContainer = quill.container;
            if (qlContainer) {
                qlContainer.style.position = 'relative';

                var fondoCapa = document.createElement('div');
                fondoCapa.id = 'editor-fondo-capa';
                fondoCapa.style.position = 'absolute';
                fondoCapa.style.inset = '0';
                fondoCapa.style.backgroundPosition = 'center';
                fondoCapa.style.backgroundSize = 'cover';
                fondoCapa.style.backgroundRepeat = 'no-repeat';
                fondoCapa.style.pointerEvents = 'none';
                fondoCapa.style.zIndex = '0';
                fondoCapa.style.transition = 'opacity 0.2s ease';
                fondoCapa.style.borderBottomLeftRadius = '0.75rem';
                fondoCapa.style.borderBottomRightRadius = '0.75rem';
                fondoCapa.style.opacity = '{{ ($moduloSeleccionado->fondo_opacidad ?? 20) / 100 }}';
                @if($moduloSeleccionado->fondo_imagen)
                    fondoCapa.style.backgroundImage = 'url("{{ asset($moduloSeleccionado->fondo_imagen) }}")';
                @endif

                qlContainer.insertBefore(fondoCapa, qlContainer.firstChild);
            }

            // Configurar el botón nativo en la barra de herramientas (limpio, sin texto que desborde)
            var btnFondo = document.querySelector('.ql-fondo_contenido');
            if (btnFondo) {
                btnFondo.setAttribute('type', 'button');
                btnFondo.setAttribute('title', 'Fondo del contenido (Imagen y Transparencia)');
                btnFondo.setAttribute('aria-label', 'Fondo del contenido');
                if (!btnFondo.querySelector('svg')) {
                    btnFondo.innerHTML = icons['fondo_contenido'];
                }
                btnFondo.onclick = function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    window.abrirModalConfigFondo();
                };
            }

            // Actualizar el input oculto y el contador de caracteres cuando el contenido cambia
            var hiddenInput = document.getElementById('contenido_html');
            var charCount = document.getElementById('charCount');

            // Set initial char count
            charCount.textContent = quill.getText().trim().length + ' caracteres';

            quill.on('text-change', function() {
                hiddenInput.value = quill.root.innerHTML;
                charCount.textContent = quill.getText().trim().length + ' caracteres';
            });

            // Antes de enviar el form, asegurar que el HTML esté en el input
            document.getElementById('editorForm').onsubmit = function() {
                hiddenInput.value = quill.root.innerHTML;
            };

            // Evento cambio de archivo para el fondo
            var inputArchivoFondo = document.getElementById('input-archivo-fondo');
            if (inputArchivoFondo) {
                inputArchivoFondo.addEventListener('change', function(e) {
                    var file = e.target.files[0];
                    if (file) {
                        if (!file.type.startsWith('image/')) {
                            if (window.mostrarAlertaBanner) {
                                window.mostrarAlertaBanner('Por favor selecciona un archivo de imagen válido.', 'error');
                            }
                            return;
                        }
                        var reader = new FileReader();
                        reader.onload = function(evt) {
                            currentFondoRawSrc = evt.target.result;
                            iniciarCropperFondo(currentFondoRawSrc);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Control Deslizante de Transparencia (En vivo en el editor)
            var sliderOpacidad = document.getElementById('slider-opacidad-control');
            if (sliderOpacidad) {
                sliderOpacidad.addEventListener('input', function(e) {
                    var val = e.target.value;
                    var labelDisplay = document.getElementById('label-opacidad-display');
                    if (labelDisplay) labelDisplay.textContent = val + '%';
                    
                    var inputHidden = document.getElementById('fondo_opacidad');
                    if (inputHidden) inputHidden.value = val;
                    
                    var editorFondoCapa = document.getElementById('editor-fondo-capa');
                    if (editorFondoCapa) {
                        editorFondoCapa.style.opacity = (val / 100);
                    }
                });
            }
        });

        // Click global con delegación para máxima confiabilidad
        document.addEventListener('click', function(e) {
            if (e.target.closest('.ql-fondo_contenido')) {
                e.preventDefault();
                e.stopPropagation();
                window.abrirModalConfigFondo();
            }
        });

        // ==========================================
        // FUNCIONES DE CONTROL DEL MODAL Y CROPPER
        // ==========================================
        let cropperFondoInstance = null;
        let currentFondoRawSrc = null;

        window.abrirModalConfigFondo = function() {
            var modal = document.getElementById('modalConfigFondoContenido');
            if (modal) {
                modal.style.display = 'flex';
                if (window.lockBodyScroll) window.lockBodyScroll(true);
            }
        };

        window.cerrarModalConfigFondo = function() {
            var modal = document.getElementById('modalConfigFondoContenido');
            if (modal) {
                modal.style.display = 'none';
                if (window.lockBodyScroll) window.lockBodyScroll(false);
            }
        };

        function iniciarCropperFondo(imgSrc) {
            var cropperFondoTarget = document.getElementById('cropper-fondo-target');
            var modalRecortarFondo = document.getElementById('modalRecortarFondo');
            
            if (cropperFondoTarget && modalRecortarFondo) {
                cropperFondoTarget.src = imgSrc;
                modalRecortarFondo.style.display = 'flex';
                if (window.lockBodyScroll) window.lockBodyScroll(true);

                if (cropperFondoInstance) {
                    cropperFondoInstance.destroy();
                }

                cropperFondoInstance = new Cropper(cropperFondoTarget, {
                    aspectRatio: 16 / 9,
                    viewMode: 1,
                    guides: true,
                    center: true,
                    highlight: false,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    autoCropArea: 0.9,
                    responsive: true,
                    background: false
                });
            }
        }

        function cropperFondoRotar(grados) {
            if (cropperFondoInstance) {
                cropperFondoInstance.rotate(grados);
            }
        }

        function cropperFondoZoom(ratio) {
            if (cropperFondoInstance) {
                cropperFondoInstance.zoom(ratio);
            }
        }

        function cancelarRecorteFondo() {
            var modalRecortarFondo = document.getElementById('modalRecortarFondo');
            if (modalRecortarFondo) {
                modalRecortarFondo.style.display = 'none';
                if (window.lockBodyScroll) window.lockBodyScroll(false);
            }
            if (cropperFondoInstance) {
                cropperFondoInstance.destroy();
                cropperFondoInstance = null;
            }
            var inputArchivo = document.getElementById('input-archivo-fondo');
            if (inputArchivo) inputArchivo.value = '';
        }

        function confirmarRecorteFondo() {
            if (!cropperFondoInstance) return;

            var canvas = cropperFondoInstance.getCroppedCanvas({
                width: 1600,
                height: 900,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high'
            });

            var croppedBase64 = canvas.toDataURL('image/png');

            var inputHiddenFondo = document.getElementById('fondo_imagen_recortada');
            if (inputHiddenFondo) inputHiddenFondo.value = croppedBase64;

            var inputHiddenQuitar = document.getElementById('quitar_fondo');
            if (inputHiddenQuitar) inputHiddenQuitar.value = '0';

            var previewMiniatura = document.getElementById('preview-fondo-miniatura');
            if (previewMiniatura) previewMiniatura.src = croppedBase64;

            var dropzone = document.getElementById('dropzone-fondo-modulo');
            if (dropzone) dropzone.classList.add('hidden');

            var previewContainer = document.getElementById('preview-fondo-container');
            if (previewContainer) previewContainer.classList.remove('hidden');

            var editorFondoCapa = document.getElementById('editor-fondo-capa');
            var sliderOpacidad = document.getElementById('slider-opacidad-control');
            if (editorFondoCapa) {
                editorFondoCapa.style.backgroundImage = 'url("' + croppedBase64 + '")';
                var op = sliderOpacidad ? sliderOpacidad.value : 20;
                editorFondoCapa.style.opacity = (op / 100);
            }

            var modalRecortarFondo = document.getElementById('modalRecortarFondo');
            if (modalRecortarFondo) {
                modalRecortarFondo.style.display = 'none';
                if (window.lockBodyScroll) window.lockBodyScroll(false);
            }

            cropperFondoInstance.destroy();
            cropperFondoInstance = null;
        }

        function quitarFondoContenido() {
            var inputHiddenQuitar = document.getElementById('quitar_fondo');
            if (inputHiddenQuitar) inputHiddenQuitar.value = '1';

            var inputHiddenFondo = document.getElementById('fondo_imagen_recortada');
            if (inputHiddenFondo) inputHiddenFondo.value = '';

            var inputArchivo = document.getElementById('input-archivo-fondo');
            if (inputArchivo) inputArchivo.value = '';

            currentFondoRawSrc = null;

            var previewMiniatura = document.getElementById('preview-fondo-miniatura');
            if (previewMiniatura) previewMiniatura.src = '';

            var previewContainer = document.getElementById('preview-fondo-container');
            if (previewContainer) previewContainer.classList.add('hidden');

            var dropzone = document.getElementById('dropzone-fondo-modulo');
            if (dropzone) dropzone.classList.remove('hidden');

            var editorFondoCapa = document.getElementById('editor-fondo-capa');
            if (editorFondoCapa) {
                editorFondoCapa.style.backgroundImage = 'none';
            }
        }
    </script>
</x-sisig::layouts.admin>
