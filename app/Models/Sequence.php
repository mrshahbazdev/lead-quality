<?php

namespace App\Models;

use App\Traits\BelongsToTeam;
use Illuminate\Database\Eloquent\Model;

class Sequence extends Model
{
    use BelongsToTeam;

    protected $fillable = ['name', 'team_id', 'is_active'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function steps()
    {
        return $this->hasMany(SequenceStep::class)->orderBy('order');
    }

    public function contacts()
    {
        return $this->belongsToMany(Contact::class, 'contact_sequence')
            ->withPivot('current_step_id', 'next_run_at', 'status')
            ->withTimestamps();
    }
}
