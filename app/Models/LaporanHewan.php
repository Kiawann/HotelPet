<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;    

class LaporanHewan extends Model
{
    use HasFactory;

    protected $table = 'laporan_hewan';

    protected $fillable = [
        'reservasi_hotel_id',
        'data_hewan_id',
        'room_id',
        'Makan',
        'Minum',
        'Bab',
        'Bak',
        'keterangan',
        'tanggal_laporan',
        'foto',
    ];

    /**
     * Relasi ke ReservasiHotel.
     */
    public function reservasiHotel()
    {
        return $this->belongsTo(ReservasiHotel::class, 'reservasi_hotel_id');
    }

    /**
     * Relasi ke DataHewan.
     */
    public function dataHewan()
    {
        return $this->belongsTo(DataHewan::class, 'data_hewan_id');
    }

    /**
     * Relasi ke Room.
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
