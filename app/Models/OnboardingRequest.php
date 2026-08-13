<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnboardingRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'first_name',
        'last_name',
        'email',
        'credentials_data',
        'status',
        'admin_feedback',
    ];

    protected $casts = [
        'credentials_data' => 'array',
    ];
}
