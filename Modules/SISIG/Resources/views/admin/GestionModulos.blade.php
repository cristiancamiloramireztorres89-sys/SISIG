<x-sisig::layouts.admin title="Gestión de Módulos SIG">
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
                    <button type="button" onclick="document.getElementById('modalCrearModulo').classList.remove('hidden')"
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
                <input type="text" placeholder="Buscar módulo por nombre o norma..." 
                       class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-2 focus:ring-sena-green/50 transition-all outline-none text-slate-700 placeholder-slate-400 shadow-inner">
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <select class="bg-slate-50 border border-slate-200 text-slate-700 font-medium text-sm rounded-2xl px-4 py-3 focus:ring-2 focus:ring-sena-green/50 outline-none w-full sm:w-auto cursor-pointer transition-all hover:bg-slate-100">
                    <option value="">Todas las secciones</option>
                </select>
                
                <select class="bg-slate-50 border border-slate-200 text-slate-700 font-medium text-sm rounded-2xl px-4 py-3 focus:ring-2 focus:ring-sena-green/50 outline-none w-full sm:w-auto cursor-pointer transition-all hover:bg-slate-100">
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
                        <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative group flex flex-col h-full">

                            <div class="flex items-start justify-between mb-5">
                                <div class="flex items-center gap-3.5">
                                    <i class="{{ $modulo->icon_class }} text-2xl sm:text-3xl flex-shrink-0"></i>
                                    <div>
                                        <h3 class="font-extrabold text-slate-800 text-lg leading-tight">{{ $modulo->titulo }}</h3>
                                        <div class="flex items-center gap-1.5 mt-1 text-slate-500 text-xs font-medium">
                                            <i class="fas fa-tags text-slate-400"></i>
                                            <span>{{ $modulo->norma }}</span>
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
            @endif
        </div>

    </div>

    <!-- 5. Modal Crear Módulo -->
    <div id="modalCrearModulo" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <!-- Backdrop oscuro -->
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="document.getElementById('modalCrearModulo').classList.add('hidden')"></div>
        
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
                <button type="button" onclick="document.getElementById('modalCrearModulo').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 p-2 rounded-xl hover:bg-slate-100 transition-colors">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            
            <!-- Formulario -->
            <form action="{{ route('sisig.admin.modulos.store') }}" method="POST" class="mt-5 space-y-4">
                @csrf
                
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
                    <button type="button" onclick="document.getElementById('modalCrearModulo').classList.add('hidden')" 
                            class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-slate-600 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 transition-all">
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
            
            <form id="formEditarModulo" action="" method="POST" class="mt-5 space-y-4">
                @csrf
                @method('PUT')
                
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

    <script>
        const rutaUpdateModuloBase = "{{ route('sisig.admin.modulos.update', ':id') }}";
        let moduloOriginalSnapshot = null;

        function getModuloFormData() {
            return {
                titulo: document.getElementById('edit-modulo-titulo').value.trim(),
                estado: document.getElementById('edit-modulo-estado').value,
                descripcion: document.getElementById('edit-modulo-descripcion').value.trim()
            };
        }

        function abrirModalEditarModulo(modulo) {
            const modal = document.getElementById('modalEditarModulo');
            const form = document.getElementById('formEditarModulo');
            form.action = rutaUpdateModuloBase.replace(':id', modulo.id);
            document.getElementById('edit-modulo-titulo').value = modulo.titulo || '';
            document.getElementById('edit-modulo-estado').value = modulo.estado || 'publicado';
            document.getElementById('edit-modulo-descripcion').value = modulo.descripcion || '';
            moduloOriginalSnapshot = JSON.stringify(getModuloFormData());

            // Configurar botón eliminar dentro del modal de edición
            document.getElementById('btn-eliminar-desde-modal').onclick = function() {
                cerrarModalEditarModulo();
                confirmarEliminarModulo('form-eliminar-modulo-' + modulo.id, modulo.titulo);
            };

            modal.classList.remove('hidden');
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
    </script>
</x-sisig::layouts.admin>
