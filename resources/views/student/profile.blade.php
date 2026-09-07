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
<header class="fixed top-0 w-full z-50 bg-[#fff7fd] flex justify-between items-center px-6 lg:px-8 py-4 border-b border-[#cec3d0]/20 backdrop-blur-sm lg:pl-64 pl-0">
    <div class="flex items-center gap-4">
        <button id="sidebar-toggle" class="lg:hidden p-2 text-primary rounded-lg hover:bg-surface-container transition-colors" aria-label="Toggle menu">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <!-- Logo & Branding (Mobile only) -->
        <div class="lg:hidden flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center overflow-hidden">
                <img alt="University Logo" class="h-8 w-8 object-contain" src="{{ asset('images/BISU-Logo-1-150x150.png.webp') }}" />
            </div>
            <span class="text-xl font-headline font-semibold text-primary">OJT Portal</span>
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
    <!-- SideNavBar (Shared Component) -->
    @include('components.student-sidebar')

<!-- Sidebar overlay for mobile -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black/40 backdrop-blur-xs z-[55] hidden lg:hidden" onclick="closeSidebar()"></div>

<!-- ═══════════════════════════════
     MAIN CONTENT
═══════════════════════════════ -->
<main class="lg:ml-64 ml-0 pt-20 min-h-screen pb-24 lg:pb-8">
    <div class="p-5 lg:p-8 max-w-5xl mx-auto space-y-6">

        <!-- ── Profile Form ── -->
        <form method="POST" action="{{ route('student.profile.update') }}" id="profile-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Hidden Photo Input -->
            <input type="file" name="profile_photo" id="profile_photo_input" accept="image/*" class="hidden" onchange="previewProfilePhoto(this)">

            <!-- ── Page Header ── -->
            <header class="flex flex-col sm:flex-row sm:items-center justify-between gap-5 border-b border-surface-variant/30 pb-6 mb-6">
                <div class="flex items-center gap-5">
                    <!-- Avatar with Upload Button -->
                    <div class="relative flex-shrink-0 group cursor-pointer" onclick="triggerPhotoUpload()">
                        <div class="w-20 h-20 rounded-full bg-primary/10 border-4 border-surface-container-lowest shadow-lg flex items-center justify-center overflow-hidden relative">
                            <img id="avatar-preview-img" 
                                 src="{{ $user->studentProfile?->profile_photo_url ?? $user->avatar_url }}" 
                                 alt="Profile Picture" 
                                 class="w-full h-full object-cover"/>
                            
                            <!-- Hover Overlay -->
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white text-[10px] font-bold">
                                <span class="material-symbols-outlined text-lg">photo_camera</span>
                                <span>Upload</span>
                            </div>
                        </div>
                        <button type="button" class="absolute -bottom-1 -right-1 w-7 h-7 bg-primary group-hover:bg-purple-900 rounded-full flex items-center justify-center border-2 border-white shadow transition" aria-label="Change photo">
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
                <div class="flex items-center gap-3">
                    <button id="edit-btn" type="button" onclick="toggleEdit()"
                            class="self-start sm:self-auto flex items-center gap-2 px-5 py-2.5 bg-primary text-on-primary rounded-lg font-bold text-sm hover:opacity-90 active:scale-95 transition-all shadow-sm flex-shrink-0">
                        <span class="material-symbols-outlined text-lg">edit</span>
                        Edit Profile
                    </button>
                </div>
            </header>

            @if(session('success'))
                <div class="mb-6 p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 flex items-center gap-3 shadow-sm">
                    <span class="material-symbols-outlined">check_circle</span>
                    <p class="font-bold text-sm">{{ session('success') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 flex items-start gap-3 shadow-sm">
                    <span class="material-symbols-outlined mt-0.5">error</span>
                    <div>
                        <p class="font-bold text-sm mb-1">Please fix the following errors:</p>
                        <ul class="list-disc list-inside text-xs font-medium space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

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
                                <input type="text" name="first_name" value="{{ old('first_name', $user->studentProfile->first_name ?? '') }}" disabled
                                       class="profile-input w-full bg-surface-container rounded-lg px-3.5 py-2.5 text-sm font-medium text-on-surface border border-surface-variant/30 focus:ring-2 focus:ring-primary/40 focus:border-transparent transition-all disabled:opacity-60 disabled:cursor-not-allowed"/>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-outline uppercase tracking-widest mb-1.5">
                                    Middle Name <span class="text-error">*</span>
                                </label>
                                <input type="text" name="middle_name" value="{{ old('middle_name', $user->studentProfile->middle_name ?? '') }}" disabled
                                       class="profile-input w-full bg-surface-container rounded-lg px-3.5 py-2.5 text-sm font-medium text-on-surface border border-surface-variant/30 focus:ring-2 focus:ring-primary/40 focus:border-transparent transition-all disabled:opacity-60 disabled:cursor-not-allowed"/>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-outline uppercase tracking-widest mb-1.5">
                                    Last Name <span class="text-error">*</span>
                                </label>
                                <input type="text" name="last_name" value="{{ old('last_name', $user->studentProfile->last_name ?? '') }}" disabled
                                       class="profile-input w-full bg-surface-container rounded-lg px-3.5 py-2.5 text-sm font-medium text-on-surface border border-surface-variant/30 focus:ring-2 focus:ring-primary/40 focus:border-transparent transition-all disabled:opacity-60 disabled:cursor-not-allowed"/>
                            </div>
                        </div>
                        {{-- Row 2: Contact --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-outline uppercase tracking-widest mb-1.5">
                                    Contact Address <span class="text-error">*</span>
                                </label>
                                <input type="text" name="contact_address" value="{{ old('contact_address', $user->studentProfile->contact_address ?? '') }}" placeholder="Contact Address" disabled
                                       class="profile-input w-full bg-surface-container rounded-lg px-3.5 py-2.5 text-sm font-medium text-on-surface border border-surface-variant/30 focus:ring-2 focus:ring-primary/40 focus:border-transparent transition-all disabled:opacity-60 disabled:cursor-not-allowed"/>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-outline uppercase tracking-widest mb-1.5">
                                    Contact Number <span class="text-error">*</span>
                                </label>
                                <input type="tel" name="contact_number" value="{{ old('contact_number', $user->studentProfile->contact_number ?? '') }}" disabled
                                       class="profile-input w-full bg-surface-container rounded-lg px-3.5 py-2.5 text-sm font-medium text-on-surface border border-surface-variant/30 focus:ring-2 focus:ring-primary/40 focus:border-transparent transition-all disabled:opacity-60 disabled:cursor-not-allowed"/>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-outline uppercase tracking-widest mb-1.5">
                                    Email Address <span class="text-error">*</span>
                                </label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" disabled
                                       class="profile-input w-full bg-surface-container rounded-lg px-3.5 py-2.5 text-sm font-medium text-on-surface border border-surface-variant/30 focus:ring-2 focus:ring-primary/40 focus:border-transparent transition-all disabled:opacity-60 disabled:cursor-not-allowed"/>
                            </div>
                        </div>
                        {{-- Row 3: DOB & Blood Type --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-outline uppercase tracking-widest mb-1.5">
                                    Date of Birth
                                </label>
                                <div class="relative">
                                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->studentProfile->date_of_birth ?? '') }}" disabled
                                           class="profile-input w-full bg-surface-container rounded-lg px-3.5 py-2.5 pr-10 text-sm font-medium text-on-surface border border-surface-variant/30 focus:ring-2 focus:ring-primary/40 focus:border-transparent transition-all disabled:opacity-60 disabled:cursor-not-allowed"/>
                                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">calendar_today</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-outline uppercase tracking-widest mb-1.5">
                                    Blood Type
                                </label>
                                <input type="text" name="blood_type" value="{{ old('blood_type', $user->studentProfile->blood_type ?? '') }}" placeholder="e.g. O+, A+, B+, AB-" disabled
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
                                <input type="text" name="father_name" value="{{ old('father_name', $user->studentProfile->father_name ?? '') }}" placeholder="Father's Name" disabled
                                       class="profile-input w-full bg-surface-container rounded-lg px-3.5 py-2.5 text-sm font-medium text-on-surface border border-surface-variant/30 focus:ring-2 focus:ring-primary/40 focus:border-transparent transition-all disabled:opacity-60 disabled:cursor-not-allowed"/>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-outline uppercase tracking-widest mb-1.5">
                                    Mother's Name <span class="text-error">*</span>
                                </label>
                                <input type="text" name="mother_name" value="{{ old('mother_name', $user->studentProfile->mother_name ?? '') }}" placeholder="Mother's Name" disabled
                                       class="profile-input w-full bg-surface-container rounded-lg px-3.5 py-2.5 text-sm font-medium text-on-surface border border-surface-variant/30 focus:ring-2 focus:ring-primary/40 focus:border-transparent transition-all disabled:opacity-60 disabled:cursor-not-allowed"/>
                            </div>
                        </div>
                        {{-- Row 2: Emergency --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-outline uppercase tracking-widest mb-1.5">
                                    Emergency Contact Person <span class="text-error">*</span>
                                </label>
                                <input type="text" name="emergency_contact_person" value="{{ old('emergency_contact_person', $user->studentProfile->emergency_contact_person ?? '') }}" placeholder="Emergency contact person" disabled
                                       class="profile-input w-full bg-surface-container rounded-lg px-3.5 py-2.5 text-sm font-medium text-on-surface border border-surface-variant/30 focus:ring-2 focus:ring-primary/40 focus:border-transparent transition-all disabled:opacity-60 disabled:cursor-not-allowed"/>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-outline uppercase tracking-widest mb-1.5">
                                    Emergency Contact Number <span class="text-error">*</span>
                                </label>
                                <input type="tel" name="emergency_contact_number" value="{{ old('emergency_contact_number', $user->studentProfile->emergency_contact_number ?? '') }}" placeholder="Emergency contact number" disabled
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
                                <input type="text" name="student_id" value="{{ $user->studentProfile->student_id_number ?? '' }}" readonly
                                       class="w-full bg-slate-50 text-gray-500 rounded-lg px-3.5 py-2.5 text-sm font-medium border border-surface-variant/30 cursor-not-allowed"/>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-outline uppercase tracking-widest mb-1.5">
                                    Company <span class="text-error">*</span>
                                </label>
                                <input type="text" name="company" value="{{ $user->studentProfile->company->name ?? 'None' }}" readonly
                                        class="w-full bg-slate-50 text-gray-500 rounded-lg px-3.5 py-2.5 text-sm font-medium border border-surface-variant/30 cursor-not-allowed"/>
                            </div>
                        </div>
                        {{-- Row 2: Internship Start Date --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-outline uppercase tracking-widest mb-1.5">
                                    Internship Start <span class="text-error">*</span>
                                </label>
                                <div class="relative">
                                    <input type="date" name="internship_start" value="{{ $user->studentProfile->internship_start ?? '' }}" readonly
                                           class="w-full bg-slate-50 text-gray-500 rounded-lg px-3.5 py-2.5 pr-10 text-sm font-medium border border-surface-variant/30 cursor-not-allowed"/>
                                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">calendar_today</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-outline uppercase tracking-widest mb-1.5">
                                    Course / Program <span class="text-error">*</span>
                                </label>
                                <div class="relative">
                                    <select name="course" disabled
                                            class="profile-input w-full bg-surface-container rounded-lg px-3.5 py-2.5 text-sm font-medium text-on-surface border border-surface-variant/30 focus:ring-2 focus:ring-primary/40 focus:border-transparent transition-all disabled:opacity-60 disabled:cursor-not-allowed appearance-none">
                                        <option value="">Select a Course</option>
                                        @foreach($courses as $courseItem)
                                            <option value="{{ $courseItem->course_name }}" {{ (old('course', $user->studentProfile->course ?? '') == $courseItem->course_name) ? 'selected' : '' }}>
                                                {{ $courseItem->course_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">expand_more</span>
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
@include('components.student-bottom-nav')

<script>
    // ── Profile Photo Upload & Preview ──
    function triggerPhotoUpload() {
        document.getElementById('profile_photo_input').click();
    }

    function previewProfilePhoto(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // Check file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Selected image exceeds 5MB limit. Please choose a smaller image.');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview-img').src = e.target.result;
            };
            reader.readAsDataURL(file);

            // Automatically reveal the Update Profile button so the user can save the photo
            const saveBtn = document.getElementById('update-btn');
            const inputs  = document.querySelectorAll('.profile-input');
            if (inputs[0].disabled) {
                toggleEdit();
            }
        }
    }

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

    // Auto-open edit mode if there are validation errors
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', () => {
            toggleEdit();
        });
    @endif
</script>
</body>
</html>
