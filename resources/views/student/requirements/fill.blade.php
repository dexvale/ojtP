<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>OJT Portal | Fill Document</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link
        href="https://fonts.googleapis.com/css2?family=Newsreader:opsz,wght@6..72,400;6..72,600;6..72,700;6..72,800&family=Public+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">
    
    <!-- PDF.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>

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

        .pdf-page-container {
            position: relative;
            display: inline-block;
            box-shadow: 0 12px 30px -6px rgba(0, 0, 0, 0.15), 0 8px 12px -6px rgba(0, 0, 0, 0.1);
            background: white;
            cursor: crosshair;
            user-select: none;
        }

        .pdf-input-wrapper {
            position: absolute;
            display: inline-flex;
            align-items: center;
            z-index: 20;
            background: transparent;
            user-select: none;
            padding: 0;
            margin: 0;
        }

        .pdf-input-wrapper:hover,
        .pdf-input-wrapper:focus-within {
            z-index: 40;
        }

        .drag-handle {
            position: absolute;
            right: 100%;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0;
            pointer-events: none;
            cursor: grab;
            padding: 2px 3px;
            color: #9333ea;
            background: rgba(243, 232, 255, 0.95);
            border: 1px solid rgba(147, 51, 234, 0.3);
            border-radius: 4px;
            font-size: 13px;
            line-height: 1;
            margin-right: 3px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.15s ease;
        }
        .drag-handle:active {
            cursor: grabbing;
        }

        .pdf-input-wrapper:hover .drag-handle,
        .pdf-input-wrapper:focus-within .drag-handle {
            opacity: 1;
            pointer-events: auto;
        }

        .pdf-text-input {
            background: transparent;
            border: 1px solid transparent;
            border-bottom: 1.5px solid rgba(147, 51, 234, 0.5);
            color: #0f172a;
            font-family: Helvetica, Arial, sans-serif;
            font-size: 11px;
            font-weight: 600;
            padding: 0px 3px;
            margin: 0;
            outline: none;
            line-height: 1.1;
            min-width: 25px;
            border-radius: 2px;
            box-sizing: border-box;
            transition: background 0.15s, border-color 0.15s;
        }

        .pdf-input-wrapper:hover .pdf-text-input {
            border: 1px dashed rgba(147, 51, 234, 0.7);
            background: rgba(243, 232, 255, 0.45);
        }
        
        .pdf-input-wrapper:focus-within .pdf-text-input {
            border: 1.5px solid #7e22ce;
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 0 0 2px rgba(168, 85, 247, 0.25);
        }

        .input-remove-btn {
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0;
            pointer-events: none;
            margin-left: 3px;
            width: 16px;
            height: 16px;
            background: #f43f5e;
            color: white;
            border-radius: 50%;
            font-size: 11px;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 1px 2px rgba(0,0,0,0.25);
            flex-shrink: 0;
            transition: opacity 0.15s ease;
        }

        .pdf-input-wrapper:hover .input-remove-btn,
        .pdf-input-wrapper:focus-within .input-remove-btn {
            opacity: 1;
            pointer-events: auto;
        }

        .chip-scroll::-webkit-scrollbar {
            height: 4px;
        }
        .chip-scroll::-webkit-scrollbar-thumb {
            background: rgba(147, 51, 234, 0.3);
            border-radius: 4px;
        }
    </style>
</head>

