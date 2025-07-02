<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_profile_return_true()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['name' => 'Admin']);

        $user->profiles()->attach($profile->id);

        $result = $user->hasProfile('Admin');

        $this->assertTrue($result);
    }

    public function test_user_has_profile_return_false()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['name' => 'User']);

        $user->profiles()->attach($profile->id);

        $result = $user->hasProfile('Admin');

        $this->assertFalse($result);
    }

}
