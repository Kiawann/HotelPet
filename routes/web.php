<?php

use App\Http\Controllers\Admin\CategoryHotelController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KategoriHewanController  as AdminKategoriHewanController;
use App\Http\Controllers\Perawat\KategoriHewanController  as PerawatKategoriHewanController;
use App\Http\Controllers\Admin\DataHewanController;
use App\Http\Controllers\Admin\DataPemilikController;
use App\Http\Controllers\Admin\KategoriLayananController;
use App\Http\Controllers\Perawat\LaporanHewanController;
use App\Http\Controllers\Admin\ReservasiHotelController;
use App\Http\Controllers\Admin\ReservasiLayananController;
use App\Http\Controllers\Perawat\PerawatReservasiHotelController;
use App\Http\Controllers\Admin\RoomController  as AdminRoomController;
use App\Http\Controllers\Perawat\RoomController  as PerawatRoomController;
use App\Http\Controllers\Admin\TransaksiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Perawat\DashboardController as PerawatDashboardController;
use App\Mail\TestEmail;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register/{step?}', [AuthController::class, 'register'])->name('register');
Route::post('/register/{step?}', [AuthController::class, 'registerStore'])->name('register.store');
Route::get('login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('login', [App\Http\Controllers\AuthController::class, 'loginStore'])->name('login.store');
Route::get('/email/verify/{id}', [AuthController::class, 'verifyEmail'])->name('verification.verify');
Route::get('data-pemilik/create', [AuthController::class, 'showDataPemilikForm'])->name('data-pemilik.create');
Route::post('data-pemilik', [AuthController::class, 'storeDataPemilik'])->name('data-pemilik.store');
Route::get('forgot-password', [AuthController::class, 'showForgetPasswordForm'])->name('password.request');
Route::post('forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
Route::middleware('auth')->group(function () {
    Route::get('/data-pemilik/create', [DataPemilikController::class, 'create'])->name('data-pemilik.create');
    Route::post('/data-pemilik', [DataPemilikController::class, 'store'])->name('data-pemilik.store');
    Route::post('/send-verification-email', [AuthController::class, 'sendVerificationEmail']);
    
});


// Route::get('data-pemilik', [AuthController::class, 'showDataPemilikForm'])->name('data-pemilik.form');

// // Route untuk menyimpan data pemilik
// Route::post('data-pemilik', [AuthController::class, 'storeDataPemilik'])->name('data-pemilik.store');

Route::middleware('auth')->group(function () {
    Route::middleware('can:admin')->group(function () {
        Route::get('admin', function () {
            return 'admin edited';
        });
        Route::resource('admin/dashboard', DashboardController::class);
        Route::resource('kategori_hewan', AdminKategoriHewanController::class);
        Route::resource('data_hewan', DataHewanController::class);
        Route::resource('kategori_layanan', KategoriLayananController::class);
        Route::resource('reservasi_layanan', ReservasiLayananController::class);
        Route::get('get-hewan-by-pemilik/{pemilikId}', [ReservasiHotelController::class, 'getHewanByPemilik']);
        Route::put('reservasi/update-pembayaran/{reservasi_layanan_id}', [ReservasiLayananController::class, 'updatePembayaran'])->name('reservasi.updatePembayaran');
        // Resource routes untuk CategoryHotel
        Route::resource('category_hotel', CategoryHotelController::class);

        // Resource routes untuk Room
        Route::resource('room', AdminRoomController::class);
        Route::resource('reservasi_hotel', ReservasiHotelController::class);
        // Route::get('reservasi_hotel/{id}', [ReservasiHotelController::class, 'show'])->name('reservasi_hotel.show');
        Route::get('/transaksi/create/{reservasi_hotel_id}', [TransaksiController::class, 'create'])->name('transaksi.create');

        Route::resource('transaksi', TransaksiController::class);
        Route::get('/transaksi/{id}', [TransaksiController::class, 'show'])->name('transaksi.show');
        Route::patch('/reservasi_hotel/{id}/update_status', [ReservasiHotelController::class, 'updateStatus'])->name('reservasi_hotel.update_status');
        Route::resource('data_pemilik', DataPemilikController::class);
        
    });


    Route::get('/dashboard', function () {
        return view('user.home');
    })->name('dashboard');
    
});

Route::middleware(['auth'])->group(function () {
    Route::middleware('can:perawat')->group(function () {
    Route::get('/perawat/dashboard', [PerawatDashboardController::class, 'index']);
    Route::resource('laporan_hewan', LaporanHewanController::class);
        // web.php
        Route::get('laporan-hewan/{reservasiId}', [LaporanHewanController::class, 'laporan'])->name('laporan_hewan.laporan');
        Route::resource('perawat/room', PerawatRoomController::class);
        // Route::resource('perawat/kategori_hewan', PerawatKategoriHewanController::class);
        // Resource route untuk reservasi hotel
    Route::resource('reservasi-hotel', PerawatReservasiHotelController::class)->except(['create', 'store']);
    
    // Custom route untuk update status
    Route::post('reservasi-hotel/{id}/update-status', [PerawatReservasiHotelController::class, 'updateStatus'])->name('reservasi_hotel.updateStatus');
    });
});
   