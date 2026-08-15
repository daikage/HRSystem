@php($job = $job ?? null)

<form action="{{ $action }}" method="POST" class="space-y-8">
    @csrf
    @if(isset($method) && $method === 'PUT') @method('PUT') @endif

    @if ($errors->any())
        <div class="mb-6 rounded-lg bg-red-500/10 border border-red-500/20 p-4">
            <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-400 space-y-1">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <!-- Details -->
    <div>
        <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-4 border-b border-slate-200 dark:border-slate-700 pb-2">Job Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="title" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Job Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $job->title ?? '') }}" required class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div>
                <label for="location" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Location</label>
                <input type="text" name="location" id="location" value="{{ old('location', $job->location ?? '') }}" required class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div>
                <label for="department" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Department</label>
                <input type="text" name="department" id="department" value="{{ old('department', $job->department ?? '') }}" required class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div>
                <label for="employment_type" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Employment Type</label>
                <select name="employment_type" id="employment_type" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                    @foreach(['Full-time','Part-time','Contract','Internship'] as $type)
                        <option value="{{ $type }}" @selected(old('employment_type', $job->employment_type ?? 'Full-time') === $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="salary_min" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Salary Min ($)</label>
                <input type="number" step="0.01" min="0" name="salary_min" id="salary_min" value="{{ old('salary_min', $job->salary_min ?? '') }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div>
                <label for="salary_max" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Salary Max ($)</label>
                <input type="number" step="0.01" min="0" name="salary_max" id="salary_max" value="{{ old('salary_max', $job->salary_max ?? '') }}" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500">
            </div>
        </div>
    </div>

    <!-- Description -->
    <div>
        <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-4 border-b border-slate-200 dark:border-slate-700 pb-2">Description & Requirements</h3>
        <div class="space-y-6">
            <div>
                <label for="description" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Job Description</label>
                <textarea name="description" id="description" rows="6" required class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500" placeholder="Describe the role and responsibilities...">{{ old('description', $job->description ?? '') }}</textarea>
            </div>
            <div>
                <label for="requirements" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Requirements</label>
                <textarea name="requirements" id="requirements" rows="4" class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500" placeholder="Qualifications, skills and experience...">{{ old('requirements', $job->requirements ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end pt-4 border-t border-slate-200 dark:border-slate-700">
        <a href="{{ route('admin.jobs.index') }}" class="mr-4 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200">Cancel</a>
        <button type="submit" class="inline-flex justify-center py-2.5 px-6 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all hover:shadow-lg hover:-translate-y-0.5">
            {{ $submit ?? 'Publish Job' }}
        </button>
    </div>
</form>