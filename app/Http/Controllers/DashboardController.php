<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Activity;
use App\Services\LeadScoreEngine;
use App\Services\NetworkStrategyEngine;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(LeadScoreEngine $scoreEngine, NetworkStrategyEngine $strategyEngine)
    {
        $contacts = Contact::where('team_id', auth()->user()->current_team_id)->get();
        $totalLeads = $contacts->count();
        
        $scoredLeads = $contacts->map(function ($contact) use ($scoreEngine) {
            return $scoreEngine->calculateScore($contact);
        });

        $goodLeadsCount = $scoredLeads->where('status', '🟢 Good Lead')->count();
        $avgScore = $scoredLeads->avg('total_score') ?? 0;
        
        $recentActivities = Activity::where('team_id', auth()->user()->current_team_id)->with('contact')->latest()->take(5)->get();

        $strategyStatus = $strategyEngine->getStrategyStatus();
        $suggestions = $strategyEngine->getSuggestions();

        $user = auth()->user();

        return view('dashboard', compact(
            'totalLeads', 
            'goodLeadsCount', 
            'avgScore', 
            'recentActivities',
            'strategyStatus',
            'suggestions',
            'user'
        ));
    }
}
