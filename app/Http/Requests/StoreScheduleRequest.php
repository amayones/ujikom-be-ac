<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'film_id' => 'required|exists:films,id',
            'studio_id' => 'required|exists:studios,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'price_id' => 'required|exists:prices,id'
        ];
    }

    public function messages(): array
    {
        return [
            'film_id.required' => 'Film wajib dipilih',
            'film_id.exists' => 'Film tidak ditemukan',
            'studio_id.required' => 'Studio wajib dipilih',
            'studio_id.exists' => 'Studio tidak ditemukan',
            'date.required' => 'Tanggal wajib diisi',
            'date.after_or_equal' => 'Tanggal tidak boleh kurang dari hari ini',
            'time.required' => 'Waktu wajib diisi',
            'time.date_format' => 'Format waktu harus HH:MM',
            'price_id.required' => 'Harga wajib dipilih',
            'price_id.exists' => 'Harga tidak ditemukan'
        ];
    }
}