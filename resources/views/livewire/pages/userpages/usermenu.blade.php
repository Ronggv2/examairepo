<div>

    <livewire:components.form-header />

    <!-- Tabs (NO LIVEWIRE CLICK) -->
    <div class="bg-gray-100 border-b">
        <div class="flex gap-6 px-4">

            <a href="?tab=question{{ request()->has('question_set') ? '&question_set=' . request()->query('question_set') : '' }}"
               class="py-3 border-b-2 transition
               {{ $activeTab === 'question' ? 'border-blue-500 text-blue-500' : 'border-transparent text-gray-600 hover:text-blue-500' }}">
                Question
            </a>

            <a href="?tab=response{{ request()->has('question_set') ? '&question_set=' . request()->query('question_set') : '' }}"
               class="py-3 border-b-2 transition
               {{ $activeTab === 'response' ? 'border-blue-500 text-blue-500' : 'border-transparent text-gray-600 hover:text-blue-500' }}">
                Response
            </a>

            <a href="?tab=timer{{ request()->has('question_set') ? '&question_set=' . request()->query('question_set') : '' }}"
               class="py-3 border-b-2 transition
               {{ $activeTab === 'timer' ? 'border-blue-500 text-blue-500' : 'border-transparent text-gray-600 hover:text-blue-500' }}">
                Timer
            </a>

        </div>
    </div>

    <!-- Content -->
    <div class="p-6">

        @if($activeTab === 'question')
            <div class="space-y-6">
                <div class="grid gap-6 xl:grid-cols-[340px_minmax(0,1fr)]">
                    <div class="space-y-6">
                        <livewire:pages.userpages.question.exam-settings />
                        <livewire:pages.userpages.question.auto-change-settings />
                    </div>

                    <livewire:pages.userpages.question.generated-questions />
                </div>
            </div>
        @endif

        @if($activeTab === 'response')
            <livewire:pages.userpages.response.response :exam-id="request()->query('question_set')" />
        @endif

        @if($activeTab === 'timer')
           <livewire:pages.userpages.timer.timer/>
        @endif

    </div>
</div>