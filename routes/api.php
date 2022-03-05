<?php

use Illuminate\Support\Facades\Route;


Route::any('{any?}', \App\Http\Controllers\RedirectController::class)
    ->where('any', '.*');
