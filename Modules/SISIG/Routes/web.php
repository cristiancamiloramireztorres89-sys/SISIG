<?php

use Illuminate\Support\Facades\Route;
use Modules\SISIG\Http\Controllers\SISIGController;
use Modules\SISIG\Http\Controllers\Admin\DashboardController;
use Modules\SISIG\Http\Controllers\Aprendiz\DashboardControllerAprendiz;
use Modules\SISIG\Http\Controllers\Editor\DashboardControllerEditor;
use Modules\SISIG\Http\Controllers\Perfilusers\PerfilusersController;

Route::prefix('sisig')->name('sisig.')->group(function () {
    // Portal Informativo Público
    Route::get('/', [SISIGController::class, 'index'])->name('index');

    // Módulo de Administración (Exclusivo admin_sisig)
    Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

    // Módulo de Edición de Contenidos (Exclusivo editor_sisig)
    Route::prefix('editor')->name('editor.')->middleware(['auth'])->group(function () {
        Route::get('/dashboard', [DashboardControllerEditor::class, 'index'])->name('dashboard');
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

