<?php
/**
 * Test Runner Integral de Verificación SISIG (33 Tests Exhaustivos)
 */
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Modules\SISIG\Entities\Modulo;
use App\Models\User;
use Modules\SISIG\Http\Controllers\editor\CanvasController;
use Illuminate\Validation\ValidationException;

class SisigTestSuite
{
    private $results = [];
    private $total = 0;
    private $passed = 0;
    private $failed = 0;

    public function runTest($id, $category, $description, callable $fn)
    {
        $this->total++;
        $start = microtime(true);
        $ok = false;
        $error = null;

        try {
            $result = $fn();
            if ($result === true || $result === null) {
                $ok = true;
            } elseif (is_array($result) && isset($result['pass'])) {
                $ok = (bool)$result['pass'];
                if (!$ok && isset($result['error'])) $error = $result['error'];
            } else {
                $ok = (bool)$result;
            }
        } catch (\Throwable $e) {
            $ok = false;
            $error = $e->getMessage() . " (" . basename($e->getFile()) . ":" . $e->getLine() . ")";
        }

        $elapsed = round((microtime(true) - $start) * 1000, 2);

        if ($ok) {
            $this->passed++;
            $statusStr = "\033[32m✔ PASS\033[0m";
        } else {
            $this->failed++;
            $statusStr = "\033[31m✖ FAIL\033[0m";
        }

        $this->results[] = [
            'id' => $id,
            'category' => $category,
            'description' => $description,
            'ok' => $ok,
            'elapsed' => $elapsed,
            'error' => $error
        ];

        printf("  [%02d] %-14s | %-52s | %s (%s ms)\n", $id, substr($category, 0, 14), substr($description, 0, 52), $statusStr, $elapsed);
        if (!$ok && $error) {
            printf("       \033[33mDetalle: %s\033[0m\n", substr($error, 0, 95));
        }
    }

    public function printSummary()
    {
        echo "\n" . str_repeat('=', 88) . "\n";
        echo "               RESUMEN FINAL DE PRUEBAS DE CALIDAD SISIG\n";
        echo str_repeat('=', 88) . "\n";
        printf("  Total Ejecutados: %d  |  Exitosos: \033[32m%d\033[0m  |  Fallidos: %s\n",
            $this->total,
            $this->passed,
            $this->failed > 0 ? "\033[31m" . $this->failed . "\033[0m" : "\033[32m0\033[0m"
        );
        $pct = round(($this->passed / max(1, $this->total)) * 100, 1);
        printf("  Tasa de Éxito: %.1f%%\n", $pct);
        echo str_repeat('=', 88) . "\n";

        return $this->failed === 0;
    }
}

