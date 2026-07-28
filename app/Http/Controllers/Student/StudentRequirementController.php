<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Requirement;
use App\Models\RequirementSubmission;
use Illuminate\Support\Facades\Storage;

class StudentRequirementController extends Controller
{
    public function index()
    {
        $student = auth()->user()->studentProfile;
        $requirements = collect();
        if ($student) {
            $requirements = Requirement::whereHas('courses', function ($query) use ($student) {
                $query->where('course_name', $student->course);
            })->orderBy('created_at', 'desc')->get();
        }

        $studentSubmissions = RequirementSubmission::where('user_id', auth()->id())
            ->get()
            ->keyBy('requirement_id');

        return view('student.requirements', compact('requirements', 'studentSubmissions'));
    }

    public function submit(Request $request, $id)
    {
        $request->validate([
            'submission_file' => 'required|file|mimes:pdf,docx,doc,zip,jpg,png|max:10240', // max 10MB
        ]);

        $requirement = Requirement::findOrFail($id);

        $filePath = $request->file('submission_file')->store('submissions', 'public');

        // Check if student already submitted this requirement
        $submission = RequirementSubmission::where('user_id', auth()->id())
            ->where('requirement_id', $requirement->id)
            ->first();

        if ($submission) {
            // Delete old file if it exists
            if ($submission->file_path) {
                Storage::disk('public')->delete($submission->file_path);
            }

            // Update submission and reset status to Pending for review
            $submission->update([
                'file_path' => $filePath,
                'status' => 'Pending',
                'remarks' => null,
            ]);
        } else {
            RequirementSubmission::create([
                'requirement_id' => $requirement->id,
                'user_id' => auth()->id(),
                'file_path' => $filePath,
                'status' => 'Pending',
            ]);
        }

        return redirect()->back()->with('success', 'Document submitted successfully. Status set to Pending Verification.');
    }
}
