<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicTerm;
use Illuminate\Http\Request;

class AcademicTermController extends Controller
{
    public function index()
    {
        $terms = AcademicTerm::withCount(['studentProfiles'])
            ->orderBy('id', 'desc')
            ->get();

        $activeTerm = AcademicTerm::current();

        return view('admin.academic_terms', compact('terms', 'activeTerm'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year' => 'required|string|max:50',
            'semester'      => 'required|in:1st Semester,2nd Semester,Midyear',
            'is_active'     => 'nullable|boolean',
        ]);

        $shouldActivate = $request->boolean('is_active');

        if ($shouldActivate || AcademicTerm::count() === 0) {
            AcademicTerm::query()->update(['is_active' => false]);
            $shouldActivate = true;
        }

        $newTerm = AcademicTerm::create([
            'academic_year' => $validated['academic_year'],
            'semester'      => $validated['semester'],
            'is_active'     => $shouldActivate,
        ]);

        if ($shouldActivate) {
            session(['selected_term_id' => $newTerm->id]);
        }

        return redirect()->route('admin.academic_terms.index')
            ->with('success', "Academic Term ({$validated['academic_year']} - {$validated['semester']}) added successfully!");
    }

    public function activate($id)
    {
        $term = AcademicTerm::findOrFail($id);

        // Deactivate all terms
        AcademicTerm::query()->update(['is_active' => false]);

        // Activate selected term
        $term->update(['is_active' => true]);

        // Keep current session in sync with newly activated term
        session(['selected_term_id' => $term->id]);

        return redirect()->route('admin.academic_terms.index')
            ->with('success', "Active academic term successfully switched to {$term->full_title}!");
    }

    public function destroy($id)
    {
        $term = AcademicTerm::findOrFail($id);

        if ($term->is_active) {
            return redirect()->back()->with('error', 'Cannot delete the currently active academic term. Please activate another term first.');
        }

        if ($term->studentProfiles()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete an academic term that contains enrolled student records.');
        }

        $term->delete();

        return redirect()->route('admin.academic_terms.index')
            ->with('success', 'Academic term removed successfully.');
    }
}
