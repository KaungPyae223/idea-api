<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'idea_id',
        'file_name',
        'file_path',
    ];
}
