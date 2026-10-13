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
            ['session_number' => 1, 'name' => 'Sesi 1', 'start_time' => '13:00:00', 'end_time' => '14:15:00', 'is_active' => true],
            ['session_number' => 2, 'name' => 'Sesi 2', 'start_time' => '14:15:00', 'end_time' => '15:30:00', 'is_active' => true],
            ['session_number' => 3, 'name' => 'Sesi 3', 'start_time' => '15:30:00', 'end_time' => '16:45:00', 'is_active' => true],
            ['session_number' => 4, 'name' => 'Sesi 4', 'start_time' => '16:45:00', 'end_time' => '18:00:00', 'is_active' => true],
            ['session_number' => 5, 'name' => 'Sesi 5', 'start_time' => '18:00:00', 'end_time' => '19:15:00', 'is_active' => true],
            ['session_number' => 6, 'name' => 'Sesi 6', 'start_time' => '19:15:00', 'end_time' => '20:30:00', 'is_active' => true],
            ['session_number' => 7, 'name' => 'Sesi 7', 'start_time' => '09:00:00', 'end_time' => '10:15:00', 'is_active' => true],
            ['session_number' => 8, 'name' => 'Sesi 8', 'start_time' => '10:15:00', 'end_time' => '11:30:00', 'is_active' => true],
            ['session_number' => 9, 'name' => 'Sesi 9', 'start_time' => '11:30:00', 'end_time' => '12:45:00', 'is_active' => true],
        ];

        // Nonaktifkan atau bersihkan sesi di atas sesi 9 jika ada
        TimeSlot::where('session_number', '>', 9)->delete();

        foreach ($timeSlots as $slot) {
            TimeSlot::updateOrCreate(['session_number' => $slot['session_number']], $slot);
        }
    }
}
