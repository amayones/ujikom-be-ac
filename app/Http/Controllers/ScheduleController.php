<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Http\Requests\StoreScheduleRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ScheduleController extends BaseController
{
    public function index(): JsonResponse
    {
        $schedules = Schedule::with(['film', 'studio'])
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->get();

        return $this->success($schedules);
    }

    public function store(StoreScheduleRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $schedule = Schedule::create(array_merge($validated, [
            'created_by' => auth()->id(),
        ]));

        return $this->success($schedule->load(['film', 'studio']), 'Jadwal berhasil ditambahkan', 201);
    }

    public function show(Schedule $schedule): JsonResponse
    {
        $schedule->load(['film', 'studio']);
        return $this->success($schedule);
    }

    public function update(StoreScheduleRequest $request, Schedule $schedule): JsonResponse
    {
        $validated = $request->validated();
        $schedule->update($validated);

        return $this->success($schedule->load(['film', 'studio']), 'Jadwal berhasil diupdate');
    }

    public function destroy(Schedule $schedule): JsonResponse
    {
        $schedule->delete();
        return $this->success(null, 'Jadwal berhasil dihapus');
    }
}