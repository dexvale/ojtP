<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>OJT Portal | Reports & Analytics</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Alpine.js for Tabs functionality -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
        
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-surface text-on-surface" data-theme="portal">
    <!-- SideNavBar -->
    <!-- SideNavBar (Shared Component) -->
    @include('components.coordinator-sidebar')

    <!-- TopNavBar -->
    <header class="fixed top-0 left-64 right-0 z-40 bg-surface/90 backdrop-blur-sm border-b border-[#cec3d0]/15">
        <div class="flex justify-between items-center px-8 py-4 w-full">
            <div class="flex items-center gap-8">
                <span class="text-2xl font-headline font-semibold text-primary tracking-tight">OJT Management</span>
                <nav class="hidden md:flex items-center gap-6">
                    <a class="text-sm font-semibold text-on-surface/60 hover:text-primary transition-colors duration-200" href="#">Dashboard</a>
                    <a class="text-sm font-semibold text-on-surface/60 hover:text-primary transition-colors duration-200" href="#">Student List</a>
                    <a class="text-sm font-semibold text-on-surface/60 hover:text-primary transition-colors duration-200" href="#">Company Directory</a>
                    <a class="text-sm font-semibold text-primary border-b-2 border-primary pb-1" href="#">Reports</a>
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
    <main class="ml-64 pt-24 px-8 pb-12 min-h-screen border-none" x-data="{ activeTab: 'dtr' }">
        <!-- Page Header & Global Actions -->
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between mb-8 gap-6">
            <div>
                <h1 class="text-4xl font-extrabold font-headline text-slate-900 tracking-tight">Reports & Exports</h1>
                <p class="text-slate-500 font-medium mt-2 text-sm max-w-xl">Generate, review, and export official end-of-semester documentation.</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <button
                    class="flex items-center justify-center gap-2 px-5 py-2.5 bg-green-600 text-white rounded-lg text-sm font-semibold hover:bg-green-700 hover:shadow-lg transition-all active:scale-95 shadow-sm">
                    <span class="material-symbols-outlined text-[20px]">table_view</span>
                    Export All Data (.xlsx)
                </button>
                <button
                    class="flex items-center justify-center gap-2 px-5 py-2.5 bg-slate-800 text-white rounded-lg text-sm font-semibold hover:bg-slate-900 hover:shadow-lg transition-all active:scale-95 shadow-sm">
                    <span class="material-symbols-outlined text-[20px]">picture_as_pdf</span>
                    Generate Master PDF
                </button>
            </div>
        </div>

        <!-- Navigation (Tabbed Interface) -->
        <div class="border-b border-slate-200 mb-6">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button @click="activeTab = 'dtr'"
                        :class="activeTab === 'dtr' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-semibold text-sm transition-colors">
                    DTR Monthly Summary
                </button>
                <button @click="activeTab = 'evaluations'"
                        :class="activeTab === 'evaluations' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-semibold text-sm transition-colors">
                    Supervisor Evaluations
                </button>
                <button @click="activeTab = 'completion'"
                        :class="activeTab === 'completion' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-semibold text-sm transition-colors">
                    Clearance & Completion
                </button>
            </nav>
        </div>

        <!-- Active Tab Content Area (White Card Container) -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            
            <!-- SECTION A: DTR Summary -->
            <div x-show="activeTab === 'dtr'" x-cloak class="space-y-6">
                <!-- Toolbar -->
                <div class="flex flex-col sm:flex-row justify-between gap-4">
                    <div class="flex flex-wrap gap-3">
                        <select class="form-select text-sm border-slate-200 text-slate-700 rounded-lg shadow-sm focus:ring-primary focus:border-primary px-4 py-2">
                            <option>October 2024</option>
                            <option>September 2024</option>
                            <option>August 2024</option>
                        </select>
                        <select class="form-select text-sm border-slate-200 text-slate-700 rounded-lg shadow-sm focus:ring-primary focus:border-primary px-4 py-2">
                            <option>All Courses</option>
                            <option>BS Computer Science</option>
                            <option>BS Information Technology</option>
                        </select>
                    </div>
                    <button class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-700 text-sm font-semibold rounded-lg shadow-sm hover:bg-slate-50 transition-colors">
                        <span class="material-symbols-outlined text-[18px]">download</span>
                        Export DTR (.csv)
                    </button>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto border border-slate-100 rounded-lg">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Student Name</th>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Assigned Company</th>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Rendered Hours (This Month)</th>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Accumulated Hours</th>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm font-bold text-slate-900">Jane Doe</td>
                                <td class="px-6 py-4 text-sm text-slate-600">TechNova Solutions</td>
                                <td class="px-6 py-4 text-sm font-medium text-slate-700">85 hrs</td>
                                <td class="px-6 py-4 text-sm font-medium text-slate-700">320 hrs</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-800 uppercase tracking-wide">On Track</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm font-bold text-slate-900">Mark Smith</td>
                                <td class="px-6 py-4 text-sm text-slate-600">DevCorp Inc.</td>
                                <td class="px-6 py-4 text-sm font-medium text-slate-700">60 hrs</td>
                                <td class="px-6 py-4 text-sm font-medium text-slate-700">210 hrs</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 uppercase tracking-wide">Behind Schedule</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm font-bold text-slate-900">Robert Chen</td>
                                <td class="px-6 py-4 text-sm text-slate-600">InnoSys Dynamics</td>
                                <td class="px-6 py-4 text-sm font-medium text-slate-700">92 hrs</td>
                                <td class="px-6 py-4 text-sm font-medium text-slate-700">400 hrs</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-primary/10 text-primary uppercase tracking-wide">Completed</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- SECTION B: Evaluation Summaries -->
            <div x-show="activeTab === 'evaluations'" x-cloak class="space-y-6">
                <!-- Toolbar -->
                <div class="flex flex-col sm:flex-row justify-between gap-4">
                    <div class="relative w-full sm:w-80">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                        <input type="text" placeholder="Search Student Name..." class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm shadow-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    </div>
                    <button class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-700 text-sm font-semibold rounded-lg shadow-sm hover:bg-slate-50 transition-colors">
                        <span class="material-symbols-outlined text-[18px]">download</span>
                        Export Evaluations
                    </button>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto border border-slate-100 rounded-lg">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Student Name</th>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Evaluator (HR)</th>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Technical Skill Score</th>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Soft Skill Score</th>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Final Rating (%)</th>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm font-bold text-slate-900">Jane Doe</td>
                                <td class="px-6 py-4 text-sm text-slate-600">Sarah Jenkins (TechNova)</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center text-amber-400">
                                        <span class="material-symbols-outlined text-[16px] fill-current">star</span>
                                        <span class="material-symbols-outlined text-[16px] fill-current">star</span>
                                        <span class="material-symbols-outlined text-[16px] fill-current">star</span>
                                        <span class="material-symbols-outlined text-[16px] fill-current">star</span>
                                        <span class="material-symbols-outlined text-[16px]">star_half</span>
                                    </div>
                                    <span class="text-xs text-slate-500 mt-1 block">4.5 / 5</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center text-amber-400">
                                        <span class="material-symbols-outlined text-[16px] fill-current">star</span>
                                        <span class="material-symbols-outlined text-[16px] fill-current">star</span>
                                        <span class="material-symbols-outlined text-[16px] fill-current">star</span>
                                        <span class="material-symbols-outlined text-[16px] fill-current">star</span>
                                        <span class="material-symbols-outlined text-[16px] fill-current">star</span>
                                    </div>
                                    <span class="text-xs text-slate-500 mt-1 block">5.0 / 5</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-extrabold text-green-700">95%</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button class="text-primary text-sm font-semibold hover:underline">View Full Rubric</button>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm font-bold text-slate-900">Mark Smith</td>
                                <td class="px-6 py-4 text-sm text-slate-600">David Rogers (DevCorp)</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center text-amber-400">
                                        <span class="material-symbols-outlined text-[16px] fill-current">star</span>
                                        <span class="material-symbols-outlined text-[16px] fill-current">star</span>
                                        <span class="material-symbols-outlined text-[16px] fill-current">star</span>
                                        <span class="material-symbols-outlined text-[16px] text-slate-300">star</span>
                                        <span class="material-symbols-outlined text-[16px] text-slate-300">star</span>
                                    </div>
                                    <span class="text-xs text-slate-500 mt-1 block">3.0 / 5</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center text-amber-400">
                                        <span class="material-symbols-outlined text-[16px] fill-current">star</span>
                                        <span class="material-symbols-outlined text-[16px] fill-current">star</span>
                                        <span class="material-symbols-outlined text-[16px] fill-current">star</span>
                                        <span class="material-symbols-outlined text-[16px] fill-current">star</span>
                                        <span class="material-symbols-outlined text-[16px] text-slate-300">star</span>
                                    </div>
                                    <span class="text-xs text-slate-500 mt-1 block">4.0 / 5</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-extrabold text-amber-600">70%</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button class="text-primary text-sm font-semibold hover:underline">View Full Rubric</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- SECTION C: Completion Report -->
            <div x-show="activeTab === 'completion'" x-cloak class="space-y-6">
                <!-- Alert Banner -->
                <div class="bg-green-50 rounded-lg p-4 flex items-center gap-3 border border-green-200 shadow-sm">
                    <span class="material-symbols-outlined text-green-600">verified</span>
                    <p class="text-sm font-medium text-green-800">
                        <span class="font-bold">Showing students</span> who have met all academic and hour requirements.
                    </p>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto border border-slate-100 rounded-lg">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Student Name</th>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Hours</th>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Document Status</th>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Clearance Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm font-bold text-slate-900">Robert Chen</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200 shadow-sm">
                                        400/400 (Complete)
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200 shadow-sm">
                                        All Verified
                                    </span>
                                    <p class="text-[10px] text-slate-400 mt-1 pl-1">Medical, Consent, MOA</p>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-[#3a0ca3] text-white shadow-sm tracking-wide">
                                        Ready for Grading
                                    </span>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm font-bold text-slate-900">Emily Rivera</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200 shadow-sm">
                                        400/400 (Complete)
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200 shadow-sm">
                                        All Verified
                                    </span>
                                    <p class="text-[10px] text-slate-400 mt-1 pl-1">Medical, Consent, MOA</p>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-[#3a0ca3] text-white shadow-sm tracking-wide">
                                        Ready for Grading
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>
</body>

</html>
