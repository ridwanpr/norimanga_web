<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Manga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class MangaListController extends Controller
{
    public function gridList(Request $request)
    {
        $cacheKey = 'grid_list_' . md5(json_encode($request->all()));

        $latestUpdate = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($request) {
            $query = Manga::join('manga_detail', 'manga.id', '=', 'manga_detail.manga_id')
                ->select('manga.title', 'manga.slug', 'manga_detail.cover', 'manga_detail.type', 'manga_detail.status', 'manga_detail.views', 'manga_detail.updated_at')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('manga_chapters')
                        ->whereColumn('manga_chapters.manga_id', 'manga.id')
                        ->limit(1);
                });

            if ($request->filled('genre')) {
                $query->whereHas('genres', function ($q) use ($request) {
                    $q->where('slug', $request->genre);
                });
            }

            if ($request->filled('search')) {
                $searchTerm = preg_replace('/[^a-zA-Z0-9\s]/', ' ', strtolower($request->search));

                $query->where(function ($q) use ($searchTerm) {
                    $q->whereRaw("MATCH(title) AGAINST(? IN NATURAL LANGUAGE MODE)", [$searchTerm])
                        ->orWhereRaw("LOWER(REGEXP_REPLACE(manga.title, '[^a-zA-Z0-9]', ' ')) LIKE ?", ['%' . $searchTerm . '%']);
                });
            }

            if ($request->filled('type')) {
                $query->where('manga_detail.type', $request->type);
            }

            if ($request->filled('status')) {
                $query->where('manga_detail.status', $request->status);
            }

            if ($request->order_by === 'popular') {
                $query->orderBy('manga_detail.views', 'desc');
            } else {
                $query->orderBy('manga_detail.updated_at', 'desc');
            }

            return $query->paginate(24)
                ->withQueryString()
                ->through(function ($manga) {
                    $manga->cover = str_replace('.s3.tebi.io', '', $manga->cover);
                    return $manga;
                });
        });

        $genres = Cache::remember('genre.list', now()->addHours(1), function () {
            return Genre::select('name', 'slug')->orderBy('name')->get();
        });

        return view('manga.grid-list', compact('latestUpdate', 'genres'));
    }

    public function textList()
    {
        $mangas = Cache::remember('manga.list', now()->addHours(1), function () {
            return Manga::with('detail')
                ->whereHas('detail')
                ->select('title', 'slug')
                ->orderBy('title')
                ->get()
                ->groupBy(function ($manga) {
                    $cleanedTitle = preg_replace('/^[^a-zA-Z0-9]+/', '', $manga->title);
                    $firstChar = strtoupper(substr($cleanedTitle, 0, 1));

                    return ctype_alpha($firstChar) ? $firstChar : '#';
                })
                ->map(fn($titles) => $titles->sortBy('title'))
                ->sortKeys();
        });

        return view('manga.list', compact('mangas'));
    }
}
