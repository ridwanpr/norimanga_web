<?php

namespace App\Http\Controllers\Backend;

use App\Models\Manga;
use App\Jobs\FetchMangaJob;
use App\Models\MangaChapter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\FetchChapterJob;
use App\Jobs\SyncBucketUsageJob;

class AutoMationController extends Controller
{
    public function index()
    {
        $latestManga = Manga::whereHas('detail')->latest()->take(10)->get();
        $latestChapter = MangaChapter::with('manga')->whereJsonLength('image', '>', 0)->orderBy('updated_at', 'desc')->take(10)->get();
        
        return view('backend.automation.index', compact('latestManga', 'latestChapter'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json([]);
        }

        $manga = Manga::whereRaw('LOWER(title) LIKE LOWER(?)', ['%' . $query . '%'])
            ->limit(10)
            ->get(['id', 'title']);


        return response()->json($manga);
    }

    public function fetchManga(Request $request)
    {
        $url = $request->input('url');
        $bucket = $request->input('bucket');

        $request->validate([
            'url' => 'required|string',
            'bucket' => 'required|string',
        ]);

        dispatch(new FetchMangaJob($url, $bucket));
        return back()->with('success', 'Job dispatched successfully.');
    }

    public function fetchChapter(Request $request)
    {
        $id = $request->input('manga_id');
        $bucket = $request->input('bucket');

        $request->validate([
            'manga_id' => 'required',
            'bucket' => 'required|string',
        ]);

        $manga = Manga::findOrFail($id);

        dispatch(new FetchChapterJob($manga, $bucket));

        return back()->with('success', "Job dispatched successfully to bucket: {$bucket}");
    }
}
