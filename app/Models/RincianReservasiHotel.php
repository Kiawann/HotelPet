<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RincianReservasiHotel extends Model
{
    use HasFactory;

    // Nama tabel (opsional jika nama model tidak sesuai konvensi Laravel)
    protected $table = 'rincian_reservasi_hotel';

    // Kolom yang dapat diisi melalui mass assignment
    protected $fillable = [
        'reservasi_hotel_id',
        'data_hewan_id',
        'room_id',
        'tanggal_checkin',
        'tanggal_checkout',
        'Denda',
        'status',
        'SubTotal',
    ];

    /**
     * Relasi ke model ReservasiHotel (many-to-one).
     */
    public function reservasiHotel()
    {
        return $this->belongsTo(ReservasiHotel::class, 'reservasi_hotel_id', 'id');
    }

    /**
     * Relasi ke model DataHewan (many-to-one).
     */
    public function dataHewan()
    {
        return $this->belongsTo(DataHewan::class, 'data_hewan_id', 'id');
    }

    /**
     * Relasi ke model Room (many-to-one).
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }

    // Relasi ke model DataPemilik
    public function dataPemilik()
    {
        return $this->belongsTo(DataPemilik::class, 'data_pemilik_id');
    }

    public function laporanHewan()
    {
        return $this->hasMany(LaporanHewan::class, 'reservasi_hotel_id', 'id');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'reservasi_hotel_id', 'id');
    }
}
