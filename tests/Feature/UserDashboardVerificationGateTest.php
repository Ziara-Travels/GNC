<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserDashboardVerificationGateTest extends TestCase
{
    use RefreshDatabase;

    public function test_verified_user_can_reach_the_dashboard()
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->get('/user')->assertOk();
    }

    public function test_unverified_user_is_redirected_to_the_verification_notice()
    {
        $user = User::factory()->unverified()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get('/user')
            ->assertRedirect(route('verification.notice'));
    }

    public function test_unverified_user_is_redirected_from_a_nested_dashboard_page()
    {
        $user = User::factory()->unverified()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get('/user/bookings/my')
            ->assertRedirect(route('verification.notice'));
    }

    /**
     * The guest-booking continuation must remain reachable while unverified:
     * after confirming their email the user is sent to the passenger step.
     */
    public function test_verification_notice_is_not_itself_gated()
    {
        $user = User::factory()->unverified()->create(['role' => 'user']);

        $this->actingAs($user)->get('/verify-email')->assertOk();
    }
}