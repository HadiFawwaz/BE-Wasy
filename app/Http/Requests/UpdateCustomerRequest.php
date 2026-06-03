<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => is_string($this->input('name')) ? trim($this->input('name')) : $this->input('name'),
            'email' => is_string($this->input('email')) ? trim($this->input('email')) : $this->input('email'),
            'password' => is_string($this->input('password')) ? trim($this->input('password')) : $this->input('password'),
            'phone' => is_string($this->input('phone')) ? trim($this->input('phone')) : $this->input('phone'),
            'address' => is_string($this->input('address')) ? trim($this->input('address')) : $this->input('address'),
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $customer = $this->route('customer');
        $userId = $customer?->user_id;

        return [
            'name' => 'required|string|min:3|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'password' => 'nullable|string|min:6',
            'phone' => 'required|string|min:10|max:15',
            'address' => 'required|string|min:5|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama pelanggan wajib diisi.',
            'name.min' => 'Nama pelanggan minimal 3 karakter.',
            'name.max' => 'Nama pelanggan tidak boleh lebih dari 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email harus format yang valid.',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
            'email.unique' => 'Email sudah terdaftar di sistem.',
            'password.min' => 'Password minimal 6 karakter.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.min' => 'Nomor telepon minimal 10 karakter.',
            'phone.max' => 'Nomor telepon tidak boleh lebih dari 15 karakter.',
            'address.required' => 'Alamat wajib diisi.',
            'address.min' => 'Alamat minimal 5 karakter.',
            'address.max' => 'Alamat tidak boleh lebih dari 500 karakter.',
        ];
    }

    public function after(): array
    {
        return [
            function ($validator): void {
                $customer = $this->route('customer');
                $exists = User::query()
                    ->where('name', $this->input('name'))
                    ->where('email', $this->input('email'))
                    ->when($customer?->user_id, fn ($query, $userId) => $query->where('id', '!=', $userId))
                    ->exists();

                if ($exists) {
                    $validator->errors()->add('email', 'Nama dan email pelanggan sudah terdaftar.');
                }
            },
        ];
    }
}
