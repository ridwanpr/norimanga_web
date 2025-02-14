<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('show', 'manga.show');
Route::view('reader', 'manga.reader');
Route::view('text-list', 'manga.list');
Route::view('list', 'manga.grid-list');