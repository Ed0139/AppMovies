<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CineController extends Controller
{
    public function index()
    {
        return view('movies.index');
    }

    // ==============================
    // BUSCAR PELÍCULAS
    // ==============================

    public function search(Request $request)
    {
        $query = $request->q;
        $page = $request->page ?? 1;
        $genre = $request->genre;

        $params = [
            'api_key' => env('TMDB_API_KEY'),
            'query' => $query,
            'page' => $page,
            'language' => 'es-MX'
        ];

        if ($genre) {
            $params['with_genres'] = $genre;
        }

        $response = Http::get(
            'https://api.themoviedb.org/3/search/movie',
            $params
        );

        return response()->json($response->json());
    }

    // ==============================
    // PELÍCULAS EN TENDENCIA
    // ==============================

    public function trending(Request $request)
    {
        $page = $request->page ?? 1;
        $genre = $request->genre;

        $params = [
            'api_key' => env('TMDB_API_KEY'),
            'page' => $page,
            'language' => 'es-MX'
        ];

        if ($genre) {
            $params['with_genres'] = $genre;
        }

        $response = Http::get(
            'https://api.themoviedb.org/3/trending/movie/day',
            $params
        );

        return response()->json($response->json());
    }

    // ==============================
    // DETALLES DE PELÍCULA
    // ==============================

    public function show($id)
    {
        $movie = Http::get("https://api.themoviedb.org/3/movie/$id", [
            'api_key' => env('TMDB_API_KEY'),
            'language' => 'es-MX'
        ])->json();

        $videos = Http::get("https://api.themoviedb.org/3/movie/$id/videos", [
            'api_key' => env('TMDB_API_KEY'),
            'language' => 'es-MX'
        ])->json();

        $credits = Http::get("https://api.themoviedb.org/3/movie/$id/credits", [
            'api_key' => env('TMDB_API_KEY'),
            'language' => 'es-MX'
        ])->json();

        $trailer = null;

        if (isset($videos['results'])) {
            foreach ($videos['results'] as $video) {
                if (
                    $video['site'] === 'YouTube' &&
                    ($video['type'] === 'Trailer' || $video['type'] === 'Teaser')
                ) {
                    $trailer = $video['key'];
                    break;
                }
            }
        }

        $cast = $credits['cast'] ?? [];

        return view('movies.show', compact('movie', 'trailer', 'cast'));
    }
}
