<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'school_origin',
        'grade_class',
        'education_level_id',
        'program_id',
        'weekly_frequency',
        'preferred_days',
        'profile_photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * URL Foto Profil Tutor / User.
     */
    public function getProfilePhotoUrlAttribute(): ?string
    {
        if ($this->profile_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->profile_photo)) {
            return asset('storage/' . $this->profile_photo);
        }

        return null;
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'preferred_days' => 'array',
            'weekly_frequency' => 'integer',
        ];
    }

    // Role helper methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTutor(): bool
    {
        return $this->role === 'tutor';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    // Relationships
    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    // Subjects taught by tutor
    public function taughtSubjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_tutor', 'tutor_id', 'subject_id')->withTimestamps();
    }

    // Subjects taken by student
    public function studentSubjects()
    {
        return $this->belongsToMany(Subject::class, 'student_subject', 'student_id', 'subject_id')->withTimestamps();
    }

    public function enrolledSubjects()
    {
        return $this->studentSubjects();
    }

    // Schedules opened by tutor
    public function tutorSchedules()
    {
        return $this->hasMany(Schedule::class, 'tutor_id');
    }

    // Bookings made by / for student
    public function studentBookings()
    {
        return $this->hasMany(Booking::class, 'student_id');
    }
}
