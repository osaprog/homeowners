<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PersonsController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', [PersonsController::class, 'index'])
->name('home');

Route::post('persons/store', [PersonsController::class, 'store'])
    ->name('persons.store');

Route::post('persons/upload', [PersonsController::class, 'upload'])
    ->name('persons.upload');

Route::post('persons/delete', [PersonsController::class, 'delete'])
    ->name('persons.delete');
