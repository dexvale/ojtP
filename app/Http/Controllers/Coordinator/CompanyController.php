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
        $companies = Company::withCount(['studentProfiles as filled_slots'])->get();
        return view('coordinator.companies', compact('companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:255',
            'allocation_slots' => 'required|integer|min:0'
        ]);

        Company::create($validated);

        return redirect()->back()->with('success', 'Company registered successfully.');
    }

    public function storeSupervisor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'company_id' => 'required|exists:companies,id'
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'Supervisor',
            'company_id' => $request->company_id,
        ]);

        return redirect()->back()->with('success', 'Supervisor account provisioned successfully! You can now share these credentials with them.');
    }
}
