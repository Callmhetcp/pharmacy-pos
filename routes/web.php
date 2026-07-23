<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\SaleController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories',[CategoryController::class,'store']);
Route::get('/categories/{id}/edit',[CategoryController::class, 'edit']);
Route::put('/categories/{id}',[CategoryController::class,'update']);
Route::delete('/categories/{id}',[CategoryController::class, 'destroy']);


Route::get('/suppliers', [SupplierController::class, 'index']);
Route::post('/suppliers', [SupplierController::class, 'store']);
Route::get('/suppliers/{id}/edit',[SupplierController::class, 'edit']);
Route::put('/suppliers/{id}',[SupplierController::class,'update']);
Route::delete('/suppliers/{id}',[SupplierController::class,'destroy']);

Route:: get('/medicines', [MedicineController::class, 'index']);
Route:: post('/medicines', [MedicineController::class, 'store']);
Route::get('/medicines/{id}/edit',[MedicineController::class, 'edit']);
Route::put('/medicines/{id}',[MedicineController::class,'update']);
Route::delete('/medicines/{id}',[MedicineController::class,'destroy']);

Route:: get('/customers',[CustomerController::class,'index']);
Route:: post('/customers',[CustomerController::class,'store']);
Route::get('/customers/{id}/edit',[CustomerController::class, 'edit']);
Route::put('/customers/{id}',[CustomerController::class,'update']);
Route::delete('/customers/{id}',[CustomerController::class,'destroy']);



Route:: get('/sales',[SaleController::class, 'index']);
Route:: post('/sales',[SaleController::class, 'store']);
Route:: post('/sales/add-to-cart',[SaleController::class, 'addToCart'])
    ->name('sales.addToCart');
Route::post('/sales/store', [SaleController::class, 'store'])->name('sales.store');
Route::get('/sales/clear-cart', [SaleController::class,'clearCart'])
    ->name('sales.clearCart');
Route::delete('/cart/{id}',[SaleController::class, 'removeCart'])
    ->name('cart.remove');
Route::get('/sales/{sale}/receipt', [SaleController::class,'receipt'])
    ->name('sales.receipt');
Route::get('/sales/history',[SaleController::class, 'history'])->name('sales.history');
Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
Route::post('sales/customerType', [SaleController::class,'customerType'])
    ->name('sales.customerType');

