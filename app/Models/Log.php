<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'type',
        'activity',
        'created_at',
    ];

    public function user() {
        return $this->belongsTo(User::class,"user_id","id");
    }
}
