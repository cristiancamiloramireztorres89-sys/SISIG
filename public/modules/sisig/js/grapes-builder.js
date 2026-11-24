/**
 * ==========================================================================
 * SISIG - CONSTRUCTOR VISUAL DE CONTENIDOS (TIPO CANVA CON GRAPESJS)
 * Lógica modular con buenas prácticas: Drag & Drop, Resizing, YouTube y Guardado
 * ==========================================================================
 */

(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        const editorContainer = document.getElementById('gjs');
        if (!editorContainer) return;

        // Leer datos iniciales inyectados en la vista Blade
        const moduloId = editorContainer.dataset.moduloId;
        const saveUrl = editorContainer.dataset.saveUrl;
        const uploadUrl = editorContainer.dataset.uploadUrl;
        const csrfToken = editorContainer.dataset.csrfToken;

        // Leer datos estructurados de forma segura sin colisión de comillas en atributos HTML
        let parsedState = null;
        const stateScript = document.getElementById('sisig-canvas-state');
        if (stateScript && stateScript.textContent.trim()) {
            try {
                const text = stateScript.textContent.trim();
                if (text !== 'null' && text !== '""') {
                    parsedState = JSON.parse(text);
                    if (typeof parsedState === 'string') {
                        parsedState = JSON.parse(parsedState);
                    }
                }
            } catch (e) {
                console.warn('Advertencia al interpretar canvas_state:', e);
            }
        }

        const htmlTemplate = document.getElementById('sisig-initial-html');
        const rawHtml = htmlTemplate ? htmlTemplate.innerHTML.trim() : (editorContainer.dataset.initialHtml || '');

        // 1. Inicialización de GrapesJS con arquitectura modular
        const editor = grapesjs.init({
            container: '#gjs',
            fromElement: false,
            height: '100%',
            width: 'auto',
            storageManager: false, // Guardado propio mediante AJAX en Laravel
            deviceManager: {
                devices: [
                    { id: 'desktop', name: 'Escritorio', width: '' },
                    { id: 'tablet', name: 'Tablet', width: '768px', widthMedia: '992px' },
                    { id: 'mobile', name: 'Móvil', width: '375px', widthMedia: '480px' },
                ]
            },
            canvas: {
                styles: [
                    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css'
                ]
            },
            blockManager: {
                appendTo: '#blocks-container',
            },
            styleManager: {
                appendTo: '#styles-container',
                sectors: [
                    {
                        name: 'Dimensiones y Tamaño',
                        open: true,
                        buildProps: ['width', 'height', 'max-width', 'min-height', 'margin', 'padding'],
                        properties: [
                            { name: 'Ancho', property: 'width', type: 'integer', units: ['px', '%', 'vw', 'auto'], defaults: 'auto' },
                            { name: 'Alto', property: 'height', type: 'integer', units: ['px', '%', 'vh', 'auto'], defaults: 'auto' },
                            { name: 'Ancho Máximo', property: 'max-width', type: 'integer', units: ['px', '%', 'vw'], defaults: '100%' },
                            { name: 'Margen Exterior', property: 'margin', type: 'composite', properties: [
                                { name: 'Arriba', property: 'margin-top', type: 'integer', units: ['px', 'rem'] },
                                { name: 'Derecha', property: 'margin-right', type: 'integer', units: ['px', 'rem'] },
                                { name: 'Abajo', property: 'margin-bottom', type: 'integer', units: ['px', 'rem'] },
                                { name: 'Izquierda', property: 'margin-left', type: 'integer', units: ['px', 'rem'] },
                            ]},
                            { name: 'Relleno Interior', property: 'padding', type: 'composite', properties: [
                                { name: 'Arriba', property: 'padding-top', type: 'integer', units: ['px', 'rem'] },
                                { name: 'Derecha', property: 'padding-right', type: 'integer', units: ['px', 'rem'] },
                                { name: 'Abajo', property: 'padding-bottom', type: 'integer', units: ['px', 'rem'] },
                                { name: 'Izquierda', property: 'padding-left', type: 'integer', units: ['px', 'rem'] },
                            ]}
                        ]
                    },
                    {
                        name: 'Tipografía y Texto',
                        open: false,
                        buildProps: ['font-family', 'font-size', 'font-weight', 'color', 'line-height', 'text-align'],
                        properties: [
                            {
                                name: 'Fuente',
                                property: 'font-family',
                                type: 'select',
                                defaults: 'system-ui, sans-serif',
                                options: [
                                    { value: 'system-ui, -apple-system, sans-serif', name: 'Moderna (Sans)' },
                                    { value: 'Arial, Helvetica, sans-serif', name: 'Arial' },
                                    { value: 'Georgia, serif', name: 'Elegante (Serif)' },
                                    { value: 'Courier New, monospace', name: 'Monospace' },
                                ]
                            },
                            { name: 'Tamaño de Letra', property: 'font-size', type: 'integer', units: ['px', 'rem', 'em'], defaults: '16px' },
                            { name: 'Grosor', property: 'font-weight', type: 'select', options: [
                                { value: '400', name: 'Normal' },
                                { value: '600', name: 'Seminegrita' },
                                { value: '700', name: 'Negrita' },
                                { value: '900', name: 'Extra Negrita' },
                            ]},
                            { name: 'Color de Texto', property: 'color', type: 'color' },
                            { name: 'Alineación', property: 'text-align', type: 'radio', options: [
                                { value: 'left', name: 'Izq' },
                                { value: 'center', name: 'Centro' },
                                { value: 'right', name: 'Der' },
                                { value: 'justify', name: 'Just' },
                            ]}
                        ]
                    },
                    {
                        name: 'Decoración y Bordes',
                        open: false,
                        buildProps: ['background-color', 'border-radius', 'border', 'box-shadow'],
                        properties: [
                            { name: 'Color de Fondo', property: 'background-color', type: 'color' },
                            { name: 'Borde Redondeado', property: 'border-radius', type: 'integer', units: ['px', '%', 'rem'] },
                            { name: 'Borde', property: 'border', type: 'composite', properties: [
                                { name: 'Grosor', property: 'border-width', type: 'integer', units: ['px'] },
                                { name: 'Estilo', property: 'border-style', type: 'select', options: [
                                    { value: 'none', name: 'Ninguno' },
                                    { value: 'solid', name: 'Sólido' },
                                    { value: 'dashed', name: 'Punteado' },
                                ]},
                                { name: 'Color', property: 'border-color', type: 'color' }
                            ]},
                            { name: 'Sombra', property: 'box-shadow', type: 'stack' }
                        ]
                    }
                ]
            },
            traitManager: {
                appendTo: '#traits-container'
            },
            panels: { defaults: [] }
        });

        // Función utilitaria para extraer el ID limpio de YouTube ante enlaces completos o recortados
        function extraerIdYouTube(url) {
            if (!url) return '';
            const trimmed = String(url).trim();
            const regExp = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i;
            const match = trimmed.match(regExp);
            if (match && match[1]) {
                return match[1];
            }
            if (/^[a-zA-Z0-9_-]{11}$/.test(trimmed)) {
                return trimmed;
            }
            return trimmed;
        }

        // Función recursiva para normalizar videos antes de cargar o guardar
        function normalizarVideos(nodo) {
            if (!nodo) return;
            if (Array.isArray(nodo)) {
                nodo.forEach(normalizarVideos);
                return;
            }
            if (typeof nodo === 'object') {
                if (nodo.type === 'video' || (nodo.src && (nodo.src.includes('youtube') || nodo.src.includes('youtu.be')))) {
                    nodo.type = 'video';
                    nodo.provider = 'yt';
                    nodo.tagName = 'iframe';
                    if (nodo.src) {
                        const cleanId = extraerIdYouTube(nodo.src);
                        if (cleanId && !nodo.videoId) {
                            nodo.videoId = cleanId;
                        }
                    }
                    if (nodo.videoId) {
                        const cleanId = extraerIdYouTube(nodo.videoId);
                        nodo.videoId = cleanId;
                        if (!nodo.src || !nodo.src.includes('youtube.com/embed/')) {
                            nodo.src = 'https://www.youtube.com/embed/' + cleanId;
                        }
                    }
                }
                if (nodo.components) normalizarVideos(nodo.components);
                if (nodo.frames) normalizarVideos(nodo.frames);
                if (nodo.pages) normalizarVideos(nodo.pages);
                if (nodo.component) normalizarVideos(nodo.component);
            }
        }

        // Listener en el componente de video: si el usuario pega un enlace completo, extraer el ID y asegurar provider=yt
        editor.on('component:update:videoId', function (component) {
            const raw = component.get('videoId');
            if (raw) {
                const cleanId = extraerIdYouTube(raw);
                if (cleanId !== raw) {
                    component.set('videoId', cleanId);
                }
                component.set({
                    provider: 'yt',
                    tagName: 'iframe'
                });
            }
        });

        editor.on('component:update:src', function (component) {
            if (component.get('type') === 'video') {
                const src = component.get('src') || '';
                if (src.includes('youtube') || src.includes('youtu.be')) {
                    const cleanId = extraerIdYouTube(src);
                    if (cleanId) {
                        component.set('videoId', cleanId);
                    }
                    component.set({
                        provider: 'yt',
                        tagName: 'iframe'
                    });
                }
            }
        });

        // 2. Configurar Componente de Video con Traits en Español y Manijas de Tamaño
        editor.DomComponents.addType('video', {
            model: {
                defaults: {
                    provider: 'yt',
                    videoId: 'M7lc1UVf-VE',
                    resizable: {
                        ratioDefault: 1,
                        tl: 1, tr: 1, bl: 1, br: 1, cl: 1, cr: 1
                    },
                    traits: [
                        {
                            type: 'select',
                            label: 'Proveedor',
                            name: 'provider',
                            options: [
                                { value: 'yt', name: 'YouTube' },
                                { value: 'vi', name: 'Vimeo' },
                                { value: 'so', name: 'Video HTML5 (MP4)' },
                            ]
                        },
                        {
                            type: 'text',
                            label: 'Enlace / ID Video',
                            name: 'videoId',
                            placeholder: 'Pega enlace de YouTube aquí'
                        },
                        {
                            type: 'checkbox',
                            label: 'Mostrar Controles',
                            name: 'controls'
                        },
                        {
                            type: 'checkbox',
                            label: 'Autoplay (Silenciado)',
                            name: 'autoplay'
                        },
                        {
                            type: 'checkbox',
                            label: 'Repetir en Bucle',
                            name: 'loop'
                        }
                    ]
                }
            }
        });

        // Configurar Manijas Interactivas para Imágenes
        editor.DomComponents.addType('image', {
            model: {
                defaults: {
                    resizable: {
                        ratioDefault: 1,
                        tl: 1, tr: 1, bl: 1, br: 1, cl: 1, cr: 1
                    },
                    traits: [
                        {
                            type: 'text',
                            label: 'URL de la Imagen',
                            name: 'src',
                            placeholder: 'URL de la imagen o usa el botón subir'
                        },
                        {
                            type: 'text',
                            label: 'Texto Alternativo (Alt)',
                            name: 'alt'
                        }
                    ]
                }
            }
        });

        // 3. Catálogo de Bloques Visuales (Elementos tipo Canva)
        const bm = editor.BlockManager;

        // Título Principal (H1)
        bm.add('titulo-h1', {
            label: '<i class="fas fa-heading fa-2x mb-1 text-emerald-400"></i><div>Título H1</div>',
            category: 'Textos y Títulos',
            content: '<h1 style="color: #1A2B4C; font-size: 2.25rem; font-weight: 800; line-height: 1.25; margin-bottom: 1rem;">Escribe aquí tu título principal</h1>'
        });

        // Subtítulo (H2)
        bm.add('titulo-h2', {
            label: '<i class="fas fa-font fa-2x mb-1 text-emerald-400"></i><div>Subtítulo H2</div>',
            category: 'Textos y Títulos',
            content: '<h2 style="color: #2E6F40; font-size: 1.5rem; font-weight: 700; line-height: 1.35; margin-bottom: 0.75rem;">Subtítulo de sección formativa</h2>'
        });

        // Párrafo de Texto
        bm.add('parrafo', {
            label: '<i class="fas fa-paragraph fa-2x mb-1 text-slate-300"></i><div>Párrafo</div>',
            category: 'Textos y Títulos',
            content: '<p style="color: #334155; font-size: 1rem; line-height: 1.7; margin-bottom: 1rem;">Escribe aquí la explicación detallada, conceptos o instrucciones pedagógicas para los aprendices.</p>'
        });

        // Video Interactivo (YouTube / Vimeo / MP4)
        bm.add('video-interactivo', {
            label: '<i class="fab fa-youtube fa-2x mb-1 text-red-500"></i><div>Video YouTube</div>',
            category: 'Multimedia',
            content: {
                type: 'video',
                provider: 'yt',
                tagName: 'iframe',
                videoId: 'M7lc1UVf-VE',
                style: {
                    height: '350px',
                    width: '100%',
                    'max-width': '720px',
                    'border-radius': '12px',
                    margin: '1.5rem auto',
                    display: 'block'
                }
            }
        });

        // Imagen con Manijas de Tamaño
        bm.add('imagen-interactiva', {
            label: '<i class="fas fa-image fa-2x mb-1 text-sky-400"></i><div>Imagen</div>',
            category: 'Multimedia',
            content: {
                type: 'image',
                src: 'https://placehold.co/800x450/1A2B4C/FFFFFF?text=Imagen+Formativa+SIG',
                style: {
                    width: '100%',
                    'max-width': '650px',
                    height: 'auto',
                    'border-radius': '12px',
                    margin: '1.5rem auto',
                    display: 'block'
                }
            }
        });

        // 2 Columnas (Lado a Lado) - Ideal para Video + Texto
        bm.add('columnas-2', {
            label: '<i class="fas fa-columns fa-2x mb-1 text-purple-400"></i><div>2 Columnas</div>',
            category: 'Estructuras',
            content: `
                <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; margin: 1.5rem 0; width: 100%;">
                    <div style="flex: 1; min-width: 280px; padding: 1rem; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
                        <h3 style="color: #1A2B4C; font-weight: 700; margin-bottom: 0.5rem;">Columna Izquierda</h3>
                        <p style="color: #64748b; font-size: 0.9rem;">Inserta aquí texto o un video explicativo.</p>
                    </div>
                    <div style="flex: 1; min-width: 280px; padding: 1rem; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
                        <h3 style="color: #1A2B4C; font-weight: 700; margin-bottom: 0.5rem;">Columna Derecha</h3>
                        <p style="color: #64748b; font-size: 0.9rem;">Inserta aquí puntos clave o una imagen técnica.</p>
                    </div>
                </div>
            `
        });

        // 3 Columnas
        bm.add('columnas-3', {
            label: '<i class="fas fa-th-large fa-2x mb-1 text-purple-400"></i><div>3 Columnas</div>',
            category: 'Estructuras',
            content: `
                <div style="display: flex; flex-wrap: wrap; gap: 1rem; margin: 1.5rem 0; width: 100%;">
                    <div style="flex: 1; min-width: 220px; padding: 1rem; background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; text-align: center;">
                        <i class="fas fa-check-circle" style="color: #2E6F40; font-size: 1.75rem; margin-bottom: 0.5rem;"></i>
                        <h4 style="font-weight: 700; margin-bottom: 0.25rem;">Paso 1</h4>
                        <p style="color: #64748b; font-size: 0.85rem;">Inspección preliminar de seguridad.</p>
                    </div>
                    <div style="flex: 1; min-width: 220px; padding: 1rem; background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; text-align: center;">
                        <i class="fas fa-hard-hat" style="color: #f59e0b; font-size: 1.75rem; margin-bottom: 0.5rem;"></i>
                        <h4 style="font-weight: 700; margin-bottom: 0.25rem;">Paso 2</h4>
                        <p style="color: #64748b; font-size: 0.85rem;">Porte de EPP certificado.</p>
                    </div>
                    <div style="flex: 1; min-width: 220px; padding: 1rem; background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; text-align: center;">
                        <i class="fas fa-award" style="color: #0284c7; font-size: 1.75rem; margin-bottom: 0.5rem;"></i>
                        <h4 style="font-weight: 700; margin-bottom: 0.25rem;">Paso 3</h4>
                        <p style="color: #64748b; font-size: 0.85rem;">Ejecución con calidad ISO.</p>
                    </div>
                </div>
            `
        });

        // Tarjeta Informativa SIG
        bm.add('tarjeta-sig', {
            label: '<i class="fas fa-id-card fa-2x mb-1 text-emerald-500"></i><div>Tarjeta SIG</div>',
            category: 'Componentes SIG',
            content: `
                <div style="background: #f0fdf4; border-left: 5px solid #2E6F40; border-radius: 12px; padding: 1.25rem 1.5rem; margin: 1.5rem 0;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                        <i class="fas fa-certificate" style="color: #2E6F40; font-size: 1.25rem;"></i>
                        <strong style="color: #166534; font-size: 1rem;">LINEAMIENTO INSTITUCIONAL SIG</strong>
                    </div>
                    <p style="color: #14532d; font-size: 0.95rem; margin: 0; line-height: 1.6;">
                        El cumplimiento de los protocolos de calidad y sostenibilidad ambiental es obligatorio para todos los aprendices del centro.
                    </p>
                </div>
            `
        });

        // Alerta de Seguridad SST
        bm.add('alerta-sst', {
            label: '<i class="fas fa-exclamation-triangle fa-2x mb-1 text-amber-500"></i><div>Alerta SST</div>',
            category: 'Componentes SIG',
            content: `
                <div style="background: #fffbeb; border-left: 5px solid #f59e0b; border-radius: 12px; padding: 1.25rem 1.5rem; margin: 1.5rem 0;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                        <i class="fas fa-hard-hat" style="color: #d97706; font-size: 1.25rem;"></i>
                        <strong style="color: #b45309; font-size: 1rem;">PRECAUCIÓN Y SEGURIDAD EN EL TRABAJO</strong>
                    </div>
                    <p style="color: #78350f; font-size: 0.95rem; margin: 0; line-height: 1.6;">
                        No opere maquinaria ni herramientas cortantes o rotativas sin supervisión del instructor y sin los elementos de protección personal reglamentarios.
                    </p>
                </div>
            `
        });

        // Botón de Acción
        bm.add('boton-accion', {
            label: '<i class="fas fa-mouse-pointer fa-2x mb-1 text-emerald-400"></i><div>Botón</div>',
            category: 'Componentes SIG',
            content: '<a href="#" style="display: inline-block; background-color: #2E6F40; color: #ffffff; padding: 0.75rem 1.5rem; border-radius: 9999px; text-decoration: none; font-weight: 700; font-size: 0.9rem; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">Ver Guía Técnica</a>'
        });

        // 4. Carga del Estado Inicial (Persistencia en Base de Datos)
        try {
            if (parsedState && (parsedState.pages || parsedState.components || (typeof parsedState === 'object' && Object.keys(parsedState).length > 0))) {
                normalizarVideos(parsedState);
                editor.loadProjectData(parsedState);
            } else if (rawHtml && rawHtml.trim() !== '') {
                editor.setComponents(rawHtml);
            } else {
                // Diapositiva inicial amigable por defecto
                editor.setComponents(`
                    <div style="max-width: 900px; margin: 2rem auto; padding: 2.5rem; background: #ffffff; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); font-family: system-ui, -apple-system, sans-serif;">
                        <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid #e2e8f0; padding-bottom: 1rem; margin-bottom: 1.5rem;">
                            <div style="font-size: 0.75rem; font-weight: 800; color: #2E6F40; text-transform: uppercase; letter-spacing: 0.05em;">SISTEMA INTEGRADO DE GESTIÓN • SENA EMPRESA CEFA</div>
                            <div style="font-size: 0.75rem; font-weight: 700; color: #64748b;">Módulo Didáctico</div>
                        </div>
                        <h1 style="color: #1A2B4C; font-size: 2.25rem; font-weight: 800; line-height: 1.2; margin-bottom: 1rem;">Bienvenido al Diseñador Visual</h1>
                        <p style="color: #475569; font-size: 1.05rem; line-height: 1.7; margin-bottom: 1.5rem;">
                            Arrastra componentes desde la barra derecha (títulos, textos, imágenes, videos de YouTube y columnas). Haz clic en cualquier imagen o video para cambiar su tamaño estirando las esquinas con el ratón.
                        </p>
                    </div>
                `);
            }
        } catch (e) {
            console.warn('No se pudo cargar el estado previo en formato JSON; usando HTML base.', e);
            if (rawHtml) editor.setComponents(rawHtml);
        }

        // 5. Botones de Control de la Barra Superior
        // Dispositivos (Desktop, Tablet, Mobile)
        const deviceBtns = document.querySelectorAll('.sisig-builder-btn-device');
        deviceBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                deviceBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                editor.setDevice(this.dataset.device);
            });
        });

        // Deshacer y Rehacer
        const btnUndo = document.getElementById('btn-undo');
        const btnRedo = document.getElementById('btn-redo');
        if (btnUndo) btnUndo.addEventListener('click', () => editor.UndoManager.undo());
        if (btnRedo) btnRedo.addEventListener('click', () => editor.UndoManager.redo());

        // Limpiar Lienzo
        const btnClear = document.getElementById('btn-clear');
        if (btnClear) {
            btnClear.addEventListener('click', function () {
                if (confirm('¿Deseas vaciar el lienzo y comenzar desde cero?')) {
                    editor.setComponents('');
                }
            });
        }

        // Vista Previa (Preview)
        const btnPreview = document.getElementById('btn-preview');
        if (btnPreview) {
            btnPreview.addEventListener('click', function () {
                const isPreview = editor.Commands.isActive('preview');
                if (isPreview) {
                    editor.stopCommand('preview');
                    this.classList.remove('active');
                } else {
                    editor.runCommand('preview');
                    this.classList.add('active');
                }
            });
        }

        // Pantalla Completa (Fullscreen)
        const btnFullscreen = document.getElementById('btn-fullscreen');
        if (btnFullscreen) {
            btnFullscreen.addEventListener('click', function () {
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen().catch(() => {});
                } else {
                    document.exitFullscreen().catch(() => {});
                }
            });
        }

        // Pestañas de la Barra Lateral Derecha
        const tabBtns = document.querySelectorAll('.sisig-sidebar-tab');
        const tabContents = {
            blocks: document.getElementById('blocks-container'),
            styles: document.getElementById('styles-container'),
            traits: document.getElementById('traits-container')
        };

        tabBtns.forEach(tab => {
            tab.addEventListener('click', function () {
                tabBtns.forEach(t => t.classList.remove('active'));
                this.classList.add('active');

                const targetTab = this.dataset.tab;
                Object.keys(tabContents).forEach(key => {
                    if (tabContents[key]) {
                        tabContents[key].style.display = (key === targetTab) ? 'block' : 'none';
                    }
                });
            });
        });

        // Al seleccionar un componente en el lienzo:
        // Si es video, imagen o enlace, activar automáticamente la pestaña de "Ajustes" para ver el campo de URL;
        // de lo contrario, activar la pestaña de "Estilos"
        editor.on('component:selected', function (component) {
            if (!component) return;
            const type = component.get('type');
            if (type === 'video' || type === 'image' || type === 'link') {
                const traitsTab = document.querySelector('.sisig-sidebar-tab[data-tab="traits"]');
                if (traitsTab) traitsTab.click();
            } else {
                const stylesTab = document.querySelector('.sisig-sidebar-tab[data-tab="styles"]');
                if (stylesTab) stylesTab.click();
            }
        });

        // 6. Subida de Imágenes desde el PC al Servidor
        const inputUploadImagen = document.getElementById('input-upload-imagen');
        if (inputUploadImagen) {
            inputUploadImagen.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (!file) return;

                if (!file.type.startsWith('image/')) {
                    alert('Por favor selecciona un archivo de imagen válido (PNG, JPG, WEBP).');
                    return;
                }

                const formData = new FormData();
                formData.append('image', file);
                formData.append('_token', csrfToken);

                const btnLabel = document.getElementById('label-btn-subir-imagen');
                if (btnLabel) btnLabel.textContent = 'Subiendo...';

                fetch(uploadUrl || '/sisig/editor/modules/upload-media', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (btnLabel) btnLabel.textContent = 'Subir Imagen';
                    if (data.url || (data.data && data.data.url)) {
                        const finalUrl = data.url || data.data.url;
                        // Si hay un componente de imagen seleccionado, cambiar su fuente
                        const selected = editor.getSelected();
                        if (selected && selected.is('image')) {
                            selected.set('src', finalUrl);
                        } else {
                            // Insertar nueva imagen en el lienzo
                            editor.addComponents({
                                type: 'image',
                                src: finalUrl,
                                style: {
                                    width: '100%',
                                    'max-width': '600px',
                                    height: 'auto',
                                    'border-radius': '12px',
                                    margin: '1.5rem auto',
                                    display: 'block'
                                }
                            });
                        }
                        mostrarNotificacion('¡Imagen subida e insertada exitosamente!', 'success');
                    } else {
                        alert('No se pudo procesar la imagen: ' + (data.message || 'Error en servidor'));
                    }
                })
                .catch(err => {
                    if (btnLabel) btnLabel.textContent = 'Subir Imagen';
                    console.error('Error al subir imagen:', err);
                    alert('Error de conexión al subir la imagen.');
                });
            });
        }

        // 7. Guardado Persistente en Laravel (AJAX con Notificación Toast)
        window.guardarDisenoVisual = function () {
            const btnSave = document.getElementById('btn-save');
            const txtSave = document.getElementById('txt-save');
            if (txtSave) txtSave.textContent = 'Guardando...';
            if (btnSave) btnSave.disabled = true;

            const html = editor.getHtml();
            const css = editor.getCss();
            const projectData = editor.getProjectData();
            normalizarVideos(projectData);

            // Combinar estilos scoped con HTML para el aprendiz
            const finalHtml = `<style>${css}</style>\n${html}`;
            const canvasState = JSON.stringify(projectData);

            fetch(saveUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    _method: 'PUT',
                    contenido_html: finalHtml,
                    canvas_state: canvasState
                })
            })
            .then(async res => {
                const data = await res.json().catch(() => null);
                if (!res.ok) {
                    const msg = (data && data.message) ? data.message : 'Error HTTP ' + res.status;
                    throw new Error(msg);
                }
                return data;
            })
            .then(data => {
                if (txtSave) txtSave.textContent = 'Guardar';
                if (btnSave) btnSave.disabled = false;
                mostrarNotificacion('¡Diseño guardado exitosamente en el sistema!', 'success');
            })
            .catch(err => {
                console.error('Error al guardar diseño visual:', err);
                if (txtSave) txtSave.textContent = 'Guardar';
                if (btnSave) btnSave.disabled = false;
                mostrarNotificacion(err.message || 'Error al guardar el diseño. Intenta nuevamente.', 'error');
            });
        };

        const btnSave = document.getElementById('btn-save');
        if (btnSave) {
            btnSave.addEventListener('click', window.guardarDisenoVisual);
        }

        // Atajo de teclado: Ctrl + S / Cmd + S
        document.addEventListener('keydown', function (e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                window.guardarDisenoVisual();
            }
        });

        // 8. Notificaciones Toast Flotantes
        function mostrarNotificacion(mensaje, tipo = 'success') {
            let toast = document.getElementById('sisig-builder-toast');
            if (!toast) {
                toast = document.createElement('div');
                toast.id = 'sisig-builder-toast';
                toast.style.position = 'fixed';
                toast.style.bottom = '24px';
                toast.style.right = '24px';
                toast.style.padding = '12px 20px';
                toast.style.borderRadius = '12px';
                toast.style.fontSize = '13px';
                toast.style.fontWeight = '700';
                toast.style.zIndex = '9999';
                toast.style.boxShadow = '0 10px 25px rgba(0,0,0,0.3)';
                toast.style.display = 'flex';
                toast.style.alignItems = 'center';
                toast.style.gap = '8px';
                toast.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                document.body.appendChild(toast);
            }

            if (tipo === 'success') {
                toast.style.backgroundColor = '#166534';
                toast.style.color = '#ffffff';
                toast.innerHTML = '<i class="fas fa-check-circle text-emerald-300"></i> ' + mensaje;
            } else {
                toast.style.backgroundColor = '#991b1b';
                toast.style.color = '#ffffff';
                toast.innerHTML = '<i class="fas fa-exclamation-triangle text-rose-300"></i> ' + mensaje;
            }

            toast.style.opacity = '1';
            toast.style.transform = 'translateY(0)';

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(10px)';
            }, 3500);
        }

        // 13. Sincronización robusta de apertura/cierre de categorías del panel de bloques
        document.addEventListener('click', (e) => {
            const catTitle = e.target.closest('.gjs-block-category .gjs-title');
            if (!catTitle) return;
            const categoryEl = catTitle.closest('.gjs-block-category');
            if (!categoryEl) return;
            const blocksContainer = categoryEl.querySelector('.gjs-blocks-c');
            if (!blocksContainer) return;

            // Esperar un breve instante para que GrapesJS actualice el estado interno de la categoría
            setTimeout(() => {
                const isClosed = !categoryEl.classList.contains('gjs-open') || 
                                 blocksContainer.style.display === 'none' ||
                                 Boolean(catTitle.querySelector('.fa-caret-right, .caretR'));
                
                if (isClosed) {
                    blocksContainer.style.setProperty('display', 'none', 'important');
                } else {
                    blocksContainer.style.setProperty('display', 'grid', 'important');
                }
            }, 25);
        });
    });
})();