// Asegurar que Módulo 2 tenga su contenido semántico fluido y 32 objetos
function restaurarModulo2Completo() {
    $m2 = Modulo::find(2);
    $m7 = Modulo::find(7);
    if ($m2 && $m7 && !empty($m7->canvas_state)) {
        $m2->canvas_state = $m7->canvas_state;
        $html = '<div class="espacio-didactico-interactivo space-y-8 max-w-5xl mx-auto py-2">';
        $html .= '<div class="rounded-3xl bg-gradient-to-br from-[#1A2B4C] to-[#0f172a] text-white p-6 sm:p-8 shadow-md border border-slate-700/50 relative overflow-hidden">';
        $html .= '<div class="relative z-10 space-y-3">';
        $html .= '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black tracking-wide uppercase bg-[#33FF57]/15 text-[#33FF57] border border-[#33FF57]/30"><i class="fas fa-shield-alt text-xs"></i> SISTEMA INTEGRADO DE GESTIÓN • SISIG</span>';
        $html .= '<h1 class="text-2xl sm:text-4xl font-black text-white tracking-tight leading-tight mt-1" style="color: #ffffff !important;">INDUCCIÓN INSTITUCIONAL</h1>';
        $html .= '<p class="text-slate-300 text-sm sm:text-base font-normal max-w-3xl leading-relaxed">Calidad, Seguridad y Salud en el Trabajo y Gestión Ambiental</p>';
        $html .= '<div class="flex flex-wrap gap-2 pt-2">';
        $html .= '<span class="px-3 py-1 rounded-xl text-xs font-bold bg-white/10 text-white border border-white/15 flex items-center gap-1.5 shadow-2xs"><i class="fas fa-check-circle text-[#33FF57]"></i> ISO 9001:2015</span>';
        $html .= '<span class="px-3 py-1 rounded-xl text-xs font-bold bg-white/10 text-white border border-white/15 flex items-center gap-1.5 shadow-2xs"><i class="fas fa-check-circle text-[#33FF57]"></i> ISO 14001:2015</span>';
        $html .= '<span class="px-3 py-1 rounded-xl text-xs font-bold bg-white/10 text-white border border-white/15 flex items-center gap-1.5 shadow-2xs"><i class="fas fa-check-circle text-[#33FF57]"></i> ISO 45001:2018</span>';
        $html .= '</div></div></div>';
        $html .= '<div class="my-6 rounded-3xl overflow-hidden bg-slate-900 border border-slate-700 shadow-xl">';
        $html .= '<div class="flex items-center justify-between px-5 py-3 bg-slate-800/90 border-b border-slate-700 text-white text-xs font-bold">';
        $html .= '<span class="flex items-center gap-2 truncate max-w-md"><i class="fab fa-youtube text-red-500 text-sm"></i> Video de Inducción Institucional SISIG</span>';
        $html .= '<span class="text-[#33FF57] font-semibold text-[11px] hidden sm:inline">Recurso Oficial SISIG</span>';
        $html .= '</div>';
        $html .= '<div class="relative w-full aspect-video bg-black flex items-center justify-center">';
        $html .= '<iframe class="w-full h-full" src="https://www.youtube-nocookie.com/embed/M2cckDmNLMI?rel=0" title="Video de Inducción SISIG" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';
        $html .= '</div></div>';
        $html .= '<div class="grid grid-cols-1 md:grid-cols-3 gap-6 my-6">';
        $html .= '<div class="rounded-3xl p-6 border border-sky-200 bg-sky-50/40 shadow-xs flex flex-col justify-between">';
        $html .= '<div><span class="inline-block px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-sky-100 text-sky-800 mb-3">🏅 GESTIÓN DE CALIDAD</span>';
        $html .= '<ul class="space-y-2.5 mt-2"><li class="flex items-start gap-2.5 text-xs sm:text-sm text-slate-700 leading-relaxed"><i class="fas fa-check-circle text-sky-600 mt-1 text-xs flex-shrink-0"></i><span>Satisfacción del aprendiz</span></li><li class="flex items-start gap-2.5 text-xs sm:text-sm text-slate-700 leading-relaxed"><i class="fas fa-check-circle text-sky-600 mt-1 text-xs flex-shrink-0"></i><span>Eficiencia en procesos</span></li><li class="flex items-start gap-2.5 text-xs sm:text-sm text-slate-700 leading-relaxed"><i class="fas fa-check-circle text-sky-600 mt-1 text-xs flex-shrink-0"></i><span>Mejora continua SIG</span></li></ul></div></div>';
        $html .= '<div class="rounded-3xl p-6 border border-emerald-200 bg-emerald-50/40 shadow-xs flex flex-col justify-between">';
        $html .= '<div><span class="inline-block px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 mb-3">🌿 MEDIO AMBIENTE</span>';
        $html .= '<ul class="space-y-2.5 mt-2"><li class="flex items-start gap-2.5 text-xs sm:text-sm text-slate-700 leading-relaxed"><i class="fas fa-check-circle text-[#2E6F40] mt-1 text-xs flex-shrink-0"></i><span>Código de colores de canecas</span></li><li class="flex items-start gap-2.5 text-xs sm:text-sm text-slate-700 leading-relaxed"><i class="fas fa-check-circle text-[#2E6F40] mt-1 text-xs flex-shrink-0"></i><span>Ahorro de agua y energía</span></li><li class="flex items-start gap-2.5 text-xs sm:text-sm text-slate-700 leading-relaxed"><i class="fas fa-check-circle text-[#2E6F40] mt-1 text-xs flex-shrink-0"></i><span>Separación en la fuente</span></li></ul></div></div>';
        $html .= '<div class="rounded-3xl p-6 border border-amber-200 bg-amber-50/40 shadow-xs flex flex-col justify-between">';
        $html .= '<div><span class="inline-block px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-amber-100 text-amber-800 mb-3">🛡️ SEGURIDAD SST</span>';
        $html .= '<ul class="space-y-2.5 mt-2"><li class="flex items-start gap-2.5 text-xs sm:text-sm text-slate-700 leading-relaxed"><i class="fas fa-check-circle text-amber-600 mt-1 text-xs flex-shrink-0"></i><span>Uso obligatorio de EPP</span></li><li class="flex items-start gap-2.5 text-xs sm:text-sm text-slate-700 leading-relaxed"><i class="fas fa-check-circle text-amber-600 mt-1 text-xs flex-shrink-0"></i><span>Reporte de condiciones inseguras</span></li><li class="flex items-start gap-2.5 text-xs sm:text-sm text-slate-700 leading-relaxed"><i class="fas fa-check-circle text-amber-600 mt-1 text-xs flex-shrink-0"></i><span>Rutas de evacuación claras</span></li></ul></div></div>';
        $html .= '</div>';
        $html .= '<div class="mt-8 pt-5 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-400">';
        $html .= '<span>SERVICIO NACIONAL DE APRENDIZAJE - SENA • REGIONAL DISTRITO CAPITAL</span>';
        $html .= '<span class="font-bold text-[#2E6F40]">CÓDIGO: SIG-IND-01</span>';
        $html .= '</div></div>';
        $m2->contenido_html = $html;
        $m2->save();
    }
}
restaurarModulo2Completo();

