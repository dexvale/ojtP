<!-- ═══════════════════════════════
     MOBILE BOTTOM NAVIGATION (SHARED)
═══════════════════════════════ -->
<nav class="lg:hidden fixed bottom-0 left-0 right-0 w-full bg-white/95 backdrop-blur-md border-t border-surface-variant/20 flex items-center justify-around px-2 py-1.5 z-50 shadow-[0_-4px_20px_rgba(0,0,0,0.05)]">
    {{-- 1. Dashboard / Home --}}
    <a href="{{ route('student.dashboard') }}" 
       class="flex flex-col items-center justify-center flex-1 py-1 transition-all duration-200 {{ request()->routeIs('student.dashboard') ? 'text-primary' : 'text-slate-400 hover:text-slate-600' }}">
        <span class="material-symbols-outlined text-2xl" style="{{ request()->routeIs('student.dashboard') ? "font-variation-settings:'FILL' 1,'wght' 600,'GRAD' 0,'opsz' 24;" : '' }}">dashboard</span>
        <span class="text-[10px] {{ request()->routeIs('student.dashboard') ? 'font-bold' : 'font-medium' }} tracking-tight mt-0.5">Home</span>
    </a>

    {{-- 2. Daily Logs --}}
    <a href="{{ route('student.logs.index') }}" 
       class="flex flex-col items-center justify-center flex-1 py-1 transition-all duration-200 {{ request()->routeIs('student.logs.*') ? 'text-primary' : 'text-slate-400 hover:text-slate-600' }}">
        <span class="material-symbols-outlined text-2xl" style="{{ request()->routeIs('student.logs.*') ? "font-variation-settings:'FILL' 1,'wght' 600,'GRAD' 0,'opsz' 24;" : '' }}">description</span>
        <span class="text-[10px] {{ request()->routeIs('student.logs.*') ? 'font-bold' : 'font-medium' }} tracking-tight mt-0.5">Logs</span>
    </a>

    {{-- 3. Center Elevated Action: Quick Time Log --}}
    <div class="flex flex-col items-center justify-center -mt-6 px-1 relative">
        <a href="{{ route('student.dashboard') }}#shift-form" 
           onclick="if(window.location.pathname.endsWith('/dashboard') || window.location.pathname.endsWith('/dashboard/')) { event.preventDefault(); const el = document.getElementById('shift-form'); if(el) { el.scrollIntoView({behavior:'smooth', block:'center'}); const inp = document.getElementById('am_clock_in') || document.getElementById('pm_clock_in'); if(inp) setTimeout(() => inp.focus(), 350); } }"
           class="w-12 h-12 rounded-full bg-gradient-to-tr from-[#300050] to-purple-700 text-white flex items-center justify-center shadow-lg shadow-purple-900/30 hover:scale-105 active:scale-95 transition-transform ring-4 ring-white"
           title="Log OJT Shift">
            <span class="material-symbols-outlined text-[26px]">add</span>
        </a>
        <span class="text-[9px] font-bold text-primary uppercase tracking-wider mt-1">Log</span>
    </div>

    {{-- 4. Requirements --}}
    <a href="{{ route('student.requirements') }}" 
       class="flex flex-col items-center justify-center flex-1 py-1 transition-all duration-200 {{ request()->routeIs('student.requirements*') ? 'text-primary' : 'text-slate-400 hover:text-slate-600' }}">
        <span class="material-symbols-outlined text-2xl" style="{{ request()->routeIs('student.requirements*') ? "font-variation-settings:'FILL' 1,'wght' 600,'GRAD' 0,'opsz' 24;" : '' }}">assignment</span>
        <span class="text-[10px] {{ request()->routeIs('student.requirements*') ? 'font-bold' : 'font-medium' }} tracking-tight mt-0.5">Tasks</span>
    </a>

    {{-- 5. Profile --}}
    <a href="{{ route('student.profile') }}" 
       class="flex flex-col items-center justify-center flex-1 py-1 transition-all duration-200 {{ request()->routeIs('student.profile*') ? 'text-primary' : 'text-slate-400 hover:text-slate-600' }}">
        <span class="material-symbols-outlined text-2xl" style="{{ request()->routeIs('student.profile*') ? "font-variation-settings:'FILL' 1,'wght' 600,'GRAD' 0,'opsz' 24;" : '' }}">person</span>
        <span class="text-[10px] {{ request()->routeIs('student.profile*') ? 'font-bold' : 'font-medium' }} tracking-tight mt-0.5">Profile</span>
    </a>
</nav>
