<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use App\Models\ReservasiHotel;
use App\Models\RincianReservasiHotel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PerawatReservasiHotelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Start with base query including relationships
        $query = ReservasiHotel::with([
            'rincianReservasiHotel.dataHewan',
            'rincianReservasiHotel.room',
            'transaksi'
        ]);
    
        // Get today's date
        $today = now()->format('Y-m-d');
    
        // Apply status filter if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // If no filter is applied, fetch only the allowed statuses
            $query->whereIn('status', ['check in', 'check out']);
        }
    
        // Apply date filter
        if ($request->filled('date_filter')) {
            if ($request->date_filter === 'today') {
                $query->where(function($q) use ($today) {
                    // Check if today falls between check-in and check-out dates (inclusive)
                    $q->whereDate('tanggal_checkin', '<=', $today)
                      ->whereDate('tanggal_checkout', '>=', $today);
                });
            } elseif ($request->date_filter === 'check_out_today') {
                // Check for "Check Out Hari Ini"
                $query->where('status', 'check in')
                      ->whereDate('tanggal_checkout', $today);
            }
        }
    
        // Apply ordering and pagination
        $reservasiHotels = $query->orderBy('created_at', 'desc')->paginate(10); // Pagination 10 per halaman
    
        return view('perawat.reservasi_hotel.index', compact('reservasiHotels'));
    }
    
    



    /**
     * Show the form for creating a new resource.
     */
    public function bulkCheckin(Request $request)
    {
        try {
            if (!$request->has('selected_ids')) {
                return redirect()->back()->with('error', 'Pilih minimal satu reservasi.');
            }
    
            $selectedIds = $request->selected_ids;
    
            // Ambil data reservasi yang akan di-checkout
            $reservations = ReservasiHotel::whereIn('id', $selectedIds)->where('status', '!=', 'check out')->get();
    
            if ($reservations->isEmpty()) {
                return redirect()->back()->with('error', 'Semua reservasi sudah dalam status check out.');
            }
    
            // Update status reservasi menjadi check out
            ReservasiHotel::whereIn('id', $selectedIds)
                ->where('status', '!=', 'check out')
                ->update(['status' => 'check out']);
    
            $apiToken = 'API-TOKEN-lJ1MjuP1b1yeA5UMk7aVQNtpBycMzrjaQeTwmKQx2Geab05B1QACYo';
            $gatewayNumber = '6288222087560';
    
            foreach ($reservations as $reservasi) {
                $pemilik = $reservasi->dataPemilik->user ?? null;
                
                if ($pemilik && $pemilik->role == 'user') {
                    $messageUser = "Halo {$pemilik->name}, mohon maaf mengganggu waktunya. Kami dari Fur Heaven Pet Hotel memberitahukan:\n" .
                        "ID RESERVASI: {$reservasi->id}\n" .
                        "TANGGAL CHECK IN: {$reservasi->tanggal_checkin}\n" .
                        "TANGGAL CHECK OUT: {$reservasi->tanggal_checkout}\n" .
                        "Hewan sudah boleh diambil dari jam 08.00 sampai 17.00 ke Pet Hotel kami.";
                    
                    Http::withToken($apiToken)->post('https://app.japati.id/api/send-message', [
                        'gateway' => $gatewayNumber,
                        'number' => $pemilik->phone,
                        'type' => 'text',
                        'message' => $messageUser,
                    ]);
                }
            }
    
            // Kirim pesan ke kasir
            $kasirUsers = User::where('role', 'kasir')->get();
            foreach ($kasirUsers as $kasir) {
                foreach ($reservations as $reservasi) {
                    $messageKasir = "Halo {$kasir->name}, ada informasi reservasi yang telah check out:\n" .
                        "Nama: {$reservasi->dataPemilik->user->name}\n" .
                        "ID RESERVASI: {$reservasi->id}\n" .
                        "TANGGAL CHECK IN: {$reservasi->tanggal_checkin}\n" .
                        "TANGGAL CHECK OUT: {$reservasi->tanggal_checkout}\n" .
                        "Sudah boleh diambil, tunggu pelanggan mengambil hewan ke Pet Hotel.";
                    
                    Http::withToken($apiToken)->post('https://app.japati.id/api/send-message', [
                        'gateway' => $gatewayNumber,
                        'number' => $kasir->phone,
                        'type' => 'text',
                        'message' => $messageKasir,
                    ]);
                }
            }
    
            return redirect()->route('reservasi-hotel.index', [
                'status' => $request->query('status'),
                'date_filter' => $request->query('date_filter')
            ])->with('success', 'Status reservasi berhasil diperbarui menjadi check out dan pesan telah dikirim.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui status.');
        }
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
        return view('perawat.reservasi_hotel.rincian', compact('reservasiHotel'));
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
