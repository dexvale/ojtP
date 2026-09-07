<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>OJT Placement Application | OJT Portal</title>
    <meta name="description" content="Register your host company placement, department, and supervisor details."/>
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
        aside nav a { transition: all 0.2s ease; }
    </style>
</head>
<body class="bg-surface font-body text-on-surface antialiased" data-theme="student">

    <!-- TOP HEADER -->
    <header class="fixed top-0 w-full z-50 bg-[#fff7fd] flex justify-between items-center px-6 lg:px-8 py-4 border-b border-[#cec3d0]/20 backdrop-blur-sm lg:pl-64 pl-0">
        <div class="flex items-center gap-4">
            <button id="sidebar-toggle" class="lg:hidden p-2 text-primary rounded-lg hover:bg-surface-container transition-colors" aria-label="Toggle menu">
                <span class="material-symbols-outlined">menu</span>
            </button>
            <!-- Logo & Branding (Mobile only) -->
            <div class="lg:hidden flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center overflow-hidden">
                    <img alt="University Logo" class="h-8 w-8 object-contain" src="{{ asset('images/BISU-Logo-1-150x150.png.webp') }}" />
                </div>
                <span class="text-xl font-headline font-semibold text-primary">OJT Portal</span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('student.profile') }}" class="flex items-center gap-2 text-primary hover:opacity-80 transition-opacity">
                <div class="w-9 h-9 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-sm uppercase">
                    {{ substr($profile->first_name ?? 'S', 0, 1) }}{{ substr($profile->last_name ?? 'T', 0, 1) }}
                </div>
                <span class="text-sm font-semibold hidden md:block">{{ $profile->first_name ?? 'Student' }}</span>
            </a>
        </div>
    </header>

    <!-- SIDEBAR -->
    @include('components.student-sidebar')
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 backdrop-blur-xs z-[55] hidden lg:hidden" onclick="closeSidebar()"></div>

    <!-- MAIN CONTENT -->
    <main class="lg:ml-64 ml-0 pt-24 px-4 sm:px-6 lg:px-8 pb-16 min-h-screen">
        <div class="max-w-5xl mx-auto space-y-8">

            <!-- Page Title -->
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold font-headline text-slate-900 tracking-tight">OJT Placement Registration</h1>
                    <p class="text-slate-500 font-medium text-sm mt-1">Register or view your host training organization, department assignment, and supervisor details.</p>
                </div>
                <div>
                    @if($profile->placement_status === 'Approved')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold uppercase tracking-wider shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">verified</span> Active Placement
                        </span>
                    @elseif($profile->placement_status === 'Pending')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-50 border border-amber-200 text-amber-700 text-xs font-bold uppercase tracking-wider shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">hourglass_top</span> Under Review
                        </span>
                    @elseif($profile->placement_status === 'Rejected')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold uppercase tracking-wider shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">cancel</span> Revision Required
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-100 border border-slate-200 text-slate-600 text-xs font-bold uppercase tracking-wider shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">pending</span> Unassigned
                        </span>
                    @endif
                </div>
            </div>

            <!-- Flash Alerts -->
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-start gap-3 shadow-sm text-emerald-800 text-sm">
                    <span class="material-symbols-outlined text-emerald-600 mt-0.5">check_circle</span>
                    <div>
                        <p class="font-bold">Success</p>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-50 border border-rose-200 rounded-xl p-4 flex items-start gap-3 shadow-sm text-rose-800 text-sm">
                    <span class="material-symbols-outlined text-rose-600 mt-0.5">error</span>
                    <div>
                        <p class="font-bold">Error</p>
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-rose-50 border border-rose-200 rounded-xl p-4 text-rose-800 text-sm">
                    <p class="font-bold flex items-center gap-1.5 mb-1"><span class="material-symbols-outlined text-rose-600">warning</span> Please correct the following errors:</p>
                    <ul class="list-disc list-inside space-y-1 ml-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ═════════════════════════════════════════════════════════════ --}}
            {{-- CASE 1: PLACEMENT IS APPROVED (ACTIVE)                       --}}
            {{-- ═════════════════════════════════════════════════════════════ --}}
            @if($profile->placement_status === 'Approved')
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="bg-gradient-to-r from-[#2c003e] to-[#4a154b] p-6 sm:p-8 text-white flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center text-white text-3xl font-bold flex-shrink-0">
                                <span class="material-symbols-outlined text-4xl">corporate_fare</span>
                            </div>
                            <div>
                                <span class="text-xs uppercase tracking-widest font-bold text-amber-300">Endorsed Host Organization</span>
                                <h2 class="text-2xl sm:text-3xl font-headline font-bold">{{ $profile->company->name ?? 'Company Assigned' }}</h2>
                                <p class="text-white/70 text-sm flex items-center gap-1.5 mt-1">
                                    <span class="material-symbols-outlined text-base">location_on</span>
                                    {{ $profile->company->location ?? 'Location not specified' }}
                                </p>
                            </div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/15 min-w-[200px]">
                            <p class="text-[11px] font-bold uppercase tracking-widest text-white/60">Department Assignment</p>
                            <p class="text-lg font-bold text-white mt-0.5">{{ $profile->department ?? 'General Operations' }}</p>
                        </div>
                    </div>

                    <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 bg-slate-50/50 border-b border-slate-100">
                        <div class="bg-white p-5 rounded-xl border border-slate-200/60 shadow-sm">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-sm text-purple-700">supervisor_account</span> Immediate Supervisor
                            </p>
                            <p class="text-base font-bold text-slate-800">{{ $profile->supervisor->name ?? $profile->pending_supervisor_name ?? 'Assigned Supervisor' }}</p>
                            <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                                <span class="material-symbols-outlined text-xs">mail</span>
                                {{ $profile->supervisor->email ?? $profile->pending_supervisor_email ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="bg-white p-5 rounded-xl border border-slate-200/60 shadow-sm">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-sm text-purple-700">calendar_today</span> Internship Schedule
                            </p>
                            <p class="text-base font-bold text-slate-800">
                                {{ $profile->internship_start ? \Carbon\Carbon::parse($profile->internship_start)->format('F d, Y') : 'Active' }}
                            </p>
                            <p class="text-xs text-slate-500 mt-1">Required: {{ $profile->required_hours ?? 400 }} hours</p>
                        </div>

                        <div class="bg-white p-5 rounded-xl border border-slate-200/60 shadow-sm">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-sm text-purple-700">description</span> Acceptance Document
                            </p>
                            @if($profile->acceptance_letter_path)
                                <a href="{{ asset('storage/' . $profile->acceptance_letter_path) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-purple-700 hover:text-purple-900 transition">
                                    <span class="material-symbols-outlined text-base">visibility</span> View Submitted Document
                                </a>
                            @else
                                <p class="text-xs text-slate-400 italic">No document attached.</p>
                            @endif
                        </div>
                    </div>

                    <div class="p-6 bg-white flex items-center justify-between">
                        <p class="text-xs text-slate-500">Need to change company or department? Please contact your OJT Coordinator.</p>
                        <a href="{{ route('student.logs.index') }}" class="px-5 py-2.5 bg-primary text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:opacity-90 transition shadow-sm">
                            Go to Internship Logs &rarr;
                        </a>
                    </div>
                </div>

            {{-- ═════════════════════════════════════════════════════════════ --}}
            {{-- CASE 2: PLACEMENT IS PENDING REVIEW                           --}}
            {{-- ═════════════════════════════════════════════════════════════ --}}
            @elseif($profile->placement_status === 'Pending')
                <div class="bg-white rounded-2xl border border-amber-200 shadow-sm overflow-hidden">
                    <div class="bg-amber-500/10 border-b border-amber-200 p-6 flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-amber-500 text-white flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-2xl">hourglass_empty</span>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-xl font-headline font-bold text-amber-900">Placement Application Under Review</h2>
                            <p class="text-amber-800 text-xs sm:text-sm mt-1">Your company placement and department details have been forwarded to your Department Coordinator for verification and endorsement.</p>
                        </div>
                    </div>

                    <div class="p-6 sm:p-8 space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/70">
                                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Company</span>
                                <p class="text-sm font-bold text-slate-800 mt-1">
                                    {{ $profile->company->name ?? $profile->pending_company_name ?? 'Pending Company' }}
                                </p>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/70">
                                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Department</span>
                                <p class="text-sm font-bold text-slate-800 mt-1">{{ $profile->department ?? 'General Operations' }}</p>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/70">
                                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Immediate Supervisor</span>
                                <p class="text-sm font-bold text-slate-800 mt-1">{{ $profile->pending_supervisor_name ?? $profile->supervisor->name ?? 'Supervisor' }}</p>
                                <p class="text-xs text-slate-500">{{ $profile->pending_supervisor_email ?? $profile->supervisor->email ?? '' }}</p>
                            </div>
                        </div>

                        <div class="border-t border-slate-100 pt-4 flex justify-between items-center">
                            <span class="text-xs text-slate-400">Submitted for academic endorsement</span>
                            <button onclick="document.getElementById('placement-form-wrapper').classList.toggle('hidden')" class="text-xs font-bold text-purple-700 hover:text-purple-900 underline">
                                Need to update or correct information? Click here to modify form
                            </button>
                        </div>
                    </div>
                </div>

            {{-- ═════════════════════════════════════════════════════════════ --}}
            {{-- CASE 3: PLACEMENT REJECTED (REVISION REQUIRED)                --}}
            {{-- ═════════════════════════════════════════════════════════════ --}}
            @elseif($profile->placement_status === 'Rejected')
                <div class="bg-white rounded-2xl border border-rose-200 shadow-sm overflow-hidden">
                    <div class="bg-rose-500/10 border-b border-rose-200 p-6 flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-rose-600 text-white flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-2xl">report_problem</span>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-xl font-headline font-bold text-rose-900">Placement Revision Required</h2>
                            <p class="text-rose-800 text-xs sm:text-sm mt-1">Your Coordinator returned your placement application with the following feedback remarks:</p>
                            <div class="mt-3 p-3 bg-white rounded-lg border border-rose-200 text-rose-900 font-medium text-sm">
                                "{{ $profile->placement_remarks ?? 'Please verify company and supervisor details and resubmit.' }}"
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ═════════════════════════════════════════════════════════════ --}}
            {{-- PLACEMENT FORM (FOR UNASSIGNED, REJECTED, OR EDITING)         --}}
            {{-- ═════════════════════════════════════════════════════════════ --}}
            <div id="placement-form-wrapper" class="{{ in_array($profile->placement_status, ['Approved', 'Pending']) ? 'hidden' : '' }} bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 sm:px-8 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                        <span class="material-symbols-outlined">domain_add</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-headline font-bold text-slate-800">Submit Host Company & Supervisor Details</h2>
                        <p class="text-xs text-slate-500">Provide the organization details where you will render your internship hours.</p>
                    </div>
                </div>

                <form action="{{ route('student.placement.apply') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-8">
                    @csrf

                    <!-- STEP 1: COMPANY SELECTION -->
                    <div>
                        <div class="flex items-center gap-2 mb-4">
                            <span class="w-6 h-6 rounded-full bg-primary text-white text-xs font-bold flex items-center justify-center">1</span>
                            <h3 class="font-bold text-sm text-slate-800 uppercase tracking-wider">Host Training Company / Agency</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                            <label class="cursor-pointer border-2 rounded-xl p-4 flex items-center gap-3 transition-all" id="label-existing-company">
                                <input type="radio" name="company_mode" value="existing" checked class="text-primary focus:ring-primary h-4 w-4" onchange="toggleCompanyMode('existing')">
                                <div>
                                    <p class="font-bold text-sm text-slate-800">Choose from Partner Companies</p>
                                    <p class="text-xs text-slate-500">Select an existing verified partner organization</p>
                                </div>
                            </label>

                            <label class="cursor-pointer border-2 rounded-xl p-4 flex items-center gap-3 transition-all" id="label-new-company">
                                <input type="radio" name="company_mode" value="new" class="text-primary focus:ring-primary h-4 w-4" onchange="toggleCompanyMode('new')">
                                <div>
                                    <p class="font-bold text-sm text-slate-800">+ Register New Company</p>
                                    <p class="text-xs text-slate-500">I applied to a company not listed in the directory</p>
                                </div>
                            </label>
                        </div>

                        <!-- Existing Company Select Dropdown -->
                        <div id="section-existing-company" class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Select Company <span class="text-rose-500">*</span></label>
                                <select name="company_id" id="company_id_select" onchange="handleCompanyChange()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition">
                                    <option value="">-- Choose Host Company --</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" 
                                                data-industry="{{ $company->industry }}" 
                                                data-location="{{ $company->location }}"
                                                data-supervisors="{{ json_encode($company->users->map(fn($u) => ['id' => $u->id, 'email' => $u->email, 'department' => $u->department ?? 'General Operations'])) }}"
                                                {{ (old('company_id', $profile->company_id) == $company->id) ? 'selected' : '' }}>
                                            {{ $company->name }} ({{ $company->location }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- New Company Input Fields -->
                        <div id="section-new-company" class="hidden space-y-4 bg-slate-50/70 p-5 rounded-xl border border-slate-200/70">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Company / Organization Name <span class="text-rose-500">*</span></label>
                                    <input type="text" name="company_name" value="{{ old('company_name', $profile->pending_company_name) }}" placeholder="e.g. Provincial Government of Bohol" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Industry / Field</label>
                                    <input type="text" name="company_industry" value="{{ old('company_industry') }}" placeholder="e.g. Information Technology / Public Sector" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Office Address / Location <span class="text-rose-500">*</span></label>
                                    <input type="text" name="company_location" value="{{ old('company_location') }}" placeholder="e.g. Tagbilaran City, Bohol" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Company Telephone / Mobile</label>
                                    <input type="text" name="company_contact" value="{{ old('company_contact') }}" placeholder="e.g. (038) 411-2345" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2: DEPARTMENT & SUPERVISOR ASSIGNMENT -->
                    <div class="pt-6 border-t border-slate-100">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="w-6 h-6 rounded-full bg-primary text-white text-xs font-bold flex items-center justify-center">2</span>
                            <h3 class="font-bold text-sm text-slate-800 uppercase tracking-wider">Department Assignment & Immediate Supervisor</h3>
                        </div>

                        <div class="space-y-4">
                            <!-- Department Field -->
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Assigned Department / Branch / Unit <span class="text-rose-500">*</span></label>
                                <input type="text" name="department" value="{{ old('department', $profile->department) }}" required placeholder="e.g. Management Information Systems (MIS) / Software Engineering" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                <p class="text-[11px] text-slate-400 mt-1">Specify your exact division inside the organization (allows different departments to have different supervisors).</p>
                            </div>

                            <!-- Supervisor Mode Selection -->
                            <div id="supervisor-mode-container" class="space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <label class="cursor-pointer border-2 rounded-xl p-3 flex items-center gap-3" id="label-existing-sup">
                                        <input type="radio" name="supervisor_mode" value="existing" class="text-primary focus:ring-primary h-4 w-4" onchange="toggleSupervisorMode('existing')">
                                        <div>
                                            <p class="font-bold text-xs text-slate-800">Select Existing Supervisor in Company</p>
                                            <p class="text-[10px] text-slate-500">Pick a registered advisor from this company</p>
                                        </div>
                                    </label>

                                    <label class="cursor-pointer border-2 rounded-xl p-3 flex items-center gap-3" id="label-new-sup">
                                        <input type="radio" name="supervisor_mode" value="new" checked class="text-primary focus:ring-primary h-4 w-4" onchange="toggleSupervisorMode('new')">
                                        <div>
                                            <p class="font-bold text-xs text-slate-800">+ Add New Supervisor Info</p>
                                            <p class="text-[10px] text-slate-500">Enter new supervisor details for my department</p>
                                        </div>
                                    </label>
                                </div>

                                <!-- Existing Supervisor Dropdown -->
                                <div id="section-existing-supervisor" class="hidden">
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Choose Supervisor</label>
                                    <select name="existing_supervisor_id" id="existing_supervisor_select" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                        <option value="">-- Select from company supervisors --</option>
                                    </select>
                                </div>

                                <!-- New Supervisor Fields -->
                                <div id="section-new-supervisor" class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-slate-50/70 p-5 rounded-xl border border-slate-200/70">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Supervisor Full Name <span class="text-rose-500">*</span></label>
                                        <input type="text" name="supervisor_name" id="supervisor_name" value="{{ old('supervisor_name', $profile->pending_supervisor_name) }}" placeholder="e.g. Engr. Juan Dela Cruz" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Supervisor Email <span class="text-rose-500">*</span></label>
                                        <input type="email" name="supervisor_email" id="supervisor_email" value="{{ old('supervisor_email', $profile->pending_supervisor_email) }}" placeholder="e.g. supervisor@company.com" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                        <p class="text-[10px] text-slate-400 mt-1">Advisor login credentials will be generated for this email.</p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Supervisor Mobile #</label>
                                        <input type="text" name="supervisor_contact" id="supervisor_contact" value="{{ old('supervisor_contact', $profile->pending_supervisor_contact) }}" placeholder="e.g. 09123456789" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3: SCHEDULE & ACCEPTANCE DOCUMENT -->
                    <div class="pt-6 border-t border-slate-100">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="w-6 h-6 rounded-full bg-primary text-white text-xs font-bold flex items-center justify-center">3</span>
                            <h3 class="font-bold text-sm text-slate-800 uppercase tracking-wider">Schedule & Documentation</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Target Internship Start Date</label>
                                <input type="date" name="internship_start" value="{{ old('internship_start', $profile->internship_start) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Upload Acceptance Letter / Endorsement Form (Optional)</label>
                                <input type="file" name="acceptance_letter" accept=".pdf,.jpg,.jpeg,.png" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white hover:file:opacity-90">
                                <p class="text-[10px] text-slate-400 mt-1">Accepted formats: PDF, JPG, PNG (Max: 5MB)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-6 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="px-8 py-3.5 bg-primary text-white rounded-xl text-sm font-bold shadow-lg shadow-primary/20 hover:opacity-90 active:scale-95 transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg">send</span>
                            Submit Placement for Endorsement
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </main>
    @include('components.student-bottom-nav')

    <script>
        function toggleCompanyMode(mode) {
            const existingSec = document.getElementById('section-existing-company');
            const newSec = document.getElementById('section-new-company');
            const labelExisting = document.getElementById('label-existing-company');
            const labelNew = document.getElementById('label-new-company');

            if (mode === 'existing') {
                existingSec.classList.remove('hidden');
                newSec.classList.add('hidden');
                labelExisting.classList.add('border-primary', 'bg-primary/5');
                labelExisting.classList.remove('border-slate-200');
                labelNew.classList.remove('border-primary', 'bg-primary/5');
                labelNew.classList.add('border-slate-200');
                handleCompanyChange();
            } else {
                existingSec.classList.add('hidden');
                newSec.classList.remove('hidden');
                labelNew.classList.add('border-primary', 'bg-primary/5');
                labelNew.classList.remove('border-slate-200');
                labelExisting.classList.remove('border-primary', 'bg-primary/5');
                labelExisting.classList.add('border-slate-200');
                
                // For new company, always default to new supervisor
                document.querySelector('input[name="supervisor_mode"][value="new"]').checked = true;
                toggleSupervisorMode('new');
                document.getElementById('label-existing-sup').classList.add('opacity-50', 'pointer-events-none');
            }
        }

        function handleCompanyChange() {
            const select = document.getElementById('company_id_select');
            const selectedOption = select.options[select.selectedIndex];
            const supSelect = document.getElementById('existing_supervisor_select');
            const labelExistingSup = document.getElementById('label-existing-sup');

            supSelect.innerHTML = '<option value="">-- Select from company supervisors --</option>';

            if (selectedOption && selectedOption.dataset.supervisors) {
                try {
                    const supervisors = JSON.parse(selectedOption.dataset.supervisors);
                    if (supervisors.length > 0) {
                        labelExistingSup.classList.remove('opacity-50', 'pointer-events-none');
                        supervisors.forEach(sup => {
                            const opt = document.createElement('option');
                            opt.value = sup.id;
                            opt.textContent = `${sup.email} (${sup.department})`;
                            supSelect.appendChild(opt);
                        });
                    } else {
                        labelExistingSup.classList.add('opacity-50', 'pointer-events-none');
                        document.querySelector('input[name="supervisor_mode"][value="new"]').checked = true;
                        toggleSupervisorMode('new');
                    }
                } catch(e) {
                    labelExistingSup.classList.add('opacity-50', 'pointer-events-none');
                }
            } else {
                labelExistingSup.classList.add('opacity-50', 'pointer-events-none');
            }
        }

        function toggleSupervisorMode(mode) {
            const existingSec = document.getElementById('section-existing-supervisor');
            const newSec = document.getElementById('section-new-supervisor');
            const labelExisting = document.getElementById('label-existing-sup');
            const labelNew = document.getElementById('label-new-sup');

            if (mode === 'existing') {
                existingSec.classList.remove('hidden');
                newSec.classList.add('hidden');
                labelExisting.classList.add('border-primary', 'bg-primary/5');
                labelExisting.classList.remove('border-slate-200');
                labelNew.classList.remove('border-primary', 'bg-primary/5');
                labelNew.classList.add('border-slate-200');
                document.getElementById('supervisor_name').required = false;
                document.getElementById('supervisor_email').required = false;
            } else {
                existingSec.classList.add('hidden');
                newSec.classList.remove('hidden');
                labelNew.classList.add('border-primary', 'bg-primary/5');
                labelNew.classList.remove('border-slate-200');
                labelExisting.classList.remove('border-primary', 'bg-primary/5');
                labelExisting.classList.add('border-slate-200');
                document.getElementById('supervisor_name').required = true;
                document.getElementById('supervisor_email').required = true;
            }
        }

        // Initialize state on load
        document.addEventListener('DOMContentLoaded', () => {
            toggleCompanyMode('existing');
            toggleSupervisorMode('new');
        });

        // Sidebar Toggling Code
        const sidebarEl   = document.getElementById('sidebar');
        const overlayEl   = document.getElementById('sidebar-overlay');
        const toggleBtnEl = document.getElementById('sidebar-toggle');

        function openSidebar() {
            sidebarEl.classList.remove('-translate-x-full');
            overlayEl?.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
        function closeSidebar() {
            sidebarEl.classList.add('-translate-x-full');
            overlayEl?.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
        toggleBtnEl?.addEventListener('click', () => {
            sidebarEl.classList.contains('-translate-x-full') ? openSidebar() : closeSidebar();
        });
    </script>
</body>
</html>
