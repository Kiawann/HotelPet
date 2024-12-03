<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class DataPemilik extends Model
{
    use HasFactory;

    protected $table = 'data_pemilik'; 

    protected $fillable = [
        'user_id',
        'nama',
        'jenis_kelamin',
        'nomor_telp',
        'foto',
    ];
}
