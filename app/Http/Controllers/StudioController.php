<?php

namespace App\Http\Controllers;

use App\Models\Studio;
use Illuminate\Http\Request;

class StudioController extends Controller
{
    public function index()
    {
        $studios = Studio::orderBy('name')->get();
        
        return response()->json([
            'success' => true,
            'message' => 'Studios retrieved successfully',
            'data' => $studios
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1'
        ]);
        
        $validated['created_by'] = $request->user()->id ?? 1;
        $studio = Studio::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Studio created successfully',
            'data' => $studio
        ], 201);
    }

    public function show(Studio $studio)
    {
        return response()->json([
            'success' => true,
            'message' => 'Studio retrieved successfully',
            'data' => $studio
        ]);
    }

    public function update(Request $request, Studio $studio)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'capacity' => 'sometimes|integer|min:1'
        ]);
        
        $studio->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Studio updated successfully',
            'data' => $studio->fresh()
        ]);
    }

    public function destroy(Studio $studio)
    {
        $studio->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Studio deleted successfully',
            'data' => null
        ]);
    }
}