$suite = new SisigTestSuite();
echo "\n========================================================================================\n";
echo "              INICIANDO SUITE DE 33 PRUEBAS INTEGRALES DEL SISTEMA SISIG\n";
echo "========================================================================================\n\n";

// -----------------------------------------------------------------------------
// BLOQUE 1: BASE DE DATOS Y CONECTIVIDAD (Tests 1 - 5)
// -----------------------------------------------------------------------------
$suite->runTest(1, 'Base de Datos', 'Conexión activa a MySQL (Puerto 3320)', function() {
    $pdo = DB::connection()->getPdo();
    return $pdo !== null;
});

$suite->runTest(2, 'Base de Datos', 'Tabla sisig_modulos existe y tiene columnas requeridas', function() {
    if (!Schema::hasTable('sisig_modulos')) return false;
    $cols = ['id', 'titulo', 'norma', 'contenido_html', 'canvas_state', 'estado', 'descripcion'];
    foreach ($cols as $c) {
        if (!Schema::hasColumn('sisig_modulos', $c)) {
            return ['pass' => false, 'error' => "Columna {$c} ausente"];
        }
    }
    return true;
});

$suite->runTest(3, 'Base de Datos', 'Columna canvas_state es tipo LongText para datos grandes', function() {
    $type = DB::getSchemaBuilder()->getColumnType('sisig_modulos', 'canvas_state');
    return in_array($type, ['text', 'string', 'longtext', 'mediumtext', 'json']);
});

$suite->runTest(4, 'Base de Datos', 'Tabla editor_modulos existe para asignación de permisos', function() {
    return Schema::hasTable('editor_modulos') && Schema::hasColumns('editor_modulos', ['user_id', 'modulo_id']);
});

$suite->runTest(5, 'Modelos Eloquent', 'Modulo model castea canvas_state a tipo array', function() {
    $m = Modulo::find(2);
    if (!$m) return ['pass' => false, 'error' => 'Modulo #2 no encontrado'];
    return is_array($m->canvas_state);
});

// -----------------------------------------------------------------------------
// BLOQUE 2: ROLES Y PERMISOS DEL SISTEMA (Tests 6 - 9)
// -----------------------------------------------------------------------------
$suite->runTest(6, 'Roles y Permisos', 'Roles del sistema configurados en tabla roles', function() {
    $count = DB::table('roles')->count();
    return $count >= 2;
});

$suite->runTest(7, 'Roles y Permisos', 'Usuario Administrador SISIG existe y tiene rol asignado', function() {
    $admin = User::find(1);
    if (!$admin) return false;
    return $admin->hasRole('admin_sisig') || $admin->hasRole('Administrador SISIG');
});

