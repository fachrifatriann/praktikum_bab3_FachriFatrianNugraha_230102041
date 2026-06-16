<?php
// app/Http/Requests/StoreProductRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{

    // Aturan validasi
    public function rules(): array
    {
        return [
        'name'        =>    'required|string|min:3|max:200|unique:products,name',
         'category_id' => 'required|exists:categories,id',
         'price'       => 'required|numeric|min:0',
         'stock'       => 'required|integer|min:0',
         'description' => 'nullable|string|max:5000',
         'status'      => 'required|in:active,inactive,draft',
         'image'       => 'nullable|image|mimes:jpeg,png,webp|max:2048',
       ];
    }

    // Pesan error kustom (opsional)
    public function messages(): array
    {
        return [
            'name.required'    => 'Nama produk wajib diisi.',
            'name.unique'      => 'Nama produk sudah digunakan.',
            'price.required'   => 'Harga produk wajib diisi.',
            'price.numeric'    => 'Harga harus berupa angka.',
            'image.image'      => 'File harus berupa gambar.',
            'image.max'        => 'Ukuran gambar maksimal 2MB.',
        ];
    }

    // Modifikasi data sebelum validasi (opsional)
    protected function prepareForValidation(): void
    {
        $this->merge(['slug' => str($this->name)->slug()]);
    }
}
