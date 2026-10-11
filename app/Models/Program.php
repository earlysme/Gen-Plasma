<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'is_private',
        'requires_room',
    ];

    protected $casts = [
        'is_private' => 'boolean',
        'requires_room' => 'boolean',
    ];

    public function students()
    {
        return $this->hasMany(User::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
