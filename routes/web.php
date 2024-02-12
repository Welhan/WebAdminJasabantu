<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\IndexController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\UserController;

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

Route::get('/', [indexController::class, 'index']);

Route::prefix('/mitra-management')->group(function () {
    Route::get('/', [MitraController::class, 'index']);
    Route::get('/getDataMitra', [MitraController::class, 'getDataMitra']);
    Route::get('/newMitra', [MitraController::class, 'newMitra']);
});
// Route::get('/user-management', [UserController::class, 'index']);
