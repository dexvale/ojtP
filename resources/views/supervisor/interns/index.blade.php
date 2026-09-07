<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>OJT Portal | My Interns</title>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:opsz,wght@6..72,400;6..72,600;6..72,700;6..72,800&family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Public Sans', sans-serif; }
        .font-headline { font-family: 'Newsreader', serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .material-icon-filled { font-family: 'Material Symbols Outlined'; font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24; }

        /* Hide Alpine-managed elements until Alpine.js initializes */
        [x-cloak] { display: none !important; }

        /* Custom scrollbar for evaluation modal */
        .eval-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .eval-scrollbar::-webkit-scrollbar-track {
            background: #f8fafc;
            border-radius: 8px;
        }
        .eval-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 8px;
        }
        .eval-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
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
    <main class="lg:ml-64 ml-0 pt-20 md:pt-24 px-4 sm:px-6 lg:px-8 pb-12" x-data="{ 
        showEvalModal: false, 
        evalStudentId: null, 
        evalStudentName: '',
        ratedByName: '{{ addslashes(auth()->user()->display_name ?? auth()->user()->email) }}',
        ratedByDesignation: 'OJT Supervisor / Host Agency Representative',
        comments: '',
        attendance: null,
        criteria: {
            technical_knowledge: null,
            time_management: null,
            problem_solving: null,
            quality_of_work: null,
            initiative_dependability: null,
            safety_consciousness: null,
            cooperation: null,
            personality: null,
            attitude_maturity: null,
            communication_skills: null,
            positive_attitude: null,
            self_confidence: null
        },
        setScore(key, val) {
            this.criteria[key] = val;
        },
        setAllScores(val) {
            for (let k in this.criteria) {
                this.criteria[k] = val;
            }
            this.attendance = val;
        },
        resetScores() {
            for (let k in this.criteria) {
                this.criteria[k] = null;
            }
            this.attendance = null;
        },
        ratedCount() {
            let count = 0;
            for (let k in this.criteria) {
                if (this.criteria[k] !== null && this.criteria[k] !== undefined && this.criteria[k] !== '') {
                    count++;
                }
            }
            if (this.attendance !== null && this.attendance !== undefined && this.attendance !== '') {
                count++;
            }
            return count;
        },
        isAllRated() {
            return this.ratedCount() === 13;
        },
        jpScore() {
            let items = [this.criteria.technical_knowledge, this.criteria.time_management, this.criteria.problem_solving].filter(v => v !== null && v !== undefined && v !== '');
            if (items.length === 0) return '0.00';
            let avg = items.reduce((a, b) => a + Number(b), 0) / items.length;
            return ((avg / 5) * 50).toFixed(2);
        },
        wmScore() {
            let items = [this.criteria.quality_of_work, this.criteria.initiative_dependability, this.criteria.safety_consciousness].filter(v => v !== null && v !== undefined && v !== '');
            if (items.length === 0) return '0.00';
            let avg = items.reduce((a, b) => a + Number(b), 0) / items.length;
            return ((avg / 5) * 20).toFixed(2);
        },
        whScore() {
            let items = [this.criteria.cooperation, this.criteria.personality, this.criteria.attitude_maturity, this.criteria.communication_skills, this.criteria.positive_attitude, this.criteria.self_confidence].filter(v => v !== null && v !== undefined && v !== '');
            if (items.length === 0) return '0.00';
            let avg = items.reduce((a, b) => a + Number(b), 0) / items.length;
            return ((avg / 5) * 20).toFixed(2);
        },
        attScore() {
            if (this.attendance === null || this.attendance === undefined || this.attendance === '') return '0.00';
            let val = Math.max(1, Math.min(5, Number(this.attendance)));
            return ((val / 5) * 10).toFixed(2);
        },
        finalRating() {
            if (this.ratedCount() === 0) return '0.00';
            let tot = parseFloat(this.jpScore()) + parseFloat(this.wmScore()) + parseFloat(this.whScore()) + parseFloat(this.attScore());
            return Math.min(100.0, Math.max(0.0, tot)).toFixed(2);
        },
        bisuGrade() {
            if (!this.isAllRated()) {
                return this.ratedCount() === 0 ? 'Pending' : '(' + this.ratedCount() + '/13 rated)';
            }
            let r = parseFloat(this.finalRating());
            if (r >= 95) return '1.0 (Excellent)';
            if (r >= 90) return (1.5 - ((r - 90) / 4) * 0.4).toFixed(1) + ' (Very Good)';
            if (r >= 85) return (2.0 - ((r - 85) / 4) * 0.4).toFixed(1) + ' (Good)';
            if (r >= 80) return (2.5 - ((r - 80) / 4) * 0.4).toFixed(1) + ' (Fair)';
            if (r >= 75) return (3.0 - ((r - 75) / 4) * 0.4).toFixed(1) + ' (Passed)';
            return '5.0 (Failure)';
        },
        openEvalModal(studentId, name, existingData) {
            this.evalStudentId = studentId;
            this.evalStudentName = name;
            if (typeof existingData === 'string') {
                try { existingData = JSON.parse(existingData); } catch(e) { existingData = null; }
            }
            if (existingData && existingData.criteria_scores) {
                this.criteria = {
                    technical_knowledge: existingData.criteria_scores.technical_knowledge ?? null,
                    time_management: existingData.criteria_scores.time_management ?? null,
                    problem_solving: existingData.criteria_scores.problem_solving ?? null,
                    quality_of_work: existingData.criteria_scores.quality_of_work ?? null,
                    initiative_dependability: existingData.criteria_scores.initiative_dependability ?? null,
                    safety_consciousness: existingData.criteria_scores.safety_consciousness ?? null,
                    cooperation: existingData.criteria_scores.cooperation ?? null,
                    personality: existingData.criteria_scores.personality ?? null,
                    attitude_maturity: existingData.criteria_scores.attitude_maturity ?? null,
                    communication_skills: existingData.criteria_scores.communication_skills ?? null,
                    positive_attitude: existingData.criteria_scores.positive_attitude ?? null,
                    self_confidence: existingData.criteria_scores.self_confidence ?? null
                };
                this.attendance = existingData.criteria_scores.attendance_rating ?? (existingData.attendance_score ? Math.round((existingData.attendance_score / 10) * 5) : null);
                this.comments = existingData.comments || '';
                this.ratedByName = existingData.rated_by_name || this.ratedByName;
                this.ratedByDesignation = existingData.rated_by_designation || this.ratedByDesignation;
            } else {
                this.resetScores();
                this.comments = '';
            }
            this.showEvalModal = true;
        }
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
                                        <div class="inline-flex flex-col gap-0.5">
                                            <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 border border-amber-200 rounded-lg text-amber-800 text-xs font-bold" title="Final Rating: {{ number_format($latestEval->final_rating, 2) }}%">
                                                <span class="material-symbols-outlined text-amber-500 text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                                <span>Grade: {{ $latestEval->transmuted_grade ? number_format($latestEval->transmuted_grade, 1) : '5.0' }}</span>
                                            </div>
                                            <span class="text-[10px] text-slate-500 font-semibold pl-1">{{ number_format($latestEval->final_rating, 1) }}% ({{ $latestEval->grade_description }})</span>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-500 border border-slate-200">
                                            Pending Evaluation
                                        </span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex items-center justify-end gap-2">
                                        @if($latestEval)
                                            <a href="{{ route('supervisor.interns.grading-sheet', $intern->id) }}" target="_blank"
                                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition-all border border-slate-200 shadow-sm"
                                               title="View & Print Official BISU Grading Sheet">
                                                <span class="material-symbols-outlined text-[15px]">print</span>
                                                <span>BISU Sheet</span>
                                            </a>
                                        @endif

                                        @php
                                            $internEvalData = $latestEval ? [
                                                'criteria_scores' => $latestEval->criteria_scores,
                                                'attendance_score' => $latestEval->attendance_score,
                                                'comments' => $latestEval->comments,
                                                'rated_by_name' => $latestEval->rated_by_name,
                                                'rated_by_designation' => $latestEval->rated_by_designation,
                                            ] : null;
                                        @endphp
                                        <button
                                            type="button"
                                            data-eval="{{ json_encode($internEvalData) }}"
                                            @click="openEvalModal({{ $intern->id }}, '{{ addslashes($studentName) }}', $el.getAttribute('data-eval'))"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-primary text-white text-xs font-bold rounded-lg hover:bg-primary/90 active:scale-95 transition-all shadow-sm">
                                            <span class="material-symbols-outlined text-[15px]" style="font-variation-settings: 'FILL' 1;">star_rate</span>
                                            <span>{{ $latestEval ? 'Re-Evaluate' : 'Evaluate' }}</span>
                                        </button>
                                    </div>
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
                            <div class="text-right flex-shrink-0">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-amber-50 border border-amber-200 rounded-lg text-amber-800 text-xs font-bold">
                                    <span class="material-symbols-outlined text-amber-500 text-[14px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                    Grade: {{ $latestEval->transmuted_grade ? number_format($latestEval->transmuted_grade, 1) : '5.0' }}
                                </span>
                                <p class="text-[10px] text-slate-500 font-semibold">{{ number_format($latestEval->final_rating, 1) }}%</p>
                            </div>
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

                    <!-- Action Buttons on Mobile -->
                    @php
                        $mobileEvalData = $latestEval ? [
                            'criteria_scores' => $latestEval->criteria_scores,
                            'attendance_score' => $latestEval->attendance_score,
                            'comments' => $latestEval->comments,
                            'rated_by_name' => $latestEval->rated_by_name,
                            'rated_by_designation' => $latestEval->rated_by_designation,
                        ] : null;
                    @endphp
                    <div class="flex items-center gap-2">
                        @if($latestEval)
                            <a href="{{ route('supervisor.interns.grading-sheet', $intern->id) }}" target="_blank"
                               class="flex-1 inline-flex items-center justify-center gap-1 px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl border border-slate-200 shadow-sm">
                                <span class="material-symbols-outlined text-[16px]">print</span>
                                <span>BISU Sheet</span>
                            </a>
                        @endif
                        <button
                            type="button"
                            data-eval="{{ json_encode($mobileEvalData) }}"
                            @click="openEvalModal({{ $intern->id }}, '{{ addslashes($studentName) }}', $el.getAttribute('data-eval'))"
                            class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-primary text-white text-xs font-bold rounded-xl hover:bg-primary/90 active:scale-95 transition-all shadow-sm">
                            <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star_rate</span>
                            <span>{{ $latestEval ? 'Re-Evaluate' : 'Evaluate' }}</span>
                        </button>
                    </div>
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

        <!-- ======= BISU APPRAISAL & EVALUATION MODAL ======= -->
        <div
            x-show="showEvalModal"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-2 sm:p-4"
            @click.self="showEvalModal = false">

            <div
                x-show="showEvalModal"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl xl:max-w-6xl overflow-hidden border border-purple-100 max-h-[92vh] flex flex-col">

                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-[#300050] to-[#560bad] px-5 sm:px-7 py-4 flex items-center justify-between flex-shrink-0 text-white shadow-sm">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center text-white flex-shrink-0 shadow-inner">
                            <span class="material-symbols-outlined text-[26px]">school</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h2 class="text-base sm:text-xl font-extrabold font-headline tracking-tight">BISU OJT Performance Appraisal &amp; Grading</h2>
                                <span class="bg-white/20 text-white text-[10px] sm:text-xs px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider border border-white/20">Official 50-20-20-10</span>
                            </div>
                            <p class="text-white/85 text-xs sm:text-sm mt-0.5">
                                Intern: <strong class="text-white font-bold" x-text="evalStudentName"></strong> • Bohol Island State University Balilihan Campus
                            </p>
                        </div>
                    </div>
                    <button @click="showEvalModal = false" class="text-white/70 hover:text-white hover:bg-white/10 p-2 rounded-xl transition-all" aria-label="Close modal">
                        <span class="material-symbols-outlined text-[24px]">close</span>
                    </button>
                </div>

                <!-- Sticky Score Summary Banner -->
                <div class="bg-slate-900 text-white px-5 sm:px-7 py-3 border-b border-slate-800 flex flex-wrap items-center justify-between gap-3 shadow-inner flex-shrink-0">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 text-xs w-full lg:w-auto">
                        <div class="bg-slate-800/90 px-3 py-2 rounded-xl border border-slate-700/60">
                            <span class="text-[10px] sm:text-[11px] text-slate-400 block font-bold uppercase tracking-wider">Job Perf (50%)</span>
                            <span class="font-black text-amber-400 text-sm sm:text-base" x-text="jpScore() + '%'"></span>
                        </div>
                        <div class="bg-slate-800/90 px-3 py-2 rounded-xl border border-slate-700/60">
                            <span class="text-[10px] sm:text-[11px] text-slate-400 block font-bold uppercase tracking-wider">Workmanship (20%)</span>
                            <span class="font-black text-amber-400 text-sm sm:text-base" x-text="wmScore() + '%'"></span>
                        </div>
                        <div class="bg-slate-800/90 px-3 py-2 rounded-xl border border-slate-700/60">
                            <span class="text-[10px] sm:text-[11px] text-slate-400 block font-bold uppercase tracking-wider">Work Habits (20%)</span>
                            <span class="font-black text-amber-400 text-sm sm:text-base" x-text="whScore() + '%'"></span>
                        </div>
                        <div class="bg-slate-800/90 px-3 py-2 rounded-xl border border-slate-700/60">
                            <span class="text-[10px] sm:text-[11px] text-slate-400 block font-bold uppercase tracking-wider">Attendance (10%)</span>
                            <span class="font-black text-amber-400 text-sm sm:text-base" x-text="attScore() + '%'"></span>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 sm:gap-6 ml-auto flex-wrap">
                        <!-- Progress Counter -->
                        <div class="text-right">
                            <span class="text-[10px] sm:text-[11px] text-slate-400 uppercase tracking-wider block font-bold">Rating Progress</span>
                            <div class="flex items-center justify-end gap-1.5 mt-0.5">
                                <span class="text-sm sm:text-base font-black" :class="isAllRated() ? 'text-emerald-400' : 'text-amber-300'" x-text="ratedCount() + ' / 13'"></span>
                                <span class="text-[10px] px-1.5 py-0.5 rounded font-bold uppercase tracking-wide"
                                    :class="isAllRated() ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30'"
                                    x-text="isAllRated() ? 'Done' : 'Pending'"></span>
                            </div>
                        </div>

                        <!-- Final Rating -->
                        <div class="text-right">
                            <span class="text-[10px] sm:text-[11px] text-slate-400 uppercase tracking-wider block font-bold">Final Rating</span>
                            <span class="text-lg sm:text-xl font-black text-emerald-400" x-text="finalRating() + '%'"></span>
                        </div>

                        <!-- BISU Grade -->
                        <div class="bg-emerald-500/15 border border-emerald-500/40 px-3.5 py-1.5 rounded-xl text-center min-w-[120px]">
                            <span class="text-[10px] sm:text-[11px] text-emerald-300 uppercase tracking-wider block font-bold">BISU Grade</span>
                            <span class="text-sm sm:text-base font-black text-white" x-text="bisuGrade()"></span>
                        </div>
                    </div>
                </div>

                <!-- Modal Form Body -->
                <form
                    method="POST"
                    action="{{ route('supervisor.evaluate') }}"
                    class="px-5 sm:px-7 py-5 space-y-6 overflow-y-auto flex-1 eval-scrollbar text-slate-800">
                    @csrf

                    <input type="hidden" name="student_id" :value="evalStudentId">
                    <input type="hidden" name="rated_by_name" :value="ratedByName">
                    <input type="hidden" name="rated_by_designation" :value="ratedByDesignation">

                    <!-- Quick Pre-fill toolbar -->
                    <div class="flex flex-wrap items-center justify-between gap-3 p-3.5 bg-purple-50/80 border border-purple-200/60 rounded-xl shadow-2xs">
                        <div class="flex items-center gap-2 text-primary text-xs sm:text-sm font-semibold">
                            <span class="material-symbols-outlined text-[20px] text-primary">info</span>
                            <span>Rating Scale: <strong>5</strong> (Outstanding) down to <strong>1</strong> (Poor) on each criterion. Please select a score for all 13 items.</span>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs text-slate-600 font-semibold mr-0.5">Quick Actions:</span>
                            <button type="button" @click="setAllScores(5)" class="px-3 py-1.5 bg-white border border-purple-200 text-purple-800 font-bold rounded-lg text-xs hover:bg-purple-100 hover:border-purple-300 transition-all shadow-2xs">Set All 5s (100%)</button>
                            <button type="button" @click="setAllScores(4)" class="px-3 py-1.5 bg-white border border-slate-200 text-slate-700 font-semibold rounded-lg text-xs hover:bg-slate-100 hover:border-slate-300 transition-all shadow-2xs">Set All 4s (80%)</button>
                            <button type="button" @click="resetScores()" class="px-3 py-1.5 bg-white border border-rose-200 text-rose-700 font-semibold rounded-lg text-xs hover:bg-rose-50 hover:border-rose-300 transition-all shadow-2xs flex items-center gap-1">
                                <span class="material-symbols-outlined text-[15px]">restart_alt</span>
                                <span>Clear All</span>
                            </button>
                        </div>
                    </div>

                    <!-- ================= SECTION 1: JOB PERFORMANCE (50%) ================= -->
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-6 shadow-sm space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-sm sm:text-base flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-black">1</span>
                                    <span>Job Performance (50% Weight)</span>
                                </h3>
                                <p class="text-xs sm:text-sm text-slate-600 font-medium mt-0.5">Evaluation of technical mastery, analytical thinking, and time management.</p>
                            </div>
                            <span class="text-xs sm:text-sm font-extrabold text-primary bg-purple-50 border border-purple-200/60 px-3 py-1 rounded-lg" x-text="jpScore() + ' / 50%'"></span>
                        </div>

                        <div class="space-y-3 pt-1">
                            <!-- 1. Technical Knowledge -->
                            <div class="p-3.5 sm:p-4 rounded-xl border transition-all"
                                :class="criteria.technical_knowledge ? 'bg-purple-50/20 border-purple-200/60' : 'bg-slate-50/40 border-slate-200/60 hover:bg-slate-50/80'">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-6">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-purple-100 text-primary font-black text-xs">1</span>
                                            <label class="font-bold text-slate-900 text-sm sm:text-base block">Technical Knowledge</label>
                                            <span x-show="criteria.technical_knowledge" class="inline-flex items-center gap-1 text-[11px] font-bold text-purple-700 bg-purple-100/80 px-2 py-0.5 rounded-md">
                                                Rated: <strong x-text="criteria.technical_knowledge + ' / 5'"></strong>
                                            </span>
                                            <span x-show="!criteria.technical_knowledge" class="text-[11px] font-medium text-amber-700 bg-amber-50 border border-amber-200/80 px-2 py-0.5 rounded-md">
                                                Unrated
                                            </span>
                                        </div>
                                        <p class="text-xs sm:text-sm text-slate-600 font-normal leading-relaxed mt-1 pl-7">
                                            Technical knowledge of current job and applied industry concepts.
                                        </p>
                                    </div>
                                    <input type="hidden" name="technical_knowledge" :value="criteria.technical_knowledge">
                                    <div class="flex items-center gap-1.5 self-end sm:self-center pl-7 sm:pl-0 flex-shrink-0">
                                        <template x-for="n in [5,4,3,2,1]" :key="n">
                                            <button type="button" @click="criteria.technical_knowledge = n"
                                                class="w-10 h-10 sm:w-11 sm:h-10 rounded-xl font-black text-sm transition-all flex items-center justify-center shadow-2xs"
                                                :class="criteria.technical_knowledge === n
                                                    ? 'bg-primary text-white shadow-md ring-2 ring-primary/30 scale-105'
                                                    : 'bg-white text-slate-700 hover:bg-purple-50 hover:text-primary border border-slate-200 hover:border-primary/40'"
                                                :title="'Rate ' + n + ' out of 5'"
                                                x-text="n"></button>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. Time Management Ability -->
                            <div class="p-3.5 sm:p-4 rounded-xl border transition-all"
                                :class="criteria.time_management ? 'bg-purple-50/20 border-purple-200/60' : 'bg-slate-50/40 border-slate-200/60 hover:bg-slate-50/80'">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-6">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-purple-100 text-primary font-black text-xs">2</span>
                                            <label class="font-bold text-slate-900 text-sm sm:text-base block">Time Management Ability</label>
                                            <span x-show="criteria.time_management" class="inline-flex items-center gap-1 text-[11px] font-bold text-purple-700 bg-purple-100/80 px-2 py-0.5 rounded-md">
                                                Rated: <strong x-text="criteria.time_management + ' / 5'"></strong>
                                            </span>
                                            <span x-show="!criteria.time_management" class="text-[11px] font-medium text-amber-700 bg-amber-50 border border-amber-200/80 px-2 py-0.5 rounded-md">
                                                Unrated
                                            </span>
                                        </div>
                                        <p class="text-xs sm:text-sm text-slate-600 font-normal leading-relaxed mt-1 pl-7">
                                            Able to perform and complete assigned work in given time and working well under pressure.
                                        </p>
                                    </div>
                                    <input type="hidden" name="time_management" :value="criteria.time_management">
                                    <div class="flex items-center gap-1.5 self-end sm:self-center pl-7 sm:pl-0 flex-shrink-0">
                                        <template x-for="n in [5,4,3,2,1]" :key="n">
                                            <button type="button" @click="criteria.time_management = n"
                                                class="w-10 h-10 sm:w-11 sm:h-10 rounded-xl font-black text-sm transition-all flex items-center justify-center shadow-2xs"
                                                :class="criteria.time_management === n
                                                    ? 'bg-primary text-white shadow-md ring-2 ring-primary/30 scale-105'
                                                    : 'bg-white text-slate-700 hover:bg-purple-50 hover:text-primary border border-slate-200 hover:border-primary/40'"
                                                :title="'Rate ' + n + ' out of 5'"
                                                x-text="n"></button>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. Problem-solving and Analytical Skills -->
                            <div class="p-3.5 sm:p-4 rounded-xl border transition-all"
                                :class="criteria.problem_solving ? 'bg-purple-50/20 border-purple-200/60' : 'bg-slate-50/40 border-slate-200/60 hover:bg-slate-50/80'">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-6">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-purple-100 text-primary font-black text-xs">3</span>
                                            <label class="font-bold text-slate-900 text-sm sm:text-base block">Problem-solving and Analytical Skills</label>
                                            <span x-show="criteria.problem_solving" class="inline-flex items-center gap-1 text-[11px] font-bold text-purple-700 bg-purple-100/80 px-2 py-0.5 rounded-md">
                                                Rated: <strong x-text="criteria.problem_solving + ' / 5'"></strong>
                                            </span>
                                            <span x-show="!criteria.problem_solving" class="text-[11px] font-medium text-amber-700 bg-amber-50 border border-amber-200/80 px-2 py-0.5 rounded-md">
                                                Unrated
                                            </span>
                                        </div>
                                        <p class="text-xs sm:text-sm text-slate-600 font-normal leading-relaxed mt-1 pl-7">
                                            Ability to find solutions to problems encountered in work assignments.
                                        </p>
                                    </div>
                                    <input type="hidden" name="problem_solving" :value="criteria.problem_solving">
                                    <div class="flex items-center gap-1.5 self-end sm:self-center pl-7 sm:pl-0 flex-shrink-0">
                                        <template x-for="n in [5,4,3,2,1]" :key="n">
                                            <button type="button" @click="criteria.problem_solving = n"
                                                class="w-10 h-10 sm:w-11 sm:h-10 rounded-xl font-black text-sm transition-all flex items-center justify-center shadow-2xs"
                                                :class="criteria.problem_solving === n
                                                    ? 'bg-primary text-white shadow-md ring-2 ring-primary/30 scale-105'
                                                    : 'bg-white text-slate-700 hover:bg-purple-50 hover:text-primary border border-slate-200 hover:border-primary/40'"
                                                :title="'Rate ' + n + ' out of 5'"
                                                x-text="n"></button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================= SECTION 2: WORKMANSHIP (20%) ================= -->
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-6 shadow-sm space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-sm sm:text-base flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-black">2</span>
                                    <span>Workmanship (20% Weight)</span>
                                </h3>
                                <p class="text-xs sm:text-sm text-slate-600 font-medium mt-0.5">Quality of output, self-reliance, and safety adherence.</p>
                            </div>
                            <span class="text-xs sm:text-sm font-extrabold text-primary bg-purple-50 border border-purple-200/60 px-3 py-1 rounded-lg" x-text="wmScore() + ' / 20%'"></span>
                        </div>

                        <div class="space-y-3 pt-1">
                            <!-- 4. Quality of Work -->
                            <div class="p-3.5 sm:p-4 rounded-xl border transition-all"
                                :class="criteria.quality_of_work ? 'bg-purple-50/20 border-purple-200/60' : 'bg-slate-50/40 border-slate-200/60 hover:bg-slate-50/80'">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-6">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-purple-100 text-primary font-black text-xs">4</span>
                                            <label class="font-bold text-slate-900 text-sm sm:text-base block">Quality of Work</label>
                                            <span x-show="criteria.quality_of_work" class="inline-flex items-center gap-1 text-[11px] font-bold text-purple-700 bg-purple-100/80 px-2 py-0.5 rounded-md">
                                                Rated: <strong x-text="criteria.quality_of_work + ' / 5'"></strong>
                                            </span>
                                            <span x-show="!criteria.quality_of_work" class="text-[11px] font-medium text-amber-700 bg-amber-50 border border-amber-200/80 px-2 py-0.5 rounded-md">
                                                Unrated
                                            </span>
                                        </div>
                                        <p class="text-xs sm:text-sm text-slate-600 font-normal leading-relaxed mt-1 pl-7">
                                            Ability for individual productivity with swiftness in performance of task.
                                        </p>
                                    </div>
                                    <input type="hidden" name="quality_of_work" :value="criteria.quality_of_work">
                                    <div class="flex items-center gap-1.5 self-end sm:self-center pl-7 sm:pl-0 flex-shrink-0">
                                        <template x-for="n in [5,4,3,2,1]" :key="n">
                                            <button type="button" @click="criteria.quality_of_work = n"
                                                class="w-10 h-10 sm:w-11 sm:h-10 rounded-xl font-black text-sm transition-all flex items-center justify-center shadow-2xs"
                                                :class="criteria.quality_of_work === n
                                                    ? 'bg-primary text-white shadow-md ring-2 ring-primary/30 scale-105'
                                                    : 'bg-white text-slate-700 hover:bg-purple-50 hover:text-primary border border-slate-200 hover:border-primary/40'"
                                                :title="'Rate ' + n + ' out of 5'"
                                                x-text="n"></button>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- 5. Initiative and Dependability -->
                            <div class="p-3.5 sm:p-4 rounded-xl border transition-all"
                                :class="criteria.initiative_dependability ? 'bg-purple-50/20 border-purple-200/60' : 'bg-slate-50/40 border-slate-200/60 hover:bg-slate-50/80'">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-6">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-purple-100 text-primary font-black text-xs">5</span>
                                            <label class="font-bold text-slate-900 text-sm sm:text-base block">Initiative and Dependability</label>
                                            <span x-show="criteria.initiative_dependability" class="inline-flex items-center gap-1 text-[11px] font-bold text-purple-700 bg-purple-100/80 px-2 py-0.5 rounded-md">
                                                Rated: <strong x-text="criteria.initiative_dependability + ' / 5'"></strong>
                                            </span>
                                            <span x-show="!criteria.initiative_dependability" class="text-[11px] font-medium text-amber-700 bg-amber-50 border border-amber-200/80 px-2 py-0.5 rounded-md">
                                                Unrated
                                            </span>
                                        </div>
                                        <p class="text-xs sm:text-sm text-slate-600 font-normal leading-relaxed mt-1 pl-7">
                                            Understand and follow instructions with less supervision. Able to perform and complete assigned work given. Show initiative and interest in other tasks.
                                        </p>
                                    </div>
                                    <input type="hidden" name="initiative_dependability" :value="criteria.initiative_dependability">
                                    <div class="flex items-center gap-1.5 self-end sm:self-center pl-7 sm:pl-0 flex-shrink-0">
                                        <template x-for="n in [5,4,3,2,1]" :key="n">
                                            <button type="button" @click="criteria.initiative_dependability = n"
                                                class="w-10 h-10 sm:w-11 sm:h-10 rounded-xl font-black text-sm transition-all flex items-center justify-center shadow-2xs"
                                                :class="criteria.initiative_dependability === n
                                                    ? 'bg-primary text-white shadow-md ring-2 ring-primary/30 scale-105'
                                                    : 'bg-white text-slate-700 hover:bg-purple-50 hover:text-primary border border-slate-200 hover:border-primary/40'"
                                                :title="'Rate ' + n + ' out of 5'"
                                                x-text="n"></button>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- 6. Safety Consciousness -->
                            <div class="p-3.5 sm:p-4 rounded-xl border transition-all"
                                :class="criteria.safety_consciousness ? 'bg-purple-50/20 border-purple-200/60' : 'bg-slate-50/40 border-slate-200/60 hover:bg-slate-50/80'">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-6">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-purple-100 text-primary font-black text-xs">6</span>
                                            <label class="font-bold text-slate-900 text-sm sm:text-base block">Safety Consciousness</label>
                                            <span x-show="criteria.safety_consciousness" class="inline-flex items-center gap-1 text-[11px] font-bold text-purple-700 bg-purple-100/80 px-2 py-0.5 rounded-md">
                                                Rated: <strong x-text="criteria.safety_consciousness + ' / 5'"></strong>
                                            </span>
                                            <span x-show="!criteria.safety_consciousness" class="text-[11px] font-medium text-amber-700 bg-amber-50 border border-amber-200/80 px-2 py-0.5 rounded-md">
                                                Unrated
                                            </span>
                                        </div>
                                        <p class="text-xs sm:text-sm text-slate-600 font-normal leading-relaxed mt-1 pl-7">
                                            Awareness of safety practices and strict compliance with company health and safety standards.
                                        </p>
                                    </div>
                                    <input type="hidden" name="safety_consciousness" :value="criteria.safety_consciousness">
                                    <div class="flex items-center gap-1.5 self-end sm:self-center pl-7 sm:pl-0 flex-shrink-0">
                                        <template x-for="n in [5,4,3,2,1]" :key="n">
                                            <button type="button" @click="criteria.safety_consciousness = n"
                                                class="w-10 h-10 sm:w-11 sm:h-10 rounded-xl font-black text-sm transition-all flex items-center justify-center shadow-2xs"
                                                :class="criteria.safety_consciousness === n
                                                    ? 'bg-primary text-white shadow-md ring-2 ring-primary/30 scale-105'
                                                    : 'bg-white text-slate-700 hover:bg-purple-50 hover:text-primary border border-slate-200 hover:border-primary/40'"
                                                :title="'Rate ' + n + ' out of 5'"
                                                x-text="n"></button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================= SECTION 3: WORK HABITS & ATTITUDES (20%) ================= -->
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-6 shadow-sm space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-sm sm:text-base flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-black">3</span>
                                    <span>Work Habits and Attitudes (20% Weight)</span>
                                </h3>
                                <p class="text-xs sm:text-sm text-slate-600 font-medium mt-0.5">Professionalism, cooperation, and personal growth.</p>
                            </div>
                            <span class="text-xs sm:text-sm font-extrabold text-primary bg-purple-50 border border-purple-200/60 px-3 py-1 rounded-lg" x-text="whScore() + ' / 20%'"></span>
                        </div>

                        <div class="space-y-3 pt-1">
                            <!-- 7. Cooperation -->
                            <div class="p-3.5 sm:p-4 rounded-xl border transition-all"
                                :class="criteria.cooperation ? 'bg-purple-50/20 border-purple-200/60' : 'bg-slate-50/40 border-slate-200/60 hover:bg-slate-50/80'">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-6">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-purple-100 text-primary font-black text-xs">7</span>
                                            <label class="font-bold text-slate-900 text-sm sm:text-base block">Cooperation</label>
                                            <span x-show="criteria.cooperation" class="inline-flex items-center gap-1 text-[11px] font-bold text-purple-700 bg-purple-100/80 px-2 py-0.5 rounded-md">
                                                Rated: <strong x-text="criteria.cooperation + ' / 5'"></strong>
                                            </span>
                                            <span x-show="!criteria.cooperation" class="text-[11px] font-medium text-amber-700 bg-amber-50 border border-amber-200/80 px-2 py-0.5 rounded-md">
                                                Unrated
                                            </span>
                                        </div>
                                        <p class="text-xs sm:text-sm text-slate-600 font-normal leading-relaxed mt-1 pl-7">
                                            Ability to work in harmony with colleagues, supervisors, and team members.
                                        </p>
                                    </div>
                                    <input type="hidden" name="cooperation" :value="criteria.cooperation">
                                    <div class="flex items-center gap-1.5 self-end sm:self-center pl-7 sm:pl-0 flex-shrink-0">
                                        <template x-for="n in [5,4,3,2,1]" :key="n">
                                            <button type="button" @click="criteria.cooperation = n"
                                                class="w-10 h-10 sm:w-11 sm:h-10 rounded-xl font-black text-sm transition-all flex items-center justify-center shadow-2xs"
                                                :class="criteria.cooperation === n
                                                    ? 'bg-primary text-white shadow-md ring-2 ring-primary/30 scale-105'
                                                    : 'bg-white text-slate-700 hover:bg-purple-50 hover:text-primary border border-slate-200 hover:border-primary/40'"
                                                :title="'Rate ' + n + ' out of 5'"
                                                x-text="n"></button>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- 8. Personality -->
                            <div class="p-3.5 sm:p-4 rounded-xl border transition-all"
                                :class="criteria.personality ? 'bg-purple-50/20 border-purple-200/60' : 'bg-slate-50/40 border-slate-200/60 hover:bg-slate-50/80'">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-6">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-purple-100 text-primary font-black text-xs">8</span>
                                            <label class="font-bold text-slate-900 text-sm sm:text-base block">Personality</label>
                                            <span x-show="criteria.personality" class="inline-flex items-center gap-1 text-[11px] font-bold text-purple-700 bg-purple-100/80 px-2 py-0.5 rounded-md">
                                                Rated: <strong x-text="criteria.personality + ' / 5'"></strong>
                                            </span>
                                            <span x-show="!criteria.personality" class="text-[11px] font-medium text-amber-700 bg-amber-50 border border-amber-200/80 px-2 py-0.5 rounded-md">
                                                Unrated
                                            </span>
                                        </div>
                                        <p class="text-xs sm:text-sm text-slate-600 font-normal leading-relaxed mt-1 pl-7">
                                            Able to adjust with varied and diverse personalities. Ability to be tactful at most times, observe and extend courtesies to all.
                                        </p>
                                    </div>
                                    <input type="hidden" name="personality" :value="criteria.personality">
                                    <div class="flex items-center gap-1.5 self-end sm:self-center pl-7 sm:pl-0 flex-shrink-0">
                                        <template x-for="n in [5,4,3,2,1]" :key="n">
                                            <button type="button" @click="criteria.personality = n"
                                                class="w-10 h-10 sm:w-11 sm:h-10 rounded-xl font-black text-sm transition-all flex items-center justify-center shadow-2xs"
                                                :class="criteria.personality === n
                                                    ? 'bg-primary text-white shadow-md ring-2 ring-primary/30 scale-105'
                                                    : 'bg-white text-slate-700 hover:bg-purple-50 hover:text-primary border border-slate-200 hover:border-primary/40'"
                                                :title="'Rate ' + n + ' out of 5'"
                                                x-text="n"></button>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- 9. Attitude / Mental Maturity -->
                            <div class="p-3.5 sm:p-4 rounded-xl border transition-all"
                                :class="criteria.attitude_maturity ? 'bg-purple-50/20 border-purple-200/60' : 'bg-slate-50/40 border-slate-200/60 hover:bg-slate-50/80'">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-6">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-purple-100 text-primary font-black text-xs">9</span>
                                            <label class="font-bold text-slate-900 text-sm sm:text-base block">Attitude / Mental Maturity</label>
                                            <span x-show="criteria.attitude_maturity" class="inline-flex items-center gap-1 text-[11px] font-bold text-purple-700 bg-purple-100/80 px-2 py-0.5 rounded-md">
                                                Rated: <strong x-text="criteria.attitude_maturity + ' / 5'"></strong>
                                            </span>
                                            <span x-show="!criteria.attitude_maturity" class="text-[11px] font-medium text-amber-700 bg-amber-50 border border-amber-200/80 px-2 py-0.5 rounded-md">
                                                Unrated
                                            </span>
                                        </div>
                                        <p class="text-xs sm:text-sm text-slate-600 font-normal leading-relaxed mt-1 pl-7">
                                            Able to absorb comments, suggestions, and feedback positively.
                                        </p>
                                    </div>
                                    <input type="hidden" name="attitude_maturity" :value="criteria.attitude_maturity">
                                    <div class="flex items-center gap-1.5 self-end sm:self-center pl-7 sm:pl-0 flex-shrink-0">
                                        <template x-for="n in [5,4,3,2,1]" :key="n">
                                            <button type="button" @click="criteria.attitude_maturity = n"
                                                class="w-10 h-10 sm:w-11 sm:h-10 rounded-xl font-black text-sm transition-all flex items-center justify-center shadow-2xs"
                                                :class="criteria.attitude_maturity === n
                                                    ? 'bg-primary text-white shadow-md ring-2 ring-primary/30 scale-105'
                                                    : 'bg-white text-slate-700 hover:bg-purple-50 hover:text-primary border border-slate-200 hover:border-primary/40'"
                                                :title="'Rate ' + n + ' out of 5'"
                                                x-text="n"></button>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- 10. Good Communication Skills -->
                            <div class="p-3.5 sm:p-4 rounded-xl border transition-all"
                                :class="criteria.communication_skills ? 'bg-purple-50/20 border-purple-200/60' : 'bg-slate-50/40 border-slate-200/60 hover:bg-slate-50/80'">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-6">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-purple-100 text-primary font-black text-xs">10</span>
                                            <label class="font-bold text-slate-900 text-sm sm:text-base block">Good Communication Skills</label>
                                            <span x-show="criteria.communication_skills" class="inline-flex items-center gap-1 text-[11px] font-bold text-purple-700 bg-purple-100/80 px-2 py-0.5 rounded-md">
                                                Rated: <strong x-text="criteria.communication_skills + ' / 5'"></strong>
                                            </span>
                                            <span x-show="!criteria.communication_skills" class="text-[11px] font-medium text-amber-700 bg-amber-50 border border-amber-200/80 px-2 py-0.5 rounded-md">
                                                Unrated
                                            </span>
                                        </div>
                                        <p class="text-xs sm:text-sm text-slate-600 font-normal leading-relaxed mt-1 pl-7">
                                            The ability to convey information to others effectively, professionally, and efficiently.
                                        </p>
                                    </div>
                                    <input type="hidden" name="communication_skills" :value="criteria.communication_skills">
                                    <div class="flex items-center gap-1.5 self-end sm:self-center pl-7 sm:pl-0 flex-shrink-0">
                                        <template x-for="n in [5,4,3,2,1]" :key="n">
                                            <button type="button" @click="criteria.communication_skills = n"
                                                class="w-10 h-10 sm:w-11 sm:h-10 rounded-xl font-black text-sm transition-all flex items-center justify-center shadow-2xs"
                                                :class="criteria.communication_skills === n
                                                    ? 'bg-primary text-white shadow-md ring-2 ring-primary/30 scale-105'
                                                    : 'bg-white text-slate-700 hover:bg-purple-50 hover:text-primary border border-slate-200 hover:border-primary/40'"
                                                :title="'Rate ' + n + ' out of 5'"
                                                x-text="n"></button>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- 11. Positive Attitude -->
                            <div class="p-3.5 sm:p-4 rounded-xl border transition-all"
                                :class="criteria.positive_attitude ? 'bg-purple-50/20 border-purple-200/60' : 'bg-slate-50/40 border-slate-200/60 hover:bg-slate-50/80'">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-6">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-purple-100 text-primary font-black text-xs">11</span>
                                            <label class="font-bold text-slate-900 text-sm sm:text-base block">Positive Attitude</label>
                                            <span x-show="criteria.positive_attitude" class="inline-flex items-center gap-1 text-[11px] font-bold text-purple-700 bg-purple-100/80 px-2 py-0.5 rounded-md">
                                                Rated: <strong x-text="criteria.positive_attitude + ' / 5'"></strong>
                                            </span>
                                            <span x-show="!criteria.positive_attitude" class="text-[11px] font-medium text-amber-700 bg-amber-50 border border-amber-200/80 px-2 py-0.5 rounded-md">
                                                Unrated
                                            </span>
                                        </div>
                                        <p class="text-xs sm:text-sm text-slate-600 font-normal leading-relaxed mt-1 pl-7">
                                            Ability to accept and learn from criticism and can easily overcome failures and discouragements.
                                        </p>
                                    </div>
                                    <input type="hidden" name="positive_attitude" :value="criteria.positive_attitude">
                                    <div class="flex items-center gap-1.5 self-end sm:self-center pl-7 sm:pl-0 flex-shrink-0">
                                        <template x-for="n in [5,4,3,2,1]" :key="n">
                                            <button type="button" @click="criteria.positive_attitude = n"
                                                class="w-10 h-10 sm:w-11 sm:h-10 rounded-xl font-black text-sm transition-all flex items-center justify-center shadow-2xs"
                                                :class="criteria.positive_attitude === n
                                                    ? 'bg-primary text-white shadow-md ring-2 ring-primary/30 scale-105'
                                                    : 'bg-white text-slate-700 hover:bg-purple-50 hover:text-primary border border-slate-200 hover:border-primary/40'"
                                                :title="'Rate ' + n + ' out of 5'"
                                                x-text="n"></button>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- 12. Self Confidence -->
                            <div class="p-3.5 sm:p-4 rounded-xl border transition-all"
                                :class="criteria.self_confidence ? 'bg-purple-50/20 border-purple-200/60' : 'bg-slate-50/40 border-slate-200/60 hover:bg-slate-50/80'">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-6">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-purple-100 text-primary font-black text-xs">12</span>
                                            <label class="font-bold text-slate-900 text-sm sm:text-base block">Self Confidence</label>
                                            <span x-show="criteria.self_confidence" class="inline-flex items-center gap-1 text-[11px] font-bold text-purple-700 bg-purple-100/80 px-2 py-0.5 rounded-md">
                                                Rated: <strong x-text="criteria.self_confidence + ' / 5'"></strong>
                                            </span>
                                            <span x-show="!criteria.self_confidence" class="text-[11px] font-medium text-amber-700 bg-amber-50 border border-amber-200/80 px-2 py-0.5 rounded-md">
                                                Unrated
                                            </span>
                                        </div>
                                        <p class="text-xs sm:text-sm text-slate-600 font-normal leading-relaxed mt-1 pl-7">
                                            Ability to have positive yet realistic views of themselves and their situations.
                                        </p>
                                    </div>
                                    <input type="hidden" name="self_confidence" :value="criteria.self_confidence">
                                    <div class="flex items-center gap-1.5 self-end sm:self-center pl-7 sm:pl-0 flex-shrink-0">
                                        <template x-for="n in [5,4,3,2,1]" :key="n">
                                            <button type="button" @click="criteria.self_confidence = n"
                                                class="w-10 h-10 sm:w-11 sm:h-10 rounded-xl font-black text-sm transition-all flex items-center justify-center shadow-2xs"
                                                :class="criteria.self_confidence === n
                                                    ? 'bg-primary text-white shadow-md ring-2 ring-primary/30 scale-105'
                                                    : 'bg-white text-slate-700 hover:bg-purple-50 hover:text-primary border border-slate-200 hover:border-primary/40'"
                                                :title="'Rate ' + n + ' out of 5'"
                                                x-text="n"></button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================= SECTION 4: ATTENDANCE (10%) ================= -->
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-6 shadow-sm space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-sm sm:text-base flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-black">4</span>
                                    <span>Attendance &amp; Punctuality (10% Weight)</span>
                                </h3>
                                <p class="text-xs sm:text-sm text-slate-600 font-medium mt-0.5">Reliability, shift punctuality, and completion of required internship hours.</p>
                            </div>
                            <span class="text-xs sm:text-sm font-extrabold text-emerald-700 bg-emerald-50 border border-emerald-200/60 px-3 py-1 rounded-lg" x-text="attScore() + ' / 10%'"></span>
                        </div>

                        <div class="p-3.5 sm:p-4 rounded-xl border transition-all"
                            :class="attendance ? 'bg-emerald-50/30 border-emerald-200/60' : 'bg-slate-50/40 border-slate-200/60 hover:bg-slate-50/80'">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-6">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-emerald-100 text-emerald-700 font-black text-xs">13</span>
                                        <label class="font-bold text-slate-900 text-sm sm:text-base block">Attendance &amp; Punctuality Rating</label>
                                        <span x-show="attendance" class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-800 bg-emerald-100/90 px-2 py-0.5 rounded-md">
                                            Rated: <strong x-text="attendance + ' / 5'"></strong>
                                        </span>
                                        <span x-show="!attendance" class="text-[11px] font-medium text-amber-700 bg-amber-50 border border-amber-200/80 px-2 py-0.5 rounded-md">
                                            Unrated
                                        </span>
                                    </div>
                                    <p class="text-xs sm:text-sm text-slate-600 font-normal leading-relaxed mt-1 pl-7">
                                        <strong>5</strong> = 100% full hours &amp; excellent punctuality; <strong>4</strong> = Very Good (&lt;5% tardiness); <strong>3</strong> = Average; <strong>2</strong> = Below average; <strong>1</strong> = Excessive absences or tardiness.
                                    </p>
                                </div>
                                <input type="hidden" name="attendance_rating" :value="attendance">
                                <div class="flex items-center gap-1.5 self-end sm:self-center pl-7 sm:pl-0 flex-shrink-0">
                                    <template x-for="n in [5,4,3,2,1]" :key="n">
                                        <button type="button" @click="attendance = n"
                                            class="w-10 h-10 sm:w-11 sm:h-10 rounded-xl font-black text-sm transition-all flex items-center justify-center shadow-2xs"
                                            :class="attendance === n
                                                ? 'bg-emerald-600 text-white shadow-md ring-2 ring-emerald-400/40 scale-105'
                                                : 'bg-white text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 border border-slate-200 hover:border-emerald-300'"
                                            :title="'Rate attendance ' + n + ' out of 5'"
                                            x-text="n"></button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================= SECTION 5: COMMENTS ================= -->
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-6 shadow-sm space-y-2">
                        <label class="block text-sm sm:text-base font-bold text-slate-900">
                            Comments for Enrichment of the OJT Program / Improvement of the Trainees
                        </label>
                        <p class="text-xs sm:text-sm text-slate-500">Provide specific observations, commendations, or constructive guidance for the student intern.</p>
                        <textarea name="comments" x-model="comments" rows="3"
                            placeholder="State areas of strength, constructive feedback, or suggestions for the trainee..."
                            class="w-full border border-slate-200 rounded-xl px-4 py-3 text-xs sm:text-sm text-slate-800 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none bg-slate-50/50 mt-1"></textarea>
                    </div>

                    <!-- ================= SECTION 6: SIGNATORY ================= -->
                    <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 sm:p-6 space-y-3">
                        <h4 class="font-bold text-slate-800 text-xs sm:text-sm uppercase tracking-wider flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px] text-slate-600">draw</span>
                            <span>Official Evaluator Signatory Details</span>
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Rated By (Signature over Printed Name)</label>
                                <input type="text" x-model="ratedByName" required
                                    class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Designation / Title</label>
                                <input type="text" x-model="ratedByDesignation" required
                                    class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            </div>
                        </div>
                    </div>

                    <!-- Modal Actions Sticky Footer -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-4 border-t border-slate-200">
                        <div class="text-xs sm:text-sm text-slate-600">
                            <span x-show="!isAllRated()" class="text-amber-700 font-semibold flex items-center gap-1.5 bg-amber-50 px-3 py-1.5 rounded-lg border border-amber-200/80">
                                <span class="material-symbols-outlined text-[18px] text-amber-600">warning</span>
                                <span>Please rate all 13 criteria to finalize (<strong x-text="13 - ratedCount()"></strong> remaining).</span>
                            </span>
                            <span x-show="isAllRated()" class="text-emerald-700 font-semibold flex items-center gap-1.5 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-200/80">
                                <span class="material-symbols-outlined text-[18px] text-emerald-600">check_circle</span>
                                <span>All 13 criteria rated! Ready to save and submit.</span>
                            </span>
                        </div>
                        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                            <button type="button" @click="showEvalModal = false"
                                class="px-4 py-2.5 border border-slate-200 text-slate-700 text-xs sm:text-sm font-semibold rounded-xl hover:bg-slate-100 transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                :disabled="!isAllRated()"
                                :class="isAllRated()
                                    ? 'bg-primary hover:bg-primary/90 text-white cursor-pointer active:scale-95 shadow-md hover:shadow-lg'
                                    : 'bg-slate-200 text-slate-400 cursor-not-allowed border border-slate-300'"
                                class="px-5 py-2.5 text-xs sm:text-sm font-bold rounded-xl transition-all flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">verified</span>
                                <span>Save &amp; Finalize BISU Evaluation</span>
                            </button>
                        </div>
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
