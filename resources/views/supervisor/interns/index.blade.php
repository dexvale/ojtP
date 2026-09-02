<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>OJT Portal | My Interns</title>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:opsz,wght@6..72,400;6..72,600;6..72,700;6..72,800&family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Public Sans', sans-serif; }
        .font-headline { font-family: 'Newsreader', serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .material-icon-filled { font-family: 'Material Symbols Outlined'; font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24; }

        /* Hide Alpine-managed elements until Alpine.js initializes */
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-surface text-on-surface" data-theme="portal">

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 backdrop-blur-xs z-40 hidden lg:hidden transition-opacity"></div>

    <!-- SIDEBAR -->
    @include('components.supervisor-sidebar')

    <!-- TOP NAVIGATION -->
    <header class="fixed top-0 lg:left-64 left-0 right-0 z-30 bg-[#fff7fd]/95 backdrop-blur-sm border-b border-[#cec3d0]/15">
        <div class="flex justify-between items-center px-4 md:px-8 py-3.5 w-full">
            <div class="flex items-center gap-3 md:gap-4">
                <button id="sidebar-toggle" class="lg:hidden p-2 text-[#300050] rounded-lg hover:bg-black/5 transition-colors active:scale-95" aria-label="Toggle Menu">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <span class="text-xl md:text-2xl font-headline font-semibold text-[#300050] tracking-tight">Industry Supervisor</span>
            </div>
            <div class="flex items-center gap-3 sm:gap-6">
                <div class="flex items-center gap-3">
                    <button class="p-2 text-[#300050] hover:bg-[#faf1f8] rounded-full transition-all active:scale-95" title="Notifications">
                        <span class="material-symbols-outlined">notifications</span>
                    </button>
                    <div class="flex items-center gap-3 pl-2 border-l border-[#cec3d0]/30">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold font-headline text-[#300050]">{{ auth()->user()->display_name }}</p>
                            <p class="text-[10px] uppercase tracking-wider text-secondary font-bold">{{ auth()->user()->department ? auth()->user()->department . ' • ' : '' }}{{ auth()->user()->company->name ?? 'Supervisor' }}</p>
                        </div>
                        <img alt="User profile avatar"
                            class="w-8 h-8 sm:w-9 sm:h-9 rounded-full object-cover ring-2 ring-primary/10 flex-shrink-0"
                            src="{{ auth()->user()->avatar_url }}">
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="lg:ml-64 ml-0 pt-20 md:pt-24 px-4 sm:px-6 lg:px-8 pb-12 w-full max-w-7xl mx-auto" x-data="{ 
        showEvalModal: false, 
        evalStudentId: null, 
        evalStudentName: '',
        techScore: 0,
        softScore: 0,
        attScore: 0,
        existingEval: null
    }">
        <div>
            <!-- Flash Success Banner -->
            @if(session('success'))
                <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 rounded-xl px-4 sm:px-5 py-3.5 text-emerald-800 text-sm font-semibold shadow-sm">
                    <span class="material-symbols-outlined text-emerald-600 text-[20px]">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-6 md:mb-8 gap-4">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold font-headline text-primary tracking-tight">My Interns</h1>
                    <p class="text-on-surface/60 font-medium mt-1 text-xs sm:text-sm">Manage, monitor progress, and evaluate students assigned under your direct supervision.</p>
                </div>
                <div class="bg-primary/10 text-primary text-xs font-semibold px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-xl border border-primary/20 flex items-center gap-2 self-start sm:self-auto shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">groups</span>
                    <span>Total: {{ $interns->count() }} Assigned Intern(s)</span>
                </div>
            </div>

            <!-- Search Control Bar -->
            <div class="mb-6">
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                    <input type="text" id="internSearchInput" onkeyup="filterInternsTable()"
                        class="w-full bg-white border border-slate-200 rounded-xl py-2.5 sm:py-3 pl-11 sm:pl-12 pr-4 text-xs sm:text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary shadow-sm transition-all"
                        placeholder="Search interns by name, student ID, or course...">
                </div>
            </div>

            <!-- ================= TABLE VIEW (Tablets & Desktops) ================= -->
            <div class="hidden md:block bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="w-full overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap" id="internsTable">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200">
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Student Intern</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Academic Track</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">OJT Progress</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Evaluation</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100" id="internsTableBody">
                            @forelse($interns as $intern)
                            @php
                                $latestEval = $intern->evaluations->sortByDesc('evaluated_at')->first();
                                $studentName = $intern->user->display_name ?? trim($intern->first_name . ' ' . $intern->last_name);
                                $initials = substr(implode('', array_map(fn($w) => strtoupper($w[0] ?? ''), explode(' ', $studentName))), 0, 2);
                                if(empty($initials)) { $initials = 'IN'; }
                                
                                $approvedHours = floatval($intern->approved_hours ?? 0);
                                $requiredHours = $intern->academicCourse->required_hours ?? $intern->required_hours ?? 400;
                                $percent = min(($approvedHours / max($requiredHours, 1)) * 100, 100);
                                
                                $avgRating = $latestEval ? (($latestEval->technical_score + $latestEval->soft_skills_score + $latestEval->attitude_score) / 3) : null;
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors intern-row"
                                data-name="{{ strtolower($studentName) }}"
                                data-id="{{ strtolower($intern->student_id_number ?? '') }}"
                                data-course="{{ strtolower($intern->course ?? $intern->academicCourse->course_name ?? '') }}"
                                data-email="{{ strtolower($intern->user->email ?? '') }}">
                                
                                <!-- Intern Profile -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-primary flex items-center justify-center font-bold text-sm shadow-sm uppercase flex-shrink-0">
                                            {{ $initials }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-900">{{ $studentName }}</p>
                                            <div class="flex items-center gap-2 text-xs text-slate-400">
                                                <span class="font-mono">{{ $intern->student_id_number ?? 'ID N/A' }}</span>
                                                <span>•</span>
                                                <span class="font-mono text-[11px]">{{ $intern->user->email ?? '' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Academic Track -->
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $intern->course ?? $intern->academicCourse->course_name ?? 'Computing Student' }}
                                    </span>
                                </td>

                                <!-- OJT Progress -->
                                <td class="px-6 py-4">
                                    <div class="w-48">
                                        <div class="flex justify-between items-center text-xs mb-1.5">
                                            <span class="font-bold text-slate-800">{{ number_format($approvedHours, 1) }} / {{ $requiredHours }} hrs</span>
                                            <span class="text-slate-500 font-semibold text-[11px]">{{ number_format($percent, 1) }}%</span>
                                        </div>
                                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden border border-slate-200/50">
                                            <div class="bg-primary h-full rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Performance Rating -->
                                <td class="px-6 py-4">
                                    @if($latestEval)
                                        <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 border border-amber-200 rounded-lg text-amber-800 text-xs font-bold" title="Technical: {{ $latestEval->technical_score }}/5 | Soft Skills: {{ $latestEval->soft_skills_score }}/5 | Attitude: {{ $latestEval->attitude_score }}/5">
                                            <span class="material-symbols-outlined text-amber-500 text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                            <span>{{ number_format($avgRating, 1) }} / 5.0</span>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-500 border border-slate-200">
                                            Pending Evaluation
                                        </span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 text-right">
                                    <button
                                        @click="showEvalModal = true; evalStudentId = {{ $intern->id }}; evalStudentName = '{{ addslashes($studentName) }}'; techScore = {{ $latestEval?->technical_score ?? 0 }}; softScore = {{ $latestEval?->soft_skills_score ?? 0 }}; attScore = {{ $latestEval?->attitude_score ?? 0 }}"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-primary text-white text-xs font-bold rounded-lg hover:bg-primary/90 active:scale-95 transition-all shadow-sm">
                                        <span class="material-symbols-outlined text-[15px]" style="font-variation-settings: 'FILL' 1;">star_rate</span>
                                        <span>{{ $latestEval ? 'Re-Evaluate' : 'Evaluate' }}</span>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="material-symbols-outlined text-4xl text-slate-300 mb-2">groups</span>
                                        <p class="font-headline font-bold text-slate-700 text-base">No Assigned Interns Found</p>
                                        <p class="text-xs text-slate-400 mt-0.5">Students assigned under your supervisor account will appear here.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Table Footer -->
                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50 flex items-center justify-between">
                    <p class="text-xs text-slate-500 font-medium">Showing <span class="font-bold text-slate-700">{{ $interns->count() }}</span> assigned intern(s)</p>
                </div>
            </div>

            <!-- ================= MOBILE CARD VIEW (Phones < 768px) ================= -->
            <div class="block md:hidden space-y-4" id="internsMobileList">
                @forelse($interns as $intern)
                @php
                    $latestEval = $intern->evaluations->sortByDesc('evaluated_at')->first();
                    $studentName = $intern->user->display_name ?? trim($intern->first_name . ' ' . $intern->last_name);
                    $initials = substr(implode('', array_map(fn($w) => strtoupper($w[0] ?? ''), explode(' ', $studentName))), 0, 2);
                    if(empty($initials)) { $initials = 'IN'; }
                    
                    $approvedHours = floatval($intern->approved_hours ?? 0);
                    $requiredHours = $intern->academicCourse->required_hours ?? $intern->required_hours ?? 400;
                    $percent = min(($approvedHours / max($requiredHours, 1)) * 100, 100);
                    
                    $avgRating = $latestEval ? (($latestEval->technical_score + $latestEval->soft_skills_score + $latestEval->attitude_score) / 3) : null;
                @endphp
                <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm intern-mobile-card space-y-3.5"
                    data-name="{{ strtolower($studentName) }}"
                    data-id="{{ strtolower($intern->student_id_number ?? '') }}"
                    data-course="{{ strtolower($intern->course ?? $intern->academicCourse->course_name ?? '') }}"
                    data-email="{{ strtolower($intern->user->email ?? '') }}">
                    
                    <!-- Intern Header -->
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-purple-100 text-primary flex items-center justify-center font-bold text-sm shadow-sm uppercase flex-shrink-0">
                                {{ $initials }}
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-sm font-bold text-slate-900 truncate">{{ $studentName }}</h3>
                                <p class="text-[11px] font-mono text-slate-400 truncate">{{ $intern->student_id_number ?? 'ID N/A' }} • {{ $intern->user->email ?? '' }}</p>
                            </div>
                        </div>

                        <!-- Rating Badge on Mobile -->
                        @if($latestEval)
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-amber-50 border border-amber-200 rounded-lg text-amber-800 text-xs font-bold flex-shrink-0">
                                <span class="material-symbols-outlined text-amber-500 text-[14px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                {{ number_format($avgRating, 1) }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-500 border border-slate-200 flex-shrink-0">
                                Pending
                            </span>
                        @endif
                    </div>

                    <!-- Course Badge -->
                    <div>
                        <span class="inline-block px-2.5 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                            {{ $intern->course ?? $intern->academicCourse->course_name ?? 'Computing Student' }}
                        </span>
                    </div>

                    <!-- OJT Progress Bar -->
                    <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                        <div class="flex justify-between items-center text-xs mb-1.5">
                            <span class="font-bold text-slate-800">{{ number_format($approvedHours, 1) }} / {{ $requiredHours }} hrs</span>
                            <span class="text-slate-500 font-semibold text-[11px]">{{ number_format($percent, 1) }}%</span>
                        </div>
                        <div class="w-full bg-slate-200/60 h-2 rounded-full overflow-hidden">
                            <div class="bg-primary h-full rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <button
                        @click="showEvalModal = true; evalStudentId = {{ $intern->id }}; evalStudentName = '{{ addslashes($studentName) }}'; techScore = {{ $latestEval?->technical_score ?? 0 }}; softScore = {{ $latestEval?->soft_skills_score ?? 0 }}; attScore = {{ $latestEval?->attitude_score ?? 0 }}"
                        class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-primary text-white text-xs font-bold rounded-xl hover:bg-primary/90 active:scale-95 transition-all shadow-sm">
                        <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star_rate</span>
                        <span>{{ $latestEval ? 'Re-Evaluate Intern' : 'Submit Evaluation' }}</span>
                    </button>
                </div>
                @empty
                <div class="bg-white rounded-2xl border border-slate-200 p-8 text-center text-slate-400">
                    <span class="material-symbols-outlined text-4xl text-slate-300 mb-2">groups</span>
                    <p class="font-headline font-bold text-slate-700 text-base">No Assigned Interns</p>
                    <p class="text-xs text-slate-400 mt-1">Students assigned to you will appear here.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- ======= EVALUATE MODAL ======= -->
        <div
            x-show="showEvalModal"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-3 sm:p-4"
            @click.self="showEvalModal = false">

            <div
                x-show="showEvalModal"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden border border-purple-100 max-h-[92vh] flex flex-col">

                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-[#300050] to-[#560bad] px-5 sm:px-6 py-4 sm:py-5 flex items-center justify-between flex-shrink-0">
                    <div>
                        <h2 class="text-base sm:text-lg font-extrabold text-white font-headline">Intern Evaluation Rubric</h2>
                        <p class="text-white/70 text-xs mt-0.5 truncate max-w-[240px] sm:max-w-none" x-text="'Evaluating: ' + evalStudentName"></p>
                    </div>
                    <button @click="showEvalModal = false" class="text-white/70 hover:text-white transition-colors p-1" aria-label="Close">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <form
                    method="POST"
                    action="{{ route('supervisor.evaluate') }}"
                    class="px-5 sm:px-6 py-5 sm:py-6 space-y-5 overflow-y-auto flex-1">
                    @csrf

                    <!-- Hidden fields — values bound by Alpine -->
                    <input type="hidden" name="student_id"        :value="evalStudentId">
                    <input type="hidden" name="technical_score"   :value="techScore">
                    <input type="hidden" name="soft_skills_score" :value="softScore">
                    <input type="hidden" name="attitude_score"    :value="attScore">

                    <!-- Technical Skills -->
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-slate-700 mb-0.5">Technical Skills</label>
                        <p class="text-[11px] text-slate-400 mb-2">Quality of work, problem-solving, and domain knowledge.</p>
                        <div class="flex items-center gap-1">
                            <template x-for="n in [1,2,3,4,5]" :key="n">
                                <span @click="techScore = n"
                                      class="material-symbols-outlined text-[28px] sm:text-[34px] cursor-pointer select-none transition-colors duration-100"
                                      :class="techScore >= n ? 'text-amber-400' : 'text-slate-200'"
                                      style="font-variation-settings:'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 24">star</span>
                            </template>
                            <span class="ml-3 text-xs sm:text-sm font-extrabold text-slate-600" x-text="techScore + ' / 5'"></span>
                        </div>
                    </div>

                    <!-- Soft Skills -->
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-slate-700 mb-0.5">Soft Skills &amp; Communication</label>
                        <p class="text-[11px] text-slate-400 mb-2">Teamwork, communication, punctuality, and adaptability.</p>
                        <div class="flex items-center gap-1">
                            <template x-for="n in [1,2,3,4,5]" :key="n">
                                <span @click="softScore = n"
                                      class="material-symbols-outlined text-[28px] sm:text-[34px] cursor-pointer select-none transition-colors duration-100"
                                      :class="softScore >= n ? 'text-amber-400' : 'text-slate-200'"
                                      style="font-variation-settings:'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 24">star</span>
                            </template>
                            <span class="ml-3 text-xs sm:text-sm font-extrabold text-slate-600" x-text="softScore + ' / 5'"></span>
                        </div>
                    </div>

                    <!-- Attitude -->
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-slate-700 mb-0.5">Work Attitude &amp; Initiative</label>
                        <p class="text-[11px] text-slate-400 mb-2">Proactivity, work ethic, professionalism, and eagerness to learn.</p>
                        <div class="flex items-center gap-1">
                            <template x-for="n in [1,2,3,4,5]" :key="n">
                                <span @click="attScore = n"
                                      class="material-symbols-outlined text-[28px] sm:text-[34px] cursor-pointer select-none transition-colors duration-100"
                                      :class="attScore >= n ? 'text-amber-400' : 'text-slate-200'"
                                      style="font-variation-settings:'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 24">star</span>
                            </template>
                            <span class="ml-3 text-xs sm:text-sm font-extrabold text-slate-600" x-text="attScore + ' / 5'"></span>
                        </div>
                    </div>

                    <!-- Comments -->
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-slate-700 mb-1">Additional Comments <span class="text-slate-400 font-normal">(optional)</span></label>
                        <textarea name="comments" rows="3" placeholder="Any additional remarks about the intern's performance..."
                            class="w-full border border-slate-200 rounded-xl px-3.5 sm:px-4 py-2 text-xs sm:text-sm text-slate-700 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none bg-slate-50"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 sm:gap-3 pt-2">
                        <button type="button" @click.prevent="showEvalModal = false; techScore = 0; softScore = 0; attScore = 0;"
                            class="px-3.5 sm:px-4 py-2 border border-slate-200 text-slate-600 text-xs sm:text-sm font-semibold rounded-xl hover:bg-slate-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            :disabled="techScore === 0 || softScore === 0 || attScore === 0"
                            :class="(techScore === 0 || softScore === 0 || attScore === 0) ? 'opacity-40 cursor-not-allowed' : 'hover:bg-primary/90'"
                            class="px-4 sm:px-5 py-2 bg-primary text-white text-xs sm:text-sm font-bold rounded-xl transition-all active:scale-95 shadow-sm flex items-center gap-1.5 sm:gap-2">
                            <span class="material-symbols-outlined text-[16px] sm:text-[18px]">save</span>
                            Submit Evaluation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        // Responsive Sidebar Toggle
        const sidebarEl = document.getElementById('sidebar');
        const overlayEl = document.getElementById('sidebar-overlay');
        const toggleBtnEl = document.getElementById('sidebar-toggle');

        function toggleSidebar() {
            sidebarEl.classList.toggle('-translate-x-full');
            overlayEl.classList.toggle('hidden');
        }

        toggleBtnEl?.addEventListener('click', toggleSidebar);
        overlayEl?.addEventListener('click', toggleSidebar);

        // Search Filter (Both Table & Mobile Cards)
        function filterInternsTable() {
            const query = document.getElementById('internSearchInput').value.toLowerCase();
            
            // Filter Desktop Table Rows
            const rows = document.querySelectorAll('.intern-row');
            rows.forEach(row => {
                const name = row.dataset.name || '';
                const id = row.dataset.id || '';
                const course = row.dataset.course || '';
                const email = row.dataset.email || '';

                if (name.includes(query) || id.includes(query) || course.includes(query) || email.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            // Filter Mobile Cards
            const cards = document.querySelectorAll('.intern-mobile-card');
            cards.forEach(card => {
                const name = card.dataset.name || '';
                const id = card.dataset.id || '';
                const course = card.dataset.course || '';
                const email = card.dataset.email || '';

                if (name.includes(query) || id.includes(query) || course.includes(query) || email.includes(query)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
