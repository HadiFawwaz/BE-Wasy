<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>wasy - @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --wt-navy: #0A192F;
            --wt-muted-navy: #496173;
            --wt-aqua: #00E5FF;
            --wt-aqua-dark: #00B8CC;
            --wt-aqua-soft: #E6FCFF;
            --wt-line: #D7F3F7;
            --wt-table-head: #F2FDFF;
            --wt-golden-yellow: #FBB03B;
            --wt-surface: #FFFFFF;
            --wt-card-shadow: 0px 10px 28px rgba(10, 25, 47, 0.06);
        }

        html {
            color: var(--wt-navy);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        ::selection {
            background: rgba(0, 229, 255, 0.22);
            color: var(--wt-navy);
        }

        * {
            scrollbar-width: thin;
            scrollbar-color: rgba(0, 229, 255, 0.45) transparent;
        }

        *::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        *::-webkit-scrollbar-thumb {
            background: rgba(0, 229, 255, 0.38);
            border: 3px solid transparent;
            border-radius: 999px;
            background-clip: padding-box;
        }

        input,
        select,
        textarea {
            background-color: #fff;
            transition: border-color 160ms ease, box-shadow 160ms ease, background-color 160ms ease;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: var(--wt-aqua) !important;
            box-shadow: 0 0 0 4px rgba(0, 229, 255, 0.14);
            outline: none;
        }

        button {
            transition: background-color 160ms ease, border-color 160ms ease, color 160ms ease, box-shadow 160ms ease, transform 160ms ease;
        }

        button:not(:disabled):active {
            transform: translateY(1px);
        }

        table tbody tr {
            transition: background-color 140ms ease;
        }

        table tbody tr:hover {
            background: #F7FEFF;
        }

        thead {
            background: var(--wt-table-head) !important;
            color: var(--wt-muted-navy) !important;
        }

        html,
        body {
            max-width: 100%;
            overflow: hidden;
        }

        .app-shell {
            height: 100vh;
            height: 100dvh;
            max-width: 100vw;
            overflow: hidden;
        }

        .app-content-scroll {
            min-height: 0;
            overflow-x: hidden;
            overflow-y: auto;
            overscroll-behavior: contain;
        }

        @media (max-height: 760px) and (min-width: 1280px) {
            .sidebar-extra {
                display: none;
            }

            .sidebar-menu {
                gap: 0.25rem;
            }
        }

        @media (max-width: 1023px) {
            html,
            body {
                overflow: hidden;
            }
        }
    </style>
</head>
<body class="bg-[#F7FEFF]">
    <div class="app-shell flex w-full bg-[#F7FEFF]">
        <aside class="hidden shrink-0 border-r border-[var(--wt-line)] bg-[#ECFBFE] p-3 xl:block xl:w-[248px] 2xl:w-[292px] 2xl:p-4">
            <div class="flex h-full min-w-0 flex-col rounded-[26px] border border-white bg-white/90 p-4 shadow-[0_16px_44px_rgba(10,25,47,0.08)] 2xl:rounded-[30px] 2xl:p-5">
                <div class="flex items-center gap-3 border-b border-[var(--wt-line)] pb-5">
                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-[var(--wt-aqua-soft)]">
                        <x-application-logo class="h-9 w-9" />
                    </span>
                    <div class="min-w-0">
                        <p class="text-lg font-black leading-5 text-[var(--wt-navy)] lowercase">wasy</p>
                        <p class="mt-1 text-xs font-bold uppercase tracking-[0.18em] text-[var(--wt-aqua-dark)]">Laundry POS</p>
                    </div>
                </div>

                <nav class="mt-5 flex flex-1 flex-col 2xl:mt-6">
                    <p class="px-3 text-xs font-black uppercase tracking-[0.16em] text-[var(--wt-muted-navy)]">Menu</p>

                    <div class="sidebar-menu mt-3 space-y-1.5 2xl:space-y-2">
                        <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition {{ request()->routeIs('dashboard') ? 'bg-[var(--wt-navy)] text-white shadow-[0_12px_26px_rgba(10,25,47,0.18)]' : 'text-[var(--wt-muted-navy)] hover:bg-[var(--wt-aqua-soft)] hover:text-[var(--wt-navy)]' }}">
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl {{ request()->routeIs('dashboard') ? 'bg-white/12' : 'bg-[#F7FEFF] text-[var(--wt-navy)] group-hover:bg-white' }}">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M4 10.5 12 4l8 6.5V20a1 1 0 0 1-1 1h-5v-6h-4v6H5a1 1 0 0 1-1-1z"/></svg>
                            </span>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('transactions.index') }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition {{ request()->routeIs('transactions.index') ? 'bg-[var(--wt-navy)] text-white shadow-[0_12px_26px_rgba(10,25,47,0.18)]' : 'text-[var(--wt-muted-navy)] hover:bg-[var(--wt-aqua-soft)] hover:text-[var(--wt-navy)]' }}">
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl {{ request()->routeIs('transactions.index') ? 'bg-white/12' : 'bg-[#F7FEFF] text-[var(--wt-navy)] group-hover:bg-white' }}">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M7 4h10l2 4v12H5V8z"/><path d="M7 8h10"/><path d="M9 13h6"/><path d="M9 17h4"/></svg>
                            </span>
                            <span>Transaksi</span>
                            <span class="ml-auto inline-flex h-6 min-w-6 items-center justify-center rounded-full bg-[var(--wt-aqua-soft)] px-2 text-xs font-black text-[var(--wt-aqua-dark)]">POS</span>
                        </a>
                        <a href="{{ route('customers.index') }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition {{ request()->routeIs('customers.index') ? 'bg-[var(--wt-navy)] text-white shadow-[0_12px_26px_rgba(10,25,47,0.18)]' : 'text-[var(--wt-muted-navy)] hover:bg-[var(--wt-aqua-soft)] hover:text-[var(--wt-navy)]' }}">
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl {{ request()->routeIs('customers.index') ? 'bg-white/12' : 'bg-[#F7FEFF] text-[var(--wt-navy)] group-hover:bg-white' }}">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"/><circle cx="9.5" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            </span>
                            <span>Pelanggan</span>
                        </a>
                        <a href="{{ route('services.index') }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition {{ request()->routeIs('services.index') ? 'bg-[var(--wt-navy)] text-white shadow-[0_12px_26px_rgba(10,25,47,0.18)]' : 'text-[var(--wt-muted-navy)] hover:bg-[var(--wt-aqua-soft)] hover:text-[var(--wt-navy)]' }}">
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl {{ request()->routeIs('services.index') ? 'bg-white/12' : 'bg-[#F7FEFF] text-[var(--wt-navy)] group-hover:bg-white' }}">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16"/><path d="M4 12h16"/><path d="M4 17h16"/><path d="M7 4v16"/><path d="M17 4v16"/></svg>
                            </span>
                            <span>Layanan</span>
                        </a>
                        <a href="{{ route('reports.index') }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition {{ request()->routeIs('reports.index') ? 'bg-[var(--wt-navy)] text-white shadow-[0_12px_26px_rgba(10,25,47,0.18)]' : 'text-[var(--wt-muted-navy)] hover:bg-[var(--wt-aqua-soft)] hover:text-[var(--wt-navy)]' }}">
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl {{ request()->routeIs('reports.index') ? 'bg-white/12' : 'bg-[#F7FEFF] text-[var(--wt-navy)] group-hover:bg-white' }}">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19V5"/><path d="M4 19h16"/><path d="M8 16v-5"/><path d="M12 16V8"/><path d="M16 16v-3"/></svg>
                            </span>
                            <span>Laporan</span>
                        </a>
                    </div>

                    <div class="sidebar-extra mt-auto rounded-[24px] border border-[var(--wt-line)] bg-[#F7FEFF] p-4">
                        <p class="text-xs font-black uppercase tracking-[0.16em] text-[var(--wt-aqua-dark)]">Quick Flow</p>
                        <div class="mt-4 space-y-3">
                            <div class="flex items-center justify-between text-sm">
                                <span class="font-semibold text-[var(--wt-muted-navy)]">Order</span>
                                <span class="h-2.5 w-2.5 rounded-full bg-[var(--wt-aqua)]"></span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-white">
                                <div class="h-full w-3/4 rounded-full bg-[var(--wt-aqua)]"></div>
                            </div>
                        </div>
                    </div>

                    <button id="logoutBtn" class="mt-3 flex w-full items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold text-[var(--wt-muted-navy)] transition hover:bg-[#FFF4F4] hover:text-[#D64545]">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-white">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M10 17l5-5-5-5"/><path d="M15 12H3"/><path d="M21 5v14a2 2 0 0 1-2 2h-6"/><path d="M13 3h6a2 2 0 0 1 2 2"/></svg>
                        </span>
                        <span>Logout</span>
                    </button>
                </nav>
            </div>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col overflow-hidden">
            <div class="shrink-0 bg-white border-b border-[var(--wt-line)] px-4 py-3 flex justify-between items-center gap-4 sm:px-6 xl:px-7 2xl:px-8 2xl:py-4">
                <div class="min-w-0">
                    <p class="text-xs font-semibold uppercase tracking-wide text-[var(--wt-muted-navy)]">wasy management</p>
                    <h1 class="truncate text-xl font-black text-[var(--wt-navy)] leading-tight sm:text-2xl">@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="flex shrink-0 items-center gap-2 rounded-full border border-[var(--wt-line)] bg-[#F7FEFF] px-2 py-1.5 sm:gap-3 sm:px-3 sm:py-2">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-[var(--wt-aqua-soft)] text-sm font-bold text-[var(--wt-aqua-dark)]">A</span>
                    <span id="userName" class="hidden max-w-[150px] truncate text-[var(--wt-navy)] font-semibold sm:inline">Memuat...</span>
                </div>
            </div>

            <div id="loadingSpinner" class="hidden fixed inset-0 bg-[rgba(10,25,47,0.36)] backdrop-blur-sm items-center justify-center z-50">
                <div class="bg-white rounded-xl p-8 text-center shadow-[0_24px_80px_rgba(10,25,47,0.22)] border border-[var(--wt-line)]">
                    <div class="animate-spin inline-flex items-center justify-center w-12 h-12 border-4 border-[var(--wt-aqua)] border-t-transparent rounded-full"></div>
                    <p class="mt-4 text-gray-700">Memproses...</p>
                </div>
            </div>

            <div id="toast" class="hidden fixed top-4 right-4 px-6 py-3 bg-white rounded-lg shadow-[0_18px_50px_rgba(10,25,47,0.14)] border-l-4 z-50 text-gray-700"></div>

            <div id="logoutConfirmModal" class="hidden fixed inset-0 z-50 items-center justify-center bg-[rgba(10,25,47,0.38)] backdrop-blur-sm p-4">
                <div class="w-full max-w-md rounded-2xl border border-[var(--wt-line)] bg-white shadow-[0_24px_80px_rgba(10,25,47,0.22)] overflow-hidden">
                    <div class="px-6 py-5 border-b border-[var(--wt-line)]">
                        <h2 class="text-lg font-bold text-[var(--wt-navy)]">Konfirmasi Logout</h2>
                        <p class="mt-1 text-sm text-[var(--wt-muted-navy)]">Sesi admin akan diakhiri dan kamu perlu login lagi untuk masuk dashboard.</p>
                    </div>
                    <div class="px-6 py-5 flex items-center justify-end gap-3">
                        <button id="cancelLogoutBtn" type="button" class="h-10 px-4 rounded-lg border border-[var(--wt-line)] bg-white text-[var(--wt-navy)] hover:bg-[var(--wt-aqua-soft)] font-medium">Batal</button>
                        <button id="confirmLogoutBtn" type="button" class="h-10 px-4 rounded-lg bg-[#ef4444] text-white hover:bg-[#dc2626] font-medium">Logout</button>
                    </div>
                </div>
            </div>

            <script>
        const token = localStorage.getItem('auth_token');
        const cachedUserName = localStorage.getItem('auth_user_name');
        const cachedUserRole = localStorage.getItem('auth_user_role');
        const userNameElement = document.getElementById('userName');

        if (cachedUserName && userNameElement) {
            userNameElement.textContent = cachedUserName;
        }

        if (!token) {
            window.location.href = '/login';
        }

        if (cachedUserRole && cachedUserRole !== 'admin') {
            localStorage.removeItem('auth_token');
            localStorage.removeItem('auth_user_name');
            localStorage.removeItem('auth_user_role');
            window.location.href = '/login';
        }

        window.toggleLoading = function(show) {
            const spinner = document.getElementById('loadingSpinner');
            if (show) {
                spinner.classList.remove('hidden');
                spinner.classList.add('flex');
            } else {
                spinner.classList.add('hidden');
                spinner.classList.remove('flex');
            }
        };

        window.showToast = function(message, type = 'success') {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.className = 'fixed top-4 right-4 max-w-sm px-6 py-3 bg-white rounded-xl shadow-[0_18px_55px_rgba(10,25,47,0.18)] border-l-4 z-[80] text-gray-700';
            toast.style.borderLeftColor = '#00E5FF';

            if (type === 'success') toast.style.borderLeftColor = '#10b981';
            if (type === 'error') toast.style.borderLeftColor = '#ef4444';

            toast.classList.remove('hidden');
            setTimeout(() => toast.classList.add('hidden'), 3000);
        };

        window.showActionAlert = async function({
            title = 'Perhatian',
            text = '',
            icon = 'info',
            confirmText = 'Mengerti',
        } = {}) {
            if (typeof Swal === 'undefined') {
                alert(text ? `${title}\n${text}` : title);
                return;
            }

            await Swal.fire({
                title,
                text,
                icon,
                confirmButtonText: confirmText,
                background: '#FFFFFF',
                color: '#0A192F',
                customClass: {
                    popup: 'rounded-[28px] border border-[#D7F3F7] shadow-[0_24px_80px_rgba(10,25,47,0.22)]',
                    title: 'text-[#0A192F] text-2xl font-black',
                    htmlContainer: 'text-[#496173] text-sm',
                    confirmButton: 'h-11 px-5 rounded-full bg-[#00E5FF] text-[#0A192F] font-bold hover:bg-[#00B8CC] focus:outline-none',
                },
                buttonsStyling: false,
            });
        };

        window.showSuccessAlert = async function({
            title = 'Berhasil',
            text = 'Data berhasil disimpan.',
            confirmText = 'Oke',
        } = {}) {
            if (typeof Swal === 'undefined') {
                alert(`${title}\n${text}`);
                return;
            }

            await Swal.fire({
                title,
                text,
                icon: 'success',
                confirmButtonText: confirmText,
                timer: 1800,
                timerProgressBar: true,
                background: '#FFFFFF',
                color: '#0A192F',
                customClass: {
                    popup: 'rounded-[28px] border border-[#D7F3F7] shadow-[0_24px_80px_rgba(10,25,47,0.22)]',
                    title: 'text-[#0A192F] text-2xl font-black',
                    htmlContainer: 'text-[#496173] text-sm',
                    confirmButton: 'h-11 px-5 rounded-full bg-[#00E5FF] text-[#0A192F] font-bold hover:bg-[#00B8CC] focus:outline-none',
                },
                buttonsStyling: false,
            });
        };

        window.formatCurrency = function(value) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value || 0);
        };

        window.apiFetch = async function(url, options = {}) {
            const headers = { ...(options.headers || {}), Authorization: `Bearer ${token}` };
            if (!(options.body instanceof FormData)) {
                headers['Content-Type'] = headers['Content-Type'] || 'application/json';
            }

            const response = await fetch(url, { ...options, headers });
            const raw = await response.text();
            let data = {};
            try {
                data = raw ? JSON.parse(raw) : {};
            } catch (_) {
                data = {};
            }

            if (response.status === 401) {
                localStorage.removeItem('auth_token');
                localStorage.removeItem('auth_user_name');
                localStorage.removeItem('auth_user_role');
                window.location.href = '/login';
                throw new Error('Sesi berakhir, silakan login ulang.');
            }

            if (!response.ok) {
                const message = data.message || `Permintaan gagal (HTTP ${response.status}).`;
                const error = new Error(message);
                error.status = response.status;
                error.errors = data.errors || {};
                error.raw = raw;
                throw error;
            }

            return data;
        };

        window.clearInlineErrors = function(formId) {
            document.querySelectorAll(`#${formId} [data-error-for]`).forEach((item) => {
                item.textContent = '';
                item.classList.add('hidden');
            });
        };

        window.applyInlineErrors = function(formId, errors = {}) {
            Object.keys(errors).forEach((key) => {
                const errorElement = document.querySelector(`#${formId} [data-error-for="${key}"]`);
                if (errorElement) {
                    errorElement.textContent = errors[key][0];
                    errorElement.classList.remove('hidden');
                }
            });
        };

        window.confirmAction = async function({
            title = 'Yakin lanjut?',
            text = 'Data yang sudah dihapus tidak bisa dikembalikan.',
            confirmText = 'Ya, hapus',
            cancelText = 'Batal',
            icon = 'warning',
        } = {}) {
            if (typeof Swal === 'undefined') {
                return confirm(`${title}\n${text}`);
            }

            const result = await Swal.fire({
                title,
                text,
                icon,
                showCancelButton: true,
                confirmButtonText: confirmText,
                cancelButtonText: cancelText,
                reverseButtons: true,
                focusCancel: true,
                background: '#FFFFFF',
                color: '#0A192F',
                customClass: {
                    popup: 'rounded-[28px] border border-[#D7F3F7] shadow-[0_24px_80px_rgba(10,25,47,0.18)]',
                    title: 'text-[#0A192F] text-2xl font-black',
                    htmlContainer: 'text-[#496173] text-sm',
                    confirmButton: 'h-11 px-5 rounded-full bg-[#DC2626] text-white font-bold hover:bg-[#B91C1C] focus:outline-none',
                    cancelButton: 'h-11 px-5 rounded-full border border-[#D7F3F7] bg-white text-[#0A192F] font-bold hover:bg-[#F7FEFF] focus:outline-none',
                },
                buttonsStyling: false,
            });

            return result.isConfirmed;
        };

        async function loadProfile() {
            try {
                const profile = await window.apiFetch('/api/profile');
                const user = profile?.user;

                if (!user) {
                    showToast('Sesi login tidak valid. Silakan login ulang.', 'error');
                    localStorage.removeItem('auth_token');
                    localStorage.removeItem('auth_user_name');
                    localStorage.removeItem('auth_user_role');
                    window.location.href = '/login';
                    return;
                }

                if (user.name) {
                    document.getElementById('userName').textContent = user.name;
                    localStorage.setItem('auth_user_name', user.name);
                }
                if (user.role) {
                    localStorage.setItem('auth_user_role', user.role);
                }

                if (user.role !== 'admin') {
                    showToast('Akun ini bukan admin.', 'error');
                    localStorage.removeItem('auth_token');
                    localStorage.removeItem('auth_user_name');
                    localStorage.removeItem('auth_user_role');
                    window.location.href = '/login';
                }
            } catch (error) {
                showToast(error.message, 'error');
            }
        }
        const logoutConfirmModal = document.getElementById('logoutConfirmModal');
        const confirmLogoutBtn = document.getElementById('confirmLogoutBtn');
        const cancelLogoutBtn = document.getElementById('cancelLogoutBtn');

        function openLogoutConfirm() {
            logoutConfirmModal.classList.remove('hidden');
            logoutConfirmModal.classList.add('flex');
        }

        function closeLogoutConfirm() {
            logoutConfirmModal.classList.add('hidden');
            logoutConfirmModal.classList.remove('flex');
        }

        document.getElementById('logoutBtn').addEventListener('click', openLogoutConfirm);

        cancelLogoutBtn.addEventListener('click', closeLogoutConfirm);
        logoutConfirmModal.addEventListener('click', (event) => {
            if (event.target.id === 'logoutConfirmModal') {
                closeLogoutConfirm();
            }
        });

        confirmLogoutBtn.addEventListener('click', async () => {
            closeLogoutConfirm();
            toggleLoading(true);
            try {
                await window.apiFetch('/api/logout', { method: 'POST' });
            } catch (_) {
                // Tetap hapus sesi lokal walau API logout gagal.
            } finally {
                localStorage.removeItem('auth_token');
                localStorage.removeItem('auth_user_name');
                localStorage.removeItem('auth_user_role');
                toggleLoading(false);
                window.location.href = '/login';
            }
        });

        loadProfile();
            </script>

            <div class="app-content-scroll flex-1 bg-[#F7FEFF] p-4 pb-24 sm:p-6 xl:p-6 xl:pb-6 2xl:p-8">
                @yield('content')
            </div>
        </div>

        <nav class="fixed inset-x-3 bottom-3 z-40 grid grid-cols-5 gap-1 rounded-[24px] border border-white/70 bg-[#0A192F] p-2 shadow-[0_18px_55px_rgba(10,25,47,0.24)] xl:hidden">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center gap-1 rounded-2xl px-2 py-2 text-[10px] font-bold {{ request()->routeIs('dashboard') ? 'bg-white text-[#0A192F]' : 'text-white/60' }}">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M4 10.5 12 4l8 6.5V20a1 1 0 0 1-1 1h-5v-6h-4v6H5a1 1 0 0 1-1-1z"/></svg>
                <span>Home</span>
            </a>
            <a href="{{ route('transactions.index') }}" class="flex flex-col items-center justify-center gap-1 rounded-2xl px-2 py-2 text-[10px] font-bold {{ request()->routeIs('transactions.index') ? 'bg-white text-[#0A192F]' : 'text-white/60' }}">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M7 4h10l2 4v12H5V8z"/><path d="M7 8h10"/><path d="M9 13h6"/><path d="M9 17h4"/></svg>
                <span>Order</span>
            </a>
            <a href="{{ route('customers.index') }}" class="flex flex-col items-center justify-center gap-1 rounded-2xl px-2 py-2 text-[10px] font-bold {{ request()->routeIs('customers.index') ? 'bg-white text-[#0A192F]' : 'text-white/60' }}">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"/><circle cx="9.5" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/></svg>
                <span>User</span>
            </a>
            <a href="{{ route('services.index') }}" class="flex flex-col items-center justify-center gap-1 rounded-2xl px-2 py-2 text-[10px] font-bold {{ request()->routeIs('services.index') ? 'bg-white text-[#0A192F]' : 'text-white/60' }}">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16"/><path d="M4 12h16"/><path d="M4 17h16"/><path d="M7 4v16"/><path d="M17 4v16"/></svg>
                <span>Jasa</span>
            </a>
            <a href="{{ route('reports.index') }}" class="flex flex-col items-center justify-center gap-1 rounded-2xl px-2 py-2 text-[10px] font-bold {{ request()->routeIs('reports.index') ? 'bg-white text-[#0A192F]' : 'text-white/60' }}">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19V5"/><path d="M4 19h16"/><path d="M8 16v-5"/><path d="M12 16V8"/><path d="M16 16v-3"/></svg>
                <span>Grafik</span>
            </a>
        </nav>
    </div>
</body>
</html>



