<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CategoryHotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index()
    {
        $categories = CategoryHotel::all();

        return view('user.layananPetHotel', compact('categories'));
    }
}
