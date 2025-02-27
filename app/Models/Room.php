<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    protected $table = 'room';

    protected $fillable = [
        'category_hotel_id',
        'nama_ruangan',
        'status',
    ];

    public function category_hotel()
    {
        return $this->belongsTo(CategoryHotel::class, 'category_hotel_id', 'id');
    }

    public function laporanHewan()
    {
        return $this->hasMany(LaporanHewan::class, 'room_id');
    }

    public function rincianReservasiHotel()
    {
        return $this->hasMany(RincianReservasiHotel::class);
    }
}