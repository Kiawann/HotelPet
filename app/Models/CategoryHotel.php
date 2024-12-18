<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class CategoryHotel extends Model
{
    use HasFactory;

    // Nama tabel yang terkait dengan model ini
    protected $table = 'category_hotel';

    // Kolom yang dapat diisi menggunakan mass assignment
    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'harga',
        'jumlah_ruangan',
        'foto',
    ];

     // Relasi dengan Room
     // Relasi dengan Room
    public function rooms()
    {
        return $this->hasMany(Room::class, 'category_hotel_id');
    }

     // Fungsi untuk menambah jumlah ruangan
    public function incrementJumlahRuangan()
    {
        $this->increment('jumlah_ruangan');
    }

    // Fungsi untuk mengurangi jumlah ruangan
    public function decrementJumlahRuangan()
    {
        $this->decrement('jumlah_ruangan');
    }

}
