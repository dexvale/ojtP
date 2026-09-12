<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Account Profile & Security | OJT Portal</title>
    <meta name="description" content="Manage your account profile, contact details, and security settings on the OJT Management Portal."/>
    
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
        .active-tab-btn {
            background-color: #300050 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(48, 0, 80, 0.25);
        }
    </style>
</head>
<body class="bg-[#faf7fb] font-body text-slate-800 antialiased min-h-screen">

    <!-- ═══════════════════════════════
         SIDEBAR (Role-aware)
    ═══════════════════════════════ -->
    @if($user->role === 'Advisor')
        @include('components.supervisor-sidebar')
    @elseif($user->role === 'Student')
        @include('components.student-sidebar')
    @else
        @include('components.coordinator-sidebar')
    @endif

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 backdrop-blur-xs z-40 hidden lg:hidden" onclick="closeSidebar()"></div>

    <!-- ═══════════════════════════════
         TOP NAVBAR / HEADER
    ═══════════════════════════════ -->
    <header class="fixed top-0 w-full z-30 bg-white/90 backdrop-blur-md border-b border-slate-100 lg:pl-64 pl-0 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <button type="button" onclick="toggleSidebar()" class="lg:hidden p-2 text-slate-600 hover:text-[#300050] hover:bg-purple-50 rounded-xl transition-colors" aria-label="Open sidebar">
                    <span class="material-symbols-outlined text-2xl">menu</span>
                </button>
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-purple-600"></span>
                    <span class="text-sm font-bold text-slate-700 uppercase tracking-wider">Account Settings</span>
                </div>
            </div>

            <!-- Header Right Menu -->
            <div class="flex items-center gap-3">
                <div class="relative flex items-center sm:pl-3 sm:border-l sm:border-slate-200" id="user-profile-menu">
                    <button type="button" id="user-menu-btn" class="flex items-center gap-2.5 p-1 rounded-full hover:bg-slate-100 transition-colors focus:outline-none cursor-pointer" aria-expanded="false">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold text-slate-800 truncate max-w-[150px]">{{ $user->display_name }}</p>
                            <p class="text-[10px] uppercase font-bold text-purple-700 tracking-wider">{{ $user->display_role }}</p>
                        </div>
                        <img alt="User avatar" class="w-8 h-8 rounded-full object-cover ring-2 ring-purple-100" src="{{ $user->avatar_url }}" id="nav-avatar-img">
                    </button>
                    <!-- Dropdown Menu -->
                    <div id="user-menu-dropdown" class="hidden absolute right-0 top-full mt-2 w-52 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 transition-all">
                        <div class="px-4 py-2 border-b border-slate-100">
                            <p class="text-xs font-bold text-slate-800 truncate">{{ $user->display_name }}</p>
                            <p class="text-[10px] text-purple-700 font-semibold truncate">{{ $user->display_role }}</p>
                        </div>
                        <a href="{{ route('account.profile') }}" class="w-full text-left px-4 py-2 text-xs text-slate-700 hover:bg-purple-50 hover:text-purple-900 flex items-center gap-2.5 font-semibold transition-colors">
                            <span class="material-symbols-outlined text-[18px] text-purple-600">person</span>
                            <span>Account Profile</span>
                        </a>
                        <button type="button" onclick="switchTab('security')" class="w-full text-left px-4 py-2 text-xs text-slate-700 hover:bg-purple-50 hover:text-purple-900 flex items-center gap-2.5 font-semibold transition-colors cursor-pointer">
                            <span class="material-symbols-outlined text-[18px] text-purple-600">lock_reset</span>
                            <span>Change Password</span>
                        </button>
                        <div class="border-t border-slate-100 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-xs text-rose-600 hover:bg-rose-50 flex items-center gap-2.5 font-semibold transition-colors cursor-pointer">
                                <span class="material-symbols-outlined text-[18px]">logout</span>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- ═══════════════════════════════
         MAIN CONTENT
    ═══════════════════════════════ -->
    <main class="lg:ml-64 ml-0 pt-20 pb-16 px-4 sm:px-6 lg:px-8 min-h-screen">
        <div class="max-w-4xl mx-auto space-y-6">

            <!-- Hero Banner & Welcome Card -->
            <div class="bg-gradient-to-r from-[#300050] to-[#511378] rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-purple-950/10 relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5 relative z-10">
                    <div class="flex items-center gap-4 sm:gap-5">
                        <div class="relative group cursor-pointer" onclick="document.getElementById('profile_photo_input').click()" title="Click to change photo">
                            <div class="w-20 h-20 sm:w-22 sm:h-22 rounded-2xl overflow-hidden ring-4 ring-white/20 shadow-inner bg-white/10 flex items-center justify-center">
                                <img id="hero-avatar-preview" src="{{ $user->avatar_url }}" alt="{{ $user->display_name }}" class="w-full h-full object-cover">
                            </div>
                            <div class="absolute inset-0 bg-black/40 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white text-[10px] font-bold">
                                <span class="material-symbols-outlined text-lg">photo_camera</span>
                                <span>Change</span>
                            </div>
                        </div>
                        <div>
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md text-[11px] font-bold text-purple-200 uppercase tracking-wider mb-2">
                                <span class="material-symbols-outlined text-[14px]">verified</span>
                                {{ $user->display_role }}
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold font-headline tracking-tight">{{ $user->display_name }}</h1>
                            <p class="text-xs sm:text-sm text-purple-200/90 font-medium mt-0.5 flex items-center gap-2">
                                <span>{{ $user->email }}</span>
                                @if($user->department)
                                    <span>•</span>
                                    <span>{{ $user->department }}</span>
                                @elseif($user->role === 'Advisor' && $user->company)
                                    <span>•</span>
                                    <span>{{ $user->company->name }}</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Quick stats / Meta -->
                    <div class="bg-white/10 backdrop-blur-md px-4 py-3 rounded-2xl border border-white/10 text-right sm:text-right w-full sm:w-auto">
                        <span class="block text-[10px] uppercase font-bold text-purple-200 tracking-wider">Member Since</span>
                        <span class="text-xs font-bold text-white">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'Active Member' }}</span>
                    </div>
                </div>
            </div>

            <!-- Tab Switcher Navigation -->
            <div class="flex items-center gap-2 p-1.5 bg-slate-200/60 rounded-2xl max-w-md">
                <button type="button" id="tab-btn-profile" onclick="switchTab('profile')" 
                        class="flex-1 py-2.5 px-4 rounded-xl text-xs sm:text-sm font-bold flex items-center justify-center gap-2 transition-all cursor-pointer active-tab-btn">
                    <span class="material-symbols-outlined text-[18px]">person</span>
                    <span>Profile Info</span>
                </button>
                <button type="button" id="tab-btn-security" onclick="switchTab('security')" 
                        class="flex-1 py-2.5 px-4 rounded-xl text-xs sm:text-sm font-bold flex items-center justify-center gap-2 transition-all cursor-pointer text-slate-600 hover:text-slate-900">
                    <span class="material-symbols-outlined text-[18px]">lock_reset</span>
                    <span>Change Password</span>
                </button>
            </div>

            <!-- Flash Notifications -->
            @if(session('profile_success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl flex items-center gap-3 shadow-xs">
                    <span class="material-symbols-outlined text-emerald-600 text-2xl">check_circle</span>
                    <div>
                        <p class="text-sm font-bold">Profile Updated</p>
                        <p class="text-xs text-emerald-700">{{ session('profile_success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('password_success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl flex items-center gap-3 shadow-xs">
                    <span class="material-symbols-outlined text-emerald-600 text-2xl">verified_user</span>
                    <div>
                        <p class="text-sm font-bold">Security Updated</p>
                        <p class="text-xs text-emerald-700">{{ session('password_success') }}</p>
                    </div>
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-2xl shadow-xs">
                    <div class="flex items-center gap-2 font-bold text-sm mb-1 text-rose-900">
                        <span class="material-symbols-outlined text-rose-600">error</span>
                        <span>Please correct the following errors:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs text-rose-700 space-y-1 ml-6">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- ═══════════════════════════════
                 TAB 1: PROFILE INFORMATION
            ═══════════════════════════════ -->
            <div id="tab-pane-profile" class="space-y-6">
                <form method="POST" action="{{ route('account.profile.update') }}" enctype="multipart/form-data" class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                    @csrf
                    @method('PUT')

                    <!-- Hidden Avatar File Input -->
                    <input type="file" name="profile_photo" id="profile_photo_input" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" onchange="handlePhotoSelect(this)">

                    <div class="p-6 sm:p-8 space-y-6">
                        <div class="border-b border-slate-100 pb-4">
                            <h2 class="text-lg font-bold text-slate-800 font-headline">Personal & Contact Information</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Update your public display name, phone number, and institution/company assignment.</p>
                        </div>

                        <!-- Avatar Upload Box -->
                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5 p-4 rounded-2xl bg-purple-50/50 border border-purple-100/70">
                            <div class="relative group cursor-pointer flex-shrink-0" onclick="document.getElementById('profile_photo_input').click()">
                                <img id="card-avatar-preview" src="{{ $user->avatar_url }}" alt="Profile photo" class="w-20 h-20 rounded-2xl object-cover ring-2 ring-purple-200 shadow-xs">
                                <div class="absolute inset-0 bg-black/40 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                    <span class="material-symbols-outlined text-xl">photo_camera</span>
                                </div>
                            </div>
                            <div class="flex-1 text-center sm:text-left space-y-2">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-purple-950">Profile Picture</h3>
                                <p class="text-xs text-slate-600">Upload a professional headshot or photo. Recommended size is at least 300×300px (JPG, PNG, or WEBP, max 4MB).</p>
                                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2.5 pt-1">
                                    <button type="button" onclick="document.getElementById('profile_photo_input').click()" 
                                            class="px-3.5 py-1.5 bg-[#300050] hover:bg-[#430270] text-white rounded-xl text-xs font-bold transition-all shadow-xs cursor-pointer flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[16px]">upload</span>
                                        <span>Choose Photo</span>
                                    </button>
                                    @if($user->profile_photo_path)
                                        <label class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 text-rose-700 hover:bg-rose-100 rounded-xl text-xs font-bold transition-colors cursor-pointer border border-rose-200">
                                            <input type="checkbox" name="remove_photo" value="1" class="rounded text-rose-600 focus:ring-rose-500">
                                            <span>Remove custom avatar</span>
                                        </label>
                                    @endif
                                    <span id="photo-filename" class="text-[11px] text-slate-500 italic hidden truncate max-w-xs"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Form Inputs Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Full Name <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none">badge</span>
                                    <input type="text" name="name" id="name" required value="{{ old('name', $user->name) }}" 
                                           placeholder="e.g. Dr. Maria Santos"
                                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#300050]/20 focus:border-[#300050] transition">
                                </div>
                            </div>

                            <!-- Email (Locked) -->
                            <div>
                                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Email Address <span class="text-slate-400 font-normal">(Non-editable)</span>
                                </label>
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none">mail</span>
                                    <input type="email" id="email" value="{{ $user->email }}" disabled
                                           class="w-full pl-10 pr-10 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm font-medium text-slate-500 cursor-not-allowed">
                                    <span class="material-symbols-outlined absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg" title="Email is tied to your institutional account">lock</span>
                                </div>
                            </div>

                            <!-- Contact Number -->
                            <div>
                                <label for="contact_number" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Contact / Mobile Number
                                </label>
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none">call</span>
                                    <input type="text" name="contact_number" id="contact_number" value="{{ old('contact_number', $user->contact_number) }}" 
                                           placeholder="e.g. +63 912 345 6789"
                                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#300050]/20 focus:border-[#300050] transition">
                                </div>
                            </div>

                            <!-- Department / Division -->
                            <div>
                                <label for="department" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Department / Office
                                </label>
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none">domain</span>
                                    <input type="text" name="department" id="department" value="{{ old('department', $user->department) }}" 
                                           placeholder="{{ $user->role === 'Advisor' ? 'e.g. IT Solutions Division' : 'e.g. Department of Computer Studies' }}"
                                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#300050]/20 focus:border-[#300050] transition">
                                </div>
                            </div>
                        </div>

                        <!-- Role-Specific Context Boxes -->
                        @if($user->role === 'Coordinator')
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                                <div class="flex items-center gap-2 text-xs font-bold text-slate-700 uppercase tracking-wider">
                                    <span class="material-symbols-outlined text-purple-700 text-sm">school</span>
                                    <span>Assigned Academic Programs / Courses</span>
                                </div>
                                <p class="text-xs text-slate-500">You are registered as the OJT coordinator for the following degree programs:</p>
                                <div class="flex flex-wrap gap-2 pt-1">
                                    @forelse($user->managedCourses as $course)
                                        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-white border border-purple-200 text-purple-900 text-xs font-bold rounded-xl shadow-xs hover:border-purple-300 transition-colors">
                                            <span class="material-symbols-outlined text-[16px] text-purple-600">school</span>
                                            <span>{{ $course->course_name }}</span>
                                            @if($course->required_hours)
                                                <span class="px-2 py-0.5 rounded-lg bg-purple-100/70 text-[10px] text-purple-800 font-semibold font-mono">{{ $course->required_hours }} hrs</span>
                                            @endif
                                        </span>
                                    @empty
                                        <span class="text-xs italic text-slate-400">No courses assigned yet. Contact Dean/Admin to assign courses.</span>
                                    @endforelse
                                </div>
                            </div>
                        @elseif($user->role === 'Advisor')
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                                <div class="flex items-center gap-2 text-xs font-bold text-slate-700 uppercase tracking-wider">
                                    <span class="material-symbols-outlined text-purple-700 text-sm">business</span>
                                    <span>Affiliated Host Training Establishment (HTE)</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">{{ $user->company->name ?? 'Company Not Linked' }}</p>
                                        <p class="text-xs text-slate-500">{{ $user->company->address ?? 'No physical address specified' }}</p>
                                    </div>
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-[11px] font-bold rounded-full">Partner Company</span>
                                </div>
                            </div>
                        @elseif($user->role === 'Admin')
                            <div class="p-4 rounded-2xl bg-purple-50/60 border border-purple-100 space-y-1">
                                <div class="flex items-center gap-2 text-xs font-bold text-purple-900 uppercase tracking-wider">
                                    <span class="material-symbols-outlined text-purple-700 text-sm">admin_panel_settings</span>
                                    <span>Administrator Privileges</span>
                                </div>
                                <p class="text-xs text-slate-600">As Dean / System Administrator, you have full campus oversight for academic terms, program coordinators, and company endorsements.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Footer Action Bar -->
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                        <button type="submit" class="px-6 py-2.5 bg-[#300050] hover:bg-[#430270] text-white rounded-xl text-sm font-bold shadow-md shadow-purple-950/10 transition-all hover:scale-[1.01] active:scale-[0.99] cursor-pointer flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg">save</span>
                            <span>Save Profile Changes</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- ═══════════════════════════════
                 TAB 2: SECURITY & PASSWORD CHANGE
            ═══════════════════════════════ -->
            <div id="tab-pane-security" class="space-y-6 hidden">
                <form method="POST" action="{{ route('account.password.update') }}" class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                    @csrf
                    @method('PUT')

                    <div class="p-6 sm:p-8 space-y-6">
                        <div class="border-b border-slate-100 pb-4">
                            <h2 class="text-lg font-bold text-slate-800 font-headline">Change Account Password</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Ensure your account is using a strong password with at least 8 characters.</p>
                        </div>

                        <!-- Current Password -->
                        <div>
                            <label for="current_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Current Password <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative max-w-md">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none">key</span>
                                <input type="password" name="current_password" id="current_password" required
                                       placeholder="Enter current password"
                                       class="w-full pl-10 pr-11 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#300050]/20 focus:border-[#300050] transition">
                                <button type="button" onclick="togglePasswordVisibility('current_password', 'current_eye')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer">
                                    <span class="material-symbols-outlined text-lg" id="current_eye">visibility</span>
                                </button>
                            </div>
                        </div>

                        <!-- New Password -->
                        <div>
                            <label for="new_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                New Password <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative max-w-md">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none">lock</span>
                                <input type="password" name="password" id="new_password" required minlength="8"
                                       placeholder="Minimum 8 characters"
                                       oninput="checkPasswordStrength(this.value)"
                                       class="w-full pl-10 pr-11 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#300050]/20 focus:border-[#300050] transition">
                                <button type="button" onclick="togglePasswordVisibility('new_password', 'new_eye')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer">
                                    <span class="material-symbols-outlined text-lg" id="new_eye">visibility</span>
                                </button>
                            </div>

                            <!-- Password strength meter -->
                            <div class="max-w-md mt-2 space-y-1">
                                <div class="h-1.5 w-full bg-slate-200 rounded-full overflow-hidden">
                                    <div id="strength-bar" class="h-full w-0 transition-all duration-300 bg-rose-500"></div>
                                </div>
                                <div class="flex items-center justify-between text-[11px] text-slate-500">
                                    <span>Password strength: <strong id="strength-label" class="text-slate-700">None</strong></span>
                                    <span>Min 8 characters</span>
                                </div>
                            </div>
                        </div>

                        <!-- Confirm New Password -->
                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Confirm New Password <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative max-w-md">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none">check_circle</span>
                                <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8"
                                       placeholder="Repeat new password"
                                       oninput="validateMatch()"
                                       class="w-full pl-10 pr-11 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#300050]/20 focus:border-[#300050] transition">
                                <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'confirm_eye')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer">
                                    <span class="material-symbols-outlined text-lg" id="confirm_eye">visibility</span>
                                </button>
                            </div>
                            <p id="match-hint" class="text-[11px] mt-1 font-semibold hidden"></p>
                        </div>

                        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs space-y-1 max-w-md">
                            <div class="flex items-center gap-1.5 font-bold">
                                <span class="material-symbols-outlined text-amber-600 text-base">info</span>
                                <span>Security Recommendation</span>
                            </div>
                            <p class="text-amber-800/90 leading-relaxed">
                                Use a unique password not shared with other systems. Never share your password or temporary coordinator credentials with anyone.
                            </p>
                        </div>
                    </div>

                    <!-- Footer Action Bar -->
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                        <button type="submit" class="px-6 py-2.5 bg-[#300050] hover:bg-[#430270] text-white rounded-xl text-sm font-bold shadow-md shadow-purple-950/10 transition-all hover:scale-[1.01] active:scale-[0.99] cursor-pointer flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg">lock_reset</span>
                            <span>Update Password</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </main>

    <!-- ═══════════════════════════════
         INTERACTIVITY SCRIPTS
    ═══════════════════════════════ -->
    <script>
        // Sidebar Mobile Toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (sidebar) {
                sidebar.classList.toggle('-translate-x-full');
            }
            if (overlay) {
                overlay.classList.toggle('hidden');
            }
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (sidebar) sidebar.classList.add('-translate-x-full');
            if (overlay) overlay.classList.add('hidden');
        }

        // User Header Menu Dropdown Toggle
        const userMenuBtn = document.getElementById('user-menu-btn');
        const userMenuDropdown = document.getElementById('user-menu-dropdown');
        if (userMenuBtn && userMenuDropdown) {
            userMenuBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                userMenuDropdown.classList.toggle('hidden');
            });
            document.addEventListener('click', (e) => {
                if (!userMenuBtn.contains(e.target) && !userMenuDropdown.contains(e.target)) {
                    userMenuDropdown.classList.add('hidden');
                }
            });
        }

        // Tab Switching Logic
        function switchTab(tab) {
            const profilePane = document.getElementById('tab-pane-profile');
            const securityPane = document.getElementById('tab-pane-security');
            const profileBtn = document.getElementById('tab-btn-profile');
            const securityBtn = document.getElementById('tab-btn-security');

            if (tab === 'security') {
                profilePane.classList.add('hidden');
                securityPane.classList.remove('hidden');

                profileBtn.classList.remove('active-tab-btn');
                profileBtn.classList.add('text-slate-600', 'hover:text-slate-900');

                securityBtn.classList.add('active-tab-btn');
                securityBtn.classList.remove('text-slate-600', 'hover:text-slate-900');
                
                window.location.hash = 'security';
            } else {
                securityPane.classList.add('hidden');
                profilePane.classList.remove('hidden');

                securityBtn.classList.remove('active-tab-btn');
                securityBtn.classList.add('text-slate-600', 'hover:text-slate-900');

                profileBtn.classList.add('active-tab-btn');
                profileBtn.classList.remove('text-slate-600', 'hover:text-slate-900');

                if (window.location.hash === '#security') {
                    history.replaceState(null, null, ' ');
                }
            }
        }

        // Auto switch tab if URL hash is #security or session active_tab is security
        document.addEventListener('DOMContentLoaded', () => {
            if (window.location.hash === '#security' || @json(session('active_tab') === 'security' || (isset($errors) && ($errors->has('current_password') || $errors->has('password'))))) {
                switchTab('security');
            }
        });

        // Photo Upload Preview
        function handlePhotoSelect(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const filenameLabel = document.getElementById('photo-filename');
                if (filenameLabel) {
                    filenameLabel.textContent = file.name;
                    filenameLabel.classList.remove('hidden');
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const heroAvatar = document.getElementById('hero-avatar-preview');
                    const cardAvatar = document.getElementById('card-avatar-preview');
                    const navAvatar = document.getElementById('nav-avatar-img');
                    if (heroAvatar) heroAvatar.src = e.target.result;
                    if (cardAvatar) cardAvatar.src = e.target.result;
                    if (navAvatar) navAvatar.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }

        // Password Show/Hide Toggle
        function togglePasswordVisibility(fieldId, iconId) {
            const input = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);
            if (!input || !icon) return;

            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility';
            }
        }

        // Realtime Password Strength Indicator
        function checkPasswordStrength(password) {
            const bar = document.getElementById('strength-bar');
            const label = document.getElementById('strength-label');
            if (!bar || !label) return;

            let score = 0;
            if (password.length >= 8) score++;
            if (/[A-Z]/.test(password)) score++;
            if (/[0-9]/.test(password)) score++;
            if (/[^A-Za-z0-9]/.test(password)) score++;

            if (password.length === 0) {
                bar.style.width = '0%';
                bar.className = 'h-full w-0 transition-all duration-300 bg-slate-300';
                label.textContent = 'None';
                label.className = 'text-slate-700';
            } else if (password.length < 8) {
                bar.style.width = '25%';
                bar.className = 'h-full transition-all duration-300 bg-rose-500';
                label.textContent = 'Too Short (Min 8 chars)';
                label.className = 'text-rose-600 font-bold';
            } else if (score <= 2) {
                bar.style.width = '50%';
                bar.className = 'h-full transition-all duration-300 bg-amber-500';
                label.textContent = 'Fair';
                label.className = 'text-amber-600 font-bold';
            } else if (score === 3) {
                bar.style.width = '75%';
                bar.className = 'h-full transition-all duration-300 bg-blue-500';
                label.textContent = 'Good';
                label.className = 'text-blue-600 font-bold';
            } else {
                bar.style.width = '100%';
                bar.className = 'h-full transition-all duration-300 bg-emerald-500';
                label.textContent = 'Strong';
                label.className = 'text-emerald-600 font-bold';
            }

            validateMatch();
        }

        function validateMatch() {
            const newPass = document.getElementById('new_password');
            const confirmPass = document.getElementById('password_confirmation');
            const hint = document.getElementById('match-hint');
            if (!newPass || !confirmPass || !hint) return;

            if (confirmPass.value.length === 0) {
                hint.classList.add('hidden');
                return;
            }

            hint.classList.remove('hidden');
            if (newPass.value === confirmPass.value) {
                hint.textContent = '✓ Passwords match';
                hint.className = 'text-[11px] mt-1 font-semibold text-emerald-600';
            } else {
                hint.textContent = '✗ Passwords do not match';
                hint.className = 'text-[11px] mt-1 font-semibold text-rose-600';
            }
        }
    </script>
</body>
</html>
