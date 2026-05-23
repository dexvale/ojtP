<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Course;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::orderBy('course_name', 'asc')->get();
        return view('coordinator.courses', compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_name' => 'required|string|max:255|unique:courses,course_name',
            'required_hours' => 'required|integer|min:0',
        ]);

        Course::create([
            'course_name' => $request->course_name,
            'required_hours' => $request->required_hours,
        ]);

        return redirect()->route('courses.index')->with('success', 'Course added successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $course = Course::findOrFail($id);
        
        $request->validate([
            'course_name' => 'required|string|max:255|unique:courses,course_name,' . $course->id,
            'required_hours' => 'required|integer|min:0',
        ]);

        $course->update([
            'course_name' => $request->course_name,
            'required_hours' => $request->required_hours,
        ]);

        return redirect()->route('courses.index')->with('success', 'Course updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Course deleted successfully.');
    }
}
