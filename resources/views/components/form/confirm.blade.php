<div>

    confirmation modal
    <div x-data="{ show: @entangle('showModal') }" x-show="show" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-sm">
            <h2 class="text-xl font-bold mb-4">Confirm Edit</h2>
            <p class="mb-4">Are you sure you want to edit this teacher's information?</p>
            <div class="flex justify-end space-x-2">
                <x-form.button color="red" wire:click="$set('showModal', false)">
                    Cancel
                </x-form.button>
                <x-form.button color="green" wire:click="editTeacher({{ $teacherId }})">
                    Confirm
                </x-form.button>
            </div>
</div>