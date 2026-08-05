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

        /* Star rating: completely hide the radio input, keep it accessible */
        .star-input {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0,0,0,0);
            white-space: nowrap;
            border: 0;
            appearance: none;
            -webkit-appearance: none;
        }
    </style>
</head>
<body class="bg-surface text-on-surface" data-theme="portal">

    <!-- SIDEBAR -->
    @include('components.supervisor-sidebar')

    <!-- TOP NAVIGATION -->
    <header class="fixed top-0 md:left-64 left-0 right-0 z-40 bg-[#fff7fd] border-b border-[#cec3d0]/15">
        <div class="flex justify-between items-center px-4 md:px-8 py-4 w-full">
            <div class="flex items-center gap-8">
                <button class="md:hidden p-2 text-primary rounded outline-none hover:bg-surface-container"><span class="material-symbols-outlined">menu</span></button>
                <span class="text-2xl font-headline font-semibold text-[#300050] tracking-tight hidden sm:block">Industry Supervisor</span>
            </div>
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-3">
                    <button class="p-2 text-[#300050] hover:bg-[#faf1f8] rounded-full transition-all active:scale-95">
                        <span class="material-symbols-outlined">notifications</span>
                    </button>
                    <div class="flex items-center gap-3 pl-2 border-l border-[#cec3d0]/30">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold font-headline text-[#300050]">{{ auth()->user()->name ?? 'Supervisor' }}</p>
                            <p class="text-[10px] uppercase tracking-wider text-secondary font-bold">Industry Partner</p>
                        </div>
                        <div class="w-9 h-9 border border-outline/30 rounded-full flex items-center justify-center bg-surface-container text-primary font-bold">
                            {{ strtoupper(substr(auth()->user()->name ?? 'SP', 0, 2)) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="md:ml-64 pt-24 px-4 md:px-8 pb-12" x-data="{ 
        showEvalModal: false, 
        evalStudentId: null, 
        evalStudentName: '',
        techScore: 0,
        softScore: 0,
        attScore: 0,
        existingEval: null
    }">
        <div class="p-6 max-w-7xl mx-auto">

            <!-- Flash Success Banner -->
            @if(session('success'))
                <div class="mb-6 flex items-center gap-3 bg-green-50 border border-green-200 rounded-xl px-5 py-4 text-green-800 text-sm font-semibold shadow-sm">
                    <span class="material-symbols-outlined text-green-500 text-[20px]">check_circle</span>
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-4xl font-extrabold font-headline text-primary tracking-tight">My Interns</h1>
                    <p class="text-on-surface/60 font-medium mt-1">Manage and monitor students currently assigned to your company deployment.</p>
                </div>
                <div class="bg-primary/10 text-primary text-xs font-semibold px-4 py-2 rounded-full border border-primary/20 flex items-center gap-2 self-start md:self-auto shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">group</span>
                    <span>Total: {{ $interns->count() }} Intern(s)</span>
                </div>
            </div>

            @if($interns->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($interns as $intern)
                        @php
                            $latestEval = $intern->evaluations->sortByDesc('evaluated_at')->first();
                        @endphp
                        <div class="bg-surface-container rounded-2xl border border-outline/30 shadow-sm hover:border-primary/30 hover:shadow-md transition-all duration-300 overflow-hidden flex flex-col justify-between p-6 group">
                            <div>
                                <div class="flex items-start gap-4">
                                    <div class="w-14 h-14 bg-primary/10 text-primary font-bold rounded-xl flex items-center justify-center text-xl flex-shrink-0 border border-primary/20 shadow-inner">
                                        {{ strtoupper(substr($intern->first_name ?? 'I', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0 pt-1">
                                        <h3 class="font-bold text-lg text-on-surface truncate group-hover:text-primary transition-colors">{{ $intern->first_name }} {{ $intern->last_name }}</h3>
                                        <p class="text-xs text-on-surface/60 mt-0.5 font-medium truncate">{{ $intern->course ?? 'Computing Student' }}</p>
                                        <p class="text-[11px] text-on-surface/40 mt-1 font-mono font-semibold">ID: {{ $intern->student_id_number ?? 'N/A' }}</p>
                                    </div>
                                </div>

                                <div class="mt-6">
                                    @php
                                        $approvedHours = floatval($intern->approved_hours ?? 0);
                                        $requiredHours = $intern->required_hours ?? 400;
                                        $percent = min(($approvedHours / max($requiredHours, 1)) * 100, 100);
                                    @endphp
                                    <div class="flex justify-between items-end mb-2">
                                        <span class="text-xs font-bold text-on-surface/60 uppercase tracking-widest">Verified Progress</span>
                                        <span class="text-primary font-bold text-sm bg-primary/5 px-2 py-0.5 rounded border border-primary/10">{{ number_format($approvedHours, 1) }} / {{ $requiredHours }} hrs</span>
                                    </div>
                                    <div class="w-full bg-outline/20 h-2 rounded-full overflow-hidden">
                                        <div class="bg-primary h-full rounded-full transition-all duration-500 relative" style="width: {{ $percent }}%">
                                            <div class="absolute inset-0 bg-white/20 w-full h-full"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Evaluation Summary -->
                                @if($latestEval)
                                    <div class="mt-5 bg-amber-50 border border-amber-200 rounded-xl p-3">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-amber-700 mb-2 flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[14px]">star</span> Last Evaluation
                                        </p>
                                        <div class="grid grid-cols-3 gap-2 text-center">
                                            <div>
                                                <p class="text-[9px] font-semibold text-slate-500 uppercase">Technical</p>
                                                <p class="text-sm font-extrabold text-amber-700">{{ number_format($latestEval->technical_score, 1) }}/5</p>
                                            </div>
                                            <div>
                                                <p class="text-[9px] font-semibold text-slate-500 uppercase">Soft Skills</p>
                                                <p class="text-sm font-extrabold text-amber-700">{{ number_format($latestEval->soft_skills_score, 1) }}/5</p>
                                            </div>
                                            <div>
                                                <p class="text-[9px] font-semibold text-slate-500 uppercase">Attitude</p>
                                                <p class="text-sm font-extrabold text-amber-700">{{ number_format($latestEval->attitude_score, 1) }}/5</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-6 pt-5 border-t border-outline/20 flex items-center justify-between gap-3">
                                <span class="text-on-surface/50 flex items-center gap-1.5 font-mono truncate text-xs font-medium">
                                    <span class="material-symbols-outlined text-[16px]">mail</span>
                                    {{ $intern->user->email ?? 'No email' }}
                                </span>
                                <button
                                    @click="showEvalModal = true; evalStudentId = {{ $intern->id }}; evalStudentName = '{{ $intern->first_name }} {{ $intern->last_name }}'; techScore = {{ $latestEval?->technical_score ?? 0 }}; softScore = {{ $latestEval?->soft_skills_score ?? 0 }}; attScore = {{ $latestEval?->attitude_score ?? 0 }}"
                                    class="flex-shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary text-white text-xs font-bold rounded-lg hover:bg-primary/90 active:scale-95 transition-all shadow-sm">
                                    <span class="material-symbols-outlined text-[15px]">star_rate</span>
                                    {{ $latestEval ? 'Re-Evaluate' : 'Evaluate' }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-surface-container rounded-2xl border border-dashed border-outline/40 p-12 text-center max-w-xl mx-auto mt-12">
                    <div class="w-20 h-20 bg-surface text-on-surface/30 rounded-full flex items-center justify-center mx-auto mb-5 shadow-sm border border-outline/20">
                        <span class="material-symbols-outlined text-4xl">badge_visibility</span>
                    </div>
                    <h3 class="text-xl font-bold font-headline text-on-surface">No Assigned Interns Found</h3>
                    <p class="text-sm text-on-surface/60 mt-3 max-w-md mx-auto leading-relaxed">There are currently no students linked to your industry account profile records. Please contact your school campus training coordinator to map deployments.</p>
                </div>
            @endif
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
            class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
            @click.self="showEvalModal = false">

            <div
                x-show="showEvalModal"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">

                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-[#3a0ca3] to-[#560bad] px-6 py-5 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-extrabold text-white font-headline">Intern Evaluation Rubric</h2>
                        <p class="text-white/70 text-xs mt-0.5" x-text="'Evaluating: ' + evalStudentName"></p>
                    </div>
                    <button @click="showEvalModal = false" class="text-white/70 hover:text-white transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <form
                    method="POST"
                    action="{{ route('supervisor.evaluate') }}"
                    class="px-6 py-6 space-y-6">
                    @csrf

                    <!-- Hidden fields — values bound by Alpine -->
                    <input type="hidden" name="student_id"        :value="evalStudentId">
                    <input type="hidden" name="technical_score"   :value="techScore">
                    <input type="hidden" name="soft_skills_score" :value="softScore">
                    <input type="hidden" name="attitude_score"    :value="attScore">

                    <!-- Technical Skills -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Technical Skills</label>
                        <p class="text-xs text-slate-400 mb-3">Quality of work, problem-solving, and domain knowledge.</p>
                        <div class="flex items-center gap-1">
                            <template x-for="n in [1,2,3,4,5]" :key="n">
                                <span @click="techScore = n"
                                      class="material-symbols-outlined text-[34px] cursor-pointer select-none transition-colors duration-100"
                                      :class="techScore >= n ? 'text-amber-400' : 'text-slate-200'"
                                      style="font-variation-settings:'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 24">star</span>
                            </template>
                            <span class="ml-3 text-sm font-extrabold text-slate-600" x-text="techScore + ' / 5'"></span>
                        </div>
                    </div>

                    <!-- Soft Skills -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Soft Skills &amp; Communication</label>
                        <p class="text-xs text-slate-400 mb-3">Teamwork, communication, punctuality, and adaptability.</p>
                        <div class="flex items-center gap-1">
                            <template x-for="n in [1,2,3,4,5]" :key="n">
                                <span @click="softScore = n"
                                      class="material-symbols-outlined text-[34px] cursor-pointer select-none transition-colors duration-100"
                                      :class="softScore >= n ? 'text-amber-400' : 'text-slate-200'"
                                      style="font-variation-settings:'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 24">star</span>
                            </template>
                            <span class="ml-3 text-sm font-extrabold text-slate-600" x-text="softScore + ' / 5'"></span>
                        </div>
                    </div>

                    <!-- Attitude -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Work Attitude &amp; Initiative</label>
                        <p class="text-xs text-slate-400 mb-3">Proactivity, work ethic, professionalism, and eagerness to learn.</p>
                        <div class="flex items-center gap-1">
                            <template x-for="n in [1,2,3,4,5]" :key="n">
                                <span @click="attScore = n"
                                      class="material-symbols-outlined text-[34px] cursor-pointer select-none transition-colors duration-100"
                                      :class="attScore >= n ? 'text-amber-400' : 'text-slate-200'"
                                      style="font-variation-settings:'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 24">star</span>
                            </template>
                            <span class="ml-3 text-sm font-extrabold text-slate-600" x-text="attScore + ' / 5'"></span>
                        </div>
                    </div>

                    <!-- Comments -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Additional Comments <span class="text-slate-400 font-normal">(optional)</span></label>
                        <textarea name="comments" rows="3" placeholder="Any additional remarks about the intern's performance..."
                            class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-700 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" @click.prevent="showEvalModal = false; techScore = 0; softScore = 0; attScore = 0;"
                            class="px-4 py-2 border border-slate-200 text-slate-600 text-sm font-semibold rounded-lg hover:bg-slate-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            :disabled="techScore === 0 || softScore === 0 || attScore === 0"
                            :class="(techScore === 0 || softScore === 0 || attScore === 0) ? 'opacity-40 cursor-not-allowed' : 'hover:bg-primary/90'"
                            class="px-5 py-2 bg-primary text-white text-sm font-bold rounded-lg transition-all active:scale-95 shadow-sm flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">save</span>
                            Submit Evaluation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
