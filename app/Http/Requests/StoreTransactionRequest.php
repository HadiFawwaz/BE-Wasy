<?php

namespace App\Http\Requests;

use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreTransactionRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $items = $this->input('items');

        if (is_array($items)) {
            $items = array_values(array_map(function ($item) {
                return [
                    'service_id' => is_string($item['service_id'] ?? null) ? trim($item['service_id']) : ($item['service_id'] ?? null),
                    'quantity' => is_string($item['quantity'] ?? null) ? trim($item['quantity']) : ($item['quantity'] ?? null),
                ];
            }, $items));
        }

        $this->merge([
            'customer_id' => is_string($this->input('customer_id')) ? trim($this->input('customer_id')) : $this->input('customer_id'),
            'service_id' => is_string($this->input('service_id')) ? trim($this->input('service_id')) : $this->input('service_id'),
            'weight' => is_string($this->input('weight')) ? trim($this->input('weight')) : $this->input('weight'),
            'items' => $items,
            'payment_method' => is_string($this->input('payment_method')) ? trim($this->input('payment_method')) : $this->input('payment_method'),
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'service_id' => 'required_without:items|exists:services,id',
            'weight' => 'required_without:items|numeric|min:1',
            'items' => 'nullable|array|min:1|max:20',
            'items.*.service_id' => 'required_with:items|exists:services,id',
            'items.*.quantity' => 'required_with:items|numeric|min:1',
            'payment_method' => 'required|in:cash,transfer',
            'payment_proof' => 'required_if:payment_method,transfer|image|mimes:jpeg,png,jpg,gif|max:2048',
            'condition_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'ID pelanggan wajib diisi.',
            'customer_id.exists' => 'ID pelanggan tidak ditemukan.',
            'service_id.required_without' => 'ID layanan wajib diisi.',
            'service_id.exists' => 'ID layanan tidak ditemukan.',
            'weight.required_without' => 'Berat wajib diisi.',
            'weight.numeric' => 'Berat harus berupa angka.',
            'weight.min' => 'Berat minimal 1.',
            'items.array' => 'Daftar layanan harus berupa data yang valid.',
            'items.min' => 'Minimal pilih satu layanan.',
            'items.max' => 'Maksimal 20 layanan dalam satu transaksi.',
            'items.*.service_id.required_with' => 'Layanan wajib dipilih.',
            'items.*.service_id.exists' => 'Layanan tidak ditemukan.',
            'items.*.quantity.required_with' => 'Berat/jumlah wajib diisi.',
            'items.*.quantity.numeric' => 'Berat/jumlah harus berupa angka.',
            'items.*.quantity.min' => 'Berat/jumlah minimal 1.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'payment_method.in' => 'Metode pembayaran hanya boleh cash atau transfer.',
            'payment_proof.required_if' => 'Bukti pembayaran wajib diupload jika memilih metode transfer.',
            'payment_proof.image' => 'Bukti pembayaran harus berupa gambar.',
            'payment_proof.mimes' => 'Bukti pembayaran hanya boleh format jpeg, png, jpg, atau gif.',
            'payment_proof.max' => 'Ukuran bukti pembayaran tidak boleh lebih dari 2MB.',
            'condition_photo.image' => 'Foto kondisi baju harus berupa gambar.',
            'condition_photo.mimes' => 'Foto kondisi baju hanya boleh format jpeg, png, jpg, atau gif.',
            'condition_photo.max' => 'Ukuran foto kondisi baju tidak boleh lebih dari 2MB.',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $items = $this->input('items');

                if (is_array($items) && count($items) > 0) {
                    $seenServices = [];

                    foreach ($items as $index => $item) {
                        $serviceId = $item['service_id'] ?? null;
                        $quantity = (float) ($item['quantity'] ?? 0);
                        $service = $serviceId ? Service::find($serviceId) : null;

                        if (!$service) {
                            continue;
                        }

                        if (in_array((string) $serviceId, $seenServices, true)) {
                            $validator->errors()->add("items.$index.service_id", 'Layanan yang sama tidak perlu ditambahkan dua kali. Ubah jumlahnya saja.');
                        }

                        $seenServices[] = (string) $serviceId;

                        if (strtolower($service->unit) === 'pcs' && $quantity !== floor($quantity)) {
                            $validator->errors()->add("items.$index.quantity", 'Jumlah untuk unit Pcs harus bilangan bulat.');
                        }
                    }

                    return;
                }

                $service = Service::find($this->input('service_id'));

                if (!$service || strtolower($service->unit) !== 'pcs') {
                    return;
                }

                $weight = (float) $this->input('weight');

                if ($weight !== floor($weight)) {
                    $validator->errors()->add('weight', 'Jumlah untuk unit Pcs harus bilangan bulat.');
                }
            },
        ];
    }
}
