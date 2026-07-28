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

        return view('coordinator.dashboard', compact('students'));
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
}
