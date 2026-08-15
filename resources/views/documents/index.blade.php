<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 dark:text-white leading-tight flex items-center">
            <svg class="w-6 h-6 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            {{ __('My Documents') }}
        </h2>
    </x-slot>

    <div class="pb-12 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Upload form -->
        <div class="lg:col-span-1">
            <div class="glass dark:glass-dark rounded-xl p-6">
                <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-4 border-b border-slate-200 dark:border-slate-700 pb-2">Upload a document</h3>

                @if ($errors->any())
                    <div class="mb-6 rounded-lg bg-red-500/10 border border-red-500/20 p-4">
                        <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-400 space-y-1">
                            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-6 bg-green-500/10 border border-green-500/20 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg text-sm animate-fade-in" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    <div>
                        <label for="title" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Document Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    <div>
                        <label for="category" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Category</label>
                        <select name="category" id="category" required class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                            @foreach($categories as $category)
                                <option value="{{ $category }}" @selected(old('category') === $category)>{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="document" class="block text-sm font-medium text-slate-700 dark:text-slate-300">File (PDF, doc, jpg, png — max 5MB)</label>
                        <input type="file" name="document" id="document" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="mt-1 block w-full text-sm text-slate-700 dark:text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-600 file:text-white hover:file:bg-primary-700">
                    </div>
                    <button type="submit" class="w-full inline-flex justify-center py-2.5 px-4 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all">
                        Upload for Verification
                    </button>
                </form>
            </div>
        </div>

        <!-- Document list -->
        <div class="lg:col-span-2">
            <div class="glass dark:glass-dark rounded-xl overflow-hidden">
                <div class="p-6">
                    @if($documents->isEmpty())
                        <div class="text-center py-12">
                            <h3 class="mt-2 text-sm font-medium text-slate-900 dark:text-white">No documents yet</h3>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Upload a document to have it verified.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Document</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Category</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
@foreach($documents as $doc)
                                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors duration-150">
                                            <td class="px-6 py-5">
                                                <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $doc->title }}</div>
                                                <div class="text-xs text-slate-500 dark:text-slate-400">{{ $doc->file_name }}</div>
                                            </td>
                                            <td class="px-6 py-5 text-sm text-slate-600 dark:text-slate-300">{{ $doc->category }}</td>
                                            <td class="px-6 py-5">
                                                @php($badge = ['pending' => 'bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300', 'approved' => 'bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300', 'rejected' => 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300'][$doc->status])
                                                <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $badge }}">{{ ucfirst($doc->status) }}</span>
                                                @if($doc->admin_feedback)
                                                    <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $doc->admin_feedback }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap text-right">
                                                <a href="{{ route('documents.download', $doc) }}" class="inline-flex px-3 py-1.5 border border-slate-300 dark:border-slate-600 text-xs font-medium rounded text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700">Download</a>
                                            </td>
                                        </tr>
                                    @endforeach
</tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>