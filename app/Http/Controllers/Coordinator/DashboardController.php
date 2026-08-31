<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\StudentProfile;

class DashboardController extends Controller
{
    public function index()
    {
        $coordinator = auth()->user();
        $query = StudentProfile::with('company')
            ->withSum(['ojtLogs as approved_hours' => function ($query) {
                $query->where('status', 'Approved');
            }], 'hours_rendered');

        if ($coordinator->managedCourses()->exists()) {
            $courseNames = $coordinator->managedCourses->pluck('course_name')->toArray();
            $query->whereIn('course', $courseNames);
        }

        $students = $query->get();
        $activeStudentsCount = $students->count();
        $totalHoursTracked = $students->sum('approved_hours');
        $placedStudentsCount = $students->whereNotNull('company_id')->count();
        $placementRate = $activeStudentsCount > 0 ? round(($placedStudentsCount / $activeStudentsCount) * 100) : 0;

        // Fetch pending submissions for managed courses
        $submissionsQuery = \App\Models\RequirementSubmission::where('status', 'Pending');
        if ($coordinator->managedCourses()->exists()) {
            $courseNames = $coordinator->managedCourses->pluck('course_name')->toArray();
            $submissionsQuery->whereHas('user.studentProfile', function ($q) use ($courseNames) {
                $q->whereIn('course', $courseNames);
            });
        }

        $pendingApprovalsCount = (clone $submissionsQuery)->count();
        $pendingSubmissions = $submissionsQuery->with(['requirement', 'user.studentProfile'])
            ->whereHas('requirement')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('coordinator.dashboard', compact(
            'students',
            'activeStudentsCount',
            'pendingApprovalsCount',
            'totalHoursTracked',
            'pendingSubmissions',
            'placementRate'
        ));
    }

    public function students()
    {
        $coordinator = auth()->user();
        $query = StudentProfile::with(['company', 'academicCourse'])
            ->withSum(['ojtLogs as approved_hours' => function ($query) {
                $query->where('status', 'Approved');
            }], 'hours_rendered');

        if ($coordinator->managedCourses()->exists()) {
            $courseNames = $coordinator->managedCourses->pluck('course_name')->toArray();
            $query->whereIn('course', $courseNames);
        }

        $students = $query->get();
        $companies = \App\Models\Company::with('courses')->orderBy('name', 'asc')->get();

        return view('coordinator.students', compact('students', 'companies'));
    }

    public function showStudent($id)
    {
        $coordinator = auth()->user();
        $student = \App\Models\StudentProfile::with(['company', 'user.ojtLogs' => function($q) {
            $q->orderBy('log_date', 'desc');
        }])
        ->withSum(['ojtLogs as approved_hours' => function ($query) {
            $query->where('status', 'Approved');
        }], 'hours_rendered')
        ->findOrFail($id);

        if ($coordinator->managedCourses()->exists()) {
            $courseNames = $coordinator->managedCourses->pluck('course_name')->toArray();
            if (!in_array($student->course, $courseNames)) {
                abort(403, 'Unauthorized action. You do not manage this student\'s department.');
            }
        }

        return view('coordinator.students.show', compact('student'));
    }

    public function reports(Request $request)
    {
        $coordinator = auth()->user();

        // 1. Query managed students with all relations eager loaded
        $query = StudentProfile::with(['company', 'user.requirementSubmissions.requirement', 'academicCourse.requirements', 'evaluations.supervisor'])
            ->withSum(['ojtLogs as approved_hours' => function ($q) {
                $q->where('status', 'Approved');
            }], 'hours_rendered');

        if ($coordinator->managedCourses()->exists()) {
            $courseNames = $coordinator->managedCourses->pluck('course_name')->toArray();
            $query->whereIn('course', $courseNames);
        }

        $allStudents = $query->get();

        // 2. Extract distinct log months
        $studentIds = $allStudents->pluck('user_id')->toArray();
        $months = \App\Models\OjtLog::whereIn('user_id', $studentIds)
            ->selectRaw("DATE_FORMAT(log_date, '%Y-%m') as month_val")
            ->distinct()
            ->orderBy('month_val', 'desc')
            ->pluck('month_val')
            ->toArray();

        if (empty($months)) {
            $months = [now()->format('Y-m')];
        }

        $selectedMonth = $request->get('month', $months[0]);
        $selectedCourse = $request->get('course');

        // 3. Query students for DTR with monthly log sum
        $dtrQuery = StudentProfile::with(['company'])
            ->withSum(['ojtLogs as approved_hours' => function ($q) {
                $q->where('status', 'Approved');
            }], 'hours_rendered')
            ->withSum(['ojtLogs as monthly_hours' => function ($q) use ($selectedMonth) {
                $q->where('status', 'Approved')
                  ->whereRaw("DATE_FORMAT(log_date, '%Y-%m') = ?", [$selectedMonth]);
            }], 'hours_rendered');

        if ($coordinator->managedCourses()->exists()) {
            $courseNames = $coordinator->managedCourses->pluck('course_name')->toArray();
            $dtrQuery->whereIn('course', $courseNames);
        }

        if ($selectedCourse && $selectedCourse !== 'All Courses') {
            $dtrQuery->where('course', $selectedCourse);
        }

        $dtrStudents = $dtrQuery->get();

        // 4. Fetch managed courses for dropdown
        $courses = $coordinator->managedCourses()->exists()
            ? $coordinator->managedCourses
            : \App\Models\Course::all();

        return view('coordinator.reports', compact(
            'allStudents',
            'dtrStudents',
            'months',
            'selectedMonth',
            'selectedCourse',
            'courses'
        ));
    }
}
