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
    Route::get('/student/logs/{ojtLog}/edit', [\App\Http\Controllers\Student\OjtLogController::class, 'edit'])->name('student.logs.edit');
    Route::put('/student/logs/{ojtLog}', [\App\Http\Controllers\Student\OjtLogController::class, 'update'])->name('student.logs.update');
    Route::delete('/student/logs/{ojtLog}', [\App\Http\Controllers\Student\OjtLogController::class, 'destroy'])->name('student.logs.destroy');

    // Student Placement routes
    Route::get('/student/placement', [\App\Http\Controllers\Student\PlacementController::class, 'index'])->name('student.placement');
    Route::post('/student/placement/apply', [\App\Http\Controllers\Student\PlacementController::class, 'apply'])->name('student.placement.apply');

    // Student Requirements routes
    Route::get('/student/requirements', [\App\Http\Controllers\Student\StudentRequirementController::class, 'index'])->name('student.requirements');
    Route::get('/student/requirements/{id}/download-template', [\App\Http\Controllers\Student\StudentRequirementController::class, 'downloadTemplate'])->name('student.requirements.downloadTemplate');
    Route::post('/student/requirements/{id}/submit', [\App\Http\Controllers\Student\StudentRequirementController::class, 'submit'])->name('student.requirements.submit');
    Route::get('/student/requirements/{id}/fill', [\App\Http\Controllers\Student\StudentRequirementController::class, 'fill'])->name('student.requirements.fill');
    Route::post('/student/requirements/{id}/submit-form', [\App\Http\Controllers\Student\StudentRequirementController::class, 'submitForm'])->name('student.requirements.submitForm');
    Route::post('/student/requirements/{id}/download-filled', [\App\Http\Controllers\Student\StudentRequirementController::class, 'downloadFilled'])->name('student.requirements.downloadFilled');

    // Student BISU Grading Sheet
    Route::get('/student/grading-sheet', function() {
        $profile = auth()->user()->studentProfile;
        if (!$profile) {
            abort(404, 'Student profile not found.');
        }
        return app(\App\Http\Controllers\Supervisor\EvaluationController::class)->showGradingSheet($profile->id);
    })->name('student.grading-sheet');
});

// Universal Account & Security Settings Routes (Admin, Coordinator, Supervisor)
Route::middleware(['auth', 'no.cache'])->group(function () {
    Route::get('/account/profile', [\App\Http\Controllers\AccountController::class, 'edit'])->name('account.profile');
    Route::put('/account/profile', [\App\Http\Controllers\AccountController::class, 'update'])->name('account.profile.update');
    Route::put('/account/password', [\App\Http\Controllers\AccountController::class, 'updatePassword'])->name('account.password.update');
});

