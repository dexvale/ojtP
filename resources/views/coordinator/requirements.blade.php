<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>OJT Portal | Manage Requirements</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link
        href="https://fonts.googleapis.com/css2?family=Newsreader:opsz,wght@6..72,400;6..72,600;6..72,700;6..72,800&family=Public+Sans:wght@400;500;600;700&display=swap"
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
            vertical-align: middle;
        }
    </style>
</head>

<body class="bg-surface text-on-surface" data-theme="portal">
    <!-- SideNavBar (Shared Component) -->
    @include('components.coordinator-sidebar')

    <!-- TopNavBar -->
    <header class="fixed top-0 left-64 right-0 z-40 bg-surface/90 backdrop-blur-sm border-b border-[#cec3d0]/15">
        <div class="flex justify-between items-center px-8 py-4 w-full">
            <div class="flex items-center gap-8">
                <span class="text-2xl font-headline font-semibold text-primary tracking-tight">OJT Management</span>
                <nav class="hidden md:flex items-center gap-6">
                    <a class="text-sm font-semibold text-on-surface/60 hover:text-primary transition-colors duration-200" href="{{ route('coordinator.dashboard') }}">Dashboard</a>
                    <a class="text-sm font-semibold text-on-surface/60 hover:text-primary transition-colors duration-200" href="{{ route('coordinator.students') }}">Student List</a>
                    <a class="text-sm font-semibold text-on-surface/60 hover:text-primary transition-colors duration-200" href="{{ route('coordinator.companies') }}">Company Directory</a>
                    <a class="text-sm font-semibold text-on-surface/60 hover:text-primary transition-colors duration-200" href="{{ route('coordinator.reports') }}">Reports</a>
                </nav>
            </div>
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-3">
                    <button class="p-2 text-primary hover:bg-black/5 rounded-full transition-all active:scale-95">
                        <span class="material-symbols-outlined" data-icon="notifications">notifications</span>
                    </button>
                    <div class="flex items-center gap-3 pl-2 border-l border-[#cec3d0]/30">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold font-headline text-primary">Dr. Elena Vance</p>
                            <p class="text-[10px] uppercase tracking-wider text-secondary font-bold">OJT Coordinator</p>
                        </div>
                        <img alt="User profile avatar"
                            class="w-9 h-9 rounded-full object-cover ring-2 ring-primary/10"
                            src="https://ui-avatars.com/api/?name=Elena+Vance&background=3a0ca3&color=fff">
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="ml-64 pt-24 px-8 pb-12 min-h-screen border-none">
        
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-extrabold font-headline text-slate-900 tracking-tight">OJT Requirements</h1>
            <p class="text-slate-500 font-medium mt-1 text-sm">Define required forms, upload template documents, and verify student submissions.</p>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-xl mb-6 shadow-sm flex items-center gap-3">
                <span class="material-symbols-outlined text-emerald-600 text-[20px]">check_circle</span>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-xl mb-6 shadow-sm">
                <div class="flex items-center gap-3 mb-2">
                    <span class="material-symbols-outlined text-rose-600 text-[20px]">error</span>
                    <span class="text-sm font-bold">Please correct the following errors:</span>
                </div>
                <ul class="list-disc list-inside text-xs space-y-1 ml-6">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">
            
            <!-- LEFT AREA: Submissions & Templates Listing (8 Columns) -->
            <div class="xl:col-span-8 space-y-8">
                
                <!-- Submissions Queue Section -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-xl font-bold font-headline text-slate-800 mb-5 flex items-center gap-2">
                        <span class="material-symbols-outlined text-purple-600">assignment_turned_in</span>
                        Student Document Submissions
                    </h2>

                    <!-- Filter Tabs -->
                    <div class="flex border-b border-slate-200 mb-6">
                        <button onclick="switchTab('pending-tab', 'pending-content')" id="pending-tab"
                                class="tab-btn px-4 py-2 text-sm font-bold border-b-2 border-purple-600 text-purple-600 focus:outline-none transition-all">
                            Pending Verification ({{ $submissions->where('status', 'Pending')->count() }})
                        </button>
                        <button onclick="switchTab('approved-tab', 'approved-content')" id="approved-tab"
                                class="tab-btn px-4 py-2 text-sm font-medium text-slate-500 hover:text-slate-700 border-b-2 border-transparent focus:outline-none transition-all">
                            Approved ({{ $submissions->where('status', 'Approved')->count() }})
                        </button>
                        <button onclick="switchTab('rejected-tab', 'rejected-content')" id="rejected-tab"
                                class="tab-btn px-4 py-2 text-sm font-medium text-slate-500 hover:text-slate-700 border-b-2 border-transparent focus:outline-none transition-all">
                            Revision Required ({{ $submissions->where('status', 'Rejected')->count() }})
                        </button>
                    </div>

                    <!-- PENDING CONTENT -->
                    <div id="pending-content" class="tab-content block">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm border-collapse">
                                <thead>
                                    <tr class="border-b border-slate-100 text-slate-400 font-semibold text-xs uppercase bg-slate-50/50">
                                        <th class="py-3 px-4">Student</th>
                                        <th class="py-3 px-4">Requirement</th>
                                        <th class="py-3 px-4">Submitted File</th>
                                        <th class="py-3 px-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($submissions->where('status', 'Pending') as $sub)
                                        <tr class="border-b border-slate-100 hover:bg-slate-50/30 transition-colors">
                                            <td class="py-4 px-4">
                                                <div class="font-bold text-slate-800">{{ $sub->user->studentProfile->first_name ?? 'N/A' }} {{ $sub->user->studentProfile->last_name ?? '' }}</div>
                                                <div class="text-[10px] text-slate-400 font-medium">{{ $sub->user->studentProfile->course ?? '' }}</div>
                                            </td>
                                            <td class="py-4 px-4 font-semibold text-purple-950">{{ $sub->requirement->title ?? 'Unknown Requirement' }}</td>
                                            <td class="py-4 px-4">
                                                <button onclick="openReviewModal({{ $sub->id }}, '{{ asset('storage/' . $sub->file_path) }}')"
                                                   class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-50 hover:bg-purple-100 border border-purple-100 rounded-lg text-purple-700 text-xs font-bold transition shadow-sm">
                                                    <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span>
                                                    Review Document
                                                </button>
                                            </td>
                                            <td class="py-4 px-4 text-right space-x-2">
                                                <form action="{{ route('coordinator.submissions.approve', $sub->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="p-1.5 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-700 rounded-lg transition" title="Approve Submission">
                                                        <span class="material-symbols-outlined text-base">check</span>
                                                    </button>
                                                </form>
                                                <button onclick="openRejectModal({{ $sub->id }})" class="p-1.5 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 rounded-lg transition" title="Reject / Request Revision">
                                                    <span class="material-symbols-outlined text-base">close</span>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-12 text-center text-slate-400 font-medium italic">No pending submissions to verify.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- APPROVED CONTENT -->
                    <div id="approved-content" class="tab-content hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm border-collapse">
                                <thead>
                                    <tr class="border-b border-slate-100 text-slate-400 font-semibold text-xs uppercase bg-slate-50/50">
                                        <th class="py-3 px-4">Student</th>
                                        <th class="py-3 px-4">Requirement</th>
                                        <th class="py-3 px-4">Submitted File</th>
                                        <th class="py-3 px-4">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($submissions->where('status', 'Approved') as $sub)
                                        <tr class="border-b border-slate-100 hover:bg-slate-50/30 transition-colors">
                                            <td class="py-4 px-4">
                                                <div class="font-bold text-slate-800">{{ $sub->user->studentProfile->first_name ?? 'N/A' }} {{ $sub->user->studentProfile->last_name ?? '' }}</div>
                                                <div class="text-[10px] text-slate-400 font-medium">{{ $sub->user->studentProfile->course ?? '' }}</div>
                                            </td>
                                            <td class="py-4 px-4 font-semibold text-[#300050]">{{ $sub->requirement->title }}</td>
                                            <td class="py-4 px-4">
                                                <a href="{{ asset('storage/' . $sub->file_path) }}" target="_blank"
                                                   class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg text-slate-600 text-xs font-bold transition">
                                                    <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span>
                                                    View File
                                                </a>
                                            </td>
                                            <td class="py-4 px-4">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-bold uppercase tracking-wider rounded-full shadow-sm">
                                                    <span class="material-symbols-outlined text-[10px] font-bold">check</span> Verified
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-12 text-center text-slate-400 font-medium italic">No verified documents yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- REJECTED CONTENT -->
                    <div id="rejected-content" class="tab-content hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm border-collapse">
                                <thead>
                                    <tr class="border-b border-slate-100 text-slate-400 font-semibold text-xs uppercase bg-slate-50/50">
                                        <th class="py-3 px-4">Student</th>
                                        <th class="py-3 px-4">Requirement</th>
                                        <th class="py-3 px-4">File & Remarks</th>
                                        <th class="py-3 px-4 text-right">Re-eval Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($submissions->where('status', 'Rejected') as $sub)
                                        <tr class="border-b border-slate-100 hover:bg-slate-50/30 transition-colors">
                                            <td class="py-4 px-4">
                                                <div class="font-bold text-slate-800">{{ $sub->user->studentProfile->first_name ?? 'N/A' }} {{ $sub->user->studentProfile->last_name ?? '' }}</div>
                                                <div class="text-[10px] text-slate-400 font-medium">{{ $sub->user->studentProfile->course ?? '' }}</div>
                                            </td>
                                            <td class="py-4 px-4 font-semibold text-slate-700">{{ $sub->requirement->title }}</td>
                                            <td class="py-4 px-4 max-w-xs">
                                                <a href="{{ asset('storage/' . $sub->file_path) }}" target="_blank"
                                                   class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg text-slate-600 text-xs font-bold transition mb-2">
                                                    <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span>
                                                    Rejected File
                                                </a>
                                                <div class="bg-rose-50 text-rose-900 border border-rose-100 text-xs p-2 rounded-lg italic">
                                                    <strong>Remarks:</strong> {{ $sub->remarks }}
                                                </div>
                                            </td>
                                            <td class="py-4 px-4 text-right">
                                                <form action="{{ route('coordinator.submissions.approve', $sub->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition" title="Override and Approve">
                                                        Approve
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-12 text-center text-slate-400 font-medium italic">No rejected/returned submissions.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Templates List Section -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-xl font-bold font-headline text-slate-800 mb-5 flex items-center gap-2">
                        <span class="material-symbols-outlined text-purple-600">folder_open</span>
                        Active OJT Requirements List
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse($requirements as $req)
                            <div class="border border-slate-200 rounded-xl p-5 hover:shadow-md transition relative flex flex-col justify-between min-h-[140px]">
                                <div>
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="font-bold text-[#300050] text-base truncate pr-6" title="{{ $req->title }}">{{ $req->title }}</h3>
                                        <form action="{{ route('coordinator.requirements.destroy', $req->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this requirement template? This will also delete all student submissions for it.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-400 hover:text-rose-600 transition" title="Delete Template">
                                                <span class="material-symbols-outlined text-lg">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                    <p class="text-xs text-slate-500 font-medium mb-4 line-clamp-2" title="{{ $req->description ?: 'No description provided.' }}">
                                        {{ $req->description ?: 'No description provided.' }}
                                    </p>
                                </div>
                                <div class="border-t border-slate-100 pt-3 flex justify-between items-center mt-auto">
                                    @if($req->template_path)
                                        <a href="{{ asset('storage/' . $req->template_path) }}" target="_blank"
                                           class="inline-flex items-center gap-1 text-xs font-bold text-purple-700 hover:text-purple-950 transition">
                                            <span class="material-symbols-outlined text-sm">download</span>
                                            Download Template
                                        </a>
                                    @else
                                        <span class="text-[10px] text-slate-400 font-medium italic">No download template uploaded</span>
                                    @endif
                                    <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">
                                        Added {{ $req->created_at->format('M d, Y') }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 py-12 text-center border-2 border-dashed border-slate-200 rounded-xl">
                                <span class="material-symbols-outlined text-slate-300 text-5xl mb-3">folder</span>
                                <p class="text-sm text-slate-500 font-medium">No requirement definitions created yet.</p>
                                <p class="text-xs text-slate-400 mt-1">Use the panel on the right to configure the first one.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- RIGHT AREA: Define New Requirement Form (4 Columns) -->
            <div class="xl:col-span-4">
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 sticky top-24">
                    <h2 class="text-xl font-bold font-headline text-slate-800 mb-5 flex items-center gap-2">
                        <span class="material-symbols-outlined text-purple-600">note_add</span>
                        New OJT Requirement
                    </h2>

                    <form action="{{ route('coordinator.requirements') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Title Input -->
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Requirement Title</label>
                            <input type="text" name="title" value="{{ old('title') }}" required
                                   class="w-full border border-slate-200 rounded-xl px-4 py-2.5 bg-slate-50/50 text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all"
                                   placeholder="e.g. MOA, Internship Waiver">
                        </div>

                        <!-- Description Input -->
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Instructions / Description</label>
                            <textarea name="description" rows="3"
                                      class="w-full border border-slate-200 rounded-xl px-4 py-2.5 bg-slate-50/50 text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all"
                                      placeholder="Explain instructions, guidelines, and what students should submit.">{{ old('description') }}</textarea>
                        </div>

                        <!-- Scope to Academic Course(s) Checkboxes -->
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Scope to Academic Course(s)</label>
                            <div class="space-y-2 bg-slate-50 border border-slate-100 p-4 rounded-xl">
                                @foreach($managedCourses as $course)
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="checkbox" name="courses[]" value="{{ $course->id }}" checked class="rounded border-slate-200 text-purple-600 focus:ring-purple-400 focus:ring-opacity-25 transition-all">
                                        <span class="text-sm text-slate-700 font-medium group-hover:text-purple-900 transition-colors">{{ $course->course_name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1 font-medium">Specify which course departments this requirement applies to.</p>
                        </div>

                        <!-- Template File Input -->
                        <div class="mb-6">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Document Template (Optional)</label>
                            <div class="border-2 border-dashed border-slate-200 hover:border-purple-300 rounded-xl p-4 bg-slate-50/50 hover:bg-purple-50/10 cursor-pointer transition-colors relative flex flex-col items-center justify-center">
                                <input type="file" name="template" id="template_file_input"
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                       onchange="updateFileNameDisplay(this, 'file_name_display')">
                                <span class="material-symbols-outlined text-slate-400 text-3xl mb-2">cloud_upload</span>
                                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider" id="file_name_display">Click to upload template</p>
                                <p class="text-[9px] text-slate-400 font-medium mt-1">PDF, DOCX, DOC up to 5MB</p>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                                class="w-full bg-[#300050] hover:bg-purple-950 text-white py-3 rounded-xl font-bold text-sm shadow-md transition active:scale-95">
                            Publish OJT Requirement
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </main>

    <!-- REJECTION REMARKS MODAL -->
    <div id="rejectModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl border border-purple-100 p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-xl font-bold font-headline text-slate-800 flex items-center gap-2">
                    <span class="material-symbols-outlined text-rose-600">warning</span>
                    Return for Revision
                </h2>
                <button onclick="closeRejectModal()" class="text-gray-400 hover:text-rose-500 transition">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <form method="POST" id="rejectForm">
                @csrf
                <div class="mb-6">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Provide Feedback / Remarks</label>
                    <textarea name="remarks" required rows="4"
                              class="w-full border border-slate-200 rounded-xl px-4 py-2.5 bg-slate-50/50 text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all"
                              placeholder="Describe why this document is being returned (e.g. missing signature, blurred scan) and what changes are needed."></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeRejectModal()" class="flex-1 px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-bold hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 bg-rose-600 hover:bg-rose-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md transition">
                        Reject Submission
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- DOCUMENT REVIEW LIGHTBOX MODAL -->
    <div id="reviewModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-black/80 backdrop-blur-sm transition-opacity duration-300 p-4 sm:p-8">
        <div class="bg-surface rounded-2xl shadow-2xl flex flex-col w-full max-w-5xl h-full max-h-[90vh] overflow-hidden relative">
            
            <!-- Header -->
            <div class="px-6 py-4 border-b border-outline/10 flex justify-between items-center bg-white">
                <h2 class="text-lg font-bold font-headline text-slate-800 flex items-center gap-2">
                    <span class="material-symbols-outlined text-purple-600">plagiarism</span>
                    Document Verification Lightbox
                </h2>
                <button onclick="closeReviewModal()" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-full transition">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <!-- Body: PDF Viewer -->
            <div class="flex-1 bg-slate-200/50 w-full relative">
                <iframe id="reviewIframe" src="" class="absolute inset-0 w-full h-full border-none"></iframe>
            </div>

            <!-- Footer: Actions -->
            <div class="px-6 py-4 bg-white border-t border-outline/10 flex justify-between items-center">
                <p class="text-xs text-slate-500 font-medium">Please review the document carefully before endorsing.</p>
                <div class="flex items-center gap-3">
                    <button type="button" id="reviewRejectBtn" class="px-6 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl text-sm font-bold transition flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                        Return for Revision
                    </button>
                    
                    <form method="POST" id="reviewApproveForm" class="m-0">
                        @csrf
                        <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold shadow-md transition flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">verified</span>
                            Endorse Document
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateFileNameDisplay(input, elementId) {
            const fileName = input.files[0] ? input.files[0].name : "Click to upload template";
            document.getElementById(elementId).innerText = fileName;
        }

        function switchTab(tabId, contentId) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(el => {
                el.classList.add('hidden');
                el.classList.remove('block');
            });
            // Reset tab button states
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('border-purple-600', 'text-purple-600');
                el.classList.add('text-slate-500', 'border-transparent');
            });
            
            // Show target content
            document.getElementById(contentId).classList.remove('hidden');
            document.getElementById(contentId).classList.add('block');
            
            // Set active button style
            document.getElementById(tabId).classList.add('border-purple-600', 'text-purple-600');
            document.getElementById(tabId).classList.remove('text-slate-500', 'border-transparent');
        }

        function openRejectModal(id) {
            document.getElementById('rejectForm').action = `/coordinator/submissions/${id}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }

        function openReviewModal(id, pdfUrl) {
            document.getElementById('reviewIframe').src = pdfUrl + "#toolbar=0";
            document.getElementById('reviewApproveForm').action = `/coordinator/submissions/${id}/approve`;
            
            // Set reject button action inside review modal
            document.getElementById('reviewRejectBtn').onclick = function() {
                closeReviewModal();
                openRejectModal(id);
            };
            
            document.getElementById('reviewModal').classList.remove('hidden');
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').classList.add('hidden');
            document.getElementById('reviewIframe').src = "";
        }
    </script>
</body>

</html>
