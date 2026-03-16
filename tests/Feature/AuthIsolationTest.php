<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_is_redirected_to_login()
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_dashboard()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);
    }

    public function test_user_cannot_see_other_users_contacts()
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        // UserB has a contact
        Contact::create(['user_id' => $userB->id, 'name' => 'UserB Private Lead', 'status' => 'new']);

        // UserA fetches the contacts index
        $response = $this->actingAs($userA)->get('/contacts');
        $response->assertStatus(200);
        $response->assertDontSee('UserB Private Lead');
    }
}
