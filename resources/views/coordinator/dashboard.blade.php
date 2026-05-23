<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>OJT Portal | Coordinator Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link
        href="https://fonts.googleapis.com/css2?family=Newsreader:opsz,wght@6..72,400;6..72,600;6..72,700;6..72,800&family=Public+Sans:wght@400;500;600&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Public Sans', sans-serif;
        }

        .font-headline {
            font-family: 'Newsreader', serif;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>

<body class="bg-surface text-on-surface" data-theme="portal">
    <!-- SideNavBar (Shared Component 12) -->
    <!-- SideNavBar (Shared Component) -->
    @include('components.coordinator-sidebar')
    <!-- TopNavBar (Shared Component 12 Implementation) -->
    <header class="fixed top-0 left-64 right-0 z-40 bg-[#fff7fd] border-b border-[#cec3d0]/15">
        <div class="flex justify-between items-center px-8 py-4 w-full">
            <div class="flex items-center gap-8">
                <span class="text-2xl font-headline font-semibold text-[#300050] tracking-tight" style="">OJT
                    Management</span>
                <nav class="hidden md:flex items-center gap-6">
                    <a class="text-sm font-semibold text-[#300050] border-b-2 border-[#785900] pb-1" href="#"
                        style="">Dashboard</a>
                    <a class="text-sm font-semibold text-[#1e1a1f]/60 hover:text-[#300050] transition-colors duration-200"
                        href="#" style="">Student List</a>
                    <a class="text-sm font-semibold text-[#1e1a1f]/60 hover:text-[#300050] transition-colors duration-200"
                        href="#" style="">Company Directory</a>
                    <a class="text-sm font-semibold text-[#1e1a1f]/60 hover:text-[#300050] transition-colors duration-200"
                        href="#" style="">Reports</a>
                </nav>
            </div>
            <div class="flex items-center gap-6">
                <div class="relative">
                    <span
                        class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#1e1a1f]/60 text-sm"
                        data-icon="search" style="">search</span>
                    <input
                        class="bg-[#faf1f8] border-none rounded-full py-2 pl-10 pr-4 text-sm w-64 focus:ring-1 focus:ring-[#300050]/20"
                        placeholder="Search..." type="text">
                </div>
                <div class="flex items-center gap-3">
                    <button class="p-2 text-[#300050] hover:bg-[#faf1f8] rounded-full transition-all active:scale-95"
                        style="">
                        <span class="material-symbols-outlined" data-icon="notifications"
                            style="">notifications</span>
                    </button>
                    <div class="flex items-center gap-3 pl-2 border-l border-[#cec3d0]/30">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold font-headline text-[#300050]" style="">Dr. Elena Vance
                            </p>
                            <p class="text-[10px] uppercase tracking-wider text-secondary font-bold" style="">
                                OJT Coordinator</p>
                        </div>
                        <img alt="User profile avatar"
                            class="w-9 h-9 rounded-full object-cover ring-2 ring-[#300050]/10"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDDKv7C8q91OqemiUq0Q4YlTNFY71JjVlvL_DM-JaB1jNjaZY8BNxp65eI0f3Cg3JS2S64Sp5RgJAneCUpISeDEHSoUK_yh7z2SIt-fa7HG9Q7S77vNHgKJ71S8wwk-Czo3cTC0DlisGtQE62wU67RPBkK9oV62W2zpnzMJqbPhhGUZsBelRpMMTeLTas71X7Kyhqtnsx_ZG-6xXaUnLwlvdXsz68cKkM6fce2EqJHb4nIjOQ2ssdf6SpO3eOiUACueTYIYU8MEAWdS"
                            style="">
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Main Content -->
    <main class="ml-64 pt-24 px-8 pb-12">
        <!-- Dashboard Header -->
        <div class="flex items-end justify-between mb-8">
            <div>
                <h2 class="text-4xl font-extrabold font-headline text-on-surface tracking-tight" style="">
                    Coordinator Dashboard</h2>
                <p class="text-on-surface/60 font-medium" style="">Monitoring progress for Academic Year
                    2023-2024</p>
            </div>
            <div class="flex gap-3">
                <button
                    class="flex items-center gap-2 px-5 py-2.5 bg-surface-container border border-outline/20 rounded-lg text-sm font-semibold text-primary hover:bg-outline/10 transition-all active:scale-95"
                    style="">
                    <span class="material-symbols-outlined text-[18px]" data-icon="filter_list"
                        style="">filter_list</span>
                    Filter View
                </button>
                <button
                    class="flex items-center gap-2 px-5 py-2.5 bg-primary text-on-primary rounded-lg text-sm font-semibold hover:shadow-lg transition-all active:scale-95"
                    style="">
                    <span class="material-symbols-outlined text-[18px]" data-icon="add" style="">add</span>
                    Register Student
                </button>
            </div>
        </div>
        <!-- Overview Bento Grid -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div
                class="bg-surface-container p-6 rounded-lg border-l-4 border-primary shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-primary/10 rounded-lg text-primary">
                        <span class="material-symbols-outlined" data-icon="groups" style="">groups</span>
                    </div>
                    <span
                        class="text-tertiary font-bold text-xs flex items-center gap-1 bg-tertiary/10 px-2 py-1 rounded-full"
                        style="">
                        <span class="material-symbols-outlined text-[14px]" data-icon="trending_up"
                            style="">trending_up</span>
                        +4%
                    </span>
                </div>
                <div>
                    <h3 class="text-4xl font-extrabold font-headline text-on-surface" style="">125</h3>
                    <p class="text-xs font-bold uppercase tracking-widest text-on-surface/50 mt-1" style="">
                        Active Students</p>
                </div>
            </div>
            <div
                class="bg-surface-container p-6 rounded-lg border-l-4 border-error shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-error/10 rounded-lg text-error">
                        <span class="material-symbols-outlined" data-icon="pending_actions"
                            style="">pending_actions</span>
                    </div>
                    <span
                        class="text-error font-bold text-xs flex items-center gap-1 bg-error/10 px-2 py-1 rounded-full uppercase tracking-tighter"
                        style="">
                        Attention
                    </span>
                </div>
                <div>
                    <h3 class="text-4xl font-extrabold font-headline text-on-surface" style="">18</h3>
                    <p class="text-xs font-bold uppercase tracking-widest text-on-surface/50 mt-1" style="">
                        Pending Approvals</p>
                </div>
            </div>
            <div
                class="bg-surface-container p-6 rounded-lg border-l-4 border-secondary shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-secondary/10 rounded-lg text-secondary">
                        <span class="material-symbols-outlined" data-icon="schedule" style="">schedule</span>
                    </div>
                    <span class="text-on-surface/40 font-bold text-[10px] uppercase tracking-widest" style="">AY
                        23-24 Total</span>
                </div>
                <div>
                    <h3 class="text-4xl font-extrabold font-headline text-on-surface" style="">42,000</h3>
                    <p class="text-xs font-bold uppercase tracking-widest text-on-surface/50 mt-1" style="">
                        Hours Tracked</p>
                </div>
            </div>
        </section>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Manage Students Table -->
            <div class="lg:col-span-8 space-y-6">
                <div class="bg-surface-container rounded-lg overflow-hidden shadow-sm border border-outline/10">
                    <div class="px-6 py-5 border-b border-outline/10 flex justify-between items-center bg-white/50">
                        <h3 class="font-headline font-bold text-lg text-on-surface" style="">Manage Students
                        </h3>
                        <div class="flex gap-2">
                            <button class="p-1.5 hover:bg-surface/80 rounded text-on-surface/60 transition-colors"
                                style="">
                                <span class="material-symbols-outlined text-[20px]" data-icon="search"
                                    style="">search</span>
                            </button>
                            <button class="p-1.5 hover:bg-surface/80 rounded text-on-surface/60 transition-colors"
                                style="">
                                <span class="material-symbols-outlined text-[20px]" data-icon="more_vert"
                                    style="">more_vert</span>
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-surface/50">
                                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-on-surface/50"
                                        style="">Student Name</th>
                                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-on-surface/50"
                                        style="">Company</th>
                                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-on-surface/50"
                                        style="">Req. Hours</th>
                                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-on-surface/50"
                                        style="">Hours Status</th>
                                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-on-surface/50"
                                        style="">Docs</th>
                                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-on-surface/50 text-right"
                                        style="">Logbook</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline/5">
                                @foreach($students as $student)
                                <tr class="hover:bg-white/40 transition-colors group">
                                    <td class="px-6 py-4" style="">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center text-primary font-bold text-xs uppercase">
                                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-on-surface group-hover:text-primary transition-colors"
                                                    style="">{{ $student->first_name }} {{ $student->last_name }}</p>
                                                <p class="text-[11px] text-on-surface/60 font-medium" style="">
                                                    {{ $student->course }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium" style="">Assigned Company</td>
                                    <td class="px-6 py-4 text-sm font-bold text-primary" style="">
                                        {{ $student->required_hours }} hrs
                                    </td>
                                    <td class="px-6 py-4" style="">
                                        <div class="w-full max-w-[120px]">
                                            <div class="flex justify-between items-center mb-1">
                                                <span class="text-[10px] font-bold text-primary" style="">{{ $student->approved_hours_count ?? 0 }} /
                                                    {{ $student->required_hours }}h</span>
                                                <span class="text-[10px] font-bold text-on-surface/40"
                                                    style="">{{ $student->required_hours > 0 ? round(($student->approved_hours_count / $student->required_hours) * 100) : 0 }}%</span>
                                            </div>
                                            <div class="h-1 w-full bg-outline/20 rounded-full overflow-hidden">
                                                <div class="h-full bg-primary rounded-full" style="width: {{ $student->required_hours > 0 ? round(($student->approved_hours_count / $student->required_hours) * 100) : 0 }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4" style="">
                                        <span
                                            class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-secondary/10 text-secondary"
                                            style="">{{ ($student->approved_hours_count ?? 0) >= $student->required_hours && $student->required_hours > 0 ? 'Completed' : 'In Progress' }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right" style="">
                                        <span class="text-[11px] font-bold text-on-surface/60" style="">{{ $student->required_hours > 0 ? round(($student->approved_hours_count / $student->required_hours) * 100) : 0 }}%
                                            Complete</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 bg-surface/30 flex items-center justify-between border-t border-outline/10">
                        <span class="text-[11px] text-on-surface/50 font-bold uppercase tracking-widest"
                            style="">Showing 3 of 125 students</span>
                        <div class="flex gap-2">
                            <button
                                class="p-1 px-4 border border-outline/20 rounded text-[10px] font-bold uppercase tracking-widest bg-white disabled:opacity-50"
                                disabled="" style="">Prev</button>
                            <button
                                class="p-1 px-4 border border-outline/20 rounded text-[10px] font-bold uppercase tracking-widest bg-white hover:bg-surface transition-colors"
                                style="">Next</button>
                        </div>
                    </div>
                </div>
                <!-- Generate Reports Section -->
                <div class="bg-surface-container p-8 rounded-lg shadow-sm border border-outline/10">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="material-symbols-outlined text-primary text-3xl" data-icon="summarize"
                            style="">summarize</span>
                        <h3 class="font-headline font-bold text-xl" style="">Generate Reports</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="md:col-span-2">
                            <label
                                class="block text-[10px] font-bold text-on-surface/50 uppercase tracking-widest mb-2"
                                style="">Report Type</label>
                            <select
                                class="w-full rounded-lg border border-outline/20 bg-white text-sm font-medium py-3 focus:ring-1 focus:ring-primary/20">
                                <option>Weekly Progress Summary</option>
                                <option>Final Completion Report</option>
                                <option>Document Compliance Audit</option>
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-bold text-on-surface/50 uppercase tracking-widest mb-2"
                                style="">Format</label>
                            <div class="flex gap-2">
                                <button
                                    class="flex-1 py-3 bg-white border border-outline/20 rounded-lg text-[10px] font-bold uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-surface transition-colors"
                                    style="">
                                    <span class="material-symbols-outlined text-[16px]" data-icon="picture_as_pdf"
                                        style="">picture_as_pdf</span>
                                    PDF
                                </button>
                                <button
                                    class="flex-1 py-3 bg-white border border-outline/20 rounded-lg text-[10px] font-bold uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-surface transition-colors"
                                    style="">
                                    <span class="material-symbols-outlined text-[16px]" data-icon="table_chart"
                                        style="">table_chart</span>
                                    XLS
                                </button>
                            </div>
                        </div>
                        <div class="flex flex-col justify-end">
                            <button
                                class="w-full py-3 bg-secondary text-white rounded-lg text-[10px] font-bold uppercase tracking-widest shadow-sm hover:brightness-110 transition-all active:scale-95"
                                style="">
                                Generate
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Right Column: Approval Queue -->
            <div class="lg:col-span-4">
                <div
                    class="bg-surface-container rounded-lg shadow-sm overflow-hidden sticky top-24 border border-outline/10">
                    <div class="p-5 border-b border-outline/10 flex justify-between items-center bg-white/50">
                        <h3 class="font-headline font-bold text-on-surface" style="">Approval Queue</h3>
                        <span
                            class="bg-error text-white text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-tighter"
                            style="">18 New</span>
                    </div>
                    <div class="p-5 space-y-6">
                        <!-- Focused Item -->
                        <div class="p-4 bg-white rounded-lg border-l-4 border-primary shadow-sm">
                            <div class="flex items-center gap-3 mb-4">
                                <img class="w-10 h-10 rounded-lg object-cover"
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuDv7cgpO-4dwLNUVf-9uAZZBXfvIONLx7L0Iaz4lx6x7h26W2S6AfmaFxGzr6-FBlfuc8qYH_H4P_ms-S6WlLKOFx2ZizuWScrD3YsUqBGtuWoA6eYX-9LNwLpkq2BCwFCPVQabiKfYvzpMrbrZar1_RBIAcqt4n4-lscte1tzSjdUVxLrH_wlx4yQ8gQ-SLCvMXTe56UwzUzXoF4f_LsJiWLnai8HB3ocMxAEXX9klh4A4Mfp-TLju9AOJ2PBvodyfBY3EFoWE2fDy"
                                    style="">
                                <div>
                                    <p class="text-sm font-bold text-on-surface" style="">Sarah Jenkins</p>
                                    <p class="text-[10px] font-medium text-on-surface/40 uppercase tracking-wider"
                                        style="">2 hours ago</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div
                                    class="p-3 bg-surface rounded border border-outline/10 flex items-center gap-3 group cursor-pointer hover:border-primary transition-colors">
                                    <div
                                        class="w-10 h-12 bg-error/5 rounded flex items-center justify-center text-error">
                                        <span class="material-symbols-outlined text-[32px]" data-icon="picture_as_pdf"
                                            style="">picture_as_pdf</span>
                                    </div>
                                    <div class="flex-1 overflow-hidden">
                                        <p class="text-xs font-bold text-on-surface truncate" style="">
                                            Weekly_Logbook_W12.pdf</p>
                                        <p class="text-[10px] text-on-surface/50 font-medium" style="">Logbook
                                            Entry • 2.4 MB</p>
                                    </div>
                                    <span
                                        class="material-symbols-outlined text-on-surface/40 opacity-0 group-hover:opacity-100 transition-opacity"
                                        data-icon="visibility" style="">visibility</span>
                                </div>
                                <div class="flex gap-2">
                                    <button
                                        class="flex-1 py-2.5 bg-primary text-white rounded text-[10px] font-bold uppercase tracking-widest hover:brightness-110 transition-all active:scale-95"
                                        style="">Approve</button>
                                    <button
                                        class="flex-1 py-2.5 bg-outline/10 text-on-surface rounded text-[10px] font-bold uppercase tracking-widest hover:bg-error/10 hover:text-error transition-all active:scale-95"
                                        style="">Reject</button>
                                </div>
                            </div>
                        </div>
                        <!-- Next in Queue -->
                        <div class="space-y-4 pt-4 border-t border-outline/10">
                            <p class="text-[10px] font-bold text-on-surface/40 uppercase tracking-[0.2em]"
                                style="">Next in Queue</p>
                            <div class="flex items-center justify-between group cursor-pointer">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary font-bold text-xs"
                                        style="">JD</div>
                                    <div>
                                        <p class="text-xs font-bold text-on-surface" style="">John Doe</p>
                                        <p class="text-[10px] text-on-surface/50 font-medium" style="">
                                            Post-Training Narrative</p>
                                    </div>
                                </div>
                                <span
                                    class="material-symbols-outlined text-on-surface/30 text-[18px] group-hover:translate-x-1 transition-transform"
                                    data-icon="chevron_right" style="">chevron_right</span>
                            </div>
                            <button
                                class="w-full py-3 mt-2 text-[10px] font-bold uppercase tracking-widest text-primary hover:bg-primary/5 rounded-lg transition-colors border border-dashed border-primary/20"
                                style="">
                                View Full Queue
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
