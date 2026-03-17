@extends('layouts.app')

@section('title', __('Network Contacts') . ' — LeadOS')

@section('content')
@if(session('error'))
    <div class="flash" style="background: rgba(239, 68, 68, 0.1); border-color: rgba(239, 68, 68, 0.2); color: #ef4444;">
        <i class="fa-solid fa-circle-exclamation"></i>
        {{ session('error') }}
    </div>
@endif
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 2rem;">
        <h2>{{ __('All Contacts') }}</h2>
        <div style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center;">
             @if(!isset($noWorkspace))
                 <div class="csv-help" style="font-size: 0.75rem; color: var(--gray); background: var(--glass); padding: 0.5rem 1rem; border-radius: 0.5rem; border: 1px dashed var(--glass-border);">
                    <strong>{{ __('CSV Format') }}:</strong> name (req), email, company, position, industry, role, budget, notes
                 </div>
                 <form action="{{ route('contacts.import') }}" method="POST" enctype="multipart/form-data" id="importForm" style="display: none;">
                    @csrf
                    <input type="file" name="csv_file" id="csvFileInput" onchange="document.getElementById('importForm').submit()">
                 </form>
                 <button onclick="document.getElementById('csvFileInput').click()" class="btn" style="background: var(--glass); color: white; border: 1px solid var(--glass-border);">📥 {{ __('Import CSV') }}</button>
                 <a href="{{ route('contacts.create') }}" class="btn btn-primary">+ {{ __('Add Contact') }}</a>
             @else
                 <a href="{{ route('teams.index') }}" class="btn" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2);">⚠️ {{ __('Setup Workspace First') }}</a>
             @endif
        </div>
    </div>

    @if(isset($noWorkspace))
        <div style="background: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.2); padding: 3rem; text-align: center; border-radius: 1rem; margin-bottom: 2rem;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">🏢</div>
            <h3 style="color: #ef4444; margin-bottom: 0.5rem;">{{ __('Workspace Required') }}</h3>
            <p style="color: var(--gray); margin-bottom: 1.5rem;">{{ __('To keep your data private and secure, you must create or select a Workspace before adding contacts.') }}</p>
            <a href="{{ route('teams.index') }}" class="btn btn-primary">{{ __('Go to Workspaces') }}</a>
        </div>
    @endif

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>{{ __('Name / Company') }}</th>
                    <th>{{ __('Fit Score') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Last Interaction') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $contact)
                    <tr>
                        <td>
                            <div style="font-weight: 600;">{{ $contact->name }}</div>
                            <div style="font-size: 0.875rem; color: var(--gray);">{{ $contact->company }} • {{ $contact->position }}</div>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <div style="width: 40px; height: 4px; background: var(--gray-light); border-radius: 2px;">
                                    <div style="width: {{ $contact->analysis['total_score'] }}%; height: 100%; background: var(--primary); border-radius: 2px;"></div>
                                </div>
                                <span style="font-weight: 600; font-size: 0.875rem;">{{ $contact->analysis['total_score'] }}%</span>
                            </div>
                        </td>
                        <td>
                            @php
                                $score = $contact->analysis['total_score'];
                                $badgeClass = $score >= 70 ? 'badge-good' : ($score >= 40 ? 'badge-avg' : 'badge-bad');
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                {{ $contact->analysis['status'] }}
                            </span>
                        </td>
                        <td>
                            {{ $contact->last_interaction_at ? $contact->last_interaction_at->diffForHumans() : __('No interaction yet') }}
                        </td>
                        <td style="display: flex; gap: 0.5rem; align-items: center;">
                            <a href="{{ route('contacts.show', $contact) }}" class="btn" style="padding: 0.4rem 0.6rem; background: var(--glass); color: white; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;" title="{{ __('View') }}">👁️</a>
                            <a href="{{ route('contacts.edit', $contact) }}" class="btn" style="padding: 0.4rem 0.6rem; background: var(--glass); color: #ffa333; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;" title="{{ __('Edit') }}">✏️</a>
                            <form action="{{ route('contacts.destroy', $contact) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this contact?') }}')" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn" style="padding: 0.4rem 0.6rem; background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); display: flex; align-items: center; justify-content: center; font-size: 0.9rem;" title="{{ __('Delete') }}">🗑️</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--gray); padding: 3rem;">
                            {{ __('No contacts found. Start by adding your first lead!') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
