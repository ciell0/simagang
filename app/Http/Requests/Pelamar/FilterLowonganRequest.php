<?php

namespace App\Http\Requests\Pelamar;

use Illuminate\Foundation\Http\FormRequest;

class FilterLowonganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'pelamar';
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'divisi' => ['nullable', 'string', 'max:100'],
            'q' => ['nullable', 'string', 'max:100'],
        ];
    }
}
