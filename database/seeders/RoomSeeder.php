<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = [
            ['code' => 'R1', 'name' => 'Ruang 1', 'capacity' => 5, 'is_physical' => true],
            ['code' => 'R2', 'name' => 'Ruang 2', 'capacity' => 5, 'is_physical' => true],
            ['code' => 'R3', 'name' => 'Ruang 3', 'capacity' => 8, 'is_physical' => true],
            ['code' => 'R4', 'name' => 'Ruang 4', 'capacity' => 7, 'is_physical' => true],
            ['code' => 'R5', 'name' => 'Ruang 5', 'capacity' => 8, 'is_physical' => true],
            ['code' => 'R6', 'name' => 'Ruang 6', 'capacity' => 4, 'is_physical' => true],
            ['code' => 'R7', 'name' => 'Ruang 7', 'capacity' => 8, 'is_physical' => true],
            ['code' => 'R8', 'name' => 'Ruang 8', 'capacity' => 10, 'is_physical' => true],
            ['code' => 'EXT_HOME', 'name' => 'Rumah Siswa', 'capacity' => 1, 'is_physical' => false],
        ];

        foreach ($rooms as $room) {
            Room::firstOrCreate(['code' => $room['code']], $room);
        }
    }
}
