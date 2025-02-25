<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataPemilik;
use App\Models\ReservasiHotel;
use App\Models\ReservasiLayanan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaksi = Transaksi::with(['dataPemilik', 'reservasiHotel', 'reservasiLayanan'])->get();
        return view('admin.transaksi.index', compact('transaksi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Ambil semua data pemilik
        $dataPemilik = DataPemilik::all();
        
        // Ambil data reservasi hotel dengan relasi ke data pemilik
        $reservasiHotel = ReservasiHotel::with('dataPemilik')->get();
        
        // Ambil data reservasi hotel berdasarkan ID jika ada di request
        $selectedReservasiHotelId = $request->input('reservasi_hotel_id', null); // Menggunakan input dengan nilai default null
        $subtotal = 0; // Inisialisasi subtotal dengan nilai 0
        
        // Jika ada reservasi hotel yang dipilih, ambil rincian dan hitung subtotal
        if ($selectedReservasiHotelId) {
            $selectedHotel = ReservasiHotel::with('rincianReservasiHotel')->find($selectedReservasiHotelId);
            
            // Hitung subtotal berdasarkan rincian reservasi hotel
            foreach ($selectedHotel->rincianReservasiHotel as $rincian) {
                $subtotal += $rincian->SubTotal;  // Asumsi ada field SubTotal pada rincian
            }
        }
        
        // Kirimkan data ke view
        return view('admin.transaksi.create', compact('dataPemilik', 'reservasiHotel', 'selectedReservasiHotelId', 'subtotal'));
    }
    
    
    public function LaporanTransaksi()
    {
        $totals = [];
        for ($i = 1; $i <= 12; $i++) {
            $totals[$i] = Transaksi::whereMonth('tanggal_pembayaran', $i)
                ->whereYear('tanggal_pembayaran', date('Y'))
                ->sum('Subtotal');
        }

        return view('admin.transaksi.laporan', compact('totals'));
    }
    
    public function cetakPdf(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));

        $totals = [];
        for ($i = 1; $i <= 12; $i++) {
            $totals[$i] = Transaksi::whereMonth('tanggal_pembayaran', $i)
                ->whereYear('tanggal_pembayaran', $tahun)
                ->sum('Subtotal');
        }

        $pdf = Pdf::loadView('admin.transaksi.transaksi_pdf', compact('tahun', 'totals'));

        return $pdf->download("laporan-transaksi-$tahun.pdf");
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi inputan
        $validated = $request->validate([
            'data_pemilik_id' => 'required|exists:data_pemilik,id',
            'reservasi_hotel_id' => 'nullable|exists:reservasi_hotel,id', // Nullable untuk memperbolehkan kosong
            'reservasi_layanan_id' => 'nullable|exists:reservasi_layanan,id',
            'tanggal_pembayaran' => 'required|date',
            'subtotal' => 'nullable|integer',  // Gunakan huruf kecil untuk kolom
            'status_pembayaran' => 'required|in:Transfer,Cash',
            'Foto_Transfer' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi foto transfer
        ]);
        
        // Cek jika ada 'reservasi_hotel_id' dalam input, jika tidak ada, gunakan subtotal yang diinputkan
        if ($request->has('reservasi_hotel_id') && $validated['reservasi_hotel_id']) {
            $reservasiHotel = ReservasiHotel::find($validated['reservasi_hotel_id']);
            $subtotal = 0;
        
            // Jika reservasi hotel ditemukan, hitung subtotal berdasarkan rincian
            foreach ($reservasiHotel->rincian as $rincian) {
                $subtotal += $rincian->harga;  // Ganti 'harga' dengan field yang sesuai jika ada
            }
        } else {
            // Jika tidak ada reservasi hotel, gunakan subtotal dari input
            $subtotal = $validated['subtotal'] ?? 0; 
        }
    
        // Menangani upload foto transfer jika ada
        if ($request->hasFile('Foto_Transfer')) {
            $fotoTransferPath = $request->file('Foto_Transfer')->store('foto_transfer', 'public'); // Menyimpan foto di storage/app/public/uploads/foto_transfer
        } else {
            $fotoTransferPath = null; // Jika tidak ada foto transfer, set null
        }
        
        // Membuat data transaksi baru
        Transaksi::create([
            'data_pemilik_id' => $validated['data_pemilik_id'],
            'reservasi_hotel_id' => $validated['reservasi_hotel_id'] ?? null, // Menangani nullable
            'reservasi_layanan_id' => $validated['reservasi_layanan_id'] ?? null, // Menangani nullable
            'tanggal_pembayaran' => $validated['tanggal_pembayaran'],
            'subtotal' => $subtotal, // Menyimpan subtotal yang dihitung
            'status_pembayaran' => $validated['status_pembayaran'],
            'Foto_Transfer' => $fotoTransferPath, // Menyimpan path foto transfer
        ]);
        
        // Redirect dengan pesan sukses
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil ditambahkan.');
    }
    
    

    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
