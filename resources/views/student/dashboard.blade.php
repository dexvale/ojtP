<!DOCTYPE html>
<html class="light" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Student Internship Dashboard | OJT Portal</title>
<meta name="description" content="Monitor your OJT internship progress, submit daily logs, and manage required documents."/>
<!-- Fonts -->
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Newsreader:opsz,wght@6..72,400;6..72,600;6..72,700;6..72,800&family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<!-- Icons -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<!-- Tailwind -->
@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        vertical-align: middle;
    }
    .progress-ring__circle {
        transition: stroke-dashoffset 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        transform: rotate(-90deg);
        transform-origin: 50% 50%;
    }
    /* Smooth sidebar link transitions */
    aside nav a { transition: all 0.2s ease; }
</style>
</head>
<body class="bg-surface font-body text-on-surface antialiased" data-theme="student">

<!-- ═══════════════════════════════
     TOP HEADER
═══════════════════════════════ -->
<header class="fixed top-0 w-full z-50 bg-[#fff7fd] flex justify-between items-center px-6 lg:px-8 py-4 border-b border-[#cec3d0]/20 backdrop-blur-sm md:pl-64">
    <div class="flex items-center gap-4">
        <!-- Hamburger for mobile only -->
        <button id="sidebar-toggle" class="md:hidden p-2 text-primary rounded-lg hover:bg-surface-container transition-colors" aria-label="Toggle menu">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <!-- Logo & Branding -->
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center overflow-hidden">
                <img alt="University Logo" class="h-8 w-8 object-contain" src="{{ asset('images/logo.png') }}" />
            </div>
            <span class="text-xl font-headline font-semibold text-primary hidden sm:block">OJT Portal</span>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <div class="hidden md:flex bg-surface-container rounded-lg px-4 py-2 items-center gap-2 border border-outline/15">
            <span class="material-symbols-outlined text-outline text-[18px]">search</span>
            <input class="bg-transparent border-none focus:ring-0 text-sm w-44 text-on-surface-variant placeholder:text-outline/60" placeholder="Search resources..." type="text"/>
        </div>
        <button class="relative p-2 text-primary rounded-lg hover:bg-surface-container transition-colors" aria-label="Notifications">
            <span class="material-symbols-outlined">notifications</span>
            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-error rounded-full"></span>
        </button>
        <button class="p-2 text-primary rounded-lg hover:bg-surface-container transition-colors" aria-label="Profile">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 24;">account_circle</span>
        </button>
    </div>
</header>

<!-- ═══════════════════════════════
     SIDEBAR
═══════════════════════════════ -->
    <!-- SideNavBar (Shared Component) -->
    @include('components.student-sidebar')

<!-- Sidebar overlay for mobile -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-30 hidden md:hidden" onclick="closeSidebar()"></div>

<!-- ═══════════════════════════════
     MAIN CONTENT
