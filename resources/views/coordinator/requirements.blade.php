<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>OJT Portal | Manage Requirements</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link
        href="https://fonts.googleapis.com/css2?family=Newsreader:opsz,wght@6..72,400;6..72,600;6..72,700;6..72,800&family=Public+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Public Sans', sans-serif;
        }

        .font-headline {
            font-family: 'Newsreader', serif;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
    </style>
</head>

<body class="bg-surface text-on-surface" data-theme="portal">
    <!-- SideNavBar (Shared Component) -->
    @include('components.coordinator-sidebar')

    <!-- Sidebar overlay for mobile -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 backdrop-blur-xs z-40 hidden lg:hidden" onclick="closeSidebar()"></div>

    <!-- TopNavBar -->
    <header class="fixed top-0 lg:left-64 left-0 right-0 z-40 bg-surface/90 backdrop-blur-sm border-b border-[#cec3d0]/15">
        <div class="flex justify-between items-center px-4 md:px-8 py-3.5 md:py-4 w-full">
            <div class="flex items-center gap-3 md:gap-4">
                <button id="sidebar-toggle" class="lg:hidden p-2 min-w-[40px] min-h-[40px] flex items-center justify-center text-primary rounded-lg hover:bg-black/5 transition-colors" aria-label="Toggle menu">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <span class="text-xl md:text-2xl font-headline font-semibold text-primary tracking-tight">OJT Management</span>
                <nav class="hidden md:flex items-center gap-6">
                    <a class="text-sm font-semibold text-on-surface/60 hover:text-primary transition-colors duration-200" href="{{ route('coordinator.dashboard') }}">Dashboard</a>
                    <a class="text-sm font-semibold text-on-surface/60 hover:text-primary transition-colors duration-200" href="{{ route('coordinator.students') }}">Student List</a>
                    <a class="text-sm font-semibold text-on-surface/60 hover:text-primary transition-colors duration-200" href="{{ route('coordinator.companies') }}">Company Directory</a>
                    <a class="text-sm font-semibold text-on-surface/60 hover:text-primary transition-colors duration-200" href="{{ route('coordinator.reports') }}">Reports</a>
                </nav>
            </div>
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-3">
                    <button class="p-2 text-primary hover:bg-black/5 rounded-full transition-all active:scale-95">
                        <span class="material-symbols-outlined" data-icon="notifications">notifications</span>
                    </button>
                    <div class="relative flex items-center sm:pl-2 sm:border-l sm:border-[#cec3d0]/30" id="user-profile-menu">
                        <button type="button" id="user-menu-btn" class="flex items-center gap-3 cursor-pointer focus:outline-none" aria-expanded="false" aria-haspopup="true">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-bold font-headline text-primary">{{ auth()->user()->display_name }}</p>
                                <p class="text-[10px] uppercase tracking-wider text-secondary font-bold">{{ auth()->user()->display_role }}</p>
                            </div>
                            <img alt="User profile avatar"
                                class="w-9 h-9 rounded-full object-cover ring-2 ring-primary/10 hover:ring-primary transition-all"
                                src="{{ auth()->user()->avatar_url }}">
                        </button>
                        <!-- Dropdown Menu -->
                        @include('components.user-dropdown')
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="lg:ml-64 ml-0 pt-20 md:pt-24 px-4 sm:px-6 lg:px-8 pb-12 min-h-screen border-none">
        
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-extrabold font-headline text-slate-900 tracking-tight">OJT Requirements</h1>
            <p class="text-slate-500 font-medium mt-1 text-sm">Define required forms, upload template documents, and verify student submissions.</p>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-xl mb-6 shadow-sm flex items-center gap-3">
                <span class="material-symbols-outlined text-emerald-600 text-[20px]">check_circle</span>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-xl mb-6 shadow-sm">
                <div class="flex items-center gap-3 mb-2">
                    <span class="material-symbols-outlined text-rose-600 text-[20px]">error</span>
                    <span class="text-sm font-bold">Please correct the following errors:</span>
                </div>
                <ul class="list-disc list-inside text-xs space-y-1 ml-6">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">
            
            <!-- LEFT AREA: Submissions & Templates Listing (8 Columns) -->
            <div class="xl:col-span-8 space-y-8">
                
                <!-- Submissions Queue Section -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
                        <h2 class="text-xl font-bold font-headline text-slate-800 flex items-center gap-2">
                            <span class="material-symbols-outlined text-purple-600">assignment_turned_in</span>
                            Student Document Submissions
                        </h2>
                    </div>

                    <!-- Filter & Search Controls -->
                    <div class="bg-slate-50/80 border border-slate-200 rounded-xl p-3.5 mb-6 flex flex-col md:flex-row md:items-center justify-between gap-3">
                        <div class="flex-1 flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
                            <!-- Search Input -->
                            <div class="relative flex-1 min-w-[180px]">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-base">search</span>
                                <input type="text" id="submissionSearch" oninput="filterSubmissions()" placeholder="Search student name or requirement..."
                                       class="w-full pl-9 pr-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all">
                            </div>

                            <!-- Filter by Requirement -->
                            <div class="sm:w-52">
                                <select id="filterRequirement" onchange="filterSubmissions()"
                                        class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-purple-400 transition-all cursor-pointer">
                                    <option value="">All Requirements</option>
                                    @foreach($requirements as $reqItem)
                                        <option value="{{ $reqItem->id }}">{{ $reqItem->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter by Course -->
                            <div class="sm:w-48">
                                <select id="filterCourse" onchange="filterSubmissions()"
                                        class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-purple-400 transition-all cursor-pointer">
                                    <option value="">All Courses</option>
                                    @foreach($managedCourses as $c)
                                        <option value="{{ $c->course_name }}">{{ $c->course_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Reset Filters Button -->
                        <div class="flex items-center justify-end">
                            <button type="button" onclick="resetFilters()" id="resetFiltersBtn"
                                    class="text-xs font-semibold text-slate-500 hover:text-purple-700 flex items-center gap-1 transition px-2 py-1 rounded-lg hover:bg-white border border-transparent hover:border-slate-200">
                                <span class="material-symbols-outlined text-[15px]">refresh</span>
                                Reset Filters
                            </button>
                        </div>
                    </div>

                    <!-- Filter Tabs -->
                    <div class="flex border-b border-slate-200 mb-6">
                        <button onclick="switchTab('pending-tab', 'pending-content')" id="pending-tab"
                                class="tab-btn px-4 py-2 text-sm font-bold border-b-2 border-purple-600 text-purple-600 focus:outline-none transition-all">
                            Pending Verification (<span id="pendingCountBadge">{{ $submissions->where('status', 'Pending')->count() }}</span>)
                        </button>
                        <button onclick="switchTab('approved-tab', 'approved-content')" id="approved-tab"
                                class="tab-btn px-4 py-2 text-sm font-medium text-slate-500 hover:text-slate-700 border-b-2 border-transparent focus:outline-none transition-all">
                            Approved (<span id="approvedCountBadge">{{ $submissions->where('status', 'Approved')->count() }}</span>)
                        </button>
                        <button onclick="switchTab('rejected-tab', 'rejected-content')" id="rejected-tab"
                                class="tab-btn px-4 py-2 text-sm font-medium text-slate-500 hover:text-slate-700 border-b-2 border-transparent focus:outline-none transition-all">
                            Revision Required (<span id="rejectedCountBadge">{{ $submissions->where('status', 'Rejected')->count() }}</span>)
                        </button>
                    </div>

                    <!-- PENDING CONTENT -->
                    <div id="pending-content" class="tab-content block">
                        <!-- Batch Action Bar -->
                        <div id="batchActionBar" class="hidden items-center justify-between bg-purple-50/90 border border-purple-200/80 rounded-xl p-3 mb-4 transition-all">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-purple-700 text-lg">check_circle</span>
                                <span id="selectedCountText" class="text-xs font-bold text-purple-900">0 submissions selected</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="clearSelectedSubmissions()" class="px-3 py-1.5 text-xs font-medium text-slate-600 hover:text-slate-900 transition cursor-pointer">
                                    Deselect All
                                </button>
                                <button type="button" onclick="submitBatchDownload('pending-content')" class="px-3.5 py-1.5 bg-white border border-purple-200 hover:bg-purple-100 text-purple-800 rounded-lg text-xs font-bold shadow-sm transition flex items-center gap-1.5 cursor-pointer" title="Download selected pending submissions as ZIP">
                                    <span class="material-symbols-outlined text-[16px]">folder_zip</span>
                                    <span>Download ZIP</span>
                                </button>
                                <button type="button" onclick="confirmBatchApprove()" id="batchApproveBtn" class="px-4 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                                    <span class="material-symbols-outlined text-[16px]">done_all</span>
                                    <span>Approve Selected</span>
                                </button>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm border-collapse">
                                <thead>
                                    <tr class="border-b border-slate-100 text-slate-400 font-semibold text-xs uppercase bg-slate-50/50">
                                        <th class="py-3 px-4 w-10">
                                            <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)"
                                                   class="rounded border-slate-300 text-purple-600 focus:ring-purple-400 cursor-pointer" title="Select all visible">
                                        </th>
                                        <th class="py-3 px-4">Student</th>
                                        <th class="py-3 px-4">Requirement</th>
                                        <th class="py-3 px-4">Submitted File</th>
                                        <th class="py-3 px-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($submissions->where('status', 'Pending') as $sub)
                                        <tr id="sub-row-{{ $sub->id }}" class="submission-row border-b border-slate-100 hover:bg-slate-50/30 transition-colors"
                                            data-id="{{ $sub->id }}"
                                            data-student="{{ strtolower(($sub->user->studentProfile->first_name ?? '') . ' ' . ($sub->user->studentProfile->last_name ?? '')) }}"
                                            data-student-name="{{ ($sub->user->studentProfile->first_name ?? 'N/A') . ' ' . ($sub->user->studentProfile->last_name ?? '') }}"
                                            data-course="{{ $sub->user->studentProfile->course ?? '' }}"
                                            data-requirement-id="{{ $sub->requirement_id }}"
                                            data-requirement-title="{{ $sub->requirement->title ?? 'Unknown Requirement' }}"
                                            data-file-url="{{ asset('storage/' . $sub->file_path) }}">
                                            <td class="py-4 px-4">
                                                <input type="checkbox" class="sub-checkbox rounded border-slate-300 text-purple-600 focus:ring-purple-400 cursor-pointer"
                                                       value="{{ $sub->id }}" onchange="handleRowCheckboxChange()">
                                            </td>
                                            <td class="py-4 px-4">
                                                <div class="font-bold text-slate-800">{{ $sub->user->studentProfile->first_name ?? 'N/A' }} {{ $sub->user->studentProfile->last_name ?? '' }}</div>
                                                <div class="text-[10px] text-slate-400 font-medium">{{ $sub->user->studentProfile->course ?? '' }}</div>
                                            </td>
                                            <td class="py-4 px-4 font-semibold text-purple-950">{{ $sub->requirement->title ?? 'Unknown Requirement' }}</td>
                                            <td class="py-4 px-4">
                                                <button onclick="openSpeedReviewModal({{ $sub->id }})"
                                                   class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-50 hover:bg-purple-100 border border-purple-100 rounded-lg text-purple-700 text-xs font-bold transition shadow-sm cursor-pointer">
                                                    <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span>
                                                    Review Document
                                                </button>
                                            </td>
                                            <td class="py-4 px-4 text-right space-x-2">
                                                <form action="{{ route('coordinator.submissions.approve', $sub->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="p-1.5 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-700 rounded-lg transition cursor-pointer" title="Approve Submission">
                                                        <span class="material-symbols-outlined text-base">check</span>
                                                    </button>
                                                </form>
                                                <button onclick="openRejectModal({{ $sub->id }})" class="p-1.5 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 rounded-lg transition cursor-pointer" title="Reject / Request Revision">
                                                    <span class="material-symbols-outlined text-base">close</span>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr id="pending-empty-row">
                                            <td colspan="5" class="py-12 text-center text-slate-400 font-medium italic">No pending submissions to verify.</td>
                                        </tr>
                                    @endforelse
                                    <tr id="pending-no-match-row" class="hidden">
                                        <td colspan="5" class="py-12 text-center text-slate-400 font-medium italic">No submissions match the selected filters.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- APPROVED CONTENT -->
                    <div id="approved-content" class="tab-content hidden">
                        <!-- Approved Batch Action Bar -->
                        <div id="approvedBatchActionBar" class="hidden items-center justify-between bg-purple-50/90 border border-purple-200/80 rounded-xl p-3 mb-4 transition-all">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-purple-700 text-lg">check_circle</span>
                                <span id="approvedSelectedCountText" class="text-xs font-bold text-purple-900">0 documents selected</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="clearSelectedApproved()" class="px-3 py-1.5 text-xs font-medium text-slate-600 hover:text-slate-900 transition cursor-pointer">
                                    Deselect All
                                </button>
                                <button type="button" onclick="submitBatchDownload('approved-content')" class="px-4 py-1.5 bg-[#300050] hover:bg-purple-950 text-white rounded-lg text-xs font-bold shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                                    <span class="material-symbols-outlined text-[16px]">folder_zip</span>
                                    <span>Download Selected as ZIP</span>
                                </button>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm border-collapse">
                                <thead>
                                    <tr class="border-b border-slate-100 text-slate-400 font-semibold text-xs uppercase bg-slate-50/50">
                                        <th class="py-3 px-4 w-10">
                                            <input type="checkbox" id="selectAllApprovedCheckbox" onchange="toggleSelectAllApproved(this)"
                                                   class="rounded border-slate-300 text-purple-600 focus:ring-purple-400 cursor-pointer" title="Select all visible">
                                        </th>
                                        <th class="py-3 px-4">Student</th>
                                        <th class="py-3 px-4">Requirement</th>
                                        <th class="py-3 px-4">Submitted File</th>
                                        <th class="py-3 px-4">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($submissions->where('status', 'Approved') as $sub)
                                        <tr class="submission-row border-b border-slate-100 hover:bg-slate-50/30 transition-colors"
                                            data-student="{{ strtolower(($sub->user->studentProfile->first_name ?? '') . ' ' . ($sub->user->studentProfile->last_name ?? '')) }}"
                                            data-course="{{ $sub->user->studentProfile->course ?? '' }}"
                                            data-requirement-id="{{ $sub->requirement_id }}"
                                            data-requirement-title="{{ $sub->requirement->title ?? '' }}">
                                            <td class="py-4 px-4">
                                                <input type="checkbox" class="approved-checkbox rounded border-slate-300 text-purple-600 focus:ring-purple-400 cursor-pointer"
                                                       value="{{ $sub->id }}" onchange="handleApprovedRowCheckboxChange()">
                                            </td>
                                            <td class="py-4 px-4">
                                                <div class="font-bold text-slate-800">{{ $sub->user->studentProfile->first_name ?? 'N/A' }} {{ $sub->user->studentProfile->last_name ?? '' }}</div>
                                                <div class="text-[10px] text-slate-400 font-medium">{{ $sub->user->studentProfile->course ?? '' }}</div>
                                            </td>
                                            <td class="py-4 px-4 font-semibold text-[#300050]">{{ $sub->requirement->title }}</td>
                                            <td class="py-4 px-4">
                                                <a href="{{ asset('storage/' . $sub->file_path) }}" target="_blank" rel="noopener noreferrer"
                                                   class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg text-slate-600 text-xs font-bold transition">
                                                    <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span>
                                                    View File
                                                </a>
                                            </td>
                                            <td class="py-4 px-4">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-bold uppercase tracking-wider rounded-full shadow-sm">
                                                    <span class="material-symbols-outlined text-[10px] font-bold">check</span> Verified
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr id="approved-empty-row">
                                            <td colspan="5" class="py-12 text-center text-slate-400 font-medium italic">No verified documents yet.</td>
                                        </tr>
                                    @endforelse
                                    <tr id="approved-no-match-row" class="hidden">
                                        <td colspan="5" class="py-12 text-center text-slate-400 font-medium italic">No submissions match the selected filters.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- REJECTED CONTENT -->
                    <div id="rejected-content" class="tab-content hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm border-collapse">
                                <thead>
                                    <tr class="border-b border-slate-100 text-slate-400 font-semibold text-xs uppercase bg-slate-50/50">
                                        <th class="py-3 px-4">Student</th>
                                        <th class="py-3 px-4">Requirement</th>
                                        <th class="py-3 px-4">File & Remarks</th>
                                        <th class="py-3 px-4 text-right">Re-eval Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($submissions->where('status', 'Rejected') as $sub)
                                        <tr class="submission-row border-b border-slate-100 hover:bg-slate-50/30 transition-colors"
                                            data-student="{{ strtolower(($sub->user->studentProfile->first_name ?? '') . ' ' . ($sub->user->studentProfile->last_name ?? '')) }}"
                                            data-course="{{ $sub->user->studentProfile->course ?? '' }}"
                                            data-requirement-id="{{ $sub->requirement_id }}"
                                            data-requirement-title="{{ $sub->requirement->title ?? '' }}">
                                            <td class="py-4 px-4">
                                                <div class="font-bold text-slate-800">{{ $sub->user->studentProfile->first_name ?? 'N/A' }} {{ $sub->user->studentProfile->last_name ?? '' }}</div>
                                                <div class="text-[10px] text-slate-400 font-medium">{{ $sub->user->studentProfile->course ?? '' }}</div>
                                            </td>
                                            <td class="py-4 px-4 font-semibold text-slate-700">{{ $sub->requirement->title }}</td>
                                            <td class="py-4 px-4 max-w-xs">
                                                <a href="{{ asset('storage/' . $sub->file_path) }}" target="_blank" rel="noopener noreferrer"
                                                   class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg text-slate-600 text-xs font-bold transition mb-2">
                                                    <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span>
                                                    Rejected File
                                                </a>
                                                <div class="bg-rose-50 text-rose-900 border border-rose-100 text-xs p-2 rounded-lg italic">
                                                    <strong>Remarks:</strong> {{ $sub->remarks }}
                                                </div>
                                            </td>
                                            <td class="py-4 px-4 text-right">
                                                <form action="{{ route('coordinator.submissions.approve', $sub->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition cursor-pointer" title="Override and Approve">
                                                        Approve
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr id="rejected-empty-row">
                                            <td colspan="4" class="py-12 text-center text-slate-400 font-medium italic">No rejected/returned submissions.</td>
                                        </tr>
                                    @endforelse
                                    <tr id="rejected-no-match-row" class="hidden">
                                        <td colspan="4" class="py-12 text-center text-slate-400 font-medium italic">No submissions match the selected filters.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Templates List Section -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-xl font-bold font-headline text-slate-800 mb-5 flex items-center gap-2">
                        <span class="material-symbols-outlined text-purple-600">folder_open</span>
                        Active OJT Requirements List
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse($requirements as $req)
                            <div class="border border-slate-200 rounded-xl p-5 hover:shadow-md transition relative flex flex-col justify-between min-h-[140px]">
                                <div>
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="font-bold text-[#300050] text-base truncate pr-6" title="{{ $req->title }}">{{ $req->title }}</h3>
                                        <form action="{{ route('coordinator.requirements.destroy', $req->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this requirement template? This will also delete all student submissions for it.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-400 hover:text-rose-600 transition" title="Delete Template">
                                                <span class="material-symbols-outlined text-lg">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                    <p class="text-xs text-slate-500 font-medium mb-4 line-clamp-2" title="{{ $req->description ?: 'No description provided.' }}">
                                        {{ $req->description ?: 'No description provided.' }}
                                    </p>
                                </div>
                                <div class="border-t border-slate-100 pt-3 flex justify-between items-center mt-auto">
                                    @if($req->template_path)
                                        <a href="{{ asset('storage/' . $req->template_path) }}" target="_blank" rel="noopener noreferrer"
                                           class="inline-flex items-center gap-1.5 text-xs font-bold text-purple-700 hover:text-purple-950 transition">
                                            <span class="material-symbols-outlined text-[16px]">visibility</span>
                                            View Document
                                        </a>
                                    @else
                                        <span class="text-[10px] text-slate-400 font-medium italic">No document template uploaded</span>
                                    @endif
                                    <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">
                                        Added {{ $req->created_at->format('M d, Y') }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 py-12 text-center border-2 border-dashed border-slate-200 rounded-xl">
                                <span class="material-symbols-outlined text-slate-300 text-5xl mb-3">folder</span>
                                <p class="text-sm text-slate-500 font-medium">No requirement definitions created yet.</p>
                                <p class="text-xs text-slate-400 mt-1">Use the panel on the right to configure the first one.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- RIGHT AREA: Define New Requirement Form (4 Columns) -->
            <div class="xl:col-span-4">
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 sticky top-24">
                    <h2 class="text-xl font-bold font-headline text-slate-800 mb-5 flex items-center gap-2">
                        <span class="material-symbols-outlined text-purple-600">note_add</span>
                        New OJT Requirement
                    </h2>

                    <form action="{{ route('coordinator.requirements') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Title Input -->
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Requirement Title</label>
                            <input type="text" name="title" value="{{ old('title') }}" required
                                   class="w-full border border-slate-200 rounded-xl px-4 py-2.5 bg-slate-50/50 text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all"
                                   placeholder="e.g. MOA, Internship Waiver">
                        </div>

                        <!-- Description Input -->
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Instructions / Description</label>
                            <textarea name="description" rows="3"
                                      class="w-full border border-slate-200 rounded-xl px-4 py-2.5 bg-slate-50/50 text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all"
                                      placeholder="Explain instructions, guidelines, and what students should submit.">{{ old('description') }}</textarea>
                        </div>

                        <!-- Scope to Academic Course(s) Checkboxes -->
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Scope to Academic Course(s)</label>
                            <div class="space-y-2 bg-slate-50 border border-slate-100 p-4 rounded-xl">
                                @foreach($managedCourses as $course)
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="checkbox" name="courses[]" value="{{ $course->id }}" checked class="rounded border-slate-200 text-purple-600 focus:ring-purple-400 focus:ring-opacity-25 transition-all">
                                        <span class="text-sm text-slate-700 font-medium group-hover:text-purple-900 transition-colors">{{ $course->course_name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1 font-medium">Specify which course departments this requirement applies to.</p>
                        </div>

                        <!-- Template File Input -->
                        <div class="mb-6">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Document Template (Optional)</label>
                            <div class="border-2 border-dashed border-slate-200 hover:border-purple-300 rounded-xl p-4 bg-slate-50/50 hover:bg-purple-50/10 cursor-pointer transition-colors relative flex flex-col items-center justify-center">
                                <input type="file" name="template" id="template_file_input"
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                       onchange="updateFileNameDisplay(this, 'file_name_display')">
                                <span class="material-symbols-outlined text-slate-400 text-3xl mb-2">cloud_upload</span>
                                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider" id="file_name_display">Click to upload template</p>
                                <p class="text-[9px] text-slate-400 font-medium mt-1">PDF, DOCX, DOC up to 5MB</p>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                                class="w-full bg-[#300050] hover:bg-purple-950 text-white py-3 rounded-xl font-bold text-sm shadow-md transition active:scale-95">
                            Publish OJT Requirement
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </main>

    <!-- BATCH APPROVE FORM -->
    <form id="batchApproveForm" action="{{ route('coordinator.submissions.batch-approve') }}" method="POST" class="hidden">
        @csrf
        <div id="batchApproveInputs"></div>
    </form>

    <!-- BATCH DOWNLOAD FORM -->
    <form id="batchDownloadForm" action="{{ route('coordinator.submissions.batch-download') }}" method="POST" class="hidden">
        @csrf
        <div id="batchDownloadInputs"></div>
    </form>

    <!-- BATCH APPROVE CONFIRMATION MODAL -->
    <div id="batchApproveModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl border border-purple-100 p-6 w-full max-w-md mx-4">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-2xl">verified</span>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800 font-headline">Batch Approve Submissions</h3>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">Confirm bulk document verification</p>
                </div>
            </div>
            
            <p class="text-sm text-slate-600 mb-6">
                You are about to approve <strong id="batchConfirmCountText" class="text-emerald-700 font-bold">0 submissions</strong> at once. These requirements will be marked as verified.
            </p>

            <div class="flex gap-3">
                <button type="button" onclick="closeBatchModal()" class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-bold hover:bg-gray-50 transition cursor-pointer">
                    Cancel
                </button>
                <button type="button" onclick="submitBatchApprove()" id="confirmBatchSubmitBtn" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-xl text-sm font-bold shadow-md transition flex items-center justify-center gap-2 cursor-pointer">
                    <span class="material-symbols-outlined text-base">check</span>
                    <span>Confirm Approval</span>
                </button>
            </div>
        </div>
    </div>

    <!-- REJECTION REMARKS MODAL -->
    <div id="rejectModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl border border-purple-100 p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-xl font-bold font-headline text-slate-800 flex items-center gap-2">
                    <span class="material-symbols-outlined text-rose-600">warning</span>
                    Return for Revision
                </h2>
                <button onclick="closeRejectModal()" class="text-gray-400 hover:text-rose-500 transition cursor-pointer">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <form method="POST" id="rejectForm">
                @csrf
                <div class="mb-6">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Provide Feedback / Remarks</label>
                    <textarea name="remarks" required rows="4"
                              class="w-full border border-slate-200 rounded-xl px-4 py-2.5 bg-slate-50/50 text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all"
                              placeholder="Describe why this document is being returned (e.g. missing signature, blurred scan) and what changes are needed."></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeRejectModal()" class="flex-1 px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-bold hover:bg-gray-50 transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 bg-rose-600 hover:bg-rose-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md transition cursor-pointer">
                        Reject Submission
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- DOCUMENT REVIEW LIGHTBOX MODAL (Speed Reviewer) -->
    <div id="reviewModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-black/80 backdrop-blur-sm transition-opacity duration-300 p-2 sm:p-6">
        <div class="bg-white rounded-2xl shadow-2xl flex flex-col w-full max-w-5xl h-full max-h-[92vh] overflow-hidden relative">
            
            <!-- Header -->
            <div class="px-5 py-3.5 border-b border-slate-200 flex flex-wrap gap-3 justify-between items-center bg-white">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[20px]">assignment</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 id="modalStudentName" class="text-base font-bold text-slate-800 font-headline">Student Name</h2>
                            <span id="modalStudentCourse" class="text-[10px] font-semibold uppercase tracking-wider bg-slate-100 text-slate-600 px-2 py-0.5 rounded-md">Course</span>
                        </div>
                        <p id="modalRequirementTitle" class="text-xs font-semibold text-purple-800">Requirement Title</p>
                    </div>
                </div>

                <!-- Navigation Controls & Counter -->
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1 bg-slate-100 rounded-lg p-1 text-xs">
                        <button type="button" id="prevReviewBtn" onclick="navigateReview(-1)" class="p-1 hover:bg-white text-slate-600 rounded transition disabled:opacity-30 disabled:cursor-not-allowed cursor-pointer" title="Previous Document">
                            <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                        </button>
                        <span id="reviewQueueCounter" class="px-2 font-bold text-slate-700 text-xs min-w-[50px] text-center">1 of 1</span>
                        <button type="button" id="nextReviewBtn" onclick="navigateReview(1)" class="p-1 hover:bg-white text-slate-600 rounded transition disabled:opacity-30 disabled:cursor-not-allowed cursor-pointer" title="Next Document">
                            <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                        </button>
                    </div>
                    <button onclick="closeReviewModal()" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-full transition cursor-pointer" title="Close Lightbox">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
            </div>
            
            <!-- Body: PDF Viewer -->
            <div class="flex-1 bg-slate-200/50 w-full relative">
                <iframe id="reviewIframe" src="" class="absolute inset-0 w-full h-full border-none"></iframe>
            </div>

            <!-- Footer: Actions -->
            <div class="px-5 py-3.5 bg-white border-t border-slate-200 flex flex-wrap gap-3 justify-between items-center">
                <div class="flex items-center gap-2">
                    <button type="button" id="reviewRejectBtn" class="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl text-xs sm:text-sm font-bold transition flex items-center gap-1.5 cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                        Return for Revision
                    </button>
                    <a id="modalExternalLink" href="#" target="_blank" rel="noopener noreferrer" class="px-3 py-2 text-slate-500 hover:text-purple-700 text-xs font-semibold flex items-center gap-1 transition" title="Open in new tab">
                        <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                        Open Tab
                    </a>
                </div>

                <div class="flex items-center gap-2.5">
                    <button type="button" id="skipReviewBtn" onclick="navigateReview(1)" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs sm:text-sm font-bold transition cursor-pointer">
                        Skip
                    </button>
                    <button type="button" id="approveAndNextBtn" onclick="approveCurrentAndNext()" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs sm:text-sm font-bold shadow-md transition flex items-center gap-2 cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">done_all</span>
                        <span id="approveAndNextBtnText">Approve & Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateFileNameDisplay(input, elementId) {
            const fileName = input.files[0] ? input.files[0].name : "Click to upload template";
            document.getElementById(elementId).innerText = fileName;
        }

        function switchTab(tabId, contentId) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(el => {
                el.classList.add('hidden');
                el.classList.remove('block');
            });
            // Reset tab button states
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('border-purple-600', 'text-purple-600');
                el.classList.add('text-slate-500', 'border-transparent');
            });
            
            // Show target content
            document.getElementById(contentId).classList.remove('hidden');
            document.getElementById(contentId).classList.add('block');
            
            // Set active button style
            document.getElementById(tabId).classList.add('border-purple-600', 'text-purple-600');
            document.getElementById(tabId).classList.remove('text-slate-500', 'border-transparent');

            filterSubmissions();
        }

        // --- FILTERING LOGIC ---
        function filterSubmissions() {
            const search = (document.getElementById('submissionSearch')?.value || '').toLowerCase().trim();
            const reqFilter = document.getElementById('filterRequirement')?.value || '';
            const courseFilter = document.getElementById('filterCourse')?.value || '';

            ['pending-content', 'approved-content', 'rejected-content'].forEach(tabId => {
                const container = document.getElementById(tabId);
                if (!container) return;

                const rows = container.querySelectorAll('tbody tr.submission-row');
                let visibleCount = 0;

                rows.forEach(row => {
                    const student = row.dataset.student || '';
                    const reqTitle = (row.dataset.requirementTitle || '').toLowerCase();
                    const reqId = row.dataset.requirementId || '';
                    const course = row.dataset.course || '';

                    const matchesSearch = !search || student.includes(search) || reqTitle.includes(search);
                    const matchesReq = !reqFilter || reqId === reqFilter;
                    const matchesCourse = !courseFilter || course === courseFilter;

                    const isVisible = matchesSearch && matchesReq && matchesCourse;
                    row.classList.toggle('hidden', !isVisible);

                    if (isVisible) visibleCount++;
                });

                // Check empty state
                const emptyRow = container.querySelector('tr[id$="-empty-row"]');
                const noMatchRow = container.querySelector('tr[id$="-no-match-row"]');

                if (rows.length === 0) {
                    if (emptyRow) emptyRow.classList.remove('hidden');
                    if (noMatchRow) noMatchRow.classList.add('hidden');
                } else if (visibleCount === 0) {
                    if (emptyRow) emptyRow.classList.add('hidden');
                    if (noMatchRow) noMatchRow.classList.remove('hidden');
                } else {
                    if (emptyRow) emptyRow.classList.add('hidden');
                    if (noMatchRow) noMatchRow.classList.add('hidden');
                }
            });

            // Reset master checkbox if visible rows changed
            const selectAll = document.getElementById('selectAllCheckbox');
            if (selectAll) selectAll.checked = false;
            updateBatchBar();

            const selectAllApproved = document.getElementById('selectAllApprovedCheckbox');
            if (selectAllApproved) selectAllApproved.checked = false;
            updateApprovedBatchBar();
        }

        function resetFilters() {
            const s = document.getElementById('submissionSearch');
            const r = document.getElementById('filterRequirement');
            const c = document.getElementById('filterCourse');
            if (s) s.value = '';
            if (r) r.value = '';
            if (c) c.value = '';
            filterSubmissions();
        }

        // --- BATCH APPROVE LOGIC ---
        function toggleSelectAll(master) {
            const visibleCheckboxes = document.querySelectorAll('#pending-content tbody .submission-row:not(.hidden) .sub-checkbox');
            visibleCheckboxes.forEach(cb => cb.checked = master.checked);
            updateBatchBar();
        }

        function handleRowCheckboxChange() {
            updateBatchBar();
        }

        function updateBatchBar() {
            const checked = document.querySelectorAll('#pending-content .sub-checkbox:checked');
            const batchBar = document.getElementById('batchActionBar');
            const text = document.getElementById('selectedCountText');
            const selectAll = document.getElementById('selectAllCheckbox');
            const visibleCheckboxes = document.querySelectorAll('#pending-content tbody .submission-row:not(.hidden) .sub-checkbox');

            if (selectAll && visibleCheckboxes.length > 0) {
                if (checked.length === visibleCheckboxes.length) {
                    selectAll.checked = true;
                    selectAll.indeterminate = false;
                } else if (checked.length > 0) {
                    selectAll.checked = false;
                    selectAll.indeterminate = true;
                } else {
                    selectAll.checked = false;
                    selectAll.indeterminate = false;
                }
            }

            if (checked.length > 0) {
                batchBar?.classList.remove('hidden');
                batchBar?.classList.add('flex');
                if (text) text.innerText = `${checked.length} submission${checked.length > 1 ? 's' : ''} selected`;
            } else {
                batchBar?.classList.add('hidden');
                batchBar?.classList.remove('flex');
            }
        }

        function clearSelectedSubmissions() {
            document.querySelectorAll('#pending-content .sub-checkbox').forEach(cb => cb.checked = false);
            updateBatchBar();
        }

        function confirmBatchApprove() {
            const checked = document.querySelectorAll('#pending-content .sub-checkbox:checked');
            if (checked.length === 0) return;
            const countText = document.getElementById('batchConfirmCountText');
            if (countText) countText.innerText = `${checked.length} submission${checked.length > 1 ? 's' : ''}`;
            document.getElementById('batchApproveModal')?.classList.remove('hidden');
        }

        function closeBatchModal() {
            document.getElementById('batchApproveModal')?.classList.add('hidden');
        }

        function submitBatchApprove() {
            const checked = document.querySelectorAll('#pending-content .sub-checkbox:checked');
            if (checked.length === 0) return;

            const container = document.getElementById('batchApproveInputs');
            if (!container) return;
            container.innerHTML = '';

            checked.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                container.appendChild(input);
            });

            const btn = document.getElementById('confirmBatchSubmitBtn');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="material-symbols-outlined text-base animate-spin">refresh</span> Approving...';
            }

            document.getElementById('batchApproveForm')?.submit();
        }

        // --- BATCH DOWNLOAD LOGIC ---
        function submitBatchDownload(containerId) {
            const container = document.getElementById(containerId);
            if (!container) return;

            const checkboxSelector = containerId === 'pending-content' ? '.sub-checkbox:checked' : '.approved-checkbox:checked';
            const checked = container.querySelectorAll(checkboxSelector);

            if (checked.length === 0) {
                alert('Please select at least one document to download.');
                return;
            }

            const downloadInputs = document.getElementById('batchDownloadInputs');
            if (!downloadInputs) return;
            downloadInputs.innerHTML = '';

            checked.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                downloadInputs.appendChild(input);
            });

            document.getElementById('batchDownloadForm')?.submit();
        }

        // --- APPROVED BATCH SELECTION LOGIC ---
        function toggleSelectAllApproved(master) {
            const visibleCheckboxes = document.querySelectorAll('#approved-content tbody .submission-row:not(.hidden) .approved-checkbox');
            visibleCheckboxes.forEach(cb => cb.checked = master.checked);
            updateApprovedBatchBar();
        }

        function handleApprovedRowCheckboxChange() {
            updateApprovedBatchBar();
        }

        function updateApprovedBatchBar() {
            const checked = document.querySelectorAll('#approved-content .approved-checkbox:checked');
            const batchBar = document.getElementById('approvedBatchActionBar');
            const text = document.getElementById('approvedSelectedCountText');
            const selectAll = document.getElementById('selectAllApprovedCheckbox');
            const visibleCheckboxes = document.querySelectorAll('#approved-content tbody .submission-row:not(.hidden) .approved-checkbox');

            if (selectAll && visibleCheckboxes.length > 0) {
                if (checked.length === visibleCheckboxes.length) {
                    selectAll.checked = true;
                    selectAll.indeterminate = false;
                } else if (checked.length > 0) {
                    selectAll.checked = false;
                    selectAll.indeterminate = true;
                } else {
                    selectAll.checked = false;
                    selectAll.indeterminate = false;
                }
            }

            if (checked.length > 0) {
                batchBar?.classList.remove('hidden');
                batchBar?.classList.add('flex');
                if (text) text.innerText = `${checked.length} document${checked.length > 1 ? 's' : ''} selected`;
            } else {
                batchBar?.classList.add('hidden');
                batchBar?.classList.remove('flex');
            }
        }

        function clearSelectedApproved() {
            document.querySelectorAll('#approved-content .approved-checkbox').forEach(cb => cb.checked = false);
            updateApprovedBatchBar();
        }

        // --- SPEED REVIEWER LIGHTBOX LOGIC ---
        let reviewQueue = [];
        let currentReviewIndex = 0;

        function getPendingQueue() {
            const rows = document.querySelectorAll('#pending-content tbody .submission-row:not(.hidden)');
            return Array.from(rows).map(row => ({
                id: parseInt(row.dataset.id),
                studentName: row.dataset.studentName || 'Student',
                course: row.dataset.course || '',
                requirementTitle: row.dataset.requirementTitle || 'Requirement',
                fileUrl: row.dataset.fileUrl || '',
                element: row
            })).filter(item => !isNaN(item.id));
        }

        function openSpeedReviewModal(id) {
            reviewQueue = getPendingQueue();
            if (reviewQueue.length === 0) {
                alert('No pending submissions available in the current view.');
                return;
            }

            const idx = reviewQueue.findIndex(item => item.id === id);
            currentReviewIndex = idx !== -1 ? idx : 0;

            renderCurrentReview();
            document.getElementById('reviewModal')?.classList.remove('hidden');
        }

        // Backward compatibility
        function openReviewModal(id, pdfUrl) {
            openSpeedReviewModal(id);
        }

        function renderCurrentReview() {
            if (reviewQueue.length === 0) {
                closeReviewModal();
                checkTableEmptyState();
                return;
            }

            if (currentReviewIndex < 0) currentReviewIndex = 0;
            if (currentReviewIndex >= reviewQueue.length) currentReviewIndex = reviewQueue.length - 1;

            const item = reviewQueue[currentReviewIndex];
            document.getElementById('modalStudentName').innerText = item.studentName;
            document.getElementById('modalStudentCourse').innerText = item.course || 'N/A';
            document.getElementById('modalRequirementTitle').innerText = item.requirementTitle;
            document.getElementById('reviewIframe').src = item.fileUrl + "#toolbar=0";
            document.getElementById('modalExternalLink').href = item.fileUrl;
            document.getElementById('reviewQueueCounter').innerText = `${currentReviewIndex + 1} of ${reviewQueue.length}`;

            document.getElementById('prevReviewBtn').disabled = (currentReviewIndex === 0);
            document.getElementById('nextReviewBtn').disabled = (currentReviewIndex >= reviewQueue.length - 1);
            document.getElementById('skipReviewBtn').disabled = (currentReviewIndex >= reviewQueue.length - 1);

            const isLast = (currentReviewIndex === reviewQueue.length - 1);
            document.getElementById('approveAndNextBtnText').innerText = isLast ? 'Approve Document' : 'Approve & Next';

            document.getElementById('reviewRejectBtn').onclick = function() {
                openRejectModal(item.id);
            };
        }

        function navigateReview(delta) {
            const newIdx = currentReviewIndex + delta;
            if (newIdx >= 0 && newIdx < reviewQueue.length) {
                currentReviewIndex = newIdx;
                renderCurrentReview();
            }
        }

        async function approveCurrentAndNext() {
            if (reviewQueue.length === 0) return;
            const item = reviewQueue[currentReviewIndex];
            const btn = document.getElementById('approveAndNextBtn');
            const originalText = document.getElementById('approveAndNextBtnText').innerText;

            btn.disabled = true;
            document.getElementById('approveAndNextBtnText').innerText = 'Approving...';

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const response = await fetch(`/coordinator/submissions/${item.id}/approve`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) throw new Error('Failed to approve submission');

                // Remove row from DOM
                if (item.element && item.element.parentNode) {
                    item.element.remove();
                }

                // Remove from queue
                reviewQueue.splice(currentReviewIndex, 1);

                // Update counter badges
                updateTabCounts(-1, 1, 0);
                updateBatchBar();

                if (reviewQueue.length === 0) {
                    closeReviewModal();
                    checkTableEmptyState();
                } else {
                    if (currentReviewIndex >= reviewQueue.length) {
                        currentReviewIndex = reviewQueue.length - 1;
                    }
                    renderCurrentReview();
                }
            } catch (err) {
                alert('An error occurred while approving the document. Please try again.');
                console.error(err);
            } finally {
                btn.disabled = false;
                if (reviewQueue.length > 0) {
                    const isLast = (currentReviewIndex === reviewQueue.length - 1);
                    document.getElementById('approveAndNextBtnText').innerText = isLast ? 'Approve Document' : 'Approve & Next';
                }
            }
        }

        function closeReviewModal() {
            document.getElementById('reviewModal')?.classList.add('hidden');
            document.getElementById('reviewIframe').src = "";
        }

        function openRejectModal(id) {
            document.getElementById('rejectForm').action = `/coordinator/submissions/${id}/reject`;
            document.getElementById('rejectModal')?.classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal')?.classList.add('hidden');
        }

        function updateTabCounts(deltaPending, deltaApproved, deltaRejected) {
            const pBadge = document.getElementById('pendingCountBadge');
            const aBadge = document.getElementById('approvedCountBadge');
            const rBadge = document.getElementById('rejectedCountBadge');

            if (pBadge) pBadge.innerText = Math.max(0, (parseInt(pBadge.innerText) || 0) + deltaPending);
            if (aBadge) aBadge.innerText = Math.max(0, (parseInt(aBadge.innerText) || 0) + deltaApproved);
            if (rBadge) rBadge.innerText = Math.max(0, (parseInt(rBadge.innerText) || 0) + deltaRejected);
        }

        function checkTableEmptyState() {
            const pendingRows = document.querySelectorAll('#pending-content tbody .submission-row');
            const emptyRow = document.getElementById('pending-empty-row');
            if (pendingRows.length === 0) {
                if (emptyRow) emptyRow.classList.remove('hidden');
                else {
                    const tbody = document.querySelector('#pending-content tbody');
                    if (tbody) {
                        tbody.innerHTML = '<tr id="pending-empty-row"><td colspan="5" class="py-12 text-center text-slate-400 font-medium italic">No pending submissions to verify.</td></tr>';
                    }
                }
            }
        }

        // Sidebar Toggling Code
        const sidebarEl = document.getElementById('sidebar');
        const overlayEl = document.getElementById('sidebar-overlay');
        const toggleBtnEl = document.getElementById('sidebar-toggle');

        function openSidebar() {
            sidebarEl?.classList.remove('-translate-x-full');
            overlayEl?.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
        function closeSidebar() {
            sidebarEl?.classList.add('-translate-x-full');
            overlayEl?.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
        toggleBtnEl?.addEventListener('click', () => {
            if (sidebarEl) {
                sidebarEl.classList.contains('-translate-x-full') ? openSidebar() : closeSidebar();
            }
        });

        const userMenuBtn = document.getElementById('user-menu-btn');
        const userMenuDropdown = document.getElementById('user-menu-dropdown');
        userMenuBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            userMenuDropdown?.classList.toggle('hidden');
        });
        document.addEventListener('click', (e) => {
            if (!userMenuDropdown?.contains(e.target) && !userMenuBtn?.contains(e.target)) {
                userMenuDropdown?.classList.add('hidden');
            }
        });
    </script>
</body>

</html>
