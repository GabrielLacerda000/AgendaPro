<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate([
            'email' => 'paciente@example.com',
        ], [
            'name' => 'Paciente Teste',
            'password' => bcrypt('password'),
        ]);

        Patient::updateOrCreate([
            'user_id' => $user->id,
        ]);
    }
}
