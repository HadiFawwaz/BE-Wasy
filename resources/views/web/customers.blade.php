@extends('layouts.app')

@section('title', 'Pelanggan')
@section('page-title', 'Manajemen Pelanggan')

@section('content')
<style>
    #customerForm input:-webkit-autofill,
    #customerForm input:-webkit-autofill:hover,
    #customerForm input:-webkit-autofill:focus {
        -webkit-box-shadow: 0 0 0 1000px #ffffff inset !important;
        -webkit-text-fill-color: #0A192F !important;
        caret-color: #0A192F;
        transition: background-color 9999s ease-out 0s;
    }
</style>

<div class="space-y-6">
<div class="grid grid-cols-1 gap-4 md:grid-cols-3">
    <div class="rounded-[28px] border border-[#00E5FF] bg-[#0A192F] p-5 text-white shadow-[0_18px_46px_rgba(10,25,47,0.16)]">
        <p class="text-sm font-semibold text-white/70">Total Pelanggan</p>
        <div class="mt-4 flex items-end justify-between gap-4">
            <h3 id="customerTotalCard" class="text-3xl font-black leading-none">0</h3>
            <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-[#8EF5FF]">Aktif</span>
        </div>
    </div>
    <div class="rounded-[28px] border border-[#D7F3F7] bg-white p-5 shadow-[var(--wt-card-shadow)]">
        <p class="text-sm font-semibold text-[#496173]">Mode Form</p>
        <div class="mt-4 flex items-end justify-between gap-4">
            <h3 id="customerModeCard" class="text-2xl font-black text-[#0A192F]">Tambah</h3>
            <span class="rounded-full bg-[#E6FCFF] px-3 py-1 text-xs font-semibold text-[#00B8CC]">Validasi aktif</span>
        </div>
    </div>
    <div class="rounded-[28px] border border-[#D7F3F7] bg-white p-5 shadow-[var(--wt-card-shadow)]">
        <p class="text-sm font-semibold text-[#496173]">Pencarian</p>
        <div class="mt-4 flex items-end justify-between gap-4">
            <h3 id="customerSearchState" class="text-2xl font-black text-[#0A192F]">Semua</h3>
            <span class="rounded-full bg-[#F2FDFF] px-3 py-1 text-xs font-semibold text-[#496173]">Realtime</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
    <div class="bg-white rounded-[28px] border border-[#D7F3F7] shadow-[var(--wt-card-shadow)] p-6 h-full">
        <div class="mb-5 flex items-start justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-[#0A192F]" id="formTitle">Tambah Pelanggan</h2>
                <p class="mt-1 text-sm text-[#496173]">Data ini dipakai untuk transaksi dan notifikasi WhatsApp.</p>
            </div>
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#E6FCFF] text-sm font-black text-[#00B8CC]">+</span>
        </div>
        <form id="customerForm" class="space-y-4">
            <input type="hidden" id="customerId">
            <div>
                <label class="block text-sm font-medium text-[#496173] mb-1">Nama</label>
                <input id="customerName" type="text" required minlength="3" maxlength="255" class="w-full h-10 border border-[#D7F3F7] rounded-lg px-3 text-sm">
                <p data-error-for="name" class="hidden text-sm text-red-600 mt-1"></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-[#496173] mb-1">Email</label>
                <input id="customerEmail" type="email" required maxlength="255" class="w-full h-10 border border-[#D7F3F7] rounded-lg px-3 text-sm">
                <p data-error-for="email" class="hidden text-sm text-red-600 mt-1"></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-[#496173] mb-1">Password</label>
                <input id="customerPassword" type="password" minlength="6" class="w-full h-10 border border-[#D7F3F7] rounded-lg px-3 text-sm" placeholder="Wajib saat tambah, opsional saat edit">
                <p data-error-for="password" class="hidden text-sm text-red-600 mt-1"></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-[#496173] mb-1">No HP</label>
                <input id="customerPhone" type="text" required minlength="10" maxlength="15" class="w-full h-10 border border-[#D7F3F7] rounded-lg px-3 text-sm">
                <p data-error-for="phone" class="hidden text-sm text-red-600 mt-1"></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-[#496173] mb-1">Alamat</label>
                <textarea id="customerAddress" required minlength="5" maxlength="500" class="w-full min-h-[86px] border border-[#D7F3F7] rounded-lg px-3 py-2 text-sm"></textarea>
                <p data-error-for="address" class="hidden text-sm text-red-600 mt-1"></p>
            </div>
            <div class="flex gap-2 pt-1">
                <button id="customerSubmitButton" class="h-10 min-h-10 inline-flex items-center justify-center leading-none py-0 bg-[#00E5FF] hover:bg-[#00B8CC] disabled:opacity-50 disabled:cursor-not-allowed text-[#0A192F] px-5 rounded-full text-sm font-semibold transition" type="submit" disabled>Simpan</button>
                <button class="h-10 min-h-10 inline-flex items-center justify-center leading-none py-0 bg-[#F2FDFF] hover:bg-[#DFFBFF] text-[#0A192F] px-5 rounded-full text-sm font-semibold transition" type="button" onclick="resetCustomerForm()">Reset</button>
            </div>
        </form>
    </div>

    <div class="lg:col-span-2 bg-white rounded-[28px] border border-[#D7F3F7] shadow-[var(--wt-card-shadow)] overflow-hidden h-full flex flex-col">
        <div class="px-6 py-5 border-b border-[#D7F3F7] min-h-[72px] flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-bold text-[#0A192F] leading-none">Daftar Pelanggan</h3>
                <p class="mt-1 text-sm text-[#496173]">Cari dan edit data pelanggan aktif.</p>
            </div>
            <input id="customerSearch" type="text" placeholder="Cari nama/no hp..." autocomplete="off" class="h-10 self-center border border-[#D7F3F7] rounded-full bg-[#F7FEFF] px-4 text-sm w-full sm:w-64">
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full table-fixed text-sm">
                <thead class="text-[#496173] border-b border-[#D7F3F7]">
                    <tr>
                        <th class="w-[30%] px-6 py-3 text-left text-[11px] md:text-xs font-semibold uppercase tracking-wide">Nama</th>
                        <th class="w-[32%] px-6 py-3 text-left text-[11px] md:text-xs font-semibold uppercase tracking-wide">Email</th>
                        <th class="w-[20%] px-6 py-3 text-left text-[11px] md:text-xs font-semibold uppercase tracking-wide">No HP</th>
                        <th class="px-6 py-3 text-left text-[11px] md:text-xs font-semibold uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody id="customerTableBody" class="divide-y divide-[#D7F3F7]"></tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-[#D7F3F7] flex items-center justify-between gap-4 mt-auto bg-[#F7FEFF]">
            <div id="customerPagination" class="flex items-center gap-2"></div>
            <div id="customerMeta" class="text-sm text-[#496173]"></div>
        </div>
    </div>
</div>
</div>

<script>
    let customerPage = 1;
    let customerLastPage = 1;
    const customerSubmitButton = document.getElementById('customerSubmitButton');

    function fieldErrorsToInline(errors) {
        return Object.fromEntries(
            Object.entries(errors).map(([key, value]) => [key, [value]])
        );
    }

    function getTrimmedCustomerValue(id) {
        return document.getElementById(id).value.trim();
    }

    function validateCustomerForm(showErrors = false) {
        const id = document.getElementById('customerId').value;
        const values = {
            name: getTrimmedCustomerValue('customerName'),
            email: getTrimmedCustomerValue('customerEmail'),
            password: getTrimmedCustomerValue('customerPassword'),
            phone: getTrimmedCustomerValue('customerPhone'),
            address: getTrimmedCustomerValue('customerAddress')
        };
        const errors = {};

        if (values.name.length < 3) errors.name = 'Nama pelanggan minimal 3 karakter.';
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(values.email)) errors.email = 'Email harus format yang valid.';
        if (!id && values.password.length < 6) errors.password = 'Password minimal 6 karakter.';
        if (id && values.password.length > 0 && values.password.length < 6) errors.password = 'Password minimal 6 karakter.';
        if (values.phone.length < 10) errors.phone = 'Nomor telepon minimal 10 karakter.';
        if (values.phone.length > 15) errors.phone = 'Nomor telepon tidak boleh lebih dari 15 karakter.';
        if (values.address.length < 5) errors.address = 'Alamat minimal 5 karakter.';

        if (showErrors) {
            window.clearInlineErrors('customerForm');
            window.applyInlineErrors('customerForm', fieldErrorsToInline(errors));
        }

        return Object.keys(errors).length === 0;
    }

    function updateCustomerSubmitState() {
        customerSubmitButton.disabled = !validateCustomerForm(false);
    }

    function resetCustomerForm() {
        document.getElementById('customerId').value = '';
        document.getElementById('customerName').value = '';
        document.getElementById('customerEmail').value = '';
        document.getElementById('customerPassword').value = '';
        document.getElementById('customerPhone').value = '';
        document.getElementById('customerAddress').value = '';
        document.getElementById('formTitle').textContent = 'Tambah Pelanggan';
        document.getElementById('customerModeCard').textContent = 'Tambah';
        window.clearInlineErrors('customerForm');
        updateCustomerSubmitState();
    }

    function resetCustomerSearch() {
        document.getElementById('customerSearch').value = '';
    }

    function renderCustomerPagination() {
        const wrapper = document.getElementById('customerPagination');
        wrapper.innerHTML = '';

        if (customerLastPage <= 1) return;

        const prevBtn = document.createElement('button');
        prevBtn.textContent = 'Sebelumnya';
        prevBtn.className = 'px-3 py-1 border rounded-lg text-sm';
        prevBtn.disabled = customerPage <= 1;
        prevBtn.onclick = () => loadCustomers(customerPage - 1);
        wrapper.appendChild(prevBtn);

        const info = document.createElement('span');
        info.className = 'text-sm text-[#496173]';
        info.textContent = `Halaman ${customerPage} / ${customerLastPage}`;
        wrapper.appendChild(info);

        const nextBtn = document.createElement('button');
        nextBtn.textContent = 'Berikutnya';
        nextBtn.className = 'px-3 py-1 border rounded-lg text-sm';
        nextBtn.disabled = customerPage >= customerLastPage;
        nextBtn.onclick = () => loadCustomers(customerPage + 1);
        wrapper.appendChild(nextBtn);
    }

    async function loadCustomers(page = 1) {
        toggleLoading(true);
        try {
            const search = document.getElementById('customerSearch').value.trim();
            const response = await window.apiFetch(`/api/customers?per_page=10&page=${page}&search=${encodeURIComponent(search)}`);
            const tbody = document.getElementById('customerTableBody');
            tbody.innerHTML = '';

            customerPage = response.current_page;
            customerLastPage = response.last_page;
            document.getElementById('customerMeta').textContent = `Total ${response.total} data`;
            document.getElementById('customerTotalCard').textContent = response.total ?? 0;
            document.getElementById('customerSearchState').textContent = search ? 'Filter' : 'Semua';
            renderCustomerPagination();

            if (!response.data.length) {
                tbody.innerHTML = `<tr><td colspan="4" class="px-6 py-6 text-center text-gray-500">${
                    search ? 'Data pelanggan tidak ditemukan untuk pencarian ini.' : 'Belum ada pelanggan.'
                }</td></tr>`;
                return;
            }

            response.data.forEach((customer) => {
                tbody.innerHTML += `
                    <tr>
                        <td class="px-6 py-4">
                            <span class="block max-w-[220px] truncate" title="${customer.user?.name ?? '-'}">${customer.user?.name ?? '-'}</span>
                        </td>
                        <td class="px-6 py-4 truncate" title="${customer.user?.email ?? '-'}">${customer.user?.email ?? '-'}</td>
                        <td class="px-6 py-4 truncate">${customer.phone}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                            <button class="inline-flex h-8 items-center rounded-full border border-[#D7F3F7] bg-white px-3 text-xs font-semibold text-[#00B8CC] hover:bg-[#E6FCFF]" onclick="editCustomer(${customer.id})">Edit</button>
                            <button class="inline-flex h-8 items-center rounded-full border border-red-100 bg-red-50 px-3 text-xs font-semibold text-red-600 hover:bg-red-100" onclick="deleteCustomer(${customer.id})">Hapus</button>
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

    async function editCustomer(id) {
        try {
            const response = await window.apiFetch(`/api/customers/${id}`);
            const customer = response.data;
            document.getElementById('customerId').value = customer.id;
            document.getElementById('customerName').value = customer.user?.name ?? '';
            document.getElementById('customerEmail').value = customer.user?.email ?? '';
            document.getElementById('customerPassword').value = '';
            document.getElementById('customerPhone').value = customer.phone;
            document.getElementById('customerAddress').value = customer.address;
            document.getElementById('formTitle').textContent = 'Edit Pelanggan';
            document.getElementById('customerModeCard').textContent = 'Edit';
            window.clearInlineErrors('customerForm');
            updateCustomerSubmitState();
        } catch (error) {
            showToast(error.message, 'error');
        }
    }

    async function deleteCustomer(id) {
        const confirmed = await window.confirmAction({
            title: 'Hapus pelanggan?',
            text: 'Akun pelanggan dan data terkait akan dihapus dari sistem.',
            confirmText: 'Ya, hapus pelanggan',
        });
        if (!confirmed) return;

        toggleLoading(true);
        try {
            const response = await window.apiFetch(`/api/customers/${id}`, { method: 'DELETE' });
            await window.showSuccessAlert({
                title: 'Pelanggan dihapus',
                text: response.message,
            });
            await loadCustomers(customerPage);
        } catch (error) {
            showToast(error.message, 'error');
        } finally {
            toggleLoading(false);
        }
    }

    document.getElementById('customerForm').addEventListener('submit', async (event) => {
        event.preventDefault();
        if (!validateCustomerForm(true)) {
            showToast('Lengkapi data pelanggan dengan benar.', 'error');
            updateCustomerSubmitState();
            return;
        }

        toggleLoading(true);
        customerSubmitButton.disabled = true;
        customerSubmitButton.textContent = 'Menyimpan...';
        window.clearInlineErrors('customerForm');

        const id = document.getElementById('customerId').value;
        const payload = {
            name: getTrimmedCustomerValue('customerName'),
            email: getTrimmedCustomerValue('customerEmail'),
            phone: getTrimmedCustomerValue('customerPhone'),
            address: getTrimmedCustomerValue('customerAddress')
        };

        const password = getTrimmedCustomerValue('customerPassword');
        if (!id || password) payload.password = password;

        try {
            const response = await window.apiFetch(
                id ? `/api/customers/${id}` : '/api/customers',
                {
                    method: id ? 'PUT' : 'POST',
                    body: JSON.stringify(payload)
                }
            );

            await window.showSuccessAlert({
                title: id ? 'Pelanggan diperbarui' : 'Pelanggan ditambahkan',
                text: response.message,
            });
            resetCustomerForm();
            if (!id) {
                resetCustomerSearch();
                await loadCustomers(1);
            } else {
                await loadCustomers(customerPage);
            }
        } catch (error) {
            if (error.status === 422) {
                window.applyInlineErrors('customerForm', error.errors);
            }
            showToast(error.message, 'error');
        } finally {
            customerSubmitButton.textContent = 'Simpan';
            updateCustomerSubmitState();
            toggleLoading(false);
        }
    });

    ['customerName', 'customerEmail', 'customerPassword', 'customerPhone', 'customerAddress'].forEach((id) => {
        document.getElementById(id).addEventListener('input', () => {
            window.clearInlineErrors('customerForm');
            updateCustomerSubmitState();
        });
    });

    document.getElementById('customerSearch').addEventListener('input', () => {
        loadCustomers(1);
    });

    loadCustomers();
    updateCustomerSubmitState();
</script>
@endsection



