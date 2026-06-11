<div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
    <h2 class="text-lg font-semibold text-slate-900 mb-5">
        @if($isAddingToExisting)
            Add Subject to Question Set
        @else
            Exam Settings
        @endif
    </h2>

    @if($isAddingToExisting)
        <div class="mb-5 rounded-2xl border border-blue-200 bg-blue-50 p-4">
            <p class="text-sm font-medium text-blue-700">✓ You're adding a new subject to your existing question set. Questions will be saved to the same set.</p>
        </div>
    @endif

    @if($error)
        <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 p-4">
            <p class="text-sm font-medium text-red-700">{{ $error }}</p>
        </div>
    @endif

    @if($success)
        <div class="mb-5 rounded-2xl border border-green-200 bg-emerald-50 p-4">
            <p class="text-sm font-medium text-emerald-700">{{ $success }}</p>
        </div>
    @endif

    <div class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Subject</label>
            <input wire:model="subject" type="text" placeholder="e.g. English"
                   class="block w-full rounded-2xl border px-4 py-3 text-sm text-slate-900 focus:ring-2 focus:ring-sky-200" :class="$wire.error ? 'border-red-300 bg-red-50 focus:border-red-500' : 'border-slate-200 bg-slate-50 focus:border-sky-500'" />
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Difficulty</label>
            <select wire:model="difficulty"
                    class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 focus:border-sky-500 focus:ring-2 focus:ring-sky-200">
                <option>Easy</option>
                <option>Medium</option>
                <option>Hard</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Number of Questions</label>
            <input wire:model.defer="questionCount" type="number" class="block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-sky-500 focus:ring-2 focus:ring-sky-200" />
            <p class="mt-2 text-xs text-slate-500">Pool must be greater than question per user.</p>
        </div>

        <label class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
            <input wire:model="autoChange" type="checkbox" class="h-5 w-5 rounded border-slate-300 text-sky-600 focus:ring-sky-500" />
            Enable Auto Change
        </label>


        <button wire:click="generateExam" :disabled="$wire.isGenerating"
                class="inline-flex w-full items-center justify-center gap-2 rounded-2xl px-4 py-3 text-sm font-semibold shadow-sm transition" :class="$wire.isGenerating ? 'bg-slate-300 text-slate-600 cursor-not-allowed' : 'bg-sky-600 text-white hover:bg-sky-700'">
            @if($isGenerating)
                <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            @endif
            {{ $isGenerating ? 'Generating...' : 'Generate Exam' }}
        </button>

        <p class="text-center text-xs text-slate-500">Estimated time: 10-20 sec</p>
    </div>
</div>