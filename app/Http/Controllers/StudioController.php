<?php

namespace App\Http\Controllers;

use App\Models\Studio;
use Illuminate\Http\JsonResponse;

class StudioController extends BaseController
{
    public function index(): JsonResponse
    {
        $studios = Studio::all();
        return $this->success($studios);
    }
}