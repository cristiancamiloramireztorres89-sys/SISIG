/**
 * Sistema de Integridad Académica y Monitoreo Anti-Trampa - SISIG (SENA Empresa)
 * 
 * Gestiona de forma estricta y reactiva la supervisión del aprendiz durante la presentación de pruebas:
 * - Detección de pérdida de foco y cambio de pestañas (visibilitychange, blur/focus)
 * - Restricción de portapapeles (copy, cut, paste) y clic derecho (contextmenu)
 * - Bloqueo de atajos de teclado no autorizados (DevTools, inspección, captura)
 * - Requerimiento y detección de salida de pantalla completa (fullscreenchange)
 * - Notificación asíncrona de incidentes hacia el backend con token CSRF
 * - Anulación o bloqueo automático al superar el umbral configurado de advertencias
 */

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
        this.cooldownMs = 1200; // Evitar duplicación de eventos de foco rápidos

        this.init();
    }

    init() {
        if (!this.intentoId || !this.incidenteUrl) {
            console.error('[SISIG Anti-Trampa] Configuración incompleta: intentoId o incidenteUrl faltantes.');
            return;
        }

        this.actualizarUIFaltas();
        this.registrarEventosFoco();
        this.registrarEventosPortapapeles();
        this.registrarEventosTeclado();
        this.registrarEventosPantallaCompleta();
        this.iniciarProteccionSeleccion();

        console.log('[SISIG Anti-Trampa] Sistema de integridad activo. Máximo de faltas permitidas:', this.maxFaltas);
    }

    /**
     * Actualiza los indicadores visuales de faltas en la interfaz.
     */
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

        // Actualizar estado volumétrico y capas 3D de cada corazón
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

                if (body) {
                    const fillVal = body.getAttribute('fill') || '';
                    if (fillVal.includes('quiz')) {
                        body.setAttribute('fill', `url(#grad-heart-lost-quiz-${v})`);
                    } else {
                        body.setAttribute('fill', `url(#grad-heart-lost-${v})`);
                    }
                }
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

                if (body) {
                    const fillVal = body.getAttribute('fill') || '';
                    if (fillVal.includes('quiz')) {
                        body.setAttribute('fill', `url(#grad-heart-active-quiz-${v})`);
                    } else {
                        body.setAttribute('fill', `url(#grad-heart-active-${v})`);
                    }
                }
                if (depth) depth.setAttribute('fill', '#4c0519');
                if (groundShadow) groundShadow.setAttribute('opacity', '0.6');
                if (rimLight) rimLight.classList.remove('hidden');
                if (shine) shine.classList.remove('hidden');
                if (shineMini) shineMini.classList.remove('hidden');
                if (crack) crack.classList.add('hidden');
                if (crackHighlight) crackHighlight.classList.add('hidden');
            }
        }

        const contadorTexto = document.getElementById('anti-trampa-contador-texto');
        if (contadorTexto) {
            contadorTexto.textContent = `${this.faltasActuales}/${this.maxFaltas}`;
        }

        const barraProgreso = document.getElementById('anti-trampa-barra-faltas');
        if (barraProgreso) {
            const porcentaje = Math.min(100, (this.faltasActuales / this.maxFaltas) * 100);
            barraProgreso.style.width = `${porcentaje}%`;
            
            if (this.faltasActuales === 0) {
                barraProgreso.className = 'h-2 rounded-full transition-all duration-300 bg-emerald-500';
            } else if (this.faltasActuales === 1) {
                barraProgreso.className = 'h-2 rounded-full transition-all duration-300 bg-amber-400';
            } else if (this.faltasActuales === 2) {
                barraProgreso.className = 'h-2 rounded-full transition-all duration-300 bg-orange-500 animate-pulse';
            } else {
                barraProgreso.className = 'h-2 rounded-full transition-all duration-300 bg-rose-600';
            }
        }

        // Puntos indicadores individuales
        for (let i = 1; i <= this.maxFaltas; i++) {
            const dot = document.getElementById(`anti-trampa-dot-${i}`);
            if (dot) {
                if (i <= this.faltasActuales) {
                    dot.className = 'w-3 h-3 rounded-full bg-rose-500 shadow-sm shadow-rose-500/50 scale-110 transition-all';
                } else {
                    dot.className = 'w-3 h-3 rounded-full bg-slate-200 transition-all';
                }
            }
        }
    }

    /**
     * Detección de cambio de pestaña y pérdida de foco de la ventana.
     */
    registrarEventosFoco() {
        // API VisibilityChange (Detección de minimizado o cambio de pestaña)
        document.addEventListener('visibilitychange', () => {
            if (document.hidden && !this.bloqueado) {
                this.reportarIncidente('cambio_pestana', 'El aprendiz cambió de pestaña o minimizó la ventana del examen.');
            }
        });

        // Evento blur en window
        window.addEventListener('blur', () => {
            if (!this.bloqueado) {
                const ahora = Date.now();
                if (ahora - this.ultimoIncidenteTiempo > this.cooldownMs) {
                    this.reportarIncidente('desenfoque_ventana', 'El navegador perdió el foco (posible interacción con otra aplicación).');
                }
            }
        });
    }

    /**
     * Bloqueo estricto de eventos de portapapeles y menú contextual.
     */
    registrarEventosPortapapeles() {
        const contenedor = document.getElementById('contenedor-examen') || document.body;

        // Bloquear copiar
        document.addEventListener('copy', (e) => {
            if (this.bloqueado) return;
            e.preventDefault();
            this.reportarIncidente('intento_copiar', 'Intento no autorizado de copiar contenido del examen.');
        });

        // Bloquear cortar
        document.addEventListener('cut', (e) => {
            if (this.bloqueado) return;
            e.preventDefault();
            this.reportarIncidente('intento_copiar', 'Intento no autorizado de cortar contenido del examen.');
        });

        // Bloquear pegar
        document.addEventListener('paste', (e) => {
            if (this.bloqueado) return;
            e.preventDefault();
            this.reportarIncidente('intento_pegar', 'Intento no autorizado de pegar información externa.');
        });

        // Bloquear clic derecho (menú contextual)
        document.addEventListener('contextmenu', (e) => {
            if (this.bloqueado) return;
            e.preventDefault();
            this.reportarIncidente('clic_derecho', 'Intento de abrir menú contextual con clic derecho.');
        });
    }

    /**
     * Bloqueo de atajos de teclado peligrosos (inspección, impresión, DevTools, búsqueda).
     */
    registrarEventosTeclado() {
        document.addEventListener('keydown', (e) => {
            if (this.bloqueado) return;

            const tecla = e.key ? e.key.toLowerCase() : '';
            const ctrl = e.ctrlKey || e.metaKey;
            const shift = e.shiftKey;
            const alt = e.altKey;

            // F12 (Herramientas de Desarrollador)
            if (e.key === 'F12') {
                e.preventDefault();
                this.reportarIncidente('tecla_bloqueada', 'Intento de abrir herramientas de desarrollador (F12).');
                return;
            }

            // Tecla ImprPant / PrintScreen
            if (e.key === 'PrintScreen') {
                e.preventDefault();
                this.reportarIncidente('tecla_bloqueada', 'Intento de captura de pantalla (PrintScreen).');
                return;
            }

            // Atajos con Ctrl / Cmd
            if (ctrl) {
                // Ctrl + C / Ctrl + X / Ctrl + V
                if (['c', 'x', 'v'].includes(tecla)) {
                    e.preventDefault();
                    this.reportarIncidente(`intento_${tecla === 'v' ? 'pegar' : 'copiar'}`, `Atajo Ctrl+${tecla.toUpperCase()} bloqueado.`);
                    return;
                }

                // Ctrl + U (Ver código fuente)
                if (tecla === 'u') {
                    e.preventDefault();
                    this.reportarIncidente('tecla_bloqueada', 'Intento de ver el código fuente (Ctrl+U).');
                    return;
                }

                // Ctrl + S (Guardar página)
                if (tecla === 's') {
                    e.preventDefault();
                    this.reportarIncidente('tecla_bloqueada', 'Intento de guardar la página (Ctrl+S).');
                    return;
                }

                // Ctrl + P (Imprimir)
                if (tecla === 'p') {
                    e.preventDefault();
                    this.reportarIncidente('tecla_bloqueada', 'Intento de imprimir el examen (Ctrl+P).');
                    return;
                }

                // Ctrl + Shift + I/J/C (DevTools)
                if (shift && ['i', 'j', 'c'].includes(tecla)) {
                    e.preventDefault();
                    this.reportarIncidente('tecla_bloqueada', `Intento de abrir consola/inspector (Ctrl+Shift+${tecla.toUpperCase()}).`);
                    return;
                }
            }
        });
    }

    /**
     * Supervisión y exigencia de Pantalla Completa.
     */
    registrarEventosPantallaCompleta() {
        if (!this.requierePantallaCompleta) return;

        const eventoFS = 'fullscreenchange' in document ? 'fullscreenchange' : ('webkitfullscreenchange' in document ? 'webkitfullscreenchange' : 'mozfullscreenchange');

        document.addEventListener(eventoFS, () => {
            const estaEnPantallaCompleta = !!(document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement);

            const indicadorFS = document.getElementById('anti-trampa-fs-badge');
            if (indicadorFS) {
                if (estaEnPantallaCompleta) {
                    indicadorFS.innerHTML = '<i class="fas fa-expand text-emerald-500 mr-1.5"></i><span class="text-emerald-700 font-bold">Pantalla completa activa</span>';
                    indicadorFS.className = 'inline-flex items-center text-xs px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200';
                } else {
                    indicadorFS.innerHTML = '<i class="fas fa-compress text-rose-500 mr-1.5 animate-pulse"></i><span class="text-rose-700 font-bold">Pantalla completa desactivada</span>';
                    indicadorFS.className = 'inline-flex items-center text-xs px-3 py-1 rounded-full bg-rose-50 border border-rose-200';
                }
            }

            if (!estaEnPantallaCompleta && !this.bloqueado) {
                this.reportarIncidente('salida_pantalla_completa', 'El aprendiz salió del modo de pantalla completa obligatoria.');
                this.mostrarModalPantallaCompleta();
            } else if (estaEnPantallaCompleta) {
                this.ocultarModalPantallaCompleta();
            }
        });

        // Botón manual de solicitud de pantalla completa
        const btnSolicitarFS = document.getElementById('btn-solicitar-fullscreen');
        if (btnSolicitarFS) {
            btnSolicitarFS.addEventListener('click', () => this.solicitarPantallaCompleta());
        }
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

    mostrarModalPantallaCompleta() {
        const modal = document.getElementById('modal-requiere-fullscreen');
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    ocultarModalPantallaCompleta() {
        const modal = document.getElementById('modal-requiere-fullscreen');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    /**
     * Deshabilita la selección de texto en el examen.
     */
    iniciarProteccionSeleccion() {
        const style = document.createElement('style');
        style.innerHTML = `
            .examen-protegido {
                -webkit-user-select: none !important;
                -moz-user-select: none !important;
                -ms-user-select: none !important;
                user-select: none !important;
            }
        `;
        document.head.appendChild(style);

        const contenedor = document.getElementById('contenedor-examen');
        if (contenedor) {
            contenedor.classList.add('examen-protegido');
        }
    }

    /**
     * Envía de forma asíncrona la infracción hacia el servidor.
     */
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

            if (!respuesta.ok) {
                console.warn('[SISIG Anti-Trampa] Error de respuesta HTTP al reportar falta:', respuesta.status);
                return;
            }

            const data = await respuesta.json();
            this.procesarRespuestaServidor(data, tipoEvento);

        } catch (error) {
            console.error('[SISIG Anti-Trampa] Error de conexión con el monitor de seguridad:', error);
        }
    }

    /**
     * Procesa la directiva enviada por el servidor tras auditar la falta.
     */
    procesarRespuestaServidor(data, tipoEvento) {
        if (data.faltas !== undefined) {
            this.faltasActuales = data.faltas;
            this.actualizarUIFaltas();
        }

        // Si el examen fue ANULADO automáticamente por el servidor
        if (data.anulado || data.status === 'anulado') {
            this.bloquearYAnularExamen(data);
            return;
        }

        // Si fue una advertencia válida
        if (data.status === 'advertencia') {
            this.mostrarModalAdvertencia(data);
        }
    }

    /**
     * Notificación visual de advertencia tras una falta.
     */
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
                    popup: 'rounded-3xl border border-amber-100 shadow-2xl',
                    confirmButton: 'rounded-xl font-bold text-xs px-5 py-2.5'
                }
            }).then(() => {
                if (this.requierePantallaCompleta && !document.fullscreenElement) {
                    this.solicitarPantallaCompleta();
                }
            });
        } else {
            alert(`${titulo}\n\n${mensaje}\n\nAdvertencias restantes: ${restantes}. Si alcanzas ${this.maxFaltas}, el examen se anulará.`);
        }
    }

    /**
     * Bloquea la interfaz de examen y envía o redirige tras anulación definitiva.
     */
    bloquearYAnularExamen(data) {
        this.bloqueado = true;

        // Deshabilitar todos los inputs, radios y botones del examen
        const form = document.querySelector(this.formSelector);
        if (form) {
            const inputs = form.querySelectorAll('input, button, select, textarea');
            inputs.forEach(el => el.disabled = true);
        }

        const mensaje = data.mensaje || 'Has alcanzado el límite permitido de advertencias. El examen ha sido anulado.';
        const redirectUrl = data.redirect || window.location.href;

        if (window.Swal) {
            Swal.fire({
                title: '⛔ Examen Anulado',
                html: `
                    <div class="space-y-4 text-center">
                        <div class="w-16 h-16 mx-auto rounded-3xl bg-rose-100 flex items-center justify-center text-rose-600 text-3xl">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <p class="text-sm font-bold text-slate-800">${mensaje}</p>
                        <p class="text-xs text-slate-500">
                            Tus respuestas han sido bloqueadas y tu calificación ha sido fijada en 0.0 conforme al reglamento del Centro.
                        </p>
                        <div class="text-[11px] font-bold text-rose-600 bg-rose-50 p-2.5 rounded-xl border border-rose-200">
                            Redirigiendo al resumen de la prueba en unos segundos...
                        </div>
                    </div>
                `,
                icon: 'error',
                showConfirmButton: true,
                confirmButtonText: 'Ver Detalle del Intento',
                confirmButtonColor: '#e11d48',
                allowOutsideClick: false,
                allowEscapeKey: false,
                timer: 4500,
                timerProgressBar: true,
                customClass: {
                    popup: 'rounded-3xl border border-rose-200 shadow-2xl',
                    confirmButton: 'rounded-xl font-bold text-xs px-5 py-2.5'
                }
            }).then(() => {
                window.location.href = redirectUrl;
            });
        } else {
            alert(`EXAMEN ANULADO\n\n${mensaje}`);
            window.location.href = redirectUrl;
        }
    }
}

window.SisigAntiTrampa = SisigAntiTrampa;
