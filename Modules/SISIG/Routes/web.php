<?php

use Illuminate\Support\Facades\Route;
use Modules\SISIG\Http\Controllers\SISIGController;
use Modules\SISIG\Http\Controllers\Admin\DashboardController;
use Modules\SISIG\Http\Controllers\Admin\GestionUsersController;
use Modules\SISIG\Http\Controllers\Aprendiz\DashboardControllerAprendiz;
use Modules\SISIG\Http\Controllers\Editor\DashboardControllerEditor;
use Modules\SISIG\Http\Controllers\Perfilusers\PerfilusersController;
use Modules\SISIG\Http\Controllers\editor\ContenidoController;

Route::prefix('sisig')->name('sisig.')->group(function () {
    // Portal Informativo Público
    Route::get('/', [SISIGController::class, 'index'])->name('index');
    Route::get('/desarrolladores', [SISIGController::class, 'desarrolladores'])->name('desarrolladores');

    // Módulo de Administración (Exclusivo admin_sisig)
    Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/usuarios', [GestionUsersController::class, 'index'])->name('users.index');
        Route::post('/usuarios', [GestionUsersController::class, 'store'])->name('users.store');
        Route::put('/usuarios/{id}', [GestionUsersController::class, 'update'])->name('users.update');
        Route::patch('/usuarios/{id}/estado', [GestionUsersController::class, 'toggleStatus'])->name('users.toggleStatus');
        Route::put('/usuarios/{user}/rol', [GestionUsersController::class, 'updateRole'])->name('users.updateRole');
        Route::get('/usuarios/{id}/detalle', [GestionUsersController::class, 'show'])->name('users.show');
        Route::delete('/usuarios/{id}', [GestionUsersController::class, 'destroy'])->name('users.destroy');
        
        // Gestión de Módulos
        Route::get('/modulos', [\Modules\SISIG\Http\Controllers\Admin\GestionModulosController::class, 'index'])->name('modulos.index');
        Route::post('/modulos', [\Modules\SISIG\Http\Controllers\Admin\GestionModulosController::class, 'store'])->name('modulos.store');
        Route::put('/modulos/{id}', [\Modules\SISIG\Http\Controllers\Admin\GestionModulosController::class, 'update'])->name('modulos.update');
        Route::delete('/modulos/{id}', [\Modules\SISIG\Http\Controllers\Admin\GestionModulosController::class, 'destroy'])->name('modulos.destroy');
        Route::get('/modulos/{id}/contenido', [\Modules\SISIG\Http\Controllers\Admin\GestionModulosController::class, 'editContent'])->name('modulos.contenido');
        Route::put('/modulos/{id}/contenido', [\Modules\SISIG\Http\Controllers\Admin\GestionModulosController::class, 'updateContent'])->name('modulos.contenido.update');

        // Gestión de Exámenes (Quizzes)
        Route::get('/examenes', [\Modules\SISIG\Http\Controllers\Admin\GestionExamenesController::class, 'index'])->name('examenes.index');
        Route::post('/examenes', [\Modules\SISIG\Http\Controllers\Admin\GestionExamenesController::class, 'store'])->name('examenes.store');
        Route::put('/examenes/{id}', [\Modules\SISIG\Http\Controllers\Admin\GestionExamenesController::class, 'update'])->name('examenes.update');
        Route::patch('/examenes/{id}/estado', [\Modules\SISIG\Http\Controllers\Admin\GestionExamenesController::class, 'toggleStatus'])->name('examenes.toggleStatus');

        // Gestión de Preguntas de Examen
        Route::get('/examenes/{quiz_id}/preguntas', [\Modules\SISIG\Http\Controllers\Admin\GestionPreguntasController::class, 'index'])->name('examenes.preguntas.index');
        Route::post('/examenes/{quiz_id}/preguntas', [\Modules\SISIG\Http\Controllers\Admin\GestionPreguntasController::class, 'store'])->name('examenes.preguntas.store');
        Route::put('/examenes/{quiz_id}/preguntas/{pregunta_id}', [\Modules\SISIG\Http\Controllers\Admin\GestionPreguntasController::class, 'update'])->name('examenes.preguntas.update');
        Route::delete('/examenes/{quiz_id}/preguntas/{pregunta_id}', [\Modules\SISIG\Http\Controllers\Admin\GestionPreguntasController::class, 'destroy'])->name('examenes.preguntas.destroy');

        // Módulo de Seguridad y Accesos
        Route::get('/seguridad', [\Modules\SISIG\Http\Controllers\Admin\SeguridadController::class, 'index'])->name('seguridad.index');
        Route::delete('/seguridad/sesiones/{id}', [\Modules\SISIG\Http\Controllers\Admin\SeguridadController::class, 'cerrarSesion'])->name('seguridad.sesiones.destroy');

        // Módulo de Reportes y Métricas
        Route::get('/reportes', [\Modules\SISIG\Http\Controllers\Admin\ReportesController::class, 'index'])->name('reportes.index');

        // Módulo de Configuración de la Plataforma
        Route::get('/configuracion', [\Modules\SISIG\Http\Controllers\Admin\ConfiguracionController::class, 'index'])->name('configuracion.index');
        Route::put('/configuracion', [\Modules\SISIG\Http\Controllers\Admin\ConfiguracionController::class, 'update'])->name('configuracion.update');
        Route::post('/configuracion/reset', [\Modules\SISIG\Http\Controllers\Admin\ConfiguracionController::class, 'resetDefaults'])->name('configuracion.reset');
        Route::delete('/configuracion/branding/{clave}', [\Modules\SISIG\Http\Controllers\Admin\ConfiguracionController::class, 'removeBrandingFile'])->name('configuracion.removeBranding');
    });


    // Módulo de Edición de Contenidos (Exclusivo editor_sisig)
    Route::prefix('editor')->name('editor.')->middleware(['auth'])->group(function () {
        Route::get('/dashboard', [DashboardControllerEditor::class, 'index'])->name('dashboard');

        // Rutas de Gestión de Contenido para el Editor
        Route::get('/contenido', [ContenidoController::class, 'index'])->name('contenido.index');
        Route::get('/contenido/{id}/editar', [ContenidoController::class, 'edit'])->name('contenido.edit');
        Route::put('/contenido/{id}', [ContenidoController::class, 'update'])->name('contenido.update');

        // Gestión de Exámenes (Quizzes) para el Editor
        Route::get('/examenes', [\Modules\SISIG\Http\Controllers\Editor\GestionExamenesController::class, 'index'])->name('examenes.index');
        Route::post('/examenes', [\Modules\SISIG\Http\Controllers\Editor\GestionExamenesController::class, 'store'])->name('examenes.store');
        Route::put('/examenes/{id}', [\Modules\SISIG\Http\Controllers\Editor\GestionExamenesController::class, 'update'])->name('examenes.update');
        Route::patch('/examenes/{id}/estado', [\Modules\SISIG\Http\Controllers\Editor\GestionExamenesController::class, 'toggleStatus'])->name('examenes.toggleStatus');

        // Gestión de Preguntas de Examen para el Editor
        Route::get('/examenes/{quiz_id}/preguntas', [\Modules\SISIG\Http\Controllers\Editor\GestionPreguntasController::class, 'index'])->name('examenes.preguntas.index');
        Route::post('/examenes/{quiz_id}/preguntas', [\Modules\SISIG\Http\Controllers\Editor\GestionPreguntasController::class, 'store'])->name('examenes.preguntas.store');
        Route::put('/examenes/{quiz_id}/preguntas/{pregunta_id}', [\Modules\SISIG\Http\Controllers\Editor\GestionPreguntasController::class, 'update'])->name('examenes.preguntas.update');
        Route::delete('/examenes/{quiz_id}/preguntas/{pregunta_id}', [\Modules\SISIG\Http\Controllers\Editor\GestionPreguntasController::class, 'destroy'])->name('examenes.preguntas.destroy');

        // Gestión de Participantes (Exclusivo Editor)
        Route::get('/participantes', [\Modules\SISIG\Http\Controllers\Editor\ParticipantesController::class, 'index'])->name('participantes.index');

        // Seguimiento y Reportes (Exclusivo Editor)
        Route::get('/reportes', [\Modules\SISIG\Http\Controllers\Editor\ReportesController::class, 'index'])->name('reportes.index');

        // Auditoría de Infracciones y Anti-Trampa (Exclusivo Editor)
        Route::get('/infracciones', [\Modules\SISIG\Http\Controllers\Editor\InfraccionesController::class, 'index'])->name('infracciones.index');
    });

    // Módulo del Aprendiz (Seguimiento, Inducción y Evaluaciones)
    Route::prefix('aprendiz')->name('aprendiz.')->middleware(['auth'])->group(function () {
        Route::get('/dashboard', [DashboardControllerAprendiz::class, 'index'])->name('dashboard');
        
        // Módulos de Inducción y Contenido Didáctico Interactivo
        Route::get('/induccion', [\Modules\SISIG\Http\Controllers\Aprendiz\InduccionAprendizController::class, 'index'])->name('induccion.index');
        Route::get('/induccion/{id}', [\Modules\SISIG\Http\Controllers\Aprendiz\InduccionAprendizController::class, 'show'])->name('induccion.show');

        // Mis Evaluaciones y Quices Interactivos
        Route::get('/evaluaciones', [\Modules\SISIG\Http\Controllers\Aprendiz\EvaluacionesAprendizController::class, 'index'])->name('evaluaciones.index');
        Route::get('/evaluaciones/{id_quiz}/iniciar', [\Modules\SISIG\Http\Controllers\Aprendiz\EvaluacionesAprendizController::class, 'iniciar'])->name('evaluaciones.iniciar');
        Route::post('/evaluaciones/{id_resultado}/incidente', [\Modules\SISIG\Http\Controllers\Aprendiz\EvaluacionesAprendizController::class, 'registrarIncidente'])->name('evaluaciones.incidente');
        Route::post('/evaluaciones/{id_quiz}/calificar', [\Modules\SISIG\Http\Controllers\Aprendiz\EvaluacionesAprendizController::class, 'calificar'])->name('evaluaciones.calificar');
        Route::get('/evaluaciones/{id_resultado}/resultado', [\Modules\SISIG\Http\Controllers\Aprendiz\EvaluacionesAprendizController::class, 'verResultado'])->name('evaluaciones.resultado');

        // Historial y Calificaciones (Mis Resultados)
        Route::get('/historial', [\Modules\SISIG\Http\Controllers\Aprendiz\HistorialAprendizController::class, 'index'])->name('historial.index');

        // Presentación de Evaluaciones con Sistema Anti-Trampa y Auditoría (Rutas de compatibilidad)
        Route::get('/quiz/{id_quiz}/iniciar', [\Modules\SISIG\Http\Controllers\Aprendiz\EvaluacionesAprendizController::class, 'iniciar'])->name('quiz.iniciar');
        Route::get('/quiz/{id_resultado}/presentar', function ($id_resultado) {
            $resultado = \Modules\SISIG\Entities\Resultado::find($id_resultado);
            if ($resultado) {
                return redirect()->route('sisig.aprendiz.evaluaciones.iniciar', $resultado->id_quiz);
            }
            return redirect()->route('sisig.aprendiz.evaluaciones.index');
        })->name('quiz.presentar');
        Route::post('/quiz/{id_resultado}/incidente', [\Modules\SISIG\Http\Controllers\Aprendiz\EvaluacionesAprendizController::class, 'registrarIncidente'])->name('quiz.incidente');
        Route::get('/quiz/{id_resultado}/resultado', [\Modules\SISIG\Http\Controllers\Aprendiz\EvaluacionesAprendizController::class, 'verResultado'])->name('quiz.resultado');
    });

    // Módulo de Perfil de Usuario (Reutilizable para cualquier rol autenticado en SISIG)
    Route::middleware(['auth'])->group(function () {
        Route::get('/perfil', [PerfilusersController::class, 'index'])->name('perfil.index');
        Route::put('/perfil', [PerfilusersController::class, 'update'])->name('perfil.update');
        Route::put('/perfil/password', [PerfilusersController::class, 'updatePassword'])->name('perfil.password');
    });
});

