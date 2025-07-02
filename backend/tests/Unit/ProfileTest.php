<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_create_a_profile() {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/profiles', [
            'name' => 'Admin',
            'description' => 'Administrator profile',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('profiles', [
            'name' => 'Admin',
            'description' => 'Administrator profile',
        ]);
    }
}
