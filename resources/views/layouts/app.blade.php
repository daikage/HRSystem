<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-black bg-white transition-colors duration-300">
        <div class="min-h-screen relative overflow-hidden">
            <!-- Subtle background elements -->
            <div class="absolute top-0 w-full h-96 bg-gradient-to-b from-primary-50 to-transparent dark:from-primary-900/20 z-0 pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col min-h-screen">
                @include('layouts.navigation')

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-700 shadow-sm sticky top-0 z-20">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="flex-grow max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 w-full animate-slide-up">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
