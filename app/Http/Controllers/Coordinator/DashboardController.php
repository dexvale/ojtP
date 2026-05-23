<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\StudentProfile;

class DashboardController extends Controller
{
    public function index()
    {
        $students = StudentProfile::with(['user.internships.shiftLogs' => function ($query) {
            $query->where('status', 'Approved');
        }])->get();

        $students->each(function ($student) {
            $hours = 0;
            if ($student->user) {
                foreach ($student->user->internships as $internship) {
                    $hours += $internship->shiftLogs->sum('total_hours');
                }
            }
            $student->approved_hours_count = $hours;
        });

        return view('coordinator.dashboard', compact('students'));
    }

    public function students()
    {
        $students = StudentProfile::with(['user.internships.shiftLogs' => function ($query) {
            $query->where('status', 'Approved');
        }])->get();

        $students->each(function ($student) {
            $hours = 0;
            if ($student->user) {
                foreach ($student->user->internships as $internship) {
                    $hours += $internship->shiftLogs->sum('total_hours');
                }
            }
            $student->approved_hours_count = $hours;
        });

        return view('coordinator.students', compact('students'));
    }
}
