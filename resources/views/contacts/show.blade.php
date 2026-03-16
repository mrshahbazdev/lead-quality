@extends('layouts.app')

@section('title', __('Contact Details') . ' — LeadOS')

@section('content')
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem;">
            <div>
                <h2 style="font-size: 2rem;">{{ $contact->name }}</h2>
                <p style="color: var(--gray); font-size: 1.1rem;">{{ $contact->position }} at <span style="color: white;">{{ $contact->company }}</span></p>
            </div>
            <div style="display: flex; gap: 0.5rem; align-items: center;">
                @if($contact->ai_high_probability)
                    <span class="badge" style="background: rgba(245, 158, 11, 0.15); color: #f59e0b; padding: 0.5rem 1rem; font-size: 1rem; border: 1px solid rgba(245, 158, 11, 0.3); box-shadow: 0 0 10px rgba(245, 158, 11, 0.2);">
                        ⭐ {{ __('High Probability Customer') }}
                    </span>
                @endif
                <span class="badge {{ $contact->analysis['total_score'] >= 70 ? 'badge-good' : ($contact->analysis['total_score'] >= 40 ? 'badge-avg' : 'badge-bad') }}" style="padding: 0.5rem 1rem; font-size: 1rem;">
                    {{ $contact->analysis['status'] }}
                </span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 3rem;">
            <div class="info-block">
                <span style="color: var(--gray); font-size: 0.875rem; text-transform: uppercase;">{{ __('Industry') }}</span>
                <p style="font-weight: 500;">{{ $contact->industry ?? __('Not specified') }}</p>
            </div>
            <div class="info-block">
                <span style="color: var(--gray); font-size: 0.875rem; text-transform: uppercase;">{{ __('Budget') }}</span>
                <p style="font-weight: 500;">{{ $contact->budget ? '$' . number_format($contact->budget) : __('Not specified') }}</p>
            </div>
            <div class="info-block">
                <span style="color: var(--gray); font-size: 0.875rem; text-transform: uppercase;">{{ __('Fit Score') }}</span>
                <p style="font-size: 1.5rem; font-weight: 700; color: var(--primary-light);">{{ $contact->analysis['total_score'] }}%</p>
            </div>
            <div class="info-block">
                <span style="color: var(--gray); font-size: 0.875rem; text-transform: uppercase;">{{ __('Lead Source') }}</span>
                <p style="font-weight: 500; display: flex; align-items: center; gap: 0.5rem;">
                    {{ $contact->source ?? __('Unknown') }}
                    <span class="source-info-toggle" style="cursor: help; color: var(--gray); font-size: 0.8rem;" title="{{ __('Where does this info come from?') }}">
                        <i class="fa-solid fa-circle-question"></i>
                    </span>
                </p>
                <div class="source-tooltip" style="display: none; background: var(--dark-2); border: 1px solid var(--glass-border); padding: 0.75rem; border-radius: 0.5rem; font-size: 0.75rem; color: var(--gray); margin-top: 0.5rem;">
                    <ul style="list-style: none; padding: 0;">
                        <li><strong>• Chrome Extension:</strong> {{ __('Scraped from LinkedIn.') }}</li>
                        <li><strong>• Inbox Scan:</strong> {{ __('Found in your connected email.') }}</li>
                        <li><strong>• CSV Import:</strong> {{ __('Uploaded via spreadsheet.') }}</li>
                        <li><strong>• Manual Entry:</strong> {{ __('Added by you in LeadOS.') }}</li>
                    </ul>
                </div>
            </div>

            <script>
                document.querySelector('.source-info-toggle').addEventListener('click', function() {
                    const tooltip = document.querySelector('.source-tooltip');
                    tooltip.style.display = tooltip.style.display === 'none' ? 'block' : 'none';
                });
            </script>
            @if($contact->email)
            <div class="info-block">
                <span style="color: var(--gray); font-size: 0.875rem; text-transform: uppercase;">{{ __('Email') }}</span>
                <p style="font-weight: 500;"><a href="mailto:{{ $contact->email }}" style="color: var(--primary-light); text-decoration: none;">{{ $contact->email }}</a></p>
            </div>
            @endif
            @if($contact->website)
            <div class="info-block">
                <span style="color: var(--gray); font-size: 0.875rem; text-transform: uppercase;">{{ __('Website') }}</span>
                <p style="font-weight: 500;"><a href="{{ $contact->website }}" target="_blank" style="color: var(--primary-light); text-decoration: none;">{{ parse_url($contact->website, PHP_URL_HOST) ?? __('Visit') }}</a></p>
            </div>
            @endif
            @if($contact->linkedin)
            <div class="info-block">
                <span style="color: var(--gray); font-size: 0.875rem; text-transform: uppercase;">{{ __('LinkedIn') }}</span>
                <p style="font-weight: 500;"><a href="{{ $contact->linkedin }}" target="_blank" style="color: var(--primary-light); text-decoration: none;">{{ __('View Profile') }}</a></p>
            </div>
            @endif
        </div>

        <div class="analysis-results" style="background: rgba(0,0,0,0.2); border-radius: 1rem; padding: 1.5rem; margin-bottom: 2rem;">
            <h3 style="margin-bottom: 1rem; font-size: 1rem;">{{ __('ICP Fit Analysis') }}</h3>
            @if(empty($contact->analysis['issues']))
                <p style="color: var(--secondary);">✨ {{ __('Perfect match for your ICP!') }}</p>
            @else
                <ul style="color: #f87171; list-style: none;">
                    @foreach($contact->analysis['issues'] as $issue)
                        <li style="margin-bottom: 0.5rem;">⚠️ {{ $issue }}</li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="ai-insights" style="background: linear-gradient(135deg, rgba(255, 140, 0, 0.1), rgba(16, 185, 129, 0.1)); border: 1px solid rgba(255,140,0,0.2); border-radius: 1rem; padding: 1.5rem; margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                <h3 style="font-size: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <span style="font-size: 1.25rem;">🤖</span> {{ __('AI-Powered Lead Insights') }}
                </h3>
                <form action="{{ route('contacts.ai-analyze', $contact) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn" style="background: rgba(255, 140, 0, 0.2); color: #ffa333; border: 1px solid rgba(255, 140, 0, 0.4); padding: 0.4rem 0.8rem; font-size: 0.75rem;">
                        ✨ {{ __('Analyze with AI') }}
                    </button>
                </form>
            </div>
            <ul style="list-style: none; padding: 0;">
                @foreach($aiInsights as $insight)
                    <li style="margin-bottom: 0.75rem; font-size: 0.875rem; color: #e2e8f0; line-height: 1.5;">{{ $insight }}</li>
                @endforeach
            </ul>
        </div>

        <div class="sequence-enrollment" style="background: rgba(0,0,0,0.2); border-radius: 1rem; padding: 1.5rem; margin-bottom: 2rem;">
            <h3 style="margin-bottom: 1rem; font-size: 1rem; color: var(--primary-light);">✉️ {{ __('Add to Drip Campaign') }}</h3>
            @php $currentSequence = $contact->sequences->first(); @endphp
            @if($currentSequence)
                <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); padding: 1rem; border-radius: 0.5rem;">
                    <span style="color: #10b981; font-weight: bold;">✓ {{ __('Enrolled in ":name"', ['name' => $currentSequence->name]) }}</span>
                    <div style="font-size: 0.85rem; color: var(--gray); margin-top: 0.25rem;">
                        {{ __('Status') }}: {{ __($contact->sequences->first()->pivot->status) }}
                    </div>
                </div>
            @else
                <form action="{{ route('sequences.enroll') }}" method="POST" style="display: flex; gap: 0.5rem;">
                    @csrf
                    <input type="hidden" name="contact_id" value="{{ $contact->id }}">
                    <select name="sequence_id" class="form-control" required style="flex-grow: 1; margin-bottom: 0;">
                        <option value="">-- {{ __('Select a Sequence') }} --</option>
                        @foreach($sequences as $seq)
                            <option value="{{ $seq->id }}">{{ $seq->name }} ({{ $seq->steps->count() ?? 0 }} steps)</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary">{{ __('Enroll') }}</button>
                </form>
            @endif
        </div>

        <div class="outreach-templates">
            <h3 style="margin-bottom: 1.5rem;">{{ __('Outreach Templates (Semi-Auto)') }}</h3>
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                @foreach($templates as $template)
                    <div style="background: var(--glass); border: 1px solid var(--glass-border); border-radius: 1rem; padding: 1.5rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                            <span style="font-weight: 600; font-size: 0.9rem; color: var(--primary-light);">{{ $template->name }}</span>
                            <span class="badge" style="background: rgba(255,255,255,0.05); color: var(--gray);">{{ ucfirst($template->type) }}</span>
                        </div>
                        <p id="template-{{ $template->id }}" style="font-size: 0.875rem; color: #cbd5e1; margin-bottom: 1rem;">{{ $template->merged_content }}</p>
                        <button onclick="copyToClipboard('template-{{ $template->id }}')" class="btn" style="padding: 0.5rem 1rem; font-size: 0.75rem; background: var(--glass-border); color: white;">📋 {{ __('Copy Suggestion') }}</button>
                    </div>
                @endforeach
            </div>
        </div>

        <script>
            function copyToClipboard(elementId) {
                const text = document.getElementById(elementId).innerText;
                navigator.clipboard.writeText(text).then(() => {
                    alert('Template copied to clipboard! You can now paste it into LinkedIn or Email.');
                });
            }
        </script>
    </div>

    <div class="card">
        <h3 style="margin-bottom: 1.5rem;">{{ __('Activity History') }}</h3>
        <div class="timeline" style="display: flex; flex-direction: column; gap: 1.5rem;">
            @forelse($contact->activities as $activity)
                <div style="border-left: 2px solid var(--glass-border); padding-left: 1.5rem; position: relative;">
                    <div style="position: absolute; left: -6px; top: 0; width: 10px; height: 10px; border-radius: 50%; background: var(--primary);"></div>
                    <div style="font-weight: 600; text-transform: capitalize;">{{ __($activity->type) }}</div>
                    <div style="font-size: 0.875rem; color: var(--gray);">{{ $activity->scheduled_at->format('M d, Y') }}</div>
                    @if($activity->notes)
                        <div style="font-size: 0.875rem; margin-top: 0.5rem;">{{ $activity->notes }}</div>
                    @endif
                </div>
            @empty
                <p style="color: var(--gray); text-align: center; padding: 2rem;">{{ __('No activities logged yet.') }}</p>
            @endforelse
        </div>
        
        <div style="margin-top: 2rem; border-top: 1px solid var(--glass-border); padding-top: 1.5rem;">
            <h4 style="margin-bottom: 1rem; font-size: 0.9rem;">{{ __('Add Activity') }}</h4>
            <form action="{{ route('contacts.activities.store', $contact) }}" method="POST">
                @csrf
                <select name="type" class="form-control" style="margin-bottom: 1rem;">
                    <option value="outreach">{{ __('Outreach') }}</option>
                    <option value="follow-up">{{ __('Follow-up') }}</option>
                    <option value="meeting">{{ __('Meeting') }}</option>
                    <option value="reminder">{{ __('Reminder') }}</option>
                </select>
                <div style="margin-bottom: 1rem;">
                    <label style="font-size: 0.75rem; color: var(--gray); display: block; margin-bottom: 0.25rem;">{{ __('Scheduled Date (Optional)') }}</label>
                    <input type="datetime-local" name="scheduled_at" class="form-control">
                </div>
                <textarea name="notes" class="form-control" placeholder="{{ __('Notes...') }}" rows="2" style="margin-bottom: 1rem;"></textarea>
                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">{{ __('Log Activity') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
