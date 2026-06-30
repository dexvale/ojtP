<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\StudentProfile;

class DashboardController extends Controller
{
    public function index()
    {
        $students = StudentProfile::with('company')
            ->withSum(['ojtLogs as approved_hours' => function ($query) {
                $query->where('status', 'Approved');
            }], 'hours_rendered')
            ->get();

        return view('coordinator.dashboard', compact('students'));
    }

    public function students()
    {
        $students = StudentProfile::with(['company', 'academicCourse'])
            ->withSum(['ojtLogs as approved_hours' => function ($query) {
                $query->where('status', 'Approved');
            }], 'hours_rendered')
            ->get();
        $companies = \App\Models\Company::orderBy('name', 'asc')->get();

        return view('coordinator.students', compact('students', 'companies'));
    }

    public function showStudent($id)
    {
        $student = \App\Models\StudentProfile::with(['company', 'user.ojtLogs' => function($q) {
            $q->orderBy('log_date', 'desc');
        }])
        ->withSum(['ojtLogs as approved_hours' => function ($query) {
            $query->where('status', 'Approved');
        }], 'hours_rendered')
        ->findOrFail($id);

        return view('coordinator.students.show', compact('student'));
    }
}
