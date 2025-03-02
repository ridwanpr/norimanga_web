<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Manga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MangaListController extends Controller
{
    public function gridList(Request $request)
    {
        $latestUpdateCacheKey = 'manga.grid-list.latest-update.' . implode('.', array_keys($request->only(['genre', 'search', 'year', 'type', 'status'])));

        $genresCacheKey = 'manga.genres';

        // Retrieve or calculate the query results without pagination
        $queryResults = Cache::remember($latestUpdateCacheKey, now()->addHours(1), function () use ($request) {
            $query = Manga::join('manga_detail', 'manga.id', '=', 'manga_detail.manga_id')
                ->select('manga.title', 'manga.slug', 'manga_detail.cover', 'manga_detail.type', 'manga_detail.status', 'manga_detail.release_year', 'manga_detail.updated_at');

            // Applying filters
            if ($request->filled('genre')) {
                $query->whereHas('genres', function ($q) use ($request) {
                    $q->where('slug', $request->genre);
                });
            }

            if ($request->filled('search')) {
                $query->whereRaw('LOWER(manga.title) LIKE ?', ['%' . strtolower($request->search) . '%']);
            }

            if ($request->filled('year')) {
                $query->where('manga_detail.release_year', $request->year);
            }

            if ($request->filled('type')) {
                $query->where('manga_detail.type', $request->type);
            }

            if ($request->filled('status')) {
                $query->where('manga_detail.status', $request->status);
            }

            return $query->orderBy('manga_detail.updated_at', 'desc')->get();
        });

        $page = $request->input('page', 1);
        $perPage = 24;
        $offset = ($page - 1) * $perPage;
        $latestUpdate = new \Illuminate\Pagination\LengthAwarePaginator(
            array_slice($queryResults->toArray(), $offset, $perPage, true),
            count($queryResults),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $genres = Cache::remember($genresCacheKey, now()->addHours(1), function () {
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
