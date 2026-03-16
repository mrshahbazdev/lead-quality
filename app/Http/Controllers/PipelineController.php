<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PipelineController extends Controller
{
    public function index()
    {
        $contacts = \App\Models\Contact::where('team_id', auth()->user()->current_team_id)->get();

        $pipeline = [
            'new' => $contacts->where('pipeline_stage', 'new')->values(),
            'contacted' => $contacts->where('pipeline_stage', 'contacted')->values(),
            'meeting_set' => $contacts->where('pipeline_stage', 'meeting_set')->values(),
            'won' => $contacts->where('pipeline_stage', 'won')->values(),
            'lost' => $contacts->where('pipeline_stage', 'lost')->values(),
        ];

        return view('pipeline.index', compact('pipeline'));
    }

    public function updateStage(Request $request)
    {
        $validated = $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'stage' => 'required|string|in:new,contacted,meeting_set,won,lost'
        ]);

        $contact = \App\Models\Contact::where('team_id', auth()->user()->current_team_id)
            ->findOrFail($validated['contact_id']);

        $contact->update(['pipeline_stage' => $validated['stage']]);

        return response()->json(['success' => true]);
    }
}
