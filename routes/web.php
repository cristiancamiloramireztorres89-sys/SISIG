<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Modules\SICA\Entities\Bloque;
use App\Http\Controllers\Auth\LoginController;
use Modules\SISIG\Http\Controllers\editor\CanvasController;

Route::get('/', function () {
    $bloques = collect();
    if (Schema::hasTable('bloques')) {
        $bloques = Bloque::with(['apps' => function ($query) {
            $query->orderBy('name');
        }])->orderBy('order_index')->get();
    }
    return view('welcome', compact('bloques'));
})->name('home');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas del Editor Visual Interactivo (Persistencia de Lienzo y Carga Multimedia)
Route::get('/modules/{id?}/canvas/editor', [CanvasController::class, 'showEditor'])->name('modules.canvas.editor');
Route::post('/modules/{id?}/canvas', [CanvasController::class, 'saveState'])->name('modules.canvas.save');
Route::get('/modules/{id?}/canvas', [CanvasController::class, 'getState'])->name('modules.canvas.get');
Route::post('/modules/upload-media', [CanvasController::class, 'uploadMedia'])->name('modules.upload_media');
Route::get('/modules/media', [CanvasController::class, 'getMedia'])->name('modules.media.list');
Route::post('/modules/media/video', [CanvasController::class, 'saveVideoLink'])->name('modules.media.video');
Route::delete('/modules/media', [CanvasController::class, 'deleteMedia'])->name('modules.media.delete');

// Alias de contingencia para editor
Route::post('/sisig/editor/modules/{id?}/canvas', [CanvasController::class, 'saveState']);
Route::post('/sisig/modules/{id?}/canvas', [CanvasController::class, 'saveState']);
