<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OutreachTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\OutreachTemplate::create([
            'name' => 'Initial Contact (LinkedIn)',
            'type' => 'initial',
            'content' => "Hi {name}, I saw your work at {company} and was impressed by your focus on {industry}. I'd love to connect and share some insights regarding our new Lead Quality initiatives.",
        ]);

        \App\Models\OutreachTemplate::create([
            'name' => 'Follow-Up (Standard)',
            'type' => 'follow-up',
            'content' => "Hi {name}, hope you're having a great week! Just circling back on my previous message regarding {industry} trends. Would love to hear your thoughts.",
        ]);

        \App\Models\OutreachTemplate::create([
            'name' => 'Meeting Proposal',
            'type' => 'initial',
            'content' => "Hello {name}, based on our recent interaction, I think there's a great synergy between our goals. Would you be open to a 15-minute intro call next week? Here's my calendar: [Link]",
        ]);

        \App\Models\OutreachTemplate::create([
            'name' => 'Reactivation (Cold Contact)',
            'type' => 'reactive',
            'content' => "Hi {name}, it's been a while since we last spoke! I noticed {company} has been making great strides in {industry}. I'd love to catch up and see if there's any way we can support your growth this quarter.",
        ]);
    }
}
