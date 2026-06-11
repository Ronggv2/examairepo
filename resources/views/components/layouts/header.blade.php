
<div>
    <div class="bg-white shadow w-full p-4">
        <div class="mx-auto px-4 pr-0">

            <div class="flex justify-between items-center h-16">

                <!-- LEFT SIDE -->
                <div class="flex items-center">
                    <img src="{{ app(\App\Services\Setting::class)->logoUrl() }}" class="h-12 mr-4"/>

                    <div class="flex flex-col justify-center">
                        <h1 class="text-xl font-bold">Welcome Back.</h1>
                    </div>
                </div>

                <!-- RIGHT SIDE -->
                <div class="flex items-center space-x-6">

                    <!-- USER INFO -->
                    <div class="flex items-center space-x-3">

                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z"/>
                        </svg>
                        <div class="flex flex-col leading-tight">
                            <p class="text-sm font-medium">
                                Email: {{ auth()->user()->email ?? 'N/A' }}
                            </p>

                            <p class="text-xs text-gray-500">
                                Username: {{ optional(auth()->user()->owner)->username ?? auth()->user()->username ?? 'N/A' }} 
                            </p>
                            
                        </div>

                </div>

            </div>
        </div>
    </div>
</div>

