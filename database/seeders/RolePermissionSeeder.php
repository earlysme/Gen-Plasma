<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Buat Daftar Permissions
        $permissions = [
            // Master Data
            'manage rooms',
            'manage education levels',
            'manage time slots',
            'manage programs',
            'manage subjects',
            'manage users',

            // Scheduling (Pengajar / Tutor)
            'view schedules',
            'create schedule',
            'edit schedule',
            'delete schedule',
            'complete schedule',
            'assign student to schedule',

            // Booking (Murid / Siswa)
            'view available slots',
            'book slot',
            'cancel booking',

            // Dashboard
            'view admin dashboard',
            'view tutor dashboard',
            'view student dashboard',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        // 2. Buat Roles
        $roleAdmin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $roleTutor = Role::firstOrCreate(['name' => 'tutor', 'guard_name' => 'web']);
        $roleStudent = Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);

        // 3. Mapping Permissions ke Roles
        // Admin: Semua permissions
        $roleAdmin->syncPermissions(Permission::all());

        // Tutor: Buka slot, plotting murid, update status sesi, isi materi
        $roleTutor->syncPermissions([
            'view schedules',
            'create schedule',
            'edit schedule',
            'complete schedule',
            'assign student to schedule',
            'cancel booking',
            'view tutor dashboard',
        ]);

        // Student: Eksplorasi slot, booking, batalkan booking H-1
        $roleStudent->syncPermissions([
            'view available slots',
            'book slot',
            'cancel booking',
            'view student dashboard',
        ]);

        // 4. Sinkronisasi Role ke User yang Sudah Ada Berdasarkan Kolom role
        $users = User::all();
        foreach ($users as $user) {
            if ($user->role === 'admin') {
                $user->assignRole($roleAdmin);
            } elseif ($user->role === 'tutor') {
                $user->assignRole($roleTutor);
            } elseif ($user->role === 'student') {
                $user->assignRole($roleStudent);
            }
        }
    }
}
