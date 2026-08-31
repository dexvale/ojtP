<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Requirement;
use App\Models\RequirementSubmission;
use Illuminate\Support\Facades\Storage;

class CoordinatorRequirementController extends Controller
{
    public function index()
    {
        $coordinator = auth()->user();
        $reqQuery = Requirement::orderBy('created_at', 'desc');

        if ($coordinator->role === 'Coordinator' && $coordinator->managedCourses()->exists()) {
            $courseIds = $coordinator->managedCourses->pluck('id')->toArray();
            $reqQuery->whereHas('courses', function($q) use ($courseIds) {
                $q->whereIn('courses.id', $courseIds);
            });
        }
        $requirements = $reqQuery->get();

        $submissionsQuery = RequirementSubmission::with(['requirement', 'user.studentProfile'])
            ->whereHas('requirement')
            ->orderBy('created_at', 'desc');

        if ($coordinator->role === 'Coordinator' && $coordinator->managedCourses()->exists()) {
            $courseNames = $coordinator->managedCourses->pluck('course_name')->toArray();
            $submissionsQuery->whereHas('user.studentProfile', function ($q) use ($courseNames) {
                $q->whereIn('course', $courseNames);
            });
        }

        $submissions = $submissionsQuery->get();
        $managedCourses = $coordinator->role === 'Coordinator' ? $coordinator->managedCourses : \App\Models\Course::all();

        return view('coordinator.requirements', compact('requirements', 'submissions', 'managedCourses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'template' => 'nullable|file|mimes:pdf,docx,doc|max:5120', // max 5MB
            'courses' => 'required|array|min:1',
            'courses.*' => 'exists:courses,id'
        ]);

        $templatePath = null;
        if ($request->hasFile('template')) {
            $templatePath = $request->file('template')->store('requirements', 'public');
        }

        $requirement = Requirement::create([
            'title' => $request->title,
            'description' => $request->description,
            'template_path' => $templatePath,
        ]);

        $requirement->courses()->sync($request->courses);

        return redirect()->back()->with('success', 'OJT Requirement template created successfully.');
    }

    public function destroy($id)
    {
        $requirement = Requirement::findOrFail($id);

        if ($requirement->template_path) {
            Storage::disk('public')->delete($requirement->template_path);
        }

        // Delete all associated student submission files to prevent orphaned files
        foreach($requirement->submissions as $sub) {
            if ($sub->file_path) {
                Storage::disk('public')->delete($sub->file_path);
            }
        }

        // Explicitly delete submissions from the database
        $requirement->submissions()->delete();

        $requirement->delete();

        return redirect()->back()->with('success', 'Requirement template and all associated submissions deleted successfully.');
    }

    public function approve($id)
    {
        $submission = RequirementSubmission::findOrFail($id);
        $submission->update([
            'status' => 'Approved',
            'remarks' => null
        ]);

        return redirect()->back()->with('success', 'Requirement submission approved.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'remarks' => 'required|string|max:500'
        ]);

        $submission = RequirementSubmission::findOrFail($id);
        $submission->update([
            'status' => 'Rejected',
            'remarks' => $request->remarks
        ]);

        return redirect()->back()->with('success', 'Requirement submission returned for revision.');
    }
}
