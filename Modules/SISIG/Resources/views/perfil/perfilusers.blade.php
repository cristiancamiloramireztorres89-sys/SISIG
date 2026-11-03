<x-sisig::layouts.admin title="Mi Perfil">
    <div class="space-y-6">

        <!-- Encabezado con Estilo Glassmorphism Dark Mode -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-5 border-b border-slate-200">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                    <i class="fas fa-id-badge text-sena-green"></i>
                    <span>Mi Perfil de Usuario</span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                    Información institucional inmutable y personalización de foto de perfil.
                </p>
            </div>
            <div>
                @php
                    $dashboardUrl = route('sisig.aprendiz.dashboard');
                    if (auth()->check()) {
                        if (auth()->user()->hasRole('admin_sisig')) {
                            $dashboardUrl = route('sisig.admin.dashboard');
                        } elseif (auth()->user()->hasRole('editor_sisig')) {
                            $dashboardUrl = route('sisig.editor.dashboard');
                        }
                    }
                @endphp
                <a href="{{ $dashboardUrl }}" 
                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold text-slate-600 hover:text-slate-900 bg-white border border-slate-300 hover:border-slate-400 shadow-sm transition-all hover:scale-105">
                    <i class="fas fa-arrow-left"></i>
                    <span>Volver al Dashboard</span>
                </a>
            </div>
        </div>

        <!-- Alertas de Errores de Validación Backend -->
        @if(isset($errors) && $errors->any())
            <div id="error-alert" class="max-w-xl mx-auto p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 flex items-start justify-between gap-3 shadow-sm transition-all duration-500 animate-shake">
                <div class="flex items-start gap-3 flex-1">
                    <i class="fas fa-exclamation-triangle text-rose-600 text-base mt-0.5 flex-shrink-0"></i>
                    <div class="flex-1">
                        <p class="text-xs sm:text-sm font-bold">Por favor corrige los siguientes inconvenientes:</p>
                        <ul class="mt-1 list-disc list-inside text-xs space-y-0.5 text-rose-700">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" onclick="closeAlert('error-alert')" class="text-rose-500 hover:text-rose-800 p-1.5 rounded-lg hover:bg-rose-100 transition-colors flex-shrink-0" title="Cerrar">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        @endif

        <!-- ========================================================================= -->
        <!-- TARJETA ÚNICA Y CENTRADA: IDENTIDAD INMUTABLE Y CAMBIO DE FOTO            -->
        <!-- ========================================================================= -->
        <div class="max-w-xl mx-auto py-2">
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xl overflow-hidden transition-all">
                @php
                    $initials = 'U';
                    if ($person && $person->first_name) {
                        $initials = strtoupper(substr($person->first_name, 0, 1) . substr($person->first_last_name ?? '', 0, 1));
                    } elseif ($user->name) {
                        $initials = strtoupper(substr($user->name, 0, 2));
                    }

                    $badgeRol = 'Aprendiz SISIG';
                    if ($roles->isNotEmpty()) {
                        $badgeRol = $roles->pluck('name')->join(', ');
                    } elseif ($user->hasRole('admin_sisig')) {
                        $badgeRol = 'Administrador SISIG';
                    } elseif ($user->hasRole('editor_sisig')) {
                        $badgeRol = 'Editor SISIG';
                    }
                @endphp

                <!-- 1. Cabecera de la Tarjeta (Glassmorphism Dark Navy #1A2B4C) -->
                <div class="relative p-6 sm:p-8 text-white text-center flex flex-col items-center overflow-hidden" 
                     style="background: linear-gradient(135deg, rgba(26, 43, 76, 0.98) 0%, rgba(20, 34, 60, 0.96) 100%); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border-bottom: 2px solid #33FF57;">
                    
                    <!-- Glows decorativos de fondo -->
                    <div class="absolute -top-12 -right-12 w-36 h-36 rounded-full bg-[#33FF57]/15 blur-2xl pointer-events-none"></div>
                    <div class="absolute -bottom-12 -left-12 w-36 h-36 rounded-full bg-[#2E6F40]/25 blur-2xl pointer-events-none"></div>

                    <!-- Avatar Interactivo con Botón de Carga (RS-0002) -->
                    <div class="relative mx-auto w-28 h-28 group z-10">
                        <div id="avatar-container" class="w-28 h-28 rounded-full bg-slate-900 text-white flex items-center justify-center shadow-2xl border-4 border-white/20 overflow-hidden relative cursor-pointer" onclick="document.getElementById('avatar_file').click();" title="Haz clic para cambiar tu foto">
                            @if($person && $person->avatar)
                                <img id="avatar-img" 
                                     src="{{ asset('storage/' . $person->avatar) }}" 
                                     alt="{{ $user->name }}" 
                                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                     onerror="this.classList.add('hidden'); document.getElementById('avatar-initials').classList.remove('hidden');">
                                <span id="avatar-initials" class="hidden text-3xl font-black text-white tracking-widest">{{ $initials }}</span>
                            @else
                                <span id="avatar-initials" class="text-3xl font-black text-white tracking-widest">{{ $initials }}</span>
                                <img id="avatar-img" src="" alt="" class="w-full h-full object-cover hidden" onerror="this.classList.add('hidden'); document.getElementById('avatar-initials').classList.remove('hidden');">
                            @endif
                            
                            <!-- Overlay de hover con icono de cámara -->
                            <div class="absolute inset-0 bg-black/45 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white">
                                <i class="fas fa-camera text-xl mb-1"></i>
                                <span class="text-[10px] font-bold tracking-tight">Cambiar foto</span>
                            </div>
                        </div>

                        <!-- Botón Flotante con Input File (accept="image/png, image/jpeg") -->
                        <button type="button" 
                                onclick="document.getElementById('avatar_file').click();" 
                                title="Subir nueva foto de perfil (JPG o PNG)" 
                                class="absolute bottom-1 right-1 w-8 h-8 rounded-full bg-[#33FF57] hover:bg-[#2ae049] text-[#1A2B4C] flex items-center justify-center shadow-lg cursor-pointer transition-all hover:scale-110 border-2 border-white focus:outline-none z-20">
                            <i class="fas fa-camera text-xs"></i>
                        </button>
                    </div>

                    <!-- Enlace para retirar foto si existe -->
                    @if($person && $person->avatar)
                        <button type="button" id="btn-remove-avatar" onclick="submitRemoveAvatar()" class="mt-2.5 text-[11px] text-rose-300 hover:text-rose-100 font-semibold inline-flex items-center gap-1 transition-colors cursor-pointer z-10">
                            <i class="fas fa-trash-alt text-[10px]"></i>
                            <span>Quitar foto actual</span>
                        </button>
                    @endif

                    <!-- Nombre Completo del Usuario (Inmutable) -->
                    <h2 class="text-xl font-black text-white mt-3 leading-snug tracking-tight z-10" style="color: #ffffff !important;">
                        {{ $person ? trim($person->first_name . ' ' . $person->first_last_name . ' ' . ($person->second_last_name ?? '')) : $user->name }}
                    </h2>

                    <!-- Insignia del Rol -->
                    <div class="mt-2 z-10">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-[#33FF57]/20 text-[#33FF57] border border-[#33FF57]/40 shadow-xs">
                            <i class="fas fa-shield-alt text-xs"></i>
                            <span>{{ $badgeRol }}</span>
                        </span>
                    </div>
                </div>

                <!-- 2. Cuerpo de la Tarjeta: Datos Inmutables y Gestión de Foto -->
                <form id="profile-form" action="{{ route('sisig.perfil.update') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Inputs ocultos para gestión de avatar -->
                    <input type="hidden" id="remove_avatar" name="remove_avatar" value="0">
                    <input type="file" id="avatar_file" name="avatar" accept="image/png, image/jpeg" class="hidden" onchange="previewAvatar(this)">

                    <!-- Sección 1: Información Institucional (Solo Lectura con candados) -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between pb-1.5 border-b border-slate-100">
                            <span class="text-xs font-black uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                                <i class="fas fa-university text-slate-400"></i> Información Institucional
                            </span>
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">
                                <i class="fas fa-lock text-[9px] text-slate-400"></i> Solo lectura
                            </span>
                        </div>

                        <div class="bg-slate-50/80 rounded-2xl p-4 border border-slate-100 space-y-3 text-xs">
                            <!-- Documento de Identidad -->
                            <div class="flex items-center justify-between gap-3 pb-2.5 border-b border-slate-200/60">
                                <span class="text-slate-500 font-semibold flex items-center gap-1.5 flex-shrink-0">
                                    <i class="fas fa-id-card text-slate-400"></i> Documento:
                                </span>
                                <span class="font-bold text-slate-800 text-right font-mono flex items-center gap-1">
                                    {{ ($person->document_type ?? 'CC') . ': ' . ($person->document_number ?? 'N/D') }}
                                    <i class="fas fa-lock text-[10px] text-slate-400" title="Dato institucional inmutable"></i>
                                </span>
                            </div>

                            <!-- Correo Institucional -->
                            <div class="flex items-center justify-between gap-3 {{ ($esAprendiz || !empty($ficha)) ? 'pb-2.5 border-b border-slate-200/60' : '' }}">
                                <span class="text-slate-500 font-semibold flex items-center gap-1.5 flex-shrink-0">
                                    <i class="fas fa-envelope text-slate-400"></i> Correo Institucional:
                                </span>
                                <span class="font-bold text-slate-800 text-right truncate max-w-[240px] flex items-center gap-1" title="{{ $user->email }}">
                                    <span class="truncate">{{ $user->email }}</span>
                                    <i class="fas fa-lock text-[10px] text-slate-400 flex-shrink-0" title="Dato institucional inmutable"></i>
                                </span>
                            </div>

                            <!-- Datos Académicos (Si es Aprendiz) -->
                            @if($esAprendiz || !empty($ficha))
                                <!-- Ficha de Formación -->
                                <div class="flex items-center justify-between gap-3 pb-2.5 border-b border-slate-200/60">
                                    <span class="text-slate-600 font-bold flex items-center gap-1.5 flex-shrink-0">
                                        <i class="fas fa-hashtag text-sena-green"></i> Ficha:
                                    </span>
                                    <span class="font-black text-slate-900 font-mono bg-white px-2 py-0.5 rounded-md border border-slate-200 flex items-center gap-1">
                                        {{ $ficha ?? '3288036' }}
                                        <i class="fas fa-lock text-[10px] text-slate-400" title="Ficha asignada por el SENA"></i>
                                    </span>
                                </div>

                                <!-- Programa / Tecnólogo -->
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-slate-600 font-bold flex items-center gap-1.5 flex-shrink-0">
                                        <i class="fas fa-graduation-cap text-[#2E6F40]"></i> Programa:
                                    </span>
                                    <span class="font-black text-[#2E6F40] uppercase truncate max-w-[240px] flex items-center gap-1" title="{{ $programa ?? 'ADSO' }}">
                                        <span class="truncate">{{ $programa ?? 'ADSO' }}</span>
                                        <i class="fas fa-lock text-[10px] text-slate-400 flex-shrink-0" title="Programa institucional"></i>
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Sección 2: Datos de Contacto (Inmutables / Solo Lectura) -->
                    <div class="space-y-3 pt-1">
                        <div class="flex items-center justify-between pb-1.5 border-b border-slate-100">
                            <span class="text-xs font-black uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                                <i class="fas fa-address-book text-slate-400"></i> Datos de Contacto
                            </span>
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">
                                <i class="fas fa-lock text-[9px] text-slate-400"></i> Solo lectura
                            </span>
                        </div>

                        <div class="bg-slate-50/80 rounded-2xl p-4 border border-slate-100 space-y-3 text-xs">
                            <!-- Correo Personal (Inmutable) -->
                            <div class="flex items-center justify-between gap-3 pb-2.5 border-b border-slate-200/60">
                                <span class="text-slate-500 font-semibold flex items-center gap-1.5 flex-shrink-0">
                                    <i class="fas fa-at text-slate-400"></i> Correo Personal:
                                </span>
                                <span class="font-bold text-slate-800 text-right truncate max-w-[240px] flex items-center gap-1" title="{{ $personalEmail ?: 'No registrado' }}">
                                    <span class="truncate font-mono">{{ $personalEmail ?: 'No registrado' }}</span>
                                    <i class="fas fa-lock text-[10px] text-slate-400 flex-shrink-0" title="Dato de solo lectura"></i>
                                </span>
                            </div>

                            <!-- Teléfono de Contacto (Inmutable) -->
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-slate-500 font-semibold flex items-center gap-1.5 flex-shrink-0">
                                    <i class="fas fa-phone-alt text-slate-400"></i> Teléfono:
                                </span>
                                <span class="font-bold text-slate-800 text-right font-mono flex items-center gap-1">
                                    <span>{{ $phone ?: 'No registrado' }}</span>
                                    <i class="fas fa-lock text-[10px] text-slate-400 flex-shrink-0" title="Dato de solo lectura"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Indicador visual cuando se selecciona una nueva foto de perfil -->
                    <div id="photo-selected-card" class="hidden p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between gap-3 animate-fadeIn">
                        <div class="flex items-center gap-2.5 truncate">
                            <i class="fas fa-check-circle text-emerald-600 text-base flex-shrink-0"></i>
                            <div class="truncate">
                                <p class="font-bold text-slate-800">Nueva foto seleccionada</p>
                                <p id="photo-selected-name" class="text-[11px] text-emerald-700 truncate font-mono"></p>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold text-emerald-700 bg-white px-2 py-0.5 rounded-md border border-emerald-200 flex-shrink-0">
                            Listo para guardar
                        </span>
                    </div>

                    <!-- Botón de Acción Principal: Cambiar Foto o Guardar Nueva Foto -->
                    <div class="pt-2">
                        <!-- Botón por defecto: Seleccionar Foto -->
                        <button type="button" 
                                id="btn-seleccionar-foto"
                                onclick="document.getElementById('avatar_file').click();" 
                                class="w-full py-3.5 rounded-2xl bg-[#2E6F40] hover:bg-[#235833] text-white font-black text-xs sm:text-sm tracking-wide shadow-md hover:shadow-lg hover:scale-[1.01] active:scale-[0.99] transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fas fa-camera"></i>
                            <span>Cambiar Foto de Perfil</span>
                        </button>

                        <!-- Botón que aparece solo cuando ya se seleccionó una foto nueva -->
                        <button type="submit" 
                                id="btn-guardar-foto"
                                class="hidden w-full py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs sm:text-sm tracking-wide shadow-lg hover:scale-[1.01] active:scale-[0.99] transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fas fa-save"></i>
                            <span>Guardar Nueva Foto</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- Scripts de Interacción y Gestión de Foto -->
    <script>
        // Previsualización y validación inmediata de foto de perfil
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];

                // Validar tipo de formato (JPG, JPEG o PNG)
                const tiposValidos = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!tiposValidos.includes(file.type)) {
                    if (window.mostrarAlertaBanner) {
                        window.mostrarAlertaBanner('Formato no permitido. Solo se aceptan imágenes en formato JPG o PNG.', 'error');
                    } else {
                        alert('Formato no permitido. Solo se aceptan imágenes JPG o PNG.');
                    }
                    input.value = '';
                    return;
                }

                // Validar tamaño máximo (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    if (window.mostrarAlertaBanner) {
                        window.mostrarAlertaBanner('La foto seleccionada supera el límite de 2MB. Por favor elige una imagen más liviana.', 'error');
                    } else {
                        alert('La foto seleccionada supera el límite de 2MB.');
                    }
                    input.value = '';
                    return;
                }

                // Mostrar tarjeta indicadora con el nombre del archivo seleccionado
                const photoCard = document.getElementById('photo-selected-card');
                const photoName = document.getElementById('photo-selected-name');
                if (photoCard && photoName) {
                    photoName.textContent = file.name;
                    photoCard.classList.remove('hidden');
                }

                // Cambiar el botón principal para "Guardar Nueva Foto"
                const btnSelect = document.getElementById('btn-seleccionar-foto');
                const btnSave = document.getElementById('btn-guardar-foto');
                if (btnSelect && btnSave) {
                    btnSelect.classList.add('hidden');
                    btnSave.classList.remove('hidden');
                }

                // Previsualizar inmediatamente en el avatar circular
                const reader = new FileReader();
                reader.onload = function(e) {
                    const avatarImg = document.getElementById('avatar-img');
                    const avatarInitials = document.getElementById('avatar-initials');
                    if (avatarImg) {
                        avatarImg.src = e.target.result;
                        avatarImg.classList.remove('hidden');
                    }
                    if (avatarInitials) avatarInitials.classList.add('hidden');
                };
                reader.readAsDataURL(file);

                if (window.mostrarAlertaBanner) {
                    window.mostrarAlertaBanner('Foto seleccionada. Haz clic en "Guardar Nueva Foto" para confirmar.', 'info');
                }
            }
        }

        // Eliminar foto de perfil actual
        function submitRemoveAvatar() {
            const doRemove = () => {
                document.getElementById('remove_avatar').value = '1';
                document.getElementById('profile-form').submit();
            };

            if (window.abrirModalConfirmacion) {
                window.abrirModalConfirmacion({
                    tipo: 'danger',
                    titulo: '¿Eliminar foto de perfil?',
                    descripcion: 'Tu foto de perfil será eliminada y volverás a mostrar tus iniciales institucionales.',
                    btnTexto: 'Sí, eliminar foto',
                    btnIcon: 'fa-trash-alt',
                    onConfirm: doRemove
                });
            } else if (confirm('¿Estás seguro de que deseas eliminar tu foto de perfil?')) {
                doRemove();
            }
        }

        // Cerrar y ocultar suavemente alertas
        function closeAlert(alertId) {
            const alertEl = document.getElementById(alertId);
            if (alertEl) {
                alertEl.style.opacity = '0';
                alertEl.style.transform = 'translateY(-10px)';
                setTimeout(() => alertEl.remove(), 400);
            }
        }
    </script>
</x-sisig::layouts.admin>
