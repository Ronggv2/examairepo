 <!-- Form starts -->
                    <form wire:submit.prevent="joinExam">
                        <!-- Guest Name -->
                        <x-label for="guest_name">Enter Guest Name</x-label>
                        <x-input type="text" wire:model.defer="guest_name" placeholder="Enter Guest Name"/>
                        <x-input-error field="guest_name" />

                        <!-- Join error -->
                        @if($joinError)
                            <p class="text-red-500 text-sm mt-3 text-center">{{ $joinError }}</p>
                        @endif
                        <!-- Login Button -->
                        <button type="submit"
                            class="mt-16 w-full py-4 bg-indigo-500 text-white rounded-lg flex justify-center items-center gap-2"
                            wire:loading.attr="disabled" wire:target="joinExam">
                            <span wire:loading.remove wire:target="joinExam">Join Exam</span>
                            <span wire:loading wire:target="joinExam">Joining...</span>
                        </button>

                    </form>
                    <!-- Form ends -->