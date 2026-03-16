<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class NetworkStrategyEngine
{
    /**
     * Get the current status of the 2/2/2 strategy.
     * 2 New contacts per day
     * 2 Maintenance contacts per week
     * 2 Meetings per month
     */
    public function getStrategyStatus(): array
    {
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();

        // Daily: New Outreach (2/day)
        $dailyActual = Activity::where('team_id', auth()->user()->current_team_id)
            ->where('type', 'outreach')
            ->where('status', 'completed')
            ->whereDate('scheduled_at', $today)
            ->count();

        // Weekly: Maintenance (2/week)
        $weeklyActual = Activity::where('team_id', auth()->user()->current_team_id)
            ->where('type', 'follow-up')
            ->where('status', 'completed')
            ->whereBetween('scheduled_at', [$startOfWeek, Carbon::now()])
            ->count();

        // Monthly: Meetings (2/month)
        $monthlyActual = Activity::where('team_id', auth()->user()->current_team_id)
            ->where('type', 'meeting')
            ->where('status', 'completed')
            ->whereBetween('scheduled_at', [$startOfMonth, Carbon::now()])
            ->count();

        return [
            'daily' => [
                'target' => 2,
                'actual' => $dailyActual,
                'percentage' => min(100, ($dailyActual / 2) * 100),
                'label' => __('Daily Outreach')
            ],
            'weekly' => [
                'target' => 2,
                'actual' => $weeklyActual,
                'percentage' => min(100, ($weeklyActual / 2) * 100),
                'label' => __('Weekly Maintenance')
            ],
            'monthly' => [
                'target' => 2,
                'actual' => $monthlyActual,
                'percentage' => min(100, ($monthlyActual / 2) * 100),
                'label' => __('Monthly Meetings')
            ],
        ];
    }

    /**
     * Suggest contacts for the next actions.
     */
    public function getSuggestions(): array
    {
        // 1. Suggest for "New Outreach" (Contacts with 'new' status)
        $outreachSuggestions = Contact::where('team_id', auth()->user()->current_team_id)
            ->where('status', 'new')
            ->orderBy('priority', 'desc')
            ->take(2)
            ->get();

        // 2. Suggest for "Maintenance" (Active contacts not interacted with recently)
        $maintenanceSuggestions = Contact::where('team_id', auth()->user()->current_team_id)
            ->where('status', 'active')
            ->orderBy('last_interaction_at', 'asc')
            ->take(2)
            ->get();

        // 3. Suggest for "Meetings" (Hot contacts with high engagement)
        $meetingSuggestions = Contact::where('team_id', auth()->user()->current_team_id)
            ->where('status', 'hot')
            ->whereDoesntHave('activities', function ($query) {
                $query->where('type', 'meeting')
                      ->where('scheduled_at', '>', Carbon::now()->subMonth());
            })
            ->take(2)
            ->get();

        return [
            'outreach' => $outreachSuggestions,
            'maintenance' => $maintenanceSuggestions,
            'meetings' => $meetingSuggestions,
        ];
    }
}
