@extends('layouts.app')

@section('title', 'Transaksi')
@section('page-title', 'Manajemen Transaksi')

@section('content')
<div class="space-y-5 sm:space-y-6">
    <div class="grid grid-cols-1 gap-5 2xl:grid-cols-4 2xl:gap-6">
        <div class="bg-white rounded-[24px] border border-[#D7F3F7] shadow-[var(--wt-card-shadow)] p-4 sm:rounded-[28px] sm:p-6 2xl:col-span-3">
            <div class="mb-5 flex items-start justify-between gap-4">
                <div>
                    <span class="inline-flex rounded-full bg-[#E6FCFF] px-3 py-1 text-xs font-semibold text-[#00B8CC]">Workflow Admin</span>
                    <h2 class="mt-3 text-2xl font-black text-[#0A192F]">Buat Transaksi Baru</h2>
                    <p class="mt-1 text-sm text-[#496173]">Pilih pelanggan dan layanan, total otomatis dihitung dari harga unit.</p>
                </div>
                <span class="hidden h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#0A192F] text-sm font-black text-white sm:inline-flex">+</span>
            </div>
            <form id="transactionForm" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div>
                    <label class="block text-sm font-medium text-[#496173] mb-1">Pelanggan</label>
                    <select id="customerId" required class="w-full h-10 border border-[#D7F3F7] rounded-lg px-3 text-sm">
                        <option value="">Pilih pelanggan</option>
                    </select>
                    <p data-error-for="customer_id" class="hidden text-sm text-red-600 mt-1"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#496173] mb-1">Layanan</label>
                    <select id="serviceId" required class="w-full h-10 border border-[#D7F3F7] rounded-lg px-3 text-sm">
                        <option value="">Pilih layanan</option>
                    </select>
                    <p data-error-for="service_id" class="hidden text-sm text-red-600 mt-1"></p>
                </div>
                <div>
                    <label id="weightLabel" class="block text-sm font-medium text-[#496173] mb-1">Berat/Jumlah</label>
                    <input id="weight" type="number" min="1" step="0.1" required class="w-full h-10 border border-[#D7F3F7] rounded-lg px-3 text-sm" placeholder="Kg/Pcs">
                    <p data-error-for="weight" class="hidden text-sm text-red-600 mt-1"></p>
                </div>

                <div class="sm:col-span-2 lg:col-span-3 rounded-2xl border border-[#D7F3F7] bg-[#F7FEFF] p-3">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm font-bold text-[#0A192F]">Layanan tambahan</p>
                            <p class="mt-1 text-xs text-[#496173]">Gunakan kalau satu transaksi berisi lebih dari satu jenis layanan.</p>
                        </div>
                        <button id="addServiceItemBtn" type="button" class="h-9 rounded-full border border-[#00E5FF] bg-white px-4 text-xs font-bold text-[#00B8CC] transition hover:bg-[#E6FCFF]">Tambah Layanan</button>
                    </div>
                    <div id="extraServiceItems" class="mt-3 space-y-3"></div>
                    <p data-error-for="items" class="hidden text-sm text-red-600 mt-2"></p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#496173] mb-1">Harga per Unit</label>
                    <div id="serviceUnitPrice" class="h-10 rounded-lg px-3 text-[#6B7280] bg-[#F3F4F6] border border-transparent flex items-center text-sm cursor-not-allowed select-none">Rp 0</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#496173] mb-1">Metode Pembayaran</label>
                    <select id="paymentMethod" required class="w-full h-10 border border-[#D7F3F7] rounded-lg px-3 text-sm">
                        <option value="cash">cash</option>
                        <option value="transfer">transfer</option>
                    </select>
                    <p data-error-for="payment_method" class="hidden text-sm text-red-600 mt-1"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#496173] mb-1">Estimasi Total</label>
                    <div id="estimatedTotal" class="h-10 rounded-lg px-3 text-[#6B7280] bg-[#F3F4F6] border border-transparent flex items-center text-sm cursor-not-allowed select-none">Rp 0</div>
                </div>

                <div id="proofWrapper" class="hidden">
                    <label class="block text-sm font-medium text-[#496173] mb-1">Bukti Pembayaran</label>
                    <input id="paymentProof" type="file" accept="image/*" class="hidden">
                    <label for="paymentProof" class="flex min-h-[88px] items-center gap-3 rounded-2xl border border-[#D7F3F7] bg-white p-3 cursor-pointer hover:bg-[#F7FEFF] transition">
                        <span id="paymentProofPreview" class="hidden h-14 w-14 shrink-0 overflow-hidden rounded-xl border border-[#D7F3F7] bg-[#F7FEFF]">
                            <img id="paymentProofPreviewImage" src="" alt="Preview bukti pembayaran" class="h-full w-full object-cover">
                        </span>
                        <span class="min-w-0">
                            <span id="paymentProofName" class="block truncate text-sm font-semibold text-[#0A192F]">Pilih bukti transfer</span>
                            <span class="mt-1 block text-xs text-[#6B7280]">JPG/PNG, maks 2MB</span>
                        </span>
                    </label>
                    <p data-error-for="payment_proof" class="hidden text-sm text-red-600 mt-1"></p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#496173] mb-1">Foto Kondisi Baju <span class="text-xs font-normal text-[#6B7280]">(opsional)</span></label>
                    <input id="conditionPhoto" type="file" accept="image/*" class="hidden">
                    <label for="conditionPhoto" class="flex min-h-[88px] items-center gap-3 rounded-2xl border border-[#D7F3F7] bg-white p-3 cursor-pointer hover:bg-[#F7FEFF] transition">
                        <span id="conditionPhotoPreview" class="hidden h-14 w-14 shrink-0 overflow-hidden rounded-xl border border-[#D7F3F7] bg-[#F7FEFF]">
                            <img id="conditionPhotoPreviewImage" src="" alt="Preview kondisi baju" class="h-full w-full object-cover">
                        </span>
                        <span class="min-w-0">
                            <span id="conditionPhotoName" class="block truncate text-sm font-semibold text-[#0A192F]">Tambah foto kondisi</span>
                            <span class="mt-1 block text-xs text-[#6B7280]">Opsional, buat bukti kondisi barang</span>
                        </span>
                    </label>
                    <p data-error-for="condition_photo" class="hidden text-sm text-red-600 mt-1"></p>
                </div>

                <div class="sm:col-span-2 lg:col-span-3 flex justify-end pt-1">
                    <button id="transactionSubmitButton" class="w-full bg-[#00E5FF] hover:bg-[#00B8CC] disabled:opacity-50 disabled:cursor-not-allowed text-[#0A192F] px-5 h-10 rounded-full font-semibold transition sm:w-auto" type="submit" disabled>Simpan Transaksi</button>
                </div>
            </form>
        </div>

        <div class="rounded-[24px] border border-[#00E5FF] bg-[#0A192F] p-5 text-white shadow-[0_18px_46px_rgba(10,25,47,0.16)] sm:rounded-[28px] sm:p-6">
            <p class="text-sm font-semibold text-white/70">Fokus Transaksi</p>
            <h3 id="transactionFocusTotal" class="mt-4 text-4xl font-black leading-none">0</h3>
            <p class="mt-3 text-sm text-white/70">Transaksi yang perlu dipantau dari ringkasan cepat.</p>
            <div class="mt-5 grid grid-cols-2 gap-3 text-sm">
                <div class="rounded-2xl bg-white/10 px-3 py-3">
                    <p class="text-xs text-white/60">Tagih</p>
                    <strong id="transactionFocusUnpaid" class="mt-1 block text-xl">0</strong>
                </div>
                <div class="rounded-2xl bg-white/10 px-3 py-3">
                    <p class="text-xs text-white/60">Pickup</p>
                    <strong id="transactionFocusReady" class="mt-1 block text-xl">0</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 2xl:grid-cols-4 gap-4">
        <div class="bg-white rounded-[28px] border border-[#D7F3F7] shadow-[var(--wt-card-shadow)] p-5">
            <p class="text-xs font-semibold uppercase tracking-wide text-[#496173]">Transaksi Hari Ini</p>
            <div class="mt-3 flex items-end justify-between gap-4">
                <p id="summaryToday" class="text-3xl font-black text-[#0A192F]">0</p>
                <span class="rounded-full bg-[#E6FCFF] px-3 py-1 text-xs font-semibold text-[#00B8CC]">Masuk</span>
            </div>
        </div>
        <div class="bg-white rounded-[28px] border border-[#D7F3F7] shadow-[var(--wt-card-shadow)] p-5">
            <p class="text-xs font-semibold uppercase tracking-wide text-[#496173]">Belum Lunas</p>
            <div class="mt-3 flex items-end justify-between gap-4">
                <p id="summaryUnpaid" class="text-3xl font-black text-[#B45309]">0</p>
                <span class="rounded-full bg-[#FFF7ED] px-3 py-1 text-xs font-semibold text-[#B45309]">Tagih</span>
            </div>
        </div>
        <div class="bg-white rounded-[28px] border border-[#D7F3F7] shadow-[var(--wt-card-shadow)] p-5">
            <p class="text-xs font-semibold uppercase tracking-wide text-[#496173]">Lunas</p>
            <div class="mt-3 flex items-end justify-between gap-4">
                <p id="summaryPaid" class="text-3xl font-black text-[#047857]">0</p>
                <span class="rounded-full bg-[#ECFDF3] px-3 py-1 text-xs font-semibold text-[#047857]">Paid</span>
            </div>
        </div>
        <div class="bg-white rounded-[28px] border border-[#D7F3F7] shadow-[var(--wt-card-shadow)] p-5">
            <p class="text-xs font-semibold uppercase tracking-wide text-[#496173]">Siap Diambil</p>
            <div class="mt-3 flex items-end justify-between gap-4">
                <p id="summaryReady" class="text-3xl font-black text-[#00B8CC]">0</p>
                <span class="rounded-full bg-[#E6FCFF] px-3 py-1 text-xs font-semibold text-[#00B8CC]">Pickup</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[28px] border border-[#D7F3F7] shadow-[var(--wt-card-shadow)] overflow-hidden">
        <div class="px-6 py-5 border-b border-[#D7F3F7]">
            <div class="mb-4 flex flex-col gap-1">
                <h3 class="text-lg font-bold text-[#0A192F]">Riwayat Transaksi</h3>
                <p class="text-sm text-[#496173]">Filter transaksi berdasarkan status, pembayaran, tanggal, atau pencarian.</p>
            </div>
            <div class="mb-3 flex flex-wrap items-center gap-2" id="quickFilterGroup">
                <button type="button" data-quick-filter="all" class="quick-filter h-9 px-3 rounded-lg text-sm font-semibold border transition">Semua</button>
                <button type="button" data-quick-filter="today" class="quick-filter h-9 px-3 rounded-lg text-sm font-semibold border transition">Hari Ini</button>
                <button type="button" data-quick-filter="unpaid" class="quick-filter h-9 px-3 rounded-lg text-sm font-semibold border transition">Belum Lunas</button>
                <button type="button" data-quick-filter="paid" class="quick-filter h-9 px-3 rounded-lg text-sm font-semibold border transition">Lunas</button>
                <button type="button" data-quick-filter="ready" class="quick-filter h-9 px-3 rounded-lg text-sm font-semibold border transition">Siap Diambil</button>
            </div>
            <div class="mt-1 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex flex-wrap items-center gap-3">
                    <input id="search" type="text" placeholder="Cari invoice/nama/no hp" class="w-full sm:w-64 h-10 border border-[#D7F3F7] rounded-full bg-[#F7FEFF] px-4 text-sm">
                    <select id="filterStatus" class="w-full sm:w-48 h-10 border border-[#D7F3F7] rounded-full bg-white px-4 text-sm">
                        <option value="">Semua Status</option>
                        <option value="antrian">antrian</option>
                        <option value="dicuci">dicuci</option>
                        <option value="disetrika">disetrika</option>
                        <option value="siap diambil">siap diambil</option>
                        <option value="diambil">diambil</option>
                    </select>
                    <input id="dateFrom" type="date" class="w-full sm:w-40 h-10 border border-[#D7F3F7] rounded-full bg-white px-4 text-sm">
                    <input id="dateTo" type="date" class="w-full sm:w-40 h-10 border border-[#D7F3F7] rounded-full bg-white px-4 text-sm">
                    <button id="applyFilter" class="h-10 bg-[#0A192F] hover:bg-[#173b5f] text-white px-5 rounded-full text-sm font-semibold transition">Terapkan Filter</button>
                    <button id="autoRefreshBtn" class="h-10 bg-[#E6FCFF] text-[#00B8CC] px-5 rounded-full text-sm font-semibold">Auto-refresh: ON</button>
                </div>
                <span class="text-sm text-[#496173] lg:ml-4" id="transactionMeta"></span>
            </div>
        </div>

        <div class="space-y-3">
            <div class="px-6 pt-4">
                <h4 class="text-base font-bold text-[#0A192F]">Riwayat Transaksi</h4>
                <p class="text-xs text-[#496173] mt-1">Transaksi yang masih berlangsung atau belum sepenuhnya selesai.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-[#496173] border-b border-[#D7F3F7]">
                        <tr>
                            <th class="px-6 py-3 text-left text-[11px] md:text-xs font-semibold uppercase tracking-wide">Invoice</th>
                            <th class="px-6 py-3 text-left text-[11px] md:text-xs font-semibold uppercase tracking-wide">Pelanggan</th>
                            <th class="px-6 py-3 text-left text-[11px] md:text-xs font-semibold uppercase tracking-wide">Layanan</th>
                            <th class="px-6 py-3 text-right text-[11px] md:text-xs font-semibold uppercase tracking-wide">Total</th>
                            <th class="px-6 py-3 text-left text-[11px] md:text-xs font-semibold uppercase tracking-wide">Status</th>
                            <th class="px-6 py-3 text-left text-[11px] md:text-xs font-semibold uppercase tracking-wide">Bayar</th>
                            <th class="px-8 py-3 pr-10 text-right text-[11px] md:text-xs font-semibold uppercase tracking-wide">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="transactionTableBodyActive" class="divide-y divide-[#D7F3F7]"></tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-[#D7F3F7] flex items-center gap-2 bg-[#F7FEFF]" id="transactionPaginationActive"></div>
        </div>

        <div class="space-y-3 mt-8 border-t-2 border-[#D7F3F7] pt-8">
            <div class="px-6">
                <h4 class="text-base font-bold text-[#047857]">✓ Transaksi Selesai</h4>
                <p class="text-xs text-[#496173] mt-1">Transaksi yang sudah lunas dan sudah diambil oleh pelanggan. (Anda dapat menghapus data di bagian ini)</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-[#496173] border-b border-[#D7F3F7]">
                        <tr>
                            <th class="px-6 py-3 text-left text-[11px] md:text-xs font-semibold uppercase tracking-wide">Invoice</th>
                            <th class="px-6 py-3 text-left text-[11px] md:text-xs font-semibold uppercase tracking-wide">Pelanggan</th>
                            <th class="px-6 py-3 text-left text-[11px] md:text-xs font-semibold uppercase tracking-wide">Layanan</th>
                            <th class="px-6 py-3 text-right text-[11px] md:text-xs font-semibold uppercase tracking-wide">Total</th>
                            <th class="px-6 py-3 text-left text-[11px] md:text-xs font-semibold uppercase tracking-wide">Status</th>
                            <th class="px-6 py-3 text-left text-[11px] md:text-xs font-semibold uppercase tracking-wide">Bayar</th>
                            <th class="px-8 py-3 pr-10 text-right text-[11px] md:text-xs font-semibold uppercase tracking-wide">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="transactionTableBodyCompleted" class="divide-y divide-[#D7F3F7]"></tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-[#D7F3F7] flex items-center gap-2 bg-[#F7FEFF]" id="transactionPaginationCompleted"></div>
        </div>
    </div>
</div>

<div id="transactionDetailModal" class="hidden fixed inset-0 bg-black/40 z-50 items-center justify-center p-4">
    <div class="w-full max-w-2xl bg-white rounded-xl shadow-xl border border-[#D7F3F7] overflow-hidden">
        <div class="px-6 py-4 border-b border-[#D7F3F7] flex items-center justify-between">
            <h4 class="text-lg font-semibold text-[#0A192F]">Detail Transaksi</h4>
            <button id="closeDetailModalBtn" type="button" class="w-8 h-8 rounded-lg border border-[#D7F3F7] text-[#496173] hover:bg-[#F7FEFF]">X</button>
        </div>
        <div class="max-h-[82vh] overflow-y-auto p-5 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 items-start gap-y-4 md:gap-y-4 md:gap-x-6">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-[#6B7280]">Invoice</p>
                    <p id="detailInvoice" class="mt-1 text-sm font-semibold text-[#111827] leading-5">-</p>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-[#6B7280]">Tanggal</p>
                    <p id="detailDate" class="mt-1 text-sm font-semibold text-[#111827] leading-5">-</p>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-[#6B7280]">Pelanggan</p>
                    <p id="detailCustomer" class="mt-1 text-sm font-semibold text-[#111827] leading-5 max-w-[260px] truncate" title="-">-</p>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-[#6B7280]">No HP</p>
                    <p id="detailPhone" class="mt-1 text-sm font-semibold text-[#111827] leading-5">-</p>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-[#6B7280]">Layanan</p>
                    <p id="detailService" class="mt-1 text-sm font-semibold text-[#111827] leading-5">-</p>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-[#6B7280]">Estimasi Berat</p>
                    <p id="detailWeight" class="mt-1 text-sm font-semibold text-[#111827] leading-5">-</p>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-[#6B7280]">Status Cucian</p>
                    <p id="detailLaundryStatus" class="mt-1 text-sm font-semibold text-[#111827] leading-5">-</p>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-[#6B7280]">Total</p>
                    <p id="detailTotal" class="mt-1 text-sm font-bold text-[#111827] leading-5">-</p>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-[#6B7280]">Metode Bayar</p>
                    <p id="detailPaymentMethod" class="mt-1 text-sm font-semibold capitalize text-[#111827] leading-5">-</p>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-[#6B7280] mb-1">Status Bayar</p>
                    <div id="detailPaymentBadge">-</div>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-[#6B7280]">Alasan Bayar Terakhir</p>
                    <p id="detailPaymentReasonInfo" class="mt-1 text-sm font-semibold text-[#111827] leading-5">-</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-[#6B7280] mb-1">Bukti Transfer</p>
                    <div class="flex items-center gap-2">
                        <span id="detailProofInfo" class="text-sm text-[#496173]">Belum ada bukti</span>
                        <button id="detailProofPreviewBtn" type="button" class="hidden h-8 px-3 rounded-md border border-[#D7F3F7] bg-[#E6FCFF] text-[#00B8CC] hover:bg-[#DFFBFF] text-xs font-semibold">Lihat Bukti</button>
                    </div>
                </div>
                <div>
                    <p class="text-xs font-medium text-[#6B7280] mb-1">Foto Kondisi Baju</p>
                    <div class="flex items-center gap-2">
                        <span id="detailConditionInfo" class="text-sm text-[#496173]">Belum ada foto</span>
                        <button id="detailConditionPreviewBtn" type="button" class="hidden h-8 px-3 rounded-md border border-[#D7F3F7] bg-[#E6FCFF] text-[#00B8CC] hover:bg-[#DFFBFF] text-xs font-semibold">Lihat Foto</button>
                    </div>
                </div>
            </div>

            <div class="border-t border-[#D7F3F7] pt-4">
                <h5 class="text-base font-semibold text-[#0A192F] mb-3">Foto Kondisi Barang</h5>
                <div class="flex flex-col md:flex-row md:items-end gap-3">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-[#496173] mb-1">Upload/Ubah Foto Kondisi</label>
                        <input id="detailConditionPhoto" type="file" accept="image/*" class="hidden">
                        <label for="detailConditionPhoto" class="w-full min-h-[76px] border border-[#D7F3F7] rounded-2xl px-3 py-2 bg-white flex items-center gap-3 cursor-pointer hover:bg-[#F7FEFF] transition">
                            <span id="detailConditionPhotoPreview" class="hidden h-12 w-12 shrink-0 overflow-hidden rounded-xl border border-[#D7F3F7] bg-[#F7FEFF]">
                                <img id="detailConditionPhotoPreviewImage" src="" alt="Preview foto kondisi" class="h-full w-full object-cover">
                            </span>
                            <span class="min-w-0 flex-1">
                                <span id="detailConditionPhotoName" class="block text-sm font-semibold text-[#0A192F] truncate">Belum pilih file</span>
                                <span class="mt-1 block text-xs text-[#6B7280]">Klik untuk pilih foto</span>
                            </span>
                        </label>
                        <p id="detailConditionError" class="hidden mt-2 text-sm text-red-600"></p>
                    </div>
                    <button id="saveConditionPhotoBtn" type="button" class="h-10 px-4 rounded-lg bg-[#0A192F] text-white font-medium hover:bg-[#173b5f] disabled:opacity-50 disabled:cursor-not-allowed">Simpan Foto Kondisi</button>
                </div>
            </div>

            <div class="border-t border-[#D7F3F7] pt-4">
                <h5 class="text-base font-semibold text-[#0A192F] mb-3">Update Pembayaran</h5>
                <div class="flex flex-col md:flex-row md:items-stretch gap-3">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-[#496173] mb-1">Status Pembayaran</label>
                        <select id="detailPaymentStatus" class="w-full h-10 border border-[#D7F3F7] rounded-lg px-3 text-sm">
                            <option value="pending">pending</option>
                            <option value="paid">paid</option>
                        </select>
                        <label id="detailRemoveProofWrapper" class="mt-3 hidden items-start gap-2 rounded-2xl border border-[#FCD9BD] bg-[#FFF7ED] p-3 text-sm text-[#92400E]">
                            <input id="detailRemovePaymentProof" type="checkbox" class="mt-0.5 h-4 w-4 rounded border-[#FCD9BD] text-[#B45309]">
                            <span>
                                <span class="block font-bold">Hapus bukti transfer tersimpan</span>
                                <span class="mt-0.5 block text-xs">Wajib dicentang kalau status mau dibuat pending.</span>
                            </span>
                        </label>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-[#496173] mb-1">Upload/Ubah Bukti</label>
                        <input id="detailPaymentProof" type="file" accept="image/*" class="hidden">
                        <label for="detailPaymentProof" class="w-full min-h-[76px] border border-[#D7F3F7] rounded-2xl px-3 py-2 bg-white flex items-center gap-3 cursor-pointer hover:bg-[#F7FEFF] transition">
                            <span id="detailPaymentProofPreview" class="hidden h-12 w-12 shrink-0 overflow-hidden rounded-xl border border-[#D7F3F7] bg-[#F7FEFF]">
                                <img id="detailPaymentProofPreviewImage" src="" alt="Preview bukti pembayaran" class="h-full w-full object-cover">
                            </span>
                            <span class="min-w-0 flex-1">
                                <span id="detailPaymentProofName" class="block text-sm font-semibold text-[#0A192F] truncate">Belum pilih file</span>
                                <span class="mt-1 block text-xs text-[#6B7280]">Klik untuk pilih bukti</span>
                            </span>
                        </label>
                    </div>
                </div>
                <div class="mt-3">
                    <label class="block text-sm font-medium text-[#496173] mb-1">Alasan Perubahan <span class="text-xs font-normal text-[#B45309]">(wajib)</span></label>
                    <textarea id="detailPaymentReason" minlength="5" maxlength="255" class="w-full min-h-[78px] resize-none rounded-2xl border border-[#D7F3F7] px-3 py-2 text-sm" placeholder="Contoh: Bukti transfer valid / Bukti salah, set pending untuk verifikasi ulang"></textarea>
                </div>
                <p id="detailPaymentError" class="hidden mt-2 text-sm text-red-600"></p>
                <div class="mt-4 flex items-center justify-end gap-3">
                    <button id="closeDetailModalSecondaryBtn" type="button" class="h-10 px-4 rounded-lg border border-[#D7F3F7] text-[#496173] hover:bg-[#F7FEFF]">Tutup</button>
                    <button id="savePaymentBtn" type="button" class="h-10 px-4 rounded-lg bg-[#00E5FF] text-[#0A192F] font-medium hover:bg-[#00B8CC] disabled:opacity-50 disabled:cursor-not-allowed">Simpan Status Pembayaran</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="paymentProofModal" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center p-4">
    <div class="w-full max-w-2xl bg-white rounded-xl shadow-xl border border-[#D7F3F7] overflow-hidden">
        <div class="px-6 py-4 border-b border-[#D7F3F7] flex items-center justify-between">
            <h4 id="paymentProofTitle" class="text-lg font-semibold text-[#0A192F]">Bukti Pembayaran</h4>
            <button id="closeProofModalBtn" type="button" class="w-8 h-8 rounded-lg border border-[#D7F3F7] text-[#496173] hover:bg-[#F7FEFF]">X</button>
        </div>
        <div class="p-4 bg-[#F7FEFF]">
            <img id="paymentProofImage" src="" alt="Bukti pembayaran" class="w-full max-h-[62vh] object-contain rounded-lg bg-white">
        </div>
    </div>
</div>

<script>

    let loadingCount = 0;
    function safeToggleLoading(show) {
        if (typeof window.toggleLoading !== 'function') return;
        
        if (show) {
            loadingCount++;
            if (loadingCount === 1) window.toggleLoading(true);
        } else {
            loadingCount = Math.max(0, loadingCount - 1);
            if (loadingCount === 0) window.toggleLoading(false);
        }
    }

    let serviceMap = {};
    let transactionPageActive = 1;
    let transactionLastPageActive = 1;
    let transactionPageCompleted = 1;
    let transactionLastPageCompleted = 1;
    let autoRefreshEnabled = true;
    let autoRefreshTimer = null;
    

    let isLoadingActive = false;
    let isLoadingCompleted = false;
    
    let currentDetailTransaction = null;
    let quickPaymentStatus = '';
    let activeQuickFilter = 'all';
    
    const transactionSubmitButton = document.getElementById('transactionSubmitButton');
    const STATUS_OPTIONS = ['antrian', 'dicuci', 'disetrika', 'siap diambil', 'diambil'];
    const filePreviewUrls = {};

    function releaseFilePreviewUrl(inputId) {
        if (filePreviewUrls[inputId]) {
            URL.revokeObjectURL(filePreviewUrls[inputId]);
            delete filePreviewUrls[inputId];
        }
    }

    function resetFilePreviewView(previewId, imageId, nameId, defaultName) {
        const preview = document.getElementById(previewId);
        const image = document.getElementById(imageId);
        const name = document.getElementById(nameId);

        if (image) image.src = '';
        if (preview) preview.classList.add('hidden');
        if (name) name.textContent = defaultName;
    }

    function clearFilePreview(inputId, previewId, imageId, nameId, defaultName) {
        releaseFilePreviewUrl(inputId);

        const input = document.getElementById(inputId);

        if (input) input.value = '';
        resetFilePreviewView(previewId, imageId, nameId, defaultName);
    }

    function updateFilePreview(inputId, previewId, imageId, nameId, defaultName) {
        const input = document.getElementById(inputId);
        const file = input?.files?.[0];

        releaseFilePreviewUrl(inputId);
        resetFilePreviewView(previewId, imageId, nameId, defaultName);

        if (!file) return;

        document.getElementById(nameId).textContent = file.name;

        if (!file.type.startsWith('image/')) return;

        const previewUrl = URL.createObjectURL(file);
        filePreviewUrls[inputId] = previewUrl;
        document.getElementById(imageId).src = previewUrl;
        document.getElementById(previewId).classList.remove('hidden');
    }

    function clearTransactionPhotoPreviews() {
        clearFilePreview('paymentProof', 'paymentProofPreview', 'paymentProofPreviewImage', 'paymentProofName', 'Pilih bukti transfer');
        clearFilePreview('conditionPhoto', 'conditionPhotoPreview', 'conditionPhotoPreviewImage', 'conditionPhotoName', 'Tambah foto kondisi');
    }

    function clearDetailPhotoPreviews() {
        clearFilePreview('detailPaymentProof', 'detailPaymentProofPreview', 'detailPaymentProofPreviewImage', 'detailPaymentProofName', 'Belum pilih file');
        clearFilePreview('detailConditionPhoto', 'detailConditionPhotoPreview', 'detailConditionPhotoPreviewImage', 'detailConditionPhotoName', 'Belum pilih file');
    }

    function fieldErrorsToInline(errors) {
        return Object.fromEntries(
            Object.entries(errors).map(([key, value]) => [key, [value]])
        );
    }

    function getTrimmedTransactionValue(id) {
        return document.getElementById(id).value.trim();
    }


    function getServicesArray() {
        return Object.values(serviceMap);
    }

    function buildServiceOptions(selectedId = '') {
        return '<option value="">Pilih layanan</option>' + getServicesArray().map((service) => {
            const price = typeof window.formatCurrency === 'function' ? window.formatCurrency(service.price) : service.price;
            const selected = String(service.id) === String(selectedId) ? 'selected' : '';
            return `<option value="${service.id}" ${selected}>${service.service_name} (${price}/${service.unit})</option>`;
        }).join('');
    }

    function normalizeQuantityByUnit(input, service) {
        const unit = String(service?.unit ?? '').toLowerCase();
        const value = Number(input.value || 0);

        if (unit === 'pcs') {
            input.step = '1';
            if (value && !Number.isInteger(value)) {
                input.value = String(Math.max(1, Math.round(value)));
            }
            return;
        }

        input.step = '0.1';
    }

    function getTransactionItems() {
        const items = [];
        const mainServiceId = getTrimmedTransactionValue('serviceId');
        const mainQuantity = getTrimmedTransactionValue('weight');

        if (mainServiceId || mainQuantity) {
            items.push({ service_id: mainServiceId, quantity: mainQuantity, field: 'main' });
        }

        document.querySelectorAll('[data-extra-service-row]').forEach((row) => {
            const serviceId = row.querySelector('[data-extra-service]')?.value.trim() ?? '';
            const quantity = row.querySelector('[data-extra-weight]')?.value.trim() ?? '';
            items.push({ service_id: serviceId, quantity, field: 'extra' });
        });

        return items;
    }

    function addServiceItemRow() {
        const wrapper = document.getElementById('extraServiceItems');
        const row = document.createElement('div');
        row.dataset.extraServiceRow = 'true';
        row.className = 'grid grid-cols-1 gap-3 rounded-2xl border border-[#D7F3F7] bg-white p-3 sm:grid-cols-[minmax(0,1fr)_160px_auto] sm:items-end';
        row.innerHTML = `
            <div>
                <label class="mb-1 block text-xs font-bold text-[#496173]">Layanan</label>
                <select data-extra-service class="h-10 w-full rounded-lg border border-[#D7F3F7] px-3 text-sm">${buildServiceOptions()}</select>
            </div>
            <div>
                <label class="mb-1 block text-xs font-bold text-[#496173]">Berat/Jumlah</label>
                <input data-extra-weight type="number" min="1" step="0.1" class="h-10 w-full rounded-lg border border-[#D7F3F7] px-3 text-sm" placeholder="Kg/Pcs">
            </div>
            <button type="button" data-remove-service-row class="h-10 rounded-full border border-red-100 bg-red-50 px-4 text-xs font-bold text-red-600 transition hover:bg-red-100">Hapus</button>
        `;
        wrapper.appendChild(row);
        updateTransactionSubmitState();
    }

    function getTransactionItemsForDisplay(transaction) {
        if (Array.isArray(transaction.items) && transaction.items.length > 0) {
            return transaction.items;
        }

        return [{
            service: transaction.service,
            quantity: transaction.weight,
            unit_price: transaction.service?.price,
            subtotal: transaction.total_price,
        }];
    }

    function formatServiceSummary(transaction) {
        const items = getTransactionItemsForDisplay(transaction);
        const firstName = items[0]?.service?.service_name ?? transaction.service?.service_name ?? '-';
        return items.length > 1 ? `${firstName} +${items.length - 1}` : firstName;
    }

    function formatItemLines(transaction) {
        return getTransactionItemsForDisplay(transaction).map((item) => {
            const service = item.service;
            const quantity = formatTransactionQuantity(item.quantity, service?.unit);
            return `${service?.service_name ?? '-'} (${quantity})`;
        });
    }
    function validateTransactionForm(showErrors = false) {
        const paymentMethod = getTrimmedTransactionValue('paymentMethod');
        const paymentProof = document.getElementById('paymentProof').files[0];
        const items = getTransactionItems();
        const errors = {};
        const selectedServices = new Set();

        if (!getTrimmedTransactionValue('customerId')) errors.customer_id = 'Pelanggan wajib dipilih.';
        if (items.length === 0 || !items[0].service_id) errors.service_id = 'Layanan wajib dipilih.';
        if (!items[0].quantity) errors.weight = 'Berat/jumlah wajib diisi.';

        items.forEach((item, index) => {
            const service = serviceMap[item.service_id];
            const quantity = Number(item.quantity);

            if (!item.service_id || !service) {
                errors[index === 0 ? 'service_id' : 'items'] = 'Semua baris layanan wajib dipilih.';
                return;
            }

            if (selectedServices.has(item.service_id)) {
                errors.items = 'Layanan yang sama tidak perlu ditambahkan dua kali. Ubah jumlahnya saja.';
            }
            selectedServices.add(item.service_id);

            if (!Number.isFinite(quantity) || quantity < 1) {
                errors[index === 0 ? 'weight' : 'items'] = 'Berat/jumlah tiap layanan minimal 1 dan tidak boleh minus.';
            }

            if (String(service.unit ?? '').toLowerCase() === 'pcs' && !Number.isInteger(quantity)) {
                errors[index === 0 ? 'weight' : 'items'] = 'Jumlah untuk unit Pcs harus bilangan bulat.';
            }
        });

        if (!['cash', 'transfer'].includes(paymentMethod)) errors.payment_method = 'Metode pembayaran wajib dipilih.';
        if (paymentMethod === 'transfer' && !paymentProof) errors.payment_proof = 'Bukti pembayaran wajib diupload jika memilih metode transfer.';

        if (showErrors && typeof window.clearInlineErrors === 'function') {
            window.clearInlineErrors('transactionForm');
            window.applyInlineErrors('transactionForm', fieldErrorsToInline(errors));
        }

        return Object.keys(errors).length === 0;
    }
    function updateTransactionSubmitState() {
        if(transactionSubmitButton) {
            transactionSubmitButton.disabled = !validateTransactionForm(false);
        }
    }

    function getTodayDateString() {
        const date = new Date();
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function updateQuickFilterButtons() {
        document.querySelectorAll('[data-quick-filter]').forEach((button) => {
            const isActive = button.dataset.quickFilter === activeQuickFilter;
            button.className = isActive
                ? 'quick-filter h-9 px-4 rounded-full text-sm font-semibold border border-[#00E5FF] bg-[#E6FCFF] text-[#00B8CC] transition'
                : 'quick-filter h-9 px-4 rounded-full text-sm font-semibold border border-[#D7F3F7] bg-white text-[#496173] hover:bg-[#F7FEFF] transition';
        });
    }

    async function applyQuickFilter(filter) {
        activeQuickFilter = filter;
        quickPaymentStatus = '';
        document.getElementById('filterStatus').value = '';
        document.getElementById('dateFrom').value = '';
        document.getElementById('dateTo').value = '';

        if (filter === 'today') {
            const today = getTodayDateString();
            document.getElementById('dateFrom').value = today;
            document.getElementById('dateTo').value = today;
        }

        if (filter === 'unpaid') {
            quickPaymentStatus = 'pending';
        }

        if (filter === 'paid') {
            quickPaymentStatus = 'paid';
        }

        if (filter === 'ready') {
            document.getElementById('filterStatus').value = 'siap diambil';
        }

        updateQuickFilterButtons();
        
        safeToggleLoading(true);
        await loadTransactionsActive(1);
        await loadTransactionsCompleted(1);
        safeToggleLoading(false);
    }

    function decodeTransactionPayload(encodedTransaction) {
        try {
            return JSON.parse(decodeURIComponent(encodedTransaction));
        } catch (error) {
            if(typeof showToast === 'function') showToast('Data transaksi tidak valid.', 'error');
            return null;
        }
    }

    function toReadableDate(value) {
        if (!value) return '-';
        const date = new Date(value);
        if (Number.isNaN(date.getTime())) return '-';
        return date.toLocaleString('id-ID');
    }

    function getStatusPalette(status) {
        const map = {
            antrian: { bg: 'rgba(251,176,59,0.20)', color: '#C2410C' },
            dicuci: { bg: 'rgba(0,229,255,0.14)', color: '#00B8CC' },
            disetrika: { bg: 'rgba(181,32,130,0.12)', color: '#8F1667' },
            'siap diambil': { bg: 'rgba(0,229,255,0.14)', color: '#00B8CC' },
            diambil: { bg: 'rgba(93,43,138,0.12)', color: '#4A1F6F' }
        };
        return map[status] ?? { bg: '#F3F4F6', color: '#4B5563' };
    }

    function getPaymentStatusBadge(paymentStatus) {
        const map = {
            paid: { bg: 'rgba(16,185,129,0.14)', color: '#047857', label: 'lunas' },
            pending: { bg: 'rgba(251,176,59,0.16)', color: '#B45309', label: 'pending' }
        };
        const palette = map[paymentStatus] ?? { bg: '#F3F4F6', color: '#4B5563', label: paymentStatus ?? '-' };
        return `<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold" style="background:${palette.bg}; color:${palette.color};">${palette.label}</span>`;
    }

    function formatTransactionQuantity(value, unit) {
        const numericValue = Number(value);
        if (!Number.isFinite(numericValue)) return '-';
        const formattedValue = String(unit ?? '').toLowerCase() === 'pcs'
            ? String(Math.round(numericValue))
            : numericValue.toFixed(2);
        return `${formattedValue} ${unit ?? ''}`.trim();
    }

    function renderStatusSelect(transaction) {
        const palette = getStatusPalette(transaction.status);
        return `
            <span class="inline-flex items-center rounded-full px-3 py-1" style="background:${palette.bg};">
                <select
                    onchange="updateStatus(${transaction.id}, this.value)"
                    class="bg-transparent border-0 p-0 pr-5 text-xs font-semibold focus:outline-none focus:ring-0 cursor-pointer"
                    style="color:${palette.color};"
                >
                    ${STATUS_OPTIONS.map((status) => `
                        <option value="${status}" ${transaction.status === status ? 'selected' : ''}>${status}</option>
                    `).join('')}
                </select>
            </span>
        `;
    }

    function calculateEstimatedTotal() {
        const serviceId = document.getElementById('serviceId').value;
        const weightInput = document.getElementById('weight');
        const mainService = serviceMap[serviceId];
        normalizeQuantityByUnit(weightInput, mainService);

        let total = 0;
        getTransactionItems().forEach((item) => {
            const service = serviceMap[item.service_id];
            const quantity = Number(item.quantity || 0);
            if (service && Number.isFinite(quantity)) {
                total += Number(service.price || 0) * quantity;
            }
        });

        document.querySelectorAll('[data-extra-service-row]').forEach((row) => {
            const select = row.querySelector('[data-extra-service]');
            const input = row.querySelector('[data-extra-weight]');
            normalizeQuantityByUnit(input, serviceMap[select?.value]);
        });

        if(typeof window.formatCurrency === 'function') {
            document.getElementById('serviceUnitPrice').textContent = window.formatCurrency(Number(mainService?.price || 0));
            document.getElementById('estimatedTotal').textContent = window.formatCurrency(total);
        }
        const unit = mainService?.unit;
        document.getElementById('weightLabel').textContent = unit ? `Berat/Jumlah (${unit})` : 'Berat/Jumlah';
        document.getElementById('weight').placeholder = unit ? `Masukkan ${unit}` : 'Kg/Pcs';
    }
    function resetDetailPaymentError() {
        const errorBox = document.getElementById('detailPaymentError');
        errorBox.textContent = '';
        errorBox.classList.add('hidden');
    }

    function setDetailPaymentError(message) {
        const errorBox = document.getElementById('detailPaymentError');
        errorBox.textContent = message;
        errorBox.classList.remove('hidden');
    }

    function resetDetailConditionError() {
        const errorBox = document.getElementById('detailConditionError');
        errorBox.textContent = '';
        errorBox.classList.add('hidden');
    }

    function setDetailConditionError(message) {
        const errorBox = document.getElementById('detailConditionError');
        errorBox.textContent = message;
        errorBox.classList.remove('hidden');
    }

    function fillTransactionDetail(transaction) {
        currentDetailTransaction = transaction;

        document.getElementById('detailInvoice').textContent = transaction.invoice_code ?? '-';
        document.getElementById('detailDate').textContent = toReadableDate(transaction.created_at);
        const customerName = transaction.customer?.user?.name ?? '-';
        const customerElement = document.getElementById('detailCustomer');
        customerElement.textContent = customerName;
        customerElement.title = customerName;
        document.getElementById('detailPhone').textContent = transaction.customer?.phone ?? '-';
        document.getElementById('detailService').innerHTML = formatItemLines(transaction).join('<br>');
        document.getElementById('detailLaundryStatus').textContent = transaction.status ?? '-';
        if(typeof window.formatCurrency === 'function') {
            document.getElementById('detailTotal').textContent = window.formatCurrency(transaction.total_price ?? 0);
        }
        document.getElementById('detailPaymentMethod').textContent = transaction.payment_method ?? '-';
        document.getElementById('detailPaymentBadge').innerHTML = getPaymentStatusBadge(transaction.payment_status);
        document.getElementById('detailPaymentReasonInfo').textContent = transaction.payment_reason ?? '-';
        document.getElementById('detailPaymentStatus').value = transaction.payment_status ?? 'pending';
        document.getElementById('detailPaymentReason').value = '';
        document.getElementById('detailRemovePaymentProof').checked = false;
        clearDetailPhotoPreviews();
        resetDetailPaymentError();
        resetDetailConditionError();

        document.getElementById('detailWeight').textContent = getTransactionItemsForDisplay(transaction).length > 1 ? `${getTransactionItemsForDisplay(transaction).length} layanan` : formatTransactionQuantity(transaction.weight, transaction.service?.unit);

        const proofInfo = document.getElementById('detailProofInfo');
        const proofButton = document.getElementById('detailProofPreviewBtn');
        const uploadProofContainer = document.getElementById('detailPaymentProof').parentElement;
        const removeProofWrapper = document.getElementById('detailRemoveProofWrapper');

        // Cash tidak butuh bukti transfer.
        if (transaction.payment_method === 'cash') {
            proofInfo.textContent = 'Tunai (Tanpa Bukti)';
            proofButton.classList.add('hidden');
            uploadProofContainer.classList.add('hidden');
            removeProofWrapper.classList.add('hidden');
            removeProofWrapper.classList.remove('flex');
        } else {
            uploadProofContainer.classList.remove('hidden');
            if (transaction.payment_proof_url) {
                proofInfo.textContent = 'Bukti tersedia';
                proofButton.classList.remove('hidden');
                removeProofWrapper.classList.remove('hidden');
                removeProofWrapper.classList.add('flex');
            } else {
                proofInfo.textContent = 'Belum ada bukti';
                proofButton.classList.add('hidden');
                removeProofWrapper.classList.add('hidden');
                removeProofWrapper.classList.remove('flex');
            }
        }

        const conditionInfo = document.getElementById('detailConditionInfo');
        const conditionButton = document.getElementById('detailConditionPreviewBtn');
        if (transaction.condition_photo_url) {
            conditionInfo.textContent = 'Foto tersedia';
            conditionButton.classList.remove('hidden');
        } else {
            conditionInfo.textContent = 'Belum ada foto';
            conditionButton.classList.add('hidden');
        }
    }

    function openTransactionDetail(encodedTransaction) {
        const transaction = decodeTransactionPayload(encodedTransaction);
        if (!transaction) return;
        fillTransactionDetail(transaction);
        const modal = document.getElementById('transactionDetailModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeTransactionDetail() {
        const modal = document.getElementById('transactionDetailModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        clearDetailPhotoPreviews();
        document.getElementById('detailPaymentReason').value = '';
        document.getElementById('detailRemovePaymentProof').checked = false;
        resetDetailConditionError();
        currentDetailTransaction = null;
    }

    function openPaymentProof(imageUrl, invoiceCode = '-') {
        if (!imageUrl) {
            if(typeof showToast === 'function') showToast('Bukti pembayaran belum ada.', 'info');
            return;
        }
        document.getElementById('paymentProofTitle').textContent = `Bukti Pembayaran - ${invoiceCode}`;
        document.getElementById('paymentProofImage').src = imageUrl;
        const modal = document.getElementById('paymentProofModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function openPaymentProofFromPayload(encodedTransaction) {
        const transaction = decodeTransactionPayload(encodedTransaction);
        if (!transaction) return;
        openPaymentProof(transaction.payment_proof_url, transaction.invoice_code ?? '-');
    }

    function openConditionPhotoFromPayload(encodedTransaction) {
        const transaction = decodeTransactionPayload(encodedTransaction);
        if (!transaction) return;
        if (!transaction.condition_photo_url) {
            if(typeof showToast === 'function') showToast('Foto kondisi baju belum ada.', 'info');
            return;
        }
        document.getElementById('paymentProofTitle').textContent = `Foto Kondisi Baju - ${transaction.invoice_code ?? '-'}`;
        document.getElementById('paymentProofImage').src = transaction.condition_photo_url;
        const modal = document.getElementById('paymentProofModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closePaymentProof() {
        const modal = document.getElementById('paymentProofModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.getElementById('paymentProofImage').src = '';
    }

    async function promptPaymentReason(transaction, targetStatus) {
        const statusLabel = targetStatus === 'paid' ? 'lunas' : 'pending';
        const title = targetStatus === 'paid' ? 'Alasan tandai lunas' : 'Alasan set pending';
        const placeholder = targetStatus === 'paid'
            ? 'Contoh: Pembayaran sudah diterima dan bukti valid'
            : 'Contoh: Bukti salah atau pembayaran perlu dicek ulang';

        if (typeof Swal === 'undefined') {
            const reason = prompt(`${title}\nInvoice: ${transaction.invoice_code ?? '-'}\nStatus: ${statusLabel}`);
            return reason && reason.trim().length >= 5 ? reason.trim() : null;
        }

        const result = await Swal.fire({
            title,
            text: `Invoice ${transaction.invoice_code ?? '-'} akan diubah ke ${statusLabel}.`,
            input: 'textarea',
            inputPlaceholder: placeholder,
            inputAttributes: {
                maxlength: 255,
            },
            icon: targetStatus === 'paid' ? 'success' : 'warning',
            showCancelButton: true,
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            background: '#FFFFFF',
            color: '#0A192F',
            inputValidator: (value) => {
                if (!value || value.trim().length < 5) {
                    return 'Alasan minimal 5 karakter.';
                }
                return null;
            },
            customClass: {
                popup: 'rounded-[28px] border border-[#D7F3F7] shadow-[0_24px_80px_rgba(10,25,47,0.22)]',
                title: 'text-[#0A192F] text-2xl font-black',
                htmlContainer: 'text-[#496173] text-sm',
                input: 'rounded-2xl border border-[#D7F3F7] text-sm',
                confirmButton: 'h-11 px-5 rounded-full bg-[#00E5FF] text-[#0A192F] font-bold hover:bg-[#00B8CC] focus:outline-none',
                cancelButton: 'h-11 px-5 rounded-full border border-[#D7F3F7] bg-white text-[#0A192F] font-bold hover:bg-[#F7FEFF] focus:outline-none',
            },
            buttonsStyling: false,
        });

        return result.isConfirmed ? result.value.trim() : null;
    }

    async function savePaymentFromDetail() {
        if (!currentDetailTransaction) return;

        const transactionId = currentDetailTransaction.id;
        let paymentStatus = document.getElementById('detailPaymentStatus').value;
        const paymentProofFile = document.getElementById('detailPaymentProof').files[0];
        const removePaymentProof = document.getElementById('detailRemovePaymentProof').checked;
        const paymentReason = document.getElementById('detailPaymentReason').value.trim();
        const savePaymentButton = document.getElementById('savePaymentBtn');

        resetDetailPaymentError();

        if (paymentReason.length < 5) {
            setDetailPaymentError('Alasan perubahan pembayaran wajib diisi minimal 5 karakter.');
            return;
        }

        if (paymentProofFile && currentDetailTransaction.payment_method === 'transfer') {
            paymentStatus = 'paid';
            document.getElementById('detailPaymentStatus').value = 'paid';
        }

        if (paymentStatus === 'paid' && currentDetailTransaction.payment_method === 'transfer' && !paymentProofFile && !currentDetailTransaction.payment_proof_url) {
            await window.showActionAlert({
                title: 'Bukti transfer belum ada',
                text: 'Upload bukti transfer dulu sebelum transaksi transfer ditandai lunas.',
                icon: 'info',
                confirmText: 'Upload bukti',
            });
            setDetailPaymentError('Upload bukti transfer dulu sebelum tandai lunas.');
            return;
        }

        if (paymentStatus === 'pending' && currentDetailTransaction.payment_method === 'transfer' && currentDetailTransaction.payment_proof_url && !removePaymentProof) {
            await window.showActionAlert({
                title: 'Bukti transfer masih ada',
                text: 'Kalau status mau dibuat pending, centang hapus bukti transfer tersimpan dulu.',
                icon: 'warning',
                confirmText: 'Saya cek',
            });
            setDetailPaymentError('Centang hapus bukti transfer jika pembayaran memang belum valid.');
            return;
        }

        safeToggleLoading(true);
        savePaymentButton.disabled = true;
        savePaymentButton.textContent = 'Menyimpan...';
        try {
            const formData = new FormData();
            formData.append('payment_status', paymentStatus);
            formData.append('payment_reason', paymentReason);
            if (removePaymentProof) {
                formData.append('remove_payment_proof', '1');
            }
            if (paymentProofFile) {
                formData.append('payment_proof', paymentProofFile);
            }

            const response = await window.apiFetch(`/api/transactions/${transactionId}/payment`, {
                method: 'POST',
                body: formData
            });

            await window.showSuccessAlert({
                title: 'Pembayaran diperbarui',
                text: response.message,
            });
            fillTransactionDetail(response.data);
            await loadTransactionsActive(transactionPageActive);
            await loadTransactionsCompleted(transactionPageCompleted);
            await loadTransactionSummary();
        } catch (error) {
            if (error.status === 422) {
                const paymentProofError = error.errors?.payment_proof?.[0];
                const paymentReasonError = error.errors?.payment_reason?.[0];
                if (paymentProofError || paymentReasonError) {
                    setDetailPaymentError(paymentProofError || paymentReasonError);
                }
            }
            if(typeof showToast === 'function') showToast(error.message, 'error');
        } finally {
            savePaymentButton.disabled = false;
            savePaymentButton.textContent = 'Simpan Status Pembayaran';
            safeToggleLoading(false);
        }
    }

    async function saveConditionPhotoFromDetail() {
        if (!currentDetailTransaction) return;

        const conditionPhotoFile = document.getElementById('detailConditionPhoto').files[0];
        const saveConditionButton = document.getElementById('saveConditionPhotoBtn');

        resetDetailConditionError();

        if (!conditionPhotoFile) {
            setDetailConditionError('Pilih foto kondisi baju terlebih dahulu.');
            return;
        }

        safeToggleLoading(true);
        saveConditionButton.disabled = true;
        saveConditionButton.textContent = 'Menyimpan...';

        try {
            const formData = new FormData();
            formData.append('condition_photo', conditionPhotoFile);

            const response = await window.apiFetch(`/api/transactions/${currentDetailTransaction.id}/condition-photo`, {
                method: 'POST',
                body: formData
            });

            await window.showSuccessAlert({
                title: 'Foto kondisi disimpan',
                text: response.message,
            });
            fillTransactionDetail(response.data);
            await loadTransactionsActive(transactionPageActive);
            await loadTransactionsCompleted(transactionPageCompleted);
        } catch (error) {
            if (error.status === 422) {
                const conditionError = error.errors?.condition_photo?.[0];
                if (conditionError) setDetailConditionError(conditionError);
            }
            if(typeof showToast === 'function') showToast(error.message, 'error');
        } finally {
            saveConditionButton.disabled = false;
            saveConditionButton.textContent = 'Simpan Foto Kondisi';
            safeToggleLoading(false);
        }
    }

    async function quickUpdatePayment(encodedTransaction, targetStatus) {
        const transaction = decodeTransactionPayload(encodedTransaction);
        if (!transaction) return;
        if (transaction.payment_status === targetStatus) return;

        if (targetStatus === 'paid' && transaction.payment_method === 'transfer' && !transaction.payment_proof_url) {
            openTransactionDetail(encodedTransaction);
            document.getElementById('detailPaymentStatus').value = 'paid';
            await window.showActionAlert({
                title: 'Upload bukti transfer dulu',
                text: 'Transaksi transfer baru bisa lunas setelah bukti transfer diupload dari detail transaksi.',
                icon: 'info',
                confirmText: 'Buka detail',
            });
            return;
        }

        if (targetStatus === 'pending' && transaction.payment_method === 'transfer' && transaction.payment_proof_url) {
            openTransactionDetail(encodedTransaction);
            document.getElementById('detailPaymentStatus').value = 'pending';
            await window.showActionAlert({
                title: 'Hapus bukti transfer dulu',
                text: 'Bukti transfer masih tersimpan. Kalau bukti salah, centang hapus bukti di detail lalu isi alasan.',
                icon: 'warning',
                confirmText: 'Saya cek',
            });
            return;
        }

        const paymentReason = await promptPaymentReason(transaction, targetStatus);
        if (!paymentReason) return;

        safeToggleLoading(true);
        try {
            const formData = new FormData();
            formData.append('payment_status', targetStatus);
            formData.append('payment_reason', paymentReason);

            const response = await window.apiFetch(`/api/transactions/${transaction.id}/payment`, {
                method: 'POST',
                body: formData
            });

            await window.showSuccessAlert({
                title: targetStatus === 'paid' ? 'Pembayaran lunas' : 'Pembayaran pending',
                text: response.message,
            });
            await loadTransactionsActive(transactionPageActive);
            await loadTransactionsCompleted(transactionPageCompleted);
            await loadTransactionSummary();
        } catch (error) {
            if(typeof showToast === 'function') showToast(error.message, 'error');
        } finally {
            safeToggleLoading(false);
        }
    }

    function normalizeWhatsAppPhone(phone) {
        const digits = String(phone ?? '').replace(/\D/g, '');
        if (!digits) return '';
        if (digits.startsWith('62')) return digits;
        if (digits.startsWith('0')) return `62${digits.slice(1)}`;
        if (digits.startsWith('8')) return `62${digits}`;
        return digits;
    }

    function sendWhatsAppUpdate(encodedTransaction) {
        const transaction = decodeTransactionPayload(encodedTransaction);
        if (!transaction) return;

        const phone = normalizeWhatsAppPhone(transaction.customer?.phone);
        if (!phone || phone.length < 10) {
            if(typeof showToast === 'function') showToast('Nomor HP pelanggan tidak valid untuk WhatsApp.', 'error');
            return;
        }

        const customerName = transaction.customer?.user?.name ?? 'Pelanggan';
        const invoiceCode = transaction.invoice_code ?? '-';
        const status = transaction.status ?? '-';
        const serviceLines = formatItemLines(transaction).join(', ');
        const paymentStatus = transaction.payment_status === 'paid' ? 'lunas' : 'pending';
        let totalPrice = '0';
        if(typeof window.formatCurrency === 'function') {
            totalPrice = window.formatCurrency(transaction.total_price ?? 0);
        }

        const message = [
            `Halo ${customerName},`,
            '',
            `Update laundry untuk invoice ${invoiceCode}:`,
            `Layanan: ${serviceLines}`,
            `Status cucian: ${status}`,
            `Status pembayaran: ${paymentStatus}`,
            `Total: ${totalPrice}`,
            '',
            'Terima kasih.'
        ].join('\n');

        window.open(`https://wa.me/${phone}?text=${encodeURIComponent(message)}`, '_blank', 'noopener,noreferrer');
    }

    function printReceiptFromPayload(encodedTransaction) {
        const transaction = decodeTransactionPayload(encodedTransaction);
        if (!transaction) return;
        printReceipt(transaction);
    }

    function printReceipt(transaction) {
        const printWindow = window.open('', '_blank', 'width=380,height=520');
        if (!printWindow) {
            if(typeof showToast === 'function') showToast('Popup diblokir browser. Izinkan popup untuk cetak struk.', 'error');
            return;
        }

        const storeName = 'wasy';
        const storeContact = 'Kontak: 08xx-xxxx-xxxx';
        const storeAddress = 'Alamat: Jl. Laundry No. 1';
        const printedAt = toReadableDate(transaction.created_at);
        const customerName = transaction.customer?.user?.name ?? '-';
        const customerPhone = transaction.customer?.phone ?? '-';
        const invoiceCode = transaction.invoice_code ?? '-';
        const normalizeReceiptText = (value) => {
            if (!value || value === '-') return '-';
            return String(value).replace(/_/g, ' ').toLowerCase().replace(/\b\w/g, (char) => char.toUpperCase());
        };

        const serviceName = formatItemLines(transaction).join(', ');
        const paymentMethod = normalizeReceiptText(transaction.payment_method ?? '-');
        const paymentStatus = normalizeReceiptText(transaction.payment_status ?? '-');
        const laundryStatus = normalizeReceiptText(transaction.status ?? '-');
        let totalPrice = '0';
        let unitPrice = '-';
        if(typeof window.formatCurrency === 'function') {
             totalPrice = window.formatCurrency(transaction.total_price);
             const servicePrice = Number(transaction.service?.price || 0);
             unitPrice = servicePrice > 0 ? `${window.formatCurrency(servicePrice)} / ${transaction.service?.unit ?? '-'}` : '-';
        }
        
        const serviceUnit = transaction.service?.unit ?? '-';
        const estimatedQty = getTransactionItemsForDisplay(transaction).length > 1 ? `${getTransactionItemsForDisplay(transaction).length} layanan` : formatTransactionQuantity(transaction.weight, serviceUnit);

        const html = `
            <!DOCTYPE html>
            <html lang="id">
            <body style="font-family: 'Courier New', monospace; margin: 0; padding: 12px 14px 24px; color: #000; width: 100%; box-sizing: border-box; height: auto; height: max-content;">
                <div style="text-align: center; margin-bottom: 10px;">
                    <div style="font-size: 20px; font-weight: 700; line-height: 1.2;">${storeName}</div>
                    <div style="font-size: 12px; margin-top: 2px;">${storeContact}</div>
                    <div style="font-size: 12px; margin-top: 1px;">${storeAddress}</div>
                </div>
                <div style="font-size: 12px; color: #555;">
                    <div style="display: flex; justify-content: space-between; gap: 10px; margin-bottom: 4px;">
                        <span>Invoice</span>
                        <span style="font-weight: 700; color: #111;">${invoiceCode}</span>
                    </div>
                    <div style="display: flex; gap: 8px; align-items: flex-start;">
                        <span style="min-width: 54px;">Pelanggan</span>
                        <span style="display: block; flex: 1; min-width: 0; color: #111; white-space: normal; word-break: break-all; overflow-wrap: anywhere; line-height: 1.35;">${customerName}</span>
                    </div>
                </div>
                <div style="border-top: 1px dashed #000; margin: 10px 0 0;"></div>
                <div style="font-size: 13px; padding-top: 6px;">
                    <div style="display: flex; justify-content: space-between; gap: 10px; padding: 5px 0;">
                        <span>Layanan</span>
                        <span style="font-weight: 700; text-align: right;">${serviceName}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; gap: 10px; padding: 5px 0;">
                        <span>Estimasi</span>
                        <span style="font-weight: 700; text-align: right;">${estimatedQty}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; gap: 10px; padding: 5px 0;">
                        <span>Metode Bayar</span>
                        <span style="font-weight: 700; text-align: right;">${paymentMethod}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; gap: 10px; padding: 5px 0;">
                        <span>Status Bayar</span>
                        <span style="font-weight: 700; text-align: right;">${paymentStatus}</span>
                    </div>
                </div>
                <div style="border-top: 1px dashed #000; margin: 10px 0 0;"></div>
                <div style="display: flex; justify-content: space-between; align-items: baseline; gap: 10px; padding: 8px 0 3px;">
                    <span style="font-size: 15px; font-weight: 700;">TOTAL</span>
                    <span style="font-size: 18px; font-weight: 800;">${totalPrice}</span>
                </div>
                <div style="border-top: 1px dashed #000; margin: 10px 0 0;"></div>
                <div style="text-align: center; font-size: 12px; color: #333; padding-top: 6px;">Terima kasih.</div>
            </body>
            </html>
        `;

        printWindow.onload = function () {
            try {
                const desiredHeight = Math.max(420, Math.min(760, printWindow.document.body.scrollHeight + 70));
                printWindow.resizeTo(380, desiredHeight);
            } catch (_) {}
            printWindow.print();
        };
        printWindow.document.open();
        printWindow.document.write(html);
        printWindow.document.close();
    }

    function startAutoRefresh() {
        stopAutoRefresh();
        autoRefreshTimer = setInterval(async () => {
            if (autoRefreshEnabled) {
                await loadTransactionsActive(transactionPageActive);
                await loadTransactionsCompleted(transactionPageCompleted);
            }
        }, 30000);
    }

    function stopAutoRefresh() {
        if (autoRefreshTimer) {
            clearInterval(autoRefreshTimer);
            autoRefreshTimer = null;
        }
    }

    function resetTransactionFilters() {
        document.getElementById('search').value = '';
        document.getElementById('filterStatus').value = '';
        document.getElementById('dateFrom').value = '';
        document.getElementById('dateTo').value = '';
        quickPaymentStatus = '';
        activeQuickFilter = 'all';
        updateQuickFilterButtons();
    }

    async function loadTransactionSummary() {
        if (typeof window.apiFetch !== 'function') return;
        try {
            const summary = await window.apiFetch('/api/transactions/summary');

            document.getElementById('summaryToday').textContent = summary.transactions_today ?? 0;
            document.getElementById('summaryUnpaid').textContent = summary.unpaid_transactions ?? 0;
            document.getElementById('summaryPaid').textContent = summary.paid_transactions ?? 0;
            document.getElementById('summaryReady').textContent = summary.ready_transactions ?? 0;
            document.getElementById('transactionFocusUnpaid').textContent = summary.unpaid_transactions ?? 0;
            document.getElementById('transactionFocusReady').textContent = summary.ready_transactions ?? 0;
            document.getElementById('transactionFocusTotal').textContent = Number(summary.unpaid_transactions ?? 0) + Number(summary.ready_transactions ?? 0);
        } catch (error) {
            console.error("Gagal memuat summary", error);
        }
    }

    async function loadMasterData() {
        if (typeof window.apiFetch !== 'function') return;
        const customerResponse = await window.apiFetch('/api/customers?per_page=100');
        const serviceResponse = await window.apiFetch('/api/services?per_page=100');

        const customerSelect = document.getElementById('customerId');
        customerSelect.innerHTML = '<option value="">Pilih pelanggan</option>' + customerResponse.data
            .map((item) => `<option value="${item.id}">${item.user?.name ?? '-'} - ${item.phone}</option>`)
            .join('');

        serviceMap = {};
        serviceResponse.data.forEach((service) => {
            serviceMap[String(service.id)] = service;
        });

        const serviceSelect = document.getElementById('serviceId');
        serviceSelect.innerHTML = buildServiceOptions();
        document.querySelectorAll('[data-extra-service]').forEach((select) => {
            const selected = select.value;
            select.innerHTML = buildServiceOptions(selected);
        });
        calculateEstimatedTotal();
        updateTransactionSubmitState();
    }

    function renderTransactionRow(transaction, isCompleted = false) {
        const encodedTransaction = encodeURIComponent(JSON.stringify(transaction));
        
        // Tombol bukti hanya muncul untuk transfer.
        let proofButtonHtml = '';
        if (transaction.payment_method === 'transfer') {
            const proofDisabled = transaction.payment_proof_url ? '' : 'disabled';
            const proofClass = transaction.payment_proof_url
                ? 'inline-flex items-center h-8 px-2.5 rounded-md border border-[#D7F3F7] bg-white text-[#00B8CC] hover:bg-[#E6FCFF] text-xs font-semibold transition'
                : 'inline-flex items-center h-8 px-2.5 rounded-md border border-[#E4E7EC] bg-[#F9FAFB] text-[#98A2B3] cursor-not-allowed text-xs font-semibold';
            
            proofButtonHtml = `<button class="${proofClass}" ${proofDisabled} onclick="openPaymentProofFromPayload('${encodedTransaction}')">Bukti</button>`;
        }

        const conditionDisabled = transaction.condition_photo_url ? '' : 'disabled';
        const conditionClass = transaction.condition_photo_url
            ? 'inline-flex items-center h-8 px-2.5 rounded-md border border-[#D7F3F7] bg-white text-[#00B8CC] hover:bg-[#E6FCFF] text-xs font-semibold transition'
            : 'inline-flex items-center h-8 px-2.5 rounded-md border border-[#E4E7EC] bg-[#F9FAFB] text-[#98A2B3] cursor-not-allowed text-xs font-semibold';
        
        let actionButtons = `
            <button class="inline-flex items-center h-8 px-2.5 rounded-md border border-[#D7F3F7] bg-white text-[#344054] hover:bg-[#F7FEFF] text-xs font-semibold transition" onclick="openTransactionDetail('${encodedTransaction}')">Detail</button>
            ${proofButtonHtml}
            <button class="${conditionClass}" ${conditionDisabled} onclick="openConditionPhotoFromPayload('${encodedTransaction}')">Kondisi</button>
        `;

        if (isCompleted) {
            actionButtons += `
                <button class="inline-flex items-center h-8 px-2.5 rounded-md border border-[#FEE2E2] bg-[#FEF2F2] text-[#DC2626] hover:bg-[#FEE2E2] text-xs font-semibold transition" onclick="deleteTransaction(${transaction.id}, 'completedPage')">Hapus</button>
            `;
        } else {
            const paymentActionButton = transaction.payment_status === 'pending'
                ? `<button class="inline-flex items-center h-8 px-2.5 rounded-md border border-[#A7F3D0] bg-[#ECFDF3] text-[#047857] hover:bg-[#D1FAE5] text-xs font-semibold transition" onclick="quickUpdatePayment('${encodedTransaction}', 'paid')">Tandai Lunas</button>`
                : `<button class="inline-flex items-center h-8 px-2.5 rounded-md border border-[#FCD9BD] bg-[#FFF7ED] text-[#B45309] hover:bg-[#FFEDD5] text-xs font-semibold transition" onclick="quickUpdatePayment('${encodedTransaction}', 'pending')">Set Pending</button>`;

            actionButtons += paymentActionButton;
        }

        actionButtons += `
            <button class="inline-flex items-center h-8 px-2.5 rounded-md border border-[#BBF7D0] bg-[#F0FDF4] text-[#15803D] hover:bg-[#DCFCE7] text-xs font-semibold transition" onclick="sendWhatsAppUpdate('${encodedTransaction}')">WA</button>
            <button class="inline-flex items-center h-8 px-2.5 rounded-md border border-[#D7F3F7] bg-white text-[#00B8CC] hover:bg-[#E6FCFF] text-xs font-semibold transition" onclick="printReceiptFromPayload('${encodedTransaction}')">Cetak</button>
        `;

        let formattedPrice = transaction.total_price;
        if(typeof window.formatCurrency === 'function') {
            formattedPrice = window.formatCurrency(transaction.total_price);
        }
        const serviceSummary = formatServiceSummary(transaction);
        const serviceTitle = formatItemLines(transaction).join(', ');

        return `
            <tr class="hover:bg-[#F7FEFF] transition-colors">
                <td class="px-6 py-4 font-medium text-[#0A192F] whitespace-nowrap">${transaction.invoice_code}</td>
                <td class="px-6 py-4 max-w-[220px] truncate text-[#0A192F]" title="${transaction.customer?.user?.name ?? '-'}">${transaction.customer?.user?.name ?? '-'}</td>
                <td class="px-6 py-4 max-w-[160px] truncate text-[#496173]" title="${serviceTitle}">${serviceSummary}</td>
                <td class="px-6 py-4 text-right font-medium text-[#0A192F] whitespace-nowrap">${formattedPrice}</td>
                <td class="px-6 py-4">${isCompleted ? `<span class="inline-flex items-center rounded-full px-3 py-1" style="background:rgba(16,185,129,0.14); color:#047857;"><span class="text-xs font-semibold">${transaction.status}</span></span>` : renderStatusSelect(transaction)}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold capitalize text-[#496173]">${transaction.payment_method}</span>
                        ${getPaymentStatusBadge(transaction.payment_status)}
                    </div>
                </td>
                <td class="px-8 py-4 pr-10">
                    <div class="flex items-center justify-end gap-3 whitespace-nowrap flex-wrap">
                        ${actionButtons}
                    </div>
                </td>
            </tr>
        `;
    }

    function renderTransactionPaginationActive() {
        const wrapper = document.getElementById('transactionPaginationActive');
        wrapper.innerHTML = '';
        if (transactionLastPageActive <= 1) return;

        const prevBtn = document.createElement('button');
        prevBtn.textContent = 'Sebelumnya';
        prevBtn.className = 'px-3 py-1 border border-[#D7F3F7] rounded-lg text-sm bg-white hover:bg-[#F7FEFF] transition';
        prevBtn.disabled = transactionPageActive <= 1;
        prevBtn.onclick = async () => {
            safeToggleLoading(true);
            await loadTransactionsActive(transactionPageActive - 1);
            safeToggleLoading(false);
        };
        wrapper.appendChild(prevBtn);

        const info = document.createElement('span');
        info.className = 'text-sm text-[#496173] px-2 font-semibold';
        info.textContent = `Halaman ${transactionPageActive} / ${transactionLastPageActive}`;
        wrapper.appendChild(info);

        const nextBtn = document.createElement('button');
        nextBtn.textContent = 'Berikutnya';
        nextBtn.className = 'px-3 py-1 border border-[#D7F3F7] rounded-lg text-sm bg-white hover:bg-[#F7FEFF] transition';
        nextBtn.disabled = transactionPageActive >= transactionLastPageActive;
        nextBtn.onclick = async () => {
            safeToggleLoading(true);
            await loadTransactionsActive(transactionPageActive + 1);
            safeToggleLoading(false);
        };
        wrapper.appendChild(nextBtn);
    }

    function renderTransactionPaginationCompleted() {
        const wrapper = document.getElementById('transactionPaginationCompleted');
        wrapper.innerHTML = '';
        if (transactionLastPageCompleted <= 1) return;

        const prevBtn = document.createElement('button');
        prevBtn.textContent = 'Sebelumnya';
        prevBtn.className = 'px-3 py-1 border border-[#D7F3F7] rounded-lg text-sm bg-white hover:bg-[#F7FEFF] transition';
        prevBtn.disabled = transactionPageCompleted <= 1;
        prevBtn.onclick = async () => {
            safeToggleLoading(true);
            await loadTransactionsCompleted(transactionPageCompleted - 1);
            safeToggleLoading(false);
        };
        wrapper.appendChild(prevBtn);

        const info = document.createElement('span');
        info.className = 'text-sm text-[#496173] px-2 font-semibold';
        info.textContent = `Halaman ${transactionPageCompleted} / ${transactionLastPageCompleted}`;
        wrapper.appendChild(info);

        const nextBtn = document.createElement('button');
        nextBtn.textContent = 'Berikutnya';
        nextBtn.className = 'px-3 py-1 border border-[#D7F3F7] rounded-lg text-sm bg-white hover:bg-[#F7FEFF] transition';
        nextBtn.disabled = transactionPageCompleted >= transactionLastPageCompleted;
        nextBtn.onclick = async () => {
            safeToggleLoading(true);
            await loadTransactionsCompleted(transactionPageCompleted + 1);
            safeToggleLoading(false);
        };
        wrapper.appendChild(nextBtn);
    }

    function buildTransactionParams(baseParams) {
        const params = new URLSearchParams(baseParams);
        const filterStatus = document.getElementById('filterStatus').value;
        if (filterStatus) params.append('status', filterStatus);
        
        if (quickPaymentStatus) params.append('payment_status', quickPaymentStatus);
        
        const searchVal = document.getElementById('search').value.trim();
        if (searchVal) params.append('search', searchVal);
        
        const dateFrom = document.getElementById('dateFrom').value;
        if (dateFrom) params.append('date_from', dateFrom);
        
        const dateTo = document.getElementById('dateTo').value;
        if (dateTo) params.append('date_to', dateTo);

        return params;
    }

    async function loadTransactionsActive(page = 1) {
        if (isLoadingActive || typeof window.apiFetch !== 'function') return;
        isLoadingActive = true;

        try {
            const params = buildTransactionParams({
                per_page: '10',
                page: String(page),
                exclude_completed: '1'
            });

            const response = await window.apiFetch(`/api/transactions?${params.toString()}`);
            const activeBody = document.getElementById('transactionTableBodyActive');
            activeBody.innerHTML = '';

            transactionPageActive = response.current_page ?? 1;
            transactionLastPageActive = response.last_page ?? 1;
            document.getElementById('transactionMeta').textContent = `Total ${response.total ?? 0} transaksi`;
            renderTransactionPaginationActive();

            if (!response.data || response.data.length === 0) {
                activeBody.innerHTML = '<tr><td colspan="7" class="px-6 py-8 text-center text-[#496173] font-semibold bg-[#F9FAFB]">Tidak ada transaksi aktif.</td></tr>';
                return;
            }

            response.data.forEach((transaction) => {
                activeBody.innerHTML += renderTransactionRow(transaction, false);
            });
        } catch (error) {
            if(typeof showToast === 'function') showToast(error.message, 'error');
            document.getElementById('transactionTableBodyActive').innerHTML = '<tr><td colspan="7" class="px-6 py-8 text-center text-red-500 font-semibold">Gagal memuat data.</td></tr>';
        } finally {
            isLoadingActive = false;
        }
    }

    async function loadTransactionsCompleted(page = 1) {
        if (isLoadingCompleted || typeof window.apiFetch !== 'function') return;

        const filterStatus = document.getElementById('filterStatus').value;
        if (filterStatus && filterStatus !== 'diambil') {
            document.getElementById('transactionTableBodyCompleted').innerHTML = '<tr><td colspan="7" class="px-6 py-8 text-center text-[#496173] font-semibold bg-[#F9FAFB]">Tidak ada transaksi selesai untuk filter ini.</td></tr>';
            document.getElementById('transactionPaginationCompleted').innerHTML = '';
            return;
        }
        if (quickPaymentStatus && quickPaymentStatus !== 'paid') {
            document.getElementById('transactionTableBodyCompleted').innerHTML = '<tr><td colspan="7" class="px-6 py-8 text-center text-[#496173] font-semibold bg-[#F9FAFB]">Tidak ada transaksi selesai untuk filter ini.</td></tr>';
            document.getElementById('transactionPaginationCompleted').innerHTML = '';
            return;
        }

        isLoadingCompleted = true;

        try {
            const params = buildTransactionParams({
                per_page: '10',
                page: String(page)
            });
            params.set('status', 'diambil');
            params.set('payment_status', 'paid');

            const response = await window.apiFetch(`/api/transactions?${params.toString()}`);
            const completedBody = document.getElementById('transactionTableBodyCompleted');
            completedBody.innerHTML = '';

            transactionPageCompleted = response.current_page ?? 1;
            transactionLastPageCompleted = response.last_page ?? 1;
            renderTransactionPaginationCompleted();

            if (!response.data || response.data.length === 0) {
                completedBody.innerHTML = '<tr><td colspan="7" class="px-6 py-8 text-center text-[#496173] font-semibold bg-[#F9FAFB]">Tidak ada transaksi yang sudah selesai.</td></tr>';
                return;
            }

            response.data.forEach((transaction) => {
                completedBody.innerHTML += renderTransactionRow(transaction, true);
            });
        } catch (error) {
            if(typeof showToast === 'function') showToast(error.message, 'error');
            document.getElementById('transactionTableBodyCompleted').innerHTML = '<tr><td colspan="7" class="px-6 py-8 text-center text-red-500 font-semibold">Gagal memuat data.</td></tr>';
        } finally {
            isLoadingCompleted = false;
        }
    }

    async function deleteTransaction(transactionId, page = 'completedPage') {
        const confirmed = await window.confirmAction({
            title: 'Hapus transaksi?',
            text: 'Invoice, bukti pembayaran, dan foto kondisi barang akan ikut terhapus.',
            confirmText: 'Ya, hapus transaksi',
        });
        if (!confirmed) return;

        safeToggleLoading(true);
        try {
            const response = await window.apiFetch(`/api/transactions/${transactionId}`, {
                method: 'DELETE'
            });

            await window.showSuccessAlert({
                title: 'Transaksi dihapus',
                text: response.message || 'Transaksi berhasil dihapus.',
            });
            await loadTransactionsCompleted(transactionPageCompleted);
            await loadTransactionsActive(transactionPageActive);
            await loadTransactionSummary();
        } catch (error) {
            if(typeof showToast === 'function') showToast(error.message, 'error');
        } finally {
            safeToggleLoading(false);
        }
    }

    async function updateStatus(id, status) {
        safeToggleLoading(true);
        try {
            const response = await window.apiFetch(`/api/transactions/${id}/status`, {
                method: 'PUT',
                body: JSON.stringify({ status })
            });
            await window.showSuccessAlert({
                title: 'Status cucian diperbarui',
                text: response.message,
            });
            
            await loadTransactionsActive(transactionPageActive);
            await loadTransactionsCompleted(transactionPageCompleted);
            await loadTransactionSummary();

            if (status === 'siap diambil') {
                safeToggleLoading(false);
                const shouldSendWa = await window.confirmAction({
                    title: 'Kirim WhatsApp?',
                    text: 'Beritahu pelanggan bahwa laundry sudah siap diambil.',
                    confirmText: 'Kirim WA',
                    icon: 'question',
                });

                if (shouldSendWa) {
                    sendWhatsAppUpdate(encodeURIComponent(JSON.stringify(response.data)));
                }
            }
        } catch (error) {
            if(typeof showToast === 'function') showToast(error.message, 'error');
            await loadTransactionsActive(transactionPageActive);
            await loadTransactionsCompleted(transactionPageCompleted);
        } finally {
            safeToggleLoading(false);
        }
    }

    document.getElementById('paymentMethod').addEventListener('change', (event) => {
        const wrapper = document.getElementById('proofWrapper');
        if (event.target.value === 'transfer') {
            wrapper.classList.remove('hidden');
        } else {
            wrapper.classList.add('hidden');
            clearFilePreview('paymentProof', 'paymentProofPreview', 'paymentProofPreviewImage', 'paymentProofName', 'Pilih bukti transfer');
        }
        if(typeof window.clearInlineErrors === 'function') window.clearInlineErrors('transactionForm');
        updateTransactionSubmitState();
    });

    document.getElementById('customerId').addEventListener('change', () => {
        if(typeof window.clearInlineErrors === 'function') window.clearInlineErrors('transactionForm');
        updateTransactionSubmitState();
    });
    document.getElementById('serviceId').addEventListener('change', () => {
        calculateEstimatedTotal();
        if(typeof window.clearInlineErrors === 'function') window.clearInlineErrors('transactionForm');
        updateTransactionSubmitState();
    });
    document.getElementById('weight').addEventListener('input', () => {
        calculateEstimatedTotal();
        if(typeof window.clearInlineErrors === 'function') window.clearInlineErrors('transactionForm');
        updateTransactionSubmitState();
    });
    document.getElementById('addServiceItemBtn').addEventListener('click', () => {
        addServiceItemRow();
        calculateEstimatedTotal();
    });
    document.getElementById('extraServiceItems').addEventListener('click', (event) => {
        if (!event.target.matches('[data-remove-service-row]')) return;
        event.target.closest('[data-extra-service-row]')?.remove();
        if(typeof window.clearInlineErrors === 'function') window.clearInlineErrors('transactionForm');
        calculateEstimatedTotal();
        updateTransactionSubmitState();
    });
    document.getElementById('extraServiceItems').addEventListener('input', () => {
        calculateEstimatedTotal();
        if(typeof window.clearInlineErrors === 'function') window.clearInlineErrors('transactionForm');
        updateTransactionSubmitState();
    });
    document.getElementById('extraServiceItems').addEventListener('change', () => {
        calculateEstimatedTotal();
        if(typeof window.clearInlineErrors === 'function') window.clearInlineErrors('transactionForm');
        updateTransactionSubmitState();
    });
    document.getElementById('paymentProof').addEventListener('change', () => {
        updateFilePreview('paymentProof', 'paymentProofPreview', 'paymentProofPreviewImage', 'paymentProofName', 'Pilih bukti transfer');
        if(typeof window.clearInlineErrors === 'function') window.clearInlineErrors('transactionForm');
        updateTransactionSubmitState();
    });
    document.getElementById('conditionPhoto').addEventListener('change', () => {
        updateFilePreview('conditionPhoto', 'conditionPhotoPreview', 'conditionPhotoPreviewImage', 'conditionPhotoName', 'Tambah foto kondisi');
        if(typeof window.clearInlineErrors === 'function') window.clearInlineErrors('transactionForm');
        updateTransactionSubmitState();
    });
    document.getElementById('detailPaymentProof').addEventListener('change', (event) => {
        updateFilePreview('detailPaymentProof', 'detailPaymentProofPreview', 'detailPaymentProofPreviewImage', 'detailPaymentProofName', 'Belum pilih file');
        if (event.target.files?.[0] && currentDetailTransaction?.payment_method === 'transfer') {
            document.getElementById('detailPaymentStatus').value = 'paid';
            document.getElementById('detailRemovePaymentProof').checked = false;
        }
    });
    document.getElementById('detailPaymentStatus').addEventListener('change', async (event) => {
        if (event.target.value === 'pending' && currentDetailTransaction?.payment_method === 'transfer' && currentDetailTransaction?.payment_proof_url) {
            await window.showActionAlert({
                title: 'Bukti transfer masih ada',
                text: 'Untuk set pending, centang hapus bukti transfer tersimpan dan isi alasan perubahan.',
                icon: 'warning',
                confirmText: 'Mengerti',
            });
        }
    });
    document.getElementById('detailRemovePaymentProof').addEventListener('change', (event) => {
        if (event.target.checked) {
            document.getElementById('detailPaymentStatus').value = 'pending';
            clearFilePreview('detailPaymentProof', 'detailPaymentProofPreview', 'detailPaymentProofPreviewImage', 'detailPaymentProofName', 'Belum pilih file');
        }
    });
    document.getElementById('detailConditionPhoto').addEventListener('change', (event) => {
        updateFilePreview('detailConditionPhoto', 'detailConditionPhotoPreview', 'detailConditionPhotoPreviewImage', 'detailConditionPhotoName', 'Belum pilih file');
        resetDetailConditionError();
    });

    document.getElementById('closeDetailModalBtn').addEventListener('click', closeTransactionDetail);
    document.getElementById('closeDetailModalSecondaryBtn').addEventListener('click', closeTransactionDetail);
    document.getElementById('closeProofModalBtn').addEventListener('click', closePaymentProof);
    document.getElementById('detailProofPreviewBtn').addEventListener('click', () => {
        if (!currentDetailTransaction) return;
        openPaymentProof(currentDetailTransaction.payment_proof_url, currentDetailTransaction.invoice_code ?? '-');
    });
    document.getElementById('detailConditionPreviewBtn').addEventListener('click', () => {
        if (!currentDetailTransaction) return;
        openConditionPhotoFromPayload(encodeURIComponent(JSON.stringify(currentDetailTransaction)));
    });
    document.getElementById('savePaymentBtn').addEventListener('click', savePaymentFromDetail);
    document.getElementById('saveConditionPhotoBtn').addEventListener('click', saveConditionPhotoFromDetail);

    document.getElementById('transactionDetailModal').addEventListener('click', (event) => {
        if (event.target.id === 'transactionDetailModal') closeTransactionDetail();
    });

    document.getElementById('paymentProofModal').addEventListener('click', (event) => {
        if (event.target.id === 'paymentProofModal') closePaymentProof();
    });

    document.getElementById('transactionForm').addEventListener('submit', async (event) => {
        event.preventDefault();
        if (!validateTransactionForm(true)) {
            if(typeof showToast === 'function') showToast('Lengkapi data transaksi dengan benar.', 'error');
            updateTransactionSubmitState();
            return;
        }

        safeToggleLoading(true);
        transactionSubmitButton.disabled = true;
        transactionSubmitButton.textContent = 'Menyimpan...';
        if(typeof window.clearInlineErrors === 'function') window.clearInlineErrors('transactionForm');

        try {
            const formData = new FormData();
            const items = getTransactionItems();
            formData.append('customer_id', getTrimmedTransactionValue('customerId'));
            formData.append('service_id', items[0].service_id);
            formData.append('weight', items[0].quantity);
            items.forEach((item, index) => {
                formData.append(`items[${index}][service_id]`, item.service_id);
                formData.append(`items[${index}][quantity]`, item.quantity);
            });
            formData.append('payment_method', getTrimmedTransactionValue('paymentMethod'));

            const paymentProof = document.getElementById('paymentProof').files[0];
            if (paymentProof) formData.append('payment_proof', paymentProof);

            const conditionPhoto = document.getElementById('conditionPhoto').files[0];
            if (conditionPhoto) formData.append('condition_photo', conditionPhoto);

            const response = await window.apiFetch('/api/transactions', {
                method: 'POST',
                body: formData
            });

            await window.showSuccessAlert({
                title: 'Transaksi ditambahkan',
                text: response.message,
            });
            document.getElementById('transactionForm').reset();
            document.getElementById('proofWrapper').classList.add('hidden');
            clearTransactionPhotoPreviews();
            if(typeof window.clearInlineErrors === 'function') window.clearInlineErrors('transactionForm');
            calculateEstimatedTotal();
            updateTransactionSubmitState();
            resetTransactionFilters();
            
            await loadTransactionsActive(1);
            await loadTransactionsCompleted(1);
            await loadTransactionSummary();
        } catch (error) {
            if (error.status === 422 && typeof window.applyInlineErrors === 'function') {
                window.applyInlineErrors('transactionForm', error.errors);
            }
            if(typeof showToast === 'function') showToast(error.message, 'error');
        } finally {
            transactionSubmitButton.textContent = 'Simpan Transaksi';
            updateTransactionSubmitState();
            safeToggleLoading(false);
        }
    });

    document.getElementById('applyFilter').addEventListener('click', async () => {
        activeQuickFilter = 'all';
        quickPaymentStatus = '';
        updateQuickFilterButtons();
        
        safeToggleLoading(true);
        await loadTransactionsActive(1);
        await loadTransactionsCompleted(1);
        safeToggleLoading(false);
    });

    document.querySelectorAll('[data-quick-filter]').forEach((button) => {
        button.addEventListener('click', () => applyQuickFilter(button.dataset.quickFilter));
    });

    let searchTimeout;
    document.getElementById('search').addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(async () => {
            safeToggleLoading(true);
            await loadTransactionsActive(1);
            await loadTransactionsCompleted(1);
            safeToggleLoading(false);
        }, 600);
    });

    document.getElementById('autoRefreshBtn').addEventListener('click', () => {
        autoRefreshEnabled = !autoRefreshEnabled;
        document.getElementById('autoRefreshBtn').textContent = `Auto-refresh: ${autoRefreshEnabled ? 'ON' : 'OFF'}`;
        document.getElementById('autoRefreshBtn').className = autoRefreshEnabled
            ? 'h-10 bg-[#E6FCFF] text-[#00B8CC] px-5 rounded-full text-sm font-semibold'
            : 'h-10 bg-[#F2FDFF] text-[#496173] px-5 rounded-full text-sm font-semibold';
    });

    window.addEventListener('beforeunload', stopAutoRefresh);

    (async function init() {
        safeToggleLoading(true);
        
        try {
            updateQuickFilterButtons();
            
            if (typeof window.apiFetch !== 'function') {
                document.getElementById('transactionTableBodyActive').innerHTML = '<tr><td colspan="7" class="px-6 py-6 text-center text-red-500 font-semibold">Error: API Script tidak terdeteksi.</td></tr>';
                return;
            }

            await Promise.all([
                loadMasterData(),
                loadTransactionSummary(),
                loadTransactionsActive(1),
                loadTransactionsCompleted(1),
            ]);
            
            startAutoRefresh();
            updateTransactionSubmitState();
        } catch (error) {
            console.error("Gagal inisialisasi awal:", error);
        } finally {
            safeToggleLoading(false);
        }
    })();
</script>
@endsection








