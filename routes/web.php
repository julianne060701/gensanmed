<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/events', [App\Http\Controllers\HomeController::class, 'store'])->name('events.store');
Route::get('/events', [App\Http\Controllers\HomeController::class, 'fetchEvents'])->name('events.fetch');
Route::put('/events/{event}', [App\Http\Controllers\HomeController::class, 'update'])->name('events.update');
Route::delete('/events/{event}', [App\Http\Controllers\HomeController::class, 'destroy'])->name('events.destroy');


Route::get('/purchase', [App\Http\Controllers\PurchaserController::class, 'index'])->name('purchase.index');


//Route::post('/user/create', [App\Http\Controllers\Admin\CustomerController::class, 'store'])->name('admin.customer.create');