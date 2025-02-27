<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransaksiDenda extends Model
{
    use HasFactory;

    protected $table = 'transaksi_denda';

    protected $fillable = [
        'reservasi_id',
        'jumlah_denda',
        'Dibayar',
        'status_pembayaran',
        'tanggal_pembayaran',
        'metode_pembayaran',
        'bukti_pembayaran',
    ];

    public function reservasi()
    {
        return $this->belongsTo(ReservasiHotel::class, 'reservasi_id');
    }
}
