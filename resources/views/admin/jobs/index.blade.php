<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-slate-800 dark:text-white leading-tight">
                {{ __('Job Listings') }}
            </h2>
            <a href="{{ route('admin.jobs.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all hover:shadow-lg hover:-translate-y-0.5">
                + Publish New Job
            </a>
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

        <div class="glass dark:glass-dark overflow-hidden sm:rounded-xl">
            <div class="p-6">
                @if($jobs->isEmpty())
                    <div class="text-center py-12 text-slate-500 dark:text-slate-400">No job listings yet. Click "Publish New Job" to create the first one.</div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Title</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Department</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Applications</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                @foreach($jobs as $job)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors duration-150">
                                        <td class="px-6 py-5">
                                            <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $job->title }}</div>
                                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ $job->location }} • {{ $job->employment_type }}</div>
                                        </td>
                                        <td class="px-6 py-5 text-sm text-slate-600 dark:text-slate-300">{{ $job->department }}</td>
                                        <td class="px-6 py-5 text-sm text-slate-600 dark:text-slate-300">{{ $job->applications_count }}</td>
                                        <td class="px-6 py-5">
                                            @if($job->isOpen())
                                                <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300">Open</span>
                                            @else
                                                <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300">Closed</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('admin.jobs.edit', $job) }}" class="inline-flex px-3 py-1.5 border border-slate-300 dark:border-slate-600 text-xs font-medium rounded text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700">Edit</a>
                                                <a href="{{ route('jobs.show', $job) }}" target="_blank" class="inline-flex px-3 py-1.5 border border-slate-300 dark:border-slate-600 text-xs font-medium rounded text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700">View</a>
                                                <form action="{{ route('admin.jobs.status', $job) }}" method="POST" class="inline">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="inline-flex px-3 py-1.5 border border-amber-300 dark:border-amber-600 text-xs font-medium rounded text-amber-700 dark:text-amber-300 bg-white dark:bg-slate-800 hover:bg-amber-50 dark:hover:bg-slate-700">
                                                        {{ $job->isOpen() ? 'Close' : 'Reopen' }}
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" class="inline" onsubmit="return confirm('Delete this job listing and all its applications?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex px-3 py-1.5 border border-red-300 dark:border-red-600 text-xs font-medium rounded text-red-700 dark:text-red-300 bg-white dark:bg-slate-800 hover:bg-red-50 dark:hover:bg-slate-700">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">{{ $jobs->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>