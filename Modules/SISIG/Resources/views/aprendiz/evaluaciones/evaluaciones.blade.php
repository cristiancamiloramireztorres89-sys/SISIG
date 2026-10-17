<x-sisig::layouts.admin title="Mis Evaluaciones SIG">
    <div class="space-y-6 sm:space-y-8 animate-fade-in pb-12 w-full max-w-[1600px] mx-auto">

        <!-- 1. Encabezado de la Sección (Estilo Limpio y Unificado) -->
        <div class="pb-5 border-b border-slate-200">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                    <i class="fas fa-clipboard-check text-sena-green"></i>
                    <span>Mis Evaluaciones SIG</span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Espacio evaluativo donde responderás cuestionarios interactivos con control de tiempo regresivo, comprobación de intentos y calificación instantánea.
                </p>
            </div>
        </div>

        <!-- 2. Tarjetas de Métricas Clave (KPIs) con Estilo Premium -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            
            <!-- Total Evaluaciones -->
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Total Creados</span>
                    <i class="fas fa-clipboard-list text-2xl text-blue-600 group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight">{{ $totalEvaluaciones }}</h3>
                    <div class="mt-2.5 flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Evaluaciones en el sistema</span>
                    </div>
                </div>
            </div>

            <!-- Aprobadas -->
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-sena-green to-[#237000]"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Aprobadas</span>
                    <i class="fas fa-check-circle text-2xl text-sena-green group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight">{{ $totalAprobadas }}</h3>
                    <div class="mt-2.5 flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Evaluaciones superadas</span>
                    </div>
                </div>
            </div>

            <!-- Disponibles para presentar -->
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-amber-400 to-orange-500"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Disponibles</span>
                    <i class="fas fa-play text-2xl text-amber-500 group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight">{{ $totalPendientes }}</h3>
                    <div class="mt-2.5 flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Listas para presentar</span>
                    </div>
                </div>
            </div>

            <!-- Intentos Agotados -->
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-rose-500 to-red-600"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Agotadas</span>
                    <i class="fas fa-ban text-2xl text-rose-500 group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight">{{ $totalAgotadas }}</h3>
                    <div class="mt-2.5 flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Sin intentos restantes</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Barra de Búsqueda y Filtros Estilo Premium -->
        <div class="bg-white rounded-3xl p-4 sm:p-5 border border-slate-200/90 shadow-sm flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="relative w-full md:w-96">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" id="filtroQuizInput" placeholder="Buscar evaluación por nombre o módulo..." 
                       class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-2 focus:ring-sena-green/50 transition-all outline-none text-slate-700 placeholder-slate-400 shadow-inner">
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <select id="filtroModuloSelect" class="bg-slate-50 border border-slate-200 text-slate-700 font-medium text-sm rounded-2xl px-4 py-3 focus:ring-2 focus:ring-sena-green/50 outline-none w-full sm:w-auto cursor-pointer transition-all hover:bg-slate-100">
                    <option value="">Todas las secciones</option>
                    @foreach($evaluaciones->pluck('modulo.titulo')->unique()->filter() as $modTitulo)
                        <option value="{{ strtolower($modTitulo) }}">{{ $modTitulo }}</option>
                    @endforeach
                </select>

                <select id="filtroEstadoSelect" class="bg-slate-50 border border-slate-200 text-slate-700 font-medium text-sm rounded-2xl px-4 py-3 focus:ring-2 focus:ring-sena-green/50 outline-none w-full sm:w-auto cursor-pointer transition-all hover:bg-slate-100">
                    <option value="">Todos los estados</option>
                    <option value="aprobado">Aprobado</option>
                    <option value="disponible">Disponible</option>
                    <option value="agotado">Agotado</option>
                </select>
            </div>
        </div>

        <!-- 4. Catálogo de Evaluaciones -->
        <div class="space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-800 flex items-center gap-2.5">
                        <i class="fas fa-clipboard-check text-sena-green"></i>
                        <span>Catálogo del Proceso de Evaluación</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Cuestionarios de opción múltiple con cronómetro, intentos limitados y calificación instantánea</p>
                </div>
            </div>

            @if($evaluaciones->isEmpty())
                <!-- Estado vacío ilustrado exacto al diseño institucional -->
                <div class="py-16 px-6 text-center rounded-3xl bg-white border border-dashed border-slate-300 shadow-sm flex flex-col items-center justify-center">
                    <div class="w-20 h-20 rounded-3xl bg-slate-50 border border-slate-200 text-slate-400 flex items-center justify-center text-3xl mb-5 shadow-inner">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-800">Aún no hay evaluaciones creadas</h4>
                    <p class="text-sm text-slate-500 max-w-md mx-auto mt-2 leading-relaxed mb-6">
                        El sistema se encuentra en desarrollo inicial. Los instructores y administradores están configurando los quices y preguntas para evaluar cada módulo formativo.
                    </p>
                </div>
            @else
                <!-- Grid de Evaluaciones Dinámicas -->
                <div id="gridEvaluaciones" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($evaluaciones as $ev)
                        @php
                            $estadoFiltro = $ev->aprobado ? 'aprobado' : ($ev->intentos_restantes <= 0 ? 'agotado' : 'disponible');
                        @endphp
                        <div class="card-quiz bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative group flex flex-col h-full"
                             data-titulo="{{ strtolower($ev->titulo) }}"
                             data-modulo="{{ strtolower($ev->modulo->titulo ?? '') }}"
                             data-estado="{{ $estadoFiltro }}">

                            <!-- Encabezado de la Tarjeta -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-sena-green flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-tasks text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-extrabold text-slate-800 text-base leading-tight">{{ $ev->titulo }}</h3>
                                        <div class="flex items-center gap-1.5 mt-1 text-slate-500 text-xs font-medium">
                                            <i class="fas fa-layer-group text-slate-400"></i>
                                            <span>{{ $ev->modulo->titulo ?? 'Módulo General' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Etiqueta de Estado Limpia -->
                            <div class="absolute top-6 right-6">
                                @if($ev->aprobado)
                                    <span class="text-xs font-black tracking-wide uppercase text-emerald-600 flex items-center gap-1">
                                        <i class="fas fa-check-circle"></i> Aprobado
                                    </span>
                                @elseif($ev->intentos_restantes <= 0)
                                    <span class="text-xs font-black tracking-wide uppercase text-rose-600 flex items-center gap-1">
                                        <i class="fas fa-times-circle"></i> Agotado
                                    </span>
                                @else
                                    <span class="text-xs font-black tracking-wide uppercase text-sky-600 flex items-center gap-1">
                                        <i class="fas fa-clock"></i> Disponible
                                    </span>
                                @endif
                            </div>

                            <!-- Parámetros de la Evaluación (Tiempo, Preguntas, Nota Mínima) -->
                            <div class="grid grid-cols-3 gap-2.5 my-4 p-3 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                                <div>
                                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Tiempo</span>
                                    <span class="text-xs font-black text-slate-800 flex items-center justify-center gap-1 mt-0.5">
                                        <i class="far fa-clock text-amber-500"></i> {{ $ev->tiempo_minutos }}m
                                    </span>
                                </div>
                                <div class="border-x border-slate-200">
                                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Preguntas</span>
                                    <span class="text-xs font-black text-slate-800 flex items-center justify-center gap-1 mt-0.5">
                                        <i class="fas fa-question text-sky-500"></i> {{ $ev->numero_preguntas }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Mínimo</span>
                                    <span class="text-xs font-black text-sena-green flex items-center justify-center gap-1 mt-0.5">
                                        <i class="fas fa-award text-sena-green"></i> {{ $ev->puntaje_minimo }}%
                                    </span>
                                </div>
                            </div>

                            <!-- Control de Intentos en Tiempo Real -->
                            <div class="mb-5 p-3.5 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
                                <div class="flex justify-between text-xs">
                                    <span class="text-slate-600 font-bold">Intentos utilizados</span>
                                    <span class="font-black text-slate-800">{{ $ev->intentos_realizados }} / {{ $ev->intentos_permitidos }}</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden shadow-inner">
                                    @php
                                        $porcentajeIntentos = $ev->intentos_permitidos > 0 ? min(100, ($ev->intentos_realizados / $ev->intentos_permitidos) * 100) : 0;
                                    @endphp
                                    <div class="h-1.5 rounded-full transition-all {{ $ev->aprobado ? 'bg-sena-green' : ($ev->intentos_restantes <= 0 ? 'bg-rose-500' : 'bg-amber-500') }}" style="width: {{ $porcentajeIntentos }}%"></div>
                                </div>
                                @if($ev->intentos_realizados > 0)
                                    <div class="flex justify-between text-[11px] pt-1 text-slate-500">
                                        <span>Mejor calificación:</span>
                                        <span class="font-black {{ $ev->aprobado ? 'text-sena-green' : 'text-slate-700' }}">{{ $ev->mejor_puntaje }}%</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Botón de Acción Principal -->
                            <div class="pt-4 border-t border-slate-100 mt-auto">
                                @if($ev->aprobado)
                                    <button disabled 
                                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-2xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 w-full cursor-not-allowed">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Evaluación Superada</span>
                                    </button>
                                @elseif($ev->intentos_restantes <= 0)
                                    <button disabled 
                                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-2xl text-xs font-bold bg-slate-100 text-slate-400 border border-slate-200 w-full cursor-not-allowed">
                                        <i class="fas fa-ban"></i>
                                        <span>Intentos Agotados</span>
                                    </button>
                                @elseif($ev->numero_preguntas == 0)
                                    <button disabled 
                                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-2xl text-xs font-bold bg-slate-100 text-slate-400 border border-slate-200 w-full cursor-not-allowed">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <span>Sin Preguntas Configuradas</span>
                                    </button>
                                @else
                                    <a href="{{ route('sisig.aprendiz.evaluaciones.iniciar', $ev->id_quiz) }}" 
                                       class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-2xl text-xs font-bold text-white bg-sena-green hover:bg-emerald-600 shadow-sena hover:shadow-lg transition-all w-full group-hover:bg-emerald-600">
                                        <i class="fas fa-play text-xs"></i>
                                        <span>Iniciar Evaluación (Intento #{{ $ev->intentos_realizados + 1 }})</span>
                                        <i class="fas fa-arrow-right text-[10px] transform group-hover:translate-x-1 transition-transform"></i>
                                    </a>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

    <!-- Script de Filtro y Búsqueda en Tiempo Real -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('filtroQuizInput');
            const moduloSelect = document.getElementById('filtroModuloSelect');
            const estadoSelect = document.getElementById('filtroEstadoSelect');
            const cards = document.querySelectorAll('.card-quiz');

            function filtrar() {
                const query = (searchInput?.value || '').trim().toLowerCase();
                const modulo = (moduloSelect?.value || '').toLowerCase();
                const estado = (estadoSelect?.value || '').toLowerCase();

                cards.forEach(card => {
                    const cardTitulo = card.getAttribute('data-titulo') || '';
                    const cardModulo = card.getAttribute('data-modulo') || '';
                    const cardEstado = card.getAttribute('data-estado') || '';

                    const coincideQuery = !query || cardTitulo.includes(query) || cardModulo.includes(query);
                    const coincideModulo = !modulo || cardModulo.includes(modulo);
                    const coincideEstado = !estado || cardEstado === estado;

                    if (coincideQuery && coincideModulo && coincideEstado) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }

            if (searchInput) searchInput.addEventListener('input', filtrar);
            if (moduloSelect) moduloSelect.addEventListener('change', filtrar);
            if (estadoSelect) estadoSelect.addEventListener('change', filtrar);
        });
    </script>
</x-sisig::layouts.admin>
