<x-sisig::layouts.admin title="Gestión de Usuarios SISIG">
    <div class="space-y-6 sm:space-y-8 animate-fade-in pb-12">

        <!-- 1. Encabezado de la Sección -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-5 border-b border-slate-200">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                    <i class="fas fa-users-cog text-sena-green"></i>
                    <span>Gestión de Usuarios SISIG</span>
                </h1>

            </div>

            <div class="self-start lg:self-auto">
                <button type="button" onclick="openCreateModal()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-xs font-bold text-white bg-sena-green hover:bg-emerald-600 shadow-sena hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                    <i class="fas fa-user-plus text-xs"></i>
                    <span>Nuevo Usuario</span>
                </button>
            </div>
        </div>

        <!-- Alertas de Sesión y Validación -->
        @if(session('success') || session('error'))
            @php $isSuccess = session('success'); @endphp
            <div id="session-alert" class="p-4 rounded-2xl {{ $isSuccess ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : 'bg-rose-50 border-rose-200 text-rose-800' }} border flex items-center justify-between gap-3 shadow-sm transition-all duration-500 ease-out">
                <div class="flex items-center gap-3">
                    <i class="fas {{ $isSuccess ? 'fa-check-circle text-emerald-600' : 'fa-exclamation-triangle text-rose-600' }} text-base flex-shrink-0"></i>
                    <p class="text-xs sm:text-sm font-bold">{{ $isSuccess ?? session('error') }}</p>
                </div>
                <button type="button" onclick="dismissAlert()" class="p-1.5 rounded-lg opacity-70 hover:opacity-100 transition-opacity">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
            <script>
                function dismissAlert() {
                    const alertEl = document.getElementById('session-alert');
                    if (alertEl) {
                        alertEl.style.opacity = '0';
                        alertEl.style.transform = 'translateY(-8px)';
                        setTimeout(() => alertEl.remove(), 500);
                    }
                }
                setTimeout(dismissAlert, 4000);
            </script>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 space-y-1 text-xs">
                <div class="font-bold flex items-center gap-2">
                    <i class="fas fa-exclamation-circle text-rose-600"></i>
                    <span>Por favor corrige los siguientes errores:</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 pl-2 text-rose-700 font-medium">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- 2. Tarjetas de Métricas de Usuarios (KPIs con bucle optimizado) -->
        @php
            $kpis = [
                [
                    'title' => 'Total Usuarios',
                    'value' => $totalUsuarios,
                    'sub'   => 'registrados',
                    'icon'  => 'fa-users text-slate-700',
                    'bar'   => 'from-slate-600 to-slate-800',
                ],
                [
                    'title' => 'Administradores',
                    'value' => $totalAdmins,
                    'sub'   => 'admin SISIG',
                    'icon'  => 'fa-user-shield text-emerald-600',
                    'bar'   => 'from-emerald-500 to-teal-600',
                ],
                [
                    'title' => 'Editores SISIG',
                    'value' => $totalEditores,
                    'sub'   => 'editores',
                    'icon'  => 'fa-edit text-sky-600',
                    'bar'   => 'from-sky-500 to-blue-600',
                ],
                [
                    'title' => 'Aprendices',
                    'value' => $totalAprendices,
                    'sub'   => 'en formación',
                    'icon'  => 'fa-user-graduate text-teal-600',
                    'bar'   => 'from-teal-500 to-emerald-600',
                ],
            ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($kpis as $kpi)
                <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r {{ $kpi['bar'] }}"></div>
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">{{ $kpi['title'] }}</span>
                        <i class="fas {{ $kpi['icon'] }} text-2xl group-hover:scale-110 transition-transform"></i>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-baseline gap-1.5">
                            <h3 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight">{{ $kpi['value'] }}</h3>
                            <span class="text-sm font-bold text-slate-400">{{ $kpi['sub'] }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- 3. Barra de Búsqueda y Filtros Rápidos -->
        <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/90 shadow-sm space-y-4">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                
                <div class="relative flex-1 max-w-md">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" id="user-search-input" placeholder="Buscar por nombre, documento, correo o alias..." 
                           class="w-full pl-10 pr-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none text-xs transition-all text-slate-800">
                </div>

                <!-- Filtros combinados -->
                <div class="flex flex-wrap items-center gap-3 text-xs">
                    <div class="flex items-center gap-1.5 bg-slate-50 p-1.5 rounded-2xl border border-slate-200">
                        <span class="text-slate-400 font-bold px-2 hidden sm:inline">Rol:</span>
                        <button type="button" onclick="filterByRole('todos')" class="role-filter-btn px-3 py-1 rounded-xl font-bold bg-slate-900 text-white shadow-sm" data-role="todos">Todos</button>
                        <button type="button" onclick="filterByRole('admin')" class="role-filter-btn px-3 py-1 rounded-xl font-bold text-slate-600 hover:bg-slate-200" data-role="admin">Admins</button>
                        <button type="button" onclick="filterByRole('editor')" class="role-filter-btn px-3 py-1 rounded-xl font-bold text-slate-600 hover:bg-slate-200" data-role="editor">Editores</button>
                        <button type="button" onclick="filterByRole('aprendiz')" class="role-filter-btn px-3 py-1 rounded-xl font-bold text-slate-600 hover:bg-slate-200" data-role="aprendiz">Aprendices</button>
                    </div>

                    <div class="flex items-center gap-1.5 bg-slate-50 p-1.5 rounded-2xl border border-slate-200">
                        <span class="text-slate-400 font-bold px-2 hidden sm:inline">Estado:</span>
                        <button type="button" onclick="filterByStatus('todos')" class="status-filter-btn px-3 py-1 rounded-xl font-bold bg-slate-900 text-white shadow-sm" data-status="todos">Todos</button>
                        <button type="button" onclick="filterByStatus('activo')" class="status-filter-btn px-3 py-1 rounded-xl font-bold text-slate-600 hover:bg-slate-200" data-status="activo">Activos</button>
                        <button type="button" onclick="filterByStatus('inactivo')" class="status-filter-btn px-3 py-1 rounded-xl font-bold text-slate-600 hover:bg-slate-200" data-status="inactivo">Inactivos</button>
                    </div>
                </div>

            </div>
        </div>

        <!-- 4. Tabla Principal de Usuarios -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-800 flex items-center gap-2.5">
                        <i class="fas fa-address-book text-sena-green"></i>
                        <span>Directorio de Usuarios de SISIG</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Listado institucional con roles, estado de cuenta y avance formativo</p>
                </div>
                <span id="filtered-counter" class="text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1 rounded-xl self-start sm:self-auto">
                    Usuarios: {{ $usersData->count() }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider">
                            <th class="py-3.5 px-4">Usuario / Persona</th>
                            <th class="py-3.5 px-4">Documento</th>
                            <th class="py-3.5 px-4">Correo Electrónico</th>
                            <th class="py-3.5 px-4">Rol en SISIG</th>
                            <th class="py-3.5 px-4">Estado</th>
                            <th class="py-3.5 px-4">Avance Inducción</th>
                            <th class="py-3.5 px-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="users-table-body" class="divide-y divide-slate-100">
                        @forelse($usersData as $u)
                            <tr class="user-row hover:bg-slate-50/80 transition-colors {{ !$u->is_active ? 'opacity-65 bg-slate-50/40' : '' }}" 
                                data-name="{{ strtolower($u->full_name . ' ' . $u->name . ' ' . $u->nickname) }}"
                                data-email="{{ strtolower($u->email) }}"
                                data-document="{{ $u->document_number }}"
                                data-role="{{ $u->rol_slug ?? 'sin_rol' }}"
                                data-status="{{ $u->is_active ? 'activo' : 'inactivo' }}">
                                
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl overflow-hidden {{ $u->is_active ? 'bg-gradient-to-tr from-slate-700 to-slate-800' : 'bg-slate-400' }} text-white font-black text-xs flex items-center justify-center flex-shrink-0 shadow-sm border border-slate-200/50">
                                            @if($u->avatar && file_exists(public_path('storage/' . $u->avatar)))
                                                <img src="{{ asset('storage/' . $u->avatar) }}" alt="{{ $u->name }}" class="w-full h-full object-cover">
                                            @else
                                                {{ strtoupper(substr($u->name, 0, 1)) }}{{ strtoupper(substr($u->first_last_name, 0, 1)) }}
                                            @endif
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-800 block text-xs sm:text-sm">{{ $u->full_name ?: $u->name }}</span>
                                            <span class="text-[11px] text-slate-400 font-medium">Alias: {{ $u->nickname ?? 'Sin alias' }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-3.5 px-4 text-slate-600 font-mono text-xs">
                                    <span class="font-bold text-slate-700">{{ $u->document_number }}</span>
                                    <span class="text-[10px] text-slate-400 block">{{ $u->document_type }}</span>
                                </td>

                                <td class="py-3.5 px-4 text-slate-600">
                                    <span class="font-medium text-slate-700">{{ $u->email }}</span>
                                    @if($u->ficha && $u->rol_slug === 'aprendiz_sisig')
                                        <span class="text-[10px] text-sena-green font-bold block">Ficha: {{ $u->ficha }}</span>
                                    @endif
                                </td>

                                <td class="py-3.5 px-4">
                                    @php
                                        $colors = match($u->rol_slug) {
                                            'admin_sisig' => ['text-emerald-600', 'bg-emerald-500'],
                                            'editor_sisig' => ['text-sky-600', 'bg-sky-500'],
                                            'aprendiz_sisig' => ['text-teal-600', 'bg-teal-500'],
                                            default => ['text-slate-500', 'bg-slate-400'],
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold {{ $colors[0] }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $colors[1] }}"></span>
                                        <span>{{ $u->rol_nombre }}</span>
                                    </span>
                                </td>

                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold {{ $u->is_active ? 'text-emerald-600' : 'text-rose-600' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $u->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                        <span>{{ $u->is_active ? 'Activo' : 'Inactivo' }}</span>
                                    </span>
                                </td>

                                <td class="py-3.5 px-4">
                                    @if($u->rol_slug === 'aprendiz_sisig')
                                        <div class="w-28">
                                            <div class="flex items-center justify-between text-[11px] mb-1">
                                                <span class="font-bold text-slate-700">{{ $u->progreso_promedio }}%</span>
                                                <span class="text-slate-400">{{ $u->quices_aprobados }} quices</span>
                                            </div>
                                            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                                <div class="bg-sena-green h-1.5 rounded-full" style="width: {{ $u->progreso_promedio }}%"></div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-slate-300 font-bold text-sm select-none">—</span>
                                    @endif
                                </td>

                                <td class="py-3.5 px-4 text-right">
                                    <div class="inline-flex items-center gap-1.5">
                                        <button type="button" onclick="openEditModal({{ $u->id }})"
                                                class="p-1.5 rounded-xl bg-slate-100 hover:bg-amber-50 text-slate-600 hover:text-amber-700 font-bold text-xs transition-colors border border-slate-200"
                                                title="Editar información y rol del usuario">
                                            <i class="fas fa-pen text-xs"></i>
                                        </button>

                                        @if(Auth::id() !== $u->id)
                                            <button type="button" 
                                                    onclick="confirmToggleStatus({{ $u->id }}, '{{ addslashes($u->full_name ?: $u->name) }}', {{ $u->is_active ? 'true' : 'false' }})"
                                                    class="p-1.5 rounded-xl {{ $u->is_active ? 'bg-slate-100 hover:bg-rose-50 text-slate-500 hover:text-rose-600' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }} font-bold text-xs transition-colors border border-slate-200"
                                                    title="{{ $u->is_active ? 'Desactivar Cuenta' : 'Reactivar Cuenta' }}">
                                                <i class="fas {{ $u->is_active ? 'fa-user-slash' : 'fa-user-check' }} text-xs"></i>
                                            </button>

                                            <!-- Botón de Eliminar Usuario (Validación de Actividad) -->
                                            <button type="button" 
                                                    onclick="confirmDeleteUser({{ $u->id }}, '{{ addslashes($u->full_name ?: $u->name) }}', {{ $u->has_activity ? 'true' : 'false' }})"
                                                    class="p-1.5 rounded-xl {{ $u->has_activity ? 'bg-slate-100 text-slate-400 hover:bg-slate-200' : 'bg-slate-100 hover:bg-rose-50 text-slate-500 hover:text-rose-600' }} font-bold text-xs transition-colors border border-slate-200"
                                                    title="{{ $u->has_activity ? 'Tiene actividad registrada (no eliminable)' : 'Eliminar usuario permanentemente' }}">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12 text-slate-400">
                                    <i class="fas fa-users-slash text-3xl mb-2 block text-slate-300"></i>
                                    No hay usuarios registrados en el sistema.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="no-results-msg" class="hidden text-center py-8 text-slate-400 text-xs">
                <i class="fas fa-search text-2xl mb-2 block text-slate-300"></i>
                No se encontraron usuarios que coincidan con la búsqueda o filtros aplicados.
            </div>
        </div>

    </div>

    <!-- MODAL POLIMÓRFICO: CREAR Y EDITAR USUARIO (UNIFICADO) -->
    <div id="user-modal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-7 shadow-2xl border border-slate-200 animate-scale-in max-h-[90vh] overflow-y-auto relative">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div id="user-modal-icon-wrapper" class="flex items-center justify-center flex-shrink-0">
                        <img id="user-modal-avatar-img" src="" alt="Avatar" class="w-10 h-10 rounded-full object-cover shadow-sm border border-slate-200 hidden">
                        <i id="user-modal-icon" class="fas fa-user-plus text-2xl text-sena-green"></i>
                    </div>
                    <div>
                        <h3 id="user-modal-title" class="text-base font-bold text-slate-800">Registrar Nuevo Usuario</h3>
                        <p id="user-modal-subtitle" class="text-xs text-slate-500 mt-0.5">Creación de cuenta y vinculación a persona en el ERP</p>
                    </div>
                </div>
                <button type="button" onclick="closeUserModal()" class="text-slate-400 hover:text-slate-600 p-2 rounded-xl hover:bg-slate-100 transition-colors">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <form id="user-form" action="{{ route('sisig.admin.users.store') }}" method="POST" class="mt-5 space-y-4">
                @csrf
                <div id="user-form-method"></div>

                <!-- Documento -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label class="text-xs font-bold text-slate-700 block mb-1">Tipo de Documento *</label>
                        <select name="document_type" id="user-doctype" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all">
                            <option value="Cédula de ciudadanía">Cédula de ciudadanía</option>
                            <option value="Tarjeta de identidad">Tarjeta de identidad</option>
                            <option value="Cédula de extranjería">Cédula de extranjería</option>
                            <option value="PEP">Permiso Especial de Permanencia (PEP)</option>
                            <option value="Pasaporte">Pasaporte</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-700 block mb-1">Número de Documento *</label>
                        <input type="text" name="document_number" id="user-docnumber" required placeholder="Ej. 1000123456" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all">
                    </div>
                </div>

                <!-- Nombres y Apellidos -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                    <div>
                        <label class="text-xs font-bold text-slate-700 block mb-1">Nombres *</label>
                        <input type="text" name="first_name" id="user-firstname" required placeholder="Ej. Carlos Andrés" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-700 block mb-1">Primer Apellido *</label>
                        <input type="text" name="first_last_name" id="user-firstlastname" required placeholder="Ej. Pérez" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-700 block mb-1">Segundo Apellido</label>
                        <input type="text" name="second_last_name" id="user-secondlastname" placeholder="Ej. Gómez (Opcional)" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all">
                    </div>
                </div>

                <!-- Correo y Alias -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label class="text-xs font-bold text-slate-700 block mb-1">Correo Electrónico *</label>
                        <input type="email" name="email" id="user-email" required placeholder="usuario@sena.edu.co" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-700 block mb-1">Nombre de Usuario / Alias</label>
                        <input type="text" name="nickname" id="user-nickname" placeholder="Ej. cperez (Opcional)" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all">
                    </div>
                </div>

                <!-- Contraseña y Rol -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label id="user-password-label" class="text-xs font-bold text-slate-700 block mb-1">Contraseña de Acceso *</label>
                        <input type="password" name="password" id="user-password" minlength="6" placeholder="Mínimo 6 caracteres" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-700 block mb-1">Rol en SISIG *</label>
                        <select name="role_slug" id="user-roleselect" onchange="toggleFichaField(this.value)" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all">
                            <option value="aprendiz_sisig" selected>Aprendiz SISIG (Inducción y Evaluaciones)</option>
                            <option value="editor_sisig">Editor SISIG (Gestión de Módulos y Quices)</option>
                            <option value="admin_sisig">Administrador SISIG (Control total)</option>
                            <option value="ninguno">Sin Rol en SISIG</option>
                        </select>
                    </div>
                </div>

                <!-- Campo Ficha si es Aprendiz -->
                <div id="user-ficha-container" class="p-3 bg-emerald-50/50 rounded-2xl border border-emerald-200/60">
                    <label class="text-xs font-bold text-slate-700 block mb-1">Número de Ficha de Formación (Aprendices)</label>
                    <input type="text" name="ficha" id="user-ficha" placeholder="Ej. 2800001" class="w-full px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-xs focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none transition-all">
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeUserModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" id="user-submit-btn" class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-sena-green hover:bg-emerald-600 shadow-sena transition-all">
                        Crear Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- FORMULARIO OCULTO PARA ACTIVAR / DESACTIVAR USUARIO -->
    <form id="toggle-status-form" method="POST" action="" class="hidden">
        @csrf
        @method('PATCH')
    </form>

    <!-- FORMULARIO OCULTO PARA ELIMINAR USUARIO PERMANENTEMENTE -->
    <form id="delete-user-form" method="POST" action="" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <!-- SCRIPTS DE INTERACCIÓN REACTIVA -->
    <script>
        let currentRoleFilter = 'todos';
        let currentStatusFilter = 'todos';
        const searchInput = document.getElementById('user-search-input');
        const rows = document.querySelectorAll('.user-row');
        const counter = document.getElementById('filtered-counter');
        const noResults = document.getElementById('no-results-msg');

        // 1. Filtrado en Vivo y Búsqueda combinada
        function applyFilters() {
            const query = searchInput.value.toLowerCase().trim();
            let visibleCount = 0;

            rows.forEach(row => {
                const name = row.dataset.name;
                const email = row.dataset.email;
                const doc = row.dataset.document;
                const role = row.dataset.role;
                const status = row.dataset.status;

                const matchesSearch = !query || name.includes(query) || email.includes(query) || doc.includes(query);

                let matchesRole = currentRoleFilter === 'todos' 
                    || (currentRoleFilter === 'admin' && (role === 'admin_sisig' || role === 'sisig.admin'))
                    || (currentRoleFilter === 'editor' && role === 'editor_sisig')
                    || (currentRoleFilter === 'aprendiz' && (role === 'aprendiz_sisig' || role === 'sisig.aprendiz'));

                let matchesStatus = currentStatusFilter === 'todos' || (status === currentStatusFilter);

                const isVisible = matchesSearch && matchesRole && matchesStatus;
                row.style.display = isVisible ? '' : 'none';
                if (isVisible) visibleCount++;
            });

            counter.textContent = `Usuarios: ${visibleCount}`;
            noResults.classList.toggle('hidden', visibleCount > 0);
        }

        searchInput.addEventListener('input', applyFilters);

        function filterByRole(role) {
            currentRoleFilter = role;
            document.querySelectorAll('.role-filter-btn').forEach(btn => {
                btn.className = btn.dataset.role === role 
                    ? 'role-filter-btn px-3 py-1 rounded-xl font-bold bg-slate-900 text-white shadow-sm'
                    : 'role-filter-btn px-3 py-1 rounded-xl font-bold text-slate-600 hover:bg-slate-200';
            });
            applyFilters();
        }

        function filterByStatus(status) {
            currentStatusFilter = status;
            document.querySelectorAll('.status-filter-btn').forEach(btn => {
                btn.className = btn.dataset.status === status 
                    ? 'status-filter-btn px-3 py-1 rounded-xl font-bold bg-slate-900 text-white shadow-sm'
                    : 'status-filter-btn px-3 py-1 rounded-xl font-bold text-slate-600 hover:bg-slate-200';
            });
            applyFilters();
        }

        // 2. Control del Modal Polimórfico (Crear y Editar)
        const modal = document.getElementById('user-modal');
        const form = document.getElementById('user-form');
        const methodContainer = document.getElementById('user-form-method');

        function toggleFichaField(role) {
            document.getElementById('user-ficha-container').classList.toggle('hidden', role !== 'aprendiz_sisig');
        }

        function openCreateModal() {
            form.reset();
            form.action = "{{ route('sisig.admin.users.store') }}";
            methodContainer.innerHTML = '';

            document.getElementById('user-modal-title').textContent = 'Registrar Nuevo Usuario';
            document.getElementById('user-modal-subtitle').textContent = 'Creación de cuenta y vinculación a persona en el ERP';
            document.getElementById('user-modal-avatar-img').classList.add('hidden');
            const createIcon = document.getElementById('user-modal-icon');
            createIcon.className = 'fas fa-user-plus text-2xl text-sena-green';
            createIcon.classList.remove('hidden');

            const pwd = document.getElementById('user-password');
            pwd.required = true;
            pwd.placeholder = 'Mínimo 6 caracteres';
            document.getElementById('user-password-label').innerHTML = 'Contraseña de Acceso *';

            document.getElementById('user-roleselect').value = 'aprendiz_sisig';
            toggleFichaField('aprendiz_sisig');
            document.getElementById('user-submit-btn').textContent = 'Crear Usuario';

            modal.classList.remove('hidden');
        }

        function openEditModal(userId) {
            fetch(`{{ url('sisig/admin/usuarios') }}/${userId}/detalle`)
                .then(res => res.json())
                .then(data => {
                    form.reset();
                    form.action = `{{ url('sisig/admin/usuarios') }}/${userId}`;
                    methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';

                    document.getElementById('user-modal-title').textContent = 'Editar Datos y Rol del Usuario';
                    document.getElementById('user-modal-subtitle').textContent = `Editando a: ${data.name} (${data.email})`;
                    
                    const avatarImg = document.getElementById('user-modal-avatar-img');
                    const modalIcon = document.getElementById('user-modal-icon');
                    if (data.avatar_url) {
                        avatarImg.src = data.avatar_url;
                        avatarImg.classList.remove('hidden');
                        modalIcon.classList.add('hidden');
                    } else {
                        avatarImg.classList.add('hidden');
                        modalIcon.className = 'fas fa-user-edit text-2xl text-amber-500';
                        modalIcon.classList.remove('hidden');
                    }

                    document.getElementById('user-doctype').value = data.document_type || 'Cédula de ciudadanía';
                    document.getElementById('user-docnumber').value = data.document_number || '';
                    document.getElementById('user-firstname').value = data.first_name || '';
                    document.getElementById('user-firstlastname').value = data.first_last_name || '';
                    document.getElementById('user-secondlastname').value = data.second_last_name || '';
                    document.getElementById('user-email').value = data.email || '';
                    document.getElementById('user-nickname').value = data.nickname || '';

                    const pwd = document.getElementById('user-password');
                    pwd.required = false;
                    pwd.placeholder = 'Dejar en blanco para no modificar';
                    document.getElementById('user-password-label').innerHTML = 'Nueva Contraseña <span class="text-slate-400 font-normal">(Opcional)</span>';

                    const role = data.rol_slug || 'ninguno';
                    document.getElementById('user-roleselect').value = role;
                    document.getElementById('user-ficha').value = data.ficha || '';
                    toggleFichaField(role);

                    document.getElementById('user-submit-btn').textContent = 'Guardar Cambios';
                    modal.classList.remove('hidden');
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de carga',
                        text: 'No se pudo cargar la información del usuario para edición.',
                        confirmButtonColor: '#39A900',
                        confirmButtonText: 'Aceptar'
                    });
                });
        }

        function closeUserModal() {
            modal.classList.add('hidden');
        }

        // 3. Confirmación para Desactivar / Activar Usuario
        function confirmToggleStatus(userId, userName, isActive) {
            if (isActive) {
                Swal.fire({
                    title: '¿Desactivar cuenta?',
                    html: `¿Estás seguro de desactivar la cuenta de <strong class="text-slate-800 font-bold">${userName}</strong>?<br><span class="text-xs text-slate-500 mt-2 block">El usuario no podrá acceder al sistema SISIG hasta que sea reactivado.</span>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f59e0b',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: '<i class="fas fa-user-slash mr-1"></i> Sí, desactivar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-2xl shadow-2xl border border-slate-100',
                        confirmButton: 'px-4 py-2.5 rounded-xl font-bold text-sm shadow-md',
                        cancelButton: 'px-4 py-2.5 rounded-xl font-bold text-sm'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const formStatus = document.getElementById('toggle-status-form');
                        formStatus.action = `{{ url('sisig/admin/usuarios') }}/${userId}/estado`;
                        formStatus.submit();
                    }
                });
            } else {
                Swal.fire({
                    title: '¿Reactivar cuenta?',
                    html: `¿Deseas reactivar la cuenta de <strong class="text-slate-800 font-bold">${userName}</strong>?<br><span class="text-xs text-slate-500 mt-2 block">El usuario recuperará el acceso inmediato a la plataforma SISIG.</span>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#39A900',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: '<i class="fas fa-user-check mr-1"></i> Sí, reactivar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-2xl shadow-2xl border border-slate-100',
                        confirmButton: 'px-4 py-2.5 rounded-xl font-bold text-sm shadow-md',
                        cancelButton: 'px-4 py-2.5 rounded-xl font-bold text-sm'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const formStatus = document.getElementById('toggle-status-form');
                        formStatus.action = `{{ url('sisig/admin/usuarios') }}/${userId}/estado`;
                        formStatus.submit();
                    }
                });
            }
        }

        // 4. Confirmación para Eliminar Usuario Definitivamente (con validación de actividad)
        function confirmDeleteUser(userId, userName, hasActivity) {
            if (hasActivity) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No se puede eliminar',
                    text: `El usuario "${userName}" cuenta con actividad registrada en la plataforma (evaluaciones o progreso). Para preservar la trazabilidad institucional, no se puede eliminar. Puedes desactivar su cuenta en su lugar.`,
                    confirmButtonColor: '#39A900',
                    confirmButtonText: 'Entendido'
                });
                return;
            }

            Swal.fire({
                title: '¿Eliminar usuario permanentemente?',
                text: `¿Estás seguro de que deseas eliminar permanentemente a "${userName}"? Este usuario no tiene ninguna actividad registrada y será borrado del sistema.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const deleteForm = document.getElementById('delete-user-form');
                    deleteForm.action = `{{ url('sisig/admin/usuarios') }}/${userId}`;
                    deleteForm.submit();
                }
            });
        }
    </script>
</x-sisig::layouts.admin>