═══════════════════════════════ -->
<main class="md:ml-64 pt-20 min-h-screen pb-20 md:pb-0">
    <div class="p-5 lg:p-8 max-w-7xl mx-auto space-y-6">

        <!-- ── Page Header ── -->
        <header class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-surface-variant/30 pb-6">
            <div>
                <h1 class="text-3xl lg:text-4xl font-extrabold font-headline tracking-tight text-primary">Student Dashboard</h1>
                <p class="text-on-surface-variant mt-1.5 font-medium text-sm">Academic Year 2023–2024 | Semester 2</p>
            </div>
            <button class="self-start sm:self-auto px-5 py-2.5 bg-secondary text-on-secondary rounded-lg font-bold text-sm flex items-center gap-2 hover:opacity-90 active:scale-95 transition-all shadow-sm">
                <span class="material-symbols-outlined text-lg">download</span>
                Export PDF Report
            </button>
        </header>

        <!-- ── Bento Grid ── -->
        <div class="grid grid-cols-12 gap-5 lg:gap-6">

            <!-- ────────────────────────────────
                 INTERNSHIP PROGRESS  (7 cols)
            ──────────────────────────────── -->
            <section class="col-span-12 lg:col-span-7 bg-surface-container-lowest rounded-xl p-6 lg:p-8 shadow-sm border border-surface-variant/20 flex flex-col sm:flex-row items-center gap-8">
                <!-- Circular Progress Ring -->
                <div class="relative w-44 h-44 flex-shrink-0">
                    <svg class="w-full h-full" viewBox="0 0 100 100">
                        <circle class="stroke-current text-surface-container" cx="50" cy="50" fill="transparent" r="42" stroke-width="8"/>
                        <circle class="stroke-current text-primary progress-ring__circle" cx="50" cy="50" fill="transparent" r="42"
                                stroke-linecap="round" stroke-width="8"
                                style="stroke-dasharray: 263.89; stroke-dashoffset: {{ 263.89 - (263.89 * $completionPercentage / 100) }};"/>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-4xl font-extrabold font-headline text-primary leading-none">{{ $completionPercentage }}%</span>
                        <span class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mt-1">Completed</span>
                    </div>
                </div>

                <!-- Stats -->
                <div class="flex-grow space-y-5 text-center sm:text-left">
                    <div>
                        <h2 class="text-2xl font-bold font-headline text-primary mb-1">Internship Progress</h2>
                        <p class="text-sm text-on-surface-variant">Track your rendered hours against the 400-hour requirement.</p>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <div class="space-y-1">
                            <p class="text-[10px] font-bold text-outline uppercase tracking-wide">Approved</p>
                            <p class="text-2xl font-extrabold font-headline text-on-surface">{{ $approvedHours }}
                                <span class="text-xs font-medium text-on-surface-variant">hrs</span>
                            </p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-bold text-outline uppercase tracking-wide">Lacking</p>
                            <p class="text-2xl font-extrabold font-headline text-error">{{ $lackingHours }}
                                <span class="text-xs font-medium text-error/70">hrs</span>
                            </p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-bold text-outline uppercase tracking-wide">Required</p>
                            <p class="text-2xl font-extrabold font-headline text-on-surface">{{ $requiredHours }}
                                <span class="text-xs font-medium text-on-surface-variant">hrs</span>
                            </p>
                        </div>
                    </div>
                    <div class="bg-primary-container rounded-lg p-4 border-l-4 border-primary">
                        <p class="text-sm font-medium italic text-on-primary-container">"You are on track to finish in 4 weeks at your current pace."</p>
                    </div>
                </div>
            </section>

            <!-- ────────────────────────────────
                 REQUIRED DOCUMENTS  (5 cols)
            ──────────────────────────────── -->
            <section class="col-span-12 lg:col-span-5 bg-surface-container-low rounded-xl p-6 lg:p-8 border border-surface-variant/20">
                <h2 class="text-xl font-bold font-headline text-primary mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-xl">verified_user</span>
                    Required Documents
                </h2>
                <div class="space-y-3">
                    <!-- Medical Certificate -->
                    <div class="bg-surface-container-lowest p-4 rounded-lg flex items-center justify-between border border-surface-variant/10 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-tertiary-container/40 flex items-center justify-center text-tertiary flex-shrink-0">
                                <span class="material-symbols-outlined text-xl">medical_services</span>
                            </div>
                            <div>
                                <p class="font-bold text-sm">Medical Certificate</p>
                                <p class="text-xs text-on-surface-variant">Uploaded on Mar 12, 2024</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-tertiary-container text-on-tertiary-container text-[10px] font-bold rounded-lg uppercase tracking-wide flex-shrink-0">Verified</span>
                    </div>
                    <!-- Parent's Consent -->
                    <div class="bg-surface-container-lowest p-4 rounded-lg flex items-center justify-between border border-surface-variant/10 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-tertiary-container/40 flex items-center justify-center text-tertiary flex-shrink-0">
                                <span class="material-symbols-outlined text-xl">family_history</span>
                            </div>
                            <div>
                                <p class="font-bold text-sm">Parent's Consent</p>
                                <p class="text-xs text-on-surface-variant">Uploaded on Mar 10, 2024</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-tertiary-container text-on-tertiary-container text-[10px] font-bold rounded-lg uppercase tracking-wide flex-shrink-0">Verified</span>
                    </div>
                    <!-- Resume / MOA -->
                    <div class="bg-surface-container-lowest p-4 rounded-lg flex items-center justify-between border border-surface-variant/10 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-primary flex-shrink-0">
                                <span class="material-symbols-outlined text-xl">description</span>
                            </div>
                            <div>
                                <p class="font-bold text-sm">Resume / MOA</p>
                                <p class="text-xs text-on-surface-variant">Optional Submission</p>
                            </div>
                        </div>
                        <div class="flex gap-1">
                            <button class="p-1.5 hover:bg-surface-container rounded-lg text-primary transition-colors" aria-label="Upload file">
                                <span class="material-symbols-outlined text-xl">upload_file</span>
                            </button>
                            <button class="p-1.5 hover:bg-surface-container rounded-lg text-outline transition-colors" aria-label="Preview">
                                <span class="material-symbols-outlined text-xl">visibility</span>
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ────────────────────────────────
                 LOG OJT SHIFT  (8 cols)
            ──────────────────────────────── -->
            <section class="col-span-12 lg:col-span-8 bg-surface-container-lowest rounded-xl shadow-sm border border-surface-variant/20 overflow-hidden">
                <form method="POST" action="{{ route('student.logs.store') }}" id="shift-form">
                    @csrf

                    {{-- ── Card Header ── --}}
                    <div class="flex items-center justify-between px-6 pt-6 pb-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined text-xl">assignment</span>
                            </div>
                            <h2 class="text-2xl font-bold font-headline text-primary">Log OJT Shift</h2>
                        </div>
                        <div class="flex items-center gap-1.5 bg-surface-container px-3 py-1.5 rounded-lg border border-outline/10 text-on-surface-variant text-sm font-medium">
                            <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                            <span id="shift-date">Feb 21, 2026</span>
                        </div>
                    </div>

                    {{-- ── Session Grid ── --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 px-6 pb-4">

                        {{-- Morning Session --}}
                        <div class="bg-surface-container-low rounded-xl p-4 border border-surface-variant/20">
                            <div class="flex items-center gap-1.5 mb-4">
                                <span class="material-symbols-outlined text-secondary text-lg">wb_sunny</span>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Morning Session</p>
                            </div>
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <div>
                                    <label class="block text-[10px] font-semibold text-outline uppercase tracking-wide mb-1.5">Clock In</label>
                                    <div class="relative">
                                        <input type="time" name="morning_in" id="morning_in" value="08:00"
                                               class="w-full bg-surface-container-highest border-none rounded-lg py-2.5 pl-3 pr-9 text-sm font-bold text-on-surface focus:ring-2 focus:ring-primary/40 transition-all appearance-none"/>
                                        <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-[18px] text-outline pointer-events-none">schedule</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-semibold text-outline uppercase tracking-wide mb-1.5">Clock Out</label>
                                    <div class="relative">
                                        <input type="time" name="morning_out" id="morning_out" value="12:00"
                                               class="w-full bg-surface-container-highest border-none rounded-lg py-2.5 pl-3 pr-9 text-sm font-bold text-on-surface focus:ring-2 focus:ring-primary/40 transition-all appearance-none"/>
                                        <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-[18px] text-outline pointer-events-none">schedule</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between pt-3 border-t border-surface-variant/20">
                                <span class="text-xs text-on-surface-variant font-medium">Duration</span>
                                <span id="morning-duration" class="text-sm font-extrabold font-headline text-primary">4.00 Hours</span>
                            </div>
                        </div>

                        {{-- Afternoon Session --}}
                        <div class="bg-surface-container-low rounded-xl p-4 border border-surface-variant/20">
                            <div class="flex items-center gap-1.5 mb-4">
                                <span class="material-symbols-outlined text-secondary text-lg">wb_twilight</span>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Afternoon Session</p>
                            </div>
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <div>
                                    <label class="block text-[10px] font-semibold text-outline uppercase tracking-wide mb-1.5">Clock In</label>
                                    <div class="relative">
                                        <input type="time" name="afternoon_in" id="afternoon_in" value="13:00"
                                               class="w-full bg-surface-container-highest border-none rounded-lg py-2.5 pl-3 pr-9 text-sm font-bold text-on-surface focus:ring-2 focus:ring-primary/40 transition-all appearance-none"/>
                                        <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-[18px] text-outline pointer-events-none">schedule</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-semibold text-outline uppercase tracking-wide mb-1.5">Clock Out</label>
                                    <div class="relative">
                                        <input type="time" name="afternoon_out" id="afternoon_out" value="17:00"
                                               class="w-full bg-surface-container-highest border-none rounded-lg py-2.5 pl-3 pr-9 text-sm font-bold text-on-surface focus:ring-2 focus:ring-primary/40 transition-all appearance-none"/>
                                        <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-[18px] text-outline pointer-events-none">schedule</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between pt-3 border-t border-surface-variant/20">
                                <span class="text-xs text-on-surface-variant font-medium">Duration</span>
                                <span id="afternoon-duration" class="text-sm font-extrabold font-headline text-primary">4.00 Hours</span>
                            </div>
                        </div>
                    </div>

                    {{-- ── Activity Summary & Photo Upload ── --}}
                    <div class="px-6 mb-5">
                        <label class="block text-[10px] font-bold text-outline uppercase tracking-wide mb-2">Daily Activity Summary</label>
                        <textarea name="activity_summary" rows="3" placeholder="What did you work on today? Briefly describe your tasks and accomplishments..." class="w-full bg-surface-container-highest border-none rounded-xl p-4 text-sm font-medium text-on-surface focus:ring-2 focus:ring-primary/40 transition-all resize-none"></textarea>
                        
                        <!-- Photo Upload Zone -->
                        <div class="mt-4 border-2 border-dashed border-outline/40 hover:bg-gray-50 hover:border-outline/60 transition-all rounded-xl p-8 flex flex-col items-center justify-center cursor-pointer group relative">
                            <input type="file" name="workspace_photo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/*" title="Drag & Drop photo here">
                            <div class="w-12 h-12 bg-primary/10 text-primary rounded-full flex items-center justify-center mb-3 group-hover:scale-110 group-hover:bg-primary/20 transition-all duration-300 pointer-events-none">
                                <span class="material-symbols-outlined text-2xl">cloud_upload</span>
                            </div>
                            <p class="text-sm font-bold text-on-surface mb-1 pointer-events-none">Drag & Drop photo here</p>
                            <p class="text-[11px] text-on-surface-variant mb-4 pointer-events-none">Capture your workspace. Max 5MB.</p>
                            <button type="button" class="bg-blue-100 text-blue-700 group-hover:bg-blue-200 transition-colors px-6 py-2 rounded-lg text-sm font-bold pointer-events-none shadow-sm">
                                Browse Files
                            </button>
                        </div>
                    </div>

                    {{-- ── Overtime Toggle ── --}}
                    <div class="mx-6 mb-5 flex items-center justify-between bg-[#faf1f8] rounded-xl px-4 py-3.5 border border-primary/8">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center text-primary flex-shrink-0">
                                <span class="material-symbols-outlined text-lg">more_time</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-on-surface">Add Overtime Hours</p>
                                <p class="text-[11px] text-on-surface-variant mt-0.5">Log hours worked beyond regular shifts</p>
                            </div>
                        </div>
                        {{-- Toggle Switch --}}
                        <label class="relative inline-flex items-center cursor-pointer flex-shrink-0 ml-4">
                            <input type="checkbox" id="overtime-toggle" name="has_overtime" class="sr-only peer"/>
                            <div class="w-11 h-6 bg-surface-container-highest rounded-full peer
                                        peer-checked:bg-primary
                                        after:content-[''] after:absolute after:top-0.5 after:left-0.5
                                        after:bg-white after:rounded-full after:h-5 after:w-5
                                        after:transition-all after:shadow-sm
                                        peer-checked:after:translate-x-5 transition-colors duration-200">
                            </div>
                        </label>
                    </div>

                    {{-- ── Footer ── --}}
                    <div class="flex items-center justify-between px-6 py-4 border-t border-surface-variant/20 bg-surface-container-lowest">
                        <div>
                            <p class="text-xs text-on-surface-variant font-medium mb-0.5">Total Shift Duration:</p>
                            <p id="total-duration" class="text-3xl font-extrabold font-headline text-primary leading-none">8.00 <span class="text-lg font-bold text-on-surface-variant">hours</span></p>
                        </div>
                        <button type="submit"
                                class="flex items-center gap-2 bg-primary text-on-primary px-6 py-3 rounded-xl font-bold text-sm shadow-lg shadow-primary/25 hover:opacity-90 active:scale-95 transition-all">
                            <span class="material-symbols-outlined text-lg" style="font-variation-settings:'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 24;">save</span>
                            Save Shift
                        </button>
                    </div>

                </form>
            </section>


            <!-- ────────────────────────────────
                 STATUS & ADVISOR  (4 cols)
            ──────────────────────────────── -->
            <section class="col-span-12 lg:col-span-4 flex flex-col gap-5">
                <!-- Active Status Card -->
                <div class="bg-primary p-7 rounded-xl text-on-primary shadow-xl shadow-primary/30 relative overflow-hidden">
                    <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="absolute -left-4 -top-4 w-24 h-24 bg-white/[0.03] rounded-full pointer-events-none"></div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-white/60 mb-4">Current Status</p>
                    <div class="flex items-center gap-4 relative z-10">
                        <div class="w-14 h-14 bg-white/10 backdrop-blur-md rounded-xl flex items-center justify-center border border-white/20 flex-shrink-0">
                            <span class="material-symbols-outlined text-3xl" style='font-variation-settings:"FILL" 1,"wght" 400,"GRAD" 0,"opsz" 24;'>verified</span>
                        </div>
                        <div>
                            <p class="text-2xl font-bold font-headline leading-tight">Active Intern</p>
                            <p class="text-xs text-white/70 font-medium mt-0.5">Software Dev Dept.</p>
                        </div>
                    </div>
                </div>

                <!-- Advisor Card -->
                <div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border border-surface-variant/20 flex-1">
                    <h3 class="text-[10px] font-bold uppercase tracking-widest text-outline mb-4">Assigned Advisor</h3>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-surface-container-highest flex items-center justify-center flex-shrink-0 border border-surface-variant/30">
                            <span class="material-symbols-outlined text-2xl text-on-surface-variant" style='font-variation-settings:"FILL" 1,"wght" 400,"GRAD" 0,"opsz" 24;'>person</span>
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-on-surface text-sm">{{ auth()->user()->studentProfile->supervisor->name ?? 'Not Assigned' }}</p>
                            <p class="text-[11px] text-on-surface-variant truncate">{{ auth()->user()->studentProfile->supervisor->email ?? 'Pending assignment' }}</p>
                        </div>
                        <button class="ml-auto p-2 bg-surface-container rounded-lg text-primary hover:bg-primary/10 transition-colors flex-shrink-0" aria-label="Send email">
                            <span class="material-symbols-outlined text-xl">mail</span>
                        </button>
                    </div>
                </div>
            </section>

            <!-- ────────────────────────────────
                 RECENT SUBMISSIONS TABLE (full)
            ──────────────────────────────── -->
            <section class="col-span-12 bg-surface-container-lowest rounded-xl p-6 lg:p-8 shadow-sm border border-surface-variant/20">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold font-headline text-primary">Recent Daily Submissions</h2>
                    <a href="{{ route('student.logs') }}" class="text-sm font-bold text-secondary flex items-center gap-1 hover:underline underline-offset-4 transition-all">
                        View All History
                        <span class="material-symbols-outlined text-base">arrow_forward</span>
                    </a>
                </div>
                <div class="overflow-x-auto -mx-2 px-2">
                    <table class="w-full text-left border-collapse min-w-[560px]">
                        <thead>
                            <tr class="text-[10px] font-bold text-outline uppercase tracking-wider border-b border-surface-variant/20">
                                <th class="pb-4 pl-4 font-bold">Date</th>
                                <th class="pb-4 font-bold">Activity Summary</th>
                                <th class="pb-4 font-bold">Hours</th>
                                <th class="pb-4 font-bold">Status</th>
                                <th class="pb-4 text-right pr-4 font-bold">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-variant/10">
                            @forelse($recentLogs as $log)
                            <tr class="hover:bg-surface-container-low transition-colors group">
                                <td class="py-4 pl-4 font-bold text-sm whitespace-nowrap">{{ $log->log_date->format('M d, Y') }}</td>
                                <td class="py-4 text-sm text-on-surface-variant max-w-xs lg:max-w-md">
                                    <span class="line-clamp-1">{{ Str::limit($log->tasks_performed, 70) }}</span>
                                </td>
                                <td class="py-4 text-sm font-bold text-on-surface whitespace-nowrap">{{ number_format($log->hours_rendered, 1) }} hrs</td>
                                <td class="py-4">
                                    @if($log->status === 'Approved')
                                    <span class="px-2.5 py-1 bg-tertiary-container text-on-tertiary-container text-[10px] font-bold rounded-lg uppercase tracking-wide">Approved</span>
                                    @elseif($log->status === 'Rejected')
                                    <span class="px-2.5 py-1 bg-error/10 text-error text-[10px] font-bold rounded-lg uppercase tracking-wide">Rejected</span>
                                    @else
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                        Pending
                                    </span>
                                    @endif
                                </td>
                                <td class="py-4 text-right pr-4">
                                    <button class="text-secondary text-xs font-bold hover:underline underline-offset-4">View Entry</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-sm text-on-surface-variant font-medium">
                                    No recent daily submissions found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

        </div><!-- /Bento Grid -->
    </div><!-- /Container -->
</main>

<!-- ═══════════════════════════════
     MOBILE BOTTOM NAV
═══════════════════════════════ -->
<nav class="md:hidden fixed bottom-0 w-full bg-white/90 backdrop-blur-lg border-t border-surface-variant/20 flex justify-around items-center py-2.5 z-50">
    <button class="flex flex-col items-center gap-0.5 text-primary px-3 py-1">
        <span class="material-symbols-outlined text-xl" style='font-variation-settings:"FILL" 1,"wght" 400,"GRAD" 0,"opsz" 24;'>dashboard</span>
        <span class="text-[10px] font-bold">Home</span>
    </button>
    <a href="{{ route('student.logs') }}" class="flex flex-col items-center gap-0.5 text-outline px-3 py-1">
        <span class="material-symbols-outlined text-xl">description</span>
        <span class="text-[10px] font-bold">Logs</span>
    </a>
    <button class="flex flex-col items-center gap-0.5 text-outline px-3 py-1">
        <span class="material-symbols-outlined text-xl">add_circle</span>
        <span class="text-[10px] font-bold">New</span>
    </button>
    <button class="flex flex-col items-center gap-0.5 text-outline px-3 py-1">
        <span class="material-symbols-outlined text-xl">business</span>
        <span class="text-[10px] font-bold">Hub</span>
    </button>
    <button class="flex flex-col items-center gap-0.5 text-outline px-3 py-1">
        <span class="material-symbols-outlined text-xl">person</span>
        <span class="text-[10px] font-bold">Profile</span>
    </button>
</nav>

<script>
    // ── Live shift date ──
    const shiftDateEl = document.getElementById('shift-date');
    if (shiftDateEl) {
        shiftDateEl.textContent = new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    }

    // ── Duration calculator ──
    function parseTime(val) {
        if (!val) return null;
        const [h, m] = val.split(':').map(Number);
        return h * 60 + m;
    }

    function calcHours(inId, outId) {
        const inVal  = parseTime(document.getElementById(inId)?.value);
        const outVal = parseTime(document.getElementById(outId)?.value);
        if (inVal === null || outVal === null) return 0;
        const diff = outVal - inVal;
        return diff > 0 ? diff / 60 : 0;
    }

    function updateDurations() {
        const morning   = calcHours('morning_in',   'morning_out');
        const afternoon = calcHours('afternoon_in', 'afternoon_out');
        const total     = morning + afternoon;

        document.getElementById('morning-duration').textContent   = morning.toFixed(2)   + ' Hours';
        document.getElementById('afternoon-duration').textContent = afternoon.toFixed(2) + ' Hours';
        document.getElementById('total-duration').innerHTML =
            total.toFixed(2) + ' <span class="text-lg font-bold text-on-surface-variant">hours</span>';
    }

    ['morning_in','morning_out','afternoon_in','afternoon_out'].forEach(id => {
        document.getElementById(id)?.addEventListener('change', updateDurations);
    });

    // Run once on load to reflect default values
    updateDurations();

    // ── Mobile sidebar toggle ──
    const sidebar   = document.getElementById('sidebar');
    const overlay   = document.getElementById('sidebar-overlay');
    const toggleBtn = document.getElementById('sidebar-toggle');

    function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
    toggleBtn?.addEventListener('click', () => {
        sidebar.classList.contains('-translate-x-full') ? openSidebar() : closeSidebar();
    });
</script>
</body>
</html>
