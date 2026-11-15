<?php

namespace App\Services;

use Illuminate\Support\Collection;

class PencarianService
{
    /**
     * Mencari kandidat dengan pencocokan teks linear.
     * Kompleksitas: O(n * m) untuk setiap item dan panjang teks yang dicocokkan.
     */
    public function cari(iterable $items, string $keyword): Collection
    {
        $kataKunci = mb_strtolower(trim($keyword));

        if ($kataKunci === '') {
            return collect($items);
        }

        return collect($items)->filter(function ($item) use ($kataKunci) {
            $nilai = $this->normalisasi($item);

            return str_contains($nilai, $kataKunci);
        });
    }

    private function normalisasi(mixed $item): string
    {
        if (is_array($item)) {
            $nilai = [
                $item['nama'] ?? null,
                $item['judul'] ?? null,
                $item['divisi'] ?? null,
                $item['email'] ?? null,
            ];

            return strtolower(implode(' ', array_filter($nilai, fn ($value) => is_string($value) && $value !== '')));
        }

        if (is_object($item) && method_exists($item, 'toArray')) {
            return strtolower(implode(' ', array_filter([
                $item->nama ?? null,
                $item->judul ?? null,
                $item->divisi ?? null,
                $item->email ?? null,
            ], fn ($value) => is_string($value) && $value !== '')));
        }

        return strtolower((string) $item);
    }
}
