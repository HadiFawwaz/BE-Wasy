<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>wasy</title>

        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            :root {
                --wt-navy: #0A192F;
                --wt-muted-navy: #496173;
                --wt-aqua: #00E5FF;
                --wt-aqua-dark: #00B8CC;
                --wt-surface: #FFFFFF;
                --wt-line: #D7F3F7;
            }
        </style>
    </head>
    <body class="font-sans text-[var(--wt-navy)] antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center px-4 py-10 bg-[var(--wt-surface)]">
            <div class="flex items-center gap-3 rounded-2xl bg-[var(--wt-navy)] px-5 py-4 text-white shadow-[0_18px_48px_rgba(10,25,47,0.16)]">
                <a href="/">
                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-white">
                        <x-application-logo class="h-10 w-10" />
                    </span>
                </a>
                <div>
                    <p class="text-lg font-bold leading-5 lowercase">wasy</p>
                    <p class="text-xs text-white/70">Account Access</p>
                </div>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-5 bg-white shadow-[0_24px_80px_rgba(10,25,47,0.10)] border border-[var(--wt-line)] overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
