<x-careers-layout>
    <div class="max-w-3xl mx-auto animate-fade-in">
        <a href="{{ route('jobs.index') }}" class="inline-flex items-center text-sm text-slate-500 hover:text-primary-600 dark:text-slate-400 dark:hover:text-primary-400 mb-6">
            ← Back to all jobs
        </a>

        <div class="glass dark:glass-dark rounded-2xl p-8">
            <div class="flex flex-wrap items-center gap-3 mb-4">
                <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-primary-100 dark:bg-primary-900/50 text-primary-700 dark:text-primary-300">{{ $job->employment_type }}</span>
                @if($job->isOpen())
                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300">● Open</span>
                @else
                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300">Closed</span>
                @endif
            </div>

            <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">{{ $job->title }}</h1>
            <div class="flex flex-wrap gap-x-6 gap-y-2 text-sm text-slate-500 dark:text-slate-400 mb-6">
                <span>📍 {{ $job->department }}</span>
                <span>🏢 {{ $job->location }}</span>
                <span>💰 {{ $job->salary_range }}</span>
            </div>

            <div class="prose prose-slate dark:prose-invert max-w-none">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">About the role</h2>
                <p class="whitespace-pre-line text-slate-700 dark:text-slate-300">{{ $job->description }}</p>

                @if ($job->requirements)
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white mt-6">Requirements</h2>
                    <p class="whitespace-pre-line text-slate-700 dark:text-slate-300">{{ $job->requirements }}</p>
                @endif
            </div>

            <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-700">
                @if ($job->isOpen())
                    <a href="{{ route('jobs.apply', $job) }}" class="inline-flex justify-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all hover:shadow-lg hover:-translate-y-0.5">
                        Apply for this position
                    </a>
                @else
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">This position is no longer accepting applications.</p>
                @endif
            </div>
        </div>
    </div>
</x-careers-layout>