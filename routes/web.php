<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('login', [App\Http\Controllers\AuthController::class, 'loginStore'])->name('login.store');
Route::get('register', [App\Http\Controllers\AuthController::class, 'register'])->name('register');
Route::post('register', [App\Http\Controllers\AuthController::class, 'registerStore'])->name('register.store');

Route::middleware('auth')->group(function () {
    Route::middleware('can:admin')->group(function () {
        Route::get('admin', function () {
            return 'admin edited';
        });
        Route::resource('admin/dashboard', DashboardController::class);
    });

    Route::get('/dashboard', function () {
        return view('user.home');
    })->name('dashboard');

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
