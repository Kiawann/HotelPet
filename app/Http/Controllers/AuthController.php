<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DataPemilik;
use Illuminate\Support\Facades\Hash;

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

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            if (Auth::user()->role == 'admin') {
                return redirect('admin/dashboard');
            }

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function register(Request $request, $step = 1)
    {
        if ($step == 1) {
            return view('auth.register'); // View untuk User
        } elseif ($step == 2) {
            return view('auth.data-pemilik-create'); // View untuk Data Pemilik
        }
    
        abort(404); // Jika langkah tidak valid
    }
    

    public function registerStore(Request $request, $step = 1)
    {
        if ($step == 1) {
            // Validasi input User dengan pesan khusus
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed|min:8',
            ], [
                'name.required' => 'Nama wajib diisi.',
                'name.string' => 'Nama harus berupa teks.',
                'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah terdaftar.',
                'password.required' => 'Password wajib diisi.',
                'password.confirmed' => 'Konfirmasi password tidak sesuai.',
                'password.min' => 'Password harus terdiri dari minimal 8 karakter.',
            ]);
    
            // Simpan data sementara di session
            $request->session()->put('user_data', [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
    
            return redirect()->route('register', ['step' => 2]);
        }
    
        if ($step == 2) {
            // Validasi input Data Pemilik dengan pesan khusus
            $request->validate([
                'nama' => 'required|string|max:255',
                'jenis_kelamin' => 'required|in:L,P',
                'nomor_telp' => 'required|string',
                'foto' => 'nullable|image|max:2048',
            ], [
                'nama.required' => 'Nama pemilik wajib diisi.',
                'nama.string' => 'Nama pemilik harus berupa teks.',
                'nama.max' => 'Nama pemilik tidak boleh lebih dari 255 karakter.',
                'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
                'jenis_kelamin.in' => 'Pilihan jenis kelamin tidak valid.',
                'nomor_telp.required' => 'Nomor telepon wajib diisi.',
                'nomor_telp.string' => 'Nomor telepon harus berupa teks.',
                'foto.image' => 'Foto harus berupa file gambar.',
                'foto.max' => 'Ukuran foto tidak boleh lebih dari 2MB.',
            ]);
    
            // Ambil data User dari session
            $userData = $request->session()->get('user_data');
            if (!$userData) {
                return redirect()->route('register', ['step' => 1])
                    ->withErrors(['message' => 'Data pengguna belum lengkap.']);
            }
    
            // Simpan data User ke database
            $user = User::create($userData);
    
            // Simpan data Pemilik ke database
            $fotoPath = $request->file('foto') 
                ? $request->file('foto')->store('foto_pemilik', 'public') 
                : null;
    
            DataPemilik::create([
                'user_id' => $user->id,
                'nama' => $request->nama,
                'jenis_kelamin' => $request->jenis_kelamin,
                'nomor_telp' => $request->nomor_telp,
                'foto' => $fotoPath,
            ]);
    
            // Hapus data dari session
            $request->session()->forget('user_data');
    
            // Login otomatis
            Auth::login($user);
    
            return redirect('/dashboard');
        }
    
        abort(404);
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
