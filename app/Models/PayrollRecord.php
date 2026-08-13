<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollRecord extends Model
{
    protected $fillable = [
        'user_id',
        'pay_period_start',
        'pay_period_end',
        'base_salary',
        'bonuses',
        'deductions',
        'net_pay',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
