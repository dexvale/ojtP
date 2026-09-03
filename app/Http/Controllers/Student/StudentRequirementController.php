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

        // Generate template-specific default stamp coordinates
        $prefilledStamps = [];
        $titleLower = strtolower($requirement->title);
        $templateLower = strtolower($requirement->template_path);

        if (str_contains($titleLower, 'consent') || str_contains($templateLower, 'consent')) {
            // Parent's Consent Form (US Letter 612 x 792 pt)
            // 1. Date at top right
            $prefilledStamps[] = ['page' => 1, 'text' => $currentDate, 'x' => 380, 'y' => 198];

            // 2. Body paragraph lines
            if ($guardian) {
                $prefilledStamps[] = ['page' => 1, 'text' => $guardian, 'x' => 85, 'y' => 258];
            }
            if ($fullName) {
                $prefilledStamps[] = ['page' => 1, 'text' => $fullName, 'x' => 75, 'y' => 276];
            }
            if ($address) {
                $prefilledStamps[] = ['page' => 1, 'text' => $address, 'x' => 54, 'y' => 310];
            }
            if ($companyName) {
                $companyLabel = $companyAddress ? "{$companyName}, {$companyAddress}" : $companyName;
                $prefilledStamps[] = ['page' => 1, 'text' => $companyLabel, 'x' => 230, 'y' => 328];
                $prefilledStamps[] = ['page' => 1, 'text' => $companyName, 'x' => 54, 'y' => 364];
            }

            // 3. Signatures section at bottom
            if ($guardian) {
                $prefilledStamps[] = ['page' => 1, 'text' => $guardian, 'x' => 320, 'y' => 582];
            }
            if ($fullName) {
                $prefilledStamps[] = ['page' => 1, 'text' => $fullName, 'x' => 320, 'y' => 648];
            }
        } elseif (str_contains($titleLower, 'information sheet') || str_contains($titleLower, 'trainee') || str_contains($templateLower, 'student-information')) {
            // Student Trainee Information Sheet
            if ($fullName) {
                $prefilledStamps[] = ['page' => 1, 'text' => $fullName, 'x' => 140, 'y' => 165];
            }
            if ($studentId) {
                $prefilledStamps[] = ['page' => 1, 'text' => $studentId, 'x' => 430, 'y' => 165];
            }
            if ($course) {
                $prefilledStamps[] = ['page' => 1, 'text' => $course, 'x' => 140, 'y' => 190];
            }
            if ($dob) {
                $prefilledStamps[] = ['page' => 1, 'text' => $dob, 'x' => 140, 'y' => 215];
            }
            if ($bloodType) {
                $prefilledStamps[] = ['page' => 1, 'text' => $bloodType, 'x' => 430, 'y' => 215];
            }
            if ($address) {
                $prefilledStamps[] = ['page' => 1, 'text' => $address, 'x' => 140, 'y' => 240];
            }
            if ($contactNumber) {
                $prefilledStamps[] = ['page' => 1, 'text' => $contactNumber, 'x' => 140, 'y' => 265];
            }
            if ($email) {
                $prefilledStamps[] = ['page' => 1, 'text' => $email, 'x' => 350, 'y' => 265];
            }
            if ($fatherName) {
                $prefilledStamps[] = ['page' => 1, 'text' => $fatherName, 'x' => 140, 'y' => 295];
            }
            if ($motherName) {
                $prefilledStamps[] = ['page' => 1, 'text' => $motherName, 'x' => 140, 'y' => 320];
            }
            if ($emergencyPerson) {
                $prefilledStamps[] = ['page' => 1, 'text' => $emergencyPerson, 'x' => 160, 'y' => 345];
            }
            if ($emergencyNumber) {
                $prefilledStamps[] = ['page' => 1, 'text' => $emergencyNumber, 'x' => 430, 'y' => 345];
            }
            if ($companyName) {
                $prefilledStamps[] = ['page' => 1, 'text' => $companyName, 'x' => 160, 'y' => 395];
            }
            if ($companyAddress) {
                $prefilledStamps[] = ['page' => 1, 'text' => $companyAddress, 'x' => 160, 'y' => 420];
            }
            if ($department) {
                $prefilledStamps[] = ['page' => 1, 'text' => $department, 'x' => 160, 'y' => 445];
            }
            if ($supervisorName) {
                $prefilledStamps[] = ['page' => 1, 'text' => $supervisorName, 'x' => 160, 'y' => 470];
            }
        } elseif (str_contains($titleLower, 'agreement') || str_contains($titleLower, 'contract') || str_contains($titleLower, 'moa') || str_contains($templateLower, 'agreement')) {
            // Internship Contract / Agreement (MOA)
            if ($fullName) {
                $prefilledStamps[] = ['page' => 1, 'text' => $fullName, 'x' => 160, 'y' => 200];
            }
            if ($course) {
                $prefilledStamps[] = ['page' => 1, 'text' => $course, 'x' => 380, 'y' => 200];
            }
            if ($companyName) {
                $prefilledStamps[] = ['page' => 1, 'text' => $companyName, 'x' => 180, 'y' => 240];
            }
            if ($supervisorName) {
                $prefilledStamps[] = ['page' => 1, 'text' => $supervisorName, 'x' => 180, 'y' => 270];
            }
            if ($hours) {
                $prefilledStamps[] = ['page' => 1, 'text' => $hours . ' Hours', 'x' => 220, 'y' => 310];
            }
            $prefilledStamps[] = ['page' => 1, 'text' => $academicYear, 'x' => 400, 'y' => 310];
            if ($coordinatorName) {
                $prefilledStamps[] = ['page' => 1, 'text' => $coordinatorName, 'x' => 120, 'y' => 640];
            }
            if ($deanName) {
                $prefilledStamps[] = ['page' => 1, 'text' => $deanName, 'x' => 360, 'y' => 640];
            }
        }

        return view('student.requirements.fill', compact('requirement', 'studentData', 'prefilledStamps'));
    }

    public function submitForm(Request $request, $id)
    {
        $request->validate([
            'stamps' => 'required|json'
        ]);

        $requirement = Requirement::findOrFail($id);

        if (!$requirement->template_path || !Storage::disk('public')->exists($requirement->template_path)) {
            return redirect()->back()->withErrors('Original template not found.');
        }

        $stamps = json_decode($request->stamps, true) ?: [];

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
        
        $pdf->Output($outputPath, 'F');
        
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
}
