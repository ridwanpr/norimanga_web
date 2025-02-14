<?php

namespace App\Http\Controllers;

use App\Models\Manga;
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

        return view('manga.show', compact('manga', 'alsoRead'));
    }

    public function reader($slug, $chapter_slug)
    {
        $chapter = MangaChapter::where('slug', $chapter_slug)
            ->with('manga')
            ->firstOrFail();

        $images = $chapter->getFormattedImages();

        return view('manga.reader', compact('chapter', 'images'));
    }
}
