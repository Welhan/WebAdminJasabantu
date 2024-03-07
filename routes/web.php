<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\IndexController;
use App\Http\Controllers\MidtransController;
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
    Route::get('/getData', [MitraController::class, 'getData']);
    Route::get('/tableData', [MitraController::class, 'tableData']);
    Route::get('/newMitra', [MitraController::class, 'newMitra']);
    Route::get('/editMitra', [MitraController::class, 'editMitra']);
    Route::get('/deleteMitra', [MitraController::class, 'deleteMitra']);
    Route::get('/auto_generate', [MitraController::class, 'generatePin']);
    // Route::get('/checkEmail', [MitraController::class, 'checkEmail']);
    // Route::get('/checkPhone', [MitraController::class, 'checkPhone']);
    Route::get('/getUser', [MitraController::class, 'getUser']);
    Route::post('/store', [MitraController::class, 'store']);
    Route::post('/update', [MitraController::class, 'update']);
    Route::post('/delete', [MitraController::class, 'delete']);
});
// Route::get('/user-management', [UserController::class, 'index']);

Route::prefix('/user-management')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/getData', [UserController::class, 'getData']);
    Route::get('/tableData', [UserController::class, 'tableData']);
    Route::get('/editUser', [UserController::class, 'editUser']);
    Route::get('/deleteUser', [UserController::class, 'deleteUser']);
});

Route::prefix('/midtrans')->group(function () {
    Route::get('/', [MidtransController::class, 'index']);
    Route::get('/getData', [MidtransController::class, 'getData']);
    Route::get('/tableData', [MidtransController::class, 'tableData']);
    Route::get('/newMidtrans', [MidtransController::class, 'newMidtrans']);
    Route::get('/editMidtrans', [MidtransController::class, 'editMidtrans']);
    Route::get('/deleteMidtrans', [MidtransController::class, 'deleteMidtrans']);
    Route::post('/store', [MidtransController::class, 'store']);
    Route::post('/update', [MidtransController::class, 'update']);
    Route::post('/delete', [MidtransController::class, 'delete']);
});


Route::get('/login', [AuthController::class, 'index']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);
