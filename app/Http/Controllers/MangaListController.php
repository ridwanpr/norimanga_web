<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Manga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MangaListController extends Controller
{
    public function gridList()
    {
        $latestUpdate = Manga::join('manga_detail', 'manga.id', 'manga_detail.manga_id')
            ->select('manga.title', 'manga.slug', 'manga_detail.cover', 'manga_detail.type', 'manga_detail.status', 'manga_detail.updated_at')
            ->orderBy('manga_detail.updated_at', 'desc')
            ->paginate(24)
            ->through(function ($manga) {
                $manga->cover = str_replace('.s3.tebi.io', '', $manga->cover);
                return $manga;
            });

        $genres = Genre::select('name', 'slug')->orderBy('name')->get();

        return view('manga.grid-list', compact('latestUpdate', 'genres'));
    }

    public function textList()
    {
        $mangas = Cache::remember('manga.list', now()->addHours(3), function () {
            return Manga::with('detail')
                ->whereHas('detail')
                ->select('title', 'slug')
                ->orderBy('title')
                ->get()
                ->groupBy(function ($manga) {
                    $cleanedTitle = preg_replace('/^[^a-zA-Z0-9]+/', '', $manga->title);
                    $firstChar = strtoupper(substr($cleanedTitle, 0, 1));

                    return ctype_alpha($firstChar) ? $firstChar : '#';
                })
                ->map(fn($titles) => $titles->sortBy('title'))
                ->sortKeys();
        });

        return view('manga.list', compact('mangas'));
    }
}