// Coordinator Routes
Route::middleware(['auth', 'no.cache', 'role:Admin,coordinator'])->group(function () {
    Route::get('/coordinator/dashboard', [\App\Http\Controllers\Coordinator\DashboardController::class, 'index'])->name('coordinator.dashboard');

    Route::get('/coordinator/students', [\App\Http\Controllers\Coordinator\DashboardController::class, 'students'])->name('coordinator.students');
    Route::get('/coordinator/students/{id}', [\App\Http\Controllers\Coordinator\DashboardController::class, 'showStudent'])->name('coordinator.students.show');
    Route::post('/coordinator/students/{student}/assign', [\App\Http\Controllers\Coordinator\StudentPlacementController::class, 'assign'])->name('coordinator.students.assign');

    // Coordinator Placement Endorsements
    Route::get('/coordinator/placements', [\App\Http\Controllers\Coordinator\CoordinatorPlacementController::class, 'index'])->name('coordinator.placements');
    Route::post('/coordinator/placements/{student}/approve', [\App\Http\Controllers\Coordinator\CoordinatorPlacementController::class, 'approve'])->name('coordinator.placements.approve');
    Route::post('/coordinator/placements/{student}/reject', [\App\Http\Controllers\Coordinator\CoordinatorPlacementController::class, 'reject'])->name('coordinator.placements.reject');

    Route::get('/coordinator/reports', [\App\Http\Controllers\Coordinator\DashboardController::class, 'reports'])->name('coordinator.reports');
    Route::get('/coordinator/students/{student}/grading-sheet', [\App\Http\Controllers\Supervisor\EvaluationController::class, 'showGradingSheet'])->name('coordinator.students.grading-sheet');
    Route::post('/coordinator/students/{student}/endorse-evaluation', [\App\Http\Controllers\Supervisor\EvaluationController::class, 'endorseEvaluation'])->name('coordinator.students.endorse-evaluation');

    Route::get('/coordinator/companies', [\App\Http\Controllers\Coordinator\CompanyController::class, 'index'])->name('coordinator.companies');
    Route::post('/coordinator/companies', [\App\Http\Controllers\Coordinator\CompanyController::class, 'store'])->name('coordinator.companies.store');
    Route::get('/coordinator/companies/{company}', [\App\Http\Controllers\Coordinator\CompanyController::class, 'show'])->name('coordinator.companies.show');
    Route::put('/coordinator/companies/{company}', [\App\Http\Controllers\Coordinator\CompanyController::class, 'update'])->name('coordinator.companies.update');
    Route::delete('/coordinator/companies/{company}', [\App\Http\Controllers\Coordinator\CompanyController::class, 'destroy'])->name('coordinator.companies.destroy');
    Route::post('/coordinator/supervisors/store', [\App\Http\Controllers\Coordinator\CompanyController::class, 'storeSupervisor'])->name('coordinator.supervisors.store');

    // Coordinator OJT Requirements Management Routes
    Route::get('/coordinator/requirements', [\App\Http\Controllers\Coordinator\CoordinatorRequirementController::class, 'index'])->name('coordinator.requirements');
    Route::post('/coordinator/requirements', [\App\Http\Controllers\Coordinator\CoordinatorRequirementController::class, 'store'])->name('coordinator.requirements.store');
    Route::delete('/coordinator/requirements/{id}', [\App\Http\Controllers\Coordinator\CoordinatorRequirementController::class, 'destroy'])->name('coordinator.requirements.destroy');
    Route::post('/coordinator/submissions/{id}/approve', [\App\Http\Controllers\Coordinator\CoordinatorRequirementController::class, 'approve'])->name('coordinator.submissions.approve');
    Route::post('/coordinator/submissions/{id}/reject', [\App\Http\Controllers\Coordinator\CoordinatorRequirementController::class, 'reject'])->name('coordinator.submissions.reject');
    Route::post('/coordinator/submissions/batch-approve', [\App\Http\Controllers\Coordinator\CoordinatorRequirementController::class, 'batchApprove'])->name('coordinator.submissions.batch-approve');
    Route::post('/coordinator/submissions/batch-download', [\App\Http\Controllers\Coordinator\CoordinatorRequirementController::class, 'batchDownload'])->name('coordinator.submissions.batch-download');

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
    Route::get('/admin', function () {
        return redirect()->route('admin.academic_terms.index');
    });

    Route::get('/coordinator/manage', [\App\Http\Controllers\Coordinator\CoordinatorManagerController::class, 'index'])->name('admin.coordinators');
    Route::post('/coordinator/manage', [\App\Http\Controllers\Coordinator\CoordinatorManagerController::class, 'store'])->name('admin.coordinators.store');
    Route::delete('/coordinator/manage/{id}', [\App\Http\Controllers\Coordinator\CoordinatorManagerController::class, 'destroy'])->name('admin.coordinators.destroy');

    // Academic Terms Management
    Route::get('/admin/academic-terms', [\App\Http\Controllers\Admin\AcademicTermController::class, 'index'])->name('admin.academic_terms.index');
    Route::post('/admin/academic-terms', [\App\Http\Controllers\Admin\AcademicTermController::class, 'store'])->name('admin.academic_terms.store');
    Route::patch('/admin/academic-terms/{id}/activate', [\App\Http\Controllers\Admin\AcademicTermController::class, 'activate'])->name('admin.academic_terms.activate');
    Route::delete('/admin/academic-terms/{id}', [\App\Http\Controllers\Admin\AcademicTermController::class, 'destroy'])->name('admin.academic_terms.destroy');
});

