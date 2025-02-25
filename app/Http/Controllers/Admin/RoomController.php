<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryHotel;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $rooms = Room::with('category_hotel')->get();
    //     return view('admin.room.index', compact('rooms'));
    // }
    public function index()
    {
        // Ambil semua ruangan dengan kategori hotel terkait dan urutkan berdasarkan nama ruangan
        $rooms = Room::with('category_hotel')->paginate(10); // Ubah get() menjadi paginate(10)
    
        // Kirim data ke view
        return view('admin.room.index', compact('rooms'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = CategoryHotel::all();
        return view('admin.room.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all()); // Melihat semua input dari form
        $validated = $request->validate([
            'category_hotel_id' => 'required|exists:category_hotel,id',
            'nama_ruangan' => 'required|string|max:255',
        ]);
    
        $validated['status'] = 'Tersedia'; // Set status otomatis
    
        // Tambahkan ruangan baru
        $room = Room::create($validated);
    
        // Update jumlah ruangan di kategori hotel
        $category = CategoryHotel::findOrFail($request->category_hotel_id);
        $category->increment('jumlah_ruangan'); // Tambahkan jumlah ruangan
    
        return redirect()->route('room.index')->with('success', 'Room created successfully.');
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
        $room = Room::findOrFail($id); // Ambil data room berdasarkan ID
        $categories = CategoryHotel::all(); // Ambil semua kategori hotel
        return view('admin.room.edit', compact('room', 'categories'));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'category_hotel_id' => 'required|exists:category_hotel,id',
            'nama_ruangan' => 'required|string|max:255',
            'status' => 'required|in:Tersedia,Tidak Tersedia',
        ]);

        $room->update($validated);

        return redirect()->route('room.index')->with('success', 'Room updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        // Pastikan ruangan memiliki kategori hotel terkait
    if ($room->categoryHotel) {
        // Kurangi jumlah ruangan di kategori hotel
        $room->categoryHotel->decrement('jumlah_ruangan');
    }

    // Hapus ruangan
    $room->delete();
        return redirect()->route('room.index')->with('success', 'Room deleted successfully.');
    }
    
}
