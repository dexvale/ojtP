<!DOCTYPE html>
<html class="light" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Student Profile | OJT Portal</title>
<meta name="description" content="View and update your student profile information for the OJT Management Portal."/>
<!-- Fonts -->
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Newsreader:opsz,wght@6..72,400;6..72,600;6..72,700;6..72,800&family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<!-- Icons -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<!-- Tailwind -->
@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        vertical-align: middle;
    }
    aside nav a { transition: all 0.2s ease; }
    /* Remove browser default time/date picker arrows */
    input[type="date"]::-webkit-calendar-picker-indicator { opacity: 0; cursor: pointer; position: absolute; right: 0; width: 100%; }
</style>
</head>
<body class="bg-surface font-body text-on-surface antialiased" data-theme="student">

<!-- ═══════════════════════════════
     TOP HEADER
═══════════════════════════════ -->
<header class="fixed top-0 w-full z-50 bg-[#fff7fd] flex justify-between items-center px-6 lg:px-8 py-4 border-b border-[#cec3d0]/20 backdrop-blur-sm md:pl-64">
    <div class="flex items-center gap-4">
        <button id="sidebar-toggle" class="md:hidden p-2 text-primary rounded-lg hover:bg-surface-container transition-colors" aria-label="Toggle menu">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center overflow-hidden">
                <img alt="University Logo" class="h-8 w-8 object-contain" src="{{ asset('images/logo.png') }}" />
            </div>
            <span class="text-xl font-headline font-semibold text-primary hidden sm:block">OJT Portal</span>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <div class="hidden md:flex bg-surface-container rounded-lg px-4 py-2 items-center gap-2 border border-outline/15">
            <span class="material-symbols-outlined text-outline text-[18px]">search</span>
            <input class="bg-transparent border-none focus:ring-0 text-sm w-44 text-on-surface-variant placeholder:text-outline/60" placeholder="Search resources..." type="text"/>
        </div>
        <button class="relative p-2 text-primary rounded-lg hover:bg-surface-container transition-colors" aria-label="Notifications">
            <span class="material-symbols-outlined">notifications</span>
            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-error rounded-full"></span>
        </button>
        <button class="p-2 text-primary rounded-lg hover:bg-surface-container transition-colors" aria-label="Profile">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 24;">account_circle</span>
        </button>
    </div>
</header>

<!-- ═══════════════════════════════
     SIDEBAR
═══════════════════════════════ -->
<aside id="sidebar" class="fixed left-0 top-0 h-screen w-64 z-40 bg-primary flex flex-col py-6 shadow-2xl pt-28 -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
    <div class="px-6 mb-8 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-white/10 border border-white/15 flex items-center justify-center flex-shrink-0">
            <img alt="University Logo" class="h-8 w-8 object-contain" src="{{ asset('images/logo.png') }}" />
        </div>
        <div>
            <h2 class="font-headline text-xl text-white leading-tight">OJT Portal</h2>
            <p class="text-[10px] uppercase tracking-widest text-white/50 font-medium">Academic Editorial</p>
        </div>
    </div>

    <!-- nav link -->
    <nav class="flex-grow font-['Public_Sans'] text-sm flex flex-col mt-4">

    <a href="{{ route('student.dashboard') }}" 
       class="mx-2 my-1 px-4 py-3 flex items-center gap-3 rounded-lg transition-all duration-200 
              {{ request()->routeIs('student.dashboard') ? 'bg-white/10 text-white font-semibold' : 'text-white/60 hover:text-white hover:bg-white/5 font-medium' }}">
        <span class="material-symbols-outlined">dashboard</span>
        Dashboard
    </a>

    <a href="{{ route('student.logs') }}" 
       class="mx-2 my-1 px-4 py-3 flex items-center gap-3 rounded-lg transition-all duration-200 
              {{ request()->routeIs('student.logs') ? 'bg-white/10 text-white font-semibold' : 'text-white/60 hover:text-white hover:bg-white/5 font-medium' }}">
        <span class="material-symbols-outlined">description</span>
        Internship Logs
    </a>


    <a href="{{ route('student.profile') }}" 
       class="mx-2 my-1 px-4 py-3 flex items-center gap-3 rounded-lg transition-all duration-200 
              {{ request()->routeIs('student.profile') ? 'bg-white/10 text-white font-semibold' : 'text-white/60 hover:text-white hover:bg-white/5 font-medium' }}">
        <span class="material-symbols-outlined">person</span>
        Profile
    </a>

</nav>

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

<!-- Sidebar overlay for mobile -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-30 hidden md:hidden" onclick="closeSidebar()"></div>

<!-- ═══════════════════════════════
     MAIN CONTENT
