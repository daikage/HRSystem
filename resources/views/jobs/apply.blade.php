<x-careers-layout>
    <div class="max-w-2xl mx-auto animate-fade-in">
        <a href="{{ route('jobs.show', $job) }}" class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300 mb-6">
            ← Back to {{ $job->title }}
        </a>

        <div class="glass dark:glass-dark overflow-hidden sm:rounded-2xl">
            <div class="p-8">
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-1">Apply for {{ $job->title }}</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-8">{{ $job->department }} • {{ $job->location }}</p>

                @if ($errors->any())
                    <div class="mb-6 rounded-lg bg-red-500/10 border border-red-500/20 p-4">
                        <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-400 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('jobs.apply.submit', $job) }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">First Name</label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Last Name</label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Phone (optional)</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                        </div>
                    </div>

                    <div>
                        <label for="resume_link" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Resume / CV link (optional)</label>
                        <input type="url" name="resume_link" id="resume_link" value="{{ old('resume_link') }}" placeholder="https://..." class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <div>
                        <label for="cover_letter" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Cover Letter</label>
                        <textarea name="cover_letter" id="cover_letter" rows="5" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500" placeholder="Tell us why you are a great fit...">{{ old('cover_letter') }}</textarea>
                    </div>

                    <div class="flex items-center justify-end pt-4 border-t border-slate-200 dark:border-slate-700">
                        <button type="submit" class="inline-flex justify-center px-6 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all hover:shadow-lg hover:-translate-y-0.5">
                            Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-careers-layout>