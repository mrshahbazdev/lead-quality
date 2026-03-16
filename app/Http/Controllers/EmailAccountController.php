<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmailAccountController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email_address' => 'required|email',
            'imap_host' => 'required|string',
            'imap_port' => 'required|integer',
            'imap_encryption' => 'required|string',
            'smtp_host' => 'required|string',
            'smtp_port' => 'required|integer',
            'smtp_encryption' => 'required|string',
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        $account = \App\Models\EmailAccount::updateOrCreate(
            ['team_id' => auth()->user()->current_team_id, 'email_address' => $validated['email_address']],
            array_merge($validated, ['user_id' => auth()->id()])
        );

        return redirect()->back()->with('success', __('Email Account Settings Saved!'));
    }

    public function destroy(\App\Models\EmailAccount $emailAccount)
    {
        if ($emailAccount->team_id !== auth()->user()->current_team_id) abort(403);
        $emailAccount->delete();
        return redirect()->back()->with('success', __('Email Account removed.'));
    }
}
