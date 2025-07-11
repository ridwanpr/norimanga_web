<?php

namespace App\Http\Controllers;

use App\Models\UserActivity;
use Carbon\Carbon;
use App\Models\Manga;
use App\Models\Bookmark;
use App\Models\MangaView;
use App\Models\MangaDetail;
use App\Models\MangaChapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\UserActivityService;
use Illuminate\Support\Facades\Cache;

class MangaController extends Controller
{
    public function show($slug)
    {
        $manga = Cache::remember("manga_{$slug}", now()->addHours(2), function () use ($slug) {
            return Manga::where('slug', $slug)
                ->with('detail', 'genres', 'chapters')
                ->firstOrFail();
        });

        $sortedChapters = $manga->chapters->sortByDesc(function ($chapter) {
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
                ->select(
                    'manga.title',
                    'manga.slug',
                    'manga_detail.cover',
                    'manga_detail.type',
                    'manga_detail.status',
                    'manga_detail.updated_at',
                    'manga.id',
                    'manga_detail.type'
                )
                ->inRandomOrder()
                ->limit(8)
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

            MangaDetail::where('manga_id', $manga->id)
                ->increment('views', 1, ['updated_at' => DB::raw('updated_at')]);
        }

        $readChapters = [];
        $latestReadChapter = [];

        if (Auth::check()) {
            $latestReadChapter = UserActivity::with('chapter', 'manga')
                ->where('user_id', Auth::id())
                ->where('manga_id', $manga->id)
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();

            $readChapters = UserActivity::where('user_id', Auth::id())
                ->where('manga_id', $manga->id)
                ->pluck('chapter_id')
                ->toArray();

            $isBookmarked = Bookmark::where('user_id', Auth::id())
                ->where('manga_id', $manga->id)
                ->exists();
        } else {
            $isBookmarked = false;
        }

        return view('manga.show', compact(
            'manga',
            'alsoRead',
            'sortedChapters',
            'isBookmarked',
            'latestReadChapter',
            'readChapters'
        ));
    }

    public function reader($slug, $chapter_slug)
    {
        $chapter = Cache::remember("chapter_{$slug}_{$chapter_slug}", now()->addHours(4), function () use ($chapter_slug) {
            return MangaChapter::where('slug', $chapter_slug)
                ->with('manga')
                ->firstOrFail();
        });

        $prevChapter = Cache::remember("prev_chapter_{$slug}_{$chapter_slug}", now()->addHours(4), function () use ($chapter) {
            return MangaChapter::where('manga_id', $chapter->manga_id)
                ->whereRaw("CAST(REGEXP_SUBSTR(chapter_number, '[0-9]+(?:\.[0-9]+)?') AS DECIMAL) < CAST(REGEXP_SUBSTR(?, '[0-9]+(?:\.[0-9]+)?') AS DECIMAL)", [$chapter->chapter_number])
                ->orderByRaw("CAST(REGEXP_SUBSTR(chapter_number, '[0-9]+(?:\.[0-9]+)?') AS DECIMAL) DESC")
                ->first();
        });

        $nextChapter = Cache::remember("next_chapter_{$slug}_{$chapter_slug}", now()->addHours(4), function () use ($chapter) {
            return MangaChapter::where('manga_id', $chapter->manga_id)
                ->whereRaw("CAST(REGEXP_SUBSTR(chapter_number, '[0-9]+(?:\.[0-9]+)?') AS DECIMAL) > CAST(REGEXP_SUBSTR(?, '[0-9]+(?:\.[0-9]+)?') AS DECIMAL)", [$chapter->chapter_number])
                ->orderByRaw("CAST(REGEXP_SUBSTR(chapter_number, '[0-9]+(?:\.[0-9]+)?') AS DECIMAL) ASC")
                ->first();
        });


        $images = Cache::remember("images_{$slug}_{$chapter_slug}", now()->addHours(4), function () use ($chapter) {
            return $chapter->getFormattedImages();
        });

        if (Auth::check()) {
            $userActiviyService = new UserActivityService();
            $userActiviyService->storeUserActivity(Auth::id(), $chapter->manga_id, $chapter->id);
        }

        $alsoRead = Cache::remember("also_read_{$slug}_{$chapter_slug}", now()->addHours(4), function () {
            return Manga::with('detail', 'genres')->inRandomOrder()->limit(10)
                ->get()->map(function ($manga) {
                    $manga->detail->cover = str_replace('.s3.tebi.io', '', $manga->detail->cover);
                    return $manga;
                });
        });

        return view('manga.reader', compact('chapter', 'images', 'prevChapter', 'nextChapter', 'alsoRead'));
    }
}
