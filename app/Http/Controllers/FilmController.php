<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Http\Requests\StoreFilmRequest;
use App\Http\Requests\UpdateFilmRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class FilmController extends BaseController
{
    public function index(): JsonResponse
    {
        $films = Film::with('creator')->orderBy('created_at', 'desc')->get();
        return $this->success($films);
    }

    public function store(StoreFilmRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $film = Film::create(array_merge($validated, ['created_by' => Auth::id()]));

        return $this->success($film, 'Film berhasil ditambahkan', 201);
    }

    public function show(Film $film): JsonResponse
    {
        return $this->success($film->load('creator'));
    }

    public function update(UpdateFilmRequest $request, Film $film): JsonResponse
    {
        $validated = $request->validated();
        $film->update($validated);

        return $this->success($film, 'Film berhasil diupdate');
    }

    public function destroy(Film $film): JsonResponse
    {
        if ($film->schedules()->exists()) {
            return $this->error('Film masih memiliki jadwal tayang', 400);
        }
        
        $film->delete();
        return $this->success(null, 'Film berhasil dihapus');
    }
}
