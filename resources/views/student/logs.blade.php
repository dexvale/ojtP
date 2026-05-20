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
<header class="fixed top-0 w-full z-50 bg-[#fff7fd] flex justify-between items-center px-6 lg:px-8 py-4 border-b border-[#cec3d0]/20 backdrop-blur-sm md:pl-64">
    <div class="flex items-center gap-4">
        <button id="sidebar-toggle" class="md:hidden p-2 text-primary rounded-lg hover:bg-surface-container transition-colors" aria-label="Toggle menu">
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
<aside id="sidebar" class="fixed left-0 top-0 h-screen w-64 z-40 bg-primary flex flex-col py-6 shadow-2xl pt-28 -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
    <div class="px-6 mb-8 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-white/10 border border-white/15 flex items-center justify-center flex-shrink-0">
            <img alt="University Logo" class="h-8 w-8 object-contain" src="{{ asset('images/logo.png') }}" />
        </div>
        <div>
            <h2 class="font-headline text-xl text-white leading-tight">OJT Portal</h2>
            <p class="text-[10px] uppercase tracking-widest text-white/50 font-medium">Academic Editorial</p>
        </div>
    </div>


    <!-- nav link -->
    <nav class="flex-grow font-['Public_Sans'] text-sm flex flex-col mt-4">

    <a href="{{ route('student.dashboard') }}" 
       class="mx-2 my-1 px-4 py-3 flex items-center gap-3 rounded-lg transition-all duration-200 
              {{ request()->routeIs('student.dashboard') ? 'bg-white/10 text-white font-semibold' : 'text-white/60 hover:text-white hover:bg-white/5 font-medium' }}">
        <span class="material-symbols-outlined">dashboard</span>
        Dashboard
    </a>

    <a href="{{ route('student.logs') }}" 
       class="mx-2 my-1 px-4 py-3 flex items-center gap-3 rounded-lg transition-all duration-200 
              {{ request()->routeIs('student.logs') ? 'bg-white/10 text-white font-semibold' : 'text-white/60 hover:text-white hover:bg-white/5 font-medium' }}">
        <span class="material-symbols-outlined">description</span>
        Internship Logs
    </a>


    <a href="{{ route('student.profile') }}" 
       class="mx-2 my-1 px-4 py-3 flex items-center gap-3 rounded-lg transition-all duration-200 
              {{ request()->routeIs('student.profile') ? 'bg-white/10 text-white font-semibold' : 'text-white/60 hover:text-white hover:bg-white/5 font-medium' }}">
        <span class="material-symbols-outlined">person</span>
        Profile
    </a>

</nav>

    <div class="pt-4 border-t border-white/10 font-body font-medium text-sm px-2">
        <a href="#" class="text-white/60 hover:text-white hover:bg-white/5 mx-0 my-1 px-4 py-3 flex items-center gap-3 rounded-lg">
            <span class="material-symbols-outlined text-xl">help</span>
            Help Center
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left text-error hover:bg-error/10 mx-0 my-1 px-4 py-3 flex items-center gap-3 rounded-lg transition-colors">
                <span class="material-symbols-outlined text-xl">logout</span>
                Logout
            </button>
        </form>
    </div>
</aside>

<!-- Sidebar overlay for mobile -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-30 hidden md:hidden" onclick="closeSidebar()"></div>

<!-- ═══════════════════════════════
     MAIN CONTENT
