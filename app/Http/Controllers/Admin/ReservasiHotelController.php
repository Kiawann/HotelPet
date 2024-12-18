<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryHotel;
use App\Models\DataPemilik;
use App\Models\DataHewan;
use App\Models\Room;
use App\Models\ReservasiHotel;
use App\Models\RincianReservasiHotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ReservasiHotelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Gunakan relasi yang benar sesuai dengan nama yang didefinisikan di model
        $reservasiHotels = ReservasiHotel::with(['rincianReservasiHotel.dataHewan', 'rincianReservasiHotel.room'])->get();

        return view('admin.reservasi_hotel.index', compact('reservasiHotels'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil semua data pemilik dan data hewan
        $dataPemilik = DataPemilik::all();
        $dataHewan = DataHewan::all();

        // Ambil data room beserta kategori hotel (category_hotel)
        $rooms = Room::with('category_hotel')->get();

        // Mengembalikan view dengan data yang dibutuhkan
        return view('admin.reservasi_hotel.create', compact('dataPemilik', 'dataHewan', 'rooms'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'data_pemilik_id' => 'nullable|exists:data_pemilik,id',
            'id_data_hewan' => 'required|array|min:1', // Minimal 1 hewan harus dipilih
            'id_data_hewan.*' => 'exists:data_hewan,id',
            'id_room' => 'required|array|min:1', // Minimal 1 room harus dipilih
            'id_room.*' => 'exists:room,id',
            'tanggal_checkin' => 'sometimes|array',
            'tanggal_checkin.*' => 'nullable|date',
            'tanggal_checkout' => 'required|array',
            'tanggal_checkout.*' => 'date|after_or_equal:tanggal_checkin.*',
            'status' => 'nullable|in:check in,di pesan,done',
        ]);

        // Proses penyimpanan ke tabel 'reservasi_hotel'
        $reservasiHotel = ReservasiHotel::create([
            'data_pemilik_id' => $request->data_pemilik_id,
            'status' => $request->status ?? 'check in', // Default status "check in"
            'Total' => 0, // Set default total awal
        ]);

        // Menyimpan data ke tabel 'rincian_reservasi_hotel'
        $dataHewan = $request->id_data_hewan;
        $rooms = $request->id_room;
        $tanggalCheckin = $request->tanggal_checkin;
        $tanggalCheckout = $request->tanggal_checkout;

        $currentDate = now(); // Tanggal saat ini
        $totalSubtotal = 0; // Variabel untuk menghitung total biaya

        foreach ($dataHewan as $index => $hewanId) {
            // Pastikan room dan tanggal_checkout sesuai indeks
            if (isset($rooms[$index], $tanggalCheckout[$index])) {
                $roomId = $rooms[$index];
                $checkin = $tanggalCheckin[$index] ?? $currentDate->toDateString(); // Gunakan tanggal sekarang jika tanggal check-in tidak diberikan
                $checkout = $tanggalCheckout[$index];

                // Mengambil data room dan menghitung subtotal
                $room = Room::find($roomId);
                $subtotal = 0;

                if ($room) {
                    $checkinDateTime = new \Carbon\Carbon($checkin);
                    $checkoutDateTime = new \Carbon\Carbon($checkout);

                    // Hitung jumlah hari menginap
                    $days = $checkoutDateTime->diffInDays($checkinDateTime);

                    // Jika check-in dan check-out pada hari yang sama, dianggap 1 malam
                    if ($days == 0) {
                        $days = 1;
                    }

                    $subtotal = $days * $room->category_hotel->harga; // Harga dasar tanpa denda

                    // Ubah status room menjadi "Tidak Tersedia"
                    $room->update(['status' => 'Tidak Tersedia']);
                }

                // Menyimpan data rincian reservasi
                RincianReservasiHotel::create([
                    'reservasi_hotel_id' => $reservasiHotel->id,
                    'data_hewan_id' => $hewanId,
                    'room_id' => $roomId,
                    'tanggal_checkin' => $checkin,
                    'tanggal_checkout' => $checkout,
                    'SubTotal' => $subtotal,
                ]);

                // Tambahkan subtotal ke total
                $totalSubtotal += $subtotal;
            }
        }

        // Update total di tabel reservasi_hotel
        $reservasiHotel->update(['Total' => $totalSubtotal]);

        // Redirect dengan pesan sukses
        return redirect()->route('reservasi_hotel.index')->with('success', "Reservasi hotel berhasil dibuat dengan total biaya Rp" . number_format($totalSubtotal, 0, ',', '.'));
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Gunakan findOrFail untuk mengambil satu objek berdasarkan ID
        $reservasiHotel = ReservasiHotel::with([
            'rincianReservasiHotel.dataHewan',
            'rincianReservasiHotel.room',
            'rincianReservasiHotel.dataPemilik'
        ])->findOrFail($id); // Mengambil satu objek berdasarkan ID

        // Kirim objek yang diambil ke view
        return view('admin.reservasi_hotel.rincian', compact('reservasiHotel'));
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
        $reservasiHotel = ReservasiHotel::findOrFail($id);
        $reservasiHotel->rincianReservasiHotel()->delete();
        $reservasiHotel->delete();

        return redirect()->route('reservasi_hotel.index')->with('success', 'Reservasi hotel berhasil dihapus.');
    }
}
