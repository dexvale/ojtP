<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\StudentProfile;
use App\Models\StudentEvaluation;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    /**
     * Store or update evaluation from route parameter.
     */
    public function store(Request $request, $studentId)
    {
        return $this->processEvaluation($request, $studentId);
    }

    /**
     * Fixed-URL version: reads student_id from POST body.
     */
    public function storeFromForm(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:student_profiles,id',
        ]);

        return $this->processEvaluation($request, $request->student_id);
    }

    /**
     * Core evaluation processor for BISU Balilihan Appraisal Rubric & Grading Sheet.
     */
    protected function processEvaluation(Request $request, $studentId)
    {
        $student = StudentProfile::with(['company', 'academicCourse'])->findOrFail($studentId);

        // Only allow supervisor from the same company or admin/coordinator
        $user = auth()->user();
        $isSupervisor = $user->hasRole('Advisor') || $user->role === 'Advisor';
        $isAdminOrCoord = $user->hasRole('Admin') || $user->hasRole('Coordinator') || in_array($user->role, ['Admin', 'Coordinator']);

        if ($isSupervisor && $user->company_id !== $student->company_id && $student->supervisor_id !== $user->id) {
            abort(403, 'You are not authorized to evaluate this student.');
        }

        // Validate 12 criteria (1-5), attendance rating (1-5), comments, and evaluator info
        $validated = $request->validate([
            'technical_knowledge'      => 'nullable|numeric|min:1|max:5',
            'quality_of_work'          => 'nullable|numeric|min:1|max:5',
            'initiative_dependability' => 'nullable|numeric|min:1|max:5',
            'cooperation'              => 'nullable|numeric|min:1|max:5',
            'personality'              => 'nullable|numeric|min:1|max:5',
            'time_management'          => 'nullable|numeric|min:1|max:5',
            'attitude_maturity'        => 'nullable|numeric|min:1|max:5',
            'problem_solving'          => 'nullable|numeric|min:1|max:5',
            'safety_consciousness'     => 'nullable|numeric|min:1|max:5',
            'communication_skills'     => 'nullable|numeric|min:1|max:5',
            'positive_attitude'        => 'nullable|numeric|min:1|max:5',
            'self_confidence'          => 'nullable|numeric|min:1|max:5',
            'attendance_rating'        => 'nullable|numeric|min:1|max:5',
            
            // Legacy fallbacks if submitted
            'technical_score'          => 'nullable|numeric|min:1|max:5',
            'soft_skills_score'        => 'nullable|numeric|min:1|max:5',
            'attitude_score'           => 'nullable|numeric|min:1|max:5',

            'comments'                 => 'nullable|string|max:2000',
            'rated_by_name'            => 'nullable|string|max:255',
            'rated_by_designation'     => 'nullable|string|max:255',
        ]);

        // Build criteria dictionary
        $criteriaKeys = array_keys(StudentEvaluation::rubricCriteria());
        $criteriaScores = [];

        foreach ($criteriaKeys as $key) {
            if (isset($validated[$key])) {
                $criteriaScores[$key] = floatval($validated[$key]);
            } else {
                // Fallback to legacy fields if criteria not explicitly sent
                if (in_array($key, ['technical_knowledge', 'time_management', 'problem_solving'])) {
                    $criteriaScores[$key] = floatval($validated['technical_score'] ?? 5);
                } elseif (in_array($key, ['quality_of_work', 'initiative_dependability', 'safety_consciousness'])) {
                    $criteriaScores[$key] = floatval($validated['soft_skills_score'] ?? 5);
                } else {
                    $criteriaScores[$key] = floatval($validated['attitude_score'] ?? 5);
                }
            }
        }

        $attendanceRating = floatval($validated['attendance_rating'] ?? 5);
        $calculated = StudentEvaluation::calculateBisuRatings($criteriaScores, $attendanceRating);

        $ratedByName = trim($validated['rated_by_name'] ?? '') ?: ($user->display_name ?? $user->email);
        $ratedByDesignation = trim($validated['rated_by_designation'] ?? '') ?: 'OJT Supervisor / Host Agency Mentor';

        // Upsert evaluation
        StudentEvaluation::updateOrCreate(
            [
                'student_id'    => $student->id,
                'supervisor_id' => $user->id,
            ],
            [
                'criteria_scores'        => $criteriaScores,
                'job_performance_score'  => $calculated['job_performance_score'],
                'workmanship_score'      => $calculated['workmanship_score'],
                'work_habits_score'      => $calculated['work_habits_score'],
                'attendance_score'       => $calculated['attendance_score'],
                'final_rating'           => $calculated['final_rating'],
                'transmuted_grade'       => $calculated['transmuted_grade'],
                'technical_score'        => $calculated['technical_score'],
                'soft_skills_score'      => $calculated['soft_skills_score'],
                'attitude_score'         => $calculated['attitude_score'],
                'comments'               => $validated['comments'] ?? null,
                'rated_by_name'          => $ratedByName,
                'rated_by_designation'   => $ratedByDesignation,
                'evaluated_at'           => now(),
            ]
        );

        return back()->with('success', "BISU Evaluation for {$student->first_name} {$student->last_name} has been saved successfully! Final Rating: {$calculated['final_rating']}% (Grade: {$calculated['transmuted_grade']}).");
    }

    /**
     * Display the official printable BISU Grading Sheet and Performance Appraisal document.
     */
    public function showGradingSheet($studentId)
    {
        $student = StudentProfile::with([
            'user',
            'company',
            'academicCourse',
            'academicTerm',
            'evaluations' => fn($q) => $q->latest('evaluated_at')->with(['supervisor', 'approvedBy']),
            'ojtLogs' => fn($q) => $q->where('status', 'Approved')->orderBy('log_date', 'asc'),
        ])->findOrFail($studentId);

        $user = auth()->user();

        // Authorization check
        if ($user->hasRole('Advisor') || $user->role === 'Advisor') {
            if ($user->company_id !== $student->company_id && $student->supervisor_id !== $user->id) {
                abort(403, 'Unauthorized access to student grading sheet.');
            }
        } elseif ($user->hasRole('Student') || $user->role === 'Student') {
            if ($student->user_id !== $user->id) {
                abort(403, 'Unauthorized access.');
            }
        }

        $evaluation = $student->evaluations->first();

        // Calculate training dates & total hours rendered
        $approvedLogs = $student->ojtLogs;
        $totalHoursRendered = $approvedLogs->sum('hours_rendered');
        
        $trainingStartDate = $student->internship_start 
            ? \Carbon\Carbon::parse($student->internship_start)->format('M d, Y') 
            : ($approvedLogs->first() ? \Carbon\Carbon::parse($approvedLogs->first()->log_date)->format('M d, Y') : 'N/A');

        $trainingEndDate = $approvedLogs->last() 
            ? \Carbon\Carbon::parse($approvedLogs->last()->log_date)->format('M d, Y') 
            : 'Present';

        $criteriaDefinitions = StudentEvaluation::rubricCriteria();

        return view('evaluations.grading_sheet', compact(
            'student',
            'evaluation',
            'totalHoursRendered',
            'trainingStartDate',
            'trainingEndDate',
            'criteriaDefinitions'
        ));
    }

    /**
     * Coordinator endorses/approves the official BISU evaluation.
     */
    public function endorseEvaluation(Request $request, $studentId)
    {
        $student = StudentProfile::findOrFail($studentId);
        $user = auth()->user();

        if (!in_array($user->role, ['Admin', 'Coordinator']) && !$user->hasRole('Coordinator') && !$user->hasRole('Admin')) {
            abort(403, 'Only Coordinators or Administrators can endorse official grading sheets.');
        }

        $evaluation = StudentEvaluation::where('student_id', $student->id)->latest('evaluated_at')->firstOrFail();

        $validated = $request->validate([
            'approved_by_name'        => 'nullable|string|max:255',
            'approved_by_designation' => 'nullable|string|max:255',
        ]);

        $evaluation->update([
            'approved_by_id'          => $user->id,
            'approved_by_name'        => trim($validated['approved_by_name'] ?? '') ?: ($user->display_name ?? 'OJT Coordinator'),
            'approved_by_designation' => trim($validated['approved_by_designation'] ?? '') ?: 'OJT Coordinator / Department Chair',
            'approved_at'             => now(),
        ]);

        return back()->with('success', "Evaluation for {$student->first_name} {$student->last_name} has been officially endorsed.");
    }
}
