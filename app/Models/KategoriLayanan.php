<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class KategoriLayanan extends Model
{
    use HasFactory;
    
    protected $table = 'kategori_layanan';

    protected $fillable = [
        'nama_layanan',
        'deskripsi',
        'foto',
        'harga',
    ];
}