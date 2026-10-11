<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            EducationLevelSeeder::class,
            ProgramSeeder::class,
            RoomSeeder::class,
            TimeSlotSeeder::class,
            SubjectSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
        ]);
    }
}
