<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Activity;
use App\Services\NetworkStrategyEngine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class NetworkStrategyEngineTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_tracks_daily_outreach_progress()
    {
        $contact = Contact::create(['name' => 'John Doe', 'status' => 'new']);
        
        // Create 1 activity for today
        Activity::create([
            'contact_id' => $contact->id,
            'type' => 'outreach',
            'status' => 'completed',
            'scheduled_at' => Carbon::today(),
        ]);

        $engine = new NetworkStrategyEngine();
        $status = $engine->getStrategyStatus();

        $this->assertEquals(1, $status['daily']['actual']);
        $this->assertEquals(50, $status['daily']['percentage']);
    }

    public function test_it_suggests_new_contacts_for_outreach()
    {
        Contact::create(['name' => 'High Priority', 'status' => 'new', 'priority' => 10]);
        Contact::create(['name' => 'Low Priority', 'status' => 'new', 'priority' => 1]);

        $engine = new NetworkStrategyEngine();
        $suggestions = $engine->getSuggestions();

        $this->assertEquals('High Priority', $suggestions['outreach']->first()->name);
    }

    public function test_it_suggests_hot_leads_for_meetings()
    {
        $hotLead = Contact::create(['name' => 'Hot Lead', 'status' => 'hot']);
        
        $engine = new NetworkStrategyEngine();
        $suggestions = $engine->getSuggestions();

        $this->assertEquals('Hot Lead', $suggestions['meetings']->first()->name);
    }
}
