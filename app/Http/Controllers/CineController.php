<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CineController extends Controller
{
    // ==============================
    // HOME / LISTADO
    // ==============================
    public function index()
    {
        // Obtener películas populares
        $response = Http::get('https://api.themoviedb.org/3/movie/popular', [
            'api_key' => env('TMDB_API_KEY'),
            'language' => 'es-MX'
        ]);

        $movies = $response->json()['results'] ?? [];

        // Override desde BD
        foreach ($movies as &$movie) {
            $localMovie = Movie::where('tmdb_id', $movie['id'])->first();

            if ($localMovie) {
                $movie['title'] = $localMovie->title;
                $movie['overview'] = $localMovie->overview;
                $movie['poster_path'] = $localMovie->poster;
            }
        }

        return view('movies.index', compact('movies'));
    }

    // ==============================
    // BUSCAR PELÍCULAS
    // ==============================
    public function search(Request $request)
    {
        $query = $request->q;
        $page = $request->page ?? 1;

        $response = Http::get('https://api.themoviedb.org/3/search/movie', [
            'api_key' => env('TMDB_API_KEY'),
            'query' => $query,
            'page' => $page,
            'language' => 'es-MX'
        ]);

        $movies = $response->json()['results'] ?? [];

        // Override también en búsqueda
        foreach ($movies as &$movie) {
            $localMovie = Movie::where('tmdb_id', $movie['id'])->first();

            if ($localMovie) {
                $movie['title'] = $localMovie->title;
                $movie['overview'] = $localMovie->overview;
                $movie['poster_path'] = $localMovie->poster;
            }
        }

        return response()->json([
            'results' => $movies
        ]);
    }

    // ==============================
    // TENDENCIAS
    // ==============================
    public function trending(Request $request)
    {
        $page = $request->page ?? 1;

        $response = Http::get(
            'https://api.themoviedb.org/3/trending/movie/day',
            [
                'api_key' => env('TMDB_API_KEY'),
                'page' => $page,
                'language' => 'es-MX'
            ]
        );

        $movies = $response->json()['results'] ?? [];

        // Override también aquí
        foreach ($movies as &$movie) {
            $localMovie = Movie::where('tmdb_id', $movie['id'])->first();

            if ($localMovie) {
                $movie['title'] = $localMovie->title;
                $movie['overview'] = $localMovie->overview;
                $movie['poster_path'] = $localMovie->poster;
            }
        }

        return response()->json([
            'results' => $movies
        ]);
    }

    // ==============================
    // DETALLE DE PELÍCULA
    // ==============================
    public function show($id)
    {
        // Datos principales
        $movie = Http::get("https://api.themoviedb.org/3/movie/$id", [
            'api_key' => env('TMDB_API_KEY'),
            'language' => 'es-MX'
        ])->json();

        // Videos
        $videos = Http::get("https://api.themoviedb.org/3/movie/$id/videos", [
            'api_key' => env('TMDB_API_KEY'),
            'language' => 'es-MX'
        ])->json();

        // Créditos
        $credits = Http::get("https://api.themoviedb.org/3/movie/$id/credits", [
            'api_key' => env('TMDB_API_KEY'),
            'language' => 'es-MX'
        ])->json();

        // Override desde BD
        $localMovie = Movie::where('tmdb_id', $id)->first();

        if ($localMovie) {
            $movie['title'] = $localMovie->title;
            $movie['overview'] = $localMovie->overview;
            $movie['poster_path'] = $localMovie->poster;
        }

        // Trailer
        $trailer = null;

        if (isset($videos['results'])) {
            foreach ($videos['results'] as $video) {
                if (
                    $video['site'] === 'YouTube' &&
                    in_array($video['type'], ['Trailer', 'Teaser'])
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
