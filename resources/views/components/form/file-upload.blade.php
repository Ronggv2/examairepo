@props([ 
    'label' => '',
    'name' => '',
])

<div class="mb-4 w-full">
    <label class="block mb-2 text-sm font-medium text-gray-700">
        {{ $label }}
    </label>

    <input 
        type="file"
        {{ $attributes->merge([
            'class' => 'w-full bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg 
                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block px-3 py-2.5'
        ]) }}
        wire:model="{{ $name }}"
    >

    {{-- Loading --}}
    <div wire:loading wire:target="{{ $name }}" class="text-sm text-blue-500 mt-1">
        Uploading...
    </div>

    {{-- Error --}}
    @error($name)
        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
    @enderror
</div>