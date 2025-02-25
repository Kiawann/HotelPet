<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CodeOtp;
use App\Models\DataPemilik;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;


class ProfilController extends Controller
{
    /**
     * Tampilkan halaman profil pemilik.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $dataPemilik = DataPemilik::where('user_id', Auth::id())->first();

        if (!$dataPemilik) {
            return redirect()->route('dashboard')->with('error', 'Data pemilik tidak ditemukan.');
        }

        return view('user.profil', compact('dataPemilik'));
    }

    /**
     * Tampilkan form untuk mengedit profil.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $dataPemilik = DataPemilik::where('user_id', Auth::id())->first();

        if (!$dataPemilik) {
            return redirect()->route('home')->with('error', 'Data pemilik tidak ditemukan.');
        }

        return view('user.editProfil', compact('dataPemilik'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // 'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:15',
            'jenis_kelamin' => 'nullable|string|max:1',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();
        $dataPemilik = DataPemilik::where('user_id', $user->id)->first();

        if (!$dataPemilik) {
            return redirect()->route('home')->with('error', 'Data pemilik tidak ditemukan.');
        }

        $fotoPath = $dataPemilik->foto;
        if ($request->hasFile('foto')) {
            if ($fotoPath && file_exists(storage_path('app/public/' . $fotoPath))) {
                unlink(storage_path('app/public/' . $fotoPath));
            }

            $fotoPath = $request->file('foto')->store('profil_fotos', 'public');
        }

        $user->update([
            'name' => $request->input('name'),
            // 'email' => $request->input('email'),  
        ]);

        $dataPemilik->update([
            'nama' => $request->input('nama'),
            'jenis_kelamin' => $request->input('jenis_kelamin'),
            'phone' => $request->input('phone'),
            'foto' => $fotoPath,
        ]);

        return redirect()->route('profil.index')->with('success', 'Profil berhasil diperbarui.');
    }

    public function showChangePasswordForm()
    {
        return view('user.changePassword');
    }

    /**
     * Update password pengguna.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Password saat ini tidak cocok.');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('profil.index')->with('success', 'Password berhasil diperbarui.');
    }

    // public function showChangeEmailForm()
    // {
    //     return view('user.changeEmail');
    // }

    public function sendOtpChangePassword(Request $request)
{
    $request->validate([
        'phone' => 'required'
    ]);
    
    // Format nomor telepon sesuai kebutuhan, misalnya $formattedPhone
    $phone = $request->phone;
    $formattedPhone = $phone; // Lakukan format sesuai aturan jika diperlukan

    // Generate OTP misalnya 6 digit
    $otp = rand(100000, 999999);

    // Simpan OTP ke database (misalnya model CodeOtp)
    CodeOtp::updateOrCreate(
        ['phone' => $phone],
        ['otp' => $otp, 'created_at' => now()]
    );

    // Panggil API Japati untuk mengirim OTP
    $response = Http::withToken('API-TOKEN-lJ1MjuP1b1yeA5UMk7aVQNtpBycMzrjaQeTwmKQx2Geab05B1QACYo')
        ->post('https://app.japati.id/api/send-message', [
            'gateway' => '6288222087560',
            'number' => $formattedPhone,
            'type' => 'text',
            'message' => "*$otp* adalah kode verifikasi Anda. Gunakan kode ini untuk mengakses akun Fur Haven Pet Hotel.",
        ]);

    if($response->successful()){
        // Simpan nomor ke session untuk validasi OTP
        session(['phone' => $phone]);
        return response()->json(['message' => 'OTP berhasil dikirim.']);
    } else {
        return response()->json(['message' => 'Gagal mengirim OTP.'], 500);
    }
}

public function validateOtpChangePassword(Request $request)
{
    $request->validate([
        'otp' => 'required|numeric|digits:6',
    ]);

    $otp = $request->otp;
    $phone = session('phone');

    if (!$phone) {
        return response()->json(['message' => 'Session expired.'], 400);
    }

    $codeOtp = CodeOtp::where('phone', $phone)->first();

    if (!$codeOtp) {
        return response()->json(['message' => 'OTP tidak ditemukan.'], 404);
    }

    if ($codeOtp->otp == $otp) {
        // Hapus OTP setelah validasi
        $codeOtp->delete();
        return response()->json(['message' => 'OTP valid.']);
    } else {
        return response()->json(['message' => 'OTP tidak valid.'], 400);
    }
}



    public function changeEmail(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_email' => 'required|email|unique:users,email',
        ]);
    
        $user = Auth::user();
    
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Password saat ini tidak cocok.');
        }
    
        // Simpan email baru ke tabel temporary
        DB::table('email_changes')->updateOrInsert(
            ['user_id' => $user->id],
            [
                'new_email' => $request->new_email,
                'token' => Str::random(60),
                'created_at' => now()
            ]
        );
    
        // Kirim email verifikasi ke alamat email baru
        $token = DB::table('email_changes')->where('user_id', $user->id)->value('token');
        Mail::send('mails.verification', [
            'name' => $user->name,  // Kirimkan nama pengguna
            'token' => $token,      // Kirimkan token untuk verifikasi
        ], function ($message) use ($request) {
            $message->to($request->new_email);
            $message->subject('Verifikasi Perubahan Email');
        });
    
        return redirect()->route('profil.index')
            ->with('success', 'Silakan cek email baru Anda untuk melakukan verifikasi perubahan email.');
    }
    
    public function verifyEmailChange($token)
    {
        // Cari data perubahan email berdasarkan token
        $emailChange = DB::table('email_changes')->where('token', $token)->first();
    
        if (!$emailChange) {
            return redirect()->route('profil.index')
                ->with('error', 'Link verifikasi tidak valid atau sudah kadaluarsa.');
        }
    
        // Update email user
        $user = User::find($emailChange->user_id);
        $user->email = $emailChange->new_email;
        $user->email_verified_at = now();
        $user->save();
    
        // Hapus data temporary
        DB::table('email_changes')->where('user_id', $user->id)->delete();
    
        return redirect()->route('profil.index')
            ->with('success', 'Email berhasil diperbarui dan diverifikasi.');
    }
    
}
