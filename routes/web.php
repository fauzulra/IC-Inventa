<?php

use App\Http\Controllers\ConfirmationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IncomingGoodController;
use App\Http\Controllers\ItemTransferController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OutgoingGoodController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

route::get('/',[LoginController::class,'index'])->name('login.index');

route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');

Route::get('/project', [ProjectController::class, 'index'])->name('project.index');
Route::post('/project', [ProjectController::class, 'store'])->name('project.store');
Route::put('project/{id}', [ProjectController::class, 'update'])->name('project.update');
Route::delete('/project/{id}', [ProjectController::class, 'destroy'])->name('project.destroy');

Route::get('/material',[MaterialController::class,'index'])->name('material.index');
Route::post('/material', [MaterialController::class, 'store'])->name('material.store');
Route::put('/material/{id}', [MaterialController::class, 'update'])->name('material.update');
Route::delete('/material/{id}', [MaterialController::class, 'destroy'])->name('material.destroy');

route::get('/supplier',[SupplierController::class,'index'])->name('supplier.index');
Route::post('/supplier', [SupplierController::class, 'store'])->name('supplier.store');
Route::put('/supplier/{id}', [SupplierController::class, 'update'])->name('supplier.update');
Route::delete('/supplier/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');

route::get('/incominggoods',[IncomingGoodController::class,'index'])->name('incominggood.index');
route::post('/incominggoods', [IncomingGoodController::class, 'store'])->name('incominggood.store');

route::get('/outgoinggood',[OutgoingGoodController::class,'index'])->name('outgoinggood.index');
route::post('/outgoinggood', [OutgoingGoodController::class, 'store'])->name('outgoinggood.store');

route::get('/itemtransfer',[ItemTransferController::class,'index'])->name('itemtransfer.index');
Route::post('/itemtransfer', [ItemTransferController::class, 'store'])->name('itemtransfer.store');

route::get('/order',[OrderController::class,'index'])->name('order.index');
route::post('/order', [OrderController::class, 'store'])->name('order.store');

route::get('/confirmation', [ConfirmationController::class, 'index'])->name('confirmation.index');