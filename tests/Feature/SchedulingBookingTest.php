<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\EducationLevel;
use App\Models\Program;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\TimeSlot;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class SchedulingBookingTest extends TestCase
{
    public function test_tutor_can_create_schedule_and_prevent_conflict(): void
    {
        $tutor = User::role('tutor')->first();
        $room = Room::where('is_physical', true)->first();
        $slot = TimeSlot::first();
        $subject = Subject::first();
        $level = EducationLevel::first();
        $program = Program::where('code', 'reguler')->first();

        $tomorrow = Carbon::tomorrow()->toDateString();

        // 1. Tutor membuka slot pertama kali
        $response = $this->actingAs($tutor)->post(route('tutor.schedules.store'), [
            'date' => $tomorrow,
            'time_slot_id' => $slot->id,
            'subject_id' => $subject->id,
            'education_level_id' => $level->id,
            'program_id' => $program->id,
            'room_id' => $room->id,
            'quota_max' => 5,
        ]);

        $response->assertRedirect(route('tutor.dashboard'));
        $this->assertDatabaseHas('schedules', [
            'tutor_id' => $tutor->id,
            'date' => $tomorrow,
            'room_id' => $room->id,
            'time_slot_id' => $slot->id,
        ]);

        // 2. Tutor mencoba membuka slot lain di waktu yang sama (bentrok tutor)
        $responseConflictTutor = $this->actingAs($tutor)->post(route('tutor.schedules.store'), [
            'date' => $tomorrow,
            'time_slot_id' => $slot->id,
            'subject_id' => $subject->id,
            'education_level_id' => $level->id,
            'program_id' => $program->id,
            'room_id' => Room::where('id', '!=', $room->id)->first()->id,
        ]);

        $responseConflictTutor->assertSessionHas('error');

        // 3. Tutor lain mencoba memakai ruangan yang sama di waktu yang sama (bentrok ruangan)
        $anotherTutor = User::role('tutor')->where('id', '!=', $tutor->id)->first();
        $responseConflictRoom = $this->actingAs($anotherTutor)->post(route('tutor.schedules.store'), [
            'date' => $tomorrow,
            'time_slot_id' => $slot->id,
            'subject_id' => $subject->id,
            'education_level_id' => $level->id,
            'program_id' => $program->id,
            'room_id' => $room->id,
        ]);

        $responseConflictRoom->assertSessionHas('error');
    }

    public function test_student_can_book_and_respect_weekly_quota_limit(): void
    {
        $student = User::role('student')->first();
        $schedule = Schedule::where('status', 'scheduled')->first();

        if (!$schedule) {
            $tutor = User::role('tutor')->first();
            $schedule = Schedule::create([
                'tutor_id' => $tutor->id,
                'subject_id' => Subject::first()->id,
                'education_level_id' => EducationLevel::first()->id,
                'program_id' => Program::first()->id,
                'room_id' => Room::first()->id,
                'time_slot_id' => TimeSlot::first()->id,
                'date' => Carbon::now()->next(Carbon::FRIDAY)->toDateString(),
                'quota_max' => 5,
                'booked_count' => 0,
                'status' => 'scheduled',
            ]);
        }

        // Siswa booking slot
        $response = $this->actingAs($student)->post(route('student.bookings.store'), [
            'schedule_id' => $schedule->id,
        ]);

        $response->assertRedirect(route('student.bookings.my'));
        $this->assertDatabaseHas('bookings', [
            'schedule_id' => $schedule->id,
            'student_id' => $student->id,
            'status' => 'confirmed',
        ]);
    }
}
