<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'company',
        'position',
        'status',
        'industry',
        'role',
        'source',
        'budget',
        'budget_range',
        'employee_count_range',
        'priority',
        'last_interaction_at',
        'tags',
        'notes',
        'email',
        'website',
        'linkedin',
        'ai_high_probability',
        'pipeline_stage',
        'team_id',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'json',
            'last_interaction_at' => 'datetime',
            'budget' => 'decimal:2',
        ];
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function sequences()
    {
        return $this->belongsToMany(Sequence::class, 'contact_sequence')
            ->withPivot('current_step_id', 'next_run_at', 'status')
            ->withTimestamps();
    }
}
