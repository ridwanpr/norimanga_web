<?php

namespace App\Http\Controllers\Backend;

use App\Models\Manga;
use App\Models\MangaDetail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Models\MangaChapter;
use Illuminate\Http\Request;
use App\Services\BucketManager;
use App\Http\Controllers\Controller;

class ChapterController extends Controller
{
    public function index($mangaId)
    {
        $manga = Manga::findOrFail($mangaId);
        $chapters = MangaChapter::with('manga')->where('manga_id', $mangaId)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('backend.comics.chapter.index', compact('chapters', 'manga'));
    }

    public function create($mangaId)
    {
        $manga = Manga::findOrFail($mangaId);
        return view('backend.comics.chapter.create', compact('manga'));
    }

    public function store(Request $request, $mangaId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'chapter_number' => 'required|string|max:50',
            'bucket' => 'required|string|in:s1,s2,s3,s4',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'slug' => 'required'
        ]);

        $chapter = MangaChapter::create([
            'manga_id' => $mangaId,
            'title' => $request->title,
            'chapter_number' => $request->chapter_number,
            'slug' => $request->slug,
            'bucket' => $request->bucket,
            'image' => json_encode([]),
        ]);

        if ($request->hasFile('images')) {
            $bucketManager = new BucketManager();
            $imagePaths = [];

            foreach ($request->file('images') as $index => $image) {
                $extension = $image->getClientOriginalExtension() ?: 'jpg';
                $fileName = "chapters/{$mangaId}/{$chapter->id}/{$index}.{$extension}";

                $uploadedImage = $bucketManager->storeFile($fileName, file_get_contents($image), $request->bucket, ['visibility' => 'public']);
                $imagePaths[] = $uploadedImage['url'];
            }

            $chapter->update(['image' => json_encode($imagePaths)]);
        }

        MangaDetail::where('manga_id', $mangaId)->update([
            'updated_at' => now()
        ]);

        Cache::flush();

        return redirect()->route('chapter.index', $mangaId)->with('success', 'Chapter created successfully.');
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
        $request->validate([
            'title' => 'required|string|max:255',
            'chapter_number' => 'required|string|max:50',
            'bucket' => 'required',
            'images.*' => 'image|max:4096',
        ]);

        $chapter = MangaChapter::where('manga_id', $mangaId)->where('id', $chapterId)->firstOrFail();
        $chapter->update([
            'title' => $request->title,
            'chapter_number' => $request->chapter_number,
            'slug' => $request->slug,
            'bucket' => $request->bucket,
        ]);

        if ($request->hasFile('images')) {
            $bucketManager = new BucketManager();

            foreach (json_decode($chapter->image, true) as $oldImage) {
                $pathParts = parse_url($oldImage);
                $relativePath = ltrim($pathParts['path'], '/');
                $bucketManager->deleteFile($chapter->bucket, $relativePath);
            }

            $imagePaths = [];
            foreach ($request->file('images') as $index => $image) {
                $extension = $image->getClientOriginalExtension() ?: 'jpg';
                $fileName = "chapters/{$mangaId}/{$chapterId}/{$index}.{$extension}";

                $uploadedImage = $bucketManager->storeFile($fileName, file_get_contents($image), $request->bucket, ['visibility' => 'public']);
                $imagePaths[] = $uploadedImage['url'];
            }

            $chapter->update(['image' => json_encode($imagePaths)]);
        }

        MangaDetail::where('manga_id', $mangaId)->update([
            'updated_at' => now()
        ]);

        Cache::flush();

        return redirect()->route('chapter.index', $mangaId)->with('success', 'Chapter updated successfully.');
    }

}
