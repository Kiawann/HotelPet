<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReservasiLayanan extends Model
{
    use HasFactory;

    protected $table = 'reservasi_layanan';

    protected $fillable = [
        'data_pemilik_id',
        'tanggal_reservasi',
        'status',
    ];
    public function pemilik()
    {
        return $this->belongsTo(DataPemilik::class, 'data_pemilik_id', 'id');
    }

    public function rincian()
    {
        return $this->hasMany(RincianReservasiLayanan::class, 'reservasi_layanan_id', 'id');
    }
}
