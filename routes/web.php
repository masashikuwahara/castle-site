<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\PlaceController;
use App\Http\Controllers\Public\PlacePublicController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//管理ページ

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('places', PlaceController::class);
});

//公開ページ

Route::get('/', [PlacePublicController::class, 'home'])->name('public.home');
Route::get('/search', [PlacePublicController::class, 'search'])->name('public.search');

Route::get('/categories/{category:slug}', [PlacePublicController::class, 'category'])
    ->name('public.categories.show');

Route::get('/tags/{tag:slug}', [PlacePublicController::class, 'tag'])
    ->name('public.tags.show');

Route::get('/places/{place:slug}', [PlacePublicController::class, 'show'])
    ->name('public.places.show');

require __DIR__.'/auth.php';
