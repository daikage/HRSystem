<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('payroll.index') }}" class="mr-4 text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-semibold text-2xl text-slate-800 dark:text-white leading-tight">
                {{ __('Generate Payroll Record') }}
            </h2>
        </div>
    </x-slot>

    <div class="pb-12">
        <div class="max-w-3xl mx-auto">
            <div class="glass dark:glass-dark overflow-hidden sm:rounded-xl">
                <div class="p-8">
                    <form action="{{ route('payroll.store') }}" method="POST" class="space-y-8">
                        @csrf
                        
                        <!-- Employee Selection -->
                        <div>
                            <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-4 border-b border-slate-200 dark:border-slate-700 pb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Employee Details
                            </h3>
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Select Employee</label>
                                <select name="user_id" id="user_id" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-green-500 focus:border-green-500" required>
                                    <option value="" disabled selected>-- Select an employee --</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}" {{ old('user_id') == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->name }} ({{ $emp->employeeProfile->job_title ?? 'No Title' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Pay Period -->
                        <div>
                            <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-4 border-b border-slate-200 dark:border-slate-700 pb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Pay Period
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="pay_period_start" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Start Date</label>
                                    <input type="date" name="pay_period_start" id="pay_period_start" value="{{ old('pay_period_start') }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-green-500 focus:border-green-500" required>
                                    @error('pay_period_start') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="pay_period_end" class="block text-sm font-medium text-slate-700 dark:text-slate-300">End Date</label>
                                    <input type="date" name="pay_period_end" id="pay_period_end" value="{{ old('pay_period_end') }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-green-500 focus:border-green-500" required>
                                    @error('pay_period_end') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Financials -->
                        <div>
                            <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-4 border-b border-slate-200 dark:border-slate-700 pb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Financial Breakdown
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                <div>
                                    <label for="base_salary" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Base Salary ($)</label>
                                    <input type="number" step="0.01" name="base_salary" id="base_salary" value="{{ old('base_salary') }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-green-500 focus:border-green-500" required>
                                    @error('base_salary') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="bonuses" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Bonuses ($)</label>
                                    <input type="number" step="0.01" name="bonuses" id="bonuses" value="{{ old('bonuses', '0.00') }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-green-500 focus:border-green-500">
                                    @error('bonuses') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="deductions" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Deductions ($)</label>
                                    <input type="number" step="0.01" name="deductions" id="deductions" value="{{ old('deductions', '0.00') }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-red-500 focus:border-red-500">
                                    @error('deductions') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="mt-4 p-4 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-200 dark:border-slate-700 text-sm text-slate-500 dark:text-slate-400">
                                <span class="font-semibold text-slate-700 dark:text-slate-300">Note:</span> Net Pay will be automatically calculated as (Base Salary + Bonuses - Deductions).
                            </div>
                        </div>
                        
                        <div class="pt-4 flex items-center justify-end border-t border-slate-200 dark:border-slate-700">
                            <a href="{{ route('payroll.index') }}" class="mr-4 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200">Cancel</a>
                            <button type="submit" class="inline-flex justify-center py-2.5 px-6 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all hover:-translate-y-0.5 hover:shadow-lg">
                                Generate Record
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
