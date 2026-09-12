<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Academic Terms Management | OJT Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link
        href="https://fonts.googleapis.com/css2?family=Newsreader:opsz,wght@6..72,400;6..72,600;6..72,700;6..72,800&family=Public+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">
    <style>
        body { font-family: 'Public Sans', sans-serif; }
        .font-headline { font-family: 'Newsreader', serif; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
    </style>
</head>

<body class="bg-[#faf8fa] font-body text-[#1f1a20] antialiased">
    <!-- SideNavBar -->
    @include('components.coordinator-sidebar')

    <!-- Sidebar overlay for mobile -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 backdrop-blur-xs z-40 hidden lg:hidden" onclick="closeSidebar()"></div>

    <!-- TopNavBar -->
    <header class="fixed top-0 lg:left-64 left-0 right-0 z-40 bg-surface/90 backdrop-blur-sm border-b border-[#cec3d0]/15">
        <div class="flex justify-between items-center px-4 md:px-8 py-3.5 md:py-4 w-full">
            <div class="flex items-center gap-3 md:gap-4">
                <button id="sidebar-toggle" class="lg:hidden p-2.5 min-w-[44px] min-h-[44px] flex items-center justify-center text-primary rounded-lg hover:bg-black/5 transition-colors" aria-label="Toggle menu">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <span class="text-xl md:text-2xl font-headline font-semibold text-primary tracking-tight">OJT Management</span>
                <nav class="hidden md:flex items-center gap-6">
                    @if(auth()->user()->role === 'Admin')
                        <a class="text-sm font-semibold {{ request()->routeIs('admin.academic_terms.*') ? 'text-primary border-b-2 border-primary pb-1' : 'text-on-surface/60 hover:text-primary transition-colors duration-200' }}" href="{{ route('admin.academic_terms.index') }}">Academic Terms</a>
                        <a class="text-sm font-semibold {{ request()->routeIs('admin.coordinators') ? 'text-primary border-b-2 border-primary pb-1' : 'text-on-surface/60 hover:text-primary transition-colors duration-200' }}" href="{{ route('admin.coordinators') }}">Manage Coordinators</a>
                        <a class="text-sm font-semibold {{ request()->routeIs('courses.*') ? 'text-primary border-b-2 border-primary pb-1' : 'text-on-surface/60 hover:text-primary transition-colors duration-200' }}" href="{{ route('courses.index') }}">Course Settings</a>
                    @else
                        <a class="text-sm font-semibold text-on-surface/60 hover:text-primary transition-colors duration-200" href="{{ route('coordinator.dashboard') }}">Dashboard</a>
                        <a class="text-sm font-semibold text-on-surface/60 hover:text-primary transition-colors duration-200" href="{{ route('coordinator.students') }}">Student List</a>
                        <a class="text-sm font-semibold text-on-surface/60 hover:text-primary transition-colors duration-200" href="{{ route('coordinator.companies') }}">Company Directory</a>
                        <a class="text-sm font-semibold text-on-surface/60 hover:text-primary transition-colors duration-200" href="{{ route('coordinator.reports') }}">Reports</a>
                    @endif
                </nav>
            </div>
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-3">
                    <button class="p-2 text-primary hover:bg-black/5 rounded-full transition-all active:scale-95" title="Notifications">
                        <span class="material-symbols-outlined" data-icon="notifications">notifications</span>
                    </button>
                    <div class="relative flex items-center sm:pl-2 sm:border-l sm:border-[#cec3d0]/30" id="user-profile-menu">
                        <button type="button" id="user-menu-btn" class="flex items-center gap-3 cursor-pointer focus:outline-none" aria-expanded="false" aria-haspopup="true">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-bold font-headline text-primary">{{ auth()->user()->display_name }}</p>
                                <p class="text-[10px] uppercase tracking-wider text-secondary font-bold">{{ auth()->user()->display_role }}</p>
                            </div>
                            <img alt="User profile avatar"
                                class="w-9 h-9 rounded-full object-cover ring-2 ring-primary/10 hover:ring-primary transition-all flex-shrink-0"
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
    <main class="lg:ml-64 ml-0 pt-20 sm:pt-24 px-4 sm:px-6 lg:px-8 pb-16 min-h-screen">
        <div class="max-w-6xl mx-auto space-y-6 sm:space-y-8">
            
            <!-- Page Heading & Actions -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold font-headline text-slate-900 tracking-tight">Academic Terms Setup</h1>
                    <p class="text-slate-500 text-xs sm:text-sm mt-1">Configure school years, switch active semesters, and scope incoming student batches without deleting historical records.</p>
                </div>
                <button onclick="openTermModal()"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#300050] hover:bg-purple-950 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-purple-900/20 transition active:scale-95 w-full sm:w-auto shrink-0 cursor-pointer">
                    <span class="material-symbols-outlined text-lg">add_circle</span>
                    <span>New Academic Term</span>
                </button>
            </div>

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 sm:px-5 py-3.5 sm:py-4 rounded-xl shadow-xs flex items-center gap-3">
                    <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                    <span class="text-xs sm:text-sm font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 sm:px-5 py-3.5 sm:py-4 rounded-xl shadow-xs flex items-center gap-3">
                    <span class="material-symbols-outlined text-rose-600">error</span>
                    <span class="text-xs sm:text-sm font-semibold">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Stats Overview Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 sm:gap-5">
                <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-purple-100 shadow-sm flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 mb-0.5 sm:mb-1">Current Active Term</p>
                        <h3 class="text-base sm:text-lg font-extrabold text-[#300050] truncate">{{ $activeTerm ? $activeTerm->full_title : 'No Active Term' }}</h3>
                        <p class="text-[11px] text-emerald-600 font-semibold mt-0.5 sm:mt-1 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            <span>Live registration scope</span>
                        </p>
                    </div>
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-purple-50 text-[#300050] flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-xl sm:text-2xl">event_available</span>
                    </div>
                </div>

                <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-slate-100 shadow-sm flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 mb-0.5 sm:mb-1">Total Terms Configured</p>
                        <h3 class="text-xl sm:text-2xl font-extrabold text-slate-800">{{ $terms->count() }} <span class="text-xs sm:text-base font-medium text-slate-500">Terms</span></h3>
                        <p class="text-[11px] text-slate-500 font-medium mt-0.5 sm:mt-1">Archived & active terms</p>
                    </div>
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-slate-50 text-slate-600 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-xl sm:text-2xl">history_toggle_off</span>
                    </div>
                </div>

                <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-slate-100 shadow-sm flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 mb-0.5 sm:mb-1">Active Batch Interns</p>
                        <h3 class="text-xl sm:text-2xl font-extrabold text-slate-800">{{ $activeTerm ? $activeTerm->student_profiles_count : 0 }} <span class="text-xs sm:text-base font-medium text-slate-500">Students</span></h3>
                        <p class="text-[11px] text-slate-500 font-medium mt-0.5 sm:mt-1">Enrolled in current term</p>
                    </div>
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-purple-50 text-[#300050] flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-xl sm:text-2xl">group</span>
                    </div>
                </div>
            </div>

            <!-- Terms Table Card -->
            <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="px-4 sm:px-6 py-3.5 sm:py-4 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-1 sm:gap-4">
                    <h2 class="font-bold text-slate-800 text-xs sm:text-sm uppercase tracking-wider">Configured School Years & Terms</h2>
                    <span class="text-[11px] sm:text-xs text-slate-500 font-medium">Switching active term auto-adjusts registration and dashboard default view</span>
                </div>

                <!-- Desktop Table View (Hidden on mobile) -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                <th class="px-6 py-4">Academic Year</th>
                                <th class="px-6 py-4">Semester</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Enrolled Interns</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($terms as $term)
                                <tr class="hover:bg-slate-50/70 transition-colors {{ $term->is_active ? 'bg-purple-50/30' : '' }}">
                                    <td class="px-6 py-4 font-extrabold text-slate-800 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-slate-400 text-base">calendar_today</span>
                                        A.Y. {{ $term->academic_year }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-600">
                                        {{ $term->semester }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($term->is_active)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-extrabold rounded-full shadow-xs">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                ACTIVE (CURRENT TERM)
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 bg-slate-100 text-slate-600 text-xs font-bold rounded-full">
                                                Inactive / Past Term
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-bold text-slate-700">
                                        {{ $term->student_profiles_count }} {{ Str::plural('Student', $term->student_profiles_count) }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            @if(!$term->is_active)
                                                <form action="{{ route('admin.academic_terms.activate', $term->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition shadow-xs flex items-center gap-1 cursor-pointer">
                                                        <span class="material-symbols-outlined text-sm">check</span>
                                                        Set Active
                                                    </button>
                                                </form>

                                                @if($term->student_profiles_count === 0)
                                                    <form action="{{ route('admin.academic_terms.destroy', $term->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this term?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 transition rounded-lg hover:bg-rose-50 cursor-pointer" title="Delete Term">
                                                            <span class="material-symbols-outlined text-base">delete</span>
                                                        </button>
                                                    </form>
                                                @endif
                                            @else
                                                <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200">
                                                    Active Scope
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                        No academic terms configured yet. Click "New Academic Term" to create the first term.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card List View (Visible on mobile screens) -->
                <div class="md:hidden divide-y divide-slate-100">
                    @forelse($terms as $term)
                        <div class="p-4 space-y-3 transition-colors {{ $term->is_active ? 'bg-purple-50/25' : '' }}">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-slate-400 text-base">calendar_today</span>
                                        <h3 class="font-extrabold text-slate-900 text-sm sm:text-base">A.Y. {{ $term->academic_year }}</h3>
                                    </div>
                                    <p class="text-xs font-semibold text-slate-600 mt-0.5">{{ $term->semester }}</p>
                                </div>
                                <div>
                                    @if($term->is_active)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-100 text-emerald-800 text-[10px] font-extrabold rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            ACTIVE
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-full">
                                            Inactive
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center justify-between text-xs text-slate-500">
                                <span class="flex items-center gap-1.5 font-medium">
                                    <span class="material-symbols-outlined text-sm text-slate-400">group</span>
                                    <strong class="text-slate-700 font-bold">{{ $term->student_profiles_count }}</strong> {{ Str::plural('Student', $term->student_profiles_count) }} enrolled
                                </span>
                            </div>

                            <!-- Mobile Actions -->
                            <div class="pt-2 flex items-center gap-2 border-t border-slate-100">
                                @if(!$term->is_active)
                                    <form action="{{ route('admin.academic_terms.activate', $term->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="w-full py-2 px-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition shadow-xs flex items-center justify-center gap-1.5 active:scale-98 cursor-pointer">
                                            <span class="material-symbols-outlined text-sm">check_circle</span>
                                            <span>Set Active Term</span>
                                        </button>
                                    </form>

                                    @if($term->student_profiles_count === 0)
                                        <form action="{{ route('admin.academic_terms.destroy', $term->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this term?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 transition rounded-lg hover:bg-rose-50 border border-slate-200 cursor-pointer" title="Delete Term">
                                                <span class="material-symbols-outlined text-base">delete</span>
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <div class="w-full py-1.5 px-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-xs font-bold flex items-center justify-center gap-1.5">
                                        <span class="material-symbols-outlined text-sm text-emerald-600">verified</span>
                                        <span>Current Active Scope</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="px-4 py-8 text-center text-slate-400 text-sm">
                            No academic terms configured yet. Click "New Academic Term" to create the first term.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </main>

    <!-- NEW TERM MODAL -->
    <div id="termModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 overflow-y-auto" onclick="handleModalOverlayClick(event)">
        <div class="bg-white rounded-2xl shadow-2xl border border-purple-100 p-5 sm:p-6 w-full max-w-md mx-auto my-auto max-h-[90dvh] overflow-y-auto animate-in fade-in zoom-in-95 duration-200" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-base sm:text-lg font-bold font-headline text-[#300050] flex items-center gap-2">
                    <span class="material-symbols-outlined text-purple-600">calendar_month</span>
                    Add Academic Term
                </h3>
                <button type="button" onclick="closeTermModal()" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>
            </div>

            <form action="{{ route('admin.academic_terms.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Academic Year</label>
                        <input type="text" name="academic_year" placeholder="e.g. 2026-2027" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition">
                        <p class="text-[10px] text-slate-400 mt-1">Standard format: YYYY-YYYY (e.g. 2026-2027)</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Semester</label>
                        <select name="semester" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition">
                            <option value="1st Semester">1st Semester</option>
                            <option value="2nd Semester">2nd Semester</option>
                            <option value="Midyear">Midyear / Summer</option>
                        </select>
                    </div>

                    <div class="pt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" class="w-4 h-4 text-[#300050] border-slate-300 rounded focus:ring-purple-500">
                            <span class="text-xs font-bold text-slate-700">Set as currently active term immediately</span>
                        </label>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeTermModal()"
                            class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-bold hover:bg-slate-50 transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                            class="flex-1 bg-[#300050] hover:bg-purple-950 text-white px-4 py-2.5 rounded-xl text-sm font-bold shadow-md transition cursor-pointer">
                        Save Term
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openTermModal() {
            document.getElementById('termModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
        function closeTermModal() {
            document.getElementById('termModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
        function handleModalOverlayClick(e) {
            if (e.target.id === 'termModal') {
                closeTermModal();
            }
        }
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !document.getElementById('termModal').classList.contains('hidden')) {
                closeTermModal();
            }
        });

        // Sidebar Toggle
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
