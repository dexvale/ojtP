<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\StudentProfile;
use App\Models\StudentEvaluation;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function store(Request $request, $studentId)
    {
        $request->validate([
            'technical_score'   => 'required|numeric|min:1|max:5',
            'soft_skills_score' => 'required|numeric|min:1|max:5',
            'attitude_score'    => 'required|numeric|min:1|max:5',
            'comments'          => 'nullable|string|max:1000',
        ]);

        $student = StudentProfile::findOrFail($studentId);

        // Only allow supervisor from the same company
        $supervisor = auth()->user();
        if ($supervisor->company_id !== $student->company_id) {
            abort(403, 'You are not authorized to evaluate this student.');
        }

        // Upsert: one evaluation per supervisor per student
        StudentEvaluation::updateOrCreate(
            [
                'student_id'    => $student->id,
                'supervisor_id' => $supervisor->id,
            ],
            [
                'technical_score'   => $request->technical_score,
                'soft_skills_score' => $request->soft_skills_score,
                'attitude_score'    => $request->attitude_score,
                'comments'          => $request->comments,
                'evaluated_at'      => now(),
            ]
        );

        return back()->with('success', "Evaluation for {$student->first_name} {$student->last_name} has been saved.");
    }

    /**
     * Fixed-URL version: reads student_id from the POST body.
     * Used by the modal form to avoid Alpine.js dynamic :action binding issues.
     */
    public function storeFromForm(Request $request)
    {
        $request->validate([
            'student_id'        => 'required|exists:student_profiles,id',
            'technical_score'   => 'required|numeric|min:1|max:5',
            'soft_skills_score' => 'required|numeric|min:1|max:5',
            'attitude_score'    => 'required|numeric|min:1|max:5',
            'comments'          => 'nullable|string|max:1000',
        ]);

        $student = StudentProfile::findOrFail($request->student_id);

        // Only allow supervisor from the same company
        $supervisor = auth()->user();
        if ($supervisor->company_id !== $student->company_id) {
            abort(403, 'You are not authorized to evaluate this student.');
        }

        StudentEvaluation::updateOrCreate(
            [
                'student_id'    => $student->id,
                'supervisor_id' => $supervisor->id,
            ],
            [
                'technical_score'   => $request->technical_score,
                'soft_skills_score' => $request->soft_skills_score,
                'attitude_score'    => $request->attitude_score,
                'comments'          => $request->comments,
                'evaluated_at'      => now(),
            ]
        );

        return back()->with('success', "Evaluation for {$student->first_name} {$student->last_name} has been saved successfully.");
    }
}
