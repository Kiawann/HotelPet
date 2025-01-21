<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\DataPemilik;
use App\Models\ReservasiHotel;
use App\Models\Transaksi;
use Illuminate\Http\Request;

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
    return view('kasir.transaksi.create', compact('reservasiHotel', 'statuses','dataPemilik'));
}



    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        
        $validated = $request->validate([
            'data_pemilik_id' => 'required|exists:data_pemilik,id', // Validasi ID pemilik
            'reservasi_hotel_id' => 'nullable|exists:reservasi_hotel,id',
            'reservasi_layanan_id' => 'nullable|exists:reservasi_layanan,id',
            'tanggal_pembayaran' => 'required|date',
            'Subtotal' => 'nullable|integer',
            'status_pembayaran' => 'required|in:Transfer,Cash',
            'Foto_Transfer' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'Dibayar' => 'nullable|integer',
            'Kembalian' => 'nullable|integer',
        ]);

        // Cek jika ada file Foto_Transfer yang diupload
        $fotoTransferPath = $request->hasFile('Foto_Transfer') 
            ? $request->file('Foto_Transfer')->store('foto_transfer', 'public') 
            : null;

        // Hitung Kembalian jika belum dihitung sebelumnya
        $kembalian = ($validated['Dibayar'] ?? 0) - ($validated['Subtotal'] ?? 0);

        // Buat data transaksi baru
        $transaksi = Transaksi::create([
            'data_pemilik_id' => $validated['data_pemilik_id'], // Simpan ID pemilik
            'reservasi_hotel_id' => $request->reservasi_hotel_id ?? null,
            'reservasi_layanan_id' => $validated['reservasi_layanan_id'] ?? null,
            'tanggal_pembayaran' => $validated['tanggal_pembayaran'],
            'Subtotal' => $validated['Subtotal'] ?? 0,
            'status_pembayaran' => $validated['status_pembayaran'],
            'Foto_Transfer' => $fotoTransferPath,
            'Dibayar' => $validated['Dibayar'],
            'Kembalian' => $kembalian,
        ]);

        // Perbarui status reservasi hotel jika ID ada
        if ($request->reservasi_hotel_id) {
            $reservasiHotel = ReservasiHotel::findOrFail($request->reservasi_hotel_id);
            $reservasiHotel->update(['status' => 'di bayar']);
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

    // Redirect ke index dengan menyertakan parameter filter yang ada
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
