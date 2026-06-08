@extends('layouts.app')

@section('title', 'Laporan')
@section('page-title', 'Laporan & Statistik')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="space-y-6">
    <div class="bg-white rounded-[28px] border border-[#D7F3F7] shadow-[var(--wt-card-shadow)] p-6">
        <div class="flex flex-wrap items-end justify-between gap-5">
            <div class="min-w-0">
                <span class="inline-flex rounded-full bg-[#E6FCFF] px-3 py-1 text-xs font-semibold text-[#00B8CC]">Ringkasan</span>
                <h2 class="mt-3 text-2xl font-black text-[#0A192F]">Filter Laporan</h2>
                <p class="mt-1 text-sm text-[#496173]">Pantau transaksi dan pendapatan berdasarkan periode.</p>
            </div>
            <div class="flex flex-wrap items-end gap-3 rounded-2xl bg-[#F7FEFF] p-3">
                <div>
                    <label class="block text-sm font-medium text-[#496173] mb-1">Mode</label>
                    <select id="periodMode" class="h-10 border border-[#D7F3F7] rounded-full bg-white px-4 text-sm">
                        <option value="monthly">Bulanan</option>
                        <option value="daily">Harian</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#496173] mb-1">Tanggal</label>
                    <input id="reportDate" type="date" class="h-10 border border-[#D7F3F7] rounded-full bg-white px-4 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#496173] mb-1">Bulan</label>
                    <select id="month" class="h-10 border border-[#D7F3F7] rounded-full bg-white px-4 text-sm">
                        @php
                            $monthNames = [
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                                4 => 'April', 5 => 'Mei', 6 => 'Juni',
                                7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                                10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                            ];
                        @endphp
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}">{{ $monthNames[$i] }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#496173] mb-1">Tahun</label>
                    <input id="year" type="number" min="2000" max="2100" required class="h-10 border border-[#D7F3F7] rounded-full bg-white px-4 text-sm w-32" value="{{ now()->year }}">
                </div>
                <button id="loadReport" class="h-10 inline-flex items-center justify-center leading-none bg-[#00E5FF] hover:bg-[#00B8CC] disabled:opacity-50 disabled:cursor-not-allowed text-[#0A192F] px-5 rounded-full text-sm font-semibold transition">Muat Laporan</button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-[#0A192F] rounded-[28px] border border-[#00E5FF] shadow-[0_18px_46px_rgba(10,25,47,0.16)] p-6 text-white">
            <p class="text-sm font-semibold text-white/70">Total Pendapatan</p>
            <h2 id="totalIncome" class="text-3xl font-black mt-4">Rp 0</h2>
            <p class="mt-4 rounded-full bg-white/10 px-3 py-2 text-xs font-semibold text-white/80">Dihitung dari transaksi lunas.</p>
        </div>
        <div class="bg-white rounded-[28px] border border-[#D7F3F7] shadow-[var(--wt-card-shadow)] p-6">
            <p class="text-sm font-semibold text-[#496173]">Total Transaksi</p>
            <div class="mt-4 flex items-end justify-between gap-4">
                <h2 id="totalTransactions" class="text-3xl font-black text-[#0A192F]">0</h2>
                <span class="rounded-full bg-[#E6FCFF] px-3 py-1 text-xs font-semibold text-[#00B8CC]">Periode</span>
            </div>
        </div>
        <div class="bg-white rounded-[28px] border border-[#D7F3F7] shadow-[var(--wt-card-shadow)] p-6">
            <p class="text-sm font-semibold text-[#496173]">Transaksi Hari Ini</p>
            <div class="mt-4 flex items-end justify-between gap-4">
                <h2 id="transactionsToday" class="text-3xl font-black text-[#0A192F]">0</h2>
                <span class="rounded-full bg-[#F2FDFF] px-3 py-1 text-xs font-semibold text-[#496173]">Live</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6">
        <div class="bg-white rounded-[28px] border border-[#D7F3F7] shadow-[var(--wt-card-shadow)] overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-[#D7F3F7]">
                <h3 id="dailyChartTitle" class="text-lg font-bold text-[#0A192F]">Statistik Harian</h3>
                <p id="dailyChartDescription" class="mt-1 text-sm text-[#496173]">Tren pendapatan & transaksi per hari di bulan ini.</p>
            </div>
            <div class="p-5 flex-1 relative min-h-[300px]">
                <canvas id="dailyChart"></canvas>
            </div>
        </div>
        
        <div class="bg-white rounded-[28px] border border-[#D7F3F7] shadow-[var(--wt-card-shadow)] overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-[#D7F3F7]">
                <h3 class="text-lg font-bold text-[#0A192F]">Statistik Bulanan</h3>
                <p class="mt-1 text-sm text-[#496173]">Perbandingan total bulanan di tahun ini.</p>
            </div>
            <div class="p-5 flex-1 relative min-h-[300px]">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[28px] border border-[#D7F3F7] shadow-[var(--wt-card-shadow)] overflow-hidden">
        <div class="px-6 py-5 border-b border-[#D7F3F7] flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h3 class="text-lg font-bold text-[#0A192F]">Rincian Harian</h3>
                <p class="mt-1 text-sm text-[#496173]">Semua tanggal dalam periode terpilih, lengkap dengan pendapatan lunas dan jumlah transaksi.</p>
            </div>
            <span id="selectedDayBadge" class="inline-flex w-fit rounded-full bg-[#E6FCFF] px-3 py-1 text-xs font-semibold text-[#00B8CC]">Harian</span>
        </div>
        <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-3">
            <div class="rounded-2xl bg-[#0A192F] p-4 text-white">
                <p class="text-xs font-bold uppercase tracking-wide text-white/60">Tanggal Dipilih</p>
                <h4 id="selectedDayLabel" class="mt-3 text-xl font-black">-</h4>
            </div>
            <div class="rounded-2xl border border-[#D7F3F7] bg-[#F7FEFF] p-4">
                <p class="text-xs font-bold uppercase tracking-wide text-[#496173]">Transaksi Hari Itu</p>
                <h4 id="selectedDayTransactions" class="mt-3 text-2xl font-black text-[#0A192F]">0</h4>
            </div>
            <div class="rounded-2xl border border-[#D7F3F7] bg-[#F7FEFF] p-4">
                <p class="text-xs font-bold uppercase tracking-wide text-[#496173]">Pendapatan Lunas</p>
                <h4 id="selectedDayIncome" class="mt-3 text-2xl font-black text-[#00B8CC]">Rp 0</h4>
            </div>
        </div>
        <div class="overflow-x-auto border-t border-[#D7F3F7]">
            <table class="min-w-full text-sm">
                <thead class="bg-[#F7FEFF] text-[#496173]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wide">Tanggal</th>
                        <th class="px-6 py-3 text-right text-xs font-bold uppercase tracking-wide">Transaksi</th>
                        <th class="px-6 py-3 text-right text-xs font-bold uppercase tracking-wide">Pendapatan Lunas</th>
                    </tr>
                </thead>
                <tbody id="dailyDetailBody" class="divide-y divide-[#D7F3F7]"></tbody>
            </table>
        </div>
    </div>
</div>
<script>
    document.getElementById('month').value = String(new Date().getMonth() + 1);
    document.getElementById('reportDate').value = new Date().toISOString().slice(0, 10);
    const monthNames = {
        1: 'Jan', 2: 'Feb', 3: 'Mar', 4: 'Apr', 5: 'Mei', 6: 'Jun',
        7: 'Jul', 8: 'Ags', 9: 'Sep', 10: 'Okt', 11: 'Nov', 12: 'Des',
    };

    // Simpan chart aktif agar bisa diganti saat filter berubah.
    let dailyChartInstance = null;
    let monthlyChartInstance = null;

    // Tema dasar Chart.js mengikuti warna Wasy.
    Chart.defaults.font.family = "'Inter', system-ui, sans-serif";
    Chart.defaults.color = '#496173';


    function formatDateLabel(dateValue) {
        const date = new Date(dateValue);
        return Number.isNaN(date.getTime())
            ? dateValue
            : date.toLocaleDateString('id-ID', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' });
    }

    function syncDateFilterToMonthYear() {
        const dateValue = document.getElementById('reportDate').value;
        const date = new Date(dateValue);
        if (Number.isNaN(date.getTime())) return;

        document.getElementById('month').value = String(date.getMonth() + 1);
        document.getElementById('year').value = String(date.getFullYear());
    }

    function renderDailyDetails(items = [], selectedDate, selectedDaySummary = {}) {
        document.getElementById('selectedDayLabel').textContent = formatDateLabel(selectedDate);
        document.getElementById('selectedDayTransactions').textContent = selectedDaySummary.total_transactions ?? 0;
        document.getElementById('selectedDayIncome').textContent = typeof window.formatCurrency === 'function'
            ? window.formatCurrency(selectedDaySummary.total_income || 0)
            : `Rp ${new Intl.NumberFormat('id-ID').format(selectedDaySummary.total_income || 0)}`;
        document.getElementById('selectedDayBadge').textContent = `Dipilih: ${formatDateLabel(selectedDate)}`;

        const body = document.getElementById('dailyDetailBody');
        if (!items.length) {
            body.innerHTML = '<tr><td colspan="3" class="px-6 py-8 text-center font-semibold text-[#496173]">Belum ada data harian.</td></tr>';
            return;
        }

        body.innerHTML = items.map((item) => {
            const isSelected = item.date === selectedDate;
            const income = typeof window.formatCurrency === 'function'
                ? window.formatCurrency(item.paid_income || 0)
                : `Rp ${new Intl.NumberFormat('id-ID').format(item.paid_income || 0)}`;
            return `
                <tr class="${isSelected ? 'bg-[#E6FCFF]' : 'bg-white'}">
                    <td class="px-6 py-3 font-bold text-[#0A192F]">${formatDateLabel(item.date)}</td>
                    <td class="px-6 py-3 text-right font-black text-[#0A192F]">${item.total_transactions ?? 0}</td>
                    <td class="px-6 py-3 text-right font-black text-[#00B8CC]">${income}</td>
                </tr>
            `;
        }).join('');
    }
    function createChartConfig(labels, incomeData, txData) {
        return {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Pendapatan Lunas',
                        data: incomeData,
                        borderColor: '#00E5FF',
                        backgroundColor: 'rgba(0, 229, 255, 0.1)',
                        borderWidth: 3,
                        pointBackgroundColor: '#FFFFFF',
                        pointBorderColor: '#00B8CC',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Jumlah Transaksi',
                        data: txData,
                        borderColor: '#0A192F',
                        borderWidth: 3,
                        borderDash: [5, 5],
                        pointBackgroundColor: '#FFFFFF',
                        pointBorderColor: '#0A192F',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.4,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0A192F',
                        titleColor: '#00E5FF',
                        bodyColor: '#FFFFFF',
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.dataset.yAxisID === 'y') {
                                    if(typeof window.formatCurrency === 'function') {
                                        label += window.formatCurrency(context.raw);
                                    } else {
                                        label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                                    }
                                } else {
                                    label += context.raw + ' transaksi';
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false,
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Pendapatan (Rp)',
                            color: '#00B8CC',
                            font: { weight: 'bold' }
                        },
                        grid: {
                            color: '#D7F3F7',
                            borderDash: [5, 5]
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Total Transaksi',
                            color: '#0A192F',
                            font: { weight: 'bold' }
                        },
                        // Hindari grid dobel di area chart.
                        grid: {
                            drawOnChartArea: false,
                        },
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        };
    }

    async function loadReport() {
        const loadReportButton = document.getElementById('loadReport');
        const yearInput = document.getElementById('year');
        const periodMode = document.getElementById('periodMode').value;

        if (periodMode === 'daily') {
            syncDateFilterToMonthYear();
        }

        const year = Number(yearInput.value.trim());

        if (!Number.isInteger(year) || year < 2000 || year > 2100) {
            if(typeof showToast === 'function') showToast('Tahun laporan harus di antara 2000 sampai 2100.', 'error');
            yearInput.focus();
            return;
        }

        if (typeof window.toggleLoading === 'function') window.toggleLoading(true);
        loadReportButton.disabled = true;
        loadReportButton.textContent = 'Memuat...';
        
        try {
            const month = document.getElementById('month').value;
            const selectedDate = document.getElementById('reportDate').value;
            const report = await window.apiFetch(`/api/reports/stats?month=${month}&year=${year}&date=${selectedDate}`);

            const globalSummary = report?.summary ?? {};
            const periodSummary = report?.period_summary ?? globalSummary;
            const selectedDaySummary = report?.selected_day_summary ?? {};
            const transactionsByDay = report?.transactions_by_day ?? [];
            const transactionsByHour = report?.transactions_by_hour ?? [];
            const transactionsByMonth = report?.transactions_by_month ?? [];
            const activeSummary = periodMode === 'daily' ? selectedDaySummary : periodSummary;

            if(typeof window.formatCurrency === 'function') {
                document.getElementById('totalIncome').textContent = window.formatCurrency(activeSummary.total_income || 0);
            }
            document.getElementById('totalTransactions').textContent = activeSummary.total_transactions || 0;
            document.getElementById('transactionsToday').textContent = selectedDaySummary.total_transactions ?? globalSummary.transactions_today ?? 0;
            document.getElementById('dailyChartTitle').textContent = periodMode === 'daily'
                ? 'Statistik Per Jam'
                : 'Statistik Harian';
            document.getElementById('dailyChartDescription').textContent = periodMode === 'daily'
                ? `Tanggal aktif: ${formatDateLabel(selectedDate)}. Grafik menampilkan transaksi per jam pada hari itu.`
                : 'Tren pendapatan & transaksi per hari di bulan ini.';

            const dailyChartItems = periodMode === 'daily' ? transactionsByHour : transactionsByDay;
            const dailyLabels = dailyChartItems.map(item => {
                if (periodMode === 'daily') return item.label ?? `${item.hour}:00`;

                const dateParts = item.date.split('-');
                return dateParts.length === 3 ? dateParts[2] : item.date;
            });
            const dailyIncome = dailyChartItems.map(item => item.paid_income);
            const dailyTx = dailyChartItems.map(item => item.total_transactions);

            if (dailyChartInstance) dailyChartInstance.destroy();
            const ctxDaily = document.getElementById('dailyChart').getContext('2d');
            dailyChartInstance = new Chart(ctxDaily, createChartConfig(dailyLabels, dailyIncome, dailyTx));

            const monthlyLabels = transactionsByMonth.map(item => monthNames[item.month] ?? item.month);
            const monthlyIncome = transactionsByMonth.map(item => item.paid_income);
            const monthlyTx = transactionsByMonth.map(item => item.total_transactions);

            if (monthlyChartInstance) monthlyChartInstance.destroy();
            const ctxMonthly = document.getElementById('monthlyChart').getContext('2d');
            monthlyChartInstance = new Chart(ctxMonthly, createChartConfig(monthlyLabels, monthlyIncome, monthlyTx));

            renderDailyDetails(transactionsByDay, selectedDate, selectedDaySummary);
        } catch (error) {
            if(typeof showToast === 'function') showToast(error.message, 'error');
        } finally {
            loadReportButton.disabled = false;
            loadReportButton.textContent = 'Muat Laporan';
            if (typeof window.toggleLoading === 'function') window.toggleLoading(false);
        }
    }
    document.getElementById('periodMode').addEventListener('change', () => {
        if (document.getElementById('periodMode').value === 'daily') syncDateFilterToMonthYear();
        loadReport();
    });
    document.getElementById('reportDate').addEventListener('change', () => {
        syncDateFilterToMonthYear();
        if (document.getElementById('periodMode').value === 'daily') loadReport();
    });
    document.getElementById('loadReport').addEventListener('click', loadReport);

    loadReport();
</script>
@endsection






