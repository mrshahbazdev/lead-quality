<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmailDetectionController extends Controller
{
    public function index(\App\Services\EmailParsingService $emailService)
    {
        $teamId = auth()->user()->current_team_id;
        $accountsCount = \App\Models\EmailAccount::where('team_id', $teamId)->where('is_active', true)->count();
        
        if ($accountsCount === 0) {
            return view('email_scanner.index', [
                'newLeads' => [],
                'existingLeads' => [],
                'noAccounts' => true
            ]);
        }

        try {
            $emails = $emailService->scanInbox();
        } catch (\Exception $e) {
            return view('email_scanner.index', [
                'newLeads' => [],
                'existingLeads' => [],
                'error' => $e->getMessage()
            ]);
        }

        $extracted = $emailService->extractContacts($emails);
        
        $results = $emailService->identifyNewLeads($extracted, $teamId);
        
        return view('email_scanner.index', [
            'newLeads' => $results['new'],
            'existingLeads' => $results['existing'],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'selected_leads' => 'required|array',
            'selected_leads.*' => 'string' // Expecting JSON strings
        ]);

        $addedCount = 0;

        foreach ($request->selected_leads as $leadJson) {
            $leadData = json_decode($leadJson, true);
            
            if ($leadData) {
                // Double check it doesn't exist just in case
                $exists = \App\Models\Contact::where('team_id', auth()->user()->current_team_id)
                    ->where('email', $leadData['email'])
                    ->exists();

                if (!$exists) {
                    \App\Models\Contact::create([
                        'user_id' => auth()->id(),
                        'team_id' => auth()->user()->current_team_id,
                        'name' => $leadData['name'],
                        'email' => $leadData['email'],
                        'company' => $leadData['company'],
                        'source' => $leadData['source'],
                        'status' => 'new',
                        'priority' => 1
                    ]);
                    $addedCount++;
                }
            }
        }

        return redirect()->route('contacts.index')->with('success', __("Successfully imported :count new contacts from email scan.", ['count' => $addedCount]));
    }
}
