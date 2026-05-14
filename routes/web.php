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
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| RUTE UNTUK GUEST (Belum Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['guest'])->group(function () {
    Route::get('/login',[LoginController::class,'index'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    
    Route::get('/forget', [ForgotPasswordController::class, 'showForm'])->name('password.request');
    Route::post('/forget', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
    
    // Route::get('/register', [LoginController::class, 'showRegistrationForm'])->name('register');
    // Route::post('/register', [LoginController::class, 'register']);
});


/*
|--------------------------------------------------------------------------
| RUTE UNTUK AUTH (Sudah Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    // Fitur Logout hanya bisa dilakukan kalau sudah login
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}/status', [UserController::class, 'updateStatus'])->name('users.updateStatus');

    // Dashboard
    Route::get('/',[DashboardController::class,'index'])->name('dashboard');

    // Project
    Route::get('/project', [ProjectController::class, 'index'])->name('project.index');
    Route::post('/project', [ProjectController::class, 'store'])->name('project.store');
    Route::put('project/{id}', [ProjectController::class, 'update'])->name('project.update');
    Route::delete('/project/{id}', [ProjectController::class, 'destroy'])->name('project.destroy');

    
    // MATERIAL
    // 1. DATA MASTER
    Route::get('/material', [MaterialController::class, 'index'])->name('material.index');
    Route::get('/material/project/{id}', [MaterialController::class, 'showProjectMaterials'])->name('material.project.show');
    Route::post('/material', [MaterialController::class, 'store'])->name('material.store');
    Route::put('/material/{id}', [MaterialController::class, 'update'])->name('material.update');
    Route::delete('/material/{id}', [MaterialController::class, 'destroy'])->name('material.destroy');

    // 2. PEMESANAN (ORDER)
    Route::get('/material/order', [MaterialController::class, 'orderIndex'])->name('material.order');
    Route::get('/material/order/project/{id}', [MaterialController::class, 'showProjectOrders'])->name('material.order.show');
    Route::post('/material/order', [MaterialController::class, 'orderStore'])->name('material.order.store');

    // 3. KONFIRMASI MATERIAL
    Route::get('/material/confirmation', [MaterialController::class, 'confirmationIndex'])->name('material.confirmation');
    Route::get('/material/confirmation/project/{id}', [MaterialController::class, 'showProjectConfirmations'])->name('material.confirmation.show');
    Route::put('/material/confirmation/{id}', [MaterialController::class, 'confirmationUpdate'])->name('material.confirmation.update');



    // Supplier
    Route::get('/supplier',[SupplierController::class,'index'])->name('supplier.index');
    Route::post('/supplier', [SupplierController::class, 'store'])->name('supplier.store');
    Route::put('/supplier/{id}', [SupplierController::class, 'update'])->name('supplier.update');
    Route::delete('/supplier/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');

    //Barang Masuk
    Route::get('/incominggoods', [IncomingGoodController::class, 'index'])->name('incominggood.index');
    Route::get('/incominggoods/project/{id}', [IncomingGoodController::class, 'showProjectIncoming'])->name('incominggood.project.show');
    Route::post('/incominggoods', [IncomingGoodController::class, 'store'])->name('incominggood.store');
    Route::get('/incominggoods/report/print', [IncomingGoodController::class, 'printReport'])->name('incominggood.report');

    //Barang Keluar
    Route::get('/outgoinggood', [OutgoingGoodController::class, 'index'])->name('outgoinggood.index');
    Route::get('/outgoinggood/project/{id}', [OutgoingGoodController::class, 'showProjectOutgoing'])->name('outgoinggood.project');
    Route::post('/outgoinggood', [OutgoingGoodController::class, 'store'])->name('outgoinggood.store');
    Route::get('/outgoinggood/report/print', [OutgoingGoodController::class, 'printReport'])->name('outgoinggood.report');
    
    //Item Transfer
    // 1. PEMESANAN / PENGAJUAN TRANSFER BARANG
    Route::get('/itemtransfer',[ItemTransferController::class,'index'])->name('itemtransfer.index');
    Route::get('/itemtransfer/order', [ItemTransferController::class, 'orderIndex'])->name('itemtransfer.order');
    Route::get('/itemtransfer/order/project/{id}', [ItemTransferController::class, 'showProjectOrders'])->name('itemtransfer.order.show');
    Route::get('/itemtransfer/project/{id}/materials', [ItemTransferController::class, 'getProjectMaterials']);
    Route::post('/itemtransfer/order', [ItemTransferController::class, 'orderStore'])->name('itemtransfer.order.store');
    // 2. KONFIRMASI TRANSFER BARANG
    Route::get('/itemtransfer/confirmation', [ItemTransferController::class, 'confirmationIndex'])->name('itemtransfer.confirmation');
    Route::get('/itemtransfer/confirmation/project/{id}', [ItemTransferController::class, 'showProjectConfirmations'])->name('itemtransfer.confirmation.show');
    Route::put('/itemtransfer/confirmation/{id}', [ItemTransferController::class, 'confirmationUpdate'])->name('itemtransfer.confirmation.update');

});