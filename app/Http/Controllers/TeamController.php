<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $currentTeam = $user->currentTeam;
        $members = $currentTeam ? $currentTeam->members : [];
        $myTeams = $user->teams;
        return view('teams.index', compact('currentTeam', 'members', 'myTeams'));
    }

    public function switchTeam(\App\Models\Team $team)
    {
        if (auth()->user()->teams->contains($team)) {
            auth()->user()->update(['current_team_id' => $team->id]);
        }
        return redirect()->route('dashboard')->with('success', __('Switched to :name', ['name' => $team->name]));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $team = \App\Models\Team::create([
            'name' => $request->name,
            'owner_id' => auth()->id()
        ]);
        $team->members()->attach(auth()->id(), ['role' => 'owner']);
        auth()->user()->update(['current_team_id' => $team->id]);
        return redirect()->back()->with('success', __('Workspace Created & Switched'));
    }

    public function invite(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = \App\Models\User::where('email', $request->email)->first();
        if ($user) {
            $team = auth()->user()->currentTeam;
            if ($team && !$team->members->contains($user->id)) {
                $team->members()->attach($user->id, ['role' => 'member']);
                return redirect()->back()->with('success', __('User added to your workspace!'));
            }
            return redirect()->back()->with('error', __('User is already in this workspace.'));
        }
        return redirect()->back()->with('error', __('User not found. They must register first.'));
    }

    public function updateOpenAIKey(Request $request)
    {
        $request->validate(['openai_api_key' => 'required|string']);
        
        $team = auth()->user()->currentTeam;
        if ($team && $team->owner_id === auth()->id()) {
            $team->update(['openai_api_key' => $request->openai_api_key]);
            return redirect()->back()->with('success', __('OpenAI API Key successfully updated for this workspace.'));
        }

        return redirect()->back()->with('error', __('Only the workspace owner can update the API key.'));
    }

    public function updateGroqKeys(Request $request)
    {
        $request->validate(['groq_api_keys' => 'required|string']);
        
        $team = auth()->user()->currentTeam;
        if ($team && $team->owner_id === auth()->id()) {
            // Process the textarea into an array of trimmed keys
            $keys = array_filter(array_map('trim', explode("\n", $request->groq_api_keys)));
            $team->update(['groq_api_keys' => array_values($keys)]);
            return redirect()->back()->with('success', __(':count Groq API Key(s) successfully updated for this workspace.', ['count' => count($keys)]));
        }

        return redirect()->back()->with('error', __('Only the workspace owner can update the API keys.'));
    }

    public function testGroqKeys(Request $request)
    {
        $team = auth()->user()->currentTeam;
        if (!$team || empty($team->groq_api_keys)) {
            return redirect()->back()->with('error', __('No Groq API keys found to test.'));
        }

        $keys = $team->groq_api_keys;
        $randomKey = $keys[array_rand($keys)];

        try {
            $factory = \OpenAI::factory()
                ->withApiKey($randomKey)
                ->withBaseUri('api.groq.com/openai/v1');
            
            $client = $factory->make();
            $response = $client->chat()->create([
                'model' => 'openai/gpt-oss-120b',
                'messages' => [
                    ['role' => 'user', 'content' => 'Reply with exactly the word: SUCCESS']
                ],
                'max_tokens' => 5
            ]);

            $reply = trim($response->choices[0]->message->content);
            return redirect()->back()->with('success', __("API Test Successful! Responded: ':reply' using one of your keys.", ['reply' => $reply]));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('API Test Failed: :message', ['message' => $e->getMessage()]));
        }
    }
}
