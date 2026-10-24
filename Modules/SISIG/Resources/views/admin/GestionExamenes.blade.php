<x-sisig::layouts.admin title="Gestión de Exámenes">
    <div class="space-y-6 sm:space-y-8 animate-fade-in pb-12">

        <!-- 1. Encabezado de la Sección (Estilo Limpio y Unificado) -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-5 border-b border-slate-200">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                    <i class="fas fa-clipboard-list text-sena-green"></i>
                    <span>Gestión de Exámenes</span>
                </h1>
            </div>

            <div class="self-start lg:self-auto">
                <button type="button" onclick="openExamenModal()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-xs font-bold text-white bg-sena-green hover:bg-emerald-600 shadow-sena hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                    <i class="fas fa-plus text-xs"></i>
                    <span>Crear Examen</span>
                </button>
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
                <i class="fas fa-clipboard-check text-sena-green"></i> Exámenes del SIG
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($quices as $quiz)
                    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300 relative group overflow-hidden flex flex-col h-full">
                        <!-- Cabecera Card -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-check-circle text-2xl {{ $quiz->activo ? 'text-emerald-500' : 'text-slate-400' }} flex-shrink-0"></i>
                                <div>
                                    <h3 class="font-bold text-slate-800 text-lg leading-tight">{{ $quiz->titulo }}</h3>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mt-0.5 block">
                                        <i class="fas fa-tag mr-1"></i> {{ $quiz->modulo->titulo ?? 'Sin Módulo' }}
                                    </span>
                                </div>
                            </div>
                            <span class="text-xs font-black tracking-wide uppercase {{ $quiz->activo ? 'text-emerald-600' : 'text-slate-400' }}">
                                {{ $quiz->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>

                        <!-- Badge de la sección -->
                        <div class="w-full bg-slate-100/50 rounded-lg p-2 flex items-center gap-2 mb-4 border border-slate-100">
                            <span class="w-2 h-2 rounded-full bg-sena-green"></span>
                            <span class="text-xs font-bold text-slate-600">{{ $quiz->modulo->titulo ?? 'Módulo General' }}</span>
                        </div>

                        <!-- Detalles -->
                        <div class="grid grid-cols-3 gap-2 mb-4">
                            <div class="flex flex-col items-center justify-center p-2 rounded-xl bg-slate-50">
                                <div class="flex items-center gap-1.5 text-emerald-600 font-bold mb-0.5">
                                    <i class="fas fa-list-ol text-xs"></i>
                                    <span class="text-sm">{{ $quiz->numero_preguntas }}</span>
                                </div>
                                <span class="text-[10px] font-bold text-slate-500">Preguntas</span>
                            </div>
                            <div class="flex flex-col items-center justify-center p-2 rounded-xl bg-slate-50">
                                <div class="flex items-center gap-1.5 text-sky-600 font-bold mb-0.5">
                                    <i class="fas fa-redo text-xs"></i>
                                    <span class="text-sm">{{ $quiz->numero_intentos }}</span>
                                </div>
                                <span class="text-[10px] font-bold text-slate-500">Intentos</span>
                            </div>
                            <div class="flex flex-col items-center justify-center p-2 rounded-xl bg-slate-50">
                                <div class="flex items-center gap-1.5 text-amber-600 font-bold mb-0.5">
                                    <i class="fas fa-medal text-xs"></i>
                                    <span class="text-sm">{{ $quiz->puntaje_minimo }}%</span>
                                </div>
                                <span class="text-[10px] font-bold text-slate-500">Min.</span>
                            </div>
                        </div>

                        <!-- Fecha Límite -->
                        <div class="flex items-center gap-2 text-xs font-medium text-slate-500 mb-5">
                            <i class="far fa-calendar-alt {{ $quiz->fecha_limite ? 'text-sena-green' : 'text-slate-400' }}"></i>
                            @if($quiz->fecha_limite)
                                <span class="text-slate-700 font-bold">Fecha límite: {{ \Carbon\Carbon::parse($quiz->fecha_limite)->format('d M Y') }}</span>
                            @else
                                <span class="italic">Sin fecha límite</span>
                            @endif
                        </div>

                        <!-- Progreso -->
                        <div class="mb-5 mt-auto">
                            <div class="flex items-center justify-between text-[11px] mb-2">
                                <span class="font-bold text-slate-600">Aprendices que completaron</span>
                                <span class="font-bold {{ $quiz->activo ? 'text-sena-green' : 'text-slate-400' }}">0%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-sena-green h-1.5 rounded-full transition-all duration-1000" style="width: 0%"></div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="flex items-center gap-2 pt-4 border-t border-slate-100 mt-auto">
                            <a href="{{ route($routePrefix . '.examenes.preguntas.index', $quiz->id_quiz) }}" class="flex-1 py-2.5 rounded-xl bg-sena-green hover:bg-emerald-700 text-white font-bold text-xs transition-all flex items-center justify-center gap-2 shadow-sena">
                                <i class="fas fa-cogs"></i> Gestionar preguntas
                            </a>
                            <button type="button" onclick="openExamenModal({{ $quiz->toJson() }})" title="Editar examen" class="w-8 h-8 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-sena-green hover:border-emerald-200 hover:bg-emerald-50 transition-all flex items-center justify-center cursor-pointer shadow-xs" aria-label="Editar">
                                <i class="fas fa-pencil-alt text-xs"></i>
                            </button>
                            <form id="form-toggle-quiz-{{ $quiz->id_quiz }}" action="{{ route($routePrefix . '.examenes.toggleStatus', $quiz->id_quiz) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="button" onclick="confirmarToggleQuiz(document.getElementById('form-toggle-quiz-{{ $quiz->id_quiz }}'), '{{ addslashes($quiz->titulo) }}', {{ $quiz->activo ? 'true' : 'false' }})" title="{{ $quiz->activo ? 'Desactivar examen' : 'Activar examen' }}" class="w-8 h-8 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-amber-500 hover:border-amber-200 hover:bg-amber-50 transition-all flex items-center justify-center cursor-pointer shadow-xs" aria-label="{{ $quiz->activo ? 'Desactivar' : 'Activar' }}">
                                    <i class="fas {{ $quiz->activo ? 'fa-ban' : 'fa-check' }} text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16 bg-white rounded-3xl border border-slate-200 border-dashed">
                        <i class="fas fa-paste text-4xl text-slate-300 mb-3 block"></i>
                        <h3 class="text-lg font-bold text-slate-700 mb-1">No hay exámenes registrados</h3>
                        <p class="text-sm text-slate-500 mb-4">Aún no has creado ningún quiz para los módulos.</p>
                        <button onclick="openExamenModal()" class="px-5 py-2 rounded-xl bg-sena-green text-white font-bold text-sm shadow-sena hover:bg-emerald-600 transition-all">
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
