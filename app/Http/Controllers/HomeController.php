<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Manga;
use App\Models\MangaChapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        $latestUpdate = Cache::remember('latest_update', now()->addMinutes(15), function () {
            return Manga::query()
                ->join('manga_detail', 'manga.id', '=', 'manga_detail.manga_id')
                ->select('manga.id', 'manga.title', 'manga.slug', 'manga_detail.cover', 'manga_detail.type', 'manga_detail.status', 'manga_detail.updated_at')
                ->where('manga.is_project', false)
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('manga_chapters')
                        ->whereColumn('manga_chapters.manga_id', 'manga.id')
                        ->limit(1);
                })
                ->with([
                    'chapters' => function ($query) {
                        $query->orderByRaw("
                            CAST(
                                REGEXP_SUBSTR(chapter_number, '[0-9]+(\\.[0-9]+)?') AS DECIMAL(10,2)
                            ) DESC
                        ")
                            ->take(2);
                    }
                ])
                ->latest('manga_detail.updated_at')
                ->take(20)
                ->get()
                ->map(function ($manga) {
                    $manga->cover = str_replace('.s3.tebi.io', '', $manga->cover);
                    return $manga;
                });
        });

        $trendingDaily = Cache::remember('trending_daily', now()->addHour(), function () {
            return Manga::trending('daily')->with('detail', 'genres')->take(8)->get()
                ->map(function ($manga) {
                    $manga->detail->cover = str_replace('.s3.tebi.io', '', $manga->detail->cover);
                    return $manga;
                });
        });

        $trendingWeekly = Cache::remember('trending_weekly', now()->addHours(3), function () {
            return Manga::trending('weekly')->with('detail', 'genres')->take(8)->get()
                ->map(function ($manga) {
                    $manga->detail->cover = str_replace('.s3.tebi.io', '', $manga->detail->cover);
                    return $manga;
                });
        });

        $trendingMonthly = Cache::remember('trending_monthly', now()->addHours(5), function () {
            return Manga::trending('monthly')->with('detail', 'genres')->take(8)->get()
                ->map(function ($manga) {
                    $manga->detail->cover = str_replace('.s3.tebi.io', '', $manga->detail->cover);
                    return $manga;
                });
        });

        $genres = Cache::remember('genres', now()->addHours(5), function () {
            return Genre::select('name', 'slug')->orderBy('name')->get();
        });

        $projects = Cache::remember('projects', now()->addMinutes(15), function () {
            return Manga::join('manga_detail', 'manga.id', 'manga_detail.manga_id')
                ->where('is_project', 1)
                ->select('manga.title', 'manga.slug', 'manga_detail.cover', 'manga_detail.type', 'manga_detail.status', 'manga_detail.updated_at', 'manga.id')
                ->orderBy('manga_detail.updated_at', 'desc')
                ->take(4)
                ->get()
                ->map(function ($manga) {
                    $manga->cover = str_replace('.s3.tebi.io', '', $manga->cover);
                    $manga->chapters = MangaChapter::where('manga_id', $manga->id)
                        ->orderBy('created_at', 'desc')
                        ->limit(2)
                        ->get();
                    return $manga;
                });
        });

        $featureds = Cache::remember('featureds', now()->addHour(), function () {
            return Manga::join('manga_detail', 'manga.id', 'manga_detail.manga_id')
                ->where('is_featured', 1)
                ->select('manga.title', 'manga.slug', 'manga_detail.cover', 'manga_detail.type', 'manga_detail.status', 'manga_detail.updated_at', 'manga.id')
                ->orderBy('manga_detail.updated_at', 'desc')
                ->take(6)
                ->get()
                ->map(function ($manga) {
                    $manga->cover = str_replace('.s3.tebi.io', '', $manga->cover);
                    $manga->chapters = MangaChapter::where('manga_id', $manga->id)
                        ->orderBy('created_at', 'desc')
                        ->limit(2)
                        ->get();
                    return $manga;
                });
        });

        return view('welcome', compact('latestUpdate', 'genres', 'trendingDaily', 'trendingWeekly', 'trendingMonthly', 'projects', 'featureds'));
    }
}
