<?php

return [
    'required' => ':attribute harus diisi.',
    'email' => ':attribute harus berupa alamat email yang valid.',
    'min' => [
        'string' => ':attribute minimal :min karakter.',
    ],
    'max' => [
        'string' => ':attribute maksimal :max karakter.',
    ],
    'unique' => ':attribute sudah digunakan.',
    'regex' => 'Format :attribute tidak valid.',
    'numeric' => ':attribute harus berupa angka.',
    'integer' => ':attribute harus berupa bilangan bulat.',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',

    'attributes' => [
        'name' => 'nama',
        'email' => 'email',
        'password' => 'password',
        'phone' => 'nomor telepon',
        'address' => 'alamat',
        'title' => 'judul',
        'genre' => 'genre',
        'duration' => 'durasi',
        'description' => 'deskripsi',
        'price' => 'harga',
        'date' => 'tanggal',
        'time' => 'waktu',
    ],
];