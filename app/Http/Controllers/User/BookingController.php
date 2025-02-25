<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataPemilik;
use App\Models\DataHewan;
use App\Models\Room;
use App\Models\ReservasiHotel;
use App\Models\RincianReservasiHotel;
use App\Models\LaporanReservasiHotel;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class BookingController extends Controller
{
    // public function index()
    // {
    //     $reservasiHotels = ReservasiHotel::with(['rincianReservasiHotel.dataHewan', 'rincianReservasiHotel.room'])
    //         ->whereHas('rincianReservasiHotel.dataHewan.pemilik', function ($query) {
    //             $query->where('user_id', auth()->id()); // Filter berdasarkan user yang login
    //         })
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     return view('user.transaksi.riwayat', compact('reservasiHotels'));
    // }
    public function index()
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login'); // Redirect to login if not authenticated
        }

        $userId = Auth::id(); // Use Auth facade to get the authenticated user's ID

        $reservasiHotels = ReservasiHotel::with(['rincianReservasiHotel.dataHewan', 'rincianReservasiHotel.room'])
            ->whereHas('rincianReservasiHotel.dataHewan.pemilik', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.transaksi.riwayat', compact('reservasiHotels'));
    }

    public function create()
    {
        $user = Auth::user();
        $dataPemilik = DataPemilik::where('user_id', $user->id)->first();
        $dataHewan = DataHewan::where('data_pemilik_id', $dataPemilik->id)->get();

        // Ambil semua kamar, tidak hanya yang statusnya 'Tersedia'
        $rooms = Room::with('category_hotel')->get();

        return view('user.transaksi.booking', compact('dataPemilik', 'dataHewan', 'rooms'));
    }


    public function store(Request $request)
    {
        // Basic validation
        $request->validate([
            'data_pemilik_id' => 'nullable|exists:data_pemilik,id',
            'data_hewan_id' => 'required|array|min:1',
            'data_hewan_id.*' => 'exists:data_hewan,id',
            'room_id' => 'required|array|min:1',
            'room_id.*' => 'exists:room,id',
            'tanggal_checkin' => 'required|date|after_or_equal:today',
            'tanggal_checkout' => 'required|date|after_or_equal:tanggal_checkin',
        ]);
    
        $tanggalCheckin = $request->tanggal_checkin;
        $tanggalCheckout = $request->tanggal_checkout;
    
        $successfulBookings = [];
        $bookedPets = []; // Array untuk menyimpan hewan yang sudah dipesan
        $roomErrors = []; // Array untuk menyimpan error kamar yang sudah dipesan
    
        foreach ($request->data_hewan_id as $index => $hewanId) {
            // Get the pet data
            $hewan = DataHewan::find($hewanId);
            $namaHewan = $hewan ? $hewan->nama_hewan : 'Hewan tidak ditemukan';
    
            // Check for existing reservations
            $existingReservations = RincianReservasiHotel::where('data_hewan_id', $hewanId)
                ->join('reservasi_hotel', 'rincian_reservasi_hotel.reservasi_hotel_id', '=', 'reservasi_hotel.id')
                ->where(function ($query) use ($tanggalCheckin, $tanggalCheckout) {
                    $query->where(function ($q) use ($tanggalCheckin, $tanggalCheckout) {
                        // Cek jika tanggal checkin atau checkout baru berada di dalam rentang reservasi lama
                        $q->where('reservasi_hotel.tanggal_checkin', '<', $tanggalCheckout)
                            ->where('reservasi_hotel.tanggal_checkout', '>', $tanggalCheckin);
                    });
                })
                ->whereIn('reservasi_hotel.status', ['di pesan', 'di bayar', 'check in'])
                ->first(); // Ambil detail reservasi untuk mendapatkan tanggal reservasi lama
    
            if ($existingReservations) {
                $bookedPets[] = [
                    'nama' => $namaHewan,
                    'tanggal_lama' => $existingReservations->tanggal_checkin,
                    'tanggal_lama_checkout' => $existingReservations->tanggal_checkout
                ];
                continue;
            }
    
            // Ambil data kamar
            $roomId = $request->room_id[$index];
            $room = Room::with('category_hotel')->find($roomId);
    
            if (!$room) {
                $roomErrors[] = "Kamar untuk $namaHewan tidak ditemukan.";
                continue;
            }
    
            $existingRoomReservations = RincianReservasiHotel::where('room_id', $roomId)
                ->join('reservasi_hotel', 'rincian_reservasi_hotel.reservasi_hotel_id', '=', 'reservasi_hotel.id')
                ->where(function ($query) use ($tanggalCheckin, $tanggalCheckout) {
                    $query->where(function ($q) use ($tanggalCheckin, $tanggalCheckout) {
                        $q->where('reservasi_hotel.tanggal_checkin', '<', $tanggalCheckout)
                            ->where('reservasi_hotel.tanggal_checkout', '>', $tanggalCheckin);
                    });
                })
                ->whereIn('reservasi_hotel.status', ['di pesan', 'di bayar', 'check in'])
                ->first(); // Ambil detail reservasi
    
            if ($existingRoomReservations) {
                $roomErrors[] = "Kamar {$room->nama_ruangan} sudah dipesan dari " .
                    date('d/m/Y', strtotime($existingRoomReservations->tanggal_checkin)) .
                    " sampai " .
                    date('d/m/Y', strtotime($existingRoomReservations->tanggal_checkout));
                continue;
            }
    
            // Hitung durasi menginap dan subtotal
            $checkinDate = new \Carbon\Carbon($tanggalCheckin);
            $checkoutDate = new \Carbon\Carbon($tanggalCheckout);
            $jumlahHari = max(1, $checkinDate->diffInDays($checkoutDate));
            $hargaPerMalam = (float) $room->category_hotel->harga;
            $subtotal = $jumlahHari * $hargaPerMalam;
    
            // Simpan data booking yang berhasil
            $successfulBookings[] = [
                'data_pemilik_id' => $request->data_pemilik_id,
                'data_hewan_id' => $hewanId,
                'room_id' => $roomId,
                'tanggal_checkin' => $tanggalCheckin,
                'tanggal_checkout' => $tanggalCheckout,
                'SubTotal' => $subtotal,
                'room' => $room,
            ];
        }
    
        // Jika terdapat hewan yang sudah dipesan, kembalikan error
        if (!empty($bookedPets)) {
            $errorMessages = [];
            foreach ($bookedPets as $petInfo) {
                $errorMessages[] = "Hewan {$petInfo['nama']} sudah reservasi dari " .
                    date('d/m/Y', strtotime($petInfo['tanggal_lama'])) .
                    " sampai " .
                    date('d/m/Y', strtotime($petInfo['tanggal_lama_checkout']));
            }
    
            return redirect()->back()
                ->withErrors($errorMessages)
                ->withInput();
        }
    
        if (!empty($roomErrors)) {
            return redirect()->back()->withErrors($roomErrors)->withInput();
        }
    
        // Jika semua booking berhasil, buat reservasi di database
        try {
            // Buat reservasi hotel
            $reservasiHotel = ReservasiHotel::create([
                'data_pemilik_id' => $request->data_pemilik_id,
                'status' => 'di pesan',
                'Total' => 0,
                'tanggal_checkin' => $tanggalCheckin,
                'tanggal_checkout' => $tanggalCheckout,
            ]);
    
            $totalKeseluruhan = 0;
    
            // Proses setiap booking yang berhasil
            foreach ($successfulBookings as $booking) {
                // Buat rincian reservasi
                RincianReservasiHotel::create([
                    'reservasi_hotel_id' => $reservasiHotel->id,
                    'data_hewan_id' => $booking['data_hewan_id'],
                    'room_id' => $booking['room_id'],
                    'tanggal_checkin' => $booking['tanggal_checkin'],
                    'tanggal_checkout' => $booking['tanggal_checkout'],
                    'SubTotal' => $booking['SubTotal'],
                    'data_pemilik_id' => $booking['data_pemilik_id'],
                ]);
    
                // Update total
                $totalKeseluruhan += $booking['SubTotal'];
    
                // Buat laporan reservasi
                LaporanReservasiHotel::create([
                    'room_id' => $booking['room_id'],
                    'tanggal_checkin' => $booking['tanggal_checkin'],
                    'tanggal_checkout' => $booking['tanggal_checkout'],
                ]);
            }
    
            // Update total di reservasi hotel
            $reservasiHotel->update(['Total' => $totalKeseluruhan]);
    
            // Kirim notifikasi WhatsApp ke kasir
            $kasirs = User::where('role', 'kasir')->get();
            $userName = auth()->user()->name; // Nama pengguna yang membuat reservasi
            $messageKasir = "$userName telah membuat reservasi baru di hotel hewan.";
            $apiToken = 'API-TOKEN-lJ1MjuP1b1yeA5UMk7aVQNtpBycMzrjaQeTwmKQx2Geab05B1QACYo';
            $gatewayNumber = '6288222087560';
    
            foreach ($kasirs as $kasir) {
                Http::withToken($apiToken)->post('https://app.japati.id/api/send-message', [
                    'gateway' => $gatewayNumber,
                    'number'  => $kasir->phone,
                    'type'    => 'text',
                    'message' => $messageKasir,
                ]);
            }
    
            // Kirim notifikasi WhatsApp ke user yang membuat reservasi
            $user = auth()->user();
            $messageUser = "Halo $user->name, reservasi hotel Anda berhasil dibuat dengan detail sebagai berikut:\n" .
                "Check-in: " . date('d/m/Y', strtotime($tanggalCheckin)) . "\n" .
                "Check-out: " . date('d/m/Y', strtotime($tanggalCheckout)) . "\n" .
                "Total Biaya: Rp" . number_format($totalKeseluruhan, 0, ',', '.');
    
            Http::withToken($apiToken)->post('https://app.japati.id/api/send-message', [
                'gateway' => $gatewayNumber,
                'number'  => $user->phone,
                'type'    => 'text',
                'message' => $messageUser,
            ]);
    
            return redirect()->route('booking.show', $reservasiHotel->id)
                ->with('success', "Reservasi hotel berhasil dibuat dengan total biaya Rp" . number_format($totalKeseluruhan, 0, ',', '.'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.')
                ->withInput();
        }
    }
    


    public function getAvailableRooms(Request $request)
    {
        $checkin = $request->input('checkin');
        $checkout = $request->input('checkout');

        $availableRooms = Room::with('category_hotel')
            ->where('status', 'Tersedia')
            ->whereDoesntHave('rincianReservasiHotel', function ($query) use ($checkin, $checkout) {
                $query->where(function ($query) use ($checkin, $checkout) {
                    $query->whereBetween('tanggal_checkin', [$checkin, $checkout])
                        ->orWhereBetween('tanggal_checkout', [$checkin, $checkout])
                        ->orWhere(function ($query) use ($checkin, $checkout) {
                            $query->where('tanggal_checkin', '<=', $checkin)
                                ->where('tanggal_checkout', '>=', $checkout);
                        });
                });
            })
            ->get();

        return response()->json($availableRooms);
    }

    // public function show($id)
    // {
    //     $reservasiHotel = ReservasiHotel::with(['dataPemilik', 'rincianReservasiHotel.room.category_hotel', 'rincianReservasiHotel.dataHewan'])
    //         ->findOrFail($id);

    //     return view('user.transaksi.rincianHotel', compact('reservasiHotel'));
    // }
    public function show($id)
    {
        $reservasiHotel = ReservasiHotel::with([
            'dataPemilik',
            'rincianReservasiHotel.room.category_hotel',
            'rincianReservasiHotel.dataHewan',
            'transaksi' // Pastikan ini aman
        ])->findOrFail($id);

        // Tambahkan pengecekan untuk transaksi
        if ($reservasiHotel->transaksi === null) {
            $reservasiHotel->transaksi = new Transaksi(); // Buat objek kosong
        }

        return view('user.transaksi.rincianHotel', compact('reservasiHotel'));
    }
    public function cancel($id)
    {
        $reservasiHotel = ReservasiHotel::findOrFail($id);

        if ($reservasiHotel->status != 'di pesan') {
            return redirect()->route('booking.index')->with('error', 'Reservasi sudah diproses atau dibayar, tidak bisa dibatalkan.');
        }

        $reservasiHotel->status = 'cancel';
        $reservasiHotel->save();

        foreach ($reservasiHotel->rincianReservasiHotel as $rincian) {
            $rincian->room->update(['status' => 'Tersedia']);
        }

        return redirect()->route('booking.index')->with('success', 'Reservasi berhasil dibatalkan.');
    }
}
