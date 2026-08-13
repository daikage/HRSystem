<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('payroll.index') }}" class="mr-4 text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h2 class="font-semibold text-2xl text-slate-800 dark:text-white leading-tight">
                    {{ __('Payslip Overview') }}
                </h2>
            </div>
            <div>
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-slate-800 dark:bg-slate-200 border border-transparent rounded-lg font-semibold text-xs text-white dark:text-slate-800 uppercase tracking-widest hover:bg-slate-700 dark:hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Print Payslip
                </button>
            </div>
        </div>
    </x-slot>

    <div class="pb-12 print:pb-0">
        <div class="max-w-4xl mx-auto">
            <!-- Payslip Paper -->
            <div class="bg-white dark:bg-slate-800 shadow-xl sm:rounded-xl overflow-hidden print:shadow-none print:text-black">
                <!-- Header -->
                <div class="bg-slate-50 dark:bg-slate-900/50 p-8 border-b border-slate-200 dark:border-slate-700 print:border-slate-300">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight flex items-center">
                                <svg class="w-8 h-8 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                LuminaHR
                            </h1>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Official Payslip Document</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Status</p>
                            @if($payroll->status === 'paid')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800/50 uppercase tracking-wider">
                                    Paid
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50 uppercase tracking-wider">
                                    Pending
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-8 grid grid-cols-2 gap-8">
                        <div>
                            <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Employee Info</p>
                            <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $payroll->user->name }}</p>
                            <p class="text-sm text-slate-600 dark:text-slate-300">{{ $payroll->user->employeeProfile->job_title ?? 'Employee' }}</p>
                            <p class="text-sm text-slate-600 dark:text-slate-300">{{ $payroll->user->employeeProfile->department ?? 'General' }}</p>
                            <p class="text-sm text-slate-500 mt-1">{{ $payroll->user->email }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Pay Period</p>
                            <p class="text-lg font-bold text-slate-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($payroll->pay_period_start)->format('M d, Y') }} - 
                                {{ \Carbon\Carbon::parse($payroll->pay_period_end)->format('M d, Y') }}
                            </p>
                            <p class="text-sm text-slate-500 mt-2">
                                <span class="font-medium">Issue Date:</span> {{ $payroll->created_at->format('M d, Y') }}
                            </p>
                            <p class="text-sm text-slate-500">
                                <span class="font-medium">Reference:</span> PR-{{ str_pad($payroll->id, 6, '0', STR_PAD_LEFT) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Earnings & Deductions -->
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <!-- Earnings -->
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 border-b border-slate-200 dark:border-slate-700 pb-2 print:border-slate-300">Earnings</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-600 dark:text-slate-300">Base Salary</span>
                                    <span class="font-medium text-slate-900 dark:text-white">${{ number_format($payroll->base_salary, 2) }}</span>
                                </div>
                                @if($payroll->bonuses > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-600 dark:text-slate-300">Bonuses / Allowances</span>
                                    <span class="font-medium text-green-600 dark:text-green-400">+ ${{ number_format($payroll->bonuses, 2) }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Deductions -->
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 border-b border-slate-200 dark:border-slate-700 pb-2 print:border-slate-300">Deductions</h3>
                            <div class="space-y-3">
                                @if($payroll->deductions > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-600 dark:text-slate-300">Taxes & Deductions</span>
                                    <span class="font-medium text-red-600 dark:text-red-400">- ${{ number_format($payroll->deductions, 2) }}</span>
                                </div>
                                @else
                                <div class="text-sm text-slate-500 italic">No deductions for this period.</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Totals -->
                    <div class="mt-12 bg-slate-50 dark:bg-slate-900/50 rounded-xl p-6 border border-slate-200 dark:border-slate-700 flex flex-col items-end print:border-slate-300 print:bg-transparent">
                        <div class="w-full md:w-1/2 space-y-3 text-sm">
                            <div class="flex justify-between text-slate-600 dark:text-slate-400">
                                <span>Gross Earnings</span>
                                <span>${{ number_format($payroll->base_salary + $payroll->bonuses, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-slate-600 dark:text-slate-400">
                                <span>Total Deductions</span>
                                <span>${{ number_format($payroll->deductions, 2) }}</span>
                            </div>
                            <div class="pt-3 border-t border-slate-200 dark:border-slate-700 flex justify-between items-center print:border-slate-300">
                                <span class="text-lg font-bold text-slate-900 dark:text-white">Net Pay</span>
                                <span class="text-2xl font-bold text-green-600 dark:text-green-400">
                                    ${{ number_format($payroll->net_pay, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer Info -->
                    <div class="mt-12 text-center text-xs text-slate-500 dark:text-slate-400">
                        <p>This is a computer generated document. No signature is required.</p>
                        <p class="mt-1">If you have any questions regarding this payslip, please contact the HR Department.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
