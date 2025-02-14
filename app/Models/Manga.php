<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Manga extends Model
{
    protected $table = 'manga';

    protected $fillable = ['title', 'slug'];

    public function scopeTrending(Builder $query, $period = 'daily')
    {
        $dateRange = match ($period) {
            'daily' => Carbon::now()->subDay(),
            'weekly' => Carbon::now()->subWeek(),
            'monthly' => Carbon::now()->subMonth(),
            default => Carbon::now()->subDay(),
        };

        return $query->whereHas('views', function ($q) use ($dateRange) {
            $q->where('created_at', '>=', $dateRange);
        })->withCount(['views' => function ($q) use ($dateRange) {
            $q->where('created_at', '>=', $dateRange);
        }])->orderByDesc('views_count');
    }

    public function detail(): HasOne
    {
        return $this->hasOne(MangaDetail::class, 'manga_id');
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(MangaChapter::class, 'manga_id');
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'manga_genre', 'manga_id', 'genre_id');
    }

    public function views()
    {
        return $this->hasMany(MangaView::class);
    }
}
