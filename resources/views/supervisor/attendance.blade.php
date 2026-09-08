<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>OJT Portal | Supervisor Attendance</title>
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

    <!-- SIDEBAR -->
    <!-- SIDEBAR (Supervisor Version - Shared Component) -->
    @include('components.supervisor-sidebar')

    <!-- Sidebar overlay for mobile -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-40 hidden lg:hidden" onclick="closeSidebar()"></div>

    <!-- TOP NAVIGATION -->
    <header class="fixed top-0 lg:left-64 left-0 right-0 z-40 bg-[#fff7fd] border-b border-[#cec3d0]/15">
        <div class="flex justify-between items-center px-4 md:px-8 py-4 w-full">
            <div class="flex items-center gap-8">
                <button id="sidebar-toggle" class="lg:hidden p-2.5 min-w-[44px] min-h-[44px] flex items-center justify-center text-primary rounded outline-none hover:bg-surface-container" aria-label="Toggle menu">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <span class="text-2xl font-headline font-semibold text-[#300050] tracking-tight hidden sm:block">Industry Supervisor</span>
            </div>
            <div class="flex items-center gap-6">
                <!-- Notifications & Profile -->
                <div class="flex items-center gap-3">
                    <button class="p-2 text-[#300050] hover:bg-[#faf1f8] rounded-full transition-all active:scale-95">
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
                        <div id="user-menu-dropdown" class="hidden absolute right-0 top-full mt-2 w-52 bg-white rounded-xl shadow-lg border border-slate-100 py-1.5 z-50">
                            <div class="px-4 py-2 border-b border-slate-100 sm:hidden">
                                <p class="text-xs font-bold text-slate-800 truncate">{{ auth()->user()->display_name }}</p>
                                <p class="text-[10px] uppercase tracking-wider text-slate-500 font-semibold truncate">{{ auth()->user()->company->name ?? 'Supervisor' }}</p>
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

    <!-- MAIN CONTENT -->
    <main class="lg:ml-64 ml-0 pt-24 px-4 sm:px-6 lg:px-8 pb-12">
        
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-end justify-between mb-8 gap-4">
            <div>
                <h1 class="text-4xl font-extrabold font-headline text-primary tracking-tight">Intern Attendance Tracker</h1>
                <p class="text-on-surface/60 font-medium mt-1">Monitor daily time logs and weekly attendance trends.</p>
            </div>
            <button class="flex items-center justify-center gap-2 px-5 py-2.5 border-2 border-primary text-primary bg-transparent rounded-lg font-bold text-sm hover:bg-primary hover:text-white transition-all shadow-sm active:scale-95">
                <span class="material-symbols-outlined text-[20px]">download</span>
                Download Weekly Report
            </button>
        </div>

        <div class="flex flex-col gap-8">
            
            <!-- SECTION A: Live Status Table -->
            <section class="bg-white p-6 rounded-2xl shadow-sm border border-outline/30">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold font-headline text-on-surface tracking-tight flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">schedule</span> Today's Live Status
                    </h2>
                    <span class="text-sm font-bold text-on-surface/50 bg-surface-container-high px-4 py-1.5 rounded-lg border border-outline/20">{{ \Carbon\Carbon::today()->format('M d, Y') }}</span>
                </div>

                <div class="w-full overflow-x-auto -mx-4 sm:mx-0 min-w-full inline-block align-middle">
                    <table class="w-full text-left min-w-[800px]">
                        <thead>
                            <tr class="border-b border-outline/20">
                                <th class="pb-3 text-[10px] font-bold uppercase tracking-widest text-on-surface/50 px-4">Intern</th>
                                <th class="pb-3 text-[10px] font-bold uppercase tracking-widest text-on-surface/50 px-4 hidden md:table-cell">Course/Dept</th>
                                <th class="pb-3 text-[10px] font-bold uppercase tracking-widest text-on-surface/50 px-4">Time In</th>
                                <th class="pb-3 text-[10px] font-bold uppercase tracking-widest text-on-surface/50 px-4">Time Out</th>
                                <th class="pb-3 text-[10px] font-bold uppercase tracking-widest text-on-surface/50 px-4">Status</th>
                                <th class="pb-3 text-[10px] font-bold uppercase tracking-widest text-on-surface/50 px-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline/10">
                            @forelse($todayAttendance as $intern)
                                @php 
                                    $log = $intern->ojtLogs->first(); 
                                @endphp
                                <tr class="hover:bg-surface/50 transition-colors group">
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-sm">{{ strtoupper(substr($intern->user->name, 0, 2)) }}</div>
                                            <span class="font-bold text-sm text-on-surface">{{ $intern->user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 hidden md:table-cell">
                                        <div class="text-sm font-semibold text-on-surface">{{ $intern->course ?? 'N/A' }}</div>
                                    </td>
                                    <td class="py-4 px-4 font-bold text-sm text-on-surface">{{ $log && $log->morning_in ? \Carbon\Carbon::parse($log->morning_in)->format('h:i A') : '--:-- --' }}</td>
                                    <td class="py-4 px-4 font-bold text-sm text-on-surface/40">{{ $log && $log->afternoon_out ? \Carbon\Carbon::parse($log->afternoon_out)->format('h:i A') : '--:-- --' }}</td>
                                    <td class="py-4 px-4">
                                        @if($log && $log->morning_in)
                                            @if(\Carbon\Carbon::parse($log->morning_in)->format('H:i') > '09:00')
                                                <span class="bg-warning/10 text-warning border border-warning/20 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide">Late</span>
                                            @else
                                                <span class="bg-success/10 text-success border border-success/20 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide">Present</span>
                                            @endif
                                        @else
                                            <span class="bg-error/10 text-error border border-error/20 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide">Absent</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-right">
                                        <button onclick="openAttendanceCalendar({{ $intern->id }})" class="text-primary hover:bg-primary/5 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors inline-flex items-center gap-1 border border-transparent hover:border-primary/20">
                                            <span class="material-symbols-outlined text-[16px]">calendar_view_week</span> View Calendar
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-6 text-on-surface/50 text-sm">No students assigned to your company yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- SECTION B: Weekly Monitor Grid -->
            <section class="bg-white p-6 rounded-2xl shadow-sm border border-outline/30">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold font-headline text-on-surface tracking-tight flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">view_week</span> Weekly Overview 
                        <span class="text-xs font-medium font-body text-on-surface/40 ml-2 tracking-normal">({{ $startOfWeek->format('D, M d') }} - {{ \Carbon\Carbon::parse($startOfWeek)->addDays(6)->format('D, M d') }})</span>
                    </h2>
                </div>

                <div class="w-full overflow-x-auto -mx-4 sm:mx-0 min-w-full inline-block align-middle">
                    <table class="w-full text-center min-w-[700px] border-collapse">
                        <thead>
                            <tr class="bg-surface/50">
                                <th class="py-4 px-4 border border-outline/20 text-left text-[10px] font-bold uppercase tracking-widest text-on-surface/50 rounded-tl-lg">Intern Name</th>
                                <th class="py-4 px-2 border border-outline/20 text-[10px] font-bold uppercase tracking-widest text-on-surface/50 w-24">Mon</th>
                                <th class="py-4 px-2 border border-outline/20 text-[10px] font-bold uppercase tracking-widest text-on-surface/50 w-24">Tue</th>
                                <th class="py-4 px-2 border border-outline/20 text-[10px] font-bold uppercase tracking-widest text-on-surface/50 w-24">Wed</th>
                                <th class="py-4 px-2 border border-outline/20 text-[10px] font-bold uppercase tracking-widest text-on-surface/50 w-24 {{ \Carbon\Carbon::today()->isThursday() ? 'bg-primary/5 text-primary' : '' }}">Thu {{ \Carbon\Carbon::today()->isThursday() ? '(Today)' : '' }}</th>
                                <th class="py-4 px-2 border border-outline/20 text-[10px] font-bold uppercase tracking-widest text-on-surface/50 w-24 {{ \Carbon\Carbon::today()->isFriday() ? 'bg-primary/5 text-primary' : 'opacity-60' }}">Fri {{ \Carbon\Carbon::today()->isFriday() ? '(Today)' : '' }}</th>
                                <th class="py-4 px-2 border border-outline/20 text-[10px] font-bold uppercase tracking-widest text-on-surface/50 w-24 {{ \Carbon\Carbon::today()->isSaturday() ? 'bg-primary/5 text-primary' : '' }}">Sat {{ \Carbon\Carbon::today()->isSaturday() ? '(Today)' : '' }}</th>
                                <th class="py-4 px-2 border border-outline/20 text-[10px] font-bold uppercase tracking-widest text-on-surface/50 w-24 rounded-tr-lg {{ \Carbon\Carbon::today()->isSunday() ? 'bg-primary/5 text-primary' : '' }}">Sun {{ \Carbon\Carbon::today()->isSunday() ? '(Today)' : '' }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($todayAttendance as $intern)
                            @php
                                $studentWeeklyLogs = $weeklyLogs->get($intern->user_id, collect());
                            @endphp
                            <tr class="hover:bg-surface/30">
                                <td class="py-3 px-4 border border-outline/10 text-left font-bold text-sm text-on-surface rounded-bl-lg">{{ $intern->user->name }}</td>
                                @for($i = 0; $i < 7; $i++)
                                    @php
                                        $dateObj = $startOfWeek->copy()->addDays($i);
                                        $dayName = $dateObj->format('D'); // e.g. Mon, Tue
                                        $dayLogsGroup = $studentWeeklyLogs->get($dayName);
                                        $dayLog = $dayLogsGroup ? $dayLogsGroup->first() : null;
                                        $isToday = $dateObj->format('Y-m-d') === \Carbon\Carbon::today()->format('Y-m-d');
                                        $isFuture = $dateObj->format('Y-m-d') > \Carbon\Carbon::today()->format('Y-m-d');
                                    @endphp
                                    <td class="py-3 px-2 border border-outline/10 {{ $isToday ? 'bg-primary/5' : '' }} {{ $isFuture ? 'opacity-60' : '' }}">
                                        @if($isFuture)
                                            <span class="material-symbols-outlined text-outline text-[20px]">horizontal_rule</span>
                                        @elseif($dayLog)
                                            @if(strtoupper($dayLog->status) === 'APPROVED')
                                                <span class="material-icon-filled text-success text-[24px] {{ $isToday ? 'animate-pulse' : '' }}">check_circle</span>
                                            @else
                                                <span class="material-icon-filled text-warning text-[24px] {{ $isToday ? 'animate-pulse' : '' }}">error</span>
                                            @endif
                                        @else
                                            <span class="material-icon-filled text-error text-[24px] {{ $isToday ? 'animate-pulse' : '' }}">cancel</span>
                                        @endif
                                    </td>
                                @endfor
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-6 text-on-surface/50 text-sm">No students assigned to your company yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

        </div>
    </main>

    <!-- INTERACTIVE SIDE PANEL (Modal Overlay) -->
    <!-- Hidden by default, added id to toggle via JS -->
    <div id="calendarPanel" class="hidden fixed inset-0 z-[60] bg-on-surface/40 backdrop-blur-sm flex justify-end transition-opacity opacity-0 data-[open=true]:opacity-100">
        <!-- Panel -->
        <div class="h-full w-full max-w-full sm:max-w-md bg-surface shadow-2xl flex flex-col transform translate-x-full transition-transform duration-300 data-[open=true]:translate-x-0" id="calendarSidebar">
            
            <!-- Panel Header -->
            <div class="px-6 py-5 border-b border-outline/20 bg-white flex justify-between items-center">
                <h3 class="font-headline font-bold text-xl text-primary">Attendance History</h3>
                <button id="closePanelBtn" class="p-2 hover:bg-surface-container rounded-full text-on-surface/50 hover:text-error transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <!-- Panel Content -->
            <div class="p-6 flex-1 overflow-y-auto">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 rounded-full bg-success/10 text-success flex items-center justify-center font-bold text-lg" id="cal-avatar">--</div>
                    <div>
                        <h4 class="font-bold text-lg text-on-surface" id="cal-name">Loading...</h4>
                        <p class="text-[11px] text-on-surface/50 uppercase tracking-widest font-semibold flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">school</span> <span id="cal-course">...</span></p>
                    </div>
                </div>

                <!-- KPI Summary -->
                <div class="grid grid-cols-3 gap-3 mb-8">
                    <div class="bg-success/5 border border-success/20 rounded-xl p-3 text-center">
                        <span class="block text-[10px] font-bold uppercase tracking-widest text-success/70 mb-1">Present</span>
                        <span class="block text-2xl font-extrabold font-headline text-success" id="cal-present">-</span>
                    </div>
                    <div class="bg-warning/5 border border-warning/20 rounded-xl p-3 text-center">
                        <span class="block text-[10px] font-bold uppercase tracking-widest text-warning/80 mb-1">Late</span>
                        <span class="block text-2xl font-extrabold font-headline text-warning" id="cal-late">-</span>
                    </div>
                    <div class="bg-error/5 border border-error/20 rounded-xl p-3 text-center">
                        <span class="block text-[10px] font-bold uppercase tracking-widest text-error/70 mb-1">Absent</span>
                        <span class="block text-2xl font-extrabold font-headline text-error" id="cal-absent">-</span>
                    </div>
                </div>

                <!-- Month Calendar layout -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h5 class="font-bold text-sm text-on-surface" id="calendar-month-label">Loading...</h5>
                        <div class="flex gap-1">
                            <button onclick="changeCalendarMonth(-1)" class="hover:bg-surface-container p-1 rounded-lg transition-colors flex items-center justify-center">
                                <span class="material-symbols-outlined text-outline hover:text-primary text-sm">chevron_left</span>
                            </button>
                            <button onclick="changeCalendarMonth(1)" class="hover:bg-surface-container p-1 rounded-lg transition-colors flex items-center justify-center">
                                <span class="material-symbols-outlined text-outline hover:text-primary text-sm">chevron_right</span>
                            </button>
                        </div>
                    </div>

                    <!-- 30-day Calendar Grid -->
                    <div id="calendarGrid" class="grid grid-cols-7 gap-1.5 text-center mb-6">
                    </div>
                    
                    <div class="text-[10px] bg-white border border-outline/20 p-3 rounded-lg flex justify-around font-medium text-on-surface/60">
                        <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-success/20 border border-success/40 text-success flex items-center justify-center"></span> Present</div>
                        <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-warning/20 border border-warning/40 text-warning flex items-center justify-center"></span> Late</div>
                        <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-error/20 border border-error/40 text-error flex items-center justify-center"></span> Absent</div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Simple JS to mock the modal behavior -->
    <!-- Simple JS to mock the modal behavior -->
    <script>
        const panel = document.getElementById('calendarPanel');
        const sidebar = document.getElementById('calendarSidebar');
        const closeBtn = document.getElementById('closePanelBtn');

        let calendarStudentId = null;
        let calendarCurrentMonth = new Date().getMonth() + 1; // 1-indexed (1 = Jan, 12 = Dec)
        let calendarCurrentYear = new Date().getFullYear();

        const openAttendanceCalendar = (studentId) => {
            calendarStudentId = studentId;
            // Reset to current system date upon fresh opening
            const today = new Date();
            calendarCurrentMonth = today.getMonth() + 1;
            calendarCurrentYear = today.getFullYear();

            // Open Panel UI
            panel.classList.remove('hidden');
            setTimeout(() => {
                panel.setAttribute('data-open', 'true');
                sidebar.setAttribute('data-open', 'true');
            }, 10);

            fetchCalendarData();
        };

        const fetchCalendarData = async () => {
            if (!calendarStudentId) return;

            try {
                // Fetch dynamic calendar data from new API endpoint with structural parameters
                const response = await fetch(`/supervisor/interns/${calendarStudentId}/calendar-data?month=${calendarCurrentMonth}&year=${calendarCurrentYear}`);
                if(!response.ok) throw new Error("Failed to fetch");
                
                const data = await response.json();

                // 1. Map Top Header
                document.getElementById('cal-name').innerText = data.name;
                document.getElementById('cal-course').innerText = data.course;
                document.getElementById('cal-avatar').innerText = data.name.substring(0, 2).toUpperCase();

                // 2. Map KPIs
                document.getElementById('cal-present').innerText = data.metrics.present;
                document.getElementById('cal-late').innerText = data.metrics.late;
                document.getElementById('cal-absent').innerText = data.metrics.absent;

                // 3. Build Dynamic Month Calendar Layout
                const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                document.getElementById('calendar-month-label').innerText = `${monthNames[calendarCurrentMonth - 1]} ${calendarCurrentYear}`;

                // Month is 0-indexed for Date object
                const firstDay = new Date(calendarCurrentYear, calendarCurrentMonth - 1, 1).getDay();
                const daysInMonth = new Date(calendarCurrentYear, calendarCurrentMonth, 0).getDate();

                const grid = document.getElementById('calendarGrid');
                
                // Inject Header
                grid.innerHTML = `
                    <div class="text-[10px] font-bold uppercase text-on-surface/40 py-1">S</div>
                    <div class="text-[10px] font-bold uppercase text-on-surface/40 py-1">M</div>
                    <div class="text-[10px] font-bold uppercase text-on-surface/40 py-1">T</div>
                    <div class="text-[10px] font-bold uppercase text-on-surface/40 py-1">W</div>
                    <div class="text-[10px] font-bold uppercase text-on-surface/40 py-1">T</div>
                    <div class="text-[10px] font-bold uppercase text-on-surface/40 py-1">F</div>
                    <div class="text-[10px] font-bold uppercase text-on-surface/40 py-1">S</div>
                `;

                // Empty preceding blocks
                for (let i = 0; i < firstDay; i++) {
                    grid.innerHTML += `<div></div>`;
                }

                // Grid Days
                const today = new Date();
                for (let day = 1; day <= daysInMonth; day++) {
                    const dateStr = `${calendarCurrentYear}-${String(calendarCurrentMonth).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                    const event = data.events[dateStr];
                    
                    let classes = "aspect-square rounded-md flex items-center justify-center text-sm text-on-surface/30 bg-surface-container border border-outline/20";
                    let title = "No Record";

                    const dayOfWeek = new Date(calendarCurrentYear, calendarCurrentMonth - 1, day).getDay();
                    const isWeekend = (dayOfWeek === 0 || dayOfWeek === 6);
                    if (isWeekend) {
                         classes = "aspect-square rounded-md bg-outline/10 flex items-center justify-center text-sm text-on-surface/30";
                         title = "Weekend";
                    }

                    if (event) {
                        const status = event.status ? event.status.toUpperCase() : '';
                        if (event.is_late) {
                            classes = "aspect-square rounded-md bg-warning/20 flex items-center justify-center text-sm font-bold text-warning";
                            title = "Late: " + (event.time_in || '--:--');
                        } else if (status === 'APPROVED') {
                            classes = "aspect-square rounded-md bg-success/20 flex items-center justify-center text-sm font-bold text-success";
                            title = "Present: " + (event.time_in || '--:--');
                        } else if (status === 'REJECTED') {
                            classes = "aspect-square rounded-md bg-error/20 flex items-center justify-center text-sm font-bold text-error";
                            title = "Absent/Rejected";
                        } else if (status === 'PENDING') {
                            classes = "aspect-square rounded-md bg-primary/10 flex items-center justify-center text-sm font-bold text-primary";
                            title = "Pending Approval";
                        }
                    }

                    // Highlight Today
                    const todayStr = `${today.getFullYear()}-${String(today.getMonth()+1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
                    if (dateStr === todayStr) {
                        classes += " ring-2 ring-primary ring-offset-2 border border-primary relative";
                    }

                    grid.innerHTML += `<div class="${classes}" title="${title}">${day}</div>`;
                }

            } catch (err) {
                console.error('Error fetching structural calendar logs:', err);
                alert("Could not load calendar data at this time.");
            }
        };

        const changeCalendarMonth = (offset) => {
            calendarCurrentMonth += offset;
            
            if (calendarCurrentMonth < 1) {
                calendarCurrentMonth = 12;
                calendarCurrentYear -= 1;
            } else if (calendarCurrentMonth > 12) {
                calendarCurrentMonth = 1;
                calendarCurrentYear += 1;
            }
            
            // Issue API call to reload the database values for the newly calculated target window
            fetchCalendarData();
        };

        const closePanel = () => {
            panel.setAttribute('data-open', 'false');
            sidebar.setAttribute('data-open', 'false');
            setTimeout(() => {
                panel.classList.add('hidden');
            }, 300); // Wait for transition
        };

        closeBtn.addEventListener('click', closePanel);
        panel.addEventListener('click', (e) => {
            if(e.target === panel) closePanel();
        });

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
