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
        return $this->belongsTo(DataPemilik::class, 'data_pemilik_id');
    }

    public function kategoriHewan()
    {
        return $this->belongsTo(KategoriHewan::class, 'kategori_hewan_id');
    }
}
