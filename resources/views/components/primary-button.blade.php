<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#00E5FF] border border-transparent rounded-lg font-semibold text-xs text-[#0A192F] uppercase tracking-widest hover:bg-[#00B8CC] focus:bg-[#00B8CC] active:bg-[#00B8CC] focus:outline-none focus:ring-2 focus:ring-[#00E5FF] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
