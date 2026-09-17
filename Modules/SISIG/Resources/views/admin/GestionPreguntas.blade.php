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
            <div class="text-right">
                <span class="block text-[11px] font-extrabold text-slate-400 uppercase tracking-wide">Total Preguntas</span>
                <span class="text-2xl font-black text-sena-green">{{ $quiz->preguntas->count() }}</span>
            </div>
        </div>

        <!-- Selector de Tipo de Pregunta (Diseño Wayground) -->
        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">
            <h3 class="text-base font-black text-slate-700 mb-6 flex items-center gap-2">
                <i class="fas fa-magic text-amber-500"></i> Agregar nueva pregunta
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Opción Múltiple -->
                <button onclick="openPreguntaModal('opcion_multiple')" class="group flex items-center gap-4 p-4 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-200 transition-all text-left">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                        <i class="fas fa-check-square text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 group-hover:text-emerald-600 transition-colors">Opción múltiple</h4>
                        <p class="text-[10px] text-slate-500 mt-0.5 font-medium leading-tight">Una sola respuesta correcta.</p>
                    </div>
                </button>

                <!-- Selección Múltiple -->
                <button onclick="openPreguntaModal('seleccion_multiple')" class="group flex items-center gap-4 p-4 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-200 transition-all text-left">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                        <i class="fas fa-check-double text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 group-hover:text-emerald-600 transition-colors">Selección múltiple</h4>
                        <p class="text-[10px] text-slate-500 mt-0.5 font-medium leading-tight">Varias respuestas correctas.</p>
                    </div>
                </button>

                <!-- Verdadero o Falso -->
                <button onclick="openPreguntaModal('verdadero_falso')" class="group flex items-center gap-4 p-4 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-200 transition-all text-left">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform relative">
                        <i class="fas fa-check text-lg absolute -top-0.5 -left-0.5 text-emerald-600"></i>
                        <i class="fas fa-times text-lg absolute -bottom-0.5 -right-0.5 text-rose-500"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 group-hover:text-emerald-600 transition-colors">Verdadero o falso</h4>
                        <p class="text-[10px] text-slate-500 mt-0.5 font-medium leading-tight">Evalúa una afirmación.</p>
                    </div>
                </button>

                <!-- Espacios en blanco -->
                <button onclick="openPreguntaModal('espacios_blanco')" class="group flex items-center gap-4 p-4 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-200 transition-all text-left">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                        <i class="fas fa-pen-nib text-xl border-b-2 border-dashed border-emerald-500 pb-1"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 group-hover:text-emerald-600 transition-colors">Completa los espacios en blanco</h4>
                    </div>
                </button>

                <!-- Respuestas Abiertas -->
                <button onclick="openPreguntaModal('abierta')" class="group flex items-center gap-4 p-4 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-200 transition-all text-left">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                        <i class="fas fa-align-left text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 group-hover:text-emerald-600 transition-colors">Respuestas abiertas</h4>
                        <p class="text-[10px] text-slate-500 mt-0.5 font-medium leading-tight">Texto libre y descriptivo.</p>
                    </div>
                </button>

                <!-- Tabla de relleno -->
                <button onclick="openPreguntaModal('tabla_relleno')" class="group flex items-center gap-4 p-4 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-200 transition-all text-left relative overflow-hidden">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                        <i class="fas fa-table text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 group-hover:text-emerald-600 transition-colors flex items-center gap-2">
                            Tabla de relleno
                            <span class="text-[9px] font-black bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded uppercase tracking-widest border border-emerald-200">Nuevo</span>
                        </h4>
                        <p class="text-[10px] text-slate-500 mt-0.5 font-medium leading-tight">Completar celdas.</p>
                    </div>
                </button>
            </div>
        </div>

        <!-- Lista de Preguntas Creadas -->
        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">
            <h3 class="text-base font-black text-slate-700 mb-6">
                Preguntas Configuradas
            </h3>
            
            <div class="space-y-4">
                @forelse($quiz->preguntas as $index => $pregunta)
                    <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50 hover:bg-white transition-colors group flex gap-4">
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
                                    <span class="text-xs font-bold text-amber-500 bg-amber-50 px-2 py-1 rounded-lg">{{ $pregunta->puntaje }} pts</span>
                                    <form action="{{ route($routePrefix . '.examenes.preguntas.destroy', [$quiz->id_quiz, $pregunta->id_pregunta]) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta pregunta?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-rose-500 hover:border-rose-200 hover:bg-rose-50 transition-all flex items-center justify-center">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </form>
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
        <div class="bg-white rounded-3xl max-w-2xl w-full shadow-2xl border border-slate-200 animate-scale-in flex flex-col max-h-[90vh]">
            
            <div class="bg-slate-900 text-white rounded-t-3xl p-5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-sena-green">
                        <i class="fas fa-question-circle text-sm"></i>
                    </div>
                    <h3 class="font-bold text-base tracking-wide">Configurar Pregunta: <span id="modal-tipo-label" class="text-emerald-400"></span></h3>
                </div>
                <button type="button" onclick="closePreguntaModal()" class="text-white/60 hover:text-white transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <div class="p-6 overflow-y-auto">
                <form id="pregunta-form" action="{{ route($routePrefix . '.examenes.preguntas.store', $quiz->id_quiz) }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="tipo" id="input-tipo">

                    <!-- Enunciado -->
                    <div>
                        <label class="text-[11px] font-extrabold text-slate-600 uppercase tracking-wider block mb-2">Enunciado de la pregunta *</label>
                        <textarea name="pregunta" required rows="3" placeholder="Escribe la pregunta aquí..." 
                                  class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all resize-none"></textarea>
                    </div>

                    <!-- Puntaje -->
                    <div class="w-1/3">
                        <label class="text-[11px] font-extrabold text-slate-600 uppercase tracking-wider block mb-2">Puntaje *</label>
                        <input type="number" name="puntaje" required step="0.1" min="0" value="1" 
                               class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all">
                    </div>

                    <!-- Zona Dinámica de Opciones -->
                    <div id="opciones-container" class="bg-slate-50 border border-slate-200 p-4 rounded-2xl hidden">
                        <div class="flex items-center justify-between mb-3">
                            <label class="text-[11px] font-extrabold text-slate-600 uppercase tracking-wider block">Opciones de respuesta</label>
                            <button type="button" onclick="addOpcion()" class="text-xs font-bold text-sena-green hover:text-emerald-700">
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
                        <textarea name="retroalimentacion" rows="3" placeholder="Mensaje que verá el aprendiz al finalizar el quiz (ej. explicación de la respuesta o rúbrica esperada para abiertas)." 
                                  class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all resize-none"></textarea>
                    </div>

                    <div class="pt-6 flex items-center justify-end gap-3 mt-4 border-t border-slate-100">
                        <button type="button" onclick="closePreguntaModal()" class="px-5 py-2.5 rounded-xl text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-sena-green hover:bg-emerald-600 shadow-sena transition-all flex items-center gap-2">
                            <i class="fas fa-save"></i> <span>Guardar pregunta</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('pregunta-modal');
        const form = document.getElementById('pregunta-form');
        const opcionesContainer = document.getElementById('opciones-container');
        const listaOpciones = document.getElementById('lista-opciones');
        const infoCompleja = document.getElementById('info-compleja');
        let optionCount = 0;
        let currentType = '';

        const TYPE_LABELS = {
            'opcion_multiple': 'Opción múltiple',
            'seleccion_multiple': 'Selección múltiple',
            'verdadero_falso': 'Verdadero o Falso',
            'espacios_blanco': 'Completa los espacios en blanco',
            'abierta': 'Respuestas abiertas',
            'tabla_relleno': 'Tabla de relleno'
        };

        function openPreguntaModal(tipo) {
            currentType = tipo;
            document.getElementById('input-tipo').value = tipo;
            document.getElementById('modal-tipo-label').textContent = TYPE_LABELS[tipo];
            
            // Reiniciar form y opciones
            form.reset();
            listaOpciones.innerHTML = '';
            optionCount = 0;

            if (tipo === 'opcion_multiple' || tipo === 'seleccion_multiple') {
                opcionesContainer.classList.remove('hidden');
                infoCompleja.classList.add('hidden');
                addOpcion();
                addOpcion(); // Iniciar con 2 opciones
            } else if (tipo === 'verdadero_falso') {
                opcionesContainer.classList.remove('hidden');
                infoCompleja.classList.add('hidden');
                // Inyectar Verdadero y Falso
                listaOpciones.innerHTML = `
                    <div class="flex items-center gap-3 bg-white p-2.5 rounded-xl border border-slate-200">
                        <input type="radio" name="opciones[0][es_correcta]" value="1" class="w-4 h-4 text-emerald-500 focus:ring-emerald-500">
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

            modal.classList.remove('hidden');
        }

        function closePreguntaModal() {
            modal.classList.add('hidden');
        }

        function addOpcion() {
            const isMultiple = currentType === 'seleccion_multiple';
            const inputType = isMultiple ? 'checkbox' : 'radio';
            const inputNameCorrect = isMultiple ? `opciones[${optionCount}][es_correcta]` : `opciones_correcta_radio`; // radio groups need same name

            const html = `
                <div class="flex items-center gap-3 bg-white p-2.5 rounded-xl border border-slate-200 group" id="opcion-row-${optionCount}">
                    ${isMultiple ? 
                        `<input type="checkbox" name="opciones[${optionCount}][es_correcta]" value="1" class="w-4 h-4 text-sena-green rounded focus:ring-sena-green">` : 
                        `<input type="radio" name="opciones_correcta_radio" onchange="syncRadio(${optionCount})" class="w-4 h-4 text-sena-green focus:ring-sena-green">
                         <input type="hidden" name="opciones[${optionCount}][es_correcta]" id="radio-sync-${optionCount}" value="0">`
                    }
                    <input type="text" name="opciones[${optionCount}][texto]" required placeholder="Texto de la opción..." class="flex-1 bg-transparent text-sm font-medium text-slate-700 outline-none">
                    <button type="button" onclick="document.getElementById('opcion-row-${optionCount}').remove()" class="text-slate-300 hover:text-rose-500 opacity-0 group-hover:opacity-100 transition-all px-2">
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
    </script>
</x-sisig::layouts.admin>
