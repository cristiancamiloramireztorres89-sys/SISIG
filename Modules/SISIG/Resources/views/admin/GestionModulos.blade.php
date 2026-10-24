<x-sisig::layouts.admin title="Gestión de Módulos SIG">
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
    </style>
    <div class="space-y-6 sm:space-y-8 animate-fade-in pb-12">

        <!-- 1. Encabezado de la Sección (Estilo Limpio y Unificado) -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-5 border-b border-slate-200">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                    <i class="fas fa-layer-group text-sena-green"></i>
                    <span>{{ auth()->user()->hasRole('admin_sisig') ? 'Gestión de Módulos SIG' : 'Gestión de Contenidos SIG' }}</span>
                </h1>
            </div>

            @if(auth()->user()->hasRole('admin_sisig'))
                <div class="self-start lg:self-auto">
                    <button type="button" onclick="abrirModalCrearModulo()"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-xs font-bold text-white bg-sena-green hover:bg-emerald-600 shadow-sena hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                        <i class="fas fa-plus text-xs"></i>
                        <span>Nuevo Módulo</span>
                    </button>
                </div>
            @endif
        </div>



        <!-- 2. Tarjetas de Métricas Clave (KPIs) con Estilo Premium del Dashboard -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            
            <!-- Activos -->
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Total Creados</span>
                    <i class="fas fa-layer-group text-2xl text-blue-600 group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight">{{ $totalActivos }}</h3>
                    <div class="mt-2.5 flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Módulos en el sistema</span>
                    </div>
                </div>
            </div>

            <!-- Publicados -->
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-sena-green to-[#237000]"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Publicados</span>
                    <i class="fas fa-eye text-2xl text-sena-green group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight">{{ $publicados }}</h3>
                    <div class="mt-2.5 flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Visibles para aprendices</span>
                    </div>
                </div>
            </div>

            <!-- En revisión -->
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-amber-400 to-orange-500"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">En Revisión</span>
                    <i class="fas fa-pencil-alt text-2xl text-amber-500 group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight">{{ $revision }}</h3>
                    <div class="mt-2.5 flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Borradores pendientes</span>
                    </div>
                </div>
            </div>

            <!-- Inactivos -->
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-rose-500 to-red-600"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Inactivos</span>
                    <i class="fas fa-archive text-2xl text-rose-500 group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight">{{ $inactivos }}</h3>
                    <div class="mt-2.5 flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Ocultos o dados de baja</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Barra de Búsqueda y Acciones Estilo Premium -->
        <div class="bg-white rounded-3xl p-4 sm:p-5 border border-slate-200/90 shadow-sm flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="relative w-full md:w-96">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" id="filtro-buscar" placeholder="Buscar módulo por nombre o norma..." 
                       class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-2 focus:ring-sena-green/50 transition-all outline-none text-slate-700 placeholder-slate-400 shadow-inner">
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <select id="filtro-seccion" class="bg-slate-50 border border-slate-200 text-slate-700 font-medium text-sm rounded-2xl px-4 py-3 focus:ring-2 focus:ring-sena-green/50 outline-none w-full sm:w-auto cursor-pointer transition-all hover:bg-slate-100">
                    <option value="">Todas las secciones</option>
                    @foreach($modulos as $m)
                        <option value="{{ strtolower($m->titulo) }}">{{ $m->titulo }}</option>
                    @endforeach
                </select>
                
                <select id="filtro-estado" class="bg-slate-50 border border-slate-200 text-slate-700 font-medium text-sm rounded-2xl px-4 py-3 focus:ring-2 focus:ring-sena-green/50 outline-none w-full sm:w-auto cursor-pointer transition-all hover:bg-slate-100">
                    <option value="">Todos los estados</option>
                    <option value="publicado">Publicado</option>
                    <option value="revision">En revisión</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>
        </div>

        <!-- 4. Listado de Módulos o Estado Vacío -->
        <div class="space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-800 flex items-center gap-2.5">
                        <i class="fas fa-layer-group text-sena-green"></i>
                        <span>Catálogo del Proceso de Inducción</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Administra las temáticas y recursos de aprendizaje para los aprendices</p>
                </div>
            </div>

            @if($modulos->isEmpty())
                <!-- Estado vacío ilustrado -->
                <div class="py-16 px-6 text-center rounded-3xl bg-white border border-dashed border-slate-300 shadow-sm flex flex-col items-center justify-center">
                    <div class="w-20 h-20 rounded-3xl bg-slate-50 border border-slate-200 text-slate-400 flex items-center justify-center text-3xl mb-5 shadow-inner">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-800">Aún no hay módulos creados</h4>
                    <p class="text-sm text-slate-500 max-w-md mx-auto mt-2 leading-relaxed mb-6">
                        El sistema se encuentra en desarrollo inicial. Empieza agregando el primer módulo (por ejemplo: SST, Calidad, o Ambiental) para estructurar el proceso de inducción.
                    </p>
                    @if(auth()->user()->hasRole('admin_sisig'))
                        <button onclick="document.getElementById('modalCrearModulo').classList.remove('hidden')" 
                                class="px-6 py-2.5 bg-sena-green hover:bg-sena-green-hover text-white font-bold rounded-xl shadow-sena transition-all">
                            <i class="fas fa-plus mr-2"></i> Crear mi primer módulo
                        </button>
                    @endif
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($modulos as $modulo)
                        <div class="modulo-card bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative group flex flex-col h-full"
                             data-titulo="{{ strtolower($modulo->titulo) }}"
                             data-norma="{{ strtolower($modulo->norma ?? '') }}"
                             data-estado="{{ strtolower($modulo->estado) }}">

                            <div class="flex items-start justify-between mb-5">
                                <div class="flex items-center gap-3.5">
                                    @if($modulo->imagen)
                                        <img src="{{ asset($modulo->imagen) }}" alt="{{ $modulo->titulo }}" class="w-14 h-14 object-cover rounded-2xl shadow-sm border border-slate-200/80 flex-shrink-0">
                                    @else
                                        <div class="w-14 h-14 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center flex-shrink-0 text-sena-green shadow-sm">
                                            <i class="{{ $modulo->icon_class ?? 'fas fa-layer-group' }} text-2xl"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h3 class="font-extrabold text-slate-800 text-lg leading-tight">{{ $modulo->titulo }}</h3>
                                        <div class="flex items-center gap-1.5 mt-1 text-slate-500 text-xs font-medium">
                                            <i class="fas fa-tags text-slate-400"></i>
                                            <span>{{ $modulo->norma ?? 'Sin norma' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Etiqueta de Estado Limpia (Sin cuadro ni punto) -->
                            <div class="absolute top-6 right-6">
                                @if($modulo->estado == 'publicado')
                                    <span class="text-xs font-black tracking-wide uppercase text-emerald-600">
                                        Publicado
                                    </span>
                                @elseif($modulo->estado == 'revision')
                                    <span class="text-xs font-black tracking-wide uppercase text-amber-600">
                                        En Revisión
                                    </span>
                                @else
                                    <span class="text-xs font-black tracking-wide uppercase text-rose-600">
                                        Inactivo
                                    </span>
                                @endif
                            </div>

                            <!-- Descripción -->
                            <p class="text-slate-600 text-sm leading-relaxed mb-6 flex-grow">
                                {{ $modulo->descripcion }}
                            </p>

                            <!-- Progreso (Conectado a la BD) -->
                            <div class="mb-6 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                                <div class="flex justify-between text-xs mb-2">
                                    <span class="text-slate-600 font-bold">Aprendices que completaron</span>
                                    <span class="font-black text-sena-green">{{ $modulo->progreso ?? 0 }}%</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden shadow-inner">
                                    <div class="bg-sena-green h-2 rounded-full transition-all" style="width: {{ $modulo->progreso ?? 0 }}%"></div>
                                </div>
                            </div>

                            <!-- Acciones -->
                            <div class="flex items-center justify-between pt-5 border-t border-slate-100 mt-auto">
                                <div class="flex items-center gap-2 text-xs text-slate-400 font-bold">
                                    <i class="far fa-calendar-alt"></i>
                                    <span>{{ $modulo->created_at->format('d M Y') }}</span>
                                </div>
                                
                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="abrirModalEditarModulo({{ json_encode($modulo) }})" title="Editar módulo" class="w-8 h-8 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-sena-green hover:border-emerald-200 hover:bg-emerald-50 transition-all flex items-center justify-center cursor-pointer shadow-xs">
                                        <i class="fas fa-pencil-alt text-xs"></i>
                                    </button>
                                    <form id="form-eliminar-modulo-{{ $modulo->id }}" action="{{ route('sisig.admin.modulos.destroy', $modulo->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmarEliminarModulo('form-eliminar-modulo-{{ $modulo->id }}', '{{ addslashes($modulo->titulo) }}')" title="Eliminar módulo" class="w-8 h-8 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-rose-500 hover:border-rose-200 hover:bg-rose-50 transition-all flex items-center justify-center cursor-pointer shadow-xs">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Mensaje de no resultados para filtros -->
                <div id="no-results-msg" class="hidden py-12 px-6 text-center rounded-3xl bg-white border border-slate-200 shadow-sm">
                    <i class="fas fa-search text-3xl text-slate-300 mb-3 block"></i>
                    <p class="text-sm font-bold text-slate-700">No se encontraron módulos coincidentes con la búsqueda o filtro aplicado.</p>
                </div>
            @endif
        </div>

    </div>

    <!-- 5. Modal Crear Módulo -->
    <div id="modalCrearModulo" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <!-- Backdrop oscuro -->
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer" onclick="cerrarModalCrearModulo()"></div>
        
        <!-- Modal Contenedor -->
        <div class="relative w-full max-w-lg bg-white rounded-3xl p-6 sm:p-7 shadow-2xl border border-slate-200 animate-scale-in max-h-[90vh] overflow-y-auto">
            
            <!-- Header Modal Limpio con Icono Verde -->
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <i class="fas fa-layer-group text-2xl text-sena-green"></i>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Registrar Nuevo Módulo</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Creación de módulo y asignación de estado para el SIG</p>
                    </div>
                </div>
                <button type="button" onclick="cerrarModalCrearModulo()" class="text-slate-400 hover:text-slate-600 p-2 rounded-xl hover:bg-slate-100 transition-colors cursor-pointer">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            
            <!-- Formulario -->
            <form action="{{ route('sisig.admin.modulos.store') }}" method="POST" enctype="multipart/form-data" class="mt-5 space-y-4" id="formCrearModulo">
                @csrf
                
                <!-- Input oculto para la imagen recortada en Base64 -->
                <input type="hidden" name="imagen_recortada" id="crear-modulo-imagen-recortada">

                <!-- Sección de Imagen con Recorte y Ajuste -->
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1.5 flex items-center justify-between">
                        <span>Imagen del Módulo</span>
                        <span class="text-[11px] text-slate-400 font-normal">Personalizable</span>
                    </label>

                    <input type="file" id="input-imagen-modulo" accept="image/png, image/jpeg, image/jpg, image/webp" class="hidden">

                    <!-- Dropzone / Selector de Imagen -->
                    <div id="dropzone-imagen-modulo" onclick="document.getElementById('input-imagen-modulo').click()" 
                         class="border-2 border-dashed border-slate-200 hover:border-emerald-400 bg-slate-50 hover:bg-emerald-50/40 rounded-2xl p-4 text-center cursor-pointer transition-all group">
                        <div class="flex flex-col items-center justify-center gap-1.5">
                            <div class="w-11 h-11 rounded-2xl bg-white shadow-xs border border-slate-200 flex items-center justify-center text-slate-400 group-hover:text-sena-green group-hover:scale-110 transition-all">
                                <i class="fas fa-camera text-base"></i>
                            </div>
                            <div class="text-xs font-bold text-slate-700 group-hover:text-emerald-700">
                                Seleccionar imagen para el módulo
                            </div>
                            <p class="text-[11px] text-slate-400">Podrás recortar y acomodarla a tu gusto (JPG, PNG, WEBP)</p>
                        </div>
                    </div>

                    <!-- Previsualización de Imagen Recortada -->
                    <div id="preview-imagen-modulo-container" class="hidden flex items-center gap-4 p-3 bg-emerald-50/50 border border-emerald-200 rounded-2xl">
                        <img id="preview-imagen-modulo" src="" alt="Previsualización" class="w-16 h-16 object-cover rounded-xl border border-emerald-200 shadow-sm flex-shrink-0">
                        <div class="flex-grow min-w-0">
                            <div class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                <i class="fas fa-check-circle text-emerald-600 text-xs"></i>
                                <span>Imagen configurada</span>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-0.5">Recorte y encuadre aplicado correctamente.</p>
                            <div class="flex items-center gap-2 mt-2">
                                <button type="button" onclick="reabrirCropper()" class="text-xs text-sena-green hover:underline font-bold flex items-center gap-1 cursor-pointer">
                                    <i class="fas fa-crop-alt"></i> Reajustar
                                </button>
                                <span class="text-slate-300">|</span>
                                <button type="button" onclick="document.getElementById('input-imagen-modulo').click()" class="text-xs text-slate-600 hover:text-slate-900 font-medium flex items-center gap-1 cursor-pointer">
                                    <i class="fas fa-exchange-alt"></i> Cambiar
                                </button>
                                <span class="text-slate-300">|</span>
                                <button type="button" onclick="quitarImagenModulo()" class="text-xs text-rose-500 hover:text-rose-700 font-medium flex items-center gap-1 cursor-pointer">
                                    <i class="fas fa-trash-alt"></i> Quitar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Nombre del Módulo *</label>
                    <input type="text" name="titulo" required placeholder="Ej. Presentación Organizacional" 
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all">
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Estado de Publicación *</label>
                    <select name="estado" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all cursor-pointer">
                        <option value="publicado">Publicado (Visible para aprendices)</option>
                        <option value="revision">En Revisión (Borrador)</option>
                        <option value="inactivo">Inactivo (Oculto)</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Descripción del Módulo</label>
                    <textarea name="descripcion" rows="3" placeholder="Descripción breve del contenido y alcance del módulo..." 
                              class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all resize-y"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" onclick="cerrarModalCrearModulo()" 
                            class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-slate-600 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 transition-all cursor-pointer">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-white bg-sena-green hover:bg-emerald-600 shadow-sena hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                        <i class="fas fa-save text-xs"></i>
                        <span>Guardar Módulo</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 6. Modal Editar Módulo (Instancia Única y Centralizada) -->
    <div id="modalEditarModulo" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <!-- Backdrop oscuro -->
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="cerrarModalEditarModulo()"></div>
        
        <div class="relative w-full max-w-lg bg-white rounded-3xl p-6 sm:p-7 shadow-2xl border border-slate-200 animate-scale-in max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <i class="fas fa-edit text-2xl text-sena-green"></i>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Editar Módulo</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Modifica los detalles y estado del módulo SIG</p>
                    </div>
                </div>
                <button type="button" onclick="cerrarModalEditarModulo()" class="text-slate-400 hover:text-slate-600 p-2 rounded-xl hover:bg-slate-100 transition-colors cursor-pointer">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            
            <form id="formEditarModulo" action="" method="POST" enctype="multipart/form-data" class="mt-5 space-y-4">
                @csrf
                @method('PUT')
                
                <!-- Input oculto para la imagen recortada en Base64 -->
                <input type="hidden" name="imagen_recortada" id="edit-modulo-imagen-recortada">

                <!-- Sección de Imagen con Recorte y Reemplazo -->
                <div class="text-left">
                    <label class="text-xs font-bold text-slate-700 block mb-1.5 flex items-center justify-between">
                        <span>Imagen del Módulo</span>
                        <span class="text-[11px] text-slate-400 font-normal">Reemplazar o personalizar</span>
                    </label>

                    <input type="file" id="edit-input-imagen-modulo" accept="image/png, image/jpeg, image/jpg, image/webp" class="hidden">

                    <!-- Dropzone cuando no hay imagen cargada -->
                    <div id="edit-dropzone-imagen-modulo" onclick="document.getElementById('edit-input-imagen-modulo').click()" 
                         class="border-2 border-dashed border-slate-200 hover:border-emerald-400 bg-slate-50 hover:bg-emerald-50/40 rounded-2xl p-4 text-center cursor-pointer transition-all group">
                        <div class="flex flex-col items-center justify-center gap-1.5">
                            <div class="w-11 h-11 rounded-2xl bg-white shadow-xs border border-slate-200 flex items-center justify-center text-slate-400 group-hover:text-sena-green group-hover:scale-110 transition-all">
                                <i class="fas fa-camera text-base"></i>
                            </div>
                            <div class="text-xs font-bold text-slate-700 group-hover:text-emerald-700">
                                Seleccionar imagen para el módulo
                            </div>
                            <p class="text-[11px] text-slate-400">Podrás recortar y acomodarla a tu gusto (JPG, PNG, WEBP)</p>
                        </div>
                    </div>

                    <!-- Previsualización de Imagen (Actual o Nueva Recortada) -->
                    <div id="edit-preview-imagen-modulo-container" class="hidden flex items-center gap-4 p-3 bg-emerald-50/50 border border-emerald-200 rounded-2xl">
                        <img id="edit-preview-imagen-modulo" src="" alt="Previsualización" class="w-16 h-16 object-cover rounded-xl border border-emerald-200 shadow-sm flex-shrink-0">
                        <div class="flex-grow min-w-0">
                            <div class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                <i class="fas fa-check-circle text-emerald-600 text-xs"></i>
                                <span id="edit-label-imagen-estado">Imagen actual del módulo</span>
                            </div>
                            <p id="edit-sub-imagen-estado" class="text-[11px] text-slate-500 mt-0.5">Puedes reemplazarla seleccionando una nueva imagen.</p>
                            <div class="flex items-center gap-2 mt-2">
                                <button type="button" onclick="document.getElementById('edit-input-imagen-modulo').click()" class="text-xs text-sena-green hover:underline font-bold flex items-center gap-1 cursor-pointer">
                                    <i class="fas fa-exchange-alt"></i> Reemplazar imagen
                                </button>
                                <span id="edit-sep-reajustar" class="text-slate-300 hidden">|</span>
                                <button type="button" id="edit-btn-reajustar" onclick="reabrirCropperEdit()" class="hidden text-xs text-slate-600 hover:text-slate-900 font-medium flex items-center gap-1 cursor-pointer">
                                    <i class="fas fa-crop-alt"></i> Reajustar
                                </button>
                                <span id="edit-sep-deshacer" class="text-slate-300 hidden">|</span>
                                <button type="button" id="edit-btn-deshacer" onclick="deshacerCambioImagenEdit()" class="hidden text-xs text-rose-500 hover:text-rose-700 font-medium flex items-center gap-1 cursor-pointer">
                                    <i class="fas fa-undo"></i> Deshacer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-left">
                    <label class="text-xs font-bold text-slate-700 block mb-1">Nombre del Módulo *</label>
                    <input type="text" name="titulo" id="edit-modulo-titulo" required 
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all">
                </div>

                <div class="text-left">
                    <label class="text-xs font-bold text-slate-700 block mb-1">Estado de Publicación *</label>
                    <select name="estado" id="edit-modulo-estado" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all cursor-pointer">
                        <option value="publicado">Publicado (Visible)</option>
                        <option value="revision">En Revisión (Borrador)</option>
                        <option value="inactivo">Inactivo (Oculto)</option>
                    </select>
                </div>

                <div class="text-left">
                    <label class="text-xs font-bold text-slate-700 block mb-1">Descripción del Módulo</label>
                    <textarea name="descripcion" id="edit-modulo-descripcion" rows="3" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all resize-y"></textarea>
                </div>

                <div class="flex items-center justify-between gap-3 pt-4 border-t border-slate-100">
                    <button type="button" id="btn-eliminar-desde-modal" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50 px-3 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer">
                        <i class="fas fa-trash-alt text-xs"></i>
                        <span>Eliminar</span>
                    </button>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="cerrarModalEditarModulo()" class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-slate-600 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 transition-all cursor-pointer">
                            Cancelar
                        </button>
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-white bg-sena-green hover:bg-emerald-600 shadow-sena hover:shadow-lg transition-all transform hover:-translate-y-0.5 cursor-pointer">
                            <i class="fas fa-save text-xs"></i>
                            <span>Guardar Cambios</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Interactivo de Recorte / Encuadre de Imagen (Estilo Screenshot) -->
    <div id="modalRecortarImagen" class="fixed inset-0 z-[120] hidden flex flex-col bg-[#050811] text-white animate-fade-in select-none">
        <!-- Header superior discreto -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-800/80 bg-slate-950/60 z-10">
            <div class="flex items-center gap-2">
                <i class="fas fa-crop-alt text-emerald-400 text-sm"></i>
                <span class="text-xs sm:text-sm font-bold tracking-wide text-slate-200">Acomodar imagen del módulo</span>
            </div>
            <div class="text-xs text-slate-400 hidden sm:block">
                Arrastra para mover o usa la rueda del ratón para acercar/alejar
            </div>
        </div>

        <!-- Área Central de Recorte con Cropper.js -->
        <div class="flex-grow relative flex items-center justify-center overflow-hidden p-4">
            <div class="w-full max-w-4xl h-[65vh] flex items-center justify-center">
                <img id="cropper-image-target" src="" alt="Ajustar imagen" class="max-w-full max-h-full block">
            </div>
        </div>

        <!-- Barra Inferior de Acciones (Fiel a la captura proporcionada) -->
        <div class="px-8 py-5 border-t border-slate-800/80 bg-slate-950/90 flex items-center justify-between z-10">
            <!-- Botón Cancelar (Verde) -->
            <button type="button" onclick="cancelarRecorte()" class="text-emerald-400 hover:text-emerald-300 font-bold text-sm sm:text-base px-3 py-2 transition-colors cursor-pointer">
                Cancelar
            </button>

            <!-- Herramientas centrales: Rotar y Zoom -->
            <div class="flex items-center gap-3">
                <button type="button" onclick="cropperRotar(-90)" title="Girar 90° hacia la izquierda" class="w-10 h-10 rounded-full bg-slate-900 border border-slate-700 hover:border-slate-500 text-slate-300 hover:text-white flex items-center justify-center transition-all cursor-pointer">
                    <i class="fas fa-undo-alt text-xs"></i>
                </button>
                <button type="button" onclick="cropperRotar(90)" title="Rotar imagen" class="w-12 h-12 rounded-full bg-slate-800 border border-slate-600 hover:border-emerald-400 text-white hover:text-emerald-400 flex items-center justify-center shadow-lg transition-all cursor-pointer text-base">
                    <i class="fas fa-sync-alt"></i>
                </button>
                <button type="button" onclick="cropperZoom(0.1)" title="Acercar (Zoom +)" class="w-10 h-10 rounded-full bg-slate-900 border border-slate-700 hover:border-slate-500 text-slate-300 hover:text-white flex items-center justify-center transition-all cursor-pointer">
                    <i class="fas fa-search-plus text-xs"></i>
                </button>
                <button type="button" onclick="cropperZoom(-0.1)" title="Alejar (Zoom -)" class="w-10 h-10 rounded-full bg-slate-900 border border-slate-700 hover:border-slate-500 text-slate-300 hover:text-white flex items-center justify-center transition-all cursor-pointer">
                    <i class="fas fa-search-minus text-xs"></i>
                </button>
            </div>

            <!-- Botón OK (Verde) -->
            <button type="button" onclick="confirmarRecorte()" class="text-emerald-400 hover:text-emerald-300 font-bold text-sm sm:text-base px-3 py-2 transition-colors cursor-pointer">
                OK
            </button>
        </div>
    </div>

    <!-- Cropper.js Script (Local / Offline) -->
    <script src="{{ asset('modules/sisig/vendor/cropperjs/cropper.min.js') }}"></script>

    <script>
        const rutaUpdateModuloBase = "{{ route('sisig.admin.modulos.update', ':id') }}";
        const baseAssetUrl = "{{ asset('') }}";
        let moduloOriginalSnapshot = null;
        let moduloEditandoActual = null;

        function getModuloFormData() {
            return {
                titulo: document.getElementById('edit-modulo-titulo').value.trim(),
                estado: document.getElementById('edit-modulo-estado').value,
                descripcion: document.getElementById('edit-modulo-descripcion').value.trim(),
                imagen_recortada: document.getElementById('edit-modulo-imagen-recortada').value
            };
        }

        function abrirModalEditarModulo(modulo) {
            moduloEditandoActual = modulo;
            const modal = document.getElementById('modalEditarModulo');
            const form = document.getElementById('formEditarModulo');
            form.action = rutaUpdateModuloBase.replace(':id', modulo.id);
            document.getElementById('edit-modulo-titulo').value = modulo.titulo || '';
            document.getElementById('edit-modulo-estado').value = modulo.estado || 'publicado';
            document.getElementById('edit-modulo-descripcion').value = modulo.descripcion || '';

            // Reiniciar estado de imagen en edición
            const editHiddenInput = document.getElementById('edit-modulo-imagen-recortada');
            const editFileInput = document.getElementById('edit-input-imagen-modulo');
            const editDropzone = document.getElementById('edit-dropzone-imagen-modulo');
            const editPreviewContainer = document.getElementById('edit-preview-imagen-modulo-container');
            const editPreviewImg = document.getElementById('edit-preview-imagen-modulo');
            const editLabel = document.getElementById('edit-label-imagen-estado');
            const editSub = document.getElementById('edit-sub-imagen-estado');
            const editBtnReajustar = document.getElementById('edit-btn-reajustar');
            const editBtnDeshacer = document.getElementById('edit-btn-deshacer');
            const editSepReajustar = document.getElementById('edit-sep-reajustar');
            const editSepDeshacer = document.getElementById('edit-sep-deshacer');

            editHiddenInput.value = '';
            editFileInput.value = '';
            currentRawImageSrcEditar = null;

            if (modulo.imagen) {
                const imgUrl = (modulo.imagen.startsWith('http') || modulo.imagen.startsWith('/'))
                    ? modulo.imagen
                    : (baseAssetUrl + modulo.imagen.replace(/^\//, ''));
                editPreviewImg.src = imgUrl;
                editDropzone.classList.add('hidden');
                editPreviewContainer.classList.remove('hidden');
                editLabel.textContent = 'Imagen actual del módulo';
                editSub.textContent = 'Puedes reemplazarla seleccionando una nueva imagen.';
                editBtnReajustar.classList.add('hidden');
                editSepReajustar.classList.add('hidden');
                editBtnDeshacer.classList.add('hidden');
                editSepDeshacer.classList.add('hidden');
            } else {
                editPreviewImg.src = '';
                editPreviewContainer.classList.add('hidden');
                editDropzone.classList.remove('hidden');
                editBtnReajustar.classList.add('hidden');
                editSepReajustar.classList.add('hidden');
                editBtnDeshacer.classList.add('hidden');
                editSepDeshacer.classList.add('hidden');
            }

            moduloOriginalSnapshot = JSON.stringify(getModuloFormData());

            // Configurar botón eliminar dentro del modal de edición
            document.getElementById('btn-eliminar-desde-modal').onclick = function() {
                cerrarModalEditarModulo();
                confirmarEliminarModulo('form-eliminar-modulo-' + modulo.id, modulo.titulo);
            };

            modal.classList.remove('hidden');
            if (window.lockBodyScroll) window.lockBodyScroll(true);
        }

        function abrirModalCrearModulo() {
            document.getElementById('modalCrearModulo').classList.remove('hidden');
            if (window.lockBodyScroll) window.lockBodyScroll(true);
        }

        function cerrarModalCrearModulo() {
            document.getElementById('modalCrearModulo').classList.add('hidden');
            if (window.lockBodyScroll) window.lockBodyScroll(false);
        }

        document.getElementById('formEditarModulo').addEventListener('submit', function(e) {
            if (moduloOriginalSnapshot) {
                const currentData = JSON.stringify(getModuloFormData());
                if (currentData === moduloOriginalSnapshot) {
                    e.preventDefault();
                    cerrarModalEditarModulo();
                    if (window.mostrarAlertaBanner) {
                        window.mostrarAlertaBanner('No se detectaron modificaciones en el módulo.', 'info');
                    }
                    return false;
                }
            }
        });

        function cerrarModalEditarModulo() {
            document.getElementById('modalEditarModulo').classList.add('hidden');
            if (window.lockBodyScroll) window.lockBodyScroll(false);
        }

        function confirmarEliminarModulo(formId, tituloModulo) {
            const form = typeof formId === 'string' ? document.getElementById(formId) : formId;
            if (!form) return;

            window.abrirModalConfirmacion({
                tipo: 'danger',
                titulo: '¿Eliminar módulo?',
                descripcion: 'Esta acción no se puede deshacer y retirará el módulo permanentemente del catálogo del SIG.',
                item: `"${tituloModulo}"`,
                itemTag: 'Módulo seleccionado',
                advertencia: 'Se eliminarán también las evaluaciones asociadas a este módulo.',
                btnTexto: 'Sí, eliminar',
                btnIcon: 'fa-trash-alt',
                onConfirm: form
            });
        }

        // ==========================================
        // LÓGICA DE RECORTE Y ENCUADRE (CROPPER.JS)
        // ==========================================
        let cropperInstance = null;
        let cropperContext = 'crear'; // 'crear' | 'editar'
        let currentRawImageSrcCrear = null;
        let currentRawImageSrcEditar = null;

        const inputImagen = document.getElementById('input-imagen-modulo');
        const dropzoneImagen = document.getElementById('dropzone-imagen-modulo');
        const previewContainer = document.getElementById('preview-imagen-modulo-container');
        const previewImg = document.getElementById('preview-imagen-modulo');
        const hiddenInputRecortada = document.getElementById('crear-modulo-imagen-recortada');

        const editInputImagen = document.getElementById('edit-input-imagen-modulo');
        const editDropzone = document.getElementById('edit-dropzone-imagen-modulo');
        const editPreviewContainer = document.getElementById('edit-preview-imagen-modulo-container');
        const editPreviewImg = document.getElementById('edit-preview-imagen-modulo');
        const editHiddenInputRecortada = document.getElementById('edit-modulo-imagen-recortada');
        const editLabelEstado = document.getElementById('edit-label-imagen-estado');
        const editSubEstado = document.getElementById('edit-sub-imagen-estado');
        const editBtnReajustar = document.getElementById('edit-btn-reajustar');
        const editBtnDeshacer = document.getElementById('edit-btn-deshacer');
        const editSepReajustar = document.getElementById('edit-sep-reajustar');
        const editSepDeshacer = document.getElementById('edit-sep-deshacer');

        const modalRecortar = document.getElementById('modalRecortarImagen');
        const cropperTarget = document.getElementById('cropper-image-target');

        // Modo Crear: Selector de archivo
        if (inputImagen) {
            inputImagen.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    if (!file.type.startsWith('image/')) {
                        if (window.mostrarAlertaBanner) {
                            window.mostrarAlertaBanner('Por favor selecciona un archivo de imagen válido.', 'error');
                        }
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function(evt) {
                        cropperContext = 'crear';
                        currentRawImageSrcCrear = evt.target.result;
                        iniciarCropper(currentRawImageSrcCrear);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Modo Editar: Selector de archivo para reemplazar imagen
        if (editInputImagen) {
            editInputImagen.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    if (!file.type.startsWith('image/')) {
                        if (window.mostrarAlertaBanner) {
                            window.mostrarAlertaBanner('Por favor selecciona un archivo de imagen válido.', 'error');
                        }
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function(evt) {
                        cropperContext = 'editar';
                        currentRawImageSrcEditar = evt.target.result;
                        iniciarCropper(currentRawImageSrcEditar);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Soporte Drag & Drop sobre la zona de subida (Crear)
        if (dropzoneImagen) {
            ['dragenter', 'dragover'].forEach(eventName => {
                dropzoneImagen.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    dropzoneImagen.classList.add('border-emerald-500', 'bg-emerald-50');
                });
            });
            ['dragleave', 'drop'].forEach(eventName => {
                dropzoneImagen.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    dropzoneImagen.classList.remove('border-emerald-500', 'bg-emerald-50');
                });
            });
            dropzoneImagen.addEventListener('drop', (e) => {
                e.preventDefault();
                if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                    inputImagen.files = e.dataTransfer.files;
                    const event = new Event('change');
                    inputImagen.dispatchEvent(event);
                }
            });
        }

        // Soporte Drag & Drop sobre la zona de subida (Editar)
        if (editDropzone) {
            ['dragenter', 'dragover'].forEach(eventName => {
                editDropzone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    editDropzone.classList.add('border-emerald-500', 'bg-emerald-50');
                });
            });
            ['dragleave', 'drop'].forEach(eventName => {
                editDropzone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    editDropzone.classList.remove('border-emerald-500', 'bg-emerald-50');
                });
            });
            editDropzone.addEventListener('drop', (e) => {
                e.preventDefault();
                if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                    editInputImagen.files = e.dataTransfer.files;
                    const event = new Event('change');
                    editInputImagen.dispatchEvent(event);
                }
            });
        }

        function iniciarCropper(imgSrc) {
            cropperTarget.src = imgSrc;
            modalRecortar.classList.remove('hidden');
            if (window.lockBodyScroll) window.lockBodyScroll(true);

            if (cropperInstance) {
                cropperInstance.destroy();
            }

            // Inicializar Cropper con cuadrícula y encuadre óptimo
            cropperInstance = new Cropper(cropperTarget, {
                aspectRatio: 1, // Cuadrado óptimo para el icono/tarjeta del módulo
                viewMode: 1,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                autoCropArea: 0.85,
                responsive: true,
                background: false
            });
        }

        function cropperRotar(grados) {
            if (cropperInstance) {
                cropperInstance.rotate(grados);
            }
        }

        function cropperZoom(ratio) {
            if (cropperInstance) {
                cropperInstance.zoom(ratio);
            }
        }

        function cancelarRecorte() {
            modalRecortar.classList.add('hidden');
            if (window.lockBodyScroll) window.lockBodyScroll(false);
            if (cropperInstance) {
                cropperInstance.destroy();
                cropperInstance = null;
            }
            if (cropperContext === 'editar') {
                if (!editHiddenInputRecortada.value) {
                    editInputImagen.value = '';
                }
            } else {
                if (!hiddenInputRecortada.value) {
                    inputImagen.value = '';
                }
            }
        }

        function confirmarRecorte() {
            if (!cropperInstance) return;

            // Obtener canvas recortado en alta calidad
            const canvas = cropperInstance.getCroppedCanvas({
                width: 600,
                height: 600,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high'
            });

            const croppedBase64 = canvas.toDataURL('image/png');

            if (cropperContext === 'editar') {
                editHiddenInputRecortada.value = croppedBase64;
                editPreviewImg.src = croppedBase64;
                editDropzone.classList.add('hidden');
                editPreviewContainer.classList.remove('hidden');
                editLabelEstado.textContent = 'Nueva imagen seleccionada';
                editSubEstado.textContent = 'El reemplazo se guardará al hacer clic en Guardar Cambios.';
                editBtnReajustar.classList.remove('hidden');
                editSepReajustar.classList.remove('hidden');
                editBtnDeshacer.classList.remove('hidden');
                editSepDeshacer.classList.remove('hidden');
            } else {
                hiddenInputRecortada.value = croppedBase64;
                previewImg.src = croppedBase64;
                dropzoneImagen.classList.add('hidden');
                previewContainer.classList.remove('hidden');
            }

            // Cerrar el modal de recorte
            modalRecortar.classList.add('hidden');
            if (window.lockBodyScroll) window.lockBodyScroll(false);
            cropperInstance.destroy();
            cropperInstance = null;
        }

        function reabrirCropper() {
            if (currentRawImageSrcCrear) {
                cropperContext = 'crear';
                iniciarCropper(currentRawImageSrcCrear);
            }
        }

        function reabrirCropperEdit() {
            if (currentRawImageSrcEditar) {
                cropperContext = 'editar';
                iniciarCropper(currentRawImageSrcEditar);
            }
        }

        function deshacerCambioImagenEdit() {
            editHiddenInputRecortada.value = '';
            editInputImagen.value = '';
            currentRawImageSrcEditar = null;

            if (moduloEditandoActual && moduloEditandoActual.imagen) {
                const imgUrl = (moduloEditandoActual.imagen.startsWith('http') || moduloEditandoActual.imagen.startsWith('/'))
                    ? moduloEditandoActual.imagen
                    : (baseAssetUrl + moduloEditandoActual.imagen.replace(/^\//, ''));
                editPreviewImg.src = imgUrl;
                editDropzone.classList.add('hidden');
                editPreviewContainer.classList.remove('hidden');
                editLabelEstado.textContent = 'Imagen actual del módulo';
                editSubEstado.textContent = 'Puedes reemplazarla seleccionando una nueva imagen.';
                editBtnReajustar.classList.add('hidden');
                editSepReajustar.classList.add('hidden');
                editBtnDeshacer.classList.add('hidden');
                editSepDeshacer.classList.add('hidden');
            } else {
                editPreviewImg.src = '';
                editPreviewContainer.classList.add('hidden');
                editDropzone.classList.remove('hidden');
                editBtnReajustar.classList.add('hidden');
                editSepReajustar.classList.add('hidden');
                editBtnDeshacer.classList.add('hidden');
                editSepDeshacer.classList.add('hidden');
            }
        }

        function quitarImagenModulo() {
            hiddenInputRecortada.value = '';
            inputImagen.value = '';
            currentRawImageSrcCrear = null;
            previewImg.src = '';
            previewContainer.classList.add('hidden');
            dropzoneImagen.classList.remove('hidden');
        }

        // ==========================================
        // FILTRADO REACTIVO EN VIVO DE MÓDULOS
        // ==========================================
        const inputBuscar = document.getElementById('filtro-buscar');
        const selectSeccion = document.getElementById('filtro-seccion');
        const selectEstado = document.getElementById('filtro-estado');
        const cards = document.querySelectorAll('.modulo-card');
        const noResultsMsg = document.getElementById('no-results-msg');

        function filtrarModulos() {
            const query = inputBuscar ? inputBuscar.value.toLowerCase().trim() : '';
            const seccion = selectSeccion ? selectSeccion.value.toLowerCase().trim() : '';
            const estado = selectEstado ? selectEstado.value.toLowerCase().trim() : '';

            let visibles = 0;
            cards.forEach(card => {
                const titulo = card.dataset.titulo || '';
                const norma = card.dataset.norma || '';
                const cardEstado = card.dataset.estado || '';

                const matchesQuery = !query || titulo.includes(query) || norma.includes(query);
                const matchesSeccion = !seccion || titulo.includes(seccion);
                const matchesEstado = !estado || cardEstado === estado;

                if (matchesQuery && matchesSeccion && matchesEstado) {
                    card.style.display = '';
                    visibles++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (noResultsMsg) {
                noResultsMsg.classList.toggle('hidden', visibles > 0);
            }
        }

        if (inputBuscar) inputBuscar.addEventListener('input', filtrarModulos);
        if (selectSeccion) selectSeccion.addEventListener('change', filtrarModulos);
        if (selectEstado) selectEstado.addEventListener('change', filtrarModulos);
    </script>
</x-sisig::layouts.admin>
