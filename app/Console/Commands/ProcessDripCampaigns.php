<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProcessDripCampaigns extends Command
{
    protected $signature = 'sequences:process';

    protected $description = 'Process active drip campaigns and send follow-ups';

    public function handle(\App\Services\TemplateService $templateService)
    {
        $this->info("Scanning for due sequence steps...");
        
        $contacts = \App\Models\Contact::whereHas('sequences', function ($query) {
            $query->where('status', 'active')
                  ->where('next_run_at', '<=', now())
                  ->whereNotNull('current_step_id');
        })->with(['sequences' => function ($query) {
            $query->wherePivot('status', 'active')
                  ->wherePivot('next_run_at', '<=', now())
                  ->wherePivotNotNull('current_step_id');
        }])->get();

        $count = 0;

        foreach ($contacts as $contact) {
            foreach ($contact->sequences as $sequence) {
                if (!$sequence->is_active) continue;

                $currentStepId = $sequence->pivot->current_step_id;
                $currentStep = \App\Models\SequenceStep::find($currentStepId);

                if (!$currentStep) continue;

                // "Send" the email by logging an activity
                $mergedBody = $templateService->merge($currentStep->body, $contact);
                $contact->activities()->create([
                    'user_id' => $sequence->team->owner_id ?? 1,
                    'team_id' => $sequence->team_id,
                    'type' => 'outreach',
                    'status' => 'completed',
                    'scheduled_at' => now(),
                    'notes' => "Drip Campaign: {$sequence->name} (Step {$currentStep->order})\n\nSubject: {$currentStep->subject}\n\n{$mergedBody}"
                ]);
                $count++;

                // Find the next step
                $nextStep = \App\Models\SequenceStep::where('sequence_id', $sequence->id)
                    ->where('order', '>', $currentStep->order)
                    ->orderBy('order')
                    ->first();

                if ($nextStep) {
                    $contact->sequences()->updateExistingPivot($sequence->id, [
                        'current_step_id' => $nextStep->id,
                        'next_run_at' => now()->addDays($nextStep->delay_days)
                    ]);
                } else {
                    $contact->sequences()->updateExistingPivot($sequence->id, [
                        'current_step_id' => null,
                        'next_run_at' => null,
                        'status' => 'completed'
                    ]);
                }
            }
        }

        $this->info("Processed {$count} drip campaign steps.");
    }
}
