<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryHotel;
use Illuminate\Http\Request;

class CategoryHotelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    // Ambil semua kategori dengan jumlah ruangan tersedia
    $categories = CategoryHotel::withCount(['rooms as jumlah_ruangan' => function ($query) {
        $query->where('status', 'tersedia'); // Hanya hitung ruangan dengan status 'tersedia'
    }])->get();

    // Kirim data ke view
    return view('admin.kategori_hotel.index', compact('categories'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.kategori_hotel.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|integer',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('category_photos', 'public');
        }

        CategoryHotel::create($validated);

        return redirect()->route('category_hotel.index')->with('success', 'Category created successfully.');
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
        return view('admin.kategori_hotel.edit', compact('categoryHotel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,CategoryHotel $categoryHotel)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|integer',
            'jumlah_ruangan' => 'required|integer',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('category_photos', 'public');
        }

        $categoryHotel->update($validated);

        return redirect()->route('category_hotel.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoryHotel $categoryHotel)
    {
        $categoryHotel->delete();
        return redirect()->route('category_hotel.index')->with('success', 'Category deleted successfully.');
    }
}
