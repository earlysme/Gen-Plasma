<?php

namespace Database\Seeders;

use App\Models\TimeSlot;
use Illuminate\Database\Seeder;

class TimeSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timeSlots = [
            ['session_number' => 1, 'name' => 'Sesi 1', 'start_time' => '14:45:00', 'end_time' => '16:00:00'],
            ['session_number' => 2, 'name' => 'Sesi 2', 'start_time' => '16:15:00', 'end_time' => '17:45:00'],
            ['session_number' => 3, 'name' => 'Sesi 3', 'start_time' => '18:00:00', 'end_time' => '19:30:00'],
            ['session_number' => 4, 'name' => 'Sesi 4', 'start_time' => '19:45:00', 'end_time' => '20:45:00'],
        ];

        foreach ($timeSlots as $slot) {
            TimeSlot::firstOrCreate(['session_number' => $slot['session_number']], $slot);
        }
    }
}
