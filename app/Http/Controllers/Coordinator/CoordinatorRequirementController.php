<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Requirement;
use App\Models\RequirementSubmission;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    public function approve(Request $request, $id)
    {
        $submission = RequirementSubmission::findOrFail($id);
        $submission->update([
            'status' => 'Approved',
            'remarks' => null
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Requirement submission approved.',
                'id' => $submission->id
            ]);
        }

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

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Requirement submission returned for revision.',
                'id' => $submission->id
            ]);
        }

        return redirect()->back()->with('success', 'Requirement submission returned for revision.');
    }

    public function batchApprove(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:requirement_submissions,id'
        ]);

        $count = RequirementSubmission::whereIn('id', $request->ids)
            ->where('status', 'Pending')
            ->update([
                'status' => 'Approved',
                'remarks' => null
            ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Successfully approved {$count} requirement submission(s).",
                'count' => $count
            ]);
        }

        return redirect()->back()->with('success', "Successfully approved {$count} requirement submission(s).");
    }

    public function batchDownload(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:requirement_submissions,id'
        ]);

        $submissions = RequirementSubmission::with(['requirement', 'user.studentProfile'])
            ->whereIn('id', $request->ids)
            ->get();

        if ($submissions->isEmpty()) {
            return redirect()->back()->with('error', 'No submissions found for the selected items.');
        }

        $tempDir = storage_path('app/temp');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $zipFileName = 'OJT_Submissions_' . now()->format('Ymd_His') . '.zip';
        $tempZipPath = $tempDir . DIRECTORY_SEPARATOR . $zipFileName;

        $zip = new \ZipArchive();
        if ($zip->open($tempZipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return redirect()->back()->with('error', 'Could not create ZIP archive file.');
        }

        $usedFileNames = [];
        $addedCount = 0;

        foreach ($submissions as $sub) {
            if (!$sub->file_path || !Storage::disk('public')->exists($sub->file_path)) {
                continue;
            }

            $absolutePath = Storage::disk('public')->path($sub->file_path);

            $student = $sub->user?->studentProfile;
            $studentName = $student ? Str::slug($student->last_name . '_' . $student->first_name) : 'student_' . $sub->user_id;
            $reqTitle = $sub->requirement ? Str::slug($sub->requirement->title) : 'requirement_' . $sub->requirement_id;
            $extension = pathinfo($sub->file_path, PATHINFO_EXTENSION) ?: 'pdf';

            $baseName = "{$studentName}_{$reqTitle}";
            $entryName = "{$baseName}.{$extension}";

            $counter = 1;
            while (in_array($entryName, $usedFileNames)) {
                $entryName = "{$baseName}_{$counter}.{$extension}";
                $counter++;
            }
            $usedFileNames[] = $entryName;

            $zip->addFile($absolutePath, $entryName);
            $addedCount++;
        }

        $zip->close();

        if ($addedCount === 0) {
            if (file_exists($tempZipPath)) {
                @unlink($tempZipPath);
            }
            return redirect()->back()->with('error', 'None of the selected submissions contain accessible files.');
        }

        return response()->download($tempZipPath, $zipFileName)->deleteFileAfterSend(true);
    }
}
