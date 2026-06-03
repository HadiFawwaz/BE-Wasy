@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-[#496173]']) }}>
    {{ $value ?? $slot }}
</label>
