<x-sisig::layouts.admin title="Configuración de la Plataforma">
    <div class="space-y-6 sm:space-y-8 animate-fade-in pb-12">

        <!-- 1. Encabezado de la Sección -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-5 border-b border-slate-200">
            <div>
                <div class="flex items-center gap-2.5">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-sena-green/10 text-sena-green">
                        <i class="fas fa-sliders-h text-sm"></i>
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        Configuración de la Plataforma
                    </h1>
                </div>
                <p class="text-xs text-slate-500 font-medium mt-1.5 max-w-2xl">
                    Espacio donde el administrador ajusta la identidad visual, restricciones de edición para los editores y políticas generales del sistema.
                </p>
            </div>

            <!-- Botones de Acción en la cabecera -->
            <div class="flex items-center gap-2.5">
                <button type="button" onclick="confirmarRestablecerValores()" class="inline-flex items-center px-4 py-2.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold tracking-wide transition-all duration-200 cursor-pointer shadow-xs">
                    Restablecer Valores
                </button>
                <button type="submit" form="form-configuracion" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-sena-green hover:bg-emerald-600 text-white text-xs font-bold tracking-wide shadow-sena hover:shadow-lg transition-all duration-200 cursor-pointer">
                    Guardar Cambios
                </button>
            </div>
        </div>

        <!-- Alertas de Sesión -->
        @if(session('success'))
            <div id="session-alert" class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between gap-3 text-xs font-bold shadow-sm">
                <div class="flex items-center gap-2.5">
                    <i class="fas fa-check-circle text-emerald-600 text-sm"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.closest('#session-alert').remove()" class="text-emerald-600 hover:text-emerald-800">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div id="session-alert" class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center justify-between gap-3 text-xs font-bold shadow-sm">
                <div class="flex items-center gap-2.5">
                    <i class="fas fa-exclamation-circle text-rose-600 text-sm"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.closest('#session-alert').remove()" class="text-rose-600 hover:text-rose-800">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium space-y-1 shadow-sm">
                <div class="flex items-center gap-2 font-bold mb-1">
                    <i class="fas fa-exclamation-triangle text-rose-600"></i>
                    <span>Por favor corrige los siguientes errores:</span>
                </div>
                <ul class="list-disc pl-5 space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- 2. Tarjetas de Resumen Rápido (KPIs) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Tarjeta 1: Identidad Visual -->
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-emerald-500 to-teal-600"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Identidad Visual</span>
                    <i class="fas fa-paint-brush text-emerald-600 text-2xl group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="mt-4">
                    <span class="text-2xl font-black text-slate-800">{{ sisig_config('app_name', 'SISIG') }}</span>
                    <p class="text-[11px] font-semibold text-slate-500 mt-0.5">Marca e identidad activa</p>
                </div>
            </div>

            <!-- Tarjeta 2: Restricciones para Editores -->
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-sky-500 to-blue-600"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Gobernanza Editorial</span>
                    <i class="fas fa-user-shield text-sky-600 text-2xl group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="mt-4">
                    <span class="text-2xl font-black text-slate-800">{{ $restriccionesActivas }} Activas</span>
                    <p class="text-[11px] font-semibold text-slate-500 mt-0.5">Reglas de edición reforzadas</p>
                </div>
            </div>

            <!-- Tarjeta 3: Tolerancia Anti-Trampa -->
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-amber-400 to-amber-600"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Políticas de Examen</span>
                    <i class="fas fa-stopwatch text-amber-500 text-2xl group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="mt-4">
                    <span class="text-2xl font-black text-slate-800">{{ sisig_config('examen_advertencias_maximas', 3) }} Faltas</span>
                    <p class="text-[11px] font-semibold text-slate-500 mt-0.5">Tolerancia máxima anti-trampa</p>
                </div>
            </div>

            <!-- Tarjeta 4: Estado Operativo -->
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r {{ $mantenimientoActivo ? 'from-rose-500 to-rose-700' : 'from-emerald-500 to-green-600' }}"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Estado Global</span>
                    <i class="fas {{ $mantenimientoActivo ? 'fa-tools text-rose-600' : 'fa-check-circle text-emerald-600' }} text-2xl group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="mt-4">
                    <span class="text-2xl font-black {{ $mantenimientoActivo ? 'text-rose-600' : 'text-slate-800' }}">
                        {{ $mantenimientoActivo ? 'En Mantenimiento' : 'Operativo' }}
                    </span>
                    <p class="text-[11px] font-semibold text-slate-500 mt-0.5">
                        {{ $mantenimientoActivo ? 'Aviso global encendido' : 'Todos los servicios activos' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- 3. Contenedor Principal con Tabs y Formulario -->
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden">
            
            <!-- Barra de Pestañas (Tabs) -->
            <div class="flex border-b border-slate-200 bg-slate-50/50 px-6 pt-4 gap-2 overflow-x-auto">
                <button type="button" onclick="cambiarTab('tab-identidad')" id="btn-tab-identidad"
                        class="tab-btn flex items-center gap-2.5 px-5 py-3 text-xs font-bold border-b-2 border-sena-green text-sena-green transition-all duration-200 cursor-pointer">
                    <i class="fas fa-palette text-sm"></i>
                    <span>1. Identidad Visual</span>
                </button>

                <button type="button" onclick="cambiarTab('tab-editores')" id="btn-tab-editores"
                        class="tab-btn flex items-center gap-2.5 px-5 py-3 text-xs font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-900 transition-all duration-200 cursor-pointer">
                    <i class="fas fa-user-lock text-sm"></i>
                    <span>2. Restricciones para Editores</span>
                </button>

                <button type="button" onclick="cambiarTab('tab-politicas')" id="btn-tab-politicas"
                        class="tab-btn flex items-center gap-2.5 px-5 py-3 text-xs font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-900 transition-all duration-200 cursor-pointer">
                    <i class="fas fa-cogs text-sm"></i>
                    <span>3. Políticas Generales del Sistema</span>
                </button>
            </div>

            <!-- Formulario Unificado -->
            <form id="form-configuracion" action="{{ route('sisig.admin.configuracion.update') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-8">
                @csrf
                @method('PUT')

                <!-- ========================================== -->
                <!-- PESTAÑA 1: IDENTIDAD VISUAL                -->
                <!-- ========================================== -->
                <div id="tab-identidad" class="tab-content space-y-8">
                    
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                            <i class="fas fa-brush text-sena-green"></i>
                            Personalización Visual y Marca
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Ajusta el nombre público, logotipos e iconos y colores representativos de la institución.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Nombre del Sistema -->
                        <div class="space-y-2">
                            <label for="app_name" class="block text-xs font-bold text-slate-700">
                                Nombre Visible de la Plataforma
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fas fa-signature text-xs"></i>
                                </span>
                                <input type="text" name="app_name" id="app_name" 
                                       value="{{ old('app_name', sisig_config('app_name', 'SISIG')) }}" 
                                       placeholder="Ej: SISIG - SENA Empresa"
                                       class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-sena-green focus:border-transparent transition">
                            </div>
                            <span class="text-[11px] text-slate-400">Nombre mostrado en títulos y encabezados principales.</span>
                        </div>

                        <!-- Lema o Descripción -->
                        <div class="space-y-2">
                            <label for="app_slogan" class="block text-xs font-bold text-slate-700">
                                Lema Institucional / Subtítulo
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fas fa-quote-left text-xs"></i>
                                </span>
                                <input type="text" name="app_slogan" id="app_slogan" 
                                       value="{{ old('app_slogan', sisig_config('app_slogan', 'Sistema Integral de Seguimiento e Inducción a la Gestión')) }}" 
                                       placeholder="Lema descriptivo del centro formativo..."
                                       class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-sena-green focus:border-transparent transition">
                            </div>
                            <span class="text-[11px] text-slate-400">Aparece en la página de bienvenida y pie de página.</span>
                        </div>

                        <!-- Colores Institucionales con Paleta en Cuadrícula (12 columnas x 9 filas) -->
                        <div class="col-span-1 md:col-span-2 pt-6 border-t border-slate-200">
                            <div class="mb-4">
                                <h4 class="text-xs font-extrabold text-slate-800 flex items-center gap-2">
                                    <i class="fas fa-palette text-sena-green"></i>
                                    Paleta de Colores del Sistema
                                </h4>
                                <p class="text-[11px] text-slate-500">
                                    Haz clic en cualquier muestra de color para seleccionarlo o ingresa tu propio código hexadecimal / gotero.
                                </p>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                                <!-- Tarjeta 1: Color Primario de Acento -->
                                <div class="p-5 rounded-2xl bg-slate-50/90 border border-slate-200 space-y-4">
                                    <div class="flex items-center justify-between gap-3">
                                        <div>
                                            <label for="color_primario_text" class="block text-xs font-extrabold text-slate-900">
                                                Color Primario de Acento
                                            </label>
                                            <p class="text-[11px] text-slate-500">Botones, bordes activos y elementos destacados</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <input type="text" id="color_primario_text" name="color_primario" 
                                                   value="{{ old('color_primario', sisig_config('color_primario', '#39A900')) }}"
                                                   class="w-24 px-2.5 py-1.5 bg-white border border-slate-300 rounded-xl text-xs font-mono font-bold text-slate-800 uppercase text-center focus:outline-none focus:ring-2 focus:ring-sena-green shadow-xs">
                                            
                                            <div class="px-3 py-1.5 rounded-xl text-white text-[11px] font-bold shadow-xs transition-colors duration-200 select-none flex items-center gap-1.5" 
                                                 id="color_preview_btn" 
                                                 style="background-color: {{ sisig_config('color_primario', '#39A900') }}">
                                                <i class="fas fa-check text-[10px]"></i>
                                                <span>Botón</span>
                                            </div>

                                            <!-- Botón Gotero / Color Picker Nativo -->
                                            <label title="Color personalizado / Gotero" class="relative cursor-pointer w-8 h-8 rounded-xl bg-white border border-slate-300 flex items-center justify-center hover:bg-slate-100 text-slate-600 transition shadow-xs">
                                                <i class="fas fa-eye-dropper text-xs"></i>
                                                <input type="color" id="color_primario_picker" 
                                                       value="{{ old('color_primario', sisig_config('color_primario', '#39A900')) }}" 
                                                       class="sr-only">
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Cuadrícula Matricial de Colores -->
                                    <div class="p-3 bg-white rounded-xl border border-slate-200/90 shadow-xs">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">
                                                Haz clic en un color de la paleta:
                                            </span>
                                            <span class="text-[10px] font-mono font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md" id="color_primario_hover_label">
                                                {{ sisig_config('color_primario', '#39A900') }}
                                            </span>
                                        </div>
                                        <div class="grid grid-cols-12 gap-1.5 select-none" id="grid-palette-primario">
                                            <!-- Swatches matriciales generados por JavaScript -->
                                        </div>
                                    </div>
                                </div>

                                <!-- Tarjeta 2: Color de Fondo del Sidebar -->
                                <div class="p-5 rounded-2xl bg-slate-50/90 border border-slate-200 space-y-4">
                                    <div class="flex items-center justify-between gap-3">
                                        <div>
                                            <label for="color_sidebar_text" class="block text-xs font-extrabold text-slate-900">
                                                Color de Fondo del Sidebar
                                            </label>
                                            <p class="text-[11px] text-slate-500">Barra lateral de navegación del sistema</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <input type="text" id="color_sidebar_text" name="color_sidebar" 
                                                   value="{{ old('color_sidebar', sisig_config('color_sidebar', '#001A29')) }}"
                                                   class="w-24 px-2.5 py-1.5 bg-white border border-slate-300 rounded-xl text-xs font-mono font-bold text-slate-800 uppercase text-center focus:outline-none focus:ring-2 focus:ring-sena-green shadow-xs">
                                            
                                            <div class="w-8 h-8 rounded-xl border border-slate-300 shadow-xs flex items-center justify-center text-white transition-colors duration-200 select-none" 
                                                 id="color_sidebar_preview" 
                                                 style="background-color: {{ sisig_config('color_sidebar', '#001A29') }}">
                                                <i class="fas fa-bars text-xs"></i>
                                            </div>

                                            <!-- Botón Gotero / Color Picker Nativo -->
                                            <label title="Color personalizado / Gotero" class="relative cursor-pointer w-8 h-8 rounded-xl bg-white border border-slate-300 flex items-center justify-center hover:bg-slate-100 text-slate-600 transition shadow-xs">
                                                <i class="fas fa-eye-dropper text-xs"></i>
                                                <input type="color" id="color_sidebar_picker" 
                                                       value="{{ old('color_sidebar', sisig_config('color_sidebar', '#001A29')) }}" 
                                                       class="sr-only">
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Cuadrícula Matricial de Colores -->
                                    <div class="p-3 bg-white rounded-xl border border-slate-200/90 shadow-xs">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">
                                                Haz clic en un color de la paleta:
                                            </span>
                                            <span class="text-[10px] font-mono font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md" id="color_sidebar_hover_label">
                                                {{ sisig_config('color_sidebar', '#001A29') }}
                                            </span>
                                        </div>
                                        <div class="grid grid-cols-12 gap-1.5 select-none" id="grid-palette-sidebar">
                                            <!-- Swatches matriciales generados por JavaScript -->
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <!-- Sección de Subida de Logotipo y Favicon -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-slate-200">
                        
                        <!-- Logotipo -->
                        <div class="p-5 rounded-2xl bg-slate-50/80 border border-slate-200/80 space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-xs font-extrabold text-slate-800">Logotipo Principal</h4>
                                    <p class="text-[11px] text-slate-500">Visible en la parte superior del menú y login</p>
                                </div>
                                @if(sisig_config('app_logo'))
                                    <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold">Personalizado</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full bg-slate-200 text-slate-700 text-[10px] font-bold">Por Defecto</span>
                                @endif
                            </div>

                            <div class="flex items-center gap-4">
                                <div class="w-20 h-20 rounded-2xl bg-[#001A29] p-2 flex items-center justify-center border border-slate-800 flex-shrink-0 shadow-sm">
                                    <img id="preview-logo" 
                                         src="{{ sisig_config('app_logo') ? asset(sisig_config('app_logo')) : asset('modules/sisig/images/logo.png') }}" 
                                         alt="Logo Previsualización" 
                                         class="max-w-full max-h-full object-contain">
                                </div>

                                <div class="flex-1 space-y-2">
                                    <label class="block">
                                        <span class="sr-only">Seleccionar Logotipo</span>
                                        <input type="file" name="app_logo" id="input-logo" accept="image/png,image/jpeg,image/svg+xml,image/webp"
                                               onchange="previsualizarImagen(this, 'preview-logo')"
                                               class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sena-green file:text-white hover:file:bg-emerald-600 file:cursor-pointer cursor-pointer">
                                    </label>
                                    <p class="text-[10px] text-slate-400">Recomendado: PNG o SVG transparente (Máx. 3MB)</p>
                                </div>
                            </div>
                        </div>

                        <!-- Favicon -->
                        <div class="p-5 rounded-2xl bg-slate-50/80 border border-slate-200/80 space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-xs font-extrabold text-slate-800">Favicon (Icono Navegador)</h4>
                                    <p class="text-[11px] text-slate-500">Icono mostrado en la pestaña del navegador</p>
                                </div>
                                @if(sisig_config('app_favicon'))
                                    <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold">Personalizado</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full bg-slate-200 text-slate-700 text-[10px] font-bold">Por Defecto</span>
                                @endif
                            </div>

                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-white p-2.5 flex items-center justify-center border border-slate-200 flex-shrink-0 shadow-sm">
                                    <img id="preview-favicon" 
                                         src="{{ sisig_config('app_favicon') ? asset(sisig_config('app_favicon')) : asset('modules/sisig/images/logo.png') }}" 
                                         alt="Favicon Previsualización" 
                                         class="max-w-full max-h-full object-contain">
                                </div>

                                <div class="flex-1 space-y-2">
                                    <label class="block">
                                        <span class="sr-only">Seleccionar Favicon</span>
                                        <input type="file" name="app_favicon" id="input-favicon" accept=".ico,image/png,image/svg+xml"
                                               onchange="previsualizarImagen(this, 'preview-favicon')"
                                               class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-800 file:text-white hover:file:bg-slate-900 file:cursor-pointer cursor-pointer">
                                    </label>
                                    <p class="text-[10px] text-slate-400">Recomendado: Formato .ico o PNG cuadrado de 32x32px</p>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- ========================================== -->
                <!-- PESTAÑA 2: RESTRICCIONES PARA EDITORES      -->
                <!-- ========================================== -->
                <div id="tab-editores" class="tab-content hidden space-y-6">
                    
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                            <i class="fas fa-user-lock text-sky-600"></i>
                            Gobernanza y Restricciones de Edición
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Define qué acciones críticas pueden o no realizar los usuarios con rol de Editor de Contenidos.
                        </p>
                    </div>

                    <!-- CARTA CUADRADA DE EDITORES DEL SISTEMA -->
                    <div class="flex flex-wrap gap-6 pt-2">
                        
                        <div class="w-full sm:w-80 md:w-88 bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition-all duration-300 group">
                            
                            <!-- Cabecera de la Tarjeta Cuadrada -->
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl shadow-2xs group-hover:scale-105 transition-transform">
                                        <i class="fas fa-users-cog"></i>
                                    </div>
                                    <span class="px-2.5 py-1 rounded-full bg-sky-50 border border-sky-200 text-sky-800 text-[10px] font-extrabold tracking-wide">
                                        {{ isset($editores) ? $editores->count() : 0 }} {{ (isset($editores) && $editores->count() === 1) ? 'Editor' : 'Editores' }}
                                    </span>
                                </div>

                                <h4 class="text-base font-black text-slate-900 tracking-tight">Editores del Sistema</h4>
                                <p class="text-xs text-slate-500 font-medium mt-1 leading-relaxed">
                                    Gestión de usuarios con privilegios para formular evaluaciones y redactar módulos formativos.
                                </p>
                            </div>

                            <!-- Ficha de Usuario del Editor -->
                            <div class="my-5">
                                @if(isset($editores) && $editores->count() > 0)
                                    @php $primerEditor = $editores->first(); @endphp
                                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-sky-500 to-indigo-600 text-white flex items-center justify-center font-black text-xs flex-shrink-0 shadow-2xs">
                                            {{ strtoupper(substr($primerEditor->name, 0, 2)) }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-xs font-bold text-slate-900 truncate">{{ $primerEditor->name }}</p>
                                            <p class="text-[11px] text-slate-500 truncate">{{ $primerEditor->email }}</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="p-4 rounded-2xl bg-slate-50 border border-dashed border-slate-200 text-center">
                                        <p class="text-xs text-slate-400 font-medium">Sin editores registrados</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Botón Permisos -->
                            <button type="button" 
                                    onclick="abrirModalPermisos('{{ isset($editores) && $editores->count() > 0 ? addslashes($editores->first()->name) : 'Editor de Contenidos SISIG' }}', '{{ isset($editores) && $editores->count() > 0 ? addslashes($editores->first()->email) : 'editor@sena.edu.co' }}', '{{ isset($editores) && $editores->count() > 0 ? $editores->first()->id : '' }}')"
                                    class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-2xl bg-sena-green hover:bg-emerald-600 text-white text-xs font-bold tracking-wide shadow-sena hover:shadow-lg transition-all duration-200 cursor-pointer">
                                <i class="fas fa-key text-xs"></i>
                                <span>Permisos</span>
                            </button>

                        </div>

                    </div>

                </div>

                <!-- ========================================== -->
                <!-- PESTAÑA 3: POLÍTICAS GENERALES DEL SISTEMA -->
                <!-- ========================================== -->
                <div id="tab-politicas" class="tab-content hidden space-y-8">
                    
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                            <i class="fas fa-shield-alt text-amber-500"></i>
                            Políticas Globales de Evaluaciones y Seguridad
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Configura los parámetros globales que rigen las evaluaciones y la experiencia de los aprendices.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Tiempo Límite por Defecto -->
                        <div class="space-y-2">
                            <label for="examen_tiempo_defecto" class="block text-xs font-bold text-slate-700">
                                Tiempo Límite por Defecto (Minutos)
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fas fa-clock text-xs"></i>
                                </span>
                                <input type="number" name="examen_tiempo_defecto" id="examen_tiempo_defecto" min="5" max="300"
                                       value="{{ old('examen_tiempo_defecto', sisig_config('examen_tiempo_defecto', 30)) }}"
                                       class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-sena-green focus:border-transparent transition">
                            </div>
                            <span class="text-[11px] text-slate-400">Duración inicial sugerida al formular nuevos exámenes.</span>
                        </div>

                        <!-- Tolerancia Anti-Trampa -->
                        <div class="space-y-2">
                            <label for="examen_advertencias_maximas" class="block text-xs font-bold text-slate-700">
                                Tolerancia de Infracciones Anti-Trampa
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fas fa-user-secret text-xs"></i>
                                </span>
                                <input type="number" name="examen_advertencias_maximas" id="examen_advertencias_maximas" min="1" max="10"
                                       value="{{ old('examen_advertencias_maximas', sisig_config('examen_advertencias_maximas', 3)) }}"
                                       class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-sena-green focus:border-transparent transition">
                            </div>
                            <span class="text-[11px] text-slate-400">Número de advertencias permitidas antes de anular/cerrar el examen del aprendiz.</span>
                        </div>

                    </div>

                    <!-- Switch: Mostrar resultados de inmediato -->
                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between gap-4 hover:border-slate-300 transition">
                        <div class="space-y-1 max-w-xl">
                            <label for="examen_mostrar_resultados_inmediatos" class="text-xs font-bold text-slate-900 block cursor-pointer">
                                Publicar Retroalimentación Inmediata al Finalizar
                            </label>
                            <p class="text-[11px] text-slate-500">
                                Permite que el aprendiz visualice su puntaje obtenido, preguntas correctas y retroalimentación didáctica al momento exacto de enviar la evaluación.
                            </p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                            <input type="checkbox" name="examen_mostrar_resultados_inmediatos" id="examen_mostrar_resultados_inmediatos" value="1"
                                   {{ sisig_config('examen_mostrar_resultados_inmediatos', true) ? 'checked' : '' }} 
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-sena-green"></div>
                        </label>
                    </div>

                    <!-- Sección: Banner de Mantenimiento / Aviso Global -->
                    <div class="p-6 rounded-2xl bg-amber-50/60 border border-amber-200/90 space-y-4">
                        <div class="flex items-center justify-between gap-4">
                            <div class="space-y-0.5">
                                <h4 class="text-xs font-extrabold text-amber-900 flex items-center gap-2">
                                    <i class="fas fa-bullhorn text-amber-600"></i>
                                    Aviso Global / Modo Mantenimiento
                                </h4>
                                <p class="text-[11px] text-amber-700">
                                    Muestra un banner superior visible en todos los roles (aprendices, editores y administradores).
                                </p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                <input type="checkbox" name="mensaje_mantenimiento_activo" id="mensaje_mantenimiento_activo" value="1"
                                       {{ sisig_config('mensaje_mantenimiento_activo', false) ? 'checked' : '' }} 
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                            </label>
                        </div>

                        <div class="space-y-2 pt-2">
                            <label for="mensaje_mantenimiento_texto" class="block text-xs font-bold text-amber-900">
                                Mensaje Informativo del Aviso
                            </label>
                            <textarea name="mensaje_mantenimiento_texto" id="mensaje_mantenimiento_texto" rows="2"
                                      class="w-full px-4 py-2.5 bg-white border border-amber-300 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition"
                                      placeholder="Escribe el mensaje de advertencia o mantenimiento...">{{ old('mensaje_mantenimiento_texto', sisig_config('mensaje_mantenimiento_texto', 'Atención: La plataforma entrará en mantenimiento preventivo. Por favor guarda tus actividades.')) }}</textarea>
                        </div>
                    </div>

                </div>

                <!-- Botones Inferiores de Acción -->
                <div class="pt-6 border-t border-slate-200 flex items-center justify-end gap-3">
                    <button type="button" onclick="confirmarRestablecerValores()" class="px-4 py-2.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold transition cursor-pointer shadow-xs">
                        Restablecer Valores
                    </button>
                    <button type="submit" class="inline-flex items-center px-6 py-2.5 rounded-xl bg-sena-green hover:bg-emerald-600 text-white text-xs font-bold tracking-wide shadow-sena hover:shadow-lg transition-all duration-200 cursor-pointer">
                        Guardar Cambios
                    </button>
                </div>

            </form>

            <!-- Formulario Oculto para Restablecer Valores por Defecto -->
            <form id="form-reset-defaults" action="{{ route('sisig.admin.configuracion.reset') }}" method="POST" class="hidden">
                @csrf
            </form>

        </div>

    </div>

    <!-- Scripts de Interacción para las Pestañas y Previsualizaciones -->
    <script>
        function cambiarTab(tabId) {
            // Ocultar todos los contenidos
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            
            // Remover estado activo de botones
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('border-sena-green', 'text-sena-green');
                btn.classList.add('border-transparent', 'text-slate-500');
            });

            // Mostrar el tab seleccionado
            const activeTab = document.getElementById(tabId);
            if (activeTab) activeTab.classList.remove('hidden');

            // Activar botón correspondiente
            const btnId = 'btn-' + tabId;
            const activeBtn = document.getElementById(btnId);
            if (activeBtn) {
                activeBtn.classList.remove('border-transparent', 'text-slate-500');
                activeBtn.classList.add('border-sena-green', 'text-sena-green');
            }
        }

        function previsualizarImagen(input, targetId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById(targetId);
                    if (img) img.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // =========================================================================
        // MATRIZ DE LA PALETA DE COLORES (12 columnas x 9 filas, según referencia visual)
        // =========================================================================
        const PALETA_COLUMNAS = [
            // 1. Violeta Oscuro / Índigo
            ['#231955', '#2E2070', '#3E2F8C', '#5343A8', '#6F5FC4', '#8E81D6', '#B0A7E6', '#D2CDF3', '#ECE9FB'],
            // 2. Púrpura
            ['#4A0E4E', '#67156C', '#841C8B', '#A228A9', '#BF3FC5', '#D264D7', '#E28EE5', '#F0B9F2', '#F9DCFA'],
            // 3. Magenta / Frambuesa
            ['#7B0D3F', '#9D1352', '#BD1A64', '#DC2477', '#E74C91', '#EF75AB', '#F59EC4', '#FAC6DC', '#FDE4EE'],
            // 4. Carmesí / Rosa Intenso
            ['#8B0021', '#AF062E', '#D1113E', '#EE2251', '#F25277', '#F67C99', '#FAA6BB', '#FDCED8', '#FEE7EC'],
            // 5. Rojo Vivo
            ['#8A0000', '#B00000', '#D60000', '#F51B1B', '#F85050', '#FA7B7B', '#FCA7A7', '#FDD3D3', '#FEEAEA'],
            // 6. Naranja
            ['#8B3A00', '#B04C00', '#D55E00', '#F4730A', '#F7913E', '#F9AE70', '#FBCCA4', '#FDE5D1', '#FEF3EB'],
            // 7. Amarillo / Ámbar
            ['#846900', '#A98700', '#CDA400', '#EFC30B', '#F3D13F', '#F6DE6F', '#FAEC9F', '#FCF5CF', '#FEFAEB'],
            // 8. Lima / Verde Limón
            ['#556E00', '#708F00', '#8BAF00', '#A7CF07', '#BEDC3B', '#CEE46E', '#DEEFA0', '#EEF7CF', '#F7FBEA'],
            // 9. Verde SENA / Institucional
            ['#00571B', '#007A27', '#1F9936', '#39A900', '#56C426', '#7FDE52', '#A9EE87', '#CEF6B7', '#ECFCE4'],
            // 10. Teal / Turquesa
            ['#005753', '#007873', '#009791', '#05B6AF', '#2CD0C9', '#5EE0DB', '#94ECE8', '#C3F5F3', '#E6FCFB'],
            // 11. Cian / Azul Claro
            ['#004E78', '#006EA6', '#008ED2', '#0AAAF5', '#3EC0F8', '#73D3FA', '#A6E5FC', '#D1F2FD', '#EDFAFE'],
            // 12. Azul Marino / SISIG Dark
            ['#001A29', '#002E4A', '#00456F', '#045E95', '#167CBE', '#459BD3', '#7BBCE6', '#B0DCF4', '#E0F1FA']
        ];

        function inicializarPaleta(containerId, inputId, labelId, previewId, pickerId, isSidebar = false) {
            const container = document.getElementById(containerId);
            const input = document.getElementById(inputId);
            const label = document.getElementById(labelId);
            const preview = document.getElementById(previewId);
            const picker = document.getElementById(pickerId);

            if (!container || !input) return;

            container.innerHTML = '';
            const rowsCount = PALETA_COLUMNAS[0].length;
            const colsCount = PALETA_COLUMNAS.length;
            const initialHex = (input.value || '').toUpperCase();

            for (let r = 0; r < rowsCount; r++) {
                for (let c = 0; c < colsCount; c++) {
                    const hex = PALETA_COLUMNAS[c][r].toUpperCase();
                    const swatch = document.createElement('button');
                    swatch.type = 'button';
                    swatch.className = 'w-full aspect-square rounded-[3px] border border-black/10 hover:border-black/50 hover:scale-125 hover:z-20 hover:shadow-md transition-all duration-150 cursor-pointer relative flex items-center justify-center p-0';
                    swatch.style.backgroundColor = hex;
                    swatch.title = hex;
                    swatch.dataset.hex = hex;

                    if (hex === initialHex) {
                        swatch.classList.add('ring-2', 'ring-slate-900', 'ring-offset-1', 'scale-110', 'z-10', 'shadow-xs');
                        const dot = document.createElement('span');
                        dot.className = 'w-1.5 h-1.5 rounded-full ' + (r < 5 ? 'bg-white' : 'bg-slate-900');
                        swatch.appendChild(dot);
                    }

                    swatch.addEventListener('mouseenter', () => {
                        if (label) label.textContent = hex;
                    });
                    swatch.addEventListener('mouseleave', () => {
                        if (label) label.textContent = input.value.toUpperCase();
                    });

                    swatch.addEventListener('click', () => {
                        aplicarColor(hex, input, label, preview, picker, containerId, isSidebar);
                    });

                    container.appendChild(swatch);
                }
            }

            // Sincronizar input manual de texto
            input.addEventListener('input', function() {
                let val = this.value.trim().toUpperCase();
                if (!val.startsWith('#') && val.length > 0) val = '#' + val;
                if (/^#[0-9A-F]{6}$/i.test(val)) {
                    aplicarColor(val, input, label, preview, picker, containerId, isSidebar);
                }
            });

            // Sincronizar input gotero nativo
            if (picker) {
                picker.addEventListener('input', function() {
                    aplicarColor(this.value.toUpperCase(), input, label, preview, picker, containerId, isSidebar);
                });
            }
        }

        function aplicarColor(hex, input, label, preview, picker, containerId, isSidebar) {
            input.value = hex;
            if (label) label.textContent = hex;
            if (preview) preview.style.backgroundColor = hex;
            if (picker) picker.value = hex;

            if (isSidebar) {
                const sidebarEl = document.getElementById('sidebar-menu');
                if (sidebarEl) sidebarEl.style.backgroundColor = hex;
            }

            // Actualizar el estado visual del swatch seleccionado
            const container = document.getElementById(containerId);
            if (container) {
                container.querySelectorAll('button').forEach(btn => {
                    btn.innerHTML = '';
                    btn.classList.remove('ring-2', 'ring-slate-900', 'ring-offset-1', 'scale-110', 'z-10', 'shadow-xs');
                    if (btn.dataset.hex === hex) {
                        btn.classList.add('ring-2', 'ring-slate-900', 'ring-offset-1', 'scale-110', 'z-10', 'shadow-xs');
                        const dot = document.createElement('span');
                        dot.className = 'w-1.5 h-1.5 rounded-full bg-white shadow-2xs';
                        btn.appendChild(dot);
                    }
                });
            }
        }

        // Inicializar ambas paletas al cargar la página
        document.addEventListener('DOMContentLoaded', () => {
            inicializarPaleta(
                'grid-palette-primario', 
                'color_primario_text', 
                'color_primario_hover_label', 
                'color_preview_btn', 
                'color_primario_picker', 
                false
            );

            inicializarPaleta(
                'grid-palette-sidebar', 
                'color_sidebar_text', 
                'color_sidebar_hover_label', 
                'color_sidebar_preview', 
                'color_sidebar_picker', 
                true
            );
        });

        // Diálogo de confirmación para restablecer valores por defecto
        function confirmarRestablecerValores() {
            if (window.Swal) {
                Swal.fire({
                    title: '¿Restablecer valores por defecto?',
                    text: 'Esta acción restaurará la paleta de colores institucional (Verde SENA y Azul Marino), el nombre oficial y todas las políticas originales del sistema.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, restablecer',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#39A900',
                    cancelButtonColor: '#64748B'
                }).then((result) => {
                    if (result && result.isConfirmed) {
                        const form = document.getElementById('form-reset-defaults');
                        if (form) form.submit();
                    }
                });
            } else {
                if (confirm('¿Deseas restablecer todas las configuraciones a sus valores originales de fábrica?')) {
                    const form = document.getElementById('form-reset-defaults');
                    if (form) form.submit();
                }
            }
        }

        // Control de la Ventana Modal de Permisos de Editores
        function abrirModalPermisos(nombre = 'Editor de Contenidos SISIG', email = 'editor@sena.edu.co', id = '') {
            const modal = document.getElementById('modal-permisos-editor');
            const lblNombre = document.getElementById('modal-editor-nombre');
            const lblEmail = document.getElementById('modal-editor-email');
            const inputId = document.getElementById('modal-editor-id');

            if (lblNombre) lblNombre.textContent = nombre;
            if (lblEmail) lblEmail.textContent = email;
            if (inputId && id) inputId.value = id;

            if (modal) {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
        }

        function cerrarModalPermisos() {
            const modal = document.getElementById('modal-permisos-editor');
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        // Control de la Ventana Modal de Permisos de Editores
        function abrirModalPermisos(nombre = 'Editor de Contenidos SISIG', email = 'editor@sena.edu.co', id = '') {
            const modal = document.getElementById('modal-permisos-editor');
            const lblNombre = document.getElementById('modal-editor-nombre');
            const lblEmail = document.getElementById('modal-editor-email');
            const inputId = document.getElementById('modal-editor-id');

            if (lblNombre) lblNombre.textContent = nombre;
            if (lblEmail) lblEmail.textContent = email;
            if (inputId && id) inputId.value = id;

            if (modal) {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
                sincronizarDropdownDesdeSwitches();
            }
        }

        function cerrarModalPermisos() {
            const modal = document.getElementById('modal-permisos-editor');
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        // Toggle para la barra desplegadora personalizada (Estilo Oficial SISIG)
        function toggleCustomDropdown(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            const menu = document.getElementById('custom-dropdown-menu');
            const chevron = document.getElementById('custom-dropdown-chevron');
            const btn = document.getElementById('custom-dropdown-btn');

            if (menu) {
                const isOpen = !menu.classList.contains('hidden');
                if (isOpen) {
                    menu.classList.add('hidden');
                    if (chevron) chevron.classList.remove('rotate-180');
                    if (btn) btn.classList.remove('border-sena-green', 'ring-2', 'ring-sena-green/20');
                } else {
                    menu.classList.remove('hidden');
                    if (chevron) chevron.classList.add('rotate-180');
                    if (btn) btn.classList.add('border-sena-green', 'ring-2', 'ring-sena-green/20');
                }
            }
        }

        function cerrarCustomDropdown() {
            const menu = document.getElementById('custom-dropdown-menu');
            const chevron = document.getElementById('custom-dropdown-chevron');
            const btn = document.getElementById('custom-dropdown-btn');
            if (menu && !menu.classList.contains('hidden')) {
                menu.classList.add('hidden');
                if (chevron) chevron.classList.remove('rotate-180');
                if (btn) btn.classList.remove('border-sena-green', 'ring-2', 'ring-sena-green/20');
            }
        }

        // Cerrar menú al hacer clic en cualquier otra parte
        document.addEventListener('click', function(e) {
            const container = document.getElementById('custom-dropdown-container');
            if (container && !container.contains(e.target)) {
                cerrarCustomDropdown();
            }
        });

        // Seleccionar opción desde el menú desplegable personalizado
        function seleccionarOpcionCustom(val, labelText) {
            const labelEl = document.getElementById('custom-dropdown-selected-text');
            if (labelEl) labelEl.textContent = labelText;

            // Actualizar checks visuales
            document.querySelectorAll('.custom-opt-check').forEach(chk => {
                chk.classList.add('opacity-0');
                chk.classList.remove('opacity-100');
            });
            const activeChk = document.getElementById('check-opt-' + val);
            if (activeChk) {
                activeChk.classList.remove('opacity-0');
                activeChk.classList.add('opacity-100');
            }

            // Aplicar selección en los switches correspondientes
            seleccionarModuloEnModal(val);

            // Cerrar menú
            cerrarCustomDropdown();
        }

        // Control de Permisos de Módulos en el Modal
        function seleccionarModuloEnModal(val) {
            const checkboxes = document.querySelectorAll('.modal-modulo-cb');
            const statusBanner = document.getElementById('modal-estado-permiso-modulo');

            if (val === 'todos') {
                checkboxes.forEach(cb => cb.checked = true);
                if (statusBanner) {
                    statusBanner.innerHTML = '<i class="fas fa-check-circle text-sena-green text-sm"></i><span>El editor <strong>puede editar todos los módulos</strong> del sistema.</span>';
                }
            } else if (val === 'ninguno') {
                checkboxes.forEach(cb => cb.checked = false);
                if (statusBanner) {
                    statusBanner.innerHTML = '<i class="fas fa-ban text-rose-500 text-sm"></i><span>El editor <strong>no tiene permiso</strong> para editar ningún módulo.</span>';
                }
            } else {
                let nombreModulo = '';
                checkboxes.forEach(cb => {
                    if (cb.value === val) {
                        cb.checked = true;
                        nombreModulo = cb.dataset.nombre || 'Módulo seleccionado';
                    } else {
                        cb.checked = false;
                    }
                });
                if (statusBanner) {
                    statusBanner.innerHTML = `<i class="fas fa-check-circle text-sena-green text-sm"></i><span>El editor <strong>puede editar el módulo: ${nombreModulo}</strong>.</span>`;
                }
            }
        }

        function sincronizarDropdownDesdeSwitches() {
            const checkboxes = document.querySelectorAll('.modal-modulo-cb');
            const labelEl = document.getElementById('custom-dropdown-selected-text');
            const statusBanner = document.getElementById('modal-estado-permiso-modulo');

            const checked = Array.from(checkboxes).filter(cb => cb.checked);

            document.querySelectorAll('.custom-opt-check').forEach(chk => {
                chk.classList.add('opacity-0');
                chk.classList.remove('opacity-100');
            });

            if (checked.length === checkboxes.length) {
                if (labelEl) labelEl.textContent = 'Puede editar: Todos los módulos del sistema';
                const chk = document.getElementById('check-opt-todos');
                if (chk) { chk.classList.remove('opacity-0'); chk.classList.add('opacity-100'); }
                if (statusBanner) {
                    statusBanner.innerHTML = '<i class="fas fa-check-circle text-sena-green text-sm"></i><span>El editor <strong>puede editar todos los módulos</strong> del sistema.</span>';
                }
            } else if (checked.length === 0) {
                if (labelEl) labelEl.textContent = 'Sin permiso: Ningún módulo habilitado';
                const chk = document.getElementById('check-opt-ninguno');
                if (chk) { chk.classList.remove('opacity-0'); chk.classList.add('opacity-100'); }
                if (statusBanner) {
                    statusBanner.innerHTML = '<i class="fas fa-ban text-rose-500 text-sm"></i><span>El editor <strong>no tiene permiso</strong> para editar ningún módulo.</span>';
                }
            } else if (checked.length === 1) {
                const modId = checked[0].value;
                const nombre = checked[0].dataset.nombre || 'Módulo';
                if (labelEl) labelEl.textContent = `Puede editar: Módulo ${nombre}`;
                const chk = document.getElementById('check-opt-' + modId);
                if (chk) { chk.classList.remove('opacity-0'); chk.classList.add('opacity-100'); }
                if (statusBanner) {
                    statusBanner.innerHTML = `<i class="fas fa-check-circle text-sena-green text-sm"></i><span>El editor <strong>puede editar el módulo: ${nombre}</strong>.</span>`;
                }
            } else {
                const nombres = checked.map(c => c.dataset.nombre).join(', ');
                if (labelEl) labelEl.textContent = `Puede editar: ${nombres}`;
                if (statusBanner) {
                    statusBanner.innerHTML = `<i class="fas fa-check-circle text-sena-green text-sm"></i><span>El editor <strong>puede editar: ${nombres}</strong>.</span>`;
                }
            }
        }
    </script>

    <!-- Ventana Modal de Permisos de los Editores (Con Barra Desplegadora y Opciones) -->
    <div id="modal-permisos-editor" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <!-- Backdrop translúcido con desenfoque suave -->
        <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs transition-opacity duration-200" onclick="cerrarModalPermisos()"></div>

        <!-- Ventana Modal con Estilo Idéntico al Sistema -->
        <div class="relative w-full max-w-lg bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 shadow-2xl z-10 space-y-5 animate-scale-up">
            
            <input type="hidden" name="editor_id" id="modal-editor-id" value="{{ isset($editores) && $editores->count() > 0 ? $editores->first()->id : '' }}" form="form-configuracion">

            <!-- Encabezado del Modal -->
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center font-bold text-lg shadow-2xs">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-slate-900 tracking-tight" id="modal-editor-nombre">EDITOR DE CONTENIDOS SISIG</h4>
                        <p class="text-[11px] text-slate-500 font-medium" id="modal-editor-email">editor@sena.edu.co</p>
                    </div>
                </div>
                <button type="button" onclick="cerrarModalPermisos()" class="w-8 h-8 rounded-xl bg-slate-100 text-slate-400 hover:text-slate-700 hover:bg-slate-200 flex items-center justify-center transition cursor-pointer">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>

            <!-- Cuerpo del Modal -->
            <div class="space-y-5 max-h-[65vh] overflow-y-auto pr-1">
                
                <!-- SECCIÓN 1: MÓDULOS QUE SE LE DA PERMISO -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block">
                            Módulos que se le da permiso
                        </span>
                        <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">
                            {{ isset($modulos) ? $modulos->count() : 0 }} Módulos
                        </span>
                    </div>

                    <!-- Barra Desplegadora Personalizada (Estilo Oficial SISIG con Menú Flotante) -->
                    <div class="relative mb-2.5" id="custom-dropdown-container">
                        
                        <!-- Botón Selector Principal -->
                        <button type="button" onclick="toggleCustomDropdown(event)" id="custom-dropdown-btn"
                                class="w-full pl-3.5 pr-4 py-2.5 bg-slate-50 hover:bg-white border border-slate-200 hover:border-sena-green rounded-2xl text-xs font-bold text-slate-800 flex items-center justify-between shadow-2xs transition-all cursor-pointer group">
                            <div class="flex items-center gap-2.5 truncate">
                                <span class="w-7 h-7 rounded-xl bg-emerald-50 text-sena-green flex items-center justify-center text-xs flex-shrink-0 group-hover:scale-105 transition-transform shadow-2xs">
                                    <i class="fas fa-layer-group"></i>
                                </span>
                                <span id="custom-dropdown-selected-text" class="truncate font-bold text-slate-800 text-xs">
                                    Puede editar: Todos los módulos del sistema
                                </span>
                            </div>
                            <i id="custom-dropdown-chevron" class="fas fa-chevron-down text-xs text-slate-400 group-hover:text-sena-green transition-transform duration-200 flex-shrink-0 ml-2"></i>
                        </button>

                        <!-- Menú Desplegable con Estilo Limpio del Sistema -->
                        <div id="custom-dropdown-menu" 
                             class="hidden absolute top-full left-0 right-0 mt-1.5 bg-white rounded-2xl border border-slate-200 shadow-xl z-50 p-1.5 space-y-1 animate-scale-in">
                            
                            <!-- Opción 1: Todos los módulos -->
                            <div onclick="seleccionarOpcionCustom('todos', 'Puede editar: Todos los módulos del sistema')" 
                                 class="custom-dropdown-opt flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold text-slate-700 hover:bg-emerald-50 hover:text-emerald-900 transition-colors cursor-pointer group">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-7 h-7 rounded-lg bg-emerald-50 text-sena-green flex items-center justify-center text-xs group-hover:scale-105 transition-transform">
                                        <i class="fas fa-cubes"></i>
                                    </span>
                                    <span>Puede editar: Todos los módulos del sistema</span>
                                </div>
                                <i class="fas fa-check text-sena-green opacity-100 custom-opt-check" id="check-opt-todos"></i>
                            </div>

                            <!-- Opciones por Módulo -->
                            @if(isset($modulos) && $modulos->count() > 0)
                                @foreach($modulos as $mod)
                                    <div onclick="seleccionarOpcionCustom('{{ $mod->id }}', 'Puede editar: Módulo {{ addslashes($mod->titulo) }}')" 
                                         class="custom-dropdown-opt flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold text-slate-700 hover:bg-emerald-50 hover:text-emerald-900 transition-colors cursor-pointer group"
                                         data-val="{{ $mod->id }}">
                                        <div class="flex items-center gap-2.5">
                                            <span class="w-7 h-7 rounded-lg bg-slate-100 group-hover:bg-emerald-100/60 text-slate-600 group-hover:text-sena-green flex items-center justify-center text-xs group-hover:scale-105 transition-transform">
                                                <i class="{{ $mod->icon_class ?? 'fas fa-layer-group' }}"></i>
                                            </span>
                                            <div class="flex items-center gap-2">
                                                <span>Puede editar: Módulo {{ $mod->titulo }}</span>
                                                <span class="px-1.5 py-0.5 rounded-md text-[9px] font-black uppercase bg-slate-100 text-slate-600">
                                                    {{ $mod->estado }}
                                                </span>
                                            </div>
                                        </div>
                                        <i class="fas fa-check text-sena-green opacity-0 custom-opt-check" id="check-opt-{{ $mod->id }}"></i>
                                    </div>
                                @endforeach
                            @endif

                            <!-- Opción: Ningún módulo -->
                            <div onclick="seleccionarOpcionCustom('ninguno', 'Sin permiso: Ningún módulo habilitado')" 
                                 class="custom-dropdown-opt flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-rose-50 hover:text-rose-800 transition-colors cursor-pointer group border-t border-slate-100 mt-1 pt-1.5">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-7 h-7 rounded-lg bg-rose-50 text-rose-500 flex items-center justify-center text-xs group-hover:scale-105 transition-transform">
                                        <i class="fas fa-ban"></i>
                                    </span>
                                    <span>Sin permiso: Ningún módulo habilitado</span>
                                </div>
                                <i class="fas fa-check text-rose-600 opacity-0 custom-opt-check" id="check-opt-ninguno"></i>
                            </div>

                        </div>
                    </div>

                    <!-- Estado Actual Dinámico de qué módulo puede editar -->
                    <div id="modal-estado-permiso-modulo" class="text-[11px] font-semibold text-emerald-800 bg-emerald-50 border border-emerald-200/80 rounded-xl px-3 py-2 flex items-center gap-2 mb-2.5 shadow-2xs">
                        <i class="fas fa-check-circle text-sena-green text-sm"></i>
                        <span>El editor <strong>puede editar todos los módulos</strong> del sistema.</span>
                    </div>

                    <!-- Opciones de qué módulos hay en el sistema -->
                    <div class="space-y-2 max-h-44 overflow-y-auto pr-1" id="modal-lista-modulos-container">
                        @if(isset($modulos) && $modulos->count() > 0)
                            @foreach($modulos as $mod)
                                @php
                                    $primerEditor = isset($editores) && $editores->count() > 0 ? $editores->first() : null;
                                    $estaAsignado = $primerEditor && $primerEditor->modulosEditables ? $primerEditor->modulosEditables->contains('id', $mod->id) : true;
                                @endphp
                                <div class="modal-modulo-row p-3 rounded-2xl bg-slate-50 border border-slate-200/90 flex items-center justify-between gap-3 hover:border-slate-300 transition"
                                     id="modal-modulo-item-{{ $mod->id }}">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="w-8 h-8 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-sena-green text-xs font-bold flex-shrink-0 shadow-2xs">
                                            <i class="{{ $mod->icon_class ?? 'fas fa-layer-group' }}"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-slate-800 truncate">{{ $mod->titulo }}</p>
                                            <span class="text-[10px] font-medium text-slate-400 truncate block">{{ $mod->norma ?? 'Inducción General' }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        @if($mod->estado === 'publicado')
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                Publicado
                                            </span>
                                        @elseif($mod->estado === 'revision')
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase bg-amber-50 text-amber-700 border border-amber-200">
                                                En Revisión
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase bg-rose-50 text-rose-700 border border-rose-200">
                                                Inactivo
                                            </span>
                                        @endif
                                        <label class="relative inline-flex items-center cursor-pointer ml-1" title="Puede editar este módulo">
                                            <input type="checkbox" name="modulos_editor[]" value="{{ $mod->id }}"
                                                   data-nombre="{{ $mod->titulo }}"
                                                   onchange="sincronizarDropdownDesdeSwitches()"
                                                   form="form-configuracion"
                                                   {{ $estaAsignado ? 'checked' : '' }}
                                                   class="sr-only peer modal-modulo-cb">
                                            <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-sena-green"></div>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="p-3 text-center text-xs text-slate-400 bg-slate-50 rounded-xl">
                                Sin módulos registrados en el sistema.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- SECCIÓN 2: GOBERNANZA Y RESTRICCIONES CONFIGURABLES -->
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block mb-2">
                        Gobernanza y Restricciones Configurables
                    </span>
                    <div class="space-y-2">
                        <!-- Switch 1: Eliminar Evaluaciones -->
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between gap-3">
                            <div class="space-y-0.5 max-w-[260px]">
                                <label for="modal_editor_puede_eliminar_quizzes" class="text-xs font-bold text-slate-900 block cursor-pointer">
                                    Eliminar Evaluaciones
                                </label>
                                <p class="text-[10px] text-slate-500">Permitir a editores borrar exámenes.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                <input type="checkbox" name="editor_puede_eliminar_quizzes" id="modal_editor_puede_eliminar_quizzes" value="1"
                                       form="form-configuracion"
                                       {{ sisig_config('editor_puede_eliminar_quizzes', false) ? 'checked' : '' }} 
                                       class="sr-only peer">
                                <div class="w-10 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-sena-green"></div>
                            </label>
                        </div>

                        <!-- Switch 2: Editar con Intentos -->
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between gap-3">
                            <div class="space-y-0.5 max-w-[260px]">
                                <label for="modal_editor_puede_editar_quizzes_con_respuestas" class="text-xs font-bold text-slate-900 block cursor-pointer">
                                    Editar con Respuestas
                                </label>
                                <p class="text-[10px] text-slate-500">Permitir modificar exámenes ya contestados.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                <input type="checkbox" name="editor_puede_editar_quizzes_con_respuestas" id="modal_editor_puede_editar_quizzes_con_respuestas" value="1"
                                       form="form-configuracion"
                                       {{ sisig_config('editor_puede_editar_quizzes_con_respuestas', false) ? 'checked' : '' }} 
                                       class="sr-only peer">
                                <div class="w-10 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-sena-green"></div>
                            </label>
                        </div>

                        <!-- Switch 3: Aprobación Previa -->
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between gap-3">
                            <div class="space-y-0.5 max-w-[260px]">
                                <label for="modal_editor_requiere_aprobacion_publicacion" class="text-xs font-bold text-slate-900 block cursor-pointer">
                                    Requerir Aprobación
                                </label>
                                <p class="text-[10px] text-slate-500">El contenido queda "En Revisión" hasta aprobarse.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                <input type="checkbox" name="editor_requiere_aprobacion_publicacion" id="modal_editor_requiere_aprobacion_publicacion" value="1"
                                       form="form-configuracion"
                                       {{ sisig_config('editor_requiere_aprobacion_publicacion', false) ? 'checked' : '' }} 
                                       class="sr-only peer">
                                <div class="w-10 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-sena-green"></div>
                            </label>
                        </div>

                        <!-- Input 4: Límite por Módulo -->
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between gap-3">
                            <div class="space-y-0.5 max-w-[260px]">
                                <label for="modal_editor_limite_quizzes_por_modulo" class="text-xs font-bold text-slate-900 block">
                                    Límite Quizzes por Módulo
                                </label>
                                <p class="text-[10px] text-slate-500">Tope de evaluaciones por sección formativa.</p>
                            </div>
                            <input type="number" name="editor_limite_quizzes_por_modulo" id="modal_editor_limite_quizzes_por_modulo" min="1" max="100"
                                   form="form-configuracion"
                                   value="{{ old('editor_limite_quizzes_por_modulo', sisig_config('editor_limite_quizzes_por_modulo', 10)) }}"
                                   class="w-20 px-2.5 py-1.5 bg-white border border-slate-300 rounded-xl text-xs font-bold text-slate-800 text-center focus:outline-none focus:ring-2 focus:ring-sena-green">
                        </div>
                    </div>
                </div>

            </div>

            <!-- Pie del Modal -->
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                <button type="button" onclick="cerrarModalPermisos()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition cursor-pointer">
                    Cerrar
                </button>
                <button type="submit" form="form-configuracion" class="px-5 py-2 rounded-xl bg-sena-green hover:bg-emerald-600 text-white text-xs font-bold transition shadow-sena hover:shadow-lg cursor-pointer">
                    Guardar Cambios
                </button>
            </div>

        </div>
    </div>
</x-sisig::layouts.admin>
