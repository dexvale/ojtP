<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>OJT Portal | Manage Coordinators</title>
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
    <!-- SideNavBar (Shared Component) -->
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
                    @if(auth()->user()->role === 'Admin')
                        <a class="text-sm font-semibold {{ request()->routeIs('admin.coordinators') ? 'text-primary border-b-2 border-primary pb-1' : 'text-on-surface/60 hover:text-primary transition-colors duration-200' }}" href="{{ route('admin.coordinators') }}">Manage Coordinators</a>
                        <a class="text-sm font-semibold {{ request()->routeIs('courses.*') ? 'text-primary border-b-2 border-primary pb-1' : 'text-on-surface/60 hover:text-primary transition-colors duration-200' }}" href="{{ route('courses.index') }}">Course Settings</a>
                    @else
                        <a class="text-sm font-semibold text-on-surface/60 hover:text-primary transition-colors duration-200" href="{{ route('coordinator.dashboard') }}">Dashboard</a>
                        <a class="text-sm font-semibold text-on-surface/60 hover:text-primary transition-colors duration-200" href="{{ route('coordinator.students') }}">Student List</a>
                        <a class="text-sm font-semibold text-on-surface/60 hover:text-primary transition-colors duration-200" href="{{ route('coordinator.companies') }}">Company Directory</a>
                        <a class="text-sm font-semibold text-on-surface/60 hover:text-primary transition-colors duration-200" href="{{ route('coordinator.reports') }}">Reports</a>
                    @endif
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
        
        <!-- Page Header & Actions -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-end justify-between mb-8 gap-4">
            <div>
                <h1 class="text-4xl font-extrabold font-headline text-slate-900 tracking-tight">Manage Coordinators</h1>
                <p class="text-slate-500 font-medium mt-1 text-sm">Register department heads and assign them to specific academic tracks.</p>
            </div>
            <div class="flex gap-3">
                <button
                    onclick="document.getElementById('addCoordinatorModal').classList.remove('hidden')"
                    class="flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:shadow-lg hover:-translate-y-0.5 transition-all active:scale-95 shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">person_add</span>
                    Add Coordinator
                </button>
            </div>
        </div>

        @if(session('flash_password'))
            <div id="credential-flash-banner" class="bg-purple-50 border border-purple-200 rounded-xl p-5 mb-6 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-purple-600"></div>
                <button onclick="document.getElementById('credential-flash-banner').remove()" class="absolute top-4 right-4 text-purple-400 hover:text-purple-600 transition-colors">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
                
                <h3 class="text-base font-bold text-[#300050] mb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined text-purple-600">key</span>
                    Coordinator Account Created
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
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
                    Copy Coordinator Credentials
                </button>
            </div>
        @endif

        @if(session('success') && !session('flash_password'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 shadow-sm flex items-center gap-3 text-sm">
                <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-xl mb-6 shadow-sm">
                <div class="flex items-center gap-3 mb-2">
                    <span class="material-symbols-outlined text-rose-600 text-[20px]">error</span>
                    <span class="text-sm font-bold">Please correct the following errors:</span>
                </div>
                <ul class="list-disc list-inside text-xs space-y-1 ml-6">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Top Search Bar -->
        <div class="flex flex-col sm:flex-row gap-4 mb-6">
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input type="text" id="coordinatorSearchInput" onkeyup="filterCoordinatorsTable()"
                    class="w-full bg-white border border-slate-200 rounded-xl py-3 pl-12 pr-4 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary shadow-sm transition-all"
                    placeholder="Search coordinators by name, email, or assigned course...">
            </div>
        </div>

        <!-- Coordinators Data Table -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="w-full overflow-x-auto min-w-full inline-block align-middle">
                <table class="w-full text-left border-collapse whitespace-nowrap" id="coordinatorsTable">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200">
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Coordinator</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Role / Title</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Managed Academic Programs</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Date Registered</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100" id="coordinatorsTableBody">
                        @forelse($coordinators as $coordinator)
                        @php
                            $initials = substr(implode('', array_map(fn($w) => strtoupper($w[0] ?? ''), explode(' ', trim($coordinator->display_name)))), 0, 2);
                            if(empty($initials)) { $initials = 'CO'; }
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors coordinator-row"
                            data-name="{{ strtolower($coordinator->display_name) }}"
                            data-email="{{ strtolower($coordinator->email) }}"
                            data-courses="{{ strtolower($coordinator->managedCourses->pluck('course_name')->implode(' ')) }}">
                            
                            <!-- Coordinator Name & Email -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-purple-100 text-primary flex items-center justify-center font-bold text-sm shadow-sm uppercase flex-shrink-0">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900">{{ $coordinator->display_name }}</p>
                                        <p class="text-xs text-slate-400 font-mono">{{ $coordinator->email }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Role Badge -->
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-100">
                                    Department Coordinator
                                </span>
                            </td>

                            <!-- Managed Courses -->
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1 max-w-[280px]">
                                    @forelse($coordinator->managedCourses as $course)
                                        <span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 text-xs font-medium border border-slate-200">
                                            {{ preg_replace('/^(BS in|Bachelor of Science in)\s*/i', '', $course->course_name) }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-slate-400 italic">No courses assigned</span>
                                    @endforelse
                                </div>
                            </td>

                            <!-- Date Appointed -->
                            <td class="px-6 py-4 text-xs text-slate-500">
                                {{ $coordinator->created_at ? $coordinator->created_at->format('M d, Y') : 'N/A' }}
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-right">
                                <button type="button" onclick="openDeleteCoordinatorModal({{ $coordinator->id }}, '{{ addslashes($coordinator->display_name) }}')" class="border border-rose-200 text-rose-600 font-semibold text-xs rounded-lg px-3 py-1.5 hover:bg-rose-50 transition-colors inline-flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                    <span>Remove Access</span>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="material-symbols-outlined text-4xl text-slate-300 mb-2">group</span>
                                    <p class="font-headline font-bold text-slate-700 text-base">No Custom Coordinators Registered</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Click "Add Coordinator" above to provision department coordinators.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50 flex items-center justify-between">
                <p class="text-xs text-slate-500 font-medium">Showing <span class="font-bold text-slate-700">{{ $coordinators->count() }}</span> department coordinator(s)</p>
            </div>
        </div>
    </main>

    <!-- ADD COORDINATOR MODAL -->
    <div id="addCoordinatorModal" class="{{ $errors->any() ? '' : 'hidden' }} fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm transition-opacity duration-300 p-4">
        <div class="bg-white rounded-2xl shadow-2xl border border-purple-100 p-6 w-full max-w-md mx-auto max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold font-headline text-[#300050]">Add Coordinator</h2>
                <button onclick="document.getElementById('addCoordinatorModal').classList.add('hidden')" class="text-gray-400 hover:text-rose-500 transition">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form action="{{ route('admin.coordinators.store') }}" method="POST">
                @csrf
                <div class="space-y-4 mb-6">
                    <!-- Full Name Input -->
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Coordinator Full Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 bg-slate-50 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                               placeholder="e.g. Dr. Elena Vance">
                    </div>

                    <!-- Email Input -->
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Email Address <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 bg-slate-50 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                               placeholder="e.g. coordinator@bisu.edu.ph">
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Account Password <span class="text-rose-500">*</span></label>
                        <div class="flex gap-2">
                            <input type="text" name="password" id="coordinator_password" required
                                   class="flex-1 border border-slate-200 rounded-xl px-4 py-2.5 bg-slate-50 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                                   placeholder="Minimum 8 characters">
                            <button type="button" onclick="generateRandomPassword()"
                                    class="px-3 bg-purple-50 hover:bg-purple-100 text-primary border border-purple-200 rounded-xl font-bold text-xs transition-colors"
                                    title="Generate secure password">
                                Generate
                            </button>
                        </div>
                    </div>

                    <!-- Course Assignments (Checkboxes) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Managed Courses / Academic Programs <span class="text-rose-500">*</span></label>
                        <div class="space-y-2 max-h-48 overflow-y-auto pr-1 border border-slate-200 p-3 rounded-xl bg-slate-50">
                            @forelse($courses as $course)
                                <label class="flex items-center gap-3 p-1.5 hover:bg-purple-50/50 rounded-lg cursor-pointer transition-colors">
                                    <input type="checkbox" name="courses[]" value="{{ $course->id }}"
                                           class="rounded border-slate-300 text-primary focus:ring-primary/20 w-4 h-4">
                                    <span class="text-xs font-medium text-slate-700">{{ $course->course_name }}</span>
                                </label>
                            @empty
                                <p class="text-xs text-amber-600 font-medium p-1">No courses found in database. Add courses in settings first.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-2 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('addCoordinatorModal').classList.add('hidden')" class="flex-1 px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-bold hover:bg-slate-50 transition">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 bg-primary hover:opacity-90 text-white px-5 py-2.5 rounded-xl text-xs font-bold shadow-md transition">
                        Create Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- DELETE CONFIRMATION MODAL -->
    <div id="deleteCoordinatorModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm transition-opacity duration-300 p-4">
        <div class="bg-white rounded-2xl shadow-2xl border border-rose-100 p-6 w-full max-w-sm mx-auto text-center">
            <!-- Warning Icon -->
            <div class="w-14 h-14 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-4 border border-rose-100">
                <span class="material-symbols-outlined text-3xl">warning</span>
            </div>

            <!-- Warning Text -->
            <h3 class="text-lg font-bold text-slate-900 mb-1 font-headline">Remove Coordinator Access?</h3>
            <p class="text-xs text-slate-500 mb-6">
                Are you sure you want to remove <strong id="delete_coordinator_name" class="text-slate-800"></strong>? They will immediately lose access to all managed students and reports.
            </p>

            <!-- Dynamic Form Target -->
            <form id="deleteCoordinatorForm" method="POST" class="flex items-center justify-center gap-3">
                @csrf
                @method('DELETE')
                
                <!-- Cancel Button -->
                <button type="button" onclick="closeDeleteCoordinatorModal()" 
                        class="w-full px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 border border-slate-200 transition">
                    No, Keep
                </button>
                
                <!-- Delete Confirmation Button -->
                <button type="submit" 
                        class="w-full bg-rose-600 hover:bg-rose-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-sm transition">
                    Yes, Remove
                </button>
            </form>
        </div>
    </div>

    <script>
        function openDeleteCoordinatorModal(coordinatorId, coordinatorName) {
            document.getElementById('delete_coordinator_name').innerText = coordinatorName;
            document.getElementById('deleteCoordinatorForm').action = '/coordinator/manage/' + coordinatorId;
            document.getElementById('deleteCoordinatorModal').classList.remove('hidden');
        }

        function closeDeleteCoordinatorModal() {
            document.getElementById('deleteCoordinatorModal').classList.add('hidden');
        }

        function generateRandomPassword() {
            const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+";
            let pass = "";
            for (let i = 0; i < 10; i++) {
                pass += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('coordinator_password').value = pass;
        }

        function filterCoordinatorsTable() {
            const query = document.getElementById('coordinatorSearchInput').value.toLowerCase();
            const rows = document.querySelectorAll('.coordinator-row');

            rows.forEach(row => {
                const name = row.dataset.name || '';
                const email = row.dataset.email || '';
                const courses = row.dataset.courses || '';

                if (name.includes(query) || email.includes(query) || courses.includes(query)) {
                    row.style.display = '';
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
