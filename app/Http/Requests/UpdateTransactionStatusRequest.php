<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionStatusRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'status' => is_string($this->input('status')) ? trim($this->input('status')) : $this->input('status'),
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }

    // Validasi status cucian.
    public function rules(): array
    {
        return [
            'status' => 'required|in:antrian,dicuci,disetrika,siap diambil,diambil'
        ];
    }

    // Pesan error status cucian.
    public function messages(): array
    {
        return [
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status hanya boleh: antrian, dicuci, disetrika, siap diambil, atau diambil.',
        ];
    }
}
