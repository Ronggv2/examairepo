<div wire:poll.1s="refreshTimer" class="max-w-6xl mx-auto space-y-4">

    <!-- Timer Card -->
    <div class="bg-white rounded-xl shadow-sm border p-6">

        <div class="flex items-center gap-2 mb-4">
            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="9" stroke-width="2"/>
                <path d="M12 7v5l3 2" stroke-width="2"/>
            </svg>
            <div>
                <h2 class="font-medium text-lg">Exam Session Timer</h2>
                <p class="text-sm text-slate-500">Live exam session state and join code.</p>
            </div>
        </div>

@if($publishedMessage)
                <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                    {{ $publishedMessage }}
                </div>
            @endif

            <div class="text-center">
                <div class="mt-2 text-sm text-slate-600">
                    <!-- Timer display disabled: only showing exam controls and join details -->
                    <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-sm font-medium text-slate-700">
                        {{ ucfirst(str_replace('-', ' ', $this->status)) }}
                    </span>
                </div>

            <div class="mt-6 grid gap-3 md:grid-cols-3">
                @if($this->joinCode)
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-left">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Join Code</p>
                        <p class="mt-2 font-semibold text-slate-900">{{ $this->joinCode }}</p>
                    </div>
                @endif

                @if($this->joinLink)
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-left">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Join Link</p>
                        <a href="{{ $this->joinLink }}" class="mt-2 block text-sky-600 hover:underline break-all">{{ $this->joinLink }}</a>
                    </div>
                @endif
            </div>

            <div class="flex flex-wrap justify-center gap-3 mt-8">
                @if($this->status === 'pending' || $this->status === 'no-session' || $this->status === 'no-exam')
                    <button wire:click="startTimer"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-12 py-3 rounded-lg font-medium">
                        Start Exam
                    </button>
                @elseif($this->status === 'running')
                    <button wire:click="pauseTimer"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-10 py-3 rounded-lg font-medium">
                        Pause
                    </button>
                    <button wire:click="endTimer"
                            class="border border-red-500 text-red-500 hover:bg-red-50 px-10 py-3 rounded-lg font-medium">
                        End Exam
                    </button>
                @elseif($this->status === 'paused')
                    <button wire:click="resumeTimer"
                            class="bg-emerald-500 hover:bg-emerald-600 text-white px-10 py-3 rounded-lg font-medium">
                        Resume
                    </button>
                    <button wire:click="endTimer"
                            class="border border-red-500 text-red-500 hover:bg-red-50 px-10 py-3 rounded-lg font-medium">
                        End Exam
                    </button>
                @elseif($this->status === 'ended')
                    <button wire:click="startTimer"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-12 py-3 rounded-lg font-medium">
                        Start Exam
                    </button>
                @endif
            </div>
        </div>
    </div>

</div>
