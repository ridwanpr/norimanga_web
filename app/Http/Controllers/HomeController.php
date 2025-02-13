<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Manga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        $latestUpdate = Manga::join('manga_detail', 'manga.id', 'manga_detail.manga_id')
            ->select('manga.title', 'manga.slug', 'manga_detail.cover', 'manga_detail.type')
            ->orderBy('manga.updated_at', 'desc')
            ->take(16)
            ->get()
            ->map(function ($manga) {
                $manga->cover = str_replace('.s3.tebi.io', '', $manga->cover);
                return $manga;
            });

        $genres = Genre::select('name', 'slug')->orderBy('name')->get();

        return view('welcome', compact('latestUpdate', 'genres'));
    }
}
