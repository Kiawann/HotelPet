<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReservasiLayanan;
use App\Models\DataPemilik;
use App\Models\DataHewan;
use App\Models\KategoriLayanan;
use App\Models\RincianReservasiLayanan;
use Illuminate\Http\Request;

class ReservasiLayananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil semua data reservasi layanan dengan relasi yang diperlukan
        $reservasiLayanan = ReservasiLayanan::with('pemilik')  // Memuat relasi 'pemilik'
            ->paginate(10);  // Atur pagination sesuai kebutuhan

        return view('admin.reservasi-layanan.reservasi', compact('reservasiLayanan'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pemilik = DataPemilik::all();
        $hewan = DataHewan::all();
        $layanan = KategoriLayanan::all();
        return view('admin.reservasi-layanan.reservasi-create', compact('pemilik', 'hewan', 'layanan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validasi input
    $request->validate([
        'data_pemilik_id' => 'nullable|exists:data_pemilik,id',
        'data_hewan_id' => 'required|array|min:1',
        'data_hewan_id.*' => 'exists:data_hewan,id',  // Validasi setiap hewan
        'kategori_layanan_id' => 'required|array|min:1',
        'kategori_layanan_id.*' => 'exists:kategori_layanan,id',  // Validasi setiap layanan
        'tanggal_layanan' => 'required|array|min:1', // Pastikan tanggal layanan ada untuk setiap layanan
        'tanggal_layanan.*' => 'date', // Validasi setiap tanggal
    ]);

    // Proses penyimpanan ke tabel 'reservasi_layanan'
    $reservasi = ReservasiLayanan::create([
        'data_pemilik_id' => $request->data_pemilik_id,
        'status' => 'booked',  // Status otomatis diset 'booked'
        'tanggal_reservasi' => now(), // Tanggal reservasi otomatis sesuai waktu pembuatan
    ]);

    // Menyimpan data ke tabel 'rincian_reservasi_layanan'
    foreach ($request->data_hewan_id as $index => $hewanId) {
        $layananId = $request->kategori_layanan_id[$index];
        $tanggalLayanan = $request->tanggal_layanan[$index];

        // Ambil harga layanan
        $layanan = KategoriLayanan::findOrFail($layananId);
        $subtotal = $layanan->harga;

        // Simpan rincian reservasi
        RincianReservasiLayanan::create([
            'reservasi_layanan_id' => $reservasi->id,
            'data_hewan_id' => $hewanId,
            'kategori_layanan_id' => $layananId,
            'tanggal_pelayanan' => $tanggalLayanan, // Tanggal yang diinput oleh user
            'Harga' => $subtotal, // Simpan subtotal per layanan
        ]);
    }

    // Redirect dengan pesan sukses
    return redirect()->route('reservasi_layanan.index')->with('success', 'Reservasi layanan berhasil dibuat.');
}







    /**
     * Display the specified resource.       
     */
    public function show(string $id)
    {
        $reservasi = ReservasiLayanan::with(['pemilik', 'rincian.hewan', 'rincian.layanan'])->findOrFail($id);


        // Hitung subtotal
        $subtotal = $reservasi->rincian->sum(function ($rincian) {
            return $rincian->layanan->harga; // Pastikan kolom 'harga' ada di model Layanan
        });

        // Kirim subtotal ke view
        return view('admin.reservasi-layanan.rincian-reservasi-layanan', compact('reservasi', 'subtotal'));
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

    public function updatePembayaran(Request $request, $reservasi_layanan_id)
{
    // Validasi input
    $request->validate([
        'status_pembayaran' => 'required|in:Cash,Transfer',
        'foto_bukti_transfer' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // validasi file gambar (opsional)
    ]);

    // Cari rincian reservasi berdasarkan reservasi_layanan_id
    $rincian = RincianReservasiLayanan::where('reservasi_layanan_id', $reservasi_layanan_id)->get();

    if ($rincian->isEmpty()) {
        return redirect()->back()->withErrors(['error' => 'Reservasi tidak ditemukan.']);
    }

    // Update semua rincian dengan status pembayaran baru
    foreach ($rincian as $item) {
        // Update status pembayaran untuk masing-masing rincian
        $item->status_pembayaran = $request->input('status_pembayaran');
        
        // Jika status pembayaran adalah 'Transfer' dan ada bukti transfer, simpan foto bukti transfer
        if ($request->status_pembayaran == 'Transfer' && $request->hasFile('foto_bukti_transfer')) {
            // Unggah file bukti transfer
            $path = $request->file('foto_bukti_transfer')->store('bukti_transfer', 'public');
            
            // Simpan path foto bukti transfer ke kolom terkait pada masing-masing rincian
            $item->foto_bukti_transfer = $path;
        }
        
        $item->save(); // Simpan perubahan status pembayaran dan bukti transfer (jika ada)
    }

    // Setelah status pembayaran diperbarui, periksa apakah semua rincian telah memiliki status pembayaran
    $allPaid = RincianReservasiLayanan::where('reservasi_layanan_id', $reservasi_layanan_id)
                                      ->whereNull('status_pembayaran')
                                      ->doesntExist(); // Cek apakah tidak ada rincian yang status pembayarannya null

    if ($allPaid) {
        // Jika semua rincian memiliki status pembayaran, update status di tabel reservasi_layanan menjadi 'done'
        $reservasiLayanan = ReservasiLayanan::find($reservasi_layanan_id);
        if ($reservasiLayanan) {
            $reservasiLayanan->status = 'done'; // Set status menjadi 'done'
            $reservasiLayanan->save(); // Simpan perubahan status
        }
    }

    return redirect()->route('reservasi_layanan.index', $reservasi_layanan_id)
                     ->with('success', 'Status pembayaran berhasil diperbarui.');
}

    



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
