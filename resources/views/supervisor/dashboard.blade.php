<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>OJT Portal | Supervisor Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:opsz,wght@6..72,400;6..72,600;6..72,700;6..72,800&family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Public Sans', sans-serif; }
        .font-headline { font-family: 'Newsreader', serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .material-icon-filled { font-family: 'Material Symbols Outlined'; font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        /* Remove arrows from number input */
        input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }
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

    <!-- MAIN DASHBOARD CONTENT -->
    <main class="lg:ml-64 ml-0 pt-20 md:pt-24 px-4 sm:px-6 lg:px-8 pb-12">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
            <div>
                <h1 class="text-4xl font-extrabold font-headline text-primary tracking-tight">Team Overview</h1>
                <p class="text-on-surface/60 font-medium mt-1">Monitor intern attendance, performance, and pending approvals.</p>
            </div>
            <div class="flex items-center gap-2 bg-surface-container px-4 py-2 rounded-lg shadow-sm border border-outline/30">
                <span class="material-symbols-outlined text-outline">calendar_today</span>
                <span class="text-sm font-bold text-on-surface/80">Today: {{ now()->format('F j, Y') }}</span>
            </div>
        </div>

        <!-- Top Row: Headcount KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card 1 -->
            <div class="bg-surface-container p-6 rounded-2xl shadow-sm border border-outline/30 flex flex-col justify-center relative overflow-hidden group hover:border-primary/30 transition-colors">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-primary/5 rounded-full group-hover:scale-150 transition-transform duration-500 pointer-events-none"></div>
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center text-primary shadow-inner">
                        <span class="material-symbols-outlined text-2xl font-bold">groups</span>
                    </div>
                    <span class="text-sm font-bold text-on-surface/60 uppercase tracking-widest">Total Assigned Interns</span>
                </div>
                <div>
                    <h2 class="text-3xl font-extrabold font-headline text-on-surface">{{ $totalInterns }} <span class="text-xs text-gray-400">Students</span></h2>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-surface-container p-6 rounded-2xl shadow-sm border border-outline/30 flex flex-col justify-center relative overflow-hidden group hover:border-success/30 transition-colors">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-success/5 rounded-full group-hover:scale-150 transition-transform duration-500 pointer-events-none"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-success/10 rounded-xl flex items-center justify-center text-success shadow-inner">
                            <span class="material-symbols-outlined text-2xl font-bold">how_to_reg</span>
                        </div>
                        <span class="text-sm font-bold text-on-surface/60 uppercase tracking-widest">Currently Clocked In</span>
                    </div>
                    <!-- Live Badge -->
                    <div class="flex items-center gap-2 bg-success/10 px-2.5 py-1 rounded-full border border-success/20">
                        <span class="w-2 h-2 rounded-full bg-success animate-pulse"></span>
                        <span class="text-[10px] font-bold text-success uppercase tracking-widest">Live</span>
                    </div>
                </div>
                <div>
                    <h2 class="text-3xl font-extrabold font-headline text-on-surface">{{ $clockedInCount }} / {{ $totalInterns }}</h2>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-surface-container p-6 rounded-2xl shadow-sm border border-outline/30 flex flex-col justify-center relative overflow-hidden group hover:border-warning/30 transition-colors">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-warning/5 rounded-full group-hover:scale-150 transition-transform duration-500 pointer-events-none"></div>
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-warning/20 rounded-xl flex items-center justify-center text-warning shadow-inner">
                        <span class="material-symbols-outlined text-2xl font-bold">pending_actions</span>
                    </div>
                    <span class="text-sm font-bold text-on-surface/60 uppercase tracking-widest">Hours Pending Review</span>
                </div>
                <div class="flex items-end justify-between">
                    <h2 class="text-3xl font-extrabold font-headline text-warning">{{ number_format($pendingHours, 2) }} <span class="text-xs text-gray-400">hrs</span></h2>
                    <span class="text-xs font-bold text-error flex items-center gap-1 bg-error/10 px-2 py-0.5 rounded border border-error/20">
                        <span class="material-symbols-outlined text-[14px]">error</span> {{ $pendingLogs->count() }} Action(s) Required
                    </span>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
            
            <!-- Left Side: Weekly Attendance Chart (Span 2) -->
            <div class="lg:col-span-2 bg-surface-container p-7 rounded-2xl shadow-sm border border-outline/30 flex flex-col h-[400px]">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold font-headline text-on-surface tracking-tight">Weekly Attendance Trends</h2>
                    <button class="p-1.5 hover:bg-surface-container-high rounded-full text-on-surface/50 transition-colors active:scale-95"><span class="material-symbols-outlined text-[20px]">more_vert</span></button>
                </div>
                
                <!-- ApexChart Container -->
                <div id="weeklyTrendsChart" class="w-full h-64"></div>
            </div>

            <!-- Right Side: Top Performers List (Span 1) -->
            <div class="lg:col-span-1 bg-surface-container rounded-2xl shadow-sm border border-outline/30 p-7 flex flex-col h-[400px]">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold font-headline text-on-surface tracking-tight">Top Performers</h2>
                    <span class="text-[10px] font-bold text-on-surface/40 uppercase tracking-widest leading-tight text-right w-16">(Hours<br>Rendered)</span>
                </div>
                
                <div class="flex-1 flex flex-col gap-6 overflow-y-auto pr-1">
                    @forelse($topPerformers as $index => $profile)
                        @php
                            $approvedHours = $profile->user->ojt_logs_sum_hours_rendered ?? 0;
                            $requiredHours = $profile->academicCourse->required_hours ?? $profile->required_hours ?? 400;
                            $progressPercent = min(($approvedHours / max($requiredHours, 1)) * 100, 100);
                            $studentName = $profile->user->display_name ?? trim($profile->first_name . ' ' . $profile->last_name);
                            $initials = substr(implode('', array_map(fn($w) => strtoupper($w[0] ?? ''), explode(' ', $studentName))), 0, 2);
                        @endphp
                        <!-- Rank {{ $index + 1 }} -->
                        <div class="flex items-center gap-4 group">
                            <div class="w-12 h-12 rounded-full border {{ $index === 0 ? 'border-warning/50 bg-warning/10 text-warning' : 'border-outline/30 bg-surface text-on-surface/60' }} shadow-sm flex items-center justify-center font-extrabold text-sm relative flex-shrink-0">
                                {{ $initials }}
                                <div class="absolute -top-2 -right-2 bg-surface-container rounded-full shadow-sm border border-outline/20 flex items-center justify-center {{ $index === 0 ? 'p-1' : 'w-6 h-6' }}">
                                    @if($index === 0)
                                        <span class="material-symbols-outlined text-[14px] text-warning" style="font-variation-settings: 'FILL' 1;">military_tech</span>
                                    @else
                                        <span class="text-[10px] font-extrabold text-outline">#{{ $index + 1 }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-end mb-1">
                                    <div class="truncate mr-2">
                                        <p class="text-sm font-bold text-on-surface group-hover:text-primary transition-colors truncate">{{ $studentName }}</p>
                                        <p class="text-[10px] font-semibold text-on-surface/50 truncate">{{ $profile->course ?? 'Intern' }}</p>
                                    </div>
                                    <span class="text-[10px] font-bold {{ $index === 0 ? 'text-primary bg-primary/5 border border-primary/10' : 'text-on-surface/70 bg-surface border border-outline/20' }} px-2 py-0.5 rounded whitespace-nowrap">{{ number_format($approvedHours, 2) }}/{{ $requiredHours }} hrs</span>
                                </div>
                                <div class="w-full h-1.5 bg-outline/20 rounded-full overflow-hidden mt-2">
                                    <div class="h-full bg-primary{{ $index > 0 ? '/'.(90 - ($index * 10)) : '' }} rounded-full transition-all" style="width: {{ $progressPercent }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-sm font-bold text-on-surface/40">
                            No approved hours recorded yet.
                        </div>
                    @endforelse
                </div>
                
                <a href="{{ route('supervisor.leaderboard') }}" class="w-full mt-auto pt-4 text-xs font-bold text-primary hover:text-primary/80 transition-colors flex items-center justify-center gap-1">
                    View Full Leaderboard <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var options = {
                series: [{
                    name: 'Students Present',
                    data: @json($attendanceCounts)
                }],
                chart: {
                    type: 'area',
                    height: 250,
                    toolbar: { show: false },
                    fontFamily: 'Inter, sans-serif'
                },
                colors: ['#5B21B6'], /* Matching our deep purple theme */
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.05,
                        stops: [0, 90, 100]
                    }
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                xaxis: {
                    categories: ['MON', 'TUE', 'WED', 'THU', 'FRI'],
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    tickAmount: 3,
                    labels: {
                        formatter: function(val) { return Math.floor(val); }
                    }
                },
                grid: {
                    borderColor: '#F3F4F6',
                    strokeDashArray: 4
                }
            };

            var chart = new ApexCharts(document.querySelector("#weeklyTrendsChart"), options);
            chart.render();
        });

        // Responsive Sidebar Toggle
        const sidebarEl = document.getElementById('sidebar');
        const overlayEl = document.getElementById('sidebar-overlay');
        const toggleBtnEl = document.getElementById('sidebar-toggle');

        function toggleSidebar() {
            sidebarEl.classList.toggle('-translate-x-full');
            overlayEl.classList.toggle('hidden');
        }

        function closeSidebar() {
            sidebarEl?.classList.add('-translate-x-full');
            overlayEl?.classList.add('hidden');
        }

        toggleBtnEl?.addEventListener('click', toggleSidebar);
        overlayEl?.addEventListener('click', closeSidebar);

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
