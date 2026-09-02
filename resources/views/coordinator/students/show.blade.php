<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>OJT Portal | Student Profile Overview</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:opsz,wght@6..72,400;6..72,600;6..72,700;6..72,800&family=Public+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Public Sans', sans-serif; }
        .font-headline { font-family: 'Newsreader', serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    </style>
</head>
<body class="bg-surface text-on-surface" data-theme="portal">
    @include('components.coordinator-sidebar')

    <!-- TopNavBar -->
    <header class="fixed top-0 left-64 right-0 z-40 bg-surface/90 backdrop-blur-sm border-b border-[#cec3d0]/15">
        <div class="flex justify-between items-center px-8 py-4 w-full">
            <div class="flex items-center gap-8">
                <span class="text-2xl font-headline font-semibold text-primary tracking-tight">OJT Management</span>
            </div>
            <div class="flex items-center gap-4 sm:gap-6">
                <a href="{{ route('coordinator.students') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 hover:text-primary hover:border-primary/30 transition-all shadow-sm active:scale-95">
                    <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                    Back to Directory
                </a>
                <div class="flex items-center gap-3 pl-3 border-l border-[#cec3d0]/30">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold font-headline text-primary">{{ auth()->user()->display_name }}</p>
                        <p class="text-[10px] uppercase tracking-wider text-secondary font-bold">{{ auth()->user()->display_role }}</p>
                    </div>
                    <img alt="User profile avatar"
                        class="w-9 h-9 rounded-full object-cover ring-2 ring-primary/10"
                        src="{{ auth()->user()->avatar_url }}">
                </div>
            </div>
        </div>
    </header>

    <main class="ml-64 pt-24 px-8 pb-12 min-h-screen">
        
        <!-- 1. Student Profile Header Banner -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
            
            <div class="flex items-center gap-5 z-10">
                <div class="w-20 h-20 rounded-full bg-primary/10 flex items-center justify-center text-primary text-2xl font-bold shadow-sm uppercase border-2 border-white ring-4 ring-primary/5">
                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-3xl font-extrabold font-headline text-slate-900 flex items-center gap-3">
                        {{ $student->first_name }} {{ $student->last_name }}
                        @if($student->company_id)
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] uppercase font-bold tracking-wider">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] uppercase font-bold tracking-wider">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Unassigned
                            </span>
                        @endif
                    </h1>
                    <p class="text-slate-500 font-medium text-sm mt-1 flex items-center gap-3">
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">badge</span> {{ $student->student_id_number }}</span>
                        <span class="text-slate-300">•</span>
                        <span>{{ $student->course ?? 'Intern' }}</span>
                    </p>
                    
                    @if($student->company)
                        <div class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-purple-50 border border-purple-100 text-purple-700 text-xs font-semibold">
                            <span class="material-symbols-outlined text-[16px]">corporate_fare</span>
                            Deployed to: {{ $student->company->name }}
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="flex gap-3 z-10">
                <a href="mailto:{{ $student->user->email ?? '' }}" class="px-4 py-2 border border-slate-200 text-slate-700 font-semibold text-sm rounded-xl hover:bg-slate-50 transition-colors flex items-center gap-2 shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">mail</span> Contact
                </a>
            </div>
        </div>

        <!-- 2. Summary Metric Matrix Blocks -->
        @php
            $approvedHours = floatval($student->approved_hours ?? 0);
            $pendingCount = $student->user ? $student->user->ojtLogs()->where('status', 'Pending')->count() : 0;
            $progressPercent = min(($approvedHours / 400) * 100, 100);
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Approved Hours -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex items-center gap-5 relative overflow-hidden">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-3xl">task_alt</span>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Approved Hours</p>
                    <div class="flex items-end justify-between">
                        <h3 class="text-3xl font-black text-slate-800 font-mono">{{ number_format($approvedHours, 2) }}</h3>
                        <span class="text-sm font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">{{ round($progressPercent) }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 h-1.5 rounded-full mt-3 overflow-hidden">
                        <div class="bg-emerald-500 h-full rounded-full" style="width: {{ $progressPercent }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Pending Approvals -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex items-center gap-5 relative overflow-hidden">
                <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-3xl">pending_actions</span>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Pending Reviews</p>
                    <h3 class="text-3xl font-black text-slate-800 font-mono">{{ $pendingCount }}</h3>
                    <p class="text-xs text-slate-500 mt-2">Awaiting supervisor approval</p>
                </div>
            </div>

            <!-- Documents -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex items-center gap-5 relative overflow-hidden">
                <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-3xl">folder_open</span>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Documents</p>
                    <h3 class="text-3xl font-black text-slate-800 font-mono">0</h3>
                    <p class="text-xs text-slate-500 mt-2">Uploaded verifications</p>
                </div>
            </div>
        </div>

        <!-- 3. Attendance Logs List Component -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="border-b border-slate-200 bg-slate-50/50 px-6 py-4 flex justify-between items-center">
                <h2 class="text-lg font-bold font-headline text-slate-800 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">history</span>
                    Attendance & Activity Logs
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white border-b border-slate-100 whitespace-nowrap">
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Shift Punches</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Activity Summary</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Hours</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Evidence</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @if($student->user && $student->user->ojtLogs->count() > 0)
                            @foreach($student->user->ojtLogs as $log)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-slate-800">{{ \Carbon\Carbon::parse($log->log_date)->format('M d, Y') }}</p>
                                    <p class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($log->log_date)->format('l') }}</p>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-600 font-mono">
                                    @if($log->morning_in && $log->morning_out)
                                        <div class="mb-1">AM: {{ \Carbon\Carbon::parse($log->morning_in)->format('h:i A') }} - {{ \Carbon\Carbon::parse($log->morning_out)->format('h:i A') }}</div>
                                    @endif
                                    @if($log->afternoon_in && $log->afternoon_out)
                                        <div>PM: {{ \Carbon\Carbon::parse($log->afternoon_in)->format('h:i A') }} - {{ \Carbon\Carbon::parse($log->afternoon_out)->format('h:i A') }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 align-top max-w-xs md:max-w-sm lg:max-w-md">
                                    <div class="text-xs text-slate-600 leading-relaxed break-all whitespace-pre-wrap [overflow-wrap:anywhere] block">
                                        {{ $log->tasks_performed }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-slate-800 font-mono">{{ number_format($log->hours_rendered, 2) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($log->status === 'Approved')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700 uppercase tracking-wider">Approved</span>
                                    @elseif($log->status === 'Pending')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-700 uppercase tracking-wider">Pending</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-700 uppercase tracking-wider">{{ $log->status }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($log->photo_path)
                                        <div class="relative group cursor-pointer w-16 h-12 rounded overflow-hidden border shadow-sm flex-shrink-0" 
                                             data-photo="{{ asset('storage/' . $log->photo_path) }}"
                                             data-desc="{{ $log->tasks_performed }}"
                                             onclick="openImageLightbox(this)">
                                            <img src="{{ asset('storage/' . $log->photo_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white transition-opacity">
                                                <span class="material-symbols-outlined text-sm">visibility</span>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">No image evidence</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <span class="material-symbols-outlined text-4xl text-slate-300 mb-3 block">history_toggle_off</span>
                                    <p class="text-sm font-medium text-slate-500">No logs submitted yet.</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <!-- 4. Shared JavaScript Modal Lightbox -->
    <div id="evidence-lightbox" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-black/80 backdrop-blur-sm transition-opacity duration-300 p-4">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden w-[90vw] max-w-6xl flex flex-col md:flex-row relative">
            <button onclick="closeImageLightbox()" class="absolute top-4 right-4 z-10 w-8 h-8 flex items-center justify-center rounded-full bg-black/50 text-white hover:bg-rose-500 transition-colors">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>
            <div class="w-full md:w-2/3 bg-slate-900 flex items-center justify-center min-h-[400px] md:min-h-[600px] p-4 relative overflow-hidden group/zoom">
                <div class="w-full h-full flex items-center justify-center overflow-hidden cursor-grab active:cursor-grabbing" id="zoom-container">
                    <img id="lightbox-img" src="" class="max-w-full max-h-[85vh] object-contain shadow-lg origin-center transition-transform duration-200 ease-out select-none">
                </div>
                <!-- Zoom Controls Overlay -->
                <div class="absolute bottom-4 left-4 flex gap-2 bg-black/60 backdrop-blur-sm px-3 py-2 rounded-xl text-white opacity-0 group-hover/zoom:opacity-100 transition-opacity duration-200">
                    <button onclick="zoomIn()" class="p-1 hover:text-purple-400 transition-colors flex items-center justify-center" title="Zoom In">
                        <span class="material-symbols-outlined text-[20px]">zoom_in</span>
                    </button>
                    <button onclick="zoomOut()" class="p-1 hover:text-purple-400 transition-colors flex items-center justify-center" title="Zoom Out">
                        <span class="material-symbols-outlined text-[20px]">zoom_out</span>
                    </button>
                    <button onclick="resetZoom()" class="p-1 hover:text-rose-400 transition-colors flex items-center justify-center" title="Reset Zoom">
                        <span class="material-symbols-outlined text-[20px]">restart_alt</span>
                    </button>
                </div>
            </div>
            <div class="w-full md:w-1/3 p-8 bg-white flex flex-col">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Activity Description</span>
                <p id="lightbox-desc" class="text-sm text-slate-700 leading-relaxed whitespace-pre-wrap break-all [overflow-wrap:anywhere] overflow-y-auto max-h-[40vh] md:max-h-full pr-2"></p>
                <div class="mt-auto pt-6 border-t border-slate-100">
                    <p class="text-xs text-slate-400">Workspace Evidence Screenshot</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        let zoomLevel = 1;
        let isDragging = false;
        let startX = 0, startY = 0;
        let translateX = 0, translateY = 0;
        const img = document.getElementById('lightbox-img');
        const container = document.getElementById('zoom-container');

        function updateTransform() {
            img.style.transform = `scale(${zoomLevel}) translate(${translateX}px, ${translateY}px)`;
        }

        function zoomIn() {
            zoomLevel = Math.min(zoomLevel + 0.25, 4);
            updateTransform();
        }

        function zoomOut() {
            zoomLevel = Math.max(zoomLevel - 0.25, 1);
            if (zoomLevel === 1) {
                translateX = 0;
                translateY = 0;
            }
            updateTransform();
        }

        function resetZoom() {
            zoomLevel = 1;
            translateX = 0;
            translateY = 0;
            updateTransform();
        }

        // Dragging & Panning logic
        container.addEventListener('mousedown', function(e) {
            if (zoomLevel > 1) {
                isDragging = true;
                startX = e.clientX - translateX * zoomLevel;
                startY = e.clientY - translateY * zoomLevel;
                e.preventDefault();
            }
        });

        window.addEventListener('mousemove', function(e) {
            if (isDragging) {
                translateX = (e.clientX - startX) / zoomLevel;
                translateY = (e.clientY - startY) / zoomLevel;
                updateTransform();
            }
        });

        window.addEventListener('mouseup', function() {
            isDragging = false;
        });

        // Wheel Zoom Support
        container.addEventListener('wheel', function(e) {
            e.preventDefault();
            if (e.deltaY < 0) {
                zoomIn();
            } else {
                zoomOut();
            }
        }, { passive: false });

        function openImageLightbox(element) {
            const imgSrc = element.getAttribute('data-photo');
            const descText = element.getAttribute('data-desc');
            img.src = imgSrc;
            document.getElementById('lightbox-desc').textContent = descText;
            resetZoom();
            document.getElementById('evidence-lightbox').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeImageLightbox() {
            document.getElementById('evidence-lightbox').classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Close on clicking outside the modal content
        document.getElementById('evidence-lightbox').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageLightbox();
            }
        });
    </script>
</body>
</html>
