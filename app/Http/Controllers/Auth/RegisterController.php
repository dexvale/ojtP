<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string|max:50|unique:users,student_id',
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users',
            'course' => 'required|string|max:255',
            'required_hours' => 'required|integer|min:0',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'student_id' => $request->student_id,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'name' => trim($request->first_name.' '.$request->middle_name.' '.$request->last_name),
            'email' => $request->email,
            'course' => $request->course,
            'required_hours' => $request->required_hours,
            'password' => Hash::make($request->password),
            'role' => 'student',
        ]);

        return redirect()->route('login')->with('success', 'Registration successful. Please login.');
    }
}