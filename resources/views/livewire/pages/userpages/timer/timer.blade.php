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
            <div class="text-7xl font-light tracking-wider">
                {{ $this->formatMilliseconds() }}
            </div>

            <div class="flex justify-center gap-20 mt-2 text-gray-600">
                <span>Hours</span>
                <span>Minutes</span>
                <span>Seconds</span>
            </div>

            <div class="mt-6">
                <span class="inline-flex items-center rounded-full bg-slate-100 px-4 py-1 text-sm font-medium text-slate-700">
                    @if($this->status === 'running')
                        Timer running
                    @elseif($this->status === 'ended')
                        Timer run out
                    @elseif($this->status === 'paused')
                        Timer paused
                    @elseif($this->status === 'pending')
                        Timer not started
                    @elseif($this->status === 'no-session' || $this->status === 'no-exam')
                        No active timer
                    @else
                        {{ ucfirst(str_replace('-', ' ', $this->status)) }}
                    @endif
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
                        Start Timer
                    </button>
                @elseif($this->status === 'running')
                    <button wire:click="pauseTimer"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-10 py-3 rounded-lg font-medium">
                        Pause
                    </button>
                    <button wire:click="endTimer"
                            class="border border-red-500 text-red-500 hover:bg-red-50 px-10 py-3 rounded-lg font-medium">
                        End Timer
                    </button>
                @elseif($this->status === 'paused')
                    <button wire:click="resumeTimer"
                            class="bg-emerald-500 hover:bg-emerald-600 text-white px-10 py-3 rounded-lg font-medium">
                        Resume
                    </button>
                    <button wire:click="endTimer"
                            class="border border-red-500 text-red-500 hover:bg-red-50 px-10 py-3 rounded-lg font-medium">
                        End Timer
                    </button>
                @elseif($this->status === 'ended')
                    <button wire:click="startTimer"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-12 py-3 rounded-lg font-medium">
                        Start Timer
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Settings Card -->
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">

        <div class="flex flex-col gap-4 p-5">
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <h3 class="font-medium">Duration</h3>
                    <p class="text-sm text-gray-500">Total time for the exam session.</p>
                    <div class="mt-3 flex items-center gap-2">
                        <input type="number" wire:model.defer="durationMinutes" min="1" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-sky-500 focus:ring-2 focus:ring-sky-200" />
                        <span class="text-sm text-slate-700">minutes</span>
                    </div>
                </div>

                <div>
                    <h3 class="font-medium">Auto Submit</h3>
                    <p class="text-sm text-gray-500">The session will end automatically when time runs out.</p>
                    <div class="mt-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
                        Enabled
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <button wire:click="updateDuration"
                        class="inline-flex items-center justify-center rounded-2xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white hover:bg-sky-700 transition">
                    Save Duration
                </button>
                <div class="self-center text-sm text-slate-500">
                    Current session duration: <span class="font-semibold">{{ $this->durationMinutes }} min</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Student View -->
    <div class="bg-white rounded-xl shadow-sm border p-5">

        <div class="flex flex-col gap-4 lg:flex-row lg:justify-between lg:items-center">
            <div>
                <h3 class="font-medium text-lg">Student view</h3>
                <p class="text-sm text-gray-500 mt-1">This is the live display students will see during the exam.</p>
            </div>

            <div class="text-right">
                <p class="text-xs text-gray-500">Time remaining for students</p>
                <div class="flex items-center gap-2 justify-end mt-1">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9" stroke-width="2"/>
                        <path d="M12 7v5l3 2" stroke-width="2"/>
                    </svg>
                    <span class="text-2xl font-bold">{{ $this->formatMilliseconds() }}</span>
                </div>
            </div>
        </div>

    </div>

</div>
