# Arquitectura, Trazabilidad y Conexión del Sistema SISIG (SENA Empresa)

Este documento es una **guía técnica y didáctica completa** para estudiar la arquitectura de software del módulo **SISIG**, la interacción de carpetas, el flujo de rutas, controladores, vistas y la lógica de programación detrás del **Sistema Anti-Trampa** y los **Filtros Dinámicos**.

---

## 1. Mapa y Estructura de Carpetas

El sistema está construido bajo la arquitectura modular de Laravel (`nwidart/laravel-modules`). Todo el ecosistema de SISIG reside en el directorio:
`c:\laragon\www\SISIG\Modules\SISIG\`

```text
Modules/SISIG/
├── Database/
│   ├── Migrations/               # Definición y evolución del esquema de base de datos
│   │   ├── 2026_10_19_000001_add_imagen_to_sisig_modulos_table.php
│   │   └── 2026_10_19_000002_add_fondo_to_sisig_modulos_table.php
│   └── Seeders/                  # Datos iniciales para pruebas
│
├── Entities/                     # Modelos Eloquent (ORM que conecta con la base de datos)
│   ├── Modulo.php                # Modelo de la tabla 'sisig_modulos'
│   ├── Quiz.php                  # Modelo de evaluaciones 'sisig_quices'
│   ├── Pregunta.php              # Modelo de preguntas de examen
│   ├── Opcion.php                # Opciones de respuesta por pregunta
│   ├── Resultado.php             # Intentos y notas de aprendices ('sisig_resultados')
│   └── InfraccionQuiz.php        # Registro de incidentes anti-trampa ('infraccionesquiz')
│
├── Http/
│   └── Controllers/              # Lógica de negocio dividida por roles de usuario
│       ├── admin/                # Controladores del Administrador
│       │   ├── GestionModulosController.php   # Creación, fotos y fondos de módulos
│       │   └── ReportesAdminController.php    # Reportes globales institucionales
│       │
│       ├── aprendiz/             # Controladores del Aprendiz
│       │   ├── DashboardControllerAprendiz.php
│       │   ├── InduccionAprendizController.php # Visualización didáctica de material
│       │   ├── EvaluacionesAprendizController.php # Presentación de quices y Anti-Trampa
│       │   └── HistorialAprendizController.php # Notas, intentos y aprobaciones
│       │
│       └── editor/               # Controladores del Instructor / Editor
│           ├── ContenidoController.php        # Edición de guías y contenidos
│           ├── GestionExamenesController.php  # Cuestionarios y preguntas
│           ├── InfraccionesController.php     # Auditoría de trampas y anulaciones
│           ├── ParticipantesController.php    # Directorio de aprendices y estado
│           └── ReportesController.php         # Métricas de aprobación por subsistema
│
├── Resources/
│   ├── assets/                   # Recursos estáticos fuente (JS, CSS, librerías)
│   │   ├── js/
│   │   │   └── anti-trampa.js    # Motor JavaScript de integridad académica
│   │   └── vendor/               # Librerías locales (Cropper.js y Quill.js)
│   │
│   └── views/                    # Vistas Blade (Interfaz de Usuario)
│       ├── admin/                # Vistas exclusivas de administración
│       ├── aprendiz/             # Interfaz de presentación de pruebas y contenidos
│       │   ├── evaluaciones/
│       │   │   ├── evaluaciones.blade.php     # Catálogo con filtro carrusel
│       │   │   ├── presentar.blade.php        # Examen en vivo con corazones 3D
│       │   │   └── resultado.blade.php        # Auditoría de notas e infracciones
│       │   └── quiz/
│       │       └── _script_antitrampa_inline.blade.php # Inyección reactiva
│       ├── editor/               # Interfaz del editor (Reportes, Infracciones, etc.)
│       │   ├── reportes/index.blade.php       # Reporte con filtro carrusel dinámico
│       │   ├── infracciones/index.blade.php   # Auditoría de quices anulados
│       │   └── participantes/index.blade.php  # Lista con carrusel de 3 módulos
│       └── components/           # Componentes reutilizables (layouts, banners, modales)
│
└── Routes/
    └── web.php                   # Mapeo de URL -> Controlador -> Método
