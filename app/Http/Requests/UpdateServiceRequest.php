<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $unit = is_string($this->input('unit')) ? trim($this->input('unit')) : $this->input('unit');
        $normalizedUnit = match (strtolower((string) $unit)) {
            'kg' => 'Kg',
            'pcs' => 'Pcs',
            default => $unit,
        };

        $this->merge([
            'service_name' => is_string($this->input('service_name')) ? trim($this->input('service_name')) : $this->input('service_name'),
            'unit' => $normalizedUnit,
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_name' => 'required|string|min:3|max:20',
            'price' => 'required|numeric|min:1',
            'unit' => 'required|in:Kg,Pcs',
        ];
    }

    public function messages(): array
    {
        return [
            'service_name.required' => 'Nama layanan wajib diisi.',
            'service_name.string' => 'Nama layanan harus berupa teks.',
            'service_name.min' => 'Nama layanan minimal 3 karakter.',
            'service_name.max' => 'Nama layanan tidak boleh lebih dari 20 karakter.',
            'price.required' => 'Harga wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga minimal 1.',
            'unit.required' => 'Satuan wajib diisi.',
            'unit.in' => 'Satuan hanya boleh Kg atau Pcs.',
        ];
    }
}
