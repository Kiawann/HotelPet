<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriHewan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      
    // Mengambil data kategori beserta jumlah data hewan yang terkait
    $categories = \App\Models\KategoriHewan::withCount('dataHewans')->get();

    // Mengambil nama kategori
    $categoryNames = $categories->pluck('nama_kategori')->toArray();
    // Mengambil jumlah hewan pada setiap kategori. Dengan withCount, field yang dihasilkan bernama data_hewan_count
    $animalCounts = $categories->pluck('data_hewans_count')->toArray();

    return view('admin.dashboard', compact('categoryNames', 'animalCounts'));
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
