<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>HR System | Welcome</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <style>
            body { font-family: 'Inter', sans-serif; }
            .glass {
                background: rgba(255, 255, 255, 0.05);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
            .gradient-text {
                background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }
            .animated-bg {
                background: linear-gradient(-45deg, #0f172a, #1e1b4b, #312e81, #172554);
                background-size: 400% 400%;
                animation: gradientBG 15s ease infinite;
            }
            @keyframes gradientBG {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            
            /* Custom utility for blob animation */
            .blob {
                animation: blob 7s infinite;
            }
            .animation-delay-2000 {
                animation-delay: 2s;
            }
            .animation-delay-4000 {
                animation-delay: 4s;
            }
            @keyframes blob {
                0% { transform: translate(0px, 0px) scale(1); }
                33% { transform: translate(30px, -50px) scale(1.1); }
                66% { transform: translate(-20px, 20px) scale(0.9); }
                100% { transform: translate(0px, 0px) scale(1); }
            }
        </style>
    </head>
    <body class="antialiased animated-bg min-h-screen text-slate-100 relative overflow-hidden">
        
        <!-- Decorative Background Blobs -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
            <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 blob"></div>
            <div class="absolute top-[20%] right-[-10%] w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 blob animation-delay-2000"></div>
            <div class="absolute bottom-[-20%] left-[20%] w-96 h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 blob animation-delay-4000"></div>
        </div>

        <!-- Navigation -->
        <header class="relative z-10 w-full px-6 py-4 flex justify-between items-center glass shadow-lg">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <span class="text-xl font-bold tracking-wider">Lumina<span class="text-blue-400">HR</span></span>
            </div>

            @if (Route::has('login'))
                <nav class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-5 py-2 text-sm font-medium transition-all duration-300 rounded-full bg-white/10 hover:bg-white/20 hover:scale-105 shadow-md border border-white/10">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2 text-sm font-medium transition-colors hover:text-blue-400">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-5 py-2 text-sm font-medium transition-all duration-300 rounded-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 shadow-lg hover:shadow-blue-500/25">Register</a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <!-- Main Content -->
        <main class="relative z-10 flex flex-col items-center justify-center min-h-[calc(100vh-80px)] px-6 text-center">
            
            <div class="glass p-1 mb-8 rounded-full border border-white/20 shadow-xl inline-flex animate-fade-in-up">
                <span class="px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-blue-300 bg-blue-900/40 rounded-full">New Feature</span>
                <span class="px-4 py-1.5 text-xs font-medium text-slate-300">Simplified Onboarding Process 🚀</span>
            </div>

            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-6 drop-shadow-2xl">
                The Future of <br/>
                <span class="gradient-text">Human Resources</span>
            </h1>
            
            <p class="text-lg md:text-xl text-slate-400 mb-10 max-w-2xl font-light leading-relaxed">
                Empower your workforce with LuminaHR. A seamless, intelligent, and beautifully designed platform for managing people, payroll, time-off, and onboarding.
            </p>

            <div class="flex flex-col sm:flex-row gap-5">
                <a href="{{ route('login') }}" class="group relative px-8 py-3 font-semibold text-white transition-all duration-300 ease-in-out bg-transparent border border-white/20 rounded-full hover:border-blue-400 hover:shadow-[0_0_20px_rgba(59,130,246,0.3)] overflow-hidden">
                    <span class="relative z-10 flex items-center gap-2">
                        Employee Portal
                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </span>
                </a>
                
                <a href="{{ route('onboarding.create') }}" class="group relative px-8 py-3 font-semibold text-white transition-all duration-300 ease-in-out rounded-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 shadow-lg hover:shadow-purple-500/30 overflow-hidden">
                    <span class="absolute inset-0 w-full h-full -mt-1 rounded-lg opacity-30 bg-gradient-to-b from-transparent via-transparent to-black"></span>
                    <span class="relative z-10 flex items-center gap-2">
                        Join Our Team 
                        <svg class="w-4 h-4 transition-transform group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </span>
                </a>
            </div>

            <!-- Feature highlights -->
            <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl w-full text-left">
                <div class="glass p-6 rounded-2xl border border-white/10 hover:bg-white/5 transition-colors duration-300 group">
                    <div class="w-12 h-12 rounded-full bg-blue-500/20 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Seamless Onboarding</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">Streamline your hiring process with our fully digital, automated onboarding flow. Less paperwork, more productivity.</p>
                </div>
                
                <div class="glass p-6 rounded-2xl border border-white/10 hover:bg-white/5 transition-colors duration-300 group">
                    <div class="w-12 h-12 rounded-full bg-purple-500/20 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Automated Payroll</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">Ensure accurate and timely payments with our integrated payroll module. Handle deductions and bonuses effortlessly.</p>
                </div>
                
                <div class="glass p-6 rounded-2xl border border-white/10 hover:bg-white/5 transition-colors duration-300 group">
                    <div class="w-12 h-12 rounded-full bg-indigo-500/20 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Time & Attendance</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">Track hours, manage leave requests, and monitor attendance with an intuitive calendar-based interface.</p>
                </div>
            </div>
            
            <footer class="mt-20 pb-8 text-sm text-slate-500">
                &copy; {{ date('Y') }} LuminaHR Solutions. All rights reserved.
            </footer>
        </main>
    </body>
</html>
