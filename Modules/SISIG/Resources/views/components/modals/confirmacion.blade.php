<!-- Modal Dinámico Universal de Confirmación SISIG (Eliminar, Desactivar, Activar) -->
<div id="modal-confirmacion-global" class="fixed inset-0 z-[150] hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl border border-slate-200 animate-scale-in relative text-center">
        
        <!-- Botón cerrar X -->
        <button type="button" onclick="cerrarModalConfirmacion()" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 p-2 rounded-xl hover:bg-slate-100 transition-colors cursor-pointer">
            <i class="fas fa-times text-sm"></i>
        </button>

        <!-- Ícono dinámico -->
        <div id="modal-confirm-icon-wrap" class="text-3xl mx-auto mb-3">
            <i id="modal-confirm-icon" class="fas fa-trash-alt"></i>
        </div>

        <!-- Título y descripción -->
        <h3 id="modal-confirm-title" class="text-xl font-black text-slate-800 tracking-tight"></h3>
        <p id="modal-confirm-desc" class="text-xs text-slate-500 mt-1.5 max-w-xs mx-auto leading-relaxed"></p>

        <!-- Elemento seleccionado destacado -->
        <div id="modal-confirm-item-wrap" class="mt-4 p-3.5 rounded-2xl bg-slate-50 border border-slate-200/90 text-left">
            <div class="flex items-center gap-1.5 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">
                <i id="modal-confirm-item-tag-icon" class="fas fa-tag text-slate-400"></i>
                <span id="modal-confirm-item-tag">Elemento seleccionado</span>
            </div>
            <p id="modal-confirm-item-text" class="text-xs font-bold text-slate-700 leading-snug line-clamp-2"></p>
        </div>

        <!-- Advertencia adicional opcional -->
        <div id="modal-confirm-warn-wrap" class="mt-3 flex items-center justify-center gap-1.5 text-[11px] font-medium text-rose-500 hidden">
            <i class="fas fa-exclamation-triangle text-xs flex-shrink-0"></i>
            <span id="modal-confirm-warn-text"></span>
        </div>

        <!-- Botones de Acción -->
        <div class="mt-6 flex items-center justify-center gap-3">
            <button type="button" onclick="cerrarModalConfirmacion()" class="flex-1 py-2.5 rounded-xl font-bold text-xs text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all cursor-pointer">
                Cancelar
            </button>
            <button type="button" id="modal-confirm-btn" onclick="ejecutarAccionConfirmada()" class="flex-1 py-2.5 rounded-xl font-bold text-xs text-white transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-md">
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
     * @param {string} [config.tipo='danger'] - 'danger' (rojo), 'warning' (ámbar), 'success' (verde)
     * @param {string} config.titulo - Título principal
     * @param {string} config.descripcion - Explicación de la acción
     * @param {string} [config.item] - Nombre o etiqueta del elemento afectado
     * @param {string} [config.itemTag='Elemento seleccionado']
     * @param {string} [config.advertencia] - Mensaje de advertencia adicional
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
        document.getElementById('modal-confirm-desc').textContent = config.descripcion || 'Esta acción se ejecutará de forma permanente.';

        // Ícono e iluminación según el tipo
        const iconWrap = document.getElementById('modal-confirm-icon-wrap');
        const iconEl = document.getElementById('modal-confirm-icon');
        const confirmBtn = document.getElementById('modal-confirm-btn');
        const btnIcon = document.getElementById('modal-confirm-btn-icon');
        const warnWrap = document.getElementById('modal-confirm-warn-wrap');
        const warnText = document.getElementById('modal-confirm-warn-text');

        // Reset de clases dinámicas del botón
        confirmBtn.className = 'flex-1 py-2.5 rounded-xl font-bold text-xs text-white transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-md ';

        if (tipo === 'danger') {
            iconWrap.className = 'text-rose-500 text-3xl mx-auto mb-3';
            iconEl.className = 'fas fa-trash-alt';
            confirmBtn.className += 'bg-rose-600 hover:bg-rose-700 shadow-rose-600/20';
            btnIcon.className = 'fas ' + (config.btnIcon || 'fa-trash-alt') + ' text-xs';
        } else if (tipo === 'warning') {
            iconWrap.className = 'text-amber-500 text-3xl mx-auto mb-3';
            iconEl.className = 'fas fa-pause-circle';
            confirmBtn.className += 'bg-amber-600 hover:bg-amber-700 shadow-amber-600/20';
            btnIcon.className = 'fas ' + (config.btnIcon || 'fa-user-slash') + ' text-xs';
        } else {
            iconWrap.className = 'text-emerald-500 text-3xl mx-auto mb-3';
            iconEl.className = 'fas fa-check-circle';
            confirmBtn.className += 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-600/20';
            btnIcon.className = 'fas ' + (config.btnIcon || 'fa-check') + ' text-xs';
        }

        // Elemento seleccionado
        const itemWrap = document.getElementById('modal-confirm-item-wrap');
        if (config.item) {
            document.getElementById('modal-confirm-item-tag').textContent = config.itemTag || 'Elemento seleccionado';
            document.getElementById('modal-confirm-item-text').textContent = config.item;
            itemWrap.classList.remove('hidden');
        } else {
            itemWrap.classList.add('hidden');
        }

        // Advertencia
        if (config.advertencia) {
            warnText.textContent = config.advertencia;
            warnWrap.classList.remove('hidden');
            warnWrap.className = `mt-3 flex items-center justify-center gap-1.5 text-[11px] font-medium ${tipo === 'danger' ? 'text-rose-500' : 'text-amber-600'}`;
        } else {
            warnWrap.classList.add('hidden');
        }

        // Botón
        document.getElementById('modal-confirm-btn-label').textContent = config.btnTexto || 'Confirmar';
        confirmBtn.disabled = false;

        modal.classList.remove('hidden');
    };

    window.cerrarModalConfirmacion = function() {
        const modal = document.getElementById('modal-confirmacion-global');
        if (modal) modal.classList.add('hidden');
        window._accionConfirmacion = null;
    };

    window.ejecutarAccionConfirmada = function() {
        if (!window._accionConfirmacion) return;

        const btn = document.getElementById('modal-confirm-btn');
        const originalHtml = btn.innerHTML;
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
