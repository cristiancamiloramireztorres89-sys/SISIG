class SisigAntiTrampa {
    constructor(config = {}) {
        this.intentoId = config.intentoId;
        this.incidenteUrl = config.incidenteUrl;
        this.csrfToken = config.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.maxFaltas = config.maxFaltas || 3;
        this.faltasActuales = config.faltasIniciales || 0;
        this.formSelector = config.formSelector || '#form-examen-quiz';
        this.requierePantallaCompleta = config.requierePantallaCompleta !== undefined ? config.requierePantallaCompleta : true;
        
        this.bloqueado = false;
        this.ultimoIncidenteTiempo = 0;
        this.cooldownMs = 1200;

        this.init();
    }

    init() {
        if (!this.intentoId || !this.incidenteUrl) {
            console.error('[SISIG Anti-Trampa] Configuración incompleta.');
            return;
        }

        this.actualizarUIFaltas();
        this.registrarEventosFoco();
        this.registrarEventosPortapapeles();
        this.registrarEventosTeclado();
        this.registrarEventosPantallaCompleta();
        this.iniciarProteccionSeleccion();

        console.log('[SISIG Anti-Trampa] Monitor activo con umbral máximo:', this.maxFaltas);
    }

    actualizarUIFaltas() {
        const vidasRestantes = Math.max(0, this.maxFaltas - this.faltasActuales);

        const textoVidas = document.getElementById('vidas-restantes-texto');
        if (textoVidas) {
            if (vidasRestantes === 3) {
                textoVidas.textContent = '3 vidas';
                textoVidas.className = 'text-[11px] font-black text-rose-600 block leading-tight mt-0.5';
            } else if (vidasRestantes === 2) {
                textoVidas.textContent = '2 vidas';
                textoVidas.className = 'text-[11px] font-black text-amber-600 block leading-tight mt-0.5';
            } else if (vidasRestantes === 1) {
                textoVidas.textContent = '¡Última vida!';
                textoVidas.className = 'text-[11px] font-black text-rose-600 block leading-tight mt-0.5 animate-pulse';
            } else {
                textoVidas.textContent = '0 vidas';
                textoVidas.className = 'text-[11px] font-black text-slate-400 block leading-tight mt-0.5';
            }
        }

        // Actualizar estado de los corazones 3D
        for (let v = 1; v <= this.maxFaltas; v++) {
            const heartBox = document.getElementById(`heart-life-${v}`);
            if (!heartBox) continue;

            const body = heartBox.querySelector('.heart-body');
            const depth = heartBox.querySelector('.heart-depth');
            const rimLight = heartBox.querySelector('.heart-rim-light');
            const shine = heartBox.querySelector('.heart-shine');
            const shineMini = heartBox.querySelector('.heart-shine-mini');
            const crack = heartBox.querySelector('.heart-crack');
            const crackHighlight = heartBox.querySelector('.heart-crack-highlight');
            const groundShadow = heartBox.querySelector('.heart-ground-shadow');

            const esPerdido = v > vidasRestantes;

            if (esPerdido) {
                if (!heartBox.classList.contains('heart-lost')) {
                    heartBox.classList.add('heart-shatter');
                    setTimeout(() => heartBox.classList.remove('heart-shatter'), 750);
                }
                heartBox.classList.remove('heart-alive', 'heart-critical');
                heartBox.classList.add('heart-lost');
                heartBox.setAttribute('title', `Vida #${v}: Perdida`);

                if (body) body.setAttribute('fill', `url(#grad-heart-lost-quiz-${v})`);
                if (depth) depth.setAttribute('fill', '#0f172a');
                if (groundShadow) groundShadow.setAttribute('opacity', '0.15');
                if (rimLight) rimLight.classList.add('hidden');
                if (shine) shine.classList.add('hidden');
                if (shineMini) shineMini.classList.add('hidden');
                if (crack) crack.classList.remove('hidden');
                if (crackHighlight) crackHighlight.classList.remove('hidden');
            } else {
                heartBox.classList.remove('heart-lost');
                heartBox.classList.add('heart-alive');
                heartBox.setAttribute('title', `Vida #${v}: Activa`);

                if (vidasRestantes === 1 && v === 1) {
                    heartBox.classList.add('heart-critical');
                } else {
                    heartBox.classList.remove('heart-critical');
                }

                if (body) body.setAttribute('fill', `url(#grad-heart-active-quiz-${v})`);
                if (depth) depth.setAttribute('fill', '#4c0519');
                if (groundShadow) groundShadow.setAttribute('opacity', '0.6');
                if (rimLight) rimLight.classList.remove('hidden');
                if (shine) shine.classList.remove('hidden');
                if (shineMini) shineMini.classList.remove('hidden');
                if (crack) crack.classList.add('hidden');
                if (crackHighlight) crackHighlight.classList.add('hidden');
            }
        }

        // Compatibilidad con contadores numéricos si existieran
        const contadorTexto = document.getElementById('anti-trampa-contador-texto');
        if (contadorTexto) {
            contadorTexto.textContent = `${this.faltasActuales}/${this.maxFaltas}`;
        }
    }

    registrarEventosFoco() {
        document.addEventListener('visibilitychange', () => {
            if (document.hidden && !this.bloqueado) {
                this.reportarIncidente('cambio_pestana', 'El aprendiz cambió de pestaña o minimizó el navegador.');
            }
        });

        window.addEventListener('blur', () => {
            if (!this.bloqueado) {
                const ahora = Date.now();
                if (ahora - this.ultimoIncidenteTiempo > this.cooldownMs) {
                    this.reportarIncidente('desenfoque_ventana', 'El navegador perdió el foco o interactuó fuera del examen.');
                }
            }
        });
    }

    registrarEventosPortapapeles() {
        document.addEventListener('copy', (e) => {
            if (this.bloqueado) return;
            e.preventDefault();
            this.reportarIncidente('intento_copiar', 'Intento bloqueado de copiar texto.');
        });

        document.addEventListener('cut', (e) => {
            if (this.bloqueado) return;
            e.preventDefault();
            this.reportarIncidente('intento_copiar', 'Intento bloqueado de cortar texto.');
        });

        document.addEventListener('paste', (e) => {
            if (this.bloqueado) return;
            e.preventDefault();
            this.reportarIncidente('intento_pegar', 'Intento bloqueado de pegar texto.');
        });

        document.addEventListener('contextmenu', (e) => {
            if (this.bloqueado) return;
            e.preventDefault();
            this.reportarIncidente('clic_derecho', 'Intento de abrir menú contextual con clic derecho.');
        });
    }

    registrarEventosTeclado() {
        const vaciarPortapapeles = () => {
            try {
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText('').catch(() => {});
                }
            } catch (err) {}
        };

        document.addEventListener('keydown', (e) => {
            if (this.bloqueado) return;

            const tecla = e.key ? e.key.toLowerCase() : '';
            const code = e.code ? e.code.toLowerCase() : '';
            const ctrl = e.ctrlKey || e.metaKey;
            const shift = e.shiftKey;
            const alt = e.altKey;

            // 1. Captura de pantalla
            if (e.key === 'PrintScreen' || code === 'printscreen' || e.key === 'Snapshot' || e.keyCode === 44) {
                e.preventDefault();
                vaciarPortapapeles();
                this.reportarIncidente('captura_pantalla', 'Intento de captura de pantalla (Tecla ImprPant / PrintScreen).');
                return;
            }

            // 2. Herramientas de recorte y captura por atajos (Shift+S en Windows, Cmd+Shift+3/4/5 en Mac)
            if ((ctrl || alt || e.metaKey) && shift && ['s', 'keys', '3', '4', '5'].includes(tecla || code)) {
                e.preventDefault();
                vaciarPortapapeles();
                this.reportarIncidente('captura_pantalla', 'Intento de captura o recorte de pantalla (Shift+S / Recortes).');
                return;
            }

            // 3. Atajos para invocar IA (Brave Leo Ctrl+B / Alt+B, Edge Copilot Ctrl+Shift+., etc.)
            if ((ctrl || alt) && (tecla === 'b' || code === 'keyb')) {
                e.preventDefault();
                this.reportarIncidente('asistente_ia', 'Intento de abrir asistente de IA en navegador (Ctrl+B / Alt+B / Brave Leo).');
                return;
            }

            if (ctrl && shift && (tecla === '.' || code === 'period')) {
                e.preventDefault();
                this.reportarIncidente('asistente_ia', 'Intento de abrir asistente de IA Copilot en Edge (Ctrl+Shift+.).');
                return;
            }

            if (alt && (tecla === 'c' || code === 'keyc')) {
                e.preventDefault();
                this.reportarIncidente('asistente_ia', 'Intento de abrir asistente de IA / Copilot (Alt+C).');
                return;
            }

            if ((ctrl || alt) && (code === 'space' || tecla === ' ' || tecla === 'space')) {
                e.preventDefault();
                this.reportarIncidente('asistente_ia', 'Intento de abrir lanzador de búsqueda o asistente de IA (Espacio).');
                return;
            }

            // Extensiones comunes de IA (ChatGPT, Monica, Merlin, Sider, etc.)
            if (ctrl && shift && ['l', 'o', 'e', 'y', 'a'].includes(tecla)) {
                e.preventDefault();
                this.reportarIncidente('asistente_ia', `Intento de abrir extensión de IA o herramienta externa (Ctrl+Shift+${tecla.toUpperCase()}).`);
                return;
            }

            // 4. Portapapeles alternativo (Shift+Insert, Ctrl+Insert, Shift+Delete)
            if (shift && (e.key === 'Insert' || code === 'insert')) {
                e.preventDefault();
                this.reportarIncidente('intento_pegar', 'Intento de pegar contenido externo (Shift+Insert).');
                return;
            }

            if (ctrl && (e.key === 'Insert' || code === 'insert')) {
                e.preventDefault();
                this.reportarIncidente('intento_copiar', 'Intento de copiar contenido del examen (Ctrl+Insert).');
                return;
            }

            if (shift && (e.key === 'Delete' || code === 'delete')) {
                e.preventDefault();
                this.reportarIncidente('intento_copiar', 'Intento de cortar contenido (Shift+Delete).');
                return;
            }

            // 5. DevTools y Teclas de función
            if (e.key === 'F12' || code === 'f12') {
                e.preventDefault();
                this.reportarIncidente('tecla_bloqueada', 'Intento de abrir herramientas de desarrollador (F12).');
                return;
            }

            if (e.key === 'F5' || code === 'f5') {
                e.preventDefault();
                this.reportarIncidente('tecla_bloqueada', 'Intento de recargar el examen (F5).');
                return;
            }

            if (e.key === 'F3' || code === 'f3') {
                e.preventDefault();
                this.reportarIncidente('tecla_bloqueada', 'Intento de buscar en la página (F3).');
                return;
            }

            // 6. Atajos con Ctrl / Cmd
            if (ctrl) {
                // Pegar
                if (tecla === 'v' || code === 'keyv') {
                    e.preventDefault();
                    this.reportarIncidente('intento_pegar', 'Atajo Ctrl+V (Pegar contenido externo) bloqueado.');
                    return;
                }
                // Copiar / Cortar
                if (['c', 'x'].includes(tecla) || ['keyc', 'keyx'].includes(code)) {
                    e.preventDefault();
                    this.reportarIncidente('intento_copiar', `Atajo Ctrl+${tecla.toUpperCase()} (Copiar/Cortar) bloqueado.`);
                    return;
                }
                // Seleccionar todo
                if (tecla === 'a' || code === 'keya') {
                    e.preventDefault();
                    this.reportarIncidente('intento_copiar', 'Atajo Ctrl+A (Seleccionar todo) bloqueado.');
                    return;
                }

                // Búsqueda en página
                if (['f', 'g'].includes(tecla)) {
                    e.preventDefault();
                    this.reportarIncidente('tecla_bloqueada', `Atajo de búsqueda Ctrl+${tecla.toUpperCase()} bloqueado.`);
                    return;
                }

                // Código fuente, guardar, imprimir, recargar
                if (['u', 's', 'p', 'r'].includes(tecla)) {
                    e.preventDefault();
                    if (tecla === 'p') vaciarPortapapeles();
                    this.reportarIncidente(tecla === 'p' ? 'captura_pantalla' : 'tecla_bloqueada', `Atajo Ctrl+${tecla.toUpperCase()} bloqueado.`);
                    return;
                }

                // Nuevas pestañas, ventanas, historial o descargas
                if (['n', 't', 'w', 'h', 'j'].includes(tecla)) {
                    e.preventDefault();
                    this.reportarIncidente('tecla_bloqueada', `Atajo de navegación Ctrl+${tecla.toUpperCase()} bloqueado.`);
                    return;
                }

                // Consola / Inspector DevTools (Ctrl+Shift+I/J/C/K)
                if (shift && ['i', 'j', 'c', 'k'].includes(tecla)) {
                    e.preventDefault();
                    this.reportarIncidente('tecla_bloqueada', `Atajo Ctrl+Shift+${tecla.toUpperCase()} (Inspector/Consola) bloqueado.`);
                    return;
                }
            }
        });

        // Captura reactiva de PrintScreen en keyup
        window.addEventListener('keyup', (e) => {
            if (this.bloqueado) return;
            if (e.key === 'PrintScreen' || (e.code && e.code.toLowerCase() === 'printscreen') || e.key === 'Snapshot' || e.keyCode === 44) {
                vaciarPortapapeles();
                this.reportarIncidente('captura_pantalla', 'Captura de pantalla detectada por el monitor (PrintScreen).');
            }
        });
    }

    registrarEventosPantallaCompleta() {
        if (!this.requierePantallaCompleta) return;

        const eventoFS = 'fullscreenchange' in document ? 'fullscreenchange' : ('webkitfullscreenchange' in document ? 'webkitfullscreenchange' : 'mozfullscreenchange');

        document.addEventListener(eventoFS, () => {
            const estaEnFS = !!(document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement);
            if (!estaEnFS && !this.bloqueado) {
                this.reportarIncidente('salida_pantalla_completa', 'Salida del modo de pantalla completa obligatoria.');
                document.getElementById('modal-requiere-fullscreen')?.classList.remove('hidden');
            } else if (estaEnFS) {
                document.getElementById('modal-requiere-fullscreen')?.classList.add('hidden');
            }
        });

        document.getElementById('btn-solicitar-fullscreen')?.addEventListener('click', () => this.solicitarPantallaCompleta());
    }

    solicitarPantallaCompleta() {
        const elem = document.documentElement;
        if (elem.requestFullscreen) {
            elem.requestFullscreen().catch(() => {});
        } else if (elem.webkitRequestFullscreen) {
            elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) {
            elem.msRequestFullscreen();
        }
    }

    iniciarProteccionSeleccion() {
        const style = document.createElement('style');
        style.innerHTML = `
            .bloquear-seleccion * {
                -webkit-user-select: none !important;
                -moz-user-select: none !important;
                -ms-user-select: none !important;
                user-select: none !important;
            }
        `;
        document.head.appendChild(style);
    }

    async reportarIncidente(tipoEvento, detalle) {
        if (this.bloqueado) return;

        const ahora = Date.now();
        if (ahora - this.ultimoIncidenteTiempo < this.cooldownMs && tipoEvento === 'desenfoque_ventana') {
            return;
        }
        this.ultimoIncidenteTiempo = ahora;

        try {
            const respuesta = await fetch(this.incidenteUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    tipo_evento: tipoEvento,
                    detalle: detalle,
                    marca_tiempo: new Date().toISOString()
                })
            });

            if (!respuesta.ok) return;

            const data = await respuesta.json();
            if (data.faltas !== undefined) {
                this.faltasActuales = data.faltas;
                this.actualizarUIFaltas();
            }

            if (data.anulado || data.status === 'anulado') {
                this.bloquearYAnularExamen(data);
                return;
            }

            if (data.status === 'advertencia') {
                this.mostrarModalAdvertencia(data);
            }
        } catch (error) {
            console.error('[Anti-Trampa] Error al registrar incidente:', error);
        }
    }

    mostrarModalAdvertencia(data) {
        const vidasRestantes = data.faltas_restantes !== undefined ? data.faltas_restantes : Math.max(0, this.maxFaltas - this.faltasActuales);
        const titulo = `💔 ¡Has perdido una vida de integridad! (${vidasRestantes} ${vidasRestantes === 1 ? 'restante' : 'restantes'})`;
        const mensaje = data.mensaje || 'Se ha detectado una acción que compromete la integridad del examen.';

        if (window.Swal) {
            Swal.fire({
                title: titulo,
                html: `
                    <div class="space-y-3 text-left text-xs sm:text-sm text-slate-600">
                        <p class="font-semibold text-slate-800">${mensaje}</p>
                        <div class="p-3 bg-rose-50 rounded-xl border border-rose-200 text-rose-800 flex items-start gap-2.5">
                            <i class="fas fa-heart-broken text-rose-600 text-base mt-0.5 flex-shrink-0"></i>
                            <div>
                                <span class="font-bold">Vidas restantes: ${vidasRestantes} de ${this.maxFaltas}</span>
                                <p class="text-[11px] text-rose-700 mt-0.5">Al perder las ${this.maxFaltas} vidas por faltas al protocolo, el examen se anulará automáticamente con nota 0.0.</p>
                            </div>
                        </div>
                    </div>
                `,
                icon: 'warning',
                confirmButtonText: 'Continuar evaluación',
                confirmButtonColor: '#39A900',
                allowOutsideClick: false,
                allowEscapeKey: false,
                customClass: {
                    popup: 'rounded-3xl border border-rose-200 shadow-2xl',
                    confirmButton: 'rounded-xl font-bold text-xs px-5 py-2.5'
                }
            }).then(() => {
                if (this.requierePantallaCompleta && !document.fullscreenElement) {
                    this.solicitarPantallaCompleta();
                }
            });
        } else {
            alert(`${titulo}\n\n${mensaje}\n\nVidas restantes: ${vidasRestantes} de ${this.maxFaltas}.`);
        }
    }

    bloquearYAnularExamen(data) {
        this.bloqueado = true;
        const form = document.querySelector(this.formSelector);
        if (form) {
            form.querySelectorAll('input, button, select, textarea').forEach(el => el.disabled = true);
        }

        const mensaje = data.mensaje || 'Has alcanzado el límite de advertencias. El examen ha sido anulado.';
        const redirectUrl = data.redirect || window.location.href;

        if (window.Swal) {
            Swal.fire({
                title: '⛔ Examen Anulado',
                html: `
                    <div class="space-y-4 text-center">
                        <div class="w-16 h-16 mx-auto rounded-3xl bg-rose-100 text-rose-600 flex items-center justify-center text-3xl">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <p class="text-sm font-bold text-slate-800">${mensaje}</p>
                        <p class="text-xs text-slate-500">
                            Tus respuestas han sido bloqueadas y tu calificación ha sido fijada en 0.0.
                        </p>
                    </div>
                `,
                icon: 'error',
                showConfirmButton: true,
                confirmButtonText: 'Ver Detalle del Intento',
                confirmButtonColor: '#e11d48',
                allowOutsideClick: false,
                allowEscapeKey: false,
                timer: 4000,
                timerProgressBar: true,
                customClass: {
                    popup: 'rounded-3xl border border-rose-200 shadow-2xl',
                    confirmButton: 'rounded-xl font-bold text-xs px-5 py-2.5'
                }
            }).then(() => {
                window.location.href = redirectUrl;
            });
        } else {
            alert(`EXAMEN ANULADO: ${mensaje}`);
            window.location.href = redirectUrl;
        }
    }
}

window.SisigAntiTrampa = SisigAntiTrampa;
