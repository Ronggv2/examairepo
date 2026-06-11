@props([
    'label' => '',
    'name' => '',
    'options' => [], // ['M' => 'Male', 'F' => 'Female']
    'value' => '',
])

<div class="mb-4 w-full">
    <label class="block mb-2 text-sm font-medium text-gray-700">
        {{ $label }}
    </label>

    <div class="flex gap-4">
        @foreach($options as $optionValue => $text)
            <label class="flex items-center gap-2">
                <input 
                    type="radio" 
                    name="{{ $name }}"
                    value="{{ $optionValue }}"
                    wire:model.defer="{{ $name }}"
                    {{ $attributes->merge(['class' => 'text-blue-600']) }}
                >
                <span>{{ $text }}</span>
            </label>
        @endforeach
    </div>

    @error($name)
        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
    @enderror
</div>