<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    protected $table = 'room'; // Pastikan ini sesuai dengan tabel yang ada di database

    protected $fillable = [
        'category_hotel_id',
        'nama_ruangan',
        'status',
    ];

    // Relasi dengan CategoryHotel
    public function category_hotel()
    {
        return $this->belongsTo(CategoryHotel::class, 'category_hotel_id');
    }

    public function laporanHewan()
    {
        return $this->hasMany(LaporanHewan::class, 'room_id');
    }
}
