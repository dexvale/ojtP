<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\StudentProfile;

class InternsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $companyId = $user->company_id ?? null;

        $query = StudentProfile::where('company_id', $companyId);

        if (StudentProfile::where('supervisor_id', $user->id)->exists()) {
            $query->where('supervisor_id', $user->id);
        }

        $interns = $query
            ->with(['user', 'academicCourse', 'evaluations'])
            ->withSum(['ojtLogs as approved_hours' => function ($query) {
                $query->where('status', 'Approved');
            }], 'hours_rendered')
            ->get();

        return view('supervisor.interns.index', compact('interns'));
    }
}
