<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 dark:text-white leading-tight">
            {{ __('Pending Onboarding Requests') }}
        </h2>
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
                
                @if($requests->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <h3 class="mt-2 text-sm font-medium text-slate-900 dark:text-white">No pending requests</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">All onboarding applications have been processed.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Candidate</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Email</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Submitted</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                @foreach($requests as $req)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors duration-150">
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-primary-100 dark:bg-primary-900/50 flex items-center justify-center text-primary-700 dark:text-primary-300 font-bold">
                                                    {{ substr($req->first_name, 0, 1) }}{{ substr($req->last_name, 0, 1) }}
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-slate-900 dark:text-white">
                                                        {{ $req->first_name }} {{ $req->last_name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                            {{ $req->email }}
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                            {{ $req->created_at->diffForHumans() }}
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">
                                                Pending
                                            </span>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                            <div class="flex justify-end gap-2">
                                                <form action="{{ route('admin.onboarding.approve', $req->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                                        Approve
                                                    </button>
                                                </form>

                                                <details class="relative">
                                                    <summary class="inline-flex items-center px-3 py-1.5 border border-slate-300 dark:border-slate-600 text-xs font-medium rounded text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer list-none">
                                                        Reject
                                                    </summary>
                                                    <form action="{{ route('admin.onboarding.reject', $req->id) }}" method="POST" class="absolute right-0 mt-2 w-72 glass dark:glass-dark rounded-lg p-4 z-20 space-y-2 shadow-xl border border-slate-200 dark:border-slate-700" onsubmit="return confirm('Reject this onboarding request?');">
                                                        @csrf
                                                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-300">Reason for rejection</label>
                                                        <textarea name="reason" rows="2" required maxlength="1000" class="block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-red-500 focus:border-red-500" placeholder="Required..."></textarea>
                                                        <button type="submit" class="w-full inline-flex justify-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-red-600 hover:bg-red-700">Confirm Reject</button>
                                                    </form>
                                                </details>

                                                <details class="relative">
                                                    <summary class="inline-flex items-center px-3 py-1.5 border border-slate-300 dark:border-slate-600 text-xs font-medium rounded text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer list-none">
                                                        Request Info
                                                    </summary>
                                                    <form action="{{ route('admin.onboarding.request-info', $req->id) }}" method="POST" class="absolute right-0 mt-2 w-72 glass dark:glass-dark rounded-lg p-4 z-20 space-y-2 shadow-xl border border-slate-200 dark:border-slate-700">
                                                        @csrf
                                                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-300">Message to candidate</label>
                                                        <textarea name="message" rows="2" required maxlength="1000" class="block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500" placeholder="What additional information do you need?"></textarea>
                                                        <button type="submit" class="w-full inline-flex justify-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-primary-600 hover:bg-primary-700">Send Request</button>
                                                    </form>
                                                </details>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">
                        {{ $requests->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
