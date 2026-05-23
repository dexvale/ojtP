<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Assuming the logged-in user is a student
        $user = $request->user();
        
        // Find the active internship
        $internship = $user->internships()->with(['documents', 'advisor', 'shiftLogs' => function($query) {
            $query->orderBy('date', 'desc');
        }])->first();

        if (!$internship) {
            return response()->json(['message' => 'No active internship found.'], 404);
        }

        // 1. Internship Progress
        $approved_hours = $internship->shiftLogs->where('status', 'Approved')->sum('total_hours');
        $lacking_hours = max(0, $internship->required_hours - $approved_hours);
        $percentage_completed = $internship->required_hours > 0 ? ($approved_hours / $internship->required_hours) * 100 : 0;

        // 2. Required Documents
        $documents = $internship->documents()->latest()->get();

        // 3. Assigned Advisor
        $advisor = [
            'name' => $internship->advisor->name ?? null,
            'email' => $internship->advisor->email ?? null,
        ];

        // 4. Recent Submissions
        $recent_submissions = $internship->shiftLogs()->take(5);

        // 5. Prediction Text
        $twoWeeksAgo = Carbon::now()->subDays(14);
        $recentLogs = $internship->shiftLogs()
            ->where('date', '>=', $twoWeeksAgo)
            ->where('status', 'Approved');
        
        $averageWeeklyHours = $recentLogs->sum('total_hours') / 2; // Average over 2 weeks

        $weeksToFinish = $averageWeeklyHours > 0 ? ceil($lacking_hours / $averageWeeklyHours) : null;
        $prediction_text = $weeksToFinish ? "You are on track to finish in {$weeksToFinish} weeks" : "Not enough data for prediction";

        return response()->json([
            'progress' => [
                'approved_hours' => $approved_hours,
                'lacking_hours' => $lacking_hours,
                'percentage_completed' => round($percentage_completed, 2),
                'prediction_text' => $prediction_text,
            ],
            'documents' => $documents,
            'advisor' => $advisor,
            'recent_submissions' => $recent_submissions,
        ]);
    }
}
