@extends('layouts.app')

@section('title', __('Diagnostic Results') . ' — LeadOS')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto; text-align: center;">
    <h2 style="font-size: 2rem; margin-bottom: 0.5rem;">{{ __('Your Analysis Complete') }}</h2>
    <p style="color: var(--gray); font-size: 1.1rem; margin-bottom: 2rem;">{{ __('Here is your Lead Quality & Revenue Risk report.') }}</p>

    <div style="background: var(--glass); border: 1px solid var(--glass-border); border-radius: 1rem; padding: 3rem 2rem; margin-bottom: 2.5rem;">
        <div style="font-size: 1.2rem; color: var(--gray); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1rem;">{{ __('Process Effectiveness Score') }}</div>
        <div style="display: inline-flex; justify-content: center; align-items: center; width: 150px; height: 150px; border-radius: 50%; border: 8px solid {{ $score >= 80 ? '#10b981' : ($score >= 50 ? '#fbbf24' : '#ef4444') }}; background: var(--dark); margin-bottom: 1.5rem;">
            <span style="font-size: 3rem; font-weight: 700;">{{ $score }}<span style="font-size: 1.5rem;">%</span></span>
        </div>
        
        <div>
            <span class="badge {{ $riskClass }}" style="font-size: 1.1rem; padding: 0.75rem 1.5rem;">
                {{ __($risk) }}
            </span>
        </div>
    </div>

    <div style="text-align: left;">
        <h3 style="margin-bottom: 1.5rem;">{{ __('Recommended Roadmap') }}</h3>
        
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            @if($score < 50)
                <div style="border-left: 4px solid #ef4444; padding-left: 1rem; background: rgba(239, 68, 68, 0.05); padding: 1rem; border-radius: 0 0.5rem 0.5rem 0;">
                    <h4 style="color: #ef4444; margin-bottom: 0.5rem;">{{ __('1. Define Your ICP Immediately') }}</h4>
                    <p style="color: #cbd5e1; font-size: 0.9rem;">{{ __('You are currently wasting resources on unqualified prospects. Use our ICP Builder to define exactly who your perfect buyer is.') }}</p>
                </div>
                <div style="border-left: 4px solid #f59e0b; padding-left: 1rem; background: rgba(245, 158, 11, 0.05); padding: 1rem; border-radius: 0 0.5rem 0.5rem 0;">
                    <h4 style="color: #f59e0b; margin-bottom: 0.5rem;">{{ __('2. Implement the 2/2/2 Strategy') }}</h4>
                    <p style="color: #cbd5e1; font-size: 0.9rem;">{{ __('Begin structuring your outreach. Start with 2 new contacts daily, 2 maintenance touchpoints weekly, and 2 meetings monthly to build a healthy pipeline.') }}</p>
                </div>
            @elseif($score < 80)
                <div style="border-left: 4px solid #f59e0b; padding-left: 1rem; background: rgba(245, 158, 11, 0.05); padding: 1rem; border-radius: 0 0.5rem 0.5rem 0;">
                    <h4 style="color: #f59e0b; margin-bottom: 0.5rem;">{{ __('1. Align Sales & Marketing Definitions') }}</h4>
                    <p style="color: #cbd5e1; font-size: 0.9rem;">{{ __('Ensure your qualification criteria is strictly enforced before a lead is passed to the next stage.') }}</p>
                </div>
                <div style="border-left: 4px solid #10b981; padding-left: 1rem; background: rgba(16, 185, 129, 0.05); padding: 1rem; border-radius: 0 0.5rem 0.5rem 0;">
                    <h4 style="color: #10b981; margin-bottom: 0.5rem;">{{ __('2. Automate Lead Scoring') }}</h4>
                    <p style="color: #cbd5e1; font-size: 0.9rem;">{{ __('Start using our AI Analysis tools to automatically evaluate your leads and highlight High Probability Customers.') }}</p>
                </div>
            @else
                <div style="border-left: 4px solid #10b981; padding-left: 1rem; background: rgba(16, 185, 129, 0.05); padding: 1rem; border-radius: 0 0.5rem 0.5rem 0;">
                    <h4 style="color: #10b981; margin-bottom: 0.5rem;">{{ __('1. Optimize Acquisition Costs') }}</h4>
                    <p style="color: #cbd5e1; font-size: 0.9rem;">{{ __('Your foundational processes are excellent. Focus now on trimming lead sources that have a high CAC and doubling down on what works.') }}</p>
                </div>
                <div style="border-left: 4px solid #ffa333; padding-left: 1rem; background: rgba(255, 140, 0, 0.05); padding: 1rem; border-radius: 0 0.5rem 0.5rem 0;">
                    <h4 style="color: #ffa333; margin-bottom: 0.5rem;">{{ __('2. Advanced Gamification') }}</h4>
                    <p style="color: #cbd5e1; font-size: 0.9rem;">{{ __('Maintain momentum by locking in your 2/2/2 outreach streaks and using our Dashboard to monitor overall network health.') }}</p>
                </div>
            @endif
        </div>
        
        <div style="margin-top: 3rem; text-align: center;">
            <a href="{{ route('dashboard') }}" class="btn btn-primary" style="padding: 0.75rem 2rem;">{{ __('Return to Dashboard') }}</a>
        </div>
    </div>
</div>
@endsection
