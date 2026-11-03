<x-sisig::layouts.admin title="Configuración de la Plataforma">
    <!-- Quill.js CSS (Local / Offline) -->
    <link href="{{ asset('modules/sisig/vendor/quill/quill.snow.css') }}" rel="stylesheet">

    <style>
        /* =========================================================
           ESTILOS OFICIALES DEL SISTEMA SISIG (SENA EMPRESA)
           Paleta Oficial del Sistema:
           - Dark Navy: #1A2B4C
           - Forest Green (SENA Verde): #2E6F40 (hover: #245833)
           - Neon Green: #33FF57 (hover: #2ae049)
           - Off-White Fondo: #F4F5F7
           - Texto Principal: #343A40
           - Blanco Tarjetas: #FFFFFF
           ========================================================= */

        .card-sena {
            background-color: #FFFFFF;
            border: 1px solid rgba(226, 232, 240, 0.9);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-sena:hover {
            border-color: rgba(46, 111, 64, 0.3);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -2px rgba(0, 0, 0, 0.07);
        }

        .subcard-sena {
            background-color: #F8FAFC;
            border: 1px solid #E2E8F0;
            transition: all 0.2s ease;
        }

        .subcard-sena:hover {
            background-color: #FFFFFF;
            border-color: rgba(46, 111, 64, 0.4);
            box-shadow: 0 2px 6px -1px rgba(0, 0, 0, 0.06);
        }

        .input-sena {
            background-color: #F8FAFC;
            border: 1px solid #CBD5E1;
            color: #343A40;
            transition: all 0.2s ease-in-out;
        }

        .input-sena:focus {
            background-color: #FFFFFF;
            border-color: #2E6F40;
            box-shadow: 0 0 0 3px rgba(46, 111, 64, 0.15);
            outline: none;
        }

        /* Botón de Acción Principal (Verde SENA Institucional) */
        .btn-sena-primary {
            background: linear-gradient(135deg, #2E6F40 0%, #245833 100%);
            color: #FFFFFF;
            box-shadow: 0 4px 14px rgba(46, 111, 64, 0.25);
            transition: all 0.2s ease;
        }

        .btn-sena-primary:hover {
            background: linear-gradient(135deg, #245833 0%, #1c4427 100%);
            box-shadow: 0 6px 20px rgba(46, 111, 64, 0.35);
            transform: translateY(-1px);
        }

        .btn-sena-primary:active {
            transform: translateY(0);
        }

        /* Switches estilo iOS con Verde Institucional SENA */
        .switch-sena-input:checked ~ .switch-sena-track {
            background-color: #2E6F40;
            border-color: #2E6F40;
            box-shadow: 0 0 10px rgba(46, 111, 64, 0.3);
        }

        .switch-sena-input:checked ~ .switch-sena-track .switch-sena-thumb {
            transform: translateX(1.25rem);
            background-color: #FFFFFF;
        }

        /* =========================================================
           BARRA DE HERRAMIENTAS QUILL (Fondo Azul Dark Navy Institucional)
           ========================================================= */
        .ql-toolbar.ql-snow {
            border: 1px solid #1A2B4C !important;
            border-bottom: none !important;
            border-top-left-radius: 1rem !important;
            border-top-right-radius: 1rem !important;
            background: linear-gradient(135deg, #1A2B4C 0%, #20335A 100%) !important;
            padding: 0.75rem 1rem !important;
        }

        .ql-snow .ql-stroke {
            stroke: #FFFFFF !important;
        }

        .ql-snow .ql-fill {
            fill: #FFFFFF !important;
        }

        .ql-snow .ql-picker {
            color: #FFFFFF !important;
        }

        .ql-snow .ql-picker-options {
            background-color: #1A2B4C !important;
            border: 1px solid #243B66 !important;
            border-radius: 0.75rem !important;
            color: #FFFFFF !important;
        }

        .ql-snow .ql-picker-item:hover {
            color: #33FF57 !important;
        }

        .ql-container.ql-snow {
            background-color: #FFFFFF !important;
            border: 1px solid #CBD5E1 !important;
            border-top: none !important;
            border-bottom-left-radius: 1rem !important;
            border-bottom-right-radius: 1rem !important;
            color: #343A40 !important;
            font-family: inherit !important;
            font-size: 0.9rem !important;
            min-height: 220px;
        }

        /* Micro-animación suave de transición para cambios en vivo */
        .fade-in-switches {
            animation: fadeInSwitches 0.25s ease-out;
        }
        @keyframes fadeInSwitches {
            from { opacity: 0.6; transform: translateY(3px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <div class="space-y-6 sm:space-y-8 animate-fade-in pb-16 w-full max-w-[1600px] mx-auto">
        
        <!-- 1. Banner Principal Ejecutivo con Colores Oficiales (Dark Navy + Neon Green) -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#1A2B4C] via-[#1E3A5F] to-[#243B66] border border-[#243B66] p-7 sm:p-9 text-white shadow-xl">
            <div class="absolute -top-24 -right-24 w-80 h-80 bg-[#33FF57]/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-[#00BCD4]/15 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-[#33FF57] text-xs font-black tracking-wider uppercase">
                        <i class="fas fa-sliders-h text-xs"></i>
                        <span>Panel de Administración Maestro</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-tight">
                        Configuración de la Plataforma SISIG
                    </h1>
                    <p class="text-slate-300 text-xs sm:text-sm max-w-2xl leading-relaxed">
                        Administra la identidad corporativa de <strong class="text-white">SENA Empresa</strong>, los textos legales e institucionales y las asignaciones y restricciones operativas para los editores.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2.5 bg-white/10 backdrop-blur-md px-4 py-2 rounded-full border border-white/15 text-white shadow-xs">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#33FF57] animate-pulse"></span>
                        <span class="text-xs font-bold text-slate-100">
                            Rol: <strong class="text-white">Administrador SISIG</strong>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Formulario Maestro de Configuración -->
        <form action="{{ route('sisig.admin.configuracion.update') }}" 
              method="POST" 
              enctype="multipart/form-data" 
              id="form-configuracion-plataforma" 
              class="space-y-6 sm:space-y-8">
            @csrf
            @method('PUT')

            <!-- Inputs Ocultos de Estado Reactivo -->
            <input type="hidden" name="editor_id" id="input-editor-id-hidden" value="{{ $editorSeleccionado?->id }}">
            <input type="hidden" name="permisos_editores_json" id="input-permisos-json" value="">

            <!-- FILA 1: IDENTIDAD VISUAL + TEXTOS LEGALES -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start">
                
                <!-- TARJETA 1: IDENTIDAD VISUAL (5 Columnas) -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="card-sena rounded-3xl p-6 sm:p-7 space-y-6 h-full flex flex-col justify-between">
                        <div class="space-y-6">
                            <div class="flex items-center gap-3.5 border-b border-slate-100 pb-4">
                                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-[#2E6F40] flex items-center justify-center font-bold text-base flex-shrink-0">
                                    <i class="fas fa-palette"></i>
                                </div>
                                <div>
                                    <h2 class="text-base font-extrabold text-[#1A2B4C] tracking-tight">1. Identidad Visual</h2>
                                    <p class="text-xs text-slate-500">Nombre institucional y logotipo oficial</p>
                                </div>
                            </div>

                            <!-- Nombre de la Plataforma -->
                            <div class="space-y-2">
                                <label for="nombre_plataforma" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                    Nombre Oficial del Sistema <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="fas fa-university text-xs"></i>
                                    </span>
                                    <input type="text" 
                                           name="nombre_plataforma" 
                                           id="nombre_plataforma" 
                                           value="{{ old('nombre_plataforma', $configuracion->nombre_plataforma) }}"
                                           required
                                           placeholder="Ej: SISIG - SENA Empresa"
                                           class="input-sena w-full pl-10 pr-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold">
                                </div>
                                @error('nombre_plataforma')
                                    <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Logotipo Oficial con Previsualizador -->
                            <div class="space-y-3">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                    Logotipo Oficial (Formatos: JPG / PNG)
                                </label>

                                <div class="relative group p-6 rounded-2xl bg-slate-50 border-2 border-dashed border-slate-300 hover:border-[#2E6F40] transition-all text-center flex flex-col items-center justify-center gap-3.5 cursor-pointer" 
                                     onclick="document.getElementById('input-logo-file').click()"
                                     title="Haz clic para seleccionar un nuevo logotipo">
                                    
                                    <input type="file" 
                                           name="logo" 
                                           id="input-logo-file" 
                                           accept="image/png,image/jpeg,image/jpg" 
                                           class="hidden" 
                                           onchange="actualizarPrevisualizacionLogo(event)">
                                    
                                    <div class="w-24 h-24 rounded-2xl bg-white border border-slate-200 flex items-center justify-center p-3 shadow-xs relative overflow-hidden group-hover:scale-105 transition-transform duration-200">
                                        <img id="preview-logo-imagen" 
                                             src="{{ asset($configuracion->logo_path ?? 'modules/sisig/images/logo.png') }}" 
                                             alt="Logotipo Actual" 
                                             class="w-full h-full object-contain filter drop-shadow-xs">
                                        
                                        <div class="absolute inset-0 bg-[#1A2B4C]/70 opacity-0 group-hover:opacity-100 flex flex-col items-center justify-center text-white text-xs font-bold transition-opacity">
                                            <i class="fas fa-camera text-base mb-1"></i>
                                            <span>Cambiar</span>
                                        </div>
                                    </div>

                                    <div class="space-y-1">
                                        <p class="text-xs font-bold text-[#1A2B4C] group-hover:text-[#2E6F40] transition-colors">
                                            Cargar nuevo archivo de imagen
                                        </p>
                                        <p class="text-[11px] text-slate-500">
                                            Recomendado con fondo transparente (Máx. 2MB)
                                        </p>
                                    </div>
                                </div>
                                @error('logo')
                                    <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="pt-3 text-[11px] text-slate-400 flex items-center gap-2">
                            <i class="fas fa-shield-alt text-[#2E6F40]"></i>
                            <span>Imagen oficial desplegada en el sidebar y reportes.</span>
                        </div>
                    </div>
                </div>

                <!-- TARJETA 2: TEXTOS LEGALES WYSIWYG (7 Columnas) -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="card-sena rounded-3xl p-6 sm:p-7 space-y-6 flex flex-col justify-between h-full">
                        <div class="space-y-6">
                            <div class="flex items-center gap-3.5 border-b border-slate-100 pb-4">
                                <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-base flex-shrink-0">
                                    <i class="fas fa-file-contract"></i>
                                </div>
                                <div>
                                    <h2 class="text-base font-extrabold text-[#1A2B4C] tracking-tight">2. Textos Legales e Institucionales</h2>
                                    <p class="text-xs text-slate-500">Editor enriquecido (WYSIWYG) para normativas, términos y privacidad</p>
                                </div>
                            </div>

                            <!-- Selector de Pestañas (Tabs Estilo SENA) -->
                            <div class="flex items-center gap-2 p-1.5 bg-slate-100 rounded-2xl">
                                <button type="button" 
                                        id="tab-btn-terminos"
                                        onclick="alternarTabLegal('terminos')" 
                                        class="flex-1 py-2.5 px-4 rounded-xl text-xs font-bold transition-all text-white bg-[#1A2B4C] shadow-xs flex items-center justify-center gap-2">
                                    <i class="fas fa-balance-scale text-xs"></i>
                                    <span>Términos y Condiciones</span>
                                </button>
                                <button type="button" 
                                        id="tab-btn-privacidad"
                                        onclick="alternarTabLegal('privacidad')" 
                                        class="flex-1 py-2.5 px-4 rounded-xl text-xs font-bold transition-all text-slate-600 hover:text-[#1A2B4C] flex items-center justify-center gap-2">
                                    <i class="fas fa-user-lock text-xs"></i>
                                    <span>Políticas de Privacidad</span>
                                </button>
                            </div>

                            <!-- Panel 1: Términos y Condiciones -->
                            <div id="panel-terminos" class="space-y-2">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                    Términos y Condiciones de Uso del Sistema
                                </label>
                                <div id="quill-editor-terminos"></div>
                                <input type="hidden" name="terminos_condiciones" id="input-hidden-terminos" value="{{ $configuracion->terminos_condiciones }}">
                            </div>

                            <!-- Panel 2: Políticas de Privacidad -->
                            <div id="panel-privacidad" class="space-y-2 hidden">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                    Políticas de Privacidad y Tratamiento de Datos
                                </label>
                                <div id="quill-editor-privacidad"></div>
                                <input type="hidden" name="politicas_privacidad" id="input-hidden-privacidad" value="{{ $configuracion->politicas_privacidad }}">
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex items-center gap-2 text-xs text-slate-500">
                            <i class="fas fa-info-circle text-[#2E6F40]"></i>
                            <span>Estos textos estarán visibles en el portal público y en las inducciones para aprendices.</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- FILA 2: RESTRICCIONES OPERATIVAS + ASIGNACIÓN DINÁMICA DE MÓDULOS -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start">
                
                <!-- TARJETA 3: RESTRICCIONES OPERATIVAS (5 Columnas) -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="card-sena rounded-3xl p-6 sm:p-7 space-y-6">
                        <div class="flex items-center gap-3.5 border-b border-slate-100 pb-4">
                            <div class="w-10 h-10 rounded-2xl bg-blue-50 text-[#1A2B4C] flex items-center justify-center font-bold text-base flex-shrink-0">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-extrabold text-[#1A2B4C] tracking-tight">3. Restricciones Operativas</h2>
                                <p class="text-xs text-slate-500">Políticas de control para el rol <code class="text-[#2E6F40] bg-emerald-50 px-1.5 py-0.5 rounded font-mono font-bold">editor_sisig</code></p>
                            </div>
                        </div>

                        <div class="space-y-3.5">
                            <!-- Toggle 1: Bloquear contenido crítico -->
                            <label class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50/80 border border-slate-200/80 hover:border-slate-300 transition-all cursor-pointer group">
                                <div class="pr-3">
                                    <span class="text-xs font-bold text-[#1A2B4C] group-hover:text-[#2E6F40] transition-colors block">
                                        Bloquear edición de contenido crítico
                                    </span>
                                    <span class="text-[11px] text-slate-500 block mt-0.5 leading-relaxed">
                                        Impide a los editores alterar normas o módulos centrales del SIG.
                                    </span>
                                </div>
                                <div class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                    <input type="checkbox" 
                                           name="editor_bloquear_edicion_critica" 
                                           value="1" 
                                           {{ $configuracion->editor_bloquear_edicion_critica ? 'checked' : '' }} 
                                           class="sr-only switch-sena-input">
                                    <div class="switch-sena-track w-11 h-6 bg-slate-300 rounded-full transition-colors relative">
                                        <div class="switch-sena-thumb w-5 h-5 bg-white rounded-full transition-transform absolute top-0.5 left-0.5 shadow-xs"></div>
                                    </div>
                                </div>
                            </label>

                            <!-- Toggle 2: Limitar creación de módulos -->
                            <label class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50/80 border border-slate-200/80 hover:border-slate-300 transition-all cursor-pointer group">
                                <div class="pr-3">
                                    <span class="text-xs font-bold text-[#1A2B4C] group-hover:text-[#2E6F40] transition-colors block">
                                        Limitar creación de módulos
                                    </span>
                                    <span class="text-[11px] text-slate-500 block mt-0.5 leading-relaxed">
                                        Solo los administradores podrán inaugurar nuevos módulos didácticos.
                                    </span>
                                </div>
                                <div class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                    <input type="checkbox" 
                                           name="editor_limitar_creacion_modulos" 
                                           value="1" 
                                           {{ $configuracion->editor_limitar_creacion_modulos ? 'checked' : '' }} 
                                           class="sr-only switch-sena-input">
                                    <div class="switch-sena-track w-11 h-6 bg-slate-300 rounded-full transition-colors relative">
                                        <div class="switch-sena-thumb w-5 h-5 bg-white rounded-full transition-transform absolute top-0.5 left-0.5 shadow-xs"></div>
                                    </div>
                                </div>
                            </label>

                            <!-- Toggle 3: Restringir eliminación de quices -->
                            <label class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50/80 border border-slate-200/80 hover:border-slate-300 transition-all cursor-pointer group">
                                <div class="pr-3">
                                    <span class="text-xs font-bold text-[#1A2B4C] group-hover:text-[#2E6F40] transition-colors block">
                                        Restringir eliminación de evaluaciones
                                    </span>
                                    <span class="text-[11px] text-slate-500 block mt-0.5 leading-relaxed">
                                        Evita suprimir quices o bancos de preguntas con evaluaciones realizadas.
                                    </span>
                                </div>
                                <div class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                    <input type="checkbox" 
                                           name="editor_restringir_eliminacion_quices" 
                                           value="1" 
                                           {{ $configuracion->editor_restringir_eliminacion_quices ? 'checked' : '' }} 
                                           class="sr-only switch-sena-input">
                                    <div class="switch-sena-track w-11 h-6 bg-slate-300 rounded-full transition-colors relative">
                                        <div class="switch-sena-thumb w-5 h-5 bg-white rounded-full transition-transform absolute top-0.5 left-0.5 shadow-xs"></div>
                                    </div>
                                </div>
                            </label>

                            <!-- Toggle 4: Exigir aprobación previa -->
                            <label class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50/80 border border-slate-200/80 hover:border-slate-300 transition-all cursor-pointer group">
                                <div class="pr-3">
                                    <span class="text-xs font-bold text-[#1A2B4C] group-hover:text-[#2E6F40] transition-colors block">
                                        Exigir aprobación previa de contenidos
                                    </span>
                                    <span class="text-[11px] text-slate-500 block mt-0.5 leading-relaxed">
                                        Cualquier cambio editorial quedará en estado 'Revisión' hasta visto bueno del admin.
                                    </span>
                                </div>
                                <div class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                    <input type="checkbox" 
                                           name="editor_requerir_aprobacion" 
                                           value="1" 
                                           {{ $configuracion->editor_requerir_aprobacion ? 'checked' : '' }} 
                                           class="sr-only switch-sena-input">
                                    <div class="switch-sena-track w-11 h-6 bg-slate-300 rounded-full transition-colors relative">
                                        <div class="switch-sena-thumb w-5 h-5 bg-white rounded-full transition-transform absolute top-0.5 left-0.5 shadow-xs"></div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- =========================================================
                     TARJETA 4: ASIGNACIÓN DINÁMICA DE MÓDULOS A EDITORES
                     (100% REACTIVA: CERO RECARGA DE PÁGINA)
                     ========================================================= -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="card-sena rounded-3xl p-6 sm:p-7 space-y-6">
                        
                        <!-- Encabezado de la Tarjeta de Asignación -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-[#2E6F40] flex items-center justify-center font-bold text-base flex-shrink-0">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                                <div>
                                    <h2 class="text-base font-extrabold text-[#1A2B4C] tracking-tight">
                                        4. Asignación Dinámica de Módulos a Editores
                                    </h2>
                                    <p class="text-xs text-slate-500">
                                        Sincronización en vivo en la tabla pivote <code class="text-[#2E6F40] bg-emerald-50 px-1 py-0.5 rounded font-mono font-bold">editor_modulos</code>
                                    </p>
                                </div>
                            </div>

                            @if($editores->isNotEmpty())
                                <div class="flex items-center gap-2">
                                    <button type="button" 
                                            onclick="toggleTodosModulosAsignados(true)" 
                                            class="px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-[#2E6F40] text-[11px] font-bold transition-all cursor-pointer">
                                        <i class="fas fa-check-double mr-1"></i> Todos
                                    </button>
                                    <button type="button" 
                                            onclick="toggleTodosModulosAsignados(false)" 
                                            class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 text-[11px] font-bold transition-all cursor-pointer">
                                        <i class="fas fa-times mr-1"></i> Ninguno
                                    </button>
                                </div>
                            @endif
                        </div>

                        @if($editores->isEmpty())
                            <div class="p-6 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-300 space-y-2">
                                <i class="fas fa-users-slash text-slate-400 text-xl"></i>
                                <p class="text-xs text-slate-700 font-bold">No hay usuarios con el rol de Editor SISIG registrados.</p>
                                <p class="text-[11px] text-slate-500">Crea o asigna el rol <code class="text-[#2E6F40]">editor_sisig</code> en la sección de Usuarios.</p>
                            </div>
                        @else
                            <!-- Selector del Editor a Parametrizar (Cambio en vivo sin recargar página) -->
                            <div class="space-y-2">
                                <label for="select-editor-activo" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                    Seleccionar Editor para Asignar Módulos:
                                </label>
                                <div class="relative">
                                    <select id="select-editor-activo" 
                                            onchange="cambiarEditorActivoEnVivo(this.value)" 
                                            class="input-sena w-full px-4 py-2.5 rounded-xl text-xs font-bold text-[#1A2B4C] focus:outline-none cursor-pointer">
                                        @foreach($editores as $ed)
                                            <option value="{{ $ed->id }}" 
                                                    data-nombre="{{ $ed->name }} ({{ $ed->email }})"
                                                    {{ $editorSeleccionado && $editorSeleccionado->id === $ed->id ? 'selected' : '' }}>
                                                {{ $ed->name }} ({{ $ed->email }}) &mdash; {{ $ed->modulosEditables->count() }} módulos autorizados
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- ITERACIÓN DINÁMICA DE MÓDULOS DE LA BD (FOREACH BLADE) -->
                            <div class="space-y-3 pt-2">
                                <div class="flex items-center justify-between text-xs px-1">
                                    <span class="font-bold uppercase tracking-wider text-slate-700">
                                        Módulos del Sistema SIG (Consulta Dinámica)
                                    </span>
                                    <span id="contador-modulos-asignados" class="text-[#2E6F40] font-bold">
                                        {{ count($modulosAsignados) }} de {{ $modulos->count() }} autorizados
                                    </span>
                                </div>

                                <!-- Contenedor de Switches con micro-animación en cambio reactivo -->
                                <div id="contenedor-switches-modulos" class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 max-h-[380px] overflow-y-auto pr-1">
                                    @forelse($modulos as $modulo)
                                        @php
                                            $estaAsignado = in_array($modulo->id, $modulosAsignados);
                                        @endphp

                                        <label class="subcard-sena rounded-2xl p-3.5 flex items-center justify-between gap-3 cursor-pointer select-none group" id="label-modulo-{{ $modulo->id }}">
                                            <div class="flex items-center gap-3 min-w-0 pr-2">
                                                <!-- Icono del Módulo -->
                                                <div class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-[#2E6F40] group-hover:scale-105 transition-transform flex-shrink-0 shadow-2xs">
                                                    <i class="{{ $modulo->icon_class ?? 'fas fa-book-open' }} text-xs"></i>
                                                </div>

                                                <div class="min-w-0">
                                                    <!-- Título dinámico desde la base de datos (Sin hardcoding) -->
                                                    <span class="text-xs font-bold text-[#1A2B4C] block truncate group-hover:text-[#2E6F40] transition-colors">
                                                        {{ $modulo->titulo }}
                                                    </span>
                                                    
                                                    <div class="flex items-center gap-1.5 mt-0.5">
                                                        @if(!empty($modulo->norma))
                                                            <span class="text-[10px] text-slate-500 truncate">{{ $modulo->norma }}</span>
                                                            <span class="text-slate-300">&bull;</span>
                                                        @endif
                                                        <span class="text-[9px] font-bold uppercase tracking-wider px-1.5 py-0.2 rounded {{ $modulo->estado === 'publicado' ? 'text-emerald-700 bg-emerald-50 border border-emerald-200' : 'text-amber-700 bg-amber-50 border border-amber-200' }}">
                                                            {{ $modulo->estado }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Switch Dinámico Verde SENA -->
                                            <div class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                                <input type="checkbox" 
                                                       name="modulos_asignados[]" 
                                                       value="{{ $modulo->id }}" 
                                                       data-modulo-id="{{ $modulo->id }}"
                                                       {{ $estaAsignado ? 'checked' : '' }} 
                                                       onchange="recalcularContadorAsignados()"
                                                       class="sr-only switch-sena-input checkbox-modulo-permiso">
                                                <div class="switch-sena-track w-11 h-6 bg-slate-300 rounded-full transition-colors relative">
                                                    <div class="switch-sena-thumb w-5 h-5 bg-white rounded-full transition-transform absolute top-0.5 left-0.5 shadow-xs"></div>
                                                </div>
                                            </div>
                                        </label>
                                    @empty
                                        <div class="col-span-2 p-6 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-300">
                                            <p class="text-xs text-slate-500 italic">No existen módulos registrados en la tabla sisig_modulos.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

            </div>

            <!-- 3. Barra Inferior de Guardado con Estilo Oficial SENA -->
            <div class="card-sena rounded-3xl p-5 sm:p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-xs text-slate-600 flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-emerald-50 text-[#2E6F40] flex items-center justify-center font-bold text-xs flex-shrink-0">
                        <i class="fas fa-check"></i>
                    </div>
                    <span>
                        Al guardar, se actualizarán los parámetros de plataforma y se sincronizarán los módulos mediante <code class="text-[#2E6F40] font-mono font-bold">sync()</code> sobre <code class="text-slate-700 font-mono">editor_modulos</code> sin recargar la página.
                    </span>
                </div>

                <button type="submit" 
                        id="btn-guardar-configuracion"
                        class="btn-sena-primary w-full sm:w-auto px-8 py-3.5 rounded-full text-xs font-black uppercase tracking-wider flex items-center justify-center gap-2.5 cursor-pointer">
                    <i class="fas fa-save text-sm" id="btn-save-icon"></i>
                    <span id="btn-save-text">Guardar Toda la Configuración</span>
                </button>
            </div>

        </form>
    </div>

    <!-- SCRIPTS: INICIALIZACIÓN DE QUILL Y REACTIVIDAD 100% CLIENT-SIDE -->
    <script src="{{ asset('modules/sisig/vendor/quill/quill.min.js') }}"></script>
    <script>
        let editorTerminos, editorPrivacidad;

        // 1. Estado en memoria de permisos de TODOS los editores (cargado desde backend)
        const mapaPermisosOriginales = @json($mapaPermisosEditores ?? []);
        let permisosEditoresMemoria = JSON.parse(JSON.stringify(mapaPermisosOriginales));
        let editorActualId = document.getElementById('select-editor-activo') ? document.getElementById('select-editor-activo').value : null;

        document.addEventListener('DOMContentLoaded', () => {
            const opcionesBarra = [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'color': [] }, { 'background': [] }],
                ['clean']
            ];

            // Inicializar Editor de Términos
            editorTerminos = new Quill('#quill-editor-terminos', {
                theme: 'snow',
                placeholder: 'Ingresa aquí los términos y condiciones institucionales...',
                modules: { toolbar: opcionesBarra }
            });
            const contenidoTerminos = document.getElementById('input-hidden-terminos').value;
            if (contenidoTerminos) editorTerminos.root.innerHTML = contenidoTerminos;

            // Inicializar Editor de Privacidad
            editorPrivacidad = new Quill('#quill-editor-privacidad', {
                theme: 'snow',
                placeholder: 'Ingresa aquí las políticas de privacidad y datos...',
                modules: { toolbar: opcionesBarra }
            });
            const contenidoPrivacidad = document.getElementById('input-hidden-privacidad').value;
            if (contenidoPrivacidad) editorPrivacidad.root.innerHTML = contenidoPrivacidad;

            // Sincronizar estado inicial del JSON de permisos
            const inputJson = document.getElementById('input-permisos-json');
            if (inputJson) {
                inputJson.value = JSON.stringify(permisosEditoresMemoria);
            }

            // 2. Envío Asíncrono (AJAX) del Formulario: Guarda sin recargar la página
            const formulario = document.getElementById('form-configuracion-plataforma');
            formulario.addEventListener('submit', function(e) {
                e.preventDefault();

                // Sincronizar editores Quill a los inputs hidden
                document.getElementById('input-hidden-terminos').value = editorTerminos.root.innerHTML;
                document.getElementById('input-hidden-privacidad').value = editorPrivacidad.root.innerHTML;

                // Sincronizar JSON de permisos actualizado
                if (inputJson) {
                    inputJson.value = JSON.stringify(permisosEditoresMemoria);
                }

                // Estado de carga del botón
                const btnSubmit = document.getElementById('btn-guardar-configuracion');
                const btnIcon = document.getElementById('btn-save-icon');
                const btnText = document.getElementById('btn-save-text');

                const iconOriginal = btnIcon ? btnIcon.className : 'fas fa-save text-sm';
                const textOriginal = btnText ? btnText.textContent : 'Guardar Toda la Configuración';

                if (btnIcon) btnIcon.className = 'fas fa-spinner fa-spin text-sm';
                if (btnText) btnText.textContent = 'Guardando cambios...';
                if (btnSubmit) btnSubmit.disabled = true;

                const formData = new FormData(formulario);

                fetch(formulario.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errData => { throw errData; });
                    }
                    return response.json();
                })
                .then(data => {
                    // Restaurar botón
                    if (btnIcon) btnIcon.className = iconOriginal;
                    if (btnText) btnText.textContent = textOriginal;
                    if (btnSubmit) btnSubmit.disabled = false;

                    // Mostrar notificación flotante banner sin recarga
                    const mensaje = data.message || '¡Configuración guardada exitosamente!';
                    if (window.mostrarAlertaBanner) {
                        window.mostrarAlertaBanner(mensaje, 'success');
                    } else if (window.Swal) {
                        window.Swal.fire({
                            icon: 'success',
                            title: '¡Guardado!',
                            text: mensaje,
                            timer: 2500,
                            showConfirmButton: false
                        });
                    }

                    // Actualizar previsualización de logo si se cambió
                    if (data.logo_url) {
                        const imgLogo = document.getElementById('preview-logo-imagen');
                        if (imgLogo) imgLogo.src = data.logo_url;
                    }
                })
                .catch(error => {
                    console.error('Error al guardar configuración:', error);
                    if (btnIcon) btnIcon.className = iconOriginal;
                    if (btnText) btnText.textContent = textOriginal;
                    if (btnSubmit) btnSubmit.disabled = false;

                    let errMsg = 'Ocurrió un error al procesar el guardado.';
                    if (error && error.errors) {
                        const firstKey = Object.keys(error.errors)[0];
                        errMsg = error.errors[firstKey][0] || errMsg;
                    } else if (error && error.message) {
                        errMsg = error.message;
                    }

                    if (window.mostrarAlertaBanner) {
                        window.mostrarAlertaBanner(errMsg, 'error');
                    } else {
                        alert(errMsg);
                    }
                });
            });
        });

        // =========================================================
        // CAMBIO DE EDITOR 100% EN VIVO (CERO RECARGA DE PÁGINA)
        // =========================================================
        function cambiarEditorActivoEnVivo(nuevoEditorId) {
            editorActualId = nuevoEditorId;

            // Actualizar input hidden
            const inputHiddenEditor = document.getElementById('input-editor-id-hidden');
            if (inputHiddenEditor) inputHiddenEditor.value = nuevoEditorId;

            // Obtener los IDs autorizados en memoria para este editor
            const idsAutorizados = permisosEditoresMemoria[nuevoEditorId] || [];

            // Efecto sutil de transición visual
            const contenedorSwitches = document.getElementById('contenedor-switches-modulos');
            if (contenedorSwitches) {
                contenedorSwitches.classList.remove('fade-in-switches');
                void contenedorSwitches.offsetWidth; // Forzar reflow
                contenedorSwitches.classList.add('fade-in-switches');
            }

            // Actualizar cada checkbox instantáneamente
            const checkboxes = document.querySelectorAll('.checkbox-modulo-permiso');
            checkboxes.forEach(cb => {
                const modId = parseInt(cb.dataset.moduloId || cb.value);
                cb.checked = idsAutorizados.includes(modId);
            });

            // Actualizar el contador de módulos
            actualizarTextoContador(idsAutorizados.length, checkboxes.length);
        }

        // =========================================================
        // ACTUALIZACIÓN DE PERMISOS EN MEMORIA POR CHECKBOX
        // =========================================================
        function recalcularContadorAsignados() {
            const checkboxes = document.querySelectorAll('.checkbox-modulo-permiso');
            const total = checkboxes.length;
            const seleccionados = [];

            checkboxes.forEach(cb => {
                if (cb.checked) {
                    seleccionados.push(parseInt(cb.dataset.moduloId || cb.value));
                }
            });

            // Guardar para el editor activo en el objeto memoria
            if (editorActualId) {
                permisosEditoresMemoria[editorActualId] = seleccionados;

                // Actualizar el texto del option del select en tiempo real sin recargar
                const select = document.getElementById('select-editor-activo');
                if (select) {
                    const opt = select.querySelector(`option[value="${editorActualId}"]`);
                    if (opt) {
                        let nombreBase = opt.getAttribute('data-nombre');
                        if (!nombreBase) {
                            nombreBase = opt.textContent.split('—')[0].trim();
                            opt.setAttribute('data-nombre', nombreBase);
                        }
                        opt.textContent = `${nombreBase} — ${seleccionados.length} módulos autorizados`;
                    }
                }
            }

            // Actualizar contador numérico
            actualizarTextoContador(seleccionados.length, total);

            // Sincronizar input hidden serializado
            const inputJson = document.getElementById('input-permisos-json');
            if (inputJson) {
                inputJson.value = JSON.stringify(permisosEditoresMemoria);
            }
        }

        function actualizarTextoContador(seleccionados, total) {
            const contador = document.getElementById('contador-modulos-asignados');
            if (contador) {
                contador.textContent = `${seleccionados} de ${total} autorizados`;
            }
        }

        // =========================================================
        // SELECCIÓN MASIVA EN VIVO
        // =========================================================
        function toggleTodosModulosAsignados(marcar) {
            const checkboxes = document.querySelectorAll('.checkbox-modulo-permiso');
            checkboxes.forEach(cb => cb.checked = marcar);
            recalcularContadorAsignados();
        }

        // Alternancia de Pestañas Legales
        function alternarTabLegal(tab) {
            const btnTerminos = document.getElementById('tab-btn-terminos');
            const btnPrivacidad = document.getElementById('tab-btn-privacidad');
            const panelTerminos = document.getElementById('panel-terminos');
            const panelPrivacidad = document.getElementById('panel-privacidad');

            if (tab === 'terminos') {
                btnTerminos.className = 'flex-1 py-2.5 px-4 rounded-xl text-xs font-bold transition-all text-white bg-[#1A2B4C] shadow-xs flex items-center justify-center gap-2 cursor-pointer';
                btnPrivacidad.className = 'flex-1 py-2.5 px-4 rounded-xl text-xs font-bold transition-all text-slate-600 hover:text-[#1A2B4C] flex items-center justify-center gap-2 cursor-pointer';
                panelTerminos.classList.remove('hidden');
                panelPrivacidad.classList.add('hidden');
            } else {
                btnPrivacidad.className = 'flex-1 py-2.5 px-4 rounded-xl text-xs font-bold transition-all text-white bg-[#1A2B4C] shadow-xs flex items-center justify-center gap-2 cursor-pointer';
                btnTerminos.className = 'flex-1 py-2.5 px-4 rounded-xl text-xs font-bold transition-all text-slate-600 hover:text-[#1A2B4C] flex items-center justify-center gap-2 cursor-pointer';
                panelPrivacidad.classList.remove('hidden');
                panelTerminos.classList.add('hidden');
            }
        }

        // Previsualización instantánea del Logotipo
        function actualizarPrevisualizacionLogo(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imagenElemento = document.getElementById('preview-logo-imagen');
                    if (imagenElemento) imagenElemento.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-sisig::layouts.admin>