```

---

## 2. Cómo se Conecta Todo (El Ciclo de Vida de una Petición)

Cada interacción del usuario sigue este flujo estandarizado:

```mermaid
graph TD
    A["Navegador Web (Usuario)"] -->|1. Petición HTTP / URL| B["Routes/web.php"]
    B -->|2. Middleware Auth y Enrutamiento| C["Controller (Lógica de Negocio)"]
    C -->|3. Consulta ORM (Solo Lectura)| D["Entities (Modelos Eloquent)"]
    D -->|4. SQL seguro| E[("Base de Datos MySQL")]
    E -->|5. Retorno de Registros| D
    D -->|6. Colección de Objetos| C
    C -->|7. view('vista', compact(...))| F["Views (Blade + TailwindCSS)"]
    F -->|8. Event Listeners & Fetch| G["Assets JS (anti-trampa.js)"]
    G -->|9. Petición asíncrona de incidente| B
```

---

## 3. Sistema Anti-Trampa e Integridad Académica (Explicación con Código)

El sistema anti-trampa fue diseñado con un modelo de **Defensa en Profundidad**: supervisión en tiempo real en el cliente y validación estricta con política *Zero Trust* en el servidor.

### Flujo 1: Intercepción de Eventos en el Navegador (`anti-trampa.js`)
El motor de JavaScript vigila activamente el teclado, el mouse, el portapapeles y el foco de la ventana:

```javascript
// 1. Bloqueo de Captura de Pantalla y Vaciado de Portapapeles
document.addEventListener('keydown', (e) => {
    const code = e.code ? e.code.toLowerCase() : '';
    const ctrl = e.ctrlKey || e.metaKey;
    const shift = e.shiftKey;
    const alt = e.altKey;

    // Función para borrar el contenido copiado
    const vaciarPortapapeles = () => {
        try {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText('').catch(() => {});
            }
        } catch (err) {}
    };

    // Detectar ImprPant / PrintScreen
    if (e.key === 'PrintScreen' || code === 'printscreen' || e.keyCode === 44) {
        e.preventDefault();
        vaciarPortapapeles();
        this.reportarIncidente('captura_pantalla', 'Intento de captura de pantalla (PrintScreen).');
        return;
    }

    // Detectar Recortes en Windows (Win+Shift+S) y Mac (Cmd+Shift+3/4/5)
    if ((ctrl || alt || e.metaKey) && shift && ['s', 'keys', '3', '4', '5'].includes(e.key.toLowerCase())) {
        e.preventDefault();
        vaciarPortapapeles();
        this.reportarIncidente('captura_pantalla', 'Intento de recorte de pantalla (Shift+S).');
        return;
    }

    // 2. Bloqueo de Atajos para Invocar Inteligencia Artificial
    // Brave Leo AI (Ctrl+B / Alt+B)
    if ((ctrl || alt) && (e.key.toLowerCase() === 'b')) {
        e.preventDefault();
        this.reportarIncidente('asistente_ia', 'Intento de invocar IA en navegador (Ctrl+B / Brave Leo).');
        return;
    }

    // Edge Copilot (Ctrl+Shift+.) o atajo Alt+C
    if (ctrl && shift && e.key === '.') {
        e.preventDefault();
        this.reportarIncidente('asistente_ia', 'Intento de abrir Copilot en Edge (Ctrl+Shift+.).');
        return;
    }

    // 3. Bloqueo de Inspección y DevTools (F12, F5, Ctrl+U)
    if (e.key === 'F12' || (ctrl && shift && ['i', 'j', 'c'].includes(e.key.toLowerCase())) || (ctrl && e.key.toLowerCase() === 'u')) {
        e.preventDefault();
        this.reportarIncidente('tecla_bloqueada', 'Intento de abrir herramientas de desarrollador o inspeccionar.');
        return;
    }
});
```

### Flujo 2: Detección de Foco y Cambio de Pestañas
```javascript
// Detectar si el usuario minimiza o cambia a otra pestaña
document.addEventListener('visibilitychange', () => {
    if (document.hidden && !this.bloqueado) {
        this.reportarIncidente('cambio_pestana', 'El aprendiz cambió de pestaña o minimizó el examen.');
    }
});

