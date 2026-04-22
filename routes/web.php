<?php

use App\Http\Controllers\ConfirmationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController; // Pastikan ini tidak dobel dengan LoginController untuk forget
use App\Http\Controllers\IncomingGoodController;
use App\Http\Controllers\ItemTransferController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OutgoingGoodController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| RUTE UNTUK GUEST (Belum Login)
|--------------------------------------------------------------------------
*/
// Route::middleware(['guest'])->group(function () {
    Route::get('/login',[LoginController::class,'index'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    
    Route::get('/forget', [LoginController::class, 'showForm'])->name('password.request');
    
    Route::get('/register', [LoginController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [LoginController::class, 'register']);
// });


/*
|--------------------------------------------------------------------------
| RUTE UNTUK AUTH (Sudah Login)
|--------------------------------------------------------------------------
*/
// Route::middleware(['auth'])->group(function () {
    
    // Fitur Logout hanya bisa dilakukan kalau sudah login
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/',[DashboardController::class,'index'])->name('dashboard');

    // Project
    Route::get('/project', [ProjectController::class, 'index'])->name('project.index');
    Route::post('/project', [ProjectController::class, 'store'])->name('project.store');
    Route::put('project/{id}', [ProjectController::class, 'update'])->name('project.update');
    Route::delete('/project/{id}', [ProjectController::class, 'destroy'])->name('project.destroy');

    // Material
    Route::get('/material',[MaterialController::class,'index'])->name('material.index');
    Route::post('/material', [MaterialController::class, 'store'])->name('material.store');
    Route::put('/material/{id}', [MaterialController::class, 'update'])->name('material.update');
    Route::delete('/material/{id}', [MaterialController::class, 'destroy'])->name('material.destroy');

    // Supplier
    Route::get('/supplier',[SupplierController::class,'index'])->name('supplier.index');
    Route::post('/supplier', [SupplierController::class, 'store'])->name('supplier.store');
    Route::put('/supplier/{id}', [SupplierController::class, 'update'])->name('supplier.update');
    Route::delete('/supplier/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');

    // Incoming Goods
    Route::get('/incominggoods',[IncomingGoodController::class,'index'])->name('incominggood.index');
    Route::post('/incominggoods', [IncomingGoodController::class, 'store'])->name('incominggood.store');

    // Outgoing Goods
    Route::get('/outgoinggood',[OutgoingGoodController::class,'index'])->name('outgoinggood.index');
    Route::post('/outgoinggood', [OutgoingGoodController::class, 'store'])->name('outgoinggood.store');

    // Item Transfer
    Route::get('/itemtransfer',[ItemTransferController::class,'index'])->name('itemtransfer.index');
    Route::post('/itemtransfer', [ItemTransferController::class, 'store'])->name('itemtransfer.store');

    // Order
    Route::get('/order',[OrderController::class,'index'])->name('order.index');
    Route::post('/order', [OrderController::class, 'store'])->name('order.store');

    // Confirmation
    // Rute yang sudah ada
    Route::get('/confirmation', [ConfirmationController::class, 'index'])->name('confirmation.index');
    Route::put('/confirmation/{id}', [ConfirmationController::class, 'updateStatus'])->name('confirmation.update');
// });