<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_menu' => ['required', 'string', 'max:100'],
            'kategori' => ['required', Rule::in(['Kopi', 'Non-Kopi', 'Makanan', 'Dessert'])],
            'harga' => ['required', 'integer', 'min:1', 'max:9090909090'],
            'status_ketersediaan' => ['required', Rule::in(['tersedia', 'habis'])],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'hapus_foto' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_menu.required' => 'Nama menu wajib diisi.',
            'kategori.required' => 'Kategori menu wajib dipilih.',
            'kategori.in' => 'Kategori menu tidak valid.',
            'harga.required' => 'Harga menu wajib diisi.',
            'harga.integer' => 'Harga menu harus berupa Rupiah utuh tanpa pecahan.',
            'harga.min' => 'Harga menu minimal Rp 1.',
            'harga.max' => 'Harga menu maksimal Rp 9.090.909.090 agar total setelah PPN dapat disimpan.',
            'status_ketersediaan.required' => 'Status ketersediaan wajib dipilih.',
            'status_ketersediaan.in' => 'Status ketersediaan tidak valid.',
            'foto.image' => 'Foto menu harus berupa gambar.',
            'foto.mimes' => 'Foto menu harus berformat JPG, PNG, atau WebP.',
            'foto.max' => 'Ukuran foto menu maksimal 2 MB.',
        ];
    }
}
