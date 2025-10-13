<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFilmRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'genre' => 'required|string',
            'duration' => 'required|integer|min:1',
            'description' => 'required|string',
            'status' => 'required|in:play_now,coming_soon',
            'poster' => 'nullable|string',
            'director' => 'nullable|string|max:255',
            'release_date' => 'nullable|date'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul film wajib diisi',
            'genre.required' => 'Genre film wajib diisi',
            'duration.required' => 'Durasi film wajib diisi',
            'duration.min' => 'Durasi film minimal 1 menit',
            'description.required' => 'Deskripsi film wajib diisi',
            'status.required' => 'Status film wajib diisi',
            'status.in' => 'Status film harus play_now atau coming_soon'
        ];
    }
}