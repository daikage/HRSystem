<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('employees.index') }}" class="mr-4 text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-semibold text-2xl text-slate-800 dark:text-white leading-tight">
                {{ __('Add New Employee') }}
            </h2>
        </div>
    </x-slot>

    <div class="pb-12">
        <div class="max-w-3xl mx-auto">
            <div class="glass dark:glass-dark overflow-hidden sm:rounded-xl">
                <div class="p-8">
                    <form action="{{ route('employees.store') }}" method="POST" class="space-y-8">
                        @csrf
                        
                        <!-- Account Details Section -->
                        <div>
                            <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-4 border-b border-slate-200 dark:border-slate-700 pb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Account Details
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Full Name</label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500" required>
                                    @error('name') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Email Address</label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500" required>
                                    @error('email') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">A secure temporary password will be generated and emailed to the employee. They will be required to set a new password on first login.</p>
                        </div>

                        <!-- Employment Details Section -->
                        <div>
                            <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-4 border-b border-slate-200 dark:border-slate-700 pb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                Employment Details
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="job_title" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Job Title</label>
                                    <input type="text" name="job_title" id="job_title" value="{{ old('job_title') }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500" required>
                                    @error('job_title') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="department" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Department</label>
                                    <input type="text" name="department" id="department" value="{{ old('department') }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500" required>
                                    @error('department') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="salary" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Annual Salary ($)</label>
                                    <input type="number" step="0.01" name="salary" id="salary" value="{{ old('salary') }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                                    @error('salary') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="joining_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Joining Date</label>
                                    <input type="date" name="joining_date" id="joining_date" value="{{ old('joining_date') ?? date('Y-m-d') }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500" required>
                                    @error('joining_date') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="annual_leave_entitlement" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Annual Leave Entitlement</label>
                                    <input type="number" min="0" max="365" name="annual_leave_entitlement" id="annual_leave_entitlement" value="{{ old('annual_leave_entitlement', 20) }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                                    @error('annual_leave_entitlement') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end pt-4 border-t border-slate-200 dark:border-slate-700">
                            <a href="{{ route('employees.index') }}" class="mr-4 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200">Cancel</a>
                            <button type="submit" class="inline-flex justify-center py-2.5 px-6 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all hover:shadow-lg hover:-translate-y-0.5">
                                Save Employee
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
