<?php

namespace Database\Seeders;

use App\Models\EducationLevel;
use App\Models\Program;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('password');

        // Pastikan Role Spatie Tersedia
        $roleAdmin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $roleTutor = Role::firstOrCreate(['name' => 'tutor', 'guard_name' => 'web']);
        $roleStudent = Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);

        // Cache Master
        $sd = EducationLevel::where('code', 'sd')->first();
        $smp = EducationLevel::where('code', 'smp')->first();
        $sma = EducationLevel::where('code', 'sma')->first();

        $progReguler = Program::where('code', 'reguler')->first();
        $progPrivatBimbel = Program::where('code', 'private_bimbel')->first();
        $progPrivatRumah = Program::where('code', 'private_home')->first();

        $subjects = Subject::all()->keyBy('code');

        // ========================================================
        // 1. SEED ADMIN
        // ========================================================
        $admin = User::updateOrCreate(
            ['email' => 'admin@bimbel.test'],
            [
                'name' => 'Admin Bimbel Quadra',
                'password' => $defaultPassword,
                'role' => 'admin',
                'phone' => '081234567890',
            ]
        );
        $admin->syncRoles([$roleAdmin]);

        // ========================================================
        // 2. SEED TUTOR / PENGAJAR (Data Riil dari Excel Audit)
        // ========================================================
        $tutorsData = [
            [
                'name' => 'Fahmi Idrus Salam',
                'email' => 'idrussalam1503@gmail.com',
                'phone' => '08121111001',
                'subjects' => ['IND'],
            ],
            [
                'name' => 'Muhammad Zaki',
                'email' => 'muhammad.zaki.id34@gmail.com',
                'phone' => '08121111002',
                'subjects' => ['ENG'],
            ],
            [
                'name' => 'Siti Nurhayati Halimatu Sya\'diah',
                'email' => 'sitinurhayati.work1@gmail.com',
                'phone' => '08121111003',
                'subjects' => ['ENG'],
            ],
            [
                'name' => 'Resti Lestari',
                'email' => 'lestariresti312@gmail.com',
                'phone' => '08121111004',
                'subjects' => ['MTK'],
            ],
            [
                'name' => 'Isma Salsabila',
                'email' => 'salsabilaofficial32@gmail.com',
                'phone' => '08121111005',
                'subjects' => ['MTK'],
            ],
            [
                'name' => 'Resha Robiatul AJ',
                'email' => 'resharobiatulaj2@gmail.com',
                'phone' => '08121111006',
                'subjects' => ['MTK'],
            ],
            [
                'name' => 'Ayuni Maulidiya',
                'email' => 'ayunimaulidiya@gmail.com',
                'phone' => '08121111007',
                'subjects' => ['KIM'],
            ],
            [
                'name' => 'Fina Levina Sahira',
                'email' => 'finalevina5@gmail.com',
                'phone' => '08121111008',
                'subjects' => ['ENG'],
            ],
            [
                'name' => 'Alifa Yustianisa',
                'email' => 'alif.ayst@gmail.com',
                'phone' => '08121111009',
                'subjects' => ['IND'],
            ],
            [
                'name' => 'Selva Ayuni',
                'email' => 'ayuniselva53@gmail.com',
                'phone' => '08121111010',
                'subjects' => ['EKO', 'AKT'],
            ],
            [
                'name' => 'Ira Rahayu Yunita',
                'email' => 'rahayuira16@gmail.com',
                'phone' => '08121111011',
                'subjects' => ['GEO', 'SOC'],
            ],
            [
                'name' => 'Acep Muhammad Abdullatif',
                'email' => 'acepkasep321@gmail.com',
                'phone' => '08121111012',
                'subjects' => ['ENG', 'SOC'],
            ],
            [
                'name' => 'Nurul Rahmawati',
                'email' => 'rahmawatinurul842@gmail.com',
                'phone' => '08121111013',
                'subjects' => ['IND'],
            ],
        ];

        foreach ($tutorsData as $tData) {
            $tutor = User::updateOrCreate(
                ['email' => $tData['email']],
                [
                    'name' => $tData['name'],
                    'password' => $defaultPassword,
                    'role' => 'tutor',
                    'phone' => $tData['phone'],
                ]
            );
            $tutor->syncRoles([$roleTutor]);

            // Sync keahlian mata pelajaran
            $subjectIds = [];
            foreach ($tData['subjects'] as $sCode) {
                if (isset($subjects[$sCode])) {
                    $subjectIds[] = $subjects[$sCode]->id;
                }
            }
            $tutor->taughtSubjects()->sync($subjectIds);
        }

        // ========================================================
        // 3. SEED SAMPEL MURID / SISWA (Data Riil dari Excel Booking)
        // ========================================================
        $studentsData = [
            [
                'name' => 'Kania Nur Fauzyah',
                'email' => 'nurfauzyahk@gmail.com',
                'school_origin' => 'SMA Negeri 2 Cianjur',
                'grade_class' => 'Kelas 12',
                'education_level_id' => $sma?->id,
                'program_id' => $progReguler?->id,
                'weekly_frequency' => 2,
                'preferred_days' => ['Senin', 'Rabu'],
                'phone' => '08219900001',
                'address' => 'Cianjur',
                'subjects' => ['SOC', 'ENG'],
            ],
            [
                'name' => 'Zahra Pratama',
                'email' => 'zetpe6@gmail.com',
                'school_origin' => 'SMAN 1 Cianjur',
                'grade_class' => 'Kelas 12',
                'education_level_id' => $sma?->id,
                'program_id' => $progReguler?->id,
                'weekly_frequency' => 2,
                'preferred_days' => ['Rabu', 'Jumat'],
                'phone' => '08219900002',
                'address' => 'Cianjur',
                'subjects' => ['SOC', 'GEO'],
            ],
            [
                'name' => 'Nazmi Sabrina',
                'email' => 'sabrinanazmi70@gmail.com',
                'school_origin' => 'SMK 1 Cianjur',
                'grade_class' => 'Kelas 11',
                'education_level_id' => $sma?->id,
                'program_id' => $progReguler?->id,
                'weekly_frequency' => 2,
                'preferred_days' => ['Kamis', 'Jumat'],
                'phone' => '08219900003',
                'address' => 'Cianjur',
                'subjects' => ['AKT', 'ENG'],
            ],
            [
                'name' => 'Shayna Prisscilla Putri Alam',
                'email' => 'priascillaputrialam@gmail.com',
                'school_origin' => 'SMPN 1 Cianjur',
                'grade_class' => 'Kelas 9',
                'education_level_id' => $smp?->id,
                'program_id' => $progReguler?->id,
                'weekly_frequency' => 2,
                'preferred_days' => ['Selasa', 'Jumat'],
                'phone' => '08219900004',
                'address' => 'Cianjur',
                'subjects' => ['ENG', 'MTK'],
            ],
            [
                'name' => 'Adila Hasna Nabila',
                'email' => 'adilahasnanabila97@gmail.com',
                'school_origin' => 'SMP Almamoen',
                'grade_class' => 'Kelas 9',
                'education_level_id' => $smp?->id,
                'program_id' => $progReguler?->id,
                'weekly_frequency' => 2,
                'preferred_days' => ['Kamis', 'Sabtu'],
                'phone' => '08219900005',
                'address' => 'Cianjur',
                'subjects' => ['IND', 'MTK'],
            ],
            [
                'name' => 'Salsabila Nurarini',
                'email' => 'shlshbl20@gmail.com',
                'school_origin' => 'SMP Negeri 1 Cianjur',
                'grade_class' => 'Kelas 8',
                'education_level_id' => $smp?->id,
                'program_id' => $progReguler?->id,
                'weekly_frequency' => 2,
                'preferred_days' => ['Rabu', 'Sabtu'],
                'phone' => '08219900006',
                'address' => 'Cianjur',
                'subjects' => ['MTK', 'ENG'],
            ],
        ];

        foreach ($studentsData as $sData) {
            $subjCodes = $sData['subjects'];
            unset($sData['subjects']);

            $student = User::updateOrCreate(
                ['email' => $sData['email']],
                array_merge($sData, [
                    'password' => $defaultPassword,
                    'role' => 'student',
                ])
            );
            $student->syncRoles([$roleStudent]);

            // Sync mata pelajaran yang diambil siswa
            $subjectIds = [];
            foreach ($subjCodes as $sCode) {
                if (isset($subjects[$sCode])) {
                    $subjectIds[] = $subjects[$sCode]->id;
                }
            }
            $student->studentSubjects()->sync($subjectIds);
        }
    }
}
