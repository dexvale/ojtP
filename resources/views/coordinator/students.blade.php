<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>OJT Portal | Student Directory</title>
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
    <!-- SideNavBar -->
    <!-- SideNavBar (Shared Component) -->
    @include('components.coordinator-sidebar')

    <!-- Sidebar overlay for mobile -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-40 hidden lg:hidden" onclick="closeSidebar()"></div>

    <!-- TopNavBar -->
    <header class="fixed top-0 lg:left-64 left-0 right-0 z-40 bg-surface/90 backdrop-blur-sm border-b border-[#cec3d0]/15">
        <div class="flex justify-between items-center px-4 md:px-8 py-4 w-full">
            <div class="flex items-center gap-4">
                <button id="sidebar-toggle" class="lg:hidden p-2.5 min-w-[44px] min-h-[44px] flex items-center justify-center text-primary rounded-lg hover:bg-black/5 transition-colors" aria-label="Toggle menu">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <span class="text-xl md:text-2xl font-headline font-semibold text-primary tracking-tight">OJT Management</span>
                <nav class="hidden md:flex items-center gap-6">
                    <a class="text-sm font-semibold text-on-surface/60 hover:text-primary transition-colors duration-200" href="#">Dashboard</a>
                    <a class="text-sm font-semibold text-primary border-b-2 border-primary pb-1" href="#">Student List</a>
                    <a class="text-sm font-semibold text-on-surface/60 hover:text-primary transition-colors duration-200" href="#">Company Directory</a>
                    <a class="text-sm font-semibold text-on-surface/60 hover:text-primary transition-colors duration-200" href="#">Reports</a>
                </nav>
            </div>
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-3">
                    <button class="p-2 text-primary hover:bg-black/5 rounded-full transition-all active:scale-95">
                        <span class="material-symbols-outlined" data-icon="notifications">notifications</span>
                    </button>
                    <div class="flex items-center gap-3 pl-2 border-l border-[#cec3d0]/30">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold font-headline text-primary">{{ auth()->user()->display_name }}</p>
                            <p class="text-[10px] uppercase tracking-wider text-secondary font-bold">{{ auth()->user()->display_role }}</p>
                        </div>
                        <img alt="User profile avatar"
                            class="w-9 h-9 rounded-full object-cover ring-2 ring-primary/10"
                            src="{{ auth()->user()->avatar_url }}">
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="lg:ml-64 ml-0 pt-24 px-4 sm:px-6 lg:px-8 pb-12 min-h-screen border-none">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-end justify-between mb-8 gap-4">
            <div>
                <h1 class="text-4xl font-extrabold font-headline text-slate-900 tracking-tight">Student Directory</h1>
                <p class="text-slate-500 font-medium mt-1 text-sm">Manage and track all OJT intern placements and progress.</p>
            </div>

            @if(isset($allTerms) && $allTerms->isNotEmpty())
                <form method="GET" action="{{ route('coordinator.students') }}" class="flex items-center gap-2 bg-white px-3.5 py-2 rounded-xl border border-purple-100 shadow-xs">
                    <span class="material-symbols-outlined text-purple-700 text-sm">calendar_month</span>
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Term:</label>
                    <div class="relative">
                        <select name="term_id" onchange="this.form.submit()"
                                class="bg-purple-50/50 border border-purple-200 rounded-lg px-3 py-1.5 text-xs font-bold text-[#300050] focus:ring-2 focus:ring-purple-500 focus:outline-none appearance-none pr-7 cursor-pointer">
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

        <!-- Top Control Bar -->
        <div class="flex flex-col sm:flex-row gap-4 mb-6">
            <!-- Search Bar -->
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input type="text" id="studentSearchInput" oninput="filterStudentsTable()"
                    class="w-full bg-white border border-slate-200 rounded-xl py-3 pl-12 pr-4 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary shadow-sm transition-all"
                    placeholder="Search by Student Name or ID...">
            </div>
            
            <!-- Filters -->
            <div class="flex gap-3 w-full sm:w-auto">
                <div class="relative flex-1 sm:flex-initial">
                    <select id="courseFilterSelect" onchange="filterStudentsTable()" class="w-full sm:w-auto appearance-none bg-white border border-slate-200 rounded-xl py-3 pl-4 pr-10 text-sm font-medium text-slate-700 sm:min-w-[160px] focus:ring-2 focus:ring-primary/20 focus:border-primary shadow-sm cursor-pointer transition-all">
                        <option value="all">All Courses</option>
                        @if(isset($courses))
                            @foreach($courses as $course)
                                <option value="{{ strtolower($course->course_name) }}">{{ $course->course_name }}</option>
                            @endforeach
                        @endif
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                </div>
            </div>
        </div>

        <!-- Data Table (Card Container) -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="w-full overflow-x-auto -mx-4 sm:mx-0 min-w-full inline-block align-middle">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200">
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Student Name</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider hidden md:table-cell">Course</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Placement Status</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">OJT Progress</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100" id="studentsTableBody">
                        @forelse($students as $student)
                        <tr class="hover:bg-slate-50/50 transition-colors group student-row"
                            data-name="{{ strtolower($student->first_name . ' ' . $student->last_name) }}"
                            data-id="{{ strtolower($student->student_id_number ?? '') }}"
                            data-course="{{ strtolower($student->course ?? '') }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold shadow-sm uppercase">
                                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900 group-hover:text-primary transition-colors">{{ $student->first_name }} {{ $student->last_name }}</p>
                                        <p class="text-xs text-slate-500">{{ $student->student_id_number }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 hidden md:table-cell">
                                <p class="text-sm font-medium text-slate-900">{{ $student->course }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if($student->placement_status === 'Approved' && $student->company)
                                    <div class="flex flex-col gap-1">
                                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-green-50 border border-green-100 text-green-700 text-xs font-semibold w-fit">
                                            <span class="material-symbols-outlined text-sm">corporate_fare</span>
                                            <span class="truncate max-w-[160px]">{{ $student->company->name }}</span>
                                        </div>
                                        @if($student->department)
                                            <span class="text-[11px] text-slate-500 font-medium pl-1">
                                                &bull; {{ $student->department }}
                                            </span>
                                        @endif
                                    </div>
                                @elseif($student->placement_status === 'Pending')
                                    <a href="{{ route('coordinator.placements') }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-800 text-xs font-semibold hover:bg-amber-100 transition">
                                        <span class="material-symbols-outlined text-sm text-amber-600">hourglass_top</span>
                                        <span>Pending Endorsement</span>
                                    </a>
                                @elseif($student->placement_status === 'Rejected')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-rose-50 border border-rose-100 text-rose-700 text-xs font-semibold">
                                        <span class="material-symbols-outlined text-sm">error</span>
                                        <span>Needs Revision</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-50 border border-slate-200 text-slate-500 text-xs font-semibold">
                                        <span class="material-symbols-outlined text-sm">pending</span>
                                        <span>Unassigned</span>
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $approvedHours = floatval($student->approved_hours ?? 0);
                                    $requiredHours = $student->academicCourse->required_hours ?? 400;
                                    $progressPercent = min(($approvedHours / max($requiredHours, 1)) * 100, 100);

                                    // Dynamic Color State Selector Logic
                                    if ($progressPercent <= 25) {
                                        $barColor = 'bg-amber-500';
                                        $textColor = 'text-amber-700';
                                    } elseif ($progressPercent < 90) {
                                        $barColor = 'bg-purple-700';
                                        $textColor = 'text-purple-700';
                                    } else {
                                        $barColor = 'bg-emerald-600';
                                        $textColor = 'text-emerald-700';
                                    }
                                @endphp
                                
                                <div class="w-full max-w-[170px] flex flex-col gap-1.5">
                                    <!-- Text Labels Top Layer: Aligned and De-cluttered -->
                                    <div class="flex justify-between items-center text-xs font-medium">
                                        <span class="font-mono text-gray-600">{{ number_format($approvedHours, 2) }} <span class="text-[10px] text-gray-400">/ {{ $requiredHours }} hrs</span></span>
                                        <span class="{{ $textColor }} font-bold font-mono">{{ round($progressPercent) }}%</span>
                                    </div>
                                    
                                    <!-- Track Rail Background Container -->
                                    <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden p-[1px] border border-gray-100">
                                        <!-- Dynamic Filled State Strip Indicator -->
                                        <div class="{{ $barColor }} h-full rounded-full transition-all duration-700 ease-out shadow-sm" 
                                             style="width: {{ $progressPercent }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('coordinator.students.show', $student->id) }}" class="border border-gray-200 text-gray-700 font-semibold text-xs rounded-lg px-3 py-2 hover:bg-gray-50 hover:text-primary transition-colors duration-150 inline-flex items-center gap-1">
                                    <span>View Profile</span>
                                    <span class="material-symbols-outlined text-sm">chevron_right</span>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr id="emptyRow">
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="material-symbols-outlined text-4xl text-slate-300 mb-2">school</span>
                                    <p class="font-headline font-bold text-slate-700 text-base">No Students Found</p>
                                    <p class="text-xs text-slate-400 mt-0.5">There are no students enrolled in your assigned courses for this term.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination Footer -->
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50 flex items-center justify-between">
                <p class="text-xs text-slate-500 font-medium">Showing <span class="font-bold text-slate-700" id="studentVisibleCount">{{ $students->count() }}</span> interns</p>
                <div class="flex gap-2">
                    <button class="px-3 py-1.5 border border-slate-200 rounded-lg text-xs font-semibold text-slate-400 bg-white cursor-not-allowed">
                        Previous
                    </button>
                    <button class="px-3 py-1.5 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:text-primary transition-colors focus:ring-2 focus:ring-primary/20">
                        Next
                    </button>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Sidebar Toggling Code
        const sidebarEl = document.getElementById('sidebar');
        const overlayEl = document.getElementById('sidebar-overlay');
        const toggleBtnEl = document.getElementById('sidebar-toggle');

        function openSidebar() {
            sidebarEl.classList.remove('-translate-x-full');
            overlayEl.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
        function closeSidebar() {
            sidebarEl.classList.add('-translate-x-full');
            overlayEl.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
        toggleBtnEl?.addEventListener('click', () => {
            sidebarEl.classList.contains('-translate-x-full') ? openSidebar() : closeSidebar();
        });

        function filterStudentsTable() {
            const query = document.getElementById('studentSearchInput').value.toLowerCase().trim();
            const courseSelect = document.getElementById('courseFilterSelect');
            const selectedCourse = courseSelect ? courseSelect.value.toLowerCase() : 'all';
            const rows = document.querySelectorAll('.student-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const name = row.dataset.name || '';
                const id = row.dataset.id || '';
                const course = row.dataset.course || '';

                const matchesQuery = !query || name.includes(query) || id.includes(query);
                const matchesCourse = (selectedCourse === 'all') || (course === selectedCourse);

                if (matchesQuery && matchesCourse) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Handle "No matching results" row
            let noMatchRow = document.getElementById('noMatchRow');
            const tbody = document.getElementById('studentsTableBody');
            if (visibleCount === 0 && rows.length > 0) {
                if (!noMatchRow) {
                    noMatchRow = document.createElement('tr');
                    noMatchRow.id = 'noMatchRow';
                    noMatchRow.innerHTML = `
                        <td colspan="5" class="py-12 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center">
                                <span class="material-symbols-outlined text-4xl text-slate-300 mb-2">search_off</span>
                                <p class="font-headline font-bold text-slate-700 text-base">No Matching Students</p>
                                <p class="text-xs text-slate-400 mt-0.5">Try adjusting your search keywords or course filter.</p>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(noMatchRow);
                }
                noMatchRow.style.display = '';
            } else if (noMatchRow) {
                noMatchRow.style.display = 'none';
            }

            const countEl = document.getElementById('studentVisibleCount');
            if (countEl) {
                countEl.textContent = visibleCount;
            }
        }
    </script>
</body>

</html>
