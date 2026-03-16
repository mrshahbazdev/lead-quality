<?php

namespace App\Services;

use App\Models\User;
use App\Models\Activity;
use Carbon\Carbon;

class GamificationService
{
    /**
     * Award points for completing an activity.
     */
    public function awardPoints(User $user, string $type): int
    {
        $pointsMap = [
            'outreach' => 10,
            'follow-up' => 5,
            'meeting' => 50,
        ];

        $points = $pointsMap[$type] ?? 2;
        $user->increment('points', $points);

        $this->updateStreak($user);

        return $points;
    }

    /**
     * Update user streak based on activity consistency.
     */
    protected function updateStreak(User $user)
    {
        $lastActivity = Activity::where('user_id', $user->id)
            ->where('status', 'completed')
            ->orderBy('scheduled_at', 'desc')
            ->first();

        if ($lastActivity && $lastActivity->scheduled_at->isYesterday()) {
            $user->increment('streak');
        } elseif (!$lastActivity || !$lastActivity->scheduled_at->isToday()) {
            $user->update(['streak' => 1]);
        }
    }
}
