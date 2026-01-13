<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\PlaceController;
use App\Http\Controllers\Public\PlacePublicController;
use App\Http\Controllers\Public\SitemapController;
use App\Http\Controllers\Admin\PlacePhotoController;
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

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('places', \App\Http\Controllers\Admin\PlaceController::class);

    // ギャラリー写真
    Route::post('places/{place}/photos', [PlacePhotoController::class, 'store'])->name('places.photos.store');
    Route::delete('place-photos/{photo}', [PlacePhotoController::class, 'destroy'])->name('place_photos.destroy');
    
    //ギャラリー写真の並び替え
    Route::post('place-photos/{photo}/move-up', [\App\Http\Controllers\Admin\PlacePhotoController::class, 'moveUp'])
    ->name('place_photos.move_up');
    Route::post('place-photos/{photo}/move-down', [\App\Http\Controllers\Admin\PlacePhotoController::class, 'moveDown'])
    ->name('place_photos.move_down');

    //キャプション
    Route::put('place-photos/{photo}', [\App\Http\Controllers\Admin\PlacePhotoController::class, 'update'])
    ->name('place_photos.update');

    //サムネにする
    Route::post('place-photos/{photo}/make-thumbnail', [\App\Http\Controllers\Admin\PlacePhotoController::class, 'makeThumbnail'])
    ->name('place_photos.make_thumbnail');
});

require __DIR__.'/auth.php';

//公開ページ

Route::get('/', [PlacePublicController::class, 'home'])->name('public.home');
Route::get('/search', [PlacePublicController::class, 'search'])->name('public.search');

Route::get('/categories/{category:slug}', [PlacePublicController::class, 'category'])
    ->name('public.categories.show');

Route::get('/tags/{tag:slug}', [PlacePublicController::class, 'tag'])
    ->name('public.tags.show');

Route::get('/places/{place:slug}', [PlacePublicController::class, 'show'])
    ->name('public.places.show');

Route::view('/about', 'public.about')->name('public.about');

//サイトマップ作製

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('public.sitemap');

