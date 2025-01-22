<?php

namespace App\Http\Controllers\Perawat;

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
    public function index()
{
    // Mengambil data laporan hewan dengan relasi dan mengurutkan berdasarkan created_at secara menurun
    $laporanHewan = LaporanHewan::with(['reservasiHotel', 'dataHewan', 'room'])
                                ->orderBy('created_at', 'desc')  // Mengurutkan berdasarkan waktu pembuatan, terbaru di atas
                                ->get();

    // Mengirim data ke tampilan (view)
    return view('perawat.laporan_hewan.index', compact('laporanHewan'));
}



public function laporan($reservasiId)
{
    // Mengambil data laporan hewan yang sesuai dengan reservasi_id dan mengurutkannya berdasarkan tanggal laporan secara menurun
    $laporanHewan = LaporanHewan::with(['reservasiHotel', 'dataHewan', 'room']) // Eager load untuk relasi dengan dataHewan dan room
        ->where('reservasi_hotel_id', $reservasiId)
        ->orderBy('created_at', 'desc') // Mengurutkan berdasarkan tanggal laporan terbaru di atas
        ->get();

         // Preserve query parameters
    $status = request()->query('status');
    $dateFilter = request()->query('date_filter');

    // Menampilkan halaman dengan daftar laporan hewan
    return view('perawat.laporan_hewan.laporan-hewan', compact('laporanHewan', 'reservasiId', 'status', 'dateFilter'));
}

    /**
     * Show the form for creating a new resource.
     */
    // LaporanHewanController.php
   
// LaporanHewanController.php

public function create(Request $request)
{
    $reservasiId = $request->query('reservasi_id');

    if (!$reservasiId) {
        return redirect()->route('reservasi_hotel.index')->with('error', 'Reservasi ID tidak ditemukan.');
    }

    $reservasiHotel = ReservasiHotel::findOrFail($reservasiId);
    $today = now()->toDateString();

    $dataHewans = $reservasiHotel->rincianReservasiHotel->filter(function ($rincian) use ($today, $reservasiId) {
        $existingReport = $rincian->dataHewan->laporanHewan->where('reservasi_hotel_id', $reservasiId)
            ->first(function ($laporan) use ($today) {
                $laporanDate = \Carbon\Carbon::parse($laporan->tanggal_laporan)->toDateString();
                return $laporanDate == $today;
            });
        return !$existingReport;
    })->map(function ($rincian) use ($reservasiHotel) {
        $roomId = $rincian->room_id;
        $room = $reservasiHotel->rooms->firstWhere('id', $roomId);
        $rincian->room_name = $room ? $room->nama_ruangan : 'Tidak ada ruangan';
        return $rincian;
    });

    // Pass filter parameters to view
    return view('perawat.laporan_hewan.create', [
        'dataHewans' => $dataHewans,
        'reservasiId' => $reservasiId,
        'filter_status' => $request->query('status'),
        'filter_date' => $request->query('date_filter')
    ]);
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
        'foto' => 'nullable|array',
        'foto.*' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        'video' => 'nullable|array',
        'video.*' => 'nullable|mimes:mp4,mov,avi,wmv|max:10240',
        // Tambahkan validasi untuk filter
        'filter_status' => 'nullable|string',
        'filter_date' => 'nullable|string',
    ]);

    if ($request->hasFile('foto')) {
        $fotoPaths = [];
        foreach ($request->file('foto') as $foto) {
            $fotoPaths[] = $foto->store('laporan_hewan/foto');
        }
        $validatedData['foto'] = json_encode($fotoPaths);
    }

    if ($request->hasFile('video')) {
        $videoPaths = [];
        foreach ($request->file('video') as $video) {
            $videoPaths[] = $video->store('laporan_hewan/video');
        }
        $validatedData['video'] = json_encode($videoPaths);
    }

    LaporanHewan::create([
        'reservasi_hotel_id' => $validatedData['reservasi_hotel_id'],
        'data_hewan_id' => $validatedData['data_hewan_id'],
        'room_id' => $validatedData['room_id'],
        'Makan' => $validatedData['Makan'],
        'Minum' => $validatedData['Minum'],
        'Bab' => $validatedData['Bab'],
        'Bak' => $validatedData['Bak'],
        'keterangan' => $validatedData['keterangan'],
        'tanggal_laporan' => $validatedData['tanggal_laporan'],
        'foto' => $validatedData['foto'] ?? null,
        'video' => $validatedData['video'] ?? null,
    ]);

    // Redirect dengan filter parameters
    return redirect()->route('perawat-reservasi-hotel.index', [
        'status' => $request->input('filter_status'),
        'date_filter' => $request->input('filter_date')
    ])->with('success', 'Laporan hewan berhasil ditambahkan.');
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
