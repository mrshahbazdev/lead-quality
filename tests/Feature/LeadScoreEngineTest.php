<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\IcpProfile;
use App\Services\LeadScoreEngine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadScoreEngineTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_calculates_perfect_score_for_matching_contact()
    {
        $profile = IcpProfile::create([
            'industry' => 'Real Estate',
            'employee_count_range' => '11-50',
            'budget_min' => 5000,
            'budget_max' => 15000,
            'role' => 'CEO, Owner',
            'location' => 'Berlin',
        ]);

        $contact = new Contact([
            'industry' => 'Real Estate',
            'employee_count_range' => '11-50',
            'budget' => 10000,
            'role' => 'CEO',
        ]);

        $engine = new LeadScoreEngine();
        $result = $engine->calculateScore($contact, $profile);

        $this->assertEquals(100, $result['total_score']);
        $this->assertEquals('🟢 Good Lead', $result['status']);
        $this->assertEmpty($result['issues']);
    }

    public function test_it_identifies_mismatches()
    {
        $profile = IcpProfile::create([
            'industry' => 'Real Estate',
            'role' => 'CEO',
        ]);

        $contact = new Contact([
            'industry' => 'SaaS',
            'role' => 'Manager',
        ]);

        $engine = new LeadScoreEngine();
        $result = $engine->calculateScore($contact, $profile);

        $this->assertLessThan(100, $result['total_score']);
        $this->assertContains('Industry mismatch', $result['issues']);
        $this->assertContains('Non-decision maker role', $result['issues']);
    }
}
