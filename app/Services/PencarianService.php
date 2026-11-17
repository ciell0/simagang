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
        $kataKunci = mb_strtolower(trim($keyword)); // Mengubah kata kunci menjadi huruf kecil dan menghapus spasi di awal/akhir

        if ($kataKunci === '') {
            return collect($items); // Jika kata kunci kosong, kembalikan semua item tanpa filter
        }

        // filter() digunakan untuk memfilter item berdasarkan kondisi tertentu
        // function($item) use ($kataKunci) { ... } adalah closure (fungsi anonim) yang digunakan untuk memfilter item berdasarkan kata kunci
        return collect($items)->filter(function ($item) use ($kataKunci) {
            $nilai = $this->normalisasi($item); // normalisasi digunakan untuk mengubah item menjadi string yang dapat dicocokkan dengan kata kunci

            return str_contains($nilai, $kataKunci);
        });
    }

    // fungsi normalisasi digunakan untuk mengubah item menjadi string yang dapat dicocokkan dengan kata kunci
    private function normalisasi(mixed $item): string
    {
        // Jika item adalah array, ambil nilai dari kunci tertentu dan gabungkan menjadi satu string
        if (is_array($item)) {
            $nilai = [
                $item['nama'] ?? null, // ambil nilai dari kunci 'nama', jika tidak ada maka gunakan null
                $item['judul'] ?? null,
                $item['divisi'] ?? null,
                $item['email'] ?? null,
            ];

            // array_filter digunakan untuk menghapus nilai null atau string kosong dari array $nilai
            // implode digunakan untuk menggabungkan elemen array menjadi satu string dengan spasi sebagai pemisah
            // strtolower digunakan untuk mengubah string menjadi huruf kecil
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
