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

    // Menampilkan halaman dengan daftar laporan hewan
    return view('perawat.laporan_hewan.laporan-hewan', compact('laporanHewan', 'reservasiId'));
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

    // Mendapatkan tanggal hari ini
    $today = now()->toDateString();

    // Mendapatkan data hewan yang belum memiliki laporan hari ini dalam ID reservasi ini
    $dataHewans = $reservasiHotel->rincianReservasiHotel->filter(function ($rincian) use ($today, $reservasiId) {
        // Memeriksa apakah laporan hari ini sudah ada untuk ID reservasi ini
        $existingReport = $rincian->dataHewan->laporanHewan->where('reservasi_hotel_id', $reservasiId)
            ->first(function ($laporan) use ($today) {
                // Pastikan tanggal laporan sesuai
                $laporanDate = \Carbon\Carbon::parse($laporan->tanggal_laporan)->toDateString();
                return $laporanDate == $today;
            });

        // Jika laporan tidak ada untuk ID reservasi ini pada hari ini, hewan bisa dilaporkan
        return !$existingReport;
    })->map(function ($rincian) use ($reservasiHotel) {
        // Mengambil room_id yang sesuai dengan rincian reservasi
        $roomId = $rincian->room_id; // Ambil room_id dari rincian reservasi
        
        // Cari room yang sesuai dengan room_id dari rincian reservasi dalam reservasi_hotel
        $room = $reservasiHotel->rooms->firstWhere('id', $roomId);
        
        // Pastikan mengambil nama ruangan yang sesuai dengan room_id
        $rincian->room_name = $room ? $room->nama_ruangan : 'Tidak ada ruangan'; // Ambil nama ruangan jika ada
        return $rincian;
    });

    // Mengirimkan data ke view
    return view('perawat.laporan_hewan.create', compact('dataHewans', 'reservasiId'));
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    //dd($request->all());
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
        'foto' => 'nullable|array', // Mengizinkan array untuk foto
        'foto.*' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048', // Validasi tiap file gambar
        'video' => 'nullable|array', // Mengizinkan array untuk video
        'video.*' => 'nullable|mimes:mp4,mov,avi,wmv|max:10240', // Validasi tiap file video
    ]);

    // Proses upload foto jika ada
    if ($request->hasFile('foto')) {
        $fotoPaths = [];
        foreach ($request->file('foto') as $foto) {
            $fotoPaths[] = $foto->store('laporan_hewan/foto'); // Menyimpan file foto
        }
        $validatedData['foto'] = json_encode($fotoPaths); // Menyimpan daftar path foto sebagai JSON
    }

    // Proses upload video jika ada
    if ($request->hasFile('video')) {
        $videoPaths = [];
        foreach ($request->file('video') as $video) {
            $videoPaths[] = $video->store('laporan_hewan/video'); // Menyimpan file video
        }
        $validatedData['video'] = json_encode($videoPaths); // Menyimpan daftar path video sebagai JSON
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
