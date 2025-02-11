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
            'message' => "*$otp* is your *Verification Code* for our app.",
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

        $userExists = User::where('phone', $phone)->exists();

        if (!$userExists) {
            return redirect()->back()->with('error', 'Phone not found');
        }

        CodeOtp::where('phone', $phone)->delete();
        DB::table('password_reset_tokens')->where('phone', $phone)->delete();

        $otp = rand(100000, 999999);
        $setting = ApplicationSetting::first();

        CodeOtp::updateOrCreate(
            ['phone' => $phone],
            ['otp' => $otp]
        );

        $api = Http::baseUrl($setting->japati_url)
        ->withToken($setting->japati_token)
        ->post('/api/send-message', [
            'gateway' => $setting->japati_gateway,
            'number' => $phone,
            'type' => 'text',
            'message' => '*' . $otp. '* is your *' .$setting->app_name. '* Verivication code.',
        ]);

        return redirect()->route('validate-otp')->with('success', 'OTP sent successfully');
    }

    public function validateOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
        ]);

        $otp = $request->otp;
        $phone = session('phone');

        $codeOtp = CodeOtp::where('phone', $phone)->first();

        if ($codeOtp && $codeOtp->otp == $otp) {
            $token = Str::random(60);
            DB::table('password_reset_tokens')->insert([
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
