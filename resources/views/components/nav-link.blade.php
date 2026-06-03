@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-[#00E5FF] text-sm font-medium leading-5 text-[#0A192F] focus:outline-none focus:border-[#00B8CC] transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-[#496173] hover:text-[#0A192F] hover:border-[#D7F3F7] focus:outline-none focus:text-[#0A192F] focus:border-[#D7F3F7] transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
