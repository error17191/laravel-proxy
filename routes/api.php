<?php

use Illuminate\Support\Facades\Route;


Route::domain('proxy.codejar.dev')->any('{any?}', \App\Http\Controllers\RedirectController::class)
    ->where('any', '.*');

Route::domain('estate.codejar.dev')->any('{any?}', \App\Http\Controllers\EstateRedirectController::class)
    ->where('any', '.*');
