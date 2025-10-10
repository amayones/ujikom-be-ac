<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function getFilms()
    {
        try {
            $films = Film::orderBy('created_at', 'desc')->get();
            return response()->json([
                'success' => true,
                'data' => $films
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch films'
            ], 500);
        }
    }

    public function storeFilm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:100',
            'duration' => 'required|integer|min:1',
            'description' => 'required|string',
            'status' => 'required|in:play_now,coming_soon,history',
            'director' => 'required|string|max:255',
            'release_date' => 'required|date',
            'poster' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $film = Film::create([
                'title' => $request->title,
                'genre' => $request->genre,
                'duration' => $request->duration,
                'description' => $request->description,
                'status' => $request->status,
                'director' => $request->director,
                'release_date' => $request->release_date,
                'poster' => $request->poster ?? '/ac.jpg',
                'created_by' => 1 // Default admin user
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Film created successfully',
                'data' => $film
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create film'
            ], 500);
        }
    }

    public function updateFilm(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:100',
            'duration' => 'required|integer|min:1',
            'description' => 'required|string',
            'status' => 'required|in:play_now,coming_soon,history',
            'director' => 'required|string|max:255',
            'release_date' => 'required|date',
            'poster' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $film = Film::findOrFail($id);
            $film->update([
                'title' => $request->title,
                'genre' => $request->genre,
                'duration' => $request->duration,
                'description' => $request->description,
                'status' => $request->status,
                'director' => $request->director,
                'release_date' => $request->release_date,
                'poster' => $request->poster ?? $film->poster
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Film updated successfully',
                'data' => $film
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update film'
            ], 500);
        }
    }

    public function deleteFilm($id)
    {
        try {
            $film = Film::findOrFail($id);
            $film->delete();

            return response()->json([
                'success' => true,
                'message' => 'Film deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete film'
            ], 500);
        }
    }
}