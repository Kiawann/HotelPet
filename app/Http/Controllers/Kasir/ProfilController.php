<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\CodeOtp;
use App\Models\DataPemilik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProfilController extends Controller
{
    public function index()
    {
        $dataPemilik = DataPemilik::where('user_id', Auth::id())->first();

        if (!$dataPemilik) {
            return redirect()->route('dashboard')->with('error', 'Data pemilik tidak ditemukan.');
        }

        return view('kasir.profile.profil', compact('dataPemilik'));
    }

    /**
     * Memperbarui data profil pengguna.
     */
    public function update(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Get the authenticated user
        $user = Auth::user();
    
        // Retrieve the DataPemilik associated with the user
        $dataPemilik = DataPemilik::where('user_id', $user->id)->first();
    
        if (!$dataPemilik) {
            return redirect()->route('dashboard')->with('error', 'Data pemilik tidak ditemukan.');
        }
    
        // Update the fields in DataPemilik
        $dataPemilik->nama = $request->nama;
        $dataPemilik->jenis_kelamin = $request->jenis_kelamin;
    
        // If a new photo is uploaded
        if ($request->hasFile('foto')) {
            // Delete old photo if it exists
            if ($dataPemilik->foto) {
                Storage::delete('public/' . $dataPemilik->foto);
            }
    
            // Store the new photo
            $fotoPath = $request->file('foto')->store('profile_pictures', 'public');
            $dataPemilik->foto = $fotoPath;
        }
    
        $dataPemilik->save(); // Save changes to DataPemilik
    
        return redirect()->route('kasir-profil.index')->with('success', 'Profil berhasil diperbarui!');
    }

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

        return redirect()->route('kasir-profil.index')->with('success', 'Password berhasil diperbarui.');
    }

    public function sendOtpChangePassword(Request $request)
    {
        $request->validate(['phone' => 'required']);
        
        $phone = $request->phone;
        $formattedPhone = $phone; // Lakukan format jika diperlukan
    
        $otp = rand(100000, 999999);
    
        CodeOtp::updateOrCreate(
            ['phone' => $phone],
            [
                'otp' => $otp, 
                'created_at' => now(), 
                'expires_at' => now()->addMinutes(5)
            ]
        );
    
        $response = Http::withToken('API-TOKEN-lJ1MjuP1b1yeA5UMk7aVQNtpBycMzrjaQeTwmKQx2Geab05B1QACYo')
            ->post('https://app.japati.id/api/send-message', [
                'gateway' => '6288222087560',
                'number' => $formattedPhone,
                'type' => 'text',
                'message' => "*$otp* adalah kode verifikasi Anda. Gunakan kode ini untuk mengakses akun Fur Haven Pet Hotel.",
            ]);
    
        if ($response->successful()) {
            return redirect()->route('verify-otp-page')
                             ->with('success', 'Kode OTP sudah terkirim');
        } else {
            return redirect()->back()
                             ->with('error', 'Gagal mengirim OTP.');
        }
    }
    
    public function validateOtpChangePassword(Request $request)
    {
        $otp = $request->otp;
        $phone = Auth::user()->phone;
        
        $codeOtp = CodeOtp::where('phone', $phone)->first();
        if (!$codeOtp) {
            return redirect()->back()->with('error', 'OTP tidak ditemukan');
        }
        
        if (Carbon::now()->greaterThan($codeOtp->expires_at)) {
            return redirect()->back()->with('error', 'OTP telah kadaluarsa');
        }
        
        if ($codeOtp->otp == $otp) {
            // Hapus OTP jika verifikasi berhasil (opsional)
            $codeOtp->delete();
            return redirect()->route('change-password-page')
                             ->with('success', 'OTP valid, silahkan ubah password');
        }
        
        return redirect()->back()->with('error', 'OTP tidak valid');
    }
    

    
    public function showVerifyOtp()
    {
        return view('kasir.profile.verifikasi');
    }

    public function showChangePassword()
    {
        return view('kasir.profile.changepassword');
    }

}
