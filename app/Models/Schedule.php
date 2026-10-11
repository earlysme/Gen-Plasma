<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'tutor_id',
        'subject_id',
        'education_level_id',
        'program_id',
        'room_id',
        'time_slot_id',
        'date',
        'quota_max',
        'booked_count',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'quota_max' => 'integer',
        'booked_count' => 'integer',
    ];

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function activeBookings()
    {
        return $this->hasMany(Booking::class)->where('status', 'confirmed');
    }

    public function getRemainingSeatsAttribute(): int
    {
        return max(0, $this->quota_max - $this->booked_count);
    }

    public function isFull(): bool
    {
        return $this->booked_count >= $this->quota_max;
    }
}
