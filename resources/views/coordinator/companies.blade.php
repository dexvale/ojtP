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

    <!-- Sidebar overlay for mobile -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 backdrop-blur-xs z-40 hidden lg:hidden" onclick="closeSidebar()"></div>

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
                    <div class="relative flex items-center sm:pl-2 sm:border-l sm:border-[#cec3d0]/30" id="user-profile-menu">
                        <button type="button" id="user-menu-btn" class="flex items-center gap-3 cursor-pointer focus:outline-none" aria-expanded="false" aria-haspopup="true">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-bold font-headline text-primary">{{ auth()->user()->display_name }}</p>
                                <p class="text-[10px] uppercase tracking-wider text-secondary font-bold">{{ auth()->user()->display_role }}</p>
                            </div>
                            <img alt="User profile avatar"
                                class="w-9 h-9 rounded-full object-cover ring-2 ring-primary/10 hover:ring-primary transition-all"
                                src="{{ auth()->user()->avatar_url }}">
                        </button>
                        <!-- Dropdown Menu -->
                        <div id="user-menu-dropdown" class="hidden absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-1.5 z-50">
                            <div class="px-4 py-2 border-b border-slate-100 sm:hidden">
                                <p class="text-xs font-bold text-slate-800 truncate">{{ auth()->user()->display_name }}</p>
                                <p class="text-[10px] uppercase tracking-wider text-slate-500 font-semibold truncate">{{ auth()->user()->display_role }}</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-xs sm:text-sm text-red-600 hover:bg-red-50 flex items-center gap-2.5 font-semibold transition-colors cursor-pointer">
                                    <span class="material-symbols-outlined text-[18px]">logout</span>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="lg:ml-64 ml-0 pt-24 px-4 sm:px-6 lg:px-8 pb-12 min-h-screen border-none">
        
        <!-- Page Header & Global Actions -->
        <div class="mb-8">
            <h1 class="text-4xl font-extrabold font-headline text-slate-900 tracking-tight">Company Directory</h1>
            <p class="text-slate-500 font-medium mt-1 text-sm">Manage partner organizations and monitor intern placements.</p>
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

        @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 rounded-xl p-4 mb-6 flex items-center gap-3 text-rose-800 text-sm shadow-sm">
                <span class="material-symbols-outlined text-rose-600">error</span>
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Top Control Bar -->
        <div class="mb-6">
            <!-- Search Bar -->
            <div class="relative">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input type="text" id="companySearchInput" oninput="filterCompaniesTable()"
                    class="w-full bg-white border border-slate-200 rounded-xl py-3 pl-12 pr-4 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary shadow-sm transition-all"
                    placeholder="Search companies, locations, or contact persons...">
            </div>
        </div>

        <!-- Data Table (Card Container) -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="w-full overflow-x-auto min-w-full inline-block align-middle">
                <table class="w-full text-left border-collapse whitespace-nowrap" id="companiesTable">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200">
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Company / Organization</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Industry</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Location</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider hidden md:table-cell">Supervisor / Person in Charge</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider hidden lg:table-cell">Partner Tracks</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Current Interns</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100" id="companiesTableBody">
                        @forelse($companies as $company)
                        @php
                            $filled = $company->filled_slots ?? 0;
                            $initials = substr(implode('', array_map(fn($w) => strtoupper($w[0] ?? ''), explode(' ', trim($company->name)))), 0, 2);
                            if(empty($initials)) { $initials = 'CO'; }
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors group company-row"
                            data-name="{{ strtolower($company->name) }}"
                            data-industry="{{ strtolower($company->industry ?? '') }}"
                            data-location="{{ strtolower($company->location ?? '') }}"
                            data-contact="{{ strtolower(($company->users->pluck('name')->implode(' ') . ' ' . $company->users->pluck('department')->implode(' ') . ' ' . ($company->contact_person ?? ''))) }}">
                            
                            <!-- Company Name -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-purple-100 text-primary flex items-center justify-center font-bold text-sm shadow-sm uppercase flex-shrink-0">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900 group-hover:text-primary transition-colors">{{ $company->name }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Industry -->
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-slate-600 flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[16px] text-slate-400">business_center</span>
                                    {{ $company->industry ?? 'General Operations' }}
                                </span>
                            </td>

                            <!-- Location -->
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-slate-600 flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[16px] text-slate-400">location_on</span>
                                    {{ $company->location ?? 'Bohol' }}
                                </span>
                            </td>

                            <!-- Supervisor / Person in Charge -->
                            <td class="px-6 py-4 hidden md:table-cell">
                                @php
                                    $supervisors = $company->users;
                                @endphp
                                @if($supervisors->isNotEmpty())
                                    <ul class="space-y-1">
                                        @foreach($supervisors as $sup)
                                            <li class="flex items-center gap-1.5 text-sm font-bold text-slate-800">
                                                <span class="text-purple-600 font-bold">•</span>
                                                <span>{{ $sup->name ?? $sup->email }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @elseif($company->contact_person)
                                    <div class="flex items-center gap-1.5 text-sm font-semibold text-slate-800">
                                        <span class="text-purple-600 font-bold">•</span>
                                        <span>{{ $company->contact_person }}</span>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400 italic">No supervisor assigned</span>
                                @endif
                            </td>

                            <!-- Partner Tracks / Courses -->
                            <td class="px-6 py-4 hidden lg:table-cell">
                                <div class="flex flex-wrap gap-1.5 max-w-xs">
                                    @forelse($company->courses as $course)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold tracking-wide bg-purple-50 text-[#300050] border border-purple-100">
                                            {{ preg_replace('/^(BS in|Bachelor of Science in)\s*/i', '', $course->course_name) }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-slate-400 italic">All Programs</span>
                                    @endforelse
                                </div>
                            </td>

                            <!-- Active Interns Count -->
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold {{ $filled > 0 ? 'bg-purple-100 text-purple-900 border border-purple-200' : 'bg-slate-100 text-slate-600' }}">
                                    <span class="material-symbols-outlined text-sm text-purple-600">group</span>
                                    {{ $filled }} {{ Str::plural('Intern', $filled) }}
                                </span>
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
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="material-symbols-outlined text-4xl text-slate-300 mb-2">apartment</span>
                                    <p class="font-headline font-bold text-slate-700 text-base">No Companies Registered</p>
                                    <p class="text-xs text-slate-400 mt-0.5">No partner organizations currently registered.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50 flex items-center justify-between">
                <p class="text-xs text-slate-500 font-medium">Showing <span class="font-bold text-slate-700" id="companyVisibleCount">{{ $companies->count() }}</span> partner companies</p>
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

    <!-- ERROR / ALERT MODAL -->
    @if(session('error'))
    <div id="errorModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl shadow-2xl border border-rose-100 p-6 sm:p-7 w-full max-w-md mx-auto text-center animate-in zoom-in-95 duration-200">
            <div class="w-16 h-16 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-4 border border-rose-100 shadow-xs">
                <span class="material-symbols-outlined text-3xl">shield_locked</span>
            </div>
            
            <h3 class="text-xl font-bold font-headline text-slate-900 mb-2">Action Blocked</h3>
            <p class="text-sm text-slate-600 leading-relaxed mb-6">
                {{ session('error') }}
            </p>

            <button type="button" onclick="document.getElementById('errorModal').remove()"
                    class="w-full bg-[#300050] hover:bg-purple-950 text-white font-bold text-sm py-2.5 px-5 rounded-xl shadow-md transition-all active:scale-95">
                Understood
            </button>
        </div>
    </div>
    @endif

    <script>
        function openEditModal(company) {
            document.getElementById('editCompanyForm').action = `/coordinator/companies/${company.id}`;
            document.getElementById('edit_name').value = company.name || '';
            document.getElementById('edit_industry').value = company.industry || '';
            document.getElementById('edit_location').value = company.location || '';
            document.getElementById('editCompanyModal').classList.remove('hidden');
        }

        function filterCompaniesTable() {
            const query = document.getElementById('companySearchInput').value.toLowerCase().trim();
            const rows = document.querySelectorAll('.company-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const name = row.dataset.name || '';
                const industry = row.dataset.industry || '';
                const location = row.dataset.location || '';
                const contact = row.dataset.contact || '';

                const matchesQuery = !query || name.includes(query) || industry.includes(query) || location.includes(query) || contact.includes(query);

                if (matchesQuery) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Handle "No matching results" row
            let noMatchRow = document.getElementById('noMatchRow');
            if (visibleCount === 0 && rows.length > 0) {
                if (!noMatchRow) {
                    noMatchRow = document.createElement('tr');
                    noMatchRow.id = 'noMatchRow';
                    noMatchRow.innerHTML = `
                        <td colspan="7" class="py-12 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center">
                                <span class="material-symbols-outlined text-4xl text-slate-300 mb-2">search_off</span>
                                <p class="font-headline font-bold text-slate-700 text-base">No Matching Companies</p>
                                <p class="text-xs text-slate-400 mt-0.5">Try searching with a different name, industry, location, or supervisor.</p>
                            </div>
                        </td>
                    `;
                    document.getElementById('companiesTableBody').appendChild(noMatchRow);
                }
                noMatchRow.style.display = '';
            } else if (noMatchRow) {
                noMatchRow.style.display = 'none';
            }

            // Update footer count
            const countEl = document.getElementById('companyVisibleCount');
            if (countEl) {
                countEl.textContent = visibleCount;
            }
        }

        // Sidebar Toggle
        const sidebarEl = document.getElementById('sidebar');
        const overlayEl = document.getElementById('sidebar-overlay');
        const toggleBtnEl = document.getElementById('sidebar-toggle');

        function openSidebar() {
            sidebarEl?.classList.remove('-translate-x-full');
            overlayEl?.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
        function closeSidebar() {
            sidebarEl?.classList.add('-translate-x-full');
            overlayEl?.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
        toggleBtnEl?.addEventListener('click', () => {
            if (sidebarEl) {
                sidebarEl.classList.contains('-translate-x-full') ? openSidebar() : closeSidebar();
            }
        });

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
