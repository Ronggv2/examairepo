<div class="min-h-screen bg-slate-100 py-10">
    <div class="mx-auto max-w-6xl px-4">
        <div class="mb-6 flex flex-col gap-4 rounded-3xl bg-white p-6 shadow-md md:flex-row md:items-center md:justify-between">
            <div class="space-y-2">
                <h1 class="text-2xl font-semibold text-slate-900">{{ $exam->title ?? 'Guest Exam' }}</h1>
                <p class="text-sm text-slate-500">{{ $exam->description ?? $questionSet?->description ?? 'Answer the questions before the timer ends.' }}</p>
            </div>
            <div class="grid gap-3 sm:grid-cols-3 sm:text-right">
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Guest</p>
                    <p class="font-semibold text-slate-900">{{ $guest_name ?: 'Guest User' }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Created by</p>
                    <p class="font-semibold text-slate-900">{{ $questionSet?->user?->name ?? 'Unknown' }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Timer</p>
                    <p id="exam-timer" class="font-semibold text-indigo-600">Disabled</p>
                </div>
            </div>
        </div>

        <form wire:submit.prevent="submitExam" class="grid gap-6 xl:grid-cols-[2fr_1fr]">
            <section class="space-y-6">
                @if($questions->isEmpty())
                    <div class="rounded-3xl bg-white p-8 shadow-sm">
                        <p class="text-center text-slate-500">No questions are available for this exam yet.</p>
                    </div>
                @else
                    @foreach($questions as $index => $question)
                        <div class="rounded-3xl bg-white p-6 shadow-sm">
                            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm font-medium text-slate-500">Question {{ $index + 1 }} of {{ $questions->count() }}</p>
                                    <h2 class="text-lg font-semibold text-slate-900">{{ $question->question }}</h2>
                                </div>
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs uppercase tracking-[0.2em] text-slate-500">{{ ucfirst($question->difficulty ?? 'medium') }}</span>
                            </div>

                            <div class="space-y-3">
                                @foreach($question->options as $option)
                                    <label class="block rounded-2xl border border-slate-200 bg-slate-50 p-4 transition hover:border-indigo-300">
                                        <div class="flex items-center gap-3">
                                            <input
                                                type="radio"
                                                wire:model="selectedAnswers.{{ $question->id }}"
                                                value="{{ $option->id }}"
                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500"
                                            />
                                            <span class="text-slate-700">{{ $option->option_text }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif
            </section>

            <aside class="space-y-6">
                <div class="rounded-3xl bg-white p-6 shadow-sm">
                    <h3 class="mb-3 text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Exam details</h3>
                    <dl class="space-y-3 text-sm text-slate-600">
                        <div class="flex justify-between gap-4">
                            <dt class="font-medium">Subject set</dt>
                            <dd>{{ $questionSet->title ?? 'N/A' }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="font-medium">Join code</dt>
                            <dd>{{ $exam_code }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="font-medium">Duration</dt>
                            <dd>{{ $exam->duration ?? '—' }} min</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="font-medium">Questions</dt>
                            <dd>{{ $questions->count() }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="font-medium">Created</dt>
                            <dd>{{ optional($questionSet)->created_at?->format('M d, Y') ?? 'Unknown' }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-sm">
                    <h3 class="mb-3 text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Instructions</h3>
                    <ul class="space-y-3 text-sm text-slate-600">
                        <li>Answer each question at your own pace.</li>
                        <li>Your guest name is shown above.</li>
                        <li>Timer: Disabled — no automatic submission will occur.</li>
                    </ul>
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-sm">
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="submitExam"
                        class="w-full rounded-3xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700 disabled:opacity-50"
                    >
                        Submit Answers Now
                    </button>
                    <p class="mt-3 text-xs text-slate-500">Submitting now saves your current answers and ends the exam. If time runs out, the exam submits automatically.</p>
                </div>
            </aside>
        </form>
    </div>
</div>

<!-- Timer script removed — client-side countdown and auto-submit disabled -->
