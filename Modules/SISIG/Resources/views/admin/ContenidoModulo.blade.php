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

@php
    $routePrefix = $routePrefix ?? (request()->is('*editor*') ? 'sisig.editor' : 'sisig.admin');
    $isEditor = $routePrefix === 'sisig.editor';
    $tabRouteName = $isEditor ? 'sisig.editor.contenido.edit' : 'sisig.admin.modulos.contenido';
    $updateRouteName = $isEditor ? 'sisig.editor.contenido.update' : 'sisig.admin.modulos.contenido.update';
@endphp

    <div class="space-y-6 w-full max-w-[1600px] mx-auto pb-10">
        
        <!-- 1. Header estilo banner institucional -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#001A29] via-[#002F48] to-[#004266] border border-slate-700/60 p-7 sm:p-9 text-white shadow-2xl">
            <div class="absolute -top-24 -right-24 w-80 h-80 bg-sena-green/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-sky-500/15 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="space-y-2">
                    <span class="text-sena-green text-xs font-black uppercase tracking-widest flex items-center gap-2">
                        <i class="fas fa-file-alt"></i>
                        <span>{{ $isEditor ? 'Panel Editorial SISIG' : 'Administración SIG' }}</span>
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight flex items-center gap-3">
                        <span>Edición de Contenido: <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 to-teal-200">{{ $moduloSeleccionado->titulo }}</span></span>
                    </h1>
                    <p class="text-slate-300 text-xs sm:text-sm max-w-2xl font-normal leading-relaxed">
                        Redacta y formatea el material didáctico, guías y contenido interactivo de esta sección usando el editor enriquecido.
                    </p>
                </div>
            </div>
        </div>

        <!-- 2. Alerta de Permisos -->
        <div class="bg-emerald-50 border border-emerald-200 px-5 py-3.5 rounded-2xl text-xs sm:text-sm flex items-center gap-3 shadow-sm text-emerald-800">
            <div class="w-6 h-6 rounded-full bg-emerald-500 flex items-center justify-center text-white flex-shrink-0">
                <i class="fas fa-info text-xs"></i>
            </div>
            <p>
                <span class="font-bold">Editor interactivo activo:</span> Puedes añadir texto con formato, enlaces, listas, imágenes y videos. Al guardar, el material estará disponible inmediatamente para los aprendices.
            </p>
        </div>

        <!-- 3. Título de Secciones -->
        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-4 flex items-center gap-2">
            <i class="fas fa-layer-group text-slate-400"></i> SELECCIONA LA SECCIÓN A EDITAR
        </h3>

        <!-- 4. Pestañas de Módulos (Tabs Dinámicos) -->
        <div class="flex flex-wrap gap-2.5 pb-2">
            @foreach($modulos as $mod)
                <a href="{{ route($tabRouteName, $mod->id) }}" 
                   class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs sm:text-sm font-bold border transition-all 
                   {{ $moduloSeleccionado->id == $mod->id ? 'bg-emerald-50 text-sena-green border-emerald-200 shadow-sm' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
                   
                   @if($mod->estado == 'publicado')
                        <i class="fas fa-check-circle {{ $moduloSeleccionado->id == $mod->id ? 'text-sena-green' : 'text-emerald-500' }}"></i>
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
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <!-- Header del Editor -->
            <form action="{{ route($updateRouteName, $moduloSeleccionado->id) }}" method="POST" id="editorForm">
                @csrf
                @method('PUT')
                
                <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50">
                    <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-edit text-sena-green"></i>
                        <span>Redacción de Contenido: <strong class="text-slate-900">{{ $moduloSeleccionado->titulo }}</strong></span>
                    </h2>
                    
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-white bg-sena-green hover:bg-emerald-600 shadow-sena hover:shadow-lg transition-all transform hover:-translate-y-0.5 cursor-pointer">
                        <i class="fas fa-save text-xs"></i> <span>Guardar Cambios</span>
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
