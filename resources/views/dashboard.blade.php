@extends('layouts.app')

@section('title', __('Dashboard') . ' — LeadOS')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 style="font-size: 1.75rem; font-weight: 600; margin-bottom: 0.5rem;">{{ __('Welcome back, :name 👋', ['name' => $user->name]) }}</h2>
        <p style="color: var(--gray); font-size: 0.95rem;">{{ __("Here's what's happening in your workspace today.") }}</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <!-- Stat Cards -->
    <div style="background: var(--glass); border: 1px solid var(--glass-border); border-radius: 1rem; padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
            <div style="color: var(--gray); font-size: 0.9rem; font-weight: 500;">{{ __('Total Leads') }}</div>
            <div style="width: 36px; height: 36px; border-radius: 0.5rem; background: rgba(255, 140, 0, 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>
        <div>
            <div style="font-size: 2.25rem; font-weight: bold; color: white;">{{ $totalLeads }}</div>
            <div style="color: var(--gray); font-size: 0.8rem; margin-top: 0.25rem;">{{ __('In your current workspace') }}</div>
        </div>
    </div>
    
    <div style="background: var(--glass); border: 1px solid var(--glass-border); border-radius: 1rem; padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
            <div style="color: var(--gray); font-size: 0.9rem; font-weight: 500;">{{ __('High Quality Leads') }}</div>
            <div style="width: 36px; height: 36px; border-radius: 0.5rem; background: rgba(16, 185, 129, 0.1); color: #10b981; display: flex; align-items: center; justify-content: center;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <div>
            <div style="font-size: 2.25rem; font-weight: bold; color: #10b981;">{{ $goodLeadsCount }}</div>
            <div style="color: var(--gray); font-size: 0.8rem; margin-top: 0.25rem;">{{ __('Ideal customer profile match') }}</div>
        </div>
    </div>
    
    <div style="background: var(--glass); border: 1px solid var(--glass-border); border-radius: 1rem; padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
            <div style="color: var(--gray); font-size: 0.9rem; font-weight: 500;">{{ __('Average Lead Score') }}</div>
            <div style="width: 36px; height: 36px; border-radius: 0.5rem; background: rgba(245, 158, 11, 0.1); color: #f59e0b; display: flex; align-items: center; justify-content: center;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
        </div>
        <div>
            <div style="font-size: 2.25rem; font-weight: bold; color: #f59e0b;">{{ number_format($avgScore, 1) }}%</div>
            <div style="color: var(--gray); font-size: 0.8rem; margin-top: 0.25rem;">{{ __('Overall workspace quality') }}</div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr; gap: 2rem; margin-bottom: 2rem;">
    <!-- 2/2/2 Strategy Progress -->
    <div style="background: var(--glass); border: 1px solid var(--glass-border); border-radius: 1rem; padding: 1.5rem;">
        <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            {{ __('2/2/2 Strategy Progress') }}
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
            @foreach(['daily', 'weekly', 'monthly'] as $key)
                @if(isset($strategyStatus[$key]))
                    <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 0.75rem; padding: 1rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                            <strong style="color: #e2e8f0;">{{ $strategyStatus[$key]['label'] }}</strong>
                            <span style="font-size: 0.85rem; color: var(--gray);">{{ $strategyStatus[$key]['actual'] }} / {{ $strategyStatus[$key]['target'] }}</span>
                        </div>
                        <div style="width: 100%; height: 8px; background: rgba(255,255,255,0.1); border-radius: 4px; overflow: hidden;">
                            <div style="height: 100%; width: {{ $strategyStatus[$key]['percentage'] }}%; background: var(--primary); border-radius: 4px;"></div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <!-- Left Column: Strategy Suggestions -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <div style="background: var(--glass); border: 1px solid var(--glass-border); border-radius: 1rem; padding: 1.5rem;">
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                {{ __('AI Strategy Suggestions') }}
            </h3>
            
            <div style="display: grid; grid-template-columns: 1fr; gap: 1rem;">
                <!-- Outreach -->
                @if(isset($suggestions['outreach']) && count($suggestions['outreach']) > 0)
                    <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 0.75rem; padding: 1.25rem;">
                        <div style="color: var(--primary); font-weight: 600; font-size: 0.9rem; margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">{{ __('New Outreach Targets') }}</div>
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            @foreach($suggestions['outreach'] as $contact)
                                <li style="padding: 0.5rem 0; {{ !$loop->last ? 'border-bottom: 1px solid rgba(255,255,255,0.05);' : '' }}">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <div>
                                            <a href="{{ route('contacts.show', $contact->id) }}" style="color: white; text-decoration: none; font-weight: 500;">{{ $contact->name }}</a>
                                            <span style="color: var(--gray); font-size: 0.85rem; margin-left: 0.5rem;">{{ $contact->company }}</span>
                                        </div>
                                        <a href="{{ route('contacts.show', $contact->id) }}" class="btn" style="padding: 0.25rem 0.75rem; font-size: 0.8rem; background: rgba(255, 140, 0, 0.2); color: var(--primary);">{{ __('View') }}</a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Maintenance -->
                @if(isset($suggestions['maintenance']) && count($suggestions['maintenance']) > 0)
                    <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 0.75rem; padding: 1.25rem;">
                        <div style="color: #f59e0b; font-weight: 600; font-size: 0.9rem; margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">{{ __('Needs Maintenance Follow-up') }}</div>
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            @foreach($suggestions['maintenance'] as $contact)
                                <li style="padding: 0.5rem 0; {{ !$loop->last ? 'border-bottom: 1px solid rgba(255,255,255,0.05);' : '' }}">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <div>
                                            <a href="{{ route('contacts.show', $contact->id) }}" style="color: white; text-decoration: none; font-weight: 500;">{{ $contact->name }}</a>
                                            <span style="color: var(--gray); font-size: 0.85rem; margin-left: 0.5rem;">{{ $contact->company }}</span>
                                        </div>
                                        <a href="{{ route('contacts.show', $contact->id) }}" class="btn" style="padding: 0.25rem 0.75rem; font-size: 0.8rem; background: rgba(245, 158, 11, 0.2); color: #f59e0b;">{{ __('View') }}</a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @if(empty($suggestions['outreach']) && empty($suggestions['maintenance']) && empty($suggestions['meetings']))
                    <div style="text-align: center; padding: 2rem; color: var(--gray);">
                        <p>{{ __("No new suggestions at the moment. You're all caught up!") }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column: Recent Activity -->
    <div>
        <div style="background: var(--glass); border: 1px solid var(--glass-border); border-radius: 1rem; padding: 1.5rem; height: 100%;">
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ __('Recent Activity') }}
            </h3>
            
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                @if(count($recentActivities) > 0)
                    @foreach($recentActivities as $activity)
                        <div style="display: flex; gap: 1rem; align-items: flex-start;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: rgba(255, 140, 0, 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                                @if($activity->type == 'email') ✉️ 
                                @elseif($activity->type == 'meeting') 📅
                                @elseif($activity->type == 'call') 📞
                                @else ⭐ @endif
                            </div>
                            <div>
                                <div style="font-size: 0.9rem; color: #e2e8f0;">
                                    <strong style="color: white; text-transform: capitalize;">{{ __($activity->type) }}</strong> 
                                    @if($activity->contact)
                                        {{ __('with') }} <a href="{{ route('contacts.show', $activity->contact_id) }}" style="color: var(--primary); text-decoration: none;">{{ $activity->contact->name }}</a>
                                    @endif
                                </div>
                                <div style="font-size: 0.8rem; color: var(--gray); margin-top: 0.25rem;">
                                    {{ Str::limit(strip_tags($activity->notes), 60) }}
                                </div>
                                <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem;">
                                    {{ $activity->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div style="text-align: center; padding: 2rem; color: var(--gray);">
                        <p>{{ __('No recent activities found.') }}</p>
                    </div>
                @endif
            </div>
            
            <div style="margin-top: 2rem; text-align: center;">
                <a href="{{ route('pipeline.index') }}" class="btn" style="background: rgba(255,255,255,0.05); color: white; width: 100%; border: 1px solid rgba(255,255,255,0.1);">{{ __('View Pipeline') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection
