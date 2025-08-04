<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Professional;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $professional = Professional::first();
        $patient = Patient::first();
        $service = Service::first();

        Appointment::updateOrCreate([
            'professional_id' => $professional->id,
            'patient_id' => $patient->id,
            'service_id' => $service->id,
            'start_time' => Carbon::now()->addDay()->setHour(10),
        ], [
            'end_time' => Carbon::now()->addDay()->setHour(10)->addMinutes($service->duration),
            'status' => 'scheduled',
        ]);
    }
}
