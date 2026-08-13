<x-guest-layout>
    <div class="mb-8 text-center animate-slide-up">
        <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
            Welcome to the Team!
        </h2>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
            Please provide your details to kickstart your onboarding process.
        </p>
    </div>

    <form method="POST" action="{{ route('onboarding.store') }}" class="space-y-6 animate-slide-up" style="animation-delay: 100ms;">
        @csrf

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <!-- First Name -->
            <div>
                <label for="first_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">First Name</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required autofocus
                        class="block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-colors duration-200" />
                </div>
                @error('first_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Last Name -->
            <div>
                <label for="last_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Last Name</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required
                        class="block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-colors duration-200" />
                </div>
                @error('last_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Email Address</label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    class="block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-colors duration-200" />
            </div>
            @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <!-- Resume Link -->
        <div>
            <label for="resume_link" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Resume Link (optional)</label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input id="resume_link" type="url" name="resume_link" value="{{ old('resume_link') }}" placeholder="https://..."
                    class="block w-full pl-10 rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-colors duration-200" />
            </div>
        </div>

        <!-- Cover Letter -->
        <div>
            <label for="cover_letter" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Cover Letter (optional)</label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <textarea id="cover_letter" name="cover_letter" rows="4"
                    class="block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-colors duration-200">{{ old('cover_letter') }}</textarea>
            </div>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5">
                Submit Application
            </button>
        </div>
    </form>
</x-guest-layout>
