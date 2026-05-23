    <!-- SideNavBar -->
    <aside
        class="fixed left-0 top-0 h-screen w-64 z-50 bg-[#300050] text-[#ffffff] flex flex-col py-6 gap-2 shadow-[32px_0_64px_rgba(30,26,31,0.05)]">
        <div class="px-6 mb-8 flex items-center gap-3">
            <img alt="University Logo" class="h-8 w-8 object-contain" src="{{ asset('images/logo.png') }}" onerror="this.src='https://ui-avatars.com/api/?name=OJT&background=3a0ca3&color=fff'" />
            <div>
                <h1 class="font-headline text-xl text-[#ffffff]">OJT Portal</h1>
                <p class="text-[10px] uppercase tracking-widest text-[#faf1f8]/70">Academic Editorial</p>
            </div>
        </div>
        <nav class="flex-1">
            <a href="{{ route('coordinator.dashboard') }}" 
               class="mx-2 my-1 px-4 py-3 flex items-center gap-3 text-sm font-medium rounded-lg transition-all duration-300 {{ request()->routeIs('coordinator.dashboard') ? 'bg-[#faf1f8]/10 text-[#ffffff] translate-x-1' : 'text-[#faf1f8]/70 hover:text-[#ffffff] hover:bg-[#ffffff]/5' }}">
                <span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('coordinator.students') }}" 
               class="mx-2 my-1 px-4 py-3 flex items-center gap-3 text-sm font-medium rounded-lg transition-all duration-300 {{ request()->routeIs('coordinator.students') ? 'bg-[#faf1f8]/10 text-[#ffffff] translate-x-1' : 'text-[#faf1f8]/70 hover:text-[#ffffff] hover:bg-[#ffffff]/5' }}">
                <span class="material-symbols-outlined" data-icon="groups">groups</span>
                <span>Student List</span>
            </a>
            <a href="{{ route('coordinator.companies') }}" 
               class="mx-2 my-1 px-4 py-3 flex items-center gap-3 text-sm font-medium rounded-lg transition-all duration-300 {{ request()->routeIs('coordinator.companies') ? 'bg-[#faf1f8]/10 text-[#ffffff] translate-x-1' : 'text-[#faf1f8]/70 hover:text-[#ffffff] hover:bg-[#ffffff]/5' }}">
                <span class="material-symbols-outlined" data-icon="business">business</span>
                <span>Company Directory</span>
            </a>
            <a href="{{ route('courses.index') }}" 
               class="mx-2 my-1 px-4 py-3 flex items-center gap-3 text-sm font-medium rounded-lg transition-all duration-300 {{ request()->routeIs('courses.*') ? 'bg-[#faf1f8]/10 text-[#ffffff] translate-x-1' : 'text-[#faf1f8]/70 hover:text-[#ffffff] hover:bg-[#ffffff]/5' }}">
                <span class="material-symbols-outlined" data-icon="settings">settings</span>
                <span>Course Settings</span>
            </a>
            <a href="{{ route('coordinator.reports') }}" 
               class="mx-2 my-1 px-4 py-3 flex items-center gap-3 text-sm font-medium rounded-lg transition-all duration-300 {{ request()->routeIs('coordinator.reports') ? 'bg-[#faf1f8]/10 text-[#ffffff] translate-x-1' : 'text-[#faf1f8]/70 hover:text-[#ffffff] hover:bg-[#ffffff]/5' }}">
                <span class="material-symbols-outlined" data-icon="assessment">assessment</span>
                <span>Reports</span>
            </a>
        </nav>
        <div class="mt-auto pt-4 border-t border-[#ffffff]/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left text-[#faf1f8]/70 hover:text-[#ffffff] mx-2 my-1 px-4 py-3 flex items-center gap-3 text-sm font-medium hover:bg-[#ffffff]/5 transition-all duration-300">
                    <span class="material-symbols-outlined" data-icon="logout">logout</span>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>
