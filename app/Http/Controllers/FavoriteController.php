<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{

  public function store(Request $request)
  {

    $favorite = Favorite::where('user_id', Auth::id())
      ->where('movie_id', $request->movie_id)
      ->first();

    if ($favorite) {

      $favorite->delete();

      return response()->json([
        "status" => "removed"
      ]);
    }

    Favorite::create([

      'user_id' => Auth::id(),
      'movie_id' => $request->movie_id,
      'title' => $request->title,
      'poster' => $request->poster

    ]);

    return response()->json([
      "status" => "added"
    ]);
  }
}
