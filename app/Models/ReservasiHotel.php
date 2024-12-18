<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReservasiHotel extends Model
{
    use HasFactory;

    // Nama tabel (opsional jika nama model tidak sama dengan tabel)
    protected $table = 'reservasi_hotel';

    // Kolom yang dapat diisi melalui mass assignment
    protected $fillable = [
        'data_pemilik_id', // Foreign key
        'status',
        'Total',
    ];

    /**
     * Relasi ke model DataPemilik (many-to-one).
     */
    public function dataPemilik()
    {
        return $this->belongsTo(DataPemilik::class, 'data_pemilik_id');
    }

    public function rincianReservasiHotel()
    {
        return $this->hasMany(RincianReservasiHotel::class);
    }

    public function laporanHewan()
    {
        return $this->hasMany(LaporanHewan::class, 'reservasi_hotel_id');
    }

public function rooms()
{
    return $this->hasManyThrough(Room::class, RincianReservasiHotel::class, 'reservasi_hotel_id', 'id', 'id', 'room_id');
}

 
     // Relasi dengan rincian data hewan
     public function dataHewans()
{
    return $this->hasManyThrough(DataHewan::class, RincianReservasiHotel::class, 'reservasi_hotel_id', 'id', 'id', 'data_hewan_id');
}
}
