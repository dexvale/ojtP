<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>OJT Portal | Student Directory</title>
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

    <!-- TopNavBar -->
    <header class="fixed top-0 left-64 right-0 z-40 bg-surface/90 backdrop-blur-sm border-b border-[#cec3d0]/15">
        <div class="flex justify-between items-center px-8 py-4 w-full">
            <div class="flex items-center gap-8">
                <span class="text-2xl font-headline font-semibold text-primary tracking-tight">OJT Management</span>
                <nav class="hidden md:flex items-center gap-6">
                    <a class="text-sm font-semibold text-on-surface/60 hover:text-primary transition-colors duration-200" href="#">Dashboard</a>
                    <a class="text-sm font-semibold text-primary border-b-2 border-primary pb-1" href="#">Student List</a>
                    <a class="text-sm font-semibold text-on-surface/60 hover:text-primary transition-colors duration-200" href="#">Company Directory</a>
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
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-8 gap-4">
            <div>
                <h1 class="text-4xl font-extrabold font-headline text-slate-900 tracking-tight">Student Directory</h1>
                <p class="text-slate-500 font-medium mt-1 text-sm">Manage and track all OJT intern placements and progress.</p>
            </div>
            <div class="flex gap-3">
                <button
                    class="flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:shadow-lg hover:-translate-y-0.5 transition-all active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    New Student
                </button>
            </div>
        </div>

        <!-- Top Control Bar -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <!-- Search Bar -->
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input type="text" 
                    class="w-full bg-white border border-slate-200 rounded-xl py-3 pl-12 pr-4 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary shadow-sm transition-all"
                    placeholder="Search by Student Name or ID...">
            </div>
            
            <!-- Filters -->
            <div class="flex gap-4">
                <div class="relative">
                    <select class="appearance-none bg-white border border-slate-200 rounded-xl py-3 pl-4 pr-10 text-sm font-medium text-slate-700 min-w-[140px] focus:ring-2 focus:ring-primary/20 focus:border-primary shadow-sm cursor-pointer transition-all">
                        <option value="all">All Courses</option>
                        <option value="bscs">BSCS</option>
                        <option value="bsit">BSIT</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                </div>

                <div class="relative">
                    <select class="appearance-none bg-white border border-slate-200 rounded-xl py-3 pl-4 pr-10 text-sm font-medium text-slate-700 min-w-[140px] focus:ring-2 focus:ring-primary/20 focus:border-primary shadow-sm cursor-pointer transition-all">
                        <option value="all">All Years</option>
                        <option value="3rd">3rd Year</option>
                        <option value="4th">4th Year</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                </div>
            </div>
        </div>

        <!-- Data Table (Card Container) -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200">
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Student Name</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Course & Year</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Placement Status</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">OJT Progress</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($students as $student)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold shadow-sm uppercase">
                                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900 group-hover:text-primary transition-colors">{{ $student->first_name }} {{ $student->last_name }}</p>
                                        <p class="text-xs text-slate-500">{{ $student->student_id_number }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-slate-900">{{ $student->course }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if($student->company)
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-green-50 border border-green-100 text-green-700 text-xs font-semibold">
                                        <span class="material-symbols-outlined text-sm">corporate_fare</span>
                                        <span class="truncate max-w-[150px]">{{ $student->company->name }}</span>
                                    </div>
                                @else
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 border border-amber-100 text-amber-700 text-xs font-semibold">
                                        <span class="material-symbols-outlined text-sm">pending</span>
                                        <span>Unassigned</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $approvedHours = floatval($student->approved_hours ?? 0);
                                    $requiredHours = $student->academicCourse->required_hours ?? 400;
                                    $progressPercent = min(($approvedHours / max($requiredHours, 1)) * 100, 100);

                                    // Dynamic Color State Selector Logic
                                    if ($progressPercent <= 25) {
                                        $barColor = 'bg-amber-500';
                                        $textColor = 'text-amber-700';
                                    } elseif ($progressPercent < 90) {
                                        $barColor = 'bg-purple-700';
                                        $textColor = 'text-purple-700';
                                    } else {
                                        $barColor = 'bg-emerald-600';
                                        $textColor = 'text-emerald-700';
                                    }
                                @endphp
                                
                                <div class="w-full max-w-[170px] flex flex-col gap-1.5">
                                    <!-- Text Labels Top Layer: Aligned and De-cluttered -->
                                    <div class="flex justify-between items-center text-xs font-medium">
                                        <span class="font-mono text-gray-600">{{ number_format($approvedHours, 2) }} <span class="text-[10px] text-gray-400">/ {{ $requiredHours }} hrs</span></span>
                                        <span class="{{ $textColor }} font-bold font-mono">{{ round($progressPercent) }}%</span>
                                    </div>
                                    
                                    <!-- Track Rail Background Container -->
                                    <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden p-[1px] border border-gray-100">
                                        <!-- Dynamic Filled State Strip Indicator -->
                                        <div class="{{ $barColor }} h-full rounded-full transition-all duration-700 ease-out shadow-sm" 
                                             style="width: {{ $progressPercent }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if(!$student->company_id)
                                        <button onclick="openAssignModal({{ $student->id }}, '{{ addslashes($student->first_name . ' ' . $student->last_name) }}')" class="border border-purple-200 hover:bg-purple-50 text-purple-700 font-semibold text-sm rounded-lg px-3 py-2 transition-colors duration-150 flex items-center gap-1.5">
                                            <span class="material-symbols-outlined text-sm">business_center</span>
                                            <span>Assign Placement</span>
                                        </button>
                                    @else
                                        <button onclick="openAssignModal({{ $student->id }}, '{{ addslashes($student->first_name . ' ' . $student->last_name) }}')" class="text-gray-400 hover:text-purple-700 p-2 rounded-lg hover:bg-gray-50 transition-colors" title="Change Company Assignment">
                                            <span class="material-symbols-outlined text-sm">edit</span>
                                        </button>
                                    @endif

                                    <a href="{{ route('coordinator.students.show', $student->id) }}" class="border border-gray-200 text-gray-700 font-semibold text-sm rounded-lg px-3 py-2 hover:bg-gray-50 transition-colors duration-150 flex items-center gap-1">
                                        <span>View Profile</span>
                                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination Footer -->
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50 flex items-center justify-between">
                <p class="text-xs text-slate-500 font-medium">Showing <span class="font-bold text-slate-700">1</span> to <span class="font-bold text-slate-700">4</span> of <span class="font-bold text-slate-700">248</span> interns</p>
                <div class="flex gap-2">
                    <button class="px-3 py-1.5 border border-slate-200 rounded-lg text-xs font-semibold text-slate-400 bg-white cursor-not-allowed">
                        Previous
                    </button>
                    <button class="px-3 py-1.5 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:text-primary transition-colors focus:ring-2 focus:ring-primary/20">
                        Next
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- ASSIGN PLACEMENT MODAL -->
    <div id="assign-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl border border-purple-100 p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold font-headline text-[#300050]">Assign Placement</h2>
                <button onclick="closeAssignModal()" class="text-gray-400 hover:text-rose-500 transition">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <p class="text-sm text-gray-600 mb-6">Assigning company to: <span id="assign-student-name" class="font-bold text-purple-900"></span></p>

            <form id="assign-form" method="POST" action="">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-bold text-[#300050] mb-2">Select Company</label>
                    <select name="company_id" required class="w-full border border-purple-100 rounded-xl px-4 py-3 bg-purple-50/30 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all">
                        <option value="">-- Select Company --</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-3 mt-8">
                    <button type="button" onclick="closeAssignModal()" class="flex-1 px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-bold hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 bg-purple-950 hover:bg-purple-900 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md transition">
                        Save Assignment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAssignModal(studentId, studentName) {
            document.getElementById('assign-student-name').innerText = studentName;
            document.getElementById('assign-form').action = '/coordinator/students/' + studentId + '/assign';
            document.getElementById('assign-modal').classList.remove('hidden');
        }

        function closeAssignModal() {
            document.getElementById('assign-modal').classList.add('hidden');
        }
    </script>
</body>

</html>
