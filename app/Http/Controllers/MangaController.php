<?php

namespace App\Http\Controllers;

use App\Models\Manga;
use App\Models\MangaDetail;
use Illuminate\Http\Request;

class MangaController extends Controller
{
    public function show($slug)
    {
        $manga = Manga::where('slug', $slug)
            ->with('detail', 'genres', 'chapters')
            ->firstOrFail();

        $manga->detail->cover = str_replace('.s3.tebi.io', '', $manga->detail->cover);

        return view('manga.show', compact('manga'));
    }
}
