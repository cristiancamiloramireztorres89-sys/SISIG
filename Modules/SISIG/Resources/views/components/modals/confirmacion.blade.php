<!-- Modal Dinámico Universal de Confirmación SISIG (Eliminar, Desactivar, Activar) -->
<div id="modal-confirmacion-global" class="fixed inset-0 z-[150] hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl border border-slate-200 animate-scale-in relative text-center">
        
        <!-- Botón cerrar X -->
        <button type="button" onclick="cerrarModalConfirmacion()" class="absolute top-4 right-4 w-8 h-8 rounded-xl flex items-center justify-center text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all cursor-pointer" title="Cerrar modal">
            <i class="fas fa-times text-xs leading-none"></i>
        </button>

        <!-- Ícono dinámico con contenedor badge estilizado -->
        <div id="modal-confirm-icon-wrap" class="w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center text-2xl transition-all shadow-inner">
            <i id="modal-confirm-icon" class="fas fa-trash-alt"></i>
        </div>

        <!-- Título y descripción -->
        <h3 id="modal-confirm-title" class="text-xl font-black text-slate-800 tracking-tight"></h3>
        <p id="modal-confirm-desc" class="text-xs text-slate-500 mt-2 max-w-sm mx-auto leading-relaxed"></p>

        <!-- Elemento seleccionado destacado -->
        <div id="modal-confirm-item-wrap" class="mt-4 p-3.5 rounded-2xl bg-slate-50 border border-slate-200/90 text-left">
            <div class="flex items-center gap-1.5 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">
                <i id="modal-confirm-item-tag-icon" class="fas fa-tag text-slate-400"></i>
                <span id="modal-confirm-item-tag">Elemento seleccionado</span>
            </div>
            <p id="modal-confirm-item-text" class="text-sm font-black text-slate-800 leading-snug break-words"></p>
        </div>

        <!-- Advertencia o Nota explicativa en caja estilizada (sin descuadres) -->
        <div id="modal-confirm-warn-wrap" class="mt-3.5 p-3 rounded-2xl border flex items-start gap-2.5 text-left hidden transition-all">
            <div id="modal-confirm-warn-icon-box" class="w-6 h-6 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5 text-xs">
                <i id="modal-confirm-warn-icon" class="fas fa-exclamation-triangle"></i>
            </div>
            <p id="modal-confirm-warn-text" class="text-xs font-semibold leading-relaxed m-0 p-0 text-left flex-1"></p>
        </div>

        <!-- Botones de Acción -->
        <div class="mt-6 flex items-center justify-center gap-3">
            <button type="button" onclick="cerrarModalConfirmacion()" class="flex-1 py-3 px-4 rounded-xl font-bold text-xs sm:text-sm text-slate-600 bg-slate-100 hover:bg-slate-200 hover:text-slate-900 border border-slate-200/80 transition-all cursor-pointer flex items-center justify-center">
                Cancelar
            </button>
            <button type="button" id="modal-confirm-btn" onclick="ejecutarAccionConfirmada()" class="flex-1 py-3 px-4 rounded-xl font-bold text-xs sm:text-sm text-white transition-all flex items-center justify-center gap-2 cursor-pointer shadow-md hover:shadow-lg active:scale-95">
                <i id="modal-confirm-btn-icon" class="fas fa-trash-alt text-xs"></i>
                <span id="modal-confirm-btn-label">Confirmar</span>
            </button>
        </div>
    </div>
</div>

