<?php

namespace Database\Seeders;

use App\Models\EducationLevel;
use Illuminate\Database\Seeder;

class EducationLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            ['code' => 'sd', 'name' => 'SD', 'duration_minutes' => 60],
            ['code' => 'smp', 'name' => 'SMP', 'duration_minutes' => 75],
            ['code' => 'sma', 'name' => 'SMA', 'duration_minutes' => 75],
        ];

        foreach ($levels as $level) {
            EducationLevel::firstOrCreate(['code' => $level['code']], $level);
        }
    }
}
