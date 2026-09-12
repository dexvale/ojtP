<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\OjtLog;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupervisorApprovalsTest extends TestCase
{
    use RefreshDatabase;

    protected $company;
    protected $supervisor;
    protected $student;
    protected $studentProfile;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = Company::create([
            'name' => 'Tech Corp',
            'industry' => 'Software',
            'location' => 'Bohol',
        ]);

        $this->supervisor = User::create([
            'name' => 'Alice Margate',
            'email' => 'supervisor@techcorp.com',
            'password' => bcrypt('password123'),
            'role' => 'Advisor',
            'company_id' => $this->company->id,
        ]);

        $this->student = User::create([
            'name' => 'Jane Doe',
            'email' => 'jane@student.com',
            'password' => bcrypt('password123'),
            'role' => 'Student',
        ]);

        $this->studentProfile = StudentProfile::create([
            'user_id' => $this->student->id,
            'student_id_number' => '2026-0001',
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'course' => 'BSIT',
            'company_id' => $this->company->id,
            'supervisor_id' => $this->supervisor->id,
            'required_hours' => 300,
        ]);
    }

    public function test_supervisor_can_access_approvals_data_table(): void
    {
        // Create 2 pending logs
        OjtLog::create([
            'user_id' => $this->student->id,
            'log_date' => now()->subDay()->toDateString(),
            'hours_rendered' => 8,
            'tasks_performed' => 'Built the authentication UI module',
            'status' => 'Pending',
        ]);

        OjtLog::create([
            'user_id' => $this->student->id,
            'log_date' => now()->toDateString(),
            'hours_rendered' => 4,
            'tasks_performed' => 'Attended morning standup and code review',
            'status' => 'Pending',
        ]);

        $response = $this->actingAs($this->supervisor)->get(route('supervisor.approvals'));

        $response->assertStatus(200);
        $response->assertSee('Pending Approvals');
        $response->assertSee('Pending Queue');
        $response->assertSee('Approved Logs');
        $response->assertSee('Rejected / Revisions');
        $response->assertSee('Approve Selected');
        $response->assertSee('Built the authentication UI module');
        $response->assertSee('Jane Doe');
        $response->assertSee('select-all-checkbox');
    }

    public function test_supervisor_can_approve_single_log(): void
    {
        $log = OjtLog::create([
            'user_id' => $this->student->id,
            'log_date' => now()->toDateString(),
            'hours_rendered' => 8,
            'tasks_performed' => 'Completed data ingestion pipeline',
            'status' => 'Pending',
        ]);

        $response = $this->actingAs($this->supervisor)
            ->post(route('supervisor.logs.approve', $log), [
                'remarks' => 'Great work on this task!',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('ojt_logs', [
            'id' => $log->id,
            'status' => 'Approved',
            'remarks' => 'Great work on this task!',
        ]);
    }

    public function test_supervisor_can_reject_log_with_feedback(): void
    {
        $log = OjtLog::create([
            'user_id' => $this->student->id,
            'log_date' => now()->toDateString(),
            'hours_rendered' => 8,
            'tasks_performed' => 'Worked on stuff',
            'status' => 'Pending',
        ]);

        $response = $this->actingAs($this->supervisor)
            ->post(route('supervisor.logs.reject', $log), [
                'remarks' => 'Please provide more specific details of tasks performed.',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('ojt_logs', [
            'id' => $log->id,
            'status' => 'Rejected',
            'remarks' => 'Please provide more specific details of tasks performed.',
        ]);
    }

    public function test_supervisor_can_batch_approve_multiple_logs(): void
    {
        $log1 = OjtLog::create([
            'user_id' => $this->student->id,
            'log_date' => now()->subDays(2)->toDateString(),
            'hours_rendered' => 8,
            'tasks_performed' => 'Refactored CSS stylesheet',
            'status' => 'Pending',
        ]);

        $log2 = OjtLog::create([
            'user_id' => $this->student->id,
            'log_date' => now()->subDays(1)->toDateString(),
            'hours_rendered' => 7.5,
            'tasks_performed' => 'Fixed responsiveness bugs',
            'status' => 'Pending',
        ]);

        $response = $this->actingAs($this->supervisor)
            ->post(route('supervisor.logs.batchApprove'), [
                'log_ids' => [$log1->id, $log2->id],
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('ojt_logs', [
            'id' => $log1->id,
            'status' => 'Approved',
        ]);
        $this->assertDatabaseHas('ojt_logs', [
            'id' => $log2->id,
            'status' => 'Approved',
        ]);
    }

    public function test_supervisor_can_filter_logs_by_intern(): void
    {
        $otherStudent = User::create([
            'name' => 'Mark Spencer',
            'email' => 'mark@student.com',
            'password' => bcrypt('password123'),
            'role' => 'Student',
        ]);

        StudentProfile::create([
            'user_id' => $otherStudent->id,
            'student_id_number' => '2026-0002',
            'first_name' => 'Mark',
            'last_name' => 'Spencer',
            'course' => 'BSCS',
            'company_id' => $this->company->id,
            'supervisor_id' => $this->supervisor->id,
            'required_hours' => 300,
        ]);

        OjtLog::create([
            'user_id' => $this->student->id,
            'log_date' => now()->toDateString(),
            'hours_rendered' => 8,
            'tasks_performed' => 'Jane Unique Log Item 12345',
            'status' => 'Pending',
        ]);

        OjtLog::create([
            'user_id' => $otherStudent->id,
            'log_date' => now()->toDateString(),
            'hours_rendered' => 6,
            'tasks_performed' => 'Mark Unique Log Item 67890',
            'status' => 'Pending',
        ]);

        // Filter for Jane only
        $response = $this->actingAs($this->supervisor)->get(route('supervisor.approvals', [
            'intern_id' => $this->student->id,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Jane Unique Log Item 12345');
        $response->assertDontSee('Mark Unique Log Item 67890');
    }
}
