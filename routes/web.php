<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/home', [HomeController::class, 'index'])->name('admin.home');
    Route::get('/engineer/home', [App\Http\Controllers\Engineer\EngineerController::class, 'index'])->name('engineer.home');
    Route::get('/purchaser/home', [App\Http\Controllers\Purchaser\PurchaserController::class, 'home'])->name('purchaser.home');
    Route::get('/IT/home', [HomeController::class, 'index'])->name('IT.index');
});

//admin route
    Route::post('/events', [App\Http\Controllers\HomeController::class, 'store'])->name('events.store');
    Route::get('/events', [App\Http\Controllers\HomeController::class, 'fetchEvents'])->name('events.fetch');
    Route::put('/events/{event}', [App\Http\Controllers\HomeController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [App\Http\Controllers\HomeController::class, 'destroy'])->name('events.destroy');
    Route::get('admin/purchase', [App\Http\Controllers\PurchaserController::class, 'index'])->name('admin.purchase.index');
    Route::get('admin/purchase/create', [App\Http\Controllers\PurchaserController::class, 'create'])->name('admin.purchase.create');
    Route::get('admin/ticketing', [App\Http\Controllers\TicketController::class, 'index'])->name('admin.ticketing.index');

// Purchaser Routes
    Route::get('purchaser/purchase', [App\Http\Controllers\Purchaser\PurchaserController::class, 'index'])->name('purchaser.purchase.index');
    Route::get('purchase/create', [App\Http\Controllers\Purchaser\PurchaserController::class, 'create'])->name('purchaser.purchase.create');
    Route::post('purchaser/purchase/store', [App\Http\Controllers\Purchaser\PurchaserController::class, 'store'])->name('purchaser.purchase.store');
    Route::get('purchaser/purchase/{id}/edit', [App\Http\Controllers\Purchaser\PurchaserController::class, 'edit'])->name('purchaser.purchase.edit');
    Route::put('purchaser/purchase/{id}/update', [App\Http\Controllers\Purchaser\PurchaserController::class, 'update'])->name('purchaser.purchase.update');



// Engineer Routes
    Route::get('engineer/ticketing', [App\Http\Controllers\Engineer\TicketController::class, 'index'])->name('engineer.ticketing.index');
  