═══════════════════════════════ -->
<main class="md:ml-64 pt-20 min-h-screen pb-24 md:pb-8">
    <div class="p-5 lg:p-8 max-w-5xl mx-auto space-y-6">

        <!-- ── Page Header ── -->
        <header class="flex flex-col sm:flex-row sm:items-center justify-between gap-5 border-b border-surface-variant/30 pb-6">
            <div class="flex items-center gap-5">
                <!-- Avatar -->
                <div class="relative flex-shrink-0">
                    <div class="w-20 h-20 rounded-full bg-primary/10 border-4 border-surface-container-lowest shadow-lg flex items-center justify-center overflow-hidden">
                        <span class="material-symbols-outlined text-5xl text-primary/40" style="font-variation-settings:'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 48;">account_circle</span>
                    </div>
                    <button class="absolute -bottom-1 -right-1 w-7 h-7 bg-primary rounded-full flex items-center justify-center border-2 border-white shadow" aria-label="Change photo">
                        <span class="material-symbols-outlined text-white text-sm">photo_camera</span>
                    </button>
                </div>
                <!-- Title + Status -->
                <div>
                    <h1 class="text-3xl lg:text-4xl font-extrabold font-headline tracking-tight text-primary leading-tight">
                        Student Profile<br class="sm:hidden"/> Information
                    </h1>
                    <div class="flex items-center gap-1.5 mt-1.5">
                        <span class="material-symbols-outlined text-tertiary text-base" style="font-variation-settings:'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 20;">verified</span>
                        <span class="text-xs font-semibold text-tertiary uppercase tracking-wide">Verified Student Status</span>
                    </div>
                </div>
            </div>
            <!-- Edit Profile Button -->
            <button id="edit-btn" type="button" onclick="toggleEdit()"
                    class="self-start sm:self-auto flex items-center gap-2 px-5 py-2.5 bg-primary text-on-primary rounded-lg font-bold text-sm hover:opacity-90 active:scale-95 transition-all shadow-sm flex-shrink-0">
                <span class="material-symbols-outlined text-lg">edit</span>
                Edit Profile
            </button>
        </header>

        <!-- ── Profile Form ── -->
        <form method="POST" action="#" id="profile-form">
            @csrf
            @method('PUT')

            <div class="space-y-5">

                {{-- ═══ Card 1: Basic Information ═══ --}}
                <div class="bg-surface-container-lowest rounded-xl border border-surface-variant/20 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-surface-variant/15 bg-surface-container-low flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-xl">badge</span>
                        <h2 class="font-headline font-bold text-lg text-primary">Basic Information</h2>
                    </div>
                    <div class="p-6 space-y-5">
                        {{-- Row 1: Names --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-outline uppercase tracking-widest mb-1.5">
                                    First Name <span class="text-error">*</span>
                                </label>
                                <input type="text" name="first_name" value="Jlou" disabled
                                       class="profile-input w-full bg-surface-container rounded-lg px-3.5 py-2.5 text-sm font-medium text-on-surface border border-surface-variant/30 focus:ring-2 focus:ring-primary/40 focus:border-transparent transition-all disabled:opacity-60 disabled:cursor-not-allowed"/>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-outline uppercase tracking-widest mb-1.5">
                                    Middle Name <span class="text-error">*</span>
                                </label>
                                <input type="text" name="middle_name" value="Balane" disabled
                                       class="profile-input w-full bg-surface-container rounded-lg px-3.5 py-2.5 text-sm font-medium text-on-surface border border-surface-variant/30 focus:ring-2 focus:ring-primary/40 focus:border-transparent transition-all disabled:opacity-60 disabled:cursor-not-allowed"/>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-outline uppercase tracking-widest mb-1.5">
                                    Last Name <span class="text-error">*</span>
                                </label>
                                <input type="text" name="last_name" value="Espigadera" disabled
                                       class="profile-input w-full bg-surface-container rounded-lg px-3.5 py-2.5 text-sm font-medium text-on-surface border border-surface-variant/30 focus:ring-2 focus:ring-primary/40 focus:border-transparent transition-all disabled:opacity-60 disabled:cursor-not-allowed"/>
                            </div>
                        </div>
                        {{-- Row 2: Contact --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-outline uppercase tracking-widest mb-1.5">
                                    Contact Address <span class="text-error">*</span>
                                </label>
                                <input type="text" name="address" placeholder="Contact Address" disabled
                                       class="profile-input w-full bg-surface-container rounded-lg px-3.5 py-2.5 text-sm font-medium text-on-surface border border-surface-variant/30 focus:ring-2 focus:ring-primary/40 focus:border-transparent transition-all disabled:opacity-60 disabled:cursor-not-allowed"/>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-outline uppercase tracking-widest mb-1.5">
                                    Contact Number <span class="text-error">*</span>
                                </label>
                                <input type="tel" name="contact_number" value="09317987613" disabled
                                       class="profile-input w-full bg-surface-container rounded-lg px-3.5 py-2.5 text-sm font-medium text-on-surface border border-surface-variant/30 focus:ring-2 focus:ring-primary/40 focus:border-transparent transition-all disabled:opacity-60 disabled:cursor-not-allowed"/>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-outline uppercase tracking-widest mb-1.5">
                                    Email Address <span class="text-error">*</span>
                                </label>
                                <input type="email" name="email" value="jlou@gmail.com" disabled
                                       class="profile-input w-full bg-surface-container rounded-lg px-3.5 py-2.5 text-sm font-medium text-on-surface border border-surface-variant/30 focus:ring-2 focus:ring-primary/40 focus:border-transparent transition-all disabled:opacity-60 disabled:cursor-not-allowed"/>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ═══ Card 2: Family Information ═══ --}}
                <div class="bg-surface-container-lowest rounded-xl border border-surface-variant/20 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-surface-variant/15 bg-surface-container-low flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-xl">family_history</span>
                        <h2 class="font-headline font-bold text-lg text-primary">Family Information</h2>
                    </div>
                    <div class="p-6 space-y-5">
                        {{-- Row 1: Parents --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-outline uppercase tracking-widest mb-1.5">
                                    Father's Name <span class="text-error">*</span>
                                </label>
                                <input type="text" name="father_name" placeholder="Father's Name" disabled
                                       class="profile-input w-full bg-surface-container rounded-lg px-3.5 py-2.5 text-sm font-medium text-on-surface border border-surface-variant/30 focus:ring-2 focus:ring-primary/40 focus:border-transparent transition-all disabled:opacity-60 disabled:cursor-not-allowed"/>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-outline uppercase tracking-widest mb-1.5">
                                    Mother's Name <span class="text-error">*</span>
                                </label>
                                <input type="text" name="mother_name" placeholder="Mother's Name" disabled
                                       class="profile-input w-full bg-surface-container rounded-lg px-3.5 py-2.5 text-sm font-medium text-on-surface border border-surface-variant/30 focus:ring-2 focus:ring-primary/40 focus:border-transparent transition-all disabled:opacity-60 disabled:cursor-not-allowed"/>
                            </div>
                        </div>
                        {{-- Row 2: Emergency --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-outline uppercase tracking-widest mb-1.5">
                                    Emergency Contact Person <span class="text-error">*</span>
                                </label>
                                <input type="text" name="emergency_contact_person" placeholder="Emergency contact person" disabled
                                       class="profile-input w-full bg-surface-container rounded-lg px-3.5 py-2.5 text-sm font-medium text-on-surface border border-surface-variant/30 focus:ring-2 focus:ring-primary/40 focus:border-transparent transition-all disabled:opacity-60 disabled:cursor-not-allowed"/>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-outline uppercase tracking-widest mb-1.5">
                                    Emergency Contact Number <span class="text-error">*</span>
                                </label>
                                <input type="tel" name="emergency_contact_number" placeholder="Emergency contact number" disabled
                                       class="profile-input w-full bg-surface-container rounded-lg px-3.5 py-2.5 text-sm font-medium text-on-surface border border-surface-variant/30 focus:ring-2 focus:ring-primary/40 focus:border-transparent transition-all disabled:opacity-60 disabled:cursor-not-allowed"/>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ═══ Card 3: OJT / Academic Details ═══ --}}
                <div class="bg-surface-container-lowest rounded-xl border border-surface-variant/20 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-surface-variant/15 bg-surface-container-low flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-xl">school</span>
                        <h2 class="font-headline font-bold text-lg text-primary">OJT / Academic Details</h2>
                    </div>
                    <div class="p-6 space-y-5">
                        {{-- Row 1: Student ID + Company --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-outline uppercase tracking-widest mb-1.5">
                                    Student ID <span class="text-error">*</span>
                                </label>
                                <input type="text" name="student_id" value="098765" disabled
                                       class="profile-input w-full bg-surface-container rounded-lg px-3.5 py-2.5 text-sm font-medium text-on-surface border border-surface-variant/30 focus:ring-2 focus:ring-primary/40 focus:border-transparent transition-all disabled:opacity-60 disabled:cursor-not-allowed"/>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-outline uppercase tracking-widest mb-1.5">
                                    Company <span class="text-error">*</span>
                                </label>
                                <select name="company" disabled
                                        class="profile-input w-full bg-surface-container rounded-lg px-3.5 py-2.5 text-sm font-medium text-on-surface border border-surface-variant/30 focus:ring-2 focus:ring-primary/40 focus:border-transparent transition-all disabled:opacity-60 disabled:cursor-not-allowed appearance-none">
                                    <option value="BISU-BC" selected>BISU-BC</option>
                                    <option value="other">Other Company</option>
                                </select>
                                <p class="text-[11px] text-secondary mt-1.5 flex items-center gap-1 cursor-pointer hover:underline underline-offset-2">
                                    <span class="material-symbols-outlined text-sm">add_circle</span>
                                    Would you like to add a company to the list?
                                </p>
                            </div>
                        </div>
                        {{-- Row 2: Internship Start Date --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-outline uppercase tracking-widest mb-1.5">
                                    Internship Start <span class="text-error">*</span>
                                </label>
                                <div class="relative">
                                    <input type="date" name="internship_start" value="2023-02-17" disabled
                                           class="profile-input w-full bg-surface-container rounded-lg px-3.5 py-2.5 pr-10 text-sm font-medium text-on-surface border border-surface-variant/30 focus:ring-2 focus:ring-primary/40 focus:border-transparent transition-all disabled:opacity-60 disabled:cursor-not-allowed"/>
                                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">calendar_today</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ═══ Footer: Update Button ═══ --}}
                <div class="flex justify-end pt-2">
                    <button type="submit" id="update-btn"
                            class="hidden items-center gap-2 bg-primary text-on-primary px-7 py-3 rounded-xl font-bold text-sm shadow-lg shadow-primary/25 hover:opacity-90 active:scale-95 transition-all">
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings:'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 24;">save</span>
                        Update Profile
                    </button>
                </div>

            </div>
        </form>

    </div>{{-- /Container --}}
</main>

<!-- ═══════════════════════════════
     MOBILE BOTTOM NAV
═══════════════════════════════ -->
<nav class="md:hidden fixed bottom-0 w-full bg-white/90 backdrop-blur-lg border-t border-surface-variant/20 flex justify-around items-center py-2.5 z-50">
    <a href="{{ route('student.dashboard') }}" class="flex flex-col items-center gap-0.5 text-outline px-3 py-1">
        <span class="material-symbols-outlined text-xl">dashboard</span>
        <span class="text-[10px] font-bold">Home</span>
    </a>
    <a href="{{ route('student.logs') }}" class="flex flex-col items-center gap-0.5 text-outline px-3 py-1">
        <span class="material-symbols-outlined text-xl">description</span>
        <span class="text-[10px] font-bold">Logs</span>
    </a>
    <button class="flex flex-col items-center gap-0.5 text-outline px-3 py-1">
        <span class="material-symbols-outlined text-xl">add_circle</span>
        <span class="text-[10px] font-bold">New</span>
    </button>
    <button class="flex flex-col items-center gap-0.5 text-outline px-3 py-1">
        <span class="material-symbols-outlined text-xl">business</span>
        <span class="text-[10px] font-bold">Hub</span>
    </button>
    <a href="{{ route('student.profile') }}" class="flex flex-col items-center gap-0.5 text-primary px-3 py-1">
        <span class="material-symbols-outlined text-xl" style="font-variation-settings:'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 24;">person</span>
        <span class="text-[10px] font-bold">Profile</span>
    </a>
</nav>

<script>
    // ── Edit / Save toggle ──
    function toggleEdit() {
        const inputs  = document.querySelectorAll('.profile-input');
        const editBtn = document.getElementById('edit-btn');
        const saveBtn = document.getElementById('update-btn');
        const isEditing = !inputs[0].disabled;

        inputs.forEach(el => {
            el.disabled = isEditing;
            if (isEditing) {
                el.classList.add('opacity-60', 'cursor-not-allowed');
            } else {
                el.classList.remove('opacity-60', 'cursor-not-allowed');
            }
        });

        if (isEditing) {
            // Switching back to view mode
            editBtn.innerHTML = '<span class="material-symbols-outlined text-lg">edit</span> Edit Profile';
            saveBtn.classList.replace('flex', 'hidden');
        } else {
            // Switching to edit mode
            editBtn.innerHTML = '<span class="material-symbols-outlined text-lg">close</span> Cancel';
            saveBtn.classList.replace('hidden', 'flex');
        }
    }

    // ── Mobile sidebar toggle ──
    const sidebar   = document.getElementById('sidebar');
    const overlay   = document.getElementById('sidebar-overlay');
    const toggleBtn = document.getElementById('sidebar-toggle');

    function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
    toggleBtn?.addEventListener('click', () => {
        sidebar.classList.contains('-translate-x-full') ? openSidebar() : closeSidebar();
    });
</script>
</body>
</html>
