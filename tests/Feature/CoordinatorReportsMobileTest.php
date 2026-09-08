<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoordinatorReportsMobileTest extends TestCase
{
    use RefreshDatabase;
    public function test_coordinator_can_view_responsive_reports_page(): void
    {
        $coordinator = User::create([
            'name' => 'Test Coordinator',
            'email' => 'test_coord@bisu.edu.ph',
            'password' => bcrypt('secret123'),
            'role' => 'Coordinator',
        ]);

        $response = $this->actingAs($coordinator)->get(route('coordinator.reports'));

        $response->assertStatus(200);
        $response->assertSee('sidebar-toggle');
        $response->assertSee('sidebar-overlay');
        $response->assertSee('max-w-full');
        $response->assertSee('overflow-x-auto');
    }
}
