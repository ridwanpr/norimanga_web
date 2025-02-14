<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Manga;
use App\Models\MangaChapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        $latestUpdate = Manga::join('manga_detail', 'manga.id', 'manga_detail.manga_id')
            ->select('manga.title', 'manga.slug', 'manga_detail.cover', 'manga_detail.type', 'manga_detail.status', 'manga_detail.updated_at', 'manga.id')
            ->orderBy('manga_detail.updated_at', 'desc')
            ->take(16)
            ->get()
            ->map(function ($manga) {
                $manga->cover = str_replace('.s3.tebi.io', '', $manga->cover);
                $manga->chapters = MangaChapter::where('manga_id', $manga->id)
                    ->orderBy('created_at', 'desc')
                    ->limit(2)
                    ->get();
                return $manga;
            });

        $genres = Genre::select('name', 'slug')->orderBy('name')->get();

        return view('welcome', compact('latestUpdate', 'genres'));
    }
}
