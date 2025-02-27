<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class KategoriHewan extends Model
{
    use HasFactory;
    
    protected $table = 'kategori_hewan';

    protected $fillable = [
        'nama_kategori',
    ];

    public function dataHewans()
{
    return $this->hasMany(\App\Models\DataHewan::class, 'kategori_hewan_id');
}
}