═══════════════════════════════ -->
<main class="md:ml-64 pt-20 min-h-screen pb-24 md:pb-8">
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
                <table class="w-full text-left border-collapse min-w-[800px]">
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
                        {{-- Row 1 --}}
                        <tr class="hover:bg-surface-container-low/50 transition-colors group">
                            <td class="py-4 pl-6 pr-4 font-bold text-sm whitespace-nowrap text-on-surface">Oct 23, 2024</td>
                            <td class="py-4 px-4 text-sm text-on-surface-variant whitespace-nowrap">08:00 AM - 05:00 PM</td>
                            <td class="py-4 px-4 text-sm text-on-surface-variant max-w-sm">
                                <span class="line-clamp-1">Implemented responsive grid system using Tailwind CSS for the client dashboard...</span>
                            </td>
                            <td class="py-4 px-4 text-sm font-bold text-on-surface whitespace-nowrap">8.0 hrs</td>
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 bg-[#b0f2c1] text-[#2e6a44] text-[10px] font-bold rounded-lg uppercase tracking-wide inline-flex items-center gap-1">
                                    Approved
                                </span>
                            </td>
                            <td class="py-4 pr-6 pl-4 text-right whitespace-nowrap">
                                <button class="text-secondary text-xs font-bold hover:underline underline-offset-4 flex items-center justify-end gap-1 ml-auto">
                                    View Entry
                                </button>
                            </td>
                        </tr>

                        {{-- Row 2 --}}
                        <tr class="hover:bg-surface-container-low/50 transition-colors group">
                            <td class="py-4 pl-6 pr-4 font-bold text-sm whitespace-nowrap text-on-surface">Oct 22, 2024</td>
                            <td class="py-4 px-4 text-sm text-on-surface-variant whitespace-nowrap">08:00 AM - 05:00 PM</td>
                            <td class="py-4 px-4 text-sm text-on-surface-variant max-w-sm">
                                <span class="line-clamp-1">Debugged API authentication middleware and optimized token refresh logic...</span>
                            </td>
                            <td class="py-4 px-4 text-sm font-bold text-on-surface whitespace-nowrap">8.0 hrs</td>
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 bg-[#b0f2c1] text-[#2e6a44] text-[10px] font-bold rounded-lg uppercase tracking-wide inline-flex items-center gap-1">
                                    Approved
                                </span>
                            </td>
                            <td class="py-4 pr-6 pl-4 text-right whitespace-nowrap">
                                <button class="text-secondary text-xs font-bold hover:underline underline-offset-4 flex items-center justify-end gap-1 ml-auto">
                                    View Entry
                                </button>
                            </td>
                        </tr>

                        {{-- Row 3 --}}
                        <tr class="hover:bg-surface-container-low/50 transition-colors group">
                            <td class="py-4 pl-6 pr-4 font-bold text-sm whitespace-nowrap text-on-surface">Oct 21, 2024</td>
                            <td class="py-4 px-4 text-sm text-on-surface-variant whitespace-nowrap">08:30 AM - 05:00 PM</td>
                            <td class="py-4 px-4 text-sm text-on-surface-variant max-w-sm">
                                <span class="line-clamp-1">Attended weekly sprint planning meeting and presented progress on UI/UX mockups...</span>
                            </td>
                            <td class="py-4 px-4 text-sm font-bold text-on-surface whitespace-nowrap">7.5 hrs</td>
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 bg-[#e8dfe6] text-[#4d444e] text-[10px] font-bold rounded-lg uppercase tracking-wide inline-flex items-center gap-1">
                                    Pending
                                </span>
                            </td>
                            <td class="py-4 pr-6 pl-4 text-right whitespace-nowrap">
                                <button class="text-secondary text-xs font-bold hover:underline underline-offset-4 flex items-center justify-end gap-1 ml-auto">
                                    <span class="material-symbols-outlined text-[14px]">edit</span>
                                    View Entry
                                </button>
                            </td>
                        </tr>

                        {{-- Row 4 --}}
                        <tr class="hover:bg-surface-container-low/50 transition-colors group">
                            <td class="py-4 pl-6 pr-4 font-bold text-sm whitespace-nowrap text-on-surface">Oct 20, 2024</td>
                            <td class="py-4 px-4 text-sm text-on-surface-variant whitespace-nowrap">08:00 AM - 05:00 PM</td>
                            <td class="py-4 px-4 text-sm text-on-surface-variant max-w-sm">
                                <span class="line-clamp-1">Worked on finalizing the database schema and created initial migrations...</span>
                            </td>
                            <td class="py-4 px-4 text-sm font-bold text-on-surface whitespace-nowrap">8.0 hrs</td>
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 bg-[#ffdad6] text-[#ba1a1a] text-[10px] font-bold rounded-lg uppercase tracking-wide inline-flex items-center gap-1">
                                    Rejected
                                </span>
                            </td>
                            <td class="py-4 pr-6 pl-4 text-right whitespace-nowrap">
                                <button class="text-secondary text-xs font-bold hover:underline underline-offset-4 flex items-center justify-end gap-1 ml-auto">
                                    View Entry
                                </button>
                            </td>
                        </tr>

                        {{-- Row 5 --}}
                        <tr class="hover:bg-surface-container-low/50 transition-colors group">
                            <td class="py-4 pl-6 pr-4 font-bold text-sm whitespace-nowrap text-on-surface">Oct 19, 2024</td>
                            <td class="py-4 px-4 text-sm text-on-surface-variant whitespace-nowrap">08:00 AM - 12:00 PM</td>
                            <td class="py-4 px-4 text-sm text-on-surface-variant max-w-sm">
                                <span class="line-clamp-1">Half-day shift. Conducted user interviews for the upcoming feature...</span>
                            </td>
                            <td class="py-4 px-4 text-sm font-bold text-on-surface whitespace-nowrap">4.0 hrs</td>
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 bg-[#b0f2c1] text-[#2e6a44] text-[10px] font-bold rounded-lg uppercase tracking-wide inline-flex items-center gap-1">
                                    Approved
                                </span>
                            </td>
                            <td class="py-4 pr-6 pl-4 text-right whitespace-nowrap">
                                <button class="text-secondary text-xs font-bold hover:underline underline-offset-4 flex items-center justify-end gap-1 ml-auto">
                                    View Entry
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 flex items-center justify-between border-t border-surface-variant/20 bg-surface-container-low/30">
                <p class="text-sm text-on-surface-variant">Showing 1 to 5 of 45 entries</p>
                <div class="flex items-center gap-1 shadow-sm rounded-lg overflow-hidden border border-surface-variant/30">
                    <button class="px-3 py-1.5 bg-surface-container-lowest text-on-surface-variant text-sm font-semibold hover:bg-surface-container transition-colors disabled:opacity-50">Previous</button>
                    <button class="px-3 py-1.5 bg-primary text-white text-sm font-bold">1</button>
                    <button class="px-3 py-1.5 bg-surface-container-lowest text-on-surface-variant hover:bg-surface-container transition-colors text-sm font-semibold">2</button>
                    <button class="px-3 py-1.5 bg-surface-container-lowest text-on-surface-variant hover:bg-surface-container transition-colors text-sm font-semibold">3</button>
                    <span class="px-2 py-1.5 bg-surface-container-lowest text-outline text-sm">...</span>
                    <button class="px-3 py-1.5 bg-surface-container-lowest text-on-surface-variant text-sm font-semibold hover:bg-surface-container transition-colors">Next</button>
                </div>
            </div>
        </section>

    </div>{{-- /Container --}}
</main>

<!-- ═══════════════════════════════
     MOBILE BOTTOM NAV
═══════════════════════════════ -->
<nav class="md:hidden fixed bottom-0 w-full bg-white/90 backdrop-blur-lg border-t border-surface-variant/20 flex justify-around items-center py-2.5 z-50">
    <a href="{{ route('student.dashboard') }}" class="flex flex-col items-center gap-0.5 text-outline px-3 py-1">
        <span class="material-symbols-outlined text-xl">dashboard</span>
        <span class="text-[10px] font-bold">Home</span>
    </a>
    <a href="{{ route('student.logs') }}" class="flex flex-col items-center gap-0.5 text-primary px-3 py-1">
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
</script>
</body>
</html>
