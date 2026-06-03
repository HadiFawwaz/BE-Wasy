@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="min-h-screen bg-[#F7FEFF] p-4 text-[#0A192F] sm:p-6 lg:p-8">
    <div class="mx-auto grid min-h-[calc(100vh-2rem)] w-full max-w-6xl overflow-hidden rounded-[34px] border border-[#D7F3F7] bg-white shadow-[0_28px_90px_rgba(10,25,47,0.12)] lg:min-h-[calc(100vh-4rem)] lg:grid-cols-[0.95fr_1.05fr]">
        <section class="flex min-h-[620px] flex-col px-6 py-7 sm:px-10 lg:px-14">
            <div class="flex items-center gap-3">
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-[#E6FCFF] ring-1 ring-[#D7F3F7]">
                    <x-application-logo class="h-9 w-9" />
                </span>
                <div>
                    <p class="text-lg font-black lowercase leading-5">wasy</p>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#00B8CC]">Laundry Care</p>
                </div>
            </div>

            <div class="mx-auto flex w-full max-w-md flex-1 flex-col justify-center py-8">
                <div class="mb-8">
                    <p class="mb-3 inline-flex rounded-full bg-[#E6FCFF] px-3 py-1 text-xs font-black uppercase tracking-[0.14em] text-[#00B8CC]">Dashboard Admin</p>
                    <h1 class="text-4xl font-black leading-[1.05] tracking-normal text-[#0A192F] sm:text-5xl">Kelola laundry dengan rapi.</h1>
                    <p class="mt-4 max-w-sm text-sm leading-6 text-[#496173]">Pantau order masuk, update status cucian, cek pembayaran, dan siapkan pickup pelanggan dari satu tempat.</p>
                </div>

                <form id="loginForm" class="space-y-5">
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-bold text-[#0A192F]">Email</label>
                        <input type="email" id="email" required maxlength="255" autocomplete="email" placeholder="admin@laundry.com" class="h-12 w-full rounded-2xl border border-[#D7F3F7] bg-[#F7FEFF] px-4 text-sm font-semibold text-[#0A192F] placeholder:text-[#8AA0B3] focus:border-[#00E5FF] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#00E5FF]/15">
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-bold text-[#0A192F]">Password</label>
                        <div class="relative">
                            <input type="password" id="password" required minlength="6" autocomplete="current-password" placeholder="Masukkan password" class="h-12 w-full rounded-2xl border border-[#D7F3F7] bg-[#F7FEFF] px-4 pr-16 text-sm font-semibold text-[#0A192F] placeholder:text-[#8AA0B3] focus:border-[#00E5FF] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#00E5FF]/15">
                            <button type="button" id="togglePassword" class="absolute right-2 top-1/2 h-8 -translate-y-1/2 rounded-xl px-3 text-xs font-black text-[#00B8CC] hover:bg-[#E6FCFF]">Lihat</button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-4 text-xs font-semibold text-[#496173]">
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" class="h-4 w-4 rounded border-[#D7F3F7] text-[#00B8CC] focus:ring-[#00E5FF]" checked>
                            <span>Ingat perangkat</span>
                        </label>
                        <span class="text-[#00B8CC]">Admin only</span>
                    </div>

                    <button type="submit" id="submitBtn" disabled class="flex h-12 w-full items-center justify-center rounded-2xl bg-[#00E5FF] text-sm font-black text-[#0A192F] shadow-[0_16px_32px_rgba(0,229,255,0.28)] transition hover:bg-[#00B8CC] disabled:cursor-not-allowed disabled:bg-[#B8EEF5] disabled:shadow-none">
                        Sign In
                    </button>
                </form>

                <div id="errorMessage" class="mt-5 hidden rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700"></div>

                <div class="mt-8 grid grid-cols-3 gap-3">
                    <div class="rounded-2xl border border-[#D7F3F7] bg-[#F7FEFF] p-3">
                        <p class="text-lg font-black">Order</p>
                        <p class="mt-1 text-[11px] font-semibold text-[#496173]">Transaksi</p>
                    </div>
                    <div class="rounded-2xl border border-[#D7F3F7] bg-[#F7FEFF] p-3">
                        <p class="text-lg font-black">Status</p>
                        <p class="mt-1 text-[11px] font-semibold text-[#496173]">Cucian</p>
                    </div>
                    <div class="rounded-2xl border border-[#D7F3F7] bg-[#F7FEFF] p-3">
                        <p class="text-lg font-black">Pickup</p>
                        <p class="mt-1 text-[11px] font-semibold text-[#496173]">Siap ambil</p>
                    </div>
                </div>
            </div>

            <p class="text-xs font-semibold text-[#8AA0B3]">© 2026 wasy. Laundry management dashboard.</p>
        </section>

        <section class="relative hidden overflow-hidden bg-[#0A192F] lg:block">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_15%,rgba(0,229,255,0.32),transparent_28%),radial-gradient(circle_at_78%_22%,rgba(255,255,255,0.18),transparent_24%),linear-gradient(135deg,#0A192F_0%,#0E2D4B_48%,#00B8CC_100%)]"></div>
            <div class="absolute left-10 right-10 top-10 flex items-center justify-between text-white/80">
                <span class="text-xs font-black uppercase tracking-[0.28em]">Fresh Laundry</span>
                <span class="rounded-full border border-white/20 px-3 py-1 text-xs font-bold">Admin Area</span>
            </div>

            <div class="absolute left-10 right-10 top-28 rounded-[32px] border border-white/15 bg-white/10 p-7 text-white shadow-[0_24px_80px_rgba(0,0,0,0.20)] backdrop-blur">
                <div class="flex items-center gap-4">
                    <span class="inline-flex h-16 w-16 items-center justify-center rounded-3xl bg-white">
                        <x-application-logo class="h-14 w-14" />
                    </span>
                    <div>
                        <p class="text-2xl font-black lowercase leading-6">wasy</p>
                        <p class="mt-1 text-sm text-white/70">clean orders, clear status</p>
                    </div>
                </div>
                <div class="mt-8 grid grid-cols-3 gap-3">
                    <div class="rounded-2xl bg-white/12 p-4">
                        <p class="text-2xl font-black">46</p>
                        <p class="mt-1 text-xs text-white/65">Order aktif</p>
                    </div>
                    <div class="rounded-2xl bg-white/12 p-4">
                        <p class="text-2xl font-black">9</p>
                        <p class="mt-1 text-xs text-white/65">Siap pickup</p>
                    </div>
                    <div class="rounded-2xl bg-white/12 p-4">
                        <p class="text-2xl font-black">32</p>
                        <p class="mt-1 text-xs text-white/65">Lunas</p>
                    </div>
                </div>
            </div>

            <div class="absolute bottom-10 left-10 right-10">
                <div class="mb-6 h-52 rounded-[34px] border border-white/15 bg-white/10 p-5 shadow-[0_24px_80px_rgba(0,0,0,0.24)] backdrop-blur">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-bold text-white/75">Laundry Flow</p>
                        <span class="h-3 w-3 rounded-full bg-[#00E5FF] shadow-[0_0_24px_rgba(0,229,255,0.9)]"></span>
                    </div>
                    <div class="mt-8 flex items-end gap-4">
                        <span class="h-16 flex-1 rounded-t-3xl bg-white/25"></span>
                        <span class="h-24 flex-1 rounded-t-3xl bg-[#00E5FF]"></span>
                        <span class="h-32 flex-1 rounded-t-3xl bg-white"></span>
                        <span class="h-20 flex-1 rounded-t-3xl bg-white/35"></span>
                        <span class="h-28 flex-1 rounded-t-3xl bg-[#00E5FF]/80"></span>
                    </div>
                    <p class="mt-5 text-xs font-semibold text-white/65">Monitor pembayaran, status cucian, dan pickup tanpa pindah halaman.</p>
                </div>
                <h2 class="max-w-md text-5xl font-black leading-[0.98] tracking-normal text-white">Cucian rapi, pelanggan tenang.</h2>
            </div>
        </section>
    </div>
</div>

<script>
    const form = document.getElementById('loginForm');
    const errorDiv = document.getElementById('errorMessage');
    const submitBtn = document.getElementById('submitBtn');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const togglePasswordBtn = document.getElementById('togglePassword');

    function updateSubmitState() {
        const email = emailInput.value.trim();
        const password = passwordInput.value.trim();
        submitBtn.disabled = !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email) || password.length < 6;
    }

    async function checkExistingToken() {
        const token = localStorage.getItem('auth_token');
        if (!token) return;

        try {
            const response = await fetch('/api/profile', {
                headers: { Authorization: `Bearer ${token}` }
            });

            if (!response.ok) {
                localStorage.removeItem('auth_token');
                return;
            }

            const profile = await response.json();
            if (profile.user?.role === 'admin') {
                window.location.href = '/dashboard';
            } else {
                localStorage.removeItem('auth_token');
            }
        } catch (_) {
            localStorage.removeItem('auth_token');
        }
    }

    togglePasswordBtn.addEventListener('click', () => {
        const shouldShow = passwordInput.type === 'password';
        passwordInput.type = shouldShow ? 'text' : 'password';
        togglePasswordBtn.textContent = shouldShow ? 'Tutup' : 'Lihat';
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        errorDiv.classList.add('hidden');
        updateSubmitState();
        if (submitBtn.disabled) {
            errorDiv.textContent = 'Email harus valid dan password minimal 6 karakter.';
            errorDiv.classList.remove('hidden');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.textContent = 'Memproses...';

        try {
            const response = await fetch('/api/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    email: emailInput.value.trim(),
                    password: passwordInput.value.trim()
                })
            });

            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Login gagal');
            }

            if (!data.user || data.user.role !== 'admin') {
                throw new Error('Hanya akun admin yang dapat mengakses dashboard.');
            }

            localStorage.setItem('auth_token', data.access_token);
            localStorage.setItem('auth_user_name', data.user.name || 'Admin Laundry');
            localStorage.setItem('auth_user_role', data.user.role || 'admin');
            window.location.href = '/dashboard';
        } catch (error) {
            errorDiv.textContent = error.message;
            errorDiv.classList.remove('hidden');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Sign In';
            updateSubmitState();
        }
    });

    [emailInput, passwordInput].forEach((input) => {
        input.addEventListener('input', () => {
            errorDiv.classList.add('hidden');
            updateSubmitState();
        });
    });

    checkExistingToken();
    updateSubmitState();
</script>
@endsection
