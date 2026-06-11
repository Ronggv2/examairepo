<x-layouts.header/>

    <!-- Navigation Buttons -->
    <div class="p-4 flex gap-2 items-center">
        <button wire:click="logout" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">Logout</button>
    </div>

    <!-- QUESTION FORM -->
    <div class="p-4">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4">Dashboard</h2>
            <p class="text-gray-700">Welcome to your dashboard! Here you can manage your profile, view your activities, and access various features of the application.</p>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session()->has('success'))
        <div class="p-4">
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="p-4">
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!--New question form-->
    <div class="p-4">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4">Start a new question form</h2>
            <p class="text-gray-700 mb-4">Click the button below to create a new question form.</p>
            <a href="{{ route('questionform') }}" class="inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Create New Question Form</a>
        </div>
    </div>

    <!-- Recent saved question forms -->
    <div class="p-4">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4">Your question forms</h2>
            @if(count($questionSets) > 0)
                <div class="space-y-3">
                    @foreach($questionSets as $qs)
                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 hover:bg-slate-100 transition" wire:key="questionset-{{ (int)$qs['id'] }}">
                            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                <div class="flex-1">
                                    <div class="flex flex-wrap items-center gap-3 mb-2">
                                        <p class="text-sm text-slate-500">{{ $qs['title'] ?: 'Untitled' }}</p>
                                        @if($qs['status'] === 'published')
                                            <span class="rounded-full bg-emerald-100 text-emerald-700 px-2 py-1 text-[11px] uppercase tracking-[0.2em]">Published</span>
                                        @else
                                            <span class="rounded-full bg-slate-100 text-slate-700 px-2 py-1 text-[11px] uppercase tracking-[0.2em]">Draft</span>
                                        @endif

                                        @if(!empty($qs['timer_running']))
                                            <span class="rounded-full bg-amber-100 text-amber-700 px-2 py-1 text-[11px] uppercase tracking-[0.2em]">Timer running</span>
                                        @endif
                                    </div>
                                    <p class="text-lg font-semibold">{{ $qs['description'] ?? 'Question form' }}</p>
                                    <p class="text-xs text-slate-400 mt-1">{{ $qs['total_questions'] }} questions • Updated {{ \Carbon\Carbon::parse($qs['updated_at'])->diffForHumans() }}</p>
                                </div>
                                <div class="flex gap-2 items-center">
                                    <a href="{{ route('questionform', ['question_set' => $qs['id']]) }}" class="inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition text-sm">Load</a>
                                    <button wire:click="deleteQuestionSet({{ $qs['id'] }})" wire:confirm="Are you sure? This will delete the question form and all its questions." class="inline-block bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition text-sm">Delete</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-700 mb-4">Your question forms will appear here.</p>
                <p class="text-gray-500 mb-4">No question forms found.</p>
                
            @endif
        </div>
    </div>