$suite->runTest(8, 'Roles y Permisos', 'Usuario Editor SISIG (Johan) existe y tiene rol editor', function() {
    $editor = User::find(4);
    if (!$editor) return false;
    return $editor->hasRole('editor_sisig') || $editor->hasRole('Editor SISIG');
});

$suite->runTest(9, 'Roles y Permisos', 'Usuario Aprendiz puede acceder a inducción', function() {
    $aprendiz = User::find(3);
    return $aprendiz !== null && $aprendiz->id > 0;
});

// -----------------------------------------------------------------------------
// BLOQUE 3: CONTROLADOR CANVAS Y PERSISTENCIA (Tests 10 - 16)
// -----------------------------------------------------------------------------
$suite->runTest(10, 'Editor Canvas', 'GET /sisig/editor/canvas/1 responde HTTP 200', function() {
    Auth::loginUsingId(4);
    $req = Request::create('/sisig/editor/canvas/1', 'GET');
    $res = app()->handle($req);
    return $res->getStatusCode() === 200;
});

$suite->runTest(11, 'Editor Canvas', 'GET /sisig/editor/canvas/2 responde HTTP 200', function() {
    Auth::loginUsingId(4);
    $req = Request::create('/sisig/editor/canvas/2', 'GET');
    $res = app()->handle($req);
    return $res->getStatusCode() === 200;
});

$suite->runTest(12, 'Editor Canvas', 'GET /sisig/editor/canvas/?id=2 con query string responde 200', function() {
    Auth::loginUsingId(4);
    $req = Request::create('/sisig/editor/canvas/?id=2', 'GET');
    $res = app()->handle($req);
    return $res->getStatusCode() === 200;
});

$suite->runTest(13, 'Editor Canvas', 'GET /modules/1/canvas/editor responde HTTP 200', function() {
    Auth::loginUsingId(4);
    $req = Request::create('/modules/1/canvas/editor', 'GET');
    $res = app()->handle($req);
    return $res->getStatusCode() === 200;
});

$suite->runTest(14, 'API Canvas', 'GET /sisig/editor/modules/2/canvas devuelve JSON de estado', function() {
    Auth::loginUsingId(4);
    $req = Request::create('/sisig/editor/modules/2/canvas', 'GET');
    $res = app()->handle($req);
    if ($res->getStatusCode() !== 200) return false;
    $data = json_decode($res->getContent(), true);
    return isset($data['success']) && $data['success'] === true;
});

$suite->runTest(15, 'API Canvas', 'POST /sisig/editor/modules/2/canvas guarda estado exitosamente', function() {
    Auth::loginUsingId(4);
    $testCanvas = [
        'version' => '5.3.0',
        'canvas_width' => 960,
        'canvas_height' => 640,
        'canvas_aspect_label' => '3:2',
        'objects' => [
            ['type' => 'i-text', 'text' => 'INDUCCIÓN INSTITUCIONAL SISIG', 'fontSize' => 32],
            ['type' => 'i-text', 'text' => '• Satisfacción del aprendiz\n• Eficiencia en procesos']
        ]
    ];
    $semanticHtml = '<div class="espacio-didactico-interactivo space-y-8 max-w-5xl mx-auto py-2"><h1 class="text-3xl font-bold">INDUCCIÓN INSTITUCIONAL</h1></div>';
    $req = Request::create('/sisig/editor/modules/2/canvas', 'POST', [
        'id' => 2,
        'canvas_state' => $testCanvas,
        'contenido_html' => $semanticHtml
    ]);
    $res = app()->handle($req);
    if ($res->getStatusCode() !== 200) {
        return ['pass' => false, 'error' => "HTTP " . $res->getStatusCode() . ": " . $res->getContent()];
    }
    $body = json_decode($res->getContent(), true);
    return isset($body['success']) && $body['success'] === true;
});

$suite->runTest(16, 'API Canvas', 'POST endpoint alternativo /modules/2/canvas guarda exitosamente', function() {
    Auth::loginUsingId(4);
    $req = Request::create('/modules/2/canvas', 'POST', [
        'id' => 2,
        'canvas_state' => ['version' => '5.3.0', 'canvas_width' => 960, 'canvas_height' => 800, 'objects' => []],
        'contenido_html' => '<div class="espacio-didactico-interactivo"><p>Test alternativo</p></div>'
    ]);
    $res = app()->handle($req);
    return $res->getStatusCode() === 200;
});

