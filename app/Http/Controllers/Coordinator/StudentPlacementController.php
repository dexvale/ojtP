<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentProfile;

class StudentPlacementController extends Controller
{
    public function assign(Request $request, StudentProfile $student)
    {
        $coordinator = auth()->user();
        if ($coordinator->managedCourses()->exists()) {
            $courseNames = $coordinator->managedCourses->pluck('course_name')->toArray();
            if (!in_array($student->course, $courseNames)) {
                abort(403, 'Unauthorized action. You do not manage this student\'s department.');
            }
        }

        $request->validate([
            'company_id' => 'required|exists:companies,id'
        ]);

        $student->update([
            'company_id' => $request->company_id
        ]);

        return redirect()->back()->with('success', 'Placement assigned successfully.');
    }
}
