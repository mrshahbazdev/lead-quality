<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamMigrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = \App\Models\User::all();

        foreach ($users as $user) {
            if (!$user->current_team_id) {
                // Create a default team
                $team = \App\Models\Team::create([
                    'name' => $user->name . "'s Team",
                    'owner_id' => $user->id
                ]);

                // Attach as owner
                $team->members()->attach($user->id, ['role' => 'owner']);

                // Set as current team
                $user->update(['current_team_id' => $team->id]);

                // Migrate existing data to this team
                \App\Models\Contact::where('user_id', $user->id)->update(['team_id' => $team->id]);
                \App\Models\Activity::whereHas('contact', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->update(['team_id' => $team->id]);
                \App\Models\OutreachTemplate::where('user_id', $user->id)->update(['team_id' => $team->id]);
            }
        }
    }
}
