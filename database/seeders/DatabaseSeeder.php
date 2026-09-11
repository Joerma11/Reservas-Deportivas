<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Field;
use App\Models\Schedule;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'cliente@test.com'],
            ['name' => 'Cliente Demo', 'password' => bcrypt('12345678')]
        );

        // Cancha de Tenis A: 2 horas (120 min) a $10.000 el bloque
        $tenisA = Field::create([
            'name' => 'Tenis Central (2h)',
            'sport_type' => 'Tenis',
            'price_per_hour' => 10000,
            'slot_duration' => 120, // 2 horas
            'is_active' => true,
        ]);

        // Cancha de Tenis B: 1 hora y 30 min (90 min) a $6.000 el bloque
        $tenisB = Field::create([
            'name' => 'Tenis Anexo (1h 30m)',
            'sport_type' => 'Tenis',
            'price_per_hour' => 6000,
            'slot_duration' => 90, // 1 hora y media
            'is_active' => true,
        ]);

        // Horarios estándar (Lunes a Domingo de 09:00 a 22:00)
        foreach (range(0, 6) as $day) {
            Schedule::create([
                'field_id' => $tenisA->id,
                'day_of_week' => $day,
                'start_time' => '09:00:00',
                'end_time' => '22:00:00',
            ]);

            Schedule::create([
                'field_id' => $tenisB->id,
                'day_of_week' => $day,
                'start_time' => '09:00:00',
                'end_time' => '22:00:00',
            ]);
        }
    }
}