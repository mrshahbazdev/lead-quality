@extends('layouts.app')

@section('title', __('ICP Builder') . ' — LeadOS')

@section('content')
<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div style="margin-bottom: 2rem;">
        <h2>{{ __('Define Your Ideal Customer Profile') }}</h2>
        <p style="color: var(--gray);">{{ __('Set the benchmarks used to score your leads and identify high-value prospects.') }}</p>
    </div>

    <form action="{{ route('icp.store') }}" method="POST">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2.5rem;">
            <div class="form-section">
                <h3 style="margin-bottom: 1.5rem; font-size: 1.1rem; color: var(--primary-light);">{{ __('Target Industry & Role') }}</h3>
                
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--gray);">{{ __('Primary Industry') }}</label>
                    <input type="text" name="industry" value="{{ $profile->industry }}" placeholder="{{ __('e.g. Real Estate, SaaS, Manufacturing') }}" style="width: 100%; padding: 0.75rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 0.5rem; color: white;">
                </div>

                <div class="form-group">
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--gray);">{{ __('Target Decision Maker Roles (comma separated)') }}</label>
                    <input type="text" name="role" value="{{ $profile->role }}" placeholder="{{ __('e.g. CEO, Marketing Manager, Owner') }}" style="width: 100%; padding: 0.75rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 0.5rem; color: white;">
                </div>
            </div>

            <div class="form-section">
                <h3 style="margin-bottom: 1.5rem; font-size: 1.1rem; color: var(--secondary);">{{ __('Company Size & Budget') }}</h3>
                
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--gray);">{{ __('Target Employee Count Range') }}</label>
                    <select name="employee_count_range" style="width: 100%; padding: 0.75rem; background: var(--dark); border: 1px solid var(--glass-border); border-radius: 0.5rem; color: white;">
                        <option value="">{{ __('Any Size') }}</option>
                        <option value="1-10" {{ $profile->employee_count_range == '1-10' ? 'selected' : '' }}>{{ __('1-10 employees') }}</option>
                        <option value="11-50" {{ $profile->employee_count_range == '11-50' ? 'selected' : '' }}>{{ __('11-50 employees') }}</option>
                        <option value="51-200" {{ $profile->employee_count_range == '51-200' ? 'selected' : '' }}>{{ __('51-200 employees') }}</option>
                        <option value="201-500" {{ $profile->employee_count_range == '201-500' ? 'selected' : '' }}>{{ __('201-500 employees') }}</option>
                        <option value="500+" {{ $profile->employee_count_range == '500+' ? 'selected' : '' }}>{{ __('500+ employees') }}</option>
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 0.25rem; color: var(--gray);">{{ __('Min. Budget ($)') }}</label>
                        <p style="font-size: 0.7rem; color: var(--gray); margin-bottom: 0.5rem;">{{ __('Target client\'s yearly/project spend.') }}</p>
                        <input type="number" name="budget_min" value="{{ $profile->budget_min }}" step="0.01" style="width: 100%; padding: 0.75rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 0.5rem; color: white;">
                    </div>
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 0.25rem; color: var(--gray);">{{ __('Max. Budget ($)') }}</label>
                        <p style="font-size: 0.7rem; color: var(--gray); margin-bottom: 0.5rem;">{{ __('Upper limit for project/deal size.') }}</p>
                        <input type="number" name="budget_max" value="{{ $profile->budget_max }}" step="0.01" style="width: 100%; padding: 0.75rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 0.5rem; color: white;">
                    </div>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 1rem; justify-content: flex-end; border-top: 1px solid var(--glass-border); padding-top: 2rem; margin-bottom: 2rem;">
            <button type="submit" class="btn btn-primary" style="padding: 1rem 2.5rem;">{{ __('Save ICP Profile') }}</button>
        </div>
    </form>

    <!-- ICP Help Section -->
    <div style="background: rgba(255,140,0,0.05); border: 1px solid rgba(255,140,0,0.1); border-radius: 1rem; padding: 1.5rem; margin-top: 1rem;">
        <h4 style="color: var(--primary-light); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fa-solid fa-circle-info"></i> {{ __('How this works') }}
        </h4>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div>
                <p style="font-size: 0.85rem; color: #cbd5e1; margin-bottom: 0.5rem; font-weight: 600;">{{ __('What is the Budget field for?') }}</p>
                <p style="font-size: 0.85rem; color: var(--gray);">
                    {{ __('The Budget represents the average "Deal Size" or "Project Value" of your ideal client. For example, if you sell a service that costs $5,000, your Min. Budget should be $5,000. This helps the AI understand if a lead has the financial capacity to work with you.') }}
                </p>
            </div>
            <div>
                <p style="font-size: 0.85rem; color: #cbd5e1; margin-bottom: 0.5rem; font-weight: 600;">{{ __('How is the Fit Score calculated?') }}</p>
                <p style="font-size: 0.85rem; color: var(--gray);">
                    {{ __('LeadOS compares your Industry, Role, and Budget settings against your Contacts. If a lead matches all categories, they get a "High Fit" score, allowing you to focus your time only on the most profitable opportunities.') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
