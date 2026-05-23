<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Register Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Student Routes
Route::middleware(['auth', 'no.cache'])->group(function () {
    Route::get('/student/dashboard', [StudentDashboard::class, 'index'])->name('student.dashboard');
    Route::get('/student/profile', [StudentDashboard::class, 'profile'])->name('student.profile');
    Route::get('/student/logs', [StudentDashboard::class, 'logs'])->name('student.logs');
});

// Coordinator Routes
Route::middleware(['auth', 'no.cache', 'role:Admin,coordinator'])->group(function () {
    Route::get('/coordinator/dashboard', [\App\Http\Controllers\Coordinator\DashboardController::class, 'index'])->name('coordinator.dashboard');

    Route::get('/coordinator/students', [\App\Http\Controllers\Coordinator\DashboardController::class, 'students'])->name('coordinator.students');

    Route::get('/coordinator/reports', function () {
        return view('coordinator.reports');
    })->name('coordinator.reports');

    Route::get('/coordinator/companies', function () {
        return view('coordinator.companies');
    })->name('coordinator.companies');

    Route::resource('coordinator/courses', \App\Http\Controllers\Coordinator\CourseController::class)
        ->names([
            'index' => 'courses.index',
            'store' => 'courses.store',
            'update' => 'courses.update',
            'destroy' => 'courses.destroy',
        ])
        ->except(['create', 'show', 'edit']);
});

// Supervisor / Advisor Routes
Route::middleware(['auth', 'no.cache', 'role:Advisor'])->group(function () {
    Route::get('/supervisor/dashboard', function () {
        return view('supervisor.dashboard');
    })->name('supervisor.dashboard');

    Route::get('/supervisor/attendance', function () {
        return view('supervisor.attendance');
    })->name('supervisor.attendance');

    Route::get('/supervisor/approvals', function () {
        return view('supervisor.approvals');
    })->name('supervisor.approvals');
});
