<x-sisig::layouts.admin title="Dashboard del Aprendiz">
    <div class="space-y-8 w-full max-w-[1600px] mx-auto pb-10">

        <!-- 1. Banner Principal de Bienvenida del Aprendiz -->
        <x-sisig::banner 
            badge="Portal del Aprendiz SISIG"
            badgeIcon="fas fa-graduation-cap"
            title="¡Hola, <span class='text-transparent bg-clip-text bg-gradient-to-r from-white via-slate-100 to-emerald-300'>{{ auth()->user()->full_name ?? auth()->user()->name }}</span>!"
            subtitle="Bienvenido a tu panel de control de la inducción formativa en el Sistema Integrado de Gestión (SIG) del Centro Agroindustrial 'La Angostura'. Aquí puedes consultar el resumen de tu avance, estados de aprobación e historial de evaluaciones.">
            <x-slot:action>
                <div class="flex items-center gap-2.5 bg-white/10 backdrop-blur-md px-4 py-2.5 rounded-2xl border border-white/15 text-white shadow-sm">
                    <i class="far fa-calendar-check text-sena-green text-lg"></i>
                    <span class="text-xs sm:text-sm font-bold text-slate-100 capitalize">
                        {{ now()->isoFormat('dddd, D [de] MMMM') }}
                    </span>
                </div>
            </x-slot:action>
        </x-sisig::banner>

        <!-- 2. Tarjetas de Métricas Clave (KPIs Resumen Reutilizables) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <x-sisig::stat-card 
                title="Avance de Inducción"
                value="{{ $progresoGeneral }}%"
                icon="fas fa-tasks"
                color="emerald">
                <x-slot:footer>
                    <span class="text-slate-500 font-medium">Contenidos revisados</span>
                    <span class="text-xs font-bold {{ $progresoGeneral >= 100 ? 'text-emerald-600' : 'text-slate-700' }}">
                        {{ $progresoGeneral >= 100 ? 'Al día' : 'En curso' }}
                    </span>
                </x-slot:footer>
            </x-sisig::stat-card>

            <x-sisig::stat-card 
                title="Módulos Temáticos"
                value="{{ $seccionesCompletadas }}"
                icon="fas fa-layer-group"
                color="sky">
                <x-slot:footer>
                    <span class="text-slate-500 font-medium">Módulos habilitados</span>
                    <span class="text-xs font-black text-sky-600">
                        {{ $totalSecciones > 0 && $seccionesCompletadas >= $totalSecciones ? 'Finalizados' : 'En proceso' }}
                    </span>
                </x-slot:footer>
            </x-sisig::stat-card>

            <x-sisig::stat-card 
                title="Quices Aprobados"
                value="{{ $quicesAprobadosCount }}"
                icon="fas fa-award"
                color="teal">
                <x-slot:footer>
                    <span class="text-slate-500 font-medium">Evaluaciones aprobadas</span>
                    <span class="text-xs font-bold text-teal-600">
                        {{ $totalEvaluacionesPresentadas }} presentadas
                    </span>
                </x-slot:footer>
            </x-sisig::stat-card>
        </div>

        <!-- 3. Requisitos e Información Clave de Inducción -->
        <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-800 flex items-center gap-2.5">
                        <i class="fas fa-info-circle text-sky-600"></i>
                        <span>Requisitos de Aprobación de la Inducción SIG</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Lineamientos oficiales para acreditar tu inducción en SENA Empresa</p>
                </div>
                <a href="{{ route('sisig.perfil.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-sena-green hover:text-emerald-700 bg-emerald-50 hover:bg-emerald-100/70 px-3.5 py-1.5 rounded-xl transition-all self-start sm:self-auto">
                    <i class="fas fa-user-circle"></i>
                    <span>Ver Mi Perfil</span>
                    <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-5">
                <div class="flex items-start gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <i class="fas fa-check-circle text-sena-green text-lg mt-0.5 flex-shrink-0"></i>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800">Calificación Mínima</h4>
                        <p class="text-[11px] text-slate-500 mt-0.5">Aprobar las <strong>evaluaciones de conocimiento</strong> con un puntaje mínimo del <strong>70%</strong>.</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <i class="fas fa-redo-alt text-sky-600 text-lg mt-0.5 flex-shrink-0"></i>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800">Límite de Intentos</h4>
                        <p class="text-[11px] text-slate-500 mt-0.5">Dispondrás de los <strong>intentos configurados</strong> por el instructor para cada evaluación.</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <i class="fas fa-shield-alt text-amber-500 text-lg mt-0.5 flex-shrink-0"></i>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800">Sistema Antitrampas</h4>
                        <p class="text-[11px] text-slate-500 mt-0.5">Monitoreo activo durante la prueba; cualquier conducta irregular causará la <strong>anulación del examen</strong>.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Historial de Evaluaciones y Calificaciones -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-800 flex items-center gap-2.5">
                        <i class="fas fa-history text-sena-green"></i>
                        <span>Mi Historial de Evaluaciones y Quices</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Seguimiento detallado de los intentos y calificaciones obtenidas</p>
                </div>
                <span class="text-xs font-bold text-slate-400 bg-slate-100 px-3 py-1 rounded-xl self-start sm:self-auto">
                    Total intentos: {{ $totalEvaluacionesPresentadas }}
                </span>
            </div>

            @if($historialEvaluaciones->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider">
                                <th class="py-3.5 px-4">Evaluación</th>
                                <th class="py-3.5 px-4">Eje Temático</th>
                                <th class="py-3.5 px-4 text-center">Intento</th>
                                <th class="py-3.5 px-4 text-center">Calificación</th>
                                <th class="py-3.5 px-4 text-center">Mínimo</th>
                                <th class="py-3.5 px-4 text-center">Estado</th>
                                <th class="py-3.5 px-4 text-right">Fecha</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($historialEvaluaciones as $h)
                                <tr class="hover:bg-slate-50/90 transition-colors">
                                    <td class="py-3.5 px-4 font-bold text-slate-800">
                                        {{ $h->quiz_titulo }}
                                    </td>
                                    <td class="py-3.5 px-4 font-semibold text-slate-600">
                                        {{ $h->seccion_nombre }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-bold text-slate-500">
                                        #{{ $h->intento }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <span class="text-sm font-black {{ $h->aprobado ? 'text-emerald-600' : 'text-rose-600' }}">
                                            {{ number_format($h->puntaje, 1) }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-center text-slate-400 font-medium">
                                        {{ number_format($h->puntaje_minimo, 0) }}%
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        @if($h->fue_anulado)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-rose-100 text-rose-800">
                                                Anulada
                                            </span>
                                        @elseif($h->aprobado)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800">
                                                Aprobado
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-100 text-amber-800">
                                                No Aprobado
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-right text-slate-400 font-medium">
                                        {{ \Carbon\Carbon::parse($h->created_at)->format('d/m/Y H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <!-- Estado Vacío cuando no ha presentado pruebas -->
                <div class="py-12 px-4 text-center space-y-3">
                    <div class="w-16 h-16 mx-auto rounded-3xl bg-slate-100 flex items-center justify-center text-slate-400 text-2xl shadow-inner">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-700">Aún no has presentado evaluaciones</h3>
                    <p class="text-xs text-slate-500 max-w-md mx-auto">
                        Tus intentos y calificaciones se registrarán automáticamente en esta tabla cuando presentes tus evaluaciones del SIG.
                    </p>
                </div>
            @endif
        </div>

    </div>
</x-sisig::layouts.admin>
