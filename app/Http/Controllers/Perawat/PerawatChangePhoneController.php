<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CodeOtp;
use App\Models\DataPemilik;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PerawatChangePhoneController extends Controller
{
    public function changePhonePerawat(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|regex:/^[0-9]+$/|min:10|max:13',
        ]);

        $user = Auth::user();
        $phone = $request->phone;

        if ($user->phone == $phone) {
            return redirect()->back()->with('warning', 'Silakan masukkan nomor baru yang berbeda dari yang sebelumnya.');
        }

        if (User::where('phone', $phone)->exists()) {
            return redirect()->back()->with('error', 'Nomor telepon ini sudah terdaftar.');
        }

        $otp = rand(100000, 999999);

        CodeOtp::updateOrCreate(
            ['phone' => $phone],
            ['otp' => $otp]
        );

        // Kirim OTP ke nomor telepon
        try {
            $response = Http::withToken('API-TOKEN-lJ1MjuP1b1yeA5UMk7aVQNtpBycMzrjaQeTwmKQx2Geab05B1QACYo')
                ->post('https://app.japati.id/api/send-message', [
                    'gateway' => '6288222087560',
                    'number' => $phone,
                    'type' => 'text',
                    'message' => "*{$otp}* adalah kode verifikasi Anda. Gunakan kode ini untuk mengakses akun Fur Haven Pet Hotel.",
                ]);

            if ($response->failed()) {
                return redirect()->back()->with('error', 'Gagal mengirim OTP, silakan coba lagi.');
            }

            // Simpan nomor telepon di session hanya jika pengiriman berhasil
            session(['phone' => $phone]);

            return ($user->role === 'perawat')
                ? redirect()->route('perawat-validate-otp-show')->with('success', 'Kode OTP telah dikirim.')
                : redirect()->back()->with('send', 'Kode OTP telah dikirim.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengirim OTP.');
        }
    }

    public function validateOtpPerawat(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
        ]);

        $user = Auth::user();
        $otp = $request->otp;
        $phone = session('phone');

        $codeOtp = CodeOtp::where('phone', $phone)->first();

        if ($codeOtp && $codeOtp->otp == $otp) {
            $codeOtp->delete();

            $messageUser = "Halo, *{$user->name}*!\nAkun Anda di *Fur Haven Pet Hotel* kini terhubung dengan nomor *{$phone}*. Jika ini bukan Anda, silakan cek kembali pengaturan akun.";

            $messagePhone = "Nomor ini kini terhubung dengan akun *{$user->name}* di *Fur Haven Pet Hotel*. Pastikan informasi akun Anda sudah sesuai.";

            Http::withToken('API-TOKEN-lJ1MjuP1b1yeA5UMk7aVQNtpBycMzrjaQeTwmKQx2Geab05B1QACYo')
                ->post('https://app.japati.id/api/send-message', [
                    'gateway' => '6288222087560',
                    'number' => $user->phone,
                    'type' => 'text',
                    'message' => $messageUser,
                ]);

            Http::withToken('API-TOKEN-lJ1MjuP1b1yeA5UMk7aVQNtpBycMzrjaQeTwmKQx2Geab05B1QACYo')
                ->post('https://app.japati.id/api/send-message', [
                    'gateway' => '6288222087560',
                    'number' => $phone,
                    'type' => 'text',
                    'message' => $messagePhone,
                ]);

            $user->update([
                'phone' => $phone,
                'phone_verified_at' => now(),
            ]);

            if (Auth::user()->role === 'perawat') {
                return redirect()->route('perawat-profil')->with('success', 'Phone number updated successfully!');
            }
            return redirect()->route('perawat-dashboard')->with('success', 'Phone number updated successfully!');
        } else {
            return redirect()->back()->with('invalid-otp', 'Invalid OTP');
        }
    }

    public function showValidateOtpPerawat()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'perawat') {
            return redirect()->route('perawat-profile')->with('error', 'Akses ditolak.');
        }

        $customer = User::find($user->id);
        $member = $customer ? $customer->dataPemilik()->first() : null;

        return view('perawat.profile.validate-otp', compact('user', 'customer', 'member'));
    }
}
