<?php

use Illuminate\Support\Facades\Route;
use Modules\SISIG\Http\Controllers\SISIGController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('sisigs', SISIGController::class)->names('sisig');
});
