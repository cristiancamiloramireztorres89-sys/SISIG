<x-sisig::layouts.admin title="Mi Perfil">
    <div class="space-y-6">

        <!-- Encabezado -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-5 border-b border-slate-200">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                    <i class="fas fa-id-badge text-sena-green"></i>
                    <span>Mi Perfil de Usuario</span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                    Consulta y gestiona tu información personal, credenciales y seguridad de tu cuenta en SISIG.
                </p>
            </div>
            <div>
                @php
                    $dashboardUrl = (auth()->check() && auth()->user()->hasRole('admin_sisig'))
                        ? route('sisig.admin.dashboard')
                        : route('sisig.aprendiz.dashboard');
                @endphp
                <a href="{{ $dashboardUrl }}" 
                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold text-slate-600 hover:text-slate-900 bg-white border border-slate-300 hover:border-slate-400 shadow-sm transition-all">
                    <i class="fas fa-arrow-left"></i>
                    <span>Volver al Dashboard</span>
                </a>
            </div>
        </div>

        <!-- Alertas de Sesión Temporales -->
        @if(session('success'))
            <div id="session-alert" class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between gap-3 shadow-sm transition-all duration-500">
                <div class="flex items-center gap-3">
                    <i class="fas fa-check-circle text-emerald-600 text-base flex-shrink-0"></i>
                    <p class="text-xs sm:text-sm font-bold">{{ session('success') }}</p>
                </div>
                <button type="button" onclick="closeAlert('session-alert')" class="text-emerald-500 hover:text-emerald-800 p-1.5 rounded-lg hover:bg-emerald-100 transition-colors" title="Cerrar">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div id="error-alert" class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-start justify-between gap-3 shadow-sm transition-all duration-500">
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

        <!-- Grid Principal: Resumen (Izquierda) + Formularios (Derecha) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Columna Izquierda: Tarjeta de Identidad -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 text-center relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-2 bg-sena-green"></div>

                    @php
                        $initials = 'U';
                        if ($person && $person->first_name) {
                            $initials = strtoupper(substr($person->first_name, 0, 1) . substr($person->first_last_name ?? '', 0, 1));
                        } elseif ($user->name) {
                            $initials = strtoupper(substr($user->name, 0, 2));
                        }
                    @endphp

                    <!-- Avatar / Foto con botón de cambio -->
                    <div class="relative mx-auto w-24 h-24 mt-2 group">
                        <div id="avatar-container" class="w-24 h-24 rounded-full bg-slate-900 text-white flex items-center justify-center text-3xl font-black shadow-md border-4 border-white overflow-hidden">
                            @if($person && $person->avatar)
                                <img id="avatar-img" src="{{ asset('storage/' . $person->avatar) }}" alt="Foto de Perfil" class="w-full h-full object-cover">
                            @else
                                <span id="avatar-initials">{{ $initials }}</span>
                                <img id="avatar-img" src="" alt="Foto de Perfil" class="w-full h-full object-cover hidden">
                            @endif
                        </div>
                        <label for="avatar_file" title="Cambiar Foto de Perfil" class="absolute bottom-0 right-0 w-7 h-7 rounded-full bg-sena-green hover:bg-sena-green-hover text-white flex items-center justify-center shadow-md cursor-pointer transition-all hover:scale-110 border-2 border-white">
                            <i class="fas fa-camera text-[11px]"></i>
                        </label>
                    </div>

                    @if($person && $person->avatar)
                        <button type="button" onclick="submitRemoveAvatar()" class="mt-2 text-[11px] text-rose-500 hover:text-rose-700 font-semibold inline-flex items-center gap-1 transition-colors">
                            <i class="fas fa-trash-alt text-[10px]"></i>
                            <span>Quitar foto actual</span>
                        </button>
                    @endif

                    <h2 class="text-base sm:text-lg font-black text-slate-900 mt-3 leading-snug">
                        {{ $person ? trim($person->first_name . ' ' . $person->first_last_name . ' ' . ($person->second_last_name ?? '')) : $user->name }}
                    </h2>

                    @php
                        $badgeRol = 'Aprendiz SISIG';
                        if ($roles->isNotEmpty()) {
                            $badgeRol = $roles->pluck('name')->join(', ');
                        } elseif ($user->hasRole('admin_sisig')) {
                            $badgeRol = 'Administrador SISIG';
                        } elseif ($user->hasRole('editor_sisig')) {
                            $badgeRol = 'Editor SISIG';
                        }
                    @endphp
                    <div class="mt-3">
                        <span class="text-xs font-extrabold tracking-wide uppercase text-sena-green flex items-center justify-center gap-1.5">
                            <i class="fas fa-shield-alt text-sm"></i>
                            <span>{{ $badgeRol }}</span>
                        </span>
                    </div>

                    <div class="mt-6 pt-5 border-t border-slate-100 text-left space-y-3">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500 font-medium">Documento:</span>
                            <span class="font-bold text-slate-800">{{ $person->document_type ?? 'CC' }}: {{ $person->document_number ?? 'N/D' }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500 font-medium">Correo Institucional:</span>
                            <span class="font-semibold text-slate-800 truncate max-w-[180px]" title="{{ $user->email }}">{{ $user->email }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500 font-medium">Correo Personal:</span>
                            <span class="font-semibold text-slate-800 truncate max-w-[180px]" title="{{ $person->personal_email ?? 'No registrado' }}">{{ $person->personal_email ?? 'No registrado' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Información para Aprendiz si aplica -->
                @if($aprendiz)
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                        <h3 class="text-xs font-black uppercase tracking-wider text-slate-700 flex items-center gap-2 mb-3">
                            <i class="fas fa-graduation-cap text-sky-500"></i>
                            <span>Información Académica</span>
                        </h3>
                        <div class="space-y-2.5 text-xs">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Ficha:</span>
                                <span class="font-bold text-slate-800">{{ $aprendiz->course_id ?? 'N/D' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Estado de Formación:</span>
                                <span class="font-bold text-sky-600 uppercase">{{ $aprendiz->apprentice_status ?? 'En formación' }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Columna Derecha: Formularios -->
            <div class="lg:col-span-8 space-y-6">

                <!-- 1. Información Personal y Contacto -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
                    <div class="pb-4 border-b border-slate-100 mb-6">
                        <h2 class="text-base sm:text-lg font-black text-slate-900 flex items-center gap-2">
                            <i class="fas fa-user-edit text-sena-green"></i>
                            <span>Información Personal y Contacto</span>
                        </h2>
                        <p class="text-xs text-slate-500 mt-1">
                            Los datos de identidad provienen de la base institucional. Puedes actualizar tus correos de contacto.
                        </p>
                    </div>

                    <form id="profile-form" action="{{ route('sisig.perfil.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <!-- Inputs ocultos para gestión de avatar desde la foto de la izquierda -->
                        <input type="hidden" id="remove_avatar" name="remove_avatar" value="0">
                        <input type="file" id="avatar_file" name="avatar" accept="image/png,image/jpeg,image/jpg,image/webp" class="hidden" onchange="previewAvatar(this)">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nombre Completo</label>
                                <div class="relative">
                                    <input type="text" value="{{ $person ? trim($person->first_name . ' ' . $person->first_last_name . ' ' . ($person->second_last_name ?? '')) : $user->name }}" disabled class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-slate-50 border border-slate-200 text-slate-500 cursor-not-allowed font-medium">
                                    <i class="fas fa-lock absolute right-3.5 top-3 text-slate-400 text-xs"></i>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Documento de Identidad</label>
                                <div class="relative">
                                    <input type="text" value="{{ ($person->document_type ?? 'CC') . ' - ' . ($person->document_number ?? 'N/D') }}" disabled class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-slate-50 border border-slate-200 text-slate-500 cursor-not-allowed font-medium">
                                    <i class="fas fa-lock absolute right-3.5 top-3 text-slate-400 text-xs"></i>
                                </div>
                            </div>

                            <div>
                                <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">Correo Institucional <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <i class="fas fa-envelope absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full pl-9 pr-3.5 py-2.5 rounded-xl text-xs bg-white border border-slate-300 focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all font-medium text-slate-800">
                                </div>
                            </div>

                            <div>
                                <label for="personal_email" class="block text-xs font-bold text-slate-700 mb-1.5">Correo Personal <span class="text-slate-400 font-normal">(Opcional)</span></label>
                                <div class="relative">
                                    <i class="fas fa-at absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                                    <input type="email" id="personal_email" name="personal_email" value="{{ old('personal_email', $person->personal_email ?? '') }}" placeholder="ejemplo@gmail.com" class="w-full pl-9 pr-3.5 py-2.5 rounded-xl text-xs bg-white border border-slate-300 focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all font-medium text-slate-800">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-3">
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-sena-green hover:bg-sena-green-hover transition-all shadow-sena hover:scale-[1.01]">
                                <i class="fas fa-save"></i>
                                <span>Guardar Cambios</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- 2. Seguridad y Cambio de Contraseña -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
                    <div class="pb-4 border-b border-slate-100 mb-6">
                        <h2 class="text-base sm:text-lg font-black text-slate-900 flex items-center gap-2">
                            <i class="fas fa-key text-amber-500"></i>
                            <span>Seguridad y Cambio de Contraseña</span>
                        </h2>
                        <p class="text-xs text-slate-500 mt-1">
                            Para cambiar tu contraseña, ingresa primero tu clave actual para verificar tu identidad.
                        </p>
                    </div>

                    <form action="{{ route('sisig.perfil.password') }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label for="current_password" class="block text-xs font-bold text-slate-700 mb-1.5">Contraseña Actual <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <input type="password" id="current_password" name="current_password" required placeholder="••••••••" class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-white border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none transition-all font-medium text-slate-800 pr-10">
                                    <button type="button" onclick="togglePasswordVisibility('current_password', 'eye_current')" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600">
                                        <i id="eye_current" class="fas fa-eye text-xs"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">Nueva Contraseña <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <input type="password" id="password" name="password" required placeholder="Mínimo 8 caracteres" class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-white border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none transition-all font-medium text-slate-800 pr-10">
                                    <button type="button" onclick="togglePasswordVisibility('password', 'eye_new')" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600">
                                        <i id="eye_new" class="fas fa-eye text-xs"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1.5">Confirmar Contraseña <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Repite la contraseña" class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-white border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none transition-all font-medium text-slate-800 pr-10">
                                    <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'eye_confirm')" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600">
                                        <i id="eye_confirm" class="fas fa-eye text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-3">
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 transition-all shadow-sm hover:scale-[1.01]">
                                <i class="fas fa-shield-alt"></i>
                                <span>Actualizar Contraseña</span>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Scripts de Interacción -->
    <script>
        // Ver / ocultar contraseñas
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            icon.classList.toggle('fa-eye', !isPassword);
            icon.classList.toggle('fa-eye-slash', isPassword);
        }

        // Previsualización de la foto seleccionada
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                if (file.size > 2 * 1024 * 1024) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Imagen demasiado pesada',
                            text: 'La foto seleccionada supera el límite de 2MB. Por favor elige una imagen más liviana.',
                            confirmButtonColor: '#39A900',
                            confirmButtonText: 'Entendido'
                        });
                    } else {
                        alert('La foto seleccionada supera el límite de 2MB.');
                    }
                    input.value = '';
                    return;
                }

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
            }
        }

        // Eliminar foto con modal SweetAlert2
        function submitRemoveAvatar() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '¿Eliminar foto de perfil?',
                    text: 'Tu foto de perfil será eliminada y volverás a mostrar tus iniciales institucionales.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: '<i class="fas fa-trash-alt mr-1"></i> Sí, eliminar foto',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('remove_avatar').value = '1';
                        document.getElementById('profile-form').submit();
                    }
                });
            } else if (confirm('¿Estás seguro de que deseas eliminar tu foto de perfil?')) {
                document.getElementById('remove_avatar').value = '1';
                document.getElementById('profile-form').submit();
            }
        }

        // Cerrar y ocultar suavemente alertas
        function closeAlert(alertId) {
            const alertEl = document.getElementById(alertId);
            if (alertEl) {
                alertEl.style.opacity = '0';
                alertEl.style.transform = 'translateY(-10px)';
                setTimeout(() => alertEl.remove(), 500);
            }
        }

        // Auto-cerrar alertas temporales tras unos segundos
        document.addEventListener('DOMContentLoaded', () => {
            if (document.getElementById('session-alert')) setTimeout(() => closeAlert('session-alert'), 4000);
            if (document.getElementById('error-alert')) setTimeout(() => closeAlert('error-alert'), 5000);
        });
    </script>
</x-sisig::layouts.admin>
