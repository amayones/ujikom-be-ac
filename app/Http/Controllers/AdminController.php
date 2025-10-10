<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function getFilms()
    {
        try {
            // Try to get films from database
            $films = Film::orderBy('created_at', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $films,
                'count' => $films->count()
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching films: ' . $e->getMessage());
            
            // If database error, try to run migrations and seed
            try {
                \Artisan::call('migrate:fresh', ['--force' => true]);
                \Artisan::call('db:seed', ['--force' => true]);
                
                // Try again after migration
                $films = Film::orderBy('created_at', 'desc')->get();
                
                return response()->json([
                    'success' => true,
                    'data' => $films,
                    'count' => $films->count(),
                    'message' => 'Database reset and films loaded'
                ]);
            } catch (\Exception $e2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Database error: ' . $e2->getMessage()
                ], 500);
            }
        }
    }
    
    public function getGenres()
    {
        try {
            $genres = Genre::orderBy('name')->get();
            return response()->json([
                'success' => true,
                'data' => $genres
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch genres'
            ], 500);
        }
    }
    
    public function testConnection()
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Backend connection successful',
                'timestamp' => now(),
                'database' => \DB::connection()->getPdo() ? 'Connected' : 'Disconnected'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Backend connection failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeFilm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'genre' => 'required|string',
            'duration' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'required|in:play_now,coming_soon,history',
            'director' => 'nullable|string|max:255',
            'release_date' => 'nullable|date',
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
                'description' => $request->description ?? '',
                'status' => $request->status,
                'director' => $request->director ?? 'Unknown',
                'release_date' => $request->release_date ?? now()->format('Y-m-d'),
                'poster' => $request->poster ?? 'https://be-ujikom.amayones.my.id/ac.jpg',
                'created_by' => 1
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Film created successfully',
                'data' => $film
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Error creating film: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create film: ' . $e->getMessage()
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