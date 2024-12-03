<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class KategoriHewan extends Model
{
    protected $table = 'kategori_hewan'; 

    protected $fillable = [
        'nama_kategori',
    ];
}
