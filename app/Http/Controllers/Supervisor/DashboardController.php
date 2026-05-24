<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\OjtLog;

class DashboardController extends Controller
{
    public function index()
    {
        $pendingLogs = OjtLog::where('status', 'Pending')
            ->whereHas('user.studentProfile', function ($query) {
                $query->where('company_id', auth()->user()->company_id);
            })
            ->with('user.studentProfile')
            ->orderBy('log_date', 'asc')
            ->get();
            
        return view('supervisor.dashboard', compact('pendingLogs'));
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
