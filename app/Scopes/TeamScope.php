<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class TeamScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (app()->runningInConsole()) {
            return;
        }

        $user = Auth::user();
        
        // If logged in, strictly filter by current_team_id
        if ($user) {
            $teamId = $user->current_team_id;
            
            // If user has no workspace selected, they should see NO data
            // (prevents leaking records where team_id IS NULL)
            if (!$teamId) {
                $builder->whereRaw('1 = 0');
            } else {
                $builder->where($model->getTable() . '.team_id', $teamId);
            }
        }
    }
}
