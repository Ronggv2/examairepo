@props([ 
    'label' => '',
    'name' => '',
    'placeholder' => '',
    'type' => 'text',
    'disabled' => false,
])

<div class="mb-4 w-full">
    <label class="left block mb-2 text-sm font-medium text-gray-700" for="{{ $name }}">{{ $label }}</label>
    
    <input 
        type="{{ $type }}" 
        id="{{ $name }}" 
        name="{{ $name }}" 
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge([
            'class' => 'w-full ' . ($disabled ? 'bg-gray-200 cursor-not-allowed' : 'bg-gray-100') . ' border border-gray-300 text-gray-900 text-sm rounded-lg 
                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block px-3 py-2.5'
        ]) }} 
        placeholder="{{ $placeholder }}" 
        wire:model="{{ $name }}"
    >
    
    @error($name)
        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
    @enderror
</div>