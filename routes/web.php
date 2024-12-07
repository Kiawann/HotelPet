<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KategoriHewanController;
use App\Http\Controllers\Admin\DataHewanController;
use App\Http\Controllers\Admin\DataPemilikController;
use App\Http\Controllers\Admin\KategoriLayananController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\KategoriLayanan;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register/{step?}', [AuthController::class, 'register'])->name('register');
Route::post('/register/{step?}', [AuthController::class, 'registerStore'])->name('register.store');
Route::get('login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('login', [App\Http\Controllers\AuthController::class, 'loginStore'])->name('login.store');


// Route::get('data-pemilik', [AuthController::class, 'showDataPemilikForm'])->name('data-pemilik.form');

// // Route untuk menyimpan data pemilik
// Route::post('data-pemilik', [AuthController::class, 'storeDataPemilik'])->name('data-pemilik.store');

Route::middleware('auth')->group(function () {
    Route::middleware('can:admin')->group(function () {
        Route::get('admin', function () {
            return 'admin edited';
        });
        Route::resource('admin/dashboard', DashboardController::class);
        Route::resource('kategori_hewan', KategoriHewanController::class);
        Route::resource('data_hewan', DataHewanController::class);
        Route::resource('kategori_layanan', KategoriLayananController::class);
    });


    Route::get('/dashboard', function () {
        return view('user.home');
    })->name('dashboard');

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
