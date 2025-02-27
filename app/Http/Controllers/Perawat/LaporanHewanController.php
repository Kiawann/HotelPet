<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use App\Models\DataHewan;
use App\Models\LaporanHewan;
use App\Models\ReservasiHotel;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class LaporanHewanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil data laporan hewan dengan relasi dan pagination
        $laporanHewan = LaporanHewan::with(['reservasiHotel', 'dataHewan', 'room'])
                                    ->orderBy('created_at', 'desc')  // Urutkan dari terbaru ke terlama
                                    ->paginate(10); // Pagination 10 data per halaman
    
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

        // Simpan laporan
        $laporan = LaporanHewan::create($validatedData);

        // Redirect ke controller WhatsApp
        return redirect()->route('laporan-sendWa', ['id' => $laporan->id]);
    }
    

   

    public function sendWhatsAppMessage($id)
    {
        $laporan = LaporanHewan::findOrFail($id);
    
        $phone = $laporan->reservasiHotel->dataPemilik->user->phone ?? null;
        if (!$phone) {
            return redirect()->route('perawat-reservasi-hotel.index')
                ->with('error', 'Nomor telepon pemilik tidak ditemukan.');
        }
    
        // Format nomor telepon agar sesuai dengan format internasional (WhatsApp)
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }
    
        // Gunakan created_at untuk menentukan waktu laporan
        $waktuLaporan = Carbon::parse($laporan->created_at)->setTimezone('Asia/Jakarta');
        $jamLaporan = $waktuLaporan->format('H');
    
        // Debug untuk memeriksa jam yang diambil
        // dd($waktuLaporan, $jamLaporan);
    
        if ($jamLaporan >= 7 && $jamLaporan < 10) {
            $sapaan = "🌞 *Selamat pagi*";
        } elseif ($jamLaporan >= 10 && $jamLaporan < 15) {
            $sapaan = "🌤 *Selamat siang*";
        } elseif ($jamLaporan >= 15 && $jamLaporan < 18) {
            $sapaan = "🌅 *Selamat sore*";
        } else {
            $sapaan = "🌙 *Selamat malam*";
        }
    
        $message = "{$sapaan}, Bapak/Ibu {$laporan->dataHewan->pemilik->nama}!\n\n".
            "Kami dari *Pet Hotel* ingin menginformasikan kondisi terbaru hewan peliharaan kesayangan Anda. Berikut laporan hari ini:\n\n".
            "*Laporan Harian Hewan Peliharaan*\n\n".
            "🐾 *Reservasi ID:* {$laporan->reservasi_hotel_id}\n".
            "🐶 *Nama Hewan:* {$laporan->dataHewan->nama_hewan}\n".
            "🏠 *Room:* {$laporan->room->nama_ruangan}\n".
            "🍖 *Makan:* {$laporan->Makan}\n".
            "🥤 *Minum:* {$laporan->Minum}\n".
            "💩 *BAB:* {$laporan->Bab}\n".
            "🚽 *BAK:* {$laporan->Bak}\n".
            "📝 *Keterangan:* {$laporan->keterangan}\n".
            "📅 *Tanggal Laporan:* {$waktuLaporan->format('d-m-Y H:i')}\n\n".
            "Terima kasih telah mempercayakan hewan kesayangan Anda kepada kami. Jika ada pertanyaan, silakan hubungi kami. 🙏🐾";
    
        // Kirim pesan ke WhatsApp
        $apiToken = 'API-TOKEN-lJ1MjuP1b1yeA5UMk7aVQNtpBycMzrjaQeTwmKQx2Geab05B1QACYo';
        $gatewayNumber = '6288222087560';
    
        $response = Http::withToken($apiToken)->post('https://app.japati.id/api/send-message', [
            'gateway' => $gatewayNumber,
            'number' => $phone,
            'type' => 'text',
            'message' => $message,
        ]);
    
        return redirect()->route('perawat-reservasi-hotel.index')
            ->with('success', 'Laporan telah dibuat dan pesan WhatsApp telah dikirim.');
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
