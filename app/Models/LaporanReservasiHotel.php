<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class LaporanReservasiHotel extends Model
{
    use HasFactory;

    protected $table = 'laporan_reservasi_hotel'; 

    protected $fillable = [
        'room_id', 'tanggal_checkin', 'tanggal_checkout',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'id_room');
    }
}
