<?php

use App\Http\Controllers\Admin\CategoryHotelController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KategoriHewanController;
use App\Http\Controllers\Admin\DataHewanController;
use App\Http\Controllers\Admin\DataPemilikController;
use App\Http\Controllers\Admin\DendaController;
use App\Http\Controllers\Admin\KategoriLayananController;
use App\Http\Controllers\Perawat\LaporanHewanController;
use App\Http\Controllers\Admin\ReservasiHotelController;
use App\Http\Controllers\Admin\ReservasiLayananController;
use App\Http\Controllers\Admin\RolePerawatKasirController;
use App\Http\Controllers\Perawat\PerawatReservasiHotelController;
use App\Http\Controllers\Admin\RoomController as AdminRoomController;
use App\Http\Controllers\Perawat\PerawatRoomController;
use App\Http\Controllers\Perawat\PerawatChangePhoneController;
use App\Http\Controllers\Admin\TransaksiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChangePhoneController;
use App\Http\Controllers\CodeOtpController;
use App\Http\Controllers\Kasir\KasirDashboardController;
use App\Http\Controllers\Kasir\ProfilController as KasirProfilController;
use App\Http\Controllers\Kasir\ChangePhoneController as KasirChangePhoneController;
use App\Http\Controllers\Kasir\KasirReservasiHotelController;
use App\Http\Controllers\Kasir\KasirTransaksiController;
use App\Http\Controllers\Kasir\TransaksiDendaController;
use App\Http\Controllers\Perawat\DashboardController as PerawatDashboardController;
use App\Http\Controllers\Perawat\PerawatProfileController;
use App\Mail\TestEmail;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\User\BookingController;
use App\Http\Controllers\User\GroomingController;
use App\Http\Controllers\User\HewanController;
use App\Http\Controllers\User\HotelController;
use App\Http\Controllers\User\LaporanHewanController as UserLaporanHewanController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\ProfilController;
use App\Http\Controllers\User\TransaksiController as UserTransaksiController;
use App\Models\CodeOtp;
use App\Models\DataPemilik;
use App\Models\Transaksi;
use Illuminate\Auth\Events\Verified;
use App\Models\User;
use Symfony\Component\HttpKernel\Profiler\Profile;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('register/{step?}', [AuthController::class, 'register'])->name('register');
Route::post('register/{step?}', [AuthController::class, 'registerStore'])->name('register.store');
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'loginStore'])->name('login.store');
Route::get('phone/verify/{id}', [AuthController::class, 'verifyPhone'])->name('verification.verify');
Route::get('data-pemilik/create', [AuthController::class, 'showDataPemilikForm'])->name('data-pemilik.create');
Route::post('data-pemilik', [AuthController::class, 'storeDataPemilik'])->name('data-pemilik.store');
// Lupa Password
Route::get('/forgot-password', [AuthController::class, 'showForgetPasswordForm'])->name('password.request');
Route::get('/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'reset'])->name('password.update');

Route::post('/send-otp-forgot-password', [CodeOtpController::class, 'sendOtpForgotPassword'])->name('send-otp-forgot');
Route::post('/forgot-validate-otp', [CodeOtpController::class, 'validateOtp'])->name('validate-otp-forget');
Route::get('/forgot-validate-otp', [CodeOtpController::class, 'showOtpForm'])->name('otp-form');

Route::post('logout', [AuthController::class, 'logout'])->name('logout');
Route::prefix('auth')->group(function () {
    Route::post('send-otp-register', [CodeOtpController::class, 'sendOtp'])->name('send-otp');
});
Route::post('verify-otp', [AuthController::class, 'verifyOtp'])->name('verify-otp');

Route::middleware('auth')->group(function () {
    // Data Pemilik Routes
    Route::get('/data-pemilik/create', [DataPemilikController::class, 'create'])->name('data-pemilik.create');
    Route::post('/data-pemilik', [DataPemilikController::class, 'store'])->name('data-pemilik.store');
    Route::post('/send-verification-email', [AuthController::class, 'sendVerificationEmail']);
    Route::post('/change-phone', [ChangePhoneController::class, 'changePhone'])->name('change-phone');
    Route::post('/validate-otp', [ChangePhoneController::class, 'validateOtp'])->name('validate-otp');
    Route::get('/show-validate-otp', [ChangePhoneController::class, 'showValidateotpCustomer'])->name('validate-otp-show');

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
        Route::put('change-role/{id}', [DataPemilikController::class, 'changeRole'])->name('change-role');
        // Route::get('/transaksi/create/{reservasi_hotel_id}', [TransaksiController::class, 'create'])->name('transaksi.create');
        Route::resource('transaksi', TransaksiController::class);
        // Route::get('/transaksi/{id}', [TransaksiController::class, 'show'])->name('transaksi.show');
        Route::patch('/reservasi_hotel/{id}/update_status', [ReservasiHotelController::class, 'updateStatus'])->name('reservasi_hotel.update_status');
        Route::resource('data_pemilik', DataPemilikController::class);
        
        
        
        Route::get('users-create', [RolePerawatKasirController::class, 'create'])->name('admin-user-create');
        Route::post('users-store', [RolePerawatKasirController::class, 'store'])->name('admin-user-store');
        Route::post('sen-dotp', [CodeOtpController::class, 'sendOtpCreateKasirPerawat'])->name('send-otp-create-kasir-perawat');
        Route::post('verify-otp', [CodeOtpController::class, 'verifyOtpKasirPerawat'])->name('verify-otp-kasir-perawat');
        Route::get('laporan-transaksi', [TransaksiController::class, 'LaporanTransaksi'])->name('laporan-transaksi');
        Route::get('laporan-transaksi-cetak-pdf', [TransaksiController::class, 'cetakPDF'])->name('laporan-transaksi-pdf');
        // Laporan Denda
        Route::get('laporan-denda', [DendaController::class, 'index'])->name('laporan-denda');
        Route::get('laporan-denda/pdf', [DendaController::class, 'cetakPdf'])->name('laporan-denda-pdf');
        
        // New data pemilik management routes with verification
        Route::prefix('data-pemilik')->name('data-pemilik.')->middleware(['verified'])->group(function () {
            Route::get('admin-create/{user}', [RolePerawatKasirController::class, 'createdatapemilik'])->name('admin-create-data-pemilik');
            Route::post('admin-store-data-pemilik', [RolePerawatKasirController::class, 'storedatapemilik'])->name('admin-store-data-pemilik');
        });
    });


    // User Routes
    Route::get('/dashboard', function () {
        return view('user.home');
    })->name('dashboard');

    // User Profile Routes
    Route::resource('profil', ProfilController::class);
    Route::get('/profil/change-password', [ProfilController::class, 'showChangePasswordForm'])->name('profil.changePasswordForm');
    Route::post('/profil/change-password', [ProfilController::class, 'changePassword'])->name('profil.changePassword');
    Route::post('/profil/change-email', [ProfilController::class, 'changeEmail'])->name('profil.changeEmail');
    Route::get('/verify-email-change/{token}', [ProfilController::class, 'verifyEmailChange'])->name('email.verify.change');

    // Route untuk mengirim OTP ke nomor telepon user yang sedang login
    Route::post('/send-otp', [ProfilController::class, 'sendOtpChangePassword'])->name('send-otp-change-password');
    // Route untuk memvalidasi OTP yang dikirimkan
    Route::post('/validate-otp-user', [ProfilController::class, 'validateOtpChangePassword'])->name('validate-otp-change-password-user');

    // User Service Routes
    Route::resource('hewan', HewanController::class);
    Route::resource('layananPetHotel', HotelController::class);
    // Route::resource('layananGrooming', GroomingController::class);
    Route::resource('booking', BookingController::class);

    // User Booking Routes
    Route::get('/api/available-animals', [BookingController::class, 'getAvailableAnimals']);
    Route::prefix('reservasi')->group(function () {
        Route::get('cancel/{id}', [BookingController::class, 'cancel'])->name('reservasi.cancel');
    });

    // User Reports and Payment Routes
    Route::get('/user/laporan-hewan/{reservasiId}', [UserLaporanHewanController::class, 'show'])->name('user.laporan_hewan.laporan');
    Route::post('/payment/pay', [PaymentController::class, 'pay'])->name('payment.pay');
    Route::post('/payment/notification', [PaymentController::class, 'notification'])->name('payment.notification');
    Route::post('/payment/update-status/{id}', [PaymentController::class, 'updateStatus'])->name('payment.update-status');
    // Route::get('/booking/{id}', [BookingController::class, 'show'])->name('booking.show');
    Route::get('transaksi/create', [UserTransaksiController::class, 'create'])->name('transaksi.create');
    Route::post('transaksi', [UserTransaksiController::class, 'store'])->name('transaksi.store');

    // Email Verification Route
    Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
        $user = User::findOrFail($id);

        if (!hash_equals($hash, sha1($user->getEmailForVerification()))) {
            throw new \Illuminate\Validation\ValidationException("The verification link is invalid.");
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        return redirect()->route('profil.index')->with('success', 'Email berhasil diverifikasi.');
    })->name('verification-verify');

    // Perawat Routes
    Route::middleware('can:perawat')->group(function () {
        Route::get('/perawat-dashboard', [PerawatDashboardController::class, 'index'])->name('perawat-dashboard');
        Route::resource('laporan_hewan', LaporanHewanController::class);
        Route::get('laporan-hewan/{reservasiId}', [LaporanHewanController::class, 'laporan'])->name('laporan_hewan.laporan');
        Route::resource('perawat-room', PerawatRoomController::class);
        Route::patch('/perawat-room-{room}-status', [PerawatRoomController::class, 'updatestatus'])->name('perawat-room-update');
        Route::resource('perawat-reservasi-hotel', PerawatReservasiHotelController::class)->except(['create', 'store']);
        Route::post('bulk-checkout', [PerawatReservasiHotelController::class, 'bulkCheckin'])->name('reservasi-hotel.bulk-checkout');
        Route::get('/perawat-profil', [PerawatProfileController::class, 'index'])->name('perawat-profil');
        Route::put('/perawat-profil/update', [PerawatProfileController::class, 'update'])->name('perawat-profil.update');
        Route::post('/perawat-profil/change-password', [PerawatProfileController::class, 'changePassword'])->name('perawat-profil.changePassword');
        Route::post('/perawat-change-phone', [PerawatChangePhoneController::class, 'changePhonePerawat'])->name('perawat-change-phone');
        Route::post('/perawat-validate-otp', [PerawatChangePhoneController::class, 'validateOtpPerawat'])->name('perawat-validate-otp');
        Route::get('/perawat-validate-otp', [PerawatChangePhoneController::class, 'showValidateOtpPerawat'])->name('perawat-validate-otp-show');
        Route::get('/laporan/send-wa/{id}', [LaporanHewanController::class, 'sendWhatsAppMessage'])->name('laporan-sendWa');

        Route::get('/perawat/verify-otp', [PerawatProfileController::class, 'showVerifyOtp'])->name('verify-otp-page-perawat');
        Route::get('/perawat/change-password', [PerawatProfileController::class, 'showChangePassword'])->name('change-password-page-perawat');
        Route::post('/validate-otp', [PerawatProfileController::class, 'validateOtpChangePassword'])->name('validate-otp-change-password-perawat');
        Route::post('/send-otp-change-password', [PerawatProfileController::class, 'SendOtpChangePassword'])->name('send-otp-change-password-perawat');
    });

    // Kasir Routes
    Route::middleware('can:kasir')->group(function () {
        Route::get('kasir-dashboard', [KasirDashboardController::class, 'index'])->name('kasir-dashboard');
        Route::resource('kasir-reservasi-hotel', KasirReservasiHotelController::class);
        Route::resource('kasir-reservasi-hotel-transaksi', KasirTransaksiController::class);
        Route::patch('kasir-reservasi-hotel/{id}/update_status', [KasirReservasiHotelController::class, 'updateStatus'])->name('kasir.reservasi-hotel.update-status');
        Route::put('bulk-cancel', [KasirReservasiHotelController::class, 'bulkCancel'])->name('reservasi-hotel-bulk-cancel');

        Route::put('reservasi-hotel-{id}-checkin', [KasirReservasiHotelController::class, 'checkin'])->name('reservasi-hotel-checkin');
        Route::get('/transaksi/create/{reservasi_hotel_id}', [KasirTransaksiController::class, 'create'])->name('transaksi-create');
        Route::get('/kasir/transaksi/struk/{reservasi_hotel_id}', [KasirTransaksiController::class, 'showStruk'])
            ->name('transaksi-struk');

        Route::get('riwayat-reservasi', [KasirReservasiHotelController::class, 'riwayatReservasi'])->name('riwayat.reservasi');
        Route::get('detail-riwayat-reservasi/{id}', [KasirReservasiHotelController::class, 'detailRiwayat'])->name('riwayat.reservasi-detail');
        Route::get('reservasi-hotel/{id}', [KasirReservasiHotelController::class, 'getDetails']);
        Route::put('reservasi/{reservasiHotel}/update-status', [KasirReservasiHotelController::class, 'updateStatusReservasi'])
            ->name('update-status-rincian-reservasi');

        Route::prefix('kasir')->group(function () {
            Route::get('/profil', [KasirProfilController::class, 'index'])->name('kasir-profil.index');
            Route::post('/profil/update', [KasirProfilController::class, 'update'])->name('kasir-profil.update');
            Route::get('/kasir/verify-otp', [KasirProfilController::class, 'showVerifyOtp'])->name('verify-otp-page');
            Route::get('/kasir/change-password', [KasirProfilController::class, 'showChangePassword'])->name('change-password-page');
            Route::post('/profil/change-password', [KasirProfilController::class, 'changePassword'])->name('kasir-profil-change-Password');
            Route::post('/validate-otp', [KasirProfilController::class, 'validateOtpChangePassword'])->name('validate-otp-change-password-kasir');
            Route::post('/send-otp-change-password', [KasirProfilController::class, 'SendOtpChangePassword'])->name('send-otp-change-password-kasir');
            Route::post('/kasir-change-phone', [KasirChangePhoneController::class, 'changePhoneKasir'])->name('kasir-change-phone');
            Route::post('/kasir-validate-otp', [KasirChangePhoneController::class, 'validateOtpKasir'])->name('kasir-validate-otp');
            Route::get('/kasir-validate-otp', [KasirChangePhoneController::class, 'showValidateOtpKasir'])->name('kasir-validate-otp-show');
            Route::get('/transaksi-denda', [TransaksiDendaController::class, 'index'])->name('transaksi-denda-index');
            Route::get('/transaksi-denda-create/{id}', [TransaksiDendaController::class, 'create'])->name('transaksi-denda-create');
            Route::post('/transaksi-denda', [TransaksiDendaController::class, 'store'])->name('transaksi-denda-store');
            Route::get('/hitung-denda', [TransaksiDendaController::class, 'hitungDenda']);
            Route::get('/transaksi-denda/{id}', [TransaksiDendaController::class, 'show'])->name('kasir-transaksi-denda-show'); // Route untuk menampilkan detail transaksi denda

        });
    });
});
