<?php

namespace App\Http\Controllers;

use App\Models\CodeOtp;
use App\Models\DataPemilik;
use App\Models\User;
use App\Models\Otps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

public function loginStore(Request $request)
{
    $request->validate([
        'phone' => 'required',
        'password' => 'required',
    ]);

    // Simpan input ke sesi agar tidak hilang saat terjadi error
    session()->flash('phone', $request->phone);
    session()->flash('password', $request->password);

    // Cek apakah nomor HP terdaftar
    $user = User::where('phone', $request->phone)->first();

    if (!$user) {
        return redirect()->back()
            ->withErrors(['phone' => 'Nomor handphone salah atau belum terdaftar.']);
    }

    // Cek apakah password sesuai
    if (!Hash::check($request->password, $user->password)) {
        return redirect()->back()
            ->withErrors(['password' => 'Password salah.']);
    }

    // Login pengguna
    Auth::login($user);
    session()->forget(['phone', 'password']); // Hapus session setelah login

    // Redirect sesuai role
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'kasir') {
        return redirect()->route('kasir.dashboard');
    } elseif ($user->role === 'user') {
        return redirect()->intended('/dashboard');
    }

    return redirect()->route('home');
}



    public function register()
    {
        return view('auth.register');
    }

   public function registerstore(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:15',
        'otp' => 'required|string|max:6',
        'password' => 'required|string|min:6|confirmed',
    ]);

    // Cek apakah OTP valid
    $otpRecord = CodeOtp::where('phone', $request->phone)
                        ->where('otp', $request->otp)
                        ->first();

    if (!$otpRecord) {
        return back()->withErrors(['otp' => 'Kode OTP tidak valid atau salah'])->withInput();
    }

    // Simpan user jika OTP benar
    $user = User::create([
        'name' => $request->name,
        'phone' => $request->phone,
        'password' => Hash::make($request->password),
        'phone_verified_at' => now(),
    ]);

    return redirect()->route('login')->with('status', 'Registrasi berhasil. Silakan login.');
}

public function verifyOtp(Request $request)
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

    return redirect()->route('login')->with('success', 'Verifikasi berhasil.');
}



    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function showForgetPasswordForm()
    {
        return view('auth.forget-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|digits_between:10,15|exists:users,phone_number',
        ]);

        $this->sendOtp($request);

        return redirect('/reset-password')->with('status', 'Kode OTP telah dikirim ke nomor Anda.');
    }

    public function showResetPasswordForm()
    {
        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|digits_between:10,15|exists:users,phone_number',
            'otp' => 'required|digits:6',
            'password' => 'required|confirmed|min:8',
        ]);

        $otp = Otps::where('phone_number', $request->phone_number)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otp) {
            return back()->withErrors(['otp' => 'Kode OTP salah atau telah kedaluwarsa.']);
        }

        $user = User::where('phone_number', $request->phone_number)->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        $otp->delete();

        return redirect('/login')->with('success', 'Password berhasil direset. Silakan login.');
    }

   
}
