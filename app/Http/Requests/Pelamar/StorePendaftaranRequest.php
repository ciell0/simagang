<?php

namespace App\Http\Requests\Pelamar;

use Illuminate\Foundation\Http\FormRequest;

class StorePendaftaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'pelamar';
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'ipk' => ['required', 'numeric', 'between:0,4'],
            'semester' => ['required', 'integer', 'between:1,14'],
            'pengalaman_organisasi' => ['required', 'integer', 'between:0,255'],
            'cv' => ['required', 'file', 'mimes:pdf', 'mimetypes:application/pdf', 'max:2048'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'ipk.required' => 'IPK wajib diisi.',
            'ipk.numeric' => 'IPK harus berupa angka.',
            'ipk.between' => 'IPK harus berada di antara 0,00 dan 4,00.',
            'semester.required' => 'Semester wajib diisi.',
            'semester.integer' => 'Semester harus berupa bilangan bulat.',
            'semester.between' => 'Semester harus berada di antara 1 dan 14.',
            'pengalaman_organisasi.required' => 'Lama pengalaman organisasi wajib diisi.',
            'pengalaman_organisasi.integer' => 'Pengalaman organisasi harus berupa bilangan bulat dalam bulan.',
            'pengalaman_organisasi.between' => 'Pengalaman organisasi harus berada di antara 0 dan 255 bulan.',
            'cv.required' => 'Berkas CV wajib diunggah.',
            'cv.mimes' => 'Berkas CV harus berformat PDF.',
            'cv.mimetypes' => 'Isi berkas CV harus merupakan dokumen PDF.',
            'cv.max' => 'Ukuran berkas CV maksimal 2 MB.',
        ];
    }
}
