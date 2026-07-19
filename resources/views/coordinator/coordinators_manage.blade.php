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
    <header class="fixed top-0 left-64 right-0 z-40 bg-surface/90 backdrop-blur-sm border-b border-[#cec3d0]/15">
        <div class="flex justify-between items-center px-8 py-4 w-full">
            <div class="flex items-center gap-8">
                <span class="text-2xl font-headline font-semibold text-primary tracking-tight">OJT Management</span>
                <nav class="hidden md:flex items-center gap-6">
                    <a class="text-sm font-semibold text-on-surface/60 hover:text-primary transition-colors duration-200" href="{{ route('coordinator.dashboard') }}">Dashboard</a>
                    <a class="text-sm font-semibold text-on-surface/60 hover:text-primary transition-colors duration-200" href="{{ route('coordinator.students') }}">Student List</a>
                    <a class="text-sm font-semibold text-on-surface/60 hover:text-primary transition-colors duration-200" href="{{ route('coordinator.companies') }}">Company Directory</a>
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
                            <p class="text-sm font-bold font-headline text-primary">Dr. Elena Vance</p>
                            <p class="text-[10px] uppercase tracking-wider text-secondary font-bold">OJT Director</p>
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
        <div class="mb-8">
            <h1 class="text-4xl font-extrabold font-headline text-slate-900 tracking-tight">Manage Coordinators</h1>
            <p class="text-slate-500 font-medium mt-1 text-sm">Register department heads and assign them to specific academic courses.</p>
        </div>

        @if(session('flash_password'))
            <div id="credential-flash-banner" class="bg-purple-50 border border-purple-200 rounded-xl p-5 mb-6 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-purple-600"></div>
                <button onclick="document.getElementById('credential-flash-banner').remove()" class="absolute top-4 right-4 text-purple-400 hover:text-purple-600 transition-colors">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
                
                <h3 class="text-lg font-bold text-[#300050] mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-purple-600">key</span>
                    Coordinator Account Created
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
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
                    Copy Coordinator Credentials
                </button>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-xl mb-6 shadow-sm flex items-center gap-3">
                <span class="material-symbols-outlined text-emerald-600 text-[20px]">check_circle</span>
                <span class="text-sm font-medium">{{ session('success') }}</span>
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

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- LIST OF COORDINATORS (8 Columns) -->
            <div class="lg:col-span-8 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-xl font-bold font-headline text-slate-800 mb-5 flex items-center gap-2">
                        <span class="material-symbols-outlined text-purple-600">manage_accounts</span>
                        Active Coordinators
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse($coordinators as $coordinator)
                            <div class="bg-purple-50/20 border border-purple-100 rounded-xl p-5 hover:shadow-md transition-shadow relative overflow-hidden group flex flex-col justify-between min-h-[160px]">
                                <div class="absolute top-0 left-0 w-1.5 h-full bg-purple-600"></div>
                                
                                <div>
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-700 font-bold uppercase shadow-sm">
                                            {{ substr($coordinator->email, 0, 2) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-slate-800 truncate" title="{{ $coordinator->email }}">{{ $coordinator->email }}</p>
                                            <p class="text-[10px] text-purple-600 uppercase font-extrabold tracking-wider">Department Coordinator</p>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap gap-1.5 mb-4">
                                        @forelse($coordinator->managedCourses as $course)
                                            <span class="px-2 py-0.5 bg-white border border-purple-200 text-purple-700 text-[10px] font-bold rounded-lg shadow-sm">
                                                {{ $course->course_name }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-slate-400 italic">No courses assigned yet</span>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="border-t border-purple-100/50 pt-3 flex justify-end">
                                    <form action="{{ route('admin.coordinators.destroy', $coordinator->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this coordinator account? They will lose access to all students.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-800 flex items-center gap-1 transition-colors">
                                            <span class="material-symbols-outlined text-sm">delete</span>
                                            Remove Access
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 py-12 text-center border-2 border-dashed border-slate-200 rounded-xl">
                                <span class="material-symbols-outlined text-slate-300 text-5xl mb-3">group</span>
                                <p class="text-sm text-slate-500 font-medium">No custom coordinator accounts registered yet.</p>
                                <p class="text-xs text-slate-400 mt-1">Use the registration form on the right to provision accounts.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- ADD COORDINATOR FORM (4 Columns) -->
            <div class="lg:col-span-4">
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 sticky top-24">
                    <h2 class="text-xl font-bold font-headline text-slate-800 mb-5 flex items-center gap-2">
                        <span class="material-symbols-outlined text-purple-600">person_add</span>
                        Add Coordinator
                    </h2>

                    <form action="{{ route('admin.coordinators.store') }}" method="POST">
                        @csrf
                        
                        <!-- Email Input -->
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Email Address</label>
                            <div class="relative">
                                <input type="email" name="email" value="{{ old('email') }}" required
                                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 bg-slate-50/50 text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all"
                                       placeholder="e.g. coordinator@bisu.edu.ph">
                            </div>
                        </div>

                        <!-- Password Input -->
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Account Password</label>
                            <div class="flex gap-2">
                                <input type="text" name="password" id="coordinator_password" required
                                       class="flex-1 border border-slate-200 rounded-xl px-4 py-2.5 bg-slate-50/50 text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all"
                                       placeholder="Minimum 8 characters">
                                <button type="button" onclick="generateRandomPassword()"
                                        class="px-3 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-xl font-bold text-xs transition-colors"
                                        title="Generate secure password">
                                    Generate
                                </button>
                            </div>
                        </div>

                        <!-- Course Assignments (Checkboxes) -->
                        <div class="mb-6">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Managed Courses / Departments</label>
                            <div class="space-y-2 max-h-48 overflow-y-auto pr-1 border border-slate-100 p-3 rounded-xl bg-slate-50/30">
                                @forelse($courses as $course)
                                    <label class="flex items-center gap-3 p-2 hover:bg-purple-50/30 rounded-lg cursor-pointer transition-colors">
                                        <input type="checkbox" name="courses[]" value="{{ $course->id }}"
                                               class="rounded border-slate-300 text-purple-600 focus:ring-purple-500 w-4.5 h-4.5">
                                        <span class="text-xs font-medium text-slate-700">{{ $course->course_name }}</span>
                                    </label>
                                @empty
                                    <p class="text-xs text-amber-600 font-medium p-1">No courses found in database. Add courses in settings first.</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                                class="w-full bg-[#300050] text-white py-3 rounded-xl font-bold text-sm shadow-md hover:bg-purple-950 active:scale-95 transition-all">
                            Create Coordinator Account
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </main>

    <script>
        function generateRandomPassword() {
            const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+";
            let pass = "";
            for (let i = 0; i < 10; i++) {
                pass += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('coordinator_password').value = pass;
        }
    </script>
</body>

</html>
