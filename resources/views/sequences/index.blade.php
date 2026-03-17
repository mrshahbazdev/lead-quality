@extends('layouts.app')

@section('title', __('Automated Sequences') . ' — LeadOS')

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 2rem; margin-bottom: 0.5rem;">{{ __('Automated Sequences') }}</h2>
            <p style="color: var(--gray); font-size: 1.1rem;">{{ __('Build multi-step email drip campaigns to engage your leads on autopilot.') }}</p>
        </div>
        @if(!isset($noWorkspace))
            <button onclick="document.getElementById('newSequenceForm').style.display='block'" class="btn btn-primary">+ {{ __('New Sequence') }}</button>
        @else
            <a href="{{ route('teams.index') }}" class="btn" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2);">⚠️ {{ __('Setup Workspace First') }}</a>
        @endif
    </div>

    @if(isset($noWorkspace))
        <div style="background: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.2); padding: 2rem; border-radius: 1rem; text-align: center; margin-bottom: 2rem;">
            <p style="color: #ef4444; margin-bottom: 1rem;">{{ __('You need an active Workspace to manage sequences.') }}</p>
            <a href="{{ route('teams.index') }}" class="btn btn-primary">{{ __('Go to Workspaces') }}</a>
        </div>
    @endif

    <!-- Create Sequence Inline Form -->
    <div id="newSequenceForm" style="display: none; background: var(--glass); border: 1px solid var(--primary); border-radius: 1rem; padding: 1.5rem; margin-bottom: 2rem;">
        <h3 style="font-size: 1.25rem; margin-bottom: 1rem;">{{ __('Create a Sequence') }}</h3>
        <form action="{{ route('sequences.store') }}" method="POST" style="display: flex; gap: 1rem;">
            @csrf
            <input type="text" name="name" class="form-control" placeholder="{{ __('e.g. New User Onboarding') }}" required style="flex-grow: 1;">
            <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
            <button type="button" class="btn" onclick="document.getElementById('newSequenceForm').style.display='none'" style="background: var(--glass-border); color: white;">{{ __('Cancel') }}</button>
        </form>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
        @forelse($sequences as $seq)
            <a href="{{ route('sequences.show', $seq) }}" style="text-decoration: none; color: inherit;">
                <div class="card" style="transition: transform 0.2s, background 0.2s; cursor: pointer;" onmouseover="this.style.background='var(--glass)'" onmouseout="this.style.background='var(--dark-2)'">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                        <h3 style="font-size: 1.1rem; color: white;">{{ $seq->name }}</h3>
                        <span class="badge {{ $seq->is_active ? 'badge-good' : 'badge-avg' }}">
                            {{ $seq->is_active ? __('Active') : __('Paused') }}
                        </span>
                    </div>
                    <div style="color: var(--gray); font-size: 0.85rem; display: flex; justify-content: space-between;">
                        <span>👥 {{ $seq->contacts_count }} {{ __('Enrolled') }}</span>
                        <span>📅 {{ __('Created :date', ['date' => $seq->created_at->format('M j')]) }}</span>
                    </div>
                </div>
            </a>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 2rem; background: var(--glass); border: 1px dashed var(--glass-border); border-radius: 1rem;">
                <p style="color: var(--gray); font-size: 1.1rem; margin-bottom: 1rem;">{{ __('No sequences found.') }}</p>
                <button onclick="document.getElementById('newSequenceForm').style.display='block'" class="btn btn-primary">{{ __('Create Your First Sequence') }}</button>
            </div>
        @endforelse
    </div>
</div>

<style>
    .form-control { width: 100%; padding: 0.75rem 1rem; background: var(--dark); border: 1px solid var(--glass-border); border-radius: 0.5rem; color: white; display: block; margin-bottom: 1rem; }
</style>
@endsection
