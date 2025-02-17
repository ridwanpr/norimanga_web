<?php

namespace App\Http\Controllers\Backend;

use App\Models\Manga;
use Illuminate\Support\Str;
use App\Models\MangaChapter;
use Illuminate\Http\Request;
use App\Services\BucketManager;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MangaChapterController extends Controller
{
    public function index()
    {
        $chapters = MangaChapter::with('manga')->orderBy('id', 'desc')->paginate(10);
        return view('backend.manga_chapters.index', compact('chapters'));
    }

    public function create()
    {
        return view('backend.manga_chapters.create');
    }

    public function mangaList(Request $request)
    {
        $search = $request->query('search');
        $mangas = Manga::where('title', 'like', "%{$search}%")->limit(10)->get();

        return response()->json($mangas->map(fn($manga) => ['id' => $manga->id, 'text' => $manga->title]));
    }

    public function store(Request $request, BucketManager $bucketManager)
    {
        $request->validate([
            'manga_id' => 'required|exists:manga,id',
            'title' => 'required|string|max:255',
            'chapter_number' => 'required|numeric',
            'images.*' => 'image|max:2048'
        ]);

        DB::beginTransaction();
        try {
            $chapter = MangaChapter::create([
                'manga_id' => $request->manga_id,
                'title' => $request->title,
                'chapter_number' => $request->chapter_number,
                'slug' => Str::slug($request->title),
                'bucket' => $bucketManager->getCurrentBucket()
            ]);

            $uploadedImages = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $extension = $image->getClientOriginalExtension();
                    $fileName = "chapters/{$chapter->manga_id}/{$chapter->id}/{$index}.{$extension}";
                    $result = $bucketManager->storeFile($fileName, file_get_contents($image));
                    $uploadedImages[] = $result['url'];
                }
            }

            $chapter->update(['image' => json_encode($uploadedImages)]);
            DB::commit();

            return redirect()->route('manga-chapters.index')->with('success', 'Chapter added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create chapter: ' . $e->getMessage());
        }
    }
}
