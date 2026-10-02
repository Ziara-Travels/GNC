<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserDashboardRenderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Regression: the dashboard view filtered $topDestinations->where('country', ...)
     * but getUserTopDestinations() returns objects keyed 'destination_name', and the
     * fallback path passed a plain array. Either case threw
     * "Call to a member function where() on array" and returned HTTP 500.
     */
    public function test_user_dashboard_renders_for_a_user_without_bookings()
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->get('/user')->assertOk();
    }
}