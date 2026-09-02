<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>OJT Portal | Company Directory</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link
        href="https://fonts.googleapis.com/css2?family=Newsreader:opsz,wght@6..72,400;6..72,600;6..72,700;6..72,800&family=Public+Sans:wght@400;500;600;700&display=swap"
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
    <!-- SideNavBar -->
    @include('components.coordinator-sidebar')

    <!-- TopNavBar -->
    <header class="fixed top-0 lg:left-64 left-0 right-0 z-40 bg-surface/90 backdrop-blur-sm border-b border-[#cec3d0]/15">
        <div class="flex justify-between items-center px-4 md:px-8 py-4 w-full">
            <div class="flex items-center gap-4">
                <button id="sidebar-toggle" class="lg:hidden p-2.5 min-w-[44px] min-h-[44px] flex items-center justify-center text-primary rounded-lg hover:bg-black/5 transition-colors" aria-label="Toggle menu">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <span class="text-xl md:text-2xl font-headline font-semibold text-primary tracking-tight">OJT Management</span>
                <nav class="hidden md:flex items-center gap-6">
                    <a class="text-sm font-semibold text-on-surface/60 hover:text-primary transition-colors duration-200" href="{{ route('coordinator.dashboard') }}">Dashboard</a>
                    <a class="text-sm font-semibold text-on-surface/60 hover:text-primary transition-colors duration-200" href="{{ route('coordinator.students') }}">Student List</a>
                    <a class="text-sm font-semibold text-primary border-b-2 border-primary pb-1" href="{{ route('coordinator.companies') }}">Company Directory</a>
                    <a class="text-sm font-semibold text-on-surface/60 hover:text-primary transition-colors duration-200" href="{{ route('coordinator.reports') }}">Reports</a>
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
        
        <!-- Page Header & Global Actions -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-end justify-between mb-8 gap-4">
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
                <div class="absolute top-0 left-0 w-1.5 h-full bg-purple-600"></div>
                <button onclick="document.getElementById('credential-flash-banner').remove()" class="absolute top-4 right-4 text-purple-400 hover:text-purple-600 transition-colors">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
                
                <h3 class="text-base font-bold text-[#300050] mb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined text-purple-600">key</span>
                    Advisor Credentials Provisioned
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div class="bg-white p-3 rounded-lg border border-purple-100">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Workplace</span>
                        <span class="text-xs font-semibold text-slate-800">{{ session('flash_company') }}</span>
                    </div>
                    <div class="bg-white p-3 rounded-lg border border-purple-100">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Login Email</span>
                        <span class="text-xs font-semibold text-slate-800">{{ session('flash_email') }}</span>
                    </div>
                    <div class="bg-white p-3 rounded-lg border border-purple-100">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Temporary Password</span>
                        <span class="text-xs font-semibold text-slate-800 font-mono bg-purple-100/50 px-2 py-0.5 rounded text-purple-700">{{ session('flash_password') }}</span>
                    </div>
                </div>

                <button onclick="navigator.clipboard.writeText('Email: {{ session('flash_email') }}\nPassword: {{ session('flash_password') }}'); alert('Credentials copied to clipboard!');" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white text-purple-700 border border-purple-200 rounded-lg text-xs font-bold hover:bg-purple-100 transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">content_copy</span>
                    Copy Credentials
                </button>
            </div>
        @endif

        @if(session('success') && !session('flash_password'))
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mb-6 flex items-center gap-3 text-emerald-800 text-sm shadow-sm">
                <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Top Control Bar -->
        <div class="flex flex-col sm:flex-row gap-4 mb-6">
            <!-- Search Bar -->
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input type="text" id="companySearchInput" onkeyup="filterCompaniesTable()"
                    class="w-full bg-white border border-slate-200 rounded-xl py-3 pl-12 pr-4 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary shadow-sm transition-all"
                    placeholder="Search companies, locations, or contact persons...">
            </div>
            
            <!-- Filters -->
            <div class="flex gap-3 w-full sm:w-auto">
                <div class="relative flex-1 sm:flex-initial">
                    <select id="moaFilterSelect" onchange="filterCompaniesTable()" class="w-full sm:w-auto appearance-none bg-white border border-slate-200 rounded-xl py-3 pl-4 pr-10 text-sm font-medium text-slate-700 sm:min-w-[160px] focus:ring-2 focus:ring-primary/20 focus:border-primary shadow-sm cursor-pointer transition-all">
                        <option value="all">All MOA Statuses</option>
                        <option value="active">Active MOA</option>
                        <option value="pending">Expired / Pending</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                </div>

                <div class="relative flex-1 sm:flex-initial">
                    <select id="slotsFilterSelect" onchange="filterCompaniesTable()" class="w-full sm:w-auto appearance-none bg-white border border-slate-200 rounded-xl py-3 pl-4 pr-10 text-sm font-medium text-slate-700 sm:min-w-[160px] focus:ring-2 focus:ring-primary/20 focus:border-primary shadow-sm cursor-pointer transition-all">
                        <option value="all">All Slot Status</option>
                        <option value="available">Has Available Slots</option>
                        <option value="full">Full Capacity</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                </div>
            </div>
        </div>

        <!-- Data Table (Card Container) -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="w-full overflow-x-auto min-w-full inline-block align-middle">
                <table class="w-full text-left border-collapse whitespace-nowrap" id="companiesTable">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200">
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Company / Organization</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Location</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider hidden md:table-cell">Contact Person</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider hidden lg:table-cell">Partner Tracks</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Allocation Slots</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100" id="companiesTableBody">
                        @forelse($companies as $company)
                        @php
                            $filled = $company->filled_slots ?? 0;
                            $totalSlots = $company->allocation_slots ?? 0;
                            $slotPercent = $totalSlots > 0 ? min(100, round(($filled / $totalSlots) * 100)) : 0;
                            $initials = substr(implode('', array_map(fn($w) => strtoupper($w[0] ?? ''), explode(' ', trim($company->name)))), 0, 2);
                            if(empty($initials)) { $initials = 'CO'; }
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors group company-row"
                            data-name="{{ strtolower($company->name) }}"
                            data-location="{{ strtolower($company->location ?? '') }}"
                            data-contact="{{ strtolower($company->contact_person ?? '') }}"
                            data-moa="active"
                            data-available="{{ ($totalSlots - $filled) > 0 ? 'available' : 'full' }}">
                            
                            <!-- Company Name -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-purple-100 text-primary flex items-center justify-center font-bold text-sm shadow-sm uppercase flex-shrink-0">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <p class="text-sm font-bold text-slate-900 group-hover:text-primary transition-colors">{{ $company->name }}</p>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-green-50 text-green-700 border border-green-200">
                                                MOA: Active
                                            </span>
                                        </div>
                                        <p class="text-xs text-slate-500 font-medium">{{ $company->industry ?? 'General Operations' }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Location -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1.5 text-slate-700 text-sm">
                                    <span class="material-symbols-outlined text-sm text-slate-400">location_on</span>
                                    <span class="truncate max-w-[180px]">{{ $company->location ?? 'Not specified' }}</span>
                                </div>
                            </td>

                            <!-- Contact Person -->
                            <td class="px-6 py-4 hidden md:table-cell">
                                <div>
                                    <p class="text-sm font-medium text-slate-800">{{ $company->contact_person ?? 'Not Assigned' }}</p>
                                    <p class="text-xs text-slate-400 font-mono">{{ $company->contact_number ?? 'N/A' }}</p>
                                </div>
                            </td>

                            <!-- Partner Tracks / Courses -->
                            <td class="px-6 py-4 hidden lg:table-cell">
                                <div class="flex flex-wrap gap-1 max-w-[180px]">
                                    @forelse($company->courses as $course)
                                        <span class="px-2 py-0.5 rounded-md bg-purple-50 text-purple-700 text-[10px] font-bold uppercase border border-purple-100">
                                            {{ preg_replace('/^(BS in|Bachelor of Science in)\s*/i', '', $course->course_name) }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-slate-400 italic">All Programs</span>
                                    @endforelse
                                </div>
                            </td>

                            <!-- Slots Capacity -->
                            <td class="px-6 py-4">
                                <div class="w-full max-w-[150px] flex flex-col gap-1.5">
                                    <div class="flex justify-between items-center text-xs font-medium">
                                        <span class="font-mono text-slate-700 font-bold">{{ $filled }} <span class="text-[10px] text-slate-400 font-normal">/ {{ $totalSlots }} slots</span></span>
                                        <span class="text-xs font-bold font-mono {{ $slotPercent >= 100 ? 'text-rose-600' : 'text-emerald-700' }}">{{ $slotPercent }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden p-[1px] border border-slate-100">
                                        <div class="{{ $slotPercent >= 100 ? 'bg-rose-500' : 'bg-primary' }} h-full rounded-full transition-all duration-500" style="width: {{ $slotPercent }}%"></div>
                                    </div>
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Edit Button -->
                                    <button onclick="openEditModal({{ $company }})" class="p-2 text-slate-400 hover:text-purple-700 rounded-lg hover:bg-slate-50 transition-colors" title="Edit Company Details">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>

                                    <!-- Info / View Profile Button -->
                                    <a href="{{ route('coordinator.companies.show', $company->id) }}" class="border border-slate-200 text-slate-700 font-semibold text-xs rounded-lg px-3 py-1.5 hover:bg-slate-50 hover:text-primary transition-colors flex items-center gap-1">
                                        <span>Details</span>
                                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                                    </a>

                                    <!-- Delete Button -->
                                    <form action="{{ route('coordinator.companies.destroy', $company->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this company and its associated advisor accounts?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors" title="Delete Company">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr id="emptyRow">
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="material-symbols-outlined text-4xl text-slate-300 mb-2">apartment</span>
                                    <p class="font-headline font-bold text-slate-700 text-base">No Companies Registered</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Click "Register New Company" above to add partner organizations.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50 flex items-center justify-between">
                <p class="text-xs text-slate-500 font-medium">Showing <span class="font-bold text-slate-700">{{ $companies->count() }}</span> partner companies</p>
                <div class="flex gap-2">
                    <button class="px-3 py-1.5 border border-slate-200 rounded-lg text-xs font-semibold text-slate-400 bg-white cursor-not-allowed">
                        Previous
                    </button>
                    <button class="px-3 py-1.5 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:text-primary transition-colors">
                        Next
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- ADD COMPANY MODAL -->
    <div id="addCompanyModal" class="{{ $errors->any() ? '' : 'hidden' }} fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm transition-opacity duration-300 p-4">
        <div class="bg-white rounded-2xl shadow-2xl border border-purple-100 p-6 w-full max-w-md mx-auto max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold font-headline text-[#300050]">Register Partner Company</h2>
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
                <div class="space-y-4 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Company Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 bg-slate-50 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition" placeholder="e.g. Nova Soft Solutions">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Industry</label>
                        <input type="text" name="industry" value="{{ old('industry') }}" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 bg-slate-50 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition" placeholder="e.g. Software Development">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Office Location <span class="text-rose-500">*</span></label>
                        <input type="text" name="location" value="{{ old('location') }}" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 bg-slate-50 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition" placeholder="e.g. Tagbilaran City, Bohol">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Contact Person</label>
                            <input type="text" name="contact_person" value="{{ old('contact_person') }}" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 bg-slate-50 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition" placeholder="e.g. Alice Margate">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Contact Number</label>
                            <input type="text" name="contact_number" value="{{ old('contact_number') }}" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 bg-slate-50 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition" placeholder="e.g. 09123456789">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Allocation Slots <span class="text-rose-500">*</span></label>
                        <input type="number" name="allocation_slots" value="{{ old('allocation_slots', 5) }}" min="0" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 bg-slate-50 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Scope to Academic Course(s)</label>
                        <div class="space-y-2 bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                            @foreach($managedCourses as $course)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" name="courses[]" value="{{ $course->id }}" checked class="rounded border-slate-300 text-primary focus:ring-primary/20 transition-all">
                                    <span class="text-xs text-slate-700 font-medium group-hover:text-primary transition-colors">{{ $course->course_name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-2 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('addCompanyModal').classList.add('hidden')" class="flex-1 px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-bold hover:bg-slate-50 transition">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 bg-primary hover:opacity-90 text-white px-5 py-2.5 rounded-xl text-xs font-bold shadow-md transition">
                        Save Company
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT COMPANY MODAL -->
    <div id="editCompanyModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm transition-opacity duration-300 p-4">
        <div class="bg-white rounded-2xl shadow-2xl border border-purple-100 p-6 w-full max-w-md mx-auto max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold font-headline text-[#300050]">Edit Company</h2>
                <button onclick="document.getElementById('editCompanyModal').classList.add('hidden')" class="text-gray-400 hover:text-rose-500 transition">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <form id="editCompanyForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="space-y-4 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Company Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" id="edit_name" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 bg-slate-50 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Industry</label>
                        <input type="text" name="industry" id="edit_industry" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 bg-slate-50 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Location</label>
                        <input type="text" name="location" id="edit_location" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 bg-slate-50 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Contact Person</label>
                            <input type="text" name="contact_person" id="edit_contact_person" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 bg-slate-50 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Contact Number</label>
                            <input type="text" name="contact_number" id="edit_contact_number" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 bg-slate-50 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Allocation Slots <span class="text-rose-500">*</span></label>
                        <input type="number" name="allocation_slots" id="edit_allocation_slots" min="0" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 bg-slate-50 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition">
                    </div>
                </div>

                <div class="flex gap-3 pt-2 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('editCompanyModal').classList.add('hidden')" class="flex-1 px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-bold hover:bg-slate-50 transition">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 bg-primary hover:opacity-90 text-white px-5 py-2.5 rounded-xl text-xs font-bold shadow-md transition">
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

        function filterCompaniesTable() {
            const query = document.getElementById('companySearchInput').value.toLowerCase();
            const moa = document.getElementById('moaFilterSelect').value;
            const slots = document.getElementById('slotsFilterSelect').value;
            const rows = document.querySelectorAll('.company-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const name = row.dataset.name || '';
                const location = row.dataset.location || '';
                const contact = row.dataset.contact || '';
                const rowMoa = row.dataset.moa || 'active';
                const rowSlots = row.dataset.available || 'available';

                const matchesQuery = name.includes(query) || location.includes(query) || contact.includes(query);
                const matchesMoa = (moa === 'all') || (moa === rowMoa);
                const matchesSlots = (slots === 'all') || (slots === rowSlots);

                if (matchesQuery && matchesMoa && matchesSlots) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Sidebar Toggle
        const sidebarEl = document.getElementById('sidebar');
        const toggleBtnEl = document.getElementById('sidebar-toggle');
        toggleBtnEl?.addEventListener('click', () => {
            sidebarEl.classList.toggle('-translate-x-full');
        });
    </script>
</body>

</html>
