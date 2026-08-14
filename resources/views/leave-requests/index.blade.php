<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-slate-800 dark:text-white leading-tight flex items-center">
                <svg class="w-6 h-6 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                {{ __('Leave Requests') }}
            </h2>
            <div class="flex items-center space-x-3">
                @if(Auth::user()->hasRole('admin'))
                    <a href="{{ route('leave-requests.export') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg text-xs font-semibold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">
                        Export CSV
                    </a>
                @endif
                <a href="{{ route('leave-requests.create') }}" class="inline-flex items-center px-4 py-2 bg-amber-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-700 focus:outline-none focus:border-amber-900 focus:ring ring-amber-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-sm hover:-translate-y-0.5">
                    + Request Leave
                </a>
            </div>
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

        @if($allowance)
            <div class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="glass dark:glass-dark sm:rounded-xl p-4">
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Annual Entitlement</p>
                    <p class="mt-1 text-2xl font-semibold text-slate-900 dark:text-white">{{ $allowance['entitlement'] }} <span class="text-sm text-slate-500 dark:text-slate-400">days</span></p>
                </div>
                <div class="glass dark:glass-dark sm:rounded-xl p-4">
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Used</p>
                    <p class="mt-1 text-2xl font-semibold text-amber-600 dark:text-amber-400">{{ $allowance['used'] }} <span class="text-sm text-slate-500 dark:text-slate-400">days</span></p>
                </div>
                <div class="glass dark:glass-dark sm:rounded-xl p-4">
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Remaining</p>
                    <p class="mt-1 text-2xl font-semibold text-green-600 dark:text-green-400">{{ $allowance['remaining'] }} <span class="text-sm text-slate-500 dark:text-slate-400">days</span></p>
                </div>
            </div>
        @endif

        <form method="GET" action="{{ route('leave-requests.index') }}" class="mb-6 glass dark:glass-dark sm:rounded-xl p-4 flex flex-col sm:flex-row gap-3 items-end">
            @if(Auth::user()->hasRole('admin'))
                <div class="flex-1">
                    <x-text-input name="search" value="{{ request('search') }}" placeholder="Search by employee name or email..." class="block w-full" />
                </div>
            @endif
            <div>
                <select name="status" class="block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                    <option value="">All Statuses</option>
                    @foreach(['pending', 'approved', 'rejected'] as $s)
                        <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-3">
                <x-primary-button type="submit">{{ __('Filter') }}</x-primary-button>
                @if(request('status') || request('search'))
                    <a href="{{ route('leave-requests.index') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Clear</a>
                @endif
            </div>
        </form>

        <div class="glass dark:glass-dark overflow-hidden sm:rounded-xl">
            <div class="p-6">
                @if($leaveRequests->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        <h3 class="mt-2 text-sm font-medium text-slate-900 dark:text-white">No leave requests</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">There are no pending or past leave requests.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                            <thead>
                                <tr>
                                    @if(Auth::user()->hasRole('admin'))
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Employee</th>
                                    @endif
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Type</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Dates</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Reason</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                @foreach($leaveRequests as $req)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                                        @if(Auth::user()->hasRole('admin'))
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white font-medium">
                                                <div class="flex items-center">
                                                    <div class="h-8 w-8 rounded-full bg-primary-100 dark:bg-primary-900/50 text-primary-700 dark:text-primary-300 flex items-center justify-center font-bold mr-3">
                                                        {{ substr($req->user->name ?? 'U', 0, 1) }}
                                                    </div>
                                                    {{ $req->user->name ?? 'Unknown' }}
                                                </div>
                                            </td>
                                        @endif
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">{{ $req->type }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                            {{ \Carbon\Carbon::parse($req->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($req->end_date)->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400 truncate max-w-xs">
                                            {{ $req->reason ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($req->status === 'pending')
                                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50">
                                                    Pending
                                                </span>
                                            @elseif($req->status === 'approved')
                                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800/50">
                                                    Approved
                                                </span>
                                            @else
                                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800/50">
                                                    Rejected
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if(Auth::user()->hasRole('admin') && $req->status === 'pending')
                                                <div class="flex justify-end space-x-2">
                                                    <form action="{{ route('leave-requests.update-status', $req) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="approved">
                                                        <button type="submit" class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300 font-semibold text-xs uppercase tracking-wider bg-green-100 dark:bg-green-900/30 px-3 py-1.5 rounded-lg transition-colors border border-transparent hover:border-green-200 dark:hover:border-green-800">
                                                            Approve
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('leave-requests.update-status', $req) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="rejected">
                                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 font-semibold text-xs uppercase tracking-wider bg-red-100 dark:bg-red-900/30 px-3 py-1.5 rounded-lg transition-colors border border-transparent hover:border-red-200 dark:hover:border-red-800">
                                                            Reject
                                                        </button>
                                                    </form>
                                                </div>
                                            @elseif(!Auth::user()->hasRole('admin') && $req->status === 'pending')
                                                <form action="{{ route('leave-requests.destroy', $req) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to cancel this leave request?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-slate-500 hover:text-red-600 dark:text-slate-400 dark:hover:text-red-400 transition-colors">
                                                        Cancel
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-slate-400 dark:text-slate-600 italic text-xs">Processed</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">
                        {{ $leaveRequests->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
