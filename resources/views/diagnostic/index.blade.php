@extends('layouts.app')

@section('title', __('Lead Quality Diagnostic Tool') . ' — LeadOS')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div style="text-align: center; margin-bottom: 2.5rem;">
        <h2 style="font-size: 2rem; margin-bottom: 0.5rem;">{{ __('Diagnostic Tool') }}</h2>
        <p style="color: var(--gray); font-size: 1.1rem;">{{ __('Assess your revenue risk and lead quality process in 10 simple questions.') }}</p>
    </div>

    <form action="{{ route('diagnostic.store') }}" method="POST">
        @csrf

        @php
            $questions = [
                __("1. Do you have a clearly defined Ideal Customer Profile (ICP) documented?"),
                __("2. Are you actively qualifying leads against your ICP before outreach?"),
                __("3. Do you consistently target decision-makers rather than lower-level staff?"),
                __("4. Are you tracking the source of every lead in a centralized system (CRM)?"),
                __("5. Do you know your exact customer acquisition cost (CAC) per lead source?"),
                __("6. Is your outreach personalized to the lead's specific industry and role?"),
                __("7. Do you follow a specific follow-up strategy (e.g., the 2/2/2 method)?"),
                __("8. Do you regularly clean your CRM to remove dead or junk leads?"),
                __("9. Are your sales and marketing definitions of a 'Good Lead' perfectly aligned?"),
                __("10. Do you track the reasons why deals are lost (e.g., budget, timing, fit)?"),
            ];
        @endphp

        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            @foreach($questions as $index => $q)
                <div style="background: var(--glass); border: 1px solid var(--glass-border); padding: 1.5rem; border-radius: 0.75rem;">
                    <p style="font-weight: 500; font-size: 1.05rem; margin-bottom: 1rem;">{{ $q }}</p>
                    <div style="display: flex; gap: 1.5rem; align-items: center;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="radio" name="q{{ $index + 1 }}" value="10" required>
                            <span>{{ __('Yes, perfectly') }}</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="radio" name="q{{ $index + 1 }}" value="5" required>
                            <span>{{ __('Somewhat / Partially') }}</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="radio" name="q{{ $index + 1 }}" value="0" required>
                            <span>{{ __('No, not at all') }}</span>
                        </label>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 2.5rem; text-align: right;">
            <button type="submit" class="btn btn-primary" style="font-size: 1.1rem; padding: 0.75rem 2rem;">{{ __('Get Diagnostic Results 🚀') }}</button>
        </div>
    </form>
</div>
@endsection
