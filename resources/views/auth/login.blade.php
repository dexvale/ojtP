<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,200..800;1,6..72,200..800&amp;family=Public+Sans:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
    .ghost-border {
      outline: 1px solid rgba(48, 0, 80, 0.08);
    }
    .academic-gradient {
      background: linear-gradient(135deg, #300050 0%, #4a148c 100%);
    }
    .font-newsreader {
      font-family: 'Newsreader', serif;
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
<body class="bg-surface font-body text-on-background min-h-screen flex flex-col" data-theme="student">
<main class="flex-grow flex items-center justify-center px-6 py-12 relative overflow-hidden">
<!-- Ambient Background Decorations -->
<div class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary-container/5 rounded-full blur-[120px] -z-10 translate-x-1/2 -translate-y-1/2"></div>
<div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-secondary/5 rounded-full blur-[100px] -z-10 -translate-x-1/2 translate-y-1/2"></div>
<div class="w-full max-w-5xl xl:max-w-6xl 2xl:max-w-7xl flex flex-col md:flex-row shadow-2xl rounded-xl overflow-hidden bg-surface-container-lowest">
@include('components.auth-brand-sidebar')
<div class="md:w-8/12 p-8 md:p-16 bg-surface-container-lowest">
<header class="mb-10 text-center md:text-left">
<h2 class="text-3xl text-primary font-headline font-bold">Welcome Back</h2>
<p class="text-on-surface-variant font-body mt-2 text-sm">Sign in to continue to your internship dashboard and manage your OJT activities.</p>
</header>

@if ($errors->any())
    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 flex items-start gap-3">
        <span class="material-symbols-outlined text-red-600 mt-0.5">error</span>
        <div>
            <h4 class="text-sm font-semibold text-red-800">Authentication Error</h4>
            <p class="text-xs text-red-700 mt-0.5">{{ $errors->first() }}</p>
        </div>
    </div>
@endif

<form action="{{ route('login') }}" method="POST" class="space-y-6">
@csrf
<div class="space-y-1 text-left">
    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-purple-950">
        Email
    </label>
    <div class="relative flex items-center">
        <div class="absolute left-3 flex items-center pointer-events-none text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </div>
        <input 
            type="email" 
            id="email" 
            name="email"
            value="{{ old('email') }}"
            placeholder="student@university.edu" 
            class="w-full bg-purple-50/50 border border-purple-100 rounded-xl py-3 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent transition-all"
            required
        />
    </div>
</div>

<div class="space-y-1 text-left mt-4">
    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-purple-950">
        Password
    </label>
    <div class="relative flex items-center">
        <div class="absolute left-3 flex items-center pointer-events-none text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        <input 
            type="password" 
            id="password" 
            name="password"
            placeholder="••••••••" 
            class="w-full bg-purple-50/50 border border-purple-100 rounded-xl py-3 pl-10 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent transition-all"
            required
        />
        <button type="button" onclick="togglePassword('password')" class="absolute right-3 flex items-center text-gray-400 hover:text-purple-950 focus:outline-none">
            <svg id="eye-open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            <svg id="eye-closed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
            </svg>
        </button>
    </div>
</div>
<div class="flex flex-row items-center justify-between pt-2">
<label class="inline-flex items-center gap-2 text-xs text-on-surface-variant cursor-pointer hover:text-primary transition-colors">
<input type="checkbox" name="remember" class="h-4 w-4 rounded border-outline-variant text-primary focus:ring-primary" />
<span>Remember me</span>
</label>
<a class="text-xs font-semibold text-primary hover:underline underline-offset-4 transition-colors" href="#">Forgot Password?</a>
</div>
<button class="w-full bg-primary text-on-primary px-10 py-3 rounded-lg font-body font-bold text-sm uppercase tracking-widest shadow-lg hover:shadow-primary/20 transition-all duration-200 transform hover:-translate-y-0.5 active:scale-[0.98]" type="submit">Login</button>
</form>
<div class="text-center mt-6">
<p class="text-xs text-on-surface-variant">Don't have an account? <a class="text-primary font-bold hover:underline transition-colors" href="{{ route('register') }}">Register here</a></p>
</div>
</div>
</div>
</main>
<!-- Global Footer -->
<footer class="w-full py-8 px-12 flex flex-col md:flex-row justify-between items-center gap-6 bg-surface-container-low/50 backdrop-blur-sm border-t border-primary/5">
<div class="flex items-center gap-4">
<span class="font-newsreader text-sm italic text-primary">Academic Editorial Office</span>
<span class="hidden md:block w-px h-4 bg-outline-variant/30"></span>
<p class="text-[10px] font-sans uppercase tracking-[0.2em] text-on-surface-variant/70">© 2024 University Academic Editorial Office. All rights reserved.</p>
</div>
<nav class="flex gap-8">
<a class="text-[10px] font-sans uppercase tracking-widest text-on-surface-variant/70 hover:text-primary transition-all" href="#">Privacy Policy</a>
<a class="text-[10px] font-sans uppercase tracking-widest text-on-surface-variant/70 hover:text-primary transition-all" href="#">Terms of Service</a>
<a class="text-[10px] font-sans uppercase tracking-widest text-on-surface-variant/70 hover:text-primary transition-all" href="#">Accessibility</a>
</nav>
</footer>

@if (session('success'))
<div id="success-alert" class="fixed top-5 right-5 z-50 transform transition-all duration-300 ease-out translate-y-[-20px] opacity-0">
    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 shadow-xl flex items-center gap-3 max-w-md">
        <div class="bg-emerald-500 text-white p-2 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <div>
            <h4 class="text-sm font-bold text-emerald-900">Success!</h4>
            <p class="text-xs text-emerald-700 mt-0.5">{{ session('success') }}</p>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const alertBox = document.getElementById('success-alert');
        if (alertBox) {
            setTimeout(() => {
                alertBox.classList.remove('translate-y-[-20px]', 'opacity-0');
            }, 100);
            setTimeout(() => {
                alertBox.classList.add('translate-y-[-20px]', 'opacity-0');
                setTimeout(() => {
                    alertBox.remove();
                }, 300);
            }, 4000);
        }
    });
</script>
@endif

<script>
    function togglePassword(fieldId) {
        const input = document.getElementById(fieldId);
        if (!input) return;
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        const button = input.parentElement.querySelector('button');
        if (button) {
            const openIcon = button.querySelector('#eye-open');
            const closedIcon = button.querySelector('#eye-closed');
            if (openIcon && closedIcon) {
                if (isPassword) {
                    openIcon.classList.remove('hidden');
                    closedIcon.classList.add('hidden');
                } else {
                    openIcon.classList.add('hidden');
                    closedIcon.classList.remove('hidden');
                }
            }
        }
    }
</script>
</body></html>