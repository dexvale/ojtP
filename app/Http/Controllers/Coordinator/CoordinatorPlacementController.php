<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\StudentProfile;
use App\Models\Company;
use App\Models\Course;
use App\Models\User;

class CoordinatorPlacementController extends Controller
{
    public function index()
    {
        $coordinator = auth()->user();
        $query = StudentProfile::with(['user', 'company', 'supervisor', 'academicCourse']);

        if ($coordinator->role === 'Coordinator' && $coordinator->managedCourses()->exists()) {
            $managedCourseNames = $coordinator->managedCourses->pluck('course_name')->toArray();
            $query->whereIn('course', $managedCourseNames);
        }

        $pendingPlacements = (clone $query)->where('placement_status', 'Pending')->orderBy('updated_at', 'desc')->get();
        $approvedPlacements = (clone $query)->where('placement_status', 'Approved')->orderBy('updated_at', 'desc')->paginate(15);
        $rejectedPlacements = (clone $query)->where('placement_status', 'Rejected')->orderBy('updated_at', 'desc')->get();

        return view('coordinator.placements', compact('pendingPlacements', 'approvedPlacements', 'rejectedPlacements'));
    }

    public function approve(Request $request, StudentProfile $student)
    {
        $coordinator = auth()->user();
        if ($coordinator->role === 'Coordinator' && $coordinator->managedCourses()->exists()) {
            $managedCourseNames = $coordinator->managedCourses->pluck('course_name')->toArray();
            if (!in_array($student->course, $managedCourseNames)) {
                abort(403, 'Unauthorized. You do not manage this student\'s department.');
            }
        }

        // 1. Resolve or Create Company
        if ($student->pending_company_name) {
            $company = Company::firstOrCreate(
                ['name' => $student->pending_company_name],
                [
                    'industry'              => 'General Operations',
                    'location'              => 'Bohol',
                    'status'                => 'approved',
                    'created_by_student_id' => $student->user_id,
                ]
            );
            $companyId = $company->id;
        } else {
            $companyId = $student->company_id;
            $company = Company::find($companyId);
        }

        // Attach course to company if not already linked
        if ($company && $student->academicCourse) {
            if (!$company->courses()->where('courses.id', $student->academicCourse->id)->exists()) {
                $company->courses()->attach($student->academicCourse->id);
            }
        }

        // 2. Resolve or Provision Supervisor (Smart Deduplication)
        $supervisor = null;
        $flashPassword = null;

        if ($student->supervisor_id) {
            $supervisor = User::find($student->supervisor_id);
        } elseif ($student->pending_supervisor_email) {
            $existingSupervisor = User::where('email', $student->pending_supervisor_email)->first();
            
            if ($existingSupervisor) {
                $supervisor = $existingSupervisor;
                // Ensure company_id, department, and contact info are assigned
                $updates = [];
                if (!$supervisor->company_id && $companyId) {
                    $updates['company_id'] = $companyId;
                }
                if (!$supervisor->department && $student->department) {
                    $updates['department'] = $student->department;
                }
                if (!$supervisor->name && $student->pending_supervisor_name) {
                    $updates['name'] = $student->pending_supervisor_name;
                }
                if (!$supervisor->contact_number && $student->pending_supervisor_contact) {
                    $updates['contact_number'] = $student->pending_supervisor_contact;
                }
                if (!empty($updates)) {
                    $supervisor->update($updates);
                }
            } else {
                $plainPassword = 'super' . rand(1000, 9999);
                $supervisor = User::create([
                    'name'           => $student->pending_supervisor_name,
                    'email'          => $student->pending_supervisor_email,
                    'contact_number' => $student->pending_supervisor_contact,
                    'password'       => Hash::make($plainPassword),
                    'role'           => 'Advisor',
                    'company_id'     => $companyId,
                    'department'     => $student->department,
                ]);

                $flashPassword = $plainPassword;

                // Send email with credentials to supervisor
                try {
                    $supervisor->notify(new \App\Notifications\WelcomeAdvisorNotification($plainPassword));
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning("Could not dispatch welcome email to supervisor {$supervisor->email}: " . $e->getMessage());
                }
            }
        }

        // 3. Update Student Profile to Approved
        $student->update([
            'company_id'           => $companyId,
            'supervisor_id'        => $supervisor ? $supervisor->id : null,
            'placement_status'     => 'Approved',
            'placement_remarks'    => null,
            'pending_company_name' => null,
        ]);

        $msg = "Placement for {$student->first_name} {$student->last_name} has been approved and endorsed.";
        if ($flashPassword) {
            return redirect()->back()->with([
                'success'        => $msg,
                'flash_email'    => $supervisor->email,
                'flash_password' => $flashPassword,
                'flash_company'  => $company ? $company->name : 'Assigned Company',
            ]);
        }

        return redirect()->back()->with('success', $msg);
    }

    public function reject(Request $request, StudentProfile $student)
    {
        $coordinator = auth()->user();
        if ($coordinator->role === 'Coordinator' && $coordinator->managedCourses()->exists()) {
            $managedCourseNames = $coordinator->managedCourses->pluck('course_name')->toArray();
            if (!in_array($student->course, $managedCourseNames)) {
                abort(403, 'Unauthorized.');
            }
        }

        $request->validate([
            'remarks' => 'required|string|max:1000',
        ]);

        $student->update([
            'placement_status'  => 'Rejected',
            'placement_remarks' => $request->remarks,
        ]);

        return redirect()->back()->with('success', "Placement application for {$student->first_name} {$student->last_name} returned for revision.");
    }
}
