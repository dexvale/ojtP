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
            'am_clock_in' => 'nullable|date_format:H:i',
            'am_clock_out' => 'nullable|date_format:H:i',
            'pm_clock_in' => 'nullable|date_format:H:i',
            'pm_clock_out' => 'nullable|date_format:H:i',
            'activity_summary' => 'required|string',
            'has_overtime' => 'nullable|in:on,1,true',
            'ot_clock_in' => 'nullable|required_if:has_overtime,on|date_format:H:i|after:pm_clock_out',
            'ot_clock_out' => 'nullable|required_with:ot_clock_in|date_format:H:i|after:ot_clock_in',
            // Optional: workspace_photo upload logic here
        ]);

        // Calculate hours logic
        $morningMinutes = 0;
        $afternoonMinutes = 0;
        $overtimeMinutes = 0;

        // Support both old naming and new naming from form
        $morningIn = $request->am_clock_in ?? $request->morning_in;
        $morningOut = $request->am_clock_out ?? $request->morning_out;
        $afternoonIn = $request->pm_clock_in ?? $request->afternoon_in;
        $afternoonOut = $request->pm_clock_out ?? $request->afternoon_out;

        if ($morningIn && $morningOut) {
            $mIn = \Carbon\Carbon::createFromFormat('H:i', $morningIn);
            $mOut = \Carbon\Carbon::createFromFormat('H:i', $morningOut);
            $morningMinutes = abs($mOut->diffInMinutes($mIn, false));
        }

        if ($afternoonIn && $afternoonOut) {
            $aIn = \Carbon\Carbon::createFromFormat('H:i', $afternoonIn);
            $aOut = \Carbon\Carbon::createFromFormat('H:i', $afternoonOut);
            $afternoonMinutes = abs($aOut->diffInMinutes($aIn, false));
        }

        if ($request->has_overtime && $request->ot_clock_in && $request->ot_clock_out) {
            $otIn = \Carbon\Carbon::createFromFormat('H:i', $request->ot_clock_in);
            $otOut = \Carbon\Carbon::createFromFormat('H:i', $request->ot_clock_out);
            $overtimeMinutes = abs($otOut->diffInMinutes($otIn, false));
        }

        $otDuration = abs($overtimeMinutes) / 60;
        $totalHours = abs($morningMinutes + $afternoonMinutes + $overtimeMinutes) / 60;

        auth()->user()->ojtLogs()->create([
            'log_date' => now()->toDateString(),
            'morning_in' => $morningIn,
            'morning_out' => $morningOut,
            'afternoon_in' => $afternoonIn,
            'afternoon_out' => $afternoonOut,
            'ot_clock_in' => $request->ot_clock_in,
            'ot_clock_out' => $request->ot_clock_out,
            'ot_duration' => $otDuration,
            'tasks_performed' => $request->activity_summary,
            'hours_rendered' => $totalHours,
            'status' => 'Pending',
            'has_overtime' => $request->has('has_overtime'),
        ]);

        return redirect()->back()->with('success', 'Shift logged successfully!');
    }
}
