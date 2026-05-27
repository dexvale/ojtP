<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OjtLog;
use Carbon\Carbon;

class OjtLogController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'log_date' => 'required|date|before_or_equal:today',
            'am_clock_in' => 'required_with:am_clock_out|nullable|date_format:H:i',
            'am_clock_out' => 'nullable|after:am_clock_in|date_format:H:i',
            'pm_clock_in' => 'required_with:pm_clock_out|nullable|date_format:H:i|after:am_clock_out',
            'pm_clock_out' => 'nullable|after:pm_clock_in|date_format:H:i',
            'activity_summary' => 'required|string|min:20',
            'photo_attachment' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $exists = OjtLog::where('user_id', auth()->id())->where('log_date', $request->log_date)->exists();
        if ($exists) {
            return redirect()->back()->withErrors(['log_date' => 'You have already submitted an OJT log for this date.']);
        }

        $photoPath = null;
        if ($request->hasFile('photo_attachment')) {
            $photoPath = $request->file('photo_attachment')->store('ojt_photos', 'public');
        }

        // Calculate hours dynamically based on inputs
        $totalHours = 0;
        
        if ($request->filled('am_clock_in') && $request->filled('am_clock_out')) {
            $amIn = Carbon::createFromFormat('H:i', $request->am_clock_in);
            $amOut = Carbon::createFromFormat('H:i', $request->am_clock_out);
            $totalHours += $amOut->diffInMinutes($amIn) / 60;
        }

        if ($request->filled('pm_clock_in') && $request->filled('pm_clock_out')) {
            $pmIn = Carbon::createFromFormat('H:i', $request->pm_clock_in);
            $pmOut = Carbon::createFromFormat('H:i', $request->pm_clock_out);
            $totalHours += $pmOut->diffInMinutes($pmIn, false) / 60;
        }

        $ot_hours = 0;
        if ($request->filled('ot_clock_in') && $request->filled('ot_clock_out')) {
            $ot_in = Carbon::createFromFormat('H:i', $request->ot_clock_in);
            $ot_out = Carbon::createFromFormat('H:i', $request->ot_clock_out);
            
            if ($ot_out->greaterThan($ot_in)) {
                $ot_hours = $ot_in->diffInMinutes($ot_out) / 60;
            }
        }

        // Guarantee we never save a negative hours value and add OT
        $totalHours = abs($totalHours) + $ot_hours;

        OjtLog::create([
            'user_id' => auth()->id(),
            'log_date' => $request->log_date,
            'morning_in' => $request->am_clock_in,
            'morning_out' => $request->am_clock_out,
            'afternoon_in' => $request->pm_clock_in,
            'afternoon_out' => $request->pm_clock_out,
            'ot_clock_in' => $request->ot_clock_in,
            'ot_clock_out' => $request->ot_clock_out,
            'ot_duration' => $ot_hours,
            'hours_rendered' => round($totalHours, 2),
            'tasks_performed' => $request->activity_summary,
            'photo_path' => $photoPath,
            'status' => 'Pending',
            'has_overtime' => $request->has('has_overtime')
        ]);

        return redirect()->back()->with('success', 'OJT Shift Logged Successfully!');
    }

    public function index()
    {
        // Fetch the authenticated student's logs ordered by newest date
        $logs = OjtLog::where('user_id', auth()->id())
            ->orderBy('log_date', 'desc')
            ->paginate(10);

        return view('student.logs', compact('logs'));
    }

    public function destroy(OjtLog $ojtLog)
    {
        // Security Check: Ensure this log belongs to the logged-in student
        if ($ojtLog->user_id !== auth()->id()) {
            abort(403);
        }

        // CRITICAL STATUS GUARD TRAP: Only allow deletion if the log is pending
        if (strtoupper($ojtLog->status) !== 'PENDING') {
            return redirect()->back()->with('error', 'You cannot delete a log that has already been approved or rejected by an advisor!');
        }

        // Clean up the associated uploaded photo file from storage if it exists
        if ($ojtLog->photo_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($ojtLog->photo_path);
        }

        $ojtLog->delete();

        return redirect()->back()->with('success', 'OJT shift log entry has been successfully deleted.');
    }
}
