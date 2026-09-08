<aside id="sidebar" class="fixed left-0 top-0 h-full h-[100dvh] max-h-screen w-64 z-[60] bg-primary flex flex-col justify-between shadow-2xl overscroll-contain -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
    <!-- Brand & Mobile Close -->
    <div class="px-6 pt-6 pb-2 mb-2 flex-shrink-0 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-white/10 border border-white/15 flex items-center justify-center flex-shrink-0">
                <img alt="University Logo" class="h-8 w-8 object-contain" src="{{ asset('images/BISU-Logo-1-150x150.png.webp') }}" onerror="this.src='https://ui-avatars.com/api/?name=OJT&background=3a0ca3&color=fff'" />
            </div>
            <div>
                <h2 class="font-headline text-xl text-white leading-tight">OJT Portal</h2>
                <p class="text-[10px] uppercase tracking-widest text-white/50 font-medium">Academic Editorial</p>
            </div>
        </div>
        <button type="button" onclick="closeSidebar()" class="lg:hidden p-1.5 text-white/70 hover:text-white rounded-lg hover:bg-white/10 transition-colors" aria-label="Close sidebar">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>

    <!-- Nav Links -->
    <nav class="flex-1 min-h-0 overflow-y-auto px-2 py-1 space-y-1 font-['Public_Sans'] text-sm">
        <a href="{{ route('student.dashboard') }}" 
           class="mx-1 my-0.5 px-4 py-2.5 flex items-center gap-3 rounded-lg transition-all duration-200 
                  {{ request()->routeIs('student.dashboard') ? 'bg-white/10 text-white font-semibold' : 'text-white/60 hover:text-white hover:bg-white/5 font-medium' }}">
            <span class="material-symbols-outlined">dashboard</span>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('student.logs.index') }}" 
           class="mx-1 my-0.5 px-4 py-2.5 flex items-center gap-3 rounded-lg transition-all duration-200 
                  {{ request()->routeIs('student.logs.index') ? 'bg-white/10 text-white font-semibold' : 'text-white/60 hover:text-white hover:bg-white/5 font-medium' }}">
            <span class="material-symbols-outlined">description</span>
            <span>Internship Logs</span>
        </a>

        <a href="{{ route('student.placement') }}" 
           class="mx-1 my-0.5 px-4 py-2.5 flex items-center gap-3 rounded-lg transition-all duration-200 
                  {{ request()->routeIs('student.placement*') ? 'bg-white/10 text-white font-semibold' : 'text-white/60 hover:text-white hover:bg-white/5 font-medium' }}">
            <span class="material-symbols-outlined">corporate_fare</span>
            <span>Company Placement</span>
        </a>

        <a href="{{ route('student.requirements') }}" 
           class="mx-1 my-0.5 px-4 py-2.5 flex items-center gap-3 rounded-lg transition-all duration-200 
                  {{ request()->routeIs('student.requirements*') ? 'bg-white/10 text-white font-semibold' : 'text-white/60 hover:text-white hover:bg-white/5 font-medium' }}">
            <span class="material-symbols-outlined">assignment</span>
            <span>OJT Requirements</span>
        </a>

        <a href="{{ route('student.grading-sheet') }}" target="_blank"
           class="mx-1 my-0.5 px-4 py-2.5 flex items-center gap-3 rounded-lg transition-all duration-200 
                  {{ request()->routeIs('student.grading-sheet') ? 'bg-white/10 text-white font-semibold' : 'text-white/60 hover:text-white hover:bg-white/5 font-medium' }}">
            <span class="material-symbols-outlined">grade</span>
            <span>BISU Grading Sheet</span>
        </a>

        <a href="{{ route('student.profile') }}" 
           class="mx-1 my-0.5 px-4 py-2.5 flex items-center gap-3 rounded-lg transition-all duration-200 
                  {{ request()->routeIs('student.profile') ? 'bg-white/10 text-white font-semibold' : 'text-white/60 hover:text-white hover:bg-white/5 font-medium' }}">
            <span class="material-symbols-outlined">person</span>
            <span>Profile</span>
        </a>
    </nav>

    <!-- Bottom actions -->
    <div class="flex-shrink-0 p-4 border-t border-white/10 bg-primary pb-[calc(1rem+env(safe-area-inset-bottom,0px))]">
        <!-- Mobile Student Profile snippet -->
        <div class="flex items-center gap-3 px-3 py-2 mb-2 rounded-xl bg-white/5 border border-white/10 lg:hidden">
            <img alt="User avatar" class="w-8 h-8 rounded-full object-cover ring-1 ring-white/20" src="{{ auth()->user()->studentProfile?->profile_photo_url ?? auth()->user()->avatar_url }}">
            <div class="min-w-0 flex-1">
                <p class="text-xs font-bold text-white truncate">{{ auth()->user()->display_name }}</p>
                <p class="text-[10px] uppercase tracking-wider text-white/70 font-semibold truncate">{{ auth()->user()->studentProfile?->student_id_number ?? 'Student Intern' }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left text-white px-4 py-2.5 flex items-center gap-3 text-sm font-semibold rounded-xl bg-white/10 hover:bg-white/20 active:bg-white/25 transition-all duration-200 cursor-pointer">
                <span class="material-symbols-outlined text-rose-300">logout</span>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>
