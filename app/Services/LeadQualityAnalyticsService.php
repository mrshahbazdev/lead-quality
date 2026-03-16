<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LeadQualityAnalyticsService
{
    /**
     * Get network growth stats over time.
     */
    public function getNetworkGrowth(): array
    {
        $last6Months = collect(range(0, 5))->map(function ($i) {
            $month = Carbon::now()->subMonths($i);
            return [
                'label' => $month->format('M Y'),
                'count' => Contact::where('team_id', auth()->user()->current_team_id)
                    ->whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->count()
            ];
        })->reverse()->values()->toArray();

        return $last6Months;
    }

    /**
     * Get fulfillment rate for the 2/2/2 strategy.
     */
    public function getFulfillmentRates(): array
    {
        $totalActivitiesLast30Days = Activity::where('team_id', auth()->user()->current_team_id)
            ->where('scheduled_at', '>=', Carbon::now()->subDays(30))->count();
        $completedActivitiesLast30Days = Activity::where('team_id', auth()->user()->current_team_id)
            ->where('scheduled_at', '>=', Carbon::now()->subDays(30))
            ->where('status', 'completed')
            ->count();

        $successRate = $totalActivitiesLast30Days > 0 
            ? ($completedActivitiesLast30Days / $totalActivitiesLast30Days) * 100 
            : 0;

        return [
            'success_rate' => round($successRate, 1),
            'total_completed' => $completedActivitiesLast30Days,
        ];
    }

    /**
     * Get industry distribution of "Good Leads".
     */
    public function getIndustrySuccessMap(): array
    {
        $scoreEngine = new LeadScoreEngine();
        $goodLeads = Contact::where('team_id', auth()->user()->current_team_id)->get()->filter(function ($contact) use ($scoreEngine) {
            $analysis = $scoreEngine->calculateScore($contact);
            return $analysis['total_score'] >= 70;
        });

        $industryCounts = $goodLeads->groupBy('industry')->map(function ($group) {
            return $group->count();
        })->sortDesc()->take(5)->toArray();

        return $industryCounts;
    }
}