// Detectar si hace clic fuera del navegador (Alt+Tab, otra ventana)
window.addEventListener('blur', () => {
    if (!this.bloqueado) {
        const ahora = Date.now();
        if (ahora - this.ultimoIncidenteTiempo > this.cooldownMs) {
            this.reportarIncidente('desenfoque_ventana', 'El navegador perdió el foco (posible cambio de app).');
        }
    }
});
```

### Flujo 3: Envío Asíncrono del Incidente al Backend
```javascript
async reportarIncidente(tipo, descripcion) {
    this.ultimoIncidenteTiempo = Date.now();

    // Petición asíncrona segura con token CSRF
    const respuesta = await fetch(this.incidenteUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': this.csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            tipo_infraccion: tipo,
            tipo_evento: descripcion,
            tiempo_segundos: Math.round((Date.now() - this.tiempoInicio) / 1000)
        })
    });

    const data = await respuesta.json();
    this.faltasActuales = data.faltas;
    this.actualizarUIFaltas(); // Fractura los corazones 3D

    // Si el servidor confirma la anulación (3 faltas)
    if (data.anulado) {
        this.bloquearExamen('Has superado el límite de 3 faltas. Examen anulado.');
        setTimeout(() => {
            window.location.href = data.redirect;
        }, 2000);
    }
}
```

### Flujo 4: Recepción, Persistencia y Anulación en el Servidor (`EvaluacionesAprendizController.php`)
Ruta: `POST /aprendiz/evaluaciones/{id_resultado}/incidente` $\rightarrow$ método `registrarIncidente()`:

```php
public function registrarIncidente(Request $request, $idResultado)
{
    $resultado = Resultado::where('id_resultado', $idResultado)
        ->where('user_id', Auth::id())
        ->whereNull('fecha_presentacion')
        ->firstOrFail();

    // 1. Guardar la falta en la tabla 'infraccionesquiz'
    InfraccionQuiz::create([
        'id_resultado'      => $resultado->id_resultado,
        'user_id'           => Auth::id(),
        'id_quiz'           => $resultado->id_quiz,
        'tipo_infraccion'   => $request->input('tipo_infraccion'),
        'tipo_evento'       => $request->input('tipo_evento'),
        'marca_tiempo'      => now(),
        'navegador_detalles'=> $request->userAgent(),
        'ip_address'        => $request->ip(),
    ]);

    // 2. Contar el número total de faltas acumuladas en este intento
    $totalFaltas = $resultado->infracciones()->count();
    $resultado->update(['infracciones_detectadas' => $totalFaltas]);

    // 3. Regla de Oro: Si acumula 3 faltas -> ANULACIÓN INMEDIATA
    if ($totalFaltas >= 3) {
        $resultado->update([
            'fue_anulado'        => true,
            'motivo_anulacion'   => 'Anulación automática por acumular 3 incidentes de seguridad anti-trampa.',
            'fecha_presentacion' => now(),
            'puntaje'            => 0.00,
            'aprobado'           => false,
        ]);

        return response()->json([
            'status'   => 'anulado',
            'anulado'  => true,
            'faltas'   => $totalFaltas,
            'redirect' => route('sisig.aprendiz.evaluaciones.resultado', $resultado->id_resultado)
        ]);
    }

    return response()->json([
        'status'  => 'advertencia',
        'anulado' => false,
        'faltas'  => $totalFaltas,
        'vidas_restantes' => 3 - $totalFaltas
    ]);
}
```

### Flujo 5: Interfaz de Corazones 3D Volumétricos
En `presentar.blade.php`:
```html
<div class="flex items-center gap-2">
    @for($v = 1; $v <= 3; $v++)
        <div id="heart-life-{{ $v }}" class="heart-box heart-alive w-7 h-7 relative">
            <div class="heart-ground-shadow"></div>
            <div class="heart-body">
                <div class="heart-depth"></div>
                <div class="heart-rim-light"></div>
                <div class="heart-shine"></div>
                <div class="heart-crack"></div>
                <i class="fas fa-heart text-rose-500 text-lg"></i>
            </div>
        </div>
    @endfor
    <span id="vidas-restantes-texto" class="text-xs font-black text-rose-600">3 vidas</span>
