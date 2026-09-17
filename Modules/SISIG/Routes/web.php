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
        Route::delete('/examenes/{quiz_id}/preguntas/{pregunta_id}', [\Modules\SISIG\Http\Controllers\Admin\GestionPreguntasController::class, 'destroy'])->name('examenes.preguntas.destroy');
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
        Route::delete('/examenes/{quiz_id}/preguntas/{pregunta_id}', [\Modules\SISIG\Http\Controllers\Editor\GestionPreguntasController::class, 'destroy'])->name('examenes.preguntas.destroy');
    });

    // Módulo del Aprendiz (Seguimiento, Inducción y Evaluaciones)
    Route::prefix('aprendiz')->name('aprendiz.')->middleware(['auth'])->group(function () {
        Route::get('/dashboard', [DashboardControllerAprendiz::class, 'index'])->name('dashboard');
    });

    // Módulo de Perfil de Usuario (Reutilizable para cualquier rol autenticado en SISIG)
    Route::middleware(['auth'])->group(function () {
        Route::get('/perfil', [PerfilusersController::class, 'index'])->name('perfil.index');
        Route::put('/perfil', [PerfilusersController::class, 'update'])->name('perfil.update');
        Route::put('/perfil/password', [PerfilusersController::class, 'updatePassword'])->name('perfil.password');
    });
});

