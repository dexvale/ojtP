<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Company;
use App\Models\StudentProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AllRolesMobileLogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_see_logout_on_dashboard_and_profile(): void
    {
        $studentUser = User::create([
            'name' => 'Student Test',
            'email' => 'student_test@bisu.edu.ph',
            'password' => bcrypt('password123'),
            'role' => 'Student',
        ]);

        $response = $this->actingAs($studentUser)->get(route('student.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('sidebar-toggle');
        $response->assertSee('Logout');
        $response->assertSee('user-menu-btn');
        $response->assertSee('user-menu-dropdown');

        $profileResponse = $this->actingAs($studentUser)->get(route('student.profile'));
        $profileResponse->assertStatus(200);
        $profileResponse->assertSee('Log Out of Account');
    }

    public function test_supervisor_can_see_logout_on_dashboard(): void
    {
        $company = Company::create([
            'name' => 'Test Company',
            'industry' => 'Tech',
            'location' => 'Bohol',
        ]);

        $supervisor = User::create([
            'name' => 'Supervisor Test',
            'email' => 'supervisor_test@company.com',
            'password' => bcrypt('password123'),
            'role' => 'Advisor',
            'company_id' => $company->id,
        ]);

        $response = $this->actingAs($supervisor)->get(route('supervisor.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('sidebar-toggle');
        $response->assertSee('Logout');
        $response->assertSee('user-menu-btn');
        $response->assertSee('user-menu-dropdown');
    }

    public function test_superadmin_can_see_logout_on_academic_terms(): void
    {
        $admin = User::create([
            'name' => 'Super Admin Test',
            'email' => 'admin_test@bisu.edu.ph',
            'password' => bcrypt('password123'),
            'role' => 'Admin',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.academic_terms.index'));
        $response->assertStatus(200);
        $response->assertSee('sidebar-toggle');
        $response->assertSee('Logout');
        $response->assertSee('user-menu-btn');
        $response->assertSee('user-menu-dropdown');
    }

    public function test_supervisor_leaderboard_has_logout(): void
    {
        $company = Company::create([
            'name' => 'Leaderboard Co',
            'industry' => 'Tech',
            'location' => 'Bohol',
        ]);

        $supervisor = User::create([
            'name' => 'Supervisor Leader',
            'email' => 'supervisor_lead@company.com',
            'password' => bcrypt('password123'),
            'role' => 'Advisor',
            'company_id' => $company->id,
        ]);

        $response = $this->actingAs($supervisor)->get(route('supervisor.leaderboard'));
        $response->assertStatus(200);
        $response->assertSee('user-menu-btn');
        $response->assertSee('user-menu-dropdown');
        $response->assertSee('Logout');
    }
}
