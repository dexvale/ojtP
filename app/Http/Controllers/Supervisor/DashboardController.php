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
            
        $startOfWeek = \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::MONDAY);
        
        $attendanceCounts = [];
        for ($i = 0; $i < 5; $i++) {
            $date = $startOfWeek->copy()->addDays($i)->format('Y-m-d');
            $attendanceCounts[] = OjtLog::whereIn('user_id', $internUserIds)
                ->whereDate('log_date', $date)
                ->whereNotNull('morning_in')
                ->count();
        }
            
        return view('supervisor.dashboard', compact('totalInterns', 'clockedInCount', 'pendingHours', 'topPerformers', 'pendingLogs', 'attendanceCounts'));
    }

    public function attendance()
    {
        $companyId = auth()->user()->supervisorProfile->company_id ?? null;
        $students = \App\Models\StudentProfile::where('company_id', $companyId)->with('user')->get();
        $studentIds = $students->pluck('user_id');

        // 1. Today's Live Status Data
        $todayLogs = \App\Models\OjtLog::whereIn('user_id', $studentIds)
            ->whereDate('log_date', \Carbon\Carbon::today())
            ->get()
            ->keyBy('user_id');

        // 2. Build Weekly Attendance Matrix Tracker (Mon - Fri)
        $startOfWeek = \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::MONDAY);
        $endOfWeek = \Carbon\Carbon::now()->endOfWeek(\Carbon\Carbon::FRIDAY);
        
        $weeklyLogs = \App\Models\OjtLog::whereIn('user_id', $studentIds)
            ->whereBetween('log_date', [$startOfWeek, $endOfWeek])
            ->get()
            ->groupBy('user_id');

        return view('supervisor.attendance', compact('students', 'todayLogs', 'weeklyLogs', 'startOfWeek'));
    }

    public function approvals()
    {
        $status = ucfirst(strtolower(request('status', 'Pending')));
        
        $user = auth()->user();
        $companyId = $user->company_id ?? $user->supervisorProfile->company_id ?? null;

        if ($companyId) {
            $internUserIds = \App\Models\StudentProfile::where('company_id', $companyId)->pluck('user_id');
            
            $logs = \App\Models\OjtLog::whereIn('user_id', $internUserIds)
                ->where('status', $status)
                ->with('user.studentProfile')
                ->latest()
                ->paginate(10);
        } else {
            // Debugging fall-back: grab all pending logs for local development testing
            $logs = \App\Models\OjtLog::where('status', $status)
                ->with('user.studentProfile')
                ->latest()
                ->paginate(10);
        }

        return view('supervisor.approvals', compact('logs', 'status'));
    }

    public function approve(\Illuminate\Http\Request $request, \App\Models\OjtLog $log)
    {
        $request->validate([
            'remarks' => 'nullable|string|max:50000'
        ]);

        $log->status = 'Approved';
        // Note: Only assign approved_at if the column exists in your migration
        // $log->approved_at = now(); 
        
        // Capture optional remark text if provided by supervisor
        if ($request->has('remarks') && !empty($request->remarks)) {
            $log->remarks = $request->remarks;
        } else {
            $log->remarks = null; // Clear previous rejection text if any
        }
        
        $log->save();

        return redirect()->back()->with('success', 'Intern log entry verified and hours officially approved!');
    }

    public function reject(\Illuminate\Http\Request $request, \App\Models\OjtLog $log)
    {
        $request->validate([
            'remarks' => 'required|string|max:50000'
        ]);

        // Shift to revision queue
        $log->status = 'Rejected';
        $log->remarks = $request->remarks;
        $log->save();

        return redirect()->back()->with('error', 'Log entry sent back to intern for mandatory revision.');
    }
}
