<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Http\Request;

class FilmController extends Controller
{
    public function index(Request $request)
    {
        $query = Film::orderBy('created_at', 'desc');
        
        // Filter by status if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        $films = $query->get();
        
        return response()->json([
            'success' => true,
            'message' => 'Films retrieved successfully',
            'data' => $films
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'genre' => 'required|string',
            'duration' => 'required|integer|min:1',
            'status' => 'required|in:now_playing,coming_soon,ended',
            'poster' => 'nullable|url',
            'director' => 'nullable|string|max:255',
            'release_date' => 'nullable|date'
        ]);
        
        $validated['created_by'] = $request->user()->id ?? 1;
        $film = Film::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Film created successfully',
            'data' => $film
        ], 201);
    }

    public function show(Film $film)
    {
        $film->load('creator:id,name');
        
        return response()->json([
            'success' => true,
            'message' => 'Film retrieved successfully',
            'data' => $film
        ]);
    }

    public function update(Request $request, Film $film)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'genre' => 'sometimes|string',
            'duration' => 'sometimes|integer|min:1',
            'status' => 'sometimes|in:now_playing,coming_soon,ended',
            'poster' => 'nullable|url',
            'director' => 'nullable|string|max:255',
            'release_date' => 'nullable|date'
        ]);
        
        $film->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Film updated successfully',
            'data' => $film->fresh()
        ]);
    }

    public function destroy(Film $film)
    {
        $film->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Film deleted successfully',
            'data' => null
        ]);
    }
}