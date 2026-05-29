<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>OJT Portal | Pending Approvals</title>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:opsz,wght@6..72,400;6..72,600;6..72,700;6..72,800&family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Public Sans', sans-serif; }
        .font-headline { font-family: 'Newsreader', serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .material-icon-filled { font-family: 'Material Symbols Outlined'; font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    </style>
</head>
<body class="bg-surface text-on-surface" data-theme="portal">

    <!-- SIDEBAR -->
    <!-- SIDEBAR (Supervisor Version - Shared Component) -->
    @include('components.supervisor-sidebar')

    <!-- TOP NAVIGATION -->
    <header class="fixed top-0 md:left-64 left-0 right-0 z-40 bg-[#fff7fd] border-b border-[#cec3d0]/15">
        <div class="flex justify-between items-center px-4 md:px-8 py-4 w-full">
            <div class="flex items-center gap-8">
                <button class="md:hidden p-2 text-primary rounded outline-none hover:bg-surface-container"><span class="material-symbols-outlined">menu</span></button>
                <span class="text-2xl font-headline font-semibold text-[#300050] tracking-tight hidden sm:block">Industry Supervisor</span>
            </div>
            <div class="flex items-center gap-6">
                <!-- Notifications & Profile -->
                <div class="flex items-center gap-3">
                    <button class="p-2 text-[#300050] hover:bg-[#faf1f8] rounded-full transition-all active:scale-95">
                        <span class="material-symbols-outlined">notifications</span>
                    </button>
                    <div class="flex items-center gap-3 pl-2 border-l border-[#cec3d0]/30">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold font-headline text-[#300050]">Mr. David Miller</p>
                            <p class="text-[10px] uppercase tracking-wider text-secondary font-bold">Tech Lead - IT Dept</p>
                        </div>
                        <div class="w-9 h-9 border border-outline/30 rounded-full flex items-center justify-center bg-surface-container text-primary font-bold">DM</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="md:ml-64 pt-24 px-4 md:px-8 pb-12 overflow-x-hidden">
        
        <!-- Wrapper for centralization max-w-4xl -->
        <div class="max-w-4xl mx-auto">
            
            <!-- Page Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-4xl font-extrabold font-headline text-primary tracking-tight">Pending Approvals</h1>
                    <p class="text-on-surface/60 font-medium mt-1">Review daily task logs and validate intern hours.</p>
                </div>
                <!-- Global Actions -->
                <div class="flex items-center gap-3 z-10 hidden sm:flex">
                    <div class="relative">
                        <select class="appearance-none bg-surface-container border border-outline/20 pl-4 py-2.5 pr-8 rounded-lg text-sm font-bold text-on-surface/70 shadow-sm focus:ring-primary focus:border-primary cursor-pointer w-44 outline-none">
                            <option>Sort by: Oldest First</option>
                            <option>Sort by: Newest First</option>
                            <option>Sort by: Intern Name</option>
                            <option>Sort by: Highest Hours</option>
                        </select>
                        <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-on-surface/50 pointer-events-none text-[20px]">expand_more</span>
                    </div>

                    @if($status === 'Pending' && $logs->count() > 0)
                        <button class="bg-purple-900 text-white rounded-lg px-4 py-2 text-sm font-semibold flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">done_all</span>
                            Batch Approve All ({{ $logs->count() }})
                        </button>
                    @endif
                </div>
            </div>

            <div class="flex border-b border-gray-200 mb-6 gap-6">
                <a href="{{ route('supervisor.approvals', ['status' => 'Pending']) }}" class="pb-3 text-sm font-semibold border-b-2 {{ $status === 'Pending' ? 'border-purple-600 text-purple-900' : 'border-transparent text-gray-500 hover:text-purple-600' }}">
                    Pending Queue
                </a>
                <a href="{{ route('supervisor.approvals', ['status' => 'Approved']) }}" class="pb-3 text-sm font-semibold border-b-2 {{ $status === 'Approved' ? 'border-purple-600 text-purple-900' : 'border-transparent text-gray-500 hover:text-purple-600' }}">
                    Approved Logs
                </a>
                <a href="{{ route('supervisor.approvals', ['status' => 'Rejected']) }}" class="pb-3 text-sm font-semibold border-b-2 {{ $status === 'Rejected' ? 'border-purple-600 text-purple-900' : 'border-transparent text-gray-500 hover:text-purple-600' }}">
                    Rejected / Revisions
                </a>
            </div>

            <!-- Review Queue List -->
            <div class="flex flex-col gap-6">

                @forelse($logs as $log)
                <div class="bg-surface-container rounded-2xl shadow-sm border border-outline/20 p-6 transition-all hover:bg-white relative">
                    <!-- Card Header -->
                    <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-sm border-2 border-surface border-primary/20 shadow-sm">{{ strtoupper(substr($log->user->name, 0, 2)) }}</div>
                            <div>
                                <h3 class="font-bold text-base text-on-surface">{{ $log->user->name }}</h3>
                                <p class="text-xs font-semibold text-on-surface/50">{{ $log->user->studentProfile->course ?? 'Intern' }} • {{ $log->log_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <div class="bg-surface border border-outline/30 px-3 py-1.5 rounded-lg shadow-sm">
                            <span class="text-xs font-bold text-on-surface/70 tracking-wide">Hours Logged: <span class="text-primary font-black ml-1 text-sm font-headline">{{ number_format($log->hours_rendered, 1) }} hrs</span></span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <!-- Main Grid Container: Splits layout into 3 rigid columns on desktop viewports -->
                    <div class="w-full grid grid-cols-1 md:grid-cols-3 gap-6 items-start mt-4 min-w-0">
                        
                        <!-- LEFT SIDE: Task Description Column (Takes up 2 out of 3 columns) -->
                        <div class="md:col-span-2 w-full min-w-0">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Task Description</span>
                            <div class="w-full bg-gray-50 rounded-lg p-4 border border-gray-100 min-w-0 overflow-hidden">
                                <p class="text-sm text-gray-600 whitespace-pre-wrap break-all [word-break:break-all] [overflow-wrap:anywhere] leading-relaxed">
                                    {{ $log->tasks_performed }}
                                </p>
                            </div>
                        </div>

                        <!-- RIGHT SIDE: Evidence Photo Column (Takes up exactly 1 out of 3 columns) -->
                        <div class="md:col-span-1 w-full min-w-0">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Evidence</span>
                            <div class="w-full h-48 bg-gray-50 rounded-lg border border-gray-100 overflow-hidden flex items-center justify-center p-2">
                                @if($log->photo_path)
                                    <img src="{{ asset('storage/' . $log->photo_path) }}" 
                                         alt="Workspace Evidence" 
                                         class="max-w-full max-h-full object-contain rounded shadow-sm cursor-pointer hover:scale-[1.02] transition-transform duration-200"
                                         onclick="window.open(this.src, '_blank')">
                                @else
                                    <div class="text-center p-4 flex flex-col items-center justify-center">
                                        <span class="material-symbols-outlined text-gray-300 text-2xl">image_not_supported</span>
                                        <p class="text-[11px] text-gray-400 mt-1">No Photo Uploaded</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>

                    @if($status === 'Rejected' && !empty($log->remarks))
                        <div class="mt-4 mb-2 bg-red-50 border-l-4 border-red-500 p-3 rounded-r-lg w-full min-w-0 overflow-hidden">
                            <span class="text-[10px] font-bold text-red-700 uppercase tracking-wider block">Supervisor Revision Notes</span>
                            <p class="text-sm text-red-900 mt-0.5 break-all whitespace-pre-wrap [word-break:break-all] [overflow-wrap:anywhere]">
                                "{{ $log->remarks }}"
                            </p>
                        </div>
                    @endif

                    @if($status === 'Approved' && !empty($log->remarks))
                        <div class="mt-4 mb-2 bg-purple-50 border-l-4 border-purple-500 p-3 rounded-r-lg w-full min-w-0 overflow-hidden">
                            <span class="text-[10px] font-bold text-purple-700 uppercase tracking-wider block">Supervisor Review Remarks</span>
                            <p class="text-sm text-purple-900 mt-0.5 break-all whitespace-pre-wrap [word-break:break-all] [overflow-wrap:anywhere]">
                                "{{ $log->remarks }}"
                            </p>
                        </div>
                    @endif

                    <!-- Card Footer Actions -->
                    <div class="border-t border-outline/10 pt-4 mt-6">
                        <div class="flex flex-col gap-4">
                            @if($status === 'Pending')
                                <div class="mt-4">
                                    <textarea name="remarks" 
                                              id="remarks-{{ $log->id }}"
                                              form="reject-form-{{ $log->id }}"
                                              rows="4"
                                              maxlength="50000"
                                              required
                                              class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:border-purple-500 transition-colors duration-150 resize-y"
                                              placeholder="Add remarks or feedback for the intern (Required for rejection...)"></textarea>
                                </div>
                                
                                <div class="flex justify-end gap-3 items-center flex-wrap">
                                    <form id="reject-form-{{$log->id}}" method="POST" action="{{ route('supervisor.logs.reject', $log) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-1.5 px-4 py-2 md:py-2.5 rounded-lg text-xs md:text-sm font-bold text-error border border-error/30 hover:bg-error/5 hover:border-error transition-all active:scale-95 bg-white shadow-sm">
                                            <span class="material-symbols-outlined text-[16px]">undo</span> Reject & Request Revision
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('supervisor.logs.approve', $log) }}" class="inline" onsubmit="document.getElementById('hidden-remarks-{{ $log->id }}').value = document.getElementById('remarks-{{ $log->id }}').value;">
                                        @csrf
                                        <input type="hidden" name="remarks" id="hidden-remarks-{{ $log->id }}" value="">
                                        <button type="submit" class="flex items-center gap-1.5 bg-primary text-white px-5 py-2 md:py-2.5 rounded-lg text-xs md:text-sm font-bold shadow hover:bg-primary/95 transition-all active:scale-95 hover:shadow-md">
                                            <span class="material-symbols-outlined text-[16px]">check_circle</span> Verify & Approve
                                        </button>
                                    </form>
                                </div>
                            @elseif($status === 'Approved')
                                <div class="flex justify-between items-center bg-green-50 px-4 py-3 rounded-lg border border-green-100">
                                    <span class="text-sm font-medium text-green-800">This log was approved.</span>
                                    <span class="text-xs font-bold text-green-700 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px]">check_circle</span> {{ $log->updated_at->format('M d, Y h:i A') }}
                                    </span>
                                </div>
                            @elseif($status === 'Rejected')
                                <div class="flex justify-between items-center bg-red-50 px-4 py-3 rounded-lg border border-red-100">
                                    <span class="text-sm font-medium text-red-800">This log was rejected/requires revision.</span>
                                    <span class="text-xs font-bold text-red-700 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px]">cancel</span> {{ $log->updated_at->format('M d, Y h:i A') }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <span class="material-symbols-outlined text-6xl text-outline/30 mb-4">fact_check</span>
                    <h3 class="text-xl font-bold font-headline text-on-surface mb-2">No Entries Found</h3>
                    <p class="text-on-surface/60 font-medium">There are no log entries matching the '{{ $status }}' status at this time.</p>
                </div>
                @endforelse

            </div>
            
            <div class="mt-8">
                {{ $logs->appends(['status' => $status])->links() }}
            </div>

        </div>
    </main>
</body>
</html>
