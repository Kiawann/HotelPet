<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use App\Models\ReservasiHotel;
use App\Models\RincianReservasiHotel;
use Illuminate\Http\Request;

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
            $query->whereIn('status', ['check in', 'check out', 'done']);
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
    
        // Apply the ordering
        $reservasiHotels = $query->orderBy('created_at', 'desc')->get();
    
        return view('perawat.reservasi_hotel.index', compact('reservasiHotels'));
    }
    



    /**
     * Show the form for creating a new resource.
     */
    public function bulkCheckin(Request $request)
{
    try {
        // Pastikan ada data terpilih
        if (!$request->has('selected_ids')) {
            return redirect()->back()->with('error', 'Pilih minimal satu reservasi.');
        }

        $selectedIds = $request->selected_ids;

        // Update status reservasi menjadi check out
        ReservasiHotel::whereIn('id', $selectedIds)
            ->where('status', '!=', 'check out')
            ->update(['status' => 'check out']);

        // Redirect dengan filter tetap ada di URL
        return redirect()->route('reservasi-hotel.index', [
            'status' => $request->query('status'), // Pertahankan filter status
            'date_filter' => $request->query('date_filter') // Pertahankan filter tanggal
        ])->with('success', 'Status reservasi berhasil diperbarui menjadi check out.');
    } catch (\Exception $e) {
        // Tangani error
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
