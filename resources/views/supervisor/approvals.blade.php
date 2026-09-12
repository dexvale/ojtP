<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>OJT Portal | Pending Approvals</title>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:opsz,wght@6..72,400;6..72,600;6..72,700;6..72,800&family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Public Sans', sans-serif; }
        .font-headline { font-family: 'Newsreader', serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .material-icon-filled { font-family: 'Material Symbols Outlined'; font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    </style>
</head>
<body class="bg-surface text-on-surface" data-theme="portal">

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 backdrop-blur-xs z-40 hidden lg:hidden transition-opacity"></div>

    <!-- SIDEBAR (Supervisor Version - Shared Component) -->
    @include('components.supervisor-sidebar')

    <!-- TOP NAVIGATION -->
    <header class="fixed top-0 lg:left-64 left-0 right-0 z-30 bg-[#fff7fd]/95 backdrop-blur-sm border-b border-[#cec3d0]/15">
        <div class="flex justify-between items-center px-4 md:px-8 py-3.5 w-full">
            <div class="flex items-center gap-3 md:gap-4">
                <button id="sidebar-toggle" class="lg:hidden p-2 text-[#300050] rounded-lg hover:bg-black/5 transition-colors active:scale-95" aria-label="Toggle Menu">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <span class="text-xl md:text-2xl font-headline font-semibold text-[#300050] tracking-tight">Industry Supervisor</span>
            </div>
            <div class="flex items-center gap-3 sm:gap-6">
                <!-- Notifications & Profile -->
                <div class="flex items-center gap-3">
                    <button class="p-2 text-[#300050] hover:bg-[#faf1f8] rounded-full transition-all active:scale-95" title="Notifications">
                        <span class="material-symbols-outlined">notifications</span>
                    </button>
                    <div class="relative flex items-center sm:pl-2 sm:border-l sm:border-[#cec3d0]/30" id="user-profile-menu">
                        <button type="button" id="user-menu-btn" class="flex items-center gap-3 cursor-pointer focus:outline-none" aria-expanded="false" aria-haspopup="true">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-bold font-headline text-[#300050]">{{ auth()->user()->display_name }}</p>
                                <p class="text-[10px] uppercase tracking-wider text-secondary font-bold">{{ auth()->user()->department ? auth()->user()->department . ' • ' : '' }}{{ auth()->user()->company->name ?? 'Supervisor' }}</p>
                            </div>
                            <img alt="User profile avatar"
                                class="w-8 h-8 sm:w-9 sm:h-9 rounded-full object-cover ring-2 ring-primary/10 hover:ring-primary transition-all flex-shrink-0"
                                src="{{ auth()->user()->avatar_url }}">
                        </button>
                        <!-- Dropdown Menu -->
                        @include('components.user-dropdown')
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="lg:ml-64 ml-0 pt-20 md:pt-24 px-4 sm:px-6 lg:px-8 pb-16 overflow-x-hidden">
        
        <div class="max-w-7xl mx-auto space-y-6">
            
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold font-headline text-primary tracking-tight">Pending Approvals</h1>
                    <p class="text-on-surface/60 font-medium text-xs sm:text-sm mt-1">Review daily task logs, validate intern hours, and inspect submitted workspace evidence.</p>
                </div>
            </div>

            <!-- Flash Notifications -->
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 sm:px-5 py-3.5 rounded-xl shadow-xs flex items-center gap-3">
                    <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                    <span class="text-xs sm:text-sm font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 sm:px-5 py-3.5 rounded-xl shadow-xs flex items-center gap-3">
                    <span class="material-symbols-outlined text-rose-600">error</span>
                    <span class="text-xs sm:text-sm font-semibold">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Status Tabs with Live Count Badges -->
            <div class="flex items-center border-b border-outline/15 overflow-x-auto gap-2 sm:gap-6 no-scrollbar">
                <a href="{{ route('supervisor.approvals', array_merge(request()->query(), ['status' => 'Pending', 'page' => 1])) }}" 
                   class="pb-3 px-1 text-xs sm:text-sm font-bold border-b-2 flex items-center gap-2 whitespace-nowrap transition-colors {{ $status === 'Pending' ? 'border-primary text-primary' : 'border-transparent text-on-surface/50 hover:text-primary' }}">
                    <span>Pending Queue</span>
                    <span class="px-2 py-0.5 text-[11px] font-black rounded-full {{ $status === 'Pending' ? 'bg-primary text-white' : 'bg-surface-container text-on-surface/60' }}">{{ $pendingCount }}</span>
                </a>
                <a href="{{ route('supervisor.approvals', array_merge(request()->query(), ['status' => 'Approved', 'page' => 1])) }}" 
                   class="pb-3 px-1 text-xs sm:text-sm font-bold border-b-2 flex items-center gap-2 whitespace-nowrap transition-colors {{ $status === 'Approved' ? 'border-primary text-primary' : 'border-transparent text-on-surface/50 hover:text-primary' }}">
                    <span>Approved Logs</span>
                    <span class="px-2 py-0.5 text-[11px] font-black rounded-full {{ $status === 'Approved' ? 'bg-emerald-600 text-white' : 'bg-surface-container text-on-surface/60' }}">{{ $approvedCount }}</span>
                </a>
                <a href="{{ route('supervisor.approvals', array_merge(request()->query(), ['status' => 'Rejected', 'page' => 1])) }}" 
                   class="pb-3 px-1 text-xs sm:text-sm font-bold border-b-2 flex items-center gap-2 whitespace-nowrap transition-colors {{ $status === 'Rejected' ? 'border-primary text-primary' : 'border-transparent text-on-surface/50 hover:text-primary' }}">
                    <span>Rejected / Revisions</span>
                    <span class="px-2 py-0.5 text-[11px] font-black rounded-full {{ $status === 'Rejected' ? 'bg-rose-600 text-white' : 'bg-surface-container text-on-surface/60' }}">{{ $rejectedCount }}</span>
                </a>
            </div>

            <!-- Action & Filter Bar -->
            <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2.5">
                    <!-- Intern Filter Dropdown -->
                    <div class="relative min-w-[170px] sm:min-w-[200px]">
                        <select id="intern-filter" onchange="applyFilters()" class="w-full appearance-none bg-white border border-outline/25 pl-9 pr-8 py-2 rounded-xl text-xs sm:text-sm font-bold text-on-surface/80 shadow-xs focus:ring-2 focus:ring-primary/20 focus:border-primary cursor-pointer outline-none transition-all">
                            <option value="">All Assigned Interns ({{ $interns->count() }})</option>
                            @foreach($interns as $intern)
                                <option value="{{ $intern->user_id }}" {{ $internId == $intern->user_id ? 'selected' : '' }}>
                                    {{ $intern->user->display_name }}
                                </option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface/40 text-[18px] pointer-events-none z-10">group</span>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface/40 text-[18px] pointer-events-none z-10">expand_more</span>
                    </div>

                    <!-- Sort Dropdown -->
                    <div class="relative min-w-[150px] sm:min-w-[170px]">
                        <select id="sort-filter" onchange="applyFilters()" class="w-full appearance-none bg-white border border-outline/25 pl-9 pr-8 py-2 rounded-xl text-xs sm:text-sm font-bold text-on-surface/80 shadow-xs focus:ring-2 focus:ring-primary/20 focus:border-primary cursor-pointer outline-none transition-all">
                            <option value="oldest_first" {{ $sortBy === 'oldest_first' ? 'selected' : '' }}>Oldest First</option>
                            <option value="newest_first" {{ $sortBy === 'newest_first' ? 'selected' : '' }}>Newest First</option>
                            <option value="intern_name" {{ $sortBy === 'intern_name' ? 'selected' : '' }}>Intern Name</option>
                            <option value="highest_hours" {{ $sortBy === 'highest_hours' ? 'selected' : '' }}>Highest Hours</option>
                        </select>
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface/40 text-[18px] pointer-events-none z-10">sort</span>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface/40 text-[18px] pointer-events-none z-10">expand_more</span>
                    </div>

                    @if($internId)
                        <a href="{{ route('supervisor.approvals', ['status' => $status, 'sort' => $sortBy]) }}" 
                           class="text-xs text-primary font-bold hover:underline inline-flex items-center gap-1 py-1">
                            <span class="material-symbols-outlined text-[14px]">close</span>
                            <span>Clear Filter</span>
                        </a>
                    @endif
                </div>

                <!-- Batch Approval Actions (When Pending) -->
                @if($status === 'Pending' && $logs->count() > 0)
                    <div class="flex items-center gap-2">
                        <button type="button" id="batch-approve-btn" onclick="submitBatchApprove()" disabled
                                class="opacity-40 cursor-not-allowed bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl px-4 py-2 text-xs sm:text-sm font-bold flex items-center justify-center gap-1.5 shadow-xs transition-all active:scale-95 w-full sm:w-auto">
                            <span class="material-symbols-outlined text-[18px]">done_all</span>
                            <span id="batch-approve-label">Approve Selected (0)</span>
                        </button>
                    </div>
                @endif
            </div>

            {{-- ── SELECT ALL RESULTS BANNER (shown when master checkbox is checked) ── --}}
            @if($status === 'Pending' && $logs->hasPages())
                <div id="select-all-results-banner" class="hidden items-center justify-center gap-3 px-4 py-2.5 bg-emerald-50 border border-emerald-200 rounded-xl text-sm text-emerald-800 font-medium">
                    <span class="material-symbols-outlined text-emerald-600 text-[18px] shrink-0">info</span>
                    <span id="select-all-banner-text">All <strong>{{ $logs->count() }}</strong> logs on this page are selected.</span>
                    <button type="button" id="select-all-results-btn" onclick="activateSelectAllResults()"
                            class="font-bold underline underline-offset-2 hover:text-emerald-900 transition cursor-pointer whitespace-nowrap">
                        Select all {{ $pendingCount }} pending logs
                    </button>
                    <span id="select-all-results-active" class="hidden items-center gap-1.5 text-emerald-700 font-bold">
                        <span class="material-symbols-outlined text-[16px]">check_circle</span>
                        All {{ $pendingCount }} pending logs selected.
                        <button type="button" onclick="deactivateSelectAllResults()" class="ml-1 text-xs underline font-normal cursor-pointer">Undo</button>
                    </span>
                </div>
            @endif

            <!-- ═══════════════════════════════════════════
                 DESKTOP DATA TABLE (Visible md and up)
            ══════════════════════════════════════════════ -->
            <div class="hidden md:block bg-white rounded-2xl border border-outline/20 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[760px]">
                        <thead>
                            <tr class="border-b border-outline/15 bg-surface-container/50 text-[11px] font-bold text-on-surface/60 uppercase tracking-wider">
                                @if($status === 'Pending')
                                    <th class="py-3.5 pl-5 pr-2 w-10">
                                        <div class="flex items-center justify-center">
                                            <input type="checkbox" id="select-all-checkbox" onchange="toggleSelectAll(this)"
                                                   class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 hover:border-emerald-500 cursor-pointer transition-colors duration-150"
                                                   title="Select all on this page"
                                                   aria-label="Select all pending log entries on this page">
                                        </div>
                                    </th>
                                @endif
                                <th class="py-3.5 px-4">Intern</th>
                                <th class="py-3.5 px-4">Log Date</th>
                                <th class="py-3.5 px-3">Hours</th>
                                <th class="py-3.5 px-4">Task Performed</th>
                                <th class="py-3.5 px-3 text-center">Evidence</th>
                                @if($status !== 'Pending')
                                    <th class="py-3.5 px-4">Status & Feedback</th>
                                @endif
                                <th class="py-3.5 px-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline/10 text-sm">
                            @forelse($logs as $log)
                                @php
                                    $internDisplayName = $log->user->display_name ?? 'Intern';
                                    $internInitials = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $internDisplayName) ?: 'IN', 0, 2));
                                    $logPayload = [
                                        'id' => $log->id,
                                        'intern_name' => $internDisplayName,
                                        'hours_rendered' => $log->hours_rendered,
                                        'status' => $log->status,
                                        'log_date' => $log->log_date->format('M d, Y'),
                                        'tasks_performed' => $log->tasks_performed,
                                        'photo_path' => $log->photo_path,
                                        'remarks' => $log->remarks,
                                    ];
                                @endphp
                                <tr id="log-row-{{ $log->id }}"
                                    class="log-table-row transition-colors duration-150 {{ $status === 'Pending' ? 'cursor-pointer select-none' : '' }} hover:bg-purple-50/20"
                                    @if($status === 'Pending') onclick="handleRowClick(event, {{ $log->id }})" @endif>
                                    @if($status === 'Pending')
                                        <td class="py-3.5 pl-5 pr-2">
                                            <div class="flex items-center justify-center">
                                                <input type="checkbox" name="log_checkbox" value="{{ $log->id }}"
                                                       id="cb-{{ $log->id }}"
                                                       onchange="updateBatchApproveState()"
                                                       class="log-checkbox w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 hover:border-emerald-500 cursor-pointer transition-colors duration-150"
                                                       aria-label="Select log entry for {{ $internDisplayName }} on {{ $log->log_date->format('M d, Y') }}">
                                            </div>
                                        </td>
                                    @endif

                                    <!-- Intern Column -->
                                    <td class="py-3.5 px-4">
                                        <div class="min-w-0">
                                            <p class="font-bold text-sm text-on-surface truncate">{{ $internDisplayName }}</p>
                                            <p class="text-[11px] text-on-surface/50 font-medium truncate">
                                                {{ $log->user->studentProfile?->student_id_number ? $log->user->studentProfile->student_id_number . ' • ' : '' }}{{ $log->user->studentProfile?->course ?? 'Intern' }}
                                            </p>
                                        </div>
                                    </td>

                                    <!-- Date Column -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <div class="font-bold text-xs sm:text-sm text-on-surface">
                                            {{ $log->log_date->format('M d, Y') }}
                                        </div>
                                        <p class="text-[10px] text-on-surface/45 font-medium">{{ $log->log_date->format('l') }}</p>
                                    </td>

                                    <!-- Hours Column -->
                                    <td class="py-3.5 px-3 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-black bg-purple-50 text-purple-900 border border-purple-100 font-headline shadow-2xs">
                                            {{ number_format($log->hours_rendered, 2) }} hrs
                                        </span>
                                    </td>

                                    <!-- Tasks Column -->
                                    <td class="py-3.5 px-4 max-w-xs lg:max-w-sm">
                                        <p class="text-xs text-on-surface/75 line-clamp-2 leading-relaxed" title="{{ $log->tasks_performed }}">
                                            {{ $log->tasks_performed }}
                                        </p>
                                        <button type="button" onclick="openDetailsModal({{ json_encode($logPayload) }})" 
                                                class="text-[11px] text-primary font-bold hover:underline mt-1 inline-flex items-center gap-0.5 cursor-pointer">
                                            <span>Full summary</span>
                                            <span class="material-symbols-outlined text-[13px]">open_in_new</span>
                                        </button>
                                    </td>

                                    <!-- Evidence Column -->
                                    <td class="py-3.5 px-3 text-center whitespace-nowrap">
                                        @if($log->photo_path)
                                            <button type="button" onclick="openLightbox('{{ asset('storage/' . $log->photo_path) }}', '{{ addslashes($internDisplayName) }} • {{ $log->log_date->format('M d, Y') }}')" 
                                                    class="inline-block relative group/img cursor-pointer" title="Click to view full photo">
                                                <img src="{{ asset('storage/' . $log->photo_path) }}" 
                                                     alt="Evidence" 
                                                     class="w-10 h-10 rounded-lg object-cover border border-outline/20 group-hover/img:ring-2 group-hover/img:ring-primary transition-all shadow-2xs">
                                                <span class="absolute inset-0 bg-black/35 rounded-lg opacity-0 group-hover/img:opacity-100 flex items-center justify-center text-white transition-opacity">
                                                    <span class="material-symbols-outlined text-[16px]">zoom_in</span>
                                                </span>
                                            </button>
                                        @else
                                            <span class="text-[11px] text-on-surface/35 font-medium italic">No photo</span>
                                        @endif
                                    </td>

                                    <!-- Status & Notes Column (for Approved / Rejected) -->
                                    @if($status !== 'Pending')
                                        <td class="py-3.5 px-4 max-w-xs">
                                            @if($status === 'Approved')
                                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                                                    <span class="material-symbols-outlined text-[13px]">check_circle</span> Approved
                                                </span>
                                                @if($log->remarks)
                                                    <p class="text-[11px] text-purple-900 mt-1 line-clamp-1 italic" title="{{ $log->remarks }}">"{{ $log->remarks }}"</p>
                                                @endif
                                            @elseif($status === 'Rejected')
                                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-rose-700 bg-rose-50 px-2 py-0.5 rounded-md border border-rose-200">
                                                    <span class="material-symbols-outlined text-[13px]">cancel</span> Needs Revision
                                                </span>
                                                @if($log->remarks)
                                                    <p class="text-[11px] text-rose-900 mt-1 line-clamp-1 italic" title="{{ $log->remarks }}">"{{ $log->remarks }}"</p>
                                                @endif
                                            @endif
                                            <p class="text-[10px] text-on-surface/40 mt-0.5">{{ $log->updated_at->format('M d, Y h:i A') }}</p>
                                        </td>
                                    @endif

                                    <!-- Actions Column -->
                                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                        @if($status === 'Pending')
                                            <div class="flex items-center justify-end gap-1.5">
                                                <!-- Quick Approve Form -->
                                                <form method="POST" action="{{ route('supervisor.logs.approve', $log) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition shadow-xs inline-flex items-center gap-1 active:scale-95 cursor-pointer" title="Approve Log">
                                                        <span class="material-symbols-outlined text-[16px]">check</span>
                                                        <span>Approve</span>
                                                    </button>
                                                </form>

                                                <!-- Quick Reject Button (Opens Modal) -->
                                                <button type="button" onclick="openRejectModal({{ $log->id }}, '{{ addslashes($internDisplayName) }}', '{{ $log->log_date->format('M d, Y') }}', '{{ number_format($log->hours_rendered, 2) }}')" 
                                                        class="px-2.5 py-1.5 bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 rounded-lg text-xs font-bold transition shadow-xs inline-flex items-center gap-1 active:scale-95 cursor-pointer" title="Reject & Request Revision">
                                                        <span class="material-symbols-outlined text-[16px]">undo</span>
                                                        <span>Reject</span>
                                                </button>
                                            </div>
                                        @else
                                            <button type="button" onclick="openDetailsModal({{ json_encode($logPayload) }})" 
                                                    class="px-3 py-1.5 bg-surface-container hover:bg-black/5 text-on-surface/70 rounded-lg text-xs font-bold transition inline-flex items-center gap-1 cursor-pointer">
                                                <span class="material-symbols-outlined text-[15px]">visibility</span>
                                                <span>Inspect</span>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $status === 'Pending' ? 7 : 7 }}" class="py-12 px-6 text-center text-on-surface/50">
                                        <span class="material-symbols-outlined text-4xl text-outline/40 mb-2 block">fact_check</span>
                                        <p class="font-bold text-sm text-on-surface/70">No log entries found</p>
                                        <p class="text-xs text-on-surface/50 mt-0.5">There are no logs matching the '{{ $status }}' status filter.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ═══════════════════════════════════════════
                 MOBILE CARDS VIEW (Visible on screens < md)
            ══════════════════════════════════════════════ -->
            <div class="md:hidden space-y-3">
                @forelse($logs as $log)
                    @php
                        $internDisplayName = $log->user->display_name ?? 'Intern';
                        $internInitials = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $internDisplayName) ?: 'IN', 0, 2));
                        $logPayload = [
                            'id' => $log->id,
                            'intern_name' => $internDisplayName,
                            'hours_rendered' => $log->hours_rendered,
                            'status' => $log->status,
                            'log_date' => $log->log_date->format('M d, Y'),
                            'tasks_performed' => $log->tasks_performed,
                            'photo_path' => $log->photo_path,
                            'remarks' => $log->remarks,
                        ];
                    @endphp
                    <div id="mobile-card-{{ $log->id }}" class="log-mobile-card bg-white rounded-2xl border border-outline/20 p-4 shadow-xs space-y-3 transition-all duration-150">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex items-center gap-2.5 min-w-0">
                                @if($status === 'Pending')
                                    <div class="flex items-center justify-center shrink-0">
                                        <input type="checkbox" name="log_checkbox" value="{{ $log->id }}"
                                               id="mobile-cb-{{ $log->id }}"
                                               onchange="updateBatchApproveState()"
                                               class="log-checkbox w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 hover:border-emerald-500 cursor-pointer transition-colors duration-150 shrink-0"
                                               aria-label="Select log entry for {{ $internDisplayName }} on {{ $log->log_date->format('M d, Y') }}">
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <h3 class="font-bold text-sm text-on-surface truncate">{{ $internDisplayName }}</h3>
                                    <p class="text-[10px] text-on-surface/50 font-semibold truncate">
                                        {{ $log->user->studentProfile?->student_id_number ? $log->user->studentProfile->student_id_number . ' • ' : '' }}{{ $log->user->studentProfile?->course ?? 'Intern' }}
                                    </p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-black bg-purple-50 text-purple-900 border border-purple-100 shrink-0 font-headline">
                                {{ number_format($log->hours_rendered, 2) }} hrs
                            </span>
                        </div>

                        <!-- Date & Evidence preview -->
                        <div class="flex items-center justify-between text-xs text-on-surface/70 bg-surface-container/50 px-3 py-2 rounded-xl">
                            <div class="font-bold">
                                {{ $log->log_date->format('M d, Y') }} ({{ $log->log_date->format('D') }})
                            </div>
                            @if($log->photo_path)
                                <button type="button" onclick="openLightbox('{{ asset('storage/' . $log->photo_path) }}', '{{ addslashes($internDisplayName) }} • {{ $log->log_date->format('M d, Y') }}')"
                                        class="text-primary font-bold text-[11px] inline-flex items-center gap-1 hover:underline cursor-pointer">
                                    <span class="material-symbols-outlined text-[14px]">image</span>
                                    <span>Photo</span>
                                </button>
                            @else
                                <span class="text-[10px] text-on-surface/40 italic">No photo</span>
                            @endif
                        </div>

                        <!-- Tasks preview -->
                        <div>
                            <p class="text-xs text-on-surface/70 line-clamp-2 leading-relaxed">
                                {{ $log->tasks_performed }}
                            </p>
                            <button type="button" onclick="openDetailsModal({{ json_encode($logPayload) }})"
                                    class="text-[11px] text-primary font-bold hover:underline mt-1 inline-flex items-center gap-0.5 cursor-pointer">
                                <span>View full details</span>
                                <span class="material-symbols-outlined text-[13px]">chevron_right</span>
                            </button>
                        </div>

                        <!-- Status or Revision remarks -->
                        @if($status === 'Approved')
                            <div class="text-[11px] bg-emerald-50 text-emerald-800 p-2.5 rounded-xl border border-emerald-100 flex items-center justify-between">
                                <span class="font-bold flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">check_circle</span> Approved
                                </span>
                                <span class="text-[10px] opacity-75">{{ $log->updated_at->format('M d, Y') }}</span>
                            </div>
                        @elseif($status === 'Rejected')
                            <div class="text-[11px] bg-rose-50 text-rose-800 p-2.5 rounded-xl border border-rose-100 space-y-1">
                                <span class="font-bold flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">cancel</span> Revision Requested
                                </span>
                                @if($log->remarks)
                                    <p class="text-[11px] italic text-rose-900">"{{ $log->remarks }}"</p>
                                @endif
                            </div>
                        @endif

                        <!-- Mobile Action Buttons -->
                        @if($status === 'Pending')
                            <div class="grid grid-cols-2 gap-2 pt-1 border-t border-outline/10">
                                <button type="button" onclick="openRejectModal({{ $log->id }}, '{{ addslashes($internDisplayName) }}', '{{ $log->log_date->format('M d, Y') }}', '{{ number_format($log->hours_rendered, 2) }}')"
                                        class="py-2 px-3 bg-white border border-rose-200 text-rose-600 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1 active:scale-95 cursor-pointer">
                                    <span class="material-symbols-outlined text-[16px]">undo</span>
                                    <span>Reject</span>
                                </button>
                                <form method="POST" action="{{ route('supervisor.logs.approve', $log) }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full py-2 px-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-xs flex items-center justify-center gap-1 active:scale-95 cursor-pointer">
                                        <span class="material-symbols-outlined text-[16px]">check</span>
                                        <span>Approve</span>
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="bg-white rounded-2xl border border-outline/20 p-8 text-center text-on-surface/50">
                        <span class="material-symbols-outlined text-4xl text-outline/40 mb-2 block">fact_check</span>
                        <p class="font-bold text-sm text-on-surface/70">No entries found</p>
                        <p class="text-xs text-on-surface/50 mt-0.5">There are no log entries matching the '{{ $status }}' status.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="pt-2">
                {{ $logs->links() }}
            </div>

        </div>
    </main>

    <!-- ═══════════════════════════════════════════
         HIDDEN BATCH APPROVE FORM
    ══════════════════════════════════════════════ -->
    <form id="batch-approve-form" method="POST" action="{{ route('supervisor.logs.batchApprove') }}" class="hidden">
        @csrf
        {{-- select_all flag: set to 1 when approving all results across all pages --}}
        <input type="hidden" name="select_all" id="batch-select-all-flag" value="0">
        {{-- Pass active intern filter so the server scopes it correctly --}}
        @if($internId)
            <input type="hidden" name="intern_id" value="{{ $internId }}">
        @endif
    </form>

    <!-- ═══════════════════════════════════════════
         REJECTION FEEDBACK MODAL
    ══════════════════════════════════════════════ -->
    <div id="rejectModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4 overflow-y-auto" onclick="handleRejectModalOverlay(event)">
        <div class="bg-white rounded-2xl shadow-2xl border border-outline/20 p-5 sm:p-6 w-full max-w-md mx-auto my-auto animate-in fade-in zoom-in-95 duration-200" onclick="event.stopPropagation()">
            <div class="flex items-start justify-between gap-3 mb-4">
                <div>
                    <h3 class="text-lg font-bold font-headline text-rose-700 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-rose-600">assignment_return</span>
                        <span>Request Revision</span>
                    </h3>
                    <p class="text-xs text-on-surface/60 mt-0.5" id="reject-intern-subtitle">Provide mandatory feedback for the intern.</p>
                </div>
                <button type="button" onclick="closeRejectModal()" class="p-1.5 rounded-lg text-outline hover:text-on-surface hover:bg-surface-container transition cursor-pointer">
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>
            </div>

            <form id="reject-form-element" method="POST" action="">
                @csrf
                <div class="space-y-3">
                    <div class="bg-surface-container/60 p-3 rounded-xl text-xs space-y-1">
                        <div class="flex justify-between">
                            <span class="text-on-surface/50 font-semibold">Intern:</span>
                            <span class="font-bold text-on-surface" id="reject-modal-name">—</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-on-surface/50 font-semibold">Log Date:</span>
                            <span class="font-bold text-on-surface" id="reject-modal-date">—</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-on-surface/50 font-semibold">Hours:</span>
                            <span class="font-bold text-primary" id="reject-modal-hours">—</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-on-surface/70 mb-1.5">
                            Supervisor Feedback / Revision Instructions <span class="text-rose-600">*</span>
                        </label>
                        <textarea name="remarks" id="reject-remarks-input" rows="4" required maxlength="50000"
                                  class="w-full bg-slate-50 border border-outline/30 rounded-xl p-3 text-xs sm:text-sm text-on-surface placeholder:text-on-surface/40 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition resize-y"
                                  placeholder="State clearly what needs revision (e.g. incomplete task details, inaccurate time logged, missing attachment)..."></textarea>
                    </div>
                </div>

                <div class="flex gap-2.5 mt-5">
                    <button type="button" onclick="closeRejectModal()"
                            class="flex-1 py-2.5 px-4 rounded-xl border border-outline/30 text-on-surface/70 text-xs sm:text-sm font-bold hover:bg-surface-container transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 px-4 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs sm:text-sm font-bold shadow-xs transition active:scale-95 cursor-pointer flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">send</span>
                        <span>Send Revision</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════
         LOG DETAILS & INSPECTION MODAL
    ══════════════════════════════════════════════ -->
    <div id="detailsModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4 overflow-y-auto" onclick="handleDetailsModalOverlay(event)">
        <div class="bg-white rounded-2xl shadow-2xl border border-outline/20 p-5 sm:p-6 w-full max-w-lg mx-auto my-auto max-h-[90dvh] overflow-y-auto animate-in fade-in zoom-in-95 duration-200" onclick="event.stopPropagation()">
            <div class="flex items-start justify-between gap-3 mb-4 pb-3 border-b border-outline/10">
                <div>
                    <h3 class="text-lg font-bold font-headline text-primary flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-primary">description</span>
                        <span id="details-modal-title">Intern Daily Log Details</span>
                    </h3>
                    <p class="text-xs text-on-surface/60 mt-0.5" id="details-modal-subtitle">—</p>
                </div>
                <button type="button" onclick="closeDetailsModal()" class="p-1.5 rounded-lg text-outline hover:text-on-surface hover:bg-surface-container transition cursor-pointer">
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>
            </div>

            <div class="space-y-4">
                <!-- Info Badges -->
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div class="bg-surface-container/60 p-3 rounded-xl">
                        <span class="text-[10px] uppercase font-bold text-on-surface/50 tracking-wider block mb-0.5">Hours Rendered</span>
                        <span class="font-extrabold text-sm text-primary font-headline" id="details-modal-hours">—</span>
                    </div>
                    <div class="bg-surface-container/60 p-3 rounded-xl">
                        <span class="text-[10px] uppercase font-bold text-on-surface/50 tracking-wider block mb-0.5">Log Status</span>
                        <span class="font-extrabold text-sm" id="details-modal-status">—</span>
                    </div>
                </div>

                <!-- Task Description -->
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-on-surface/70 block mb-1.5">Tasks Performed & Accomplishments</span>
                    <div class="bg-slate-50 border border-outline/20 rounded-xl p-3.5 max-h-48 overflow-y-auto">
                        <p class="text-xs sm:text-sm text-on-surface/80 whitespace-pre-wrap leading-relaxed" id="details-modal-tasks">—</p>
                    </div>
                </div>

                <!-- Evidence Photo Preview -->
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-on-surface/70 block mb-1.5">Workspace Evidence Photo</span>
                    <div id="details-modal-photo-container" class="bg-slate-50 border border-outline/20 rounded-xl overflow-hidden flex items-center justify-center p-2 min-h-[140px]">
                        <!-- Populated by JS -->
                    </div>
                </div>

                <!-- Existing Remarks (if any) -->
                <div id="details-modal-remarks-section" class="hidden">
                    <span class="text-xs font-bold uppercase tracking-wider text-on-surface/70 block mb-1.5">Supervisor Review Remarks</span>
                    <div class="bg-purple-50 border border-purple-200 text-purple-900 rounded-xl p-3 text-xs leading-relaxed" id="details-modal-remarks">
                        —
                    </div>
                </div>
            </div>

            <div class="flex gap-2.5 mt-6 pt-3 border-t border-outline/10">
                <button type="button" onclick="closeDetailsModal()"
                        class="w-full py-2.5 px-4 rounded-xl border border-outline/30 text-on-surface/70 text-xs sm:text-sm font-bold hover:bg-surface-container transition cursor-pointer">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════
         FULL EVIDENCE IMAGE LIGHTBOX
    ══════════════════════════════════════════════ -->
    <div id="lightboxModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-black/85 backdrop-blur-md p-4 cursor-zoom-out" onclick="closeLightbox()">
        <div class="relative max-w-4xl max-h-[92dvh] flex flex-col items-center cursor-default" onclick="event.stopPropagation()">
            <button type="button" onclick="closeLightbox()" class="absolute -top-10 right-0 p-1.5 rounded-full bg-white/20 hover:bg-white/40 text-white transition cursor-pointer" title="Close">
                <span class="material-symbols-outlined text-2xl">close</span>
            </button>
            <img id="lightbox-image" src="" alt="Evidence Full Preview" class="max-w-full max-h-[82dvh] object-contain rounded-xl shadow-2xl">
            <p id="lightbox-caption" class="text-white/80 text-xs font-medium mt-3 text-center"></p>
        </div>
    </div>

    <script>
        // ── Responsive Sidebar Toggle ──
        const sidebarEl = document.getElementById('sidebar');
        const overlayEl = document.getElementById('sidebar-overlay');
        const toggleBtnEl = document.getElementById('sidebar-toggle');

        function toggleSidebar() {
            sidebarEl.classList.toggle('-translate-x-full');
            overlayEl.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        }

        function closeSidebar() {
            sidebarEl?.classList.add('-translate-x-full');
            overlayEl?.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        toggleBtnEl?.addEventListener('click', toggleSidebar);
        overlayEl?.addEventListener('click', closeSidebar);

        // ── User Dropdown ──
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

        // ── Filter Application ──
        function applyFilters() {
            const internId = document.getElementById('intern-filter').value;
            const sort = document.getElementById('sort-filter').value;
            const params = new URLSearchParams(window.location.search);
            
            if (internId) {
                params.set('intern_id', internId);
            } else {
                params.delete('intern_id');
            }
            
            if (sort) {
                params.set('sort', sort);
            }
            
            params.set('page', 1); // Reset to page 1 on filter
            window.location.href = `${window.location.pathname}?${params.toString()}`;
        }

        // ── Select All Results across pages ──
        let _selectAllResultsActive = false;
        const _totalPendingCount = {{ $pendingCount ?? 0 }};
        const _pageCount = {{ $logs->count() }};

        function activateSelectAllResults() {
            _selectAllResultsActive = true;
            document.getElementById('select-all-results-btn')?.classList.add('hidden');
            document.getElementById('select-all-banner-text')?.classList.add('hidden');
            document.getElementById('select-all-results-active')?.classList.remove('hidden');
            document.getElementById('select-all-results-active')?.classList.add('flex');
            // Update button label to show the total count
            const batchLabel = document.getElementById('batch-approve-label');
            if (batchLabel) batchLabel.innerText = `Approve All (${_totalPendingCount})`;
        }

        function deactivateSelectAllResults() {
            _selectAllResultsActive = false;
            document.getElementById('select-all-results-btn')?.classList.remove('hidden');
            document.getElementById('select-all-banner-text')?.classList.remove('hidden');
            document.getElementById('select-all-results-active')?.classList.add('hidden');
            document.getElementById('select-all-results-active')?.classList.remove('flex');
            // Revert button label to page selection count
            const count = getCheckedValues().size;
            const batchLabel = document.getElementById('batch-approve-label');
            if (batchLabel) batchLabel.innerText = `Approve Selected (${count})`;
        }

        // ── Row Click Toggle (clicks on row body toggle the checkbox; excludes action buttons/links) ──
        function handleRowClick(event, logId) {
            const excluded = ['BUTTON', 'A', 'INPUT', 'LABEL', 'FORM', 'SPAN'];
            let el = event.target;
            while (el && el !== event.currentTarget) {
                if (excluded.includes(el.tagName)) return;
                el = el.parentElement;
            }
            const cb = document.getElementById('cb-' + logId);
            if (cb) {
                cb.checked = !cb.checked;
                syncCheckboxesByValue(logId, cb.checked);
                updateBatchApproveState();
            }
        }

        // ── Sync desktop + mobile checkboxes for the same log ID ──
        function syncCheckboxesByValue(value, isChecked) {
            document.querySelectorAll(`.log-checkbox[value="${value}"]`).forEach(cb => {
                cb.checked = isChecked;
            });
        }

        // ── Row highlight based on checkbox state (deduped by value) ──
        function applyRowHighlights() {
            const seen = new Set();
            document.querySelectorAll('.log-checkbox').forEach(cb => {
                if (seen.has(cb.value)) return;
                seen.add(cb.value);
                const row = document.getElementById('log-row-' + cb.value);
                const mobileCard = document.getElementById('mobile-card-' + cb.value);
                if (cb.checked) {
                    row?.classList.add('bg-emerald-50/60');
                    row?.classList.remove('hover:bg-purple-50/20');
                    mobileCard?.classList.add('bg-emerald-50/60', 'border-emerald-300', 'ring-1', 'ring-emerald-200');
                    mobileCard?.classList.remove('border-outline/20');
                } else {
                    row?.classList.remove('bg-emerald-50/60');
                    row?.classList.add('hover:bg-purple-50/20');
                    mobileCard?.classList.remove('bg-emerald-50/60', 'border-emerald-300', 'ring-1', 'ring-emerald-200');
                    mobileCard?.classList.add('border-outline/20');
                }
            });
        }

        // ── Get unique checked values (deduped across desktop + mobile) ──
        function getCheckedValues() {
            const values = new Set();
            document.querySelectorAll('.log-checkbox:checked').forEach(cb => values.add(cb.value));
            return values;
        }

        // ── Get unique total values ──
        function getTotalUniqueValues() {
            const values = new Set();
            document.querySelectorAll('.log-checkbox').forEach(cb => values.add(cb.value));
            return values;
        }

        // ── Batch Approval Selection ──
        function toggleSelectAll(selectAllCheckbox) {
            document.querySelectorAll('.log-checkbox').forEach(cb => cb.checked = selectAllCheckbox.checked);
            applyRowHighlights();
            // Show/hide the select-all-results banner
            const banner = document.getElementById('select-all-results-banner');
            if (banner) {
                if (selectAllCheckbox.checked) {
                    banner.classList.remove('hidden');
                    banner.classList.add('flex');
                } else {
                    banner.classList.add('hidden');
                    banner.classList.remove('flex');
                    deactivateSelectAllResults();
                }
            }
            updateBatchApproveState();
        }

        function updateBatchApproveState() {
            // Sync all duplicate checkboxes (desktop ↔ mobile) first
            document.querySelectorAll('.log-checkbox').forEach(cb => {
                syncCheckboxesByValue(cb.value, cb.checked);
            });

            applyRowHighlights();

            const checkedValues = getCheckedValues();
            const totalValues = getTotalUniqueValues();
            const selectAllCheckbox = document.getElementById('select-all-checkbox');
            const batchBtn = document.getElementById('batch-approve-btn');
            const batchLabel = document.getElementById('batch-approve-label');

            const count = checkedValues.size;
            const total = totalValues.size;

            // ── Indeterminate state ──
            if (selectAllCheckbox && total > 0) {
                if (count === 0) {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                } else if (count === total) {
                    selectAllCheckbox.checked = true;
                    selectAllCheckbox.indeterminate = false;
                } else {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = true;
                }
            }

            // ── Approve button dynamic state ──
            if (batchBtn && batchLabel) {
                batchLabel.innerText = `Approve Selected (${count})`;
                if (count > 0) {
                    batchBtn.disabled = false;
                    batchBtn.classList.remove('opacity-40', 'cursor-not-allowed', 'pointer-events-none');
                    batchBtn.classList.add('cursor-pointer', 'shadow-sm');
                } else {
                    batchBtn.disabled = true;
                    batchBtn.classList.add('opacity-40', 'cursor-not-allowed', 'pointer-events-none');
                    batchBtn.classList.remove('cursor-pointer', 'shadow-sm');
                }
            }
        }

        function submitBatchApprove() {
            const checkedValues = getCheckedValues();
            const count = _selectAllResultsActive ? _totalPendingCount : checkedValues.size;
            if (count === 0) return;

            const confirmMsg = _selectAllResultsActive
                ? `Approve ALL ${_totalPendingCount} pending intern daily logs across all pages?`
                : `Are you sure you want to approve ${count} selected intern daily log(s)?`;

            if (!confirm(confirmMsg)) return;

            const form = document.getElementById('batch-approve-form');
            // Remove any previously added log_ids inputs (keep CSRF + hidden flags)
            form.querySelectorAll('input[name="log_ids[]"]').forEach(el => el.remove());

            const flagInput = document.getElementById('batch-select-all-flag');

            if (_selectAllResultsActive) {
                // Signal server to approve ALL results
                if (flagInput) flagInput.value = '1';
            } else {
                // Normal mode: send selected IDs only
                if (flagInput) flagInput.value = '0';
                checkedValues.forEach(value => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'log_ids[]';
                    input.value = value;
                    form.appendChild(input);
                });
            }

            form.submit();
        }

        // ── Rejection Modal ──
        function openRejectModal(logId, internName, logDate, hours) {
            const form = document.getElementById('reject-form-element');
            form.action = `/supervisor/logs/${logId}/reject`;

            document.getElementById('reject-modal-name').innerText = internName;
            document.getElementById('reject-modal-date').innerText = logDate;
            document.getElementById('reject-modal-hours').innerText = `${hours} hrs`;
            document.getElementById('reject-remarks-input').value = '';

            document.getElementById('rejectModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            setTimeout(() => document.getElementById('reject-remarks-input').focus(), 100);
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function handleRejectModalOverlay(e) {
            if (e.target.id === 'rejectModal') closeRejectModal();
        }

        // ── Details Modal ──
        function openDetailsModal(log) {
            const internName = log.intern_name || log.user?.name || 'Intern';
            document.getElementById('details-modal-subtitle').innerText = `${internName} • Log ID #${log.id}`;
            document.getElementById('details-modal-hours').innerText = `${parseFloat(log.hours_rendered).toFixed(2)} Hours`;
            
            const statusEl = document.getElementById('details-modal-status');
            statusEl.innerText = log.status;
            if (log.status === 'Approved') {
                statusEl.className = 'font-extrabold text-sm text-emerald-600';
            } else if (log.status === 'Rejected') {
                statusEl.className = 'font-extrabold text-sm text-rose-600';
            } else {
                statusEl.className = 'font-extrabold text-sm text-amber-600';
            }

            document.getElementById('details-modal-tasks').innerText = log.tasks_performed || 'No detailed description provided.';

            const photoContainer = document.getElementById('details-modal-photo-container');
            if (log.photo_path) {
                photoContainer.innerHTML = `
                    <img src="/storage/${log.photo_path}" 
                         alt="Evidence" 
                         class="max-h-64 object-contain rounded-lg shadow-sm cursor-pointer hover:opacity-90 transition"
                         onclick="openLightbox('/storage/${log.photo_path}', '${internName.replace(/'/g, "\\'")} • Evidence')">
                `;
            } else {
                photoContainer.innerHTML = `
                    <div class="text-center p-6 text-on-surface/40">
                        <span class="material-symbols-outlined text-3xl mb-1 block">image_not_supported</span>
                        <span class="text-xs font-medium">No photo attachment uploaded for this log.</span>
                    </div>
                `;
            }

            const remarksSection = document.getElementById('details-modal-remarks-section');
            const remarksText = document.getElementById('details-modal-remarks');
            if (log.remarks) {
                remarksSection.classList.remove('hidden');
                remarksText.innerText = log.remarks;
            } else {
                remarksSection.classList.add('hidden');
            }

            document.getElementById('detailsModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeDetailsModal() {
            document.getElementById('detailsModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function handleDetailsModalOverlay(e) {
            if (e.target.id === 'detailsModal') closeDetailsModal();
        }

        // ── Evidence Lightbox ──
        function openLightbox(imageUrl, caption) {
            document.getElementById('lightbox-image').src = imageUrl;
            document.getElementById('lightbox-caption').innerText = caption || '';
            document.getElementById('lightboxModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeLightbox() {
            document.getElementById('lightboxModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // ── Keyboard Escape Closes All Modals ──
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeRejectModal();
                closeDetailsModal();
                closeLightbox();
            }
        });
    </script>
</body>
</html>
