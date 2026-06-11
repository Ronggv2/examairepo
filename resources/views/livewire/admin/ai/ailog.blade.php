<x-layouts.admin_layout>
    <div class="p-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900">AI Generation Logs</h1>
            <p class="text-gray-600 mt-2">View the history of all AI-generated question sets.</p>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">#</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">User</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Topic</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Subject</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Difficulty</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Questions</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-6 py-3 text-sm text-gray-900">{{ $log->id }}</td>
                                <td class=\"px-6 py-3 text-sm text-gray-900\">
                                    @if($log->user && $log->user->name)
                                        {{ $log->user->name }}
                                    @elseif($log->user_id)
                                        User #{{ $log->user_id }}
                                    @else
                                        Unknown
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-900">{{ Str::limit($log->topic ?? '-', 30) }}</td>
                                <td class="px-6 py-3 text-sm text-gray-900">{{ $log->subject ?? '-' }}</td>
                                <td class="px-6 py-3 text-sm">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $log->difficulty === 'Easy' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $log->difficulty === 'Medium' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $log->difficulty === 'Hard' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ $log->difficulty ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-900">{{ $log->question_count ?? 0 }}</td>
                                <td class="px-6 py-3 text-sm text-gray-500">{{ $log->created_at?->format('M d, Y H:i') ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">No AI generation logs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</x-layouts.admin_layout>
