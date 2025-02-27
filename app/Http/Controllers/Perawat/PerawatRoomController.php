<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class PerawatRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $rooms = Room::with('category_hotel')->paginate(10);
    return view('perawat.room.index', compact('rooms'));
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

    public function updateStatus(Request $request, Room $room)
    {
        $request->validate([
            'status' => 'required|in:Tersedia,Tidak Tersedia',
        ]);

        $room->update([
            'status' => $request->status,
        ]);

        return redirect()->route('perawat-room.index')->with('success', 'Status ruangan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
