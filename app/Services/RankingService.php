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
            ->sortByDesc(fn (array $row) => [
                $row['skor'],
                (float) ($row['pendaftaran']->ipk ?? 0.0),
                (float) ($row['pendaftaran']->pengalaman_organisasi ?? 0.0),
            ])
            ->values()
            ->all();
    }
}
