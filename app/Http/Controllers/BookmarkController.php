<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Bookmark;

class BookmarkController extends Controller
{
    public function index()
    {
        $bookmarks = Auth::check()
            ? Bookmark::where('user_id', Auth::id())
            ->join('manga_detail', 'bookmarks.manga_id', '=', 'manga_detail.manga_id')
            ->with(['manga.detail', 'manga.genres', 'manga.chapters'])
            ->orderBy('manga_detail.updated_at', 'desc')
            ->select('bookmarks.*')
            ->paginate(10)
            : collect();

        foreach ($bookmarks as $bookmark) {
            if ($bookmark->manga && $bookmark->manga->chapters) {
                $sortedChapters = $bookmark->manga->chapters->sortByDesc(function ($chapter) {
                    preg_match('/(\d+(\.\d+)?)/', $chapter->chapter_number, $matches);
                    return isset($matches[1]) ? (float) $matches[1] : 0;
                });

                $bookmark->manga->lastChapter = $sortedChapters->first();
            }
        }

        return view('bookmark.index', compact('bookmarks'));
    }

    public function toggle(Request $request)
    {
        $user = Auth::user();
        $mangaId = $request->manga_id;

        $bookmark = Bookmark::where('user_id', $user->id)->where('manga_id', $mangaId)->first();

        if ($bookmark) {
            $bookmark->delete();
            return response()->json(['bookmarked' => false]);
        } else {
            Bookmark::create([
                'user_id' => $user->id,
                'manga_id' => $mangaId
            ]);
            return response()->json(['bookmarked' => true]);
        }
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();
        $mangaId = $request->manga_id;
        Bookmark::where('user_id', $user->id)->where('manga_id', $mangaId)->delete();

        return redirect()->route('bookmark.index')->with('success', 'Bookmark removed successfully.');
    }
}
