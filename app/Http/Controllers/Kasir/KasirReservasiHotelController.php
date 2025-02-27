<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\ReservasiHotel;
use App\Models\RincianReservasiHotel;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KasirReservasiHotelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get today's date at midnight (start of day)
        $today = now()->startOfDay()->format('Y-m-d H:i:s');
    
        $query = ReservasiHotel::with([
            'rincianReservasiHotel.dataHewan',
            'rincianReservasiHotel.room',
            'transaksi'
        ])->where('status', '!=', 'done');
    
        // Apply status filter and corresponding date filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
    
            if ($request->status === 'check out' && $request->filled('date_filter')) {
                if ($request->date_filter === 'today') {
                    $query->whereDate('tanggal_checkout', $today);
                } elseif ($request->date_filter === 'yesterday') {
                    $query->whereDate('tanggal_checkout', now()->subDay()->format('Y-m-d'));
                } elseif ($request->date_filter === 'all_dates') {
                    $query->whereRaw('tanggal_checkout >= ?', [$today]);
                }
            } elseif ($request->status === 'check in' && $request->filled('date_filter')) {
                if ($request->date_filter === 'today') {
                    $query->whereDate('tanggal_checkin', now()->format('Y-m-d'));
                } elseif ($request->date_filter === 'yesterday') {
                    $query->whereDate('tanggal_checkin', now()->subDay()->format('Y-m-d'));
                } elseif ($request->date_filter === 'all_dates') {
                    $query->whereRaw('DATE(tanggal_checkin) >= DATE(?)', [$today]);
                }
            } elseif ($request->filled('date_filter')) {
                if ($request->date_filter === 'today') {
                    $query->whereDate('tanggal_checkin', now()->format('Y-m-d'));
                } elseif ($request->date_filter === 'yesterday') {
                    $query->whereDate('tanggal_checkin', now()->subDay()->format('Y-m-d'));
                } elseif ($request->date_filter === 'all_dates') {
                    $query->whereRaw('DATE(tanggal_checkin) >= DATE(?)', [$today]);
                }
            }
        } elseif ($request->filled('date_filter')) {
            if ($request->date_filter === 'today') {
                $query->where(function ($q) {
                    $today = now()->format('Y-m-d');
                    $q->whereDate('tanggal_checkin', $today)
                        ->orWhere(function ($q2) use ($today) {
                            $q2->where('status', 'check out')
                                ->whereDate('tanggal_checkout', $today);
                        });
                });
            } elseif ($request->date_filter === 'yesterday') {
                $query->where(function ($q) {
                    $yesterday = now()->subDay()->format('Y-m-d');
                    $q->whereDate('tanggal_checkin', $yesterday)
                        ->orWhere(function ($q2) use ($yesterday) {
                            $q2->where('status', 'check out')
                                ->whereDate('tanggal_checkout', $yesterday);
                        });
                });
            } elseif ($request->date_filter === 'all_dates') {
                $query->where(function ($q) use ($today) {
                    $q->where(function ($q1) use ($today) {
                        $q1->where('status', '!=', 'check out')
                            ->whereRaw('DATE(tanggal_checkin) >= DATE(?)', [$today]);
                    })->orWhere(function ($q2) use ($today) {
                        $q2->where('status', 'check out')
                            ->whereRaw('DATE(tanggal_checkout) >= DATE(?)', [$today]);
                    });
                });
            }
        }
    
        // Apply pagination
        $reservasiHotels = $query->paginate(10);
    
        return view('kasir.reservasi_hotel.index', compact('reservasiHotels'));
    }
    


    // public function getDetails($id)
    // {
    //     $details = RincianReservasiHotel::with(['dataHewan', 'room'])
    //         ->where('reservasi_hotel_id', $id)
    //         ->get();

    //     return view('kasir.reservasi-hotel.details', compact('details'));
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    // public function updateStatus(Request $request, $id)
    // {
    //     // Validasi input checkbox
    //     $request->validate([
    //         'rincian_ids' => 'array',
    //         'rincian_ids.*' => 'exists:rincian_reservasi_hotel,id', // Validasi menggunakan ID Rincian
    //     ]);

    //     // Update status rincian menjadi "sudah diambil"
    //     RincianReservasiHotel::whereIn('id', $request->rincian_ids)
    //         ->update(['status' => 'sudah di ambil']);

    //     // Periksa apakah semua rincian dalam reservasi ini sudah diambil
    //     $allTaken = RincianReservasiHotel::where('reservasi_hotel_id', $id)
    //         ->where('status', '!=', 'sudah di ambil')
    //         ->doesntExist();

    //     if ($allTaken) {
    //         // Update status reservasi menjadi "done" jika semua rincian sudah diambil
    //         ReservasiHotel::where('id', $id)->update(['status' => 'done']);
    //     }

    //     return redirect()->route('reservasi-hotel.index', $id)
    //         ->with('success', 'Status berhasil diperbarui');
    // }

    public function updateStatusReservasi(Request $request, $reservasiHotelId) {
        // Ambil data reservasiHotel
        $reservasiHotel = ReservasiHotel::with(['dataPemilik.user', 'rincianReservasiHotel.dataHewan'])->findOrFail($reservasiHotelId);
        
        // Ambil rincian yang dipilih oleh user
        $rincianIds = $request->input('rincian_ids', []);
        
        if (!empty($rincianIds)) {
            // Update status rincian_reservasi_hotel yang dipilih menjadi 'sudah di ambil'
            RincianReservasiHotel::whereIn('id', $rincianIds)
                ->update(['status' => 'sudah di ambil']);
            
            // Mengubah status kamar terkait menjadi 'tersedia'
            foreach ($rincianIds as $rincianId) {
                $rincian = RincianReservasiHotel::findOrFail($rincianId);
                $rincian->room->status = 'tersedia';
                $rincian->room->save();
            }
        }
        
        // Hitung jumlah total rincian untuk reservasi ini
        $totalRincian = RincianReservasiHotel::where('reservasi_hotel_id', $reservasiHotelId)->count();
        
        // Hitung jumlah rincian yang sudah diambil
        $totalSudahDiambil = RincianReservasiHotel::where('reservasi_hotel_id', $reservasiHotelId)
            ->where('status', 'sudah di ambil')
            ->count();
        
        // Jika semua rincian sudah diambil, ubah status reservasi menjadi 'done'
        if ($totalRincian == $totalSudahDiambil) {
            $reservasiHotel->update(['status' => 'done']);
        }
    
        // Kirim pesan ke pemilik jika ada data pemilik
        if ($reservasiHotel->dataPemilik && $reservasiHotel->dataPemilik->user) {
            $user = $reservasiHotel->dataPemilik->user;
            $phone = $user->phone;
            
            $namaHewan = $reservasiHotel->rincianReservasiHotel->pluck('dataHewan.nama_hewan')->implode(', ');
            
            $message = "Halo {$user->name},\n" .
                "Mohon maaf mengganggu waktunya. Kami dari Fur Heaven Pet Hotel ingin menginformasikan detail reservasi Anda:\n\n" .
                "📌 ID Reservasi: {$reservasiHotel->id}\n" .
                "📅 Tanggal Check-in: {$reservasiHotel->tanggal_checkin}\n" .
                "📅 Tanggal Check-out: {$reservasiHotel->tanggal_checkout}\n" .
                "🐾 Nama Hewan: {$namaHewan}\n\n" .
                "Sudah di ambil dan reservasi *selesai*. Terima kasih telah mempercayakan perawatan hewan kesayangan Anda kepada kami!";
    
            // Kirim pesan WhatsApp
            $apiToken = 'API-TOKEN-lJ1MjuP1b1yeA5UMk7aVQNtpBycMzrjaQeTwmKQx2Geab05B1QACYo';
            $gatewayNumber = '6288222087560';
    
            Http::withToken($apiToken)->post('https://app.japati.id/api/send-message', [
                'gateway' => $gatewayNumber,
                'number' => $phone,
                'type' => 'text',
                'message' => $message,
            ]);
        }
        
        // Ambil status dan date_filter dari request
        $status = $request->input('status');
        $dateFilter = $request->input('date_filter');
        
        // Redirect kembali ke halaman dengan mempertahankan filter yang ada
        return redirect()->route('kasir-reservasi-hotel.index', [
            'status' => $status,
            'date_filter' => $dateFilter
        ])->with('success', 'Status berhasil diperbarui dan notifikasi telah dikirim.');
    }
    
 
    public function bulkCancel(Request $request)
    {
        // Validasi input
        $request->validate([
            'selected_reservasi' => 'required|array',
            'selected_reservasi.*' => 'exists:reservasi_hotel,id',
        ]);
    
        // Ambil reservasi yang dipilih
        $reservasiList = ReservasiHotel::whereIn('id', $request->selected_reservasi)->get();
    
        foreach ($reservasiList as $reservasi) {
            // Ambil user terkait dengan reservasi
            $user = $reservasi->dataPemilik->user;
    
            if ($user && $user->role === 'user') {
                $message = "Halo {$user->name}, mohon maaf mengganggu waktunya. Kami dari Fur Heaven Pet Hotel memberitahukan:\n" .
                           "ID Reservasi: {$reservasi->id}\n" .
                           "Dibatalkan karena {$user->name} tidak melakukan pembayaran dengan lewat tenggat sehari yang sudah kami tentukan.";
    
                // Kirim pesan ke WhatsApp
                $apiToken = 'API-TOKEN-lJ1MjuP1b1yeA5UMk7aVQNtpBycMzrjaQeTwmKQx2Geab05B1QACYo';
                $gatewayNumber = '6288222087560';
                
                Http::withToken($apiToken)->post('https://app.japati.id/api/send-message', [
                    'gateway' => $gatewayNumber,
                    'number' => $user->phone,
                    'type' => 'text',
                    'message' => $message,
                ]);
            }
        }
    
        // Pembaruan status reservasi yang dipilih
        ReservasiHotel::whereIn('id', $request->selected_reservasi)
            ->update(['status' => 'cancel']);
    
        // Kembali dengan pesan sukses dan mempertahankan filter
        return redirect()->route('kasir-reservasi-hotel.index', [
            'status' => $request->status,
            'date_filter' => $request->date_filter,
        ])->with('success', 'Reservasi yang dipilih telah dibatalkan.');
    }
    


    public function checkin($id, Request $request)
    {
        $reservasi = ReservasiHotel::findOrFail($id);
    
        if ($reservasi->status == 'di bayar') {
            $reservasi->status = 'check in';
            $reservasi->save();
    
            foreach ($reservasi->rincianReservasiHotel as $rincian) {
                $rincian->room->status = 'tidak tersedia';
                $rincian->room->save();
            }
    
            $user = $reservasi->dataPemilik->user;
            $phone = $user->phone;
            $message = "Halo $user->name, mohon maaf mengganggu waktunya. Kami dari Fur Heaven Pet Hotel memberitahukan:\n" .
                       "ID RESERVASI: $reservasi->id\n" .
                       "Sedang melakukan check-in pada $reservasi->tanggal_checkin sampai $reservasi->tanggal_checkout";
    
            $perawatUsers = User::where('role', 'perawat')->get();
            foreach ($perawatUsers as $perawat) {
                $messagePerawat = "Halo $perawat->name, ada tugas baru nih yang harus dibuat laporan dengan:\n" .
                                  "Nama Pelanggan: $user->name\n" .
                                  "ID RESERVASI: $reservasi->id\n" .
                                  "HARUS DIBUAT LAPORAN DARI $reservasi->tanggal_checkin SAMPAI $reservasi->tanggal_checkout";
                Http::withToken('API-TOKEN-lJ1MjuP1b1yeA5UMk7aVQNtpBycMzrjaQeTwmKQx2Geab05B1QACYo')
                    ->post('https://app.japati.id/api/send-message', [
                        'gateway' => '6288222087560',
                        'number' => $perawat->phone,
                        'type' => 'text',
                        'message' => $messagePerawat,
                    ]);
            }
    
            Http::withToken('API-TOKEN-lJ1MjuP1b1yeA5UMk7aVQNtpBycMzrjaQeTwmKQx2Geab05B1QACYo')
                ->post('https://app.japati.id/api/send-message', [
                    'gateway' => '6288222087560',
                    'number' => $phone,
                    'type' => 'text',
                    'message' => $message,
                ]);
    
            return redirect()->route('kasir-reservasi-hotel.index', [
                'status' => $request->input('status'),
                'date_filter' => $request->input('date_filter'),
            ])->with('success', 'Reservasi telah diubah menjadi Check-In dan status kamar diperbarui menjadi Tidak Tersedia');
        }
    
        return redirect()->route('kasir-reservasi-hotel.index', [
            'status' => $request->input('status'),
            'date_filter' => $request->input('date_filter'),
        ])->with('error', 'Status reservasi tidak sesuai untuk Check-In');
    }
    
    



    public function riwayatReservasi()
    {
        // Ambil data reservasi yang statusnya 'done' beserta relasi terkait dengan pagination
        $reservasi = ReservasiHotel::with(['dataPemilik', 'rincianReservasiHotel', 'laporanHewan', 'transaksi'])
            ->where('status', 'done') // Filter status reservasi yang 'done'
            ->orderBy('tanggal_checkin', 'desc')
            ->paginate(10); // Pagination 10 per halaman
    
        return view('kasir.reservasi_hotel.riwayat', compact('reservasi'));
    }
    

    public function detailRiwayat($id)
    {
        $reservasi = ReservasiHotel::with([
            'dataPemilik',
            'rincianReservasiHotel.room',
            'rincianReservasiHotel.dataHewan',
            'transaksi'
        ])->findOrFail($id);

        return view('kasir.reservasi_hotel.detail-riwayat', compact('reservasi'));
    }




    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        // Gunakan findOrFail untuk mengambil satu objek berdasarkan ID
        $reservasiHotel = ReservasiHotel::with([
            'rincianReservasiHotel.dataHewan',
            'rincianReservasiHotel.room',
            'rincianReservasiHotel.dataPemilik'
        ])->findOrFail($id); // Mengambil satu objek berdasarkan ID

        // Ambil parameter filter status dan tanggal dari request
        $status = $request->query('status', ''); // Default kosong jika tidak ada
        $dateFilter = $request->query('date_filter', ''); // Default kosong jika tidak ada

        // Kirim objek yang diambil dan filter ke view
        return view('kasir.reservasi_hotel.rincian', compact('reservasiHotel', 'status', 'dateFilter'));
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
    public function destroy(Request $request, string $id)
    {
        $reservasiHotel = ReservasiHotel::findOrFail($id);

        // Hapus rincian dan reservasi hotel
        $reservasiHotel->rincianReservasiHotel()->delete();
        $reservasiHotel->delete();

        // Ambil status dan date_filter dari request
        $status = $request->input('status');
        $dateFilter = $request->input('date_filter');

        // Redirect ke halaman daftar reservasi dengan parameter filter yang sama
        return redirect()->route('kasir-reservasi-hotel.index', [
            'status' => $status,
            'date_filter' => $dateFilter,
        ])->with('success', 'Reservasi hotel berhasil dihapus.');
    }
}
