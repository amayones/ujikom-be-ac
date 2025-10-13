<?php

namespace App\Http\Controllers;

use App\Models\Price;
use Illuminate\Http\JsonResponse;

class PriceController extends BaseController
{
    public function index(): JsonResponse
    {
        $prices = Price::all();
        return $this->success($prices);
    }
}