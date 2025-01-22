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
    
        // Kode sebelumnya untuk $needsReport tetap ada
        $activeReservations = ReservasiHotel::where('status', 'check in')
            ->whereDate('tanggal_checkin', '<=', $today)
            ->whereDate('tanggal_checkout', '>=', $today)
            ->with(['rincianReservasiHotel.dataHewan', 'laporanHewan'])
            ->get();
    
        // Hitung needsReport seperti sebelumnya
        $needsReport = 0;
        foreach ($activeReservations as $reservation) {
            foreach ($reservation->rincianReservasiHotel as $rincian) {
                $hasReport = $reservation->laporanHewan()
                    ->where('reservasi_hotel_id', $reservation->id)
                    ->whereDate('tanggal_laporan', $today)
                    ->where('data_hewan_id', $rincian->data_hewan_id)
                    ->exists();
    
                if (!$hasReport) {
                    $needsReport++;
                }
            }
        }
    
        // Tambahkan perhitungan untuk checkout hari ini
        $checkoutToday = ReservasiHotel::where('status', 'check in')
            ->whereDate('tanggal_checkout', $today)
            ->count();
    
        return view('perawat.dashboard', compact('needsReport', 'checkoutToday'));
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
