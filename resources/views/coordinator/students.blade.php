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
    <aside
        class="fixed left-0 top-0 h-screen w-64 z-50 bg-[#300050] text-[#ffffff] flex flex-col py-6 gap-2 shadow-[32px_0_64px_rgba(30,26,31,0.05)]">
        <div class="px-6 mb-8 flex items-center gap-3">
            <img alt="University Logo" class="h-8 w-8 object-contain" src="{{ asset('images/logo.png') }}" onerror="this.src='https://ui-avatars.com/api/?name=OJT&background=3a0ca3&color=fff'" />
            <div>
                <h1 class="font-headline text-xl text-[#ffffff]">OJT Portal</h1>
                <p class="text-[10px] uppercase tracking-widest text-[#faf1f8]/70">Academic Editorial</p>
            </div>
        </div>
        <nav class="flex-1">
            <a href="{{ route('coordinator.dashboard') }}" 
               class="mx-2 my-1 px-4 py-3 flex items-center gap-3 text-sm font-medium rounded-lg transition-all duration-300 {{ request()->routeIs('coordinator.dashboard') ? 'bg-[#faf1f8]/10 text-[#ffffff] translate-x-1' : 'text-[#faf1f8]/70 hover:text-[#ffffff] hover:bg-[#ffffff]/5' }}">
                <span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('coordinator.students') }}" 
               class="mx-2 my-1 px-4 py-3 flex items-center gap-3 text-sm font-medium rounded-lg transition-all duration-300 {{ request()->routeIs('coordinator.students') ? 'bg-[#faf1f8]/10 text-[#ffffff] translate-x-1' : 'text-[#faf1f8]/70 hover:text-[#ffffff] hover:bg-[#ffffff]/5' }}">
                <span class="material-symbols-outlined" data-icon="groups">groups</span>
                <span>Student List</span>
            </a>
            <a href="{{ route('coordinator.companies') }}" 
               class="mx-2 my-1 px-4 py-3 flex items-center gap-3 text-sm font-medium rounded-lg transition-all duration-300 {{ request()->routeIs('coordinator.companies') ? 'bg-[#faf1f8]/10 text-[#ffffff] translate-x-1' : 'text-[#faf1f8]/70 hover:text-[#ffffff] hover:bg-[#ffffff]/5' }}">
                <span class="material-symbols-outlined" data-icon="business">business</span>
                <span>Company Directory</span>
            </a>
            <a href="{{ route('coordinator.reports') }}" 
               class="mx-2 my-1 px-4 py-3 flex items-center gap-3 text-sm font-medium rounded-lg transition-all duration-300 {{ request()->routeIs('coordinator.reports') ? 'bg-[#faf1f8]/10 text-[#ffffff] translate-x-1' : 'text-[#faf1f8]/70 hover:text-[#ffffff] hover:bg-[#ffffff]/5' }}">
                <span class="material-symbols-outlined" data-icon="assessment">assessment</span>
                <span>Reports</span>
            </a>
        </nav>
        <div class="mt-auto pt-4 border-t border-[#ffffff]/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left text-[#faf1f8]/70 hover:text-[#ffffff] mx-2 my-1 px-4 py-3 flex items-center gap-3 text-sm font-medium hover:bg-[#ffffff]/5 transition-all duration-300">
                    <span class="material-symbols-outlined" data-icon="logout">logout</span>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- TopNavBar -->
    <header class="fixed top-0 left-64 right-0 z-40 bg-surface/90 backdrop-blur-sm border-b border-[#cec3d0]/15">
        <div class="flex justify-between items-center px-8 py-4 w-full">
            <div class="flex items-center gap-8">
                <span class="text-2xl font-headline font-semibold text-primary tracking-tight">OJT Management</span>
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
                            <p class="text-sm font-bold font-headline text-primary">Dr. Elena Vance</p>
                            <p class="text-[10px] uppercase tracking-wider text-secondary font-bold">OJT Coordinator</p>
                        </div>
                        <img alt="User profile avatar"
                            class="w-9 h-9 rounded-full object-cover ring-2 ring-primary/10"
                            src="https://ui-avatars.com/api/?name=Elena+Vance&background=3a0ca3&color=fff">
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="ml-64 pt-24 px-8 pb-12 min-h-screen border-none">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-8 gap-4">
            <div>
                <h1 class="text-4xl font-extrabold font-headline text-slate-900 tracking-tight">Student Directory</h1>
                <p class="text-slate-500 font-medium mt-1 text-sm">Manage and track all OJT intern placements and progress.</p>
            </div>
            <div class="flex gap-3">
                <button
                    class="flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:shadow-lg hover:-translate-y-0.5 transition-all active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    New Student
                </button>
            </div>
        </div>

        <!-- Top Control Bar -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <!-- Search Bar -->
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input type="text" 
                    class="w-full bg-white border border-slate-200 rounded-xl py-3 pl-12 pr-4 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary shadow-sm transition-all"
                    placeholder="Search by Student Name or ID...">
            </div>
            
            <!-- Filters -->
            <div class="flex gap-4">
                <div class="relative">
                    <select class="appearance-none bg-white border border-slate-200 rounded-xl py-3 pl-4 pr-10 text-sm font-medium text-slate-700 min-w-[140px] focus:ring-2 focus:ring-primary/20 focus:border-primary shadow-sm cursor-pointer transition-all">
                        <option value="all">All Courses</option>
                        <option value="bscs">BSCS</option>
                        <option value="bsit">BSIT</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                </div>

                <div class="relative">
                    <select class="appearance-none bg-white border border-slate-200 rounded-xl py-3 pl-4 pr-10 text-sm font-medium text-slate-700 min-w-[140px] focus:ring-2 focus:ring-primary/20 focus:border-primary shadow-sm cursor-pointer transition-all">
                        <option value="all">All Years</option>
                        <option value="3rd">3rd Year</option>
                        <option value="4th">4th Year</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                </div>
            </div>
        </div>

        <!-- Data Table (Card Container) -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200">
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Student Name</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Course & Year</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Placement Status</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">OJT Progress (400 hrs)</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <!-- Row 1: Deployed & In Progress (BSCS) -->
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img class="w-10 h-10 rounded-full object-cover shadow-sm"
                                        src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-1.2.1&auto=format&fit=crop&w=64&h=64&q=80" alt="Avatar">
                                    <div>
                                        <p class="text-sm font-bold text-slate-900 group-hover:text-primary transition-colors">Jane Doe</p>
                                        <p class="text-xs text-slate-500">STD-2021-0145</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-slate-900">BSCS</p>
                                <p class="text-xs text-slate-500">4th Year</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200/50">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-600 mr-1.5"></span>
                                    Deployed
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="w-full max-w-[180px]">
                                    <div class="flex justify-between items-center mb-1.5">
                                        <span class="text-xs font-semibold text-slate-700">320 / 400 hrs</span>
                                        <span class="text-xs font-bold text-primary">80%</span>
                                    </div>
                                    <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden shadow-inner">
                                        <div class="h-full bg-primary rounded-full transition-all duration-500" style="width: 80%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-primary/30 text-primary text-xs font-semibold rounded-lg hover:bg-purple-50 hover:border-primary/50 transition-all focus:ring-2 focus:ring-primary/20">
                                    View Profile
                                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                                </button>
                            </td>
                        </tr>

                        <!-- Row 2: Pending Placement (BSIT) -->
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold shadow-sm">
                                        MS
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900 group-hover:text-primary transition-colors">Mark Smith</p>
                                        <p class="text-xs text-slate-500">STD-2022-0092</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-slate-900">BSIT</p>
                                <p class="text-xs text-slate-500">3rd Year</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200/50">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-600 mr-1.5"></span>
                                    Pending Placement
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="w-full max-w-[180px]">
                                    <div class="flex justify-between items-center mb-1.5">
                                        <span class="text-xs font-semibold text-slate-700">0 / 400 hrs</span>
                                        <span class="text-xs font-bold text-slate-400">0%</span>
                                    </div>
                                    <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden shadow-inner">
                                        <div class="h-full bg-primary rounded-full transition-all duration-500" style="width: 0%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-primary/30 text-primary text-xs font-semibold rounded-lg hover:bg-purple-50 hover:border-primary/50 transition-all focus:ring-2 focus:ring-primary/20">
                                    View Profile
                                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                                </button>
                            </td>
                        </tr>

                        <!-- Row 3: Completed (BSCS) -->
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img class="w-10 h-10 rounded-full object-cover shadow-sm"
                                        src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-1.2.1&auto=format&fit=crop&w=64&h=64&q=80" alt="Avatar">
                                    <div>
                                        <p class="text-sm font-bold text-slate-900 group-hover:text-primary transition-colors">Robert Chen</p>
                                        <p class="text-xs text-slate-500">STD-2021-0305</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-slate-900">BSCS</p>
                                <p class="text-xs text-slate-500">4th Year</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-600 text-white shadow-sm">
                                    <span class="material-symbols-outlined text-[12px] mr-1">check_circle</span>
                                    Completed
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="w-full max-w-[180px]">
                                    <div class="flex justify-between items-center mb-1.5">
                                        <span class="text-xs font-semibold text-slate-700">400 / 400 hrs</span>
                                        <span class="text-xs font-bold text-green-600">100%</span>
                                    </div>
                                    <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden shadow-inner">
                                        <div class="h-full bg-green-500 rounded-full transition-all duration-500" style="width: 100%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-primary/30 text-primary text-xs font-semibold rounded-lg hover:bg-purple-50 hover:border-primary/50 transition-all focus:ring-2 focus:ring-primary/20">
                                    View Profile
                                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                                </button>
                            </td>
                        </tr>

                        <!-- Row 4: Deployed & Low Progress (BSIT) -->
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img class="w-10 h-10 rounded-full object-cover shadow-sm"
                                        src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-1.2.1&auto=format&fit=crop&w=64&h=64&q=80" alt="Avatar">
                                    <div>
                                        <p class="text-sm font-bold text-slate-900 group-hover:text-primary transition-colors">Emily Rivera</p>
                                        <p class="text-xs text-slate-500">STD-2022-0188</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-slate-900">BSIT</p>
                                <p class="text-xs text-slate-500">3rd Year</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200/50">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-600 mr-1.5"></span>
                                    Deployed
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="w-full max-w-[180px]">
                                    <div class="flex justify-between items-center mb-1.5">
                                        <span class="text-xs font-semibold text-slate-700">80 / 400 hrs</span>
                                        <span class="text-xs font-bold text-primary">20%</span>
                                    </div>
                                    <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden shadow-inner">
                                        <div class="h-full bg-primary rounded-full transition-all duration-500" style="width: 20%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-primary/30 text-primary text-xs font-semibold rounded-lg hover:bg-purple-50 hover:border-primary/50 transition-all focus:ring-2 focus:ring-primary/20">
                                    View Profile
                                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination Footer -->
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50 flex items-center justify-between">
                <p class="text-xs text-slate-500 font-medium">Showing <span class="font-bold text-slate-700">1</span> to <span class="font-bold text-slate-700">4</span> of <span class="font-bold text-slate-700">248</span> interns</p>
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
</body>

</html>
