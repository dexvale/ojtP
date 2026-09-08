<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoordinatorDashboardMobileTest extends TestCase
{
    use RefreshDatabase;

    public function test_coordinator_dashboard_renders_mobile_logout_and_sidebar(): void
    {
        $coordinator = User::create([
            'name' => 'Dr. Elena Vance',
            'email' => 'coordinator@bisu.edu.ph',
            'password' => bcrypt('password123'),
            'role' => 'Coordinator',
        ]);

        $response = $this->actingAs($coordinator)->get(route('coordinator.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('sidebar-toggle');
        $response->assertSee('sidebar-overlay');
        $response->assertSee('Logout');
        $response->assertSee('user-menu-btn');
        $response->assertSee('user-menu-dropdown');
    }
}
