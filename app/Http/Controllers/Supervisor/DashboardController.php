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
                'totalInterns' => 0, 'clockedInCount' => 0, 'pendingHours' => 0, 'topPerformers' => collect(), 'pendingLogs' => collect(), 'attendanceCounts' => [0, 0, 0, 0, 0]
            ]);
        }

        // 1. Get student profiles query for this specific supervisor
        $studentQuery = \App\Models\StudentProfile::where('company_id', $companyId);
        if (\App\Models\StudentProfile::where('supervisor_id', $user->id)->exists()) {
            $studentQuery->where('supervisor_id', $user->id);
        }

        $internUserIds = (clone $studentQuery)->pluck('user_id');

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
        $topPerformers = (clone $studentQuery)
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
        $user = auth()->user();
        $companyId = $user->company_id ?? null;
        $todayDate = \Carbon\Carbon::today()->format('Y-m-d');
        $startOfWeek = \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::MONDAY);
        $endOfWeek = \Carbon\Carbon::now()->endOfWeek(\Carbon\Carbon::SUNDAY);

        // 1. Get student profiles query for this specific supervisor
        $studentQuery = \App\Models\StudentProfile::where('company_id', $companyId);
        if (\App\Models\StudentProfile::where('supervisor_id', $user->id)->exists()) {
            $studentQuery->where('supervisor_id', $user->id);
        }

        $internUserIds = (clone $studentQuery)->pluck('user_id');

        // 2. Fetch Today's Live Status
        $todayAttendance = (clone $studentQuery)
            ->with(['user', 'ojtLogs' => function($query) use ($todayDate) {
                $query->whereDate('log_date', $todayDate);
            }])->get();

        // 3. Fetch Weekly Logs for Matrix Mapping
        $weeklyLogs = \App\Models\OjtLog::whereIn('user_id', $internUserIds)
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
        $internId = request('intern_id');
        
        $user = auth()->user();
        $companyId = $user->company_id ?? $user->supervisorProfile->company_id ?? null;

        if ($companyId) {
            $studentQuery = \App\Models\StudentProfile::where('company_id', $companyId);
            if (\App\Models\StudentProfile::where('supervisor_id', $user->id)->exists()) {
                $studentQuery->where('supervisor_id', $user->id);
            }

            $internUserIds = (clone $studentQuery)->pluck('user_id');
            $baseQuery = \App\Models\OjtLog::whereIn('user_id', $internUserIds);
            $interns = $studentQuery->with('user')->get();
        } else {
            // Fallback for development without company assigned
            $baseQuery = \App\Models\OjtLog::query();
            $interns = \App\Models\StudentProfile::with('user')->get();
        }

        // Live status counts for tab badges
        $pendingCount = (clone $baseQuery)->where('status', 'Pending')->count();
        $approvedCount = (clone $baseQuery)->where('status', 'Approved')->count();
        $rejectedCount = (clone $baseQuery)->where('status', 'Rejected')->count();

        $query = (clone $baseQuery)
            ->where('status', $status)
            ->with('user.studentProfile');

        if ($internId) {
            $query->where('user_id', $internId);
        }

        switch ($sortBy) {
            case 'newest_first':
                $query->orderBy('log_date', 'desc')->orderBy('created_at', 'desc');
                break;
            case 'intern_name':
                $query->join('users', 'ojt_logs.user_id', '=', 'users.id')
                      ->select('ojt_logs.*')
                      ->orderBy('users.name', 'asc')
                      ->orderBy('ojt_logs.log_date', 'desc');
                break;
            case 'highest_hours':
                $query->orderBy('hours_rendered', 'desc');
                break;
            case 'oldest_first':
            default:
                $query->orderBy('log_date', 'asc')->orderBy('created_at', 'asc');
                break;
        }

        $logs = $query->paginate(15)->withQueryString();

        return view('supervisor.approvals', compact(
            'logs', 
            'status', 
            'sortBy', 
            'internId', 
            'interns', 
            'pendingCount', 
            'approvedCount', 
            'rejectedCount'
        ));
    }

    public function batchApprove(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();
        $companyId = $user->company_id ?? $user->supervisorProfile->company_id ?? null;

        // Build the base intern scope for this supervisor
        $internUserIds = null;
        if ($companyId) {
            $studentQuery = \App\Models\StudentProfile::where('company_id', $companyId);
            if (\App\Models\StudentProfile::where('supervisor_id', $user->id)->exists()) {
                $studentQuery->where('supervisor_id', $user->id);
            }
            $internUserIds = $studentQuery->pluck('user_id');
        }

        // ── Select All Results mode: approve every pending log for this supervisor ──
        if ($request->boolean('select_all')) {
            $query = \App\Models\OjtLog::where('status', 'Pending');
            if ($internUserIds) {
                $query->whereIn('user_id', $internUserIds);
            }
            // Respect any active intern filter
            if ($request->filled('intern_id')) {
                $query->where('user_id', $request->intern_id);
            }
            $updatedCount = $query->update([
                'status'     => 'Approved',
                'remarks'    => null,
                'updated_at' => now(),
            ]);
            return redirect()->route('supervisor.approvals', ['status' => 'Pending'])
                ->with('success', "Successfully approved all {$updatedCount} pending intern daily log(s)!");
        }

        // ── Normal mode: approve by explicit IDs ──
        $request->validate([
            'log_ids'   => 'required|array|min:1',
            'log_ids.*' => 'exists:ojt_logs,id'
        ]);

        $query = \App\Models\OjtLog::whereIn('id', $request->log_ids)->where('status', 'Pending');
        if ($internUserIds) {
            $query->whereIn('user_id', $internUserIds);
        }

        $updatedCount = $query->update([
            'status'     => 'Approved',
            'remarks'    => null,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', "Successfully approved {$updatedCount} intern daily log(s)!");
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

        $studentQuery = \App\Models\StudentProfile::with('user')->where('company_id', $companyId);
        if (\App\Models\StudentProfile::where('supervisor_id', $user->id)->exists()) {
            $studentQuery->where('supervisor_id', $user->id);
        }

        // Aggregate approved hours specifically for students assigned to this supervisor
        $leaderboard = $studentQuery
            ->addSelect(['approved_hours' => \App\Models\OjtLog::selectRaw('COALESCE(SUM(hours_rendered), 0)')
                ->whereColumn('user_id', 'student_profiles.user_id')
                ->where('status', 'Approved')
            ])
            ->orderBy('approved_hours', 'desc')
            ->get();

        return view('supervisor.leaderboard', compact('leaderboard'));
    }
}
