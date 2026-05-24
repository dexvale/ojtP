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
                    onclick="document.getElementById('addCompanyModal').classList.remove('hidden')"
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

            @forelse($companies as $company)
            <!-- Dynamic Card -->
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 border border-slate-200 overflow-hidden flex flex-col">
                <div class="p-6 pb-4 flex justify-between items-start gap-4">
                    <div class="flex gap-4 items-center">
                        <div class="w-12 h-12 rounded-xl bg-purple-100 text-primary flex items-center justify-center font-bold text-lg flex-shrink-0">
                            {{ substr(implode('', array_map(fn($w) => strtoupper($w[0] ?? ''), explode(' ', trim($company->name)))), 0, 2) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-base leading-tight">{{ $company->name }}</h3>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">{{ $company->industry }}</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide bg-green-100 text-green-800 border border-green-200 flex-shrink-0">
                        MOA: Active
                    </span>
                </div>
                <div class="px-6 pb-5 space-y-2.5">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-[18px] text-slate-400 mt-0.5">location_on</span>
                        <p class="text-sm text-slate-600">{{ $company->location }}</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-[18px] text-slate-400 mt-0.5">person</span>
                        <p class="text-sm text-slate-600">{{ $company->contact_person }} • {{ $company->contact_number }}</p>
                    </div>
                </div>
                <div class="px-6 py-5 border-t border-slate-100 bg-slate-50/50 mt-auto">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs font-semibold text-slate-600">Allocation Slots</span>
                        <span class="text-xs font-bold text-slate-900">{{ $company->filled_slots ?? 0 }} / {{ $company->allocation_slots }} Filled</span>
                    </div>
                    <div class="h-2 w-full bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full bg-primary rounded-full transition-all duration-500" style="width: {{ $company->allocation_slots > 0 ? (($company->filled_slots ?? 0) / $company->allocation_slots) * 100 : 0 }}%"></div>
                    </div>
                </div>
                <div class="p-4 bg-white border-t border-slate-100 flex gap-2">
                    <button class="flex-1 py-2 border border-slate-200 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                        Edit
                    </button>
                    <button onclick="openAddSupervisorModal({{ $company->id }}, '{{ addslashes($company->name) }}')" class="flex-1 py-2 bg-purple-50 text-purple-700 rounded-lg text-xs font-semibold hover:bg-primary hover:text-white transition-colors">
                        + Supervisor
                    </button>
                    <button class="flex-1 py-2 bg-slate-50 text-slate-700 border border-slate-200 rounded-lg text-xs font-semibold hover:bg-slate-100 transition-colors">
                        Details
                    </button>
                </div>
            </div>
            @empty
            <div class="col-span-full py-12 flex flex-col items-center justify-center text-center bg-white rounded-2xl border border-slate-200 border-dashed">
                <div class="w-16 h-16 bg-purple-50 text-primary rounded-full flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined text-3xl">apartment</span>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-1">No Companies Registered</h3>
                <p class="text-slate-500 text-sm max-w-md">You haven't added any partner companies yet. Click the "Register New Company" button to start building your directory.</p>
            </div>
            @endforelse

        </div>
    </main>

    <!-- REGISTER COMPANY MODAL -->
    <div id="addCompanyModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl border border-purple-100 p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold font-headline text-[#300050]">Register New Company</h2>
                <button onclick="document.getElementById('addCompanyModal').classList.add('hidden')" class="text-gray-400 hover:text-rose-500 transition">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <form method="POST" action="{{ route('coordinator.companies.store') }}">
                @csrf
                <div class="space-y-4 mb-8">
                    <div>
                        <label class="block text-sm font-bold text-[#300050] mb-1">Company Name</label>
                        <input type="text" name="name" required class="w-full border border-purple-100 rounded-xl px-4 py-2.5 bg-purple-50/30 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all" placeholder="e.g. TechNova Solutions">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#300050] mb-1">Industry</label>
                        <input type="text" name="industry" class="w-full border border-purple-100 rounded-xl px-4 py-2.5 bg-purple-50/30 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all" placeholder="e.g. Information Technology">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#300050] mb-1">Location</label>
                        <input type="text" name="location" class="w-full border border-purple-100 rounded-xl px-4 py-2.5 bg-purple-50/30 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all" placeholder="e.g. IT Park, Cebu City">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-[#300050] mb-1">Contact Person</label>
                            <input type="text" name="contact_person" class="w-full border border-purple-100 rounded-xl px-4 py-2.5 bg-purple-50/30 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all" placeholder="e.g. Mr. John Doe">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-[#300050] mb-1">Contact Number</label>
                            <input type="text" name="contact_number" class="w-full border border-purple-100 rounded-xl px-4 py-2.5 bg-purple-50/30 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all" placeholder="e.g. 0912-345-6789">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#300050] mb-1">Allocation Slots</label>
                        <input type="number" name="allocation_slots" value="5" min="0" required class="w-full border border-purple-100 rounded-xl px-4 py-2.5 bg-purple-50/30 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all">
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('addCompanyModal').classList.add('hidden')" class="flex-1 px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-bold hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 bg-purple-950 hover:bg-purple-900 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md transition">
                        Save Company
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ADD SUPERVISOR MODAL -->
    <div id="addSupervisorModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl border border-purple-100 p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold font-headline text-[#300050]">Add Supervisor</h2>
                <button onclick="document.getElementById('addSupervisorModal').classList.add('hidden')" class="text-gray-400 hover:text-rose-500 transition">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <p class="text-sm text-gray-600 mb-6">Provisioning an account for: <span id="supervisor-company-name" class="font-bold text-purple-900"></span></p>

            <form method="POST" action="{{ route('coordinator.supervisors.store') }}">
                @csrf
                <input type="hidden" name="company_id" id="supervisor-company-id" value="">
                
                <div class="space-y-4 mb-8">
                    <div>
                        <label class="block text-sm font-bold text-[#300050] mb-1">Full Name</label>
                        <input type="text" name="name" required class="w-full border border-purple-100 rounded-xl px-4 py-2.5 bg-purple-50/30 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all" placeholder="e.g. John Doe">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#300050] mb-1">Email Address</label>
                        <input type="email" name="email" required class="w-full border border-purple-100 rounded-xl px-4 py-2.5 bg-purple-50/30 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all" placeholder="e.g. john@company.com">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#300050] mb-1">Temporary Password</label>
                        <input type="text" name="password" required value="Welcome123!" class="w-full border border-purple-100 rounded-xl px-4 py-2.5 bg-purple-50/30 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all">
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('addSupervisorModal').classList.add('hidden')" class="flex-1 px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-bold hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 bg-purple-950 hover:bg-purple-900 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md transition">
                        Create Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddSupervisorModal(companyId, companyName) {
            document.getElementById('supervisor-company-id').value = companyId;
            document.getElementById('supervisor-company-name').innerText = companyName;
            document.getElementById('addSupervisorModal').classList.remove('hidden');
        }
    </script>
</body>

</html>
