<?php

use App\Http\Controllers\PersonsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/persons/list', [PersonsController::class, 'list']);
