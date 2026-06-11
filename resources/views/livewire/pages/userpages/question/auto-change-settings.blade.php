<div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
    <h2 class="text-lg font-semibold text-slate-900 mb-5">Auto Change Settings</h2>

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
            <label class="block text-sm font-medium text-slate-700 mb-2">Assign Method</label>
            <select wire:model.defer="assignMethod"
                    class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 focus:border-sky-500 focus:ring-2 focus:ring-sky-200">
                <option value="random">Random</option>
                <option value="fixed">Sequential</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Question Per User</label>
            <input wire:model.defer="questionsPerUser" type="number" class="block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-sky-500 focus:ring-2 focus:ring-sky-200" />
        </div>

        <div class="space-y-3 rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <p class="text-sm font-semibold text-slate-700">Prevent Question Repetition</p>

            <label class="flex items-center gap-3">
                <input wire:model="repeatPolicy" value="within_exam" type="radio" class="h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500" />
                <span class="text-sm text-slate-700">Within the same exam</span>
            </label>

            <label class="flex items-center gap-3">
                <input wire:model="repeatPolicy" value="across_attempts" type="radio" class="h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500" />
                <span class="text-sm text-slate-700">Across all user attempts</span>
            </label>
        </div>

        <button wire:click="saveSettings"
                class="inline-flex w-full items-center justify-center rounded-2xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700">
            Save Settings
        </button>
    </div>
</div>