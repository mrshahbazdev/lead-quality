<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SequenceController extends Controller
{
    public function index()
    {
        $sequences = \App\Models\Sequence::where('team_id', auth()->user()->current_team_id)->withCount('contacts')->get();
        return view('sequences.index', compact('sequences'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        \App\Models\Sequence::create([
            'name' => $request->name,
            'team_id' => auth()->user()->current_team_id
        ]);
        return redirect()->route('sequences.index')->with('success', __('Sequence created!'));
    }

    public function show(\App\Models\Sequence $sequence)
    {
        if ($sequence->team_id !== auth()->user()->current_team_id) abort(403);
        $sequence->load(['steps', 'contacts']);
        return view('sequences.show', compact('sequence'));
    }

    public function storeStep(Request $request, \App\Models\Sequence $sequence)
    {
        if ($sequence->team_id !== auth()->user()->current_team_id) abort(403);
        $request->validate([
            'delay_days' => 'required|integer|min:0',
            'subject' => 'required|string|max:255',
            'body' => 'required|string'
        ]);

        $order = $sequence->steps()->max('order') + 1;

        $sequence->steps()->create([
            'order' => $order,
            'delay_days' => $request->delay_days,
            'subject' => $request->subject,
            'body' => $request->body
        ]);

        return redirect()->route('sequences.show', $sequence)->with('success', __('Step added!'));
    }

    public function enroll(Request $request)
    {
        $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'sequence_id' => 'required|exists:sequences,id'
        ]);

        $contact = \App\Models\Contact::where('team_id', auth()->user()->current_team_id)->findOrFail($request->contact_id);
        $sequence = \App\Models\Sequence::where('team_id', auth()->user()->current_team_id)->findOrFail($request->sequence_id);

        $firstStep = $sequence->steps()->orderBy('order')->first();

        if (!$contact->sequences->contains($sequence->id)) {
            $contact->sequences()->attach($sequence->id, [
                'current_step_id' => $firstStep ? $firstStep->id : null,
                'next_run_at' => $firstStep ? now()->addDays($firstStep->delay_days) : null,
                'status' => 'active'
            ]);
            return redirect()->back()->with('success', __('Contact enrolled in sequence!'));
        }

        return redirect()->back()->with('error', __('Contact is already in this sequence.'));
    }
}
