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

    /* Position native time picker indicator to the right */
    input[type="time"]::-webkit-calendar-picker-indicator {
        background: transparent;
        color: transparent;
        cursor: pointer;
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 24px;
        height: 24px;
        z-index: 10;
    }
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
                        <p class="text-sm text-on-surface-variant">Track your rendered hours against the {{ $requiredHours }}-hour requirement.</p>
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
                <form method="POST" action="{{ route('student.logs.store') }}" id="shift-form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="hours_rendered" id="hours_rendered_input" value="0.00">

                    @if($errors->any())
                        <div class="bg-error/10 text-error p-4 text-sm border-b border-error/20">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- ── Card Header ── --}}
                    <div class="flex items-center justify-between px-6 pt-6 pb-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined text-xl">assignment</span>
                            </div>
                            <h2 class="text-2xl font-bold font-headline text-primary">Log OJT Shift</h2>
                        </div>
                        <div class="flex items-center gap-2 bg-purple-50 border border-purple-100 rounded-lg px-3 py-1.5">
                            <span class="material-symbols-outlined text-purple-700 text-sm">calendar_month</span>
                            <input type="date" 
                                   name="log_date" 
                                   id="log_date" 
                                   value="{{ old('log_date', date('Y-m-d')) }}" 
                                   max="{{ date('Y-m-d') }}" 
                                   class="bg-transparent text-sm font-medium text-purple-900 border-none focus:ring-0 p-0 cursor-pointer">
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
                                        <input type="time" name="am_clock_in" id="am_clock_in" value="{{ old('am_clock_in') }}"
                                               class="w-full bg-surface-container-highest border-none rounded-lg py-2.5 pl-3 pr-9 text-sm font-bold text-on-surface focus:ring-2 focus:ring-primary/40 transition-all appearance-none time-input"/>
                                        <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-[18px] text-outline pointer-events-none">schedule</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-semibold text-outline uppercase tracking-wide mb-1.5">Clock Out</label>
                                    <div class="relative">
                                        <input type="time" name="am_clock_out" id="am_clock_out" value="{{ old('am_clock_out') }}"
                                               class="w-full bg-surface-container-highest border-none rounded-lg py-2.5 pl-3 pr-9 text-sm font-bold text-on-surface focus:ring-2 focus:ring-primary/40 transition-all appearance-none time-input"/>
                                        <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-[18px] text-outline pointer-events-none">schedule</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between pt-3 border-t border-surface-variant/20">
                                <span class="text-xs text-on-surface-variant font-medium">Duration</span>
                                <span id="morning-duration" class="text-sm font-extrabold font-headline text-primary">0 hrs 0 mins</span>
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
                                        <input type="time" name="pm_clock_in" id="pm_clock_in" value="{{ old('pm_clock_in') }}"
                                               class="w-full bg-surface-container-highest border-none rounded-lg py-2.5 pl-3 pr-9 text-sm font-bold text-on-surface focus:ring-2 focus:ring-primary/40 transition-all appearance-none time-input"/>
                                        <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-[18px] text-outline pointer-events-none">schedule</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-semibold text-outline uppercase tracking-wide mb-1.5">Clock Out</label>
                                    <div class="relative">
                                        <input type="time" name="pm_clock_out" id="pm_clock_out" value="{{ old('pm_clock_out') }}"
                                               class="w-full bg-surface-container-highest border-none rounded-lg py-2.5 pl-3 pr-9 text-sm font-bold text-on-surface focus:ring-2 focus:ring-primary/40 transition-all appearance-none time-input"/>
                                        <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-[18px] text-outline pointer-events-none">schedule</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between pt-3 border-t border-surface-variant/20">
                                <span class="text-xs text-on-surface-variant font-medium">Duration</span>
                                <span id="afternoon-duration" class="text-sm font-extrabold font-headline text-primary">0 hrs 0 mins</span>
                            </div>
                        </div>
                    </div>

                    {{-- ── Activity Summary & Photo Upload ── --}}
                    <div class="px-6 mb-5">
                        <label class="block text-[10px] font-bold text-outline uppercase tracking-wide mb-2">Daily Activity Summary</label>
                        <textarea name="activity_summary" rows="3" placeholder="What did you work on today? Briefly describe your tasks and accomplishments..." class="w-full bg-surface-container-highest border-none rounded-xl p-4 text-sm font-medium text-on-surface focus:ring-2 focus:ring-primary/40 transition-all resize-none">{{ old('activity_summary') }}</textarea>
                        
                        <!-- Photo Upload Zone -->
                        <div id="photo_drop_zone" class="mt-4 border-2 border-dashed border-outline/40 hover:bg-gray-50 hover:border-outline/60 transition-all rounded-xl p-8 flex flex-col items-center justify-center cursor-pointer group relative">
                            <input type="file" id="photo_attachment" name="photo_attachment" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/png, image/jpeg, image/jpg" title="Drag & Drop photo here">
                            <div id="photo_placeholder" class="flex flex-col items-center pointer-events-none w-full">
                                <div class="w-12 h-12 bg-primary/10 text-primary rounded-full flex items-center justify-center mb-3 group-hover:scale-110 group-hover:bg-primary/20 transition-all duration-300">
                                    <span class="material-symbols-outlined text-2xl">cloud_upload</span>
                                </div>
                                <p class="text-sm font-bold text-on-surface mb-1">Drag & Drop photo here</p>
                                <p class="text-[11px] text-on-surface-variant mb-4">Capture your workspace. Max 5MB (JPG/PNG).</p>
                                <button type="button" class="bg-blue-100 text-blue-700 group-hover:bg-blue-200 transition-colors px-6 py-2 rounded-lg text-sm font-bold shadow-sm">
                                    Browse Files
                                </button>
                            </div>
                            <div id="photo_preview_container" class="hidden w-full relative group">
                                <img id="photo_preview" src="#" alt="Preview" class="max-h-48 object-contain rounded-lg mx-auto shadow-sm">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center z-20">
                                    <button type="button" id="remove_photo_btn" class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-md hover:bg-red-600 transition-colors flex items-center gap-2 relative z-30">
                                        <span class="material-symbols-outlined text-sm">delete</span> Remove Photo
                                    </button>
                                </div>
                            </div>
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
                            <input type="checkbox" id="overtime_toggle" name="has_overtime" {{ old('has_overtime') ? 'checked' : '' }} class="hidden peer"/>
                            <div class="w-11 h-6 bg-surface-container-highest rounded-full peer
                                        peer-checked:bg-primary
                                        after:content-[''] after:absolute after:top-0.5 after:left-0.5
                                        after:bg-white after:rounded-full after:h-5 after:w-5
                                        after:transition-all after:shadow-sm
                                        peer-checked:after:translate-x-5 transition-colors duration-200">
                            </div>
                        </label>
                    </div>

                    {{-- ── Overtime Container ── --}}
                    <div id="overtime_inputs_container" class="hidden transition-all duration-300 bg-purple-50/50 border border-purple-100 rounded-xl p-4 mt-3 mx-6 mb-5">
                        <h4 class="text-sm font-semibold text-purple-900 mb-3 flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm text-purple-700">more_time</span> Overtime Session
                        </h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Clock In</label>
                                <div class="relative w-full">
                                    <input type="time" name="ot_clock_in" id="ot_clock_in" value="{{ old('ot_clock_in') }}" class="w-full bg-white border border-gray-200 rounded-lg p-2.5 text-sm focus:ring-purple-500 focus:border-purple-500 transition-all">
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-purple-400 pointer-events-none text-base">schedule</span>
                                </div>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Clock Out</label>
                                <div class="relative w-full">
                                    <input type="time" name="ot_clock_out" id="ot_clock_out" value="{{ old('ot_clock_out') }}" class="w-full bg-white border border-gray-200 rounded-lg p-2.5 text-sm focus:ring-purple-500 focus:border-purple-500 transition-all">
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-purple-400 pointer-events-none text-base">schedule</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-right mt-2 text-xs font-semibold text-purple-900">
                            Duration: <span id="ot_duration_display">0 hrs 0 mins</span>
                        </div>
                    </div>

                    {{-- ── Footer ── --}}
                    <div class="flex items-center justify-between px-6 py-4 border-t border-surface-variant/20 bg-surface-container-lowest">
                        <div>
                            <p class="text-xs text-on-surface-variant font-medium mb-0.5">Total Shift Duration:</p>
                            <p id="total-duration" class="text-3xl font-extrabold font-headline text-primary leading-none">0 <span class="text-lg font-bold text-on-surface-variant">hrs</span> 0 <span class="text-lg font-bold text-on-surface-variant">mins</span></p>
                        </div>
                        <button type="submit" id="save-shift-btn"
                                class="flex items-center gap-2 bg-primary text-on-primary px-6 py-3 rounded-xl font-bold text-sm shadow-lg shadow-primary/25 hover:opacity-90 active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
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
                        @if(auth()->user()->company && auth()->user()->company->users->where('role', 'Advisor')->first())
                            @php $advisor = auth()->user()->company->users->where('role', 'Advisor')->first(); @endphp
                            <div class="min-w-0">
                                <p class="font-bold text-on-surface text-sm">{{ $advisor->name }}</p>
                                <p class="text-[11px] text-on-surface-variant truncate">{{ $advisor->email }}</p>
                            </div>
                            <a href="mailto:{{ $advisor->email }}" class="ml-auto p-2 bg-surface-container rounded-lg text-primary hover:bg-primary/10 transition-colors flex-shrink-0" aria-label="Send email">
                                <span class="material-symbols-outlined text-xl">mail</span>
                            </a>
                        @else
                            <div class="min-w-0">
                                <p class="font-bold text-on-surface text-sm">Not Assigned</p>
                                <p class="text-[11px] text-on-surface-variant truncate">Pending assignment</p>
                            </div>
                            <button class="ml-auto p-2 bg-surface-container rounded-lg text-primary hover:bg-primary/10 transition-colors flex-shrink-0" aria-label="Send email" disabled>
                                <span class="material-symbols-outlined text-xl">mail</span>
                            </button>
                        @endif
                    </div>
                </div>
            </section>

            <!-- ────────────────────────────────
                 RECENT SUBMISSIONS TABLE (full)
            ──────────────────────────────── -->
            <section class="col-span-12 bg-surface-container-lowest rounded-xl p-6 lg:p-8 shadow-sm border border-surface-variant/20">
                <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                    <h3 class="text-lg font-bold text-purple-900">Recent Daily Submissions</h3>
                    <a href="{{ route('student.logs.index') }}" class="group flex items-center gap-1.5 text-xs font-bold text-amber-700 hover:text-amber-900 transition-colors duration-200">
                        View All History
                        <span class="material-symbols-outlined text-sm transform group-hover:translate-x-1 transition-transform duration-200">arrow_forward</span>
                    </a>
                </div>
                <div class="overflow-x-auto -mx-2 px-2">
                    <table class="w-full text-left border-collapse min-w-[560px]">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="text-[10px] font-bold text-gray-400 uppercase tracking-wider py-3 px-4 text-left">Date</th>
                                <th class="text-[10px] font-bold text-gray-400 uppercase tracking-wider py-3 px-4 text-left">Activity Summary</th>
                                <th class="text-[10px] font-bold text-gray-400 uppercase tracking-wider py-3 px-4 text-left">Hours</th>
                                <th class="text-[10px] font-bold text-gray-400 uppercase tracking-wider py-3 px-4 text-left">Status</th>
                                <th class="text-[10px] font-bold text-gray-400 uppercase tracking-wider py-3 px-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLogs as $log)
                            <tr class="hover:bg-purple-50/30 transition-colors duration-150 border-b border-gray-50 last:border-0 group">
                                <td class="py-4 px-4 font-bold text-sm whitespace-nowrap">{{ $log->log_date->format('M d, Y') }}</td>
                                <td class="py-4 px-4 text-sm text-on-surface-variant max-w-xs lg:max-w-md">
                                    <span class="line-clamp-1">{{ Str::limit($log->tasks_performed, 70) }}</span>
                                </td>
                                <td class="py-4 px-4 text-sm font-bold text-on-surface whitespace-nowrap">{{ number_format($log->hours_rendered, 2) }} hrs</td>
                                <td class="py-4 px-4">
                                    @if($log->status === 'Pending')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100">Pending</span>
                                    @elseif($log->status === 'Approved')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">Approved</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-100">Rejected</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-right">
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
    <a href="{{ route('student.logs.index') }}" class="flex flex-col items-center gap-0.5 text-outline px-3 py-1">
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

    // ── Duration calculator & Strict Validation ──
    const saveBtn = document.getElementById('save-shift-btn');
    const form = document.getElementById('shift-form');

    function timeToMinutes(timeString) {
        if (!timeString) return null;
        const [hours, minutes] = timeString.split(':').map(Number);
        return (hours * 60) + minutes;
    }

    function calculateSessionDuration(timeInId, timeOutId, displayId) {
        const timeInEl = document.getElementById(timeInId);
        const timeOutEl = document.getElementById(timeOutId);

        if (!timeInEl || !timeOutEl || !timeInEl.value || !timeOutEl.value) {
            const displayEl = document.getElementById(displayId);
            if (displayEl) displayEl.innerText = '0 hrs 0 mins';
            return 0;
        }

        const minutesIn = timeToMinutes(timeInEl.value);
        const minutesOut = timeToMinutes(timeOutEl.value);

        // Handle cases where checkout happens after midnight if applicable
        let diffMinutes = minutesOut - minutesIn;
        if (diffMinutes < 0) diffMinutes += 24 * 60; 

        // Calculate discrete hours and remaining minutes
        const hrs = Math.floor(diffMinutes / 60);
        const mins = diffMinutes % 60;
        
        // Display cleanly: e.g., "4 hrs 1 min"
        const displayEl = document.getElementById(displayId);
        if (displayEl) displayEl.innerText = `${hrs} hrs ${mins} min${mins !== 1 ? 's' : ''}`;
        
        // Remove error states since we handle midnight crossing
        timeOutEl.classList.remove('ring-2', 'ring-error', 'text-error');
        
        return diffMinutes;
    }

    function updateDurations() {
        const morningMins = calculateSessionDuration('am_clock_in', 'am_clock_out', 'morning-duration');
        const afternoonMins = calculateSessionDuration('pm_clock_in', 'pm_clock_out', 'afternoon-duration');
        
        let otMins = 0;
        const otToggle = document.getElementById('overtime_toggle');
        
        if (otToggle && otToggle.checked) {
            otMins = calculateSessionDuration('ot_clock_in', 'ot_clock_out', 'ot_duration_display');
        } else {
            const otDisplay = document.getElementById('ot_duration_display');
            if(otDisplay) otDisplay.innerText = '0 hrs 0 mins';
        }

        const totalMinutes = morningMins + afternoonMins + otMins;
        
        const totalHrs = Math.floor(totalMinutes / 60);
        const totalMins = totalMinutes % 60;

        const totalDurationEl = document.getElementById('total-duration');
        if (totalDurationEl) {
            totalDurationEl.innerHTML = `${totalHrs} <span class="text-lg font-bold text-on-surface-variant">hrs</span> ${totalMins} <span class="text-lg font-bold text-on-surface-variant">min${totalMins !== 1 ? 's' : ''}</span>`;
        }
        
        const paddedMinutesString = totalMins < 10 ? '0' + totalMins : totalMins;
        const humanDecimalValue = `${totalHrs}.${paddedMinutesString}`;
        
        const hiddenInput = document.getElementById('hours_rendered_input');
        if (hiddenInput) {
            hiddenInput.value = parseFloat(humanDecimalValue).toFixed(2);
        }

        if (saveBtn) {
            saveBtn.disabled = totalMinutes === 0;
        }
    }

    ['am_clock_in','am_clock_out','pm_clock_in','pm_clock_out','ot_clock_in','ot_clock_out'].forEach(id => {
        document.getElementById(id)?.addEventListener('change', updateDurations);
    });

    if (form) {
        form.addEventListener('submit', function(e) {
            updateDurations(); // final check
            if (saveBtn && saveBtn.disabled) {
                e.preventDefault();
                alert('Invalid time sequence detected. Please ensure your Clock Out time is logically after your Clock In time, and that you have valid hours rendered.');
            }
        });
    }

    // Run once on load to reflect default values
    document.addEventListener("DOMContentLoaded", function() {
        updateDurations(); 
        
        const otToggle = document.getElementById('overtime_toggle');
        const otContainer = document.getElementById('overtime_inputs_container');
        if (otToggle && otToggle.checked) {
            otContainer.classList.remove('hidden');
        }
    });

    // ── Overtime Toggle Logic ──
    const otToggle = document.getElementById('overtime_toggle');
    const otContainer = document.getElementById('overtime_inputs_container');
    const otClockIn = document.getElementById('ot_clock_in');
    const otClockOut = document.getElementById('ot_clock_out');

    if (otToggle) {
        otToggle.addEventListener('change', function() {
            if (this.checked) {
                otContainer.classList.remove('hidden');
            } else {
                otContainer.classList.add('hidden');
                if(otClockIn) otClockIn.value = '';
                if(otClockOut) otClockOut.value = '';
                if(otClockIn) otClockIn.classList.remove('ring-2', 'ring-error', 'text-error');
                if(otClockOut) otClockOut.classList.remove('ring-2', 'ring-error', 'text-error');
            }
            updateDurations();
        });
    }

    // ── Photo Upload Logic ──
    const photoDropZone = document.getElementById('photo_drop_zone');
    const photoInput = document.getElementById('photo_attachment');
    const photoPlaceholder = document.getElementById('photo_placeholder');
    const photoPreviewContainer = document.getElementById('photo_preview_container');
    const photoPreview = document.getElementById('photo_preview');
    const removePhotoBtn = document.getElementById('remove_photo_btn');

    if (photoDropZone && photoInput) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            photoDropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            photoDropZone.addEventListener(eventName, () => {
                photoDropZone.classList.add('border-primary', 'bg-purple-50');
                photoDropZone.classList.remove('border-outline/40');
            }, false);
        });

        photoDropZone.addEventListener('dragleave', () => {
            photoDropZone.classList.remove('border-primary', 'bg-purple-50');
            photoDropZone.classList.add('border-outline/40');
        }, false);

        photoDropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            photoDropZone.classList.remove('border-primary', 'bg-purple-50'); // Remove active hover styles
            photoDropZone.classList.add('border-outline/40');

            // 1. Grab the dropped files safely
            const droppedFiles = e.dataTransfer.files;
            const fileInput = document.getElementById('photo_attachment');

            if (droppedFiles.length > 0 && droppedFiles[0].type.match('image.*')) {
                
                // 2. CRITICAL BRIDGE: Create a new container and inject the file into the HTML input
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(droppedFiles[0]);
                fileInput.files = dataTransfer.files;

                // 3. RUN PREVIEW LOGIC: Now that the file is saved to the form, show the preview image
                const reader = new FileReader();
                reader.onload = function(event) {
                    photoPreview.src = event.target.result;
                    if (photoPlaceholder) photoPlaceholder.classList.add('hidden');
                    if (photoPreviewContainer) photoPreviewContainer.classList.remove('hidden');
                    
                    // Adjust input z-index to allow clicking remove button
                    photoInput.classList.add('hidden');
                };
                reader.readAsDataURL(droppedFiles[0]);

            } else {
                alert('Please drop a valid image file (PNG, JPG, or JPEG).');
            }
        });
        
        photoInput.addEventListener('change', function() {
            if (this.files && this.files.length > 0 && this.files[0].type.match('image.*')) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    photoPreview.src = event.target.result;
                    if (photoPlaceholder) photoPlaceholder.classList.add('hidden');
                    if (photoPreviewContainer) photoPreviewContainer.classList.remove('hidden');
                    
                    // Adjust input z-index to allow clicking remove button
                    photoInput.classList.add('hidden');
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        if (removePhotoBtn) {
            removePhotoBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                photoInput.value = ''; // Clear input
                photoPreview.src = '#';
                
                if (photoPreviewContainer) photoPreviewContainer.classList.add('hidden');
                if (photoPlaceholder) photoPlaceholder.classList.remove('hidden');
                photoInput.classList.remove('hidden');
            });
        }
    }

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
