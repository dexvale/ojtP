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
                <!-- Notifications & Profile -->
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
    <main class="md:ml-64 pt-24 px-4 md:px-8 pb-12">
        <div class="p-6 max-w-7xl mx-auto">
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
                        <div class="bg-surface-container rounded-2xl border border-outline/30 shadow-sm hover:border-primary/30 hover:shadow-md transition-all duration-300 overflow-hidden flex flex-col justify-between p-6 group">
                            <div>
                                <div class="flex items-start gap-4">
                                    <div class="w-14 h-14 bg-primary/10 text-primary font-bold rounded-xl flex items-center justify-center text-xl flex-shrink-0 border border-primary/20 shadow-inner">
                                        {{ strtoupper(substr($intern->user->name ?? 'I', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0 pt-1">
                                        <h3 class="font-bold text-lg text-on-surface truncate group-hover:text-primary transition-colors">{{ $intern->user->name ?? 'Unknown Student' }}</h3>
                                        <p class="text-xs text-on-surface/60 mt-0.5 font-medium truncate">{{ $intern->course_major ?? 'Computing Student' }}</p>
                                        <p class="text-[11px] text-on-surface/40 mt-1 font-mono font-semibold">ID: {{ $intern->student_id ?? 'N/A' }}</p>
                                    </div>
                                </div>

                                <div class="mt-8">
                                    <div class="flex justify-between items-end mb-2">
                                        <span class="text-xs font-bold text-on-surface/60 uppercase tracking-widest">Verified Progress</span>
                                        <span class="text-primary font-bold text-sm bg-primary/5 px-2 py-0.5 rounded border border-primary/10">{{ number_format($intern->approved_hours ?? 0, 1) }} / 400 hrs</span>
                                    </div>
                                    <div class="w-full bg-outline/20 h-2 rounded-full overflow-hidden">
                                        @php
                                            $percent = min((($intern->approved_hours ?? 0) / 400) * 100, 100);
                                        @endphp
                                        <div class="bg-primary h-full rounded-full transition-all duration-500 relative" style="width: {{ $percent }}%">
                                            <div class="absolute inset-0 bg-white/20 w-full h-full"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 pt-5 border-t border-outline/20 flex items-center justify-between text-xs">
                                <span class="text-on-surface/50 flex items-center gap-1.5 font-mono truncate font-medium">
                                    <span class="material-symbols-outlined text-[16px]">mail</span>
                                    {{ $intern->user->email ?? 'No email' }}
                                </span>
                                <span class="px-2.5 py-1 rounded bg-success/10 text-success border border-success/20 font-bold text-[10px] uppercase tracking-wider flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-success animate-pulse"></span> Active
                                </span>
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
    </main>
</body>
</html>
