<?php

use App\Models\SubCategoryModel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\SubCategoryController;

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

Route::prefix('/merchant-management')->group(function () {
    Route::get('/', [MerchantController::class, 'index']);
    Route::get('/getData', [MerchantController::class, 'getData']);
    Route::get('/tableData', [MerchantController::class, 'tableData']);
    Route::get('/newMerchant', [MerchantController::class, 'newMerchant']);
    Route::get('/editMerchant', [MerchantController::class, 'editMerchant']);
    Route::get('/deleteMerchant', [MerchantController::class, 'deleteMerchant']);
    Route::get('/auto_generate', [MerchantController::class, 'generatePin']);
    // Route::get('/checkEmail', [MerchantController::class, 'checkEmail']);
    // Route::get('/checkPhone', [MerchantController::class, 'checkPhone']);
    Route::get('/getUser', [MerchantController::class, 'getUser']);
    Route::post('/store', [MerchantController::class, 'store']);
    Route::post('/update', [MerchantController::class, 'update']);
    Route::post('/delete', [MerchantController::class, 'delete']);
});
// Route::get('/user-management', [UserController::class, 'index']);

Route::prefix('/customer-management')->group(function () {
    Route::get('/', [CustomerController::class, 'index']);
    Route::get('/getData', [CustomerController::class, 'getData']);
    Route::get('/tableData', [CustomerController::class, 'tableData']);
    Route::get('/editCustomer', [CustomerController::class, 'editCustomer']);
    Route::get('/deleteCustomer', [CustomerController::class, 'deleteCustomer']);
});

Route::prefix('/web-config')->group(function () {
    Route::get('/', [ConfigController::class, 'index']);
    Route::get('/getData', [ConfigController::class, 'getData']);
    Route::get('/tableData', [ConfigController::class, 'tableData']);
    Route::get('/newConfig', [ConfigController::class, 'newConfig']);
    Route::get('/editConfig', [ConfigController::class, 'editConfig']);
    Route::get('/deleteConfig', [ConfigController::class, 'deleteConfig']);
    Route::post('/store', [ConfigController::class, 'store']);
    Route::post('/update', [ConfigController::class, 'update']);
    Route::post('/delete', [ConfigController::class, 'delete']);
});

// Route::prefix('/midtrans')->group(function () {
//     Route::get('/', [MidtransController::class, 'wd_index']);
//     Route::get('/newMidtransWD', [MidtransController::class, 'newMidtransWD']);
// });

Route::prefix('/payment-method')->group(function () {
    Route::get('/', [PaymentController::class, 'index']);
    Route::get('/getData', [PaymentController::class, 'getData']);
    Route::get('/tableData', [PaymentController::class, 'tableData']);
    Route::get('/newPayment', [PaymentController::class, 'newPayment']);
    Route::get('/editPayment', [PaymentController::class, 'editPayment']);
    Route::get('/deletePayment', [PaymentController::class, 'deletePayment']);
    Route::post('/store', [PaymentController::class, 'store']);
    Route::post('/update', [PaymentController::class, 'update']);
    Route::post('/delete', [PaymentController::class, 'delete']);
});

Route::prefix('/category')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/getData', [CategoryController::class, 'getData']);
    Route::get('/tableData', [CategoryController::class, 'tableData']);
    Route::get('/newCategory', [CategoryController::class, 'newCategory']);
    Route::get('/editCategory', [CategoryController::class, 'editCategory']);
    Route::get('/deleteCategory', [CategoryController::class, 'deleteCategory']);
    Route::post('/store', [CategoryController::class, 'store']);
    Route::post('/update', [CategoryController::class, 'update']);
    Route::post('/delete', [CategoryController::class, 'delete']);
});

Route::prefix('/sub-category')->group(function () {
    Route::get('/', [SubCategoryController::class, 'index']);
    Route::get('/getData', [SubCategoryController::class, 'getData']);
    Route::get('/tableData', [SubCategoryController::class, 'tableData']);
    Route::get('/newSubCategory', [SubCategoryController::class, 'newSubCategory']);
    Route::get('/editSubCategory', [SubCategoryController::class, 'editSubCategory']);
    Route::get('/deleteSubCategory', [SubCategoryController::class, 'deleteSubCategory']);
    Route::post('/store', [SubCategoryController::class, 'store']);
    Route::post('/update', [SubCategoryController::class, 'update']);
    Route::post('/delete', [SubCategoryController::class, 'delete']);
});

Route::prefix('/banner')->group(function () {
    Route::get('/', [BannerController::class, 'index']);
    Route::get('/getData', [BannerController::class, 'getData']);
    Route::get('/tableData', [BannerController::class, 'tableData']);
    Route::get('/newBanner', [BannerController::class, 'newBanner']);
    Route::get('/editBanner', [BannerController::class, 'editBanner']);
    Route::get('/deleteBanner', [BannerController::class, 'deleteBanner']);
    Route::post('/store', [BannerController::class, 'store']);
    Route::post('/update', [BannerController::class, 'update']);
    Route::post('/delete', [BannerController::class, 'delete']);
});

Route::prefix('/service')->group(function () {
    Route::get('/', [ServiceController::class, 'index']);
    Route::get('/getData', [ServiceController::class, 'getData']);
    Route::get('/tableData', [ServiceController::class, 'tableData']);
});

Route::get('/login', [AuthController::class, 'index']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);
