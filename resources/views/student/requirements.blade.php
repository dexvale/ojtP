<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>OJT Portal | My Requirements</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link
        href="https://fonts.googleapis.com/css2?family=Newsreader:opsz,wght@6..72,400;6..72,600;6..72,700;6..72,800&family=Public+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">

    <!-- JSZip (Required dependency for docx-preview to unpack .docx files) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

    <!-- docx-preview for client-side Word document rendering -->
    <script src="{{ asset('vendor/docx/docx-preview.min.js') }}"></script>
    <script>window.docx || document.write('<script src="https://cdn.jsdelivr.net/npm/docx-preview@0.3.3/dist/docx-preview.min.js"><\/script>');</script>

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

        /* docx-preview responsive styling */
        .docx-wrapper {
            background: transparent !important;
            padding: 16px 8px !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
        .docx-wrapper > section.docx {
            background: #ffffff !important;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.12) !important;
            margin-bottom: 24px !important;
            border-radius: 6px !important;
            box-sizing: border-box !important;
            color: #1e293b !important;
            max-width: 100% !important;
        }

        /* Mobile specific docx adaptations */
        @media (max-width: 640px) {
            .docx-wrapper {
                padding: 8px 4px !important;
                overflow-x: hidden !important;
            }
            .docx-wrapper > section.docx {
                width: 100% !important;
                min-width: 0 !important;
                max-width: 100% !important;
                min-height: auto !important;
                padding: 16px 12px !important;
                margin-bottom: 12px !important;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
                font-size: 13px !important;
                line-height: 1.5 !important;
            }
            .docx-wrapper table {
                max-width: 100% !important;
                display: block !important;
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch !important;
            }
            .docx-wrapper img {
                max-width: 100% !important;
                height: auto !important;
            }
        }
    </style>
</head>

<body class="bg-surface font-body text-on-surface antialiased" data-theme="student">
    <!-- SideNavBar (Student Component) -->
    @include('components.student-sidebar')

    <!-- Sidebar overlay for mobile -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 backdrop-blur-xs z-[55] hidden lg:hidden" onclick="closeSidebar()"></div>

    <!-- TopNavBar -->
    <header class="fixed top-0 w-full z-50 bg-[#fff7fd] flex justify-between items-center px-6 lg:px-8 py-4 border-b border-[#cec3d0]/20 backdrop-blur-sm lg:pl-64 pl-0">
        <div class="flex items-center gap-4">
            <!-- Hamburger for mobile only -->
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
            <div class="relative" id="user-profile-menu">
                <button type="button" id="user-menu-btn" class="flex items-center gap-2.5 p-1 rounded-full hover:bg-surface-container transition-colors focus:outline-none cursor-pointer" aria-expanded="false" aria-haspopup="true">
                    <img alt="User avatar" class="w-8 h-8 rounded-full object-cover ring-2 ring-primary/20 hover:ring-primary transition-all" src="{{ auth()->user()->studentProfile?->profile_photo_url ?? auth()->user()->avatar_url }}">
                </button>
                <!-- Dropdown Menu -->
                <div id="user-menu-dropdown" class="hidden absolute right-0 top-full mt-2 w-52 bg-white rounded-xl shadow-lg border border-slate-100 py-1.5 z-50">
                    <div class="px-4 py-2.5 border-b border-slate-100">
                        <p class="text-xs font-bold text-slate-800 truncate">{{ auth()->user()->display_name }}</p>
                        <p class="text-[10px] uppercase tracking-wider text-slate-500 font-semibold truncate">{{ auth()->user()->studentProfile?->student_id_number ?? 'Student Intern' }}</p>
                    </div>
                    <a href="{{ route('student.profile') }}" class="px-4 py-2 text-xs sm:text-sm text-slate-700 hover:bg-slate-50 flex items-center gap-2.5 font-medium transition-colors">
                        <span class="material-symbols-outlined text-[18px]">person</span>
                        <span>My Profile</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-xs sm:text-sm text-red-600 hover:bg-red-50 flex items-center gap-2.5 font-semibold transition-colors cursor-pointer">
                            <span class="material-symbols-outlined text-[18px]">logout</span>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="lg:ml-64 ml-0 pt-20 px-4 sm:px-6 lg:px-8 pb-12 min-h-screen border-none max-w-7xl mx-auto w-full">
        
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-extrabold font-headline text-slate-900 tracking-tight">OJT Requirements</h1>
            <p class="text-slate-500 font-medium mt-1 text-sm">Download forms, submit completed files, and monitor the verification status of your requirements.</p>
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

        <!-- Requirements Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($requirements as $req)
                @php
                    $sub = $studentSubmissions->get($req->id);
                @endphp
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between min-h-[220px]">
                    <div>
                        <!-- Header & Status Badge -->
                        <div class="flex justify-between items-start gap-4 mb-3">
                            <h3 class="font-bold text-[#300050] text-lg leading-tight">{{ $req->title }}</h3>
                            
                            @if(!$sub)
                                <span class="inline-flex items-center px-2.5 py-0.5 bg-slate-100 text-slate-800 text-[10px] font-bold uppercase tracking-wider rounded-full shadow-sm">
                                    Not Submitted
                                </span>
                            @elseif($sub->status === 'Pending')
                                <span class="inline-flex items-center px-2.5 py-0.5 bg-amber-100 text-amber-800 text-[10px] font-bold uppercase tracking-wider rounded-full shadow-sm">
                                    Pending Verification
                                </span>
                            @elseif($sub->status === 'Approved')
                                <span class="inline-flex items-center px-2.5 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-bold uppercase tracking-wider rounded-full shadow-sm">
                                    Verified
                                </span>
                            @elseif($sub->status === 'Rejected')
                                <span class="inline-flex items-center px-2.5 py-0.5 bg-rose-100 text-rose-800 text-[10px] font-bold uppercase tracking-wider rounded-full shadow-sm">
                                    Needs Revision
                                </span>
                            @endif
                        </div>

                        <!-- Description -->
                        <p class="text-xs text-slate-500 font-medium mb-5">
                            {{ $req->description ?: 'No description or instructions provided.' }}
                        </p>

                        <!-- Rejection remarks -->
                        @if($sub && $sub->status === 'Rejected')
                            <div class="bg-rose-50/50 border border-rose-100 text-rose-900 rounded-lg p-3 text-xs mb-5 italic">
                                <strong>Coordinator Remarks:</strong> {{ $sub->remarks }}
                            </div>
                        @endif
                    </div>

                    <!-- Footer Actions -->
                    <div class="border-t border-slate-100 pt-4 flex flex-wrap gap-3 items-center justify-between mt-auto">
                        <div>
                            @if($req->template_path)
                                @php
                                    $tplExt = strtolower(pathinfo($req->template_path, PATHINFO_EXTENSION));
                                    $tplUrl = asset('storage/' . $req->template_path);
                                    $tplDownload = route('student.requirements.downloadTemplate', $req->id);
                                @endphp
                                <div class="flex items-center gap-2">
                                    <button type="button"
                                            onclick="openFilePreview('{{ $tplUrl }}', '{{ addslashes($req->title) }} (Template)', '{{ $tplExt }}', '{{ $tplDownload }}')"
                                            class="inline-flex items-center gap-1 text-xs font-bold text-primary hover:text-purple-950 transition cursor-pointer"
                                            title="Preview template on screen">
                                        <span class="material-symbols-outlined text-sm">visibility</span>
                                        Preview Form
                                    </button>
                                    <span class="text-slate-300">•</span>
                                    <a href="{{ $tplDownload }}"
                                       class="inline-flex items-center gap-1 text-xs font-medium text-slate-500 hover:text-slate-800 transition"
                                       title="Download template file">
                                        <span class="material-symbols-outlined text-sm">download</span>
                                        Download
                                    </a>
                                </div>
                            @else
                                <span class="text-[10px] text-slate-400 font-medium italic">No download template available</span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 flex-wrap">
                            @if(!$sub)
                                @if($req->template_path && str_ends_with(strtolower($req->template_path), '.pdf'))
                                    <a href="{{ route('student.requirements.fill', $req->id) }}"
                                            class="inline-flex items-center gap-1 px-3.5 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-xs font-bold shadow-sm transition active:scale-95">
                                        <span class="material-symbols-outlined text-[15px]">edit_note</span>
                                        <span>Fill Form (Online)</span>
                                    </a>
                                @endif
                                <button data-id="{{ $req->id }}" data-title="{{ $req->title }}" onclick="openSubmitModal(this.dataset.id, this.dataset.title)"
                                        class="px-4 py-2 bg-[#300050] hover:bg-purple-950 text-white rounded-lg text-xs font-bold shadow-sm transition active:scale-95">
                                    Submit File
                                </button>
                            @elseif($sub->status === 'Pending')
                                @php
                                    $subExt = strtolower(pathinfo($sub->file_path, PATHINFO_EXTENSION));
                                    $subUrl = asset('storage/' . $sub->file_path);
                                @endphp
                                <button type="button"
                                        onclick="openFilePreview('{{ $subUrl }}', '{{ addslashes($req->title) }} (My Submission)', '{{ $subExt }}', '{{ $subUrl }}')"
                                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition flex items-center gap-1.5 cursor-pointer">
                                    <span class="material-symbols-outlined text-sm">visibility</span>
                                    <span>View Submission</span>
                                </button>
                            @elseif($sub->status === 'Approved')
                                @php
                                    $subExt = strtolower(pathinfo($sub->file_path, PATHINFO_EXTENSION));
                                    $subUrl = asset('storage/' . $sub->file_path);
                                @endphp
                                <button type="button"
                                        onclick="openFilePreview('{{ $subUrl }}', '{{ addslashes($req->title) }} (Approved)', '{{ $subExt }}', '{{ $subUrl }}')"
                                        class="px-4 py-2 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 rounded-lg text-xs font-bold transition flex items-center gap-1.5 cursor-pointer">
                                    <span class="material-symbols-outlined text-base">check_circle</span>
                                    <span>View Approved File</span>
                                </button>
                            @elseif($sub->status === 'Rejected')
                                @if($sub->file_path)
                                    @php
                                        $subExt = strtolower(pathinfo($sub->file_path, PATHINFO_EXTENSION));
                                        $subUrl = asset('storage/' . $sub->file_path);
                                    @endphp
                                    <button type="button"
                                            onclick="openFilePreview('{{ $subUrl }}', '{{ addslashes($req->title) }} (Previous)', '{{ $subExt }}', '{{ $subUrl }}')"
                                            class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-xs font-bold transition flex items-center gap-1 cursor-pointer"
                                            title="View previously submitted document">
                                        <span class="material-symbols-outlined text-sm">visibility</span>
                                        <span>View Previous</span>
                                    </button>
                                @endif
                                @if($req->template_path && str_ends_with(strtolower($req->template_path), '.pdf'))
                                    <a href="{{ route('student.requirements.fill', $req->id) }}"
                                            class="inline-flex items-center gap-1 px-3.5 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-xs font-bold shadow-sm transition active:scale-95">
                                        <span class="material-symbols-outlined text-[15px]">edit_note</span>
                                        <span>Fill Form (Online)</span>
                                    </a>
                                @endif
                                <button data-id="{{ $req->id }}" data-title="{{ $req->title }}" onclick="openSubmitModal(this.dataset.id, this.dataset.title)"
                                        class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-bold shadow-md transition active:scale-95">
                                    Re-submit Document
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-2 py-16 text-center border-2 border-dashed border-slate-200 bg-white rounded-xl">
                    <span class="material-symbols-outlined text-slate-300 text-6xl mb-3">assignment_late</span>
                    <h3 class="text-lg font-bold text-slate-700">No OJT Requirements Assigned</h3>
                    <p class="text-sm text-slate-500 font-medium mt-1">Your department coordinator has not configured any requirements yet.</p>
                </div>
            @endforelse
        </div>

    </main>

    <!-- SUBMISSION UPLOAD MODAL -->
    <div id="submitModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm transition-opacity duration-300 p-4">
        <div class="bg-white rounded-xl shadow-2xl border border-purple-100 p-4 sm:p-6 w-full max-w-lg mx-auto max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-xl font-bold font-headline text-[#300050] flex items-center gap-2">
                    <span class="material-symbols-outlined text-purple-600">cloud_upload</span>
                    Submit Requirement
                </h2>
                <button onclick="closeSubmitModal()" class="text-gray-400 hover:text-rose-500 transition">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <p class="text-xs text-slate-500 mb-4">Uploading file for: <span class="font-bold text-purple-900" id="modal_req_title">Requirement Title</span></p>

            <form method="POST" id="submitForm" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Upload Completed Document(s)</label>
                    <div class="border-2 border-dashed border-slate-200 hover:border-purple-300 rounded-xl p-6 bg-slate-50/50 hover:bg-purple-50/10 cursor-pointer transition-colors relative flex flex-col items-center justify-center">
                        <input type="file" name="submission_files[]" id="submission_file_input" multiple required
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                               accept=".pdf,.docx,.doc,.zip,.jpg,.jpeg,.png,.webp"
                               onchange="updateFilesDisplay(this)">
                        <span class="material-symbols-outlined text-slate-400 text-4xl mb-2" id="upload_icon">cloud_upload</span>
                        <p class="text-xs font-bold text-slate-600 uppercase tracking-wider text-center" id="file_name_display">Choose or drag completed file(s)</p>
                        <p class="text-[10px] text-slate-400 font-medium mt-1 text-center" id="file_type_hint">PDF, DOCX, ZIP, PNG, JPG up to 15MB each (select multiple to merge)</p>
                    </div>

                    <!-- Multi-file list preview container -->
                    <div id="selected_files_list" class="mt-3 space-y-1.5 hidden max-h-40 overflow-y-auto pr-1"></div>

                    <!-- Auto-merge indicator banner -->
                    <div id="multi_merge_badge" class="mt-3 hidden bg-purple-50 border border-purple-200 rounded-xl p-3 flex items-start gap-2.5 text-xs text-purple-950">
                        <span class="material-symbols-outlined text-purple-600 text-base mt-0.5 shrink-0">auto_awesome</span>
                        <div>
                            <p class="font-bold text-purple-900">Automatic PDF Merger Active</p>
                            <p class="text-[11px] text-purple-700 mt-0.5">All selected scan files and photos will be compiled into a single combined PDF for you.</p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeSubmitModal()" class="flex-1 px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-bold hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 bg-purple-950 hover:bg-purple-900 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md transition">
                        Upload Document
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- FILE PREVIEW MODAL (In-Browser Viewer for DOCX, PDF, Images) -->
    <div id="filePreviewModal" class="hidden fixed inset-0 z-[65] flex items-center justify-center bg-black/80 backdrop-blur-sm transition-opacity duration-300 p-0 sm:p-4 md:p-6">
        <div class="bg-white rounded-none sm:rounded-2xl shadow-2xl flex flex-col w-full max-w-5xl h-full sm:h-[92vh] max-h-[100dvh] sm:max-h-[92vh] overflow-hidden relative">
            <!-- Header -->
            <div class="px-3 sm:px-5 py-2.5 sm:py-3.5 border-b border-slate-200 flex gap-2 justify-between items-center bg-white shrink-0">
                <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                    <button type="button" onclick="closeFilePreviewModal()" class="flex items-center gap-1 px-2.5 sm:px-3 py-1.5 rounded-lg sm:rounded-xl text-slate-700 hover:text-[#300050] bg-slate-100 hover:bg-purple-50 transition border border-slate-200 cursor-pointer shrink-0" title="Back to Requirements List">
                        <span class="material-symbols-outlined text-[18px] sm:text-[20px]">arrow_back</span>
                        <span class="text-xs font-bold">Back</span>
                    </button>
                    <div id="fileModalIconBox" class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center shrink-0">
                        <span id="fileModalIcon" class="material-symbols-outlined text-[18px] sm:text-[20px]">description</span>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-1.5 sm:gap-2">
                            <h2 id="fileModalTitle" class="text-sm sm:text-base font-bold text-slate-800 font-headline truncate">Document Preview</h2>
                            <span id="fileModalBadge" class="text-[9px] sm:text-[10px] font-extrabold uppercase tracking-wider bg-blue-100 text-blue-800 px-1.5 sm:px-2 py-0.5 rounded-md shrink-0">DOCX</span>
                        </div>
                        <p class="text-[10px] sm:text-xs text-slate-400 font-medium truncate">Document Previewer</p>
                    </div>
                </div>

                <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
                    <a id="fileModalDownloadBtn" href="#" download class="px-2.5 sm:px-3.5 py-1.5 bg-[#300050] hover:bg-purple-950 text-white rounded-lg sm:rounded-xl text-xs font-bold transition flex items-center gap-1 shadow-sm" title="Download File">
                        <span class="material-symbols-outlined text-[16px]">download</span>
                        <span class="hidden sm:inline">Download</span>
                    </a>
                    <a id="fileModalExternalBtn" href="#" target="_blank" rel="noopener noreferrer" class="p-1.5 sm:px-3 sm:py-1.5 text-slate-600 hover:text-purple-700 text-xs font-semibold flex items-center gap-1 transition rounded-lg sm:rounded-xl hover:bg-slate-100" title="Open in new tab">
                        <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                        <span class="hidden md:inline">Open Tab</span>
                    </a>
                    <button type="button" onclick="closeFilePreviewModal()" class="p-1.5 sm:p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-full transition cursor-pointer" title="Close Preview">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>
            </div>

            <!-- Body Viewer Area -->
            <div class="flex-1 bg-slate-100 w-full relative overflow-hidden flex flex-col items-center justify-start">
                <!-- Loading State -->
                <div id="fileViewerLoading" class="absolute inset-0 bg-white/90 z-20 flex flex-col items-center justify-center gap-3">
                    <div class="w-8 h-8 border-4 border-purple-200 border-t-[#300050] rounded-full animate-spin"></div>
                    <p class="text-xs font-bold text-slate-700">Loading document preview...</p>
                </div>

                <!-- PDF / Image Viewer Iframe -->
                <iframe id="fileIframeViewer" src="" class="w-full h-full border-none hidden"></iframe>

                <!-- DOCX Preview Container -->
                <div id="fileDocxViewer" class="w-full h-full overflow-y-auto overflow-x-hidden p-2 sm:p-6 flex flex-col items-center hidden">
                    <div id="fileDocxMount" class="w-full max-w-4xl flex flex-col items-center"></div>
                </div>

                <!-- Fallback Container (e.g. .doc or errors) -->
                <div id="fileFallbackViewer" class="p-6 sm:p-8 text-center flex flex-col items-center justify-center h-full max-w-md mx-auto hidden">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center mb-3 sm:mb-4">
                        <span class="material-symbols-outlined text-2xl sm:text-3xl">description</span>
                    </div>
                    <h3 class="text-sm sm:text-base font-bold text-slate-800 mb-1">In-browser preview not available</h3>
                    <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                        This file is in a legacy format (.doc) or unsupported format. You can download and view it directly on your device.
                    </p>
                    <a id="fileFallbackDownloadBtn" href="#" download class="px-4 sm:px-5 py-2 sm:py-2.5 bg-[#300050] hover:bg-purple-950 text-white text-xs font-bold rounded-xl shadow-md transition flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">download</span>
                        <span>Download File</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════
         MOBILE BOTTOM NAV
    ═══════════════════════════════ -->
    @include('components.student-bottom-nav')

    <script>
        function updateFilesDisplay(input) {
            const display = document.getElementById('file_name_display');
            const hint = document.getElementById('file_type_hint');
            const listContainer = document.getElementById('selected_files_list');
            const mergeBadge = document.getElementById('multi_merge_badge');
            const icon = document.getElementById('upload_icon');

            listContainer.innerHTML = '';

            if (!input.files || input.files.length === 0) {
                display.innerText = "Choose or drag completed file(s)";
                hint.innerText = "PDF, DOCX, ZIP, PNG, JPG up to 15MB each (select multiple to merge)";
                listContainer.classList.add('hidden');
                mergeBadge.classList.add('hidden');
                icon.innerText = "cloud_upload";
                icon.className = "material-symbols-outlined text-slate-400 text-4xl mb-2";
                return;
            }

            const files = Array.from(input.files);

            if (files.length === 1) {
                const file = files[0];
                const sizeMb = (file.size / (1024 * 1024)).toFixed(2);
                display.innerText = file.name;
                hint.innerText = `${sizeMb} MB • Ready to upload`;
                listContainer.classList.add('hidden');
                mergeBadge.classList.add('hidden');
                icon.innerText = "task";
                icon.className = "material-symbols-outlined text-purple-600 text-4xl mb-2";
            } else {
                display.innerText = `${files.length} files selected`;
                hint.innerText = `Ready to automatically merge into 1 PDF`;
                listContainer.classList.remove('hidden');
                mergeBadge.classList.remove('hidden');
                icon.innerText = "collections";
                icon.className = "material-symbols-outlined text-purple-600 text-4xl mb-2";

                files.forEach((file, index) => {
                    const item = document.createElement('div');
                    item.className = "flex items-center justify-between bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs text-slate-700";
                    const sizeKb = (file.size / 1024).toFixed(0);
                    item.innerHTML = `
                        <div class="flex items-center gap-1.5 truncate">
                            <span class="text-[10px] font-bold text-slate-400">#${index + 1}</span>
                            <span class="font-medium truncate">${file.name}</span>
                        </div>
                        <span class="text-[10px] text-slate-400 shrink-0 font-mono">${sizeKb} KB</span>
                    `;
                    listContainer.appendChild(item);
                });
            }
        }

        function openSubmitModal(reqId, reqTitle) {
            document.getElementById('modal_req_title').innerText = reqTitle;
            document.getElementById('submitForm').action = `/student/requirements/${reqId}/submit`;
            
            const input = document.getElementById('submission_file_input');
            if (input) {
                input.value = '';
                updateFilesDisplay(input);
            }

            document.getElementById('submitModal').classList.remove('hidden');
        }

        function closeSubmitModal() {
            document.getElementById('submitModal').classList.add('hidden');
            const input = document.getElementById('submission_file_input');
            if (input) {
                input.value = '';
                updateFilesDisplay(input);
            }
        }

        // ── In-Browser File Preview Modal (DOCX, PDF, Images) ──
        function openFilePreview(fileUrl, title, ext, downloadUrl) {
            const modal = document.getElementById('filePreviewModal');
            const modalTitle = document.getElementById('fileModalTitle');
            const modalBadge = document.getElementById('fileModalBadge');
            const downloadBtn = document.getElementById('fileModalDownloadBtn');
            const externalBtn = document.getElementById('fileModalExternalBtn');
            const icon = document.getElementById('fileModalIcon');
            const iconBox = document.getElementById('fileModalIconBox');
            const iframe = document.getElementById('fileIframeViewer');
            const docxViewer = document.getElementById('fileDocxViewer');
            const docxMount = document.getElementById('fileDocxMount');
            const fallback = document.getElementById('fileFallbackViewer');
            const fallbackDownload = document.getElementById('fileFallbackDownloadBtn');
            const loading = document.getElementById('fileViewerLoading');

            const dlUrl = downloadUrl || fileUrl;
            modalTitle.innerText = title;
            modalBadge.innerText = (ext || 'FILE').toUpperCase();
            downloadBtn.href = dlUrl;
            externalBtn.href = fileUrl;
            if (fallbackDownload) fallbackDownload.href = dlUrl;

            // Reset viewer states
            iframe.classList.add('hidden');
            iframe.src = '';
            docxViewer.classList.add('hidden');
            docxMount.innerHTML = '';
            fallback.classList.add('hidden');
            loading.classList.remove('hidden');

            const normalizedExt = (ext || '').toLowerCase();

            if (normalizedExt === 'docx') {
                icon.innerText = 'description';
                iconBox.className = 'w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center shrink-0';
                modalBadge.className = 'text-[9px] sm:text-[10px] font-extrabold uppercase tracking-wider bg-blue-100 text-blue-800 px-1.5 sm:px-2 py-0.5 rounded-md shrink-0';

                fetch(fileUrl)
                    .then(res => {
                        if (!res.ok) throw new Error('Network error: ' + res.status);
                        return res.blob();
                    })
                    .then(blob => {
                        loading.classList.add('hidden');
                        docxViewer.classList.remove('hidden');
                        if (window.docx) {
                            window.docx.renderAsync(blob, docxMount, null, {
                                className: "docx",
                                inWrapper: true,
                                ignoreWidth: false,
                                ignoreHeight: false,
                                breakPages: true
                            }).catch(err => {
                                console.error('DOCX render error:', err);
                                docxViewer.classList.add('hidden');
                                fallback.classList.remove('hidden');
                            });
                        } else {
                            throw new Error('docx library not loaded');
                        }
                    })
                    .catch(err => {
                        console.error('Fetch error:', err);
                        loading.classList.add('hidden');
                        fallback.classList.remove('hidden');
                    });

            } else if (normalizedExt === 'doc') {
                icon.innerText = 'description';
                iconBox.className = 'w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0';
                modalBadge.className = 'text-[9px] sm:text-[10px] font-extrabold uppercase tracking-wider bg-amber-100 text-amber-800 px-1.5 sm:px-2 py-0.5 rounded-md shrink-0';
                loading.classList.add('hidden');
                fallback.classList.remove('hidden');

            } else {
                // PDF or Image
                const isImg = ['jpg', 'jpeg', 'png', 'webp', 'gif'].includes(normalizedExt);
                icon.innerText = isImg ? 'image' : 'picture_as_pdf';
                iconBox.className = 'w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center shrink-0';
                modalBadge.className = 'text-[9px] sm:text-[10px] font-extrabold uppercase tracking-wider bg-purple-100 text-purple-800 px-1.5 sm:px-2 py-0.5 rounded-md shrink-0';

                iframe.onload = () => loading.classList.add('hidden');
                iframe.src = fileUrl;
                iframe.classList.remove('hidden');
                setTimeout(() => loading.classList.add('hidden'), 1500);
            }

            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeFilePreviewModal() {
            const modal = document.getElementById('filePreviewModal');
            const iframe = document.getElementById('fileIframeViewer');
            const docxMount = document.getElementById('fileDocxMount');
            if (modal) modal.classList.add('hidden');
            if (iframe) iframe.src = '';
            if (docxMount) docxMount.innerHTML = '';
            document.body.classList.remove('overflow-hidden');
        }

        // Escape key listener to close modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeFilePreviewModal();
                closeSubmitModal();
            }
        });

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

        const userMenuBtn = document.getElementById('user-menu-btn');
        const userMenuDropdown = document.getElementById('user-menu-dropdown');
        userMenuBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            userMenuDropdown?.classList.toggle('hidden');
        });
        document.addEventListener('click', (e) => {
            if (!userMenuDropdown?.contains(e.target) && !userMenuBtn?.contains(e.target)) {
                userMenuDropdown?.classList.add('hidden');
            }
        });
    </script>
</body>

</html>
