<x-sisig::layouts.admin title="Panel de Edición SISIG">
    <div class="space-y-6 sm:space-y-8 animate-fade-in pb-8">

        <!-- 1. Banner Principal de Bienvenida del Editor -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#001A29] via-[#002F48] to-[#004266] border border-slate-700/60 p-7 sm:p-9 text-white shadow-2xl">
            <!-- Destellos y brillos ambientales de fondo -->
            <div class="absolute -top-24 -right-24 w-80 h-80 bg-sena-green/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-sky-500/15 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <!-- Columna Izquierda: Información de bienvenida -->
                <div class="space-y-3">
                    <div>
                        <span class="text-sena-green text-xs font-black uppercase tracking-widest flex items-center gap-2">
                            <i class="fas fa-edit"></i>
                            <span>Panel de Edición y Contenidos Pedagógicos SISIG</span>
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-tight">
                        ¡Hola, <span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-slate-100 to-emerald-300">{{ auth()->user()->full_name ?? auth()->user()->name }}</span>!
                    </h1>

                    <p class="text-slate-300 text-xs sm:text-sm max-w-2xl font-normal leading-relaxed">
                        Gestiona los módulos formativos del Sistema Integrado de Gestión, administra materiales pedagógicos, diseña evaluaciones y supervisa el banco de preguntas institucional.
                    </p>
                </div>

                <!-- Columna Derecha: Fecha actual limpia -->
                <div class="flex items-center gap-2.5 self-start lg:self-center bg-white/10 backdrop-blur-md px-4 py-2.5 rounded-2xl border border-white/15 text-white shadow-sm">
                    <i class="far fa-calendar-check text-sena-green text-lg"></i>
                    <span class="text-xs sm:text-sm font-bold text-slate-100 capitalize">
                        {{ now()->isoFormat('dddd, D [de] MMMM') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- 2. Tarjetas de Métricas Editoriales (KPIs) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            
            <!-- KPI 1: Módulos / Secciones -->
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-sky-500 to-blue-600"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Módulos Temáticos</span>
                    <i class="fas fa-layer-group text-2xl text-sky-600 group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="mt-4">
                    <div class="flex items-baseline gap-1.5">
                        <h3 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight">{{ $totalModulos }}</h3>
                        <span class="text-sm font-bold text-slate-400">{{ $totalModulos == 1 ? 'módulo' : 'módulos' }}</span>
                    </div>
                    <div class="mt-2.5 flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Ejes formativos SIG</span>
                        <span class="text-xs font-bold text-sky-600">Registrados</span>
                    </div>
                </div>
            </div>

            <!-- KPI 2: Recursos y Materiales Educativos -->
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-emerald-500 to-teal-600"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Recursos Didácticos</span>
                    <i class="fas fa-file-alt text-2xl text-emerald-600 group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="mt-4">
                    <div class="flex items-baseline gap-1.5">
                        <h3 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight">{{ $totalContenidos }}</h3>
                        <span class="text-sm font-bold text-slate-400">{{ $totalContenidos == 1 ? 'recurso' : 'recursos' }}</span>
                    </div>
                    <div class="mt-2.5 flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">PDFs, videos y guías</span>
                        <span class="text-xs font-bold text-emerald-600">Publicados</span>
                    </div>
                </div>
            </div>

            <!-- KPI 3: Quices y Evaluaciones -->
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-teal-400 to-emerald-600"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Evaluaciones / Quices</span>
                    <i class="fas fa-clipboard-check text-2xl text-teal-600 group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="mt-4">
                    <div class="flex items-baseline gap-1.5">
                        <h3 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight">{{ $totalQuices }}</h3>
                        <span class="text-sm font-bold text-slate-400">{{ $totalQuices == 1 ? 'quiz' : 'quices' }}</span>
                    </div>
                    <div class="mt-2.5 flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">{{ $quicesActivos }} activos en línea</span>
                        <span class="text-xs font-bold text-teal-600">{{ $quicesActivos }}/{{ $totalQuices }}</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- 3. Herramientas Editoriales y Accesos Rápidos -->
        <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-sm">
            <div class="pb-4 border-b border-slate-100">
                <h3 class="text-base sm:text-lg font-bold text-slate-800 flex items-center gap-2.5">
                    <i class="fas fa-tools text-sena-green"></i>
                    <span>Herramientas de Gestión de Contenidos</span>
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Acciones directas para editar y nutrir la formación de los aprendices en SISIG</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-5">
                <!-- Acción 1: Gestión de Módulos -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 hover:border-sky-200 hover:bg-sky-50/20 transition-all group flex flex-col justify-between">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-layer-group text-2xl text-sky-600 mt-1 flex-shrink-0 group-hover:scale-110 transition-transform"></i>
                        <div>
                            <h4 class="text-xs sm:text-sm font-bold text-slate-800">Gestión de Módulos</h4>
                            <p class="text-[11px] text-slate-500 mt-1">Crear nuevas áreas formativas, reorganizar el orden y actualizar títulos y descripciones.</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-200/60 flex justify-end">
                        <button onclick="Swal.fire({ title: 'Gestión de Módulos', text: 'Módulo en fase de integración por el equipo.', icon: 'info', confirmButtonColor: '#39A900' })" 
                                class="text-xs font-bold text-sky-600 hover:text-sky-800 inline-flex items-center gap-1.5 transition-colors">
                            <span>Administrar módulos</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </button>
                    </div>
                </div>

                <!-- Acción 2: Quices y Banco de Preguntas -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/20 transition-all group flex flex-col justify-between">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-tasks text-2xl text-emerald-600 mt-1 flex-shrink-0 group-hover:scale-110 transition-transform"></i>
                        <div>
                            <h4 class="text-xs sm:text-sm font-bold text-slate-800">Quices y Preguntas</h4>
                            <p class="text-[11px] text-slate-500 mt-1">Configurar cuestionarios, ajustar puntajes mínimos, límites de intentos y reactivos evaluativos.</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-200/60 flex justify-end">
                        <button onclick="Swal.fire({ title: 'Quices y Evaluaciones', text: 'Módulo en fase de integración por el equipo.', icon: 'info', confirmButtonColor: '#39A900' })" 
                                class="text-xs font-bold text-emerald-600 hover:text-emerald-800 inline-flex items-center gap-1.5 transition-colors">
                            <span>Gestionar evaluaciones</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </button>
                    </div>
                </div>

                <!-- Acción 3: Recursos y Material Multimedia -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 hover:border-amber-200 hover:bg-amber-50/20 transition-all group flex flex-col justify-between">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-cloud-upload-alt text-2xl text-amber-500 mt-1 flex-shrink-0 group-hover:scale-110 transition-transform"></i>
                        <div>
                            <h4 class="text-xs sm:text-sm font-bold text-slate-800">Recursos Multimedia</h4>
                            <p class="text-[11px] text-slate-500 mt-1">Subir archivos PDF normativos, enlaces a videos explicativos e infografías de bioseguridad y calidad.</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-200/60 flex justify-end">
                        <button onclick="Swal.fire({ title: 'Recursos Multimedia', text: 'Módulo en fase de integración por el equipo.', icon: 'info', confirmButtonColor: '#39A900' })" 
                                class="text-xs font-bold text-amber-600 hover:text-amber-800 inline-flex items-center gap-1.5 transition-colors">
                            <span>Subir recursos</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Resumen de Módulos Existentes en el Repositorio -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-800 flex items-center gap-2.5">
                        <i class="fas fa-book-open text-sena-green"></i>
                        <span>Estado de los Módulos de Inducción SIG</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Control y resumen de las áreas temáticas actualmente registradas en el sistema</p>
                </div>
                <span class="text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1 rounded-xl self-start sm:self-auto">
                    Total: {{ $modulos->count() }}
                </span>
            </div>

            @if($modulos->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($modulos as $mod)
                        <div class="p-5 rounded-2xl border border-slate-200/80 hover:border-slate-300 bg-white hover:shadow-md transition-all flex flex-col justify-between space-y-4">
                            <div>
                                <div class="flex items-center justify-between gap-2">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $mod->meta['bg'] }} {{ $mod->meta['color'] }} border {{ $mod->meta['border'] }}">
                                        {{ $mod->meta['badge'] }}
                                    </span>
                                    <span class="text-[11px] font-extrabold text-slate-400">Orden #{{ $mod->orden }}</span>
                                </div>
                                <h3 class="text-sm font-bold text-slate-800 mt-2.5 flex items-center gap-2">
                                    <i class="{{ $mod->meta['icono'] }} {{ $mod->meta['color'] }}"></i>
                                    <span>{{ $mod->nombre }}</span>
                                </h3>
                                <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                                    {{ $mod->descripcion ?? 'Sin descripción registrada para esta sección.' }}
                                </p>
                            </div>

                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
                                <span class="flex items-center gap-1.5">
                                    <i class="fas fa-file text-slate-400"></i>
                                    <strong>{{ $mod->contenidos_count }}</strong> recursos
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <i class="fas fa-question text-slate-400"></i>
                                    <strong>{{ $mod->preguntas_count }}</strong> preguntas
                                </span>
                                @if($mod->quiz && $mod->quiz->activo)
                                    <span class="text-emerald-600 font-bold flex items-center gap-1">
                                        <i class="fas fa-check-circle text-[10px]"></i> Quiz activo
                                    </span>
                                @else
                                    <span class="text-slate-400 font-medium">Sin quiz</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Estado vacío si aún no hay módulos registrados -->
                <div class="text-center py-12 px-4 rounded-2xl bg-slate-50 border border-dashed border-slate-200 space-y-3">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center text-2xl text-slate-400">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <h4 class="text-sm font-bold text-slate-700">No hay módulos temáticos registrados todavía</h4>
                    <p class="text-xs text-slate-500 max-w-md mx-auto leading-relaxed">
                        A medida que tu equipo registre módulos en la tabla <code class="text-emerald-600 bg-white px-1.5 py-0.5 rounded border border-slate-200">sisig_secciones</code>, aparecerán listados aquí automáticamente con su conteo de recursos y evaluaciones.
                    </p>
                </div>
            @endif
        </div>

    </div>
</x-sisig::layouts.admin>
