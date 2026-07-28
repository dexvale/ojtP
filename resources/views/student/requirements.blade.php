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

<body class="bg-slate-50 text-slate-800 antialiased" data-theme="student">
    <!-- SideNavBar (Student Component) -->
    @include('components.student-sidebar')

    <!-- TopNavBar -->
    <header class="fixed top-0 left-64 right-0 z-40 bg-white/95 backdrop-blur-sm border-b border-slate-200">
        <div class="flex justify-between items-center px-8 py-4 w-full">
            <div>
                <span class="text-2xl font-headline font-semibold text-primary tracking-tight">OJT Student Portal</span>
            </div>
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-3">
                    <button class="p-2 text-primary hover:bg-slate-100 rounded-full transition-all active:scale-95">
                        <span class="material-symbols-outlined" data-icon="notifications">notifications</span>
                    </button>
                    <div class="flex items-center gap-3 pl-2 border-l border-slate-200">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold text-slate-800">{{ auth()->user()->studentProfile->first_name ?? 'Student' }}</p>
                            <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">OJT Intern</p>
                        </div>
                        <img alt="User profile avatar"
                            class="w-9 h-9 rounded-full object-cover ring-2 ring-primary/10"
                            src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->studentProfile->first_name ?? 'Student') }}&background=3a0ca3&color=fff">
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
                                <a href="{{ asset('storage/' . $req->template_path) }}" target="_blank"
                                   class="inline-flex items-center gap-1 text-xs font-bold text-primary hover:text-purple-950 transition">
                                    <span class="material-symbols-outlined text-sm">download</span>
                                    Get Template Form
                                </a>
                            @else
                                <span class="text-[10px] text-slate-400 font-medium italic">No download template available</span>
                            @endif
                        </div>

                        <div>
                            @if(!$sub)
                                <button onclick="openSubmitModal({{ $req->id }}, '{{ $req->title }}')"
                                        class="px-4 py-2 bg-[#300050] hover:bg-purple-950 text-white rounded-lg text-xs font-bold shadow-sm transition active:scale-95">
                                    Submit File
                                </button>
                            @elseif($sub->status === 'Pending')
                                <a href="{{ asset('storage/' . $sub->file_path) }}" target="_blank"
                                   class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition">
                                    View Submission
                                </a>
                            @elseif($sub->status === 'Approved')
                                <a href="{{ asset('storage/' . $sub->file_path) }}" target="_blank"
                                   class="px-4 py-2 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 rounded-lg text-xs font-bold transition flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-base">check_circle</span>
                                    View Approved File
                                </a>
                            @elseif($sub->status === 'Rejected')
                                <button onclick="openSubmitModal({{ $req->id }}, '{{ $req->title }}')"
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
    <div id="submitModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl border border-purple-100 p-6 w-full max-w-md mx-4">
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
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Upload Completed Document</label>
                    <div class="border-2 border-dashed border-slate-200 hover:border-purple-300 rounded-xl p-6 bg-slate-50/50 hover:bg-purple-50/10 cursor-pointer transition-colors relative flex flex-col items-center justify-center">
                        <input type="file" name="submission_file" required
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                               onchange="updateFileNameDisplay(this, 'file_name_display')">
                        <span class="material-symbols-outlined text-slate-400 text-4xl mb-2">cloud_upload</span>
                        <p class="text-xs font-bold text-slate-600 uppercase tracking-wider" id="file_name_display">Choose or drag completed file</p>
                        <p class="text-[10px] text-slate-400 font-medium mt-1">PDF, DOCX, ZIP, PNG, JPG up to 10MB</p>
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

    <script>
        function updateFileNameDisplay(input, elementId) {
            const fileName = input.files[0] ? input.files[0].name : "Choose or drag completed file";
            document.getElementById(elementId).innerText = fileName;
        }

        function openSubmitModal(reqId, reqTitle) {
            document.getElementById('modal_req_title').innerText = reqTitle;
            document.getElementById('submitForm').action = `/student/requirements/${reqId}/submit`;
            document.getElementById('submitModal').classList.remove('hidden');
        }

        function closeSubmitModal() {
            document.getElementById('submitModal').classList.add('hidden');
        }
    </script>
</body>

</html>
