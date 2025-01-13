<?php

use App\Http\Controllers\Admin\CategoryHotelController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KategoriHewanController;
use App\Http\Controllers\Admin\DataHewanController;
use App\Http\Controllers\Admin\DataPemilikController;
use App\Http\Controllers\Admin\KategoriLayananController;
use App\Http\Controllers\Perawat\LaporanHewanController;
use App\Http\Controllers\Admin\ReservasiHotelController;
use App\Http\Controllers\Admin\ReservasiLayananController;
use App\Http\Controllers\Perawat\PerawatReservasiHotelController;
use App\Http\Controllers\Admin\RoomController as AdminRoomController;
use App\Http\Controllers\Perawat\PerawatRoomController;
use App\Http\Controllers\Admin\TransaksiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Kasir\KasirDashboardController;
use App\Http\Controllers\Kasir\KasirReservasiHotelController;
use App\Http\Controllers\Kasir\KasirTransaksiController;
use App\Http\Controllers\Perawat\DashboardController as PerawatDashboardController;
use App\Mail\TestEmail;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/register/{step?}', [AuthController::class, 'register'])->name('register');
Route::post('/register/{step?}', [AuthController::class, 'registerStore'])->name('register.store');
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'loginStore'])->name('login.store');
Route::get('/email/verify/{id}', [AuthController::class, 'verifyEmail'])->name('verification.verify');
Route::get('data-pemilik/create', [AuthController::class, 'showDataPemilikForm'])->name('data-pemilik.create');
Route::post('data-pemilik', [AuthController::class, 'storeDataPemilik'])->name('data-pemilik.store');
Route::get('forgot-password', [AuthController::class, 'showForgetPasswordForm'])->name('password.request');
Route::post('forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    // Data Pemilik Routes
    Route::get('/data-pemilik/create', [DataPemilikController::class, 'create'])->name('data-pemilik.create');
    Route::post('/data-pemilik', [DataPemilikController::class, 'store'])->name('data-pemilik.store');
    Route::post('/send-verification-email', [AuthController::class, 'sendVerificationEmail']);

    // Admin Routes
    Route::middleware('can:admin')->group(function () {
        Route::get('admin', function () {
            return 'admin edited';
        });
        Route::resource('admin/dashboard', DashboardController::class);
        Route::resource('kategori_hewan', KategoriHewanController::class);
        Route::resource('data_hewan', DataHewanController::class);
        Route::resource('kategori_layanan', KategoriLayananController::class);
        Route::resource('reservasi_layanan', ReservasiLayananController::class);
        Route::get('get-hewan-by-pemilik/{pemilikId}', [ReservasiHotelController::class, 'getHewanByPemilik']);
        Route::put('reservasi/update-pembayaran/{reservasi_layanan_id}', [ReservasiLayananController::class, 'updatePembayaran'])->name('reservasi.updatePembayaran');
        Route::resource('category_hotel', CategoryHotelController::class);
        Route::resource('room', AdminRoomController::class);
        Route::resource('reservasi_hotel', ReservasiHotelController::class);
        // Route::get('/transaksi/create/{reservasi_hotel_id}', [TransaksiController::class, 'create'])->name('transaksi.create');
        Route::resource('transaksi', TransaksiController::class);
        // Route::get('/transaksi/{id}', [TransaksiController::class, 'show'])->name('transaksi.show');
        Route::patch('/reservasi_hotel/{id}/update_status', [ReservasiHotelController::class, 'updateStatus'])->name('reservasi_hotel.update_status');
        Route::resource('data_pemilik', DataPemilikController::class);
    });

    // User Dashboard
    Route::get('/dashboard', function () {
        return view('user.home');
    })->name('dashboard');

    // Perawat Routes
    Route::middleware('can:perawat')->group(function () {
        Route::get('/perawat-dashboard', [PerawatDashboardController::class, 'index'])->name('perawat-dashboard');
        Route::resource('laporan_hewan', LaporanHewanController::class);
        Route::get('laporan-hewan/{reservasiId}', [LaporanHewanController::class, 'laporan'])->name('laporan_hewan.laporan');
        Route::resource('perawat-room', PerawatRoomController::class);
        Route::resource('perawat-reservasi-hotel', PerawatReservasiHotelController::class)->except(['create', 'store']);
        Route::post('bulk-checkout', [PerawatReservasiHotelController::class, 'bulkCheckin'])->name('reservasi-hotel.bulk-checkout');
    });

    // Kasir Routes
    Route::middleware('can:kasir')->group(function () {
        Route::get('kasir-dashboard', [KasirDashboardController::class,'index'])->name('kasir-dashboard');
        Route::resource('kasir-reservasi-hotel', KasirReservasiHotelController::class);
        Route::resource('kasir-reservasi-hotel-transaksi', KasirTransaksiController::class);
        Route::patch('kasir-reservasi-hotel/{id}/update_status', [KasirReservasiHotelController::class, 'updateStatus'])->name('kasir.reservasi-hotel.update-status');
        Route::put('bulk-cancel', [KasirReservasiHotelController::class, 'bulkCancel'])->name('reservasi-hotel-bulk-cancel');
        
Route::put('reservasi-hotel-{id}-checkin', [KasirReservasiHotelController::class, 'checkin'])->name('reservasi-hotel-checkin');
        Route::get('/transaksi/create/{reservasi_hotel_id}', [KasirTransaksiController::class, 'create'])->name('transaksi-create');
        Route::get('/kasir/transaksi/struk/{reservasi_hotel_id}', [KasirTransaksiController::class, 'showStruk'])
        ->name('transaksi-struk');





    });
    
    
});


