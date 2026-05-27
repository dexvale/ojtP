<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\OjtLog;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $companyId = $user->company_id ?? $user->supervisorProfile->company_id ?? null;

        if (!$companyId) {
            return view('supervisor.dashboard', [
                'totalInterns' => 0, 'clockedInCount' => 0, 'pendingHours' => 0, 'topPerformers' => collect(), 'pendingLogs' => collect()
            ]);
        }

        // 1. Get all user IDs of students assigned to this company
        $internUserIds = \App\Models\StudentProfile::where('company_id', $companyId)->pluck('user_id');

        // 2. Metrics Calculations
        $totalInterns = $internUserIds->count();

        $clockedInCount = OjtLog::whereIn('user_id', $internUserIds)
            ->whereDate('log_date', \Carbon\Carbon::today())
            ->whereNotNull('morning_in')
            ->whereNull('afternoon_out')
            ->count();

        $pendingHours = OjtLog::whereIn('user_id', $internUserIds)
            ->where('status', 'Pending')
            ->sum('hours_rendered');

        // 3. Top Performers (Sum of approved hours per student, taking top 3)
        $topPerformers = \App\Models\StudentProfile::where('company_id', $companyId)
            ->with(['user' => function($query) {
                $query->withSum(['ojtLogs' => function($q) {
                    $q->where('status', 'Approved');
                }], 'hours_rendered');
            }])
            ->get()
            ->sortByDesc('user.ojt_logs_sum_hours_rendered')
            ->take(3);

        $pendingLogs = OjtLog::where('status', 'Pending')
            ->whereIn('user_id', $internUserIds)
            ->with('user.studentProfile')
            ->orderBy('log_date', 'asc')
            ->get();
            
        return view('supervisor.dashboard', compact('totalInterns', 'clockedInCount', 'pendingHours', 'topPerformers', 'pendingLogs'));
    }

    public function attendance()
    {
        return view('supervisor.attendance');
    }

    public function approvals()
    {
        $pendingLogs = OjtLog::where('status', 'Pending')
            ->whereHas('user.studentProfile', function ($query) {
                $query->where('company_id', auth()->user()->company_id);
            })
            ->with('user.studentProfile')
            ->orderBy('log_date', 'asc')
            ->get();
            
        return view('supervisor.approvals', compact('pendingLogs'));
    }

    public function approve(OjtLog $log)
    {
        $log->update(['status' => 'Approved']);
        return redirect()->back()->with('success', 'Intern log entry verified and approved successfully!');
    }

    public function reject(OjtLog $log)
    {
        $log->update(['status' => 'Rejected']);
        return redirect()->back()->with('success', 'Intern log entry rejected and sent back for revision.');
    }
}
