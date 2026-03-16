<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IcpProfile extends Model
{
    protected $fillable = [
        'user_id',
        'industry',
        'employee_count_range',
        'budget_min',
        'budget_max',
        'role',
        'location',
    ];

    protected function casts(): array
    {
        return [
            'budget_min' => 'decimal:2',
            'budget_max' => 'decimal:2',
        ];
    }
}
