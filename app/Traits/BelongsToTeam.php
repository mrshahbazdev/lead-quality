<?php

namespace App\Traits;

use App\Scopes\TeamScope;
use Illuminate\Support\Facades\Auth;

trait BelongsToTeam
{
    public static function bootBelongsToTeam()
    {
        static::addGlobalScope(new TeamScope);

        static::creating(function ($model) {
            if (!app()->runningInConsole()) {
                if (Auth::check() && !$model->team_id) {
                    $model->team_id = Auth::user()->current_team_id;
                }
            }
        });
    }

    public function team()
    {
        return $this->belongsTo(\App\Models\Team::class);
    }
}
