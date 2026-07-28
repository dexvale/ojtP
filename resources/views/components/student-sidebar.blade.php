<aside id="sidebar" class="fixed left-0 top-0 h-screen w-64 z-40 bg-primary flex flex-col py-6 shadow-2xl pt-28 -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
    <!-- Brand -->
    <div class="px-6 mb-8 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-white/10 border border-white/15 flex items-center justify-center flex-shrink-0">
             <!-- <span class="material-symbols-outlined text-white text-xl" style="font-variation-settings:'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 24;">school</span> -->
            <img alt="University Logo" class="h-8 w-8 object-contain" src="{{ asset('images/logo.png') }}" />
        </div>
        <div>
            <h2 class="font-headline text-xl text-white leading-tight">OJT Portal</h2>
            <p class="text-[10px] uppercase tracking-widest text-white/50 font-medium">Academic Editorial</p>
        </div>
    </div>

    <!-- Nav Links -->
    <nav class="flex-grow font-['Public_Sans'] text-sm flex flex-col mt-4">

    <a href="{{ route('student.dashboard') }}" 
       class="mx-2 my-1 px-4 py-3 flex items-center gap-3 rounded-lg transition-all duration-200 
              {{ request()->routeIs('student.dashboard') ? 'bg-white/10 text-white font-semibold' : 'text-white/60 hover:text-white hover:bg-white/5 font-medium' }}">
        <span class="material-symbols-outlined">dashboard</span>
        Dashboard
    </a>

    <a href="{{ route('student.logs.index') }}" 
       class="mx-2 my-1 px-4 py-3 flex items-center gap-3 rounded-lg transition-all duration-200 
              {{ request()->routeIs('student.logs.index') ? 'bg-white/10 text-white font-semibold' : 'text-white/60 hover:text-white hover:bg-white/5 font-medium' }}">
        <span class="material-symbols-outlined">description</span>
        Internship Logs
    </a>

    <a href="{{ route('student.requirements') }}" 
       class="mx-2 my-1 px-4 py-3 flex items-center gap-3 rounded-lg transition-all duration-200 
              {{ request()->routeIs('student.requirements*') ? 'bg-white/10 text-white font-semibold' : 'text-white/60 hover:text-white hover:bg-white/5 font-medium' }}">
        <span class="material-symbols-outlined">assignment</span>
        OJT Requirements
    </a>

    <a href="{{ route('student.profile') }}" 
       class="mx-2 my-1 px-4 py-3 flex items-center gap-3 rounded-lg transition-all duration-200 
              {{ request()->routeIs('student.profile') ? 'bg-white/10 text-white font-semibold' : 'text-white/60 hover:text-white hover:bg-white/5 font-medium' }}">
        <span class="material-symbols-outlined">person</span>
        Profile
    </a>

</nav>

    <!-- Bottom actions -->
    <div class="pt-4 border-t border-white/10 font-body font-medium text-sm px-2">
        <a href="#" class="text-white/60 hover:text-white hover:bg-white/5 mx-0 my-1 px-4 py-3 flex items-center gap-3 rounded-lg">
            <span class="material-symbols-outlined text-xl">help</span>
            Help Center
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left text-error hover:bg-error/10 mx-0 my-1 px-4 py-3 flex items-center gap-3 rounded-lg transition-colors">
                <span class="material-symbols-outlined text-xl">logout</span>
                Logout
            </button>
        </form>
    </div>
</aside>
