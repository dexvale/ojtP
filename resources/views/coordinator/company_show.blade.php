<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>OJT Portal | {{ $company->name }} - Details</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:opsz,wght@6..72,400;6..72,600;6..72,700;6..72,800&family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Public Sans', sans-serif; }
        .font-headline { font-family: 'Newsreader', serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
    </style>
</head>
<body class="bg-surface text-on-surface" data-theme="portal">
    @include('components.coordinator-sidebar')

    <!-- TopNavBar -->
    <header class="fixed top-0 lg:left-64 left-0 right-0 z-40 bg-surface/90 backdrop-blur-sm border-b border-[#cec3d0]/15">
        <div class="flex justify-between items-center px-4 md:px-8 py-4 w-full">
            <div class="flex items-center gap-4">
                <button id="sidebar-toggle" class="lg:hidden p-2.5 min-w-[44px] min-h-[44px] flex items-center justify-center text-primary rounded-lg hover:bg-black/5 transition-colors" aria-label="Toggle menu">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <span class="text-xl md:text-2xl font-headline font-semibold text-primary tracking-tight">OJT Management</span>
            </div>
            <div class="flex items-center gap-4 sm:gap-6">
                <a href="{{ route('coordinator.companies') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 hover:text-primary hover:border-primary/30 transition-all shadow-sm active:scale-95">
                    <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                    Back to Directory
                </a>
                <div class="flex items-center gap-3 pl-3 border-l border-[#cec3d0]/30">
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
    </header>

    <main class="lg:ml-64 ml-0 pt-24 px-4 sm:px-6 lg:px-8 pb-12 min-h-screen">
        
        <!-- Credential Flash Banner -->
        @if(session('flash_password'))
            <div id="credential-flash-banner" class="bg-purple-50 border border-purple-200 rounded-2xl p-6 mb-6 shadow-sm relative overflow-hidden">
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
                    Copy Connection Details
                </button>
            </div>
        @endif

        @if(session('success') && !session('flash_password'))
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mb-6 flex items-center gap-3 text-emerald-800 text-sm shadow-sm">
                <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <!-- 1. Company Profile Header Banner -->
        @php
            $initials = substr(implode('', array_map(fn($w) => strtoupper($w[0] ?? ''), explode(' ', trim($company->name)))), 0, 2);
            if(empty($initials)) { $initials = 'CO'; }
            $filledCount = $company->studentProfiles->count();
            $totalSlots = $company->allocation_slots ?? 0;
            $slotPercent = $totalSlots > 0 ? min(100, round(($filledCount / $totalSlots) * 100)) : 0;
        @endphp
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
            
            <div class="flex items-center gap-5 z-10">
                <div class="w-20 h-20 rounded-2xl bg-purple-100 text-primary flex items-center justify-center text-2xl font-bold shadow-sm uppercase border-2 border-white ring-4 ring-primary/5 flex-shrink-0">
                    {{ $initials }}
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        <h1 class="text-3xl font-extrabold font-headline text-slate-900">{{ $company->name }}</h1>
                    </div>
                    <p class="text-slate-500 font-medium text-sm mt-1 flex flex-wrap items-center gap-3">
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px] text-slate-400">corporate_fare</span> {{ $company->industry ?? 'General Industry' }}</span>
                        <span class="text-slate-300">•</span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px] text-slate-400">location_on</span> {{ $company->location ?? 'Not specified' }}</span>
                    </p>
                </div>
            </div>
            
            <div class="flex flex-wrap gap-3 z-10">
                <button onclick="openEditModal({{ $company }})" class="px-4 py-2 border border-slate-200 text-slate-700 font-semibold text-sm rounded-xl hover:bg-slate-50 transition-colors flex items-center gap-2 shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">edit</span> Edit Details
                </button>
                <button onclick="document.getElementById('addSupervisorModal').classList.remove('hidden')" class="px-4 py-2 bg-primary text-white font-semibold text-sm rounded-xl hover:opacity-90 transition-all flex items-center gap-2 shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">person_add</span> Provision Advisor
                </button>
            </div>
        </div>

        <!-- 2. Summary Metric Matrix Blocks -->
        <!-- 2. Quick Metrics Bento -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Active Interns Deployed -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex items-center gap-5 relative overflow-hidden">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-3xl">groups</span>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Deployed Interns</p>
                    <h3 class="text-3xl font-black text-slate-800 font-mono">{{ $company->studentProfiles->count() }}</h3>
                    <p class="text-xs text-slate-500 mt-1">Actively placed students</p>
                </div>
            </div>

            <!-- Supervisors Registered -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex items-center gap-5 relative overflow-hidden">
                <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-3xl">badge</span>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Company Advisors</p>
                    <h3 class="text-3xl font-black text-slate-800 font-mono">{{ $company->users->count() }}</h3>
                    <p class="text-xs text-slate-500 mt-1">Authorized evaluators</p>
                </div>
            </div>

            <!-- Partner Courses -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex items-center gap-5 relative overflow-hidden">
                <div class="w-14 h-14 rounded-2xl bg-purple-50 text-primary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-3xl">school</span>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Partner Programs</p>
                    <h3 class="text-3xl font-black text-slate-800 font-mono">{{ $company->courses->count() }}</h3>
                    <p class="text-xs text-slate-500 mt-1">Affiliated degree tracks</p>
                </div>
            </div>
        </div>

        <!-- 3. Deployed Interns Table Component -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
            <div class="border-b border-slate-200 bg-slate-50/50 px-6 py-4 flex justify-between items-center">
                <h2 class="text-lg font-bold font-headline text-slate-800 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">groups</span>
                    Assigned Student Interns ({{ $company->studentProfiles->count() }})
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-white border-b border-slate-100 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-4">Student Name</th>
                            <th class="px-6 py-4">Program / Track</th>
                            <th class="px-6 py-4">Assigned Department</th>
                            <th class="px-6 py-4">Immediate Supervisor</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($company->studentProfiles as $student)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-primary/10 text-primary font-bold text-xs flex items-center justify-center uppercase">
                                            {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900">{{ $student->first_name }} {{ $student->last_name }}</p>
                                            <p class="text-xs text-slate-500">{{ $student->student_id_number }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-slate-800">{{ $student->course }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-800 text-xs font-semibold">
                                        {{ $student->department ?? 'General Operations' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-slate-800">{{ $student->supervisor->name ?? $student->supervisor->email ?? 'Not Assigned' }}</p>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('coordinator.students.show', $student->id) }}" class="border border-slate-200 text-slate-700 font-semibold text-xs rounded-lg px-3 py-1.5 hover:bg-slate-50 hover:text-primary transition-colors inline-flex items-center gap-1">
                                        <span>View Profile</span>
                                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                    <span class="material-symbols-outlined text-4xl text-slate-300 mb-2 block">person_off</span>
                                    <p class="text-sm font-medium text-slate-500">No students are currently deployed to this company.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 4. Company Advisors List Component -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="border-b border-slate-200 bg-slate-50/50 px-6 py-4 flex justify-between items-center">
                <h2 class="text-lg font-bold font-headline text-slate-800 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">supervisor_account</span>
                    Authorized Company Advisors ({{ $company->users->count() }})
                </h2>
                <button onclick="document.getElementById('addSupervisorModal').classList.remove('hidden')" class="text-xs font-bold text-primary hover:underline flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">add</span> Add Another Advisor
                </button>
            </div>
            <div class="p-6">
                @if($company->users->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($company->users as $advisor)
                            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 flex flex-col justify-between">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-purple-100 text-primary flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-[20px]">badge</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-sm text-slate-900 truncate">{{ $advisor->name ?? 'Advisor' }}</p>
                                        <div class="mt-2 space-y-1.5 text-xs">
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-semibold text-slate-400">Email:</span>
                                                <span class="font-mono text-slate-700 truncate select-all">{{ $advisor->email }}</span>
                                            </div>
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-semibold text-slate-400">Department:</span>
                                                <span class="font-medium text-slate-800">{{ $advisor->department ?? 'General / Unassigned' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 pt-3 border-t border-slate-200/60 flex items-center text-xs text-slate-400">
                                    <span>Added {{ $advisor->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-8 text-center text-slate-400">
                        <p class="text-sm">No advisor accounts provisioned yet for this company.</p>
                    </div>
                @endif
            </div>
        </div>

    </main>

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

    <!-- PROVISION SUPERVISOR MODAL -->
    <div id="addSupervisorModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm transition-opacity duration-300 p-4">
        <div class="bg-white rounded-2xl shadow-2xl border border-purple-100 p-6 w-full max-w-md mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold font-headline text-[#300050]">Provision New Advisor</h2>
                <button onclick="document.getElementById('addSupervisorModal').classList.add('hidden')" class="text-gray-400 hover:text-rose-500 transition">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <form method="POST" action="{{ route('coordinator.supervisors.store') }}">
                @csrf
                <input type="hidden" name="company_id" value="{{ $company->id }}">
                <div class="space-y-4 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Advisor Full Name</label>
                        <input type="text" name="name" placeholder="e.g. Engr. Mark Santos" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 bg-slate-50 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Advisor Email <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" required placeholder="e.g. advisor@company.com" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 bg-slate-50 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Assigned Department / Branch</label>
                        <input type="text" name="department" placeholder="e.g. IT Infrastructure" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 bg-slate-50 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Initial Temporary Password <span class="text-rose-500">*</span></label>
                        <div class="flex gap-2">
                            <input type="text" name="password" id="advisor_password_gen" required placeholder="Min 8 characters" class="flex-1 border border-slate-200 rounded-xl px-4 py-2.5 bg-slate-50 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition">
                            <button type="button" onclick="generatePassword()" class="px-3 bg-purple-50 hover:bg-purple-100 text-primary border border-purple-200 rounded-xl font-bold text-xs transition-colors">
                                Generate
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-2 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('addSupervisorModal').classList.add('hidden')" class="flex-1 px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-bold hover:bg-slate-50 transition">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 bg-primary hover:opacity-90 text-white px-5 py-2.5 rounded-xl text-xs font-bold shadow-md transition">
                        Create Advisor
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
            document.getElementById('editCompanyModal').classList.remove('hidden');
        }

        function generatePassword() {
            const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
            let pass = "";
            for (let i = 0; i < 10; i++) {
                pass += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('advisor_password_gen').value = pass;
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
