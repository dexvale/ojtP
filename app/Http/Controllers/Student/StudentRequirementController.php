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

        return view('student.requirements.fill', compact('requirement', 'studentData'));
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
