<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Idea extends Model
{
    /** @use HasFactory<\Database\Factories\IdeaFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'is_anonymous',
        'is_enabled',
        'system_setting_id'
    ];

    public function categories () {
        return $this->belongsToMany(Category::class,"category_ideas","idea_id","category_id","id","id");
    }

    public function files () {
        return $this->hasMany(File::class,"idea_id","id");
    }

    public function user() {
        return $this->belongsTo(User::class,"user_id","id");
    }

    public function comment() {
        return $this->hasMany(Comment::class,"idea_id","id");
    }

    public function votes() {
        return $this->hasMany(Vote::class,"idea_id","id");
    }

    public function systemSetting() {
        return $this->belongsTo(SystemSetting::class,'system_setting_id',"id");
    }

}
