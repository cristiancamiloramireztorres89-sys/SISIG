/**
 * ==========================================================================
 * SISIG - GESTIÓN Y EDICIÓN DE CONTENIDOS DIDÁCTICOS
 * Lógica de frontend para Quill.js, Cropper.js y Manipulación de Imágenes
 * ==========================================================================
 */

(function () {
    'use strict';

    // Variables de estado del recortador de fondo
    var cropperFondoInstance = null;
    var currentFondoRawSrc = null;

    // Funciones globales para control del modal de fondo (accesibles desde botones HTML)
    window.abrirModalConfigFondo = function () {
        var modal = document.getElementById('modalConfigFondoContenido');
        if (modal) {
            modal.style.display = 'flex';
            if (window.lockBodyScroll) window.lockBodyScroll(true);
        }
    };

    window.cerrarModalConfigFondo = function () {
        var modal = document.getElementById('modalConfigFondoContenido');
        if (modal) {
            modal.style.display = 'none';
            if (window.lockBodyScroll) window.lockBodyScroll(false);
        }
    };

    window.iniciarCropperFondo = function (imageSrc) {
        window.cerrarModalConfigFondo();

        var modalRecortarFondo = document.getElementById('modalRecortarFondo');
        var cropperFondoTarget = document.getElementById('cropper-fondo-target');

        if (modalRecortarFondo && cropperFondoTarget) {
            cropperFondoTarget.src = imageSrc;
            modalRecortarFondo.style.display = 'flex';
            if (window.lockBodyScroll) window.lockBodyScroll(true);

            if (cropperFondoInstance) {
                cropperFondoInstance.destroy();
            }

            cropperFondoInstance = new Cropper(cropperFondoTarget, {
                aspectRatio: 16 / 9,
                viewMode: 1,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                autoCropArea: 0.9,
                responsive: true,
                background: false
            });
        }
    };

    window.cropperFondoRotar = function (grados) {
        if (cropperFondoInstance) {
            cropperFondoInstance.rotate(grados);
        }
    };

    window.cropperFondoZoom = function (ratio) {
        if (cropperFondoInstance) {
            cropperFondoInstance.zoom(ratio);
        }
    };

    window.cancelarRecorteFondo = function () {
        var modalRecortarFondo = document.getElementById('modalRecortarFondo');
        if (modalRecortarFondo) {
            modalRecortarFondo.style.display = 'none';
            if (window.lockBodyScroll) window.lockBodyScroll(false);
        }
        if (cropperFondoInstance) {
            cropperFondoInstance.destroy();
            cropperFondoInstance = null;
        }
        var inputArchivo = document.getElementById('input-archivo-fondo');
        if (inputArchivo) inputArchivo.value = '';
    };

    window.confirmarRecorteFondo = function () {
        if (!cropperFondoInstance) return;

        var canvas = cropperFondoInstance.getCroppedCanvas({
            width: 1600,
            height: 900,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high'
        });

        var croppedBase64 = canvas.toDataURL('image/png');

        var inputHiddenFondo = document.getElementById('fondo_imagen_recortada');
        if (inputHiddenFondo) inputHiddenFondo.value = croppedBase64;

        var inputHiddenQuitar = document.getElementById('quitar_fondo');
        if (inputHiddenQuitar) inputHiddenQuitar.value = '0';

        var previewMiniatura = document.getElementById('preview-fondo-miniatura');
        if (previewMiniatura) previewMiniatura.src = croppedBase64;

        var dropzone = document.getElementById('dropzone-fondo-modulo');
        if (dropzone) dropzone.classList.add('hidden');

        var previewContainer = document.getElementById('preview-fondo-container');
        if (previewContainer) previewContainer.classList.remove('hidden');

        var editorFondoCapa = document.getElementById('editor-fondo-capa');
        var sliderOpacidad = document.getElementById('slider-opacidad-control');
        if (editorFondoCapa) {
            editorFondoCapa.style.backgroundImage = 'url("' + croppedBase64 + '")';
            var op = sliderOpacidad ? sliderOpacidad.value : 20;
            editorFondoCapa.style.opacity = (op / 100);
        }

        var modalRecortarFondo = document.getElementById('modalRecortarFondo');
        if (modalRecortarFondo) {
            modalRecortarFondo.style.display = 'none';
            if (window.lockBodyScroll) window.lockBodyScroll(false);
        }

        cropperFondoInstance.destroy();
        cropperFondoInstance = null;
    };

    window.quitarFondoContenido = function () {
        var inputHiddenQuitar = document.getElementById('quitar_fondo');
        if (inputHiddenQuitar) inputHiddenQuitar.value = '1';

        var inputHiddenFondo = document.getElementById('fondo_imagen_recortada');
        if (inputHiddenFondo) inputHiddenFondo.value = '';

        var inputArchivo = document.getElementById('input-archivo-fondo');
        if (inputArchivo) inputArchivo.value = '';

        currentFondoRawSrc = null;

        var previewMiniatura = document.getElementById('preview-fondo-miniatura');
        if (previewMiniatura) previewMiniatura.src = '';

        var previewContainer = document.getElementById('preview-fondo-container');
        if (previewContainer) previewContainer.classList.add('hidden');

        var dropzone = document.getElementById('dropzone-fondo-modulo');
        if (dropzone) dropzone.classList.remove('hidden');

        var editorFondoCapa = document.getElementById('editor-fondo-capa');
        if (editorFondoCapa) {
            editorFondoCapa.style.backgroundImage = 'none';
        }
    };

    // Inicialización al cargar el DOM
    document.addEventListener('DOMContentLoaded', function () {
        var editorElement = document.getElementById('editor');
        if (!editorElement || typeof Quill === 'undefined') return;

        // 1. Configurar Attributor de Estilo en línea para Tipografía y Alineación
        var FontStyle = Quill.import('attributors/style/font');
        FontStyle.whitelist = ['Arial', 'Calibri', 'Times New Roman'];
        Quill.register(FontStyle, true);

        var AlignStyle = Quill.import('attributors/style/align');
        Quill.register(AlignStyle, true);

        // 2. Registrar icono nativo de Quill para Fondo del Contenido
        var icons = Quill.import('ui/icons');
        icons['fondo_contenido'] = `
            <svg viewBox="0 0 18 18">
                <rect class="ql-stroke" stroke-dasharray="2,1.5" height="13" width="14" x="2" y="2.5" rx="1.5"></rect>
                <rect class="ql-stroke" height="7.5" width="9.5" x="4.25" y="5.25" rx="1"></rect>
                <circle class="ql-fill" cx="6.5" cy="7.25" r="0.9"></circle>
                <polyline class="ql-even ql-fill" points="5.5 11.5 5.5 10.5 7 9 8 10 10 8 12 10 12 11.5 5.5 11.5"></polyline>
            </svg>
        `;

        var toolbarOptions = [
            [{ 'font': [false, 'Arial', 'Calibri', 'Times New Roman'] }],
            [{ 'header': [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
            [{ 'align': [] }],
            ['link', 'image', 'video', 'fondo_contenido'],
            ['clean']
        ];

        var quill = new Quill('#editor', {
            modules: {
                toolbar: {
                    container: toolbarOptions,
                    handlers: {
                        'fondo_contenido': function () {
                            window.abrirModalConfigFondo();
                        }
                    }
                }
            },
            theme: 'snow',
            placeholder: 'Escribe aquí el contenido del módulo...'
        });

        // 3. Inyectar capa de fondo detrás del contenido
        var qlContainer = quill.container;
        if (qlContainer) {
            qlContainer.style.position = 'relative';

            var fondoCapa = document.createElement('div');
            fondoCapa.id = 'editor-fondo-capa';
            fondoCapa.style.position = 'absolute';
            fondoCapa.style.inset = '0';
            fondoCapa.style.backgroundPosition = 'center';
            fondoCapa.style.backgroundSize = 'cover';
            fondoCapa.style.backgroundRepeat = 'no-repeat';
            fondoCapa.style.pointerEvents = 'none';
            fondoCapa.style.zIndex = '0';
            fondoCapa.style.transition = 'opacity 0.2s ease';
            fondoCapa.style.borderBottomLeftRadius = '0.75rem';
            fondoCapa.style.borderBottomRightRadius = '0.75rem';

            // Lectura desacoplada de valores iniciales vía atributos data-*
            var initialOpacity = editorElement.dataset.fondoOpacidad || '0.2';
            var initialBg = editorElement.dataset.fondoImagen || '';
            fondoCapa.style.opacity = initialOpacity;
            if (initialBg) {
                fondoCapa.style.backgroundImage = 'url("' + initialBg + '")';
            }

            qlContainer.insertBefore(fondoCapa, qlContainer.firstChild);
        }

        // 4. Configurar botón de Fondo en la barra
        var btnFondo = document.querySelector('.ql-fondo_contenido');
        if (btnFondo) {
            btnFondo.setAttribute('type', 'button');
            btnFondo.setAttribute('title', 'Fondo del contenido (Imagen y Transparencia)');
            btnFondo.setAttribute('aria-label', 'Fondo del contenido');
            if (!btnFondo.querySelector('svg')) {
                btnFondo.innerHTML = icons['fondo_contenido'];
            }
            btnFondo.onclick = function (e) {
                e.preventDefault();
                e.stopPropagation();
                window.abrirModalConfigFondo();
            };
        }

        // 5. Sincronización de contenido HTML y contador de caracteres
        var hiddenInput = document.getElementById('contenido_html');
        var charCount = document.getElementById('charCount');

        if (charCount) {
            charCount.textContent = quill.getText().trim().length + ' caracteres';
        }

        quill.on('text-change', function () {
            if (hiddenInput) hiddenInput.value = quill.root.innerHTML;
            if (charCount) charCount.textContent = quill.getText().trim().length + ' caracteres';
        });

        var editorForm = document.getElementById('editorForm');
        if (editorForm) {
            editorForm.onsubmit = function () {
                if (hiddenInput) hiddenInput.value = quill.root.innerHTML;
            };
        }

        // 6. Selector de archivo de fondo
        var inputArchivoFondo = document.getElementById('input-archivo-fondo');
        if (inputArchivoFondo) {
            inputArchivoFondo.addEventListener('change', function (e) {
                var file = e.target.files[0];
                if (file) {
                    if (!file.type.startsWith('image/')) {
                        if (window.mostrarAlertaBanner) {
                            window.mostrarAlertaBanner('Por favor selecciona un archivo de imagen válido.', 'error');
                        }
                        return;
                    }
                    var reader = new FileReader();
                    reader.onload = function (evt) {
                        currentFondoRawSrc = evt.target.result;
                        window.iniciarCropperFondo(currentFondoRawSrc);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // 7. Slider de transparencia en vivo
        var sliderOpacidad = document.getElementById('slider-opacidad-control');
        if (sliderOpacidad) {
            sliderOpacidad.addEventListener('input', function (e) {
                var val = e.target.value;
                var labelDisplay = document.getElementById('label-opacidad-display');
                if (labelDisplay) labelDisplay.textContent = val + '%';

                var inputHidden = document.getElementById('fondo_opacidad');
                if (inputHidden) inputHidden.value = val;

                var capa = document.getElementById('editor-fondo-capa');
                if (capa) {
                    capa.style.opacity = (val / 100);
                }
            });
        }

        // ==========================================================================
        // 8. MOTOR DE MANIPULACIÓN LIBRE DE IMÁGENES (RESIZER & DRAG-AND-DROP)
        // ==========================================================================
        var currentImg = null;
        var draggedImg = null;
        var isResizing = false;
        var currentHandle = null;
        var startX = 0, startY = 0, startWidth = 0, startHeight = 0;
        var startAspect = 1;
        var dropTargetInfo = null;

        // Indicador visual de soltado
        var dropIndicator = document.createElement('div');
        dropIndicator.id = 'sisig-drop-indicator';
        if (qlContainer) {
            qlContainer.appendChild(dropIndicator);
        }

        // Overlay interactivo
        var resizerOverlay = document.createElement('div');
        resizerOverlay.id = 'sisig-img-resizer-overlay';
        resizerOverlay.innerHTML = `
            <div class="sisig-resize-handle sisig-handle-nw" data-handle="nw"></div>
            <div class="sisig-resize-handle sisig-handle-ne" data-handle="ne"></div>
            <div class="sisig-resize-handle sisig-handle-sw" data-handle="sw"></div>
            <div class="sisig-resize-handle sisig-handle-se" data-handle="se"></div>
            <div class="sisig-resize-handle sisig-handle-n"  data-handle="n"></div>
            <div class="sisig-resize-handle sisig-handle-s"  data-handle="s"></div>
            <div class="sisig-resize-handle sisig-handle-w"  data-handle="w"></div>
            <div class="sisig-resize-handle sisig-handle-e"  data-handle="e"></div>
            <div class="sisig-move-crosshair" draggable="true" title="Arrastra para mover la imagen a cualquier parte del documento">
                <i class="fas fa-arrows-alt"></i> <span>Mover</span>
            </div>
            <div id="sisig-img-toolbar">
                <button type="button" class="sisig-drag-btn" draggable="true" title="Arrastrar para mover libremente de lugar">
                    <i class="fas fa-arrows-alt"></i> Mover
                </button>
                <span class="toolbar-divider"></span>
                <button type="button" data-action="move-up" title="Subir / Mover antes"><i class="fas fa-arrow-up"></i></button>
                <button type="button" data-action="move-down" title="Bajar / Mover después"><i class="fas fa-arrow-down"></i></button>
                <span class="toolbar-divider"></span>
                <button type="button" data-action="align-left" title="Bloque a la izquierda"><i class="fas fa-align-left"></i></button>
                <button type="button" data-action="align-center" title="Centrar imagen"><i class="fas fa-align-center"></i></button>
                <button type="button" data-action="align-right" title="Bloque a la derecha"><i class="fas fa-align-right"></i></button>
                <button type="button" data-action="inline" title="En línea / Lado a lado (Flujo libre con otros elementos)"><i class="fas fa-columns"></i></button>
                <span class="toolbar-divider"></span>
                <button type="button" data-action="float-left" title="Flotar a la izquierda (Texto a la derecha)"><i class="fas fa-indent"></i></button>
                <button type="button" data-action="float-right" title="Flotar a la derecha (Texto a la izquierda)"><i class="fas fa-outdent"></i></button>
                <span class="toolbar-divider"></span>
                <button type="button" data-action="nudge-left" title="Desplazar a la izquierda (-15px)"><i class="fas fa-chevron-left"></i></button>
                <button type="button" data-action="nudge-right" title="Desplazar a la derecha (+15px)"><i class="fas fa-chevron-right"></i></button>
                <span class="toolbar-divider"></span>
                <button type="button" data-action="size-25" title="Ancho 25%">25%</button>
                <button type="button" data-action="size-50" title="Ancho 50%">50%</button>
                <button type="button" data-action="size-100" title="Ancho 100%">100%</button>
                <span class="toolbar-divider"></span>
                <span id="sisig-img-size-badge">0x0</span>
                <span class="toolbar-divider"></span>
                <button type="button" data-action="delete" title="Eliminar imagen" style="color: #f87171;"><i class="fas fa-trash-alt"></i></button>
            </div>
        `;

        if (qlContainer) {
            qlContainer.appendChild(resizerOverlay);
        }

        function updateResizerPosition() {
            if (!currentImg || !resizerOverlay || !qlContainer) return;

            var containerRect = qlContainer.getBoundingClientRect();
            var imgRect = currentImg.getBoundingClientRect();

            var top = imgRect.top - containerRect.top + qlContainer.scrollTop;
            var left = imgRect.left - containerRect.left + qlContainer.scrollLeft;

            resizerOverlay.style.top = top + 'px';
            resizerOverlay.style.left = left + 'px';
            resizerOverlay.style.width = imgRect.width + 'px';
            resizerOverlay.style.height = imgRect.height + 'px';
            resizerOverlay.style.display = 'block';

            var toolbar = document.getElementById('sisig-img-toolbar');
            if (toolbar) {
                if (top < 50) {
                    toolbar.style.top = (imgRect.height + 12) + 'px';
                } else {
                    toolbar.style.top = '-46px';
                }
            }

            var badge = document.getElementById('sisig-img-size-badge');
            if (badge) {
                badge.textContent = Math.round(imgRect.width) + ' × ' + Math.round(imgRect.height) + 'px';
            }
        }

        function hideResizer() {
            if (isResizing) return;
            currentImg = null;
            if (resizerOverlay) {
                resizerOverlay.style.display = 'none';
            }
        }

        function prepararImagenesArrastrables() {
            if (!quill || !quill.root) return;
            var imgs = quill.root.querySelectorAll('img');
            imgs.forEach(function (img) {
                if (!img.getAttribute('draggable')) {
                    img.setAttribute('draggable', 'true');
                }
            });
        }
        prepararImagenesArrastrables();

        quill.root.addEventListener('mouseover', function (e) {
            if (e.target && e.target.tagName === 'IMG' && !e.target.getAttribute('draggable')) {
                e.target.setAttribute('draggable', 'true');
            }
        });

        quill.root.addEventListener('click', function (e) {
            if (e.target && e.target.tagName === 'IMG') {
                currentImg = e.target;
                updateResizerPosition();
            } else if (!e.target.closest('#sisig-img-resizer-overlay')) {
                hideResizer();
            }
        });

        // Arrastre libre de imágenes
        function iniciarArrastre(imgElement, e) {
            draggedImg = imgElement;
            if (e.dataTransfer) {
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/plain', 'sisig-img');
            }
            draggedImg.classList.add('sisig-img-dragging');
            if (resizerOverlay) {
                resizerOverlay.style.display = 'none';
            }
        }

        quill.root.addEventListener('dragstart', function (e) {
            if (e.target && e.target.tagName === 'IMG') {
                iniciarArrastre(e.target, e);
            }
        });

        resizerOverlay.addEventListener('dragstart', function (e) {
            var handle = e.target.closest('.sisig-move-crosshair, .sisig-drag-btn');
            if (handle && currentImg) {
                iniciarArrastre(currentImg, e);
            }
        });

        function finalizarArrastre() {
            if (draggedImg) {
                draggedImg.classList.remove('sisig-img-dragging');
            }
            if (dropIndicator) {
                dropIndicator.style.display = 'none';
            }
            dropTargetInfo = null;
        }

        document.addEventListener('dragend', function () {
            finalizarArrastre();
            if (currentImg) {
                updateResizerPosition();
            }
        });

        function calcularPosicionSoltado(e) {
            var range = null;
            if (document.caretRangeFromPoint) {
                range = document.caretRangeFromPoint(e.clientX, e.clientY);
            } else if (document.caretPositionFromPoint) {
                var pos = document.caretPositionFromPoint(e.clientX, e.clientY);
                if (pos) {
                    range = document.createRange();
                    range.setStart(pos.offsetNode, pos.offset);
                    range.collapse(true);
                }
            }

            if (range && quill.root.contains(range.startContainer)) {
                return { type: 'range', range: range, node: range.startContainer, offset: range.startOffset };
            }

            var targetEl = document.elementFromPoint(e.clientX, e.clientY);
            if (targetEl && quill.root.contains(targetEl)) {
                var block = targetEl.closest('p, h1, h2, h3, li, blockquote, div, img');
                if (block) {
                    var rect = block.getBoundingClientRect();
                    var isAfter = (e.clientY > rect.top + rect.height / 2);
                    if (block.tagName === 'IMG') {
                        isAfter = (e.clientX > rect.left + rect.width / 2);
                    }
                    return { type: 'element', element: block, isAfter: isAfter };
                }
            }
            return null;
        }

        quill.root.addEventListener('dragover', function (e) {
            if (!draggedImg) return;
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';

            dropTargetInfo = calcularPosicionSoltado(e);
            if (!dropTargetInfo || !qlContainer || !dropIndicator) return;

            var containerRect = qlContainer.getBoundingClientRect();

            if (dropTargetInfo.type === 'element' && dropTargetInfo.element) {
                var elRect = dropTargetInfo.element.getBoundingClientRect();
                if (dropTargetInfo.element.tagName === 'IMG') {
                    var posX = dropTargetInfo.isAfter ? elRect.right : elRect.left;
                    dropIndicator.style.left = (posX - containerRect.left + qlContainer.scrollLeft - 2) + 'px';
                    dropIndicator.style.top = (elRect.top - containerRect.top + qlContainer.scrollTop) + 'px';
                    dropIndicator.style.width = '4px';
                    dropIndicator.style.height = elRect.height + 'px';
                    dropIndicator.style.display = 'block';
                } else {
                    var posY = dropTargetInfo.isAfter ? elRect.bottom : elRect.top;
                    dropIndicator.style.left = (elRect.left - containerRect.left + qlContainer.scrollLeft) + 'px';
                    dropIndicator.style.top = (posY - containerRect.top + qlContainer.scrollTop - 2) + 'px';
                    dropIndicator.style.width = elRect.width + 'px';
                    dropIndicator.style.height = '4px';
                    dropIndicator.style.display = 'block';
                }
            } else if (dropTargetInfo.type === 'range' && dropTargetInfo.range) {
                var r = dropTargetInfo.range.getBoundingClientRect();
                if (r && r.height > 0) {
                    dropIndicator.style.left = (r.left - containerRect.left + qlContainer.scrollLeft - 2) + 'px';
                    dropIndicator.style.top = (r.top - containerRect.top + qlContainer.scrollTop) + 'px';
                    dropIndicator.style.width = '4px';
                    dropIndicator.style.height = (r.height || 24) + 'px';
                    dropIndicator.style.display = 'block';
                }
            }
        });

        quill.root.addEventListener('drop', function (e) {
            if (!draggedImg) return;
            e.preventDefault();
            e.stopPropagation();

            var targetInfo = dropTargetInfo || calcularPosicionSoltado(e);
            if (targetInfo) {
                if (targetInfo.type === 'range' && targetInfo.node) {
                    var node = targetInfo.node;
                    if (node.nodeType === Node.TEXT_NODE) {
                        var split = node.splitText(targetInfo.offset);
                        node.parentNode.insertBefore(draggedImg, split);
                    } else {
                        var child = node.childNodes[targetInfo.offset];
                        if (child && child !== draggedImg) {
                            node.insertBefore(draggedImg, child);
                        } else {
                            node.appendChild(draggedImg);
                        }
                    }
                } else if (targetInfo.type === 'element' && targetInfo.element) {
                    var el = targetInfo.element;
                    if (el !== draggedImg && el.parentNode) {
                        if (targetInfo.isAfter) {
                            el.parentNode.insertBefore(draggedImg, el.nextSibling);
                        } else {
                            el.parentNode.insertBefore(draggedImg, el);
                        }
                    }
                }

                quill.update();
                currentImg = draggedImg;
                updateResizerPosition();
                if (hiddenInput) hiddenInput.value = quill.root.innerHTML;
                if (charCount) charCount.textContent = quill.getText().trim().length + ' caracteres';
            }

            finalizarArrastre();
        });

        // Redimensionamiento con manejadores (Handles)
        resizerOverlay.addEventListener('mousedown', function (e) {
            var handle = e.target.closest('.sisig-resize-handle');
            if (!handle || !currentImg) return;

            e.preventDefault();
            e.stopPropagation();

            isResizing = true;
            currentHandle = handle.dataset.handle;
            startX = e.clientX;
            startY = e.clientY;
            startWidth = currentImg.offsetWidth || currentImg.clientWidth;
            startHeight = currentImg.offsetHeight || currentImg.clientHeight;
            startAspect = startWidth / (startHeight || 1);

            document.addEventListener('mousemove', onResizerMouseMove);
            document.addEventListener('mouseup', onResizerMouseUp);
        });

        function onResizerMouseMove(e) {
            if (!isResizing || !currentImg) return;

            var dx = e.clientX - startX;
            var dy = e.clientY - startY;
            var newWidth = startWidth;
            var newHeight = startHeight;

            switch (currentHandle) {
                case 'se':
                    newWidth = Math.max(50, startWidth + dx);
                    newHeight = newWidth / startAspect;
                    break;
                case 'sw':
                    newWidth = Math.max(50, startWidth - dx);
                    newHeight = newWidth / startAspect;
                    break;
                case 'ne':
                    newWidth = Math.max(50, startWidth + dx);
                    newHeight = newWidth / startAspect;
                    break;
                case 'nw':
                    newWidth = Math.max(50, startWidth - dx);
                    newHeight = newWidth / startAspect;
                    break;
                case 'e':
                    newWidth = Math.max(50, startWidth + dx);
                    newHeight = startHeight;
                    break;
                case 'w':
                    newWidth = Math.max(50, startWidth - dx);
                    newHeight = startHeight;
                    break;
                case 's':
                    newHeight = Math.max(40, startHeight + dy);
                    break;
                case 'n':
                    newHeight = Math.max(40, startHeight - dy);
                    break;
            }

            currentImg.style.width = Math.round(newWidth) + 'px';
            if (currentHandle === 's' || currentHandle === 'n') {
                currentImg.style.height = Math.round(newHeight) + 'px';
            } else if (currentHandle === 'e' || currentHandle === 'w') {
                currentImg.style.height = 'auto';
            } else {
                currentImg.style.height = Math.round(newHeight) + 'px';
            }

            currentImg.removeAttribute('width');
            currentImg.removeAttribute('height');

            updateResizerPosition();
        }

        function onResizerMouseUp() {
            if (isResizing) {
                isResizing = false;
                document.removeEventListener('mousemove', onResizerMouseMove);
                document.removeEventListener('mouseup', onResizerMouseUp);

                if (hiddenInput) hiddenInput.value = quill.root.innerHTML;
                if (charCount) charCount.textContent = quill.getText().trim().length + ' caracteres';
            }
        }

        // Acciones flotantes del resizer
        resizerOverlay.addEventListener('click', function (e) {
            var btn = e.target.closest('button[data-action]');
            if (!btn || !currentImg) return;

            e.preventDefault();
            e.stopPropagation();

            var action = btn.dataset.action;

            switch (action) {
                case 'move-up':
                    var prev = currentImg.previousElementSibling;
                    if (prev) {
                        currentImg.parentNode.insertBefore(currentImg, prev);
                    } else if (currentImg.parentNode && currentImg.parentNode.previousElementSibling) {
                        currentImg.parentNode.previousElementSibling.before(currentImg);
                    }
                    quill.update();
                    break;
                case 'move-down':
                    var next = currentImg.nextElementSibling;
                    if (next) {
                        currentImg.parentNode.insertBefore(next, currentImg);
                    } else if (currentImg.parentNode && currentImg.parentNode.nextElementSibling) {
                        currentImg.parentNode.nextElementSibling.after(currentImg);
                    }
                    quill.update();
                    break;
                case 'align-left':
                    currentImg.style.display = 'block';
                    currentImg.style.marginLeft = '0';
                    currentImg.style.marginRight = 'auto';
                    currentImg.style.float = 'none';
                    break;
                case 'align-center':
                    currentImg.style.display = 'block';
                    currentImg.style.marginLeft = 'auto';
                    currentImg.style.marginRight = 'auto';
                    currentImg.style.float = 'none';
                    break;
                case 'align-right':
                    currentImg.style.display = 'block';
                    currentImg.style.marginLeft = 'auto';
                    currentImg.style.marginRight = '0';
                    currentImg.style.float = 'none';
                    break;
                case 'inline':
                    currentImg.style.display = 'inline-block';
                    currentImg.style.verticalAlign = 'middle';
                    currentImg.style.float = 'none';
                    currentImg.style.margin = '0.5rem 0.75rem';
                    break;
                case 'float-left':
                    currentImg.style.display = 'inline-block';
                    currentImg.style.float = 'left';
                    currentImg.style.margin = '0.5rem 1.25rem 0.5rem 0';
                    break;
                case 'float-right':
                    currentImg.style.display = 'inline-block';
                    currentImg.style.float = 'right';
                    currentImg.style.margin = '0.5rem 0 0.5rem 1.25rem';
                    break;
                case 'nudge-left':
                    var curML = parseInt(currentImg.style.marginLeft || '0', 10);
                    currentImg.style.marginLeft = Math.max(0, curML - 15) + 'px';
                    break;
                case 'nudge-right':
                    var curML = parseInt(currentImg.style.marginLeft || '0', 10);
                    currentImg.style.marginLeft = (curML + 15) + 'px';
                    break;
                case 'size-25':
                    currentImg.style.width = '25%';
                    currentImg.style.height = 'auto';
                    break;
                case 'size-50':
                    currentImg.style.width = '50%';
                    currentImg.style.height = 'auto';
                    break;
                case 'size-100':
                    currentImg.style.width = '100%';
                    currentImg.style.height = 'auto';
                    break;
                case 'delete':
                    if (confirm('¿Deseas eliminar esta imagen del contenido?')) {
                        currentImg.remove();
                        hideResizer();
                        quill.update();
                        if (hiddenInput) hiddenInput.value = quill.root.innerHTML;
                        if (charCount) charCount.textContent = quill.getText().trim().length + ' caracteres';
                    }
                    return;
            }

            updateResizerPosition();
            if (hiddenInput) hiddenInput.value = quill.root.innerHTML;
            if (charCount) charCount.textContent = quill.getText().trim().length + ' caracteres';
        });

        quill.root.addEventListener('scroll', updateResizerPosition);
        window.addEventListener('resize', updateResizerPosition);
        document.addEventListener('click', function (e) {
            if (!e.target.closest('#sisig-img-resizer-overlay') && e.target.tagName !== 'IMG') {
                hideResizer();
            }
        });
    });

    // Delegación global para el botón de fondo si se interactúa desde el DOM
    document.addEventListener('click', function (e) {
        if (e.target.closest('.ql-fondo_contenido')) {
            e.preventDefault();
            e.stopPropagation();
            window.abrirModalConfigFondo();
        }
    });
})();
