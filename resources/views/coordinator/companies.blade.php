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

        @if(session('flash_password'))
            <div id="credential-flash-banner" class="bg-purple-50 border border-purple-200 rounded-xl p-5 mb-8 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-purple-600"></div>
                <button onclick="document.getElementById('credential-flash-banner').remove()" class="absolute top-4 right-4 text-purple-400 hover:text-purple-600 transition-colors">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
                
                <h3 class="text-lg font-bold text-[#300050] mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-purple-600">key</span>
                    Advisor Credentials Provisioned
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
                    <div class="bg-white p-3 rounded-lg border border-purple-100">
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Workplace</span>
                        <span class="text-sm font-semibold text-slate-800">{{ session('flash_company') }}</span>
                    </div>
                    <div class="bg-white p-3 rounded-lg border border-purple-100">
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Login Email</span>
                        <span class="text-sm font-semibold text-slate-800">{{ session('flash_email') }}</span>
                    </div>
                    <div class="bg-white p-3 rounded-lg border border-purple-100">
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Temporary Password</span>
                        <span class="text-sm font-semibold text-slate-800 font-mono bg-purple-100/50 px-2 py-0.5 rounded text-purple-700">{{ session('flash_password') }}</span>
                    </div>
                </div>

                <button onclick="navigator.clipboard.writeText('Email: {{ session('flash_email') }}\nPassword: {{ session('flash_password') }}'); alert('Credentials copied to clipboard!');" class="inline-flex items-center gap-2 px-4 py-2 bg-white text-purple-700 border border-purple-200 rounded-lg text-sm font-bold hover:bg-purple-100 transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">content_copy</span>
                    Copy Connection Details
                </button>
            </div>
        @endif

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
                <!-- Action Buttons Row -->
                <div class="grid grid-cols-3 gap-2 mt-5 pt-4 border-t border-gray-100 p-3 bg-white">
                    
                    <!-- Edit Button -->
                    <button onclick="openEditModal({{ $company }})" class="flex items-center justify-center gap-1 py-2 text-xs font-medium text-gray-600 transition-colors bg-white border border-gray-200 rounded-md hover:bg-gray-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        Edit
                    </button>

                    <!-- Info Button -->
                    <a href="{{ route('coordinator.companies.show', $company->id) }}" class="flex items-center justify-center gap-1 py-2 text-xs font-medium text-gray-600 transition-colors bg-white border border-gray-200 rounded-md hover:bg-gray-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        Info
                    </a>

                    <!-- Delete Form -->
                    <form action="{{ route('coordinator.companies.destroy', $company->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this company?');" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex items-center justify-center w-full gap-1 py-2 text-xs font-medium text-red-600 transition-colors bg-red-50 border border-red-100 rounded-md hover:bg-red-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Del
                        </button>
                    </form>

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

    <!-- ADD COMPANY MODAL -->
    <div id="addCompanyModal" class="{{ $errors->any() ? '' : 'hidden' }} fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl border border-purple-100 p-6 w-full max-w-md mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold font-headline text-[#300050]">Register New Company</h2>
                <button onclick="document.getElementById('addCompanyModal').classList.add('hidden')" class="text-gray-400 hover:text-rose-500 transition">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            @if($errors->any())
                <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 text-sm border border-red-100">
                    <div class="font-bold mb-1">Please fix the following errors:</div>
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('coordinator.companies.store') }}">
                @csrf
                <div class="space-y-4 mb-8">
                    <div>
                        <label class="block text-sm font-bold text-[#300050] mb-1">Company Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full border border-purple-100 rounded-xl px-4 py-2.5 bg-purple-50/30 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all" placeholder="e.g. TechNova Solutions">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#300050] mb-1">Industry</label>
                        <input type="text" name="industry" value="{{ old('industry') }}" class="w-full border border-purple-100 rounded-xl px-4 py-2.5 bg-purple-50/30 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all" placeholder="e.g. Information Technology">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#300050] mb-1">Location</label>
                        <input type="text" name="location" value="{{ old('location') }}" class="w-full border border-purple-100 rounded-xl px-4 py-2.5 bg-purple-50/30 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all" placeholder="e.g. IT Park, Cebu City">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-[#300050] mb-1">Contact Person</label>
                            <input type="text" name="contact_person" value="{{ old('contact_person') }}" class="w-full border border-purple-100 rounded-xl px-4 py-2.5 bg-purple-50/30 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all" placeholder="e.g. Mr. John Doe">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-[#300050] mb-1">Contact Number</label>
                            <input type="text" name="contact_number" value="{{ old('contact_number') }}" class="w-full border border-purple-100 rounded-xl px-4 py-2.5 bg-purple-50/30 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all" placeholder="e.g. 0912-345-6789">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#300050] mb-1">Allocation Slots</label>
                        <input type="number" name="allocation_slots" value="{{ old('allocation_slots', 5) }}" min="0" required class="w-full border border-purple-100 rounded-xl px-4 py-2.5 bg-purple-50/30 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all">
                    </div>
                </div>

                <hr class="my-4 border-gray-100">
                <h3 class="text-sm font-bold text-[#300050] mb-4">🔑 Initial Advisor Account (Optional)</h3>
                
                <div class="space-y-4 mb-8">
                    <div>
                        <label class="block text-sm font-bold text-[#300050] mb-1">Advisor Email Address</label>
                        <input type="email" name="advisor_email" value="{{ old('advisor_email') }}" placeholder="e.g. advisor@company.com" class="w-full border border-purple-100 rounded-xl px-4 py-2.5 bg-purple-50/30 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#300050] mb-1">Initial Password</label>
                        <div class="flex gap-2">
                            <input type="text" name="advisor_password" id="advisor_password" placeholder="Set initial temporary password (min 8 chars)" class="flex-1 border border-purple-100 rounded-xl px-4 py-2.5 bg-purple-50/30 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all">
                            <button type="button" onclick="generateCompanyAdvisorPassword()" class="px-3 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-xl font-bold text-xs transition-colors">
                                Generate
                            </button>
                        </div>
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

    <!-- EDIT COMPANY MODAL -->
    <div id="editCompanyModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl border border-purple-100 p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold font-headline text-[#300050]">Edit Company</h2>
                <button onclick="document.getElementById('editCompanyModal').classList.add('hidden')" class="text-gray-400 hover:text-rose-500 transition">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <form id="editCompanyForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="space-y-4 mb-8">
                    <div>
                        <label class="block text-sm font-bold text-[#300050] mb-1">Company Name</label>
                        <input type="text" name="name" id="edit_name" required class="w-full border border-purple-100 rounded-xl px-4 py-2.5 bg-purple-50/30 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#300050] mb-1">Industry</label>
                        <input type="text" name="industry" id="edit_industry" class="w-full border border-purple-100 rounded-xl px-4 py-2.5 bg-purple-50/30 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#300050] mb-1">Location</label>
                        <input type="text" name="location" id="edit_location" class="w-full border border-purple-100 rounded-xl px-4 py-2.5 bg-purple-50/30 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-[#300050] mb-1">Contact Person</label>
                            <input type="text" name="contact_person" id="edit_contact_person" class="w-full border border-purple-100 rounded-xl px-4 py-2.5 bg-purple-50/30 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-[#300050] mb-1">Contact Number</label>
                            <input type="text" name="contact_number" id="edit_contact_number" class="w-full border border-purple-100 rounded-xl px-4 py-2.5 bg-purple-50/30 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#300050] mb-1">Allocation Slots</label>
                        <input type="number" name="allocation_slots" id="edit_allocation_slots" min="0" required class="w-full border border-purple-100 rounded-xl px-4 py-2.5 bg-purple-50/30 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all">
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('editCompanyModal').classList.add('hidden')" class="flex-1 px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-bold hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 bg-purple-950 hover:bg-purple-900 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md transition">
                        Update Details
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(company) {
            document.getElementById('editCompanyForm').action = `/coordinator/companies/${company.id}`;
            document.getElementById('edit_name').value = company.name || '';
            document.getElementById('edit_industry').value = company.industry || '';
            document.getElementById('edit_location').value = company.location || '';
            document.getElementById('edit_contact_person').value = company.contact_person || '';
            document.getElementById('edit_contact_number').value = company.contact_number || '';
            document.getElementById('edit_allocation_slots').value = company.allocation_slots || 0;
            document.getElementById('editCompanyModal').classList.remove('hidden');
        }

        function generateCompanyAdvisorPassword() {
            const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+";
            let pass = "";
            for (let i = 0; i < 10; i++) {
                pass += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('advisor_password').value = pass;
        }
    </script>
</body>

</html>
