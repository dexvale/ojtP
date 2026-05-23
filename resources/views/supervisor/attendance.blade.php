<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>OJT Portal | Supervisor Attendance</title>
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
    <main class="md:ml-64 pt-24 px-4 md:px-8 pb-12">
        
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
            <div>
                <h1 class="text-4xl font-extrabold font-headline text-primary tracking-tight">Intern Attendance Tracker</h1>
                <p class="text-on-surface/60 font-medium mt-1">Monitor daily time logs and weekly attendance trends.</p>
            </div>
            <button class="flex items-center gap-2 px-5 py-2.5 border-2 border-primary text-primary bg-transparent rounded-lg font-bold text-sm hover:bg-primary hover:text-white transition-all shadow-sm active:scale-95">
                <span class="material-symbols-outlined text-[20px]">download</span>
                Download Weekly Report
            </button>
        </div>

        <div class="flex flex-col gap-8">
            
            <!-- SECTION A: Live Status Table -->
            <section class="bg-white p-6 rounded-2xl shadow-sm border border-outline/30">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold font-headline text-on-surface tracking-tight flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">schedule</span> Today's Live Status
                    </h2>
                    <span class="text-sm font-bold text-on-surface/50 bg-surface-container-high px-4 py-1.5 rounded-lg border border-outline/20">Oct 24, 2024</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left min-w-[800px]">
                        <thead>
                            <tr class="border-b border-outline/20">
                                <th class="pb-3 text-[10px] font-bold uppercase tracking-widest text-on-surface/50 px-4">Intern</th>
                                <th class="pb-3 text-[10px] font-bold uppercase tracking-widest text-on-surface/50 px-4">Course/Dept</th>
                                <th class="pb-3 text-[10px] font-bold uppercase tracking-widest text-on-surface/50 px-4">Time In</th>
                                <th class="pb-3 text-[10px] font-bold uppercase tracking-widest text-on-surface/50 px-4">Time Out</th>
                                <th class="pb-3 text-[10px] font-bold uppercase tracking-widest text-on-surface/50 px-4">Status</th>
                                <th class="pb-3 text-[10px] font-bold uppercase tracking-widest text-on-surface/50 px-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline/10">
                            <!-- Row 1 -->
                            <tr class="hover:bg-surface/50 transition-colors group">
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-success/10 text-success flex items-center justify-center font-bold text-sm">MC</div>
                                        <span class="font-bold text-sm text-on-surface">Marcus Chen</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="text-sm font-semibold text-on-surface">BS Computer Science</div>
                                    <div class="text-[11px] text-on-surface/50">Frontend Dev Team</div>
                                </td>
                                <td class="py-4 px-4 font-bold text-sm text-on-surface">08:05 AM</td>
                                <td class="py-4 px-4 font-bold text-sm text-on-surface/40">--:-- --</td>
                                <td class="py-4 px-4">
                                    <span class="bg-success/10 text-success border border-success/20 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide">Present</span>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <button class="text-primary hover:bg-primary/5 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors inline-flex items-center gap-1 border border-transparent hover:border-primary/20 view-calendar-btn">
                                        <span class="material-symbols-outlined text-[16px]">calendar_view_week</span> View Calendar
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Row 2 -->
                            <tr class="hover:bg-surface/50 transition-colors group">
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-warning/10 text-warning flex items-center justify-center font-bold text-sm">SJ</div>
                                        <span class="font-bold text-sm text-on-surface">Sarah Jenkins</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="text-sm font-semibold text-on-surface">BS Business Admin</div>
                                    <div class="text-[11px] text-on-surface/50">HR Department</div>
                                </td>
                                <td class="py-4 px-4 font-bold text-sm text-on-surface text-warning">09:15 AM</td>
                                <td class="py-4 px-4 font-bold text-sm text-on-surface/40">--:-- --</td>
                                <td class="py-4 px-4">
                                    <span class="bg-warning/10 text-warning border border-warning/20 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide">Late</span>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <button class="text-primary hover:bg-primary/5 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors inline-flex items-center gap-1 border border-transparent hover:border-primary/20 view-calendar-btn">
                                        <span class="material-symbols-outlined text-[16px]">calendar_view_week</span> View Calendar
                                    </button>
                                </td>
                            </tr>

                            <!-- Row 3 -->
                            <tr class="hover:bg-surface/50 transition-colors group">
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-error/10 text-error flex items-center justify-center font-bold text-sm">AL</div>
                                        <span class="font-bold text-sm text-on-surface text-error">Amanda Lee</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="text-sm font-semibold text-on-surface opacity-60">BS Information Tech</div>
                                    <div class="text-[11px] text-on-surface/50">IT Support</div>
                                </td>
                                <td class="py-4 px-4 font-bold text-sm text-on-surface/40">--:-- --</td>
                                <td class="py-4 px-4 font-bold text-sm text-on-surface/40">--:-- --</td>
                                <td class="py-4 px-4">
                                    <span class="bg-error/10 text-error border border-error/20 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide">Absent</span>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <button class="text-primary hover:bg-primary/5 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors inline-flex items-center gap-1 border border-transparent hover:border-primary/20 view-calendar-btn">
                                        <span class="material-symbols-outlined text-[16px]">calendar_view_week</span> View Calendar
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- SECTION B: Weekly Monitor Grid -->
            <section class="bg-white p-6 rounded-2xl shadow-sm border border-outline/30">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold font-headline text-on-surface tracking-tight flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">view_week</span> Weekly Overview 
                        <span class="text-xs font-medium font-body text-on-surface/40 ml-2 tracking-normal">(Mon, Oct 21 - Fri, Oct 25)</span>
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-center min-w-[700px] border-collapse">
                        <thead>
                            <tr class="bg-surface/50">
                                <th class="py-4 px-4 border border-outline/20 text-left text-[10px] font-bold uppercase tracking-widest text-on-surface/50 rounded-tl-lg">Intern Name</th>
                                <th class="py-4 px-2 border border-outline/20 text-[10px] font-bold uppercase tracking-widest text-on-surface/50 w-24">Mon</th>
                                <th class="py-4 px-2 border border-outline/20 text-[10px] font-bold uppercase tracking-widest text-on-surface/50 w-24">Tue</th>
                                <th class="py-4 px-2 border border-outline/20 text-[10px] font-bold uppercase tracking-widest text-on-surface/50 w-24">Wed</th>
                                <th class="py-4 px-2 border border-outline/20 text-[10px] font-bold uppercase tracking-widest text-on-surface/50 w-24 bg-primary/5 text-primary">Thu <span class="lowercase tracking-normal">(Today)</span></th>
                                <th class="py-4 px-2 border border-outline/20 text-[10px] font-bold uppercase tracking-widest text-on-surface/50 w-24 opacity-60 rounded-tr-lg">Fri</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            <!-- Marcus Chen -->
                            <tr class="hover:bg-surface/30">
                                <td class="py-3 px-4 border border-outline/10 text-left font-bold text-sm text-on-surface">Marcus Chen</td>
                                <td class="py-3 px-2 border border-outline/10"><span class="material-icon-filled text-success text-[24px]">check_circle</span></td>
                                <td class="py-3 px-2 border border-outline/10"><span class="material-icon-filled text-success text-[24px]">check_circle</span></td>
                                <td class="py-3 px-2 border border-outline/10"><span class="material-icon-filled text-success text-[24px]">check_circle</span></td>
                                <td class="py-3 px-2 border border-outline/10 bg-primary/5"><span class="material-icon-filled text-success text-[24px] animate-pulse">check_circle</span></td>
                                <td class="py-3 px-2 border border-outline/10 opacity-60"><span class="material-symbols-outlined text-outline text-[20px]">horizontal_rule</span></td>
                            </tr>
                            <!-- Sarah Jenkins -->
                            <tr class="hover:bg-surface/30">
                                <td class="py-3 px-4 border border-outline/10 text-left font-bold text-sm text-on-surface">Sarah Jenkins</td>
                                <td class="py-3 px-2 border border-outline/10"><span class="material-icon-filled text-success text-[24px]">check_circle</span></td>
                                <td class="py-3 px-2 border border-outline/10"><span class="material-icon-filled text-error text-[24px]">cancel</span></td>
                                <td class="py-3 px-2 border border-outline/10"><span class="material-icon-filled text-success text-[24px]">check_circle</span></td>
                                <td class="py-3 px-2 border border-outline/10 bg-primary/5"><span class="material-icon-filled text-warning text-[24px]">error</span></td>
                                <td class="py-3 px-2 border border-outline/10 opacity-60"><span class="material-symbols-outlined text-outline text-[20px]">horizontal_rule</span></td>
                            </tr>
                            <!-- Amanda Lee -->
                            <tr class="hover:bg-surface/30">
                                <td class="py-3 px-4 border border-outline/10 text-left font-bold text-sm text-on-surface rounded-bl-lg">Amanda Lee</td>
                                <td class="py-3 px-2 border border-outline/10"><span class="material-icon-filled text-warning text-[24px]">error</span></td>
                                <td class="py-3 px-2 border border-outline/10"><span class="material-icon-filled text-success text-[24px]">check_circle</span></td>
                                <td class="py-3 px-2 border border-outline/10"><span class="material-icon-filled text-success text-[24px]">check_circle</span></td>
                                <td class="py-3 px-2 border border-outline/10 bg-primary/5"><span class="material-icon-filled text-error text-[24px]">cancel</span></td>
                                <td class="py-3 px-2 border border-outline/10 opacity-60 rounded-br-lg"><span class="material-symbols-outlined text-outline text-[20px]">horizontal_rule</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

        </div>
    </main>

    <!-- INTERACTIVE SIDE PANEL (Modal Overlay) -->
    <!-- Hidden by default, added id to toggle via JS -->
    <div id="calendarPanel" class="hidden fixed inset-0 z-[60] bg-on-surface/40 backdrop-blur-sm flex justify-end transition-opacity opacity-0 data-[open=true]:opacity-100">
        <!-- Panel -->
        <div class="h-full w-full max-w-md bg-surface shadow-2xl flex flex-col transform translate-x-full transition-transform duration-300 data-[open=true]:translate-x-0" id="calendarSidebar">
            
            <!-- Panel Header -->
            <div class="px-6 py-5 border-b border-outline/20 bg-white flex justify-between items-center">
                <h3 class="font-headline font-bold text-xl text-primary">Attendance History</h3>
                <button id="closePanelBtn" class="p-2 hover:bg-surface-container rounded-full text-on-surface/50 hover:text-error transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <!-- Panel Content -->
            <div class="p-6 flex-1 overflow-y-auto">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 rounded-full bg-success/10 text-success flex items-center justify-center font-bold text-lg">MC</div>
                    <div>
                        <h4 class="font-bold text-lg text-on-surface">Marcus Chen</h4>
                        <p class="text-[11px] text-on-surface/50 uppercase tracking-widest font-semibold flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">school</span> BS Computer Science</p>
                    </div>
                </div>

                <!-- KPI Summary -->
                <div class="grid grid-cols-3 gap-3 mb-8">
                    <div class="bg-success/5 border border-success/20 rounded-xl p-3 text-center">
                        <span class="block text-[10px] font-bold uppercase tracking-widest text-success/70 mb-1">Present</span>
                        <span class="block text-2xl font-extrabold font-headline text-success">18</span>
                    </div>
                    <div class="bg-warning/5 border border-warning/20 rounded-xl p-3 text-center">
                        <span class="block text-[10px] font-bold uppercase tracking-widest text-warning/80 mb-1">Late</span>
                        <span class="block text-2xl font-extrabold font-headline text-warning">2</span>
                    </div>
                    <div class="bg-error/5 border border-error/20 rounded-xl p-3 text-center">
                        <span class="block text-[10px] font-bold uppercase tracking-widest text-error/70 mb-1">Absent</span>
                        <span class="block text-2xl font-extrabold font-headline text-error">1</span>
                    </div>
                </div>

                <!-- Month Calendar layout -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h5 class="font-bold text-sm text-on-surface">October 2024</h5>
                        <div class="flex gap-1">
                            <span class="material-symbols-outlined text-outline hover:text-primary cursor-pointer">chevron_left</span>
                            <span class="material-symbols-outlined text-outline hover:text-primary cursor-pointer">chevron_right</span>
                        </div>
                    </div>

                    <!-- 30-day Calendar Grid -->
                    <div class="grid grid-cols-7 gap-1.5 text-center mb-6">
                        <!-- header -->
                        <div class="text-[10px] font-bold uppercase text-on-surface/40 py-1">S</div>
                        <div class="text-[10px] font-bold uppercase text-on-surface/40 py-1">M</div>
                        <div class="text-[10px] font-bold uppercase text-on-surface/40 py-1">T</div>
                        <div class="text-[10px] font-bold uppercase text-on-surface/40 py-1">W</div>
                        <div class="text-[10px] font-bold uppercase text-on-surface/40 py-1">T</div>
                        <div class="text-[10px] font-bold uppercase text-on-surface/40 py-1">F</div>
                        <div class="text-[10px] font-bold uppercase text-on-surface/40 py-1">S</div>

                        <!-- Blank days for start of month (assuming Oct 1 is Tuesday) -->
                        <div></div>
                        <div></div>

                        <!-- Days 1-23 -->
                        <!-- Week 1: 1-5 -->
                        <div class="aspect-square rounded-md bg-success/20 flex items-center justify-center text-sm font-bold text-success" title="Present">1</div>
                        <div class="aspect-square rounded-md bg-success/20 flex items-center justify-center text-sm font-bold text-success" title="Present">2</div>
                        <div class="aspect-square rounded-md bg-success/20 flex items-center justify-center text-sm font-bold text-success" title="Present">3</div>
                        <div class="aspect-square rounded-md bg-success/20 flex items-center justify-center text-sm font-bold text-success" title="Present">4</div>
                        <div class="aspect-square rounded-md bg-outline/10 flex items-center justify-center text-sm text-on-surface/30">5</div> <!-- Weekend -->
                        <div class="aspect-square rounded-md bg-outline/10 flex items-center justify-center text-sm text-on-surface/30">6</div> <!-- Weekend -->
                        <!-- Week 2 -->
                        <div class="aspect-square rounded-md bg-warning/20 flex items-center justify-center text-sm font-bold text-warning" title="Late">7</div>
                        <div class="aspect-square rounded-md bg-success/20 flex items-center justify-center text-sm font-bold text-success" title="Present">8</div>
                        <div class="aspect-square rounded-md bg-success/20 flex items-center justify-center text-sm font-bold text-success" title="Present">9</div>
                        <div class="aspect-square rounded-md bg-success/20 flex items-center justify-center text-sm font-bold text-success" title="Present">10</div>
                        <div class="aspect-square rounded-md bg-success/20 flex items-center justify-center text-sm font-bold text-success" title="Present">11</div>
                        <div class="aspect-square rounded-md bg-outline/10 flex items-center justify-center text-sm text-on-surface/30">12</div>
                        <div class="aspect-square rounded-md bg-outline/10 flex items-center justify-center text-sm text-on-surface/30">13</div>
                        <!-- Week 3 -->
                        <div class="aspect-square rounded-md bg-success/20 flex items-center justify-center text-sm font-bold text-success" title="Present">14</div>
                        <div class="aspect-square rounded-md bg-error/20 flex items-center justify-center text-sm font-bold text-error" title="Absent">15</div>
                        <div class="aspect-square rounded-md bg-success/20 flex items-center justify-center text-sm font-bold text-success" title="Present">16</div>
                        <div class="aspect-square rounded-md bg-success/20 flex items-center justify-center text-sm font-bold text-success" title="Present">17</div>
                        <div class="aspect-square rounded-md bg-success/20 flex items-center justify-center text-sm font-bold text-success" title="Present">18</div>
                        <div class="aspect-square rounded-md bg-outline/10 flex items-center justify-center text-sm text-on-surface/30">19</div>
                        <div class="aspect-square rounded-md bg-outline/10 flex items-center justify-center text-sm text-on-surface/30">20</div>
                        <!-- Week 4 -->
                        <div class="aspect-square rounded-md bg-success/20 flex items-center justify-center text-sm font-bold text-success" title="Present">21</div>
                        <div class="aspect-square rounded-md bg-success/20 flex items-center justify-center text-sm font-bold text-success" title="Present">22</div>
                        <div class="aspect-square rounded-md bg-warning/20 flex items-center justify-center text-sm font-bold text-warning" title="Late">23</div>
                        <div class="aspect-square rounded-md bg-success/20 flex items-center justify-center text-sm font-bold text-success ring-2 ring-primary ring-offset-2 border border-primary relative" title="Today">24</div>
                        
                        <!-- Future days -->
                        <div class="aspect-square rounded-md bg-surface-container border border-outline/20 flex items-center justify-center text-sm text-on-surface/30">25</div>
                        <div class="aspect-square rounded-md bg-surface-container border border-outline/20 flex items-center justify-center text-sm text-on-surface/30">26</div>
                        <div class="aspect-square rounded-md bg-surface-container border border-outline/20 flex items-center justify-center text-sm text-on-surface/30">27</div>
                        <div class="aspect-square rounded-md bg-surface-container border border-outline/20 flex items-center justify-center text-sm text-on-surface/30">28</div>
                        <div class="aspect-square rounded-md bg-surface-container border border-outline/20 flex items-center justify-center text-sm text-on-surface/30">29</div>
                        <div class="aspect-square rounded-md bg-surface-container border border-outline/20 flex items-center justify-center text-sm text-on-surface/30">30</div>
                        <div class="aspect-square rounded-md bg-surface-container border border-outline/20 flex items-center justify-center text-sm text-on-surface/30">31</div>

                    </div>
                    
                    <div class="text-[10px] bg-white border border-outline/20 p-3 rounded-lg flex justify-around font-medium text-on-surface/60">
                        <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-success/20 border border-success/40 text-success flex items-center justify-center"></span> Present</div>
                        <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-warning/20 border border-warning/40 text-warning flex items-center justify-center"></span> Late</div>
                        <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-error/20 border border-error/40 text-error flex items-center justify-center"></span> Absent</div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Simple JS to mock the modal behavior -->
    <script>
        const panel = document.getElementById('calendarPanel');
        const sidebar = document.getElementById('calendarSidebar');
        const btns = document.querySelectorAll('.view-calendar-btn');
        const closeBtn = document.getElementById('closePanelBtn');

        const openPanel = () => {
            panel.classList.remove('hidden');
            // Slight delay to allow flex to process before opacity transitions
            setTimeout(() => {
                panel.setAttribute('data-open', 'true');
                sidebar.setAttribute('data-open', 'true');
            }, 10);
        };

        const closePanel = () => {
            panel.setAttribute('data-open', 'false');
            sidebar.setAttribute('data-open', 'false');
            setTimeout(() => {
                panel.classList.add('hidden');
            }, 300); // Wait for transition
        };

        btns.forEach(btn => btn.addEventListener('click', openPanel));
        closeBtn.addEventListener('click', closePanel);
        panel.addEventListener('click', (e) => {
            if(e.target === panel) closePanel();
        });
    </script>
</body>
</html>
