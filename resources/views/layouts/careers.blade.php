<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} – Careers</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-black bg-white transition-colors duration-300">
        <div class="min-h-screen relative overflow-hidden">
            <div class="absolute top-0 w-full h-96 bg-gradient-to-b from-primary-50 to-transparent dark:from-primary-900/20 z-0 pointer-events-none"></div>

            <!-- Top navigation bar for guests exploring jobs -->
            <nav class="relative z-20 bg-white/70 dark:bg-slate-800/70 backdrop-blur-md border-b border-slate-200 dark:border-slate-700 sticky top-0">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('jobs.index') }}">
                                <x-application-logo class="block h-9 w-auto fill-current text-primary-600 dark:text-primary-400" />
                            </a>
                        </div>
                        <div class="hidden sm:flex sm:items-center sm:space-x-6">
                            <a href="{{ route('jobs.index') }}" class="text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-primary-600">{{ __('Browse Jobs') }}</a>
                            <a href="{{ route('onboarding.create') }}" class="text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-primary-600">{{ __('Onboarding') }}</a>
                            @auth
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700">{{ __('Dashboard') }}</a>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700">{{ __('Sign In') }}</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>

            <main class="relative z-10 flex-grow max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 w-full">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>