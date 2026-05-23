<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShiftLogRequest;
use Carbon\Carbon;
use App\Models\ShiftLog;
use Illuminate\Http\Request;

class ShiftLogController extends Controller
{
    public function store(ShiftLogRequest $request)
    {
        $user = $request->user();
        $internship = $user->internships()->first();

        if (!$internship) {
            return response()->json(['message' => 'No active internship found.'], 404);
        }

        $totalMinutes = 0;

        if ($request->morning_in && $request->morning_out) {
            $morningIn = Carbon::parse($request->morning_in);
            $morningOut = Carbon::parse($request->morning_out);
            $totalMinutes += $morningOut->diffInMinutes($morningIn);
        }

        if ($request->afternoon_in && $request->afternoon_out) {
            $afternoonIn = Carbon::parse($request->afternoon_in);
            $afternoonOut = Carbon::parse($request->afternoon_out);
            $totalMinutes += $afternoonOut->diffInMinutes($afternoonIn);
        }

        $totalHours = $totalMinutes / 60;

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('shift_photos', 'public');
        }

        $shiftLog = ShiftLog::create([
            'internship_id' => $internship->id,
            'date' => $request->date,
            'morning_in' => $request->morning_in,
            'morning_out' => $request->morning_out,
            'afternoon_in' => $request->afternoon_in,
            'afternoon_out' => $request->afternoon_out,
            'activity_summary' => $request->activity_summary,
            'photo_path' => $photoPath,
            'is_overtime' => $request->boolean('is_overtime'),
            'total_hours' => round($totalHours, 2),
            'status' => 'Pending',
        ]);

        return response()->json([
            'message' => 'Shift log successfully stored.',
            'shift_log' => $shiftLog
        ], 201);
    }
}
