<x-layouts.admin_layout>
        <div class="flex justify-between items-center mb-4 border-b pb-2 pt-8">
            <h2 class="text-2xl font-bold">User Details</h2>
        </div>
<div>
    {{-- SUCCESS TOAST --}}
    <div x-data="{ show: false, message: '', timeout: null }" x-cloak x-init="
        window.addEventListener('schedule-created', event => {
            const payload = event.detail;
            message =
                payload?.message ||
                (Array.isArray(payload) ? payload[0]?.message : null) ||
                (typeof payload === 'string' ? payload : '');

            if (!message) return;

            show = true;

            if (timeout) clearTimeout(timeout);

            timeout = setTimeout(() => {
                show = false;
                message = '';
            }, 3000);
        })
    "
    x-show="show"
    x-transition
    class="fixed inset-x-0 top-6 flex justify-center z-50 pointer-events-none">

        <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-3 rounded shadow-lg">
            <span x-text="message"></span>
        </div>
    </div>

    {{-- SEARCH --}}
    <div class="grid gap-4 md:grid-cols-[1fr_220px] mb-4">
        <div>
            <x-form.search
                label="Search User"
                name="query"
                placeholder="Search by username, email, or role..."
            />
        </div>

        <div>
            <label class="block mb-2 text-sm font-medium text-gray-700" for="filter-role">
                Filter Role
            </label>
            <select id="filter-role" wire:model="selectedRole"
                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                <option value="">All Roles</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
        </div>
    </div>

    {{-- HEADER --}}
    <div class="flex justify-end mb-4">
        <button wire:click="openModal"
            class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded transition-all disabled:bg-blue-300 disabled:cursor-not-allowed">
            Add User
        </button>
    </div>

    {{-- TABLE --}}
    <div class="relative" wire:poll.5s>

        <div wire:loading.flex wire:target="query,selectedRole,page" class="absolute inset-0 z-20 bg-white/80 backdrop-blur-sm items-center justify-center">
            <div class="rounded-lg bg-white px-4 py-3 shadow border border-gray-200 text-gray-700">
                Loading users...
            </div>
        </div>

        <x-table :headers="['Username', 'Email', 'Created At', 'Updated At', 'Action States', 'Actions']">

            @forelse($users as $user)
                <tr class="hover:bg-gray-50">

                    <td class="px-4 py-2">
                        {{ $user->username ?? $user->name ?? 'N/A' }}
                    </td>

                    <td class="px-4 py-2">{{ $user->email ?? 'N/A' }}</td>

                    <td class="px-4 py-2">{{ $user->created_at?->format('Y-m-d') }}</td>

                    <td class="px-4 py-2">{{ $user->updated_at?->format('Y-m-d') }}</td>

                    <td class="px-4 py-2">
                        <button wire:click="actionUser({{ $user->id }})"
                            class="px-2 py-1 rounded font-medium transition-all duration-200
                                {{ $user->status === 'active'
                                    ? 'bg-green-500 text-white shadow-lg'
                                    : ($user->status === 'suspended'
                                        ? 'bg-yellow-500 text-white shadow-lg'
                                        : 'bg-gray-400 text-white hover:bg-gray-500') }}
                                disabled:bg-gray-300 disabled:cursor-not-allowed">
                            {{ $user->status === 'active'
                                ? '✓ Active'
                                : ($user->status === 'suspended' ? 'Unsuspend' : 'Inactive') }}
                        </button>
                    </td>

                    <td class="px-4 py-2 flex gap-2">

                        <button wire:click="confirmEditUser({{ $user->id }})"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded transition-all disabled:bg-blue-300 disabled:cursor-not-allowed">
                            Edit
                        </button>

                        @if($user->status !== 'suspended')
                            <button wire:click="confirmDeleteUser({{ $user->id }})"
                                class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded transition-all disabled:bg-red-300 disabled:cursor-not-allowed">
                                Suspend
                            </button>
                        @endif

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-gray-500">
                        No users found
                    </td>
                </tr>
            @endforelse

        </x-table>

        {{-- PAGINATION --}}
        <div class="mt-4">
            {{ $users->links() }}
        </div>

    </div>

    {{-- USER FORM MODAL --}}
    @if($showUserForm)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-40">
            <div class="bg-white w-full max-w-md rounded shadow p-4">
                <livewire:admin.accesscontrol.userform :userId="$selectedUserId" />
            </div>
        </div>
    @endif

    {{-- USER DETAIL MODAL --}}
    @if($showUserDetail && $selectedUser)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-40">

            <div class="bg-white w-full max-w-md rounded shadow p-6">

                <h3 class="text-lg font-bold mb-4">User Details</h3>

                <div class="space-y-3">

                    <p><strong>Name:</strong> {{ $selectedUser->username ?? $selectedUser->name }}</p>
                    <p><strong>Email:</strong> {{ $selectedUser->email }}</p>
                    <p><strong>Created At:</strong> {{ $selectedUser->created_at?->format('Y-m-d H:i') }}</p>
                    <p><strong>Updated At:</strong> {{ $selectedUser->updated_at?->format('Y-m-d H:i') }}</p>
                    

                    <p><strong>Time:</strong>
                        @if(optional($selectedUser->schedule))
                            {{ \Carbon\Carbon::parse(optional($selectedUser->schedule)->start_time)->format('H:i') }}
                            -
                            {{ \Carbon\Carbon::parse(optional($selectedUser->schedule)->end_time)->format('H:i') }}
                        @else
                            N/A
                        @endif
                    </p>

                </div>

                <div class="flex justify-end mt-6">
                    <button wire:click="$set('showUserDetail', false)"
                        class="px-4 py-2 border rounded hover:bg-gray-50 transition-all">
                        Close
                    </button>
                </div>

            </div>
        </div>
    @endif

    {{-- DELETE CONFIRM --}}
    @if($showDeleteConfirm)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-40">

            <div class="bg-white w-full max-w-sm p-6 rounded">

                <h3 class="text-red-600 font-bold mb-3">Confirm Suspend</h3>

                <p class="mb-4">Are you sure you want to suspend this user?</p>

                <div class="flex justify-end gap-2">

                    <button wire:click="cancelDelete"
                        class="px-4 py-2 border rounded hover:bg-gray-50 transition-all disabled:text-gray-400 disabled:cursor-not-allowed">
                        Cancel
                    </button>

                    <button wire:click="deleteUser"
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition-all disabled:bg-red-300 disabled:cursor-not-allowed">
                        Suspend
                    </button>

                </div>

            </div>
        </div>
    @endif

</div>
    </x-layouts.admin_layout>
    