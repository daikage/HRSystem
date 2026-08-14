<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-slate-800 dark:text-white leading-tight flex items-center">
                <svg class="w-6 h-6 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                {{ __('Employee Directory') }}
            </h2>
            <div class="flex items-center space-x-3">
                @if(Auth::user()->hasRole('admin'))
                    <a href="{{ route('employees.export') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg text-xs font-semibold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">
                        Export CSV
                    </a>
                    <a href="{{ route('employees.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-900 focus:outline-none focus:border-primary-900 focus:ring ring-primary-300 disabled:opacity-25 transition ease-in-out duration-150">
                        + Add Employee
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="pb-12">
        @if(session('success'))
            <div class="mb-6 bg-green-500/10 border border-green-500/20 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg relative animate-fade-in" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg relative animate-fade-in" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <form method="GET" action="{{ route('employees.index') }}" class="mb-6 glass dark:glass-dark sm:rounded-xl p-4 flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <x-text-input name="search" value="{{ request('search') }}" placeholder="Search by name, email, department or job title..." class="block w-full" />
            </div>
            <div class="flex gap-3">
                <x-primary-button type="submit">{{ __('Search') }}</x-primary-button>
                @if(request('search'))
                    <a href="{{ route('employees.index') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Clear</a>
                @endif
            </div>
        </form>

        <div class="glass dark:glass-dark overflow-hidden sm:rounded-xl">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @forelse($employees as $employee)
                        <a href="{{ route('employees.show', $employee) }}" class="block bg-white/50 dark:bg-slate-800/50 rounded-lg p-5 border border-slate-200 dark:border-slate-700 hover:shadow-lg hover:border-primary-300 transition-all">
                            <div class="flex items-center space-x-4">
                                <div class="h-12 w-12 rounded-full bg-primary-100 dark:bg-primary-900/50 text-primary-700 dark:text-primary-300 flex items-center justify-center font-bold text-xl">
                                    {{ substr($employee->user->name ?? 'E', 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="text-lg font-medium text-slate-900 dark:text-white">{{ $employee->user->name ?? 'Employee' }}</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $employee->job_title ?? 'Role Undefined' }}</p>
                                </div>
                            </div>
                            <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-700">
                                <p class="text-sm text-slate-600 dark:text-slate-300 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    {{ $employee->department ?? 'General' }}
                                </p>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <h3 class="mt-2 text-sm font-medium text-slate-900 dark:text-white">No employees found</h3>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Get started by creating a new employee profile.</p>
                        </div>
                    @endforelse
                </div>
                <div class="mt-6">
                    {{ $employees->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
