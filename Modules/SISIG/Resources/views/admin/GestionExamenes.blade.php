<x-sisig::layouts.admin>
    <div class="space-y-6 max-w-7xl mx-auto pb-10">

        <!-- Banner Principal -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-sena-green to-emerald-800 p-8 shadow-2xl">
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-white/10 rounded-full blur-3xl mix-blend-overlay"></div>
            <div class="absolute right-20 -bottom-20 w-48 h-48 bg-emerald-400/20 rounded-full blur-2xl"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-3 text-emerald-50 mb-3">
                    <i class="fas fa-clipboard-list text-2xl"></i>
                    <h1 class="text-3xl font-black tracking-tight">Gestión de Exámenes</h1>
                </div>
                <p class="text-emerald-100/90 text-sm max-w-2xl font-medium leading-relaxed">
                    Crea y gestiona los quizzes evaluativos de cada módulo SIG. Configura preguntas, tipos, intentos y fecha límite.
                </p>
                <div class="mt-4 flex items-center gap-2 text-xs font-bold text-emerald-200/80">
                    <i class="fas fa-home"></i> Inicio <i class="fas fa-chevron-right text-[10px] mx-1"></i> Gestión <i class="fas fa-chevron-right text-[10px] mx-1"></i> Exámenes
                </div>
            </div>
        </div>

        <!-- Barra de Filtros y Acciones -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200 flex flex-col md:flex-row gap-4 items-center justify-between relative z-20">
            <form action="{{ route($routePrefix . '.examenes.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 w-full md:w-auto flex-1">
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
            <button onclick="openExamenModal()" class="w-full md:w-auto px-6 py-2.5 rounded-xl bg-sena-green hover:bg-emerald-600 text-white font-bold text-sm shadow-sena hover:shadow-lg transition-all flex items-center justify-center gap-2">
                <i class="fas fa-plus-circle"></i> Crear examen
            </button>
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
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center {{ $quiz->activo ? 'bg-emerald-50 text-emerald-500' : 'bg-slate-100 text-slate-400' }}">
                                    <i class="fas fa-check-circle text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-800 text-lg leading-tight">{{ $quiz->titulo }}</h3>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mt-0.5 block">
                                        <i class="fas fa-tag mr-1"></i> {{ $quiz->modulo->titulo ?? 'Sin Módulo' }}
                                    </span>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-extrabold tracking-wide uppercase {{ $quiz->activo ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $quiz->activo ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
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
                            <button onclick="openExamenModal({{ $quiz->toJson() }})" class="flex-1 py-2 rounded-xl bg-sky-50 hover:bg-sky-100 text-sky-600 font-bold text-xs transition-colors flex items-center justify-center gap-1.5">
                                <i class="fas fa-pen"></i> Editar
                            </button>
                            <form action="{{ route($routePrefix . '.examenes.toggleStatus', $quiz->id_quiz) }}" method="POST" class="flex-1">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full py-2 rounded-xl font-bold text-xs transition-colors flex items-center justify-center gap-1.5 {{ $quiz->activo ? 'bg-amber-50 hover:bg-amber-100 text-amber-600' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-600' }}">
                                    <i class="fas {{ $quiz->activo ? 'fa-ban' : 'fa-check' }}"></i> {{ $quiz->activo ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>
                        </div>
                        <a href="{{ route($routePrefix . '.examenes.preguntas.index', $quiz->id_quiz) }}" class="w-full mt-2 py-2.5 rounded-xl bg-sena-green hover:bg-emerald-700 text-white font-bold text-xs transition-all flex items-center justify-center gap-2 shadow-sena">
                            <i class="fas fa-cogs"></i> Gestionar preguntas
                        </a>
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
        <div class="bg-white rounded-3xl max-w-lg w-full shadow-2xl border border-slate-200 animate-scale-in flex flex-col max-h-[90vh]">
            
            <div class="bg-slate-900 text-white rounded-t-3xl p-5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-sena-green">
                        <i class="fas fa-plus-circle text-sm" id="modal-icon"></i>
                    </div>
                    <h3 id="modal-title" class="font-bold text-base tracking-wide">Crear examen</h3>
                </div>
                <button type="button" onclick="closeExamenModal()" class="text-white/60 hover:text-white transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <div class="p-6 overflow-y-auto">
                <form id="examen-form" action="{{ route($routePrefix . '.examenes.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div id="examen-method"></div>

                    <!-- Nombre -->
                    <div>
                        <label class="text-[11px] font-extrabold text-slate-600 uppercase tracking-wider block mb-1">Nombre *</label>
                        <input type="text" name="titulo" id="quiz-titulo" required placeholder="Ej: Quiz Calidad" 
                               class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Módulo -->
                        <div>
                            <label class="text-[11px] font-extrabold text-slate-600 uppercase tracking-wider block mb-1">Sección SIG *</label>
                            <select name="modulo_id" id="quiz-modulo" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all">
                                <option value="" disabled selected>Selecciona...</option>
                                @foreach($modulos as $modulo)
                                    <option value="{{ $modulo->id }}">{{ $modulo->titulo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Estado -->
                        <div>
                            <label class="text-[11px] font-extrabold text-slate-600 uppercase tracking-wider block mb-1">Estado</label>
                            <select name="activo" id="quiz-activo" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <!-- Intentos -->
                        <div>
                            <label class="text-[11px] font-extrabold text-slate-600 uppercase tracking-wider block mb-1">Intentos *</label>
                            <input type="number" name="numero_intentos" id="quiz-intentos" required min="1" value="3" 
                                   class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all">
                        </div>
                        <!-- Nota Minima -->
                        <div>
                            <label class="text-[11px] font-extrabold text-slate-600 uppercase tracking-wider block mb-1">Nota Min (%) *</label>
                            <input type="number" name="puntaje_minimo" id="quiz-puntaje" required min="1" max="100" value="70" 
                                   class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all">
                        </div>
                        <!-- Tiempo -->
                        <div>
                            <label class="text-[11px] font-extrabold text-slate-600 uppercase tracking-wider block mb-1">Tiempo (Min)</label>
                            <input type="number" name="tiempo_minutos" id="quiz-tiempo" min="0" value="0" 
                                   class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all">
                        </div>
                    </div>

                    <!-- Número de Preguntas (Oculto porque se cuentan dinámicamente o por defecto) -->
                    <input type="hidden" name="numero_preguntas" id="quiz-preguntas" value="0">

                    <!-- Fecha Límite -->
                    <div>
                        <label class="text-[11px] font-extrabold text-slate-600 uppercase tracking-wider block mb-1">Fecha Límite de Entrega</label>
                        <input type="date" name="fecha_limite" id="quiz-fecha" 
                               class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all">
                    </div>

                    <div class="pt-6 flex items-center justify-end gap-3 mt-4">
                        <button type="button" onclick="closeExamenModal()" class="px-5 py-2.5 rounded-xl text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" id="btn-submit" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-sena-green hover:bg-emerald-600 shadow-sena transition-all flex items-center gap-2">
                            <i class="fas fa-save"></i> <span>Crear y gestionar preguntas</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('examen-modal');
        const form = document.getElementById('examen-form');
        const method = document.getElementById('examen-method');

        function openExamenModal(quiz = null) {
            form.reset();
            if (quiz) {
                // Modo Edición
                document.getElementById('modal-title').textContent = 'Editar examen';
                document.getElementById('modal-icon').className = 'fas fa-pen text-sm';
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
                }

                document.getElementById('btn-submit').innerHTML = '<i class="fas fa-save"></i> <span>Guardar cambios</span>';
            } else {
                // Modo Creación
                document.getElementById('modal-title').textContent = 'Crear examen';
                document.getElementById('modal-icon').className = 'fas fa-plus-circle text-sm';
                form.action = "{{ route($routePrefix . '.examenes.store') }}";
                method.innerHTML = '';
                
                document.getElementById('quiz-preguntas').value = 0; // Default

                document.getElementById('btn-submit').innerHTML = '<i class="fas fa-save"></i> <span>Crear y gestionar preguntas</span>';
            }
            modal.classList.remove('hidden');
        }

        function closeExamenModal() {
            modal.classList.add('hidden');
        }
    </script>
</x-sisig::layouts.admin>
