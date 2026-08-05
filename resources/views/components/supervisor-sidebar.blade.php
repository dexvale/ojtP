<aside id="sidebar" class="fixed left-0 top-0 h-screen w-64 z-50 bg-[#300050] text-[#ffffff] flex flex-col py-6 gap-2 shadow-[32px_0_64px_rgba(30,26,31,0.05)] -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
    <div class="px-6 mb-8 flex items-center gap-3">
        <img alt="University Logo" class="h-8 w-8 object-contain" src="{{ asset('images/logo.png') }}" />
        <div>
            <h1 class="font-headline text-xl text-[#ffffff]">OJT Portal</h1>
            <p class="text-[10px] uppercase tracking-widest text-[#faf1f8]/70">Industry Partner</p>
        </div>
    </div>
    <nav class="flex-1">
        <a href="{{ route('supervisor.dashboard') }}" class="mx-2 my-1 px-4 py-3 flex items-center gap-3 text-sm font-medium rounded-lg {{ request()->routeIs('supervisor.dashboard') ? 'bg-[#faf1f8]/10 text-[#ffffff] translate-x-1' : 'text-[#faf1f8]/70 hover:text-[#ffffff] hover:bg-[#ffffff]/5' }} transition-all duration-300">
            <span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('supervisor.attendance') }}" class="mx-2 my-1 px-4 py-3 flex items-center gap-3 text-sm font-medium rounded-lg {{ request()->routeIs('supervisor.attendance') ? 'bg-[#faf1f8]/10 text-[#ffffff] translate-x-1' : 'text-[#faf1f8]/70 hover:text-[#ffffff] hover:bg-[#ffffff]/5' }} transition-all duration-300">
            <span class="material-symbols-outlined" data-icon="calendar_month">calendar_month</span>
            <span>Intern Attendance</span>
        </a>
        <a href="{{ route('supervisor.interns.index') }}" class="mx-2 my-1 px-4 py-3 flex items-center gap-3 text-sm font-medium rounded-lg {{ request()->routeIs('supervisor.interns.*') ? 'bg-[#faf1f8]/10 text-[#ffffff] translate-x-1' : 'text-[#faf1f8]/70 hover:text-[#ffffff] hover:bg-[#ffffff]/5' }} transition-all duration-300">
            <span class="material-symbols-outlined" data-icon="group">group</span>
            <span>My Interns</span>
        </a>
        <a href="{{ route('supervisor.approvals') }}" class="mx-2 my-1 px-4 py-3 flex items-center gap-3 text-sm font-medium rounded-lg {{ request()->routeIs('supervisor.approvals') ? 'bg-[#faf1f8]/10 text-[#ffffff] translate-x-1' : 'text-[#faf1f8]/70 hover:text-[#ffffff] hover:bg-[#ffffff]/5' }} transition-all duration-300">
            <span class="material-symbols-outlined" data-icon="fact_check">fact_check</span>
            <span>Pending Approvals</span>
        </a>
        <!-- Simplified sidebar for supervisor just for layout context -->
    </nav>
    <div class="mt-auto pt-4 border-t border-[#ffffff]/10">
        <a class="text-[#faf1f8]/70 hover:text-[#ffffff] mx-2 my-1 px-4 py-3 flex items-center gap-3 text-sm font-medium hover:bg-[#ffffff]/5 transition-all duration-300" href="#">
            <span class="material-symbols-outlined" data-icon="help">help</span>
            <span>Support</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left text-[#faf1f8]/70 hover:text-[#ffffff] mx-2 my-1 px-4 py-3 flex items-center gap-3 text-sm font-medium hover:bg-[#ffffff]/5 transition-all duration-300">
                <span class="material-symbols-outlined" data-icon="logout">logout</span>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>
