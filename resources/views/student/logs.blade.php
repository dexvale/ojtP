<!DOCTYPE html>
<html class="light" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Internship Logs | OJT Portal</title>
<meta name="description" content="View and manage your entire 400-hour log history."/>
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

<!-- ═══════════════════════════════
     TOP HEADER
═══════════════════════════════ -->
<header class="fixed top-0 w-full z-50 bg-[#fff7fd] flex justify-between items-center px-6 lg:px-8 py-4 border-b border-[#cec3d0]/20 backdrop-blur-sm lg:pl-64 pl-0">
    <div class="flex items-center gap-4">
        <button id="sidebar-toggle" class="lg:hidden p-2 text-primary rounded-lg hover:bg-surface-container transition-colors" aria-label="Toggle menu">
            <span class="material-symbols-outlined">menu</span>
        </button>
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
<div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden" onclick="closeSidebar()"></div>

<!-- ═══════════════════════════════
     MAIN CONTENT
═══════════════════════════════ -->
<main class="lg:ml-64 ml-0 pt-20 min-h-screen pb-24 lg:pb-8">
    <div class="p-5 lg:p-8 max-w-7xl mx-auto space-y-6">

        <!-- ── Page Header & Filters ── -->
        <header class="flex flex-col lg:flex-row lg:items-end justify-between gap-5 border-b border-surface-variant/30 pb-6">
            <div>
                <h1 class="text-3xl font-extrabold font-headline tracking-tight text-primary leading-tight">
                    Internship Logs
                </h1>
                <p class="text-sm text-on-surface-variant mt-1">View and manage your entire 400-hour log history.</p>
            </div>
            
            <!-- Filters -->
            <div class="flex flex-col sm:flex-row items-center gap-3">
                <div class="w-full sm:w-auto relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">search</span>
                    <input type="text" placeholder="Search activities..." class="w-full sm:w-56 bg-surface-container-lowest border border-surface-variant/30 rounded-lg py-2 pl-9 pr-3 text-sm focus:ring-2 focus:ring-primary/40 focus:border-transparent transition-all placeholder:text-outline/60" />
                </div>
                
                <div class="flex w-full sm:w-auto gap-3">
                    <select class="w-full sm:w-32 bg-surface-container-lowest border border-surface-variant/30 rounded-lg py-2 pl-3 pr-8 text-sm focus:ring-2 focus:ring-primary/40 appearance-none text-on-surface">
                        <option value="all">All Status</option>
                        <option value="approved">Approved</option>
                        <option value="pending">Pending</option>
                        <option value="rejected">Rejected</option>
                    </select>
                    
                    <select class="w-full sm:w-40 bg-surface-container-lowest border border-surface-variant/30 rounded-lg py-2 pl-3 pr-8 text-sm focus:ring-2 focus:ring-primary/40 appearance-none text-on-surface">
                        <option value="10">October 2024</option>
                        <option value="11">November 2024</option>
                    </select>
                </div>
            </div>
        </header>

        <!-- ── Main Data Table ── -->
        <section class="bg-surface-container-lowest rounded-xl shadow-sm border border-surface-variant/20 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[800px] table-fixed">
                    <thead>
                        <tr class="bg-surface-container-low text-[10px] font-bold text-outline uppercase tracking-wider border-b border-surface-variant/20">
                            <th class="py-4 pl-6 pr-4">Date</th>
                            <th class="py-4 px-4">Shift Times</th>
                            <th class="py-4 px-4">Activity Summary</th>
                            <th class="py-4 px-4">Hours</th>
                            <th class="py-4 px-4">Status</th>
                            <th class="py-4 pr-6 pl-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-variant/10">
                        @forelse($logs as $log)
                        <tr class="hover:bg-surface-container-low/50 transition-colors group">
                            <td class="py-4 pl-6 pr-4 font-bold text-sm whitespace-nowrap text-on-surface">
                                {{ \Carbon\Carbon::parse($log->log_date)->format('M d, Y') }}
                            </td>
                            <td class="py-4 px-4 text-sm text-on-surface-variant whitespace-nowrap">
                                @if($log->morning_in && $log->morning_out)
                                    {{ \Carbon\Carbon::parse($log->morning_in)->format('h:i A') }} - {{ \Carbon\Carbon::parse($log->morning_out)->format('h:i A') }}<br>
                                @endif
                                @if($log->afternoon_in && $log->afternoon_out)
                                    {{ \Carbon\Carbon::parse($log->afternoon_in)->format('h:i A') }} - {{ \Carbon\Carbon::parse($log->afternoon_out)->format('h:i A') }}
                                @endif
                            </td>
                            <td class="py-4 px-4 max-w-xs whitespace-normal break-words text-sm text-gray-600 line-clamp-2 hover:line-clamp-none transition-all duration-200">
                                {{ $log->tasks_performed }}
                            </td>
                            <td class="py-4 px-4 text-sm font-bold text-on-surface whitespace-nowrap">
                                {{ number_format($log->hours_rendered, 2) }} hrs
                            </td>
                            <td class="py-4 px-4">
                                @if(strtoupper($log->status) === 'APPROVED')
                                    <span class="px-2.5 py-1 bg-[#b0f2c1] text-[#2e6a44] text-[10px] font-bold rounded-lg uppercase tracking-wide inline-flex items-center gap-1">
                                        Approved
                                    </span>
                                @elseif(strtoupper($log->status) === 'REJECTED')
                                    <span class="px-2.5 py-1 bg-[#ffdad6] text-[#ba1a1a] text-[10px] font-bold rounded-lg uppercase tracking-wide inline-flex items-center gap-1">
                                        Rejected
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-[#e8dfe6] text-[#4d444e] text-[10px] font-bold rounded-lg uppercase tracking-wide inline-flex items-center gap-1">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 pr-6 pl-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-3">
                                    <button type="button" 
                                            class="text-xs font-semibold text-purple-700 hover:text-purple-900 view-log-btn"
                                            data-date="{{ \Carbon\Carbon::parse($log->log_date)->format('M d, Y') }}"
                                            data-summary="{{ $log->tasks_performed }}"
                                            data-remarks="{{ $log->remarks ?? '' }}"
                                            data-hours="{{ number_format($log->hours_rendered, 2) }}"
                                            data-times="AM: {{ $log->morning_in ? \Carbon\Carbon::parse($log->morning_in)->format('h:i A') : '--' }} - {{ $log->morning_out ? \Carbon\Carbon::parse($log->morning_out)->format('h:i A') : '--' }} | PM: {{ $log->afternoon_in ? \Carbon\Carbon::parse($log->afternoon_in)->format('h:i A') : '--' }} - {{ $log->afternoon_out ? \Carbon\Carbon::parse($log->afternoon_out)->format('h:i A') : '--' }}"
                                            data-photo="{{ $log->photo_path ? asset('storage/' . $log->photo_path) : '' }}">
                                        View Entry
                                    </button>
                                    @if(strtoupper($log->status) === 'PENDING')
                                        <form action="{{ route('student.logs.destroy', $log->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this pending log?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    @elseif(strtoupper($log->status) === 'REJECTED')
                                        <a href="{{ route('student.logs.edit', $log->id) }}" class="text-xs font-semibold text-amber-600 hover:text-amber-900">
                                            Edit / Resubmit
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-sm text-on-surface-variant">
                                No logs found. Start logging your shifts!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-surface-variant/20 bg-surface-container-low/30">
                {{ $logs->links() }}
            </div>
        </section>

    </div>{{-- /Container --}}
</main>

<!-- View Entry Modal -->
<div id="view-log-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity duration-300">
    <div class="bg-surface-container-lowest rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden flex flex-col border border-surface-variant/20">
        <div class="flex items-center justify-between p-5 border-b border-surface-variant/20 bg-purple-50">
            <h3 class="text-lg font-bold font-headline text-primary flex items-center gap-2">
                <span class="material-symbols-outlined">description</span>
                Log Entry Details
            </h3>
            <button type="button" class="close-modal-btn text-outline hover:text-primary transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="w-full px-6 py-6 space-y-4 overflow-y-auto overflow-x-hidden max-h-[70vh]">
            <div class="flex justify-between items-center pb-3 border-b border-surface-variant/10">
                <span class="text-xs font-bold text-outline uppercase tracking-wider">Date</span>
                <span id="modal-date" class="text-sm font-bold text-on-surface"></span>
            </div>
            <div class="flex justify-between items-center pb-3 border-b border-surface-variant/10">
                <span class="text-xs font-bold text-outline uppercase tracking-wider">Shift Times</span>
                <span id="modal-times" class="text-sm font-medium text-on-surface-variant text-right"></span>
            </div>
            <div class="flex justify-between items-center pb-3 border-b border-surface-variant/10">
                <span class="text-xs font-bold text-outline uppercase tracking-wider">Total Hours</span>
                <span id="modal-hours" class="text-sm font-bold text-primary"></span>
            </div>
            <div class="pt-2">
                <span class="block text-xs font-bold text-outline uppercase tracking-wider mb-2">Activity Summary</span>
                <div class="mt-1 w-full bg-gray-50 rounded-lg p-3 border border-gray-100 min-h-[4.5rem]">
                    <p id="modal-summary" class="text-sm text-gray-700 whitespace-pre-wrap break-words overflow-visible"></p>
                </div>
            </div>
            <div id="modal-remarks-container" class="pt-2 hidden">
                <span class="block text-xs font-bold text-error uppercase tracking-wider mb-2">Supervisor Remarks</span>
                <div class="mt-1 w-full bg-error/10 rounded-lg p-3 border border-error/20 min-h-[4.5rem]">
                    <p id="modal-remarks" class="text-sm text-error whitespace-pre-wrap break-words overflow-visible"></p>
                </div>
            </div>
            <div id="modal-photo-container" class="pt-4 hidden border-t border-surface-variant/10">
                <span class="block text-xs font-bold text-outline uppercase tracking-wider mb-2">Workspace Photo</span>
                <img id="modal-photo" src="" alt="Workspace" class="w-full max-w-full h-auto max-h-64 object-contain rounded-lg border border-gray-100 shadow-sm">
            </div>
        </div>
        <div class="p-5 border-t border-surface-variant/20 bg-surface-container-low/30 text-right">
            <button type="button" class="close-modal-btn px-6 py-2.5 bg-surface-container-highest text-on-surface rounded-xl font-bold text-sm hover:bg-surface-variant transition-colors">
                Close
            </button>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════
     MOBILE BOTTOM NAV
═══════════════════════════════ -->
<nav class="lg:hidden fixed bottom-0 w-full bg-white/90 backdrop-blur-lg border-t border-surface-variant/20 flex justify-around items-center py-2.5 z-50">
    <a href="{{ route('student.dashboard') }}" class="flex flex-col items-center gap-0.5 text-outline px-3 py-1">
        <span class="material-symbols-outlined text-xl">dashboard</span>
        <span class="text-[10px] font-bold">Home</span>
    </a>
    <a href="{{ route('student.logs.index') }}" class="flex flex-col items-center gap-0.5 text-primary px-3 py-1">
        <span class="material-symbols-outlined text-xl" style="font-variation-settings:'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 24;">description</span>
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
    <a href="{{ route('student.profile') }}" class="flex flex-col items-center gap-0.5 text-outline px-3 py-1">
        <span class="material-symbols-outlined text-xl">person</span>
        <span class="text-[10px] font-bold">Profile</span>
    </a>
</nav>

<script>
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

    // ── Modal Logic ──
    const modal = document.getElementById('view-log-modal');
    const modalDate = document.getElementById('modal-date');
    const modalSummary = document.getElementById('modal-summary');
    const modalRemarksContainer = document.getElementById('modal-remarks-container');
    const modalRemarks = document.getElementById('modal-remarks');
    const modalHours = document.getElementById('modal-hours');
    const modalTimes = document.getElementById('modal-times');
    const modalPhotoContainer = document.getElementById('modal-photo-container');
    const modalPhoto = document.getElementById('modal-photo');
    const closeBtns = document.querySelectorAll('.close-modal-btn');

    document.querySelectorAll('.view-log-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            modalDate.textContent = this.getAttribute('data-date');
            modalSummary.textContent = this.getAttribute('data-summary');
            modalHours.textContent = this.getAttribute('data-hours') + ' hrs';
            modalTimes.textContent = this.getAttribute('data-times');
            
            const remarks = this.getAttribute('data-remarks');
            if (remarks) {
                modalRemarks.textContent = remarks;
                modalRemarksContainer.classList.remove('hidden');
            } else {
                modalRemarks.textContent = '';
                modalRemarksContainer.classList.add('hidden');
            }
            
            const photoUrl = this.getAttribute('data-photo');
            if (photoUrl) {
                modalPhoto.src = photoUrl;
                modalPhotoContainer.classList.remove('hidden');
            } else {
                modalPhoto.src = '';
                modalPhotoContainer.classList.add('hidden');
            }
            
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        });
    });

    closeBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        });
    });
</script>
</body>
</html>
