<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CodeOtp;
use App\Models\User;
use App\Models\DataPemilik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class RolePerawatKasirController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::whereIn('role', ['perawat', 'kasir'])->get();
        return view('admin.role_perawat_kasir.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.data_pemilik.create');
    }

    /**
     * Store a newly created user in storage and send verification email.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15|unique:users,phone',
            'otp' => 'required|string|max:6',
            'role' => 'required|in:perawat,kasir', // Hanya bisa memilih perawat atau kasir
        ]);
    
        // Cek apakah OTP valid
        $otpRecord = CodeOtp::where('phone', $request->phone)
                            ->where('otp', $request->otp)
                            ->first();
    
        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid atau salah'])->withInput();
        }
    
        // Simpan user jika OTP benar tanpa password
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => null, // Password tidak diisi
            'role' => $request->role, // Ambil role dari input
            'phone_verified_at' => now(),
        ]);
    
        // Tambahkan data pemilik dengan nama NULL
        DataPemilik::create([
            'user_id' => $user->id,
            'nama' => null, // Nama dibiarkan kosong
            'phone' => $request->phone, // Sama dengan nomor telepon user
            'jenis_kelamin' => null, // Bisa diisi nanti oleh user
            'foto' => null, // Bisa diisi nanti oleh user
        ]);
    
        // Hapus OTP setelah digunakan
        $otpRecord->delete();
    
        return redirect()->route('data-pemilik.admin-create-data-pemilik', ['user' => $user->id])
            ->with('success', 'Akun berhasil dibuat.');
    }
    

    

    /**
     * Show the form for creating data pemilik after email verification.
     */
    public function createdatapemilik($userId)
{
    $user = User::findOrFail($userId);
    return view('admin.data_pemilik.create_datapemilik', compact('user'));
}


    /**
     * Store a newly created data pemilik in storage.
     */


public function storedatapemilik(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'nama' => 'required|string|max:255',
        'jenis_kelamin' => 'required|in:L,P',
        'foto' => 'nullable|image|max:2048'
    ]);

    $user = User::findOrFail($request->user_id);

    // Cek apakah data_pemilik sudah ada
    $dataPemilik = DataPemilik::where('user_id', $user->id)->first();

    if ($dataPemilik) {
        // Jika sudah ada, update datanya
        $fotoPath = $dataPemilik->foto;

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }

            // Simpan foto baru
            $fotoPath = $request->file('foto')->store('profil_fotos', 'public');
        }

        $dataPemilik->update([
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'phone' => $user->phone,
            'foto' => $fotoPath, // Simpan foto baru atau tetap pakai yang lama
        ]);
    } else {
        // Jika belum ada, buat data baru
        $fotoPath = null;

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('profil_fotos', 'public');
        }

        DataPemilik::create([
            'user_id' => $user->id,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'phone' => $user->phone,
            'foto' => $fotoPath,
        ]);
    }

    if (is_null($user->phone_verified_at)) {
        $user->update(['phone_verified_at' => now()]);
    }

    return redirect()->route('data_pemilik.index')
        ->with('success', 'Data pemilik berhasil disimpan dan email telah diverifikasi secara otomatis.');
}




public function verify($id)
{
    $user = User::findOrFail($id);

    // Jika email sudah diverifikasi, alihkan ke halaman login
    if ($user->email_verified_at) {
        return redirect()->route('login')
            ->with('info', 'Email sudah diverifikasi sebelumnya.');
    }

    // Tandai email sebagai diverifikasi
    $user->email_verified_at = now();
    $user->save();

    // Redirect ke login setelah verifikasi
    return redirect()->route('login')
        ->with('success', 'Email berhasil diverifikasi. Silakan login.');
}

}
