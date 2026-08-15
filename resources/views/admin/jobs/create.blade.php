<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('admin.jobs.index') }}" class="mr-4 text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-semibold text-2xl text-slate-800 dark:text-white leading-tight">
                {{ __('Publish New Job') }}
            </h2>
        </div>
    </x-slot>

    <div class="pb-12">
        <div class="max-w-3xl mx-auto">
            <div class="glass dark:glass-dark overflow-hidden sm:rounded-xl">
                <div class="p-8">
                    @include('admin.jobs._form', [
                        'action' => route('admin.jobs.store'),
                        'submit' => 'Publish Job',
                    ])
                </div>
            </div>
        </div>
    </div>
</x-app-layout>