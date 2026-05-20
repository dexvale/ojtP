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
<div class="w-full max-w-5xl flex flex-col md:flex-row shadow-2xl rounded-xl overflow-hidden bg-surface-container-lowest">
@include('components.auth-brand-sidebar')
<div class="md:w-8/12 p-8 md:p-16 bg-surface-container-lowest">
<header class="mb-10 text-center md:text-left">
<h2 class="text-3xl text-primary font-headline font-bold">Welcome Back</h2>
<p class="text-on-surface-variant font-body mt-2 text-sm">Sign in to continue to your internship dashboard and manage your OJT activities.</p>
</header>
<form action="{{ route('login') }}" method="POST" class="space-y-6">
@csrf
<div class="space-y-2">
<label class="text-[10px] font-label uppercase tracking-widest text-on-surface-variant font-semibold" for="email">Email</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/50">email</span>
<input class="w-full bg-surface-container-highest border-none rounded-lg p-3 pl-12 text-on-surface focus:ring-1 focus:ring-primary-container transition-all duration-200 placeholder:text-outline-variant/60" id="email" name="email" placeholder="student@university.edu" type="email" required />
</div>
</div>
<div class="space-y-2">
<label class="text-[10px] font-label uppercase tracking-widest text-on-surface-variant font-semibold" for="password">Password</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/50">lock</span>
<input class="w-full bg-surface-container-highest border-none rounded-lg p-3 pl-12 pr-16 text-on-surface focus:ring-1 focus:ring-primary-container transition-all duration-200 placeholder:text-outline-variant/60" id="password" name="password" placeholder="••••••••" type="password" required />
<button type="button" onclick="togglePassword('password')" class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-semibold text-primary-container hover:text-primary transition-colors">
<span class="material-symbols-outlined">visibility</span>
</button>
</div>
</div>
<div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
<a class="text-xs font-semibold text-primary hover:underline underline-offset-4 transition-colors" href="#">Forgot Password?</a>
<label class="inline-flex items-center gap-2 text-xs text-on-surface-variant">
<input type="checkbox" class="h-4 w-4 rounded border-outline-variant text-primary focus:ring-primary" />
<span>Remember me</span>
</label>
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
</body></html>