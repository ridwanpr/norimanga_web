<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\MangaController;
use App\Http\Controllers\MangaListController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('daftar-komik', [MangaListController::class, 'gridList'])->name('manga.grid-list');
Route::get('komik/{slug}', [MangaController::class, 'show'])->name('manga.show');
Route::view('reader', 'manga.reader');
Route::view('text-list', 'manga.list');
Route::view('list', 'manga.grid-list');