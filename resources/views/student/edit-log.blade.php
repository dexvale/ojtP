<!DOCTYPE html>
<html class="light" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Edit Internship Log | OJT Portal</title>
<meta name="description" content="Edit and resubmit your rejected OJT log."/>
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
    /* Smooth sidebar link transitions */
    aside nav a { transition: all 0.2s ease; }

    /* Position native time picker indicator to the right */
    input[type="time"]::-webkit-calendar-picker-indicator {
        background: transparent;
        color: transparent;
        cursor: pointer;
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 24px;
        height: 24px;
        z-index: 10;
    }
</style>
</head>
<body class="bg-surface font-body text-on-surface antialiased" data-theme="student">

<header class="fixed top-0 w-full z-50 bg-[#fff7fd] flex justify-between items-center px-6 lg:px-8 py-4 border-b border-[#cec3d0]/20 backdrop-blur-sm lg:pl-64 pl-0">
    <div class="flex items-center gap-4">
        <button id="sidebar-toggle" class="lg:hidden p-2 text-primary rounded-lg hover:bg-surface-container transition-colors" aria-label="Toggle menu">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center overflow-hidden">
                <img alt="University Logo" class="h-8 w-8 object-contain" src="{{ asset('images/logo.png') }}" />
            </div>
            <span class="text-xl font-headline font-semibold text-primary hidden sm:block">OJT Portal</span>
        </div>
    </div>
</header>

@include('components.student-sidebar')
<div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden" onclick="closeSidebar()"></div>

