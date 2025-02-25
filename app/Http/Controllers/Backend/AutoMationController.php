<?php

namespace App\Http\Controllers\Backend;

use App\Models\Manga;
use App\Models\MangaChapter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AutoMationController extends Controller
{
    public function index()
    {
        $latestManga = Manga::whereHas('detail')->latest()->take(10)->get();
        $latestChapter = MangaChapter::with('manga')->whereJsonLength('image', '>', 0)->latest()->take(10)->get();
        return view('backend.automation.index', compact('latestManga', 'latestChapter'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json([]);
        }

        $manga = Manga::where('title', 'like', '%' . $query . '%')
            ->limit(10)
            ->get(['id', 'title']);

        return response()->json($manga);
    }
}
