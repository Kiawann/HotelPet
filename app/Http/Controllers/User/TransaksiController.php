<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DataPemilik;
use App\Models\ReservasiHotel;
use App\Models\ReservasiLayanan;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class TransaksiController extends Controller
{
    public function create(Request $request)
    {
        $dataPemilik = DataPemilik::all();
        $reservasiHotels = ReservasiHotel::all();
        $reservasiLayanans = ReservasiLayanan::all();
    
        // Ambil data reservasi hotel berdasarkan ID yang dikirimkan
        $reservasiHotelId = $request->query('reservasi_hotel_id');
        $reservasiHotel = ReservasiHotel::with('dataPemilik')->find($reservasiHotelId);
    
        if (!$reservasiHotel) {
            return redirect()->back()->with('error', 'Data reservasi hotel tidak ditemukan');
        }
    
        // Hitung total harga
        $totalHarga = $reservasiHotel->rincianReservasiHotel->sum('SubTotal');
    
        return view('user.transaksi.transaksi', compact('dataPemilik', 'reservasiHotels', 'reservasiLayanans', 'reservasiHotel', 'totalHarga'));
    }
    // public function store(Request $request)
    // {
    //     // dd($request->all()); // Menampilkan semua input dari form

    //     // Validasi input
    //     $request->validate([
    //         'data_pemilik_id' => 'required|exists:data_pemilik,id',
    //         'reservasi_hotel_id' => 'required|exists:reservasi_hotel,id',
    //         'reservasi_layanan_id' => 'nullable|string', // Pastikan ini nullable
    //         'tanggal_pembayaran' => 'nullable|date',
    //         'Subtotal' => 'required|numeric',
    //         'status_pembayaran' => 'required|in:Transfer,Cash',
    //         'Foto_Transfer' => 'nullable|image|max:2048',
    //     ]);
    
    //     // Tanggal pembayaran
    //     $tanggalPembayaran = $request->tanggal_pembayaran ?? now()->format('Y-m-d');
    
    //     // Menyimpan foto transfer
    //     $fotoTransferPath = null;
    //     if ($request->hasFile('Foto_Transfer')) {
    //         $fotoTransferPath = $request->file('Foto_Transfer')->store('foto_transfer', 'public');
    //     }
    
    //     // Membuat transaksi baru
    //     $transaksi = Transaksi::create([
    //         'data_pemilik_id' => $request->data_pemilik_id,
    //         'reservasi_hotel_id' => $request->reservasi_hotel_id,
    //         'reservasi_layanan_id' => $request->reservasi_layanan_id, // Ini akan menyimpan '-'
    //         'tanggal_pembayaran' => $tanggalPembayaran,
    //         'Subtotal' => (int) $request->Subtotal,
    //         'status_pembayaran' => $request->status_pembayaran,
    //         'Foto_Transfer' => $fotoTransferPath,
    //     ]);
    
    //     return redirect()->route('booking.index')->with('success', 'Transaksi berhasil dibuat.');
    // }
    public function store(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'data_pemilik_id' => 'required|exists:data_pemilik,id',
                'reservasi_hotel_id' => 'required|exists:reservasi_hotel,id',
                'reservasi_layanan_id' => 'nullable|exists:reservasi_layanan,id',
                'tanggal_pembayaran' => 'nullable|date',
                'Subtotal' => 'required|numeric',
                'status_pembayaran' => 'required|in:Transfer,Cash',
                'Foto_Transfer' => 'nullable|image|max:2048',
            ]);
    
            // Handle file upload
            $fotoTransferPath = null;
            if ($request->hasFile('Foto_Transfer')) {
                $fotoTransferPath = $request->file('Foto_Transfer')->store('foto_transfer', 'public');
            }
    
            // Create transaction
            $transaksi = Transaksi::create([
                'data_pemilik_id' => $request->data_pemilik_id,
                'reservasi_hotel_id' => $request->reservasi_hotel_id,
                'reservasi_layanan_id' => $request->reservasi_layanan_id,
                'tanggal_pembayaran' => $request->tanggal_pembayaran ?? now()->format('Y-m-d'),
                'Subtotal' => $request->Subtotal,
                'status_pembayaran' => $request->status_pembayaran,
                'Foto_Transfer' => $fotoTransferPath,
            ]);
    
            // Update the status of the hotel reservation to "Di Bayar"
            $reservasiHotel = ReservasiHotel::find($request->reservasi_hotel_id);
            if ($reservasiHotel) {
                $reservasiHotel->status = 'Di Bayar';
                $reservasiHotel->save();
            }
    
                    // Kirim pesan ke WhatsApp
        $apiToken = 'API-TOKEN-lJ1MjuP1b1yeA5UMk7aVQNtpBycMzrjaQeTwmKQx2Geab05B1QACYo';
        $gatewayNumber = '6288222087560';
        $kasirUsers = User::where('role', 'kasir')->get();

        foreach ($kasirUsers as $kasir) {
            if ($kasir->phone) {
                $message = "📌 *Pembayaran Baru*\n"
                . "------------------------\n"
                . "🔹 *ID Reservasi:* {$request->reservasi_hotel_id}\n"
                . "🔹 *Tanggal Pembayaran:* {$transaksi->tanggal_pembayaran}\n"
                . "🔹 *Nama:* {$request->user()->name}\n"
                . "------------------------\n"
                . "✅ Pembayaran telah dilakukan.";
                Http::withToken($apiToken)->post('https://app.japati.id/api/send-message', [
                    'gateway' => $gatewayNumber,
                    'number' => $kasir->phone,
                    'type' => 'text',
                    'message' => $message,
                ]);
            }
        }
    
            // Redirect to the booking index
            return redirect()->route('booking.index')->with('success', 'Transaksi berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal membuat transaksi: ' . $e->getMessage());
        }
    }
    
}
