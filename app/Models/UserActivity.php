<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    protected $table = 'user_activity';
    protected $guarded = [];

    public function manga()
    {
        return $this->belongsTo(Manga::class);
    }

    public function chapter()
    {
        return $this->belongsTo(MangaChapter::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
