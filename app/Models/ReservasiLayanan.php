<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ReservasiLayanan extends Model
{
    use HasFactory;

    // Menentukan nama tabel jika tidak mengikuti konvensi penamaan
    protected $table = 'reservasi_layanan';

    // Menentukan kolom yang bisa diisi (fillable) untuk menghindari mass assignment
    protected $fillable = [
        'data_pemilik_id', // Kolom untuk foreign key ke tabel DataPemilik
        'tanggal_reservasi',
        'status',
    ];
   // Relasi ke model DataPemilik
   public function pemilik()
   {
       return $this->belongsTo(DataPemilik::class, 'data_pemilik_id', 'id');
   }

   // Relasi ke model RincianReservasiLayanan
   public function rincian()
   {
       return $this->hasMany(RincianReservasiLayanan::class, 'reservasi_layanan_id', 'id');
   }

  
}
