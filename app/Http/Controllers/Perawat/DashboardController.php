<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use App\Models\LaporanHewan;
use App\Models\ReservasiHotel;
use App\Models\RincianReservasiHotel;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $today = Carbon::now()->format('Y-m-d');

    // Get reservations that include today's date within their check-in/check-out range
    $activeReservations = ReservasiHotel::where('status', 'check in')
        ->whereDate('tanggal_checkin', '<=', $today)
        ->whereDate('tanggal_checkout', '>=', $today)
        ->with(['rincianReservasiHotel.dataHewan', 'laporanHewan'])
        ->get();

    // Initialize counter
    $needsReport = 0;

    foreach ($activeReservations as $reservation) {
        // Count animals through rincianReservasiHotel relationship
        foreach ($reservation->rincianReservasiHotel as $rincian) {
            // Check if this animal already has a report for today
            $hasReport = $reservation->laporanHewan()
                ->where('reservasi_hotel_id', $reservation->id)
                ->whereDate('tanggal_laporan', $today)
                ->where('data_hewan_id', $rincian->data_hewan_id)
                ->exists();

            // If no report exists for this animal today, increment counter
            if (!$hasReport) {
                $needsReport++;
            }
        }
    }

    return view('perawat.dashboard', compact('needsReport'));
}


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
