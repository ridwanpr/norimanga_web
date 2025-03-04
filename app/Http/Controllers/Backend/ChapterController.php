<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Manga;
use App\Models\MangaChapter;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    public function index($mangaId)
    {
        $manga = Manga::findOrFail($mangaId);
        $chapters = MangaChapter::with('manga')->where('manga_id', $mangaId)
            ->paginate(15);

        return view('backend.comics.chapter.index', compact('chapters', 'manga'));
    }

    public function edit($mangaId, $chapterId)
    {
        $chapter = MangaChapter::with('manga')
            ->where('manga_id', $mangaId)
            ->where('id', $chapterId)
            ->firstOrFail();

        $formattedImages = $chapter->getFormattedImages();

        return view('backend.comics.chapter.edit', compact('chapter', 'formattedImages'));
    }

    public function update(Request $request, $mangaId, $chapterId)
    {
        
    }
}
