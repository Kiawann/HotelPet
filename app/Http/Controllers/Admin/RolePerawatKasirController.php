<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DataPemilik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:perawat,kasir', // Hanya perawat & kasir
        ]);
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role, // Ambil role langsung dari request
        ]);
    
        return redirect()->route('data-pemilik.admin-create-data-pemilik', ['user' => $user->id])
            ->with('success', 'Akun berhasil dibuat. Silakan lengkapi data pemilik.');
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
        'nomor_telp' => 'required|string|max:15',
        'foto' => 'nullable|image|max:2048'
    ]);

    $data = $request->all();
    
    if ($request->hasFile('foto')) {
        $foto = $request->file('foto');
        $filename = time() . '.' . $foto->getClientOriginalExtension();
        $foto->storeAs('public/foto_pemilik', $filename);
        $data['foto'] = $filename;
    }

    // Menyimpan data pemilik
    DataPemilik::create($data);

    // Update status email user menjadi terverifikasi otomatis
    $user = User::findOrFail($request->user_id);
    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }

    // Redirect dengan pesan sukses
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
