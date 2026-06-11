<div>
    @if($showModal)
        <!-- Modal Overlay -->
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
            wire:click="$dispatch('closeModal')" id="my-modal">
            <div class="relative top-5 mx-auto p-5 border md:w-1/2 w-96 shadow-lg rounded-md bg-white" wire:click.stop>
                <div class="mt-3 ">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">User Form</h3>
                    <div class="mt-2 px-7 py-3">
                        <div class="grid gap-6 mb-6">
                            <form wire:submit.prevent="submit">
                                @error('duplicate')
                                    <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <x-form.input-text label="Username" name="username" :value="$username" />
                                <x-form.input-text label="Email" name="email" :value="$email" />
                                <x-form.input-text label="Password" name="password" type="password" :value="$password" />

                                <div class="mb-4 w-full">
                                    <label class="block mb-2 text-sm font-medium text-gray-700" for="role">
                                        Role
                                    </label>
                                    <input type="text" id="role" value="User" readonly
                                        class="w-full bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block px-3 py-2.5" />
                                    <input type="hidden" name="role" wire:model.defer="role" value="user" />
                                    @error('role')
                                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="flex justify-end space-x-2 mt-4">
                                    <x-form.button type="submit" color="green" wire:loading.attr="disabled" wire:target="submit">
                                        <span wire:loading.remove>
                                            {{ $isEdit ? 'Update' : 'Save' }}
                                        </span>
                                        <span wire:loading>
                                            {{ $isEdit ? 'Updating...' : 'Saving...' }}
                                        </span>
                                    </x-form.button>
                                    <x-form.button type="button" color="red" wire:click="$dispatch('closeModal')">
                                        Cancel
                                    </x-form.button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

</div>
