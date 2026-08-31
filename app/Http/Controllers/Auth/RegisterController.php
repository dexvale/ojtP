<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\StudentProfile;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $courses = \App\Models\Course::orderBy('course_name', 'asc')->get();
        return view('auth.register', compact('courses'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'student_id' => 'required|string|max:50|unique:student_profiles,student_id_number',
            'course' => 'required|string|max:255',
            'email' => 'required|string|email:rfc,dns|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'Student',
            ]);

            // Determine the hours automatically from the courses table
            $selectedCourse = $request->course;
            $courseRecord = \App\Models\Course::where('course_name', $selectedCourse)->first();
            $automaticallyAssignedHours = $courseRecord ? $courseRecord->required_hours : 0;

            $user->studentProfile()->create([
                'student_id_number' => $request->student_id,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'course' => $selectedCourse,
                'required_hours' => $automaticallyAssignedHours,
            ]);
        });

        return redirect()->route('login')->with('success', 'Registration successful. Please login.');
    }
}