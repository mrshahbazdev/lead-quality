<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'user_id',
        'contact_id',
        'type',
        'scheduled_at',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
        ];
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
