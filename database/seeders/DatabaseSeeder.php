<?php

namespace Database\Seeders;

use App\Models\EducationLevel;
use App\Models\Program;
use App\Models\Room;
use App\Models\Subject;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Jenjang Pendidikan
        $sd = EducationLevel::firstOrCreate(['code' => 'sd'], [
            'name' => 'SD',
            'duration_minutes' => 60,
        ]);
        $smp = EducationLevel::firstOrCreate(['code' => 'smp'], [
            'name' => 'SMP',
            'duration_minutes' => 75,
        ]);
        $sma = EducationLevel::firstOrCreate(['code' => 'sma'], [
            'name' => 'SMA',
            'duration_minutes' => 75,
        ]);

        // 2. Seed Programs
        $progPrivatBimbel = Program::firstOrCreate(['code' => 'private_bimbel'], [
            'name' => 'Privat di Bimbel',
            'is_private' => true,
            'requires_room' => true,
        ]);
        $progPrivatRumah = Program::firstOrCreate(['code' => 'private_home'], [
            'name' => 'Privat di Rumah',
            'is_private' => true,
            'requires_room' => false,
        ]);
        $progReguler = Program::firstOrCreate(['code' => 'reguler'], [
            'name' => 'Reguler',
            'is_private' => false,
            'requires_room' => true,
        ]);
        $progKonsul = Program::firstOrCreate(['code' => 'consult'], [
            'name' => 'Konsultasi',
            'is_private' => false,
            'requires_room' => true,
        ]);

        // 3. Seed Ruangan (R1 - R8 + Rumah Siswa)
        $roomsData = [
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
        foreach ($roomsData as $r) {
            Room::firstOrCreate(['code' => $r['code']], $r);
        }

        // 4. Seed Sesi Waktu (Sesi 1 - 4)
        $timeSlotsData = [
            ['session_number' => 1, 'name' => 'Sesi 1', 'start_time' => '14:45:00', 'end_time' => '16:00:00'],
            ['session_number' => 2, 'name' => 'Sesi 2', 'start_time' => '16:15:00', 'end_time' => '17:45:00'],
            ['session_number' => 3, 'name' => 'Sesi 3', 'start_time' => '18:00:00', 'end_time' => '19:30:00'],
            ['session_number' => 4, 'name' => 'Sesi 4', 'start_time' => '19:45:00', 'end_time' => '20:45:00'],
        ];
        foreach ($timeSlotsData as $t) {
            TimeSlot::firstOrCreate(['session_number' => $t['session_number']], $t);
        }

        // 5. Seed Mata Pelajaran
        $subjectsData = [
            ['code' => 'ENG', 'name' => 'Bahasa Inggris'],
            ['code' => 'IND', 'name' => 'Bahasa Indonesia'],
            ['code' => 'MTK', 'name' => 'Matematika'],
            ['code' => 'GEO', 'name' => 'Geografi'],
            ['code' => 'SOC', 'name' => 'Sosiologi'],
            ['code' => 'EKO', 'name' => 'Ekonomi'],
            ['code' => 'AKT', 'name' => 'Akuntansi'],
            ['code' => 'KIM', 'name' => 'Kimia'],
        ];
        $subjects = [];
        foreach ($subjectsData as $s) {
            $subjects[$s['code']] = Subject::firstOrCreate(['code' => $s['code']], $s);
        }

        // 6. Seed Admin
        User::firstOrCreate(
            ['email' => 'admin@bimbel.test'],
            [
                'name' => 'Admin Bimbel',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '081234567890',
            ]
        );

        // 7. Seed Tutor & Pemetaan Mapel
        $tutorMapping = [
            'ENG' => ['Zaki', 'Sitey', 'Ainy', 'Fina', 'Acep', 'Cahya'],
            'IND' => ['Fahmi', 'Alifa', 'Nurul'],
            'MTK' => ['Resha', 'Ajun', 'Resti', 'Isma'],
            'GEO' => ['Ira'],
            'SOC' => ['Ira', 'Acep'],
            'EKO' => ['Selva'],
            'AKT' => ['Selva'],
            'KIM' => ['Ayuni'],
        ];

        $createdTutors = [];
        foreach ($tutorMapping as $subjCode => $tutorNames) {
            $subjectModel = $subjects[$subjCode];
            foreach ($tutorNames as $name) {
                if (!isset($createdTutors[$name])) {
                    $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $name));
                    $tutor = User::firstOrCreate(
                        ['email' => "{$slug}@bimbel.test"],
                        [
                            'name' => $name,
                            'password' => Hash::make('password'),
                            'role' => 'tutor',
                            'phone' => '08' . rand(1111111111, 9999999999),
                        ]
                    );
                    $createdTutors[$name] = $tutor;
                }
                // Attach to subject if not attached
                $tutor = $createdTutors[$name];
                if (!$tutor->taughtSubjects()->where('subject_id', $subjectModel->id)->exists()) {
                    $tutor->taughtSubjects()->attach($subjectModel->id);
                }
            }
        }

        // 8. Seed Sample Murid (lengkap 8 data profil)
        $sampleStudents = [
            [
                'name' => 'Ahmad Budi',
                'email' => 'budi@siswa.test',
                'school_origin' => 'SMAN 1 Kota',
                'grade_class' => 'Kelas 12',
                'education_level_id' => $sma->id,
                'program_id' => $progReguler->id,
                'weekly_frequency' => 2,
                'preferred_days' => ['Senin', 'Kamis'],
                'phone' => '08129990001',
                'address' => 'Jl. Merdeka No. 10',
                'subjects' => [$subjects['MTK']->id, $subjects['ENG']->id],
            ],
            [
                'name' => 'Citra Lestari',
                'email' => 'citra@siswa.test',
                'school_origin' => 'SMPN 3 Kota',
                'grade_class' => 'Kelas 8',
                'education_level_id' => $smp->id,
                'program_id' => $progPrivatRumah->id,
                'weekly_frequency' => 2,
                'preferred_days' => ['Selasa', 'Jumat'],
                'phone' => '08129990002',
                'address' => 'Komplek Permata Blok B2 No. 5',
                'subjects' => [$subjects['MTK']->id, $subjects['IND']->id],
            ],
            [
                'name' => 'Dimas Pratama',
                'email' => 'dimas@siswa.test',
                'school_origin' => 'SDN Cemerlang 1',
                'grade_class' => 'Kelas 5',
                'education_level_id' => $sd->id,
                'program_id' => $progPrivatBimbel->id,
                'weekly_frequency' => 2,
                'preferred_days' => ['Rabu', 'Sabtu'],
                'phone' => '08129990003',
                'address' => 'Jl. Melati No. 12',
                'subjects' => [$subjects['MTK']->id],
            ],
        ];

        foreach ($sampleStudents as $studentData) {
            $subjIds = $studentData['subjects'];
            unset($studentData['subjects']);
            $student = User::firstOrCreate(
                ['email' => $studentData['email']],
                array_merge($studentData, [
                    'password' => Hash::make('password'),
                    'role' => 'student',
                ])
            );
            $student->studentSubjects()->sync($subjIds);
        }
    }
}
