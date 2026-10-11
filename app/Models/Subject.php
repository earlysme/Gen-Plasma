<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
    ];

    public function tutors()
    {
        return $this->belongsToMany(User::class, 'subject_tutor', 'subject_id', 'tutor_id')->withTimestamps();
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'student_subject', 'subject_id', 'student_id')->withTimestamps();
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
