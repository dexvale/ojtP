<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Course;
use App\Models\User;

class PlacementController extends Controller
{
    public function index()
    {
        $user = auth()->user()->load(['studentProfile.company', 'studentProfile.supervisor']);
        $profile = $user->studentProfile;

        // Fetch approved companies offering slots or associated with the student's course
        $companies = Company::where('status', 'approved')
            ->with(['courses', 'users' => function($q) {
                $q->where('role', 'Advisor');
            }])
            ->orderBy('name', 'asc')
            ->get();

        return view('student.placement', compact('user', 'profile', 'companies'));
    }

    public function apply(Request $request)
    {
        $user = auth()->user();
        $profile = $user->studentProfile;

        if (!$profile) {
            return redirect()->back()->with('error', 'Please complete your student profile before applying for placement.');
        }

        $request->validate([
            'company_mode'               => 'required|in:existing,new',
            'company_id'                 => 'required_if:company_mode,existing|nullable|exists:companies,id',
            'company_name'               => 'required_if:company_mode,new|nullable|string|max:255',
            'company_industry'           => 'nullable|string|max:255',
            'company_location'           => 'required_if:company_mode,new|nullable|string|max:255',
            'company_contact'            => 'nullable|string|max:255',
            
            'supervisor_mode'            => 'required|in:existing,new',
            'existing_supervisor_id'     => 'required_if:supervisor_mode,existing|nullable|exists:users,id',
            'supervisor_name'            => 'required_if:supervisor_mode,new|nullable|string|max:255',
            'supervisor_email'           => 'required_if:supervisor_mode,new|nullable|email:rfc,dns|max:255',
            'supervisor_contact'         => 'nullable|string|max:255',
            'department'                 => 'required|string|max:255',
            
            'internship_start'           => 'nullable|date',
            'acceptance_letter'          => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $letterPath = $profile->acceptance_letter_path;
        if ($request->hasFile('acceptance_letter')) {
            $letterPath = $request->file('acceptance_letter')->store('placement_letters', 'public');
        }

        if ($request->company_mode === 'existing') {
            $companyId = $request->company_id;
            $pendingCompanyName = null;
        } else {
            $companyId = null;
            $pendingCompanyName = $request->company_name;
        }

        if ($request->supervisor_mode === 'existing') {
            $existingSupervisor = User::find($request->existing_supervisor_id);
            $supervisorId = $existingSupervisor ? $existingSupervisor->id : null;
            $pendingSupName = $existingSupervisor ? ($existingSupervisor->name ?? $existingSupervisor->email) : null;
            $pendingSupEmail = $existingSupervisor ? $existingSupervisor->email : null;
            $pendingSupContact = null;
        } else {
            $supervisorId = null;
            $pendingSupName = $request->supervisor_name;
            $pendingSupEmail = $request->supervisor_email;
            $pendingSupContact = $request->supervisor_contact;
        }

        $profile->update([
            'company_id'                 => $companyId,
            'pending_company_name'       => $pendingCompanyName,
            'department'                 => $request->department,
            'supervisor_id'              => $supervisorId,
            'pending_supervisor_name'    => $pendingSupName,
            'pending_supervisor_email'   => $pendingSupEmail,
            'pending_supervisor_contact' => $pendingSupContact,
            'internship_start'           => $request->internship_start,
            'acceptance_letter_path'     => $letterPath,
            'placement_status'           => 'Pending',
            'placement_remarks'          => null,
        ]);

        return redirect()->route('student.placement')->with('success', 'Your OJT placement application has been submitted for Coordinator review and endorsement.');
    }
}
