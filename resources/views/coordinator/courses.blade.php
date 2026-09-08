<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>OJT Portal | Course Settings</title>
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
        }
    </style>
</head>

<body class="bg-surface text-on-surface" data-theme="portal">
    <!-- SideNavBar -->
    <!-- SideNavBar (Shared Component) -->
    @include('components.coordinator-sidebar')

    <!-- Sidebar overlay for mobile -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 backdrop-blur-xs z-40 hidden lg:hidden" onclick="closeSidebar()"></div>

    <!-- TopNavBar -->
    <header class="fixed top-0 lg:left-64 left-0 right-0 z-40 bg-surface/90 backdrop-blur-sm border-b border-[#cec3d0]/15">
        <div class="flex justify-between items-center px-4 md:px-8 py-3.5 md:py-4 w-full">
            <div class="flex items-center gap-3 md:gap-4">
                <button id="sidebar-toggle" class="lg:hidden p-2 min-w-[40px] min-h-[40px] flex items-center justify-center text-primary rounded-lg hover:bg-black/5 transition-colors" aria-label="Toggle menu">
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
    <main class="lg:ml-64 ml-0 pt-20 md:pt-24 px-4 sm:px-6 lg:px-8 pb-12 min-h-screen border-none">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-8 gap-4">
            <div>
                <h1 class="text-4xl font-extrabold font-headline text-slate-900 tracking-tight">Course Settings</h1>
                <p class="text-slate-500 font-medium mt-1 text-sm">Manage dynamic course mappings and their required OJT hours.</p>
            </div>
            <div class="flex gap-3">
                <button onclick="document.getElementById('addCourseModal').classList.remove('hidden')"
                    class="flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:shadow-lg hover:-translate-y-0.5 transition-all active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    New Course
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-100 flex items-start gap-3">
                <span class="material-symbols-outlined text-green-600 mt-0.5">check_circle</span>
                <div>
                    <h4 class="text-sm font-semibold text-green-800">{{ session('success') }}</h4>
                </div>
            </div>
        @endif
        
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 flex items-start gap-3">
                <span class="material-symbols-outlined text-red-600 mt-0.5">error</span>
                <div>
                    <h4 class="text-sm font-semibold text-red-800">Please correct the following errors:</h4>
                    <ul class="mt-1 list-disc list-inside text-xs text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Data Table (Card Container) -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200">
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Course Name</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Required OJT Hours</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($courses as $course)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-slate-900 group-hover:text-primary transition-colors">{{ $course->course_name }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-slate-900">{{ $course->required_hours }} hrs</p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="openEditModal({{ $course->id }}, '{{ addslashes($course->course_name) }}', {{ $course->required_hours }})" class="p-2 text-slate-400 hover:text-primary hover:bg-primary/10 rounded-lg transition-colors">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </button>
                                    <button type="button" onclick="openDeleteModal({{ $course->id }})" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if(count($courses) === 0)
            <div class="p-8 text-center text-slate-500 text-sm">
                No courses added yet. Click "New Course" to add one.
            </div>
            @endif
        </div>
    </main>

    <!-- Add Course Modal -->
    <div id="addCourseModal" class="fixed inset-0 z-50 hidden flex items-center justify-center transition-opacity duration-300">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="document.getElementById('addCourseModal').classList.add('hidden')"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl border border-purple-100 p-6 w-full max-w-md mx-4 transform transition-all duration-300 z-10">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                <h3 class="text-lg font-bold text-purple-950 font-headline">Add New Course</h3>
                <button type="button" onclick="document.getElementById('addCourseModal').classList.add('hidden')" class="text-gray-400 hover:text-purple-700 transition">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form action="{{ route('courses.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-purple-900 mb-1">Course Name</label>
                    <input type="text" name="course_name" required placeholder="e.g. BS in Information Technology" 
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-purple-900 mb-1">Required Hours</label>
                    <input type="number" name="required_hours" required placeholder="e.g. 486" min="0"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                </div>
                <div class="flex items-center justify-end gap-2 pt-2 mt-4 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('addCourseModal').classList.add('hidden')" 
                            class="px-4 py-2 text-sm font-semibold text-gray-500 hover:text-gray-700 transition">Cancel</button>
                    <button type="submit" 
                            class="bg-purple-950 hover:bg-purple-900 text-white px-5 py-2 rounded-xl text-sm font-bold shadow-md transition">Save Course</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Course Modal -->
    <div id="editCourseModal" class="fixed inset-0 z-50 hidden flex items-center justify-center transition-opacity duration-300">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="document.getElementById('editCourseModal').classList.add('hidden')"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl border border-purple-100 p-6 w-full max-w-md mx-4 transform transition-all duration-300 z-10">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                <h3 class="text-lg font-bold text-purple-950 font-headline">Edit Course</h3>
                <button type="button" onclick="document.getElementById('editCourseModal').classList.add('hidden')" class="text-gray-400 hover:text-purple-700 transition">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form id="editCourseForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-purple-900 mb-1">Course Name</label>
                    <input type="text" id="edit_course_name" name="course_name" required 
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-purple-900 mb-1">Required Hours</label>
                    <input type="number" id="edit_required_hours" name="required_hours" required min="0"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                </div>
                <div class="flex items-center justify-end gap-2 pt-2 mt-4 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('editCourseModal').classList.add('hidden')" 
                            class="px-4 py-2 text-sm font-semibold text-gray-500 hover:text-gray-700 transition">Cancel</button>
                    <button type="submit" 
                            class="bg-purple-950 hover:bg-purple-900 text-white px-5 py-2 rounded-xl text-sm font-bold shadow-md transition">Update Course</button>
                </div>
            </form>
        </div>
    </div>

    <!-- DELETE CONFIRMATION MODAL -->
    <div id="deleteCourseModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm transition-opacity duration-300">
        <div class="relative bg-white rounded-2xl shadow-2xl border border-rose-50 p-6 w-full max-w-sm mx-4 text-center transform transition-all duration-300 z-10">
            
            <!-- Warning Icon -->
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-rose-50 mb-4">
                <svg class="h-6 w-6 text-rose-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            <!-- Warning Text -->
            <h3 class="text-lg font-bold text-gray-900 mb-1 font-headline">Delete Course?</h3>
            <p class="text-sm text-gray-500 mb-6">Are you sure you want to delete this course? Any student registered under this track will lose their automated hours mapping alignment.</p>

            <!-- Dynamic Form Target -->
            <form id="deleteCourseForm" method="POST" class="flex items-center justify-center gap-2">
                @csrf
                @method('DELETE')
                
                <!-- Cancel Button -->
                <button type="button" onclick="closeDeleteModal()" 
                        class="w-full px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-500 hover:bg-gray-50 border border-gray-200 transition">
                    No, Keep It
                </button>
                
                <!-- Delete Confirmation Button -->
                <button type="submit" 
                        class="w-full bg-rose-600 hover:bg-rose-700 text-white px-4 py-2.5 rounded-xl text-sm font-bold shadow-sm transition">
                    Yes, Delete
                </button>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, name, hours) {
            document.getElementById('edit_course_name').value = name;
            document.getElementById('edit_required_hours').value = hours;
            // The route name will be evaluated in JS, so we replace the ID placeholder
            let formAction = '{{ route("courses.update", ":id") }}';
            formAction = formAction.replace(':id', id);
            document.getElementById('editCourseForm').action = formAction;
            document.getElementById('editCourseModal').classList.remove('hidden');
        }

        function openDeleteModal(courseId) {
            // Dynamically inject the correct database routing URL into the modal form action
            const form = document.getElementById('deleteCourseForm');
            let formAction = '{{ route("courses.destroy", ":id") }}';
            formAction = formAction.replace(':id', courseId);
            form.action = formAction;
            
            // Unhide the custom confirmation modal asset layer
            document.getElementById('deleteCourseModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            // Re-hide the modal layer cleanly
            document.getElementById('deleteCourseModal').classList.add('hidden');
        }

        // Sidebar Toggling Code
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
