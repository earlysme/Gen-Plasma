<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            ['code' => 'private_bimbel', 'name' => 'Privat di Bimbel', 'is_private' => true, 'requires_room' => true],
            ['code' => 'private_home', 'name' => 'Privat di Rumah', 'is_private' => true, 'requires_room' => false],
            ['code' => 'reguler', 'name' => 'Reguler', 'is_private' => false, 'requires_room' => true],
            ['code' => 'consult', 'name' => 'Konsultasi', 'is_private' => false, 'requires_room' => true],
        ];

        foreach ($programs as $prog) {
            Program::firstOrCreate(['code' => $prog['code']], $prog);
        }
    }
}
