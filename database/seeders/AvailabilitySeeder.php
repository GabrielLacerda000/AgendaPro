<?php

namespace Database\Seeders;

use App\Models\Availability;
use App\Models\Professional;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AvailabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $professional = Professional::first();

        // Monday
        Availability::updateOrCreate([
            'professional_id' => $professional->id,
            'day_of_week' => 1,
        ], [
            'start_time' => '09:00:00',
            'end_time' => '18:00:00',
        ]);

        // Wednesday
        Availability::updateOrCreate([
            'professional_id' => $professional->id,
            'day_of_week' => 3,
        ], [
            'start_time' => '09:00:00',
            'end_time' => '18:00:00',
        ]);

        // Friday
        Availability::updateOrCreate([
            'professional_id' => $professional->id,
            'day_of_week' => 5,
        ], [
            'start_time' => '09:00:00',
            'end_time' => '18:00:00',
        ]);
    }
}
