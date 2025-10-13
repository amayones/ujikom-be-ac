<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFilmRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'genre' => 'sometimes|string',
            'duration' => 'sometimes|integer|min:1',
            'description' => 'sometimes|string',
            'status' => 'sometimes|in:play_now,coming_soon',
            'poster' => 'nullable|string',
            'director' => 'nullable|string|max:255',
            'release_date' => 'nullable|date'
        ];
    }
}