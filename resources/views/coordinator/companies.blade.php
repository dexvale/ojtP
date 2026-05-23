<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>OJT Portal | Company Directory</title>
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
            vertical-align: middle;
        }
    </style>
</head>

<body class="bg-surface text-on-surface" data-theme="portal">
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
                    <a class="text-sm font-semibold text-primary border-b-2 border-primary pb-1" href="#">Company Directory</a>
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
        
        <!-- Page Header & Global Actions -->
        <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-8 gap-4">
            <div>
                <h1 class="text-4xl font-extrabold font-headline text-slate-900 tracking-tight">Company Directory</h1>
                <p class="text-slate-500 font-medium mt-1 text-sm">Manage partner organizations, track MOA renewals, and monitor intern allocation slots.</p>
            </div>
            <div class="flex gap-3">
                <button
                    class="flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:shadow-lg hover:-translate-y-0.5 transition-all active:scale-95 shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    Register New Company
                </button>
            </div>
        </div>

        <!-- Top Control Bar -->
        <div class="flex flex-col md:flex-row gap-4 mb-8 border border-slate-200 bg-white p-4 rounded-xl shadow-sm">
            <!-- Search Bar -->
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input type="text" 
                    class="w-full bg-slate-50 border-none rounded-lg py-3 pl-12 pr-4 text-sm focus:ring-2 focus:ring-primary/20 transition-all"
                    placeholder="Search companies or contact persons...">
            </div>
            
            <!-- Filters -->
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="relative">
                    <select class="appearance-none bg-slate-50 border-none rounded-lg py-3 pl-4 pr-10 text-sm font-medium text-slate-700 min-w-[200px] sm:min-w-[180px] focus:ring-2 focus:ring-primary/20 cursor-pointer transition-all">
                        <option value="all">All MOA Statuses</option>
                        <option value="active">Active MOA</option>
                        <option value="pending">Expired / Pending</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                </div>

                <div class="relative">
                    <select class="appearance-none bg-slate-50 border-none rounded-lg py-3 pl-4 pr-10 text-sm font-medium text-slate-700 min-w-[200px] sm:min-w-[180px] focus:ring-2 focus:ring-primary/20 cursor-pointer transition-all">
                        <option value="all">All Slot Availability</option>
                        <option value="available">Has Available Slots</option>
                        <option value="full">Full Capacity</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                </div>
            </div>
        </div>

        <!-- Company Profiles Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- Card 1: Active, partial capacity -->
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 border border-slate-200 overflow-hidden flex flex-col">
                <div class="p-6 pb-4 flex justify-between items-start gap-4">
                    <div class="flex gap-4 items-center">
                        <div class="w-12 h-12 rounded-xl bg-purple-100 text-primary flex items-center justify-center font-bold text-lg flex-shrink-0">
                            TN
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-base leading-tight">TechNova Solutions</h3>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">Information Technology</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide bg-green-100 text-green-800 border border-green-200 flex-shrink-0">
                        MOA: Active
                    </span>
                </div>
                <div class="px-6 pb-5 space-y-2.5">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-[18px] text-slate-400 mt-0.5">location_on</span>
                        <p class="text-sm text-slate-600">IT Park, Cebu City</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-[18px] text-slate-400 mt-0.5">person</span>
                        <p class="text-sm text-slate-600">Mr. John Doe • 0912-345-6789</p>
                    </div>
                </div>
                <div class="px-6 py-5 border-t border-slate-100 bg-slate-50/50 mt-auto">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs font-semibold text-slate-600">Allocation Slots</span>
                        <span class="text-xs font-bold text-slate-900">2 / 5 Filled</span>
                    </div>
                    <div class="h-2 w-full bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full bg-primary rounded-full transition-all duration-500" style="width: 40%"></div>
                    </div>
                </div>
                <div class="p-4 bg-white border-t border-slate-100 flex gap-3">
                    <button class="flex-1 py-2.5 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                        Edit
                    </button>
                    <button class="flex-1 py-2.5 bg-purple-50 text-purple-700 rounded-xl text-sm font-semibold hover:bg-primary hover:text-white transition-colors">
                        View Details
                    </button>
                </div>
            </div>

            <!-- Card 2: Expired MOA -->
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 border border-slate-200 overflow-hidden flex flex-col">
                <div class="p-6 pb-4 flex justify-between items-start gap-4">
                    <div class="flex gap-4 items-center">
                        <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-lg flex-shrink-0">
                            GF
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-base leading-tight">Global Finance</h3>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">Banking & Finance</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide bg-red-100 text-red-700 border border-red-200 flex-shrink-0">
                        MOA: Expired
                    </span>
                </div>
                <div class="px-6 pb-5 space-y-2.5">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-[18px] text-slate-400 mt-0.5">location_on</span>
                        <p class="text-sm text-slate-600">Paseo de Roxas, Makati City</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-[18px] text-slate-400 mt-0.5">person</span>
                        <p class="text-sm text-slate-600">Ms. Sarah Jenkins • 0998-123-4567</p>
                    </div>
                </div>
                <div class="px-6 py-5 border-t border-slate-100 bg-slate-50/50 mt-auto opacity-75">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs font-semibold text-slate-600">Allocation Slots</span>
                        <span class="text-xs font-bold text-slate-900">0 / 2 Filled</span>
                    </div>
                    <div class="h-2 w-full bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full bg-slate-400 rounded-full transition-all duration-500" style="width: 0%"></div>
                    </div>
                </div>
                <div class="p-4 bg-white border-t border-slate-100 flex gap-3">
                    <button class="flex-1 py-2.5 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                        Edit
                    </button>
                    <button class="flex-1 py-2.5 bg-purple-50 text-purple-700 rounded-xl text-sm font-semibold hover:bg-primary hover:text-white transition-colors">
                        View Details
                    </button>
                </div>
            </div>

            <!-- Card 3: Active, Full Capacity -->
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 border border-slate-200 overflow-hidden flex flex-col">
                <div class="p-6 pb-4 flex justify-between items-start gap-4">
                    <div class="flex gap-4 items-center">
                        <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-lg flex-shrink-0">
                            WS
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-base leading-tight">WebSystems Studio</h3>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">Software Development</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide bg-green-100 text-green-800 border border-green-200 flex-shrink-0">
                        MOA: Active
                    </span>
                </div>
                <div class="px-6 pb-5 space-y-2.5">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-[18px] text-slate-400 mt-0.5">location_on</span>
                        <p class="text-sm text-slate-600">BGC, Taguig</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-[18px] text-slate-400 mt-0.5">person</span>
                        <p class="text-sm text-slate-600">Engr. Robert Chen • 0917-888-9999</p>
                    </div>
                </div>
                <div class="px-6 py-5 border-t border-slate-100 bg-amber-50 rounded-b-none mt-auto">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs font-bold text-amber-700">Allocation Slots</span>
                        <span class="text-xs font-black text-amber-700">5 / 5 Filled</span>
                    </div>
                    <div class="h-2 w-full bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full bg-amber-500 rounded-full transition-all duration-500" style="width: 100%"></div>
                    </div>
                </div>
                <div class="p-4 bg-white border-t border-slate-100 flex gap-3">
                    <button class="flex-1 py-2.5 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                        Edit
                    </button>
                    <button class="flex-1 py-2.5 bg-purple-50 text-purple-700 rounded-xl text-sm font-semibold hover:bg-primary hover:text-white transition-colors">
                        View Details
                    </button>
                </div>
            </div>

            <!-- Card 4: Pending MOA -->
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 border border-slate-200 overflow-hidden flex flex-col">
                <div class="p-6 pb-4 flex justify-between items-start gap-4">
                    <div class="flex gap-4 items-center">
                        <div class="w-12 h-12 rounded-xl bg-pink-100 text-pink-600 flex items-center justify-center font-bold text-lg flex-shrink-0">
                            CS
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-base leading-tight">Creativ Studios</h3>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">Multimedia Arts</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide bg-amber-100 text-amber-800 border border-amber-200 flex-shrink-0">
                        MOA: Pending
                    </span>
                </div>
                <div class="px-6 pb-5 space-y-2.5">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-[18px] text-slate-400 mt-0.5">location_on</span>
                        <p class="text-sm text-slate-600">Magallanes Village, Makati</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-[18px] text-slate-400 mt-0.5">person</span>
                        <p class="text-sm text-slate-600">Ms. Emily Rivera • 0922-111-2222</p>
                    </div>
                </div>
                <div class="px-6 py-5 border-t border-slate-100 bg-slate-50/50 mt-auto">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs font-semibold text-slate-600">Allocation Slots</span>
                        <span class="text-xs font-bold text-slate-900">3 / 4 Filled</span>
                    </div>
                    <div class="h-2 w-full bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full bg-primary rounded-full transition-all duration-500" style="width: 75%"></div>
                    </div>
                </div>
                <div class="p-4 bg-white border-t border-slate-100 flex gap-3">
                    <button class="flex-1 py-2.5 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                        Edit
                    </button>
                    <button class="flex-1 py-2.5 bg-purple-50 text-purple-700 rounded-xl text-sm font-semibold hover:bg-primary hover:text-white transition-colors">
                        View Details
                    </button>
                </div>
            </div>

            <!-- Card 5: Active MOA, abundant space -->
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 border border-slate-200 overflow-hidden flex flex-col">
                <div class="p-6 pb-4 flex justify-between items-start gap-4">
                    <div class="flex gap-4 items-center">
                        <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center font-bold text-lg flex-shrink-0">
                            ID
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-base leading-tight">InnoSys Dynamics</h3>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">Network Engineering</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide bg-green-100 text-green-800 border border-green-200 flex-shrink-0">
                        MOA: Active
                    </span>
                </div>
                <div class="px-6 pb-5 space-y-2.5">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-[18px] text-slate-400 mt-0.5">location_on</span>
                        <p class="text-sm text-slate-600">Alabang, Muntinlupa</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-[18px] text-slate-400 mt-0.5">person</span>
                        <p class="text-sm text-slate-600">Engr. Alex Torres • 0915-555-7777</p>
                    </div>
                </div>
                <div class="px-6 py-5 border-t border-slate-100 bg-slate-50/50 mt-auto">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs font-semibold text-slate-600">Allocation Slots</span>
                        <span class="text-xs font-bold text-slate-900">1 / 10 Filled</span>
                    </div>
                    <div class="h-2 w-full bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full bg-primary rounded-full transition-all duration-500" style="width: 10%"></div>
                    </div>
                </div>
                <div class="p-4 bg-white border-t border-slate-100 flex gap-3">
                    <button class="flex-1 py-2.5 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                        Edit
                    </button>
                    <button class="flex-1 py-2.5 bg-purple-50 text-purple-700 rounded-xl text-sm font-semibold hover:bg-primary hover:text-white transition-colors">
                        View Details
                    </button>
                </div>
            </div>

        </div>
    </main>
</body>

</html>
