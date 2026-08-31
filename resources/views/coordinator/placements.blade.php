<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>OJT Placement Endorsements | Coordinator Portal</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:opsz,wght@6..72,400;6..72,600;6..72,700;6..72,800&family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <!-- Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <!-- Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .font-headline { font-family: 'Newsreader', serif; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
    </style>
</head>
<body class="bg-surface font-body text-on-surface antialiased" data-theme="portal">

    <!-- TopNavBar -->
    <header class="fixed top-0 lg:left-64 left-0 right-0 z-40 bg-surface/90 backdrop-blur-sm border-b border-[#cec3d0]/15">
        <div class="flex justify-between items-center px-4 md:px-8 py-4 w-full">
            <div class="flex items-center gap-4">
                <button id="sidebar-toggle" class="lg:hidden p-2.5 flex items-center justify-center text-primary rounded-lg hover:bg-black/5 transition-colors" aria-label="Toggle menu">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <span class="text-xl md:text-2xl font-headline font-semibold text-primary tracking-tight">OJT Management</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold font-headline text-primary">{{ auth()->user()->email }}</p>
                    <p class="text-[10px] uppercase tracking-wider text-secondary font-bold">OJT Coordinator</p>
                </div>
            </div>
        </div>
    </header>

    <!-- SideNavBar -->
    @include('components.coordinator-sidebar')

    <!-- Main Content -->
    <main class="lg:ml-64 ml-0 pt-24 px-4 sm:px-6 lg:px-8 pb-16 min-h-screen">
        <div class="max-w-7xl mx-auto space-y-8">

            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold font-headline text-slate-900 tracking-tight">Placement Endorsements</h1>
                    <p class="text-slate-500 font-medium text-sm mt-1">Review student company applications, verify department assignments, and approve supervisor credentials.</p>
                </div>
            </div>

            <!-- Credentials Flash Banner -->
            @if(session('flash_password'))
                <div id="credential-flash-banner" class="bg-emerald-50 border border-emerald-200 rounded-2xl p-6 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-emerald-600"></div>
                    <div class="flex items-start justify-between">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined">key</span>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-emerald-900">New Advisor Credentials Generated!</h3>
                                <p class="text-emerald-700 text-xs mt-1">Share these login credentials with the company supervisor for <strong>{{ session('flash_company') }}</strong>:</p>
                                <div class="mt-3 flex flex-wrap gap-4 bg-white/80 border border-emerald-200/80 p-3 rounded-xl">
                                    <div>
                                        <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest">Login Email</span>
                                        <p class="text-xs font-mono font-bold text-slate-800">{{ session('flash_email') }}</p>
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest">Temporary Password</span>
                                        <p class="text-xs font-mono font-bold text-slate-800">{{ session('flash_password') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button onclick="document.getElementById('credential-flash-banner').remove()" class="text-emerald-400 hover:text-emerald-700 transition">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Flash Success / Error -->
            @if(session('success') && !session('flash_password'))
                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-center gap-3 text-emerald-800 text-sm shadow-sm">
                    <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Overview Bento Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Pending Review</p>
                        <p class="text-3xl font-extrabold font-headline text-amber-600 mt-1">{{ $pendingPlacements->count() }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">hourglass_top</span>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Active / Endorsed</p>
                        <p class="text-3xl font-extrabold font-headline text-emerald-600 mt-1">{{ $approvedPlacements->total() }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">verified</span>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Revision Requested</p>
                        <p class="text-3xl font-extrabold font-headline text-rose-600 mt-1">{{ $rejectedPlacements->count() }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">error_outline</span>
                    </div>
                </div>
            </div>

            <!-- Pending Review Table Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div class="flex items-center gap-2.5">
                        <span class="material-symbols-outlined text-amber-600">pending_actions</span>
                        <h2 class="font-headline font-bold text-lg text-slate-800">Pending Placement Applications ({{ $pendingPlacements->count() }})</h2>
                    </div>
                    <span class="text-xs text-slate-500">Awaiting your academic review & endorsement</span>
                </div>

                @if($pendingPlacements->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse whitespace-nowrap">
                            <thead>
                                <tr class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                    <th class="px-6 py-4">Student</th>
                                    <th class="px-6 py-4">Host Company</th>
                                    <th class="px-6 py-4">Department</th>
                                    <th class="px-6 py-4">Immediate Supervisor</th>
                                    <th class="px-6 py-4">Acceptance Letter</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @foreach($pendingPlacements as $student)
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full bg-primary/10 text-primary font-bold text-xs flex items-center justify-center uppercase">
                                                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-slate-900">{{ $student->first_name }} {{ $student->last_name }}</p>
                                                    <p class="text-xs text-slate-500">{{ $student->student_id_number }} &bull; {{ $student->course }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-medium text-slate-800">
                                            @if($student->pending_company_name)
                                                <span class="inline-flex items-center gap-1 text-purple-700 font-bold">
                                                    <span class="material-symbols-outlined text-sm">add_circle</span> {{ $student->pending_company_name }}
                                                </span>
                                                <span class="block text-[10px] text-amber-600 font-bold uppercase">(New Company)</span>
                                            @else
                                                <span class="font-bold text-slate-800">{{ $student->company->name ?? 'N/A' }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-slate-700">
                                            <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-800 text-xs font-semibold">
                                                {{ $student->department ?? 'General Operations' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="font-bold text-slate-800">{{ $student->pending_supervisor_name ?? $student->supervisor->name ?? 'N/A' }}</p>
                                            <p class="text-xs text-slate-500 font-mono">{{ $student->pending_supervisor_email ?? $student->supervisor->email ?? '' }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($student->acceptance_letter_path)
                                                <a href="{{ asset('storage/' . $student->acceptance_letter_path) }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-purple-50 text-purple-700 hover:bg-purple-100 text-xs font-bold transition">
                                                    <span class="material-symbols-outlined text-sm">picture_as_pdf</span> View Letter
                                                </a>
                                            @else
                                                <span class="text-xs text-slate-400 italic">None attached</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <form action="{{ route('coordinator.placements.approve', $student->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-xs font-bold uppercase tracking-wider hover:opacity-90 active:scale-95 transition shadow-sm flex items-center gap-1">
                                                        <span class="material-symbols-outlined text-sm">check</span> Endorse
                                                    </button>
                                                </form>

                                                <button onclick="openRejectModal({{ $student->id }}, '{{ addslashes($student->first_name . ' ' . $student->last_name) }}')" class="px-3 py-2 border border-slate-200 text-rose-600 hover:bg-rose-50 rounded-lg text-xs font-bold transition">
                                                    Return
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-12 text-center text-slate-400">
                        <span class="material-symbols-outlined text-5xl mb-2 text-slate-300">task_alt</span>
                        <p class="font-headline font-bold text-lg text-slate-600">All caught up!</p>
                        <p class="text-xs">There are no pending placement applications to review.</p>
                    </div>
                @endif
            </div>

            <!-- Active / Endorsed Placements Table Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                        <h2 class="font-headline font-bold text-lg text-slate-800">Active & Endorsed Placements ({{ $approvedPlacements->total() }})</h2>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                <th class="px-6 py-4">Student</th>
                                <th class="px-6 py-4">Host Organization</th>
                                <th class="px-6 py-4">Department Assignment</th>
                                <th class="px-6 py-4">Assigned Supervisor</th>
                                <th class="px-6 py-4 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($approvedPlacements as $student)
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-slate-900">{{ $student->first_name }} {{ $student->last_name }}</p>
                                        <p class="text-xs text-slate-500">{{ $student->student_id_number }} &bull; {{ $student->course }}</p>
                                    </td>
                                    <td class="px-6 py-4 font-medium text-slate-800">
                                        {{ $student->company->name ?? 'Unassigned' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 rounded-lg bg-purple-50 text-purple-700 text-xs font-semibold">
                                            {{ $student->department ?? 'General Operations' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-slate-800">{{ $student->supervisor->name ?? 'Assigned Advisor' }}</p>
                                        <p class="text-xs text-slate-500 font-mono">{{ $student->supervisor->email ?? 'N/A' }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-slate-400">No active placements found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($approvedPlacements->hasPages())
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                        {{ $approvedPlacements->links() }}
                    </div>
                @endif
            </div>

        </div>
    </main>

    <!-- REJECTION REMARKS MODAL -->
    <div id="rejectModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm transition-opacity p-4">
        <div class="bg-white rounded-2xl shadow-2xl border border-rose-100 p-6 w-full max-w-md mx-auto">
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-lg font-bold font-headline text-slate-800 flex items-center gap-2">
                    <span class="material-symbols-outlined text-rose-600">report_problem</span>
                    Return Placement for Revision
                </h2>
                <button onclick="closeRejectModal()" class="text-slate-400 hover:text-rose-500 transition">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <p class="text-xs text-slate-500 mb-4">Returning application for: <span id="reject-student-name" class="font-bold text-slate-800"></span></p>

            <form method="POST" id="rejectForm">
                @csrf
                <div class="mb-6">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Revision Remarks / Reason <span class="text-rose-500">*</span></label>
                    <textarea name="remarks" required rows="4" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 bg-slate-50/50 text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 focus:border-transparent transition-all" placeholder="Explain what details are missing or need correction (e.g. invalid supervisor email, missing endorsement letter)."></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeRejectModal()" class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-bold hover:bg-slate-50 transition">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 bg-rose-600 hover:bg-rose-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-md transition">
                        Return to Student
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRejectModal(id, studentName) {
            document.getElementById('reject-student-name').innerText = studentName;
            document.getElementById('rejectForm').action = `/coordinator/placements/${id}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }

        // Sidebar Toggle
        const sidebarEl = document.getElementById('sidebar');
        const toggleBtnEl = document.getElementById('sidebar-toggle');
        toggleBtnEl?.addEventListener('click', () => {
            sidebarEl.classList.toggle('-translate-x-full');
        });
    </script>
</body>
</html>
