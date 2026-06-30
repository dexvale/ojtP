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
            ->with(['academicCourse', 'user' => function($query) {
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
        $companyId = auth()->user()->company_id ?? null;
        $todayDate = \Carbon\Carbon::today()->format('Y-m-d');
        $startOfWeek = \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::MONDAY);
        $endOfWeek = \Carbon\Carbon::now()->endOfWeek(\Carbon\Carbon::SUNDAY);

        // 1. Fetch Today's Live Status
        $todayAttendance = \App\Models\StudentProfile::where('company_id', $companyId)
            ->with(['user', 'ojtLogs' => function($query) use ($todayDate) {
                $query->whereDate('log_date', $todayDate);
            }])->get();

        // 2. Fetch Weekly Logs for Matrix Mapping
        $weeklyLogs = \App\Models\OjtLog::whereIn('user_id', function($query) use ($companyId) {
            $query->select('user_id')->from('student_profiles')->where('company_id', $companyId);
        })
        ->whereBetween('log_date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
        ->get()
        ->groupBy(['user_id', function($item) {
            return \Carbon\Carbon::parse($item->log_date)->format('D'); // Groups by 'Mon', 'Tue', etc.
        }]);

        return view('supervisor.attendance', compact('todayAttendance', 'weeklyLogs', 'startOfWeek'));
    }

    public function approvals()
    {
        $status = ucfirst(strtolower(request('status', 'Pending')));
        $sortBy = request('sort', 'oldest_first');
        
        $user = auth()->user();
        $companyId = $user->company_id ?? $user->supervisorProfile->company_id ?? null;

        if ($companyId) {
            $internUserIds = \App\Models\StudentProfile::where('company_id', $companyId)->pluck('user_id');
            
            $query = \App\Models\OjtLog::whereIn('user_id', $internUserIds)
                ->where('status', $status)
                ->with('user.studentProfile');
        } else {
            // Debugging fall-back: grab all pending logs for local development testing
            $query = \App\Models\OjtLog::where('status', $status)
                ->with('user.studentProfile');
        }

        switch ($sortBy) {
            case 'newest_first':
                $query->orderBy('created_at', 'desc');
                break;
            case 'intern_name':
                $query->join('users', 'ojt_logs.user_id', '=', 'users.id')
                      ->select('ojt_logs.*')
                      ->orderBy('users.name', 'asc');
                break;
            case 'highest_hours':
                $query->orderBy('hours_rendered', 'desc');
                break;
            case 'oldest_first':
            default:
                $query->orderBy('created_at', 'asc');
                break;
        }

        $logs = $query->paginate(10)->withQueryString();

        return view('supervisor.approvals', compact('logs', 'status', 'sortBy'));
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

    public function getCalendarData(\Illuminate\Http\Request $request, $id)
    {
        $student = \App\Models\StudentProfile::with('user')->findOrFail($id);
        
        $month = $request->get('month', \Carbon\Carbon::now()->month);
        $year = $request->get('year', \Carbon\Carbon::now()->year);

        $logs = \App\Models\OjtLog::where('user_id', $student->user_id)
            ->whereMonth('log_date', $month)
            ->whereYear('log_date', $year)
            ->get();

        // Compute Summary Statistics
        $presentCount = $logs->where('status', 'Approved')->count();
        $lateCount = $logs->filter(function($log) {
            return $log->morning_in && \Carbon\Carbon::parse($log->morning_in)->format('H:i') > '09:00';
        })->count();
        $absentCount = 0; // Customize according to your institutional calendar expectations

        // Map daily statuses into an easy-to-read lookup array [ 'YYYY-MM-DD' => 'status_type' ]
        $events = [];
        foreach ($logs as $log) {
            $dateString = \Carbon\Carbon::parse($log->log_date)->format('Y-m-d');
            $events[$dateString] = [
                'status' => $log->status,
                'time_in' => $log->morning_in,
                'time_out' => $log->afternoon_out,
                'is_late' => $log->morning_in && \Carbon\Carbon::parse($log->morning_in)->format('H:i') > '09:00'
            ];
        }

        return response()->json([
            'name' => $student->user->name,
            'course' => $student->course ?? $student->course_major ?? 'N/A',
            'metrics' => [
                'present' => $presentCount,
                'late' => $lateCount,
                'absent' => $absentCount
            ],
            'events' => $events
        ]);
    }
    public function viewLeaderboard()
    {
        $user = auth()->user();
        $companyId = $user->company_id ?? $user->supervisorProfile->company_id ?? null;

        if (!$companyId) {
            return redirect()->back()->with('error', 'You are not currently linked to an active industry partner company.');
        }

        // Aggregate approved hours specifically for students inside this company
        $leaderboard = \App\Models\StudentProfile::with('user')
            ->where('company_id', $companyId)
            ->addSelect(['approved_hours' => \App\Models\OjtLog::selectRaw('COALESCE(SUM(hours_rendered), 0)')
                ->whereColumn('user_id', 'student_profiles.user_id')
                ->where('status', 'Approved')
            ])
            ->orderBy('approved_hours', 'desc')
            ->get(); // Using get() since a single company typically has a manageable group of interns

        return view('supervisor.leaderboard', compact('leaderboard'));
    }
}