</div>
```

---

## 4. Filtros Dinámicos de Secciones SIG (Carrusel de 3 Módulos)

El requisito exigía:
1. **No usar listas fijas**: Leer los módulos directamente de la base de datos para que módulos nuevos (ej. `aaaa`, `Ambiental`) aparezcan automáticamente.
2. **Capacidad estricta de 3 módulos**: Solo deben verse 3 a la vez. El 4to y subsiguientes se visualizan desplazándose con flechas `<` y `>`.
3. **Sin logos**: Retiro de iconos decorativos para mantener tipografía limpia.

### Lógica en el Controlador (`ReportesController.php`)
```php
public function index(Request $request)
{
    // 1. Consulta dinámica a la tabla 'sisig_modulos' sin alterar la base de datos
    $modulos = DB::table('sisig_modulos')
        ->where('estado', '!=', 'inactivo')
        ->orderBy('id', 'asc')
        ->get();

    // 2. Determinar el módulo activo según el ID o nombre enviado por URL
    $seccionInput = $request->input('seccion');
    $moduloSeleccionado = null;

    if ($seccionInput) {
        $moduloSeleccionado = is_numeric($seccionInput)
            ? $modulos->firstWhere('id', (int) $seccionInput)
            : $modulos->first(fn($m) => strtolower($m->titulo) === strtolower($seccionInput));
    }

    if (!$moduloSeleccionado) {
        $moduloSeleccionado = $modulos->first();
    }

    $seccion = $moduloSeleccionado ? $moduloSeleccionado->titulo : 'General';

    return view('sisig::editor.reportes.index', compact('modulos', 'moduloSeleccionado', 'seccion', ...));
}
```

### Arquitectura Matemática del Carrusel en Blade (`index.blade.php`)
Para forzar al navegador a renderizar **estrictamente 3 botones**, cada elemento ocupa exactamente un tercio del ancho disponible menos los espacios (`gap-1.5 = 6px`, 2 espacios = 12px):

$$\text{Ancho de cada botón} = \frac{100\% - 12\text{px}}{3}$$

```html
@php
    $tieneMasDeTres = $modulos->count() > 3;
@endphp

<div class="flex items-center gap-1.5 bg-slate-50 p-1.5 rounded-2xl border border-slate-200 text-xs">
    <span class="text-slate-400 font-bold px-2 hidden sm:inline">Sección SIG:</span>

    @if($tieneMasDeTres)
        <!-- Flecha Izquierda -->
        <button type="button" id="btn-nav-secciones-prev" onclick="desplazarSecciones('left')" 
                class="w-7 h-7 rounded-xl bg-white text-slate-500 hover:bg-slate-100 flex items-center justify-center">
            <i class="fas fa-chevron-left text-[10px]"></i>
        </button>
    @endif

    <!-- Contenedor con ancho fijo para 3 pastillas exactas -->
    <div id="contenedor-secciones-sig" 
         class="flex items-center gap-1.5 overflow-x-auto scroll-smooth {{ $tieneMasDeTres ? 'w-[240px] sm:w-[270px] md:w-[300px]' : '' }} py-0.5"
         style="scrollbar-width: none; -ms-overflow-style: none;">
        @foreach($modulos as $mod)
            @php
                $esActivo = ($moduloSeleccionado && $moduloSeleccionado->id == $mod->id);
            @endphp
            <a href="{{ route('sisig.editor.reportes.index', ['seccion' => $mod->id]) }}"
               title="{{ $mod->titulo }}"
               @if($tieneMasDeTres) style="flex: 0 0 calc((100% - 12px) / 3); min-width: 0;" @endif
               class="px-2.5 py-1.5 rounded-xl font-bold flex items-center justify-center whitespace-nowrap text-center truncate {{ $esActivo ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-200' }}">
                <span class="truncate">{{ $mod->titulo }}</span> <!-- Sin logos, solo texto -->
            </a>
        @endforeach
    </div>

    @if($tieneMasDeTres)
        <!-- Flecha Derecha -->
        <button type="button" id="btn-nav-secciones-next" onclick="desplazarSecciones('right')" 
                class="w-7 h-7 rounded-xl bg-white text-slate-500 hover:bg-slate-100 flex items-center justify-center">
            <i class="fas fa-chevron-right text-[10px]"></i>
        </button>
    @endif
