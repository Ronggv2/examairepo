<x-layouts.admin_layout>
    <div class="p-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900">Admin Dashboard</h1>
            <p class="text-gray-600 mt-2">Welcome to the admin panel. Manage users, exams, and system settings.</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Users -->
            <div class="bg-white rounded-xl shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Users</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">
                            {{ \App\Models\User::count() }}
                        </p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Exams -->
            <div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Exams</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">
                            {{ \App\Models\Exam::count() }}
                        </p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-lg">
                        <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v10H5V5z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Questions -->
            <div class="bg-white rounded-xl shadow p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Questions</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">
                            {{ \App\Models\Question::count() }}
                        </p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-lg">
                        <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01m-6.471-5c.549-1.165 2.03-2 3.772-2C13.75 5 15.5 6.343 15.5 8c0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M9 20a9 9 0 100-18 9 9 0 000 18z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- AI Generations -->
            <div class="bg-white rounded-xl shadow p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">AI Generations</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">
                            {{ \App\Models\AiGeneration::count() }}
                        </p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-lg">
                        <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v4h8v-4zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Users Management -->
            <a href="#" class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl shadow p-6 hover:shadow-lg transition-shadow">
                <h3 class="text-xl font-bold mb-2">User Management</h3>
                <p class="text-blue-100 mb-4">Manage user accounts and permissions</p>
                <div class="flex items-center gap-2 text-sm">
                    <span>→</span>
                    <span>View Users</span>
                </div>
            </a>

            <!-- AI Logs -->
            <a href="{{ route('ailog') }}" class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-xl shadow p-6 hover:shadow-lg transition-shadow">
                <h3 class="text-xl font-bold mb-2">AI Generation Logs</h3>
                <p class="text-purple-100 mb-4">View AI question generation history</p>
                <div class="flex items-center gap-2 text-sm">
                    <span>→</span>
                    <span>View Logs</span>
                </div>
            </a>

            <!-- Settings -->
            <a href="#" class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl shadow p-6 hover:shadow-lg transition-shadow">
                <h3 class="text-xl font-bold mb-2">Settings</h3>
                <p class="text-green-100 mb-4">Configure system settings and preferences</p>
                <div class="flex items-center gap-2 text-sm">
                    <span>→</span>
                    <span>Configure</span>
                </div>
            </a>
        </div>
    </div>
</x-layouts.admin_layout>