<main class="lg:ml-64 ml-0 pt-20 min-h-screen pb-20 lg:pb-0">
    <div class="p-5 lg:p-8 max-w-4xl mx-auto space-y-6">

        <header class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-surface-variant/30 pb-6">
            <div>
                <a href="{{ route('student.logs.index') }}" class="text-xs font-bold text-outline hover:text-primary mb-2 inline-flex items-center gap-1 transition-colors">
                    <span class="material-symbols-outlined text-[16px]">arrow_back</span> Back to Logs
                </a>
                <h1 class="text-3xl lg:text-4xl font-extrabold font-headline tracking-tight text-primary">Edit OJT Log</h1>
                <p class="text-on-surface-variant mt-1.5 font-medium text-sm">Resubmit your rejected log for date: {{ \Carbon\Carbon::parse($ojtLog->log_date)->format('M d, Y') }}</p>
            </div>
        </header>

        <section class="bg-surface-container-lowest rounded-xl shadow-sm border border-surface-variant/20 overflow-hidden">
            @if($ojtLog->remarks)
            <div class="bg-error/10 text-error p-6 border-b border-error/20 flex gap-3">
                <span class="material-symbols-outlined text-error">error</span>
                <div>
                    <h4 class="font-bold text-sm uppercase tracking-wide">Supervisor Remarks</h4>
                    <p class="text-sm mt-1 whitespace-pre-wrap">{{ $ojtLog->remarks }}</p>
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('student.logs.update', $ojtLog->id) }}" id="shift-form" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="hours_rendered" id="hours_rendered_input" value="{{ $ojtLog->hours_rendered }}">

                @if($errors->any())
                    <div class="bg-error/10 text-error p-4 text-sm border-b border-error/20">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex items-center justify-between px-6 pt-6 pb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined text-xl">edit_document</span>
                        </div>
                        <h2 class="text-xl font-bold font-headline text-primary">Log Details</h2>
                    </div>
                </div>

                {{-- ── Session Grid ── --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 px-6 pb-4">
                    {{-- Morning Session --}}
                    <div class="bg-surface-container-low rounded-xl p-4 border border-surface-variant/20">
                        <div class="flex items-center gap-1.5 mb-4">
                            <span class="material-symbols-outlined text-secondary text-lg">wb_sunny</span>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Morning Session</p>
                        </div>
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div>
                                <label class="block text-[10px] font-semibold text-outline uppercase tracking-wide mb-1.5">Clock In</label>
                                <div class="relative">
                                    <input type="time" name="am_clock_in" id="am_clock_in" value="{{ old('am_clock_in', $ojtLog->morning_in ? \Carbon\Carbon::parse($ojtLog->morning_in)->format('H:i') : '') }}"
                                           class="w-full bg-surface-container-highest border-none rounded-lg py-2.5 pl-3 pr-9 text-sm font-bold text-on-surface focus:ring-2 focus:ring-primary/40 transition-all appearance-none time-input"/>
                                    <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-[18px] text-outline pointer-events-none">schedule</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold text-outline uppercase tracking-wide mb-1.5">Clock Out</label>
                                <div class="relative">
                                    <input type="time" name="am_clock_out" id="am_clock_out" value="{{ old('am_clock_out', $ojtLog->morning_out ? \Carbon\Carbon::parse($ojtLog->morning_out)->format('H:i') : '') }}"
                                           class="w-full bg-surface-container-highest border-none rounded-lg py-2.5 pl-3 pr-9 text-sm font-bold text-on-surface focus:ring-2 focus:ring-primary/40 transition-all appearance-none time-input"/>
                                    <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-[18px] text-outline pointer-events-none">schedule</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-3 border-t border-surface-variant/20">
                            <span class="text-xs text-on-surface-variant font-medium">Duration</span>
                            <span id="morning-duration" class="text-sm font-extrabold font-headline text-primary">0 hrs 0 mins</span>
                        </div>
                    </div>

                    {{-- Afternoon Session --}}
                    <div class="bg-surface-container-low rounded-xl p-4 border border-surface-variant/20">
                        <div class="flex items-center gap-1.5 mb-4">
                            <span class="material-symbols-outlined text-secondary text-lg">wb_twilight</span>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Afternoon Session</p>
                        </div>
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div>
                                <label class="block text-[10px] font-semibold text-outline uppercase tracking-wide mb-1.5">Clock In</label>
                                <div class="relative">
                                    <input type="time" name="pm_clock_in" id="pm_clock_in" value="{{ old('pm_clock_in', $ojtLog->afternoon_in ? \Carbon\Carbon::parse($ojtLog->afternoon_in)->format('H:i') : '') }}"
                                           class="w-full bg-surface-container-highest border-none rounded-lg py-2.5 pl-3 pr-9 text-sm font-bold text-on-surface focus:ring-2 focus:ring-primary/40 transition-all appearance-none time-input"/>
                                    <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-[18px] text-outline pointer-events-none">schedule</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold text-outline uppercase tracking-wide mb-1.5">Clock Out</label>
                                <div class="relative">
                                    <input type="time" name="pm_clock_out" id="pm_clock_out" value="{{ old('pm_clock_out', $ojtLog->afternoon_out ? \Carbon\Carbon::parse($ojtLog->afternoon_out)->format('H:i') : '') }}"
                                           class="w-full bg-surface-container-highest border-none rounded-lg py-2.5 pl-3 pr-9 text-sm font-bold text-on-surface focus:ring-2 focus:ring-primary/40 transition-all appearance-none time-input"/>
                                    <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-[18px] text-outline pointer-events-none">schedule</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-3 border-t border-surface-variant/20">
                            <span class="text-xs text-on-surface-variant font-medium">Duration</span>
                            <span id="afternoon-duration" class="text-sm font-extrabold font-headline text-primary">0 hrs 0 mins</span>
                        </div>
                    </div>
                </div>

                {{-- ── Activity Summary & Photo Upload ── --}}
                <div class="px-6 mb-5">
                    <label class="block text-[10px] font-bold text-outline uppercase tracking-wide mb-2">Daily Activity Summary</label>
                    <textarea name="activity_summary" rows="3" placeholder="What did you work on today? Briefly describe your tasks and accomplishments..." class="w-full bg-surface-container-highest border-none rounded-xl p-4 text-sm font-medium text-on-surface focus:ring-2 focus:ring-primary/40 transition-all resize-none">{{ old('activity_summary', $ojtLog->tasks_performed) }}</textarea>
                    
                    <!-- Photo Upload Zone -->
                    <div id="photo_drop_zone" class="mt-4 border-2 border-dashed border-outline/40 hover:bg-gray-50 hover:border-outline/60 transition-all rounded-xl p-8 flex flex-col items-center justify-center cursor-pointer group relative {{ $ojtLog->photo_path ? 'hidden' : '' }}">
                        <input type="file" id="photo_attachment" name="photo_attachment" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/png, image/jpeg, image/jpg" title="Drag & Drop photo here">
                        <div id="photo_placeholder" class="flex flex-col items-center pointer-events-none w-full">
                            <div class="w-12 h-12 bg-primary/10 text-primary rounded-full flex items-center justify-center mb-3 group-hover:scale-110 group-hover:bg-primary/20 transition-all duration-300">
                                <span class="material-symbols-outlined text-2xl">cloud_upload</span>
                            </div>
                            <p class="text-sm font-bold text-on-surface mb-1">Drag & Drop photo to update (Optional)</p>
                            <p class="text-[11px] text-on-surface-variant mb-4">Max 5MB (JPG/PNG).</p>
                            <button type="button" class="bg-blue-100 text-blue-700 group-hover:bg-blue-200 transition-colors px-6 py-2 rounded-lg text-sm font-bold shadow-sm">
                                Browse Files
                            </button>
                        </div>
                    </div>

                    <div id="photo_preview_container" class="mt-4 w-full relative group {{ $ojtLog->photo_path ? '' : 'hidden' }}">
                        <img id="photo_preview" src="{{ $ojtLog->photo_path ? asset('storage/' . $ojtLog->photo_path) : '#' }}" alt="Preview" class="max-h-48 object-contain rounded-lg mx-auto shadow-sm">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center z-20">
                            <button type="button" id="remove_photo_btn" class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-md hover:bg-red-600 transition-colors flex items-center gap-2 relative z-30">
                                <span class="material-symbols-outlined text-sm">delete</span> Change Photo
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ── Overtime Toggle ── --}}
                <div class="mx-6 mb-5 flex items-center justify-between bg-[#faf1f8] rounded-xl px-4 py-3.5 border border-primary/8">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center text-primary flex-shrink-0">
                            <span class="material-symbols-outlined text-lg">more_time</span>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-on-surface">Overtime Hours</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0 ml-4">
                        <input type="checkbox" id="overtime_toggle" name="has_overtime" {{ old('has_overtime', $ojtLog->has_overtime) ? 'checked' : '' }} class="hidden peer"/>
                        <div class="w-11 h-6 bg-surface-container-highest rounded-full peer
                                    peer-checked:bg-primary
                                    after:content-[''] after:absolute after:top-0.5 after:left-0.5
                                    after:bg-white after:rounded-full after:h-5 after:w-5
                                    after:transition-all after:shadow-sm
                                    peer-checked:after:translate-x-5 transition-colors duration-200">
                        </div>
                    </label>
                </div>

                {{-- ── Overtime Container ── --}}
                <div id="overtime_inputs_container" class="hidden transition-all duration-300 bg-purple-50/50 border border-purple-100 rounded-xl p-4 mt-3 mx-6 mb-5">
                    <h4 class="text-sm font-semibold text-purple-900 mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm text-purple-700">more_time</span> Overtime Session
                    </h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Clock In</label>
                            <div class="relative w-full">
                                <input type="time" name="ot_clock_in" id="ot_clock_in" value="{{ old('ot_clock_in', $ojtLog->ot_clock_in ? \Carbon\Carbon::parse($ojtLog->ot_clock_in)->format('H:i') : '') }}" class="w-full bg-white border border-gray-200 rounded-lg p-2.5 text-sm focus:ring-purple-500 focus:border-purple-500 transition-all">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-purple-400 pointer-events-none text-base">schedule</span>
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Clock Out</label>
                            <div class="relative w-full">
                                <input type="time" name="ot_clock_out" id="ot_clock_out" value="{{ old('ot_clock_out', $ojtLog->ot_clock_out ? \Carbon\Carbon::parse($ojtLog->ot_clock_out)->format('H:i') : '') }}" class="w-full bg-white border border-gray-200 rounded-lg p-2.5 text-sm focus:ring-purple-500 focus:border-purple-500 transition-all">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-purple-400 pointer-events-none text-base">schedule</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right mt-2 text-xs font-semibold text-purple-900">
                        Duration: <span id="ot_duration_display">0 hrs 0 mins</span>
                    </div>
                </div>

                {{-- ── Footer ── --}}
                <div class="flex items-center justify-between px-6 py-4 border-t border-surface-variant/20 bg-surface-container-lowest">
                    <div>
                        <p class="text-xs text-on-surface-variant font-medium mb-0.5">Total Shift Duration:</p>
                        <p id="total-duration" class="text-3xl font-extrabold font-headline text-primary leading-none">0 <span class="text-lg font-bold text-on-surface-variant">hrs</span> 0 <span class="text-lg font-bold text-on-surface-variant">mins</span></p>
                    </div>
                    <button type="submit" id="save-shift-btn"
                            class="flex items-center gap-2 bg-primary text-on-primary px-6 py-3 rounded-xl font-bold text-sm shadow-lg shadow-primary/25 hover:opacity-90 active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings:'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 24;">save</span>
                        Submit Changes
                    </button>
                </div>
            </form>
        </section>

    </div>
