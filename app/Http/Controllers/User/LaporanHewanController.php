<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanHewan;
use App\Models\ReservasiHotel;

class LaporanHewanController extends Controller
{
    public function show($reservasiId)
    {
        // Ambil data reservasi berdasarkan ID
        $reservasi = ReservasiHotel::findOrFail($reservasiId);

        // Ambil semua laporan hewan yang terkait dengan reservasi ini
        $laporanHewan = LaporanHewan::where('reservasi_hotel_id', $reservasiId)->get();

        // Render view dan kirim data ke view
        return view('user.transaksi.laporanHewan', compact('reservasi', 'laporanHewan'));
    }
}
