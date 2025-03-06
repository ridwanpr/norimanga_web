<?php

namespace App\Http\Controllers\Backend;

use App\Models\Manga;
use App\Jobs\FetchMangaJob;
use App\Models\MangaChapter;
use Illuminate\Http\Request;
use App\Jobs\FetchChapterJob;
use App\Jobs\SyncBucketUsageJob;
use App\Http\Controllers\Controller;
use App\Jobs\FetchChapterImageJobSingle;

class AutoMationController extends Controller
{
    public function index()
    {
        $latestManga = Manga::whereHas('detail')->latest()->take(15)->get();
        $latestChapter = MangaChapter::with('manga')->whereJsonLength('image', '>', 0)->orderBy('updated_at', 'desc')->take(15)->get();

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
            'manga_id' => 'required|exists:manga,id',
            'bucket' => 'required|string',
        ]);

        $manga = Manga::findOrFail($id);

        dispatch(new FetchChapterJob($manga, $bucket));

        return back()->with('success', "Job dispatched successfully to bucket: {$bucket}");
    }

    public function fetchChapterImage(Request $request)
    {
        $manga_id = $request->input('manga_id');
        $bucket = $request->input('bucket');
        $title = $request->input('chapter_title');
        $chapter_number = $request->input('chapter_number');
        $chapter_url = $request->input('chapter_url');

        // dd($request->all());

        $request->validate([
            'manga_id' => 'required',
            'bucket' => 'required|string',
            'chapter_title' => 'required',
            'chapter_number' => 'required',
            'chapter_url' => 'required'
        ]);

        $manga = Manga::findOrFail($manga_id);

        dispatch(new FetchChapterImageJobSingle($manga_id, $bucket, $title, $chapter_number, $chapter_url));

        return back()->with('success', "Job dispatched successfully to bucket: {$bucket}");
    }
}
