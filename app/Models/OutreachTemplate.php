<?php

namespace App\Models;

use App\Traits\BelongsToTeam;
use Illuminate\Database\Eloquent\Model;

class OutreachTemplate extends Model
{
    use BelongsToTeam;

    protected $fillable = [
        'name',
        'type',
        'content',
        'user_id',
        'team_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
