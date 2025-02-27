<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\DataPemilik;
use App\Models\ReservasiHotel;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KasirTransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($reservasi_hotel_id)
    {
        // Mendapatkan data reservasi berdasarkan ID
        $reservasiHotel = ReservasiHotel::findOrFail($reservasi_hotel_id);

        // Menyiapkan array status pembayaran
        $statuses = ['Transfer', 'Cash'];

        // Mengambil data pemilik berdasarkan relasi yang ada
        $dataPemilik = $reservasiHotel->dataPemilik; // Pastikan relasi sudah didefinisikan di model

        // Tampilkan halaman form transaksi dengan data reservasi dan status pembayaran
        return view('kasir.transaksi.create', compact('reservasiHotel', 'statuses', 'dataPemilik'));
    }





    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'data_pemilik_id' => 'required|exists:data_pemilik,id',
            'reservasi_hotel_id' => 'nullable|exists:reservasi_hotel,id',
            'reservasi_layanan_id' => 'nullable|exists:reservasi_layanan,id',
            'tanggal_pembayaran' => 'required|date',
            'Subtotal' => 'required|integer|min:0',
            'status_pembayaran' => 'required|in:Transfer,Cash',
            'Foto_Transfer' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'Dibayar' => 'required|integer|min:0',
        ]);
    
        $subtotal = $validated['Subtotal'];
        $dibayar = $validated['Dibayar'];
        $kembalian = $dibayar - $subtotal;
    
        // Jika Dibayar kurang dari Subtotal, kembali ke form dengan error
        if ($dibayar < $subtotal) {
            return back()
                ->withErrors(['Dibayar' => 'Jumlah dibayar kurang ' . number_format($subtotal - $dibayar) . ' rupiah'])
                ->withInput();
        }
    
        // Simpan foto transfer jika ada
        $fotoTransferPath = $request->hasFile('Foto_Transfer') 
            ? $request->file('Foto_Transfer')->store('foto_transfer', 'public') 
            : null;
    
        // Simpan transaksi
        $transaksi = Transaksi::create([
            'data_pemilik_id' => $validated['data_pemilik_id'],
            'reservasi_hotel_id' => $request->reservasi_hotel_id ?? null,
            'reservasi_layanan_id' => $validated['reservasi_layanan_id'] ?? null,
            'tanggal_pembayaran' => $validated['tanggal_pembayaran'],
            'Subtotal' => $subtotal,
            'status_pembayaran' => $validated['status_pembayaran'],
            'Foto_Transfer' => $fotoTransferPath,
            'Dibayar' => $dibayar,
            'Kembalian' => $kembalian,
        ]);
    
        // Inisialisasi variabel user
        $user = null;
    
        // Jika reservasi hotel ada, perbarui status dan ambil user melalui relasi dataPemilik
        if ($request->reservasi_hotel_id) {
            $reservasiHotel = ReservasiHotel::findOrFail($request->reservasi_hotel_id);
            $reservasiHotel->update(['status' => 'di bayar']);
    
            // Mengambil dataPemilik dari reservasi, lalu user terkait dari dataPemilik
            if ($reservasiHotel->dataPemilik && $reservasiHotel->dataPemilik->user) {
                $user = $reservasiHotel->dataPemilik->user;
            }
        } else {
            // Jika tidak ada reservasi hotel, ambil DataPemilik berdasarkan id dan user terkait
            $dataPemilik = \App\Models\DataPemilik::findOrFail($validated['data_pemilik_id']);
            if ($dataPemilik->user) {
                $user = $dataPemilik->user;
            }
        }
    
        // Kirim pesan WhatsApp jika user ditemukan, memiliki role "user" dan nomor telepon valid
        if ($user && $user->role == 'user' && !empty($user->phone)) {
            $tanggalPembayaran = \Carbon\Carbon::parse($validated['tanggal_pembayaran'])->format('d-m-Y');
            $message = "Halo {$user->name}, pembayaran Anda telah berhasil! 🎉\n\n"
                     . "Detail Pembayaran:\n"
                     . "Total: Rp" . number_format($subtotal) . "\n"
                     . "Dibayar: Rp" . number_format($dibayar) . "\n"
                     . "Kembalian: Rp" . number_format($kembalian) . "\n\n"
                     . "Tanggal Pembayaran: " . ($tanggalPembayaran) . "\n\n"
                     . "Terima kasih telah menggunakan layanan kami!";
    
            $apiToken = 'API-TOKEN-lJ1MjuP1b1yeA5UMk7aVQNtpBycMzrjaQeTwmKQx2Geab05B1QACYo';
            $gatewayNumber = '6288222087560';
    
            $response = Http::withToken($apiToken)->post('https://app.japati.id/api/send-message', [
                'gateway' => $gatewayNumber,
                'number'  => $user->phone,
                'type'    => 'text',
                'message' => $message,
            ]);
    
            if (!$response->successful()) {
                Log::error('Gagal mengirim WhatsApp: ' . $response->body());
            }
        }
    
        // Simpan filter yang ada dari request
        $statusFilter = $request->input('status');
        $dateFilter = $request->input('date_filter');
    
        // Buat array parameter untuk redirect
        $redirectParams = [];
        if ($statusFilter) {
            $redirectParams['status'] = $statusFilter;
        }
        if ($dateFilter) {
            $redirectParams['date_filter'] = $dateFilter;
        }
    
        // Redirect ke halaman transaksi-struk dengan parameter filter yang ada
        return redirect()->route('transaksi-struk', ['reservasi_hotel_id' => $transaksi->reservasi_hotel_id])
            ->with('success', 'Pembayaran berhasil.');
    }
    
    




    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function showStruk($reservasiHotelId)
    {
        // Ambil data transaksi berdasarkan reservasi hotel
        $transaksi = Transaksi::with([
            'reservasiHotel.dataPemilik',
            'reservasiHotel.rincianReservasiHotel.dataHewan',
            'reservasiHotel.rincianReservasiHotel.room'
        ])->where('reservasi_hotel_id', $reservasiHotelId)->first();

        if (!$transaksi) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');
        }

        // Return view struk dengan data transaksi
        return view('kasir.transaksi.struk', compact('transaksi'));
    }




    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
