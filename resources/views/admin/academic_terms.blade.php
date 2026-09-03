<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Academic Terms Management | OJT Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link
        href="https://fonts.googleapis.com/css2?family=Newsreader:opsz,wght@6..72,400;6..72,600;6..72,700;6..72,800&family=Public+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">
    <style>
        body { font-family: 'Public Sans', sans-serif; }
        .font-headline { font-family: 'Newsreader', serif; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
    </style>
</head>

<body class="bg-[#faf8fa] font-body text-[#1f1a20] antialiased">
    <!-- SideNavBar -->
    @include('components.coordinator-sidebar')

    <!-- TopNavBar -->
    <header class="fixed top-0 w-full z-40 bg-[#fff7fd] flex justify-between items-center px-6 lg:px-8 py-4 border-b border-[#cec3d0]/20 backdrop-blur-sm lg:pl-64 pl-0">
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2.5">
                <span class="text-xl font-headline font-semibold text-[#300050]">Academic Terms & School Years</span>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Active Term: {{ $activeTerm ? $activeTerm->full_title : 'None Set' }}
            </span>
            <div class="w-8 h-8 rounded-full bg-[#300050] text-white flex items-center justify-center font-bold text-xs">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="lg:ml-64 ml-0 pt-24 px-6 lg:px-8 pb-16 min-h-screen">
        <div class="max-w-6xl mx-auto space-y-8">
            
            <!-- Page Heading & Actions -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold font-headline text-slate-900 tracking-tight">Academic Terms Setup</h1>
                    <p class="text-slate-500 text-sm mt-1">Configure school years, switch active semesters, and scope incoming student batches without deleting historical records.</p>
                </div>
                <button onclick="openTermModal()"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#300050] hover:bg-purple-950 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-purple-900/20 transition active:scale-95 flex-shrink-0">
                    <span class="material-symbols-outlined text-lg">add_circle</span>
                    New Academic Term
                </button>
            </div>

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-xl shadow-xs flex items-center gap-3">
                    <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-xl shadow-xs flex items-center gap-3">
                    <span class="material-symbols-outlined text-rose-600">error</span>
                    <span class="text-sm font-semibold">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Stats Overview Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="bg-white rounded-2xl p-6 border border-purple-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Current Active Term</p>
                        <h3 class="text-lg font-extrabold text-[#300050]">{{ $activeTerm ? $activeTerm->full_title : 'No Active Term' }}</h3>
                        <p class="text-[11px] text-emerald-600 font-semibold mt-1">● Live registration scope</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-[#300050] flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">event_available</span>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Total Terms Configured</p>
                        <h3 class="text-2xl font-extrabold text-slate-800">{{ $terms->count() }} Terms</h3>
                        <p class="text-[11px] text-slate-500 font-medium mt-1">Archived & active terms</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-slate-50 text-slate-600 flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">history_toggle_off</span>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Active Batch Interns</p>
                        <h3 class="text-2xl font-extrabold text-slate-800">{{ $activeTerm ? $activeTerm->student_profiles_count : 0 }} Students</h3>
                        <p class="text-[11px] text-slate-500 font-medium mt-1">Enrolled in current term</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-[#300050] flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">group</span>
                    </div>
                </div>
            </div>

            <!-- Terms Table Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h2 class="font-bold text-slate-800 text-sm uppercase tracking-wider">Configured School Years & Terms</h2>
                    <span class="text-xs text-slate-400 font-medium">Switching active term auto-adjusts registration and dashboard default view</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                <th class="px-6 py-4">Academic Year</th>
                                <th class="px-6 py-4">Semester</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Enrolled Interns</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($terms as $term)
                                <tr class="hover:bg-slate-50/70 transition-colors {{ $term->is_active ? 'bg-purple-50/30' : '' }}">
                                    <td class="px-6 py-4 font-extrabold text-slate-800 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-slate-400 text-base">calendar_today</span>
                                        A.Y. {{ $term->academic_year }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-600">
                                        {{ $term->semester }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($term->is_active)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-extrabold rounded-full shadow-xs">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                ACTIVE (CURRENT TERM)
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 bg-slate-100 text-slate-600 text-xs font-bold rounded-full">
                                                Inactive / Past Term
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-bold text-slate-700">
                                        {{ $term->student_profiles_count }} {{ Str::plural('Student', $term->student_profiles_count) }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            @if(!$term->is_active)
                                                <form action="{{ route('admin.academic_terms.activate', $term->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition shadow-xs flex items-center gap-1">
                                                        <span class="material-symbols-outlined text-sm">check</span>
                                                        Set Active
                                                    </button>
                                                </form>

                                                @if($term->student_profiles_count === 0)
                                                    <form action="{{ route('admin.academic_terms.destroy', $term->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this term?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 transition rounded-lg hover:bg-rose-50" title="Delete Term">
                                                            <span class="material-symbols-outlined text-base">delete</span>
                                                        </button>
                                                    </form>
                                                @endif
                                            @else
                                                <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200">
                                                    Active Scope
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                        No academic terms configured yet. Click "New Academic Term" to create the first term.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <!-- NEW TERM MODAL -->
    <div id="termModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-2xl border border-purple-100 p-6 w-full max-w-md mx-auto animate-in fade-in zoom-in-95 duration-200">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-lg font-bold font-headline text-[#300050] flex items-center gap-2">
                    <span class="material-symbols-outlined text-purple-600">calendar_month</span>
                    Add Academic Term
                </h3>
                <button onclick="closeTermModal()" class="text-slate-400 hover:text-slate-600 transition">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form action="{{ route('admin.academic_terms.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Academic Year</label>
                        <input type="text" name="academic_year" placeholder="e.g. 2026-2027" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition">
                        <p class="text-[10px] text-slate-400 mt-1">Standard format: YYYY-YYYY (e.g. 2026-2027)</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Semester</label>
                        <select name="semester" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition">
                            <option value="1st Semester">1st Semester</option>
                            <option value="2nd Semester">2nd Semester</option>
                            <option value="Midyear">Midyear / Summer</option>
                        </select>
                    </div>

                    <div class="pt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" class="w-4 h-4 text-[#300050] border-slate-300 rounded focus:ring-purple-500">
                            <span class="text-xs font-bold text-slate-700">Set as currently active term immediately</span>
                        </label>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeTermModal()"
                            class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-bold hover:bg-slate-50 transition">
                        Cancel
                    </button>
                    <button type="submit"
                            class="flex-1 bg-[#300050] hover:bg-purple-950 text-white px-4 py-2.5 rounded-xl text-sm font-bold shadow-md transition">
                        Save Term
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openTermModal() {
            document.getElementById('termModal').classList.remove('hidden');
        }
        function closeTermModal() {
            document.getElementById('termModal').classList.add('hidden');
        }
    </script>
</body>

</html>
