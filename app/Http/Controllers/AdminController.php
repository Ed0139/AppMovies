<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $users = User::all();

        return view('admin.dashboard', compact('users'));
    }

    public function updateMovie(Request $request)
    {
        $movie = Movie::updateOrCreate(
            ['tmdb_id' => $request->tmdb_id],
            [
                'title' => $request->title,
                'poster' => $request->poster,
                'overview' => $request->overview
            ]
        );

        return response()->json([
            "status" => "updated",
            "movie" => $movie
        ]);
    }

    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();

        return back()->with('success', 'Rol actualizado');
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'Usuario eliminado');
    }
}
