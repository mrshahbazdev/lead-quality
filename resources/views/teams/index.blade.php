@extends('layouts.app')

@section('title', __('Workspaces & Teams') . ' — LeadOS')

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    <h2 style="font-size: 2rem; margin-bottom: 0.5rem;">{{ __('Workspaces & Teams') }}</h2>
    <p style="color: var(--gray); font-size: 1.1rem; margin-bottom: 2rem;">{{ __('Manage your organization, invite team members, and switch between workspaces.') }}</p>

    @if(session('success'))
        <div style="background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; color: #10b981; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; color: #ef4444; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
            {{ session('error') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        <!-- Left Column -->
        <div style="display: flex; flex-direction: column; gap: 2rem;">
        <div style="background: var(--glass); border: 1px solid var(--glass-border); border-radius: 1rem; padding: 1.5rem;">
            <h3 style="font-size: 1.25rem; margin-bottom: 1rem;">{{ __('Current Workspace') }}</h3>
            <div style="font-size: 1.5rem; font-weight: bold; margin-bottom: 1rem; color: var(--primary);">
                {{ $currentTeam ? $currentTeam->name : __('No Active Workspace') }}
            </div>
            
            <h4 style="font-size: 1.1rem; margin-bottom: 1rem; margin-top: 1.5rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 0.5rem;">{{ __('Team Members') }}</h4>
            <ul style="list-style: none; padding: 0; margin-bottom: 1.5rem;">
                @foreach($members as $member)
                    <li style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <div>
                            <span style="font-weight: 500;">{{ $member->name }}</span>
                            <div style="color: var(--gray); font-size: 0.85rem;">{{ $member->email }}</div>
                        </div>
                        <span style="background: var(--darker); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.8rem; color: var(--gray);">
                            {{ ucfirst($member->pivot->role) }}
                        </span>
                    </li>
                @endforeach
            </ul>

            <h4 style="font-size: 1.1rem; margin-bottom: 1rem;">{{ __('Invite Member') }}</h4>
            <form action="{{ route('teams.invite') }}" method="POST" style="display: flex; gap: 0.5rem;">
                @csrf
                <input type="email" name="email" class="form-control" placeholder="user@example.com" required style="flex-grow: 1;">
                <button type="submit" class="btn" style="background: var(--primary); color: white;">{{ __('Invite') }}</button>
            </form>
            <p style="font-size: 0.8rem; color: var(--gray); margin-top: 0.5rem;">{{ __('User must be registered in the system first.') }}</p>
        </div>

        <!-- Connected Email Accounts (IMAP/SMTP) -->
        <div style="background: var(--glass); border: 1px solid var(--glass-border); border-radius: 1rem; padding: 1.5rem;">
            <h3 style="font-size: 1.25rem; margin-bottom: 1rem;">{{ __('Connected Email Accounts') }}</h3>
            <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 1.5rem;">{{ __('Connect your inbox via IMAP/SMTP to send Drip Campaigns and Auto-detect Replies.') }}</p>
            
            <ul style="list-style: none; padding: 0; margin-bottom: 1.5rem;">
                @foreach(\App\Models\EmailAccount::where('team_id', $currentTeam->id)->get() as $account)
                    <li style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <div>
                            <span style="font-weight: 500;">{{ $account->email_address }}</span>
                            <div style="color: var(--gray); font-size: 0.85rem;">IMAP: {{ $account->imap_host }}</div>
                        </div>
                        <form action="{{ route('email-accounts.destroy', $account) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn" style="background: rgba(239, 68, 68, 0.2); color: #ef4444; padding: 0.25rem 0.75rem; font-size: 0.8rem;">{{ __('Disconnect') }}</button>
                        </form>
                    </li>
                @endforeach
            </ul>

            <h4 style="font-size: 1.1rem; margin-bottom: 1rem;">{{ __('Connect New Account') }}</h4>
            <form action="{{ route('email-accounts.store') }}" method="POST">
                @csrf
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.25rem; color: var(--gray); font-size: 0.85rem;">{{ __('Email Address') }}</label>
                    <input type="email" name="email_address" class="form-control" placeholder="you@company.com" required>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.25rem; color: var(--gray); font-size: 0.85rem;">IMAP Host</label>
                        <input type="text" name="imap_host" class="form-control" placeholder="imap.gmail.com" required>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.25rem; color: var(--gray); font-size: 0.85rem;">IMAP Port</label>
                        <input type="number" name="imap_port" class="form-control" value="993" required>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.25rem; color: var(--gray); font-size: 0.85rem;">IMAP Encryption</label>
                        <select name="imap_encryption" class="form-control">
                            <option value="ssl">SSL</option>
                            <option value="tls">TLS</option>
                        </select>
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.25rem; color: var(--gray); font-size: 0.85rem;">SMTP Host</label>
                        <input type="text" name="smtp_host" class="form-control" placeholder="smtp.gmail.com" required>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.25rem; color: var(--gray); font-size: 0.85rem;">SMTP Port</label>
                        <input type="number" name="smtp_port" class="form-control" value="465" required>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.25rem; color: var(--gray); font-size: 0.85rem;">SMTP Encryption</label>
                        <select name="smtp_encryption" class="form-control">
                            <option value="ssl">SSL</option>
                            <option value="tls">TLS</option>
                        </select>
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.25rem; color: var(--gray); font-size: 0.85rem;">App Password</label>
                        <input type="password" name="password" class="form-control" placeholder="16-digit app password" required>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.25rem; color: var(--gray); font-size: 0.85rem;">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="you@company.com" required>
                    </div>
                </div>
                <button type="submit" class="btn" style="background: var(--primary); color: white; width: 100%;">{{ __('Connect Mailbox') }}</button>
            </form>
        </div>
        </div>

        <!-- Right Column -->
        <div style="display: flex; flex-direction: column; gap: 2rem;">
            <div style="background: var(--glass); border: 1px solid var(--glass-border); border-radius: 1rem; padding: 1.5rem; margin-bottom: 2rem;">
                <h3 style="font-size: 1.25rem; margin-bottom: 1rem;">{{ __('API Credentials (Chrome Extension)') }}</h3>
                <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 1rem;">{{ __('Generate an API token to connect your Chrome Extension to this account.') }}</p>
                <form action="{{ route('user.api-token') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">{{ __('Generate New Token') }}</button>
                </form>
            </div>

            <div style="background: var(--glass); border: 1px solid var(--glass-border); border-radius: 1rem; padding: 1.5rem; margin-bottom: 2rem;">
                <h3 style="font-size: 1.25rem; margin-bottom: 1rem;">{{ __('OpenAI API Key') }}</h3>
                <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 1rem;">{{ __('Provide your own OpenAI API Key to enable AI Lead Scoring and Insights for your workspace.') }}</p>
                <form action="{{ route('teams.update-openai-key') }}" method="POST">
                    @csrf
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; color: var(--gray);">{{ __('API Key') }}</label>
                        <input type="password" name="openai_api_key" class="form-control" placeholder="sk-..." value="{{ $currentTeam->openai_api_key ? '********************************' : '' }}" required>
                        @if ($currentTeam->openai_api_key)
                            <small style="color: #10b981; margin-top: 0.5rem; display: block;">{{ __('✓ Key is currently saved.') }}</small>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-outline" style="width: 100%; justify-content: center;">{{ __('Save AI Key') }}</button>
                </form>
            </div>

            <div style="background: var(--glass); border: 1px solid var(--glass-border); border-radius: 1rem; padding: 1.5rem; margin-bottom: 2rem;">
                <h3 style="font-size: 1.25rem; margin-bottom: 1rem;">{{ __('Groq API Keys') }}</h3>
                <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 1rem;">{!! __('Add multiple Groq API Keys (one per line). The system will randomly rotate through these keys during AI analysis to help prevent rate limits for the <code>openai/gpt-oss-120b</code> equivalent models.') !!}</p>
                <form action="{{ route('teams.update-groq-keys') }}" method="POST">
                    @csrf
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; color: var(--gray);">{{ __('API Keys') }}</label>
                        <textarea name="groq_api_keys" class="form-control" placeholder="gsk_...\ngsk_..." rows="4" required>{{ $currentTeam->groq_api_keys ? implode("\n", $currentTeam->groq_api_keys) : '' }}</textarea>
                        @if ($currentTeam->groq_api_keys && count($currentTeam->groq_api_keys) > 0)
                            <small style="color: #10b981; margin-top: 0.5rem; display: block;">{{ __('✓ :count key(s) currently saved.', ['count' => count($currentTeam->groq_api_keys)]) }}</small>
                        @endif
                    </div>
                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 0.5rem;">
                        <button type="submit" class="btn btn-outline" style="width: 100%; justify-content: center;">{{ __('Save Groq Keys') }}</button>
                    </div>
                </form>
                @if ($currentTeam->groq_api_keys && count($currentTeam->groq_api_keys) > 0)
                <form action="{{ route('teams.test-groq-keys') }}" method="POST" style="margin-top: 0.5rem;">
                    @csrf
                    <button type="submit" class="btn" style="width: 100%; justify-content: center; background: rgba(16, 185, 129, 0.2); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3);">{{ __('Test API Connection') }}</button>
                </form>
                @endif
            </div>

            <div style="background: var(--glass); border: 1px solid var(--glass-border); border-radius: 1rem; padding: 1.5rem; margin-bottom: 2rem;">
                <h3 style="font-size: 1.25rem; margin-bottom: 1rem;">{{ __('Switch Workspace') }}</h3>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    @foreach($myTeams as $team)
                        <form action="{{ route('teams.switch', $team) }}" method="POST">
                            @csrf
                            <button type="submit" style="width: 100%; text-align: left; background: {{ $currentTeam && $currentTeam->id === $team->id ? 'rgba(255, 140, 0, 0.2)' : 'var(--darker)' }}; border: 1px solid {{ $currentTeam && $currentTeam->id === $team->id ? 'var(--primary)' : 'var(--glass-border)' }}; padding: 1rem; border-radius: 0.5rem; color: white; cursor: pointer; display: flex; justify-content: space-between;">
                                <span>{{ $team->name }}</span>
                                @if($currentTeam && $currentTeam->id === $team->id)
                                    <span style="color: var(--primary);">{{ __('✓ Active') }}</span>
                                @endif
                            </button>
                        </form>
                    @endforeach
                </div>
            </div>

            <div style="background: var(--glass); border: 1px solid var(--glass-border); border-radius: 1rem; padding: 1.5rem;">
                <h3 style="font-size: 1.25rem; margin-bottom: 1rem;">{{ __('Create New Workspace') }}</h3>
                <form action="{{ route('teams.store') }}" method="POST">
                    @csrf
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; color: var(--gray);">{{ __('Workspace Name') }}</label>
                        <input type="text" name="name" class="form-control" placeholder="{{ __('e.g. Acme Corp Sales') }}" required>
                    </div>
                    <button type="submit" class="btn" style="width: 100%; background: var(--glass-border); color: white;">{{ __('Create & Switch') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
