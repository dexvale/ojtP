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
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            background: white;
            cursor: text;
        }

        .pdf-text-input {
            position: absolute;
            background: transparent;
            border: 1px dashed rgba(147, 51, 234, 0.5); /* purple-600 */
            color: black;
            font-family: Helvetica, Arial, sans-serif;
            font-size: 16px; /* Matches 12pt visually in browser, adjusted dynamically later */
            padding: 0;
            margin: 0;
            outline: none;
            line-height: 1;
            min-width: 10px;
            white-space: nowrap;
        }
        
        .pdf-text-input:focus {
            border: 1px solid rgba(147, 51, 234, 1);
            background: rgba(255, 255, 255, 0.9);
        }
    </style>
</head>

<body class="bg-surface font-body text-on-surface antialiased h-screen overflow-hidden flex flex-col" data-theme="student">
    <!-- TopNavBar -->
    <header class="w-full bg-[#fff7fd] flex justify-between items-center px-6 py-4 border-b border-[#cec3d0]/20 flex-shrink-0 z-10 relative">
        <div class="flex items-center gap-4">
            <a href="{{ route('student.requirements') }}" class="text-primary hover:text-purple-900 transition flex items-center gap-1 font-semibold text-sm bg-purple-50 px-3 py-1.5 rounded-lg">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Back
            </a>
            <div class="w-px h-6 bg-slate-200 mx-2"></div>
            <div>
                <h1 class="text-lg font-bold text-slate-800 font-headline">{{ $requirement->title }}</h1>
                <p class="text-[10px] text-slate-500 font-medium uppercase tracking-wider flex items-center gap-1">
                    <span class="material-symbols-outlined text-[12px] text-purple-600">touch_app</span>
                    Click on the document to type
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            @if($errors->any())
                <div class="text-xs font-bold text-rose-600 bg-rose-50 px-3 py-1.5 rounded-lg border border-rose-200">
                    Error submitting document.
                </div>
            @endif
            <button onclick="submitStamps()" class="bg-[#300050] hover:bg-purple-950 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-md transition active:scale-95 flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">assignment_turned_in</span>
                Stamp & Submit
            </button>
        </div>
    </header>

    <!-- Main Content: Centered PDF -->
    <main class="flex-1 bg-slate-200 overflow-auto w-full flex justify-center p-8 relative">
        
        <div id="pdf-wrapper" class="pdf-page-container">
            <canvas id="pdf-canvas"></canvas>
            <div id="overlay-layer" class="absolute inset-0 z-10 w-full h-full cursor-text"></div>
        </div>

        <!-- Loader -->
        <div id="loader" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-200/80 backdrop-blur-sm z-50">
            <span class="material-symbols-outlined text-4xl text-purple-600 animate-spin mb-2">refresh</span>
            <p class="text-sm font-bold text-slate-600">Loading Document...</p>
        </div>
    </main>

    <!-- Hidden Form for Submission -->
    <form id="stampForm" action="{{ route('student.requirements.submitForm', $requirement->id) }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="stamps" id="stampsInput">
    </form>

    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';

        const pdfUrl = "{{ asset('storage/' . $requirement->template_path) }}";
        const canvas = document.getElementById('pdf-canvas');
        const ctx = canvas.getContext('2d');
        const wrapper = document.getElementById('pdf-wrapper');
        const overlay = document.getElementById('overlay-layer');
        const loader = document.getElementById('loader');

        let currentScale = 1.25; 

        // Make it responsive for mobile devices
        if (window.innerWidth < 768) {
            // A standard A4 document is ~595 points wide. We leave some padding.
            currentScale = (window.innerWidth - 40) / 595;
        }

        // Load PDF
        pdfjsLib.getDocument(pdfUrl).promise.then(pdf => {
            return pdf.getPage(1);
        }).then(page => {
            // Re-calculate the viewport with our responsive scale
            const viewport = page.getViewport({ scale: currentScale });
            
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            const renderContext = {
                canvasContext: ctx,
                viewport: viewport
            };

            page.render(renderContext).promise.then(() => {
                loader.classList.add('hidden');
            });
        }).catch(err => {
            console.error('Error loading PDF:', err);
            loader.innerHTML = `<p class="text-rose-600 font-bold">Failed to load document.</p>`;
        });

        // Handle Clicking to add text
        overlay.addEventListener('click', function(e) {
            // Only spawn if clicking directly on the overlay, not on an existing input
            if (e.target !== overlay) return;

            const rect = overlay.getBoundingClientRect();
            // Get coordinates relative to the overlay top-left
            let x = e.clientX - rect.left;
            let y = e.clientY - rect.top;

            // Adjust Y slightly so the text baseline feels right where they clicked
            y = y - 10; 

            createTextInput(x, y);
        });

        function createTextInput(x, y) {
            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'pdf-text-input';
            input.style.left = x + 'px';
            input.style.top = y + 'px';
            input.placeholder = "Type here...";

            // Auto-resize width based on content
            input.addEventListener('input', function() {
                this.style.width = ((this.value.length + 1) * 9) + 'px';
            });

            // Prevent click from bubbling to overlay
            input.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            // Remove if empty on blur
            input.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.remove();
                }
            });

            overlay.appendChild(input);
            input.focus();
        }

        function submitStamps() {
            const inputs = overlay.querySelectorAll('.pdf-text-input');
            const stamps = [];

            inputs.forEach(input => {
                const val = input.value.trim();
                if (val !== '') {
                    // Extract pixel coordinates from inline styles
                    const pxX = parseFloat(input.style.left);
                    const pxY = parseFloat(input.style.top);

                    // Convert to PDF points (pt). 
                    // Our canvas is rendered at `currentScale` of the original PDF points.
                    // So we divide the pixel coordinate by the scale to get the true PDF point coordinate.
                    // Note: In FPDF, the Y coordinate is the TOP of the text string.
                    // We also need to add a small baseline offset because browsers position inputs differently than PDF rendering text.
                    // A rough offset of +12px (scaled) for FPDF's text baseline usually aligns well.
                    
                    const pdfX = pxX / currentScale;
                    const pdfY = (pxY / currentScale) + (12 / currentScale); 

                    stamps.push({
                        text: val,
                        x: pdfX,
                        y: pdfY
                    });
                }
            });

            if (stamps.length === 0) {
                alert("You haven't typed anything on the document yet.");
                return;
            }

            document.getElementById('stampsInput').value = JSON.stringify(stamps);
            document.getElementById('stampForm').submit();
        }
    </script>
</body>

</html>
