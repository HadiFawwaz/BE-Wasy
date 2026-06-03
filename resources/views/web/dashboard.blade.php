@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-5 sm:space-y-6">
    <div class="rounded-[24px] border border-[#D7F3F7] bg-white shadow-[var(--wt-card-shadow)] p-4 sm:rounded-[28px] sm:p-6">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
            <div class="min-w-0">
                <p id="dashboardDate" class="text-sm font-semibold text-[#496173]">Memuat tanggal...</p>
                <h2 class="mt-2 text-2xl font-black tracking-tight text-[#0A192F] sm:text-3xl">Ringkasan Operasional</h2>
                <p class="mt-1 text-sm text-[#496173]">Pantau pembayaran, antrean cucian, dan transaksi terbaru dari satu tempat.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('transactions.index') }}" class="inline-flex h-10 flex-1 items-center justify-center rounded-lg bg-[#00E5FF] px-4 text-sm font-semibold text-[#0A192F] shadow-[0_10px_22px_rgba(0,229,255,0.22)] hover:bg-[#00B8CC] sm:flex-none">Buat Transaksi</a>
                <a href="{{ route('reports.index') }}" class="inline-flex h-10 flex-1 items-center justify-center rounded-lg border border-[#D7F3F7] bg-white px-4 text-sm font-semibold text-[#0A192F] hover:bg-[#E6FCFF] sm:flex-none">Lihat Laporan</a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 xl:grid-cols-3 2xl:grid-cols-4 2xl:gap-5">
        <div class="min-w-0 rounded-[24px] border border-[#00E5FF] bg-[#0A192F] p-5 text-white shadow-[0_22px_60px_rgba(10,25,47,0.20)] xl:col-span-1 sm:rounded-[28px] 2xl:p-6">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-white/70">Pendapatan Lunas</p>
                    <h3 id="totalIncome" class="mt-3 break-words text-[clamp(1.55rem,2.2vw,2rem)] font-black leading-tight">Rp 0</h3>
                </div>
                <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white/12 text-base font-bold 2xl:h-10 2xl:w-10 2xl:text-lg">Rp</span>
            </div>
            <div class="mt-5 grid grid-cols-2 gap-3">
                <div class="min-w-0 rounded-xl bg-white/10 px-3 py-2">
                    <p class="text-[11px] font-medium text-white/60">Transaksi lunas</p>
                    <p id="paidIncomeCount" class="mt-1 text-lg font-black">0</p>
                </div>
                <div class="min-w-0 rounded-xl bg-white/10 px-3 py-2">
                    <p class="text-[11px] font-medium text-white/60">Rata-rata</p>
                    <p id="averagePaidIncome" class="mt-1 break-words text-sm font-black">Rp 0</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:col-span-2 2xl:col-span-3 2xl:grid-cols-3 2xl:gap-5">
            <div class="rounded-[24px] border border-[#D7F3F7] bg-white p-4 shadow-[var(--wt-card-shadow)] sm:rounded-[28px] 2xl:p-5">
                <p class="text-sm font-semibold text-[#496173]">Total Transaksi</p>
                <div class="mt-3 flex items-end justify-between gap-4">
                    <h3 id="totalTransactions" class="text-3xl font-black text-[#0A192F]">0</h3>
                    <span class="rounded-full bg-[#E6FCFF] px-3 py-1 text-xs font-semibold text-[#00B8CC]">Semua</span>
                </div>
            </div>
            <div class="rounded-[24px] border border-[#D7F3F7] bg-white p-4 shadow-[var(--wt-card-shadow)] sm:rounded-[28px] 2xl:p-5">
                <p class="text-sm font-semibold text-[#496173]">Hari Ini</p>
                <div class="mt-3 flex items-end justify-between gap-4">
                    <h3 id="transactionsToday" class="text-3xl font-black text-[#0A192F]">0</h3>
                    <span class="rounded-full bg-[#E6FCFF] px-3 py-1 text-xs font-semibold text-[#00B8CC]">Masuk</span>
                </div>
            </div>
            <div class="rounded-[24px] border border-[#D7F3F7] bg-white p-4 shadow-[var(--wt-card-shadow)] sm:rounded-[28px] 2xl:p-5">
                <p class="text-sm font-semibold text-[#496173]">Siap Diambil</p>
                <div class="mt-3 flex items-end justify-between gap-4">
                    <h3 id="readyTransactions" class="text-3xl font-black text-[#00B8CC]">0</h3>
                    <span class="rounded-full bg-[#E6FCFF] px-3 py-1 text-xs font-semibold text-[#00B8CC]">Prioritas</span>
                </div>
            </div>
            <div class="rounded-[24px] border border-[#D7F3F7] bg-white p-4 shadow-[var(--wt-card-shadow)] sm:rounded-[28px] 2xl:p-5">
                <p class="text-sm font-semibold text-[#496173]">Belum Lunas</p>
                <div class="mt-3 flex items-end justify-between gap-4">
                    <h3 id="unpaidTransactions" class="text-3xl font-black text-[#B45309]">0</h3>
                    <span class="rounded-full bg-[#FFF7ED] px-3 py-1 text-xs font-semibold text-[#B45309]">Tagih</span>
                </div>
            </div>
            <div class="rounded-[24px] border border-[#D7F3F7] bg-white p-4 shadow-[var(--wt-card-shadow)] sm:rounded-[28px] 2xl:p-5">
                <p class="text-sm font-semibold text-[#496173]">Pembayaran Lunas</p>
                <div class="mt-3 flex items-end justify-between gap-4">
                    <h3 id="paidTransactions" class="text-3xl font-black text-[#047857]">0</h3>
                    <span class="rounded-full bg-[#ECFDF3] px-3 py-1 text-xs font-semibold text-[#047857]">Paid</span>
                </div>
            </div>
            <div class="rounded-[24px] border border-[#D7F3F7] bg-white p-4 shadow-[var(--wt-card-shadow)] sm:rounded-[28px] 2xl:p-5">
                <p class="text-sm font-semibold text-[#496173]">Fokus Admin</p>
                <div class="mt-3 space-y-2 text-sm">
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-[#496173]">Follow up bayar</span>
                        <strong id="focusUnpaid" class="text-[#0A192F]">0</strong>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-[#496173]">Kirim pickup WA</span>
                        <strong id="focusReady" class="text-[#0A192F]">0</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 2xl:grid-cols-3 2xl:gap-6">
        <div class="rounded-[24px] border border-[#D7F3F7] bg-white shadow-[var(--wt-card-shadow)] overflow-hidden sm:rounded-[28px] 2xl:col-span-2">
            <div class="flex items-center justify-between gap-4 border-b border-[#D7F3F7] px-4 py-4 sm:px-6">
                <div class="min-w-0">
                    <h3 class="text-lg font-bold text-[#0A192F]">Aktivitas Harian</h3>
                    <p class="text-sm text-[#496173]">Jumlah transaksi dalam 14 hari terakhir.</p>
                </div>
                <span class="shrink-0 rounded-full bg-[#E6FCFF] px-3 py-1 text-xs font-semibold text-[#00B8CC]">14 hari</span>
            </div>
            <div id="dailyChart" class="min-h-[18rem] px-4 py-5 sm:px-6"></div>
        </div>

        <div class="rounded-[24px] border border-[#D7F3F7] bg-white p-5 shadow-[var(--wt-card-shadow)] sm:rounded-[28px] sm:p-6">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-[#0A192F]">Progress Pembayaran</h3>
                    <p class="text-sm text-[#496173]">Rasio transaksi lunas.</p>
                </div>
            </div>
            <div class="mt-6 flex flex-col items-center sm:mt-7">
                <div id="paymentProgressRing" class="grid h-36 w-36 place-items-center rounded-full sm:h-44 sm:w-44" style="background: conic-gradient(#00E5FF 0deg, #E6FCFF 0deg);">
                    <div class="grid h-24 w-24 place-items-center rounded-full bg-white shadow-inner sm:h-32 sm:w-32">
                        <div class="text-center">
                            <p id="paymentProgressValue" class="text-3xl font-black text-[#0A192F] sm:text-4xl">0%</p>
                            <p class="mt-1 text-xs font-semibold text-[#496173]">Lunas</p>
                        </div>
                    </div>
                </div>
                <p id="paymentProgressCaption" class="mt-5 text-center text-sm text-[#496173]">Belum ada transaksi.</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 2xl:grid-cols-3 2xl:gap-6">
        <div class="rounded-[24px] border border-[#D7F3F7] bg-white shadow-[var(--wt-card-shadow)] overflow-hidden sm:rounded-[28px] 2xl:col-span-2">
            <div class="flex items-center justify-between gap-4 border-b border-[#D7F3F7] px-4 py-4 sm:px-6">
                <div class="min-w-0">
                    <h3 class="text-lg font-bold text-[#0A192F]">Transaksi Terbaru</h3>
                    <p class="text-sm text-[#496173]">Aktivitas terbaru yang perlu dipantau admin.</p>
                </div>
                <a href="{{ route('transactions.index') }}" class="shrink-0 text-sm font-semibold text-[#00B8CC] hover:text-[#0A192F]">Kelola</a>
            </div>
            <div id="recentTransactionList" class="divide-y divide-[#D7F3F7]"></div>
        </div>

        <div class="rounded-[24px] border border-[#D7F3F7] bg-white shadow-[var(--wt-card-shadow)] overflow-hidden sm:rounded-[28px]">
            <div class="border-b border-[#D7F3F7] px-4 py-4 sm:px-6">
                <h3 class="text-lg font-bold text-[#0A192F]">Prioritas Hari Ini</h3>
                <p class="text-sm text-[#496173]">Aksi cepat untuk operasional.</p>
            </div>
            <div class="p-5 space-y-3">
                <a href="{{ route('transactions.index') }}" class="flex items-center justify-between rounded-xl border border-[#D7F3F7] bg-[#F7FEFF] px-4 py-3 hover:bg-[#E6FCFF]">
                    <span>
                        <span class="block text-sm font-bold text-[#0A192F]">Tagih pembayaran pending</span>
                        <span class="text-xs text-[#496173]">Cek bukti dan tandai lunas</span>
                    </span>
                    <span id="priorityUnpaid" class="text-lg font-black text-[#B45309]">0</span>
                </a>
                <a href="{{ route('transactions.index') }}" class="flex items-center justify-between rounded-xl border border-[#D7F3F7] bg-[#F7FEFF] px-4 py-3 hover:bg-[#E6FCFF]">
                    <span>
                        <span class="block text-sm font-bold text-[#0A192F]">Hubungi siap diambil</span>
                        <span class="text-xs text-[#496173]">Kirim update WhatsApp</span>
                    </span>
                    <span id="priorityReady" class="text-lg font-black text-[#00B8CC]">0</span>
                </a>
                <a href="{{ route('services.index') }}" class="flex items-center justify-between rounded-xl border border-[#D7F3F7] bg-[#F7FEFF] px-4 py-3 hover:bg-[#E6FCFF]">
                    <span>
                        <span class="block text-sm font-bold text-[#0A192F]">Cek layanan & harga</span>
                        <span class="text-xs text-[#496173]">Pastikan satuan sudah sesuai</span>
                    </span>
                    <span class="text-sm font-black text-[#00B8CC]">Buka</span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function renderStatusBadge(status) {
        const map = {
            'antrian': { bg: 'rgba(251,176,59,0.2)', color: '#C2410C' },
            'dicuci': { bg: 'rgba(0,229,255,0.14)', color: '#00B8CC' },
            'disetrika': { bg: 'rgba(181,32,130,0.12)', color: '#8F1667' },
            'siap diambil': { bg: 'rgba(0,229,255,0.14)', color: '#00B8CC' },
            'diambil': { bg: 'rgba(93,43,138,0.12)', color: '#4A1F6F' },
        };

        const palette = map[status] ?? { bg: '#F3F4F6', color: '#4B5563' };
        return `<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold" style="background:${palette.bg}; color:${palette.color};">${status ?? '-'}</span>`;
    }

    function renderPaymentBadge(paymentStatus) {
        const isPaid = paymentStatus === 'paid';
        return `<span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ${isPaid ? 'bg-[#ECFDF3] text-[#047857]' : 'bg-[#FFF7ED] text-[#B45309]'}">${isPaid ? 'lunas' : 'pending'}</span>`;
    }

    function setDashboardDate() {
        document.getElementById('dashboardDate').textContent = new Date().toLocaleDateString('id-ID', {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    }

    function renderDailyChart(items = []) {
        const wrapper = document.getElementById('dailyChart');
        const recentItems = items.slice(-14);

        if (!recentItems.length) {
            wrapper.innerHTML = '<div class="flex h-64 items-center justify-center text-sm font-semibold text-[#496173]">Belum ada data aktivitas.</div>';
            return;
        }

        const width = 920;
        const height = 230;
        const paddingX = 36;
        const paddingY = 28;
        const chartWidth = width - paddingX * 2;
        const chartHeight = height - paddingY * 2;
        const maxValue = Math.max(...recentItems.map((item) => Number(item.total_transactions || 0)), 1);
        const points = recentItems.map((item, index) => {
            const x = paddingX + (recentItems.length === 1 ? chartWidth / 2 : (index / (recentItems.length - 1)) * chartWidth);
            const value = Number(item.total_transactions || 0);
            const y = paddingY + chartHeight - ((value / maxValue) * chartHeight);
            return { x, y, value, item };
        });

        const linePath = points.map((point, index) => {
            if (index === 0) return `M ${point.x} ${point.y}`;
            const previous = points[index - 1];
            const controlX = (previous.x + point.x) / 2;
            return `C ${controlX} ${previous.y}, ${controlX} ${point.y}, ${point.x} ${point.y}`;
        }).join(' ');
        const areaPath = `${linePath} L ${points[points.length - 1].x} ${height - paddingY} L ${points[0].x} ${height - paddingY} Z`;
        const gridLines = [0, 1, 2, 3].map((step) => {
            const y = paddingY + (step / 3) * chartHeight;
            return `<line x1="${paddingX}" y1="${y}" x2="${width - paddingX}" y2="${y}" stroke="#E6FCFF" stroke-width="1" />`;
        }).join('');
        const labels = points.map((point) => {
            const date = new Date(point.item.date);
            const label = Number.isNaN(date.getTime())
                ? point.item.date
                : date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
            return `<span class="min-w-[54px] text-center text-[11px] font-bold text-[#496173]">${label}</span>`;
        }).join('');
        const dots = points.map((point) => `
            <g>
                <circle cx="${point.x}" cy="${point.y}" r="5" fill="#FFFFFF" stroke="#00B8CC" stroke-width="3" />
                <text x="${point.x}" y="${point.y - 13}" text-anchor="middle" fill="#0A192F" font-size="12" font-weight="800">${point.value}</text>
            </g>
        `).join('');

        wrapper.innerHTML = `
            <div class="w-full overflow-x-auto">
                <div class="min-w-[720px]">
                    <svg viewBox="0 0 ${width} ${height}" class="h-64 w-full" role="img" aria-label="Grafik gelombang transaksi 14 hari">
                        <defs>
                            <linearGradient id="dailyWaveFill" x1="0" x2="0" y1="0" y2="1">
                                <stop offset="0%" stop-color="#00E5FF" stop-opacity="0.28" />
                                <stop offset="100%" stop-color="#00E5FF" stop-opacity="0.02" />
                            </linearGradient>
                        </defs>
                        ${gridLines}
                        <path d="${areaPath}" fill="url(#dailyWaveFill)" />
                        <path d="${linePath}" fill="none" stroke="#00B8CC" stroke-width="5" stroke-linecap="round" />
                        ${dots}
                    </svg>
                    <div class="grid grid-flow-col auto-cols-fr gap-2 px-6 pb-1">${labels}</div>
                </div>
            </div>
        `;
    }
    function renderRecentTransactions(items = []) {
        const wrapper = document.getElementById('recentTransactionList');

        if (!items.length) {
            wrapper.innerHTML = '<div class="px-6 py-8 text-center text-sm text-[#496173]">Belum ada transaksi.</div>';
            return;
        }

        wrapper.innerHTML = items.map((item) => `
            <div class="flex flex-col gap-3 px-6 py-4 md:flex-row md:items-center md:justify-between">
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <p class="font-bold text-[#0A192F]">${item.invoice_code ?? '-'}</p>
                        ${renderStatusBadge(item.status)}
                        ${renderPaymentBadge(item.payment_status)}
                    </div>
                    <p class="mt-1 truncate text-sm text-[#496173]" title="${item.customer?.user?.name ?? '-'}">${item.customer?.user?.name ?? '-'} - ${item.service?.service_name ?? '-'}</p>
                </div>
                <div class="text-left md:text-right">
                    <p class="font-black text-[#0A192F]">${window.formatCurrency(item.total_price)}</p>
                    <p class="text-xs text-[#496173]">${new Date(item.created_at).toLocaleString('id-ID')}</p>
                </div>
            </div>
        `).join('');
    }

    function updatePaymentProgress(paidCount, totalCount) {
        const percent = totalCount > 0 ? Math.round((paidCount / totalCount) * 100) : 0;
        const degrees = Math.round((percent / 100) * 360);

        document.getElementById('paymentProgressRing').style.background = `conic-gradient(#00E5FF ${degrees}deg, #E6FCFF ${degrees}deg)`;
        document.getElementById('paymentProgressValue').textContent = `${percent}%`;
        document.getElementById('paymentProgressCaption').textContent = totalCount > 0
            ? `${paidCount} dari ${totalCount} transaksi sudah lunas.`
            : 'Belum ada transaksi.';
    }

    async function loadDashboardData() {
        toggleLoading(true);
        try {
            const [stats, transactions, unpaid, ready, paid] = await Promise.all([
                window.apiFetch('/api/reports/stats'),
                window.apiFetch('/api/transactions?per_page=6'),
                window.apiFetch('/api/transactions?per_page=1&payment_status=pending'),
                window.apiFetch('/api/transactions?per_page=1&status=siap%20diambil'),
                window.apiFetch('/api/transactions?per_page=1&payment_status=paid')
            ]);

            const summary = stats?.summary ?? {};
            const totalTransactions = Number(summary.total_transactions || 0);
            const paidTotal = Number(paid?.total || 0);
            const unpaidTotal = Number(unpaid?.total || 0);
            const readyTotal = Number(ready?.total || 0);

            document.getElementById('totalIncome').textContent = window.formatCurrency(summary.total_income || 0);
            document.getElementById('totalTransactions').textContent = totalTransactions;
            document.getElementById('transactionsToday').textContent = summary.transactions_today || 0;
            document.getElementById('unpaidTransactions').textContent = unpaidTotal;
            document.getElementById('readyTransactions').textContent = readyTotal;
            document.getElementById('paidTransactions').textContent = paidTotal;
            document.getElementById('paidIncomeCount').textContent = paidTotal;
            document.getElementById('averagePaidIncome').textContent = window.formatCurrency(paidTotal > 0 ? Number(summary.total_income || 0) / paidTotal : 0);
            document.getElementById('focusUnpaid').textContent = unpaidTotal;
            document.getElementById('focusReady').textContent = readyTotal;
            document.getElementById('priorityUnpaid').textContent = unpaidTotal;
            document.getElementById('priorityReady').textContent = readyTotal;

            renderDailyChart(stats?.transactions_by_week ?? stats?.transactions_by_day ?? []);
            renderRecentTransactions(transactions.data ?? []);
            updatePaymentProgress(paidTotal, totalTransactions);
        } catch (error) {
            showToast(error.message, 'error');
        } finally {
            toggleLoading(false);
        }
    }

    setDashboardDate();
    loadDashboardData();
</script>
@endsection



