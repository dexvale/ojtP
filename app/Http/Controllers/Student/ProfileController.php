<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentProfile;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user()->load('studentProfile', 'company');
        return view('student.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        // Find or instantiate the student profile record safely
        $profile = \App\Models\StudentProfile::firstOrNew(['user_id' => $user->id]);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_address' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'emergency_contact_person' => 'required|string|max:255',
            'emergency_contact_number' => 'required|string|max:20',
        ]);

        // Update credentials on the core users table
        $user->update(['email' => $request->email]);

        // Save personal, family, and contact metadata to the profiles table
        $profile->fill($validated);
        $profile->save();

        return redirect()->back()->with('success', 'Your student profile information has been successfully synchronized and updated!');
    }
}