<script>
    window._accionConfirmacion = null;

    /**
     * Abre el modal universal de confirmación configurado dinámicamente.
     * @param {Object} config
     * @param {string} [config.tipo='danger'] - 'danger' (rojo), 'warning' (ámbar), 'info'/'success' (verde)
     * @param {string} config.titulo - Título principal
     * @param {string} config.descripcion - Explicación de la acción
     * @param {string} [config.item] - Nombre o etiqueta del elemento afectado
     * @param {string} [config.itemTag='Elemento seleccionado']
     * @param {string} [config.advertencia] - Mensaje de advertencia o información adicional
     * @param {string} [config.btnTexto='Confirmar']
     * @param {string} [config.btnIcon='fa-check']
     * @param {HTMLFormElement|Function} config.onConfirm - Formulario a enviar o callback a ejecutar
     */
    window.abrirModalConfirmacion = function(config) {
        const modal = document.getElementById('modal-confirmacion-global');
        if (!modal) return;

        const tipo = config.tipo || 'danger';
        window._accionConfirmacion = config.onConfirm || config.form || null;

        // Título y descripción
        document.getElementById('modal-confirm-title').textContent = config.titulo || '¿Confirmar acción?';
        document.getElementById('modal-confirm-desc').textContent = config.descripcion || 'Esta acción se ejecutará en la plataforma.';

        // Íconos y contenedores
        const iconWrap = document.getElementById('modal-confirm-icon-wrap');
        const iconEl = document.getElementById('modal-confirm-icon');
        const confirmBtn = document.getElementById('modal-confirm-btn');
        const btnIcon = document.getElementById('modal-confirm-btn-icon');
        const warnWrap = document.getElementById('modal-confirm-warn-wrap');
        const warnIconBox = document.getElementById('modal-confirm-warn-icon-box');
        const warnIcon = document.getElementById('modal-confirm-warn-icon');
        const warnText = document.getElementById('modal-confirm-warn-text');

        // Reset base de botón
        confirmBtn.className = 'flex-1 py-3 px-4 rounded-xl font-bold text-xs sm:text-sm text-white transition-all flex items-center justify-center gap-2 cursor-pointer shadow-md active:scale-95 ';

        if (tipo === 'danger') {
            // Peligro / Eliminación permanente
            iconWrap.className = 'w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center text-2xl bg-rose-50 text-rose-600 border border-rose-100 ring-8 ring-rose-50/60 shadow-inner';
            iconEl.className = 'fas fa-trash-alt';
            confirmBtn.className += 'bg-rose-600 hover:bg-rose-700 shadow-rose-600/25';
            btnIcon.className = 'fas ' + (config.btnIcon || 'fa-trash-alt') + ' text-xs';

            warnWrap.className = 'mt-3.5 p-3 rounded-2xl border flex items-start gap-2.5 text-left bg-rose-50/90 border-rose-200/80';
            warnIconBox.className = 'w-6 h-6 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5 bg-rose-100 text-rose-600 text-xs';
            warnIcon.className = 'fas fa-exclamation-triangle';
            warnText.className = 'text-xs font-semibold leading-relaxed text-rose-800 text-left flex-1';

        } else if (tipo === 'warning') {
            // Pausa / Desactivación temporal
            iconWrap.className = 'w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center text-2xl bg-amber-50 text-amber-600 border border-amber-100 ring-8 ring-amber-50/60 shadow-inner';
            iconEl.className = 'fas fa-pause';
            confirmBtn.className += 'bg-amber-600 hover:bg-amber-700 shadow-amber-600/25';
            btnIcon.className = 'fas ' + (config.btnIcon || 'fa-ban') + ' text-xs';

            warnWrap.className = 'mt-3.5 p-3 rounded-2xl border flex items-start gap-2.5 text-left bg-amber-50/90 border-amber-200/80';
            warnIconBox.className = 'w-6 h-6 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5 bg-amber-100 text-amber-600 text-xs';
            warnIcon.className = 'fas fa-exclamation-circle';
            warnText.className = 'text-xs font-semibold leading-relaxed text-amber-800 text-left flex-1';

        } else {
            // Éxito / Reactivación / Confirmación positiva
            iconWrap.className = 'w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center text-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 ring-8 ring-emerald-50/60 shadow-inner';
            iconEl.className = 'fas fa-check';
            confirmBtn.className += 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-600/25';
            btnIcon.className = 'fas ' + (config.btnIcon || 'fa-check') + ' text-xs';

            warnWrap.className = 'mt-3.5 p-3 rounded-2xl border flex items-start gap-2.5 text-left bg-emerald-50/90 border-emerald-200/80';
            warnIconBox.className = 'w-6 h-6 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5 bg-emerald-100 text-emerald-600 text-xs';
            warnIcon.className = 'fas fa-info-circle';
            warnText.className = 'text-xs font-semibold leading-relaxed text-emerald-800 text-left flex-1';
        }

        // Elemento seleccionado
        const itemWrap = document.getElementById('modal-confirm-item-wrap');
        if (config.item) {
            document.getElementById('modal-confirm-item-tag').textContent = config.itemTag || 'Elemento seleccionado';
            // Limpiar comillas innecesarias
            const itemLimpio = String(config.item).replace(/^["'«“]|["'»”]$/g, '').trim();
            document.getElementById('modal-confirm-item-text').textContent = itemLimpio;
            itemWrap.classList.remove('hidden');
        } else {
            itemWrap.classList.add('hidden');
        }

        // Advertencia o nota explicativa
        if (config.advertencia) {
            warnText.textContent = config.advertencia;
            warnWrap.classList.remove('hidden');
        } else {
            warnWrap.classList.add('hidden');
        }

        // Botón
        document.getElementById('modal-confirm-btn-label').textContent = config.btnTexto || 'Confirmar';
        confirmBtn.disabled = false;

        modal.classList.remove('hidden');
        if (window.lockBodyScroll) window.lockBodyScroll(true);
    };

    window.cerrarModalConfirmacion = function() {
        const modal = document.getElementById('modal-confirmacion-global');
        if (modal) modal.classList.add('hidden');
        window._accionConfirmacion = null;
        if (window.lockBodyScroll) window.lockBodyScroll(false);
    };

    window.ejecutarAccionConfirmada = function() {
        if (!window._accionConfirmacion) return;

        const btn = document.getElementById('modal-confirm-btn');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin text-xs"></i> Procesando...';
        btn.disabled = true;

        if (typeof window._accionConfirmacion === 'function') {
            window._accionConfirmacion();
        } else if (window._accionConfirmacion.submit) {
            window._accionConfirmacion.submit();
        }
    };

    // Cerrar con tecla Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            window.cerrarModalConfirmacion();
        }
    });
</script>
