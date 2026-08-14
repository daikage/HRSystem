<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 dark:text-white leading-tight flex items-center">
            <svg class="w-6 h-6 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ __('Attendance Log') }}
        </h2>
    </x-slot>

    <div class="pb-12">
        <div class="space-y-6">
            @if(session('success'))
                <div class="bg-green-500/10 border border-green-500/20 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg relative animate-fade-in" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-500/10 border border-red-500/20 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg relative animate-fade-in" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Daily Action Panel -->
            <div class="glass dark:glass-dark sm:rounded-xl p-8 flex flex-col items-center justify-center text-center relative overflow-hidden">
                <!-- Decorative background elements -->
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-indigo-500/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-indigo-400/20 rounded-full blur-3xl"></div>

                <div class="relative z-10">
                    <h3 class="text-xl font-medium text-slate-900 dark:text-white mb-2">{{ \Carbon\Carbon::now()->format('l, F j, Y') }}</h3>
                    
                    <div class="my-6">
                        @if(!$todayRecord)
                            <!-- Has not clocked in yet -->
                            <p class="text-slate-500 dark:text-slate-400 mb-6">You have not clocked in today. Please clock in to start your shift.</p>
                            <form action="{{ route('attendance.clock-in') }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center justify-center px-8 py-4 border border-transparent text-lg font-bold rounded-full shadow-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all hover:scale-105 hover:shadow-indigo-500/30">
                                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    Clock In Now
                                </button>
                            </form>
                        @elseif($todayRecord && !$todayRecord->clock_out)
                            <!-- Clocked in, not clocked out -->
                            <div class="inline-flex items-center px-4 py-2 rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 font-medium mb-6 border border-green-200 dark:border-green-800/50">
                                <span class="w-2.5 h-2.5 rounded-full bg-green-500 mr-2 animate-pulse"></span>
                                Clocked in at {{ \Carbon\Carbon::parse($todayRecord->clock_in)->format('h:i A') }}
                            </div>
                            <form action="{{ route('attendance.clock-out') }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center justify-center px-8 py-4 border border-transparent text-lg font-bold rounded-full shadow-lg text-white bg-slate-800 dark:bg-slate-700 hover:bg-slate-900 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-all hover:scale-105">
                                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                                    Clock Out
                                </button>
                            </form>
                        @else
                            <!-- Clocked in and out -->
                            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 mb-4">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <h4 class="text-lg font-medium text-slate-900 dark:text-white">Day Complete</h4>
                            <p class="text-slate-500 dark:text-slate-400 mt-1">
                                Clocked in: {{ \Carbon\Carbon::parse($todayRecord->clock_in)->format('h:i A') }} &bull; 
                                Clocked out: {{ \Carbon\Carbon::parse($todayRecord->clock_out)->format('h:i A') }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Historical Data -->
            <div class="glass dark:glass-dark overflow-hidden sm:rounded-xl">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                        <h3 class="text-lg font-medium text-slate-900 dark:text-white">Attendance History</h3>
                        @if(Auth::user()->hasRole('admin'))
                            <a href="{{ route('attendance.export') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg text-xs font-semibold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all self-start sm:self-auto">
                                Export CSV
                            </a>
                        @endif
                    </div>

                    @if(Auth::user()->hasRole('admin'))
                        <form method="GET" action="{{ route('attendance.index') }}" class="mb-6 flex flex-col sm:flex-row gap-3 items-end">
                            <div class="flex-1">
                                <x-text-input name="search" value="{{ request('search') }}" placeholder="Search by employee name or email..." class="block w-full" />
                            </div>
                            <div>
                                <input type="date" name="date" value="{{ request('date') }}" class="block rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2">
                            </div>
                            <div class="flex gap-3">
                                <x-primary-button type="submit">{{ __('Filter') }}</x-primary-button>
                                @if(request('search') || request('date'))
                                    <a href="{{ route('attendance.index') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Clear</a>
                                @endif
                            </div>
                        </form>
                    @endif
                    
                    @if($attendanceRecords->isEmpty())
                        <div class="text-center py-12 border-t border-slate-200 dark:border-slate-700">
                            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <h3 class="mt-2 text-sm font-medium text-slate-900 dark:text-white">No attendance records</h3>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Past attendance records will appear here.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                                <thead>
                                    <tr>
                                        @if(Auth::user()->hasRole('admin'))
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Employee</th>
                                        @endif
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Date</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Clock In</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Clock Out</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                    @foreach($attendanceRecords as $record)
                                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                                            @if(Auth::user()->hasRole('admin'))
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white font-medium">
                                                    {{ $record->user->name ?? 'Unknown' }}
                                                </td>
                                            @endif
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                                {{ \Carbon\Carbon::parse($record->date)->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600 dark:text-green-400">
                                                {{ $record->clock_in ? \Carbon\Carbon::parse($record->clock_in)->format('h:i A') : '--:--' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-600 dark:text-slate-400">
                                                {{ $record->clock_out ? \Carbon\Carbon::parse($record->clock_out)->format('h:i A') : '--:--' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($record->clock_in && $record->clock_out)
                                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                                        Completed
                                                    </span>
                                                @elseif($record->clock_in && !$record->clock_out)
                                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800/50">
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800/50">
                                                        Missed
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6">
                            {{ $attendanceRecords->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
