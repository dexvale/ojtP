<aside id="sidebar" class="fixed left-0 top-0 h-full h-[100dvh] max-h-screen w-64 z-50 bg-[#300050] text-[#ffffff] flex flex-col justify-between shadow-[32px_0_64px_rgba(30,26,31,0.05)] -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out overscroll-contain">
    <div class="px-6 pt-6 pb-2 mb-2 flex-shrink-0 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <img alt="University Logo" class="h-8 w-8 object-contain" src="{{ asset('images/BISU-Logo-1-150x150.png.webp') }}" onerror="this.src='https://ui-avatars.com/api/?name=OJT&background=3a0ca3&color=fff'" />
            <div>
                <h1 class="font-headline text-xl text-[#ffffff]">OJT Portal</h1>
                <p class="text-[10px] uppercase tracking-widest text-[#faf1f8]/70">Industry Partner</p>
            </div>
        </div>
        <button type="button" onclick="closeSidebar()" class="lg:hidden p-1.5 text-[#faf1f8]/70 hover:text-white rounded-lg hover:bg-white/10 transition-colors" aria-label="Close sidebar">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>
    <nav class="flex-1 min-h-0 overflow-y-auto px-2 py-1 space-y-1">
        <a href="{{ route('supervisor.dashboard') }}" class="mx-1 my-0.5 px-4 py-2.5 flex items-center gap-3 text-sm font-medium rounded-lg {{ request()->routeIs('supervisor.dashboard') ? 'bg-[#faf1f8]/10 text-[#ffffff] translate-x-1 font-semibold' : 'text-[#faf1f8]/70 hover:text-[#ffffff] hover:bg-[#ffffff]/5' }} transition-all duration-300">
            <span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('supervisor.attendance') }}" class="mx-1 my-0.5 px-4 py-2.5 flex items-center gap-3 text-sm font-medium rounded-lg {{ request()->routeIs('supervisor.attendance') ? 'bg-[#faf1f8]/10 text-[#ffffff] translate-x-1 font-semibold' : 'text-[#faf1f8]/70 hover:text-[#ffffff] hover:bg-[#ffffff]/5' }} transition-all duration-300">
            <span class="material-symbols-outlined" data-icon="calendar_month">calendar_month</span>
            <span>Intern Attendance</span>
        </a>
        <a href="{{ route('supervisor.interns.index') }}" class="mx-1 my-0.5 px-4 py-2.5 flex items-center gap-3 text-sm font-medium rounded-lg {{ request()->routeIs('supervisor.interns.*') ? 'bg-[#faf1f8]/10 text-[#ffffff] translate-x-1 font-semibold' : 'text-[#faf1f8]/70 hover:text-[#ffffff] hover:bg-[#ffffff]/5' }} transition-all duration-300">
            <span class="material-symbols-outlined" data-icon="group">group</span>
            <span>My Interns</span>
        </a>
        <a href="{{ route('supervisor.approvals') }}" class="mx-1 my-0.5 px-4 py-2.5 flex items-center gap-3 text-sm font-medium rounded-lg {{ request()->routeIs('supervisor.approvals') ? 'bg-[#faf1f8]/10 text-[#ffffff] translate-x-1 font-semibold' : 'text-[#faf1f8]/70 hover:text-[#ffffff] hover:bg-[#ffffff]/5' }} transition-all duration-300">
            <span class="material-symbols-outlined" data-icon="fact_check">fact_check</span>
            <span>Pending Approvals</span>
        </a>
        <a href="{{ route('account.profile') }}" class="mx-1 my-0.5 px-4 py-2.5 flex items-center gap-3 text-sm font-medium rounded-lg {{ request()->routeIs('account.profile*') ? 'bg-[#faf1f8]/10 text-[#ffffff] translate-x-1 font-semibold' : 'text-[#faf1f8]/70 hover:text-[#ffffff] hover:bg-[#ffffff]/5' }} transition-all duration-300">
            <span class="material-symbols-outlined" data-icon="manage_accounts">manage_accounts</span>
            <span>Account Profile</span>
        </a>
    </nav>
    <div class="flex-shrink-0 p-4 border-t border-[#ffffff]/10 bg-[#300050] pb-[calc(1rem+env(safe-area-inset-bottom,0px))]">
        <!-- Mobile Supervisor Profile snippet -->
        <a href="{{ route('account.profile') }}" class="flex items-center gap-3 px-3 py-2 mb-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 lg:hidden transition-colors">
            <img alt="User avatar" class="w-8 h-8 rounded-full object-cover ring-1 ring-white/20" src="{{ auth()->user()->avatar_url }}">
            <div class="min-w-0 flex-1">
                <p class="text-xs font-bold text-white truncate">{{ auth()->user()->display_name }}</p>
                <p class="text-[10px] uppercase tracking-wider text-[#faf1f8]/70 font-semibold truncate">{{ auth()->user()->company->name ?? 'Industry Partner' }}</p>
            </div>
            <span class="material-symbols-outlined text-xs text-white/50">chevron_right</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left text-[#faf1f8] hover:text-white px-4 py-2.5 flex items-center gap-3 text-sm font-semibold rounded-xl bg-white/10 hover:bg-white/20 active:bg-white/25 transition-all duration-200 cursor-pointer">
                <span class="material-symbols-outlined text-rose-300" data-icon="logout">logout</span>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>