<body class="bg-slate-100 font-body text-slate-800 antialiased h-screen overflow-hidden flex flex-col" data-theme="student">
    
    <!-- ═══════════════════════════════
         TOP NAVBAR & ACTIONS
    ═══════════════════════════════ -->
    <header class="w-full bg-white/95 backdrop-blur-md flex flex-col border-b border-slate-200 flex-shrink-0 z-30 shadow-xs">
        <div class="flex items-center justify-between px-3 sm:px-6 py-2.5 sm:py-3 gap-2 sm:gap-4">
            
            <!-- Left: Back & Title -->
            <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                <a href="{{ route('student.requirements') }}" class="text-slate-700 hover:text-purple-900 transition flex items-center gap-1 font-bold text-xs bg-slate-100 hover:bg-purple-50 px-2.5 sm:px-3 py-2 rounded-lg shrink-0">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    <span class="hidden sm:inline">Back</span>
                </a>
                <div class="w-px h-5 bg-slate-200 shrink-0"></div>
                <div class="truncate">
                    <h1 class="text-sm sm:text-lg font-bold text-slate-900 font-headline leading-tight truncate">
                        {{ $requirement->title }}
                    </h1>
                    <p class="text-[10px] sm:text-[11px] text-slate-500 font-medium hidden xs:flex items-center gap-1">
                        <span class="material-symbols-outlined text-[13px] text-purple-600">touch_app</span>
                        <span>Click anywhere on document to type, or use Quick Insert chips</span>
                    </p>
                </div>
            </div>

            <!-- Right: Actions -->
            <div class="flex items-center gap-2 shrink-0">
                <button type="button" onclick="clearAllInputs()" title="Clear all text fields on the document"
                        class="px-2.5 sm:px-3 py-2 bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 rounded-xl font-bold text-xs border border-slate-200 transition flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">clear_all</span>
                    <span class="hidden md:inline">Clear All</span>
                </button>

                <button type="button" onclick="downloadFilledPdf()" title="Download typed PDF to print and collect physical pen signatures and official seals"
                        style="background-color: #4338ca; color: #ffffff;"
                        class="px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl font-bold text-xs sm:text-sm shadow-md transition active:scale-95 flex items-center gap-1.5 sm:gap-2 cursor-pointer hover:opacity-90">
                    <span class="material-symbols-outlined text-[18px]" style="color: #ffffff;">print</span>
                    <span style="color: #ffffff; font-weight: 700;">Download & Print</span>
                    <span class="hidden md:inline text-[10px] font-normal px-1.5 py-0.5 rounded" style="background-color: rgba(255, 255, 255, 0.2); color: #ffffff;">(For Signing)</span>
                </button>

                <button type="button" onclick="submitStamps()" title="Directly submit filled document if no physical signatures/seals are needed"
                        class="bg-[#300050] hover:bg-purple-950 text-white px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl font-bold text-xs sm:text-sm shadow-md hover:shadow-purple-900/20 transition active:scale-95 flex items-center gap-1.5 sm:gap-2">
                    <span class="material-symbols-outlined text-[18px]">assignment_turned_in</span>
                    <span>Stamp & Submit</span>
                </button>
            </div>
        </div>

        <!-- ═══════════════════════════════
             SMART QUICK-INSERT CHIPS BAR
        ═══════════════════════════════ -->
        <div class="bg-purple-50/70 border-t border-purple-100 px-4 sm:px-6 py-2 flex items-center gap-2 overflow-x-auto chip-scroll text-xs">
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-purple-900 flex-shrink-0 flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">content_paste</span> Quick Insert:
            </span>

            @php
                $chips = [
                    'Name' => $studentData['fullName'] ?? '',
                    'ID No.' => $studentData['studentId'] ?? '',
                    'Course' => $studentData['course'] ?? '',
                    'Company' => $studentData['companyName'] ?? '',
                    'Supervisor' => $studentData['supervisorName'] ?? '',
                    'Hours' => $studentData['hours'] ?? '',
                    'Address' => $studentData['address'] ?? '',
                    'Phone' => $studentData['contactNumber'] ?? '',
                    'Guardian' => $studentData['guardian'] ?? '',
                    'Emergency' => $studentData['emergencyPerson'] ?? '',
                    'Date' => $studentData['currentDate'] ?? '',
                    'Dean' => $studentData['deanName'] ?? '',
                ];
            @endphp

            @foreach($chips as $label => $val)
                @if(!empty($val))
                    <button type="button" onclick="setQuickClipboard('{{ addslashes($val) }}', this)"
                            class="quick-chip flex-shrink-0 bg-white hover:bg-purple-600 hover:text-white border border-purple-200 rounded-lg px-2.5 py-1 text-[11px] font-semibold text-purple-950 shadow-xs transition flex items-center gap-1 group">
                        <span class="text-purple-400 group-hover:text-purple-200 text-[9px] uppercase font-bold">{{ $label }}:</span>
                        <span>{{ Str::limit($val, 24) }}</span>
                    </button>
                @endif
            @endforeach
        </div>

        <!-- Signing Guidance Info Banner -->
        <div class="bg-amber-50 border-t border-amber-200/70 px-4 sm:px-6 py-1.5 flex items-center justify-between text-[11px] text-amber-900">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-amber-600 text-[16px] shrink-0">info</span>
                <span>
                    <strong>Need physical signatures or official seals?</strong> Fill in your details, click <strong class="text-indigo-950 font-bold">Download Filled PDF</strong>, print it out for signing and dry seals, then upload the scanned copy under <strong class="text-purple-950 font-bold">Submit File</strong> on your dashboard.
                </span>
            </div>
        </div>
    </header>

    <!-- ═══════════════════════════════════════════════════════
         DOCUMENT CANVAS VIEW
    ════════════════════════════════════════════════════════════ -->
    <main class="flex-1 bg-slate-200 overflow-auto w-full flex flex-col items-center p-4 sm:p-8 relative" id="main-scroll-container">
        
        <div id="pages-container" class="flex flex-col items-center gap-8 sm:gap-10 w-full pb-20"></div>

        <!-- Loader -->
        <div id="loader" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-200/90 backdrop-blur-xs z-50">
            <span class="material-symbols-outlined text-4xl text-purple-600 animate-spin mb-2">refresh</span>
            <p class="text-sm font-bold text-slate-700">Loading Document Pages...</p>
        </div>
    </main>

    <!-- Hidden Form for Final Submission -->
    <form id="stampForm" action="{{ route('student.requirements.submitForm', $requirement->id) }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="stamps" id="stampsInput">
    </form>

    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';

        const pdfUrl = "{{ asset('storage/' . $requirement->template_path) }}";
        const pagesContainer = document.getElementById('pages-container');
        const loader = document.getElementById('loader');

        let quickClipboardText = null;

        function setQuickClipboard(text, btnElement) {
            quickClipboardText = text;

            document.querySelectorAll('.quick-chip').forEach(c => {
                c.classList.remove('bg-purple-700', 'text-white', 'ring-2', 'ring-purple-400');
            });
            if (btnElement) {
                btnElement.classList.add('bg-purple-700', 'text-white', 'ring-2', 'ring-purple-400');
            }
            
            const existingToast = document.getElementById('clipboard-toast');
            if (existingToast) existingToast.remove();

            const toast = document.createElement('div');
            toast.id = 'clipboard-toast';
            toast.className = 'fixed bottom-6 right-6 bg-slate-900 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-xl z-50 flex items-center gap-2 animate-bounce';
            toast.innerHTML = `<span class="material-symbols-outlined text-emerald-400 text-base">check_circle</span> Copied "${text.length > 22 ? text.substring(0, 22) + '...' : text}". Tap anywhere on the PDF to place!`;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 2800);
        }

        // Render PDF Document Canvas
        pdfjsLib.getDocument(pdfUrl).promise.then(async (pdf) => {
            const numPages = pdf.numPages;

            for (let pageNum = 1; pageNum <= numPages; pageNum++) {
                const page = await pdf.getPage(pageNum);
                
                const unscaledViewport = page.getViewport({ scale: 1.0 });
                let pageScale = 1.25;
                if (window.innerWidth < 768) {
                    pageScale = (window.innerWidth - 32) / unscaledViewport.width;
                }
                const viewport = page.getViewport({ scale: pageScale });

                // Page Wrapper Container
                const pageWrapper = document.createElement('div');
                pageWrapper.className = 'pdf-page-container rounded-xl shadow-xl relative bg-white my-3 border border-slate-300';
                pageWrapper.dataset.page = pageNum;
                pageWrapper.dataset.scale = pageScale;
                pageWrapper.id = `pdf-page-${pageNum}`;

                // Page Number Header
                const pageBadge = document.createElement('div');
                pageBadge.className = 'absolute -top-7 left-0 flex items-center gap-2';
                pageBadge.innerHTML = `<span class="bg-slate-800 text-white text-[10px] font-bold tracking-wider uppercase px-2.5 py-0.5 rounded-md shadow">Page ${pageNum} of ${numPages}</span>`;
                pageWrapper.appendChild(pageBadge);

                // Canvas for PDF rendering
                const canvas = document.createElement('canvas');
                canvas.height = viewport.height;
                canvas.width = viewport.width;
                canvas.className = 'block rounded-xl';
                pageWrapper.appendChild(canvas);

                // Interactive Overlay Layer
                const overlay = document.createElement('div');
                overlay.className = 'overlay-layer absolute inset-0 z-10 w-full h-full';
                overlay.dataset.page = pageNum;
                overlay.dataset.scale = pageScale;
                pageWrapper.appendChild(overlay);

                pagesContainer.appendChild(pageWrapper);

                const ctx = canvas.getContext('2d');
                await page.render({
                    canvasContext: ctx,
                    viewport: viewport
                }).promise;

                // Click event for placing or typing inputs
                overlay.addEventListener('click', function(e) {
                    if (e.target !== overlay) return;

                    const rect = overlay.getBoundingClientRect();
                    let x = e.clientX - rect.left;
                    let y = e.clientY - rect.top - 8;

                    const initialText = quickClipboardText || '';
                    createTextInput(overlay, x, y, pageScale, pageNum, initialText);

                    quickClipboardText = null;
                    document.querySelectorAll('.quick-chip').forEach(c => {
                        c.classList.remove('bg-purple-700', 'text-white', 'ring-2', 'ring-purple-400');
                    });
                });
            }

            loader.classList.add('hidden');

        }).catch(err => {
            console.error('Error loading PDF:', err);
            loader.innerHTML = `<div class="bg-white p-6 rounded-xl shadow-lg border border-rose-200 text-center">
                <span class="material-symbols-outlined text-rose-500 text-4xl mb-2">error</span>
                <p class="text-rose-600 font-bold text-sm">Failed to load document.</p>
                <p class="text-xs text-slate-500 mt-1">Please ensure the template file is accessible.</p>
            </div>`;
        });

        function createTextInput(overlay, x, y, scale, pageNum, text = '') {
            const wrapper = document.createElement('div');
            wrapper.className = 'pdf-input-wrapper';
            wrapper.style.left = x + 'px';
            wrapper.style.top = y + 'px';

            const dragHandle = document.createElement('div');
            dragHandle.className = 'drag-handle';
            dragHandle.innerHTML = '<span class="material-symbols-outlined text-[13px]">drag_indicator</span>';
            dragHandle.title = 'Drag to reposition';

            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'pdf-text-input';
            input.value = text;
            input.dataset.page = pageNum;
            input.dataset.scale = scale;
            input.placeholder = "Type here...";

            function adjustWidth() {
                const len = Math.max(input.value.length, input.placeholder.length);
                input.style.width = Math.max(35, (len + 1) * 7.2) + 'px';
            }
            adjustWidth();
            input.addEventListener('input', adjustWidth);

            // Dragging Logic
            let isDragging = false;
            let startX, startY, initialLeft, initialTop;

            function startDrag(e) {
                isDragging = true;
                const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                startX = clientX;
                startY = clientY;
                initialLeft = parseFloat(wrapper.style.left) || 0;
                initialTop = parseFloat(wrapper.style.top) || 0;

                document.addEventListener('mousemove', onDragMove);
                document.addEventListener('mouseup', endDrag);
                document.addEventListener('touchmove', onDragMove, { passive: false });
                document.addEventListener('touchend', endDrag);
                e.preventDefault();
            }

            function onDragMove(e) {
                if (!isDragging) return;
                const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                const deltaX = clientX - startX;
                const deltaY = clientY - startY;

                let newLeft = initialLeft + deltaX;
                let newTop = initialTop + deltaY;

                const overlayRect = overlay.getBoundingClientRect();
                newLeft = Math.max(0, Math.min(newLeft, overlayRect.width - 40));
                newTop = Math.max(0, Math.min(newTop, overlayRect.height - 20));

                wrapper.style.left = newLeft + 'px';
                wrapper.style.top = newTop + 'px';
            }

            function endDrag() {
                isDragging = false;
                document.removeEventListener('mousemove', onDragMove);
                document.removeEventListener('mouseup', endDrag);
                document.removeEventListener('touchmove', onDragMove);
                document.removeEventListener('touchend', endDrag);
            }

            dragHandle.addEventListener('mousedown', startDrag);
            dragHandle.addEventListener('touchstart', startDrag);

            // Alt + Arrow keys nudging
            input.addEventListener('keydown', function(e) {
                if (e.altKey && ['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
                    e.preventDefault();
                    let curLeft = parseFloat(wrapper.style.left) || 0;
                    let curTop = parseFloat(wrapper.style.top) || 0;
                    const step = e.shiftKey ? 5 : 1;

                    if (e.key === 'ArrowUp') wrapper.style.top = (curTop - step) + 'px';
                    if (e.key === 'ArrowDown') wrapper.style.top = (curTop + step) + 'px';
                    if (e.key === 'ArrowLeft') wrapper.style.left = (curLeft - step) + 'px';
                    if (e.key === 'ArrowRight') wrapper.style.left = (curLeft + step) + 'px';
                }
            });

            wrapper.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'input-remove-btn';
            removeBtn.innerHTML = '&times;';
            removeBtn.title = 'Remove';
            removeBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                wrapper.remove();
            });

            wrapper.appendChild(dragHandle);
            wrapper.appendChild(input);
            wrapper.appendChild(removeBtn);
            overlay.appendChild(wrapper);

            if (!text) {
                input.focus();
            }

            return wrapper;
        }

        function clearAllInputs() {
            if (confirm("Clear all text fields on this document?")) {
                document.querySelectorAll('.pdf-input-wrapper').forEach(w => w.remove());
            }
        }

        function getStampsData() {
            const inputs = document.querySelectorAll('.pdf-text-input');
            const stamps = [];

            inputs.forEach(input => {
                const val = input.value.trim();
                if (val !== '') {
                    const page = parseInt(input.dataset.page, 10) || 1;
                    const scale = parseFloat(input.dataset.scale) || 1.25;
                    const wrapper = input.parentElement;
                    const pxX = parseFloat(wrapper.style.left);
                    const pxY = parseFloat(wrapper.style.top);

                    const pdfX = pxX / scale;
                    const pdfY = (pxY + 12) / scale;

                    stamps.push({
                        page: page,
                        text: val,
                        x: pdfX,
                        y: pdfY
                    });
                }
            });

            return stamps;
        }

        function downloadFilledPdf() {
            const stamps = getStampsData();

            if (stamps.length === 0) {
                alert("Please add your information onto the document before downloading.");
                return;
            }

            const form = document.getElementById('stampForm');
            form.action = "{{ route('student.requirements.downloadFilled', $requirement->id) }}";
            document.getElementById('stampsInput').value = JSON.stringify(stamps);
            form.submit();
        }

        function submitStamps() {
            const stamps = getStampsData();

            if (stamps.length === 0) {
                alert("Please add at least one text field to the document before submitting.");
                return;
            }

            if (confirm("Are you sure you want to directly submit this document?\n\nNOTE: If this requirement requires real physical signatures or official school/company seals (like Parent's Consent or MOA), please click 'Download Filled PDF' instead so you can print it out for signing.")) {
                const form = document.getElementById('stampForm');
                form.action = "{{ route('student.requirements.submitForm', $requirement->id) }}";
                document.getElementById('stampsInput').value = JSON.stringify(stamps);
                form.submit();
            }
        }
    </script>
</body>

</html>
