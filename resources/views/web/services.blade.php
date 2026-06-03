@extends('layouts.app')

@section('title', 'Layanan')
@section('page-title', 'Manajemen Layanan')

@section('content')
<div class="space-y-6">
<div class="grid grid-cols-1 gap-4 md:grid-cols-3">
    <div class="rounded-[28px] border border-[#00E5FF] bg-[#0A192F] p-5 text-white shadow-[0_18px_46px_rgba(10,25,47,0.16)]">
        <p class="text-sm font-semibold text-white/70">Total Layanan</p>
        <div class="mt-4 flex items-end justify-between gap-4">
            <h3 id="serviceTotalCard" class="text-3xl font-black leading-none">0</h3>
            <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-[#8EF5FF]">Tersedia</span>
        </div>
    </div>
    <div class="rounded-[28px] border border-[#D7F3F7] bg-white p-5 shadow-[var(--wt-card-shadow)]">
        <p class="text-sm font-semibold text-[#496173]">Mode Form</p>
        <div class="mt-4 flex items-end justify-between gap-4">
            <h3 id="serviceModeCard" class="text-2xl font-black text-[#0A192F]">Tambah</h3>
            <span class="rounded-full bg-[#E6FCFF] px-3 py-1 text-xs font-semibold text-[#00B8CC]">Nama bebas</span>
        </div>
    </div>
    <div class="rounded-[28px] border border-[#D7F3F7] bg-white p-5 shadow-[var(--wt-card-shadow)]">
        <p class="text-sm font-semibold text-[#496173]">Harga</p>
        <div class="mt-4 flex items-end justify-between gap-4">
            <h3 id="serviceAverageCard" class="text-2xl font-black text-[#0A192F]">Rp 0</h3>
            <span class="rounded-full bg-[#F2FDFF] px-3 py-1 text-xs font-semibold text-[#496173]">Rata-rata</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
    <div class="bg-white rounded-[28px] border border-[#D7F3F7] shadow-[var(--wt-card-shadow)] p-6 h-full">
        <div class="mb-5 flex items-start justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-[#0A192F]" id="formTitle">Tambah Layanan</h2>
                <p class="mt-1 text-sm text-[#496173]">Gunakan nama layanan bebas seperti Cuci Kering atau Bed Cover.</p>
            </div>
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#E6FCFF] text-sm font-black text-[#00B8CC]">+</span>
        </div>
        <form id="serviceForm" class="space-y-4">
            <input type="hidden" id="serviceId">
            <div>
                <label class="block text-sm font-medium text-[#496173] mb-1">Nama Layanan</label>
                <input id="serviceName" type="text" required minlength="3" maxlength="20" class="w-full h-10 border border-[#D7F3F7] rounded-lg px-3 text-sm" placeholder="Contoh: Cuci Kering">
                <p data-error-for="service_name" class="hidden text-sm text-red-600 mt-1"></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-[#496173] mb-1">Harga</label>
                <input id="servicePrice" type="number" min="1" step="1" required class="w-full h-10 border border-[#D7F3F7] rounded-lg px-3 text-sm">
                <p data-error-for="price" class="hidden text-sm text-red-600 mt-1"></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-[#496173] mb-1">Satuan</label>
                <select id="serviceUnit" required class="w-full h-10 border border-[#D7F3F7] rounded-lg px-3 text-sm">
                    <option value="">Pilih satuan</option>
                    <option value="Kg">Kg</option>
                    <option value="Pcs">Pcs</option>
                </select>
                <p data-error-for="unit" class="hidden text-sm text-red-600 mt-1"></p>
            </div>
            <div class="flex gap-2 pt-1">
                <button id="serviceSubmitButton" class="h-10 min-h-10 inline-flex items-center justify-center leading-none py-0 bg-[#00E5FF] hover:bg-[#00B8CC] disabled:opacity-50 disabled:cursor-not-allowed text-[#0A192F] px-5 rounded-full text-sm font-semibold transition" type="submit" disabled>Simpan</button>
                <button class="h-10 min-h-10 inline-flex items-center justify-center leading-none py-0 bg-[#F2FDFF] hover:bg-[#DFFBFF] text-[#0A192F] px-5 rounded-full text-sm font-semibold transition" type="button" onclick="resetServiceForm()">Reset</button>
            </div>
        </form>
    </div>

    <div class="lg:col-span-2 bg-white rounded-[28px] border border-[#D7F3F7] shadow-[var(--wt-card-shadow)] overflow-hidden h-full flex flex-col">
        <div class="px-6 py-5 border-b border-[#D7F3F7] flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-[#0A192F]">Daftar Layanan</h3>
                <p class="mt-1 text-sm text-[#496173]">Layanan yang tersedia untuk transaksi laundry.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="text-[#496173] border-b border-[#D7F3F7]">
                    <tr>
                        <th class="px-6 py-3 text-left text-[11px] md:text-xs font-semibold uppercase tracking-wide">Nama Layanan</th>
                        <th class="px-6 py-3 text-right text-[11px] md:text-xs font-semibold uppercase tracking-wide">Harga</th>
                        <th class="px-6 py-3 text-left text-[11px] md:text-xs font-semibold uppercase tracking-wide">Satuan</th>
                        <th class="px-6 py-3 text-left text-[11px] md:text-xs font-semibold uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody id="serviceTableBody" class="divide-y divide-[#D7F3F7]"></tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-[#D7F3F7] flex items-center justify-between gap-4 mt-auto bg-[#F7FEFF]">
            <div id="servicePagination" class="flex items-center gap-2"></div>
            <div id="serviceMeta" class="text-sm text-[#496173]"></div>
        </div>
    </div>
</div>
</div>

<script>
    let servicePage = 1;
    let serviceLastPage = 1;
    const serviceSubmitButton = document.getElementById('serviceSubmitButton');

    function fieldErrorsToInline(errors) {
        return Object.fromEntries(
            Object.entries(errors).map(([key, value]) => [key, [value]])
        );
    }

    function getTrimmedServiceValue(id) {
        return document.getElementById(id).value.trim();
    }

    function validateServiceForm(showErrors = false) {
        const serviceName = getTrimmedServiceValue('serviceName');
        const price = Number(getTrimmedServiceValue('servicePrice'));
        const unit = getTrimmedServiceValue('serviceUnit');
        const errors = {};

        if (serviceName.length < 3) errors.service_name = 'Nama layanan minimal 3 karakter.';
        if (!Number.isFinite(price) || price < 1) errors.price = 'Harga minimal 1.';
        if (!['Kg', 'Pcs'].includes(unit)) errors.unit = 'Satuan wajib dipilih antara Kg atau Pcs.';

        if (showErrors) {
            window.clearInlineErrors('serviceForm');
            window.applyInlineErrors('serviceForm', fieldErrorsToInline(errors));
        }

        return Object.keys(errors).length === 0;
    }

    function updateServiceSubmitState() {
        serviceSubmitButton.disabled = !validateServiceForm(false);
    }

    function toTitleCase(value = '') {
        return String(value)
            .toLowerCase()
            .replace(/\b\w/g, (char) => char.toUpperCase());
    }

    function resetServiceForm() {
        document.getElementById('serviceId').value = '';
        document.getElementById('serviceName').value = '';
        document.getElementById('servicePrice').value = '';
        document.getElementById('serviceUnit').value = '';
        document.getElementById('formTitle').textContent = 'Tambah Layanan';
        document.getElementById('serviceModeCard').textContent = 'Tambah';
        window.clearInlineErrors('serviceForm');
        updateServiceSubmitState();
    }

    function renderServicePagination() {
        const wrapper = document.getElementById('servicePagination');
        wrapper.innerHTML = '';

        if (serviceLastPage <= 1) return;

        const prevBtn = document.createElement('button');
        prevBtn.textContent = 'Sebelumnya';
        prevBtn.className = 'px-3 py-1 border rounded-lg text-sm';
        prevBtn.disabled = servicePage <= 1;
        prevBtn.onclick = () => loadServices(servicePage - 1);
        wrapper.appendChild(prevBtn);

        const info = document.createElement('span');
        info.className = 'text-sm text-[#496173]';
        info.textContent = `Halaman ${servicePage} / ${serviceLastPage}`;
        wrapper.appendChild(info);

        const nextBtn = document.createElement('button');
        nextBtn.textContent = 'Berikutnya';
        nextBtn.className = 'px-3 py-1 border rounded-lg text-sm';
        nextBtn.disabled = servicePage >= serviceLastPage;
        nextBtn.onclick = () => loadServices(servicePage + 1);
        wrapper.appendChild(nextBtn);
    }

    async function loadServices(page = 1) {
        toggleLoading(true);
        try {
            const response = await window.apiFetch(`/api/services?per_page=10&page=${page}`);
            const tbody = document.getElementById('serviceTableBody');
            tbody.innerHTML = '';

            servicePage = response.current_page;
            serviceLastPage = response.last_page;
            document.getElementById('serviceMeta').textContent = `Total ${response.total} data`;
            document.getElementById('serviceTotalCard').textContent = response.total ?? 0;
            const average = response.data.length
                ? response.data.reduce((total, service) => total + Number(service.price || 0), 0) / response.data.length
                : 0;
            document.getElementById('serviceAverageCard').textContent = window.formatCurrency(average);
            renderServicePagination();

            if (!response.data.length) {
                tbody.innerHTML = '<tr><td colspan="4" class="px-6 py-6 text-center text-gray-500">Belum ada layanan.</td></tr>';
                return;
            }

            response.data.forEach((service) => {
                tbody.innerHTML += `
                    <tr>
                        <td class="px-6 py-4">${toTitleCase(service.service_name)}</td>
                        <td class="px-6 py-4 text-right font-medium text-[#0A192F] whitespace-nowrap">${window.formatCurrency(service.price)}</td>
                        <td class="px-6 py-4">${service.unit}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3 whitespace-nowrap">
                            <button class="inline-flex h-8 items-center rounded-full border border-[#D7F3F7] bg-white px-3 text-xs font-semibold text-[#00B8CC] hover:bg-[#E6FCFF]" onclick="editService(${service.id})">Edit</button>
                            <button class="inline-flex h-8 items-center rounded-full border border-red-100 bg-red-50 px-3 text-xs font-semibold text-red-600 hover:bg-red-100" onclick="deleteService(${service.id})">Hapus</button>
                            </div>
                        </td>
                    </tr>
                `;
            });
        } catch (error) {
            showToast(error.message, 'error');
        } finally {
            toggleLoading(false);
        }
    }

    async function editService(id) {
        try {
            const response = await window.apiFetch(`/api/services/${id}`);
            const service = response.data;
            document.getElementById('serviceId').value = service.id;
            document.getElementById('serviceName').value = service.service_name;
            document.getElementById('servicePrice').value = service.price;
            document.getElementById('serviceUnit').value = service.unit;
            document.getElementById('formTitle').textContent = 'Edit Layanan';
            document.getElementById('serviceModeCard').textContent = 'Edit';
            window.clearInlineErrors('serviceForm');
            updateServiceSubmitState();
        } catch (error) {
            showToast(error.message, 'error');
        }
    }

    async function deleteService(id) {
        const confirmed = await window.confirmAction({
            title: 'Hapus layanan?',
            text: 'Layanan yang dihapus tidak bisa dipakai untuk transaksi baru.',
            confirmText: 'Ya, hapus layanan',
        });
        if (!confirmed) return;

        toggleLoading(true);
        try {
            const response = await window.apiFetch(`/api/services/${id}`, { method: 'DELETE' });
            await window.showSuccessAlert({
                title: 'Layanan dihapus',
                text: response.message,
            });
            await loadServices(servicePage);
        } catch (error) {
            showToast(error.message, 'error');
        } finally {
            toggleLoading(false);
        }
    }

    document.getElementById('serviceForm').addEventListener('submit', async (event) => {
        event.preventDefault();
        if (!validateServiceForm(true)) {
            showToast('Lengkapi data layanan dengan benar.', 'error');
            updateServiceSubmitState();
            return;
        }

        toggleLoading(true);
        serviceSubmitButton.disabled = true;
        serviceSubmitButton.textContent = 'Menyimpan...';
        window.clearInlineErrors('serviceForm');

        const id = document.getElementById('serviceId').value;
        const payload = {
            service_name: getTrimmedServiceValue('serviceName'),
            price: Number(getTrimmedServiceValue('servicePrice')),
            unit: getTrimmedServiceValue('serviceUnit')
        };

        try {
            const response = await window.apiFetch(
                id ? `/api/services/${id}` : '/api/services',
                {
                    method: id ? 'PUT' : 'POST',
                    body: JSON.stringify(payload)
                }
            );

            await window.showSuccessAlert({
                title: id ? 'Layanan diperbarui' : 'Layanan ditambahkan',
                text: response.message,
            });
            resetServiceForm();
            await loadServices(id ? servicePage : 1);
        } catch (error) {
            if (error.status === 422) {
                window.applyInlineErrors('serviceForm', error.errors);
            }
            showToast(error.message, 'error');
        } finally {
            serviceSubmitButton.textContent = 'Simpan';
            updateServiceSubmitState();
            toggleLoading(false);
        }
    });

    ['serviceName', 'servicePrice', 'serviceUnit'].forEach((id) => {
        document.getElementById(id).addEventListener('input', () => {
            window.clearInlineErrors('serviceForm');
            updateServiceSubmitState();
        });
        document.getElementById(id).addEventListener('change', () => {
            window.clearInlineErrors('serviceForm');
            updateServiceSubmitState();
        });
    });

    loadServices();
    updateServiceSubmitState();
</script>
@endsection



