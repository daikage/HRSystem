<x-careers-layout>
    <div class="max-w-2xl mx-auto text-center animate-fade-in py-16">
        <div class="h-20 w-20 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center mx-auto mb-6">
            <svg class="h-10 w-10 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-3">Application Received!</h1>
        <p class="text-slate-600 dark:text-slate-300 mb-8 max-w-md mx-auto">
            Thank you for applying. Our team will review your application and contact you if you are shortlisted for the next stage.
        </p>
        <a href="{{ route('jobs.index') }}" class="inline-flex justify-center px-6 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700">
            Browse More Jobs
        </a>
    </div>
</x-careers-layout>