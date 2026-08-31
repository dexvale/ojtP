<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Company;
use App\Models\User;

class CompanyController extends Controller
{
    public function index()
    {
        $coordinator = auth()->user();
        $query = Company::withCount(['studentProfiles as filled_slots']);

        if ($coordinator->role === 'Coordinator' && $coordinator->managedCourses()->exists()) {
            $courseIds = $coordinator->managedCourses->pluck('id')->toArray();
            $query->whereHas('courses', function($q) use ($courseIds) {
                $q->whereIn('courses.id', $courseIds);
            });
        }

        $companies = $query->get();
        $managedCourses = $coordinator->role === 'Coordinator' ? $coordinator->managedCourses : \App\Models\Course::all();

        return view('coordinator.companies', compact('companies', 'managedCourses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:255',
            'allocation_slots' => 'required|integer|min:0',
            'advisor_email' => 'nullable|string|email:rfc,dns|max:255|unique:users,email',
            'advisor_password' => 'nullable|string|min:8',
            'courses' => 'required|array|min:1',
            'courses.*' => 'exists:courses,id'
        ]);

        $company = Company::create([
            'name' => $validated['name'],
            'industry' => $validated['industry'] ?? null,
            'location' => $validated['location'] ?? null,
            'contact_person' => $validated['contact_person'] ?? null,
            'contact_number' => $validated['contact_number'] ?? null,
            'allocation_slots' => $validated['allocation_slots'],
        ]);

        $company->courses()->sync($validated['courses']);

        if ($request->filled('advisor_email') && $request->filled('advisor_password')) {
            $plainPassword = $request->advisor_password;

            $user = User::create([
                'email' => $request->advisor_email,
                'password' => Hash::make($plainPassword),
                'role' => 'Advisor', 
                'company_id' => $company->id,
            ]);

            $user->notify(new \App\Notifications\WelcomeAdvisorNotification($plainPassword));

            return redirect()->back()->with([
                'success' => 'Company registered and Advisor account provisioned successfully!',
                'flash_email' => $user->email,
                'flash_password' => $plainPassword,
                'flash_company' => $company->name
            ]);
        }

        return redirect()->back()->with('success', 'Company registered successfully!');
    }

    public function show(Company $company)
    {
        $company->load(['studentProfiles.user', 'users' => function($query) {
            $query->where('role', 'Advisor');
        }]);

        return view('coordinator.company_show', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:255',
            'allocation_slots' => 'required|integer|min:0'
        ]);

        $company->update($validated);

        return redirect()->back()->with('success', 'Company details updated successfully.');
    }

    public function destroy(Company $company)
    {
        // 1. Delete all user accounts with the 'Advisor' role that belong to this company
        $company->users()->where('role', 'Advisor')->delete();

        // 2. Delete the company record itself
        $company->delete();

        return redirect()->route('coordinator.companies')->with('success', 'Company and its associated advisor accounts have been cleanly removed together.');
    }

    public function storeSupervisor(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email:rfc,dns|max:255|unique:users',
            'password' => 'required|string|min:8',
            'company_id' => 'required|exists:companies,id'
        ]);

        $plainPassword = $request->password;

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($plainPassword),
            'role' => 'Advisor', 
            'company_id' => $request->company_id,
        ]);

        $user->notify(new \App\Notifications\WelcomeAdvisorNotification($plainPassword));

        return redirect()->back()->with([
            'success' => 'Advisor account provisioned successfully!',
            'flash_email' => $user->email,
            'flash_password' => $plainPassword,
            'flash_company' => $user->company->name
        ]);
    }
}
