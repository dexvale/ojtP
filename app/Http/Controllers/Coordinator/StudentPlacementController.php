<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentProfile;

class StudentPlacementController extends Controller
{
    public function assign(Request $request, StudentProfile $student)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id'
        ]);

        $student->update([
            'company_id' => $request->company_id
        ]);

        return redirect()->back()->with('success', 'Placement assigned successfully.');
    }
}
