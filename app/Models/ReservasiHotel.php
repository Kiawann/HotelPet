<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReservasiHotel extends Model
{
    use HasFactory;

    protected $table = 'reservasi_hotel';

    protected $fillable = [
        'data_pemilik_id',
        'status',
        'tanggal_checkin',
        'tanggal_checkout',
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
        return $this->hasMany(RincianReservasiHotel::class, 'reservasi_hotel_id', 'id');
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

    public function transaksi()
    {
        return $this->hasOne(Transaksi::class, 'reservasi_hotel_id','id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'data_pemilik_id');
    }
}
