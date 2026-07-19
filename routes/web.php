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
    Route::post('/student/logs/store', [\App\Http\Controllers\Student\OjtLogController::class, 'store'])->name('student.logs.store');
    Route::get('/student/profile', [\App\Http\Controllers\Student\ProfileController::class, 'edit'])->name('student.profile');
    Route::put('/student/profile', [\App\Http\Controllers\Student\ProfileController::class, 'update'])->name('student.profile.update');
    
    Route::get('/student/logs', [\App\Http\Controllers\Student\OjtLogController::class, 'index'])->name('student.logs.index');
    Route::delete('/student/logs/{ojtLog}', [\App\Http\Controllers\Student\OjtLogController::class, 'destroy'])->name('student.logs.destroy');
});

// Coordinator Routes
Route::middleware(['auth', 'no.cache', 'role:Admin,coordinator'])->group(function () {
    Route::get('/coordinator/dashboard', [\App\Http\Controllers\Coordinator\DashboardController::class, 'index'])->name('coordinator.dashboard');

    Route::get('/coordinator/students', [\App\Http\Controllers\Coordinator\DashboardController::class, 'students'])->name('coordinator.students');
    Route::get('/coordinator/students/{id}', [\App\Http\Controllers\Coordinator\DashboardController::class, 'showStudent'])->name('coordinator.students.show');
    Route::post('/coordinator/students/{student}/assign', [\App\Http\Controllers\Coordinator\StudentPlacementController::class, 'assign'])->name('coordinator.students.assign');

    Route::get('/coordinator/reports', function () {
        return view('coordinator.reports');
    })->name('coordinator.reports');

    Route::get('/coordinator/companies', [\App\Http\Controllers\Coordinator\CompanyController::class, 'index'])->name('coordinator.companies');
    Route::post('/coordinator/companies', [\App\Http\Controllers\Coordinator\CompanyController::class, 'store'])->name('coordinator.companies.store');
    Route::get('/coordinator/companies/{company}', [\App\Http\Controllers\Coordinator\CompanyController::class, 'show'])->name('coordinator.companies.show');
    Route::put('/coordinator/companies/{company}', [\App\Http\Controllers\Coordinator\CompanyController::class, 'update'])->name('coordinator.companies.update');
    Route::delete('/coordinator/companies/{company}', [\App\Http\Controllers\Coordinator\CompanyController::class, 'destroy'])->name('coordinator.companies.destroy');
    Route::post('/coordinator/supervisors/store', [\App\Http\Controllers\Coordinator\CompanyController::class, 'storeSupervisor'])->name('coordinator.supervisors.store');

    Route::resource('coordinator/courses', \App\Http\Controllers\Coordinator\CourseController::class)
        ->names([
            'index' => 'courses.index',
            'store' => 'courses.store',
            'update' => 'courses.update',
            'destroy' => 'courses.destroy',
        ])
        ->except(['create', 'show', 'edit']);
});

// Admin (Super Admin / Dean) Only Routes
Route::middleware(['auth', 'no.cache', 'role:Admin'])->group(function () {
    Route::get('/coordinator/manage', [\App\Http\Controllers\Coordinator\CoordinatorManagerController::class, 'index'])->name('admin.coordinators');
    Route::post('/coordinator/manage', [\App\Http\Controllers\Coordinator\CoordinatorManagerController::class, 'store'])->name('admin.coordinators.store');
    Route::delete('/coordinator/manage/{id}', [\App\Http\Controllers\Coordinator\CoordinatorManagerController::class, 'destroy'])->name('admin.coordinators.destroy');
});

// Supervisor / Advisor Routes
Route::middleware(['auth', 'no.cache', 'role:Advisor'])->group(function () {
    Route::get('/supervisor/dashboard', [\App\Http\Controllers\Supervisor\DashboardController::class, 'index'])->name('supervisor.dashboard');
    Route::get('/supervisor/attendance', [\App\Http\Controllers\Supervisor\DashboardController::class, 'attendance'])->name('supervisor.attendance');
    Route::get('/supervisor/interns/{id}/calendar-data', [\App\Http\Controllers\Supervisor\DashboardController::class, 'getCalendarData'])->name('supervisor.interns.calendar-data');
    Route::get('/supervisor/my-interns', [\App\Http\Controllers\Supervisor\InternsController::class, 'index'])->name('supervisor.interns.index');
    Route::get('/supervisor/approvals', [\App\Http\Controllers\Supervisor\DashboardController::class, 'approvals'])->name('supervisor.approvals');
    Route::get('/supervisor/leaderboard', [\App\Http\Controllers\Supervisor\DashboardController::class, 'viewLeaderboard'])->name('supervisor.leaderboard');
    
    Route::post('/supervisor/logs/{log}/approve', [\App\Http\Controllers\Supervisor\DashboardController::class, 'approve'])->name('supervisor.logs.approve');
    Route::post('/supervisor/logs/{log}/reject', [\App\Http\Controllers\Supervisor\DashboardController::class, 'reject'])->name('supervisor.logs.reject');
});
