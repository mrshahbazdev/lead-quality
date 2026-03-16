@extends('layouts.app')

@section('title', __('Sales Pipeline') . ' — LeadOS')

@section('content')
<style>
    .kanban-board {
        display: flex;
        gap: 1.5rem;
        overflow-x: auto;
        padding-bottom: 1rem;
        min-height: 70vh;
    }
    .kanban-column {
        flex: 0 0 300px;
        background: rgba(0, 0, 0, 0.2);
        border-radius: 1rem;
        border: 1px solid var(--glass-border);
        display: flex;
        flex-direction: column;
    }
    .kanban-header {
        padding: 1rem;
        border-bottom: 1px solid var(--glass-border);
        font-weight: 600;
        font-size: 1.1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .kanban-cards {
        padding: 1rem;
        flex-grow: 1;
        overflow-y: auto;
        min-height: 100px;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .kanban-card {
        background: var(--glass);
        border: 1px solid var(--glass-border);
        padding: 1rem;
        border-radius: 0.5rem;
        cursor: grab;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .kanban-card:active {
        cursor: grabbing;
        transform: scale(1.02);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
    }
    .kanban-card.dragging {
        opacity: 0.5;
    }
    .kanban-column.drag-over {
        background: rgba(255, 255, 255, 0.05);
        border: 1px dashed var(--primary);
    }
</style>

<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
    <div>
        <h2 style="font-size: 2rem; margin-bottom: 0.5rem;">{{ __('Sales Pipeline') }}</h2>
        <p style="color: var(--gray); font-size: 1.1rem;">{{ __('Visually track and move your leads through the sales cycle.') }}</p>
    </div>
</div>

<div class="kanban-board">
    @php
        $columns = [
            'new' => ['title' => __('🆕 New Leads'), 'color' => '#6366f1'],
            'contacted' => ['title' => __('📨 Contacted'), 'color' => '#f59e0b'],
            'meeting_set' => ['title' => __('📅 Meeting Set'), 'color' => '#3b82f6'],
            'won' => ['title' => __('🎉 Won (Customer)'), 'color' => '#10b981'],
            'lost' => ['title' => __('❌ Lost / Disqualified'), 'color' => '#ef4444'],
        ];
    @endphp

    @foreach($columns as $key => $col)
        <div class="kanban-column" data-stage="{{ $key }}">
            <div class="kanban-header" style="border-bottom-color: {{ $col['color'] }}50;">
                <span style="color: {{ $col['color'] }};">{{ $col['title'] }}</span>
                <span class="badge" style="background: var(--glass); font-size: 0.8rem;">{{ $pipeline[$key]->count() }}</span>
            </div>
            
            <div class="kanban-cards">
                @foreach($pipeline[$key] as $contact)
                    <div class="kanban-card" draggable="true" data-id="{{ $contact->id }}">
                        <div style="font-weight: 500; font-size: 1.05rem; margin-bottom: 0.25rem;">
                            <a href="{{ route('contacts.show', $contact) }}" style="color: white; text-decoration: none;">{{ $contact->name }}</a>
                        </div>
                        <div style="color: var(--gray); font-size: 0.85rem; margin-bottom: 0.75rem;">
                            {{ $contact->position }} @ {{ $contact->company }}
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem;">
                            <span style="color: var(--gray);">{{ $contact->created_at->diffForHumans() }}</span>
                            @if($contact->ai_high_probability)
                                <span title="{{ __('High Probability') }}" style="color: #f59e0b;">⭐</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const cards = document.querySelectorAll('.kanban-card');
        const dropZones = document.querySelectorAll('.kanban-cards');

        cards.forEach(card => {
            card.addEventListener('dragstart', () => {
                card.classList.add('dragging');
            });

            card.addEventListener('dragend', () => {
                card.classList.remove('dragging');
            });
        });

        dropZones.forEach(zone => {
            zone.addEventListener('dragover', e => {
                e.preventDefault();
                zone.parentElement.classList.add('drag-over');
                
                const draggingCard = document.querySelector('.dragging');
                if (draggingCard) {
                    zone.appendChild(draggingCard);
                }
            });

            zone.addEventListener('dragleave', () => {
                zone.parentElement.classList.remove('drag-over');
            });

            zone.addEventListener('drop', e => {
                e.preventDefault();
                zone.parentElement.classList.remove('drag-over');
                
                const draggingCard = document.querySelector('.dragging');
                if (draggingCard) {
                    const stage = zone.parentElement.getAttribute('data-stage');
                    const contactId = draggingCard.getAttribute('data-id');
                    
                    // Update header count visually
                    updateCounts();

                    // Send AJAX request
                    fetch('{{ route('pipeline.update-stage') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            contact_id: contactId,
                            stage: stage
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(!data.success) {
                            alert('Failed to update stage. Please refresh.');
                        }
                    })
                    .catch(() => alert('Network error. Please try again.'));
                }
            });
        });

        function updateCounts() {
            document.querySelectorAll('.kanban-column').forEach(col => {
                const count = col.querySelectorAll('.kanban-card').length;
                col.querySelector('.kanban-header .badge').innerText = count;
            });
        }
    });
</script>
@endsection
