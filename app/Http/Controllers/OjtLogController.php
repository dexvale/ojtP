<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OjtLogController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'morning_in' => 'nullable|date_format:H:i',
            'morning_out' => 'nullable|date_format:H:i',
            'afternoon_in' => 'nullable|date_format:H:i',
            'afternoon_out' => 'nullable|date_format:H:i',
            'activity_summary' => 'required|string',
            'has_overtime' => 'nullable|in:on,1,true',
            // Optional: workspace_photo upload logic here
        ]);

        // Calculate hours logic
        $morningMinutes = 0;
        $afternoonMinutes = 0;

        if ($request->morning_in && $request->morning_out) {
            $mIn = \Carbon\Carbon::createFromFormat('H:i', $request->morning_in);
            $mOut = \Carbon\Carbon::createFromFormat('H:i', $request->morning_out);
            if ($mOut->greaterThan($mIn)) {
                $morningMinutes = $mOut->diffInMinutes($mIn);
            }
        }

        if ($request->afternoon_in && $request->afternoon_out) {
            $aIn = \Carbon\Carbon::createFromFormat('H:i', $request->afternoon_in);
            $aOut = \Carbon\Carbon::createFromFormat('H:i', $request->afternoon_out);
            if ($aOut->greaterThan($aIn)) {
                $afternoonMinutes = $aOut->diffInMinutes($aIn);
            }
        }

        $totalHours = ($morningMinutes + $afternoonMinutes) / 60;

        auth()->user()->ojtLogs()->create([
            'log_date' => now()->toDateString(),
            'morning_in' => $request->morning_in,
            'morning_out' => $request->morning_out,
            'afternoon_in' => $request->afternoon_in,
            'afternoon_out' => $request->afternoon_out,
            'tasks_performed' => $request->activity_summary,
            'hours_rendered' => $totalHours,
            'status' => 'Pending',
            'has_overtime' => $request->has('has_overtime'),
        ]);

        return redirect()->back()->with('success', 'Shift logged successfully!');
    }
}
