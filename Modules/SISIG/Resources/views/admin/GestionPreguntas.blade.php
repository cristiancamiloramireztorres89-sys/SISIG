
<x-sisig::layouts.admin>
    <div class="space-y-6 max-w-5xl mx-auto pb-10">
        


        <!-- Cabecera -->
        <div class="flex items-center justify-between bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
            <div class="flex items-center gap-4">
                <a href="{{ route($routePrefix . '.examenes.index') }}" class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 hover:text-sena-green hover:bg-emerald-50 flex items-center justify-center transition-colors">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="text-xl font-black text-slate-800 tracking-tight">{{ $quiz->titulo }}</h2>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">
                        <i class="fas fa-tag mr-1 text-slate-300"></i> Módulo: {{ $quiz->modulo->titulo ?? 'General' }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-6 text-right">
                <div>
                    <span class="block text-[11px] font-extrabold text-slate-400 uppercase tracking-wide">Puntaje Máximo</span>
                    <span class="text-2xl font-black text-sena-green">100%</span>
                </div>
                <div class="border-l border-slate-200 pl-6">
                    <span class="block text-[11px] font-extrabold text-slate-400 uppercase tracking-wide">Total Preguntas</span>
                    <span class="text-2xl font-black text-slate-700">{{ $quiz->preguntas->count() }}</span>
                </div>
            </div>
        </div>

        @if(!empty($tieneIntentos))
            <!-- Banner Examen Protegido (Solo Lectura) -->
            <div class="bg-amber-50 border border-amber-200/90 rounded-3xl p-6 shadow-xs flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/15 text-amber-600 flex items-center justify-center shrink-0 text-xl font-black">
                    <i class="fas fa-lock"></i>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-200/80 text-amber-900 flex items-center gap-1.5">
                            <i class="fas fa-shield-alt text-xs"></i> Examen con respuestas registradas
                        </span>
                        <span class="text-xs font-bold text-amber-700">Modo Solo Lectura</span>
                    </div>
                    <h3 class="text-base font-black text-slate-800 mb-1">Evaluación protegida contra modificaciones</h3>
                    <p class="text-xs text-slate-600 leading-relaxed font-medium">
                        Este examen ya ha sido presentado por aprendices. Para proteger la validez de los resultados históricos y la integridad de las calificaciones, no se permite agregar, editar ni eliminar preguntas. Puedes usar el botón <strong class="text-slate-800">"Ver"</strong> en cada reactivo para revisar sus enunciados y opciones.
                    </p>
                </div>
            </div>
        @else
            <!-- Selector de Tipo de Pregunta (Diseño Wayground) -->
            <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">
                <h3 class="text-base font-black text-slate-700 mb-6 flex items-center gap-2">
                    <i class="fas fa-magic text-amber-500"></i> Agregar nueva pregunta
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Opción Múltiple -->
                    <button onclick="openPreguntaModal('opcion_multiple')" class="group flex items-center gap-3.5 p-3.5 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-200 transition-all text-left cursor-pointer">
                        <div class="text-emerald-500 text-2xl shrink-0 group-hover:scale-110 transition-transform w-8 flex items-center justify-center">
                            <i class="fas fa-check-square"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-800 group-hover:text-emerald-600 transition-colors">Opción múltiple</h4>
                            <p class="text-[10px] text-slate-500 mt-0.5 font-medium leading-tight">Una sola respuesta correcta.</p>
                        </div>
                    </button>

                    <!-- Selección Múltiple -->
                    <button onclick="openPreguntaModal('seleccion_multiple')" class="group flex items-center gap-3.5 p-3.5 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-200 transition-all text-left cursor-pointer">
                        <div class="text-emerald-500 text-2xl shrink-0 group-hover:scale-110 transition-transform w-8 flex items-center justify-center">
                            <i class="fas fa-check-double"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-800 group-hover:text-emerald-600 transition-colors">Selección múltiple</h4>
                            <p class="text-[10px] text-slate-500 mt-0.5 font-medium leading-tight">Varias respuestas correctas.</p>
                        </div>
                    </button>

                    <!-- Verdadero o Falso -->
                    <button onclick="openPreguntaModal('verdadero_falso')" class="group flex items-center gap-3.5 p-3.5 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-200 transition-all text-left cursor-pointer">
                        <div class="text-2xl shrink-0 group-hover:scale-110 transition-transform w-8 flex items-center justify-center gap-1">
                            <i class="fas fa-check text-emerald-500 text-lg"></i>
                            <i class="fas fa-times text-rose-500 text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-800 group-hover:text-emerald-600 transition-colors">Verdadero o falso</h4>
                            <p class="text-[10px] text-slate-500 mt-0.5 font-medium leading-tight">Evalúa una afirmación.</p>
                        </div>
                    </button>

                    <!-- Espacios en blanco -->
                    <button onclick="openPreguntaModal('espacios_blanco')" class="group flex items-center gap-3.5 p-3.5 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-200 transition-all text-left cursor-pointer">
                        <div class="text-emerald-500 text-2xl shrink-0 group-hover:scale-110 transition-transform w-8 flex items-center justify-center">
                            <i class="fas fa-pen-nib"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-800 group-hover:text-emerald-600 transition-colors">Completa los espacios en blanco</h4>
                        </div>
                    </button>

                    <!-- Respuestas Abiertas -->
                    <button onclick="openPreguntaModal('abierta')" class="group flex items-center gap-3.5 p-3.5 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-200 transition-all text-left cursor-pointer">
                        <div class="text-emerald-500 text-2xl shrink-0 group-hover:scale-110 transition-transform w-8 flex items-center justify-center">
                            <i class="fas fa-align-left"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-800 group-hover:text-emerald-600 transition-colors">Respuestas abiertas</h4>
                            <p class="text-[10px] text-slate-500 mt-0.5 font-medium leading-tight">Texto libre y descriptivo.</p>
                        </div>
                    </button>

                    <!-- Tabla de relleno -->
                    <button onclick="openPreguntaModal('tabla_relleno')" class="group flex items-center gap-3.5 p-3.5 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-200 transition-all text-left cursor-pointer relative overflow-hidden">
                        <div class="text-emerald-500 text-2xl shrink-0 group-hover:scale-110 transition-transform w-8 flex items-center justify-center">
                            <i class="fas fa-table"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-800 group-hover:text-emerald-600 transition-colors flex items-center gap-2">
                                Tabla de relleno
                                <span class="text-[9px] font-black text-emerald-700 uppercase tracking-widest">Nuevo</span>
                            </h4>
                            <p class="text-[10px] text-slate-500 mt-0.5 font-medium leading-tight">Completar celdas.</p>
                        </div>
                    </button>
                </div>
            </div>
        @endif

        <!-- Lista de Preguntas Creadas -->
        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">
            <h3 class="text-base font-black text-slate-700 mb-6">
                Preguntas Configuradas
            </h3>
            
            <div class="space-y-4">
                @forelse($quiz->preguntas as $index => $pregunta)
                    <div class="tarjeta-pregunta-item p-5 rounded-2xl border border-slate-200 bg-slate-50 hover:bg-white transition-colors group flex gap-4">
                        <div class="w-8 h-8 rounded-full bg-sena-green/10 text-sena-green flex items-center justify-center shrink-0 font-black text-sm">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider mb-1 bg-slate-200 text-slate-600">
                                        {{ str_replace('_', ' ', $pregunta->tipo) }}
                                    </span>
                                    <h4 class="text-sm font-bold text-slate-800">{{ $pregunta->pregunta }}</h4>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black bg-emerald-50 text-emerald-700 border border-emerald-200/60" title="Ponderación automática equitativa">
                                        <i class="fas fa-percentage text-[10px] mr-1 text-emerald-500"></i>{{ $pesoPorPregunta }}%
                                    </span>
                                    @if(!empty($tieneIntentos))
                                        <button type="button" onclick="openVerPreguntaModal({{ json_encode($pregunta) }})" title="Ver detalles de la pregunta (modo lectura)" 
                                                class="px-2.5 py-1 rounded-xl bg-white hover:bg-slate-100 border border-slate-300 text-slate-700 hover:text-slate-900 flex items-center gap-1.5 shadow-2xs transition-all text-xs font-bold cursor-pointer">
                                            <i class="fas fa-eye text-xs text-slate-500"></i>
                                            <span>Ver</span>
                                        </button>
                                    @else
                                        <button type="button" onclick="openEditarPreguntaModal({{ json_encode($pregunta) }})" title="Editar pregunta" 
                                                class="w-8 h-8 rounded-full bg-white hover:bg-slate-100 border border-slate-300 text-slate-600 hover:text-slate-900 flex items-center justify-center shadow-2xs transition-all cursor-pointer">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>
                                        <form action="{{ route($routePrefix . '.examenes.preguntas.destroy', [$quiz->id_quiz, $pregunta->id_pregunta]) }}" method="POST" onsubmit="return confirmarEliminarPregunta(event, this, '{{ addslashes(Str::limit($pregunta->pregunta, 60)) }}')" class="m-0 p-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Eliminar pregunta" 
                                                    class="w-8 h-8 rounded-full bg-white hover:bg-rose-50 border border-rose-300 hover:border-rose-400 text-rose-500 hover:text-rose-700 flex items-center justify-center shadow-2xs transition-all cursor-pointer">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            @if($pregunta->opciones->count() > 0)
                                <div class="mt-3 pl-3 border-l-2 border-slate-200 space-y-1.5">
                                    @foreach($pregunta->opciones as $opcion)
                                        <div class="flex items-center gap-2 text-xs text-slate-600">
                                            <i class="fas {{ $opcion->es_correcta ? 'fa-check-circle text-emerald-500' : 'fa-circle text-slate-300 text-[10px]' }}"></i>
                                            <span class="{{ $opcion->es_correcta ? 'font-bold text-slate-700' : '' }}">{{ $opcion->opcion }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @if($pregunta->retroalimentacion)
                                <div class="mt-4 bg-sky-50 p-3 rounded-xl border border-sky-100 flex items-start gap-2">
                                    <i class="fas fa-info-circle text-sky-500 mt-0.5"></i>
                                    <div>
                                        <span class="block text-[10px] font-bold text-sky-700 uppercase tracking-wider mb-0.5">Retroalimentación / Rúbrica</span>
                                        <p class="text-xs text-slate-600 leading-relaxed">{{ $pregunta->retroalimentacion }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <i class="fas fa-puzzle-piece text-4xl text-slate-300 mb-3"></i>
                        <p class="text-sm font-bold text-slate-600">Aún no hay preguntas</p>
                        <p class="text-xs text-slate-400 mt-1">Haz clic en alguno de los tipos arriba para comenzar a construir el examen.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Modal Dinámico para Crear Pregunta -->
    <div id="pregunta-modal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-7 shadow-2xl border border-slate-200 animate-scale-in max-h-[90vh] overflow-y-auto relative">
            
            <!-- Header Modal Limpio -->
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <i id="modal-icon" class="fas fa-question-circle text-2xl text-sena-green"></i>
                    <div>
                        <h3 class="text-base font-bold text-slate-800"><span id="modal-action-title">Configurar</span> Pregunta: <span id="modal-tipo-label" class="text-sena-green font-extrabold"></span></h3>
                        <p class="text-xs text-slate-500 mt-0.5" id="modal-subtitle">Define el enunciado y las opciones de respuesta</p>
                    </div>
                </div>
                <button type="button" onclick="closePreguntaModal()" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors cursor-pointer flex-shrink-0" title="Cerrar modal">
                    <i class="fas fa-times text-sm leading-none"></i>
                </button>
            </div>

            <form id="pregunta-form" action="{{ route($routePrefix . '.examenes.preguntas.store', $quiz->id_quiz) }}" method="POST" class="mt-5 space-y-5">
                    @csrf
                    <input type="hidden" name="_method" id="form-method" value="POST">
                    <input type="hidden" name="tipo" id="input-tipo">

                    <!-- Enunciado -->
                    <div>
                        <label class="text-[11px] font-extrabold text-slate-600 uppercase tracking-wider block mb-2">Enunciado de la pregunta *</label>
                        <textarea name="pregunta" id="input-pregunta" required rows="3" placeholder="Escribe la pregunta aquí..." 
                                  class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all resize-none"></textarea>
                    </div>

                    <!-- Ponderación Automática sobre 100% -->
                    <input type="hidden" name="puntaje" id="input-puntaje" value="1">
                    <div class="p-3.5 rounded-2xl bg-emerald-50/80 border border-emerald-200/80 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center shrink-0">
                            <i class="fas fa-calculator text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <span class="block text-[11px] font-extrabold text-emerald-800 uppercase tracking-wider">Calificación Equitativa Automática (Base 100%)</span>
                            <p class="text-xs text-emerald-700 font-medium">Cada pregunta se pondera automáticamente de manera equitativa sobre el total de preguntas del examen.</p>
                        </div>
                    </div>

                    <!-- Zona Dinámica de Opciones -->
                    <div id="opciones-container" class="bg-slate-50 border border-slate-200 p-4 rounded-2xl hidden">
                        <div class="flex items-center justify-between mb-3">
                            <label class="text-[11px] font-extrabold text-slate-600 uppercase tracking-wider block">Opciones de respuesta</label>
                            <button type="button" id="btn-add-opcion" onclick="addOpcion()" class="text-xs font-bold text-sena-green hover:text-emerald-700 cursor-pointer">
                                <i class="fas fa-plus"></i> Añadir opción
                            </button>
                        </div>
                        <div id="lista-opciones" class="space-y-2">
                            <!-- Inyectado por JS -->
                        </div>
                    </div>

                    <!-- Información Adicional para Huecos o Tabla -->
                    <div id="info-compleja" class="hidden bg-amber-50 p-4 rounded-2xl border border-amber-200">
                        <p class="text-xs text-amber-700 font-medium leading-relaxed">
                            <i class="fas fa-info-circle mr-1"></i> Para este tipo de pregunta compleja, redacta las instrucciones en el enunciado y proporciona la respuesta esperada en el campo de retroalimentación de abajo.
                        </p>
                    </div>

                    <!-- Retroalimentación -->
                    <div>
                        <label class="text-[11px] font-extrabold text-slate-600 uppercase tracking-wider block mb-2 flex items-center gap-1">
                            Retroalimentación / Respuesta Correcta Sugerida
                            <span class="group relative cursor-help text-slate-400">
                                <i class="fas fa-question-circle text-[10px]"></i>
                            </span>
                        </label>
                        <textarea name="retroalimentacion" id="input-retroalimentacion" rows="3" placeholder="Mensaje que verá el aprendiz al finalizar el quiz (ej. explicación de la respuesta o rúbrica esperada para abiertas)." 
                                  class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all resize-none"></textarea>
                    </div>

                    <div id="modal-footer-buttons" class="pt-6 flex items-center justify-end gap-3 mt-4 border-t border-slate-100">
                        <button type="button" onclick="closePreguntaModal()" id="btn-cancelar-modal" class="px-5 py-2.5 rounded-xl text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors cursor-pointer">
                            Cancelar
                        </button>
                        <button type="submit" id="btn-submit-pregunta" class="px-6 py-2.5 rounded-full text-xs sm:text-sm font-black text-[#1A2B4C] bg-[#33FF57] hover:bg-[#2ae049] shadow-md shadow-emerald-500/20 transition-all flex items-center gap-2 cursor-pointer">
                            <i class="fas fa-save text-xs text-[#1A2B4C]"></i> <span id="btn-submit-label">Guardar pregunta</span>
                        </button>
                    </div>
                </form>
        </div>
    </div>



    <script>
        const modal = document.getElementById('pregunta-modal');
        const form = document.getElementById('pregunta-form');
        const formMethod = document.getElementById('form-method');
        const modalActionTitle = document.getElementById('modal-action-title');
        const modalSubtitle = document.getElementById('modal-subtitle');
        const btnSubmitLabel = document.getElementById('btn-submit-label');
        const btnSubmitPregunta = document.getElementById('btn-submit-pregunta');
        const btnCancelarModal = document.getElementById('btn-cancelar-modal');
        const inputPregunta = document.getElementById('input-pregunta');
        const inputPuntaje = document.getElementById('input-puntaje');
        const inputRetro = document.getElementById('input-retroalimentacion');
        const opcionesContainer = document.getElementById('opciones-container');
        const listaOpciones = document.getElementById('lista-opciones');
        const infoCompleja = document.getElementById('info-compleja');
        let optionCount = 0;
        let currentType = '';

        const storeUrl = "{{ route($routePrefix . '.examenes.preguntas.store', $quiz->id_quiz) }}";
        const updateUrlTemplate = "{{ route($routePrefix . '.examenes.preguntas.update', [$quiz->id_quiz, ':preguntaId']) }}";

        const TYPE_LABELS = {
            'opcion_multiple': 'Opción múltiple',
            'seleccion_multiple': 'Selección múltiple',
            'verdadero_falso': 'Verdadero o Falso',
            'espacios_blanco': 'Completa los espacios en blanco',
            'abierta': 'Respuestas abiertas',
            'tabla_relleno': 'Tabla de relleno'
        };

        function setFormReadOnly(readOnly) {
            inputPregunta.readOnly = readOnly;
            inputRetro.readOnly = readOnly;
            
            if (readOnly) {
                inputPregunta.classList.add('bg-slate-100', 'cursor-not-allowed', 'text-slate-600');
                inputRetro.classList.add('bg-slate-100', 'cursor-not-allowed', 'text-slate-600');
                if (btnSubmitPregunta) btnSubmitPregunta.classList.add('hidden');
                if (btnCancelarModal) btnCancelarModal.textContent = 'Cerrar';
            } else {
                inputPregunta.classList.remove('bg-slate-100', 'cursor-not-allowed', 'text-slate-600');
                inputRetro.classList.remove('bg-slate-100', 'cursor-not-allowed', 'text-slate-600');
                if (btnSubmitPregunta) btnSubmitPregunta.classList.remove('hidden');
                if (btnCancelarModal) btnCancelarModal.textContent = 'Cancelar';
            }

            const btnAddOpc = document.getElementById('btn-add-opcion');
            if (btnAddOpc) {
                btnAddOpc.style.display = readOnly ? 'none' : 'inline-flex';
            }

            const textInputs = listaOpciones.querySelectorAll('input[type="text"]');
            textInputs.forEach(input => {
                input.readOnly = readOnly;
                if (readOnly) input.classList.add('cursor-not-allowed', 'text-slate-600');
                else input.classList.remove('cursor-not-allowed', 'text-slate-600');
            });

            const checkAndRadio = listaOpciones.querySelectorAll('input[type="checkbox"], input[type="radio"]');
            checkAndRadio.forEach(input => {
                input.disabled = readOnly;
            });

            const deleteOptionBtns = listaOpciones.querySelectorAll('.btn-del-opcion');
            deleteOptionBtns.forEach(btn => {
                btn.style.display = readOnly ? 'none' : '';
            });
        }

        function openPreguntaModal(tipo) {
            currentType = tipo;
            form.action = storeUrl;
            formMethod.value = 'POST';
            modalActionTitle.textContent = 'Configurar';
            if (modalSubtitle) modalSubtitle.textContent = 'Define el enunciado y las opciones de respuesta';
            btnSubmitLabel.textContent = 'Guardar pregunta';

            document.getElementById('input-tipo').value = tipo;
            document.getElementById('modal-tipo-label').textContent = TYPE_LABELS[tipo] || tipo;
            
            // Reiniciar form y opciones
            inputPregunta.value = '';
            inputPuntaje.value = 1;
            inputRetro.value = '';
            listaOpciones.innerHTML = '';
            optionCount = 0;

            if (tipo === 'opcion_multiple' || tipo === 'seleccion_multiple') {
                opcionesContainer.classList.remove('hidden');
                infoCompleja.classList.add('hidden');
                addOpcion();
                addOpcion(); // Iniciar con 2 opciones vacías
            } else if (tipo === 'verdadero_falso') {
                opcionesContainer.classList.remove('hidden');
                infoCompleja.classList.add('hidden');
                // Inyectar Verdadero y Falso
                listaOpciones.innerHTML = `
                    <div class="flex items-center gap-3 bg-white p-2.5 rounded-xl border border-slate-200">
                        <input type="radio" name="opciones[0][es_correcta]" value="1" checked class="w-4 h-4 text-emerald-500 focus:ring-emerald-500">
                        <input type="text" name="opciones[0][texto]" value="Verdadero" readonly class="flex-1 bg-transparent text-sm font-bold text-slate-700 outline-none">
                    </div>
                    <div class="flex items-center gap-3 bg-white p-2.5 rounded-xl border border-slate-200">
                        <input type="radio" name="opciones[1][es_correcta]" value="1" class="w-4 h-4 text-emerald-500 focus:ring-emerald-500">
                        <input type="text" name="opciones[1][texto]" value="Falso" readonly class="flex-1 bg-transparent text-sm font-bold text-slate-700 outline-none">
                    </div>
                `;
            } else {
                opcionesContainer.classList.add('hidden');
                infoCompleja.classList.remove('hidden');
            }

            setFormReadOnly(false);
            modal.classList.remove('hidden');
        }

        function openEditarPreguntaModal(pregunta) {
            currentType = pregunta.tipo;
            form.action = updateUrlTemplate.replace(':preguntaId', pregunta.id_pregunta);
            formMethod.value = 'PUT';
            modalActionTitle.textContent = 'Editar';
            if (modalSubtitle) modalSubtitle.textContent = 'Modifica el enunciado y las opciones de respuesta';
            btnSubmitLabel.textContent = 'Actualizar pregunta';

            document.getElementById('input-tipo').value = pregunta.tipo;
            document.getElementById('modal-tipo-label').textContent = TYPE_LABELS[pregunta.tipo] || pregunta.tipo;

            inputPregunta.value = pregunta.pregunta || '';
            inputPuntaje.value = pregunta.puntaje || 1;
            inputRetro.value = pregunta.retroalimentacion || '';
            listaOpciones.innerHTML = '';
            optionCount = 0;

            if (pregunta.tipo === 'opcion_multiple' || pregunta.tipo === 'seleccion_multiple') {
                opcionesContainer.classList.remove('hidden');
                infoCompleja.classList.add('hidden');

                if (pregunta.opciones && pregunta.opciones.length > 0) {
                    pregunta.opciones.forEach((opc) => {
                        addOpcion(opc.opcion, opc.es_correcta == 1);
                    });
                } else {
                    addOpcion();
                    addOpcion();
                }
            } else if (pregunta.tipo === 'verdadero_falso') {
                opcionesContainer.classList.remove('hidden');
                infoCompleja.classList.add('hidden');

                let isVerdaderoCorrect = true;
                if (pregunta.opciones && pregunta.opciones.length > 0) {
                    const verd = pregunta.opciones.find(o => o.opcion && o.opcion.toLowerCase() === 'verdadero');
                    if (verd) {
                        isVerdaderoCorrect = (verd.es_correcta == 1);
                    }
                }

                listaOpciones.innerHTML = `
                    <div class="flex items-center gap-3 bg-white p-2.5 rounded-xl border border-slate-200">
                        <input type="radio" name="opciones[0][es_correcta]" value="1" ${isVerdaderoCorrect ? 'checked' : ''} class="w-4 h-4 text-emerald-500 focus:ring-emerald-500">
                        <input type="text" name="opciones[0][texto]" value="Verdadero" readonly class="flex-1 bg-transparent text-sm font-bold text-slate-700 outline-none">
                    </div>
                    <div class="flex items-center gap-3 bg-white p-2.5 rounded-xl border border-slate-200">
                        <input type="radio" name="opciones[1][es_correcta]" value="1" ${!isVerdaderoCorrect ? 'checked' : ''} class="w-4 h-4 text-emerald-500 focus:ring-emerald-500">
                        <input type="text" name="opciones[1][texto]" value="Falso" readonly class="flex-1 bg-transparent text-sm font-bold text-slate-700 outline-none">
                    </div>
                `;
            } else {
                opcionesContainer.classList.add('hidden');
                infoCompleja.classList.remove('hidden');
            }

            setFormReadOnly(false);
            modal.classList.remove('hidden');
            if (window.lockBodyScroll) window.lockBodyScroll(true);
            setTimeout(() => {
                preguntaOriginalSnapshot = JSON.stringify(getPreguntaSnapshot());
            }, 50);
        }

        function openVerPreguntaModal(pregunta) {
            openEditarPreguntaModal(pregunta);
            modalActionTitle.textContent = 'Consultar';
            if (modalSubtitle) modalSubtitle.textContent = 'Modo solo lectura: la evaluación ya cuenta con intentos de aprendices.';
            setFormReadOnly(true);
        }

        let preguntaOriginalSnapshot = null;

        function getPreguntaSnapshot() {
            const opciones = [];
            const textInputs = listaOpciones.querySelectorAll('input[type="text"]');
            textInputs.forEach(t => opciones.push(t.value.trim()));
            return {
                pregunta: inputPregunta.value.trim(),
                puntaje: String(inputPuntaje.value).trim(),
                retro: inputRetro.value.trim(),
                opciones: opciones
            };
        }

        form.addEventListener('submit', function(e) {
            if (preguntaOriginalSnapshot) {
                const current = JSON.stringify(getPreguntaSnapshot());
                if (current === preguntaOriginalSnapshot) {
                    e.preventDefault();
                    closePreguntaModal();
                    if (window.mostrarAlertaBanner) {
                        window.mostrarAlertaBanner('No se detectaron modificaciones en la pregunta.', 'info');
                    }
                    return false;
                }
            }
        });

        function closePreguntaModal() {
            modal.classList.add('hidden');
            if (window.lockBodyScroll) window.lockBodyScroll(false);
        }

        // Cerrar modal al hacer clic en el backdrop oscuro
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closePreguntaModal();
            }
        });

        function addOpcion(texto = '', esCorrecta = false) {
            const isMultiple = currentType === 'seleccion_multiple';
            const index = optionCount;
            const isChecked = esCorrecta ? 'checked' : '';
            const isVal = esCorrecta ? '1' : '0';
            const safeText = texto ? String(texto).replace(/"/g, '&quot;') : '';

            const html = `
                <div class="flex items-center gap-3 bg-white p-2.5 rounded-xl border border-slate-200 group" id="opcion-row-${index}">
                    ${isMultiple ? 
                        `<input type="checkbox" name="opciones[${index}][es_correcta]" value="1" ${isChecked} class="w-4 h-4 text-sena-green rounded focus:ring-sena-green cursor-pointer">` : 
                        `<input type="radio" name="opciones_correcta_radio" onchange="syncRadio(${index})" ${isChecked} class="w-4 h-4 text-sena-green focus:ring-sena-green cursor-pointer">
                         <input type="hidden" name="opciones[${index}][es_correcta]" id="radio-sync-${index}" value="${isVal}">`
                    }
                    <input type="text" name="opciones[${index}][texto]" value="${safeText}" required placeholder="Texto de la opción..." class="flex-1 bg-transparent text-sm font-medium text-slate-700 outline-none">
                    <button type="button" onclick="document.getElementById('opcion-row-${index}').remove()" class="btn-del-opcion text-slate-300 hover:text-rose-500 opacity-0 group-hover:opacity-100 transition-all px-2 cursor-pointer" title="Eliminar opción">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            listaOpciones.insertAdjacentHTML('beforeend', html);
            optionCount++;
        }

        function syncRadio(selectedIndex) {
            // Reset all hidden inputs
            for(let i=0; i<optionCount; i++) {
                const hidden = document.getElementById(`radio-sync-${i}`);
                if(hidden) hidden.value = "0";
            }
            // Set selected
            const selected = document.getElementById(`radio-sync-${selectedIndex}`);
            if(selected) selected.value = "1";
        }

        async function ejecutarEliminarPreguntaAjax(form) {
            const token = form.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}';
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
                });
                const data = await response.json();
                if (data.success) {
                    const card = form.closest('.tarjeta-pregunta-item') || form.closest('.p-5');
                    if (card) {
                        card.style.transition = 'all 0.3s ease';
                        card.style.opacity = '0';
                        card.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            card.remove();
                            document.querySelectorAll('.tarjeta-pregunta-item').forEach((item, idx) => {
                                const badgeIndex = item.querySelector('.shrink-0');
                                if (badgeIndex) badgeIndex.textContent = idx + 1;
                            });
                        }, 300);
                    }
                    if (window.mostrarAlertaBanner) {
                        window.mostrarAlertaBanner(data.message, 'success');
                    }
                } else {
                    if (window.mostrarAlertaBanner) {
                        window.mostrarAlertaBanner(data.message || 'Error al eliminar la pregunta.', 'danger');
                    }
                }
            } catch (err) {
                console.error(err);
                if (window.mostrarAlertaBanner) {
                    window.mostrarAlertaBanner('Error de conexión al eliminar la pregunta.', 'danger');
                }
            } finally {
                window.cerrarModalConfirmacion();
            }
        }

        // Modal universal reutilizable para confirmación de eliminación
        function confirmarEliminarPregunta(event, form, tituloPregunta) {
            if (event) event.preventDefault();
            window.abrirModalConfirmacion({
                tipo: 'danger',
                titulo: '¿Eliminar pregunta?',
                descripcion: 'Esta acción no se puede deshacer y retirará la pregunta permanentemente del examen.',
                item: tituloPregunta,
                itemTag: 'Pregunta a eliminar',
                advertencia: 'Se borrarán también sus opciones de respuesta.',
                btnTexto: 'Sí, eliminar',
                btnIcon: 'fa-trash-alt',
                onConfirm: () => ejecutarEliminarPreguntaAjax(form)
            });
            return false;
        }

        // Cerrar con Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closePreguntaModal();
            }
        });
    </script>
</x-sisig::layouts.admin>
