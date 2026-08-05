<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>OJT Portal | Intern Leaderboard</title>
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

    <!-- Sidebar overlay for mobile -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-40 hidden lg:hidden" onclick="closeSidebar()"></div>

    <!-- TOP NAVIGATION -->
    <header class="fixed top-0 lg:left-64 left-0 right-0 z-40 bg-[#fff7fd] border-b border-[#cec3d0]/15">
        <div class="flex justify-between items-center px-4 md:px-8 py-4 w-full">
            <div class="flex items-center gap-8">
                <button id="sidebar-toggle" class="lg:hidden p-2.5 min-w-[44px] min-h-[44px] flex items-center justify-center text-primary rounded outline-none hover:bg-surface-container" aria-label="Toggle menu">
                    <span class="material-symbols-outlined">menu</span>
                </button>
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
    <main class="lg:ml-64 ml-0 pt-24 px-4 sm:px-6 lg:px-8 pb-12 w-full max-w-7xl mx-auto">
        <div class="mb-8 flex items-center gap-4">
            <a href="{{ route('supervisor.dashboard') }}" class="p-2 hover:bg-surface-container rounded-full text-on-surface/50 transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h1 class="text-4xl font-extrabold font-headline text-primary tracking-tight">Intern Leaderboard</h1>
                <p class="text-on-surface/60 font-medium mt-1">Ranking of all actively assigned interns based on approved total hours.</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="w-full overflow-x-auto -mx-4 sm:mx-0 min-w-full inline-block align-middle">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/70 border-b border-gray-100">
                            <th class="p-4 text-center text-xs font-semibold text-gray-400 uppercase tracking-wider w-[70px]">Rank</th>
                            <th class="p-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Intern</th>
                            <th class="p-4 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden md:table-cell">Course Track</th>
                            <th class="p-4 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Approved Hours</th>
                        </tr>
                    </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($leaderboard as $index => $intern)
                        <tr class="hover:bg-gray-50/40 transition-colors">
                            <td class="p-4 font-mono font-bold text-center text-sm">
                                @if($index === 0)
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-100 text-amber-800 text-xs">🥇</span>
                                @elseif($index === 1)
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-100 text-slate-800 text-xs">🥈</span>
                                @elseif($index === 2)
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-orange-100 text-orange-800 text-xs">🥉</span>
                                @else
                                    <span class="text-gray-400 text-xs">#{{ $index + 1 }}</span>
                                @endif
                            </td>

                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-purple-50 text-purple-700 border border-purple-100 flex items-center justify-center font-bold text-xs uppercase">
                                        {{ substr($intern->user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-900 block text-sm">{{ $intern->user->name }}</span>
                                        <span class="text-[10px] text-gray-400 font-mono">{{ $intern->student_id_number }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="p-4 text-xs text-gray-600 font-medium capitalize hidden md:table-cell">
                                {{ $intern->course ?? $intern->course_major ?? 'Computing Track' }}
                            </td>

                            <td class="p-4 text-right font-mono text-sm font-bold text-purple-950">
                                {{ number_format($intern->approved_hours, 2) }} hrs
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-gray-400 italic text-sm">No interns are currently logging progress for your industry branch.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </main>

    <script>
        // Sidebar Toggling Code
        const sidebarEl = document.getElementById('sidebar');
        const overlayEl = document.getElementById('sidebar-overlay');
        const toggleBtnEl = document.getElementById('sidebar-toggle');

        function openSidebar() {
            sidebarEl.classList.remove('-translate-x-full');
            overlayEl.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
        function closeSidebar() {
            sidebarEl.classList.add('-translate-x-full');
            overlayEl.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
        toggleBtnEl?.addEventListener('click', () => {
            sidebarEl.classList.contains('-translate-x-full') ? openSidebar() : closeSidebar();
        });
    </script>
</body>
</html>
