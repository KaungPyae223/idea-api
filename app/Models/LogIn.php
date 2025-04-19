<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogIn extends Model
{
    /** @use HasFactory<\Database\Factories\LogInFactory> */

    protected $fillable  = [
        'user_id',
        'browser',
        'ip_address'
    ];

    use HasFactory;
}