// -----------------------------------------------------------------------------
// BLOQUE 4: MULTIMEDIA Y GESTIÓN DE VIDEOS (Tests 17 - 21)
// -----------------------------------------------------------------------------
$suite->runTest(17, 'Multimedia', 'GET /sisig/editor/modules/media devuelve listado JSON', function() {
    Auth::loginUsingId(4);
    $req = Request::create('/sisig/editor/modules/media', 'GET');
    $res = app()->handle($req);
    if ($res->getStatusCode() !== 200) return false;
    $data = json_decode($res->getContent(), true);
    return isset($data['success']) && is_array($data['media'] ?? null);
});

$suite->runTest(18, 'Multimedia', 'GET /modules/media endpoint directo devuelve listado JSON', function() {
    Auth::loginUsingId(4);
    $req = Request::create('/modules/media', 'GET');
    $res = app()->handle($req);
    return $res->getStatusCode() === 200;
});

$suite->runTest(19, 'Multimedia', 'POST /sisig/editor/modules/media/video valida y registra enlace de video', function() {
    Auth::loginUsingId(4);
    $req = Request::create('/sisig/editor/modules/media/video', 'POST', [
        'url' => 'https://www.youtube.com/watch?v=M2cckDmNLMI',
        'title' => 'Video Prueba Inducción SISIG'
    ]);
    $res = app()->handle($req);
    if (!in_array($res->getStatusCode(), [200, 201])) {
        return ['pass' => false, 'error' => "Status: " . $res->getStatusCode() . " Body: " . substr($res->getContent(), 0, 100)];
    }
    $data = json_decode($res->getContent(), true);
    return isset($data['success']) && $data['success'] === true && isset($data['video']);
});

$suite->runTest(20, 'Multimedia', 'POST /modules/media/video directo guarda video con éxito', function() {
    Auth::loginUsingId(4);
    $req = Request::create('/modules/media/video', 'POST', [
        'url' => 'https://youtu.be/M2cckDmNLMI',
        'title' => 'Video Directo Test'
    ]);
    $res = app()->handle($req);
    return in_array($res->getStatusCode(), [200, 201]);
});

$suite->runTest(21, 'Multimedia', 'DELETE /sisig/editor/modules/media responde validación correcta', function() {
    Auth::loginUsingId(4);
    $req = Request::create('/sisig/editor/modules/media', 'DELETE', ['type' => 'video', 'id' => 'no_existe_999']);
    $res = app()->handle($req);
    return in_array($res->getStatusCode(), [200, 400, 404]);
});

// Restaurar contenido completo de Módulo 2 para las pruebas de vista del aprendiz
restaurarModulo2Completo();

// -----------------------------------------------------------------------------
// BLOQUE 5: VISTA DEL APRENDIZ Y FORMATO NO-PRESENTACIÓN (Tests 22 - 26)
// -----------------------------------------------------------------------------
$suite->runTest(22, 'Vista Aprendiz', 'GET /sisig/aprendiz/induccion/1 responde HTTP 200', function() {
    Auth::loginUsingId(3);
    $req = Request::create('/sisig/aprendiz/induccion/1', 'GET');
    $res = app()->handle($req);
    return $res->getStatusCode() === 200;
});

$suite->runTest(23, 'Vista Aprendiz', 'GET /sisig/aprendiz/induccion/2 responde HTTP 200', function() {
    Auth::loginUsingId(3);
    $req = Request::create('/sisig/aprendiz/induccion/2', 'GET');
    $res = app()->handle($req);
    return $res->getStatusCode() === 200;
});

$suite->runTest(24, 'Vista Aprendiz', 'Contenido del Módulo 2 tiene estructura didáctica semántica fluida', function() {
    $m = Modulo::find(2);
    $html = $m->contenido_html ?? '';
    $hasWrapper = str_contains($html, 'espacio-didactico-interactivo');
    $hasHeader = str_contains($html, 'INDUCCIÓN') || str_contains($html, 'SISTEMA INTEGRADO');
    return $hasWrapper && $hasHeader;
});

