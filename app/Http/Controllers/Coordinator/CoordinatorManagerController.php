<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use Illuminate\Support\Facades\Hash;

class CoordinatorManagerController extends Controller
{
    public function index()
    {
        $coordinators = User::where('role', 'Coordinator')->with('managedCourses')->get();
        $courses = Course::orderBy('course_name', 'asc')->get();

        return view('coordinator.coordinators_manage', compact('coordinators', 'courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'courses' => 'required|array|min:1',
            'courses.*' => 'exists:courses,id'
        ]);

        $coordinator = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'Coordinator'
        ]);

        $coordinator->managedCourses()->sync($request->courses);

        $coordinator->notify(new \App\Notifications\WelcomeCoordinatorNotification($request->password));

        return redirect()->back()->with([
            'success' => 'Coordinator account created and assigned successfully.',
            'flash_email' => $request->email,
            'flash_password' => $request->password,
        ]);
    }

    public function destroy($id)
    {
        $coordinator = User::where('role', 'Coordinator')->findOrFail($id);
        
        // Detach pivot records and delete user
        $coordinator->managedCourses()->detach();
        $coordinator->delete();

        return redirect()->back()->with('success', 'Coordinator account removed successfully.');
    }
}