</div>
```

### Función de Desplazamiento Suave en JavaScript
```javascript
function desplazarSecciones(direccion) {
    const contenedor = document.getElementById('contenedor-secciones-sig');
    if (!contenedor) return;

    // Desplazamiento exacto de 1 módulo (1/3 del ancho visible del contenedor)
    const paso = Math.round(contenedor.clientWidth / 3) + 2;
    contenedor.scrollBy({
        left: direccion === 'left' ? -paso : paso,
        behavior: 'smooth'
    });

    setTimeout(verificarLimitesScrollSecciones, 320);
}

// Opaca las flechas cuando se alcanza el límite izquierdo o derecho
function verificarLimitesScrollSecciones() {
    const contenedor = document.getElementById('contenedor-secciones-sig');
    const btnPrev = document.getElementById('btn-nav-secciones-prev');
    const btnNext = document.getElementById('btn-nav-secciones-next');
    if (!contenedor) return;

    if (btnPrev) {
        const alInicio = contenedor.scrollLeft <= 5;
        btnPrev.style.opacity = alInicio ? '0.35' : '1';
        btnPrev.style.pointerEvents = alInicio ? 'none' : 'auto';
    }
    if (btnNext) {
        const alFinal = (contenedor.scrollLeft + contenedor.clientWidth) >= (contenedor.scrollWidth - 5);
        btnNext.style.opacity = alFinal ? '0.35' : '1';
        btnNext.style.pointerEvents = alFinal ? 'none' : 'auto';
    }
}
```

---

## 5. Resumen de Conexiones de Rutas en `Routes/web.php`

| Ruta (URL) | Método HTTP | Controlador y Método | Rol / Propósito |
| :--- | :---: | :--- | :--- |
| `/sisig/editor/reportes` | `GET` | `ReportesController@index` | Seguimiento, aprobados/reprobados con filtro dinámico |
| `/sisig/editor/infracciones` | `GET` | `InfraccionesController@index` | Auditoría de exámenes anulados por trampa |
| `/sisig/editor/participantes`| `GET` | `ParticipantesController@index` | Directorio de aprendices con avance SIG |
| `/sisig/aprendiz/evaluaciones`| `GET` | `EvaluacionesAprendizController@index` | Catálogo de quices con carrusel sin logos |
| `/sisig/aprendiz/evaluaciones/{id}/iniciar` | `GET` | `EvaluacionesAprendizController@iniciar` | Crear intento y cargar examen en vivo |
| `/sisig/aprendiz/evaluaciones/{id}/incidente` | `POST` | `EvaluacionesAprendizController@registrarIncidente` | Reportar falta anti-trampa vía AJAX |
| `/sisig/aprendiz/evaluaciones/{id}/calificar` | `POST` | `EvaluacionesAprendizController@calificar` | Validar tiempo, calcular puntaje o anular |
| `/sisig/aprendiz/historial` | `GET` | `HistorialAprendizController@index` | Resumen de calificaciones con filtro carrusel |

---

## 6. Integridad de la Base de Datos

> [!IMPORTANT]
> **Política de No Alteración del Esquema:**
> Todas las mejoras de filtros dinámicos y anti-trampa se desarrollaron mediante **consultas de solo lectura** (`SELECT`) sobre las tablas existentes (`sisig_modulos`, `sisig_quices`, `sisig_resultados`, `infraccionesquiz`). No se alteraron columnas ni se destruyeron datos, asegurando total compatibilidad con la rama principal de producción.
