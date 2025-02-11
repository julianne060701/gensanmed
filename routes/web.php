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
Route::get('/purchase/create', [App\Http\Controllers\PurchaserController::class, 'create'])->name('purchase.create');

Route::get('/ticketing', [App\Http\Controllers\TicketController::class, 'index'])->name('ticketing.index');


Route::post('/staff/create', [App\Http\Controllers\Admin\StaffController::class, 'store'])->name('admin.staff.create');
// For IT users
Route::get('/IT/home', function () {
    return view('IT.home'); // This should load resources/views/IT/home.blade.php
})->middleware('auth'); // Optionally add role-specific middleware if needed

// For admin users
Route::get('/admin/home', function () {
    return view('admin.home'); // This should load resources/views/admin/home.blade.php
})->middleware('auth');

//Route::post('/user/create', [App\Http\Controllers\Admin\CustomerController::class, 'store'])->name('admin.customer.create');