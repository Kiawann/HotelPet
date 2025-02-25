<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ApplicationSetting;
use App\Models\CodeOtp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CodeOtpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function sendOtp(Request $request)
{
    $request->validate([
        'phone' => 'required|string|max:13',
    ]);

    $phone = $request->phone;

    // Cek apakah nomor sudah terdaftar
    if (User::where('phone', $phone)->exists()) {
        return response()->json(['success' => false, 'message' => 'Phone already exists!'], 409);
    }

    // Cek apakah ada OTP yang masih berlaku
    $existingOtp = CodeOtp::where('phone', $phone)
                          ->where('expires_at', '>', now())
                          ->first();

    if ($existingOtp) {
        return response()->json([
            'success' => false,
            'message' => 'OTP masih berlaku, coba lagi nanti!'
        ], 429);
    }

    // Generate OTP dan set waktu kedaluwarsa (1 menit dari sekarang)
    $otp = rand(100000, 999999);
    CodeOtp::updateOrCreate(
        ['phone' => $phone],
        ['otp' => $otp, 'expires_at' => now()->addMinutes(1)]
    );

    // Kirim OTP via Japati API
    $apiResponse = Http::withToken('API-TOKEN-lJ1MjuP1b1yeA5UMk7aVQNtpBycMzrjaQeTwmKQx2Geab05B1QACYo')
        ->post('https://app.japati.id/api/send-message', [
            'gateway' => '6288222087560',
            'number' => $phone,
            'type' => 'text',
            // 'message' => "*$otp* is your *Verification Code* for our app.",
            'message' => "*$otp* adalah kode verifikasi Anda. Gunakan kode ini untuk mengakses akun Fur Haven Pet Hotel.",
        ]);

    if ($apiResponse->successful()) {
        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully'
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Failed to send OTP',
            'error' => $apiResponse->body()
        ], 500);
    }
}

public function sendOtpForgotPassword(Request $request)
{
    $request->validate([
        'phone' => 'required|string|max:13',
    ]);

    $phone = $request->phone;
session(['phone' => $phone]);
// dd(session('phone'));

// Jika nomor diawali dengan 0, ubah ke format 62
$formattedPhone = preg_replace('/^0/', '62', $phone);

// Hapus OTP sebelumnya jika ada
CodeOtp::where('phone', $phone)->delete();
DB::table('password_reset_tokens')->where('phone', $phone)->delete();

// Generate OTP baru
$otp = rand(100000, 999999);

// Simpan atau perbarui OTP di database dengan nomor asli (bisa pakai 0)
CodeOtp::updateOrCreate(
    ['phone' => $phone],
    ['otp' => $otp]
);

// Kirim OTP dengan nomor yang sudah diformat ke API Japati
$response = Http::withToken('API-TOKEN-lJ1MjuP1b1yeA5UMk7aVQNtpBycMzrjaQeTwmKQx2Geab05B1QACYo')
    ->post('https://app.japati.id/api/send-message', [
        'gateway' => '6288222087560', // Sesuaikan dengan gateway yang digunakan
        'number' => $formattedPhone,  // Gunakan nomor yang sudah diformat
        'type' => 'text',
        // 'message' => "*$otp* is your *App Name* verification code."
        'message' => "*$otp* adalah kode verifikasi Anda. Gunakan kode ini untuk mengakses akun Fur Haven Pet Hotel.",

    ]);

// Cek apakah request berhasil atau gagal
if ($response->failed()) {
    return redirect()->back()->with('error', 'Failed to send OTP. Please try again.');
}

return redirect()->route('otp-form')->with('success', 'OTP sent successfully');
}


public function validateOtp(Request $request)
{
    
    $request->validate([
        'otp' => 'required|numeric|digits:6',
    ]);

    // Ambil OTP dari input user
    $otp = $request->otp;
    $phone = session('phone');
    //   dd($phone);

    // Debug untuk memastikan session 'phone' ada
    if (!$phone) {
        return redirect()->route('login')->with('error', 'Session expired. Please request OTP again.');
    }

    // Cari kode OTP di database
    $codeOtp = CodeOtp::where('phone', $phone)->first();

    // Debug untuk melihat apakah OTP ditemukan
    if (!$codeOtp) {
        return redirect()->back()->with('error', 'OTP not found.');
    }

    if ($codeOtp->otp == $otp) {
        // Generate token untuk reset password
        $token = Str::random(60);

        // Simpan ke database password_reset_tokens
        $inserted = DB::table('password_reset_tokens')->insert([
            'phone' => $phone,
            'token' => $token,
            'created_at' => now(),
        ]);

        $codeOtp->delete();
        return redirect()->route('password.reset',['token' => $token, 'phone' => $phone])->with('success', 'OTP verified successfully');
    } else {
        return redirect()->back()->with('error', 'Invalid OTP');
    }
}


    public function showOtpForm()
    {
        // Pastikan nomor telepon tersedia di sesi
        if (!session()->has('phone')) {
            return redirect()->route('login')->with('error', 'Nomor telepon tidak ditemukan.');
        }

        return view('auth.validate-otp');
    }

    public function sendOtpCreateKasirPerawat(Request $request)
{
    $request->validate([
        'phone' => 'required|string|max:13',
    ]);

    $phone = $request->phone;

    // Cek apakah nomor sudah terdaftar
    if (User::where('phone', $phone)->exists()) {
        return response()->json(['success' => false, 'message' => 'Phone already exists!'], 409);
    }

    // Cek apakah ada OTP yang masih berlaku
    $existingOtp = CodeOtp::where('phone', $phone)
                          ->where('expires_at', '>', now())
                          ->first();

    if ($existingOtp) {
        return response()->json([
            'success' => false,
            'message' => 'OTP masih berlaku, coba lagi nanti!'
        ], 429);
    }

    // Generate OTP dan set waktu kedaluwarsa (1 menit dari sekarang)
    $otp = rand(100000, 999999);
    CodeOtp::updateOrCreate(
        ['phone' => $phone],
        ['otp' => $otp, 'expires_at' => now()->addMinutes(1)]
    );

    // Kirim OTP via Japati API
    $apiResponse = Http::withToken('API-TOKEN-lJ1MjuP1b1yeA5UMk7aVQNtpBycMzrjaQeTwmKQx2Geab05B1QACYo')
        ->post('https://app.japati.id/api/send-message', [
            'gateway' => '6288222087560',
            'number' => $phone,
            'type' => 'text',
            // 'message' => "*$otp* is your *Verification Code* for our app.",
            'message' => "*$otp* adalah kode verifikasi Anda. Gunakan kode ini untuk mengakses akun Fur Haven Pet Hotel.",
        ]);

    if ($apiResponse->successful()) {
        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully'
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Failed to send OTP',
            'error' => $apiResponse->body()
        ], 500);
    }
}

public function verifyOtpKasirPerawat(Request $request)
{
    $request->validate([
        'phone' => 'required|exists:users,phone',
        'otp' => 'required|digits:6',
    ]);

    $otp = CodeOtp::where('phone', $request->phone)
                 ->where('otp', $request->otp)
                 ->where('expires_at', '>', now()) // Periksa apakah OTP masih berlaku
                 ->first();

    if (!$otp) {
        return back()->withErrors(['otp' => 'Kode OTP tidak valid atau sudah kadaluarsa.'])->withInput();
    }

    // Jika OTP valid, lanjutkan proses
    $user = User::where('phone', $request->phone)->first();
    $user->phone_verified_at = now();
    $user->save();

    return redirect()->route('data-pemilik.admin-create-data-pemilik')->with('success', 'Verifikasi berhasil.');
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
