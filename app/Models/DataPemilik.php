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
        'phone',
        'foto',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

       public function hewan()
       {
           return $this->hasMany(DataHewan::class, 'data_pemilik_id');
       }
       
}
