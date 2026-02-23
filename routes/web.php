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

Route::get('/material',[MaterialController::class,'index'])->name('material.index');

route::get('/supplier',[SupplierController::class,'index'])->name('supplier.index');

route::get('/incominggoods',[IncomingGoodController::class,'index'])->name('incominggood.index');

route::get('/outgoinggood',[OutgoingGoodController::class,'index'])->name('outgoinggood.index');

route::get('/itemtransfer',[ItemTransferController::class,'index'])->name('itemtransfer.index');

route::get('/order',[OrderController::class,'index'])->name('order.index');

route::get('/confirmation', [ConfirmationController::class, 'index'])->name('confirmation.index');