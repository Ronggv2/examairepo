@props([
    'type' => 'submit',
    'color' => 'brand',
])

@php
$colors = [
    'brand' => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500',
    'red' => 'bg-red-600 hover:bg-red-700 focus:ring-red-500',
    'green' => 'bg-green-600 hover:bg-green-700 focus:ring-green-500',
];
$colorClasses = $colors[$color] ?? $colors['brand'];
@endphp

<button type="{{ $type }}"
        {{ $attributes->merge([
            'class' => "text-white $colorClasses box-border border border-transparent 
                        shadow-xs font-medium leading-5 rounded-full text-sm px-4 py-2.5 focus:outline-none"
        ]) }}>
    {{ $slot }}
</button>