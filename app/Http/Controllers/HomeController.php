<?php

namespace App\Http\Controllers;

use PDO;
use App\Models\Genre;
use App\Models\Manga;
use App\Models\MangaChapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        $latestUpdate = Cache::remember('latest_update', now()->addMinutes(15), function () {
            return Manga::join('manga_detail', 'manga.id', 'manga_detail.manga_id')
                ->select('manga.title', 'manga.slug', 'manga_detail.cover', 'manga_detail.type', 'manga_detail.status', 'manga_detail.updated_at', 'manga.id')
                ->where('manga.is_project', false)
                ->orderBy('manga_detail.updated_at', 'desc')
                ->take(16)
                ->get()
                ->map(function ($manga) {
                    $manga->cover = str_replace('.s3.tebi.io', '', $manga->cover);

                    $dbDriver = DB::connection()->getPDO()->getAttribute(PDO::ATTR_DRIVER_NAME);

                    if ($dbDriver === 'pgsql') {
                        $manga->chapters = MangaChapter::where('manga_id', $manga->id)
                            ->orderByRaw("NULLIF(chapter_number, '')::INTEGER DESC")
                            ->take(2)
                            ->get();
                    } else {
                        $manga->chapters = MangaChapter::where('manga_id', $manga->id)
                            ->orderByRaw("CAST(chapter_number AS UNSIGNED) DESC")
                            ->take(2)
                            ->get();
                    }

                    return $manga;
                });
        });


        $trendingDaily = Cache::remember('trending_daily', now()->addHour(), function () {
            return Manga::trending('daily')->with('detail')->take(5)->get()
                ->map(function ($manga) {
                    $manga->detail->cover = str_replace('.s3.tebi.io', '', $manga->detail->cover);
                    return $manga;
                });
        });

        $trendingWeekly = Cache::remember('trending_weekly', now()->addHours(3), function () {
            return Manga::trending('weekly')->with('detail')->take(5)->get()
                ->map(function ($manga) {
                    $manga->detail->cover = str_replace('.s3.tebi.io', '', $manga->detail->cover);
                    return $manga;
                });
        });

        $trendingMonthly = Cache::remember('trending_monthly', now()->addHours(5), function () {
            return Manga::trending('monthly')->with('detail')->take(5)->get()
                ->map(function ($manga) {
                    $manga->detail->cover = str_replace('.s3.tebi.io', '', $manga->detail->cover);
                    return $manga;
                });
        });

        $genres = Genre::select('name', 'slug')->orderBy('name')->get();

        $projects =  Manga::join('manga_detail', 'manga.id', 'manga_detail.manga_id')
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

        $featureds =  Manga::join('manga_detail', 'manga.id', 'manga_detail.manga_id')
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

        return view('welcome', compact('latestUpdate', 'genres', 'trendingDaily', 'trendingWeekly', 'trendingMonthly', 'projects', 'featureds'));
    }
}
