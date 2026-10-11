<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'capacity',
        'is_physical',
        'is_active',
    ];

    protected $casts = [
        'is_physical' => 'boolean',
        'is_active' => 'boolean',
        'capacity' => 'integer',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
