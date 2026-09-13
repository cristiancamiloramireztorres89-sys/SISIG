<?php

use Illuminate\Support\Facades\Route;
use Modules\SISIG\Http\Controllers\SISIGController;

Route::prefix('sisig')->name('sisig.')->group(function () {
    Route::get('/', [SISIGController::class, 'index'])->name('index');
});
