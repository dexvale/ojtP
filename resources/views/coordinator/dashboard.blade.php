<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>OJT Portal | Coordinator Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link
        href="https://fonts.googleapis.com/css2?family=Newsreader:opsz,wght@6..72,400;6..72,600;6..72,700;6..72,800&family=Public+Sans:wght@400;500;600&display=swap"
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
                    <a class="text-sm font-semibold text-primary border-b-2 border-secondary pb-1" href="{{ route('coordinator.dashboard') }}">Dashboard</a>
                    <a class="text-sm font-semibold text-on-surface/60 hover:text-primary transition-colors duration-200" href="{{ route('coordinator.students') }}">Student List</a>
                    <a class="text-sm font-semibold text-on-surface/60 hover:text-primary transition-colors duration-200" href="{{ route('coordinator.companies') }}">Company Directory</a>
                    <a class="text-sm font-semibold text-on-surface/60 hover:text-primary transition-colors duration-200" href="{{ route('coordinator.reports') }}">Reports</a>
                </nav>
            </div>
            <div class="flex items-center gap-3 sm:gap-6">
                <div class="relative hidden sm:block">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface/60 text-sm" data-icon="search">search</span>
                    <input class="bg-surface-container-low border-none rounded-full py-2 pl-10 pr-4 text-sm w-44 lg:w-64 focus:ring-1 focus:ring-primary/20" placeholder="Search..." type="text">
                </div>
                <div class="flex items-center gap-2 sm:gap-3">
                    <button class="p-2 text-primary hover:bg-surface-container-low rounded-full transition-all active:scale-95">
                        <span class="material-symbols-outlined" data-icon="notifications">notifications</span>
                    </button>
                    <div class="relative flex items-center sm:pl-2 sm:border-l sm:border-outline/20" id="user-profile-menu">
                        <button type="button" id="user-menu-btn" class="flex items-center gap-3 cursor-pointer focus:outline-none" aria-expanded="false" aria-haspopup="true">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-bold font-headline text-primary">{{ auth()->user()->display_name }}</p>
                                <p class="text-[10px] uppercase tracking-wider text-secondary font-bold">{{ auth()->user()->display_role }}</p>
                            </div>
                            <img alt="User profile avatar" class="w-9 h-9 rounded-full object-cover ring-2 ring-primary/10 hover:ring-primary transition-all" src="{{ auth()->user()->avatar_url }}">
                        </button>
                        <!-- Dropdown Menu -->
                        <div id="user-menu-dropdown" class="hidden absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-1.5 z-50">
                            <div class="px-4 py-2 border-b border-slate-100 sm:hidden">
                                <p class="text-xs font-bold text-slate-800 truncate">{{ auth()->user()->display_name }}</p>
                                <p class="text-[10px] uppercase tracking-wider text-slate-500 font-semibold truncate">{{ auth()->user()->display_role }}</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-xs sm:text-sm text-red-600 hover:bg-red-50 flex items-center gap-2.5 font-semibold transition-colors cursor-pointer">
                                    <span class="material-symbols-outlined text-[18px]">logout</span>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="lg:ml-64 ml-0 pt-20 md:pt-24 px-4 sm:px-6 lg:px-8 pb-12 min-h-screen">
        <!-- Dashboard Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-6 sm:mb-8">
            <div>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold font-headline text-on-surface tracking-tight">Coordinator Dashboard</h2>
                <p class="text-on-surface/60 font-medium text-xs sm:text-sm mt-1">Monitoring student internship progress & verified requirements</p>
            </div>
            @if(isset($allTerms) && $allTerms->isNotEmpty())
                <form method="GET" action="{{ route('coordinator.dashboard') }}" class="flex items-center gap-2 bg-white px-3.5 py-2 rounded-xl border border-purple-100 shadow-xs w-full sm:w-auto">
                    <span class="material-symbols-outlined text-purple-700 text-sm shrink-0">calendar_month</span>
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider shrink-0">Term:</label>
                    <div class="relative flex-1 sm:flex-initial min-w-0">
                        <select name="term_id" onchange="this.form.submit()"
                                class="w-full bg-purple-50/50 border border-purple-200 rounded-lg px-3 py-1.5 text-xs font-bold text-[#300050] focus:ring-2 focus:ring-purple-500 focus:outline-none appearance-none pr-7 cursor-pointer truncate">
                            @foreach($allTerms as $t)
                                <option value="{{ $t->id }}" {{ ($selectedTermId == $t->id) ? 'selected' : '' }}>
                                    {{ $t->full_title }} {{ $t->is_active ? '★ (Active)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none">expand_more</span>
                    </div>
                </form>
            @endif
        </div>

        <!-- Overview Bento Grid -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mb-8 sm:mb-10">
            <div class="bg-surface-container p-5 sm:p-6 rounded-lg border-l-4 border-primary shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-primary/10 rounded-lg text-primary">
                        <span class="material-symbols-outlined" data-icon="groups">groups</span>
                    </div>
                    <span class="text-tertiary font-bold text-xs flex items-center gap-1 bg-tertiary/10 px-2.5 py-1 rounded-full">
                        <span class="material-symbols-outlined text-[14px]" data-icon="trending_up">trending_up</span>
                        {{ $placementRate }}% Placed
                    </span>
                </div>
                <div>
                    <h3 class="text-3xl sm:text-4xl font-extrabold font-headline text-on-surface">{{ $activeStudentsCount }}</h3>
                    <p class="text-xs font-bold uppercase tracking-widest text-on-surface/50 mt-1">Active Students</p>
                </div>
            </div>
            <div class="bg-surface-container p-5 sm:p-6 rounded-lg border-l-4 border-error shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-error/10 rounded-lg text-error">
                        <span class="material-symbols-outlined" data-icon="pending_actions">pending_actions</span>
                    </div>
                    <span class="text-error font-bold text-xs flex items-center gap-1 bg-error/10 px-2.5 py-1 rounded-full uppercase tracking-tighter">
                        Attention
                    </span>
                </div>
                <div>
                    <h3 class="text-3xl sm:text-4xl font-extrabold font-headline text-on-surface">{{ $pendingApprovalsCount }}</h3>
                    <p class="text-xs font-bold uppercase tracking-widest text-on-surface/50 mt-1">Pending Approvals</p>
                </div>
            </div>
            <div class="bg-surface-container p-5 sm:p-6 rounded-lg border-l-4 border-secondary shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-secondary/10 rounded-lg text-secondary">
                        <span class="material-symbols-outlined" data-icon="schedule">schedule</span>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl sm:text-4xl font-extrabold font-headline text-on-surface">{{ number_format($totalHoursTracked) }}</h3>
                    <p class="text-xs font-bold uppercase tracking-widest text-on-surface/50 mt-1">Total Hours Tracked</p>
                </div>
            </div>
        </section>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">
            <!-- Manage Students Table -->
            <div class="lg:col-span-8 space-y-6">
                <div class="bg-surface-container rounded-lg overflow-hidden shadow-sm border border-outline/10">
                    <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-outline/10 flex justify-between items-center bg-white/50">
                        <h3 class="font-headline font-bold text-base sm:text-lg text-on-surface">Manage Students</h3>
                        <div class="flex gap-2">
                            <a href="{{ route('coordinator.students') }}" class="p-1.5 hover:bg-surface/80 rounded text-on-surface/60 transition-colors" title="View all students">
                                <span class="material-symbols-outlined text-[20px]" data-icon="search">search</span>
                            </a>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[620px] text-left border-collapse">
                            <thead>
                                <tr class="bg-surface/50">
                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-[10px] font-bold uppercase tracking-widest text-on-surface/50">Student Name</th>
                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-[10px] font-bold uppercase tracking-widest text-on-surface/50">Company</th>
                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-[10px] font-bold uppercase tracking-widest text-on-surface/50">Req. Hours</th>
                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-[10px] font-bold uppercase tracking-widest text-on-surface/50">Hours Status</th>
                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-[10px] font-bold uppercase tracking-widest text-on-surface/50">Docs</th>
                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-[10px] font-bold uppercase tracking-widest text-on-surface/50 text-right">Logbook</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline/5">
                                @forelse($students as $student)
                                <tr class="hover:bg-white/40 transition-colors group">
                                    <td class="px-4 sm:px-6 py-3.5 sm:py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center text-primary font-bold text-xs uppercase shrink-0">
                                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <a href="{{ route('coordinator.students.show', $student->id) }}" class="text-sm font-bold text-on-surface group-hover:text-primary transition-colors">
                                                    {{ $student->first_name }} {{ $student->last_name }}
                                                </a>
                                                <p class="text-[11px] text-on-surface/60 font-medium">
                                                    {{ $student->course }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-3.5 sm:py-4 text-sm font-medium">{{ $student->company ? $student->company->name : 'Not Assigned' }}</td>
                                    <td class="px-4 sm:px-6 py-3.5 sm:py-4 text-sm font-bold text-primary whitespace-nowrap">
                                        {{ $student->required_hours }} hrs
                                    </td>
                                    <td class="px-4 sm:px-6 py-3.5 sm:py-4">
                                        <div class="w-full max-w-[120px]">
                                            <div class="flex justify-between items-center mb-1">
                                                <span class="text-[10px] font-bold text-primary">{{ $student->approved_hours ?? 0 }} / {{ $student->required_hours }}h</span>
                                                <span class="text-[10px] font-bold text-on-surface/40">{{ $student->required_hours > 0 ? round((($student->approved_hours ?? 0) / $student->required_hours) * 100) : 0 }}%</span>
                                            </div>
                                            <div class="h-1 w-full bg-outline/20 rounded-full overflow-hidden">
                                                <div class="h-full bg-primary rounded-full" style="width: {{ $student->required_hours > 0 ? min(100, round((($student->approved_hours ?? 0) / $student->required_hours) * 100)) : 0 }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-3.5 sm:py-4">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-secondary/10 text-secondary whitespace-nowrap">
                                            {{ ($student->approved_hours ?? 0) >= $student->required_hours && $student->required_hours > 0 ? 'Completed' : 'In Progress' }}
                                        </span>
                                    </td>
                                    <td class="px-4 sm:px-6 py-3.5 sm:py-4 text-right whitespace-nowrap">
                                        <span class="text-[11px] font-bold text-on-surface/60">
                                            {{ $student->required_hours > 0 ? min(100, round((($student->approved_hours ?? 0) / $student->required_hours) * 100)) : 0 }}% Complete
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-on-surface/50 text-xs italic">
                                        No students found for this academic term.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-4 sm:px-6 py-3.5 sm:py-4 bg-surface/30 flex flex-col sm:flex-row items-center justify-between gap-3 border-t border-outline/10">
                        <span class="text-[11px] text-on-surface/50 font-bold uppercase tracking-widest text-center sm:text-left">Showing {{ $students->count() }} of {{ $students->count() }} students</span>
                        <a href="{{ route('coordinator.students') }}" class="p-1.5 px-4 border border-outline/20 rounded text-[10px] font-bold uppercase tracking-widest bg-white hover:bg-surface transition-colors">
                            View All Students
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column: Document Verification Queue -->
            <div class="lg:col-span-4">
                <div class="bg-surface-container rounded-lg shadow-sm overflow-hidden lg:sticky lg:top-24 border border-outline/10">
                    <div class="p-4 sm:p-5 border-b border-outline/10 flex justify-between items-center bg-white/50">
                        <h3 class="font-headline font-bold text-sm sm:text-base text-on-surface">Document Verification Queue</h3>
                        <span class="bg-error text-white text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-tighter">{{ $pendingSubmissions->count() }} New</span>
                    </div>
                    <div class="p-4 sm:p-5 space-y-5 sm:space-y-6">
                        @if($pendingSubmissions->isNotEmpty())
                            @php
                                $firstSub = $pendingSubmissions->first();
                                $initials = substr($firstSub->user->studentProfile->first_name ?? 'S', 0, 1) . substr($firstSub->user->studentProfile->last_name ?? 'P', 0, 1);
                            @endphp
                            <!-- Focused Item -->
                            <div class="p-4 bg-white rounded-lg border-l-4 border-primary shadow-sm">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary font-bold text-sm uppercase shrink-0">
                                        {{ $initials }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-on-surface truncate">
                                            {{ $firstSub->user->studentProfile->first_name ?? 'N/A' }} {{ $firstSub->user->studentProfile->last_name ?? '' }}
                                        </p>
                                        <p class="text-[10px] font-medium text-on-surface/40 uppercase tracking-wider">
                                            {{ $firstSub->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <a href="{{ asset('storage/' . $firstSub->file_path) }}" target="_blank"
                                        class="p-3 bg-surface rounded border border-outline/10 flex items-center gap-3 group cursor-pointer hover:border-primary transition-colors block">
                                        <div class="w-10 h-12 bg-error/5 rounded flex items-center justify-center text-error shrink-0">
                                            <span class="material-symbols-outlined text-[32px]" data-icon="picture_as_pdf">picture_as_pdf</span>
                                        </div>
                                        <div class="flex-1 overflow-hidden min-w-0">
                                            <p class="text-xs font-bold text-on-surface truncate">
                                                {{ basename($firstSub->file_path) }}
                                            </p>
                                            <p class="text-[10px] text-on-surface/50 font-medium truncate">Submitted: {{ $firstSub->requirement->title ?? 'Unknown Requirement' }}</p>
                                        </div>
                                        <span class="material-symbols-outlined text-on-surface/40 opacity-0 group-hover:opacity-100 transition-opacity shrink-0" data-icon="visibility">visibility</span>
                                    </a>
                                    <div class="flex flex-col sm:flex-row gap-2">
                                        <form action="{{ route('coordinator.submissions.approve', $firstSub->id) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit"
                                                class="w-full py-2.5 bg-primary text-white rounded text-[10px] font-bold uppercase tracking-widest hover:brightness-110 transition-all active:scale-95 text-center">Endorse Document</button>
                                        </form>
                                        <button onclick="openRejectModal({{ $firstSub->id }})"
                                            class="flex-1 py-2.5 bg-outline/10 text-on-surface rounded text-[10px] font-bold uppercase tracking-widest hover:bg-error/10 hover:text-error transition-all active:scale-95 text-center">Return for Revision</button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Next in Queue -->
                            @if($pendingSubmissions->count() > 1)
                                <div class="space-y-4 pt-4 border-t border-outline/10">
                                    <p class="text-[10px] font-bold text-on-surface/40 uppercase tracking-[0.2em]">Next in Queue</p>
                                    
                                    @foreach($pendingSubmissions->skip(1)->take(3) as $nextSub)
                                        @php
                                            $nextInitials = substr($nextSub->user->studentProfile->first_name ?? 'S', 0, 1) . substr($nextSub->user->studentProfile->last_name ?? 'P', 0, 1);
                                        @endphp
                                        <div class="flex items-center justify-between group cursor-pointer hover:bg-surface/50 p-2 rounded-lg transition-colors" onclick="window.open('{{ asset('storage/' . $nextSub->file_path) }}', '_blank')">
                                            <div class="flex items-center gap-3 min-w-0">
                                                <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary font-bold text-xs uppercase shrink-0">
                                                    {{ $nextInitials }}
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-xs font-bold text-on-surface truncate">
                                                        {{ $nextSub->user->studentProfile->first_name ?? 'N/A' }} {{ $nextSub->user->studentProfile->last_name ?? '' }}
                                                    </p>
                                                    <p class="text-[10px] text-on-surface/50 font-medium truncate">
                                                        {{ $nextSub->requirement->title ?? 'Unknown Requirement' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <span class="material-symbols-outlined text-on-surface/30 text-[18px] group-hover:translate-x-1 transition-transform shrink-0" data-icon="chevron_right">chevron_right</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <div class="p-8 text-center text-on-surface/50 font-medium italic">
                                <span class="material-symbols-outlined text-4xl mb-2 text-on-surface/30" data-icon="verified_user">verified_user</span>
                                <p class="text-sm">No pending submissions to verify.</p>
                            </div>
                        @endif

                        <div class="pt-2">
                            <a href="{{ route('coordinator.requirements') }}"
                                class="block text-center w-full py-3 mt-2 text-[10px] font-bold uppercase tracking-widest text-primary hover:bg-primary/5 rounded-lg transition-colors border border-dashed border-primary/20">
                                View Full Queue
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- REJECTION REMARKS MODAL -->
    <div id="rejectModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm transition-opacity duration-300 p-4">
        <div class="bg-white rounded-2xl shadow-2xl border border-purple-100 p-5 sm:p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-lg sm:text-xl font-bold font-headline text-slate-800 flex items-center gap-2">
                    <span class="material-symbols-outlined text-rose-600">warning</span>
                    Return for Revision
                </h2>
                <button onclick="closeRejectModal()" class="text-gray-400 hover:text-rose-500 transition p-1">
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

                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="button" onclick="closeRejectModal()" class="flex-1 px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-bold hover:bg-gray-50 transition text-center">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 bg-rose-600 hover:bg-rose-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md transition text-center">
                        Reject Submission
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
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

        function openRejectModal(id) {
            document.getElementById('rejectForm').action = `/coordinator/submissions/${id}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }
    </script>
</body>
</html>
