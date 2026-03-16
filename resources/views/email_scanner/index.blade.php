@extends('layouts.app')

@section('title', __('Email Scanner'))

@section('content')
<div class="card" style="max-width: 1000px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 2rem; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <span>📧</span> {{ __('Email Scanner') }}
            </h2>
            <p style="color: var(--gray); font-size: 1.1rem;">{{ __('Automatically detect new leads from your connected inbox.') }}</p>
        </div>
        <div>
            <span class="badge" style="background: rgba(255, 140, 0, 0.15); color: #ffa333; font-size: 0.9rem; padding: 0.5rem 1rem;">
                {{ __('Last Scan') }}: {{ __('Just now') }}
            </span>
        </div>
    </div>

    @if(isset($noAccounts) && $noAccounts)
        <div style="background: rgba(239, 68, 68, 0.05); border: 1px dashed rgba(239, 68, 68, 0.3); padding: 3rem; text-align: center; border-radius: 1rem; margin-bottom: 3rem;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">🔌</div>
            <h3 style="color: #ef4444; margin-bottom: 0.5rem;">{{ __('No Email Accounts Connected') }}</h3>
            <p style="color: var(--gray); margin-bottom: 1.5rem;">{{ __('You need to connect an IMAP account to scan for new leads.') }}</p>
            <a href="{{ route('teams.index') }}" class="btn btn-primary">{{ __('Go to Workspaces & Settings') }}</a>
        </div>
    @elseif(isset($error))
        <div style="background: rgba(239, 68, 68, 0.05); border: 1px dashed rgba(239, 68, 68, 0.3); padding: 3rem; text-align: center; border-radius: 1rem; margin-bottom: 3rem;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">❌</div>
            <h3 style="color: #ef4444; margin-bottom: 0.5rem;">{{ __('Connection Error') }}</h3>
            <p style="color: var(--gray); margin-bottom: 1.5rem;">{{ $error }}</p>
            <a href="{{ route('teams.index') }}" class="btn btn-outline">{{ __('Check Credentials in Settings') }}</a>
        </div>
    @elseif(count($newLeads) > 0)
        <div style="margin-bottom: 3rem;">
            <h3 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; color: #10b981;">
                <span style="font-size: 1.2rem;">✨</span> {{ __('Discovered Leads') }}
                <span class="badge" style="background: rgba(16, 185, 129, 0.15); color: #10b981; margin-left: auto;">{{ __(':count New', ['count' => count($newLeads)]) }}</span>
            </h3>
            
            <form action="{{ route('email-scanner.store') }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table style="background: var(--dark); border-radius: 0.75rem; overflow: hidden; border: 1px solid var(--glass-border);">
                        <thead style="background: var(--glass);">
                            <tr>
                                <th style="width: 40px; text-align: center;"><input type="checkbox" id="selectAll" checked></th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Inferred Company') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($newLeads as $lead)
                                <tr style="border-bottom: 1px solid var(--glass-border);">
                                    <td style="text-align: center;">
                                        <input type="checkbox" name="selected_leads[]" value="{{ json_encode($lead) }}" checked class="lead-checkbox">
                                    </td>
                                    <td style="font-weight: 500;">{{ $lead['name'] }}</td>
                                    <td style="color: var(--gray);">{{ $lead['email'] }}</td>
                                    <td><span class="badge" style="background: var(--glass);">{{ $lead['company'] }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div style="margin-top: 1.5rem; text-align: right;">
                    <button type="submit" class="btn btn-primary" style="font-size: 1rem; padding: 0.75rem 2rem;">
                        📥 {{ __('Add Selected to CRM') }}
                    </button>
                </div>
            </form>
        </div>
    @else
        <div style="background: rgba(16, 185, 129, 0.05); border: 1px dashed rgba(16, 185, 129, 0.3); padding: 3rem; text-align: center; border-radius: 1rem; margin-bottom: 3rem;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">🎉</div>
            <h3 style="color: #10b981; margin-bottom: 0.5rem;">{{ __('Inbox Zero!') }}</h3>
            <p style="color: var(--gray);">{{ __('No new leads discovered in your recent emails.') }}</p>
        </div>
    @endif

    <div style="border-top: 1px solid var(--glass-border); padding-top: 2rem;">
        <h3 style="margin-bottom: 1.5rem; color: var(--gray); font-size: 1.1rem;">{{ __('Existing Contacts Found') }}</h3>
        
        @if(count($existingLeads) > 0)
            <div class="table-responsive" style="opacity: 0.7;">
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($existingLeads as $lead)
                            <tr>
                                <td>{{ $lead['name'] }}</td>
                                <td>{{ $lead['email'] }}</td>
                                <td><span class="badge" style="background: var(--glass); color: var(--gray);">{{ __('Already in CRM') }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p style="color: var(--gray); font-style: italic;">{{ __('No existing contacts detected in recent emails.') }}</p>
        @endif
    </div>
</div>

<script>
    document.getElementById('selectAll').addEventListener('change', function(e) {
        document.querySelectorAll('.lead-checkbox').forEach(box => {
            box.checked = e.target.checked;
        });
    });
</script>
@endsection
