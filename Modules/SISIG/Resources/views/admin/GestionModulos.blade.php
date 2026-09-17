<x-sisig::layouts.admin title="Gestión de Módulos SIG">
    <div class="space-y-8 w-full max-w-[1600px] mx-auto pb-10">

        <!-- 1. Banner Principal Ejecutivo (Estilo del Proyecto SISIG) -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#001A29] via-[#002F48] to-[#004266] border border-slate-700/60 p-7 sm:p-9 text-white shadow-2xl">
            <!-- Destellos y brillos ambientales de fondo -->
            <div class="absolute -top-24 -right-24 w-80 h-80 bg-sena-green/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-sky-500/15 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <!-- Columna Izquierda: Información -->
                <div class="space-y-3">
                    <div>
                        <span class="text-sena-green text-xs font-black uppercase tracking-widest">
                            Panel de Administración
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-tight flex items-center gap-3">
                        <i class="fas fa-layer-group text-sena-green"></i>
                        <span>{{ auth()->user()->hasRole('admin_sisig') ? 'Gestión de Módulos SIG' : 'Gestión de Contenidos SIG' }}</span>
                    </h1>

                    <p class="text-slate-300 text-xs sm:text-sm max-w-2xl font-normal leading-relaxed">
                        @if(auth()->user()->hasRole('admin_sisig'))
                            Crea, edita y administra los módulos del proceso de inducción SIG. Controla el contenido, los quizzes y el estado de cada sección.
                        @else
                            Administra y enriquece el material formativo y los contenidos para cada una de las secciones del SIG.
                        @endif
                    </p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: '¡Éxito!',
                        text: '{{ session('success') }}',
                        icon: 'success',
                        confirmButtonColor: '#39A900'
                    });
                });
            </script>
        @endif

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

                @if(auth()->user()->hasRole('admin_sisig'))
                    <button type="button" onclick="document.getElementById('modalCrearModulo').classList.remove('hidden')"
                            class="bg-sena-green hover:bg-sena-green-hover text-white px-6 py-3 rounded-2xl text-sm font-bold shadow-sena hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 w-full sm:w-auto">
                        <i class="fas fa-plus"></i>
                        <span>Crear Nuevo</span>
                    </button>
                @endif
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
                            <!-- Top Decorator -->
                            <div class="absolute top-0 left-0 right-0 h-1.5 {{ str_replace('bg-', 'bg-', $modulo->icon_bg) }} rounded-t-3xl opacity-80"></div>

                            <div class="flex items-start justify-between mb-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl {{ $modulo->icon_bg }} border {{ $modulo->border_class }} flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                                        <i class="{{ $modulo->icon_class }} text-2xl drop-shadow-sm"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-extrabold text-slate-800 text-lg leading-tight">{{ $modulo->titulo }}</h3>
                                        <div class="flex items-center gap-1.5 mt-1 text-slate-500 text-xs font-medium">
                                            <i class="fas fa-tags text-slate-400"></i>
                                            <span>{{ $modulo->norma }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Etiqueta de Estado -->
                            <div class="absolute top-6 right-6">
                                @if($modulo->estado == 'publicado')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-50 text-emerald-600 border border-emerald-200/60 shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Publicado
                                    </span>
                                @elseif($modulo->estado == 'revision')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-50 text-amber-600 border border-amber-200/60 shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        En Revisión
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-50 text-rose-600 border border-rose-200/60 shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
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
                                
                                <div class="flex gap-2.5">
                                    <button type="button" onclick="document.getElementById('modalEditarModulo-{{ $modulo->id }}').classList.remove('hidden')" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 bg-white border border-slate-200 hover:bg-emerald-50 hover:border-emerald-200 hover:text-emerald-700 transition-all shadow-sm flex items-center gap-1.5">
                                        <i class="fas fa-pencil-alt"></i>
                                        <span>Editar</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Editar Módulo -->
                        <div id="modalEditarModulo-{{ $modulo->id }}" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
                            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="document.getElementById('modalEditarModulo-{{ $modulo->id }}').classList.add('hidden')"></div>
                            
                            <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up">
                                <div class="bg-sena-green px-6 py-4 text-white flex justify-between items-center">
                                    <h3 class="text-lg font-bold flex items-center gap-2.5">
                                        <i class="fas fa-edit"></i> Editar módulo
                                    </h3>
                                    <button type="button" onclick="document.getElementById('modalEditarModulo-{{ $modulo->id }}').classList.add('hidden')" class="text-white/80 hover:text-white transition-colors">
                                        <i class="fas fa-times text-xl font-light"></i>
                                    </button>
                                </div>
                                
                                <form action="{{ route('sisig.admin.modulos.update', $modulo->id) }}" method="POST" class="p-6 md:p-8 space-y-6">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="space-y-1.5 text-left">
                                        <label class="block text-[11px] font-extrabold text-slate-700 uppercase tracking-wider">Nombre del modulo</label>
                                        <input type="text" name="titulo" value="{{ $modulo->titulo }}" required 
                                               class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 focus:ring-2 focus:ring-sena-green/50 outline-none transition-all">
                                    </div>

                                    <div class="space-y-1.5 text-left">
                                        <label class="block text-[11px] font-extrabold text-slate-700 uppercase tracking-wider">Estado</label>
                                        <div class="relative">
                                            <select name="estado" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 focus:ring-2 focus:ring-sena-green/50 outline-none transition-all appearance-none cursor-pointer">
                                                <option value="publicado" {{ $modulo->estado == 'publicado' ? 'selected' : '' }}>Publicado</option>
                                                <option value="revision" {{ $modulo->estado == 'revision' ? 'selected' : '' }}>En Revisión</option>
                                                <option value="inactivo" {{ $modulo->estado == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                                <i class="fas fa-chevron-down text-[10px]"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-1.5 text-left">
                                        <label class="block text-[11px] font-extrabold text-slate-700 uppercase tracking-wider">Descripcion</label>
                                        <textarea name="descripcion" rows="4" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 focus:ring-2 focus:ring-sena-green/50 outline-none transition-all resize-y">{{ $modulo->descripcion }}</textarea>
                                    </div>

                                    <div class="pt-2 flex justify-center items-center gap-4">
                                        <button type="button" onclick="document.getElementById('modalEditarModulo-{{ $modulo->id }}').classList.add('hidden')" class="px-6 py-2.5 rounded-xl text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors border border-transparent">
                                            Cancelar
                                        </button>
                                        <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-[#0f9d58] hover:bg-[#0b8043] transition-colors flex items-center gap-2 shadow-sm border border-transparent">
                                            <i class="fas fa-save"></i> Guardar cambios
                                        </button>
                                    </div>
                                </form>
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
        <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up">
            
            <!-- Header Modal (Estilo Imagen: Verde sólido) -->
            <div class="bg-sena-green px-6 py-4 text-white flex justify-between items-center">
                <h3 class="text-lg font-bold flex items-center gap-2.5">
                    <i class="fas fa-archive"></i> Nuevo modulo
                </h3>
                <button type="button" onclick="document.getElementById('modalCrearModulo').classList.add('hidden')" class="text-white/80 hover:text-white transition-colors">
                    <i class="fas fa-times text-xl font-light"></i>
                </button>
            </div>
            
            <!-- Body del Modal / Formulario -->
            <form action="{{ route('sisig.admin.modulos.store') }}" method="POST" class="p-6 md:p-8 space-y-6">
                @csrf
                
                <!-- Nombre -->
                <div class="space-y-1.5">
                    <label class="block text-[11px] font-extrabold text-slate-700 uppercase tracking-wider">Nombre del modulo</label>
                    <input type="text" name="titulo" required placeholder="Ej: Presentacion Organizacional" 
                           class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 focus:ring-2 focus:ring-sena-green/50 outline-none transition-all placeholder-slate-400">
                </div>

                <!-- Estado -->
                <div class="space-y-1.5">
                    <label class="block text-[11px] font-extrabold text-slate-700 uppercase tracking-wider">Estado</label>
                    <div class="relative">
                        <select name="estado" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 focus:ring-2 focus:ring-sena-green/50 outline-none transition-all appearance-none cursor-pointer">
                            <option value="publicado">Publicado</option>
                            <option value="revision">En Revisión</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                        <!-- Custom Select Arrow -->
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                            <i class="fas fa-chevron-down text-[10px]"></i>
                        </div>
                    </div>
                </div>

                <!-- Descripción -->
                <div class="space-y-1.5">
                    <label class="block text-[11px] font-extrabold text-slate-700 uppercase tracking-wider">Descripcion</label>
                    <textarea name="descripcion" rows="4" placeholder="Descripcion breve del modulo..." 
                              class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 focus:ring-2 focus:ring-sena-green/50 outline-none transition-all resize-y placeholder-slate-400"></textarea>
                </div>

                <!-- Footer Modal (Botones Centrados) -->
                <div class="pt-2 flex justify-center items-center gap-4">
                    <button type="button" onclick="document.getElementById('modalCrearModulo').classList.add('hidden')" 
                            class="px-6 py-2.5 rounded-xl text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors border border-transparent">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-[#0f9d58] hover:bg-[#0b8043] transition-colors flex items-center gap-2 shadow-sm border border-transparent">
                        <i class="fas fa-save"></i> Guardar modulo
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-sisig::layouts.admin>
