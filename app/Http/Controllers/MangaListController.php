<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Manga;
use Illuminate\Http\Request;

class MangaListController extends Controller
{
    public function gridList()
    {
        $latestUpdate = Manga::join('manga_detail', 'manga.id', 'manga_detail.manga_id')
            ->select('manga.title', 'manga.slug', 'manga_detail.cover', 'manga_detail.type', 'manga_detail.status', 'manga_detail.updated_at')
            ->orderBy('manga_detail.updated_at', 'desc')
            ->paginate(5)
            ->onEachSide(1)
            ->through(function ($manga) {
                $manga->cover = str_replace('.s3.tebi.io', '', $manga->cover);
                return $manga;
            });

        $genres = Genre::select('name', 'slug')->orderBy('name')->get();

        return view('manga.grid-list', compact('latestUpdate', 'genres'));
    }
}
