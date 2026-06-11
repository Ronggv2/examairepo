
<div>
    <div class="bg-white shadow w-full p-4">
        <div class="mx-auto px-4 pr-0">

            <div class="flex justify-between items-center h-16">

                <!-- LEFT SIDE -->
                <div class="flex items-center">
                    <a href="{{ route('users') }}" class="flex items-center gap-3 z-50 relative">
                        <img src="{{ app(\App\Services\Setting::class)->logoUrl() }}" class="h-12">
                    </a>

                    <div class="flex flex-col justify-center">
                        <input type="text" value="UntitledName" class="text-xl font-bold border-0 focus:ring-0 p-0 bg-transparent"  />
                    </div>
                </div>

                <!-- RIGHT SIDE -->
                <div class="flex items-center space-x-6">
                    <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                            Publish
                        </button>
                    <!-- USER INFO -->
                    <div class="flex items-center space-x-3">

                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z"/>
                        </svg>

                </div>

            </div>
        </div>
    </div>
</div>

