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
    <aside class="fixed left-0 top-0 h-screen w-64 z-50 bg-[#300050] text-[#ffffff] flex flex-col py-6 gap-2 shadow-[32px_0_64px_rgba(30,26,31,0.05)] hidden md:flex">
        <div class="px-6 mb-8 flex items-center gap-3">
            <img alt="University Logo" class="h-8 w-8 object-contain" src="{{ asset('images/logo.png') }}" />
            <div>
                <h1 class="font-headline text-xl text-[#ffffff]">OJT Portal</h1>
                <p class="text-[10px] uppercase tracking-widest text-[#faf1f8]/70">Industry Partner</p>
            </div>
        </div>
        <nav class="flex-1">
            <a href="{{ route('supervisor.dashboard') }}" class="mx-2 my-1 px-4 py-3 flex items-center gap-3 text-sm font-medium rounded-lg text-[#faf1f8]/70 hover:text-[#ffffff] hover:bg-[#ffffff]/5 transition-all duration-300">
                <span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('supervisor.attendance') }}" class="mx-2 my-1 px-4 py-3 flex items-center gap-3 text-sm font-medium rounded-lg text-[#faf1f8]/70 hover:text-[#ffffff] hover:bg-[#ffffff]/5 transition-all duration-300">
                <span class="material-symbols-outlined" data-icon="calendar_month">calendar_month</span>
                <span>Intern Attendance</span>
            </a>
            <a href="{{ route('supervisor.approvals') }}" class="mx-2 my-1 px-4 py-3 flex items-center gap-3 text-sm font-medium rounded-lg bg-[#faf1f8]/10 text-[#ffffff] translate-x-1 transition-all duration-300">
                <span class="material-symbols-outlined" data-icon="fact_check">fact_check</span>
                <span>Pending Approvals</span>
            </a>
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

                    <button class="flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-lg text-sm font-bold shadow-md hover:bg-primary/90 transition-all active:scale-95 whitespace-nowrap">
                        <span class="material-symbols-outlined text-[18px]">done_all</span>
                        Batch Approve All (5)
                    </button>
                </div>
            </div>

            <!-- Review Queue List -->
            <div class="flex flex-col gap-6">

                <!-- Submission Card 1 -->
                <div class="bg-surface-container rounded-2xl shadow-sm border border-outline/20 p-6 transition-all hover:bg-white relative">
                    <!-- Global Actions responsive (shows on mobile above the card list if needed, or keeping it hidden). I will skip mobile sort for brevity, but make card responsive -->
                    <!-- Card Header -->
                    <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-sm border-2 border-surface border-primary/20 shadow-sm">MC</div>
                            <div>
                                <h3 class="font-bold text-base text-on-surface">Marcus Chen</h3>
                                <p class="text-xs font-semibold text-on-surface/50">BS Computer Science • Yesterday, 5:00 PM</p>
                            </div>
                        </div>
                        <div class="bg-surface border border-outline/30 px-3 py-1.5 rounded-lg shadow-sm">
                            <span class="text-xs font-bold text-on-surface/70 tracking-wide">Hours Logged: <span class="text-primary font-black ml-1 text-sm font-headline">8.0 hrs</span></span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
                        <!-- Task Description -->
                        <div class="md:col-span-2 relative">
                            <div class="absolute w-1 h-full bg-primary/10 left-0 rounded-full top-0"></div>
                            <div class="pl-4">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-on-surface/40 mb-2 block">Task Description</span>
                                <p class="text-sm font-medium italic text-on-surface/80 leading-relaxed">
                                    "Assisted in troubleshooting the network server and resetting the main router for the marketing floor. I also documented the IP configurations for the new developer workstations and ran the scheduled backup script."
                                </p>
                            </div>
                        </div>
                        
                        <!-- Evidence -->
                        <div class="md:col-span-1">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-on-surface/40 mb-2 block">Evidence</span>
                            <div class="relative group cursor-pointer overflow-hidden rounded-xl border border-outline/20 aspect-video md:aspect-[4/3] bg-surface-container-high w-full flex items-center justify-center">
                                <!-- Dummy image placeholder mimicking a code screen -->
                                <div class="w-full h-full bg-[#1e1e2f] p-3 text-[8px] sm:text-[10px] md:text-[8px] text-[#10b981] font-mono whitespace-pre opacity-90 overflow-hidden break-words leading-tight">
<span class="text-primary/70">root@server:~#</span> ping 192.168.1.1
PING 192.168.1.1 56 data bytes
64 bytes from 192.168.1.1: icmp_seq=1 ttl=64
...
<span class="text-primary/70">root@server:~#</span> sudo backup.sh
Starting backup...
Backup complete.
                                </div>

                                <div class="absolute inset-0 bg-primary/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                                    <div class="bg-surface text-primary p-2 rounded-full shadow border border-outline/20 hover:scale-110 transition-transform">
                                        <span class="material-symbols-outlined text-[20px]">zoom_in</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer Actions -->
                    <div class="border-t border-outline/10 pt-4 mt-6">
                        <div class="flex flex-col gap-4">
                            <input type="text" class="w-full bg-surface-container-high/50 border border-outline/20 rounded-lg px-4 py-2.5 text-sm font-medium text-on-surface focus:ring-primary focus:border-primary placeholder-on-surface/40 transition-colors" placeholder="Add remarks or feedback for the intern (Optional)...">
                            
                            <div class="flex justify-end gap-3 items-center flex-wrap">
                                <button class="flex items-center gap-1.5 px-4 py-2 md:py-2.5 rounded-lg text-xs md:text-sm font-bold text-error border border-error/30 hover:bg-error/5 hover:border-error transition-all active:scale-95 bg-white shadow-sm">
                                    <span class="material-symbols-outlined text-[16px]">undo</span> Reject & Request Revision
                                </button>
                                <button class="flex items-center gap-1.5 bg-primary text-white px-5 py-2 md:py-2.5 rounded-lg text-xs md:text-sm font-bold shadow hover:bg-primary/95 transition-all active:scale-95 hover:shadow-md">
                                    <span class="material-symbols-outlined text-[16px]">check_circle</span> Verify & Approve
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submission Card 2 -->
                <div class="bg-surface-container rounded-2xl shadow-sm border border-outline/20 p-6 transition-all hover:bg-white relative">
                    <!-- Card Header -->
                    <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-secondary/10 text-secondary flex items-center justify-center font-bold text-sm border-2 border-surface border-secondary/20 shadow-sm">SJ</div>
                            <div>
                                <h3 class="font-bold text-base text-on-surface">Sarah Jenkins</h3>
                                <p class="text-xs font-semibold text-on-surface/50">BS Business Admin • Yesterday, 4:45 PM</p>
                            </div>
                        </div>
                        <div class="bg-surface border border-outline/30 px-3 py-1.5 rounded-lg shadow-sm">
                            <span class="text-xs font-bold text-on-surface/70 tracking-wide">Hours Logged: <span class="text-primary font-black ml-1 text-sm font-headline">8.0 hrs</span></span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
                        <!-- Task Description -->
                        <div class="md:col-span-2 relative">
                            <div class="absolute w-1 h-full bg-secondary/20 left-0 rounded-full top-0"></div>
                            <div class="pl-4">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-on-surface/40 mb-2 block">Task Description</span>
                                <p class="text-sm font-medium italic text-on-surface/80 leading-relaxed">
                                    "Completed data entry for the Q3 partner reports and reorganized the client filing system on Google Drive. I effectively reduced the file clutter by 30% and prepared the draft for the weekly progress meeting."
                                </p>
                            </div>
                        </div>
                        
                        <!-- Evidence -->
                        <div class="md:col-span-1">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-on-surface/40 mb-2 block">Evidence</span>
                            <div class="relative group cursor-pointer overflow-hidden rounded-xl border border-outline/20 aspect-video md:aspect-[4/3] bg-surface flex items-center justify-center">
                                <!-- Dummy image placeholder mimicking a spreadsheet/report -->
                                <div class="w-full h-full flex flex-col items-center justify-center text-outline gap-1 border-4 border-white bg-[#e3f2fd]">
                                    <span class="material-symbols-outlined text-[40px] text-success/40">table_chart</span>
                                    <span class="text-[8px] font-bold text-[#1e88e5]">Q3_REPORT.XLSX</span>
                                </div>
                                <div class="absolute inset-0 bg-primary/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                                    <div class="bg-surface text-primary p-2 rounded-full shadow border border-outline/20 hover:scale-110 transition-transform">
                                        <span class="material-symbols-outlined text-[20px]">zoom_in</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer Actions -->
                    <div class="border-t border-outline/10 pt-4 mt-6">
                        <div class="flex flex-col gap-4">
                            <input type="text" class="w-full bg-surface-container-high/50 border border-outline/20 rounded-lg px-4 py-2.5 text-sm font-medium text-on-surface focus:ring-primary focus:border-primary placeholder-on-surface/40 transition-colors" placeholder="Add remarks or feedback for the intern (Optional)...">
                            
                            <div class="flex justify-end gap-3 items-center flex-wrap">
                                <button class="flex items-center gap-1.5 px-4 py-2 md:py-2.5 rounded-lg text-xs md:text-sm font-bold text-error border border-error/30 hover:bg-error/5 hover:border-error transition-all active:scale-95 bg-white shadow-sm">
                                    <span class="material-symbols-outlined text-[16px]">undo</span> Reject & Request Revision
                                </button>
                                <button class="flex items-center gap-1.5 bg-primary text-white px-5 py-2 md:py-2.5 rounded-lg text-xs md:text-sm font-bold shadow hover:bg-primary/95 transition-all active:scale-95 hover:shadow-md">
                                    <span class="material-symbols-outlined text-[16px]">check_circle</span> Verify & Approve
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
            <div class="mt-8 text-center">
                <button class="px-6 py-2 border-2 border-outline/30 text-on-surface/60 font-bold text-xs rounded-full hover:bg-surface-container hover:text-primary transition-colors inline-flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">expand_more</span>
                    Load More Pending Reviews (3)
                </button>
            </div>

        </div>
    </main>
</body>
</html>
