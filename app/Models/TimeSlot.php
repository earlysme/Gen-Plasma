<?php

namespace App\Models;

use Carbon\Carbon;
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

    protected $appends = ['is_saturday_only'];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function getIsSaturdayOnlyAttribute(): bool
    {
        return $this->session_number >= 7;
    }

    /**
     * Ambil slot waktu aktif yang sesuai dengan hari pada tanggal tersebut.
     * Sesi 7 s/d 9 (09:00 - 12:45) khusus Sabtu; Senin-Jumat hanya Sesi 1 s/d 6 (13:00 - 20:30).
     */
    public static function forDate(Carbon|string $date)
    {
        $isSaturday = Carbon::parse($date)->isSaturday();
        $query = static::where('is_active', true);

        if (! $isSaturday) {
            $query->where('session_number', '<=', 6);
        }

        return $query->orderBy('start_time')->get();
    }
}