// Supervisor / Advisor Routes
Route::middleware(['auth', 'no.cache', 'role:Advisor'])->group(function () {
    Route::get('/supervisor/dashboard', [\App\Http\Controllers\Supervisor\DashboardController::class, 'index'])->name('supervisor.dashboard');
    Route::get('/supervisor/attendance', [\App\Http\Controllers\Supervisor\DashboardController::class, 'attendance'])->name('supervisor.attendance');
    Route::get('/supervisor/interns/{id}/calendar-data', [\App\Http\Controllers\Supervisor\DashboardController::class, 'getCalendarData'])->name('supervisor.interns.calendar-data');
    Route::get('/supervisor/my-interns', [\App\Http\Controllers\Supervisor\InternsController::class, 'index'])->name('supervisor.interns.index');
    Route::get('/supervisor/approvals', [\App\Http\Controllers\Supervisor\DashboardController::class, 'approvals'])->name('supervisor.approvals');
    Route::get('/supervisor/leaderboard', [\App\Http\Controllers\Supervisor\DashboardController::class, 'viewLeaderboard'])->name('supervisor.leaderboard');
    
    Route::post('/supervisor/logs/batch-approve', [\App\Http\Controllers\Supervisor\DashboardController::class, 'batchApprove'])->name('supervisor.logs.batchApprove');
    Route::post('/supervisor/logs/{log}/approve', [\App\Http\Controllers\Supervisor\DashboardController::class, 'approve'])->name('supervisor.logs.approve');
    Route::post('/supervisor/logs/{log}/reject', [\App\Http\Controllers\Supervisor\DashboardController::class, 'reject'])->name('supervisor.logs.reject');
    Route::post('/supervisor/interns/{student}/evaluate', [\App\Http\Controllers\Supervisor\EvaluationController::class, 'store'])->name('supervisor.interns.evaluate');
    Route::post('/supervisor/evaluate', [\App\Http\Controllers\Supervisor\EvaluationController::class, 'storeFromForm'])->name('supervisor.evaluate');
    Route::get('/supervisor/interns/{student}/grading-sheet', [\App\Http\Controllers\Supervisor\EvaluationController::class, 'showGradingSheet'])->name('supervisor.interns.grading-sheet');
});

// Admin System Setup Route (Run migrations/seeders directly in browser without paid SSH shell)
Route::get('/system-setup/{key}', function ($key) {
    $validKey = config('app.key') ?: 'bisu2026';
    if ($key !== 'bisu2026' && $key !== $validKey) {
        abort(403, 'Unauthorized');
    }

    $action = request('action', 'all');
    $output = "OJT Portal Setup Console\n" . str_repeat("=", 40) . "\n";

    try {
        if ($action === 'migrate' || $action === 'all') {
            \Illuminate\Support\Facades\Artisan::call('migrate --force');
            $output .= "\n[MIGRATIONS]\n" . \Illuminate\Support\Facades\Artisan::output();
        }

        if ($action === 'seed' || $action === 'all') {
            \Illuminate\Support\Facades\Artisan::call('db:seed --force');
            $output .= "\n[SEEDERS]\n" . \Illuminate\Support\Facades\Artisan::output();
        }

        if ($action === 'users') {
            $users = \App\Models\User::all(['id', 'name', 'email', 'role', 'created_at']);
            $output .= "\n[REGISTERED USERS (" . $users->count() . " Total)]\n";
            $output .= sprintf("%-4s | %-24s | %-28s | %-12s\n", "ID", "Name", "Email", "Role");
            $output .= str_repeat("-", 75) . "\n";
            foreach ($users as $u) {
                $output .= sprintf("%-4d | %-24s | %-28s | %-12s\n", $u->id, \Illuminate\Support\Str::limit($u->name ?: 'N/A', 22), \Illuminate\Support\Str::limit($u->email, 26), $u->role ?: 'N/A');
            }
        }

        if ($action === 'clear') {
            \Illuminate\Support\Facades\Artisan::call('optimize:clear');
            $output .= "\n[OPTIMIZE CLEAR]\n" . \Illuminate\Support\Facades\Artisan::output();
        }
    } catch (\Throwable $e) {
        $output .= "\n[ERROR]: " . $e->getMessage();
    }

    return response("<pre style='background:#1e1e2e;color:#a6adc8;padding:24px;font-family:monospace;font-size:14px;border-radius:12px;margin:30px auto;max-width:850px;line-height:1.6;box-shadow:0 10px 25px rgba(0,0,0,0.3);overflow-x:auto;'>" . htmlspecialchars($output) . "</pre>");
});
