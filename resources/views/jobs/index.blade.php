<x-careers-layout>
    <!-- Hero -->
    <div class="text-center max-w-3xl mx-auto mb-10 animate-fade-in">
        <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight">
            Join the team at <span class="text-primary-600 dark:text-primary-400">{{ config('app.name', 'our company') }}</span>
        </h1>
        <p class="mt-4 text-lg text-slate-600 dark:text-slate-300">
            Explore open positions below and apply to become part of a growing, people-first organisation.
        </p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-500/10 border border-green-500/20 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg relative animate-fade-in" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Department filter -->
    <form method="GET" action="{{ route('jobs.index') }}" class="mb-8 flex flex-col sm:flex-row gap-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title, department or location..."
               class="flex-1 rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500">
        <select name="department" class="rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500">
            <option value="">All Departments</option>
            @foreach($departments as $dept)
                <option value="{{ $dept }}" @selected(request('department') === $dept)>{{ $dept }}</option>
            @endforeach
        </select>
        <button type="submit" class="inline-flex justify-center items-center px-6 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700">Search</button>
    </form>

    @if($jobs->isEmpty())
        <div class="text-center py-16">
            <h3 class="text-lg font-medium text-slate-900 dark:text-white">No open positions right now</h3>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Check back soon or reach out through our onboarding form.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($jobs as $job)
                <a href="{{ route('jobs.show', $job) }}" class="glass dark:glass-dark rounded-xl p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 group">
                    <div class="flex items-start justify-between mb-3">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white group-hover:text-primary-600 transition-colors">{{ $job->title }}</h3>
                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-primary-100 dark:bg-primary-900/50 text-primary-700 dark:text-primary-300 whitespace-nowrap">{{ $job->employment_type }}</span>
                    </div>
                    <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm text-slate-500 dark:text-slate-400 mb-3">
                        <span>📍 {{ $job->department }}</span>
                        <span>🏢 {{ $job->location }}</span>
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-300 line-clamp-3">{{ $job->description }}</p>
                    <div class="mt-4 flex items-center justify-between text-sm">
                        <span class="font-medium text-primary-600 dark:text-primary-400">{{ $job->salary_range }}</span>
                        <span class="inline-flex items-center text-primary-600 dark:text-primary-400 font-medium">View & Apply →</span>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-8 flex justify-center">
            {{ $jobs->links() }}
        </div>
    @endif
</x-careers-layout>