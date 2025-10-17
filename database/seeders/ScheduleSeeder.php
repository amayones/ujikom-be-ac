<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;
use App\Models\Film;
use App\Models\Studio;
use Carbon\Carbon;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $films = Film::where('status', 'now_playing')->get();
        $studios = Studio::all();
        $times = ['10:00:00', '13:00:00', '16:00:00', '19:00:00', '21:30:00'];
        
        // Generate schedules for next 7 days
        for ($day = 0; $day < 7; $day++) {
            $date = Carbon::now()->addDays($day);
            
            foreach ($films as $film) {
                // Each film gets 2-3 showtimes per day
                $filmTimes = collect($times)->random(rand(2, 3));
                
                foreach ($filmTimes as $time) {
                    $studio = $studios->random();
                    
                    Schedule::firstOrCreate([
                        'film_id' => $film->id,
                        'studio_id' => $studio->id,
                        'show_date' => $date->format('Y-m-d'),
                        'show_time' => $time
                    ], [
                        'film_id' => $film->id,
                        'studio_id' => $studio->id,
                        'show_date' => $date->format('Y-m-d'),
                        'show_time' => $time,
                        'price' => $this->calculatePrice($studio->type, $date),
                        'available_seats' => $studio->capacity - rand(0, 10) // Some seats already booked
                    ]);
                }
            }
        }
    }
    
    private function calculatePrice($studioType, $date)
    {
        $isWeekend = $date->isWeekend();
        $dayType = $isWeekend ? 'weekend' : 'weekday';
        
        $basePrices = [
            'Regular' => $isWeekend ? 55000 : 45000,
            'Premium' => $isWeekend ? 75000 : 65000,
            'VIP' => $isWeekend ? 100000 : 85000
        ];
        
        return $basePrices[$studioType] ?? 50000;
    }
}