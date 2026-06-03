<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionConditionPhotoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'condition_photo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'condition_photo.required' => 'Foto kondisi baju wajib diupload.',
            'condition_photo.image' => 'Foto kondisi baju harus berupa gambar.',
            'condition_photo.mimes' => 'Foto kondisi baju hanya boleh format jpeg, png, jpg, atau gif.',
            'condition_photo.max' => 'Ukuran foto kondisi baju tidak boleh lebih dari 2MB.',
        ];
    }
}
