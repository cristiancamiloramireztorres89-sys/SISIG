<?php

use Illuminate\Support\Facades\Route;
use Modules\SISIG\Http\Controllers\SISIGController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('sisigs', SISIGController::class)->names('sisig');
});
