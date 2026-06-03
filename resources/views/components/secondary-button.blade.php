<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white border border-[#D7F3F7] rounded-lg font-semibold text-xs text-[#0A192F] uppercase tracking-widest shadow-sm hover:bg-[#E6FCFF] focus:outline-none focus:ring-2 focus:ring-[#00E5FF] focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
