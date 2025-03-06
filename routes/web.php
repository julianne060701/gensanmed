<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PurchaserController;
Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/home', [HomeController::class, 'index'])->name('admin.home');
    Route::get('/engineer/home', [App\Http\Controllers\Engineer\EngineerController::class, 'index'])->name('engineer.home');
    Route::get('/purchaser/home', [App\Http\Controllers\Purchaser\PurchaserController::class, 'home'])->name('purchaser.home');
    Route::get('/staff/home', [App\Http\Controllers\Staff\StaffController::class, 'index'])->name('staff.home');
});

//Admin route

// calendar routes
    Route::post('/events', [App\Http\Controllers\HomeController::class, 'store'])->name('events.store');
    Route::get('/events', [App\Http\Controllers\HomeController::class, 'fetchEvents'])->name('events.fetch');
    Route::put('/events/{event}', [App\Http\Controllers\HomeController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [App\Http\Controllers\HomeController::class, 'destroy'])->name('events.destroy');   

// User Routes
    Route::get('admin/user', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.user.index');
    Route::get('admin/user/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('admin.user.create');
    Route::post('admin/user/create', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.user.store');

// Purchaser Routes
    Route::post('admin/purchase/store', [App\Http\Controllers\PurchaserController::class, 'store'])->name('admin.purchase.store');
    Route::get('admin/purchase', [App\Http\Controllers\PurchaserController::class, 'index'])->name('admin.purchase.index');
    Route::get('admin/purchase/create', [App\Http\Controllers\PurchaserController::class, 'create'])->name('admin.purchase.create');
    Route::get('admin/purchase/{id}/edit', [App\Http\Controllers\PurchaserController::class, 'edit'])->name('admin.purchase.edit');
    Route::put('admin/purchase/{id}/update', [App\Http\Controllers\PurchaserController::class, 'update'])->name('admin.purchase.update');

// ticketing routes
    Route::get('admin/ticketing', [App\Http\Controllers\TicketController::class, 'index'])->name('admin.ticketing.index');
    Route::get('admin/ticket/create', [App\Http\Controllers\TicketController::class, 'create'])->name('admin.ticketing.create');
    Route::post('admin/ticket/store', [App\Http\Controllers\TicketController::class, 'store'])->name('admin.ticketing.store');
    Route::get('/admin/ticketing/{id}', [App\Http\Controllers\TicketController::class, 'getTicketDetails'])->name('ticketing.details');
    Route::post('/admin/tickets/{id}/deny', [App\Http\Controllers\TicketController::class, 'deny'])->name('ticketing.deny');
    Route::post('/tickets/{id}/accept', [App\Http\Controllers\TicketController::class, 'accept'])->name('tickets.accept');
    Route::get('admmin/ticketing/{id}/edit', [App\Http\Controllers\TicketController::class, 'edit'])->name('admin.ticketing.edit');
    Route::put('admin/ticketing/{id}', [App\Http\Controllers\TicketController::class, 'update'])->name('admin.ticketing.update');
    

// Purchaser Routes
    Route::get('purchaser/purchase', [App\Http\Controllers\Purchaser\PurchaserController::class, 'index'])->name('purchaser.purchase.index');
    Route::get('purchase/create', [App\Http\Controllers\Purchaser\PurchaserController::class, 'create'])->name('purchaser.purchase.create');
    Route::post('purchaser/purchase/store', [App\Http\Controllers\Purchaser\PurchaserController::class, 'store'])->name('purchaser.purchase.store');
    Route::get('purchaser/{id}/edit', [App\Http\Controllers\Purchaser\PurchaserController::class, 'edit'])->name('purchaser.purchase.edit');
    // Route::delete('/purchaser/purchase/{id}', [PurchaserController::class, 'destroy'])->name('purchaser.purchase.destroy');
    // Route::post('/purchaser/purchase', [PurchaserController::class, 'destroy'])->name('purchaser.purchase.destroy');
    Route::delete('/purchaser/purchase/{id}', [App\Http\Controllers\Purchaser\PurchaserController::class, 'destroy'])->name('purchaser.purchase.destroy');





// Engineer Routes
    Route::get('engineer/ticketing', [App\Http\Controllers\Engineer\TicketController::class, 'index'])->name('engineer.ticketing.index');
  
//Staff Routes
    Route::get('staff/ticketing', [App\Http\Controllers\Staff\TicketController::class, 'index'])->name('staff.ticketing.index');
    Route::get('staff/ticketing/create', [App\Http\Controllers\Staff\TicketController::class, 'create'])->name('staff.ticketing.create');
    Route::post('staff/ticketing', [App\Http\Controllers\Staff\TicketController::class, 'store'])->name('staff.ticketing.store');

    Route::get('staff/ticketing/{id}/edit', [App\Http\Controllers\Staff\TicketController::class, 'edit'])->name('staff.ticketing.edit');
    Route::put('/staff/ticketing/{id}', [App\Http\Controllers\Staff\TicketController::class, 'update'])->name('staff.ticketing.update');

