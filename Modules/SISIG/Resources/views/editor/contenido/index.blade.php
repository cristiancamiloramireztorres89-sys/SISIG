<x-sisig::layouts.editor title="Gestión de Contenidos">
    <div class="space-y-8 w-full max-w-[1600px] mx-auto pb-10">

        <!-- Banner Principal Editorial -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#001A29] via-[#002F48] to-[#004266] border border-slate-700/60 p-7 sm:p-9 text-white shadow-2xl">
            <div class="absolute -top-24 -right-24 w-80 h-80 bg-sena-green/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-sena-green/15 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="space-y-3">
                    <div>
                        <span class="text-sena-green text-xs font-black uppercase tracking-widest">
                            Panel Editorial
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-tight flex items-center gap-3">
                        <i class="fas fa-layer-group text-sena-green"></i>
                        <span>Gestión de Contenidos SIG</span>
                    </h1>

                    <p class="text-slate-300 text-xs sm:text-sm max-w-2xl font-normal leading-relaxed">
                        Administra y enriquece el material formativo y los contenidos para cada una de las secciones del Sistema Integrado de Gestión.
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
        
        @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Restricción',
                        text: '{{ session('error') }}',
                        icon: 'error',
                        confirmButtonColor: '#e3342f'
                    });
                });
            </script>
        @endif

        <!-- Listado de Módulos para Editor -->
        <div class="space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-800 flex items-center gap-2.5">
                        <i class="fas fa-edit text-sena-green"></i>
                        <span>Módulos Disponibles para Edición</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Selecciona el módulo que deseas enriquecer con nuevo material didáctico.</p>
                </div>
            </div>

            @if($modulos->isEmpty())
                <div class="py-16 px-6 text-center rounded-3xl bg-white border border-dashed border-slate-300 shadow-sm flex flex-col items-center justify-center">
                    <div class="w-20 h-20 rounded-3xl bg-slate-50 border border-slate-200 text-slate-400 flex items-center justify-center text-3xl mb-5 shadow-inner">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-800">No hay módulos asignados</h4>
                    <p class="text-sm text-slate-500 max-w-md mx-auto mt-2 leading-relaxed mb-6">
                        Aún no se han creado módulos en el sistema. El administrador debe crear la estructura primero para que puedas empezar a añadir contenido.
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($modulos as $modulo)
                        <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative group flex flex-col h-full">
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
                                            <span>{{ $modulo->norma ?? 'Sin norma' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
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

                            <div class="flex items-center justify-between pt-5 border-t border-slate-100 mt-auto">
                                <div class="flex gap-2.5 w-full">
                                    <a href="{{ route('sisig.editor.contenido.edit', $modulo->id) }}" class="w-full px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-sena-green hover:bg-sena-green-hover transition-all shadow-sena flex items-center justify-center gap-2">
                                        <i class="fas fa-pencil-alt"></i>
                                        <span>Editar Contenido</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</x-sisig::layouts.admin>