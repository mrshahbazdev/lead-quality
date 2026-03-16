<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeadImportController extends Controller
{
    public function store(Request $request, \App\Services\LeadScoreEngine $scoreEngine)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'nullable|email',
            'company' => 'nullable|string',
            'position' => 'nullable|string',
            'linkedin' => 'nullable|url',
        ]);

        $user = $request->user();
        $teamId = $user->current_team_id;

        $existing = null;

        if (!empty($validated['linkedin'])) {
            // Trim tracking params from url for cleaner comparison
            $cleanUrl = explode('?', $validated['linkedin'])[0];
            $validated['linkedin'] = $cleanUrl;
            $existing = \App\Models\Contact::where('team_id', $teamId)
                            ->where('linkedin', 'like', $cleanUrl . '%')
                            ->first();
        }

        if (!$existing && !empty($validated['email'])) {
            $existing = \App\Models\Contact::where('team_id', $teamId)
                            ->where('email', $validated['email'])
                            ->first();
        }

        if ($existing) {
            $existing->update(array_filter($validated)); // update only with provided non-null values
            $analysis = $scoreEngine->calculateScore($existing);
            return response()->json([
                'message' => 'Lead updated successfully in CRM!',
                'contact' => $existing,
                'score' => $analysis['total_score'] ?? 0
            ], 200);
        }

        // If not existing, create a new one
        $contact = \App\Models\Contact::create(array_merge($validated, [
            'user_id' => $user->id,
            'team_id' => $teamId,
            'source' => 'Chrome Extension'
        ]));

        $analysis = $scoreEngine->calculateScore($contact);

        return response()->json([
            'message' => 'Lead imported successfully!',
            'contact' => $contact,
            'score' => $analysis['total_score'] ?? 0
        ], 201);
    }
}
