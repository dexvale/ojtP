<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $profile = auth()->user()->studentProfile;
        $recentLogs = auth()->user()->ojtLogs()->orderBy('log_date', 'desc')->take(5)->get();

        $requiredHours = $profile->academicCourse->required_hours ?? 400;
        $approvedHours = auth()->user()->ojtLogs()->where('status', 'Approved')->sum('hours_rendered');
        $lackingHours = max(0, $requiredHours - $approvedHours);
        $completionPercentage = $requiredHours > 0 ? round(($approvedHours / $requiredHours) * 100) : 0;

        // Calculate dynamic completion pace
        $approvedLogs = auth()->user()->ojtLogs()->where('status', 'Approved')->orderBy('log_date', 'asc')->get();
        $paceMessage = "Start logging approved shifts to calculate your completion pace.";
        
        if ($lackingHours <= 0) {
            $paceMessage = "Congratulations! You have completed all required OJT hours.";
        } elseif ($approvedLogs->count() > 0) {
            $firstDate = \Carbon\Carbon::parse($approvedLogs->first()->log_date);
            $lastDate = \Carbon\Carbon::parse($approvedLogs->last()->log_date);
            
            // Days difference, minimum of 1 day to avoid divide-by-zero
            $daysDiff = max(1, $firstDate->diffInDays($lastDate));
            // Calculate weeks, minimum of 1 week
            $weeksPassed = max(1, ceil($daysDiff / 7));
            $averageWeeklyHours = $approvedHours / $weeksPassed;
            
            if ($averageWeeklyHours > 0) {
                $weeksRemaining = ceil($lackingHours / $averageWeeklyHours);
                $weeksText = $weeksRemaining == 1 ? 'week' : 'weeks';
                $paceMessage = "You are on track to finish in {$weeksRemaining} {$weeksText} at your current pace (" . number_format($averageWeeklyHours, 1) . " hrs/week).";
            }
        }

        $requirements = collect();
        if ($profile) {
            $requirements = \App\Models\Requirement::whereHas('courses', function ($query) use ($profile) {
                $query->where('course_name', $profile->course);
            })->orderBy('created_at', 'desc')->take(3)->get();
        }

        $studentSubmissions = \App\Models\RequirementSubmission::where('user_id', auth()->id())
            ->get()
            ->keyBy('requirement_id');

        return view('student.dashboard', compact(
            'requiredHours',
            'approvedHours',
            'lackingHours',
            'completionPercentage',
            'recentLogs',
            'paceMessage',
            'requirements',
            'studentSubmissions'
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
