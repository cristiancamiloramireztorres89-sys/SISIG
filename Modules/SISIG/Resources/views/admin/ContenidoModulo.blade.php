<x-sisig::layouts.admin title="Gestión de Contenido">
    <!-- Quill.js CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .ql-toolbar.ql-snow {
            border: 1px solid #e2e8f0;
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
            background-color: #f8fafc;
            padding: 0.75rem 1rem;
        }
        .ql-container.ql-snow {
            border: 1px solid #e2e8f0;
            border-bottom-left-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
            min-height: 400px;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            color: #334155;
        }
        .ql-editor {
            padding: 1.5rem;
        }
    </style>

    <div class="space-y-6 w-full max-w-[1600px] mx-auto pb-10">
        
        <!-- 1. Header estilo banner verde oscuro -->
        <div class="bg-[#1a803e] rounded-2xl p-6 sm:p-8 text-white shadow-md relative overflow-hidden">
            <div class="relative z-10">
                <h1 class="text-2xl font-extrabold flex items-center gap-2 mb-2">
                    <i class="fas fa-file-alt text-white/80"></i> Gestión de Contenido
                </h1>
                <p class="text-white/80 text-sm font-medium mb-5">
                    Edita el contenido de cada sección: texto, imágenes y videos en el orden que prefieras.
                </p>
                <div class="text-xs font-medium text-white/70 flex items-center gap-2">
                    <span>Inicio</span>
                    <i class="fas fa-chevron-right text-[10px] text-white/40"></i>
                    <span>Contenido</span>
                    <i class="fas fa-chevron-right text-[10px] text-white/40"></i>
                    <span class="text-white font-bold">Gestión de Contenido</span>
                </div>
            </div>
            <!-- Brillos decorativos -->
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-xl text-sm flex items-center gap-3">
                <i class="fas fa-check-circle text-emerald-500"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <!-- 2. Alerta de Permisos -->
        <div class="bg-emerald-50 border border-emerald-200 px-6 py-4 rounded-xl text-sm flex items-center gap-3 shadow-sm">
            <div class="w-6 h-6 rounded-full bg-emerald-500 flex items-center justify-center text-white flex-shrink-0">
                <i class="fas fa-exclamation text-xs"></i>
            </div>
            <p class="text-emerald-700">
                <span class="font-bold">Permisos de edición activos:</span> Solo puedes editar las secciones que el administrador te ha habilitado. Las secciones bloqueadas muestran <i class="fas fa-lock mx-1 text-slate-400"></i> y no permiten modificaciones.
            </p>
        </div>

        <!-- 3. Título de Secciones -->
        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-4 flex items-center gap-2">
            <i class="fas fa-layer-group text-slate-400"></i> SELECCIONA LA SECCIÓN A EDITAR
        </h3>

        <!-- 4. Pestañas de Módulos (Tabs Dinámicos) -->
        <div class="flex flex-wrap gap-3 border-b border-transparent pb-2">
            @foreach($modulos as $mod)
                <a href="{{ route('sisig.admin.modulos.contenido', $mod->id) }}" 
                   class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold border transition-all 
                   {{ $moduloSeleccionado->id == $mod->id ? 'bg-[#e6f4ea] text-[#1a803e] border-[#a8dab5] shadow-sm' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
                   
                   @if($mod->estado == 'publicado')
                        <i class="fas fa-check-circle {{ $moduloSeleccionado->id == $mod->id ? 'text-[#1a803e]' : 'text-emerald-500' }}"></i>
                   @elseif($mod->estado == 'inactivo')
                        <i class="fas fa-lock text-slate-400"></i>
                   @else
                        <i class="fas fa-pencil-alt text-amber-500"></i>
                   @endif
                   
                   <span>{{ $mod->titulo }}</span>
                </a>
            @endforeach
        </div>

        <!-- 5. Editor de Contenido -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
            <!-- Header del Editor -->
            <form action="{{ route('sisig.admin.modulos.contenido.update', $moduloSeleccionado->id) }}" method="POST" id="editorForm">
                @csrf
                @method('PUT')
                
                <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-file-alt text-sena-green"></i>
                        Editor de contenido — <span class="text-slate-900">{{ $moduloSeleccionado->titulo }}</span>
                    </h2>
                    
                    <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-[#0f9d58] hover:bg-[#0b8043] transition-colors flex items-center justify-center gap-2 shadow-sm border border-transparent">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                </div>

                <!-- Contenedor Quill -->
                <div class="p-6">
                    <!-- Campo oculto para enviar el HTML -->
                    <input type="hidden" name="contenido_html" id="contenido_html" value="{{ $moduloSeleccionado->contenido_html }}">
                    
                    <!-- Editor Div -->
                    <div id="editor">
                        {!! $moduloSeleccionado->contenido_html !!}
                    </div>
                </div>

                <!-- Footer del Editor -->
                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between text-xs font-medium text-slate-500 bg-slate-50/50 rounded-b-2xl">
                    <span id="charCount">0 caracteres</span>
                    <span class="flex items-center gap-1.5 text-emerald-600">
                        <i class="fas fa-check-circle"></i> Último guardado: {{ $moduloSeleccionado->updated_at->diffForHumans() }}
                    </span>
                </div>
            </form>
        </div>

    </div>

    <!-- Quill.js JS -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toolbarOptions = [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['link', 'image', 'video'],
                ['clean']                                         // remove formatting button
            ];

            var quill = new Quill('#editor', {
                modules: {
                    toolbar: toolbarOptions
                },
                theme: 'snow',
                placeholder: 'Escribe aquí el contenido del módulo...'
            });

            // Actualizar el input oculto y el contador de caracteres cuando el contenido cambia
            var hiddenInput = document.getElementById('contenido_html');
            var charCount = document.getElementById('charCount');

            // Set initial char count
            charCount.textContent = quill.getText().trim().length + ' caracteres';

            quill.on('text-change', function() {
                hiddenInput.value = quill.root.innerHTML;
                charCount.textContent = quill.getText().trim().length + ' caracteres';
            });

            // Antes de enviar el form, asegurar que el HTML esté en el input
            document.getElementById('editorForm').onsubmit = function() {
                hiddenInput.value = quill.root.innerHTML;
            };
        });
    </script>
</x-sisig::layouts.admin>
