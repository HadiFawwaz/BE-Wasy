<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionPaymentRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'payment_status' => is_string($this->input('payment_status')) ? trim($this->input('payment_status')) : $this->input('payment_status'),
            'payment_reason' => is_string($this->input('payment_reason')) ? trim($this->input('payment_reason')) : $this->input('payment_reason'),
            'remove_payment_proof' => $this->boolean('remove_payment_proof'),
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_status' => 'required|in:pending,paid',
            'payment_reason' => 'required|string|min:5|max:255',
            'remove_payment_proof' => 'nullable|boolean',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'payment_status.required' => 'Status pembayaran wajib diisi.',
            'payment_status.in' => 'Status pembayaran hanya boleh pending atau paid.',
            'payment_reason.required' => 'Alasan perubahan pembayaran wajib diisi.',
            'payment_reason.min' => 'Alasan perubahan pembayaran minimal 5 karakter.',
            'payment_reason.max' => 'Alasan perubahan pembayaran maksimal 255 karakter.',
            'payment_proof.image' => 'Bukti pembayaran harus berupa gambar.',
            'payment_proof.mimes' => 'Bukti pembayaran hanya boleh format jpeg, png, jpg, atau gif.',
            'payment_proof.max' => 'Ukuran bukti pembayaran tidak boleh lebih dari 2MB.',
        ];
    }
}
