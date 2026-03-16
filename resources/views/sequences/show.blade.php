@extends('layouts.app')

@section('title', __('Manage Sequence: :name', ['name' => $sequence->name]) . ' — LeadOS')

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem;">
        <div>
            <a href="{{ route('sequences.index') }}" style="color: var(--primary); text-decoration: none; font-size: 0.85rem; display: inline-block; margin-bottom: 0.5rem;">&larr; {{ __('Back to Sequences') }}</a>
            <h2 style="font-size: 2rem; margin-bottom: 0.5rem;">{{ $sequence->name }}</h2>
            <span class="badge {{ $sequence->is_active ? 'badge-good' : 'badge-avg' }}">
                {{ $sequence->is_active ? __('Active') : __('Paused') }}
            </span>
        </div>
        <div style="text-align: right;">
            <div style="font-size: 2rem; font-weight: bold; color: white;">{{ $sequence->contacts->count() }}</div>
            <div style="color: var(--gray); font-size: 0.85rem;">{{ __('Enrolled Contacts') }}</div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
        
        <!-- Steps Builder -->
        <div>
            <h3 style="font-size: 1.25rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 0.5rem;">{{ __('Sequence Steps') }}</h3>
            
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                @forelse($sequence->steps as $index => $step)
                    <div style="background: var(--dark-2); border: 1px solid var(--glass-border); border-radius: 0.75rem; padding: 1.25rem; position: relative;">
                        @if($index > 0)
                            <div style="position: absolute; top: -1.5rem; left: 2rem; width: 2px; height: 1.5rem; background: var(--primary);"></div>
                        @endif
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                            <span style="font-weight: 600; color: var(--primary-light);">{{ __('Step :order (Wait :days days)', ['order' => $step->order, 'days' => $step->delay_days]) }}</span>
                        </div>
                        <div style="font-size: 1.1rem; margin-bottom: 0.75rem; color: white;">{{ __('Subject: :subject', ['subject' => $step->subject]) }}</div>
                        <div style="color: var(--gray); font-size: 0.9rem; white-space: pre-wrap; background: var(--dark); padding: 1rem; border-radius: 0.5rem; line-height: 1.5;">{{ $step->body }}</div>
                    </div>
                @empty
                    <p style="color: var(--gray);">{{ __('No steps added yet.') }}</p>
                @endforelse
            </div>

            <!-- Add Step Form -->
            <div style="margin-top: 2rem; background: var(--glass); border: 1px dashed var(--glass-border); border-radius: 0.75rem; padding: 1.5rem;">
                <h4 style="margin-bottom: 1rem; color: var(--primary);">+ {{ __('Add New Step') }}</h4>
                <form action="{{ route('sequences.steps.store', $sequence) }}" method="POST">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 3fr; gap: 1rem; margin-bottom: 1rem;">
                        <div>
                            <label style="display: block; font-size: 0.85rem; color: var(--gray); margin-bottom: 0.25rem;">{{ __('Delay (Days)') }}</label>
                            <input type="number" name="delay_days" class="form-control" value="1" min="0" required>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.85rem; color: var(--gray); margin-bottom: 0.25rem;">{{ __('Email Subject') }}</label>
                            <input type="text" name="subject" class="form-control" placeholder="{{ __('Quick question...') }}" required>
                        </div>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.85rem; color: var(--gray); margin-bottom: 0.25rem;">{{ __('Email Body') }}</label>
                        <textarea name="body" class="form-control" rows="5" placeholder="{{ __('Hi [Name], ...') }}" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="margin-top: 0.5rem;">{{ __('Save Step') }}</button>
                </form>
            </div>
        </div>

        <!-- Enrolled Contacts List -->
        <div>
            <div style="background: var(--dark-2); border: 1px solid var(--glass-border); border-radius: 0.75rem; padding: 1.25rem;">
                <h3 style="font-size: 1.1rem; margin-bottom: 1rem;">{{ __('Enrolled Contacts') }}</h3>
                <ul style="list-style: none; padding: 0;">
                    @forelse($sequence->contacts as $contact)
                        <li style="padding: 0.75rem 0; border-bottom: 1px solid var(--glass-border); font-size: 0.9rem;">
                            <div style="font-weight: 500;">
                                <a href="{{ route('contacts.show', $contact) }}" style="color: white; text-decoration: none;">{{ $contact->name }}</a>
                            </div>
                            <div style="color: var(--gray); font-size: 0.8rem; margin-top: 0.25rem; display: flex; justify-content: space-between;">
                                <span>{{ __($contact->pivot->status) }}</span>
                                <span style="color: var(--primary-light);">{{ __('Step') }} {{ $contact->pivot->current_step_id ? __('Current') : __('Done') }}</span>
                            </div>
                        </li>
                    @empty
                        <li style="color: var(--gray); font-size: 0.85rem;">{{ __('No contacts enrolled. Find a contact and enroll them from their profile.') }}</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control { width: 100%; padding: 0.65rem 1rem; background: var(--dark); border: 1px solid var(--glass-border); border-radius: 0.5rem; color: white; display: block; margin-bottom: 0.5rem; }
    textarea.form-control { resize: vertical; }
</style>
@endsection
