<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataHewan;
use App\Models\LaporanHewan;
use App\Models\ReservasiHotel;
use App\Models\Room;
use Illuminate\Http\Request;

class LaporanHewanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $laporanHewan = LaporanHewan::with(['reservasiHotel', 'dataHewan', 'room'])->get();
        return view('admin.laporan_hewan.index', compact('laporanHewan'));
    }

    public function laporan($reservasiId)
    {
        // Mengambil data laporan hewan yang sesuai dengan reservasi_id
        $laporanHewan = LaporanHewan::with(['reservasiHotel', 'dataHewan', 'room']) // Eager load untuk relasi dengan dataHewan dan room
            ->where('reservasi_hotel_id', $reservasiId)
            ->get();

        // Menampilkan halaman dengan daftar laporan hewan
        return view('admin.laporan_hewan.laporan-hewan', compact('laporanHewan', 'reservasiId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    // LaporanHewanController.php

// LaporanHewanController.php

public function create(Request $request)
{
    // Mengambil 'reservasi_id' dari query parameter atau route parameter
    $reservasiId = $request->query('reservasi_id'); 

    // Pastikan bahwa 'reservasi_id' ada
    if (!$reservasiId) {
        return redirect()->route('reservasi_hotel.index')->with('error', 'Reservasi ID tidak ditemukan.');
    }

    // Mendapatkan data ReservasiHotel berdasarkan ID
    $reservasiHotel = ReservasiHotel::findOrFail($reservasiId);

    // Mendapatkan room dan data hewan terkait dengan reservasi ini melalui rincian_reservasi_hotel
    $rooms = $reservasiHotel->rooms;  // Mengambil room yang terkait dengan reservasi
    $dataHewans = $reservasiHotel->dataHewans;  // Mengambil data hewan yang terkait dengan reservasi

    // Mengirimkan data ke view
    return view('admin.laporan_hewan.create', compact('rooms', 'dataHewans', 'reservasiId'));
}



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validatedData = $request->validate([
        'reservasi_hotel_id' => 'required|exists:reservasi_hotel,id',
        'data_hewan_id' => 'required|exists:data_hewan,id',
        'room_id' => 'required|exists:room,id',
        'Makan' => 'required|string|max:255',
        'Minum' => 'required|string|max:255',
        'Bab' => 'required|string|max:255',
        'Bak' => 'required|string|max:255',
        'keterangan' => 'nullable|string',
        'tanggal_laporan' => 'required|date',
        'foto' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048', // Validasi foto
    ]);

    // Proses upload foto jika ada
    if ($request->hasFile('foto')) {
        $validatedData['foto'] = $request->file('foto')->store('laporan_hewan'); // Menyimpan di storage
    }

    // Simpan data ke database
    LaporanHewan::create($validatedData);

    return redirect()->route('laporan_hewan.laporan', ['reservasiId' => $validatedData['reservasi_hotel_id']])
        ->with('success', 'Laporan hewan berhasil ditambahkan.');
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