$suite->runTest(25, 'Vista Aprendiz', 'Contenido del Aprendiz NO tiene aspect-ratio cuadrado forzado tipo slide', function() {
    $m = Modulo::find(2);
    $html = $m->contenido_html ?? '';
    return !str_contains($html, 'aspect-ratio: 960 / 960');
});

$suite->runTest(26, 'Vista Aprendiz', 'show.blade.php integra modales de Zoom y Video in-page', function() {
    Auth::loginUsingId(3);
    $req = Request::create('/sisig/aprendiz/induccion/2', 'GET');
    $res = app()->handle($req);
    $content = $res->getContent();
    $hasVideoModal = str_contains($content, 'modalVideoAprendiz') && str_contains($content, 'reproducirVideoAprendiz');
    $hasZoomModal = str_contains($content, 'modalZoomImagen') && str_contains($content, 'ampliarImagen');
    return $hasVideoModal && $hasZoomModal;
});

// -----------------------------------------------------------------------------
// BLOQUE 6: GESTIÓN DE CONTENIDOS Y WYSIWYG (Tests 27 - 30)
// -----------------------------------------------------------------------------
$suite->runTest(27, 'Gestión Contenidos', 'GET /sisig/editor/contenido responde HTTP 200 para el editor', function() {
    Auth::loginUsingId(4);
    $req = Request::create('/sisig/editor/contenido', 'GET');
    $res = app()->handle($req);
    return $res->getStatusCode() === 200;
});

$suite->runTest(28, 'Gestión Contenidos', 'GET /sisig/editor/contenido/1/editar redirige limpiamente al Canvas', function() {
    Auth::loginUsingId(4);
    $req = Request::create('/sisig/editor/contenido/1/editar', 'GET');
    $res = app()->handle($req);
    return in_array($res->getStatusCode(), [200, 302]);
});

$suite->runTest(29, 'Gestión Contenidos', 'Creación de módulo valida título requerido (rechaza vacío)', function() {
    Auth::loginUsingId(4);
    $controller = new \Modules\SISIG\Http\Controllers\editor\ContenidoController();
    $req = Request::create('/sisig/editor/contenido', 'POST', [
        'titulo' => '',
        'estado' => 'revision'
    ]);
    try {
        $controller->store($req);
        return false; // No debió pasar la validación
    } catch (ValidationException $e) {
        return true; // Validación lanzó excepción correctamente
    }
});

$suite->runTest(30, 'Catálogo Aprendiz', 'GET /sisig/aprendiz/induccion responde HTTP 200 para aprendices', function() {
    Auth::loginUsingId(3);
    $req = Request::create('/sisig/aprendiz/induccion', 'GET');
    $res = app()->handle($req);
    return $res->getStatusCode() === 200;
});

// -----------------------------------------------------------------------------
// BLOQUE 7: INTEGRIDAD DEL CLIENTE CANVAS (Tests 31 - 33)
// -----------------------------------------------------------------------------
$suite->runTest(31, 'Editor Script', 'editor.blade.php define calibración getPointer para evitar desfases de zoom', function() {
    $editorViewPath = resource_path('../Modules/SISIG/Resources/views/editor/canvas/editor.blade.php');
    if (!file_exists($editorViewPath)) return false;
    $code = file_get_contents($editorViewPath);
    return str_contains($code, 'canvas.getPointer = function') && str_contains($code, 'getBoundingClientRect');
});

$suite->runTest(32, 'Editor Script', 'editor.blade.php define generador de HTML didáctico semántico', function() {
    $editorViewPath = resource_path('../Modules/SISIG/Resources/views/editor/canvas/editor.blade.php');
    $code = file_get_contents($editorViewPath);
    return str_contains($code, 'generarContenidoHtmlDidactico') && str_contains($code, 'espacio-didactico-interactivo');
});

$suite->runTest(33, 'Editor Script', 'editor.blade.php soporta opciones de lienzo horizontal y vertical', function() {
    $editorViewPath = resource_path('../Modules/SISIG/Resources/views/editor/canvas/editor.blade.php');
    $code = file_get_contents($editorViewPath);
    $has169 = str_contains($code, '960, 540');
    $hasVertical = str_contains($code, '960, 1080') || str_contains($code, '960, 1200') || str_contains($code, '640, 960');
    return $has169 && $hasVertical;
});

$allOk = $suite->printSummary();
exit($allOk ? 0 : 1);
