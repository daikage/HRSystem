<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-slate-800 dark:text-white leading-tight">
                {{ __('Document Verification') }}
            </h2>
            <form method="GET" action="{{ route('admin.documents.index') }}" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search employee..."
                       class="rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500">
                <select name="status" class="rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500">
                    <option value="">All Statuses</option>
                    @foreach(['pending','approved','rejected'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-slate-300 dark:border-slate-600 text-xs font-medium rounded-lg text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700">Filter</button>
            </form>
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
                @if($documents->isEmpty())
                    <div class="text-center py-12 text-slate-500 dark:text-slate-400">No documents to review.</div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Employee</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Document</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
@foreach($documents as $doc)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors duration-150">
                                        <td class="px-6 py-5">
                                            <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $doc->user->name }}</div>
                                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ $doc->user->email }}</div>
                                        </td>
                                        <td class="px-6 py-5">
                                            <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $doc->title }}</div>
                                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ $doc->category }}</div>
                                        </td>
                                        <td class="px-6 py-5">
                                            @php($badge = ['pending' => 'bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300', 'approved' => 'bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300', 'rejected' => 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300'][$doc->status])
                                            <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $badge }}">{{ ucfirst($doc->status) }}</span>
                                            @if($doc->admin_feedback)
                                                <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $doc->admin_feedback }}</div>
                                            @endif
                                        </td>
<td class="px-6 py-5 whitespace-nowrap text-right">
                                            <div class="flex flex-col items-end gap-2">
                                                <a href="{{ route('documents.download', $doc) }}" class="inline-flex px-3 py-1.5 border border-slate-300 dark:border-slate-600 text-xs font-medium rounded text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700">Download</a>
                                                @if($doc->status === 'pending')
                                                    <div class="flex items-center gap-2">
                                                        <form action="{{ route('admin.documents.approve', $doc) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="inline-flex px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700">Approve</button>
                                                        </form>
                                                        <details class="relative">
                                                            <summary class="inline-flex px-3 py-1.5 border border-red-300 dark:border-red-600 text-xs font-medium rounded text-red-700 dark:text-red-300 bg-white dark:bg-slate-800 hover:bg-red-50 dark:hover:bg-slate-700 cursor-pointer list-none">Reject</summary>
                                                            <form action="{{ route('admin.documents.reject', $doc) }}" method="POST" class="absolute right-0 mt-2 w-72 glass dark:glass-dark rounded-lg p-4 z-20 space-y-2 shadow-xl border border-slate-200 dark:border-slate-700">
                                                                @csrf
                                                                <label class="block text-xs font-medium text-slate-600 dark:text-slate-300">Reason for rejection</label>
                                                                <textarea name="feedback" rows="2" required maxlength="1000" class="block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-red-500 focus:border-red-500" placeholder="What needs to be corrected?"></textarea>
                                                                <button type="submit" class="w-full inline-flex justify-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-red-600 hover:bg-red-700">Confirm Reject</button>
                                                            </form>
                                                        </details>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
</tbody>
                        </table>
                    </div>
                    <div class="mt-6">{{ $documents->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>