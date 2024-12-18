<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RincianReservasiLayanan extends Model
{
    use HasFactory;

    // Menentukan nama tabel jika tidak mengikuti konvensi penamaan
    protected $table = 'rincian_reservasi_layanan';
     // Menetapkan kolom primary key yang digunakan
     protected $primaryKey = 'reservasi_layanan_id'; // Pastikan ini adalah kolom primary key yang benar

    // Menentukan kolom yang bisa diisi (fillable) untuk menghindari mass assignment
    protected $fillable = [
        'reservasi_layanan_id', // Kolom untuk foreign key ke tabel ReservasiLayanan
        'data_hewan_id', // Kolom untuk foreign key ke tabel ReservasiLayanan
        'kategori_layanan_id', // Kolom untuk foreign key ke tabel KategoriLayanan
        'tanggal_pelayanan', // Kolom untuk foreign key ke tabel KategoriLayanan
        'Harga', // Kolom untuk foreign key ke tabel KategoriLayanan
        
    ];

    
     // Definisikan relasi ke model KategoriLayanan
     public function layanan()
     {
         return $this->belongsTo(KategoriLayanan::class, 'kategori_layanan_id');
     }

     public function hewan()
     {
         return $this->belongsTo(DataHewan::class, 'data_hewan_id');
     }
}
