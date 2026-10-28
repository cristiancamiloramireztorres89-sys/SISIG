<x-sisig::layouts.master title="Equipo de Desarrolladores SISIG">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-12">

        <!-- Encabezado simple y limpio -->
        <div class="border-b border-slate-200 pb-5 flex items-center justify-between">
            <div class="flex items-center gap-3.5">
                <a href="{{ route('sisig.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 text-xs sm:text-sm font-bold border border-slate-200 shadow-xs hover:border-sena-green transition-all">
                    <i class="fas fa-arrow-left text-sena-green"></i>
                    <span>Volver a SISIG</span>
                </a>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                    <span class="w-3.5 h-8 rounded-full bg-sena-green inline-block"></span>
                    Equipo de Desarrolladores SISIG
                </h1>
            </div>
        </div>

        <section class="space-y-6">

            @php
                // Sincronización y detección automática de fotos (en public o en Modules/SISIG/Resources/assets/images/desarrolladores)
                $sourceDir = base_path('Modules/SISIG/Resources/assets/images/desarrolladores');
                $targetDir = public_path('modules/sisig/images/desarrolladores');

                if (!file_exists($targetDir)) {
                    @mkdir($targetDir, 0755, true);
                }

                $devs = ['cristian', 'johan', 'kevin', 'keiner'];
                $exts = ['jpg', 'jpeg', 'png', 'webp', 'JPG', 'PNG', 'JPEG'];

                foreach ($devs as $dev) {
                    foreach ($exts as $ext) {
                        $srcFile = $sourceDir . '/' . $dev . '.' . $ext;
                        $dstFile = $targetDir . '/' . $dev . '.' . $ext;
                        if (file_exists($srcFile) && (!file_exists($dstFile) || filemtime($srcFile) > filemtime($dstFile))) {
                            @copy($srcFile, $dstFile);
                        }
                    }
                }

                $getFoto = function($dev) use ($targetDir, $exts) {
                    foreach ($exts as $ext) {
                        if (file_exists($targetDir . '/' . $dev . '.' . $ext)) {
                            return asset('modules/sisig/images/desarrolladores/' . $dev . '.' . $ext) . '?v=' . filemtime($targetDir . '/' . $dev . '.' . $ext);
                        }
                    }
                    return null;
                };

                $fotoCristian = $getFoto('cristian');
                $fotoJohan = $getFoto('johan');
                $fotoKevin = $getFoto('kevin');
                $fotoKeiner = $getFoto('keiner');
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                {{-- =========================================================================
                     1. CRISTIAN CAMILO RAMÍREZ TORRES
                     ========================================================================= --}}
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden group">
                    <!-- Cabecera de la Tarjeta (Dark Navy Gradient) -->
                    <div class="bg-gradient-to-r from-[#1A2B4C] to-[#243B66] p-6 text-white text-center relative rounded-t-2xl flex flex-col items-center">
                        <div class="relative w-28 h-28 rounded-full overflow-hidden shadow-lg ring-4 ring-white/20 bg-slate-800 flex items-center justify-center group-hover:scale-105 transition-transform duration-300 flex-shrink-0 mb-3">
                            @if($fotoCristian)
                                <img src="{{ $fotoCristian }}" alt="Cristian Camilo Ramírez Torres" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-[#2E6F40] to-[#1A2B4C] text-white font-black text-2xl flex items-center justify-center">
                                    CR
                                </div>
                            @endif
                        </div>

                        <h3 class="text-base sm:text-lg font-black text-white leading-snug" style="color: #ffffff !important;">
                            Cristian Camilo Ramírez Torres
                        </h3>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-[#33FF57]/20 text-[#33FF57] border border-[#33FF57]/30 mt-2 shadow-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#33FF57]"></span>
                            Líder Full-Stack
                        </span>
                    </div>

                    <!-- Cuerpo de la Tarjeta con datos 100% reales -->
                    <div class="p-5 flex-1 flex flex-col justify-between bg-white rounded-b-2xl space-y-4 text-center items-center">
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Liderazgo tecnico, diseño de arquitectura, base de datos, desarrollo backend, estructuracion de controladores, verificacion y autenticacion y apoyo en liderazgo.
                        </p>

                        <!-- Áreas de Aporte -->
                        <div class="pt-3 border-t border-slate-100 w-full flex flex-wrap justify-center gap-1">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700">MVC</span>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700">Backend</span>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700">Frontend</span>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700">Laravel</span>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700">Base de datos</span>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700">Tailwind</span>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700">Controladores</span>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700">Liderazgo</span>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700">UI / UX</span>
                        </div>

                        <!-- Botones de Redes y Contacto -->
                        <div class="pt-3 border-t border-slate-100 w-full flex items-center justify-center gap-2">
                            <a href="https://github.com/cristiancamiloramireztorres89-sys" target="_blank" rel="noopener noreferrer" title="GitHub" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-[#1A2B4C] text-slate-500 hover:text-white flex items-center justify-center text-xs transition-all duration-200 hover:scale-110 shadow-xs">
                                <i class="fab fa-github"></i>
                            </a>
                            <a href="https://www.linkedin.com/in/%E5%8C%9A%E5%B0%BAi%E4%B8%82%E3%84%92i%E5%8D%82%E5%87%A0-%E5%B0%BA%E5%8D%82%E7%88%AAi%E5%B0%BA%E4%B9%87%E4%B9%99%C6%AD%CD%BC-undefined-681681299/?isSelfProfile=true" target="_blank" rel="noopener noreferrer" title="LinkedIn" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-[#0A66C2] text-slate-500 hover:text-white flex items-center justify-center text-xs transition-all duration-200 hover:scale-110 shadow-xs">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="https://wa.me/" target="_blank" rel="noopener noreferrer" title="WhatsApp" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-[#25D366] text-slate-500 hover:text-white flex items-center justify-center text-xs transition-all duration-200 hover:scale-110 shadow-xs">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="mailto:cristiancamiloramireztorres89@gmail.com" title="Correo Electrónico" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-[#2E6F40] text-slate-500 hover:text-white flex items-center justify-center text-xs transition-all duration-200 hover:scale-110 shadow-xs">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </div>

                        <div class="w-full pt-3 mt-auto border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                            <span class="font-bold">SENA CEFA</span>
                            <span class="font-semibold text-[#2E6F40]">La Angostura</span>
                        </div>
                    </div>
                </div>

                {{-- =========================================================================
                     2. JOHAN MANUEL RICO ARIZA
                     ========================================================================= --}}
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden group">
                    <!-- Cabecera de la Tarjeta (Dark Navy Gradient) -->
                    <div class="bg-gradient-to-r from-[#1A2B4C] to-[#243B66] p-6 text-white text-center relative rounded-t-2xl flex flex-col items-center">
                        <div class="relative w-28 h-28 rounded-full overflow-hidden shadow-lg ring-4 ring-white/20 bg-slate-800 flex items-center justify-center group-hover:scale-105 transition-transform duration-300 flex-shrink-0 mb-3">
                            @if($fotoJohan)
                                <img src="{{ $fotoJohan }}" alt="Johan Manuel Rico Ariza" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-[#2E6F40] to-[#1A2B4C] text-white font-black text-2xl flex items-center justify-center">
                                    JR
                                </div>
                            @endif
                        </div>

                        <h3 class="text-base sm:text-lg font-black text-white leading-snug" style="color: #ffffff !important;">
                            Johan Manuel Rico Ariza
                        </h3>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-[#33FF57]/20 text-[#33FF57] border border-[#33FF57]/30 mt-2 shadow-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#33FF57]"></span>
                            Backend Liderazgo
                        </span>
                    </div>

                    <!-- Cuerpo de la Tarjeta con datos 100% reales -->
                    <div class="p-5 flex-1 flex flex-col justify-between bg-white rounded-b-2xl space-y-4 text-center items-center">
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Desarrollo backend, estructuración de controladores, verificación y autenticación, apoyo en liderazgo y documentación.
                        </p>

                        <!-- Áreas de Aporte -->
                        <div class="pt-3 border-t border-slate-100 w-full flex flex-wrap justify-center gap-1">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700">Backend</span>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700">Controladores</span>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700">Seguridad</span>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700">Documentación</span>
                        </div>

                        <!-- Botones de Redes y Contacto -->
                        <div class="pt-3 border-t border-slate-100 w-full flex items-center justify-center gap-2">
                            <a href="https://github.com/johan32-laps" target="_blank" rel="noopener noreferrer" title="GitHub" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-[#1A2B4C] text-slate-500 hover:text-white flex items-center justify-center text-xs transition-all duration-200 hover:scale-110 shadow-xs">
                                <i class="fab fa-github"></i>
                            </a>
                            <a href="#" target="_blank" rel="noopener noreferrer" title="LinkedIn" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-[#0A66C2] text-slate-500 hover:text-white flex items-center justify-center text-xs transition-all duration-200 hover:scale-110 shadow-xs">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="https://wa.me/" target="_blank" rel="noopener noreferrer" title="WhatsApp" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-[#25D366] text-slate-500 hover:text-white flex items-center justify-center text-xs transition-all duration-200 hover:scale-110 shadow-xs">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="mailto:" title="Correo Electrónico" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-[#2E6F40] text-slate-500 hover:text-white flex items-center justify-center text-xs transition-all duration-200 hover:scale-110 shadow-xs">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </div>

                        <div class="w-full pt-3 mt-auto border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                            <span class="font-bold">SENA CEFA</span>
                            <span class="font-semibold text-[#2E6F40]">La Angostura</span>
                        </div>
                    </div>
                </div>

                {{-- =========================================================================
                     3. KEVIN ZABALETA ESPITIA
                     ========================================================================= --}}
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden group">
                    <!-- Cabecera de la Tarjeta (Dark Navy Gradient) -->
                    <div class="bg-gradient-to-r from-[#1A2B4C] to-[#243B66] p-6 text-white text-center relative rounded-t-2xl flex flex-col items-center">
                        <div class="relative w-28 h-28 rounded-full overflow-hidden shadow-lg ring-4 ring-white/20 bg-slate-800 flex items-center justify-center group-hover:scale-105 transition-transform duration-300 flex-shrink-0 mb-3">
                            @if($fotoKevin)
                                <img src="{{ $fotoKevin }}" alt="Kevin Zabaleta Espitia" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-[#2E6F40] to-[#1A2B4C] text-white font-black text-2xl flex items-center justify-center">
                                    KZ
                                </div>
                            @endif
                        </div>

                        <h3 class="text-base sm:text-lg font-black text-white leading-snug" style="color: #ffffff !important;">
                            Kevin Zabaleta Espitia
                        </h3>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-[#33FF57]/20 text-[#33FF57] border border-[#33FF57]/30 mt-2 shadow-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#33FF57]"></span>
                            Documentación QA
                        </span>
                    </div>

                    <!-- Cuerpo de la Tarjeta con datos 100% reales -->
                    <div class="p-5 flex-1 flex flex-col justify-between bg-white rounded-b-2xl space-y-4 text-center items-center">
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Historias de usuario en formato Gherkin, criterios de aceptación, pruebas funcionales del aplicativo y redacción técnica.
                        </p>

                        <!-- Áreas de Aporte -->
                        <div class="pt-3 border-t border-slate-100 w-full flex flex-wrap justify-center gap-1">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700">Gherkin</span>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700">Criterios</span>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700">Pruebas QA</span>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700">Frontend</span>
                        </div>

                        <!-- Botones de Redes y Contacto -->
                        <div class="pt-3 border-t border-slate-100 w-full flex items-center justify-center gap-2">
                            <a href="https://github.com/Kevin2008zabaleta" target="_blank" rel="noopener noreferrer" title="GitHub" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-[#1A2B4C] text-slate-500 hover:text-white flex items-center justify-center text-xs transition-all duration-200 hover:scale-110 shadow-xs">
                                <i class="fab fa-github"></i>
                            </a>
                            <a href="#" target="_blank" rel="noopener noreferrer" title="LinkedIn" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-[#0A66C2] text-slate-500 hover:text-white flex items-center justify-center text-xs transition-all duration-200 hover:scale-110 shadow-xs">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="https://wa.me/" target="_blank" rel="noopener noreferrer" title="WhatsApp" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-[#25D366] text-slate-500 hover:text-white flex items-center justify-center text-xs transition-all duration-200 hover:scale-110 shadow-xs">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="mailto:" title="Correo Electrónico" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-[#2E6F40] text-slate-500 hover:text-white flex items-center justify-center text-xs transition-all duration-200 hover:scale-110 shadow-xs">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </div>

                        <div class="w-full pt-3 mt-auto border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                            <span class="font-bold">SENA CEFA</span>
                            <span class="font-semibold text-[#2E6F40]">La Angostura</span>
                        </div>
                    </div>
                </div>

                {{-- =========================================================================
                     4. KEINER ZABALETA ESPITIA
                     ========================================================================= --}}
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden group">
                    <!-- Cabecera de la Tarjeta (Dark Navy Gradient) -->
                    <div class="bg-gradient-to-r from-[#1A2B4C] to-[#243B66] p-6 text-white text-center relative rounded-t-2xl flex flex-col items-center">
                        <div class="relative w-28 h-28 rounded-full overflow-hidden shadow-lg ring-4 ring-white/20 bg-slate-800 flex items-center justify-center group-hover:scale-105 transition-transform duration-300 flex-shrink-0 mb-3">
                            @if($fotoKeiner)
                                <img src="{{ $fotoKeiner }}" alt="Keiner Zabaleta Espitia" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-[#2E6F40] to-[#1A2B4C] text-white font-black text-2xl flex items-center justify-center">
                                    KZ
                                </div>
                            @endif
                        </div>

                        <h3 class="text-base sm:text-lg font-black text-white leading-snug" style="color: #ffffff !important;">
                            Keiner Zabaleta Espitia
                        </h3>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-[#33FF57]/20 text-[#33FF57] border border-[#33FF57]/30 mt-2 shadow-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#33FF57]"></span>
                            Diagramación Frontend
                        </span>
                    </div>

                    <!-- Cuerpo de la Tarjeta con datos 100% reales -->
                    <div class="p-5 flex-1 flex flex-col justify-between bg-white rounded-b-2xl space-y-4 text-center items-center">
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Modelado de diagramas UML, casos de uso, maquetación de interfaces con Tailwind CSS y estructuración gráfica del proyecto.
                        </p>

                        <!-- Áreas de Aporte -->
                        <div class="pt-3 border-t border-slate-100 w-full flex flex-wrap justify-center gap-1">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700">UML</span>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700">UI / UX</span>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700">Tailwind</span>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700">Blade</span>
                        </div>

                        <!-- Botones de Redes y Contacto -->
                        <div class="pt-3 border-t border-slate-100 w-full flex items-center justify-center gap-2">
                            <a href="https://github.com/keinerzabaleta44-gif" target="_blank" rel="noopener noreferrer" title="GitHub" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-[#1A2B4C] text-slate-500 hover:text-white flex items-center justify-center text-xs transition-all duration-200 hover:scale-110 shadow-xs">
                                <i class="fab fa-github"></i>
                            </a>
                            <a href="#" target="_blank" rel="noopener noreferrer" title="LinkedIn" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-[#0A66C2] text-slate-500 hover:text-white flex items-center justify-center text-xs transition-all duration-200 hover:scale-110 shadow-xs">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="https://wa.me/" target="_blank" rel="noopener noreferrer" title="WhatsApp" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-[#25D366] text-slate-500 hover:text-white flex items-center justify-center text-xs transition-all duration-200 hover:scale-110 shadow-xs">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="mailto:" title="Correo Electrónico" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-[#2E6F40] text-slate-500 hover:text-white flex items-center justify-center text-xs transition-all duration-200 hover:scale-110 shadow-xs">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </div>

                        <div class="w-full pt-3 mt-auto border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                            <span class="font-bold">SENA CEFA</span>
                            <span class="font-semibold text-[#2E6F40]">La Angostura</span>
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </div>
</x-sisig::layouts.master>
