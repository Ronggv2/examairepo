<div class="max-w-7xl mx-auto">

    <!-- Stats -->
    <div class="grid grid-cols-5 gap-4 mb-6">

        <div class="bg-white rounded-xl shadow p-4">
            <p class="text-gray-500 text-sm">Total Students</p>
            <h2 class="text-3xl font-bold">
                {{ $stats['total_students'] }}
            </h2>
            <p class="text-gray-400 text-sm">Submitted</p>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <p class="text-gray-500 text-sm">Average Score</p>
            <h2 class="text-3xl font-bold">
                {{ $stats['average_score'] }}%
            </h2>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <p class="text-gray-500 text-sm">Highest Score</p>
            <h2 class="text-3xl font-bold">
                {{ $stats['highest_score'] }}%
            </h2>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <p class="text-gray-500 text-sm">Lowest Score</p>
            <h2 class="text-3xl font-bold">
                {{ $stats['lowest_score'] }}%
            </h2>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <p class="text-gray-500 text-sm">Average Time</p>
            <h2 class="text-3xl font-bold">
                {{ $stats['avg_time_formatted'] }}
            </h2>
        </div>

    </div>

    <!-- Question Analysis -->
    <div class="bg-white rounded-xl shadow p-5 mb-6">

        <h2 class="font-semibold text-lg mb-4">
            Question Wise Analysis
        </h2>

        <table class="w-full border-collapse">

            <thead>
                <tr class="bg-gray-100">
                    <th class="p-3 text-left">#</th>
                    <th class="p-3 text-left">Question</th>
                    <th class="p-3 text-left">Correct %</th>
                    <th class="p-3 text-left">Correct</th>
                    <th class="p-3 text-left">Incorrect</th>
                    <th class="p-3 text-left">Unanswered</th>
                    <th class="p-3 text-left">Difficulty</th>
                </tr>
            </thead>

            <tbody>
                @foreach($questionAnalysis as $index => $qa)
                    <tr class="border-t">
                        <td class="p-3">{{ $index + 1 }}</td>
                        <td class="p-3">{{ Str::limit($qa['question'], 50) }}</td>
                        <td class="p-3">
                            <div class="flex items-center gap-2">
                                <span>{{ $qa['correct_percentage'] }}%</span>
                                <div class="w-24 h-2 bg-gray-200 rounded">
                                    <div class="h-2 bg-green-500 rounded" style="width: {{ $qa['correct_percentage'] }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="p-3 text-green-600">{{ $qa['correct'] }}</td>
                        <td class="p-3 text-red-600">{{ $qa['incorrect'] }}</td>
                        <td class="p-3">{{ $qa['unanswered'] }}</td>
                        <td class="p-3">{{ $qa['difficulty'] }}</td>
                    </tr>
                @endforeach
            </tbody>

        </table>

        <div class="mt-4">
            {{ $questionAnalysis->links() }}
        </div>

    </div>

    <!-- Student Performance -->
    <div class="bg-white rounded-xl shadow p-5">

        <h2 class="font-semibold text-lg mb-4">
            Student Performance Overview
        </h2>

        <div class="relative" wire:poll.10s>

            <x-table :headers="['#','Student','Score','%','Correct','Incorrect','Unanswered','Time','Action']">

                @forelse($attempts as $index => $attempt)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $attempts->firstItem() + $index }}</td>
                        <td class="px-4 py-2">{{ $attempt->guest_name ?? 'Guest User' }}</td>
                        <td class="px-4 py-2">{{ $attempt->correct_count }}/{{ $attempt->correct_count + $attempt->incorrect_count + $attempt->unanswered_count }}</td>
                        <td class="px-4 py-2">{{ $attempt->percentage }}%</td>
                        <td class="px-4 py-2 text-green-600">{{ $attempt->correct_count }}</td>
                        <td class="px-4 py-2 text-red-600">{{ $attempt->incorrect_count }}</td>
                        <td class="px-4 py-2">{{ $attempt->unanswered_count }}</td>
                        <td class="px-4 py-2">{{ sprintf('%02d:%02d', floor($attempt->time_used_seconds / 60), $attempt->time_used_seconds % 60) }}</td>
                        <td class="px-4 py-2">
                            <div class="flex gap-2">
                                <button wire:click="viewAttempt({{ $attempt->id }})" class="bg-blue-500 text-white px-3 py-1 rounded">View</button>
                                <button wire:click="confirmDeleteAttempt({{ $attempt->id }})" class="bg-red-500 text-white px-3 py-1 rounded">Delete</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-gray-500">No attempts found</td>
                    </tr>
                @endforelse

            </x-table>

            <div class="mt-4">
                {{ $attempts->links() }}
            </div>

        </div>

    </div>

    @if(! empty($selectedAttempt))
        <div class="bg-white rounded-xl shadow p-5 mt-6">
            <div class="flex justify-between items-start">
                <h2 class="font-semibold text-lg">Exam Result (Attempt #{{ $selectedAttempt['id'] }})</h2>
                <button wire:click="closeAttempt" class="text-gray-600">Close</button>
            </div>

            <div class="grid grid-cols-3 gap-4 mt-4">
                <div>
                    <p class="text-sm text-gray-500">Score</p>
                    <h3 class="text-2xl font-bold">{{ $selectedAttempt['score'] ?? 0 }}</h3>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Correct</p>
                    <h3 class="text-2xl font-bold">{{ $selectedAttempt['correct_count'] ?? 0 }}</h3>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Total Questions</p>
                    <h3 class="text-2xl font-bold">{{ $selectedAttempt['total_marks'] ?? 0 }}</h3>
                </div>
            </div>

            <h3 class="mt-6 font-medium">Saved Responses</h3>

            <div class="mt-3 space-y-3">
                @foreach($selectedAttempt['answers'] as $i => $ans)
                    <div class="p-4 border rounded">
                        <div class="font-semibold">Question {{ $i + 1 }}: {{ Str::limit($ans['question']['question'] ?? "-", 120) }}</div>
                        <div class="mt-2 flex items-center gap-4">
                            <div>
                                <span class="text-sm text-gray-500">Answer:</span>
                                <div>{{ $ans['answer'] ?? 'No answer' }}</div>
                            </div>

                            <div>
                                <span class="text-sm text-gray-500">Correct:</span>
                                <div>{{ ! is_null($ans['is_correct']) && $ans['is_correct'] ? 'Yes' : 'No' }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- DELETE CONFIRM MODAL --}}
    @if($showDeleteConfirm)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white w-full max-w-sm p-6 rounded shadow">
                <h3 class="text-red-600 font-bold text-lg mb-3">Confirm Delete</h3>
                <p class="mb-6 text-gray-700">Are you sure you want to delete this attempt? This action cannot be undone.</p>
                <div class="flex justify-end gap-2">
                    <button wire:click="cancelDelete" class="px-4 py-2 border rounded hover:bg-gray-50 transition-all">Cancel</button>
                    <button wire:click="deleteAttempt" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition-all">Delete</button>
                </div>
            </div>
        </div>
    @endif

</div>