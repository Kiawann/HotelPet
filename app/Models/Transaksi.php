<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaksi extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transaksi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'data_pemilik_id',
        'reservasi_hotel_id',
        'reservasi_layanan_id',
        'tanggal_pembayaran',
        'Subtotal',
        'status_pembayaran',
        'Foto_Transfer',
    ];

    /**
     * Get the related DataPemilik model.
     */
    public function dataPemilik()
    {
        return $this->belongsTo(DataPemilik::class);
    }

    /**
     * Get the related ReservasiHotel model.
     */
    public function reservasiHotel()
    {
        return $this->belongsTo(ReservasiHotel::class);
    }

    /**
     * Get the related ReservasiLayanan model.
     */
    public function reservasiLayanan()
    {
        return $this->belongsTo(ReservasiLayanan::class);
    }
}
