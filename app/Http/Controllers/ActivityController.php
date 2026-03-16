<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function store(Request $request, Contact $contact, \App\Services\GamificationService $gamificationService)
    {
        $validated = $request->validate([
            'type' => 'required|in:outreach,follow-up,meeting,reminder',
            'notes' => 'nullable|string',
            'scheduled_at' => 'nullable|date',
        ]);

        $activity = $contact->activities()->create([
            'user_id' => auth()->id(),
            'team_id' => auth()->user()->current_team_id,
            'type' => $validated['type'],
            'notes' => $validated['notes'],
            'scheduled_at' => $validated['scheduled_at'] ?? now(),
            'status' => \Carbon\Carbon::parse($validated['scheduled_at'] ?? now())->isFuture() ? 'pending' : 'completed',
        ]);

        $contact->update(['last_interaction_at' => now()]);

        // Award points to the authenticated user
        $user = auth()->user();
        if ($user) {
            $gamificationService->awardPoints($user, $validated['type']);
        }

        return redirect()->back()->with('success', __('Activity logged and points awarded!'));
    }
}
