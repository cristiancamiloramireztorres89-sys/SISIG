<x-sisig::layouts.admin title="Gestión de Exámenes">
    <div class="space-y-6 sm:space-y-8 animate-fade-in pb-12">

        <!-- 1. Encabezado de la Sección (Estilo Limpio y Unificado) -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-slate-200">
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-400 font-semibold mb-1">
                    <span class="text-[#2E6F40]">Inicio</span>
                    <span>/</span>
                    <span class="text-slate-600">Exámenes</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-[#343A40] tracking-tight flex items-center gap-2.5">
                    <i class="fas fa-clipboard-list text-[#2E6F40]"></i>
                    <span>Gestión de Exámenes</span>
                </h1>
            </div>

            <div class="self-start lg:self-auto">
                <button type="button" onclick="openExamenModal()"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-xs font-black text-[#1A2B4C] bg-[#33FF57] hover:bg-[#2ae049] shadow-md shadow-emerald-500/20 transition-all transform hover:-translate-y-0.5 cursor-pointer">
                    <i class="fas fa-plus text-xs"></i>
                    <span>+ Crear Examen</span>
                </button>
            </div>
        </div>



        <!-- 2. Tarjetas de Métricas Clave (KPIs con Estilo de Referencia Image 1) -->
        @php
            $totalQuicesCount = $quices->count();
            $quicesActivosCount = $quices->where('activo', 1)->count();
            $quicesInactivosCount = $quices->where('activo', 0)->count();
            $totalPreguntasCount = $quices->sum('numero_preguntas');
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Total Exámenes -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Total Exámenes</span>
                    <div class="w-8 h-8 rounded-full bg-blue-50 text-[#1A2B4C] flex items-center justify-center text-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#343A40] tracking-tight">{{ $totalQuicesCount }}</h3>
                    <div class="mt-1 flex items-center justify-between text-xs text-slate-400 font-medium">
                        <span>Quices en el sistema</span>
                        <span class="text-xs font-bold text-[#2E6F40]">Evaluaciones</span>
                    </div>
                </div>
            </div>

            <!-- Exámenes Activos -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Exámenes Activos</span>
                    <div class="w-8 h-8 rounded-full bg-emerald-50 text-[#2E6F40] flex items-center justify-center text-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#343A40] tracking-tight">{{ $quicesActivosCount }}</h3>
                    <div class="mt-1 flex items-center justify-between text-xs text-slate-400 font-medium">
                        <span>Habilitados para aprendices</span>
                        <span class="text-xs font-bold text-[#2E6F40]">En línea</span>
                    </div>
                </div>
            </div>

            <!-- Exámenes Inactivos -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">En Pausa / Inactivos</span>
                    <div class="w-8 h-8 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center text-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                        <i class="fas fa-pause-circle"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#343A40] tracking-tight">{{ $quicesInactivosCount }}</h3>
                    <div class="mt-1 flex items-center justify-between text-xs text-slate-400 font-medium">
                        <span>Borradores o pausados</span>
                        <span class="text-xs font-bold text-amber-600">Pausados</span>
                    </div>
                </div>
            </div>

            <!-- Total Preguntas -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Banco de Preguntas</span>
                    <div class="w-8 h-8 rounded-full bg-cyan-50 text-[#00BCD4] flex items-center justify-center text-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                        <i class="fas fa-question-circle"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#343A40] tracking-tight">{{ $totalPreguntasCount }}</h3>
                    <div class="mt-1 flex items-center justify-between text-xs text-slate-400 font-medium">
                        <span>Preguntas configuradas</span>
                        <span class="text-xs font-bold text-[#00BCD4]">Reactivos</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barra de Filtros y Acciones -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200 flex flex-col md:flex-row gap-4 items-center justify-between relative z-20">
            <form action="{{ route($routePrefix . '.examenes.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 w-full">
                <div class="relative flex-1 max-w-md">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar examen..." 
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all">
                </div>
                <select name="modulo_id" class="px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-sm text-slate-600 focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all cursor-pointer font-medium min-w-[160px]" onchange="this.form.submit()">
                    <option value="all">Todos los módulos</option>
                    @foreach($modulos as $modulo)
                        <option value="{{ $modulo->id }}" {{ request('modulo_id') == $modulo->id ? 'selected' : '' }}>{{ $modulo->titulo }}</option>
                    @endforeach
                </select>
                <select name="estado" class="px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-sm text-slate-600 focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all cursor-pointer font-medium min-w-[140px]" onchange="this.form.submit()">
                    <option value="all">Todos los estados</option>
                    <option value="activo" {{ request('estado') === 'activo' ? 'selected' : '' }}>Activos</option>
                    <option value="inactivo" {{ request('estado') === 'inactivo' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </form>
        </div>

        <!-- Listado de Exámenes -->
        <div>
            <div class="flex items-center gap-2 text-xs font-black text-slate-400 tracking-wider mb-5 uppercase ml-1">
                <i class="fas fa-clipboard-check text-[#2E6F40]"></i> Exámenes del SIG
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($quices as $quiz)
                    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative group flex flex-col overflow-hidden">
                        <!-- Cabecera de la Tarjeta (Dark Navy Gradient) -->
                        <div class="bg-gradient-to-r from-[#1A2B4C] to-[#243B66] p-5 text-white relative rounded-t-2xl">
                            <div class="flex items-center justify-between gap-3 mb-3">
                                <div class="flex items-center gap-2.5">
                                    @if(!empty($quiz->modulo->imagen))
                                        <img src="{{ asset($quiz->modulo->imagen) }}" alt="{{ $quiz->modulo->titulo ?? 'Módulo' }}" class="w-7 h-7 rounded-full object-cover border border-white/30 shadow-xs flex-shrink-0">
                                    @else
                                        <div class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center text-xs text-white border border-white/20 flex-shrink-0">
                                            <i class="fas fa-clipboard-check"></i>
                                        </div>
                                    @endif
                                    <span class="text-[11px] font-bold text-white/90 bg-white/10 px-2.5 py-0.5 rounded-full border border-white/15 truncate max-w-[170px]" style="color: #ffffff !important;">
                                        {{ $quiz->modulo->titulo ?? 'Módulo General' }}
                                    </span>
                                </div>

                                <!-- Estado Activo / Inactivo -->
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold shadow-xs {{ $quiz->activo ? 'bg-[#33FF57]/20 text-[#33FF57] border border-[#33FF57]/30' : 'bg-slate-500/20 text-slate-300 border border-slate-400/30' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $quiz->activo ? 'bg-[#33FF57]' : 'bg-slate-400' }}"></span>
                                    {{ $quiz->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>

                            <h3 class="font-extrabold text-white text-lg tracking-tight leading-tight line-clamp-1" style="color: #ffffff !important;">
                                {{ $quiz->titulo }}
                            </h3>
                            <p class="text-xs text-slate-200 mt-1 line-clamp-1 font-medium flex items-center gap-1.5" style="color: #e2e8f0 !important;">
                                <i class="fas fa-tag text-[10px] text-slate-300"></i>
                                <span>{{ $quiz->modulo->titulo ?? 'Sin Módulo Asignado' }}</span>
                            </p>
                        </div>

                        <!-- Cuerpo de la Tarjeta con datos 100% reales -->
                        <div class="p-5 flex-1 flex flex-col justify-between bg-white rounded-b-2xl space-y-4">
                            <!-- Badge de la sección -->
                            <div class="w-full bg-slate-50 rounded-xl p-2.5 flex items-center gap-2 border border-slate-100">
                                <span class="w-2 h-2 rounded-full bg-[#2E6F40]"></span>
                                <span class="text-xs font-bold text-slate-700 truncate">{{ $quiz->modulo->titulo ?? 'Módulo General' }}</span>
                            </div>

                            <!-- Métricas: Preguntas, Intentos, Min % -->
                            <div class="grid grid-cols-3 gap-2 p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="flex items-center gap-1 text-[#2E6F40] font-extrabold mb-0.5">
                                        <i class="fas fa-list-ol text-xs"></i>
                                        <span class="text-sm">{{ $quiz->numero_preguntas }}</span>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-500">Preguntas</span>
                                </div>
                                <div class="flex flex-col items-center justify-center border-x border-slate-200">
                                    <div class="flex items-center gap-1 text-sky-600 font-extrabold mb-0.5">
                                        <i class="fas fa-redo text-xs"></i>
                                        <span class="text-sm">{{ $quiz->numero_intentos }}</span>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-500">Intentos</span>
                                </div>
                                <div class="flex flex-col items-center justify-center">
                                    <div class="flex items-center gap-1 text-amber-600 font-extrabold mb-0.5">
                                        <i class="fas fa-medal text-xs"></i>
                                        <span class="text-sm">{{ $quiz->puntaje_minimo }}%</span>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-500">Mínimo</span>
                                </div>
                            </div>

                            <!-- Fecha Límite -->
                            <div class="flex items-center gap-2 text-xs font-medium text-slate-500">
                                <i class="far fa-calendar-alt {{ $quiz->fecha_limite ? 'text-[#2E6F40]' : 'text-slate-400' }}"></i>
                                @if($quiz->fecha_limite)
                                    <span class="text-slate-700 font-bold">Fecha límite: {{ \Carbon\Carbon::parse($quiz->fecha_limite)->format('d M Y') }}</span>
                                @else
                                    <span class="italic text-slate-400">Sin fecha límite asignada</span>
                                @endif
                            </div>

                            <!-- Barra de Aprendices que completaron -->
                            <div class="space-y-1.5 mt-auto">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-medium text-slate-500">Aprendices que completaron</span>
                                    <span class="font-bold {{ $quiz->activo ? 'text-[#2E6F40]' : 'text-slate-400' }}">0%</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden shadow-inner">
                                    <div class="bg-gradient-to-r from-[#2E6F40] to-[#33FF57] h-2 rounded-full transition-all duration-1000" style="width: 0%"></div>
                                </div>
                            </div>

                            <!-- Botones de Acción -->
                            <div class="flex items-center gap-2 pt-3 border-t border-slate-100 mt-auto">
                                <a href="{{ route($routePrefix . '.examenes.preguntas.index', $quiz->id_quiz) }}" 
                                   class="flex-1 py-2.5 px-4 rounded-full bg-[#2E6F40] hover:bg-[#235833] text-white font-black text-xs transition-all flex items-center justify-center gap-2 shadow-sm hover:shadow-md">
                                    <i class="fas fa-cogs text-xs"></i>
                                    <span>Gestionar preguntas</span>
                                </a>
                                <button type="button" onclick="openExamenModal({{ $quiz->toJson() }})" title="Editar examen" 
                                        class="w-9 h-9 rounded-full bg-slate-50 hover:bg-[#1A2B4C] border border-slate-200 hover:border-[#1A2B4C] text-slate-500 hover:text-white transition-all flex items-center justify-center cursor-pointer shadow-2xs" aria-label="Editar">
                                    <i class="fas fa-pencil-alt text-xs"></i>
                                </button>
                                <form id="form-toggle-quiz-{{ $quiz->id_quiz }}" action="{{ route($routePrefix . '.examenes.toggleStatus', $quiz->id_quiz) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="button" onclick="confirmarToggleQuiz(document.getElementById('form-toggle-quiz-{{ $quiz->id_quiz }}'), '{{ addslashes($quiz->titulo) }}', {{ $quiz->activo ? 'true' : 'false' }})" 
                                            title="{{ $quiz->activo ? 'Desactivar examen' : 'Activar examen' }}" 
                                            class="w-9 h-9 rounded-full bg-slate-50 hover:bg-rose-500 border border-slate-200 hover:border-rose-500 text-slate-500 hover:text-white transition-all flex items-center justify-center cursor-pointer shadow-2xs" aria-label="{{ $quiz->activo ? 'Desactivar' : 'Activar' }}">
                                        <i class="fas {{ $quiz->activo ? 'fa-ban' : 'fa-check' }} text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16 bg-white rounded-3xl border border-slate-200 border-dashed">
                        <i class="fas fa-paste text-4xl text-slate-300 mb-3 block"></i>
                        <h3 class="text-lg font-bold text-slate-700 mb-1">No hay exámenes registrados</h3>
                        <p class="text-sm text-slate-500 mb-4">Aún no has creado ningún quiz para los módulos.</p>
                        <button onclick="openExamenModal()" class="px-5 py-2.5 rounded-full bg-[#2E6F40] hover:bg-[#235833] text-white font-bold text-sm shadow-sm transition-all cursor-pointer">
                            <i class="fas fa-plus mr-1"></i> Crear mi primer examen
                        </button>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Modal Crear / Editar Examen -->
    <div id="examen-modal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-7 shadow-2xl border border-slate-200 animate-scale-in max-h-[90vh] overflow-y-auto relative">
            
            <!-- Header Modal Limpio -->
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <i class="fas fa-clipboard-list text-2xl text-sena-green" id="modal-icon"></i>
                    <div>
                        <h3 id="modal-title" class="text-base font-bold text-slate-800">Crear Examen Evaluativo</h3>
                        <p id="modal-subtitle" class="text-xs text-slate-500 mt-0.5">Configura las reglas de evaluación y aprobación del quiz</p>
                    </div>
                </div>
                <button type="button" onclick="closeExamenModal()" class="text-slate-400 hover:text-slate-600 p-2 rounded-xl hover:bg-slate-100 transition-colors">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <!-- Formulario -->
            <form id="examen-form" action="{{ route($routePrefix . '.examenes.store') }}" method="POST" class="mt-5 space-y-4">
                @csrf
                <div id="examen-method"></div>

                <!-- Nombre -->
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Nombre del Examen *</label>
                    <input type="text" name="titulo" id="quiz-titulo" required placeholder="Ej. Quiz Calidad y Normativa SIG" 
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <!-- Módulo -->
                    <div>
                        <label class="text-xs font-bold text-slate-700 block mb-1">Sección SIG *</label>
                        <select name="modulo_id" id="quiz-modulo" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all cursor-pointer">
                            <option value="" disabled selected>Selecciona una sección...</option>
                            @foreach($modulos as $modulo)
                                <option value="{{ $modulo->id }}">{{ $modulo->titulo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Estado -->
                    <div>
                        <label class="text-xs font-bold text-slate-700 block mb-1">Estado *</label>
                        <select name="activo" id="quiz-activo" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all cursor-pointer">
                            <option value="1">Activo (Habilitado)</option>
                            <option value="0">Inactivo (Deshabilitado)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                    <!-- Intentos -->
                    <div>
                        <label class="text-xs font-bold text-slate-700 block mb-1">Intentos *</label>
                        <input type="number" name="numero_intentos" id="quiz-intentos" required min="1" value="3" 
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all">
                    </div>
                    <!-- Nota Minima -->
                    <div>
                        <label class="text-xs font-bold text-slate-700 block mb-1">Nota Mínima (%) *</label>
                        <input type="number" name="puntaje_minimo" id="quiz-puntaje" required min="1" max="100" value="70" 
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all">
                    </div>
                    <!-- Tiempo -->
                    <div>
                        <label class="text-xs font-bold text-slate-700 block mb-1">Tiempo (Minutos)</label>
                        <input type="number" name="tiempo_minutos" id="quiz-tiempo" min="0" value="0" placeholder="0 = Ilimitado" 
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all">
                    </div>
                </div>

                <!-- Número de Preguntas (Oculto) -->
                <input type="hidden" name="numero_preguntas" id="quiz-preguntas" value="0">

                <!-- Fecha Límite -->
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Fecha Límite de Entrega (Opcional)</label>
                    <input type="date" name="fecha_limite" id="quiz-fecha" 
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all">
                </div>

                <!-- Footer de Botones -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeExamenModal()" class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-slate-600 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 transition-all">
                        Cancelar
                    </button>
                    <button type="submit" id="btn-submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-white bg-sena-green hover:bg-emerald-600 shadow-sena hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                        <i class="fas fa-save text-xs"></i> <span>Crear y gestionar preguntas</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('examen-modal');
        const form = document.getElementById('examen-form');
        const method = document.getElementById('examen-method');
        let quizOriginalSnapshot = null;

        function getQuizFormData() {
            return {
                titulo: document.getElementById('quiz-titulo').value.trim(),
                modulo_id: document.getElementById('quiz-modulo').value,
                activo: document.getElementById('quiz-activo').value,
                numero_intentos: String(document.getElementById('quiz-intentos').value).trim(),
                puntaje_minimo: String(document.getElementById('quiz-puntaje').value).trim(),
                tiempo_minutos: String(document.getElementById('quiz-tiempo').value || '0').trim(),
                fecha_limite: document.getElementById('quiz-fecha').value
            };
        }

        function openExamenModal(quiz = null) {
            form.reset();
            const icon = document.getElementById('modal-icon');
            const title = document.getElementById('modal-title');
            const subtitle = document.getElementById('modal-subtitle');
            const btnSubmit = document.getElementById('btn-submit');

            if (quiz) {
                // Modo Edición
                title.textContent = 'Editar Examen Evaluativo';
                subtitle.textContent = 'Modifica los parámetros y condiciones de este examen';
                icon.className = 'fas fa-edit text-2xl text-sena-green';
                form.action = `{{ url(str_replace('.', '/', $routePrefix) . '/examenes') }}/${quiz.id_quiz}`;
                method.innerHTML = '<input type="hidden" name="_method" value="PUT">';

                document.getElementById('quiz-titulo').value = quiz.titulo;
                document.getElementById('quiz-modulo').value = quiz.modulo_id || '';
                document.getElementById('quiz-activo').value = quiz.activo ? '1' : '0';
                document.getElementById('quiz-intentos').value = quiz.numero_intentos;
                document.getElementById('quiz-puntaje').value = quiz.puntaje_minimo;
                document.getElementById('quiz-tiempo').value = quiz.tiempo_minutos || 0;
                document.getElementById('quiz-preguntas').value = quiz.numero_preguntas;
                if (quiz.fecha_limite) {
                    document.getElementById('quiz-fecha').value = quiz.fecha_limite.split('T')[0];
                } else {
                    document.getElementById('quiz-fecha').value = '';
                }

                btnSubmit.innerHTML = '<i class="fas fa-save text-xs"></i> <span>Guardar Cambios</span>';
                quizOriginalSnapshot = JSON.stringify(getQuizFormData());
            } else {
                // Modo Creación
                title.textContent = 'Crear Examen Evaluativo';
                subtitle.textContent = 'Configura las reglas de evaluación y aprobación del quiz';
                icon.className = 'fas fa-clipboard-list text-2xl text-sena-green';
                form.action = "{{ route($routePrefix . '.examenes.store') }}";
                method.innerHTML = '';
                
                document.getElementById('quiz-preguntas').value = 0; // Default
                document.getElementById('quiz-fecha').value = '';

                btnSubmit.innerHTML = '<i class="fas fa-save text-xs"></i> <span>Crear y gestionar preguntas</span>';
                quizOriginalSnapshot = null;
            }
            modal.classList.remove('hidden');
            if (window.lockBodyScroll) window.lockBodyScroll(true);
        }

        form.addEventListener('submit', function(e) {
            if (quizOriginalSnapshot) {
                const currentData = JSON.stringify(getQuizFormData());
                if (currentData === quizOriginalSnapshot) {
                    e.preventDefault();
                    closeExamenModal();
                    if (window.mostrarAlertaBanner) {
                        window.mostrarAlertaBanner('No se detectaron modificaciones en el examen.', 'info');
                    }
                    return false;
                }
            }
        });

        function closeExamenModal() {
            modal.classList.add('hidden');
            if (window.lockBodyScroll) window.lockBodyScroll(false);
        }

        // Cerrar modal al hacer clic en el backdrop oscuro
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeExamenModal();
            }
        });

        // Cerrar modal al presionar Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeExamenModal();
            }
        });

        function confirmarToggleQuiz(form, tituloQuiz, activo) {
            if (!form) return;
            window.abrirModalConfirmacion({
                tipo: activo ? 'warning' : 'success',
                titulo: activo ? '¿Desactivar examen?' : '¿Activar examen?',
                descripcion: activo ? 'El examen dejará de estar disponible para los aprendices.' : 'El examen volverá a estar habilitado y visible para los aprendices.',
                item: `"${tituloQuiz}"`,
                itemTag: 'Examen seleccionado',
                btnTexto: activo ? 'Sí, desactivar' : 'Sí, activar',
                btnIcon: activo ? 'fa-pause-circle' : 'fa-check',
                onConfirm: form
            });
        }
    </script>
</x-sisig::layouts.admin>
