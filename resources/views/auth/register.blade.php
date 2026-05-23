<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Academic Excellence - OJT Student Sign Up</title>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link
        href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,200..800;1,6..72,200..800&amp;family=Public+Sans:ital,wght@0,100..900;1,100..900&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Public Sans', sans-serif;
        }

        h1,
        h2,
        h3 {
            font-family: 'Newsreader', serif;
        }

        .glass-panel {
            background: rgba(255, 247, 253, 0.8);
            backdrop-filter: blur(24px);
        }

        input[type="password"] {
            -webkit-appearance: none;
            appearance: none;
        }

        input[type="password"]::-ms-clear,
        input[type="password"]::-ms-reveal {
            display: none;
        }

        input[type="password"]::-webkit-textfield-decoration-container,
        input[type="password"]::-webkit-textfield-decoration-button {
            display: none !important;
        }
    </style>
</head>

<body
    class="bg-surface text-on-surface min-h-screen flex flex-col items-center justify-center p-6 sm:p-12 relative overflow-x-hidden" data-theme="student">
    <!-- Ambient Background Decorations -->
    <div
        class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary-container/5 rounded-full blur-[120px] -z-10 translate-x-1/2 -translate-y-1/2">
    </div>
    <div
        class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-secondary/5 rounded-full blur-[100px] -z-10 -translate-x-1/2 translate-y-1/2">
    </div>
    <main
        class="w-full max-w-5xl flex flex-col md:flex-row shadow-2xl rounded-xl overflow-hidden bg-surface-container-lowest">
        @include('components.auth-brand-sidebar')
        <!-- Signup Form Area -->
        <div class="md:w-8/12 p-8 md:p-16 bg-surface-container-lowest">
            <header class="mb-10">
                <h2 class="text-3xl text-primary font-headline font-bold">Create User Account</h2>
                <p class="text-on-surface-variant font-body mt-2 text-sm">Please fill up the form to join the internship
                    management system.</p>
            </header>

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 flex items-start gap-3">
                    <span class="material-symbols-outlined text-red-600 mt-0.5">error</span>
                    <div>
                        <h4 class="text-sm font-semibold text-red-800">Please correct the following errors:</h4>
                        <ul class="mt-1 list-disc list-inside text-xs text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="space-y-6">
                @csrf
                <!-- Names -->
                <div class="space-y-2">
                    <label class="text-[10px] font-sans uppercase tracking-[0.2em] text-primary font-bold">First Name</label>
                    <input name="first_name"
                        value="{{ old('first_name') }}"
                        class="w-full bg-surface-container-highest border-none rounded-lg p-3 text-on-surface focus:ring-2 focus:ring-purple-600 focus:outline-none shadow-sm transition-all duration-200 placeholder:text-outline-variant/60"
                        placeholder="First Name" type="text" required />
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-sans uppercase tracking-[0.2em] text-primary font-bold">Middle Name</label>
                    <input name="middle_name"
                        value="{{ old('middle_name') }}"
                        class="w-full bg-surface-container-highest border-none rounded-lg p-3 text-on-surface focus:ring-2 focus:ring-purple-600 focus:outline-none shadow-sm transition-all duration-200 placeholder:text-outline-variant/60"
                        placeholder="Middle Name" type="text" />
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-sans uppercase tracking-[0.2em] text-primary font-bold">Last Name</label>
                    <input name="last_name"
                        value="{{ old('last_name') }}"
                        class="w-full bg-surface-container-highest border-none rounded-lg p-3 text-on-surface focus:ring-2 focus:ring-purple-600 focus:outline-none shadow-sm transition-all duration-200 placeholder:text-outline-variant/60"
                        placeholder="Last Name" type="text" required />
                </div>

                <!-- Academic Info & ID -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-sans uppercase tracking-[0.2em] text-primary font-bold">Your Student ID</label>
                        <input name="student_id"
                            value="{{ old('student_id') }}"
                            class="w-full bg-surface-container-highest border-none rounded-lg p-3 text-on-surface focus:ring-2 focus:ring-purple-600 focus:outline-none shadow-sm transition-all duration-200 placeholder:text-outline-variant/60"
                            placeholder="Student ID" type="text" required />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-sans uppercase tracking-[0.2em] text-primary font-bold">Course</label>
                        <div class="relative">
                            <select name="course"
                                class="w-full bg-surface-container-highest border-none rounded-lg p-3 pl-4 pr-10 text-on-surface focus:ring-2 focus:ring-purple-600 focus:outline-none shadow-sm transition-all duration-200 appearance-none"
                                required>
                                <option value="">Select your course</option>
                                <option {{ old('course') == 'BS in Information Technology' ? 'selected' : '' }}>BS in Information Technology</option>
                                <option {{ old('course') == 'BS in Computer Science' ? 'selected' : '' }}>BS in Computer Science</option>
                                <option {{ old('course') == 'BS in Engineering' ? 'selected' : '' }}>BS in Engineering</option>
                                <option {{ old('course') == 'Bachelor of Elementary Education' ? 'selected' : '' }}>Bachelor of Elementary Education</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-on-surface">
                                <span class="material-symbols-outlined">expand_more</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact and Security -->
                <div class="space-y-2">
                    <label class="text-[10px] font-sans uppercase tracking-[0.2em] text-primary font-bold">Your Email</label>
                    <input name="email"
                        value="{{ old('email') }}"
                        class="w-full bg-surface-container-highest border-none rounded-lg p-3 text-on-surface focus:ring-2 focus:ring-purple-600 focus:outline-none shadow-sm transition-all duration-200 placeholder:text-outline-variant/60"
                        placeholder="student@university.edu" type="email" required />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-sans uppercase tracking-[0.2em] text-primary font-bold">Password</label>
                        <div class="relative">
                            <input id="password" name="password"
                                class="w-full bg-surface-container-highest border-none rounded-lg p-3 pr-12 text-on-surface focus:ring-2 focus:ring-purple-600 focus:outline-none shadow-sm transition-all duration-200 placeholder:text-outline-variant/60"
                                placeholder="••••••••" type="password" required />
                            <button type="button" onclick="togglePassword('password')"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-primary hover:text-purple-600 transition-colors">
                                <span class="material-symbols-outlined">visibility</span>
                            </button>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-sans uppercase tracking-[0.2em] text-primary font-bold">Confirm Password</label>
                        <div class="relative">
                            <input id="password_confirmation" name="password_confirmation"
                                class="w-full bg-surface-container-highest border-none rounded-lg p-3 pr-12 text-on-surface focus:ring-2 focus:ring-purple-600 focus:outline-none shadow-sm transition-all duration-200 placeholder:text-outline-variant/60"
                                placeholder="••••••••" type="password" required />
                            <button type="button" onclick="togglePassword('password_confirmation')"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-primary hover:text-purple-600 transition-colors">
                                <span class="material-symbols-outlined">visibility</span>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Action Section -->
                <div class="pt-6 flex flex-col gap-6">
                    <button
                        class="w-max bg-primary text-on-primary px-12 py-3 rounded-lg font-body font-bold text-sm uppercase tracking-widest shadow-lg hover:shadow-primary transition-all duration-200 transform hover:-translate-y-0.5 active:scale-[0.98]"
                        type="submit">
                        SUBMIT
                    </button>
                    <div class="flex items-center gap-2">
                        <a class="text-xs font-bold text-primary/70 hover:text-primary transition-colors flex items-center gap-1"
                            href="{{ route('login') }}">
                            <span class="material-symbols-outlined text-sm">arrow_back</span>
                            Back to login page
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </main>
    <script>
        function togglePassword(fieldId) {
            const input = document.getElementById(fieldId);
            if (!input) return;
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            const button = input.parentElement.querySelector('button');
            if (button) {
                const icon = button.querySelector('span');
                if (icon) {
                    icon.textContent = isPassword ? 'visibility_off' : 'visibility';
                }
            }
        }
    </script>
    <!-- Footer Area -->
    <footer class="mt-12 flex flex-col items-center gap-4 w-full">
        <div class="h-px w-24 bg-outline-variant/20 mb-4"></div>
        <div class="flex gap-8">
            <a class="font-label text-[10px] uppercase tracking-widest text-on-surface-variant/70 hover:text-primary-container transition-colors"
                href="#">Privacy Policy</a>
            <a class="font-label text-[10px] uppercase tracking-widest text-on-surface-variant/70 hover:text-primary-container transition-colors"
                href="#">Terms of Service</a>
            <a class="font-label text-[10px] uppercase tracking-widest text-on-surface-variant/70 hover:text-primary-container transition-colors"
                href="#">Accessibility</a>
        </div>
        <p class="font-label text-[10px] uppercase tracking-[0.2em] text-on-surface-variant/50">
            © 2024 Bohol Island State University Academic Editorial Office.
        </p>
    </footer>
</body>

</html>
