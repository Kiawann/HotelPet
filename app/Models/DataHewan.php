<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class DataHewan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'data_hewan';

    public function pemilik()
    {
        return $this->belongsTo(DataPemilik::class, 'data_pemilik_id', 'id');
    }

    public function kategoriHewan()
    {
        return $this->belongsTo(KategoriHewan::class, 'kategori_hewan_id');
    }

    public function laporanHewan()
    {
        return $this->hasMany(LaporanHewan::class, 'data_hewan_id');
    }

    public function rincianReservasiHotel()
    {
        return $this->hasMany(RincianReservasiHotel::class, 'data_hewan_id');
    }
}
