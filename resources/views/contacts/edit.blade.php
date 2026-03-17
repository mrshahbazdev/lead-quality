@extends('layouts.app')

@section('title', __('Edit Contact') . ' — LeadOS')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <h2 style="margin-bottom: 2rem;">{{ __('Edit Contact') }}: {{ $contact->name }}</h2>

    <form action="{{ route('contacts.update', $contact) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--gray);">{{ __('Full Name') }}</label>
                <input type="text" name="name" value="{{ old('name', $contact->name) }}" required style="width: 100%; padding: 0.75rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 0.5rem; color: white;">
            </div>
            
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--gray);">{{ __('Company') }}</label>
                <input type="text" name="company" value="{{ old('company', $contact->company) }}" style="width: 100%; padding: 0.75rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 0.5rem; color: white;">
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--gray);">{{ __('Position') }}</label>
                <input type="text" name="position" value="{{ old('position', $contact->position) }}" style="width: 100%; padding: 0.75rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 0.5rem; color: white;">
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--gray);">{{ __('Industry') }}</label>
                <input type="text" name="industry" value="{{ old('industry', $contact->industry) }}" style="width: 100%; padding: 0.75rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 0.5rem; color: white;">
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--gray);">{{ __('Role / Decision Maker') }}</label>
                <input type="text" name="role" value="{{ old('role', $contact->role) }}" placeholder="{{ __('e.g. Owner, Manager') }}" style="width: 100%; padding: 0.75rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 0.5rem; color: white;">
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--gray);">{{ __('Budget ($)') }}</label>
                <input type="number" name="budget" step="0.01" value="{{ old('budget', $contact->budget) }}" style="width: 100%; padding: 0.75rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 0.5rem; color: white;">
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--gray);">{{ __('Email Address') }}</label>
                <input type="email" name="email" value="{{ old('email', $contact->email) }}" placeholder="{{ __('Optional') }}" style="width: 100%; padding: 0.75rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 0.5rem; color: white;">
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--gray);">{{ __('Website URL') }}</label>
                <input type="url" name="website" value="{{ old('website', $contact->website) }}" placeholder="https://" style="width: 100%; padding: 0.75rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 0.5rem; color: white;">
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--gray);">{{ __('LinkedIn URL') }}</label>
                <input type="url" name="linkedin" value="{{ old('linkedin', $contact->linkedin) }}" placeholder="https://linkedin.com/in/..." style="width: 100%; padding: 0.75rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 0.5rem; color: white;">
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 2rem;">
            <label style="display: block; margin-bottom: 0.5rem; color: var(--gray);">{{ __('Notes') }}</label>
            <textarea name="notes" rows="4" style="width: 100%; padding: 0.75rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 0.5rem; color: white; resize: none;">{{ old('notes', $contact->notes) }}</textarea>
        </div>

        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('contacts.show', $contact) }}" class="btn" style="background: transparent; color: var(--gray);">{{ __('Cancel') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Update Contact') }}</button>
        </div>
    </form>
</div>
@endsection
