<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('leave-requests.index') }}" class="mr-4 text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-semibold text-2xl text-slate-800 dark:text-white leading-tight">
                {{ __('Request Leave') }}
            </h2>
        </div>
    </x-slot>

    <div class="pb-12">
        <div class="max-w-2xl mx-auto">
            <div class="glass dark:glass-dark overflow-hidden sm:rounded-xl">
                <div class="p-8">
                    <form action="{{ route('leave-requests.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div>
                            <label for="type" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Leave Type</label>
                            <select name="type" id="type" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-amber-500 focus:border-amber-500" required>
                                <option value="" disabled selected>Select a leave type</option>
                                <option value="Annual" {{ old('type') == 'Annual' ? 'selected' : '' }}>Annual Leave</option>
                                <option value="Sick" {{ old('type') == 'Sick' ? 'selected' : '' }}>Sick Leave</option>
                                <option value="Unpaid" {{ old('type') == 'Unpaid' ? 'selected' : '' }}>Unpaid Leave</option>
                                <option value="Other" {{ old('type') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('type') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Start Date</label>
                                <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-amber-500 focus:border-amber-500" required>
                                @error('start_date') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300">End Date</label>
                                <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-amber-500 focus:border-amber-500" required>
                                @error('end_date') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="reason" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Reason (Optional)</label>
                            <textarea name="reason" id="reason" rows="4" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-amber-500 focus:border-amber-500" placeholder="Provide any additional context for your manager...">{{ old('reason') }}</textarea>
                            @error('reason') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="pt-4 flex items-center justify-end">
                            <a href="{{ route('leave-requests.index') }}" class="mr-4 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200">Cancel</a>
                            <button type="submit" class="inline-flex justify-center py-2.5 px-6 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all hover:-translate-y-0.5 hover:shadow-lg">
                                Submit Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
