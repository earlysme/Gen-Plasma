<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'schedule_id',
        'student_id',
        'booked_by_user_id',
        'booking_type',
        'status',
        'cancelled_at',
        'cancel_reason',
    ];

    protected $casts = [
        'cancelled_at' => 'datetime',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function bookedByUser()
    {
        return $this->belongsTo(User::class, 'booked_by_user_id');
    }
}
