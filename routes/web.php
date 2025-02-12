<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

//admin route
Route::get('/admin/home', [App\Http\Controllers\HomeController::class, 'index'])->name('admin/home');
Route::post('/events', [App\Http\Controllers\HomeController::class, 'store'])->name('events.store');
Route::get('/events', [App\Http\Controllers\HomeController::class, 'fetchEvents'])->name('events.fetch');
Route::put('/events/{event}', [App\Http\Controllers\HomeController::class, 'update'])->name('events.update');
Route::delete('/events/{event}', [App\Http\Controllers\HomeController::class, 'destroy'])->name('events.destroy');


Route::get('admin/purchase', [App\Http\Controllers\PurchaserController::class, 'index'])->name('admin.purchase.index');
Route::get('admin/purchase/create', [App\Http\Controllers\PurchaserController::class, 'create'])->name('admin.purchase.create');

Route::get('admin/ticketing', [App\Http\Controllers\TicketController::class, 'index'])->name('admin.ticketing.index');



Route::get('/engineer/home', [App\Http\Controllers\HomeController::class, 'index'])->name('engineer.index');
Route::get('/IT/home', [App\Http\Controllers\HomeController::class, 'index'])->name('IT.index');

//Route::post('/user/create', [App\Http\Controllers\Admin\CustomerController::class, 'store'])->name('admin.customer.create');