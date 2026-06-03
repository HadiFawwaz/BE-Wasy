@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-[#D7F3F7] focus:border-[#00E5FF] focus:ring-[#00E5FF] rounded-lg shadow-sm']) }}>
