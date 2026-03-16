@extends('layouts.app')

@section('title', __('Network Contacts') . ' — LeadOS')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 2rem;">
        <h2>{{ __('All Contacts') }}</h2>
        <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
             <form action="{{ route('contacts.import') }}" method="POST" enctype="multipart/form-data" id="importForm" style="display: none;">
                @csrf
                <input type="file" name="csv_file" onchange="document.getElementById('importForm').submit()">
             </form>
             <button onclick="document.querySelector('#importForm input').click()" class="btn" style="background: var(--glass); color: white; border: 1px solid var(--glass-border);">📥 {{ __('Import CSV') }}</button>
             <a href="{{ route('contacts.create') }}" class="btn btn-primary">+ {{ __('Add Contact') }}</a>
        </div>
    </div>

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
                        <td>
                            <a href="{{ route('contacts.show', $contact) }}" class="nav-link" style="display: inline-flex; padding: 0.5rem;">👁️</a>
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
