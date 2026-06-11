<div id="main-content" class="min-h-screen w-full m-0 sm:m-10 bg-white shadow lg:rounded-lg flex flex-col lg:flex-row">
    <!-- Left Side -->
    <div class="w-full lg:w-1/2 xl:w-5/12 p-6 sm:p-12 flex flex-col justify-center lg:rounded-l-lg">    
        <!-- /back to main page -->
        <a href="{{ url()->previous() }}"
        class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 text-gray-600 hover:bg-gray-100">

            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 19l-7-7 7-7" />
            </svg>

        </a>
        <!-- Logo -->
        <div class="flex flex-col items-center">
            <img src="{{ app(\App\Services\Setting::class)->logoUrl() }}" style="width:200px">
        </div>

        <!-- Title -->
        <div class="flex flex-col items-center mt-6">
            <h1 class="text-2xl xl:text-3xl font-extrabold text-center">Join Exam</h1>

            <div class="w-full flex-1 mt-6">
                <div class="mx-auto max-w-xs w-full">

                    @if($joinError)
                        <p class="text-red-500 text-sm mt-3 text-center">{{ $joinError }}</p>
                    @endif

                    @if(!$exam_code)
                        <livewire:pages.auth.exam.examcode />
                    @else
                        <livewire:pages.auth.exam.examguest :exam_code="$exam_code" />
                    @endif

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