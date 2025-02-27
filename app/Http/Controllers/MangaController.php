<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Manga;
use App\Models\MangaView;
use App\Models\MangaDetail;
use App\Models\MangaChapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MangaController extends Controller
{
    public function show($slug)
    {
        $manga = Manga::where('slug', $slug)
            ->with('detail', 'genres', 'chapters')
            ->firstOrFail();

            $sortedChapters = $manga->chapters->sortByDesc(function($chapter) {
                // Extract numeric part and convert to float
                preg_match('/(\d+(\.\d+)?)/', $chapter->chapter_number, $matches);
                return isset($matches[1]) ? (float) $matches[1] : 0;
            });

        $firstChapter = $sortedChapters->last();
        $lastChapter = $sortedChapters->first();

        $manga->firstChapter = $firstChapter;
        $manga->lastChapter = $lastChapter;

        $ip = request()->ip();

        $alsoRead = Cache::remember("alsoRead_{$manga->id}", now()->addHours(2), function () {
            return Manga::with('genres')
                ->join('manga_detail', 'manga.id', 'manga_detail.manga_id')
                ->select('manga.title', 'manga.slug', 'manga_detail.cover', 'manga_detail.type', 'manga_detail.status', 'manga_detail.updated_at', 'manga.id')
                ->inRandomOrder()
                ->limit(4)
                ->get()
                ->map(function ($manga) {
                    $manga->cover = str_replace('.s3.tebi.io', '', $manga->cover);
                    return $manga;
                });
        });

        $manga->detail->cover = str_replace('.s3.tebi.io', '', $manga->detail->cover);

        $lastView = MangaView::where('manga_id', $manga->id)
            ->where('ip', $ip)
            ->where('created_at', '>=', Carbon::now()->subHour())
            ->exists();

        if (!$lastView) {
            MangaView::create([
                'manga_id' => $manga->id,
                'ip' => $ip,
                'created_at' => now(),
            ]);
        }

        return view('manga.show', compact('manga', 'alsoRead', 'sortedChapters'));
    }

    public function reader($slug, $chapter_slug)
    {
        $chapter = Cache::remember("chapter_{$slug}_{$chapter_slug}", now()->addHours(12), function () use ($chapter_slug) {
            return MangaChapter::where('slug', $chapter_slug)
                ->with('manga')
                ->firstOrFail();
        });

        $prevChapter = Cache::remember("prev_chapter_{$slug}_{$chapter_slug}", now()->addHours(12), function () use ($chapter) {
            return MangaChapter::where('manga_id', $chapter->manga_id)
                ->where('slug', '<', $chapter->slug)
                ->orderBy('slug', 'desc')
                ->first();
        });

        $nextChapter = Cache::remember("next_chapter_{$slug}_{$chapter_slug}", now()->addHours(12), function () use ($chapter) {
            return MangaChapter::where('manga_id', $chapter->manga_id)
                ->where('slug', '>', $chapter->slug)
                ->orderBy('slug', 'asc')
                ->first();
        });

        $images = Cache::remember("images_{$slug}_{$chapter_slug}", now()->addHours(12), function () use ($chapter) {
            return $chapter->getFormattedImages();
        });

        return view('manga.reader', compact('chapter', 'images', 'prevChapter', 'nextChapter'));
    }
}
