<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobListing extends Model
{
    protected $fillable = [
        'title',
        'department',
        'location',
        'employment_type',
        'salary_min',
        'salary_max',
        'description',
        'requirements',
        'status',
        'created_by',
    ];

    protected $casts = [
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', 'open');
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    /**
     * A human readable salary range, e.g. "$50,000 - $70,000" or "Competitive".
     */
    public function getSalaryRangeAttribute(): string
    {
        if ($this->salary_min === null && $this->salary_max === null) {
            return 'Competitive';
        }

        $min = $this->salary_min !== null ? '$'.number_format((float) $this->salary_min) : '';
        $max = $this->salary_max !== null ? '$'.number_format((float) $this->salary_max) : '';

        return trim($min . ($min && $max ? ' – ' : '') . $max);
    }
}