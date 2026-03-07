<?php

use App\Http\Controllers\CineController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ProfileController;
use App\Models\Favorite;
use Illuminate\Support\Facades\Route;

/*
 * |--------------------------------------------------------------------------
 * | Rutas públicas
 * |--------------------------------------------------------------------------
 */

Route::get('/', [CineController::class, 'index'])->name('movies.index');
Route::get('/api/movies', [CineController::class, 'search']);
Route::get('/api/trending', [CineController::class, 'trending']);
Route::get('/movie/{id}', [CineController::class, 'show'])->name('movies.show');

/*
 * |--------------------------------------------------------------------------
 * | Dashboard
 * |--------------------------------------------------------------------------
 */

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $favorites = Favorite::where('user_id', auth()->id())->get();

        return view('dashboard', compact('favorites'));
    })->middleware(['auth', 'verified'])->name('dashboard');
});

/*
 * |--------------------------------------------------------------------------
 * | Favoritos
 * |--------------------------------------------------------------------------
 */

Route::middleware('auth')->group(function () {
    Route::post('/favorite', [FavoriteController::class, 'store']);
    Route::delete('/favorite/{movie_id}', [FavoriteController::class, 'destroy']);
});

/*
 * |--------------------------------------------------------------------------
 * | Perfil
 * |--------------------------------------------------------------------------
 */

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