</main>

<script>
    // ── Duration calculator & Strict Validation ──
    const saveBtn = document.getElementById('save-shift-btn');
    const form = document.getElementById('shift-form');

    function timeToMinutes(timeString) {
        if (!timeString) return null;
        const [hours, minutes] = timeString.split(':').map(Number);
        return (hours * 60) + minutes;
    }

    function calculateSessionDuration(timeInId, timeOutId, displayId) {
        const timeInEl = document.getElementById(timeInId);
        const timeOutEl = document.getElementById(timeOutId);

        if (!timeInEl || !timeOutEl || !timeInEl.value || !timeOutEl.value) {
            const displayEl = document.getElementById(displayId);
            if (displayEl) displayEl.innerText = '0 hrs 0 mins';
            return 0;
        }

        const minutesIn = timeToMinutes(timeInEl.value);
        const minutesOut = timeToMinutes(timeOutEl.value);

        let diffMinutes = minutesOut - minutesIn;
        if (diffMinutes < 0) diffMinutes += 24 * 60; 

        const hrs = Math.floor(diffMinutes / 60);
        const mins = diffMinutes % 60;
        
        const displayEl = document.getElementById(displayId);
        if (displayEl) displayEl.innerText = `${hrs} hrs ${mins} min${mins !== 1 ? 's' : ''}`;
        
        timeOutEl.classList.remove('ring-2', 'ring-error', 'text-error');
        
        return diffMinutes;
    }

    function updateDurations() {
        const morningMins = calculateSessionDuration('am_clock_in', 'am_clock_out', 'morning-duration');
        const afternoonMins = calculateSessionDuration('pm_clock_in', 'pm_clock_out', 'afternoon-duration');
        
        let otMins = 0;
        const otToggle = document.getElementById('overtime_toggle');
        
        if (otToggle && otToggle.checked) {
            otMins = calculateSessionDuration('ot_clock_in', 'ot_clock_out', 'ot_duration_display');
        } else {
            const otDisplay = document.getElementById('ot_duration_display');
            if(otDisplay) otDisplay.innerText = '0 hrs 0 mins';
        }

        const totalMinutes = morningMins + afternoonMins + otMins;
        
        const totalHrs = Math.floor(totalMinutes / 60);
        const totalMins = totalMinutes % 60;

        const totalDurationEl = document.getElementById('total-duration');
        if (totalDurationEl) {
            totalDurationEl.innerHTML = `${totalHrs} <span class="text-lg font-bold text-on-surface-variant">hrs</span> ${totalMins} <span class="text-lg font-bold text-on-surface-variant">min${totalMins !== 1 ? 's' : ''}</span>`;
        }
        
        const paddedMinutesString = totalMins < 10 ? '0' + totalMins : totalMins;
        const humanDecimalValue = `${totalHrs}.${paddedMinutesString}`;
        
        const hiddenInput = document.getElementById('hours_rendered_input');
        if (hiddenInput) {
            hiddenInput.value = parseFloat(humanDecimalValue).toFixed(2);
        }

        if (saveBtn) {
            saveBtn.disabled = totalMinutes === 0;
        }
    }

    ['am_clock_in','am_clock_out','pm_clock_in','pm_clock_out','ot_clock_in','ot_clock_out'].forEach(id => {
        document.getElementById(id)?.addEventListener('change', updateDurations);
    });

    if (form) {
        form.addEventListener('submit', function(e) {
            updateDurations(); // final check
            if (saveBtn && saveBtn.disabled) {
                e.preventDefault();
                alert('Invalid time sequence detected. Please ensure your Clock Out time is logically after your Clock In time, and that you have valid hours rendered.');
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        updateDurations(); 
        
        const otToggle = document.getElementById('overtime_toggle');
        const otContainer = document.getElementById('overtime_inputs_container');
        if (otToggle && otToggle.checked) {
            otContainer.classList.remove('hidden');
        }
    });

    const otToggle = document.getElementById('overtime_toggle');
    const otContainer = document.getElementById('overtime_inputs_container');
    const otClockIn = document.getElementById('ot_clock_in');
    const otClockOut = document.getElementById('ot_clock_out');

    if (otToggle) {
        otToggle.addEventListener('change', function() {
            if (this.checked) {
                otContainer.classList.remove('hidden');
            } else {
                otContainer.classList.add('hidden');
                if(otClockIn) otClockIn.value = '';
                if(otClockOut) otClockOut.value = '';
                if(otClockIn) otClockIn.classList.remove('ring-2', 'ring-error', 'text-error');
                if(otClockOut) otClockOut.classList.remove('ring-2', 'ring-error', 'text-error');
            }
            updateDurations();
        });
    }

    // ── Photo Upload Logic ──
    const photoDropZone = document.getElementById('photo_drop_zone');
    const photoInput = document.getElementById('photo_attachment');
    const photoPreviewContainer = document.getElementById('photo_preview_container');
    const photoPreview = document.getElementById('photo_preview');
    const removePhotoBtn = document.getElementById('remove_photo_btn');

    if (photoDropZone && photoInput) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            photoDropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            photoDropZone.addEventListener(eventName, () => {
                photoDropZone.classList.add('border-primary', 'bg-purple-50');
                photoDropZone.classList.remove('border-outline/40');
            }, false);
        });

        photoDropZone.addEventListener('dragleave', () => {
            photoDropZone.classList.remove('border-primary', 'bg-purple-50');
            photoDropZone.classList.add('border-outline/40');
        }, false);

        photoDropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            photoDropZone.classList.remove('border-primary', 'bg-purple-50'); // Remove active hover styles
            photoDropZone.classList.add('border-outline/40');

            const droppedFiles = e.dataTransfer.files;

            if (droppedFiles.length > 0 && droppedFiles[0].type.match('image.*')) {
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(droppedFiles[0]);
                photoInput.files = dataTransfer.files;

                const reader = new FileReader();
                reader.onload = function(event) {
                    photoPreview.src = event.target.result;
                    photoDropZone.classList.add('hidden');
                    photoPreviewContainer.classList.remove('hidden');
                };
                reader.readAsDataURL(droppedFiles[0]);

            } else {
                alert('Please drop a valid image file (PNG, JPG, or JPEG).');
            }
        });
        
        photoInput.addEventListener('change', function() {
            if (this.files && this.files.length > 0 && this.files[0].type.match('image.*')) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    photoPreview.src = event.target.result;
                    photoDropZone.classList.add('hidden');
                    photoPreviewContainer.classList.remove('hidden');
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        if (removePhotoBtn) {
            removePhotoBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                photoInput.value = ''; // Clear input
                photoPreview.src = '#';
                
                photoPreviewContainer.classList.add('hidden');
                photoDropZone.classList.remove('hidden');
            });
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
