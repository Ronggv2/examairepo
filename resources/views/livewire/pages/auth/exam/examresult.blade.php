<div class="min-h-screen bg-gray-100 flex items-center justify-center p-6">

    <div class="bg-white rounded-lg shadow-md border w-full max-w-4xl p-8">

        <div class="grid md:grid-cols-2 gap-8">

            <!-- Left Side -->
            <div>
                <h1 class="text-5xl font-bold text-gray-900 mb-4">
                    Your Score
                </h1>

                <div class="text-[100px] font-bold text-purple-500 leading-none">
                    {{ $score }}
                </div>

                <div class="flex items-center gap-2 mt-6 text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5 text-green-500"
                        fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.172 7.707 8.879a1 1 0 10-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>

                    <span>
                        {{ $correct }} of {{ $total }} Questions Correct
                    </span>
                </div>
            </div>

            <!-- Right Side -->
            <div class="space-y-8">

                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-6 h-6 text-blue-500"
                            fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3a1 1 0 002 0V7zm-1 5a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>

                    <div>
                        <p class="font-semibold">Time Used:</p>
                        <p class="text-3xl">Disabled</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-6 h-6 text-orange-500"
                            fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3a1 1 0 002 0V7zm-1 5a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>

                    <div>
                        <p class="font-semibold">Time Left:</p>
                        <p class="text-3xl">Disabled</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-6 h-6 text-purple-500"
                            fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3a1 1 0 002 0V7zm-1 5a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"
                                clip-rule="evenodd" />
                        </svg> 
                    </div>

                    <div>
                        <p class="font-semibold">Exam Time:</p>
                        <p class="text-3xl">Disabled</p>
                    </div>
                </div>

            </div>

        </div>

        <div class="mt-10 grid gap-8 lg:grid-cols-[1fr_1.2fr]">
            <div class="space-y-8">
                <div class="rounded-3xl bg-slate-50 p-6">
                    <h2 class="text-lg font-semibold text-slate-900">Exam Summary</h2>
                    <div class="mt-5 space-y-4 text-sm text-slate-700">
                        <div class="flex justify-between gap-4">
                            <span class="font-medium">Score</span>
                            <span>{{ $score }}</span>
                        </div>
                        <div class="flex justify-between gap-4">
                            <span class="font-medium">Correct</span>
                            <span>{{ $correct }}</span>
                        </div>
                        <div class="flex justify-between gap-4">
                            <span class="font-medium">Total Questions</span>
                            <span>{{ $total }}</span>
                        </div>
                        <div class="flex justify-between gap-4">
                            <span class="font-medium">Time Used</span>
                            <span>Disabled</span>
                        </div>
                        <div class="flex justify-between gap-4">
                            <span class="font-medium">Time Left</span>
                            <span>Disabled</span>
                        </div>
                        <div class="flex justify-between gap-4">
                            <span class="font-medium">Exam Duration</span>
                            <span>Disabled</span>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl bg-slate-50 p-6">
                    <h2 class="text-lg font-semibold text-slate-900">Saved Responses</h2>
                    <div class="mt-5 space-y-4">
                        @forelse($attempt->answers as $answer)
                            <div class="rounded-2xl border border-slate-200 p-4">
                                <div class="flex items-center justify-between gap-4">
                                    <p class="font-medium text-slate-900">Question {{ $loop->iteration }}</p>
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $answer->is_correct ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                        {{ $answer->is_correct ? 'Correct' : 'Incorrect' }}
                                    </span>
                                </div>
                                <p class="mt-3 text-sm text-slate-600">{{ $answer->question?->question ?? 'Question text not found' }}</p>
                                <p class="mt-2 text-sm text-slate-800"><span class="font-semibold">Answer:</span> {{ $answer->answer ?? 'No answer selected' }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No answers were saved for this attempt.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="rounded-3xl bg-white p-8 shadow-lg">
                <h2 class="text-xl font-semibold text-slate-900">Attempt information</h2>
                <div class="mt-6 space-y-4 text-sm text-slate-700">
                    <div class="flex justify-between gap-4">
                        <span class="font-medium">Guest name</span>
                        <span>{{ $attempt->guest_name ?? 'Guest' }}</span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="font-medium">Submitted at</span>
                        <span>{{ optional($attempt->submitted_at)->format('M d, Y H:i') ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="font-medium">Percentage</span>
                        <span>{{ $attempt->percentage }}%</span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="font-medium">Correct / Incorrect</span>
                        <span>{{ $attempt->correct_count }} / {{ $attempt->incorrect_count }}</span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="font-medium">Unanswered</span>
                        <span>{{ $attempt->unanswered_count }}</span>
                    </div>
                </div>

                <div class="mt-10 flex justify-center">
                    <button
                        wire:click="returnHome"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-16 py-3 rounded-lg font-semibold">
                        Return
                    </button>
                </div>
            </div>
        </div>
    </div>
