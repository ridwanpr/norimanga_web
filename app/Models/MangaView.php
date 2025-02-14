<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MangaView extends Model
{
    protected $fillable = ['manga_id', 'ip'];

    public $timestamps = false;

    public function manga(): BelongsTo
    {
        return $this->belongsTo(Manga::class);
    }
}
