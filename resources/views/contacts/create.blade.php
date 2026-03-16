@extends('layouts.app')

@section('title', 'Add New Contact')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <form action="{{ route('contacts.store') }}" method="POST">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--gray);">Full Name</label>
                <input type="text" name="name" required style="width: 100%; padding: 0.75rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 0.5rem; color: white;">
            </div>
            
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--gray);">Company</label>
                <input type="text" name="company" style="width: 100%; padding: 0.75rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 0.5rem; color: white;">
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--gray);">Position</label>
                <input type="text" name="position" style="width: 100%; padding: 0.75rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 0.5rem; color: white;">
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--gray);">Industry</label>
                <input type="text" name="industry" style="width: 100%; padding: 0.75rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 0.5rem; color: white;">
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--gray);">Role / Decision Maker</label>
                <input type="text" name="role" placeholder="e.g. Owner, Manager" style="width: 100%; padding: 0.75rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 0.5rem; color: white;">
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--gray);">Budget ($)</label>
                <input type="number" name="budget" step="0.01" style="width: 100%; padding: 0.75rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 0.5rem; color: white;">
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--gray);">Email Address</label>
                <input type="email" name="email" placeholder="Optional" style="width: 100%; padding: 0.75rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 0.5rem; color: white;">
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--gray);">Website URL</label>
                <input type="url" name="website" placeholder="https://" style="width: 100%; padding: 0.75rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 0.5rem; color: white;">
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--gray);">LinkedIn URL</label>
                <input type="url" name="linkedin" placeholder="https://linkedin.com/in/..." style="width: 100%; padding: 0.75rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 0.5rem; color: white;">
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 2rem;">
            <label style="display: block; margin-bottom: 0.5rem; color: var(--gray);">Notes</label>
            <textarea name="notes" rows="4" style="width: 100%; padding: 0.75rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 0.5rem; color: white; resize: none;"></textarea>
        </div>

        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('contacts.index') }}" class="btn" style="background: transparent; color: var(--gray);">Cancel</a>
            <button type="submit" class="btn btn-primary">Create Contact</button>
        </div>
    </form>
</div>
@endsection
