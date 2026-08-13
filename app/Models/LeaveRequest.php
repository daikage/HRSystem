<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'start_date',
        'end_date',
        'status',
        'reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Number of calendar days covered by this leave request (inclusive).
     */
    public function getDurationAttribute(): int
    {
        return \Illuminate\Support\Carbon::parse($this->start_date)
            ->diffInDays(\Illuminate\Support\Carbon::parse($this->end_date)) + 1;
    }
}
