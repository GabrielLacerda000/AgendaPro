<?php

namespace Database\Seeders;

use App\Models\Professional;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfessionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'test@example.com')->first();

        Professional::updateOrCreate([
            'user_id' => $user->id,
        ], [
            'specialty' => 'Psicólogo',
            'bio' => 'Especialista em terapia cognitivo-comportamental.',
        ]);
    }
}
