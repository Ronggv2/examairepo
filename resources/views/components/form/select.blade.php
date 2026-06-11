@props([
    'label' => '',
    'name' => '',
    'options' => [],
    'value' => null,
    'placeholder' => 'Select an option',
])

<div class="mb-4 w-full">
    <label class="left block mb-2 text-sm font-medium text-gray-700" for="{{ $name }}">{{ $label }}</label>
    
    <select
        id="{{ $name }}"
        name="{{ $name }}"
        {{ $attributes->merge([
            'class' => 'w-full bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg
                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block px-3 py-2.5'
        ]) }}
        wire:model="{{ $name }}"
    >
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}"
                    @if($value == $optionValue) selected @endif>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>

    @error($name)
        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
    @enderror
</div>
