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
use Illuminate\Support\Facades\DB;

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
        return redirect()->route('dashboard.index');
    } elseif ($user->role === 'kasir') {
        return redirect()->route('kasir-dashboard');
    } elseif ($user->role === 'perawat') {
        return redirect()->route('perawat-dashboard');
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
            'phone' => 'required|string|max:15|unique:users,phone',
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
    
        // Tambahkan data pemilik secara otomatis
        DataPemilik::create([
            'user_id' => $user->id,
            'nama' => $request->name, // Sesuai dengan nama user
            'phone' => $request->phone,
            'jenis_kelamin' => null, // Bisa diisi nanti oleh user
            'foto' => null, // Bisa diisi nanti oleh user
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

    // public function sendResetLink(Request $request)
    // {
    //     $request->validate([
    //         'phone_number' => 'required|digits_between:10,15|exists:users,phone_number',
    //     ]);

    //     $this->sendOtp($request);

    //     return redirect('/reset-password')->with('status', 'Kode OTP telah dikirim ke nomor Anda.');
    // }

    public function showResetForm(Request $request)
    {
        $token = $request->segment(2);
        $phone = $request->query('phone');
    
        $resetToken = DB::table('password_reset_tokens')
            ->where('phone', $phone)
            ->where('token', $token)
            ->first();
    
        if (!$resetToken) {
            if (Auth::check()) {
                $roleRedirects = [
                    'user' => '/dasboard',
                    'kasir' => '/cashier',
                ];
    
                $role = Auth::user()->role;
    
                if (isset($roleRedirects[$role])) {
                    return redirect($roleRedirects[$role])->with('error', 'The reset link has expired or is invalid.');
                }
            }
            return redirect()->route('login')->with('error', 'The reset link has expired or is invalid.');
        }
    
        return view('auth.reset-password', compact('token', 'phone'));
    }
    

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'phone' => 'required|string|max:12',
            'password' => 'required|string|min:3|confirmed',
        ]);

        $token = $request->input('token');
        $phone = $request->input('phone');
        $password = $request->input('password');

        $resetToken = DB::table('password_reset_tokens')
        ->where('phone', $phone)
        ->where('token', $token)
        ->first();

        if (!$resetToken) {
            if (Auth::check()) {
                $roleRedirects = [
                    'user' => '/dasboard',
                    'kasir' => '/cashier',
                    // 'admin' => '/dashboard',
                ];

                $role = Auth::user()->role;

                if (isset($roleRedirects[$role])) {
                    return redirect($roleRedirects[$role])->with('error', 'Request parameters have been tampered with.');
                }
            }
            return redirect()->route('login')->with('error', 'Request parameters have been tampered with.');
        }

        $user = User::where('phone', $phone)->first();

        $user->update([
            'password' => Hash::make($password),
        ]);

        DB::table('password_reset_tokens')->where('phone', $phone)->delete();
        session()->forget('phone');

        if (Auth::check()) {
            $roleRedirects = [
                'user' => '/dasboard',
                'kasir' => '/cashier',
                // 'admin' => '/dashboard',
            ];

            $role = Auth::user()->role;

            if (isset($roleRedirects[$role])) {
                return redirect($roleRedirects[$role])->with('success', 'Password reset successfully');
            }
        }
        return redirect()->route('login')->with('success', 'Password reset successfully');
    }

   
}
