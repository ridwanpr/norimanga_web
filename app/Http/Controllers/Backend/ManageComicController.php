<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Manga;
use App\Models\MangaDetail;
use App\Services\BucketManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ManageComicController extends Controller
{
    protected $bucketManager;

    public function __construct(BucketManager $bucketManager)
    {
        $this->bucketManager = $bucketManager;
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $query = Manga::with('detail')->latest('created_at');

        if ($search) {
            $query->where('title', 'LIKE', "%{$search}%");
        }

        $comics = $query->paginate(20)->withQueryString();
        return view('backend.comics.index', compact('comics', 'search'));
    }

    public function create()
    {
        return view('backend.comics.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:manga,title',
            'slug' => 'required|string|max:255|unique:manga,slug',
            'is_project' => 'boolean',
            'status' => 'required|string',
            'type' => 'required|string',
            'release_year' => 'nullable|integer',
            'author' => 'nullable|string|max:255',
            'artist' => 'nullable|string|max:255',
            'synopsis' => 'nullable|string',
            'cover' => 'nullable|image|max:2048',
            'bucket' => 'string',
        ]);

        return DB::transaction(function () use ($request) {
            $coverPath = null;
            $bucket = null;

            try {
                // ✅ Create manga
                $manga = Manga::create([
                    'title' => $request->title,
                    'slug' => $request->slug,
                    'is_project' => $request->is_project ?? 0,
                    'is_featured' => $request->is_featured ?? 0,
                ]);

                if (!$manga) {
                    return redirect()->back()->withErrors(['error' => 'Failed to create comic. Please try again.']);
                }

                // ✅ Upload cover if exists
                if ($request->hasFile('cover')) {
                    $file = $request->file('cover');
                    $fileName = 'covers/' . $manga->id . '.' . $file->getClientOriginalExtension();

                    try {
                        $upload = $this->bucketManager->storeFile(
                            $fileName,
                            file_get_contents($file),
                            $request->bucket,
                            ['visibility' => 'public']
                        );

                        if (!$upload || !isset($upload['url'])) {
                            return redirect()->back()->withErrors(['error' => 'Cover upload failed. Please try again.']);
                        }

                        $coverPath = $upload['url'];
                        $bucket = $upload['bucket'];
                    } catch (\Exception $e) {
                        Log::error("Cover upload failed for '{$manga->title}': " . $e->getMessage());
                        return redirect()->back()->withErrors(['error' => 'Cover upload failed. Please try again.']);
                    }
                }

                // ✅ Save manga details
                $mangaDetail = MangaDetail::create([
                    'manga_id' => $manga->id,
                    'status' => $request->status,
                    'type' => $request->type,
                    'release_year' => $request->release_year,
                    'author' => $request->author,
                    'artist' => $request->artist,
                    'synopsis' => $request->synopsis,
                    'cover' => $coverPath,
                    'bucket' => $bucket,
                ]);

                if (!$mangaDetail) {
                    return redirect()->back()->withErrors(['error' => 'Failed to save comic details. Please try again.']);
                }

                return redirect()->route('manage-comic.index')->with('success', 'Comic created successfully.');
            } catch (\Exception $e) {
                Log::error("Manga creation failed: " . $e->getMessage());
                return redirect()->back()->withErrors(['error' => 'Something went wrong. Please try again.']);
            }
        });
    }


    public function edit($id)
    {
        $manage_comic = Manga::with('detail')->where('id', $id)->first();
        return view('backend.comics.edit', compact('manage_comic'));
    }

    public function update(Request $request, Manga $manage_comic)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:manga,title,' . $manage_comic->id,
            'slug' => 'required|string|max:255|unique:manga,slug,' . $manage_comic->id,
            'is_project' => 'boolean',
            'status' => 'required|string',
            'type' => 'required|string',
            'release_year' => 'nullable|integer',
            'author' => 'nullable|string|max:255',
            'artist' => 'nullable|string|max:255',
            'synopsis' => 'nullable|string',
            'cover' => 'nullable|image|max:2048',
            'bucket' => 'string',
        ]);

        return DB::transaction(function () use ($request, $manage_comic) {
            $coverPath = $manage_comic->detail->cover;
            $bucket = $manage_comic->detail->bucket;

            if ($request->hasFile('cover')) {
                $file = $request->file('cover');
                $path = 'covers/' . uniqid() . '.' . $file->getClientOriginalExtension();

                try {
                    // Delete old cover if exists
                    if ($coverPath && $bucket) {
                        $this->bucketManager->deleteFile($bucket, parse_url($coverPath, PHP_URL_PATH));
                    }

                    // Upload new cover
                    $upload = $this->bucketManager->storeFile(
                        $path,
                        file_get_contents($file),
                        $request->bucket,
                        ['visibility' => 'public']
                    );

                    $coverPath = $upload['url'];
                    $bucket = $upload['bucket'];
                } catch (\Exception $e) {
                    Log::error("Cover update failed: " . $e->getMessage());
                    throw $e;
                }
            }

            $manage_comic->update([
                'title' => $request->title,
                'slug' => $request->slug,
                'is_project' => $request->is_project ?? 0,
                'is_featured' => $request->is_featured ?? 0,
            ]);

            $manage_comic->detail->update([
                'status' => $request->status,
                'type' => $request->type,
                'release_year' => $request->release_year,
                'author' => $request->author,
                'artist' => $request->artist,
                'synopsis' => $request->synopsis,
                'cover' => $coverPath,
                'bucket' => $bucket,
            ]);

            return redirect()->route('manage-comic.index')->with('success', 'Comic updated successfully.');
        });
    }

    public function destroy(Manga $manage_comic)
    {
        return DB::transaction(function () use ($manage_comic) {
            try {
                if ($manage_comic->detail->cover && $manage_comic->detail->bucket) {
                    $this->bucketManager->deleteFile($manage_comic->detail->bucket, parse_url($manage_comic->detail->cover, PHP_URL_PATH));
                }

                $manage_comic->detail()->delete();
                $manage_comic->delete();

                return redirect()->route('manage-comic.index')->with('success', 'Comic deleted successfully.');
            } catch (\Exception $e) {
                Log::error("Comic deletion failed: " . $e->getMessage());
                throw $e;
            }
        });
    }
}
