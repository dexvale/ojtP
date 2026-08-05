<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\StudentProfile;

class InternsController extends Controller
{
    public function index()
    {
        $companyId = auth()->user()->company_id ?? null;

        $interns = StudentProfile::where('company_id', $companyId)
            ->with(['user', 'academicCourse', 'evaluations'])
            ->withSum(['ojtLogs as approved_hours' => function ($query) {
                $query->where('status', 'Approved');
            }], 'hours_rendered')
            ->get();

        return view('supervisor.interns.index', compact('interns'));
    }
}
