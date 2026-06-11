<div id="main-content" class="min-h-screen w-full m-0 sm:m-10 bg-white shadow lg:rounded-lg flex flex-col lg:flex-row">
    <!-- Left Side -->
    <div class="w-full lg:w-1/2 xl:w-5/12 p-6 sm:p-12 flex flex-col justify-center lg:rounded-l-lg">    
        <!-- Logo -->
        <div class="flex flex-col items-center">
            <img src="{{ app(\App\Services\Setting::class)->logoUrl() }}" style="width:200px">
        </div>

        <!-- Title -->
        <div class="flex flex-col items-center mt-6">
            <h1 class="text-2xl xl:text-3xl font-extrabold text-center">Create an Account</h1>

            <div class="w-full flex-1 mt-6">
                <div class="mx-auto max-w-xs w-full">

                    <!-- Form starts -->
                    <form wire:submit.prevent="register">
                        <!-- Username -->
                        <x-label for="username">Username</x-label>
                        <x-input type="text" wire:model.defer="username" placeholder="Username"/>
                        <x-input-error field="username" />

                        <!-- Email -->
                        <x-label for="email">Email</x-label>
                        <x-input type="email" wire:model.defer="email" placeholder="Email"/> 
                        <x-input-error field="email" />

                        <!-- Password -->
                        <x-label for="password">Password</x-label>
                        <x-input type="password" wire:model.defer="password" placeholder="Password"/>
                        <x-input-error field="password" />
                        
                        <!-- Confirm Password -->
                        <x-label for="password_confirmation">Confirm Password</x-label>
                        <x-input type="password" wire:model.defer="password_confirmation" placeholder="Confirm Password"/>
                        <x-input-error field="password_confirmation" />

                        <!-- Register error -->
                        @if($registerError)
                            <p class="text-red-500 text-sm mt-3 text-center">{{ $registerError }}</p>
                        @endif
                        <!-- Register Button -->
                        <button type="submit"
                            class="mt-5 w-full py-4 bg-indigo-500 text-white rounded-lg flex justify-center items-center gap-2"
                            wire:loading.attr="disabled" wire:target="register">
                            <span wire:loading.remove wire:target="register">Create Account</span>
                            <span wire:loading wire:target="register">Processing...</span>
                        </button>

                        <!-- Register link -->
                        <p class="mt-6 text-xs text-gray-600 text-center">
                            Already have an account?
                            <a href="/login" class="border-b border-gray-500 text-blue-500 border-dotted">Sign In</a>
                        </p>
                    </form>
                    <!-- Form ends -->

                </div>
            </div>
        </div>
    </div>

    <!-- Right Side Image -->
    <div class="flex-1 hidden lg:flex bg-indigo-100 justify-center items-center lg:rounded-r-lg">
        <div class="w-full h-full bg-cover bg-center"
            style="background-image:url('{{ app(\App\Services\Setting::class)->urlForPath('image', 'background.jpg') }}')"></div>
    </div>

</div>