<?php

namespace Database\Seeders;

use App\Models\Professional;
use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $professional = Professional::first();

        Service::updateOrCreate([
            'professional_id' => $professional->id,
            'name' => 'Terapia Individual',
        ], [
            'description' => 'Sessão de terapia individual com foco em autoconhecimento.',
            'duration' => 50,
            'price' => 150.00,
        ]);
    }
}
