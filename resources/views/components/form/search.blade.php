@props([ 
    'label' => '',
    'name' => 'search',
    'placeholder' => 'Search...',
])

<div class="mb-4 w-full">
    @if($label)
        <label class="block mb-2 text-sm font-medium text-gray-700">
            {{ $label }}
        </label>
    @endif

    <input 
        type="text"
        name="{{ $name }}"
        placeholder="{{ $placeholder }}"
        wire:model.live.debounce.300ms="{{ $name }}"
        {{ $attributes->merge([
            'class' => 'w-full bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg 
                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2.5'
        ]) }}
    >

    @error($name)
        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
    @enderror
</div>