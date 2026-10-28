<x-sisig::layouts.admin title="Panel de Edición SISIG">
    <div class="space-y-6 sm:space-y-8 animate-fade-in pb-8">

        <!-- 1. Banner Principal de Bienvenida del Editor -->
        <x-sisig::banner 
            badge="Panel de Edición y Contenidos Pedagógicos SISIG"
            badgeIcon="fas fa-edit"
            title="¡Hola, <span class='text-transparent bg-clip-text bg-gradient-to-r from-white via-slate-100 to-emerald-300'>{{ auth()->user()->full_name ?? auth()->user()->name }}</span>!"
            subtitle="Gestiona los módulos formativos del Sistema Integrado de Gestión, administra materiales pedagógicos, diseña evaluaciones y supervisa el banco de preguntas institucional.">
            <x-slot:action>
                <div class="flex items-center gap-2.5 bg-white/10 backdrop-blur-md px-4 py-2.5 rounded-2xl border border-white/15 text-white shadow-sm">
                    <i class="far fa-calendar-check text-sena-green text-lg"></i>
                    <span class="text-xs sm:text-sm font-bold text-slate-100 capitalize">
                        {{ now()->isoFormat('dddd, D [de] MMMM') }}
                    </span>
                </div>
            </x-slot:action>
        </x-sisig::banner>

        <!-- 2. Tarjetas de Métricas Editoriales (KPIs Reutilizables) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <x-sisig::stat-card 
                title="Módulos Temáticos"
                value="{{ $totalModulos }}"
                icon="fas fa-layer-group"
                color="sky">
                <x-slot:footer>
                    <span class="text-slate-500 font-medium">Ejes formativos SIG</span>
                    <span class="text-xs font-bold text-sky-600">Registrados</span>
                </x-slot:footer>
            </x-sisig::stat-card>

            <x-sisig::stat-card 
                title="Recursos Didácticos"
                value="{{ $totalContenidos }}"
                icon="fas fa-file-alt"
                color="emerald">
                <x-slot:footer>
                    <span class="text-slate-500 font-medium">PDFs, videos y guías</span>
                    <span class="text-xs font-bold text-emerald-600">Publicados</span>
                </x-slot:footer>
            </x-sisig::stat-card>

            <x-sisig::stat-card 
                title="Evaluaciones / Quices"
                value="{{ $totalQuices }}"
                icon="fas fa-clipboard-check"
                color="teal">
                <x-slot:footer>
                    <span class="text-slate-500 font-medium">{{ $quicesActivos }} activos en línea</span>
                    <span class="text-xs font-bold text-teal-600">{{ $quicesActivos }}/{{ $totalQuices }}</span>
                </x-slot:footer>
            </x-sisig::stat-card>
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
                        <a href="{{ route('sisig.editor.contenido.index') }}" 
                           class="text-xs font-bold text-sky-600 hover:text-sky-800 inline-flex items-center gap-1.5 transition-colors">
                            <span>Administrar módulos</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
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
                        <a href="{{ route('sisig.editor.examenes.index') }}" 
                           class="text-xs font-bold text-emerald-600 hover:text-emerald-800 inline-flex items-center gap-1.5 transition-colors">
                            <span>Gestionar evaluaciones</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
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
                        <button type="button" onclick="mostrarAlertaBanner('Recursos Multimedia: Módulo en fase de integración por el equipo.', 'info')" 
                                class="text-xs font-bold text-amber-600 hover:text-amber-800 inline-flex items-center gap-1.5 transition-colors cursor-pointer">
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
                        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative group flex flex-col overflow-hidden">
                            <!-- Cabecera de la Tarjeta (Dark Navy Gradient) -->
                            <div class="bg-gradient-to-r from-[#1A2B4C] to-[#243B66] p-5 text-white relative rounded-t-2xl">
                                <div class="flex items-center justify-between gap-3 mb-3">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center text-xs text-white border border-white/20 flex-shrink-0">
                                            <i class="{{ $mod->meta['icono'] ?? 'fas fa-layer-group' }}"></i>
                                        </div>
                                        <span class="text-[11px] font-bold text-white/90 bg-white/10 px-2.5 py-0.5 rounded-full border border-white/15 truncate max-w-[170px]" style="color: #ffffff !important;">
                                            {{ $mod->meta['badge'] }}
                                        </span>
                                    </div>

                                    <!-- Estado Quiz -->
                                    @if($mod->quiz && $mod->quiz->activo)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-[#33FF57]/20 text-[#33FF57] border border-[#33FF57]/30 shadow-xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#33FF57]"></span>
                                            Quiz Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-slate-500/20 text-slate-300 border border-slate-400/30 shadow-xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            Sin Quiz
                                        </span>
                                    @endif
                                </div>

                                <h3 class="font-extrabold text-white text-lg tracking-tight leading-tight line-clamp-1" style="color: #ffffff !important;">
                                    {{ $mod->nombre }}
                                </h3>
                                <p class="text-xs text-slate-200 mt-1 line-clamp-1 font-medium flex items-center gap-1.5" style="color: #e2e8f0 !important;">
                                    <i class="fas fa-sort-numeric-down text-[10px] text-slate-300"></i>
                                    <span>Orden de formación #{{ $mod->orden }}</span>
                                </p>
                            </div>

                            <!-- Cuerpo de la Tarjeta con datos 100% reales -->
                            <div class="p-5 flex-1 flex flex-col justify-between bg-white rounded-b-2xl space-y-4">
                                <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                    {{ $mod->descripcion ?? 'Sin descripción registrada para esta sección.' }}
                                </p>

                                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600 mt-auto">
                                    <span class="flex items-center gap-1.5">
                                        <i class="fas fa-file text-[#2E6F40]"></i>
                                        <strong>{{ $mod->contenidos_count }}</strong> recursos
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        <i class="fas fa-question text-[#00BCD4]"></i>
                                        <strong>{{ $mod->preguntas_count }}</strong> preguntas
                                    </span>
                                    <a href="{{ route('sisig.editor.contenido.edit', $mod->id) }}" class="inline-flex items-center gap-1 text-[#2E6F40] hover:text-emerald-800 font-bold transition-colors">
                                        <span>Editar</span>
                                        <i class="fas fa-arrow-right text-[10px]"></i>
                                    </a>
                                </div>
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
