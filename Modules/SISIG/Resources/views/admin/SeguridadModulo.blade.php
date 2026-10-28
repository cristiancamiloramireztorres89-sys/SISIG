<x-sisig::layouts.admin title="Seguridad y Accesos SISIG">
    <div class="space-y-6 sm:space-y-8 animate-fade-in pb-12">

        <!-- 1. Encabezado de la Sección (Estilo institucional limpio) -->
        <div class="pb-5 border-b border-slate-200">
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                Seguridad y Accesos SISIG
            </h1>
            <p class="text-xs text-slate-500 font-medium mt-1">
                Controla la seguridad del sistema – registros de actividad, sesiones activas, restablecimiento de contraseñas y bloqueo de cuentas.
            </p>
        </div>

        <!-- Alertas de Sesión -->
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between gap-3 text-xs font-bold shadow-sm">
                <div class="flex items-center gap-2.5">
                    <i class="fas fa-check-circle text-emerald-600 text-sm"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center justify-between gap-3 text-xs font-bold shadow-sm">
                <div class="flex items-center gap-2.5">
                    <i class="fas fa-exclamation-circle text-rose-600 text-sm"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- 2. Tarjetas de Métricas (KPIs con barra superior idéntica a Gestión de Usuarios) -->
        @php
            $kpis = [
                [
                    'title' => 'Sesiones Activas Ahora',
                    'value' => $kpiSesionesActivas,
                    'sub'   => 'usuarios en línea',
                    'icon'  => 'fa-users text-emerald-600',
                    'bar'   => 'from-emerald-500 to-teal-600',
                ],
                [
                    'title' => 'Acciones Registradas Hoy',
                    'value' => $kpiAccionesHoy,
                    'sub'   => 'eventos en sistema',
                    'icon'  => 'fa-clipboard-list text-sky-600',
                    'bar'   => 'from-sky-500 to-blue-600',
                ],
                [
                    'title' => 'Contraseñas Restablecidas',
                    'value' => $kpiPasswordsRestablecidas,
                    'sub'   => 'credenciales',
                    'icon'  => 'fa-key text-amber-500',
                    'bar'   => 'from-amber-400 to-amber-600',
                ],
                [
                    'title' => 'Cuentas Bloqueadas',
                    'value' => $kpiCuentasBloqueadas,
                    'sub'   => 'inactivas',
                    'icon'  => 'fa-user-slash text-rose-600',
                    'bar'   => 'from-rose-500 to-rose-700',
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
                            <span>Seguridad SISIG</span>
                            <span class="text-xs font-bold text-[#2E6F40]">Auditoría</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- ============================================================== -->
        <!-- 3. TABLA 1: REGISTRO DE LOGS (HISTORIAL DE ACTIVIDADES)        -->
        <!-- ============================================================== -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-5">
            <!-- Encabezado de la Tarjeta -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-800">
                        Historial de Actividades (Registro de Log)
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Acciones por usuario con fecha, hora, módulo e IP en las últimas 24 horas</p>
                </div>
                <span class="text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1 rounded-xl self-start sm:self-auto">
                    Últimas 24 horas
                </span>
            </div>

            <!-- Filtros Rápidos Estilo SISIG -->
            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <form method="GET" action="{{ route('sisig.admin.seguridad.index') }}" class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                    <!-- Búsqueda por Usuario -->
                    <div class="relative flex-1 sm:w-64">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="usuario" value="{{ $filtroUsuario }}" 
                               placeholder="Buscar por usuario..." 
                               class="w-full pl-10 pr-4 py-2 rounded-xl bg-white border border-slate-200 focus:border-sena-green focus:ring-2 focus:ring-sena-green/20 outline-none text-xs transition-all text-slate-800">
                    </div>

                    <!-- Filtro por Acción -->
                    <div class="flex items-center gap-1.5 bg-white p-1 rounded-xl border border-slate-200 text-xs">
                        <span class="text-slate-400 font-bold px-2 hidden sm:inline">Acción:</span>
                        <a href="{{ route('sisig.admin.seguridad.index', ['usuario' => $filtroUsuario, 'accion' => 'todas']) }}" 
                           class="px-3 py-1 rounded-lg font-bold transition-all {{ empty($filtroAccion) || $filtroAccion === 'todas' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                            Todas
                        </a>
                        <a href="{{ route('sisig.admin.seguridad.index', ['usuario' => $filtroUsuario, 'accion' => 'inicio_sesion']) }}" 
                           class="px-3 py-1 rounded-lg font-bold transition-all {{ $filtroAccion === 'inicio_sesion' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                            Inicio sesión
                        </a>
                        <a href="{{ route('sisig.admin.seguridad.index', ['usuario' => $filtroUsuario, 'accion' => 'edicion']) }}" 
                           class="px-3 py-1 rounded-lg font-bold transition-all {{ $filtroAccion === 'edicion' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                            Edición
                        </a>
                        <a href="{{ route('sisig.admin.seguridad.index', ['usuario' => $filtroUsuario, 'accion' => 'restablecimiento']) }}" 
                           class="px-3 py-1 rounded-lg font-bold transition-all {{ $filtroAccion === 'restablecimiento' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                            Restablecimiento
                        </a>
                        <a href="{{ route('sisig.admin.seguridad.index', ['usuario' => $filtroUsuario, 'accion' => 'bloqueo']) }}" 
                           class="px-3 py-1 rounded-lg font-bold transition-all {{ $filtroAccion === 'bloqueo' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                            Bloqueo
                        </a>
                    </div>

                    <button type="submit" class="px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-sena-green hover:bg-emerald-600 shadow-sena transition-all">
                        <i class="fas fa-filter mr-1"></i> Filtrar
                    </button>

                    @if(!empty($filtroUsuario) || (!empty($filtroAccion) && $filtroAccion !== 'todas'))
                        <a href="{{ route('sisig.admin.seguridad.index') }}" 
                           class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 hover:bg-slate-100 transition-all inline-flex items-center gap-1.5">
                            <i class="fas fa-undo text-[10px]"></i> Limpiar
                        </a>
                    @endif
                </form>
            </div>

            <!-- Tabla de Registros -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                            <th class="py-3.5 px-4">Fecha y Hora</th>
                            <th class="py-3.5 px-4">Usuario</th>
                            <th class="py-3.5 px-4">Rol en SISIG</th>
                            <th class="py-3.5 px-4">Acción</th>
                            <th class="py-3.5 px-4">Módulo</th>
                            <th class="py-3.5 px-4">IP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($actividades as $act)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 px-4 text-slate-500 whitespace-nowrap">{{ $act->fecha_hora }}</td>
                                <td class="py-3.5 px-4 font-bold text-slate-800 whitespace-nowrap">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-lg bg-gradient-to-tr from-slate-700 to-slate-800 text-white font-black text-[10px] flex items-center justify-center flex-shrink-0 shadow-sm">
                                            {{ strtoupper(substr($act->usuario, 0, 2)) }}
                                        </div>
                                        <span>{{ $act->usuario }}</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600">
                                        <span class="w-1.5 h-1.5 rounded-full {{ strtolower($act->rol) === 'admin' ? 'bg-emerald-500' : (strtolower($act->rol) === 'editor' ? 'bg-sky-500' : 'bg-teal-500') }}"></span>
                                        {{ $act->rol }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    @if($act->accion_color === 'emerald')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100/90 text-emerald-800">
                                            {{ $act->accion }}
                                        </span>
                                    @elseif($act->accion_color === 'sky')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-sky-100/90 text-sky-800">
                                            {{ $act->accion }}
                                        </span>
                                    @elseif($act->accion_color === 'amber')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100/90 text-amber-800">
                                            {{ $act->accion }}
                                        </span>
                                    @elseif($act->accion_color === 'rose')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-100/90 text-rose-800">
                                            {{ $act->accion }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700">
                                            {{ $act->accion }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-slate-500 whitespace-nowrap">{{ $act->modulo }}</td>
                                <td class="py-3.5 px-4 font-mono text-[11px] text-slate-400 whitespace-nowrap">{{ $act->ip }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center text-slate-400 font-medium">
                                    <i class="fas fa-inbox text-3xl mb-2 text-slate-300 block"></i>
                                    No se encontraron registros de auditoría en las últimas 24 horas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- 4. TABLA 2: CONTROL DE SESIONES ACTIVAS EN TIEMPO REAL         -->
        <!-- ============================================================== -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-5">
            <!-- Encabezado de la Tarjeta -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-800">
                        Usuarios Conectados en Tiempo Real
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Control de sesiones vivas en la plataforma y desconexión remota</p>
                </div>
            </div>

            <!-- Tabla de Sesiones Activas -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                            <th class="py-3.5 px-4">Usuario</th>
                            <th class="py-3.5 px-4">Rol</th>
                            <th class="py-3.5 px-4">Último Acceso</th>
                            <th class="py-3.5 px-4">IP</th>
                            <th class="py-3.5 px-4">Dispositivo</th>
                            <th class="py-3.5 px-4">Estado</th>
                            <th class="py-3.5 px-4 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($sesionesActivas as $ses)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-slate-700 to-slate-800 text-white font-black text-xs flex items-center justify-center flex-shrink-0 shadow-sm border border-slate-200/50">
                                            {{ strtoupper(substr($ses->nombre, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800">{{ $ses->nombre }}</div>
                                            <div class="text-[11px] text-slate-400">{{ $ses->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600">
                                        <span class="w-1.5 h-1.5 rounded-full {{ strtolower($ses->rol) === 'admin' ? 'bg-emerald-500' : (strtolower($ses->rol) === 'editor' ? 'bg-sky-500' : 'bg-teal-500') }}"></span>
                                        {{ $ses->rol }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-slate-500 whitespace-nowrap">{{ $ses->ultimo_acceso }}</td>
                                <td class="py-3.5 px-4 font-mono text-[11px] text-slate-400 whitespace-nowrap">{{ $ses->ip }}</td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 text-slate-600 font-medium">
                                        <i class="{{ $ses->dispositivo_icono }} text-slate-400 text-xs"></i>
                                        <span>{{ $ses->dispositivo_texto }}</span>
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        <span>{{ $ses->estado }}</span>
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <button type="button" 
                                            onclick="confirmarCierreSesion('{{ $ses->session_id }}', '{{ addslashes($ses->nombre) }}')"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 hover:text-rose-700 bg-slate-100 hover:bg-rose-50 border border-slate-200/80 hover:border-rose-200 transition-all">
                                        <i class="fas fa-sign-out-alt text-xs text-slate-500"></i>
                                        <span>Cerrar sesión</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-10 text-center text-slate-400 font-medium">
                                    <i class="fas fa-shield-virus text-3xl mb-2 text-slate-300 block"></i>
                                    No hay sesiones activas registradas en este momento.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Formulario para Cerrar Sesión -->
    <form id="formCerrarSesion" method="POST" action="" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <!-- Scripts de Interacción -->
    <script>
        // Cerrar Sesión en tiempo real
        function confirmarCierreSesion(sessionId, nombreUsuario) {
            if (window.abrirModalConfirmacion) {
                window.abrirModalConfirmacion({
                    tipo: 'danger',
                    titulo: '¿Cerrar sesión activa?',
                    descripcion: `Se forzará el cierre de la sesión actual de ${nombreUsuario}. Tendrá que volver a autenticarse.`,
                    btnTexto: 'Sí, cerrar sesión',
                    btnIcon: 'fa-sign-out-alt',
                    onConfirm: () => {
                        const form = document.getElementById('formCerrarSesion');
                        form.action = `{{ url('sisig/admin/seguridad/sesiones') }}/${sessionId}`;
                        form.submit();
                    }
                });
            } else if (confirm(`¿Estás seguro de cerrar la sesión activa de ${nombreUsuario}?`)) {
                const form = document.getElementById('formCerrarSesion');
                form.action = `{{ url('sisig/admin/seguridad/sesiones') }}/${sessionId}`;
                form.submit();
            }
        }
    </script>
</x-sisig::layouts.admin>
