<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $company->name }} - Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans p-8 antialiased">
    
    <!-- Top Navigation Area -->
    <div class="max-w-7xl mx-auto mb-8 flex justify-between items-center">
        <div>
            <a href="{{ route('coordinator.companies') }}" class="inline-flex items-center gap-2 text-purple-700 hover:text-purple-900 font-semibold mb-2 transition-colors">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                Back to Directory
            </a>
            <h1 class="text-4xl font-extrabold text-[#300050] tracking-tight flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-900 flex items-center justify-center font-bold text-lg flex-shrink-0 shadow-inner">
                    {{ substr(implode('', array_map(fn($w) => strtoupper($w[0] ?? ''), explode(' ', trim($company->name)))), 0, 2) }}
                </div>
                {{ $company->name }}
            </h1>
        </div>
        <div class="text-right bg-white px-6 py-3 rounded-2xl shadow-sm border border-slate-200">
            <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Allocation Status</span>
            <span class="text-lg font-extrabold text-[#300050]">{{ $company->studentProfiles->count() }} <span class="text-slate-400 font-medium">/ {{ $company->allocation_slots }}</span></span>
        </div>
    </div>

    <div class="max-w-7xl mx-auto mb-6">
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
    </div>

    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- LEFT COLUMN: Company Profile & Credentials -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- Company Profile Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-purple-600"></div>
                <div class="flex justify-between items-center mb-5">
                    <h2 class="text-lg font-bold text-[#300050] flex items-center gap-2">
                        <span class="material-symbols-outlined text-purple-500 text-[20px]">business</span>
                        Company Profile
                    </h2>
                    <button onclick="openEditModal({{ $company }})" class="text-xs font-bold text-purple-700 hover:text-purple-950 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">edit</span>
                        Edit
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-bold tracking-wider text-slate-400 uppercase mb-1">Industry</p>
                        <p class="text-sm font-semibold text-slate-800 bg-slate-50 px-3 py-2 rounded-lg border border-slate-100">{{ $company->industry ?: 'Not specified' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold tracking-wider text-slate-400 uppercase mb-1">Location</p>
                        <p class="text-sm font-semibold text-slate-800 bg-slate-50 px-3 py-2 rounded-lg border border-slate-100 flex items-start gap-1">
                            <span class="material-symbols-outlined text-[16px] text-slate-400 mt-0.5">location_on</span>
                            {{ $company->location ?: 'Not specified' }}
                        </p>
                    </div>
                    <div class="pt-2 border-t border-slate-100">
                        <p class="text-xs font-bold tracking-wider text-slate-400 uppercase mb-1">Contact Person</p>
                        <p class="text-sm font-semibold text-slate-800">{{ $company->contact_person ?: 'Not specified' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold tracking-wider text-slate-400 uppercase mb-1">Contact Number</p>
                        <p class="text-sm font-semibold text-slate-800">{{ $company->contact_number ?: 'Not specified' }}</p>
                    </div>
                </div>
            </div>

            <!-- Connected Accounts Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-purple-400"></div>
                <div class="flex justify-between items-center mb-5">
                    <h2 class="text-lg font-bold text-[#300050] flex items-center gap-2">
                        <span class="material-symbols-outlined text-purple-500 text-[20px]">key</span>
                        Connected Accounts
                    </h2>
                </div>

                <div class="space-y-3">
                    @forelse($company->users as $advisor)
                        <div class="p-4 rounded-xl border border-purple-100 bg-purple-50/30">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-bold text-slate-800">{{ $advisor->email }}</span>
                                <span class="px-2.5 py-1 bg-purple-100 text-purple-700 text-[10px] font-extrabold uppercase tracking-wider rounded-full">{{ $advisor->role }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-slate-500 bg-white px-3 py-2 rounded-lg border border-purple-50">
                                <span class="material-symbols-outlined text-[14px]">lock</span>
                                Password: •••••••• <span class="italic text-slate-400">(Encrypted)</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 rounded-xl border border-amber-200 bg-amber-50 text-amber-700 flex flex-col gap-3 items-start">
                            <div class="flex gap-3">
                                <span class="material-symbols-outlined text-amber-500 text-[20px]">warning</span>
                                <p class="text-sm font-medium">No advisor account linked yet. Create one to grant them access.</p>
                            </div>
                            <button onclick="document.getElementById('quickSupervisorModal').classList.remove('hidden')" class="ml-8 px-4 py-2 bg-purple-100 text-purple-700 hover:bg-purple-200 transition-colors rounded-lg text-xs font-bold shadow-sm">
                                + Provision Advisor Account
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN: Assigned Interns -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 h-full overflow-hidden flex flex-col">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h2 class="text-lg font-bold text-[#300050] flex items-center gap-2">
                        <span class="material-symbols-outlined text-purple-500 text-[20px]">groups</span>
                        Assigned Interns Roster
                    </h2>
                </div>
                
                <div class="flex-1 p-6">
                    @if($company->studentProfiles->count() > 0)
                        <div class="overflow-x-auto rounded-xl border border-slate-200">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-200">
                                        <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Intern Name</th>
                                        <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Course</th>
                                        <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Hours Rendered</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($company->studentProfiles as $profile)
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="py-4 px-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-xs">
                                                        {{ substr($profile->first_name, 0, 1) }}{{ substr($profile->last_name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-bold text-slate-900">{{ $profile->first_name }} {{ $profile->last_name }}</p>
                                                        <p class="text-xs text-slate-500">{{ $profile->user->email ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 px-4 text-sm font-medium text-slate-600">{{ $profile->course ?: 'Not set' }}</td>
                                            <td class="py-4 px-4">
                                                <span class="px-2.5 py-1 bg-green-50 text-green-700 text-xs font-bold rounded-md border border-green-100">
                                                    {{ $profile->user->ojtLogs()->where('status', 'Approved')->sum('hours_rendered') ?? 0 }} / {{ $profile->required_hours ?? 400 }} hrs
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center h-full text-center py-12 px-4">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                <span class="material-symbols-outlined text-3xl text-slate-300">engineering</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-700 mb-1">No interns assigned</h3>
                            <p class="text-sm text-slate-500 max-w-sm mx-auto">There are no students currently deployed to this organization. You can assign interns from the Student Directory.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <!-- QUICK SUPERVISOR MODAL -->
    <div id="quickSupervisorModal" class="{{ $errors->any() ? '' : 'hidden' }} fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl border border-purple-100 p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold font-headline text-[#300050]">Provision Advisor</h2>
                <button onclick="document.getElementById('quickSupervisorModal').classList.add('hidden')" class="text-gray-400 hover:text-rose-500 transition">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <p class="text-sm text-gray-600 mb-6">Provisioning an account for: <span class="font-bold text-purple-900">{{ $company->name }}</span></p>

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

            <form method="POST" action="{{ route('coordinator.supervisors.store') }}">
                @csrf
                <input type="hidden" name="company_id" value="{{ $company->id }}">
                
                <div class="space-y-4 mb-8">
                    <div>
                        <label class="block text-sm font-bold text-[#300050] mb-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full border border-purple-100 rounded-xl px-4 py-2.5 bg-purple-50/30 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all" placeholder="e.g. advisor@company.com">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#300050] mb-1">Temporary Password</label>
                        <div class="flex gap-2">
                            <input type="text" name="password" id="supervisor_password" required class="flex-1 border border-purple-100 rounded-xl px-4 py-2.5 bg-purple-50/30 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all" placeholder="Min 8 characters">
                            <button type="button" onclick="generateSupervisorPassword()" class="px-3 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-xl font-bold text-xs transition-colors">
                                Generate
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('quickSupervisorModal').classList.add('hidden')" class="flex-1 px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-bold hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 bg-purple-950 hover:bg-purple-900 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md transition">
                        Create Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT COMPANY MODAL -->
    <div id="editCompanyModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl border border-purple-100 p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold font-headline text-[#300050]">Edit Company Details</h2>
                <button onclick="document.getElementById('editCompanyModal').classList.add('hidden')" class="text-gray-400 hover:text-rose-500 transition">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form method="POST" id="editCompanyForm" action="{{ route('coordinator.companies.update', $company->id) }}">
                @csrf
                @method('PUT')
                
                <div class="space-y-4 mb-6">
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
        function generateSupervisorPassword() {
            const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+";
            let pass = "";
            for (let i = 0; i < 10; i++) {
                pass += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('supervisor_password').value = pass;
        }

        function openEditModal(company) {
            document.getElementById('edit_name').value = company.name || '';
            document.getElementById('edit_industry').value = company.industry || '';
            document.getElementById('edit_location').value = company.location || '';
            document.getElementById('edit_contact_person').value = company.contact_person || '';
            document.getElementById('edit_contact_number').value = company.contact_number || '';
            document.getElementById('edit_allocation_slots').value = company.allocation_slots || 0;
            document.getElementById('editCompanyModal').classList.remove('hidden');
        }
    </script>
</body>
</html>
