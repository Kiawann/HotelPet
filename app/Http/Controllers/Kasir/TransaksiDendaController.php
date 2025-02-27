<?php

namespace App\Http\Controllers\kasir;

use App\Http\Controllers\Controller;
use App\Models\ReservasiHotel;
use App\Models\RincianReservasiHotel;
use App\Models\TransaksiDenda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiDendaController extends Controller
{
    public function index()
    {
        $transaksiDenda = TransaksiDenda::with('reservasi')->latest()->paginate(10); // Menampilkan 10 data per halaman
        return view('kasir.transaksi_denda.index', compact('transaksiDenda'));
    }
    

    public function create($id)
    {
        $reservasi = ReservasiHotel::findOrFail($id);
        return view('kasir.transaksi_denda.create', compact('reservasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'reservasi_id' => 'required|exists:reservasi_hotel,id',
            'jumlah_denda' => 'required|numeric|min:0',
            'Dibayar' => [
                'nullable',
                'numeric',
                'required_if:status_pembayaran,Cash',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->status_pembayaran === 'Cash' && $value < $request->jumlah_denda) {
                        $fail('Jumlah yang dibayar harus minimal sebesar jumlah denda.');
                    }
                }
            ],
            'status_pembayaran' => 'required|in:Cash,Transfer',
            'bukti_pembayaran' => 'nullable|image|max:2048|required_if:status_pembayaran,Transfer',
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $buktiPath = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
        }

        TransaksiDenda::create([
            'reservasi_id' => $request->reservasi_id,
            'jumlah_denda' => $request->jumlah_denda,
            'Dibayar' => $request->Dibayar ?? null,
            'status_pembayaran' => $request->status_pembayaran,
            'tanggal_pembayaran' => now(),
            'bukti_pembayaran' => $buktiPath,
        ]);

        return redirect()->route('kasir-reservasi-hotel.index')->with('success', 'Transaksi denda berhasil disimpan.');
    }

    public function hitungDenda()
    {
        // Ambil waktu sekarang
        $waktuSekarang = Carbon::now();
        
        // Periksa apakah sekarang sudah lewat jam 5 sore (17:00)
        $sudahLewatJam5 = $waktuSekarang->hour >= 7;
        
        // Ambil semua rincian reservasi dengan status "belum diambil"
        // dan telah melewati tanggal checkout
        $rincianReservasi = RincianReservasiHotel::where('status', 'belum di ambil')
            ->whereDate('tanggal_checkout', '<=', $waktuSekarang->toDateString())
            ->get();
        
        // Mulai transaksi database
        DB::beginTransaction();
        
        try {
            $jumlahUpdate = 0;
            
            foreach ($rincianReservasi as $rincian) {
                // Konversi tanggal checkout ke Carbon untuk manipulasi
                $tanggalCheckout = Carbon::parse($rincian->tanggal_checkout);
                
                // Hanya berikan denda jika tanggal checkout sudah lewat 
                // dan sekarang sudah lewat jam 5 sore
                // atau tanggal checkout sudah lewat lebih dari 1 hari
                if ($tanggalCheckout->toDateString() < $waktuSekarang->toDateString() || 
                    ($tanggalCheckout->toDateString() == $waktuSekarang->toDateString() && $sudahLewatJam5)) {
                    
                    // Hitung selisih hari untuk penentuan denda
                    $selisihHari = $waktuSekarang->diffInDays($tanggalCheckout);
                    
                    // Jika hari yang sama tapi sudah lewat jam 5, tetapkan selisih minimal 1 hari
                    if ($selisihHari == 0 && $sudahLewatJam5) {
                        $selisihHari = 1;
                    }
                    
                    // Hitung total denda (Rp 70.000 per hari)
                    $totalDenda = $selisihHari * 70000;
                    
                    // Update kolom denda di tabel rincian reservasi
                    $rincian->Denda = $totalDenda;
                    $rincian->save();
                    
                    $jumlahUpdate++;
                }
            }
            
            // Commit transaksi jika semua operasi berhasil
            DB::commit();
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Berhasil menghitung denda untuk {$jumlahUpdate} reservasi"
                ], 200);
            }
            
            return redirect()->back()->with('success', "Berhasil menghitung denda untuk {$jumlahUpdate} reservasi");
            
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghitung denda: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghitung denda: ' . $e->getMessage());
        }
    }


    public function show($id)
    {
        $denda = TransaksiDenda::with('reservasi.dataPemilik')->findOrFail($id);
        return view('kasir.transaksi_denda.show', compact('denda'));
    }
}
