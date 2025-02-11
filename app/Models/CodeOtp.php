<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CodeOtp extends Model
{
    use HasFactory;
    protected $table = 'code_otps';
    protected $fillable = [
        'phone',
        'otp',
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
