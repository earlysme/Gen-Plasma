<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            ['code' => 'ENG', 'name' => 'Bahasa Inggris'],
            ['code' => 'IND', 'name' => 'Bahasa Indonesia'],
            ['code' => 'MTK', 'name' => 'Matematika'],
            ['code' => 'GEO', 'name' => 'Geografi'],
            ['code' => 'SOC', 'name' => 'Sosiologi'],
            ['code' => 'EKO', 'name' => 'Ekonomi'],
            ['code' => 'AKT', 'name' => 'Akuntansi'],
            ['code' => 'KIM', 'name' => 'Kimia'],
        ];

        foreach ($subjects as $subject) {
            Subject::firstOrCreate(['code' => $subject['code']], $subject);
        }
    }
}
