<?php

namespace App\Services;

use App\Models\Pendaftaran;

class RankingService
{
    /**
     * Mengurutkan pendaftar dari skor tertinggi ke terendah.
     * Kompleksitas: O(n log n) karena memakai pengurutan koleksi.
     */
    public function urutkan(iterable $items): array
    {
        $service = app(SkorService::class);

        return collect($items)
            ->map(function ($item) use ($service) {
                $pendaftaran = $item instanceof Pendaftaran ? $item : ($item['pendaftaran'] ?? $item);

                return [
                    'pendaftaran' => $pendaftaran,
                    'skor' => (float) ($pendaftaran->skor ?? $service->hitung($pendaftaran)),
                ];
            })
            // Urutkan berdasarkan skor, IPK, dan pengalaman organisasi secara menurun
            // sortByDesc fungsi bawaan Laravel untuk mengurutkan koleksi secara menurun (tinggi ke rendah)
            ->sortByDesc(fn (array $row) => [ // digunakan untuk mengambil nilai dari array $row untuk diurutkan
                $row['skor'], // pertimbangan pertama
                (float) ($row['pendaftaran']->ipk ?? 0.0), // pertimbangan kedua (?? 0.0 --> jika null maka gunakan 0.0)
                (float) ($row['pendaftaran']->pengalaman_organisasi ?? 0.0), // pertimbangan ketiga
            ])
            ->values()
            ->all();
    }
}
