<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateApiProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

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

    public function rules(): array
    {
        $userId = $this->user()?->id;

        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => ['nullable', 'string', 'min:6', 'max:255'],
            'phone' => [
                Rule::requiredIf(fn () => $this->user()?->role === 'customer'),
                'nullable',
                'string',
                'min:10',
                'max:15',
            ],
            'address' => [
                Rule::requiredIf(fn () => $this->user()?->role === 'customer'),
                'nullable',
                'string',
                'min:5',
                'max:500',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.min' => 'Nama minimal 3 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan akun lain.',
            'password.min' => 'Password minimal 6 karakter.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.min' => 'Nomor telepon minimal 10 karakter.',
            'phone.max' => 'Nomor telepon tidak boleh lebih dari 15 karakter.',
            'address.required' => 'Alamat wajib diisi.',
            'address.min' => 'Alamat minimal 5 karakter.',
        ];
    }
}
