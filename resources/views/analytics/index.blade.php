@extends('layouts.app')

@section('title', __('Network Analytics') . ' — LeadOS')

@section('content')
<div style="margin-bottom: 2rem;">
    <h2>{{ __('Network & Lead Quality Analytics') }}</h2>
    <p style="color: var(--gray);">{{ __('Deep dive into your 2/2/2 strategy performance and network growth patterns.') }}</p>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; margin-bottom: 2.5rem;">
    <div class="card" style="text-align: center; padding: 2rem;">
        <div style="font-size: 0.875rem; color: var(--gray); text-transform: uppercase; margin-bottom: 0.5rem;">{{ __('Strategy Fulfillment') }}</div>
        <div style="font-size: 2.5rem; font-weight: 700; color: var(--secondary);">{{ $fulfillment['success_rate'] }}%</div>
        <div style="font-size: 0.875rem; color: var(--gray); margin-top: 0.5rem;">{{ __('Last 30 Days') }}</div>
    </div>
    <div class="card" style="text-align: center; padding: 2rem;">
        <div style="font-size: 0.875rem; color: var(--gray); text-transform: uppercase; margin-bottom: 0.5rem;">{{ __('Total Interactions') }}</div>
        <div style="font-size: 2.5rem; font-weight: 700; color: var(--primary-light);">{{ $fulfillment['total_completed'] }}</div>
        <div style="font-size: 0.875rem; color: var(--gray); margin-top: 0.5rem;">{{ __('Completed Tasks') }}</div>
    </div>
    <div class="card" style="text-align: center; padding: 2rem;">
        <div style="font-size: 0.875rem; color: var(--gray); text-transform: uppercase; margin-bottom: 0.5rem;">{{ __('Growth Target') }}</div>
        <div style="font-size: 2.5rem; font-weight: 700; color: #facc15;">{{ __('On Track') }}</div>
        <div style="font-size: 0.875rem; color: var(--gray); margin-top: 0.5rem;">{{ __('Network Dynamics') }}</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <div class="card">
        <h3 style="margin-bottom: 2rem;">{{ __('Network Growth (Last 6 Months)') }}</h3>
        <div style="display: flex; align-items: flex-end; gap: 1.5rem; height: 300px; padding-bottom: 2rem; border-bottom: 1px solid var(--glass-border);">
            @foreach($growthData as $data)
                <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                    <div style="font-size: 0.75rem; color: var(--gray);">{{ $data['count'] }}</div>
                    <div style="width: 100%; height: {{ min(100, $data['count'] * 10) }}%; background: linear-gradient(to top, rgba(255, 140, 0, 0.2), var(--primary)); border-radius: 4px 4px 0 0; min-height: 5px;"></div>
                    <div style="font-size: 0.75rem; color: var(--gray); transform: rotate(-45deg); margin-top: 1rem;">{{ $data['label'] }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="card">
        <h3 style="margin-bottom: 2rem;">{{ __('Top Success Industries') }}</h3>
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            @forelse($industryMap as $industry => $count)
                <div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span style="font-weight: 600;">{{ __($industry ?? 'Other') }}</span>
                        <span style="color: var(--secondary);">{{ __(' :count Leads', ['count' => $count]) }}</span>
                    </div>
                    <div style="width: 100%; height: 6px; background: var(--glass); border-radius: 3px;">
                        <div style="width: {{ min(100, $count * 20) }}%; height: 100%; background: var(--secondary); border-radius: 3px;"></div>
                    </div>
                </div>
            @empty
                <p style="color: var(--gray); text-align: center; padding: 2rem;">{{ __('Not enough data yet.') }}</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
