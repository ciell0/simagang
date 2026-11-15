<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreLowonganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string', 'max:10000'],
            'divisi' => ['required', 'string', 'max:100'],
            'kuota' => ['required', 'integer', 'min:1', 'max:65535'],
            'deadline' => ['required', 'date', 'after_or_equal:today'],
            'status' => ['required', 'in:buka,tutup'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'judul.required' => 'Judul lowongan wajib diisi.',
            'deskripsi.required' => 'Deskripsi lowongan wajib diisi.',
            'divisi.required' => 'Divisi wajib diisi.',
            'kuota.required' => 'Kuota wajib diisi.',
            'kuota.integer' => 'Kuota harus berupa bilangan bulat.',
            'kuota.min' => 'Kuota minimal satu orang.',
            'deadline.required' => 'Batas pendaftaran wajib diisi.',
            'deadline.after_or_equal' => 'Batas pendaftaran tidak boleh sebelum hari ini.',
            'status.required' => 'Status lowongan wajib dipilih.',
            'status.in' => 'Status lowongan tidak valid.',
        ];
    }
}
