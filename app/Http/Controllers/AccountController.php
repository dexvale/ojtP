<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    /**
     * Display the user account profile and security settings.
     */
    public function edit()
    {
        $user = auth()->user();

        if ($user->role === 'Coordinator') {
            $user->load('managedCourses');
        } elseif ($user->role === 'Advisor') {
            $user->load('company');
        }

        return view('account.profile', compact('user'));
    }

    /**
     * Update the user's basic profile details and avatar.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:30'],
            'department' => ['nullable', 'string', 'max:255'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ]);

        // Handle avatar photo removal
        if ($request->boolean('remove_photo')) {
            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $user->profile_photo_path = null;
        }

        // Handle avatar photo upload
        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo_path = $path;
        }

        $user->name = $validated['name'];
        $user->contact_number = $validated['contact_number'] ?? null;
        $user->department = $validated['department'] ?? null;
        $user->save();

        return back()->with('profile_success', 'Account profile details updated successfully!');
    }

    /**
     * Update the user's account password.
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
        ], [
            'current_password.current_password' => 'The current password provided is incorrect.',
            'password.min' => 'Your new password must be at least 8 characters long.',
            'password.confirmed' => 'The new password confirmation does not match.',
            'password.different' => 'Your new password must be different from your current password.',
        ]);

        $user->password = Hash::make($validated['password']);
        $user->save();

        if ($user->role === 'Student') {
            return redirect()->route('student.profile', ['#security'])
                ->with('password_success', 'Your password has been changed successfully!');
        }

        return redirect()->route('account.profile', ['#security'])
            ->with('password_success', 'Your password has been changed successfully!');
    }
}
