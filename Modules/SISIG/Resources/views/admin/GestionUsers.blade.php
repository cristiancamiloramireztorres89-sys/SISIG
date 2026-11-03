<x-sisig::layouts.admin title="Gestión de Usuarios SISIG">
    <div class="space-y-6 sm:space-y-8 animate-fade-in pb-12">

        <!-- 1. Encabezado de la Sección -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-5 border-b border-slate-200">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                    <i class="fas fa-users-cog text-sena-green"></i>
                    <span>Gestión de Usuarios SISIG</span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Administración centralizada de roles, permisos y estados de cuentas en SISIG</p>
            </div>
        </div>



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
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500">{{ $kpi['title'] }}</span>
                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                            <i class="fas {{ $kpi['icon'] }}"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="flex items-baseline gap-1.5">
                            <h3 class="text-2xl sm:text-3xl font-extrabold text-[#343A40] tracking-tight">{{ $kpi['value'] }}</h3>
                            <span class="text-xs font-bold text-slate-400">{{ $kpi['sub'] }}</span>
                        </div>
                        <div class="mt-1 flex items-center justify-between text-xs text-slate-400 font-medium">
                            <span>Usuarios en SISIG</span>
                            <span class="text-xs font-bold text-[#2E6F40]">Sistema</span>
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
                        <button type="button" onclick="filterByStatus('activos')" 
                           class="status-filter-btn px-3 py-1 rounded-xl font-bold transition-all bg-slate-900 text-white shadow-sm" data-status="activos">
                            Activos
                        </button>
                        <button type="button" onclick="filterByStatus('inactivos')" 
                           class="status-filter-btn px-3 py-1 rounded-xl font-bold transition-all text-slate-600 hover:bg-slate-200" data-status="inactivos">
                            Inactivos @if(isset($totalInactivos) && $totalInactivos > 0) <span class="ml-1 px-1.5 py-0.5 rounded-full text-[10px] bg-slate-200 text-slate-700">{{ $totalInactivos }}</span> @endif
                        </button>
                        <button type="button" onclick="filterByStatus('todos')" 
                           class="status-filter-btn px-3 py-1 rounded-xl font-bold transition-all text-slate-600 hover:bg-slate-200" data-status="todos">
                            Todos
                        </button>
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
                                        <span class="text-[10px] text-black font-bold block">Ficha: {{ $u->ficha }}</span>
                                    @endif
                                </td>

                                <td class="py-3.5 px-4">
                                    @php
                                        $dotColor = match($u->rol_slug) {
                                            'admin_sisig' => 'bg-emerald-500',
                                            'editor_sisig' => 'bg-sky-500',
                                            'aprendiz_sisig' => 'bg-teal-500',
                                            default => 'bg-slate-400',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-black">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>
                                        <span>{{ $u->rol_nombre }}</span>
                                    </span>
                                </td>

                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-black">
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
                                                class="w-8 h-8 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-sena-green hover:border-emerald-200 hover:bg-emerald-50 transition-all flex items-center justify-center cursor-pointer shadow-xs"
                                                title="Editar información y rol del usuario">
                                            <i class="fas fa-pencil-alt text-xs"></i>
                                        </button>

                                        @if(Auth::id() !== $u->id)
                                            <button type="button" 
                                                    onclick="confirmToggleStatus({{ $u->id }}, '{{ addslashes($u->full_name ?: $u->name) }}', {{ $u->is_active ? 'true' : 'false' }})"
                                                    class="w-8 h-8 rounded-xl bg-white border border-slate-200 text-slate-400 {{ $u->is_active ? 'hover:text-amber-500 hover:border-amber-200 hover:bg-amber-50' : 'hover:text-emerald-600 hover:border-emerald-200 hover:bg-emerald-50' }} transition-all flex items-center justify-center cursor-pointer shadow-xs"
                                                    title="{{ $u->is_active ? 'Desactivar Cuenta' : 'Reactivar Cuenta' }}">
                                                <i class="fas {{ $u->is_active ? 'fa-user-slash' : 'fa-user-check' }} text-xs"></i>
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

    <!-- MODAL POLIMÓRFICO: CREAR Y ASIGNAR ROL A USUARIO (CON ESTILO INSTITUCIONAL SISIG) -->
    <div id="user-modal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-2xl w-full shadow-2xl border border-slate-200/90 animate-scale-in max-h-[92vh] overflow-hidden flex flex-col relative">
            
            <!-- 1. Cabecera del Modal con Estilo Glassmorphism Dark Mode del Sistema -->
            <div class="relative p-6 sm:p-7 text-white flex items-center justify-between overflow-hidden flex-shrink-0" 
                 style="background: linear-gradient(135deg, rgba(26, 43, 76, 0.98) 0%, rgba(20, 34, 60, 0.96) 100%); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border-bottom: 2px solid #33FF57;">
                
                <!-- Glows decorativos de fondo -->
                <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full bg-[#33FF57]/15 blur-2xl pointer-events-none"></div>
                <div class="absolute -bottom-10 -left-10 w-32 h-32 rounded-full bg-[#2E6F40]/25 blur-2xl pointer-events-none"></div>

                <div class="flex items-center gap-3.5 z-10">
                    <div id="user-modal-icon-wrapper" class="w-12 h-12 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center flex-shrink-0 shadow-inner overflow-hidden">
                        <img id="user-modal-avatar-img" src="" alt="Avatar" class="w-full h-full object-cover hidden">
                        <i id="user-modal-icon" class="fas fa-user-shield text-2xl text-[#33FF57]"></i>
                    </div>
                    <div>
                        <h3 id="user-modal-title" class="text-base sm:text-lg font-black text-white tracking-tight flex items-center gap-2">
                            <span>Asignación de Rol de Usuario</span>
                        </h3>
                        <p id="user-modal-subtitle" class="text-xs text-slate-300 mt-0.5">
                            Gestión de roles y permisos institucionales en SISIG
                        </p>
                    </div>
                </div>

                <button type="button" onclick="closeUserModal()" class="text-slate-300 hover:text-white p-2.5 rounded-xl hover:bg-white/10 transition-colors z-10 cursor-pointer" title="Cerrar">
                    <i class="fas fa-times text-base"></i>
                </button>
            </div>

            <!-- 2. Formulario del Modal con scroll suave y contenido organizado -->
            <form id="user-form" action="{{ route('sisig.admin.users.store') }}" method="POST" class="p-6 sm:p-7 overflow-y-auto space-y-5 custom-scrollbar">
                @csrf
                <div id="user-form-method"></div>

                <!-- SECCIÓN 1: DATOS INSTITUCIONALES DEL USUARIO (Bloqueados en modo edición) -->
                <div id="user-personal-fields-section" class="space-y-3.5">
                    <div class="flex items-center justify-between pb-1.5 border-b border-slate-100">
                        <span class="text-xs font-black uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                            <i class="fas fa-id-card text-slate-400"></i> Información de Identidad
                        </span>
                        <span id="user-fields-status-badge" class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-500 bg-slate-100 px-2.5 py-0.5 rounded-full">
                            <i class="fas fa-lock text-[9px] text-slate-400"></i> Solo lectura
                        </span>
                    </div>

                    <!-- Fila 1: Documento -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="text-xs font-bold text-slate-700 block mb-1 flex items-center justify-between">
                                <span>Tipo de Documento *</span>
                                <span class="lock-indicator text-[10px] text-slate-400 hidden"><i class="fas fa-lock text-[9px]"></i></span>
                            </label>
                            <div class="relative">
                                <select name="document_type" id="user-doctype" required class="w-full px-3.5 py-2.5 rounded-xl text-xs outline-none transition-all">
                                    <option value="Cédula de ciudadanía">Cédula de ciudadanía</option>
                                    <option value="Tarjeta de identidad">Tarjeta de identidad</option>
                                    <option value="Cédula de extranjería">Cédula de extranjería</option>
                                    <option value="PEP">Permiso Especial de Permanencia (PEP)</option>
                                    <option value="Pasaporte">Pasaporte</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-700 block mb-1 flex items-center justify-between">
                                <span>Número de Documento *</span>
                                <span class="lock-indicator text-[10px] text-slate-400 hidden"><i class="fas fa-lock text-[9px]"></i></span>
                            </label>
                            <div class="relative">
                                <input type="text" name="document_number" id="user-docnumber" required placeholder="Ej. 1000123456" class="w-full px-3.5 py-2.5 rounded-xl text-xs outline-none transition-all font-mono">
                            </div>
                        </div>
                    </div>

                    <!-- Fila 2: Nombres y Apellidos -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                        <div>
                            <label class="text-xs font-bold text-slate-700 block mb-1 flex items-center justify-between">
                                <span>Nombres *</span>
                                <span class="lock-indicator text-[10px] text-slate-400 hidden"><i class="fas fa-lock text-[9px]"></i></span>
                            </label>
                            <input type="text" name="first_name" id="user-firstname" required placeholder="Ej. Carlos Andrés" class="w-full px-3.5 py-2.5 rounded-xl text-xs outline-none transition-all">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-700 block mb-1 flex items-center justify-between">
                                <span>Primer Apellido *</span>
                                <span class="lock-indicator text-[10px] text-slate-400 hidden"><i class="fas fa-lock text-[9px]"></i></span>
                            </label>
                            <input type="text" name="first_last_name" id="user-firstlastname" required placeholder="Ej. Pérez" class="w-full px-3.5 py-2.5 rounded-xl text-xs outline-none transition-all">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-700 block mb-1 flex items-center justify-between">
                                <span>Segundo Apellido</span>
                                <span class="lock-indicator text-[10px] text-slate-400 hidden"><i class="fas fa-lock text-[9px]"></i></span>
                            </label>
                            <input type="text" name="second_last_name" id="user-secondlastname" placeholder="Ej. Gómez (Opcional)" class="w-full px-3.5 py-2.5 rounded-xl text-xs outline-none transition-all">
                        </div>
                    </div>

                    <!-- Fila 3: Correo y Alias -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="text-xs font-bold text-slate-700 block mb-1 flex items-center justify-between">
                                <span>Correo Electrónico *</span>
                                <span class="lock-indicator text-[10px] text-slate-400 hidden"><i class="fas fa-lock text-[9px]"></i></span>
                            </label>
                            <input type="email" name="email" id="user-email" required placeholder="usuario@sena.edu.co" class="w-full px-3.5 py-2.5 rounded-xl text-xs outline-none transition-all font-mono">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-700 block mb-1 flex items-center justify-between">
                                <span>Nombre de Usuario / Alias</span>
                                <span class="lock-indicator text-[10px] text-slate-400 hidden"><i class="fas fa-lock text-[9px]"></i></span>
                            </label>
                            <input type="text" name="nickname" id="user-nickname" placeholder="Ej. cperez (Opcional)" class="w-full px-3.5 py-2.5 rounded-xl text-xs outline-none transition-all font-mono">
                        </div>
                    </div>

                    <!-- Fila 4: Contraseña (Solo en creación o bloqueada con aviso en edición) -->
                    <div id="user-password-container">
                        <label id="user-password-label" class="text-xs font-bold text-slate-700 block mb-1 flex items-center justify-between">
                            <span>Contraseña de Acceso *</span>
                            <span class="lock-indicator text-[10px] text-slate-400 hidden"><i class="fas fa-lock text-[9px]"></i> Inmutable</span>
                        </label>
                        <input type="password" name="password" id="user-password" minlength="6" placeholder="Mínimo 6 caracteres" class="w-full px-3.5 py-2.5 rounded-xl text-xs outline-none transition-all">
                    </div>
                </div>

                <!-- SECCIÓN 2: ASIGNACIÓN DE ROL EN SISIG (Única sección editable y destacada con colores del sistema) -->
                <div class="p-4 sm:p-5 rounded-2xl bg-emerald-50/50 border-2 border-[#2E6F40]/30 space-y-4">
                    <div class="pb-2 border-b border-emerald-200/60">
                        <span class="text-xs font-black uppercase tracking-wider text-[#2E6F40] flex items-center gap-1.5">
                            <i class="fas fa-user-tag text-sena-green"></i> Asignación de Rol en SISIG
                        </span>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-800 block mb-1.5 flex items-center justify-between">
                            <span>Rol Autorizado *</span>
                            <span class="text-[11px] text-slate-500 font-normal">Selecciona el rol que tendrá el usuario</span>
                        </label>
                        <select name="role_slug" id="user-roleselect" onchange="toggleFichaField(this.value); toggleEditorPermissions(this.value)" 
                                class="w-full px-3.5 py-2.5 rounded-xl bg-white border-2 border-[#2E6F40]/40 focus:border-[#2E6F40] focus:ring-2 focus:ring-[#2E6F40]/20 outline-none text-xs font-bold text-slate-800 transition-all shadow-xs cursor-pointer">
                            <option value="aprendiz_sisig">Aprendiz SISIG (Inducción y Evaluaciones)</option>
                            <option value="editor_sisig">Editor SISIG (Gestión de Módulos y Quices)</option>
                            <option value="admin_sisig">Administrador SISIG (Control total)</option>
                            <option value="ninguno">Sin Rol en SISIG</option>
                        </select>
                    </div>

                    <!-- Permisos de Edición de Módulos (Solo si es Editor SISIG) -->
                    <div id="user-editor-permissions-container" class="hidden p-4 bg-white rounded-xl border border-emerald-200/80 space-y-3">
                        <h4 class="text-xs font-bold text-slate-800 border-b border-slate-100 pb-2 flex items-center justify-between">
                            <span class="flex items-center gap-1.5">
                                <i class="fas fa-cubes text-[#2E6F40]"></i> Módulos y Quices Asignados
                            </span>
                            <span class="text-[10px] text-slate-400 font-normal">Marca los contenidos a los que tendrá acceso</span>
                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Módulos -->
                            <div>
                                <span class="text-[11px] font-extrabold text-slate-600 uppercase tracking-wider block mb-2">Módulos Asignados</span>
                                <div class="space-y-2 max-h-36 overflow-y-auto pr-2 custom-scrollbar">
                                    @forelse($modulosActivos as $modulo)
                                        <label class="flex items-center gap-2 text-xs text-slate-700 cursor-pointer hover:bg-emerald-50/50 p-1.5 rounded-lg transition-colors">
                                            <input type="checkbox" name="modulos[]" value="{{ $modulo->id }}" class="editor-modulo-cb text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                                            <span class="font-medium">{{ $modulo->titulo }}</span>
                                        </label>
                                    @empty
                                        <p class="text-xs text-slate-400 italic">No hay módulos disponibles.</p>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Quices -->
                            <div>
                                <span class="text-[11px] font-extrabold text-slate-600 uppercase tracking-wider block mb-2">Quices Asignados</span>
                                <div class="space-y-2 max-h-36 overflow-y-auto pr-2 custom-scrollbar">
                                    @forelse($quicesActivos as $quiz)
                                        <label class="flex items-center gap-2 text-xs text-slate-700 cursor-pointer hover:bg-emerald-50/50 p-1.5 rounded-lg transition-colors">
                                            <input type="checkbox" name="quices[]" value="{{ $quiz->id_quiz }}" class="editor-quiz-cb text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                                            <span class="font-medium">{{ $quiz->titulo }}</span>
                                        </label>
                                    @empty
                                        <p class="text-xs text-slate-400 italic">No hay quices disponibles.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Campo Ficha si es Aprendiz -->
                    <div id="user-ficha-container" class="p-3.5 bg-white rounded-xl border border-emerald-200/80">
                        <label class="text-xs font-bold text-slate-700 block mb-1 flex items-center justify-between">
                            <span>Número de Ficha de Formación</span>
                            <span id="user-ficha-badge" class="text-[10px] text-slate-400 flex items-center gap-1">
                                <i class="fas fa-lock text-[9px]"></i> Institucional
                            </span>
                        </label>
                        <input type="text" name="ficha" id="user-ficha" placeholder="Ej. 3288036" class="w-full px-3.5 py-2 rounded-xl border text-xs outline-none transition-all font-mono">
                    </div>
                </div>

                <!-- Footer del Modal -->
                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-3 flex-shrink-0">
                    <button type="button" onclick="closeUserModal()" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-colors cursor-pointer">
                        Cancelar
                    </button>
                    <button type="submit" id="user-submit-btn" class="px-6 py-2.5 rounded-2xl text-xs font-black text-white bg-[#2E6F40] hover:bg-[#235833] shadow-md hover:shadow-lg hover:scale-[1.01] active:scale-[0.99] transition-all flex items-center gap-2 cursor-pointer">
                        <i class="fas fa-save"></i>
                        <span>Guardar Cambios</span>
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

    <!-- Modal Informativo: Usuario No Eliminable (Tiene actividad registrada) -->
    <div id="modal-usuario-no-eliminable" class="fixed inset-0 z-[150] hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl border border-slate-200 animate-scale-in relative text-center">
            
            <button type="button" onclick="cerrarModalNoEliminable()" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 p-2 rounded-xl hover:bg-slate-100 transition-colors">
                <i class="fas fa-times text-sm"></i>
            </button>

            <!-- Ícono sin recuadro -->
            <div class="text-amber-500 text-3xl mx-auto mb-3">
                <i class="fas fa-shield-alt"></i>
            </div>

            <h3 class="text-xl font-black text-slate-800 tracking-tight">No se puede eliminar</h3>
            
            <div class="mt-4 p-3.5 rounded-2xl bg-slate-50 border border-slate-200/90 text-left">
                <div class="flex items-center gap-1.5 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">
                    <i class="fas fa-user text-amber-500"></i>
                    <span>Usuario</span>
                </div>
                <p id="no-eliminable-usuario-nombre" class="text-xs font-bold text-slate-700 leading-snug"></p>
            </div>

            <p class="text-xs text-slate-500 mt-3 max-w-xs mx-auto leading-relaxed">
                Este usuario cuenta con actividad formativa o evaluaciones registradas. Para preservar la trazabilidad institucional, el sistema no permite borrarlo.
            </p>

            <div class="mt-3 flex items-center justify-center gap-1.5 text-[11px] font-medium text-amber-600">
                <i class="fas fa-info-circle text-xs"></i>
                <span>Puedes desactivar su cuenta en su lugar para restringir el acceso.</span>
            </div>

            <div class="mt-6">
                <button type="button" onclick="cerrarModalNoEliminable()" class="w-full py-2.5 rounded-xl font-bold text-xs text-slate-700 bg-slate-100 hover:bg-slate-200 transition-all cursor-pointer">
                    Entendido
                </button>
            </div>
        </div>
    </div>

    <!-- SCRIPTS DE INTERACCIÓN REACTIVA -->
    <script>
        let currentRoleFilter = 'todos';
        let currentStatusFilter = '{{ $status ?? "activos" }}';
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

                let matchesStatus = currentStatusFilter === 'todos' 
                    || ((currentStatusFilter === 'activos' || currentStatusFilter === 'activo') && status === 'activo')
                    || ((currentStatusFilter === 'inactivos' || currentStatusFilter === 'inactivo') && status === 'inactivo');

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
                    ? 'status-filter-btn px-3 py-1 rounded-xl font-bold transition-all bg-slate-900 text-white shadow-sm'
                    : 'status-filter-btn px-3 py-1 rounded-xl font-bold transition-all text-slate-600 hover:bg-slate-200';
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

        function toggleEditorPermissions(role) {
            document.getElementById('user-editor-permissions-container').classList.toggle('hidden', role !== 'editor_sisig');
        }

        let userOriginalSnapshot = null;

        function setFieldsEditability(isEdit) {
            const personalInputs = [
                document.getElementById('user-doctype'),
                document.getElementById('user-docnumber'),
                document.getElementById('user-firstname'),
                document.getElementById('user-firstlastname'),
                document.getElementById('user-secondlastname'),
                document.getElementById('user-email'),
                document.getElementById('user-nickname'),
                document.getElementById('user-ficha')
            ];

            const pwd = document.getElementById('user-password');
            const pwdContainer = document.getElementById('user-password-container');
            const statusBadge = document.getElementById('user-fields-status-badge');
            const lockIndicators = document.querySelectorAll('.lock-indicator');

            if (isEdit) {
                // Modo Edición: Bloquear datos personales e institucionales (Solo Lectura)
                personalInputs.forEach(input => {
                    if (input) {
                        input.readOnly = true;
                        input.className = 'w-full px-3.5 py-2.5 rounded-xl bg-slate-100/90 border border-slate-200 text-slate-500 font-semibold text-xs cursor-not-allowed select-none transition-all';
                    }
                });

                const docType = document.getElementById('user-doctype');
                if (docType) {
                    docType.style.pointerEvents = 'none';
                    docType.tabIndex = -1;
                }

                // Ocultar campo de contraseña en edición (contraseña institucional inmutable)
                if (pwdContainer) pwdContainer.classList.add('hidden');
                if (pwd) {
                    pwd.required = false;
                    pwd.value = '';
                }

                if (statusBadge) {
                    statusBadge.innerHTML = '<i class="fas fa-lock text-[9px] text-slate-400"></i> Solo lectura';
                    statusBadge.className = 'inline-flex items-center gap-1 text-[10px] font-bold text-slate-500 bg-slate-100 px-2.5 py-0.5 rounded-full';
                }

                lockIndicators.forEach(el => el.classList.remove('hidden'));

            } else {
                // Modo Creación: Desbloquear todos los campos
                personalInputs.forEach(input => {
                    if (input) {
                        input.readOnly = false;
                        input.className = 'w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 text-slate-800 text-xs outline-none transition-all';
                    }
                });

                const docType = document.getElementById('user-doctype');
                if (docType) {
                    docType.style.pointerEvents = 'auto';
                    docType.tabIndex = 0;
                }

                if (pwdContainer) pwdContainer.classList.remove('hidden');
                if (pwd) {
                    pwd.required = true;
                    pwd.readOnly = false;
                    pwd.value = '';
                    pwd.placeholder = 'Mínimo 6 caracteres';
                    pwd.className = 'w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 text-slate-800 text-xs outline-none transition-all';
                }

                if (statusBadge) {
                    statusBadge.innerHTML = '<i class="fas fa-pencil-alt text-[9px] text-[#2E6F40]"></i> Nuevo';
                    statusBadge.className = 'inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full';
                }

                lockIndicators.forEach(el => el.classList.add('hidden'));
            }
        }

        function getUserFormData() {
            const modulos = [];
            document.querySelectorAll('.editor-modulo-cb:checked').forEach(cb => modulos.push(cb.value));
            const quices = [];
            document.querySelectorAll('.editor-quiz-cb:checked').forEach(cb => quices.push(cb.value));

            return {
                role_slug: document.getElementById('user-roleselect').value,
                ficha: document.getElementById('user-ficha').value.trim(),
                modulos: modulos.sort(),
                quices: quices.sort()
            };
        }

        function openCreateModal() {
            form.reset();
            form.action = "{{ route('sisig.admin.users.store') }}";
            methodContainer.innerHTML = '';
            userOriginalSnapshot = null;

            setFieldsEditability(false);

            document.getElementById('user-modal-title').innerHTML = '<i class="fas fa-user-plus text-[#33FF57] text-lg"></i> <span>Registrar Nuevo Usuario</span>';
            document.getElementById('user-modal-subtitle').textContent = 'Creación de cuenta y asignación de rol inicial en SISIG';
            document.getElementById('user-modal-avatar-img').classList.add('hidden');
            const createIcon = document.getElementById('user-modal-icon');
            createIcon.className = 'fas fa-user-plus text-2xl text-[#33FF57]';
            createIcon.classList.remove('hidden');

            document.getElementById('user-password-label').innerHTML = '<span>Contraseña de Acceso *</span>';

            document.getElementById('user-roleselect').value = 'aprendiz_sisig';
            toggleFichaField('aprendiz_sisig');
            toggleEditorPermissions('aprendiz_sisig');
            
            // Desmarcar todos los checkboxes
            document.querySelectorAll('.editor-modulo-cb, .editor-quiz-cb').forEach(cb => cb.checked = false);

            document.getElementById('user-submit-btn').innerHTML = '<i class="fas fa-user-plus"></i> <span>Crear Usuario</span>';

            modal.classList.remove('hidden');
            if (window.lockBodyScroll) window.lockBodyScroll(true);
        }

        function openEditModal(userId) {
            fetch(`{{ url('sisig/admin/usuarios') }}/${userId}/detalle`)
                .then(res => res.json())
                .then(data => {
                    form.reset();
                    form.action = `{{ url('sisig/admin/usuarios') }}/${userId}`;
                    methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';

                    setFieldsEditability(true);

                    document.getElementById('user-modal-title').innerHTML = '<i class="fas fa-user-tag text-[#33FF57] text-lg"></i> <span>Asignación de Rol de Usuario</span>';
                    document.getElementById('user-modal-subtitle').textContent = `Editando rol de: ${data.name} (${data.email})`;
                    
                    const avatarImg = document.getElementById('user-modal-avatar-img');
                    const modalIcon = document.getElementById('user-modal-icon');
                    if (data.avatar_url) {
                        avatarImg.src = data.avatar_url;
                        avatarImg.classList.remove('hidden');
                        modalIcon.classList.add('hidden');
                    } else {
                        avatarImg.classList.add('hidden');
                        modalIcon.className = 'fas fa-user-tag text-2xl text-[#33FF57]';
                        modalIcon.classList.remove('hidden');
                    }

                    document.getElementById('user-doctype').value = data.document_type || 'Cédula de ciudadanía';
                    document.getElementById('user-docnumber').value = data.document_number || '';
                    document.getElementById('user-firstname').value = data.first_name || '';
                    document.getElementById('user-firstlastname').value = data.first_last_name || '';
                    document.getElementById('user-secondlastname').value = data.second_last_name || '';
                    document.getElementById('user-email').value = data.email || '';
                    document.getElementById('user-nickname').value = data.nickname || '';

                    const role = data.rol_slug || 'ninguno';
                    document.getElementById('user-roleselect').value = role;
                    document.getElementById('user-ficha').value = data.ficha || '';
                    toggleFichaField(role);
                    toggleEditorPermissions(role);

                    // Pre-marcar checkboxes de permisos
                    document.querySelectorAll('.editor-modulo-cb').forEach(cb => {
                        cb.checked = data.modulos_editables && data.modulos_editables.includes(parseInt(cb.value));
                    });
                    document.querySelectorAll('.editor-quiz-cb').forEach(cb => {
                        cb.checked = data.quices_editables && data.quices_editables.includes(parseInt(cb.value));
                    });

                    document.getElementById('user-submit-btn').innerHTML = '<i class="fas fa-save"></i> <span>Guardar Rol Asignado</span>';
                    userOriginalSnapshot = JSON.stringify(getUserFormData());
                    modal.classList.remove('hidden');
                    if (window.lockBodyScroll) window.lockBodyScroll(true);
                })
                .catch(err => {
                    console.error(err);
                    if (window.mostrarAlertaBanner) {
                        window.mostrarAlertaBanner('No se pudo cargar la información del usuario para edición.', 'error');
                    }
                });
        }

        form.addEventListener('submit', function(e) {
            if (userOriginalSnapshot) {
                const currentData = JSON.stringify(getUserFormData());
                if (currentData === userOriginalSnapshot) {
                    e.preventDefault();
                    closeUserModal();
                    if (window.mostrarAlertaBanner) {
                        window.mostrarAlertaBanner('No se detectaron modificaciones en los datos del usuario.', 'info');
                    }
                    return false;
                }
            }
        });

        function closeUserModal() {
            modal.classList.add('hidden');
            if (window.lockBodyScroll) window.lockBodyScroll(false);
        }

        // 3. Confirmación para Desactivar / Activar Usuario (Asíncrono AJAX en tiempo real)
        async function ejecutarToggleStatusAjax(userId, userName) {
            const token = document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}';
            try {
                const response = await fetch(`{{ url('sisig/admin/usuarios') }}/${userId}/estado`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': token
                    }
                });
                const data = await response.json();
                if (data.success) {
                    const row = document.querySelector(`button[onclick*="confirmToggleStatus(${userId},"]`)?.closest('tr');
                    if (row) {
                        row.dataset.status = data.is_active ? 'activo' : 'inactivo';
                        // Actualizar columna de estado
                        const statusCell = row.children[4];
                        if (statusCell) {
                            statusCell.innerHTML = `
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-black">
                                    <span class="w-1.5 h-1.5 rounded-full ${data.is_active ? 'bg-emerald-500' : 'bg-rose-500'}"></span>
                                    <span>${data.is_active ? 'Activo' : 'Inactivo'}</span>
                                </span>
                            `;
                        }
                        // Actualizar botón de toggle
                        const btnToggle = row.querySelector(`button[onclick*="confirmToggleStatus(${userId},"]`);
                        if (btnToggle) {
                            btnToggle.setAttribute('onclick', `confirmToggleStatus(${userId}, '${userName.replace(/'/g, "\\'")}', ${data.is_active ? 'true' : 'false'})`);
                            btnToggle.title = data.is_active ? 'Desactivar Cuenta' : 'Reactivar Cuenta';
                            btnToggle.innerHTML = `<i class="fas ${data.is_active ? 'fa-user-slash' : 'fa-user-check'} text-xs"></i>`;
                            if (data.is_active) {
                                btnToggle.className = 'w-8 h-8 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-amber-500 hover:border-amber-200 hover:bg-amber-50 transition-all flex items-center justify-center cursor-pointer shadow-xs';
                                row.classList.remove('opacity-65', 'bg-slate-50/40');
                            } else {
                                btnToggle.className = 'w-8 h-8 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-emerald-600 hover:border-emerald-200 hover:bg-emerald-50 transition-all flex items-center justify-center cursor-pointer shadow-xs';
                                row.classList.add('opacity-65', 'bg-slate-50/40');
                            }
                        }
                    }
                    if (window.mostrarAlertaBanner) {
                        window.mostrarAlertaBanner(data.message, 'success');
                    }
                    applyFilters();
                } else {
                    if (window.mostrarAlertaBanner) {
                        window.mostrarAlertaBanner(data.message || 'Error al actualizar el estado del usuario.', 'danger');
                    }
                }
            } catch (err) {
                console.error(err);
                if (window.mostrarAlertaBanner) {
                    window.mostrarAlertaBanner('Error de conexión al actualizar el usuario.', 'danger');
                }
            } finally {
                window.cerrarModalConfirmacion();
            }
        }

        function confirmToggleStatus(userId, userName, isActive) {
            if (isActive) {
                window.abrirModalConfirmacion({
                    tipo: 'warning',
                    titulo: '¿Desactivar cuenta?',
                    descripcion: 'El usuario perderá el acceso temporal al sistema SISIG hasta que sea reactivado.',
                    item: userName,
                    itemTag: 'Usuario',
                    btnTexto: 'Sí, desactivar',
                    btnIcon: 'fa-user-slash',
                    onConfirm: () => ejecutarToggleStatusAjax(userId, userName)
                });
            } else {
                window.abrirModalConfirmacion({
                    tipo: 'success',
                    titulo: '¿Reactivar cuenta?',
                    descripcion: 'El usuario recuperará el acceso inmediato a la plataforma SISIG.',
                    item: userName,
                    itemTag: 'Usuario',
                    btnTexto: 'Sí, reactivar',
                    btnIcon: 'fa-user-check',
                    onConfirm: () => ejecutarToggleStatusAjax(userId, userName)
                });
            }
        }

        // 4. Confirmación para Eliminar Usuario Definitivamente (Asíncrono AJAX en tiempo real)
        async function ejecutarDeleteUserAjax(userId, userName) {
            const token = document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}';
            try {
                const response = await fetch(`{{ url('sisig/admin/usuarios') }}/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': token
                    }
                });
                const data = await response.json();
                if (data.success) {
                    const row = document.querySelector(`button[onclick*="confirmDeleteUser(${userId},"]`)?.closest('tr');
                    if (row) {
                        row.style.transition = 'all 0.3s ease';
                        row.style.opacity = '0';
                        row.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            row.remove();
                            applyFilters();
                        }, 300);
                    }
                    if (window.mostrarAlertaBanner) {
                        window.mostrarAlertaBanner(data.message, 'success');
                    }
                } else {
                    if (window.mostrarAlertaBanner) {
                        window.mostrarAlertaBanner(data.message || 'No fue posible eliminar el usuario.', 'danger');
                    }
                }
            } catch (err) {
                console.error(err);
                if (window.mostrarAlertaBanner) {
                    window.mostrarAlertaBanner('Error de conexión al eliminar el usuario.', 'danger');
                }
            } finally {
                window.cerrarModalConfirmacion();
            }
        }

        function confirmDeleteUser(userId, userName, hasActivity) {
            if (hasActivity) {
                document.getElementById('no-eliminable-usuario-nombre').textContent = userName;
                document.getElementById('modal-usuario-no-eliminable').classList.remove('hidden');
                if (window.lockBodyScroll) window.lockBodyScroll(true);
                return;
            }

            window.abrirModalConfirmacion({
                tipo: 'danger',
                titulo: '¿Eliminar usuario permanentemente?',
                descripcion: 'Esta acción no se puede deshacer y retirará la cuenta del usuario de la plataforma SISIG.',
                item: `"${userName}"`,
                itemTag: 'Usuario seleccionado',
                advertencia: 'Este usuario no tiene actividad registrada y será borrado del sistema.',
                btnTexto: 'Sí, eliminar',
                btnIcon: 'fa-trash-alt',
                onConfirm: () => ejecutarDeleteUserAjax(userId, userName)
            });
        }

        function cerrarModalNoEliminable() {
            document.getElementById('modal-usuario-no-eliminable').classList.add('hidden');
            if (window.lockBodyScroll) window.lockBodyScroll(false);
        }

        // Cerrar modal al hacer clic en el backdrop oscuro
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeUserModal();
            }
        });

        const modalNoEliminable = document.getElementById('modal-usuario-no-eliminable');
        if (modalNoEliminable) {
            modalNoEliminable.addEventListener('click', (e) => {
                if (e.target === modalNoEliminable) {
                    cerrarModalNoEliminable();
                }
            });
        }

        // Cerrar modal con Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                if (!modal.classList.contains('hidden')) {
                    closeUserModal();
                }
                cerrarModalNoEliminable();
            }
        });
    </script>
</x-sisig::layouts.admin>
