<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DataPemilik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function loginStore(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    // Cek apakah user ada
    if (!$user) {
        return back()->withErrors([
            'email' => 'Akun tidak ditemukan.',
        ])->withInput($request->only('email'));
    }

    // Cek apakah email sudah diverifikasi
    if (!$user->hasVerifiedEmail()) {
        return back()->withErrors([
            'email' => 'Akun Anda belum diverifikasi. Silakan cek email Anda untuk verifikasi.',
        ])->withInput($request->only('email'));
    }

    // Cek kredensial login
    if (Auth::attempt($request->only('email', 'password'), $request->remember)) {
        $request->session()->regenerate();

        // Redirect berdasarkan role
        $role = Auth::user()->role;
        if ($role == 'admin') {
            return redirect('admin/dashboard');
        } elseif ($role == 'perawat') {
            return redirect('perawat/dashboard');
        }

        return redirect()->intended('/dashboard');
    }

    return back()->withErrors([
        'password' => 'Password yang Anda masukkan salah.',
    ])->withInput($request->only('email'));
}
       

    public function register(Request $request)
    {
        return view('auth.register'); // Tampilan untuk form pendaftaran
    }

    public function registerStore(Request $request)
{
    // Validasi input User
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|confirmed|min:8',
    ]);

    // Simpan data User
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'email_verified_at' => null,  // Belum diverifikasi
    ]);

    // Kirim email verifikasi (menggunakan notifikasi verifikasi bawaan Laravel)
    $user->sendEmailVerificationNotification();

    // Redirect ke halaman login setelah pendaftaran
    return redirect('/login')->with('status', 'Akun Sudah Di Buat,Silakan cek email Anda untuk memverifikasi akun.');
}


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function verifyEmail($id)
    {
        $user = User::find($id); // Mencari user berdasarkan ID

        if (!$user || $user->email_verified_at) {
            abort(404); // Jika user tidak ditemukan atau sudah diverifikasi
        }

        // Verifikasi email
        $user->email_verified_at = now();
        $user->save();

        // Login otomatis setelah email diverifikasi
        Auth::login($user); // Pastikan yang diteruskan adalah objek User

        // Setelah login, periksa apakah Data Pemilik sudah ada
        if (is_null($user->dataPemilik)) {
            return redirect()->route('data-pemilik.create'); // Arahkan ke halaman pengisian Data Pemilik
        }

        return redirect('/dashboard')->with('status', 'Email berhasil diverifikasi. Anda telah login.');
    }

    // Menampilkan form pengisian Data Pemilik
    public function showDataPemilikForm()
    {
        return view('auth.data-pemilik-create'); // Tampilan form Data Pemilik
    }

    public function showForgetPasswordForm()
    {
        return view('auth.forget-password'); // Form untuk memasukkan email
    }

    public function sendResetLink(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
    ]);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    if ($status === Password::RESET_LINK_SENT) {
        return redirect('/login')->with('status', 'Link reset password telah dikirim ke email Anda.');
    }

    return back()->withErrors(['email' => 'Gagal mengirim link reset password.']);
}


    public function showResetPasswordForm(Request $request,$token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email, // Email yang dikirim via URL
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect('/login')->with('status', 'Password berhasil direset. Silakan login.')
            : back()->withErrors(['email' => 'Gagal mereset password.']);
    }

    
}


