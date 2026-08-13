<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 dark:text-white leading-tight flex items-center">
            <svg class="w-6 h-6 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            {{ __('Dashboard Overview') }}
        </h2>
    </x-slot>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card 1 -->
        <div class="glass dark:glass-dark rounded-xl p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Employees</h3>
                <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
            <div class="flex items-baseline">
                <p class="text-3xl font-semibold text-slate-900 dark:text-white">142</p>
                <p class="ml-2 text-sm font-medium text-green-600 dark:text-green-400 flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                    12%
                </p>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="glass dark:glass-dark rounded-xl p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400">On Leave Today</h3>
                <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            </div>
            <div class="flex items-baseline">
                <p class="text-3xl font-semibold text-slate-900 dark:text-white">8</p>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="glass dark:glass-dark rounded-xl p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400">Pending Onboarding</h3>
                <div class="p-2 bg-amber-100 dark:bg-amber-900/30 rounded-lg">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="flex items-baseline">
                <p class="text-3xl font-semibold text-slate-900 dark:text-white">5</p>
                @if(Auth::user() && Auth::user()->hasRole('admin'))
                    <a href="{{ route('admin.onboarding.index') }}" class="ml-auto text-sm text-primary-600 dark:text-primary-400 hover:underline">Review &rarr;</a>
                @endif
            </div>
        </div>

        <!-- Card 4 -->
        <div class="glass dark:glass-dark rounded-xl p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400">Monthly Payroll</h3>
                <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="flex items-baseline">
                <p class="text-3xl font-semibold text-slate-900 dark:text-white">$124.5k</p>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Quick Actions -->
        <div class="glass dark:glass-dark rounded-xl p-6 h-full">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <button class="w-full flex items-center p-3 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors text-left text-slate-700 dark:text-slate-300">
                    <svg class="w-5 h-5 mr-3 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Add New Employee
                </button>
                <button class="w-full flex items-center p-3 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors text-left text-slate-700 dark:text-slate-300">
                    <svg class="w-5 h-5 mr-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Generate Payroll Report
                </button>
                <button class="w-full flex items-center p-3 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors text-left text-slate-700 dark:text-slate-300">
                    <svg class="w-5 h-5 mr-3 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    Review Leave Requests
                </button>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="lg:col-span-2 glass dark:glass-dark rounded-xl p-6">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-6">Recent Activity</h3>
            <div class="relative border-l border-slate-200 dark:border-slate-700 ml-3 space-y-6">
                <!-- Activity 1 -->
                <div class="relative pl-6">
                    <div class="absolute -left-1.5 top-1.5 w-3 h-3 bg-primary-500 rounded-full ring-4 ring-white dark:ring-slate-800"></div>
                    <p class="text-sm font-medium text-slate-900 dark:text-white">Sarah Jenkins <span class="text-slate-500 dark:text-slate-400 font-normal">was onboarded as Senior Designer</span></p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">2 hours ago</p>
                </div>
                <!-- Activity 2 -->
                <div class="relative pl-6">
                    <div class="absolute -left-1.5 top-1.5 w-3 h-3 bg-green-500 rounded-full ring-4 ring-white dark:ring-slate-800"></div>
                    <p class="text-sm font-medium text-slate-900 dark:text-white">Payroll Processed <span class="text-slate-500 dark:text-slate-400 font-normal">for August 2026</span></p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">1 day ago</p>
                </div>
                <!-- Activity 3 -->
                <div class="relative pl-6">
                    <div class="absolute -left-1.5 top-1.5 w-3 h-3 bg-amber-500 rounded-full ring-4 ring-white dark:ring-slate-800"></div>
                    <p class="text-sm font-medium text-slate-900 dark:text-white">Leave Request <span class="text-slate-500 dark:text-slate-400 font-normal">approved for Michael Chen</span></p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">2 days ago</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
