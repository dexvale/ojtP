<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Requirement;
use App\Models\RequirementSubmission;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;

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

    public function downloadTemplate($id)
    {
        $requirement = Requirement::findOrFail($id);

        if (!$requirement->template_path || !Storage::disk('public')->exists($requirement->template_path)) {
            return redirect()->back()->withErrors('Template file not found.');
        }

        $extension = pathinfo($requirement->template_path, PATHINFO_EXTENSION) ?: 'pdf';
        $downloadName = \Illuminate\Support\Str::slug($requirement->title) . '.' . $extension;

        return Storage::disk('public')->download($requirement->template_path, $downloadName);
    }

    public function submit(Request $request, $id)
    {
        // Support both submission_files array and submission_file single input
        $files = $request->file('submission_files');
        if (!$files && $request->hasFile('submission_file')) {
            $files = [$request->file('submission_file')];
        }

        if (empty($files)) {
            return redirect()->back()->withErrors(['submission_files' => 'Please select at least one document or scan to upload.']);
        }

        $request->validate([
            'submission_files' => 'nullable|array',
            'submission_files.*' => 'file|mimes:pdf,docx,doc,zip,jpg,jpeg,png,webp|max:15360', // max 15MB each
            'submission_file' => 'nullable|file|mimes:pdf,docx,doc,zip,jpg,jpeg,png,webp|max:15360',
        ]);

        $requirement = Requirement::findOrFail($id);
        $filePath = null;

        // If only 1 file is uploaded
        if (count($files) === 1) {
            $file = $files[0];
            $ext = strtolower($file->getClientOriginalExtension());

            if (in_array($ext, ['pdf', 'docx', 'doc', 'zip'])) {
                $filePath = $file->store('submissions', 'public');
            } else {
                // Single image (JPG, PNG, WEBP) -> convert into a clean single-page PDF
                try {
                    $filePath = $this->mergeFilesToPdf($files, $requirement->id);
                } catch (\Throwable $e) {
                    $filePath = $file->store('submissions', 'public');
                }
            }
        } else {
            // Multiple files uploaded -> merge into one PDF
            foreach ($files as $file) {
                $ext = strtolower($file->getClientOriginalExtension());
                if (!in_array($ext, ['pdf', 'jpg', 'jpeg', 'png', 'webp'])) {
                    return redirect()->back()->withErrors([
                        'submission_files' => 'Multi-file automatic merging supports PDF and image scans (JPG, PNG, WEBP). Please ensure all selected files are PDFs or images, or archive them into a .ZIP.'
                    ]);
                }
            }

            try {
                $filePath = $this->mergeFilesToPdf($files, $requirement->id);
            } catch (\Throwable $e) {
                return redirect()->back()->withErrors([
                    'submission_files' => 'Could not merge documents into PDF: ' . $e->getMessage() . '. Please verify your files or upload them as a ZIP.'
                ]);
            }
        }

        // Check if student already submitted this requirement
        $submission = RequirementSubmission::where('user_id', auth()->id())
            ->where('requirement_id', $requirement->id)
            ->first();

        if ($submission) {
            // Delete old file if it exists
            if ($submission->file_path && Storage::disk('public')->exists($submission->file_path)) {
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

        $successMsg = count($files) > 1
            ? count($files) . ' files merged into a single PDF and submitted successfully. Status set to Pending Verification.'
            : 'Document submitted successfully. Status set to Pending Verification.';

        return redirect()->back()->with('success', $successMsg);
    }

    private function mergeFilesToPdf(array $files, int $requirementId): string
    {
        $pdf = new Fpdi('P', 'pt');

        // Standard A4 dimensions in points: 595.28 x 841.89
        $a4Width = 595.28;
        $a4Height = 841.89;
        $tempCleanup = [];

        try {
            foreach ($files as $file) {
                $ext = strtolower($file->getClientOriginalExtension());
                $realPath = $file->getRealPath();

                if ($ext === 'pdf') {
                    $pageCount = $pdf->setSourceFile($realPath);
                    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                        $templateId = $pdf->importPage($pageNo);
                        $size = $pdf->getTemplateSize($templateId);
                        $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                        $pdf->useTemplate($templateId);
                    }
                } elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                    $imagePath = $realPath;
                    $imageType = ($ext === 'png') ? 'PNG' : 'JPG';

                    // Convert WEBP to PNG if needed
                    if ($ext === 'webp' && function_exists('imagecreatefromwebp')) {
                        $src = @imagecreatefromwebp($realPath);
                        if ($src) {
                            $tempConverted = tempnam(sys_get_temp_dir(), 'ojt_conv_') . '.png';
                            imagepng($src, $tempConverted);
                            $tempCleanup[] = $tempConverted;
                            $imagePath = $tempConverted;
                            $imageType = 'PNG';
                        }
                    }

                    $imgInfo = @getimagesize($imagePath);
                    if ($imgInfo && $imgInfo[0] > 0 && $imgInfo[1] > 0) {
                        $imgWidth = $imgInfo[0];
                        $imgHeight = $imgInfo[1];
                        $isLandscape = $imgWidth > $imgHeight;

                        $pageW = $isLandscape ? $a4Height : $a4Width;
                        $pageH = $isLandscape ? $a4Width : $a4Height;
                        $orientation = $isLandscape ? 'L' : 'P';

                        $pdf->AddPage($orientation, [$pageW, $pageH]);

                        // Scale to fit neatly with 30pt margin
                        $margin = 30;
                        $maxW = $pageW - ($margin * 2);
                        $maxH = $pageH - ($margin * 2);

                        $ratio = min($maxW / $imgWidth, $maxH / $imgHeight);
                        $drawW = $imgWidth * $ratio;
                        $drawH = $imgHeight * $ratio;
                        $drawX = ($pageW - $drawW) / 2;
                        $drawY = ($pageH - $drawH) / 2;

                        $pdf->Image($imagePath, $drawX, $drawY, $drawW, $drawH, $imageType);
                    }
                }
            }

            $fileName = 'submissions/merged_' . $requirementId . '_' . time() . '_' . auth()->id() . '.pdf';
            $outputPath = Storage::disk('public')->path($fileName);

            $dir = dirname($outputPath);
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }

            $pdf->Output($outputPath, 'F');
            return $fileName;
        } finally {
            foreach ($tempCleanup as $tmp) {
                if (file_exists($tmp)) {
                    @unlink($tmp);
                }
            }
        }
    }

    public function fill($id)
    {
        $requirement = Requirement::findOrFail($id);
        
        if (!$requirement->template_path) {
            return redirect()->back()->withErrors('This requirement does not have a template to fill.');
        }

        $user = auth()->user();
        $student = $user->studentProfile;
        $company = $student?->company;
        $supervisor = $student?->supervisor ?? ($company ? $company->users()->where('role', 'Advisor')->first() : null);
        $dean = \App\Models\User::where('role', 'Admin')->first();
        $coordinator = \App\Models\User::where('role', 'Coordinator')->whereHas('managedCourses', function($q) use ($student) {
            if ($student && $student->course) {
                $q->where('course_name', $student->course);
            }
        })->first() ?? \App\Models\User::where('role', 'Coordinator')->first();

        $fullName = trim(($student?->first_name ?? '') . ' ' . ($student?->middle_name ? $student->middle_name . ' ' : '') . ($student?->last_name ?? ''));
        $studentId = $student?->student_id_number ?? '';
        $course = $student?->course ?? '';
        $hours = $student?->required_hours ?? ($student?->academicCourse?->required_hours ?? 600);
        $address = $student?->contact_address ?? '';
        $contactNumber = $student?->contact_number ?? '';
        $email = $user->email ?? '';
        $dob = $student?->date_of_birth ? date('F d, Y', strtotime($student->date_of_birth)) : '';
        $bloodType = $student?->blood_type ?? '';
        $emergencyPerson = $student?->emergency_contact_person ?? '';
        $emergencyNumber = $student?->emergency_contact_number ?? '';
        $fatherName = $student?->father_name ?? '';
        $motherName = $student?->mother_name ?? '';
        $guardian = $fatherName ?: ($motherName ?: ($emergencyPerson ?: ''));
        $companyName = $company?->name ?? '';
        $companyAddress = $company?->location ?? '';
        $supervisorName = $supervisor?->name ?? ($company?->contact_person ?? '');
        $department = $student?->department ?? ($supervisor?->department ?? 'Information Technology / Operations');
        $deanName = $dean?->name ?? 'Dean Arthur Pendelton';
        $coordinatorName = $coordinator?->name ?? 'Dr. Elena Vance';
        $currentDate = date('F d, Y');
        $academicYear = 'A.Y. ' . date('Y') . '-' . (date('Y') + 1);

        $studentData = [
            'fullName'        => $fullName,
            'studentId'       => $studentId,
            'course'          => $course,
            'hours'           => $hours . ' Hours',
            'address'         => $address,
            'contactNumber'   => $contactNumber,
            'email'           => $email,
            'dob'             => $dob,
            'bloodType'       => $bloodType,
            'fatherName'      => $fatherName,
            'motherName'      => $motherName,
            'guardian'        => $guardian,
            'emergencyPerson' => $emergencyPerson,
            'emergencyNumber' => $emergencyNumber,
            'companyName'     => $companyName,
            'companyAddress'  => $companyAddress,
            'supervisorName'  => $supervisorName,
            'department'      => $department,
            'deanName'        => $deanName,
            'coordinatorName' => $coordinatorName,
            'currentDate'     => $currentDate,
            'academicYear'    => $academicYear,
        ];

        return view('student.requirements.fill', compact('requirement', 'studentData'));
    }

    public function submitForm(Request $request, $id)
    {
        $request->validate([
            'stamps' => 'required|json'
        ]);

        $requirement = Requirement::findOrFail($id);
        $stamps = json_decode($request->stamps, true) ?: [];

        try {
            $fileName = $this->generateStampedPdf($requirement, $stamps);
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors('Could not generate filled document: ' . $e->getMessage());
        }

        // Check if student already submitted this requirement
        $submission = RequirementSubmission::where('user_id', auth()->id())
            ->where('requirement_id', $requirement->id)
            ->first();

        if ($submission) {
            if ($submission->file_path && Storage::disk('public')->exists($submission->file_path)) {
                Storage::disk('public')->delete($submission->file_path);
            }

            $submission->update([
                'file_path' => $fileName,
                'status' => 'Pending',
                'remarks' => null,
            ]);
        } else {
            RequirementSubmission::create([
                'requirement_id' => $requirement->id,
                'user_id' => auth()->id(),
                'file_path' => $fileName,
                'status' => 'Pending',
            ]);
        }

        return redirect()->route('student.requirements')->with('success', 'Form filled and submitted successfully.');
    }

    public function downloadFilled(Request $request, $id)
    {
        $request->validate([
            'stamps' => 'required|json'
        ]);

        $requirement = Requirement::findOrFail($id);
        $stamps = json_decode($request->stamps, true) ?: [];

        try {
            $fileName = $this->generateStampedPdf($requirement, $stamps);
            $fullPath = Storage::disk('public')->path($fileName);
            $downloadName = \Illuminate\Support\Str::slug($requirement->title) . '_filled_for_signing.pdf';

            return response()->download($fullPath, $downloadName)->deleteFileAfterSend(true);
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors('Could not generate filled PDF for download: ' . $e->getMessage());
        }
    }

    private function generateStampedPdf(Requirement $requirement, array $stamps): string
    {
        if (!$requirement->template_path || !Storage::disk('public')->exists($requirement->template_path)) {
            throw new \Exception('Original template not found.');
        }

        $templateFullPath = Storage::disk('public')->path($requirement->template_path);

        // Initialize FPDI with 'pt' (points) to match 1:1 with browser rendering scale
        $pdf = new Fpdi('P', 'pt');
        $pageCount = $pdf->setSourceFile($templateFullPath);

        // Group stamps by page number
        $stampsByPage = [];
        foreach ($stamps as $stamp) {
            $pageNo = isset($stamp['page']) ? (int)$stamp['page'] : 1;
            $stampsByPage[$pageNo][] = $stamp;
        }

        // Import all pages of the document
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($templateId);

            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($templateId);

            $pdf->SetFont('Helvetica', '', 12);

            // Loop through stamps for this specific page
            if (isset($stampsByPage[$pageNo])) {
                foreach ($stampsByPage[$pageNo] as $stamp) {
                    $pdf->SetXY($stamp['x'], $stamp['y']);
                    $pdf->Write(0, $stamp['text']);
                }
            }
        }

        $fileName = 'submissions/stamped_' . time() . '_' . auth()->id() . '.pdf';
        $outputPath = Storage::disk('public')->path($fileName);

        $dir = dirname($outputPath);
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $pdf->Output($outputPath, 'F');

        return $fileName;
    }
}
