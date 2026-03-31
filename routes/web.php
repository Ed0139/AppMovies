<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CineController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\Favorite;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Rutas públicas
|--------------------------------------------------------------------------
*/

Route::get('/', [CineController::class, 'index'])->name('movies.index');
Route::get('/api/movies', [CineController::class, 'search']);
Route::get('/api/trending', [CineController::class, 'trending']);
Route::get('/movie/{id}', [CineController::class, 'show'])->name('movies.show');

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->get('/dashboard', function () {

    $user = Auth::user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    $favorites = Favorite::where('user_id', $user->id)->get();

    return view('user.dashboard', compact('favorites'));
})->name('dashboard');
/*
|--------------------------------------------------------------------------
| Favoritos
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::post('/favorite', [FavoriteController::class, 'store'])->name('favorite.store');
    Route::delete('/favorite/{movie_id}', [FavoriteController::class, 'destroy'])->name('favorite.destroy');
});

Route::get('/favorites/list', function () {
    return \App\Models\Favorite::where('user_id', Auth::id())
        ->pluck('movie_id');
})->middleware('auth');

/*
|--------------------------------------------------------------------------
| Perfil
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| PANEL ADMIN 
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        Route::resource('/users', UserController::class);

        Route::post('/movies/update', [AdminController::class, 'updateMovie'])
            ->name('movies.update');

        Route::put('/users/{id}/role', [AdminController::class, 'updateRole'])->name('users.updateRole');

        Route::delete('/users/{id}', [AdminController::class, 'delete'])->name('users.delete');
    });


/*
|--------------------------------------------------------------------------
| Auth (Laravel Breeze / Jetstream)
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
