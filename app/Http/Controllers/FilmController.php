<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class FilmController extends Controller
{
    public function index(): JsonResponse
    {
        $films = Film::with('creator')->orderBy('created_at', 'desc')->get();
        return response()->json($films);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'genre' => 'required|string',
            'duration' => 'required|integer|min:1',
            'description' => 'required|string',
            'status' => 'required|in:play_now,coming_soon',
            'poster' => 'nullable|string',
            'director' => 'nullable|string|max:255',
            'release_date' => 'nullable|date'
        ]);

        $validated['created_by'] = Auth::id();
        $film = Film::create($validated);

        return response()->json($film->load('creator'), 201);
    }

    public function show(Film $film): JsonResponse
    {
        return response()->json($film->load('creator'));
    }

    public function update(Request $request, Film $film): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'genre' => 'sometimes|string',
            'duration' => 'sometimes|integer|min:1',
            'description' => 'sometimes|string',
            'status' => 'sometimes|in:play_now,coming_soon',
            'poster' => 'nullable|string',
            'director' => 'nullable|string|max:255',
            'release_date' => 'nullable|date'
        ]);

        $film->update($validated);

        return response()->json($film->load('creator'));
    }

    public function destroy(Film $film): JsonResponse
    {
        $film->delete();

        return response()->json(['message' => 'Film deleted successfully']);
    }
}
