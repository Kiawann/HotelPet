<?php

use App\Http\Controllers\Admin\CategoryHotelController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KategoriHewanController;
use App\Http\Controllers\Admin\DataHewanController;
use App\Http\Controllers\Admin\KategoriLayananController;
use App\Http\Controllers\Admin\LaporanHewanController;
use App\Http\Controllers\Admin\ReservasiHotelController;
use App\Http\Controllers\Admin\ReservasiLayananController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\TransaksiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\ReservasiLayanan;

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
        Route::resource('reservasi_layanan', ReservasiLayananController::class);
        Route::put('reservasi/update-pembayaran/{reservasi_layanan_id}', [ReservasiLayananController::class, 'updatePembayaran'])->name('reservasi.updatePembayaran');
        // Resource routes untuk CategoryHotel
        Route::resource('category_hotel', CategoryHotelController::class);

        // Resource routes untuk Room
        Route::resource('room', RoomController::class);
        Route::resource('reservasi_hotel', ReservasiHotelController::class);
        // Route::get('reservasi_hotel/{id}', [ReservasiHotelController::class, 'show'])->name('reservasi_hotel.show');
        Route::resource('laporan_hewan', LaporanHewanController::class);
        // web.php
        Route::get('laporan-hewan/{reservasiId}', [LaporanHewanController::class, 'laporan'])->name('laporan_hewan.laporan');
        Route::get('/transaksi/create/{reservasi_hotel_id}', [TransaksiController::class, 'create'])->name('transaksi.create');
        Route::get('/api/reservasi_hotel/{id}/subtotal', [ReservasiHotelController::class, 'getSubtotal']);

        Route::resource('transaksi', TransaksiController::class);
    });


    Route::get('/dashboard', function () {
        return view('user.home');
    })->name('dashboard');

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
