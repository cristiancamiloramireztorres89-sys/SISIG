<x-sisig::layouts.admin title="Quiz Interactivo | Aprendiz">
    <div class="space-y-6 w-full max-w-[1600px] mx-auto pb-12">

        <!-- Banner de cabecera verde institucional SENA -->
        <div class="relative overflow-hidden rounded-2xl p-6 sm:p-8 text-white shadow-sm" style="background: linear-gradient(135deg, #0d5c3a 0%, #116842 50%, #157347 100%);">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="space-y-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-white text-xl shadow-sm">
                            <i class="fas fa-gamepad"></i>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                            Quiz Interactivo
                        </h1>
                    </div>
                    <p class="text-emerald-100/90 text-xs sm:text-sm max-w-2xl font-normal leading-relaxed">
                        Selecciona la sección que quieres evaluar y pon a prueba tus conocimientos en forma de juego.
                    </p>
                    <nav class="flex items-center gap-2 text-xs text-emerald-200/80 pt-1">
                        <a href="{{ route('sisig.aprendiz.dashboard') }}" class="hover:text-white transition-colors">Inicio</a>
                        <span class="text-emerald-300/60">›</span>
                        <span class="text-white font-semibold">Quiz Interactivo</span>
                    </nav>
                </div>
            </div>
        </div>

        @if(count($seccionesList) > 0)
            <!-- Subtítulo de selección de sección -->
            <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-wider pt-2">
                <i class="fas fa-gamepad text-[#0d5c3a]"></i>
                <span>Elige una sección para comenzar</span>
            </div>

            <!-- Rejilla de tarjetas de quices en tiempo real -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-7">
                @foreach($seccionesList as $seccion)
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-md hover:border-emerald-200 transition-all p-7 text-center flex flex-col items-center justify-between h-full group">
                    <!-- Parte Superior: Icono, Badge, Título y Metadatos -->
                    <div class="w-full flex flex-col items-center">
                        <!-- Contenedor del Icono Temático en Verde Institucional -->
                        <div class="w-20 h-20 rounded-2xl bg-[#e8f5e9] flex items-center justify-center text-3xl text-[#0d5c3a] mb-4 shadow-xs group-hover:scale-105 transition-transform duration-200">
                            @if(str_contains(mb_strtolower($seccion->icono), 'cone'))
                                <svg class="w-9 h-9 fill-current text-[#0d5c3a]" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 2L6 17h12L12 2zm0 4.2l3.4 8.8H8.6L12 6.2zM4 19h16v3H4v-3z"/>
                                </svg>
                            @else
                                <i class="{{ $seccion->icono }}"></i>
                            @endif
                        </div>

                        <!-- Insignia de Estado en Tiempo Real -->
                        <div class="mb-3">
                            <span class="inline-block px-3.5 py-1 rounded-full text-xs font-bold {{ $seccion->badge_bg }} {{ $seccion->badge_text }} shadow-xs">
                                {{ $seccion->estado }}
                            </span>
                        </div>

                        <!-- Título de la Sección / Quiz -->
                        <h2 class="text-xl font-bold text-slate-800 mb-1.5">
                            {{ $seccion->titulo }}
                        </h2>

                        <!-- Detalles: Norma, Preguntas, Intentos -->
                        <p class="text-xs text-slate-400 font-medium mb-6">
                            {{ $seccion->norma }} — {{ $seccion->preguntas_count }} {{ $seccion->preguntas_count == 1 ? 'pregunta' : 'preguntas' }} — {{ $seccion->intentos_max }} {{ $seccion->intentos_max == 1 ? 'intento' : 'intentos' }} max.
                        </p>
                    </div>

                    <!-- Parte Inferior: Botón de Acción -->
                    <div class="w-full pt-2">
                        @if($seccion->disponible)
                            <a href="{{ route('sisig.aprendiz.quiz.show', $seccion->id_quiz) }}"
                               class="w-full py-2.5 px-4 rounded-xl bg-[#0d5c3a] hover:bg-[#157347] text-white font-bold text-sm flex items-center justify-center gap-2 transition-all shadow-sm hover:shadow-md cursor-pointer active:scale-98">
                                <i class="fas fa-play text-xs"></i>
                                <span>Comenzar quiz</span>
                            </a>
                        @else
                            <button disabled
                                    class="w-full py-2.5 px-4 rounded-xl bg-[#f1f5f9] text-slate-400 font-bold text-sm flex items-center justify-center gap-2 cursor-not-allowed select-none">
                                <i class="fas fa-lock text-xs"></i>
                                <span>No disponible</span>
                            </button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <!-- Estado Vacío en Tiempo Real cuando el editor no ha creado quices aún -->
            <div class="bg-white rounded-3xl border border-emerald-100 shadow-sm p-10 sm:p-14 text-center max-w-xl mx-auto my-6">
                <div class="w-20 h-20 rounded-2xl bg-[#e8f5e9] text-[#0d5c3a] flex items-center justify-center text-3xl mx-auto mb-4 shadow-xs">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">
                    No hay quices interactivos disponibles
                </h3>
                <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto leading-relaxed mb-6">
                    Actualmente el editor de contenidos aún no ha creado ni habilitado quices interactivos en la plataforma. Una vez creados en la base de datos, aparecerán aquí automáticamente en tiempo real.
                </p>
                <a href="{{ route('sisig.aprendiz.dashboard') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0d5c3a] hover:bg-[#157347] text-white text-xs font-bold transition-all shadow-sm">
                    <i class="fas fa-arrow-left"></i>
                    <span>Volver a mi dashboard</span>
                </a>
            </div>
        @endif

    </div>
</x-sisig::layouts.admin>
