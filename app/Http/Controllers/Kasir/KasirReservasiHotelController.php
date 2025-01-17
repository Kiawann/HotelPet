<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\ReservasiHotel;
use App\Models\RincianReservasiHotel;
use App\Models\Room;
use Illuminate\Http\Request;

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

            // If status is checkout, use tanggal_checkout for date filtering
            if ($request->status === 'check out' && $request->filled('date_filter')) {
                if ($request->date_filter === 'today') {
                    $query->whereDate('tanggal_checkout', $today);
                } elseif ($request->date_filter === 'yesterday') {
                    $query->whereDate('tanggal_checkout', now()->subDay()->format('Y-m-d'));
                } elseif ($request->date_filter === 'all_dates') {
                    $query->whereRaw('tanggal_checkout >= ?', [$today]);
                }
            }
            // For check in status
            elseif ($request->status === 'check in' && $request->filled('date_filter')) {
                if ($request->date_filter === 'today') {
                    $query->whereDate('tanggal_checkin', now()->format('Y-m-d'));
                } elseif ($request->date_filter === 'yesterday') {
                    $query->whereDate('tanggal_checkin', now()->subDay()->format('Y-m-d'));
                } elseif ($request->date_filter === 'all_dates') {
                    $query->whereRaw('DATE(tanggal_checkin) >= DATE(?)', [$today]);
                }
            }
            // For other statuses
            elseif ($request->filled('date_filter')) {
                if ($request->date_filter === 'today') {
                    $query->whereDate('tanggal_checkin', now()->format('Y-m-d'));
                } elseif ($request->date_filter === 'yesterday') {
                    $query->whereDate('tanggal_checkin', now()->subDay()->format('Y-m-d'));
                } elseif ($request->date_filter === 'all_dates') {
                    $query->whereRaw('DATE(tanggal_checkin) >= DATE(?)', [$today]);
                }
            }
        }
        // If no status is selected but date filter is applied
        elseif ($request->filled('date_filter')) {
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

        // Get the filtered results
        $reservasiHotels = $query->get();

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

    public function updateStatus(Request $request, $id)
    {
        // Validasi input checkbox
        $request->validate([
            'rincian_ids' => 'array',
            'rincian_ids.*' => 'exists:rincian_reservasi_hotel,id', // Validasi menggunakan ID Rincian
        ]);

        // Update status rincian menjadi "sudah diambil"
        RincianReservasiHotel::whereIn('id', $request->rincian_ids)
            ->update(['status' => 'sudah di ambil']);

        // Periksa apakah semua rincian dalam reservasi ini sudah diambil
        $allTaken = RincianReservasiHotel::where('reservasi_hotel_id', $id)
            ->where('status', '!=', 'sudah di ambil')
            ->doesntExist();

        if ($allTaken) {
            // Update status reservasi menjadi "done" jika semua rincian sudah diambil
            ReservasiHotel::where('id', $id)->update(['status' => 'done']);
        }

        return redirect()->route('reservasi-hotel.show', $id)
            ->with('success', 'Status berhasil diperbarui');
    }


    public function bulkCancel(Request $request)
    {
        // Validasi input
        $request->validate([
            'selected_reservasi' => 'required|array',
            'selected_reservasi.*' => 'exists:reservasi_hotel,id',
        ]);

        // Pembaruan status reservasi yang dipilih
        ReservasiHotel::whereIn('id', $request->selected_reservasi)
            ->update(['status' => 'cancel']);

        // Kembali dengan pesan sukses dan mempertahankan filter
        return redirect()->route('kasir-reservasi-hotel.index', [
            'status' => $request->status,
            'date_filter' => $request->date_filter,
        ])->with('success', 'Reservasi yang dipilih telah dicancel.');
    }


    public function checkin($id, Request $request)
    {
        $reservasi = ReservasiHotel::findOrFail($id);

        // Pastikan bahwa status adalah 'di bayar' sebelum diubah
        if ($reservasi->status == 'di bayar') {
            $reservasi->status = 'check in';
            $reservasi->save();

            return redirect()->route('kasir-reservasi-hotel.index', [
                'status' => $request->input('status'),
                'date_filter' => $request->input('date_filter'),
            ])->with('success', 'Reservasi telah diubah menjadi Check-In');
        }

        return redirect()->route('kasir-reservasi-hotel.index', [
            'status' => $request->input('status'),
            'date_filter' => $request->input('date_filter'),
        ])->with('error', 'Status reservasi tidak sesuai untuk Check-In');
    }



    public function riwayatReservasi()
    {
        // Ambil data reservasi beserta relasi terkait
        $reservasi = ReservasiHotel::with(['dataPemilik', 'rincianReservasiHotel', 'laporanHewan', 'transaksi'])
            ->orderBy('tanggal_checkin', 'desc')
            ->get();

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
