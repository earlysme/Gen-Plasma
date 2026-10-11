<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_number',
        'name',
        'start_time',
        'end_time',
        'is_active',
    ];

    protected $casts = [
        'session_number' => 'integer',
        'is_active' => 'boolean',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
