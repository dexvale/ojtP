<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BISU Grading Sheet - {{ $student->first_name }} {{ $student->last_name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />

    <style>
        body {
            font-family: 'Public Sans', Arial, sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
        }

        .paper-sheet {
            width: 210mm;
            min-height: 297mm;
            padding: 16mm 20mm;
            margin: 20px auto;
            background: #ffffff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            box-sizing: border-box;
            position: relative;
        }

        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .no-print {
                display: none !important;
            }

            .paper-sheet {
                width: 100% !important;
                min-height: auto !important;
                margin: 0 !important;
                padding: 12mm 16mm !important;
                box-shadow: none !important;
                border: none !important;
                page-break-after: always;
                break-after: page;
            }

            .page-break {
                page-break-before: always;
                break-before: page;
            }

            @page {
                size: portrait;
                margin: 10mm;
            }

            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .table-bordered th, .table-bordered td {
            border: 1px solid #1e293b;
        }
    </style>
</head>
<body class="antialiased">

    <!-- Top Action Bar (Screen only) -->
    <header class="no-print bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 py-3 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ url()->previous() }}" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-600 hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    <span>Back</span>
                </a>
                <span class="text-slate-300">|</span>
                <div>
                    <h1 class="text-sm font-bold text-slate-800">BISU Official OJT Grading Sheet & Appraisal</h1>
                    <p class="text-[11px] text-slate-500">Trainee: <strong class="text-slate-700">{{ $student->first_name }} {{ $student->last_name }}</strong> ({{ $student->course ?? $student->academicCourse?->course_name ?? 'OJT Trainee' }})</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                @if(in_array(auth()->user()->role, ['Admin', 'Coordinator']) || auth()->user()->hasRole('Coordinator') || auth()->user()->hasRole('Admin'))
                    @if($evaluation && !$evaluation->approved_at)
                        <form method="POST" action="{{ route('coordinator.students.endorse-evaluation', $student->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm transition-all active:scale-95">
                                <span class="material-symbols-outlined text-[16px]">verified</span>
                                <span>Endorse / Approve Grade</span>
                            </button>
                        </form>
                    @elseif($evaluation && $evaluation->approved_at)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-xs font-bold">
                            <span class="material-symbols-outlined text-[16px]">check_circle</span>
                            <span>Endorsed by {{ $evaluation->approved_by_name ?? 'Coordinator' }}</span>
                        </span>
                    @endif
                @endif

                <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-primary hover:bg-primary/90 text-white text-xs font-bold rounded-lg shadow-sm transition-all active:scale-95">
                    <span class="material-symbols-outlined text-[16px]">print</span>
                    <span>Print Official Document</span>
                </button>
            </div>
        </div>
    </header>

    @if(session('success'))
        <div class="no-print max-w-5xl mx-auto px-4 mt-4">
            <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600 text-base">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- ================= PAGE 1: GRADING SHEET FOR ON-THE-JOB-TRAINING ================= -->
    <div class="paper-sheet">
        <!-- University Header -->
        <div class="flex items-center justify-center gap-4 border-b border-slate-900 pb-3 mb-4">
            <img src="{{ asset('images/BISU-Logo-1-150x150.png.webp') }}" alt="BISU Logo" class="w-16 h-16 object-contain">
            <div class="text-center">
                <p class="text-[11px] font-medium tracking-wide uppercase text-slate-700">Republic of the Philippines</p>
                <h2 class="text-base font-extrabold tracking-wide uppercase text-slate-900 leading-tight">Bohol Island State University</h2>
                <p class="text-xs font-semibold uppercase text-slate-800">Balilihan Campus</p>
                <p class="text-[11px] text-slate-600">Magsija, Balilihan, Bohol</p>
            </div>
        </div>

        <!-- Form Title -->
        <div class="text-center mb-5">
            <h3 class="text-sm font-extrabold uppercase tracking-wider text-slate-900 underline decoration-1 underline-offset-2">
                Grading Sheet for On-The-Job-Training
            </h3>
            <p class="text-xs font-semibold text-slate-800 mt-0.5">
                S.Y. <span class="border-b border-slate-800 px-4 font-bold">{{ $student->academicTerm?->academic_year ?? \App\Models\AcademicTerm::current()?->academic_year ?? '2026 - 2027' }}</span>
            </p>
        </div>

        <!-- Trainee Details -->
        <div class="space-y-1.5 text-xs text-slate-900 mb-6">
            <div class="flex items-baseline">
                <span class="w-44 font-semibold">Name of Trainee:</span>
                <span class="flex-1 border-b border-slate-800 pb-0.5 font-bold uppercase">
                    {{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name }}
                </span>
            </div>

            <div class="flex items-baseline">
                <span class="w-44 font-semibold">Course:</span>
                <span class="flex-1 border-b border-slate-800 pb-0.5 font-medium">
                    {{ $student->course ?? $student->academicCourse?->course_name ?? 'Bachelor of Science in Information Technology' }}
                </span>
            </div>

            <div class="flex items-baseline">
                <span class="w-44 font-semibold">Cooperating Agency:</span>
                <span class="flex-1 border-b border-slate-800 pb-0.5 font-medium">
                    {{ $student->company?->name ?? 'N/A' }}
                </span>
            </div>

            <div class="flex items-baseline">
                <span class="w-44 font-semibold">Address:</span>
                <span class="flex-1 border-b border-slate-800 pb-0.5 font-medium">
                    {{ $student->company?->location ?? 'Tagbilaran City, Bohol' }}
                </span>
            </div>

            <div class="flex items-baseline">
                <span class="w-44 font-semibold">Date of Training:</span>
                <div class="flex-1 flex items-baseline gap-2">
                    <span class="border-b border-slate-800 pb-0.5 font-medium flex-1 text-center">{{ $trainingStartDate }}</span>
                    <span class="text-slate-600">to</span>
                    <span class="border-b border-slate-800 pb-0.5 font-medium flex-1 text-center">{{ $trainingEndDate }}</span>
                </div>
            </div>

            <div class="flex items-baseline">
                <span class="w-44 font-semibold">Number of Hours Rendered:</span>
                <span class="flex-1 border-b border-slate-800 pb-0.5 font-bold">
                    {{ number_format($totalHoursRendered, 1) }} Hours
                </span>
            </div>
        </div>

        <!-- Grading Table & Guidelines Side by Side -->
        <div class="flex items-start gap-5 mb-8">
            <!-- Summary Rating Table -->
            <div class="flex-1">
                <table class="w-full text-xs table-bordered border-collapse text-slate-900">
                    <thead>
                        <tr class="bg-slate-100">
                            <th class="p-2 text-left font-bold uppercase text-[11px]">Evaluation Area</th>
                            <th class="p-2 text-center font-bold uppercase text-[11px] w-20">Weight</th>
                            <th class="p-2 text-center font-bold uppercase text-[11px] w-24">Rating</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="p-2.5 font-semibold">Job Performance</td>
                            <td class="p-2.5 text-center font-bold text-slate-700">50%</td>
                            <td class="p-2.5 text-center font-extrabold text-slate-900">
                                {{ $evaluation ? number_format($evaluation->job_performance_score, 2) . '%' : '—' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2.5 font-semibold">Workmanship</td>
                            <td class="p-2.5 text-center font-bold text-slate-700">20%</td>
                            <td class="p-2.5 text-center font-extrabold text-slate-900">
                                {{ $evaluation ? number_format($evaluation->workmanship_score, 2) . '%' : '—' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2.5 font-semibold">Work Habits and Attitudes</td>
                            <td class="p-2.5 text-center font-bold text-slate-700">20%</td>
                            <td class="p-2.5 text-center font-extrabold text-slate-900">
                                {{ $evaluation ? number_format($evaluation->work_habits_score, 2) . '%' : '—' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2.5 font-semibold">Attendance</td>
                            <td class="p-2.5 text-center font-bold text-slate-700">10%</td>
                            <td class="p-2.5 text-center font-extrabold text-slate-900">
                                {{ $evaluation ? number_format($evaluation->attendance_score, 2) . '%' : '—' }}
                            </td>
                        </tr>
                        <tr class="bg-slate-50 font-extrabold text-[13px]">
                            <td class="p-2.5 uppercase tracking-wide">Final Rating</td>
                            <td class="p-2.5 text-center font-bold">100%</td>
                            <td class="p-2.5 text-center text-primary text-sm font-black">
                                {{ $evaluation ? number_format($evaluation->final_rating, 2) . '%' : '—' }}
                            </td>
                        </tr>
                    </tbody>
                </table>

                @if($evaluation)
                    <div class="mt-3 p-3 bg-slate-50 border border-slate-900 rounded flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-bold text-slate-600 uppercase tracking-wider block">Official Transmuted Grade:</span>
                            <span class="text-xs font-bold text-slate-800">Status: {{ $evaluation->grade_description }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl font-black text-primary">
                                {{ $evaluation->transmuted_grade ? number_format($evaluation->transmuted_grade, 1) : '5.0' }}
                            </span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Guidelines Table -->
            <div class="w-56 flex-shrink-0">
                <table class="w-full text-xs table-bordered border-collapse text-slate-900 text-center">
                    <thead>
                        <tr class="bg-slate-100">
                            <th colspan="2" class="p-2 font-bold uppercase tracking-wider text-[11px] leading-tight">
                                Guidelines for<br>Grading System
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-300">
                        <tr class="{{ ($evaluation && $evaluation->final_rating >= 95) ? 'bg-primary/10 font-bold' : '' }}">
                            <td class="p-1.5 font-medium whitespace-nowrap">95 - up</td>
                            <td class="p-1.5 font-bold whitespace-nowrap">= 1.0</td>
                        </tr>
                        <tr class="{{ ($evaluation && $evaluation->final_rating >= 90 && $evaluation->final_rating < 95) ? 'bg-primary/10 font-bold' : '' }}">
                            <td class="p-1.5 font-medium whitespace-nowrap">94 - 90</td>
                            <td class="p-1.5 font-bold whitespace-nowrap">= 1.1 - 1.5</td>
                        </tr>
                        <tr class="{{ ($evaluation && $evaluation->final_rating >= 85 && $evaluation->final_rating < 90) ? 'bg-primary/10 font-bold' : '' }}">
                            <td class="p-1.5 font-medium whitespace-nowrap">89 - 85</td>
                            <td class="p-1.5 font-bold whitespace-nowrap">= 1.6 - 2.0</td>
                        </tr>
                        <tr class="{{ ($evaluation && $evaluation->final_rating >= 80 && $evaluation->final_rating < 85) ? 'bg-primary/10 font-bold' : '' }}">
                            <td class="p-1.5 font-medium whitespace-nowrap">84 - 80</td>
                            <td class="p-1.5 font-bold whitespace-nowrap">= 2.1 - 2.5</td>
                        </tr>
                        <tr class="{{ ($evaluation && $evaluation->final_rating >= 75 && $evaluation->final_rating < 80) ? 'bg-primary/10 font-bold' : '' }}">
                            <td class="p-1.5 font-medium whitespace-nowrap">79 - 75</td>
                            <td class="p-1.5 font-bold whitespace-nowrap">= 2.6 - 3.0</td>
                        </tr>
                        <tr class="{{ ($evaluation && $evaluation->final_rating < 75) ? 'bg-rose-50 text-rose-800 font-bold' : '' }}">
                            <td class="p-1.5 font-medium whitespace-nowrap">74 - Below</td>
                            <td class="p-1.5 font-bold text-rose-600 whitespace-nowrap">= Failure</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Official Signatures -->
        <div class="mt-14 pt-4 text-xs text-slate-900 grid grid-cols-2 gap-10">
            <!-- Rated by -->
            <div>
                <p class="font-bold italic mb-8">Rated by:</p>
                <div class="text-center">
                    <p class="font-bold uppercase tracking-wide border-b border-slate-900 pb-0.5 inline-block min-w-[220px]">
                        {{ $evaluation?->rated_by_name ?? ($student->company?->contact_person ?? ' ') }}
                    </p>
                    <p class="text-[11px] text-slate-700 mt-0.5">(Signature over printed name)</p>
                    <p class="font-semibold text-slate-800 mt-2 border-b border-slate-900 pb-0.5 inline-block min-w-[220px]">
                        {{ $evaluation?->rated_by_designation ?? 'OJT Supervisor / Cooperating Agency Representative' }}
                    </p>
                    <p class="text-[11px] text-slate-700 mt-0.5">(Designation)</p>
                </div>
            </div>

            <!-- Approved by -->
            <div>
                <p class="font-bold italic mb-8">Approved by:</p>
                <div class="text-center">
                    <p class="font-bold uppercase tracking-wide border-b border-slate-900 pb-0.5 inline-block min-w-[220px]">
                        {{ $evaluation?->approved_by_name ?? ' ' }}
                    </p>
                    <p class="text-[11px] text-slate-700 mt-0.5">(Signature over printed name)</p>
                    <p class="font-semibold text-slate-800 mt-2 border-b border-slate-900 pb-0.5 inline-block min-w-[220px]">
                        {{ $evaluation?->approved_by_designation ?? 'OJT Coordinator / Department Chair' }}
                    </p>
                    <p class="text-[11px] text-slate-700 mt-0.5">(Designation)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= PAGE 2: PERFORMANCE APPRAISAL RUBRIC ================= -->
    <div class="paper-sheet page-break">
        <!-- Title & Direction -->
        <div class="text-center mb-3">
            <h3 class="text-sm font-extrabold uppercase tracking-wider text-slate-900">
                Performance Appraisal
            </h3>
        </div>

        <div class="text-xs text-slate-800 mb-4 bg-slate-50 p-2.5 border border-slate-300 rounded leading-relaxed">
            <strong>Direction:</strong> Please indicate <span class="font-bold underline">✔</span> under the column that best describes the trainee on each of the criteria below. <br>
            <span class="text-slate-600 font-medium">(5 - highest; 1 - lowest)</span>
        </div>

        <!-- 12 Rubric Criteria Table -->
        <table class="w-full text-xs table-bordered border-collapse text-slate-900 mb-6">
            <thead>
                <tr class="bg-slate-100 text-center">
                    <th class="p-2 text-left w-3/5 font-extrabold text-[11px] uppercase">Area of Assessment</th>
                    <th class="p-2 w-12 font-bold text-center">5</th>
                    <th class="p-2 w-12 font-bold text-center">4</th>
                    <th class="p-2 w-12 font-bold text-center">3</th>
                    <th class="p-2 w-12 font-bold text-center">2</th>
                    <th class="p-2 w-12 font-bold text-center">1</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $scores = $evaluation?->criteria_scores ?? [];
                @endphp

                @foreach($criteriaDefinitions as $key => $item)
                    @php
                        $itemScore = isset($scores[$key]) ? (int) round($scores[$key]) : null;
                    @endphp
                    <tr>
                        <td class="p-2 align-top">
                            <span class="font-bold text-slate-900 block">({{ $item['title'] }})</span>
                            <span class="text-[11px] text-slate-600 leading-tight block mt-0.5">{{ $item['description'] }}</span>
                        </td>
                        @for($col = 5; $col >= 1; $col--)
                            <td class="p-2 text-center align-middle font-bold text-base {{ $itemScore === $col ? 'text-primary font-black bg-primary/5' : 'text-slate-300' }}">
                                {{ $itemScore === $col ? '✔' : '' }}
                            </td>
                        @endfor
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Comments Section -->
        <div class="mt-4 text-xs text-slate-900">
            <p class="font-bold mb-2 text-slate-800">
                Comments for Enrichment of the OJT Program / improvement of the On-The-Job Trainees:
            </p>
            <div class="border border-slate-300 rounded p-3 min-h-[90px] bg-slate-50/50 leading-relaxed italic text-slate-700">
                @if($evaluation && filled($evaluation->comments))
                    {{ $evaluation->comments }}
                @else
                    <div class="space-y-4 pt-2">
                        <div class="border-b border-slate-300"></div>
                        <div class="border-b border-slate-300"></div>
                        <div class="border-b border-slate-300"></div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer watermark / date -->
        <div class="mt-8 pt-3 border-t border-slate-200 flex justify-between items-center text-[10px] text-slate-400">
            <span>BISU Balilihan Campus — OJT Management Portal</span>
            <span>Evaluated: {{ $evaluation?->evaluated_at?->format('F d, Y h:i A') ?? 'Pending Evaluation' }}</span>
        </div>
    </div>

</body>
</html>
