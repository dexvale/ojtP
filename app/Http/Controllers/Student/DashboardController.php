<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $profile = auth()->user()->studentProfile;
        $recentLogs = auth()->user()->ojtLogs()->orderBy('log_date', 'desc')->take(5)->get();

        $requiredHours = $profile->required_hours ?? 400;
        $approvedHours = auth()->user()->ojtLogs()->where('status', 'Approved')->sum('hours_rendered');
        $lackingHours = max(0, $requiredHours - $approvedHours);
        $completionPercentage = $requiredHours > 0 ? round(($approvedHours / $requiredHours) * 100) : 0;

        return view('student.dashboard', compact(
            'requiredHours',
            'approvedHours',
            'lackingHours',
            'completionPercentage',
            'recentLogs'
        ));
    }

    public function profile()
    {
        return view('student.profile');
    }

    public function logs()
    {
        return view('student.logs');
    }
}
